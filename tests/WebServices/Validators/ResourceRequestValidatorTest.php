<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Validators/ResourceRequestValidator.php');

class ResourceRequestValidatorTest extends TestBase
{
    /**
     * @var ResourceRequestValidator
     */
    private $validator;

    private IAttributeService&\PHPUnit\Framework\MockObject\MockObject $attributeService;
    private IGroupRepository&\PHPUnit\Framework\MockObject\MockObject $groupRepository;

    public function setUp(): void
    {
        $this->attributeService = $this->createMock('IAttributeService');
        $this->groupRepository = $this->createMock(IGroupRepository::class);
        $this->validator = new ResourceRequestValidator(
            attributeService: $this->attributeService,
            groupRepository: $this->groupRepository
        );
        parent::setup();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidAdminGroupIds')]
    public function testRejectsInvalidAdminGroupIdTypes(mixed $adminGroupId): void
    {
        $request = ResourceRequest::Example();
        $request->customAttributes = [];
        $request->adminGroupId = $adminGroupId;
        $this->groupRepository->expects($this->never())->method('LoadById');

        $expected = ['adminGroupId must be a positive integer or null'];
        $this->assertSame($expected, $this->validator->ValidateCreateRequest($request));
        $this->assertSame($expected, $this->validator->ValidateUpdateRequest(resourceId: 1, updateRequest: $request));
    }

    public static function invalidAdminGroupIds(): array
    {
        return [[0], [-1], ['3'], [3.0], [true], [false], [''], [[]], [new stdClass()]];
    }

    public function testRejectsMissingOrIneligibleAdminGroup(): void
    {
        $request = ResourceRequest::Example();
        $request->customAttributes = [];
        $request->adminGroupId = 42;
        $this->groupRepository->expects($this->exactly(3))
            ->method('LoadById')
            ->with($request->adminGroupId)
            ->willReturnOnConsecutiveCalls(null, Group::Null(), new Group(id: 42, name: 'Regular group'));

        $this->assertSame(
            ['adminGroupId must identify an existing group'],
            $this->validator->ValidateCreateRequest($request)
        );
        $this->assertSame(
            ['adminGroupId must identify an existing group'],
            $this->validator->ValidateUpdateRequest(resourceId: 1, updateRequest: $request)
        );
        $this->assertSame(
            ['adminGroupId must identify a group with the Resource Administrator role'],
            $this->validator->ValidateCreateRequest($request)
        );
    }

    public function testAcceptsResourceAdministratorGroup(): void
    {
        $request = ResourceRequest::Example();
        $request->customAttributes = [];
        $request->adminGroupId = 42;
        $group = new Group(id: 42, name: 'Resource administrators');
        $group->WithRole(RoleLevel::RESOURCE_ADMIN);
        $this->groupRepository->expects($this->exactly(2))
            ->method('LoadById')->with($request->adminGroupId)->willReturn($group);

        $this->assertSame([], $this->validator->ValidateCreateRequest($request));
        $this->assertSame([], $this->validator->ValidateUpdateRequest(resourceId: 1, updateRequest: $request));
    }

    public function testAcceptsNullOrOmittedAdminGroupWithoutLookup(): void
    {
        $request = ResourceRequest::Example();
        $request->adminGroupId = null;
        $request->customAttributes = [];
        $this->groupRepository->expects($this->never())->method('LoadById');

        $this->assertSame([], $this->validator->ValidateCreateRequest($request));
        $this->assertSame([], $this->validator->ValidateUpdateRequest(resourceId: 1, updateRequest: $request));
        unset($request->adminGroupId);
        $this->assertSame([], $this->validator->ValidateCreateRequest($request));
        $this->assertSame([], $this->validator->ValidateUpdateRequest(resourceId: 1, updateRequest: $request));
    }

    public function testBasicRequiredFields()
    {
        $request = ResourceRequest::Example();
        $request->adminGroupId = null;
        $request->customAttributes = [];
        /** @var mixed $missingName */
        $missingName = null;
        /** @var mixed $missingScheduleId */
        $missingScheduleId = '   ';
        $request->name = $missingName;
        $request->scheduleId = $missingScheduleId;

        $createErrors = $this->validator->ValidateCreateRequest($request);
        $updateErrors = $this->validator->ValidateUpdateRequest(1, $request);

        $this->assertEquals(2, count($createErrors));
        $this->assertEquals(2, count($updateErrors));
    }

    public function testTimesAreCheckedWhenProvided()
    {
        $request = ResourceRequest::Example();
        $request->adminGroupId = null;
        $request->customAttributes = [];
        $request->maxNotice = 'xyz';

        $createErrors = $this->validator->ValidateCreateRequest($request);
        $updateErrors = $this->validator->ValidateUpdateRequest(1, $request);

        $this->assertEquals(1, count($createErrors));
        $this->assertEquals(1, count($updateErrors));
    }

    public function testCustomAttributesAreValidated()
    {
        $request = ResourceRequest::Example();
        $request->adminGroupId = null;

        $result = new AttributeServiceValidationResult(false, ['error']);
        $this->attributeService->expects($this->atLeastOnce())
                ->method('Validate')
                ->with(
                    $this->equalTo(CustomAttributeCategory::RESOURCE),
                    $this->equalTo([new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)]),
                    $this->anything(),
                    $this->anything(),
                    $this->anything(),
                    $this->equalTo($request->typeId)
                )
                ->willReturn($result);

        $createErrors = $this->validator->ValidateCreateRequest($request);
        $updateErrors = $this->validator->ValidateUpdateRequest(1, $request);

        $this->assertEquals(1, count($createErrors));
        $this->assertEquals(1, count($updateErrors));
    }

    public function testUpdateAndDeleteRequireResourceId()
    {
        $request = ResourceRequest::Example();
        $request->adminGroupId = null;

        // Route IDs are digits only, so 0 is the only ID that can be empty.
        $deleteErrors = $this->validator->ValidateDeleteRequest(resourceId: 0);
        $updateErrors = $this->validator->ValidateUpdateRequest(resourceId: 0, updateRequest: $request);

        $this->assertEquals(1, count($deleteErrors));
        $this->assertEquals(1, count($updateErrors));
    }
}
