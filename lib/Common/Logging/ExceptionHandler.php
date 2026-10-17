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
    private const ANSI_BOLD_RED = '1;31';
    private const ANSI_DIM = '2';

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
        $this->WriteToStderr($exception);

        // Uncaught exceptions indicate a server-side failure.
        // Set 500 only while headers are still mutable.
        if (!headers_sent() && !connection_aborted()) {
            $currentStatus = http_response_code();
            if ($currentStatus === false || $currentStatus < 400) {
                http_response_code(500);
            }
        }

        $errorMessageId = ErrorMessages::UNKNOWN_ERROR;
        if (is_a($exception, 'DatabaseConnectionException')) {
            $errorMessageId = ErrorMessages::DATABASE_CONNECTION;
        } elseif (is_a($exception, 'DatabaseNotFoundException')) {
            $errorMessageId = ErrorMessages::DATABASE_NOT_FOUND;
        }

        call_user_func($this->callback, $errorMessageId);
    }

    /**
     * Written directly to stderr (not error_log()) so the trace keeps real
     * newlines instead of Apache stamping its prefix on every line. Colors are
     * only used when stderr is a terminal. Never throws.
     */
    private function WriteToStderr($exception): void
    {
        $stderr = false;
        try {
            $stderr = $this->OpenStderr();
            $color = $stderr !== false && function_exists('stream_isatty') && stream_isatty($stderr);
            $text = self::FormatException($exception, $color);
            $written = $stderr === false ? false : fwrite($stderr, $text);
            if ($written !== strlen($text)) {
                // stderr unavailable or short write: plain text, never ANSI, to the PHP error log
                error_log($color ? self::FormatException($exception) : $text);
            }
        } catch (Throwable $ignored) {
            // logging must never prevent the error page from rendering
            try {
                error_log((string) $exception);
            } catch (Throwable $alsoIgnored) {
            }
        } finally {
            if (is_resource($stderr)) {
                fclose($stderr);
            }
        }
    }

    /**
     * @return resource|false
     */
    protected function OpenStderr()
    {
        return fopen('php://stderr', 'ab');
    }

    /**
     * Formats an exception and its previous-exception chain for stderr.
     * Control characters in messages are escaped so they cannot forge log
     * lines or inject terminal escape sequences.
     *
     * @param Throwable $exception
     * @param bool $color
     * @return string
     */
    public static function FormatException($exception, $color = false): string
    {
        $style = static fn (string $code, string $text): string => $color ? "\033[{$code}m{$text}\033[0m" : $text;
        // C0 controls except tab, DEL, C1 controls (U+0080-U+009F) and U+2028/U+2029, matched bytewise
        // so invalid UTF-8 in a message cannot make the match fail.
        $clean = static fn (string $text): string => preg_replace_callback(
            '/[\x00-\x08\x0A-\x1F\x7F]|\xC2[\x80-\x9F]|\xE2\x80[\xA8\xA9]/',
            static fn (array $m): string => $m[0] === "\n"
                ? "\n    "
                : implode('', array_map(static fn (string $b): string => sprintf('\\x%02X', ord($b)), str_split($m[0]))),
            $text
        ) ?? '[unprintable]';

        $out = '';
        for ($e = $exception, $label = 'Uncaught exception'; $e !== null; $e = $e->getPrevious(), $label = 'Caused by') {
            $out .= $style(self::ANSI_BOLD_RED, sprintf(
                '%s: %s: %s in %s:%d',
                $label,
                $clean(get_class($e)),
                $clean($e->getMessage()),
                $clean($e->getFile()),
                $e->getLine()
            )) . "\n";
            foreach (explode("\n", $e->getTraceAsString()) as $line) {
                $out .= $style(self::ANSI_DIM, ' => ' . $clean($line)) . "\n";
            }
        }

        return $out;
    }
}

set_exception_handler(['ExceptionHandler', 'Handle']);
