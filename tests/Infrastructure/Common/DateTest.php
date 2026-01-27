<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class DateTest extends TestBase
{
    private $tz;
    private $datestring;

    public function setUp(): void
    {
        parent::setup();

        $this->datestring = date(DATE_W3C, time());
        $this->tz = new DateTimeZone('UTC');
    }

    public function testCanGetNow()
    {
        $format = 'd m y H:i:s';

        Date::_ResetNow();
        $now = Date::Now();
        $datenow = new DateTime(date(Date::SHORT_FORMAT, time()));

        $this->assertEquals($datenow->format($format), $now->Format($format));
    }

    public function testCanAddDays()
    {
        $format = 'd m y H:i:s';

        $hour = 11;
        $minute = 40;
        $second = 22;
        $month = 3;
        $day = 21;
        $year = 2007;

        $string = "$year-$month-$day $hour:$minute:$second";
        $now = new Date($string);

        $expectedTS = mktime($hour, $minute, $second, $month, $day + 20, $year);

        $twentyDaysDate = $now->AddDays(20);

        $this->assertEquals($expectedTS, $twentyDaysDate->Timestamp());
    }

    public function testCanSubtractDays()
    {
        $format = 'd m y H:i:s';

        $hour = 11;
        $minute = 40;
        $second = 22;
        $month = 3;
        $day = 21;
        $year = 2007;

        $now = new Date("$year-$month-$day $hour:$minute:$second");

        $expectedTS = mktime($hour, $minute, $second, $month, $day - 20, $year);
        $twentyDaysDate = $now->AddDays(-20);

        $this->assertEquals($expectedTS, $twentyDaysDate->Timestamp());
    }

    public function testCanGetAsDateTime()
    {
        $now = new Date($this->datestring);
        $datenow = new DateTime($this->datestring);

        $parts = date_parse($datenow->format(DATE_W3C));

        $exptected = mktime(
            $parts['hour'],
            $parts['minute'],
            $parts['second'],
            $parts['month'],
            $parts['day'],
            $parts['year']
        );

        $this->assertEquals($exptected, $now->Timestamp());
    }

    public function testCanConvertToTimezone()
    {
        $format = 'd m y H:i:s';

        $now = new Date($this->datestring);

        $datetime = new DateTime($this->datestring);
        $datetime->setTimezone(new DateTimeZone('America/New_York'));

        $expected = $datetime->format($format);
        $adjusted = $now->ToTimezone("America/New_York");

        $this->assertEquals($expected, $adjusted->Format($format));
    }

    public function testDateGetsAdjustedIntoProvidedTimezone()
    {
        $format = 'd m y H:i:s';
        $tzName = 'America/New_York';
        $baseTz = new DateTimeZone($tzName);

        $actual = new Date($this->datestring, $tzName);

        $datetime = new DateTime($this->datestring, $baseTz);

        $expected = $datetime->format($format);

        $this->assertEquals($expected, $actual->Format($format));
    }

    public function testGmtConvertsDateToGMT()
    {
        $format = 'd m y H:i:s';

        $date = new Date($this->datestring);

        $datetime = new DateTime($this->datestring);
        $datetime->setTimezone(new DateTimeZone('UTC'));

        $expected = $datetime->format($format);

        $this->assertEquals($expected, $date->ToUtc()->Format($format));
    }

    public function testDatePartsAreParsedCorrectly()
    {
        $hour = 11;
        $minute = 40;
        $second = 22;
        $month = 3;
        $day = 21;
        $year = 2007;

        $date = new Date("$year-$month-$day $hour:$minute:$second", 'America/Chicago');

        $this->assertEquals($hour, $date->Hour());
        $this->assertEquals($minute, $date->Minute());
        $this->assertEquals($second, $date->Second());
        $this->assertEquals($month, $date->Month());
        $this->assertEquals($day, $date->Day());
        $this->assertEquals($year, $date->Year());


        $adjusted = $date->ToTimezone('America/New_York');

        $this->assertEquals($hour + 1, $adjusted->Hour());
        $this->assertEquals($minute, $adjusted->Minute());
        $this->assertEquals($second, $adjusted->Second());
        $this->assertEquals($month, $adjusted->Month());
        $this->assertEquals($day, $adjusted->Day());
        $this->assertEquals($year, $adjusted->Year());
    }

    public function testToDatabaseConvertsToGmtThenFormats()
    {
        $databaseformat = 'Y-m-d H:i:s';
        $date = new Date($this->datestring);

        $datetime = new DateTime($this->datestring);
        $datetime->setTimezone(new DateTimeZone('UTC'));

        $expected = $datetime->format($databaseformat);

        $this->assertEquals($expected, $date->ToDatabase());
    }

    public function testCanCreateTimeInServerTimezone()
    {
        $hour = 10;
        $min = 22;
        $sec = 21;

        $time = new Time($hour, $min, $sec);

        $this->assertEquals("$hour:$min:$sec", $time->ToString());
        $this->assertEquals($hour, $time->Hour());
        $this->assertEquals($min, $time->Minute());
        $this->assertEquals($sec, $time->Second());

        $time = new Time($hour, $min);

        $this->assertEquals("$hour:$min:00", $time->ToString());
        $this->assertEquals($hour, $time->Hour());
        $this->assertEquals($min, $time->Minute());
        $this->assertEquals(0, $time->Second());
    }

    public function testTimeCreatedInEasternCanBeConvertedToCentral()
    {
        $east = 'America/New_York';
        $central = 'America/Chicago';
        $hour = 10;
        $min = 10;
        $sec = 10;

        $time = Date::Create(2001, 1, 1, $hour, $min, $sec, $east);
        $converted = $time->ToTimezone($central);

        $this->assertEquals($hour - 1, $converted->Hour());
        $this->assertEquals($min, $converted->Minute());
        $this->assertEquals($sec, $converted->Second());

        $time = Date::Create(2001, 1, 1, 0, $min, $sec, $east);
        $converted = $time->ToTimezone($central);
        $this->assertEquals(23, $converted->Hour());

        $time = Date::Create(2001, 1, 1, 1, $min, $sec, $east);
        $converted = $time->ToTimezone($central);
        $this->assertEquals(0, $converted->Hour());
    }

    public function testCanParseTimeFromString()
    {
        $time = Time::Parse('10:11:12', 'UTC');

        $this->assertEquals(10, $time->Hour());
        $this->assertEquals(11, $time->Minute());
        $this->assertEquals(12, $time->Second());
        $this->assertEquals('UTC', $time->Timezone());

        $time = Time::Parse('13:11:12', 'UTC');

        $this->assertEquals(13, $time->Hour());
        $this->assertEquals(11, $time->Minute());
        $this->assertEquals(12, $time->Second());
        $this->assertEquals('UTC', $time->Timezone());

        $time = Time::Parse('10:11:12 PM', 'UTC');

        $this->assertEquals(22, $time->Hour());
        $this->assertEquals(11, $time->Minute());
        $this->assertEquals(12, $time->Second());
        $this->assertEquals('UTC', $time->Timezone());

        $time = Time::Parse('10:11:12 AM', 'UTC');

        $this->assertEquals(10, $time->Hour());
        $this->assertEquals(11, $time->Minute());
        $this->assertEquals(12, $time->Second());
        $this->assertEquals('UTC', $time->Timezone());

        $time = Time::Parse('10:11 AM', 'UTC');

        $this->assertEquals(10, $time->Hour());
        $this->assertEquals(11, $time->Minute());
        $this->assertEquals(00, $time->Second());
        $this->assertEquals('UTC', $time->Timezone());
    }

    public function testTimesCanBeCompared()
    {
        $date = Date::Parse('2010-01-01');
        $early = Time::Parse('10:11');
        $late = Time::Parse('12:11');

        $this->assertEquals(-1, $early->Compare($late, $date));
        $this->assertEquals(1, $late->Compare($early, $date));

        $early2 = Time::Parse('10:11', 'America/Chicago');
        $late2 = Time::Parse('10:11', 'America/Los_Angeles');

        $this->assertEquals(-1, $early2->Compare($late2, $date));
    }

    public function testCanCompareDateOnlyEquality()
    {
        $date1 = Date::Parse('2008-01-01 11:00:00', 'America/Chicago');
        $date2 = Date::Parse('2008-01-01 11:00:00', 'America/New_York');

        $this->assertTrue($date1->DateEquals($date2));

        $date1 = Date::Parse('2008-01-01 00:00:00', 'America/Chicago');
        $date2 = Date::Parse('2008-01-01 00:00:00', 'America/New_York');

        $this->assertFalse($date1->DateEquals($date2));
    }

    public function testCreateBuildsDateObjectCorectly()
    {
        $date = Date::Create(2008, 10, 9, 8, 7, 6, 'America/Chicago');

        $this->assertEquals(2008, $date->Year());
        $this->assertEquals(10, $date->Month());
        $this->assertEquals(9, $date->Day());
        $this->assertEquals(8, $date->Hour());
        $this->assertEquals(7, $date->Minute());
        $this->assertEquals(6, $date->Second());
        $this->assertEquals('America/Chicago', $date->Timezone());
    }

    public function testCanCompareDateRelativity()
    {
        $date1 = Date::Parse('2008-01-01 11:00:00', 'America/Chicago');
        $date2 = Date::Parse('2008-01-01 11:00:00', 'America/New_York');

        $this->assertEquals(0, $date1->DateCompare($date2));

        $date1 = Date::Parse('2008-01-01 00:00:00', 'America/Chicago');
        $date2 = Date::Parse('2008-01-01 00:00:00', 'America/New_York');

        $this->assertEquals(1, $date1->DateCompare($date2), 'midnight eastern is 11pm central');

        $date1 = Date::Parse('2008-01-01 00:00:00', 'America/Chicago');
        $date2 = Date::Parse('2008-01-01 22:00:00', 'America/New_York');

        $this->assertEquals(0, $date1->DateCompare($date2), 'same calendar day after timezone normalization');
    }

    public function GetDateReturnsDateAsOfMidnight()
    {
        $date = new Date('2009-10-10 10:10:10');
        $onlyDate = $date->GetDate();

        $this->assertEquals(0, $onlyDate->Hour());
        $this->assertEquals(0, $onlyDate->Minute());
        $this->assertEquals(0, $onlyDate->Second());
    }

    public function testDateIsWithinRange()
    {
        $begin = Date::Create(2008, 9, 9, 10, 11, 12, 'UTC');
        $end = Date::Create(2008, 10, 9, 10, 11, 12, 'UTC');

        $range = new DateRange($begin, $end);

        $within = $begin->AddDays(10);
        $notWithin = $begin->AddDays(-10);
        $exactStart = $begin;
        $exactEnd = $end;

        $this->assertTrue($range->Contains($within));
        $this->assertTrue($range->Contains($exactStart));
        $this->assertTrue($range->Contains($exactEnd));
        $this->assertFalse($range->Contains($notWithin));
    }

    public function testDateRangeIsWithinRange()
    {
        $begin = Date::Create(2008, 9, 9, 10, 11, 12, 'UTC');
        $end = Date::Create(2008, 10, 9, 10, 11, 12, 'UTC');

        $range = new DateRange($begin, $end);

        $within = new DateRange($begin->AddDays(10), $end->AddDays(-10));
        $notWithin = new DateRange($begin->AddDays(-10), $end->AddDays(-1));

        $exact = new DateRange($begin, $end);

        $this->assertTrue($range->ContainsRange($within));
        $this->assertTrue($range->ContainsRange($exact));
        $this->assertFalse($range->ContainsRange($notWithin));
    }

    public function testDateRangeReturnsAllDatesForRangeWithoutTime()
    {
        $begin = Date::Create(2008, 9, 9, 10, 11, 12, 'UTC');
        $end = Date::Create(2008, 9, 12, 10, 11, 12, 'UTC');

        $range = new DateRange($begin, $end);

        $expected[] = $begin->GetDate();
        $expected[] = $begin->AddDays(1)->GetDate();
        $expected[] = $begin->AddDays(2)->GetDate();
        $expected[] = $begin->AddDays(3)->GetDate();

        $actual = $range->Dates();

        //		foreach ($expected as $d)
        //		{
        //			echo $d->ToString();
        //			echo "\n";
        //		}
        //
        //		echo "\n";
        //
        //		foreach ($actual as $d)
        //		{
        //			echo $d->ToString();
        //			echo "\n";
        //		}

        //		$this->assertEquals($expected, $actual);
        $this->assertEquals(count($expected), count($actual));
        $this->assertTrue($expected[0]->Equals($actual[0]), "Dates[0] are not equal");
        $this->assertTrue($expected[1]->Equals($actual[1]), "Dates[1] are not equal");
        $this->assertTrue($expected[2]->Equals($actual[2]), "Dates[2] are not equal");
        $this->assertTrue($expected[3]->Equals($actual[3]), "Dates[3] are not equal");
    }

    public function testCanGetDifferenceBetweenTwoDates()
    {
        $date1 = Date::Parse('2011-01-01 12:15:45', 'UTC');
        $date2 = $date1->AddDays(1);

        $diff = $date1->GetDifference($date2);

        $secondsInOneDay = 60 * 60 * 24;

        $this->assertEquals($diff->TotalSeconds(), $secondsInOneDay, '2nd date is one day greater than 1st date');
        $this->assertEquals(1, $diff->Days());
        $this->assertEquals(0, $diff->Hours());
        $this->assertEquals(0, $diff->Minutes());

        $newDate = $date1->ApplyDifference($diff);
        $this->assertTrue($date2->Equals($newDate));

        $laterDate = Date::Parse('2011-07-26 05:30:00', 'America/New_York');
        $earlierDate = Date::Parse('2011-07-22 17:40:41', 'America/Chicago');

        $diff = $laterDate->GetDifference($earlierDate);
        $shouldBeEarlier = $laterDate->ApplyDifference($diff);

        $this->assertTrue($earlierDate->Equals($shouldBeEarlier));
    }

    public function testCanGetDifferenceBetweenTwoTimes()
    {
        $date1 = Date::Parse('2011-01-01 12:15:45', 'utc');
        $date2 = Date::Parse('2011-01-01 13:16:46', 'utc');

        $diff = $date1->GetDifference($date2);

        $this->assertEquals(0, $diff->Days());
        $this->assertEquals(1, $diff->Hours());
        $this->assertEquals(1, $diff->Minutes());
    }

    public function testCanGetDifferenceFromTime()
    {
        $seconds = (12 * 60 * 60) + (60 * 35);
        $str1 = "0d12h35m";
        $diff1 = DateDiff::FromTimeString($str1);

        $this->assertEquals($seconds, $diff1->TotalSeconds());
        $this->assertEquals(12, $diff1->Hours());
        $this->assertEquals(35, $diff1->Minutes());
        $this->assertEquals(0, $diff1->Days());
        $this->assertEquals("12 hours 35 minutes", $diff1->__toString());

        $seconds2 = (4 * 24 * 60 * 60) + (12 * 60 * 60) + (60 * 35);
        $str2 = "4d12h35m";
        $diff2 = DateDiff::FromTimeString($str2);

        $this->assertEquals($seconds2, $diff2->TotalSeconds());
        $this->assertEquals(12, $diff2->Hours());
        $this->assertEquals(35, $diff2->Minutes());
        $this->assertEquals(4, $diff2->Days());
        $this->assertEquals("4 days 12 hours 35 minutes", $diff2->__toString());

        $diff3 = DateDiff::FromTimeString("25h0m");
        $this->assertEquals((25 * 60 * 60), $diff3->TotalSeconds());

        $this->assertEquals(0, DateDiff::FromTimeString("dhm")->TotalSeconds());
        $this->assertEquals(0, DateDiff::FromTimeString("hm")->TotalSeconds());
        $this->assertEquals(0, DateDiff::FromTimeString("dm")->TotalSeconds());
    }

    public function testDateRangeOverlapsIfStartsWithinOrEndsWithin()
    {
        $dr1 = DateRange::Create('2011-01-01 12:00:00', '2011-01-02 11:00:00', 'UTC');
        $dr2 = DateRange::Create('2011-01-01 12:00:00', '2011-01-02 10:00:00', 'UTC');

        $this->assertTrue($dr1->Overlaps($dr2));
    }

    public function testDateRangeOverlapsIfStartsAndEndsExactlyTheSameTimes()
    {
        $dr1 = DateRange::Create('2011-01-01 12:00:00', '2011-01-02 11:00:00', 'UTC');
        $dr2 = DateRange::Create('2011-01-01 12:00:00', '2011-01-02 11:00:00', 'UTC');

        $this->assertTrue($dr1->Overlaps($dr2));
    }

    public function testDateRangeDoesNotOverlapIfStartsAtEnd()
    {
        $dr1 = DateRange::Create('2011-01-01 12:00:00', '2011-01-02 11:00:00', 'UTC');
        $dr2 = DateRange::Create('2011-01-02 11:00:00', '2011-01-04 10:00:00', 'UTC');

        $this->assertFalse($dr1->Overlaps($dr2));
    }

    public function testDateRangeOccursOnDateIfAnyDateStartsOrEnds()
    {
        $range = DateRange::Create('2011-01-01 12:00:00', '2011-01-03 00:00:00', 'UTC');
        $d1 = Date::Parse('2011-01-02 02:02:02', 'UTC');
        $d2 = Date::Parse('2011-01-01 23:59:59', 'UTC');
        $d3 = Date::Parse('2011-01-03 00:00:00', 'UTC');

        $this->assertTrue($range->OccursOn($d1));
        $this->assertTrue($range->OccursOn($d2));
        $this->assertFalse($range->OccursOn($d3));
    }

    public function testNullDateTests()
    {
        $null = NullDate::Instance();

        $this->assertNull($null->ToDatabase());
        $this->assertEquals($null, $null->ToTimezone('anything'));
    }

    public function testCanParseExactWithTimezone()
    {
        $d = Date::Parse('2012-04-06 12:02:03', 'America/New_York');
        $iso = $d->ToIso();

        $d2 = Date::ParseExact($iso);

        $this->assertTrue($d->Equals($d2), $d->ToUtc()->ToString() . ' ' . $d2->ToString());
    }

    public function testJan31Bug()
    {
        $d = new Date('2013-01-31', 'America/Chicago');
        $this->assertEquals(31, $d->Day());
        $this->assertEquals(1, $d->Month());
        $this->assertEquals(2013, $d->Year());
    }

    public function testTimeIntervalParsesEmptyValue()
    {
        $interval = TimeInterval::Parse('dhm');

        $this->assertEquals(0, $interval->TotalSeconds());
    }

    public function testWhenComparingDatePartOnly_UseTimeOfSourceDate()
    {
        $d1 = new Date('2014-09-14 02:00:00', 'America/Chicago');
        $d2 = new Date('2014-09-14 00:00:00', 'Europe/Berlin');

        $this->assertFalse($d1->DateEquals($d2));

        $d1 = new Date('2014-09-14 00:00:00', 'America/Chicago');
        $d2 = new Date('2014-09-14 07:00:00', 'Europe/Berlin');

        $this->assertTrue($d1->DateEquals($d2));

        $d1 = new Date('2014-09-14 02:00:00', 'America/Chicago');
        $d2 = new Date('2014-09-15 00:00:00', 'Europe/Berlin');

        $this->assertTrue($d1->DateEquals($d2));

        //		$d1 = new Date('2014-09-14 02:00:00', 'America/Chicago');
        //		$d2 = new Date('2014-09-14 22:00:00', 'Europe/Berlin');
        //
        //		$this->assertFalse($d1->DateEquals($d2));
    }

    public function testCountsWeekdaysAndWeekends()
    {
        $timezone = 'America/Chicago';
        $begin = Date::Parse('2014-11-01 12:00', $timezone);
        $end = Date::Parse('2014-11-20 08:00', $timezone);
        $range = new DateRange($begin, $end, $timezone);

        $this->assertEquals(14, $range->NumberOfWeekdays());
        $this->assertEquals(6, $range->NumberOfWeekendDays());
    }

    public function testOneWeekday()
    {
        $timezone = 'America/Chicago';
        $begin = Date::Parse('2014-11-03 12:00', $timezone);
        $end = Date::Parse('2014-11-03 13:00', $timezone);
        $range = new DateRange($begin, $end, $timezone);

        $this->assertEquals(1, $range->NumberOfWeekdays());
        $this->assertEquals(0, $range->NumberOfWeekendDays());
    }

    public function testOneWeekendDay()
    {
        $timezone = 'America/Chicago';
        $begin = Date::Parse('2014-11-01 12:00', $timezone);
        $end = Date::Parse('2014-11-01 13:00', $timezone);
        $range = new DateRange($begin, $end, $timezone);

        $this->assertEquals(0, $range->NumberOfWeekdays());
        $this->assertEquals(1, $range->NumberOfWeekendDays());
    }

    public function testWhenEndDateIsAtMidnight_DoNotCountIt()
    {
        $timezone = 'America/Chicago';
        $begin = Date::Parse('2014-11-01 12:00', $timezone);
        $end = Date::Parse('2014-11-02 00:00', $timezone);
        $range = new DateRange($begin, $end, $timezone);

        $this->assertEquals(0, $range->NumberOfWeekdays());
        $this->assertEquals(1, $range->NumberOfWeekendDays());
    }

    public function testWebServiceDateDropsUsesIfSetTimezoneOffset()
    {
        $date = WebServiceDate::GetDate('2014-09-14T06:00:00-0000', $this->fakeUser);

        $this->assertEquals($date->Hour(), 6);
        $this->assertEquals($date->Timezone(), 'UTC');
    }

    public function testWebServiceParsesJustDate()
    {
        $date = WebServiceDate::GetDate('2014-09-14', $this->fakeUser);

        $this->assertEquals($date->Hour(), 0);
        $this->assertEquals($date->Timezone(), $this->fakeUser->Timezone);
    }

    public function testWebServiceParsesJustDateAndTime()
    {
        $date = WebServiceDate::GetDate('2014-09-14 06:00:00', $this->fakeUser);

        $this->assertEquals($date->Hour(), 6);
        $this->assertEquals($date->Timezone(), $this->fakeUser->Timezone);
    }

    public function testDateRangeReturnsAllDatesTimesForRange()
    {
        $begin = Date::Create(2008, 9, 9, 10, 11, 12, 'UTC');
        $end = Date::Create(2008, 9, 12, 10, 11, 12, 'UTC');

        $range = new DateRange($begin, $end);

        $expected[] = $begin;
        $expected[] = $begin->AddDays(1)->GetDate();
        $expected[] = $begin->AddDays(2)->GetDate();
        $expected[] = $end;

        $actual = $range->DateTimes();

        $this->assertEquals(count($expected), count($actual));
        $this->assertTrue($expected[0]->Equals($actual[0]), "Dates[0] are not equal");
        $this->assertTrue($expected[1]->Equals($actual[1]), "Dates[1] are not equal");
        $this->assertTrue($expected[2]->Equals($actual[2]), "Dates[2] are not equal");
        $this->assertTrue($expected[3]->Equals($actual[3]), "Dates[3] are not equal");
    }

    public function testAddMonth()
    {
        $hour = 11;
        $minute = 40;
        $second = 22;
        $month = 3;
        $day = 21;
        $year = 2007;

        $now = new Date("$year-$month-$day $hour:$minute:$second");
        $expectedTS = mktime($hour, $minute, $second, $month + 1, $day, $year);
        $nextMonth = $now->AddMonths(1);

        $this->assertEquals($expectedTS, $nextMonth->Timestamp());

        $expectedTS = mktime($hour, $minute, $second, $month - 1, $day, $year);
        $nextMonth = $now->AddMonths(-1);

        $this->assertEquals($expectedTS, $nextMonth->Timestamp());
    }

    public function testAddYear()
    {
        $hour = 11;
        $minute = 40;
        $second = 22;
        $month = 3;
        $day = 21;
        $year = 2007;

        $now = new Date("$year-$month-$day $hour:$minute:$second");
        $expectedTS = mktime($hour, $minute, $second, $month, $day, $year + 1);
        $nextMonth = $now->AddYears(1);

        $this->assertEquals($expectedTS, $nextMonth->Timestamp());

        $expectedTS = mktime($hour, $minute, $second, $month, $day, $year - 1);
        $nextMonth = $now->AddYears(-1);

        $this->assertEquals($expectedTS, $nextMonth->Timestamp());
    }
}
