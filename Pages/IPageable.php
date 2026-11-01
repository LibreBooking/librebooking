<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Domain/Access/PageableDataStore.php');

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
     * @return int The requested page, or 0 when none (or an invalid one) was requested
     */
    public function GetPageNumber()
    {
        return max(0, intval($this->page->GetQuerystring(QueryStringKeys::PAGE)));
    }

    /**
     * @return int The requested page size, PageInfo::All, or the configured default
     */
    public function GetPageSize()
    {
        $size = intval($this->page->GetQuerystring(QueryStringKeys::PAGE_SIZE));
        if ($size < 1 && $size != PageInfo::All) {
            return intval(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_PAGE_SIZE));
        }
        return $size;
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
