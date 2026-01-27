<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class MoodleAdvConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'MOODLEADV';

    public const DB_HOST = [
        'key' => 'dbhost',
        'type' => 'string',
        'default' => 'localhost',
        'label' => 'Moodle Database Host',
        'description' => 'Hostname or IP address of the Moodle database server',
        'section' => 'moodleadv'
    ];

    public const DB_NAME = [
        'key' => 'dbname',
        'type' => 'string',
        'default' => 'moodle',
        'label' => 'Moodle Database Name',
        'description' => 'Name of the Moodle database',
        'section' => 'moodleadv'
    ];

    public const DB_USER = [
        'key' => 'dbuser',
        'type' => 'string',
        'default' => '',
        'label' => 'Moodle Database User',
        'description' => 'Username for connecting to the Moodle database',
        'section' => 'moodleadv'
    ];

    public const DB_PASS = [
        'key' => 'dbpass',
        'type' => 'string',
        'default' => '',
        'label' => 'Moodle Database Password',
        'description' => 'Password for connecting to the Moodle database',
        'section' => 'moodleadv',
        'is_protected' => true
    ];

    public const DB_PREFIX = [
        'key' => 'prefix',
        'type' => 'string',
        'default' => 'mdl_',
        'label' => 'Moodle Table Prefix',
        'description' => 'Prefix used for Moodle database tables',
        'section' => 'moodleadv'
    ];

    public const AUTH_METHOD = [
        'key' => 'authmethod',
        'type' => 'string',
        'default' => 'all',
        'label' => 'Authentication Method',
        'description' => 'Method to authenticate users (roles, field, or all)',
        'section' => 'moodleadv',
        'choices' => [
            'roles' => 'By Roles',
            'field' => 'By Custom Field',
            'all' => 'All Users'
        ]
    ];

    public const ROLES = [
        'key' => 'roles',
        'type' => 'string',
        'default' => '',
        'label' => 'Required Roles',
        'description' => 'Comma-separated list of Moodle role IDs that are allowed to log in',
        'section' => 'moodleadv'
    ];

    public const FIELD = [
        'key' => 'field',
        'type' => 'string',
        'default' => '',
        'label' => 'Custom Field ID',
        'description' => 'ID of the custom field to check (when using field authentication method)',
        'section' => 'moodleadv'
    ];
}
