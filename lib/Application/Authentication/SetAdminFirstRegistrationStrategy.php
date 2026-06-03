<?php

interface IFirstRegistrationStrategy
{
    /**
     * @param User $user
     * @param IUserRepository $userRepository
     * @param IGroupRepository $groupRepository
     * @return User
     */
    public function HandleLogin(User $user, IUserRepository $userRepository, IGroupRepository $groupRepository);
}

class SetAdminFirstRegistrationStrategy implements IFirstRegistrationStrategy
{
    public function HandleLogin(User $user, IUserRepository $userRepository, IGroupRepository $groupRepository)
    {
        $users = $userRepository->GetCount();
        if ($users == 1) {
            $configFile = ROOT_DIR . 'config/config.php';

            if (file_exists($configFile)) {
                $str = file_get_contents($configFile);
                $str = str_replace('admin@example.com', $user->EmailAddress(), $str);
                file_put_contents($configFile, $str);
                $this->ReloadCachedConfig();
            }

            $groups = $user->Groups();
            if (count($groups) === 0) {
                $groupId = $groupRepository->Add(new Group(0, 'Administrators'));
                $adminGroup = $groupRepository->LoadById($groupId);
                $adminGroup->ChangeRoles([RoleLevel::APPLICATION_ADMIN]);
                $adminGroup->AddUser($user->Id());
                $groupRepository->Update($adminGroup);
            }

            return $userRepository->LoadById($user->Id());
        }

        return $user;
    }

    private function ReloadCachedConfig()
    {
        Configuration::SetInstance(null);
    }
}
