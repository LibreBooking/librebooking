Advanced Configuration
======================

This guide covers all advanced configuration options available in LibreBooking. For basic setup, see :doc:`BASIC-CONFIGURATION` first.

All settings are configured in the ``/config/config.php`` file. The configuration uses a mix of flat dot notation and nested arrays.

Environment Variable Override
-----------------------------

LibreBooking supports overriding any configuration setting using environment variables. This provides flexibility for deployment scenarios and keeps sensitive data separate from configuration files.

**Naming Convention**
  Environment variables follow the pattern: ``LB_`` + config key with special characters converted:

  - Dots (``.``) and dashes (``-``) become underscores (``_``)
  - All letters converted to uppercase

**Examples**

  .. code-block:: bash

     # Configuration key → Environment variable
     app.title → LB_APP_TITLE
     database.hostspec → LB_DATABASE_HOSTSPEC
     phpmailer.smtp.host → LB_PHPMAILER_SMTP_HOST
     reservation.prevent.participation → LB_RESERVATION_PREVENT_PARTICIPATION

**Using .env Files**
  Create a ``.env`` file in your application root directory:

  .. code-block:: bash

     LB_APP_TITLE='My Company Booking'
     LB_DATABASE_HOSTSPEC='db.example.com'
     LB_ADMIN_EMAIL='admin@mycompany.com'

  LibreBooking will automatically load and use these values.

**Docker Integration**
  Environment variables work seamlessly with Docker:

  .. code-block:: yaml

     # docker-compose.yml
     services:
       librebooking:
         environment:
           - LB_APP_TITLE=Company Booking System
           - LB_DATABASE_HOSTSPEC=mysql
           - LB_ADMIN_EMAIL=admin@company.com

**Complete Example**
  See ``develop/app/.env.example`` for a comprehensive list of all available environment variables with their default values and descriptions.

Application Advanced Settings
-----------------------------

**app.debug**
  Enable or disable debug mode for the application.

  .. code-block:: php

     'app.debug' => false,

**admin.email.name**
  Display name used for outgoing admin emails.

  .. code-block:: php

     'admin.email.name' => 'LB Administrator',

Frontend Advanced Settings
--------------------------

**inactivity.timeout**
  Time in minutes before a user is logged out due to inactivity.

  .. code-block:: php

     'inactivity.timeout' => 30,

**use.local.js.libs**
  Use local JavaScript libraries instead of CDN.

  .. code-block:: php

     'use.local.js.libs' => false,

**home.url**
  URL to redirect users after login.

  .. code-block:: php

     'home.url' => '',

**logout.url**
  URL to redirect users after logout.

  .. code-block:: php

     'logout.url' => '',

**css.extension.file**
  Path to a custom CSS file to extend the default styles.

  .. code-block:: php

     'css.extension.file' => '',

**name.format**
  Format for displaying user names.

  .. code-block:: php

     'name.format' => '{first} {last}',

Page Control
~~~~~~~~~~~~

.. code-block:: php

   'pages' => [
       'configuration.enabled' => true,
   ],

**pages.configuration.enabled**
  Enable or disable the configuration page in the admin panel.

**default.page.size**
  Default number of items per page in listings.

  .. code-block:: php

     'default.page.size' => 50,

Advanced Email Configuration
----------------------------

**email.enforce.custom.template**
  Force the use of a custom email template for all emails.

  .. code-block:: php

     'email' => [
         'enforce.custom.template' => false,
     ],

Advanced PHPMailer Settings
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

   'phpmailer' => [
       'sendmail.path' => '/usr/sbin/sendmail',
       'smtp.debug' => false,
   ],

**phpmailer.sendmail.path**
  Path to the sendmail binary.

**phpmailer.smtp.debug**
  Enable SMTP debug output (true/false).

Logging Configuration
---------------------

.. code-block:: php

   'logging' => [
       'folder' => '/var/log/librebooking/log',
       'level' => 'none',
       'sql' => false,
   ],

