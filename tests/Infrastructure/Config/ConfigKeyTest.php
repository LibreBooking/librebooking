<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/ConfigKey.php');

class ConfigKeyTest extends TestBase
{
    public function testValidConstruction(): void
    {
        $key = new ConfigKey(
            key: 'app.title',
            type: 'string',
            default: 'LibreBooking',
        );

        $this->assertEquals('app.title', $key->key);
        $this->assertEquals('string', $key->type);
        $this->assertEquals('LibreBooking', $key->default);
    }

    public function testOptionalFieldsDefaultCorrectly(): void
    {
        $key = new ConfigKey(key: 'some.key', type: 'string', default: '');

        $this->assertNull($key->section);
        $this->assertNull($key->label);
        $this->assertNull($key->description);
        $this->assertNull($key->choices);
        $this->assertNull($key->configFileComment);
        $this->assertNull($key->legacy);
        $this->assertFalse($key->isPrivate);
        $this->assertFalse($key->isHidden);
        $this->assertFalse($key->allowCustom);
        $this->assertFalse($key->isProtected);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('validTypeProvider')]
    public function testAllValidTypesAreAccepted(string $type): void
    {
        $key = new ConfigKey(key: 'some.key', type: $type, default: '');

        $this->assertEquals($type, $key->type);
    }

    public static function validTypeProvider(): array
    {
        return [
            ['string'],
            ['boolean'],
            ['integer'],
        ];
    }

    public function testEmptyKeyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key cannot be empty or whitespace');

        new ConfigKey(key: '', type: 'string', default: '');
    }

    public function testWhitespaceOnlyKeyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key cannot be empty or whitespace');

        new ConfigKey(key: '   ', type: 'string', default: '');
    }

    public function testInvalidTypeThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid config type 'bool' for key 'some.key'");

        new ConfigKey(key: 'some.key', type: 'bool', default: '');
    }

    public function testInvalidTypeIntThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid config type 'int' for key 'some.key'");

        new ConfigKey(key: 'some.key', type: 'int', default: 0);
    }

    public function testFromArrayMapsSnakeCaseKeysToCamelCaseProperties(): void
    {
        $key = ConfigKey::fromArray([
            'key' => 'app.title',
            'type' => 'string',
            'default' => 'LibreBooking',
            'section' => 'app',
            'label' => 'App title',
            'description' => 'The title of the application',
            'choices' => ['a' => 'A', 'b' => 'B'],
            'config_file_comment' => 'The public name of the application',
            'legacy' => 'old.app.title',
            'is_private' => true,
            'is_hidden' => true,
            'allow_custom' => true,
            'is_protected' => true,
        ]);

        $this->assertEquals('app.title', $key->key);
        $this->assertEquals('string', $key->type);
        $this->assertEquals('LibreBooking', $key->default);
        $this->assertEquals('app', $key->section);
        $this->assertEquals('App title', $key->label);
        $this->assertEquals('The title of the application', $key->description);
        $this->assertEquals(['a' => 'A', 'b' => 'B'], $key->choices);
        $this->assertEquals('The public name of the application', $key->configFileComment);
        $this->assertEquals('old.app.title', $key->legacy);
        $this->assertTrue($key->isPrivate);
        $this->assertTrue($key->isHidden);
        $this->assertTrue($key->allowCustom);
        $this->assertTrue($key->isProtected);
    }

    public function testFromArrayAppliesDefaultsForOmittedOptionalFields(): void
    {
        $key = ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
        ]);

        $this->assertNull($key->section);
        $this->assertNull($key->label);
        $this->assertNull($key->description);
        $this->assertNull($key->choices);
        $this->assertNull($key->configFileComment);
        $this->assertNull($key->legacy);
        $this->assertFalse($key->isPrivate);
        $this->assertFalse($key->isHidden);
        $this->assertFalse($key->allowCustom);
        $this->assertFalse($key->isProtected);
    }

    public function testFromArrayRejectsMissingDefault(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "some.key" is missing a required "default" value');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
        ]);
    }

    public function testFromArrayRejectsMissingKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "" is missing a required "key" value');

        ConfigKey::fromArray([
            'type' => 'string',
            'default' => '',
        ]);
    }

    public function testFromArrayRejectsMissingType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "some.key" is missing a required "type" value');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'default' => '',
        ]);
    }

    public function testFromArrayAllowsExplicitNullDefault(): void
    {
        $key = ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => null,
        ]);

        $this->assertNull($key->default);
    }

    public function testFromArrayRejectsUnknownKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown config definition key(s) [allow_cusotm] for config key "some.key"');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
            'allow_cusotm' => true,
        ]);
    }

    public function testFromArrayRejectsNonBooleanAllowCustom(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "some.key" has an invalid "allow_custom" value: must be true or false (boolean), got string');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
            'allow_custom' => 'yes',
        ]);
    }

    public function testFromArrayRejectsInvalidType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid config type 'bool' for key 'some.key'");

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'bool',
            'default' => '',
        ]);
    }

    public function testFromArrayRejectsNonStringKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('has an invalid "key" value: must be a string, got integer');

        ConfigKey::fromArray([
            'key' => 123,
            'type' => 'string',
            'default' => '',
        ]);
    }

    public function testFromArrayRejectsNonStringNullableMetadata(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "some.key" has an invalid "label" value: must be a string or null, got integer');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
            'label' => 123,
        ]);
    }

    public function testFromArrayAllowsNullNullableMetadata(): void
    {
        $key = ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
            'label' => null,
            'section' => null,
        ]);

        $this->assertNull($key->label);
        $this->assertNull($key->section);
    }

    public function testFromArrayRejectsNonArrayChoices(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "some.key" has an invalid "choices" value: must be an array or null, got string');

        ConfigKey::fromArray([
            'key' => 'some.key',
            'type' => 'string',
            'default' => '',
            'choices' => 'not-an-array',
        ]);
    }

    public function testFromArrayErrorMessageIncludesSectionToDisambiguateSameNamedKeys(): void
    {
        // Plugin definitions can share the same bare 'key' across sections, so the
        // section must appear in the message to identify the offending definition.
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Config key "key" (section "server1") has an invalid "allow_custom" value: must be true or false (boolean), got string');

        ConfigKey::fromArray([
            'key' => 'key',
            'type' => 'string',
            'default' => '',
            'section' => 'server1',
            'allow_custom' => 'yes',
        ]);
    }
}
