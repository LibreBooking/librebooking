<?php

declare(strict_types=1);

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

        $this->validator
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
        $schedule = new FakeSchedule($scheduleId, 'Engineering Schedule');

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->ScheduleId = $publicId;

        $this->service->expects($this->once())
                ->method('GetSchedule')
                ->with($this->equalTo($publicId))
                ->willReturn($schedule);

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->equalTo($publicId), $this->isNull(), $this->isNull(), $this->isNull())
                ->willReturn('Engineering Schedule');

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->isNull(), ReservationUserLevel::OWNER, $scheduleId, $this->isNull())
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
        $this->assertEquals('Engineering Schedule', $this->page->CalendarName);
    }

    public function testGetsScheduleReservationsForTheNextYearByResourceId()
    {
        $publicId = '1';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now())];

        $resourceId = 999;
        $resource = new FakeBookableResource($resourceId, 'Meeting Room 3');

        $weekAgo = Date::Now()->AddDays(0);
        $nextYear = Date::Now()->AddDays(30);

        $this->page->ResourceId = $publicId;

        $this->service->expects($this->once())
                ->method('GetResource')
                ->with($this->equalTo($publicId))
                ->willReturn($resource);

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->isNull(), $this->equalTo($publicId), $this->isNull(), $this->isNull())
                ->willReturn('Meeting Room 3');

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->isNull(), ReservationUserLevel::OWNER, $this->isNull(), $resourceId)
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
        $this->assertEquals('Meeting Room 3', $this->page->CalendarName);
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

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->isNull(), $this->isNull(), $this->isNull(), $this->equalTo($publicId))
                ->willReturn(Resources::GetInstance()->GetString('MyCalendar'));

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->with($this->equalTo($weekAgo), $this->equalTo($nextYear), $this->equalTo($userId), ReservationUserLevel::ALL, $this->isNull(), $this->isNull())
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertCount(1, $this->page->Reservations);
        $this->assertEquals(Resources::GetInstance()->GetString('MyCalendar'), $this->page->CalendarName);
    }

    public function testGetsUserReservationsFilteredByResourceCombinesCalendarNameWithResourceName()
    {
        $userPublicId = '1';
        $resourcePublicId = '2';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now())];

        $userId = 999;
        $user = new FakeUser($userId);

        $resourceId = 888;
        $resource = new FakeBookableResource($resourceId, 'Meeting Room 3');

        $this->page->UserId = $userPublicId;
        $this->page->ResourceId = $resourcePublicId;

        $this->service->expects($this->once())
                ->method('GetUser')
                ->with($this->equalTo($userPublicId))
                ->willReturn($user);

        $this->service->expects($this->once())
                ->method('GetResource')
                ->with($this->equalTo($resourcePublicId))
                ->willReturn($resource);

        $combinedName = sprintf('%s - %s', Resources::GetInstance()->GetString('MyCalendar'), 'Meeting Room 3');

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->isNull(), $this->equalTo($resourcePublicId), $this->isNull(), $this->equalTo($userPublicId))
                ->willReturn($combinedName);

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertEquals($combinedName, $this->page->CalendarName);
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

    public function testGetsResourceGroupReservationsSetsCalendarNameFromGroup()
    {
        $publicId = '1';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now(), 1)];

        $resourceIds = [1];

        $this->page->ResourceGroupId = $publicId;

        $this->service->expects($this->once())
                ->method('GetResourcesInGroup')
                ->with($this->equalTo($publicId))
                ->willReturn($resourceIds);

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->isNull(), $this->isNull(), $this->equalTo($publicId), $this->isNull())
                ->willReturn('Engineering Rooms');

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertEquals('Engineering Rooms', $this->page->CalendarName);
    }

    public function testGetsResourceGroupReservationsKeepsScheduleCalendarNameWhenGroupNameIsNull()
    {
        $publicScheduleId = '1';
        $publicGroupId = '2';
        $reservationResult = [new TestReservationItemView(1, Date::Now(), Date::Now(), 1)];

        $scheduleId = 999;
        $schedule = new FakeSchedule($scheduleId, 'Engineering Schedule');

        $this->page->ScheduleId = $publicScheduleId;
        $this->page->ResourceGroupId = $publicGroupId;

        $this->service->expects($this->once())
                ->method('GetSchedule')
                ->with($this->equalTo($publicScheduleId))
                ->willReturn($schedule);

        $this->service->expects($this->once())
                ->method('GetResourcesInGroup')
                ->with($this->equalTo($publicGroupId))
                ->willReturn([1]);

        $this->service->expects($this->once())
                ->method('ResolveCalendarName')
                ->with($this->equalTo($publicScheduleId), $this->isNull(), $this->equalTo($publicGroupId), $this->isNull())
                ->willReturn('Engineering Schedule');

        $this->repo->expects($this->once())
                ->method('GetReservations')
                ->willReturn($reservationResult);

        $this->presenter->PageLoad();

        $this->assertEquals('Engineering Schedule', $this->page->CalendarName);
    }

    public function testPageLoadReturnsFalseAndDoesNotLoadReservationsWhenValidationFails()
    {
        $validator = $this->createMock('ICalendarExportValidator');
        $validator->expects($this->once())
                ->method('IsValid')
                ->willReturn(false);

        $this->repo->expects($this->never())->method('GetReservations');
        $this->service->expects($this->never())->method('GetSchedule');
        $this->service->expects($this->never())->method('GetResource');
        $this->service->expects($this->never())->method('GetUser');

        $presenter = new CalendarSubscriptionPresenter(
            $this->page,
            $this->repo,
            $validator,
            $this->service,
            $this->privacyFilter
        );

        $this->assertFalse($presenter->PageLoad());
        $this->assertNull($this->page->Reservations);
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

    public $SubscriptionKey = '123';
    public $PastDays;
    public $FutureDays;
    public $CalendarName;

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

    public function SetCalendarName(string $calendarName): void
    {
        $this->CalendarName = $calendarName;
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
        return 0;
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
