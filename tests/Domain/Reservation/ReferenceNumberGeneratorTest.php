<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');

class ReferenceNumberGeneratorTest extends TestBase
{
    public function tearDown(): void
    {
        ReferenceNumberGenerator::$__referenceNumber = null;
        parent::tearDown();
    }

    public function testGenerateReturnsLowercaseHexOfExpectedLength(): void
    {
        ReferenceNumberGenerator::$__referenceNumber = null;

        $referenceNumber = ReferenceNumberGenerator::Generate();

        $expectedLength = ReferenceNumberGenerator::RANDOM_BYTE_LENGTH * 2;
        $this->assertSame($expectedLength, strlen($referenceNumber));
        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $referenceNumber);
    }

    public function testGenerateReturnsDifferentValuesOnEachCall(): void
    {
        ReferenceNumberGenerator::$__referenceNumber = null;

        $first = ReferenceNumberGenerator::Generate();
        $second = ReferenceNumberGenerator::Generate();

        $this->assertNotSame($first, $second);
    }

    public function testGenerateReturnsTestHookValueWhenSet(): void
    {
        ReferenceNumberGenerator::$__referenceNumber = 'fixed-reference';

        $this->assertSame('fixed-reference', ReferenceNumberGenerator::Generate());
    }
}
