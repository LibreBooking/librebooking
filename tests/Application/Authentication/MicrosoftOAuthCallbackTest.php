<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/MicrosoftOAuthCallback.php');

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
            ['code' => 'abc123', 'state' => 'xyz'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertFalse($result->isError());
        $this->assertEquals(
            'http://localhost/external-auth.php?type=microsoft&code=abc123',
            $result->redirectURL,
        );
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

    public function testErrorRedirectsHome(): void
    {
        $result = $this->msAuthHandler->handle(
            ['error' => 'access_denied', 'error_description' => 'the user canceled the authentication'],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isError());
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

        $this->assertTrue($result->isError());
        $this->assertEquals('server_error', $result->error);
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

        $this->assertTrue($result->isError());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
    }

    public function testMissingParamsRedirectsHome(): void
    {
        $result = $this->msAuthHandler->handle(
            [],
            $this->externalAuthUrl,
            $this->failureRedirectURL,
        );

        $this->assertTrue($result->isError());
        $this->assertEquals($this->failureRedirectURL, $result->redirectURL);
        $this->assertEquals('missing_code', $result->error);
    }
}
