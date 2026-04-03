INSERT INTO `dbversion` (`version_number`, `version_date`)
SELECT 4.0, NOW()
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1
    FROM `dbversion`
    WHERE `version_number` = 4.0
);
