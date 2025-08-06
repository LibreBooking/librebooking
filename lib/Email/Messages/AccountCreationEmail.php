<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');

class AccountCreationEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var null|UserSession
     */
    private $userSession;

    public function __construct(User $user, $userSession = null)
    {
        $this->user = $user;
        $this->userSession = $userSession;
        parent::__construct(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE));
    }

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function To()
    {
        $userRepo = new UserRepository();
        $admins = $userRepo->GetApplicationAdmins();
        $emails = [];
        foreach ($admins as $admin) {
            $emails[] = new EmailAddress($admin->EmailAddress, $admin->FullName);
        }
        return $emails;
    }

    /**
     * @return string
     */
    public function Subject()
    {
        return $this->Translate('UserAdded');
    }

    /**
     * @return string
     */
    public function Body()
    {
        $this->Set('To', Configuration::Instance()->GetKey(ConfigKeys::ADMIN_EMAIL_NAME) ? Configuration::Instance()
                                                                                                        ->GetKey(ConfigKeys::ADMIN_EMAIL_NAME) : 'Administrator');
        $this->Set('FullName', $this->user->FullName());
        $this->Set('EmailAddress', $this->user->EmailAddress());
        $this->Set('Phone', $this->user->GetAttribute(UserAttribute::Phone));
        $this->Set('Organization', $this->user->GetAttribute(UserAttribute::Organization));
        $this->Set('Position', $this->user->GetAttribute(UserAttribute::Position));

        $this->Set('CreatedBy', '');
        if ($this->userSession != null && $this->userSession->UserId != $this->user->Id()) {
            $this->Set('CreatedBy', new FullName($this->userSession->FirstName, $this->userSession->LastName));
        }

        return $this->FetchTemplate('AccountCreation.tpl');
    }
}
