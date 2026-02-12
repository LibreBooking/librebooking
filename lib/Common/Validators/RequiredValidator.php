<?php

class RequiredValidator extends ValidatorBase implements IValidator
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function Validate()
    {
        $trimmed = trim((string) ($this->value ?? ''));
        $this->isValid = ($trimmed !== '');
    }
}
