<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Server/namespace.php');

class ServerTest extends TestBase
{
    public function testGetUserSessionReturnsUserSessionForValidPayload()
    {
        $server = new InMemorySessionServer();
        $expected = new UserSession(123);
        $expected->FirstName = 'Test';
        $expected->LastName = 'User';
        $server->SetSession(SessionKeys::USER_SESSION, $expected);

        $session = $server->GetUserSession();

        $this->assertInstanceOf(UserSession::class, $session);
        $this->assertNotInstanceOf(NullUserSession::class, $session);
        $this->assertSame($expected, $session);
        $this->assertTrue($session->IsLoggedIn());
    }

    public function testGetUserSessionPreservesNullUserSessionType()
    {
        $server = new InMemorySessionServer();
        $server->SetSession(SessionKeys::USER_SESSION, new NullUserSession());

        $session = $server->GetUserSession();

        $this->assertInstanceOf(NullUserSession::class, $session);
        $this->assertFalse($session->IsLoggedIn());
        $this->assertTrue($session->IsGuest());
    }

    public function testGetUserSessionReturnsNullUserSessionForUnknownPayload()
    {
        $server = new InMemorySessionServer();
        $server->SetSession(SessionKeys::USER_SESSION, (object) ['foo' => 'bar']);

        $session = $server->GetUserSession();

        $this->assertInstanceOf(NullUserSession::class, $session);
        $this->assertFalse($session->IsLoggedIn());
        $this->assertTrue($session->IsGuest());
    }
}

class InMemorySessionServer extends Server
{
    private array $session = [];

    public function SetSession($name, $value)
    {
        $this->session[$name] = $value;
    }

    public function GetSession($name)
    {
        return $this->session[$name] ?? null;
    }
}
