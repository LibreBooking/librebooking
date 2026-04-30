<?php

class ParameterNames
{
    private function __construct()
    {
    }

    public const ACCESSORY_ID = '@accessoryid';
    public const ACCESSORY_NAME = '@accessoryname';
    public const ACCESSORY_QUANTITY = '@quantity';
    public const ACCESSORY_MIN_QUANTITY = '@minimum_quantity';
    public const ACCESSORY_MAX_QUANTITY = '@maximum_quantity';

    public const ACTIVATION_CODE = '@activation_code';

    public const ALL_RESOURCES = '@all_resources';
    public const ALL_SCHEDULES = '@all_schedules';
    public const All_OWNERS = '@all_owners';
    public const ALL_PARTICIPANTS = '@all_participants';
    public const ALLOW_CALENDAR_SUBSCRIPTION = '@allow_calendar_subscription';
    public const ALLOW_PARTICIPATION = '@allow_participation';

    public const ANNOUNCEMENT_ID = '@announcementid';
    public const ANNOUNCEMENT_TEXT = '@text';
    public const ANNOUNCEMENT_PRIORITY = '@priority';
    public const ANNOUNCEMENT_DISPLAY_PAGE = '@display_page';

    public const ATTRIBUTE_ID = '@custom_attribute_id';
    public const ATTRIBUTE_ADMIN_ONLY = '@admin_only';
    public const ATTRIBUTE_CATEGORY = '@attribute_category';
    public const ATTRIBUTE_LABEL = '@display_label';
    public const ATTRIBUTE_POSSIBLE_VALUES = '@possible_values';
    public const ATTRIBUTE_REGEX = '@validation_regex';
    public const ATTRIBUTE_REQUIRED = '@is_required';
    public const ATTRIBUTE_SORT_ORDER = '@sort_order';
    public const ATTRIBUTE_TYPE = '@display_type';
    public const ATTRIBUTE_VALUE = '@attribute_value';
    public const ATTRIBUTE_ENTITY_ID = '@entity_id';
    public const ATTRIBUTE_ENTITY_IDS = '@entity_ids';
    public const ATTRIBUTE_UNIQUE_PER_ENTITY = '@unique_per_entity';
    public const ATTRIBUTE_SECONDARY_CATEGORY = '@secondary_category';
    public const ATTRIBUTE_SECONDARY_ENTITY_IDS = '@secondary_entity_ids';
    public const ATTRIBUTE_IS_PRIVATE = '@is_private';
    public const AUTO_RELEASE_MINUTES = '@auto_release_minutes';
    public const ADDITIONAL_PROPERTIES = '@additional_properties';

    public const BLACKOUT_SERIES_ID = '@blackout_series_id';
    public const BLACKOUT_INSTANCE_ID = '@blackout_instance_id';

    public const CHECKIN_DATE = '@checkin_date';
    public const CHECKOUT_DATE = '@checkout_date';
    public const COLOR_ATTRIBUTE_TYPE = '@attribute_type';
    public const COLOR = '@color';
    public const COMPARISON_TYPE = '@comparison_type';
    public const COLOR_REQUIRED_VALUE = '@required_value';
    public const COLOR_ATTRIBUTE_ID = '@attribute_id';
    public const CURRENT_DATE = '@current_date';
    public const CURRENT_SERIES_ID = '@currentSeriesId';
    public const COLOR_RULE_ID = '@reservation_color_rule_id';
    public const CREDIT_COUNT = '@credit_count';
    public const CREDIT_COST = '@credit_cost';
    public const CREDIT_CURRENCY = '@credit_currency';
    public const CREDIT_NOTE = '@credit_note';

    public const DATE_CREATED = '@dateCreated';
    public const DATE_MODIFIED = '@dateModified';
    public const DESCRIPTION = '@description';

    public const END_DATE = '@endDate';
    public const END_TIME = '@endTime';
    public const ENABLE_CHECK_IN = '@enable_check_in';
    public const ENFORCED_DAYS = '@enforcedDays';
    public const EMAIL_ADDRESS = '@email';
    public const EVENT_CATEGORY = '@event_category';
    public const EVENT_TYPE = '@event_type';

    public const FILE_ID = '@file_id';
    public const FILE_NAME = '@file_name';
    public const FILE_TYPE = '@file_type';
    public const FILE_SIZE = '@file_size';
    public const FILE_EXTENSION = '@file_extension';
    public const FIRST_NAME = '@fname';

    public const GATEWAY_TYPE = '@gateway_type';
    public const GATEWAY_SETTING_NAME = '@setting_name';
    public const GATEWAY_SETTING_VALUE = '@setting_value';
    public const GROUP_ID = '@groupid';
    public const GROUP_NAME = '@groupname';
    public const GROUP_ADMIN_ID = '@admin_group_id';
    public const GROUP_ISDEFAULT = '@isdefault';

    public const HOMEPAGE_ID = '@homepageid';

