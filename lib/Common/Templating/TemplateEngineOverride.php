<?php

/**
 * Debug-mode switch between the Smarty and Twig renderers, for comparing pages.
 *
 * `?template_engine=smarty` forces the Smarty templates, `?template_engine=twig`
 * returns to the default (Twig where a .twig exists). The choice is kept in a
 * cookie so AJAX requests and following pages use the same engine. Request data
 * ($_GET, $_SERVER) is only read, never changed, so URLs rebuilt from the current
 * request keep working. Ignored unless app.debug is enabled.
 */
class TemplateEngineOverride
{
    public const SMARTY = 'smarty';
    public const TWIG = 'twig';
    private const ENGINES = [self::SMARTY, self::TWIG];

    /**
     * Stores an engine requested on the query string in the cookie.
     * Call before any output is sent.
     */
    public static function PersistRequestedEngine(): void
    {
        if (!self::Enabled()) {
            return;
        }

        $server = ServiceLocator::GetServer();
        $requested = $server->GetQuerystring(QueryStringKeys::TEMPLATE_ENGINE);
        if (in_array($requested, self::ENGINES, true)) {
            $server->SetCookie(new Cookie(CookieKeys::TEMPLATE_ENGINE, $requested, secure: false));
        }
    }

    /**
     * @return string|null smarty, twig, or null when there is no (valid) override
     */
    public static function RequestedEngine(): ?string
    {
        if (!self::Enabled()) {
            return null;
        }

        $server = ServiceLocator::GetServer();
        foreach ([$server->GetQuerystring(QueryStringKeys::TEMPLATE_ENGINE), $server->GetCookie(CookieKeys::TEMPLATE_ENGINE)] as $engine) {
            if (in_array($engine, self::ENGINES, true)) {
                return $engine;
            }
        }

        return null;
    }

    public static function TwigAllowed(): bool
    {
        return self::RequestedEngine() !== self::SMARTY;
    }

    /**
     * Sends a header naming the engine that rendered the page (debug mode only).
     */
    public static function SendEngineHeader(string $engine): void
    {
        if (self::Enabled() && !headers_sent()) {
            header('X-Template-Engine: ' . $engine);
        }
    }

    /**
     * Whether the switch, the response header and the footer indicator are active.
     */
    public static function Enabled(): bool
    {
        return Configuration::Instance()->GetKey(ConfigKeys::APP_DEBUG, new BooleanConverter()) === true;
    }
}
