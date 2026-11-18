<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Templating;

use LibreBooking\Common\Templating\TemplateEngine;
use PHPUnit\Framework\Attributes\DataProvider;

class TemplateEngineTest extends \TestBase
{
    public function testSmartyIsUsedWhenTheSettingIsNotSet(): void
    {
        $this->assertSame(TemplateEngine::SMARTY, TemplateEngine::configured());
    }

    public function testSmartyIsTheDefaultOfTheSetting(): void
    {
        $this->assertSame(TemplateEngine::SMARTY->value, \ConfigKeys::TEMPLATE_ENGINE['default']);
    }

    #[DataProvider('engineProvider')]
    public function testConfiguredEngineIsUsed(TemplateEngine $engine): void
    {
        $this->fakeConfig->SetKey(\ConfigKeys::TEMPLATE_ENGINE, $engine->value);

        $this->assertSame($engine, TemplateEngine::configured());
    }

    /**
     * @return array<string, array{TemplateEngine}>
     */
    public static function engineProvider(): array
    {
        return [
            'smarty' => [TemplateEngine::SMARTY],
            'twig' => [TemplateEngine::TWIG],
        ];
    }

    public function testUnknownValueFallsBackToSmarty(): void
    {
        $this->fakeConfig->SetKey(\ConfigKeys::TEMPLATE_ENGINE, 'mustache');

        $this->assertSame(TemplateEngine::SMARTY, TemplateEngine::configured());
    }

    public function testSettingOffersExactlyTheKnownEngines(): void
    {
        $this->assertSame(
            array_map(static fn (TemplateEngine $engine): string => $engine->value, TemplateEngine::cases()),
            array_keys(\ConfigKeys::TEMPLATE_ENGINE['choices'])
        );
    }
}
