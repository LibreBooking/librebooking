<?php

class ConfigKeys
{
    public const ADMIN_EMAIL = 'admin.email';
    public const ADMIN_EMAIL_NAME = 'admin.email.name';
    public const COMPANY_NAME = 'company.name';
    public const COMPANY_URL = 'company.url';
    public const ALLOW_REGISTRATION = 'allow.self.registration';
    public const CREDITS_ENABLED = 'enabled';
    public const CREDITS_ALLOW_PURCHASE = 'allow.purchase';
    public const CSS_EXTENSION_FILE = 'css.extension.file';
    public const CSS_THEME = 'css.theme';
    public const DEFAULT_HOMEPAGE = 'default.homepage';
    public const DEFAULT_PAGE_SIZE = 'default.page.size';
    public const DISABLE_PASSWORD_RESET = 'disable.password.reset';
    public const ENABLE_EMAIL = 'enable.email';
    public const HOME_URL = 'home.url';
    public const INACTIVITY_TIMEOUT = 'inactivity.timeout';
    public const LANGUAGE = 'default.language';
    public const ENFORCE_CUSTOM_MAIL_TEMPLATE = 'enforce.custom.mail.template';
    public const LOGOUT_URL = 'logout.url';
    public const NAME_FORMAT = 'name.format';
    public const SCRIPT_URL = 'script.url';
    public const DEFAULT_TIMEZONE = 'default.timezone';
    public const REGISTRATION_ENABLE_CAPTCHA = 'registration.captcha.enabled';
    public const REGISTRATION_REQUIRE_ACTIVATION = 'registration.require.email.activation';
    public const REGISTRATION_AUTO_SUBSCRIBE_EMAIL = 'registration.auto.subscribe.email';
    public const REGISTRATION_NOTIFY = 'registration.notify.admin';

    public const VERSION = 'version';

    public const SCHEDULE_SHOW_INACCESSIBLE_RESOURCES = 'show.inaccessible.resources';
    public const SCHEDULE_RESERVATION_LABEL = 'reservation.label';
    public const SCHEDULE_HIDE_BLOCKED_PERIODS = 'hide.blocked.periods';
    public const SCHEDULE_UPDATE_HIGHLIGHT_MINUTES = 'update.highlight.minutes';
    public const SCHEDULE_SHOW_WEEK_NUMBERS = 'show.week.numbers';
    public const SCHEDULE_FAST_RESERVATION_LOAD = 'fast.reservation.load';
    public const SCHEDULE_LOAD_MOBILE_VIEWS = 'load.mobile.views';
    public const SCHEDULE_AUTO_SCROLL_TODAY = 'auto.scroll.today';

    public const DATABASE_TYPE = 'type';
    public const DATABASE_USER = 'user';
    public const DATABASE_PASSWORD = 'password';
    public const DATABASE_HOSTSPEC = 'hostspec';
    public const DATABASE_NAME = 'name';

    public const PLUGIN_AUTHENTICATION = 'Authentication';
    public const PLUGIN_AUTHORIZATION = 'Authorization';
    public const PLUGIN_PERMISSION = 'Permission';
    public const PLUGIN_POSTREGISTRATION = 'PostRegistration';
    public const PLUGIN_PRERESERVATION = 'PreReservation';
    public const PLUGIN_POSTRESERVATION = 'PostReservation';
    public const PLUGIN_STYLING = 'Styling';

    public const RESERVATION_START_TIME_CONSTRAINT = 'start.time.constraint';
    public const RESERVATION_UPDATES_REQUIRE_APPROVAL = 'updates.require.approval';
    public const RESERVATION_PREVENT_PARTICIPATION = 'prevent.participation';
    public const RESERVATION_PREVENT_RECURRENCE = 'prevent.recurrence';
    public const RESERVATION_REMINDERS_ENABLED = 'enable.reminders';
    public const RESERVATION_ALLOW_GUESTS = 'allow.guest.participation';
    public const RESERVATION_ALLOW_WAITLIST = 'allow.wait.list';
    public const RESERVATION_CHECKIN_MINUTES = 'checkin.minutes.prior';
    public const RESERVATION_START_REMINDER = 'default.start.reminder';
    public const RESERVATION_END_REMINDER = 'default.end.reminder';
    public const RESERVATION_TITLE_REQUIRED = 'title.required';
    public const RESERVATION_DESCRIPTION_REQUIRED = 'description.required';
    public const RESERVATION_CHECKIN_ADMIN_ONLY = 'checkin.admin.only';
    public const RESERVATION_CHECKOUT_ADMIN_ONLY = 'checkout.admin.only';

    public const IMAGE_UPLOAD_DIRECTORY = 'image.upload.directory';
    public const IMAGE_UPLOAD_URL = 'image.upload.url';

