<?php

$conf['settings']['app.title'] = 'LibreBooking';
$conf['settings']['default.timezone'] = 'UTC';
$conf['settings']['allow.self.registration'] = 'true';
$conf['settings']['admin.email'] = 'admin@example.com';
$conf['settings']['admin.email.name'] = 'LB Administrator';
$conf['settings']['company.name'] = '';
$conf['settings']['company.url'] = '';
$conf['settings']['default.page.size'] = '50';
$conf['settings']['enable.email'] = 'true';
$conf['settings']['default.language'] = 'en_us';
$conf['settings']['enforce.custom.mail.template'] = 'false';
$conf['settings']['script.url'] = '';
$conf['settings']['image.upload.directory'] = 'Web/uploads/images';
$conf['settings']['image.upload.url'] = 'uploads/images';
$conf['settings']['cache.templates'] = 'false';
$conf['settings']['use.local.js.libs'] = 'false';
$conf['settings']['registration.captcha.enabled'] = 'true';
$conf['settings']['registration.require.email.activation'] = 'false';
$conf['settings']['registration.auto.subscribe.email'] = 'false';
$conf['settings']['registration.notify.admin'] = 'false';
$conf['settings']['inactivity.timeout'] = '30';
$conf['settings']['name.format'] = '{first} {last}';
$conf['settings']['css.extension.file'] = '';
$conf['settings']['css.theme'] = 'default';
$conf['settings']['disable.password.reset'] = 'false';
$conf['settings']['home.url'] = '';
$conf['settings']['logout.url'] = '';
$conf['settings']['default.homepage'] = '1';
$conf['settings']['resource.contact.is.user'] = 'false';

$conf['settings']['schedule']['use.per.user.colors'] = 'false';
$conf['settings']['schedule']['show.inaccessible.resources'] = 'true';
$conf['settings']['schedule']['reservation.label'] = '{name}';
$conf['settings']['schedule']['hide.blocked.periods'] = 'false';
$conf['settings']['schedule']['update.highlight.minutes'] = '0';
$conf['settings']['schedule']['show.week.numbers'] = 'false';
$conf['settings']['schedule']['fast.reservation.load'] = 'false';
$conf['settings']['schedule']['load.mobile.views'] = 'true';
$conf['settings']['schedule']['auto.scroll.today'] = 'true';

$conf['settings']['ics']['subscription.key'] = '';
$conf['settings']['ics']['future.days'] = '30';
$conf['settings']['ics']['past.days'] = '0';

$conf['settings']['privacy']['view.schedules'] = 'true';
$conf['settings']['privacy']['view.reservations'] = 'false';
$conf['settings']['privacy']['hide.user.details'] = 'false';
$conf['settings']['privacy']['hide.reservation.details'] = 'false';
$conf['settings']['privacy']['allow.guest.reservations'] = 'false';
$conf['settings']['privacy']['public.future.days'] = '1';

$conf['settings']['reservation']['start.time.constraint'] = 'future';
$conf['settings']['reservation']['updates.require.approval'] = 'false';
$conf['settings']['reservation']['prevent.participation'] = 'false';
$conf['settings']['reservation']['prevent.recurrence'] = 'false';
$conf['settings']['reservation']['enable.reminders'] = 'false';
$conf['settings']['reservation']['allow.guest.participation'] = 'false';
$conf['settings']['reservation']['allow.wait.list'] = 'false';
$conf['settings']['reservation']['checkin.minutes.prior'] = '5';
$conf['settings']['reservation']['default.start.reminder'] = '';
$conf['settings']['reservation']['default.end.reminder'] = '';
$conf['settings']['reservation']['title.required'] = 'false';
$conf['settings']['reservation']['description.required'] = 'false';
$conf['settings']['reservation']['checkin.admin.only'] = 'true';
$conf['settings']['reservation']['checkout.admin.only'] = 'true';

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

