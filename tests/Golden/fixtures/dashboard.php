<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

$base = require __DIR__ . '/root-pages-base.php';

return array_merge($base, [
    // Use a logged-in state since dashboard is a logged-in page.
    'LoggedIn' => true,
    'AvailableLanguages' => [
        new AvailableLanguage('en_us', 'en_us.php', 'English US'),
    ],
    'CurrentLanguage' => 'en_us',

    // ── dashboard.twig requirements ──────────────────────────────────────
    // DashboardItem objects require real DB/session state, so we use an empty
    // array for the golden test (the items loop renders nothing with []).
    'items' => [],

    'ScriptUrl' => 'http://localhost/web',
]);
