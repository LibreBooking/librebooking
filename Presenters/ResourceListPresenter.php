<?php

/**
 * Resource List Presenter
 * 
 * Simplified presenter for mock data implementation.
 * Will be replaced with real repository queries when database
 * schema includes custom attributes (Division, Platform, etc.)
 */
class ResourceListPresenter
{
    /**
     * @var ResourceListPage
     */
    private $page;

    /**
     * @param ResourceListPage $page
     */
    public function __construct($page)
    {
        $this->page = $page;
    }

    /**
     * Load page data and bind to template
     */
    public function PageLoad()
    {
        // For now, just enable mock mode
        // JavaScript will handle loading client-side data
        $this->page->SetMockMode(true);
        
        // TODO: When DB schema is ready, implement:
        // - $resources = $this->resourceRepo->GetList($filters);
        // - $this->page->BindResources($resources);
        // - $this->page->BindFilterOptions($departments, $methods);
    }
}