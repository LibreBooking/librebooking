<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Install/InstallationResult.php');

class InstallationResultTest extends TestBase
{
    public function testSkippedUpgradeStepIsSuccessful(): void
    {
        $result = new InstallationResultSkipped('9.9');

        $this->assertTrue($result->WasSuccessful());
    }

    public function testResultWithoutErrorsIsSuccessful(): void
    {
        $result = new InstallationResult('task');
        $result->SetResult(0, '', '');

        $this->assertTrue($result->WasSuccessful());
    }

    public function testResultWithSqlErrorIsNotSuccessful(): void
    {
        $result = new InstallationResult('task');
        $result->SetResult(1064, 'syntax error', 'SELECT');

        $this->assertFalse($result->WasSuccessful());
    }
}
