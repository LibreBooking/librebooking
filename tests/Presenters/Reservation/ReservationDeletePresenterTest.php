<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationDeletePresenter.php');
require_once(ROOT_DIR . 'Pages/Ajax/ReservationDeletePage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationDeletePresenterTest extends TestBase
{
    private $userId;

    /**
     * @var UserSession
     */
    private $user;

    private IReservationDeletePage&\PHPUnit\Framework\MockObject\MockObject $page;

    private IDeleteReservationPersistenceService&\PHPUnit\Framework\MockObject\MockObject $persistenceService;

    private IReservationHandler&\PHPUnit\Framework\MockObject\MockObject $handler;

    /**
     * @var ReservationDeletePresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->user = $this->fakeServer->UserSession;
        $this->userId = $this->user->UserId;

        $this->persistenceService = $this->createMock('IDeleteReservationPersistenceService');
        $this->handler = $this->createMock('IReservationHandler');

        $this->page = $this->createMock('IReservationDeletePage');

        $this->presenter = new ReservationDeletePresenter(
            $this->page,
            $this->persistenceService,
            $this->handler,
            $this->user
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testLoadsExistingReservationAndDeletesIt()
    {
        $referenceNumber = '109809';
        $seriesUpdateScope = SeriesUpdateScope::ThisInstance;
        $reason = 'reason';

        $expectedSeries = $this->createMock('ExistingReservationSeries');

        $this->page->expects($this->once())
            ->method('GetReferenceNumber')
            ->willReturn($referenceNumber);

        $this->page->expects($this->once())
            ->method('GetSeriesUpdateScope')
            ->willReturn($seriesUpdateScope);

        $this->page->expects($this->once())
            ->method('GetReason')
            ->willReturn($reason);

        $this->persistenceService->expects($this->once())
            ->method('LoadByReferenceNumber')
            ->with($this->equalTo($referenceNumber))
            ->willReturn($expectedSeries);

        $expectedSeries->expects($this->once())
            ->method('Delete')
            ->with($this->equalTo($this->user), $this->equalTo($reason));

        $expectedSeries->expects($this->once())
            ->method('ApplyChangesTo')
            ->with($this->equalTo($seriesUpdateScope));

        $existingSeries = $this->presenter->BuildReservation();
    }

    public function testHandlingReservationCreationDelegatesToServicesForValidationAndPersistenceAndNotification()
    {
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $instance = new Reservation($series, NullDateRange::Instance());
        $series->WithCurrentInstance($instance);

        $this->handler->expects($this->once())
            ->method('Handle')
            ->with($this->equalTo($series), $this->equalTo($this->page))
            ->willReturn(true);


        $this->presenter->HandleReservation($series);
    }
}
