<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class ScheduleTotalConcurrentReservationsRuleTest extends TestBase
{
    /**
     * @var FakeScheduleRepository
     */
    public $scheduleRepository;

    /**
     * var FakeReservationViewRepository
     */
    public $reservationViewRepository;

    public $rule;

    public function setUp(): void
    {
        parent::setup();

        $this->scheduleRepository = new FakeScheduleRepository();
        $this->scheduleRepository->_Schedule = new FakeSchedule();
        $this->reservationViewRepository = new FakeReservationViewRepository();

        $this->rule = new ScheduleTotalConcurrentReservationsRule($this->scheduleRepository, $this->reservationViewRepository, 'UTC');
    }

    public function testValidWhenScheduleIsUnlimited()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;

        $this->reservationViewRepository->_Reservations = [new TestReservationItemView(1, $start, $end, $resourceId)];
        $series = (new ExistingReservationSeriesBuilder())->WithPrimaryResource(new FakeBookableResource($resourceId))->Build();

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(0);

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testValidWhenUpdating()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;
        $refNum = '123';

        $this->reservationViewRepository->_Reservations = [new TestReservationItemView(1, $start, $end, $resourceId, $refNum)];
        $series = (new ExistingReservationSeriesBuilder())->WithPrimaryResource(new FakeBookableResource($resourceId))
                                                          ->WithCurrentInstance(new TestReservation($refNum, new DateRange($start, $end)))->Build();

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(count($series->AllResourceIds()));

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testValidWhenNotConflicting()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;
        $refNum = '123';

        $this->reservationViewRepository->_Reservations = [new TestReservationItemView(1, $start, $end, $resourceId, $refNum)];
        $series = ReservationSeries::Create(
            $this->fakeUser->UserId,
            new FakeBookableResource($resourceId),
            '',
            '',
            new DateRange($end, $end->AddMinutes(20)),
            new RepeatNone(),
            $this->fakeUser
        );
        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid());
    }

    public function testInvalidWhenOverLimitDuringCreate()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;
        $refNum = '123';
        $resource = new FakeBookableResource($resourceId);

        $this->reservationViewRepository->_Reservations = [new TestReservationItemView(1, $start, $end, $resourceId, $refNum)];
        $series = ReservationSeries::Create($this->fakeUser->UserId, $resource, '', '', new DateRange($start, $end), new RepeatNone(), $this->fakeUser);

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testInvalidWhenOverLimitDuringUpdate()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;
        $refNum = '123';
        $resource = new FakeBookableResource($resourceId);

        $this->reservationViewRepository->_Reservations = [
            new TestReservationItemView(1, $start, $end, $resourceId, $refNum),
            new TestReservationItemView(2, $start, $end, $resourceId, 'another'),
        ];
        $series = (new ExistingReservationSeriesBuilder())->WithPrimaryResource($resource)
                                                          ->WithCurrentInstance(new TestReservation($refNum, new DateRange($start, $end)))
                                                          ->Build();

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testInvalidWhenSingleReservationContainsMoreResourcesThanLimit()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $this->reservationViewRepository->_Reservations = [];
        $series = ReservationSeries::Create($this->fakeUser->UserId, new FakeBookableResource(1), '', '', new DateRange($start, $end), new RepeatNone(), $this->fakeUser);
        $series->AddResource(new FakeBookableResource(2));

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testChecksEachInstanceOfASeries()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(30);
        $resourceId = 1;
        $this->reservationViewRepository->_Reservations = [new TestReservationItemView(1, $start->AddDays(2), $end->AddDays(2), $resourceId, 'another res'),];
        $series = ReservationSeries::Create($this->fakeUser->UserId, new FakeBookableResource($resourceId), '', '', new DateRange($start, $end), new RepeatDaily(1, $end->AddDays(7)), $this->fakeUser);

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }

    public function testIncludesBufferTime()
    {
        $start = TestBase::GetTestDate();
        $end = $start->AddMinutes(90);
        $resourceId = 1;
        $testReservationItemView = new TestReservationItemView(1, $end, $end->AddMinutes(30), $resourceId, 'another res');
        $testReservationItemView->WithBufferTime(60);
        $this->reservationViewRepository->_Reservations = [$testReservationItemView,];
        $series = ReservationSeries::Create($this->fakeUser->UserId, new FakeBookableResource($resourceId), '', '', new DateRange($start, $end), new RepeatDaily(1, $end->AddDays(7)), $this->fakeUser);

        $this->scheduleRepository->_Schedule->SetTotalConcurrentReservations(1);

        $result = $this->rule->Validate($series, null);

        $this->assertFalse($result->IsValid());
    }
}
