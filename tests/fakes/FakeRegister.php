<?php

class FakeRegistration implements IRegistration
{
    public $_RegisterCalled = false;
    public $_UserExists = true;
    public $_ExistsCalled = false;
    public $_SynchronizeCalled = false;
    public $_LastLogin;
    public $_LastEmail;
    public $_Login;
    public $_Email;
    public $_First;
    public $_Last;
    public $_Password;
    public $_Timezone;
    public $_AdditionalFields;
    public $_HomepageId;
    /**
     * @var AuthenticatedUser
     */
    public $_LastSynchronizedUser;
    public $_RegisteredUser;
    public $_TermsAccepted;
    public $_Groups = [];
    public $_Language;

    public function __construct()
    {
        $this->_RegisteredUser = new FakeUser(1);
    }
    /**
     * @var array|AttributeValue[]
     */
    public $_AttributeValues = [];

    public function Register($login, $email, $firstName, $lastName, $password, $timezone, $language, $homepageId, $additionalFields = [], $attributes = [], $groups = [], $acceptTerms = false)
    {
        $this->_RegisterCalled = true;
        $this->_Login = $login;
        $this->_Email = $email;
        $this->_First = $firstName;
        $this->_Last = $lastName;
        $this->_Password = $password;
        $this->_Timezone = $timezone;
        $this->_HomepageId = $homepageId;
        $this->_AdditionalFields = $additionalFields;
        $this->_AttributeValues = $attributes;
        $this->_Groups = $groups;
        $this->_TermsAccepted = $acceptTerms;
        $this->_Language = $language;

        return $this->_RegisteredUser;
    }

    public function UserExists($loginName, $emailAddress)
    {
        $this->_ExistsCalled = true;
        $this->_LastLogin = $loginName;
        $this->_LastEmail = $emailAddress;

        return $this->_UserExists;
    }

    public function Synchronize(AuthenticatedUser $user, $insertOnly = false, $overwritePassword = true)
    {
        $this->_SynchronizeCalled = true;
        $this->_LastSynchronizedUser = $user;
    }
}