    public const CACHE_TEMPLATES = 'cache.templates';

    public const USE_LOCAL_JS = 'use.local.js.libs';

    public const INSTALLATION_PASSWORD = 'install.password';

    public const ICS_SUBSCRIPTION_KEY = 'subscription.key';
    public const ICS_FUTURE_DAYS = 'future.days';
    public const ICS_PAST_DAYS = 'past.days';

    public const PRIVACY_HIDE_USER_DETAILS = 'hide.user.details';
    public const PRIVACY_HIDE_RESERVATION_DETAILS = 'hide.reservation.details';
    public const PRIVACY_VIEW_RESERVATIONS = 'view.reservations';
    public const PRIVACY_VIEW_SCHEDULES = 'view.schedules';
    public const PRIVACY_ALLOW_GUEST_BOOKING = 'allow.guest.reservations';
    public const PRIVACY_PUBLIC_FUTURE_DAYS = 'public.future.days';

    public const NOTIFY_CREATE_RESOURCE_ADMINS = 'resource.admin.add';
    public const NOTIFY_CREATE_APPLICATION_ADMINS = 'application.admin.add';
    public const NOTIFY_CREATE_GROUP_ADMINS = 'group.admin.add';

    public const NOTIFY_UPDATE_RESOURCE_ADMINS = 'resource.admin.update';
    public const NOTIFY_UPDATE_APPLICATION_ADMINS = 'application.admin.update';
    public const NOTIFY_UPDATE_GROUP_ADMINS = 'group.admin.update';

    public const NOTIFY_DELETE_RESOURCE_ADMINS = 'resource.admin.delete';
    public const NOTIFY_DELETE_APPLICATION_ADMINS = 'application.admin.delete';
    public const NOTIFY_DELETE_GROUP_ADMINS = 'group.admin.delete';

    public const NOTIFY_APPROVAL_RESOURCE_ADMINS = 'resource.admin.approval';
    public const NOTIFY_APPROVAL_APPLICATION_ADMINS = 'application.admin.approval';
    public const NOTIFY_APPROVAL_GROUP_ADMINS = 'group.admin.approval';

    public const UPLOAD_ENABLE_RESERVATION_ATTACHMENTS = 'enable.reservation.attachments';
    public const UPLOAD_RESERVATION_ATTACHMENTS = 'reservation.attachment.path';
    public const UPLOAD_RESERVATION_EXTENSIONS = 'reservation.attachment.extensions';

    public const PAGES_ENABLE_CONFIGURATION = 'enable.configuration';

    public const API_ENABLED = 'enabled';
    public const RECAPTCHA_ENABLED = 'enabled';
    public const RECAPTCHA_PUBLIC_KEY = 'public.key';
    public const RECAPTCHA_PRIVATE_KEY = 'private.key';
    public const RECAPTCHA_REQUEST_METHOD = 'request.method';

    public const DEFAULT_FROM_ADDRESS = 'default.from.address';
    public const DEFAULT_FROM_NAME = 'default.from.name';

    public const REPORTS_ALLOW_ALL = 'allow.all.users';

    public const APP_TITLE = 'app.title';

    public const SCHEDULE_PER_USER_COLORS = 'use.per.user.colors';

    public const PASSWORD_UPPER_AND_LOWER = 'upper.and.lower';
    public const PASSWORD_LETTERS = 'minimum.letters';
    public const PASSWORD_NUMBERS = 'minimum.numbers';

    public const RESERVATION_LABELS_ICS_SUMMARY = 'ics.summary';
    public const RESERVATION_LABELS_MY_ICS_SUMMARY = 'ics.my.summary';
    public const RESERVATION_LABELS_RSS_DESCRIPTION = 'rss.description';
    public const RESERVATION_LABELS_MY_CALENDAR = 'my.calendar';
    public const RESERVATION_LABELS_RESOURCE_CALENDAR = 'resource.calendar';
    public const RESERVATION_LABELS_RESERVATION_POPUP = 'reservation.popup';

    public const SECURITY_HEADERS = 'security.headers';
    public const SECURITY_STRICT_TRANSPORT = 'security.strict-transport';
    public const SECURITY_X_FRAME = 'security.x-frame';
    public const SECURITY_X_XSS = 'security.x-xss';
    public const SECURITY_X_CONTENT_TYPE = 'security.x-content-type';
    public const SECURITY_CONTENT_SECURITY_POLICY = 'security.content-security-policy';

    public const GOOGLE_ANALYTICS_TRACKING_ID = 'tracking.id';

