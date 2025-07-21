<?php

require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

class ScheduleAdminScheduleRepositoryTest extends TestBase
{
    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $userRepository;

    /**
     * @var FakeUserSession
     */
    public $user;

    /**
     * @var ScheduleAdminScheduleRepository
     */
    public $repo;

    public function setUp(): void
    {
        $this->userRepository = $this->createMock('IUserRepository');
        $this->user = new FakeUserSession();
        $this->repo = new ScheduleAdminScheduleRepository($this->userRepository, $this->user);

        parent::setup();
    }

    public function testOnlyGetsSchedulesWhereUserIsAdmin()
    {
        $user = $this->createMock('User');
        $this->userRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($this->fakeUser->UserId))
                ->willReturn($user);

        $ra = new FakeScheduleRepository();
        $this->db->SetRows($ra->GetRows());

        $user->expects($this->exactly(2))
                ->method('IsScheduleAdminFor')
                ->willReturnCallback(function ($schedule) use ($ra)
                {
                    return $this
                        ->equalTo($ra->_AllRows[1])
                        ->evaluate($schedule, '', true);
                });

        $schedules = $this->repo->GetAll();

        $this->assertTrue($this->db->ContainsCommand(new GetAllSchedulesCommand()));
        $this->assertEquals(1, count($schedules));
        $this->assertEquals(2, $schedules[0]->GetId());
    }

    public function testDoesNotUpdateScheduleIfUserDoesNotHaveAccess()
    {
        $user = $this->createMock('User');
        $this->userRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($this->fakeUser->UserId))
                ->willReturn($user);

        $schedule = new FakeSchedule(1);
        $schedule->SetAdminGroupId(2);

        $user->expects($this->atLeastOnce())
                ->method('IsScheduleAdminFor')
                ->willReturn(false);

        $actualEx = null;
        try {
            $this->repo->Update($schedule);
        } catch (Exception $ex) {
            $actualEx = $ex;
        }
        $this->assertNotEmpty($actualEx, "should have thrown an exception");
    }
}
