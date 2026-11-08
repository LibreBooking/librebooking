<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');

class ForgotPasswordEmail extends EmailMessage
{
    /**
     * @var \User
     */
    private $user;

    /**
     * @var string
     */
    private $temporaryPassword;

    public function __construct(User $user, $temporaryPassword)
    {
        parent::__construct($user->Language());

        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function To(): EmailAddress
    {
        return new EmailAddress($this->user->EmailAddress(), $this->user->FullName());
    }

    /**
     * @return string
     */
    public function Subject()
    {
        return $this->Translate('ResetPasswordRequest');
    }

    /**
     * @return string
     */
    public function Body()
    {
        $this->Set('TemporaryPassword', $this->temporaryPassword);
        return $this->FetchTemplate('ResetPassword.tpl');
    }
}
