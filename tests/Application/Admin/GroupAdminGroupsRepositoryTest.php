<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class GroupAdminGroupsRepositoryTest extends TestBase
{
    public function testGetsListOfGroupsThatThisUserCanAdminister()
    {
        $user = new User();
        $adminGroup1 = new UserGroup(1, null, null, RoleLevel::GROUP_ADMIN);
        $adminGroup2 = new UserGroup(2, null, 1, RoleLevel::GROUP_ADMIN);
        $adminGroup3 = new UserGroup(3, null, 1, RoleLevel::GROUP_ADMIN);
        $user->WithOwnedGroups([$adminGroup1, $adminGroup2, $adminGroup3]);

        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($this->fakeUser->UserId))
                ->willReturn($user);

        $groupRepository = new GroupAdminGroupRepository($userRepo, $this->fakeUser);

        $groupRows = new GroupItemRow();
        $groupRows->With(1, '1');
        $rows = $groupRows->Rows();

        $this->db->SetRow(0, [ [ColumnNames::TOTAL => 4] ]);
        $this->db->SetRow(1, $rows);

        $filter = null;
        $results = $groupRepository->GetList(1, 2, null, null, $filter);

        $getGroupsCommand = new GetAllGroupsCommand();

        $filterCommand = new FilterCommand($getGroupsCommand, new SqlFilterIn(new SqlFilterColumn(TableNames::GROUPS_ALIAS, ColumnNames::GROUP_ID), [1, 2, 3]));
        $countCommand = new CountCommand($filterCommand);

        $this->assertEquals($countCommand, $this->db->_Commands[0]);
        $this->assertEquals($filterCommand, $this->db->_Commands[1]);

        $resultList = $results->Results();
        $this->assertEquals(count($rows), count($resultList));
        $this->assertInstanceOf('GroupItemView', $resultList[0]);
    }
}
