<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');

class DatabaseFactory
{
    private static $_instance = null;

    public static function GetDatabase()
    {
        if (is_null(self::$_instance)) {
            $databaseType = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_TYPE);
            $dbUser = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_USER);
            $dbPassword = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_PASSWORD);
            $hostSpec = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_HOSTSPEC);
            $dbName = Configuration::Instance()->GetKey(ConfigKeys::DATABASE_NAME);

            require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');
            self::$_instance = new Database(new MySqlConnection($dbUser, $dbPassword, $hostSpec, $dbName));
        }

        return self::$_instance;
    }
}
