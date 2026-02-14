<?php

require_once(ROOT_DIR . 'plugins/Authentication/Shibboleth/namespace.php');

class ShibbolethUserTest extends TestBase
{
    public function testMapsValuesFromConfiguredServerAttributeKeys()
    {
        $options = new TestShibbolethOptions();

        $user = new ShibbolethUser([
            'REMOTE_USER' => 'jdoe',
            'givenName' => 'John',
            'sn' => 'Doe',
            'mail' => 'jdoe@example.com',
            'telephone' => '555-1234',
            'ou' => 'Engineering',
        ], $options);

        $this->assertEquals('jdoe', $user->GetUsername());
        $this->assertEquals('John', $user->GetFirstName());
        $this->assertEquals('Doe', $user->GetLastName());
        $this->assertEquals('jdoe@example.com', $user->GetEmailAddress());
        $this->assertEquals('555-1234', $user->GetPhone());
        $this->assertEquals('Engineering', $user->GetOrganization());
    }
}

class TestShibbolethOptions extends ShibbolethOptions
{
    public function __construct()
    {
        // Intentionally bypass parent constructor to avoid file I/O in unit tests.
    }

    protected function GetConfig($configDef, $converter = null)
    {
        return $configDef['default'] ?? null;
    }
}
