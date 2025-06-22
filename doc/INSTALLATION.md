# LibreBooking Installation

Note: for users without web hosting service or existing environment, packages like [XAMMP](http://www.apachefriends.org/en/index.html) or [WampServer](http://www.wampserver.com/en/) can help you get set up quickly.

## Fresh Installation

### Server Configuration

In an **Apache** or similar server environment, some required modules for LibreBooking may not be enabled by default. The following modules (or their equivalents) are often not enabled as part of a standard installation but should be enabled for the proper operation of the LibreBooking application:

* headers
* rewrite

The enabled modules in an **Apache2** environment can be verified as follows:

```bash
apachectl -M
```

If required modules are not present in the enabled list, modules can be enabled in an **Apache2** environment as follows:

```bash
sudo a2enmod headers
sudo a2enmod rewrite
sudo service apache2 restart
```

### Application Deployment to Server

Move the contents of the directory to your webserver's document root (or subsite).
If you don't have direct access to your document root or use a hosting service, then transfer the directory to your web server's document root using FTP or [WinSCP](https://winscp.net/).

Copy `/config/config.dist.php` to `/config/config.php` and adjust the settings for your environment.

Important! The web server must have write access (0755) to `/librebooking/tpl_c` and `/librebooking/tpl` [want to know why?](http://www.smarty.net/docs/en/variable.compile.dir.tpl")

If using an (S)FTP client, check read/write/execute for Owner and Group on `/tpl`, `/tpl_c`, and `/uploads`

LibreBooking will not work if PHP [session.autostart](http://www.php.net/manual/en/session.configuration.php#ini.session.auto-start) is enabled.
Ensure this setting is disabled.

### Application Configuration

You can configure LibreBooking to fit your environments and needs or use the minimal default settings which should be enough for the application to work.
We recommend you to change according to your specifics. Additional information on all configuration settings can be found in the application help page.
To configure the application, you can open `/config/config.php` and alter any settings accordingly.  
The admin email address can be set in the `librebooking/config/config.php` file of `$conf['settings']['admin.email']`  
When later register an account with admin email address the user will be given admin privilege.  

In addition, to allow resource image uploads, the web server must also have read/write access to your configurable uploads directory of `$conf['settings']['image.upload.directory']` in the `config.php`.  
By default, LibreBooking uses standard username/password for user authentication.  
Alternatively, you can use LDAP authentication.  
See the plugins section of the application help page for more details.  
Note: If you try to load the application at this time (eg. <http://localhost/librebooking/Web/>), you will probably get a white page.

This is because there is no backend database configured yet.  
So continue on ...

### Database Setup

You have 2 ways to set up your database for the application to work.

#### Automatic Database Setup

You must have the application configured correctly before running the automated install.

The automated database setup only supports MySQL at this time.  
To run the automated database setup, navigate to the `/Web/install` directory in a web browser and follow the on-screen instructions.  
Note: Some may see directory permission issues displayed on the page.  
The web server must have write access to `/librebooking/tpl_c` and `/librebooking/tpl`.  
If you cannot provide the required permission. Contact your web server administrator or hosting service to resolve or run the manual install

#### Manual Database Setup

The packaged database scripts make assumptions about your desired database configuration and set default values.  
Please edit them to suit your environment before running. The files are located in `librebooking/database_schema/`  
Import the following sql files in the listed order (we recommend [phpMyAdmin](https://www.phpmyadmin.net/):

On a remote host with no database creation privileges  
If you are installing LibreBooking on a remote host, please follow these steps.  
These steps assume you are using cPanel and have the ability to create databases via the cPanel tool and phpMyAdmin.  

Adding the database and user

Select the MySQL Databases tool

Add a new user with username and password of your choice.
This will be the database user and database password set in your LibreBooking config file.  

**Please be aware that some hosts will prefix your database user name.**  

Create a new database with whatever name you choose.  
This will be the name of the database in your LibreBooking config file. 'librebooking' is the recommended database name.  

**Please be aware that some hosts will prefix your database name.**  

Associate the new user with the new database, giving the user permission to SELECT, CREATE, UPDATE, INSERT and DELETE.  
Click the 'Add User to Db' button. 'Creating tables'  
Open phpMyAdmin.  
Click on the database name that you just created in the left panel.  
Click the SQL tab at the top of the page.  
Import `/database_schema/create-schema.sql` to librebooking (or whatever database name was used in the creation process)  
Import `/database_schema/create-data.sql` to librebooking (or whatever database name was used in the creation process)  

If you have database creation privileges in MySQL  
Open `/database_schema/full-install.sql` and edit the database name and username/password to match your `config.php` database values  
Run or import `/database_schema/full-install.sql` Optionally - run/import `/database_schema/testdata-utf8.sql` to librebooking (sample application data will be created with 2 users: admin/password and user/password).  
These users are available for testing your installation.  

You are done. Try to load the application at (eg. [http://yourhostname/librebooking/Web/](http://yourhostname/librebooking/Web/)).

### Registering the Administrator Account

After the database has been set up you will need to register the account for your application administrator. Navigate to register.php register an account with email address set in `$conf['settings']['admin.email']`.

## Upgrading

### Upgrading from a previous version of LibreBooking (or Booked 2.x and phpScheduleIt 2.x)

The steps for upgrading from a previous version of LibreBooking are very similar to the steps described above in Application Deployment to Server.

#### Recommended

The recommended approach is to backup your current LibreBooking files, then upload the new files to the that same location.  
This prevents any old files from interfering with new ones.
After the new files are uploaded, copy your old `config/config.php` file to the config directory in the new version.  
Then run `/Web/install/configure.php` to bring your config file up to date.  
If you have any uploaded resource images you will need to copy them from their old location to the new one.

#### Alternative

An alternative upgrade approach is to overwrite the current LibreBooking files with the new ones.  
If doing this, you must delete the contents of `/tpl_c`.
This approach will not allow you to roll back and will not clear out any obsolete files.

#### Database

After the application files have been upgraded you will need to upgrade the database.

##### Automatical Database Upgrade

The automatic database upgrade is exactly the same as the automatic database install.  
Please follow the instructions in the Automatic Database Setup section above.

##### Manual Database Upgrade

The packaged database scripts make assumptions about your desired database configuration and set default values.
Please edit them to suit your environment before running.
The files are located in `librebooking/database_schema/upgrades.`
Depending on your current version, import the `upgrade.sql` file within each subdirectory to get to the current version (we recommend [adminer](https://www.adminer.org/) for this)  
For example, if you are running version 2.0 and the current version is 2.2 then you should run `librebooking/database_schema/upgrade/2.1/upgrade.sql` then `librebooking/database_schema/upgrade/2.2/upgrade.sql`

### Migrating from version 1.2

A migration from 1.2 to 2.0 is supported for MySQL only.  
This can be run after the 2.0 installation.  
To run the migration open `/Web/install/migrate.php` directory in a web browser and follow the on-screen instructions.

## Getting Started

### The First Login

There are 2 main types of accounts, they are admin and user account.

* If you imported a sample application data, you now can use admin/password and user/password to login and make changes or addition via the application.
* If not, **you will need to register an account with your configured admin email address**. The admin email address can be set in the `librebooking/config/config.php` file of setting `$conf['settings']['admin.email']`

Other self registration accounts are defaulted to normal users.

After registration you will be logged in automatically.

At this time, it is recommended to change your password.

* For LDAP authentication please login with your LDAP username/password.

#### Log Files

LibreBooking logs multiple levels of information categorized into either application or database logs. To do this:

* To allow application logging, the PHP account requires write access (0755) to your configured log directory.
* Logging is configured in /config/config.php
* Levels used by LibreBooking are OFF, DEBUG, ERROR. For normal operation, ERROR is appropriate. If trace logs are needed, DEBUG is appropriate.
* To turn on application logging, change the `$conf['settings']['logging']['level'] =` to an appropriate level for either the default or sql loggers. For example, `$conf['settings']['logging']['level'] = 'debug';`

### Enabling LibreBooking API

LibreBooking has the option to expose a RESTful JSON API. This API can be leveraged for third party integration, automation or to develop client applications.

#### Prerequisites

* PHP 7.0 or greater
* To use 'friendly' URLs, mod_rewrite or URL rewriting must be enabled
* Your web server must accept all verbs: GET, POST, PUT, DELETE

#### Configuration

* Set `$conf['settings']['api']['enabled'] = 'true'`; in your config file.
* If you want friendly URL paths, mod_rewrite or URL rewriting must be enabled. Note, this is not required in order to use the API.
* If using mod_rewrite and an Apache alias, ensure RewriteBase in /Web/Services/.htaccess is set to that alias root.

#### API Documentation

Auto-generated documentation for API usage can be found by browsing http://your_librebooking_url/Web/Services.  
This documentation describes each available service, indicates whether or not the service is available to unauthenticated users/administrators, and provides example requests/responses.

#### Consuming the API

If URL rewriting is being used, all services will be available from http://your_librebooking_url/Web/Services
If not using URL rewriting, all services will be available from http://your_librebooking_url/Web/Services/index.php

Certain services are only available to authenticated users or administrators. Secure services will require a session token and userid, which can be obtained from the Authentication service.

## Support

Please post any questions or issues to the github repo or the gitter chat room.
