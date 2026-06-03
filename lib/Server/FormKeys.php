<?php

class FormKeys
{
    private function __construct()
    {
    }

    public const ACCESSORY_LIST = 'accessoryList';
    public const ACCESSORY_NAME = 'accessoryName';
    public const ACCESSORY_ID = 'ACCESSORY_ID';
    public const ACCESSORY_QUANTITY_AVAILABLE = 'accessoryQuantityAvailable';
    public const ACCESSORY_RESOURCE = 'accessoryResource';
    public const ACCESSORY_MIN_QUANTITY = 'ACCESSORY_MIN_QUANTITY';
    public const ACCESSORY_MAX_QUANTITY = 'ACCESSORY_MAX_QUANTITY';
    public const ADDITIONAL_RESOURCES = 'additionalResources';
    public const ADDRESS = 'address';
    public const ALLOW_CALENDAR_SUBSCRIPTIONS = 'ALLOW_CALENDAR_SUBSCRIPTIONS';
    public const ALLOW_MULTIDAY = 'allowMultiday';
    public const ALLOW_PARTICIPATION = 'ALLOW_PARTICIPATION';
    public const ANNOUNCEMENT_TEXT = 'announcementText';
    public const ANNOUNCEMENT_START = 'announcementStart';
    public const ANNOUNCEMENT_END = 'announcementEnd';
    public const ANNOUNCEMENT_PRIORITY = 'announcementPriority';
    public const ATTRIBUTE_ID = 'ATTRIBUTE_ID';
    public const ATTRIBUTE_VALUE = 'ATTRIBUTE_VALUE';
    public const ATTRIBUTE_LABEL = 'ATTRIBUTE_LABEL';
    public const ATTRIBUTE_TYPE = 'ATTRIBUTE_TYPE';
    public const ATTRIBUTE_CATEGORY = 'ATTRIBUTE_CATEGORY';
    public const ATTRIBUTE_VALIDATION_EXPRESSION = 'ATTRIBUTE_VALIDATION_EXPRESSION';
    public const ATTRIBUTE_IS_ADMIN_ONLY = 'ATTRIBUTE_IS_ADMIN_ONLY';
    public const ATTRIBUTE_IS_REQUIRED = 'ATTRIBUTE_IS_REQUIRED';
    public const ATTRIBUTE_IS_UNIQUE = 'ATTRIBUTE_IS_UNIQUE';
    public const ATTRIBUTE_POSSIBLE_VALUES = 'ATTRIBUTE_POSSIBLE_VALUES';
    public const ATTRIBUTE_PREFIX = 'psiattribute';
    public const ATTRIBUTE_SORT_ORDER = 'attributeOrder';
    public const ATTRIBUTE_ENTITY = 'ATTRIBUTE_ENTITY';
    public const ATTRIBUTE_LIMIT_SCOPE = 'ATTRIBUTE_LIMIT_SCOPE';
    public const ATTRIBUTE_IS_PRIVATE = 'ATTRIBUTE_IS_PRIVATE';
    public const ATTRIBUTE_SECONDARY_CATEGORY = 'ATTRIBUTE_SECONDARY_CATEGORY';
    public const ATTRIBUTE_SECONDARY_ENTITY_IDS = 'ATTRIBUTE_SECONDARY_ENTITY_IDS';
    public const AUTO_ASSIGN = 'autoAssign';
    public const AUTO_ASSIGN_CLEAR = 'AUTO_ASSIGN_CLEAR';
    public const AUTO_RELEASE_MINUTES = 'AUTO_RELEASE_MINUTES';
    public const AVAILABILITY_RANGE = 'AVAILABILITY_RANGE';
    public const AVAILABLE_ALL_YEAR = 'AVAILABLE_ALL_YEAR';
    public const AVAILABLE_BEGIN_DATE = 'AVAILABLE_BEGIN_DATE';
    public const AVAILABLE_END_DATE = 'AVAILABLE_END_DATE';
    public const ALLOW_CONCURRENT_RESERVATIONS = 'ALLOW_CONCURRENT_RESERVATIONS';

