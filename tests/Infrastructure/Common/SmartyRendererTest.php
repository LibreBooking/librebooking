<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

class SmartyRendererTest extends TestBase
{
    private const ENGLISH = 'en_us';
    private const GERMAN = 'de_de';
    private const FRENCH = 'fr_fr';
    private const ENGLISH_OUTPUT = 'english for LibreBooking';
    private const GERMAN_OUTPUT = 'german for LibreBooking';

    /**
     * Random bytes in the name of a fixture file or directory, enough to keep
     * the names of concurrent test runs apart.
     */
    private const UNIQUE_NAME_BYTES = 8;

    private ?string $templateDir = null;
    private ?string $compileDir = null;

    /**
     * @var string[]
     */
    private array $localizedTemplates = [];

    public function tearDown(): void
    {
        foreach ($this->localizedTemplates as $file) {
            unlink($file);
        }
        $this->localizedTemplates = [];

        foreach ([$this->templateDir, $this->compileDir] as $dir) {
            if ($dir !== null) {
                array_map(unlink(...), glob($dir . '/*') ?: []);
                rmdir($dir);
            }
        }
        $this->templateDir = null;
        $this->compileDir = null;

        parent::tearDown();
    }

    public function testAssignAndGetRoundTrip(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'Foo', value: 'bar');

