<?php

/**
 * Base fixture for search-reservations-results golden tests.
 * Domain/ReservationItemView.php is included via namespace.php.
 */
$startDate = Date::Parse('2025-01-15 10:00:00', 'UTC');
$endDate = Date::Parse('2025-01-15 11:00:00', 'UTC');
$createdDate = Date::Parse('2025-01-10 08:00:00', 'UTC');
$modifiedDate = Date::Parse('2025-01-12 09:00:00', 'UTC');

$reservation = new ReservationItemView(
    'ABC-123',          // referenceNumber
    $startDate,         // startDate
    $endDate,           // endDate
    'Conference Room A', // resourceName
    1,                  // resourceId
    101,                // reservationId
    null,               // userLevelId
    'Team Meeting',     // title
    'Weekly sync',      // description
    1,                  // scheduleId
    'John',             // userFirstName
    'Doe',              // userLastName
    42,                 // userId
);
$reservation->SeriesId = 201;
$reservation->CreatedDate = $createdDate;
$reservation->ModifiedDate = $modifiedDate;
$reservation->RequiresApproval = false;

return [
    'Reservations' => [$reservation],
    'Timezone' => 'UTC',
    'UserId' => 42,
];