    public const BEGIN_DATE = 'beginDate';
    public const BEGIN_PERIOD = 'beginPeriod';
    public const BEGIN_TIME = 'beginTime';
    public const BLACKOUT_APPLY_TO_SCHEDULE = 'applyToSchedule';
    public const BLACKOUT_INSTANCE_ID = 'BLACKOUT_INSTANCE_ID';
    public const BUFFER_TIME = 'BUFFER_TIME';
    public const BUFFER_TIME_NONE = 'BUFFER_TIME_NONE';

    public const CAPTCHA = 'captcha';
    public const CONFLICT_ACTION = 'conflictAction';
    public const CONTACT_INFO = 'contactInfo';
    public const CREDITS = 'CREDITS';
    public const CREDIT_COUNT = 'CREDIT_COUNT';
    public const CREDIT_COST = 'CREDIT_COST';
    public const CREDIT_CURRENCY = 'CREDIT_CURRENCY';
    public const CSS_FILE = 'CSS_FILE';
    public const CSRF_TOKEN = 'CSRF_TOKEN';
    public const CREDIT_QUANTITY = 'CREDIT_QUANTITY';
    public const CURRENT_PASSWORD = 'currentPassword';

    public const DAY = 'DAY';
    public const DEFAULT_HOMEPAGE = 'defaultHomepage';
    public const DESCRIPTION = 'reservationDescription';
    public const DURATION = 'duration';
    public const DELETE_REASON = 'DELETE_REASON';
    public const DISPLAY_PAGE = 'DISPLAY_PAGE';

    public const EMAIL = 'email';
    public const END_DATE = 'endDate';
    public const END_PERIOD = 'endPeriod';
    public const END_REMINDER_ENABLED = 'END_REMINDER_ENABLED';
    public const END_REMINDER_TIME = 'END_REMINDER_TIME';
    public const END_REMINDER_INTERVAL = 'END_REMINDER_INTERVAL';
    public const END_REPEAT_DATE = 'endRepeatDate';
    public const END_TIME = 'endTime';
    public const ENFORCE_ALL_DAY = 'ENFORCE_ALL_DAY';
    public const ENFORCE_EVERY_DAY = 'ENFORCE_EVERY_DAY';
    public const ENABLE_CHECK_IN = 'ENABLE_CHECK_IN';
    public const ENABLE_AUTO_RELEASE = 'ENABLE_AUTO_RELEASE';
    public const EMAIL_CONTENTS = 'EMAIL_CONTENTS';
    public const EMAIL_TEMPLATE_NAME = 'EMAIL_TEMPLATE_NAME';
    public const EMAIL_TEMPLATE_LANGUAGE = 'EMAIL_TEMPLATE_LANGUAGE';

    public const FIRST_NAME = 'fname';
    public const FAVICON_FILE = 'FAVICON_FILE';

    public const GROUP = 'group';
    public const GROUP_ID = 'group_id';
    public const GROUP_NAME = 'group_name';
    public const GROUP_ADMIN = 'group_admin';
    public const GROUP_IMPORT_FILE = 'GROUP_IMPORT_FILE';
    public const GUEST_INVITATION_LIST = 'guestInvitationList';
    public const GUEST_PARTICIPATION_LIST = 'guestParticipationList';

    public const HOURS = 'HOURS';

    public const INSTALL_PASSWORD = 'install_password';
    public const INSTALL_DB_USER = 'install_db_user';
    public const INSTALL_DB_PASSWORD = 'install_db_password';
    public const INVITATION_LIST = 'invitationList';
    public const IS_ACTIVE = 'isactive';
    public const ICS_IMPORT_FILE = 'ICS_IMPORT_FILE';
    public const INCLUDE_DELETED = 'INCLUDE_DELETED';
    public const INVITED_EMAILS = 'INVITED_EMAILS';
    public const IS_DEFAULT = 'IS_DEFAULT';

