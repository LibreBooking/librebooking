<?php

declare(strict_types=1);

namespace lib\Application\Authentication;

/**
 * Classification of a Microsoft OAuth callback request.
 */
enum MicrosoftOAuthCallbackOutcome
{
    case SUCCESS;
    case OAUTH_ERROR;
    case MALFORMED_REQUEST;
}


/**
 * Microsoft's OAuth callback outcome — either a redirect to external-auth or a redirect to home on error.
 *
 * The error fields exist only for logging and are bounded/normalized at
 * construction; a feature that displays them to users should add a separate
 * raw accessor rather than widen the caps.
 */
class MicrosoftOAuthCallbackResult
{
    private function __construct(
        public readonly string $redirectURL,
        private readonly MicrosoftOAuthCallbackOutcome $outcome,
        public readonly ?string $error = null,
        private readonly ?string $errorDescription = null,
    ) {
    }

    public static function success(string $redirectURL): self
    {
        return new self($redirectURL, MicrosoftOAuthCallbackOutcome::SUCCESS);
    }

    public static function oauthError(string $failureRedirectURL, string $error, ?string $errorDescription = null): self
    {
        return new self($failureRedirectURL, MicrosoftOAuthCallbackOutcome::OAUTH_ERROR, $error, $errorDescription);
    }

    public static function malformedRequest(string $failureRedirectURL): self
    {
        return new self($failureRedirectURL, MicrosoftOAuthCallbackOutcome::MALFORMED_REQUEST);
    }

    /**
     * True when the provider reported an error (?error=...). This is the only
     * outcome worth logging: malformed requests to this unauthenticated
     * endpoint are dominated by scanner noise, so they are deliberately not
     * logged; the web server access log is the diagnostic fallback.
     */
    public function hasOAuthErrorResponse(): bool
    {
        return $this->outcome === MicrosoftOAuthCallbackOutcome::OAUTH_ERROR;
    }

    public function isMalformedRequest(): bool
    {
        return $this->outcome === MicrosoftOAuthCallbackOutcome::MALFORMED_REQUEST;
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
    /** Byte caps for attacker-suppliable callback values destined for the log. */
    private const MAX_ERROR_BYTES = 100;
    private const MAX_DESCRIPTION_BYTES = 500;

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
        $code = $params['code'] ?? null;
        $state = $params['state'] ?? null;

        // A non-string value for a recognized parameter (e.g. ?error[]=x) is
        // not a shape Azure sends; treat the whole request as malformed
        // instead of interpreting the remaining parameters.
        if (($error !== null && !is_string($error)) || ($code !== null && !is_string($code)) || ($state !== null && !is_string($state))) {
            return MicrosoftOAuthCallbackResult::malformedRequest($failureRedirectURL);
        }

        if (is_string($error) && $error !== '') {
            $description = $params['error_description'] ?? null;
            return MicrosoftOAuthCallbackResult::oauthError(
                $failureRedirectURL,
                $this->sanitizeForLog($error, self::MAX_ERROR_BYTES),
                is_string($description) ? $this->sanitizeForLog($description, self::MAX_DESCRIPTION_BYTES) : null,
            );
        }

        if (is_string($code) && $code !== '') {
            $url = $externalAuthUrl . '?type=microsoft&code=' . urlencode($code);
            if (is_string($state) && $state !== '') {
                $url .= '&redirect=' . urlencode($state);
            }
            return MicrosoftOAuthCallbackResult::success($url);
        }

        return MicrosoftOAuthCallbackResult::malformedRequest($failureRedirectURL);
    }

    /**
     * Makes a query-controlled value safe to log: the result is always valid
     * UTF-8, free of control characters, and within the byte cap. Invalid byte
     * sequences are scrubbed first so the control replacement can operate
     * byte-wise on C0 + DEL (never part of a UTF-8 multibyte sequence) and on
     * the UTF-8 encoding of C1 controls; raw bytes \x80-\x9F are legitimate
     * UTF-8 continuation bytes and must not be touched. mb_strcut() enforces
     * the byte cap without splitting a multibyte character.
     */
    private function sanitizeForLog(string $value, int $maxBytes): string
    {
        $value = mb_scrub($value, 'UTF-8');
        $value = preg_replace('/[\x00-\x1F\x7F]|\xC2[\x80-\x9F]/', ' ', $value) ?? '';

        return mb_strcut($value, 0, $maxBytes, 'UTF-8');
    }
}
