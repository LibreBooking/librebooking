<?php

class ColumnNames
{
    private function __construct()
    {
    }

    // USERS //
    public const USER_ID = 'user_id';
    public const USERNAME = 'username';
    public const EMAIL = 'email';
    public const FIRST_NAME = 'fname';
    public const LAST_NAME = 'lname';
    public const PASSWORD = 'password';
    public const OLD_PASSWORD = 'legacypassword';
    public const USER_CREATED = 'date_created';
    public const USER_MODIFIED = 'last_modified';
    public const USER_STATUS_ID = 'status_id';
    public const HOMEPAGE_ID = 'homepageid';
    public const LAST_LOGIN = 'lastlogin';
    public const TIMEZONE_NAME = 'timezone';
    public const LANGUAGE_CODE = 'language';
    public const SALT = 'salt';
    public const PHONE_NUMBER = 'phone';
    public const ORGANIZATION = 'organization';
    public const POSITION = 'position';
    public const DEFAULT_SCHEDULE_ID = 'default_schedule_id';
    public const USER_PREFERENCES = 'preferences';
    public const USER_STATUS = 'status_id';

    // USER_ADDRESSES //
    public const ADDRESS_ID = 'address_id';

    // USER_PREFERENCES //
    public const PREFERENCE_NAME = 'name';
    public const PREFERENCE_VALUE = 'value';

    // ROLES //
    public const ROLE_LEVEL = 'role_level';
    public const ROLE_ID = 'role_id';
    public const ROLE_NAME = 'name';

    // ANNOUNCEMENTS //
    public const ANNOUNCEMENT_ID = 'announcementid';
    public const ANNOUNCEMENT_PRIORITY = 'priority';
    public const ANNOUNCEMENT_START = 'start_date';
    public const ANNOUNCEMENT_END = 'end_date';
    public const ANNOUNCEMENT_TEXT = 'announcement_text';
    public const ANNOUNCEMENT_DISPLAY_PAGE = 'display_page';

    // GROUPS //
    public const GROUP_ID = 'group_id';
    public const GROUP_NAME = 'name';
    public const GROUP_ADMIN_GROUP_ID = 'admin_group_id';
    public const GROUP_ADMIN_GROUP_NAME = 'admin_group_name';
    public const GROUP_ISDEFAULT = 'isdefault';

    // RESOURCE_GROUPS //
    public const RESOURCE_GROUP_ID = 'resource_group_id';
    public const RESOURCE_GROUP_NAME = 'resource_group_name';
    public const RESOURCE_GROUP_PARENT_ID = 'parent_id';

    // TIME BLOCKS //
    public const BLOCK_DAY_OF_WEEK = 'day_of_week';
    public const BLOCK_LABEL = 'label';
    public const BLOCK_LABEL_END = 'end_label';
    public const BLOCK_CODE = 'availability_code';
    public const BLOCK_TIMEZONE = 'timezone';
    public const LAYOUT_TYPE = 'layout_type';

    // TIME BLOCK USES //
    public const BLOCK_START = 'start_time';
    public const BLOCK_END = 'end_time';

    public const CUSTOM_ATTRIBUTE_ID = 'custom_attribute_id';
    public const CUSTOM_ATTRIBUTE_VALUE = 'attribute_value';

    // RESERVATION SERIES //
    public const RESERVATION_USER = 'user_id';
    public const RESERVATION_GROUP = 'group_id';
    public const RESERVATION_CREATED = 'date_created';
    public const RESERVATION_MODIFIED = 'last_modified';
    public const LAST_ACTION_BY = 'last_action_by';
    public const LAST_ACTION_BY_USERNAME = 'last_action_by_username';
    public const RESERVATION_TYPE = 'type_id';
    public const RESERVATION_TITLE = 'title';
    public const RESERVATION_DESCRIPTION = 'description';
    public const RESERVATION_COST = 'total_cost';
    public const RESERVATION_PARENT_ID = 'parent_id';
    public const REPEAT_TYPE = 'repeat_type';
    public const REPEAT_OPTIONS = 'repeat_options';
    public const RESERVATION_STATUS = 'status_id';
    public const SERIES_ID = 'series_id';
    public const RESERVATION_OWNER = 'owner_id';
    public const RESERVATION_ALLOW_PARTICIPATION = 'allow_participation';
    public const RESERVATION_TERMS_ACCEPTANCE_DATE = 'terms_date_accepted';
    public const RESERVATION_SERIES_ID = 'series_id';

    // RESERVATION_INSTANCE //
    public const RESERVATION_INSTANCE_ID = 'reservation_instance_id';
    public const RESERVATION_START = 'start_date';
    public const RESERVATION_END = 'end_date';
    public const REFERENCE_NUMBER = 'reference_number';
    public const CHECKIN_DATE = 'checkin_date';
    public const CHECKOUT_DATE = 'checkout_date';
    public const PREVIOUS_END_DATE = 'previous_end_date';

