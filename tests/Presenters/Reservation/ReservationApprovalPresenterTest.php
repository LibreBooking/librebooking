<?php

require_once(ROOT_DIR . 'Pages/Ajax/ReservationApprovalPage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationApprovalPresenter.php');

class ReservationApprovalPresenterTest extends TestBase
{
    /**
     * @var IReservationApprovalPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IUpdateReservationPersistenceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $persistence;

    /**
     * @var IReservationHandler|PHPUnit\Framework\MockObject\MockObject
     */
    private $handler;

    /**
     * @var IReservationAuthorization|PHPUnit\Framework\MockObject\MockObject
     */
    private $auth;

    /**
     * @var ReservationApprovalPresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IReservationApprovalPage');
        $this->persistence = $this->createMock('IUpdateReservationPersistenceService');
        $this->handler = $this->createMock('IReservationHandler');
        $this->auth = $this->createMock('IReservationAuthorization');

        $this->presenter = new ReservationApprovalPresenter($this->page, $this->persistence, $this->handler, $this->auth, $this->fakeUser);
    }

    public function testLoadAndApprovesReservationSendingNotifications()
    {
        $referenceNumber = 'rn';

        $builder = new ExistingReservationSeriesBuilder();
        $reservation = $builder->Build();

        $this->page->expects($this->once())
            ->method('GetReferenceNumber')
            ->willReturn($referenceNumber);

        $this->persistence->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($reservation);

        $this->handler->expects($this->once())
            ->method('Handle')
            ->with($this->equalTo($reservation), $this->equalTo($this->page))
            ->willReturn(true);

        $this->auth->expects($this->once())
                    ->method('CanApprove')
                    ->with($this->equalTo(new ReservationViewAdapter($reservation)), $this->equalTo($this->fakeUser))
                    ->willReturn(true);

        $this->presenter->PageLoad();

        $this->assertTrue(in_array(new SeriesApprovedEvent($reservation), $reservation->GetEvents()));
    }
}
