<?php

require_once(ROOT_DIR . 'WebServices/ReservationWriteWebService.php');

class ReservationWriteWebServiceTest extends TestBase
{
    /**
     * @var ReservationWriteWebService
     */
    private $service;

    /**
     * @var FakeRestServer
     */
    private $server;

    /**
     * @var PHPUnit\Framework\MockObject\MockObject|IReservationSaveController
     */
    private $controller;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new FakeRestServer();
        $this->controller = $this->createMock('IReservationSaveController');

        $this->service = new ReservationWriteWebService($this->server, $this->controller);
    }

    public function testCreatesNewReservation()
    {
        $pendingApproval = true;
        $reservationRequest = $this->GetReservationRequest();
        $this->server->SetRequest($reservationRequest);

        $referenceNumber = '12323';
        $controllerResult = new ReservationControllerResult($referenceNumber, [], $pendingApproval);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($reservationRequest), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $expectedResponse = new ReservationCreatedResponse($this->server, $referenceNumber, $pendingApproval);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::CREATED_CODE, $this->server->_LastResponseCode);
    }

    public function testUpdatesExistingReservation()
    {
        $pendingApproval = true;
        $reservationRequest = $this->GetReservationRequest();
        $this->server->SetRequest($reservationRequest);
        $referenceNumber = '12323';
        $updateScope = SeriesUpdateScope::FullSeries;
        $this->server->SetQueryString(WebServiceQueryStringKeys::UPDATE_SCOPE, $updateScope);

        $controllerResult = new ReservationControllerResult($referenceNumber, [], $pendingApproval);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with(
                    $this->equalTo($reservationRequest),
                    $this->equalTo($this->server->GetSession()),
                    $this->equalTo($referenceNumber),
                    $this->equalTo($updateScope)
                )
                ->willReturn($controllerResult);

        $this->service->Update($referenceNumber);

        $expectedResponse = new ReservationUpdatedResponse($this->server, $referenceNumber, $pendingApproval);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::OK_CODE, $this->server->_LastResponseCode);
    }

    public function testApprovesExistingReservation()
    {
        $reservationRequest = $this->GetReservationRequest();
        $this->server->SetRequest($reservationRequest);
        $referenceNumber = '12323';

        $controllerResult = new ReservationControllerResult($referenceNumber);

        $this->controller->expects($this->once())
                ->method('Approve')
                ->with(
                    $this->equalTo($this->server->GetSession()),
                    $this->equalTo($referenceNumber)
                )
                ->willReturn($controllerResult);

        $this->service->Approve($referenceNumber);

        $expectedResponse = new ReservationApprovedResponse($this->server, $referenceNumber);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::OK_CODE, $this->server->_LastResponseCode);
    }

    public function testDeletesExistingReservation()
    {
        $referenceNumber = '12323';
        $updateScope = SeriesUpdateScope::FullSeries;
        $this->server->SetQueryString(WebServiceQueryStringKeys::UPDATE_SCOPE, $updateScope);

        $controllerResult = new ReservationControllerResult($referenceNumber);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with(
                    $this->equalTo($this->server->GetSession()),
                    $this->equalTo($referenceNumber),
                    $this->equalTo($updateScope)
                )
                ->willReturn($controllerResult);

        $this->service->Delete($referenceNumber);

        $expectedResponse = new DeletedResponse();
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::OK_CODE, $this->server->_LastResponseCode);
    }

    public function testWhenCreationValidationFails()
    {
        $reservationRequest = new ReservationRequest();
        $this->server->SetRequest($reservationRequest);

        $errors = ['error'];
        $controllerResult = new ReservationControllerResult($reservationRequest);
        $controllerResult->SetErrors($errors);

        $this->controller->expects($this->once())
                ->method('Create')
                ->with($this->equalTo($reservationRequest), $this->equalTo($this->server->GetSession()))
                ->willReturn($controllerResult);

        $this->service->Create();

        $expectedResponse = new FailedResponse($errors);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testWhenUpdateValidationFails()
    {
        $referenceNumber = '123';
        $reservationRequest = new ReservationRequest();
        $this->server->SetRequest($reservationRequest);

        $errors = ['error'];
        $controllerResult = new ReservationControllerResult($referenceNumber);
        $controllerResult->SetErrors($errors);

        $this->controller->expects($this->once())
                ->method('Update')
                ->with($this->anything(), $this->anything(), $this->anything(), $this->anything())
                ->willReturn($controllerResult);

        $this->service->Update($referenceNumber);

        $expectedResponse = new FailedResponse($errors);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testWhenApproveValidationFails()
    {
        $referenceNumber = '123';

        $errors = ['error'];
        $controllerResult = new ReservationControllerResult($referenceNumber);
        $controllerResult->SetErrors($errors);

        $this->controller->expects($this->once())
                ->method('Approve')
                ->with(
                    $this->equalTo($this->server->GetSession()),
                    $this->equalTo($referenceNumber)
                )
                ->willReturn($controllerResult);

        $this->service->Approve($referenceNumber);

        $expectedResponse = new FailedResponse($errors);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testWhenDeleteValidationFails()
    {
        $referenceNumber = '123';

        $errors = ['error'];
        $controllerResult = new ReservationControllerResult($referenceNumber, $errors);

        $this->controller->expects($this->once())
                ->method('Delete')
                ->with($this->anything(), $this->anything(), $this->anything())
                ->willReturn($controllerResult);

        $this->service->Delete($referenceNumber);

        $expectedResponse = new FailedResponse($errors);
        $this->assertEquals($expectedResponse, $this->server->_LastResponse);
        $this->assertEquals(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    private function GetReservationRequest()
    {
        $request = new ReservationRequest();
        $endDate = Date::Parse('2012-11-20 05:30', 'UTC');
        $startDate = Date::Parse('2012-11-18 02:30', 'UTC');
        $repeatTerminationDate = Date::Parse('2012-12-13', 'UTC');

        $accessoryId = 8912;
        $quantity = 1232;
        $attributeId = 3393;
        $attributeValue = '23232';
        $description = 'reservation description';
        $invitees = [9, 8];
        $participants = [99, 88];
        $repeatInterval = 1;
        $repeatMonthlyType = null;
        $repeatType = RepeatType::Weekly;
        $repeatWeekdays = [0, 4, 5];
        $resourceId = 122;
        $resources = [22, 23, 33];
        $title = 'reservation title';
        $userId = 1;

        $request->accessories = [new ReservationAccessoryRequest($accessoryId, $quantity)];
        $request->customAttributes = [new AttributeValueRequest($attributeId, $attributeValue)];
        $request->description = $description;
        $request->endDateTime = $endDate->ToIso();
        $request->invitees = $invitees;
        $request->participants = $participants;
        $recurrence = new RecurrenceRequestResponse($repeatType, $repeatInterval, $repeatMonthlyType, $repeatWeekdays, $repeatTerminationDate->ToIso());
        $request->recurrenceRule = $recurrence;
        $request->resourceId = $resourceId;
        $request->resources = $resources;
        $request->startDateTime = $startDate->ToIso();
        $request->title = $title;
        $request->userId = $userId;

        return $request;
    }
}
