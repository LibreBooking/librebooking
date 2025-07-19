<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeTermsOfServiceRepository implements ITermsOfServiceRepository
{
    /**
     * @var TermsOfService
     */
    public $_Terms;

    public function __construct()
    {
        $this->_Terms = new TermsOfService(0, 'text', 'url', 'filename', TermsOfService::REGISTRATION);
    }

    public function Add(TermsOfService $terms)
    {
        // TODO: Implement Add() method.
        return null;
    }

    public function Load()
    {
        return $this->_Terms;
    }

    public function Delete()
    {
        // TODO: Implement Delete() method.
    }
}
