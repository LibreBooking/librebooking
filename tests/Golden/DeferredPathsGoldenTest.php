<?php

require_once(__DIR__ . '/GoldenTemplateTestCase.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../tests/fakes/FakeServer.php');
require_once(__DIR__ . '/../../lang/AvailableLanguage.php');

/**
 * Golden tests for deferred template paths:
 *   - tpl/json_data.tpl    → json_data.twig  (data and error branches)
 *   - tpl/maintenance.twig (structural assertTwigContains)
 *   - tpl/Ajax/resource_popup.twig (structural assertTwigContains)
 */
class DeferredPathsGoldenTest extends GoldenTemplateTestCase
{
    /** @var array<string, mixed> */
    private array $savedServer = [];

    private ?Resources $savedResources = null;

    private ?Server $savedServiceLocatorServer = null;

    protected function setUp(): void
    {
        parent::setUp();
        $this->savedServer = $_SERVER;
        $_SERVER['SCRIPT_NAME'] = '/web/index.php';
        $_SERVER['REQUEST_URI'] = '/web/index.php';
        $this->savedResources = Resources::GetInstance();
        Resources::SetInstance(null);
        Resources::GetInstance();
        $this->savedServiceLocatorServer = ServiceLocator::GetServer();
        $fakeServer = new FakeServer();
        $fakeServer->UserSession->CSRFToken = 'golden-test-csrf-token';
        $fakeServer->UserSession->UserId = 42;
        ServiceLocator::SetServer($fakeServer);
        Date::_SetNow(Date::Parse('2025-06-15 10:00:00', 'UTC'));
    }

    protected function tearDown(): void
    {
        $prop = new \ReflectionProperty(Date::class, '_Now');
        $prop->setAccessible(true);
        $prop->setValue(null, null);

        $_SERVER = $this->savedServer;
        Resources::SetInstance($this->savedResources);
        ServiceLocator::SetServer($this->savedServiceLocatorServer);
        parent::tearDown();
    }

    /**
     * Render both Smarty and Twig with the given vars and assert normalized parity.
     *
     * @param array<string, mixed> $vars
     */
    private function assertParity(string $tplName, string $twigName, array $vars): void
    {
        $smarty = new SmartyRenderer();
        foreach ($vars as $k => $v) {
            $smarty->assign($k, $v);
        }
        $expected = $smarty->render($tplName);

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $actual = $twig->render($twigName);

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
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render($twigName);
        foreach ($expectedStrings as $needle) {
            $this->assertStringContainsString($needle, $output, "Expected '$needle' in $twigName output");
        }
    }

    // ── json_data ─────────────────────────────────────────────────────────────

    public function testJsonDataWithDataBranchMatchesSmarty(): void
    {
        $this->assertParity(
            'json_data.tpl',
            'json_data.twig',
            ['data' => '{"foo":"bar"}', 'error' => '']
        );
    }

    public function testJsonDataWithErrorBranchMatchesSmarty(): void
    {
        $this->assertParity(
            'json_data.tpl',
            'json_data.twig',
            ['error' => '{"response":{},"errors":"oops"}', 'data' => '']
        );
    }

    public function testJsonDataEmptyBothBranchesMatchesSmarty(): void
    {
        $this->assertParity(
            'json_data.tpl',
            'json_data.twig',
            ['data' => '', 'error' => '']
        );
    }

    // ── maintenance ───────────────────────────────────────────────────────────

