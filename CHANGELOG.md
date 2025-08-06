# CHANGELOG

## 4.0.0 - 2025-08-06

### Highlights

* New configuration file format. Please read the documentation for more
  details. Thanks to @lucs7 for all of their work.
* Initial work on getting PHPStan setup and working. We have level 1 working
  with no baseline and are currently using level 2 with a baseline.
* Add configuration option to be able to choose the resource contact from a
  drop-down list of registered users.
* Language selector now working on the login page.
* New date selector library used, thanks @labmecanicatec

### What's Changed

* style(manage_resources): don't default collapse CustomAttributes by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/682>
* fix: EmailMessage.php variable defined after use by @lucs7 in <https://github.com/LibreBooking/app/pull/683>
* fix: captchas not working by @lucs7 in <https://github.com/LibreBooking/app/pull/684>
* fix(participation): add missing ParticipationNotification import by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/686>
* chore: ensure all config variables are in both config files by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/689>
* fix(reservation): fix the delete reason not submitted when a reservation is not approved. by @belcirelk in <https://github.com/LibreBooking/app/pull/696>
* fix(reservation): pdf generation is not working in French by @belcirelk in <https://github.com/LibreBooking/app/pull/697>
* fix(API): stop storing multiple custom attributes of same type for Reources by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/681>
* chore: Replace jQuery UI datepicker with flatpickr by @labmecanicatec in <https://github.com/LibreBooking/app/pull/756>
* fix(login): Language selector is not working due to httponly cookie by @belcirelk in <https://github.com/LibreBooking/app/pull/763>
* fix: allow setting language for non-HTTPS by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/764>
* chore: remove exec permission from some files by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/765>
* chore: add text to the "More Resource Actions" drop-down by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/766>
* refactor: timezone handling and remove jstz library by @labmecanicatec in <https://github.com/LibreBooking/app/pull/769>
* feat: optional: resource contact may be chosen via a drop-down list of users by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/770>
* Fix for reminders on cancelled reservations by @cgutteridge in <https://github.com/LibreBooking/app/pull/773>
* Fix for phpunit tests and additional error check by @lucs7 in <https://github.com/LibreBooking/app/pull/774>
* fix: error in WebAuthenticationTest and Facebook login by @lucs7 in <https://github.com/LibreBooking/app/pull/781>

### New Contributors

* @belcirelk made their first contribution in <https://github.com/LibreBooking/app/pull/696>

**Full Changelog**: <https://github.com/LibreBooking/app/compare/v3.0.3...v4.0.0>

## 3.0.3 - 2025-07-09

### Highlights

* Improvements to the Installation guide at
  <https://librebooking.readthedocs.io/en/latest/INSTALLATION.html>

### What's Changed

* fix(API): add endpoint to get resource types by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/663>
* remove old docs by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/665>
* fix(schedule): datepicker conflicts with url validation by @lucs7 in <https://github.com/LibreBooking/app/pull/661>
* Fix for #671 / Adjusting css to enable datepicker styling by @lucs7 in <https://github.com/LibreBooking/app/pull/673>
* doc: remove trailing whitespace from *.rst files by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/674>
* fix(CustomAttribute): handle invalid data by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/662>
* fix: allow guests to book reservations by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/668>
* update(docs): update installation documentation: cloning, composer setup, and database config by @labmecanicatec in <https://github.com/LibreBooking/app/pull/675>
* docs: add link to API docs, use notes, update PHP version by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/676>

**Full Changelog**: <https://github.com/LibreBooking/app/compare/v3.0.2...v3.0.3>

## 3.0.2 - 2025-07-07

### Highlights

* Now have a documentation/home page website at <https://librebooking.readthedocs.io/>

### What's Changed

* docs: add a `.readthedocs.yml` file by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/647>
* docs: remove 'beta' designation of the `develop` branch by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/649>
* docs: fix theme options list by @ClemCordier in <https://github.com/LibreBooking/app/pull/650>
* Fix date and autogrow issues by @lucs7 in <https://github.com/LibreBooking/app/pull/657>
* fix: correct datepicker issue introduced in e0cfcbc by @lucs7 in <https://github.com/LibreBooking/app/pull/658>
* fix: don't attempt to reserve view-only resource by @JohnVillalovos in <https://github.com/LibreBooking/app/pull/656>

