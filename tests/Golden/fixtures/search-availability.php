<?php

/**
 * Base fixture for search-availability golden tests.
 * ResourceDto and TimeInterval are available via namespace.php.
 */
$resource1 = new ResourceDto(
    1,
    'Room A',
    true,
    true,
    1,
    TimeInterval::None(),
    null,
    null,
    null,
    ResourceStatus::AVAILABLE,
    false,
    false,
    false,
    null,
    null,
    null
);

$today = Date::Parse('2025-01-15 09:00:00', 'UTC');

return [
    'Path' => '/booking/',
    'Resources' => [$resource1],
    'ResourceTypes' => [],
    'ResourceAttributes' => [],
    'ResourceTypeAttributes' => [],
    'ResourceTypeIdFilter' => '',
    'MaxParticipantsFilter' => '',
    'TimeFormat' => 'h:mm tt',
    'Today' => $today,
    'Tomorrow' => Date::Parse('2025-01-16 09:00:00', 'UTC'),
];
