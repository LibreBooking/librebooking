<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'plugins/Authentication/Ldap/namespace.php');
use Symfony\Component\Ldap\Entry;

class LdapTest extends TestBase
{
    /**
     * @var FakeAuth
     */
    private $fakeAuth;

    /**
     * @var FakeLdapOptions
     */
    private $fakeLdapOptions;

    /**
     * @var FakeLdapWrapper
     */
    private $fakeLdap;

    /**
     * @var LdapUser
     */
    private $ldapUser;

    /**
     * @var FakeRegistration
     */
    private $fakeRegistration;

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
        $this->fakeLdapOptions = new FakeLdapOptions();
        $this->fakeLdap = new FakeLdapWrapper();
        $this->fakeRegistration = new FakeRegistration();
        $this->encryption = new FakePasswordEncryption();

        $ldapEntry = new Entry('cn=test,dc=example,dc=org', [
            'sn' => ['user'],
            'givenname' => ['test'],
            'mail' => ['ldap@user.com'],
            'telephonenumber' => ['000-000-0000'],
            'physicaldeliveryofficename' => [''],
            'title' => [''],
            'groups' => ['memberOf'],
            'filter' => [''],
        ]);

        $this->ldapUser = new LdapUser($ldapEntry, []);

        $this->fakeLdap->_ExpectedLdapUser = $this->ldapUser;

        $this->loginContext = $this->createMock('ILoginContext');
    }

    public function testCanValidateUser()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = false;
        $expectedResult = true;
        $this->fakeLdap->_ExpectedAuthenticate = $expectedResult;

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
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
        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertFalse($isValid);
        $this->assertTrue($this->fakeLdap->_ConnectCalled);
        $this->assertTrue($this->fakeLdap->_AuthenticateCalled);
    }

    public function testValidateRethrowsRuntimeExceptionFromConnect()
    {
        $exception = new RuntimeException("LDAP settings 'host' and 'port' have been removed. Use only the 'uri' setting.");
        $this->fakeLdap->_ConnectException = $exception;

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LDAP settings 'host' and 'port' have been removed");

        $auth->Validate($this->username, $this->password);
    }

    public function testDoesNotTryToLoadUserDetailsIfNotValid()
    {
        $this->fakeLdap->_ExpectedAuthenticate = false;
        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertFalse($this->fakeLdap->_GetLdapUserCalled);
        $this->assertFalse($isValid);
    }

    public function testNotValidIfValidateFailsAndNotFailingOverToDb()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = false;
        $expectedResult = false;
        $this->fakeLdap->_ExpectedAuthenticate = $expectedResult;

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $isValid = $auth->Validate($this->username, $this->password);

        $this->assertEquals($expectedResult, $isValid);
    }

    public function testFailOverToDbIfConfigured()
    {
        $this->fakeLdapOptions->_RetryAgainstDatabase = true;
        $this->fakeLdap->_ExpectedAuthenticate = false;

        $authResult = true;
        $this->fakeAuth->_ValidateResult = $authResult;

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
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
            $this->ldapUser->GetTitle()
        );


        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
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

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);

        $auth->Validate($this->username, $this->password);
        $auth->Login($this->username, $this->loginContext);

        $this->assertFalse($this->fakeRegistration->_SynchronizeCalled);
        $this->assertEquals($this->loginContext, $this->loginContext);
    }

    public function testConstructsOptionsCorrectly()
    {
        $uris = 'ldap://localhost:389 ldap://localhost.2:389';
        $binddn = 'cn=admin,ou=users,dc=example,dc=org';
        $password = 'pw';
        $base = 'dc=example,dc=org';
        $starttls = 'false';
        $version = '3';

        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::URI, $uris);
        $configFile->SetKey(LdapConfigKeys::BINDDN, $binddn);
        $configFile->SetKey(LdapConfigKeys::BINDPW, $password);
        $configFile->SetKey(LdapConfigKeys::BASEDN, $base);
        $configFile->SetKey(LdapConfigKeys::STARTTLS, $starttls);
        $configFile->SetKey(LdapConfigKeys::VERSION, $version);

        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $ldapOptions = new LdapOptions();
        $options = $ldapOptions->GetConnectionConfig();

        $this->assertNotNull($this->fakeConfig->_RegisteredFiles[LdapConfigKeys::CONFIG_ID]);
        $this->assertEquals($uris, $options['connectionString'], 'connectionString should match configured URI list');
        $this->assertEquals($binddn, $options['binddn']);
        $this->assertEquals($password, $options['bindpw']);
        $this->assertEquals($base, $options['basedn']);
        $this->assertEquals(false, $options['starttls']);
        $this->assertEquals(intval($version), $options['version'], 'version should be int');
    }

    public function testGetAllHosts()
    {
        $controllers = 'ldap://localhost:389 ldap://localhost.2:389';

        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::URI, $controllers);
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->assertEquals(['ldap://localhost:389', 'ldap://localhost.2:389'], $options->Controllers(), 'space separated uris should become array');
    }

    public function testThrowsIfLegacyHostIsConfigured()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey([
            'key' => 'host',
            'section' => 'ldap',
            'type' => 'string',
        ], 'ldap://localhost');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LDAP settings 'host' and 'port' have been removed");

        $options->GetConnectionConfig();
    }

    public function testThrowsIfLegacyPortIsConfigured()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey([
            'key' => 'port',
            'section' => 'ldap',
            'type' => 'string',
        ], '389');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("LDAP settings 'host' and 'port' have been removed");

        $options->GetConnectionConfig();
    }

    public function testUserHandlesArraysAsAttribute()
    {
        $ldapEntry = new Entry('cn=test,dc=example,dc=org', [
            'sn' => ['user', 'user2'],
        ]);
        $user = new LdapUser($ldapEntry, []);

        $this->assertEquals('user', $user->GetLastName());
    }

    public function testConvertsEmailToUserName()
    {
        $email = 'user@email.com';
        $expectedUsername = 'user';

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $auth->Validate($email, $this->password);

        $this->assertEquals($expectedUsername, $this->fakeLdap->_LastUsername);
    }

    public function testConvertsUserNameWithDomainToUserName()
    {
        $username = 'domain\user';
        $expectedUsername = 'user';

        $auth = new Ldap($this->fakeAuth, $this->fakeLdap, $this->fakeLdapOptions);
        $auth->Validate($username, $this->password);

        $this->assertEquals($expectedUsername, $this->fakeLdap->_LastUsername);
    }

    public function testCanGetAttributeMapping()
    {
        $attributeMapping = 'sn= sn,givenname =givenname,mail=email ,telephonenumber=phone, physicaldeliveryofficename=physicaldeliveryofficename';

        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::ATTRIBUTE_MAPPING, $attributeMapping);
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $expectedAttributes = ['sn', 'givenname', 'email', 'phone', 'physicaldeliveryofficename', 'title'];
        $this->assertEquals($expectedAttributes, $options->Attributes());
    }

    public function testGetsDefaultAttributes()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::ATTRIBUTE_MAPPING, '');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $expectedAttributes = ['sn', 'givenname', 'mail', 'telephonenumber', 'physicaldeliveryofficename', 'title'];
        $this->assertEquals($expectedAttributes, $options->Attributes());
    }

    public function testGetsUserIdAttribute()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::USER_ID_ATTRIBUTE, 'user_id');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->assertEquals('user_id', $options->GetUserIdAttribute());
    }

    public function testGetsDefaultUserIdAttribute()
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::USER_ID_ATTRIBUTE, '');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->assertEquals('uid', $options->GetUserIdAttribute());
    }

    public function testThrowsForInvalidUserIdAttribute(): void
    {
        $configFile = new FakeConfigFile();
        $configFile->SetKey(LdapConfigKeys::USER_ID_ATTRIBUTE, 'uid)(|(cn=*))');
        $this->fakeConfig->SetFile(LdapConfigKeys::CONFIG_ID, $configFile);

        $options = new LdapOptions();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid LDAP attribute name for user.id.attribute');
        $options->GetUserIdAttribute();
    }

    public function testMapsUserAttributes()
    {
        $mapping = [
            'sn' => 'sn',
            'givenname' => 'givenname',
            'mail' => 'fooName',
        ];

        $entry = new Entry('cn=test,dc=example,dc=org', [
            'sn' => ['sn'],
            'givenname' => ['given'],
            'fooName' => ['foo'],
            'telephonenumber' => ['phone'],
        ]);

        $user = new LdapUser($entry, $mapping);

        $this->assertEquals('sn', $user->GetLastName());
        $this->assertEquals('given', $user->GetFirstName());
        $this->assertEquals('foo', $user->GetEmail());
        $this->assertEquals('phone', $user->GetPhone());
    }
}

