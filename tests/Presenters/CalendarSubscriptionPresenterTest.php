<?php

require_once(ROOT_DIR . 'Pages/Export/CalendarSubscriptionPage.php');
require_once(ROOT_DIR . 'Presenters/CalendarSubscriptionPresenter.php');

class CalendarSubscriptionPresenterTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $repo;

    /**
     * @var FakeCalendarSubscriptionPage
     */
    private $page;

    /**
     * @var CalendarSubscriptionPresenter
     */
    private $presenter;

    /**
     * @var ICalendarExportValidator|PHPUnit\Framework\MockObject\MockObject
     */
    private $validator;

    /**
     * @var ICalendarSubscriptionService|PHPUnit\Framework\MockObject\MockObject
     */
    private $service;

    /**
     * @var FakePrivacyFilter
     */
    private $privacyFilter;

    public function setUp(): void
    {
        parent::setup();

        $this->repo = $this->createMock('IReservationViewRepository');
        $this->page = new FakeCalendarSubscriptionPage();//$this->createMock('ICalendarSubscriptionPage');
        $this->validator = $this->createMock('ICalendarExportValidator');
        $this->service = $this->createMock('ICalendarSubscriptionService');
        $this->privacyFilter = new FakePrivacyFilter();

        $this->validator->expects($this->atLeastOnce())
                ->method('IsValid')
                ->willReturn(true);

        $this->presenter = new CalendarSubscriptionPresenter(
            $this->page,
            $this->repo,
            $this->validator,
            $this->service,
            $this->privacyFilter
        );
    }

    public function testGetsScheduleReservationsForTheNextYearByScheduleId()
    {
        $publicId = '1';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now())];

        $scheduleId = 999;
        $schedule = new FakeSchedule($scheduleId);

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->ScheduleId = $publicId;

        $this->service->expects($this->once())
                ->method('GetSchedule')
                ->with($this->equalTo($publicId))
                ->willReturn($schedule);

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->isNull(), ReservationUserLevel::OWNER, $scheduleId, $this->isNull())
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
    }

    public function testGetsScheduleReservationsForTheNextYearByResourceId()
    {
        $publicId = '1';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now())];

        $resourceId = 999;
        $resource = new FakeBookableResource($resourceId);

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->ResourceId = $publicId;

        $this->service->expects($this->once())
                ->method('GetResource')
                ->with($this->equalTo($publicId))
                ->willReturn($resource);

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->isNull(), ReservationUserLevel::OWNER, $this->isNull(), $resourceId)
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
    }

    public function testGetsUserReservationsForTheNextYearByResourceId()
    {
        $publicId = '1';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now())];

        $userId = 999;
        $user = new FakeUser($userId);

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->UserId = $publicId;

        $this->service->expects($this->once())
                ->method('GetUser')
                ->with($this->equalTo($publicId))
                ->willReturn($user);

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->equalTo($userId), ReservationUserLevel::ALL, $this->isNull(), $this->isNull())
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
    }

    public function testGetsResourceGroupReservationsForTheNextYearByGroupId()
    {
        $publicId = '1';
        $reservationResult = [
                new TestReservationItemView(1, Date::Now(), Date::Now(), 1),
                new TestReservationItemView(2, Date::Now(), Date::Now(), 2),
        ];

        $resourceIds = [2];

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->ResourceGroupId = $publicId;

        $this->service->expects($this->once())
                ->method('GetResourcesInGroup')
                ->with($this->equalTo($publicId))
                ->willReturn($resourceIds);

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->isNull(), ReservationUserLevel::OWNER, $this->isNull(), $this->isNull())
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
    }
}

class FakeCalendarSubscriptionPage implements ICalendarSubscriptionPage
{
    public $ScheduleId;
    public $ResourceId;
    public $ResourceGroupId;

    /**
     * @vari CalendarReservationView[]
     */
    public $Reservations;

    public $UserId;

    public $SubscriptionKey = "123";
    public $PastDays;
    public $FutureDays;

    public function GetSubscriptionKey()
    {
        return $this->SubscriptionKey;
    }

    public function GetUserId()
    {
        return $this->UserId;
    }

    public function SetReservations($reservations)
    {
        $this->Reservations = $reservations;
    }

    public function GetScheduleId()
    {
        return $this->ScheduleId;
    }

    public function GetResourceId()
    {
        return $this->ResourceId;
    }

    public function GetResourceGroupId()
    {
        return $this->ResourceGroupId;
    }

    public function GetAccessoryIds()
    {
        // TODO: Implement GetAccessoryIds() method.
        return null;
    }

    public function GetPastNumberOfDays()
    {
        return $this->PastDays;
    }

    public function GetFutureNumberOfDays()
    {
        return $this->FutureDays;
    }
}
