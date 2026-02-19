<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class ReportCommandBuilderTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testJustFullResourceList()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfResources()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testJustFullAccessoryList()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfAccessories()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredBySchedule()
    {
        $scheduleId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfAccessories()
                          ->WithScheduleIds($scheduleId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::SCHEDULE_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByAccessory()
    {
        $accessoryId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfAccessories()
                          ->WithAccessoryIds($accessoryId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByUser()
    {
        $userId = 123;

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfResources()
                          ->WithUserId($userId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::USER_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByParticipant()
    {
        $userId = 123;

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfResources()
                          ->WithParticipantId($userId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::PARTICIPANT_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByGroup()
    {
        $groupId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfAccessories()
                          ->WithGroupIds($groupId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::USER_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByGroupAndSchedule()
    {
        $groupId = [123];
        $scheduleId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfAccessories()
                          ->WithGroupIds($groupId)
                          ->WithScheduleIds($scheduleId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::USER_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ACCESSORY_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::USER_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::SCHEDULE_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::ORDER_BY_FRAGMENT, $actual->GetQuery());
    }

    public function testCountOfResourceIdGroupedByGroup()
    {
        $resourceId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectCount()
                          ->WithResourceIds($resourceId)
                          ->GroupByGroup()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::COUNT_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_BY_GROUP_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByDateRange()
    {
        $resourceId = [123];
        $start = Date::Now();
        $end = Date::Now();

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfResources()
                          ->WithResourceIds($resourceId)
                          ->Within($start, $end)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::USER_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_ID_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::DATE_FRAGMENT, $actual->GetQuery());
    }

    public function testGroupsByGroupAndResource()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectCount()
                          ->GroupByGroup()
                          ->GroupByResource()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::COUNT_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_BY_GROUP_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_BY_RESOURCE_FRAGMENT, $actual->GetQuery());
    }

    public function testGroupsBySchedule()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectCount()
                          ->GroupBySchedule()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::COUNT_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::SCHEDULE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_BY_SCHEDULE_FRAGMENT, $actual->GetQuery());
    }

    public function testGroupsByUser()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectCount()
                          ->GroupByUser()
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::COUNT_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::USER_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::GROUP_BY_USER_FRAGMENT, $actual->GetQuery());
    }

    public function testIfGroupByThenNoResourcesAreListed()
    {
        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectCount()
                          ->OfResources()
                          ->GroupByGroup()
                          ->Build();

        $this->assertStringNotContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringNotContainsString(ReportCommandBuilder::RESERVATION_LIST_FRAGMENT, $actual->GetQuery());
    }

    public function testFilteredByResourceType()
    {
        $resourceTypeId = [123];

        $builder = new ReportCommandBuilder();
        $actual = $builder->SelectFullList()
                          ->OfResources()
                          ->WithResourceTypeIds($resourceTypeId)
                          ->Build();

        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_LIST_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_JOIN_FRAGMENT, $actual->GetQuery());
        $this->assertStringContainsString(ReportCommandBuilder::RESOURCE_TYPE_ID_FRAGMENT, $actual->GetQuery());
    }
}
