<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class GroupAdminManageReservationsServiceTest extends TestBase
{
    public function testGetsListOfReservationsThatThisUserCanAdminister()
    {
        $user = new User();
        $adminGroup2 = new UserGroup(2, null, 1, RoleLevel::GROUP_ADMIN);
        $adminGroup3 = new UserGroup(3, null, 1, RoleLevel::GROUP_ADMIN);
        $user->WithOwnedGroups([$adminGroup2, $adminGroup3]);

        $reservationRepo = $this->createMock('IReservationViewRepository');
        $reservationAuth = $this->createMock('IReservationAuthorization');
        $handler = $this->createMock('IReservationHandler');
        $persistenceService = $this->createMock('IUpdateReservationPersistenceService');
        $userRepo = $this->createMock('IUserRepository');
        $userRepo->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($this->fakeUser->UserId))
                ->willReturn($user);

        $service = new GroupAdminManageReservationsService($reservationRepo, $userRepo, $reservationAuth, $handler, $persistenceService);

        $reservationRows = FakeReservationRepository::GetReservationRows();
        $this->db->SetRow(0, [ [ColumnNames::TOTAL => 4] ]);
        $this->db->SetRow(1, $reservationRows);

        $filter = new ReservationFilter();
        $results = $service->LoadFiltered(1, 2, null, null, $filter, $this->fakeUser);

        $getGroupReservationsCommand = new GetFullGroupReservationListCommand([2, 3]);

        $filterCommand = new FilterCommand($getGroupReservationsCommand, $filter->GetFilter());
        $countCommand = new CountCommand($filterCommand);

        $this->assertEquals($countCommand, $this->db->_Commands[0]);
        $this->assertEquals($filterCommand, $this->db->_Commands[1]);

        $resultList = $results->Results();
        $this->assertEquals(count($reservationRows), count($resultList));
        $this->assertInstanceOf('ReservationItemView', $resultList[0]);
    }
}
