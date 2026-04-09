<?php

// see http://pear.php.net/manual/en/package.networking.net-ldap2.connecting.php
//     https://www.php.net/manual/en/function.ldap-connect.php

return [
    'settings' => [
        'ldap' => [
            // LDAP URI(s). For multiple servers, separate each URI with spaces.
            // If no port is specified, defaults are: ldap:// = 389, ldaps:// = 636.
            // examples:
            //   'ldap://ldap1.example.com'
            //   'ldap://ldap1.example.com:389'
            //   'ldaps://ldap1.example.com'
            //   'ldaps://ldap1.example.com:636'
            //   'ldap://ldap1.example.com:389 ldap://ldap2.example.com:389'
            //   'ldaps://ldap1.example.com:636 ldaps://ldap2.example.com:636'
            'uri' => 'ldap://localhost:389',

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
