<?php

class ResourceCrossDayRule implements IReservationValidationRule
{
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(IScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        foreach ($reservationSeries->AllResources() as $resource) {
            if (!$resource->GetAllowMultiday()) {
                $schedule = $this->scheduleRepository->LoadById($reservationSeries->ScheduleId());
                $tz = $schedule->GetTimezone();
                $start = $reservationSeries->CurrentInstance()->StartDate()->ToTimezone($tz);
                $end = $reservationSeries->CurrentInstance()->EndDate()->ToTimezone($tz);
                $isAllowed = $start->DateEquals($end)
                    || $this->EndsWithinOvernightPeriod($reservationSeries->ScheduleId(), $start, $end);

                return new ReservationRuleResult($isAllowed, Resources::GetInstance()->GetString('MultiDayRule', $resource->GetName()));
            }
        }

        return new ReservationRuleResult();
    }

    /**
     * A reservation which only crosses midnight inside a period that the
     * schedule layout itself runs past midnight (for example 22:00 - 06:00)
     * is not treated as a multi-day reservation.
     *
     * @param int $scheduleId
     * @param Date $start in the schedule timezone
     * @param Date $end in the schedule timezone
     */
    private function EndsWithinOvernightPeriod($scheduleId, Date $start, Date $end): bool
    {
        $midnight = $start->GetDate()->AddDays(1);
        $layout = $this->scheduleRepository->GetLayout($scheduleId, new ScheduleLayoutFactory($start->Timezone()));
        $period = $layout->GetPeriod($midnight);

        return $period != null
            && $period->IsReservable()
            && $period->BeginDate()->LessThan($midnight)
            && !$end->GreaterThan($period->EndDate());
    }
}
