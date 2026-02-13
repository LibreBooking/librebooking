<?php

/**
 * LibreBooking Preflight Check
 *
 * Standalone script that validates all prerequisites for running LibreBooking.
 * No dependency on the application's autoloader or classes.
 *
 * Usage: composer preflight [-- --no-color] [-- --skip-db] [-- --help]
 *    or: php lib/preflight.php [--no-color] [--skip-db] [--help]
 */

enum CheckStatus: string
{
    case Pass = 'PASS';
    case Fail = 'FAIL';
    case Warn = 'WARN';
    case Skip = 'SKIP';
}

readonly class CheckResult
{
    public function __construct(
        public CheckStatus $status,
        public string $label,
        public string $message = '',
    ) {
    }
}

class PreflightRunner
{
    private const REQUIRED_PHP_VERSION = '8.2.0';
    private const DB_CONNECT_TIMEOUT_SECONDS = 3;
    // https://dev.mysql.com/doc/mysql-errors/5.7/en/client-error-reference.html
    private const MYSQL_ERR_CANT_CONNECT_LOCAL = 2002;
    private const MYSQL_ERR_CANT_CONNECT_HOST = 2003;
    private const MYSQL_ERR_UNKNOWN_HOST = 2005;
    private const MYSQL_ERR_SERVER_GONE_AWAY = 2006;
    private const MYSQL_ERR_LOST_CONNECTION = 2013;

    private const REQUIRED_EXTENSIONS = [
        'ctype',
        'curl',
        'fileinfo',
        'json',
        'mbstring',
        'mysqli',
        'openssl',
        'pdo',
        'pdo_mysql',
        'tokenizer',
        'xml',
    ];

    private const OPTIONAL_EXTENSIONS = [
        'gd' => 'image upload and processing',
        'ldap' => 'LDAP authentication',
        'bcmath' => 'Active Directory authentication (password expiry)',
    ];

    private const REQUIRED_WRITABLE_DIRS = [
        'tpl_c',
    ];

    private const OPTIONAL_WRITABLE_DIRS = [
        'uploads',
        'Web/uploads/images',
        'Web/uploads/tos',
    ];

    /** @var list<CheckResult> */
    private array $results = [];

    private bool $useColor;
    private bool $skipDb;

    /** @var array<string, mixed>|null */
    private ?array $config = null;

    public function __construct(
        private readonly string $rootDir,
        bool $noColor = false,
        bool $skipDb = false,
    ) {
        $this->useColor = !$noColor && $this->supportsColor();
        $this->skipDb = $skipDb;
    }

    public function run(): int
    {
        $this->printHeader();

        // Phase 1: PHP Environment
        $this->record($this->checkPhpVersion());
        $this->record($this->checkRequiredExtensions());
        $this->record($this->checkOptionalExtensions());

        // Phase 2: Project Setup
        $this->record($this->checkComposerDependencies());

        // Phase 3: Configuration
        $configExists = $this->record($this->checkConfigFileExists());

        $configValid = null;
        if ($configExists->status === CheckStatus::Pass) {
            $configValid = $this->record($this->checkConfigFileValid());
        } else {
            $this->record(new CheckResult(
                status: CheckStatus::Skip,
                label: 'Configuration validity',
                message: 'Skipped because config/config.php does not exist.',
            ));
        }

        // Phase 4: Filesystem
        $this->record($this->checkRequiredWritableDirectories());
        $this->record($this->checkOptionalWritableDirectories());

        // Phase 5: Database
        if ($this->skipDb) {
            $this->record(new CheckResult(
                status: CheckStatus::Skip,
                label: 'Database connection',
                message: 'Skipped due to --skip-db flag.',
            ));
        } elseif ($configValid?->status === CheckStatus::Pass) {
            $this->record($this->checkDatabaseConnection());
        } else {
            $this->record(new CheckResult(
                status: CheckStatus::Skip,
                label: 'Database connection',
                message: 'Skipped because configuration is missing or invalid.',
            ));
        }

        $this->printSummary();

        return $this->hasFailures() ? 1 : 0;
    }

