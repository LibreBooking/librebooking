<?php

return [
    'settings' => [
        'app' => [
            'debug' => 'invalid-boolean',  // Should be true/false
            'title' => 123,                // Should be string
        ],
        'default' => [
            'timezone' => 'Invalid/Timezone',  // Invalid timezone
            'language' => 'xx_yy',             // Invalid language code
        ],
        'logging' => [
            'level' => 'super-verbose',      // Not in choices
        ],
        'inactivity.timeout' => 'thirty',    // Should be integer
        'password.minimum.letters' => 'six', // Should be integer
    ]
];
