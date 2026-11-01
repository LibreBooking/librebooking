<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Server/namespace.php');

class FormKeysTest extends TestBase
{
    public function testEvaluateResolvesAKeyByName(): void
    {
        $this->assertSame(FormKeys::PHONE, FormKeys::Evaluate('PHONE'));
        $this->assertSame(FormKeys::PHONE, FormKeys::Evaluate('phone'));
    }

    public function testPersistLoginKeyStillResolvesForLoginTemplatesCompiledByEarlierReleases(): void
    {
        $this->assertSame('persistLogin', FormKeys::Evaluate('PERSIST_LOGIN'));
    }
}
