<?php

define('ROOT_DIR', '../../../');

require_once(ROOT_DIR . 'lib/Common/namespace.php');

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}

try {
    @session_start();

    $builder = new Gregwar\Captcha\CaptchaBuilder();
    $builder->build(280, 100);
    header('Content-type: image/jpeg');
    $builder->output();
    $_SESSION['phrase'] = $builder->getPhrase();
} catch (Exception $ex) {
    Log::Error('Error showing captcha image: %s', $ex);
}