    public const LAST_LOGIN = '@lastlogin';
    public const LAST_NAME = '@lname';
    public const LAYOUT_ID = '@layoutid';
    public const LANGUAGE = '@language';
    public const LAYOUT_TYPE = '@layout_type';
    public const LAST_ACTION_BY = '@last_action_by';

    public const NAME = '@name';

    public const ORGANIZATION = '@organization';
    public const ORIGINAL_CREDIT_COUNT = '@original_credit_count';

    public const PASSWORD = '@password';
    public const PARTICIPANT_ID = '@participant_id';
    public const PAYMENT_STATUS = '@status';
    public const PAYMENT_INVOICE_NUMBER = '@invoice_number';
    public const PAYMENT_TRANSACTION_LOG_ID = '@payment_transaction_log_id';
    public const PAYMENT_TRANSACTION_ID = '@transaction_id';
    public const PAYMENT_TOTAL = '@total_amount';
    public const PAYMENT_TRANSACTION_FEE = '@transaction_fee';
    public const PAYMENT_CURRENCY = '@currency';
    public const PAYMENT_TRANSACTION_HREF = '@transaction_href';
    public const PAYMENT_REFUND_HREF = '@refund_href';
    public const PAYMENT_DATE_CREATED = '@date_created';
    public const PAYMENT_GATEWAY_DATE_CREATED = '@gateway_date_created';
    public const PAYMENT_GATEWAY_NAME = '@gateway_name';
    public const PAYMENT_GATEWAY_RESPONSE = '@payment_response';
    public const PEAK_CREDIT_COUNT = '@peak_credit_count';
    public const PEAK_TIMES_ID = '@peak_times_id';
    public const PEAK_TIMES_ALL_DAY = '@all_day';
    public const PEAK_TIMES_START_TIME = '@start_time';
    public const PEAK_TIMES_END_TIME = '@end_time';
    public const PEAK_TIMES_EVERY_DAY = '@every_day';
    public const PEAK_TIMES_DAYS = '@peak_days';
    public const PEAK_TIMES_ALL_YEAR = '@all_year';
    public const PEAK_TIMES_BEGIN_DAY = '@begin_day';
    public const PEAK_TIMES_BEGIN_MONTH = '@begin_month';
    public const PEAK_TIMES_END_DAY = '@end_day';
    public const PEAK_TIMES_END_MONTH = '@end_month';
    public const PERIOD_AVAILABILITY_TYPE = '@periodType';
    public const PERIOD_DAY_OF_WEEK = '@day_of_week';
    public const PERIOD_LABEL = '@label';
    public const PHONE = '@phone';
    public const POSITION = '@position';
    public const PREVIOUS_END_DATE = '@previous_end_date';
    public const PUBLIC_ID = '@publicid';
    public const PERMISSION_TYPE = '@permission_type';

    public const QUOTA_DURATION = '@duration';
    public const QUOTA_ID = '@quotaid';
    public const QUOTA_LIMIT = '@limit';
    public const QUOTA_SCOPE = '@scope';
    public const QUOTA_UNIT = '@unit';

    public const REFUND_STATUS = '@status';
    public const REFUND_TRANSACTION_ID = '@transaction_id';
    public const REFUND_TOTAL_AMOUNT = '@total_refund_amount';
    public const REFUND_PAYMENT_AMOUNT = '@payment_refund_amount';
    public const REFUND_FEE_AMOUNT = '@fee_refund_amount';
    public const REFUND_TRANSACTION_HREF = '@transaction_href';
    public const REFUND_DATE_CREATED = '@date_created';
    public const REFUND_GATEWAY_DATE_CREATED = '@gateway_date_created';
    public const REFUND_GATEWAY_RESPONSE = '@refund_response';

    public const REMINDER_ID = '@reminder_id';
    public const REMINDER_USER_ID = '@user_id';
    public const REMINDER_SENDTIME = '@sendtime';
    public const REMINDER_MESSAGE = '@message';
    public const REMINDER_ADDRESS = '@address';
    public const REMINDER_REFNUMBER = '@refnumber';
    public const REMINDER_MINUTES_PRIOR = '@minutes_prior';
    public const REMINDER_TYPE = '@reminder_type';

    public const REFERENCE_NUMBER = '@referenceNumber';

    public const REPEAT_OPTIONS = '@repeatOptions';
    public const REPEAT_TYPE = '@repeatType';

    public const REPORT_NAME = '@report_name';
    public const REPORT_DETAILS = '@report_details';
    public const REPORT_ID = '@report_id';

    public const RESERVATION_INSTANCE_ID = '@reservationid';
    public const RESERVATION_USER_LEVEL_ID = '@levelid';
    public const RESERVATION_WAITLIST_REQUEST_ID = '@reservation_waitlist_request_id';

