<?php

require_once(ROOT_DIR . 'Presenters/Dashboard/ResourceAvailabilityControlPresenter.php');

class ResourceAvailabilityControlPresenterTest extends TestBase
{
    /**
     * @var FakeReservationViewRepository
     */
    private $reservationRepo;

    /**
     * @var FakeResourceAvailabilityControl
     */
    private $control;

    /**
     * @var ResourceAvailabilityControlPresenter
     */
    private $presenter;

    /**
     * @var FakeResourceService
     */
    private $resourceService;

    /**
     * @var ResourceDto
     */
    private $unavailableResource;

    /**
     * @var ResourceDto
     */
    private $availableResource;

    /**
     * @var ResourceDto
     */
    private $unavailableAllDayResource;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepo;

    public function setUp(): void
    {
        parent::setup();

        $this->control = new FakeResourceAvailabilityControl();
        $this->resourceService = new FakeResourceService();
        $this->reservationRepo = new FakeReservationViewRepository();
        $this->scheduleRepo = new FakeScheduleRepository();
        $this->presenter = new ResourceAvailabilityControlPresenter($this->control, $this->resourceService, $this->reservationRepo, $this->scheduleRepo);

        $this->unavailableResource = new TestResourceDto(1, '1');
        $this->availableResource = new TestResourceDto(2, '2');
        $this->unavailableAllDayResource = new TestResourceDto(3, '3');
    }

    public function testSetsResourceAvailability()
    {
        Date::_SetNow(Date::Parse('2017-01-20 4:00', 'UTC'));
        $this->PopulateSchedules();
        $this->PopulateResources();
        $this->PopulateReservations();

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertEquals(
            new AvailableDashboardItem($this->availableResource, $this->reservationRepo->_Reservations[3]),
            $this->control->_AvailableNow[1][0]
        );
        $this->assertEquals(
            new UnavailableDashboardItem($this->unavailableResource, $this->reservationRepo->_Reservations[1]),
            $this->control->_UnavailableNow[1][0]
        );
        $this->assertEquals(
            new UnavailableDashboardItem($this->unavailableAllDayResource, $this->reservationRepo->_Reservations[2]),
            $this->control->_UnavailableAllDay[1][0]
        );
    }

    private function PopulateResources()
    {
        $this->resourceService->_AllResources = [
            $this->unavailableResource,
            $this->availableResource,
            $this->unavailableAllDayResource,
        ];
    }

    private function PopulateReservations()
    {
        $this->reservationRepo->_Reservations = [
            new TestReservationItemView(1, Date::Now()->AddHours(-1), Date::Now()->AddHours(1), $this->unavailableResource->GetId()),
            new TestReservationItemView(2, Date::Now()->AddHours(1), Date::Now()->AddHours(2), $this->unavailableResource->GetId()),
            new TestReservationItemView(3, Date::Now()->AddDays(-1), Date::Now()->AddDays(1), $this->unavailableAllDayResource->GetId()),
            new TestReservationItemView(4, Date::Now()->AddDays(1), Date::Now()->AddDays(2), $this->availableResource->GetId()),
        ];
    }

    private function PopulateSchedules()
    {
        $this->scheduleRepo->_Schedules = [new FakeSchedule(1), new FakeSchedule(2)];
    }
}

class FakeResourceAvailabilityControl implements IResourceAvailabilityControl
{
    /**
     * @var AvailableDashboardItem[]
     */
    public $_AvailableNow;

    /**
     * @var UnavailableDashboardItem[]
     */
    public $_UnavailableNow;

    /**
     * @var UnavailableDashboardItem[]
     */
    public $_UnavailableAllDay;

    /**
     * @param AvailableDashboardItem[] $items
     */
    public function SetAvailable($items)
    {
        $this->_AvailableNow = $items;
    }

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailable($items)
    {
        $this->_UnavailableNow = $items;
    }

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailableAllDay($items)
    {
        $this->_UnavailableAllDay = $items;
    }

    /**
     * @param Schedule[] $schedules
     */
    public function SetSchedules($schedules)
    {
        // TODO: Implement SetSchedules() method.
    }
}
