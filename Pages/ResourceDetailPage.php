<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php'); 
require_once(ROOT_DIR . 'Presenters/ResourceDetailPresenter.php');

class ResourceDetailPage extends SecurePage 

{
    /**
     * @var ResourceDetailPresenter 
     */
    private $presenter; 

    public function __construct()
    {
        parent::__construct('ResourceDetails');
        $this->presenter = new ResourceDetailPresenter($this);
    }

    public function PageLoad()
    {
        //check if user is logged in
        // if(!$this->IsLoggedIn()) {
        //    $this->Redirect('../index.php');
        //    return;
        //}
        
        $this->presenter->PageLoad();

        $this->Display('resource_detail.tpl');
    }
}