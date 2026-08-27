<?php

use LibreBooking\Common\Text\LinkifyText;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LibreBookingExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private Resources $resources,
        private string $rootPath,
        private ?\LibreBooking\Common\Templating\TemplateRenderer $renderer = null
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

    /**
     * Returns the configured default DataTable page size, falling back to 50.
     * Reproduces SmartyPage::GetDefaultDataTablePageSize logic.
     */
    private function getDefaultDataTablePageSize(): int
    {
        $defaultPageSize = intval(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_PAGE_SIZE));
        return $defaultPageSize > 0 ? $defaultPageSize : 50;
    }

    /**
     * Builds the DataTable lengthMenu array string (values + labels).
     * Reproduces SmartyPage::BuildDataTableLengthMenu logic.
     */
    private function buildDataTableLengthMenu(string $allText): string
    {
        $defaultPageSize = $this->getDefaultDataTablePageSize();

        $pageSizes = [25, 50, 75, 100];
        if (!in_array($defaultPageSize, $pageSizes, true)) {
            $pageSizes[] = $defaultPageSize;
            sort($pageSizes);
        }

        $lengthValues = array_merge($pageSizes, [-1]);
        $lengthLabels = array_map('strval', $pageSizes);
        $lengthLabels[] = $allText;

        return sprintf(
            '[%s, %s]',
            json_encode($lengthValues),
            json_encode($lengthLabels, JSON_UNESCAPED_UNICODE)
        );
    }

    /**
     * Builds a single pagination link for the current URL.
     * Reproduces SmartyPage::CreatePageLink logic.
     */
    private static function pageLink(int $page, int $pageSize, string $text, string $class = 'page-link'): string
    {
        $url = ServiceLocator::GetServer()->GetUrl();
        $newUrl = SmartyPage::ReplaceQueryString($url, QueryStringKeys::PAGE, $page);
        $newUrl = SmartyPage::ReplaceQueryString($newUrl, QueryStringKeys::PAGE_SIZE, $pageSize);
        $href = htmlspecialchars($newUrl, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return sprintf('<a class="%s" href="%s" data-page="%s" data-page-size="%s">%s</a>', $class, $href, $page, $pageSize, $text);
    }

    /**
     * Exposes PHP superglobals as Twig global variables.
     * Provides `{{ server.REQUEST_URI }}` etc. as an equivalent to `{$smarty.server.*}`,
     * and `{{ cookies.X }}` as an equivalent to `{$smarty.cookies.X}`.
     *
     * @return array<string, mixed>
     */
    public function getGlobals(): array
    {
        return ['server' => $_SERVER, 'cookies' => $_COOKIE];
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

            /**
             * Renders a partial template by name, with engine-selecting fallback.
             *
             * Given a template name (typically ending in .tpl), computes the .twig candidate
             * by swapping the extension.  If the .twig file exists it is rendered via the
             * current Twig environment directly.  Otherwise the original .tpl is rendered via
             * a fresh SmartyRenderer using full-page context (equivalent to Smarty {include}).
             *
             * Using full-page Smarty context (rather than the isolated createData() context
             * used by renderControlTemplate/Controls) is intentional: sub-templates included
             * via {include} in Smarty share the calling template's {function} definitions and
             * assigned variables.  render_partial must reproduce that behaviour so that
             * templates like schedule-reservations-grid-static.tpl — which call {call} to
             * invoke slot-display functions defined in the parent — work correctly.
             *
             * Once the .twig counterpart is migrated, the Twig branch fires automatically
             * and the Smarty fallback is no longer reached.
             *
             * This is the standard mechanism for forward/cross-area includes in the Twig
             * migration: use render_partial instead of {% include 'something.tpl' %}.
             *
             * Usage in templates:
             *   {{ render_partial('Schedule/schedule-reservations-grid-static.tpl', _context) }}
             *
             * @param string              $name Template name, usually a .tpl path relative to tpl/.
             * @param array<string,mixed> $vars Variables to pass to the partial (use _context to
             *                                  forward all current page variables).
             */
            new TwigFunction(
                'render_partial',
                function (string $name, array $vars = []): string {
                    if ($this->renderer === null) {
                        return '';
                    }

                    // Delegate to TwigRenderer::renderPartial(), which computes the .twig
                    // candidate, renders via Twig if it exists, and otherwise falls back to
                    // the renderer's own SmartyRenderer using full-page context (matching
                    // Smarty {include} shared-context semantics).
                    // TwigRenderer is the only concrete renderer that adds this extension;
                    // the instanceof check is for static-analysis safety.
                    if ($this->renderer instanceof TwigRenderer) {
                        return $this->renderer->renderPartial($name, $vars);
                    }

                    // Graceful degrade for non-TwigRenderer wiring (should not occur in practice).
                    return $this->renderer->renderControlTemplate($name, $vars);
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Instantiates and renders a page Control by type name, capturing its echoed output.
             * Equivalent to SmartyPage::DisplayControl / the Smarty {control} tag.
             *
             * @param string               $type   The control class name (e.g. 'CaptchaControl').
             * @param array<string,mixed>  $params Key/value pairs forwarded to Control::Set().
             */
            new TwigFunction(
                'control',
                function (string $type, array $params = []): string {
                    require_once ROOT_DIR . "Controls/$type.php";
                    /** @var Control $control */
                    $control = new $type($this->renderer);
                    foreach ($params as $key => $val) {
                        $control->Set($key, $val);
                    }
                    ob_start();
                    $control->PageLoad();
                    return (string) ob_get_clean();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders an already-constructed DashboardItem (or any Control) by calling
             * its PageLoad() method and capturing echoed output.
             * Equivalent to `{$dashboardItem->PageLoad()}` in the Smarty dashboard template.
             */
            new TwigFunction(
                'dashboard_item',
                static function (object $item): string {
                    ob_start();
                    $item->PageLoad();
                    return (string) ob_get_clean();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders <option> elements from an associative array or parallel arrays.
             * Equivalent to Smarty's built-in {html_options}.
             *
             * Supports two calling forms (matching Smarty):
             *   - options form:       html_options(options={'key': 'label', ...}, selected=...)
             *   - values+output form: html_options(values=[...], output=[...], selected=...)
             *
             * @param mixed[] $values   Array of option value attributes (parallel-array form).
             * @param mixed[] $output   Array of option display labels (parallel-array form).
             * @param array<string|int,string> $options Associative key=>label map (options form).
             * @param mixed   $selected Currently selected value (scalar or array for multi-select).
             */
            new TwigFunction(
                'html_options',
                static function (array $values = [], array $output = [], array $options = [], mixed $selected = null): string {
                    // Use ENT_COMPAT + no double-encode to match Smarty's smarty_function_escape_special_chars.
                    $escapeAttr = static fn (string $s): string =>
                        htmlspecialchars($s, ENT_COMPAT, 'UTF-8', false);

                    // Build a value-keyed selected map (matching Smarty's $selected[$_sel] = true approach).
                    // For scalar: escape and store as a plain string for === comparison.
                    // For array: build [$escapedValue => true] map so isset() works correctly.
                    /** @var array<string,true>|null $selectedMap */
                    $selectedMap = null;
                    $escapedScalarSelected = '';
                    if (is_array($selected)) {
                        $selectedMap = [];
                        foreach ($selected as $sel) {
                            $selectedMap[$escapeAttr((string) $sel)] = true;
                        }
                    } else {
                        $escapedScalarSelected = $escapeAttr((string) ($selected ?? ''));
                    }

                    $isSelectedAttr = static function (string $escapedKey) use ($selectedMap, $escapedScalarSelected): string {
                        if ($selectedMap !== null) {
                            return isset($selectedMap[$escapedKey]) ? ' selected="selected"' : '';
                        }
                        return $escapedKey === $escapedScalarSelected ? ' selected="selected"' : '';
                    };

                    $builder = new StringBuilder();

                    if ($options !== []) {
                        // options form: assoc key => label
                        foreach ($options as $key => $label) {
                            $escapedKey = $escapeAttr((string) $key);
                            // Trailing \n matches Smarty's {html_options} byte-for-byte output.
                            $builder->Append(sprintf(
                                '<option value="%s"%s>%s</option>' . "\n",
                                $escapedKey,
                                $isSelectedAttr($escapedKey),
                                $escapeAttr((string) $label)
                            ));
                        }
                    } else {
                        // values+output form: parallel arrays
                        foreach ($values as $i => $value) {
                            $label = $output[$i] ?? $value;
                            $escapedValue = $escapeAttr((string) $value);
                            // Trailing \n matches Smarty's {html_options} byte-for-byte output.
                            $builder->Append(sprintf(
                                '<option value="%s"%s>%s</option>' . "\n",
                                $escapedValue,
                                $isSelectedAttr($escapedValue),
                                $escapeAttr((string) $label)
                            ));
                        }
                    }

                    return $builder->ToString();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns the current LibreBooking Date (server time, UTC internally).
             * Equivalent to the inline {Date::Now()} Smarty expression.
             *
             * Templates can write {{ now().Format('H:00') }},
             * {{ now().AddHours(1).Format('H:00') }}, {{ now().ToTimezone(tz) }}, etc.
             * Returns a Date object — NOT marked is_safe because it is not HTML.
             */
            new TwigFunction(
                'now',
                static function (): Date {
                    return Date::Now();
                }
            ),

            /**
             * Returns the current logged-in user's ID from the session.
             * Equivalent to ServiceLocator::GetServer()->GetUserSession()->UserId
             * used inline in view.tpl for permission checks.
             */
            new TwigFunction(
                'current_user_id',
                static function (): int {
                    return ServiceLocator::GetServer()->GetUserSession()->UserId;
                }
            ),

            /**
             * Returns the minimum/epoch Date sentinel value.
             * Equivalent to the inline {Date::Min()} Smarty expression.
             *
             * Used in templates that need a sentinel "minimum date" for comparison,
             * e.g. {% set LastDate = date_min() %} in the agenda template.
             * Returns a Date object — NOT marked is_safe because it is not HTML.
             */
            new TwigFunction(
                'date_min',
                static function (): Date {
                    return Date::Min();
                }
            ),

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

            // ── Group B: form / validation ──────────────────────────────────

            /**
             * Renders validation error list items for a named validator.
             * Equivalent to SmartyPage::Validator.
             *
             * When the validator is invalid, returns <li> elements with messages
             * (or the translated key fallback). Returns '' when valid.
             */
            new TwigFunction(
                'validator',
                function (string $id, string $key = ''): string {
                    if ($this->renderer === null) {
                        return '';
                    }
                    $validator = $this->renderer->validators()->Get($id);
                    if (!$validator->IsValid()) {
                        if ($key !== '') {
                            return '<li>' . $this->resources->GetString($key, '') . '</li>';
                        }
                        $messages = $validator->Messages();
                        if (!empty($messages)) {
                            $errors = '';
                            foreach ($messages as $message) {
                                $errors .= sprintf('<li id="%s">%s</li>', $id, $message);
                            }
                            return $errors;
                        }
                    }
                    return '';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders an async-validation placeholder list item.
             * Equivalent to SmartyPage::AsyncValidator.
             */
            new TwigFunction(
                'async_validator',
                function (string $id, string $key = ''): string {
                    $message = $key !== '' ? $this->resources->GetString($key, '') : '';
                    return sprintf('<li class="asyncValidation" id="%s">%s</li>', $id, $message);
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Wraps already-rendered content in the validation-group markup.
             * Equivalent to SmartyPage::ValidationGroup (was a Smarty block).
             *
             * Pass the pre-rendered inner content as a string; if it trims to
             * empty, returns ''. Otherwise wraps in the error-group div.
             */
            new TwigFunction(
                'validation_group',
                function (string $content, string $class = 'error'): string {
                    $actualContent = trim($content);
                    if ($actualContent === '') {
                        return '';
                    }
                    return '<div class="' . $class . ' d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
                    <div class="error-list">
                        <ul class="list-unstyled">' . $actualContent . '</ul>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a text or password input.
             * Equivalent to SmartyPage::Textbox (builds SmartyTextbox/SmartyPasswordbox).
             *
             * The `value` parameter is a Smarty template-variable name: SmartyTextbox
             * resolves it by calling getTemplateVars($value) on the renderer, falling
             * back to the posted form value.  Pass null/'' to emit an empty input.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'textbox',
                function (
                    string $name,
                    string $type = 'text',
                    string $id = '',
                    mixed $value = null,
                    string $class = '',
                    string $placeholderkey = '',
                    mixed $required = false,
                    array $attributes = []
                ): string {
                    // Replicate SmartyPage::Textbox: build $params then call AppendAttributes
                    // AppendAttributes excludes ['value', 'type', 'name', 'placeholderkey', 'required'];
                    // everything else (class, id, size, tabindex, placeholder, …) becomes attributes.
                    $cssClass = $class !== '' ? $class . ' form-control form-control-sm' : 'form-control form-control-sm';

                    $type = strtolower($type === '' ? 'text' : $type);
                    $isRequired = (bool) $required;

                    // Build the synthetic $params array in insertion order, mirroring Smarty tag order.
                    //
                    // SmartyPage::Textbox adds 'class' and 'placeholder' to $params AFTER reading the
                    // tag attributes.  PHP associative-array semantics mean:
                    //   - If 'class' was already a key (explicitly in the tag), assigning it modifies
                    //     in-place — so class stays BEFORE any subsequent data-bv-* attributes.
                    //   - If 'class' was absent, it is appended at the END of $params —  so class ends
                    //     up AFTER data-bv-* attributes but BEFORE 'placeholder' (which is added next).
                    //   - 'placeholder' (from placeholderkey) is always appended AFTER class.
                    //
                    // Mirror this insertion order here:
                    //   class-provided  → class, id?, placeholder?, extras
                    //   class-absent    → id?,  extras,  class,  placeholder?
                    $params = [];

                    $classWasProvided = $class !== '';
                    if ($classWasProvided) {
                        $params['class'] = $cssClass;
                    }

                    if ($id !== '') {
                        $params['id'] = $id;
                    }

                    foreach ($attributes as $k => $v) {
                        $params[$k] = $v;
                    }

                    if (!$classWasProvided) {
                        $params['class'] = $cssClass;
                    }

                    if ($placeholderkey !== '') {
                        $params['placeholder'] = $this->resources->GetString($placeholderkey);
                    }

                    $extraStr = self::buildAttributes($params);

                    if ($type === 'password') {
                        $box = new SmartyPasswordbox($name, 'password', $id ?: null, $value, $extraStr, $isRequired, $this->renderer);
                    } else {
                        $box = new SmartyTextbox($name, $type, $id ?: null, $value, $extraStr, $isRequired, $this->renderer);
                    }

                    return $box->Html();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Builds <option> elements from an object list.
             * Equivalent to SmartyPage::ObjectHtmlOptions (pure — no renderer needed).
             *
             * @param object[] $options    List of objects.
             * @param string   $key        Property/method name for the option value.
             * @param string   $label      Property/method name for the option label.
             * @param bool     $usemethod  Whether to call as a method (true) or access as property (false).
             * @param mixed    $selected   Currently selected value.
             */
            new TwigFunction(
                'object_html_options',
                function (array $options, string $key, string $label, bool $usemethod = true, mixed $selected = ''): string {
                    $builder = new StringBuilder();
                    foreach ($options as $option) {
                        $_key   = $usemethod ? $option->$key() : $option->$key;
                        $_label = $usemethod ? $option->$label() : $option->$label;
                        $isSelected = ($_key == $selected) ? 'selected="selected"' : '';
                        $_key   = self::escapeOnce($_key);
                        $_label = self::escapeOnce($_label);
                        $builder->Append(sprintf(
                            '<option label="%s" value="%s"%s>%s</option>',
                            $_label,
                            $_key,
                            $isSelected,
                            $_label
                        ));
                    }
                    return $builder->ToString();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Emits a focus script for a form element.
             * Equivalent to SmartyPage::SetFocus.
             *
             * Pass either `key` (resolved via FormKeys::Evaluate) or `id` directly.
             */
            new TwigFunction(
                'setfocus',
                function (string $key = '', string $id = ''): string {
                    $elementId = $key !== '' ? FormKeys::Evaluate($key) : $id;
                    return "<script type=\"text/javascript\">document.getElementById('$elementId').focus();</script>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns a name="..." attribute string for a form field.
             * Equivalent to SmartyPage::GetFormName.
             *
             * @param bool $multi When true, appends [] to support array inputs.
             */
            new TwigFunction(
                'formname',
                function (string $key, bool $multi = false): string {
                    $append = $multi ? '[]' : '';
                    return 'name=\'' . FormKeys::Evaluate($key) . $append . '\'';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a hidden CSRF token input.
             * Equivalent to SmartyPage::CSRFToken (echoed in Smarty; returned here).
             */
            new TwigFunction(
                'csrf_token',
                function (): string {
                    $token = ServiceLocator::GetServer()->GetUserSession()->CSRFToken;
                    return '<input type="hidden" id="csrf_token" name="' . FormKeys::CSRF_TOKEN . '" value="' . $token . '"/>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders the display value for a read-only custom attribute.
             * Equivalent to SmartyPage::ReadOnlyAttribute (echoed in Smarty; returned here).
             *
             * For CHECKBOX attributes renders Yes/No via Resources; otherwise the raw value.
             */
            new TwigFunction(
                'read_only_attribute',
                function (mixed $value, mixed $attribute): string {
                    if ($attribute->Type() == CustomAttributeTypes::CHECKBOX) {
                        return $value == 1
                            ? Resources::GetInstance()->GetString('Yes')
                            : Resources::GetInstance()->GetString('No');
                    }
                    return self::escapeOnce($value);
                },
                ['is_safe' => ['html']]
            ),

            // ── Group C: links, URLs, images, formatting, utility ────────────

            /**
             * Renders a navigation link with a people icon.
             * Equivalent to SmartyPage::PrintLink (returns HTML string).
             *
             * Href starting with '/' is used as-is; otherwise rootPath is prepended.
             * Title defaults to the resource string for `key` when not provided.
             *
             * @param array<string,mixed> $attributes Extra HTML attributes passed through verbatim.
             */
            new TwigFunction(
                'html_link',
                function (string $key, string $href, ?string $title = null, array $attributes = []): string {
                    $string = $this->resources->GetString($key);
                    $titleStr = $title !== null ? $this->resources->GetString($title) : $string;

                    if (!str_starts_with($href, '/')) {
                        $href = $this->rootPath . $href;
                    }

                    $extra = self::buildAttributes($attributes);
                    return "<a href=\"$href\" class=\"link-primary\" title=\"$titleStr\" $extra><i class=\"bi bi-people-fill me-1\"></i>$string</a>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Appends a named query-string parameter to the current page URL.
             * Equivalent to SmartyPage::AddQueryString.
             *
             * `key` is the name of a constant on QueryStringKeys (e.g. 'SORT_DIRECTION').
             */
            new TwigFunction(
                'add_querystring',
                function (string $key, string $value): string {
                    $url = new Url(ServiceLocator::GetServer()->GetUrl());
                    $name = constant(sprintf('QueryStringKeys::%s', $key));
                    $url->AddQueryString($name, $value);
                    return $url->ToString();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a sortable-column header link with caret indicator.
             * Equivalent to SmartyPage::SortColumn (echoed in Smarty; returned here).
             *
             * Reads current sort state from the request URL and toggles direction
             * for the active field.
             */
            new TwigFunction(
                'sort_column',
                function (string $field, string $key): string {
                    $server = ServiceLocator::GetServer();
                    $url = $server->GetRequestUri();
                    $sortField = $field;
                    $sortDirection = 'asc';
                    $currentDirection = $server->GetQuerystring(QueryStringKeys::SORT_DIRECTION);
                    $currentField = $server->GetQuerystring(QueryStringKeys::SORT_FIELD);
                    $hasQueryString = BookedStringHelper::Contains($url, '?');
                    $sd = QueryStringKeys::SORT_DIRECTION;
                    $sf = QueryStringKeys::SORT_FIELD;
                    $indicator = '';
                    if ($sortField == $currentField) {
                        $sortDirection = $currentDirection == 'asc' ? 'desc' : 'asc';
                        $indicator = '<i class="bi bi-caret-down-fill"></i>';
                        if ($currentDirection == 'asc') {
                            $indicator = '<i class="bi bi-caret-up-fill"></i>';
                        }
                    }
                    if (BookedStringHelper::Contains($url, $sd)) {
                        $url = preg_replace("/$sd=(asc|desc)&?/", "$sd=$sortDirection&", (string) $url);
                    } else {
                        $url = $url . ($hasQueryString ? '&' : '?') . "$sd=$sortDirection";
                    }
                    if (BookedStringHelper::Contains($url, $sf)) {
                        $url = preg_replace("/$sf=[a-zA-Z0-9_\\-]+&?/", "$sf=$sortField&", (string) $url);
                    } else {
                        $url = "$url&$sf=$sortField";
                    }
                    return '<a href="' . $url . '">' . $this->resources->GetString($key) . ' ' . $indicator . '</a>';
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns the absolute URL for a resource (uploaded) image.
             * Equivalent to SmartyPage::GetResourceImage.
             *
             * When the configured image URL does not start with 'http://', the
             * application's script URL is prepended.
             */
            new TwigFunction(
                'resource_image',
                function (string $image): string {
                    $imageUrl = Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_IMAGE_URL);
                    if (!str_contains((string) $imageUrl, 'http://')) {
                        $imageUrl = Configuration::Instance()->GetScriptUrl() . "/$imageUrl";
                    }
                    return "$imageUrl/$image";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns the formatted full name, respecting the privacy-hide-user-details setting.
             * Equivalent to SmartyPage::DisplayFullName.
             *
             * When privacy is enabled and the current user is not an admin, returns the
             * translated 'Private' string. Pass `ignorePrivacy=true` to bypass the check.
             */
            new TwigFunction(
                'fullname',
                function (string $first, string $last, bool $ignorePrivacy = false): string {
                    $config = Configuration::Instance();
                    if (!$ignorePrivacy && $config->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter()) && !ServiceLocator::GetServer()->GetUserSession()->IsAdmin) {
                        return $this->resources->GetString('Private');
                    }
                    $fullName = new FullName($first, $last);
                    // Names are stored either HTML-encoded or raw, so do not encode existing entities
                    return htmlspecialchars($fullName->__toString(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Formats a Date (or date string) using a resource date format.
             * Equivalent to SmartyPage::FormatDate.
             *
             * Returns '' when `date` is empty. Accepts an explicit `format` string
             * or a resource `key` (default 'general_date'). Applies optional
             * `timezone` conversion. Translates day names when the format contains 'l'.
             */
            new TwigFunction(
                'formatdate',
                function (mixed $date = null, ?string $timezone = null, ?string $format = null, string $key = 'general_date'): string {
                    if ($date === null || $date === '') {
                        return '';
                    }
                    $date = is_string($date) ? Date::Parse($date) : $date;
                    $date = $timezone !== null ? $date->ToTimezone($timezone) : $date;
                    if ($format !== null) {
                        return $date->Format($format);
                    }
                    $fmt = $this->resources->GetDateFormat($key);
                    $formatted = $date->Format($fmt);
                    if (str_contains((string) $fmt, 'l')) {
                        $englishDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $days = $this->resources->GetDays('full');
                        $formatted = str_replace($englishDays[$date->Weekday()], $days[$date->Weekday()], $formatted);
                    }
                    return $formatted;
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Alias for `formatdate` — same implementation, second name for template compat.
             * Equivalent to SmartyPage::FormatDate.
             */
            new TwigFunction(
                'format_date',
                function (mixed $date = null, ?string $timezone = null, ?string $format = null, string $key = 'general_date'): string {
                    if ($date === null || $date === '') {
                        return '';
                    }
                    $date = is_string($date) ? Date::Parse($date) : $date;
                    $date = $timezone !== null ? $date->ToTimezone($timezone) : $date;
                    if ($format !== null) {
                        return $date->Format($format);
                    }
                    $fmt = $this->resources->GetDateFormat($key);
                    $formatted = $date->Format($fmt);
                    if (str_contains((string) $fmt, 'l')) {
                        $englishDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $days = $this->resources->GetDays('full');
                        $formatted = str_replace($englishDays[$date->Weekday()], $days[$date->Weekday()], $formatted);
                    }
                    return $formatted;
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Formats a monetary amount using the current locale's NumberFormatter.
             * Equivalent to SmartyPage::FormatCurrency (echoed in Smarty; returned here).
             *
             * Falls back to a plain USD string when the `intl` extension is not available.
             */
            new TwigFunction(
                'formatcurrency',
                function (mixed $amount = null, string $currency = 'USD'): string {
                    $amount = isset($amount) && is_numeric($amount) ? floatval($amount) : 0.0;
                    if (!class_exists('NumberFormatter')) {
                        if ($currency == 'USD') {
                            return '$' . number_format($amount, 2) . ' USD';
                        }
                        return 'We cannot format this currency. <a href="http://php.net/manual/en/book.intl.php">You must enable internationalization</a>.';
                    }
                    $fmt = new NumberFormatter($this->resources->CurrentLanguage, NumberFormatter::CURRENCY);
                    return (string) $fmt->formatCurrency($amount, $currency);
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Converts a PHP array to a JavaScript array literal string.
             * Equivalent to SmartyPage::CreateJavascriptArray.
             *
             * @param mixed[] $array
             */
            new TwigFunction(
                'js_array',
                function (array $array): string {
                    $string = implode('","', $array);
                    return "[\"$string\"]";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns a newline character.
             * Equivalent to SmartyPage::LineBreak.
             */
            new TwigFunction(
                'linebreak',
                static function (): string {
                    return "\n";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Flushes the output buffer and returns the flushing marker comment.
             * Equivalent to SmartyPage::Flush (which echoes the comment).
             */
            new TwigFunction(
                'flush',
                static function (): string {
                    flush();
                    return '<!-- flushing -->';
                },
                ['is_safe' => ['html']]
            ),

            // ── Group D: asset includes & datatables ────────────────────────

            /**
             * Renders a <script> tag for an application JS file.
             * Equivalent to SmartyPage::IncludeJavascriptFile (echoed; returned here).
             *
             * Prepends rootPath + 'scripts/' and appends the version query string.
             * Pass `async=true` to add the async attribute.
             */
            new TwigFunction(
                'jsfile',
                function (string $src, bool $async = false): string {
                    $versionNumber = Configuration::VERSION;
                    $asyncAttr = $async ? ' async' : '';
                    return "<script type=\"text/javascript\" src=\"{$this->rootPath}scripts/{$src}?v={$versionNumber}\"{$asyncAttr}></script>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a <link> tag for an application CSS file.
             * Equivalent to SmartyPage::IncludeCssFile (echoed; returned here).
             *
             * When `src` contains no '/', prepends 'css/' automatically.
             */
            new TwigFunction(
                'cssfile',
                function (string $src): string {
                    $versionNumber = Configuration::VERSION;
                    if (!BookedStringHelper::Contains($src, '/')) {
                        $src = "css/{$src}";
                    }
                    return "<link rel='stylesheet' type='text/css' href='{$this->rootPath}{$src}?v={$versionNumber}'/>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a <script> tag for a vendor JS file.
             * Equivalent to SmartyPage::IncludeVendorJavascriptFile (echoed; returned here).
             *
             * Prepends rootPath + 'assets/vendor/' and appends the version query string.
             * Pass `async=true` to add the async attribute.
             */
            new TwigFunction(
                'vendor_js',
                function (string $src, bool $async = false): string {
                    $versionNumber = Configuration::VERSION;
                    $asyncAttr = $async ? ' async' : '';
                    return "<script type=\"text/javascript\" src=\"{$this->rootPath}assets/vendor/{$src}?v={$versionNumber}\"{$asyncAttr}></script>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders a <link> tag for a vendor CSS file.
             * Equivalent to SmartyPage::IncludeVendorCssFile (echoed; returned here).
             *
             * Prepends rootPath + 'assets/vendor/' and appends the version query string.
             */
            new TwigFunction(
                'vendor_css',
                function (string $src): string {
                    $versionNumber = Configuration::VERSION;
                    return "<link rel='stylesheet' type='text/css' href='{$this->rootPath}assets/vendor/{$src}?v={$versionNumber}'/>";
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders the DataTable initialisation <script> block.
             * Equivalent to SmartyPage::CreateDataTable (already returns).
             *
             * The `report-results` tableId uses a minimal, non-paginating config;
             * all other ids use the configurable page-size and length menu.
             */
            new TwigFunction(
                'datatable',
                function (string $tableId): string {
                    $searchText = $this->resources->GetString('Filter');
                    $noResultsFoundText = $this->resources->GetString('NoResultsFound');
                    $copyText = $this->resources->GetString('Copy');
                    $exportText = $this->resources->GetString('Export');
                    $printText = $this->resources->GetString('Print');
                    $showHideText = $this->resources->GetString('ShowHide');
                    $infoText = $this->resources->GetString('Info');
                    $lengthMenuText = $this->resources->GetString('LengthMenu');

                    if ($tableId == 'report-results') {
                        $pagination = '"paging": false,
                "lengthChange": false,
                "searching": false,
                "info": false,
                "ordering": false,';
                    } else {
                        $pagination = '"paging": false, "info": false,';
                    }

                    return sprintf(
                        '<script>
           var table =  new DataTable("#' . $tableId . '", {
                "searching": false,
                "dom": \'<"d-flex justify-content-center flex-wrap"B><"d-flex justify-content-between flex-wrap mt-2"fil>rt<"d-flex justify-content-center"i><"d-flex justify-content-center"p><"clear">\',
                ' . $pagination . '
                "language": {
                    search: "' . $searchText . '",
                    info: "' . $infoText . '",
                    infoEmpty: "' . $noResultsFoundText . '",
                    infoFiltered: "",
                    lengthMenu: "' . $lengthMenuText . '",
                    zeroRecords: "' . $noResultsFoundText . '",
                },
                "buttons": [
                    {
                        extend: "copyHtml5",
                        text: "<i class=\"bi bi-copy me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $copyText . '</div>",
                    },
                    {
                        extend: "excelHtml5",
                        text: "<i class=\"bi bi-file-earmark-spreadsheet me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $exportText . ' Excel</div>",
                    },
                    {
                        extend: "pdfHtml5",
                        text: "<i class=\"bi bi-filetype-pdf me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $exportText . ' PDF</div>",
                    },
                    {
                        extend: "print",
                        text: "<i class=\"bi bi-printer me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $printText . '</div>",
                    },
                    {
                        extend: "colvis",
                        text: "<i class=\"bi bi-list-check me-1\"></i><div class=\"d-none d-sm-inline-block\">' . $showHideText . '</div>",
                    }
                ],
                "initComplete": function(settings, json) {
                    $(".dt-buttons .btn-secondary").removeClass("btn-secondary").addClass("btn-primary");
                    $(".dt-buttons").addClass("btn-group-sm");
                    $(".buttons-collection").addClass("btn-sm");
                },
                "drawCallback": function (settings) {
                    if (typeof setUpEditables !== "undefined") {
                        setUpEditables();
                    }
                }
            });
        </script>
        '
                    );
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders the DataTable filter initialisation <script> block.
             * Equivalent to SmartyPage::CreateDataTableFilter (already returns).
             *
             * Uses a compact dom layout with built-in search/filter input.
             */
            new TwigFunction(
                'datatablefilter',
                function (string $tableId): string {
                    $searchText = $this->resources->GetString('Filter');
                    $viewAllText = $this->resources->GetString('All');
                    $noResultsFoundText = $this->resources->GetString('NoResultsFound');
                    $infoText = $this->resources->GetString('Info');
                    $lengthMenuText = $this->resources->GetString('LengthMenu');
                    $defaultPageSize = $this->getDefaultDataTablePageSize();
                    $lengthMenu = $this->buildDataTableLengthMenu($viewAllText);

                    return sprintf(
                        '<script>
           const tableFilter =  new DataTable("#' . $tableId . '", {
                "dom": \'<"d-flex justify-content-between my-1"fl><t>t<"d-flex justify-content-center"i><"d-flex justify-content-center"p><"clear">\',
                "pageLength": ' . $defaultPageSize . ',
                "lengthMenu": ' . $lengthMenu . ',
                language: {
                    search: "' . $searchText . '",
                    info: "' . $searchText . '",
                    infoEmpty: "' . $infoText . '",
                    infoFiltered: "",
                    lengthMenu: "' . $lengthMenuText . '",
                    zeroRecords: "' . $noResultsFoundText .
                        '"
                },
                "drawCallback": function (settings) {
                    if (typeof setUpEditables !== "undefined") {
                        setUpEditables();
                    }
                }
            });
        </script>
        '
                    );
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Renders server-side pagination links for a PageInfo.
             * Equivalent to SmartyPage::CreatePagination.
             */
            new TwigFunction(
                'pagination',
                function (?PageInfo $pageInfo, bool $showCount = true): string {
                    if ($pageInfo === null || empty($pageInfo->Total)) {
                        return '';
                    }

                    $sb = new StringBuilder();

                    if ($showCount) {
                        $sb->Append('<div class="pagination-rows text-center mt-2">');
                        $sb->Append($this->resources->GetString('Rows'));
                        $sb->Append(": {$pageInfo->ResultsStart} - {$pageInfo->ResultsEnd} ({$pageInfo->Total})");
                        $sb->Append('<span>&nbsp;</span>');
                        if ($pageInfo->TotalPages != 1) {
                            $sb->Append(self::pageLink(1, -1, $this->resources->GetString('ViewAll'), 'link-primary'));
                        }
                        $sb->Append('</div>');
                    }
                    $size = (int) $pageInfo->PageSize;
                    $currentPage = (int) $pageInfo->CurrentPage;
                    $totalPages = (int) $pageInfo->TotalPages;

                    $sb->Append('<ul class="pagination justify-content-center flex-wrap">');
                    $sb->Append('<li class="page-item">');
                    $sb->Append(self::pageLink(max(1, $currentPage - 1), $size, '&laquo;'));
                    $sb->Append('</li>');

                    for ($i = 1; $i <= $totalPages; $i++) {
                        $sb->Append($i == $currentPage ? '<li class="active page-item">' : '<li class="page-item">');
                        $sb->Append(self::pageLink($i, $size, (string) $i));
                        $sb->Append('</li>');
                    }
                    $sb->Append('<li class="page-item">');
                    $sb->Append(self::pageLink(min($totalPages, $currentPage + 1), $size, '&raquo;'));
                    $sb->Append('</li>');
                    $sb->Append('</ul>');

                    return $sb->ToString();
                },
                ['is_safe' => ['html']]
            ),

            /**
             * Returns the raw date-format string for a resource date-format key.
             * Equivalent to the inline Smarty expression
             * {Resources::GetInstance()->GetDateFormat('...')}.
             *
             * Unlike formatdate(), this returns the format pattern itself (for
             * client-side date formatting), not a formatted date.
             */
            new TwigFunction(
                'date_format',
                function (string $key): string {
                    return (string) $this->resources->GetDateFormat($key);
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
            // Marked is_safe so Twig's autoescape does not double-encode the
            // &#39; and &quot; entities produced by this filter.
            new TwigFilter('escapequotes', static function (mixed $var): string {
                $str = str_replace('\'', '&#39;', (string) $var);
                return str_replace('"', '&quot;', $str);
            }, ['is_safe' => ['html']]),

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

            // Escapes a string for safe embedding inside a JavaScript string literal.
            // Equivalent to Smarty's |escape:'javascript' modifier: escapes backslashes,
            // single quotes, double quotes, newlines, and HTML script-closing sequences,
            // but does NOT Unicode-escape hyphens or slashes (unlike Twig's built-in |e('js')).
            new TwigFilter('escape_js', static function (mixed $value): string {
                return strtr((string) $value, [
                    '\\' => '\\\\',
                    "'"  => "\\'",
                    '"'  => '\\"',
                    "\r" => '\\r',
                    "\n" => '\\n',
                    '</' => '<\/',
                    '<!--' => '<\!--',
                    '<s' => '<\s',
                    '<S' => '<\S',
                    '`' => '\\\\`',
                    '${' => '\\\\\\$\\{',
                ]);
            }, ['is_safe' => ['html']]),

            // Encodes a value as JSON.
            // Equivalent to Smarty's |json_encode modifier.
            // Marked is_safe: the HEX flags encode < > & ' " so the output is safe in
            // HTML and inside <script> blocks.
            new TwigFilter('json_encode', static function (mixed $value): string {
                return (string) json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
            }, ['is_safe' => ['html']]),

            // Truncates a string to a maximum length, appending a suffix.
            // Equivalent to Smarty's |truncate modifier (single-byte-safe via mb_strlen/mb_substr).
            // NOT marked is_safe — callers should pipe |nl2br or |escape as needed.
            new TwigFilter('truncate', static function (mixed $text, int $length = 80, string $suffix = '...'): string {
                $str = (string) $text;
                if (mb_strlen($str) > $length) {
                    return mb_substr($str, 0, $length) . $suffix;
                }
                return $str;
            }),

            // NOTE: The following Smarty modifiers are intentionally NOT added
            // as custom Twig filters because Twig provides equivalent built-ins:
            //   strtolower  → Twig native |lower
            //   count       → Twig native |length
        ];
    }
}
