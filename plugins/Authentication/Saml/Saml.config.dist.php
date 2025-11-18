<?php


// File in Authentication plugin package for ver 2.1.4 LibreBooking
// to implement Single Sign On Capability.  Based on code from the
// LibreBooking Authentication Ldap plugin as well as a SAML
// Authentication plugin for Moodle 1.9+.
// See http://moodle.org/mod/data/view.php?d=13&rid=2574
// This plugin uses the SimpleSAMLPHP version 1.8.2 libraries.
// http://simplesamlphp.org/


return [
    'settings' => [
        'saml' => [
            // path to SimpleSAMLphp Service Provider(SP) base directory
            // the SP should be installed on the same server as LibreBooking
            'simplesamlphp.lib' => '/var/simplesamlphp',

            // path to SimpleSAML SP configuration directory
            'simplesamlphp.config' => '/var/simplesamlphp/config',

            // name of the SimpleSAML authentication source configured
            // in SimpleSAML SP /var/simplesamlphp/config/authsources.php
            'simplesamlphp.sp' => 'default-sp',

            // SAML attribute names found in SimpleSAMLphp Identify Provider (Idp)
            // configuration /var/simplesamlphp/config/authsources.php
            // The Idp will most likely be installed on another server

            // SAML attribute that is mapped to LibreBooking username
            'simplesamlphp.username' => 'sAMAccountName',

            // SAML attribute that is mapped to LibreBooking firstname
            'simplesamlphp.firstname' => 'givenName',

            // SAML attribute that is mapped to LibreBooking lastname
            'simplesamlphp.lastname' => 'sn',

            // SAML attribute that is mapped to LibreBooking email
            'simplesamlphp.email' => 'mail',

            // SAML attribute that is mapped to LibreBooking phone
            'simplesamlphp.phone' => 'telephoneNumber',

            // SAML attribute that is mapped to LibreBooking organization
            'simplesamlphp.organization' => 'department',

            // SAML attribute that is mapped to LibreBooking position
            'simplesamlphp.position' => 'title',

            // SAML attribute that is mapped to LibreBooking groups
            'simplesamlphp.groups' => 'groups',

            // Whether or not groups should be synced into LibreBooking
            'simplesamlphp.sync.groups' => false,
        ],
    ],
];
