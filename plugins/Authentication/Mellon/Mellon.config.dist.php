<?php

return [
    'settings' => [
        'mellon' => [
            // The email domain to append after the REMOTE_USER variable.
            'email.domain' => 'yourdomain.com',
            'key.given.name' => 'MELLON_givenname',
            'key.surname' => 'MELLON_surname',

            // The Group field that has been provided by Mellon.  Must be one line.  (Remember to set MellonMergeEnvVars to On)
            'key.groups' => 'ADFS_GROUP',

            // Group mappings, bookedAttribute=mellonAttribute.  Separate different mappings by semicolon (;).
            'group.mappings' => 'Application Administrators=SomeMellonGroup',
        ],
    ],
];
