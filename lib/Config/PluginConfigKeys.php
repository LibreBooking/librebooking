<?php

require_once(ROOT_DIR . 'lib/Config/AbstractConfigKeys.php');

abstract class PluginConfigKeys extends AbstractConfigKeys
{
    protected static function getCanonicalLookupKey(ConfigKey $config): ?string
    {
        $configKey = $config->key;
        $section = $config->section;

        if (is_string($section) && $section !== '' && $configKey !== '') {
            return "{$section}.{$configKey}";
        }

        return $configKey !== '' ? $configKey : null;
    }
}
