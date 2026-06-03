<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Config/DeprecatedConfigKeys.php');

class DeprecatedConfigKeysTest extends TestBase
{
    public function testFindReasonReturnsReasonForCanonicalKey(): void
    {
        $reason = DeprecatedConfigKeys::findReason('security.x-xss');

        $this->assertNotNull($reason);
        $this->assertStringContainsString('X-XSS-Protection', $reason);
    }

    public function testFindReasonReturnsReasonForLegacyKey(): void
    {
        $reason = DeprecatedConfigKeys::findReason('security.security.x-xss');

        $this->assertNotNull($reason);
        $this->assertStringContainsString('X-XSS-Protection', $reason);
    }

    public function testFindReasonIsCaseInsensitive(): void
    {
        $reason = DeprecatedConfigKeys::findReason('Security.X-XSS');

        $this->assertNotNull($reason);
    }

    public function testFindReasonReturnsNullForUnknownKey(): void
    {
        $reason = DeprecatedConfigKeys::findReason('security.unknown-key');

        $this->assertNull($reason);
    }

    public function testFindReasonReturnsNullForEmptyString(): void
    {
        $reason = DeprecatedConfigKeys::findReason('');

        $this->assertNull($reason);
    }

    public function testAllKeysAreStoredInLowercase(): void
    {
        $constants = [
            'DEPRECATED_KEYS' => DeprecatedConfigKeys::DEPRECATED_KEYS,
            'DEPRECATED_LEGACY_KEYS' => DeprecatedConfigKeys::DEPRECATED_LEGACY_KEYS,
        ];

        foreach ($constants as $constName => $keys) {
            foreach (array_keys($keys) as $key) {
                $this->assertSame(
                    strtolower($key),
                    $key,
                    "Key '$key' in $constName must be lowercase"
                );
            }
        }
    }
}
