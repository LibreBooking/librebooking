<?php

mysqli_report(MYSQLI_REPORT_OFF);
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/**
 * Application configuration
 */
$conf['settings']['app.title'] = 'LibreBooking';            // application title
$conf['settings']['default.timezone'] = 'Etc/UTC';              // look up here http://php.net/manual/en/timezones.php
$conf['settings']['allow.self.registration'] = 'true';          // if users can register themselves
$conf['settings']['admin.email'] = 'admin@example.com';         // email address of admin user
$conf['settings']['admin.email.name'] = 'LB Administrator'; // name to be used in From: field when sending automatic emails
$conf['settings']['company.name'] = '';                         // name of company, if applicable
$conf['settings']['company.url'] = '';                          // URL of company, if applicable
$conf['settings']['default.page.size'] = '50';                  // number of records per page
$conf['settings']['enable.email'] = 'true';                     // global configuration to enable if any emails will be sent
$conf['settings']['default.language'] = 'en_us';                // find your language in the lang directory
$conf['settings']['script.url'] = '';                           // public URL to the Web directory of this instance. this is the URL that appears when you are logging in. leave http: or https: off to auto-detect
$conf['settings']['image.upload.directory'] = 'Web/uploads/images'; // full or relative path to where images will be stored
$conf['settings']['image.upload.url'] = 'uploads/images';       // full or relative path to show uploaded images from
$conf['settings']['cache.templates'] = 'false';                 // true recommended, caching template files helps web pages render faster
$conf['settings']['use.local.js.libs'] = 'false';               // false recommended, delivers jQuery from Google CDN, uses less bandwidth
$conf['settings']['registration.captcha.enabled'] = 'true';     // recommended. unless using recaptcha this requires php_gd2 enabled in php.ini
$conf['settings']['registration.require.email.activation'] = 'false';       // requires enable.email = true
$conf['settings']['registration.auto.subscribe.email'] = 'false';           // requires enable.email = true
$conf['settings']['registration.notify.admin'] = 'false';       // whether the registration of a new user sends an email to the admin (ala phpScheduleIt 1.2)
$conf['settings']['inactivity.timeout'] = '30';                 // minutes before the user is automatically logged out
$conf['settings']['name.format'] = '{first} {last}';            // display format when showing user names
$conf['settings']['css.extension.file'] = '';                   // full or relative url to an additional css file to include. this can be used to override the default style
$conf['settings']['disable.password.reset'] = 'false';          // if the password reset functionality should be disabled
$conf['settings']['home.url'] = '';                             // the url to open when the logo is clicked
$conf['settings']['logout.url'] = '';                           // the url to be directed to after logging out
$conf['settings']['default.homepage'] = '1';                    // the default homepage to use when new users register (1 = Dashboard, 2 = Schedule, 3 = My Calendar, 4 = Resource Calendar)

$conf['settings']['schedule']['use.per.user.colors'] = 'false';         // color reservations by user
$conf['settings']['schedule']['show.inaccessible.resources'] = 'true';  // whether or not resources that are inaccessible to the user are visible
$conf['settings']['schedule']['reservation.label'] = '{name}';          // format for what to display on the reservation slot label.  Available properties are: {name}, {title}, {description}, {email}, {phone}, {organization}, {position}, {startdate}, {enddate} {resourcename} {participants} {invitees} {reservationAttributes}. Custom attributes can be added using att with the attribute id. For example {att1}
$conf['settings']['schedule']['hide.blocked.periods'] = 'false';        // if blocked periods should be hidden or shown
$conf['settings']['schedule']['update.highlight.minutes'] = '0';    // if set, will show reservations as 'updated' for a certain amount of time
$conf['settings']['schedule']['show.week.numbers'] = 'false';
$conf['settings']['schedule']['fast.reservation.load'] = 'false';  // Experimental: Use new algorithm to load reservations faster in the schedule. Currently does not support concurrent reservations. With larger number of resources this can be 10x or 100x faster. Only runs with the StandardSchedule otherwise will fall back to legacy mode.
/**
 * ical integration configuration
 */
$conf['settings']['ics']['subscription.key'] = '';              // must be set to allow webcal subscriptions
$conf['settings']['ics']['future.days'] = '30';
$conf['settings']['ics']['past.days'] = '0';
/**
 * Privacy configuration
 */
