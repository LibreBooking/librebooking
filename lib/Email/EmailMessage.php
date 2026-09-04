<?php

abstract class EmailMessage implements IEmailMessage
{
    /**
     * @var SmartyPage
     */
    protected $email;

    protected \LibreBooking\Common\Templating\TemplateRenderer $renderer;
    /**
     * @var string|null
     */
    private $attachmentContents;
    /**
     * @var string|null
     */
    private $attachmentFileName;
    private ?string $attachmentMimeType = null;

    protected bool $enforceCustomTemplate;

    protected function __construct($languageCode = null)
    {
        $this->enforceCustomTemplate = Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENFORCE_CUSTOM_TEMPLATE, new BooleanConverter());
        $resources = Resources::GetInstance();
        if (!empty($languageCode)) {
            $resources->SetLanguage($languageCode); // switch BEFORE renderer is created
        }
        $this->renderer = new TwigRenderer($resources);
        // BC alias: kept for subclasses that call $this->email->FetchLocalized() directly
        // (e.g. ReportEmailMessage). Shares the same $resources instance so language is in sync.
        $this->email = (new SmartyRenderer($resources))->smarty();
        if (!empty($languageCode)) {
            $this->Set('CurrentLanguage', $resources->CurrentLanguage);
        }
        $this->Set('ScriptUrl', Configuration::Instance()->GetScriptUrl());
        $this->Set('Charset', $resources->Charset);
        $appTitle = Configuration::Instance()->GetKey(ConfigKeys::APP_TITLE);
        $this->Set('AppTitle', (empty($appTitle) ? 'LibreBooking' : $appTitle));
    }

    protected function Set($var, $value)
    {
        $this->renderer->assign($var, $value);
        // Keep BC alias in sync so subclasses using $this->email see the same variables.
        $this->email->assign($var, $value);
    }

    /**
     * Escapes user-provided text for the HTML email body. Profile data is stored either
     * HTML-encoded or raw, so existing entities are not encoded a second time.
     */
    public static function EscapeHtml(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
    }

    protected function FetchTemplate($templateName, $includeHeaders = true)
    {
        $header = $includeHeaders ? $this->renderer->fetch('Email/emailheader.tpl') : '';
        $body = $this->renderer->fetchLocalized($templateName, $this->enforceCustomTemplate);
        $footer = $includeHeaders ? $this->renderer->fetch('Email/emailfooter.tpl') : '';

        return $header . $body . $footer;
    }

    protected function Translate($key, $args = [])
    {
        if (!is_array($args)) {
            $args = [$args];
        }
        $resources = Resources::GetInstance();
        if (empty($args)) {
            return $resources->GetString($key, '');
        }
        return $resources->GetString($key, $args);
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
        return $this->renderer->getTemplateVars('Charset');
    }

    public function AddStringAttachment($contents, $fileName, ?string $mimeType = null)
    {
        $this->attachmentContents = $contents;
        $this->attachmentFileName = $fileName;
        $this->attachmentMimeType = $mimeType;
    }

    public function HasStringAttachment()
    {
        return !empty($this->attachmentContents);
    }

    public function RemoveStringAttachment()
    {
        $this->attachmentContents = null;
        $this->attachmentFileName = null;
        $this->attachmentMimeType = null;
    }

    public function AttachmentContents()
    {
        return $this->attachmentContents;
    }

    public function AttachmentFileName()
    {
        return $this->attachmentFileName;
    }

    public function AttachmentMimeType(): ?string
    {
        return $this->attachmentMimeType;
    }
}
