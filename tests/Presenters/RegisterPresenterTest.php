<?php

require_once(ROOT_DIR . 'Presenters/RegistrationPresenter.php');
require_once(ROOT_DIR . 'Pages/RegistrationPage.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class RegisterPresenterTest extends TestBase
{
    /**
     * @var FakeRegistrationPage
     */
    private $page;

    /**
     * @var RegistrationPresenter
     */
    private $presenter;

    /**
     * @var FakeRegistration
     */
    private $fakeReg;

    /**
     * @var FakeAuth
     */
    private $fakeAuth;

    /**
     * @var ICaptchaService
     */
    private $captcha;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var ITermsOfServiceRepository
     */
    private $termsOfServiceRepository;

    private $login = 'testlogin';
    private $email = 'test@test.com';
    private $fname = 'First';
    private $lname = 'Last';
    private $phone = '123.123.1234';
    private $password = 'password';
    private $confirm = 'password';
    private $timezone = 'America/New_York';
    private $homepageId = '1';
    private $acknowledgedTerms = true;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeRegistrationPage();
        $this->fakeReg = new FakeRegistration();
        $this->fakeAuth = new FakeAuth();
        $this->captcha = $this->createMock('ICaptchaService');
        $this->attributeService = $this->createMock('IAttributeService');
        $this->termsOfServiceRepository = new FakeTermsOfServiceRepository();

        $this->presenter = new RegistrationPresenter(
            $this->page,
            $this->fakeReg,
            $this->fakeAuth,
            $this->captcha,
            $this->attributeService,
            $this->termsOfServiceRepository
        );
    }

    public function teardown(): void
    {
        parent::teardown();

        $this->page = null;
        $this->fakeReg = null;
        $this->fakeAuth = null;
    }

    public function testSetsSelectedTimezoneToServerDefault()
    {
        $expectedTimezone = "America/Chicago";

        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_TIMEZONE, $expectedTimezone);
        $this->page->_IsPostBack = false;

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($this->page->_Timezone, $expectedTimezone);
    }

    public function testSetsSelectedTimezoneToServerSubmitted()
    {
        $expectedTimezone = "America/New_York";
        $this->page->SetTimezone($expectedTimezone);
        $this->page->_IsPostBack = true;

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($this->page->_Timezone, $expectedTimezone);
    }

    public function testLoadsAllTimezones()
    {
        $numberOfTimezones = count($GLOBALS[GlobalKeys::TIMEZONES]);

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($numberOfTimezones, count($this->page->_TimezoneValues));
        $this->assertEquals($numberOfTimezones, count($this->page->_TimezoneOutput));
    }

    public function testLoadsAllHomepages()
    {
        $pages = Pages::GetAvailablePages();
        $numberOfPages = count($pages);

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($numberOfPages, count($this->page->_HomepageValues));
        $this->assertEquals(1, $this->page->_HomepageValues[0]);
        $this->assertEquals($numberOfPages, count($this->page->_HomepageOutput));
        $this->assertEquals($pages[1]['name'], $this->page->_HomepageOutput[0]);
    }

    public function testSetsSelectedHomepageToDefault()
    {
        $expectedHomepage = 1;
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_HOMEPAGE, $expectedHomepage);

        $this->page->_IsPostBack = false;

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($expectedHomepage, $this->page->_Homepage);
    }

    public function testSetsSelectedHomepageToServerSubmitted()
    {
        $expectedHomepage = 2;
        $this->page->SetHomepage($expectedHomepage);
        $this->page->_IsPostBack = true;

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($expectedHomepage, $this->page->_Homepage);
    }

    public function testSetsCaptchaUrl()
    {
        $url = "http://blah/blah/blah";

        $this->captcha->expects($this->once())
            ->method('GetImageUrl')
            ->willReturn($url);

        $this->ExpectAttributeServiceCalled();

        $this->presenter->PageLoad();

        $this->assertEquals($url, $this->page->_CaptchaUrl);
    }

    public function testPresenterRegistersIfAllFieldsAreValid()
    {
        $this->LoadPageValues();

        $additionalFields = [
            'phone' => $this->phone,
            'instituntion' => '',
            'position' => ''
        ];

        $this->page->_Action = RegisterActions::Register;

        $this->presenter->ProcessAction();

        $expectedAttributeValues = [new AttributeValue(1, 2)];
        $this->assertTrue($this->fakeReg->_RegisterCalled);
        $this->assertEquals($this->login, $this->fakeReg->_Login);
        $this->assertEquals($this->email, $this->fakeReg->_Email);
        $this->assertEquals($this->fname, $this->fakeReg->_First);
        $this->assertEquals($this->lname, $this->fakeReg->_Last);
        $this->assertEquals($this->password, $this->fakeReg->_Password);
        $this->assertEquals($this->timezone, $this->fakeReg->_Timezone);
        $this->assertEquals(intval($this->homepageId), $this->fakeReg->_HomepageId);
        $this->assertEquals($expectedAttributeValues, $this->fakeReg->_AttributeValues);
        $this->assertEquals(true, $this->fakeReg->_TermsAccepted);

        $this->assertEquals($additionalFields['phone'], $this->fakeReg->_AdditionalFields['phone']);

        $v = $this->page->_Validators;

        $this->assertEquals(12, count($v));
        $this->assertEquals($v['fname'], new RequiredValidator($this->fname));
        $this->assertEquals($v['lname'], new RequiredValidator($this->lname));
        $this->assertEquals($v['username'], new RequiredValidator($this->login));
        $this->assertEquals($v['passwordmatch'], new EqualValidator($this->password, $this->confirm));
        $this->assertEquals($v['passwordcomplexity'], new PasswordComplexityValidator($this->password));
        $this->assertEquals($v['emailformat'], new EmailValidator($this->email));
        $this->assertEquals($v['uniqueemail'], new UniqueEmailValidator(new UserRepository(), $this->email));
        $this->assertEquals($v['uniqueusername'], new UniqueUserNameValidator(new UserRepository(), $this->login));
        $this->assertEquals($v['additionalattributes'], new AttributeValidator($this->attributeService, CustomAttributeCategory::USER, $expectedAttributeValues));
        $this->assertEquals($v['requiredEmailDomain'], new RequiredEmailDomainValidator($this->email));
        $this->assertEquals($v['termsOfService'], new TermsOfServiceValidator($this->termsOfServiceRepository, $this->acknowledgedTerms));
    }

    public function testDoesNotRegisterIfPageIsNotValid()
    {
        $this->page->_IsValid = false;
        $this->page->_Action = RegisterActions::Register;

        $this->presenter->ProcessAction();

        $this->assertFalse($this->fakeReg->_RegisterCalled);
        $this->assertFalse($this->fakeAuth->_LoginCalled);
    }

    public function testAuthorizesUserAfterRegister()
    {
        $this->LoadPageValues();
        $this->page->_IsValid = true;
        $this->page->_Email = $this->email;
        $this->page->_Homepage = 2;

        $this->presenter->Register();

        $this->assertTrue($this->fakeReg->_RegisterCalled);

        $this->assertTrue($this->fakePluginManager->_LoadedRegistration);
        $this->assertEquals($this->fakeReg->_RegisteredUser, $this->fakePluginManager->_RegistrationUser);
        $this->assertEquals($this->page, $this->fakePluginManager->_RegistrationPage);
    }

    public function testRedirectsToLoginIfAllowSelfRegistrationIsOff()
    {
        $this->fakeConfig->SetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, 'false');

        $this->presenter = new RegistrationPresenter($this->page, $this->fakeReg, $this->fakeAuth);
        $this->presenter->PageLoad();

        $this->assertEquals(Pages::LOGIN, $this->page->_RedirectDestination);
    }

    public function testLoadsCustomAttributes()
    {
        $attributes = [new FakeCustomAttribute(1), new FakeCustomAttribute(2)];
        $expectedAttributes = [new LBAttribute($attributes[0]), new LBAttribute($attributes[1])];

        $this->ExpectAttributeServiceCalled($expectedAttributes);

        $this->presenter->PageLoad();

        $this->assertEquals($expectedAttributes, $this->page->_Attributes);
    }

    private function ExpectAttributeServiceCalled($attributes = [])
    {
        $list = new FakeAttributeList($attributes);
        $this->attributeService->expects($this->once())
            ->method('GetAttributes')
            ->with($this->equalTo(CustomAttributeCategory::USER))
            ->willReturn($list);
    }

    private function LoadPageValues()
    {
        $this->page->SetLoginName($this->login);
        $this->page->SetEmail($this->email);
        $this->page->SetFirstName($this->fname);
        $this->page->SetLastName($this->lname);
        $this->page->SetPhone($this->phone);
        $this->page->SetPassword($this->password);
        $this->page->SetPasswordConfirm($this->confirm);
        $this->page->SetTimezone($this->timezone);
        $this->page->SetHomepage($this->homepageId);
        $this->page->_Acknowledged = $this->acknowledgedTerms;
    }
}
