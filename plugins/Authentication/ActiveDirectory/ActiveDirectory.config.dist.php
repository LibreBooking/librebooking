<?php

return [
    'settings' => [
        'activedirectory' => [
            // comma separated list of ldap servers such as domaincontroller1,controller2
            'domain.controllers' => 'mydomain,local',

            // default ldap port 389 or 636 for ssl.
            'port' => 389,

            // admin user - bind to ldap service with an authorized account user/password
            'username' => '',

            // admin password - corresponding password
            'password' => '',

            // The base dn for your domain. This is generally the same as your account suffix, but broken up and prefixed with DC=. Your base dn can be located in the extended attributes in Active Directory Users and Computers MMC.
            'basedn' => 'ou=uidauthent,o=domain.com',

            // LDAP protocol version
            'version' => 3,

            // 'true' if 636 was used.
            'use.ssl' => 'false',

            // The full account suffix for your domain. Example: @uidauthent.domain.com.
            'account.suffix' => '',

            // if ldap auth fails, authenticate against LibreBooking database
            'database.auth.when.ldap.user.not.found' => false,

            // mapping of required attributes to attribute names in your directory
            'attribute.mapping' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',

            // Required groups (empty if not necessary) User only needs to belong to at least one listed (eg. Group1,Group2)
            'required.groups' => '',

            // Whether or not groups should be synced into LibreBooking. When true then be sure that the attribute.mapping config value contains a correct map for groups
            'sync.groups' => false,

            // Whether or not to use single sign on
            'use.sso' => false,

            // If the username is an email address or contains the domain, clean it
            'prevent.clean.username' => false,
        ],
    ],
];
