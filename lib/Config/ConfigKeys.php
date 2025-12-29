<?php

class ConfigKeys
{
    // Application configuration

    public const APP_TITLE = [
        'key' => 'app.title',
        'type' => 'string',
        'default' => 'LibreBooking',
        'label' => 'App title',
        'description' => 'The title of the application displayed in the header and browser tab',
    ];
    public const APP_DEBUG = [
        'key' => 'app.debug',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Debug Mode',
        'description' => 'Enable or disable debug mode for the application'
    ];

    public const ADMIN_EMAIL = [
        'key' => 'admin.email',
        'type' => 'string',
        'default' => 'admin@example.com',
        'label' => 'Administrator Email',
        'description' => 'Administrator email address'
    ];

    public const ADMIN_EMAIL_NAME = [
        'key' => 'admin.email.name',
        'type' => 'string',
        'default' => 'LB Administrator',
        'label' => 'Administrator Display Name',
        'description' => 'Display name used for outgoing admin emails'
    ];

    public const COMPANY_NAME = [
        'key' => 'company.name',
        'type' => 'string',
        'default' => '',
        'label' => 'Company Name',
        'description' => 'Company name to show in the page header'
    ];

    public const COMPANY_URL = [
        'key' => 'company.url',
        'type' => 'string',
        'default' => '',
        'label' => 'Company URL',
        'description' => 'URL to the company\'s website'
    ];

    public const SCRIPT_URL = [
        'key' => 'script.url',
        'type' => 'string',
        'default' => '',
        'label' => 'Script URL',
        'description' => 'Public URL to the Web directory of this instance'
    ];

    public const VERSION = [
        'key' => 'version',
        'type' => 'string',
        'default' => '',
        'label' => 'Application Version',
        'description' => 'The version of the application',
        'is_hidden' => true
    ];

    // Language and Timezone
    public const DEFAULT_TIMEZONE = [
        'key' => 'default.timezone',
        'type' => 'string',
        'default' => 'Europe/London',
        'label' => 'Default Timezone',
        'description' => 'Look up here http://php.net/manual/en/timezones.php',
    ];

    # previously LANGUAGE
    public const DEFAULT_LANGUAGE = [
        'key' => 'default.language',
        'type' => 'string',
        'default' => 'en_us',
        'label' => 'Default Language',
        'description' => 'Default language for the application'
    ];

    // Frontend

    # previously INSTALLATION_PASSWORD
    public const INSTALL_PASSWORD = [
        'key' => 'install.password',
        'type' => 'string',
        'default' => '',
        'label' => 'Installation Password',
        'description' => 'Password required for installation or upgrades',
        'is_hidden' => true
    ];

    public const CACHE_TEMPLATES = [
        'key' => 'cache.templates',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Cache Templates',
        'description' => 'Enable or disable template caching',
    ];

    # previously USE_LOCAL_JS
    public const USE_LOCAL_JS_LIBS = [
        'key' => 'use.local.js.libs',
        'legacy' => 'use.local.js',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Use Local JS Libraries',
        'description' => 'Use local JavaScript libraries instead of CDN',
    ];

    public const INACTIVITY_TIMEOUT = [
        'key' => 'inactivity.timeout',
        'type' => 'integer',
        'default' => 30,
        'label' => 'Inactivity Timeout',
        'description' => 'Time in minutes before a user is logged out due to inactivity',
    ];

    public const HOME_URL = [
        'key' => 'home.url',
        'type' => 'string',
        'default' => '',
        'label' => 'Home URL',
        'description' => 'URL to redirect users after login',
    ];

    public const LOGOUT_URL = [
        'key' => 'logout.url',
        'type' => 'string',
        'default' => '',
        'label' => 'Logout URL',
        'description' => 'URL to redirect users after logout',
    ];

    public const DEFAULT_HOMEPAGE = [
        'key' => 'default.homepage',
        'type' => 'integer',
        'default' => 1,
        'choices' => [
            1 => 'Dashboard',
            2 => 'Schedule',
            3 => 'My Calendar',
            4 => 'Resource Calendar'
        ],
        'label' => 'Default Homepage',
        'description' => 'Default homepage for new users',
    ];

    public const CSS_EXTENSION_FILE = [
        'key' => 'css.extension.file',
        'type' => 'string',
        'default' => '',
        'label' => 'CSS Extension File',
        'description' => 'Path to a custom CSS file to extend the default styles',
    ];

    public const CSS_THEME = [
        'key' => 'css.theme',
        'type' => 'string',
        'default' => 'default',
        'choices' => [
            'default' => 'Default',
            'dimgray' => 'Dim Gray',
            'dark_red' => 'Dark Red',
            'dark_green' => 'Dark Green',
            'french_blue' => 'French Blue',
            'cake_blue' => 'Cake Blue',
            'orange' => 'Orange'
        ],
        'label' => 'CSS Theme',
        'description' => 'Theme to use for the application. Options: default, dimgray, dark_red, dark_green, french_blue, cake_blue, orange',
    ];

    public const NAME_FORMAT = [
        'key' => 'name.format',
        'type' => 'string',
        'default' => '{first} {last}',
        'label' => 'Name Format',
        'description' => 'Format for displaying user names',
    ];

    # previously PAGES_ENABLE_CONFIGURATION
    public const PAGES_CONFIGURATION_ENABLED = [
        'key' => 'pages.configuration.enabled',
        'legacy' => 'pages.enable.configuration',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Enable Configuration Page',
        'description' => 'Enable or disable the configuration page in the admin panel',
        'section' => 'pages'
    ];

    public const REPORTS_ALLOW_ALL_USERS = [
        'key' => 'reports.allow.all.users',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow All Users to Access Reports',
        'description' => 'Allow all users to access reports, not just admins',
        'section' => 'reports'
    ];

    public const DEFAULT_PAGE_SIZE = [
        'key' => 'default.page.size',
        'type' => 'integer',
        'default' => 50,
        'label' => 'Default Page Size',
        'description' => 'Default number of items per page in listings'
    ];

    // Database
    public const DATABASE_TYPE = [
        'key' => 'database.type',
        'type' => 'string',
        'default' => 'mysql',
        'label' => 'Database Type',
        'description' => 'Type of database used by the application',
        'section' => 'database'
    ];

    public const DATABASE_HOSTSPEC = [
        'key' => 'database.hostspec',
        'type' => 'string',
        'default' => '127.0.0.1',
        'label' => 'Database Host',
        'description' => 'Hostname or IP address of the database server',
        'section' => 'database',
        'is_private' => true
    ];

    public const DATABASE_NAME = [
        'key' => 'database.name',
        'type' => 'string',
        'default' => 'librebooking',
        'label' => 'Database Name',
        'description' => 'Name of the database used by the application',
        'section' => 'database'
    ];

