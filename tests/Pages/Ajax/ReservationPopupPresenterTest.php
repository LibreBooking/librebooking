<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Ajax/ReservationPopupPage.php');

class ReservationPopupPresenterTest extends TestBase
{
    private $page;
    private $reservationRepository;
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock(IReservationPopupPage::class);
        $this->page->method('GetReservationId')->willReturn(1);

        $view = new ReservationView();
        $view->StartDate = Date::Now();
        $view->EndDate = Date::Now()->AddDays(1);
        $view->Resources = [
            new ReservationResourceView(10, 'r1', null, 1, null, false, null),
            new ReservationResourceView(11, 'r2', null, 1, null, false, null),
        ];

        $this->reservationRepository = $this->createMock(IReservationViewRepository::class);
        $this->reservationRepository->method('GetReservationForEditing')->willReturn($view);

        $userRepository = new FakeUserRepository();
        $userRepository->_User = new FakeUser(0);

        $this->presenter = new ReservationPopupPresenter(
            $this->page,
            $this->reservationRepository,
            $this->createMock(IReservationAuthorization::class),
            new FakeAttributeService(),
            $userRepository
        );
    }

    public function testPublicUserSeesReservationResourcesWhenPublicDetailsEnabled()
    {
        $this->fakeServer->SetUserSession(new NullUserSession());
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, true);
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_PUBLIC_RESERVATION_DETAILS, true);

        $this->page->expects($this->once())->method('BindViewableResourceReservations')->with([10, 11]);

        $this->presenter->PageLoad();
    }
}
