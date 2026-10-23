<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class AccountActivationEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $activationCode;

    public function __construct(User $user, $activationCode)
    {
        $this->user = $user;
        $this->activationCode = $activationCode;
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
        return $this->Translate('ActivateYourAccount');
    }

    /**
     * @return string
     */
    public function Body()
    {
        $activationUrl = new Url(Configuration::Instance()->GetScriptUrl());
        $activationUrl
                ->Add(Pages::ACTIVATION)
                ->AddQueryString(QueryStringKeys::ACCOUNT_ACTIVATION_CODE, $this->activationCode);

        $this->Set('FirstName', self::EscapeHtml($this->user->FirstName()));
        $this->Set('EmailAddress', self::EscapeHtml($this->user->EmailAddress()));
        $this->Set('ActivationUrl', $activationUrl);
        return $this->FetchTemplate('AccountActivation.tpl');
    }
}
