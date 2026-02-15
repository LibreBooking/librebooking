<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/CalendarSubscriptionUrl.php');

interface ISchedule
{
    public function GetId();

    public function GetName();

    public function GetIsDefault();

    public function GetWeekdayStart();

    public function GetDaysVisible();

    public function GetTimezone();

    public function GetLayoutId();

    public function GetIsCalendarSubscriptionAllowed();

    public function GetPublicId();

    public function GetAdminGroupId();

    /**
     * @return Date
     */
    public function GetAvailabilityBegin();

    /**
     * @return Date
     */
    public function GetAvailabilityEnd();

    /**
     * @return DateRange
     */
    public function GetAvailability();

    /**
     * @return bool
     */
    public function HasAvailability();

    /**
     * @return int
     */
    public function GetDefaultStyle(): int;
}

class Schedule implements ISchedule
{
    protected $_id;
    protected $_name;
    protected $_isDefault;
    protected $_weekdayStart;
    protected $_daysVisible;
    protected $_timezone;
    protected $_layoutId;
    protected $_isCalendarSubscriptionAllowed = false;
    protected $_publicId;
    protected $_adminGroupId;
    protected $_availabilityBegin;
    protected $_availabilityEnd;
    protected ScheduleStyle $_defaultStyle;
    protected $_layoutType;
    protected $_totalConcurrentReservations = 0;
    protected $_maxResourcesPerReservation = 0;

    public const Today = 100;

    public function __construct(
        $id,
        $name,
        $isDefault,
        $weekdayStart,
        $daysVisible,
        $timezone = null,
        $layoutId = null
    ) {
        $this->_id = $id;
        $this->_name = $name;
        $this->_isDefault = $isDefault;
        $this->_weekdayStart = $weekdayStart;
        $this->_daysVisible = $daysVisible;
        $this->_timezone = empty($timezone) ? Configuration::Instance()->GetDefaultTimezone() : $timezone;
        $this->_layoutId = $layoutId;
        $this->_availabilityBegin = new NullDate();
        $this->_availabilityEnd = new NullDate();
        $this->_defaultStyle = ScheduleStyle::Standard;
        $this->_layoutType = ScheduleLayout::Standard;
        $this->_totalConcurrentReservations = 0;
        $this->_maxResourcesPerReservation = 0;
    }

    public function GetId()
    {
        return $this->_id;
    }

    public function SetId($value)
    {
        $this->_id = $value;
    }

    public function GetName()
    {
        return $this->_name;
    }

    public function SetName($value)
    {
        $this->_name = $value;
    }

    public function GetIsDefault()
    {
        return $this->_isDefault;
    }

    public function SetIsDefault($value)
    {
        $this->_isDefault = $value;
    }

    public function GetWeekdayStart()
    {
        return $this->_weekdayStart;
    }

    public function SetWeekdayStart($value)
    {
        $this->_weekdayStart = $value;
    }

    public function GetDaysVisible()
    {
        return $this->_daysVisible;
    }

    public function SetDaysVisible($value)
    {
        $this->_daysVisible = $value;
    }

    public function GetTimezone()
    {
        return $this->_timezone;
    }

    public function GetLayoutId()
    {
        return $this->_layoutId;
    }

    public function SetTimezone($timezone)
    {
        $this->_timezone = $timezone;
    }

    protected function SetIsCalendarSubscriptionAllowed($isAllowed)
    {
        $this->_isCalendarSubscriptionAllowed = $isAllowed;
    }

    public function GetIsCalendarSubscriptionAllowed()
    {
        return (bool)$this->_isCalendarSubscriptionAllowed;
    }

    protected function SetPublicId($publicId)
    {
        $this->_publicId = $publicId;
    }

    public function GetPublicId()
    {
        return $this->_publicId;
    }

