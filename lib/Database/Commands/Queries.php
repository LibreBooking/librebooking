<?php

class Queries
{
    private function __construct()
    {
    }

    public const ADD_ACCESSORY =
        'INSERT INTO `accessories` (`accessory_name`, `accessory_quantity`)
		VALUES (@accessoryname, @quantity)';

    public const ADD_ACCESSORY_RESOURCE =
        'INSERT INTO `resource_accessories` (`resource_id`, `accessory_id`, `minimum_quantity`, `maximum_quantity`)
		VALUES (@resourceid, @accessoryid, @minimum_quantity, @maximum_quantity)';

    public const ADD_ACCOUNT_ACTIVATION =
        'INSERT INTO `account_activation` (`user_id`, `activation_code`, `date_created`) VALUES (@userid, @activation_code, @dateCreated)';

    public const ADD_ANNOUNCEMENT =
        'INSERT INTO `announcements` (`announcement_text`, `priority`, `start_date`, `end_date`, `display_page`)
		VALUES (@text, @priority, @startDate, @endDate, @display_page)';

    public const ADD_ANNOUNCEMENT_GROUP = 'INSERT INTO `announcement_groups` (`announcementid`, `group_id`) VALUES (@announcementid, @groupid)';

    public const ADD_ANNOUNCEMENT_RESOURCE = 'INSERT INTO `announcement_resources` (`announcementid`, `resource_id`) VALUES (@announcementid, @resourceid)';

    public const ADD_ATTRIBUTE =
        'INSERT INTO `custom_attributes` (`display_label`, `display_type`, `attribute_category`, `validation_regex`, `is_required`, `possible_values`, `sort_order`, `admin_only`, `secondary_category`, `secondary_entity_ids`, `is_private`)
		VALUES (@display_label, @display_type, @attribute_category, @validation_regex, @is_required, @possible_values, @sort_order, @admin_only, @secondary_category, @secondary_entity_ids, @is_private)';

    public const ADD_ATTRIBUTE_ENTITY =
        'INSERT INTO `custom_attribute_entities` (`custom_attribute_id`, `entity_id`)
				VALUES (@custom_attribute_id, @entity_id)';

    public const ADD_ATTRIBUTE_VALUE =
        'INSERT INTO `custom_attribute_values` (`custom_attribute_id`, `attribute_category`, `attribute_value`, `entity_id`)
			VALUES (@custom_attribute_id, @attribute_category, @attribute_value, @entity_id)';

    public const ADD_BLACKOUT_INSTANCE =
        'INSERT INTO `blackout_instances` (`start_date`, `end_date`, `blackout_series_id`)
		VALUES (@startDate, @endDate, @seriesid)';

    public const ADD_BLACKOUT_RESOURCE = 'INSERT INTO `blackout_series_resources` (`blackout_series_id`, `resource_id`) VALUES (@seriesid, @resourceid)';

    public const ADD_EMAIL_PREFERENCE =
        'INSERT IGNORE INTO `user_email_preferences` (`user_id`, `event_category`, `event_type`) VALUES (@userid, @event_category, @event_type)';

    public const ADD_BLACKOUT_SERIES =
        'INSERT INTO `blackout_series` (`date_created`, `title`, `owner_id`, `repeat_type`, `repeat_options`) VALUES (@dateCreated, @title, @userid, @repeatType, @repeatOptions)';

    public const ADD_GROUP =
        'INSERT INTO `groups` (`name`, `isdefault`) VALUES (@groupname, @isdefault)';

    public const ADD_GROUP_RESOURCE_PERMISSION =
        'INSERT INTO `group_resource_permissions` (`group_id`, `resource_id`, `permission_type`)
			VALUES (@groupid, @resourceid, @permission_type)';

    public const ADD_GROUP_ROLE =
        'INSERT IGNORE INTO `group_roles` (`group_id`, `role_id`) VALUES (@groupid, @roleid)';

    public const ADJUST_USER_CREDITS =
        'INSERT INTO `credit_log` (`user_id`, `original_credit_count`, `credit_count`, `credit_note`, `date_created`)
            SELECT `user_id`, `credit_count`, COALESCE(`credit_count`,0) - @credit_count, @credit_note, @dateCreated FROM `users` WHERE `user_id` = @userid;
          UPDATE `users` SET `credit_count` = COALESCE(`credit_count`,0) - @credit_count WHERE `user_id` = @userid';

    public const ADD_LAYOUT =
        'INSERT INTO `layouts` (`timezone`, `layout_type`) VALUES (@timezone, @layout_type)';

    public const ADD_LAYOUT_TIME =
        'INSERT INTO `time_blocks` (`layout_id`, `start_time`, `end_time`, `availability_code`, `label`, `day_of_week`)
		VALUES (@layoutid, @startTime, @endTime, @periodType, @label, @day_of_week)';

    public const ADD_CUSTOM_LAYOUT_SLOT =
        'INSERT INTO `custom_time_blocks` (`start_time`, `end_time`, `layout_id`)
		VALUES (@startTime, @endTime, (select `layout_id` from `schedules` where `schedule_id` = @scheduleid))';

    public const ADD_QUOTA =
        'INSERT INTO `quotas` (`quota_limit`, `unit`, `duration`, `resource_id`, `group_id`, `schedule_id`, `enforced_time_start`, `enforced_time_end`, `enforced_days`, `scope`)
			VALUES (@limit, @unit, @duration, @resourceid, @groupid, @scheduleid, @startTime, @endTime, @enforcedDays, @scope)';

    public const ADD_PAYMENT_CONFIGURATION = 'INSERT INTO `payment_configuration` (`credit_cost`, `credit_currency`, `credit_count`) VALUES (@credit_cost, @credit_currency, @credit_count)';

    public const ADD_PAYMENT_GATEWAY_SETTING = 'INSERT INTO `payment_gateway_settings` (`gateway_type`, `setting_name`, `setting_value`)
                                      VALUES (@gateway_type, @setting_name, @setting_value)';

    public const ADD_PAYMENT_TRANSACTION_LOG =
        'INSERT INTO `payment_transaction_log` (`user_id`, `status`, `invoice_number`, `transaction_id`, `subtotal_amount`, `tax_amount`, `total_amount`, `transaction_fee`, `currency`, `transaction_href`, `refund_href`, `date_created`, `gateway_date_created`, `gateway_name`, `payment_response`)
          VALUES (@userid, @status, @invoice_number, @transaction_id, @total_amount, 0, @total_amount, @transaction_fee, @currency, @transaction_href, @refund_href, @date_created, @gateway_date_created, @gateway_name, @payment_response)';

    public const ADD_PEAK_TIMES =
        'INSERT INTO `peak_times` (`schedule_id`, `all_day`, `start_time`, `end_time`, `every_day`, `peak_days`, `all_year`, `begin_month`, `begin_day`, `end_month`, `end_day`)
			VALUES (@scheduleid, @all_day, @start_time, @end_time, @every_day, @peak_days, @all_year, @begin_month, @begin_day, @end_month, @end_day)';

    public const ADD_REFUND_TRANSACTION_LOG =
        'INSERT INTO `refund_transaction_log` (`payment_transaction_log_id`, `status`, `transaction_id`, `total_refund_amount`, `payment_refund_amount`, `fee_refund_amount`, `transaction_href`, `date_created`, `gateway_date_created`, `refund_response`)
          VALUES (@payment_transaction_log_id, @status, @transaction_id, @total_refund_amount, @payment_refund_amount, @fee_refund_amount, @transaction_href, @date_created, @gateway_date_created, @refund_response)';

    public const ADD_REMINDER =
        'INSERT INTO `reminders` (`user_id`, `address`, `message`, `sendtime`, `refnumber`)
			VALUES (@user_id, @address, @message, @sendtime, @refnumber)';

    public const ADD_RESERVATION =
        'INSERT INTO `reservation_instances` (`start_date`, `end_date`, `reference_number`, `series_id`, `credit_count`)
        VALUES (@startDate, @endDate, @referenceNumber, @seriesid, @credit_count)';
    //		SELECT @startDate, @endDate, @referenceNumber, @seriesid, @credit_count
    //		WHERE NOT EXISTS(SELECT `ri`.`reference_number`
    //		    FROM `reservation_instances` `ri`
    //		    INNER JOIN `reservation_resources` `rr` on `ri`.`series_id` = `rr`.`series_id`
    //		    INNER JOIN `reservation_series` `rs` ON `ri`.`series_id` = `ri`.`series_id`
    //		    WHERE `ri`.`reference_number` <> @referenceNumber AND `rs`.`status_id` <> 2
    //		    AND ((`ri`.`start_date` > @startDate AND `ri`.`start_date` < @endDate) OR
    //					(`ri`.`end_date` > @startDate AND `ri`.`end_date` < @endDate) OR
    //					(`ri`.`start_date` <= @startDate AND `ri`.`end_date` >= @endDate)) LIMIT 1)';

    public const ADD_RESERVATION_ACCESSORY =
        'INSERT INTO `reservation_accessories` (`series_id`, `accessory_id`, `quantity`)
		VALUES (@seriesid, @accessoryid, @quantity)';

    public const ADD_RESERVATION_ATTACHMENT =
        'INSERT INTO `reservation_files` (`series_id`, `file_name`, `file_type`, `file_size`, `file_extension`)
		VALUES (@seriesid, @file_name, @file_type, @file_size, @file_extension)';

    public const ADD_RESERVATION_COLOR_RULE =
        'INSERT INTO `reservation_color_rules` (`custom_attribute_id`, `attribute_type`, `required_value`, `comparison_type`, `color`)
		VALUES (@custom_attribute_id, @attribute_type, @required_value, @comparison_type, @color)';

    public const ADD_RESERVATION_REMINDER =
        'INSERT INTO `reservation_reminders` (`series_id`, `minutes_prior`, `reminder_type`)
			VALUES (@seriesid, @minutes_prior, @reminder_type)';

    public const ADD_RESERVATION_RESOURCE =
        'INSERT INTO `reservation_resources` (`series_id`, `resource_id`, `resource_level_id`)
		VALUES (@seriesid, @resourceid, @resourceLevelId)';

    public const ADD_RESERVATION_SERIES =
        'INSERT INTO
        `reservation_series` (`date_created`, `title`, `description`, `allow_participation`, `allow_anon_participation`, `repeat_type`, `repeat_options`, `type_id`, `status_id`, `owner_id`, `terms_date_accepted`, `last_action_by`)
		VALUES (@dateCreated, @title, @description, @allow_participation, false, @repeatType, @repeatOptions, @typeid, @statusid, @userid, @terms_date_accepted, @last_action_by)';

