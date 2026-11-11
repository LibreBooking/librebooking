<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/SchedulePage.php');

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A phone renders schedule-mobile.tpl for every style except Tall, so the
 * effective style has to be normalized to Standard. Otherwise the toolbar marks
 * no control active and schedule.js takes rendering paths that do not match the
 * template that was emitted.
 */
class SchedulePageTest extends TestBase
{
    public const SCHEDULE_ID = 1;

    #[DataProvider('phoneStyles')]
    public function testNormalizesNonTallStylesToStandardOnAPhone(
        ScheduleStyle $selected,
        ScheduleStyle $expected
    ): void {
        $page = new TestableSchedulePage(isMobile: true, isTablet: false);

        $page->SetScheduleStyle($selected);

        $this->assertSame($expected, $page->EffectiveScheduleStyle());
        $this->assertSame($expected->value, $page->AssignedValue('ScheduleStyle'));
    }

    #[DataProvider('allStyles')]
    public function testPreservesTheSelectedStyleOnATablet(ScheduleStyle $selected): void
    {
        $page = new TestableSchedulePage(isMobile: true, isTablet: true);

        $page->SetScheduleStyle($selected);

        $this->assertSame($selected, $page->EffectiveScheduleStyle());
        $this->assertSame($selected->value, $page->AssignedValue('ScheduleStyle'));
    }

    #[DataProvider('allStyles')]
    public function testPreservesTheSelectedStyleOnADesktop(ScheduleStyle $selected): void
    {
        $page = new TestableSchedulePage(isMobile: false, isTablet: false);

        $page->SetScheduleStyle($selected);

        $this->assertSame($selected, $page->EffectiveScheduleStyle());
        $this->assertSame($selected->value, $page->AssignedValue('ScheduleStyle'));
    }

    public function testKeepsTheCookieNameTiedToTheSchedule(): void
    {
        $page = new TestableSchedulePage(isMobile: true, isTablet: false);

        $page->SetScheduleStyle(ScheduleStyle::Wide);

        $this->assertSame(
            'schedule-style-' . self::SCHEDULE_ID,
            $page->AssignedValue('CookieName')
        );
    }

    /**
     * @param string|string[] $submitted
     * @param int[] $expected
     */
    #[DataProvider('submittedResourceIds')]
    public function testConvertsSubmittedResourceIds(string|array $submitted, array $expected): void
    {
        $this->fakeServer->SetQuerystring(name: FormKeys::RESOURCE_ID, value: $submitted);
        $page = new TestableSchedulePage(isMobile: false, isTablet: false);

        $this->assertSame($expected, $page->GetResourceIds());
    }

    #[DataProvider('submittedWholeNumbers')]
    public function testReadsSingleFilterValuesAsWholeNumbers(string $submitted, int $expected): void
    {
        $this->fakeServer->SetQuerystring(name: QueryStringKeys::RESOURCE_ID, value: $submitted);
        $this->fakeServer->SetQuerystring(name: FormKeys::RESOURCE_TYPE_ID, value: $submitted);
        $this->fakeServer->SetQuerystring(name: FormKeys::MAX_PARTICIPANTS, value: $submitted);
        $page = new TestableSchedulePage(isMobile: false, isTablet: false);

        $this->assertSame($expected, $page->GetResourceId());
        $this->assertSame($expected, $page->GetResourceTypeId());
        $this->assertSame($expected, $page->GetMaxParticipants());
    }

    /**
     * @return array<string, array{0:string, 1:int}>
     */
    public static function submittedWholeNumbers(): array
    {
        return [
            'whole number' => ['5', 5],
            'malformed number' => ['5x', 0],
            'decimal' => ['1.5', 0],
            'negative number' => ['-4', 0],
            'nothing submitted' => ['', 0],
        ];
    }

    /**
     * @return array<string, array{0:string|string[], 1:int[]}>
     */
    public static function submittedResourceIds(): array
    {
        return [
            'single id' => ['5', [5]],
            'single malformed id' => ['5x', []],
            'single decimal' => ['1.5', []],
            'single negative id' => ['-4', []],
            'list of ids' => [['3', '10'], [3, 10]],
            'list with malformed ids' => [['3', '5x', '0', '10'], [3, 10]],
            'nothing submitted' => ['', []],
        ];
    }

    /**
     * @return array<string, array{0:ScheduleStyle, 1:ScheduleStyle}>
     */
    public static function phoneStyles(): array
    {
        return [
            'standard is left alone' => [ScheduleStyle::Standard, ScheduleStyle::Standard],
            'wide becomes standard' => [ScheduleStyle::Wide, ScheduleStyle::Standard],
            'condensed week becomes standard' => [ScheduleStyle::CondensedWeek, ScheduleStyle::Standard],
            'tall is preserved' => [ScheduleStyle::Tall, ScheduleStyle::Tall],
        ];
    }

    /**
     * @return array<string, array{0:ScheduleStyle}>
     */
    public static function allStyles(): array
    {
        return [
            'standard' => [ScheduleStyle::Standard],
            'wide' => [ScheduleStyle::Wide],
            'tall' => [ScheduleStyle::Tall],
            'condensed week' => [ScheduleStyle::CondensedWeek],
        ];
    }
}

/**
 * SchedulePage's constructor builds a large graph of repositories and services,
 * so it is deliberately not called here. Set() and GetVar() are overridden to
 * keep Smarty out of the test.
 */
class TestableSchedulePage extends SchedulePage
{
    /** @var array<string, mixed> */
    private array $assigned = [];

    public function __construct(bool $isMobile, bool $isTablet)
    {
        $this->IsMobile = $isMobile;
        $this->IsTablet = $isTablet;
        $this->server = ServiceLocator::GetServer();
    }

    public function Set(string $var, mixed $value): void
    {
        $this->assigned[$var] = $value;
    }

    public function AssignedValue(string $var): mixed
    {
        return $this->assigned[$var] ?? null;
    }

    public function EffectiveScheduleStyle(): ScheduleStyle
    {
        return $this->ScheduleStyle;
    }

    protected function GetVar($var)
    {
        return $var === 'ScheduleId' ? (string) SchedulePageTest::SCHEDULE_ID : '';
    }
}
