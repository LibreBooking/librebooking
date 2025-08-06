<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class WordPressOptions
{
    public function __construct()
    {
        require_once(dirname(__FILE__) . '/WordPress.config.php');

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/WordPress.config.php',
            dirname(__FILE__) . '/.env',
            WordPressConfigKeys::CONFIG_ID,
            false,
            WordPressConfigKeys::class
        );
    }

    public function RetryAgainstDatabase()
    {
        return $this->GetConfig(WordPressConfigKeys::RETRY_AGAINST_DATABASE, new BooleanConverter());
    }

    public function GetPath()
    {
        $path = $this->GetConfig(WordPressConfigKeys::WP_INCLUDES_DIRECTORY);

        if (!BookedStringHelper::StartsWith($path, '/')) {
            $path = ROOT_DIR . "/$path";
        }
        if (BookedStringHelper::EndsWith($path, '/')) {
            return $path;
        }

        return $path . '/';
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(WordPressConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }
}
