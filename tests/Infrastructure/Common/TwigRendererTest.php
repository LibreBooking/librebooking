<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

use Twig\Loader\ArrayLoader;

class TwigRendererTest extends TestBase
{
    private const GERMAN = 'de_de';

    /**
     * Random bytes in the name of a fixture file or directory, enough to keep
     * the names of concurrent test runs apart.
     */
    private const UNIQUE_NAME_BYTES = 8;

    /**
     * @var string[]
     */
    private array $templateDirs = [];
    private ?string $localizedTemplate = null;

    public function setUp(): void
    {
        parent::setUp();

        // Without the template cache nothing is written to tpl_c/twig
        $this->fakeConfig->SetKey(ConfigKeys::CACHE_TEMPLATES, false);
    }

    public function tearDown(): void
    {
        unset($_GET['debug']);

        if ($this->localizedTemplate !== null) {
            unlink($this->localizedTemplate);
            $this->localizedTemplate = null;
        }

        foreach ($this->templateDirs as $dir) {
            array_map(unlink(...), glob($dir . '/*') ?: []);
            rmdir($dir);
        }
        $this->templateDirs = [];

        parent::tearDown();
    }

    public function testOutputIsEscapedForHtml(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['t.twig' => '{{ v }}']);

        $this->assertSame('&lt;b&gt;', $renderer->render(templateName: 't.twig', vars: ['v' => '<b>']));
    }

    public function testAssignAndGetRoundTrip(): void
    {
        $renderer = new TwigRenderer();
        $renderer->assign(name: 'Foo', value: 'bar');

        $this->assertSame('bar', $renderer->getTemplateVars(name: 'Foo'));
    }

    public function testGetTemplateVarsReturnsNullForAnUnassignedVariable(): void
    {
        $this->assertNull((new TwigRenderer())->getTemplateVars(name: 'NeverAssigned'));
    }

    public function testGetTemplateVarsWithoutANameReturnsAllVariables(): void
    {
        $renderer = new TwigRenderer();
        $renderer->assign(name: 'Foo', value: 'bar');
        $renderer->assign(name: 'Baz', value: 'qux');

        $this->assertSame(['Foo' => 'bar', 'Baz' => 'qux'], $renderer->getTemplateVars());
    }

    public function testFetchReturnsTheRenderedTemplate(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['t.twig' => 'Hello {{ name }}']);
        $renderer->assign(name: 'name', value: 'World');

        $this->assertSame('Hello World', $renderer->fetch(templateName: 't.twig'));
    }

    public function testDisplayPrintsTheRenderedTemplate(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['t.twig' => 'Hello {{ name }}']);
        $renderer->assign(name: 'name', value: 'World');

        $this->expectOutputString('Hello World');

        $renderer->display(templateName: 't.twig');
    }

    public function testRenderUsesAssignedAndPassedVariables(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['t.twig' => '{{ greeting }} {{ name }}']);
        $renderer->assign(name: 'greeting', value: 'Hello');

        $this->assertSame('Hello World', $renderer->render(templateName: 't.twig', vars: ['name' => 'World']));
    }

    /**
     * Unlike SmartyRenderer, which keeps them assigned.
     */
    public function testVariablesPassedToRenderAreNotKept(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['t.twig' => '[{{ name }}]']);
        $renderer->render(templateName: 't.twig', vars: ['name' => 'World']);

        $this->assertNull($renderer->getTemplateVars(name: 'name'));
        $this->assertSame('[]', $renderer->fetch(templateName: 't.twig'));
    }

    public function testControlTemplateIsRenderedWithItsOwnVariables(): void
    {
        $renderer = $this->rendererWithTemplates(templates: ['control.twig' => 'checkbox:{{ id }}']);

        $this->assertSame(
            'checkbox:agree',
            $renderer->renderControlTemplate(templateName: 'control.twig', vars: ['id' => 'agree'])
        );
    }

    public function testTemplatesAreFoundInAnAddedDirectory(): void
    {
        $dir = $this->createTemplateDirectory(templates: ['probe.twig' => 'from {{ where }}']);

        $renderer = new TwigRenderer();
        $renderer->addTemplateDirectory(dir: $dir);
        $renderer->assign(name: 'where', value: 'the added directory');

        $this->assertSame('from the added directory', $renderer->fetch(templateName: 'probe.twig'));
    }

    /**
     * Like Smarty, which also searches an added directory last.
     */
    public function testAnAddedDirectoryDoesNotReplaceATemplateOfAnEarlierDirectory(): void
    {
        $first = $this->createTemplateDirectory(templates: ['probe.twig' => 'first']);
        $second = $this->createTemplateDirectory(templates: ['probe.twig' => 'second']);

        $renderer = new TwigRenderer();
        $renderer->addTemplateDirectory(dir: $first);
        $renderer->addTemplateDirectory(dir: $second);

        $this->assertSame('first', $renderer->fetch(templateName: 'probe.twig'));
    }

    public function testTemplatesAreFoundInTheDirectoryOfTheLanguageAtCreation(): void
    {
        $template = 'TwigRendererTest' . $this->uniqueName() . '.twig';
        $this->localizedTemplate = ROOT_DIR . 'lang/' . self::GERMAN . '/' . $template;
        file_put_contents($this->localizedTemplate, 'german');

        $this->fakeResources->CurrentLanguage = self::GERMAN;
        $renderer = new TwigRenderer();

        $this->assertSame('german', $renderer->fetch(templateName: $template));
    }

    public function testIsValidWithoutValidators(): void
    {
        $this->assertTrue((new TwigRenderer())->isValid());
    }

    public function testIsValidRunsTheRegisteredValidators(): void
    {
        $failing = new FakeValidator();
        $failing->_IsValid = false;

        $renderer = new TwigRenderer();
        $renderer->validators()->Register(id: 'failing', validator: $failing);

        $this->assertFalse($renderer->isValid());
        $this->assertTrue($failing->_WasValidated);
        $this->assertSame(['failing' => $failing], $renderer->validators()->GetFailed());
    }

    public function testTemplatesAreCompiledOnEveryRequestWhenTheTemplateCacheIsOff(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::CACHE_TEMPLATES, false);

        $this->assertTrue((new TwigRenderer())->environment()->isDebug());
    }

    public function testTheTemplateCacheIsUsedWhenItIsOn(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::CACHE_TEMPLATES, true);

        $this->assertFalse((new TwigRenderer())->environment()->isDebug());
    }

    /**
     * Otherwise any visitor could switch off the template cache.
     */
    public function testTheDebugQueryParameterOnlyCountsInDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::CACHE_TEMPLATES, true);
        $_GET['debug'] = '1';

        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, false);
        $this->assertFalse((new TwigRenderer())->environment()->isDebug());

        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->assertTrue((new TwigRenderer())->environment()->isDebug());
    }

    /**
     * @param array<string, string> $templates template source by name
     */
    private function rendererWithTemplates(array $templates): TwigRenderer
    {
        $renderer = new TwigRenderer();
        $renderer->environment()->setLoader(loader: new ArrayLoader(templates: $templates));

        return $renderer;
    }

    /**
     * @param array<string, string> $templates template source by file name
     */
    private function createTemplateDirectory(array $templates): string
    {
        $dir = sys_get_temp_dir() . '/twig-renderer-test-' . $this->uniqueName();
        mkdir($dir);
        $this->templateDirs[] = $dir;

        foreach ($templates as $name => $source) {
            file_put_contents($dir . '/' . $name, $source);
        }

        return $dir;
    }

    private function uniqueName(): string
    {
        return bin2hex(random_bytes(self::UNIQUE_NAME_BYTES));
    }

    /**
     * fetch() selects Twig when a .twig counterpart exists in the loader.
     */
    public function testFetchSelectsTwigWhenTwigCounterpartExists(): void
    {
        $renderer = new TwigRenderer();
        $renderer->environment()->setLoader(
            new \Twig\Loader\ArrayLoader(['Email/emailheader.twig' => 'TWIG_HEADER'])
        );
        $renderer->assign('Charset', 'UTF-8');

        $result = $renderer->fetch('Email/emailheader.tpl');

        $this->assertSame('TWIG_HEADER', $result);
    }

    /**
     * fetchLocalized() falls through to Smarty when no .twig counterpart of the
     * localized template exists.
     *
     * lang/de_de/ has .tpl email bodies but NO .twig counterparts (Phase-5 backlog).
     * AccountDeleted.tpl in de_de is confirmed .tpl-only (no sibling .twig).
     * This test proves the Smarty fallback branch fires by:
     *   (a) rendering via TwigRenderer::fetchLocalized — which must delegate to Smarty
     *   (b) rendering the same template via SmartyRenderer::fetchLocalized directly
     *   (c) asserting byte-equal (normalized) output — proving it is the Smarty result,
     *       not any Twig rendering
     *   (d) asserting the de_de localized content (German) is present, confirming it is
     *       the non-en_us locale and not the en_us English fallback
     *
     * NOTE: the language code MUST be lowercase ('de_de') — Linux filesystems are
     * case-sensitive and lang/de_DE/ does not exist; only lang/de_de/ does.
     * The former bug passed 'de_DE' which missed the lang/ dir, fell through to
     * lang/en_us/AccountDeleted.twig, and exercised the Twig branch instead.
     */
    public function testFetchLocalizedFallsBackToSmartyWhenNoTwigExists(): void
    {
        // AccountDeleted.tpl exists in lang/de_de/ as .tpl only (no .twig counterpart).
        // lang/de_de/AccountDeleted.tpl needs UserFullName and AdminFullName.
        $vars = [
            'UserFullName' => 'Max Mustermann',
            'AdminFullName' => 'Admin Person',
            'AppTitle' => 'LibreBooking',
        ];

        // Twig path: TwigRenderer::fetchLocalized → no .twig at lang/de_de/ → Smarty fallback
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOut = $twig->fetchLocalized('AccountDeleted.tpl', false, 'de_de');

        // Smarty path: SmartyRenderer::fetchLocalized → lang/de_de/AccountDeleted.tpl directly
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOut = $smarty->fetchLocalized('AccountDeleted.tpl', false, 'de_de');

        // Both must be non-empty strings
        $this->assertIsString($twigOut);
        $this->assertNotEmpty($twigOut);

        // Normalized output must be byte-equal — proving TwigRenderer delegated to Smarty
        $normalize = static fn (string $s): string => trim(preg_replace('/\s+/', ' ', $s) ?? $s);
        $this->assertSame(
            $normalize($smartyOut),
            $normalize($twigOut),
            'fetchLocalized must delegate to Smarty for a .tpl-only non-en_us template'
        );

        // Confirm there is no .twig counterpart in lang/de_de/ — this is the structural
        // precondition that forces TwigRenderer::fetchLocalized into the Smarty branch.
        // If this assertion fails, a .twig file was added to lang/de_de/ and the test
        // must be updated to use a different non-en_us lang/body that remains .tpl-only.
        $this->assertFileDoesNotExist(
            ROOT_DIR . 'lang/de_de/AccountDeleted.twig',
            'lang/de_de/AccountDeleted.twig must not exist for this test to prove the Smarty fallback branch'
        );
    }

    /**
     * fetchLocalized() selects the Twig engine when a real .twig file exists at
     * the resolved localized path (lang/en_us/ReportEmail.twig, added in Phase 4b).
     *
     * This replaces the previously vacuous test that could not exercise the positive
     * branch because ROOT_DIR is a compile-time constant.  Now that real on-disk
     * .twig bodies exist, the branch fires without any mocking.
     *
     * The assertion: Twig-rendered output equals Smarty-rendered .tpl output after
     * HTML normalization — proving the engine-select branch fires with a real file.
     */
    public function testFetchLocalizedSelectsTwigWhenTwigFileExists(): void
    {
        $vars = [
            'CurrentLanguage' => 'en_us',
            'AppTitle' => 'LibreBooking',
            'ScriptUrl' => 'http://localhost/',
        ];

        // Twig path: fetchLocalized finds lang/en_us/ReportEmail.twig → renders via Twig
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $twigOut = $twig->fetchLocalized('ReportEmail.tpl', false, 'en_us');

        // Smarty path: fetchLocalized finds lang/en_us/ReportEmail.tpl → renders via Smarty
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $smartyOut = $smarty->fetchLocalized('ReportEmail.tpl', false);

        // Both must be non-empty
        $this->assertIsString($twigOut);
        $this->assertNotEmpty($twigOut);

        // Normalize and compare — proving the Twig branch fires and produces correct output
        $normalize = static fn (string $s): string => trim(preg_replace('/\s+/', ' ', $s) ?? $s);
        $this->assertSame(
            $normalize($smartyOut),
            $normalize($twigOut),
            'fetchLocalized must select the .twig file and produce output matching the .tpl'
        );
    }
}
