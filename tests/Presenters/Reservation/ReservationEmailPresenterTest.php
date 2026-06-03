<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationEmailPresenter.php');
require_once(ROOT_DIR . 'Pages/Ajax/ReservationEmailPage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationEmailPresenterTest extends TestBase
{
    private $userId;

    /**
     * @var UserSession
     */
    private $user;

    /**
     * @var FakeReservationEmailPage
     */
    private $page;

    /**
     * @var ReservationEmailPresenter
     */
    private $presenter;

    /**
     * @var FakeReservationRepository
     */
    private $reservationRepository;

    /**
     * @var FakeUserRepository
     */
    private $userRepository;

    /**
     * @var FakeAttributeRepository
     */
    private $attributeRepository;

    /**
     * @var FakePermissionService
     */
    private $permissionService;

    public function setUp(): void
    {
        parent::setup();

        $this->user = $this->fakeServer->UserSession;
        $this->userId = $this->user->UserId;

        $this->reservationRepository = new FakeReservationRepository();
        $this->userRepository = new FakeUserRepository();
        $this->attributeRepository = new FakeAttributeRepository();
        $this->permissionService = new FakePermissionService();

        $this->page = new FakeReservationEmailPage();

        $this->presenter = new ReservationEmailPresenter(
            $this->page,
            $this->fakeUser,
            $this->reservationRepository,
            $this->userRepository,
            $this->attributeRepository,
            $this->permissionService
        );
    }

    public function testSendsEmailIfCurrentUserIsOwner()
    {
        $reservation = new TestHelperExistingReservationSeries();
        $reservation->WithOwner($this->fakeUser->UserId);
        $reservation->WithCurrentInstance(new TestReservation());

        $this->page->_EmailAddresses = ['email1', 'email2'];
        $this->reservationRepository->_Series = $reservation;

        $user = new FakeUser($this->fakeUser->UserId);
        $this->userRepository->_User = $user;

        $this->presenter->PageLoad();

        $messages = $this->fakeEmailService->_Messages;

        $this->assertEquals(count($this->page->_EmailAddresses), count($messages));
    }

    public function testSendsIfUserCanSeeReservation()
    {
        $reservation = new TestHelperExistingReservationSeries();
        $reservation->WithOwner(999);
        $reservation->WithCurrentInstance(new TestReservation());

        $this->permissionService->_CanViewResource[2] = true;
        $this->permissionService->_CanViewResource[3] = true;

        $this->page->_EmailAddresses = ['email1', 'email2'];
        $this->reservationRepository->_Series = $reservation;

        $user = new FakeUser($this->fakeUser->UserId);
        $this->userRepository->_User = $user;

        $this->presenter->PageLoad();

        $messages = $this->fakeEmailService->_Messages;

        $this->assertEquals(count($this->page->_EmailAddresses), count($messages));
    }

    public function testDoesNotSendIfUserCannotSeeReservation()
    {
        $reservation = new TestHelperExistingReservationSeries();
        $reservation->WithOwner(999);
        $reservation->WithCurrentInstance(new TestReservation());

        $this->permissionService->_CanViewResource[2] = false;
        $this->permissionService->_CanViewResource[3] = false;

        $this->page->_EmailAddresses = ['email1', 'email2'];
        $this->reservationRepository->_Series = $reservation;

        $user = new FakeUser($this->fakeUser->UserId);
        $this->userRepository->_User = $user;

        $this->presenter->PageLoad();

        $messages = $this->fakeEmailService->_Messages;

        $this->assertEquals(0, count($messages));
    }
}

class FakeReservationEmailPage implements IReservationEmailPage
{
    public $_ReferenceNumber = 'reference number';
    public $_EmailAddresses = [];

    public function GetReferenceNumber()
    {
        return $this->_ReferenceNumber;
    }

    public function GetEmailAddresses()
    {
        return $this->_EmailAddresses;
    }
}
