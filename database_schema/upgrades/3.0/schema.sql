-- MariaDB supports `ADD COLUMN IF NOT EXISTS`, but MySQL 8.0's documented
-- ALTER TABLE syntax does not. Use metadata checks plus dynamic SQL so this
-- upgrade remains idempotent across both supported database families.
SET @notes_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'schedules'
        AND COLUMN_NAME = 'notes'
);
SET @notes_sql := IF(
    @notes_exists = 0,
    'ALTER TABLE `schedules` ADD COLUMN `notes` TEXT NULL',
    'SELECT 1'
);
PREPARE stmt FROM @notes_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @published_exists := (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'schedules'
        AND COLUMN_NAME = 'published'
);
SET @published_sql := IF(
    @published_exists = 0,
    'ALTER TABLE `schedules` ADD COLUMN `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0',
    'SELECT 1'
);
PREPARE stmt FROM @published_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
