<?php

require_once(ROOT_DIR . 'config/timezones.php');
require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageUsersPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

interface IManageUsersPage extends IPageable, IActionPage
{
    /**
     * @param UserItemView[] $users
     * @return void
     */
    public function BindUsers($users);

    /**
     * @return int
     */
    public function GetUserId();

    /**
     * @param BookableResource[] $resources
     * @return void
     */
    public function BindResources($resources);

    /**
     * @param mixed $objectToSerialize
     * @return void
     */
    public function SetJsonResponse($objectToSerialize);

    /**
     * @return int[] resource ids the user has permission to
     */
    public function GetAllowedResourceIds();

    /**
     * @return string
     */
    public function GetPassword();

    /**
     * @return string
     */
    public function GetEmail();

    /**
     * @return string
     */
    public function GetUserName();

    /**
     * @return string
     */
    public function GetFirstName();

    /**
     * @return string
     */
    public function GetLastName();

    /**
     * @return string
     */
    public function GetTimezone();

    /**
     * @return string
     */
    public function GetPhone();

    /**
     * @return string
     */
    public function GetPosition();

    /**
     * @return string
     */
    public function GetOrganization();

    /**
     * @return string
     */
    public function GetLanguage();

    /**
     * @param $attributeList CustomAttribute[]
     */
    public function BindAttributeList($attributeList);

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetAttributes();

    /**
     * @return AccountStatus|int
     */
    public function GetFilterStatusId();

    /**
     * @return int
     */
    public function GetUserGroup();

    /**
     * @param GroupItemView[] $groups
     */
    public function BindGroups($groups);

    /**
     * @return string
     */
    public function GetReservationColor();

    /**
     * @return string
     */
    public function GetValue();

    /**
     * @return string
     */
    public function GetName();

    /**
     * @param CustomAttribute[] $attributes
     */
    public function ShowTemplateCSV($attributes);

    /**
     * @return UploadedFile
     */
    public function GetImportFile();

    /**
     * @param CsvImportResult $importResult
     */
    public function SetImportResult($importResult);

    /**
     * @return string
     */
    public function GetInvitedEmails();

    public function ShowExportCsv();

    public function BindStatusDescriptions();

    /**
     * @return int[]
     */
    public function GetDeletedUserIds();

    /**
     * @return bool
     */
    public function SendEmailNotification();

    /**
     * @return bool
     */
    public function GetUpdateOnImport();

    /**
     * @param User $user
     * @param CustomAttribute[] $attributes
     */
    public function ShowUserUpdate(User $user, $attributes);
}

class ManageUsersPage extends ActionPage implements IManageUsersPage
{
    /**
     * @var \ManageUsersPresenter
     */
    protected $_presenter;

    /**
     * @var \PageablePage
     */
    protected $pageable;

    public function __construct()
    {
        $serviceFactory = new ManageUsersServiceFactory();

        parent::__construct('ManageUsers', 1);
        $groupRepository = new GroupRepository();
        $this->_presenter = new ManageUsersPresenter(
            $this,
            new UserRepository(),
            new ResourceRepository(),
            new PasswordEncryption(),
            $serviceFactory->CreateAdmin(),
            new AttributeService(new AttributeRepository()),
            $groupRepository,
            $groupRepository
        );

        $this->pageable = new PageablePage($this);
    }

    public function ProcessPageLoad()
    {
        $this->_presenter->PageLoad();

        $config = Configuration::Instance();

        $this->Set('Timezone', $config->GetDefaultTimezone());
        $this->Set('Timezones', $GLOBALS['APP_TIMEZONES']);
        $this->Set('Languages', $GLOBALS['APP_TIMEZONES']);
        $this->Set('ManageGroupsUrl', Pages::MANAGE_GROUPS);
        $this->Set('ManageReservationsUrl', Pages::MANAGE_RESERVATIONS);
        $this->Set('FilterStatusId', $this->GetFilterStatusId());
        $this->Set('PerUserColors', $config->GetKey(ConfigKeys::SCHEDULE_USE_PER_USER_COLORS, new BooleanConverter()));
        $this->Set('CreditsEnabled', $config->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));
        $url = $this->server->GetUrl();
        $exportUrl = BookedStringHelper::Contains($url, '?') ? $url . '&dr=export' : $this->server->GetRequestUri() . '?dr=export';
        $this->Set('ExportUrl', $exportUrl);

