<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class ReservationTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testCreatingNewSeriesSetsAllSharedDataAndCreatesInstances()
    {
        $userId = 32;
        $resourceId = 10;
        $title = 'Title';
        $description = 'some long decription';
        $tz = 'America/Chicago';

        $userSession = new FakeUserSession();

        $startDateCst = '2010-02-02 12:15';
        $endDateCst = '2010-02-04 17:15';

        $startDateUtc = Date::Parse($startDateCst, $tz)->ToUtc();
        $endDateUtc = Date::Parse($endDateCst, $tz)->ToUtc();

        $dateRange = DateRange::Create($startDateCst, $endDateCst, $tz);
        $repeatedDate = DateRange::Create('2010-01-01', '2010-01-02', 'UTC');

        $repeatOptions = $this->createMock('IRepeatOptions');
        $repeatDates = [$repeatedDate];

        $repeatOptions->expects($this->once())
            ->method('GetDates')
            ->with($this->equalTo($dateRange->ToTimezone($userSession->Timezone)))
            ->willReturn($repeatDates);

        $resource = new FakeBookableResource($resourceId);
        $series = ReservationSeries::Create(
            $userId,
            $resource,
            $title,
            $description,
            $dateRange,
            $repeatOptions,
            $userSession
        );

        $this->assertEquals($userId, $series->UserId());
        $this->assertEquals($resource, $series->Resource());
        $this->assertEquals($title, $series->Title());
        $this->assertEquals($description, $series->Description());
        $this->assertTrue($series->IsRecurring());
        $this->assertEquals($repeatOptions, $series->RepeatOptions());

        $instances = array_values($series->Instances());

        $this->assertEquals(count($repeatDates) + 1, count($instances), 'should have original plus instances');
        $this->assertTrue($startDateUtc->Equals($instances[0]->StartDate()));
        $this->assertTrue($endDateUtc->Equals($instances[0]->EndDate()));

        $this->assertTrue($repeatedDate->GetBegin()->Equals($instances[1]->StartDate()));
        $this->assertTrue($repeatedDate->GetEnd()->Equals($instances[1]->EndDate()));
    }

    public function testCanGetSpecificInstanceByDate()
    {
        $startDate = Date::Parse('2010-02-02 12:15', 'UTC');
        $endDate = $startDate->AddDays(1);
        $dateRange = new DateRange($startDate, $endDate);

        $repeatOptions = $this->createMock('IRepeatOptions');

        $series = ReservationSeries::Create(1, new FakeBookableResource(1), null, null, $dateRange, $repeatOptions, new FakeUserSession());

        $instance = $series->CurrentInstance();

        $this->assertEquals($startDate, $instance->StartDate());
        $this->assertEquals($endDate, $instance->EndDate());
    }
}
