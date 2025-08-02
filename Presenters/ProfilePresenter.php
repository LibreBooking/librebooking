<?php

require_once(ROOT_DIR . 'config/timezones.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

class ProfileActions
{
    public const Update = 'update';
    public const ChangeDefaultSchedule = 'changeDefaultSchedule';
}

class ProfilePresenter extends ActionPresenter
{
    /**
     * @var IProfilePage
     */
    private $page;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(
        IProfilePage $page,
        IUserRepository $userRepository,
        IAttributeService $attributeService
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->userRepository = $userRepository;
        $this->attributeService = $attributeService;

        $this->AddAction(ProfileActions::Update, 'UpdateProfile');
        $this->AddAction(ProfileActions::ChangeDefaultSchedule, 'ChangeDefaultSchedule');
    }

    public function PageLoad()
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();

        Log::Debug('ProfilePresenter loading user %s', $userSession->UserId);

        $user = $this->userRepository->LoadById($userSession->UserId);

        $this->page->SetUsername($user->Username());
        $this->page->SetFirstName($user->FirstName());
        $this->page->SetLastName($user->LastName());
        $this->page->SetEmail($user->EmailAddress());
        $this->page->SetTimezone($user->Timezone());
        $this->page->SetHomepage($user->Homepage());

        $this->page->SetPhone($user->GetAttribute(UserAttribute::Phone));
        $this->page->SetOrganization($user->GetAttribute(UserAttribute::Organization));
        $this->page->SetPosition($user->GetAttribute(UserAttribute::Position));

        $userId = $userSession->UserId;

        $this->page->SetAttributes($this->GetAttributes($userId));

        $this->PopulateTimezones();
        $this->PopulateHomepages();
        $this->page->SetAllowedActions(PluginManager::Instance()->LoadAuthentication());
    }

    public function UpdateProfile()
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        Log::Debug('ProfilePresenter updating profile for user %s', $userSession->UserId);

        $user = $this->userRepository->LoadById($userSession->UserId);

        $user->ChangeName($this->page->GetFirstName(), $this->page->GetLastName());
        $user->ChangeEmailAddress($this->page->GetEmail());
        $user->ChangeUsername($this->page->GetLoginName());
        $user->ChangeDefaultHomePage($this->page->GetHomepage());
        $user->ChangeTimezone($this->page->GetTimezone());
        $user->ChangeAttributes($this->page->GetPhone(), $this->page->GetOrganization(), $this->page->GetPosition());
        $user->ChangeCustomAttributes($this->GetAttributeValues(), false);

        $userSession->Email = $this->page->GetEmail();
        $userSession->FirstName = $this->page->GetFirstName();
        $userSession->LastName = $this->page->GetLastName();
        $userSession->HomepageId = $this->page->GetHomepage();
        $userSession->Timezone = $this->page->GetTimezone();

        $this->userRepository->Update($user);
        ServiceLocator::GetServer()->SetUserSession($userSession);
    }

    public function ChangeDefaultSchedule()
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $scheduleId = $this->page->GetDefaultSchedule();

        Log::Debug('ProfilePresenter updating default schedule to %s for user %s', $scheduleId, $userSession->UserId);

        $user = $this->userRepository->LoadById($userSession->UserId);
        $user->ChangeDefaultSchedule($scheduleId);

        $this->userRepository->Update($user);

        $userSession->ScheduleId = $this->page->GetDefaultSchedule();
        ServiceLocator::GetServer()->SetUserSession($userSession);
    }

    protected function LoadValidators($action)
    {
        if ($action != ProfileActions::Update) {
            return;
        }
        $userId = ServiceLocator::GetServer()->GetUserSession()->UserId;
        $this->page->RegisterValidator('fname', new RequiredValidator($this->page->GetFirstName()));
        $this->page->RegisterValidator('username', new RequiredValidator($this->page->GetLoginName()));
        $this->page->RegisterValidator('lname', new RequiredValidator($this->page->GetLastName()));
        $this->page->RegisterValidator('emailformat', new EmailValidator($this->page->GetEmail()));
        $this->page->RegisterValidator(
            'uniqueemail',
            new UniqueEmailValidator($this->userRepository, $this->page->GetEmail(), $userId)
        );
        $this->page->RegisterValidator(
            'uniqueusername',
            new UniqueUserNameValidator($this->userRepository, $this->page->GetLoginName(), $userId)
        );
        $this->page->RegisterValidator(
            'additionalattributes',
            new AttributeValidator($this->attributeService, CustomAttributeCategory::USER, $this->GetAttributeValues(), $userId)
        );
        if (Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_PHONE, new BooleanConverter())) {
            $this->page->RegisterValidator('phoneRequired', new RequiredValidator($this->page->GetPhone()));
        }
        if (Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_ORGANIZATION, new BooleanConverter())) {
            $this->page->RegisterValidator('organizationRequired', new RequiredValidator($this->page->GetOrganization()));
        }
        if (Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_POSITION, new BooleanConverter())) {
            $this->page->RegisterValidator('positionRequired', new RequiredValidator($this->page->GetPosition()));
        }
    }

    /**
     * @return array|AttributeValue[]
     */
    private function GetAttributeValues()
    {
        $attributes = [];
        foreach ($this->page->GetAttributes() as $attribute) {
            $attributes[] = new AttributeValue($attribute->Id, $attribute->Value);
        }
        return $attributes;
    }

    private function PopulateTimezones()
    {
        $timezoneValues = [];
        $timezoneOutput = [];

        foreach ($GLOBALS['APP_TIMEZONES'] as $timezone) {
            $timezoneValues[] = $timezone;
            $timezoneOutput[] = $timezone;
        }

        $this->page->SetTimezones($timezoneValues, $timezoneOutput);
    }

    private function PopulateHomepages()
    {
        $homepageValues = [];
        $homepageOutput = [];

        $pages = Pages::GetAvailablePages();
        foreach ($pages as $pageid => $page) {
            $homepageValues[] = $pageid;
            $homepageOutput[] = Resources::GetInstance()->GetString($page['name']);
        }

        $this->page->SetHomepages($homepageValues, $homepageOutput);
    }

    private function GetAttributes($userId)
    {
        $allAttributes = [];

        $attributes = $this->attributeService->GetAttributes(CustomAttributeCategory::USER, $userId);
        $asList = $attributes->GetAttributes($userId);

        foreach ($asList as $a) {
            if (!$a->AdminOnly()) {
                $allAttributes[] = $a;
            }
        }

        return $allAttributes;
    }
}