    private function checkPhpVersion(): CheckResult
    {
        $current = PHP_VERSION;
        $required = self::REQUIRED_PHP_VERSION;

        if (version_compare(version1: $current, version2: $required, operator: '>=')) {
            return new CheckResult(
                status: CheckStatus::Pass,
                label: "PHP version: {$current} (required: >= 8.2)",
            );
        }

        return new CheckResult(
            status: CheckStatus::Fail,
            label: "PHP version: {$current} (required: >= 8.2)",
            message: 'Upgrade PHP to version 8.2 or later.',
        );
    }

    private function checkRequiredExtensions(): CheckResult
    {
        $missing = [];
        foreach (self::REQUIRED_EXTENSIONS as $ext) {
            if (!extension_loaded($ext)) {
                $missing[] = $ext;
            }
        }

        $total = count(self::REQUIRED_EXTENSIONS);

        if ($missing === []) {
            return new CheckResult(
                status: CheckStatus::Pass,
                label: "Required PHP extensions: all {$total} loaded",
            );
        }

        $missingCount = count($missing);
        $missingList = implode(separator: ', ', array: $missing);

        return new CheckResult(
            status: CheckStatus::Fail,
            label: "Required PHP extensions: missing {$missingCount} of {$total}",
            message: "Missing: {$missingList}\nInstall the missing extensions and restart PHP.",
        );
    }

    private function checkOptionalExtensions(): CheckResult
    {
        $missing = [];
        foreach (self::OPTIONAL_EXTENSIONS as $ext => $description) {
            if (!extension_loaded($ext)) {
                $missing[$ext] = $description;
            }
        }

        if ($missing === []) {
            $extList = implode(separator: ', ', array: array_keys(self::OPTIONAL_EXTENSIONS));
            return new CheckResult(
                status: CheckStatus::Pass,
                label: "Optional PHP extensions: {$extList}",
            );
        }

        $missingDetails = [];
        foreach ($missing as $ext => $description) {
            $missingDetails[] = "{$ext} (needed for {$description})";
        }
        $missingList = implode(separator: ', ', array: array_keys($missing));

        return new CheckResult(
            status: CheckStatus::Warn,
            label: "Optional PHP extensions: missing {$missingList}",
            message: implode(separator: "\n", array: $missingDetails),
        );
    }

    private function checkComposerDependencies(): CheckResult
    {
        $autoloadPath = $this->rootDir . 'vendor/autoload.php';

        if (file_exists($autoloadPath)) {
            return new CheckResult(
                status: CheckStatus::Pass,
                label: 'Composer dependencies: vendor/autoload.php found',
            );
        }

        return new CheckResult(
            status: CheckStatus::Fail,
            label: 'Composer dependencies: vendor/autoload.php not found',
            message: "Run 'composer install' to install dependencies.",
        );
    }

    private function checkConfigFileExists(): CheckResult
    {
        $configPath = $this->rootDir . 'config/config.php';

        if (file_exists($configPath)) {
            return new CheckResult(
                status: CheckStatus::Pass,
                label: 'Configuration file: config/config.php exists',
            );
        }

        return new CheckResult(
            status: CheckStatus::Fail,
            label: 'Configuration file: config/config.php not found',
            message: 'Copy config/config.dist.php to config/config.php and edit it with your settings.',
        );
    }

    private function checkConfigFileValid(): CheckResult
    {
        $configPath = $this->rootDir . 'config/config.php';

        if (!is_readable($configPath)) {
            return new CheckResult(
                status: CheckStatus::Fail,
                label: 'Configuration file: config/config.php is not readable',
                message: 'Ensure the file exists and is readable by the current PHP process.',
            );
        }

        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            $config = include $configPath;
        } catch (\Throwable $e) {
            return new CheckResult(
                status: CheckStatus::Fail,
                label: 'Configuration file: failed to load config/config.php',
                message: "Error while loading config/config.php: {$e->getMessage()}\n"
                    . 'Validate PHP syntax with: php -l config/config.php',
            );
        } finally {
            restore_error_handler();
        }

        if (!is_array($config)) {
            return new CheckResult(
                status: CheckStatus::Fail,
                label: 'Configuration file: config/config.php did not return a valid array',
                message: "Ensure the file returns an array with a 'settings' key. See config/config.dist.php for reference.",
            );
        }

