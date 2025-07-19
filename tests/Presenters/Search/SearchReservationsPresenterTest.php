<?php

require_once(ROOT_DIR . 'Presenters/Search/SearchReservationsPresenter.php');

class SearchReservationsPresenterTest extends TestBase
{
    /**
     * @var SearchReservationsPresenter
     */
    private $presenter;

    /**
     * @var FakeSearchReservationsPage
     */
    private $page;

    /**
     * @var FakeResourceService
     */
    private $resourceService;

    /**
     * @var FakeReservationViewRepository
     */
    private $reservationRepository;
    /**
     * @var FakeScheduleService
     */
    private $scheduleService;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeSearchReservationsPage();
        $this->resourceService = new FakeResourceService();
        $this->reservationRepository = new FakeReservationViewRepository();
        $this->scheduleService = new FakeScheduleService();

        $this->presenter = new SearchReservationsPresenter(
            $this->page,
            $this->fakeUser,
            $this->reservationRepository,
            $this->resourceService,
            $this->scheduleService
        );

        $this->resourceService->_AllResources = [new TestResourceDto(1, '', true, true, 1), new TestResourceDto(3, '', true, true, 2)];
        $this->scheduleService->_AllSchedules = [new FakeSchedule(1, 'schedule', true, 1)];
    }

    public function testFiltersReservations()
    {
        $userId = 10;
        $resourceIds = [10, 11];
        $scheduleIds = [20, 21];
        $title = 'title';
        $description = 'description';
        $referenceNumber = 'refnum';
        $range = 'today';
        $r1 = new ReservationItemView();
        $r1->ResourceId = 1;
        $r2= new ReservationItemView();
        $r2->ResourceId = 2;
        $reservations = [$r1];

        $this->page->_UserId = $userId;
        $this->page->_ResourceIds = $resourceIds;
        $this->page->_ScheduleIds = $scheduleIds;
        $this->page->_Title = $title;
        $this->page->_Description = $description;
        $this->page->_ReferenceNumber = $referenceNumber;
        $this->page->_Range = $range;

        $this->resourceService->_AllResources = [new TestResourceDto(1)];
        $this->reservationRepository->_FilterResults = new PageableData($reservations);

        $this->presenter->SearchReservations();

        $today = Date::Now()->ToTimezone($this->fakeUser->Timezone)->GetDate();
        $tomorrow = Date::Now()->ToTimezone($this->fakeUser->Timezone)->AddDays(1)->GetDate();
        $expectedFilter = ReservationsSearchFilter::GetFilter($today, $tomorrow, $userId, $resourceIds, $scheduleIds, $title, $description, $referenceNumber);

        $this->assertEquals($expectedFilter, $this->reservationRepository->_Filter);
        $this->assertEquals([$r1], $this->page->_Reservations, "no permission to the other one");
    }
}

class FakeSearchReservationsPage extends SearchReservationsPage
{
    public $_Reservations;

    public $_Range;
    public $_UserId;
    public $_ResourceIds;
    public $_ScheduleIds;
    public $_Title;
    public $_Description;
    public $_ReferenceNumber;

    public function SetResources($resources)
    {
    }

    public function ShowReservations($reservations, $timezone)
    {
        $this->_Reservations = $reservations;
    }

    public function GetRequestedRange()
    {
        return $this->_Range;
    }

    public function GetRequestedStartDate()
    {
        return null;
    }


    public function GetRequestedEndDate()
    {
        return null;
    }

    public function GetResources()
    {
        return $this->_ResourceIds;
    }

    public function GetSchedules()
    {
        return $this->_ScheduleIds;
    }

    public function GetUserId()
    {
        return $this->_UserId;
    }

    public function GetTitle()
    {
        return $this->_Title;
    }

    public function GetDescription()
    {
        return $this->_Description;
    }

    public function GetReferenceNumber()
    {
        return $this->_ReferenceNumber;
    }
}
