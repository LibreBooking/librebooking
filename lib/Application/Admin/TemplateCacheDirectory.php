<?php

class TemplateCacheDirectory
{
    public function Flush()
    {
        try {
            $dirName = $this->GetDirectory();
            $cacheDir = opendir($dirName);
            while (false !== ($file = readdir($cacheDir))) {
                if ($file != '.' && $file != '..') {
                    unlink($dirName . $file);
                }
            }
            closedir($cacheDir);
        } catch (Exception $ex) {
            Log::Error('Could not flush template cache directory: %s', $ex);
        }
    }

    public function GetDirectory()
    {
        return ROOT_DIR . 'tpl_c/';
    }
}
