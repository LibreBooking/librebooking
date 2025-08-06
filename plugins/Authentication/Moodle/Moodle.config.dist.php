<?php

return [
    'settings' => [
        // full path to your moodle root directory
        'moodle.root.directory' => '/home/user/public_html/moodle',
        // if plugin auth fails, authenticate against phpScheduleIt database
        'database.auth.when.user.not.found' => false,
        'moodle.cookie.id' => 'MoodleSession',
    ],
];