    public const LANGUAGE = 'language';
    public const LAST_NAME = 'lname';
    public const LIMIT = 'limit';
    public const LOCATION = 'location';
    public const LOGIN = 'login';
    public const LOGO_FILE = 'LOGO_FILE';
    public const LAYOUT_TYPE = 'LAYOUT_TYPE';
    public const LAYOUT_PERIOD_ID = 'LAYOUT_PERIOD_ID';

    public const MIN_DURATION = 'minDuration';
    public const MIN_DURATION_NONE = 'minDurationNone';
    public const MIN_INCREMENT = 'minIncrement';
    public const MIN_INCREMENT_NONE = 'minIncrementNone';
    public const MINUTES = 'MINUTES';
    public const MAX_DURATION = 'maxDuration';
    public const MAX_DURATION_NONE = 'maxDurationNone';
    public const MAX_PARTICIPANTS = 'maxParticipants';
    public const MAX_PARTICIPANTS_UNLIMITED = 'maxParticipantsUnlimited';
    public const MIN_NOTICE_ADD = 'minNoticeAdd';
    public const MIN_NOTICE_UPDATE = 'minNoticeUpdate';
    public const MIN_NOTICE_DELETE = 'minNoticeDelete';
    public const MIN_NOTICE_NONE_ADD = 'minNoticeNoneAdd';
    public const MIN_NOTICE_NONE_UPDATE = 'minNoticeNoneUpdate';
    public const MIN_NOTICE_NONE_DELETE = 'minNoticeNoneDelete';
    public const MIN_CAPACITY = 'MIN_CAPACITY';
    public const MAX_NOTICE = 'maxNotice';
    public const MAX_NOTICE_NONE = 'maxNoticeNone';
    public const MAXIMUM_CONCURRENT_UNLIMITED = 'MAXIMUM_CONCURRENT_UNLIMITED';
    public const MAXIMUM_CONCURRENT_RESERVATIONS = 'MAXIMUM_CONCURRENT_RESERVATIONS';
    public const MAXIMUM_RESOURCES_PER_RESERVATION_UNLIMITED = 'MAXIMUM_RESOURCES_PER_RESERVATION_UNLIMITED';
    public const MAXIMUM_RESOURCES_PER_RESERVATION = 'MAXIMUM_RESOURCES_PER_RESERVATION';
    public const MAX_CONCURRENT_RESERVATIONS = 'MAX_CONCURRENT_RESERVATIONS';

    public const NAME = 'name';
    public const NOTES = 'notes';

    public const ORGANIZATION = 'organization';
    public const ORIGINAL_RESOURCE_ID = 'ORIGINAL_RESOURCE_ID';
    public const OWNER_TEXT = 'ot';

    public const PARENT_ID = 'PARENT_ID';
    public const PARTICIPANT_LIST = 'participantList';
    public const PARTICIPANT_ID = 'PARTICIPANT_ID';
    public const PARTICIPANT_TEXT = 'pt';
    public const PASSWORD = 'password';
    public const PASSWORD_CONFIRM = 'passwordConfirm';
    public const PAYPAL_ENABLED = 'ENABLE_PAYPAL';
    public const PAYPAL_CLIENT_ID = 'PAYPAL_CLIENT_ID';
    public const PAYPAL_SECRET = 'PAYPAL_SECRET';
    public const PAYPAL_ENVIRONMENT = 'PAYPAL_ENVIRONMENT';
    public const PAYMENT_RESPONSE_DATA = 'PAYMENT_RESPONSE_DATA';
    public const PEAK_ALL_DAY = 'PEAK_ALL_DAY';
    public const PEAK_ALL_YEAR = 'PEAK_ALL_YEAR';
    public const PEAK_EVERY_DAY = 'PEAK_EVERY_DAY';
    public const PEAK_CREDITS = 'PEAK_CREDITS';
    public const PEAK_BEGIN_MONTH = 'PEAK_BEGIN_MONTH';
    public const PEAK_BEGIN_DAY = 'PEAK_BEGIN_DAY';
    public const PEAK_END_MONTH = 'PEAK_END_MONTH';
    public const PEAK_END_DAY = 'PEAK_END_DAY';
    public const PEAK_BEGIN_TIME = 'PEAK_BEGIN_TIME';
    public const PEAK_END_TIME = 'PEAK_END_TIME';
    public const PEAK_DELETE = 'PEAK_DELETE';
    public const PERSIST_LOGIN = 'persistLogin';
    public const PHONE = 'phone';
    public const POSITION = 'position';
    public const PK = 'pk';
    public const PERMISSION_TYPE = 'PERMISSION_TYPE';