    /**
     * Returns the minimum set of variables needed for maintenance.twig, which
     * includes globalheader.twig, javascript-includes.twig, and globalfooter.twig.
     *
     * @return array<string, mixed>
     */
    private function maintenanceVars(): array
    {
        return [
            // globalheader vars
            'HtmlLang' => 'en',
            'HtmlTextDirection' => 'ltr',
            'Title' => 'LibreBooking',
            'TitleKey' => '',
            'TitleArgs' => [],
            'Charset' => 'UTF-8',
            'Path' => '/web/',
            'FaviconUrl' => 'favicon.ico',
            'UseLocalJquery' => false,
            'Trumbowyg' => false,
            'DataTable' => false,
            'InlineEdit' => false,
            'Select2' => false,
            'cssTheme' => 'light',
            'ScriptUrl' => 'http://localhost/web',
            'HideNavBar' => true,
            // maintenance-specific vars
            'LogoUrl' => 'logo.png',
            'Version' => '1',
            // globalfooter vars
            'CompanyName' => '',
            'CompanyUrl' => '',
            'DisplayVersion' => '1.0.0',
            'LoggedIn' => false,
            'AvailableLanguages' => [],
            'CSRFToken' => 'test-csrf-token',
            'GoogleAnalyticsTrackingId' => '',
        ];
    }

    public function testMaintenanceTwigRendersCorrectly(): void
    {
        $this->assertTwigContains(
            'maintenance.twig',
            $this->maintenanceVars(),
            ['page-maintenance', 'maintenance-box', 'bi-tools']
        );
    }

    public function testMaintenanceTwigContainsLogoAndTitle(): void
    {
        $vars = $this->maintenanceVars();
        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render('maintenance.twig');

        $this->assertStringContainsString('logo.png', $output);
        $this->assertStringContainsString('LibreBooking', $output);
    }

    // ── resource_popup ────────────────────────────────────────────────────────

    /**
     * Returns minimal vars for the resource_popup.twig template.
     *
     * @return array<string, mixed>
     */
    private function resourcePopupVars(): array
    {
        return [
            'resourceName' => 'Conference Room A',
            'color' => '',
            'textColor' => '#000000',
            'imageUrl' => '',
            'images' => [],
            'contactInformation' => 'admin@example.com',
            'locationInformation' => 'Building 1, Floor 2',
            'resourceType' => 'Meeting Room',
            'Attributes' => [],
            'ResourceTypeAttributes' => [],
            'minimumDuration' => '30 minutes',
            'maximumDuration' => '4 hours',
            'requiresApproval' => false,
            'minimumNotice' => '',
            'maximumNotice' => '',
            'allowMultiday' => false,
            'maxParticipants' => '10',
            'autoReleaseMinutes' => '',
            'isCheckInEnabled' => false,
            'creditsEnabled' => false,
            'offPeakCredits' => 0,
            'peakCredits' => 0,
            'description' => 'A great conference room for meetings.',
            'notes' => '',
        ];
    }

    public function testResourcePopupRendersBasicDetails(): void
    {
        $this->assertTwigContains(
            'Ajax/resource_popup.twig',
            $this->resourcePopupVars(),
            [
                'resourceDetailsPopup',
                'Conference Room A',
                'admin@example.com',
                'Building 1, Floor 2',
                'Meeting Room',
                'A great conference room for meetings.',
            ]
        );
    }

    public function testResourcePopupWithColorRendersHeaderStyle(): void
    {
        $vars = $this->resourcePopupVars();
        $vars['color'] = '#336699';
        $vars['textColor'] = '#ffffff';

        $this->assertTwigContains(
            'Ajax/resource_popup.twig',
            $vars,
            ['background-color:#336699', 'color:#ffffff']
        );
    }

    public function testResourcePopupNoImageUsesFullWidth(): void
    {
        $vars = $this->resourcePopupVars();

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render('Ajax/resource_popup.twig');

        // No image URL — carousel div should not appear.
        $this->assertStringNotContainsString('resourceImageCarousel', $output);
        // Default class col-md-6 should be used.
        $this->assertStringContainsString('col-md-6', $output);
    }

    public function testResourcePopupEmptyDescriptionShowsLabel(): void
    {
        $vars = $this->resourcePopupVars();
        $vars['description'] = '';
        $vars['notes'] = '';

        $twig = new TwigRenderer();
        foreach ($vars as $k => $v) {
            $twig->assign($k, $v);
        }
        $output = $twig->render('Ajax/resource_popup.twig');

        // The translation keys resolve to the English strings in test context.
        $this->assertStringContainsString('no description', $output);
        $this->assertStringContainsString('no notes', $output);
    }
}