**logging.folder**
  Directory where log files are stored.

**logging.level**
  Logging level: none, DEBUG, INFO, WARNING, ERROR.

**logging.sql**
  Enable or disable logging of SQL queries.

File Upload Settings
--------------------

.. code-block:: php

   'uploads' => [
       'image.upload.directory' => 'Web/uploads/images',
       'image.upload.url' => 'Web/uploads/attachments',
       'reservation.attachments.enabled' => false,
       'reservation.attachment.path' => 'Web/uploads/attachments',
       'reservation.attachment.extensions' => 'pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif',
   ],

**uploads.image.upload.directory**
  Directory for uploaded images.

**uploads.image.upload.url**
  URL path for uploaded images.

**uploads.reservation.attachments.enabled**
  Allow users to attach files to reservations.

**uploads.reservation.attachment.path**
  Directory for reservation attachments.

**uploads.reservation.attachment.extensions**
  Comma-separated list of allowed file extensions for attachments.

Notification Settings
---------------------

Configure email notifications for different events:

.. code-block:: php

   'reservation.notify' => [
       'application.admin.add' => false,
       'application.admin.update' => false,
       'application.admin.delete' => false,
       'application.admin.approval' => false,
       'group.admin.add' => false,
       'group.admin.update' => false,
       'group.admin.delete' => false,
       'group.admin.approval' => false,
       'resource.admin.add' => false,
       'resource.admin.update' => false,
       'resource.admin.delete' => false,
       'resource.admin.approval' => false,
   ],

Schedule Display Settings
-------------------------

.. code-block:: php

   'schedule' => [
       'auto.scroll.today' => true,
       'show.week.numbers' => false,
       'hide.blocked.periods' => false,
       'show.inaccessible.resources' => true,
       'reservation.label' => '{name}',
       'use.per.user.colors' => false,
       'update.highlight.minutes' => 0,
       'fast.reservation.load' => false,
       'load.mobile.views' => true,
   ],

**schedule.auto.scroll.today**
  Automatically scroll to current date when loading schedules.

**schedule.show.week.numbers**
  Display week numbers in calendar views.

**schedule.hide.blocked.periods**
  Hide time periods that are blocked from booking.

**schedule.show.inaccessible.resources**
  Show resources that users cannot book (grayed out).

**schedule.reservation.label**
  Template for reservation labels. Available tokens: {name}, {title}, {description}, {email}, {phone}, {organization}, {position}, {startdate}, {enddate}, {resourcename}, {participants}, {invitees}, {reservationAttributes}, and custom attributes like {att1}.

**schedule.use.per.user.colors**
  Use different colors for each user's reservations.

**schedule.update.highlight.minutes**
  Minutes to highlight recently updated reservations (0 = disabled).

**schedule.fast.reservation.load**
  Enable faster loading with reduced detail.

**schedule.load.mobile.views**
  Use simplified views on mobile devices.

Reservation Behavior
--------------------

.. code-block:: php

   'reservation' => [
       'prevent.participation' => false,
       'prevent.recurrence' => false,
       'allow.guest.participation' => false,
       'allow.wait.list' => false,
       'start.time.constraint' => 'future',
       'updates.require.approval' => false,
       'title.required' => false,
       'description.required' => false,
       'checkin.minutes.prior' => 5,
       'checkin.admin.only' => false,
       'checkout.admin.only' => false,
       'reminders.enabled' => false,
       'default.start.reminder' => '',
       'default.end.reminder' => '',
   ],

**reservation.prevent.participation**
  Disable the ability to add participants to reservations.

**reservation.prevent.recurrence**
  Disable recurring/repeating reservations.

**reservation.allow.guest.participation**
  Allow non-registered users to be added as participants.

**reservation.allow.wait.list**
  Enable waitlist when resources are fully booked.

**reservation.start.time.constraint**
  When reservations can be made: 'future', 'any', 'same_day'.

**reservation.updates.require.approval**
  Require approval when editing existing approved reservations.