    public const QUOTA_SCOPE = 'QUOTA_SCOPE';

    public const REFERENCE_NUMBER = 'referenceNumber';
    public const REFUND_AMOUNT = 'REFUND_AMOUNT';
    public const REFUND_TRANSACTION_ID = 'REFUND_TRANSACTION_ID';
    public const REMOVED_FILE_IDS = 'removeFile';
    public const REPEAT_OPTIONS = 'repeatOptions';
    public const REPEAT_EVERY = 'repeatEvery';
    public const REPEAT_SUNDAY = 'repeatSunday';
    public const REPEAT_MONDAY = 'repeatMonday';
    public const REPEAT_TUESDAY = 'repeatTuesday';
    public const REPEAT_WEDNESDAY = 'repeatWednesday';
    public const REPEAT_THURSDAY = 'repeatThursday';
    public const REPEAT_FRIDAY = 'repeatFriday';
    public const REPEAT_SATURDAY = 'repeatSaturday';
    public const REPEAT_MONTHLY_TYPE = 'repeatMonthlyType';
    public const REPORT_START = 'reportStart';
    public const REPORT_END = 'reportEnd';
    public const REPORT_GROUPBY = 'REPORT_GROUPBY';
    public const REPORT_RANGE = 'REPORT_RANGE';
    public const REPORT_RESULTS = 'reportResults';
    public const REPORT_USAGE = 'REPORT_USAGE';
    public const REPORT_NAME = 'REPORT_NAME';
    public const REQUIRES_APPROVAL = 'requiresApproval';
    public const RESERVATION_ACTION = 'reservationAction';
    public const RESERVATION_COLOR = 'RESERVATION_COLOR';
    public const RESERVATION_COLOR_RULE_ID = 'RESERVATION_COLOR_RULE_ID';
    public const RESERVATION_FILE = 'reservationFile';
    public const RESERVATION_ID = 'reservationId';
    public const RESERVATION_TITLE = 'reservationTitle';
    public const RESERVATION_RETRY_PREFIX = 'RESERVATION_RETRY_PREFIX';
    public const RESERVATION_IMPORT_FILE = 'RESERVATION_IMPORT_FILE';
    public const RESOURCE = 'resource';
    public const RESOURCE_ADMIN_GROUP_ID = 'resourceAdminGroupId';
    public const RESOURCE_CONTACT = 'resourceContact';
    public const RESOURCE_DESCRIPTION = 'resourceDescription';
    public const RESOURCE_ID = 'resourceId';
    public const RESOURCE_IMAGE = 'resourceImage';
    public const RESOURCE_IMPORT_FILE = 'resourceImportFile';
    public const RESOURCE_LOCATION = 'resourceLocation';
    public const RESOURCE_NAME = 'resourceName';
    public const RESOURCE_NOTES = 'resourceNotes';
    public const RESOURCE_SORT_ORDER = 'RESOURCE_SORT_ORDER';
    public const RESOURCE_TYPE_ID = 'RESOURCE_TYPE_ID';
    public const RESOURCE_TYPE_DESCRIPTION = 'RESOURCE_TYPE_DESCRIPTION';
    public const RESOURCE_TYPE_NAME = 'RESOURCE_TYPE_NAME';
    public const RESUME = 'resume';
    public const RETURN_URL = 'returnUrl';
    public const ROLE_ID = 'roleId';
    public const RESOURCE_STATUS_ID = 'RESOURCE_STATUS_ID';
    public const RESOURCE_STATUS_REASON = 'RESOURCE_STATUS_REASON';
    public const RESOURCE_STATUS_REASON_ID = 'RESOURCE_STATUS_REASON_ID';
    public const RESOURCE_STATUS_UPDATE_SCOPE = 'RESOURCE_STATUS_UPDATE_SCOPE';
    public const ROLLING = 'ROLLING';
    public const REPEAT_CUSTOM_DATES = 'repeatCustomDates';

