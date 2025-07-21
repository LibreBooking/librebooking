<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class QuotaRepositoryTest extends TestBase
{
    /**
     * @var QuotaRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new QuotaRepository();
    }

    public function teardown(): void
    {
        parent::teardown();

        $this->repository = null;
    }

    public function testCanGetQuotas()
    {
        $limit = 12;
        $resourceId = 100;
        $groupId = 923;
        $scheduleId = 828;

        $rows[] = $this->GetRow(
            1,
            $limit,
            QuotaUnit::Reservations,
            QuotaDuration::Month,
            $resourceId,
            $groupId,
            $scheduleId,
            '11:00',
            '12:00',
            '0,6',
            QuotaScope::ExcludeCompleted
        );
        $rows[] = $this->GetRow(2, $limit, QuotaUnit::Hours, QuotaDuration::Day, null, null, null, null, null, null, QuotaScope::IncludeCompleted);
        $rows[] = $this->GetRow(
            3,
            $limit,
            QuotaUnit::Hours,
            QuotaDuration::Week,
            null,
            $groupId,
            $scheduleId,
            '00:00',
            '12:00',
            '1,2,3,4,5',
            QuotaScope::ExcludeCompleted
        );

        $this->db->SetRows($rows);

        /** @var Quota[] $quotas */
        $quotas = $this->repository->LoadAll();

        $quota1 = $quotas[0];
        $quota2 = $quotas[1];
        $quota3 = $quotas[2];

        $command = new GetAllQuotasCommand();
        $this->assertEquals($command, $this->db->_LastCommand);

        $this->assertEquals(new QuotaLimitCount($limit), $quota1->GetLimit());
        $this->assertEquals(new QuotaLimitHours($limit), $quota2->GetLimit());
        $this->assertEquals(new QuotaLimitHours($limit), $quota3->GetLimit());

        $this->assertEquals(new QuotaDurationMonth(), $quota1->GetDuration());
        $this->assertEquals(new QuotaDurationDay(), $quota2->GetDuration());
        $this->assertEquals(new QuotaDurationWeek(), $quota3->GetDuration());

        $this->assertEquals(new QuotaScopeExcluded(), $quota1->GetScope());
        $this->assertEquals(new QuotaScopeIncluded(), $quota2->GetScope());
        $this->assertEquals(new QuotaScopeExcluded(), $quota3->GetScope());

        $this->assertEquals(Time::Parse('11:00'), $quota1->EnforcedStartTime());
        $this->assertEquals(Time::Parse('12:00'), $quota1->EnforcedEndTime());
        $this->assertEquals([0, 6], $quota1->EnforcedDays());

        $this->assertEquals(null, $quota2->EnforcedStartTime());
        $this->assertEquals(null, $quota2->EnforcedEndTime());
        $this->assertEquals([], $quota2->EnforcedDays());

        $this->assertTrue($quota1->AppliesToResource($resourceId));
        $this->assertTrue($quota1->AppliesToGroup($groupId));
        $this->assertTrue($quota1->AppliesToSchedule($scheduleId));

        $this->assertFalse($quota1->AppliesToResource(727));
        $this->assertFalse($quota1->AppliesToGroup(727));
        $this->assertFalse($quota1->AppliesToSchedule(727));

        $this->assertTrue($quota2->AppliesToResource(89123987));
        $this->assertTrue($quota2->AppliesToGroup(128973));
        $this->assertTrue($quota2->AppliesToSchedule($scheduleId));

        $this->assertTrue($quota3->AppliesToResource(89123987));
        $this->assertTrue($quota3->AppliesToGroup($groupId));
        $this->assertFalse($quota3->AppliesToSchedule(18));
    }

    public function testAddsNewQuota()
    {
        $duration = QuotaDuration::Month;
        $limit = 2.4;
        $unit = QuotaUnit::Reservations;
        $resourceId = 2183;
        $groupId = 123987;
        $scheduleId = 102983;
        $enforcedDays = [2, 4, 5];
        $enforcedStartTime = '12:30pm';
        $enforcedEndTime = '4:00pm';
        $scope = QuotaScope::ExcludeCompleted;

        $quota = Quota::Create($duration, $limit, $unit, $resourceId, $groupId, $scheduleId, $enforcedStartTime, $enforcedEndTime, $enforcedDays, $scope);

        $command = new AddQuotaCommand(
            $duration,
            $limit,
            $unit,
            $resourceId,
            $groupId,
            $scheduleId,
            Time::Parse($enforcedStartTime),
            Time::Parse($enforcedEndTime),
            $enforcedDays,
            $scope
        );

        $this->repository->Add($quota);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    public function testDeletesById()
    {
        $id = 282;

        $command = new DeleteQuotaCommand($id);

        $this->repository->DeleteById($id);

        $this->assertEquals($command, $this->db->_LastCommand);
    }

    private function GetRow($quotaId, $limit, $unit, $duration, $resourceId, $groupId, $scheduleId, $enforcedStartTime, $enforcedEndTime, $enforcedDays, $scope)
    {
        return [ColumnNames::QUOTA_ID => $quotaId,
                ColumnNames::QUOTA_LIMIT => $limit,
                ColumnNames::QUOTA_UNIT => $unit,
                ColumnNames::QUOTA_DURATION => $duration,
                ColumnNames::RESOURCE_ID => $resourceId,
                ColumnNames::GROUP_ID => $groupId,
                ColumnNames::SCHEDULE_ID => $scheduleId,
                ColumnNames::ENFORCED_START_TIME => $enforcedStartTime,
                ColumnNames::ENFORCED_END_TIME => $enforcedEndTime,
                ColumnNames::ENFORCED_DAYS => $enforcedDays,
                ColumnNames::QUOTA_SCOPE => $scope,
        ];
    }
}
