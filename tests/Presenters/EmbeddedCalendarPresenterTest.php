<?php

require_once(ROOT_DIR . 'Presenters/EmbeddedCalendarPresenter.php');

class EmbeddedCalendarPresenterTest extends TestBase
{
    /**
     * @var FakeEmbeddedCalendarPage
     */
    private $page;

    /**
     * @var EmbeddedCalendarPresenter
     */
    private $presenter;

    /**
     * @var FakeReservationViewRepository
     */
    private $reservationRepository;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var FakeResourceRepository
     */
    private $resourceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeEmbeddedCalendarPage();
        $this->reservationRepository = new FakeReservationViewRepository();
        $this->scheduleRepository = new FakeScheduleRepository();
        $this->resourceRepository = new FakeResourceRepository();

        $this->presenter = new EmbeddedCalendarPresenter($this->page, $this->reservationRepository, $this->resourceRepository, $this->scheduleRepository);
    }

    public function testLoadsDefaultCalendarForPublicSchedulesAndResources()
    {
        $timezone = 'America/New_York';
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_TIMEZONE, $timezone);

        $r1 = new TestReservationItemView(1, Date::Now(), Date::Now());
        $r1->ScheduleId = 2;
        $r1->ResourceId = 2;
        $r2 = new TestReservationItemView(1, Date::Now(), Date::Now());
        $r2->ScheduleId = 1;
        $r2->ResourceId = 2;
        $r3 = new TestReservationItemView(1, Date::Now(), Date::Now());
        $r3->ScheduleId = 2;
        $r3->ResourceId = 1;
        $reservations = [$r1, $r2, $r3];
        $this->reservationRepository->_Reservations = $reservations;

        $this->scheduleRepository->_PublicScheduleIds = [1 => 'public1'];
        $this->resourceRepository->_PublicResourceIds = [1 => 'public1'];

        $this->presenter->PageLoad();

        $this->assertEquals([new ReservationListItem($r2), new ReservationListItem($r3)], $this->page->_Reservations->Reservations());
        $this->assertEquals($timezone, $this->page->_Timezone);
        $this->assertTrue($this->page->_AgendaDisplayed);
        $this->assertEquals(new DateRange(Date::Now(), Date::Now()->ToTimezone($timezone)->GetDate()->AddDays(8)), $this->reservationRepository->_LastRange);
    }

    public function testLoadsRequestedScheduleDefaultView()
    {
        $this->page->_ScheduleId = 'scheduleid';
        $id = 100;

        $fakeSchedule = new FakeSchedule($id);
        $fakeSchedule->EnableSubscription();
        $this->scheduleRepository->_Schedule = $fakeSchedule;

        $this->presenter->PageLoad();

        $this->assertEquals($id, $this->reservationRepository->_LastScheduleIds);
    }

    public function testLoadsRequestedResourceDefaultView()
    {
        $this->page->_ResourceId = 'resourceId';
        $id = 100;

        $fakeResource = new FakeBookableResource($id);
        $fakeResource->EnableSubscription();
        $this->resourceRepository->_Resource = $fakeResource;

        $this->presenter->PageLoad();

        $this->assertEquals($id, $this->reservationRepository->_LastResourceIds);
    }

    public function testDisplaysWeek()
    {
        $timezone = $this->fakeConfig->GetDefaultTimezone();
        Date::_SetNow(Date::Parse('2018-04-17', $timezone));   // tuesday
        $first = Date::Parse('2018-04-15', $timezone);
        $last = Date::Parse('2018-04-23', $timezone);
        $this->page->_DisplayType = 'week';

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_WeekDisplayed);
        $this->assertEquals(new DateRange($first, $last), $this->reservationRepository->_LastRange, 'end date at midnnight of next day to get all of last days reservations');
    }

    public function testDisplaysMonth()
    {
        $timezone = $this->fakeConfig->GetDefaultTimezone();
        Date::_SetNow(Date::Parse('2018-03-17', $timezone));   // tuesday
        $first = Date::Parse('2018-02-25', $timezone);
        $last = Date::Parse('2018-04-01', $timezone);
        $this->page->_DisplayType = 'month';

        $this->presenter->PageLoad();

        $this->assertTrue($this->page->_MonthDisplayed);
        $this->assertEquals(new DateRange($first, $last), $this->reservationRepository->_LastRange, 'end date at midnnight of next day to get all of last days reservations');
    }
}

class FakeEmbeddedCalendarPage extends FakePageBase implements IEmbeddedCalendarPage
{
    /**
     * @var ReservationListing
     */
    public $_Reservations;
    /**
     * @var string
     */
    public $_Timezone;
    public $_AgendaDisplayed = false;
    public $_WeekDisplayed = false;
    public $_MonthDisplayed = false;
    public $_ScheduleId;
    public $_ResourceId;
    public $_DaysToShow = '';
    public $_DisplayType;

    public function BindReservations($reservations, $timezone, Date $startDate, Date $endDate)
    {
        $this->_Reservations = $reservations;
        $this->_Timezone = $timezone;
    }

    public function DisplayAgenda()
    {
        $this->_AgendaDisplayed = true;
    }

    public function DisplayWeek()
    {
        $this->_WeekDisplayed = true;
    }

    public function DisplayMonth()
    {
        $this->_MonthDisplayed = true;
    }

    public function DisplayError()
    {
        // TODO: Implement DisplayError() method.
    }

    public function GetScheduleId()
    {
        return $this->_ScheduleId;
    }

    public function GetResourceId()
    {
        return $this->_ResourceId;
    }

    public function GetDaysToShow()
    {
        return $this->_DaysToShow;
    }

    public function GetDisplayType()
    {
        return $this->_DisplayType;
    }


    /**
     * @return string
     */
    public function GetTitleFormat()
    {
        // TODO: Implement GetTitleFormat() method.
        return null;
    }

    /**
     * @param EmbeddedCalendarTitleFormatter $formatter
     */
    public function BindTitleFormatter(EmbeddedCalendarTitleFormatter $formatter)
    {
        // TODO: Implement BindTitleFormatter() method.
    }
}
