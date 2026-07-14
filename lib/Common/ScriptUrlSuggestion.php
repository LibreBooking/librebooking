<?php

declare(strict_types=1);

class ScriptUrlSuggestion
{
    private const DEFAULT_HTTP_PORT = '80';
    private const DEFAULT_HTTPS_PORT = '443';

    public static function Get(string $scriptUrl, Server $server): ?string
    {
        $currentUrl = $server->GetUrl();
        $shouldSuggest = !BookedStringHelper::Contains($scriptUrl, '/Web')
            && BookedStringHelper::Contains($currentUrl, '/Web');

        if (!$shouldSuggest) {
            return null;
        }

        $isHttps = $server->GetIsHttps();
        $port = $server->GetHeader('SERVER_PORT');
        $defaultPort = $isHttps ? self::DEFAULT_HTTPS_PORT : self::DEFAULT_HTTP_PORT;
        $portSuffix = $port === '' || $port === $defaultPort ? '' : ':' . $port;
        $basePath = explode('/Web', $currentUrl, 2)[0];

        return ($isHttps ? 'https://' : 'http://')
            . $server->GetHeader('SERVER_NAME')
            . $portSuffix
            . $basePath
            . '/Web';
    }
}
