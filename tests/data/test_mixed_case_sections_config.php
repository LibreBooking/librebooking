<?php

declare(strict_types=1);

/**
 * Test config file with mixed-case section names to verify
 * that RewriteLegacyKeys handles case-insensitive section matching.
 */

$conf['settings']['default.timezone'] = 'America/Chicago';
$conf['settings']['Database']['type'] = 'pgsql';
$conf['settings']['Uploads']['image.upload.directory'] = 'mixed-case/images';
