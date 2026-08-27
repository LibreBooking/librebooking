<?php

/**
 * Base fixture for profile golden tests.
 * Values are free of HTML-special characters so Smarty and Twig produce
 * byte-identical output after HtmlNormalizer normalization.
 */
return array_merge(require __DIR__ . '/myaccount-base.php', [
    'AllowUsernameChange' => true,
    'AllowEmailAddressChange' => true,
    'AllowNameChange' => true,
    'AllowPhoneChange' => true,
    'AllowOrganizationChange' => true,
    'AllowPositionChange' => true,
    'Username' => 'testuser',
    'Email' => 'test@example.com',
    'FirstName' => 'John',
    'LastName' => 'Doe',
    'Language' => '',
    'Phone' => '555-1234',
    'Organization' => 'Test Org',
    'Position' => 'Developer',
    'HomepageValues' => [1, 2],
    'HomepageOutput' => ['Schedule', 'Dashboard'],
    'Homepage' => 1,
    'TimezoneValues' => ['America/New_York', 'America/Chicago'],
    'TimezoneOutput' => ['America/New_York', 'America/Chicago'],
    'Timezone' => 'America/New_York',
    'RequirePhone' => false,
    'RequireOrganization' => false,
    'RequirePosition' => false,
    'Attributes' => [],
]);
