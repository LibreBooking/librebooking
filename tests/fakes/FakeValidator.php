<?php

declare(strict_types=1);

class FakeValidator extends ValidatorBase implements IValidator
{
    public $_WasValidated = false;
    public $_IsValid = false;

    public function Validate()
    {
        $this->_WasValidated = true;
    }

    public function IsValid()
    {
        return $this->_IsValid;
    }
}
