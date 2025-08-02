<?php

class IntConverter implements IConvert
{
    public function Convert($value)
    {
        return intval($value);
    }

    public function IsValid($value): bool
    {
        return is_numeric($value) && intval($value) == $value;
    }
}