**Full Changelog**: <https://github.com/LibreBooking/app/compare/v3.0.1...v3.0.2>

## 3.0.1 - 2025-06-25

Update the version number in the code base. This wa missed in the v3.0.0
release.

**Full Changelog**: <https://github.com/LibreBooking/app/compare/v3.0.0...v3.0.1>

## 3.0.0 - 2025-06-24

### Highlights

* Bootstrap 5 Framework and Bootstrap icons now used.
* Modernization of the user interface.
* Integration of libraries such as Datatables and Trumbowyg.
* Migrate external libraries to be pulled in via composer instead of storing the code for those projects in the repository. Also upgraded versions of external libraries used.
* Improvements to the CI system.
* Thanks to @labmecanicatec and @lucs7 for all their work this release.

**Full Changelog**: <https://github.com/LibreBooking/app/compare/2.8.6.2...v3.0.0>

## 2.8.6.2 - 2024-08-18

See all the changes at <https://github.com/LibreBooking/app/commits/develop>

## 2.8.6.1 - 2023-09-26

Mainly Bug fixes, special mention for the ldap plugin, more details at <https://github.com/LibreBooking/app/commits/develop>

## 2.8.6 - 2023-04-18

Librebooking now has PHP8 support
Many bugs, updates and even new features were added but the list is a bit long so for further details please check the commit history <https://github.com/LibreBooking/app/commits/develop>

