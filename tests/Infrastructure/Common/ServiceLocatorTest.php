<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ServiceLocatorTest extends TestBase
{
    public function testGetUserSessionReturnsBrowserSessionOutsideApiRequests()
    {
        $this->assertSame($this->fakeUser, ServiceLocator::GetUserSession());
    }

    public function testGetUserSessionPrefersAuthenticatedApiSession()
    {
        $apiServer = new FakeRestServer();
        ServiceLocator::SetApiServer($apiServer);

        $this->assertSame($apiServer->session, ServiceLocator::GetUserSession());
    }

    public function testGetUserSessionFallsBackToBrowserSessionWhenApiRequestIsNotAuthenticated()
    {
        $apiServer = new class () extends FakeRestServer {
            public function GetSession()
            {
                return null;
            }
        };
        ServiceLocator::SetApiServer($apiServer);

        $this->assertSame($this->fakeUser, ServiceLocator::GetUserSession());
    }

    public function testGetUserSessionIsNullWithoutAnySession()
    {
        $this->fakeServer->SetUserSession(new NullUserSession());

        $this->assertNull(ServiceLocator::GetUserSession());
    }
}
