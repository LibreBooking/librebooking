<?php

class ErrorMessages
{
    public const UNKNOWN_ERROR = 0;
    public const INSUFFICIENT_PERMISSIONS = 1;
    public const MISSING_RESOURCE = 2;
    public const MISSING_SCHEDULE = 3;
    public const RESERVATION_NOT_FOUND = 4;
    public const RESERVATION_NOT_AVAILABLE = 5;
    public const DATABASE_CONNECTION = 6;
    public const DATABASE_NOT_FOUND = 7;

    private $_resourceKeys = [];
    private static $_instance;

    private function __construct()
    {
        $this->SetKey(ErrorMessages::INSUFFICIENT_PERMISSIONS, 'InsufficientPermissionsError');
        $this->SetKey(ErrorMessages::MISSING_RESOURCE, 'MissingReservationResourceError');
        $this->SetKey(ErrorMessages::MISSING_SCHEDULE, 'MissingReservationScheduleError');
        $this->SetKey(ErrorMessages::RESERVATION_NOT_FOUND, 'ReservationNotFoundError');
        $this->SetKey(ErrorMessages::RESERVATION_NOT_AVAILABLE, 'ReservationNotAvailable');
        $this->SetKey(ErrorMessages::DATABASE_CONNECTION, 'DatabaseConnectionError');
        $this->SetKey(ErrorMessages::DATABASE_NOT_FOUND, 'DatabaseNotFoundError');
    }

    /**
     * @static
     * @return ErrorMessages
     */
    public static function Instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new ErrorMessages();
        }

        return self::$_instance;
    }

    private function SetKey($errorMessageId, $resourceKey)
    {
        $this->_resourceKeys[$errorMessageId] = $resourceKey;
    }

    public function GetResourceKey($errorMessageId)
    {
        if (!isset($this->_resourceKeys[$errorMessageId])) {
            return 'UnknownError';
        }

        return $this->_resourceKeys[$errorMessageId];
    }
}
