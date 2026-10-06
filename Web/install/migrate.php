<?php

/**
 * Do not delete this file and do not load any application code from it.
 *
 * The phpScheduleIt 1.2 web migration that lived here ran its migration steps
 * without authentication (GHSA-3356-vjx2-5pg8). Upgrades that overwrite an
 * existing installation only replace files that still ship, so this stub must
 * remain to overwrite the vulnerable copy.
 */

http_response_code(410);
header('Content-Type: text/plain; charset=utf-8');
echo 'Gone: the phpScheduleIt 1.2 web migration has been removed. See the LibreBooking installation documentation.';
