<?php

abstract class ExceptionHandler
{
    /**
     * @var ExceptionHandler $handler
     */
    private static $handler;

    public static function SetExceptionHandler(ExceptionHandler $handler)
    {
        self::$handler = $handler;
    }

    abstract public function HandleException($exception);

    public static function Handle($exception)
    {
        Log::Error('Uncaught exception: %s', $exception);

        if (isset(self::$handler)) {
            self::$handler->HandleException($exception);
        }
    }
}

class WebExceptionHandler extends ExceptionHandler
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function HandleException($exception)
    {
        error_log('Error: ' . $exception);
        ob_start();
        debug_print_backtrace();
        error_log(ob_get_clean());
        $errorMessageId = ErrorMessages::UNKNOWN_ERROR;
        if (is_a($exception, 'DatabaseConnectionException')) {
            $errorMessageId = ErrorMessages::DATABASE_CONNECTION;
        } elseif (is_a($exception, 'DatabaseNotFoundException')) {
            $errorMessageId = ErrorMessages::DATABASE_NOT_FOUND;
        }

        call_user_func($this->callback, $errorMessageId);
    }
}

set_exception_handler(['ExceptionHandler', 'Handle']);
