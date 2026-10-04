<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationAttachmentPresenter.php');

class ReservationAttachmentPresenterTest extends TestBase
{
    /**
     * @var ReservationAttachmentPresenter
     */
    private $presenter;

    /**
     * @var IReservationAttachmentPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IReservationRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationRepository;

    /**
     * @var FakePermissionService
     */
    private $fakePermissionService;

    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationViewRepository;

    /**
     * @var IReservationAuthorization|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationAuthorization;

    public function setUp(): void
    {
        $this->fakePermissionService = new FakePermissionService([true]);
        $this->reservationRepository = $this->createMock('IReservationRepository');
        $this->page = $this->createMock('IReservationAttachmentPage');

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->reservationAuthorization = $this->createMock('IReservationAuthorization');
        $this->reservationViewRepository->method('GetReservationForEditing')->willReturn(new ReservationView());

        $this->presenter = new ReservationAttachmentPresenter(
            $this->page,
            $this->reservationRepository,
            $this->fakePermissionService,
            $this->reservationViewRepository,
            $this->reservationAuthorization
        );

        parent::setup();
    }

    public function testLoadsAttachmentIfUserHasPermissionToPrimaryResource()
    {
        $this->reservationAuthorization->method('CanViewAttachments')->willReturn(true);
        $fileId = 110;
        $resourceId = 1909;
        $referenceNumber = 'rn';
        $seriesId = 1;

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource(new FakeBookableResource($resourceId));
        $builder->WithId($seriesId);
        $reservationSeries = $builder->Build();

        $reservationAttachment = new FakeReservationAttachment($fileId);
        $reservationAttachment->SetSeriesId($seriesId);

        $this->page->expects($this->once())
                ->method('GetFileId')
                ->willReturn($fileId);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->reservationRepository->expects($this->once())
                ->method('LoadReservationAttachment')
                ->with($this->equalTo($fileId))
                ->willReturn($reservationAttachment);

        $this->reservationRepository->expects($this->once())
                ->method('LoadByReferenceNumber')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationSeries);

        $this->page->expects($this->once())
                ->method('BindAttachment')
                ->with($this->equalTo($reservationAttachment));

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertEquals(1, count($this->fakePermissionService->Resources));
        $this->assertEquals(new ReservationResource($resourceId), $this->fakePermissionService->Resources[0]);
        $this->assertEquals($this->fakeUser, $this->fakePermissionService->User);
    }

    public function testShowsErrorIfUserDoesNotHavePermissionToPrimaryResource()
    {
        $this->fakePermissionService->ReturnValues = [false];
        $fileId = 110;
        $resourceId = 1909;
        $referenceNumber = 'rn';
        $seriesId = 1;

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource(new FakeBookableResource($resourceId));
        $builder->WithId($seriesId);
        $reservationSeries = $builder->Build();

        $reservationAttachment = new FakeReservationAttachment($fileId);
        $reservationAttachment->SetSeriesId($seriesId);

        $this->page->expects($this->once())
                ->method('GetFileId')
                ->willReturn($fileId);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->reservationRepository->expects($this->once())
                ->method('LoadReservationAttachment')
                ->with($this->equalTo($fileId))
                ->willReturn($reservationAttachment);

        $this->reservationRepository->expects($this->once())
                ->method('LoadByReferenceNumber')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationSeries);

        $this->page->expects($this->once())
                ->method('ShowError');

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertEquals(1, count($this->fakePermissionService->Resources));
        $this->assertEquals(new ReservationResource($resourceId), $this->fakePermissionService->Resources[0]);
        $this->assertEquals($this->fakeUser, $this->fakePermissionService->User);
    }

    public function testShowsErrorIfReservationNotFound()
    {
        $fileId = 110;
        $referenceNumber = 'rn';

        $reservationAttachment = new FakeReservationAttachment($fileId);

        $this->page->expects($this->once())
                ->method('GetFileId')
                ->willReturn($fileId);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->reservationRepository->expects($this->once())
                ->method('LoadReservationAttachment')
                ->with($this->equalTo($fileId))
                ->willReturn($reservationAttachment);

        $this->reservationRepository->expects($this->once())
                ->method('LoadByReferenceNumber')
                ->with($this->equalTo($referenceNumber))
                ->willReturn(null);

        $this->page->expects($this->once())
                ->method('ShowError');

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testShowsErrorIfFileNotFound()
    {
        $fileId = 110;
        $referenceNumber = 'rn';

        $this->page->expects($this->once())
                ->method('GetFileId')
                ->willReturn($fileId);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->reservationRepository->expects($this->once())
                ->method('LoadReservationAttachment')
                ->with($this->equalTo($fileId))
                ->willReturn(null);

        $this->page->expects($this->once())
                ->method('ShowError');

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testShowsErrorFileDoesNotBelongToReservation()
    {
        $fileId = 110;
        $resourceId = 1909;
        $referenceNumber = 'rn';
        $seriesId = 1;
        $fileSeriesId = 2;

        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithId($seriesId);
        $builder->WithPrimaryResource(new FakeBookableResource($resourceId));
        $reservationSeries = $builder->Build();

        $reservationAttachment = new FakeReservationAttachment($fileId);
        $reservationAttachment->SetSeriesId($fileSeriesId);

        $this->page->expects($this->once())
                ->method('GetFileId')
                ->willReturn($fileId);

        $this->page->expects($this->once())
                ->method('GetReferenceNumber')
                ->willReturn($referenceNumber);

        $this->reservationRepository->expects($this->once())
                ->method('LoadReservationAttachment')
                ->with($this->equalTo($fileId))
                ->willReturn($reservationAttachment);

        $this->reservationRepository->expects($this->once())
                ->method('LoadByReferenceNumber')
                ->with($this->equalTo($referenceNumber))
                ->willReturn($reservationSeries);

        $this->page->expects($this->once())
                ->method('ShowError');

        $this->presenter->PageLoad($this->fakeUser);
    }

    public function testShowsErrorIfUserCanBookResourceButIsNotRelatedToReservation()
    {
        $this->ArrangeAttachmentOnReservation(new ReservationView());
        $this->reservationAuthorization->method('CanViewAttachments')->willReturn(false);

        $this->page->expects($this->once())->method('ShowError');
        $this->page->expects($this->never())->method('BindAttachment');

        $this->presenter->PageLoad($this->fakeUser);
    }

    private function ArrangeAttachmentOnReservation(ReservationView $view): void
    {
        $seriesId = 1;
        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource(new FakeBookableResource(1909));
        $builder->WithId($seriesId);

        $attachment = new FakeReservationAttachment(110);
        $attachment->SetSeriesId($seriesId);

        $this->page->method('GetFileId')->willReturn(110);
        $this->page->method('GetReferenceNumber')->willReturn('rn');
        $this->reservationRepository->method('LoadReservationAttachment')->willReturn($attachment);
        $this->reservationRepository->method('LoadByReferenceNumber')->willReturn($builder->Build());

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->reservationViewRepository->method('GetReservationForEditing')->willReturn($view);
        $this->presenter = new ReservationAttachmentPresenter(
            $this->page,
            $this->reservationRepository,
            $this->fakePermissionService,
            $this->reservationViewRepository,
            $this->reservationAuthorization
        );
    }
}
