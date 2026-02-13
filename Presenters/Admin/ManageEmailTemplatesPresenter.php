<?php

require_once(ROOT_DIR . 'Pages/Admin/ManageEmailTemplatesPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class EmailTemplatesActions
{
    public const Update = 'update';
}

class ManageEmailTemplatesPresenter extends ActionPresenter
{
    /**
     * @var IManageEmailTemplatesPage
     */
    private $page;

    /**
     * @var \Booked\FileSystem
     */
    private $filesystem;

    public function __construct(IManageEmailTemplatesPage $page)
    {
        parent::__construct($page);

        $this->filesystem = ServiceLocator::GetFileSystem();
        $this->page = $page;

        $this->AddAction(EmailTemplatesActions::Update, 'UpdateEmailTemplate');
    }

    public function PageLoad()
    {
        $path = $this->GetTemplatePath('en_us');
        $this->page->SetSelectedLanguage($this->GetSelectedLanguage());
        $this->page->BindTemplateNames(EmailTemplateFile::FromFiles($this->filesystem->GetFiles($path)));
    }

    /**
     * @param $language
     * @return string
     */
    private function GetTemplatePath($language)
    {
        $path = Paths::EmailTemplates($language);
        return $path;
    }

    /**
     * @return string
     */
    private function GetSelectedLanguage()
    {
        $language = $this->page->GetLanguage();
        if (empty($language)) {
            $language = Resources::GetInstance()->CurrentLanguage;
        }
        return $language;
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest == 'template') {
            $this->LoadTemplate();
        } elseif ($dataRequest == 'originalTemplate') {
            $this->LoadOriginalTemplate();
        }
    }

    private function RemoveComments($contents)
    {
        return preg_replace('/\{\*.+\*\}/', '', $contents);
    }

    public function LoadTemplate()
    {
        $templateName = strtolower($this->page->GetTemplateName());
        if (!BookedStringHelper::EndsWith($templateName, '.tpl')
            || BookedStringHelper::Contains($templateName, '..')
            || BookedStringHelper::Contains($templateName, '\\')
            || BookedStringHelper::Contains($templateName, '/')) {
            return '';
        }
        $templatePath = Paths::EmailTemplates($this->GetSelectedLanguage()) . $this->page->GetTemplateName();
        $customTemplatePath = str_replace('.tpl', '-custom.tpl', $templatePath);
        if ($this->filesystem->Exists($customTemplatePath)) {
            $contents = $this->filesystem->GetFileContents($customTemplatePath);
        } elseif ($this->filesystem->Exists($templatePath)) {
            $contents = $this->filesystem->GetFileContents($templatePath);
        } else {
            $defaultTemplatePath = Paths::EmailTemplates('en_us') . $this->page->GetTemplateName();
            $contents = $this->filesystem->GetFileContents($defaultTemplatePath);
        }
        $this->page->BindTemplate($this->RemoveComments($contents));
    }

    public function LoadOriginalTemplate()
    {
        $templateName = strtolower($this->page->GetTemplateName());
        if (!BookedStringHelper::EndsWith($templateName, '.tpl')
            || BookedStringHelper::Contains($templateName, '..')
            || BookedStringHelper::Contains($templateName, '\\')
            || BookedStringHelper::Contains($templateName, '/')) {
            return '';
        }
        $templatePath = Paths::EmailTemplates($this->GetSelectedLanguage()) . $this->page->GetTemplateName();
        $contents = $this->filesystem->GetFileContents($templatePath);
        $this->page->BindTemplate($this->RemoveComments($contents));
    }

    public function UpdateEmailTemplate()
    {
        $templateName = $this->page->GetUpdatedTemplateName();

        try {
            Log::Debug('Updating email template. Template=%s', $templateName);

            $templatePath = Paths::EmailTemplates($this->page->GetUpdatedLanguage());
            $saveResult = $this->filesystem->Save($templatePath, str_replace('.tpl', '-custom.tpl', $templateName), $this->page->GetTemplateContents());

            $this->filesystem->FlushSmartyCache();

            Log::Debug('Update email template results. Template=%s, Result=%s', $templateName, $saveResult);
            $this->page->SetSaveResult($saveResult);
        } catch (Exception $exception) {
            Log::Error('Error updating email template. Template=%s. %s', $templateName, $exception);
            $this->page->SetSaveResult(false);
        }
    }
}

class EmailTemplateFile
{
    private $name;
    private $fileName;

    public function __construct($name, $fileName)
    {
        $this->name = $name;
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function FileName()
    {
        return $this->fileName;
    }

    public static function FromPath($fullTemplatePath)
    {
        $parts = explode('/', $fullTemplatePath);
        $fileName = $parts[count($parts) - 1];
        return new EmailTemplateFile(str_replace('.tpl', '', $fileName), $fileName);
    }

    public static function FromFiles($templates)
    {
        $templateList = [];
        foreach ($templates as $template) {
            if (
                BookedStringHelper::Contains($template, 'help.tpl') ||
                BookedStringHelper::Contains($template, 'help-admin.tpl') ||
                BookedStringHelper::Contains($template, '-custom.tpl')
            ) {
                continue;
            }

            $templateList[] = EmailTemplateFile::FromPath($template);
        }

        return $templateList;
    }
}
