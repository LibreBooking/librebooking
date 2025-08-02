<?php

require_once(ROOT_DIR . 'Pages/Export/EmbeddedCalendarPage.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class EmbeddedCalendarPresenter
{
    /**
     * @var IEmbeddedCalendarPage
     */
    private $page;
    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;
    /**
     * @var IResourceRepository
     */
    private $resourceRepository;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(
        IEmbeddedCalendarPage $page,
        IReservationViewRepository $reservationViewRepository,
        IResourceRepository $resourceRepository,
        IScheduleRepository $scheduleRepository
    ) {
        $this->page = $page;
        $this->reservationViewRepository = $reservationViewRepository;
        $this->resourceRepository = $resourceRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function PageLoad()
    {
        try {
            $schedule = $this->GetSchedule($this->page->GetScheduleId());
            $resource = $this->GetResource($this->page->GetResourceId());
            $timezone = $this->GetTimezone($schedule);

            $scheduleId = $schedule != null ? $schedule->GetId() : null;
            $resourceId = $resource != null ? $resource->GetId() : null;

            $allSchedules = $this->scheduleRepository->GetPublicScheduleIds();
            $allResources = $this->resourceRepository->GetPublicResourceIds();

            $startDate = $this->GetStartDate($schedule);
            $endDate = $this->GetEndDate($schedule);

            $reservations = $this->GetReservations($scheduleId, $resourceId, $allSchedules, $allResources, $startDate, $endDate, $timezone);

            $this->page->BindTitleFormatter(new EmbeddedCalendarTitleFormatter($timezone, $this->GetDisplayType(), $this->page->GetTitleFormat()));
            $this->page->BindReservations($reservations, $timezone, $startDate, $endDate);
            $this->Display();
        } catch (Exception $exception) {
            Log::Error('Error loading embedded calendar %s', $exception);
            $this->page->DisplayError();
        }
    }

    /**
     * @param int $schedulePublicId
     * @return Schedule|null
     */
    private function GetSchedule($schedulePublicId)
    {
        if (!empty($schedulePublicId)) {
            $schedule = $this->scheduleRepository->LoadByPublicId($schedulePublicId);
            if ($schedule->GetIsCalendarSubscriptionAllowed()) {
                return $schedule;
            }
        }
        return null;
    }

    /**
     * @param int $resourcePublicId
     * @return BookableResource|null
     */
    private function GetResource($resourcePublicId)
    {
        if (!empty($resourcePublicId)) {
            $resource = $this->resourceRepository->LoadByPublicId($resourcePublicId);
            if ($resource->GetIsCalendarSubscriptionAllowed()) {
                return $resource;
            }
        }
        return null;
    }

    /**
     * @param Schedule|null $schedule
     * @return string
     */
    private function GetTimezone($schedule)
    {
        if ($schedule != null) {
            return $schedule->GetTimezone();
        }

        return Configuration::Instance()->GetDefaultTimezone();
    }

    /**
     * @param int $scheduleId
     * @param int $resourceId
     * @param array $allSchedules
     * @param array $allResources
     * @param Date $startDate
     * @param Date $endDate
     * @param string $timezone
     * @return ReservationListing
     */
    private function GetReservations($scheduleId, $resourceId, $allSchedules, $allResources, $startDate, $endDate, $timezone)
    {
        $listing = new ReservationListing($timezone, new DateRange($startDate, $endDate->AddDays(1)));
        $allReservations = $this->reservationViewRepository->GetReservations($startDate, $endDate->AddDays(1), null, null, $scheduleId, $resourceId, true);

        foreach ($allReservations as $r) {
            if (array_key_exists($r->ResourceId, $allResources) || array_key_exists($r->ScheduleId, $allSchedules)) {
                $listing->Add($r);
            }
        }

        return $listing;
    }

    private function GetDaysToShow()
    {
        $days = $this->page->GetDaysToShow();
        if (empty($days)) {
            return 7;
        }

        return max(1, min(30, $days));
    }

    private function Display()
    {
        $displayType = $this->GetDisplayType();

        if ($displayType == 'week') {
            $this->page->DisplayWeek();
        } elseif ($displayType == 'month') {
            $this->page->DisplayMonth();
        } else {
            $this->page->DisplayAgenda();
        }
    }

    /**
     * @param Schedule|null $schedule
     * @return int
     */
    private function GetFirstDayOfWeek($schedule)
    {
        if ($schedule == null || $schedule->GetWeekdayStart() == Schedule::Today) {
            return 0;
        }

        return $schedule->GetWeekdayStart();
    }

    /**
     * @param Schedule|null $schedule
     * @return Date
     */
    private function GetStartDate($schedule)
    {
        $displayType = $this->page->GetDisplayType();
        if ($displayType == 'week') {
            $startDay = $this->GetFirstDayOfWeek($schedule);
            $now = Date::Now()->ToTimezone($this->GetTimezone($schedule))->GetDate();

            $adjustedDays = ($startDay - $now->Weekday());

            if ($now->Weekday() < $startDay) {
                $adjustedDays = $adjustedDays - 7;
            }

            return $now->AddDays($adjustedDays);
        }
        if ($displayType == 'month') {
            $startDay = $this->GetFirstDayOfWeek($schedule);
            $timezone = $this->GetTimezone($schedule);
            $now = Date::Now()->ToTimezone($timezone);
            $firstDayOfMonth = Date::Create($now->Year(), $now->Month(), 1, 0, 0, 0, $timezone);

            if ($firstDayOfMonth->Weekday() != $startDay) {
                $adjustedDays = ($startDay - $firstDayOfMonth->Weekday());
                if ($firstDayOfMonth->Weekday() < $startDay) {
                    $adjustedDays = $adjustedDays - 7;
                }
                $firstDayOfMonth = $firstDayOfMonth->AddDays($adjustedDays);
            }

            return $firstDayOfMonth;
        }

        return Date::Now();
    }

    /**
     * @param Schedule|null $schedule
     * @return Date
     */
    private function GetEndDate($schedule)
    {
        $displayType = $this->page->GetDisplayType();
        if ($displayType == 'week') {
            return $this->GetStartDate($schedule)->AddDays(7);
        }
        if ($displayType == 'month') {
            $timezone = $this->GetTimezone($schedule);
            $now = Date::Now()->ToTimezone($timezone);
            $nextMonth = $now->AddMonths(1);
            return Date::Create($nextMonth->Year(), $nextMonth->Month(), 1, 0, 0, 0, $timezone)->AddDays(-1);
        }

        return Date::Now()->ToTimezone($this->GetTimezone($schedule))->GetDate()->AddDays($this->GetDaysToShow());
    }

    /**
     * @return string
     */
    private function GetDisplayType()
    {
        $displayType = $this->page->GetDisplayType();

        if ($displayType == 'week') {
            return 'week';
        }
        if ($displayType == 'month') {
            return 'month';
        }
        return 'agenda';
    }
}

class EmbeddedCalendarTitleFormatter
{
    private $timezone;
    private $displayType;
    private $requestedFormat;

    public function __construct($timezone, $displayType, $requestedFormat)
    {
        $this->timezone = $timezone;
        $this->displayType = $displayType;
        $this->requestedFormat = $requestedFormat;
    }

    public function Format(ReservationListItem $reservation, Date $boundDate)
    {
        if (!empty($this->requestedFormat)) {
            $format = '';
            $keys = explode(',', $this->requestedFormat);
            if (in_array('date', $keys)) {
                $format .= 'date';
            }
            if (in_array('title', $keys)) {
                $format .= 'title';
            }
            if (in_array('user', $keys)) {
                $format .= 'user';
            }
            if (in_array('resource', $keys)) {
                $format .= 'resource';
            }
        } else {
            $format = 'date';
        }
        $allowUser = !Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter());
        $allowRevervation = !Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, new BooleanConverter());

        $dateText = $this->GetDateText($reservation, $boundDate);
        $title = $reservation->GetTitle();
        $resourceName = $reservation->GetResourceName();
        $userName = $reservation->GetUserName();
        if (!$allowUser) {
            $userName = '';
        }
        if (!$allowRevervation) {
            $title = '';
        }
        $format = str_replace('date', $dateText, $format);
        $format = str_replace('title', !empty($title) ? '<br/>' . $title : '', $format);
        $format = str_replace('user', !empty($userName) ? '<br/>' . $userName : '', $format);
        $format = str_replace('resource', !empty($resourceName) ? '<br/>' . $resourceName : '', $format);

        return $format;
    }

    /**
     * @param ReservationListItem $reservation
     * @param Date $boundDate
     * @return string
     */
    private function GetDateText(ReservationListItem $reservation, Date $boundDate)
    {
        $resources = Resources::GetInstance();
        $dateText = '';

        if ($this->displayType != 'agenda') {
            if ($reservation->StartDate()->DateEquals($boundDate)) {
                $dateText .= $reservation->StartDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_time'));
            } else {
                $dateText .= $reservation->StartDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_datetime'));
            }

            if ($reservation->EndDate()->DateEquals($boundDate)) {
                $dateText .= ' - ' . $reservation->EndDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_time'));
            } else {
                $dateText .= ' - ' . $reservation->EndDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_datetime'));
            }
        } else {
            $dateText = $reservation->StartDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_time'));
            if (!$reservation->StartDate()->DateEquals($reservation->EndDate())) {
                $dateText .= ' - ' . $reservation->EndDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_datetime'));
            } else {
                $dateText .= ' - ' . $reservation->EndDate()->ToTimezone($this->timezone)->Format($resources->GetDateFormat('embedded_time'));
            }
        }
        return $dateText;
    }
}
