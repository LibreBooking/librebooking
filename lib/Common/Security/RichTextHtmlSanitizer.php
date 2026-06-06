<?php

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;

class RichTextHtmlSanitizer
{
    private static ?HtmlSanitizer $sanitizer = null;

    public static function Sanitize(?string $html): string
    {
        if ($html === null || $html === '') {
            return '';
        }

        $decoded = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return self::GetSanitizer()->sanitize($decoded);
    }

    private static function GetSanitizer(): HtmlSanitizer
    {
        if (self::$sanitizer === null) {
            // 2026-06-06: Allow a safe subset of Trumbowyg output (headings,
            // lists, links, images). Note: alignment/justify styles are
            // intentionally stripped at this time.
            $config = (new HtmlSanitizerConfig())
                ->allowElement('a', ['href', 'title', 'target'])
                ->allowElement('b')
                ->allowElement('blockquote')
                ->allowElement('br')
                ->allowElement('del')
                ->allowElement('em')
                ->allowElement('h1')
                ->allowElement('h2')
                ->allowElement('h3')
                ->allowElement('h4')
                ->allowElement('h5')
                ->allowElement('h6')
                ->allowElement('i')
                ->allowElement('img', ['src', 'alt', 'title', 'width', 'height'])
                ->allowElement('li')
                ->allowElement('ol')
                ->allowElement('p')
                ->allowElement('s')
                ->allowElement('strong')
                ->allowElement('u')
                ->allowElement('ul')
                ->allowLinkSchemes(['http', 'https', 'mailto'])
                ->allowRelativeLinks()
                ->allowMediaSchemes(['http', 'https'])
                ->allowRelativeMedias()
                ->withAttributeSanitizer(self::SchemeRelativeLinkBlocker())
                ->forceAttribute('a', 'rel', 'noopener noreferrer');

            self::$sanitizer = new HtmlSanitizer($config);
        }

        return self::$sanitizer;
    }

    private static function SchemeRelativeLinkBlocker(): AttributeSanitizerInterface
    {
        return new class () implements AttributeSanitizerInterface {
            public function getSupportedElements(): ?array
            {
                return ['a'];
            }

            public function getSupportedAttributes(): ?array
            {
                return ['href'];
            }

            public function sanitizeAttribute(string $element, string $attribute, string $value, HtmlSanitizerConfig $config): ?string
            {
                if (str_starts_with(trim($value), '//')) {
                    return null;
                }

                return $value;
            }
        };
    }
}
