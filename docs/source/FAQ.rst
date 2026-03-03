Frequently Asked Questions
==========================

This FAQ collects recurring questions from the LibreBooking GitHub issues and
discussions and points to the longer documentation when you need the full
setup or configuration details.

Why do I get a white page, HTTP 500, or ``Unknown Error`` after install or upgrade?
-----------------------------------------------------------------------------------

Start with the basics:

1. Confirm that ``config/config.php`` exists and the database credentials are
   correct.
2. Confirm that ``tpl``, ``tpl_c``, and ``uploads`` are writable by the web
   server.
3. Confirm that the configured log directory is writable by the web server so
   LibreBooking can write error logs there. For example, some deployments use
   permissions such as ``0755``, but the exact mode depends on your server user
   and group setup.
4. Enable logging and set the log level to ``debug`` so the real exception is
   written to disk.
5. Re-check PHP requirements and loaded extensions.

In practice, many "unknown error" reports come down to one of three causes:
incorrect database credentials, missing Composer dependencies, or directory
permission problems.

See :doc:`INSTALLATION` and/or :doc:`BASIC-CONFIGURATION`.

Related threads:

- `<https://github.com/LibreBooking/librebooking/discussions/752>`__
- `<https://github.com/LibreBooking/librebooking/discussions/999>`__
- `<https://github.com/LibreBooking/librebooking/issues/171>`__

Why does LibreBooking fail with ``Class "Smarty" not found``?
-------------------------------------------------------------

LibreBooking depends on Composer-managed PHP packages, including Smarty. If
you deploy the source tree without running ``composer install``, the
application will load PHP files from the repository but fail when it reaches a
class provided by ``vendor/``.

Install dependencies in the application root:

.. code-block:: bash

   composer install --no-dev

For development, use ``composer install`` instead.

If you are deploying to shared hosting, run Composer before upload or on the
target host if the host supports it.

See :doc:`INSTALLATION`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/issues/413>`__

Which URL should I use for the installer?
-----------------------------------------

Use the ``Web`` directory with the correct case:

.. code-block:: text

   https://example.com/Web/install

On case-sensitive hosts, ``/web/install`` and ``/Web/Install`` are different
paths and may return 404 or 500 errors even when the application is deployed
correctly.

You must also set ``install.password`` in ``config/config.php`` before the
installer can run.

See :doc:`INSTALLATION` and :doc:`BASIC-CONFIGURATION`.

Related threads:

- `<https://github.com/LibreBooking/librebooking/discussions/752>`__
- `<https://github.com/LibreBooking/librebooking/discussions/501>`__

Why do my URLs redirect to ``/Web/Web/``?
-----------------------------------------

This usually means ``script.url`` is configured incorrectly.

Set ``script.url`` to the base URL of LibreBooking's ``Web`` directory exactly
once, for example:

.. code-block:: php

   'script.url' => 'https://example.com/librebooking/Web',

Do not append ``/Web`` twice, and do not point it at a specific page such as
``/Web/index.php`` or ``/Web/schedule.php``. LibreBooking builds application
links by appending page paths to ``script.url``, so a value that already
contains an extra ``/Web`` will produce broken URLs such as ``/Web/Web/...``.

See :doc:`BASIC-CONFIGURATION`.

Related threads:

- `<https://github.com/LibreBooking/librebooking/discussions/411>`__
- `<https://github.com/LibreBooking/librebooking/discussions/516>`__

What account do I use to sign in right after installation?
----------------------------------------------------------

There is no universal built-in administrator password for normal production
installs.

After installation, register an account using the email address configured as
``admin.email`` in ``config/config.php``. That user will receive administrator
permissions.

If self-registration is disabled, temporarily enable it so the administrator
account can be registered with the configured ``admin.email`` address.

See :doc:`INSTALLATION` and :doc:`BASIC-CONFIGURATION`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/issues/308>`__

How do I hide reservation details from other users?
---------------------------------------------------

Use the privacy settings in ``config/config.php``, especially
``privacy.view.reservations``. That setting controls whether users can see
reservation details for resources they are not allowed to manage.

If you need per-resource behavior instead of a global privacy policy, that is
not a simple configuration toggle today and may require custom code or a
workflow change.

See :doc:`BASIC-CONFIGURATION`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/issues/157>`__

