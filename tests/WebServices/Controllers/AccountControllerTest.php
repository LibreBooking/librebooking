<?php

require_once(ROOT_DIR . 'WebServices/Controllers/AccountController.php');

class AccountControllerTest extends TestBase
{
    /**
     * @var AccountController
     */
    private $controller;
    /**
     * @var FakeRegistration
     */
    private $registration;
    /**
     * @var FakeAccountRequestValidator
     */
    private $requestValidator;
    /**
     * @var FakeWebServiceUserSession
     */
    private $session;
    /**
     * @var FakeUserRepository
     */
    private $userRepository;
    /**
     * @var FakePasswordEncryption
     */
    private $passwordEncryption;
    /**
     * @var FakeAttributeService
     */
    private $attributeService;

    public function setUp(): void
    {
        parent::setup();

        $this->session = new FakeWebServiceUserSession(99);
        $this->registration = new FakeRegistration();
        $this->requestValidator = new FakeAccountRequestValidator();
        $this->userRepository = new FakeUserRepository();
        $this->passwordEncryption = new FakePasswordEncryption();
        $this->attributeService = new FakeAttributeService();

        $this->controller = new AccountController(
            $this->registration,
            $this->userRepository,
            $this->requestValidator,
            $this->passwordEncryption,
            $this->attributeService
        );
    }

    public function testRegistersAccount()
    {
        $request = CreateAccountRequest::Example();
        $this->requestValidator->_Errors = [];
        $this->registration->_RegisteredUser = new FakeUser(100);

        $result = $this->controller->Create($request);

        $attributes = [];
        foreach ($request->GetCustomAttributes() as $a) {
            $attributes[] = new AttributeValue($a->attributeId, $a->attributeValue);
        }

        $this->assertTrue($result->WasSuccessful());
        $this->assertEquals(100, $result->UserId());
        $this->assertTrue($this->registration->_RegisterCalled);
        $this->assertEquals($request->userName, $this->registration->_Login);
        $this->assertEquals($request->emailAddress, $this->registration->_Email);
        $this->assertEquals($request->firstName, $this->registration->_First);
        $this->assertEquals($request->lastName, $this->registration->_Last);
        $this->assertEquals($request->password, $this->registration->_Password);
        $this->assertEquals($request->timezone, $this->registration->_Timezone);
        $this->assertEquals($request->language, $this->registration->_Language);
        $this->assertEquals(null, $this->registration->_Groups);
        $this->assertEquals($request->acceptTermsOfService, $this->registration->_TermsAccepted);
        $this->assertEquals($attributes, $this->registration->_AttributeValues);
        $this->assertEquals($request->GetAdditionalFields(), $this->registration->_AdditionalFields);
    }

    public function testWhenRegisterValidationFails()
    {
        $request = CreateAccountRequest::Example();
        $this->requestValidator->_Errors = ['error1', 'error2'];

        $result = $this->controller->Create($request);

        $this->assertFalse($result->WasSuccessful());
        $this->assertEquals(null, $result->UserId());
        $this->assertEquals($this->requestValidator->_Errors, $result->Errors());
    }

    public function testUpdatesAccount()
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_TIMEZONE, 'America/New_York');
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, 'en_us');
        $request = UpdateAccountRequest::Example();
        $this->requestValidator->_Errors = [];

        $this->userRepository->_User = new FakeUser($this->session->UserId);

        $result = $this->controller->Update($request, $this->session);

        $user = $this->userRepository->_UpdatedUser;

        $attributes = [];
        foreach ($request->GetCustomAttributes() as $a) {
            $attributes[] = new AttributeValue($a->attributeId, $a->attributeValue);
        }

        $this->assertTrue($result->WasSuccessful());
        $this->assertEquals($this->session->UserId, $result->UserId());
        $this->assertEquals($request->userName, $user->Username());
        $this->assertEquals($request->emailAddress, $user->EmailAddress());
        $this->assertEquals($request->firstName, $user->FirstName());
        $this->assertEquals($request->lastName, $user->LastName());
        $this->assertEquals($request->GetTimezone(), $user->Timezone());
        $this->assertEquals($request->GetLanguage(), $user->Language());
        $this->assertEquals($attributes, $user->GetAddedAttributes());
        $this->assertEquals($request->phone, $user->GetAttribute(UserAttribute::Phone));
        $this->assertEquals($request->organization, $user->GetAttribute(UserAttribute::Organization));
        $this->assertEquals($request->position, $user->GetAttribute(UserAttribute::Position));
    }

    public function testWhenUpdateValidationFails()
    {
        $request = UpdateAccountRequest::Example();
        $this->requestValidator->_Errors = ['error1', 'error2'];

        $result = $this->controller->Update($request, $this->session);

        $this->assertFalse($result->WasSuccessful());
        $this->assertEquals(null, $result->UserId());
        $this->assertEquals($this->requestValidator->_Errors, $result->Errors());
    }

    public function testUpdatesPassword()
    {
        $request = UpdateAccountPasswordRequest::Example();
        $this->requestValidator->_Errors = [];

        $this->userRepository->_User = new FakeUser($this->session->UserId);

        $result = $this->controller->UpdatePassword($request, $this->session);

        $user = $this->userRepository->_UpdatedUser;

        $this->assertTrue($result->WasSuccessful());
        $this->assertEquals($this->session->UserId, $result->UserId());
        $this->assertEquals($request->newPassword, $this->passwordEncryption->_LastPassword);
        $this->assertEquals($this->passwordEncryption->_Encrypted, $user->_Password);
        $this->assertEquals($this->passwordEncryption->_Salt, $user->_Salt);
    }

    public function testWhenPasswordValidationFails()
    {
        $request = UpdateAccountPasswordRequest::Example();
        $this->requestValidator->_Errors = ['error1', 'error2'];

        $result = $this->controller->UpdatePassword($request, $this->session);

        $this->assertFalse($result->WasSuccessful());
        $this->assertEquals(null, $result->UserId());
        $this->assertEquals($this->requestValidator->_Errors, $result->Errors());
    }
}

class FakeAccountRequestValidator implements IAccountRequestValidator
{
    /**
     * @var string[]
     */
    public $_Errors;

    public function ValidateCreate($request)
    {
        return $this->_Errors;
    }

    public function ValidateUpdate($request, WebServiceUserSession $session)
    {
        return $this->_Errors;
    }

    public function ValidatePasswordUpdate($request, WebServiceUserSession $session)
    {
        return $this->_Errors;
    }
}
