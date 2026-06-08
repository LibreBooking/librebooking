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
}