## 2.8.5.5 - 2022-02-11

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/app](https://github.com/LibreBooking/app)  
Fork this repo, contribute and help keep it alive

Small update to fix a security issue

## 2.8.5.4 - 2021-09-03

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/app](https://github.com/LibreBooking/app)  
Fork this repo, contribute and help keep it alive

Way too many changes, bugfixes and improvements to list them all here, so please take a look at: [https://github.com/LibreBooking/app/commits/master](https://github.com/LibreBooking/app/commits/master)

## 2.8.5.3 - 2021-03-10

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/app](https://github.com/LibreBooking/app)  
Fork this repo, contribute and help keep it alive

* Added translation: Greek
* Updated jsPDF
* Bugfixes

## 2.8.5.2 - 2021-01-25

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/app](<https://github.com/LibreBooking/app>)  
Fork this repo, contribute and help keep it alive - Bugfixes

## 2.8.5.1 - 2020-11-11

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/app](<https://github.com/LibreBooking/app>)

Fork this repo, contribute and help keep it alive - Added intial support for generating pdf's on the reservation page

* Added two plugins (Moodle Advanced Authentication and Admin Check-in/out Only)
* Updated portuguese translation
* Bugfixes

## 2.8.5

* Added import and export of groups
* Updated Danish translation
* Allow lower level administrators edit in-progress reservations
* Added optional email to be sent to users when changing resource status
* Added setting to show week numbers on calendars
* Added settings to require phone, position, and organization during registration
* Bugfixes

## 2.8.4

* Allow reservations on the schedule to be filtered by owner or participant
* Include participant list in reports output
* Add resource concurrency to resource import and export
* Bugfixes

## 2.8.3

* Do not require logging in to set up resource tablet display
* Bugfixes

## 2.8.2

* Added the ability to set a limit on the number of concurrent reservations per resource
* Removed the ability to set a schedule as allowing unlimited concurrent reservations per resource
* Bugfixes

## 2.8.1

* Added ability to limit the total number of concurrent reservations for a schedule
* Added ability to limit the number of resources per reservation for a schedule

## 2.7.8

* Added ability to repeat a reservation on non-sequential dates
* Updated PayPal API to version 2
* Added option to sync group membership when logging in via SAML
* Updated Portuguese, German, and Spanish translations
* Updated PhpCAS to 1.3.8
* MySQL 8+ compatibility
* Bugfixes

## 2.7.7

* Added a configuration option to show whether a reservation is new or updated for a period of time
* Added Hungarian translation
* Bugfixes

## 2.7.6

* Added email notifications when participants of a reservation accept or decline invites
* Added reservation waitlist signup on view reservation page
* Added ability to restrict guests from using tablet view
* Notify users if the creation of a blackout time deletes their reservation
* Updated Portuguese and Finnish translations
* Bugfixes

## 2.7.5

* Added utilization reports
* Added ability to find a specific time
* Added recurring reservation series ending emails
* Added credits to reservation emails
* Added link to add to Google Calendar to reservation emails
* Bugfixes

## 2.7.4

* Added availability view to reservation page
* Added participant list to reservation emails
* Redesign of resource tablet display
* Added ability to search for reservations that missed checkin/checkout
* Bugfixes

## 2.7.3

* Added ability to set user status on CSV import
* Added ability to share reservation details via email
* Added ability set the resources, groups, and schedules a group can administer from Groups tool
* Bugfixes

## 2.7.2

* Added monitor display view
* Resolved accessibility issues
* Added Serbian
* Bugfixes

## 2.7.1

* Added ability to purchase credits
* Added credit usage to the reservation page
* Added ability to set comma or semicolon delimited admin.email configuration setting to allow multiple admin emails
* Added ability to send a reservation to Google Calendar
* Added ability to select a resource image while adding
* Added ability to begin a reservation directly from Slack
* Added ability to set default group membership
* Added ability to require terms of service acknowledgment
* Added ability to set login page announcements
* Added ability to set schedule availability dates
* Added ability to configure different minimum notice rules for reservation add, edit and delete
* Added ability to allow multiple reservations on the same resource at the same time for a schedule
* Added ability to set multiple resource images
* Added ability to set view-only resource permissions
* Added ability to sync group membership from LDAP and CAS
* Added ability to set fully custom layout slots
* Added blackouts to schedule and resource calendar view
* Added view calendar page
* Added ability to embed a Booked calendar view on an external website
* Added ability to require reservation title and description
* Added user groups to report output
* Added ability to set custom favicon
* Added ability to customize email messages
* Added ability to bulk delete resources
* Resource QR code will open ongoing reservation if it requires check in
* Added ability to find an open recurring time
* Upgraded jQuery to latest
* Bugfixes

## 2.6.8

* Added ability to see real time availability when selecting additional resources
* Added the ability to set a delete/reject reason
* Added the ability to update users and resources on import from CSV
* Allow setting phone, organization and position when creating a user from the admin section
* Better highlight pending reservations on Dashboard and popups
* Optimize JavaScript file loading for better page rendering times
* Bugfixes

## 2.6.7

* Added real-time indication of additional resource availability in reservation screen
* Added ability to search for reservations
* Added ability to send user an email when an account is created for them
* Added option to show captcha on login
* Updated reCaptcha to use nocaptcha
* If recurring start and end dates are not the same, then include both in the emails
* Added Basque language
* Added Thai language
* Bugfixes

## 2.6.6

* Added ability to set default start and end reminders
* Added ability to import resources from CSV
* Added ability to export resources to CSV
* Added ability to export users to CSV
* Added ability to include custom attributes in user CSV import
* Added ability to import reservations from CSV
* Added ability to bulk delete users
* Added ability to bulk delete reservations
* Added ability to bulk delete blackouts
* Added ability to drag and drop reservations from calendar views
* Added ability to select multiple options for most report filters
* Added password update API
* Added ability to set number of past and future days to include for Atom and iCalendar subscriptions
* Added ability to apply configured default homepage to existing users
* Saved reports and exported reports will use same columns
* Added credits to manage reservations and reports
* Show if a reservation is pending approval on popups and edit page
* Added config option to notify users if they missed their reservation check in time
* Numerous security fixes
* Bugfixes

## 2.6.5

* Ensure only one reminder email is sent per reservation when multiple resources are booked
* Added Vietnamese
* Added ability to automatically fill in blocked time slots based on gaps in available slots
* Added ability to update a reservation before approving it
* Added resource type filter to reports
* Bugfixes

## 2.6.4

* Use resource color on availability dashboard
* Display reservations for multiple resources as one item on dashboard
* Better handling of dates on the reservation page when an entire day is unavailable
* Allow view schedule to be changed to alternate schedule views
* Upgrade PHPMailer
* Bugfixes

## 2.6.3

* Include resource name in all email subjects
* Added 'Today' link to schedule navigation
* Added real time accessory quantity availability
* Added ability to include email and phone in reservation popup
* Added support for MySQL 5.7+
* Added use sso flag for Active Directory authentication
* Added user available credits to the reservation page
* Added ability to copy a resource
* Added Russian
* Bugfixes and security updates

## 2.6.2

* Added ability to invite users to join Booked
* Added ability to repeat multi-day reservations
* Added additional columns to reports
* Bugfixes
* Updated French language pack

## 2.6.1

* Bugfixes

## 2.6

* Mobile first, fully responsive user interface
* Allow guests to book and be invited to reservations
* Allow users to join wait list if requested time not available
* Control resource usage with credits
* Ability to request that users check in and out of reservations, optionally auto-releasing the reservation
* Allow users to sign in using Facebook or Google
* Require users to register with an email address from a known domain
* Set specific days and hours which quotas are enforced
* Allow quotas to exclude completed reservations
* Added ability to search for an available time rather than browsing schedule
* Require minimum and maximum number of accessories when specific resources are booked
* Ability to restrict announcements to certain groups or users with access to certain resources
* Added ability to book around conflicting reservations
* Added ability to set reservation color by user, resource, or custom attribute value
* Added tablet view that can be used to display resource schedule and allow sign ups
* Added private custom attributes
* Added admin-only custom attributes
* Added resource-aware custom reservation attributes
* Invites are attached to reservation emails as .ics file
* On mobile, allow a picture to be taken for resource image
* The first user to register will automatically be setup as the primary admin
* Numerous minor enhancements and bug fixes

## 2.5.21

* Added ability to duplicate a reservation
* Added ability to move reservations by dragging to new slot
* Added ability to blackout around existing reservations
* Added delete confirmation to reservation window
* Fix API bugs
* Fix bug not showing custom user attributes on manage user page
* Fix for account deleted email

## 2.5.20

* Added multi-date selection to bookings page
* Added ability to send announcements as emails
* Added ability to send email to all users when reservation is cancelled
* Added ability to filter on multiple resources on bookings page
* Added ability to allow cross origin requests for API
* Added ability to import ICS files
* Fixed click and drag on condensed week view
* Fixed problem showing hidden resources on dashboard

## 2.5.19

* Fixed some packaging issues from 2.5.18
* Added ability to filter multiple resources on the schedule
* Updated Japanese language files

## 2.5.18

* Fixed bugs with CSRF checks
* Changed the manage reservation search filter to be inclusive of reservations spanning filtered time
* Fixed issue that didn't maintain selected date in schedule calendar popup
* Fixed double html encode issue for custom attributes
* Fixed issue filtering on custom attributes on manage reservations page
* Added fix to allow larger datasets returned when using group_concat
* Fixed the 'deleted by' name in the account deletion email

## 2.5.17

* Fixed bug preventing schedule view switching on Chrome and IE
* Fixed bug with reports showing no results when searching on accessories
* Fixed issue displaying schedule dates even when no slots are defined
* If register or forgot password urls open in external site, open in new window
* Include total hours in reports
* Changed reservation email message to come from whoever made the reservation
* Added ability to override language strings
* Fixed missing homepageid upon registration
* Fixed missing email address in reservation reminders
* Properly custom attribute regex format if user supplied version is incorrect
* Added ability to remove all assigned permissions for resource
* Added ability to include all reservation attributes in display labels
* Save calendar expand/collapse on schedule page
* Fixed bug determining when to send notification emails
* Fixed bug with PR language
* Changed resource availability web service to use same logic as dashboard
* Fixed issue displaying reservations when date had no slots
* Fixed bug that prevented cookies from being written properly in IE
* Fixed warning when path property is not found in the url
* Removed CSRF check on registration page
* Ensure session is started when rendering captcha
* Fixed syntax issue on PHP 5.3 and lower

## 2.5.16

* Added datetime custom attribute type
* Added ability to import a list of users
* Added ability to manage custom attributes through the API
* Added ability to customize report columns
* Added a yearly quota
* Added API for getting resource types and ability to set resource type in add/update
* Added ability to restrict showing user details to simply on/off or past/future reservations
* Added user deleted email notification
* When a reservation is created on behalf of another user, the user taking action is included in the email notifications
* When a user is created on behalf of another user, the user taking action is included in the email notification
* Improved rendering of schedule when being printed
* Resource details are now shown even if user does not have permission
* Added ability to include Google Analytics
* Fixed bug which prevented joining or canceling a recurring reservation instance if it violated a notice rule
* Fixed resource availability dashboard timeout issues
* Fixed bug with creating and updating reservations through the API
* Fixed bug which over-counted accessories when reservation contained multiple resources
* Fixed bug loading resource type attributes when managing custom attributes
* Fixed bug requiring user to uncheck removed resources from all groups
* Fixed bug for resource groups when they are returned from the db sorted incorrectly
* Fixed bug with upcoming reservations dashboard
* Changed cookies to be scoped to Booked root path
* Implemented CSRF checks (thank you Netsparker)
* Updated French language pack
* Updated Croatian language pack

## 2.5.15

* Added ability for users to join reservations without being invited
* Upgraded CAS library to 1.3.3
* Added Active Directory option to sync group membership into Booked
* Added user details popup
* Added ability to manage user and group permissions from resource management page
* Fixed bug preventing recurring reservations from being deleted in management page
* Fixed bug incorrectly grouping recurring reservations on calendar views
* Updated Italian language
* Updated Spanish language

## 2.5.14

* Added notice to schedule when no resources have been added
* Added emails to participants and invitees when a reservation is updated
* Added resource image to reservation email
* Added ability to set default homepage for new users
* Added dashboard item for current resource availability
* Fixed bug displaying wrong reservation dates on reservation save confirmation message
* Fixed bug on view schedule page when using daily layouts
* Fixed bug preventing individual reservations from being added to external calendars
* Fixed bug which did not check Sunday checkbox on recurring reservations
* Fixed bug on dst change preventing all reservations on that day
* Fixed bug causing permission updates performed by schedule admins to wipe out certain permissions
* Updated Italian language pack
* Updated Spanish language pack

## 2.5.13

* Fixed bug preventing reservations from being added to Outlook
* Fixed bug preventing accessories from showing in reservation popup
* Fixed bug preventing resource filter from working on view schedule
* Added Drupal authentication plugin (Drupal 7.x with MySQL only)
* Added ability to display participant and invitee lists in the reservation label
* Applied patch for HTTP security headers
* Updated Italian language

## 2.5.12

* Fixed English admin help page

## 2.5.11

* Fixed issue that was sending approval request emails on every reservation create/update if approval emails were enabled

## 2.5.10

* Fixed issue sending email from \*nix servers

## 2.5.9

* Added custom attributes to reports
* Added resource groups to calendar views
* Added ability to enter maintenance mode
* Added ability manage user groups through API
* Added more options for customizing the reservation slot label, including using custom attributes
* Added ability to customize reservation label for My Calendar, Resource Calendar, ICS feeds, RSS feeds and the reservation popup
* Added list of dates and resources to reservation confirmation message
* Added ability to receive reservation approval request emails
* Added API to get schedule slots
* Added finer-grained control over what profile values can be managed through Booked when using an authentication plugin
* Reduced the size of the bookings page
* Fixed bug graying out resources and dates when user and schedule timezone don't match
* Fixed bug handling non-UTC dates in API
* Fixed bug performing case sensitive match when checking if user is admin
* Fixed bug for GetAvailability API
* Updated German language files
* Updated Portuguese language files

## 2.5.8

* Added schedule and resource filter to My Calendar
* Fixed bug displaying week in calendar views
* Reduced the size of the bookings page by \~35%
* Updated German language files
* Updated Japanese language files
* Updated Portuguese language files

## 2.5.7

* Fixed potential XSS vulnerability on login page

## 2.5.6

* Fixed problem navigating to reservation details from tall schedule view
* Fixed problem rendering resource group management page

## 2.5.5

* Fixed problem updating plugin config files through UI
* Fixed date parsing in web services

## 2.5.4

* Fixed error updating resources

## 2.5.3

* Fixed manage reservations/resources custom attribute filter when multiple attributes are provided
* Fixed javascript error when recaptcha is disabled during registration
* Fixed error updating usage configuration of resources
* Fixed installer to handle the case when the database exists but no tables have been created
* Changed installer to use mysqli
* Fixed error filtering blackouts by resource
* Fixed error creating recurring reservation which sometimes picked the wrong week of the month

## 2.5.2

* Added ability for admins to filter reservations by custom attributes
* Added ability for admins update reservation custom attributes inline on manage reservations page
* Added paging and filtering on Manage Resources
* Added bulk update on Manage Resources
* Added admin dashboard for all upcoming reservations
* Added ability to leave protocol off script.url setting to auto-detect http vs https
* Fixed bug failing to display error message when invalid daily layout is being created
* Fixed missing HTML tags on print report page
* Added Croatian translation
* Updated Czech translation
* Fixed overly restrictive password validator
* Changed reservation confirmation screen to notify when the reservation requires approval
* Updates to Italian language pack

## 2.5.1

* Updated German language files
* Changed reservations web service to not default to current user if no user is provided
* Added resource availability web service
* Added reservation approval web service
* Fixed bug creating a opening new reservation window without a selected resource id
* Fixed bug where reservations ending at midnight would show on the next day for condensed view
* Fixed bug where role restricted pages could not be opened up to everyone
* Fixed bug when a hidden resource belongs to a group
* Fixed bug with schedule admin being able to see reservation list and see blackout list
* Fixed bug where readonly schedule page failed to render
* Fixed bug adding/removing resource images
* Fixed sample data import
* Cleaned up sample post-reservation plugin example

## 2.5

* Application renamed from phpScheduleIt to Booked Scheduler [(why?)](http://www.bookedscheduler.com/phpscheduleit)
* Added ability to reserve resource groups
* Added ability to filter schedule resources
* Added ability to specify resource type
* Added enhanced resource status management
* Added ability to specify buffer time between reservations (per resource)
* Custom attributes now appear on all reservation emails and balloons
* Added ability set custom attributes for an individual resource, user or resource type
* Added ability manage config files for all plugins through the UI
* Added ability to set reservation colors per user
* Added ability to subscribe to reservation Atom feeds
* Added ability update blackouts
* Added ability attach multiple items to a reservation
* Added Shibboleth authentication plugin (thank you to the folks at UCSF)
* Added ability to email admin for all new account creations
* Updates and cleanup on the API
* Removed password regex setting in favor of password complexity settings
* Changed schedule drop downs to exclude schedules if the user does not have permission to any of the resources belonging to it
* Added wide and condensed booking page views
* Added option to allow all users access to reports
* Added setting for default 'from' email address
* Changed the reservation page to default to the minimum resource reservation time
* Changed reservation update to grant permissions to all users if auto-assign permissions is being turned on
* Fixed showing 'Private' when the current user is the reservation owner
* Fixed bug where recurring reservations across daylight savings time boundaries were not being updated to the correct time
* Fixed bug where schedule would freeze on certain daylight savings boundaries
* Fixed pagination bug on manage reservations page
* Fixed bug allowing invitees to join a reservation that was already at capacity
* Fixed bug not enforcing resource cross day reservation constraint
* Fixed bug where quota rules were being enforced cumulatively for resources on a schedule
* Fixed bug where reminders were being sent for deleted reservations
* Updated all mysql_\* calls to mysqli_\*
* Numerous other minor fixes and updates

## 2.4.2

* Added ability to click and drag to create reservations
* Added ability hide blocked slots on schedule
* Added ability to view reservation participation on schedule
* Changed migration process to be asynchronous
* Fixed bug preventing reminders from running on some servers
* Fixed bug hiding labels for periods less than 1 hour
* Fixed bug in configuration management escaping special characters
* Fixed bug when changing start date/end date on reservation page
* Fixed bug selecting wrong start time when user and schedule timezones are different
* Updated German, Portuguese and Hebrew languages

## 2.4.1

* Changed periods spanning less than an hour to display tick marks instead of times
* Fixed bug when displaying vertical schedule when reservation title contained special characters
* Fixed bug in migration script not copying legacy password correctly
* Fixed bugs generating API documentation

## 2.4

* Added restful API
* Added ability to set different layouts for each day of the week
* Added ability to set reminders for reservation beginning and end
* Added UI management page for changing configuration
* Added ability for users to set default schedule
* Added ability to display schedules vertically
* Text for slot labels is now tokenized
* Added WordPress authentication plugin
* Added ability to use reCAPTCHA instead of built in captcha
* Added ability to set logo and custom css files
* Added configurable home page and logout urls
* Added ability to manage user groups from user management page
* Added Bulgarian and Flemisch language packs
* Localized the installation and configuration pages
* Fixed issues with accessory and reservation migration
* Added ability to disable password reset
* Numerous bug fixes and minor enhancements

## 2.3

* Added ability for administrators of all levels to create reports
* Added ability to create a reservation from the schedule and resource calendar views
* Added ability to create recurring blackout dates
* Added schedule admin role
* Added setting to disable recurring reservations for non-admins
* Added setting to automatically subscribe users to all emails
* Added setting to prevent reservation invitations and participation
* Added setting to load jQuery from CDN
* Added setting to return reservation to pending when updated
* Added Swedish translation
* Added full resource and accessory list to reservation emails
* Added ability to set resource order
* Added email address to user autocomplete
* Numerous minor enhancements added and defects fixed

## 2.2

* Breaking change: For Active Directory authentication, please set your authentication plugin to ActiveDirectory. Ldap plugin is now targeted at non-Active Directory.
* Added ability to create custom attributes
* Rewrote CAPTCHA functionality
* Added account activation emails
* Added ability to upload reservation attachments
* Made post-registration action pluggable
* Added Saml SSO Authentication plugin
* Made configuring resource image directories easier
* Added ability to start schedules on Today
* Numerous minor enhancements added and defects fixed

## 2.1

* Added resource administrator role
* Added configurable ability for application admins, resource admins and group admins to recieve reservation activity emails
* Added configuration options for user name formatting, resource editing rules, privacy settings and CSS extension file
* Added ability to subscribe to schedule, resource and personal calendars
* Added option for owner to receive emails when reservations are deleted
* Added participant email notifications when reservations are deleted
* Added ability use full HTML in announcements and resource descriptions/notes
* Many bug fixes, including: reservation approval, reservation admin delete, resource configuration, admin user creation, group user management, registration CAPTCHA
* Added Dutch, Spanish, Italian, Japanese, Polish, Catalan languages

## 2.0.2

* Fix and additional logging for migration
* Minor UI cleanup of validation group error div
* Fixed defect with captcha
* Fixed defect not translating full day names properly when using date formatting
* Fixed some IE7 display problems
* Updated install instructions to be more clear for cPanel users
* Dashboard now shows upcoming reservations for owned/invited/participating
* Fixed defect on quotas which was not working for non English
* Fixed defect where accessories with unlimited quantities were being rejected
* Fixed defect on manage blackouts
* Added pre-reservation plugin example
* Ajax reservation now displays errors
* Fixed defect selecting first period instead of last period when reservation ends at start time of first period
* Fixed defect displaying reservation on first period of the day if it ends at the first period's start time
* Fixed bug adding users from the admin tool
* Fixed javascript single quote bugs

## 2.0.1

* Perfomance improvements on bookings page
* Added Spanish and Dutch translations
* Added ability to view reservation details from view schedule page
* Fixed defect loading translated emails
* Fixed defect approving reservations
* Fixed defects when using IE
* Fixed defect showing an error during log out when using LDAP

## 2.0

* Fully rewritten from scratch with a focus on testability, extensibility and maintainability
* All new, more intuitive and friendly user interface
* Pluggable authentication, authorization, permissions, pre/post reservation actions
* Ability to reserve multiple resources at one time
* Flexible layout configuration and time slot labeling
* Quotas
* Roles
* Better Microsoft Outlook integration
* Easier installation process
