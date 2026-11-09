<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

interface IManageResourceStatusPage extends IActionPage
{
    public function BindResourceStatusReasons($statusReasonList);

    public function GetStatusId();

    public function GetDescription();

    public function GetReasonId();
}

class ManageResourceStatusPage extends ActionPage implements IManageResourceStatusPage
{
    protected ManageResourceStatusPresenter $presenter;

    public function __construct()
    {
        parent::__construct('ManageResourceStatus', 1);
        $this->presenter = new ManageResourceStatusPresenter(
            $this,
            new ResourceRepository()
        );
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();

        $this->Display('Admin/Resources/manage_resource_status.tpl');
    }

    /**
     * @return void
     */
    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    /**
     * This page has no data requests. ManageResourceStatusPresenter has no
     * ProcessDataRequest(), so forwarding the request would fail.
     *
     * @param $dataRequest string
     */
    public function ProcessDataRequest($dataRequest): void
    {
    }

    public function BindResourceStatusReasons($statusReasonList)
    {
        $this->Set('StatusReasons', $statusReasonList);
    }

    public function GetStatusId()
    {
        return $this->GetForm(FormKeys::RESOURCE_STATUS_ID);
    }

    public function GetDescription()
    {
        return $this->GetForm(FormKeys::RESOURCE_STATUS_REASON);
    }

    public function GetReasonId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_STATUS_REASON_ID);
    }
}
