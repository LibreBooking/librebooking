<?php

/**
 * Resource List Page - Mock implementation for Device Hub migration
 */
class ResourceListPage extends Page
{
    public function __construct()
    {
        parent::__construct('CheckResources');
    }

    public function PageLoad()
    {
        $this->Set('PageTitle', 'Equipment List');
        $this->Set('TotalRecords', 388);
        
        // Back to main template
        $this->Display('resource_list.tpl');
    }
}