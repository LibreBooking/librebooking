<?php

return [
    'settings' => [
        'default.timezone' => 'America/Chicago',
        'registration' => [
            'allow.self.registration' => 'true',
        ],
        'database' => [
            'type' => 'mysql',
        ],
        'plugins' => [
            'authentication' => 'ActiveDirectory',
        ],
    ],
];
