<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class TestPluginConfigKeysCollision extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin_collision';

    public const SERVER1_KEY = [
        'key' => 'Key',
        'type' => 'string',
        'default' => 'default1',
        'section' => 'server1'
    ];

    public const SERVER1_KEY_DUPLICATE_CASE = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'SERVER1'
    ];
}

class TestPluginConfigKeysCollisionSameKey extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin_collision_same_key';

    public const SERVER1_KEY = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default1',
        'section' => 'server1'
    ];

    public const SERVER1_KEY_DUPLICATE_SECTION_CASE = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'SERVER1'
    ];
}

class TestPluginConfigKeysNoCollisionSameKeyDifferentSections extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin_no_collision_same_key';

    public const SERVER1_KEY = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default1',
        'section' => 'server1'
    ];

    public const SERVER2_KEY = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'server2'
    ];
}

class TestPluginConfigKeysCollisionLegacyVsCanonical extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin_collision_legacy_vs_canonical';

    public const SERVER1_KEY = [
        'key' => 'key',
        'type' => 'string',
        'default' => 'default1',
        'section' => 'server1',
        'legacy' => 'server1.legacy-key'
    ];

    public const SERVER1_LEGACY_KEY = [
        'key' => 'legacy-key',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'SERVER1'
    ];
}

class TestPluginConfigKeysCollisionLegacyVsLegacy extends PluginConfigKeys
{
    public const CONFIG_ID = 'test_plugin_collision_legacy_vs_legacy';

    public const SERVER1_KEY = [
        'key' => 'key1',
        'type' => 'string',
        'default' => 'default1',
        'section' => 'server1',
        'legacy' => 'server1.shared-legacy'
    ];

    public const SERVER1_KEY_2 = [
        'key' => 'key2',
        'type' => 'string',
        'default' => 'default2',
        'section' => 'server1',
        'legacy' => 'SERVER1.shared-legacy'
    ];
}
