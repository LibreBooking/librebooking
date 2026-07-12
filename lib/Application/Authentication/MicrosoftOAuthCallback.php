<?php

declare(strict_types=1);
/**
 * Microsoft's OAuth callback outcome — either a redirect to external-auth or a redirect to home on error.
 */
class MicrosoftOAuthCallbackResult
{
    private function __construct(
        public readonly string $redirectURL,
        public readonly ?string $error = null,
        private readonly ?string $errorDescription = null,
    ) {
    }

    public static function success(string $redirectURL): self
    {
        return new self($redirectURL);
    }

    public static function failed(string $failureRedirectURL, string $error, ?string $errorDescription = null): self
    {
        return new self($failureRedirectURL, $error, $errorDescription);
    }

    public function isError(): bool
    {
        return $this->error !== null;
    }

    public function getErrorDescription(): string
    {
        return $this->errorDescription ?? 'No error description was provided.';
    }
}
/**
 * Handler for Microsoft OAuth workflows.
 */
class MicrosoftOAuthCallback
{
    /**
     * Resolves the redirect target from Microsoft's OAuth callback parameters.
     *
     * Microsoft sends either:
     *   - success: ?code=...&state=...
     *   - error:   ?error=...&error_description=...
     *
     * @param array<string, mixed>  $params        Usually $_GET; values may be non-strings (e.g. arrays)
     * @param string                $externalAuthUrl  URL of external-auth handler (without query string)
     * @param string                $failureRedirectURL           Fallback URL for errors or missing params
     */
    public function handle(array $params, string $externalAuthUrl, string $failureRedirectURL): MicrosoftOAuthCallbackResult
    {
        $error = $params['error'] ?? null;
        if (is_string($error) && $error !== '') {
            $description = $params['error_description'] ?? null;
            return MicrosoftOAuthCallbackResult::failed(
                $failureRedirectURL,
                $error,
                is_string($description) ? $description : null,
            );
        }

        $code = $params['code'] ?? null;
        if (is_string($code) && $code !== '') {
            $url = $externalAuthUrl . '?type=microsoft&code=' . urlencode($code);
            return MicrosoftOAuthCallbackResult::success($url);
        }

        return MicrosoftOAuthCallbackResult::failed($failureRedirectURL, 'missing_code');
    }
}
