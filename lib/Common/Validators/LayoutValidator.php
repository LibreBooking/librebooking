<?php

class LayoutValidator extends ValidatorBase implements IValidator
{
    private const MINUTES_PER_HOUR = 60;
    private const MINUTES_PER_DAY = 1440;

    /**
     * @var string|string[]
     */
    private $reservableSlots;

    /**
     * @var string|string[]
     */
    private $blockedSlots;

    /**
     * @var bool
     */
    private $validateSingle;

    /**
     * @param string|string[] $reservableSlots
     * @param string|string[] $blockedSlots
     * @param bool $validateSingle
     */
    public function __construct($reservableSlots, $blockedSlots, $validateSingle = true)
    {
        $this->reservableSlots = $reservableSlots;
        $this->blockedSlots = $blockedSlots;
        $this->validateSingle = $validateSingle;
    }

    /**
     * @return void
     */
    public function Validate()
    {
        try {
            $this->isValid = true;

            if (!$this->validateSingle) {
                Log::Debug('Validating daily layout');
                if (count($this->reservableSlots) != DayOfWeek::NumberOfDays || count($this->blockedSlots) != DayOfWeek::NumberOfDays) {
                    $this->isValid = false;
                    return;
                }
                $layout = ScheduleLayout::ParseDaily('UTC', $this->reservableSlots, $this->blockedSlots);
            } else {
                Log::Debug('Validating single layout');
                $layout = ScheduleLayout::Parse('UTC', $this->reservableSlots, $this->blockedSlots);
            }

            $this->isValid = $this->validateSingle
                ? $this->CoversWholeDay($layout->GetSlots())
                : $this->CoversWholeWeek($layout);
        } catch (Throwable $ex) {
            Log::Error('Error during LayoutValidator %s', $ex);
            $this->isValid = false;
        }
    }

    /**
     * A single layout repeats every day, so its periods have to follow on from
     * each other around the clock. One of them may run past midnight (for
     * example 22:00 - 06:00), in which case the day starts where that period ends.
     *
     * @param LayoutPeriod[] $periods sorted by start time
     */
    private function CoversWholeDay(array $periods): bool
    {
        $count = count($periods);
        if ($count == 0) {
            Log::Debug('Layout has no slots');
            return false;
        }

        $totalMinutes = 0;
        for ($i = 0; $i < $count; $i++) {
            $period = $periods[$i];
            $next = $periods[($i + 1) % $count];

            $minutes = $this->DurationInMinutes($period);
            if ($minutes === null || $this->MinuteOfDay($period->End) != $this->MinuteOfDay($next->Start)) {
                return false;
            }
            $totalMinutes += $minutes;
        }

        return $totalMinutes == self::MINUTES_PER_DAY;
    }

    /**
     * Each day's periods have to follow on from each other and reach midnight.
     * Only the last period of a day may run past midnight; the following day
     * then has to start where that period ends rather than at midnight.
     */
    private function CoversWholeWeek(ILayoutCreation $layout): bool
    {
        $days = DayOfWeek::Days();

        foreach ($days as $index => $day) {
            $periods = $layout->GetSlots($day);
            $count = count($periods);
            if ($count == 0) {
                Log::Debug('Layout has no slots');
                return false;
            }

            for ($i = 0; $i < $count - 1; $i++) {
                $endsLater = $this->MinuteOfDay($periods[$i]->End) > $this->MinuteOfDay($periods[$i]->Start);
                $isFollowed = $this->MinuteOfDay($periods[$i]->End) == $this->MinuteOfDay($periods[$i + 1]->Start);
                if (!$endsLater || !$isFollowed) {
                    return false;
                }
            }

            $last = $periods[$count - 1];
            $reachesMidnight = $this->MinuteOfDay($last->End) <= $this->MinuteOfDay($last->Start);
            if (!$reachesMidnight || $this->DurationInMinutes($last) === null) {
                Log::Debug('Last slot does not reach midnight');
                return false;
            }

            $nextDay = $days[($index + 1) % count($days)];
            $nextDayPeriods = $layout->GetSlots($nextDay);
            if (count($nextDayPeriods) == 0 || $this->MinuteOfDay($nextDayPeriods[0]->Start) != $this->MinuteOfDay($last->End)) {
                Log::Debug('Next day does not start where the last slot ends');
                return false;
            }
        }

        return true;
    }

    private function MinuteOfDay(Time $time): int
    {
        return $time->Hour() * self::MINUTES_PER_HOUR + $time->Minute();
    }

    /**
     * @return int|null length of the period, or null when the period is not valid.
     * A period ending at or before its start time ends on the following day.
     */
    private function DurationInMinutes(LayoutPeriod $period): ?int
    {
        $start = $this->MinuteOfDay($period->Start);
        $end = $this->MinuteOfDay($period->End);

        if ($start == $end) {
            // only midnight to midnight can cover a whole day
            return $start == 0 ? self::MINUTES_PER_DAY : null;
        }

        return $end > $start ? $end - $start : $end - $start + self::MINUTES_PER_DAY;
    }
}
