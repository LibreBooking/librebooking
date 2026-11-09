<?php

require_once(ROOT_DIR . 'Domain/Values/WebService/WebServiceUserSession.php');

interface IUserSessionRepository
{
    /**
     * @param int $userId
     * @return WebServiceUserSession|null
     */
    public function LoadByUserId($userId);

    /**
     * @param string $sessionToken
     */
    public function LoadBySessionToken($sessionToken): ?WebServiceUserSession;

    /**
     * @param WebServiceUserSession $session
     * @return void
     */
    public function Add(WebServiceUserSession $session);

    /**
     * @param WebServiceUserSession $session
     * @return void
     */
    public function Update(WebServiceUserSession $session);

    /**
     * @param WebServiceUserSession $session
     * @return void
     */
    public function Delete(WebServiceUserSession $session);

    /**
     * @return void
     */
    public function CleanUp();
}

class UserSessionRepository implements IUserSessionRepository
{
    public function LoadByUserId($userId)
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetUserSessionByUserIdCommand($userId));
        if ($row = $reader->GetRow()) {
            $reader->Free();
            return unserialize($row[ColumnNames::USER_SESSION]);
        }
        $reader->Free();
        return null;
    }

    public function LoadBySessionToken($sessionToken): ?WebServiceUserSession
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetUserSessionBySessionTokenCommand($sessionToken));
        if ($row = $reader->GetRow()) {
            $reader->Free();
            return unserialize($row[ColumnNames::USER_SESSION]);
        }
        $reader->Free();
        return null;
    }

    public function Add(WebServiceUserSession $session)
    {
        $serializedSession = serialize($session);
        ServiceLocator::GetDatabase()->Execute(new AddUserSessionCommand($session->UserId, $session->SessionToken, Date::Now(), $serializedSession));
    }

    public function Update(WebServiceUserSession $session)
    {
        $serializedSession = serialize($session);
        ServiceLocator::GetDatabase()->Execute(new UpdateUserSessionCommand($session->UserId, $session->SessionToken, Date::Now(), $serializedSession));
    }

    public function Delete(WebServiceUserSession $session)
    {
        ServiceLocator::GetDatabase()->Execute(new DeleteUserSessionCommand($session->SessionToken));
    }

    public function CleanUp()
    {
        ServiceLocator::GetDatabase()->Execute(new CleanUpUserSessionsCommand());
    }
}
