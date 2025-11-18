<?php
/**
 * @file ShibbolethConfigKeys.php
 */

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class ShibbolethConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'shibboleth';

    public const USERNAME = [
        'key' => 'username',
        'type' => 'string',
        'default' => 'REMOTE_USER',
        'label' => 'Username Attribute',
        'description' => 'The key of the external user\'s identity',
        'section' => 'shibboleth'
    ];

    public const FIRSTNAME = [
        'key' => 'firstname',
        'type' => 'string',
        'default' => 'givenName',
        'label' => 'First Name Attribute',
        'description' => 'The key of the external user\'s first name',
        'section' => 'shibboleth'
    ];

    public const LASTNAME = [
        'key' => 'lastname',
        'type' => 'string',
        'default' => 'sn',
        'label' => 'Last Name Attribute',
        'description' => 'The key of the external user\'s last name',
        'section' => 'shibboleth'
    ];

    public const EMAIL = [
        'key' => 'email',
        'type' => 'string',
        'default' => 'mail',
        'label' => 'Email Attribute',
        'description' => 'The key of the external user\'s email address',
        'section' => 'shibboleth'
    ];

    public const PHONE = [
        'key' => 'phone',
        'type' => 'string',
        'default' => 'telephone',
        'label' => 'Phone Attribute',
        'description' => 'The key of the external user\'s phone number',
        'section' => 'shibboleth'
    ];

    public const ORGANIZATION = [
        'key' => 'organization',
        'type' => 'string',
        'default' => 'ou',
        'label' => 'Organization Attribute',
        'description' => 'The key of the external user\'s organization',
        'section' => 'shibboleth'
    ];

    // Additional attributes can be added here
    public const GROUPS = [
        'key' => 'groups',
        'type' => 'string',
        'default' => 'groups',
        'label' => 'Groups Attribute',
        'description' => 'The key of the external user\'s groups',
        'section' => 'shibboleth'
    ];

    public const SYNC_GROUPS = [
        'key' => 'sync.groups',
        'type' => 'boolean',
        'default' => false,
        'label' => 'Sync Groups',
        'description' => 'Whether or not groups should be synchronized into LibreBooking',
        'section' => 'shibboleth'
    ];
}
