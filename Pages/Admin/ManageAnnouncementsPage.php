<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageAnnouncementsPresenter.php');

interface IManageAnnouncementsPage extends IActionPage
{
    public function GetAnnouncementId(): ?int;

    /**
     * @return string
     */
    public function GetText();

    /**
     * @return string
     */
    public function GetStart();

    /**
     * @return string
     */
    public function GetEnd();

    /**
     * @return string
     */
    public function GetPriority();

    /**
     * @return int[]
     */
    public function GetGroups();

    /**
     * @return int[]
     */
    public function GetResources();

    /**
     * @return bool
     */
    public function GetSendAsEmail();

    /**
     * @param $announcements Announcement[]
     * @return void
     */
    public function BindAnnouncements($announcements);

    /**
     * @param GroupItemView[] $groups
     */
    public function BindGroups($groups);

    /**
     * @param BookableResource[] $resources
     */
    public function BindResources($resources);

    /**
     * @param int $number
     */
    public function BindNumberOfUsersToBeSent($number);

    public function GetDisplayPage(): ?int;
}

class ManageAnnouncementsPage extends ActionPage implements IManageAnnouncementsPage
{
    /**
     * @var ManageAnnouncementsPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageAnnouncements', 1);
        $resourceRepository = new ResourceRepository();
        $this->presenter = new ManageAnnouncementsPresenter(
            $this,
            new AnnouncementRepository(),
            new GroupRepository(),
            $resourceRepository,
            PluginManager::Instance()->LoadPermission(),
            new UserRepository()
        );
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();

        $this->Set('priorities', range(1, 10));
        $this->Set('timezone', ServiceLocator::GetServer()->GetUserSession()->Timezone);

        $this->Display('Admin/manage_announcements.tpl');
    }

    public function BindAnnouncements($announcements)
    {
        $this->Set('announcements', $announcements);
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function GetAnnouncementId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::ANNOUNCEMENT_ID);
    }

    public function GetText()
    {
        return $this->GetForm(FormKeys::ANNOUNCEMENT_TEXT);
    }

    public function GetStart()
    {
        return $this->GetForm(FormKeys::ANNOUNCEMENT_START);
    }

    public function GetEnd()
    {
        return $this->GetForm(FormKeys::ANNOUNCEMENT_END);
    }

    public function GetPriority()
    {
        return $this->GetForm(FormKeys::ANNOUNCEMENT_PRIORITY);
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest);
    }

    public function BindGroups($groups)
    {
        $this->Set('Groups', $groups);
    }

    public function BindResources($resources)
    {
        $this->Set('Resources', $resources);
    }

    public function BindNumberOfUsersToBeSent($number)
    {
        $this->SetJson(['users' => $number]);
    }
    public function GetGroups()
    {
        $groupIds = $this->GetForm(FormKeys::GROUP_ID);

        if (!is_array($groupIds)) {
            return [];
        }

        return $groupIds;
    }

    public function GetResources()
    {
        $resourceIds = $this->GetForm(FormKeys::RESOURCE_ID);

        if (!is_array($resourceIds)) {
            return [];
        }

        return $resourceIds;
    }

    public function GetSendAsEmail()
    {
        $send = $this->GetForm(FormKeys::SEND_AS_EMAIL);

        return isset($send);
    }

    public function GetDisplayPage(): ?int
    {
        return $this->GetFormInt(key: FormKeys::DISPLAY_PAGE);
    }
}
