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
- Maintenance mode
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

Language String Overrides
-------------------------

LibreBooking supports installation-specific string overrides through
``config/lang-overrides.php``. If this file exists, its string values are
merged into the active language during resource initialization after the
standard language file has been loaded.

This is useful when you want to change a few labels or messages for one
installation without modifying the files in ``lang/``.

Global Overrides
^^^^^^^^^^^^^^^^

The ``$langOverrides`` array applies to all languages:

.. code-block:: php

   <?php

   $langOverrides = [
       'LogIn' => 'Sign in to Room Booking',
       'Register' => 'Request an account',
   ];

Per-Language Overrides
^^^^^^^^^^^^^^^^^^^^^^

The ``$langOverridesByLanguage`` array allows overrides scoped to specific
languages. Use the language code (e.g. ``en_us``, ``fr_fr``) as the key:

.. code-block:: php

   <?php

   $langOverridesByLanguage = [
       'fr_fr' => [
           'LogIn' => 'Se connecter à la réservation',
           'Register' => 'Demander un compte',
       ],
       'fi_fi' => [
           'LogIn' => 'Kirjaudu huonevaraukseen',
           'Register' => 'Pyydä tiliä',
       ],
       'de_de' => [
           'LogIn' => 'Anmeldung zur Raumbuchung',
       ],
   ];

Both arrays can be used in the same file. Per-language overrides are applied
after global overrides, so they take precedence when both define the same key.

Notes:

- The file path must be ``config/lang-overrides.php``
- Override keys must match the existing translation string keys used by the application.
  They must be identical in case. For example: ``Login`` will not match ``LogIn``
  (one has an upper case ``I``)
- This only overrides string entries; it does not override date, day, or month definitions
