<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Controllers/ReservationSaveController.php');

class ReservationSaveControllerTest extends TestBase
{
    /**
     * @var ReservationSaveController
     */
    private $controller;

    private IReservationPresenterFactory&\PHPUnit\Framework\MockObject\MockObject $presenterFactory;

    public function setUp(): void
    {
        parent::setup();

        $this->presenterFactory = $this->createMock('IReservationPresenterFactory');
        $this->controller = new ReservationSaveController($this->presenterFactory);
    }

    public function testUsesPresenterToCreateReservation()
    {
        $series = new TestReservationSeries();
        $presenter = $this->createMock('IReservationSavePresenter');

        $request = new ReservationRequest();
        $request->resourceId = 1;
        $request->startDateTime = '2012-04-05 01:01:01';
        $request->endDateTime = '2012-04-05 01:01:01';
        $session = new FakeWebServiceUserSession(123);
        $facade = new ReservationRequestResponseFacade($request, $session);

        $this->presenterFactory->expects($this->once())
                               ->method('Create')
                               ->with($this->equalTo($facade), $this->equalTo($session))
                               ->willReturn($presenter);

        $presenter->expects($this->once())
                  ->method('BuildReservation')
                  ->willReturn($series);

        $presenter->expects($this->once())
                  ->method('HandleReservation')
                  ->with($this->equalTo($series));

        $result = $this->controller->Create($request, $session);

        $expectedResult = new ReservationControllerResult($facade->ReferenceNumber(), $facade->Errors(), $facade->RequiresApproval());
        $this->assertEquals($expectedResult, $result);
    }

    public function testUpdatesExistingReservation()
    {
        $series = new TestReservationSeries();
        $presenter = $this->createMock('IReservationSavePresenter');
        $referenceNumber = '123';
        $updateScope = SeriesUpdateScope::FullSeries;

        $request = new ReservationRequest();
        $request->resourceId = 1;
        $request->startDateTime = '2012-04-05 01:01:01';
        $request->endDateTime = '2012-04-05 01:01:01';
        $session = new FakeWebServiceUserSession(123);
        $facade = new ReservationUpdateRequestResponseFacade($request, $session, $referenceNumber, $updateScope);

        $this->presenterFactory->expects($this->once())
                               ->method('Update')
                               ->with($this->equalTo($facade), $this->equalTo($session))
                               ->willReturn($presenter);
        $presenter->expects($this->once())
                  ->method('BuildReservation')
                  ->willReturn($series);

        $presenter->expects($this->once())
                  ->method('HandleReservation')
                  ->with($this->equalTo($series));


        $result = $this->controller->Update($request, $session, $referenceNumber, $updateScope);

        $expectedResult = new ReservationControllerResult($facade->ReferenceNumber(), $facade->Errors(), $facade->RequiresApproval());
        $this->assertEquals($expectedResult, $result);
    }

    public function testApprovesExistingReservation()
    {
        $presenter = $this->createMock('IReservationApprovalPresenter');
        $referenceNumber = '123';
        $session = new FakeWebServiceUserSession(123);

        $facade = new ReservationApprovalRequestResponseFacade($referenceNumber);

        $this->presenterFactory->expects($this->once())
                               ->method('Approve')
                               ->with($this->equalTo($facade), $this->equalTo($session))
                               ->willReturn($presenter);

        $presenter->expects($this->once())
                  ->method('PageLoad');

        $result = $this->controller->Approve($session, $referenceNumber);

        $expectedResult = new ReservationControllerResult($facade->GetReferenceNumber(), $facade->Errors());
        $this->assertEquals($expectedResult, $result);
    }

    public function testDeletesReservation()
    {
        $referenceNumber = '123';
        $updateScope = SeriesUpdateScope::FullSeries;
        $session = new FakeWebServiceUserSession(123);

        $facade = new ReservationDeleteRequestResponseFacade($referenceNumber, $updateScope);

        $presenter = $this->createMock('IReservationDeletePresenter');

        $reservation = new TestReservation();

        $this->presenterFactory->expects($this->once())
                               ->method('Delete')
                               ->with($this->equalTo($facade), $this->equalTo($session))
                               ->willReturn($presenter);

        $presenter->expects($this->once())
                  ->method('BuildReservation')
                  ->willReturn($reservation);

        $presenter->expects($this->once())
                  ->method('HandleReservation')
                  ->with($this->equalTo($reservation));

        $result = $this->controller->Delete($session, $referenceNumber, $updateScope);

        $expectedResult = new ReservationControllerResult($facade->ReferenceNumber(), $facade->Errors());
        $this->assertEquals($expectedResult, $result);
    }

