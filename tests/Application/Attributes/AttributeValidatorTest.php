<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

class AttributeValidatorTest extends TestBase
{
    public function testChecksAttributesAgainstService()
    {
        $service = $this->createMock('IAttributeService');
        $category = CustomAttributeCategory::RESOURCE;
        $attributes = ['abc'];
        $entityId = 123;

        $errors = ['error1', 'error2'];

        $serviceResult = new AttributeServiceValidationResult(false, $errors);

        $service->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo($category), $this->equalTo($attributes), $this->equalTo($entityId))
                ->willReturn($serviceResult);

        $validator = new AttributeValidator($service, $category, $attributes, $entityId);
        $validator->Validate();

        $this->assertFalse($validator->IsValid());
        $this->assertEquals($errors, $validator->Messages());
    }

    public function testInlineValidatorChecksAttributeAgainstService(): void
    {
        $service = $this->createMock(IAttributeService::class);
        $category = CustomAttributeCategory::RESOURCE;
        $attribute = new AttributeValue(attributeId: '1', value: 'value');
        $entityId = 123;

        $service->expects($this->once())
                ->method('Validate')
                ->with($this->equalTo($category), $this->equalTo([$attribute]), $this->equalTo($entityId))
                ->willReturn(new AttributeServiceValidationResult(true, []));

        $validator = new AttributeValidatorInline(
            service: $service,
            category: $category,
            attribute: $attribute,
            entityId: $entityId,
        );
        $validator->Validate();

        $this->assertTrue($validator->IsValid());
    }

    public function testInlineValidatorFailsWithoutAttribute(): void
    {
        $service = $this->createMock(IAttributeService::class);
        $service->expects($this->never())
                ->method('Validate');

        $validator = new AttributeValidatorInline(
            service: $service,
            category: CustomAttributeCategory::RESOURCE,
            attribute: null,
            entityId: 123,
        );
        $validator->Validate();

        $this->assertFalse($validator->IsValid());
        $this->assertSame(['CustomAttributeInvalidAttribute'], $validator->Messages());
    }
}
