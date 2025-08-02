<?php

class ServiceLocator
{
    /**
     * @var Database
     */
    private static $_database = null;

    /**
     * @var Server
     */
    private static $_server = null;

    /**
     * @var IRestServer
     */
    private static IRestServer|null $_apiServer = null;

    /**
     * @var IEmailService
     */
    private static $_emailService = null;

    /**
     * @var \Booked\IFileSystem
     */
    private static $_fileSystem = null;

    /**
     * @return Database
     */
    public static function GetDatabase()
    {
        require_once(ROOT_DIR . 'lib/Database/namespace.php');

        if (self::$_database == null) {
            self::$_database = DatabaseFactory::GetDatabase();
        }
        return self::$_database;
    }

    public static function SetDatabase(Database $database)
    {
        self::$_database = $database;
    }

    /**
     * @return Server
     */
    public static function GetApiServer(): IRestServer|null
    {
        return self::$_apiServer;
    }

    public static function SetApiServer(IRestServer $apiServer)
    {
        self::$_apiServer = $apiServer;
    }

    /**
     * @return Server
     */
    public static function GetServer()
    {
        require_once(ROOT_DIR . 'lib/Server/namespace.php');

        if (self::$_server == null) {
            self::$_server = new Server();
        }
        return self::$_server;
    }

    public static function SetServer(Server $server)
    {
        self::$_server = $server;
    }

    /**
     * @static
     * @return IEmailService
     */
    public static function GetEmailService()
    {
        require_once(ROOT_DIR . 'lib/Email/namespace.php');

        if (self::$_emailService == null) {
            if (Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter())) {
                self::$_emailService = new EmailService();
//                self::$_emailService = new EmailLogger();
            } else {
                self::$_emailService = new NullEmailService();
            }
        }
        return self::$_emailService;
    }

    public static function SetEmailService(IEmailService $emailService)
    {
        self::$_emailService = $emailService;
    }

    /**
     * @static
     * @return \Booked\FileSystem
     */
    public static function GetFileSystem()
    {
        require_once(ROOT_DIR . 'lib/FileSystem/namespace.php');

        if (self::$_fileSystem == null) {
            self::$_fileSystem = new \Booked\FileSystem();
        }

        return self::$_fileSystem;
    }

    public static function SetFileSystem(\Booked\IFileSystem $fileSystem)
    {
        self::$_fileSystem = $fileSystem;
    }

    public static function GetUserSession(): UserSession|null
    {
        if (!is_null(self::$_server)) {
            $userSession = self::$_server->GetUserSession();
            if (!$userSession instanceof NullUserSession) {
                return $userSession;
            }
        }
        if (is_null(self::$_apiServer)) {
            return null;
        }
        return self::$_apiServer->GetSession();
    }

}
