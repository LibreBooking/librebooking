<?php

class CalendarReservation
{
    /**
     * @var Date
     */
    public $StartDate;

    /**
     * @var Date
     */
    public $EndDate;

    /**
     * @var string
     */
    public $ResourceName;

    /**
    * @var int
    */
    public $ResourceId;

    /**
     * @var string
     */
    public $ReferenceNumber;

    /**
     * @var string
     */
    public $Title;

    /**
     * @var string
     */
    public $Description;

    /**
     * @var bool
     */
    public $Invited;

    /**
     * @var bool
     */
    public $Participant;

    /**
     * @var bool
     */
    public $Owner;

    /**
     * @var string
     */
    public $OwnerName;

    /**
     * @var string
     */
    public $OwnerFirst;

    /**
     * @var string
     */
    public $OwnerLast;

    /**
     * @var string
     */
    public $DisplayTitle;

    /**
     * @var string
     */
    public $Color;

    /**
     * @var string
     */
    public $TextColor;

    /**
     * @var string
     */
    public $Class;

    /**
     * @var bool
     */
    public $IsEditable;

    /**
     * @var int
     */
    public $ScheduleId;

    private function __construct(Date $startDate, Date $endDate, $resourceName, $referenceNumber)
    {
        $this->StartDate = $startDate;
        $this->EndDate = $endDate;
        $this->ResourceName = $resourceName;
        $this->ReferenceNumber = $referenceNumber;
    }

    /**
     * @param $reservations array|ReservationItemView[]
     * @param $timezone string
     * @param $user UserSession
     * @param $groupSeriesByResource bool
     * @return array|CalendarReservation[]
     */
    public static function FromViewList($reservations, $timezone, $user, $groupSeriesByResource = false)
    {
        $knownSeries = [];
        $results = [];

        foreach ($reservations as $reservation) {
            if ($groupSeriesByResource) {
                if (array_key_exists($reservation->ReferenceNumber, $knownSeries)) {
                    continue;
                }
                $knownSeries[$reservation->ReferenceNumber] = true;
            }
            $results[] = self::FromView($reservation, $timezone, $user);
        }
        return $results;
    }

    /**
     * @param $reservation ReservationItemView
     * @param $timezone string
     * @param $user UserSession
     * @param $factory SlotLabelFactory|null
     * @return CalendarReservation
     */
    public static function FromView($reservation, $timezone, $user, $factory = null)
    {
        if ($factory == null) {
            $factory = new SlotLabelFactory($user);
        }
        $start = $reservation->StartDate->ToTimezone($timezone);
        $end = $reservation->EndDate->ToTimezone($timezone);
        $resourceName = implode(', ', $reservation->ResourceNames);
        $referenceNumber = $reservation->ReferenceNumber;

        $res = new CalendarReservation($start, $end, $resourceName, $referenceNumber);

        $res->Title = $reservation->Title;
        $res->Description = $reservation->Description;
        $res->DisplayTitle = $factory->Format($reservation, Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_MY_CALENDAR));
        $res->Invited = $reservation->UserLevelId == ReservationUserLevel::INVITEE;
        $res->Participant = $reservation->UserLevelId == ReservationUserLevel::PARTICIPANT;
        $res->Owner = $reservation->UserLevelId == ReservationUserLevel::OWNER;

        $color = $reservation->GetColor();
        if (!empty($color)) {
            $res->Color = $reservation->GetColor() . ' !important';
            $res->TextColor = $reservation->GetTextColor() . ' !important';
        }
        $res->IsEditable = $user->IsAdmin || $user->UserId == $reservation->OwnerId;

        $res->Class = self::GetClass($reservation);

