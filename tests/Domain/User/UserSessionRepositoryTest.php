<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/UserSessionRepository.php');

class UserSessionRepositoryTest extends TestBase
{
    /**
     * @var UserSessionRepository
     */
    private $repo;

    public function setUp(): void
    {
        parent::setup();

        $this->repo = new UserSessionRepository();
    }

    public function testLoadsByUserId()
    {
        $userId = 123;
        $token = 'my special token';

        $expectedSession = new WebServiceUserSession($userId);
        $expectedSession->SessionToken = $token;

        $serializedSession = serialize($expectedSession);

        $rows = [ColumnNames::USER_ID => $userId, ColumnNames::SESSION_TOKEN => $token, ColumnNames::USER_SESSION => $serializedSession];

        $this->db->SetRows([$rows]);

        $command = new GetUserSessionByUserIdCommand($userId);

        $actualSession = $this->repo->LoadByUserId($userId);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($expectedSession, $actualSession);
    }

    public function testAddsSession()
    {
        $userId = 123;
        $token = 'my special token';

        $expectedSession = new WebServiceUserSession($userId);
        $expectedSession->SessionToken = $token;
        $expectedSession->UserId = $userId;

        $serializedSession = serialize($expectedSession);

        $this->repo->Add($expectedSession);

        $command = new AddUserSessionCommand($userId, $token, Date::Now(), $serializedSession);
        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testUpdatesSession()
    {
        $userId = 123;
        $token = 'my special token';

        $expectedSession = new WebServiceUserSession($userId);
        $expectedSession->SessionToken = $token;
        $expectedSession->UserId = $userId;

        $serializedSession = serialize($expectedSession);

        $this->repo->Update($expectedSession);

        $command = new UpdateUserSessionCommand($userId, $token, Date::Now(), $serializedSession);
        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testLoadsBySessionToken()
    {
        $userId = 123;
        $token = 'my special token';

        $expectedSession = new WebServiceUserSession($userId);
        $expectedSession->SessionToken = $token;

        $serializedSession = serialize($expectedSession);

        $rows = [ColumnNames::USER_ID => $userId, ColumnNames::SESSION_TOKEN => $token, ColumnNames::USER_SESSION => $serializedSession];

        $this->db->SetRows([$rows]);

        $command = new GetUserSessionBySessionTokenCommand($token);

        $actualSession = $this->repo->LoadBySessionToken($token);

        $this->assertEquals($command, $this->db->_LastCommand);
        $this->assertEquals($expectedSession, $actualSession);
    }

    public function testDeletesBySessionToken()
    {
        $userId = 123;
        $token = 'my special token';

        $expectedSession = new WebServiceUserSession($userId);
        $expectedSession->SessionToken = $token;
        $expectedSession->UserId = $userId;

        $this->repo->Delete($expectedSession);

        $command = new DeleteUserSessionCommand($token);
        $this->assertEquals($command, $this->db->_LastCommand);
    }
}
