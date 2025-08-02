<?php

class BooleanConverter implements IConvert
{
    public function Convert($value)
    {
        return self::ConvertValue($value);
    }

    public function IsValid($value): bool
    {
        if (is_null($value)) {
            return false;
        }

        if (is_bool($value) || $value === 0 || $value === 1) {
            return true;
        }

        $stringValue = strtolower((string) $value);
        return in_array($stringValue, ['true', 'false', '1', '0', ''], true);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function ConvertValue($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_null($value)) {
            return false;
        }

        $stringValue = strtolower((string) $value);
        return $stringValue === 'true' || $stringValue === '1' || $value === 1;
    }
}
