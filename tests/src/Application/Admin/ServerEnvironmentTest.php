<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Application\Admin;

use LibreBooking\Application\Admin\ServerEnvironment;
use PHPUnit\Framework\TestCase;

class ServerEnvironmentTest extends TestCase
{
    public function testPhpInfoReportsRunningPhpVersionAndSapi(): void
    {
        $info = (new ServerEnvironment())->getPhpInfo();

        $this->assertSame(PHP_VERSION, $info['PHP Version']);
        $this->assertSame(PHP_SAPI, $info['Server API']);
        $this->assertSame(date_default_timezone_get(), $info['Default Timezone']);
    }

    public function testIniSettingsMapUnsetAndUnavailableValues(): void
    {
        $values = [
            'memory_limit' => '256M',
            'date.timezone' => '',
            'opcache.enable' => false,
        ];
        $environment = new ServerEnvironment(
            iniGet: static fn (string $name): string|false => $values[$name] ?? '1',
        );

        $settings = $environment->getIniSettings();

        $this->assertSame(ServerEnvironment::INI_SETTINGS, array_keys($settings));
        $this->assertSame('256M', $settings['memory_limit']);
        $this->assertSame(ServerEnvironment::NOT_SET, $settings['date.timezone']);
        $this->assertSame(ServerEnvironment::NOT_AVAILABLE, $settings['opcache.enable']);
    }

    public function testBooleanIniSettingsReportOnAndOff(): void
    {
        $values = [
            'display_errors' => '',
            'session.auto_start' => '0',
            'opcache.enable' => '1',
            'date.timezone' => '',
        ];
        $environment = new ServerEnvironment(
            iniGet: static fn (string $name): string|false => $values[$name] ?? '1',
        );

        $settings = $environment->getIniSettings();

        $this->assertSame(ServerEnvironment::OFF, $settings['display_errors']);
        $this->assertSame(ServerEnvironment::OFF, $settings['session.auto_start']);
        $this->assertSame(ServerEnvironment::ON, $settings['opcache.enable']);
        $this->assertSame(ServerEnvironment::NOT_SET, $settings['date.timezone']);
    }

    public function testBooleanIniSettingPreservesNonBooleanValue(): void
    {
        $environment = new ServerEnvironment(
            iniGet: static fn (string $name): string|false => $name === 'display_errors' ? 'stderr' : '1',
        );

        $this->assertSame('stderr', $environment->getIniSettings()['display_errors']);
    }
}
