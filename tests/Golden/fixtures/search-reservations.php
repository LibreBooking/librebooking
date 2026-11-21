<?php

/**
 * Base fixture for search-reservations golden tests.
 * GoldenTemplateTestCase loads namespace.php, which includes Date and other domain classes.
 */
return [
    'Path' => '/booking/',
    'UserNameFilter' => 'John Doe (john@example.com)',
    'UserIdFilter' => 42,
    'Resources' => [],
    'Schedules' => [],
    'Today' => Date::Parse('2025-01-15 09:00:00', 'UTC'),
    'Tomorrow' => Date::Parse('2025-01-16 09:00:00', 'UTC'),
    'BeginDate' => Date::Parse('2025-01-15 00:00:00', 'UTC'),
    'EndDate' => Date::Parse('2025-01-22 00:00:00', 'UTC'),
    'UserId' => 42,
];
