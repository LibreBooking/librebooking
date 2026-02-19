<?php

declare(strict_types=1);

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
        return 1;
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
