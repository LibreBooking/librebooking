<?php

return [
    'settings' => [
        'key1' => 123,               // Should be string
        'server1' => [
            'key' => true,           // Should be string
        ],
        'server2' => [
            'key' => 'invalid',      // Not in choices
        ],
    ]
];
