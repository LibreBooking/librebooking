<?php

interface IConvert
{
    /**
     * Converts a value to a specific type.
     *
     * @param mixed $value The value to convert.
     * @return mixed The converted value.
     */
    public function Convert($value);

    /**
     * Checks if the value is valid for conversion.
     *
     * @param mixed $value The value to check.
     * @return bool True if valid, false otherwise.
     */
    public function IsValid($value);
}
