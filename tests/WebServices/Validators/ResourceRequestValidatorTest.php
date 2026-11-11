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

    public function setUp(): void
    {
        $this->attributeService = $this->createMock('IAttributeService');
        $this->validator = new ResourceRequestValidator($this->attributeService);
        parent::setup();
    }

    public function testBasicRequiredFields()
    {
        $request = ResourceRequest::Example();
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

        $result = new AttributeServiceValidationResult(false, ['error']);
        $this->attributeService->expects($this->atLeastOnce())
                ->method('Validate')
                ->with(
                    $this->equalTo(CustomAttributeCategory::RESOURCE),
                    $this->equalTo([new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)])
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

        // Route IDs are digits only, so 0 is the only ID that can be empty.
        $deleteErrors = $this->validator->ValidateDeleteRequest(resourceId: 0);
        $updateErrors = $this->validator->ValidateUpdateRequest(resourceId: 0, updateRequest: $request);

        $this->assertEquals(1, count($deleteErrors));
        $this->assertEquals(1, count($updateErrors));
    }
}
