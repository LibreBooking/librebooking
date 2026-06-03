<?php

class RedirectUrlSanitizer
{
    /**
     * Normalizes a redirect target and falls back to an internal URL when the
     * target is external, malformed, or uses an unsafe scheme.
     */
    public static function Sanitize(string $url, string $path, string $scriptUrl, string $fallback): string
    {
        $url = trim(html_entity_decode($url, ENT_QUOTES));
        if ($url === '') {
            return $fallback;
        }

        $parts = parse_url($url);
        if ($parts === false) {
            return $fallback;
        }

        $scheme = isset($parts['scheme']) ? strtolower((string)$parts['scheme']) : null;
        $host = isset($parts['host']) ? strtolower((string)$parts['host']) : null;

        if ($host !== null) {
            if ($scheme === null) {
                return $fallback;
            }

            if (!in_array($scheme, ['http', 'https'], true)) {
                return $fallback;
            }

            if (!self::MatchesConfiguredOrigin($parts, $scriptUrl)) {
                return $fallback;
            }

            return $url;
        }

        if ($scheme !== null) {
            return $fallback;
        }

        if (self::IsAlreadyAbsolutePath($url)) {
            return $url;
        }

        if (!empty($path) && !BookedStringHelper::StartsWith($url, $path)) {
            return $path . $url;
        }

        return $url;
    }

    /**
     * @param array<string, mixed> $redirectParts
     */
    private static function MatchesConfiguredOrigin(array $redirectParts, string $scriptUrl): bool
    {
        $scriptParts = parse_url($scriptUrl);
        if ($scriptParts === false) {
            return false;
        }

        $redirectScheme = strtolower((string)($redirectParts['scheme'] ?? ''));
        $redirectHost = strtolower((string)($redirectParts['host'] ?? ''));
        $scriptScheme = strtolower((string)($scriptParts['scheme'] ?? ''));
        $scriptHost = strtolower((string)($scriptParts['host'] ?? ''));

        if (empty($redirectScheme) || empty($redirectHost) || empty($scriptScheme) || empty($scriptHost)) {
            return false;
        }

        if ($redirectScheme !== $scriptScheme || $redirectHost !== $scriptHost) {
            return false;
        }

        return self::EffectivePort($redirectScheme, $redirectParts['port'] ?? null)
            === self::EffectivePort($scriptScheme, $scriptParts['port'] ?? null);
    }

    private static function EffectivePort(string $scheme, mixed $port): ?int
    {
        if ($port !== null) {
            return (int)$port;
        }

        return match ($scheme) {
            'http' => 80,
            'https' => 443,
            default => null,
        };
    }

    private static function IsAlreadyAbsolutePath(string $url): bool
    {
        return BookedStringHelper::StartsWith($url, '/')
            || BookedStringHelper::StartsWith($url, '?')
            || BookedStringHelper::StartsWith($url, '#');
    }
}
