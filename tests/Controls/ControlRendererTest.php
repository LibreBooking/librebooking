<?php

declare(strict_types=1);

require_once ROOT_DIR . 'vendor/autoload.php';
require_once ROOT_DIR . 'lib/Common/namespace.php';
require_once ROOT_DIR . 'Controls/Control.php';

use LibreBooking\Common\Templating\TemplateRenderer;
use PHPUnit\Framework\TestCase;

class ControlRendererTest extends TestCase
{
    // ---------------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------------

    /**
     * Builds a concrete Control subclass that exposes protected members for
     * testing. The optional $renderer defaults to a fresh SmartyRenderer.
     *
     * @param TemplateRenderer|SmartyPage|null $renderer
     */
    private function makeControl(TemplateRenderer|SmartyPage|null $renderer = null): object
    {
        $renderer ??= new SmartyRenderer();

        return new class ($renderer) extends Control {
            public function PageLoad(): void
            {
            }

            public function publicSet(string $var, mixed $value): void
            {
                $this->Set($var, $value);
            }

            public function publicGet(string $var): mixed
            {
                return $this->Get($var);
            }

            public function publicDisplay(string $name): void
            {
                $this->Display($name);
            }

            public function getRenderer(): TemplateRenderer
            {
                return $this->renderer;
            }
        };
    }

    /**
     * Builds a fake TemplateRenderer that records the last renderControlTemplate
     * call and returns a sentinel string.
     *
     * @param string|null &$capturedName  Set to the $templateName argument
     * @param array|null  &$capturedVars  Set to the $vars argument
     */
    private function makeFakeRenderer(?string &$capturedName, ?array &$capturedVars): TemplateRenderer
    {
        return new class ($capturedName, $capturedVars) implements TemplateRenderer {
            private ?string $nameRef;
            private ?array $varsRef;

            /** @param string|null &$nameRef @param array|null &$varsRef */
            public function __construct(?string &$nameRef, ?array &$varsRef)
            {
                $this->nameRef = &$nameRef;
                $this->varsRef = &$varsRef;
            }

            public function assign(string $name, mixed $value): void
            {
            }

            public function render(string $templateName, array $vars = []): string
            {
                return '';
            }

            public function display(string $templateName): void
            {
            }

            public function fetch(string $templateName): string
            {
                return '';
            }

            public function getTemplateVars(?string $name = null): mixed
            {
                return null;
            }

            public function fetchLocalized(
                string $templateName,
                bool $enforceCustomTemplate,
                ?string $languageCode = null
            ): string {
                return '';
            }

            public function addTemplateDirectory(string $dir): void
            {
            }

            public function renderControlTemplate(string $templateName, array $vars): string
            {
                $this->nameRef = $templateName;
                $this->varsRef = $vars;
                return 'RENDERED';
            }

            public function validators(): \PageValidators
            {
                // Never called in these tests; satisfy the interface type contract.
                throw new \LogicException('validators() must not be called in ControlRendererTest');
            }

            public function isValid(): bool
            {
                return true;
            }
        };
    }

    // ---------------------------------------------------------------------------
    // Construction assertions (kept from original)
    // ---------------------------------------------------------------------------

    public function testAcceptsSmartyRenderer(): void
    {
        $control = $this->makeControl(new SmartyRenderer());
        $this->assertInstanceOf(Control::class, $control);
    }

    public function testStillAcceptsRawSmartyPageForBackCompat(): void
    {
        $control = $this->makeControl(new SmartyPage());
        $this->assertInstanceOf(Control::class, $control);
    }

    // ---------------------------------------------------------------------------
    // 1. Set / Get round-trip
    // ---------------------------------------------------------------------------

    public function testSetGetRoundTrip(): void
    {
        $control = $this->makeControl();

        $control->publicSet('x', 42);

        $this->assertSame(42, $control->publicGet('x'));
    }

    public function testGetMissingKeyReturnsNull(): void
    {
        $control = $this->makeControl();

        $this->assertNull($control->publicGet('missing'));
    }

    // ---------------------------------------------------------------------------
    // 2. Display dispatches to the renderer
    // ---------------------------------------------------------------------------

    public function testDisplayDelegatesToRendererAndEchoesSentinel(): void
    {
        $capturedName = null;
        $capturedVars = null;
        $fakeRenderer = $this->makeFakeRenderer($capturedName, $capturedVars);

        $control = $this->makeControl($fakeRenderer);
        $control->publicSet('x', 42);

        ob_start();
        $control->publicDisplay('some.tpl');
        $output = ob_get_clean();

        $this->assertSame('RENDERED', $output);
        $this->assertSame('some.tpl', $capturedName);
        $this->assertSame(['x' => 42], $capturedVars);
    }

    public function testDisplayPassesAllSetVarsToRenderer(): void
    {
        $capturedName = null;
        $capturedVars = null;
        $fakeRenderer = $this->makeFakeRenderer($capturedName, $capturedVars);

        $control = $this->makeControl($fakeRenderer);
        $control->publicSet('a', 'hello');
        $control->publicSet('b', true);
        $control->publicSet('c', [1, 2, 3]);

        ob_start();
        $control->publicDisplay('multi.tpl');
        ob_get_clean();

        $this->assertSame(['a' => 'hello', 'b' => true, 'c' => [1, 2, 3]], $capturedVars);
    }

    // ---------------------------------------------------------------------------
    // 3. Raw SmartyPage path wraps into a SmartyRenderer
    // ---------------------------------------------------------------------------

    public function testRawSmartyPageIsWrappedInSmartyRenderer(): void
    {
        $control = $this->makeControl(new SmartyPage());

        $this->assertInstanceOf(SmartyRenderer::class, $control->getRenderer());
    }

    public function testSmartyRendererPassedDirectlyIsStoredAsIs(): void
    {
        $renderer = new SmartyRenderer();
        $control   = $this->makeControl($renderer);

        $this->assertSame($renderer, $control->getRenderer());
    }
}
