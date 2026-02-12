<?php

class IntConverter implements IConvert
{
    public function Convert(mixed $value): int
    {
        return intval($value);
    }

    public function IsValid(mixed $value): bool
    {
        return is_numeric($value) && intval($value) == $value;
    }
}
