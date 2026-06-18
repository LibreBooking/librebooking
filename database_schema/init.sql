

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;



DROP USER IF EXISTS 'lb_user'@'%';
CREATE USER 'lb_user'@'%' identified by 'password';

DROP USER IF EXISTS 'lb_user'@'127.0.0.1';
CREATE USER 'lb_user'@'127.0.0.1' identified by 'password';

GRANT ALL on librebooking.* to 'lb_user'@'%';
GRANT ALL on librebooking.* to 'lb_user'@'127.0.0.1';

DROP DATABASE IF EXISTS `librebooking`;

CREATE DATABASE `librebooking`;

USE `librebooking`;


CREATE TABLE `accessories` (
  `accessory_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `accessory_name` varchar(85) NOT NULL,
  `accessory_quantity` smallint(5) unsigned DEFAULT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`accessory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `account_activation` (
  `account_activation_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `activation_code` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`account_activation_id`),
  UNIQUE KEY `activation_code_2` (`activation_code`),
  KEY `activation_code` (`activation_code`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `account_activation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `announcement_groups` (
  `announcementid` mediumint(8) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`announcementid`,`group_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `announcement_groups_ibfk_1` FOREIGN KEY (`announcementid`) REFERENCES `announcements` (`announcementid`) ON DELETE CASCADE,
  CONSTRAINT `announcement_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `announcement_resources` (
  `announcementid` mediumint(8) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`announcementid`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `announcement_resources_ibfk_1` FOREIGN KEY (`announcementid`) REFERENCES `announcements` (`announcementid`) ON DELETE CASCADE,
  CONSTRAINT `announcement_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `announcements` (
  `announcementid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_text` text NOT NULL,
  `priority` mediumint(8) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `display_page` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`announcementid`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `display_page` (`display_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `blackout_instances` (
  `blackout_instance_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `blackout_series_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`blackout_instance_id`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `blackout_series_id` (`blackout_series_id`),
  CONSTRAINT `blackout_instances_ibfk_1` FOREIGN KEY (`blackout_series_id`) REFERENCES `blackout_series` (`blackout_series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `blackout_series` (
  `blackout_series_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `title` varchar(85) NOT NULL,
  `description` text DEFAULT NULL,
  `owner_id` mediumint(8) unsigned NOT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `repeat_type` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `repeat_options` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`blackout_series_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `blackout_series_resources` (
  `blackout_series_id` int(10) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`blackout_series_id`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `blackout_series_resources_ibfk_1` FOREIGN KEY (`blackout_series_id`) REFERENCES `blackout_series` (`blackout_series_id`) ON DELETE CASCADE,
  CONSTRAINT `blackout_series_resources_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `credit_log` (
  `credit_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `original_credit_count` decimal(7,2) DEFAULT NULL,
  `credit_count` decimal(7,2) DEFAULT NULL,
  `credit_note` varchar(1000) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`credit_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `custom_attribute_entities` (
  `custom_attribute_id` mediumint(8) unsigned NOT NULL,
  `entity_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`custom_attribute_id`,`entity_id`),
  CONSTRAINT `custom_attribute_entities_ibfk_1` FOREIGN KEY (`custom_attribute_id`) REFERENCES `custom_attributes` (`custom_attribute_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `custom_attribute_values` (
  `custom_attribute_value_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `custom_attribute_id` mediumint(8) unsigned NOT NULL,
  `attribute_value` text NOT NULL,
  `entity_id` mediumint(8) unsigned NOT NULL,
  `attribute_category` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`custom_attribute_value_id`),
  KEY `custom_attribute_id` (`custom_attribute_id`),
  KEY `entity_id` (`entity_id`),
  KEY `attribute_category` (`attribute_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `custom_attributes` (
  `custom_attribute_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `display_label` varchar(50) NOT NULL,
  `display_type` tinyint(2) unsigned NOT NULL,
  `attribute_category` tinyint(2) unsigned NOT NULL,
  `validation_regex` varchar(50) DEFAULT NULL,
  `is_required` tinyint(1) unsigned NOT NULL,
  `possible_values` text DEFAULT NULL,
  `sort_order` tinyint(2) unsigned DEFAULT NULL,
  `admin_only` tinyint(1) unsigned DEFAULT NULL,
  `secondary_category` tinyint(2) unsigned DEFAULT NULL,
  `secondary_entity_ids` varchar(2000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `is_private` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`custom_attribute_id`),
  KEY `attribute_category` (`attribute_category`),
  KEY `display_label` (`display_label`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `custom_time_blocks` (
  `custom_time_block_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `layout_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`custom_time_block_id`),
  KEY `layout_id` (`layout_id`),
  CONSTRAINT `custom_time_blocks_ibfk_1` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`layout_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `dbversion` (
  `version_number` double unsigned NOT NULL DEFAULT 0,
  `version_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `group_resource_permissions` (
  `group_id` smallint(5) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  `permission_type` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`group_id`,`resource_id`),
  KEY `group_id` (`group_id`),
  KEY `resource_id` (`resource_id`),
  KEY `group_id_2` (`group_id`),
  KEY `resource_id_2` (`resource_id`),
  CONSTRAINT `group_resource_permissions_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `group_resource_permissions_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `group_roles` (
  `group_id` smallint(8) unsigned NOT NULL,
  `role_id` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`role_id`),
  KEY `group_id` (`group_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `group_roles_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `group_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `groups` (
  `group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(85) NOT NULL,
  `admin_group_id` smallint(5) unsigned DEFAULT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `isdefault` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`group_id`),
  KEY `admin_group_id` (`admin_group_id`),
  KEY `isdefault` (`isdefault`),
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`admin_group_id`) REFERENCES `groups` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `layouts` (
  `layout_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `timezone` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `layout_type` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`layout_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payment_configuration` (
  `credit_cost` decimal(7,2) unsigned NOT NULL,
  `credit_currency` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `credit_count` int(10) unsigned NOT NULL DEFAULT 1 CHECK (`credit_count` > 0),
  PRIMARY KEY (`credit_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payment_gateway_settings` (
  `gateway_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `setting_value` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`gateway_type`,`setting_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `payment_transaction_log` (
  `payment_transaction_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `invoice_number` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `transaction_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `subtotal_amount` decimal(7,2) NOT NULL,
  `tax_amount` decimal(7,2) NOT NULL,
  `total_amount` decimal(7,2) NOT NULL,
  `transaction_fee` decimal(7,2) DEFAULT NULL,
  `currency` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `transaction_href` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `refund_href` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `gateway_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `gateway_date_created` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `payment_response` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`payment_transaction_log_id`),
  KEY `user_id` (`user_id`),
  KEY `invoice_number` (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `peak_times` (
  `peak_times_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` smallint(5) unsigned NOT NULL,
  `all_day` tinyint(1) unsigned NOT NULL,
  `start_time` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `end_time` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `every_day` tinyint(1) unsigned NOT NULL,
  `peak_days` varchar(13) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `all_year` tinyint(1) unsigned NOT NULL,
  `begin_month` tinyint(1) unsigned NOT NULL,
  `begin_day` tinyint(1) unsigned NOT NULL,
  `end_month` tinyint(1) unsigned NOT NULL,
  `end_day` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`peak_times_id`),
  KEY `schedule_id` (`schedule_id`),
  CONSTRAINT `peak_times_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `quotas` (
  `quota_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `quota_limit` decimal(7,2) unsigned NOT NULL,
  `unit` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `duration` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `resource_id` smallint(5) unsigned DEFAULT NULL,
  `group_id` smallint(5) unsigned DEFAULT NULL,
  `schedule_id` smallint(5) unsigned DEFAULT NULL,
  `enforced_days` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `enforced_time_start` time DEFAULT NULL,
  `enforced_time_end` time DEFAULT NULL,
  `scope` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`quota_id`),
  KEY `resource_id` (`resource_id`),
  KEY `group_id` (`group_id`),
  KEY `schedule_id` (`schedule_id`),
  CONSTRAINT `quotas_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `quotas_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `quotas_ibfk_3` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `refund_transaction_log` (
  `refund_transaction_log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_transaction_log_id` int(10) unsigned NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `transaction_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `total_refund_amount` decimal(7,2) NOT NULL,
  `payment_refund_amount` decimal(7,2) DEFAULT NULL,
  `fee_refund_amount` decimal(7,2) DEFAULT NULL,
  `transaction_href` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `gateway_date_created` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `refund_response` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`refund_transaction_log_id`),
  KEY `payment_transaction_log_id` (`payment_transaction_log_id`),
  CONSTRAINT `refund_transaction_log_ibfk_1` FOREIGN KEY (`payment_transaction_log_id`) REFERENCES `payment_transaction_log` (`payment_transaction_log_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reminders` (
  `reminder_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `address` text NOT NULL,
  `message` text NOT NULL,
  `sendtime` datetime NOT NULL,
  `refnumber` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`reminder_id`),
  KEY `reminders_user_id` (`user_id`),
  CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_accessories` (
  `series_id` int(10) unsigned NOT NULL,
  `accessory_id` smallint(5) unsigned NOT NULL,
  `quantity` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`series_id`,`accessory_id`),
  KEY `accessory_id` (`accessory_id`),
  KEY `series_id` (`series_id`),
  CONSTRAINT `reservation_accessories_ibfk_1` FOREIGN KEY (`accessory_id`) REFERENCES `accessories` (`accessory_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_accessories_ibfk_2` FOREIGN KEY (`series_id`) REFERENCES `reservation_series` (`series_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_color_rules` (
  `reservation_color_rule_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `custom_attribute_id` mediumint(8) unsigned NOT NULL,
  `attribute_type` smallint(5) unsigned DEFAULT NULL,
  `required_value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `comparison_type` smallint(5) unsigned DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`reservation_color_rule_id`),
  KEY `custom_attribute_id` (`custom_attribute_id`),
  CONSTRAINT `reservation_color_rules_ibfk_1` FOREIGN KEY (`custom_attribute_id`) REFERENCES `custom_attributes` (`custom_attribute_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_files` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `series_id` int(10) unsigned NOT NULL,
  `file_name` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `file_type` varchar(75) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `file_size` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `file_extension` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`file_id`),
  KEY `series_id` (`series_id`),
  CONSTRAINT `reservation_files_ibfk_1` FOREIGN KEY (`series_id`) REFERENCES `reservation_series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_guests` (
  `reservation_instance_id` int(10) unsigned NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `reservation_user_level` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`reservation_instance_id`,`email`),
  KEY `reservation_guests_reservation_instance_id` (`reservation_instance_id`),
  KEY `reservation_guests_email_address` (`email`),
  KEY `reservation_guests_reservation_user_level` (`reservation_user_level`),
  CONSTRAINT `reservation_guests_ibfk_1` FOREIGN KEY (`reservation_instance_id`) REFERENCES `reservation_instances` (`reservation_instance_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_instances` (
  `reservation_instance_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `reference_number` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `series_id` int(10) unsigned NOT NULL,
  `checkin_date` datetime DEFAULT NULL,
  `checkout_date` datetime DEFAULT NULL,
  `previous_end_date` datetime DEFAULT NULL,
  `credit_count` decimal(7,2) unsigned DEFAULT NULL,
  PRIMARY KEY (`reservation_instance_id`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `reference_number` (`reference_number`),
  KEY `series_id` (`series_id`),
  KEY `checkin_date` (`checkin_date`),
  CONSTRAINT `reservations_series` FOREIGN KEY (`series_id`) REFERENCES `reservation_series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `reservation_reminders` (
  `reminder_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `series_id` int(10) unsigned NOT NULL,
  `minutes_prior` int(10) unsigned NOT NULL,
  `reminder_type` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`reminder_id`),
  KEY `series_id` (`series_id`),
  KEY `reminder_type` (`reminder_type`),
  CONSTRAINT `reservation_reminders_ibfk_1` FOREIGN KEY (`series_id`) REFERENCES `reservation_series` (`series_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `reservation_resources` (
  `series_id` int(10) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  `resource_level_id` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`series_id`,`resource_id`),
  KEY `resource_id` (`resource_id`),
  KEY `series_id` (`series_id`),
  CONSTRAINT `reservation_resources_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_resources_ibfk_2` FOREIGN KEY (`series_id`) REFERENCES `reservation_series` (`series_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_series` (
  `series_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date_created` datetime NOT NULL,
  `last_modified` datetime DEFAULT NULL,
  `title` varchar(300) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `allow_participation` tinyint(1) unsigned NOT NULL,
  `allow_anon_participation` tinyint(1) unsigned NOT NULL,
  `type_id` tinyint(2) unsigned NOT NULL,
  `status_id` tinyint(2) unsigned NOT NULL,
  `repeat_type` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `repeat_options` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `owner_id` mediumint(8) unsigned NOT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `last_action_by` mediumint(8) unsigned DEFAULT NULL,
  `terms_date_accepted` datetime DEFAULT NULL,
  PRIMARY KEY (`series_id`),
  KEY `type_id` (`type_id`),
  KEY `status_id` (`status_id`),
  KEY `reservations_owner` (`owner_id`),
  CONSTRAINT `reservations_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `reservations_status` FOREIGN KEY (`status_id`) REFERENCES `reservation_statuses` (`status_id`) ON UPDATE CASCADE,
  CONSTRAINT `reservations_type` FOREIGN KEY (`type_id`) REFERENCES `reservation_types` (`type_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_statuses` (
  `status_id` tinyint(2) unsigned NOT NULL,
  `label` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_types` (
  `type_id` tinyint(2) unsigned NOT NULL,
  `label` varchar(85) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_users` (
  `reservation_instance_id` int(10) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `reservation_user_level` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`reservation_instance_id`,`user_id`),
  KEY `reservation_instance_id` (`reservation_instance_id`),
  KEY `user_id` (`user_id`),
  KEY `reservation_user_level` (`reservation_user_level`),
  CONSTRAINT `reservation_users_ibfk_1` FOREIGN KEY (`reservation_instance_id`) REFERENCES `reservation_instances` (`reservation_instance_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservation_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `reservation_waitlist_requests` (
  `reservation_waitlist_request_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`reservation_waitlist_request_id`),
  KEY `user_id` (`user_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `reservation_waitlist_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_waitlist_requests_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `resource_accessories` (
  `resource_accessory_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` smallint(5) unsigned NOT NULL,
  `accessory_id` smallint(5) unsigned NOT NULL,
  `minimum_quantity` smallint(6) DEFAULT NULL,
  `maximum_quantity` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`resource_accessory_id`),
  KEY `resource_id` (`resource_id`),
  KEY `accessory_id` (`accessory_id`),
  CONSTRAINT `resource_accessories_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE,
  CONSTRAINT `resource_accessories_ibfk_2` FOREIGN KEY (`accessory_id`) REFERENCES `accessories` (`accessory_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `resource_group_assignment` (
  `resource_group_id` mediumint(8) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`resource_group_id`,`resource_id`),
  KEY `resource_group_assignment_resource_id` (`resource_id`),
  KEY `resource_group_assignment_resource_group_id` (`resource_group_id`),
  CONSTRAINT `resource_group_assignment_ibfk_1` FOREIGN KEY (`resource_group_id`) REFERENCES `resource_groups` (`resource_group_id`) ON DELETE CASCADE,
  CONSTRAINT `resource_group_assignment_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `resource_groups` (
  `resource_group_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `resource_group_name` varchar(75) DEFAULT NULL,
  `parent_id` mediumint(8) unsigned DEFAULT NULL,
  `public_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`resource_group_id`),
  KEY `resource_groups_parent_id` (`parent_id`),
  CONSTRAINT `resource_groups_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `resource_groups` (`resource_group_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `resource_images` (
  `resource_image_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` smallint(5) unsigned NOT NULL,
  `image_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`resource_image_id`),
  KEY `resource_id` (`resource_id`),
  CONSTRAINT `resource_images_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `resource_status_reasons` (
  `resource_status_reason_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status_id` tinyint(3) unsigned NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`resource_status_reason_id`),
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `resource_type_assignment` (
  `resource_id` smallint(5) unsigned NOT NULL,
  `resource_type_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`resource_id`,`resource_type_id`),
  KEY `resource_type_id` (`resource_type_id`),
  CONSTRAINT `resource_type_assignment_ibfk_1` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE,
  CONSTRAINT `resource_type_assignment_ibfk_2` FOREIGN KEY (`resource_type_id`) REFERENCES `resource_types` (`resource_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `resource_types` (
  `resource_type_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type_name` varchar(75) DEFAULT NULL,
  `resource_type_description` text DEFAULT NULL,
  PRIMARY KEY (`resource_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `resources` (
  `resource_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(85) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `min_duration` int(11) DEFAULT NULL,
  `min_increment` int(11) DEFAULT NULL,
  `max_duration` int(11) DEFAULT NULL,
  `unit_cost` decimal(7,2) DEFAULT NULL,
  `autoassign` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `requires_approval` tinyint(1) unsigned NOT NULL,
  `allow_multiday_reservations` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `max_participants` mediumint(8) unsigned DEFAULT NULL,
  `min_notice_time_add` int(11) DEFAULT NULL,
  `max_notice_time` int(11) DEFAULT NULL,
  `image_name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `schedule_id` smallint(5) unsigned NOT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `admin_group_id` smallint(5) unsigned DEFAULT NULL,
  `public_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `allow_calendar_subscription` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` smallint(5) unsigned DEFAULT NULL,
  `resource_type_id` mediumint(8) unsigned DEFAULT NULL,
  `status_id` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `resource_status_reason_id` smallint(5) unsigned DEFAULT NULL,
  `buffer_time` int(10) unsigned DEFAULT NULL,
  `enable_check_in` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `auto_release_minutes` smallint(5) unsigned DEFAULT NULL,
  `color` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `allow_display` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `credit_count` decimal(7,2) unsigned DEFAULT NULL,
  `peak_credit_count` decimal(7,2) unsigned DEFAULT NULL,
  `min_notice_time_update` int(11) DEFAULT NULL,
  `min_notice_time_delete` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `additional_properties` text DEFAULT NULL,
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `public_id` (`public_id`),
  KEY `schedule_id` (`schedule_id`),
  KEY `admin_group_id` (`admin_group_id`),
  KEY `resource_type_id` (`resource_type_id`),
  KEY `resource_status_reason_id` (`resource_status_reason_id`),
  KEY `auto_release_minutes` (`auto_release_minutes`),
  CONSTRAINT `admin_group_id` FOREIGN KEY (`admin_group_id`) REFERENCES `groups` (`group_id`) ON DELETE SET NULL,
  CONSTRAINT `resources_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`schedule_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resources_ibfk_2` FOREIGN KEY (`resource_type_id`) REFERENCES `resource_types` (`resource_type_id`) ON DELETE SET NULL,
  CONSTRAINT `resources_ibfk_3` FOREIGN KEY (`resource_status_reason_id`) REFERENCES `resource_status_reasons` (`resource_status_reason_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `roles` (
  `role_id` tinyint(2) unsigned NOT NULL,
  `name` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `role_level` tinyint(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `saved_reports` (
  `saved_report_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `report_name` varchar(50) DEFAULT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `report_details` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`saved_report_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `saved_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `schedules` (
  `schedule_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(85) NOT NULL,
  `isdefault` tinyint(1) unsigned NOT NULL,
  `weekdaystart` tinyint(2) unsigned NOT NULL,
  `daysvisible` tinyint(2) unsigned NOT NULL DEFAULT 7,
  `layout_id` mediumint(8) unsigned NOT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `public_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `allow_calendar_subscription` tinyint(1) NOT NULL DEFAULT 0,
  `admin_group_id` smallint(5) unsigned DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `allow_concurrent_bookings` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `default_layout` tinyint(4) NOT NULL DEFAULT 0,
  `total_concurrent_reservations` smallint(5) unsigned NOT NULL DEFAULT 0,
  `max_resources_per_reservation` smallint(5) unsigned NOT NULL DEFAULT 0,
  `additional_properties` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `published` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`schedule_id`),
  UNIQUE KEY `public_id` (`public_id`),
  KEY `layout_id` (`layout_id`),
  KEY `schedules_groups_admin_group_id` (`admin_group_id`),
  CONSTRAINT `schedules_groups_admin_group_id` FOREIGN KEY (`admin_group_id`) REFERENCES `groups` (`group_id`) ON DELETE SET NULL,
  CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`layout_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `terms_of_service` (
  `terms_of_service_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `terms_text` text DEFAULT NULL,
  `terms_url` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `terms_file` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `applicability` varchar(50) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`terms_of_service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `time_blocks` (
  `block_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(85) DEFAULT NULL,
  `end_label` varchar(85) DEFAULT NULL,
  `availability_code` tinyint(2) unsigned NOT NULL,
  `layout_id` mediumint(8) unsigned NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day_of_week` smallint(5) unsigned DEFAULT NULL,
  PRIMARY KEY (`block_id`),
  KEY `layout_id` (`layout_id`),
  CONSTRAINT `time_blocks_ibfk_1` FOREIGN KEY (`layout_id`) REFERENCES `layouts` (`layout_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_email_preferences` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `event_category` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `event_type` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`user_id`,`event_category`,`event_type`),
  CONSTRAINT `user_email_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_groups` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_preferences` (
  `user_preferences_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  PRIMARY KEY (`user_preferences_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_resource_permissions` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `resource_id` smallint(5) unsigned NOT NULL,
  `permission_id` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `permission_type` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`,`resource_id`),
  KEY `user_id` (`user_id`),
  KEY `resource_id` (`resource_id`),
  KEY `user_id_2` (`user_id`),
  KEY `resource_id_2` (`resource_id`),
  CONSTRAINT `user_resource_permissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_resource_permissions_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `resources` (`resource_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_session` (
  `user_session_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `last_modified` datetime NOT NULL,
  `session_token` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_session_value` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`user_session_id`),
  KEY `user_session_user_id` (`user_id`),
  KEY `user_session_session_token` (`session_token`),
  CONSTRAINT `user_session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_statuses` (
  `status_id` tinyint(2) unsigned NOT NULL,
  `description` varchar(85) DEFAULT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `users` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `fname` varchar(85) DEFAULT NULL,
  `lname` varchar(85) DEFAULT NULL,
  `username` varchar(85) DEFAULT NULL,
  `email` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `salt` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `organization` varchar(85) DEFAULT NULL,
  `position` varchar(85) DEFAULT NULL,
  `phone` varchar(85) DEFAULT NULL,
  `timezone` varchar(85) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `language` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `homepageid` tinyint(2) unsigned NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lastlogin` datetime DEFAULT NULL,
  `status_id` tinyint(2) unsigned NOT NULL,
  `legacyid` char(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `legacypassword` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `public_id` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `allow_calendar_subscription` tinyint(1) NOT NULL DEFAULT 0,
  `default_schedule_id` smallint(5) unsigned DEFAULT NULL,
  `credit_count` decimal(7,2) DEFAULT 0.00,
  `terms_date_accepted` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `public_id` (`public_id`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `user_statuses` (`status_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;


--  create-data.sql


insert into `user_statuses` values (1, 'Active'), (2, 'Awaiting'), (3, 'Inactive');
insert into `roles` values (1, 'Group Admin', 1);
insert into `roles` values (2, 'Application Admin', 2);
insert into `reservation_types` values (1, 'Reservation'), (2, 'Blackout');
insert into `reservation_statuses` values (1, 'Created'), (2, 'Deleted'), (3, 'Pending');

insert into `layouts` values (1, 'America/New_York', 0);

insert into `time_blocks` (`availability_code`, `layout_id`, `start_time`, `end_time`) values
(2, 1, '00:00', '08:00'),
(1, 1, '08:00', '08:30'),
(1, 1, '08:30', '09:00'),
(1, 1, '09:00', '09:30'),
(1, 1, '09:30', '10:00'),
(1, 1, '10:00', '10:30'),
(1, 1, '10:30', '11:00'),
(1, 1, '11:00', '11:30'),
(1, 1, '11:30', '12:00'),
(1, 1, '12:00', '12:30'),
(1, 1, '12:30', '13:00'),
(1, 1, '13:00', '13:30'),
(1, 1, '13:30', '14:00'),
(1, 1, '14:00', '14:30'),
(1, 1, '14:30', '15:00'),
(1, 1, '15:00', '15:30'),
(1, 1, '15:30', '16:00'),
(1, 1, '16:00', '16:30'),
(1, 1, '16:30', '17:00'),
(1, 1, '17:00', '17:30'),
(1, 1, '17:30', '18:00'),
(2, 1, '18:00', '00:00');

insert into `schedules` (`schedule_id`, `name`, `isdefault`, `weekdaystart`, `layout_id`) values (1, 'Default', 1, 0, 1);
--  sample-data-utf8.sql
SET foreign_key_checks = 0;

delete from `groups` where `admin_group_id` is not null;
delete from `groups`;
alter table `groups` AUTO_INCREMENT = 1;
delete from `resources`;
alter table `resources` AUTO_INCREMENT = 1;
delete from `accessories`;
alter table `accessories` AUTO_INCREMENT = 1;
delete from `users`;
alter table `users` AUTO_INCREMENT = 1;
truncate table `group_roles`;
truncate table `user_groups`;

insert into `groups` (`group_id`, `name`) values (1, 'Group Administrators'), (2, 'Application Administrators'), (3, 'Resource Administrators'), (4, 'Schedule Administrators');

insert into `group_roles` values (1, 1);
insert into `group_roles` values (2, 2);
insert into `group_roles` values (4, 4);

insert into `users` (`fname`, `lname`, `email`, `username`, `password`, `salt`, `timezone`, `lastlogin`, `status_id`, `date_created`, `language`, `organization`)
values ('User', 'User', 'user@example.com', 'user', '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b', 'America/New_York', '2008-09-16 01:59:00', 1, now(), 'en_us', 'XYZ Org Inc.'),
       ('Admin', 'Admin', 'admin@example.com', 'admin', '70f3e748c6801656e4aae9dca6ee98ab137d952c', '4a04db87', 'America/New_York', '2010-03-26 12:44:00', 1, now(), 'en_us', 'ABC Org Inc.');

insert into `user_groups` values (2,2);

insert into `resources` (`resource_id`, `name`, `location`, `contact_info`, `description`, `notes`, `min_duration`, `min_increment`, `max_duration`, `unit_cost`, `autoassign`, `requires_approval`, `allow_multiday_reservations`, `max_participants`, `min_notice_time_add`, `max_notice_time`, `image_name`, `legacyid`, `schedule_id`) VALUES
  (1, 'Conference Room 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, NULL, 'resource1.jpg', NULL, 1),
  (2, 'Conference Room 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, NULL, NULL, 'resource2.jpg', NULL, 1);

insert into `accessories` (`accessory_id`, `accessory_name`, `accessory_quantity`) values
  (1, 'accessory limited to 10', 10),
  (2, 'accessory limited to 2', 2),
  (3, 'unlimited accessory', NULL);

truncate table `user_resource_permissions`;
insert into `user_resource_permissions` values (1,1,1,0),(1,2,1,0),(2,1,1,0),(2,2,1,0);

truncate table `custom_attributes`;
insert into `custom_attributes` (`custom_attribute_id`,`display_label`,`display_type`,`attribute_category`,`validation_regex`,`is_required`,`possible_values`) VALUES
  (1, 'Test Number', 1, 1, null, false, null),
  (2, 'Test String', 1, 1, null, false, null),
  (3, 'Test Number', 1, 4, null, false, null),
  (4, 'Test String', 1, 4, null, false, null);

SET foreign_key_checks = 1;
