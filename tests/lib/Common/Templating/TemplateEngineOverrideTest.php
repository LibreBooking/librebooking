<?php

require_once(__DIR__ . '/../../../../lib/Common/namespace.php');

class TemplateEngineOverrideTest extends TestBase
{
    public function testNoOverrideByDefault(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);

        $this->assertNull(TemplateEngineOverride::RequestedEngine());
        $this->assertTrue(TemplateEngineOverride::TwigAllowed());
    }

    public function testQueryOverrideIsUsedAndPersistedInDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');

        TemplateEngineOverride::PersistRequestedEngine();

        $this->assertSame('smarty', TemplateEngineOverride::RequestedEngine());
        $this->assertFalse(TemplateEngineOverride::TwigAllowed());
        $cookie = $this->fakeServer->Cookies[CookieKeys::TEMPLATE_ENGINE];
        $this->assertSame('smarty', $cookie->Value);
        $this->assertFalse($cookie->Secure, 'must also work on plain http dev servers');
    }

    public function testQueryOverrideWinsOverCookie(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetCookie(new Cookie(CookieKeys::TEMPLATE_ENGINE, 'smarty'));
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'twig');

        $this->assertSame('twig', TemplateEngineOverride::RequestedEngine());
        $this->assertTrue(TemplateEngineOverride::TwigAllowed());
    }

    public function testIgnoredWithoutDebugMode(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, false);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');
        $this->fakeServer->SetCookie(new Cookie(CookieKeys::TEMPLATE_ENGINE, 'smarty'));

        TemplateEngineOverride::PersistRequestedEngine();

        $this->assertNull(TemplateEngineOverride::RequestedEngine());
        $this->assertTrue(TemplateEngineOverride::TwigAllowed());
    }

    public function testInvalidValuesAreIgnored(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, '<script>');

        TemplateEngineOverride::PersistRequestedEngine();

        $this->assertNull(TemplateEngineOverride::RequestedEngine());
        $this->assertArrayNotHasKey(CookieKeys::TEMPLATE_ENGINE, $this->fakeServer->Cookies);
    }

    public function testEmailsUseSmartyWhenOverridden(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);
        $this->fakeResources->SetDateFormat('reservation_email', 'Y-m-d H:i');

        $this->assertStringContainsString('&lt;b&gt;probe', $this->renderEmail());

        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');
        $this->assertStringContainsString('<b>probe</b>', $this->renderEmail());
    }

    public function testFetchAndControlsUseSmartyWhenOverridden(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::APP_DEBUG, true);

        $this->assertStringContainsString('&lt;b&gt;probe', $this->fetchQrPage());
        $this->assertStringContainsString('&lt;b&gt;label', $this->renderControl());

        $this->fakeServer->SetQuerystring(QueryStringKeys::TEMPLATE_ENGINE, 'smarty');

        // Smarty prints both raw, Twig escapes them
        $this->assertStringContainsString('<b>probe</b>', $this->fetchQrPage());
        $this->assertStringContainsString('<b>label</b></label>', $this->renderControl());
    }

    /**
     * Routes that validate the whole query string must accept the extra parameter,
     * e.g. the schedule's AJAX reservation load (dr=reservations).
     *
     * @return array<string, array{array<string, mixed>, string, bool}>
     */
    public static function validatedRoutes(): array
    {
        return [
            'schedule' => [RouteParamsKeys::VIEW_SCHEDULE, '/schedule.php?sid=1&sd=2026-11-08&template_engine=smarty', true],
            'schedule reservations ajax' => [RouteParamsKeys::VIEW_SCHEDULE, '/schedule.php?dr=reservations&sid=1&template_engine=smarty', true],
            'calendar' => [RouteParamsKeys::VIEW_SCHEDULE, '/calendar.php?sid=1&template_engine=twig', true],
            'view calendar' => [RouteParamsKeys::VIEW_CALENDAR, '/view-calendar.php?sid=1&start=2026-11-08&template_engine=smarty', true],
            'guest reservation from schedule' => [RouteParamsKeys::GUEST_RESERVATION_FROM_SCHEDULE, '/guest-reservation.php?rid=1&sid=1&rd=2026-11-08&template_engine=smarty', false],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validatedRoutes')]
    public function testValidatedRoutesAcceptTheParameter(array $rules, string $uri, bool $optional): void
    {
        $this->assertTrue(ParamsValidator::validate($rules, $uri, $optional));
    }

    private function fetchQrPage(): string
    {
        $twig = new TwigRenderer();
        $twig->assign('ResourceName', '<b>probe</b>');
        $twig->assign('QRImageUrl', 'qr.png');

        return $twig->fetch('Admin/Resources/show_resource_qr.tpl');
    }

    private function renderControl(): string
    {
        $definition = new CustomAttribute(1, '<b>label</b>', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESERVATION, null, false, null, 1);

        return (new TwigRenderer())->renderControlTemplate('Controls/Attributes/SingleLineTextbox.tpl', [
            'attribute' => new LBAttribute($definition, 'value'),
            'attributeId' => 'a1',
            'attributeName' => 'a1',
            'readonly' => true,
            'class' => '',
            'searchmode' => false,
        ]);
    }

    private function renderEmail(): string
    {
        $twig = new TwigRenderer();
        foreach (['StartDate' => Date::Now(), 'EndDate' => Date::Now(), 'Title' => '<b>probe</b>'] as $k => $v) {
            $twig->assign($k, $v);
        }

        return $twig->fetchLocalized('ReservationDeleted.tpl', false, 'en_us');
    }
}
