Configuration Overview
======================

LibreBooking configuration has been split into two guides for better usability:

Basic Configuration
-------------------

For new installations and common settings, see :doc:`BASIC-CONFIGURATION`.

This covers:
- Essential settings to get started
- Database configuration
- Email setup
- User registration
- Basic security
- Common troubleshooting

Advanced Configuration
----------------------

For detailed configuration and advanced features, see :doc:`ADVANCED-CONFIGURATION`.

This covers:
- All configuration options in detail
- Advanced email and logging settings
- Schedule and reservation behavior
- Security headers and authentication providers
- API configuration
- Plugin system
- Performance tuning

Configuration File Format
-------------------------

LibreBooking uses a PHP array-based configuration format. The file returns a
settings array with both flat dot notation and nested arrays:

.. code-block:: php

   <?php
   return [
       'settings' => [
           // Flat keys
           'app.title' => 'LibreBooking',
           'admin.email' => 'admin@example.com',

           // Nested arrays
           'database' => [
               'type' => 'mysql',
               'hostspec' => '127.0.0.1',
               'name' => 'librebooking',
               'user' => 'lb_user',
               'password' => 'password',
           ],

           'email' => [
               'enabled' => true,
               'default.from.address' => 'noreply@example.com',
           ],
       ]
   ];

Quick Start
-----------

1. Copy ``/config/config.dist.php`` to ``/config/config.php``
2. Follow :doc:`BASIC-CONFIGURATION` for essential settings
3. Run the installation wizard at ``/Web/install/``
4. Configure advanced features using :doc:`ADVANCED-CONFIGURATION` as needed

Migration from Old Format
-------------------------

If upgrading from an older version that used ``$conf['settings'][...]`` format,
you'll need to convert your configuration to the new array return format. The
new format is more modern and provides better IDE support and validation.

LibreBooking includes a migration script for this purpose:

.. code-block:: bash

   php scripts/migrate-config.php config/config.php

By default, this writes a sibling file named ``config/config.migrated.php``.
You can also provide an explicit output path:

.. code-block:: bash

   php scripts/migrate-config.php config/config.php /tmp/config.new.php

The migration script uses the current config parser and legacy key mappings to:

- convert legacy ``$conf['settings'][...]`` files into the modern ``return [...]`` format
- rewrite known legacy keys to their canonical modern names
- preserve values using the canonical nested section structure expected by
  ``config/config.dist.php``
- keep keys that are present in your config but not in
  ``config/config.dist.php``, while warning about them

During migration you may see messages such as:

- ``Legacy config format detected``: the input file is using the old ``$conf`` style
- ``Deprecated config key ... maps to ...``: a recognized legacy key was
  rewritten to its modern equivalent
- ``Unknown config key ...``: the parser does not recognize that key and it
  should be reviewed manually
- ``Warning: preserving key not present in config.dist.php``: the key is being
  kept, but it is not part of the current template

After reviewing the generated file, replace ``config/config.php`` with the
migrated file when you are satisfied with the result.
