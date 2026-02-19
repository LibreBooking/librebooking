<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Requests/CustomAttributes/CustomAttributeRequest.php');
require_once(ROOT_DIR . 'WebServices/Controllers/AttributeSaveController.php');

class AttributeSaveControllerTest extends TestBase
{
    /**
     * @var AttributeSaveController
     */
    private $controller;

    /**
     * @var FakeAttributeRepository
     */
    private $attributeRepository;
    private $session;

    public function setUp(): void
    {
        parent::setup();

        $this->attributeRepository = new FakeAttributeRepository();
        $this->controller = new AttributeSaveController($this->attributeRepository);
        $this->session = new FakeWebServiceUserSession(1);
    }

    public function testAddsAttribute()
    {
        $request = new CustomAttributeRequest();
        $request->label = 'attributename';
        $request->type = CustomAttributeTypes::SELECT_LIST;
        $request->categoryId = CustomAttributeCategory::USER;
        $request->regex = 'regex';
        $request->required = true;
        $request->possibleValues = '1,2,3';
        $request->sortOrder = 9;
        $request->appliesToIds = [100];

        $result = $this->controller->Create($request, $this->session);

        $this->assertEquals(true, $result->WasSuccessful());
        $this->assertEquals($this->attributeRepository->_LastCreateId, $result->AttributeId());

        $expected = CustomAttribute::Create(
            $request->label,
            $request->type,
            $request->categoryId,
            $request->regex,
            $request->required,
            $request->possibleValues,
            $request->sortOrder,
            $request->appliesToIds,
            $request->adminOnly
        );
        $expected->WithIsPrivate($request->isPrivate);
        $this->assertEquals($expected, $this->attributeRepository->_Added);
    }

    public function testWhenAddRequestIsInvalid()
    {
        $request = new CustomAttributeRequest();

        $result = $this->controller->Create($request, $this->session);

        $this->assertEquals(false, $result->WasSuccessful());
    }

    public function testUpdatesAttribute()
    {
        $attributeId = 123;
        $request = new CustomAttributeRequest();
        $request->label = 'attributename';
        $request->type = CustomAttributeTypes::SELECT_LIST;
        $request->categoryId = CustomAttributeCategory::USER;
        $request->regex = 'regex';
        $request->required = true;
        $request->possibleValues = '1,2,3';
        $request->sortOrder = 9;
        $request->appliesToIds = [100];

        $result = $this->controller->Update($attributeId, $request, $this->session);

        $this->assertEquals(true, $result->WasSuccessful());
        $this->assertEquals($attributeId, $result->AttributeId());

        $expected = new CustomAttribute(
            $attributeId,
            $request->label,
            $request->type,
            $request->categoryId,
            $request->regex,
            $request->required,
            $request->possibleValues,
            $request->sortOrder,
            $request->appliesToIds
        );

        $this->assertEquals($expected, $this->attributeRepository->_Updated);
    }

    public function testWhenUpdateRequestIsInvalid()
    {
        $request = new CustomAttributeRequest();

        $result = $this->controller->Update(1, $request, $this->session);

        $this->assertEquals(false, $result->WasSuccessful());
    }

    public function testDeletesAttribute()
    {
        $result = $this->controller->Delete(1, $this->session);

        $this->assertEquals(true, $result->WasSuccessful());
        $this->assertEquals(1, $result->AttributeId());
    }
}
