<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');

class ResourcePermissionStoreTest extends TestBase
{
    public function testRepositoryIsAccessedForUserPermissionInformation()
    {
        $userId = 99;

        $rid1 = 1;
        $rid2 = 2;
        $r1 = new ScheduleResource($rid1, 'resource 1');
        $r2 = new ScheduleResource($rid2, 'resource 2');
        $resources = [$r1, $r2];

        $rid3 = 3;
        $rid4 = 4;
        $r3 = new ScheduleResource($rid3, 'resource 3');
        $r4 = new ScheduleResource($rid4, 'resource 4');

        $g1 = new ScheduleGroup(100, [$r1, $r3], []);
        $g2 = new ScheduleGroup(200, [$r1, $r4, $r3], []);
        $groups = [$g1, $g2];

        $user = $this->createMock('IScheduleUser');

        $user->expects($this->once())
            ->method('GetAllResources')
            ->willReturn([$r1, $r2, $r3, $r4]);

        $userRepository = $this->createMock('IScheduleUserRepository');

        $userRepository->expects($this->once())
            ->method('GetUser')
            ->with($this->equalTo($userId))
            ->willReturn($user);

        $rps = new ResourcePermissionStore($userRepository);

        $permittedResources = $rps->GetAllResources($userId);

        $this->assertEquals(4, count($permittedResources));
        $this->assertContains($rid1, $permittedResources);
        $this->assertContains($rid2, $permittedResources);
        $this->assertContains($rid3, $permittedResources);
        $this->assertContains($rid4, $permittedResources);
    }

    public function testGetsFullPermissions()
    {
        $userFull = new ScheduleResource(1, 'user full');
        $userView = new ScheduleResource(2, 'user view');
        $groupFull = new ScheduleResource(3, 'group full');
        $groupView = new ScheduleResource(4, 'group view');
        $groupAdmin = new ScheduleResource(5, 'group admin');

        $full = [$userFull];
        $view = [$userView];
        $groupPermissions = [new ScheduleGroup(1, [$groupFull], [$groupView])];
        $groupAdminPermissions = [$groupAdmin];
        $userId = 1;
        $user = new ScheduleUser($userId, $full, $view, $groupPermissions, $groupAdminPermissions);

        $userRepository = $this->createMock('IScheduleUserRepository');
        $userRepository->expects($this->any())
            ->method('GetUser')
            ->willReturn($user);

        $rps = new ResourcePermissionStore($userRepository);

        $bookable = $rps->GetBookableResources($userId);
        $view = $rps->GetViewOnlyResources($userId);
        $all = $rps->GetAllResources($userId);

        $this->assertEquals([$userView->Id(), $groupView->Id()], $view);
        $this->assertEquals([$userFull->Id(), $groupFull->Id(), $groupAdmin->Id()], $bookable);
        $this->assertEquals(5, count($all));
    }
}
