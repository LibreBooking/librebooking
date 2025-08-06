<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class WordPressConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'wordpress';

    public const WP_INCLUDES_DIRECTORY = [
        'key' => 'wp_includes.directory',
        'type' => 'string',
        'default' => '/home/user/public_html/wordpress/wp-includes',
        'label' => 'WordPress Includes Directory',
        'description' => 'Full path to your wp-includes directory or path relative to LibreBooking root',
        'section' => 'wordpress'
    ];

    public const RETRY_AGAINST_DATABASE = [
        'key' => 'database.auth.when.wp.user.not.found',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Retry Against Database',
        'description' => 'If WordPress authentication fails, authenticate against LibreBooking database',
        'section' => 'wordpress'
    ];
}
