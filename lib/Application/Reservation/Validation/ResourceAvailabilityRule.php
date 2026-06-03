<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/ResourceAvailability.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationConflictIdentifier.php');

class ResourceAvailabilityRule implements IReservationValidationRule
{
    /**
     * @var string
     */
    protected $timezone;

    /**
     * @var ReservationConflictIdentifier
     */
    private $conflictIdentifier;

    public function __construct(IReservationConflictIdentifier $conflictIdentifier, $timezone)
    {
        $this->timezone = $timezone;
        $this->conflictIdentifier = $conflictIdentifier;
    }

    public function Validate($reservationSeries, $retryParameters = null)
    {
        $conflicts = $this->conflictIdentifier->GetConflicts($reservationSeries);
        $shouldSkipConflicts = ReservationRetryParameter::GetValue(
            ReservationRetryParameter::$SKIP_CONFLICTS,
            $retryParameters,
            new BooleanConverter()
        ) == true;

        $skippedConflicts = 0;
        if ($shouldSkipConflicts) {
            foreach ($conflicts->Conflicts() as $conflict) {
                Log::Debug(
                    'Skipping conflicting reservation. Reference number %s conflicts with existing %s with id %s on %s',
                    $conflict->Reservation->ReferenceNumber(),
                    get_class($conflict->Conflict),
                    $conflict->Conflict->GetId(),
                    $conflict->Reservation->StartDate()
                );

                $skipped = $reservationSeries->RemoveInstance($conflict->Reservation);

                if ($skipped) {
                    $skippedConflicts++;
                }
            }
        }

        $allowReservation = $conflicts->AllowReservation($skippedConflicts);//$numberOfConflicts > 0 || $anyConflictsAreBlackouts;

        if (!$allowReservation) {
            $numberOfReservationDates = count($reservationSeries->Instances());
            $shouldRetry = count($conflicts->Conflicts()) < $numberOfReservationDates;
            $canJoinWaitlist = $numberOfReservationDates == 1;
            return new ReservationRuleResult(
                false,
                $this->GetErrorString($conflicts->Conflicts()),
                $shouldRetry,
                Resources::GetInstance()->GetString('RetrySkipConflicts'),
                [new ReservationRetryParameter(ReservationRetryParameter::$SKIP_CONFLICTS, true)],
                $canJoinWaitlist
            );
        }

        return new ReservationRuleResult();
    }

    /**
     * @param Reservation $instance
     * @param ReservationSeries $series
     * @param IReservedItemView $existingItem
     * @param BookableResource[] $keyedResources
     * @return bool
     */
    protected function IsInConflict(Reservation $instance, ReservationSeries $series, IReservedItemView $existingItem, $keyedResources)
    {
        if (array_key_exists($existingItem->GetResourceId(), $keyedResources)) {
            return $existingItem->BufferedTimes()->Overlaps($instance->Duration());
        }

        return false;
    }

    /**
     * @param IdentifiedConflict[] $conflicts
     * @return string
     */
    protected function GetErrorString($conflicts)
    {
        $errorString = new StringBuilder();

        $errorString->Append(Resources::GetInstance()->GetString('ConflictingReservationDates'));
        $errorString->Append("\n");
        $format = Resources::GetInstance()->GetDateFormat(ResourceKeys::DATE_GENERAL);

        $dates = [];
        /** @var IdentifiedConflict $conflict */
        foreach ($conflicts as $conflict) {
            $dates[] = sprintf(
                '%s - %s',
                $conflict->Conflict->GetStartDate()->ToTimezone($this->timezone)->Format($format),
                $conflict->Conflict->GetResourceName()
            );
        }

        $uniqueDates = array_unique($dates);
        sort($uniqueDates);

        foreach ($uniqueDates as $date) {
            $errorString->Append($date);
            $errorString->Append("\n");
        }

        return $errorString->ToString();
    }
}
