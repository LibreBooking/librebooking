<?php

declare(strict_types=1);

namespace LibreBooking\Common\Templating;

/**
 * Renders templates for pages, controls and emails.
 *
 * The methods follow what SmartyPage offers, so existing call sites can use a
 * renderer without knowing which template engine is behind it.
 *
 * Implementations wrap their engine. SmartyPage cannot implement this
 * interface itself, because its untyped methods of the same names would
 * conflict with the typed ones declared here.
 */
interface TemplateRenderer
{
    /**
     * Makes a variable available to the templates rendered afterwards.
     *
     * Control templates are the exception: see renderControlTemplate().
     */
    public function assign(string $name, mixed $value): void;

    /**
     * Renders a template and returns its output.
     *
     * Whether the passed variables are still assigned after the call depends
     * on the engine, so callers must not rely on either behaviour.
     *
     * @param array<string, mixed> $vars variables for the template, in
     *                                   addition to the assigned ones
     */
    public function render(string $templateName, array $vars = []): string;

    /**
     * Renders a template with the assigned variables and prints its output.
     */
    public function display(string $templateName): void;

    /**
     * Renders a template with the assigned variables and returns its output.
     */
    public function fetch(string $templateName): string;

    /**
     * Returns the value of an assigned variable, or null when it was never
     * assigned. Without a name, returns all assigned variables as an array
     * keyed by name.
     */
    public function getTemplateVars(?string $name = null): mixed;

    /**
     * Renders a template from the renderer's language directory and returns
     * its output.
     *
     * A renderer serves one language: the one that was current when it was
     * created. There is no way to ask for another one. When a CurrentLanguage
     * variable is assigned, it must name that language.
     *
     * A site can customize a template by adding a file with the same name and
     * a "-custom" suffix next to it. The lookup has two steps:
     *
     * 1. A directory is chosen: lang/<language>/ when it has the template or
     *    a "-custom" file for it, otherwise lang/en_us/.
     * 2. In that directory the "-custom" file is used when there is one,
     *    otherwise the standard template.
     *
     * A template that is in neither language directory is still found when
     * one of the regular template directories has it. Report emails rely on
     * this for Reports/custom-csv.tpl.
     *
     * $enforceCustomTemplate changes step 1 in one case. When it is true and
     * the renderer's language has no "-custom" file while the configured
     * default language has one, the directory of the default language is
     * chosen. The customized wording then wins over the standard template of
     * the renderer's language, even though it is in another language. When it
     * is false, the two steps apply as described.
     */
    public function fetchLocalized(string $templateName, bool $enforceCustomTemplate): string;

    /**
     * Adds a directory in which templates are looked up.
     */
    public function addTemplateDirectory(string $dir): void;

    /**
     * Renders the template of a control and returns its output.
     *
     * Whether the assigned variables are visible to the template depends on
     * the engine, so a control template must use only the variables passed
     * here.
     *
     * @param array<string, mixed> $vars the variables set on the control
     */
    public function renderControlTemplate(string $templateName, array $vars): string;

    /**
     * Returns the validators registered for the page being rendered.
     */
    public function validators(): \PageValidators;

    /**
     * Runs the registered validators and returns whether all of them passed.
     */
    public function isValid(): bool;
}
