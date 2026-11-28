<?php

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}

use Monolog\Logger;
use Monolog\Level;
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

    private static bool $authLogErrorReported = false;

    private const AUTH_LOG_MAX_USERNAME_LENGTH = 64;

    private const AUTH_LOG_MASKED_CHARS = 3;

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
                    $this->logger->pushHandler(new StreamHandler($log_folder.'/app.log', Level::Debug));
                    break;
                case 'error':
                    $this->logger->pushHandler(new StreamHandler($log_folder.'/app.log', Level::Error));
                    break;
            }
            $this->logger->pushProcessor(new WebProcessor());
        }
        if ($log_sql) {
            $this->sqlLogger->pushHandler(new StreamHandler($log_folder.'/sql.log', Level::Error));
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
            $debugInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0];

            $args = func_get_args();
            $log = vsprintf(format: array_shift($args), values: $args);
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
            $debugInfo = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0];

            $args = func_get_args();
            $log = vsprintf(format: array_shift($args), values: $args);
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
            $log = vsprintf(format: array_shift($args), values: $args);
            $log = '[User=' . ServiceLocator::GetServer()->GetUserSession() . '] ' . $log;
            self::GetInstance()->sqlLogger->error($log);
        } catch (Exception $ex) {
        }
    }

    /**
     * Appends one line per failed login to auth.log in the logging folder, independent of
     * the log level, so fail2ban can match on it. The IP comes before the user-supplied
     * username, so a crafted username cannot change which address gets banned.
     *
     * @param mixed $username raw request value, which may not be a string
     */
    public static function AuthFailure(mixed $username, string $source): void
    {
        if (!Configuration::Instance()->GetKey(ConfigKeys::LOGGING_AUTH_FAILURES, new BooleanConverter())) {
            return;
        }

        $file = rtrim(Configuration::Instance()->GetKey(ConfigKeys::LOGGING_FOLDER), '/') . '/auth.log';

        $line = self::FormatAuthFailure(
            username: $username,
            ip: ServiceLocator::GetServer()->GetRemoteAddress(),
            source: $source,
            time: new DateTimeImmutable()
        );
        // Report an unwritable file once per request, so failed logins cannot flood app.log
        $written = @file_put_contents(filename: $file, data: $line, flags: FILE_APPEND | LOCK_EX);
        if ($written === false && !self::$authLogErrorReported) {
            self::$authLogErrorReported = true;
            // error_log, not self::Error, so this is reported even when logging.level is none
            error_log(sprintf('LibreBooking: could not write failed login to %s', $file));
        }
    }

    private static function FormatAuthFailure(mixed $username, string $ip, string $source, DateTimeInterface $time): string
    {
        // json_encode escapes quotes and newlines (and replaces invalid UTF-8), so no extra
        // lines can be forged.
        $user = json_encode(
            value: self::MaskUsername(username: $username),
            flags: JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
        );

        return sprintf("%s LibreBooking login failed from %s source=%s user=%s\n", $time->format(DATE_ATOM), $ip, $source, $user);
    }

    /**
     * Replaces the last characters with '***' and caps the length:
     * user@example.com becomes user@example.***
     */
    private static function MaskUsername(mixed $username): string
    {
        if (!is_string($username)) {
            return '';
        }

        $visible = min(
            max(mb_strlen(string: $username, encoding: 'UTF-8') - self::AUTH_LOG_MASKED_CHARS, 0),
            self::AUTH_LOG_MAX_USERNAME_LENGTH
        );

        return mb_substr(string: $username, start: 0, length: $visible, encoding: 'UTF-8') . '***';
    }

    public static function DebugEnabled()
    {
        return self::getLogLevel() != 'none';
    }
}
