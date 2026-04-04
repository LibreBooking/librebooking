<?php

/**
 * LibreBooking configuration file
 *
 * This file contains the default configuration for LibreBooking.
 * It is used to set up the application and can be overridden by environment variables.
 * The settings are grouped into sections for easier management.
 */

return [
    'settings' => [

        ###########################
        # Application configuration
        ###########################

        # The public name of the application
        'app.title' => 'LibreBooking',

        # Enable or disable debug mode for the application
        # if enabled it will enable 'display_errors' and 'display_startup_errors' (true/false)
        'app.debug' => false,

        # Administrator email address
        'admin.email' => 'admin@example.com',

        # Display name used for outgoing admin emails
        'admin.email.name' => 'LB Administrator',

        # Company name to show in the page header
        'company.name' => '',

        # URL to the company's website
        'company.url' => '',

        #######################
        # Language and Timezone
        #######################

        # Default timezone.
        # Options: Look up here https://php.net/manual/en/timezones.php
        'default.timezone' => 'Europe/London',

        # Default language.
        # Options: Find your language in the lang directory
        'default.language' => 'en_us',

        # Restrict which languages appear in the language selector.
        # Comma-separated list of language codes (e.g. 'en_us,fr_fr,de_de').
        # Languages appear in the selector in the order listed.
        # Leave empty to show all supported languages.
        # Language codes must match those defined in lang/AvailableLanguages.php.
        # If the value of `default.language` is not included in this list, the
        # application will fall back to 'en_us' and log an error.
        'enabled.languages' => '',

        ##########
        # Frontend
        ##########

        # Public URL to the Web directory of this instance
        # This is the URL that appears when you are logging in
        # Leave http: or https: off to auto-detect
        'script.url' => '',

        # Password to access installation wizard under Web/install/
        # Leave empty to disable the installation wizard
        'install.password' => '',

        # Enable template caching. Recommended for production. (true/false)
        'cache.templates' => true,

        # Enable use of bundled/self-hosted frontend assets (JavaScript, CSS, fonts) (true/false)
        'use.local.js.libs' => true,

        # Session inactivity timeout in minutes
        'inactivity.timeout' => 30,

        # Home URL linked to application logo in header
        'home.url' => '',

        # URL to redirect users after logout
        'logout.url' => '',

        # Default homepage to use when new users register
        # Options:  1 = Dashboard, 2 = Schedule, 3 = My Calendar, 4 = Resource Calendar
        'default.homepage' => 1,

        # Default number of items per page in listings
        # Use a positive integer. -1 is not supported for performance reasons
        'default.page.size' => 50,

        # Optional path to a custom CSS file
        'css.extension.file' => '',

        # Name of the CSS theme to use
        # Options: default, dimgray, dark_red, dark_green, french_blue, cake_blue, orange
        'css.theme' => 'default',

        # Display format when showing user names
        'name.format' => '{first} {last}',

        #######################
        # Analytics Integration
        #######################

        # Google Analytics tracking ID (e.g., UA-XXXXXXX or G-XXXXXXXX)
        'google.analytics.tracking.id' => '',

        ###################
        # Slack Integration
        ###################

        # Slack webhook token for sending notifications
        'slack.token' => '',


        #######
        # Pages
        #######

        'pages' => [
            # Enable or disable the configuration page in the admin panel (true/false)
            'configuration.enabled' => true,
        ],

        ##########
        # Database
        ##########

        'database' => [
            # Database configuration. Only MySQL is supported
            'type' => 'mysql',

            # Database host address or IP.
            'hostspec' => '127.0.0.1',

            # Database name
            'name' => 'librebooking',

            # Database username with access to the librebooking database
            'user' => 'lb_user',

            # Database password for the user
            'password' => 'password',
        ],

        ###########################
        # Email Sending (PHPMailer)
        ###########################

        'phpmailer' => [
            # Mailer type:
            # Options: mail, smtp or sendmail
            'mailer' => 'smtp',

            # SMTP host address or IP
            'smtp.host' => '',

            # SMTP port
            'smtp.port' => 25,

            # SMTP encryption
            # Options: tls, ssl
            'smtp.secure' => '',

            # SMTP Auto TLS, if an unencrypted SMTP connection should attempt to use STARTTLS (true/false)
            'smtp.autotls' => true,

            # Enable SMTP authentication (true/false)
            'smtp.auth' => true,

            # SMTP username
            'smtp.username' => '',

            # SMTP password
            'smtp.password' => '',

            # Path to sendmail binary
            'sendmail.path' => '/usr/sbin/sendmail',

            # Enable SMTP debug output (true/false)
            'smtp.debug' => false,
        ],

        #######
        # Email
        #######

        'email' => [
            # Enable or disable email notifications (true/false)
            'enabled' => true,

            # Enable custom email templates (true/false)
            'enforce.custom.template' => false,

            # Default email address to use for outgoing emails
            'default.from.address' => '',

            # Default name to use for outgoing emails
            'default.from.name' => '',
        ],

        #########
        # Logging
        #########

        'logging' => [
            # Directory where logs are stored
            # Write permission is required
            'folder' => '/var/log/librebooking/log',

            # Logging level: none, debug, error
            # Options: none, error, debug
            'level' => 'error',

            # Enable SQL logging (true/false)
            'sql' => false,
        ],

        #########
        # Uploads
        #########

        'uploads' => [
            # Full or relative path to where images will be stored
            'image.upload.directory' => 'Web/uploads/images',

            # full or relative path to show uploaded images from
            'image.upload.url' => 'uploads/images',

            # Enable reservation attachments (true/false)
            'reservation.attachments.enabled' => false,

            # Full or relative path to where reservation attachments will be stored
            'reservation.attachment.path' => 'uploads/reservation',

            # File extensions allowed for reservation attachments
            'reservation.attachment.extensions' => 'csv,doc,docx,gif,jpeg,jpg,pdf,png,ppt,pptx,txt,xls,xlsx',
        ],

        ########################################
        # Notification Settings for Reservations
        ########################################

        'reservation.notify' => [
            # Send notification to application administrators when a reservation is
            # created (true/false)
            'application.admin.add' => false,

            # Send notification to application administrators when a reservation is
            # updated (true/false)
            'application.admin.update' => false,

            # Send notification to application administrators when a reservation is
            # deleted (true/false)
            'application.admin.delete' => false,

            # Send notification to application administrators when a reservation requires
            # approval (true/false)
            'application.admin.approval' => false,

            # Send notification to group administrators when a reservation is created (true/false)
            'group.admin.add' => false,

            # Send notification to group administrators when a reservation is updated (true/false)
            'group.admin.update' => false,

            # Send notification to group administrators when a reservation is deleted (true/false)
            'group.admin.delete' => false,

            # Send notification to group administrators when a reservation requires
            # approval (true/false)
            'group.admin.approval' => false,

            # Send notification to resource administrators when a reservation is created (true/false)
            'resource.admin.add' => false,

            # Send notification to resource administrators when a reservation is updated (true/false)
            'resource.admin.update' => false,

            # Send notification to resource administrators when a reservation is deleted (true/false)
            'resource.admin.delete' => false,

            # Send notification to resource administrators when a reservation requires
            # approval (true/false)
            'resource.admin.approval' => false,
        ],

        ###############################
        # Schedule Display and Behavior
        ###############################

        'schedule' => [
            # Automatically scroll to today's date on load (true/false)
            'auto.scroll.today' => true,

            # Show week numbers in the calendar view (true/false)
            'show.week.numbers' => false,

            # Hide periods that are blocked or unavailable (true/false)
            'hide.blocked.periods' => false,

            # Display resources the user cannot access (true/false)
            'show.inaccessible.resources' => true,

            # Format string for reservation labels (use placeholders like {name}, {title})
            # Available properties are: {name}, {title}, {description}, {email}, {phone}, {organization}, {position}, {startdate}, {enddate} {resourcename} {participants} {invitees} {reservationAttributes}.
            # Custom attributes can be added using att with the attribute id. For example {att1}
            'reservation.label' => '{name}',

            # Use different colors for each user's reservations (true/false)
            'use.per.user.colors' => false,

            # Number of minutes to highlight updated reservations
            'update.highlight.minutes' => 0,

            # Enable faster loading for reservation data (may reduce detail) (true/false)
            'fast.reservation.load' => false,

            # Load simplified mobile views on small devices (true/false)
            'load.mobile.views' => true,
        ],

        ##############
        # Reservations
        ##############

        'reservation' => [
            # Disable reservation participation/invitations and hide participant/invitee
            # lists in the reservation UI (true/false)
            'prevent.participation' => false,

            # Disable recurring reservations (true/false)
            'prevent.recurrence' => false,

            # Allow non-registered users (guests) to participate in reservations (true/false)
            'allow.guest.participation' => false,

            # Enable a waitlist for fully booked reservations (true/false)
            'allow.wait.list' => false,

            # Restrict start times (e.g., 'future', 'none', 'current')
            # Note: In the standard reservation create/update flow, exemptions from this constraint apply only in specific cases:
            #   - Application admins are always exempt.
            #   - Group admins are exempt only when acting as admin for the reservation user.
            #   - Resource and schedule admins are exempt only when they administer all resources in the reservation.
            # Options: none, future, current
            'start.time.constraint' => 'future',

            # Require approval when an existing reservation is updated (true/false)
            'updates.require.approval' => false,

            # Require a title for all reservations (true/false)
            'title.required' => false,

            # Require a description for all reservations (true/false)
            'description.required' => false,

            # Number of minutes before start when check-in is allowed
            'checkin.minutes.prior' => 5,

            # Restrict check-in functionality to administrators only (true/false)
            'checkin.admin.only' => false,

            # Restrict check-out functionality to administrators only (true/false)
            'checkout.admin.only' => false,

            # Enable reminder notifications for upcoming reservations (true/false)
            'reminders.enabled' => false,

            # Default reminder time before reservation start (e.g., '15 minutes', '1 hours', '1 days')
            'default.start.reminder' => '',

            # Default reminder time before reservation end (e.g., '15 minutes', '1 hours', '1 days')
            'default.end.reminder' => '',
        ],

        #############################
        # Reservation Label Templates
        #############################

        'reservation.labels' => [
            # ICS calendar summary text for all reservations
            # Available properties are: {name}, {title}, {description}, {email}, {phone}, {organization}, {position}, {startdate}, {enddate} {resourcename} {participants} {invitees} {reservationAttributes}.
            # Custom attributes can be added using att with the attribute id. For example {att1}
            'ics.summary' => '{title}',

            # ICS calendar summary text for a user's reservations
            'ics.my.summary' => '{title}',

            # RSS feed description template for reservations (HTML allowed)
            'rss.description' => '<div><span>Start</span> {startdate}</div><div><span>End</span> {enddate}</div><div><span>Organizer</span> {name}</div><div><span>Description</span> {description}</div>',

            # Label template used in the 'My Calendar' view
            'my.calendar' => '{resourcename} {title}',

            # Label template for resource-specific calendars
            'resource.calendar' => '{name}',

            # Label used in display in reservation popups
            'reservation.popup' => '',
        ],

        ############################
        # Reporting and Registration
        ############################

        'reports' => [
            # Allow all users to access reports (true/false)
            'allow.all.users' => false,
        ],

        ##############
        # Registration
        ##############

        'registration' => [
            # Enable self-registration for new users (true/false)
            'allow.self.registration' => true,

            # Require phone number during registration (true/false)
            'require.phone' => false,

            # Require position/title during registration (true/false)
            'require.position' => false,

            # Require organization name during registration (true/false)
            'require.organization' => false,

            # Hide phone field from the registration form (true/false)
            'hide.phone' => false,

            # Hide position/title field from the registration form (true/false)
            'hide.position' => false,

            # Hide organization field from the registration form (true/false)
            'hide.organization' => false,

            # Enable CAPTCHA during user registration (true/false)
            'captcha.enabled' => true,

            # Require users to activate their account via email (true/false)
            'require.email.activation' => false,

            # Automatically subscribe new users to email notifications (true/false)
            'auto.subscribe.email' => false,

            # Notify the admin when a new user registers (true/false)
            'notify.admin' => false,
        ],

        ##################
        # Resource Options
        ##################

        'resource' => [
            # Indicates if the contact must be a registered user (true/false)
            'contact.is.user' => false,
        ],

        #####################
        # Tablet View Options
        #####################

        'tablet.view' => [
            # Allow users to make reservations in the tablet view (true/false)
            'allow.reservations' => true,

            # Allow guests to make reservations from the tablet view (true/false)
            'allow.guest.reservations' => false,

            # Suggest known email addresses during reservation creation (true/false)
            'auto.suggest.emails' => false,
        ],

        ##############
        # ICS Settings
        ##############

        'ics' => [
            # Subscription key secret used for ICS calendar feeds
            'subscription.key' => '',

            # Number of future days to include in ICS feeds
            'future.days' => 30,

            # Number of past days to include in ICS feeds
            'past.days' => 0,
        ],

        #############################
        # Data Retention and Deletion
        #############################

        'cleanup' => [
            # Requires  'deleteolddata.php' to run as a cron job
            # Number of years after which old data is considered for deletion
            'years.old.data' => 3,

            # Automatically delete old announcements (true/false)
            'delete.old.announcements' => false,

            # Automatically delete old blackout periods (true/false)
            'delete.old.blackouts' => false,

            # Automatically delete old reservations (true/false)
            'delete.old.reservations' => false,
        ],

        #################
        # Password Policy
        #################

        'password' => [
            # Disable the 'Forgot Password' feature (true/false)
            'disable.reset' => false,

            # Minimum number of letters required in passwords
            'minimum.letters' => 6,

            # Minimum number of numeric digits required in passwords
            'minimum.numbers' => 0,

            # Require both upper and lower case characters in passwords (true/false)
            'upper.and.lower' => false,
        ],

        #########
        # Privacy
        #########

        'privacy' => [
            # Allow unauthenticated users to view schedules (true/false)
            'view.schedules' => true,

            # Allow users to view reservation details (true/false)
            'view.reservations' => false,

            # Hide user details from general users (true/false)
            'hide.user.details' => false,

            # Hide reservation details from general users (true/false)
            'hide.reservation.details' => false,

            # Allow guest users to make reservations (true/false)
            'allow.guest.reservations' => false,

            # Set number of days in the future for which reservations can be made by guest users
            'public.future.days' => 30,
        ],

        ###########
        # reCAPTCHA
        ###########

        'recaptcha' => [
            # Enable Google reCAPTCHA on login or registration (true/false)
            'enabled' => false,

            # Google reCAPTCHA public site key
            'public.key' => '',

            # Google reCAPTCHA secret key
            'private.key' => '',

            # HTTP request method used for verification
            # Options: curl, post, socket
            'request.method' => 'curl',
        ],

        ##################
        # Security Headers
        ##################

        'security' => [
            # Enable the following security headers in HTTP responses (true/false)
            'headers' => false,

            # HTTP Strict Transport Security (HSTS) header value
            'strict-transport' => 'max-age=31536000',

            # X-Frame-Options header value (e.g., deny, sameorigin)
            'x-frame' => 'deny',

            # X-XSS-Protection header value
            'x-xss' => '1; mode=block',

            # X-Content-Type-Options header value
            'x-content-type' => 'nosniff',

            # Set the Content-Security-Policy header value
            'content-security-policy' => '',
        ],

        ########################
        # Credit System Settings
        ########################

        'credits' => [
            # Enable credit-based reservation system (true/false)
            'enabled' => false,

            # Allow users to purchase credits (true/false)
            'allow.purchase' => false,
        ],

        #########################
        # Authentication Settings
        #########################

        'authentication' => [
            # Hide the login prompt (true/false)
            'hide.login.prompt' => false,

            # Enable CAPTCHA on login page (true/false)
            'captcha.on.login' => false,

            # Restrict registration to specific email domains (comma-separated, e.g., example.com,school.edu)
            'required.email.domains' => '',

            # Enable login via Google (true/false)
            'google.login.enabled' => false,

            # Google OAuth2 client credentials
            'google.client.id' => '',

            # Client secret for Google OAuth login
            'google.client.secret' => '',

            # Path to the Google redirect URI
            # /Web/google-auth.php
            'google.redirect.uri' => '/Web/google-auth.php',

            # Enable login via Microsoft (true/false)
            'microsoft.login.enabled' => false,

            # Microsoft OAuth2 client credentials
            'microsoft.client.id' => '',

            # Replace with your tenant id if the app is single tenant
            'microsoft.tenant.id' => 'common',

            # Microsoft OAuth2 client secret
            'microsoft.client.secret' => '',

            # Path to the Microsoft redirect URI
            # /Web/microsoft-auth.php
            'microsoft.redirect.uri' => '/Web/microsoft-auth.php',

            # Enable login via Facebook (true/false)
            'facebook.login.enabled' => false,

            # Facebook App credentials
            'facebook.client.id' => '',

            # Facebook App client secret
            'facebook.client.secret' => '',

            # Facebook OAuth2 redirect URI
            # /Web/facebook-auth.php
            'facebook.redirect.uri' => '/Web/facebook-auth.php',

            # Enable login via Keycloak (true/false)
            'keycloak.login.enabled' => false,

            # Keycloak OAuth2 credentials
            'keycloak.url' => '',

            # Realm for Keycloak authentication
            'keycloak.realm' => '',

            # Client ID for Keycloak OAuth login
            'keycloak.client.id' => '',

            # Client secret for Keycloak OAuth login
            'keycloak.client.secret' => '',

            # Redirect URI for Keycloak OAuth login
            'keycloak.client.uri' => '/Web/keycloak-auth.php',

            # Enable login via custom OAuth2 provider (true/false)
            'oauth2.login.enabled' => false,

            # OAuth2 identity provider name (shown on login button)
            'oauth2.name' => 'OAuth2',

            # OAuth2 endpoint URLs and client credentials
            # If true, the configured authorize URL's trailing slash is removed (true/false)
            'oauth2.strip.trailing.slash' => true,

            # Authorization URL for OAuth2 login
            'oauth2.url.authorize' => '',

            # Token URL for OAuth2 login
            'oauth2.url.token' => '',

            # Userinfo URL for OAuth2 login
            'oauth2.url.userinfo' => '',

            # Client ID for OAuth2 login
            'oauth2.client.id' => '',

            # Client secret for OAuth2 login
            'oauth2.client.secret' => '',

            # Redirect URI for OAuth2 login
            'oauth2.client.uri' => '/Web/oauth2-auth.php',
        ],

        ######################
        # Plugin Configuration
        ######################

        'plugins' => [
            # Comma-separated list of plugin class names to use for authentication
            # Options: ActiveDirectory, Apache, CAS, Drupal, Krb5, Ldap, Mellon, Moodle, MoodleAdv, Saml, Shibboleth, WordPress
            'authentication' => '',

            # Comma-separated list of plugin class names to use for authorization
            'authorization' => '',

            # Comma-separated list of plugin class names to handle data export
            'export' => '',

            # Comma-separated list of plugin class names for permission management
            'permission' => '',

            # Comma-separated list of plugin class names to run after user registration
            'postregistration' => '',

            # Comma-separated list of plugin class names to run before reservation creation
            # Options: AdminCheckOnly, PreReservationExample
            'prereservation' => '',

            # Comma-separated list of plugin class names to run after reservation is created/updated
            # Options: PostReservation
            'postreservation' => '',

            # Comma-separated list of plugin class names to apply custom styling logic
            'styling' => '',
        ],

        ###################
        # API Configuration
        ###################

        'api' => [
            # Enable or disable the API (true/false)
            'enabled' => false,

            # Allow users to self-register via the API (true/false)
            'registration.allow.self' => false,

            # If set, a user must belong to this group to authenticate via the API
            # Admin users are exempt
            'authentication.group' => '',

            # Restrict read-only access to Accessories via API to this group
            # NOTE: There are no write APIs for Accessories
            'accessories.ro.group' => '',

            # Restrict read-only access to Accounts via API to this group
            'accounts.ro.group' => '',

            # Restrict read-write access to Accounts via API to this group
            'accounts.rw.group' => '',

            # Restrict read-only access to Attributes via API to this group
            # NOTE: Only application administrators can modify Attributes
            'attributes.ro.group' => '',

            # Restrict read-only access to Groups via API to this group
            # NOTE: Only application administrators can modify Groups
            'groups.ro.group' => '',

            # Restrict read-only access to Reservations via API to this group
            'reservations.ro.group' => '',

            # Restrict read-write access to Reservations via API to this group
            'reservations.rw.group' => '',

            # Restrict read-only access to Resources via API to this group
            # NOTE: Only application administrators can modify Resources
            'resources.ro.group' => '',

            # Restrict read-only access to Schedules via API to this group
            # NOTE: There are no write APIs for Schedules
            'schedules.ro.group' => '',

            # Restrict read-only access to Users via API to this group
            # NOTE: Only application administrators can modify Users
            'users.ro.group' => '',
        ],
    ],
];
