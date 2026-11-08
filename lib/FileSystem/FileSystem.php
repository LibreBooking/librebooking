<?php

namespace Booked {

    require_once(ROOT_DIR . 'lib/Common/namespace.php');
    require_once(ROOT_DIR . 'lib/Application/Admin/namespace.php');

    use Log;
    use Paths;
    use TemplateCacheDirectory;

    interface IFileSystem
    {
        /**
         * @param $path string
         * @param $fileName string
         * @param $fileContents string
         * @return bool
         */
        public function Save($path, $fileName, $fileContents);

        /**
         * Returns null when the file cannot be read.
         */
        public function GetFileContents(string $fullPath): ?string;

        /**
         * @param $fullPath string
         * @return bool
         */
        public function RemoveFile($fullPath);

        /**
         * @return string
         */
        public function GetReservationAttachmentsPath();

        /**
         * @param $fullPath string
         * @return string[]
         */
        public function GetFiles($fullPath);

        /**
         * @param string $fullPath
         * @return bool
         */
        public function Exists($fullPath);

        /**
         * @return void
         */
        public function FlushSmartyCache();
    }

    class FileSystem implements IFileSystem
    {
        public function Save($path, $fileName, $fileContents)
        {
            $fullName = $path . $fileName;
            Log::Debug('Saving file to %s', $fullName);

            if (file_put_contents($fullName, $fileContents) === false) {
                Log::Error('Could not write contents of file: %s', $fullName);
                return false;
            }

            return true;
        }

        public function GetFileContents(string $fullPath): ?string
        {
            $contents = file_get_contents($fullPath);
            if ($contents === false) {
                Log::Error('Could not read contents of file: %s', $fullPath);
                return null;
            }

            return $contents;
        }

        public function RemoveFile($fullPath)
        {
            Log::Debug('Deleting file: %s', $fullPath);
            if (unlink($fullPath) === false) {
                Log::Error('Could not delete file: %s', $fullPath);
                return false;
            }

            return true;
        }

        public function GetReservationAttachmentsPath()
        {
            return Paths::ReservationAttachments();
        }

        public function GetFiles($path, $filter = '*')
        {
            return glob($path . $filter);
        }

        public function Exists($fullPath)
        {
            return file_exists($fullPath);
        }

        public function FlushSmartyCache()
        {
            $cacheDir = new TemplateCacheDirectory();
            $cacheDir->Flush();
        }
    }
}
