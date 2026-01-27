Active Directory Authentication
================================

The Active Directory authentication plugin provides enhanced support for Microsoft Active Directory with features like Single Sign-On (SSO).

Enable the Plugin
-----------------

Edit ``/config/config.php`` and set:

.. code-block:: php

   'plugins' => [
       'authentication' => 'ActiveDirectory',
   ],

Alternatively, enable the plugin through the web admin interface at **Application Configuration** (``/Web/admin/manage_configuration.php``).

Configuration
-------------

If not existing already, copy the template and edit with your Active Directory settings:

.. code-block:: bash

   cp /plugins/Authentication/ActiveDirectory/ActiveDirectory.config.dist.php /config/ActiveDirectory.config.php

The configuration file at ``/config/ActiveDirectory.config.php`` contains all available options with detailed comments. You can also view and modify these settings through the web admin interface at **Application Management > Configuration**. Key settings include:

- **domain.controllers**: Comma-separated list of domain controller hostnames
- **username/password**: Service account credentials for AD searches
- **basedn**: Base DN in DC= format (e.g., ``DC=example,DC=com``)
- **account.suffix**: Domain suffix for user logins (e.g., ``@example.com``)
- **attribute.mapping**: Maps AD attributes to LibreBooking fields (note: AD uses ``givenName``, ``telephoneNumber``, etc.)
- **sync.groups**: Enable group membership synchronization
- **use.sso**: Enable Windows Single Sign-On
- **database.auth.when.ldap.user.not.found**: Fallback to database authentication

Alternatively, configure the plugin through the web admin interface at **Application Configuration** (``/Web/admin/manage_configuration.php``) and select **Authentification-ActiveDirectory**.
Refer to ``/plugins/Authentication/ActiveDirectory/ActiveDirectory.config.dist.php`` for complete documentation of all options.

User Login
----------

With ``account.suffix`` configured, users can log in with just their username:

- User enters: ``jsmith``
- Plugin authenticates as: ``jsmith@example.com``

This simplifies the login experience while maintaining proper AD authentication.

Single Sign-On (SSO)
--------------------

When ``use.sso`` is enabled, users are automatically authenticated using their Windows login credentials.

Requirements:

- Web server configured for Windows authentication:
  
  - IIS with Windows Authentication enabled
  - Apache with mod_auth_sspi or mod_auth_kerb

- Browsers configured to send Windows credentials automatically
- Users accessing from domain-joined computers
- The ``$_SERVER['REMOTE_USER']`` variable populated by the web server

With SSO enabled, users won't see a login page - they're automatically logged in using their Windows credentials.

Troubleshooting
---------------

Enable Debug Logging
~~~~~~~~~~~~~~~~~~~~

Set ``debug.enabled`` to ``true`` in the Ldap plugin configuration (Active Directory uses the same logging) to see detailed operations in LibreBooking logs.

Migration from Database Auth
-----------------------------

To migrate existing users:

1. Keep ``database.auth.when.ldap.user.not.found`` set to ``true``
2. Ensure LibreBooking usernames match AD usernames (without @domain)
3. Users automatically switch to AD auth on next login
4. Existing reservations and data are preserved

Users are matched by username - if a LibreBooking account exists with the same username, it will be updated with AD information.
