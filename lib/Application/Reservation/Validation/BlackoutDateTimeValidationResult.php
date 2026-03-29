<?php

class BlackoutDateTimeValidationResult implements IBlackoutValidationResult
{
    private string $messageKey;

    public function __construct(string $messageKey = 'StartDateBeforeEndDateRule')
    {
        $this->messageKey = $messageKey;
    }

    /**
     * @return bool
     */
    public function WasSuccessful()
    {
        return false;
    }

    /**
     * @return string
     */
    public function Message()
    {
        return Resources::GetInstance()->GetString($this->messageKey);
    }

    /**
     * @return array|ReservationItemView[]
     */
    public function ConflictingReservations()
    {
        return [];
    }

    /**
     * @return array|BlackoutItemView[]
     */
    public function ConflictingBlackouts()
    {
        return [];
    }
}

class BlackoutSecurityValidationResult implements IBlackoutValidationResult
{
    /**
     * @return bool
     */
    public function WasSuccessful()
    {
        return false;
    }

    /**
     * @return string
     */
    public function Message()
    {
        return Resources::GetInstance()->GetString('NoResourcePermission');
    }

    /**
     * @return array|ReservationItemView[]
     */
    public function ConflictingReservations()
    {
        return [];
    }

    /**
     * @return array|BlackoutItemView[]
     */
    public function ConflictingBlackouts()
    {
        return [];
    }
}