    // RESERVATION_USER //
    public const RESERVATION_USER_LEVEL = 'reservation_user_level';

    // RESOURCE //
    public const RESOURCE_ID = 'resource_id';
    public const RESOURCE_NAME = 'name';
    public const RESOURCE_LOCATION = 'location';
    public const RESOURCE_CONTACT = 'contact_info';
    public const RESOURCE_DESCRIPTION = 'description';
    public const RESOURCE_NOTES = 'notes';
    public const RESOURCE_MINDURATION = 'min_duration';
    public const RESOURCE_MININCREMENT = 'min_increment';
    public const RESOURCE_MAXDURATION = 'max_duration';
    public const RESOURCE_COST = 'unit_cost';
    public const RESOURCE_AUTOASSIGN = 'autoassign';
    public const RESOURCE_REQUIRES_APPROVAL = 'requires_approval';
    public const RESOURCE_ALLOW_MULTIDAY = 'allow_multiday_reservations';
    public const RESOURCE_MAX_PARTICIPANTS = 'max_participants';
    public const RESOURCE_MINNOTICE_ADD = 'min_notice_time_add';
    public const RESOURCE_MINNOTICE_UPDATE = 'min_notice_time_update';
    public const RESOURCE_MINNOTICE_DELETE = 'min_notice_time_delete';
    public const RESOURCE_MAXNOTICE = 'max_notice_time';
    public const RESOURCE_IMAGE_NAME = 'image_name';
    public const RESOURCE_STATUS_ID = 'status_id';
    public const RESOURCE_STATUS_ID_ALIAS = 'resource_status_id';
    public const RESOURCE_STATUS_REASON_ID = 'resource_status_reason_id';
    public const RESOURCE_STATUS_DESCRIPTION = 'description';
    public const RESOURCE_ADMIN_GROUP_ID = 'admin_group_id';
    public const RESOURCE_SORT_ORDER = 'sort_order';
    public const RESOURCE_BUFFER_TIME = 'buffer_time';
    public const ENABLE_CHECK_IN = 'enable_check_in';
    public const AUTO_RELEASE_MINUTES = 'auto_release_minutes';
    public const RESOURCE_ALLOW_DISPLAY = 'allow_display';
    public const RESOURCE_IMAGE_LIST = 'image_list';
    public const PERMISSION_TYPE = 'permission_type';
    public const RESOURCE_ADDITIONAL_PROPERTIES = 'additional_properties';

    // RESERVATION RESOURCES
    public const RESOURCE_LEVEL_ID = 'resource_level_id';

    // SCHEDULE //
    public const SCHEDULE_ID = 'schedule_id';
    public const SCHEDULE_NAME = 'name';
    public const SCHEDULE_DEFAULT = 'isdefault';
    public const SCHEDULE_WEEKDAY_START = 'weekdaystart';
    public const SCHEDULE_DAYS_VISIBLE = 'daysvisible';
    public const LAYOUT_ID = 'layout_id';
    public const SCHEDULE_ADMIN_GROUP_ID = 'admin_group_id';
    public const SCHEDULE_ADMIN_GROUP_ID_ALIAS = 's_admin_group_id';
    public const SCHEDULE_AVAILABLE_START_DATE = 'start_date';
    public const SCHEDULE_AVAILABLE_END_DATE = 'end_date';
    public const SCHEDULE_ALLOW_CONCURRENT_RESERVATIONS = 'allow_concurrent_bookings';
    public const SCHEDULE_DEFAULT_STYLE = 'default_layout';
    public const TOTAL_CONCURRENT_RESERVATIONS = 'total_concurrent_reservations';
    public const MAX_RESOURCES_PER_RESERVATION = 'max_resources_per_reservation';

    // EMAIL PREFERENCES //
    public const EVENT_CATEGORY = 'event_category';
    public const EVENT_TYPE = 'event_type';

    public const REPEAT_START = 'repeat_start';
    public const REPEAT_END = 'repeat_end';

    // QUOTAS //
    public const QUOTA_ID = 'quota_id';
    public const QUOTA_LIMIT = 'quota_limit';
    public const QUOTA_UNIT = 'unit';
    public const QUOTA_DURATION = 'duration';
    public const ENFORCED_START_TIME = 'enforced_time_start';
    public const ENFORCED_END_TIME = 'enforced_time_end';
    public const ENFORCED_DAYS = 'enforced_days';
    public const QUOTA_SCOPE = 'scope';

