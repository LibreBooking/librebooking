<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'plugins/Authentication/MoodleAdv/namespace.php');

class MoodleAdvOptionsTest extends TestBase
{
    public function testReadsConfigValuesFromConfigDefinitions()
    {
        $pluginConfig = new FakeConfigFile();
        $pluginConfig->SetKey(MoodleAdvConfigKeys::DB_HOST, 'db.example.internal');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::DB_NAME, 'moodle_prod');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::DB_USER, 'moodle_user');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::DB_PASS, 'secret');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::DB_PREFIX, 'mdl_');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::AUTH_METHOD, 'roles');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::ROLES, '1,2');
        $pluginConfig->SetKey(MoodleAdvConfigKeys::FIELD, '10');
        $this->fakeConfig->SetFile(MoodleAdvConfigKeys::CONFIG_ID, $pluginConfig);

        $options = new TestMoodleAdvOptions();

        $this->assertEquals('db.example.internal', $options->GetDbHost());
        $this->assertEquals('moodle_prod', $options->GetDbName());
        $this->assertEquals('moodle_user', $options->GetDbUser());
        $this->assertEquals('secret', $options->GetDbPass());
        $this->assertEquals('mdl_', $options->GetTablePrefix());
        $this->assertEquals('roles', $options->GetAuthMethod());
        $this->assertEquals(['1', '2'], $options->GetRoles());
        $this->assertEquals('10', $options->GetField());
    }
}

class TestMoodleAdvOptions extends MoodleAdvOptions
{
    public function __construct()
    {
        // Intentionally bypass parent constructor to avoid file I/O in unit tests.
    }
}
