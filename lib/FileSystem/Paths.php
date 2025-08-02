<?php

class Paths
{
    /**
     * Filesystem directory for storing reservation attachments. Always contains trailing slash
     *
     * @static
     * @return string
     */
    public static function ReservationAttachments()
    {
        $uploadDir = Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_RESERVATION_ATTACHMENT_PATH);

        if (empty($uploadDir)) {
            $uploadDir = dirname(__FILE__) . '/' . ROOT_DIR . 'uploads/reservation';
        }

        if (!is_dir($uploadDir)) {
            $uploadDir =  dirname(__FILE__) . '/' . ROOT_DIR . $uploadDir;
        }

        if (!BookedStringHelper::EndsWith($uploadDir, '/')) {
            $uploadDir = $uploadDir . '/';
        }

        if (!is_dir($uploadDir)) {
            Log::Debug('Could not find directory %s. Attempting to create it', $uploadDir);
            $created = mkdir($uploadDir);
            if ($created) {
                Log::Debug('Created %s', $uploadDir);
            } else {
                Log::Debug('Could not create %s', $uploadDir);
            }
        }
        return $uploadDir;
    }

    /**
     * Filesystem directory for storing terms of service file. Always contains trailing slash
     *
     * @static
     * @return string
     */
    public static function Terms()
    {
        return ROOT_DIR . 'Web/uploads/tos/';
    }

    /**
     * Filesystem directory for storing terms of email templates for given language. Always contains trailing slash
     *
     * @static
     * @param $language string
     * @return string
     */
    public static function EmailTemplates($language)
    {
        if (AvailableLanguages::Contains($language)) {
            return dirname(__FILE__) . '/' . ROOT_DIR . "lang/$language/";
        }
        return dirname(__FILE__) . '/' . ROOT_DIR . "lang/en_us/";
    }
}
