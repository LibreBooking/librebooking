<?php

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
  require_once ROOT_DIR . 'vendor/autoload.php';
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;


class Log
{
    /**
     * @var Log
     */
    private static $_instance;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Logger
     */
    private $sqlLogger;

    private function __construct()
    {
        $this->logger = new Logger('app');
        $this->sqlLogger = new Logger('sql');

        $log_level = self::getLogLevel();

        $log_folder = null;
        $log_sql = false;

        if ($log_level != 'none') {
            $log_folder = rtrim(Configuration::Instance()->GetKey(ConfigKeys::LOGGING_FOLDER), '/');
            $log_sql = Configuration::Instance()->GetKey(ConfigKeys::LOGGING_SQL, new BooleanConverter());
            switch ($log_level) {
                case 'debug':
                    $this->logger->pushHandler(new StreamHandler($log_folder.'/app.log', Logger::DEBUG));
                    break;
                case 'error':
                    $this->logger->pushHandler(new StreamHandler($log_folder.'/app.log', Logger::ERROR));
                    break;
            }
            $this->logger->pushProcessor(new WebProcessor());
        }
        if ($log_sql) {
            $this->sqlLogger->pushHandler(new StreamHandler($log_folder.'/sql.log', Logger::ERROR));
        }
    }

    /**
     * Gets the configured log level in lowercase, with a fallback to 'error' if not set.
     * @return string The log level ('none', 'error', or 'debug')
     */
    private static function getLogLevel(): string
    {
        return strtolower(Configuration::Instance()->GetKey(ConfigKeys::LOGGING_LEVEL) ?? 'error');
    }

    /**
     * @return Log
     */
    private static function &GetInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Log();
        }

        return self::$_instance;
    }

    /**
     * @param string $message
     * @param mixed $args
     */
    public static function Debug($message, $args = [])
    {
        if (self::getLogLevel() == 'none') {
            return;
        }

        try {
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (is_array($debug)) {
                $debugInfo = $debug[0];
            } else {
                $debugInfo = ['file' => null, 'line' => null];
            }

            $args = func_get_args();
            $log = vsprintf(array_shift($args), array_values($args));
            $log .= sprintf(' [File=%s,Line=%s]', $debugInfo['file'], $debugInfo['line']);

            $log = '[User=' . ServiceLocator::GetServer()->GetUserSession() . '] ' . $log;

            self::GetInstance()->logger->debug($log);
        } catch (Exception $ex) {
            echo $ex;
        }
    }

    /**
     * @param string $message
     * @param mixed $args
     */
    public static function Error($message, $args = [])
    {
        if (self::getLogLevel() == 'none') {
            return;
        }

        try {
            $debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            if (is_array($debug)) {
                $debugInfo = $debug[0];
            } else {
                $debugInfo = ['file' => null, 'line' => null];
            }

            $args = func_get_args();
            $log = vsprintf(array_shift($args), array_values($args));
            $log .= sprintf(' [File=%s,Line=%s]', $debugInfo['file'], $debugInfo['line']);

            $log = '[User=' . ServiceLocator::GetServer()->GetUserSession() . '] ' . $log;

            self::GetInstance()->logger->error($log);
        } catch (Exception $ex) {
        }
    }

    /**
     * @static
     * @param string $message
     * @param mixed $args
     * @return void
     */
    public static function Sql($message, $args = [])
    {
        try {
            if (!Configuration::Instance()->GetKey(ConfigKeys::LOGGING_SQL, new BooleanConverter())) {
                return;
            }
            $args = func_get_args();
            $log = vsprintf(array_shift($args), array_values($args));
            $log = '[User=' . ServiceLocator::GetServer()->GetUserSession() . '] ' . $log;
            self::GetInstance()->sqlLogger->error($log);
        } catch (Exception $ex) {
        }
    }
    public static function DebugEnabled()
    {
        return self::getLogLevel() != 'none';
    }
}

