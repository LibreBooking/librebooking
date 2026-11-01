<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

use LibreBooking\Application\Admin\ServerEnvironment;

class ServerSettingsPage extends AdminPage
{
    public function __construct()
    {
        parent::__construct('ServerSettings');
    }

    public function PageLoad()
    {
        if ($this->TakingAction()) {
            $this->ProcessAction();
        }

        $plugins = $this->GetPlugins();

        $uploadDir = new ImageUploadDirectory();
        $cacheDir = new TemplateCacheDirectory();

        $environment = new ServerEnvironment();

        $this->Set('plugins', $plugins);
        $this->Set('appVersion', Configuration::VERSION);
        $this->Set('smartyVersion', \Smarty\Smarty::SMARTY_VERSION);
        $this->Set('databaseServerVersion', $this->GetDatabaseServerVersion());
        $this->Set('databaseSchemaVersion', $this->GetDatabaseSchemaVersion());
        $this->Set('phpInfo', $environment->getPhpInfo());
        $this->Set('iniSettings', $environment->getIniSettings());
        $this->Set('currentTime', date('Y-m-d H:i:s (e P)'));
        $this->Set('imageUploadDirPermissions', substr(sprintf('%o', fileperms($uploadDir->GetDirectory())), -4));
        $this->Set('imageUploadDirectory', $uploadDir->GetDirectory());
        $this->Set('templateCacheDirectory', $cacheDir->GetDirectory());
        $this->Display('Configuration/server_settings.tpl');
    }

    public function ProcessAction()
    {
        if ($this->GetAction() == 'changePermissions') {
            $uploadDir = new ImageUploadDirectory();
            $uploadDir->MakeWriteable();
        } else {
            $cacheDir = new TemplateCacheDirectory();
            $cacheDir->Flush();
        }
    }

    private function GetDatabaseServerVersion(): string
    {
        $reader = ServiceLocator::GetDatabase()->Query(new AdHocCommand('SELECT VERSION() AS server_version'));
        $row = $reader->GetRow();
        $reader->Free();

        return $row ? (string)$row['server_version'] : ServerEnvironment::NOT_AVAILABLE;
    }

    private function GetDatabaseSchemaVersion(): string
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetVersionCommand());
        $row = $reader->GetRow();
        $reader->Free();

        return $row ? (string)$row[ColumnNames::VERSION_NUMBER] : ServerEnvironment::NOT_AVAILABLE;
    }

    private function GetPlugins()
    {
        $plugins = [];
        $dit = new RecursiveDirectoryIterator(ROOT_DIR . 'plugins');

        /** @var SplFileInfo $path  */
        foreach ($dit as $path) {
            if ($path->isDir() && basename($path->getPathname()) != '.' && basename($path->getPathname()) != '..') {
                $plugins[basename($path->getPathname())] = [];
                /** @var SplFileInfo $plugin  */
                foreach (new RecursiveDirectoryIterator($path) as $plugin) {
                    if ($plugin->isDir() && basename($plugin->getPathname()) != '.' && basename($plugin->getPathname()) != '..') {
                        $plugins[basename($path->getPathname())][] = basename($plugin->getPathname());
                    }
                }
            }
        }

        return $plugins;
    }
}
