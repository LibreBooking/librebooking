<?php

return [
    'settings' => [
        'cas' => [
            // '1.0' = CAS_VERSION_1_0, '2.0' = CAS_VERSION_2_0, 'S1' = SAML_VERSION_1_1
            'version' => 'S1',

            // the hostname of the CAS server
            'server.hostname' => 'localhost',

            // the port the CAS server is running on
            'port' => 443,

            // the URI the CAS server is responding on
            'server.uri' => '',

            // Allow phpCAS to change the session_id
            'change.session.id' => false,

            // Email suffix to use when storing CAS user account. IE, email addresses will be saved to LibreBooking as username@yourdomain.com
            'email.suffix' => '@yourdomain.com',

            // Comma separated list of servers to use for logout. Leave blank to not use cas logout servers
            'logout.servers' => '',

            // Path to certificate to use for CAS. Leave blank if no certificate should be used
            'certificates' => '',

            // bookedAttribute=CASAttribute
            'attribute.mapping' => 'givenName=givenName,surName=surname,email=mail,groups=Role',

            'debug.enabled' => false,
            'debug.file' => '/tmp/phpcas.log',
        ],
    ],
];
