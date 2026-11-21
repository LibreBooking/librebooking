<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Templating;

use LibreBooking\Common\Templating\EngineSelector;
use PHPUnit\Framework\TestCase;

class EngineSelectorTest extends TestCase
{
    private string $tempDir = '';

    public function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/engine_selector_test_' . uniqid();
        mkdir($this->tempDir);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->removeDirectory($this->tempDir);
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    public function testReturnsTwigNameWhenTwigFileExists(): void
    {
        touch($this->tempDir . '/foo.twig');

        $result = EngineSelector::twigNameFor('foo.tpl', [$this->tempDir]);

        $this->assertSame('foo.twig', $result);
    }

    public function testReturnsNullWhenOnlyTplExists(): void
    {
        touch($this->tempDir . '/foo.tpl');

        $result = EngineSelector::twigNameFor('foo.tpl', [$this->tempDir]);

        $this->assertNull($result);
    }

    public function testReturnsNullWhenNoFileExists(): void
    {
        $result = EngineSelector::twigNameFor('foo.tpl', [$this->tempDir]);

        $this->assertNull($result);
    }

    public function testFindsFileInSecondDir(): void
    {
        $dir1 = $this->tempDir . '/dir1';
        $dir2 = $this->tempDir . '/dir2';
        mkdir($dir1);
        mkdir($dir2);
        touch($dir2 . '/bar.twig');

        $result = EngineSelector::twigNameFor('bar.tpl', [$dir1, $dir2]);

        $this->assertSame('bar.twig', $result);
    }

    public function testFindsSubdirTemplate(): void
    {
        $subdir = $this->tempDir . '/Sub';
        mkdir($subdir, recursive: true);
        touch($subdir . '/bar.twig');

        $result = EngineSelector::twigNameFor('Sub/bar.tpl', [$this->tempDir]);

        $this->assertSame('Sub/bar.twig', $result);
    }

    public function testAlreadyDotTwigInputUsedAsIs(): void
    {
        touch($this->tempDir . '/foo.twig');

        $result = EngineSelector::twigNameFor('foo.twig', [$this->tempDir]);

        $this->assertSame('foo.twig', $result);
    }

    public function testFirstDirWinsWhenBothHaveTwigFile(): void
    {
        $dir1 = $this->tempDir . '/dir1';
        $dir2 = $this->tempDir . '/dir2';
        mkdir($dir1);
        mkdir($dir2);
        touch($dir1 . '/page.twig');
        touch($dir2 . '/page.twig');

        $result = EngineSelector::twigNameFor('page.tpl', [$dir1, $dir2]);

        $this->assertSame('page.twig', $result);
    }

    /**
     * Smoke check against the real template directory: now that login.twig
     * exists, engine selection must route login.tpl requests to the Twig engine.
     */
    public function testRealLoginTemplateSelectsTwig(): void
    {
        $tplDir = __DIR__ . '/../../../../tpl';

        $result = EngineSelector::twigNameFor('login.tpl', [$tplDir]);

        $this->assertSame('login.twig', $result);
    }
}
