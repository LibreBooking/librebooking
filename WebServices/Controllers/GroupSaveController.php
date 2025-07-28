<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageGroupsPresenter.php');

interface IGroupSaveController
{
    /**
     * @param GroupRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Create($request, $session);

    /**
     * @param int $groupId
     * @param GroupRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Update($groupId, $request, $session);

    /**
     * @param int $groupId
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Delete($groupId, $session);

    /**
     * @param int $groupId
     * @param GroupRolesRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangeRoles($groupId, $request, $session);

    /**
     * @param int $groupId
     * @param GroupPermissionsRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangePermissions($groupId, $request, $session);

    /**
     * @param int $groupId
     * @param GroupUsersRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangeUsers($groupId, $request, $session);
}

class GroupControllerResult
{
    public function __construct(private $groupId, private $errors = [])
    {
    }

    /**
     * @return bool
     */
    public function WasSuccessful()
    {
        return !empty($this->groupId) && empty($this->errors);
    }

    /**
     * @return int
     */
    public function GroupId()
    {
        return $this->groupId;
    }

    /**
     * @return string[]
     */
    public function Errors()
    {
        return $this->errors;
    }
}

class GroupSaveController implements IGroupSaveController
{
    /**
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * @var ResourceRepository
     */
    private $resourceRepository;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(GroupRepository $groupRepository, ResourceRepository $resourceRepository, IScheduleRepository $scheduleRepository)
    {
        $this->groupRepository = $groupRepository;
        $this->resourceRepository = $resourceRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * @param GroupRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Create($request, $session)
    {
        $errors = $this->ValidateRequest($request);

        if (!empty($errors)) {
            return new GroupControllerResult(null, $errors);
        }

        $presenter = $this->GetPresenter(new CreateGroupFacade($request));

        $id = $presenter->AddGroup();

        return new GroupControllerResult($id, null);
    }

    /**
     * @param int $groupId
     * @param GroupRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Update($groupId, $request, $session)
    {
        $errors = $this->ValidateRequest($request);
        if (empty($groupId)) {
            $errors[] = 'groupId is required';
        }

        if (!empty($errors)) {
            return new GroupControllerResult(null, $errors);
        }

        $presenter = $this->GetPresenter(new CreateGroupFacade($request, $groupId));

        $presenter->UpdateGroup();

        return new GroupControllerResult($groupId);
    }

    /**
     * @param int $groupId
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function Delete($groupId, $session)
    {
        $errors = empty($groupId) ? ['groupId is required'] : [];
        if (!empty($errors)) {
            return new GroupControllerResult(null, $errors);
        }

        $presenter = $this->GetPresenter(new CreateGroupFacade(null, $groupId));
        $presenter->DeleteGroup();

        return new GroupControllerResult($groupId);
    }

    /**
     * @param GroupRequest $request
     * @return array|string[]
     */
    private function ValidateRequest($request)
    {
        $errors = [];

        if (empty($request->name)) {
            $errors[] = 'name is required';
        }

        return $errors;
    }

    private function GetPresenter($page)
    {
        return new ManageGroupsPresenter(
            $page,
            $this->groupRepository,
            $this->resourceRepository,
            $this->scheduleRepository,
            new UserRepository()
        );
    }

    /**
     * @param int $groupId
     * @param GroupRolesRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangeRoles($groupId, $request, $session)
    {
        $presenter = $this->GetPresenter(new UpdateGroupRolesFacade($request, $groupId));

        $presenter->ChangeRoles();

        return new GroupControllerResult($groupId, null);
    }

    /**
     * @param int $groupId
     * @param GroupPermissionsRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangePermissions($groupId, $request, $session)
    {
        $presenter = $this->GetPresenter(new UpdateGroupPermissionsFacade($request, $groupId));

        $presenter->ChangePermissions();

        return new GroupControllerResult($groupId, null);
    }

    /**
     * @param int $groupId
     * @param GroupUsersRequest $request
     * @param WebServiceUserSession $session
     * @return GroupControllerResult
     */
    public function ChangeUsers($groupId, $request, $session)
    {
        $presenter = $this->GetPresenter(new UpdateGroupUsersFacade($request, $groupId));

        $presenter->ChangeUsers();

        return new GroupControllerResult($groupId, null);
    }
}

abstract class GroupControllerPageFacade implements IManageGroupsPage
{
    public function TakingAction()
    {
    }

    public function GetAction()
    {
    }

    public function RequestingData()
    {
    }

    public function GetDataRequest()
    {
    }

    public function PageLoad()
    {
    }

    public function Redirect($url)
    {
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
    }

    public function IsPostBack()
    {
    }

    public function IsValid()
    {
    }

    public function GetLastPage($defaultPage = '')
    {
    }

    public function RegisterValidator($validatorId, $validator)
    {
    }

    public function EnforceCSRFCheck()
    {
    }

    public function GetSortField()
    {
    }

    public function GetSortDirection()
    {
    }

    abstract public function GetGroupId(): ?int;

    public function BindGroups($groups)
    {
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
    }

    public function GetPageNumber(): ?int
    {
        return null;
    }

    public function GetPageSize(): ?int
    {
        return null;
    }

