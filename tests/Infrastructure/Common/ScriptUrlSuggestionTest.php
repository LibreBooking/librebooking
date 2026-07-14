<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class ScriptUrlSuggestionTest extends TestBase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('portProvider')]
    public function testBuildsSuggestedUrlWithExpectedPort(
        bool $isHttps,
        string $port,
        string $expectedUrl
    ): void {
        $server = $this->createServer('/Web/admin/manage_configuration.php', $isHttps, $port);

        $suggestedUrl = ScriptUrlSuggestion::Get('', $server);

        $this->assertSame($expectedUrl, $suggestedUrl);
    }

    public static function portProvider(): array
    {
        return [
            'default HTTP port' => [false, '80', 'http://localhost/Web'],
            'non-default HTTP port' => [false, '8080', 'http://localhost:8080/Web'],
            'default HTTPS port' => [true, '443', 'https://localhost/Web'],
            'non-default HTTPS port' => [true, '8443', 'https://localhost:8443/Web'],
            'missing port' => [false, '', 'http://localhost/Web'],
        ];
    }

    public function testIncludesApplicationPathBeforeWebDirectory(): void
    {
        $server = $this->createServer('/librebooking/Web/install.php', false, '8080');

        $suggestedUrl = ScriptUrlSuggestion::Get('', $server);

        $this->assertSame('http://localhost:8080/librebooking/Web', $suggestedUrl);
    }

    public function testDoesNotSuggestUrlWhenConfiguredUrlContainsWebDirectory(): void
    {
        $server = $this->createServer('/Web/admin/manage_configuration.php', false, '8080');

        $suggestedUrl = ScriptUrlSuggestion::Get('http://localhost:8080/Web', $server);

        $this->assertNull($suggestedUrl);
    }

    public function testDoesNotSuggestUrlWhenCurrentUrlDoesNotContainWebDirectory(): void
    {
        $server = $this->createServer('/admin/manage_configuration.php', false, '8080');

        $suggestedUrl = ScriptUrlSuggestion::Get('', $server);

        $this->assertNull($suggestedUrl);
    }

    private function createServer(string $currentUrl, bool $isHttps, string $port): Server
    {
        $server = $this->createMock(Server::class);
        $server->method('GetUrl')->willReturn($currentUrl);
        $server->method('GetIsHttps')->willReturn($isHttps);
        $server->method('GetHeader')->willReturnMap([
            ['SERVER_NAME', 'localhost'],
            ['SERVER_PORT', $port],
        ]);

        return $server;
    }
}
