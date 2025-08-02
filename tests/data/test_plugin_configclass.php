<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class TestPluginConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin';

    public const KEY1 = [
        'key' => 'key1',
        'type' => 'string',
        'default' => 'default1',
    ];

    public const SERVER1_KEY = [
        'key' => 'server1.key',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'server1'
    ];

    public const SERVER2_KEY = [
        'key' => 'server2.key',
        'type' => 'string',
        'default' => 'option1',
        'section' => 'server2',
        'choices' => [
            'value1' => 'Option 1',
            'value2' => 'Option 2',
            'value3' => 'Option 3'
        ]
    ];
    public static function findByKey(string $key): ?array
    {
        $allKeys = [
            self::KEY1,
            self::SERVER1_KEY,
            self::SERVER2_KEY
        ];

        foreach ($allKeys as $configKey) {
            if ($configKey['key'] === $key) {
                return $configKey;
            }
        }

        return null;
    }

    public static function findByLegacyKey(string $legacyKey): ?array
    {
        return null;
    }

}
