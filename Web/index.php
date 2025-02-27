<?php

define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Config/Configuration.php');

Configuration::Instance()->Validate();

require_once(ROOT_DIR . 'Pages/LoginPage.php');
require_once(ROOT_DIR . 'Presenters/LoginPresenter.php');

$page = new LoginPage();

if ($page->LoggingIn()) {
    $page->Login();
}

if ($page->ChangingLanguage()) {
    $page->ChangeLanguage();
}

$page->PageLoad();
