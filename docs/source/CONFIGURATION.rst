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

LibreBooking uses a PHP array-based configuration format. The file returns a settings array with both flat dot notation and nested arrays:

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

If upgrading from an older version that used ``$conf['settings'][...]`` format, you'll need to convert your configuration to the new array return format. The new format is more modern and provides better IDE support and validation.