    public const ADD_RESERVATION_GUEST =
        'INSERT INTO `reservation_guests` (`reservation_instance_id`, `email`, `reservation_user_level`)
			VALUES (@reservationid, @email, @levelid)';

    public const ADD_RESERVATION_USER =
        'INSERT INTO `reservation_users` (`reservation_instance_id`, `user_id`, `reservation_user_level`)
		VALUES (@reservationid, @userid, @levelid)';

    public const ADD_RESERVATION_WAITLIST =
        'INSERT INTO `reservation_waitlist_requests` (`user_id`, `start_date`, `end_date`, `resource_id`)
      VALUES (@userid, @startDate, @endDate, @resourceid)';

    public const ADD_SAVED_REPORT =
        'INSERT INTO `saved_reports` (`report_name`, `user_id`, `date_created`, `report_details`)
			VALUES (@report_name, @userid, @dateCreated, @report_details)';

    public const ADD_SCHEDULE =
        'INSERT INTO `schedules` (`name`, `isdefault`, `weekdaystart`, `daysvisible`, `layout_id`, `admin_group_id`)
		VALUES (@scheduleName, @scheduleIsDefault, @scheduleWeekdayStart, @scheduleDaysVisible, @layoutid, @admin_group_id)';

    public const ADD_TERMS_OF_SERVICE =
        'INSERT INTO `terms_of_service` (`terms_text`, `terms_url`, `terms_file`, `applicability`, `date_created`)
      VALUES (@terms_text, @terms_url, @terms_file, @applicability, @dateCreated)';

    public const ADD_USER_GROUP =
        'INSERT INTO `user_groups` (`user_id`, `group_id`)
		VALUES (@userid, @groupid)';

    public const ADD_USER_RESOURCE_PERMISSION =
        'INSERT IGNORE INTO `user_resource_permissions` (`user_id`, `resource_id`, `permission_type`)
		VALUES (@userid, @resourceid, @permission_type)';

    public const ADD_USER_TO_DEFAULT_GROUPS =
        'INSERT IGNORE INTO `user_groups` (`user_id`, `group_id`) SELECT @userid, `group_id` FROM `groups` WHERE `isdefault`=1';

    public const ADD_USER_SESSION =
        'INSERT INTO `user_session` (`user_id`, `last_modified`, `session_token`, `user_session_value`)
		VALUES (@userid, @dateModified, @session_token, @user_session_value)';

    public const AUTO_ASSIGN_PERMISSIONS =
        'INSERT INTO `user_resource_permissions` (`user_id`, `resource_id`)
		SELECT @userid as `user_id`, `resource_id` FROM `resources` WHERE `autoassign`=1';

    public const AUTO_ASSIGN_GUEST_PERMISSIONS =
        'INSERT INTO `user_resource_permissions` (`user_id`, `resource_id`)
		SELECT @userid as `user_id`, `resource_id`
		FROM `resources` WHERE `schedule_id` = @scheduleid';

    public const AUTO_ASSIGN_RESOURCE_PERMISSIONS =
        'INSERT IGNORE INTO `user_resource_permissions` (`user_id`, `resource_id`)
			(
			SELECT
				`user_id`, @resourceid as `resource_id`
			FROM
				`users` `u`)';

    public const AUTO_ASSIGN_CLEAR_RESOURCE_PERMISSIONS = 'DELETE FROM `user_resource_permissions` WHERE `resource_id` = @resourceid';

    public const CHECK_EMAIL =
        'SELECT `user_id` FROM `users` WHERE `email` = @email';

    public const CHECK_USERNAME =
        'SELECT `user_id` FROM `users` WHERE `username` = @username';

    public const CHECK_USER_EXISTENCE =
        'SELECT *
		FROM `users`
		WHERE ( (`username` IS NOT NULL AND `username` = @username) OR (`email` IS NOT NULL AND `email` = @email) )';

    public const CLEANUP_USER_SESSIONS =
        'DELETE FROM `user_session` WHERE utc_timestamp()>date_add(`last_modified`,interval 24 hour)';

    public const COOKIE_LOGIN =
        'SELECT `user_id`, `lastlogin`, `email`
		FROM `users` WHERE `user_id` = @userid';

    public const DELETE_ACCESSORY = 'DELETE FROM `accessories` WHERE `accessory_id` = @accessoryid';

    public const DELETE_ACCESSORY_RESOURCES = 'DELETE FROM `resource_accessories` WHERE `accessory_id` = @accessoryid';

    public const DELETE_ATTRIBUTE = 'DELETE FROM `custom_attributes` WHERE `custom_attribute_id` = @custom_attribute_id';

    public const DELETE_ATTRIBUTE_VALUES = 'DELETE FROM `custom_attribute_values` WHERE `custom_attribute_id` = @custom_attribute_id';

    public const DELETE_ATTRIBUTE_COLOR_RULES = 'DELETE FROM `reservation_color_rules` WHERE `custom_attribute_id` = @custom_attribute_id';

    public const DELETE_ACCOUNT_ACTIVATION = 'DELETE FROM `account_activation` WHERE `activation_code` = @activation_code';

    public const DELETE_ANNOUNCEMENT = 'DELETE FROM `announcements` WHERE `announcementid` = @announcementid';

    public const DELETE_BLACKOUT_SERIES = 'DELETE `blackout_series` FROM `blackout_series`
		INNER JOIN `blackout_instances` ON `blackout_series`.`blackout_series_id` = `blackout_instances`.`blackout_series_id`
		WHERE `blackout_instance_id` = @blackout_instance_id';

    public const DELETE_CUSTOM_LAYOUT_PERIOD = 'DELETE FROM `custom_time_blocks`
      WHERE `start_time` = @startTime AND
        `layout_id` = (select `layout_id` from `schedules` where `schedule_id` = @scheduleid)';

    public const DELETE_BLACKOUT_INSTANCE = 'DELETE FROM `blackout_instances` WHERE `blackout_instance_id` = @blackout_instance_id';

    public const DELETE_EMAIL_PREFERENCE =
        'DELETE FROM `user_email_preferences` WHERE `user_id` = @userid AND `event_category` = @event_category AND `event_type` = @event_type';

    public const DELETE_GROUP = 'DELETE FROM `groups` WHERE `group_id` = @groupid';

    public const DELETE_GROUP_RESOURCE_PERMISSION =
        'DELETE	FROM `group_resource_permissions` WHERE `group_id` = @groupid AND `resource_id` = @resourceid';

    public const DELETE_GROUP_ROLE = 'DELETE FROM `group_roles` WHERE `group_id` = @groupid AND `role_id` = @roleid';

    public const DELETE_ORPHAN_LAYOUTS = 'DELETE `l`.* FROM `layouts` `l` LEFT JOIN `schedules` `s` ON `l`.`layout_id` = `s`.`layout_id` WHERE `s`.`layout_id` IS NULL';

    public const DELETE_PAYMENT_CONFIGURATION = 'DELETE FROM `payment_configuration` WHERE `credit_count` = @credit_count';

    public const DELETE_PAYMENT_GATEWAY_SETTINGS = 'DELETE FROM `payment_gateway_settings` WHERE `gateway_type` = @gateway_type';

    public const DELETE_PEAK_TIMES = 'DELETE FROM `peak_times` WHERE `schedule_id` = @scheduleid';

    public const DELETE_QUOTA = 'DELETE FROM `quotas` WHERE `quota_id` = @quotaid';

    public const DELETE_RESERVATION_COLOR_RULE_COMMAND = 'DELETE FROM `reservation_color_rules` WHERE `reservation_color_rule_id` = @reservation_color_rule_id';

    public const DELETE_RESERVATION_WAITLIST_COMMAND = 'DELETE FROM `reservation_waitlist_requests` WHERE `reservation_waitlist_request_id` = @reservation_waitlist_request_id';

    public const DELETE_RESOURCE_COMMAND = 'DELETE FROM `resources` WHERE `resource_id` = @resourceid';

    public const DELETE_RESOURCE_GROUP_COMMAND = 'DELETE FROM `resource_groups` WHERE `resource_group_id` = @resourcegroupid';

    public const DELETE_RESOURCE_RESERVATIONS_COMMAND =
        'DELETE `s`.*
		FROM `reservation_series` `s`
		INNER JOIN `reservation_resources` `rs` ON `s`.`series_id` = `rs`.`series_id`
		WHERE `rs`.`resource_id` = @resourceid';

    public const DELETE_RESOURCE_IMAGES = 'DELETE FROM `resource_images` WHERE `resource_id` = @resourceid';

    public const DELETE_RESOURCE_STATUS_REASON_COMMAND = 'DELETE FROM `resource_status_reasons` WHERE `resource_status_reason_id` = @resource_status_reason_id';

    public const DELETE_RESOURCE_TYPE_COMMAND = 'DELETE FROM `resource_types` WHERE `resource_type_id` = @resource_type_id';

    public const DELETE_SAVED_REPORT = 'DELETE FROM `saved_reports` WHERE `saved_report_id` = @report_id AND `user_id` = @userid';

    public const DELETE_SCHEDULE = 'DELETE FROM `schedules` WHERE `schedule_id` = @scheduleid';

    public const DELETE_SERIES =
        'UPDATE `reservation_series`
		    SET `status_id` = @statusid,
			`last_modified` = @dateModified,
			`last_action_by` = @last_action_by
		  WHERE `series_id` = @seriesid';

    public const DELETE_SERIES_PERMANENT = 'DELETE FROM `reservation_series` WHERE `series_id` = @seriesid';

    public const DELETE_TERMS_OF_SERVICE = 'DELETE FROM `terms_of_service`';

    public const DELETE_USER = 'DELETE FROM `users` WHERE `user_id` = @userid';

    public const DELETE_USER_GROUP = 'DELETE FROM `user_groups` WHERE `user_id` = @userid AND `group_id` = @groupid';

    public const DELETE_USER_RESOURCE_PERMISSION =
        'DELETE	FROM `user_resource_permissions` WHERE `user_id` = @userid AND `resource_id` = @resourceid';

    public const DELETE_USER_SESSION =
        'DELETE	FROM `user_session` WHERE `session_token` = @session_token';

    public const LOG_CREDIT_ACTIVITY_COMMAND =
        'INSERT INTO `credit_log` (`user_id`, `original_credit_count`, `credit_count`, `credit_note`, `date_created`)
            VALUES (@userid, @original_credit_count, @credit_count, @credit_note, @dateCreated)';

    public const LOGIN_USER =
        'SELECT * FROM `users` WHERE (`username` = @username OR `email` = @username)';

    public const GET_ACCESSORY_BY_ID = 'SELECT * FROM `accessories` WHERE `accessory_id` = @accessoryid';

