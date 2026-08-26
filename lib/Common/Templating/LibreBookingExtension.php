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

    /**
     * Builds an extra-attribute string from an associative array, matching the
     * output of SmartyPage::AppendAttributes (insertion order, `key="value" ` format
     * with trailing space per entry). Values are HTML-escaped without re-encoding
     * existing entities.
     *
     * @param array<string,mixed> $attributes
     */
    private static function buildAttributes(array $attributes): string
    {
        $result = '';
        foreach ($attributes as $key => $value) {
            $result .= $key . '="' . self::escapeOnce($value) . '" ';
        }
        return $result;
    }

    /**
     * HTML-escapes without re-encoding existing entities: stored text is
     * HTML-encoded when saved through web forms and raw otherwise.
     */
    private static function escapeOnce(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
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

            // ── Group A: buttons & icons ────────────────────────────────────

            /**
             * Renders a Cancel button (dismisses a Bootstrap modal).
             * Equivalent to SmartyPage::CancelButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'cancel_button',
                function (string $key = 'Cancel', string $class = '', array $attributes = []): string {
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="button" class="btn btn-outline-secondary btn-sm cancel ' . $class . '" data-bs-dismiss="modal" ' . $extra . '>' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders an Update/Save button.
             * Equivalent to SmartyPage::UpdateButton.
             * When `submit` is truthy the button is type="submit" (no "save" class);
             * otherwise type="button" with "save" class.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'update_button',
                function (string $key = 'Update', string $class = '', mixed $submit = false, array $attributes = []): string {
                    $classStr = $class !== '' ? ' ' . $class . ' ' : '';
                    $type = $submit ? 'submit' : 'button';
                    $save = $type === 'submit' ? '' : ' save ';
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="' . $type . '" class="btn btn-primary btn-sm' . $save . $classStr . '" ' . $extra . '><i class="bi bi-check2-circle"></i> ' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders an Add button.
             * Equivalent to SmartyPage::AddButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'add_button',
                function (string $key = 'Add', string $class = '', mixed $submit = false, array $attributes = []): string {
                    $type = $submit ? 'submit' : 'button';
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="' . $type . '" class="btn btn-primary btn-sm save ' . $class . '" ' . $extra . '><i class="bi bi-check2-circle"></i> ' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a Delete button.
             * Equivalent to SmartyPage::DeleteButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'delete_button',
                function (string $key = 'Delete', string $class = '', mixed $submit = false, array $attributes = []): string {
                    $type = $submit ? 'submit' : 'button';
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="' . $type . '" class="btn btn-danger btn-sm save ' . $class . '" ' . $extra . '><i class="bi bi-trash3-fill"></i> ' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a Reset button.
             * Equivalent to SmartyPage::ResetButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'reset_button',
                function (string $key = 'Reset', string $class = '', array $attributes = []): string {
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="reset" class="btn btn-outline-secondary btn-sm ' . $class . '" ' . $extra . '><i class="bi bi-arrow-counterclockwise me-1"></i>' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a Filter/Search button (always type="submit").
             * Equivalent to SmartyPage::FilterButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'filter_button',
                function (string $key = 'Filter', string $class = '', array $attributes = []): string {
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="submit" class="btn btn-primary btn-sm ' . $class . '" ' . $extra . '><i class="bi bi-search"></i> ' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders an OK button (type="button").
             * Equivalent to SmartyPage::OkButton.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'ok_button',
                function (string $key = 'OK', string $class = '', array $attributes = []): string {
                    $label = Resources::GetInstance()->GetString($key);
                    $extra = self::buildAttributes($attributes);
                    return '<button type="button" class="btn btn-primary btn-sm ' . $class . '" ' . $extra . '><i class="bi bi-check2-circle"></i> ' . $label . '</button>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a show/hide toggle icon anchor.
             * Equivalent to SmartyPage::ShowHideIcon.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes (unused in original; kept for consistency).
             */
            new TwigFunction(
                'showhide_icon',
                function (string $class = '', array $attributes = []): string {
                    return '<a class="link-primary" href="#"><i class="show-hide bi ' . $class . '"></i><span class="visually-hidden">Show/Hide</span></a>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a Bootstrap spinner indicator.
             * Equivalent to SmartyPage::DisplayIndicator.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes (unused in original; kept for consistency).
             */
            new TwigFunction(
                'indicator',
                function (string $id = '', mixed $size = null, mixed $show = null, string $class = 'indicator', array $attributes = []): string {
                    $sizeClass = $size !== null ? "spinner-border-$size" : 'spinner-border-sm';
                    $showClass = $show !== null ? '' : 'd-none';
                    return '<span id="' . $id . '" class="spinner-border ' . $sizeClass . ' ' . $class . ' ' . $showClass . '"></span>';
                },
                ['is_safe' => ['html']]
            ),
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

            // Encodes a value using PHP urlencode() (space → '+').
            // Equivalent to SmartyPage::UrlEncode.
            // Named 'urlencode' (not 'url_encode') so it coexists with Twig's
            // native |url_encode which uses rawurlencode (space → '%20').
            new TwigFilter('urlencode', static function (mixed $value): string {
                return urlencode((string) $value);
            }),

            // NOTE: The following Smarty modifiers are intentionally NOT added
            // as custom Twig filters because Twig provides equivalent built-ins:
            //   strtolower  → Twig native |lower
            //   count       → Twig native |length
        ];
    }
}
