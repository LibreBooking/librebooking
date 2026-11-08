<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Common\Templating;

use LibreBooking\Common\Templating\TwigEnvironmentFactory;
use PHPUnit\Framework\TestCase;

class TwigEnvironmentFactoryTest extends TestCase
{
    public function testCreatesAutoescapingEnvironment(): void
    {
        $dir = sys_get_temp_dir();
        $env = TwigEnvironmentFactory::create([$dir], $dir . '/twigcache', true);
        $this->assertInstanceOf(\Twig\Environment::class, $env);
        // autoescape on: a rendered variable is HTML-escaped
        $env->setLoader(new \Twig\Loader\ArrayLoader(['t' => '{{ v }}']));
        $this->assertSame('&lt;b&gt;', $env->render('t', ['v' => '<b>']));
    }
}