    public const GET_ACCESSORY_RESOURCES = 'SELECT * FROM `resource_accessories` WHERE `accessory_id` = @accessoryid';

    public const GET_ACCESSORY_LIST =
        'SELECT *, `rs`.`status_id` as `status_id`
		FROM `reservation_instances` `ri`
		INNER JOIN `reservation_series` `rs` ON `ri`.`series_id` = `rs`.`series_id`
		INNER JOIN `reservation_accessories` `ar` ON `ar`.`series_id` = `rs`.`series_id`
		INNER JOIN `accessories` `a` on `ar`.`accessory_id` = `a`.`accessory_id`
		WHERE
			(
				(`ri`.`start_date` >= @startDate AND `ri`.`start_date` <= @endDate)
				OR
				(`ri`.`end_date` >= @startDate AND `ri`.`end_date` <= @endDate)
				OR
				(`ri`.`start_date` <= @startDate AND `ri`.`end_date` >= @endDate)
			) AND
			`rs`.`status_id` <> 2
		ORDER BY
			`ri`.`start_date` ASC';

    public const GET_ALL_ACCESSORIES =
        'SELECT `a`.*, `c`.`num_resources`,
			(SELECT GROUP_CONCAT(CONCAT(`ra`.`resource_id`, ",", COALESCE(`ra`.`minimum_quantity`,""), ",",  COALESCE(`ra`.`maximum_quantity`,"")) SEPARATOR "!sep!")
				FROM `resource_accessories` `ra` WHERE `ra`.`accessory_id` = `a`.`accessory_id`) as `resource_accessory_list`
 			FROM `accessories` `a`
			LEFT JOIN (
				SELECT `accessory_id`, COUNT(*) AS `num_resources`
				FROM `resource_accessories` `ra`
				GROUP BY `ra`.`accessory_id`
				) AS `c` ON `a`.`accessory_id` = `c`.`accessory_id`

 			ORDER BY `accessory_name`';

    public const GET_ALL_ANNOUNCEMENTS = 'SELECT `a`.*,
			(SELECT GROUP_CONCAT(`ag`.`group_id`) FROM `announcement_groups` `ag` WHERE `ag`.`announcementid` = `a`.`announcementid`) as `group_ids`,
			(SELECT GROUP_CONCAT(`ar`.`resource_id`) FROM `announcement_resources` `ar` WHERE `ar`.`announcementid` = `a`.`announcementid`) as `resource_ids`
			FROM `announcements` `a` ORDER BY `start_date`';

    public const GET_ALL_APPLICATION_ADMINS = 'SELECT *
            FROM `users`
            WHERE `status_id` = @user_statusid AND
            (`user_id` IN (
                SELECT `user_id`
                FROM `user_groups` `ug`
                INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
                INNER JOIN `group_roles` `gr` ON `g`.`group_id` = `gr`.`group_id`
                INNER JOIN `roles` ON `roles`.`role_id` = `gr`.`role_id` AND `roles`.`role_level` = @role_level
              ) OR `email` IN (@email))
              GROUP BY `user_id`';

    public const GET_ALL_CREDIT_LOGS = 'SELECT `cl`.*, `u`.`fname`, `u`.`lname`, `u`.`email` FROM `credit_log` `cl`
            LEFT JOIN `users` `u` ON `cl`.`user_id` = `u`.`user_id`
            WHERE (@userid = -1 or `cl`.`user_id` = @userid)
            ORDER BY `cl`.`date_created` DESC';

    public const GET_ALL_GROUPS =
        'SELECT `g`.*, `admin_group`.`name` as `admin_group_name`,
			(SELECT GROUP_CONCAT(`gr`.`role_id`) FROM `group_roles` `gr` WHERE `gr`.`group_id` = `g`.`group_id`) as `group_role_list`
		FROM `groups` `g`
		LEFT JOIN `groups` `admin_group` ON `g`.`admin_group_id` = `admin_group`.`group_id`
		ORDER BY `g`.`name`';

    public const GET_ALL_GROUPS_BY_ROLE =
        'SELECT `g`.*,
			(SELECT GROUP_CONCAT(`gr`.`role_id`) FROM `group_roles` `gr` WHERE `gr`.`group_id` = `g`.`group_id`) as `group_role_list`
		FROM `groups` `g`
		INNER JOIN `group_roles` `gr` ON `g`.`group_id` = `gr`.`group_id`
		INNER JOIN `roles` `r` ON `r`.`role_id` = `gr`.`role_id`
		WHERE `r`.`role_level` = @role_level
		ORDER BY `g`.`name`';

    public const GET_ALL_GROUP_RESOURCE_PERMISSIONS = 'SELECT `grp`.*, `r`.`name`
        FROM `group_resource_permissions` `grp`
        INNER JOIN `resources` `r` ON `grp`.`resource_id` = `r`.`resource_id`';

    public const GET_ALL_GROUP_ADMINS =
        'SELECT `u`.* FROM `users` `u`
        INNER JOIN `user_groups` `ug` ON `u`.`user_id` = `ug`.`user_id`
        WHERE `status_id` = @user_statusid AND `ug`.`group_id` IN (
          SELECT `g`.`admin_group_id` FROM `user_groups` `ug`
          INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
          WHERE `ug`.`user_id` = @userid AND `g`.`admin_group_id` IS NOT NULL)';

