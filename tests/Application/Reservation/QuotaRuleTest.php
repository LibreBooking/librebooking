<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class QuotaRuleTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $reservationViewRepository;

    /**
     * @var IQuotaRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $quotaRepository;

    /**
     * @var IUserRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $userRepository;

    /**
     * @var IScheduleRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $scheduleRepository;

    /**
     * @var FakeQuotaViewRepository
     */
    private $quotaViewRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationViewRepository = $this->createMock('IReservationViewRepository');
        $this->quotaRepository = $this->createMock('IQuotaRepository');
        $this->userRepository = $this->createMock('IUserRepository');
        $this->scheduleRepository = $this->createMock('IScheduleRepository');
        $this->quotaViewRepository = new FakeQuotaViewRepository();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testWhenQuotaThatAppliesToReservationResourceAndUserGroupIsNotExceed()
    {
        $scheduleId = 971243;
        $timezone = 'America/New_York';

        $userId = 10;
        $groupId1 = 8287;
        $groupId2 = 102;

        $user = new FakeUser();
        $user->SetGroups([$groupId1, $groupId2]);

        $schedule = new Schedule(1, null, null, null, null, $timezone);
        $resource = new FakeBookableResource(20);
        $resource->SetScheduleId($scheduleId);
        $series = ReservationSeries::Create(
            $userId,
            $resource,
            null,
            null,
            new TestDateRange(),
            new RepeatNone(),
            new FakeUserSession()
        );
        $series->AddResource(new FakeBookableResource(22));

        $quota1 = $this->mockQuota();
        $quota2 = $this->mockQuota();
        $quota3 = $this->mockQuota();

        $quotas = [$quota1, $quota2, $quota3];

        $this->quotaRepository->expects($this->once())
                              ->method('LoadAll')
                              ->willReturn($quotas);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->scheduleRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with($this->equalTo($scheduleId))
                                 ->willReturn($schedule);

        $this->ChecksAgainstQuota($quota1, $series, $this->reservationViewRepository, $schedule, $user);
        $this->ChecksAgainstQuota($quota2, $series, $this->reservationViewRepository, $schedule, $user);
        $this->ChecksAgainstQuota($quota3, $series, $this->reservationViewRepository, $schedule, $user);

        $rule = new QuotaRule(
            $this->quotaRepository,
            $this->reservationViewRepository,
            $this->userRepository,
            $this->scheduleRepository,
            $this->quotaViewRepository
        );
        $result = $rule->Validate($series, null);

        $this->assertTrue($result->IsValid(), 'no quotas were exceeded');
    }

    public function testFirstQuotaExceeded()
    {
        $scheduleId = 971243;
        $timezone = 'America/New_York';

        $userId = 10;
        $groupId1 = 8287;
        $groupId2 = 102;

        $user = new FakeUser();
        $user->SetGroups([$groupId1, $groupId2]);

        $schedule = new Schedule(1, null, null, null, null, $timezone);
        $resource = new FakeBookableResource(20);
        $resource->SetScheduleId($scheduleId);
        $series = ReservationSeries::Create(
            $userId,
            $resource,
            null,
            null,
            new TestDateRange(),
            new RepeatNone(),
            new FakeUserSession()
        );
        $series->AddResource(new FakeBookableResource(22));

        $quota1 = $this->mockQuota();
        $quota2 = $this->mockQuota();

        $quotas = [$quota1, $quota2];

        $this->quotaRepository->expects($this->once())
                              ->method('LoadAll')
                              ->willReturn($quotas);

        $this->userRepository->expects($this->once())
                             ->method('LoadById')
                             ->with($this->equalTo($userId))
                             ->willReturn($user);

        $this->scheduleRepository->expects($this->once())
                                 ->method('LoadById')
                                 ->with($this->equalTo($scheduleId))
                                 ->willReturn($schedule);

        $this->ChecksAgainstQuota($quota1, $series, $this->reservationViewRepository, $schedule, $user, true);

        $quota2->expects($this->never())
               ->method('ExceedsQuota');

        $rule = new QuotaRule(
            $this->quotaRepository,
            $this->reservationViewRepository,
            $this->userRepository,
            $this->scheduleRepository,
            $this->quotaViewRepository
        );
        $result = $rule->Validate($series, null);

        $this->assertFalse($result->IsValid(), 'first quotas was exceeded');
    }

    private function ChecksAgainstQuota($quota, $series, $repo, $schedule, $user, $exceeds = false)
    {
        $quota->expects($this->once())
              ->method('ExceedsQuota')
              ->with($this->equalTo($series), $this->equalTo($user), $this->equalTo($schedule), $this->equalTo($repo))
              ->willReturn($exceeds);
    }

    private function mockQuota()
    {
        $mock = $this->createMock('IQuota');
        $mock->expects($this->any())
             ->method('ToString')
             ->willReturn('');

        return $mock;
    }
}

class FakeQuotaViewRepository implements IQuotaViewRepository
{
    /**
     * @return array|QuotaItemView[]
     */
    public function GetAll()
    {
        return [];
    }
}
