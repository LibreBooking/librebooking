<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class CalendarSubscriptionUrlTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();

        $this->fakeConfig->SetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY, 'thekey');
    }

    public function testOnlyIncludesQueryStringForIdsThatWereProvided()
    {
        $url = new CalendarSubscriptionUrl(null, 'scheduleId', null);

        $webcalUrl = $url->GetWebcalUrl();

        $this->assertStringContainsString('sid=scheduleId', $webcalUrl);
        $this->assertStringNotContainsString('uid=', $webcalUrl);
        $this->assertStringNotContainsString('rid=', $webcalUrl);
    }

    public function testIncludesAllProvidedIds()
    {
        $url = new CalendarSubscriptionUrl('userId', 'scheduleId', 'resourceId');

        $webcalUrl = $url->GetWebcalUrl();

        $this->assertStringContainsString('uid=userId', $webcalUrl);
        $this->assertStringContainsString('sid=scheduleId', $webcalUrl);
        $this->assertStringContainsString('rid=resourceId', $webcalUrl);
    }

    public function testOnlyIncludesResourceIdWhenScheduleAndUserAreOmitted()
    {
        $url = new CalendarSubscriptionUrl(null, null, 'resourceId');

        $webcalUrl = $url->GetWebcalUrl();

        $this->assertStringContainsString('rid=resourceId', $webcalUrl);
        $this->assertStringNotContainsString('uid=', $webcalUrl);
        $this->assertStringNotContainsString('sid=', $webcalUrl);
    }

    public function testOnlyIncludesUserIdWhenScheduleAndResourceAreOmitted()
    {
        $url = new CalendarSubscriptionUrl('userId', null, null);

        $webcalUrl = $url->GetWebcalUrl();

        $this->assertStringContainsString('uid=userId', $webcalUrl);
        $this->assertStringNotContainsString('sid=', $webcalUrl);
        $this->assertStringNotContainsString('rid=', $webcalUrl);
    }

    public function testTreatsEmptyStringIdsTheSameAsNull()
    {
        $url = new CalendarSubscriptionUrl('', '', '');

        $webcalUrl = $url->GetWebcalUrl();

        $this->assertStringNotContainsString('uid=', $webcalUrl);
        $this->assertStringNotContainsString('sid=', $webcalUrl);
        $this->assertStringNotContainsString('rid=', $webcalUrl);
    }
}
