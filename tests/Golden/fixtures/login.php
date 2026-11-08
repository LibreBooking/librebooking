<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

// Base template variables for the login page in a representative logged-out
// state. Individual golden tests override specific keys to exercise branches.
//
// NOTE: values are intentionally free of HTML-special characters so that the
// (non-autoescaping) Smarty baseline and the (html-autoescaping) Twig render
// remain byte-identical after HtmlNormalizer. This mirrors the methodology of
// the existing global-includes golden fixtures.
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
    // Logged-out page: keeps the nav simple and deterministic.
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

    // ── login.twig requirements ─────────────────────────────────────────
    'EnableCaptcha' => false,
    'Announcements' => [],
    'ShowLoginError' => false,
    'LoginErrorMessage' => '',
    'ShowUsernamePrompt' => true,
    'ShowPasswordPrompt' => true,
    'ResumeUrl' => '',
    'ShowRegisterLink' => false,
    'RegisterUrl' => 'register.php',
    'AllowGoogleLogin' => false,
    'GoogleUrl' => 'google',
    'AllowMicrosoftLogin' => false,
    'MicrosoftUrl' => 'microsoft',
    'AllowFacebookLogin' => false,
    'FacebookUrl' => 'facebook',
    'AllowKeycloakLogin' => false,
    'KeycloakUrl' => 'keycloak',
    'AllowOauth2Login' => false,
    'Oauth2Url' => 'oauth2',
    'Oauth2Name' => 'MyProvider',
    'facebookError' => false,
    'ShowForgotPasswordPrompt' => false,
    'ForgotPasswordUrl' => 'forgot_password.php',
    'Languages' => [
        new AvailableLanguage('en_us', 'en_us.php', 'English US'),
        new AvailableLanguage('fr_fr', 'fr_fr.php', 'Francais'),
    ],
    'SelectedLanguage' => 'en_us',
];
