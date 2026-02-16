<?php

class ScheduleResponse extends RestResponse
{
    public $daysVisible;
    public $id;
    public $isDefault;
    public $name;
    public $timezone;
    public $weekdayStart;
    public $icsUrl;
    public $availabilityStart;
    public $availabilityEnd;
    public $maxResourcesPerReservation;
    public $totalConcurrentReservationsAllowed;

    /**
     * @var array<int, SchedulePeriodResponse[]>
     */
    public $periods = [0 => [], 1 => [], 2 => [], 3 => [], 4 => [], 5 => [], 6 => []];

    public function __construct(IRestServer $server, Schedule $schedule, IScheduleLayout $layout)
    {
        $this->daysVisible = $schedule->GetDaysVisible();
        $this->id = $schedule->GetId();
        $this->isDefault = $schedule->GetIsDefault();
        $this->name = $schedule->GetName();
        $this->timezone = $schedule->GetTimezone();
        $this->weekdayStart = $schedule->GetWeekdayStart();
        $this->availabilityStart = $schedule->GetAvailabilityBegin()->ToIso();
        $this->availabilityEnd = $schedule->GetAvailabilityEnd()->ToIso();
        $this->maxResourcesPerReservation = $schedule->GetMaxResourcesPerReservation();
        $this->totalConcurrentReservationsAllowed = $schedule->GetTotalConcurrentReservations();

        if ($schedule->GetIsCalendarSubscriptionAllowed()) {
            $url = new CalendarSubscriptionUrl(null, $schedule->GetPublicId(), null);
            $this->icsUrl = $url->__toString();
        }

        $layoutDate = Date::Now()->ToTimezone($server->GetSession()->Timezone);
        for ($day = 0; $day < 7; $day++) {
            $periods = $layout->GetLayout($layoutDate);
            foreach ($periods as $period) {
                $this->periods[$layoutDate->Weekday()][] = new SchedulePeriodResponse($period);
            }
            $layoutDate = $layoutDate->AddDays(1);
        }
    }

    public static function Example()
    {
        return new ExampleScheduleResponse();
    }
}

class SchedulePeriodResponse
{
    public $start;
    public $end;
    public $label;
    public $startTime;
    public $endTime;
    public $isReservable;

    public function __construct(SchedulePeriod $schedulePeriod)
    {
        $this->start = $schedulePeriod->BeginDate()->ToIso();
        $this->end = $schedulePeriod->EndDate()->ToIso();
        $this->label = $schedulePeriod->Label();
        $this->startTime = $schedulePeriod->Begin()->ToString();
        $this->endTime = $schedulePeriod->End()->ToString();
        $this->isReservable = $schedulePeriod->IsReservable();
    }

    public static function Example()
    {
        return new ExampleSchedulePeriodResponse();
    }
}

class ExampleScheduleResponse extends ScheduleResponse
{
    public function __construct()
    {
        $this->daysVisible = 5;
        $this->id = 123;
        $this->isDefault = true;
        $this->name = 'schedule name';
        $this->timezone = 'timezone_name';
        $this->weekdayStart = 0;
        $this->icsUrl = 'webcal://url/to/calendar';
        $this->availabilityStart = Date::Now()->ToIso();
        $this->availabilityEnd = Date::Now()->ToIso();
        $this->maxResourcesPerReservation = 10;
        $this->totalConcurrentReservationsAllowed = 0;

        foreach (DayOfWeek::Days() as $day) {
            $this->periods[$day] = [SchedulePeriodResponse::Example()];
        }
    }
}

class ExampleSchedulePeriodResponse extends SchedulePeriodResponse
{
    public function __construct()
    {
        $d = Date::Now();
        $date = $d->ToIso();
        $this->start = $date;
        $this->end = $date;
        $this->label = 'label';
        $this->startTime = $d->GetTime()->ToString();
        $this->endTime = $d->GetTime()->ToString();
        $this->isReservable = true;
    }
}