    public function SetJsonResponse($response)
    {
    }

    public function GetUserId(): ?int
    {
        return null;
    }

    public function BindResources($resources)
    {
    }

    public function BindRoles($roles)
    {
    }

    public function GetAllowedResourceIds(): array
    {
        return [];
    }

    public function GetGroupName(): ?string
    {
        return null;
    }

    public function GetRoleIds(): array
    {
        return [];
    }

    public function BindAdminGroups($adminGroups)
    {
    }

    public function GetAdminGroupId(): ?int
    {
        return null;
    }

    public function AutomaticallyAddToGroup(): ?bool
    {
        return null;
    }

    public function GetUserIds(): array
    {
        return [];
    }

    public function Export($groups, $users, $permissionsWrite, $permissionsRead)
    {
        // TODO: Implement Export() method.
    }

    public function GetImportFile(): ?UploadedFile
    {
        // TODO: Implement GetImportFile() method.
        return null;
    }

    public function ShowTemplateCsv()
    {
    }

    public function SetImportResult($importResult)
    {
    }

    public function GetUpdateOnImport(): ?bool
    {
        return null;
    }
}

class CreateGroupFacade extends GroupControllerPageFacade
{
    /**
     * @param GroupRequest $request
     * @param int|null $id
     */
    public function __construct(private $request, private $id = null)
    {
    }

    public function GetGroupId(): ?int
    {
        return $this->id;
    }

    public function GetGroupName(): ?string
    {
        return $this->request->name;
    }

    public function AutomaticallyAddToGroup(): ?bool
    {
        return $this->request->isDefault;
    }

    /**
     * @param $schedules Schedule[]
     */
    public function BindSchedules($schedules)
    {
        // TODO: Implement BindSchedules() method.
    }

    /**
     * @return int[]
     */
    public function GetGroupAdminIds()
    {
        // TODO: Implement GetGroupAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetResourceAdminIds()
    {
        // TODO: Implement GetResourceAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetScheduleAdminIds()
    {
        // TODO: Implement GetScheduleAdminIds() method.
        return [];
    }
}

class UpdateGroupRolesFacade extends GroupControllerPageFacade
{
    /**
     * @param GroupRolesRequest $request
     * @param int|null $id
     */
    public function __construct(private $request, private $id = null)
    {
    }

    public function GetGroupId(): ?int
    {
        return $this->id;
    }

    public function GetRoleIds(): array
    {
        $roles = $this->request->roleIds;

        return empty($roles) ? [] : $roles;
    }

    /**
     * @param $schedules Schedule[]
     */
    public function BindSchedules($schedules)
    {
        // TODO: Implement BindSchedules() method.
    }

    /**
     * @return int[]
     */
    public function GetGroupAdminIds()
    {
        // TODO: Implement GetGroupAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetResourceAdminIds()
    {
        // TODO: Implement GetResourceAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetScheduleAdminIds()
    {
        // TODO: Implement GetScheduleAdminIds() method.
        return [];
    }
}

class UpdateGroupPermissionsFacade extends GroupControllerPageFacade
{
    /**
     * @param GroupPermissionsRequest $request
     * @param int|null $id
     */
    public function __construct(private $request, private $id = null)
    {
    }

    public function GetGroupId(): ?int
    {
        return $this->id;
    }

    public function GetAllowedResourceIds(): array
    {
        $ids = [];
        $full = $this->request->permissions;
        $view = $this->request->viewPermissions;

        if (!empty($full)) {
            foreach ($full as $id) {
                $ids[] = $id . '_' . ResourcePermissionType::Full;
            }
        }

        if (!empty($view)) {
            foreach ($view as $id) {
                $ids[] = $id . '_' . ResourcePermissionType::View;
            }
        }

        return $ids;
    }

    /**
     * @param $schedules Schedule[]
     */
    public function BindSchedules($schedules)
    {
        // TODO: Implement BindSchedules() method.
    }

    /**
     * @return int[]
     */
    public function GetGroupAdminIds()
    {
        // TODO: Implement GetGroupAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetResourceAdminIds()
    {
        // TODO: Implement GetResourceAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetScheduleAdminIds()
    {
        // TODO: Implement GetScheduleAdminIds() method.
        return [];
    }
}

class UpdateGroupUsersFacade extends GroupControllerPageFacade
{
    /**
     * @param GroupUsersRequest $request
     * @param int|null $id
     */
    public function __construct(private $request, private $id = null)
    {
    }

    public function GetGroupId(): ?int
    {
        return $this->id;
    }

    public function GetUserIds(): array
    {
        $ids = $this->request->userIds;

        return empty($ids) ? [] : $ids;
    }

    /**
     * @param $schedules Schedule[]
     */
    public function BindSchedules($schedules)
    {
        // TODO: Implement BindSchedules() method.
    }

    /**
     * @return int[]
     */
    public function GetGroupAdminIds()
    {
        // TODO: Implement GetGroupAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetResourceAdminIds()
    {
        // TODO: Implement GetResourceAdminIds() method.
        return [];
    }

    /**
     * @return int[]
     */
    public function GetScheduleAdminIds()
    {
        // TODO: Implement GetScheduleAdminIds() method.
        return [];
    }
}
