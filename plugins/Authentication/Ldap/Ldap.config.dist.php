<?php

// see http://pear.php.net/manual/en/package.networking.net-ldap2.connecting.php
//     https://www.php.net/manual/en/function.ldap-connect.php

return [
    'settings' => [
        'ldap' => [
            // comma separated list of ldap servers such as ldap://mydomain1,ldap://localhost
            'host' => 'ldap://localhost',

            // default ldap port 389 or 636 for ssl.
            'port' => 389,

            // LDAP protocol version
            'version' => 3,

            // TLS is started after connecting
            'starttls' => false,

            // The distinguished name to bind as (username). If you don't supply this, an anonymous bind will be established.
            'binddn' => '',

            // Password for the binddn. If the credentials are wrong, the bind will fail server-side and an anonymous bind will be established instead. An empty bindpw string requests an unauthenticated bind.
            'bindpw' => '',

            // LDAP base name (eg. dc=example,dc=com)
            'basedn' => '',

            // Default search filter
            'filter' => '',

            // Search scope (eg. uid)
            'scope' => '',

            // Required group (empty if not necessary) (eg. cn=MyGroup,cn=Groups,dc=example,dc=com)
            'required.group' => '',

            // if ldap auth fails, authenticate against LibreBooking database
            'database.auth.when.ldap.user.not.found' => false,

            // if LDAP2 should use debug logging
            'debug.enabled' => false,

            // mapping of required attributes to attribute names in your directory
            'attribute.mapping' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',

            // the attribute name for user identification
            'user.id.attribute' => 'uid',

            // Whether or not groups should be synced into LibreBooking
            'sync.groups' => false,

            // If the username is an email address or contains the domain, clean it
            'prevent.clean.username' => false,
        ],
    ],
];
