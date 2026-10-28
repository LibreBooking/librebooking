<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once ROOT_DIR . 'lang/hu_hu.php';

class HungarianLocaleTest extends TestCase
{
    public function testTranslationSubstitutionsMatchEnglish(): void
    {
        $english = new en_us();
        $hungarian = new hu_hu();

        foreach ($english->Strings as $key => $value) {
            $this->assertArrayHasKey($key, $hungarian->Strings);
            preg_match_all('/%(?:[0-9]+\$)?[-+0-9.]*[sdfu]/', $value, $expected);
            preg_match_all('/%(?:[0-9]+\$)?[-+0-9.]*[sdfu]/', $hungarian->Strings[$key], $actual);
            $this->assertSame($expected[0], $actual[0], 'Substitution mismatch: ' . $key);

            preg_match_all('/_[A-Z]+_/', $value, $expected);
            preg_match_all('/_[A-Z]+_/', $hungarian->Strings[$key], $actual);
            $this->assertSame($expected[0], $actual[0], 'Table substitution mismatch: ' . $key);
        }
    }

    public function testHungarianDateRoundTripsAndUsesTwentyFourHourTime(): void
    {
        $locale = new hu_hu();
        $date = new DateTimeImmutable('2026-10-18 23:05:00', new DateTimeZone('Europe/Budapest'));
        $display = $date->format($locale->Dates['general_date']);

        $this->assertSame('2026. 10. 18.', $display);
        $parsed = DateTimeImmutable::createFromFormat('!' . $locale->Dates['general_date'], $display);
        $this->assertInstanceOf(DateTimeImmutable::class, $parsed);
        $this->assertSame('2026-10-18', $parsed->format('Y-m-d'));

        foreach (['period_time', 'res_popup_time', 'embedded_time'] as $key) {
            $this->assertSame('23:05', $date->format($locale->Dates[$key]), $key);
        }

        $this->assertSame('g:i A', (new en_us())->Dates['period_time']);
    }

    public function testCalendarDaysAreDistinctAndJulyIsSpelledCorrectly(): void
    {
        $locale = new hu_hu();

        foreach ($locale->Days as $names) {
            $this->assertCount(7, $names);
            $this->assertCount(7, array_unique($names));
        }

        $this->assertCount(12, $locale->Months['full']);
        $this->assertSame('július', $locale->Months['full'][6]);
    }

    public function testBookingActionAndOccupiedStateHaveDifferentLabels(): void
    {
        $locale = new hu_hu();

        $this->assertSame('Foglalás', $locale->Strings['Reserve']);
        $this->assertSame('Foglalt', $locale->Strings['Reserved']);
    }
}
