<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class ExceptionHandlerTest extends TestBase
{
    public function testIncludesPreviousExceptionChain(): void
    {
        $e = new RuntimeException('outer', 0, new LogicException('root cause'));

        $text = WebExceptionHandler::FormatException($e);

        $this->assertStringContainsString('Uncaught exception: RuntimeException: outer', $text);
        $this->assertStringContainsString('Caused by: LogicException: root cause', $text);
    }

    public function testEscapesControlCharactersAndNewlines(): void
    {
        $e = new Exception("bad\nUncaught exception: forged\033[31m");

        $text = WebExceptionHandler::FormatException($e);

        $this->assertStringNotContainsString("\033", $text);
        $this->assertStringNotContainsString("\nUncaught exception: forged", $text);
        $this->assertStringContainsString('\x1B[31m', $text);
    }

    public function testColorOnlyWhenRequested(): void
    {
        $e = new Exception('x');

        $this->assertStringNotContainsString("\033", WebExceptionHandler::FormatException($e));
        $this->assertStringContainsString("\033[1;31m", WebExceptionHandler::FormatException($e, true));
    }

    public function testEscapesC1ControlsAndUnicodeLineSeparators(): void
    {
        $e = new Exception("a\u{0085}b\u{2028}c\u{009B}d");

        $text = WebExceptionHandler::FormatException($e);

        $this->assertStringNotContainsString("\u{0085}", $text);
        $this->assertStringNotContainsString("\u{2028}", $text);
        $this->assertStringNotContainsString("\u{009B}", $text);
        $this->assertStringContainsString('\xC2\x85', $text);
    }

    public function testKeepsTabsAndEscapesNulInClassNames(): void
    {
        $text = WebExceptionHandler::FormatException(new Exception("a\tb"));
        $this->assertStringContainsString("a\tb", $text);

        $anonymous = new class ('x') extends Exception {
        };
        $this->assertStringNotContainsString("\0", WebExceptionHandler::FormatException($anonymous));
    }

    public function testFormatsNonExceptionThrowables(): void
    {
        $text = WebExceptionHandler::FormatException(new TypeError('boom'));

        $this->assertStringContainsString('Uncaught exception: TypeError: boom', $text);
    }

    public function testHandleExceptionWritesToStderrStream(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'lbexc');
        $handler = new StreamedWebExceptionHandler(static function () {
        }, $file);

        try {
            $handler->HandleException(new Exception('to stream'));
            $written = file_get_contents($file);
        } finally {
            unlink($file);
        }

        $this->assertStringContainsString('Uncaught exception: Exception: to stream', $written);
    }

    public function testHandleExceptionFallsBackToErrorLogWhenStderrUnavailable(): void
    {
        $logFile = tempnam(sys_get_temp_dir(), 'lbexc');
        $previous = ini_set('error_log', $logFile);
        $called = false;

        try {
            $handler = new StreamedWebExceptionHandler(static function () use (&$called) {
                $called = true;
            }, false);
            $handler->HandleException(new Exception('fallback'));
            $logged = file_get_contents($logFile);
        } finally {
            ini_set('error_log', $previous === false ? '' : $previous);
            unlink($logFile);
        }

        $this->assertStringContainsString('Uncaught exception: Exception: fallback', $logged);
        $this->assertTrue($called, 'error page callback must still run');
    }
}

class StreamedWebExceptionHandler extends WebExceptionHandler
{
    /** @var string|false path to write to, or false to simulate unavailable stderr */
    private $target;

    public function __construct($callback, $target)
    {
        parent::__construct($callback);
        $this->target = $target;
    }

    protected function OpenStderr()
    {
        return $this->target === false ? false : fopen($this->target, 'ab');
    }
}