    public const AUTHENTICATION_ALLOW_FACEBOOK = 'allow.facebook.login';
    public const AUTHENTICATION_ALLOW_GOOGLE = 'allow.google.login';
    public const AUTHENTICATION_ALLOW_MICROSOFT = 'allow.microsoft.login';
    public const AUTHENTICATION_ALLOW_KEYCLOAK = 'allow.keycloak.login';
    public const AUTHENTICATION_ALLOW_OAUTH2 = 'allow.oauth2.login';
    public const AUTHENTICATION_REQUIRED_EMAIL_DOMAINS = 'required.email.domains';
    public const AUTHENTICATION_HIDE_BOOKED_LOGIN_PROMPT = 'hide.booked.login.prompt';
    public const AUTHENTICATION_CAPTCHA_ON_LOGIN = 'captcha.on.login';

    public const SLACK_TOKEN = 'token';

    public const TABLET_VIEW_ALLOW_GUESTS = 'allow.guest.reservations';
    public const TABLET_VIEW_AUTOCOMPLETE = 'auto.suggest.emails';

    public const USE_DATABASE_SESSION = 'use.database.session';

    public const REGISTRATION_REQUIRE_PHONE = 'require.phone';
    public const REGISTRATION_REQUIRE_ORGANIZATION = 'require.organization';
    public const REGISTRATION_REQUIRE_POSITION = 'require.position';
    public const REGISTRATION_HIDE_PHONE = 'hide.phone';
    public const REGISTRATION_HIDE_ORGANIZATION = 'hide.organization';
    public const REGISTRATION_HIDE_POSITION = 'hide.position';

    public const LOGGING_FOLDER = 'folder';
    public const LOGGING_LEVEL = 'level';
    public const LOGGING_SQL = 'sql';

    public const GOOGLE_CLIENT_ID = 'google.client.id';
    public const GOOGLE_CLIENT_SECRET = 'google.client.secret';
    public const GOOGLE_REDIRECT_URI = 'google.redirect.uri';

    public const MICROSOFT_CLIENT_ID = 'microsoft.client.id';
    public const MICROSOFT_TENANT_ID = 'microsoft.tenant.id';
    public const MICROSOFT_CLIENT_SECRET = 'microsoft.client.secret';
    public const MICROSOFT_REDIRECT_URI = 'microsoft.redirect.uri';

    public const FACEBOOK_CLIENT_ID = 'facebook.client.id';
    public const FACEBOOK_CLIENT_SECRET = 'facebook.client.secret';
    public const FACEBOOK_REDIRECT_URI = 'facebook.redirect.uri';

    public const KEYCLOAK_URL = 'keycloak.url';
    public const KEYCLOAK_REALM = 'keycloak.realm';
    public const KEYCLOAK_CLIENT_ID = 'keycloak.client.id';
    public const KEYCLOAK_CLIENT_SECRET = 'keycloak.client.secret';
    public const KEYCLOAK_REDIRECT_URI = 'keycloak.client.uri';

    public const OAUTH2_NAME = 'oauth2.name';
    public const OAUTH2_URL_AUTHORIZE = 'oauth2.url.authorize';
    public const OAUTH2_URL_TOKEN = 'oauth2.url.token';
    public const OAUTH2_URL_USERINFO = 'oauth2.url.userinfo';
    public const OAUTH2_CLIENT_ID = 'oauth2.client.id';
    public const OAUTH2_CLIENT_SECRET = 'oauth2.client.secret';
    public const OAUTH2_REDIRECT_URI = 'oauth2.client.uri';

    public const YEARS_OLD_DATA = 'years.old.data';
    public const DELETE_OLD_ANNOUNCEMENTS = 'delete.old.announcements';
    public const DELETE_OLD_BLACKOUTS = 'delete.old.blackouts';
    public const DELETE_OLD_RESERVATIONS = 'delete.old.reservations';
}

class ConfigSection
{
    public const API = 'api';
    public const AUTHENTICATION = 'authentication';
    public const CREDITS = 'credits';
    public const DATABASE = 'database';
    public const EMAIL = 'email';
    public const ICS = 'ics';
    public const PAGES = 'pages';
    public const PASSWORD = 'password';
    public const PLUGINS = 'plugins';
    public const PRIVACY = 'privacy';
    public const REPORTS = 'reports';
    public const RESERVATION = 'reservation';
    public const RESERVATION_LABELS = 'reservation.labels';
    public const RESERVATION_NOTIFY = 'reservation.notify';
    public const SCHEDULE = 'schedule';
    public const SECURITY = 'security';
    public const UPLOADS = 'uploads';
    public const RECAPTCHA = 'recaptcha';
    public const USERS = 'users';
    public const GOOGLE_ANALYTICS = 'google.analytics';
    public const PAYMENTS = 'payments';
    public const SLACK = 'slack';
    public const TABLET_VIEW = 'tablet.view';
    public const REGISTRATION = 'registration';
    public const LOGGING = 'logging';
    public const DELETE_OLD_DATA = 'delete.old.data';
}
