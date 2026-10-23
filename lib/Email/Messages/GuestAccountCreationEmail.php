<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class GuestAccountCreationEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;

        parent::__construct($user->Language());
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
        $this->Set('EmailAddress', self::EscapeHtml($this->user->EmailAddress()));
        $this->Set('Password', $this->password);
        return $this->FetchTemplate('GuestAccountCreation.tpl');
    }
}
