<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/AttributesWriteWebService.php');

class AttributesWriteWebServiceTest extends TestBase
{
    private AttributesWriteWebService $service;
    private FakeRestServer $server;
    private FakeAttributeRepository $attributeRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->server = new FakeRestServer();
        $this->attributeRepository = new FakeAttributeRepository();
        $this->service = new AttributesWriteWebService(
            $this->server,
            new AttributeSaveController($this->attributeRepository)
        );
    }

    public function testCreatesFreeTextAttributeFromDecodedJson(): void
    {
        $this->server->SetRequest(json_decode(
            '{"label":"Purpose","type":1,"categoryId":1}',
            false,
            512,
            JSON_THROW_ON_ERROR
        ));

        $this->service->Create();

        $expected = CustomAttribute::Create(
            'Purpose',
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::RESERVATION,
            '',
            0,
            null,
            null,
            [],
            null
        );
        $expected->WithSecondaryEntities(null, null);
        $expected->WithIsPrivate(null);

        $this->assertEquals($expected, $this->attributeRepository->_Added);
        $this->assertEquals(
            new CustomAttributeCreatedResponse($this->server, $this->attributeRepository->_LastCreateId),
            $this->server->_LastResponse
        );
        $this->assertSame(RestResponse::CREATED_CODE, $this->server->_LastResponseCode);
    }

    public function testCreatesSelectListWithOptionalFieldsFromDecodedJson(): void
    {
        $this->server->SetRequest(json_decode(
            '{"label":"Size","type":3,"categoryId":1,"regex":"/^(Small|Large)$/",'
            . '"required":true,"possibleValues":["Small","Large"],"sortOrder":9,'
            . '"adminOnly":true,"isPrivate":true,"secondaryCategoryId":4,"secondaryEntityIds":[41,42]}',
            false,
            512,
            JSON_THROW_ON_ERROR
        ));

        $this->service->Create();

        $expected = CustomAttribute::Create(
            'Size',
            CustomAttributeTypes::SELECT_LIST,
            CustomAttributeCategory::RESERVATION,
            '/^(Small|Large)$/',
            true,
            'Small,Large',
            9,
            [],
            true
        );
        $expected->WithSecondaryEntities(CustomAttributeCategory::RESOURCE, [41, 42]);
        $expected->WithIsPrivate(true);

        $this->assertEquals($expected, $this->attributeRepository->_Added);
        $this->assertSame(RestResponse::CREATED_CODE, $this->server->_LastResponseCode);
    }

    public function testUpdatesFreeTextAttributeFromDecodedJsonWithDefaults(): void
    {
        $attributeId = 123;
        $this->server->SetRequest(json_decode(
            '{"label":"Department","type":1,"categoryId":2}',
            false,
            512,
            JSON_THROW_ON_ERROR
        ));

        $this->service->Update($attributeId);

        $expected = new CustomAttribute(
            $attributeId,
            'Department',
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::USER,
            null,
            null,
            null,
            null,
            [],
            null
        );
        $expected->WithIsPrivate(null);

        $this->assertEquals($expected, $this->attributeRepository->_Updated);
        $this->assertEquals(new CustomAttributeUpdatedResponse($this->server, $attributeId), $this->server->_LastResponse);
        $this->assertSame(RestResponse::CREATED_CODE, $this->server->_LastResponseCode);
    }

    public function testUpdatesAttributeWithOptionalFieldsFromDecodedJson(): void
    {
        $attributeId = 123;
        $this->server->SetRequest(json_decode(
            '{"label":"Department","type":3,"categoryId":2,"regex":"/^(Sales|Support)$/",'
            . '"required":false,"possibleValues":"Sales,Support","sortOrder":9,"appliesToIds":[100],'
            . '"adminOnly":true,"isPrivate":true,"secondaryCategoryId":null,"secondaryEntityIds":null}',
            false,
            512,
            JSON_THROW_ON_ERROR
        ));

        $this->service->Update($attributeId);

        $expected = new CustomAttribute(
            $attributeId,
            'Department',
            CustomAttributeTypes::SELECT_LIST,
            CustomAttributeCategory::USER,
            '/^(Sales|Support)$/',
            false,
            'Sales,Support',
            9,
            [100],
            true
        );
        $expected->WithIsPrivate(true);

        $this->assertEquals($expected, $this->attributeRepository->_Updated);
        $this->assertSame(RestResponse::CREATED_CODE, $this->server->_LastResponseCode);
    }

    public function testRejectsInvalidCreateFromDecodedJson(): void
    {
        $this->server->SetRequest(json_decode('{"type":1,"categoryId":1}', false, 512, JSON_THROW_ON_ERROR));

        $this->service->Create();

        $this->assertNull($this->attributeRepository->_Added);
        $this->assertEquals(new FailedResponse(['label is required']), $this->server->_LastResponse);
        $this->assertSame(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }

    public function testRejectsInvalidUpdateFromDecodedJson(): void
    {
        $this->server->SetRequest(json_decode('{"type":1,"categoryId":2}', false, 512, JSON_THROW_ON_ERROR));

        $this->service->Update(123);

        $this->assertNull($this->attributeRepository->_Updated);
        $this->assertEquals(new FailedResponse(['label is required']), $this->server->_LastResponse);
        $this->assertSame(RestResponse::BAD_REQUEST_CODE, $this->server->_LastResponseCode);
    }
}
