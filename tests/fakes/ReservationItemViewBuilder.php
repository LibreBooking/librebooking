<?php

declare(strict_types=1);

class ReservationItemViewBuilder
{
    public $reservationId;
    public $startDate;
    public $endDate;
    public $summary;
    public $resourceId;
    public $userId;
    public $firstName;
    public $lastName;
    public $referenceNumber;

    public function __construct()
    {
        $this->reservationId = 1;
        $this->startDate = Date::Now();
        $this->endDate = Date::Now();
        $this->summary = 'summary';
        $this->resourceId = 10;
        $this->userId = 100;
        $this->firstName = 'first';
        $this->lastName = 'last';
        $this->referenceNumber = 'referenceNumber';
    }

    public function WithStartDate(Date $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function WithEndDate(Date $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return ReservationItemView
     */
    public function Build()
    {
        return new ReservationItemView(
            $this->referenceNumber,
            $this->startDate,
            $this->endDate,
            null,
            $this->resourceId,
            $this->reservationId,
            null,
            null,
            $this->summary,
            null,
            $this->firstName,
            $this->lastName,
            $this->userId
        );
    }
}
