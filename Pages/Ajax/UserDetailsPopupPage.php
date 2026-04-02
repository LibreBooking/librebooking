<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

interface IUserDetailsPopupPage
{
    /**
     * @return string
     */
    public function GetUserId();

    /**
     * @param bool $canView
     */
    public function SetCanViewUser($canView);

    /**
     * @param CustomAttribute[] $attributes
     */
    public function BindAttributes($attributes);

    /**
     * @param User $user
     */
    public function BindUser($user);
}

class UserDetailsPopupPage extends Page implements IUserDetailsPopupPage
{
    /**
     * @param UserDetailsPopupPresenter $presenter
     */
    private $presenter;
    public function __construct()
    {
        parent::__construct('', 1);
        $this->presenter = new UserDetailsPopupPresenter($this, new PrivacyFilter(), new UserRepository(), new AttributeService(new AttributeRepository()));
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad(ServiceLocator::GetServer()->GetUserSession());
        $this->Display('Ajax/user_popup.tpl');
    }

    public function SetCanViewUser($canView)
    {
        $this->Set('CanViewUser', $canView);
    }

    /**
     * @return string
     */
    public function GetUserId()
    {
        return $this->GetQuerystring(QueryStringKeys::USER_ID);
    }

    /**
     * @param CustomAttribute[] $attributes
     */
    public function BindAttributes($attributes)
    {
        $this->Set('Attributes', $attributes);
    }

    /**
     * @param User $user
     */
    public function BindUser($user)
    {
        $this->Set('User', $user);
    }
}

class UserDetailsPopupPresenter
{
    /**
     * @var IUserDetailsPopupPage
     */
    private $page;
    /**
     * @var IPrivacyFilter
     */
    private $privacyFilter;
    /**
     * @var IUserRepository
     */
    private $userRepository;
    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(IUserDetailsPopupPage $page, IPrivacyFilter $privacyFilter, IUserRepository $userRepository, IAttributeService $attributeService)
    {
        $this->page = $page;
        $this->privacyFilter = $privacyFilter;
        $this->userRepository = $userRepository;
        $this->attributeService = $attributeService;
    }
    /**
     * @param $currentUser UserSession
     */
    public function PageLoad($currentUser)
    {
        $user = $this->userRepository->LoadById($this->page->GetUserId());

        if ($this->privacyFilter->CanViewUser($currentUser, null, $user->Id())) {
            $this->page->SetCanViewUser(true);
            $attributes = $this->attributeService->GetByCategory(CustomAttributeCategory::USER);
            $this->page->BindAttributes($attributes);
            $this->page->BindUser($user);
        } else {
            $this->page->SetCanViewUser(false);
        }
    }
}
