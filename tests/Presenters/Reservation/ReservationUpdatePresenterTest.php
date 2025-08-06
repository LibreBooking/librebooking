<?php

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationUpdatePresenter.php');
require_once(ROOT_DIR . 'Pages/Ajax/ReservationUpdatePage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationUpdatePresenterTest extends TestBase
{
    private $userId;

    /**
     * @var UserSession
     */
    private $user;

    /**
     * @var FakeReservationUpdatePage
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
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var ReservationUpdatePresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->user = $this->fakeServer->UserSession;
        $this->userId = $this->user->UserId;

        $this->persistenceService = $this->createMock('IUpdateReservationPersistenceService');
        $this->handler = $this->createMock('IReservationHandler');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->scheduleRepository = new FakeScheduleRepository();

        $this->page = new FakeReservationUpdatePage();

        $this->presenter = new ReservationUpdatePresenter(
            $this->page,
            $this->persistenceService,
            $this->handler,
            $this->resourceRepository,
            $this->scheduleRepository,
            $this->fakeUser
        );
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testLoadsExistingReservationAndUpdatesData()
    {
        $this->fakeConfig->SetKey(ConfigKeys::CREDITS_ENABLED, 'true');

        $seriesId = 109809;
        $expectedSeries = new ExistingReservationSeries();
        $currentDuration = new DateRange(Date::Now()->AddDays(1), Date::Now()->AddDays(2), 'UTC');
        $removedResourceId = 190;

        $resource = new FakeBookableResource(1);
        $resource->SetCreditsPerSlot(1);
        $resource->SetPeakCreditsPerSlot(1);
        $additionalId1 = $this->page->resourceIds[0];
        $additionalId2 = $this->page->resourceIds[1];
        $additional1 = new FakeBookableResource($additionalId1);
        $additional2 = new FakeBookableResource($additionalId2);

        $reservation = new Reservation($expectedSeries, $currentDuration, 1);
        $expectedSeries->WithId($seriesId);
        $expectedSeries->WithCurrentInstance($reservation);
        $expectedSeries->WithPrimaryResource($resource);
        $expectedSeries->WithResource(new FakeBookableResource($removedResourceId));
        $expectedSeries->WithAttribute(new AttributeValue(100, 'to be removed'));
        $expectedSeries->WithAttachment(1, '1');
        $expectedSeries->WithAttachment(2, '1');
        $expectedSeries->WithAttachment(3, '1');

        $referenceNumber = $this->page->existingReferenceNumber;

        $timezone = $this->user->Timezone;

        $this->persistenceService->expects($this->once())
                                 ->method('LoadByReferenceNumber')
                                 ->with($this->equalTo($referenceNumber))
                                 ->willReturn($expectedSeries);

        $this->resourceRepository->expects($this->exactly(3))
                                 ->method('LoadById')
                                 ->willReturnMap(
                                 [
                                     [$this->page->resourceId, $resource],
                                     [$additionalId1, $additional1],
                                     [$additionalId2, $additional2]
                                 ]);

        $this->page->repeatType = RepeatType::Daily;
        $roFactory = new RepeatOptionsFactory();
        $repeatOptions = $roFactory->CreateFromComposite($this->page, $this->user->Timezone);

        $expectedDuration = DateRange::Create(
            $this->page->GetStartDate() . " " . $this->page->GetStartTime(),
            $this->page->GetEndDate() . " " . $this->page->GetEndTime(),
            $timezone
        );

        $attachment = new FakeUploadedFile();
        $this->page->attachment = $attachment;

        $this->page->hasEndReminder = false;
        $participatingGuests = ['p1@email.com'];
        $this->page->participatingGuests = $participatingGuests;
        $invitedGuests = ['i1@email.com'];
        $this->page->invitedGuests = $invitedGuests;

        $fakeScheduleLayout = new FakeScheduleLayout();
        $fakeScheduleLayout->_SlotCount = new SlotCount(1, 2);
        $this->scheduleRepository->_Layout = $fakeScheduleLayout;
        $expectedCredits = 168;

        $existingSeries = $this->presenter->BuildReservation();

        $expectedAccessories = [new ReservationAccessory(1, 2, 'accessoryname')];
        $expectedAttributes = [1 => new AttributeValue(1, 'something')];


        $this->assertEquals($seriesId, $existingSeries->SeriesId());
        $this->assertEquals($this->page->seriesUpdateScope, $existingSeries->SeriesUpdateScope());
        $this->assertEquals($this->page->title, $existingSeries->Title());
        $this->assertEquals($this->page->description, $existingSeries->Description());
        $this->assertEquals($this->page->userId, $existingSeries->UserId());
        $this->assertEquals($resource, $existingSeries->Resource());
        $this->assertEquals($repeatOptions, $existingSeries->RepeatOptions());
        $this->assertEquals([$additional1, $additional2], $existingSeries->AdditionalResources());
        $this->assertEquals($this->page->participants, $existingSeries->CurrentInstance()->AddedParticipants());
        $this->assertEquals($this->page->invitees, $existingSeries->CurrentInstance()->AddedInvitees());
        $this->assertTrue(
            $expectedDuration->Equals($existingSeries->CurrentInstance()->Duration()),
            "Expected: $expectedDuration Actual: {$existingSeries->CurrentInstance()->Duration()}"
        );
        $this->assertEquals($this->user, $expectedSeries->BookedBy());
        $this->assertEquals($expectedAccessories, $existingSeries->Accessories());
        $this->assertEquals($expectedAttributes, $existingSeries->AttributeValues());
        $expectedAttachment = ReservationAttachment::Create(
            $attachment->OriginalName(),
            $attachment->MimeType(),
            $attachment->Size(),
            $attachment->Contents(),
            $attachment->Extension(),
            $seriesId
        );
        $this->assertEquals([$expectedAttachment], $expectedSeries->AddedAttachments());
        $this->assertEquals($this->page->removedFileIds, $existingSeries->RemovedAttachmentIds());
        $this->assertEquals(
            new ReservationReminder($this->page->GetStartReminderValue(), $this->page->GetStartReminderInterval()),
            $existingSeries->GetStartReminder()
        );
        $this->assertEquals(ReservationReminder::None(), $existingSeries->GetEndReminder());
        $this->assertEquals($participatingGuests, $existingSeries->CurrentInstance()->AddedParticipatingGuests());
        $this->assertEquals($invitedGuests, $existingSeries->CurrentInstance()->AddedInvitedGuests());
        $this->assertEquals($expectedCredits, $existingSeries->GetCreditsRequired(), '3 credits, 28 instances, 2 resources');
    }

    public function testUsesFirstAdditionalResourceIfPrimaryIsRemoved()
    {
        $referenceNumber = $this->page->existingReferenceNumber;
        $builder = new ExistingReservationSeriesBuilder();
        $builder->WithPrimaryResource(new FakeBookableResource(100));
        $expectedSeries = $builder->Build();

        $additionalId = 5;
        $this->page->resourceId = null;
        $this->page->resourceIds = [$additionalId];

        $resource = new FakeBookableResource($additionalId);

        $this->persistenceService->expects($this->once())
                                 ->method('LoadByReferenceNumber')
                                 ->with($this->equalTo($referenceNumber))
                                 ->willReturn($expectedSeries);

        $this->resourceRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with($this->equalTo($additionalId))
                                 ->willReturn($resource);

        $existingSeries = $this->presenter->BuildReservation();

        $this->assertEquals($resource, $existingSeries->Resource());
    }

    public function testHandlingReservationCreationDelegatesToServicesForValidationAndPersistenceAndNotification()
    {
        $builder = new ExistingReservationSeriesBuilder();
        $series = $builder->Build();
        $series->SetStatusId(ReservationStatus::Pending);
        $instance = new Reservation($series, NullDateRange::Instance());
        $series->WithCurrentInstance($instance);

        $this->handler->expects($this->once())
                      ->method('Handle')
                      ->with($this->equalTo($series), $this->isInstanceOf('FakeReservationUpdatePage'))
                      ->willReturn(true);

        $this->presenter->HandleReservation($series);

        $this->assertEquals($instance->ReferenceNumber(), $this->page->referenceNumber);
        $this->assertEquals($series->RequiresApproval(), $this->page->requiresApproval);
    }
}

class FakeReservationUpdatePage extends FakeReservationSavePage implements IReservationUpdatePage
{
    public $existingReferenceNumber = 100;
    public $seriesUpdateScope = SeriesUpdateScope::FullSeries;
    public $removedFileIds = [1, 2, 3];

    public function __construct()
    {
        parent::__construct();
    }

    public function GetReferenceNumber()
    {
        return $this->existingReferenceNumber;
    }

    public function GetSeriesUpdateScope()
    {
        return $this->seriesUpdateScope;
    }

    public function GetRemovedAttachmentIds()
    {
        return $this->removedFileIds;
    }
}
