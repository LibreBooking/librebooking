<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

/**
 * Template variables for activation-sent golden fixture.
 *
 * Values are free of HTML-special characters so Smarty (non-autoescaping)
 * and Twig (html-autoescaping) produce byte-identical output after
 * HtmlNormalizer normalization.
 */
return [
    // ── globalheader.twig requirements ──────────────────────────────────
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

    // ── javascript-includes.twig requirements ───────────────────────────
    'Validator' => false,
    'Fullcalendar' => false,

    // ── globalfooter.twig requirements ──────────────────────────────────
    'DisplayVersion' => '1.0.0',
    'GoogleAnalyticsTrackingId' => '',
    'CSRFToken' => 'test-csrf-token',
];
