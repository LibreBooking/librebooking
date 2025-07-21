<?php

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class ManageReservationsServiceTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationViewRepository;

    /**
     * @var IReservationAuthorization|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationAuthorization;

    /**
     * @var IReservationHandler|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationHandler;

    /**
     * @var IUpdateReservationPersistenceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $persistenceService;

    /**
     * @var ManageReservationsService
     */
    private $service;

    /**
     * @var FakeReservationRepository
     */
    private $reservationRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->reservationAuthorization = $this->createMock('IReservationAuthorization');
        $this->reservationHandler = $this->createMock('IReservationHandler');
        $this->persistenceService = $this->createMock('IUpdateReservationPersistenceService');
        $this->reservationRepository = new FakeReservationRepository();

        $this->service = new ManageReservationsService($this->reservationViewRepository, $this->reservationAuthorization, $this->reservationHandler, $this->persistenceService, $this->reservationRepository);
    }

    public function testLoadsFilteredResultsAndChecksAuthorizationAgainstPendingReservations()
    {
        $pageNumber = 1;
        $pageSize = 40;

        $filter = new ReservationFilter();

        $data = new PageableData();
        $this->reservationViewRepository->expects($this->once())
                ->method('GetList')
                ->with($pageNumber, $pageSize, null, null, $filter->GetFilter())
                ->willReturn($data);

        $actualData = $this->service->LoadFiltered($pageNumber, $pageSize, null, null, $filter, $this->fakeUser);

        $this->assertEquals($data, $actualData);
    }

    public function testLoadsReservationIfTheUserCanEdit()
    {
        $reservation = new ReservationView();
        $user = $this->fakeUser;
        $referenceNumber = 'rn';

        $this->reservationViewRepository->expects($this->once())
                    ->method('GetReservationForEditing')
                    ->with($this->equalTo($referenceNumber))
                    ->willReturn($reservation);

        $this->reservationAuthorization->expects($this->once())
                    ->method('CanEdit')
                    ->with($this->equalTo($reservation), $this->equalTo($user))
                    ->willReturn(true);

        $res = $this->service->LoadByReferenceNumber($referenceNumber, $user);

        $this->assertEquals($reservation, $res);
    }

    public function testUpdatesReservationAttributeIfTheUserCanEdit()
    {
        $referenceNumber = 'rn';
        $id = 111;
        $value = 'new attribute value';

        $user = $this->fakeUser;

        $resultCollector = new ManageReservationsUpdateAttributeResultCollector();

        $reservation = new ExistingReservationSeries();
        $reservation->UpdateBookedBy($user);
        $reservation->AddAttributeValue(new AttributeValue($id, $value));

        $this->persistenceService->expects($this->once())
                    ->method('LoadByReferenceNumber')
                    ->with($this->equalTo($referenceNumber))
                    ->willReturn($reservation);

        $this->reservationHandler->expects($this->once())
                    ->method('Handle')
                    ->with($this->equalTo($reservation), $this->equalTo($resultCollector));

        $result = $this->service->UpdateAttribute($referenceNumber, $id, $value, $user);
    }

    public function testUnsafeDeleteDeletesInstance()
    {
        $id = 123;
        $series = new TestHelperExistingReservationSeries();
        $this->reservationRepository->_Series = $series;

        $this->service->UnsafeDelete($id, $this->fakeUser);

        $this->assertTrue($series->_WasDeleted);
        $this->assertEquals($series, $this->reservationRepository->_LastDeleted);
    }
}
