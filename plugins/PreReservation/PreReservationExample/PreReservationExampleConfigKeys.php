<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class PreReservationExampleConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'PreReservationExample';

    public const CUSTOM_ATTRIBUTE_MAX_VALUE = [
        'key' => 'custom.attribute.max.value',
        'type' => 'integer',
        'default' => 100,
        'label' => 'Custom Attribute Maximum Value',
        'description' => 'Maximum custom attribute value allowed by the pre-reservation example plugin',
        'section' => 'prereservationexample',
    ];

    public const CUSTOM_ATTRIBUTE_ID = [
        'key' => 'custom.attribute.id',
        'type' => 'integer',
        'default' => 3,
        'label' => 'Custom Attribute ID',
        'description' => 'Custom attribute ID validated by the pre-reservation example plugin',
        'section' => 'prereservationexample',
    ];
}
