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
}
