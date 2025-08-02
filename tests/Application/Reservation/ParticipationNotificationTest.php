<?php

class ParticipationNotificationTest extends TestBase
{
    /**
     * @var FakeUserRepository
     */
    private $userRepository;
    /**
     * @var ParticipationNotification
     */
    private $participationNotification;

    public function setUp(): void
    {
        parent::setup();
        $this->userRepository = new FakeUserRepository();
        $this->participationNotification = new ParticipationNotification($this->userRepository);
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, 'false');
    }

    public function testWhenOwnerWantsEmails()
    {
        $user = new FakeUser();
        $user->_WantsEmail = true;
        $this->userRepository->_User = $user;

        $this->participationNotification->Notify(new TestHelperExistingReservationSeries(), 123, InvitationAction::Accept);

        $this->assertInstanceOf('ReservationParticipationActivityEmail', $this->fakeEmailService->_LastMessage);
    }

    public function testWhenOwnerDoesNotWantEmails()
    {
        $user = new FakeUser();
        $user->_WantsEmail = false;
        $this->userRepository->_User = $user;

        $this->participationNotification->NotifyGuest(new TestHelperExistingReservationSeries(), 'guest@guest', InvitationAction::Decline);

        $this->assertEmpty($this->fakeEmailService->_LastMessage);
    }
}
