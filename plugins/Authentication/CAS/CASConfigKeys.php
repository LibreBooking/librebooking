<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class CASConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'CAS';

    public const CAS_VERSION = [
        'key' => 'version',
        'type' => 'string',
        'default' => 'S1',
        'label' => 'CAS Version',
        'description' => 'Version of the CAS protocol to use',
        'section' => 'cas',
        'choices' => [
            '1.0' => 'CAS_VERSION_1_0',
            '2.0' => 'CAS_VERSION_2_0',
            'S1' => 'SAML_VERSION_1_1'
        ]
    ];

    public const CAS_SERVER_HOSTNAME = [
        'key' => 'server.hostname',
        'type' => 'string',
        'default' => 'localhost',
        'label' => 'CAS Server Hostname',
        'description' => 'Hostname of the CAS server',
        'section' => 'cas'
    ];

    public const CAS_PORT = [
        'key' => 'port',
        'type' => 'integer',
        'default' => 443,
        'label' => 'CAS Server Port',
        'description' => 'Port of the CAS server',
        'section' => 'cas'
    ];

    public const CAS_SERVER_URI = [
        'key' => 'server.uri',
        'type' => 'string',
        'default' => '',
        'label' => 'CAS Server URI',
        'description' => 'URI of the CAS server',
        'section' => 'cas'
    ];

    public const CAS_CHANGESESSIONID = [
        'key' => 'change.session.id',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Change Session ID',
        'description' => 'Change PHP session ID during CAS authentication',
        'section' => 'cas'
    ];

    public const CAS_LOGOUT_SERVERS = [
        'key' => 'logout.servers',
        'type' => 'string',
        'default' => '',
        'label' => 'CAS Logout Servers',
        'description' => 'Comma-separated list of servers to notify on logout',
        'section' => 'cas'
    ];

    public const CAS_CERTIFICATES = [
        'key' => 'certificates',
        'type' => 'string',
        'default' => '',
        'label' => 'CAS Certificate',
        'description' => 'Path to the CAS server certificates',
        'section' => 'cas'
    ];

    public const CAS_DEBUG_ENABLED = [
        'key' => 'debug.enabled',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Enable CAS Debug',
        'description' => 'Enable debugging for CAS authentication',
        'section' => 'cas'
    ];

    public const DEBUG_FILE = [
        'key' => 'debug.file',
        'type' => 'string',
        'default' => '/tmp/cas.log',
        'label' => 'Debug File',
        'description' => 'Path to the CAS debug log file',
        'section' => 'cas'
    ];

    public const EMAIL_SUFFIX = [
        'key' => 'email.suffix',
        'type' => 'string',
        'default' => '@yourdomain.com',
        'label' => 'Email Suffix',
        'description' => 'Suffix to append to username for email address',
        'section' => 'cas'
    ];

    public const ATTRIBUTE_MAPPING = [
        'key' => 'attribute.mapping',
        'type' => 'string',
        'default' => 'givenName=givenName,surName=surname,email=mail,groups=Role',
        'label' => 'Attribute Mapping',
        'description' => 'Mapping of required attributes to CAS attributes',
        'section' => 'cas'
    ];
}
