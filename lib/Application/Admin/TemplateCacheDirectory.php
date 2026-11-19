<?php

class TemplateCacheDirectory
{
    /**
     * Removes everything in the template cache directory. Smarty keeps its
     * compiled templates directly in it, Twig in subdirectories of tpl_c/twig.
     */
    public function Flush()
    {
        try {
            $entries = new RecursiveIteratorIterator(
                iterator: new RecursiveDirectoryIterator(
                    directory: $this->GetDirectory(),
                    flags: FilesystemIterator::SKIP_DOTS
                ),
                mode: RecursiveIteratorIterator::CHILD_FIRST
            );

            /** @var SplFileInfo $entry */
            foreach ($entries as $entry) {
                if ($entry->isDir() && !$entry->isLink()) {
                    rmdir(directory: $entry->getPathname());
                } else {
                    unlink(filename: $entry->getPathname());
                }
            }
        } catch (Exception $ex) {
            Log::Error('Could not flush template cache directory: %s', $ex);
        }
    }

    public function GetDirectory()
    {
        return ROOT_DIR . 'tpl_c/';
    }
}
