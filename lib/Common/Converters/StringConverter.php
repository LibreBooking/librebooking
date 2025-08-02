<?php

class StringConverter implements IConvert
{
    public function Convert(mixed $value): string
    {
        return trim((string) $value);
    }

    public function IsValid(mixed $value): bool
    {
        return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
    }
}
