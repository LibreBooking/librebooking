<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Ajax/UserDetailsPopupPage.php');

class UserDetailsPopupPresenterTest extends TestBase
{
    /**
     * @var UserDetailsPopupPresenter
     */
    private $presenter;

    /**
     * @var FakeUserDetailsPopupPage
     */
    private $page;

    /**
     * @var FakePrivacyFilter
     */
    private $privacyFilter;

    /**
     * @var FakeAttributeService
     */
    private $attributeService;

    /**
     * @var FakeUserRepository
     */
    private $userRepository;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeUserDetailsPopupPage();
        $this->privacyFilter = new FakePrivacyFilter();
        $this->userRepository = new FakeUserRepository();
        $this->attributeService = new FakeAttributeService();

        $this->presenter = new UserDetailsPopupPresenter($this->page, $this->privacyFilter, $this->userRepository, $this->attributeService);
    }

    public function testWhenUserCannotAccessDetails_ThenDoNotBindAnything()
    {
        $this->privacyFilter->_CanViewUser = false;

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertFalse($this->page->_CanViewUser);
    }

    public function testWhenUserCannotAccessDetails_ThenBindUser()
    {
        $this->privacyFilter->_CanViewUser = true;

        $this->presenter->PageLoad($this->fakeUser);

        $this->assertTrue($this->page->_CanViewUser);
        $this->assertEquals($this->userRepository->_User, $this->page->_User);
    }
}

class FakeUserDetailsPopupPage implements IUserDetailsPopupPage
{
    /**
     * @var bool
     */
    public $_CanViewUser;

    /**
     * @var User
     */
    public $_User;

    /**
     * @var CustomAttribute[]
     */
    public $_Attributes;

    public function SetCanViewUser($canView)
    {
        $this->_CanViewUser = $canView;
    }

    public function GetUserId()
    {
        return '1';
    }

    /**
     * @param CustomAttribute[] $attributes
     */
    public function BindAttributes($attributes)
    {
        $this->_Attributes = $attributes;
    }

    /**
     * @param User $user
     */
    public function BindUser($user)
    {
        $this->_User = $user;
    }
}
