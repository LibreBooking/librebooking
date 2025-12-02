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
        $this->presenter->PageLoad();

        $resourceNAme = $this->GetVar('ResourceName');

        $this->Set('Title', $resourceNAme . ' - Equipment Details');

        $this->Display('resource_detail.tpl');
    }
}