    public const GET_ALL_GROUP_USERS =
        'SELECT `u`.*, (SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`, \'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
			FROM `custom_attribute_values` `cav` WHERE `cav`.`entity_id` = `u`.`user_id` AND `cav`.`attribute_category` = 2) as `attribute_list`
		FROM `users` `u`
		WHERE `u`.`user_id` IN (
		  SELECT DISTINCT (`ug`.`user_id`) FROM `user_groups` `ug`
		  INNER JOIN `groups` `g` ON `g`.`group_id` = `ug`.`group_id`
		  WHERE `g`.`group_id` IN (@groupid)
		  )
		AND (0 = @user_statusid OR `u`.`status_id` = @user_statusid)
		ORDER BY `u`.`lname`, `u`.`fname`';

    public const GET_ALL_QUOTAS =
        'SELECT `q`.*, `r`.`name` as `resource_name`, `g`.`name` as `group_name`, `s`.`name` as `schedule_name`
		FROM `quotas` `q`
		LEFT JOIN `resources` `r` ON `r`.`resource_id` = `q`.`resource_id`
		LEFT JOIN `groups` `g` ON `g`.`group_id` = `q`.`group_id`
		LEFT JOIN `schedules` `s` ON `s`.`schedule_id` = `q`.`schedule_id`';

    public const GET_ALL_REMINDERS = 'SELECT * FROM `reminders`';

    public const GET_ALL_RESERVATION_WAITLIST_REQUESTS = 'SELECT * FROM `reservation_waitlist_requests`';

    public const GET_ALL_RESOURCES =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`,
		(SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`, \'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
						FROM `custom_attribute_values` `cav` WHERE `cav`.`entity_id` = `r`.`resource_id` AND `cav`.`attribute_category` = 4) as `attribute_list`,
		(SELECT GROUP_CONCAT(`rga`.`resource_group_id` SEPARATOR "!sep!") FROM `resource_group_assignment` `rga` WHERE `rga`.`resource_id` = `r`.`resource_id`) AS `group_list`,
		(SELECT GROUP_CONCAT(`ri`.`image_name` SEPARATOR "!sep!") FROM `resource_images` `ri` WHERE `ri`.`resource_id` = `r`.`resource_id`) AS `image_list`
		FROM `resources` as `r`
		INNER JOIN `schedules` as `s` ON `r`.`schedule_id` = `s`.`schedule_id`
		ORDER BY COALESCE(`r`.`sort_order`,0), `r`.`name`';

	public const GET_USER_RESOURCES =
		'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`,
		(SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`, \'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
						FROM `custom_attribute_values` `cav` WHERE `cav`.`entity_id` = `r`.`resource_id` AND `cav`.`attribute_category` = 4) as `attribute_list`,
		(SELECT GROUP_CONCAT(`rga`.`resource_group_id` SEPARATOR "!sep!") FROM `resource_group_assignment` `rga` WHERE `rga`.`resource_id` = `r`.`resource_id`) AS `group_list`,
		(SELECT GROUP_CONCAT(`ri`.`image_name` SEPARATOR "!sep!") FROM `resource_images` `ri` WHERE `ri`.`resource_id` = `r`.`resource_id`) AS `image_list`
		FROM `resources` as `r`
		INNER JOIN `schedules` as `s` ON `r`.`schedule_id` = `s`.`schedule_id`
		WHERE resource_id IN (@resourceids)
		ORDER BY COALESCE(`r`.`sort_order`,0), `r`.`name`';

    public const GET_ALL_RESOURCE_GROUPS = 'SELECT * FROM `resource_groups` ORDER BY `parent_id`, `resource_group_name`';

    public const GET_ALL_RESOURCE_GROUP_ASSIGNMENTS = 'SELECT `r`.*, `a`.`resource_group_id`
		FROM `resource_group_assignment` as `a`
		INNER JOIN `resources` as `r` ON `r`.`resource_id` = `a`.`resource_id`
		WHERE (-1 = @scheduleid OR `r`.`schedule_id` = @scheduleid)
		ORDER BY COALESCE(`r`.`sort_order`,0), `r`.`name`';

    public const GET_ALL_RESOURCE_ADMINS =
        'SELECT *
        FROM `users`
        WHERE `status_id` = @user_statusid AND
        `user_id` IN (
            SELECT `user_id`
            FROM `user_groups` `ug`
            INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
            INNER JOIN `group_roles` `gr` ON `g`.`group_id` = `gr`.`group_id`
            INNER JOIN `roles` ON `roles`.`role_id` = `gr`.`role_id` AND `roles`.`role_level` = @role_level
            INNER JOIN `resources` `r` ON `g`.`group_id` = `r`.`admin_group_id`
            WHERE `r`.`resource_id` = @resourceid
          )';

    public const GET_ALL_RESOURCE_STATUS_REASONS = 'SELECT * FROM `resource_status_reasons`';

    public const GET_ALL_RESOURCE_TYPES = 'SELECT *,
			(SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`, \'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
							FROM `custom_attribute_values` `cav` INNER JOIN `custom_attribute_entities` `cae` on `cav`.`custom_attribute_id` = cae.custom_attribute_id
							WHERE `cav`.`entity_id` = `r`.`resource_type_id` AND `cav`.`attribute_category` = 5) as `attribute_list`
							FROM `resource_types` `r`';

    public const GET_ALL_TRANSACTION_LOGS = 'SELECT `ptl`.*, `u`.`fname`, `u`.`lname`, `u`.`email`, SUM(`total_refund_amount`) as `refund_amount`
            FROM `payment_transaction_log` `ptl`
            LEFT JOIN `refund_transaction_log` `refunds` on `ptl`.`payment_transaction_log_id` = `refunds`.`payment_transaction_log_id`
            LEFT JOIN `users` `u` ON `ptl`.`user_id` = `u`.`user_id`
            WHERE (@userid = -1 OR `ptl`.`user_id` = @userid)
            GROUP BY `ptl`.`payment_transaction_log_id`
            ORDER BY `date_created` DESC';

    public const GET_ALL_SAVED_REPORTS = 'SELECT * FROM `saved_reports` WHERE `user_id` = @userid ORDER BY `report_name`, `date_created`';

    public const GET_ALL_SCHEDULES = 'SELECT `s`.*, `l`.`timezone` FROM `schedules` `s` INNER JOIN `layouts` `l` ON `s`.`layout_id` = `l`.`layout_id` ORDER BY `s`.`name`';

    public const GET_ALL_USERS_BY_STATUS =
        'SELECT `u`.*,
			(SELECT GROUP_CONCAT(CONCAT(`p`.`name`, "=", `p`.`value`) SEPARATOR "!sep!")
						FROM `user_preferences` `p` WHERE `u`.`user_id` = `p`.`user_id`) as `preferences`,
			(SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`, \'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
						FROM `custom_attribute_values` `cav` WHERE `cav`.`entity_id` = `u`.`user_id` AND `cav`.`attribute_category` = 2) as attribute_list,
            (SELECT GROUP_CONCAT(`ug`.`group_id` SEPARATOR "!sep!")
                        FROM `user_groups` `ug` WHERE `ug`.`user_id` = `u`.`user_id`) as `group_ids`
			FROM `users` `u`
			WHERE (0 = @user_statusid OR `status_id` = @user_statusid) ORDER BY `lname`, `fname`';

    public const GET_ANNOUNCEMENT_BY_ID = 'SELECT `a`.*,
 		(SELECT GROUP_CONCAT(`ag`.`group_id`) FROM `announcement_groups` `ag` WHERE `ag`.`announcementid` = `a`.`announcementid`) as `group_ids`,
		(SELECT GROUP_CONCAT(`ar`.`resource_id`) FROM `announcement_resources` `ar` WHERE `ar`.`announcementid` = `a`.`announcementid`) as `resource_ids`
		FROM `announcements` `a` WHERE `a`.`announcementid` = @announcementid';

    public const GET_ATTRIBUTES_BASE_QUERY = 'SELECT `a`.*,
				(SELECT GROUP_CONCAT(`e`.`entity_id` SEPARATOR "!sep!")
							FROM `custom_attribute_entities` `e` WHERE `e`.`custom_attribute_id` = `a`.`custom_attribute_id` ORDER BY `e`.`entity_id`) as `entity_ids`,
				(CASE
				WHEN `a`.`attribute_category` = 2 THEN (SELECT GROUP_CONCAT(CONCAT(`u`.`fname`, " ", `u`.`lname`) SEPARATOR "!sep!")
													FROM `users` `u` INNER JOIN `custom_attribute_entities` `e`
													WHERE `e`.`custom_attribute_id` = `a`.`custom_attribute_id` AND `u`.`user_id` = `e`.`entity_id` ORDER BY `e`.`entity_id`)
				WHEN `a`.`attribute_category` = 4 THEN (SELECT GROUP_CONCAT(`r`.`name` SEPARATOR "!sep!")
													FROM `resources` `r` INNER JOIN `custom_attribute_entities` `e`
													WHERE `e`.`custom_attribute_id` = `a`.`custom_attribute_id` AND `r`.`resource_id` = `e`.`entity_id` ORDER BY `e`.`entity_id`)
				WHEN `a`.`attribute_category` = 5  THEN (SELECT GROUP_CONCAT(`rt`.`resource_type_name` SEPARATOR "!sep!")
													FROM `resource_types` `rt` INNER JOIN `custom_attribute_entities` `e`
													WHERE `e`.`custom_attribute_id` = `a`.`custom_attribute_id` AND `rt`.`resource_type_id` = `e`.`entity_id` ORDER BY `e`.`entity_id`)
				ELSE null
				END) as `entity_descriptions`,
				(CASE
				WHEN `a`.`secondary_category` = 2 THEN (SELECT GROUP_CONCAT(CONCAT( `fname`, " ", `lname` ) SEPARATOR  "!sep!" ) FROM `users` WHERE FIND_IN_SET( `user_id`, `a`.`secondary_entity_ids` ))
				WHEN `a`.`secondary_category` = 4 THEN (SELECT GROUP_CONCAT(`name` SEPARATOR  "!sep!" ) FROM `resources` WHERE FIND_IN_SET( `resource_id`, `a`.`secondary_entity_ids` ))
				WHEN `a`.`secondary_category` = 5 THEN (SELECT GROUP_CONCAT(`resource_type_name` SEPARATOR  "!sep!" ) FROM `resource_types` WHERE FIND_IN_SET( `resource_type_id`, `a`.`secondary_entity_ids` ))
				ELSE null
				END) as `secondary_entity_descriptions`
				FROM `custom_attributes` `a`';

    public const GET_ATTRIBUTES_BY_CATEGORY_WHERE = ' WHERE `a`.`attribute_category` = @attribute_category ORDER BY `a`.`sort_order`, `a`.`display_label`';

    public const GET_ATTRIBUTE_BY_ID_WHERE = '	WHERE `custom_attribute_id` = @custom_attribute_id';

    public const GET_ATTRIBUTE_ALL_VALUES = 'SELECT * FROM `custom_attribute_values` WHERE `attribute_category` = @attribute_category';

    public const GET_ATTRIBUTE_MULTIPLE_VALUES = 'SELECT *
		FROM `custom_attribute_values` WHERE `entity_id` IN (@entity_ids) AND `attribute_category` = @attribute_category';

    public const GET_ATTRIBUTE_VALUES = 'SELECT `cav`.*, `ca`.`display_label`, `ca`.`admin_only`
		FROM `custom_attribute_values` `cav`
		INNER JOIN `custom_attributes` `ca` ON `ca`.`custom_attribute_id` = `cav`.`custom_attribute_id`
		WHERE `cav`.`attribute_category` = @attribute_category AND `cav`.`entity_id` = @entity_id';

    public const GET_BLACKOUT_LIST =
        'SELECT *
		FROM `blackout_instances` `bi`
		INNER JOIN `blackout_series` `bs` ON `bi`.`blackout_series_id` = `bs`.`blackout_series_id`
		INNER JOIN `blackout_series_resources` `bsr` ON  `bi`.`blackout_series_id` = `bsr`.`blackout_series_id`
		INNER JOIN `resources` `r` on `bsr`.`resource_id` = `r`.`resource_id`
		INNER JOIN `users` `u` ON `u`.`user_id` = `bs`.`owner_id`
		WHERE
			(
				(`bi`.`start_date` >= @startDate AND `bi`.`start_date` <= @endDate)
				OR
				(`bi`.`end_date` >= @startDate AND `bi`.`end_date` <= @endDate)
				OR
				(`bi`.`start_date` <= @startDate AND `bi`.`end_date` >= @endDate)
			) AND
			(@scheduleid = -1 OR `r`.`schedule_id` = @scheduleid) AND (@all_resources = 1 OR `r`.`resource_id` IN(@resourceid))
		ORDER BY `bi`.`start_date` ASC';

    public const GET_BLACKOUT_LIST_FULL =
        'SELECT `bi`.*, `r`.`resource_id`, `r`.`name`, `u`.*, `bs`.`description`, `bs`.`title`, `bs`.`repeat_type`, `bs`.`repeat_options`, `schedules`.`schedule_id`
					FROM `blackout_instances` `bi`
					INNER JOIN `blackout_series` `bs` ON `bi`.`blackout_series_id` = `bs`.`blackout_series_id`
					INNER JOIN `blackout_series_resources` `bsr` ON  `bi`.`blackout_series_id` = `bsr`.`blackout_series_id`
					INNER JOIN `resources` `r` on `bsr`.`resource_id` = `r`.`resource_id`
					INNER JOIN `schedules` on `r`.`schedule_id` = `schedules`.`schedule_id`
					INNER JOIN `users` `u` ON `u`.`user_id` = `bs`.`owner_id`
		ORDER BY `bi`.`start_date` ASC';

    public const GET_BLACKOUT_INSTANCES = 'SELECT * FROM `blackout_instances` WHERE `blackout_series_id` = @blackout_series_id';

    public const GET_BLACKOUT_SERIES_BY_BLACKOUT_ID = 'SELECT *
		FROM `blackout_series` `bs`
		INNER JOIN `blackout_instances` `bi` ON `bi`.`blackout_series_id` = `bs`.`blackout_series_id`
		WHERE `blackout_instance_id` = @blackout_instance_id';

    public const GET_BLACKOUT_RESOURCES = 'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`
		FROM `blackout_series_resources` `rr`
		INNER JOIN `resources` `r` ON `rr`.`resource_id` = `r`.`resource_id`
		INNER JOIN `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
		WHERE `rr`.`blackout_series_id` = @blackout_series_id
		ORDER BY `r`.`name`';

    public const GET_CUSTOM_LAYOUT = 'SELECT `l`.`timezone`, `ctb`.*
        FROM `layouts` `l`
        INNER JOIN `custom_time_blocks` `ctb` ON `l`.`layout_id` = `ctb`.`layout_id`
        INNER JOIN `schedules` `s` ON `s`.`layout_id` = `l`.`layout_id`
        WHERE `ctb`.`start_time` >= @startDate AND `ctb`.`end_time` <= @endDate AND `s`.`schedule_id` = @scheduleid
        ORDER BY `ctb`.`start_time`';

    public const GET_DASHBOARD_ANNOUNCEMENTS =
        'SELECT `a`.*,
			(SELECT GROUP_CONCAT(`ag`.`group_id`) FROM `announcement_groups` `ag` WHERE `ag`.`announcementid` = `a`.`announcementid`) as `group_ids`,
			(SELECT GROUP_CONCAT(`ar`.`resource_id`) FROM `announcement_resources` `ar` WHERE `ar`.`announcementid` = `a`.`announcementid`) as `resource_ids`
			FROM `announcements` `a`
		WHERE ((`start_date` <= @current_date AND `end_date` >= @current_date) OR (`end_date` IS NULL)) AND (@display_page = -1 OR @display_page = `display_page`)
		ORDER BY `priority`, `start_date`, `end_date`';

    public const GET_GROUP_BY_ID =
        'SELECT *
		FROM `groups`
		WHERE `group_id` = @groupid';

    public const GET_GROUPS_I_CAN_MANAGE = 'SELECT `g`.`group_id`, `g`.`name`
		FROM `groups` `g`
		INNER JOIN `groups` `a` ON `g`.`admin_group_id` = `a`.`group_id`
		INNER JOIN `user_groups` `ug` on `ug`.`group_id` = `a`.`group_id`
		WHERE `ug`.`user_id` = @userid';

    public const GET_GROUP_RESOURCE_PERMISSIONS =
        'SELECT *
		FROM `group_resource_permissions`
		WHERE `group_id` = @groupid';

	public const GET_GROUP_RESOURCES_ID = 
		'SELECT `resource_id` FROM `resources` 
		WHERE `admin_group_id` = @groupid';

	public const GET_GROUP_SCHEDULES_ID =
		'SELECT `schedule_id` FROM `schedules`
		WHERE `admin_group_id` = @groupid';

	public const GET_RESOURCE_ADMIN_RESOURCES = 
		'SELECT `resource_id`, `name` , `schedule_id`, `admin_group_id`
		FROM `resources` 
		WHERE `admin_group_id` 
		IN (
			SELECT `g`.group_id
			FROM `user_groups` `ug`
			INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
			WHERE `user_id` = @userid
			)';

	public const GET_SHCEDULE_ADMIN_SCHEDULES = 
		'SELECT `schedule_id`, `name` , `admin_group_id`
		FROM `schedules`
		WHERE `admin_group_id`
		IN (
			SELECT `g`.group_id
			FROM `user_groups` `ug`
			INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
			WHERE `user_id` = @userid
			);';

	public const GET_SCHEDULE_ADMIN_RESOURCES = 
		'SELECT `resource_id`, `name` , `schedule_id`, `admin_group_id`
		FROM `resources`
		WHERE `schedule_id` 
		IN
			(SELECT `schedule_id`
			FROM `schedules`
			WHERE `admin_group_id`
			IN (
				SELECT `g`.group_id
				FROM `user_groups` `ug`
				INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
				WHERE `user_id` = @userid
				))';

    public const GET_GROUP_ROLES =
        'SELECT `r`.*
		FROM `roles` `r`
		INNER JOIN `group_roles` `gr` ON `r`.`role_id` = `gr`.`role_id`
		WHERE `gr`.`group_id` = @groupid';

    public const GET_REMINDER_NOTICES = 'SELECT DISTINCT
		`rs`.*,
		`ri`.*,
		`u`.`fname`, `u`.`lname`, `u`.`language`, `u`.`timezone`, `u`.`email`,
		(SELECT GROUP_CONCAT(`r`.`name`  SEPARATOR "!sep!")
			FROM `reservation_resources`
			INNER JOIN `resources` `r` on `reservation_resources`.`resource_id` = `r`.`resource_id` WHERE `reservation_resources`.`series_id` = `rs`.`series_id`) as `resource_names`
		FROM `reservation_instances` `ri`
		INNER JOIN `reservation_series` `rs` ON `ri`.`series_id` = `rs`.`series_id`
		INNER JOIN `reservation_reminders` `rr` on `ri`.`series_id` = `rr`.`series_id` INNER JOIN `reservation_users` `ru` on `ru`.`reservation_instance_id` = `ri`.`reservation_instance_id`
		INNER JOIN `users` `u` on `ru`.`user_id` = `u`.`user_id`
		WHERE `rs`.`status_id` <> 2 AND ((`reminder_type` = @reminder_type AND @reminder_type=0 AND date_sub(`start_date`,INTERVAL `rr`.`minutes_prior` MINUTE) = @current_date) OR (`reminder_type` = @reminder_type AND @reminder_type=1 AND date_sub(`end_date`,INTERVAL `rr`.`minutes_prior` MINUTE) = @current_date))';

    public const GET_REMINDERS_BY_USER = 'SELECT * FROM `reminders` WHERE `user_id` = @user_id';

    public const GET_REMINDERS_BY_REFNUMBER = 'SELECT * FROM `reminders` WHERE `refnumber` = @refnumber';

    public const GET_RESOURCE_BY_CONTACT_INFO =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`
			FROM `resources` `r`
			INNER JOIN `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
			WHERE `r`.`contact_info` = @contact_info';

    public const GET_RESOURCE_BY_ID =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`,
				(SELECT GROUP_CONCAT( `ri`.`image_name` SEPARATOR  "!sep!" ) FROM `resource_images` `ri` WHERE `ri`.`resource_id` = `r`.`resource_id`) AS `image_list`
			FROM `resources` `r`
			INNER JOIN `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
			WHERE `r`.`resource_id` = @resourceid';

    public const GET_RESOURCE_BY_PUBLIC_ID =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`,
				(SELECT GROUP_CONCAT( `ri`.`image_name` SEPARATOR  "!sep!" ) FROM `resource_images` `ri` WHERE `ri`.`resource_id` = `r`.`resource_id`) AS `image_list`
			FROM `resources` `r`
			INNER JOIN `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
			WHERE `r`.`public_id` = @publicid';

    public const GET_RESOURCES_PUBLIC = 'SELECT * FROM `resources` WHERE `allow_calendar_subscription` = 1 AND `public_id` IS NOT NULL';

    public const GET_RESOURCE_BY_NAME =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id`,
				(SELECT GROUP_CONCAT( `ri`.`image_name` SEPARATOR  "!sep!" ) FROM `resource_images` `ri` WHERE `ri`.`resource_id` = `r`.`resource_id`) AS `image_list`
			FROM `resources` `r`
			INNER JOIN  `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
			WHERE `r`.`name` = @resource_name';

    public const GET_RESOURCE_GROUP_BY_ID = 'SELECT * FROM `resource_groups` WHERE `resource_group_id` = @resourcegroupid';

    public const GET_RESOURCE_GROUP_ASSIGNMENTS = 'SELECT * FROM `resource_group_assignment` WHERE `resource_id` = @resourceid';

    public const GET_RESOURCE_GROUP_BY_PUBLIC_ID = 'SELECT * FROM `resource_groups` WHERE `public_id` = @publicid';

    public const GET_RESOURCE_TYPE_BY_ID = 'SELECT * FROM `resource_types` WHERE `resource_type_id` = @resource_type_id';

    public const GET_RESOURCE_TYPE_BY_NAME = 'SELECT * FROM `resource_types` WHERE `resource_type_name` = @resource_type_name';

    public const GET_RESERVATION_BY_ID =
        'SELECT *
		FROM `reservation_instances` `r`
		INNER JOIN `reservation_series` `rs` ON `r`.`series_id` = `rs`.`series_id`
		WHERE
			`r`.`reservation_instance_id` = @reservationid AND
			`status_id` <> 2';

    public const GET_RESERVATION_BY_REFERENCE_NUMBER =
        'SELECT *
		FROM `reservation_instances` `r`
		INNER JOIN `reservation_series` `rs` ON `r`.`series_id` = `rs`.`series_id`
		WHERE
			`reference_number` = @referenceNumber AND
			`status_id` <> 2';

    public const GET_RESERVATION_FOR_EDITING =
        'SELECT `ri`.*, `rs`.*, `rr`.*, `u`.`user_id`, `u`.`fname`, `u`.`lname`, `u`.`email`, `u`.`phone`, `r`.`schedule_id`, `r`.`name`, `rs`.`status_id` as `status_id`
		FROM `reservation_instances` `ri`
		INNER JOIN `reservation_series` `rs` ON `rs`.`series_id` = `ri`.`series_id`
		INNER JOIN `users` `u` ON `u`.`user_id` = `rs`.`owner_id`
		INNER JOIN `reservation_resources` `rr` ON `rs`.`series_id` = `rr`.`series_id` AND `rr`.`resource_level_id` = @resourceLevelId
		INNER JOIN `resources` `r` ON `r`.`resource_id` = `rr`.`resource_id`
		WHERE
			`reference_number` = @referenceNumber AND
			`rs`.`status_id` <> 2';

    public const GET_RESERVATION_LIST_TEMPLATE =
        'SELECT
				[SELECT_TOKEN]
			FROM `reservation_instances` `ri`
			INNER JOIN `reservation_series` `rs` ON `rs`.`series_id` = `ri`.`series_id`
			INNER JOIN `reservation_users` `ru` ON `ru`.`reservation_instance_id` = `ri`.`reservation_instance_id`
			INNER JOIN `users` ON `users`.`user_id` = `rs`.`owner_id`
			INNER JOIN `users` AS `owner` ON `owner`.`user_id` = `rs`.`owner_id`
			INNER JOIN `reservation_resources` `rr` ON `rs`.`series_id` = `rr`.`series_id`
			INNER JOIN `resources` ON `rr`.`resource_id` = `resources`.`resource_id`
			INNER JOIN `schedules` ON `resources`.`schedule_id` = `schedules`.`schedule_id`
			LEFT JOIN `reservation_reminders` AS `start_reminder` ON `start_reminder`.`series_id` = `rs`.`series_id` AND `start_reminder`.`reminder_type` = 0
			LEFT JOIN `reservation_reminders` AS `end_reminder` ON `end_reminder`.`series_id` = `rs`.`series_id` AND `end_reminder`.`reminder_type` = 1
			[JOIN_TOKEN]
			WHERE `rs`.`status_id` <> 2
			[AND_TOKEN]
			ORDER BY `ri`.`start_date` ASC';

    public const GET_RESERVATION_ACCESSORIES =
        'SELECT *
		FROM `reservation_accessories` `ra`
		INNER JOIN `accessories` `a` ON `ra`.`accessory_id` = `a`.`accessory_id`
		WHERE `ra`.`series_id` = @seriesid';

    public const GET_RESERVATION_ATTACHMENT = 'SELECT * FROM `reservation_files` WHERE `file_id` = @file_id';

    public const GET_RESERVATION_ATTACHMENTS_FOR_SERIES = 'SELECT * FROM `reservation_files` WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_GUESTS =
        'SELECT	`rg`.*
		FROM `reservation_guests` `rg`
		WHERE `reservation_instance_id` = @reservationid';

    public const GET_RESERVATION_COLOR_RULES = 'SELECT * FROM `reservation_color_rules` `r`
		LEFT JOIN `custom_attributes` `ca` ON `ca`.`custom_attribute_id` = `r`.`custom_attribute_id`';

    public const GET_RESERVATION_COLOR_RULE = 'SELECT * FROM `reservation_color_rules` `r`
		LEFT JOIN `custom_attributes` `ca` ON `ca`.`custom_attribute_id` = `r`.`custom_attribute_id`
		WHERE `reservation_color_rule_id` = @reservation_color_rule_id';

    public const GET_RESERVATION_PARTICIPANTS =
        'SELECT
			`u`.`user_id`,
			`u`.`fname`,
			`u`.`lname`,
			`u`.`email`,
			`ru`.*
		FROM `reservation_users` `ru`
		INNER JOIN `users` `u` ON `ru`.`user_id` = `u`.`user_id`
		WHERE `reservation_instance_id` = @reservationid';

    public const GET_RESERVATION_REMINDERS = 'SELECT * FROM `reservation_reminders` WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_REPEAT_DATES = 'SELECT `start_date` FROM `reservation_instances` WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_RESOURCES =
        'SELECT `r`.*, `rr`.`resource_level_id`, `s`.`admin_group_id` as `s_admin_group_id`
		FROM `reservation_resources` `rr`
		INNER JOIN `resources` `r` ON `rr`.`resource_id` = `r`.`resource_id`
		INNER JOIN `schedules` `s` ON `r`.`schedule_id` = `s`.`schedule_id`
		WHERE `rr`.`series_id` = @seriesid
		ORDER BY `resource_level_id`, `r`.`name`';

    public const GET_RESERVATION_SERIES_GUESTS =
        'SELECT `rg`.*, `ri`.*
			FROM `reservation_guests` `rg`
			INNER JOIN `reservation_instances` `ri` ON `rg`.`reservation_instance_id` = `ri`.`reservation_instance_id`
			WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_SERIES_INSTANCES =
        'SELECT *
		FROM `reservation_instances`
		WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_SERIES_PARTICIPANTS =
        'SELECT `ru`.*, `ri`.*
		FROM `reservation_users` `ru`
		INNER JOIN `reservation_instances` `ri` ON `ru`.`reservation_instance_id` = `ri`.`reservation_instance_id`
		WHERE `series_id` = @seriesid';

    public const GET_RESERVATION_WAITLIST_REQUEST = 'SELECT * FROM `reservation_waitlist_requests` WHERE `reservation_waitlist_request_id` = @reservation_waitlist_request_id';

    public const GET_SCHEDULE_TIME_BLOCK_GROUPS =
        'SELECT
			`tb`.`label`,
			`tb`.`end_label`,
			`tb`.`start_time`,
			`tb`.`end_time`,
			`tb`.`availability_code`,
			`tb`.`day_of_week`,
			`l`.`timezone`,
			`l`.`layout_type`
		FROM
		`layouts` `l`
		INNER JOIN `schedules` `s` ON `l`.`layout_id` = `s`.`layout_id`
		LEFT JOIN `time_blocks` `tb` ON `tb`.`layout_id` = `l`.`layout_id`
		WHERE
			`s`.`schedule_id` = @scheduleid
		ORDER BY `tb`.`start_time`';

    public const GET_PEAK_TIMES = 'SELECT * FROM `peak_times` WHERE `schedule_id` = @scheduleid';

    public const GET_PAYMENT_CONFIGURATION = 'SELECT * FROM `payment_configuration`';

    public const GET_PAYMENT_GATEWAY_SETTINGS = 'SELECT * FROM `payment_gateway_settings` WHERE `gateway_type` = @gateway_type';

    public const GET_SAVED_REPORT = 'SELECT * FROM `saved_reports` WHERE `saved_report_id` = @report_id AND `user_id` = @userid';

    public const GET_SCHEDULE_BY_ID =
        'SELECT * FROM `schedules` `s`
		INNER JOIN `layouts` `l` ON `s`.`layout_id` = `l`.`layout_id`
		WHERE `schedule_id` = @scheduleid';

    public const GET_SCHEDULE_BY_PUBLIC_ID =
        'SELECT * FROM `schedules` `s`
        INNER JOIN `layouts` `l` ON `s`.`layout_id` = `l`.`layout_id`
        WHERE `public_id` = @publicid';

    public const GET_SCHEDULE_RESOURCES =
        'SELECT `r`.*, `s`.`admin_group_id` as `s_admin_group_id` FROM  `resources` as `r`
		INNER JOIN `schedules` as `s` ON `r`.`schedule_id` = `s`.`schedule_id`
		WHERE (-1 = @scheduleid OR `r`.`schedule_id` = @scheduleid) AND
			`r`.`status_id` <> 0
		ORDER BY COALESCE(`r`.`sort_order`,0), `r`.`name`';

    public const GET_SCHEDULES_PUBLIC = 'SELECT * FROM `schedules` WHERE `allow_calendar_subscription` = 1 AND `public_id` IS NOT NULL';

    public const GET_TRANSACTION_LOG =
        'SELECT `ptl`.*, SUM(`total_refund_amount`) as `refund_amount`
        FROM `payment_transaction_log` `ptl`
        LEFT JOIN `refund_transaction_log` `refunds` on `ptl`.`payment_transaction_log_id` = `refunds`.`payment_transaction_log_id`
        WHERE `ptl`.`payment_transaction_log_id` = @payment_transaction_log_id
        GROUP BY `ptl`.`payment_transaction_log_id`';

    public const GET_TERMS_OF_SERVICE = 'SELECT * FROM `terms_of_service`';

    public const GET_USERID_BY_ACTIVATION_CODE =
        'SELECT `a`.`user_id` FROM `account_activation` `a`
			INNER JOIN `users` `u` ON `u`.`user_id` = `a`.`user_id`
			WHERE `activation_code` = @activation_code AND `u`.`status_id` = @statusid';

    public const GET_USER_BY_ID = 'SELECT * FROM `users` WHERE `user_id` = @userid';

    public const GET_USER_BY_PUBLIC_ID = 'SELECT * FROM `users` WHERE `public_id` = @publicid';

    public const GET_USER_COUNT = 'SELECT COUNT(*) as `count` FROM `users`';

    public const GET_USER_EMAIL_PREFERENCES = 'SELECT * FROM `user_email_preferences` WHERE `user_id` = @userid';

    public const GET_USER_GROUPS =
        'SELECT `g`.*, `r`.`role_level`
		FROM `user_groups` `ug`
		INNER JOIN `groups` `g` ON `ug`.`group_id` = `g`.`group_id`
		LEFT JOIN `group_roles` `gr` ON `ug`.`group_id` = `gr`.`group_id`
		LEFT JOIN `roles` `r` ON `gr`.`role_id` = `r`.`role_id`
		WHERE `user_id` = @userid AND (@role_null is null OR `r`.`role_level` IN (@role_level) )';

    public const GET_USER_RESOURCE_PERMISSIONS =
        'SELECT
			`urp`.`user_id`, `urp`.`permission_type`, `r`.`resource_id`, `r`.`name`
		FROM
			`user_resource_permissions` `urp`, `resources` `r`
		WHERE
			`urp`.`user_id` = @userid AND `r`.`resource_id` = `urp`.`resource_id`';

    public const GET_USER_GROUP_RESOURCE_PERMISSIONS =
        'SELECT
			`grp`.`group_id`, `r`.`resource_id`, `r`.`name`, `grp`.`permission_type`
		FROM
			`group_resource_permissions` `grp`, `resources` `r`, `user_groups` `ug`
		WHERE
			`ug`.`user_id` = @userid AND `ug`.`group_id` = `grp`.`group_id` AND `grp`.`resource_id` = `r`.`resource_id`';

    public const GET_USER_ADMIN_GROUP_RESOURCE_PERMISSIONS =
        'SELECT `r`.`resource_id`, `r`.`name` FROM `resources` `r`
		WHERE `r`.`schedule_id` IN (SELECT `s`.`schedule_id` FROM `schedules` `s`
			INNER JOIN `groups` `g` ON `g`.`group_id` = `s`.`admin_group_id`
			INNER JOIN `user_groups` `ug` on `ug`.`group_id` = `g`.`group_id`
			WHERE `ug`.`user_id` = @userid)
		OR `r`.`resource_id` IN (SELECT `r2`.`resource_id` FROM `resources` `r2`
			INNER JOIN `groups` `g` ON `g`.`group_id` = `r2`.`admin_group_id`
			INNER JOIN `user_groups` `ug` on `ug`.`group_id` = `g`.`group_id`
			WHERE `ug`.`user_id` = @userid)';

    public const GET_USER_PREFERENCE = 'SELECT `value` FROM `user_preferences` WHERE `user_id` = @userid AND `name` = @name';

    public const GET_USER_PREFERENCES = 'SELECT `name`, `value` FROM `user_preferences` WHERE `user_id` = @userid';

    public const GET_USER_ROLES =
        'SELECT
			`user_id`, `user_level`
		FROM
			`roles` `r`
		INNER JOIN
			`user_roles` `ur` on `r`.`role_id` = `ur`.`role_id`
		WHERE
			`ur`.`user_id` = @userid';

    public const GET_USER_SESSION_BY_SESSION_TOKEN = 'SELECT * FROM `user_session` WHERE `session_token` = @session_token';

    public const GET_USER_SESSION_BY_USERID = 'SELECT * FROM `user_session` WHERE `user_id` = @userid';

    public const GET_VERSION = 'SELECT * FROM `dbversion` order by `version_number` desc limit 0,1';

    public const GET_RESOURCE_GROUP_PERMISSION = 'SELECT
				`g`.*, `grp`.`permission_type`
			FROM
				`group_resource_permissions` `grp`, `resources` `r`, `groups` `g`
			WHERE
				`r`.`resource_id` = @resourceid AND `r`.`resource_id` = `grp`.`resource_id` AND `g`.`group_id` = `grp`.`group_id`';

    public const GET_RESOURCE_USER_PERMISSION = 'SELECT
				`u`.*, `urp`.`permission_type`
			FROM
				`user_resource_permissions` `urp`, `resources` `r`, `users` `u`
			WHERE
				`r`.`resource_id` = @resourceid AND `r`.`resource_id` = `urp`.`resource_id` AND `u`.`user_id` = `urp`.`user_id` AND `u`.`status_id` = @user_statusid';

    public const GET_RESOURCE_USER_GROUP_PERMISSION = 'SELECT `u`.*, `urp`.`permission_type`
			FROM
				`user_resource_permissions` `urp`, `resources` `r`, `users` `u`
			WHERE
				`r`.`resource_id` = @resourceid AND `r`.`resource_id` = `urp`.`resource_id` AND `u`.`user_id` = `urp`.`user_id` AND `u`.`status_id` = @user_statusid
		UNION
			SELECT `u`.*, `grp`.`permission_type`
			FROM `users` `u`
			INNER JOIN `user_groups` `ug` on `u`.`user_id` = `ug`.`user_id`
			INNER JOIN `group_resource_permissions` `grp` on `ug`.`group_id` = `grp`.`group_id`
			WHERE `ug`.`group_id` IN (
				SELECT
					`g`.`group_id`
				FROM
					`group_resource_permissions` `grp`, `resources` `r`, `groups` `g`
				WHERE
					`r`.`resource_id` = @resourceid AND `r`.`resource_id` = `grp`.`resource_id` AND `g`.`group_id` = `grp`.`group_id`)
			AND `u`.`status_id` = @user_statusid';

    public const MIGRATE_PASSWORD =
        'UPDATE
			`users`
		SET
			`password` = @password, `legacypassword` = null, `salt` = @salt
		WHERE
			`user_id` = @userid';

    public const REGISTER_USER =
        'INSERT INTO
			`users` (`email`, `password`, `fname`, `lname`, `phone`, `organization`, `position`, `username`, `salt`, `timezone`, `language`, `homepageid`, `status_id`, `date_created`, `public_id`, `default_schedule_id`, `terms_date_accepted`)
		VALUES
			(@email, @password, @fname, @lname, @phone, @organization, @position, @username, @salt, @timezone, @language, @homepageid, @user_statusid, @dateCreated, @publicid, @scheduleid, @terms_date_accepted)';

    public const REMOVE_ATTRIBUTE_ENTITY =
        'DELETE FROM `custom_attribute_entities` WHERE `custom_attribute_id` = @custom_attribute_id AND `entity_id` = @entity_id';

    public const REMOVE_ATTRIBUTE_VALUE =
        'DELETE FROM `custom_attribute_values` WHERE `custom_attribute_id` = @custom_attribute_id AND `entity_id` = @entity_id';

    public const DELETE_REMINDER = 'DELETE FROM `reminders` WHERE `reminder_id` = @reminder_id';

    public const DELETE_REMINDER_BY_USER = 'DELETE FROM `reminders` WHERE `user_id` = @user_id';

    public const DELETE_REMINDER_BY_REFNUMBER = 'DELETE FROM `reminders` WHERE `refnumber` = @refnumber';

    public const REMOVE_LEGACY_PASSWORD = 'UPDATE `users` SET `legacypassword` = null WHERE `user_id` = @user_id';

    public const REMOVE_RESERVATION_ACCESSORY =
        'DELETE FROM `reservation_accessories` WHERE `accessory_id` = @accessoryid AND `series_id` = @seriesid';

    public const REMOVE_RESERVATION_ATTACHMENT =
        'DELETE FROM `reservation_files` WHERE `file_id` = @file_id';

    public const REMOVE_RESERVATION_INSTANCE =
        'DELETE FROM `reservation_instances` WHERE `reference_number` = @referenceNumber';

    public const REMOVE_RESERVATION_GUEST =
        'DELETE FROM `reservation_guests` WHERE `reservation_instance_id` = @reservationid AND `email` = @email';

    public const REMOVE_RESERVATION_REMINDER =
        'DELETE FROM `reservation_reminders` WHERE `series_id` = @seriesid AND `reminder_type` = @reminder_type';

    public const REMOVE_RESERVATION_RESOURCE =
        'DELETE FROM `reservation_resources` WHERE `series_id` = @seriesid AND `resource_id` = @resourceid';

    public const REMOVE_RESERVATION_USER =
        'DELETE FROM `reservation_users` WHERE `reservation_instance_id` = @reservationid AND `user_id` = @userid';

    public const REMOVE_RESERVATION_USERS =
        'DELETE FROM `reservation_users` WHERE `reservation_instance_id` = @reservationid AND `reservation_user_level` = @levelid';

    public const REMOVE_RESOURCE_FROM_GROUP = 'DELETE FROM `resource_group_assignment` WHERE `resource_group_id` = @resourcegroupid AND `resource_id` = @resourceid';

    public const ADD_RESOURCE =
        'INSERT INTO
			`resources` (`name`, `location`, `contact_info`, `description`, `notes`, `status_id`, `min_duration`, `min_increment`,
					   `max_duration`, `unit_cost`, `autoassign`, `requires_approval`, `allow_multiday_reservations`,
					   `max_participants`, `min_notice_time_add`, `max_notice_time`, `schedule_id`, `admin_group_id`, `date_created`)
		VALUES
			(@resource_name, @location, @contact_info, @description, @resource_notes, @status_id, @min_duration, @min_increment,
			 @max_duration, @unit_cost, @autoassign, @requires_approval, @allow_multiday_reservations,
		     @max_participants, @min_notice_time_add, @max_notice_time, @scheduleid, @admin_group_id, @dateCreated)';

    public const ADD_RESOURCE_GROUP = 'INSERT INTO `resource_groups` (`resource_group_name`, `parent_id`) VALUES (@groupname, @resourcegroupid)';

    public const ADD_RESOURCE_STATUS_REASON = 'INSERT INTO `resource_status_reasons` (`status_id`, `description`) VALUES (@status_id, @description)';

    public const ADD_RESOURCE_TO_GROUP = 'INSERT INTO
			`resource_group_assignment` (`resource_group_id`, `resource_id`)
			VALUES (@resourcegroupid, @resourceid)';

    public const ADD_RESOURCE_TYPE = 'INSERT INTO `resource_types` (`resource_type_name`, `resource_type_description`) VALUES (@resource_type_name, @resource_type_description)';

    public const ADD_RESOURCE_IMAGE = 'INSERT INTO `resource_images` (`resource_id`, `image_name`) VALUES (@resourceid, @imageName)';

    public const ADD_USER_PREFERENCE = 'INSERT INTO `user_preferences` (`user_id`, `name`, `value`) VALUES (@userid, @name, @value)';

    public const DELETE_ALL_USER_PREFERENCES = 'DELETE FROM `user_preferences` WHERE `user_id` = @userid';

    public const SET_DEFAULT_SCHEDULE =
        'UPDATE `schedules`
		SET `isdefault` = 0
		WHERE `schedule_id` <> @scheduleid';

    public const UPDATE_ACCESSORY =
        'UPDATE `accessories`
		SET `accessory_name` = @accessoryname, `accessory_quantity` = @quantity
		WHERE `accessory_id` = @accessoryid';

    public const UPDATE_ANNOUNCEMENT =
        'UPDATE `announcements`
		SET `announcement_text` = @text, `priority` = @priority, `start_date` = @startDate, `end_date` = @endDate
		WHERE `announcementid` = @announcementid';

    public const UPDATE_ATTRIBUTE =
        'UPDATE `custom_attributes`
				SET `display_label` = @display_label, `display_type` = @display_type, `attribute_category` = @attribute_category,
				`validation_regex` = @validation_regex, `is_required` = @is_required, `possible_values` = @possible_values, `sort_order` = @sort_order, `admin_only` = @admin_only,
				`secondary_category` = @secondary_category, `secondary_entity_ids` = @secondary_entity_ids, `is_private` = @is_private
			WHERE `custom_attribute_id` = @custom_attribute_id';

    public const UPDATE_BLACKOUT_INSTANCE = 'UPDATE `blackout_instances`
			SET `blackout_series_id` = @blackout_series_id, `start_date` = @startDate, `end_date` = @endDate
			WHERE `blackout_instance_id` = @blackout_instance_id';

    public const UPDATE_GROUP =
        'UPDATE `groups`
		SET `name` = @groupname, `admin_group_id` = @admin_group_id, `isdefault` = @isdefault
		WHERE `group_id` = @groupid';

    public const UPDATE_LOGINDATA = 'UPDATE `users` SET `lastlogin` = @lastlogin, `language` = @language WHERE `user_id` = @userid';

    public const UPDATE_FUTURE_RESERVATION_INSTANCES =
        'UPDATE `reservation_instances`
		SET `series_id` = @seriesid
		WHERE
			`series_id` = @currentSeriesId AND
			`start_date` >= (SELECT `start_date` FROM `reservation_instances` WHERE `reference_number` = @referenceNumber)';

    public const UPDATE_RESERVATION_INSTANCE =
        'UPDATE `reservation_instances`
		SET
			`series_id` = @seriesid,
			`start_date` = @startDate,
			`end_date` = @endDate,
			`checkin_date` = @checkin_date,
			`checkout_date` = @checkout_date,
			`previous_end_date` = @previous_end_date,
			`credit_count` = @credit_count
		WHERE
			`reference_number` = @referenceNumber';

    public const UPDATE_RESERVATION_SERIES =
        'UPDATE
			`reservation_series`
		SET
			`last_modified` = @dateModified,
			`title` = @title,
			`description` = @description,
			`repeat_type` = @repeatType,
			`repeat_options` = @repeatOptions,
			`status_id` = @statusid,
			`owner_id` = @userid,
			`allow_participation` = @allow_participation,
			`last_action_by` = @last_action_by
		WHERE
			`series_id` = @seriesid';

    public const UPDATE_RESOURCE =
        'UPDATE `resources`
		SET
			`name` = @resource_name,
			`location` = @location,
			`contact_info` = @contact_info,
			`description` = @description,
			`notes` = @resource_notes,
			`min_duration` = @min_duration,
			`max_duration` = @max_duration,
			`autoassign` = @autoassign,
			`requires_approval` = @requires_approval,
			`allow_multiday_reservations` = @allow_multiday_reservations,
			`max_participants` = @max_participants,
			`min_notice_time_add` = @min_notice_time_add,
			`min_notice_time_update` = @min_notice_time_update,
			`min_notice_time_delete` = @min_notice_time_delete,
			`max_notice_time` = @max_notice_time,
			`image_name` = @imageName,
			`schedule_id` = @scheduleid,
			`admin_group_id` = @admin_group_id,
			`allow_calendar_subscription` = @allow_calendar_subscription,
			`public_id` = @publicid,
			`sort_order` = @sort_order,
			`resource_type_id` = @resource_type_id,
			`status_id` = @status_id,
			`resource_status_reason_id` = @resource_status_reason_id,
			`buffer_time` = @buffer_time,
			`color` = @color,
			`enable_check_in` = @enable_check_in,
			`auto_release_minutes` = @auto_release_minutes,
			`allow_display` = @allow_display,
			`credit_count` = @credit_count,
			`peak_credit_count` = @peak_credit_count,
			`last_modified` = @dateModified,
			`additional_properties` = @additional_properties
		WHERE
			`resource_id` = @resourceid';

    public const UPDATE_RESOURCE_GROUP = 'UPDATE `resource_groups` SET `resource_group_name` = @resourcegroupname, `parent_id` = @parentid WHERE `resource_group_id` = @resourcegroupid';

    public const UPDATE_RESOURCE_STATUS_REASON = 'UPDATE `resource_status_reasons` SET `description` = @description WHERE `resource_status_reason_id` = @resource_status_reason_id';

    public const UPDATE_RESOURCE_TYPE = 'UPDATE `resource_types` SET `resource_type_name` = @resource_type_name, `resource_type_description` = @resource_type_description WHERE `resource_type_id` = @resource_type_id';

    public const UPDATE_SCHEDULE =
        'UPDATE `schedules`
		SET
			`name` = @scheduleName,
			`isdefault` = @scheduleIsDefault,
			`weekdaystart` = @scheduleWeekdayStart,
			`daysvisible` = @scheduleDaysVisible,
			`allow_calendar_subscription` = @allow_calendar_subscription,
			`public_id` = @publicid,
			`admin_group_id` = @admin_group_id,
			`start_date` = @start_date,
			`end_date` = @end_date,
			`default_layout` = @default_layout,
			`total_concurrent_reservations` = @total_concurrent_reservations,
			`max_resources_per_reservation` = @max_resources_per_reservation
		WHERE
			`schedule_id` = @scheduleid';

    public const UPDATE_SCHEDULE_LAYOUT =
        'UPDATE `schedules`
		SET
			`layout_id` = @layoutid
		WHERE
			`schedule_id` = @scheduleid';

    public const UPDATE_USER =
        'UPDATE `users`
		SET
			`status_id` = @user_statusid,
			`password` = @password,
			`salt` = @salt,
			`fname` = @fname,
			`lname` = @lname,
			`email` = @email,
			`username` = @username,
			`homepageId` = @homepageid,
			`last_modified` = @dateModified,
			`timezone` = @timezone,
			`allow_calendar_subscription` = @allow_calendar_subscription,
			`public_id` = @publicid,
			`language` = @language,
			`lastlogin` = @lastlogin,
			`default_schedule_id` = @scheduleid,
			`credit_count` = @credit_count
		WHERE
			`user_id` = @userid';

    public const UPDATE_USER_ATTRIBUTES =
        'UPDATE	`users`
		SET
			`phone` = @phone,
			`position` = @position,
			`organization` = @organization
		WHERE
			`user_id` = @userid';

    public const UPDATE_USER_BY_USERNAME =
        'UPDATE `users`
		SET
			`email` = COALESCE(@email, `email`),
			`password` = COALESCE(@password, `password`),
			`salt` = COALESCE(@salt, `salt`),
			`fname` = COALESCE(@fname, `fname`),
			`lname` = COALESCE(@lname, `lname`),
			`phone` = COALESCE(@phone, `phone`),
			`organization` = COALESCE(@organization, `organization`),
			`position` = COALESCE(@position, `position`)
		WHERE
			`username` = @username OR `email` = @email';

    public const UPDATE_USER_PREFERENCE = 'UPDATE `user_preferences` SET `value` = @value WHERE `user_id` = @userid AND `name` = @name';

    public const UPDATE_USER_SESSION =
        'UPDATE `user_session`
		SET
			`last_modified` = @dateModified,
			`user_session_value` = @user_session_value
		WHERE `user_id` = @userid AND `session_token` = @session_token';

    public const VALIDATE_USER =
        'SELECT `user_id`, `password`, `salt`, `legacypassword`
		FROM `users`
		WHERE (`username` = @username OR `email` = @username) AND `status_id` = 1';
}

class QueryBuilder
{
    public static $DATE_FRAGMENT = '((`ri`.`start_date` >= @startDate AND `ri`.`start_date` <= @endDate) OR
					(`ri`.`end_date` >= @startDate AND `ri`.`end_date` <= @endDate) OR
					(`ri`.`start_date` <= @startDate AND `ri`.`end_date` >= @endDate))';

    public static $SELECT_LIST_FRAGMENT = '`ri`.*, `rs`.`date_created` as `date_created`, `rs`.`last_modified` as `last_modified`, `rs`.`description` as `description`, `rs`.`status_id` as `status_id`, `rs`.`title`, `rs`.`repeat_type`, `rs`.`repeat_options`,
					`owner`.`fname` as `owner_fname`, `owner`.`lname` as `owner_lname`, `owner`.`user_id` as `owner_id`, `owner`.`phone` as `owner_phone`, `owner`.`position` as `owner_position`, `owner`.`organization` as `owner_organization`, `owner`.`email` as `email`, `owner`.`language`, `owner`.`timezone`,
					`resources`.`name`, `resources`.`resource_id`, `resources`.`schedule_id`, `resources`.`status_id` as `resource_status_id`, `resources`.`resource_status_reason_id`, `resources`.`buffer_time`, `resources`.`color`, `resources`.`enable_check_in`, `resources`.`auto_release_minutes`, `resources`.`admin_group_id` as `resource_admin_group_id`,
					`ru`.`reservation_user_level`, `schedules`.`admin_group_id` as `schedule_admin_group_id`,
					`start_reminder`.`minutes_prior` AS `start_reminder_minutes`, `end_reminder`.`minutes_prior` AS `end_reminder_minutes`,
					(SELECT GROUP_CONCAT(`groups`.`group_id`)
						FROM `user_groups` `groups` WHERE `owner`.`user_id` = `groups`.`user_id`) as `owner_group_list`,

					(SELECT GROUP_CONCAT(CONCAT(`participants`.`user_id`, \'=\', CONCAT(`participant_users`.`fname`, " ", `participant_users`.`lname`)) SEPARATOR "!sep!")
						FROM `reservation_users` `participants` INNER JOIN `users` `participant_users` ON `participants`.`user_id` = `participant_users`.`user_id` WHERE `participants`.`reservation_instance_id` = `ri`.`reservation_instance_id` AND `participants`.`reservation_user_level` = 2) as `participant_list`,

					(SELECT GROUP_CONCAT(CONCAT(`invitees`.`user_id`, \'=\', CONCAT(`invitee_users`.`fname`, " ", `invitee_users`.`lname`)) SEPARATOR "!sep!")
						FROM `reservation_users` `invitees` INNER JOIN `users` `invitee_users` ON `invitees`.`user_id` = `invitee_users`.`user_id` WHERE `invitees`.`reservation_instance_id` = `ri`.`reservation_instance_id` AND `invitees`.`reservation_user_level` = 3) as `invitee_list`,

					(SELECT GROUP_CONCAT(CONCAT(`cav`.`custom_attribute_id`,\'=\', `cav`.`attribute_value`) SEPARATOR "!sep!")
						FROM `custom_attribute_values` `cav` WHERE `cav`.`entity_id` = `ri`.`series_id` AND `cav`.`attribute_category` = 1) as `attribute_list`,

					(SELECT GROUP_CONCAT(CONCAT(`p`.`name`, "=", `p`.`value`) SEPARATOR "!sep!")
						FROM `user_preferences` `p` WHERE `owner`.`user_id` = `p`.`user_id`) as `preferences`,

					(SELECT GROUP_CONCAT(CONCAT(`guests`.`email`, "=", `guests`.`reservation_user_level`) SEPARATOR "!sep!")
						FROM `reservation_guests` `guests` WHERE `guests`.`reservation_instance_id` = `ri`.`reservation_instance_id`) as `guest_list`';

    private static function Build($selectValue, $joinValue, $andValue)
    {
        return str_replace(
            '[AND_TOKEN]',
            $andValue ?? '',
            str_replace(
                '[JOIN_TOKEN]',
                $joinValue ?? '',
                str_replace(
                    '[SELECT_TOKEN]',
                    $selectValue ?? '',
                    Queries::GET_RESERVATION_LIST_TEMPLATE
                )
            )
        );
    }

    public static function GET_RESERVATION_LIST()
    {
        return self::Build(self::$SELECT_LIST_FRAGMENT, null, 'AND ' . self::$DATE_FRAGMENT . ' AND
					(@all_owners = 1 OR `ru`.`user_id` IN (@userid) ) AND
					(@levelid = 0 OR `ru`.`reservation_user_level` = @levelid) AND
					(@all_schedules = 1 OR `resources`.`schedule_id` IN (@scheduleid)) AND
					(@all_resources = 1 OR `rr`.`resource_id` IN (@resourceid)) AND
					(@all_participants = 1 OR `ri`.`reservation_instance_id` IN (SELECT `reservation_instance_id` FROM `reservation_users` WHERE `user_id` IN (@participant_id) AND `reservation_user_level` IN (2, 3)))');
    }

	public static function GET_RESERVATION_PENDING_APPROVAL_LIST()
    {
        return self::Build(self::$SELECT_LIST_FRAGMENT, null,' AND
					(@all_owners = 1 OR `ru`.`user_id` IN (@userid) ) AND
					(@levelid = 0 OR `ru`.`reservation_user_level` = @levelid) AND
					(@all_schedules = 1 OR `resources`.`schedule_id` IN (@scheduleid)) AND
					(@all_resources = 1 OR `rr`.`resource_id` IN (@resourceid)) AND
					(@all_participants = 1 OR `ri`.`reservation_instance_id` IN (SELECT `reservation_instance_id` FROM `reservation_users` WHERE `user_id` IN (@participant_id) AND `reservation_user_level` IN (2, 3))) AND
					`rs`.`status_id` = 3 AND `ri`.`start_date` >= @startDate');
    }

	public static function GET_RESERVATION_MISSING_CHECK_IN_OUT_LIST(){
		return self::Build(self::$SELECT_LIST_FRAGMENT, null,' AND 
					(@all_owners = 1 OR `ru`.`user_id` IN (@userid) ) AND
					(@levelid = 0 OR `ru`.`reservation_user_level` = @levelid) AND
					(@all_schedules = 1 OR `resources`.`schedule_id` IN (@scheduleid)) AND
					(@all_resources = 1 OR `rr`.`resource_id` IN (@resourceid)) AND
					(@all_participants = 1 OR `ri`.`reservation_instance_id` IN (SELECT `reservation_instance_id` FROM `reservation_users` WHERE `user_id` IN (@participant_id) AND `reservation_user_level` IN (2, 3))) AND
					(@startDate IS NULL OR `ri`.`start_date` >= @startDate) AND (`ri`.`end_date` <= @endDate) AND
					(`resources`.`enable_check_in` = 1) AND 
					(`ri`.`checkout_date` is NULL AND `ri`.`end_date` <= @current_date AND `ri`.`checkin_date` is NOT NULL)');
	}

    public static function GET_RESERVATION_LIST_FULL()
    {
        return self::Build(self::$SELECT_LIST_FRAGMENT, null, 'AND `ru`.`reservation_user_level` = @levelid');
    }

    public static function GET_RESERVATIONS_BY_ACCESSORY_NAME()
    {
        return self::Build(
            self::$SELECT_LIST_FRAGMENT,
            'INNER JOIN `reservation_accessories` `ar` ON `rs`.`series_id` = `ar`.`series_id` INNER JOIN `accessories` `a` ON `ar`.`accessory_id` = `a`.`accessory_id`',
            'AND ' . self::$DATE_FRAGMENT . ' AND `a`.`accessory_name` LIKE @accessoryname'
        );
    }
}