class LdapIntegrationTest extends TestBase
{
    public function testAuthRealLdap()
    {
        require_once(ROOT_DIR . 'plugins/Authentication/Ldap/namespace.php');
        $ldap = new Ldap(PluginManager::Instance()->LoadAuthentication());
        $ldap->Validate('riemann', 'password');
    }
}

class FakeLdapOptions extends LdapOptions
{
    public function __construct()
    {
    }

    public $_RetryAgainstDatabase = false;

    public function RetryAgainstDatabase(): bool
    {
        return $this->_RetryAgainstDatabase;
    }

    public function IsLdapDebugOn(): bool
    {
        return false;
    }

    public function CleanUsername(): bool
    {
        return true;
    }

    public function AttributeMapping(): array
    {
        return parent::AttributeMapping(); // TODO: Change the autogenerated stub
    }

    public function Filter(): string
    {
        return '';
    }
}

class FakeLdapWrapper extends SymfonyLdapWrapper
{
    public function __construct()
    {
    }

    public $_ExpectedConnect = true;
    public $_ExpectedAuthenticate = true;

    public $_AuthenticateCalled = false;
    public $_GetLdapUserCalled = false;
    public $_ConnectCalled;

    public $_LastPassword;
    public $_LastUsername;

    public $_ExpectedLdapUser;

    public ?\RuntimeException $_ConnectException = null;

    public function Connect(): bool
    {
        $this->_ConnectCalled = true;
        if ($this->_ConnectException !== null) {
            throw $this->_ConnectException;
        }
        return $this->_ExpectedConnect;
    }

    public function Authenticate(string $username, string $password, string $filter): bool
    {
        $this->_AuthenticateCalled = true;
        $this->_LastUsername = $username;
        $this->_LastPassword = $password;

        return $this->_ExpectedAuthenticate;
    }

    public function GetLdapUser(string $username): ?LdapUser
    {
        $this->_GetLdapUserCalled = true;

        return $this->_ExpectedLdapUser;
    }
}