        $this->assertSame('bar', $renderer->getTemplateVars(name: 'Foo'));
    }

    public function testGetTemplateVarsReturnsNullForAnUnassignedVariable(): void
    {
        $renderer = new SmartyRenderer();

        $this->assertNull($renderer->getTemplateVars(name: 'NeverAssigned'));
    }

    public function testGetTemplateVarsWithoutANameReturnsAllVariables(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'Foo', value: 'bar');
        $renderer->assign(name: 'Baz', value: 'qux');

        $vars = $renderer->getTemplateVars();

        $this->assertIsArray($vars);
        $this->assertSame('bar', $vars['Foo']);
        $this->assertSame('qux', $vars['Baz']);
    }

    public function testFetchReturnsTheRenderedTemplate(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'name', value: 'World');

        $this->assertSame('Hello World', $renderer->fetch(templateName: 'string:Hello {$name}'));
    }

    public function testDisplayPrintsTheRenderedTemplate(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'name', value: 'World');

        $this->expectOutputString('Hello World');

        $renderer->display(templateName: 'string:Hello {$name}');
    }

    public function testRenderUsesAssignedAndPassedVariables(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'greeting', value: 'Hello');

        $this->assertSame(
            'Hello World',
            $renderer->render(templateName: 'string:{$greeting} {$name}', vars: ['name' => 'World'])
        );
    }

    public function testVariablesPassedToRenderStayAssigned(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->render(templateName: 'string:{$name}', vars: ['name' => 'World']);

        $this->assertSame('World', $renderer->getTemplateVars(name: 'name'));
        $this->assertSame('World', $renderer->fetch(templateName: 'string:{$name}'));
    }

    public function testControlTemplateIsRenderedWithItsOwnVariables(): void
    {
        $renderer = new SmartyRenderer();

        $this->assertSame(
            'checkbox:agree',
            $renderer->renderControlTemplate(templateName: 'string:checkbox:{$id}', vars: ['id' => 'agree'])
        );
    }

    public function testControlTemplateDoesNotSeeAssignedVariables(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->assign(name: 'page', value: 'schedule');

        $this->assertSame(
            '[|agree]',
            $renderer->renderControlTemplate(templateName: 'string:[{$page}|{$id}]', vars: ['id' => 'agree'])
        );
    }

    public function testControlVariablesAreNotAssignedToTheRenderer(): void
    {
        $renderer = new SmartyRenderer();
        $renderer->renderControlTemplate(templateName: 'string:{$id}', vars: ['id' => 'agree']);

        $this->assertNull($renderer->getTemplateVars(name: 'id'));
    }

    public function testTemplatesAreFoundInAnAddedDirectory(): void
    {
        $this->templateDir = sys_get_temp_dir() . '/smarty-renderer-test-' . $this->uniqueName();
        mkdir($this->templateDir);
        file_put_contents($this->templateDir . '/added-directory-probe.tpl', 'from {$where}');

        $renderer = new SmartyRenderer();
        $renderer->addTemplateDirectory(dir: $this->templateDir);
        $renderer->assign(name: 'where', value: 'the added directory');

        $this->assertSame(
            'from the added directory',
            $renderer->fetch(templateName: 'added-directory-probe.tpl')
        );
    }

    public function testFetchLocalizedRendersTheTemplateOfTheRenderersLanguage(): void
    {
        $template = $this->createLocalizedTemplates();

        $this->assertSame(
            self::ENGLISH_OUTPUT,
            $this->rendererFor(language: self::ENGLISH)->fetchLocalized(
                templateName: $template,
                enforceCustomTemplate: false
            )
        );
        $this->assertSame(
            self::GERMAN_OUTPUT,
            $this->rendererFor(language: self::GERMAN)->fetchLocalized(
                templateName: $template,
                enforceCustomTemplate: false
            )
        );
    }

    public function testFetchLocalizedFallsBackToEnglishForALanguageWithoutTheTemplate(): void
    {
        $template = $this->createLocalizedTemplates();

        $this->assertSame(
            self::ENGLISH_OUTPUT,
            $this->rendererFor(language: self::FRENCH)->fetchLocalized(
                templateName: $template,
                enforceCustomTemplate: false
            )
        );
    }

    /**
     * Emails that are created without a language do not assign CurrentLanguage.
     * They are still rendered in the language that was current at that time.
     */
    public function testFetchLocalizedWithoutACurrentLanguageVariableUsesTheLanguageAtCreation(): void
    {
        $template = $this->createLocalizedTemplates();
        $renderer = $this->rendererCreatedFor(language: self::GERMAN);

        $this->assertNull($renderer->getTemplateVars(name: 'CurrentLanguage'));
        $this->assertSame(
            self::GERMAN_OUTPUT,
            $renderer->fetchLocalized(templateName: $template, enforceCustomTemplate: false)
        );
    }

    /**
     * Invitation emails are created without a language. A customized template
     * of the language that is current at that time must still be used.
     */
    public function testFetchLocalizedWithoutACurrentLanguageVariableUsesTheCustomTemplate(): void
    {
        $template = $this->createLocalizedTemplates();
        $this->createCustomTemplate(template: $template, language: self::GERMAN);

        $renderer = $this->rendererCreatedFor(language: self::GERMAN);

        $this->assertNull($renderer->getTemplateVars(name: 'CurrentLanguage'));
        $this->assertSame(
            'custom for LibreBooking',
            $renderer->fetchLocalized(templateName: $template, enforceCustomTemplate: false)
        );
    }

    /**
     * The customized German template makes the difference visible: a lookup
     * for German would use it, because English has no template of that name.
     */
    public function testFetchLocalizedKeepsToTheLanguageAtCreationWhenAnotherOneIsAssigned(): void
    {
        $template = $this->createLocalizedTemplates();
        $this->createCustomTemplate(template: $template, language: self::GERMAN);
        $renderer = $this->rendererCreatedFor(language: self::ENGLISH);
        $renderer->assign(name: 'CurrentLanguage', value: self::GERMAN);

        $this->assertSame(
            self::ENGLISH_OUTPUT,
            $renderer->fetchLocalized(templateName: $template, enforceCustomTemplate: false)
        );
    }

    /**
     * Report emails rely on this for Reports/custom-csv.tpl, which is not in a
     * language directory.
     */
    public function testFetchLocalizedFindsATemplateThatIsOnlyInARegularTemplateDirectory(): void
    {
        $template = 'SmartyRendererTest' . $this->uniqueName() . '.tpl';
        $this->templateDir = sys_get_temp_dir() . '/smarty-renderer-test-' . $this->uniqueName();
        mkdir($this->templateDir);
        file_put_contents($this->templateDir . '/' . $template, 'regular for {$AppTitle}');

        $renderer = $this->rendererFor(language: self::GERMAN);
        $renderer->addTemplateDirectory(dir: $this->templateDir);

        $this->assertSame(
            'regular for LibreBooking',
            $renderer->fetchLocalized(templateName: $template, enforceCustomTemplate: false)
        );
    }

    public function testValidatorsAreThoseOfTheWrappedPage(): void
    {
        $renderer = new SmartyRenderer();

        $this->assertSame($renderer->smarty()->Validators, $renderer->validators());
    }

    public function testIsValidWithoutValidators(): void
    {
        $this->assertTrue((new SmartyRenderer())->isValid());
    }

    public function testIsValidRunsTheRegisteredValidators(): void
    {
        $failing = new FakeValidator();
        $failing->_IsValid = false;

        $renderer = new SmartyRenderer();
        $renderer->validators()->Register(id: 'failing', validator: $failing);

        $this->assertFalse($renderer->isValid());
        $this->assertTrue($failing->_WasValidated);
        $this->assertSame(['failing' => $failing], $renderer->validators()->GetFailed());
    }

    /**
     * Creates an English and a German template under a name that is unique to the
     * test, so installed or customized templates cannot interfere.
     *
     * @return string the template name
     */
    private function createLocalizedTemplates(): string
    {
        $template = 'SmartyRendererTest' . $this->uniqueName() . '.tpl';

        foreach ([self::ENGLISH => 'english', self::GERMAN => 'german'] as $language => $word) {
            $file = ROOT_DIR . 'lang/' . $language . '/' . $template;
            file_put_contents($file, $word . ' for {$AppTitle}');
            $this->localizedTemplates[] = $file;
        }

        return $template;
    }

    /**
     * Creates the customized version of a localized template for one language.
     */
    private function createCustomTemplate(string $template, string $language): void
    {
        $file = ROOT_DIR . 'lang/' . $language . '/' . str_replace('.tpl', '-custom.tpl', $template);
        file_put_contents($file, 'custom for {$AppTitle}');
        $this->localizedTemplates[] = $file;
    }

    /**
     * Creates a renderer the way an email for a language does: while that
     * language is current, and with it assigned as CurrentLanguage.
     */
    private function rendererFor(string $language): SmartyRenderer
    {
        $renderer = $this->rendererCreatedFor(language: $language);
        $renderer->assign(name: 'CurrentLanguage', value: $language);

        return $renderer;
    }

    private function rendererCreatedFor(string $language): SmartyRenderer
    {
        if ($this->compileDir === null) {
            $compileDir = sys_get_temp_dir() . '/smarty-renderer-test-compile-' . $this->uniqueName();
            mkdir($compileDir);
            $this->compileDir = $compileDir;
        }

        $this->fakeResources->CurrentLanguage = $language;

        $renderer = new SmartyRenderer();
        $renderer->smarty()->setCompileDir(compile_dir: $this->compileDir);
        $renderer->assign(name: 'AppTitle', value: 'LibreBooking');

        return $renderer;
    }

    private function uniqueName(): string
    {
        return bin2hex(random_bytes(self::UNIQUE_NAME_BYTES));
    }
}
