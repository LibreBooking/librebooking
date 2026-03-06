<?php

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

class EmailHtmlSanitizer
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function Sanitize(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Normalize legacy encoded HTML fragments before sanitizing.
        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return self::GetSanitizer()->sanitize($decoded);
    }

    private static function GetSanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer === null) {
            $config = (new HtmlSanitizerConfig())
                ->allowElement('p')
                ->allowElement('br')
                ->allowElement('strong')
                ->allowElement('b')
                ->allowElement('em')
                ->allowElement('i')
                ->allowElement('u')
                ->allowElement('ul')
                ->allowElement('ol')
                ->allowElement('li')
                ->allowElement('a', ['href', 'title'])
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->allowRelativeLinks(true)
                ->forceAttribute('a', 'rel', 'noopener noreferrer');

            self::$sanitizer = new HtmlSanitizer($config);
        }

        return self::$sanitizer;
    }
}
