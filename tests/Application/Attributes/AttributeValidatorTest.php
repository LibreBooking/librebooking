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
}
