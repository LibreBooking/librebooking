<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');
require_once(ROOT_DIR . 'Pages/Reservation/NewReservationPage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/NewReservationInitializer.php');

class ReservationInitializationTest extends TestBase
{
    /**
     * @var IReservationComponentBinder|PHPUnit\Framework\MockObject\MockObject
     */
    private $userBinder;

    /**
     * @var IReservationComponentBinder|PHPUnit\Framework\MockObject\MockObject
     */
    private $dateBinder;

    /**
     * @var IReservationComponentBinder|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceBinder;

    /**
     * @var INewReservationPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var NewReservationInitializer|PHPUnit\Framework\MockObject\MockObject
     */
    private $initializer;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var ITermsOfServiceRepository
     */
    private $termsRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->userBinder = $this->createMock('IReservationComponentBinder');
        $this->dateBinder = $this->createMock('IReservationComponentBinder');
        $this->resourceBinder = $this->createMock('IReservationComponentBinder');
        $this->page = $this->createMock('INewReservationPage');
        $this->scheduleRepository = new FakeScheduleRepository();
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->termsRepository = $this->createMock('ITermsOfServiceRepository');

        $this->initializer = new NewReservationInitializer(
            $this->page,
            $this->userBinder,
            $this->dateBinder,
            $this->resourceBinder,
            $this->fakeUser,
            $this->scheduleRepository,
            $this->resourceRepository,
            $this->termsRepository
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testInitializesReservationData()
    {
        $scheduleId = 1;

        $this->page->expects($this->once())
            ->method('GetRequestedScheduleId')
            ->willReturn($scheduleId);

        $this->page->expects($this->once())
            ->method('SetScheduleId')
            ->with($this->equalTo($scheduleId));

        $this->userBinder->expects($this->once())
            ->method('Bind')
            ->with($this->equalTo($this->initializer));

        $this->dateBinder->expects($this->once())
            ->method('Bind')
            ->with($this->equalTo($this->initializer));

        $this->resourceBinder->expects($this->once())
            ->method('Bind')
            ->with($this->equalTo($this->initializer));

        $this->initializer->Initialize();
    }

    public function testBindsToClosestPeriod()
    {
        $page = $this->createMock('INewReservationPage');
        $binder = $this->createMock('IReservationComponentBinder');

        $timezone = $this->fakeUser->Timezone;

        $dateString = Date::Now()->AddDays(1)->SetTimeString('02:55:22')->Format('Y-m-d H:i:s');
        $endDateString = Date::Now()->AddDays(1)->SetTimeString('4:55:22')->Format('Y-m-d H:i:s');
        $dateInUserTimezone = Date::Parse($dateString, $timezone);

        $startDate = Date::Parse($dateString, $timezone);
        $endDate = Date::Parse($endDateString, $timezone);

        $expectedStartPeriod = new SchedulePeriod($dateInUserTimezone->SetTime(new Time(3, 30, 0)), $dateInUserTimezone->SetTime(new Time(4, 30, 0)));
        $expectedEndPeriod = new SchedulePeriod($dateInUserTimezone->SetTime(new Time(4, 30, 0)), $dateInUserTimezone->SetTime(new Time(7, 30, 0)));
        $periods = [
            new SchedulePeriod($dateInUserTimezone->SetTime(new Time(1, 0, 0)), $dateInUserTimezone->SetTime(new Time(2, 0, 0))),
            new SchedulePeriod($dateInUserTimezone->SetTime(new Time(2, 0, 0)), $dateInUserTimezone->SetTime(new Time(3, 0, 0))),
            new NonSchedulePeriod($dateInUserTimezone->SetTime(new Time(3, 0, 0)), $dateInUserTimezone->SetTime(new Time(3, 30, 0))),
            $expectedStartPeriod,
            $expectedEndPeriod,
            new SchedulePeriod($dateInUserTimezone->SetTime(new Time(7, 30, 0)), $dateInUserTimezone->SetTime(new Time(17, 30, 0))),
            new SchedulePeriod($dateInUserTimezone->SetTime(new Time(17, 30, 0)), $dateInUserTimezone->SetTime(new Time(0, 0, 0))),
        ];

        $page->expects($this->once())
            ->method('SetSelectedStart')
            ->with($this->equalTo($expectedStartPeriod), $this->equalTo($startDate));

        $page->expects($this->once())
            ->method('SetSelectedEnd')
            ->with($this->equalTo($expectedEndPeriod), $this->equalTo($endDate));

        $page->expects($this->once())
            ->method('SetRepeatTerminationDate')
            ->with($this->equalTo($endDate));

        $page->expects($this->once())
            ->method('SetFirstWeekday')
            ->with($this->equalTo(0));

        $initializer = new NewReservationInitializer(
            $page,
            $binder,
            $binder,
            $binder,
            $this->fakeUser,
            $this->scheduleRepository,
            $this->resourceRepository,
            $this->termsRepository
        );
        $initializer->SetDates($startDate, $endDate, $periods, $periods, 0);
    }

    public function testWhenNoScheduleIsPassed_UseDefaultScheduleId()
    {
        $id = $this->scheduleRepository->_DefaultScheduleId;

        $this->page->expects($this->once())
            ->method('GetRequestedScheduleId')
            ->willReturn(null);

        $this->page->expects($this->once())
            ->method('SetScheduleId')
            ->with($this->equalTo($id));

        $this->initializer->Initialize();
    }

    public function testBindsDefaultReminders()
    {
        $this->page->expects($this->once())
            ->method('SetStartReminder')
            ->with($this->equalTo('10'), $this->equalTo('minutes'));

        $this->page->expects($this->once())
            ->method('SetEndReminder')
            ->with($this->equalTo('2'), $this->equalTo('days'));

        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_REMINDER_DEFAULT_START, '10 minutes');
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_REMINDER_DEFAULT_END, '2 days');
        $this->initializer->Initialize();
    }
}
