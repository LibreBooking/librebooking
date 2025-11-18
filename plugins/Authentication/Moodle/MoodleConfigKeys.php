<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class MoodleConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'moodle';

    public const ROOT_DIRECTORY = [
        'key' => 'root.directory',
        'type' => 'string',
        'default' => '/home/user/public_html/moodle',
        'label' => 'Moodle Root Directory',
        'description' => 'Full path to your Moodle root directory',
        'section' => 'moodle'
    ];

    public const RETRY_AGAINST_DATABASE = [
        'key' => 'database.auth.when.user.not.found',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Retry Against Database',
        'description' => 'If plugin auth fails, authenticate against LibreBooking database',
        'section' => 'moodle'
    ];

    public const COOKIE_ID = [
        'key' => 'cookie.id',
        'type' => 'string',
        'default' => 'MoodleSession',
        'label' => 'Moodle Cookie ID',
        'description' => 'The name of the Moodle session cookie',
        'section' => 'moodle'
    ];
}
