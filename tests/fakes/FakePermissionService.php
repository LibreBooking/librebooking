<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

class FakePermissionService implements IPermissionService
{
    /**
     * @var array|IPermissibleResource[]
     */
    public $Resources;

    /**
     * @var UserSession
     */
    public $User;

    /**
     * @var array|bool[]
     */
    public $ReturnValues = [];

    private $_invocationCount = 0;
    public $_CanBookResource = false;
    /**
     * @var bool|bool[]
     */
    public $_CanViewResource = false;

    /**
     * @param $returnValues array|bool[]
     */
    public function __construct($returnValues = [])
    {
        $this->ReturnValues = $returnValues;
    }

    public function CanAccessResource(IPermissibleResource $resource, UserSession $user)
    {
        $this->Resources[] = $resource;
        $this->User = $user;

        return $this->ReturnValues[$this->_invocationCount++];
    }

    public function CanBookResource(IPermissibleResource $resource, UserSession $user)
    {
        return $this->_CanBookResource;
    }

    public function CanViewResource(IPermissibleResource $resource, UserSession $user)
    {
        if (is_array($this->_CanViewResource)) {
            return $this->_CanViewResource[$resource->GetResourceId()];
        }
        return $this->_CanViewResource;
    }
}

class FakePermissionServiceFactory implements IPermissionServiceFactory
{
    /**
     * @var IPermissionService
     */
    public $service;

    /**
     * @return IPermissionService
     */
    public function GetPermissionService()
    {
        return ($this->service == null) ? new FakePermissionService() : $this->service;
    }
}
