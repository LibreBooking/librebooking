<?php

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class ScheduleAdminManageReservationsServiceTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationViewRepository;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $userRepository;

    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    /**
     * @var ManageReservationsService
     */
    private $service;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->userRepository = $this->createMock('IUserRepository');
        $this->reservationAuthorization = $this->createMock('IReservationAuthorization');
        $handler = $this->createMock('IReservationHandler');
        $persistenceService = $this->createMock('IUpdateReservationPersistenceService');

        $this->service = new ScheduleAdminManageReservationsService($this->reservationViewRepository, $this->userRepository, $this->reservationAuthorization, $handler, $persistenceService);
    }

    public function testLoadsFilteredResultsAndChecksAuthorizationAgainstPendingReservations()
    {
        $pageNumber = 1;
        $pageSize = 40;

        $groups = [
            new UserGroup(1, '1'),
            new UserGroup(5, '5'),
            new UserGroup(9, '9'),
            new UserGroup(22, '22'),
        ];
        $myGroups = [1, 5, 9, 22];

        $this->userRepository->expects($this->once())
                    ->method('LoadGroups')
                    ->with($this->equalTo($this->fakeUser->UserId), $this->equalTo(RoleLevel::SCHEDULE_ADMIN))
                    ->willReturn($groups);

        $filter = new ReservationFilter();
        $expectedFilter = $filter->GetFilter();
        $expectedFilter->_And(new SqlFilterIn(new SqlFilterColumn(TableNames::SCHEDULES, ColumnNames::SCHEDULE_ADMIN_GROUP_ID), $myGroups));

        $data = new PageableData();
        $this->reservationViewRepository->expects($this->once())
                ->method('GetList')
                ->with($pageNumber, $pageSize, null, null, $expectedFilter)
                ->willReturn($data);

        $actualData = $this->service->LoadFiltered($pageNumber, $pageSize, null, null, $filter, $this->fakeUser);

        $this->assertEquals($data, $actualData);
    }
}