        if (!isset($config['settings'])) {
            return new CheckResult(
                status: CheckStatus::Fail,
                label: "Configuration file: missing 'settings' key",
                message: "Ensure the file returns an array with a 'settings' key. See config/config.dist.php for reference.",
            );
        }

        $settings = $config['settings'];
        if (!isset($settings['database']) || !is_array($settings['database'])) {
            return new CheckResult(
                status: CheckStatus::Fail,
                label: "Configuration file: missing 'database' section",
                message: "The 'database' section under 'settings' must contain: type, hostspec, name, user, password.",
            );
        }

        $requiredDbKeys = ['type', 'hostspec', 'name', 'user', 'password'];
        $missingDbKeys = [];
        foreach ($requiredDbKeys as $key) {
            if (!array_key_exists($key, $settings['database'])) {
                $missingDbKeys[] = $key;
            }
        }

        if ($missingDbKeys !== []) {
            $missing = implode(separator: ', ', array: $missingDbKeys);
            return new CheckResult(
                status: CheckStatus::Fail,
                label: 'Configuration file: database section missing keys',
                message: "Missing database keys: {$missing}",
            );
        }

        // Store config for database check
        $this->config = $config;

        return new CheckResult(
            status: CheckStatus::Pass,
            label: 'Configuration file: valid (settings and database section found)',
        );
    }

    private function checkRequiredWritableDirectories(): CheckResult
    {
        $issues = [];
        foreach (self::REQUIRED_WRITABLE_DIRS as $dir) {
            $fullPath = $this->rootDir . $dir;
            if (!is_dir($fullPath)) {
                $issues[] = "{$dir}/: directory does not exist. Create it with: mkdir -p {$dir} && chmod 770 {$dir}";
            } elseif (!is_writable($fullPath)) {
                $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
                $issues[] = "{$dir}/: not writable (current permissions: {$perms}). Fix with: chmod 770 {$dir}";
            }
        }

        if ($issues === []) {
            $dirList = implode(separator: '/, ', array: self::REQUIRED_WRITABLE_DIRS) . '/';
            return new CheckResult(
                status: CheckStatus::Pass,
                label: "Required writable directories: {$dirList}",
            );
        }

        return new CheckResult(
            status: CheckStatus::Fail,
            label: 'Required writable directories: ' . count($issues) . ' issue(s) found',
            message: implode(separator: "\n", array: $issues),
        );
    }

    private function checkOptionalWritableDirectories(): CheckResult
    {
        $issues = [];
        foreach (self::OPTIONAL_WRITABLE_DIRS as $dir) {
            $fullPath = $this->rootDir . $dir;
            if (!is_dir($fullPath)) {
                $issues[] = "{$dir}/: directory does not exist. Create it with: mkdir -p {$dir} && chmod 770 {$dir}";
            } elseif (!is_writable($fullPath)) {
                $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
                $issues[] = "{$dir}/: not writable (current permissions: {$perms}). Fix with: chmod 770 {$dir}";
            }
        }

        if ($issues === []) {
            $dirList = implode(separator: '/, ', array: self::OPTIONAL_WRITABLE_DIRS) . '/';
            return new CheckResult(
                status: CheckStatus::Pass,
                label: "Optional writable directories: {$dirList}",
            );
        }

        return new CheckResult(
            status: CheckStatus::Warn,
            label: 'Optional writable directories: ' . count($issues) . ' issue(s)',
            message: implode(separator: "\n", array: $issues),
        );
    }

    private function checkDatabaseConnection(): CheckResult
    {
        if ($this->config === null) {
            return new CheckResult(
                status: CheckStatus::Skip,
                label: 'Database connection',
                message: 'Skipped because configuration was not loaded.',
            );
        }

        $db = $this->config['settings']['database'];
        $host = $db['hostspec'];
        $user = $db['user'];
        $password = $db['password'];
        $dbName = $db['name'];
        $port = null;

        // Mirror MySqlConnection host:port parsing pattern
        if (str_contains(haystack: $host, needle: ':')) {
            $parts = explode(separator: ':', string: $host);
            $host = $parts[0];
            $port = (int) $parts[1];
        }

        try {
            $conn = mysqli_init();
            if ($conn === false) {
                return new CheckResult(
                    status: CheckStatus::Fail,
                    label: 'Database connection: failed to initialize MySQL client',
                    message: 'Error: mysqli_init() failed. This typically indicates a system-level issue (such as insufficient memory) or a PHP environment problem, rather than a connectivity issue with the database server.',
                );
            }

            mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, self::DB_CONNECT_TIMEOUT_SECONDS);
            $connected = @mysqli_real_connect($conn, $host, $user, $password, $dbName, $port);
        } catch (\mysqli_sql_exception $e) {
            $errno = (int) $e->getCode();
            $errorMessage = $e->getMessage();
            $isServerUnreachable = $this->isServerUnreachableDatabaseError($errno, $errorMessage);
            return new CheckResult(
                status: $isServerUnreachable ? CheckStatus::Fail : CheckStatus::Warn,
                label: "Database connection: failed to connect to {$host}/{$dbName}",
                message: "Error: {$errorMessage}\n"
                    . "MySQL error code: {$errno}\n"
                    . ($isServerUnreachable
                        ? "Could not reach the configured database server.\nVerify database host, port, and network connectivity in config/config.php."
                        : "You may need to run http://<your-server>/Web/install/ to complete setup.\nIf you have already run the installer, verify database host, name, user, and password in config/config.php."),
            );
        }

        if ($connected === false) {
            $error = mysqli_connect_error() ?? 'Unknown error';
            $errno = mysqli_connect_errno();
            $isServerUnreachable = $this->isServerUnreachableDatabaseError($errno, $error);
            return new CheckResult(
                status: $isServerUnreachable ? CheckStatus::Fail : CheckStatus::Warn,
                label: "Database connection: failed to connect to {$host}/{$dbName}",
                message: "Error: {$error}\n"
                    . "MySQL error code: {$errno}\n"
                    . ($isServerUnreachable
                        ? "Could not reach the configured database server.\nVerify database host, port, and network connectivity in config/config.php."
                        : "You may need to run http://<your-server>/Web/install/ to complete setup.\nIf you have already run the installer, verify database host, name, user, and password in config/config.php."),
            );
        }

        mysqli_set_charset($conn, 'utf8mb4');

        // Check for the dbversion table to determine if installation has been completed
        $installed = false;
        $schemaInfo = '';
        try {
            $result = mysqli_query($conn, 'SELECT version_number FROM dbversion ORDER BY version_date DESC LIMIT 1');
            if ($result && ($row = mysqli_fetch_assoc($result))) {
                $installed = true;
                $schemaInfo = " (schema version: {$row['version_number']})";
            }
        } catch (\mysqli_sql_exception) {
            // Table does not exist — installation has not been completed
        }

        mysqli_close($conn);

        if (!$installed) {
            return new CheckResult(
                status: CheckStatus::Warn,
                label: "Database connection: connected to {$host}/{$dbName}, but schema not found",
                message: "The database exists but has no LibreBooking tables.\n"
                    . 'Visit http://<your-server>/Web/install/ in a browser to set up the database schema.',
            );
        }

        return new CheckResult(
            status: CheckStatus::Pass,
            label: "Database connection: connected to {$host}/{$dbName}{$schemaInfo}",
        );
    }

    private function isServerUnreachableDatabaseError(int $errno, string $error): bool
    {
        // Connectivity errors: hostname resolution/network/socket/transport failures.
        if (in_array($errno, [
            self::MYSQL_ERR_CANT_CONNECT_LOCAL,
            self::MYSQL_ERR_CANT_CONNECT_HOST,
            self::MYSQL_ERR_UNKNOWN_HOST,
            self::MYSQL_ERR_SERVER_GONE_AWAY,
            self::MYSQL_ERR_LOST_CONNECTION,
        ], true)) {
            return true;
        }

        $normalized = strtolower($error);
        $connectivityFragments = [
            'php_network_getaddresses',
            'getaddrinfo',
            'name or service not known',
            'no address associated with hostname',
            'connection refused',
            'connection timed out',
            'no route to host',
            'can\'t connect to',
        ];

        foreach ($connectivityFragments as $fragment) {
            if (str_contains($normalized, $fragment)) {
                return true;
            }
        }

        return false;
    }

    private function record(CheckResult $result): CheckResult
    {
        $this->results[] = $result;
        $this->printResult($result);

        return $result;
    }

    private function hasFailures(): bool
    {
        foreach ($this->results as $result) {
            if ($result->status === CheckStatus::Fail) {
                return true;
            }
        }

        return false;
    }

    private function printHeader(): void
    {
        echo "\n";
        echo $this->colorize(text: 'LibreBooking Preflight Check', colorCode: '1') . "\n";
        echo str_repeat(string: '=', times: 35) . "\n";
        echo "\n";
    }

    private function printResult(CheckResult $result): void
    {
        $statusText = match ($result->status) {
            CheckStatus::Pass => $this->colorize(text: '[PASS]', colorCode: '32'),
            CheckStatus::Fail => $this->colorize(text: '[FAIL]', colorCode: '31'),
            CheckStatus::Warn => $this->colorize(text: '[WARN]', colorCode: '33'),
            CheckStatus::Skip => $this->colorize(text: '[SKIP]', colorCode: '36'),
        };

        echo "  {$statusText} {$result->label}\n";

        if ($result->message !== '') {
            $indent = '         ';
            $lines = explode(separator: "\n", string: $result->message);
            foreach ($lines as $line) {
                echo "{$indent}{$line}\n";
            }
        }
    }

    private function printSummary(): void
    {
        $counts = [
            'passed' => 0,
            'failed' => 0,
            'warnings' => 0,
            'skipped' => 0,
        ];

        foreach ($this->results as $result) {
            match ($result->status) {
                CheckStatus::Pass => $counts['passed']++,
                CheckStatus::Fail => $counts['failed']++,
                CheckStatus::Warn => $counts['warnings']++,
                CheckStatus::Skip => $counts['skipped']++,
            };
        }

        echo "\n";
        echo str_repeat(string: '=', times: 35) . "\n";
        echo "Results: {$counts['passed']} passed, {$counts['failed']} failed, {$counts['warnings']} warnings, {$counts['skipped']} skipped\n";

        if ($counts['failed'] === 0) {
            echo "\n" . $this->colorize(text: 'All preflight checks passed!', colorCode: '32') . "\n";
        }

        echo "\n";
    }

    private function supportsColor(): bool
    {
        if (getenv('NO_COLOR') !== false) {
            return false;
        }

        if (PHP_OS_FAMILY === 'Windows') {
            return getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON';
        }

        if (function_exists('stream_isatty')) {
            return stream_isatty(STDOUT);
        }

        if (function_exists('posix_isatty')) {
            return posix_isatty(STDOUT);
        }

        return false;
    }

    private function colorize(string $text, string $colorCode): string
    {
        if (!$this->useColor) {
            return $text;
        }

        return "\033[{$colorCode}m{$text}\033[0m";
    }
}

