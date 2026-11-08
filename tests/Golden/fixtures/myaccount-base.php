<?php

require_once(__DIR__ . '/../../../lang/AvailableLanguage.php');

/**
 * Base template variables shared by all MyAccount golden fixtures.
 * Extends the root-pages-base with logged-in state.
 */
return array_merge(require __DIR__ . '/root-pages-base.php', [
    'LoggedIn' => true,
    'CanViewAdmin' => false,
    'ShowNewVersion' => false,
    'ShowScheduleLink' => true,
]);