$conf['settings']['privacy']['view.schedules'] = 'true';                // if unauthenticated users can view schedules
$conf['settings']['privacy']['view.reservations'] = 'false';                // if unauthenticated users can view reservations
$conf['settings']['privacy']['hide.user.details'] = 'false';                // if personal user details should be displayed to non-administrators
$conf['settings']['privacy']['hide.reservation.details'] = 'false';         // if reservation details should be displayed to non-administrators. options are true, false, current, future, past
$conf['settings']['privacy']['allow.guest.reservations'] = 'false';         // if reservations can be made by users without a LibreBooking account, if true this overrides schedule and resource visibility
/**
 * Reservation specific configuration
 */
$conf['settings']['reservation']['start.time.constraint'] = 'future';       // when reservations can be created or edited. options are future, current, none
$conf['settings']['reservation']['updates.require.approval'] = 'false';     // if updates to previously approved reservations require approval again
$conf['settings']['reservation']['prevent.participation'] = 'false';        // if participation and invitation options should be removed
$conf['settings']['reservation']['prevent.recurrence'] = 'false';           // if recurring reservations are disabled for non-administrators
$conf['settings']['reservation']['enable.reminders'] = 'false';             // if reminders are enabled. this requires email to be enabled and the reminder job to be configured
$conf['settings']['reservation']['allow.guest.participation'] = 'false';
$conf['settings']['reservation']['allow.wait.list'] = 'false';
$conf['settings']['reservation']['checkin.minutes.prior'] = '5';
$conf['settings']['reservation']['default.start.reminder'] = '';            // the default start reservation reminder. format is ## interval. for example, 10 minutes, 2 hours, 6 days.
$conf['settings']['reservation']['default.end.reminder'] = '';              // the default end reservation reminder. format is ## interval. for example, 10 minutes, 2 hours, 6 days.
$conf['settings']['reservation']['title.required'] = 'false';
$conf['settings']['reservation']['description.required'] = 'false';
$conf['settings']['reservation']['checkin.admin.only'] = 'true';
$conf['settings']['reservation']['checkout.admin.only'] = 'true';
/**
 * Email notification configuration
 */
$conf['settings']['reservation.notify']['resource.admin.add'] = 'false';
$conf['settings']['reservation.notify']['resource.admin.update'] = 'false';
$conf['settings']['reservation.notify']['resource.admin.delete'] = 'false';
$conf['settings']['reservation.notify']['resource.admin.approval'] = 'false';
$conf['settings']['reservation.notify']['application.admin.add'] = 'false';
$conf['settings']['reservation.notify']['application.admin.update'] = 'false';
$conf['settings']['reservation.notify']['application.admin.delete'] = 'false';
$conf['settings']['reservation.notify']['application.admin.approval'] = 'false';
$conf['settings']['reservation.notify']['group.admin.add'] = 'false';
$conf['settings']['reservation.notify']['group.admin.update'] = 'false';
$conf['settings']['reservation.notify']['group.admin.delete'] = 'false';
$conf['settings']['reservation.notify']['group.admin.approval'] = 'false';
/**
 * File upload configuration
 */
$conf['settings']['uploads']['enable.reservation.attachments'] = 'false';   // if reservation attachments can be uploaded
$conf['settings']['uploads']['reservation.attachment.path'] = 'uploads/reservation';    // full or relative (to the root of your installation) filesystem path to store reservation attachments
$conf['settings']['uploads']['reservation.attachment.extensions'] = 'txt,jpg,gif,png,doc,docx,pdf,xls,xlsx,ppt,pptx,csv';   // comma separated list of file extensions that users are allowed to attach. leave empty to allow all extensions
/**
 * Database configuration
 */
$conf['settings']['database']['type'] = 'mysql';
$conf['settings']['database']['user'] = 'test';        // database user with permission to the LibreBooking database
$conf['settings']['database']['password'] = 'test';
$conf['settings']['database']['hostspec'] = '127.0.0.1';      // ip, dns or named pipe
$conf['settings']['database']['name'] = 'librebooking';
/**
 * Mail server configuration
 */
$conf['settings']['phpmailer']['mailer'] = 'smtp';              // options are 'mail', 'smtp' or 'sendmail'
$conf['settings']['phpmailer']['smtp.host'] = 'localhost';               // 'smtp.company.com'
$conf['settings']['phpmailer']['smtp.port'] = '25';
$conf['settings']['phpmailer']['smtp.secure'] = '';             // options are '', 'ssl' or 'tls'
$conf['settings']['phpmailer']['smtp.auth'] = 'false';           // options are 'true' or 'false'
$conf['settings']['phpmailer']['smtp.username'] = '';
$conf['settings']['phpmailer']['smtp.password'] = '';
$conf['settings']['phpmailer']['sendmail.path'] = '/usr/sbin/sendmail';
$conf['settings']['phpmailer']['smtp.debug'] = 'false';
/**
 * Plugin configuration.  For more on plugins, see readme_installation.html
 */
