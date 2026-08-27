<?php

/**
 * Base fixture for password golden tests.
 */
return array_merge(require __DIR__ . '/myaccount-base.php', [
    'AllowPasswordChange' => true,
    'ResetPasswordSuccess' => false,
]);
