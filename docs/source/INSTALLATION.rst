LibreBooking Installation
=========================

.. note::
   For users without web hosting service or existing environment, packages like
   `XAMMP <http://www.apachefriends.org/en/index.html>`__ or `WampServer
   <http://www.wampserver.com/en/>`__ can help you get set up quickly.

Fresh Installation
------------------

Server Configuration
~~~~~~~~~~~~~~~~~~~~

In an **Apache** or similar server environment, some required modules
for LibreBooking may not be enabled by default. The following modules
(or their equivalents) are often not enabled as part of a standard
installation but should be enabled for the proper operation of the
LibreBooking application:

-  headers
-  rewrite

The enabled modules in an **Apache2** environment can be verified as
follows:

.. code:: bash

   apachectl -M

If required modules are not present in the enabled list, modules can be
enabled in an **Apache2** environment as follows:

.. code:: bash

   sudo a2enmod headers
   sudo a2enmod rewrite
   sudo service apache2 restart

Application Deployment to Server
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Move the contents of the directory to your webserver’s document root (or
subsite). If you don’t have direct access to your document root or use a
hosting service, then transfer the directory to your web server’s
document root using FTP or `WinSCP <https://winscp.net/>`__.
Alternatively, you can clone the application directly from the official GitHub repository:

.. code-block:: bash

    git clone https://github.com/LibreBooking/app.git

After copying or cloning the application to your web server:

Install PHP dependencies using Composer:

   .. code-block:: bash

       cd app
       composer install

Copy ``/config/config.dist.php`` to ``/config/config.php`` and adjust
the settings for your environment.

Important! The web server must have write access to the following directories:

-  ``/tpl_c`` - Template compilation cache
-  ``/tpl`` - Template files
-  ``/uploads`` - File uploads (if enabled)
-  Configured log directory (if logging is enabled)

Recommended permissions are 755 for directories and 644 for files, with the web server user having write access.

`Want to know why? <http://www.smarty.net/docs/en/variable.compile.dir.tpl>`__

LibreBooking will not work if PHP
`session.autostart <http://www.php.net/manual/en/session.configuration.php#ini.session.auto-start>`__
is enabled. Ensure this setting is disabled.

Application Configuration
~~~~~~~~~~~~~~~~~~~~~~~~~

You can configure LibreBooking to fit your environment and needs or use the
minimal default settings which should be enough for the application to work.

Copy ``/config/config.dist.php`` to ``/config/config.php`` and adjust
the settings for your environment.

For detailed information on all configuration options, see :doc:`BASIC-CONFIGURATION` 
for essential settings or :doc:`ADVANCED-CONFIGURATION` for comprehensive options.

The admin email address can be set in the ``config/config.php`` file in the 
settings array as ``'admin.email' => 'admin@example.com'``

When you later register an account with the admin email address, the user will be given
admin privileges.

In addition, to allow resource image uploads, the web server must also have
read/write access to your configurable uploads directory specified by
``'uploads' => ['image.upload.directory' => 'path']`` in the ``config.php``.

By default, LibreBooking uses standard username/password for user
authentication.

Alternatively, you can use LDAP or Active Directory authentication. See :doc:`LDAP-Authentication` or :doc:`ActiveDirectory-Authentication` for setup instructions.

