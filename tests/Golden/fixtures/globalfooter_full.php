<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

return [
    'CompanyName' => 'Full Test Corp',
    'CompanyUrl' => 'https://fulltest.example.com',
    'DisplayVersion' => '2.0.0',
    'LoggedIn' => true,
    'AvailableLanguages' => [
        new AvailableLanguage('en_us', 'en_us.php', 'English US'),
        new AvailableLanguage('fr_fr', 'fr_fr.php', 'Français'),
        new AvailableLanguage('de_de', 'de_de.php', 'Deutsch'),
    ],
    'CSRFToken' => 'full-test-csrf-token',
    'Path' => '/web/',
    'GoogleAnalyticsTrackingId' => 'G-TESTTRACKING123',
];
