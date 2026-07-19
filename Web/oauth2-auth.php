<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Common/namespace.php');

//Checks if the user was authenticated by oauth2 and redirects to external authentication page
$code = filter_input(INPUT_GET, 'code', FILTER_UNSAFE_RAW);

if (is_string($code) && $code !== '') {
    $params = ['type' => 'oauth2', 'code' => $code];

    $state = filter_input(INPUT_GET, 'state', FILTER_UNSAFE_RAW);
    if (is_string($state) && $state !== '') {
        $params['state'] = $state;
    }

    header('Location: ' . ROOT_DIR . 'Web/external-auth.php?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
    exit;
}

header('Location:' . ROOT_DIR . 'Web');
exit();