    public const SCHEDULE_ID = 'scheduleId';
    public const SCHEDULE_NAME = 'scheduleName';
    public const SCHEDULE_WEEKDAY_START = 'scheduleWeekdayStart';
    public const SCHEDULE_DAYS_VISIBLE = 'scheduleDaysVisible';
    public const SCHEDULE_DEFAULT_STYLE = 'SCHEDULE_DEFAULT_STYLE';
    public const SEND_AS_EMAIL = 'SEND_AS_EMAIL';
    public const SERIES_UPDATE_SCOPE = 'seriesUpdateScope';
    public const START_REMINDER_ENABLED = 'START_REMINDER_ENABLED';
    public const START_REMINDER_TIME = 'START_REMINDER_TIME';
    public const START_REMINDER_INTERVAL = 'START_REMINDER_INTERVAL';
    public const SLOTS_BLOCKED = 'blockedSlots';
    public const SLOTS_RESERVABLE = 'reservableSlots';
    public const STATUS_ID = 'STATUS_ID';
    public const STRIPE_ENABLED = 'ENABLE_STRIPE';
    public const STRIPE_PUBLISHABLE_KEY = 'STRIPE_PUBLISHABLE_KEY';
    public const STRIPE_SECRET_KEY = 'STRIPE_SECRET_KEY';
    public const STRIPE_TOKEN = 'STRIPE_TOKEN';
    public const SUBMIT = 'SUBMIT';
    public const SUMMARY = 'summary';
    public const SCHEDULE_ADMIN_GROUP_ID = 'adminGroupId';
    public const SELECTED_COLUMNS = 'SELECTED_COLUMNS';
    public const SLACK_COMMAND = 'command';
    public const SLACK_TEXT = 'text';
    public const SLACK_TOKEN = 'token';
    public const SPECIFIC_TIME = 'SPECIFIC_TIME';
    public const SPECIFIC_DATES = 'SPECIFIC_DATES';

    public const THISWEEK = 'THISWEEK';
    public const TIMEZONE = 'timezone';
    public const TODAY = 'TODAY';
    public const TOMMOROW = 'TOMMOROW';
    public const TOS_METHOD = 'TOS_METHOD';
    public const TOS_APPLICABILITY = 'TOS_APPLICABILITY';
    public const TOS_TEXT = 'TOS_TEXT';
    public const TOS_URL = 'TOS_URL';
    public const TOS_UPLOAD = 'TOS_UPLOAD';
    public const TOS_ACKNOWLEDGEMENT = 'TOS_ACKNOWLEDGEMENT';

    public const UNIT = 'unit';
    public const UNIT_COST = 'unitCost';
    public const USER_ID = 'userId';
    public const USERNAME = 'username';
    public const USER_IMPORT_FILE = 'USER_IMPORT_FILE';
    public const USING_SINGLE_LAYOUT = 'USING_SINGLE_LAYOUT';
    public const UPDATE_ON_IMPORT = 'UPDATE_ON_IMPORT';

    public const VALUE = 'value';

    public static function Evaluate($formKey)
    {
        $key = strtoupper($formKey);
        return eval("return FormKeys::$key;");
    }
}
