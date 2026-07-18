<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/GroupAdminManageGroupsPage.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class GroupAdminManageGroupsPageTest extends TestBase
{
    public function testDisablesRoleAndImportControlsForGroupAdmins(): void
    {
        $page = new GroupAdminManageGroupsPage();

        $canChangeRoles = new ReflectionProperty(ManageGroupsPage::class, 'CanChangeRoles');
        $canImportGroups = new ReflectionProperty(ManageGroupsPage::class, 'CanImportGroups');

        $this->assertFalse($canChangeRoles->getValue($page));
        $this->assertFalse($canImportGroups->getValue($page));
    }

    public function testEnablesRoleAndImportControlsForApplicationAdmins(): void
    {
        $page = new ManageGroupsPage();

        $canChangeRoles = new ReflectionProperty(ManageGroupsPage::class, 'CanChangeRoles');
        $canImportGroups = new ReflectionProperty(ManageGroupsPage::class, 'CanImportGroups');

        $this->assertTrue($canChangeRoles->getValue($page));
        $this->assertTrue($canImportGroups->getValue($page));
    }
}
