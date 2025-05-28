<?php

require_once('Language.php');

class en_us extends Language
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _LoadDates()
    {
        $dates = [];

        $dates['general_date'] = 'm/d/Y';
        $dates['general_datetime'] = 'm/d/Y g:i:s A';
        $dates['short_datetime'] = 'n/j/y g:i A';
        $dates['schedule_daily'] = 'l, n/j/y';
        $dates['reservation_email'] = 'm/d/Y @ g:i A (e)';
        $dates['res_popup'] = 'D, n/d g:i A';
        $dates['res_popup_time'] = 'g:i A';
        $dates['short_reservation_date'] = 'n/j/y g:i A';
        $dates['dashboard'] = 'D, n/d Y g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['timepicker'] = 'h:i a';
        $dates['mobile_reservation_date'] = 'n/j g:i A';
        $dates['general_date_js'] = 'mm/dd/yy';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['timepicker_js'] = 'h:i a';
        $dates['momentjs_datetime'] = 'M/D/YY h:mm A';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'M d';
        $dates['embedded_date'] = 'D d';
        $dates['embedded_time'] = 'g:i A';
        $dates['embedded_datetime'] = 'n/j g:i A';
        $dates['report_date'] = '%m/%d';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = [];

        $strings['FirstName'] = 'First Name';
        $strings['LastName'] = 'Last Name';
        $strings['Timezone'] = 'Timezone';
        $strings['Edit'] = 'Edit';
        $strings['Change'] = 'Change';
        $strings['Rename'] = 'Rename';
        $strings['Remove'] = 'Remove';
        $strings['Delete'] = 'Delete';
        $strings['Update'] = 'Update';
        $strings['Cancel'] = 'Cancel';
        $strings['Add'] = 'Add';
        $strings['Name'] = 'Name';
        $strings['Yes'] = 'Yes';
        $strings['No'] = 'No';
        $strings['FirstNameRequired'] = 'First name is required.';
        $strings['LastNameRequired'] = 'Last name is required.';
        $strings['PwMustMatch'] = 'Password confirmation must match password.';
        $strings['ValidEmailRequired'] = 'A valid email address is required.';
        $strings['UniqueEmailRequired'] = 'That email address is already registered.';
        $strings['UniqueUsernameRequired'] = 'That user name is already registered.';
        $strings['UserNameRequired'] = 'User name is required.';
        $strings['CaptchaMustMatch'] = 'Captcha is required.';
        $strings['Today'] = 'Today';
        $strings['Week'] = 'Week';
        $strings['Month'] = 'Month';
        $strings['BackToCalendar'] = 'Back to calendar';
        $strings['BeginDate'] = 'Begin';
        $strings['EndDate'] = 'End';
        $strings['Username'] = 'Username';
        $strings['Password'] = 'Password';
        $strings['PasswordConfirmation'] = 'Confirm Password';
        $strings['DefaultPage'] = 'Default Homepage';
        $strings['MyCalendar'] = 'My Calendar';
        $strings['ScheduleCalendar'] = 'Schedule Calendar';
        $strings['Registration'] = 'Registration';
        $strings['NoAnnouncements'] = 'There are no announcements';
        $strings['Announcements'] = 'Announcements';
        $strings['NoUpcomingReservations'] = 'You have no upcoming reservations';
        $strings['UpcomingReservations'] = 'Upcoming Reservations';
        $strings['AllNoUpcomingReservations'] = 'There are no upcoming reservations in next %s days';
        $strings['AllUpcomingReservations'] = 'All Upcoming Reservations';
        $strings['ShowHide'] = 'Show/Hide';
        $strings['Error'] = 'Error';
        $strings['ReturnToPreviousPage'] = 'Return to the last page that you were on';
        $strings['UnknownError'] = 'Unknown Error';
        $strings['InsufficientPermissionsError'] = 'You do not have permission to access this resource';
        $strings['MissingReservationResourceError'] = 'A resource was not selected';
        $strings['MissingReservationScheduleError'] = 'A schedule was not selected';
        $strings['DoesNotRepeat'] = 'Does Not Repeat';
        $strings['Daily'] = 'Daily';
        $strings['Weekly'] = 'Weekly';
        $strings['Monthly'] = 'Monthly';
        $strings['Yearly'] = 'Yearly';
        $strings['RepeatPrompt'] = 'Repeat';
        $strings['hours'] = 'hours';
        $strings['days'] = 'days';
        $strings['weeks'] = 'weeks';
        $strings['months'] = 'months';
        $strings['years'] = 'years';
        $strings['day'] = 'day';
        $strings['week'] = 'week';
        $strings['month'] = 'month';
        $strings['year'] = 'year';
        $strings['repeatDayOfMonth'] = 'day of month';
        $strings['repeatDayOfWeek'] = 'day of week';
        $strings['RepeatUntilPrompt'] = 'Until';
        $strings['RepeatEveryPrompt'] = 'Every';
        $strings['RepeatDaysPrompt'] = 'On';
        $strings['CreateReservationHeading'] = 'New Reservation';
        $strings['EditReservationHeading'] = 'Editing Reservation %s';
        $strings['ViewReservationHeading'] = 'Viewing Reservation %s';
        $strings['ReservationErrors'] = 'Change Reservation';
        $strings['Create'] = 'Create';
        $strings['ThisInstance'] = 'Only This Instance';
        $strings['AllInstances'] = 'All Instances';
        $strings['FutureInstances'] = 'Future Instances';
        $strings['Print'] = 'Print';
        $strings['ShowHideNavigation'] = 'Show/Hide Navigation';
        $strings['ReferenceNumber'] = 'Reference Number';
        $strings['Tomorrow'] = 'Tomorrow';
        $strings['LaterThisWeek'] = 'Later This Week';
        $strings['NextWeek'] = 'Next Week';
        $strings['SignOut'] = 'Sign Out';
        $strings['LayoutDescription'] = 'Starts on %s, showing %s days at a time';
        $strings['AllResources'] = 'All Resources';
        $strings['TakeOffline'] = 'Take Offline';
        $strings['BringOnline'] = 'Bring Online';
        $strings['AddImage'] = 'Add Image';
        $strings['NoImage'] = 'No Image Assigned';
        $strings['Move'] = 'Move';
        $strings['AppearsOn'] = 'Appears On %s';
        $strings['Location'] = 'Location';
        $strings['NoLocationLabel'] = '(no location set)';
        $strings['Contact'] = 'Contact';
        $strings['NoContactLabel'] = '(no contact information)';
        $strings['Description'] = 'Description';
        $strings['NoDescriptionLabel'] = '(no description)';
        $strings['Notes'] = 'Notes';
        $strings['NoNotesLabel'] = '(no notes)';
        $strings['NoTitleLabel'] = '(no title)';
        $strings['UsageConfiguration'] = 'Usage Configuration';
        $strings['ChangeConfiguration'] = 'Change Configuration';
        $strings['ResourceMinLength'] = 'Reservations must last at least %s';
        $strings['ResourceMinLengthNone'] = 'There is no minimum reservation duration';
        $strings['ResourceMaxLength'] = 'Reservations cannot last more than %s';
        $strings['ResourceMaxLengthNone'] = 'There is no maximum reservation duration';
        $strings['ResourceRequiresApproval'] = 'Reservations must be approved';
        $strings['ResourceRequiresApprovalNone'] = 'Reservations do not require approval';
        $strings['ResourcePermissionAutoGranted'] = 'Permission is automatically granted';
        $strings['ResourcePermissionNotAutoGranted'] = 'Permission is not automatically granted';
        $strings['ResourceMinNotice'] = 'Reservations must be made at least %s prior to start time';
        $strings['ResourceMinNoticeNone'] = 'Reservations can be made up until the current time';
        $strings['ResourceMinNoticeUpdate'] = 'Reservations must be updated at least %s prior to start time';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Reservations can be updated up until the current time';
        $strings['ResourceMinNoticeDelete'] = 'Reservations must be deleted at least %s prior to start time';
        $strings['ResourceMinNoticeNoneDelete'] = 'Reservations can be deleted up until the current time';
        $strings['ResourceMaxNotice'] = 'Reservations must not end more than %s from the current time';
        $strings['ResourceMaxNoticeNone'] = 'Reservations can end at any point in the future';
        $strings['ResourceBufferTime'] = 'There must be %s between reservations';
        $strings['ResourceBufferTimeNone'] = 'There is no buffer between reservations';
        $strings['ResourceAllowMultiDay'] = 'Reservations can be made across days';
        $strings['ResourceNotAllowMultiDay'] = 'Reservations cannot be made across days';
        $strings['ResourceCapacity'] = 'This resource has a capacity of %s people';
        $strings['ResourceCapacityNone'] = 'This resource has unlimited capacity';
        $strings['AddNewResource'] = 'Add New Resource';
        $strings['AddNewUser'] = 'Add New User';
        $strings['AddResource'] = 'Add Resource';
        $strings['Capacity'] = 'Capacity';
        $strings['Access'] = 'Access';
        $strings['Duration'] = 'Duration';
        $strings['Active'] = 'Active';
        $strings['Inactive'] = 'Inactive';
        $strings['ResetPassword'] = 'Reset Password';
        $strings['LastLogin'] = 'Last Login';
        $strings['Search'] = 'Search';
        $strings['ResourcePermissions'] = 'Resource Permissions';
        $strings['Reservations'] = 'Reservations';
        $strings['Groups'] = 'Groups';
        $strings['Users'] = 'Users';
        $strings['AllUsers'] = 'All Users';
        $strings['AllGroups'] = 'All Groups';
        $strings['AllSchedules'] = 'All Schedules';
        $strings['UsernameOrEmail'] = 'Username or Email';
        $strings['Members'] = 'Members';
        $strings['QuickSlotCreation'] = 'Create slots every %s minutes between %s and %s';
        $strings['ApplyUpdatesTo'] = 'Apply Updates To';
        $strings['CancelParticipation'] = 'Cancel Participation';
        $strings['Attending'] = 'Attending';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['QuotaEnforcement'] = 'Enforced %s %s';
        $strings['reservations'] = 'reservations';
        $strings['reservation'] = 'reservation';
        $strings['ChangeCalendar'] = 'Change Calendar';
        $strings['AddQuota'] = 'Add Quota';
        $strings['FindUser'] = 'Find User';
        $strings['Created'] = 'Created';
        $strings['LastModified'] = 'Last Modified';
        $strings['GroupName'] = 'Group Name';
        $strings['GroupMembers'] = 'Group Members';
        $strings['GroupRoles'] = 'Group Roles';
        $strings['GroupAdmin'] = 'Group Administrator';
        $strings['Actions'] = 'Actions';
        $strings['CurrentPassword'] = 'Current Password';
        $strings['NewPassword'] = 'New Password';
        $strings['InvalidPassword'] = 'Current password is incorrect';
        $strings['PasswordChangedSuccessfully'] = 'Your password has been changed successfully';
        $strings['SignedInAs'] = 'Signed in as';
        $strings['NotSignedIn'] = 'You are not signed in';
        $strings['ReservationTitle'] = 'Title of reservation';
        $strings['ReservationDescription'] = 'Description of reservation';
        $strings['ResourceList'] = 'Resources to be reserved';
        $strings['Accessories'] = 'Accessories';
        $strings['InvitationList'] = 'Invitees';
        $strings['AccessoryName'] = 'Accessory Name';
        $strings['QuantityAvailable'] = 'Quantity Available';
        $strings['Resources'] = 'Resources';
        $strings['Participants'] = 'Participants';
        $strings['User'] = 'User';
        $strings['Resource'] = 'Resource';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Approve';
        $strings['Page'] = 'Page';
        $strings['Rows'] = 'Rows';
        $strings['Unlimited'] = 'Unlimited';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email Address';
        $strings['Phone'] = 'Phone';
        $strings['Organization'] = 'Organization';
        $strings['Position'] = 'Position';
        $strings['Language'] = 'Language';
        $strings['Permissions'] = 'Permissions';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Find Group';
        $strings['Manage'] = 'Manage';
        $strings['None'] = 'None';
        $strings['AddToOutlook'] = 'Add to Calendar';
        $strings['Done'] = 'Done';
        $strings['RememberMe'] = 'Remember Me';
        $strings['FirstTimeUser?'] = 'First Time User?';
        $strings['CreateAnAccount'] = 'Create an Account';
        $strings['ViewSchedule'] = 'View Schedule';
        $strings['ForgotMyPassword'] = 'I Forgot My Password';
        $strings['YouWillBeEmailedANewPassword'] = 'You will be emailed a new randomly generated password';
        $strings['Close'] = 'Close';
        $strings['ExportToCSV'] = 'Export to CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Working...';
        $strings['Login'] = 'Login';
        $strings['AdditionalInformation'] = 'Additional Information';
        $strings['AllFieldsAreRequired'] = 'all fields are required';
        $strings['Optional'] = 'optional';
        $strings['YourProfileWasUpdated'] = 'Your profile was updated';
        $strings['YourSettingsWereUpdated'] = 'Your settings were updated';
        $strings['Register'] = 'Register';
        $strings['SecurityCode'] = 'Security Code';
        $strings['ReservationCreatedPreference'] = 'When I create a reservation or a reservation is created on my behalf';
        $strings['ReservationUpdatedPreference'] = 'When I update a reservation or a reservation is updated on my behalf';
        $strings['ReservationDeletedPreference'] = 'When I delete a reservation or a reservation is deleted on my behalf';
        $strings['ReservationApprovalPreference'] = 'When my pending reservation is approved';
        $strings['PreferenceSendEmail'] = 'Send me an email';
        $strings['PreferenceNoEmail'] = 'Do not notify me';
        $strings['ReservationCreated'] = 'Your reservation was successfully created!';
        $strings['ReservationUpdated'] = 'Your reservation was successfully updated!';
        $strings['ReservationRemoved'] = 'Your reservation was removed';
        $strings['ReservationRequiresApproval'] = 'One or more of the resources reserved require approval before usage.  This reservation will be pending until it is approved.';
        $strings['YourReferenceNumber'] = 'Your reference number is %s';
        $strings['ChangeUser'] = 'Change User';
        $strings['MoreResources'] = 'More Resources';
        $strings['ReservationLength'] = 'Reservation Length';
        $strings['ParticipantList'] = 'Participant List';
        $strings['AddParticipants'] = 'Add Participants';
        $strings['InviteOthers'] = 'Invite Others';
        $strings['AddResources'] = 'Add Resources';
        $strings['AddAccessories'] = 'Add Accessories';
        $strings['Accessory'] = 'Accessory';
        $strings['QuantityRequested'] = 'Quantity Requested';
        $strings['CreatingReservation'] = 'Creating Reservation';
        $strings['UpdatingReservation'] = 'Updating Reservation';
        $strings['DeleteWarning'] = 'This action is permanent and cannot be undone!';
        $strings['DeleteAccessoryWarning'] = 'Deleting this accessory will remove it from all reservations.';
        $strings['AddAccessory'] = 'Add Accessory';
        $strings['AddBlackout'] = 'Add Blackout';
        $strings['AllResourcesOn'] = 'All Resources On';
        $strings['Reason'] = 'Reason';
        $strings['BlackoutShowMe'] = 'Show me conflicting reservations';
        $strings['BlackoutDeleteConflicts'] = 'Delete conflicting reservations';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Between';
        $strings['CreatedBy'] = 'Created By';
        $strings['BlackoutCreated'] = 'Blackout Created';
        $strings['BlackoutNotCreated'] = 'Blackout could not be created';
        $strings['BlackoutUpdated'] = 'Blackout Updated';
        $strings['BlackoutNotUpdated'] = 'Blackout could not be updated';
        $strings['BlackoutConflicts'] = 'There are conflicting blackout times';
        $strings['ReservationConflicts'] = 'There are conflicting reservations times';
        $strings['UsersInGroup'] = 'Users in this group';
        $strings['Browse'] = 'Browse';
        $strings['DeleteGroupWarning'] = 'Deleting this group will remove all associated resource permissions.  Users in this group may lose access to resources.';
        $strings['WhatRolesApplyToThisGroup'] = 'Which roles apply to this group?';
        $strings['WhoCanManageThisGroup'] = 'Who can manage this group?';
        $strings['WhoCanManageThisSchedule'] = 'Who can manage this schedule?';
        $strings['AllQuotas'] = 'All Quotas';
        $strings['QuotaReminder'] = 'Remember: Quotas are enforced based on the schedule\'s timezone.';
        $strings['AllReservations'] = 'All Reservations';
        $strings['PendingReservations'] = 'Pending Reservations';
        $strings['Approving'] = 'Approving';
        $strings['MoveToSchedule'] = 'Move to schedule';
        $strings['DeleteResourceWarning'] = 'Deleting this resource will delete all associated data, including';
        $strings['DeleteResourceWarningReservations'] = 'all past, current and future reservations associated with it';
        $strings['DeleteResourceWarningPermissions'] = 'all permission assignments';
        $strings['DeleteResourceWarningReassign'] = 'Please reassign anything that you do not want to be deleted before proceeding';
        $strings['ScheduleLayout'] = 'Layout (all times %s)';
        $strings['ReservableTimeSlots'] = 'Reservable Time Slots';
        $strings['BlockedTimeSlots'] = 'Blocked Time Slots';
        $strings['ThisIsTheDefaultSchedule'] = 'This is the default schedule';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Default schedule cannot be deleted';
        $strings['MakeDefault'] = 'Make Default';
        $strings['BringDown'] = 'Bring Down';
        $strings['ChangeLayout'] = 'Change Layout';
        $strings['AddSchedule'] = 'Add Schedule';
        $strings['StartsOn'] = 'Starts On';
        $strings['NumberOfDaysVisible'] = 'Number of Days Visible';
        $strings['UseSameLayoutAs'] = 'Use Same Layout As';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Optional Label';
        $strings['LayoutInstructions'] = 'Enter one slot per line.  Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['AddUser'] = 'Add User';
        $strings['UserPermissionInfo'] = 'Actual access to resource may be different depending on user role, group permissions, or external permission settings';
        $strings['DeleteUserWarning'] = 'Deleting this user will remove all of their current, future, and historical reservations.';
        $strings['AddAnnouncement'] = 'Add Announcement';
        $strings['Announcement'] = 'Announcement';
        $strings['Priority'] = 'Priority';
        $strings['Reservable'] = 'Open';
        $strings['Unreservable'] = 'Blocked';
        $strings['Reserved'] = 'Reserved';
        $strings['MyReservation'] = 'My Reservation';
        $strings['Pending'] = 'Pending';
        $strings['Past'] = 'Past';
        $strings['Restricted'] = 'Restricted';
        $strings['ViewAll'] = 'View All';
        $strings['MoveResourcesAndReservations'] = 'Move resources and reservations to';
        $strings['TurnOffSubscription'] = 'Hide from public';
        $strings['TurnOnSubscription'] = 'Show to public (RSS, iCalendar, Tablet, Monitor)';
        $strings['SubscribeToCalendar'] = 'Subscribe to this Calendar';
        $strings['SubscriptionsAreDisabled'] = 'The administrator has disabled calendar subscriptions';
        $strings['NoResourceAdministratorLabel'] = '(No Resource Administrator)';
        $strings['WhoCanManageThisResource'] = 'Who Can Manage This Resource?';
        $strings['ResourceAdministrator'] = 'Resource Administrator';
        $strings['Private'] = 'Private';
        $strings['Accept'] = 'Accept';
        $strings['Decline'] = 'Decline';
        $strings['ShowFullWeek'] = 'Show Full Week';
        $strings['CustomAttributes'] = 'Custom Attributes';
        $strings['AddAttribute'] = 'Add an Attribute';
        $strings['EditAttribute'] = 'Update an Attribute';
        $strings['DisplayLabel'] = 'Display Label';
        $strings['Type'] = 'Type';
        $strings['Required'] = 'Required';
        $strings['ValidationExpression'] = 'Validation Expression';
        $strings['PossibleValues'] = 'Possible Values';
        $strings['SingleLineTextbox'] = 'Single Line Textbox';
        $strings['MultiLineTextbox'] = 'Multiple Line Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Select List';
        $strings['CommaSeparated'] = 'comma separated';
        $strings['Category'] = 'Category';
        $strings['CategoryReservation'] = 'Reservation';
        $strings['CategoryGroup'] = 'Group';
        $strings['SortOrder'] = 'Sort Order';
        $strings['Title'] = 'Title';
        $strings['AdditionalAttributes'] = 'Additional Attributes';
        $strings['True'] = 'True';
        $strings['False'] = 'False';
        $strings['ForgotPasswordEmailSent'] = 'An email has been sent to the address provided with instructions for resetting your password';
        $strings['ActivationEmailSent'] = 'You will receive an activation email soon.';
        $strings['AccountActivationError'] = 'Sorry, we could not activate your account.';
        $strings['Attachments'] = 'Attachments';
        $strings['AttachFile'] = 'Attach File';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'No Schedule Administrator';
        $strings['ScheduleAdministrator'] = 'Schedule Administrator';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Quantity Reserved';
        $strings['AllAccessories'] = 'All Accessories';
        $strings['GetReport'] = 'Get Report';
        $strings['NoResultsFound'] = 'No matching results found';
        $strings['SaveThisReport'] = 'Save This Report';
        $strings['ReportSaved'] = 'Report Saved!';
        $strings['EmailReport'] = 'Email Report';
        $strings['ReportSent'] = 'Report Sent!';
        $strings['RunReport'] = 'Run Report';
        $strings['NoSavedReports'] = 'You have no saved reports.';
        $strings['CurrentWeek'] = 'Current Week';
        $strings['CurrentMonth'] = 'Current Month';
        $strings['AllTime'] = 'All Time';
        $strings['FilterBy'] = 'Filter By';
        $strings['Select'] = 'Select';
        $strings['List'] = 'List';
        $strings['TotalTime'] = 'Time';
        $strings['Count'] = 'Count';
        $strings['Usage'] = 'Usage';
        $strings['AggregateBy'] = 'Aggregate By';
        $strings['Range'] = 'Range';
        $strings['Choose'] = 'Choose';
        $strings['All'] = 'All';
        $strings['ViewAsChart'] = 'View As Chart';
        $strings['ReservedResources'] = 'Reserved Resources';
        $strings['ReservedAccessories'] = 'Reserved Accessories';
        $strings['ResourceUsageTimeBooked'] = 'Resource Usage - Time Booked';
        $strings['ResourceUsageReservationCount'] = 'Resource Usage - Reservation Count';
        $strings['Top20UsersTimeBooked'] = 'Top 20 Users - Time Booked';
        $strings['Top20UsersReservationCount'] = 'Top 20 Users - Reservation Count';
        $strings['ConfigurationUpdated'] = 'The configuration file was updated';
        $strings['ConfigurationUiNotEnabled'] = 'This page cannot be accessed because $conf[\'settings\'][\'pages\'][\'enable.configuration\'] is set to false or missing.';
        $strings['ConfigurationFileNotWritable'] = 'The config file is not writable. Please check the permissions of this file and try again.';
        $strings['ConfigurationUpdateHelp'] = 'Refer to the Configuration section of the <a target=_blank href=%s class=link-primary>Help File</a> for documentation on these settings.';
        $strings['GeneralConfigSettings'] = 'settings';
        $strings['UseSameLayoutForAllDays'] = 'Use the same layout for all days';
        $strings['LayoutVariesByDay'] = 'Layout varies by day';
        $strings['ManageReminders'] = 'Reminders';
        $strings['ReminderUser'] = 'User ID';
        $strings['ReminderMessage'] = 'Message';
        $strings['ReminderAddress'] = 'Addresses';
        $strings['ReminderSendtime'] = 'Time To Send';
        $strings['ReminderRefNumber'] = 'Reservation Reference Number';
        $strings['ReminderSendtimeDate'] = 'Date of Reminder';
        $strings['ReminderSendtimeTime'] = 'Time of Reminder (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Add Reminder';
        $strings['DeleteReminderWarning'] = 'Are you sure you want to delete this?';
        $strings['NoReminders'] = 'You have no upcoming reminders.';
        $strings['Reminders'] = 'Reminders';
        $strings['SendReminder'] = 'Send Reminder';
        $strings['minutes'] = 'minutes';
        $strings['hours'] = 'hours';
        $strings['days'] = 'days';
        $strings['ReminderBeforeStart'] = 'before the start time';
        $strings['ReminderBeforeEnd'] = 'before the end time';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Your changes have been saved. Refresh the page for changes to take effect.';
        $strings['MakeDefaultSchedule'] = 'Make this my default schedule';
        $strings['DefaultScheduleSet'] = 'This is now your default schedule';
        $strings['FlipSchedule'] = 'Flip the schedule layout';
        $strings['Next'] = 'Next';
        $strings['Success'] = 'Success';
        $strings['Participant'] = 'Participant';
        $strings['ResourceFilter'] = 'Resource Filter';
        $strings['ResourceGroups'] = 'Resource Groups';
        $strings['AddNewGroup'] = 'Add a new group';
        $strings['Quit'] = 'Quit';
        $strings['AddGroup'] = 'Add Group';
        $strings['StandardScheduleDisplay'] = 'Use the standard schedule display';
        $strings['TallScheduleDisplay'] = 'Use the tall schedule display';
        $strings['WideScheduleDisplay'] = 'Use the wide schedule display';
        $strings['CondensedWeekScheduleDisplay'] = 'Use condensed week schedule display';
        $strings['ResourceGroupHelp1'] = 'Drag and drop resource groups to reorganize.';
        $strings['ResourceGroupHelp2'] = 'Right click a resource group name for additional actions.';
        $strings['ResourceGroupHelp3'] = 'Drag and drop resources to add them to groups.';
        $strings['ResourceGroupWarning'] = 'If using resource groups, each resource must be assigned to at least one group. Unassigned resources will not be able to be reserved.';
        $strings['ResourceType'] = 'Resource Type';
        $strings['AppliesTo'] = 'Applies To';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Add Resource Type';
        $strings['NoResourceTypeLabel'] = '(no resource type set)';
        $strings['ClearFilter'] = 'Clear Filter';
        $strings['MinimumCapacity'] = 'Minimum Capacity';
        $strings['Color'] = 'Color';
        $strings['Available'] = 'Available';
        $strings['Unavailable'] = 'Unavailable';
        $strings['Hidden'] = 'Hidden';
        $strings['ResourceStatus'] = 'Resource Status';
        $strings['CurrentStatus'] = 'Current Status';
        $strings['AllReservationResources'] = 'All Reservation Resources';
        $strings['File'] = 'File';
        $strings['BulkResourceUpdate'] = 'Bulk Resource Update';
        $strings['Unchanged'] = 'Unchanged';
        $strings['Common'] = 'Common';
        $strings['AdminOnly'] = 'Is Admin Only';
        $strings['AdvancedFilter'] = 'Advanced Filter';
        $strings['MinimumQuantity'] = 'Minimum Quantity';
        $strings['MaximumQuantity'] = 'Maximum Quantity';
        $strings['ChangeLanguage'] = 'Change Language';
        $strings['AddRule'] = 'Add Rule';
        $strings['Attribute'] = 'Attribute';
        $strings['RequiredValue'] = 'Required Value';
        $strings['ReservationCustomRuleAdd'] = 'Use this color when the reservation attribute is set to the following value';
        $strings['AddReservationColorRule'] = 'Add Reservation Color Rule';
        $strings['LimitAttributeScope'] = 'Collect In Specific Cases';
        $strings['CollectFor'] = 'Collect For';
        $strings['SignIn'] = 'Sign In';
        $strings['SignInWith'] = 'Sign in with';
        $strings['AllParticipants'] = 'All Participants';
        $strings['RegisterANewAccount'] = 'Register a New Account';
        $strings['Dates'] = 'Dates';
        $strings['More'] = 'More';
        $strings['ResourceAvailability'] = 'Resource Availability';
        $strings['UnavailableAllDay'] = 'Unavailable All Day';
        $strings['AvailableUntil'] = 'Available Until';
        $strings['AvailableBeginningAt'] = 'Available Beginning At';
        $strings['AvailableAt'] = 'Available At';
        $strings['AllResourceTypes'] = 'All Resource Types';
        $strings['AllResourceStatuses'] = 'All Resource Statuses';
        $strings['AllowParticipantsToJoin'] = 'Allow Participants To Join';
        $strings['Join'] = 'Join';
        $strings['YouAreAParticipant'] = 'You are a participant of this reservation';
        $strings['YouAreInvited'] = 'You are invited to this reservation';
        $strings['YouCanJoinThisReservation'] = 'You can join this reservation';
        $strings['Import'] = 'Import';
        $strings['GetTemplate'] = 'Get Template';
        $strings['UserImportInstructions'] = '<ul><li>File must be in CSV format.</li><li>Username and email are required fields.</li><li>Attribute validity will not be enforced.</li><li>Leaving other fields blank will set default values and \'password\' as the user\'s password.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['RowsImported'] = 'Rows Imported';
        $strings['RowsSkipped'] = 'Rows Skipped';
        $strings['Columns'] = 'Columns';
        $strings['Reserve'] = 'Reserve';
        $strings['AllDay'] = 'All Day';
        $strings['Everyday'] = 'Everyday';
        $strings['IncludingCompletedReservations'] = 'Including Completed Reservations';
        $strings['NotCountingCompletedReservations'] = 'Not Including Completed Reservations';
        $strings['RetrySkipConflicts'] = 'Skip conflicting reservations';
        $strings['Retry'] = 'Retry';
        $strings['RemoveExistingPermissions'] = 'Remove existing permissions?';
        $strings['Continue'] = 'Continue';
        $strings['WeNeedYourEmailAddress'] = 'We need your email address to reserve';
        $strings['ResourceColor'] = 'Resource Color';
        $strings['DateTime'] = 'Date Time';
        $strings['AutoReleaseNotification'] = 'Automatically released if not checked in within %s minutes';
        $strings['RequiresCheckInNotification'] = 'Requires check in/out';
        $strings['NoCheckInRequiredNotification'] = 'Does not require check in/out';
        $strings['RequiresApproval'] = 'Requires Approval';
        $strings['CheckingIn'] = 'Checking In';
        $strings['CheckingOut'] = 'Checking Out';
        $strings['CheckIn'] = 'Check In';
        $strings['CheckOut'] = 'Check Out';
        $strings['ReleasedIn'] = 'Released in';
        $strings['CheckedInSuccess'] = 'You are checked in';
        $strings['CheckedOutSuccess'] = 'You are checked out';
        $strings['CheckInFailed'] = 'You could not be checked in';
        $strings['CheckOutFailed'] = 'You could not be checked out';
        $strings['CheckInTime'] = 'Check In Time';
        $strings['CheckOutTime'] = 'Check Out Time';
        $strings['OriginalEndDate'] = 'Original End';
        $strings['SpecificDates'] = 'Show Specific Dates';
        $strings['Users'] = 'Users';
        $strings['Guest'] = 'Guest';
        $strings['ResourceDisplayPrompt'] = 'Resource to Display';
        $strings['Credits'] = 'Credits';
        $strings['AvailableCredits'] = 'Available Credits';
        $strings['CreditUsagePerSlot'] = 'Requires %s credits per slot (off peak)';
        $strings['PeakCreditUsagePerSlot'] = 'Requires %s credits per slot (peak)';
        $strings['CreditsRule'] = 'You do not have enough credits. Credits required: %s. Credits in account: %s';
        $strings['PeakTimes'] = 'Peak Times';
        $strings['AllYear'] = 'All Year';
        $strings['MoreOptions'] = 'More Options';
        $strings['SendAsEmail'] = 'Send As Email';
        $strings['UsersInGroups'] = 'Users In Groups';
        $strings['UsersWithAccessToResources'] = 'Users With Access To Resources';
        $strings['AnnouncementSubject'] = 'A new announcement was posted by %s';
        $strings['AnnouncementEmailNotice'] = 'users will be sent this announcement as an email';
        $strings['Day'] = 'Day';
        $strings['NotifyWhenAvailable'] = 'Notify Me When Available';
        $strings['AddingToWaitlist'] = 'Adding you to the wait list';
        $strings['WaitlistRequestAdded'] = 'You will be notified if this time becomes available';
        $strings['PrintQRCode'] = 'Print QR Code';
        $strings['FindATime'] = 'Find A Time';
        $strings['AnyResource'] = 'Any Resource';
        $strings['ThisWeek'] = 'This Week';
        $strings['Hours'] = 'Hours';
        $strings['Minutes'] = 'Minutes';
        $strings['ImportICS'] = 'Import From ICS';
        $strings['ImportQuartzy'] = 'Import From Quartzy';
        $strings['OnlyIcs'] = 'Only *.ics files can be uploaded.';
        $strings['IcsLocationsAsResources'] = 'Locations will be imported as resources.';
        $strings['IcsMissingOrganizer'] = 'Any event missing an organizer will have the owner set to the current user.';
        $strings['IcsWarning'] = 'Reservation rules will not be enforced - conflicts, duplicates, etc are possible.';
        $strings['BlackoutAroundConflicts'] = 'Blackout around conflicting reservations';
        $strings['DuplicateReservation'] = 'Duplicate';
        $strings['UnavailableNow'] = 'Unavailable Now';
        $strings['ReserveLater'] = 'Reserve Later';
        $strings['CollectedFor'] = 'Collected For';
        $strings['IncludeDeleted'] = 'Include Deleted Reservations';
        $strings['Deleted'] = 'Deleted';
        $strings['Back'] = 'Back';
        $strings['Forward'] = 'Forward';
        $strings['DateRange'] = 'Date Range';
        $strings['Copy'] = 'Copy';
        $strings['Detect'] = 'Detect';
        $strings['Autofill'] = 'Autofill';
        $strings['NameOrEmail'] = 'name or email';
        $strings['ImportResources'] = 'Import Resources';
        $strings['ExportResources'] = 'Export Resources';
        $strings['ResourceImportInstructions'] = '<ul><li>File must be in CSV format with UTF-8 encoding.</li><li>Name is required field. Leaving other fields blank will set default values.</li><li>Status options are \'Available\', \'Unavailable\' and \'Hidden\'.</li><li>Color should be the hex value. ex) #ffffff.</li><li>Auto assign and approval columns can be true or false.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource groups.</li><li>Durations can be specified in the format #d#h#m or HH:mm (1d3h30m or 27:30 for 1 day, 3 hours, 30 minutes)</li><li>Use the supplied template as an example.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>File must be in CSV format with UTF-8 encoding.</li><li>Email, resource names, begin, and end are required fields.</li><li>Begin and end require full date time. Recommended format is YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Rules, conflicts, and valid time slots will not be checked.</li><li>Notifications will not be sent.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource names.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Autorelease Minutes';
        $strings['CreditsPeak'] = 'Credits (peak)';
        $strings['CreditsOffPeak'] = 'Credits (off peak)';
        $strings['ResourceMinLengthCsv'] = 'Reservation Minimum Length';
        $strings['ResourceMaxLengthCsv'] = 'Reservation Maximum Length';
        $strings['ResourceBufferTimeCsv'] = 'Buffer Time';
        $strings['ResourceMinNoticeAddCsv'] = 'Reservation Add Minimum Notice';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Reservation Update Minimum Notice';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Reservation Delete Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';
        $strings['Export'] = 'Export';
        $strings['DeleteMultipleUserWarning'] = 'Deleting these users will remove all of their current, future, and historical reservations. No emails will be sent.';
        $strings['DeleteMultipleReservationsWarning'] = 'No emails will be sent.';
        $strings['ErrorMovingReservation'] = 'Error Moving Reservation';
        $strings['SelectUser'] = 'Select User';
        $strings['InviteUsers'] = 'Invite Users';
        $strings['InviteUsersLabel'] = 'Enter the email addresses of the people to invite';
        $strings['ApplyToCurrentUsers'] = 'Apply to current users';
        $strings['ReasonText'] = 'Reason text';
        $strings['NoAvailableMatchingTimes'] = 'There are no available times that match your search';
        $strings['Schedules'] = 'Schedules';
        $strings['NotifyUser'] = 'Notify User';
        $strings['UpdateUsersOnImport'] = 'Update existing user if email already exists';
        $strings['UpdateResourcesOnImport'] = 'Update existing resources if name already exists';
        $strings['Reject'] = 'Reject';
        $strings['CheckingAvailability'] = 'Checking availability';
        $strings['CreditPurchaseNotEnabled'] = 'You have not enabled the ability to purchase credits';
        $strings['CreditsEachCost1'] = 'Each';
        $strings['CreditsEachCost2'] = 'credit(s) cost';
        $strings['CreditsCount'] = 'Number of credits';
        $strings['CreditsCost'] = 'Cost';
        $strings['Currency'] = 'Currency';
        $strings['PayPalClientId'] = 'Client ID';
        $strings['PayPalSecret'] = 'Secret';
        $strings['PayPalEnvironment'] = 'Environment';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Live';
        $strings['StripePublishableKey'] = 'Publishable key';
        $strings['StripeSecretKey'] = 'Secret key';
        $strings['CreditsUpdated'] = 'Credit cost has been updated';
        $strings['GatewaysUpdated'] = 'Payment gateways have been updated';
        $strings['PurchaseSummary'] = 'Purchase Summary';
        $strings['EachCreditCosts'] = 'Each credit costs';
        $strings['Checkout'] = 'Checkout';
        $strings['Quantity'] = 'Quantity';
        $strings['CreditPurchase'] = 'Credit Purchase';
        $strings['EmptyCart'] = 'Your cart is empty.';
        $strings['BuyCredits'] = 'Buy Credits';
        $strings['CreditsPurchased'] = 'credits purchased.';
        $strings['ViewYourCredits'] = 'View your credits';
        $strings['TryAgain'] = 'Try Again';
        $strings['PurchaseFailed'] = 'We had trouble processing your payment.';
        $strings['NoteCreditsPurchased'] = 'Credits purchased';
        $strings['CreditsUpdatedLog'] = 'Credits updated by %s';
        $strings['ReservationCreatedLog'] = 'Reservation created. Reference number %s';
        $strings['ReservationUpdatedLog'] = 'Reservation updated. Reference number %s';
        $strings['ReservationDeletedLog'] = 'Reservation deleted. Reference number %s';
        $strings['BuyMoreCredits'] = 'Buy More Credits';
        $strings['Transactions'] = 'Transactions';
        $strings['Cost'] = 'Cost';
        $strings['PaymentGateways'] = 'Payment Gateways';
        $strings['CreditHistory'] = 'Credit History';
        $strings['TransactionHistory'] = 'Transaction History';
        $strings['Date'] = 'Date';
        $strings['Note'] = 'Note';
        $strings['CreditsBefore'] = 'Credits Before';
        $strings['CreditsAfter'] = 'Credits After';
        $strings['TransactionFee'] = 'Transaction Fee';
        $strings['InvoiceNumber'] = 'Invoice Number';
        $strings['TransactionId'] = 'Transaction ID';
        $strings['Gateway'] = 'Gateway';
        $strings['GatewayTransactionDate'] = 'Gateway Transaction Date';
        $strings['Refund'] = 'Refund';
        $strings['IssueRefund'] = 'Issue Refund';
        $strings['RefundIssued'] = 'Refund Issued Successfully';
        $strings['RefundAmount'] = 'Refund Amount';
        $strings['AmountRefunded'] = 'Refunded';
        $strings['FullyRefunded'] = 'Fully Refunded';
        $strings['YourCredits'] = 'Your Credits';
        $strings['PayWithCard'] = 'Pay with Card';
        $strings['or'] = 'or';
        $strings['CreditsRequired'] = 'Credits Required';
        $strings['AddToGoogleCalendar'] = 'Add to Google';
        $strings['Image'] = 'Image';
        $strings['ChooseOrDropFile'] = 'Choose a file or drag it here';
        $strings['SlackBookResource'] = 'Book %s now';
        $strings['SlackBookNow'] = 'Book Now';
        $strings['SlackNotFound'] = 'We could not find a resource with that name. Book Now to start a new reservation.';
        $strings['AutomaticallyAddToGroup'] = 'Automatically add new users to this group';
        $strings['GroupAutomaticallyAdd'] = 'Auto Add';
        $strings['TermsOfService'] = 'Terms of Service';
        $strings['EnterTermsManually'] = 'Enter Terms Manually';
        $strings['LinkToTerms'] = 'Link to Terms';
        $strings['UploadTerms'] = 'Upload Terms';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Require Terms of Service Acknowledgement';
        $strings['UponReservation'] = 'Upon Reservation';
        $strings['UponRegistration'] = 'Upon Registration';
        $strings['ViewTerms'] = 'View Terms of Service';
        $strings['IAccept'] = 'I Accept';
        $strings['TheTermsOfService'] = 'the Terms of Service';
        $strings['DisplayPage'] = 'Display Page';
        $strings['AvailableAllYear'] = 'All Year';
        $strings['Availability'] = 'Availability';
        $strings['AvailableBetween'] = 'Available Between';
        $strings['ConcurrentYes'] = 'Resources can be booked by more than one person at a time';
        $strings['ConcurrentNo'] = 'Resources cannot be booked by more than one person at a time';
        $strings['ScheduleAvailabilityEarly'] = ' This schedule is not yet available. It is available';
        $strings['ScheduleAvailabilityLate'] = 'This schedule is no longer available. It was available';
        $strings['ResourceImages'] = 'Resource Images';
        $strings['FullAccess'] = 'Full Access';
        $strings['ViewOnly'] = 'View Only';
        $strings['Purge'] = 'Purge';
        $strings['UsersWillBeDeleted'] = 'users will be deleted';
        $strings['BlackoutsWillBeDeleted'] = 'blackout times will be deleted';
        $strings['ReservationsWillBePurged'] = 'reservations will be purged';
        $strings['ReservationsWillBeDeleted'] = 'reservations will be deleted';
        $strings['PermanentlyDeleteUsers'] = 'Permanently delete users who have not logged in since';
        $strings['DeleteBlackoutsBefore'] = 'Delete blackout times before';
        $strings['DeletedReservations'] = 'Deleted Reservations';
        $strings['DeleteReservationsBefore'] = 'Delete reservations before';
        $strings['SwitchToACustomLayout'] = 'Switch to a custom layout';
        $strings['SwitchToAStandardLayout'] = 'Switch to a standard layout';
        $strings['ThisScheduleUsesACustomLayout'] = 'This schedule uses a custom layout';
        $strings['ThisScheduleUsesAStandardLayout'] = 'This schedule uses a standard layout';
        $strings['SwitchLayoutWarning'] = 'Are you sure that you want to change the layout type? This will remove all existing slots.';
        $strings['DeleteThisTimeSlot'] = 'Delete this time slot?';
        $strings['Refresh'] = 'Refresh';
        $strings['ViewReservation'] = 'View Reservation';
        $strings['PublicId'] = 'Public Id';
        $strings['Public'] = 'Public';
        $strings['AtomFeedTitle'] = '%s Reservations';
        $strings['DefaultStyle'] = 'Default Style';
        $strings['Standard'] = 'Standard';
        $strings['Wide'] = 'Wide';
        $strings['Tall'] = 'Tall';
        $strings['EmailTemplate'] = 'Email Template';
        $strings['SelectEmailTemplate'] = 'Select Email Template';
        $strings['ReloadOriginalContents'] = 'Reload Original Contents';
        $strings['UpdateEmailTemplateSuccess'] = 'Updated email template';
        $strings['UpdateEmailTemplateFailure'] = 'Could not update email template. Check to make sure the directory is writable.';
        $strings['BulkResourceDelete'] = 'Bulk Resource Delete';
        $strings['NewVersion'] = 'New version!';
        $strings['WhatsNew'] = 'Whats New?';
        $strings['OnlyViewedCalendar'] = 'This schedule can only be viewed from the calendar view';
        $strings['Grid'] = 'Grid';
        $strings['List'] = 'List';
        $strings['NoReservationsFound'] = 'No Reservations Found';
        $strings['EmailReservation'] = 'Email Reservation';
        $strings['AdHocMeeting'] = 'Ad hoc Meeting';
        $strings['NextReservation'] = 'Next Reservation';
        $strings['MissedCheckin'] = 'Missed Checkin';
        $strings['MissedCheckout'] = 'Missed Checkout';
        $strings['Utilization'] = 'Utilization';
        $strings['SpecificTime'] = 'Specific Time';
        $strings['ReservationSeriesEndingPreference'] = 'When my recurring reservation series is ending';
        $strings['NotAttending'] = 'Not Attending';
        $strings['ViewAvailability'] = 'View Availability';
        $strings['ReservationDetails'] = 'Reservation Details';
        $strings['StartTime'] = 'Start Time';
        $strings['EndTime'] = 'End Time';
        $strings['New'] = 'New';
        $strings['Updated'] = 'Updated';
        $strings['Custom'] = 'Custom';
        $strings['AddDate'] = 'Add Date';
        $strings['RepeatOn'] = 'Repeat On';
        $strings['ScheduleConcurrentMaximum'] = 'A total of <b>%s</b> resources may be reserved concurrently';
        $strings['ScheduleConcurrentMaximumNone'] = 'There is no limit to the number of concurrent reserved resources';
        $strings['ScheduleMaximumConcurrent'] = 'Maximum number of resources reserved concurrently';
        $strings['ScheduleMaximumConcurrentNote'] = 'When set, the total number of resources that can be reserved concurrently for this schedule will be limited.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Each reservation is limited to a maximum of <b>%s</b> resources';
        $strings['ScheduleResourcesPerReservationNone'] = 'There is no limit to the number of resources per reservation';
        $strings['ScheduleResourcesPerReservation'] = 'Maximum number of resources per reservation';
        $strings['ResourceConcurrentReservations'] = 'Allow %s concurrent reservations';
        $strings['ResourceConcurrentReservationsNone'] = 'Do not allow concurrent reservations';
        $strings['AllowConcurrentReservations'] = 'Allow concurrent reservations';
        $strings['ResourceDisplayInstructions'] = 'No resource has been selected. You can find the URL to display a resource in Application Management, Resources. The resource must be publicly accessible.';
        $strings['Owner'] = 'Owner';
        $strings['MaximumConcurrentReservations'] = 'Maximum Concurrent Reservations';
        $strings['NotifyUsers'] = 'Notify Users';
        $strings['Message'] = 'Message';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Anyone with a reservation in the next';
        $strings['ChangeResourceStatus'] = 'Change Resource Status';
        $strings['UpdateGroupsOnImport'] = 'Update existing group if name matches';
        $strings['GroupsImportInstructions'] = '<ul><li>File must be in CSV format.</li><li>Name is required.</li><li>Member lists should be comma separated lists of emails.</li><li>Empty member lists when updating groups will leave members unchanged.</li><li>Permissions lists should be comma separated lists of resource names.</li><li>Empty permissions lists when updating groups will leave permissions unchanged.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['PhoneRequired'] = 'Phone is required';
        $strings['OrganizationRequired'] = 'Organization is required';
        $strings['PositionRequired'] = 'Position is required';
        $strings['GroupMembership'] = 'Group Membership';
        $strings['AvailableGroups'] = 'Available Groups';
        $strings['CheckingAvailabilityError'] = 'Cannot get resource availability - too many resources';
        $strings['ScanToSchedule'] = 'Scan to schedule';
        $strings['MaintenanceNotice'] = 'Currently performing maintenance. We will be back soon.';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Install LibreBooking';
        $strings['IncorrectInstallPassword'] = 'Sorry, that password was incorrect.';
        $strings['SetInstallPassword'] = 'You must set an install password before the installation can be run.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'LibreBooking is up to date. There is no upgrade needed.';
        $strings['ProvideInstallPassword'] = 'Please provide your installation password.';
        $strings['InstallPasswordLocation'] = 'This can be found at %s in %s.';
        $strings['VerifyInstallSettings'] = 'Verify the following default settings before continuing. Or you can change them in %s.';
        $strings['DatabaseName'] = 'Database Name';
        $strings['DatabaseUser'] = 'Database User';
        $strings['DatabaseHost'] = 'Database Host';
        $strings['DatabaseCredentials'] = 'You must provide credentials of a MySQL user who has privileges to create databases. If you do not know, contact your database admin. In many cases, root will work.';
        $strings['MySQLUser'] = 'MySQL User';
        $strings['InstallOptionsWarning'] = 'The following options will probably not work in a hosted environment. If you are installing in a hosted environment, use the MySQL wizard tools to complete these steps.';
        $strings['CreateDatabase'] = 'Create the database';
        $strings['CreateDatabaseUser'] = 'Create the database user';
        $strings['PopulateExampleData'] = 'Import sample data. Creates admin account: admin/password and user account: user/password';
        $strings['DataWipeWarning'] = 'Warning: This will delete any existing data';
        $strings['RunInstallation'] = 'Run Installation';
        $strings['UpgradeNotice'] = 'You are upgrading from version <b>%s</b> to version <b>%s</b>';
        $strings['RunUpgrade'] = 'Run Upgrade';
        $strings['Executing'] = 'Executing';
        $strings['StatementFailed'] = 'Failed. Details:';
        $strings['SQLStatement'] = 'SQL Statement:';
        $strings['ErrorCode'] = 'Error Code:';
        $strings['ErrorText'] = 'Error Text:';
        $strings['InstallationSuccess'] = 'Installation completed successfully!';
        $strings['RegisterAdminUser'] = 'Register your admin user. This is required if you did not import the sample data. Ensure that $conf[\'settings\'][\'allow.self.registration\'] = \'true\' in your %s file.';
        $strings['LoginWithSampleAccounts'] = 'If you imported the sample data, you can log in with admin/password for admin user or user/password for basic user.';
        $strings['InstalledVersion'] = 'You are now running version %s of LibreBooking';
        $strings['InstallUpgradeConfig'] = 'It is recommended to upgrade your config file';
        $strings['InstallationFailure'] = 'There were problems with the installation.  Please correct them and retry the installation.';
        $strings['ConfigureApplication'] = 'Configure LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Your config file is now up to date!';
        $strings['ConfigUpdateFailure'] = 'We could not automatically update your config file. Please overwrite the contents of config.php with the following:';
        $strings['ScriptUrlWarning'] = 'Your <em>script.url</em> setting may not be correct. It is currently <strong>%s</strong>, we think it should be <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'We could not match your username or password';
        $strings['ReservationFailed'] = 'Your reservation could not be made';
        $strings['MinNoticeError'] = 'This reservation requires advance notice. The earliest date and time that can be reserved is %s.';
        $strings['MinNoticeErrorUpdate'] = 'Changing this reservation requires advance notice. Reservations before %s are not allowed to be changed.';
        $strings['MinNoticeErrorDelete'] = 'Deleting this reservation requires advance notice. Reservations before %s are not allowed to be deleted.';
        $strings['MaxNoticeError'] = 'This reservation cannot be made this far in the future. The latest date and time that can be reserved is %s.';
        $strings['MinDurationError'] = 'This reservation must last at least %s.';
        $strings['MaxDurationError'] = 'This reservation cannot last longer than %s.';
        $strings['ConflictingAccessoryDates'] = 'There are not enough of the following accessories:';
        $strings['NoResourcePermission'] = 'You do not have permission to access one or more of the requested resources.';
        $strings['ConflictingReservationDates'] = 'There are conflicting reservations on the following dates:';
        $strings['InstancesOverlapRule'] = 'Some instances of the reservation series overlap:';
        $strings['StartDateBeforeEndDateRule'] = 'The start date and time must be before the end date and time.';
        $strings['StartIsInPast'] = 'The start date and time cannot be in the past.';
        $strings['EmailDisabled'] = 'The administrator has disabled email notifications.';
        $strings['ValidLayoutRequired'] = 'Slots must be provided for all 24 hours of the day beginning and ending at 00:00.';
        $strings['CustomAttributeErrors'] = 'There are problems with the additional attributes you provided:';
        $strings['CustomAttributeRequired'] = '%s is a required field.';
        $strings['CustomAttributeInvalid'] = 'The value provided for %s is invalid.';
        $strings['AttachmentLoadingError'] = 'Sorry, there was a problem loading the requested file.';
        $strings['InvalidAttachmentExtension'] = 'You can only upload files of type: %s';
        $strings['InvalidStartSlot'] = 'The start date and time requested is not valid.';
        $strings['InvalidEndSlot'] = 'The end date and time requested is not valid.';
        $strings['MaxParticipantsError'] = '%s can only support %s participants.';
        $strings['ReservationCriticalError'] = 'There was a critical error saving your reservation. If this continues, contact your system administrator.';
        $strings['InvalidStartReminderTime'] = 'The start reminder time is not valid.';
        $strings['InvalidEndReminderTime'] = 'The end reminder time is not valid.';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s does not allow reservations across days.';
        $strings['InvalidReservationData'] = 'There were problems with your reservation request.';
        $strings['PasswordError'] = 'Password must contain at least %s letters and at least %s numbers.';
        $strings['PasswordErrorRequirements'] = 'Password must contain a combination of at least %s upper and lower case letters and %s numbers.';
        $strings['NoReservationAccess'] = 'You are not allowed to change this reservation.';
        $strings['PasswordControlledExternallyError'] = 'Your password is controlled by an external system and cannot be updated here.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Accessory %s can only be booked with resources %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'You must book at least %s of accessory %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'You cannot book more than %s of accessory %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Accessory \'%s\' cannot be booked with the requested resources';
        $strings['NoResources'] = 'You have not added any resources.';
        $strings['ParticipationNotAllowed'] = 'You are not allowed to join this reservation.';
        $strings['ReservationCannotBeCheckedInTo'] = 'This reservation cannot be checked in to.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'This reservation cannot be checked out from.';
        $strings['InvalidEmailDomain'] = 'That email address is not from an allowed domain';
        $strings['TermsOfServiceError'] = 'You must accept the Terms of Service';
        $strings['UserNotFound'] = 'That user could not be found';
        $strings['ScheduleAvailabilityError'] = 'This schedule is available between %s and %s';
        $strings['ReservationNotFoundError'] = 'Reservation not found';
        $strings['ReservationNotAvailable'] = 'Reservation not available';
        $strings['TitleRequiredRule'] = 'Reservation title is required';
        $strings['DescriptionRequiredRule'] = 'Reservation description is required';
        $strings['WhatCanThisGroupManage'] = 'What can this group manage?';
        $strings['ReservationParticipationActivityPreference'] = 'When someone joins or leaves my reservation';
        $strings['RegisteredAccountRequired'] = 'Only registered users can book reservations';
        $strings['InvalidNumberOfResourcesError'] = 'The maximum number of resources that can be reserved in a single reservation is %s';
        $strings['ScheduleTotalReservationsError'] = 'This schedule only allows %s resources to be reserved concurrently. This reservation would violate that limit on the following dates:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Create Reservation';
        $strings['EditReservation'] = 'Update Reservation';
        $strings['LogIn'] = 'Log In';
        $strings['ManageReservations'] = 'Reservations';
        $strings['AwaitingActivation'] = 'Awaiting Activation';
        $strings['PendingApproval'] = 'Pending Approval';
        $strings['ManageSchedules'] = 'Schedules';
        $strings['ManageResources'] = 'Resources';
        $strings['ManageAccessories'] = 'Accessories';
        $strings['ManageUsers'] = 'Users';
        $strings['ManageGroups'] = 'Groups';
        $strings['ManageQuotas'] = 'Quotas';
        $strings['ManageBlackouts'] = 'Blackout Times';
        $strings['MyDashboard'] = 'My Dashboard';
        $strings['ServerSettings'] = 'Server Settings';
        $strings['Dashboard'] = 'Dashboard';
        $strings['Help'] = 'Help';
        $strings['Administration'] = 'Administration';
        $strings['About'] = 'About';
        $strings['Bookings'] = 'Bookings';
        $strings['Schedule'] = 'Schedule';
        $strings['Account'] = 'Account';
        $strings['EditProfile'] = 'Edit My Profile';
        $strings['FindAnOpening'] = 'Find An Opening';
        $strings['OpenInvitations'] = 'Open Invitations';
        $strings['ResourceCalendar'] = 'Resource Calendar';
        $strings['Reservation'] = 'New Reservation';
        $strings['Install'] = 'Installation';
        $strings['ChangePassword'] = 'Change Password';
        $strings['MyAccount'] = 'My Account';
        $strings['Profile'] = 'Profile';
        $strings['ApplicationManagement'] = 'Application Management';
        $strings['ForgotPassword'] = 'Forgot Password';
        $strings['NotificationPreferences'] = 'Notification Preferences';
        $strings['ManageAnnouncements'] = 'Announcements';
        $strings['Responsibilities'] = 'Responsibilities';
        $strings['GroupReservations'] = 'Group Reservations';
        $strings['ResourceReservations'] = 'Resource Reservations';
        $strings['Customization'] = 'Customization';
        $strings['Attributes'] = 'Attributes';
        $strings['AccountActivation'] = 'Account Activation';
        $strings['ScheduleReservations'] = 'Schedule Reservations';
        $strings['Reports'] = 'Reports';
        $strings['GenerateReport'] = 'Create New Report';
        $strings['MySavedReports'] = 'My Saved Reports';
        $strings['CommonReports'] = 'Common Reports';
        $strings['ViewDay'] = 'View Day';
        $strings['Group'] = 'Group';
        $strings['ManageConfiguration'] = 'Application Configuration';
        $strings['LookAndFeel'] = 'Look and Feel';
        $strings['ManageResourceGroups'] = 'Resource Groups';
        $strings['ManageResourceTypes'] = 'Resource Types';
        $strings['ManageResourceStatus'] = 'Resource Statuses';
        $strings['ReservationColors'] = 'Reservation Colors';
        $strings['SearchReservations'] = 'Search Reservations';
        $strings['ManagePayments'] = 'Payments';
        $strings['ViewCalendar'] = 'View Calendar';
        $strings['DataCleanup'] = 'Data Cleanup';
        $strings['ManageEmailTemplates'] = 'Manage Email Templates';
        $strings['CheckResources'] = 'Check Resources';
        $strings['CheckSchedules'] = 'Check Schedules';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Sun';
        $strings['DayMondayAbbr'] = 'Mon';
        $strings['DayTuesdayAbbr'] = 'Tue';
        $strings['DayWednesdayAbbr'] = 'Wed';
        $strings['DayThursdayAbbr'] = 'Thu';
        $strings['DayFridayAbbr'] = 'Fri';
        $strings['DaySaturdayAbbr'] = 'Sat';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Your reservation has been approved';
        $strings['ReservationCreatedSubject'] = 'Your reservation was created';
        $strings['ReservationUpdatedSubject'] = 'Your reservation was updated';
        $strings['ReservationDeletedSubject'] = 'Your reservation was removed';
        $strings['ReservationCreatedAdminSubject'] = 'Notification: A reservation was created';
        $strings['ReservationUpdatedAdminSubject'] = 'Notification: A reservation was updated';
        $strings['ReservationDeleteAdminSubject'] = 'Notification: A reservation was removed';
        $strings['ReservationApprovalAdminSubject'] = 'Notification: Reservation requires your approval';
        $strings['ParticipantAddedSubject'] = 'Reservation participation notification';
        $strings['ParticipantDeletedSubject'] = 'Reservation removed';
        $strings['InviteeAddedSubject'] = 'Reservation invitation';
        $strings['ResetPasswordRequest'] = 'Password reset request';
        $strings['ActivateYourAccount'] = 'Please activate your account';
        $strings['ReportSubject'] = 'Your requested report (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Reservation for %s is starting soon';
        $strings['ReservationEndingSoonSubject'] = 'Reservation for %s is ending soon';
        $strings['UserAdded'] = 'A new user has been added';
        $strings['UserDeleted'] = 'User account for %s was deleted by %s';
        $strings['GuestAccountCreatedSubject'] = 'Your %s account details';
        $strings['AccountCreatedSubject'] = 'Your %s account details';
        $strings['InviteUserSubject'] = '%s has invited you to join %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Reservation has been approved for %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Reservation created for %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Reservation updated for %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Reservation removed for %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Notification: Reservation Created for %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Notification: Reservation Updated for %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Notification: Reservation Removed for %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Notification: Reservation for %s requires your approval';
        $strings['ParticipantAddedSubjectWithResource'] = '%s added you to a reservation for %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s updated a reservation for %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s removed a reservation for %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s invited you to a reservation for %s';
        $strings['MissedCheckinEmailSubject'] = 'Missed checkin for %s';
        $strings['ReservationShareSubject'] = '%s shared a reservation for %s';
        $strings['ReservationSeriesEndingSubject'] = 'Reservation series for %s is ending on %s';
        $strings['ReservationParticipantAccept'] = '%s has accepted your reservation invitation for %s on %s';
        $strings['ReservationParticipantDecline'] = '%s has declined your reservation invitation for %s on %s';
        $strings['ReservationParticipantJoin'] = '%s has joined your reservation for %s on %s';
        $strings['ReservationAvailableSubject'] = '%s is available on %s';
        $strings['ResourceStatusChangedSubject'] = 'The availability of %s has changed';
        // End Email Subjects

        //Past Reservations
        $strings['NoPastReservations'] = 'You have no past reservations';
        $strings['PastReservations'] = 'Past Reservations';
        $strings['AllNoPastReservations'] = 'There are no past reservations in previous %s days';
        $strings['AllPastReservations'] = 'All Past Reservations';
        $strings['Yesterday'] = 'Yesterday';
        $strings['EarlierThisWeek'] = 'Earlier This Week';
        $strings['PreviousWeek'] = 'Previous Week';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['GroupUpcomingReservations'] = 'My Group(s) Upcoming Reservations';
        $strings['NoGroupUpcomingReservations'] = 'Your group has no upcoming reservations';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'An error occured while logging in with facebook. Please try again.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'You have no reservations pending approval';
        $strings['PendingApprovalReservations'] = 'Pending Approval Reservations';
        $strings['LaterThisMonth'] = 'Later This Month';
        $strings['LaterThisYear'] = 'Later This Year';
        $strings['Other'] = 'Other';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'There are no missing check out reservations';
        $strings['MissingCheckOutReservations'] = 'Missing Check Out Reservations';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Can\'t see reservation details because you don\'t have permissions to any of the resources in this reservation';
        //End Schedule Resource Permissions

        //View Resource
        $strings['Check'] = 'Check';
        $strings['PermissionType'] = 'Permission Type';
        $strings['NoResourcesToView'] = 'No available resources';
        //End View Resource

        //Datatables
        $strings['Info'] = "Showing page _PAGE_ of _PAGES_ of _MAX_";
        $strings['LengthMenu'] = "Display _MENU_ records per page";
        //End Datatables

        $this->Strings = $strings;

        return $this->Strings;
    }

    /**
     * @return array
     */
    protected function _LoadDays()
    {
        $days = [];

        /***
         * DAY NAMES
         * All of these arrays MUST start with Sunday as the first element
         * and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        // The three letter abbreviation
        $days['abbr'] = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        // The two letter abbreviation
        $days['two'] = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        // The one letter abbreviation
        $days['letter'] = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

        $this->Days = $days;

        return $this->Days;
    }

    /**
     * @return array
     */
    protected function _LoadMonths()
    {
        $months = [];

        /***
         * MONTH NAMES
         * All of these arrays MUST start with January as the first element
         * and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'en';
    }
}
