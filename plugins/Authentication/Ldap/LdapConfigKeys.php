<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class LdapConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'ldap';

    public const HOST = [
        'key' => 'host',
        'type' => 'string',
        'default' => '',
        'label' => 'LDAP Host',
        'description' => 'Hostname or IP address of LDAP server. Should start with ldap:// or ldaps://',
        'section' => 'ldap'
    ];

    public const PORT = [
        'key' => 'port',
        'type' => 'integer',
        'default' => 389,
        'label' => 'LDAP Port',
        'description' => 'Port of LDAP server (usually 389 or 636 for SSL)',
        'section' => 'ldap'
    ];

    public const VERSION = [
        'key' => 'version',
        'type' => 'integer',
        'default' => 3,
        'label' => 'LDAP Version',
        'description' => 'LDAP protocol version (usually 3)',
        'section' => 'ldap'
    ];

    public const STARTTLS = [
        'key' => 'starttls',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Use StartTLS',
        'description' => 'Whether to use StartTLS encryption',
        'section' => 'ldap'
    ];

    public const BINDDN = [
        'key' => 'binddn',
        'type' => 'string',
        'default' => '',
        'label' => 'Bind DN',
        'description' => 'DN to bind to LDAP server',
        'section' => 'ldap'
    ];

    public const BINDPW = [
        'key' => 'bindpw',
        'type' => 'string',
        'default' => '',
        'label' => 'Bind Password',
        'description' => 'Password to bind to LDAP server',
        'section' => 'ldap',
        'is_protected' => true
    ];

    public const BASEDN = [
        'key' => 'basedn',
        'type' => 'string',
        'default' => '',
        'label' => 'Base DN',
        'description' => 'Base DN for LDAP searches',
        'section' => 'ldap'
    ];

    public const FILTER = [
        'key' => 'filter',
        'type' => 'string',
        'default' => '',
        'label' => 'Search Filter',
        'description' => 'Optional LDAP search filter for additional constraints (leave empty for default)',
        'section' => 'ldap'
    ];

    public const SCOPE = [
        'key' => 'scope',
        'type' => 'string',
        'default' => 'sub',
        'label' => 'Search Scope',
        'description' => 'LDAP search scope (base, one, or sub)',
        'section' => 'ldap'
    ];

    public const RETRY_AGAINST_DATABASE = [
        'key' => 'database.auth.when.ldap.user.not.found',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Retry Against Database',
        'description' => 'Try to authenticate against the database if LDAP authentication fails',
        'section' => 'ldap'
    ];

    public const ATTRIBUTE_MAPPING = [
        'key' => 'attribute.mapping',
        'type' => 'string',
        'default' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',
        'label' => 'Attribute Mapping',
        'description' => 'Mapping of LibreBooking attributes to LDAP attributes',
        'section' => 'ldap'
    ];

    public const USER_ID_ATTRIBUTE = [
        'key' => 'user.id.attribute',
        'type' => 'string',
        'default' => 'uid',
        'label' => 'User ID Attribute',
        'description' => 'LDAP attribute to use as user ID',
        'section' => 'ldap'
    ];

    public const REQUIRED_GROUP = [
        'key' => 'required.group',
        'type' => 'string',
        'default' => '',
        'label' => 'Required Group',
        'description' => 'LDAP group required for authentication',
        'section' => 'ldap'
    ];

    public const SYNC_GROUPS = [
        'key' => 'sync.groups',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Sync Groups',
        'description' => 'Synchronize LDAP groups with LibreBooking groups',
        'section' => 'ldap'
    ];

    public const PREVENT_CLEAN_USERNAME = [
        'key' => 'prevent.clean.username',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Prevent Clean Username',
        'description' => 'Do not clean username from domain or email format',
        'section' => 'ldap'
    ];

    // Adding the debug setting that's referenced in LdapOptions::IsLdapDebugOn()
    public const DEBUG_ENABLED = [
        'key' => 'debug.enabled',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable LDAP Debug',
        'description' => 'Enable debugging for LDAP authentication',
        'section' => 'ldap'
    ];
}
