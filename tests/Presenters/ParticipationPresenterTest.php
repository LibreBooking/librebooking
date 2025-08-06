<?php

require_once(ROOT_DIR . 'Presenters/ParticipationPresenter.php');

class ParticipationPresenterTest extends TestBase
{
    /**
     * @var IParticipationPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IReservationRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationRepo;

    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationViewRepo;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $scheduleRepository;

    /**
     * @var ParticipationPresenter
     */
    private $presenter;
    /**
     * @var FakeParticipationNotification
     */
    private $participationNotification;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IParticipationPage');
        $this->reservationRepo = $this->createMock('IReservationRepository');
        $this->reservationViewRepo = $this->createMock('IReservationViewRepository');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->participationNotification = new FakeParticipationNotification();
        $rules = [new ReservationStartTimeRule($this->scheduleRepository), new ResourceMinimumNoticeRuleAdd($this->fakeUser), new ResourceMaximumNoticeRule($this->fakeUser)];
        $this->presenter = new ParticipationPresenter(
            $this->page,
            $this->reservationRepo,
            $this->reservationViewRepo,
            $this->participationNotification
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenUserAcceptsInviteAndThereIsSpace()
    {
        $invitationAction = InvitationAction::Accept;
        $seriesMethod = 'AcceptInvitation';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenUserAcceptsInviteAndThereIsNotSpace()
    {
        $invitationAction = InvitationAction::Accept;

        $currentUserId = 1029;
        $referenceNumber = 'abc123';
        $builder = new ExistingReservationSeriesBuilder();
        $instance = new TestReservation();
        $instance->WithParticipants([1]);
        $instance->WithInvitee($currentUserId);

        $resource = new FakeBookableResource(1);
        $resource->SetMaxParticipants(1);

        $builder->WithCurrentInstance($instance)
            ->WithPrimaryResource($resource);

        $series = $builder->Build();

        $this->page->expects($this->once())
            ->method('GetResponseType')
            ->willReturn('json');

        $this->page->expects($this->once())
            ->method('GetInvitationAction')
            ->willReturn($invitationAction);

        $this->page->expects($this->once())
            ->method('GetInvitationReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetUserId')
            ->willReturn($currentUserId);

        $this->reservationRepo->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($series);

        $this->page->expects($this->once())
            ->method('DisplayResult')
            ->with($this->stringContains('MaxParticipants'));

        $this->presenter->PageLoad();
    }

    public function testWhenUserJoinsAndThereIsSpace()
    {
        $invitationAction = InvitationAction::Join;
        $seriesMethod = 'JoinReservation';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenUserJoinsAndThereIsNotSpace()
    {
        $invitationAction = InvitationAction::Join;

        $currentUserId = 1029;
        $referenceNumber = 'abc123';
        $builder = new ExistingReservationSeriesBuilder();
        $instance = new TestReservation();
        $instance->WithParticipants([1]);
        $instance->WithInvitee($currentUserId);

        $resource = new FakeBookableResource(1);
        $resource->SetMaxParticipants(1);

        $builder->WithCurrentInstance($instance)
            ->WithPrimaryResource($resource);

        $series = $builder->Build();

        $this->page->expects($this->once())
            ->method('GetResponseType')
            ->willReturn('json');

        $this->page->expects($this->once())
            ->method('GetInvitationAction')
            ->willReturn($invitationAction);

        $this->page->expects($this->once())
            ->method('GetInvitationReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetUserId')
            ->willReturn($currentUserId);

        $this->reservationRepo->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($series);

        $this->page->expects($this->once())
            ->method('DisplayResult')
            ->with($this->stringContains('ParticipationNotAllowed'));

        $this->presenter->PageLoad();

        $this->assertFalse($this->participationNotification->_Notified);
    }

    public function testWhenUserJoinsAllAndThereIsSpace()
    {
        $invitationAction = InvitationAction::JoinAll;
        $seriesMethod = 'JoinReservationSeries';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenUserJoinsAllAndThereIsNotSpace()
    {
        $invitationAction = InvitationAction::JoinAll;

        $currentUserId = 1029;
        $referenceNumber = 'abc123';
        $builder = new ExistingReservationSeriesBuilder();
        $instance = new TestReservation();
        $instance->WithParticipants([1]);
        $instance->WithInvitee($currentUserId);

        $resource = new FakeBookableResource(1);
        $resource->SetMaxParticipants(1);

        $builder->WithCurrentInstance($instance)
            ->WithPrimaryResource($resource);

        $series = $builder->Build();

        $this->page->expects($this->once())
            ->method('GetResponseType')
            ->willReturn('json');

        $this->page->expects($this->once())
            ->method('GetInvitationAction')
            ->willReturn($invitationAction);

        $this->page->expects($this->once())
            ->method('GetInvitationReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetUserId')
            ->willReturn($currentUserId);

        $this->reservationRepo->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($series);

        $this->page->expects($this->once())
            ->method('DisplayResult')
            ->with($this->stringContains('ParticipationNotAllowed'));

        $this->presenter->PageLoad();

        $this->assertFalse($this->participationNotification->_Notified);
    }

    public function testWhenUserDeclinesInvite()
    {
        $invitationAction = InvitationAction::Decline;
        $seriesMethod = 'DeclineInvitation';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenUserCancelsAllParticipation()
    {
        $invitationAction = InvitationAction::CancelAll;
        $seriesMethod = 'CancelAllParticipation';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenUserCancelsInstanceParticipation()
    {
        $invitationAction = InvitationAction::CancelInstance;
        $seriesMethod = 'CancelInstanceParticipation';

        $this->assertUpdatesSeriesParticipation($invitationAction, $seriesMethod);
    }

    public function testWhenViewingOpenInvites()
    {
        $startDate = Date::Now();
        $endDate = $startDate->AddDays(30);
        $userId = $this->fakeUser->UserId;
        $inviteeLevel = ReservationUserLevel::INVITEE;

        $reservations[] = new ReservationItemView();
        $reservations[] = new ReservationItemView();
        $reservations[] = new ReservationItemView();

        $this->reservationViewRepo->expects($this->once())
            ->method('GetReservations')
            ->with($this->equalTo($startDate), $this->equalTo($endDate), $this->equalTo($userId), $this->equalTo($inviteeLevel))
            ->willReturn($reservations);

        $this->page->expects($this->once())
            ->method('BindReservations')
            ->with($this->equalTo($reservations));

        $this->presenter->PageLoad();
    }

    public function testWhenReservationStartConstraintIsViolated()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, ReservationStartTimeConstraint::FUTURE);

        $referenceNumber = 'abc';
        $currentUserId = 1029;

        $builder = new ExistingReservationSeriesBuilder();
        $instance = new TestReservation($referenceNumber, new DateRange(Date::Now()->AddMinutes(-1), Date::Now()->AddMinutes(30)), null);
        $builder->WithCurrentInstance($instance);

        $series = $builder->Build();

        $this->page->expects($this->once())
            ->method('GetResponseType')
            ->willReturn('json');

        $this->page->expects($this->once())
            ->method('GetInvitationAction')
            ->willReturn(InvitationAction::Join);

        $this->page->expects($this->once())
            ->method('GetInvitationReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetUserId')
            ->willReturn($currentUserId);

        $this->reservationRepo->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($series);

        $this->page->expects($this->once())
            ->method('DisplayResult')
            ->with($this->stringContains('StartIsInPast'));

        $this->presenter->PageLoad();

        $this->assertFalse($this->participationNotification->_Notified);
    }

    private function assertUpdatesSeriesParticipation($invitationAction, $seriesMethod)
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, ReservationStartTimeConstraint::NONE);
        $currentUserId = 1029;
        $referenceNumber = 'abc123';
        $series = $this->createMock('ExistingReservationSeries');
        $series->expects($this->any())->method('GetAllowParticipation')->willReturn(true);
        $series->expects($this->any())->method('AllResources')->willReturn([]);

        $this->page->expects($this->once())
            ->method('GetResponseType')
            ->willReturn('json');

        $this->page->expects($this->once())
            ->method('GetInvitationAction')
            ->willReturn($invitationAction);

        $this->page->expects($this->once())
            ->method('GetInvitationReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetUserId')
            ->willReturn($currentUserId);

        $this->reservationRepo->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($series);

        $series->expects($this->once())
            ->method($seriesMethod)
            ->with($this->equalTo($currentUserId));

        $this->reservationRepo->expects($this->once())
            ->method('Update')
            ->with($this->equalTo($series));

        $this->page->expects($this->once())
            ->method('DisplayResult')
            ->with($this->equalTo(null));

        $this->presenter->PageLoad();

        $this->assertTrue($this->participationNotification->_Notified);
    }
}

class FakeParticipationNotification implements IParticipationNotification
{
    public $_Notified = false;

    public function Notify(ExistingReservationSeries $series, $participantId, $invitationAction)
    {
        $this->_Notified = true;
    }


    public function NotifyGuest(ExistingReservationSeries $series, $guestEmail, $invitationAction)
    {
        // TODO: Implement NotifyGuest() method.
    }
}
