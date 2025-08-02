<?php

class iCalendarReservationView
{
    public $Classification;
    public $DateCreated;
    public $DateEnd;
    public $DateStart;
    public $Description;
    public $Organizer;
    public $OrganizerEmail;
    public $RecurRule;
    public $ReferenceNumber;
    public $Summary;
    public $ReservationUrl;
    public $Location;
    public $StartReminder;
    public $EndReminder;
    public $LastModified;
    public $IsPending;
    public $ExtraIcalLines;

    /**
     * @var ExportFactory
     */
    private $ExportFactory;

    /**
     * @var ReservationItemView
     */
    public $ReservationItemView;

    /**
     * @param ReservationItemView $res
     * @param UserSession $currentUser
     * @param IPrivacyFilter $privacyFilter
     * @param string|null $summaryFormat
     */
    public function __construct($res, UserSession $currentUser, IPrivacyFilter $privacyFilter, $summaryFormat = null)
    {
        if ($summaryFormat == null) {
            $summaryFormat = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_ICS_SUMMARY);
        }
        $factory = new SlotLabelFactory($currentUser);
        $this->ReservationItemView = $res;
        $canViewUser = $privacyFilter->CanViewUser($currentUser, $res, $res->OwnerId);
        $canViewDetails = $privacyFilter->CanViewDetails($currentUser, $res, $res->OwnerId);

        $this->ExportFactory = PluginManager::Instance()->LoadExport();

        $privateNotice = 'Private';

        $this->Classification = method_exists($this->ExportFactory, 'GetIcalendarClassification') ? $this->ExportFactory->GetIcalendarClassification($res) : 'PUBLIC';
        if ($res->DateCreated){
                $this->DateCreated = $res->DateCreated;
        }
        else $this->DateCreated = Date::Now();

        $this->DateEnd = $res->EndDate;
        $this->DateStart = $res->StartDate;
        $this->Description =  $canViewDetails ? $factory->Format($res, $summaryFormat) : $privateNotice;
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->Organizer = $canViewUser ? $fullName->__toString() : $privateNotice;
        $this->OrganizerEmail = $canViewUser ? $res->OwnerEmailAddress : $privateNotice;
        $this->RecurRule = $this->CreateRecurRule($res);
        $this->ReferenceNumber = $res->ReferenceNumber;
        $this->Summary = $canViewDetails ? $res->Title : $privateNotice;
        $this->ReservationUrl = sprintf(
            "%s/%s?%s=%s",
            Configuration::Instance()->GetScriptUrl(),
            Pages::RESERVATION,
            QueryStringKeys::REFERENCE_NUMBER,
            $res->ReferenceNumber
        );
        $this->Location = $res->ResourceName;

        $this->StartReminder = $res->StartReminder;
        $this->EndReminder = $res->EndReminder;
        $this->LastModified = empty($res->ModifiedDate) || $res->ModifiedDate->ToString() == '' ? $this->DateCreated : $res->ModifiedDate;
        $this->IsPending = $res->RequiresApproval;

        if ($res->OwnerId == $currentUser->UserId) {
            $this->OrganizerEmail = str_replace('@', '-noreply@', $res->OwnerEmailAddress);
        }

        $this->ExtraIcalLines = method_exists($this->ExportFactory, 'GetIcalendarExtraLines') ? $this->ExportFactory->GetIcalendarExtraLines($res) : null;
    }

    /**
     * @param ReservationItemView $res
     * @return null|string
     */
    private function CreateRecurRule($res)
    {
        if (is_a($res, 'ReservationItemView')) {
            // don't populate the recurrence rule when a list of reservation is being exported
            return null;
        }
        ### !!!  THIS DOES NOT WORK BECAUSE EXCEPTIONS TO RECURRENCE RULES ARE NOT PROPERLY HANDLED !!!
        ### see bug report http://php.brickhost.com/forums/index.php?topic=11450.0

        if (empty($res->RepeatType) || $res->RepeatType == RepeatType::None) {
            return null;
        }

        $freqMapping = [RepeatType::Daily => 'DAILY', RepeatType::Weekly => 'WEEKLY', RepeatType::Monthly => 'MONTHLY', RepeatType::Yearly => 'YEARLY'];
        $freq = $freqMapping[$res->RepeatType];
        $interval = $res->RepeatInterval;
        $format = Resources::GetInstance()->GetDateFormat('ical');
        $end = $res->RepeatTerminationDate->SetTime($res->EndDate->GetTime())->Format($format);
        $rrule = sprintf('FREQ=%s;INTERVAL=%s;UNTIL=%s', $freq, $interval, $end);

        if ($res->RepeatType == RepeatType::Monthly) {
            if ($res->RepeatMonthlyType == RepeatMonthlyType::DayOfMonth) {
                $rrule .= ';BYMONTHDAY=' . $res->StartDate->Day();
            }
        }

        if (!empty($res->RepeatWeekdays)) {
            $dayMapping = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
            $days = '';
            foreach ($res->RepeatWeekdays as $weekDay) {
                $days .= ($dayMapping[$weekDay] . ',');
            }
            $days = substr($days, 0, -1);
            $rrule .= (';BYDAY=' . $days);
        }

        return $rrule;
    }
}
