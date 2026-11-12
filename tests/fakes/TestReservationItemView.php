<?php

declare(strict_types=1);

class TestReservationItemView extends ReservationItemView
{
    public $_RequiresCheckin = false;

    public function __construct(
        int $id,
        Date $startDate,
        Date $endDate,
        int $resourceId = 1,
        string $referenceNumber = 'referencenumber'
    ) {
        parent::__construct();

        $this->ReservationId = $id;
        $this->StartDate = $startDate;
        $this->EndDate = $endDate;
        $this->ResourceId = $resourceId;
        $this->Date = new DateRange($startDate, $endDate);
        $this->RepeatType = RepeatType::None;
        $this->ReferenceNumber = $referenceNumber;
        $this->ScheduleId = 1;
    }

    public function WithSeriesId(int $seriesId): static
    {
        $this->SeriesId = $seriesId;
        return $this;
    }

    public function RequiresCheckin()
    {
        return $this->_RequiresCheckin;
    }
}

class TestBlackoutItemView extends BlackoutItemView
{
    public function __construct(
        $instanceId,
        Date $startDate,
        Date $endDate,
        $resourceId,
        $seriesId = 1
    ) {
        parent::__construct(
            instanceId: $instanceId,
            startDate: $startDate,
            endDate: $endDate,
            resourceId: $resourceId,
            ownerId: null,
            scheduleId: 0,
            title: '',
            description: '',
            firstName: null,
            lastName: null,
            resourceName: '',
            seriesId: $seriesId,
            repeatOptions: '',
            repeatType: RepeatType::None
        );
    }

    public function WithScheduleId($scheduleId)
    {
        $this->ScheduleId = $scheduleId;
        return $this;
    }
}
