<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');

/**
 * Resource List Page - Mock implementation for Device Hub migration
 */
class ResourceListPage extends SecurePage
{
    public function __construct()
    {
        parent::__construct('CheckResources');
    }

    public function PageLoad()
    {
        // For now, we use client-side JavaScript to load mock data
        // In future, this will query the database with filters
        
        $this->Set('PageTitle', 'Equipment List');
        $this->Set('TotalRecords', 388);
        
        $this->Display('resource_list.tpl');
    }
}