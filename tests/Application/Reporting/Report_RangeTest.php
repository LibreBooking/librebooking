<?php

declare(strict_types=1);

class Report_RangeTest extends TestBase
{
    /**
     * @var Date
     */
    private $now;

    /**
     * @var string
     */
    private $timezone;

    public function setUp(): void
    {
        parent::setup();

        $this->now = Date::Parse('2011-07-20 13:41:22', 'America/Chicago');
        Date::_SetNow($this->now);

        $this->timezone = $this->now->Timezone();
    }

    public function testGetsCurrentMonth()
    {
        $range = new Report_Range(Report_Range::CURRENT_MONTH, null, null, $this->timezone);

        $this->assertTrue(Date::Parse('2011-07-01', $this->fakeUser->Timezone)->Equals($range->Start()));
        $this->assertTrue(Date::Parse('2011-07-31', $this->fakeUser->Timezone)->Equals($range->End()));
        $this->assertEquals(31, count($range->Dates()));
    }

    public function testGetsCurrentWeek()
    {
        $range = new Report_Range(Report_Range::CURRENT_WEEK, null, null, $this->timezone);

        $this->assertTrue(Date::Parse('2011-07-17', $this->fakeUser->Timezone)->Equals($range->Start()));
        $this->assertTrue(Date::Parse('2011-07-23', $this->fakeUser->Timezone)->Equals($range->End()));
    }

    public function testGetsToday()
    {
        $range = new Report_Range(Report_Range::TODAY, null, null, $this->timezone);

        $this->assertTrue(Date::Parse('2011-07-20', $this->fakeUser->Timezone)->Equals($range->Start()));
        $this->assertTrue(Date::Parse('2011-07-20', $this->fakeUser->Timezone)->Equals($range->End()));
    }

    public function testDefaultsStartAndEnd()
    {
        $range = new Report_Range(Report_Range::DATE_RANGE, null, null, $this->timezone);

        $this->assertTrue(Date::Min()->Equals($range->Start()));
        $this->assertTrue(Date::Max()->Equals($range->End()));
    }
}
