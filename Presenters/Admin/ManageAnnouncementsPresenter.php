<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Email/Messages/AnnouncementEmail.php');

class ManageAnnouncementsActions
{
    public const Add = 'addAnnouncement';
    public const Change = 'changeAnnouncement';
    public const Delete = 'deleteAnnouncement';
    public const Email = 'emailAnnouncement';
}

class ManageAnnouncementsPresenter extends ActionPresenter
{
    /**
     * @var IManageAnnouncementsPage
     */
    private $page;
    /**
     * @var IAnnouncementRepository
     */
    private $announcementRepository;
    /**
     * @var IGroupViewRepository
     */
    private $groupViewRepository;
    /**
     * @var IResourceRepository
     */
    private $resourceRepository;
    /**
     * @var IUserViewRepository
     */
    private $userViewRepository;

    /**
     * @param IManageAnnouncementsPage $page
     * @param IAnnouncementRepository $announcementRepository
     * @param IGroupViewRepository $groupViewRepository
     * @param IResourceRepository $resourceRepository
     * @param IUserViewRepository $userViewRepository
     */
    public function __construct(
        IManageAnnouncementsPage $page,
        IAnnouncementRepository $announcementRepository,
        IGroupViewRepository $groupViewRepository,
        IResourceRepository $resourceRepository,
        IUserViewRepository $userViewRepository
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->announcementRepository = $announcementRepository;
        $this->groupViewRepository = $groupViewRepository;
        $this->resourceRepository = $resourceRepository;
        $this->userViewRepository = $userViewRepository;

        $this->AddAction(ManageAnnouncementsActions::Add, 'AddAnnouncement');
        $this->AddAction(ManageAnnouncementsActions::Change, 'ChangeAnnouncement');
        $this->AddAction(ManageAnnouncementsActions::Delete, 'DeleteAnnouncement');
        $this->AddAction(ManageAnnouncementsActions::Email, 'EmailAnnouncement');
    }

    public function PageLoad()
    {
        $this->page->BindAnnouncements($this->announcementRepository->GetAll($this->page->GetSortField(), $this->page->GetSortDirection()));
        $this->page->BindGroups($this->GetGroups());
        $this->page->BindResources($this->GetResources());
    }

    public function AddAnnouncement()
    {
        $user = ServiceLocator::GetServer()->GetUserSession();
        $text = $this->page->GetText();
        $text = str_replace('&lt;script&gt;', '', $text);
        $text = str_replace('&lt;/script&gt;', '', $text);
        $start = Date::Parse($this->page->GetStart(), $user->Timezone);
        $end = Date::Parse($this->page->GetEnd(), $user->Timezone);
        $priority = $this->page->GetPriority();
        $groupIds = $this->page->GetGroups();
        $resourceIds = $this->page->GetResources();
        $sendAsEmail = $this->page->GetSendAsEmail();
        $displayPage = $this->page->GetDisplayPage();

        Log::Debug('Adding new Announcement');

        $announcement = Announcement::Create($text, $start, $end, $priority, $groupIds, $resourceIds, $displayPage);
        $this->announcementRepository->Add($announcement);

        if ($sendAsEmail) {
            $this->SendAsEmail($announcement, $user);
        }
    }

    public function ChangeAnnouncement()
    {
        $user = ServiceLocator::GetServer()->GetUserSession();

        $id = $this->page->GetAnnouncementId();
        $text = $this->page->GetText();
        $start = Date::Parse($this->page->GetStart(), $user->Timezone);
        $end = Date::Parse($this->page->GetEnd(), $user->Timezone);
        $priority = $this->page->GetPriority();
        $groupIds = $this->page->GetGroups();
        $resourceIds = $this->page->GetResources();

        Log::Debug('Changing Announcement with id %s', $id);

        $announcement = $this->announcementRepository->LoadById($id);
        $announcement->SetText($text);
        $announcement->SetDates($start, $end);
        $announcement->SetPriority($priority);
        $announcement->SetGroups($groupIds);
        $announcement->SetResources($resourceIds);

        $this->announcementRepository->Update($announcement);
    }

    public function DeleteAnnouncement()
    {
        $id = $this->page->GetAnnouncementId();

        Log::Debug('Deleting Announcement with id %s', $id);

        $this->announcementRepository->Delete($id);
    }

    public function EmailAnnouncement()
    {
        $announcementId = $this->page->GetAnnouncementId();
        $announcement = $this->announcementRepository->LoadById($announcementId);
        $this->SendAsEmail($announcement, ServiceLocator::GetServer()->GetUserSession());
    }

    private function SendAsEmail(Announcement $announcement, UserSession $user)
    {
        $usersToSendTo = $this->GetUsersToSendTo($announcement, $user);

        $emailService = ServiceLocator::GetEmailService();
        foreach ($usersToSendTo as $userToSendTo) {
            $emailService->Send(new AnnouncementEmail($announcement->Text(), $user, $userToSendTo));
        }
    }

    /**
     * @return GroupItemView[]
     */
    private function GetGroups()
    {
        /** @var GroupItemView[] $groups */
        $groups = $this->groupViewRepository->GetList()->Results();
        $indexedGroups = [];
        foreach ($groups as $group) {
            $indexedGroups[$group->Id] = $group;
        }

        return $indexedGroups;
    }

    /**
     * @return BookableResource[]
     */
    private function GetResources()
    {
        /** @var BookableResource[] $resources */
        $resources = $this->resourceRepository->GetList(null, null)->Results();
        $indexedResources = [];
        foreach ($resources as $resource) {
            $indexedResources[$resource->GetId()] = $resource;
        }

        return $indexedResources;
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest == 'emailCount') {
            $announcementId = $this->page->GetAnnouncementId();
            $announcement = $this->announcementRepository->LoadById($announcementId);
            $user = ServiceLocator::GetServer()->GetUserSession();
            $this->page->BindNumberOfUsersToBeSent(count($this->GetUsersToSendTo($announcement, $user)));
        }
    }

    /**
     * @param Announcement $announcement
     * @param UserSession $user
     * @return UserItemView[]
     */
    private function GetUsersToSendTo(Announcement $announcement, UserSession $user)
    {
        $allUsers = [];
        $usersToSendTo = [];
        $validUsers = [];

        $groupIds = $announcement->GroupIds();
        $resourceIds = $announcement->ResourceIds();
        if (empty($groupIds) && empty($resourceIds)) {
            $userList = $this->userViewRepository->GetList(null, null, null, null, null, AccountStatus::ACTIVE)->Results();
            foreach ($userList as $user) {
                $allUsers[$user->Id] = $user;
                $usersToSendTo[] = $user;
            }
            return $usersToSendTo;
        } else {
            $groupUserIds = [];
            $resourceUserIds = [];

            $groupUsers = $this->groupViewRepository->GetUsersInGroup($announcement->GroupIds())->Results();

            foreach ($groupUsers as $groupUser) {
                $groupUserIds[] = $groupUser->Id;
                $allUsers[$groupUser->Id] = $groupUser;
            }

            foreach ($announcement->ResourceIds() as $resourceId) {
                $resourceUsers = $this->resourceRepository->GetUsersWithPermissionsIncludingGroups($resourceId)->Results();
                foreach ($resourceUsers as $resourceUser) {
                    $resourceUserIds[] = $resourceUser->Id;
                    $allUsers[$resourceUser->Id] = $resourceUser;
                }
            }

            $usersToSendTo = array_unique(array_merge($groupUserIds, $resourceUserIds));

            foreach ($usersToSendTo as $userId) {
                $validUsers[] = $allUsers[$userId];
            }

            return $validUsers;
        }
    }
}
