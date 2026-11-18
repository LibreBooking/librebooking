<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class ValidatorTest extends TestBase
{
    public function testValidatorsAreEvaluatedWhenRegistered()
    {
        $validator = new FakeValidator();
        $validator->_IsValid = true;
        $validators = new PageValidators();

        $validators->Register('someid', $validator);
        $validators->Validate();

        $this->assertTrue($validator->_WasValidated, 'should have been validated when registered');
        $this->assertTrue($validators->AreAllValid());
    }

    public function testFailedValidatorsAreKeptByTheirId(): void
    {
        $passing = new FakeValidator();
        $passing->_IsValid = true;
        $failing = new FakeValidator();
        $failing->_IsValid = false;
        $alsoFailing = new FakeValidator();
        $alsoFailing->_IsValid = false;

        $validators = new PageValidators();
        $validators->Register('passing', $passing);
        $validators->Register('failing', $failing);
        $validators->Register('alsoFailing', $alsoFailing);
        $validators->Validate();

        $this->assertFalse($validators->AreAllValid());
        $this->assertSame(['failing' => $failing, 'alsoFailing' => $alsoFailing], $validators->GetFailed());
    }

    public function testNoValidatorHasFailedBeforeValidation(): void
    {
        $failing = new FakeValidator();
        $failing->_IsValid = false;

        $validators = new PageValidators();
        $validators->Register('failing', $failing);

        $this->assertSame([], $validators->GetFailed());
    }

    public function testNoValidatorHasFailedWhenAllPass(): void
    {
        $passing = new FakeValidator();
        $passing->_IsValid = true;

        $validators = new PageValidators();
        $validators->Register('passing', $passing);
        $validators->Validate();

        $this->assertSame([], $validators->GetFailed());
    }

    public function testPasswordComplexity()
    {
        $regex = '/^[^\s ]{6,}$/i';

        $valid1 = new RegexValidator('$password$_+123', $regex);
        $valid2 = new RegexValidator('pas123', $regex);

        $invalid1 = new RegexValidator('passw', $regex);
        $invalid2 = new RegexValidator('password123 123', $regex);
        $invalid3 = new RegexValidator('', $regex);

        $valid1->Validate();
        $valid2->Validate();
        $invalid1->Validate();
        $invalid2->Validate();
        $invalid3->Validate();

        $this->assertTrue($valid1->IsValid());
        $this->assertTrue($valid2->IsValid());
        $this->assertFalse($invalid1->IsValid());
        $this->assertFalse($invalid2->IsValid(), 'spaces are not allowed');
        $this->assertFalse($invalid3->IsValid(), 'password is required are not allowed');
    }
}
