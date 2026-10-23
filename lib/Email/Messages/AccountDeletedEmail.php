<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class AccountDeletedEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $deletedUser;
    /**
     * @var UserDto
     */
    private $to;
    /**
     * @var UserSession
     */
    private $userSession;

    public function __construct(User $deletedUser, UserDto $to, UserSession $userSession)
    {
        parent::__construct($to->LanguageCode);

        $this->deletedUser = $deletedUser;
        $this->to = $to;
        $this->userSession = $userSession;
    }

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function To()
    {
        return new EmailAddress($this->to->EmailAddress(), $this->to->FullName());
    }

    /**
     * @return string
     */
    public function Subject()
    {
        return $this->Translate('UserDeleted', [$this->deletedUser->FullName(), new FullName($this->userSession->FirstName, $this->userSession->LastName)]);
    }

    /**
     * @return string
     */
    public function Body()
    {
        $this->Set('UserFullName', self::EscapeHtml($this->deletedUser->FullName()));
        $this->Set('AdminFullName', self::EscapeHtml(new FullName($this->userSession->FirstName, $this->userSession->LastName)));
        return $this->FetchTemplate('AccountDeleted.tpl');
    }
}
