<?php

require_once(ROOT_DIR . 'lib/Config/PluginConfigKeys.php');

class DrupalConfigKeys extends PluginConfigKeys
{
    public const CONFIG_ID = 'Drupal';

    public const DRUPAL_ROOT_DIR = [
        'key' => 'root.dir',
        'type' => 'string',
        'default' => 'S1',
        'label' => 'Drupal Root Directory',
        'description' => 'Root directory of the Drupal installation',
        'section' => 'drupal',
    ];

    public const DRUPAL_ALLOWED_ROLES = [
        'key' => 'allowed_roles',
        'type' => 'string',
        'default' => 'comma,separated,roles',
        'label' => 'Drupal Allowed Roles',
        'description' => 'Roles allowed to access the Drupal installation',
        'section' => 'drupal'
    ];
}
