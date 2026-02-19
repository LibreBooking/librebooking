<?php

declare(strict_types=1);

class TestDateRange extends DateRange
{
    public function __construct()
    {
        parent::__construct(Date::Now(), Date::Now());
    }

    public static function CreateWithDays($days)
    {
        $now = Date::Now();
        return new DateRange($now->AddDays($days), $now->AddDays($days));
    }
}
