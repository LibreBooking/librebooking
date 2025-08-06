<?php

require_once(ROOT_DIR . 'Domain/Access/UserRepository.php');
require_once(ROOT_DIR . 'Domain/Access/GroupRepository.php');
require_once(ROOT_DIR . 'lib/Application/Admin/GroupAdminUserRepository.php');

interface IUserRepositoryFactory
{
    /**
     * @param UserSession $session
     * @return IUserRepository
     */
    public function Create(UserSession $session);
}

class UserRepositoryFactory implements IUserRepositoryFactory
{
    public function Create(UserSession $session)
    {
        $hideUsers = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter());

        if ($session->IsAdmin || !$hideUsers) {
            return new UserRepository();
        }

        return new GroupAdminUserRepository(new GroupRepository(), $session);
    }
}
