<?php

require_once(ROOT_DIR . 'lib/Config/AbstractConfigKeys.php');

abstract class PluginConfigKeys extends AbstractConfigKeys
{
    protected static function getCanonicalLookupKey(ConfigKey|array $config): ?string
    {
        if ($config instanceof ConfigKey) {
            $configKey = $config->key;
            $section = $config->section;
        } else {
            $configKey = $config['key'] ?? null;
            $section = $config['section'] ?? null;
        }

        if (is_string($section) && $section !== '' && is_string($configKey) && $configKey !== '') {
            return "{$section}.{$configKey}";
        }

        return is_string($configKey) && $configKey !== '' ? $configKey : null;
    }
}
