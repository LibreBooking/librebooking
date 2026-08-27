<?php

/**
 * Base fixture for notification-preferences golden tests.
 * All checkbox vars default to false, EmailEnabled=true, PreferencesUpdated=false.
 */
return array_merge(require __DIR__ . '/myaccount-base.php', [
    'EmailEnabled' => true,
    'PreferencesUpdated' => false,
    'Created' => false,
    'Updated' => false,
    'Deleted' => false,
    'Approved' => false,
    'ParticipantChanged' => false,
    'SeriesEnding' => false,
]);
