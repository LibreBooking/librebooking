<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class ReservationValidationRuleProcessorTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testValidatesAgainstAllRules()
    {
        $reservation = new ExistingReservationSeries();
        $validResult = new ReservationRuleResult(true);

        $rule1 = $this->createMock('IUpdateReservationValidationRule');
        $rule2 = $this->createMock('IUpdateReservationValidationRule');
        $rule3 = $this->createMock('IUpdateReservationValidationRule');

        $rule1->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($reservation))
            ->willReturn($validResult);

        $rule2->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($reservation))
            ->willReturn($validResult);

        $rule3->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($reservation))
            ->willReturn($validResult);

        $rules = [$rule1, $rule2, $rule3];

        $vs = new ReservationValidationRuleProcessor($rules);
        $result = $vs->Validate($reservation);

        $this->assertEquals(true, $result->CanBeSaved());
        $this->assertEquals(0, count($result->GetErrors()));
        $this->assertEquals(0, count($result->GetWarnings()));
    }

    public function testValidatesStopsAfterFirstBrokenRule()
    {
        $reservation = new ExistingReservationSeries();

        $rule1 = $this->createMock('IUpdateReservationValidationRule');
        $rule2 = $this->createMock('IUpdateReservationValidationRule');
        $rule3 = $this->createMock('IUpdateReservationValidationRule');

        $rules = [$rule1, $rule2, $rule3];

        $rule1->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($reservation))
            ->willReturn(new ReservationRuleResult());

        $error = 'something went wrong';
        $retryMessage = 'retry message';
        $retryParams = [new ReservationRetryParameter('n', 'v')];

        $rule2->expects($this->once())
            ->method('Validate')
            ->with($this->equalTo($reservation))
            ->willReturn(new ReservationRuleResult(false, $error, true, $retryMessage, $retryParams));

        $vs = new ReservationValidationRuleProcessor($rules);

        $result = $vs->Validate($reservation);

        $this->assertEquals(false, $result->CanBeSaved());
        $actualErrors = $result->GetErrors();
        $this->assertEquals($error, $actualErrors[0]);
        $this->assertEquals(0, count($result->GetWarnings()));
        $this->assertEquals(true, $result->CanBeRetried());
        $this->assertEquals([$retryMessage], $result->GetRetryMessages());
        $this->assertEquals($retryParams, $result->GetRetryParameters());
    }
}
