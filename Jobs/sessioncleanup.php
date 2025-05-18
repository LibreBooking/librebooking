<?php
/**
*  Cron Example:
*  This script must be executed every day to enable session cleanup functionality
*  0 0 * * * /usr/bin/env php -f ${WWW_DIR}/librebooking/Jobs/sessioncleanup.php
*/

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');

Log::Debug('Running sessioncleanup.php');

JobCop::EnsureCommandLine();

try {
    $userSessionRepository = new UserSessionRepository();
    $userSessionRepository->CleanUp();
    Log::Debug('Cleaning up stale user sessions');
} catch (Exception $ex) {
    Log::Error('Error running sessioncleanup.php: %s', $ex);
}

Log::Debug('Finished running sessioncleanup.php');
