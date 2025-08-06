Basic Configuration
===================

This guide covers the essential configuration settings needed to get LibreBooking up and running. All settings are configured in the ``/config/config.php`` file, which should be created by copying ``/config/config.dist.php``.

The configuration file uses a PHP array format that returns a settings array. Some settings use nested arrays (like database settings), while others use flat dot notation.

Getting Started
---------------

Copy the configuration template:

.. code-block:: bash

   cp /config/config.dist.php /config/config.php

Then edit the file with your preferred settings.

Environment Variable Override
-----------------------------

LibreBooking supports overriding configuration settings using environment variables. This is especially useful for Docker deployments or when you want to keep sensitive information separate from configuration files.

**Pattern**
  Environment variables follow the pattern ``LB_`` + the config key with dots and dashes converted to underscores and converted to uppercase.

**Examples**

- ``app.title`` → ``LB_APP_TITLE``
- ``database.hostspec`` → ``LB_DATABASE_HOSTSPEC``
- ``admin.email`` → ``LB_ADMIN_EMAIL``
- ``default.timezone`` → ``LB_DEFAULT_TIMEZONE``

**Using .env Files**
  LibreBooking automatically loads ``.env`` files if present in the root directory. See ``develop/app/.env.example`` for a complete list of available environment variables.

**Priority**
  Environment variables take precedence over configuration file settings. The order of precedence is:

  1. Environment variables (highest priority)
  2. Configuration file settings
  3. Default values (lowest priority)

Essential Settings
------------------

Application Identity
~~~~~~~~~~~~~~~~~~~~

**app.title**
  The title of the application displayed in the header and browser tab.
  
  .. code-block:: php
  
     'app.title' => 'LibreBooking',

**admin.email**
  Administrator email address.
  
  .. code-block:: php
  
     'admin.email' => 'admin@example.com',

**company.name**
  Company name to show in the page header.
  
  .. code-block:: php
  
     'company.name' => '',

**company.url**
  URL to the company's website.
  
  .. code-block:: php
  
     'company.url' => '',

Time and Language
~~~~~~~~~~~~~~~~~

**default.timezone**
  Look up here http://php.net/manual/en/timezones.php.
  
  .. code-block:: php
  
     'default.timezone' => 'Europe/London',

**default.language**
  Default language for the application.
  
  .. code-block:: php
  
     'default.language' => 'en_us',

Database Configuration
----------------------

Database settings are configured as a nested array:

.. code-block:: php

   'database' => [
       'type' => 'mysql',
       'hostspec' => '127.0.0.1',
       'name' => 'librebooking',
       'user' => 'lb_user',
       'password' => 'password',
   ],

**database.type**
  Type of database used by the application.

**database.hostspec**
  Hostname or IP address of the database server.

**database.name**
  Name of the database used by the application.

**database.user**
  Username for connecting to the database.

**database.password**
  Password for connecting to the database.

Email Configuration
-------------------

Email functionality is essential for user registration and notifications.

Basic Email Settings
~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

   'email' => [
       'enabled' => true,
       'default.from.address' => '',
       'default.from.name' => '',
   ],

**email.enabled**
  Enable or disable email notifications.

**email.default.from.address**
  Default email address for outgoing emails.

**email.default.from.name**
  Default display name for outgoing emails.

SMTP Configuration
~~~~~~~~~~~~~~~~~~

For reliable email delivery, configure SMTP settings:

.. code-block:: php

   'phpmailer' => [
       'mailer' => 'smtp',
       'smtp.host' => '',
       'smtp.port' => 25,
       'smtp.secure' => '',
       'smtp.auth' => true,
       'smtp.username' => 'your_smtp_username',
       'smtp.password' => 'your_smtp_password',
   ],

**phpmailer.mailer**
  Mailer type to use for sending emails.

**phpmailer.smtp.host**
  SMTP server hostname.

**phpmailer.smtp.port**
  SMTP server port.

**phpmailer.smtp.secure**
  Encryption type for SMTP.

