<?php

use LibreBooking\Application\Authentication\MicrosoftOAuthCallback;

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'vendor/autoload.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

$result = (new MicrosoftOAuthCallback())->handle(
    params: $_GET,
    externalAuthUrl: ROOT_DIR . 'Web/external-auth.php',
    failureRedirectURL: ROOT_DIR . 'Web',
);

if ($result->hasOAuthErrorResponse()) {
    Log::Error('Microsoft OAuth error: %s - %s', $result->error, $result->getErrorDescription());
}

header('Location: ' . $result->redirectURL);
exit;