$conf['settings']['plugins']['Authentication'] = '';
$conf['settings']['plugins']['Authorization'] = '';
$conf['settings']['plugins']['Permission'] = '';
$conf['settings']['plugins']['PostRegistration'] = '';
$conf['settings']['plugins']['PreReservation'] = '';
$conf['settings']['plugins']['PostReservation'] = '';
$conf['settings']['plugins']['Styling'] = '';
/**
 * Installation settings
 */
$conf['settings']['install.password'] = '';
/**
 * Pages
 */
$conf['settings']['pages']['enable.configuration'] = 'true';
/**
 * API
 */
$conf['settings']['api']['enabled'] = 'false';
$conf['settings']['api']['allow.self.registration'] = 'false';
/**
 * ReCaptcha
 */
$conf['settings']['recaptcha']['enabled'] = 'false';
$conf['settings']['recaptcha']['public.key'] = '';
$conf['settings']['recaptcha']['private.key'] = '';
$conf['settings']['recaptcha']['request.method'] = 'curl'; // options are curl, post or socket. default: post
/**
 * Email
 */
$conf['settings']['email']['default.from.address'] = '';
$conf['settings']['email']['default.from.name'] = '';
/**
 * Reports
 */
$conf['settings']['reports']['allow.all.users'] = 'false';
/**
 * Account Password Rules
 */
$conf['settings']['password']['minimum.letters'] = '6';
$conf['settings']['password']['minimum.numbers'] = '0';
$conf['settings']['password']['upper.and.lower'] = 'false';
/**
 * Label display settings
 */
$conf['settings']['reservation.labels']['ics.summary'] = '{title}';
$conf['settings']['reservation.labels']['ics.my.summary'] = '{title}';
$conf['settings']['reservation.labels']['rss.description'] = '<div><span>Start</span> {startdate}</div><div><span>End</span> {enddate}</div><div><span>Organizer</span> {name}</div><div><span>Description</span> {description}</div>';
$conf['settings']['reservation.labels']['my.calendar'] = '{resourcename} {title}';
$conf['settings']['reservation.labels']['resource.calendar'] = '{name}';
$conf['settings']['reservation.labels']['reservation.popup'] = ''; // Format for what to display in reservation popups. Possible values: {name} {dates} {title} {resources} {participants} {accessories} {description} {attributes} {pending} {duration}. Custom attributes can be added using att with the attribute id. For example {att1}
/**
 * Security header settings
 */
$conf['settings']['security']['security.headers'] = 'false'; // Enable the following options
$conf['settings']['security']['security.strict-transport'] = 'max-age=31536000';
$conf['settings']['security']['security.x-frame'] = 'deny';
$conf['settings']['security']['security.x-xss'] = '1; mode=block';
$conf['settings']['security']['security.x-content-type'] = 'nosniff';
$conf['settings']['security']['security.content-security-policy'] = ""; // Requires careful tuning (know what your doing)
/**
 * Google Analytics settings
 */
$conf['settings']['google.analytics']['tracking.id'] = ''; // if set, Google Analytics tracking code will be added to every page in LibreBooking

$conf['settings']['authentication']['allow.facebook.login'] = 'false';
$conf['settings']['authentication']['allow.google.login'] = 'false';
$conf['settings']['authentication']['required.email.domains'] = '';
$conf['settings']['authentication']['hide.booked.login.prompt'] = 'false';
$conf['settings']['authentication']['captcha.on.login'] = 'false';
/**
 * Credits
 */
$conf['settings']['credits']['enabled'] = 'false';
$conf['settings']['credits']['allow.purchase'] = 'false';
/**
 * Slack integration
 */
$conf['settings']['slack']['token'] = '';
/**
 * Tablet view
 */
$conf['settings']['tablet.view']['allow.guest.reservations'] = 'false';
$conf['settings']['tablet.view']['auto.suggest.emails'] = 'false';
/**
 * Registration
 */
$conf['settings']['registration']['require.phone'] = 'false';
$conf['settings']['registration']['require.position'] = 'false';
$conf['settings']['registration']['require.organization'] = 'false';
/**
 * Error logging
 */
$conf['settings']['logging']['folder'] = '/var/log/librebooking/log'; //Absolute path to folder were the log will be written, writing permissions to the folder are required
$conf['settings']['logging']['level'] = 'debug'; //Set to none disable logs, error to only log errors or debug to log all messages to the app.log file
$conf['settings']['logging']['sql'] = 'false'; //Set to true no enable the creation of and sql.log file
