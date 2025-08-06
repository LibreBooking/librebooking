<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class PluginManagerTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();

        PluginManager::SetInstance(null);
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testCanLoadAuthPluginThatExists()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PLUGIN_AUTHENTICATION, 'ActiveDirectory');

        $auth = PluginManager::Instance()->LoadAuthentication();

        $this->assertEquals('ActiveDirectory', get_class($auth));
    }

    public function testLoadsDefaultIfNoPluginConfigured()
    {
        $auth = PluginManager::Instance()->LoadAuthentication();

        $this->assertEquals('Authentication', get_class($auth));
    }

    public function testLoadsDefaultPluginNotFound()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PLUGIN_AUTHENTICATION, 'foo');
        $auth = PluginManager::Instance()->LoadAuthentication();

        $this->assertEquals('Authentication', get_class($auth));
    }

    public function testPreReservationPlugin()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PLUGIN_PRERESERVATION, 'foo');

        $plugin = PluginManager::Instance()->LoadPreReservation();

        $this->assertEquals('PreReservationFactory', get_class($plugin));
    }

    public function testPostReservationPlugin()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PLUGIN_POSTRESERVATION, 'foo');

        $plugin = PluginManager::Instance()->LoadPostReservation();

        $this->assertEquals('PostReservationFactory', get_class($plugin));
    }

    public function testPostRegistrationPlugin()
    {
        $this->fakeConfig->SetKey(ConfigKeys::PLUGIN_POSTREGISTRATION, 'foo');

        $plugin = PluginManager::Instance()->LoadPostRegistration();

        $this->assertEquals('PostRegistration', get_class($plugin));
    }
}
