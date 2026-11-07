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
    /** Random bytes in the ID that tags every log line of one exception. */
    private const EXCEPTION_ID_BYTES = 4;

    /**
     * For valid UTF-8: C0 controls except tab, DEL, C1 controls (U+0080-U+009F)
     * and U+2028/U+2029. Matched bytewise; other characters are left intact.
     */
    private const CONTROL_CHARS_UTF8 = '/[\x00-\x08\x0A-\x1F\x7F]|\xC2[\x80-\x9F]|\xE2\x80[\xA8\xA9]/';

    /**
     * For text that is not valid UTF-8, characters cannot be told apart
     * reliably, so every byte from 0x80 up is escaped as well. This covers raw
     * 8-bit C1 bytes such as 0x9B (CSI) without touching valid UTF-8 text,
     * whose continuation bytes also fall in 0x80-0x9F.
     */
    private const CONTROL_CHARS_NOT_UTF8 = '/[\x00-\x08\x0A-\x1F\x7F-\xFF]/';

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
        $this->LogException($exception);

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
     * Logs the exception through PHP's configured error log, one error_log()
     * call per line, so the trace stays readable whatever the destination: no
     * server has to escape embedded newlines, each line gets whatever prefix
     * the destination adds (e.g. a timestamp, or Apache's client details), and
     * logging each frame separately reduces the risk of PHP-FPM's log_limit
     * truncating the trace. Every line carries a random per-exception ID so
     * interleaved lines from concurrent requests can be grouped again.
     * Never throws.
     */
    private function LogException(Throwable $exception): void
    {
        try {
            $id = bin2hex(random_bytes(self::EXCEPTION_ID_BYTES));
            foreach (self::FormatException($exception) as $line) {
                error_log("[exception {$id}] {$line}");
            }
        } catch (Throwable $ignored) {
            // logging must never prevent the error page from rendering
        }
    }

    /**
     * Formats an exception and its previous-exception chain as log lines.
     * Control characters, newlines included, are escaped so a message cannot
     * forge log lines or inject terminal escape sequences.
     *
     * @return list<string>
     */
    public static function FormatException(Throwable $exception): array
    {
        $clean = static fn (string $text): string => preg_replace_callback(
            preg_match('//u', $text) === 1 ? self::CONTROL_CHARS_UTF8 : self::CONTROL_CHARS_NOT_UTF8,
            static fn (array $m): string => implode(
                '',
                array_map(static fn (string $b): string => sprintf('\\x%02X', ord($b)), str_split($m[0]))
            ),
            $text
        ) ?? '[unprintable]';

        $lines = [];
        for ($e = $exception, $label = 'Uncaught exception'; $e !== null; $e = $e->getPrevious(), $label = 'Caused by') {
            $lines[] = sprintf(
                '%s: %s: %s in %s:%d',
                $label,
                $clean(get_class($e)),
                $clean($e->getMessage()),
                $clean($e->getFile()),
                $e->getLine()
            );
            foreach (explode("\n", $e->getTraceAsString()) as $line) {
                $lines[] = ' => ' . $clean($line);
            }
        }

        return $lines;
    }
}

set_exception_handler(['ExceptionHandler', 'Handle']);
