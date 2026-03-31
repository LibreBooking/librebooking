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

    /**
     * @dataProvider numericalValidatorProvider
     */
    public function testNumericalValidator(string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::numericalValidator('uid', $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function numericalValidatorProvider(): array
    {
        return [
            'valid digits' => ['/Web/view-schedule.php?uid=123', true],
            'zero is valid' => ['/Web/view-schedule.php?uid=0', true],
            'trailing alpha rejected' => ['/Web/view-schedule.php?uid=123abc', false],
            'leading alpha rejected' => ['/Web/view-schedule.php?uid=abc123', false],
            'float rejected' => ['/Web/view-schedule.php?uid=1.5', false],
            'scientific notation rejected' => ['/Web/view-schedule.php?uid=1e10', false],
            'negative rejected' => ['/Web/view-schedule.php?uid=-1', false],
            'empty value rejected' => ['/Web/view-schedule.php?uid=', false],
            'missing param rejected' => ['/Web/view-schedule.php?other=123', false],
            'array param rejected' => ['/Web/view-schedule.php?uid[]=123', false],
            'repeated array param rejected' => ['/Web/view-schedule.php?uid[]=1&uid[]=2', false],
        ];
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
            'array param treated as absent' => ['dr', 'reservations', '/Web/view-schedule.php?dr[]=reservations', true],
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
            'array param rejected' => ['/Web/reservation.php?sd[]=2026-03-23+08%3A00', false],
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
            'array param rejected' => ['/Web/reservation.php?sd[]=2026-03-23+08%3A00%3A00', false],
        ];
    }

    /**
     * @dataProvider arrayParamRejectedProvider
     */
    public function testArrayParamRejectedAcrossValidators(string $validator, string $param, string $uri)
    {
        $result = match ($validator) {
            'existsInURL' => ParamsValidatorMethods::existsInURLValidator($param, $uri),
            'boolean' => ParamsValidatorMethods::booleanValidator($param, $uri),
            'date' => ParamsValidatorMethods::dateValidator($param, $uri),
            'simpleDateList' => ParamsValidatorMethods::simpleDateValidatorList($param, $uri),
            'optionalSimpleDate' => ParamsValidatorMethods::optionalSimpleDateValidator($param, $uri),
            'optionalNumeric' => ParamsValidatorMethods::optionalNumericValidator($param, $uri),
        };
        $this->assertFalse($result, "Array param should be rejected by $validator for URI: $uri");
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function arrayParamRejectedProvider(): array
    {
        return [
            'existsInURL rejects array' => ['existsInURL', 'sid', '/Web/reservation.php?sid[]=123'],
            'boolean rejects array' => ['boolean', 'show', '/Web/view-schedule.php?show[]=true'],
            'date rejects array' => ['date', 'sd', '/Web/reservation.php?sd[]=2026-03-23'],
            'simpleDateList rejects array' => ['simpleDateList', 'sd', '/Web/reservation.php?sd[]=2026-03-23'],
            'optionalSimpleDate rejects array' => ['optionalSimpleDate', 'sd', '/Web/schedule.php?sd[]='],
            'optionalNumeric rejects array' => ['optionalNumeric', 'uid', '/Web/view-schedule.php?uid[]=123'],
        ];
    }

    /**
     * @dataProvider optionalSimpleDateProvider
     */
    public function testOptionalSimpleDateValidator(string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::optionalSimpleDateValidator('sds', $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function optionalSimpleDateProvider(): array
    {
        return [
            'empty string is valid' => ['/Web/schedule.php?sds=', true],
            'single date is valid' => ['/Web/schedule.php?sds=2026-03-31', true],
            'date list is valid' => ['/Web/schedule.php?sds=2026-03-31,2026-04-01', true],
            'missing param rejected' => ['/Web/schedule.php?sd=2026-03-31', false],
            'invalid date rejected' => ['/Web/schedule.php?sds=bad-date', false],
            'array param rejected' => ['/Web/schedule.php?sds[]=', false],
            'xss rejected' => ['/Web/schedule.php?sds=&x=%3Cscript%3E', false],
        ];
    }

    /**
     * @dataProvider optionalNumericProvider
     */
    public function testOptionalNumericValidator(string $uri, bool $expected)
    {
        $result = ParamsValidatorMethods::optionalNumericValidator('uid', $uri);
        $this->assertSame($expected, $result, "Failed for URI: $uri");
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function optionalNumericProvider(): array
    {
        return [
            'valid digits' => ['/Web/schedule.php?uid=123', true],
            'zero is valid' => ['/Web/schedule.php?uid=0', true],
            'empty string is valid' => ['/Web/schedule.php?uid=', true],
            'trailing alpha rejected' => ['/Web/schedule.php?uid=123abc', false],
            'leading alpha rejected' => ['/Web/schedule.php?uid=abc123', false],
            'alpha rejected' => ['/Web/schedule.php?uid=abc', false],
            'float rejected' => ['/Web/schedule.php?uid=1.5', false],
            'negative rejected' => ['/Web/schedule.php?uid=-1', false],
            'missing param rejected' => ['/Web/schedule.php?other=123', false],
            'array param rejected' => ['/Web/schedule.php?uid[]=123', false],
            'xss script tag rejected' => ['/Web/schedule.php?uid=123&x=%3Cscript%3E', false],
            'xss double quotes rejected' => ['/Web/schedule.php?uid=123&x=%22alert%22', false],
        ];
    }

    /**
     * Regression test: schedule filter with empty optional params must not fail validation.
     */
    public function testScheduleFilterWithEmptyOptionalParamsPassesValidation()
    {
        $requestURI = '/Web/schedule.php?RESOURCE_TYPE_ID=&maxParticipants=&sid=1&sd=2026-03-31&sds=&SUBMIT=true&clearFilter=0';
        $result = ParamsValidator::validate(
            params: RouteParamsKeys::VIEW_SCHEDULE,
            requestURI: $requestURI,
            optional: true,
        );
        $this->assertTrue($result, 'Schedule filter with empty optional params should pass validation');
    }

    /**
     * Schedule filter with valid numeric optional params must pass validation.
     */
    public function testScheduleFilterWithNumericOptionalParamsPassesValidation()
    {
        $requestURI = '/Web/schedule.php?RESOURCE_TYPE_ID=5&maxParticipants=10&sid=1&sd=2026-03-31&sds=2026-03-31&SUBMIT=true&clearFilter=0';
        $result = ParamsValidator::validate(
            params: RouteParamsKeys::VIEW_SCHEDULE,
            requestURI: $requestURI,
            optional: true,
        );
        $this->assertTrue($result, 'Schedule filter with numeric optional params should pass validation');
    }

    /**
     * Schedule filter with non-numeric optional params must fail validation.
     */
    public function testScheduleFilterWithInvalidOptionalParamsFailsValidation()
    {
        $requestURI = '/Web/schedule.php?RESOURCE_TYPE_ID=abc&sid=1&sd=2026-03-31&sds=2026-03-31&SUBMIT=true&clearFilter=0';
        $result = ParamsValidator::validate(
            params: RouteParamsKeys::VIEW_SCHEDULE,
            requestURI: $requestURI,
            optional: true,
        );
        $this->assertFalse($result, 'Schedule filter with non-numeric RESOURCE_TYPE_ID should fail validation');
    }
}
