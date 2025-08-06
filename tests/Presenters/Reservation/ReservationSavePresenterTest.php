<?php

require_once(ROOT_DIR . 'Presenters/Reservation/ReservationSavePresenter.php');
require_once(ROOT_DIR . 'Pages/Ajax/ReservationSavePage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class ReservationSavePresenterTest extends TestBase
{
    /**
     * @var UserSession
     */
    private $user;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var IReservationSavePage|FakeReservationSavePage
     */
    private $page;

    /**
     * @var ReservationSavePresenter
     */
    private $presenter;

    /**
     * @var IReservationPersistenceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $persistenceService;

    /**
     * @var IReservationHandler|PHPUnit\Framework\MockObject\MockObject
     */
    private $handler;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    /**
     * @var FakeScheduleRepository
     */
    private $scheduleRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->user = $this->fakeServer->UserSession;
        $this->userId = $this->user->UserId;

        $this->page = new FakeReservationSavePage();

        $this->persistenceService = $this->createMock('IReservationPersistenceService');
        $this->handler = $this->createMock('IReservationHandler');
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->scheduleRepository = new FakeScheduleRepository();

        $this->presenter = new ReservationSavePresenter(
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

    public function testCreationBuildsReservationFromPageData()
    {
        $this->fakeConfig->SetKey(ConfigKeys::CREDITS_ENABLED, 'true');
        $timezone = $this->user->Timezone;

        $userId = $this->page->GetUserId();
        $resourceId = $this->page->GetResourceId();
        $title = $this->page->GetTitle();
        $description = $this->page->GetDescription();

        $startDate = $this->page->GetStartDate();
        $endDate = $this->page->GetEndDate();
        $startTime = $this->page->GetStartTime();
        $endTime = $this->page->GetEndTime();
        $additionalResources = $this->page->GetResources();
        $pageAccessories = $this->page->GetAccessories();
        $pageAttributes = $this->page->GetAttributes();
        $participatingGuests = ['p1@email.com'];
        $this->page->participatingGuests = $participatingGuests;
        $invitedGuests = ['i1@email.com'];
        $this->page->invitedGuests = $invitedGuests;

        $roFactory = new RepeatOptionsFactory();
        $repeatOptions = $roFactory->CreateFromComposite($this->page, $timezone);

        $participants = $this->page->GetParticipants();
        $invitees = $this->page->GetInvitees();
        $attachment = new FakeUploadedFile();
        $this->page->attachment = $attachment;

        $resource = new FakeBookableResource($resourceId, 'r1');
        $resource->SetCreditsPerSlot(1);
        $resource->SetPeakCreditsPerSlot(1);
        $additionalResource1 = new FakeBookableResource($additionalResources[0], 'r2');
        $additionalResource2 = new FakeBookableResource($additionalResources[1], 'r3');

        $accessories = [];
        foreach ($pageAccessories as $pa) {
            $accessories[] = new ReservationAccessory($pa->Id, $pa->Quantity, $pa->Name);
        }

        $expectedAttributes = [];
        foreach ($pageAttributes as $attr) {
            $expectedAttributes[] = new AttributeValue($attr->Id, $attr->Value);
        }

        $startReminder = new ReservationReminder($this->page->GetStartReminderValue(), $this->page->GetStartReminderInterval());
        $endReminder = new ReservationReminder($this->page->GetEndReminderValue(), $this->page->GetEndReminderInterval());

        $this->resourceRepository->expects($this->exactly(3))
                                 ->method('LoadById')
                                 ->willReturnMap(
                                 [
                                     [$resourceId, $resource],
                                     [$additionalResources[0], $additionalResource1],
                                     [$additionalResources[1], $additionalResource2]
                                 ]);

        $fakeScheduleLayout = new FakeScheduleLayout();
        $fakeScheduleLayout->_SlotCount = new SlotCount(1, 2);
        $this->scheduleRepository->_Layout = $fakeScheduleLayout;
        $expectedCredits = 168;

        $duration = DateRange::Create($startDate . ' ' . $startTime, $endDate . ' ' . $endTime, $timezone);

        $actualReservation = $this->presenter->BuildReservation();

        $this->assertEquals($userId, $actualReservation->UserId());
        $this->assertEquals($resourceId, $actualReservation->ResourceId());
        $this->assertEquals($title, $actualReservation->Title());
        $this->assertEquals($description, $actualReservation->Description());
        $this->assertEquals($duration, $actualReservation->CurrentInstance()->Duration());
        $this->assertEquals($repeatOptions, $actualReservation->RepeatOptions());
        $this->assertEquals($participants, $actualReservation->CurrentInstance()->AddedParticipants());
        $this->assertEquals($invitees, $actualReservation->CurrentInstance()->AddedInvitees());
        $this->assertEquals($accessories, $actualReservation->Accessories());
        $this->assertTrue(in_array($expectedAttributes[0], $actualReservation->AttributeValues()));
        $expectedAttachment = ReservationAttachment::Create(
            $attachment->OriginalName(),
            $attachment->MimeType(),
            $attachment->Size(),
            $attachment->Contents(),
            $attachment->Extension(),
            0
        );
        $this->assertEquals([$expectedAttachment], $actualReservation->AddedAttachments());
        $this->assertEquals($startReminder, $actualReservation->GetStartReminder());
        $this->assertEquals($endReminder, $actualReservation->GetEndReminder());
        $this->assertEquals($participatingGuests, $actualReservation->CurrentInstance()->AddedParticipatingGuests());
        $this->assertEquals($invitedGuests, $actualReservation->CurrentInstance()->AddedInvitedGuests());
        $this->assertEquals($expectedCredits, $actualReservation->GetCreditsRequired(), '3 credits per slot, 2 day reservation, recurs 28 times');
    }

    public function testHandlingReservationCreationDelegatesToHandler()
    {
        $series = new TestReservationSeries();
        $instance = new Reservation($series, NullDateRange::Instance());
        $series->WithCurrentInstance($instance);

        $this->handler->expects($this->once())
                      ->method('Handle')
                      ->with($this->equalTo($series), $this->isInstanceOf('FakeReservationSavePage'))
                      ->willReturn(true);

        $this->presenter->HandleReservation($series);

        $this->assertEquals($instance->ReferenceNumber(), $this->page->referenceNumber);
        $this->assertEquals($series->RequiresApproval(), $this->page->requiresApproval);
    }
}
