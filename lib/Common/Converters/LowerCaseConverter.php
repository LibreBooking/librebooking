<?php

class LowerCaseConverter implements IConvert
{
    public function Convert(mixed $value): string
    {
        return strtolower((string) ($value ?? ''));
    }

    public function IsValid(mixed $value): bool
    {
        return is_string($value);
    }
}
