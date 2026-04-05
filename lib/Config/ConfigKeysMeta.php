<?php

class ConfigKeysMeta
{
    public const SECTION_TITLES = [
        'api' => 'API Configuration',
        'authentication' => 'Authentication Settings',
        'cleanup' => 'Data Retention and Deletion',
        'credits' => 'Credit System Settings',
        'database' => 'Database',
        'email' => 'Email',
        'ics' => 'ICS Settings',
        'logging' => 'Logging',
        'pages' => 'Pages',
        'password' => 'Password Policy',
        'phpmailer' => 'Email Sending (PHPMailer)',
        'plugins' => 'Plugin Configuration',
        'privacy' => 'Privacy',
        'recaptcha' => 'reCAPTCHA',
        'registration' => 'Registration',
        'reports' => 'Reporting and Registration',
        'reservation' => 'Reservations',
        'reservation.labels' => 'Reservation Label Templates',
        'reservation.notify' => 'Notification Settings for Reservations',
        'resource' => 'Resource Options',
        'schedule' => 'Schedule Display and Behavior',
        'security' => 'Security Headers',
        'tablet.view' => 'Tablet View Options',
        'uploads' => 'Uploads',
    ];

    public const TOP_LEVEL_GROUPS = [
        'Application configuration' => [
            'app.title',
            'app.debug',
            'admin.email',
            'admin.email.name',
            'company.name',
            'company.url',
        ],
        'Language and Timezone' => [
            'default.timezone',
            'default.language',
            'enabled.languages',
        ],
        'Frontend' => [
            'script.url',
            'install.password',
            'cache.templates',
            'use.local.js.libs',
            'inactivity.timeout',
            'home.url',
            'logout.url',
            'default.homepage',
            'default.page.size',
            'css.extension.file',
            'css.theme',
            'name.format',
        ],
        'Analytics Integration' => [
            'google.analytics.tracking.id',
        ],
        'Slack Integration' => [
            'slack.token',
        ],
    ];
}
