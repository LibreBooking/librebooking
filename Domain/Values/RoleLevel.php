<?php

class RoleLevel
{
    private function __construct()
    {
    }

    public const NONE = null;
    public const GROUP_ADMIN = 1;
    public const APPLICATION_ADMIN = 2;
    public const RESOURCE_ADMIN = 3;
    public const SCHEDULE_ADMIN = 4;

    /**
     * @return int[] RoleLevel constants
     */
    public static function All(): array
    {
        return [
            RoleLevel::GROUP_ADMIN,
            RoleLevel::APPLICATION_ADMIN,
            RoleLevel::RESOURCE_ADMIN,
            RoleLevel::SCHEDULE_ADMIN
        ];
    }
}
