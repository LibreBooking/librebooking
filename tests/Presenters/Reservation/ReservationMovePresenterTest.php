<?php

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationMovePresenter.php');
require_once(ROOT_DIR . 'Pages/Ajax/ReservationMovePage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationMovePresenterTest extends TestBase
{
    private $userId;

    /**
     * @var UserSession
     */
    private $user;

    /**
     * @var FakeReservationMovePage
     */
    private $page;

    /**
     * @var IUpdateReservationPersistenceService
     */
    private $persistenceService;

    /**
     * @var IReservationHandler
     */
    private $handler;

    /**
     * @var ReservationMovePresenter
     */
    private $presenter;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->user = $this->fakeServer->UserSession;
        $this->userId = $this->user->UserId;

        $this->persistenceService = $this->createMock('IUpdateReservationPersistenceService');
        $this->handler = $this->createMock('IReservationHandler');
        $this->resourceRepository = $this->createMock('IResourceRepository');

        $this->page = new FakeReservationMovePage();

        $this->presenter = new ReservationMovePresenter(
            $this->page,
            $this->persistenceService,
            $this->handler,
            $this->resourceRepository,
            $this->fakeUser
        );
    }

    public function testAdjustsDuration()
    {
        $tuesdayAtNoon = Date::Parse('2016-04-26 12:30', 'UTC');
        $wednesdayAtOne = Date::Parse('2016-04-26 13:00', 'UTC');

        $this->page->_StartDate = $wednesdayAtOne->ToTimezone($this->user->Timezone);
        $this->page->_ResourceId = 1;
        $this->page->_OriginalResourceId = 1;

        $currentDuration = new DateRange($tuesdayAtNoon, $tuesdayAtNoon->AddHours(1));
        $newDuration = new DateRange($wednesdayAtOne, $wednesdayAtOne->AddHours(1));

        $expectedSeries = new ExistingReservationSeries();
        $expectedSeries->WithPrimaryResource(new FakeBookableResource(1));
        $reservation = new Reservation($expectedSeries, $currentDuration);
        $expectedSeries->WithCurrentInstance($reservation);

        $this->persistenceService->expects($this->once())
                                 ->method('LoadByReferenceNumber')
                                 ->with($this->equalTo($this->page->_ReferenceNumber))
                                 ->willReturn($expectedSeries);

        $this->handler->expects($this->once())
                      ->method('Handle')
                      ->with($this->equalTo($expectedSeries), $this->equalTo($this->page));

        $this->presenter->PageLoad();

        $this->assertEquals($newDuration, $expectedSeries->CurrentInstance()->Duration());
    }

    public function testChangesResource()
    {
        $this->page->_StartDate = '2016-05-01 12:00';
        $this->page->_ResourceId = 3;
        $this->page->_OriginalResourceId = 1;

        $newResource = new FakeBookableResource(3);

        $expectedSeries = new ExistingReservationSeries();
        $expectedSeries->WithPrimaryResource(new FakeBookableResource(1));
        $reservation = new Reservation($expectedSeries, new DateRange(Date::Now(), Date::Now()));
        $expectedSeries->WithCurrentInstance($reservation);

        $this->persistenceService->expects($this->once())
                                 ->method('LoadByReferenceNumber')
                                 ->with($this->equalTo($this->page->_ReferenceNumber))
                                 ->willReturn($expectedSeries);

        $this->resourceRepository->expects($this->once())
                    ->method('LoadById')
                    ->with($this->equalTo($this->page->_ResourceId))
                    ->willReturn($newResource);

        $this->presenter->PageLoad();

        $this->assertEquals($newResource, $expectedSeries->Resource());
        $this->assertEquals(1, count($expectedSeries->AllResources()));
    }

    public function testRemovesDuplicateResource()
    {
        $this->page->_StartDate = '2016-05-01 12:00';
        $this->page->_ResourceId = 2;
        $this->page->_OriginalResourceId = 1;

        $newResource = new FakeBookableResource(2);

        $expectedSeries = new ExistingReservationSeries();
        $expectedSeries->WithPrimaryResource(new FakeBookableResource(1));
        $expectedSeries->WithResource($newResource);
        $reservation = new Reservation($expectedSeries, new DateRange(Date::Now(), Date::Now()));
        $expectedSeries->WithCurrentInstance($reservation);

        $this->persistenceService->expects($this->once())
                                 ->method('LoadByReferenceNumber')
                                 ->with($this->equalTo($this->page->_ReferenceNumber))
                                 ->willReturn($expectedSeries);

        $this->presenter->PageLoad();

        $this->assertEquals($newResource, $expectedSeries->Resource());
        $this->assertEquals(1, count($expectedSeries->AllResources()));
    }
}

class FakeReservationMovePage implements IReservationMovePage
{
    public $_ReferenceNumber = 'reference number';
    public $_StartDate = '2016-04-30 15:21';
    public $_ResourceId;
    public $_OriginalResourceId;

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        // TODO: Implement SetSaveSuccessfulMessage() method.
    }

    /**
     * @param array|string[] $errors
     */
    public function SetErrors($errors)
    {
        // TODO: Implement SetErrors() method.
    }

    /**
     * @param array|string[] $warnings
     */
    public function SetWarnings($warnings)
    {
        // TODO: Implement SetWarnings() method.
    }

    /**
     * @return string
     */
    public function GetStartDate()
    {
        return $this->_StartDate;
    }

    /**
     * @return string
     */
    public function GetReferenceNumber()
    {
        return $this->_ReferenceNumber;
    }

    /**
     * @return int
     */
    public function GetResourceId()
    {
        return $this->_ResourceId;
    }

    /**
     * @return int
     */
    public function GetOriginalResourceId()
    {
        return $this->_OriginalResourceId;
    }

    /**
     * @param array|string[] $messages
     */
    public function SetRetryMessages($messages)
    {
        // TODO: Implement SetRetryMessages() method.
    }

    /**
     * @param bool $canBeRetried
     */
    public function SetCanBeRetried($canBeRetried)
    {
        // TODO: Implement SetCanBeRetried() method.
    }

    /**
     * @param ReservationRetryParameter[] $retryParameters
     */
    public function SetRetryParameters($retryParameters)
    {
        // TODO: Implement SetRetryParameters() method.
    }

    /**
     * @return ReservationRetryParameter[]
     */
    public function GetRetryParameters()
    {
        // TODO: Implement GetRetryParameters() method.
        return null;
    }

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // TODO: Implement SetCanJoinWaitList() method.
    }
}