$conf['settings']['uploads']['enable.reservation.attachments'] = 'false';
$conf['settings']['uploads']['reservation.attachment.path'] = 'uploads/reservation';
$conf['settings']['uploads']['reservation.attachment.extensions'] = 'txt,jpg,gif,png,doc,docx,pdf,xls,xlsx,ppt,pptx,csv';

$conf['settings']['database']['type'] = 'mysql';
$conf['settings']['database']['user'] = 'test';
$conf['settings']['database']['password'] = 'test';
$conf['settings']['database']['hostspec'] = '127.0.0.1';
$conf['settings']['database']['name'] = 'librebooking';

$conf['settings']['phpmailer']['mailer'] = 'smtp';
$conf['settings']['phpmailer']['smtp.host'] = 'localhost';
$conf['settings']['phpmailer']['smtp.port'] = '25';
$conf['settings']['phpmailer']['smtp.secure'] = '';
$conf['settings']['phpmailer']['smtp.auth'] = 'false';
$conf['settings']['phpmailer']['smtp.username'] = '';
$conf['settings']['phpmailer']['smtp.password'] = '';
$conf['settings']['phpmailer']['sendmail.path'] = '/usr/sbin/sendmail';
$conf['settings']['phpmailer']['smtp.debug'] = 'false';

$conf['settings']['plugins']['Authentication'] = '';
$conf['settings']['plugins']['Authorization'] = '';
$conf['settings']['plugins']['Export'] = '';
$conf['settings']['plugins']['Permission'] = '';
$conf['settings']['plugins']['PostRegistration'] = '';
$conf['settings']['plugins']['PreReservation'] = '';
$conf['settings']['plugins']['PostReservation'] = '';
$conf['settings']['plugins']['Styling'] = '';

$conf['settings']['install.password'] = '';
$conf['settings']['pages']['enable.configuration'] = 'true';

$conf['settings']['api']['enabled'] = 'false';
$conf['settings']['api']['allow.self.registration'] = 'false';

$conf['settings']['recaptcha']['enabled'] = 'false';
$conf['settings']['recaptcha']['public.key'] = '';
$conf['settings']['recaptcha']['private.key'] = '';
$conf['settings']['recaptcha']['request.method'] = 'curl';

$conf['settings']['email']['default.from.address'] = '';
$conf['settings']['email']['default.from.name'] = '';

$conf['settings']['reports']['allow.all.users'] = 'false';

$conf['settings']['password']['minimum.letters'] = '6';
$conf['settings']['password']['minimum.numbers'] = '0';
$conf['settings']['password']['upper.and.lower'] = 'false';

$conf['settings']['reservation.labels']['ics.summary'] = '{title}';
$conf['settings']['reservation.labels']['ics.my.summary'] = '{title}';
$conf['settings']['reservation.labels']['rss.description'] = '<div><span>Start</span> {startdate}</div><div><span>End</span> {enddate}</div><div><span>Organizer</span> {name}</div><div><span>Description</span> {description}</div>';
$conf['settings']['reservation.labels']['my.calendar'] = '{resourcename} {title}';
$conf['settings']['reservation.labels']['resource.calendar'] = '{name}';
$conf['settings']['reservation.labels']['reservation.popup'] = '';

$conf['settings']['security']['security.headers'] = 'false';
$conf['settings']['security']['security.strict-transport'] = 'max-age=31536000';
$conf['settings']['security']['security.x-frame'] = 'deny';
$conf['settings']['security']['security.x-xss'] = '1; mode=block';
$conf['settings']['security']['security.x-content-type'] = 'nosniff';
$conf['settings']['security']['security.content-security-policy'] = '';

$conf['settings']['google.analytics']['tracking.id'] = 'tracking_id';

