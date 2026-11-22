<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

class AttributeFormParserTest extends TestBase
{
    private const ATTRIBUTE_ID = '12';

    /**
     * @return array<string, array{mixed}>
     */
    public static function unusableInlineNameProvider(): array
    {
        return [
            'missing' => [null],
            'empty' => [''],
            'prefix only' => [FormKeys::ATTRIBUTE_PREFIX],
            'no prefix' => ['foo'],
            'id without prefix' => [self::ATTRIBUTE_ID],
            'prefix not at the start' => ['x' . FormKeys::ATTRIBUTE_PREFIX . self::ATTRIBUTE_ID],
            'prefix after the id' => [self::ATTRIBUTE_ID . FormKeys::ATTRIBUTE_PREFIX],
            'id is not a number' => [FormKeys::ATTRIBUTE_PREFIX . 'foo'],
            'id with trailing text' => [FormKeys::ATTRIBUTE_PREFIX . self::ATTRIBUTE_ID . 'x'],
            'negative id' => [FormKeys::ATTRIBUTE_PREFIX . '-1'],
            'submitted as name[]' => [[FormKeys::ATTRIBUTE_PREFIX . self::ATTRIBUTE_ID]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('unusableInlineNameProvider')]
    public function testInlineAttributeValueIsNullWithoutAttributeId(mixed $name): void
    {
        $this->assertNull(AttributeFormParser::GetInlineAttributeValue(name: $name, value: 'value'));
    }

    /**
     * @return array<string, array{mixed}>
     */
    public static function unusableInlineValueProvider(): array
    {
        return [
            // Sent as value[][]=x; casting it to a string would store "Array".
            'nested list' => [[['nested']]],
            'nested list after a string' => [[['nested'], 'second']],
            'keyed nested list' => [['a' => ['nested']]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('unusableInlineValueProvider')]
    public function testInlineAttributeValueIsNullForNestedValue(mixed $value): void
    {
        $this->assertNull(
            AttributeFormParser::GetInlineAttributeValue(
                name: FormKeys::ATTRIBUTE_PREFIX . self::ATTRIBUTE_ID,
                value: $value,
            )
        );
    }

    /**
     * @return array<string, array{mixed, string}>
     */
    public static function inlineValueProvider(): array
    {
        return [
            'string' => ['value', 'value'],
            'missing' => [null, ''],
            // An empty value clears the attribute.
            'empty' => ['', ''],
            // A checkbox attribute submits value[].
            'list' => [['first', 'second'], 'first'],
            'keyed list' => [['a' => 'first', 'b' => 'second'], 'first'],
            'empty list' => [[], ''],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('inlineValueProvider')]
    public function testInlineAttributeValueReadsIdAndValue(mixed $value, string $expectedValue): void
    {
        $attributeValue = AttributeFormParser::GetInlineAttributeValue(
            name: FormKeys::ATTRIBUTE_PREFIX . self::ATTRIBUTE_ID,
            value: $value,
        );

        $this->assertNotNull($attributeValue);
        $this->assertSame(self::ATTRIBUTE_ID, $attributeValue->AttributeId);
        $this->assertSame($expectedValue, $attributeValue->Value);
    }
}
