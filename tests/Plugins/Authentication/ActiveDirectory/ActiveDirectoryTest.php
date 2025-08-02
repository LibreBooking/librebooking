<?php

require_once(ROOT_DIR . 'plugins/Authentication/ActiveDirectory/namespace.php');

class ActiveDirectoryTest extends TestBase
{
    /**
     * @var FakeAuth
     */
    private $fakeAuth;

    /**
     * @var FakeActiveDirectoryOptions
     */
    private $fakeLdapOptions;

    /**
     * @var FakeActiveDirectoryWrapper
     */
    private $fakeLdap;

    /**
     * @var ActiveDirectoryUser
     */
    private $ldapUser;

    /**
     * @var FakeRegistration
     */
    private $fakeRegistration;

    /**
     * @var FakeGroupViewRepository
     */
    private $fakeGroupRepository;

    /**
     * @var FakePasswordEncryption
     */
    private $encryption;

    private $username = 'username';
    private $password = 'password';

    /**
     * @var ILoginContext
     */
    private $loginContext;

    public function setUp(): void
    {
        parent::setup();

        $this->fakeAuth = new FakeAuth();
        $this->fakeLdapOptions = new FakeActiveDirectoryOptions();
        $this->fakeLdap = new FakeActiveDirectoryWrapper();
        $this->fakeRegistration = new FakeRegistration();
        $this->encryption = new FakePasswordEncryption();
        $this->fakeGroupRepository = new FakeGroupViewRepository();

        $ldapEntry = new TestAdLdapEntry();
        $ldapEntry->sn = 'user';
        $ldapEntry->givenname = 'test';
        $ldapEntry->mail = 'ldap@user.com';
        $ldapEntry->telephonenumber = '000-000-0000';
        $ldapEntry->physicaldeliveryofficename = '';
        $ldapEntry->title = '';

        $mapping = [
            'sn' => 'sn',
            'givenname' => 'givenname',
            'mail' => 'mail',
            'telephonenumber' => 'telephonenumber',
            'physicaldeliveryofficename' => 'physicaldeliveryofficename',
            'title' => 'title'
        ];

        $this->ldapUser = new ActiveDirectoryUser($ldapEntry, $mapping, 'group1,group2,group3');

        $this->fakeLdap->_ExpectedLdapUser = $this->ldapUser;

        $this->loginContext = $this->createMock('ILoginContext');
    }