**phpmailer.smtp.auth**
  Enable SMTP authentication.

**phpmailer.smtp.username**
  Username for SMTP authentication.

**phpmailer.smtp.password**
  Password for SMTP authentication.

**phpmailer.smtp.username**
  SMTP username (often your email address).

**phpmailer.smtp.password**
  SMTP password.

User Registration
-----------------

.. code-block:: php

   'registration' => [
       'allow.self.registration' => true,
       'captcha.enabled' => true,
       'require.email.activation' => false,
       'notify.admin' => false,
   ],

**registration.allow.self.registration**
  Allow users to register themselves.

**registration.captcha.enabled**
  Enable captcha on the registration form.

**registration.require.email.activation**
  Require email activation for new registrations.

**registration.notify.admin**
  Send notification to admin when a new user registers.

Security Settings
-----------------

.. code-block:: php

   'recaptcha' => [
       'enabled' => false,
       'public.key' => '',
       'private.key' => '',
   ],

**recaptcha.enabled**
  Enable Google reCAPTCHA for forms.

**recaptcha.public.key**
  Public key for Google reCAPTCHA.

**recaptcha.private.key**
  Private key for Google reCAPTCHA.

Password Policy
~~~~~~~~~~~~~~~

.. code-block:: php

   'password' => [
       'minimum.letters' => 6,
       'minimum.numbers' => 0,
       'upper.and.lower' => false,
       'disable.reset' => false,
   ],

**password.minimum.letters**
  Minimum number of letters required in passwords.

**password.minimum.numbers**
  Minimum number of numbers required in passwords.

**password.upper.and.lower**
  Require both upper and lower case letters in passwords.

**password.disable.reset**
  Disable the password reset feature.

Frontend Settings
-----------------

.. code-block:: php

   'script.url' => '',
   'css.theme' => 'default',
   'cache.templates' => true,
   'default.homepage' => 1,

**script.url**
  Public URL to the Web directory of this instance.

**css.theme**
  Theme to use for the application. Options: default, dimgray, dark_red, dark_green, french_blue, cake_blue, orange.

**cache.templates**
  Enable or disable template caching.

**default.homepage**
  Default homepage for new users.

Installation
------------

**install.password**
  Password required for installation or upgrades.
  
  .. code-block:: php
  
     'install.password' => '',

Basic Privacy Settings
----------------------

.. code-block:: php

   'privacy' => [
       'view.schedules' => true,
       'view.reservations' => false,
       'allow.guest.reservations' => false,
   ],

**privacy.view.schedules**
  Allow users to view schedules.

**privacy.view.reservations**
  Allow users to view reservations.

**privacy.allow.guest.reservations**
  Allow guests to make reservations.

Next Steps
----------

After configuring these basic settings:

1. Set up your database using the installation wizard at ``/Web/install/``
2. Test email functionality
3. Create your first admin user
4. Configure resources and schedules
5. For advanced features, see :doc:`ADVANCED-CONFIGURATION`

Docker Installation
===================

LibreBooking can be easily deployed using Docker containers. This is the recommended method for quick setup and testing.

Prerequisites
-------------

- Docker and Docker Compose installed on your system
- Basic understanding of Docker concepts

Quick Start with Docker Compose
-------------------------------

1. **Create a docker-compose.yml file:**

   .. code-block:: yaml

      name: librebooking

      services:
        db:
          image: linuxserver/mariadb:10.6.13
          restart: always
          volumes:
            - db_data:/config
          environment:
            - PUID=1000
            - PGID=1000
            - TZ=America/New_York
            - MYSQL_ROOT_PASSWORD=your_secure_root_password
        
        app:
          image: librebooking/librebooking:develop
          restart: always
          depends_on:
            - db
          ports:
            - "80:80"
          volumes:
            - app_config:/config
          environment:
            - LB_DB_NAME=librebooking
            - LB_DB_USER=lb_user
            - LB_DB_USER_PWD=your_secure_user_password
            - LB_DB_HOST=db
            - LB_INSTALL_PWD=your_installation_password
            - TZ=America/New_York

      volumes:
        db_data:
        app_config:

