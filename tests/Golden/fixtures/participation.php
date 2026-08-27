<?php

/**
 * Base fixture for participation golden tests.
 */
return array_merge(require __DIR__ . '/myaccount-base.php', [
    'Reservations' => [],
    'Timezone' => 'America/New_York',
]);
