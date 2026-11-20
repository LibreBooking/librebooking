<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageEmailTemplatesPage.php');

class ManageEmailTemplatesPageTest extends TestBase
{
    public function tearDown(): void
    {
        // GetTemplateContents() reads the raw POST value, which FakeServer
        // does not replace.
        unset($_POST[FormKeys::EMAIL_CONTENTS]);

        parent::tearDown();
    }

    public function testUpdatedLanguageIsReturned(): void
    {
        $this->fakeServer->SetForm(FormKeys::EMAIL_TEMPLATE_LANGUAGE, 'cz');

        $this->assertSame('cz', $this->CreatePage()->GetUpdatedLanguage());
    }

    public function testMissingUpdatedLanguageIsNull(): void
    {
        $this->assertNull($this->CreatePage()->GetUpdatedLanguage());
    }

    public function testArrayUpdatedLanguageIsNull(): void
    {
        $this->fakeServer->SetForm(FormKeys::EMAIL_TEMPLATE_LANGUAGE, ['cz']);

        $this->assertNull($this->CreatePage()->GetUpdatedLanguage());
    }

    public function testTemplateContentsAreReturned(): void
    {
        $_POST[FormKeys::EMAIL_CONTENTS] = '<p>{$FirstName}</p>';

        $this->assertSame('<p>{$FirstName}</p>', $this->CreatePage()->GetTemplateContents());
    }

    public function testEmptyTemplateContentsAreAnEmptyString(): void
    {
        $_POST[FormKeys::EMAIL_CONTENTS] = '';

        $this->assertSame('', $this->CreatePage()->GetTemplateContents());
    }

    public function testMissingTemplateContentsAreNull(): void
    {
        $this->assertNull($this->CreatePage()->GetTemplateContents());
    }

    public function testArrayTemplateContentsAreNull(): void
    {
        $_POST[FormKeys::EMAIL_CONTENTS] = ['contents'];

        $this->assertNull($this->CreatePage()->GetTemplateContents());
    }

    private function CreatePage(): ManageEmailTemplatesPage
    {
        return new class () extends ManageEmailTemplatesPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
