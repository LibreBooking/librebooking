<?php
require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class MellonConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'Mellon';

    public const KEY_GIVEN_NAME = [
        'key' => 'key.given.name',
        'type' => 'string',
        'default' => 'MELLON_givenName',
        'label' => 'Given Name Attribute',
        'description' => 'The Mellon attribute that contains the user\'s given name',
        'section' => 'mellon'
    ];

    public const KEY_SURNAME = [
        'key' => 'key.surname',
        'type' => 'string',
        'default' => 'MELLON_surname',
        'label' => 'Surname Attribute',
        'description' => 'The Mellon attribute that contains the user\'s surname',
        'section' => 'mellon'
    ];

    public const KEY_GROUPS = [
        'key' => 'key.groups',
        'type' => 'string',
        'default' => 'ADFS_GROUP',
        'label' => 'Groups Attribute',
        'description' => 'The Group field that has been provided by Mellon.  Must be one line.  (Remember to set MellonMergeEnvVars to On)',
        'section' => 'mellon'
    ];

    public const GROUP_MAPPINGS = [
        'key' => 'group.mappings',
        'type' => 'string',
        'default' => 'Application Administrators=SomeMellonGroup',
        'label' => 'Group Mappings',
        'description' => 'CGroup mappings, bookedAttribute=mellonAttribute.  Separate different mappings by semicolon (;).',
        'section' => 'mellon'
    ];

    public const EMAIL_DOMAIN = [
        'key' => 'email.domain',
        'type' => 'string',
        'default' => '',
        'label' => 'Email Domain',
        'description' => 'The email domain to append after the REMOTE_USER variable',
        'section' => 'mellon'
    ];
}
