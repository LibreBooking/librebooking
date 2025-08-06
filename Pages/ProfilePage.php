<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Presenters/ProfilePresenter.php');

interface IProfilePage extends IPage, IActionPage
{
    public function SetFirstName($firstName);

    public function SetLastName($lastName);

    public function SetEmail($email);

    public function SetUsername($username);

    public function SetTimezone($timezoneName);

    public function SetHomepage($homepageId);

    public function SetTimezones($timezoneValues, $timezoneOutput);

    public function SetHomepages($homepageValues, $homepageOutput);

    public function GetFirstName();

    public function GetLastName();

    public function GetEmail();

    public function GetLoginName();

    public function GetTimezone();

    public function GetHomepage();

    public function GetPhone();

    public function GetOrganization();

    public function GetPosition();

    public function SetPhone($phone);

    public function SetOrganization($organization);

    public function SetPosition($position);

    public function SetAttributes($attributes);

    public function GetDefaultSchedule();

    /**
     * @abstract
     * @return AttributeFormElement[]
     */
    public function GetAttributes();

    /**
     * @param IAuthenticationActionOptions $options
     */
    public function SetAllowedActions($options);
}

class ProfilePage extends ActionPage implements IProfilePage
{
    /**
     * @var \ProfilePresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('EditProfile');
        $this->presenter = new ProfilePresenter(
            $this,
            new UserRepository(),
            new AttributeService(new AttributeRepository())
        );
    }

    public function ProcessPageLoad()
    {
        $this->Set('RequirePhone', Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_PHONE, new BooleanConverter()));
        $this->Set('RequirePosition', Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_POSITION, new BooleanConverter()));
        $this->Set('RequireOrganization', Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_REQUIRE_ORGANIZATION, new BooleanConverter()));

        $this->presenter->PageLoad();
        $this->Display('MyAccount/profile.tpl');
    }

    public function SetFirstName($firstName)
    {
        $this->Set('FirstName', $firstName);
    }

    public function SetEmail($email)
    {
        $this->Set('Email', $email);
    }

    public function SetHomepage($homepageId)
    {
        $this->Set('Homepage', $homepageId);
    }

    public function SetLastName($lastName)
    {
        $this->Set('LastName', $lastName);
    }

    public function SetTimezone($timezoneName)
    {
        $this->Set('Timezone', $timezoneName);
    }

    public function SetHomepages($homepageValues, $homepageOutput)
    {
        $this->Set('HomepageValues', $homepageValues);
        $this->Set('HomepageOutput', $homepageOutput);
    }

    public function SetTimezones($timezoneValues, $timezoneOutput)
    {
        $this->Set('TimezoneValues', $timezoneValues);
        $this->Set('TimezoneOutput', $timezoneOutput);
    }

    public function SetUsername($username)
    {
        $this->Set('Username', $username);
    }

    public function GetEmail()
    {
        return $this->GetForm(FormKeys::EMAIL);
    }

    public function GetFirstName()
    {
        return $this->GetForm(FormKeys::FIRST_NAME);
    }

    public function GetLastName()
    {
        return $this->GetForm(FormKeys::LAST_NAME);
    }

    public function GetLoginName()
    {
        return $this->GetForm(FormKeys::USERNAME);
    }

    public function GetHomepage()
    {
        return $this->GetForm(FormKeys::DEFAULT_HOMEPAGE);
    }

    public function GetTimezone()
    {
        return $this->GetForm(FormKeys::TIMEZONE);
    }

    public function GetOrganization()
    {
        return $this->GetForm(FormKeys::ORGANIZATION);
    }

    public function GetPhone()
    {
        return $this->GetForm(FormKeys::PHONE);
    }

    public function GetPosition()
    {
        return $this->GetForm(FormKeys::POSITION);
    }

    public function SetOrganization($organization)
    {
        $this->Set('Organization', $organization);
    }

    public function SetPhone($phone)
    {
        $this->Set('Phone', $phone);
    }

    public function SetPosition($position)
    {
        $this->Set('Position', $position);
    }

    public function SetAttributes($attributes)
    {
        $this->Set('Attributes', $attributes);
    }

    public function GetAttributes()
    {
        return AttributeFormParser::GetAttributes($this->GetForm(FormKeys::ATTRIBUTE_PREFIX));
    }

    /**
     * @return void
     */
    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    public function GetDefaultSchedule()
    {
        return $this->server->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    /**
     * @param IAuthenticationActionOptions $options
     */
    public function SetAllowedActions($options)
    {
        $this->Set('AllowEmailAddressChange', $options->AllowEmailAddressChange());
        $this->Set('AllowNameChange', $options->AllowNameChange());
        $this->Set('AllowOrganizationChange', $options->AllowOrganizationChange());
        $this->Set('AllowPhoneChange', $options->AllowPhoneChange());
        $this->Set('AllowPositionChange', $options->AllowPositionChange());
        $this->Set('AllowUsernameChange', $options->AllowUsernameChange());
    }
}
