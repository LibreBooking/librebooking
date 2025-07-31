<?php

foreach (DateTimeZone::listIdentifiers() as $tz) {
    AddTimezone($tz);
}

function AddTimezone($timezoneName)
{
    $GLOBALS['APP_TIMEZONES'][] = $timezoneName;
}
