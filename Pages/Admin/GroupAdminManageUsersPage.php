<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageUsersPage.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class GroupAdminManageUsersPage extends ManageUsersPage
{
    public function __construct()
    {
        parent::__construct();
        $this->_presenter->SetUserRepository(new GroupAdminUserRepository(new GroupRepository(), ServiceLocator::GetServer()->GetUserSession()));
        $groupRepository = new GroupAdminGroupRepository(new UserRepository(), ServiceLocator::GetServer()->GetUserSession());
        $this->_presenter->SetGroupRepository($groupRepository);
        $this->_presenter->SetGroupViewRepository($groupRepository);
    }

    protected function RenderPageTemplate(): void
    {
        $this->Set('ManageGroupsUrl', Pages::MANAGE_GROUPS_ADMIN);
        $this->Set('ManageReservationsUrl', Pages::MANAGE_GROUP_RESERVATIONS);
        parent::RenderPageTemplate();
    }
}
