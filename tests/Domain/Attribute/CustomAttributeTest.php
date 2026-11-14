<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');

class CustomAttributeTest extends TestBase
{
    public function testChecksForRequiredValues()
    {
        $customAttributeRequired = CustomAttribute::Create('', 1, 1, null, true, null, 0);
        $customAttributeNotRequired = CustomAttribute::Create('', 1, 1, null, false, null, 0);

        $this->assertFalse($customAttributeRequired->SatisfiesRequired(''));
        $this->assertFalse($customAttributeRequired->SatisfiesRequired(' '));
        $this->assertFalse($customAttributeRequired->SatisfiesRequired("\t"));
        $this->assertFalse($customAttributeRequired->SatisfiesRequired(null));

        // "0" should satisfy required: empty('0') is true, but it is numeric.
        $this->assertTrue($customAttributeRequired->SatisfiesRequired('0'));
        $this->assertTrue($customAttributeRequired->SatisfiesRequired(0));

        $this->assertTrue($customAttributeRequired->SatisfiesRequired('  something  '));
        $this->assertTrue($customAttributeNotRequired->SatisfiesRequired(''));
        $this->assertTrue($customAttributeNotRequired->SatisfiesRequired('something'));
    }

    public function testChecksForRegexValues()
    {
        $regex = '/^\d+$/';

        $customAttributeRequired = CustomAttribute::Create('', 1, 1, $regex, false, null, 0);
        $customAttributeNotRequired = CustomAttribute::Create('', 1, 1, null, false, null, 0);

        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(''));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(' '));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(null));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint('a1'));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint('1a'));

        $this->assertTrue($customAttributeRequired->SatisfiesConstraint('1'));
        $this->assertTrue($customAttributeRequired->SatisfiesConstraint('11'));

        $this->assertTrue($customAttributeNotRequired->SatisfiesConstraint('abc'));
        $this->assertTrue($customAttributeNotRequired->SatisfiesConstraint(''));
    }

    public function testChecksForPossibleValues()
    {
        $customAttributeRequired = CustomAttribute::Create('', 1, 1, null, true, '1,abc,1abc3', 0);
        $customAttributeNotRequired = CustomAttribute::Create('', 1, 1, null, false, 'something', 0);

        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(''));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(' '));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint(null));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint('2'));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint('abcd'));
        $this->assertFalse($customAttributeRequired->SatisfiesConstraint('ab'));

        $this->assertTrue($customAttributeRequired->SatisfiesConstraint('1'));
        $this->assertTrue($customAttributeRequired->SatisfiesConstraint('abc'));
        $this->assertTrue($customAttributeRequired->SatisfiesConstraint('1abc3'));

        $this->assertTrue($customAttributeNotRequired->SatisfiesConstraint(''));
        $this->assertTrue($customAttributeNotRequired->SatisfiesConstraint('something'));
    }

    public function testTrimsOffPossibleValueWhiteSpace()
    {
        $attribute = CustomAttribute::Create('', 1, 1, null, false, '  1, abc    ,1abc3   ', 1);

        $list = $attribute->PossibleValueList();

        $this->assertTrue(in_array('1', $list));
        $this->assertTrue(in_array('abc', $list));
        $this->assertTrue(in_array('1abc3', $list));
        $this->assertEquals(1, $attribute->SortOrder());
    }

    public function testResourceAttributesCanBeLimitedToResourceTypes()
    {
        $attribute = CustomAttribute::Create('', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, null, false, null, 0);
        $attribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE_TYPE, '3,4');

        $this->assertTrue($attribute->AppliesToResourceType(3));
        $this->assertTrue($attribute->AppliesToResourceType(4));
        $this->assertFalse($attribute->AppliesToResourceType(5));
        $this->assertFalse($attribute->AppliesToResourceType(null));
    }

    public function testResourceAttributesCannotBeLimitedToOtherCategories()
    {
        $attribute = CustomAttribute::Create('', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, null, false, null, 0);
        $attribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE, '3');
        $attribute->WithSecondaryEntities(CustomAttributeCategory::USER, '3');

        $this->assertFalse($attribute->HasSecondaryEntities());
        $this->assertTrue($attribute->AppliesToResourceType(5));
    }

    public function testResourceTypeScopeCanBeRemoved()
    {
        $attribute = CustomAttribute::Create('', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, null, false, null, 0);
        $attribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE_TYPE, '3');
        $attribute->WithSecondaryEntities(null, null);

        $this->assertFalse($attribute->HasSecondaryEntities());
        $this->assertTrue($attribute->AppliesToResourceType(5));
    }

    public function testOnlyResourceAttributesAreScopedByResourceType()
    {
        $attribute = CustomAttribute::Create('', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESERVATION, null, false, null, 0);
        $attribute->WithSecondaryEntities(CustomAttributeCategory::RESOURCE_TYPE, '3');

        $this->assertTrue($attribute->HasSecondaryEntities());
        $this->assertTrue($attribute->AppliesToResourceType(5));
    }
}
