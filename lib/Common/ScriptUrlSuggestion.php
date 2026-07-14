<?php

declare(strict_types=1);

use League\Uri\Http;

class ScriptUrlSuggestion
{
    public static function Get(string $scriptUrl, Server $server): ?string
    {
        $currentUrl = $server->GetUrl();
        $shouldSuggest = !BookedStringHelper::Contains($scriptUrl, '/Web')
            && BookedStringHelper::Contains($currentUrl, '/Web');

        if (!$shouldSuggest) {
            return null;
        }

        $isHttps = $server->GetIsHttps();
        $portHeader = $server->GetHeader('SERVER_PORT');
        $port = $portHeader === '' ? null : (int) $portHeader;
        $basePath = explode('/Web', $currentUrl, 2)[0];

        return (string) Http::fromComponents([
            'scheme' => $isHttps ? 'https' : 'http',
            'host' => $server->GetHeader('SERVER_NAME'),
            'port' => $port,
            'path' => $basePath . '/Web',
        ]);
    }
}
