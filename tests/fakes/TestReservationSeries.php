<?php

declare(strict_types=1);

require_once ROOT_DIR . '/Domain/namespace.php';

class TestReservationSeries extends ReservationSeries
{
    private $_scheduleId;

    public function __construct()
    {
        parent::__construct();
        $this->_bookedBy = new FakeUserSession();
        $this->WithResource(new FakeBookableResource(1));
    }

    public function WithOwnerId($ownerId)
    {
        $this->_userId = $ownerId;
    }

    public function WithResource(BookableResource $resource)
    {
        $this->_resource = $resource;
    }

    public function WithDuration(DateRange $duration)
    {
        $this->UpdateDuration($duration);
    }

    public function WithRepeatOptions(IRepeatOptions $repeatOptions)
    {
        $this->Repeats($repeatOptions);
    }

    public function WithCurrentInstance(Reservation $currentInstance)
    {
        $this->SetCurrentInstance($currentInstance);
        $this->AddInstance($currentInstance);
    }

    public function WithBookedBy($bookedBy)
    {
        $this->_bookedBy = $bookedBy;
    }

    public function WithAccessory(ReservationAccessory $accessory)
    {
        $this->AddAccessory($accessory);
    }

    public function WithInstanceOn(DateRange $dateRange)
    {
        $this->AddNewInstance($dateRange);
    }

    public function WithScheduleId($scheduleId)
    {
        $this->_scheduleId = $scheduleId;
    }

    public function ScheduleId()
    {
        if (!empty($this->_scheduleId)) {
            return $this->_scheduleId;
        }

        return parent::ScheduleId();
    }

    /**
     * @param $attributeValue AttributeValue
     */
    public function WithAttributeValue($attributeValue)
    {
        $this->AddAttributeValue($attributeValue);
    }

    public function WithCreditsRequired($credits)
    {
        $this->creditsRequired = $credits;
    }

    public function GetCreditsRequired()
    {
        return $this->creditsRequired;
    }
}
