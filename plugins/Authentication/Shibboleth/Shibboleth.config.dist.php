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
        'shibboleth' => [
            // the key of the external user's identity. mandatory.
            'username' => 'REMOTE_USER',

            // the key of the external user's email address. mandatory.
            'email' => 'mail',

            // the key of the external user's first name. optional.
            'firstname' => 'givenName',

            // the key of the external user's last name. optional.
            'lastname' => 'sn',

            // the key of the external user's phone number. optional.
            'phone' => 'telephone',

            // the key of the external user's organization. optional.
            'organization' => 'ou',
        ],
    ],
];