$conf['settings']['authentication']['allow.facebook.login'] = 'false';
$conf['settings']['authentication']['allow.google.login'] = 'false';
$conf['settings']['authentication']['allow.microsoft.login'] = 'false';
$conf['settings']['authentication']['allow.oauth2.login'] = 'false';
$conf['settings']['authentication']['required.email.domains'] = '';
$conf['settings']['authentication']['hide.booked.login.prompt'] = 'false';
$conf['settings']['authentication']['captcha.on.login'] = 'false';

$conf['settings']['credits']['enabled'] = 'false';
$conf['settings']['credits']['allow.purchase'] = 'false';

$conf['settings']['slack']['token'] = 'slack_token';

$conf['settings']['tablet.view']['allow.guest.reservations'] = 'false';
$conf['settings']['tablet.view']['auto.suggest.emails'] = 'false';

$conf['settings']['registration']['require.phone'] = 'false';
$conf['settings']['registration']['require.position'] = 'false';
$conf['settings']['registration']['require.organization'] = 'false';
$conf['settings']['registration']['hide.phone'] = 'false';
$conf['settings']['registration']['hide.position'] = 'false';
$conf['settings']['registration']['hide.organization'] = 'false';

$conf['settings']['logging']['folder'] = '/var/log/librebooking/log';
$conf['settings']['logging']['level'] = 'debug';
$conf['settings']['logging']['sql'] = 'false';

$conf['settings']['authentication']['google.client.id'] = '';
$conf['settings']['authentication']['google.client.secret'] = '';
$conf['settings']['authentication']['google.redirect.uri'] = '/Web/google-auth.php';

$conf['settings']['authentication']['microsoft.client.id'] = '';
$conf['settings']['authentication']['microsoft.tenant.id'] = 'common';
$conf['settings']['authentication']['microsoft.client.secret'] = '';
$conf['settings']['authentication']['microsoft.redirect.uri'] = '/Web/microsoft-auth.php';

$conf['settings']['authentication']['facebook.client.id'] = '';
$conf['settings']['authentication']['facebook.client.secret'] = '';
$conf['settings']['authentication']['facebook.redirect.uri'] = '/Web/facebook-auth.php';

$conf['settings']['authentication']['keycloak.url'] = '';
$conf['settings']['authentication']['keycloak.realm'] = '';
$conf['settings']['authentication']['keycloak.client.id'] = '';
$conf['settings']['authentication']['keycloak.client.secret'] = '';
$conf['settings']['authentication']['keycloak.client.uri'] = '/Web/keycloak-auth.php';

$conf['settings']['authentication']['oauth2.name'] = 'OAuth2';
$conf['settings']['authentication']['oauth2.url.authorize'] = '';
$conf['settings']['authentication']['oauth2.url.token'] = '';
$conf['settings']['authentication']['oauth2.url.userinfo'] = '';
$conf['settings']['authentication']['oauth2.client.id'] = '';
$conf['settings']['authentication']['oauth2.client.secret'] = '';
$conf['settings']['authentication']['oauth2.client.uri'] = '/Web/oauth2-auth.php';

$conf['settings']['delete.old.data']['years.old.data'] = '3';
$conf['settings']['delete.old.data']['delete.old.announcements'] = 'false';
$conf['settings']['delete.old.data']['delete.old.blackouts'] = 'false';
$conf['settings']['delete.old.data']['delete.old.reservations'] = 'false';

$conf['settings']['api']['Authentication.group'] = '';
$conf['settings']['api']['Accessories.ro.group'] = '';
$conf['settings']['api']['Accounts.ro.group'] = '';
$conf['settings']['api']['Accounts.rw.group'] = '';
$conf['settings']['api']['Attributes.ro.group'] = '';
$conf['settings']['api']['Groups.ro.group'] = '';
$conf['settings']['api']['Reservations.ro.group'] = '';
$conf['settings']['api']['Reservations.rw.group'] = '';
$conf['settings']['api']['Resources.ro.group'] = '';
$conf['settings']['api']['Schedules.ro.group'] = '';
$conf['settings']['api']['Users.ro.group'] = '';
