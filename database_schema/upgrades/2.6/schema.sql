# noinspection SqlNoDataSourceInspectionForFile
ALTER TABLE `custom_attributes`
  ADD COLUMN `admin_only` TINYINT(1) UNSIGNED;

ALTER TABLE `user_preferences`
  CHANGE COLUMN `value` `value` TEXT;

ALTER TABLE `reservation_files`
  CHANGE COLUMN `file_type` `file_type` VARCHAR(75);

DROP TABLE IF EXISTS `reservation_color_rules`;
CREATE TABLE `reservation_color_rules` (
		`reservation_color_rule_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`custom_attribute_id`       MEDIUMINT(8) UNSIGNED NOT NULL,
		`attribute_type`            SMALLINT UNSIGNED,
		`required_value`            TEXT,
		`comparison_type`           SMALLINT UNSIGNED,
		`color`                     VARCHAR(50),
  PRIMARY KEY (`reservation_color_rule_id`),
  FOREIGN KEY (`custom_attribute_id`)
  REFERENCES `custom_attributes` (`custom_attribute_id`)
    ON DELETE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `resource_accessories`;

CREATE TABLE `resource_accessories` (
		`resource_accessory_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`resource_id`           SMALLINT(5) UNSIGNED  NOT NULL,
		`accessory_id`          SMALLINT(5) UNSIGNED  NOT NULL,
		`minimum_quantity`      SMALLINT              NULL,
		`maximum_quantity`      SMALLINT              NULL,
		PRIMARY KEY (`resource_accessory_id`),
		FOREIGN KEY (`resource_id`)
		REFERENCES `resources` (`resource_id`)
				ON DELETE CASCADE,
		FOREIGN KEY (`accessory_id`)
		REFERENCES `accessories` (`accessory_id`)
				ON DELETE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;


ALTER TABLE `custom_attributes` ADD COLUMN `secondary_category` TINYINT(2) UNSIGNED;
ALTER TABLE `custom_attributes` ADD COLUMN `secondary_entity_ids` VARCHAR(2000);
ALTER TABLE `custom_attributes` ADD COLUMN `is_private` TINYINT(1) UNSIGNED;

ALTER TABLE `resource_groups`
  ADD COLUMN `public_id` VARCHAR(20);

ALTER TABLE `resources`
  MODIFY COLUMN `contact_info` VARCHAR(255);
ALTER TABLE `resources`
  MODIFY COLUMN `location` VARCHAR(255);

DROP TABLE IF EXISTS `resource_type_assignment`;
CREATE TABLE `resource_type_assignment` (
		`resource_id`      SMALLINT(5) UNSIGNED  NOT NULL,
		`resource_type_id` MEDIUMINT(8) UNSIGNED NOT NULL,
		PRIMARY KEY (`resource_id`, `resource_type_id`),
		FOREIGN KEY (`resource_id`)
		REFERENCES `resources` (`resource_id`)
				ON DELETE CASCADE,
		FOREIGN KEY (`resource_type_id`)
		REFERENCES `resource_types` (`resource_type_id`)
				ON DELETE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `custom_attribute_entities`;
CREATE TABLE `custom_attribute_entities` (
		`custom_attribute_id` MEDIUMINT(8) UNSIGNED NOT NULL,
		`entity_id`           MEDIUMINT(8) UNSIGNED NOT NULL,
		PRIMARY KEY (`custom_attribute_id`, `entity_id`),
		FOREIGN KEY (`custom_attribute_id`)
		REFERENCES `custom_attributes` (`custom_attribute_id`)
				ON DELETE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

INSERT INTO `custom_attribute_entities` (`custom_attribute_id`, `entity_id`) (SELECT
        `custom_attribute_id`,
        `entity_id`
FROM `custom_attributes`
WHERE `entity_id` IS NOT NULL AND `entity_id` <> 0);

ALTER TABLE `custom_attributes`
  DROP COLUMN `entity_id`;

ALTER TABLE `quotas`
  ADD COLUMN `enforced_days` VARCHAR(15);
ALTER TABLE `quotas`
  ADD COLUMN `enforced_time_start` TIME;
ALTER TABLE `quotas`
  ADD COLUMN `enforced_time_end` TIME;
ALTER TABLE `quotas`
  ADD COLUMN `scope` VARCHAR(25);

ALTER TABLE `resources`
  ADD COLUMN `enable_check_in` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `resources`
  ADD COLUMN `auto_release_minutes` SMALLINT UNSIGNED;
ALTER TABLE `resources` ADD INDEX( `auto_release_minutes`);
ALTER TABLE `resources`
  ADD COLUMN `color` VARCHAR(10);
ALTER TABLE `resources`
  ADD COLUMN `allow_display` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0;

ALTER TABLE `reservation_instances`
  ADD COLUMN `checkin_date` DATETIME;
ALTER TABLE `reservation_instances` ADD INDEX( `checkin_date`);
ALTER TABLE `reservation_instances`
  ADD COLUMN `checkout_date` DATETIME;
ALTER TABLE `reservation_instances`
  ADD COLUMN `previous_end_date` DATETIME;
ALTER TABLE `reservation_series`
  ADD COLUMN `last_action_by` MEDIUMINT(8) UNSIGNED;

DROP TABLE IF EXISTS `reservation_guests`;
CREATE TABLE `reservation_guests` (
		`reservation_instance_id` INT UNSIGNED        NOT NULL,
		`email`                   VARCHAR(255)        NOT NULL,
		`reservation_user_level`  TINYINT(2) UNSIGNED NOT NULL,
		PRIMARY KEY (`reservation_instance_id`, `email`),
		KEY `reservation_guests_reservation_instance_id` (`reservation_instance_id`),
		KEY `reservation_guests_email_address` (`email`),
		KEY `reservation_guests_reservation_user_level` (`reservation_user_level`),
		FOREIGN KEY (`reservation_instance_id`) REFERENCES `reservation_instances` (`reservation_instance_id`)
				ON DELETE CASCADE
				ON UPDATE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

ALTER TABLE `users`
  ADD COLUMN `credit_count` DECIMAL(7, 2) UNSIGNED;
ALTER TABLE `resources`
  ADD COLUMN `credit_count` DECIMAL(7, 2) UNSIGNED;
ALTER TABLE `resources`
  ADD COLUMN `peak_credit_count` DECIMAL(7, 2) UNSIGNED;
ALTER TABLE `reservation_instances`
  ADD COLUMN `credit_count` DECIMAL(7, 2) UNSIGNED;


DROP TABLE IF EXISTS `peak_times`;
CREATE TABLE `peak_times` (
		`peak_times_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`schedule_id`   SMALLINT(5) UNSIGNED  NOT NULL,
		`all_day`   TINYINT(1) UNSIGNED  NOT NULL,
		`start_time`   VARCHAR(10),
		`end_time`   VARCHAR(10),
		`every_day`   TINYINT(1) UNSIGNED  NOT NULL,
		`peak_days`   VARCHAR(13),
		`all_year`   TINYINT(1) UNSIGNED  NOT NULL,
		`begin_month`   TINYINT(1) UNSIGNED  NOT NULL,
		`begin_day`   TINYINT(1) UNSIGNED  NOT NULL,
		`end_month`   TINYINT(1) UNSIGNED  NOT NULL,
		`end_day`   TINYINT(1) UNSIGNED  NOT NULL,
		PRIMARY KEY (`peak_times_id`),
		FOREIGN KEY (`schedule_id`)
		REFERENCES `schedules` (`schedule_id`)
				ON DELETE CASCADE
)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `announcement_groups`;
CREATE TABLE `announcement_groups` (
		`announcementid` MEDIUMINT(8) UNSIGNED NOT NULL,
		`group_id` SMALLINT(5) UNSIGNED NOT NULL,
		PRIMARY KEY (`announcementid`, `group_id`),
		FOREIGN KEY (`announcementid`)
		REFERENCES `announcements` (`announcementid`)
				ON DELETE CASCADE,
    FOREIGN KEY (`group_id`)
		REFERENCES `groups` (`group_id`)
				ON DELETE CASCADE
		)
    ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `announcement_resources`;
CREATE TABLE `announcement_resources` (
		`announcementid` MEDIUMINT(8) UNSIGNED NOT NULL,
		`resource_id` SMALLINT(5) UNSIGNED NOT NULL,
		PRIMARY KEY (`announcementid`, `resource_id`),
		FOREIGN KEY (`announcementid`)
		REFERENCES `announcements` (`announcementid`)
				ON DELETE CASCADE,
    FOREIGN KEY (`resource_id`)
		REFERENCES `resources` (`resource_id`)
				ON DELETE CASCADE
		)
    ENGINE = InnoDB
		DEFAULT CHARACTER SET utf8;

DROP TABLE IF EXISTS `reservation_waitlist_requests`;
CREATE TABLE `reservation_waitlist_requests` (
  `reservation_waitlist_request_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` MEDIUMINT(8) UNSIGNED NOT NULL,
  `resource_id` SMALLINT(5) UNSIGNED NOT NULL,
  `start_date` DATETIME,
  `end_date` DATETIME,
  PRIMARY KEY (`reservation_waitlist_request_id`),
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`user_id`)
    ON DELETE CASCADE,
  FOREIGN KEY (`resource_id`)
  REFERENCES `resources` (`resource_id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  DEFAULT CHARACTER SET utf8;

ALTER TABLE `custom_attribute_values`
  CHANGE `custom_attribute_value_id`  `custom_attribute_value_id` INT(8) UNSIGNED NOT NULL AUTO_INCREMENT;
