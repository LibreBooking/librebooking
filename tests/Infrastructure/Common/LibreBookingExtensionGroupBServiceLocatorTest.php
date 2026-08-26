<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Group B tests that require ServiceLocator/FakeServer wiring (textbox, csrf_token).
 * Extends TestBase so setUp() populates ServiceLocator automatically.
 */
class LibreBookingExtensionGroupBServiceLocatorTest extends TestBase
{
    private function makeEnvWithRenderer(string $template, ?\LibreBooking\Common\Templating\TemplateRenderer $renderer = null): \Twig\Environment
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), '', $renderer));
        return $env;
    }

    // --- csrf_token ----------------------------------------------------------

    public function testCsrfTokenMatchesSmartyPage(): void
    {
        // FakeServer/FakeUserSession set up by TestBase::setUp()
        $this->fakeUser->CSRFToken = 'test-csrf-xyz';

        $env = $this->makeEnvWithRenderer('{{ csrf_token() }}');
        $actual = $env->render('t');

        $page = new SmartyPage();
        ob_start();
        $page->CSRFToken([], null);
        $expected = ob_get_clean();

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('csrf_token', $actual);
        $this->assertStringContainsString('test-csrf-xyz', $actual);
        $this->assertStringContainsString('type="hidden"', $actual);
    }

    public function testCsrfTokenContainsFormKeyName(): void
    {
        $this->fakeUser->CSRFToken = 'abc123';

        $env = $this->makeEnvWithRenderer('{{ csrf_token() }}');
        $actual = $env->render('t');

        $this->assertStringContainsString('name="' . FormKeys::CSRF_TOKEN . '"', $actual);
        $this->assertStringContainsString('value="abc123"', $actual);
    }

    // --- textbox (text type) -------------------------------------------------

    public function testTextboxBasicTextMatchesSmartyPage(): void
    {
        // No posted value, no template var → empty value
        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL') }}", $this->buildFakeRenderer());
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $page->Textbox(['name' => 'EMAIL'], null);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('type="text"', $actual);
        $this->assertStringContainsString('class="form-control form-control-sm"', $actual);
    }

    public function testTextboxWithClassMatchesSmartyPage(): void
    {
        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL', class='my-class') }}", $this->buildFakeRenderer());
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $page->Textbox(['name' => 'EMAIL', 'class' => 'my-class'], null);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('my-class form-control form-control-sm', $actual);
    }

    public function testTextboxPasswordTypeMatchesSmartyPage(): void
    {
        $env = $this->makeEnvWithRenderer("{{ textbox('PASSWORD', type='password') }}", $this->buildFakeRenderer());
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $page->Textbox(['name' => 'PASSWORD', 'type' => 'password'], null);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('type="password"', $actual);
    }

    public function testTextboxWithRequiredMatchesSmartyPage(): void
    {
        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL', required=true) }}", $this->buildFakeRenderer());
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $page->Textbox(['name' => 'EMAIL', 'required' => true], null);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('required="required"', $actual);
    }

    public function testTextboxWithPlaceholderkeyMatchesSmartyPage(): void
    {
        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL', placeholderkey='EmailAddress') }}", $this->buildFakeRenderer());
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $page->Textbox(['name' => 'EMAIL', 'placeholderkey' => 'EmailAddress'], null);

        $this->assertSame($expected, $actual);
    }

    public function testTextboxWithTemplateVarValue(): void
    {
        $renderer = $this->buildFakeRenderer(['EmailValue' => 'user@example.com']);

        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL', value='EmailValue') }}", $renderer);
        $actual = $env->render('t');

        $page = new SmartyPage();
        $page->assign('EmailValue', 'user@example.com');
        $expected = $page->Textbox(['name' => 'EMAIL', 'value' => 'EmailValue'], $page);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('value="user@example.com"', $actual);
    }

    public function testTextboxWithPostedValueOverridesTemplateVar(): void
    {
        // POST value takes priority over template var
        $this->fakeServer->SetForm(FormKeys::EMAIL, 'posted@example.com');
        $renderer = $this->buildFakeRenderer(['EmailValue' => 'template@example.com']);

        $env = $this->makeEnvWithRenderer("{{ textbox('EMAIL', value='EmailValue') }}", $renderer);
        $actual = $env->render('t');

        $this->assertStringContainsString('value="posted@example.com"', $actual);
    }

    // -------------------------------------------------------------------------

    /**
     * Build a minimal fake TemplateRenderer for textbox tests.
     * @param array<string,mixed> $vars Template vars to expose via getTemplateVars.
     */
    private function buildFakeRenderer(array $vars = []): \LibreBooking\Common\Templating\TemplateRenderer
    {
        return new class ($vars) implements \LibreBooking\Common\Templating\TemplateRenderer {
            public PageValidators $Validators;
            public function __construct(private array $vars = [])
            {
                $this->Validators = new PageValidators(new class () extends SmartyPage {
                    public function AddFailedValidation($id, $validator): void
                    {
                    }
                });
            }
            public function assign(string $name, mixed $value): void
            {
                $this->vars[$name] = $value;
            }
            public function render(string $templateName, array $v = []): string
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
                if ($name === null) {
                    return $this->vars;
                }
                return $this->vars[$name] ?? null;
            }
            public function fetchLocalized(string $t, bool $e, ?string $l = null): string
            {
                return '';
            }
            public function addTemplateDirectory(string $dir): void
            {
            }
            public function renderControlTemplate(string $t, array $v): string
            {
                return '';
            }
            public function validators(): PageValidators
            {
                return $this->Validators;
            }
            public function isValid(): bool
            {
                return true;
            }
        };
    }
}
