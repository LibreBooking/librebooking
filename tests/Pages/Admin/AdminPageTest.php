<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');

class AdminPageTest extends TestBase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->fakeUser->IsAdmin = true;
    }

    public function testIsValidWhenAllValidatorsPass(): void
    {
        $page = new AdminPageUnderTest();
        $page->RegisterValidator('name', new AdminPageTestValidator(isValid: true));

        $this->assertTrue($page->IsValid());
        $this->assertNull($page->json);
    }

    public function testFailedValidatorsAreReportedByTheirId(): void
    {
        $page = new AdminPageUnderTest();
        $page->RegisterValidator('name', new AdminPageTestValidator(isValid: false, message: 'name is required'));
        $page->RegisterValidator('email', new AdminPageTestValidator(isValid: true));
        $page->RegisterValidator('phone', new AdminPageTestValidator(isValid: false, message: 'phone is invalid'));

        $this->assertFalse($page->IsValid());

        $this->assertInstanceOf(ActionErrors::class, $page->json);
        $this->assertSame(['name', 'phone'], $page->json->ErrorIds);
        $this->assertSame(
            ['name' => ['name is required'], 'phone' => ['phone is invalid']],
            $page->json->Messages
        );
    }

    public function testValidatorsReturningAnErrorResponseAreReportedInline(): void
    {
        $page = new AdminPageUnderTest();
        $page->RegisterValidator(
            'name',
            new AdminPageTestValidator(isValid: false, message: 'name is required', returnsErrorResponse: true)
        );

        $this->assertFalse($page->IsValid());
        $this->assertSame('name is required', $page->json);
    }
}

class AdminPageUnderTest extends AdminPage
{
    public mixed $json = null;

    public function PageLoad()
    {
    }

    protected function SetJson($objectToSerialize, ?array $error = null, $httpResponseCode = 200)
    {
        $this->json = $objectToSerialize;
    }
}

class AdminPageTestValidator extends ValidatorBase
{
    public function __construct(
        bool $isValid,
        string $message = '',
        private bool $returnsErrorResponse = false,
    ) {
        $this->isValid = $isValid;
        if ($message !== '') {
            $this->AddMessage($message);
        }
    }

    public function Validate()
    {
    }

    public function ReturnsErrorResponse()
    {
        return $this->returnsErrorResponse;
    }
}
