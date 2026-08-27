<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

return [
    'CompanyName' => 'Test Corp',
    'CompanyUrl' => 'https://example.com',
    'DisplayVersion' => '1.0.0',
    'LoggedIn' => true,
    'AvailableLanguages' => [
        new AvailableLanguage('en_us', 'en_us.php', 'English US'),
        new AvailableLanguage('fr_fr', 'fr_fr.php', 'Français'),
    ],
    'CSRFToken' => 'test-csrf-token',
    'Path' => '/web/',
    'GoogleAnalyticsTrackingId' => '',
];
