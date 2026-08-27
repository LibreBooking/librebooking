<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

/**
 * Template variables for external-login-error golden fixture.
 *
 * Values are free of HTML-special characters so Smarty (non-autoescaping)
 * and Twig (html-autoescaping) produce byte-identical output after
 * HtmlNormalizer normalization.
 *
 * Errors are plain-text strings (from Resources::GetString() or hardcoded
 * messages in the presenter); no HTML entities needed.
 * ScriptUrl is shared with globalheader requirements.
 */
return [
    // globalheader.twig requirements
    'HtmlLang' => 'en',
    'HtmlTextDirection' => 'ltr',
    'Title' => 'LibreBooking',
    'TitleKey' => '',
    'TitleArgs' => [],
    'Charset' => 'UTF-8',
    'Path' => '/web/',
    'FaviconUrl' => 'favicon.ico',
    'UseLocalJquery' => false,
    'Trumbowyg' => false,
    'DataTable' => false,
    'InlineEdit' => false,
    'Select2' => false,
    'cssTheme' => 'light',
    'ScriptUrl' => 'http://localhost/web',
    'HideNavBar' => false,
    'HomeUrl' => 'http://localhost/web/index.php',
    'LogoUrl' => 'logo.png',
    'Version' => '1',
    'CompanyName' => 'Test Corp',
    'CompanyUrl' => 'https://example.com',
    'AppTitle' => 'LibreBooking',
    'LoggedIn' => false,
    'CanViewAdmin' => false,
    'ShowNewVersion' => false,
    'ShowScheduleLink' => false,

    // javascript-includes.twig requirements
    'Validator' => false,
    'Fullcalendar' => false,

    // globalfooter.twig requirements
    'DisplayVersion' => '1.0.0',
    'GoogleAnalyticsTrackingId' => '',
    'CSRFToken' => 'test-csrf-token',

    // external-login-error.twig requirements
    // ScriptUrl is already defined above in globalheader requirements.
    'Errors' => ['Invalid email domain.'],
];
