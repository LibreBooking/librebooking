<?php

use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    /**
     * @var FakeDatabase
     */
    public $db;

    /**
     * @var FakeServer
     */
    public $fakeServer;

    /**
     * @var FakeConfig
     */
    public $fakeConfig;

    /**
     * @var FakeResources
     */
    public $fakeResources;

    /**
     * @var FakeEmailService
     */
    public $fakeEmailService;

    /**
     * @var UserSession
     */
    public $fakeUser;

    /**
     * @var FakePluginManager
     */
    public $fakePluginManager;

    /**
     * @var FakeFileSystem
     */
    public $fileSystem;

    public function setUp(): void
    {
        Date::_SetNow(Date::Now());
        ReferenceNumberGenerator::$__referenceNumber = null;

        $this->db = new FakeDatabase();
        $this->fakeServer = new FakeServer();
        $this->fakeEmailService = new FakeEmailService();
        $this->fakeConfig = new FakeConfig();
        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_TIMEZONE, 'America/Chicago');
        $this->fakeConfig->SetKey(ConfigKeys::TABLET_VIEW_ALLOW_RESERVATIONS, true);

        $this->fakeResources = new FakeResources();
        $this->fakeUser = $this->fakeServer->UserSession;
        $this->fakePluginManager = new FakePluginManager();
        $this->fileSystem = new FakeFileSystem();

        ServiceLocator::SetDatabase($this->db);
        ServiceLocator::SetServer($this->fakeServer);
        ServiceLocator::SetEmailService($this->fakeEmailService);
        ServiceLocator::SetFileSystem($this->fileSystem);
        Configuration::SetInstance($this->fakeConfig);
        Resources::SetInstance($this->fakeResources);
        PluginManager::SetInstance($this->fakePluginManager);
    }

    /**
     * Helper method to capture error logs during test execution
     * @param callable $testFunction The function to execute while capturing logs
     * @param bool $displayLogs Whether to display captured logs (for debugging)
     * @return array Array of captured error log entries
     */
    protected function captureErrorLog(callable $testFunction, bool $displayLogs = false): array
    {
        $errorLogs = [];
        $originalErrorLog = ini_get('error_log');

        // Create a temporary log file
        $tempLogFile = tempnam(sys_get_temp_dir(), 'test_error_log');
        if ($tempLogFile === false) {
            throw new \RuntimeException('Failed to create a temporary error log file in ' . sys_get_temp_dir());
        }
        if (ini_set('error_log', $tempLogFile) === false) {
            throw new \RuntimeException("Failed to set error_log to temporary file: $tempLogFile");
        }

        try {
            // Execute the test function
            $testFunction();
        } finally {
            // Read captured error logs
            if (file_exists($tempLogFile)) {
                $errorLogs = file($tempLogFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            }

            // Restore original error log setting
            ini_set('error_log', $originalErrorLog);

            // Clean up temporary file
            if (file_exists($tempLogFile)) {
                unlink($tempLogFile);
            }
        }

        // Optionally display logs for debugging
        if ($displayLogs && !empty($errorLogs)) {
            echo "\n=== Captured Error Logs ===\n";
            foreach ($errorLogs as $logEntry) {
                echo "ERROR LOG: " . $logEntry . "\n";
            }
            echo "=== End Error Logs ===\n";
        }

        return $errorLogs;
    }

    /**
     * Assert that a specific log message is found in captured error logs
     * 
     * @param array $logs Array of captured error log messages
     * @param string $expectedPattern Expected log message pattern to find
     * @param string $description Description of what should be logged (for assertion message)
     * @param string $logPrefix Prefix to filter logs by (e.g., '[CONFIG]')
     */
    protected function assertLogMessage(array $logs, string $expectedPattern, string $description, string $logPrefix = '[CONFIG]')
    {
        $filteredLogs = array_filter($logs, fn($log) => str_contains($log, $logPrefix));
        $found = false;
        foreach ($filteredLogs as $log) {
            if (str_contains($log, $expectedPattern)) {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found, "Should log: $description");
    }

    public function teardown(): void
    {
        $this->db = null;
        $this->fakeServer = null;
        Configuration::SetInstance(null);
        PluginManager::SetInstance(null);
        $this->fakeResources = null;
        Date::_ResetNow();
    }
}
