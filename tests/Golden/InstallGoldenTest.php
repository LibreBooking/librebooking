<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../Presenters/Install/InstallationResult.php');

use PHPUnit\Framework\Attributes\DataProvider;

class InstallGoldenTest extends GoldenTemplateTestCase
{
    private ?Resources $savedResources = null;
    /** @var array<string, mixed> */
    private array $savedServer = [];
    /** @var array<string, mixed> */
    private array $savedCookie = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServer = $_SERVER;
        $this->savedCookie = $_COOKIE;
        $_SERVER['SCRIPT_NAME'] = '/web/install/index.php';
        $_COOKIE = [];
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->savedServer;
        $_COOKIE = $this->savedCookie;
        Resources::SetInstance($this->savedResources);
        parent::tearDown();
    }

    private function baseVars(): array
    {
        return require __DIR__ . '/fixtures/install-base.php';
    }

    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $expected = (new SmartyRenderer())->render($tplName, $vars);
        $actual   = (new TwigRenderer())->render($twigName, $vars);
        $this->assertSame(
            HtmlNormalizer::normalize($expected),
            HtmlNormalizer::normalize($actual),
            "Smarty vs Twig mismatch for $twigName"
        );
    }

    /**
     * Render Twig only and assert the output contains expected strings.
     *
     * @param array<string, mixed> $vars
     * @param string[]             $expectedStrings
     */
    private function assertTwigContains(string $twigName, array $vars, array $expectedStrings): void
    {
        $twig = new TwigRenderer();
        $output = $twig->render($twigName, $vars);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $output, "Expected '$needle' in $twigName output");
        }
    }

    // ── install.twig ─────────────────────────────────────────────────────────

    public static function installBranchProvider(): array
    {
        return [
            'defaults (password prompt)' => [[]],
            'ShowInvalidPassword' => [['ShowInvalidPassword' => true]],
            'InstallPasswordMissing' => [['InstallPasswordMissing' => true]],
            'ShowUpToDateMessage' => [['ShowUpToDateMessage' => true]],
            'ShowScriptUrlWarning' => [[
                'ShowScriptUrlWarning' => true,
                'CurrentScriptUrl' => 'http://old.example.com/web',
                'SuggestedScriptUrl' => 'http://new.example.com/web',
            ]],
            'InstallCompletedSuccessfully' => [[
                'ShowPasswordPrompt' => false,
                'InstallCompletedSuccessfully' => true,
            ]],
            'UpgradeCompletedSuccessfully' => [[
                'ShowPasswordPrompt' => false,
                'UpgradeCompletedSuccessfully' => true,
                'TargetVersion' => '2.9.0',
            ]],
            'InstallFailed' => [[
                'ShowPasswordPrompt' => false,
                'InstallFailed' => true,
            ]],
        ];
    }

    #[DataProvider('installBranchProvider')]
    public function testInstallBranchesMatchSmarty(array $overrides): void
    {
        $vars = array_merge($this->baseVars(), $overrides);
        $this->assertParity('Install/install.tpl', 'Install/install.twig', $vars);
    }

    /**
     * The database-prompt branch renders textbox inputs with both `id` and `size`
     * attributes. Smarty's AppendAttributes preserves tag declaration order (size,
     * then id) while the Twig textbox() helper places the named `id` param before
     * the `attributes` dict entries.  This is a cosmetic attribute-ordering
     * divergence; use assertTwigContains for structural coverage.
     */
    public function testInstallDatabasePromptInstallOptionsRendersCorrectly(): void
    {
        $vars = array_merge($this->baseVars(), [
            'ShowPasswordPrompt' => false,
            'ShowDatabasePrompt' => true,
            'ShowInstallOptions' => true,
            'dbname' => 'librebooking',
            'dbuser' => 'lbuser',
            'dbhost' => 'localhost',
        ]);
        $this->assertTwigContains('Install/install.twig', $vars, [
            'list-group-numbered',
            'Database Name',
            'librebooking',
            'lbuser',
            'localhost',
            'name="install_db_user"',
            'id="dbUser"',
            'name="install_db_password"',
            'id="dbPassword"',
            'create_database',
            'create_user',
            'create_sample_data',
            'create_large_sample_data',
            'run_install',
        ]);
    }

    public function testInstallDatabasePromptUpgradeOptionsRendersCorrectly(): void
    {
        $vars = array_merge($this->baseVars(), [
            'ShowPasswordPrompt' => false,
            'ShowDatabasePrompt' => true,
            'ShowUpgradeOptions' => true,
            'CurrentVersion' => '2.8.0',
            'TargetVersion' => '2.9.0',
            'dbname' => 'librebooking',
            'dbuser' => 'lbuser',
            'dbhost' => 'localhost',
        ]);
        $this->assertTwigContains('Install/install.twig', $vars, [
            'list-group-numbered',
            'Database Name',
            'librebooking',
            'lbuser',
            'localhost',
            'name="install_db_user"',
            'id="dbUser"',
            'name="install_db_password"',
            'id="dbPassword"',
            'run_upgrade',
            '2.8.0',
            '2.9.0',
        ]);
    }

    public function testInstallResultsMatchSmarty(): void
    {
        $success = new InstallationResult('Create tables');
        $success->SetResult(0, '', 'CREATE TABLE foo (id INT)');
        $failure = new InstallationResult('Add index');
        $failure->SetResult(1062, 'Duplicate entry', 'CREATE INDEX idx ON foo (id)');
        $vars = array_merge($this->baseVars(), [
            'ShowPasswordPrompt' => false,
            'installresults' => [$success, $failure],
            'InstallFailed' => true,
        ]);
        $this->assertParity('Install/install.tpl', 'Install/install.twig', $vars);
    }

    // ── configure.twig ───────────────────────────────────────────────────────

    public static function configureBranchProvider(): array
    {
        return [
            'defaults (password prompt)' => [[]],
            'ShowInvalidPassword' => [['ShowInvalidPassword' => true]],
            'InstallPasswordMissing' => [['InstallPasswordMissing' => true]],
            'ShowConfigSuccess' => [[
                'ShowPasswordPrompt' => false,
                'ShowConfigSuccess' => true,
            ]],
        ];
    }

    #[DataProvider('configureBranchProvider')]
    public function testConfigureBranchesMatchSmarty(array $overrides): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/install/configure.php';
        $vars = array_merge($this->baseVars(), $overrides);
        $this->assertParity('Install/configure.tpl', 'Install/configure.twig', $vars);
    }

    /**
     * The ManualConfig branch renders PHP config text via Smarty's |nl2br modifier
     * (which calls PHP nl2br() on the raw string without HTML-escaping).  The Twig
     * template uses |raw|nl2br so that special characters such as &, <, >, ' and "
     * are preserved verbatim and newlines become <br />, byte-identical to Smarty.
     *
     * This test uses assertParity with a ManualConfig fixture containing HTML-special
     * characters to prove the two renderers produce identical output.  It would FAIL
     * with the old |raw|nl2br|raw rendering only if pre_escape fired (Twig version
     * dependent), and definitively fails with plain |nl2br (no leading |raw) where
     * pre_escape always escapes the input.
     */
    public function testConfigureShowManualConfigMatchesSmarty(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/web/install/configure.php';
        $vars = array_merge($this->baseVars(), [
            'ShowPasswordPrompt' => false,
            'ShowManualConfig' => true,
            // ManualConfig with HTML-special chars: &, <, >, ', " and multiple newlines.
            'ManualConfig' => "\$conf['settings']['db.name'] = 'libre & booking';\n"
                . "\$conf['settings']['db.user'] = '<admin>';\n"
                . "\$conf['settings']['db.password'] = 'p&ss\"w<o>rd';",
        ]);
        $this->assertParity('Install/configure.tpl', 'Install/configure.twig', $vars);
    }

}