.. note::
   If you try to load the application at this time (eg.
   http://localhost/librebooking/Web/), you will probably get a white page.

This is because there is no backend database configured yet. So continue on …

Database Setup
~~~~~~~~~~~~~~
Edit the configuration file to set up the database connection.

Open the configuration file (located at `config/config.php`) and ensure the following database settings are properly filled out:

.. code-block:: php

    return [
        'settings' => [
            'database' => [
                'type' => 'mysql',
                'user' => 'lb_user',         // Database user with permission to access the LibreBooking database
                'password' => 'password',    // Database password
                'hostspec' => '127.0.0.1',   // IP address, DNS name, or named pipe
                'name' => 'librebooking',    // Name of the database used by LibreBooking
            ],
        ]
    ];

Ensure that the database user has the necessary privileges to create the database (if it does not exist), and to create, read, insert, update, and modify tables within it.

You have 2 ways to set up your database for the application to work.

Automatic Database Setup
^^^^^^^^^^^^^^^^^^^^^^^^

You must have the application configured correctly before running the
automated install.

| The automated database setup only supports MySQL at this time.
| To run the automated database setup, make sure to first set an installation password in the configuration file:

.. code-block:: php

    return [
        'settings' => [
            'install.password' => 'your_secure_password',
        ]
    ];

This password is required to access the installer.

Then, navigate to the ``/Web/install`` directory in a web browser and follow
the on-screen instructions.

.. note::
   Some may see directory permission issues displayed on the page.
   The web server must have write access to ``/librebooking/tpl_c`` and
   ``/librebooking/tpl``.
   If you cannot provide the required permission. Contact your web server
   administrator or hosting service to resolve or run the manual install

Manual Database Setup
^^^^^^^^^^^^^^^^^^^^^

| The packaged database scripts make assumptions about your desired
  database configuration and set default values.
| Please edit them to suit your environment before running. The files
  are located in ``librebooking/database_schema/``
| 
| The following SQL files are available:
| - ``create-db.sql`` - Creates the database
| - ``create-user.sql`` - Creates the database user (optional)
| - ``create-schema.sql`` - Creates base table structure
| - ``database_schema/upgrades/*/schema.sql`` - Database schema upgrades
| - ``database_schema/upgrades/*/data.sql`` - Database data upgrades
| - ``create-data.sql`` - Inserts initial application data
| - ``sample-data-utf8.sql`` - Sample data for testing (optional)
|

.. important::
   **Correct Import Order**
   
   The automated installer (Web/install) follows this sequence:
   
   1. ``create-schema.sql`` - Base table structure
   2. All upgrade scripts in ``database_schema/upgrades/`` (in version order)
   3. ``create-data.sql`` - Initial data (depends on upgraded schema)
   4. ``sample-data-utf8.sql`` (optional) - Sample data for testing
   
   **Warning:** Simply running create-schema.sql followed by create-data.sql will fail 
   because create-data.sql expects the fully upgraded schema including all modifications 
   from the upgrade scripts.

| Import the SQL files in the following order (we recommend
  `phpMyAdmin <https://www.phpmyadmin.net/>`__):

| On a remote host with no database creation privileges
| If you are installing LibreBooking on a remote host, please follow
  these steps.
| These steps assume you are using cPanel and have the ability to create
  databases via the cPanel tool and phpMyAdmin.

Adding the database and user

Select the MySQL Databases tool

Add a new user with username and password of your choice. This will be
the database user and database password set in your LibreBooking config
file.

**Please be aware that some hosts will prefix your database user name.**

| Create a new database with whatever name you choose.
| This will be the name of the database in your LibreBooking config
  file. ‘librebooking’ is the recommended database name.

**Please be aware that some hosts will prefix your database name.**

| Associate the new user with the new database, giving the user
  permission to SELECT, CREATE, UPDATE, INSERT and DELETE.
| Click the ‘Add User to Db’ button. ‘Creating tables’
| Open phpMyAdmin.
| Click on the database name that you just created in the left panel.
| Click the SQL tab at the top of the page.
| Import ``/database_schema/create-schema.sql`` to librebooking (or
  whatever database name was used in the creation process)
| Import all upgrade scripts from ``/database_schema/upgrades/`` in version order.
  For each version directory (2.1, 2.2, 2.3, etc.), import first the ``schema.sql`` 
  then the ``data.sql`` file if they exist.
| Import ``/database_schema/create-data.sql`` to librebooking (or
  whatever database name was used in the creation process)

| If you have database creation privileges in MySQL
| Open ``/database_schema/create-db.sql`` to create the database
| Import ``/database_schema/create-schema.sql`` to create the table structure
| Import all upgrade scripts from ``/database_schema/upgrades/`` in version order.
  For each version directory, import ``schema.sql`` then ``data.sql`` if they exist.
| Import ``/database_schema/create-data.sql`` to populate initial data
| Optionally - import ``/database_schema/sample-data-utf8.sql`` to add
  sample application data (this will create 2 test users: admin/password
  and user/password for testing your installation).

You are done. Try to load the application at (eg.
http://yourhostname/librebooking/Web/).

Building from Source
---------------------

If you want to build LibreBooking from source code, the project includes a Phing build configuration.

Prerequisites
~~~~~~~~~~~~~

-  PHP with MySQL support
-  `Phing <https://www.phing.info/>`__ build tool
-  MySQL server access for database setup
-  Composer for PHP dependencies

Build Process
~~~~~~~~~~~~~

The build process is configured in ``build.xml`` and includes several targets:

**Database Setup:**

.. code-block:: bash

   # Set up a fresh database with latest schema
   phing setup.db

**Package Creation:**

.. code-block:: bash

   # Create a distribution package
   phing package

The packaging process will:

-  Create a clean staging directory
-  Copy application files while excluding development artifacts
-  Combine database schema files
-  Create a distribution ZIP file
-  Generate a build bundle in ``build/bundle/``

**Files Excluded from Package:**

The build process automatically excludes development files:

-  Configuration files (``config.php``, ``*.config.php``)
-  User uploads and temporary files
-  Development tools and IDE files
-  Testing files and documentation
-  Version control files
-  Build artifacts

**Database Upgrades:**

For upgrading existing installations:

.. code-block:: bash

   # Upgrade database to latest version
   phing upgrade.db

This will run any pending database migrations from the ``database_schema/upgrades/`` directory.

**Database File Combination:**

The build process creates optimized database installation files by combining:

-  ``create-schema.sql`` - Table structure
-  ``create-data.sql`` - Initial data

This creates consolidated installation files for easier deployment.

Docker Installation (Recommended)
----------------------------------

LibreBooking can be easily deployed using Docker containers, which provides a consistent environment and simplifies setup. This is the recommended method for new installations.

Prerequisites
~~~~~~~~~~~~~

-  Docker and Docker Compose installed on your system
-  Basic understanding of Docker concepts

Quick Start with Docker Compose
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

   -  Open your browser to ``http://localhost/install``
   -  Enter the installation password (``LB_INSTALL_PWD`` from docker-compose.yml)
   -  Enter database root user: ``root``
   -  Enter database root password (``MYSQL_ROOT_PASSWORD`` from docker-compose.yml)
   -  Select "Create the database" and "Create the database user"
   -  Click the register link to create your admin account

Docker Environment Variables
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

Docker Image Versions
~~~~~~~~~~~~~~~~~~~~~

**Stable Release:**

.. code-block:: bash

   docker pull librebooking/librebooking:v3.0.3

**Development Version:**

.. code-block:: bash

   docker pull librebooking/librebooking:develop

Persistent Data Storage
~~~~~~~~~~~~~~~~~~~~~~~

To persist data beyond container lifecycle, mount these directories:

**Configuration:**
  Mount ``/config`` volume to persist configuration files

**File Uploads:**
  Mount ``/var/www/html/Web/uploads/images`` for uploaded images
  Mount ``/var/www/html/Web/uploads/reservation`` for reservation attachments

Background Jobs (Cron)
~~~~~~~~~~~~~~~~~~~~~~

LibreBooking requires background jobs for features like reminder emails:

.. code-block:: yaml

   environment:
     - LB_CRON_ENABLED=true

Or run them manually:

.. code-block:: bash

   docker exec <container_name> php -f /var/www/html/Jobs/sendreminders.php

Docker Troubleshooting
~~~~~~~~~~~~~~~~~~~~~~

**Container won't start:**
  -  Check Docker logs: ``docker-compose logs app``
  -  Verify environment variables are set correctly
  -  Ensure database container is running: ``docker-compose ps``

**Cannot access installation:**
  -  Verify port mapping: ``docker-compose ps``
  -  Check firewall settings
  -  Ensure ``LB_INSTALL_PWD`` is set

**Database connection failed:**
  -  Verify database container is healthy: ``docker-compose logs db``
  -  Check database environment variables match between services
  -  Ensure containers are on the same network

**Configuration not persisting:**
  -  Verify volume mounts are correct
  -  Check container has write permissions to volumes
  -  Use named volumes instead of bind mounts for easier management

For more detailed Docker configuration options and advanced setups, see the 
`LibreBooking Docker repository <https://github.com/LibreBooking/docker>`__.

Registering the Administrator Account
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

After the database has been set up you will need to register the account
for your application administrator. Navigate to register.php register an
account with email address set as the ``'admin.email'`` value in your configuration.

Upgrading
---------

Upgrading from a previous version of LibreBooking (or Booked 2.x and phpScheduleIt 2.x)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The steps for upgrading from a previous version of LibreBooking are very
similar to the steps described above in Application Deployment to
Server.

Recommended
^^^^^^^^^^^

| The recommended approach is to backup your current LibreBooking files,
  then upload the new files to the that same location.
| This prevents any old files from interfering with new ones. After the
  new files are uploaded, copy your old ``config/config.php`` file to
  the config directory in the new version.
| Then run ``/Web/install/configure.php`` to bring your config file up
  to date.
| If you have any uploaded resource images you will need to copy them
  from their old location to the new one.

Alternative
^^^^^^^^^^^

| An alternative upgrade approach is to overwrite the current
  LibreBooking files with the new ones.
| If doing this, you must delete the contents of ``/tpl_c``. This
  approach will not allow you to roll back and will not clear out any
  obsolete files.

Database
^^^^^^^^

After the application files have been upgraded you will need to upgrade
the database.

Automatical Database Upgrade
''''''''''''''''''''''''''''

| The automatic database upgrade is exactly the same as the automatic
  database install.
| Please follow the instructions in the Automatic Database Setup section
  above.

Manual Database Upgrade
'''''''''''''''''''''''

| The packaged database scripts make assumptions about your desired
  database configuration and set default values. Please edit them to
  suit your environment before running. The files are located in
  ``librebooking/database_schema/upgrades.`` Depending on your current
  version, import the ``upgrade.sql`` file within each subdirectory to
  get to the current version (we recommend
  `adminer <https://www.adminer.org/>`__ for this)
| For example, if you are running version 2.0 and the current version is
  2.2 then you should run
  ``librebooking/database_schema/upgrade/2.1/upgrade.sql`` then
  ``librebooking/database_schema/upgrade/2.2/upgrade.sql``

Migrating from version 1.2
~~~~~~~~~~~~~~~~~~~~~~~~~~

| A migration from 1.2 to 2.0 is supported for MySQL only.
| This can be run after the 2.0 installation.
| To run the migration open ``/Web/install/migrate.php`` directory in a
  web browser and follow the on-screen instructions.

Getting Started
---------------

The First Login
~~~~~~~~~~~~~~~

There are 2 main types of accounts, they are admin and user account.

-  If you imported a sample application data, you now can use
   admin/password and user/password to login and make changes or
   addition via the application.
-  If not, **you will need to register an account with your configured
   admin email address**. The admin email address is set in the
   ``librebooking/config/config.php`` file as ``'admin.email' => 'admin@example.com'``
   within the settings array.

Other self registration accounts are defaulted to normal users.

After registration you will be logged in automatically.

At this time, it is recommended to change your password.

-  For LDAP or Active Directory authentication, login with your directory username/password. See :doc:`LDAP-Authentication` or :doc:`ActiveDirectory-Authentication` for configuration details.

Log Files
^^^^^^^^^

LibreBooking logs multiple levels of information categorized into either
application or database logs. To do this:

-  To allow application logging, the PHP account requires write access
   (0755) to your configured log directory.
-  Logging is configured in /config/config.php
-  Levels used by LibreBooking are OFF, DEBUG, ERROR. For normal
   operation, ERROR is appropriate. If trace logs are needed, DEBUG is
   appropriate.
-  To turn on application logging, change the logging level setting
   in your configuration file to an appropriate level. For example,
   set ``'logging' => ['level' => 'debug']`` within the settings array.

For detailed information on all logging and other configuration options, 
see :doc:`BASIC-CONFIGURATION` for essential settings or :doc:`ADVANCED-CONFIGURATION` 
for comprehensive options.


Enabling LibreBooking API
~~~~~~~~~~~~~~~~~~~~~~~~~

LibreBooking has the option to expose a RESTful JSON API. This API can
be leveraged for third party integration, automation or to develop
client applications.

Prerequisites
^^^^^^^^^^^^^

-  PHP 8.2 or greater
-  To use ‘friendly’ URLs, mod_rewrite or URL rewriting must be enabled
-  Your web server must accept all verbs: GET, POST, PUT, DELETE

Configuration
^^^^^^^^^^^^^

-  Set ``'api' => ['enabled' => true]`` in your config file's settings array.
-  If you want friendly URL paths, mod_rewrite or URL rewriting must be
   enabled. Note, this is not required in order to use the API.
-  If using mod_rewrite and an Apache alias, ensure RewriteBase in
   /Web/Services/.htaccess is set to that alias root.

API Documentation
^^^^^^^^^^^^^^^^^

Auto-generated documentation for API usage can be found by browsing
http://your_librebooking_url/Web/Services.

API documentation is also available at :doc:`API`

This documentation describes each available service, indicates whether or not
the service is available to unauthenticated users/administrators, and provides
example requests/responses.

Consuming the API
^^^^^^^^^^^^^^^^^

If URL rewriting is being used, all services will be available from
http://your_librebooking_url/Web/Services If not using URL rewriting,
all services will be available from
http://your_librebooking_url/Web/Services/index.php

Certain services are only available to authenticated users or
administrators. Secure services will require a session token and userid,
which can be obtained from the Authentication service.

Support
-------

Please post any questions or issues to the github repo or the gitter
chat room.
