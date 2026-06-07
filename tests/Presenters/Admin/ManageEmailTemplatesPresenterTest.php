<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Admin/ManageEmailTemplatesPresenter.php');

class ManageEmailTemplatesPresenterTest extends TestBase
{
    /**
     * @var FakeManageEmailTemplatesPage
     */
    private $page;

    /**
     * @var ManageEmailTemplatesPresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeManageEmailTemplatesPage();
        $this->presenter = new ManageEmailTemplatesPresenter($this->page);
    }

    public function testShowsAvailableTemplates()
    {
        $this->fileSystem->_Files = [
            '/some/path/help.tpl',
            '/some/path/help-admin.tpl',
            '/some/path/file1.tpl',
            '/some/path/file2.tpl',
            '/some/path/file2-custom.tpl'];

        $this->presenter->PageLoad();

        $this->assertEquals([new EmailTemplateFile('file1', 'file1.tpl'), new EmailTemplateFile('file2', 'file2.tpl')], $this->page->_BoundTemplateNames);
    }

    public function testLoadsRequestedTemplate()
    {
        $contents = '{* copyright
        copyright
        *}
        template contents here';

        $this->page->_TemplateName = 'file1.tpl';
        $this->page->_Language = 'en_us';
        $this->fileSystem->_Exists = false;
        $this->fileSystem->_ExpectedContents[Paths::EmailTemplates('en_us') . $this->page->_TemplateName] = $contents;

        $this->presenter->LoadTemplate();

        $this->assertEquals($contents, $this->page->_BoundTemplateContents);
    }

    public function testLoadsCustomTemplate()
    {
        $contents = 'contents';
        $this->page->_Language = 'en_us';
        $this->fileSystem->_Exists[Paths::EmailTemplates('en_us') . 'file1-custom.tpl'] = true;
        $this->page->_TemplateName = 'file1.tpl';

        $this->fileSystem->_ExpectedContents[Paths::EmailTemplates('en_us') . 'file1-custom.tpl'] = $contents;

        $this->presenter->LoadTemplate();

        $this->assertEquals($contents, $this->page->_BoundTemplateContents);
    }

    public function testUpdatesEmailTemplate()
    {
        $contents = 'new email contents';
        $templateName = 'template.tpl';
        $this->page->_UpdatedTemplateName = $templateName;
        $this->page->_TemplateContents = $contents;
        $this->page->_Language = 'en_us';
        $this->page->_UpdatedLanguage = 'cz';

        $this->presenter->UpdateEmailTemplate();

        $this->assertEquals($contents, $this->fileSystem->_AddedFileContents);
        $this->assertEquals(Paths::EmailTemplates('cz'), $this->fileSystem->_AddedFilePath);
        $this->assertEquals('template-custom.tpl', $this->fileSystem->_AddedFileName);
    }

    public function testRejectsUpdateWithPathTraversalTemplateName()
    {
        // PoC vector: the file name traverses out of the lang directory.
        $this->page->_UpdatedTemplateName = '../../Web/lb_shell.php';
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertNull($this->fileSystem->_AddedFileContents);
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithTraversalEndingInTpl()
    {
        // Ends in .tpl, so this is blocked specifically by the traversal checks.
        $this->page->_UpdatedTemplateName = '../../Web/shell.tpl';
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithControlCharactersInTemplateName()
    {
        // Ends in .tpl with no traversal, but the embedded newline must be rejected
        // (log forging / control-character filename).
        $this->page->_UpdatedTemplateName = "welcome\ninjected.tpl";
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithUppercaseTplExtension()
    {
        // Uppercase extension would not be rewritten to -custom.tpl by the
        // case-sensitive save path, so it must be rejected outright.
        $this->page->_UpdatedTemplateName = 'WELCOME.TPL';
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithoutTplExtension()
    {
        $this->page->_UpdatedTemplateName = 'shell.php';
        $this->page->_TemplateContents = '<?php ?>';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithSlashInTemplateName()
    {
        $this->page->_UpdatedTemplateName = 'subdir/template.tpl';
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }

    public function testRejectsUpdateWithBackslashInTemplateName()
    {
        $this->page->_UpdatedTemplateName = '..\\..\\Web\\shell.php.tpl';
        $this->page->_TemplateContents = 'contents';
        $this->page->_UpdatedLanguage = 'en_us';

        $this->presenter->UpdateEmailTemplate();

        $this->assertNull($this->fileSystem->_AddedFileName, 'no file should be written');
        $this->assertFalse($this->page->_SaveResult);
    }
}

class FakeManageEmailTemplatesPage extends ManageEmailTemplatesPage
{
    public $_BoundTemplateNames = [];
    public $_TemplateName;
    public $_UpdatedTemplateName;
    public $_BoundTemplateContents;
    public $_TemplateContents;
    public $_Language;
    public $_UpdatedLanguage;
    public $_SaveResult = true;

    public function BindTemplateNames($templates)
    {
        $this->_BoundTemplateNames = $templates;
    }

    public function BindTemplate($templateContents)
    {
        $this->_BoundTemplateContents = $templateContents;
    }

    public function GetTemplateName()
    {
        return $this->_TemplateName;
    }

    public function GetUpdatedTemplateName()
    {
        return $this->_UpdatedTemplateName;
    }

    public function GetTemplateContents()
    {
        return $this->_TemplateContents;
    }

    public function GetLanguage()
    {
        return $this->_Language;
    }

    public function GetUpdatedLanguage(): string
    {
        return $this->_UpdatedLanguage;
    }

    public function SetSaveResult($saveResult)
    {
        $this->_SaveResult = $saveResult;
    }
}
