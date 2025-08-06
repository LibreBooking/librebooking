<?php

class Installer
{
    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @param $should_create_db bool
     * @param $should_create_user bool
     * @param $should_create_sample_data bool
     * @return array|InstallationResult[]
     */
    public function InstallFresh($should_create_db, $should_create_user, $should_create_sample_data)
    {
        $results = [];
        $config = Configuration::Instance();

        $hostname = $config->GetKey(ConfigKeys::DATABASE_HOSTSPEC);
        $database_name = $config->GetKey(ConfigKeys::DATABASE_NAME);
        $database_user = $config->GetKey(ConfigKeys::DATABASE_USER);
        $database_password = $config->GetKey(ConfigKeys::DATABASE_PASSWORD);
        $timezone = $config->GetKey(ConfigKeys::DEFAULT_TIMEZONE);

        $create_database = new MySqlScript(ROOT_DIR . 'database_schema/create-db.sql');
        $create_database->Replace('librebooking', $database_name);

        $create_user = new MySqlScript(ROOT_DIR . 'database_schema/create-user.sql');
        $create_user->Replace('librebooking', $database_name);
        $create_user->Replace('lb_user', $database_user);
        $create_user->Replace('localhost', $hostname);
        $create_user->Replace('password', $database_password);

        $populate_sample_data = new MySqlScript(ROOT_DIR . 'database_schema/sample-data-utf8.sql');
        $populate_sample_data->Replace('librebooking', $database_name);

        $create_schema = new MySqlScript(ROOT_DIR . 'database_schema/create-schema.sql');
        $populate_data = new MySqlScript(ROOT_DIR . 'database_schema/create-data.sql');
        $populate_data->Replace('America/New_York', $timezone);

        if ($should_create_db) {
            $results[] = $this->ExecuteScript($hostname, 'mysql', $this->user, $this->password, $create_database);
        }

        $results[] = $this->ExecuteScript($hostname, $database_name, $this->user, $this->password, $create_schema);

        if ($should_create_user) {
            $results[] = $this->ExecuteScript($hostname, $database_name, $this->user, $this->password, $create_user);
        }

        $upgradeResults = $this->Upgrade();

        $results[] = $this->ExecuteScript($hostname, $database_name, $this->user, $this->password, $populate_data);

        /**
         * Populate sample data given in /LibreBooking/database_schema/sample-data-utf8.sql
         */
        if ($should_create_sample_data) {
            $results[] = $this->ExecuteScript($hostname, $database_name, $this->user, $this->password, $populate_sample_data);
        }

        $results = array_merge($results, $upgradeResults);
        return $results;
    }

    /**
     * @return array|InstallationResult[]
     */
    public function Upgrade()
    {
        $results = [];

        $upgradeDir = ROOT_DIR . 'database_schema/upgrades';
        $upgrades = scandir($upgradeDir);

        $currentVersion = $this->GetVersion();

        usort($upgrades, [$this, 'SortDirectories']);

        foreach ($upgrades as $upgrade) {
            if ($upgrade === '.' || $upgrade === '..' || strpos($upgrade, '.') === 0) {
                continue;
            }

            $upgradeResults = $this->ExecuteUpgrade($upgradeDir, $upgrade, $currentVersion);
            $results = array_merge($results, $upgradeResults);
        }

        return $results;
    }

    /**
     * @param string $upgradeDir
     * @param string $versionNumber
     * @param string $currentVersion
     * @return array|InstallationResult[]
     */
    private function ExecuteUpgrade($upgradeDir, $versionNumber, $currentVersion)
    {
        $results = [];
        $fullUpgradeDir = "$upgradeDir/$versionNumber";
        if (!is_dir($fullUpgradeDir)) {
            $results[] = new InstallationResultSkipped($versionNumber);
        } else {
            $greaterThanCurrent = floatval($versionNumber) > floatval($currentVersion);

            if ($greaterThanCurrent) {
                $config = Configuration::Instance();
                $hostname = $config->GetKey(ConfigKeys::DATABASE_HOSTSPEC);
                $database_name = $config->GetKey(ConfigKeys::DATABASE_NAME);
                $database_user = $this->user;
                $database_password = $this->password;

                $create_schema = new MySqlScript("$fullUpgradeDir/schema.sql");
                $results[] = $this->ExecuteScript($hostname, $database_name, $database_user, $database_password, $create_schema);

                $populate_data = new MySqlScript("$fullUpgradeDir/data.sql");
                $results[] = $this->ExecuteScript($hostname, $database_name, $database_user, $database_password, $populate_data);
            }
        }
        return $results;
    }

