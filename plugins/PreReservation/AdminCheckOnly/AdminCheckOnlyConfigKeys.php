<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class AdminCheckOnlyConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'AdminCheckOnly';

    public const ATTRIBUTE_CHECKIN_ID = [
        'key' => 'attribute.checkin.id',
        'type' => 'integer',
        'default' => 5,
        'label' => 'Check-In Attribute ID',
        'description' => 'Custom attribute ID used to require administrator check-in',
        'section' => 'admincheckonly',
    ];

    public const ATTRIBUTE_CHECKOUT_ID = [
        'key' => 'attribute.checkout.id',
        'type' => 'integer',
        'default' => 6,
        'label' => 'Check-Out Attribute ID',
        'description' => 'Custom attribute ID used to require administrator check-out',
        'section' => 'admincheckonly',
    ];

    public const MESSAGE_CHECKIN = [
        'key' => 'message.checkin',
        'type' => 'string',
        'default' => 'Only Admin has permission to Check In on these resource(s).',
        'label' => 'Check-In Message',
        'description' => 'Message shown when only an administrator can check in',
        'section' => 'admincheckonly',
    ];

    public const MESSAGE_CHECKOUT = [
        'key' => 'message.checkout',
        'type' => 'string',
        'default' => 'Only Admin has permission to Check Out on these resource(s).',
        'label' => 'Check-Out Message',
        'description' => 'Message shown when only an administrator can check out',
        'section' => 'admincheckonly',
    ];

    public const MESSAGE_CHECKIN_RESOURCE_CONFLICT = [
        'key' => 'message.checkin.resource.conflict',
        'type' => 'string',
        'default' => 'There is at least one resource in this reservation that needs to be Checked In by Admin.',
        'label' => 'Check-In Resource Conflict Message',
        'description' => 'Message shown when at least one resource requires administrator check-in',
        'section' => 'admincheckonly',
    ];

    public const MESSAGE_CHECKOUT_RESOURCE_CONFLICT = [
        'key' => 'message.checkout.resource.conflict',
        'type' => 'string',
        'default' => 'There is at least one resource in this reservation that needs to be Checked Out by Admin.',
        'label' => 'Check-Out Resource Conflict Message',
        'description' => 'Message shown when at least one resource requires administrator check-out',
        'section' => 'admincheckonly',
    ];
}
