<?php

declare(strict_types=1);

@define('BASE_DIR', dirname(__FILE__) . '/../..');
require_once(ROOT_DIR . 'lib/Email/namespace.php');

use PHPMailer\PHPMailer\PHPMailer;

class FakeMailer extends PHPMailer
{
    public $addresses = [];
    public $Subject = null;
    public $Body = null;
    public $sendWasCalled = false;
    public $isHtml = true;

    public function __construct()
    {
    }

    public function AddAddress($address, $name = '')
    {
        $this->addresses[] = $address;
        return true;
    }

    public function Send()
    {
        $this->sendWasCalled = true;
        return true;
    }

    public function IsHTML($bool = true)
    {
        $this->isHtml = $bool;
    }
}