    public const RESOURCE_ID = '@resourceid';
    public const RESOURCE_IDS = '@resourceids';
    public const RESOURCE_ALLOW_MULTIDAY = '@allow_multiday_reservations';
    public const RESOURCE_ALLOW_DISPLAY = '@allow_display';
    public const RESOURCE_AUTOASSIGN = '@autoassign';
    public const RESOURCE_CONTACT = '@contact_info';
    public const RESOURCE_COST = '@unit_cost';
    public const RESOURCE_DESCRIPTION = '@description';
    public const RESOURCE_LOCATION = '@location';
    public const RESOURCE_MAX_PARTICIPANTS = '@max_participants';
    public const RESOURCE_MAXDURATION = '@max_duration';
    public const RESOURCE_MAXNOTICE = '@max_notice_time';
    public const RESOURCE_MINDURATION = '@min_duration';
    public const RESOURCE_MININCREMENT = '@min_increment';
    public const RESOURCE_MINNOTICE_ADD = '@min_notice_time_add';
    public const RESOURCE_MINNOTICE_UPDATE = '@min_notice_time_update';
    public const RESOURCE_MINNOTICE_DELETE = '@min_notice_time_delete';
    public const RESOURCE_NAME = '@resource_name';
    public const RESOURCE_NOTES = '@resource_notes';
    public const RESOURCE_REQUIRES_APPROVAL = '@requires_approval';
    public const RESOURCE_LEVEL_ID = '@resourceLevelId';
    public const RESOURCE_IMAGE_NAME = '@imageName';
    public const RESOURCE_SORT_ORDER = '@sort_order';
    public const RESOURCE_STATUS = '@status_id';
    public const RESOURCE_STATUS_REASON_ID = '@resource_status_reason_id';
    public const RESOURCE_STATUS_REASON_DESCRIPTION = '@description';
    public const RESOURCE_BUFFER_TIME = '@buffer_time';

    public const RESOURCE_GROUP_ID = '@resourcegroupid';
    public const RESOURCE_GROUP_NAME = '@resourcegroupname';
    public const RESOURCE_GROUP_PARENT_ID = '@parentid';

    public const RESOURCE_TYPE_ID = '@resource_type_id';
    public const RESOURCE_TYPE_NAME = '@resource_type_name';
    public const RESOURCE_TYPE_DESCRIPTION = '@resource_type_description';

    public const ROLE_ID = '@roleid';
    public const ROLE_LEVEL = '@role_level';

    public const SALT = '@salt';
    public const SCHEDULE_ID = '@scheduleid';
    public const SCHEDULE_NAME = '@scheduleName';
    public const SCHEDULE_ISDEFAULT = '@scheduleIsDefault';
    public const SCHEDULE_WEEKDAYSTART = '@scheduleWeekdayStart';
    public const SCHEDULE_DAYSVISIBLE = '@scheduleDaysVisible';
    public const SCHEDULE_AVAILABILITY_BEGIN = '@start_date';
    public const SCHEDULE_AVAILABILITY_END = '@end_date';
    public const SCHEDULE_ALLOW_CONCURRENT_RESERVATIONS = '@allow_concurrent_bookings';
    public const SCHEDULE_DEFAULT_STYLE = '@default_layout';
    public const SCHEDULE_TOTAL_CONCURRENT_RESERVATIONS = '@total_concurrent_reservations';
    public const SCHEDULE_MAX_RESOURCES_PER_RESERVATION = '@max_resources_per_reservation';
    public const SERIES_ID = '@seriesid';
    public const SESSION_TOKEN = '@session_token';
    public const START_DATE = '@startDate';
    public const START_TIME = '@startTime';
    public const STATUS_ID = '@statusid';

    public const TIMEZONE_NAME = '@timezone';
    public const TYPE_ID = '@typeid';
    public const TERMS_TEXT = '@terms_text';
    public const TERMS_URL = '@terms_url';
    public const TERMS_FILENAME = '@terms_file';
    public const TERMS_APPLICABILITY = '@applicability';
    public const TITLE = '@title';
    public const TERMS_ACCEPTANCE_DATE = '@terms_date_accepted';

    public const USER_ID = '@userid';
    public const USER_ROLE_ID = '@user_roleid';
    public const USER_STATUS_ID = '@user_statusid';
    public const USER_SESSION = '@user_session_value';
    public const USERNAME = '@username';

    public const VALUE = '@value';


    // used?
    public const FIRST_NAME_SETTING = '@fname_setting';
    public const LAST_NAME_SETTING = '@lname_setting';
    public const USERNAME_SETTING = '@username_setting';
    public const EMAIL_ADDRESS_SETTING = '@email_setting';
    public const PASSWORD_SETTING = '@password_setting';
    public const ORGANIZATION_SELECTION_SETTING = '@organization_setting';
    public const GROUP_SETTING = '@group_setting';
    public const POSITION_SETTING = '@position_setting';
    public const ADDRESS_SETTING = '@address_setting';
    public const PHONE_SETTING = '@phone_setting';
    public const HOMEPAGE_SELECTION_SETTING = '@homepage_setting';
    public const TIMEZONE_SELECTION_SETTING = '@timezone_setting';
}