    // ACCESSORIES //
    public const ACCESSORY_ID = 'accessory_id';
    public const ACCESSORY_NAME = 'accessory_name';
    public const ACCESSORY_QUANTITY = 'accessory_quantity';
    public const ACCESSORY_RESOURCE_COUNT = 'num_resources';
    public const ACCESSORY_MINIMUM_QUANTITY = 'minimum_quantity';
    public const ACCESSORY_MAXIMUM_QUANTITY = 'maximum_quantity';

    // RESERVATION ACCESSORY //
    public const QUANTITY = 'quantity';

    // BLACKOUTS //
    public const BLACKOUT_INSTANCE_ID = 'blackout_instance_id';
    public const BLACKOUT_START = 'start_date';
    public const BLACKOUT_END = 'end_date';
    public const BLACKOUT_TITLE = 'title';
    public const BLACKOUT_DESCRIPTION = 'description';
    public const BLACKOUT_SERIES_ID = 'blackout_series_id';

    // ATTRIBUTES //
    public const ATTRIBUTE_ID = 'custom_attribute_id';
    public const ATTRIBUTE_ADMIN_ONLY = 'admin_only';
    public const ATTRIBUTE_LABEL = 'display_label';
    public const ATTRIBUTE_TYPE = 'display_type';
    public const ATTRIBUTE_CATEGORY = 'attribute_category';
    public const ATTRIBUTE_CONSTRAINT = 'validation_regex';
    public const ATTRIBUTE_REQUIRED = 'is_required';
    public const ATTRIBUTE_POSSIBLE_VALUES = 'possible_values';
    public const ATTRIBUTE_VALUE = 'attribute_value';
    public const ATTRIBUTE_ENTITY_ID = 'entity_id';
    public const ATTRIBUTE_ENTITY_IDS = 'entity_ids';
    public const ATTRIBUTE_ENTITY_DESCRIPTIONS = 'entity_descriptions';
    public const ATTRIBUTE_SORT_ORDER = 'sort_order';
    public const ATTRIBUTE_SECONDARY_CATEGORY = 'secondary_category';
    public const ATTRIBUTE_SECONDARY_ENTITY_IDS = 'secondary_entity_ids';
    public const ATTRIBUTE_SECONDARY_ENTITY_DESCRIPTIONS = 'secondary_entity_descriptions';
    public const ATTRIBUTE_IS_PRIVATE = 'is_private';

    // RESERVATION FILES //
    public const FILE_ID = 'file_id';
    public const FILE_NAME = 'file_name';
    public const FILE_TYPE = 'file_type';
    public const FILE_SIZE = 'file_size';
    public const FILE_EXTENSION = 'file_extension';

    // SAVED REPORTS //
    public const REPORT_ID = 'saved_report_id';
    public const REPORT_NAME = 'report_name';
    public const DATE_CREATED = 'date_created';
    public const REPORT_DETAILS = 'report_details';

    // USER SESSION //
    public const SESSION_TOKEN = 'session_token';
    public const USER_SESSION = 'user_session_value';
    public const SESSION_LAST_MODIFIED = 'last_modified';

    // REMINDERS //
    public const REMINDER_ID = 'reminder_id';
    public const REMINDER_SENDTIME = 'sendtime';
    public const REMINDER_MESSAGE = 'message';
    public const REMINDER_USER_ID = 'user_id';
    public const REMINDER_ADDRESS = 'address';
    public const REMINDER_REFNUMBER = 'refnumber';
    public const REMINDER_MINUTES_PRIOR = 'minutes_prior';
    public const REMINDER_TYPE = 'reminder_type';

    // RESOURCE TYPE //
    public const RESOURCE_TYPE_ID = 'resource_type_id';
    public const RESOURCE_TYPE_NAME = 'resource_type_name';
    public const RESOURCE_TYPE_DESCRIPTION = 'resource_type_description';

    // DBVERSION //
    public const VERSION_NUMBER = 'version_number';
    public const VERSION_DATE = 'version_date';

    // RESERVATION COLOR RULES //
    public const REQUIRED_VALUE = 'required_value';
    public const RESERVATION_COLOR = 'color';
    public const RESERVATION_COLOR_RULE_ID = 'reservation_color_rule_id';
    public const COLOR_ATTRIBUTE_TYPE = 'attribute_type';
    public const COMPARISON_TYPE = 'comparison_type';

    // CURRENT_CREDITS //
    public const CREDIT_COUNT = 'credit_count';
    public const PEAK_CREDIT_COUNT = 'peak_credit_count';
    public const CREDIT_NOTE = 'credit_note';
    public const ORIGINAL_CREDIT_COUNT = 'original_credit_count';

    // PEAK TIMES //
    public const PEAK_TIMES_ID = 'peak_times_id';
    public const PEAK_ALL_DAY = 'all_day';
    public const PEAK_START_TIME = 'start_time';
    public const PEAK_END_TIME = 'end_time';
    public const PEAK_EVERY_DAY = 'every_day';
    public const PEAK_DAYS = 'peak_days';
    public const PEAK_ALL_YEAR = 'all_year';
    public const PEAK_BEGIN_MONTH = 'begin_month';
    public const PEAK_BEGIN_DAY = 'begin_day';
    public const PEAK_END_MONTH = 'end_month';
    public const PEAK_END_DAY = 'end_day';