        $this->RenderTemplate();
    }

    public function BindStatusDescriptions()
    {
        $resources = Resources::GetInstance();
        $this->Set(
            'statusDescriptions',
            [AccountStatus::ALL => $resources->GetString('All'), AccountStatus::ACTIVE => $resources->GetString('Active'), AccountStatus::AWAITING_ACTIVATION => $resources->GetString('Pending'), AccountStatus::INACTIVE => $resources->GetString('Inactive')]
        );
    }

    protected function RenderTemplate()
    {
        $this->Display('Admin/Users/manage_users.tpl');
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->pageable->BindPageInfo($pageInfo);
    }

    public function GetPageNumber()
    {
        return $this->pageable->GetPageNumber();
    }

    /**
     * @todo Delete this method for LibreBooking v4.0.0
     */
    public function GetPageSize()
    {
        /* replaced by dataTable */
        /*return $this->pageable->GetPageSize();*/
        throw new \LogicException('GetPageSize is not implemented - replaced by dataTable pagination');
    }

    public function BindUsers($users)
    {
        $this->Set('users', $users);
    }

    public function ProcessAction()
    {
        $this->_presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->_presenter->ProcessDataRequest($dataRequest);
    }

    /**
     * @return int
     */
    public function GetUserId()
    {
        $id = $this->GetQuerystring(QueryStringKeys::USER_ID);
        if (empty($id)) {
            $id = $this->GetForm(FormKeys::PK);
        }

        return $id;
    }

    /**
     * @param BookableResource[] $resources
     * @return void
     */
    public function BindResources($resources)
    {
        $this->Set('resources', $resources);
    }

    /**
     * @return int[] resource ids the user has permission to
     */
    public function GetAllowedResourceIds()
    {
        return $this->GetForm(FormKeys::RESOURCE_ID);
    }

    /**
     * @return string
     */
    public function GetPassword()
    {
        return $this->GetForm(FormKeys::PASSWORD);
    }

    /**
     * @param mixed $objectToSerialize
     * @return void
     */
    public function SetJsonResponse($objectToSerialize)
    {
        parent::SetJson($objectToSerialize);
    }

    /**
     * @return string
     */
    public function GetEmail()
    {
        return $this->GetForm(FormKeys::EMAIL);
    }

    /**
     * @return string
     */
    public function GetUserName()
    {
        return $this->GetForm(FormKeys::USERNAME);
    }

    public function GetFirstName()
    {
        return $this->GetForm(FormKeys::FIRST_NAME);
    }

    public function GetLastName()
    {
        return $this->GetForm(FormKeys::LAST_NAME);
    }

    public function GetTimezone()
    {
        return $this->GetForm(FormKeys::TIMEZONE);
    }

    public function GetPhone()
    {
        return $this->GetForm(FormKeys::PHONE);
    }

    public function GetPosition()
    {
        return $this->GetForm(FormKeys::POSITION);
    }

    public function GetOrganization()
    {
        return $this->GetForm(FormKeys::ORGANIZATION);
    }

    public function GetLanguage()
    {
        return $this->GetForm(FormKeys::LANGUAGE);
    }

    public function BindAttributeList($attributeList)
    {
        $this->Set('AttributeList', $attributeList);
    }

    public function GetAttributes()
    {
        return AttributeFormParser::GetAttributes($this->GetForm(FormKeys::ATTRIBUTE_PREFIX));
    }

    public function GetFilterStatusId()
    {
        $statusId = $this->GetQuerystring(QueryStringKeys::ACCOUNT_STATUS);
        return empty($statusId) ? AccountStatus::ALL : $statusId;
    }

    public function GetUserGroup()
    {
        return $this->GetForm(FormKeys::GROUP_ID);
    }

    public function BindGroups($groups)
    {
        $gs = [];
        foreach ($groups as $g) {
            $gs[$g->Id()] = $g;
        }
        $this->Set('Groups', $gs);
    }

    public function GetReservationColor()
    {
        return $this->GetForm(FormKeys::RESERVATION_COLOR);
    }

    public function GetValue()
    {
        return $this->GetForm(FormKeys::VALUE);
    }

    public function GetName()
    {
        return $this->GetForm(FormKeys::NAME);
    }


    public function ShowTemplateCSV($attributes)
    {
        $this->Set('attributes', $attributes);
        $this->DisplayCsv('Admin/Users/import_user_template_csv.tpl', 'users.csv');
    }

    public function GetImportFile()
    {
        return $this->server->GetFile(FormKeys::USER_IMPORT_FILE);
    }

    public function SetImportResult($importResult)
    {
        $this->SetJsonResponse($importResult);
    }

    public function GetInvitedEmails()
    {
        return $this->GetForm(FormKeys::INVITED_EMAILS);
    }

    public function ShowExportCsv()
    {
        $this->DisplayCsv('Admin/Users/users_csv.tpl', 'users.csv');
    }

    public function GetDeletedUserIds()
    {
        $ids = $this->GetForm(FormKeys::USER_ID);
        if (!is_array($ids)) {
            return [$ids];
        }

        return $ids;
    }

    public function SendEmailNotification()
    {
        return $this->GetCheckbox(FormKeys::SEND_AS_EMAIL);
    }

    public function GetUpdateOnImport()
    {
        return $this->GetCheckbox(FormKeys::UPDATE_ON_IMPORT);
    }

    public function ShowUserUpdate(User $user, $attributes)
    {
        $this->Set('Timezones', $GLOBALS['APP_TIMEZONES']);
        $this->Set('Languages', $GLOBALS['APP_TIMEZONES']);
        $this->Set('User', $user);
        $this->Set('Attributes', $attributes);
        $this->Display('Admin/Users/user-update.tpl');
    }
}
