<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');

/**
 * Upgrades from 2.9 onward are written to be safe to apply again (see commit
 * "make 2.9+ upgrades idempotent"); earlier upgrades are not. This re-applies
 * those upgrades to an already upgraded database using the unchanged
 * phing-tasks/UpgradeDbTask.php, which always runs every upgrade directory it
 * finds, by pointing it at a temporary copy containing only those directories.
 */
class UpgradeScriptsDatabaseTest extends DatabaseTestCase
{
    private const FIRST_REPEATABLE_UPGRADE = 2.9;
    private const UPGRADE_FILES = ['schema.sql', 'data.sql'];

    private ?string $tempSchemaDir = null;

    public function tearDown(): void
    {
        if ($this->tempSchemaDir !== null) {
            $this->removeDirectory($this->tempSchemaDir);
        }

        parent::tearDown();
    }

    public function testRepeatableUpgradesCanBeAppliedAgain(): void
    {
        $this->requireLoadedSchema();
        $versionsBefore = $this->versionRowCounts();
        $tableRowsBefore = $this->tableRowCounts();

        $output = $this->runUpgradeTask($this->copyRepeatableUpgrades());

        $this->assertStringNotContainsString('Failed on statement', $output);
        // dbversion has no unique constraint, so a missing "WHERE NOT EXISTS" guard
        // would add a duplicate row rather than fail; compare every version's count.
        $this->assertSame($versionsBefore, $this->versionRowCounts());
        // Re-applying an upgrade must not insert any data again, in any table.
        $this->assertSame($tableRowsBefore, $this->tableRowCounts());
    }

    private function copyRepeatableUpgrades(): string
    {
        $this->tempSchemaDir = sys_get_temp_dir() . '/lb-upgrades-' . bin2hex(random_bytes(8));
        $copied = 0;

        foreach (glob(ROOT_DIR . 'database_schema/upgrades/*', GLOB_ONLYDIR) as $upgradeDir) {
            $version = basename($upgradeDir);
            if ((float)$version < self::FIRST_REPEATABLE_UPGRADE) {
                continue;
            }

            $targetDir = $this->tempSchemaDir . '/upgrades/' . $version;
            mkdir($targetDir, 0777, true);
            foreach (self::UPGRADE_FILES as $file) {
                copy($upgradeDir . '/' . $file, $targetDir . '/' . $file);
            }
            $copied++;
        }

        $this->assertGreaterThan(0, $copied, 'Expected at least one repeatable upgrade');

        return $this->tempSchemaDir;
    }

    /**
     * @return array<string, int> row count per dbversion version_number
     */
    private function versionRowCounts(): array
    {
        $reader = $this->realDatabase()->Query(new AdHocCommand(
            'SELECT `version_number`, COUNT(*) AS `row_count` FROM `dbversion` GROUP BY `version_number` ORDER BY `version_number`'
        ));

        $counts = [];
        while ($row = $reader->GetRow()) {
            $counts[(string)$row['version_number']] = (int)$row['row_count'];
        }

        return $counts;
    }

    /**
     * @return array<string, int> row count per table
     */
    private function tableRowCounts(): array
    {
        $database = $this->realDatabase();
        $reader = $database->Query(new AdHocCommand('SHOW TABLES'));

        $tables = [];
        while ($row = $reader->GetRow()) {
            $tables[] = (string)reset($row);
        }
        sort($tables);

        $counts = [];
        foreach ($tables as $table) {
            $countRow = $database->Query(new AdHocCommand('SELECT COUNT(*) AS `row_count` FROM `' . $table . '`'))->GetRow();
            $counts[$table] = (int)$countRow['row_count'];
        }

        return $counts;
    }

    private function runUpgradeTask(string $schemaDir): string
    {
        $command = [
            PHP_BINARY,
            ROOT_DIR . 'phing-tasks/UpgradeDbTask.php',
            $this->dbUser,
            $this->dbPassword,
            $this->dbHost,
            $this->dbName,
            $schemaDir,
        ];
        $pipes = [];
        $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        $this->assertIsResource($process, 'Failed to start the upgrade task');

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        $this->assertSame(0, $exitCode, "Upgrade task failed:\n" . $stdout . $stderr);

        return $stdout . $stderr;
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($dir);
    }
}
