<?php

$base = require __DIR__ . '/root-pages-base.php';

return array_merge($base, [
    // ── register.twig requirements ──────────────────────────────────────
    // Feature flags
    'EnableCaptcha' => false,
    'RequirePhone' => false,
    'RequirePosition' => false,
    'RequireOrganization' => false,
    'HidePhone' => false,
    'HidePosition' => false,
    'HideOrganization' => false,

    // Timezone selector: small representative set for deterministic output.
    'TimezoneValues' => ['America/New_York', 'America/Chicago', 'America/Los_Angeles'],
    'TimezoneOutput' => ['America/New_York', 'America/Chicago', 'America/Los_Angeles'],
    'Timezone' => 'America/New_York',

    // Homepage selector: small representative set.
    'HomepageValues' => [1, 2, 3],
    'HomepageOutput' => ['Dashboard', 'Schedule', 'MyCalendar'],
    'Homepage' => 1,

    // Custom attributes (empty = no attribute controls rendered).
    'Attributes' => [],

    // Terms of service (null = no ToS checkbox rendered).
    'Terms' => null,
]);
