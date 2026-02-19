<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenter.php');
require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class NewReservationPresenterTest extends TestBase
{
    /**
     * @var UserSession
     */
    private $_user;

    /**
     * @var int
     */
    private $_userId;

    public function setUp(): void
    {
        parent::setup();

        $this->_user = $this->fakeServer->UserSession;
        $this->_userId = $this->_user->UserId;
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testPageLoadValidatesAllPreconditionsAndGetsReservationInitializerAndInitializes()
    {
        $page = $this->createMock('INewReservationPage');

        $reservationPreconditionService = $this->createMock('INewReservationPreconditionService');
        $reservationPreconditionService->expects($this->once())
            ->method('CheckAll')
            ->with($this->equalTo($page), $this->equalTo($this->_user));

        $reservationInitializerFactory = $this->createMock('IReservationInitializerFactory');
        $initializer = $this->createMock('IReservationInitializer');

        $reservationInitializerFactory->expects($this->once())
            ->method('GetNewInitializer')
            ->with($this->equalTo($page))
            ->willReturn($initializer);

        $initializer->expects($this->once())
            ->method('Initialize');

        $presenter = new ReservationPresenter($page, $reservationInitializerFactory, $reservationPreconditionService);
        $presenter->PageLoad();
    }
}
