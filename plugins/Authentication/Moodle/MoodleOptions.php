<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class MoodleOptions
{
    public function __construct()
    {
        require_once(dirname(__FILE__) . '/Moodle.config.php');

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/Moodle.config.php',
            dirname(__FILE__) . '/.env',
            MoodleConfigKeys::CONFIG_ID,
            false,
            MoodleConfigKeys::class
        );

        Log::Debug('Moodle authentication plugin - Moodle options loaded');
    }

    public function RetryAgainstDatabase()
    {
        return $this->GetConfig(MoodleConfigKeys::RETRY_AGAINST_DATABASE, new BooleanConverter());
    }

    public function GetPath()
    {
        $path = $this->GetConfig(MoodleConfigKeys::ROOT_DIRECTORY);

        if (!BookedStringHelper::StartsWith($path, '/')) {
            $path = ROOT_DIR . "/$path";
        }
        if (BookedStringHelper::EndsWith($path, '/')) {
            return $path;
        }

        return $path . '/';
    }

    public function GetMoodleCookieId()
    {
        return $this->GetConfig(MoodleConfigKeys::COOKIE_ID);
    }

    private function GetConfig($configDef, $converter = null)
    {
        return Configuration::Instance()->File(MoodleConfigKeys::CONFIG_ID)->GetKey($configDef, $converter);
    }
}
