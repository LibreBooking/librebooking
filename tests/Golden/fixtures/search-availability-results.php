<?php

/**
 * Base fixture for search-availability-results golden tests.
 * ResourceDto, TimeInterval, ResourceStatus, AvailableOpeningView available via namespace.php +
 * Presenters/SearchAvailabilityPresenter.php.
 */
$resource = new ResourceDto(
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

$resourceWithColor = new ResourceDto(
    2,
    'Conference B',
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
    '#ff0000',
    null
);

$start1 = Date::Parse('2025-01-15 10:00:00', 'UTC');
$end1 = Date::Parse('2025-01-15 11:00:00', 'UTC');   // SameDate() = true
$start2 = Date::Parse('2025-01-15 14:00:00', 'UTC');
$end2 = Date::Parse('2025-01-16 09:00:00', 'UTC');   // SameDate() = false

return [
    'Openings' => [
        new AvailableOpeningView($resource, $start1, $end1),
        new AvailableOpeningView($resourceWithColor, $start2, $end2),
    ],
];
