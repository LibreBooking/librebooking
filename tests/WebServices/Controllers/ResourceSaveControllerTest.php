<?php

require_once(ROOT_DIR . 'WebServices/Controllers/ResourceSaveController.php');

class ResourceSaveControllerTest extends TestBase
{
    /**
     * @var ResourceSaveController
     */
    private $controller;

    /**
     * @var WebServiceUserSession
     */
    private $session;

    private IResourceRepository&\PHPUnit\Framework\MockObject\MockObject $repository;

    private IResourceRequestValidator&\PHPUnit\Framework\MockObject\MockObject $validator;

    public function setUp(): void
    {
        $this->validator = $this->createMock('IResourceRequestValidator');
        $this->repository = $this->createMock('IResourceRepository');
        $this->session = new FakeWebServiceUserSession(1);
        $this->controller = new ResourceSaveController($this->repository, $this->validator);

        parent::setup();
    }

    public function testAddsNewResource()
    {
        $resourceId = 122;
        $request = ResourceRequest::Example();
        $expectedCreateResource = BookableResource::CreateNew(
            $request->name,
            $request->scheduleId,
            $request->autoAssignPermissions,
            $request->sortOrder
        );

        $expectedUpdateResource = new BookableResource(
            $resourceId,
            $request->name,
            $request->location,
            $request->contact,
            $request->notes,
            $request->minLength,
            $request->maxLength,
            $request->autoAssignPermissions,
            $request->requiresApproval,
            $request->allowMultiday,
            $request->maxParticipants,
            $request->minNoticeAdd,
            $request->maxNotice,
            $request->description,
            $request->scheduleId,
            minNoticeDelete: $request->minNoticeDelete,
            minNoticeUpdate: $request->minNoticeUpdate,
            bufferTime: $request->bufferTime,
            groupIds: $request->groupIds,
            resourceTypeId: $request->typeId
        );

        $expectedUpdateResource->SetSortOrder($request->sortOrder);
        $expectedUpdateResource->ChangeStatus($request->statusId, $request->statusReasonId);
        $attributes = [new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)];
        $expectedUpdateResource->ChangeAttributes($attributes);
        $expectedUpdateResource->SetCheckin($request->requiresCheckIn, $request->autoReleaseMinutes);
        $expectedUpdateResource->SetColor($request->color);
        $expectedUpdateResource->SetCreditsPerSlot($request->creditsPerSlot);
        $expectedUpdateResource->SetPeakCreditsPerSlot($request->peakCreditsPerSlot);

        $this->validator->expects($this->once())
                ->method('ValidateCreateRequest')
                ->with($this->equalTo($request))
                ->willReturn([]);

        $this->repository->expects($this->once())
                ->method('Add')
                ->with($this->equalTo($expectedCreateResource))
                ->willReturn($resourceId);

        $this->repository->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($expectedUpdateResource));

        $response = $this->controller->Create($request, $this->session);

        $this->assertTrue($response->WasSuccessful());
        $this->assertEquals($resourceId, $response->ResourceId());
        $this->assertEmpty($response->Errors());
    }

    public function testWhenAddValidationFails()
    {
        $errors = ['something failed'];

        $request = ResourceRequest::Example();
        $this->validator->expects($this->once())
                ->method('ValidateCreateRequest')
                ->with($this->anything())
                ->willReturn($errors);

        $response = $this->controller->Create($request, $this->session);

        $this->assertFalse($response->WasSuccessful());
        $this->assertNull($response->ResourceId());
        $this->assertEquals($errors, $response->Errors());
    }

    public function testUpdatesResource()
    {
        $resourceId = 122;
        $request = ResourceRequest::Example();
        $expectedUpdateResource = new BookableResource(
            $resourceId,
            $request->name,
            $request->location,
            $request->contact,
            $request->notes,
            $request->minLength,
            $request->maxLength,
            $request->autoAssignPermissions,
            $request->requiresApproval,
            $request->allowMultiday,
            $request->maxParticipants,
            $request->minNoticeAdd,
            $request->maxNotice,
            $request->description,
            $request->scheduleId,
            minNoticeDelete: $request->minNoticeDelete,
            minNoticeUpdate: $request->minNoticeUpdate,
            bufferTime: $request->bufferTime,
            groupIds: $request->groupIds,
            resourceTypeId: $request->typeId
        );

        $expectedUpdateResource->SetSortOrder($request->sortOrder);
        $expectedUpdateResource->ChangeStatus($request->statusId, $request->statusReasonId);
        $attributes = [new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)];
        $expectedUpdateResource->ChangeAttributes($attributes);
        $expectedUpdateResource->SetCheckin($request->requiresCheckIn, $request->autoReleaseMinutes);
        $expectedUpdateResource->SetColor($request->color);
        $expectedUpdateResource->SetCreditsPerSlot($request->creditsPerSlot);
        $expectedUpdateResource->SetPeakCreditsPerSlot($request->peakCreditsPerSlot);

        $this->validator->expects($this->once())
                ->method('ValidateUpdateRequest')
                ->with($this->equalTo($resourceId), $this->equalTo($request))
                ->willReturn([]);

        $this->repository->expects($this->once())
                ->method('Update')
                ->with($this->equalTo($expectedUpdateResource));

        $this->repository->expects($this->once())
                ->method('LoadById')
                ->willReturnMap(
                    [
                        [$resourceId, $expectedUpdateResource]
                    ]
                );

        $response = $this->controller->Update($resourceId, $request, $this->session);

        $this->assertTrue($response->WasSuccessful());
        $this->assertEquals($resourceId, $response->ResourceId());
        $this->assertEmpty($response->Errors());
    }

    public function testWhenUpdateValidationFails()
    {
        $resourceId = 123;
        $errors = ['something failed'];

        $request = ResourceRequest::Example();
        $this->validator->expects($this->once())
                ->method('ValidateUpdateRequest')
                ->with($this->anything(), $this->anything())
                ->willReturn($errors);

        $response = $this->controller->Update($resourceId, $request, $this->session);

        $this->assertFalse($response->WasSuccessful());
        $this->assertNull($response->ResourceId());
        $this->assertEquals($errors, $response->Errors());
    }

    public function testDeletesResource()
    {
        $resourceId = 998;
        $resource = new FakeBookableResource($resourceId);

        $this->validator->expects($this->once())
                ->method('ValidateDeleteRequest')
                ->with($this->equalTo($resourceId))
                ->willReturn([]);

        $this->repository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($resourceId))
                ->willReturn($resource);

        $this->repository->expects($this->once())
                ->method('Delete')
                ->with($this->equalTo($resource));

        $response = $this->controller->Delete($resourceId, $this->session);

        $this->assertTrue($response->WasSuccessful());
        $this->assertEquals($resourceId, $response->ResourceId());
        $this->assertEmpty($response->Errors());
    }

    public function testWhenDeleteFails()
    {
        $resourceId = 998;
        $errors = ['error'];

        $this->validator->expects($this->once())
                ->method('ValidateDeleteRequest')
                ->with($this->equalTo($resourceId))
                ->willReturn($errors);

        $response = $this->controller->Delete($resourceId, $this->session);

        $this->assertFalse($response->WasSuccessful());
        $this->assertEquals($errors, $response->Errors());
        $this->assertEmpty($response->ResourceId());
    }
}
