<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Templating;

use LibreBooking\Common\Templating\TwigEnvironmentFactory;
use PHPUnit\Framework\TestCase;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

class TwigEnvironmentFactoryTest extends TestCase
{
    private const CACHE_DIR = '/twig-environment-factory-test-cache';

    public function testOutputIsEscapedForHtml(): void
    {
        $environment = $this->createEnvironment(debug: true);
        $environment->setLoader(loader: new ArrayLoader(templates: ['t' => '{{ v }}']));

        $this->assertSame('&lt;b&gt;', $environment->render(name: 't', context: ['v' => '<b>']));
    }

    public function testAnUndefinedVariableRendersAsEmpty(): void
    {
        $environment = $this->createEnvironment(debug: true);
        $environment->setLoader(loader: new ArrayLoader(templates: ['t' => '[{{ missing }}]']));

        $this->assertSame('[]', $environment->render(name: 't'));
    }

    public function testTemplateDirectoriesThatDoNotExistAreLeftOut(): void
    {
        $existing = sys_get_temp_dir();
        $environment = TwigEnvironmentFactory::create(
            templateDirs: [$existing, $existing . '/twig-environment-factory-test-missing'],
            cacheDir: $existing . self::CACHE_DIR,
            debug: true
        );

        $loader = $environment->getLoader();

        $this->assertInstanceOf(FilesystemLoader::class, $loader);
        $this->assertSame([$existing], $loader->getPaths());
    }

    /**
     * A template that two directories have is taken from the one listed first.
     */
    public function testTemplateDirectoriesAreSearchedInTheOrderGiven(): void
    {
        $first = __DIR__;
        $second = sys_get_temp_dir();

        $this->assertSame([$first, $second], $this->templatePaths(templateDirs: [$first, $second]));
        $this->assertSame([$second, $first], $this->templatePaths(templateDirs: [$second, $first]));
    }

    public function testDebugKeepsNoCacheAndReloadsTemplates(): void
    {
        $environment = $this->createEnvironment(debug: true);

        $this->assertTrue($environment->isDebug());
        $this->assertTrue($environment->isAutoReload());
        $this->assertFalse($environment->getCache());
    }

    public function testWithoutDebugCompiledTemplatesAreCached(): void
    {
        $environment = $this->createEnvironment(debug: false);

        $this->assertFalse($environment->isDebug());
        $this->assertFalse($environment->isAutoReload());
        $this->assertSame(sys_get_temp_dir() . self::CACHE_DIR, $environment->getCache());
    }

    /**
     * @param string[] $templateDirs
     * @return string[]
     */
    private function templatePaths(array $templateDirs): array
    {
        $loader = TwigEnvironmentFactory::create(
            templateDirs: $templateDirs,
            cacheDir: sys_get_temp_dir() . self::CACHE_DIR,
            debug: true
        )->getLoader();

        $this->assertInstanceOf(FilesystemLoader::class, $loader);

        return $loader->getPaths();
    }

    private function createEnvironment(bool $debug): \Twig\Environment
    {
        return TwigEnvironmentFactory::create(
            templateDirs: [sys_get_temp_dir()],
            cacheDir: sys_get_temp_dir() . self::CACHE_DIR,
            debug: $debug
        );
    }
}