    public function EnableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(true);
        if (empty($this->_publicId)) {
            $this->SetPublicId(BookedStringHelper::Random(20));
        }
    }

    public function DisableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(false);
    }

    /**
     * @param int|null $adminGroupId
     */
    public function SetAdminGroupId($adminGroupId)
    {
        if (empty($adminGroupId)) {
            $adminGroupId = null;
        }
        $this->_adminGroupId = $adminGroupId;
    }

    /**
     * @return int|null
     */
    public function GetAdminGroupId()
    {
        return $this->_adminGroupId;
    }

    /**
     * @return bool
     */
    public function HasAdminGroup()
    {
        return !empty($this->_adminGroupId);
    }

    public function SetAvailableAllYear()
    {
        $this->_availabilityBegin = new NullDate();
        $this->_availabilityEnd = new NullDate();
    }

    public function SetAvailability(Date $start, Date $end)
    {
        $this->_availabilityBegin = $start->ToTimezone($this->_timezone);
        $this->_availabilityEnd = $end->ToTimezone($this->_timezone);
    }

    /**
     * @return Date
     */
    public function GetAvailabilityBegin()
    {
        if ($this->_availabilityBegin == null) {
            return new NullDate();
        }

        return $this->_availabilityBegin;
    }

    /**
     * @return Date
     */
    public function GetAvailabilityEnd()
    {
        if ($this->_availabilityEnd == null) {
            return new NullDate();
        }

        return $this->_availabilityEnd;
    }

    /**
     * @return DateRange
     */
    public function GetAvailability()
    {
        return new DateRange($this->GetAvailabilityBegin(), $this->GetAvailabilityEnd());
    }

    /**
     * @return bool
     */
    public function HasAvailability()
    {
        return $this->GetAvailabilityBegin()->ToString() != '' && $this->GetAvailabilityEnd()->ToString() != '';
    }

    public function GetDefaultStyle(): int
    {
        return $this->_defaultStyle->value;
    }

    public function SetDefaultStyle(int|ScheduleStyle $defaultDisplay): void
    {
        if ($defaultDisplay instanceof ScheduleStyle) {
            $this->_defaultStyle = $defaultDisplay;
            return;
        }

        $styleValue = is_numeric($defaultDisplay) ? intval($defaultDisplay) : ScheduleStyle::Standard->value;
        $this->_defaultStyle = ScheduleStyle::tryFrom($styleValue) ?? ScheduleStyle::Standard;
    }

    /**
     * @static
     * @return Schedule
     */
    public static function null()
    {
        return new Schedule(null, null, false, null, null);
    }

    /**
     * @static
     * @param array $row
     * @return Schedule
     */
    public static function FromRow($row)
    {
        $schedule = new Schedule(
            $row[ColumnNames::SCHEDULE_ID],
            $row[ColumnNames::SCHEDULE_NAME],
            $row[ColumnNames::SCHEDULE_DEFAULT],
            $row[ColumnNames::SCHEDULE_WEEKDAY_START],
            $row[ColumnNames::SCHEDULE_DAYS_VISIBLE],
            $row[ColumnNames::TIMEZONE_NAME],
            $row[ColumnNames::LAYOUT_ID]
        );

        $schedule->WithSubscription($row[ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION]);
        $schedule->WithPublicId($row[ColumnNames::PUBLIC_ID]);
        $schedule->SetAdminGroupId($row[ColumnNames::SCHEDULE_ADMIN_GROUP_ID]);
        $schedule->SetAvailability(Date::FromDatabase($row[ColumnNames::SCHEDULE_AVAILABLE_START_DATE]), Date::FromDatabase($row[ColumnNames::SCHEDULE_AVAILABLE_END_DATE]));
        $schedule->SetDefaultStyle($row[ColumnNames::SCHEDULE_DEFAULT_STYLE]);
        if (in_array(ColumnNames::LAYOUT_TYPE, $row)) {
            $schedule->SetLayoutType($row[ColumnNames::LAYOUT_TYPE]);
        }
        $schedule->SetTotalConcurrentReservations($row[ColumnNames::TOTAL_CONCURRENT_RESERVATIONS]);
        $schedule->SetMaxResourcesPerReservation($row[ColumnNames::MAX_RESOURCES_PER_RESERVATION]);
        return $schedule;
    }

    /**
     * @param bool $allowSubscription
     * @internal
     */
    public function WithSubscription($allowSubscription)
    {
        $this->SetIsCalendarSubscriptionAllowed($allowSubscription);
    }

    /**
     * @param string $publicId
     * @internal
     */
    public function WithPublicId($publicId)
    {
        $this->SetPublicId($publicId);
    }

    public function GetSubscriptionUrl()
    {
        return new CalendarSubscriptionUrl(null, $this->GetPublicId(), null);
    }

    /**
     * @param $layoutType int
     */
    public function SetLayoutType($layoutType)
    {
        $this->_layoutType = $layoutType;
    }

    /**
     * @return int
     */
    public function GetLayoutType()
    {
        return $this->_layoutType;
    }

    /**
     * @return bool
     */
    public function HasCustomLayout()
    {
        return $this->_layoutType == ScheduleLayout::Custom;
    }

    /**
     * @param $totalConcurrent int
     */
    public function SetTotalConcurrentReservations($totalConcurrent)
    {
        $total = intval($totalConcurrent);
        $this->_totalConcurrentReservations = min(65535, max($total, 0));
    }

    /**
     * @return int
     */
    public function GetTotalConcurrentReservations()
    {
        return $this->_totalConcurrentReservations;
    }

    /**
     * @return bool
     */
    public function EnforceConcurrentReservationMaximum()
    {
        return $this->_totalConcurrentReservations > 0;
    }

    /**
     * @param $max int
     */
    public function SetMaxResourcesPerReservation($max)
    {
        $total = intval($max);
        $this->_maxResourcesPerReservation = min(65535, max($total, 0));
    }

    /**
     * @return int
     */
    public function GetMaxResourcesPerReservation()
    {
        return $this->_maxResourcesPerReservation;
    }

    /**
     * @return bool
     */
    public function EnforceMaxResourcesPerReservation()
    {
        return $this->_maxResourcesPerReservation > 0;
    }
}

class NullSchedule extends Schedule
{
    public function __construct()
    {
        parent::__construct(0, null, false, 0, 7);
    }
}


enum ScheduleStyle: int
{
    case Standard = 0;
    case Wide = 1;
    case Tall = 2;
    case CondensedWeek = 3;
}
