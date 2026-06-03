<?php

declare(strict_types=1);

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
