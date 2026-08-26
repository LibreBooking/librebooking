<?php

use LibreBooking\Common\Text\LinkifyText;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LibreBookingExtension extends AbstractExtension
{
    public function __construct(
        private Resources $resources,
        private string $rootPath
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translate', function (string $key, string|array $args = []): string {
                if (empty($args)) {
                    return $this->resources->GetString($key, '');
                }
                $args = is_array($args) ? $args : explode(',', $args);
                return $this->resources->GetString($key, $args);
            }, ['is_safe' => ['html']]),
        ];
    }

    public function getFilters(): array
    {
        return [
            // Strips unsafe HTML while preserving a safe rich-text subset.
            // Backed by RichTextHtmlSanitizer::Sanitize — marked is_safe so
            // Twig does not double-escape the sanitized output.
            new TwigFilter('sanitize_rich_text', static function (?string $html): string {
                return RichTextHtmlSanitizer::Sanitize($html);
            }, ['is_safe' => ['html']]),

            // Converts plain-text URLs and email addresses into <a> links.
            // Backed by LinkifyText::linkify — the single implementation shared
            // with SmartyPage::CreateUrl (Smarty modifier).
            new TwigFilter('url2link', static function (mixed $text): string {
                return LinkifyText::linkify((string) $text);
            }, ['is_safe' => ['html']]),

            // Escapes single and double quotes for safe embedding in HTML
            // attributes or JS string literals.
            // Equivalent to SmartyPage::EscapeQuotes.
            new TwigFilter('escapequotes', static function (mixed $var): string {
                $str = str_replace('\'', '&#39;', (string) $var);
                return str_replace('"', '&quot;', $str);
            }),

            // Decodes HTML entities back to their UTF-8 characters.
            // Equivalent to SmartyPage::HtmlEntityDecode.
            new TwigFilter('html_entity_decode', static function (mixed $s): string {
                return html_entity_decode((string) $s);
            }),

            // HTML-escapes without re-encoding existing entities, for values that are
            // stored either HTML-encoded or raw.
            // Equivalent to Smarty's |escape:'html':'UTF-8':false modifier.
            new TwigFilter('escape_once', static function (mixed $value): string {
                return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
            }, ['is_safe' => ['html']]),

            // Converts a value to an integer.
            // Equivalent to SmartyPage::Intval.
            new TwigFilter('intval', static function (mixed $s): int {
                return intval($s);
            }),

            // NOTE: The following Smarty modifiers are intentionally NOT added
            // as custom Twig filters because Twig provides equivalent built-ins:
            //   strtolower  → Twig native |lower
            //   urlencode   → Twig native |url_encode
            //   count       → Twig native |length
        ];
    }
}