**reservation.title.required**
  Force users to enter a title/subject for reservations.

**reservation.description.required**
  Force users to enter a description for reservations.

**reservation.checkin.minutes.prior**
  How many minutes before start time check-in is allowed.

**reservation.checkin.admin.only**
  Restrict check-in to administrators only.

**reservation.checkout.admin.only**
  Restrict check-out to administrators only.

**reservation.reminders.enabled**
  Enable email reminders before reservations start/end.

**reservation.default.start.reminder**
  Default reminder time before start (e.g., '15 minutes', '1 hour').

**reservation.default.end.reminder**
  Default reminder time before end.

Reservation Label Templates
---------------------------

.. code-block:: php

   'reservation.labels' => [
       'ics.summary' => '{title}',
       'ics.my.summary' => '{title}',
       'rss.description' => '<div><span>Start</span> {startdate}</div><div><span>End</span> {enddate}</div><div><span>Organizer</span> {name}</div><div><span>Description</span> {description}</div>',
       'my.calendar' => '{resourcename} {title}',
       'resource.calendar' => '{name}',
       'reservation.popup' => '',
   ],

These templates control how reservations appear in different contexts using the same tokens as schedule.reservation.label.

Reports Settings
----------------

.. code-block:: php

   'reports' => [
       'allow.all.users' => false,
   ],

**reports.allow.all.users**
  Allow all users to access reports (not just admins).

Advanced Registration Settings
------------------------------

.. code-block:: php

   'registration' => [
       'auto.subscribe.email' => false,
       'require.phone' => false,
       'require.position' => false,
       'require.organization' => false,
       'hide.phone' => false,
       'hide.position' => false,
       'hide.organization' => false,
   ],

**registration.auto.subscribe.email**
  Automatically subscribe new users to email notifications.

**registration.require.phone**
  Require phone number during registration.

**registration.require.position**
  Require position during registration.

**registration.require.organization**
  Require organization during registration.

**registration.hide.phone**
  Hide phone field during registration.

**registration.hide.position**
  Hide position field during registration.

**registration.hide.organization**
  Hide organization field during registration.

Resource Settings
-----------------

.. code-block:: php

   'resource' => [
       'contact.is.user' => false,
   ],

**resource.contact.is.user**
  Indicates if the contact must be a registered user.

Tablet View Settings
--------------------

.. code-block:: php

   'tablet.view' => [
       'allow.guest.reservations' => false,
       'auto.suggest.emails' => false,
   ],

**tablet.view.allow.guest.reservations**
  Allow guests to make reservations from the tablet view.

**tablet.view.auto.suggest.emails**
  Auto-suggest email addresses during reservation creation.

ICS Calendar Settings
---------------------

.. code-block:: php

   'ics' => [
       'subscription.key' => '',
       'future.days' => 30,
       'past.days' => 0,
   ],

**ics.subscription.key**
  Secret key for calendar feed URLs (prevents unauthorized access).

**ics.future.days**
  Number of future days to include in calendar feeds.

**ics.past.days**
  Number of past days to include in calendar feeds.

Data Cleanup Settings
---------------------

.. code-block:: php

   'cleanup' => [
       'years.old.data' => 3,
       'delete.old.announcements' => false,
       'delete.old.blackouts' => false,
       'delete.old.reservations' => false,
   ],

**cleanup.years.old.data**
  Age in years when data is considered "old" for cleanup.

**cleanup.delete.old.announcements**
  Automatically delete old announcements.

**cleanup.delete.old.blackouts**
  Automatically delete old blackout periods.

**cleanup.delete.old.reservations**
  Automatically delete old reservation records.

Note: Cleanup requires setting up a cron job to run ``deleteolddata.php``.

Advanced Privacy Settings
-------------------------

.. code-block:: php

   'privacy' => [
       'view.schedules' => true,
       'hide.user.details' => false,
       'hide.reservation.details' => false,
       'public.future.days' => 30,
   ],

