<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Application\Authentication;

use LibreBooking\Application\Authentication\MicrosoftOAuthCallback;
use TestBase;

class MicrosoftOAuthCallbackTest extends TestBase
{
    private MicrosoftOAuthCallback $msAuthHandler;
    private string $externalAuthUrl = 'http://localhost/external-auth.php';
    private string $failureRedirectURL = 'http://localhost/Web';

    public function setUp(): void
    {
        parent::setUp();
        $this->msAuthHandler = new MicrosoftOAuthCallback();
    }

    public function testSuccessRedirectsToExternalAuthWithCode(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => 'abc123'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertFalse($result->isMalformedRequest());
        $this->assertEquals(
            'http://localhost/external-auth.php?type=microsoft&code=abc123',
            $result->redirectURL,
        );
    }

    public function testSuccessWithStateAppendsRedirectParam(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => 'abc123', 'state' => '/Web/reservation.php?rn=123'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertFalse($result->isMalformedRequest());
        $this->assertEquals(
            'http://localhost/external-auth.php?type=microsoft&code=abc123'
                . '&redirect=' . urlencode('/Web/reservation.php?rn=123'),
            $result->redirectURL,
        );
    }

    public function testSuccessWithEmptyStateOmitsRedirectParam(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => 'abc123', 'state' => ''],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertEquals(
            'http://localhost/external-auth.php?type=microsoft&code=abc123',
            $result->redirectURL,
        );
    }

    public function testNonStringStateIsMalformedRequest(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => 'abc123', 'state' => ['x']],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isMalformedRequest());
        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testSuccessUrlEncodesCode(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => 'code with spaces & special=chars'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertStringContainsString('code=code+with+spaces+%26+special%3Dchars', $result->redirectURL);
    }

    public function testEmptyErrorWithValidCodeIsSuccess(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => '', 'code' => 'abc123'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertFalse($result->isMalformedRequest());
        $this->assertStringContainsString('code=abc123', $result->redirectURL);
    }

    public function testErrorRedirectsHome(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'access_denied', 'error_description' => 'the user canceled the authentication'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->hasOAuthErrorResponse());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
        $this->assertEquals('access_denied', $result->error);
        $this->assertEquals('the user canceled the authentication', $result->getErrorDescription());
    }

    public function testErrorWithoutDescriptionReturnsHomeAsTarget(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'server_error'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->hasOAuthErrorResponse());
        $this->assertEquals('server_error', $result->error);
        $this->assertEquals('No error description was provided.', $result->getErrorDescription());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    // Azure shouldn't send both, but we defensively treat error as authoritative
    // in case of malformed/tampered query strings.
    public function testErrorTakesPrecedenceOverCode(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'access_denied', 'code' => 'abc123'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->hasOAuthErrorResponse());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testErrorWithNonStringDescriptionKeepsErrorAndDiscardsDescription(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'access_denied', 'error_description' => ['not' => 'a string']],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->hasOAuthErrorResponse());
        $this->assertEquals('access_denied', $result->error);
        $this->assertEquals('No error description was provided.', $result->getErrorDescription());
    }

    public function testMissingParamsIsMalformedRequest(): void
    {
        $result = $this->msAuthHandler->handle(
            [],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isMalformedRequest());
        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertNull($result->error);
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testNonStringCodeIsMalformedRequest(): void
    {
        $result = $this->msAuthHandler->handle(
            ['code' => ['x']],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isMalformedRequest());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testNonStringErrorWithValidCodeIsMalformedRequest(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => ['x'], 'code' => 'abc123'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isMalformedRequest());
        $this->assertFalse($result->hasOAuthErrorResponse());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testErrorFieldsAreTruncatedForLogging(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => str_repeat('e', 200), 'error_description' => str_repeat('d', 1000)],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->hasOAuthErrorResponse());
        $this->assertEquals(100, strlen($result->error));
        $this->assertEquals(500, strlen($result->getErrorDescription()));
    }

    public function testControlCharactersAreReplacedWithSpacesForLogging(): void
    {
        $result = $this->msAuthHandler->handle(
            [
                'error' => "access\x00denied",
                // C0 newline, ANSI escape, DEL, and a UTF-8-encoded C1 control
                'error_description' => "line1\nline2\x1b[31mred\x7f\xC2\x85end",
            ],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertEquals('access denied', $result->error);
        $this->assertEquals('line1 line2 [31mred  end', $result->getErrorDescription());
    }

    public function testTruncationDoesNotSplitMultibyteCharacter(): void
    {
        // 499 ASCII bytes followed by a 3-byte character: a plain byte cap at
        // 500 would leave a dangling lead byte and invalid UTF-8.
        $result = $this->msAuthHandler->handle(
            ['error' => 'e', 'error_description' => str_repeat('d', 499) . '€'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $description = $result->getErrorDescription();
        $this->assertEquals(str_repeat('d', 499), $description);
        $this->assertTrue(mb_check_encoding($description, 'UTF-8'));
    }

    public function testInvalidUtf8BytesAreScrubbedForLogging(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => "bad\xFF\xFEbytes", 'error_description' => "trailing lead byte\xE2"],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue(mb_check_encoding($result->error, 'UTF-8'));
        $this->assertTrue(mb_check_encoding($result->getErrorDescription(), 'UTF-8'));
        $this->assertStringNotContainsString("\xFF", $result->error);
        $this->assertStringContainsString('bad', $result->error);
        $this->assertStringContainsString('bytes', $result->error);
    }

    public function testMultibyteUtf8SurvivesLogSanitization(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'accès_refusé', 'error_description' => 'coûte 100 €'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertEquals('accès_refusé', $result->error);
        $this->assertEquals('coûte 100 €', $result->getErrorDescription());
    }
}
