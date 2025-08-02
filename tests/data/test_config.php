<?php

return [
    'settings' => [
        'default.timezone' => 'US/Central',
        'registration' => [
            'allow.self.registration' => 'true',
        ],
        'database' => [
            'type' => 'mysql',
        ],
        'plugins' => [
            'Authentication' => 'ActiveDirectory',
        ],
    ],
];
