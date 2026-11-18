<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

/**
 * Every test creates its own templates under a name that is unique to the run,
 * so installed or customized templates and concurrent test runs cannot interfere.
 */
class SmartyPageFetchLocalizedTest extends TestBase
{
    private const ENGLISH = 'en_us';
    private const GERMAN = 'de_de';
    private const FRENCH = 'fr_fr';
    private const UNKNOWN_LANGUAGE = 'zz_zz';
    private const ENGLISH_OUTPUT = 'english for LibreBooking';
    private const GERMAN_OUTPUT = 'german for LibreBooking';
    private const CUSTOM_GERMAN_OUTPUT = 'custom german for LibreBooking';

    private string $template;
    private string $customTemplate;
    private ?string $compileDir = null;

    /**
     * @var string[]
     */
    private array $createdFiles = [];

    public function setUp(): void
    {
        parent::setUp();

        $basename = 'FetchLocalizedTest' . bin2hex(random_bytes(8));
        $this->template = $basename . '.tpl';
        $this->customTemplate = $basename . '-custom.tpl';

        $compileDir = sys_get_temp_dir() . '/' . $basename;
        mkdir($compileDir);
        $this->compileDir = $compileDir;

        $this->createTemplate(self::ENGLISH, $this->template, 'english for {$AppTitle}');
        $this->createTemplate(self::GERMAN, $this->template, 'german for {$AppTitle}');
    }

    public function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            unlink($file);
        }
        $this->createdFiles = [];

        if ($this->compileDir !== null) {
            array_map(unlink(...), glob($this->compileDir . '/*') ?: []);
            rmdir($this->compileDir);
            $this->compileDir = null;
        }

        parent::tearDown();
    }

    public function testFetchesTheRequestedLanguageWhenThePageWasCreatedForAnother(): void
    {
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);

        $this->assertSame(self::GERMAN_OUTPUT, $page->FetchLocalized($this->template, false, self::GERMAN));
    }

    public function testFetchesEachRequestedLanguageFromTheSamePage(): void
    {
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);

        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false, self::ENGLISH));
        $this->assertSame(self::GERMAN_OUTPUT, $page->FetchLocalized($this->template, false, self::GERMAN));
        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false, self::ENGLISH));
    }

    public function testFallsBackToEnglishForAnUnknownLanguage(): void
    {
        $this->assertDirectoryDoesNotExist(ROOT_DIR . 'lang/' . self::UNKNOWN_LANGUAGE);
        $page = $this->createPage(resourcesLanguage: self::GERMAN);

        $this->assertSame(
            self::ENGLISH_OUTPUT,
            $page->FetchLocalized($this->template, false, self::UNKNOWN_LANGUAGE)
        );
    }

    public function testFallsBackToEnglishForALanguageWithoutTheTemplate(): void
    {
        $page = $this->createPage(resourcesLanguage: self::GERMAN);

        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false, self::FRENCH));
    }

    public function testUsesTheCurrentLanguageVariableWhenNoLanguageIsGiven(): void
    {
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);
        $page->assign('CurrentLanguage', self::GERMAN);

        $this->assertSame(self::GERMAN_OUTPUT, $page->FetchLocalized($this->template, false));
    }

    public function testUsesTheLanguageThePageWasCreatedForWhenNoneIsGivenOrAssigned(): void
    {
        $page = $this->createPage(resourcesLanguage: self::GERMAN);

        $this->assertNull($page->getTemplateVars('CurrentLanguage'));
        $this->assertSame(self::GERMAN_OUTPUT, $page->FetchLocalized($this->template, false));
    }

    public function testFallsBackToEnglishWhenThePageLanguageHasNoTemplateAndNoneIsGiven(): void
    {
        $page = $this->createPage(resourcesLanguage: self::FRENCH);

        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false));
    }

    public function testCustomTemplateOfTheLanguageTakesPrecedence(): void
    {
        $this->createTemplate(self::GERMAN, $this->customTemplate, 'custom german for {$AppTitle}');
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);

        $this->assertSame(
            self::CUSTOM_GERMAN_OUTPUT,
            $page->FetchLocalized($this->template, false, self::GERMAN)
        );
        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false, self::ENGLISH));
    }

    public function testCustomTemplateOfTheDefaultLanguageIsUsedWhenEnforced(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, self::GERMAN);
        $this->createTemplate(self::GERMAN, $this->customTemplate, 'custom german for {$AppTitle}');
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);

        $this->assertSame(
            self::CUSTOM_GERMAN_OUTPUT,
            $page->FetchLocalized($this->template, true, self::ENGLISH)
        );
    }

    public function testCustomTemplateOfTheDefaultLanguageIsIgnoredWhenNotEnforced(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_LANGUAGE, self::GERMAN);
        $this->createTemplate(self::GERMAN, $this->customTemplate, 'custom german for {$AppTitle}');
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);

        $this->assertSame(self::ENGLISH_OUTPUT, $page->FetchLocalized($this->template, false, self::ENGLISH));
    }

    public function testTemplateThatIsNotLocalizedIsFetchedFromTheTemplateDirectories(): void
    {
        $page = $this->createPage(resourcesLanguage: self::ENGLISH);
        $page->assign('data', 'not localized');

        $output = $page->FetchLocalized('json_data.tpl', false, self::GERMAN);

        $this->assertSame('not localized', trim($output));
    }

    private function createPage(string $resourcesLanguage): SmartyPage
    {
        $this->assertNotNull($this->compileDir);
        $this->fakeResources->CurrentLanguage = $resourcesLanguage;

        $page = new SmartyPage();
        $page->setCompileDir($this->compileDir);
        $page->assign('AppTitle', 'LibreBooking');

        return $page;
    }

    private function createTemplate(string $language, string $name, string $content): void
    {
        $file = ROOT_DIR . 'lang/' . $language . '/' . $name;

        file_put_contents($file, $content);
        $this->createdFiles[] = $file;
    }
}
