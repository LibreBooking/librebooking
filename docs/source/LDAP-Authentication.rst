LDAP Authentication
===================

The LDAP authentication plugin allows users to authenticate against OpenLDAP
and other generic LDAP directory servers.

Prerequisites
-------------

Before enabling the LDAP plugin, install LDAP support for PHP and the LDAP client library package:

.. code-block:: bash

   # install/enable PHP LDAP extension (package name varies by distro)
   # examples:
   #   dnf install php-ldap
   #   apt install php-ldap

   composer require symfony/ldap

If ``symfony/ldap`` is not installed, enabling the LDAP plugin will fail with:

.. code-block:: text

   The LDAP plugin requires symfony/ldap. Install it with: composer require symfony/ldap

Enable the Plugin
-----------------

Edit ``/config/config.php`` and set:

.. code-block:: php

   'plugins' => [
       'authentication' => 'Ldap',
   ],

Alternatively, enable the plugin through the web admin interface at
**Application Configuration** (``/Web/admin/manage_configuration.php``).

Configuration
-------------

If not existing already, copy the template and edit with your LDAP settings:

.. code-block:: bash

   cp /plugins/Authentication/Ldap/Ldap.config.dist.php /config/Ldap.config.php

The configuration file at ``/config/Ldap.config.php`` contains all available
options with detailed comments explaining each setting. You can also view and
modify these settings through the web admin interface at **Application
Management > Configuration**. Key settings include:

- **uri**: LDAP URI string. For multiple servers, use a space-separated list of URIs.
- **binddn/bindpw**: Service account credentials for directory searches
- **basedn**: Base DN where users are located
- **user.id.attribute**: LDAP attribute for username lookup (typically ``uid``)
- **attribute.mapping**: Maps LDAP attributes to LibreBooking user fields
- **sync.groups**: Enable group membership synchronization
- **database.auth.when.ldap.user.not.found**: Fallback to database authentication

.. important::

   The ``uri`` value must include the LDAP scheme, for example
   ``ldap://ldap.example.com`` or ``ldaps://ldap.example.com``.
   A bare hostname such as ``ldap.example.com`` is not enough.

URI examples:

.. code-block:: php

   // single LDAP server (unencrypted LDAP, explicit port)
   'uri' => 'ldap://ldap1.example.com:389',

   // single LDAP server (unencrypted LDAP, default port 389)
   'uri' => 'ldap://ldap1.example.com',

   // single LDAP server over LDAPS (TLS, explicit port)
   'uri' => 'ldaps://ldap1.example.com:636',

   // single LDAP server over LDAPS (TLS, default port 636)
   'uri' => 'ldaps://ldap1.example.com',

   // multiple LDAP servers (space-separated URIs in one string)
   'uri' => 'ldap://ldap1.example.com:389 ldap://ldap2.example.com:389',

   // multiple LDAPS servers
   'uri' => 'ldaps://ldap1.example.com:636 ldaps://ldap2.example.com:636',

Port defaults:

- ``ldap://`` uses port ``389`` by default when no port is specified.
- ``ldaps://`` uses port ``636`` by default when no port is specified.

Breaking change:

- ``host`` and ``port`` are no longer supported.
- Configure LDAP endpoints only through ``uri``.

Alternatively, configure the plugin through the web admin interface at
**Application Configuration** (``/Web/admin/manage_configuration.php``) and
select **Authentication-Ldap**. Refer to
``/plugins/Authentication/Ldap/Ldap.config.dist.php`` for complete
documentation of all options.

Troubleshooting
---------------

Enable Debug Logging
~~~~~~~~~~~~~~~~~~~~

Set ``debug.enabled`` to ``true`` to see detailed LDAP operations in the LibreBooking logs:

.. code-block:: php

   'debug.enabled' => true,


Common Issues
~~~~~~~~~~~~~

**Connection failures**
  - Verify LDAP URI hostname and port accessibility
  - Check firewall rules
  - Test with ``telnet ldap.example.com 389``

**Authentication failures**
  - Verify binddn credentials are correct
  - Check basedn matches your directory structure
  - Ensure user.id.attribute is correct (``uid`` vs ``cn``)
  - Review filter configuration

**Groups not syncing**
  - Verify ``sync.groups`` is ``true``
  - Check that users have ``memberof`` attribute populated
  - Some OpenLDAP configurations require the memberof overlay
  - Ensure service account can read group memberships

Migration from Database Auth
-----------------------------

To migrate existing users:

1. Keep ``database.auth.when.ldap.user.not.found`` set to ``true``
2. Ensure LibreBooking usernames match LDAP usernames
3. Users automatically switch to LDAP auth on next login
4. Existing reservations and data are preserved

Users are matched by username - if a LibreBooking account exists with the same
username, it will be updated with LDAP information.
