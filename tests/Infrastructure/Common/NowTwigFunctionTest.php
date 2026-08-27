<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Parity tests for the now() Twig function introduced in LibreBookingExtension.
 *
 * Asserts that {{ now().Format('H:00') }} and {{ now().AddHours(1).Format('H:00') }}
 * rendered via Twig match the values produced by Date::Now()->Format('H:00') and
 * Date::Now()->AddHours(1)->Format('H:00') in the same PHP process (same hour →
 * same output), faithfully reproducing the inline {Date::Now()...} Smarty expression.
 *
 * Extends TestBase so that Date::_SetNow is pinned in setUp() and ServiceLocator /
 * Resources are wired consistently with the rest of the suite.
 */
class NowTwigFunctionTest extends TestBase
{
    private function makeEnv(string $template): \Twig\Environment
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        return $env;
    }

    /**
     * Reset Date::$_Now to null via reflection so the live clock is used after
     * a test pins a fixed date.
     */
    private function resetDateNow(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);
    }

    /** now() returns a Date instance whose formatted value matches the expected hour. */
    public function testNowReturnsDateObject(): void
    {
        $fixed = Date::Parse('2025-06-15 14:30:00', 'UTC');
        Date::_SetNow($fixed);

        try {
            $env = $this->makeEnv('{% set d = now() %}{{ d.Format("Y-m-d H:i") }}');
            $actual = $env->render('t');
            $this->assertSame('2025-06-15 14:30', $actual);
        } finally {
            $this->resetDateNow();
        }
    }

    /**
     * Parity: {{ now().Format('H:00') }} equals Date::Now()->Format('H:00').
     *
     * Both are evaluated in the same process with the same fixed clock, so
     * same-hour determinism is guaranteed — faithfully reproducing
     * the Smarty inline {Date::Now()->format('H:00')} used in
     * tpl/SearchAvailability/search-availability.tpl.
     */
    public function testNowFormatHourParityWithDateNow(): void
    {
        $fixed = Date::Parse('2025-06-15 09:15:00', 'UTC');
        Date::_SetNow($fixed);

        try {
            $smartyValue = Date::Now()->Format('H:00');

            $env = $this->makeEnv("{{ now().Format('H:00') }}");
            $twigValue = $env->render('t');

            $this->assertSame($smartyValue, $twigValue);
            $this->assertSame('09:00', $twigValue);
        } finally {
            $this->resetDateNow();
        }
    }

    /**
     * Parity: {{ now().AddHours(1).Format('H:00') }} equals
     * Date::Now()->AddHours(1)->Format('H:00') — used for the end-time picker default
     * in tpl/SearchAvailability/search-availability.tpl.
     */
    public function testNowAddHoursParityWithDateNow(): void
    {
        $fixed = Date::Parse('2025-06-15 09:15:00', 'UTC');
        Date::_SetNow($fixed);

        try {
            $smartyValue = Date::Now()->AddHours(1)->Format('H:00');

            $env = $this->makeEnv("{{ now().AddHours(1).Format('H:00') }}");
            $twigValue = $env->render('t');

            $this->assertSame($smartyValue, $twigValue);
            $this->assertSame('10:00', $twigValue);
        } finally {
            $this->resetDateNow();
        }
    }
}
