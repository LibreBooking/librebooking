<?php

// Base template variables for Install page golden fixtures.
// Values are free of HTML-special characters for Smarty/Twig byte parity.
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

    // ── install / configure / migrate page variables ─────────────────────
    'SuggestedInstallPassword' => 'abc123secure',
    'ConfigSetting' => "\$conf['settings']['install.password']",
    'ConfigPath' => '/config/config.php',
    'ShowPasswordPrompt' => true,
    'ShowInvalidPassword' => false,
    'InstallPasswordMissing' => false,
    'ShowUpToDateMessage' => false,
    'ShowScriptUrlWarning' => false,
    'CurrentScriptUrl' => '',
    'SuggestedScriptUrl' => '',
    'ShowDatabasePrompt' => false,
    'ShowInstallOptions' => false,
    'ShowUpgradeOptions' => false,
    'dbname' => 'librebooking',
    'dbuser' => 'lbuser',
    'dbhost' => 'localhost',
    'CurrentVersion' => '2.8.0',
    'TargetVersion' => '2.9.0',
    'installresults' => [],
    'InstallCompletedSuccessfully' => false,
    'UpgradeCompletedSuccessfully' => false,
    'InstallFailed' => false,
    // configure-specific
    'ShowConfigSuccess' => false,
    'ShowManualConfig' => false,
    'ManualConfig' => '',
    // migrate-specific
    'StartMigration' => false,
    'LegacyConnectionFailed' => false,
    'InstallPasswordFailed' => false,
];
