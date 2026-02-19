<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Validators/UserRequestValidator.php');

class UserRequestValidatorTest extends TestBase
{
    private IAttributeService&\PHPUnit\Framework\MockObject\MockObject $attributeService;

    /**
     * @var UserRequestValidator
     */
    private $validator;

    private IUserViewRepository&\PHPUnit\Framework\MockObject\MockObject $userRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->attributeService = $this->createMock('IAttributeService');
        $this->userRepository = $this->createMock('IUserViewRepository');

        $this->validator = new UserRequestValidator($this->attributeService, $this->userRepository);
    }

    public function testWhenCreateRequestIsJunk()
    {
        $errors = $this->validator->ValidateCreateRequest(null);
        $this->assertEquals(1, count($errors));
    }

    public function testCreateRequiredFields()
    {
        $this->expectsAttributeValidator();
        $request = CreateUserRequest::Example();
        $request->firstName = null;
        $request->lastName = '';
        $request->userName = ' ';
        $request->timezone = ' ';
        $request->language = ' ';
        $request->password = ' ';

        $errors = $this->validator->ValidateCreateRequest($request);
        $this->assertTrue(count($errors) == 6);
    }

    public function testCreateValidatesEmailFormat()
    {
        $this->expectsAttributeValidator();
        $request = CreateUserRequest::Example();
        $request->emailAddress = 'aaaaaa.com';
        $errors = $this->validator->ValidateCreateRequest($request);
        $this->assertTrue(count($errors) == 1);
    }

    public function testCreateValidatesExistingEmailAndUsername()
    {
        $this->expectsAttributeValidator();
        $request = CreateUserRequest::Example();

        $this->userRepository->expects($this->exactly(2))
                ->method('UserExists')
                ->willReturnMap(
                    [
                        [$request->emailAddress, null, 1],
                        [null, $request->userName, 1]
                    ]
                );

        $errors = $this->validator->ValidateCreateRequest($request);
        $this->assertTrue(count($errors) == 2);
    }

    public function testCreateValidatesAttributes()
    {
        $request = CreateUserRequest::Example();
        $result = new AttributeServiceValidationResult(false, ['error']);
        $this->attributeService->expects($this->once())
                ->method('Validate')
                ->with(
                    $this->equalTo(CustomAttributeCategory::USER),
                    $this->equalTo([new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)])
                )
                ->willReturn($result);

        $errors = $this->validator->ValidateCreateRequest($request);
        $this->assertTrue(count($errors) == 1);
    }

    public function testWhenUpdateRequestIsJunk()
    {
        $errors = $this->validator->ValidateUpdateRequest(1, null);
        $this->assertEquals(1, count($errors));
    }

    public function testUpdateRequiredFields()
    {
        $this->expectsAttributeValidator();
        $request = UpdateUserRequest::Example();
        $request->firstName = null;
        $request->lastName = '';
        $request->userName = ' ';
        $request->timezone = ' ';

        $errors = $this->validator->ValidateUpdateRequest(1, $request);
        $this->assertTrue(count($errors) == 4);
    }

    public function testUpdateValidatesEmailFormat()
    {
        $this->expectsAttributeValidator();
        $request = UpdateUserRequest::Example();
        $request->emailAddress = 'aaaaaa.com';
        $errors = $this->validator->ValidateUpdateRequest(1, $request);
        $this->assertTrue(count($errors) == 1);
    }

    public function testUpdateValidatesExistingEmail()
    {
        $this->expectsAttributeValidator();
        $request = UpdateUserRequest::Example();

        $this->userRepository->expects($this->exactly(2))
                ->method('UserExists')
                ->willReturnMap(
                    [
                        [$request->emailAddress, null, 2],
                        [null, $request->userName, 1]
                    ]
                );

        $errors = $this->validator->ValidateUpdateRequest(1, $request);
        $this->assertTrue(count($errors) == 1);
    }

    public function testUpdateValidatesExistingUsername()
    {
        $this->expectsAttributeValidator();
        $request = UpdateUserRequest::Example();

        $this->userRepository->expects($this->exactly(2))
                ->method('UserExists')
                ->willReturnMap(
                    [
                        [$request->emailAddress, null, 1],
                        [null, $request->userName, 2]
                    ]
                );

        $errors = $this->validator->ValidateUpdateRequest(1, $request);
        $this->assertTrue(count($errors) == 1);
    }

    public function testUpdateValidatesAttributes()
    {
        $request = UpdateUserRequest::Example();
        $result = new AttributeServiceValidationResult(false, ['error']);
        $this->attributeService->expects($this->once())
                ->method('Validate')
                ->with(
                    $this->equalTo(CustomAttributeCategory::USER),
                    $this->equalTo([new AttributeValue($request->customAttributes[0]->attributeId, $request->customAttributes[0]->attributeValue)])
                )
                ->willReturn($result);

        $errors = $this->validator->ValidateUpdateRequest(1, $request);
        $this->assertTrue(count($errors) == 1);
    }

    private function expectsAttributeValidator()
    {
        $this->attributeService->expects($this->any())
                ->method('Validate')
                ->with($this->anything(), $this->anything())
                ->willReturn(new AttributeServiceValidationResult(true, null));
    }
}
