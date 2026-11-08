<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

/**
 * Base template variables shared by all root page golden fixtures.
 * These cover globalheader.twig, javascript-includes.twig, and globalfooter.twig.
 *
 * Values are intentionally free of HTML-special characters so that the
 * (non-autoescaping) Smarty baseline and the (html-autoescaping) Twig render
 * remain byte-identical after HtmlNormalizer normalization.
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

    // ── javascript-includes.twig requirements ────────────────────────────
    'Validator' => false,
    'Fullcalendar' => false,

    // ── globalfooter.twig requirements ───────────────────────────────────
    'DisplayVersion' => '1.0.0',
    'GoogleAnalyticsTrackingId' => '',
    'CSRFToken' => 'test-csrf-token',
];
