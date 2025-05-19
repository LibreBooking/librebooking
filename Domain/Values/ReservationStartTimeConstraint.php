<?php

class ReservationStartTimeConstraint
{
    public const _DEFAULT = 'future';
    public const FUTURE = 'future';
    public const CURRENT = 'current';
    public const NONE = 'none';

    /**
     * @static
     * @param string $startTimeConstraint
     * @return bool
     */
    public static function IsCurrent(string|null $startTimeConstraint)
    {
        return strtolower($startTimeConstraint ?? "") == self::CURRENT;
    }

    /**
     * @static
     * @param string $startTimeConstraint
     * @return bool
     */
    public static function IsNone(string|null $startTimeConstraint)
    {
        return strtolower($startTimeConstraint ?? "") == self::NONE;
    }

    /**
     * @static
     * @param string $startTimeConstraint
     * @return bool
     */
    public static function IsFuture(string|null $startTimeConstraint)
    {
        return strtolower($startTimeConstraint ?? "") == self::FUTURE;
    }
}
