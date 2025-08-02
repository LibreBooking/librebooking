<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class AccountCreationForUserEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var null|UserSession
     */
    private $userSession;

    /**
     * @var string
     */
    private $password;

    public function __construct(User $user, $password, $userSession = null)
    {
        $this->user = $user;
        $this->userSession = $userSession;
        $this->password = $password;
        parent::__construct(Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE));
    }

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function To()
    {
        return new EmailAddress($this->user->EmailAddress(), $this->user->FullName());
    }

    /**
     * @return string
     */
    public function Subject()
    {
        return $this->Translate('GuestAccountCreatedSubject', [Configuration::Instance()->GetKey(ConfigKeys::APP_TITLE)]);
    }

    /**
     * @return string
     */
    public function Body()
    {
        $this->Set('FullName', $this->user->FullName());
        $this->Set('EmailAddress', $this->user->EmailAddress());
        $this->Set('Phone', $this->user->GetAttribute(UserAttribute::Phone));
        $this->Set('Organization', $this->user->GetAttribute(UserAttribute::Organization));
        $this->Set('Position', $this->user->GetAttribute(UserAttribute::Position));
        $this->Set('Password', $this->password);
        $this->Set('AppTitle', Configuration::Instance()->GetKey(ConfigKeys::APP_TITLE));
        $this->Set('ScriptUrl', Configuration::Instance()->GetScriptUrl());
        $this->Set('CreatedBy', '');
        if ($this->userSession != null && $this->userSession->UserId != $this->user->Id()) {
            $this->Set('CreatedBy', new FullName($this->userSession->FirstName, $this->userSession->LastName));
        }

        return $this->FetchTemplate('AccountCreationForUser.tpl');
    }
}
