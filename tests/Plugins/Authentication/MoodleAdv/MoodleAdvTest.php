<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'plugins/Authentication/MoodleAdv/namespace.php');

class MoodleAdvTest extends TestBase
{
    public function testRejectsLoginWhenRolesMethodHasNoRolesConfigured()
    {
        $auth = $this->createPlugin(authMethod: 'roles', roles: '');

        $this->assertFalse($auth->Validate('user', 'password'));
    }

    public function testRejectsLoginWhenAuthenticationMethodIsUnknown()
    {
        $auth = $this->createPlugin(authMethod: 'unknown', roles: '');

        $this->assertFalse($auth->Validate('user', 'password'));
    }

    private function createPlugin(string $authMethod, string $roles): MoodleAdv
    {
        $pluginConfig = new FakeConfigFile();
        $pluginConfig->SetKey(MoodleAdvConfigKeys::AUTH_METHOD, $authMethod);
        $pluginConfig->SetKey(MoodleAdvConfigKeys::ROLES, $roles);
        $this->fakeConfig->SetFile(MoodleAdvConfigKeys::CONFIG_ID, $pluginConfig);

        return new MoodleAdv(authentication: new FakeAuth());
    }
}
