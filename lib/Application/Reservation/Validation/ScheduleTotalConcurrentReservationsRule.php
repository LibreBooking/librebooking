<?php

require_once(ROOT_DIR . 'Domain/Access/ScheduleRepository.php');
require_once(ROOT_DIR . 'Domain/Access/ReservationViewRepository.php');

class ScheduleTotalConcurrentReservationsRule implements IReservationValidationRule
{
    /**
     * @var IReservationViewRepository
     */
    protected $reservationRepository;

    /**
     * @var IScheduleRepository
     */
    protected $scheduleRepository;
    private $timezone;

    public function __construct(IScheduleRepository $scheduleRepository, IReservationViewRepository $reservationRepository, $timezone)
    {
        $this->reservationRepository = $reservationRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->timezone = $timezone;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        $schedule = $this->scheduleRepository->LoadById($reservationSeries->ScheduleId());
        if (!$schedule->EnforceConcurrentReservationMaximum()) {
            return new ReservationRuleResult();
        }

        $isValid = true;
        $invalidDates = [];
        $totalConcurrentReservations = $schedule->GetTotalConcurrentReservations();

        foreach ($reservationSeries->Instances() as $instance) {
            $concurrent = 0;
            if ($reservationSeries->IsMarkedForDelete($instance->ReservationId())) {
                continue;
            }

            $reservations = $this->reservationRepository->GetReservations(
                $instance->StartDate(),
                $instance->EndDate(),
                null,
                null,
                [$reservationSeries->ScheduleId()]
            );

            foreach ($reservations as $existingItem) {
                if ($existingItem->ReferenceNumber == $instance->ReferenceNumber()) {
                    continue;
                }

                if ($existingItem->BufferedTimes()->Overlaps($instance->Duration())) {
                    $concurrent++;
                }
            }


            if ($concurrent + count($reservationSeries->AllResourceIds()) > $totalConcurrentReservations) {
                $isValid = false;
                $invalidDates[] = $instance->StartDate();
            }
        }

        return new ReservationRuleResult($isValid, $this->GetErrorMessage($invalidDates, $totalConcurrentReservations));
    }

    /**
     * @param $invalidDates Date[]
     * @param $totalConcurrentReservationLimit int
     * @return string
     */
    private function GetErrorMessage($invalidDates, $totalConcurrentReservationLimit)
    {
        $uniqueDates = array_unique($invalidDates);
        sort($uniqueDates);
        $resources = Resources::GetInstance();
        $format = $resources->GetDateFormat(ResourceKeys::DATE_GENERAL);
        $formatted = [];
        foreach ($uniqueDates as $d) {
            $formatted[] = $d->ToTimezone($this->timezone)->Format($format);
        }

        $datesAsString = implode(",", $formatted);

        $errorString = new StringBuilder();
        $errorString->AppendLine(Resources::GetInstance()->GetString('ScheduleTotalReservationsError', [$totalConcurrentReservationLimit]));
        $errorString->Append($datesAsString);
        return $errorString->ToString();
    }
}