function printUsage(): void
{
    echo "Usage: composer preflight -- [OPTIONS]\n";
    echo "       php lib/preflight.php [OPTIONS]\n";
    echo "\n";
    echo "Validates all prerequisites for running LibreBooking.\n";
    echo "\n";
    echo "Note: When using 'composer preflight', options must be passed after '--'\n";
    echo "      so Composer forwards them to the script instead of handling them itself.\n";
    echo "      Example: composer preflight -- --skip-db\n";
    echo "\n";
    echo "Options:\n";
    echo "  --no-color  Disable colored output\n";
    echo "  --skip-db   Skip the database connection check\n";
    echo "  --help      Show this help message\n";
    echo "\n";
}

// Entry point (only when executed directly, not when included)
$scriptFilename = $_SERVER['SCRIPT_FILENAME'] ?? '';
$isDirectExecution = $scriptFilename !== '' && realpath($scriptFilename) === __FILE__;

if (($isDirectExecution) && (php_sapi_name() === 'cli' || php_sapi_name() === 'cli-server')) {
    $args = array_slice($argv, 1);

    if (in_array(needle: '--help', haystack: $args, strict: true)) {
        printUsage();
        exit(0);
    }

    $noColor = in_array(needle: '--no-color', haystack: $args, strict: true);
    $skipDb = in_array(needle: '--skip-db', haystack: $args, strict: true);

    $rootDir = dirname(__DIR__) . '/';
    $runner = new PreflightRunner(
        rootDir: $rootDir,
        noColor: $noColor,
        skipDb: $skipDb,
    );
    exit($runner->run());
}
