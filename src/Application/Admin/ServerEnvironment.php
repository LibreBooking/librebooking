<?php

declare(strict_types=1);

namespace LibreBooking\Application\Admin;

use Closure;

/**
 * Collects information about the PHP runtime for display on the admin
 * Server Settings page.
 */
class ServerEnvironment
{
    public const INI_SETTINGS = [
        'memory_limit',
        'max_execution_time',
        'upload_max_filesize',
        'post_max_size',
        'max_input_vars',
        'date.timezone',
        'display_errors',
        'session.auto_start',
        'opcache.enable',
    ];

    /**
     * Boolean directives, for which ini_get() may return '' when disabled.
     */
    public const BOOLEAN_INI_SETTINGS = [
        'display_errors',
        'session.auto_start',
        'opcache.enable',
    ];

    public const NOT_SET = '(not set)';
    public const NOT_AVAILABLE = '(not available)';
    public const ON = 'On';
    public const OFF = 'Off';

    private Closure $iniGet;

    /**
     * @param ?Closure(string): (string|false) $iniGet defaults to ini_get()
     */
    public function __construct(?Closure $iniGet = null)
    {
        $this->iniGet = $iniGet ?? ini_get(...);
    }

    /**
     * @return array<string, string> label => value
     */
    public function getPhpInfo(): array
    {
        $iniFile = php_ini_loaded_file();

        return [
            'PHP Version' => PHP_VERSION,
            'Server API' => PHP_SAPI,
            'Operating System' => sprintf('%s (%s %s)', PHP_OS_FAMILY, php_uname('s'), php_uname('r')),
            'Web Server' => $_SERVER['SERVER_SOFTWARE'] ?? self::NOT_AVAILABLE,
            'Loaded php.ini' => $iniFile === false ? self::NOT_SET : $iniFile,
            'Default Timezone' => date_default_timezone_get(),
        ];
    }

    /**
     * @return array<string, string> setting name => value
     */
    public function getIniSettings(): array
    {
        $settings = [];
        foreach (self::INI_SETTINGS as $name) {
            $value = ($this->iniGet)($name);
            if ($value === false) {
                $settings[$name] = self::NOT_AVAILABLE;
            } elseif (in_array($name, self::BOOLEAN_INI_SETTINGS, true)) {
                $settings[$name] = $this->formatBooleanIniValue($value);
            } else {
                $settings[$name] = $value === '' ? self::NOT_SET : $value;
            }
        }

        return $settings;
    }

    /**
     * Values other than on/off (e.g. display_errors=stderr) are returned unchanged.
     */
    private function formatBooleanIniValue(string $value): string
    {
        return match (strtolower($value)) {
            '', '0', 'off', 'false', 'no', 'none' => self::OFF,
            '1', 'on', 'true', 'yes' => self::ON,
            default => $value,
        };
    }
}
