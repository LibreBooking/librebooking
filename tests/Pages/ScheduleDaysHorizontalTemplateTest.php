<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

/**
 * Tests for the wide schedule view. The template is rendered against a
 * stand-in parent so only its reservations block is exercised.
 */
class ScheduleDaysHorizontalTemplateTest extends TestBase
{
    private const PARENT_TEMPLATE_DIR = __DIR__ . '/fixtures/schedule-block-only';
    private const TIMEZONE = 'UTC';
    private const FIRST_DAY = '2026-03-06';
    private const MIDDLE_DAY = '2026-03-07';
    private const LAST_DAY = '2026-03-08';
    private const OPENING_HOUR = '08:00';
    private const MIDDAY_HOUR = '12:00';
    private const CLOSING_HOUR = '17:00';
    private const SLOTS_PER_DAY = 2;
    private const DATE_FORMAT = 'Y-m-d';

    public function setUp(): void
    {
        parent::setUp();

        // FakeResources falls back to the key as the format, which FormatDate cannot translate
        $this->fakeResources->SetDateFormat('schedule_daily', self::DATE_FORMAT);
        $this->fakeResources->SetDateFormat('url', self::DATE_FORMAT);
    }

    public function testUsesFirstAndLastPeriodsWhenEveryDayHasPeriods(): void
    {
        $output = $this->render(daysWithPeriods: [self::FIRST_DAY, self::MIDDLE_DAY, self::LAST_DAY]);

        $this->assertTableRange(self::FIRST_DAY, self::LAST_DAY, $output);
        $this->assertSame(3 * self::SLOTS_PER_DAY, substr_count($output, 'class="slot"'));
    }

    public function testSkipsFirstDayWithoutPeriods(): void
    {
        $output = $this->render(daysWithPeriods: [self::MIDDLE_DAY, self::LAST_DAY]);

        $this->assertTableRange(self::MIDDLE_DAY, self::LAST_DAY, $output);
        $this->assertSame(2 * self::SLOTS_PER_DAY, substr_count($output, 'class="slot"'));
    }

    public function testSkipsLastDayWithoutPeriods(): void
    {
        $output = $this->render(daysWithPeriods: [self::FIRST_DAY, self::MIDDLE_DAY]);

        $this->assertTableRange(self::FIRST_DAY, self::MIDDLE_DAY, $output);
        $this->assertSame(2 * self::SLOTS_PER_DAY, substr_count($output, 'class="slot"'));
    }

    public function testSkipsFirstAndLastDaysWithoutPeriods(): void
    {
        $output = $this->render(daysWithPeriods: [self::MIDDLE_DAY]);

        $this->assertTableRange(self::MIDDLE_DAY, self::MIDDLE_DAY, $output);
        $this->assertSame(1, substr_count($output, 'class="resdate'));
    }

    public function testRendersNoTableWhenNoDayHasPeriods(): void
    {
        $output = $this->render(daysWithPeriods: []);

        $this->assertStringNotContainsString('<table', $output);
    }

    /**
     * @param string[] $daysWithPeriods
     */
    private function render(array $daysWithPeriods): string
    {
        $dailyLayout = $this->createStub(IDailyLayout::class);
        $dailyLayout->method('GetPeriods')->willReturnCallback(
            fn (Date $date): array => in_array($date->Format('Y-m-d'), $daysWithPeriods, true)
                ? $this->periodsFor($date->Format('Y-m-d'))
                : []
        );

        $slotFactory = $this->createStub(DisplaySlotFactory::class);
        $slotFactory->method('GetFunction')->willReturn('displaySlotStub');

        $page = new SmartyPage();
        $page->setTemplateDir(array_merge([self::PARENT_TEMPLATE_DIR], $page->getTemplateDir()));
        $page->assign('BoundDates', array_map(
            fn (string $day): Date => Date::Parse($day, self::TIMEZONE),
            [self::FIRST_DAY, self::MIDDLE_DAY, self::LAST_DAY]
        ));
        $page->assign('DailyLayout', $dailyLayout);
        $page->assign('DisplaySlotFactory', $slotFactory);
        $page->assign('Resources', [new TestResourceDto()]);
        $page->assign('ScheduleId', 1);

        return $page->fetch('Schedule/schedule-days-horizontal.tpl');
    }

    /**
     * @return SchedulePeriod[]
     */
    private function periodsFor(string $day): array
    {
        return [
            new SchedulePeriod($this->at($day, self::OPENING_HOUR), $this->at($day, self::MIDDAY_HOUR)),
            new SchedulePeriod($this->at($day, self::MIDDAY_HOUR), $this->at($day, self::CLOSING_HOUR)),
        ];
    }

    private function at(string $day, string $time): Date
    {
        return Date::Parse("$day $time", self::TIMEZONE);
    }

    private function assertTableRange(string $firstDay, string $lastDay, string $output): void
    {
        $min = $this->at($firstDay, self::OPENING_HOUR)->Timestamp();
        $max = $this->at($lastDay, self::CLOSING_HOUR)->Timestamp();

        $this->assertStringContainsString("data-min=\"$min\" data-max=\"$max\">", $output);
    }
}
