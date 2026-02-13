<?php

require_once(ROOT_DIR . 'Pages/Ajax/AvailableAccessoriesPage.php');
require_once(ROOT_DIR . 'Presenters/AvailableAccessoriesPresenter.php');

class AvailableAccessoriesPresenterTest extends TestBase
{
    /**
     * @var FakeAccessoryRepository
     */
    private $accessoryRepo;

    /**
     * @var FakeAvailableAccessoriesPage
     */
    private $page;

    /**
     * @var FakeReservationViewRepository
     */
    private $reservationRepo;

    /**
     * @var AvailableAccessoriesPresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->accessoryRepo = new FakeAccessoryRepository();
        $this->reservationRepo = new FakeReservationViewRepository();
        $this->page = new FakeAvailableAccessoriesPage();
        $this->fakeUser->Timezone = 'UTC';

        $this->presenter = new AvailableAccessoriesPresenter($this->page, $this->accessoryRepo, $this->reservationRepo, $this->fakeUser);
    }

    public function testGetsAvailableQuantityWhenNotTheSameReservation()
    {
        $duration = $this->page->GetDuration();
        $this->accessoryRepo->_AllAccessories = [new Accessory(1, '', 10), new Accessory(2, '', 4), new Accessory(3, '', null)];

        $this->reservationRepo->_AccessoryReservations = [
            new AccessoryReservation('r1', $duration->GetBegin(), $duration->GetEnd(), 1, 2),
            new AccessoryReservation('r1', $duration->GetBegin(), $duration->GetEnd(), 2, 2),
            new AccessoryReservation('r2', $duration->GetBegin(), $duration->GetEnd(), 1, 2),
            new AccessoryReservation('r2', $duration->GetBegin(), $duration->GetEnd(), 2, 2),
            new AccessoryReservation('r3', $duration->GetBegin(), $duration->GetEnd(), 3, 2),
        ];

        $this->presenter->PageLoad();

        $bound = $this->page->_BoundAvailability;

        $this->assertEquals([new AccessoryAvailability(1, 6), new AccessoryAvailability(2, 0), new AccessoryAvailability(3, null)], $bound);
    }

    public function testGetsAvailableQuantityWhenTheSameReservation()
    {
        $duration = $this->page->GetDuration();
        $this->accessoryRepo->_AllAccessories = [new Accessory(1, '', 10), new Accessory(2, '', 4), new Accessory(3, '', null)];

        $this->reservationRepo->_AccessoryReservations = [
            new AccessoryReservation('r1', $duration->GetBegin(), $duration->GetEnd(), 1, 2),
            new AccessoryReservation('r1', $duration->GetBegin(), $duration->GetEnd(), 2, 2),
            new AccessoryReservation('r2', $duration->GetBegin(), $duration->GetEnd(), 1, 2),
            new AccessoryReservation('r2', $duration->GetBegin(), $duration->GetEnd(), 2, 2),
            new AccessoryReservation('r3', $duration->GetBegin(), $duration->GetEnd(), 3, 2),
        ];

        $this->page->_ReferenceNumber = 'r2';
        $this->presenter->PageLoad();

        $bound = $this->page->_BoundAvailability;

        $this->assertEquals([new AccessoryAvailability(1, 8), new AccessoryAvailability(2, 2), new AccessoryAvailability(3, null)], $bound);
    }

    public function testWhenReservationSpansMultipleDays()
    {
        $this->page->_StartDate = '2016-11-23';
        $this->page->_EndDate = '2016-11-24';
        $this->page->_StartTime = '08:30';
        $this->page->_EndTime = '17:30';
        $this->accessoryRepo->_AllAccessories = [new Accessory(1, '', 10)];

        $this->reservationRepo->_AccessoryReservations = [
            new AccessoryReservation('r1', Date::Parse('2016-11-23 12:00', 'UTC'), Date::Parse('2016-11-23 12:30', 'UTC'), 1, 5),
            new AccessoryReservation('r2', Date::Parse('2016-11-24 12:00', 'UTC'), Date::Parse('2016-11-24 12:30', 'UTC'), 1, 5),
        ];

        $this->presenter->PageLoad();

        $bound = $this->page->_BoundAvailability;

        $this->assertEquals([new AccessoryAvailability(1, 0)], $bound);
    }

    public function testWhenAccessoryReservationSpansMultipleDays()
    {
        $this->page->_StartDate = '2016-11-23';
        $this->page->_EndDate = '2016-11-24';
        $this->page->_StartTime = '08:30';
        $this->page->_EndTime = '17:30';
        $this->accessoryRepo->_AllAccessories = [new Accessory(1, '', 10)];

        $this->reservationRepo->_AccessoryReservations = [
            new AccessoryReservation('r1', Date::Parse('2016-11-23 12:00', 'UTC'), Date::Parse('2016-11-24 12:30', 'UTC'), 1, 5),
            new AccessoryReservation('r2', Date::Parse('2016-11-24 12:00', 'UTC'), Date::Parse('2016-11-24 12:30', 'UTC'), 1, 4),
        ];

        $this->presenter->PageLoad();

        $bound = $this->page->_BoundAvailability;

        $this->assertEquals([new AccessoryAvailability(1, 1)], $bound);
    }

    public function testWhenNewReservationOverlapsNonOverlappingReservations()
    {
        $this->page->_StartDate = '2018-10-16';
        $this->page->_StartTime = '09:30';
        $this->page->_EndDate = '2018-10-16';
        $this->page->_EndTime = '11:00';
        $this->accessoryRepo->_AllAccessories = [new Accessory(1, '', 2)];

        $this->reservationRepo->_AccessoryReservations = [
            new AccessoryReservation('r1', Date::Parse('2018-10-16 10:00', 'UTC'), Date::Parse('2018-10-16 10:30', 'UTC'), 1, 1),
            new AccessoryReservation('r2', Date::Parse('2018-10-16 10:30', 'UTC'), Date::Parse('2018-10-16 11:00', 'UTC'), 1, 1),
        ];

        $this->presenter->PageLoad();

        $bound = $this->page->_BoundAvailability;

        $this->assertEquals([new AccessoryAvailability(1, 0)], $bound);
    }
}

class FakeAvailableAccessoriesPage implements IAvailableAccessoriesPage
{
    public $_StartDate;
    public $_EndDate;
    public $_StartTime;
    public $_EndTime;
    public $_ReferenceNumber;
    public $_BoundAvailability;

    public function __construct()
    {
        $this->_StartDate = '2016-11-23';
        $this->_EndDate = '2016-11-24';
        $this->_StartTime = '08:30';
        $this->_EndTime = '17:30';
    }

    public function GetDuration()
    {
        return DateRange::Create($this->_StartDate . ' ' . $this->_StartTime, $this->_EndDate . ' ' . $this->_EndTime, 'UTC');
    }

    public function GetStartDate()
    {
        return $this->_StartDate;
    }

    public function GetEndDate()
    {
        return $this->_EndDate;
    }

    public function GetReferenceNumber()
    {
        return $this->_ReferenceNumber;
    }

    public function GetStartTime()
    {
        return $this->_StartTime;
    }

    public function GetEndTime()
    {
        return $this->_EndTime;
    }

    public function BindAvailability($realAvailability)
    {
        $this->_BoundAvailability = $realAvailability;
    }
}
