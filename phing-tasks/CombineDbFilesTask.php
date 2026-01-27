<?php

//require_once "phing/Task.php";

class CombineDbFilesTask
{
    private $schemaDir = null;

    public function setSchemadir($schemadir)
    {
        $this->schemaDir = $schemadir;
    }

    private $schemaFile = null;

    public function setSchemafile($schemaFile)
    {
        $this->schemaFile = $schemaFile;
    }

    private $dataFile = null;

    public function setDatafile($dataFile)
    {
        $this->dataFile = $dataFile;
    }

    /**
     * The init method: Do init steps.
     */
    public function init()
    {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        $upgradeDir = "{$this->schemaDir}/upgrades";

        print("Searching $upgradeDir for upgrade directories\n");

        $upgrades = scandir($upgradeDir);

        usort($upgrades, [$this, 'SortDirectories']);

        foreach ($upgrades as $upgrade) {
            if ($upgrade === '.' || $upgrade === '..' || str_starts_with($upgrade, '.')) {
                continue;
            }

            $this->Combine($upgradeDir, $upgrade);
        }
    }

    private function Combine($upgradeDir, $versionNumber)
    {
        $fullUpgradeDir = "$upgradeDir/$versionNumber";
        if (!is_dir($fullUpgradeDir)) {
            return;
        }

        print("Combining database files for version $versionNumber\n");

        $this->CombineMainFiles($fullUpgradeDir, $versionNumber);
        $this->CombineUpgradeFiles($fullUpgradeDir, $versionNumber);

        print("Finished combining database files for version $versionNumber\n");
    }

    private function CombineMainFiles($upgradeDir, $versionNumber)
    {
        $versionInfo = "\r\n\r\n-- UPGRADE TO VERSION $versionNumber\r\n\r\n";

        // schema
        $schemaHandle = fopen($this->schemaFile, "a");
        $upgradeSchema = $this->GetSchemaFileContents($upgradeDir);
        $newContents = "$versionInfo\r\n\r\n$upgradeSchema";

        fwrite($schemaHandle, $newContents);
        fclose($schemaHandle);

        // data
        $dataHandle = fopen($this->dataFile, "a");
        $upgradeData = $this->GetDataFileContents($upgradeDir);
        $newContents = "$versionInfo\r\n\r\n$upgradeData";

        fwrite($dataHandle, $newContents);
        fclose($dataHandle);
    }

    private function CombineUpgradeFiles($upgradeDir, $versionNumber)
    {
        $upgradeHandle = fopen("$upgradeDir/upgrade.sql", "w+");

        $upgradeSchema = $this->GetSchemaFileContents($upgradeDir);
        $upgradeData = $this->GetDataFileContents($upgradeDir);

        fwrite($upgradeHandle, "\r\n\r\n$upgradeSchema\r\n\r\n$upgradeData");
        fclose($upgradeHandle);
    }

    private function GetFullSql($file)
    {
        $f = fopen($file, "r");
        $sql = fread($f, filesize($file));
        fclose($f);
        return $sql;
    }

    private function GetSchemaFileContents($upgradeDir)
    {
        return $this->GetFullSql("$upgradeDir/schema.sql");
    }

    private function GetDataFileContents($upgradeDir)
    {
        return $this->GetFullSql("$upgradeDir/data.sql");
    }

    private function SortDirectories($dir1, $dir2)
    {
        $d1 = floatval($dir1);
        $d2 = floatval($dir2);
        return $d1 <=> $d2;
    }
}

if (PHP_SAPI === 'cli' && isset($argv, $argc)) {
    if ($argc < 4 || in_array('--help', $argv) || in_array('-h', $argv)) {
        echo "Usage: php {$argv[0]} <schema_dir> <schema_file> <data_file>\n";
        echo "\n";
        echo "Arguments:\n";
        echo "  schema_dir   - Directory containing database schema upgrades\n";
        echo "  schema_file  - Output file path for combined schema SQL\n";
        echo "  data_file    - Output file path for combined data SQL\n";
        exit($argc < 4 ? 1 : 0);
    }

    $task = new CombineDbFilesTask();
    $task->setSchemadir($argv[1]);
    $task->setSchemafile($argv[2]);
    $task->setDatafile($argv[3]);
    $task->main();
}
