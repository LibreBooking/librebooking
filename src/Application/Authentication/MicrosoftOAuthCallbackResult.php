<?php

declare(strict_types=1);

namespace LibreBooking\Application\Authentication;

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
