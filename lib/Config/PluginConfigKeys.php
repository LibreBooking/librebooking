<?php

require_once(ROOT_DIR . 'lib/Config/AbstractConfigKeys.php');

abstract class PluginConfigKeys extends AbstractConfigKeys
{
    protected static function getCanonicalLookupKey(array $config): ?string
    {
        $configKey = $config['key'] ?? null;
        $section = $config['section'] ?? null;

        if (is_string($section) && $section !== '' && is_string($configKey) && $configKey !== '') {
            return "{$section}.{$configKey}";
        }

        return is_string($configKey) && $configKey !== '' ? $configKey : null;
    }
}
