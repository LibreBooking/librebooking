<?php
/**
 * @file Shibboleth.config.dist.php
 *
 * Plugin configuration template.
 *
 * Usage:
 * Copy this file to <code>Shibboleth.config.php</code> and adjust values as applicable.
 */

return [
    'settings' => [
        // the key of the external user's identity. mandatory.
        'shibboleth.username' => 'REMOTE_USER',

        // the key of the external user's email address. mandatory.
        'shibboleth.email' => 'mail',

        // the key of the external user's first name. optional.
        'shibboleth.firstname' => 'givenName',

        // the key of the external user's last name. optional.
        'shibboleth.lastname' => 'sn',

        // the key of the external user's phone number. optional.
        'shibboleth.phone' => 'telephone',

        // the key of the external user's organization. optional.
        'shibboleth.organization' => 'ou',
    ],
];