    public function testCanValidateUser()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = false;
        $expectedResult = true;
        $this->fakeLdap->_ExpectedAuthenticate = $expectedResult;

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertEquals($expectedResult, $isValid);
        $this->assertTrue($this->fakeLdap->_ConnectCalled);
        $this->assertTrue($this->fakeLdap->_AuthenticateCalled);
        $this->assertEquals($this->username, $this->fakeLdap->_LastUsername);
        $this->assertEquals($this->password, $this->fakeLdap->_LastPassword);
    }

    public function testNotValidIfCannotFindUser()
    {
        $this->fakeLdap->_ExpectedLdapUser = null;
        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertFalse($isValid);
        $this->assertTrue($this->fakeLdap->_ConnectCalled);
        $this->assertTrue($this->fakeLdap->_AuthenticateCalled);
    }

    public function testDoesNotTryToLoadUserDetailsIfNotValid()
    {
        $this->fakeLdap->_ExpectedAuthenticate = false;
        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertFalse($this->fakeLdap->_GetLdapUserCalled);
        $this->assertFalse($isValid);
    }

    public function testNotValidIfValidateFailsAndNotFailingOverToDb()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = false;
        $expectedResult = false;
        $this->fakeLdap->_ExpectedAuthenticate = $expectedResult;

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertEquals($expectedResult, $isValid);
    }

    public function testFailOverToDbIfConfigured()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = true;
        $this->fakeLdap->_ExpectedAuthenticate = false;

        $authResult = true;
        $this->fakeAuth->_ValidateResult = $authResult;

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertEquals($authResult, $isValid);
        $this->assertTrue($this->fakeLdap->_AuthenticateCalled);
        $this->assertEquals($this->username, $this->fakeAuth->_LastLogin);
        $this->assertEquals($this->password, $this->fakeAuth->_LastPassword);
    }

    public function testLoginSynchronizesInfoAndCallsAuthLogin()
    {
        Password::$_Random = 'random';
        $timezone = 'UTC';
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_TIMEZONE, $timezone);
        $languageCode = 'en_US';
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, $languageCode);

        $expectedUser = new AuthenticatedUser(
            $this->username,
            $this->ldapUser->GetEmail(),
            $this->ldapUser->GetFirstName(),
            $this->ldapUser->GetLastName(),
            'random',
            $languageCode,
            $timezone,
            $this->ldapUser->GetPhone(),
            $this->ldapUser->GetInstitution(),
            $this->ldapUser->GetTitle(),
            ['group1', 'group2', 'group3']
        );

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $auth->SetRegistration($this->fakeRegistration);

        $auth->Validate($this->username, $this->password);
        $auth->Login($this->username, $this->loginContext);

        $this->assertTrue($this->fakeRegistration->_SynchronizeCalled);
        $this->assertEquals($expectedUser, $this->fakeRegistration->_LastSynchronizedUser);
        $this->assertEquals($this->loginContext, $this->fakeAuth->_LastLoginContext);
    }

    public function testDoesNotSyncIfUserWasNotFoundInLdap()
    {
        $this->fakeLdap->_ExpectedLdapUser = null;

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);

        $auth->Validate($this->username, $this->password);
        $auth->Login($this->username, $this->loginContext);

        $this->assertFalse($this->fakeRegistration->_SynchronizeCalled);
        $this->assertEquals($this->loginContext, $this->loginContext);
    }

    public function testAdLdapConstructsOptionsCorrectly()
    {
        $controllers = 'localhost, localhost.2';
        $port = '389';
        $username = 'uname';
        $password = 'pw';
        $base = '';
        $usessl = 'false';
        $version = '3';
        $accountSuffix = '';

        $configFile = new FakeConfigFile();
        $configFile->SetKey(ActiveDirectoryConfigKeys::DOMAIN_CONTROLLERS, $controllers);
        $configFile->SetKey(ActiveDirectoryConfigKeys::PORT, $port);
        $configFile->SetKey(ActiveDirectoryConfigKeys::USERNAME, $username);
        $configFile->SetKey(ActiveDirectoryConfigKeys::PASSWORD, $password);
        $configFile->SetKey(ActiveDirectoryConfigKeys::BASEDN, $base);
        $configFile->SetKey(ActiveDirectoryConfigKeys::USE_SSL, $usessl);
        $configFile->SetKey(ActiveDirectoryConfigKeys::VERSION, $version);
        $configFile->SetKey(ActiveDirectoryConfigKeys::ACCOUNT_SUFFIX, $accountSuffix);

        $this->fakeConfig->SetFile(ActiveDirectoryConfigKeys::CONFIG_ID, $configFile);

        $ldapOptions = new ActiveDirectoryOptions();
        $options = $ldapOptions->AdLdapOptions();

        $this->assertNotNull($this->fakeConfig->_RegisteredFiles[ActiveDirectoryConfigKeys::CONFIG_ID]);
        $this->assertEquals('localhost', $options['domain_controllers'][0], 'domain_controllers must be an array');
        $this->assertEquals(intval($port), $options['ad_port'], 'port should be int');
        $this->assertEquals($username, $options['admin_username']);
        $this->assertEquals($password, $options['admin_password']);
        $this->assertEquals($base, $options['base_dn']);
        $this->assertEquals(false, $options['use_ssl']);
        $this->assertEquals($accountSuffix, $options['account_suffix']);
        $this->assertEquals(intval($version), $options['ldap_version'], "version should be int");
    }

    public function testGetAllHosts()
    {
        $controllers = 'localhost, localhost.2';

        $configFile = new FakeConfigFile();
        $configFile->SetKey(ActiveDirectoryConfigKeys::DOMAIN_CONTROLLERS, $controllers);
        $this->fakeConfig->SetFile(ActiveDirectoryConfigKeys::CONFIG_ID, $configFile);

        $options = new ActiveDirectoryOptions();

        $this->assertEquals(['localhost', 'localhost.2'], $options->Controllers(), "comma separated values should become array");
    }

    public function testConvertsEmailToUserName()
    {
        $email = 'user@email.com';
        $expectedUsername = 'user';

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $auth->Validate($email, $this->password);

        $this->assertEquals($expectedUsername, $this->fakeLdap->_LastUsername);
    }

    public function testConvertsUserNameWithDomainToUserName()
    {
        $username = 'domain\user';
        $expectedUsername = 'user';

        $auth = new ActiveDirectory($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $auth->Validate($username, $this->password);

        $this->assertEquals($expectedUsername, $this->fakeLdap->_LastUsername);
    }

    public function testCanGetAttributeMapping()
    {
        $attributeMapping = "sn= sn,givenname =givenname,mail=email ,telephonenumber=phone, physicaldeliveryofficename=physicaldeliveryofficename";

        $configFile = new FakeConfigFile();
        $configFile->SetKey(ActiveDirectoryConfigKeys::ATTRIBUTE_MAPPING, $attributeMapping);
        $this->fakeConfig->SetFile(ActiveDirectoryConfigKeys::CONFIG_ID, $configFile);

        $options = new ActiveDirectoryOptions();

        $expectedAttributes = ['sn', 'givenname', 'email', 'phone', 'physicaldeliveryofficename', 'title'];
        $this->assertEquals($expectedAttributes, $options->Attributes());
    }

    public function testGetsDefaultAttributes()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey(ActiveDirectoryConfigKeys::ATTRIBUTE_MAPPING, '');
        $this->fakeConfig->SetFile(ActiveDirectoryConfigKeys::CONFIG_ID, $configFile);

        $options = new ActiveDirectoryOptions();

        $expectedAttributes = ['sn', 'givenname', 'mail', 'telephonenumber', 'physicaldeliveryofficename', 'title'];
        $this->assertEquals($expectedAttributes, $options->Attributes());
    }

    public function testMapsUserAttributes()
    {
        $mapping = [
            'sn' => 'sn',
            'givenname' => 'givenname',
            'mail' => 'fooName',
        ];

        $entry = new TestAdLdapEntry();
        $entry->sn = 'sn';
        $entry->givenname = 'given';
        $entry->fooName = 'foo';
        $entry->telephonenumber = 'phone';

        $user = new ActiveDirectoryUser($entry, $mapping);

        $this->assertEquals('sn', $user->GetLastName());
        $this->assertEquals('given', $user->GetFirstName());
        $this->assertEquals('foo', $user->GetEmail());
        $this->assertEquals('phone', $user->GetPhone());
    }
}

class TestAdLdapEntry
{
    public $sn;
    public $givenname;
    public $mail;
    public $telephonenumber;
    public $physicaldeliveryofficename;
    public $title;
    public $groups;
}
