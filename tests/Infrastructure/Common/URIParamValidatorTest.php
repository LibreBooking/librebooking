<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class URIParamValidatorTest extends TestBase
{
    public function testValidParamsPassValidation()
    {
        $requestURI = '/Web/view-schedule.php?uid=123&sd=2025-05-28';
        $params = [
            'uid' => ['n'], // numerical
            'sd' => ['d'],  // date
        ];
        $result = ParamsValidator::validate($params, $requestURI, false);
        $this->assertTrue($result);
    }

    public function testInvalidNumericalFailsValidation()
    {
        $requestURI = '/Web/view-schedule.php?uid=abc';
        $params = [
            'uid' => ['n'], // should be numeric
        ];
        $result = ParamsValidator::validate($params, $requestURI, false);
        $this->assertFalse($result);
    }

    public function testMissingOptionalParamsSkipsValidation()
    {
        $requestURI = '/Web/view-schedule.php';
        $params = [
            'uid' => ['n'],
        ];
        $result = ParamsValidator::validate($params, $requestURI, true);
        $this->assertTrue($result); // Should skip validation due to 'optional' flag
    }

    public function testMissingRequiredParamsFails()
    {
        $requestURI = '/Web/view-schedule.php';
        $params = [
            'uid' => ['n'],
        ];
        $result = ParamsValidator::validate($params, $requestURI, false);
        $this->assertFalse($result); // Should fail due to missing required param and optional = false
    }

    public function testMatchValidationPasses()
    {
        $requestURI = '/Web/view-schedule.php?dr=reservations';
        $params = [
            'dr' => [['reservations']], // match validator
        ];
        $result = ParamsValidator::validate($params, $requestURI, false);
        $this->assertTrue($result);
    }

    public function testMatchValidationFails()
    {
        $requestURI = '/Web/view-schedule.php?dr=wrongvalue';
        $params = [
            'dr' => [['reservations']], // match validator
        ];
        $result = ParamsValidator::validate($params, $requestURI, false);
        $this->assertFalse($result);
    }

    public function testExistsInURLValidatorPassesWhenParamHasValue()
    {
        $result = ParamsValidatorMethods::existsInURLValidator('sid', '/Web/reservation.php?sid=123&rid=456');
        $this->assertTrue($result);
    }

    public function testExistsInURLValidatorFailsWhenParamIsEmpty()
    {
        $result = ParamsValidatorMethods::existsInURLValidator('sid', '/Web/reservation.php?sid=&rid=456');
        $this->assertFalse($result);
    }

    public function testExistsInURLValidatorFailsWhenParamIsMissing()
    {
        $result = ParamsValidatorMethods::existsInURLValidator('sid', '/Web/reservation.php?rid=456');
        $this->assertFalse($result);
    }

    /**
     * @dataProvider matchValidatorProvider
     */
    public function testMatchValidator(string $param, string $expectedValue, string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::matchValidator($param, $expectedValue, $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, string, string, bool}>
     */
    public static function matchValidatorProvider(): array
    {
        return [
            'param matches expected value' => ['dr', 'reservations', '/Web/view-schedule.php?dr=reservations', true],
            'param does not match expected value' => ['dr', 'reservations', '/Web/view-schedule.php?dr=wrongvalue', false],
            'param absent returns true' => ['dr', 'reservations', '/Web/view-schedule.php?other=value', true],
            'param empty does not match' => ['dr', 'reservations', '/Web/view-schedule.php?dr=', false],
            'param with multiple query params' => ['dr', 'reservations', '/Web/view-schedule.php?uid=123&dr=reservations&sd=2026-01-01', true],
            'param wrong value with multiple query params' => ['dr', 'reservations', '/Web/view-schedule.php?uid=123&dr=wrong&sd=2026-01-01', false],
            'xss script tag rejected' => ['dr', 'reservations', '/Web/view-schedule.php?dr=reservations&x=%3Cscript%3E', false],
            'xss script tag rejected even when param absent' => ['dr', 'reservations', '/Web/view-schedule.php?x=%3Cscript%3E', false],
            'xss double quotes rejected' => ['dr', 'reservations', '/Web/view-schedule.php?dr=reservations&x=%22alert%22', false],
        ];
    }

    /**
     * @dataProvider simpleDateTimeProvider
     */
    public function testSimpleDateTimeValidator(string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::simpleDateTimeValidator('sd', $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function simpleDateTimeProvider(): array
    {
        return [
            'zero-padded morning hour' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 08:00'), true],
            'zero-padded 9am' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 09:30'), true],
            'unpadded single-digit hour' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 8:00'), false],
            'midnight' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 00:00'), true],
            'noon' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 12:00'), true],
            'last minute of day' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 23:59'), true],
            'hour 20' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 20:00'), true],
            'invalid hour 24' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 24:00'), false],
            'invalid hour 25' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 25:00'), false],
            'invalid minute 60' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 10:60'), false],
            'invalid month 13' => ['/Web/reservation.php?sd=' . urlencode('2026-13-23 10:00'), false],
            'invalid day 32' => ['/Web/reservation.php?sd=' . urlencode('2026-03-32 10:00'), false],
            'missing param' => ['/Web/reservation.php?other=value', false],
            'empty param' => ['/Web/reservation.php?sd=', false],
            'with seconds rejected' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 08:00:00'), false],
        ];
    }

    /**
     * @dataProvider complexDateTimeProvider
     */
    public function testComplexDateTimedateValidator(string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::complexDateTimedateValidator('sd', $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function complexDateTimeProvider(): array
    {
        return [
            'zero-padded morning hour' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 08:00:00'), true],
            'zero-padded 9am' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 09:30:00'), true],
            'unpadded single-digit hour' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 8:00:00'), false],
            'midnight' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 00:00:00'), true],
            'noon' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 12:00:00'), true],
            'last second of day' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 23:59:59'), true],
            'hour 20' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 20:00:00'), true],
            'invalid hour 24' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 24:00:00'), false],
            'invalid hour 25' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 25:00:00'), false],
            'invalid minute 60' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 10:60:00'), false],
            'invalid second 60' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 10:00:60'), false],
            'invalid month 13' => ['/Web/reservation.php?sd=' . urlencode('2026-13-23 10:00:00'), false],
            'invalid day 32' => ['/Web/reservation.php?sd=' . urlencode('2026-03-32 10:00:00'), false],
            'missing param' => ['/Web/reservation.php?other=value', false],
            'empty param' => ['/Web/reservation.php?sd=', false],
            'without seconds rejected' => ['/Web/reservation.php?sd=' . urlencode('2026-03-23 08:00'), false],
        ];
    }
}
