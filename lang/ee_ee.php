<?php

require_once('en_gb.php');

class ee_ee extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Eesnimi';
        $strings['LastName'] = 'Perekonnanimi';
        $strings['Timezone'] = 'Ajavöönd';
        $strings['Edit'] = 'Muuda';
        $strings['Change'] = 'Vaheta';
        $strings['Rename'] = 'Nimeta ümber';
        $strings['Remove'] = 'Eemalda';
        $strings['Delete'] = 'Kustuta';
        $strings['Update'] = 'Uuenda';
        $strings['Cancel'] = 'Tühista';
        $strings['Add'] = 'Lisa';
        $strings['Name'] = 'Nimi';
        $strings['Yes'] = 'Jah';
        $strings['No'] = 'Ei';
        $strings['FirstNameRequired'] = 'Eesnimi on kohustuslik.';
        $strings['LastNameRequired'] = 'Perekonnanimi on kohustuslik.';
        $strings['PwMustMatch'] = 'Parooli kinnitus peab klappima parooliga.';

        $strings['ValidEmailRequired'] = 'Kehtiv emaili aadress on kohustuslik.';
        $strings['UniqueEmailRequired'] = 'See emaili aadress on juba registreeritud.';
        $strings['UniqueUsernameRequired'] = 'See kasutajanimi on juba registreeritud.';
        $strings['UserNameRequired'] = 'Kasutajanimi on kohustuslik.';
        $strings['CaptchaMustMatch'] = 'Palun sisesta kiri turvapildilt täpselt nagu näidatud.';
        $strings['Today'] = 'Täna';
        $strings['Week'] = 'Nädal';
        $strings['Month'] = 'Kuu';
        $strings['BackToCalendar'] = 'Tagasi kalendri juurde';
        $strings['BeginDate'] = 'Algus';
        $strings['EndDate'] = 'Lõpp';
        $strings['Username'] = 'Kasutajanimi';
        $strings['Password'] = 'Parool';
        $strings['PasswordConfirmation'] = 'Kinnita parool';
        $strings['DefaultPage'] = 'Pealeht';
        $strings['MyCalendar'] = 'Minu kalender';
        $strings['ScheduleCalendar'] = 'Kalender';
        $strings['Registration'] = 'Registreerimine';
        $strings['NoAnnouncements'] = 'Pole teateid';
        $strings['Announcements'] = 'Teated';
        $strings['NoUpcomingReservations'] = 'Pole tulevasi broneeringuid';

        $strings['UpcomingReservations'] = 'Tulevased broneeringud';
        $strings['AllNoUpcomingReservations'] = 'Tulevasi broneeringuid pole järgmise %s päeva jooksul';
        $strings['AllUpcomingReservations'] = 'Kõik tulevased broneeringud';
        $strings['ShowHide'] = 'Näita/Peida';
        $strings['Error'] = 'Viga';
        $strings['ReturnToPreviousPage'] = 'Tagasi eelmisele lehele';
        $strings['UnknownError'] = 'Tundmatu viga';
        $strings['InsufficientPermissionsError'] = 'Teil puudub ligipääs selle väljakule';
        $strings['MissingReservationResourceError'] = 'Väljak pole valitud';
        $strings['MissingReservationScheduleError'] = 'Kalender pole valitud';
        $strings['DoesNotRepeat'] = 'Ei kordu';
        $strings['Daily'] = 'Iga päev';
        $strings['Weekly'] = 'Iga nädal';
        $strings['Monthly'] = 'Iga kuu';
        $strings['Yearly'] = 'Iga aasta';
        $strings['RepeatPrompt'] = 'Korda';
        $strings['hours'] = 'tundi';
        $strings['days'] = 'päeva';
        $strings['weeks'] = 'nädala';
        $strings['months'] = 'kuud';
        $strings['years'] = 'aastad';
        $strings['day'] = 'päev';
        $strings['week'] = 'nädal';
        $strings['month'] = 'kuu';
        $strings['year'] = 'aasta';
        $strings['repeatDayOfMonth'] = 'päev kuus';
        $strings['repeatDayOfWeek'] = 'päev nädalas';
        $strings['RepeatUntilPrompt'] = 'Kuni';
        $strings['RepeatEveryPrompt'] = 'Iga';
        $strings['RepeatDaysPrompt'] = 'Päev';
        $strings['CreateReservationHeading'] = 'Loo uus broneering';
        $strings['EditReservationHeading'] = 'Broneeringu %s muutmine';
        $strings['ViewReservationHeading'] = 'Broneeringu %s vaatamine';
        $strings['ReservationErrors'] = 'Muuda broneeringut';
        $strings['Create'] = 'Loo broneering';
        $strings['ThisInstance'] = 'Ainult antud juhul';
        $strings['AllInstances'] = 'Igal juhul';
        $strings['FutureInstances'] = 'Tulevastel juhtudel';
        $strings['Print'] = 'Prindi';
        $strings['ShowHideNavigation'] = 'Näita/Peida navigatsioon';
        $strings['ReferenceNumber'] = 'Viitenumber';
        $strings['Tomorrow'] = 'Homme';
        $strings['LaterThisWeek'] = 'Hiljem sel nädalal';
        $strings['NextWeek'] = 'Järgmine nädal';
        $strings['SignOut'] = 'Logi välja';
        $strings['LayoutDescription'] = 'Algab %s, näitab %s päeva';
        $strings['AllResources'] = 'Kõik väljakud';
        $strings['TakeOffline'] = 'Tee offline';
        $strings['BringOnline'] = 'Too online';
        $strings['AddImage'] = 'Lisa pilt';
        $strings['NoImage'] = 'Pilti pole määratud';
        $strings['Move'] = 'Liiguta';
        $strings['AppearsOn'] = 'Ilmub %s';
        $strings['Location'] = 'Asukoht';
        $strings['NoLocationLabel'] = '(asukohta pole määratud)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(Kontakti info puudub)';
        $strings['Description'] = 'Kirjeldus';
        $strings['NoDescriptionLabel'] = '(Kirjeldus puudub)';
        $strings['Notes'] = 'Märkused';
        $strings['NoNotesLabel'] = '(Märkused puuduvad)';
        $strings['NoTitleLabel'] = '(Pealkiri puudub)';
        $strings['UsageConfiguration'] = 'Kasutamise seadistus';
        $strings['ChangeConfiguration'] = 'Muuda seadeid';
        $strings['ResourceMinLength'] = 'Broneering peab olama vähemalt %s tundi';
        $strings['ResourceMinLengthNone'] = 'Minimaalne broneeringu aeg puudub';
        $strings['ResourceMaxLength'] = 'Broneering ei saa olla pikem kui %s';
        $strings['ResourceMaxLengthNone'] = 'Maksimaalsne broneeringu aeg puudub';
        $strings['ResourceRequiresApproval'] = 'Broneering peab olema kinnitatud';
        $strings['ResourceRequiresApprovalNone'] = 'Broneering ei vaja kinnitamiset';
        $strings['ResourcePermissionAutoGranted'] = 'Load on automaatselt antud';
        $strings['ResourcePermissionNotAutoGranted'] = 'Load pole automaatselt antud';
        $strings['ResourceMinNotice'] = 'Broneering tuleb teha vähemalt %s enne algusaega';
        $strings['ResourceMinNoticeNone'] = 'Broneeringut saab teha kuni praeguse ajani';
        $strings['ResourceMaxNotice'] = 'Broneering ei tohi lõppeda %s praegusest ajast';
        $strings['ResourceMaxNoticeNone'] = 'Broneering saab lõppeda igal ajal';
        $strings['ResourceBufferTime'] = 'Broneeringute vahel peab olema %s';
        $strings['ResourceBufferTimeNone'] = 'Broneeringute vahel pole puhvrit';
        $strings['ResourceAllowMultiDay'] = 'Broneeringut saab teha üle päeva';
        $strings['ResourceNotAllowMultiDay'] = 'Broneeringut ei saa teha üle päeva';
        $strings['ResourceCapacity'] = 'See väljak mahutab %s inimest';
        $strings['ResourceCapacityNone'] = 'See väljak mahutab piiramatult inimesi';
        $strings['AddNewResource'] = 'Lisa uus väljak';
        $strings['AddNewUser'] = 'Lisa uus kasutaja';
        $strings['AddResource'] = 'Lisa väljak';
        $strings['Capacity'] = 'Mahutavus';
        $strings['Access'] = 'Ligipääs';
        $strings['Duration'] = 'Kestvus';
        $strings['Active'] = 'Aktiivne';
        $strings['Inactive'] = 'Pole aktiivne';
        $strings['ResetPassword'] = 'Lähtesta parool';
        $strings['LastLogin'] = 'Viimane sisselogimine';
        $strings['Search'] = 'Otsi';
        $strings['ResourcePermissions'] = 'Väljaku load';
        $strings['Reservations'] = 'Broneeringud';
        $strings['Groups'] = 'Grupid';
        $strings['Users'] = 'Kasutajad';
        $strings['AllUsers'] = 'Kõik kasutajad';
        $strings['AllGroups'] = 'Kõik grupid';
        $strings['AllSchedules'] = 'Kõik kalendrid';
        $strings['UsernameOrEmail'] = 'Kasutajanimi või Email';
        $strings['Members'] = 'Liikmed';
        $strings['QuickSlotCreation'] = 'Create slots every %s minutes between %s and %s';
        $strings['ApplyUpdatesTo'] = 'kohalda muudatus';
        $strings['CancelParticipation'] = 'Cancel Participation';
        $strings['Attending'] = 'Attending';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['QuotaEnforcement'] = 'Sunniviisiline %s %s';
        $strings['reservations'] = 'broneeringud';
        $strings['reservation'] = 'broneering';
        $strings['ChangeCalendar'] = 'Vaheta kalendrit';
        $strings['AddQuota'] = 'Lisa limiit';
        $strings['FindUser'] = 'Otsi kasutajat';
        $strings['Created'] = 'Broneering loodud';
        $strings['LastModified'] = 'Viimati uuendatud';
        $strings['GroupName'] = 'Grupi nimi';
        $strings['GroupMembers'] = 'Grupi liikmed';
        $strings['GroupRoles'] = 'Grupi rollid';
        $strings['GroupAdmin'] = 'Grupi admin';
        $strings['Actions'] = 'Tegevused';
        $strings['CurrentPassword'] = 'Praegune parool';
        $strings['NewPassword'] = 'Uus parool';
        $strings['InvalidPassword'] = 'Praegune parool on vale';
        $strings['PasswordChangedSuccessfully'] = 'Sinu parool on edukalt muudetud';
        $strings['SignedInAs'] = 'Sisselogitud kui';
        $strings['NotSignedIn'] = 'Sa pole sisse logitud';
        $strings['ReservationTitle'] = 'Broneeringu pealkiri';
        $strings['ReservationDescription'] = 'Broneeringu kirjeldus';
        $strings['ResourceList'] = 'Broneeritavad väljakud';
        $strings['Accessories'] = 'Lisavarustus';
        ;
        $strings['InvitationList'] = 'Kutsutud inimesed';
        $strings['AccessoryName'] = 'Lisavarustuse nimi';
        $strings['QuantityAvailable'] = 'Kogus saadaval';
        $strings['Resources'] = 'Väljakud';
        $strings['Participants'] = 'Osalejad';
        $strings['User'] = 'Kasutaja';
        $strings['Resource'] = 'Väljak';
        $strings['Status'] = 'Staatus';
        $strings['Approve'] = 'Kinnita';
        $strings['Page'] = 'Lehekülg';
        $strings['Rows'] = 'Ridasid';
        $strings['Unlimited'] = 'Piiramatult';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Emaili aadress';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Asutus';
        $strings['Position'] = 'Ametikoht';
        $strings['Language'] = 'Keel';
        $strings['Permissions'] = 'Load';
        $strings['Reset'] = 'Lähtesta';
        $strings['FindGroup'] = 'Leia grupp';
        $strings['Manage'] = 'Halda';
        $strings['None'] = 'Mitte ükski';
        $strings['AddToOutlook'] = 'Lisa kalendrisse';
        $strings['Done'] = 'Lisatud';
        $strings['RememberMe'] = 'Jäta mind meelde';
        $strings['FirstTimeUser?'] = 'Oled esmakasutaja?';
        $strings['CreateAnAccount'] = 'Loo konto';
        $strings['ViewSchedule'] = 'Vaata kalendrit';
        $strings['ForgotMyPassword'] = 'Unustasin parooli';
        $strings['YouWillBeEmailedANewPassword'] = 'Sulle saadetakse emailiga uus parool';
        $strings['Close'] = 'Sulge';
        $strings['ExportToCSV'] = 'Ekspordi CSV formaati';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Töötab...';
        $strings['Login'] = 'Logi sisse';
        $strings['AdditionalInformation'] = 'Lisainformatsioon';
        $strings['AllFieldsAreRequired'] = 'kõik väljad on kohustuslikud';
        $strings['Optional'] = 'Vabatahtlik';
        $strings['YourProfileWasUpdated'] = 'Sinu profiil on uuendatud';
        $strings['YourSettingsWereUpdated'] = 'Sinu seaded on uuendatud';
        $strings['Register'] = 'Registreeri';
        $strings['SecurityCode'] = 'Turvakood';
        $strings['ReservationCreatedPreference'] = 'Kui broneering on loodud minu poolt';
        $strings['ReservationUpdatedPreference'] = 'Kui broneering on uuendatud minu poolt';
        $strings['ReservationDeletedPreference'] = 'Kui broneering on kustutatud minu poolt';
        $strings['ReservationApprovalPreference'] = 'Kui broneeringud on kinnitatud ';
        $strings['PreferenceSendEmail'] = 'Saada mulle email';
        $strings['PreferenceNoEmail'] = 'Ära saada teavitust';
        $strings['ReservationCreated'] = 'Sinu broneering on edukalt loodud!';
        $strings['ReservationUpdated'] = 'Sinu broneering on uuendatud!';
        $strings['ReservationRemoved'] = 'Sinu broneering on kustutatud';
        $strings['ReservationRequiresApproval'] = 'Väljaku broneerimine vajab kinnitust. Broneering on ootel, kuni see kinnitatakse.';
        $strings['YourReferenceNumber'] = 'Sinu viitenumber on %s';
        $strings['ChangeUser'] = 'Vaheta kasutajat';
        $strings['MoreResources'] = 'Lisa väljak';
        $strings['ReservationLength'] = 'Broneeringu pikkus';
        $strings['ParticipantList'] = 'Osalejate nimekiri';
        $strings['AddParticipants'] = 'Lisa osalejad';
        $strings['InviteOthers'] = 'Kutsu teisi';
        $strings['AddResources'] = 'Lisa väljak';
        $strings['AddAccessories'] = 'Lisa lisavarustus';
        $strings['Accessory'] = 'Lisvarustus';
        $strings['QuantityRequested'] = 'Soovitud kogus';
        $strings['CreatingReservation'] = 'Broneeringu loomine';
        $strings['UpdatingReservation'] = 'Broneeringu uuendamine';
        $strings['DeleteWarning'] = 'See tegevus on püsiv ja seda ei saa parandada!';
        $strings['DeleteAccessoryWarning'] = 'Selle lisavarustuse kutstutamisel kaob see kõigilt broneeringutelt.';
        $strings['AddAccessory'] = 'Lisa lisavarustus';
        $strings['AddBlackout'] = 'Lisa suletud ajad';
        $strings['AllResourcesOn'] = 'Kõigile väljakutele';
        $strings['Reason'] = 'Põhjus';
        $strings['BlackoutShowMe'] = 'Näita mulle vastuolulisi broneeringuid';
        $strings['BlackoutDeleteConflicts'] = 'Kustuta vastuolulised broneeringud';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Vahel';
        $strings['CreatedBy'] = 'Loodud';
        $strings['BlackoutCreated'] = 'Suletud ajad loodud!';
        $strings['BlackoutNotCreated'] = 'Suletud aegu ei saanud luua!';
        $strings['BlackoutUpdated'] = 'Suletud ajad on uuendatud';
        $strings['BlackoutNotUpdated'] = 'Suletud aegu ei saa uuendada';
        $strings['BlackoutConflicts'] = 'Seal on vastuolulised suletud ajad';
        $strings['ReservationConflicts'] = 'Seal on vastuolulised broneeringu ajad';
        $strings['UsersInGroup'] = 'Grupi kasutajad';
        $strings['Browse'] = 'Sirvi';
        $strings['DeleteGroupWarning'] = 'Deleting this group will remove all associated resource permissions.  Users in this group may lose access to resources.';
        $strings['WhatRolesApplyToThisGroup'] = 'What roles apply to this group?';
        $strings['WhoCanManageThisGroup'] = 'Who can manage this group?';
        $strings['WhoCanManageThisSchedule'] = 'Who can manage this schedule?';
        $strings['AddGroup'] = 'Add Group';
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
        $strings['AddAnnouncement'] = 'Lisa teadaanne';
        $strings['Announcement'] = 'Teadaanne';
        $strings['Priority'] = 'Priority';
        $strings['Reservable'] = 'VABA';
        $strings['Unreservable'] = 'SULETUD';
        $strings['Reserved'] = 'BRONEERITUD';
        $strings['MyReservation'] = 'MINU BRONEERINGUD';
        $strings['Pending'] = '&nbsp;';
        $strings['Past'] = 'MÖÖDAS';
        $strings['Restricted'] = '&nbsp;';

        $strings['ViewAll'] = 'View All';
        $strings['MoveResourcesAndReservations'] = 'Move resources and reservations to';
        $strings['TurnOffSubscription'] = 'Turn Off Calendar Subscriptions';
        $strings['TurnOnSubscription'] = 'Allow Subscriptions to this Calendar';
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
        $strings['AdditionalAttributes'] = 'Lisainfo';
        $strings['True'] = 'Õige';
        $strings['False'] = 'Vale';
        $strings['ForgotPasswordEmailSent'] = 'Saatsime Teile emaili parooli lähtestamise infoga';
        $strings['ActivationEmailSent'] = 'Saate varsti konto aktiveerimise e-maili.';
        $strings['AccountActivationError'] = 'Vabandame, ei saanud Teie kontot aktiveerida.';
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
        $strings['TotalTime'] = 'Total Time';
        $strings['Count'] = 'Count';
        $strings['Usage'] = 'Usage';
        $strings['AggregateBy'] = 'Aggregate By';
        $strings['Range'] = 'Range';
        $strings['Choose'] = 'Choose';
        $strings['All'] = 'Kõik';
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
        $strings['ConfigurationUpdateHelp'] = 'Refer to the Configuration section of the <a target=_blank href=%s>Help File</a> for documentation on these settings.';
        $strings['GeneralConfigSettings'] = 'Seaded';
        $strings['UseSameLayoutForAllDays'] = 'Kasuta sama vaadet kõigil päevadel';
        $strings['LayoutVariesByDay'] = 'Vaade varieerub';
        $strings['ManageReminders'] = 'Meeldetuletused';
        $strings['ReminderUser'] = 'Kasutaja';
        $strings['ReminderMessage'] = 'Sõnum';
        $strings['ReminderAddress'] = 'Aadressid';
        $strings['ReminderSendtime'] = 'Saatmise aeg';
        $strings['ReminderRefNumber'] = 'Broneeringu viitenumber';
        $strings['ReminderSendtimeDate'] = 'Meeldetuletuse kuupäev';
        $strings['ReminderSendtimeTime'] = 'Aega meeletuletuseni (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Lisa meeldetuletus';
        $strings['DeleteReminderWarning'] = 'Oled Sa kindel?';
        $strings['NoReminders'] = 'Sul pole tulevasi meeldetuletusi.';
        $strings['Reminders'] = 'Meeldetuletused';
        $strings['SendReminder'] = 'Saada meeldetuletus';
        $strings['minutes'] = 'minutit';
        $strings['hours'] = 'tundi';
        $strings['days'] = 'päeva';
        $strings['ReminderBeforeStart'] = 'before the start time';
        $strings['ReminderBeforeEnd'] = 'before the end time';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Your changes have been saved. Refresh the page for changes to take effect.';
        $strings['MakeDefaultSchedule'] = 'Make this my default schedule';
        $strings['DefaultScheduleSet'] = 'This is now your default schedule';
        $strings['FlipSchedule'] = 'Flip the schedule layout';
        $strings['Next'] = 'Next';
        $strings['Success'] = 'Õnnestus';
        $strings['Participant'] = 'Osaleja';
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
        $strings['ReservationCustomRuleAdd'] = 'If %s then the reservation color will be';
        $strings['AddReservationColorRule'] = 'Add Reservation Color Rule';
        $strings['LimitAttributeScope'] = 'Collect In Specific Cases';
        $strings['CollectFor'] = 'Collect For';
        $strings['SignIn'] = 'Logi sisse';
        $strings['AllParticipants'] = 'All Participants';
        $strings['RegisterANewAccount'] = 'Registreeri uus kasutaja';
        $strings['Dates'] = 'Aeg';
        $strings['More'] = 'Rohkem';
        $strings['ResourceAvailability'] = 'Resource Availability';
        $strings['UnavailableAllDay'] = 'Unavailable All Day';
        $strings['AvailableUntil'] = 'Available Until';
        $strings['AvailableBeginningAt'] = 'Available Beginning At';
        $strings['AllResourceTypes'] = 'Kõik väljakud';
        $strings['AllResourceStatuses'] = 'Kõik väljaku staatused';
        $strings['AllowParticipantsToJoin'] = 'Allow Participants To Join';
        $strings['Join'] = 'Liitu';
        $strings['YouAreAParticipant'] = 'You are a participant of this reservation';
        $strings['YouAreInvited'] = 'You are invited to this reservation';
        $strings['YouCanJoinThisReservation'] = 'You can join this reservation';
        $strings['Import'] = 'Import';
        $strings['GetTemplate'] = 'Get Template';
        $strings['UserImportInstructions'] = 'File must be in CSV format. Username and email are required fields. Leaving other fields blank will set default values and \'password\' as the user\'s password. Use the supplied template as an example.';
        $strings['RowsImported'] = 'Rows Imported';
        $strings['RowsSkipped'] = 'Rows Skipped';
        $strings['Columns'] = 'Veerud';
        $strings['Reserve'] = 'Broneeri';
        $strings['AllDay'] = 'Kogu päev';
        $strings['Everyday'] = 'Iga päev';
        $strings['IncludingCompletedReservations'] = 'Including Completed Reservations';
        $strings['NotCountingCompletedReservations'] = 'Not Counting Completed Reservations';
        $strings['RetrySkipConflicts'] = 'Jäta vastuolulised broneeringud vahele';
        $strings['Retry'] = 'Proovi uuesti';
        $strings['RemoveExistingPermissions'] = 'Eemalda praegused load?';
        $strings['Continue'] = 'Edasi';
        $strings['WeNeedYourEmailAddress'] = 'Broneeringu tegemiseks on vaja Sinu emaili aadressi';
        $strings['ResourceColor'] = 'Väljaku värv';
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
        $strings['ResourceDisplayPrompt'] = 'Näita väljakut';
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
        $strings['AnnouncementSubject'] = '%s postitas uue teadaande';
        $strings['AnnouncementEmailNotice'] = 'Kasutajatele saadetakse see teadaanne emailile';
        $strings['Day'] = 'Päev';
        $strings['NotifyWhenAvailable'] = 'Teavita kui vabandeb';
        $strings['AddingToWaitlist'] = 'Lisame Sind ootenimekirja';
        $strings['WaitlistRequestAdded'] = 'Sind teavitatakse kui see aeg vabaneb';
        $strings['PrintQRCode'] = 'Prindi QR kood';
        $strings['FindATime'] = 'Leia aeg';
        $strings['AnyResource'] = 'Mistahes väljak';
        $strings['ThisWeek'] = 'Sel nädalal';
        $strings['Hours'] = 'Tundi';
        $strings['Minutes'] = 'Minutit';
        $strings['ImportICS'] = 'Impordi ICS-st';
        $strings['ImportQuartzy'] = 'Impordi Quartzy-st';
        $strings['OnlyIcs'] = 'Ainult *.ics faile saab üles laadida';
        $strings['IcsLocationsAsResources'] = 'Locations will be imported as resources.';
        $strings['IcsMissingOrganizer'] = 'Any event missing an organizer will have the owner set to the current user.';
        $strings['IcsWarning'] = 'Reservation rules will not be enforced - conflicts, duplicates, etc are possible.';
        $strings['BlackoutAroundConflicts'] = 'Blackout around conflicting reservations';
        $strings['DuplicateReservation'] = 'Kopeeri';
        $strings['UnavailableNow'] = 'Pole enam vaba';
        $strings['ReserveLater'] = 'Broneeri hiljem';
        $strings['CollectedFor'] = 'Kogutud';
        $strings['IncludeDeleted'] = 'Kaasa kustutatud broneeringud';
        $strings['Deleted'] = 'Kustutatud';
        $strings['Back'] = 'Tagasi';
        $strings['Forward'] = 'Edasi';
        $strings['DateRange'] = 'Kuupäevavahemik';
        $strings['Copy'] = 'Kopeeri';
        $strings['Detect'] = 'Leia';
        $strings['Autofill'] = 'Automaatne täitmine';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Install LibreBooking (MySQL only)';
        $strings['IncorrectInstallPassword'] = 'Sorry, that password was incorrect.';
        $strings['SetInstallPassword'] = 'You must set an install password before the installation can be run.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'There is no upgrade needed. Running the installation process will delete all existing data and install a new copy of LibreBooking!';
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
        $strings['SelectUser'] = 'Select User';
        $strings['InviteUsers'] = 'Kutsu kasutajad';
        $strings['InviteUsersLabel'] = 'Sisesta kutsutute emaili aadressid';
        // End Install

        // Errors
        $strings['LoginError'] = 'Vale kasutajanimi või parool';
        $strings['ReservationFailed'] = 'Broneeringut ei saanud teha';
        $strings['MinNoticeError'] = 'Selle aja broneerimiseks helistage palun 55663493.  Varaseim aeg mida on võimalik internetis broneerida: %s.';
        $strings['MaxNoticeError'] = 'Pole võimalik nii kaugele ette broneerida. Kaugeim kuupäev ja aeg, mida on võimalik broneerida: %s.';
        $strings['MinDurationError'] = 'Broneering peab olema vähemalt %s.';
        $strings['MaxDurationError'] = 'Broneering ei saa olla pikem kui %s.';
        $strings['ConflictingAccessoryDates'] = 'There are not enough of the following accessories:';
        $strings['NoResourcePermission'] = 'You do not have permission to access one or more of the requested resources';
        $strings['ConflictingReservationDates'] = 'Järgnevatel kuupäevadel on vastuolulised broneeringud:';
        $strings['StartDateBeforeEndDateRule'] = 'Broneeringu algusaeg peab olema enne lõppemisaega';
        $strings['StartIsInPast'] = 'Alguse kuupäev ja aeg ei tohi olla juba möödunud';
        $strings['EmailDisabled'] = 'Administraator on keelanud emailile teavitamise';
        $strings['ValidLayoutRequired'] = 'Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'Puudulik lisainfo:';
        $strings['CustomAttributeRequired'] = '%s on kohustuslik väli';
        $strings['CustomAttributeInvalid'] = '%s väli on valesti täidetud';
        $strings['AttachmentLoadingError'] = 'Sorry, there was a problem loading the requested file.';
        $strings['InvalidAttachmentExtension'] = 'You can only upload files of type: %s';
        $strings['InvalidStartSlot'] = 'Soovitud alguskuupäev ja kellaaeg pole kehtiv.';
        $strings['InvalidEndSlot'] = 'Soovitud lõpukuupäev ja kellaaeg pole kehtiv.';
        $strings['MaxParticipantsError'] = '%s can only support %s participants.';
        $strings['ReservationCriticalError'] = 'Broneeringu salvestamisel tekkis viga. Kui viga kordub, võtke ühedust administraatoriga.';
        $strings['InvalidStartReminderTime'] = 'Meeldetuletuse algusaeg pole kehtiv.';
        $strings['InvalidEndReminderTime'] = 'Meeldetuletuse lõppaeg pole kehtiv.';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s ei luba broneeringuid üle päeva.';
        $strings['InvalidReservationData'] = 'Soovitud broneeringuga tekkis probleem.';
        $strings['PasswordError'] = 'Parool peab sisaldama vähemalt %s tähte ja %s numbrit.';
        $strings['PasswordErrorRequirements'] = 'Parool peab koosnema %s suure ja väikese tähe ja %s numbri kombinatsioonist.';
        $strings['NoReservationAccess'] = 'Sul pole lubatud broneeringut muuta.';
        $strings['PasswordControlledExternallyError'] = 'Your password is controlled by an external system and cannot be updated here.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Accessory %s can only be booked with resources %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'You must book at least %s of accessory %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'You cannot book more than %s of accessory %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Accessory \'%s\' cannot be booked with the requested resources';
        $strings['NoResources'] = 'Ühtki väljakut pole lisatud.';
        $strings['ParticipationNotAllowed'] = 'You are not allowed to join this reservation.';
        $strings['ReservationCannotBeCheckedInTo'] = 'This reservation cannot be checked in to.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'This reservation cannot be checked out from.';
        $strings['InvalidEmailDomain'] = 'That email address is not from an allowed domain';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Broneeringu loomine';
        $strings['EditReservation'] = 'Broneeringu muutmine';
        $strings['LogIn'] = 'Logi sisse';
        $strings['ManageReservations'] = 'Broneeringud';
        $strings['AwaitingActivation'] = 'Awaiting Activation';
        $strings['PendingApproval'] = 'Pending Approval';
        $strings['ManageSchedules'] = 'Kalendrid';
        $strings['ManageResources'] = 'Väljakud';
        $strings['ManageAccessories'] = 'Lisavarustus';
        $strings['ManageUsers'] = 'Kasutajad';
        $strings['ManageGroups'] = 'Gruppid';
        $strings['ManageQuotas'] = 'Kvoodid';
        $strings['ManageBlackouts'] = 'Suletud ajad';
        $strings['MyDashboard'] = 'Minu pealeht';
        $strings['ServerSettings'] = 'Serveri seaded';
        $strings['Dashboard'] = 'Pealeht';
        $strings['Help'] = 'Abi';
        $strings['Administration'] = 'Administratsioon';
        $strings['About'] = 'Info';
        $strings['Bookings'] = 'Broneeringud';
        $strings['Schedule'] = 'Kalender';
        $strings['Account'] = 'Konto';
        $strings['EditProfile'] = 'Muuda minu profiili';
        $strings['FindAnOpening'] = 'Leia vaba aeg';
        $strings['OpenInvitations'] = 'Kutsed';
        $strings['ResourceCalendar'] = 'Väljaku kalender';
        $strings['Reservation'] = 'Uued broneeringud';
        $strings['Install'] = 'Installimine';
        $strings['ChangePassword'] = 'Muuda parool';
        $strings['MyAccount'] = 'Minu konto';
        $strings['Profile'] = 'Profiil';
        $strings['ApplicationManagement'] = 'Programmi haldus';
        $strings['ForgotPassword'] = 'Unustasin parooli';
        $strings['NotificationPreferences'] = 'Teavitamise seaded';
        $strings['ManageAnnouncements'] = 'Teadaanded';
        $strings['Responsibilities'] = 'Kohustused';
        $strings['GroupReservations'] = 'Group Reservations';
        $strings['ResourceReservations'] = 'Väljaku broneeringud';
        $strings['Customization'] = 'Kohandamine';
        $strings['Attributes'] = 'Atribuudid';
        $strings['AccountActivation'] = 'Konto aktiveerimine';
        $strings['ScheduleReservations'] = 'Kalendri broneeringud';
        $strings['Reports'] = 'Raportid';
        $strings['GenerateReport'] = 'Loo uus raport';
        $strings['MySavedReports'] = 'Minu salvestatud raportid';
        $strings['CommonReports'] = 'Üldised raportid';
        $strings['ViewDay'] = 'Vaata päeva';
        $strings['Group'] = 'Grupp';
        $strings['ManageConfiguration'] = 'Programmi seadistamine';
        $strings['LookAndFeel'] = 'Välimus';
        $strings['ManageResourceGroups'] = 'Väljakute grupid';
        $strings['ManageResourceTypes'] = 'Väljakute tüübid';
        $strings['ManageResourceStatus'] = 'Väljakute staatused';
        $strings['ReservationColors'] = 'Broneeringute värvid';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'P';
        $strings['DayMondaySingle'] = 'E';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'K';
        $strings['DayThursdaySingle'] = 'N';
        $strings['DayFridaySingle'] = 'R';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Püh';
        $strings['DayMondayAbbr'] = 'Esm';
        $strings['DayTuesdayAbbr'] = 'Tei';
        $strings['DayWednesdayAbbr'] = 'Kol';
        $strings['DayThursdayAbbr'] = 'Nel';
        $strings['DayFridayAbbr'] = 'Ree';
        $strings['DaySaturdayAbbr'] = 'Lau';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Rannahall: Sinu broneering on kinnitatud';
        $strings['ReservationCreatedSubject'] = 'Rannahall: Sinu broneering on loodud';
        $strings['ReservationUpdatedSubject'] = 'Rannahall: Sinu broneering on uuendatud';
        $strings['ReservationDeletedSubject'] = 'Rannahall: Sinu broneering on kustutaud';
        $strings['ReservationCreatedAdminSubject'] = 'Teade: Broneering on loodud';
        $strings['ReservationUpdatedAdminSubject'] = 'Teade: Broneering on uuendatud';
        $strings['ReservationDeleteAdminSubject'] = 'Teade: Broneering on kustutatud';
        $strings['ReservationApprovalAdminSubject'] = 'Teade: Broneering vajab kinnitust';
        $strings['ParticipantAddedSubject'] = 'Reservation Participation Notification';
        $strings['ParticipantDeletedSubject'] = 'Broneering on kustutatud';
        $strings['InviteeAddedSubject'] = 'Reservation Invitation';
        $strings['ResetPassword'] = 'Rannahall: Parooli lähtestamine';
        $strings['ActivateYourAccount'] = 'Palun aktiveerige oma konto';
        $strings['ReportSubject'] = 'Sinu raport (%s)';
        $strings['ReservationStartingSoonSubject'] = '%s broneering algab varsti';
        $strings['ReservationEndingSoonSubject'] = '%s broneering lõppeb kohe';
        $strings['UserAdded'] = 'Uus kasutaja on lisatud';
        $strings['UserDeleted'] = 'Kasutaja %s on kustutatud %s poolt';
        $strings['UserAdded'] = 'Uuus kasutaja on lisatud';
        $strings['UserDeleted'] = '%s kasutaja konto kustutati %s poolt';
        $strings['ReservationApprovedSubjectWithResource'] = '%s broneering on heakskiidetud';
        $strings['ReservationCreatedSubjectWithResource'] = 'Broneering loodud %s-le';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Broneering uuendatud %s-le';
        $strings['ReservationDeletedSubjectWithResource'] = 'Broneering kustutatud %s-le';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Teade: Broneering loodud %s-le';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Teade: Broneering uuendatud %s-le';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Teade: Broneering kustutatud %s-le';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Teade: Broneering %s-le vajab sinu heakskiitu';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Added You to a Reservation for %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s Removed a Reservation for %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s Invited You to a Reservation for %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Teil pole varasemaid broneeringuid';
        $strings['PastReservations'] = 'Varasemad broneeringud';
        $strings['AllNoPastReservations'] = 'Viimase %s päeva jooksul pole varasemaid broneeringuid';
        $strings['AllPastReservations'] = 'Kõik varasemad broneeringud';
        $strings['Yesterday'] = 'Eile';
        $strings['EarlierThisWeek'] = 'Varem sel nädalal';
        $strings['PreviousWeek'] = 'Eelmine nädal';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Teie grupil pole tulevasi broneeringuid';
        $strings['GroupUpcomingReservations'] = 'Minu grupi(t)e tulevased broneeringud';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Facebooki sisselogimisel ilmnes viga. Palun proovi uuesti.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Teil pole ühtegi ootel olevat broneeringut';
        $strings['PendingApprovalReservations'] = 'Ootel heakskiitmiseks määratud broneeringud';
        $strings['LaterThisMonth'] = 'Hiljem sel kuul';
        $strings['LaterThisYear'] = 'Hiljem sel aastal';
        $strings['Remaining'] = 'Jäänud';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Puuduvad väljaregistreerimise broneeringud puuduvad';
        $strings['MissingCheckOutReservations'] = 'Puuduvad väljaregistreerimise broneeringud';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Üksikasju ei saa näha, kuna teil pole selles broneeringus ühegi ressursi jaoks luba';
        //End Schedule Resource Permissions
        //END NEEDS CHECKING

        $this->Strings = $strings;

        return $this->Strings;
    }

    /**
     * @return array
     */
    protected function _LoadDays()
    {
        $days = parent::_LoadDays();

        /***
         * DAY NAMES
         * All of these arrays MUST start with Sunday as the first element
         * and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'];
        // The three letter abbreviation
        $days['abbr'] = ['Püh', 'Esm', 'Tei', 'Kol', 'Nel', 'Ree', 'Lau'];
        // The two letter abbreviation
        $days['two'] = ['Pü', 'Es', 'Te', 'Ko', 'Ne', 'Re', 'La'];
        // The one letter abbreviation
        $days['letter'] = ['P', 'E', 'T', 'K', 'N', 'R', 'L'];

        $this->Days = $days;

        return $this->Days;
    }

    /**
     * @return array
     */
    protected function _LoadMonths()
    {
        $months = parent::_LoadMonths();

        /***
         * MONTH NAMES
         * All of these arrays MUST start with January as the first element
         * and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'November', 'Detsember'];
        // The three letter month name
        $months['abbr'] = ['Jaa', 'Vee', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Det'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Ö', 'Ä', 'Õ', 'Ü'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ee';
    }
}
