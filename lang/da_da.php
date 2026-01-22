<?php

require_once('Language.php');
require_once('en_gb.php');

class da_da extends en_gb
{
    /**
     * @return array
     */
    protected function _LoadDates()
    {
        $dates = parent::_LoadDates();

        $dates['general_date'] = 'j/n-Y';
        $dates['general_datetime'] = 'j/n-Y G.i.s';
        $dates['short_datetime'] = 'j/n-y G.i';
        $dates['schedule_daily'] = 'l, j/n-y';
        $dates['reservation_email'] = 'j/n-Y @ G.i (e)';
        $dates['res_popup'] = 'D, j/n G.i';
        $dates['res_popup_time'] = 'G.i';
        $dates['short_reservation_date'] = 'j/n-y G.i';
        $dates['dashboard'] = 'l, j/n G.i';
        $dates['period_time'] = 'G.i';
        $dates['mobile_reservation_date'] = 'j/n G.i';
        $dates['general_date_js'] = 'dd/mm-yy';
        $dates['general_time_js'] = 'H.mm tt';
        $dates['momentjs_datetime'] = 'D/M-YY H.mm';
        $dates['calendar_time'] = 'H:mmt';
        $dates['calendar_dates'] = 'j. M';
        $dates['embedded_date'] = 'D j.';
        $dates['embedded_time'] = 'G.i';
        $dates['embedded_datetime'] = 'j/n G.i';
        $dates['report_date'] = '%d/%m';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Fornavn';
        $strings['LastName'] = 'Efternavn';
        $strings['Timezone'] = 'Tidszone';
        $strings['Edit'] = 'Rediger';
        $strings['Change'] = 'Skift';
        $strings['Rename'] = 'Omdøb';
        $strings['Remove'] = 'Fjern';
        $strings['Delete'] = 'Slet';
        $strings['Update'] = 'Opdatér';
        $strings['Cancel'] = 'Annuller';
        $strings['Add'] = 'Tilføj';
        $strings['Name'] = 'Navn';
        $strings['Yes'] = 'Ja';
        $strings['No'] = 'Nej';
        $strings['FirstNameRequired'] = 'Fornavn skal udfyldes';
        $strings['LastNameRequired'] = 'Efternavn skal udfyldes';
        $strings['PwMustMatch'] = 'De to kodeord skal være ens.';
        $strings['ValidEmailRequired'] = 'Du skal indtaste en gyldig e-mailadresse.';
        $strings['UniqueEmailRequired'] = 'Denne e-mailadresse er allerede registreret.';
        $strings['UniqueUsernameRequired'] = 'Dette brugernavnet er allerede taget.';
        $strings['UserNameRequired'] = 'Brugernavn skal udfyldes';
        $strings['CaptchaMustMatch'] = 'Indtast tegnene nøjagtigt som vist.';
        $strings['Today'] = 'I dag';
        $strings['Week'] = 'Uge';
        $strings['Month'] = 'Måned';
        $strings['BackToCalendar'] = 'Tilbage til kalenderen';
        $strings['BeginDate'] = 'Begynder';
        $strings['EndDate'] = 'Slutter';
        $strings['Username'] = 'Brugernavn';
        $strings['Password'] = 'Adgangskode';
        $strings['PasswordConfirmation'] = 'Bekræft adgangskoden';
        $strings['DefaultPage'] = 'Startside';
        $strings['MyCalendar'] = 'Mine reservationer';
        $strings['ScheduleCalendar'] = 'Reservationer';
        $strings['Registration'] = 'Registrering';
        $strings['NoAnnouncements'] = 'Der er ingen meddelelser';
        $strings['Announcements'] = 'Meddelelser';
        $strings['NoUpcomingReservations'] = 'Du har ingen reservationer';
        $strings['UpcomingReservations'] = 'Kommende reservationer';
        $strings['AllNoUpcomingReservations'] = 'Der er ingen reservationer de næste %s dage';
        $strings['AllUpcomingReservations'] = 'Alle kommende reservationer';
        $strings['ShowHide'] = 'Vis/Skjul';
        $strings['Error'] = 'Fejl';
        $strings['ReturnToPreviousPage'] = 'Tilbage til forrige side';
        $strings['UnknownError'] = 'Ukendt fejl';
        $strings['InsufficientPermissionsError'] = 'Du har ikke adgang til denne facilitet';
        $strings['MissingReservationResourceError'] = 'Du har ikke valgt en facilitet';
        $strings['MissingReservationScheduleError'] = 'Du har ikke valgt en tidsplan';
        $strings['DoesNotRepeat'] = 'Gentages ikke';
        $strings['Daily'] = 'Dagligt';
        $strings['Weekly'] = 'Ugentligt';
        $strings['Monthly'] = 'Månedligt';
        $strings['Yearly'] = 'Årligt';
        $strings['RepeatPrompt'] = 'Gentag ';
        $strings['hours'] = 'timer';
        $strings['days'] = 'dage';
        $strings['weeks'] = 'uger';
        $strings['months'] = 'måneder';
        $strings['years'] = 'år';
        $strings['day'] = 'dag';
        $strings['week'] = 'uge';
        $strings['month'] = 'måned';
        $strings['year'] = 'år';
        $strings['repeatDayOfMonth'] = 'dag i måneden';
        $strings['repeatDayOfWeek'] = 'ugedag';
        $strings['RepeatUntilPrompt'] = 'Indtil';
        $strings['RepeatEveryPrompt'] = 'Hver';
        $strings['RepeatDaysPrompt'] = 'På';
        $strings['CreateReservationHeading'] = 'Ny reservation';
        $strings['EditReservationHeading'] = 'Rediger reservation %s';
        $strings['ViewReservationHeading'] = 'Vis reservation %s';
        $strings['ReservationErrors'] = 'Ændre reservation';
        $strings['Create'] = 'Opret';
        $strings['ThisInstance'] = 'Kun denne dato';
        $strings['AllInstances'] = 'Alle datoer';
        $strings['FutureInstances'] = 'Kun fremtidige datoer';
        $strings['Print'] = 'Udskriv';
        $strings['ShowHideNavigation'] = 'Vis/Skjul navigation';
        $strings['ReferenceNumber'] = 'Referencenummer';
        $strings['Tomorrow'] = 'I morgen';
        $strings['LaterThisWeek'] = 'Senere i denne uge';
        $strings['NextWeek'] = 'Næste uge';
        $strings['SignOut'] = 'Log ud';
        $strings['LayoutDescription'] = 'Begynder på %s, og viser %s dage ad gangen';
        $strings['AllResources'] = 'Alle faciliteter';
        $strings['TakeOffline'] = 'Gå offline';
        $strings['BringOnline'] = 'Gå online';
        $strings['AddImage'] = 'Tilføj et billede';
        $strings['NoImage'] = 'Der intet billede tilknyttet';
        $strings['Move'] = 'Flyt';
        $strings['AppearsOn'] = 'Vises den %s';
        $strings['Location'] = 'Adresse';
        $strings['NoLocationLabel'] = '(Ingen adresse angivet)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(Ingen kontaktinformationer)';
        $strings['Description'] = 'Beskrivelse';
        $strings['NoDescriptionLabel'] = '(ingen beskrivelse)';
        $strings['Notes'] = 'Noter';
        $strings['NoNotesLabel'] = '(ingen noter)';
        $strings['NoTitleLabel'] = '(ingen overskrift)';
        $strings['UsageConfiguration'] = 'Brugerindstillinger';
        $strings['ChangeConfiguration'] = 'Ændre indstillinger';
        $strings['ResourceMinLength'] = 'Reservationer skal vare mindst %s';
        $strings['ResourceMinLengthNone'] = 'Der er ingen minimum varighed for en reservation';
        $strings['ResourceMaxLength'] = 'En reservation kan højest vare %s';
        $strings['ResourceMaxLengthNone'] = 'Der er ingen maksimal varighed for en reservation';
        $strings['ResourceRequiresApproval'] = 'Reservationer skal godkendes';
        $strings['ResourceRequiresApprovalNone'] = 'Reservationer kræver ikke godkendelse';
        $strings['ResourcePermissionAutoGranted'] = 'Der gives automatisk adgang';
        $strings['ResourcePermissionNotAutoGranted'] = 'Der gives ikke automatisk adgang';
        $strings['ResourceMinNotice'] = 'Reservationer skal oprettes senest %s før de begynder';
        $strings['ResourceMinNoticeNone'] = 'Reservationer kan oprettes frem til de begynder';
        $strings['ResourceMinNoticeUpdate'] = 'Reservationer skal opdateres senest %s før de begynder';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Reservationer kan opdateres frem til de begynder';
        $strings['ResourceMinNoticeDelete'] = 'Reservationer kan kun slettes op til %s før de begynder';
        $strings['ResourceMinNoticeNoneDelete'] = 'Reservationer skal slettes senest %s før de begynder';
        $strings['ResourceMaxNotice'] = 'Reservationer kan ikke stoppe tidligere end %s fra den aktuelle tid';
        $strings['ResourceMaxNoticeNone'] = 'Reservationer kan slutte på ethvert tidspunkt i fremtiden';
        $strings['ResourceBufferTime'] = 'Der skal være %s mellem reservationer';
        $strings['ResourceBufferTimeNone'] = 'Der kræves intet tidsrum mellem reservationer';
        $strings['ResourceAllowMultiDay'] = 'Reservationer kan løbe over flere dage';
        $strings['ResourceNotAllowMultiDay'] = 'Reservationer kan ikke løbe over flere dage';
        $strings['ResourceCapacity'] = 'Denne facilitet har en kapacitet på %s personer';
        $strings['ResourceCapacityNone'] = 'Denne facilitet har ubegrænset kapacitet';
        $strings['AddNewResource'] = 'Tilføj ny facilitet';
        $strings['AddNewUser'] = 'Tilføj ny bruger';
        $strings['AddResource'] = 'Tilføj facilitet';
        $strings['Capacity'] = 'Kapacitet';
        $strings['Access'] = 'Adgang';
        $strings['Duration'] = 'Varighed';
        $strings['Active'] = 'Aktiv';
        $strings['Inactive'] = 'Inaktiv';
        $strings['ResetPassword'] = 'Nulstil adgangskode';
        $strings['LastLogin'] = 'Sidste login';
        $strings['Search'] = 'Søg';
        $strings['ResourcePermissions'] = 'Tilladelser til faciliteter';
        $strings['Reservations'] = 'Reservationer';
        $strings['Groups'] = 'Grupper';
        $strings['Users'] = 'Brugere';
        $strings['AllUsers'] = 'Alle brugere';
        $strings['AllGroups'] = 'Alle grupper';
        $strings['AllSchedules'] = 'Alle tidsplaner';
        $strings['UsernameOrEmail'] = 'Brugernavn eller e-mail';
        $strings['Members'] = 'Medlemmer';
        $strings['QuickSlotCreation'] = 'Opret tidsintervaller hver %s minut mellem %s og %s';
        $strings['ApplyUpdatesTo'] = 'Anvend opdateringer på';
        $strings['CancelParticipation'] = 'Afmeld deltagelse';
        $strings['Attending'] = 'Deltager';
        $strings['QuotaConfiguration'] = 'På %s for %s brugere i %s er begrænset til %s %s per %s';
        $strings['QuotaEnforcement'] = 'Tvungen %s %s';
        $strings['reservations'] = 'reservationer';
        $strings['reservation'] = 'reservation';
        $strings['ChangeCalendar'] = 'Vælg kalender';
        $strings['AddQuota'] = 'Tilføj begrænsning';
        $strings['FindUser'] = 'Find bruger';
        $strings['Created'] = 'Opret';
        $strings['LastModified'] = 'Sidst ændret';
        $strings['GroupName'] = 'Gruppenavn';
        $strings['GroupMembers'] = 'Gruppemedlemmer';
        $strings['GroupRoles'] = 'Roller tildelt gruppe';
        $strings['GroupAdmin'] = 'Gruppeadministrator';
        $strings['Actions'] = 'Handlinger';
        $strings['CurrentPassword'] = 'Nuværende adgangskode';
        $strings['NewPassword'] = 'Ny adgangskode';
        $strings['InvalidPassword'] = 'Nuværende adgangskoden er forkert';
        $strings['PasswordChangedSuccessfully'] = 'Din adgangskode er nu ændret';
        $strings['SignedInAs'] = 'Logget ind som';
        $strings['NotSignedIn'] = 'Du er ikke logget ind';
        $strings['ReservationTitle'] = 'Overskrift';
        $strings['ReservationDescription'] = 'Beskrivelse';
        $strings['ResourceList'] = 'Faciliteter som skal reserveres';
        $strings['Accessories'] = 'Udstyr';
        $strings['InvitationList'] = 'Inviterede';
        $strings['AccessoryName'] = 'Navn på udstyr';
        $strings['QuantityAvailable'] = 'Antal tilgængelige';
        $strings['Resources'] = 'Faciliteter';
        $strings['Participants'] = 'Deltagere';
        $strings['User'] = 'Bruger';
        $strings['Resource'] = 'Facilitet';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Godkend';
        $strings['Page'] = 'Side';
        $strings['Rows'] = 'Rækker';
        $strings['Unlimited'] = 'Ubegrænsede';
        $strings['Email'] = 'E-mail';
        $strings['EmailAddress'] = 'E-mailadresse';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organisation';
        $strings['Position'] = 'Adresse';
        $strings['Language'] = 'Sprog';
        $strings['Permissions'] = 'Tilladelser';
        $strings['Reset'] = 'Nulstil';
        $strings['FindGroup'] = 'Find gruppe';
        $strings['Manage'] = 'Håndter';
        $strings['None'] = 'Ingen';
        $strings['AddToOutlook'] = 'Føj til kalender';
        $strings['Done'] = 'Færdig';
        $strings['RememberMe'] = 'Husk mig';
        $strings['FirstTimeUser?'] = 'Ny bruger';
        $strings['CreateAnAccount'] = 'Opret en konto';
        $strings['ViewSchedule'] = 'Se reservationer';
        $strings['ForgotMyPassword'] = 'Jeg har glemt min adgangskode';
        $strings['YouWillBeEmailedANewPassword'] = 'Du modtager en e-mail med en tilfældig genereret adgangskode';
        $strings['Close'] = 'Luk';
        $strings['ExportToCSV'] = 'Eksporter til CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Arbejder...';
        $strings['Login'] = 'Login';
        $strings['AdditionalInformation'] = 'Beskrivelse';
        $strings['AllFieldsAreRequired'] = 'Alle felter skal udfyldes';
        $strings['Optional'] = 'valgfrit';
        $strings['YourProfileWasUpdated'] = 'Din profil er opdateret';
        $strings['YourSettingsWereUpdated'] = 'Dine indstillinger er opdaterede';
        $strings['Register'] = 'Tilmeld';
        $strings['SecurityCode'] = 'Sikkerhedskode';
        $strings['ReservationCreatedPreference'] = 'Når jeg opretter en reservation, eller en reservation bliver oprettet på mine vegne';
        $strings['ReservationUpdatedPreference'] = 'Når jeg opdaterer en reservation, eller en reservation bliver opdateret på mine vegne';
        $strings['ReservationDeletedPreference'] = 'Når jeg sletter en reservation, eller en reservation bliver slettet på mine vegne';
        $strings['ReservationApprovalPreference'] = 'Når min reservation bliver godkendt';
        $strings['PreferenceSendEmail'] = 'Send e-mail';
        $strings['PreferenceNoEmail'] = 'Giv ikke besked';
        $strings['ReservationCreated'] = 'Din reservation er oprettet!';
        $strings['ReservationUpdated'] = 'Din reservation er opdateret!';
        $strings['ReservationRemoved'] = 'Din reservation er fjernet!';
        $strings['ReservationRequiresApproval'] = 'En eller flere af dine reservationer kræver godkendelses, før den er gældende. Den/disse afventer derfor godkendelse';
        $strings['YourReferenceNumber'] = 'Dit referencenummer er %s';
        $strings['ChangeUser'] = 'Ændre bruger';
        $strings['MoreResources'] = 'Flere faciliteter';
        $strings['ReservationLength'] = 'Reservationens varighed';
        $strings['ParticipantList'] = 'Deltagerliste';
        $strings['AddParticipants'] = 'Tilføj deltagere';
        $strings['InviteOthers'] = 'Inviter andre';
        $strings['AddResources'] = 'Tilføj faciliteter';
        $strings['AddAccessories'] = 'Tilføj udstyr';
        $strings['Accessory'] = 'Udstyr';
        $strings['QuantityRequested'] = 'Ønsket antal';
        $strings['CreatingReservation'] = 'Opret reservation';
        $strings['UpdatingReservation'] = 'Opdater reservation';
        $strings['DeleteWarning'] = 'Denne handling er permanent og kan ikke fortrydes!';
        $strings['DeleteAccessoryWarning'] = 'Hvis du sletter dette udstyr, fjernes det fra alle reservationer';
        $strings['AddAccessory'] = 'Tilføj udstyr';
        $strings['AddBlackout'] = 'Tilføj lukket tidsrum';
        $strings['AllResourcesOn'] = 'Alle faciliteter på';
        $strings['Reason'] = 'Årsag';
        $strings['BlackoutShowMe'] = 'Vis mig reservationer i konflikt';
        $strings['BlackoutDeleteConflicts'] = 'Slet reservationer i konflikt';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Mellem';
        $strings['CreatedBy'] = 'Oprettet af';
        $strings['BlackoutCreated'] = 'Lukket tidsrum, er oprettet';
        $strings['BlackoutNotCreated'] = 'Lukket tidsrum, kunne ikke oprettes';
        $strings['BlackoutUpdated'] = 'Lukket tidsrum, er opdateret';
        $strings['BlackoutNotUpdated'] = 'Lukket tidsrum, kunne ikke opdateres';
        $strings['BlackoutConflicts'] = 'Der er konflikt mellem lukkede tidsrum.';
        $strings['ReservationConflicts'] = 'Der er reservationstider i konflikt.';
        $strings['UsersInGroup'] = 'Brugere i denne gruppe';
        $strings['Browse'] = 'Gennemse';
        $strings['DeleteGroupWarning'] = 'Hvis du sletter denne gruppe, fjerner du alle tilladelser, der er tildelt gruppen. Brugere i denne gruppe kan miste adgang til faciliteter.';
        $strings['WhatRolesApplyToThisGroup'] = 'Hvilke roller er tildelt denne gruppe?';
        $strings['WhoCanManageThisGroup'] = 'Hvem kan foretage ændringer i denne gruppe?';
        $strings['WhoCanManageThisSchedule'] = 'Hvem kan foretage ændringer i denne tidsplan?';
        $strings['AllQuotas'] = 'Alle begrænsninger';
        $strings['QuotaReminder'] = 'Husk: Begrænsninger bliver håndhævet i forhold til tidsplaners tidszone';
        $strings['AllReservations'] = 'Alle reservationer';
        $strings['PendingReservations'] = 'Reservationer, der afventer godkendelse';
        $strings['Approving'] = 'Godkendelse';
        $strings['MoveToSchedule'] = 'Flyt til tidsplan';
        $strings['DeleteResourceWarning'] = 'Hvis du sletter denne facilitet, slettes alle tilknyttede data, herunder:';
        $strings['DeleteResourceWarningReservations'] = 'alle tidligere, nuværende og fremtidige reservationer';
        $strings['DeleteResourceWarningPermissions'] = 'alle tilladelser, der er givet';
        $strings['DeleteResourceWarningReassign'] = 'Reservationer, tilladelser og lign. som du ikke ønsker bliver slettet, skal tilknyttes en anden facilitet, før du fortsætter.';
        $strings['ScheduleLayout'] = 'Tidsplaner (alle tider %s)';
        $strings['ReservableTimeSlots'] = 'Tidsrum, der kan reserveres';
        $strings['BlockedTimeSlots'] = ' Tidsrum, der ikke kan reserveres';
        $strings['ThisIsTheDefaultSchedule'] = 'Dette er standardtidsplanen';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Standardtidsplanen kan ikke slettes';
        $strings['MakeDefault'] = 'Anvend som standard';
        $strings['BringDown'] = 'Flyt ned';
        $strings['ChangeLayout'] = 'Skift tidsplan';
        $strings['AddSchedule'] = 'Tilføj ny tidsplan';
        $strings['StartsOn'] = 'Begynder på';
        $strings['NumberOfDaysVisible'] = 'Antallet af synlige dage';
        $strings['UseSameLayoutAs'] = 'Brug samme tidsplan som';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Valgfrit felt';
        $strings['LayoutInstructions'] = 'Tilføj ét tidsinterval pr. linje. Tidsintervallerne skal dække alle 24 timer i døgnet og begynde og slutte kl. 00:00';
        $strings['AddUser'] = 'Tilføj bruger';
        $strings['UserPermissionInfo'] = 'Adgang til faciliteter, er dog afhængig af brugerrolle, gruppetilladelser eller eksterne indstillinger.';
        $strings['DeleteUserWarning'] = 'Hvis du sletter denne bruger, slettes dennes tidligere, nuværende og fremtidige reservationer';
        $strings['AddAnnouncement'] = 'Tilføj en meddelelse';
        $strings['Announcement'] = 'Meddelelse';
        $strings['Priority'] = 'Prioritet';
        $strings['Reservable'] = 'Ledig';
        $strings['Unreservable'] = 'Lukket tidsrum';
        $strings['Reserved'] = 'Reserveret';
        $strings['MyReservation'] = 'Min reservation';
        $strings['Pending'] = 'Afventer godkendelse';
        $strings['Past'] = 'Tidligere';
        $strings['Restricted'] = 'Begrænset';
        $strings['ViewAll'] = 'Se alle';
        $strings['MoveResourcesAndReservations'] = 'Flyt faciliteter og reservationer til';
        $strings['TurnOffSubscription'] = 'Ikke offentligt';
        $strings['TurnOnSubscription'] = 'Offentligt (RSS, iCalendar, Tablet, Monitor)';
        $strings['SubscribeToCalendar'] = 'Abonner på denne kalender';
        $strings['SubscriptionsAreDisabled'] = 'Det er ikke muligt at abonnere på kalendere';
        $strings['NoResourceAdministratorLabel'] = '(ingen administrator)';
        $strings['WhoCanManageThisResource'] = 'Hvem kan ændre indstillingerne for denne facilitet?';
        $strings['ResourceAdministrator'] = 'Administrator for faciliteter';
        $strings['Private'] = 'Privat';
        $strings['Accept'] = 'Godkend';
        $strings['Decline'] = 'Afvis';
        $strings['ShowFullWeek'] = 'Vis hele ugen';
        $strings['CustomAttributes'] = 'Brugerdefinerede oplysningsfelter';
        $strings['AddAttribute'] = 'Opret et oplysningsfelt';
        $strings['EditAttribute'] = 'Opdater et oplysningsfelt';
        $strings['DisplayLabel'] = 'Etiket';
        $strings['Type'] = 'Type';
        $strings['Required'] = 'Påkrævet';
        $strings['ValidationExpression'] = 'Udtryk til godkendelse';
        $strings['PossibleValues'] = 'Mulige værdier';
        $strings['SingleLineTextbox'] = 'Tekstboks med én linje';
        $strings['MultiLineTextbox'] = 'Tekstboks med flere linjer';
        $strings['Checkbox'] = 'Afkrydsningsfelt';
        $strings['SelectList'] = 'Vælg liste';
        $strings['CommaSeparated'] = 'kommaseparerede';
        $strings['Category'] = 'Kategori';
        $strings['CategoryReservation'] = 'Reservation';
        $strings['CategoryGroup'] = 'Gruppe';
        $strings['SortOrder'] = 'Rækkefølge';
        $strings['Title'] = 'Overskrift';
        $strings['AdditionalAttributes'] = 'Flere oplysningsfelter';
        $strings['True'] = 'Sandt';
        $strings['False'] = 'Falsk';
        $strings['ForgotPasswordEmailSent'] = 'Du modtager en e-mail med instruktioner i, hvordan du nulstiller dit kodeord';
        $strings['ActivationEmailSent'] = 'Du modtager snart en aktiverings-e-mail.';
        $strings['AccountActivationError'] = 'Vi kunne desværre ikke aktivere din konto, vi beklager.';
        $strings['Attachments'] = 'Vedhæftede filer';
        $strings['AttachFile'] = 'Vedhæft fil';
        $strings['Maximum'] = 'maksimal';
        $strings['NoScheduleAdministratorLabel'] = 'Ingen administrator for tidsplaner';
        $strings['ScheduleAdministrator'] = 'Administrator for tidsplaner';
        $strings['Total'] = 'I alt';
        $strings['QuantityReserved'] = 'Antal reserverede';
        $strings['AllAccessories'] = 'Al udstyr';
        $strings['GetReport'] = 'Hent rapport';
        $strings['NoResultsFound'] = 'Der blev ikke fundet noget, som svarer til din søgning';
        $strings['SaveThisReport'] = 'Gem denne rapport';
        $strings['ReportSaved'] = 'Rapporten blev gemt';
        $strings['EmailReport'] = 'Send rapporten med e-mail';
        $strings['ReportSent'] = 'Rapporten er afsendt';
        $strings['RunReport'] = 'Kør rapport';
        $strings['NoSavedReports'] = 'Du har ingen gemte rapporter';
        $strings['CurrentWeek'] = 'Denne uge';
        $strings['CurrentMonth'] = 'Denne måned';
        $strings['AllTime'] = 'Alle tidspunkter';
        $strings['FilterBy'] = 'Sorter ud fra';
        $strings['Select'] = 'Vælg';
        $strings['List'] = 'Liste';
        $strings['TotalTime'] = 'Tid';
        $strings['Count'] = 'Tæl';
        $strings['Usage'] = 'Brug';
        $strings['AggregateBy'] = 'Saml, ud fra';
        $strings['Range'] = 'Rækkevidde';
        $strings['Choose'] = 'Vælg';
        $strings['All'] = 'Alt';
        $strings['ViewAsChart'] = 'Se som diagram';
        $strings['ReservedResources'] = 'Reserverede faciliteter';
        $strings['ReservedAccessories'] = 'Reserveret udstyr';
        $strings['ResourceUsageTimeBooked'] = 'Brug af facilitet - tid reserveret';
        $strings['ResourceUsageReservationCount'] = 'Brug af facilitet - antal reservationer';
        $strings['Top20UsersTimeBooked'] = 'Top 20 brugere - tid reserveret';
        $strings['Top20UsersReservationCount'] = 'Top 20 brugere - antal reservationer';
        $strings['ConfigurationUpdated'] = 'Konfigurationsfilen er opdateret';
        $strings['ConfigurationUiNotEnabled'] = 'Der er ikke adgang til denne side fordi $conf[\'settings\'][\'pages\'][\'enable.configuration\'] enten er indstillet til falsk eller helt mangler.';
        $strings['ConfigurationFileNotWritable'] = 'Der er ikke skriverettigheder på config-filen. Tjek tilladelserne på denne fil og prøv forfra';
        $strings['ConfigurationUpdateHelp'] = 'Få hjælp til disse indstillinger:  <a target=_blank href=%s>Help File</a> for vejledning om disse indstillinger.';
        $strings['GeneralConfigSettings'] = 'indstillinger';
        $strings['UseSameLayoutForAllDays'] = 'Brug dne samme tidsplan for alle dage';
        $strings['LayoutVariesByDay'] = 'Tidsplanerne skifter fra dag til dag';
        $strings['ManageReminders'] = 'Påmindelser';
        $strings['ReminderUser'] = 'Bruger-ID';
        $strings['ReminderMessage'] = 'Besked';
        $strings['ReminderAddress'] = 'Adresser';
        $strings['ReminderSendtime'] = 'Afsendelsestidspunkt';
        $strings['ReminderRefNumber'] = 'Reservationens referencenummer';
        $strings['ReminderSendtimeDate'] = 'Dato for påmindelser';
        $strings['ReminderSendtimeTime'] = 'Tidspunkt for påmindelser (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Tilføj en påmindelse';
        $strings['DeleteReminderWarning'] = 'Er du sikker på, du vil slette dette?';
        $strings['NoReminders'] = 'Du har ingen kommende påmindelser';
        $strings['Reminders'] = 'Påmindelser';
        $strings['SendReminder'] = 'Afsend påmindelse';
        $strings['minutes'] = 'minutter';
        $strings['hours'] = 'timer';
        $strings['days'] = 'dage';
        $strings['ReminderBeforeStart'] = 'før begyndelsestidspunktet';
        $strings['ReminderBeforeEnd'] = 'før afslutningstidspunktet';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS-fil';
        $strings['ThemeUploadSuccess'] = 'Dine ændringer er gemt. Genindlæs siden, for at se ændringerne';
        $strings['MakeDefaultSchedule'] = 'Gør dette til min standardtidsplan';
        $strings['DefaultScheduleSet'] = 'Dette er nu din standardtidplan';
        $strings['FlipSchedule'] = 'Byt om på tidsplaner';
        $strings['Next'] = 'Næste';
        $strings['Success'] = 'Vellykket';
        $strings['Participant'] = 'Deltager';
        $strings['ResourceFilter'] = 'Filter';
        $strings['ResourceGroups'] = 'Facilitetsgrupper';
        $strings['AddNewGroup'] = 'Tilføj en ny gruppe';
        $strings['Quit'] = 'Afslut';
        $strings['AddGroup'] = 'Tilføj gruppe';
        $strings['StandardScheduleDisplay'] = 'Brug standardvisning';
        $strings['TallScheduleDisplay'] = 'Brug høj visning';
        $strings['WideScheduleDisplay'] = 'Brug bred visning';
        $strings['CondensedWeekScheduleDisplay'] = 'Brug komprimeret ugevisning';
        $strings['ResourceGroupHelp1'] = 'Flyt rundt på facilitetsgrupper ved at hjælp af træk og slip.';
        $strings['ResourceGroupHelp2'] = 'Højreklik på en facilitets gruppenavn, for at se flere muligheder';
        $strings['ResourceGroupHelp3'] = 'Træk og slip faciliteter for at tilføje dem til grupper';
        $strings['ResourceGroupWarning'] = 'Hvis du bruger facilitetsgrupper, skal hver facilitet være tilknyttet mindst én gruppe. Faciliteter, som ikke er tilknyttet en gruppe, kan ikke reserveres';
        $strings['ResourceType'] = 'Facilitetstype';
        $strings['AppliesTo'] = 'Gælder for';
        $strings['UniquePerInstance'] = 'Hver forekomst er unikt';
        $strings['AddResourceType'] = 'Tilføj en facilitetstype';
        $strings['NoResourceTypeLabel'] = '(der er ikke indstillet en facilitetstype)';
        $strings['ClearFilter'] = 'Nulstil filter';
        $strings['MinimumCapacity'] = 'Minimum kapacitet';
        $strings['Color'] = 'Farve';
        $strings['Available'] = 'Ledig';
        $strings['Unavailable'] = 'Optaget';
        $strings['Hidden'] = 'Skjult';
        $strings['ResourceStatus'] = 'Status for facilitet';
        $strings['CurrentStatus'] = 'Nuværende status';
        $strings['AllReservationResources'] = 'Alle faciliteter som kan reserveres';
        $strings['File'] = 'Fil';
        $strings['BulkResourceUpdate'] = 'Multiopdatering';
        $strings['Unchanged'] = 'Uændret';
        $strings['Common'] = 'Almindelig';
        $strings['AdminOnly'] = 'Kun administratorer';
        $strings['AdvancedFilter'] = 'Avanceret filter';
        $strings['MinimumQuantity'] = 'Minimumsantal';
        $strings['MaximumQuantity'] = 'Maksimumantal';
        $strings['ChangeLanguage'] = 'Vælg sprog';
        $strings['AddRule'] = 'Tilføj regel';
        $strings['Attribute'] = 'Oplysningsfelt';
        $strings['RequiredValue'] = 'Påkrævet værdi';
        $strings['ReservationCustomRuleAdd'] = 'Brug denne farve, når oplysningsfeltet er indstillet til den følgende værdi';
        $strings['AddReservationColorRule'] = 'Tilføj en farveregel';
        $strings['LimitAttributeScope'] = 'Indsaml i specifikke tilfælder';
        $strings['CollectFor'] = 'Indsaml for';
        $strings['SignIn'] = 'Log ind';
        $strings['AllParticipants'] = 'Alle deltagere';
        $strings['RegisterANewAccount'] = 'Opret en ny profil';
        $strings['Dates'] = 'Datoer';
        $strings['More'] = 'Mere';
        $strings['ResourceAvailability'] = 'Faciliteter er ledige';
        $strings['UnavailableAllDay'] = 'Optaget hele dagen';
        $strings['AvailableUntil'] = 'Ledig indtil';
        $strings['AvailableBeginningAt'] = 'Ledig fra';
        $strings['AvailableAt'] = 'Ledig kl.';
        $strings['AllResourceTypes'] = 'Alle typer af faciliteter';
        $strings['AllResourceStatuses'] = 'Status for alle faciliteter';
        $strings['AllowParticipantsToJoin'] = 'Tillad deltagere at tilmelde sig';
        $strings['Join'] = 'Tilmeld';
        $strings['YouAreAParticipant'] = 'Du er deltager til denne reservation';
        $strings['YouAreInvited'] = 'Du er inviteret til denne reservation';
        $strings['YouCanJoinThisReservation'] = 'Du kan tilmelde dig denne reservation';
        $strings['Import'] = 'Importer';
        $strings['GetTemplate'] = 'Hent skabelon';
        $strings['UserImportInstructions'] = 'Filen skal være i CSV format.</li><li>Brugernavn og e-mail SKAL udfyldes.</li><li>Validering for oplysningsfelter bliver ikke håndhævet</li><li>Felter som ikke udfyldes får tildelt en standardværdi og \'kodeord\' som brugerens\brugernes kodeord.</li><li>Brug den medfølgende skabelon, som eksempel.</li></ul>';
        $strings['RowsImported'] = 'Rækker importeret';
        $strings['RowsSkipped'] = 'Rækker sprunget over';
        $strings['Columns'] = 'Kolonner';
        $strings['Reserve'] = 'Reserver';
        $strings['AllDay'] = 'Hele dagen';
        $strings['Everyday'] = 'Altid';
        $strings['IncludingCompletedReservations'] = 'Medtag gennemførte reservationer';
        $strings['NotCountingCompletedReservations'] = 'Medtag ikke gennemførte reservationer';
        $strings['RetrySkipConflicts'] = 'Undlad reservationer, som er i konflikt';
        $strings['Retry'] = 'Prøv igen';
        $strings['RemoveExistingPermissions'] = 'Fjern nuværende tilladelser';
        $strings['Continue'] = 'Fortsæt';
        $strings['WeNeedYourEmailAddress'] = 'For at kunne reservere, skal vi bruge din e-mailadresse';
        $strings['ResourceColor'] = 'Farve for Facilitet';
        $strings['DateTime'] = 'Dato/tid';
        $strings['AutoReleaseNotification'] = 'Hvis der ikke er tjekket ind inden %s minutter, slettes den automatisk.';
        $strings['RequiresCheckInNotification'] = 'Kræver at der tjekkes ind/ud';
        $strings['NoCheckInRequiredNotification'] = 'Kræver ikke at der tjekkes ind/ud';
        $strings['RequiresApproval'] = 'Skal godkendes';
        $strings['CheckingIn'] = 'Tjekker ind';
        $strings['CheckingOut'] = 'Tjekker ud';
        $strings['CheckIn'] = 'Tjek ind';
        $strings['CheckOut'] = 'Tjek ud';
        $strings['ReleasedIn'] = 'Bliver ledig om';
        $strings['CheckedInSuccess'] = 'Du er tjekket ind';
        $strings['CheckedOutSuccess'] = 'Du er tjekket ud';
        $strings['CheckInFailed'] = 'Du kunne ikke blive tjekket ind';
        $strings['CheckOutFailed'] = 'Du kunne ikke blive tjekket ud';
        $strings['CheckInTime'] = 'Tidspunkt for tjek ind';
        $strings['CheckOutTime'] = 'Tidspunkt for tjek ud';
        $strings['OriginalEndDate'] = 'Afslutningsdato';
        $strings['SpecificDates'] = 'Vis bestemte datoer';
        $strings['Users'] = 'Brugere';
        $strings['Guest'] = 'Gæst';
        $strings['ResourceDisplayPrompt'] = 'Facilitet der skal vises';
        $strings['Credits'] = 'Mønter';
        $strings['AvailableCredits'] = 'Mønter til rådig';
        $strings['CreditUsagePerSlot'] = 'Bruger %s mønter pr. tidsinterval (uden for spidsbelastninger)';
        $strings['PeakCreditUsagePerSlot'] = 'Bruger %s mønter pr. tidsinterval (under spidsbelastning)';
        $strings['CreditsRule'] = 'Du har ikke mønter nok. Der skal bruges %s mønter. Du har %s mønter på din konto';
        $strings['PeakTimes'] = 'Tid med spidsbelastning';
        $strings['AllYear'] = 'Hele året';
        $strings['MoreOptions'] = 'Flere muligheder';
        $strings['SendAsEmail'] = 'Send som e-mail';
        $strings['UsersInGroups'] = 'Brugere i grupper';
        $strings['UsersWithAccessToResources'] = 'Brugere med adgang til faciliteter';
        $strings['AnnouncementSubject'] = '%s har tilføjet en meddelelse';
        $strings['AnnouncementEmailNotice'] = 'brugere vil få denne meddelelse sendt med e-mail';
        $strings['Day'] = 'Dag';
        $strings['NotifyWhenAvailable'] = 'Giv mig besked, når ledig';
        $strings['AddingToWaitlist'] = 'Tilføjer dig til ventelisten';
        $strings['WaitlistRequestAdded'] = 'Du får besked, hvis dette tidspunkt bliver ledigt';
        $strings['PrintQRCode'] = 'Udskriv QR-kode';
        $strings['FindATime'] = 'Find en tid';
        $strings['AnyResource'] = 'En hvilken som helst facilitet';
        $strings['ThisWeek'] = 'Denne uge';
        $strings['Hours'] = 'Timer';
        $strings['Minutes'] = 'Minutter';
        $strings['ImportICS'] = 'Importer fra ICS';
        $strings['ImportQuartzy'] = 'Importer fra Quartzy';
        $strings['OnlyIcs'] = 'Kun *.ics filer, kan uploades';
        $strings['IcsLocationsAsResources'] = 'Adressen vil blive importeret som en facilitet';
        $strings['IcsMissingOrganizer'] = 'Hvis en begivenhed ikke har en organisator, bliver den nuværende bruger sat som ejer.';
        $strings['IcsWarning'] = 'Når du importerer, bliver reglerne for reservationer ikke medtaget. Derfor er der mulighed for, at der opstår reservationer i konflikt som dobbeltbooking og lign.';
        $strings['BlackoutAroundConflicts'] = 'Lukket tidsrum, omkring reservationer i konflikt';
        $strings['DuplicateReservation'] = 'Kopier';
        $strings['UnavailableNow'] = 'Optaget nu';
        $strings['ReserveLater'] = 'Reserver senere';
        $strings['CollectedFor'] = 'Indsaml for';
        $strings['IncludeDeleted'] = 'Medtag slettede reservationer';
        $strings['Deleted'] = 'Slettet';
        $strings['Back'] = 'Tilbage';
        $strings['Forward'] = 'Næste';
        $strings['DateRange'] = 'Datointerval';
        $strings['Copy'] = 'Kopier';
        $strings['Detect'] = 'Undersøg';
        $strings['Autofill'] = 'Udfyld automatisk';
        $strings['NameOrEmail'] = 'navn eller e-mail';
        $strings['ImportResources'] = 'Importer faciliteter';
        $strings['ExportResources'] = 'Eksporter faciliteter';
        $strings['ResourceImportInstructions'] = '<ul><li>Filen skal være i CSV-format med UTF-8 kodning.</li><li>Navn SKAL udfyldes. Lader du andre felter stå tomme, vil standardværdier blive indsat. </li><li>Muligheder for at sætte status: \'Ledig\', \'Optaget\' og \'Skjult\'.</li><li>Farver bør være hex-værdier f.eks.#ffffff for hvid.</li><li>Kolonnerne for automatisk tildeling og godkendelse kan enten være sandt eller falsk.</li><li>Validering for oplysningsfelter håndhæves ikke.</li><li>Adskil flere facilitetsgrupper med komma.</li><li>Du kan angive varigheden i formatet #d#h#m eller HH:mm (1d3h30m eller 27:30 for 1 dag, 3 timer, 30 minutter)</li><li> Brug den medfølgende skabelon, som eksempel.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Filen skal være i CSV-format med UTF-8 kodning.</li><li>E-mailadresse, facilitetens navn, begyndelses- og sluttidspunkt SKAL udfyldes</li><li>Begyndelses- og sluttidspunkt skal være med fuld dato og klokkeslæt. Det anbefalede format for datoer er dd/mm-YYYY (31/12-2019) og for klokkeslæt: HH:mm (20:30).</li><li>Der bliver ved importen ikke tjekket for regler, konflikter og om tidsintervallerne er gyldige.</li><li>NDer bliver ikke sendt notifikationer.</li><li>Validering for oplysningsfelter håndhæves ikke.</li><li>Adskil flere facilitetsgrupper med komma.</li><li> Brug den medfølgende skabelon, som eksempel.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Minutter til ledig';
        $strings['CreditsPeak'] = 'Mønter (spidsbelastning)';
        $strings['CreditsOffPeak'] = 'Mønter (uden for spidsbelastning)';
        $strings['ResourceMinLengthCsv'] = 'Reservationen skal som minimum vare';
        $strings['ResourceMaxLengthCsv'] = 'Reservationen må maksimalt vare';
        $strings['ResourceBufferTimeCsv'] = 'Tidsrum mellem reservationer';
        $strings['ResourceMinNoticeAddCsv'] = 'Tilføj, korteste varslingstid for en reservation';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Korteste varslingstid for at opdatere en reservation';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Korteste varslingstid for at slette en reservation';
        $strings['ResourceMaxNoticeCsv'] = 'Maksimale varslingstid for en reservation';
        $strings['Export'] = 'Eksport';
        $strings['DeleteMultipleUserWarning'] = 'Hvis du sletter disse brugere, fjernes alle deres reservationer, både bagud- og fremadrettet. Der vil ikke blive sendt mails ud';
        $strings['DeleteMultipleReservationsWarning'] = 'Der bliver ikke sendt mails ud';
        $strings['ErrorMovingReservation'] = 'Der opstod en fejl, da du forsøgte at flytte en reservation';
        $strings['SelectUser'] = 'Vælg bruger';
        $strings['InviteUsers'] = 'Inviter brugere';
        $strings['InviteUsersLabel'] = 'Tilføj e-mailadressen på de personer, du vil invitere';
        $strings['ApplyToCurrentUsers'] = 'Gælder for de nuværende brugere';
        $strings['ReasonText'] = 'Årsag';
        $strings['NoAvailableMatchingTimes'] = 'Der er ingen ledige tider, der passer til din søgning';
        $strings['Schedules'] = 'Tidsplaner';
        $strings['NotifyUser'] = 'Giv brugeren besked';
        $strings['UpdateUsersOnImport'] = 'Hvis e-mailadressen allerede findes, opdateres brugeren';
        $strings['UpdateResourcesOnImport'] = 'Opdater eksisterende facilitet, hvis navnet findes';
        $strings['Reject'] = 'Afvis';
        $strings['CheckingAvailability'] = 'Undersøger ledige tider';
        $strings['CreditPurchaseNotEnabled'] = 'Du har ikke mulighed for at købe mønter';
        $strings['CreditsCost'] = 'Hver mønt koster';
        $strings['Currency'] = 'Betalingsmetode';
        $strings['PayPalClientId'] = 'Klient ID';
        $strings['PayPalSecret'] = 'Hemmelig';
        $strings['PayPalEnvironment'] = 'Miljø';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Live';
        $strings['StripePublishableKey'] = 'Publishable key';
        $strings['StripeSecretKey'] = 'Secret key';
        $strings['CreditsUpdated'] = 'Prisen på mønter er opdateret';
        $strings['GatewaysUpdated'] = 'Betalingportalerne er blevet opdaterede';
        $strings['PurchaseSummary'] = 'Oversigt over køb';
        $strings['EachCreditCosts'] = 'Hver mønt koster';
        $strings['Checkout'] = 'Checkout';
        $strings['Quantity'] = 'Antal';
        $strings['CreditPurchase'] = 'Skaf mønter ';
        $strings['EmptyCart'] = 'Din indkøbskurv er tom';
        $strings['BuyCredits'] = 'Køb mønter';
        $strings['CreditsPurchased'] = 'Mønter anskaffet';
        $strings['ViewYourCredits'] = 'Se dine mønter';
        $strings['TryAgain'] = 'Prøv igen';
        $strings['PurchaseFailed'] = 'Der var problemer med din betaling';
        $strings['NoteCreditsPurchased'] = 'Mønter anskaffet';
        $strings['CreditsUpdatedLog'] = 'Mønter er opdateret med %s';
        $strings['ReservationCreatedLog'] = 'Din reservation er oprettet. Referencenummer %s';
        $strings['ReservationUpdatedLog'] = 'Din reservation er opdateret. Referencenummer %s';
        $strings['ReservationDeletedLog'] = 'Din reservation er slettet. Referencenummer %s';
        $strings['BuyMoreCredits'] = 'Køb flere mønter';
        $strings['Transactions'] = 'Overførsel';
        $strings['Cost'] = 'Pris';
        $strings['PaymentGateways'] = 'Betalingsportaler';
        $strings['CreditHistory'] = 'Alle mønter.';
        $strings['TransactionHistory'] = 'Alle overførsler';
        $strings['Date'] = 'Dato';
        $strings['Note'] = 'Note';
        $strings['CreditsBefore'] = 'Mønter inden';
        $strings['CreditsAfter'] = 'Mønter efter';
        $strings['TransactionFee'] = 'Overførselsgebyr';
        $strings['InvoiceNumber'] = 'Fakturanr.';
        $strings['TransactionId'] = 'Overførsels-ID';
        $strings['Gateway'] = 'Portal';
        $strings['GatewayTransactionDate'] = 'Dato overførselsportal';
        $strings['Refund'] = 'Tilbagebetaling';
        $strings['IssueRefund'] = 'Tilgodehavende';
        $strings['RefundIssued'] = 'Tilgodehavende gennemført';
        $strings['RefundAmount'] = 'Beløb tilgode';
        $strings['AmountRefunded'] = 'Tilbagebetalt';
        $strings['FullyRefunded'] = 'Tilbagebetalt hele beløbet';
        $strings['YourCredits'] = 'Dine mønter';
        $strings['PayWithCard'] = 'Betal med kort';
        $strings['or'] = 'eller';
        $strings['CreditsRequired'] = 'Antal mønter der skal bruges';
        $strings['AddToGoogleCalendar'] = 'Tilføj til Google';
        $strings['Image'] = 'Billede';
        $strings['ChooseOrDropFile'] = 'Vælg en fil, eller træk den hertil';
        $strings['SlackBookResource'] = 'Reserver %s nu';
        $strings['SlackBookNow'] = 'Reserver nu';
        $strings['SlackNotFound'] = 'Vi kunne ikke finde en facilitet med det navn. Reserver nu, for at oprette en ny reservation.';
        $strings['AutomaticallyAddToGroup'] = 'Tilføj automatisk nye brugere til denne gruppe';
        $strings['GroupAutomaticallyAdd'] = 'Tilføj automatisk';
        $strings['TermsOfService'] = 'Retningslinjer';
        $strings['EnterTermsManually'] = 'Tilføj retningslinjer manuelt';
        $strings['LinkToTerms'] = 'Tilføj et link til retningslinjer';
        $strings['UploadTerms'] = 'Upload retningslinjer';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Kræver at du har kendskab til retningslinjerne';
        $strings['UponReservation'] = 'Efter reservation';
        $strings['UponRegistration'] = 'Efter registrering';
        $strings['ViewTerms'] = 'Se retningslinjer';
        $strings['IAccept'] = 'Jeg accepterer';
        $strings['TheTermsOfService'] = 'retningslinjerne';
        $strings['DisplayPage'] = 'Vis side';
        $strings['AvailableAllYear'] = 'Hele året';
        $strings['Availability'] = 'Ledig';
        $strings['AvailableBetween'] = 'Ledig mellem';
        $strings['ConcurrentYes'] = 'Faciliteter kan reserveres af mere end én person ad gangen';
        $strings['ConcurrentNo'] = 'Faciliteter kan ikke reserveres af mere end én person ad gangen';
        $strings['ScheduleAvailabilityEarly'] = 'Dette tidsrum er endnu ikke ledigt. Det bliver ledigt';
        $strings['ScheduleAvailabilityLate'] = 'Dette tidsrum er ikke længere ledigt. Det var ledigt';
        $strings['ResourceImages'] = 'Billeder af faciliteten';
        $strings['FullAccess'] = 'Fuld adgang';
        $strings['ViewOnly'] = 'Kun adgang til at se';
        $strings['Purge'] = 'Ryd';
        $strings['UsersWillBeDeleted'] = 'brugere bliver slettet';
        $strings['BlackoutsWillBeDeleted'] = 'lukkede tidsrum bliver slettet';
        $strings['ReservationsWillBePurged'] = 'reservationer vil blive ryddet';
        $strings['ReservationsWillBeDeleted'] = 'reservationer bliver slettet';
        $strings['PermanentlyDeleteUsers'] = 'Slet brugere permanent, der ikke har været logget på siden, ';
        $strings['DeleteBlackoutsBefore'] = 'Slet lukkede tidsrum før';
        $strings['DeletedReservations'] = 'Slet reservationer';
        $strings['DeleteReservationsBefore'] = 'Slet reservationer før';
        $strings['SwitchToACustomLayout'] = 'Skift til en brugerdefinerede tidsplan';
        $strings['SwitchToAStandardLayout'] = 'Skift til standard tidsplanen';
        $strings['ThisScheduleUsesACustomLayout'] = 'Denne tidsplan har brugerdefinerede tidsintervaller';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Denne tidsplan har standard tidsintervaller';
        $strings['SwitchLayoutWarning'] = 'Er du sikker på, du vil ændre, hvordan tidsplanen er sat op. Det sletter alle eksisterende tidsintervaller';
        $strings['DeleteThisTimeSlot'] = 'Slet dette tidsinterval';
        $strings['Refresh'] = 'Genindlæs';
        $strings['ViewReservation'] = 'Se reservation';
        $strings['PublicId'] = 'Offentlig ID';
        $strings['Public'] = 'Offentlig';
        $strings['AtomFeedTitle'] = '%s reservationer';
        $strings['DefaultStyle'] = 'Standardudseende';
        $strings['Standard'] = 'Standard';
        $strings['Wide'] = 'Bred';
        $strings['Tall'] = 'Høj';
        $strings['EmailTemplate'] = 'E-mail-skabelon';
        $strings['SelectEmailTemplate'] = 'Vælg, e-mail-skabelon';
        $strings['ReloadOriginalContents'] = 'Genindlæs det oprindelige indhold';
        $strings['UpdateEmailTemplateSuccess'] = 'Opdateret e-mail-skabelon';
        $strings['UpdateEmailTemplateFailure'] = 'E-mail-skabelonen kunne ikke opdateres. Tjek at der er skrivetilladelse på stien';
        $strings['BulkResourceDelete'] = 'Multi-sletning af faciliteter';
        $strings['NewVersion'] = 'Ny udgave';
        $strings['WhatsNew'] = 'Hvad er nyt?';
        $strings['OnlyViewedCalendar'] = 'Denne tidsplan kan kun ses i kalendervisning';
        $strings['Grid'] = 'Gitter';
        $strings['List'] = 'Liste';
        $strings['NoReservationsFound'] = 'Der blev ikke fundet nogen reservationer';
        $strings['EmailReservation'] = 'E-mail reservation';
        $strings['AdHocMeeting'] = 'Ad-hoc-møde';
        $strings['NextReservation'] = 'Næste reservation';
        $strings['MissedCheckin'] = 'Fik ikke tjekket ind';
        $strings['MissedCheckout'] = 'Fik ikke tjekket ud';
        $strings['Utilization'] = 'Brug';
        $strings['SpecificTime'] = 'Bestemt tidspunkt';
        $strings['ReservationSeriesEndingPreference'] = 'Når min serie af reservationer slutter';
        $strings['NotAttending'] = 'Deltager ikke';
        $strings['ViewAvailability'] = 'Se ledige tider';
        $strings['ReservationDetails'] = 'Oplysninger om reservation';
        $strings['StartTime'] = 'Begynder';
        $strings['EndTime'] = 'Slutter';
        $strings['New'] = 'Ny';
        $strings['Updated'] = 'Opdateret';
        $strings['Custom'] = 'Brugerdefineret';
        $strings['AddDate'] = 'Tilføj dato';
        $strings['RepeatOn'] = 'Gentages';
        $strings['ScheduleConcurrentMaximum'] = 'I alt<b>%s</b> faciliteter kan reserveres på en gang.';
        $strings['ScheduleConcurrentMaximumNone'] = 'Der er ingen begrænsning på antallet af faciliteter, som kan reserveres på en gang';
        $strings['ScheduleMaximumConcurrent'] = 'Det maksimale antal af faciliteter, som kan reserveres på en gang';
        $strings['ScheduleMaximumConcurrentNote'] = 'Med denne indstilling vil antallet af faciliteter, der kan reserveres på en gang, for denne tidsplan, være ubegrænset';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Hver enkelt reservation er begrænset til maksimal <b>%s</b> faciliteter';
        $strings['ScheduleResourcesPerReservationNone'] = 'Der er ingen begrænsning på antallet af faciliteter pr. reservation';
        $strings['ScheduleResourcesPerReservation'] = 'Det maksimale antal af faciliteter pr. reservation';
        $strings['ResourceConcurrentReservations'] = 'Tillad %s reservationer på en gang';
        $strings['ResourceConcurrentReservationsNone'] = 'Tillad ikke at reservationer foregår samtidigt';
        $strings['AllowConcurrentReservations'] = 'Tillad at reservationer foregår samtidigt';
        $strings['ResourceDisplayInstructions'] = 'Der er ikke valgt en facilitet. Du finder linket, der viser en facilitet, under Administration > Faciliteter. Faciliteten skal være offentlig tilgængelig.';
        $strings['Owner'] = 'Ejer';
        $strings['MaximumConcurrentReservations'] = 'Det maksimale antal af samtidige reservationer';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Installer LibreBooking';
        $strings['IncorrectInstallPassword'] = 'Kodeordet var forkert.';
        $strings['SetInstallPassword'] = 'Du skal angive et installationskodeord, før installationen kan køres.';
        $strings['InstallPasswordInstructions'] = 'I %s sæt %s til et kodeord, som er tilfældigt og svært at gætte, gå derefter tilbage til denne side.<br/>Du kan bruge %s';
        $strings['NoUpgradeNeeded'] = 'LibreBooking er opdateret. Der er ikke brug for opgradering.';
        $strings['ProvideInstallPassword'] = 'Du skal angive dit installationskodeord.';
        $strings['InstallPasswordLocation'] = 'Du kan finde dette på %s i %s.';
        $strings['VerifyInstallSettings'] = 'Bekræft de følgende standardindstillinger før du fortsætter, eller ændre dem i %s.';
        $strings['DatabaseName'] = 'Database navn';
        $strings['DatabaseUser'] = 'Database bruger';
        $strings['DatabaseHost'] = 'Database Host';
        $strings['DatabaseCredentials'] = 'Du skal intaste informationer på en MySQL bruger som har adgang til at oprette databaser. Hvis du ikke kender disse informationer kan du kontakte administratoren for databasen. I de fleste tilfælde fungerer "root".';
        $strings['MySQLUser'] = 'MySQL bruger';
        $strings['InstallOptionsWarning'] = 'De følgende indstillinger vil sandsynligvis ikke fungerer, hvis din udbyder styrer dine indstillinger. Hvis dette er tilfældet, kan du bruge en MySQL opsætningsguide, til at gennemføre disse trin.';
        $strings['CreateDatabase'] = 'Opret databasen';
        $strings['CreateDatabaseUser'] = 'Opret database bruger';
        $strings['PopulateExampleData'] = 'Importer "sample data". Opretter en administratorkonto: admin/password og en brugerkonto: user/password';
        $strings['DataWipeWarning'] = 'Adarsel: Dette sletter al eksisterende data';
        $strings['RunInstallation'] = 'Kør installationen';
        $strings['UpgradeNotice'] = 'Du opgraderer fra version <b>%s</b> til version <b>%s</b>';
        $strings['RunUpgrade'] = 'Kør Opgradering';
        $strings['Executing'] = 'Udfører';
        $strings['StatementFailed'] = 'Mislykket. Beskrivelse:';
        $strings['SQLStatement'] = 'SQL Statement:';
        $strings['ErrorCode'] = 'Fejl Kode:';
        $strings['ErrorText'] = 'Fejl Tekst:';
        $strings['InstallationSuccess'] = 'Installationen blev gennemført!';
        $strings['RegisterAdminUser'] = 'Opret din administrator bruger. Dette er nødvendigt, hvis du ikke importerede "sample data". Vær sikker på at $conf[\'settings\'][\'allow.self.registration\'] = \'true\' i din %s fil.';
        $strings['LoginWithSampleAccounts'] = 'Hvis du importerede "sample data", kan du logge ind med admin/password for administratoren eller user/password for almindelig bruger.';
        $strings['InstalledVersion'] = 'Du kører nu version %s af LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Vi anbefaler, at du opgraderer din config fil';
        $strings['InstallationFailure'] = 'Der var problemer med installationen. Ret problemerne og kør installationen forfra.';
        $strings['ConfigureApplication'] = 'Konfigurer Booked Scheduler';
        $strings['ConfigUpdateSuccess'] = 'Din config fil er opdateret!';
        $strings['ConfigUpdateFailure'] = 'Vi kunne ikke opdaterer din config fil automatisk. Overskriv indholdet af config.php med det følgende:';
        $strings['ScriptUrlWarning'] = 'Din <em>script.url</em> indstilling er muligvis forkert. Den er sat til <strong>%s</strong>, og burde være <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Der var intet der passede til dit brugernavn eller kodeord';
        $strings['ReservationFailed'] = 'Din reservation kunne ikke oprettes';
        $strings['MinNoticeError'] = 'Denne reservation kræver varsling. Den kan først reserveres %s.';
        $strings['MinNoticeErrorUpdate'] = 'Ændring i denne reservation kræver en varsling. Reservationer før %s kan ikke ændres.';
        $strings['MinNoticeErrorDelete'] = 'Sletning af denne reservation kræver en varsling. Reservationer før %s kan ikke slettes.';
        $strings['MaxNoticeError'] = 'Der kan ikke oprettes en reservation så langt ud i fremtiden. Den kan først reserveres %s.';
        $strings['MinDurationError'] = 'Reservationen skal vare mindst %s';
        $strings['MaxDurationError'] = 'Reservationen kan ikke strække sig over mere end %s.';
        $strings['ConflictingAccessoryDates'] = 'Der er ikke et tilstrækkeligt antal af følgende udstyr:';
        $strings['NoResourcePermission'] = 'Du har ikke adgang til én eller flere af de efterspurgte faciliteter.';
        $strings['ConflictingReservationDates'] = 'Der er konflikter mellem reservationer på følgende datoer.';
        $strings['InstancesOverlapRule'] = 'I nogle af reservationerne i serien er der overlap.';
        $strings['StartDateBeforeEndDateRule'] = 'Begyndelsestidspunktet skal være før sluttidspunktet.';
        $strings['StartIsInPast'] = 'Begyndelsestidspunktet kan ikke være i fortiden';
        $strings['EmailDisabled'] = 'Din administrator har fjernet muligheden for at få besked via e-mail.';
        $strings['ValidLayoutRequired'] = 'Tidsintervallerne skal dække alle 24 timer i døgnet og begynde og slutte kl. 00:00.';
        $strings['CustomAttributeErrors'] = 'Der er problemer med de oplysningsfelter, du har oprettet.';
        $strings['CustomAttributeRequired'] = '%s skal udfyldes.';
        $strings['CustomAttributeInvalid'] = 'Den værdi, du har tildelt %s er ugyldig.';
        $strings['AttachmentLoadingError'] = 'Der var problemer med at hente den efterspurgte fil.';
        $strings['InvalidAttachmentExtension'] = 'Du kan kun uploade disse filtyper: %s';
        $strings['InvalidStartSlot'] = 'Det ønskede begyndelsestidspunkt er ugyldig.';
        $strings['InvalidEndSlot'] = 'Det ønskede sluttidspunkt er ugyldig.';
        $strings['MaxParticipantsError'] = '%s har kun plads til %s deltagere.';
        $strings['ReservationCriticalError'] = 'Der opstod en alvorlig fejl, da vi skulle gemme din reservation. Kontakt venligst din administrator, hvis denne fejl fortsætter';
        $strings['InvalidStartReminderTime'] = 'Begyndelsestidspunktet for påmindelsen er ikke gyldigt';
        $strings['InvalidEndReminderTime'] = 'Sluttidspunktet for påmindelsen er ikke gyldigt';
        $strings['QuotaExceeded'] = 'Begrænsningerne er overskredet.';
        $strings['MultiDayRule'] = '%s kan ikke reserveres over flere dage.';
        $strings['InvalidReservationData'] = 'Der var problemer med den ønskede reservation';
        $strings['PasswordError'] = 'Kodeordet skal indeholde mindst %s bogstaver og mindst %s tal.';
        $strings['PasswordErrorRequirements'] = 'Kodeordet skal indeholde en kombination af mindst %s bogstaver med stort og lille begyndelsesbogstav og mindst %s tal.';
        $strings['NoReservationAccess'] = 'Du har ikke tilladelse til at ændre denne reservation.';
        $strings['PasswordControlledExternallyError'] = 'Dit kodeord styres af et eksternt system og kan ikke opdateres her.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Udstyr kan kun reserveres sammen med en facilitet %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Du skal reservere mindst %s af udstyret %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Du kan ikke reservere mere end %s af udstyret %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Udstyr \'%s\' kan ikke reserveres sammen med de ønskede faciliteter';
        $strings['NoResources'] = 'Du har ikke tilføjet en facilitet';
        $strings['ParticipationNotAllowed'] = 'Du har ikke tilladelse til at deltage i denne reservation';
        $strings['ReservationCannotBeCheckedInTo'] = 'Der kan ikke tjekkes ind, på denne reservation';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Der kan ikke tjekkes ud, fra denne reservation';
        $strings['InvalidEmailDomain'] = 'Domænet på denne e-mailadresse er ikke godkendt';
        $strings['TermsOfServiceError'] = 'Du skal acceptere retningslinjerne';
        $strings['UserNotFound'] = 'Vi kunne ikke finde denne bruger';
        $strings['ScheduleAvailabilityError'] = 'Denne tidsplan er ledig mellem %s og %s';
        $strings['ReservationNotFoundError'] = 'Vi kunne ikke finde denne reservation';
        $strings['ReservationNotAvailable'] = 'Denne reservationen er ikke tilgængelig';
        $strings['TitleRequiredRule'] = 'Du skal give din reservation en overskrift';
        $strings['DescriptionRequiredRule'] = 'Du skal give en beskrivelse af din reservation';
        $strings['WhatCanThisGroupManage'] = 'Hvilke ændringer kan denne gruppe foretage?';
        $strings['ReservationParticipationActivityPreference'] = 'Når nogle tilmelder eller afmelder sig min reservation';
        $strings['RegisteredAccountRequired'] = 'Det er kun registrerede brugere, som kan oprette en reservation';
        $strings['InvalidNumberOfResourcesError'] = 'Det højeste antal af faciliteter, som kan reserveres på en gang er %s';
        $strings['ScheduleTotalReservationsError'] = 'Denne tidsplan tillader kun at %s faciliteter reserveres samtidigt. Din reservation overskrider denne begrænsning på de følgende datoer:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Opret reservation';
        $strings['EditReservation'] = 'Opdater reservation';
        $strings['LogIn'] = 'Log ind';
        $strings['ManageReservations'] = 'Reservationer';
        $strings['AwaitingActivation'] = 'Afventer aktivering';
        $strings['PendingApproval'] = 'Afventer godkendelse';
        $strings['ManageSchedules'] = 'Tidsplaner';
        $strings['ManageResources'] = 'Faciliteter';
        $strings['ManageAccessories'] = 'Udstyr';
        $strings['ManageUsers'] = 'Brugere';
        $strings['ManageGroups'] = 'Grupper';
        $strings['ManageQuotas'] = 'Begrænsninger';
        $strings['ManageBlackouts'] = 'Lukkede tidsrum';
        $strings['MyDashboard'] = 'Min opslagstavle';
        $strings['ServerSettings'] = 'Serverindstillinger';
        $strings['Dashboard'] = 'Opslagstavle';
        $strings['Help'] = 'Hjælp';
        $strings['Administration'] = 'Administration';
        $strings['About'] = 'Om';
        $strings['Bookings'] = 'Reservationer';
        $strings['Schedule'] = 'Tidsplan';
        $strings['Account'] = 'Konto';
        $strings['EditProfile'] = 'Min profil';
        $strings['FindAnOpening'] = 'Find en tid';
        $strings['OpenInvitations'] = 'Invitationer';
        $strings['ResourceCalendar'] = 'Kalender over faciliteter';
        $strings['Reservation'] = 'Ny reservation';
        $strings['Install'] = 'Installation';
        $strings['ChangePassword'] = 'Skift kodeord';
        $strings['MyAccount'] = 'Min konto';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Administration';
        $strings['ForgotPassword'] = 'Glemt kodeord';
        $strings['NotificationPreferences'] = 'Beskedindstillinger';
        $strings['ManageAnnouncements'] = 'Meddelelser';
        $strings['Responsibilities'] = 'Ansvar';
        $strings['GroupReservations'] = 'Gruppereservationer';
        $strings['ResourceReservations'] = 'Facilitetsreservationer';
        $strings['Customization'] = 'Tilpasning';
        $strings['Attributes'] = 'Udstyr';
        $strings['AccountActivation'] = 'Aktivering af konto';
        $strings['ScheduleReservations'] = 'Tidsplaner';
        $strings['Reports'] = 'Rapport';
        $strings['GenerateReport'] = 'Opret ny rapport';
        $strings['MySavedReports'] = 'Mine gemte rapporter';
        $strings['CommonReports'] = 'Almindelige rapporter';
        $strings['ViewDay'] = 'Se dag';
        $strings['Group'] = 'Gruppe';
        $strings['ManageConfiguration'] = 'Kofiguration';
        $strings['LookAndFeel'] = 'Udseende';
        $strings['ManageResourceGroups'] = 'Facilitetsgrupper';
        $strings['ManageResourceTypes'] = 'Facilitetstype';
        $strings['ManageResourceStatus'] = 'Status';
        $strings['ReservationColors'] = 'Reservationsfarver';
        $strings['SearchReservations'] = 'Find reservation';
        $strings['ManagePayments'] = 'Betalling';
        $strings['ViewCalendar'] = 'Se kalender';
        $strings['DataCleanup'] = 'Fjern data';
        $strings['ManageEmailTemplates'] = 'E-mailskabeloner';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'O';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Søn';
        $strings['DayMondayAbbr'] = 'Man';
        $strings['DayTuesdayAbbr'] = 'Tir';
        $strings['DayWednesdayAbbr'] = 'Ons';
        $strings['DayThursdayAbbr'] = 'Tor';
        $strings['DayFridayAbbr'] = 'Fre';
        $strings['DaySaturdayAbbr'] = 'Lør';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Din reservation er godkendt';
        $strings['ReservationCreatedSubject'] = 'Din reservation er oprettet';
        $strings['ReservationUpdatedSubject'] = 'Din reservation er opdateret';
        $strings['ReservationDeletedSubject'] = 'Din reservation er blevet fjernet';
        $strings['ReservationCreatedAdminSubject'] = 'Meddelelse: En reservation er oprettet';
        $strings['ReservationUpdatedAdminSubject'] = 'Meddelelse: En reservation er opdateret';
        $strings['ReservationDeleteAdminSubject'] = 'Meddelelse: En reservation er blevet fjernet';
        $strings['ReservationApprovalAdminSubject'] = 'Meddelelse: En reservation kræver din godkendelse';
        $strings['ParticipantAddedSubject'] = 'Meddelelse om deltagere til en reservation';
        $strings['ParticipantDeletedSubject'] = 'En reservation er fjernet';
        $strings['InviteeAddedSubject'] = 'Invitation til en reservation';
        $strings['ResetPasswordRequest'] = 'Anmodning om at nulstille en adgangskode';
        $strings['ActivateYourAccount'] = 'Vær venlig at aktivere din konto';
        $strings['ReportSubject'] = 'Din efterspurgte rapport (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Reservationen af %s begynder snart';
        $strings['ReservationEndingSoonSubject'] = 'Reservationen af %s slutter snart';
        $strings['UserAdded'] = 'En ny bruger er tilføjet';
        $strings['UserDeleted'] = 'Brugerkontoen for %s blev slettet af %s';
        $strings['GuestAccountCreatedSubject'] = 'Din %s kontooplysninger';
        $strings['AccountCreatedSubject'] = 'Din %s kontodetaljer';
        $strings['InviteUserSubject'] = '%s har inviteret dig til at tilmelde dig %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Reservationen for %s er godkendt';
        $strings['ReservationCreatedSubjectWithResource'] = 'Reservation for %s er oprettet';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Reservation for %s er opdateret';
        $strings['ReservationDeletedSubjectWithResource'] = 'Reservation for %s er fjernet';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Meddelelse: Der er oprettet en reservation for %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Meddelelse: Der er opdateret en reservation for %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Meddelelse: Der er fjernet en reservation for %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Meddelelse: Reservation for %s kræver din godkendelse';
        $strings['ParticipantAddedSubjectWithResource'] = '%s tilføjede dig til en reservation af %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s fjernede en reservation af %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s inviterede dig til en reservation af %s';
        $strings['MissedCheckinEmailSubject'] = 'Manglende tjek ind for %s';
        $strings['ReservationShareSubject'] = '%s delte en reservation af %s';
        $strings['ReservationSeriesEndingSubject'] = 'Serien af reservationen for %s slutter %s';
        $strings['ReservationParticipantAccept'] = '% har accepteret din invitation til reservationen af %s på %s';
        $strings['ReservationParticipantDecline'] = '% har afvist din invitation til reservationen af %s på %s';
        $strings['ReservationParticipantJoin'] = '% har tilmeldt sig din reservationen af %s på %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Du har ingen tidligere reservationer';
        $strings['PastReservations'] = 'Tidligere reservationer';
        $strings['AllNoPastReservations'] = 'Der er ingen tidligere reservationer i de seneste %s dage';
        $strings['AllPastReservations'] = 'Alle tidligere reservationer';
        $strings['Yesterday'] = 'I går';
        $strings['EarlierThisWeek'] = 'Tidligere på ugen';
        $strings['PreviousWeek'] = 'Forrige uge';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Din gruppe har ingen kommende reservationer';
        $strings['GroupUpcomingReservations'] = 'Mine gruppers kommende reservationer';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Der opstod en fejl under login med Facebook. Prøv venligst igen.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Du har ingen reservationer, der venter på godkendelse';
        $strings['PendingApprovalReservations'] = 'Reservationer afventer godkendelse';
        $strings['LaterThisMonth'] = 'Senere denne måned';
        $strings['LaterThisYear'] = 'Senere på året';
        $strings['Remaining'] = 'Resterende';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Der er ingen manglende check-out reservationer';
        $strings['MissingCheckOutReservations'] = 'Manglende check-out reservationer';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Kan ikke se reservationsoplysninger, fordi du ikke har tilladelser til nogen af ressourcerne i denne reservation';
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
        $days['full'] = ['Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag'];
        // The three letter abbreviation
        $days['abbr'] = ['Søn', 'Man', 'Tir', 'Ons', 'Tor', 'Fre', 'Lør'];
        // The two letter abbreviation
        $days['two'] = ['Sø', 'Ma', 'Ti', 'On', 'To', 'Fr', 'Lø'];
        // The one letter abbreviation
        $days['letter'] = ['S', 'M', 'T', 'O', 'T', 'F', 'L'];

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
        $months['full'] = ['Januar', 'Februar', 'Marts', 'April', 'Maj', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Æ', 'Ø', 'Å',];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'da';
    }
}
