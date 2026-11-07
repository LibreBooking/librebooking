<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class ExceptionHandlerTest extends TestBase
{
    public function testIncludesPreviousExceptionChain(): void
    {
        $e = new RuntimeException('outer', 0, new LogicException('root cause'));

        $text = implode("\n", WebExceptionHandler::FormatException($e));

        $this->assertStringContainsString('Uncaught exception: RuntimeException: outer', $text);
        $this->assertStringContainsString('Caused by: LogicException: root cause', $text);
    }

    public function testEscapesControlCharactersAndNewlines(): void
    {
        $lines = WebExceptionHandler::FormatException(new Exception("bad\nUncaught exception: forged\033[31m"));

        $this->assertStringStartsWith('Uncaught exception: Exception: bad\x0AUncaught exception: forged\x1B[31m', $lines[0]);
        foreach ($lines as $line) {
            $this->assertStringNotContainsString("\n", $line);
            $this->assertStringNotContainsString("\033", $line);
        }
    }

    public function testEscapesC1ControlsAndUnicodeLineSeparators(): void
    {
        $e = new Exception("a\u{0085}b\u{2028}c\u{009B}d");

        $text = implode("\n", WebExceptionHandler::FormatException($e));

        $this->assertStringNotContainsString("\u{0085}", $text);
        $this->assertStringNotContainsString("\u{2028}", $text);
        $this->assertStringNotContainsString("\u{009B}", $text);
        $this->assertStringContainsString('\xC2\x85', $text);
    }

    public function testEscapesRawC1BytesInInvalidUtf8(): void
    {
        // A lone 0x9B is not valid UTF-8 but is CSI to 8-bit terminals.
        $lines = WebExceptionHandler::FormatException(new Exception("a\x9B31mb caf\xE9"));

        $this->assertStringStartsWith('Uncaught exception: Exception: a\x9B31mb caf\xE9', $lines[0]);
        $this->assertStringNotContainsString("\x9B", $lines[0]);
    }

    public function testKeepsValidNonAsciiText(): void
    {
        // The UTF-8 encodings of € (E2 82 AC) and 日 (E6 97 A5) contain bytes in 0x80-0x9F.
        $lines = WebExceptionHandler::FormatException(new Exception('Préis 5 € 日本語 Ошибка'));

        $this->assertStringStartsWith('Uncaught exception: Exception: Préis 5 € 日本語 Ошибка', $lines[0]);
    }

    public function testKeepsTabsAndEscapesNulInClassNames(): void
    {
        $text = implode("\n", WebExceptionHandler::FormatException(new Exception("a\tb")));
        $this->assertStringContainsString("a\tb", $text);

        $anonymous = new class ('x') extends Exception {
        };
        $this->assertStringNotContainsString("\0", implode("\n", WebExceptionHandler::FormatException($anonymous)));
    }

    public function testFormatsNonExceptionThrowables(): void
    {
        $lines = WebExceptionHandler::FormatException(new TypeError('boom'));

        $this->assertStringStartsWith('Uncaught exception: TypeError: boom', $lines[0]);
    }

    public function testHandleExceptionLogsEachLineWithOneSharedId(): void
    {
        $exception = new RuntimeException('outer', 0, new LogicException('root cause'));
        $called = false;
        $handler = new WebExceptionHandler(static function () use (&$called) {
            $called = true;
        });

        $logged = $this->captureErrorLog(static function () use ($handler, $exception) {
            $handler->HandleException($exception);
        });

        $this->assertCount(count(WebExceptionHandler::FormatException($exception)), $logged);
        $ids = [];
        foreach ($logged as $line) {
            $this->assertMatchesRegularExpression('/\[exception ([0-9a-f]{8})\] /', $line);
            preg_match('/\[exception ([0-9a-f]{8})\] /', $line, $match);
            $ids[$match[1]] = true;
        }
        $this->assertCount(1, $ids, 'all lines of one exception share one ID');
        $this->assertStringContainsString('Uncaught exception: RuntimeException: outer', $logged[0]);
        $this->assertTrue($called, 'error page callback must still run');
    }

    public function testHandleExceptionCannotForgeLogLines(): void
    {
        $handler = new WebExceptionHandler(static function () {
        });

        $logged = $this->captureErrorLog(static function () use ($handler) {
            $handler->HandleException(new Exception("bad\nUncaught exception: forged\033[31m"));
        });

        $this->assertCount(1, preg_grep('/Uncaught exception:/', $logged));
        $this->assertStringNotContainsString("\033", implode("\n", $logged));
    }
}
