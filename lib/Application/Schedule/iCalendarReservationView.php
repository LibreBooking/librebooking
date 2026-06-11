<?php

class iCalendarReservationViewOptions
{
    private ?bool $canViewUser = null;
    private ?bool $canViewDetails = null;
    private ?UserSession $formattingUser = null;
    private bool $formatWithoutVisibilityChecks = false;
    private bool $respectPublicReservationVisibility = true;

    public static function Default(): static
    {
        return new iCalendarReservationViewOptions();
    }

    public static function ForAnonymousSubscription(): static
    {
        $options = new iCalendarReservationViewOptions();
        $options->formatWithoutVisibilityChecks = true;
        return $options;
    }

    public static function ForUserSubscription(UserSession $subscribedUser): static
    {
        $options = new iCalendarReservationViewOptions();
        $options->canViewUser = true;
        $options->canViewDetails = true;
        $options->formattingUser = $subscribedUser;
        $options->formatWithoutVisibilityChecks = true;
        $options->respectPublicReservationVisibility = false;
        return $options;
    }

    public function CanViewUser(UserSession $currentUser, IPrivacyFilter $privacyFilter, ReservationItemView $reservation): bool
    {
        if ($this->canViewUser !== null) {
            return $this->canViewUser;
        }

        return $privacyFilter->CanViewUser($currentUser, $reservation, $reservation->OwnerId);
    }

    public function CanViewDetails(UserSession $currentUser, IPrivacyFilter $privacyFilter, ReservationItemView $reservation): bool
    {
        if ($this->canViewDetails !== null) {
            return $this->canViewDetails;
        }

        return $privacyFilter->CanViewDetails($currentUser, $reservation, $reservation->OwnerId);
    }

    public function FormattingUser(UserSession $currentUser): UserSession
    {
        return $this->formattingUser ?? $currentUser;
    }

    public function FormatWithoutVisibilityChecks(): bool
    {
        return $this->formatWithoutVisibilityChecks;
    }

    public function RespectPublicReservationVisibility(): bool
    {
        return $this->respectPublicReservationVisibility;
    }
}

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
     * @param iCalendarReservationViewOptions|null $options
     */
    public function __construct($res, UserSession $currentUser, IPrivacyFilter $privacyFilter, $summaryFormat = null, $options = null)
    {
        if ($summaryFormat == null) {
            $summaryFormat = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_ICS_SUMMARY);
        }
        $options = $options ?? iCalendarReservationViewOptions::Default();
        $formattingUser = $options->FormattingUser($currentUser);
        $factory = new SlotLabelFactory($formattingUser);
        $this->ReservationItemView = $res;
        $canViewUser = $options->CanViewUser($currentUser, $privacyFilter, $res);
        $canViewDetails = $options->CanViewDetails($currentUser, $privacyFilter, $res);

        if ($options->RespectPublicReservationVisibility() && !$this->CanViewPublicReservations($currentUser)) {
            $canViewUser = false;
            $canViewDetails = false;
        }

        $this->ExportFactory = PluginManager::Instance()->LoadExport();

        $privateNotice = 'Private';

        $this->Classification = method_exists($this->ExportFactory, 'GetIcalendarClassification') ? $this->ExportFactory->GetIcalendarClassification($res) : 'PUBLIC';
        if ($res->DateCreated) {
            $this->DateCreated = $res->DateCreated;
        } else {
            $this->DateCreated = Date::Now();
        }

        $this->DateEnd = $res->EndDate;
        $this->DateStart = $res->StartDate;
        $this->Summary = $canViewDetails ? self::toRfc5545Text($factory->Format($res, $summaryFormat, skipVisibilityChecks: $options->FormatWithoutVisibilityChecks())) : $privateNotice;
        $this->Description = $canViewDetails ? self::toRfc5545Text($res->Description ?? '') : $privateNotice;
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->Organizer = $canViewUser ? $fullName->__toString() : $privateNotice;
        $this->OrganizerEmail = $canViewUser ? $res->OwnerEmailAddress : $privateNotice;
        $this->RecurRule = $this->CreateRecurRule($res);
        $this->ReferenceNumber = $res->ReferenceNumber;
        $this->ReservationUrl = sprintf(
            '%s/%s?%s=%s',
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

        if ($canViewUser && !empty($res->OwnerId) && $res->OwnerId == $currentUser->UserId) {
            $this->OrganizerEmail = str_replace('@', '-noreply@', $res->OwnerEmailAddress);
        }

        $this->ExtraIcalLines = method_exists($this->ExportFactory, 'GetIcalendarExtraLines') ? $this->ExportFactory->GetIcalendarExtraLines($res) : null;
    }

    private function CanViewPublicReservations(UserSession $currentUser)
    {
        if ($currentUser->IsLoggedIn()) {
            return true;
        }

        return Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter());
    }

    /**
     * Escapes a plain-text value for use in an iCalendar TEXT property (RFC 5545 §3.3.11).
     * Backslashes, semicolons, and commas are escaped first; then newline sequences
     * (\r\n, \n, \r) are replaced with the literal two-character escape \n so that
     * calendar clients parse SUMMARY, DESCRIPTION, and any other TEXT property correctly.
     */
    private static function toRfc5545Text(string $value): string
    {
        // addcslashes prefixes each of '\', ',', and ';' with a backslash in a single pass.
        $value = addcslashes($value, '\\,;');
        return str_replace(["\r\n", "\n", "\r"], '\\n', $value);
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
