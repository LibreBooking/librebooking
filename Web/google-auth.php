<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Common/namespace.php');

//Checks if the user was authenticated by google and redirects to external authentication page
if (isset($_GET['code'])) {
    $code = filter_input(INPUT_GET, 'code');
    $url = ROOT_DIR . 'Web/external-auth.php?type=google&code=' . urlencode($code);

    $state = filter_input(INPUT_GET, 'state');
    if (!empty($state)) {
        $url .= '&redirect=' . urlencode($state);
    }

    header('Location: ' . $url);
    exit;
} else {
    header('Location:' . ROOT_DIR . 'Web');
    exit();
}
