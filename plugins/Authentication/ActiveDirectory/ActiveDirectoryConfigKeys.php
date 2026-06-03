<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class ActiveDirectoryConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'activeDirectory';
    public const DOMAIN_CONTROLLERS = [
        'key' => 'domain.controllers',
        'type' => 'string',
        'default' => '',
        'label' => 'Domain Controllers',
        'description' => 'Comma separated list of domain controllers',
        'section' => 'activedirectory'
    ];

    public const PORT = [
        'key' => 'port',
        'type' => 'integer',
        'default' => 389,
        'label' => 'LDAP Port',
        'description' => 'Default LDAP port (389 or 636 for SSL)',
        'section' => 'activedirectory'
    ];

    public const USERNAME = [
        'key' => 'username',
        'type' => 'string',
        'default' => '',
        'label' => 'Admin Username',
        'description' => 'Username for binding to LDAP service. Will have account.suffix appended automatically',
        'section' => 'activedirectory'
    ];

    public const PASSWORD = [
        'key' => 'password',
        'type' => 'string',
        'default' => '',
        'label' => 'Admin Password',
        'description' => 'Password for binding to LDAP service',
        'section' => 'activedirectory',
        'is_protected' => true
    ];

    public const BASEDN = [
        'key' => 'basedn',
        'type' => 'string',
        'default' => '',
        'label' => 'Base DN',
        'description' => 'Base DN for your domain',
        'section' => 'activedirectory'
    ];

    public const USE_SSL = [
        'key' => 'use.ssl',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Use SSL',
        'description' => 'Connect using SSL (LDAPS)',
        'section' => 'activedirectory'
    ];

    public const VERSION = [
        'key' => 'version',
        'type' => 'integer',
        'default' => 3,
        'label' => 'LDAP Protocol Version',
        'description' => 'LDAP protocol version (usually 3)',
        'section' => 'activedirectory'
    ];

    public const ACCOUNT_SUFFIX = [
        'key' => 'account.suffix',
        'type' => 'string',
        'default' => '',
        'label' => 'Account Suffix',
        'description' => 'Account suffix for your domain (e.g. @domain.com)',
        'section' => 'activedirectory'
    ];

    public const SECTION_AD = [
        'key' => 'ad',
        'type' => 'string',
        'default' => '',
        'label' => 'Active Directory Section',
        'description' => 'Configuration section for Active Directory settings',
        'section' => 'activedirectory',
        'is_hidden' => true
    ];
    public const RETRY_AGAINST_DATABASE = [
        'key' => 'database.auth.when.ldap.user.not.found',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Retry Against Database',
        'description' => 'Retry authentication against database if LDAP authentication fails',
        'section' => 'activedirectory'
    ];

    public const ATTRIBUTE_MAPPING = [
        'key' => 'attribute.mapping',
        'type' => 'string',
        'default' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',
        'label' => 'Attribute Mapping',
        'description' => 'Mapping of required attributes to LDAP attributes',
        'section' => 'activedirectory'
    ];

    public const REQUIRED_GROUPS = [
        'key' => 'required.groups',
        'type' => 'string',
        'default' => '',
        'label' => 'Required Groups',
        'description' => 'Groups that users must belong to (comma separated)',
        'section' => 'activedirectory'
    ];

    public const SYNC_GROUPS = [
        'key' => 'sync.groups',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Sync Groups',
        'description' => 'Synchronize AD groups with LibreBooking',
        'section' => 'activedirectory'
    ];

    public const USE_SSO = [
        'key' => 'use.sso',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Use SSO',
        'description' => 'Use single sign-on with AD',
        'section' => 'activedirectory'
    ];

    public const PREVENT_CLEAN_USERNAME = [
        'key' => 'prevent.clean.username',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Prevent Clean Username',
        'description' => 'Do not clean username from domain or email format',
        'section' => 'activedirectory'
    ];
}