        return $res;
    }

    /**
     * @static
     * @param array|ReservationItemView[] $reservations
     * @param array|BlackoutItemView[] $blackouts
     * @param array|ReservableCalendarSlot[] $availablePeriods
     * @param array|ResourceDto[] $resources
     * @param UserSession $userSession
     * @param bool $groupSeriesByResource
     * @param SlotLabelFactory|null $factory
     * @return CalendarReservation[]
     */
    public static function FromScheduleReservationList($reservations, $blackouts, $availablePeriods, $resources, UserSession $userSession, $groupSeriesByResource = false, $factory = null)
    {
        if ($factory == null) {
            $factory = new SlotLabelFactory($userSession);
        }

        $knownSeries = [];

        $resourceMap = [];
        /** @var ResourceDto $resource */
        foreach ($resources as $resource) {
            $resourceMap[$resource->GetResourceId()] = $resource->GetName();
        }

        $res = [];
        foreach ($reservations as $reservation) {
            if (!array_key_exists($reservation->ResourceId, $resourceMap)) {
                continue;
            }

            if ($groupSeriesByResource) {
                if (array_key_exists($reservation->ReferenceNumber, $knownSeries)) {
                    continue;
                }
                $knownSeries[$reservation->ReferenceNumber] = true;
            }

            $timezone = $userSession->Timezone;
            $start = $reservation->StartDate->ToTimezone($timezone);
            $end = $reservation->EndDate->ToTimezone($timezone);
            $referenceNumber = $reservation->ReferenceNumber;

            $cr = new CalendarReservation($start, $end, $resourceMap[$reservation->ResourceId], $referenceNumber);
            $cr->Title = $reservation->Title;
            $cr->OwnerName = new FullName($reservation->FirstName, $reservation->LastName);
            $cr->OwnerFirst = $reservation->FirstName;
            $cr->OwnerLast = $reservation->LastName;
            $cr->DisplayTitle = $factory->Format($reservation, Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_RESOURCE_CALENDAR));
            $color = $reservation->GetColor();
            if (!empty($color)) {
                $cr->Color = $reservation->GetColor() . ' !important';
                $cr->TextColor = $reservation->GetTextColor() . ' !important';
            }

            $cr->IsEditable = $userSession->IsAdmin || $userSession->UserId == $reservation->OwnerId;

            $cr->Class = self::GetClass($reservation);

            $res[] = $cr;
        }

        foreach ($blackouts as $blackout) {
            if (!array_key_exists($blackout->ResourceId, $resourceMap)) {
                continue;
            }

            $timezone = $userSession->Timezone;
            $start = $blackout->StartDate->ToTimezone($timezone);
            $end = $blackout->EndDate->ToTimezone($timezone);

            $cr = new CalendarReservation($start, $end, $resourceMap[$blackout->ResourceId], null);
            $cr->Title = $blackout->Title;
            $cr->DisplayTitle = $blackout->Title;
            $cr->IsEditable = false;
            $cr->Class = 'unreservable';
            $res[] = $cr;
        }

        foreach ($availablePeriods as $period) {
            $timezone = $userSession->Timezone;
            $start = $period->BeginDate()->ToTimezone($timezone);
            $end = $period->EndDate()->ToTimezone($timezone);

            $cr = new CalendarReservation($start, $end, null, null);
            $cr->IsEditable = false;
            $cr->Class = 'reservable';
            $cr->ResourceId = $period->ResourceId;
            $cr->ScheduleId = $period->ScheduleId;
            $res[] = $cr;
        }

        return $res;
    }

    private static function GetClass(ReservationItemView $reservation)
    {
        if ($reservation->RequiresApproval) {
            return 'reserved pending';
        }

        $user = ServiceLocator::GetServer()->GetUserSession();

        if ($reservation->IsUserOwner($user->UserId)) {
            return 'reserved mine';
        }

        if ($reservation->IsUserParticipating($user->UserId)) {
            return 'reserved participating';
        }

        return 'reserved';
    }

    public function AsFullCalendarEvent()
    {
        $dateFormat = Resources::GetInstance()->GetDateFormat('fullcalendar');
        return [
            'id' => $this->ReferenceNumber,
            'title' => $this->DisplayTitle,
            'start' => $this->StartDate->Format($dateFormat),
            'end' => $this->EndDate->Format($dateFormat),
            'url' => $this->GetUrl(),
            'allDay' => false,
            'backgroundColor' => $this->Color,
            'textColor' => $this->TextColor,
            'className' => $this->Class,
            'startEditable' => $this->IsEditable
        ];
    }

    /**
     * @return string
     */
    private function GetUrl()
    {
        if (!empty($this->ReferenceNumber)) {
            return sprintf('%s?rn=%s&redirect=[redirect]', Pages::RESERVATION, $this->ReferenceNumber);
        }

        if (!empty($this->ResourceId)) {
            $format = Resources::GetInstance()->GetDateFormat('url_full');
            return  sprintf('%s?rid=%s&sd=%s&ed=%s', Pages::RESERVATION, $this->ResourceId, $this->StartDate->Format($format), $this->EndDate->Format($format));
        }

        if (!empty($this->ScheduleId)) {
            $format = Resources::GetInstance()->GetDateFormat('url_full');
            return  sprintf('%s?sid=%s&sd=%s&ed=%s', Pages::RESERVATION, $this->ScheduleId, $this->StartDate->Format($format), $this->EndDate->Format($format));
        }

        return '';
    }
}

class ReservableCalendarSlot
{
    /**
     * @var SchedulePeriod
     */
    public $Period;

    /**
     * @var int
     */
    public $ResourceId;

    /**
     * @var int
     */
    public $ScheduleId;

    public function __construct(SchedulePeriod $period, $resourceId, $scheduleId)
    {
        $this->Period = $period;
        $this->ResourceId = $resourceId;
        $this->ScheduleId = $scheduleId;
    }

    public function BeginDate()
    {
        return $this->Period->BeginDate();
    }

    public function EndDate()
    {
        return $this->Period->EndDate();
    }
}
