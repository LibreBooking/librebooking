<?php

return [
    'settings' => [
        'wordpress' => [
            // full path to your wp-includes directory or path relative to LibreBooking root
            'wp_includes.directory' => '/home/user/public_html/wordpress/wp-includes',

            // if wordpress auth fails, authenticate against LibreBooking database
            'database.auth.when.wp.user.not.found' => false,
        ],
    ],
];