    public const DATABASE_USER = [
        'key' => 'database.user',
        'type' => 'string',
        'default' => 'lb_user',
        'label' => 'Database User',
        'description' => 'Username for connecting to the database',
        'section' => 'database',
        'is_private' => true
    ];

    public const DATABASE_PASSWORD = [
        'key' => 'database.password',
        'type' => 'string',
        'default' => 'password',
        'label' => 'Database Password',
        'description' => 'Password for connecting to the database',
        'section' => 'database',
        'is_private' => true
    ];

    // Email Sending (PHPMailer)
    public const PHPMAILER_MAILER = [
        'key' => 'phpmailer.mailer',
        'type' => 'string',
        'default' => 'smtp',
        'choices' => [
            'mail' => 'mail',
            'sendmail' => 'sendmail',
            'smtp' => 'smtp'
        ],
        'label' => 'PHPMailer Mailer',
        'description' => 'Mailer type to use for sending emails',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_HOST = [
        'key' => 'phpmailer.smtp.host',
        'type' => 'string',
        'default' => '',
        'label' => 'SMTP Host',
        'description' => 'SMTP server hostname',
        'is_private' => true,
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_PORT = [
        'key' => 'phpmailer.smtp.port',
        'type' => 'integer',
        'default' => 25,
        'label' => 'SMTP Port',
        'description' => 'SMTP server port',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_SECURE = [
        'key' => 'phpmailer.smtp.secure',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
            'tls' => 'TLS',
            'ssl' => 'SSL'
        ],
        'label' => 'SMTP Secure',
        'description' => 'Encryption type for SMTP',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_AUTH = [
        'key' => 'phpmailer.smtp.auth',
        'type' => 'boolean',
        'default' => true,
        'label' => 'SMTP Authentication',
        'description' => 'Enable SMTP authentication',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_USERNAME = [
        'key' => 'phpmailer.smtp.username',
        'type' => 'string',
        'default' => '',
        'label' => 'SMTP Username',
        'description' => 'Username for SMTP authentication',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_PASSWORD = [
        'key' => 'phpmailer.smtp.password',
        'type' => 'string',
        'default' => '',
        'label' => 'SMTP Password',
        'description' => 'Password for SMTP authentication',
        'section' => 'phpmailer',
        'is_private' => true
    ];

    public const PHPMAILER_SENDMAIL_PATH = [
        'key' => 'phpmailer.sendmail.path',
        'type' => 'string',
        'default' => '/usr/sbin/sendmail',
        'label' => 'Sendmail Path',
        'description' => 'Path to the sendmail binary',
        'section' => 'phpmailer'
    ];

    public const PHPMAILER_SMTP_DEBUG = [
        'key' => 'phpmailer.smtp.debug',
        'type' => 'boolean',
        'default' => false,
        'label' => 'SMTP Debug Level',
        'description' => 'Enable SMTP debug output (true/false)',
        'section' => 'phpmailer'
    ];

    // Email
    # previously ENABLE_EMAIL
    public const EMAIL_ENABLED = [
        'key' => 'email.enabled',
        'legacy' => 'enable.email',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Enable Email',
        'description' => 'Enable or disable email notifications',
        'section' => 'email'
    ];

    # previously ENFORCE_CUSTOM_MAIL_TEMPLATE
    public const EMAIL_ENFORCE_CUSTOM_TEMPLATE = [
        'key' => 'email.enforce.custom.template',
        'legacy' => 'enforce.custom.mail.template',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enforce Custom Email Template',
        'description' => 'Force the use of a custom email template for all emails',
        'section' => 'email'
    ];

    # previously DEFAULT_FROM_ADDRESS
    public const EMAIL_DEFAULT_FROM_ADDRESS = [
        'key' => 'email.default.from.address',
        'type' => 'string',
        'default' => '',
        'label' => 'Default From Address',
        'description' => 'Default email address for outgoing emails',
        'section' => 'email'
    ];

    # previously DEFAULT_FROM_NAME
    public const EMAIL_DEFAULT_FROM_NAME = [
        'key' => 'email.default.from.name',
        'type' => 'string',
        'default' => '',
        'label' => 'Default From Name',
        'description' => 'Default display name for outgoing emails',
        'section' => 'email'
    ];

    // Logging
    public const LOGGING_FOLDER = [
        'key' => 'logging.folder',
        'type' => 'string',
        'default' => '/var/log/librebooking/log',
        'label' => 'Logging Folder',
        'description' => 'Directory where log files are stored',
        'section' => 'logging'
    ];

    public const LOGGING_LEVEL = [
        'key' => 'logging.level',
        'type' => 'string',
        'default' => 'error',
        'choices' => [
            'none' => 'None',
            'error' => 'error',
            'debug' => 'debug',
        ],
        'label' => 'Logging Level',
        'description' => 'Logging level: none, debug, error',
        'section' => 'logging'
    ];

    public const LOGGING_SQL = [
        'key' => 'logging.sql',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Log SQL Queries',
        'description' => 'Enable or disable logging of SQL queries',
        'section' => 'logging'
    ];

    // Uploads
    # previously IMAGE_UPLOAD_DIRECTORY
    public const UPLOAD_IMAGE_DIRECTORY = [
        'key' => 'uploads.image.upload.directory',
        'legacy' => 'image.upload.directory',
        'type' => 'string',
        'default' => 'Web/uploads/images',
        'label' => 'Image Upload Directory',
        'description' => 'Directory for uploaded images',
        'section' => 'uploads'
    ];

    # previously IMAGE_UPLOAD_URL
    public const UPLOAD_IMAGE_URL = [
        'key' => 'uploads.image.upload.url',
        'legacy' => 'image.upload.url',
        'type' => 'string',
        'default' => 'Web/uploads/attachments',
        'label' => 'Image Upload URL',
        'description' => 'URL path for uploaded images',
        'section' => 'uploads'
    ];

    # previously UPLOAD_ENABLE_RESERVATION_ATTACHMENTS
    public const UPLOAD_RESERVATION_ATTACHMENTS_ENABLED = [
        'key' => 'uploads.reservation.attachments.enabled',
        'legacy' => 'upload.enable.reservation.attachments',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Reservation Attachments',
        'description' => 'Allow users to attach files to reservations',
        'section' => 'uploads'
    ];

    # previously UPLOAD_RESERVATION_ATTACHMENTS
    public const UPLOAD_RESERVATION_ATTACHMENT_PATH = [
        'key' => 'uploads.reservation.attachment.path',
        'type' => 'string',
        'default' => 'Web/uploads/attachments',
        'label' => 'Reservation Attachment Path',
        'description' => 'Directory for reservation attachments',
        'section' => 'uploads'
    ];

    # previously UPLOAD_RESERVATION_EXTENSIONS
    public const UPLOAD_RESERVATION_ATTACHMENT_EXTENSIONS = [
        'key' => 'uploads.reservation.attachment.extensions',
        'type' => 'string',
        'default' => 'pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif',
        'label' => 'Allowed Attachment Extensions',
        'description' => 'Comma-separated list of allowed file extensions for attachments',
        'section' => 'uploads'
    ];

    // Resource settings

    public const RESOURCE_CONTACT_IS_USER =  [
        'key' => 'resource.contact.is.user',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Is User Contact',
        'description' => 'Indicates if the contact must be a registered user',
        'section' => 'resource'
    ];

    // Notification Settings for Reservations

    # previously NOTIFY_CREATE_RESOURCE_ADMINS
    public const RESERVATION_NOTIFY_APPLICATION_ADMIN_ADD = [
        'key' => 'reservation.notify.application.admin.add',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Application Admin on Reservation Add',
        'description' => 'Send notification to application administrators when a reservation is created',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_UPDATE_APPLICATION_ADMINS
    public const RESERVATION_NOTIFY_APPLICATION_ADMIN_UPDATE = [
        'key' => 'reservation.notify.application.admin.update',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Application Admin on Reservation Update',
        'description' => 'Send notification to application administrators when a reservation is updated',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_DELETE_APPLICATION_ADMINS
    public const RESERVATION_NOTIFY_APPLICATION_ADMIN_DELETE = [
        'key' => 'reservation.notify.application.admin.delete',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Application Admin on Reservation Delete',
        'description' => 'Send notification to application administrators when a reservation is deleted',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_APPROVAL_APPLICATION_ADMINS
    public const RESERVATION_NOTIFY_APPLICATION_ADMIN_APPROVAL = [
        'key' => 'reservation.notify.application.admin.approval',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Application Admin on Reservation Approval',
        'description' => 'Send notification to application administrators when a reservation requires approval',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_CREATE_GROUP_ADMINS
    public const RESERVATION_NOTIFY_GROUP_ADMIN_ADD = [
        'key' => 'reservation.notify.group.admin.add',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Group Admin on Reservation Add',
        'description' => 'Send notification to group administrators when a reservation is created',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_UPDATE_GROUP_ADMINS
    public const RESERVATION_NOTIFY_GROUP_ADMIN_UPDATE = [
        'key' => 'reservation.notify.group.admin.update',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Group Admin on Reservation Update',
        'description' => 'Send notification to group administrators when a reservation is updated',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_DELETE_GROUP_ADMINS
    public const RESERVATION_NOTIFY_GROUP_ADMIN_DELETE = [
        'key' => 'reservation.notify.group.admin.delete',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Group Admin on Reservation Delete',
        'description' => 'Send notification to group administrators when a reservation is deleted',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_APPROVAL_GROUP_ADMINS
    public const RESERVATION_NOTIFY_GROUP_ADMIN_APPROVAL = [
        'key' => 'reservation.notify.group.admin.approval',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Group Admin on Reservation Approval',
        'description' => 'Send notification to group administrators when a reservation requires approval',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_CREATE_RESOURCE_ADMINS
    public const RESERVATION_NOTIFY_RESOURCE_ADMIN_ADD = [
        'key' => 'reservation.notify.resource.admin.add',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Resource Admin on Reservation Add',
        'description' => 'Send notification to resource administrators when a reservation is created',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_UPDATE_RESOURCE_ADMINS
    public const RESERVATION_NOTIFY_RESOURCE_ADMIN_UPDATE = [
        'key' => 'reservation.notify.resource.admin.update',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Resource Admin on Reservation Update',
        'description' => 'Send notification to resource administrators when a reservation is updated',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_DELETE_RESOURCE_ADMINS
    public const RESERVATION_NOTIFY_RESOURCE_ADMIN_DELETE = [
        'key' => 'reservation.notify.resource.admin.delete',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Resource Admin on Reservation Delete',
        'description' => 'Send notification to resource administrators when a reservation is deleted',
        'section' => 'reservation.notify'
    ];
    # previously NOTIFY_APPROVAL_RESOURCE_ADMINS
    public const RESERVATION_NOTIFY_RESOURCE_ADMIN_APPROVAL = [
        'key' => 'reservation.notify.resource.admin.approval',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Resource Admin on Reservation Approval',
        'description' => 'Send notification to resource administrators when a reservation requires approval',
        'section' => 'reservation.notify'
    ];

    // Schedule Display and Behavior

    public const SCHEDULE_AUTO_SCROLL_TODAY = [
        'key' => 'schedule.auto.scroll.today',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Auto Scroll to Today',
        'description' => 'Automatically scroll the schedule view to today\'s date',
        'section' => 'schedule'
    ];
    public const SCHEDULE_SHOW_WEEK_NUMBERS = [
        'key' => 'schedule.show.week.numbers',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Show Week Numbers',
        'description' => 'Display week numbers in the schedule view',
        'section' => 'schedule'
    ];
    public const SCHEDULE_HIDE_BLOCKED_PERIODS = [
        'key' => 'schedule.hide.blocked.periods',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Blocked Periods',
        'description' => 'Hide periods that are blocked from reservation in the schedule view',
        'section' => 'schedule'
    ];
    public const SCHEDULE_SHOW_INACCESSIBLE_RESOURCES = [
        'key' => 'schedule.show.inaccessible.resources',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Show Inaccessible Resources',
        'description' => 'Display resources that the user cannot access in the schedule view',
        'section' => 'schedule'
    ];
    public const SCHEDULE_RESERVATION_LABEL = [
        'key' => 'schedule.reservation.label',
        'type' => 'string',
        'default' => '{name}',
        'label' => 'Reservation Label',
        'description' => 'Label template for reservations in the schedule view',
        'section' => 'schedule'
    ];
    # previously SCHEDULE_PER_USER_COLORS
    public const SCHEDULE_USE_PER_USER_COLORS = [
        'key' => 'schedule.use.per.user.colors',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Use Per-User Colors',
        'description' => 'Display reservations in different colors for each user',
        'section' => 'schedule'
    ];
    public const SCHEDULE_UPDATE_HIGHLIGHT_MINUTES = [
        'key' => 'schedule.update.highlight.minutes',
        'type' => 'integer',
        'default' => 0,
        'label' => 'Update Highlight Minutes',
        'description' => 'Number of minutes to highlight updated reservations in the schedule',
        'section' => 'schedule'
    ];
    public const SCHEDULE_FAST_RESERVATION_LOAD = [
        'key' => 'schedule.fast.reservation.load',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Fast Reservation Load',
        'description' => 'Enable fast loading of reservations in the schedule view',
        'section' => 'schedule'
    ];
    public const SCHEDULE_LOAD_MOBILE_VIEWS = [
        'key' => 'schedule.load.mobile.views',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Load Mobile Views',
        'description' => 'Enable mobile-optimized views for the schedule',
        'section' => 'schedule'
    ];

    // Reservations
    public const RESERVATION_PREVENT_PARTICIPATION = [
        'key' => 'reservation.prevent.participation',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Prevent Participation',
        'description' => 'Prevent users from participating in reservations',
        'section' => 'reservation'
    ];
    public const RESERVATION_PREVENT_RECURRENCE = [
        'key' => 'reservation.prevent.recurrence',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Prevent Recurrence',
        'description' => 'Prevent recurring reservations',
        'section' => 'reservation'
    ];
    # previously RESERVATION_ALLOW_GUESTS
    public const RESERVATION_ALLOW_GUEST_PARTICIPATION = [
        'key' => 'reservation.allow.guest.participation',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow Guest Participation',
        'description' => 'Allow guests to participate in reservations',
        'section' => 'reservation'
    ];
    public const RESERVATION_ALLOW_WAITLIST = [
        'key' => 'reservation.allow.wait.list',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow Waitlist',
        'description' => 'Allow users to join a waitlist for reservations',
        'section' => 'reservation'
    ];
    public const RESERVATION_START_TIME_CONSTRAINT = [
        'key' => 'reservation.start.time.constraint',
        'type' => 'string',
        'default' => 'future',
        'choices' => [
            'none' => 'Any time',
            'future' => 'Future',
            'same_day' => 'Same day'
        ],
        'label' => 'Start Time Constraint',
        'description' => 'Restrict start times. Options: future, any, same_day',
        'section' => 'reservation'
    ];
    public const RESERVATION_UPDATES_REQUIRE_APPROVAL = [
        'key' => 'reservation.updates.require.approval',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Updates Require Approval',
        'description' => 'Require admin approval for reservation updates',
        'section' => 'reservation'
    ];
    public const RESERVATION_TITLE_REQUIRED = [
        'key' => 'reservation.title.required',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Title Required',
        'description' => 'Require a title for reservations',
        'section' => 'reservation'
    ];
    public const RESERVATION_DESCRIPTION_REQUIRED = [
        'key' => 'reservation.description.required',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Description Required',
        'description' => 'Require a description for reservations',
        'section' => 'reservation'
    ];

    # previously RESERVATION_CHECKIN_MINUTES
    public const RESERVATION_CHECKIN_MINUTES_PRIOR = [
        'key' => 'reservation.checkin.minutes.prior',
        'type' => 'integer',
        'default' => 5,
        'label' => 'Check-in Minutes Prior',
        'description' => 'Number of minutes before reservation start when check-in is allowed',
        'section' => 'reservation'
    ];
    public const RESERVATION_CHECKIN_ADMIN_ONLY = [
        'key' => 'reservation.checkin.admin.only',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Check-in Admin Only',
        'description' => 'Only admins can check in reservations',
        'section' => 'reservation'
    ];
    public const RESERVATION_CHECKOUT_ADMIN_ONLY = [
        'key' => 'reservation.checkout.admin.only',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Checkout Admin Only',
        'description' => 'Only admins can check out reservations',
        'section' => 'reservation'
    ];

    # previously RESERVATION_ENABLE_REMINDERS
    public const RESERVATION_REMINDERS_ENABLED = [
        'key' => 'reservation.reminders.enabled',
        'legacy' => 'reservation.enable.reminders',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Reservation Reminders',
        'description' => 'Enable email reminders for reservations',
        'section' => 'reservation'
    ];
    # previously RESERVATION_DEFAULT_START_REMINDER
    public const RESERVATION_REMINDER_DEFAULT_START = [
        'key' => 'reservation.default.start.reminder',
        'type' => 'string',
        'default' => '',
        'label' => 'Default Start Reminder (minutes)',
        'description' => 'Default start reservation reminder. format is ## interval. for example, 10 minutes, 2 hours, 6 days.',
        'section' => 'reservation'
    ];
    # previously RESERVATION_DEFAULT_END_REMINDER
    public const RESERVATION_REMINDER_DEFAULT_END = [
        'key' => 'reservation.default.end.reminder',
        'type' => 'string',
        'default' => '',
        'label' => 'Default End Reminder (minutes)',
        'description' => 'Default end reservation reminder. format is ## interval. for example, 10 minutes, 2 hours, 6 days.',
        'section' => 'reservation'
    ];

    // Reservation Label Templates

    public const RESERVATION_LABELS_ICS_SUMMARY = [
        'key' => 'reservation.labels.ics.summary',
        'type' => 'string',
        'default' => '{title}',
        'label' => 'ICS Summary Label',
        'description' => 'Label template for ICS calendar summary',
        'section' => 'reservation.labels'
    ];
    # previously RESERVATION_LABELS_MY_ICS_SUMMARY
    public const RESERVATION_LABELS_ICS_MY_SUMMARY = [
        'key' => 'reservation.labels.ics.my.summary',
        'type' => 'string',
        'default' => '{title}',
        'label' => 'ICS My Summary Label',
        'description' => 'Label template for ICS calendar summary for my reservations',
        'section' => 'reservation.labels'
    ];
    public const RESERVATION_LABELS_RSS_DESCRIPTION = [
        'key' => 'reservation.labels.rss.description',
        'type' => 'string',
        'default' => '<div><span>Start</span> {startdate}</div><div><span>End</span> {enddate}</div><div><span>Organizer</span> {name}</div><div><span>Description</span> {description}</div>',
        'label' => 'RSS Description Label',
        'description' => 'Label template for RSS feed description',
        'section' => 'reservation.labels'
    ];
    public const RESERVATION_LABELS_MY_CALENDAR = [
        'key' => 'reservation.labels.my.calendar',
        'type' => 'string',
        'default' => '{resourcename} {title}',
        'label' => 'My Calendar Label',
        'description' => 'Label template for my calendar',
        'section' => 'reservation.labels'
    ];
    public const RESERVATION_LABELS_RESOURCE_CALENDAR = [
        'key' => 'reservation.labels.resource.calendar',
        'type' => 'string',
        'default' => '{name}',
        'label' => 'Resource Calendar Label',
        'description' => 'Label template for resource calendar',
        'section' => 'reservation.labels'
    ];
    public const RESERVATION_LABELS_RESERVATION_POPUP = [
        'key' => 'reservation.labels.reservation.popup',
        'type' => 'string',
        'default' => '',
        'label' => 'Reservation Popup Label',
        'description' => 'Label template for reservation popup',
        'section' => 'reservation.labels'
    ];

    // Registration

    # previously ALLOW_REGISTRATION
    public const REGISTRATION_ALLOW_SELF = [
        'key' => 'registration.allow.self.registration',
        'legacy' => 'allow.self.registration',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Allow Self Registration',
        'description' => 'Allow users to register themselves',
        'section' => 'registration'
    ];
    public const REGISTRATION_REQUIRE_PHONE = [
        'key' => 'registration.require.phone',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Require Phone',
        'description' => 'Require phone number during registration',
        'section' => 'registration'
    ];
    public const REGISTRATION_REQUIRE_POSITION = [
        'key' => 'registration.require.position',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Require Position',
        'description' => 'Require position during registration',
        'section' => 'registration'
    ];
    public const REGISTRATION_REQUIRE_ORGANIZATION = [
        'key' => 'registration.require.organization',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Require Organization',
        'description' => 'Require organization during registration',
        'section' => 'registration'
    ];
    public const REGISTRATION_HIDE_PHONE = [
        'key' => 'registration.hide.phone',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Phone',
        'description' => 'Hide phone field during registration',
        'section' => 'registration'
    ];
    public const REGISTRATION_HIDE_POSITION = [
        'key' => 'registration.hide.position',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Position',
        'description' => 'Hide position field during registration',
        'section' => 'registration'
    ];
    public const REGISTRATION_HIDE_ORGANIZATION = [
        'key' => 'registration.hide.organization',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Organization',
        'description' => 'Hide organization field during registration',
        'section' => 'registration'
    ];
    # previously REGISTRATION_ENABLE_CAPTCHA
    public const REGISTRATION_CAPTCHA_ENABLED = [
        'key' => 'registration.captcha.enabled',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Enable Registration Captcha',
        'description' => 'Enable captcha on the registration form',
        'section' => 'registration'
    ];
    # previously REGISTRATION_REQUIRE_ACTIVATION
    public const REGISTRATION_REQUIRE_EMAIL_ACTIVATION = [
        'key' => 'registration.require.email.activation',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Require Email Activation',
        'description' => 'Require email activation for new registrations',
        'section' => 'registration'
    ];
    public const REGISTRATION_AUTO_SUBSCRIBE_EMAIL = [
        'key' => 'registration.auto.subscribe.email',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Auto Subscribe Email',
        'description' => 'Automatically subscribe new users to email notifications',
        'section' => 'registration'
    ];
    # previously REGISTRATION_NOTIFY
    public const REGISTRATION_NOTIFY_ADMIN = [
        'key' => 'registration.notify.admin',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Notify Admin on Registration',
        'description' => 'Send notification to admin when a new user registers',
        'section' => 'registration'
    ];

    // Tablet View Options
    public const TABLET_VIEW_ALLOW_RESERVATIONS = [
        'key' => 'tablet.view.allow.reservations',
        'type' => 'boolean',
        'default' => true,
        'label' => 'Allows reservations',
        'description' => 'Allows users to make reservations in the tablet view',
        'section' => 'tablet.view'
    ];

    # previously TABLET_VIEW_ALLOW_GUESTS
    public const TABLET_VIEW_ALLOW_GUEST_RESERVATIONS = [
        'key' => 'tablet.view.allow.guest.reservations',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow Guest Reservations (Tablet View)',
        'description' => 'Allow guests to make reservations from the tablet view',
        'section' => 'tablet.view'
    ];
    # previously TABLET_VIEW_AUTOCOMPLETE
    public const TABLET_VIEW_AUTO_SUGGEST_EMAILS = [
        'key' => 'tablet.view.auto.suggest.emails',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Auto Suggest Emails (Tablet View)',
        'description' => 'Enable email auto-suggestion in tablet view',
        'section' => 'tablet.view'
    ];

    // ICS Settings

    public const ICS_SUBSCRIPTION_KEY = [
        'key' => 'ics.subscription.key',
        'type' => 'string',
        'default' => '',
        'label' => 'ICS Subscription Key',
        'description' => 'Key required for ICS calendar subscriptions',
        'section' => 'ics'
    ];
    public const ICS_FUTURE_DAYS = [
        'key' => 'ics.future.days',
        'type' => 'integer',
        'default' => 30,
        'label' => 'ICS Future Days',
        'description' => 'Number of future days to include in ICS feeds',
        'section' => 'ics'
    ];
    public const ICS_PAST_DAYS = [
        'key' => 'ics.past.days',
        'type' => 'integer',
        'default' => 0,
        'label' => 'ICS Past Days',
        'description' => 'Number of past days to include in ICS feeds',
        'section' => 'ics'
    ];

    // Data Retention and Deletion

    # previously YEARS_OLD_DATA
    public const CLEANUP_YEARS_OLD_DATA = [
        'key' => 'cleanup.years.old.data',
        'legacy' => 'delete.old.data.years.old.data',
        'type' => 'integer',
        'default' => 3,
        'label' => 'Cleanup Years Old Data',
        'description' => 'Delete data older than this number of years',
        'section' => 'cleanup'
    ];
    # previously DELETE_OLD_ANNOUNCEMENTS
    public const CLEANUP_DELETE_OLD_ANNOUNCEMENTS = [
        'key' => 'cleanup.delete.old.announcements',
        'legacy' => 'delete.old.data.delete.old.announcements',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Delete Old Announcements',
        'description' => 'Delete old announcements during cleanup',
        'section' => 'cleanup'
    ];
    # previously DELETE_OLD_BLACKOUTS
    public const CLEANUP_DELETE_OLD_BLACKOUTS = [
        'key' => 'cleanup.delete.old.blackouts',
        'legacy' => 'delete.old.data.delete.old.blackouts',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Delete Old Blackouts',
        'description' => 'Delete old blackouts during cleanup',
        'section' => 'cleanup'
    ];
    # previously DELETE_OLD_RESERVATIONS
    public const CLEANUP_DELETE_OLD_RESERVATIONS = [
        'key' => 'cleanup.delete.old.reservations',
        'legacy' => 'delete.old.data.delete.old.reservations',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Delete Old Reservations',
        'description' => 'Delete old reservations during cleanup',
        'section' => 'cleanup'
    ];

    // Password Policy

    # previously DISABLE_PASSWORD_RESET
    public const PASSWORD_DISABLE_RESET = [
        'key' => 'password.disable.reset',
        'legacy' => 'disable.password.reset',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Disable Password Reset',
        'description' => 'Disable the password reset feature',
        'section' => 'password'
    ];
    # previously PASSWORD_LETTERS
    public const PASSWORD_MINIMUM_LETTERS = [
        'key' => 'password.minimum.letters',
        'type' => 'integer',
        'default' => 6,
        'label' => 'Minimum Letters in Password',
        'description' => 'Minimum number of letters required in passwords',
        'section' => 'password'
    ];
    # previously PASSWORD_NUMBERS
    public const PASSWORD_MINIMUM_NUMBERS = [
        'key' => 'password.minimum.numbers',
        'type' => 'integer',
        'default' => 0,
        'label' => 'Minimum Numbers in Password',
        'description' => 'Minimum number of numbers required in passwords',
        'section' => 'password'
    ];
    public const PASSWORD_UPPER_AND_LOWER = [
        'key' => 'password.upper.and.lower',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Require Upper and Lower Case',
        'description' => 'Require both upper and lower case letters in passwords',
        'section' => 'password'
    ];

    // Privacy

    public const PRIVACY_VIEW_SCHEDULES = [
        'key' => 'privacy.view.schedules',
        'type' => 'boolean',
        'default' => true,
        'label' => 'View Schedules',
        'description' => 'Allow users to view schedules',
        'section' => 'privacy'
    ];
    public const PRIVACY_VIEW_RESERVATIONS = [
        'key' => 'privacy.view.reservations',
        'type' => 'boolean',
        'default' => false,
        'label' => 'View Reservations',
        'description' => 'Allow users to view reservations',
        'section' => 'privacy'
    ];
    public const PRIVACY_HIDE_USER_DETAILS = [
        'key' => 'privacy.hide.user.details',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide User Details',
        'description' => 'Hide user details from other users',
        'section' => 'privacy'
    ];
    public const PRIVACY_HIDE_RESERVATION_DETAILS = [
        'key' => 'privacy.hide.reservation.details',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Reservation Details',
        'description' => 'Hide reservation details from other users',
        'section' => 'privacy'
    ];
    # previously PRIVACY_ALLOW_GUEST_BOOKING
    public const PRIVACY_ALLOW_GUEST_RESERVATIONS = [
        'key' => 'privacy.allow.guest.reservations',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow Guest Reservations',
        'description' => 'Allow guests to make reservations',
        'section' => 'privacy'
    ];
    public const PRIVACY_PUBLIC_FUTURE_DAYS = [
        'key' => 'privacy.public.future.days',
        'type' => 'integer',
        'default' => 30,
        'label' => 'Public Future Days',
        'description' => 'Number of future days visible to the public',
        'section' => 'privacy'
    ];

    // reCAPTCHA

    public const RECAPTCHA_ENABLED = [
        'key' => 'recaptcha.enabled',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable reCAPTCHA',
        'description' => 'Enable Google reCAPTCHA for forms',
        'section' => 'recaptcha'
    ];
    public const RECAPTCHA_PUBLIC_KEY = [
        'key' => 'recaptcha.public.key',
        'type' => 'string',
        'default' => '',
        'label' => 'reCAPTCHA Public Key',
        'description' => 'Public key for Google reCAPTCHA',
        'section' => 'recaptcha'
    ];
    public const RECAPTCHA_PRIVATE_KEY = [
        'key' => 'recaptcha.private.key',
        'type' => 'string',
        'default' => '',
        'label' => 'reCAPTCHA Private Key',
        'description' => 'Private key for Google reCAPTCHA',
        'section' => 'recaptcha',
        'is_private' => true
    ];
    public const RECAPTCHA_REQUEST_METHOD = [
        'key' => 'recaptcha.request.method',
        'type' => 'string',
        'default' => 'curl',
        'label' => 'reCAPTCHA Request Method',
        'description' => 'HTTP method to use for reCAPTCHA validation. Options: curl, post, socket',
        'section' => 'recaptcha'
    ];

    // Security Headers

    public const SECURITY_HEADERS = [
        'key' => 'security.headers',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Security Headers',
        'description' => 'Enable sending of security headers',
        'section' => 'security'
    ];
    public const SECURITY_STRICT_TRANSPORT = [
        'key' => 'security.strict-transport',
        'type' => 'string',
        'default' => 'max-age=31536000',
        'label' => 'Strict Transport Security',
        'description' => 'Enable HTTP Strict Transport Security (HSTS)',
        'section' => 'security'
    ];
    public const SECURITY_X_FRAME = [
        'key' => 'security.x-frame',
        'type' => 'string',
        'default' => 'deny',
        'label' => 'X-Frame-Options',
        'description' => 'Set the X-Frame-Options header value',
        'section' => 'security'
    ];
    public const SECURITY_X_XSS = [
        'key' => 'security.x-xss',
        'type' => 'string',
        'default' => '1; mode=block',
        'label' => 'X-XSS-Protection',
        'description' => 'Set the X-XSS-Protection header value',
        'section' => 'security'
    ];
    public const SECURITY_X_CONTENT_TYPE = [
        'key' => 'security.x-content-type',
        'type' => 'string',
        'default' => 'nosniff',
        'label' => 'X-Content-Type-Options',
        'description' => 'Set the X-Content-Type-Options header value',
        'section' => 'security'
    ];
    public const SECURITY_CONTENT_SECURITY_POLICY = [
        'key' => 'security.content-security-policy',
        'type' => 'string',
        'default' => '',
        'label' => 'Content Security Policy',
        'description' => 'Set the Content-Security-Policy header value',
        'section' => 'security'
    ];

    // Credit System Settings

    public const CREDITS_ENABLED = [
        'key' => 'credits.enabled',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Credits System',
        'description' => 'Enable or disable the credits system for reservations',
        'section' => 'credits'
    ];
    public const CREDITS_ALLOW_PURCHASE = [
        'key' => 'credits.allow.purchase',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow Credit Purchase',
        'description' => 'Allow users to purchase credits',
        'section' => 'credits'
    ];

    // Analytics Integration

    public const GOOGLE_ANALYTICS_TRACKING_ID = [
        'key' => 'google.analytics.tracking.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Google Analytics Tracking ID',
        'description' => 'Tracking ID for Google Analytics integration',
        'section' => '',
        'is_private' => true
    ];

    // Slack Integration
    public const SLACK_TOKEN = [
        'key' => 'slack.token',
        'type' => 'string',
        'default' => '',
        'label' => 'Slack Token',
        'description' => 'Token for Slack integration',
        'section' => '',
        'is_private' => true
    ];

    // Authentication Settings
    public const AUTHENTICATION_HIDE_LOGIN_PROMPT = [
        'key' => 'authentication.hide.login.prompt',
        'legacy' => 'authentication.hide.booked.login.prompt',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Hide Login Prompt',
        'description' => 'Hide the login prompt on the login page',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_CAPTCHA_ON_LOGIN = [
        'key' => 'authentication.captcha.on.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Captcha on Login',
        'description' => 'Enable captcha on the login form',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_REQUIRED_EMAIL_DOMAINS = [
        'key' => 'authentication.required.email.domains',
        'type' => 'string',
        'default' => '',
        'label' => 'Required Email Domains',
        'description' => 'Comma-separated list of allowed email domains for registration',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_GOOGLE_LOGIN_ENABLED = [
        'key' => 'authentication.google.login.enabled',
        'legacy' => 'authentication.allow.google.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Google Login',
        'description' => 'Allow users to log in with Google',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_GOOGLE_CLIENT_ID = [
        'key' => 'authentication.google.client.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Google Client ID',
        'description' => 'Client ID for Google OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_GOOGLE_CLIENT_SECRET = [
        'key' => 'authentication.google.client.secret',
        'type' => 'string',
        'default' => '',
        'label' => 'Google Client Secret',
        'description' => 'Client secret for Google OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_GOOGLE_REDIRECT_URI = [
        'key' => 'authentication.google.redirect.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'Google Redirect URI',
        'description' => 'Redirect URI for Google OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_MICROSOFT_LOGIN_ENABLED = [
        'key' => 'authentication.microsoft.login.enabled',
        'legacy' => 'authentication.allow.microsoft.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Microsoft Login',
        'description' => 'Allow users to log in with Microsoft',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_MICROSOFT_CLIENT_ID = [
        'key' => 'authentication.microsoft.client.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Microsoft Client ID',
        'description' => 'Client ID for Microsoft OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_MICROSOFT_TENANT_ID = [
        'key' => 'authentication.microsoft.tenant.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Microsoft Tenant ID',
        'description' => 'Tenant ID for Microsoft OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_MICROSOFT_CLIENT_SECRET = [
        'key' => 'authentication.microsoft.client.secret',
        'type' => 'string',
        'default' => '',
        'label' => 'Microsoft Client Secret',
        'description' => 'Client secret for Microsoft OAuth login',
        'section' => 'authentication',
        'is_private' => true
    ];
    public const AUTHENTICATION_MICROSOFT_REDIRECT_URI = [
        'key' => 'authentication.microsoft.redirect.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'Microsoft Redirect URI',
        'description' => 'Redirect URI for Microsoft OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_FACEBOOK_LOGIN_ENABLED = [
        'key' => 'authentication.facebook.login.enabled',
        'legacy' => 'authentication.allow.facebook.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Facebook Login',
        'description' => 'Allow users to log in with Facebook',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_FACEBOOK_CLIENT_ID = [
        'key' => 'authentication.facebook.client.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Facebook Client ID',
        'description' => 'Client ID for Facebook OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_FACEBOOK_CLIENT_SECRET = [
        'key' => 'authentication.facebook.client.secret',
        'type' => 'string',
        'default' => '',
        'label' => 'Facebook Client Secret',
        'description' => 'Client secret for Facebook OAuth login',
        'section' => 'authentication',
        'is_private' => true
    ];
    public const AUTHENTICATION_FACEBOOK_REDIRECT_URI = [
        'key' => 'authentication.facebook.redirect.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'Facebook Redirect URI',
        'description' => 'Redirect URI for Facebook OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_KEYCLOAK_LOGIN_ENABLED = [
        'key' => 'authentication.keycloak.login.enabled',
        'legacy' => 'authentication.allow.keycloak.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable Keycloak Login',
        'description' => 'Allow users to log in with Keycloak',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_KEYCLOAK_URL = [
        'key' => 'authentication.keycloak.url',
        'type' => 'string',
        'default' => '',
        'label' => 'Keycloak URL',
        'description' => 'URL for Keycloak server',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_KEYCLOAK_REALM = [
        'key' => 'authentication.keycloak.realm',
        'type' => 'string',
        'default' => '',
        'label' => 'Keycloak Realm',
        'description' => 'Realm for Keycloak authentication',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_KEYCLOAK_CLIENT_ID = [
        'key' => 'authentication.keycloak.client.id',
        'type' => 'string',
        'default' => '',
        'label' => 'Keycloak Client ID',
        'description' => 'Client ID for Keycloak OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_KEYCLOAK_CLIENT_SECRET = [
        'key' => 'authentication.keycloak.client.secret',
        'type' => 'string',
        'default' => '',
        'label' => 'Keycloak Client Secret',
        'description' => 'Client secret for Keycloak OAuth login',
        'section' => 'authentication',
        'is_private' => true
    ];
    public const AUTHENTICATION_KEYCLOAK_REDIRECT_URI = [
        'key' => 'authentication.keycloak.client.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'Keycloak Redirect URI',
        'description' => 'Redirect URI for Keycloak OAuth login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_LOGIN_ENABLED = [
        'key' => 'authentication.oauth2.login.enabled',
        'legacy' => 'authentication.allow.oauth2.login',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable OAuth2 Login',
        'description' => 'Allow users to log in with OAuth2',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_NAME = [
        'key' => 'authentication.oauth2.name',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Name',
        'description' => 'Display name for OAuth2 login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_URL_AUTHORIZE = [
        'key' => 'authentication.oauth2.url.authorize',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Authorize URL',
        'description' => 'Authorization URL for OAuth2 login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_URL_TOKEN = [
        'key' => 'authentication.oauth2.url.token',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Token URL',
        'description' => 'Token URL for OAuth2 login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_URL_USERINFO = [
        'key' => 'authentication.oauth2.url.userinfo',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Userinfo URL',
        'description' => 'Userinfo URL for OAuth2 login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_CLIENT_ID = [
        'key' => 'authentication.oauth2.client.id',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Client ID',
        'description' => 'Client ID for OAuth2 login',
        'section' => 'authentication'
    ];
    public const AUTHENTICATION_OAUTH2_CLIENT_SECRET = [
        'key' => 'authentication.oauth2.client.secret',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Client Secret',
        'description' => 'Client secret for OAuth2 login',
        'section' => 'authentication',
        'is_private' => true
    ];
    public const AUTHENTICATION_OAUTH2_REDIRECT_URI = [
        'key' => 'authentication.oauth2.client.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'OAuth2 Redirect URI',
        'description' => 'Redirect URI for OAuth2 login',
        'section' => 'authentication'
    ];

    // Plugin Configuration

    public const PLUGIN_AUTHENTICATION = [
        'key' => 'plugins.authentication',
        'legacy' => 'plugins.Authentication',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
            'ActiveDirectory' => 'ActiveDirectory',
            'Apache' => 'Apache',
            'CAS' => 'CAS',
            'Drupal' => 'Drupal',
            'Krb5' => 'Krb5',
            'Ldap' => 'Ldap',
            'Mellon' => 'Mellon',
            'Moodle' => 'Moodle',
            'MoodleAdv' => 'MoodleAdv',
            'Saml' => 'Saml',
            'Shibboleth' => 'Shibboleth',
            'WordPress' => 'WordPress'
        ],
        'label' => 'Authentication Plugin',
        'description' => 'Plugin used for authentication',
        'section' => 'plugins'
    ];
    public const PLUGIN_AUTHORIZATION = [
        'key' => 'plugins.authorization',
        'legacy' => 'plugin.Authorization',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
        ],
        'label' => 'Authorization Plugin',
        'description' => 'Plugin used for authorization',
        'section' => 'plugins'
    ];
    public const PLUGIN_EXPORT = [
        'key' => 'plugins.export',
        'legacy' => 'plugin.Export',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
        ],
        'label' => 'Export Plugin',
        'description' => 'Plugin used for exporting data',
        'section' => 'plugins'
    ];
    public const PLUGIN_PERMISSION = [
        'key' => 'plugins.permission',
        'legacy' => 'plugin.Permission',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
        ],
        'label' => 'Permission Plugin',
        'description' => 'Plugin used for permission management',
        'section' => 'plugins'
    ];
    public const PLUGIN_POSTREGISTRATION = [
        'key' => 'plugins.postregistration',
        'legacy' => 'plugin.PostRegistration',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
        ],
        'label' => 'Post-Registration Plugin',
        'description' => 'Plugin used after user registration',
        'section' => 'plugins'
    ];
    public const PLUGIN_PRERESERVATION = [
        'key' => 'plugins.prereservation',
        'legacy' => 'plugin.PreReservation',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
            'AdminCheckOnly' => 'AdminCheckOnly',
            'PreReservationExample' => 'PreReservationExample',
        ],
        'label' => 'Pre-Reservation Plugin',
        'description' => 'Plugin used before making a reservation',
        'section' => 'plugins'
    ];
    public const PLUGIN_POSTRESERVATION = [
        'key' => 'plugins.postreservation',
        'legacy' => 'plugin.PostReservation',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
            'PostReservation' => 'PostReservation',
        ],
        'label' => 'Post-Reservation Plugin',
        'description' => 'Plugin used after making a reservation',
        'section' => 'plugins'
    ];
    public const PLUGIN_STYLING = [
        'key' => 'plugins.styling',
        'legacy' => 'plugin.Styling',
        'type' => 'string',
        'default' => '',
        'choices' => [
            '' => 'None',
        ],
        'label' => 'Styling Plugin',
        'description' => 'Plugin used for custom styling',
        'section' => 'plugins'
    ];

    // API Configuration

    public const API_ENABLED = [
        'key' => 'api.enabled',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable API',
        'description' => 'Enable or disable the API',
        'section' => 'api'
    ];
    public const API_REGISTRATION_ALLOW_SELF = [
        'key' => 'api.registration.allow.self',
        'legacy' => 'api.allow.self.registration',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Allow API Self Registration',
        'description' => 'Allow users to self-register via the API',
        'section' => 'api'
    ];
    public const API_AUTHENTICATION_GROUP = [
        'key' => 'api.authentication.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Authentication Group',
        'description' => 'Group required for API authentication',
        'section' => 'api'
    ];
    public const API_ACCESSORIES_RO_GROUP = [
        'key' => 'api.accessories.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Accessories Read-Only Group',
        'description' => 'Group with read-only access to accessories via API',
        'section' => 'api'
    ];
    public const API_ACCOUNTS_RO_GROUP = [
        'key' => 'api.accounts.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Accounts Read-Only Group',
        'description' => 'Group with read-only access to accounts via API',
        'section' => 'api'
    ];
    public const API_ACCOUNTS_RW_GROUP = [
        'key' => 'api.accounts.rw.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Accounts Read-Write Group',
        'description' => 'Group with read-write access to accounts via API',
        'section' => 'api'
    ];
    public const API_ATTRIBUTES_RO_GROUP = [
        'key' => 'api.attributes.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Attributes Read-Only Group',
        'description' => 'Group with read-only access to attributes via API',
        'section' => 'api'
    ];
    public const API_GROUPS_RO_GROUP = [
        'key' => 'api.groups.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Groups Read-Only Group',
        'description' => 'Group with read-only access to groups via API',
        'section' => 'api'
    ];
    public const API_RESERVATIONS_RO_GROUP = [
        'key' => 'api.reservations.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Reservations Read-Only Group',
        'description' => 'Group with read-only access to reservations via API',
        'section' => 'api'
    ];
    public const API_RESERVATIONS_RW_GROUP = [
        'key' => 'api.reservations.rw.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Reservations Read-Write Group',
        'description' => 'Group with read-write access to reservations via API',
        'section' => 'api'
    ];
    public const API_RESOURCES_RO_GROUP = [
        'key' => 'api.resources.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Resources Read-Only Group',
        'description' => 'Group with read-only access to resources via API',
        'section' => 'api'
    ];
    public const API_SCHEDULES_RO_GROUP = [
        'key' => 'api.schedules.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Schedules Read-Only Group',
        'description' => 'Group with read-only access to schedules via API',
        'section' => 'api'
    ];
    public const API_USERS_RO_GROUP = [
        'key' => 'api.users.ro.group',
        'type' => 'string',
        'default' => '',
        'label' => 'API Users Read-Only Group',
        'description' => 'Group with read-only access to users via API',
        'section' => 'api'
    ];

    public static function all(): array
    {
        $constants = (new \ReflectionClass(static::class))->getConstants();

        $all = [];
        foreach ($constants as $name => $value) {
            if (is_array($value) && isset($value['key'])) {
                $all[] = $value;
            }
        }

        return $all;
    }

    public static function mapByKey(): array
    {
        $map = [];
        foreach (self::all() as $entry) {
            $map[$entry['key']] = $entry;
        }

        return $map;
    }

    public static function findByKey(string $key): ?array
    {
        foreach (self::all() as $config) {
            if (($config['key'] ?? null) === $key) {
                return $config;
            }
        }

        return null;
    }

    public static function findByLegacyKey(string $legacyKey): ?array
    {
        foreach (self::all() as $config) {
            if (($config['legacy'] ?? null) === $legacyKey) {
                return $config;
            }
        }

        return null;
    }

    public static function isPrivate($configDef): bool
    {
        if (empty($configDef)) {
            return false;
        }
        return $configDef['is_private'] ?? false;
    }

    public static function hasEnv($configDef): bool
    {
        $key = $configDef['key'] ?? null;
        if (!is_string($key) || $key === '') {
            return false;
        }

        $loadedEnvVars = getenv();
        $envKey = strtoupper('LB_' . preg_replace('/[.\-]+/', '_', (string)$key));
        return array_key_exists($envKey, $loadedEnvVars) ?? false;
    }
}

class ConfigSettingType
{
    public const String = 'string';
    public const Boolean = 'boolean';
    public const Integer = 'integer';
}
