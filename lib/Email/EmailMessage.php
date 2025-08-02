<?php

abstract class EmailMessage implements IEmailMessage
{
    /**
     * @var SmartyPage
     */
    protected $email;
    /**
     * @var string|null
     */
    private $attachmentContents;
    /**
     * @var string|null
     */
    private $attachmentFileName;

    protected bool $enforceCustomTemplate;

    protected function __construct($languageCode = null)
    {
        $this->enforceCustomTemplate = Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENFORCE_CUSTOM_TEMPLATE, new BooleanConverter());
        $resources = Resources::GetInstance();
        $this->email = new SmartyPage($resources);
        if (!empty($languageCode)) {
            $resources->SetLanguage($languageCode);
            $this->Set('CurrentLanguage', $languageCode);
        }

        $this->Set('ScriptUrl', Configuration::Instance()->GetScriptUrl());
        $this->Set('Charset', $resources->Charset);
        $appTitle = Configuration::Instance()->GetKey(ConfigKeys::APP_TITLE);
        $this->Set('AppTitle', (empty($appTitle) ? 'LibreBooking' : $appTitle));
    }

    protected function Set($var, $value)
    {
        $this->email->assign($var, $value);
    }

    protected function FetchTemplate($templateName, $includeHeaders = true)
    {
        $header = $includeHeaders ? $this->email->fetch('Email/emailheader.tpl') : '';
        $body = $this->email->FetchLocalized($templateName, $this->enforceCustomTemplate);
        $footer = $includeHeaders ? $this->email->fetch('Email/emailfooter.tpl') : '';

        return $header . $body . $footer;
    }

    protected function Translate($key, $args = [])
    {
        if (!is_array($args)) {
            $args = [$args];
        }
        return $this->email->SmartyTranslate(['key' => $key, 'args' => implode(',', $args)], $this->email);
    }

    public function ReplyTo()
    {
        return $this->From();
    }

    public function From()
    {
        return new EmailAddress(Configuration::Instance()->GetAdminEmail(), Configuration::Instance()->GetKey(ConfigKeys::ADMIN_EMAIL_NAME));
    }

    public function CC()
    {
        return [];
    }

    public function BCC()
    {
        return [];
    }

    public function Charset()
    {
        return $this->email->getTemplateVars('Charset');
    }

    public function AddStringAttachment($contents, $fileName)
    {
        $this->attachmentContents = $contents;
        $this->attachmentFileName = $fileName;
    }

    public function HasStringAttachment()
    {
        return !empty($this->attachmentContents);
    }

    public function RemoveStringAttachment()
    {
        $this->attachmentContents = null;
        $this->attachmentFileName = null;
    }

    public function AttachmentContents()
    {
        return $this->attachmentContents;
    }

    public function AttachmentFileName()
    {
        return $this->attachmentFileName;
    }
}
