<?php

require_once(ROOT_DIR . 'Pages/Page.php');

interface IPageable
{
    /**
     * @abstract
     * @return int
     */
    public function GetPageNumber();

    /**
     * @abstract
     * @return int
     */
    public function GetPageSize();

    /**
     * @abstract
     * @param PageInfo $pageInfo
     * @return void
     */
    public function BindPageInfo(PageInfo $pageInfo);
}

class PageablePage extends Page implements IPageable
{
    /**
     * @var \Page
     */
    private $page;

    public function __construct(Page $wrappedPage)
    {
        $this->page = $wrappedPage;
    }

    /**
     * @return int
     */
    public function GetPageNumber()
    {
        return intval($this->page->GetQuerystring(QueryStringKeys::PAGE));
    }

    /**
     * @return int
     */
    public function GetPageSize()
    {
        $size = $this->page->GetQuerystring(QueryStringKeys::PAGE_SIZE);
        if (empty($size)) {
            return intval(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_PAGE_SIZE));
        }
        return intval($size);
    }

    /**
     * @param PageInfo $pageInfo
     * @return void
     */
    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->page->Set('PageInfo', $pageInfo);
    }

    public function PageLoad()
    {
        $this->page->PageLoad();
    }
}
