<?php

require_once(ROOT_DIR . 'Domain/namespace.php');

class RepeatOptionsTest extends TestBase
{
    //http://www.timeanddate.com/calendar/

    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testNoRepeatNeverHasRepeatDates()
    {
        $duration = DateRange::Create('2010-01-12', '2010-02-12', 'UTC');

        $repeatOptions = new RepeatNone();
        $repeatedDates = $repeatOptions->GetDates($duration);

        $this->assertEquals(0, count($repeatedDates));
    }

    public function testTerminationDateIsInclusive()
    {
        $reservationStart = Date::Parse('2010-02-12 08:30', 'CST');
        $reservationEnd = Date::Parse('2010-02-12 10:30', 'CST');
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2010-02-14', 'CST');

        $repeatOptions = new RepeatDaily($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $this->assertEquals(2, count($repeatedDates));
    }

    public function testRepeatDailyCreatesRecurrenceEverySpecifiedDayUntilEndAcrossDST()
    {
        $reservationStart = Date::Parse('2010-02-12 08:30', 'CST');
        $reservationEnd = Date::Parse('2010-02-12 10:30', 'CST');
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 2;
        $terminiationDate = Date::Parse('2010-04-02', 'CST');

        $repeatOptions = new RepeatDaily($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 8 + 15 + 1;
        $firstDate = DateRange::Create('2010-02-14 08:30', '2010-02-14 10:30', 'CST');
        $lastDate = DateRange::Create('2010-04-01 08:30', '2010-04-01 10:30', 'CST');

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testRepeatWeeklyCreatesRecurrenceOnSpecifiedDaysEveryIntervalUntilEndAcrossDST()
    {
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-02-11 08:30', $timezone);
        $reservationEnd = Date::Parse('2010-02-11 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 2;
        $terminiationDate = Date::Parse('2010-04-01', $timezone);
        $daysOfWeek = [1, 3, 5];

        $repeatOptions = new RepeatWeekly($interval, $terminiationDate, $daysOfWeek);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 10;
        $firstDate = DateRange::Create('2010-02-12 08:30', '2010-02-12 10:30', $timezone);
        $secondDate = DateRange::Create('2010-02-22 08:30', '2010-02-22 10:30', $timezone);
        $thirdDate = DateRange::Create('2010-02-24 08:30', '2010-02-24 10:30', $timezone);
        $forthDate = DateRange::Create('2010-02-26 08:30', '2010-02-26 10:30', $timezone);
        $lastDate = DateRange::Create('2010-03-26 08:30', '2010-03-26 10:30', $timezone);


        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($secondDate->Equals($repeatedDates[1]), $secondDate->ToString() . ' ' . $repeatedDates[1]->ToString());
        $this->assertTrue($thirdDate->Equals($repeatedDates[2]), $thirdDate->ToString() . ' ' . $repeatedDates[2]->ToString());
        $this->assertTrue($forthDate->Equals($repeatedDates[3]), $forthDate->ToString() . ' ' . $repeatedDates[3]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testRepeatWeeklyCreatesRecurrenceOnSingleDayEveryIntervalUntilEndAcrossDST()
    {
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-02-11 08:30', $timezone);
        $reservationEnd = Date::Parse('2010-02-11 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2010-04-01', $timezone);
        $daysOfWeek = [3];

        $repeatOptions = new RepeatWeekly($interval, $terminiationDate, $daysOfWeek);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 7;
        $firstDate = DateRange::Create('2010-02-17 08:30', '2010-02-17 10:30', $timezone);
        $forthDate = DateRange::Create('2010-03-10 08:30', '2010-03-10 10:30', $timezone);
        $lastDate = DateRange::Create('2010-03-31 08:30', '2010-03-31 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($forthDate->Equals($repeatedDates[3]), $forthDate->ToString() . ' ' . $repeatedDates[3]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testMonthlyRepeatDayOfMonthWhenDayIsInAllMonths()
    {
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-02-11 08:30', $timezone);
        $reservationEnd = Date::Parse('2010-02-11 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2011-10-01', $timezone);

        $repeatOptions = new RepeatDayOfMonth($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 19;
        $firstDate = DateRange::Create('2010-03-11 08:30', '2010-03-11 10:30', $timezone);
        $secondDate = DateRange::Create('2010-04-11 08:30', '2010-04-11 10:30', $timezone);
        $lastDate = DateRange::Create('2011-09-11 08:30', '2011-09-11 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($secondDate->Equals($repeatedDates[1]), $secondDate->ToString() . ' ' . $repeatedDates[1]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testMonthlyRepeatDayOfMonthWhenDayIsNotInAllMonths()
    {
        $timezone = 'EST';
        $reservationStart = Date::Parse('2018-01-31 08:30', $timezone);
        $reservationEnd = Date::Parse('2018-01-31 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2018-12-31', $timezone);

        $repeatOptions = new RepeatDayOfMonth($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 6;
        $date1 = DateRange::Create('2018-03-31 08:30', '2018-03-31 10:30', $timezone);
        $date2 = DateRange::Create('2018-05-31 08:30', '2018-05-31 10:30', $timezone);
        $date3 = DateRange::Create('2018-07-31 08:30', '2018-07-31 10:30', $timezone);
        $date4 = DateRange::Create('2018-08-31 08:30', '2018-08-31 10:30', $timezone);
        $date5 = DateRange::Create('2018-10-31 08:30', '2018-10-31 10:30', $timezone);
        $date6 = DateRange::Create('2018-12-31 08:30', '2018-12-31 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($date1->Equals($repeatedDates[0]), $date1->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($date2->Equals($repeatedDates[1]), $date2->ToString() . ' ' . $repeatedDates[1]->ToString());
        $this->assertTrue($date3->Equals($repeatedDates[2]), $date3->ToString() . ' ' . $repeatedDates[2]->ToString());
        $this->assertTrue($date4->Equals($repeatedDates[3]), $date4->ToString() . ' ' . $repeatedDates[3]->ToString());
        $this->assertTrue($date5->Equals($repeatedDates[4]), $date5->ToString() . ' ' . $repeatedDates[4]->ToString());
        $this->assertTrue($date6->Equals($repeatedDates[5]), $date6->ToString() . ' ' . $repeatedDates[5]->ToString());
    }

    public function testMonthlyRepeatDayOfWeekWhenWeekIsInAllMonths()
    {
        //http://www.timeanddate.com/calendar/
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-03-01 08:30', $timezone); // first monday
        $reservationEnd = Date::Parse('2010-03-01 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2011-10-01', $timezone);

        $repeatOptions = new RepeatWeekDayOfMonth($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 18;
        $firstDate = DateRange::Create('2010-04-05 08:30', '2010-04-05 10:30', $timezone);
        $secondDate = DateRange::Create('2010-05-03 08:30', '2010-05-03 10:30', $timezone);
        $lastDate = DateRange::Create('2011-09-05 08:30', '2011-09-05 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($secondDate->Equals($repeatedDates[1]), $secondDate->ToString() . ' ' . $repeatedDates[1]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testMonthlyRepeatDayOfWeekWhenWeekIsNotInAllMonths()
    {
        //http://www.timeanddate.com/calendar/
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-03-31 08:30', $timezone); // fifth wednesday
        $reservationEnd = Date::Parse('2010-03-31 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 1;
        $terminiationDate = Date::Parse('2010-10-01', $timezone);

        $repeatOptions = new RepeatWeekDayOfMonth($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 2;
        $firstDate = DateRange::Create('2010-06-30 08:30', '2010-06-30 10:30', $timezone);
        $lastDate = DateRange::Create('2010-09-29 08:30', '2010-09-29 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testYearlyRepeat()
    {
        $timezone = 'EST';
        $reservationStart = Date::Parse('2010-03-31 08:30', $timezone); // fifth wednesday
        $reservationEnd = Date::Parse('2010-03-31 10:30', $timezone);
        $duration = new DateRange($reservationStart, $reservationEnd);

        $interval = 2;
        $terminiationDate = Date::Parse('2016-03-30', $timezone);

        $repeatOptions = new RepeatYearly($interval, $terminiationDate);
        $repeatedDates = $repeatOptions->GetDates($duration);

        $totalDates = 2;
        $firstDate = DateRange::Create('2012-03-31 08:30', '2012-03-31 10:30', $timezone);
        $lastDate = DateRange::Create('2014-03-31 08:30', '2014-03-31 10:30', $timezone);

        $this->assertEquals($totalDates, count($repeatedDates));
        $this->assertTrue($firstDate->Equals($repeatedDates[0]), $firstDate->ToString() . ' ' . $repeatedDates[0]->ToString());
        $this->assertTrue($lastDate->Equals($repeatedDates[$totalDates-1]), $lastDate->ToString() . ' ' . $repeatedDates[$totalDates-1]->ToString());
    }

    public function testFactoryCreatesRepeatDailyOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('daily', 1, null, null, null, []);

        $this->assertInstanceOf('RepeatDaily', $options);
    }

    public function testFactoryCreatesRepeatWeeklyOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('weekly', 1, null, [], null, []);

        $this->assertInstanceOf('RepeatWeekly', $options);
    }

    public function testFactoryCreatesDayOfMonthRepeatOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('monthly', 1, null, null, 'dayOfMonth', []);

        $this->assertInstanceOf('RepeatDayOfMonth', $options);
    }

    public function testFactoryCreatesWeekDayOfMonthRepeatOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('monthly', 1, null, null, null, []);

        $this->assertInstanceOf('RepeatWeekDayOfMonth', $options);
    }

    public function testFactoryCreatesYearlyRepeatOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('yearly', 1, null, null, null, []);

        $this->assertInstanceOf('RepeatYearly', $options);
    }

    public function testFactoryCreatesNoRepeatOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('none', 1, null, null, null, []);

        $this->assertInstanceOf('RepeatNone', $options);
    }

    public function testConfigurationStringCanBeDeserialized()
    {
        $terminationDate = Date::Parse('2010-12-12 01:06:07', 'UTC');
        $dateString = $terminationDate->ToDatabase();
        $interval = 10;

        // none
        $config = RepeatConfiguration::Create(RepeatType::None, '');
        $this->assertEquals(RepeatType::None, $config->Type);

        // daily
        $daily = new RepeatDaily($interval, $terminationDate);
        $config = RepeatConfiguration::Create($daily->RepeatType(), $daily->ConfigurationString());
        $this->assertEquals(RepeatType::Daily, $config->Type);
        $this->assertEquals(10, $config->Interval);
        $this->assertEquals($terminationDate, $config->TerminationDate);

        // weekly
        $weekdays = [1, 3, 4, 5];
        $weekly = new RepeatWeekly($interval, $terminationDate, $weekdays);
        $config = RepeatConfiguration::Create($weekly->RepeatType(), $weekly->ConfigurationString());
        $this->assertEquals(RepeatType::Weekly, $config->Type);
        $this->assertEquals($terminationDate, $config->TerminationDate);
        $this->assertEquals($weekdays, $config->Weekdays);

        // day of month
        $dayOfMonth = new RepeatDayOfMonth($interval, $terminationDate);
        $config = RepeatConfiguration::Create($dayOfMonth->RepeatType(), $dayOfMonth->ConfigurationString());
        $this->assertEquals(RepeatType::Monthly, $config->Type);
        $this->assertEquals($terminationDate, $config->TerminationDate);
        $this->assertEquals(RepeatMonthlyType::DayOfMonth, $config->MonthlyType);

        // weekday of month
        $weekOfMonth = new RepeatWeekDayOfMonth($interval, $terminationDate);
        $config = RepeatConfiguration::Create($weekOfMonth->RepeatType(), $weekOfMonth->ConfigurationString());
        $this->assertEquals(RepeatType::Monthly, $config->Type);
        $this->assertEquals($terminationDate, $config->TerminationDate);
        $this->assertEquals(RepeatMonthlyType::DayOfWeek, $config->MonthlyType);

        // yearly
        $yearly = new RepeatYearly($interval, $terminationDate);
        $config = RepeatConfiguration::Create($yearly->RepeatType(), $yearly->ConfigurationString());
        $this->assertEquals(RepeatType::Yearly, $config->Type);
        $this->assertEquals(10, $config->Interval);
        $this->assertEquals($terminationDate, $config->TerminationDate);

        // custom
        $custom = new RepeatCustom([]);
        $config = RepeatConfiguration::Create($custom->RepeatType(), $custom->ConfigurationString());
        $this->assertEquals(RepeatType::Custom, $config->Type);
        $this->assertEquals("", $config->Interval);
        $this->assertEquals(new NullDate(), $config->TerminationDate);
    }

    public function testRepeatWhenRepeatingDayBeforeFirstDayOfMonth()
    {
        // 2012-08 starts on wednesday, the 1st
        $firstSunday = Date::Parse('2012-08-05');
        $firstTuesday = Date::Parse('2012-08-07');
        $secondTuesday = Date::Parse('2012-08-14');
        $firstWednesday = Date::Parse('2012-08-01');
        $secondWednesday = Date::Parse('2012-08-08');
        $weekOfMonth = new RepeatWeekDayOfMonth(1, Date::Parse('2013-01-01'));

        $repeatDatesForFirstSun = $weekOfMonth->GetDates(new DateRange($firstSunday, $firstSunday));
        $repeatDatesForFirstTue = $weekOfMonth->GetDates(new DateRange($firstTuesday, $firstTuesday));
        $repeatDatesForSecondTue = $weekOfMonth->GetDates(new DateRange($secondTuesday, $secondTuesday));
        $repeatDatesForFirstWed = $weekOfMonth->GetDates(new DateRange($firstWednesday, $firstWednesday));
        $repeatDatesForSecondWed = $weekOfMonth->GetDates(new DateRange($secondWednesday, $secondWednesday));

        $firstRepeatedSun = $repeatDatesForFirstSun[0]->GetBegin();
        $firstRepeatedTue = $repeatDatesForFirstTue[0]->GetBegin();
        $secondRepeatedTue = $repeatDatesForSecondTue[0]->GetBegin();
        $firstRepeatedWed = $repeatDatesForFirstWed[0]->GetBegin();
        $secondRepeatedWed = $repeatDatesForSecondWed[0]->GetBegin();

        $this->assertTrue(Date::Parse('2012-09-02')->Equals($firstRepeatedSun), $firstRepeatedSun->__toString());
        $this->assertTrue(Date::Parse('2012-09-04')->Equals($firstRepeatedTue), $firstRepeatedTue->__toString());
        $this->assertTrue(Date::Parse('2012-09-05')->Equals($firstRepeatedWed), $firstRepeatedWed->__toString());
        $this->assertTrue(Date::Parse('2012-09-11')->Equals($secondRepeatedTue), $secondRepeatedTue->__toString());
        $this->assertTrue(Date::Parse('2012-09-12')->Equals($secondRepeatedWed), $secondRepeatedWed->__toString());
    }

    public function testRepeatingAcrossEuropeanDaylightSavings()
    {
        $firstWednesday = DateRange::Create('2013-02-06 09:00', '2013-02-06 10:00', 'Europe/London');
        $firstWednesdayRepeat = new RepeatWeekDayOfMonth(1, Date::Parse('2013-10-01', 'Europe/London'));

        /** @var DateRange[] $dates */
        $dates = $firstWednesdayRepeat->GetDates($firstWednesday);

        foreach ($dates as $date) {
            $date = $date->ToTimezone('Europe/London');
            $this->assertEquals(9, $date->GetBegin()->Hour(), $date->__toString());
            $this->assertEquals(10, $date->GetEnd()->Hour());
            $this->assertEquals(3, $date->GetBegin()->Weekday());
        }
    }

    public function testRepeatingAcrossEuropeanDaylightSavingsWithOtherExample()
    {
        $firstWednesday = DateRange::Create('2013-03-06 13:00:00', '2013-03-06 14:00:00', 'Europe/London');
        $firstWednesdayRepeat = new RepeatWeekDayOfMonth(1, Date::Parse('2013-12-24', 'Europe/London'));

        /** @var DateRange[] $dates */
        $dates = $firstWednesdayRepeat->GetDates($firstWednesday);

        foreach ($dates as $date) {
            $date = $date->ToTimezone('Europe/London');
            $this->assertEquals(13, $date->GetBegin()->Hour(), $date->__toString());
            $this->assertEquals(14, $date->GetEnd()->Hour());
            $this->assertEquals(3, $date->GetBegin()->Weekday());
        }
    }

    public function testRepeatFirstFridayWhenTheFirstDayOfTheMonthIsAFriday()
    {
        $firstFriday = DateRange::Create('2014-04-04 08:00', '2014-04-04 08:00', 'UTC');
        $repeat = new RepeatWeekDayOfMonth(1, Date::Parse('2015-01-01', 'UTC'));

        /** @var DateRange[] $dates */
        $dates = $repeat->GetDates($firstFriday);
        $this->assertEquals(1, $dates[3]->GetBegin()->Day());
    }

    public function testFactoryCreatesCustomRepeatOptions()
    {
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create('custom', null, null, null, null, []);

        $this->assertInstanceOf('RepeatCustom', $options);
    }

    public function testRepeatCustom()
    {
        $timezone = 'America/Chicago';
        $reservationDate = DateRange::Create('2020-02-02 2:30', '2020-02-03 4:00', $timezone);
        $repeatDates = [new Date('2020-02-05', $timezone), new Date('2020-02-22', $timezone), new Date('2020-05-19', $timezone),];
        $repeat = new RepeatCustom($repeatDates);

        $dates = $repeat->GetDates($reservationDate);
        $this->assertEquals(3, count($dates));
        $this->assertEquals(DateRange::Create('2020-02-05 2:30', '2020-02-06 4:00', $timezone), $dates[0]);
        $this->assertEquals(DateRange::Create('2020-02-22 2:30', '2020-02-23 4:00', $timezone), $dates[1]);
        $this->assertEquals(DateRange::Create('2020-05-19 2:30', '2020-05-20 4:00', $timezone), $dates[2]);
    }
}
