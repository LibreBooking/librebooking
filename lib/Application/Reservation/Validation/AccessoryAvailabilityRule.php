<?php

require_once(ROOT_DIR . 'Domain/Access/AccessoryRepository.php');
require_once(ROOT_DIR . 'Domain/Access/ReservationRepository.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/AccessoryAggregation.php');

class AccessoryAvailabilityRule implements IReservationValidationRule
{
    /**
     * @var IReservationViewRepository
     */
    protected $reservationRepository;

    /**
     * @var IAccessoryRepository
     */
    protected $accessoryRepository;

    /**
     * @var string
     */
    protected $timezone;

    public function __construct(IReservationViewRepository $reservationRepository, IAccessoryRepository $accessoryRepository, $timezone)
    {
        $this->reservationRepository = $reservationRepository;
        $this->accessoryRepository = $accessoryRepository;
        $this->timezone = $timezone;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        $conflicts = [];
        $reservationAccessories = $reservationSeries->Accessories();

        if (count($reservationAccessories) == 0) {
            // no accessories to be reserved, no need to proceed
            return new ReservationRuleResult();
        }

        /** @var AccessoryToCheck[] $accessories */
        $accessories = [];
        foreach ($reservationAccessories as $accessory) {
            $a = $this->accessoryRepository->LoadById($accessory->AccessoryId);
            if (!$a->HasUnlimitedQuantity()) {
                $accessories[$a->GetId()] = new AccessoryToCheck($a, $accessory);
            }
        }

        if (count($accessories) == 0) {
            // no accessories with limited quantity to be reserved, no need to proceed
            return new ReservationRuleResult();
        }

        $reservations = $reservationSeries->Instances();
        /** @var Reservation $reservation */
        foreach ($reservations as $reservation) {
            $dates = $reservation->Duration()->Dates();
            for ($i = 0; $i < count($dates); $i++) {
                if ($i == 0) {
                    $start = $reservation->StartDate();
                } else {
                    $start = $dates[$i]->GetDate();
                }
                if ($i == count($dates) - 1) {
                    $end = $reservation->EndDate();
                } else {
                    $end = $dates[$i]->GetDate()->AddDays(1);
                }

                $range = new DateRange($start, $end);

                Log::Debug('Checking for accessory conflicts, reference number %s date %s', $reservation->ReferenceNumber(), $range);

                $accessoryReservations = $this->reservationRepository->GetAccessoriesWithin($range);

                $aggregation = new AccessoryAggregation($accessories, $range);

                foreach ($accessoryReservations as $accessoryReservation) {
                    if ($reservation->ReferenceNumber() != $accessoryReservation->GetReferenceNumber()) {
                        $aggregation->Add($accessoryReservation);
                    }
                }

                foreach ($accessories as $accessory) {
                    $alreadyReserved = $aggregation->GetQuantity($accessory->GetId());
                    $requested = $accessory->QuantityReserved();

                    if ($requested + $alreadyReserved > $accessory->QuantityAvailable()) {
                        Log::Debug(
                            'Accessory over limit. Reference Number %s, Date %s, Quantity already reserved %s, Quantity requested: %s',
                            $reservation->ReferenceNumber(),
                            $reservation->Duration(),
                            $alreadyReserved,
                            $requested
                        );

                        array_push($conflicts, ['name' => $accessory->GetName(), 'date' => $reservation->StartDate()]);
                    }
                }
            }

            $thereAreConflicts = count($conflicts) > 0;

            if ($thereAreConflicts) {
                return new ReservationRuleResult(false, $this->GetErrorString($conflicts));
            }
        }



        return new ReservationRuleResult();
    }

    /**
     * @param array $conflicts
     * @return string
     */
    protected function GetErrorString($conflicts)
    {
        $errorString = new StringBuilder();

        $errorString->Append(Resources::GetInstance()->GetString('ConflictingAccessoryDates'));
        $errorString->AppendLine();
        $format = Resources::GetInstance()->GetDateFormat(ResourceKeys::DATE_GENERAL);

        foreach ($conflicts as $conflict) {
            $errorString->Append(sprintf('(%s) %s', $conflict['date']->ToTimezone($this->timezone)->Format($format), $conflict['name']));
            $errorString->AppendLine();
        }

        return $errorString->ToString();
    }
}

class AccessoryToCheck
{
    /**
     * @var \Accessory
     */
    private $accessory;

    /**
     * @var \ReservationAccessory
     */
    private $reservationAccessory;

    /**
     * @var int
     */
    private $quantityReserved;

    public function __construct(Accessory $accessory, ReservationAccessory $reservationAccessory)
    {
        $this->accessory = $accessory;
        $this->reservationAccessory = $reservationAccessory;
        $this->quantityReserved = $this->reservationAccessory->QuantityReserved;
    }

    /**
     * @return int
     */
    public function GetId()
    {
        return $this->accessory->GetId();
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->accessory->GetName();
    }

    /**
     * @return int
     */
    public function QuantityReserved()
    {
        return $this->quantityReserved;
    }

    /**
     * @return int
     */
    public function QuantityAvailable()
    {
        return $this->accessory->GetQuantityAvailable();
    }
}
