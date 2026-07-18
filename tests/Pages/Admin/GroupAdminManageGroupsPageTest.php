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
        $canExportGroups = new ReflectionProperty(ManageGroupsPage::class, 'CanExportGroups');

        $this->assertFalse($canChangeRoles->getValue($page));
        $this->assertFalse($canImportGroups->getValue($page));
        $this->assertFalse($canExportGroups->getValue($page));
    }

    public function testEnablesRoleAndImportControlsForApplicationAdmins(): void
    {
        $page = new ManageGroupsPage();

        $canChangeRoles = new ReflectionProperty(ManageGroupsPage::class, 'CanChangeRoles');
        $canImportGroups = new ReflectionProperty(ManageGroupsPage::class, 'CanImportGroups');
        $canExportGroups = new ReflectionProperty(ManageGroupsPage::class, 'CanExportGroups');

        $this->assertTrue($canChangeRoles->getValue($page));
        $this->assertTrue($canImportGroups->getValue($page));
        $this->assertTrue($canExportGroups->getValue($page));
    }
}
