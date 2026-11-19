<?php

require_once(ROOT_DIR . 'Pages/LoginPage.php');
require_once(ROOT_DIR . 'Presenters/LoginPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class PostLogoutPage extends LoginPage
{
    public function __construct()
    {
        parent::__construct();
    }

    public function PageLoad()
    {
        $this->presenter->postLogout();
    }

    public function GetResumeUrl()
    {
        return $this->GetQuerystring(QueryStringKeys::REDIRECT);
    }
}