2. **Start the services:**

   .. code-block:: bash

      docker-compose up -d

3. **Complete the installation:**

   - Open your browser to ``http://localhost/install``
   - Enter the installation password (``LB_INSTALL_PWD`` from docker-compose.yml)
   - Enter database root user: ``root``
   - Enter database root password (``MYSQL_ROOT_PASSWORD`` from docker-compose.yml)
   - Select "Create the database" and "Create the database user"
   - Click the register link to create your admin account

Docker Environment Variables
----------------------------

**Required Environment Variables (when config.php doesn't exist):**

``LB_DB_NAME``
  Database name for LibreBooking (e.g., ``librebooking``)

``LB_DB_USER``
  Database username (e.g., ``lb_user``)

``LB_DB_USER_PWD``
  Database user password

``LB_DB_HOST``
  Database hostname (e.g., ``db`` when using docker-compose)

``LB_INSTALL_PWD``
  Password for accessing the installation wizard

``TZ``
  Timezone (e.g., ``America/New_York``, ``Europe/London``)

**Optional Environment Variables:**

``LB_ENV``
  Environment mode: ``production`` (default) or ``dev``

``LB_LOG_FOLDER``
  Log directory (default: ``/var/log/librebooking``)

``LB_LOG_LEVEL``
  Logging level: ``none`` (default), ``debug``, ``error``

``LB_LOG_SQL``
  Enable SQL logging: ``false`` (default), ``true``

``LB_CRON_ENABLED``
  Enable background cron jobs: ``false`` (default), ``true``

``LB_PATH``
  URL path prefix (for reverse proxy setups)

Docker Image Versions
---------------------

**Stable Release:**

.. code-block:: bash

   docker pull librebooking/librebooking:v3.0.3

**Development Version:**

.. code-block:: bash

   docker pull librebooking/librebooking:develop

Persistent Data
---------------

To persist data beyond container lifecycle, mount these directories:

**Configuration:**
  Mount ``/config`` volume to persist configuration files

**File Uploads:**
  Mount ``/var/www/html/Web/uploads/images`` for uploaded images
  Mount ``/var/www/html/Web/uploads/reservation`` for reservation attachments

Example with persistent uploads:

.. code-block:: yaml

   app:
     image: librebooking/librebooking:develop
     volumes:
       - app_config:/config
       - ./uploads/images:/var/www/html/Web/uploads/images
       - ./uploads/reservation:/var/www/html/Web/uploads/reservation

Background Jobs (Cron)
----------------------

LibreBooking requires background jobs for features like reminder emails. Enable them with:

.. code-block:: yaml

   environment:
     - LB_CRON_ENABLED=true

Or run them manually from the host:

.. code-block:: bash

   docker exec <container_name> php -f /var/www/html/Jobs/sendreminders.php

Docker Troubleshooting
----------------------

**Container won't start:**
  - Check Docker logs: ``docker-compose logs app``
  - Verify environment variables are set correctly
  - Ensure database container is running: ``docker-compose ps``

**Cannot access installation:**
  - Verify port mapping: ``docker-compose ps``
  - Check firewall settings
  - Ensure ``LB_INSTALL_PWD`` is set

**Database connection failed:**
  - Verify database container is healthy: ``docker-compose logs db``
  - Check database environment variables match between services
  - Ensure containers are on the same network

**Configuration not persisting:**
  - Verify volume mounts are correct
  - Check container has write permissions to volumes
  - Use named volumes instead of bind mounts for easier management

Common Issues
-------------

**Email not working?**
  - Check SMTP settings
  - Verify firewall allows outbound connections on your SMTP port
  - Test with a simple mail client first

**Can't access after setup?**
  - Check ``script.url`` setting
  - Verify web server configuration
  - Check file permissions on uploads and cache directories

**Database connection errors?**
  - Verify database credentials
  - Ensure database exists and user has proper permissions
  - Check database server is running and accessible
