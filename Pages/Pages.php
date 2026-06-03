<?php

class Pages
{
    public const ID_DASHBOARD = 1;
    public const ID_LOGIN = 5;

    public const DEFAULT_HOMEPAGE_ID = self::ID_DASHBOARD;

    public const ACTIVATION = 'activate.php';
    public const CALENDAR = 'calendar.php';
    public const CALENDAR_EXPORT = 'ical.php';
    public const CALENDAR_SUBSCRIBE = 'ical-subscribe.php';
    public const CALENDAR_SUBSCRIBE_ATOM = 'atom-subscribe.php';
    public const CHECKOUT = 'checkout.php';
    public const CREDITS = 'credits.php';
    public const DASHBOARD = 'dashboard.php';
    public const DISPLAY_RESOURCE = 'resource-display.php';
    public const DEFAULT_LOGIN = 'dashboard.php';
    public const GUEST_INVITATION_RESPONSES = 'guest-participation.php';
    public const FORGOT_PASSWORD = 'forgot.php';
    public const GUEST_RESERVATION = 'guest-reservation.php';
    public const INVITATION_RESPONSES = 'participation.php';
    public const LOGIN = 'index.php';
    public const MANAGE_RESERVATIONS = 'manage_reservations.php';
    public const MANAGE_GROUPS = 'manage_groups.php';
    public const MANAGE_GROUPS_ADMIN = 'manage_admin_groups.php';
    public const MANAGE_GROUP_RESERVATIONS = 'manage_group_reservations.php';
    public const MY_CALENDAR = 'my-calendar.php';
    public const OPENINGS = 'search-availability.php';
    public const NOTIFICATION_PREFERENCES = 'notification-preferences.php';
    public const PARTICIPATION = 'participation.php';
    public const PASSWORD = 'password.php';
    public const PROFILE = 'profile.php';
    public const REPORTS_GENERATE = 'generate-report.php';
    public const REPORTS_SAVED = 'saved-reports.php';
    public const REPORTS_COMMON = 'common-reports.php';
    public const RESERVATION = 'reservation.php';
    public const RESERVATION_FILE = 'reservation-file.php';
    public const RESOURCE_QR_ROUTER = 'resource-qr-router.php';
    public const REGISTRATION = 'register.php';
    public const SCHEDULE = 'schedule.php';
    public const SEARCH_RESERVATIONS = 'search-reservations.php';
    public const VIEW_CALENDAR = 'view-calendar.php';
    public const VIEW_RESERVATION = 'view-reservation.php';
    public const VIEW_SCHEDULE = 'view-schedule.php';

    private static $_pages = [
        1 => ['url' => Pages::DASHBOARD, 'name' => 'Dashboard'],
        2 => ['url' => Pages::SCHEDULE, 'name' => 'Schedule'],
        3 => ['url' => Pages::MY_CALENDAR, 'name' => 'MyCalendar'],
        4 => ['url' => Pages::CALENDAR, 'name' => 'ResourceCalendar'],
        5 => ['url' => Pages::LOGIN, 'name' => 'Login'],
    ];

    private function __construct()
    {
    }

    public static function UrlFromId($pageId)
    {
        return self::$_pages[$pageId]['url'];
    }

    public static function NameFromId($pageId)
    {
        return self::$_pages[$pageId]['name'];
    }

    public static function GetAvailablePages()
    {
        $pages = [];
        foreach (self::$_pages as $key => $page) {
            if ($key != Pages::ID_LOGIN) {
                $pages[$key] = $page;
            }
        }

        return $pages;
    }
}
