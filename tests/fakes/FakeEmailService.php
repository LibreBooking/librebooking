<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/namespace.php');

class FakeEmailService implements IEmailService
{
    /**
     * @var IEmailMessage[]
     */
    public $_Messages = [];

    /**
     * @var IEmailMessage
     */
    public $_LastMessage = null;

    /**
     * @param IEmailMessage $emailMessage
     * @see IEmailService::Send()
     */
    public function Send(IEmailMessage $emailMessage)
    {
        $this->_Messages[] = $emailMessage;
        $this->_LastMessage = $emailMessage;
    }
}