    // RESERVATION_WAITLIST_REQUEST_ID //
    public const RESERVATION_WAITLIST_REQUEST_ID = 'reservation_waitlist_request_id';

    // PAYMENT CONFIGURATION //
    public const CREDIT_COST = 'credit_cost';
    public const CREDIT_CURRENCY = 'credit_currency';

    // PAYMENT GATEWAYS //
    public const GATEWAY_TYPE = 'gateway_type';
    public const GATEWAY_SETTING_NAME = 'setting_name';
    public const GATEWAY_SETTING_VALUE = 'setting_value';

    // PAYMENT TRANSACTION LOG //
    public const TRANSACTION_LOG_ID = 'payment_transaction_log_id';
    public const TRANSACTION_LOG_STATUS = 'status';
    public const TRANSACTION_LOG_INVOICE = 'invoice_number';
    public const TRANSACTION_LOG_TRANSACTION_ID = 'transaction_id';
    public const TRANSACTION_LOG_TOTAL = 'total_amount';
    public const TRANSACTION_LOG_FEE = 'transaction_fee';
    public const TRANSACTION_LOG_CURRENCY = 'currency';
    public const TRANSACTION_LOG_TRANSACTION_HREF = 'transaction_href';
    public const TRANSACTION_LOG_REFUND_HREF = 'refund_href';
    public const TRANSACTION_LOG_GATEWAY_NAME = 'gateway_name';
    public const TRANSACTION_LOG_GATEWAY_DATE = 'gateway_date_created';
    public const TRANSACTION_LOG_REFUND_AMOUNT = 'refund_amount';

    // TERMS OF SERVICE //
    public const TERMS_ID = 'terms_of_service_id';
    public const TERMS_TEXT = 'terms_text';
    public const TERMS_URL = 'terms_url';
    public const TERMS_FILE = 'terms_file';
    public const TERMS_APPLICABILITY = 'applicability';

    // dynamic
    public const TOTAL = 'total';
    public const TOTAL_TIME = 'totalTime';
    public const OWNER_FIRST_NAME = 'owner_fname';
    public const OWNER_LAST_NAME = 'owner_lname';
    public const OWNER_FULL_NAME_ALIAS = 'owner_name';
    public const OWNER_USER_ID = 'owner_id';
    public const OWNER_PHONE = 'owner_phone';
    public const OWNER_ORGANIZATION = 'owner_organization';
    public const OWNER_POSITION = 'owner_position';
    public const GROUP_NAME_ALIAS = 'group_name';
    public const RESOURCE_NAME_ALIAS = 'resource_name';
    public const RESOURCE_NAMES = 'resource_names';
    public const SCHEDULE_NAME_ALIAS = 'schedule_name';
    public const PARTICIPANT_LIST = 'participant_list';
    public const INVITEE_LIST = 'invitee_list';
    public const ATTRIBUTE_LIST = 'attribute_list';
    public const RESOURCE_ATTRIBUTE_LIST = 'resource_attribute_list';
    public const RESOURCE_TYPE_ATTRIBUTE_LIST = 'resource_type_attribute_list';
    public const USER_ATTRIBUTE_LIST = 'user_attribute_list';
    public const RESOURCE_ACCESSORY_LIST = 'resource_accessory_list';
    public const RESOURCE_GROUP_LIST = 'group_list';
    public const GROUP_LIST = 'owner_group_list';
    public const START_REMINDER_MINUTES_PRIOR = 'start_reminder_minutes';
    public const END_REMINDER_MINUTES_PRIOR = 'end_reminder_minutes';
    public const DURATION_ALIAS = 'duration';
    public const DURATION_HOURS = 'duration_in_hours';
    public const GROUP_IDS = 'group_ids';
    public const RESOURCE_IDS = 'resource_ids';
    public const GUEST_LIST = 'guest_list';
    public const USER_GROUP_LIST = 'user_group_list';
    public const GROUP_ROLE_LIST = 'group_role_list';
    public const UTILIZATION_TYPE = 'utilization_type';
    public const DATE = 'date';
    public const UTILIZATION = 'utilization';

    // shared
    public const ALLOW_CALENDAR_SUBSCRIPTION = 'allow_calendar_subscription';
    public const PUBLIC_ID = 'public_id';
    public const RESOURCE_ADMIN_GROUP_ID_RESERVATIONS = 'resource_admin_group_id';
    public const SCHEDULE_ADMIN_GROUP_ID_RESERVATIONS = 'schedule_admin_group_id';
}
