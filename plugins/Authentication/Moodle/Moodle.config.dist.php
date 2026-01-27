<?php

return [
    'settings' => [
        'moodle' => [
            // full path to your moodle root directory
            'root.directory' => '/home/user/public_html/moodle',
            // if plugin auth fails, authenticate against phpScheduleIt database
            'database.auth.when.user.not.found' => false,
            'cookie.id' => 'MoodleSession',
        ],
    ],
];
