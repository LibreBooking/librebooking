<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageResourceStatusPage.php');

class ManageResourceStatusActions
{
    public const Add = 'Add';
    public const Update = 'Update';
    public const Delete = 'Delete';
}

class ManageResourceStatusPresenter extends ActionPresenter
{
    /**
     * @var IManageResourceStatusPage
     */
    private $page;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    public function __construct(
        IManageResourceStatusPage $page,
        IResourceRepository $resourceRepository
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->resourceRepository = $resourceRepository;

        $this->AddAction(ManageResourceStatusActions::Add, 'Add');
        $this->AddAction(ManageResourceStatusActions::Update, 'Update');
        $this->AddAction(ManageResourceStatusActions::Delete, 'Delete');
    }

    public function PageLoad()
    {
        $statusReasons = $this->resourceRepository->GetStatusReasons();
        $statusReasonList = [];

        foreach ($statusReasons as $reason) {
            $statusReasonList[$reason->StatusId()][] = $reason;
        }
        $this->page->BindResourceStatusReasons($statusReasonList);
    }

    public function Add()
    {
        $statusId = $this->page->GetStatusId();
        $description = $this->page->GetDescription();

        $this->resourceRepository->AddStatusReason($statusId, $description);
    }

    public function Update()
    {
        $reasonId = $this->page->GetReasonId();
        $description = $this->page->GetDescription();

        $this->resourceRepository->UpdateStatusReason($reasonId, $description);
    }

    public function Delete()
    {
        $reasonId = $this->page->GetReasonId();
        $this->resourceRepository->RemoveStatusReason($reasonId);
    }
}
