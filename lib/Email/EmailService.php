<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService implements IEmailService
{
    /**
     * @var PHPMailer
     */
    private $phpMailer;

    public function __construct($phpMailer = null)
    {
        if (is_null($phpMailer)) {
            $phpMailer = new PHPMailer(true); // true para usar excepciones

            $phpMailer->isHTML(true);
            $phpMailer->CharSet = 'UTF-8';

            $mailer = $this->Config('mailer');
            if ($mailer === 'smtp') {
                $phpMailer->isSMTP();
            } elseif ($mailer === 'sendmail') {
                $phpMailer->isSendmail();
                $phpMailer->Sendmail = $this->Config('sendmail.path');
            }

            $phpMailer->Host = $this->Config('smtp.host');
            $phpMailer->Port = $this->Config('smtp.port', new IntConverter());
            $phpMailer->SMTPSecure = $this->Config('smtp.secure');
            $phpMailer->SMTPAuth = $this->Config('smtp.auth', new BooleanConverter());
            $phpMailer->Username = $this->Config('smtp.username');
            $phpMailer->Password = $this->Config('smtp.password');
            $phpMailer->SMTPDebug = $this->Config('smtp.debug', new BooleanConverter());
        }

        $this->phpMailer = $phpMailer;
    }

    public function Send(IEmailMessage $emailMessage)
    {
        $this->phpMailer->clearAllRecipients();
        $this->phpMailer->clearReplyTos();
        $this->phpMailer->CharSet = $emailMessage->Charset();
        $this->phpMailer->Subject = $emailMessage->Subject();
        $this->phpMailer->Body = $emailMessage->Body();

        $from = $emailMessage->From();
        $defaultFrom = Configuration::Instance()->GetSectionKey(ConfigSection::EMAIL, ConfigKeys::DEFAULT_FROM_ADDRESS);
        $defaultName = Configuration::Instance()->GetSectionKey(ConfigSection::EMAIL, ConfigKeys::DEFAULT_FROM_NAME);
        $address = empty($defaultFrom) ? $from->Address() : $defaultFrom;
        $name = empty($defaultName) ? $from->Name() : $defaultName;
        $this->phpMailer->setFrom($address, $name);

        $replyTo = $emailMessage->ReplyTo();
        $this->phpMailer->addReplyTo($replyTo->Address(), $replyTo->Name());

        $to = $this->ensureArray($emailMessage->To());
        $toAddresses = new StringBuilder();
        foreach ($to as $address) {
            $toAddresses->Append($address->Address());
            $this->phpMailer->addAddress($address->Address(), $address->Name());
        }

        $cc = $this->ensureArray($emailMessage->CC());
        foreach ($cc as $address) {
            $this->phpMailer->addCC($address->Address(), $address->Name());
        }

        $bcc = $this->ensureArray($emailMessage->BCC());
        foreach ($bcc as $address) {
            $this->phpMailer->addBCC($address->Address(), $address->Name());
        }

        if ($emailMessage->HasStringAttachment()) {
            Log::Debug('Adding email attachment %s', $emailMessage->AttachmentFileName());
            $this->phpMailer->addStringAttachment($emailMessage->AttachmentContents(), $emailMessage->AttachmentFileName());
        }

        Log::Debug('Sending %s email to: %s from: %s', get_class($emailMessage), $toAddresses->ToString(), $this->phpMailer->From);

        $success = false;
        try {
            $success = $this->phpMailer->send();
        } catch (Exception $ex) {
            Log::Error('Failed sending email. Exception: %s', $ex);
        }

        Log::Debug('Email send success: %d. %s', $success, $this->phpMailer->ErrorInfo);
    }

    /**
     * @param $key
     * @param IConvert|null $converter
     * @return mixed|string
     */
    private function Config($key, $converter = null)
    {
        return Configuration::Instance()->GetSectionKey('phpmailer', $key, $converter);
    }

    /**
     * @param $possibleArray array|EmailAddress[]
     * @return array|EmailAddress[]
     */
    private function ensureArray($possibleArray)
    {
        if (is_array($possibleArray)) {
            return $possibleArray;
        }

        return [$possibleArray];
    }
}

class NullEmailService implements IEmailService
{
    /**
     * @param IEmailMessage $emailMessage
     */
    public function Send(IEmailMessage $emailMessage)
    {
        // no-op
    }
}
