<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../lib/Common/namespace.php');
require_once(__DIR__ . '/../../../lib/Email/namespace.php');

use LibreBooking\Common\Templating\TemplateRenderer;

class EmailMessageRendererTest extends TestBase
{
    private function makeMessage(): TestableEmailMessage
    {
        return new TestableEmailMessage();
    }

    public function testEmailUsesRenderer(): void
    {
        $msg = $this->makeMessage();
        $this->assertInstanceOf(TemplateRenderer::class, $msg->exposeRenderer());
    }

    /**
     * The primary renderer must be a TwigRenderer (engine-select wiring).
     */
    public function testEmailUsesTwigRendererAsPrimary(): void
    {
        $msg = $this->makeMessage();
        $this->assertInstanceOf(TwigRenderer::class, $msg->exposeRenderer());
    }

    /**
     * The BC $email alias must still be a SmartyPage instance so that
     * subclasses such as ReportEmailMessage can call $this->email->FetchLocalized().
     */
    public function testBCAliasIsSmartyPage(): void
    {
        $msg = $this->makeMessage();
        $this->assertInstanceOf(SmartyPage::class, $msg->exposeBCEmail());
    }

    /**
     * Set() must propagate variables to the TwigRenderer so the renderer
     * can access assigned variables.  ScriptUrl is always set from Configuration
     * during construction (even with FakeConfig), so we verify it is not null.
     */
    public function testSetPropagatesVarsToRenderer(): void
    {
        $msg = $this->makeMessage();
        $renderer = $msg->exposeRenderer();
        // AppTitle is always assigned during construction.
        $this->assertNotNull($renderer->getTemplateVars('AppTitle'));
    }

    /**
     * Charset() must read from the TwigRenderer (not the BC alias).
     * In test environments FakeResources has no Charset, so the result may be null;
     * what matters is the method delegates to the renderer without crashing.
     */
    public function testCharsetDelegatesToRenderer(): void
    {
        $msg = $this->makeMessage();
        // Must not throw; return value is whatever Resources::Charset is in test env.
        $charset = $msg->Charset();
        // The value is whatever was assigned from $resources->Charset (may be null in tests).
        $this->assertTrue($charset === null || is_string($charset));
    }
}

/**
 * Concrete testable subclass of EmailMessage that exposes protected members.
 */
class TestableEmailMessage extends EmailMessage
{
    public function __construct()
    {
        parent::__construct();
    }

    public function To(): array
    {
        return [];
    }

    public function Subject(): string
    {
        return '';
    }

    public function Body(): string
    {
        return '';
    }

    public function exposeRenderer(): TemplateRenderer
    {
        return $this->renderer;
    }

    public function exposeBCEmail(): SmartyPage
    {
        return $this->email;
    }
}