**privacy.view.schedules**
  Hide schedule and calendar from unauthenticated users.

**privacy.hide.user.details**
  Hide user details from other users.

**privacy.hide.reservation.details**
  Hide reservation details from other users.

**privacy.public.future.days**
  Number of future days visible to the public.

Advanced Security Settings
--------------------------

.. code-block:: php

   'security' => [
       'headers' => false,
       'strict-transport' => 'max-age=31536000',
       'x-frame' => 'deny',
       'x-xss' => '1, mode=block',
       'x-content-type' => 'nosniff',
       'content-security-policy' => '',
   ],

**security.headers**
  Enable sending of security headers.

**security.strict-transport**
  Enable HTTP Strict Transport Security (HSTS).

**security.x-frame**
  X-Frame-Options header (prevents clickjacking).

**security.x-xss**
  X-XSS-Protection header.

**security.x-content-type**
  X-Content-Type-Options header.

**security.content-security-policy**
  Content Security Policy header.

reCAPTCHA Advanced Settings
---------------------------

.. code-block:: php

   'recaptcha' => [
       'request.method' => 'curl',
   ],

**recaptcha.request.method**
  HTTP method to use for reCAPTCHA validation. Options: curl, post, socket.

Credits System
--------------

.. code-block:: php

   'credits' => [
       'enabled' => false,
       'allow.purchase' => false,
   ],

**credits.enabled**
  Enable credit-based reservation system.

**credits.allow.purchase**
  Allow users to purchase additional credits.

Analytics Integration
---------------------

**google.analytics.tracking.id**
  Google Analytics tracking ID.

  .. code-block:: php

     'google.analytics.tracking.id' => '',

Third-Party Integrations
------------------------

**slack.token**
  Slack webhook token for notifications.

  .. code-block:: php

     'slack.token' => '',

Authentication Providers
------------------------

.. code-block:: php

   'authentication' => [
       'hide.login.prompt' => false,
       'captcha.on.login' => false,
       'required.email.domains' => '',
       'google.login.enabled' => false,
       'google.client.id' => '',
       'google.client.secret' => '',
       'google.redirect.uri' => '/Web/google-auth.php',
       'microsoft.login.enabled' => false,
       'microsoft.client.id' => '',
       'microsoft.tenant.id' => 'common',
       'microsoft.client.secret' => '',
       'microsoft.redirect.uri' => '/Web/microsoft-auth.php',
       'facebook.login.enabled' => false,
       'facebook.client.id' => '',
       'facebook.client.secret' => '',
       'facebook.redirect.uri' => '/Web/facebook-auth.php',
       'keycloak.login.enabled' => false,
       'keycloak.url' => '',
       'keycloak.realm' => '',
       'keycloak.client.id' => '',
       'keycloak.client.secret' => '',
       'keycloak.client.uri' => '/Web/keycloak-auth.php',
       'oauth2.login.enabled' => false,
       'oauth2.name' => '',
       'oauth2.url.authorize' => '',
       'oauth2.url.token' => '',
       'oauth2.url.userinfo' => '',
       'oauth2.client.id' => '',
       'oauth2.client.secret' => '',
       'oauth2.client.uri' => '/Web/oauth2-auth.php',
   ],

**authentication.hide.login.prompt**
  Hide the standard login form when external authentication is used.

**authentication.captcha.on.login**
  Enable CAPTCHA on login forms.

**authentication.required.email.domains**
  Comma-separated list of required email domains for login.

**authentication.google.login.enabled**
  Allow users to log in with Google.

**authentication.google.client.id**
  Client ID for Google authentication.

**authentication.google.client.secret**
  Client secret for Google authentication.

**authentication.google.redirect.uri**
  Redirect URI for Google authentication.

**authentication.microsoft.login.enabled**
  Allow users to log in with Microsoft.

**authentication.microsoft.client.id**
  Client ID for Microsoft authentication.

**authentication.microsoft.tenant.id**
  Tenant ID for Microsoft authentication.