    public function testFacadeProvidesDataFromRequestAndCollectsResponses()
    {
        $session = new FakeWebServiceUserSession(123);
        $timezone = 'America/Chicago';
        $session->Timezone = $timezone;

        $request = new ReservationRequest();

        $endDate = Date::Parse('2012-11-20 05:30', 'UTC');
        $endDateUserTz = $endDate->ToTimezone($timezone);
        $startDate = Date::Parse('2012-11-18 02:30', 'UTC');
        $startDateUserTz = $startDate->ToTimezone($timezone);
        $repeatTerminationDate = Date::Parse('2012-12-13', 'UTC');
        $repeatTerminationUserTz = $repeatTerminationDate->ToTimezone($timezone);

        $accessoryId = 8912;
        $quantity = 1232;
        $attributeId = 3393;
        $attributeValue = '23232';
        $description = 'reservation description';
        $invitees = [9, 8];
        $participants = [99, 88];
        $guests = ['u1@guest.com', 'u2@guest.com'];
        $repeatInterval = 1;
        $repeatMonthlyType = null;
        $repeatType = RepeatType::Weekly;
        $repeatWeekdays = [0, 4, 5];
        $resourceId = 122;
        $resources = [22, 23, 33];
        $title = 'reservation title';
        $userId = 1;
        $startReminderValue = 15;
        $startReminderInterval = ReservationReminderInterval::Minutes;
        $endReminderValue = 2;
        $endReminderInterval = ReservationReminderInterval::Hours;

        $request->accessories = [new ReservationAccessoryRequest($accessoryId, $quantity)];
        $request->customAttributes = [new AttributeValueRequest($attributeId, $attributeValue)];
        $request->description = $description;
        $request->endDateTime = $endDate->ToIso();
        $request->invitees = $invitees;
        $request->participants = $participants;
        $request->invitedGuests = $guests;
        $recurrence = new RecurrenceRequestResponse($repeatType, $repeatInterval, $repeatMonthlyType, $repeatWeekdays, $repeatTerminationDate->ToIso());
        $request->recurrenceRule = $recurrence;
        $request->resourceId = $resourceId;
        $request->resources = $resources;
        $request->startDateTime = $startDate->ToIso();
        $request->title = $title;
        $request->userId = $userId;
        $request->startReminder = new ReminderRequestResponse($startReminderValue, $startReminderInterval);
        $request->endReminder = new ReminderRequestResponse($endReminderValue, $endReminderInterval);

        $facade = new ReservationRequestResponseFacade($request, $session);

        $referenceNumber = uniqid();
        $errors = ['error', 'error2'];

        $facade->SetReferenceNumber($referenceNumber);
        $facade->SetErrors($errors);
        $facade->SetRequiresApproval(true);

        $accessories = [AccessoryFormElement::Create($accessoryId, $quantity)];
        $attributes = [new AttributeFormElement($attributeId, $attributeValue)];

        $this->assertEquals($accessories, $facade->GetAccessories());
        $this->assertEquals([], $facade->GetAttachments());
        $this->assertEquals($attributes, $facade->GetAttributes());
        $this->assertEquals($description, $facade->GetDescription());
        $this->assertEquals($endDateUserTz->Format('Y-m-d'), $facade->GetEndDate());
        $this->assertEquals($endDateUserTz->Format('H:i'), $facade->GetEndTime());
        $this->assertEquals($invitees, $facade->GetInvitees());
        $this->assertEquals($participants, $facade->GetParticipants());
        $this->assertEquals($guests, $facade->GetInvitedGuests());
        $this->assertEquals($repeatInterval, $facade->GetRepeatInterval());
        $this->assertEquals($repeatMonthlyType, $facade->GetRepeatMonthlyType());
        $this->assertEquals($repeatType, $facade->GetRepeatType());
        $this->assertEquals($repeatWeekdays, $facade->GetRepeatWeekdays());
        $this->assertEquals($repeatTerminationUserTz->Format('Y-m-d'), $facade->GetRepeatTerminationDate());
        $this->assertEquals($resourceId, $facade->GetResourceId());
        $this->assertEquals($resources, $facade->GetResources());
        $this->assertEquals($startDateUserTz->Format('Y-m-d'), $facade->GetStartDate());
        $this->assertEquals($startDateUserTz->Format('H:i'), $facade->GetStartTime());
        $this->assertEquals($title, $facade->GetTitle());
        $this->assertEquals($userId, $facade->GetUserId());
        $this->assertTrue($facade->HasStartReminder());
        $this->assertTrue($facade->HasEndReminder());
        $this->assertEquals($startReminderValue, $facade->GetStartReminderValue());
        $this->assertEquals($startReminderInterval, $facade->GetStartReminderInterval());
        $this->assertEquals($endReminderValue, $facade->GetEndReminderValue());
        $this->assertEquals($endReminderInterval, $facade->GetEndReminderInterval());

        $this->assertEquals($referenceNumber, $facade->ReferenceNumber());
        $this->assertEquals($errors, $facade->Errors());
        $this->assertEquals(true, $facade->RequiresApproval());
    }
}
