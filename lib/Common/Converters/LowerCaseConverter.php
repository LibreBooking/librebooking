<?php

class LowerCaseConverter implements IConvert
{
    public function Convert($value)
    {
        return strtolower($value);
    }

    public function IsValid($value): bool
    {
        return is_string($value);
    }
}