**authentication.microsoft.client.secret**
  Client secret for Microsoft authentication.

**authentication.microsoft.redirect.uri**
  Redirect URI for Microsoft authentication.

**authentication.facebook.login.enabled**
  Allow users to log in with Facebook.

**authentication.facebook.client.id**
  Client ID for Facebook authentication.

**authentication.facebook.client.secret**
  Client secret for Facebook authentication.

**authentication.facebook.redirect.uri**
  Redirect URI for Facebook authentication.

**authentication.keycloak.login.enabled**
  Allow users to log in with Keycloak.

**authentication.keycloak.url**
  Base URL for Keycloak server.

**authentication.keycloak.realm**
  Keycloak realm name.

**authentication.keycloak.client.id**
  Client ID for Keycloak authentication.

**authentication.keycloak.client.secret**
  Client secret for Keycloak authentication.

**authentication.keycloak.client.uri**
  Redirect URI for Keycloak authentication.

**authentication.oauth2.login.enabled**
  Allow users to log in with OAuth2.

**authentication.oauth2.name**
  Display name for OAuth2 login.

**authentication.oauth2.url.authorize**
  Authorization URL for OAuth2 login.

**authentication.oauth2.url.token**
  Token URL for OAuth2 login.

**authentication.oauth2.url.userinfo**
  Userinfo URL for OAuth2 login.

**authentication.oauth2.client.id**
  Client ID for OAuth2 login.

**authentication.oauth2.client.secret**
  Client secret for OAuth2 login.

**authentication.oauth2.client.uri**
  Redirect URI for OAuth2 login.

For OAuth2 and SAML setup details, see :doc:`Oauth2-Configuration` and :doc:`SAML-Configuration`.

Plugin System
-------------

.. code-block:: php

   'plugins' => [
       'authentication' => '',
       'authorization' => '',
       'export' => '',
       'permission' => '',
       'postregistration' => '',
       'prereservation' => '',
       'postreservation' => '',
       'styling' => '',
   ],

Available authentication plugins: ActiveDirectory, Apache, CAS, Drupal, Krb5, Ldap, Mellon, Moodle, MoodleAdv, Saml, Shibboleth, WordPress.

For authentication plugin configuration, see:

- LDAP: :doc:`LDAP-Authentication`
- Active Directory: :doc:`ActiveDirectory-Authentication`
- OAuth2: :doc:`Oauth2-Configuration`
- SAML: :doc:`SAML-Configuration`

API Configuration
-----------------

.. code-block:: php

   'api' => [
       'enabled' => false,
       'registration.allow.self' => false,
       'authentication.group' => '',
       'accessories.ro.group' => '',
       'accounts.ro.group' => '',
       'accounts.rw.group' => '',
       'attributes.ro.group' => '',
       'groups.ro.group' => '',
       'reservations.ro.group' => '',
       'reservations.rw.group' => '',
       'resources.ro.group' => '',
       'schedules.ro.group' => '',
       'users.ro.group' => '',
   ],

**api.enabled**
  Enable the REST API.

**api.registration.allow.self**
  Allow user registration via API.

**api.authentication.group**
  Group required for API authentication (admins exempt).

**api.*.ro.group**
  Groups with read-only access to specific API endpoints.

**api.*.rw.group**
  Groups with read-write access to specific API endpoints.

Performance Tuning
------------------

For high-traffic installations:

- Set ``cache.templates`` to ``true``
- Use ``use.local.js.libs`` = ``false`` (CDN is faster)
- Enable ``schedule.fast.reservation.load`` for busy schedules
- Configure proper logging levels (avoid DEBUG in production)
- Consider database optimization and caching

Troubleshooting
---------------

**Performance Issues**
  - Enable template caching
  - Check database query performance
  - Review logging levels

**Email Problems**
  - Test SMTP settings with external client
  - Check firewall rules
  - Verify DNS configuration

**Authentication Issues**
  - Check plugin configuration
  - Review server logs
  - Verify SSL certificates for external providers
