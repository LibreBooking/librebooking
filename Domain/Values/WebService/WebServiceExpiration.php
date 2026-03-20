<?php

class WebServiceExpiration
{
    /**
     * @param string $expirationTime
     * @return bool
     */
    public static function IsExpired(string $expirationTime): bool
    {
        return Date::Parse($expirationTime, 'UTC')->LessThan(Date::Now());
    }

    /**
     * @return string
     */
    public static function Create(): string
    {
        $minutes = Configuration::Instance()->GetKey(ConfigKeys::INACTIVITY_TIMEOUT, new IntConverter());
        if ($minutes <= 0) {
            Log::Error('Invalid inactivity.timeout value: %d. Falling back to default: %d', $minutes, ConfigKeys::INACTIVITY_TIMEOUT['default']);
            $minutes = ConfigKeys::INACTIVITY_TIMEOUT['default'];
        }

        return Date::Now()->AddMinutes($minutes)->ToUtc()->ToIso();
    }
}
