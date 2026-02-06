<?php

require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');

class BookedStringHelperTest extends TestBase
{
    // StartsWith tests

    public function testStartsWithReturnsTrueWhenHaystackStartsWithNeedle()
    {
        $this->assertTrue(BookedStringHelper::StartsWith('hello world', 'hello'));
    }

    public function testStartsWithReturnsFalseWhenHaystackDoesNotStartWithNeedle()
    {
        $this->assertFalse(BookedStringHelper::StartsWith('hello world', 'world'));
    }

    public function testStartsWithReturnsTrueForEmptyNeedle()
    {
        $this->assertTrue(BookedStringHelper::StartsWith('hello', ''));
    }

    public function testStartsWithHandlesNullHaystack()
    {
        $this->assertFalse(BookedStringHelper::StartsWith(null, 'hello'));
    }

    public function testStartsWithHandlesNullNeedle()
    {
        // strlen(null ?? '') = 0, substr('hello', 0, 0) = '', '' === null is false
        $this->assertFalse(BookedStringHelper::StartsWith('hello', null));
    }

    public function testStartsWithHandlesBothNull()
    {
        // strlen(null ?? '') = 0, substr('' , 0, 0) = '', '' === null is false
        $this->assertFalse(BookedStringHelper::StartsWith(null, null));
    }

    // EndsWith tests

    public function testEndsWithReturnsTrueWhenHaystackEndsWithNeedle()
    {
        $this->assertTrue(BookedStringHelper::EndsWith('hello world', 'world'));
    }

    public function testEndsWithReturnsFalseWhenHaystackDoesNotEndWithNeedle()
    {
        $this->assertFalse(BookedStringHelper::EndsWith('hello world', 'hello'));
    }

    public function testEndsWithReturnsTrueForEmptyNeedle()
    {
        $this->assertTrue(BookedStringHelper::EndsWith('hello', ''));
    }

    public function testEndsWithHandlesNullHaystack()
    {
        $this->assertFalse(BookedStringHelper::EndsWith(null, 'hello'));
    }

    public function testEndsWithHandlesNullNeedle()
    {
        $this->assertTrue(BookedStringHelper::EndsWith('hello', null));
    }

    public function testEndsWithHandlesBothNull()
    {
        $this->assertTrue(BookedStringHelper::EndsWith(null, null));
    }

    // Contains tests

    public function testContainsReturnsTrueWhenHaystackContainsNeedle()
    {
        $this->assertTrue(BookedStringHelper::Contains('hello world', 'lo wo'));
    }

    public function testContainsReturnsFalseWhenHaystackDoesNotContainNeedle()
    {
        $this->assertFalse(BookedStringHelper::Contains('hello world', 'xyz'));
    }

    public function testContainsReturnsTrueForEmptyNeedle()
    {
        $this->assertTrue(BookedStringHelper::Contains('hello', ''));
    }

    public function testContainsHandlesNullHaystack()
    {
        $this->assertFalse(BookedStringHelper::Contains(null, 'hello'));
    }

    public function testContainsHandlesNullNeedle()
    {
        $this->assertTrue(BookedStringHelper::Contains('hello', null));
    }

    public function testContainsHandlesBothNull()
    {
        $this->assertTrue(BookedStringHelper::Contains(null, null));
    }

    public function testContainsWithSpecialCharacters()
    {
        $this->assertTrue(BookedStringHelper::Contains('100%', '%'));
        $this->assertTrue(BookedStringHelper::Contains('hello?world', '?'));
    }
}
