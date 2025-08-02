<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class SamlConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'saml';

    public const SIMPLESAMLPHP_LIB = [
        'key' => 'simplesamlphp.lib',
        'type' => 'string',
        'default' => '/var/simplesamlphp',
        'label' => 'SimpleSAMLphp Lib Path',
        'description' => 'Path to SimpleSAMLphp Service Provider base directory',
        'section' => 'saml'
    ];

    public const SIMPLESAMLPHP_CONFIG = [
        'key' => 'simplesamlphp.config',
        'type' => 'string',
        'default' => '/var/simplesamlphp/config',
        'label' => 'SimpleSAMLphp Config Path',
        'description' => 'Path to SimpleSAML SP configuration directory',
        'section' => 'saml'
    ];

    public const SIMPLESAMLPHP_SP = [
        'key' => 'simplesamlphp.sp',
        'type' => 'string',
        'default' => 'default-sp',
        'label' => 'SimpleSAMLphp SP Name',
        'description' => 'Name of the SimpleSAML authentication source configured',
        'section' => 'saml'
    ];

    public const USERNAME = [
        'key' => 'simplesamlphp.username',
        'type' => 'string',
        'default' => 'sAMAccountName',
        'label' => 'Username Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking username',
        'section' => 'saml'
    ];

    public const FIRSTNAME = [
        'key' => 'simplesamlphp.firstname',
        'type' => 'string',
        'default' => 'givenName',
        'label' => 'First Name Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking firstname',
        'section' => 'saml'
    ];

    public const LASTNAME = [
        'key' => 'simplesamlphp.lastname',
        'type' => 'string',
        'default' => 'sn',
        'label' => 'Last Name Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking lastname',
        'section' => 'saml'
    ];

    public const EMAIL = [
        'key' => 'simplesamlphp.email',
        'type' => 'string',
        'default' => 'mail',
        'label' => 'Email Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking email',
        'section' => 'saml'
    ];

    public const PHONE = [
        'key' => 'simplesamlphp.phone',
        'type' => 'string',
        'default' => 'telephoneNumber',
        'label' => 'Phone Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking phone',
        'section' => 'saml'
    ];

    public const ORGANIZATION = [
        'key' => 'simplesamlphp.organization',
        'type' => 'string',
        'default' => 'department',
        'label' => 'Organization Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking organization',
        'section' => 'saml'
    ];

    public const POSITION = [
        'key' => 'simplesamlphp.position',
        'type' => 'string',
        'default' => 'title',
        'label' => 'Position Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking position',
        'section' => 'saml'
    ];

    public const GROUPS = [
        'key' => 'simplesamlphp.groups',
        'type' => 'string',
        'default' => 'groups',
        'label' => 'Groups Attribute',
        'description' => 'SAML attribute that is mapped to LibreBooking groups',
        'section' => 'saml'
    ];

    public const RETURN_TO = [
        'key' => 'simplesamlphp.return.to',
        'type' => 'string',
        'default' => '',
        'label' => 'Return URL',
        'description' => 'URL to return to after SAML authentication',
        'section' => 'saml'
    ];

    public const SYNC_GROUPS = [
        'key' => 'simplesamlphp.sync.groups',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Sync Groups',
        'description' => 'Whether or not groups should be synced into LibreBooking',
        'section' => 'saml'
    ];
}