Why does LDAP authentication fail after migrating from older versions?
----------------------------------------------------------------------

Check the LDAP host value in ``config/Ldap.config.php``. In LibreBooking, the
host must include the LDAP scheme prefix, for example ``ldap://ldap.example.com``
or ``ldaps://ldap.example.com``.

If you migrate settings from older Booked Scheduler or Librebooking versions
and keep only the bare hostname, LDAP bind and login can fail even though the
hostname itself is correct.

See :doc:`LDAP-Authentication`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/discussions/755>`__

How do I configure Microsoft Entra ID / Azure AD login?
-------------------------------------------------------

LibreBooking uses a server-side OAuth flow. In Microsoft Entra ID, register
LibreBooking as a **Web** application, not a single-page application (SPA).

Use:

- Redirect URI: ``https://your-host/Web/microsoft-auth.php``
- A client secret **value**, not the secret ID
- Standard OAuth endpoints for your tenant

If Entra reports that PKCE is required, the app was likely registered as an
SPA. If token exchange fails with an invalid secret error, verify that you
copied the secret value and not the identifier shown beside it in the portal.

Your IdP must also return the attributes LibreBooking needs. In practice,
missing email claims can prevent auto-provisioning from succeeding.

If you want new external-auth users to be created automatically on first
login, enable ``registration.allow.self``. Otherwise new users
can be redirected back with a self-registration-disabled error instead of
being signed in.

See :doc:`Oauth2-Configuration` and :doc:`ADVANCED-CONFIGURATION`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/discussions/987>`__

Can LibreBooking automatically create external-auth users and route them to the right schedule?
-----------------------------------------------------------------------------------------------

LibreBooking can create users during external authentication, but custom
tenant-specific behavior such as choosing a schedule by email domain or
assigning groups automatically is not a simple configuration setting.

For organization-specific routing, you will likely need to customize the
external authentication flow so it updates fields such as
``default_schedule_id`` after the identity provider returns user data.

See :doc:`Oauth2-Configuration` and :doc:`ADVANCED-CONFIGURATION`.

Related thread:

- `<https://github.com/LibreBooking/librebooking/discussions/1078>`__

What is the correct order for a manual database installation or upgrade?
------------------------------------------------------------------------

For a fresh manual install, use this order:

1. ``create-db.sql`` if you need LibreBooking to create the database
2. ``create-user.sql`` if you want to create a dedicated database user
3. ``create-schema.sql``
4. All scripts in ``database_schema/upgrades/`` in version order
5. ``create-data.sql``
6. ``sample-data-utf8.sql`` if you want demo data

If your hosting control panel or DBA already created the database and database
user, you can skip ``create-db.sql`` and ``create-user.sql`` and start with
``create-schema.sql``.

For upgrades, run only the pending scripts from ``database_schema/upgrades/``
or use the supported upgrade tooling described in the installation guide.

See :doc:`INSTALLATION`.

Related threads:

- `<https://github.com/LibreBooking/librebooking/discussions/881>`__
- `<https://github.com/LibreBooking/librebooking/discussions/999>`__

How should I approach a shared-hosting or cPanel deployment?
------------------------------------------------------------

Shared hosting can work, but it is less forgiving than Docker or a managed
Linux host. At minimum, verify the following:

- PHP 8.2 or newer is available.
- Required PHP extensions are enabled.
- ``composer install`` has been run.
- ``config/config.php`` points at the correct database host, database name,
  user, and password.
- ``tpl``, ``tpl_c``, and ``uploads`` are writable.
- The application is served from the correct document root and URLs use
  ``/Web/...`` with the correct case.

If your hosting panel prefixes database names and usernames, use those exact
prefixed values in the LibreBooking database configuration.

See :doc:`INSTALLATION` and :doc:`BASIC-CONFIGURATION`.

Related threads:

- `<https://github.com/LibreBooking/librebooking/discussions/752>`__
- `<https://github.com/LibreBooking/librebooking/issues/13>`__
