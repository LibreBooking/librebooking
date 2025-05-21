<?php

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
}
