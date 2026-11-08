<?php

require_once(__DIR__ . '/HtmlNormalizer.php');
require_once(__DIR__ . '/../../lib/Common/namespace.php');

use PHPUnit\Framework\TestCase;

abstract class GoldenTemplateTestCase extends TestCase
{
    protected function baselineDir(): string
    {
        return __DIR__ . '/baselines';
    }

    protected function captureSmartyBaseline(string $templateName, array $vars, string $baselineName): void
    {
        $renderer = new SmartyRenderer();
        $html = $renderer->render($templateName, $vars);
        file_put_contents(
            $this->baselineDir() . '/' . $baselineName . '.html',
            HtmlNormalizer::normalize($html)
        );
    }

    protected function assertMatchesBaseline(string $baselineName, string $renderedHtml): void
    {
        $path = $this->baselineDir() . '/' . $baselineName . '.html';
        $normalized = HtmlNormalizer::normalize($renderedHtml);

        if (getenv('UPDATE_GOLDEN') === '1' || !file_exists($path)) {
            file_put_contents($path, $normalized);
            $this->markTestSkipped("Baseline written: $baselineName");
            return;
        }

        $this->assertSame(file_get_contents($path), $normalized, "Golden mismatch: $baselineName");
    }
}
