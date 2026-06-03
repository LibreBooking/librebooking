<?php

declare(strict_types=1);

class FakePluginManager extends PluginManager implements IPostRegistration
{
    public function __construct()
    {
    }

    public $preResPlugin = null;
    public $postResPlugin = null;
    public $postRegistrationPlugin = null;
    public $_LoadedRegistration = false;
    public $_RegistrationUser = null;
    public $_RegistrationPage;

    public function LoadPreReservation()
    {
        return ($this->preResPlugin == null) ? $this : $this->preResPlugin;
    }

    public function LoadPostReservation()
    {
        return ($this->postResPlugin == null) ? $this : $this->postResPlugin;
    }

    public function LoadPostRegistration()
    {
        $this->_LoadedRegistration = true;
        return ($this->postRegistrationPlugin == null) ? $this : $this->postRegistrationPlugin;
    }

    public function HandleSelfRegistration(User $user, IRegistrationPage $page, ILoginContext $loginContext)
    {
        $this->_RegistrationUser = $user;
        $this->_RegistrationPage = $page;
    }


    public function CreatePreUpdateService()
    {
        return null;
    }

    public function CreatePostUpdateService()
    {
        return null;
    }
}
