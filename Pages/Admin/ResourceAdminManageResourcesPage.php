<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageResourcesPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageResourcesPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

class ResourceAdminManageResourcesPage extends ManageResourcesPage
{
    public function __construct()
    {
        parent::__construct();
        $this->presenter = new ManageResourcesPresenter(
            $this,
            new ResourceAdminResourceRepository(new UserRepository(), ServiceLocator::GetServer()->GetUserSession()),
            new ScheduleRepository(),
            new ImageFactory(),
            new GroupRepository(),
            new AttributeService(new AttributeRepository()),
            new ReservationViewRepository()
        );
    }
}