    private function SortDirectories($dir1, $dir2)
    {
        $d1 = floatval($dir1);
        $d2 = floatval($dir2);

        if ($d1 == $d2) {
            return 0;
        }
        return ($d1 < $d2) ? -1 : 1;
    }

    protected function ExecuteScript($hostname, $database_name, $db_user, $db_password, MySqlScript $script)
    {
        $result = new InstallationResult($script->Name());

        $sqlErrorCode = 0;
        $sqlErrorText = null;
        $sqlStmt = null;

        $link = @mysqli_connect($hostname, $db_user, $db_password);
        if (!$link) {
            $result->SetConnectionError();
            return $result;
        }

        $select_db_result = @mysqli_select_db($link, $database_name);
        if (!$select_db_result) {
            $result->SetAuthenticationError();
            return $result;
        }

        @mysqli_query($link, "SET foreign_key_checks = 0;");

        $sqlArray = explode(';', $script->GetFullSql());
        foreach ($sqlArray as $stmt) {
            if (strlen($stmt) > 3 && substr(ltrim($stmt), 0, 2) != '/*') {
                $queryResult = @mysqli_query($link, $stmt);
                if (!$queryResult) {
                    $sqlErrorCode = mysqli_errno($link);
                    $sqlErrorText = mysqli_error($link);
                    $sqlStmt = $stmt;
                    break;
                }
            }
        }

        @mysqli_query($link, "SET foreign_key_checks = 1;");

        $result->SetResult($sqlErrorCode, $sqlErrorText, $sqlStmt);

        return $result;
    }

    /**
     * @return float
     */
    public function GetVersion()
    {
        // if dbversion table does not exist or version in db is less than current

        $config = Configuration::Instance();
        $hostname = $config->GetKey(ConfigKeys::DATABASE_HOSTSPEC);
        $database_name = $config->GetKey(ConfigKeys::DATABASE_NAME);
        $database_user = $config->GetKey(ConfigKeys::DATABASE_USER);
        $database_password = $config->GetKey(ConfigKeys::DATABASE_PASSWORD);

        $link = mysqli_connect($hostname, $database_user, $database_password);
        if (!$link) {
            return false;
        }

        $select_db_result = mysqli_select_db($link, $database_name);
        if (!$select_db_result) {
            return false;
        }

        $select_table_result = mysqli_query($link, 'select * from layouts');

        if (!$select_table_result) {
            return false;
        }

        $getVersion = 'SELECT * FROM `dbversion` order by version_number desc limit 0,1';
        $result = mysqli_query($link, $getVersion);

        if (!$result) {
            return 2.0;
        }

        if ($row = mysqli_fetch_assoc($result)) {
            $versionNumber = $row['version_number'];

            if ($versionNumber == 2.1) {
                // bug in 2.2 upgrade did not insert version number, check for table instead

                $getCustomAttributes = 'SELECT * FROM custom_attributes';
                $customAttributesResults = mysqli_query($link, $getCustomAttributes);

                if ($customAttributesResults) {
                    mysqli_query($link, "insert into dbversion values('2.2', now())");
                    return 2.2;
                }
            }

            return $versionNumber;
        }

        return 2.8; //returns the current db version being installed
    }

    public function ClearCachedTemplates()
    {
        try {
            $templateDirectory = ROOT_DIR . 'tpl_c';
            $d = dir($templateDirectory);
            while ($entry = $d->read()) {
                if ($entry != "." && $entry != "..") {
                    @unlink($templateDirectory . '/' . $entry);
                }
            }
            $d->close();
        } catch (Exception $ex) {
            // eat it and move on
        }
    }
}
