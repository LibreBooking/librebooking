<?php

return [
    'settings' => [
        // '1.0' = CAS_VERSION_1_0, '2.0' = CAS_VERSION_2_0, 'S1' = SAML_VERSION_1_1
        'cas.version' => 'S1',

        // the hostname of the CAS server
        'cas.server.hostname' => 'localhost',

        // the port the CAS server is running on
        'cas.port' => 443,

        // the URI the CAS server is responding on
        'cas.server.uri' => '',

        // Allow phpCAS to change the session_id
        'cas.change.session.id' => false,

        // Email suffix to use when storing CAS user account. IE, email addresses will be saved to LibreBooking as username@yourdomain.com
        'email.suffix' => '@yourdomain.com',

        // Comma separated list of servers to use for logout. Leave blank to not use cas logout servers
        'cas.logout.servers' => '',

        // Path to certificate to use for CAS. Leave blank if no certificate should be used
        'cas.certificates' => '',

        // bookedAttribute=CASAttribute
        'cas.attribute.mapping' => 'givenName=givenName,surName=surname,email=mail,groups=Role',

        'cas.debug.enabled' => false,
        'cas.debug.file' => '/tmp/phpcas.log',
    ],
];
