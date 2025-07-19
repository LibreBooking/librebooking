<?php

require_once('Language.php');
require_once('en_gb.php');

class du_be extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Voornaam';
        $strings['LastName'] = 'Familienaam';
        $strings['Timezone'] = 'Tijdzonezone';
        $strings['Edit'] = 'Editeer';
        $strings['Change'] = 'Wijzig';
        $strings['Rename'] = 'Hernoem';
        $strings['Remove'] = 'Verwijder';
        $strings['Delete'] = 'Delete';
        $strings['Update'] = 'Pas aan';
        $strings['Cancel'] = 'Annuleer';
        $strings['Add'] = 'Voeg toe';
        $strings['Name'] = 'Naam';
        $strings['Yes'] = 'Ja';
        $strings['No'] = 'Nee';
        $strings['FirstNameRequired'] = 'Voornaam is vereist.';
        $strings['LastNameRequired'] = 'Familienaam is vereist.';
        $strings['PwMustMatch'] = 'Paswoord bevestiging moet overeenstemmen met het paswoord.';
        $strings['PwComplexity'] = 'Paswoord moet minstens 6 karakters lang zijn en bestaan uit een combinatie van alfanumerische tekens en symbolen';
        $strings['ValidEmailRequired'] = 'Een geldig email adres moet opgegeven worden.';
        $strings['UniqueEmailRequired'] = 'Dit email adres werd al geregistreerd.';
        $strings['UniqueUsernameRequired'] = 'Deze gebruikerslogin werd al geregistreerd.';
        $strings['UserNameRequired'] = 'Een gebruikerslogin is vereist.';
        $strings['CaptchaMustMatch'] = 'Voer exact de letters in zoals afgebeeld op de figuur.';
        $strings['Today'] = 'Vandaag';
        $strings['Week'] = 'Week';
        $strings['Month'] = 'Maand';
        $strings['BackToCalendar'] = 'Terug naar kalendar';
        $strings['BeginDate'] = 'Begin';
        $strings['EndDate'] = 'Einde';
        $strings['Username'] = 'Login';
        $strings['Password'] = 'Paswoord';
        $strings['PasswordConfirmation'] = 'Bevestig paswoord';
        $strings['DefaultPage'] = 'Standaard hoofdpagina';
        $strings['MyCalendar'] = 'Mijn kalendar';
        $strings['ScheduleCalendar'] = 'Planning kalender';
        $strings['Registration'] = 'Registratie';
        $strings['NoAnnouncements'] = 'Er zijn geen aankondigingen';
        $strings['Announcements'] = 'Aankondigingen';
        $strings['NoUpcomingReservations'] = 'U hebt geen aankomende reserveringen';
        $strings['UpcomingReservations'] = 'Aankomende reserveringen';
        $strings['ShowHide'] = 'Toon/Verberg';
        $strings['Error'] = 'Fout';
        $strings['ReturnToPreviousPage'] = 'Keer terug naar de pagina die u laatst bezocht';
        $strings['UnknownError'] = 'Onbekende fout';
        $strings['InsufficientPermissionsError'] = 'U hebt onvoldoende rechten tot deze bron';
        $strings['MissingReservationResourceError'] = 'Er werd geen bron geselecteerd';
        $strings['MissingReservationScheduleError'] = 'Er werd geen planning geselecteerd';
        $strings['DoesNotRepeat'] = 'Niet herhalen';
        $strings['Daily'] = 'Dagelijks';
        $strings['Weekly'] = 'Wekelijks';
        $strings['Monthly'] = 'Maandelijks';
        $strings['Yearly'] = 'Jaarlijks';
        $strings['RepeatPrompt'] = 'Herhaal';
        $strings['hours'] = 'uren';
        $strings['days'] = 'dagen';
        $strings['weeks'] = 'weken';
        $strings['months'] = 'maanden';
        $strings['years'] = 'jaren';
        $strings['day'] = 'dagen';
        $strings['week'] = 'week';
        $strings['month'] = 'maand';
        $strings['year'] = 'jaar';
        $strings['repeatDayOfMonth'] = 'dag van de maand';
        $strings['repeatDayOfWeek'] = 'dag van de week';
        $strings['RepeatUntilPrompt'] = 'Tot';
        $strings['RepeatEveryPrompt'] = 'Elke';
        $strings['RepeatDaysPrompt'] = 'Op';
        $strings['CreateReservationHeading'] = 'Maak een nieuwe reservering';
        $strings['EditReservationHeading'] = 'Editeer reservering %s';
        $strings['ViewReservationHeading'] = 'Raadpleeg reservering %s';
        $strings['ReservationErrors'] = 'Wijzig reservering';
        $strings['Create'] = 'Maak aan';
        $strings['ThisInstance'] = 'Enkel deze instantie';
        $strings['AllInstances'] = 'Alle instanties';
        $strings['FutureInstances'] = 'Toekomstige instanties';
        $strings['Print'] = 'Print';
        $strings['ShowHideNavigation'] = 'Toon/Verberg navigatie';
        $strings['ReferenceNumber'] = 'Referentie nummer';
        $strings['Tomorrow'] = 'Morgen';
        $strings['LaterThisWeek'] = 'Later deze week';
        $strings['NextWeek'] = 'Volgende week';
        $strings['SignOut'] = 'Uitloggen';
        $strings['LayoutDescription'] = 'Begint op %s, met %s zichtbare dagen';
        $strings['AllResources'] = 'Alle bronnen';
        $strings['TakeOffline'] = 'Breng offline';
        $strings['BringOnline'] = 'Breng online';
        $strings['AddImage'] = 'Voeg afbeelding toe';
        $strings['NoImage'] = 'Geen afbeelding gekoppeld';
        $strings['Move'] = 'Verplaats';
        $strings['AppearsOn'] = 'Verschijnt op %s';
        $strings['Location'] = 'Locatie';
        $strings['NoLocationLabel'] = '(geen locatie vermeld)';
        $strings['Contact'] = 'Contact';
        $strings['NoContactLabel'] = '(geen contact informatie)';
        $strings['Description'] = 'Beschrijving';
        $strings['NoDescriptionLabel'] = '(geen beschrijving)';
        $strings['Notes'] = 'Notities';
        $strings['NoNotesLabel'] = '(geen notities)';
        $strings['NoTitleLabel'] = '(geen titel)';
        $strings['UsageConfiguration'] = 'Gebruik configuratie';
        $strings['ChangeConfiguration'] = 'Wijzig configuratie';
        $strings['ResourceMinLength'] = 'Een reservering moet minimum %s duren';
        $strings['ResourceMinLengthNone'] = 'Er is geen minimale reserveringsduur';
        $strings['ResourceMaxLength'] = 'Reserveringen kunnen niet langer duren dan %s';
        $strings['ResourceMaxLengthNone'] = 'Er is geen maximale reserveringsduur';
        $strings['ResourceRequiresApproval'] = 'Reserveringen vereisen goedkeuring';
        $strings['ResourceRequiresApprovalNone'] = 'Reserveringen zonder goedkeuringsstap';
        $strings['ResourcePermissionAutoGranted'] = 'Toegang wordt automatisch verleend';
        $strings['ResourcePermissionNotAutoGranted'] = 'Toegang wordt niet automatisch verleend';
        $strings['ResourceMinNotice'] = 'Reserveringen moeten minstens %s op voorhand gemaakt worden';
        $strings['ResourceMinNoticeNone'] = 'Reserveringen kunnen gemaakt worden vanaf actuele tijdstip';
        $strings['ResourceMaxNotice'] = 'Reserveringen kunnen niet later eindigen dan %s van het huidige tijdstip';
        $strings['ResourceMaxNoticeNone'] = 'Reserveringen kunnen onbeperkt in de toekomst eindigen';
        $strings['ResourceAllowMultiDay'] = 'Meerdaagse reserveringen mogelijk';
        $strings['ResourceNotAllowMultiDay'] = 'Meerdaagse reserveringen niet toegelaten';
        $strings['ResourceCapacity'] = 'Deze bron heeft een capaciteit van %s personen';
        $strings['ResourceCapacityNone'] = 'Deze bron heeft ongelimiteerde capaciteit';
        $strings['AddNewResource'] = 'Nieuw bron toevoegen';
        $strings['AddNewUser'] = 'Nieuwe gebruiker toevoegen';
        $strings['AddUser'] = 'Gebruiker toevoegen';
        $strings['Schedule'] = 'Planning';
        $strings['AddResource'] = 'Bron toevoegen';
        $strings['Capacity'] = 'Capaciteit';
        $strings['Access'] = 'Toegang';
        $strings['Duration'] = 'Duur';
        $strings['Active'] = 'Actief';
        $strings['Inactive'] = 'Inactief';
        $strings['ResetPassword'] = 'Reset paswoord';
        $strings['LastLogin'] = 'Laatste login';
        $strings['Search'] = 'Zoek';
        $strings['ResourcePermissions'] = 'Toegangsrechten bron';
        $strings['Reservations'] = 'Reserveringen';
        $strings['Groups'] = 'Groepen';
        $strings['ResetPassword'] = 'Reset paswoord';
        $strings['AllUsers'] = 'Alle gebruikers';
        $strings['AllGroups'] = 'Alle groepen';
        $strings['AllSchedules'] = 'Alle planningen';
        $strings['UsernameOrEmail'] = 'Login of email';
        $strings['Members'] = 'Leden';
        $strings['QuickSlotCreation'] = 'Voeg slots toe elke %s minuten tussen %s en %s';
        $strings['ApplyUpdatesTo'] = 'ApplyUpdatesTo';
        $strings['CancelParticipation'] = 'Cancel deelname';
        $strings['Attending'] = 'Wachtend...';
        $strings['QuotaConfiguration'] = 'Op %s voor %s gebruikers in %s zijn beperkt tot %s %s per %s';
        $strings['reservations'] = 'reserveringen';
        $strings['ChangeCalendar'] = 'Wijzig kalender';
        $strings['AddQuota'] = 'Voeg kwota toe';
        $strings['FindUser'] = 'Zoek gebruiker';
        $strings['Created'] = 'Aangemaakt';
        $strings['LastModified'] = 'Laatste wijziging';
        $strings['GroupName'] = 'Groep naam';
        $strings['GroupMembers'] = 'Groep leden';
        $strings['GroupRoles'] = 'Groep rollen';
        $strings['GroupAdmin'] = 'Groep beheerder';
        $strings['Actions'] = 'Acties';
        $strings['CurrentPassword'] = 'Huidig paswoord';
        $strings['NewPassword'] = 'Nieuw paswoord';
        $strings['InvalidPassword'] = 'Huidig paswoord is foutief';
        $strings['PasswordChangedSuccessfully'] = 'Uw paswoord werd succesvol aangepast';
        $strings['SignedInAs'] = 'Aangelogd als';
        $strings['NotSignedIn'] = 'U bent niet aangelogd';
        $strings['ReservationTitle'] = 'Reservering titel';
        $strings['ReservationDescription'] = 'Beschrijving van de reservering';
        $strings['ResourceList'] = 'Te reserveren bronnen';
        $strings['Accessories'] = 'Hulpmiddelen';
        $strings['Add'] = 'Voeg toe';
        $strings['ParticipantList'] = 'Deelnemers';
        $strings['InvitationList'] = 'Uitgenodigden';
        $strings['AccessoryName'] = 'Naam hulpmiddel';
        $strings['QuantityAvailable'] = 'Beschikbare hoeveelheid';
        $strings['Resources'] = 'Bronnen';
        $strings['Participants'] = 'Deelnemers';
        $strings['User'] = 'Gebruiker';
        $strings['Resource'] = 'Bron';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Bevestig';
        $strings['Page'] = 'Pagina';
        $strings['Rows'] = 'Rijen';
        $strings['Unlimited'] = 'Ongelimiteerd';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email adres';
        $strings['Phone'] = 'Telefoon';
        $strings['Organization'] = 'Organisatie';
        $strings['Position'] = 'Functie';
        $strings['Language'] = 'Taal';
        $strings['Permissions'] = 'Toegangsrechten';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Groep zoeken';
        $strings['Manage'] = 'Beheer';
        $strings['None'] = 'Geen';
        $strings['AddToOutlook'] = 'Voeg toe in Outlook';
        $strings['Done'] = 'Klaar';
        $strings['RememberMe'] = 'Herinner me';
        $strings['FirstTimeUser?'] = 'Eerste maal gebruiker?';
        $strings['CreateAnAccount'] = 'Een login aanmaken';
        $strings['ViewSchedule'] = 'Raadpleeg planning';
        $strings['ForgotMyPassword'] = 'Paswoord vergeten';
        $strings['YouWillBeEmailedANewPassword'] = 'U zult via email een random gegenereerd paswoord toegestuurd krijgen';
        $strings['Close'] = 'Sluit';
        $strings['ExportToCSV'] = 'Export to CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Bezig...';
        $strings['Login'] = 'Login';
        $strings['AdditionalInformation'] = 'Bijkomende informatie';
        $strings['AllFieldsAreRequired'] = 'alle velden zijn verplicht';
        $strings['Optional'] = 'optioneel';
        $strings['YourProfileWasUpdated'] = 'Uw profiel werd gewijzigd';
        $strings['YourSettingsWereUpdated'] = 'Uw instellingen werden gewijzigd';
        $strings['Register'] = 'Registreer';
        $strings['SecurityCode'] = 'Veiligheidscode';
        $strings['ReservationCreatedPreference'] = 'Wanneer ik of iemand in mijn naam een reservering maakt';
        $strings['ReservationUpdatedPreference'] = 'WWanneer ik of iemand anders een reservering van me wijzigt';
        $strings['ReservationApprovalPreference'] = 'Wanneer mijn reserveringen uit de wachtrij zijn goedgekeurd';
        $strings['PreferenceSendEmail'] = 'Stuur me een email';
        $strings['PreferenceNoEmail'] = 'Geen melding sturen';
        $strings['ReservationCreated'] = 'Uw reservering is succesvol aangemaakt!';
        $strings['ReservationUpdated'] = 'Uw reservering werd succesvol gewijzigd!';
        $strings['ReservationRemoved'] = 'Uw reservering werd verwijderd';
        $strings['YourReferenceNumber'] = 'Uw referentie nummer is %s';
        $strings['UpdatingReservation'] = 'Wijzigen reservering bezig';
        $strings['ChangeUser'] = 'Wijzig gebruiker';
        $strings['MoreResources'] = 'Extra bronnen';
        $strings['ReservationLength'] = 'Duur reservering';
        $strings['ParticipantList'] = 'Deelnemerslijst';
        $strings['AddParticipants'] = 'Voeg deelnemers toe';
        $strings['InviteOthers'] = 'Nodig anderen uit';
        $strings['AddResources'] = 'Voeg bron toe';
        $strings['AddAccessories'] = 'Voeg hulpmiddel toe';
        $strings['Accessory'] = 'Hulpmiddel';
        $strings['QuantityRequested'] = 'Hoeveelheid vereist';
        $strings['CreatingReservation'] = 'Aanmaken reservering bezig';
        $strings['UpdatingReservation'] = 'Wijzigen reservering bezig';
        $strings['DeleteWarning'] = 'Deze actie is permanent en niet terug te draaien!';
        $strings['DeleteAccessoryWarning'] = 'Dit hulpmiddel verwijderen betekent dat deze uit alle reserveringen verwijderd zal worden.';
        $strings['AddAccessory'] = 'Voeg hulpmiddel toe';
        $strings['AddBlackout'] = 'Voeg geblokkeerde periode toe';
        $strings['AllResourcesOn'] = 'Alle bronnen op';
        $strings['Reason'] = 'Oorzaak';
        $strings['BlackoutShowMe'] = 'Toon de conflicterende reserveringen';
        $strings['BlackoutDeleteConflicts'] = 'Verwijder conflicterende reserveringen';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Tussen';
        $strings['CreatedBy'] = 'Aangemaakt door';
        $strings['BlackoutCreated'] = 'Geblokkeerde tijd aangemaakt!';
        $strings['BlackoutNotCreated'] = 'Deze geblokkeerde periode kan niet aangemaakt worden';
        $strings['BlackoutConflicts'] = 'Er zijn conflicterende geblokkeerde periodes';
        $strings['ReservationConflicts'] = 'Er zijn conflicterende reservering periodes';
        $strings['UsersInGroup'] = 'Gebruikers in deze groep';
        $strings['Browse'] = 'Browse';
        $strings['DeleteGroupWarning'] = 'Deze groep verwijderen resulteert in het verwijderen van alle gekoppelde toegangsrechten op bronnen. Gebruikers in deze groep kunnen derhalve toegangsrechten verliezen.';
        $strings['WhatRolesApplyToThisGroup'] = 'Welke rollen behoren tot deze groep?';
        $strings['WhoCanManageThisGroup'] = 'Wie beheert deze groep?';
        $strings['AddGroup'] = 'Voeg groep toe';
        $strings['AllQuotas'] = 'Alle Kwotas';
        $strings['QuotaReminder'] = 'Opgelet: Kwotas worden toegepast op basis van de tijdzone van de planning.';
        $strings['AllReservations'] = 'Alle reserveringen';
        $strings['PendingReservations'] = 'Openstaande reserveringen';
        $strings['Approving'] = 'Te bevestigen';
        $strings['MoveToSchedule'] = 'Verplaats naar planning';
        $strings['DeleteResourceWarning'] = 'Deze bron verwijderen resulteert in het verwijderen van alle gelinkte informatie';
        $strings['DeleteResourceWarningReservations'] = 'alle gekoppelde voorbije, huidige en toekomstige reserveringen';
        $strings['DeleteResourceWarningPermissions'] = 'alle gekoppelde toegangsrechten';
        $strings['DeleteResourceWarningReassign'] = 'Wijs alles opnieuw toe wat niet verwijderd mag worden vooraleer u verder gaat.';
        $strings['ScheduleLayout'] = 'Layout (all times %s)';
        $strings['ReservableTimeSlots'] = 'Reserveerbare tijdsslots';
        $strings['BlockedTimeSlots'] = 'Geblokkeerde tijdsslots';
        $strings['ThisIsTheDefaultSchedule'] = 'Dit is de standaard planning';
        $strings['DefaultScheduleCannotBeBroughtDown'] = 'De standaard planning kan niet omlaag gebracht worden';
        $strings['MakeDefault'] = 'Maak standaard';
        $strings['BringDown'] = 'Omlaag';
        $strings['ChangeLayout'] = 'Wijzig layout';
        $strings['AddSchedule'] = 'Voeg een planning toe';
        $strings['StartsOn'] = 'Begint op';
        $strings['NumberOfDaysVisible'] = 'Aantal zichtbare dagen';
        $strings['UseSameLayoutAs'] = 'Gebruik dezelfde layout als';
        $strings['Format'] = 'Formaat';
        $strings['OptionalLabel'] = 'Optionele label';
        $strings['LayoutInstructions'] = 'Voeg een slot toe per lijn. De volledige 24 uren van een dag moeten worden voorzien beginnend en eindigend om 12:00 AM.';
        $strings['AddUser'] = 'Gebruiker toevoegen';
        $strings['UserPermissionInfo'] = 'De concrete toegang tot de bronnen hangt af van uw rol, groepsrechten en externe toegangsrechten.';
        $strings['DeleteUserWarning'] = 'Wanneer u deze gebruiker verwijdert, worden alle reserveringen uit het verleden en toekomst gewist.';
        $strings['AddAnnouncement'] = 'Voeg een aankondiging toe';
        $strings['Announcement'] = 'Aankondiging';
        $strings['Priority'] = 'Prioriteit';
        $strings['Reservable'] = 'Te reserveren';
        $strings['Unreservable'] = 'Niet te reserveren';
        $strings['Reserved'] = 'Gereserveerd';
        $strings['MyReservation'] = 'Mijn reservering';
        $strings['Pending'] = 'In wachtlijst';
        $strings['Past'] = 'Verleden';
        $strings['Restricted'] = 'Restricted';
        $strings['ViewAll'] = 'View All';
        $strings['MoveResourcesAndReservations'] = 'Move resources and reservations to';

        // Errors
        $strings['LoginError'] = 'Ongeldige usernaam of paswoord opgegeven';
        $strings['ReservationFailed'] = 'Uw reservering kan niet worden uitgevoerd';
        $strings['MinNoticeError'] = 'Deze reservering moet op voorhand worden aangevraagd. De vroegste reserveringsdatum is %s.';
        $strings['MaxNoticeError'] = 'Deze reservering kan niet zover in de toekomst worden gemaakt. De uiterste reserveringsdatum in de toekomst is %s.';
        $strings['MinDurationError'] = 'Deze reservering moet een minimum duur hebben van %s.';
        $strings['MaxDurationError'] = 'Deze reservering kan niet langer duren dan %s.';
        $strings['ConflictingAccessoryDates'] = 'Er zijn onvoldoende hulpmiddelen voorradig:';
        $strings['NoResourcePermission'] = 'U hebt onvoldoende rechten om toegang tot een of meerdere van de resources te verkrijgen';
        $strings['ConflictingReservationDates'] = 'Er zijn conflicterende reserveringen op volgende data:';
        $strings['StartDateBeforeEndDateRule'] = 'De startdatum moet voor de einddatum liggen';
        $strings['StartIsInPast'] = 'Een startdatum in het verleden is ongeldig';
        $strings['EmailDisabled'] = 'De beheerder zette de optie email meldingen af';
        $strings['ValidLayoutRequired'] = 'Slots moeten voor de volledige 24 uren van de dag voorzien worden, beginnend en eindigend om 12:00 AM.';

        // Page Titles
        $strings['CreateReservation'] = 'Maak een reservering';
        $strings['EditReservation'] = 'Editeer reservering';
        $strings['LogIn'] = 'Aanloggen';
        $strings['ManageReservations'] = 'Reserveringen';
        $strings['AwaitingActivation'] = 'Wachtend op activatie';
        $strings['PendingApproval'] = 'Wachtend op goedkeuring';
        $strings['ManageSchedules'] = 'Planningen';
        $strings['ManageResources'] = 'Bronnen';
        $strings['ManageAccessories'] = 'Hulpmiddelen';
        $strings['ManageUsers'] = 'Gebruikers';
        $strings['ManageGroups'] = 'Groepen';
        $strings['ManageQuotas'] = 'Kwotas';
        $strings['ManageBlackouts'] = 'Geblokkeerde periodes';
        $strings['MyDashboard'] = 'Mijn Dashboard';
        $strings['ServerSettings'] = 'Server settings';
        $strings['Dashboard'] = 'Dashboard';
        $strings['Help'] = 'Help';
        $strings['Bookings'] = 'Boekingen';
        $strings['Schedule'] = 'Planning';
        $strings['Reservations'] = 'Reserveringen';
        $strings['Account'] = 'Account';
        $strings['EditProfile'] = 'Editeer mijn profiel';
        $strings['FindAnOpening'] = 'Een mogelijkheid zoeken';
        $strings['OpenInvitations'] = 'Open uitnodigingen';
        $strings['MyCalendar'] = 'Mijn kalender';
        $strings['ResourceCalendar'] = 'Bron kalender';
        $strings['Reservation'] = 'Nieuwe Reservatie';
        $strings['Install'] = 'Installatie';
        $strings['ChangePassword'] = 'Wijzig paswoord';
        $strings['MyAccount'] = 'Mijn account';
        $strings['Profile'] = 'Profiel';
        $strings['ApplicationManagement'] = 'Applicatie beheer';
        $strings['ForgotPassword'] = 'Paswoord vergeten';
        $strings['NotificationPreferences'] = 'Melding voorkeuren';
        $strings['ManageAnnouncements'] = 'Aankondigingen';
        //

        // Day representations
        $strings['DaySundaySingle'] = 'Z';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'D';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'D';
        $strings['DayFridaySingle'] = 'V';
        $strings['DaySaturdaySingle'] = 'Z';

        $strings['DaySundayAbbr'] = 'Zon';
        $strings['DayMondayAbbr'] = 'Maa';
        $strings['DayTuesdayAbbr'] = 'Din';
        $strings['DayWednesdayAbbr'] = 'Woe';
        $strings['DayThursdayAbbr'] = 'Don';
        $strings['DayFridayAbbr'] = 'Vri';
        $strings['DaySaturdayAbbr'] = 'Zat';

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Uw reservatie werd goedgekeurd';
        $strings['ReservationCreatedSubject'] = 'Uw reservatie is aangemaakt';
        $strings['ReservationUpdatedSubject'] = 'Uw reservatie is aangepast';
        $strings['ReservationCreatedAdminSubject'] = 'Melding: Een reservatie werd aangemaakt';
        $strings['ReservationUpdatedAdminSubject'] = 'Melding: Een reservatie werd aangepast';
        $strings['ParticipantAddedSubject'] = 'Melding: deelname aan een reservering';
        $strings['InviteeAddedSubject'] = 'Uitnodiging reservering';
        $strings['ResetPassword'] = 'Verzoek om paswoord te resetten';
        $strings['ForgotPasswordEmailSent'] = 'Een email werd naar uw account gestuurd met de informatie om uw paswoord te resetten';
        //End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'U heeft geen vorige reserveringen';
        $strings['PastReservations'] = 'Vorige reserveringen';
        $strings['AllNoPastReservations'] = 'Er zijn geen vorige reserveringen in de afgelopen %s dagen';
        $strings['AllPastReservations'] = 'Alle vorige reserveringen';
        $strings['Yesterday'] = 'Gisteren';
        $strings['EarlierThisWeek'] = 'Eerder deze week';
        $strings['PreviousWeek'] = 'Vorige week';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Uw groep heeft geen toekomstige reserveringen';
        $strings['GroupUpcomingReservations'] = 'Aankomende reserveringen van mijn groep(en)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Er is een fout opgetreden bij het inloggen met Facebook. Probeer het opnieuw.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'U heeft geen reserveringen die wachten op goedkeuring';
        $strings['PendingApprovalReservations'] = 'Reserveringen in afwachting van goedkeuring';
        $strings['LaterThisMonth'] = 'Later deze maand';
        $strings['LaterThisYear'] = 'Later dit jaar';
        $strings['Remaining'] = 'Resterend';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Er zijn geen ontbrekende uitcheckreserveringen';
        $strings['MissingCheckOutReservations'] = 'Ontbrekende uitcheckreserveringen';              
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'U kunt geen details van de reservering zien omdat u geen toestemming heeft voor een van de bronnen in deze reservering';
        //End Schedule Resource Permissions
        //END NEEDS CHECKING

        $this->Strings = $strings;
        return $this->Strings;
    }

    protected function _LoadDays()
    {
        $days = parent::_LoadDays();

        /***
        DAY NAMES
        All of these arrays MUST start with Sunday as the first element
        and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
        // The three letter abbreviation
        $days['abbr'] = ['Zon', 'Maa', 'Din', 'Woe', 'Don', 'Vri', 'Zat'];
        // The two letter abbreviation
        $days['two'] = ['Zo', 'Ma', 'Di', 'Wo', 'Do', 'Vr', 'Za'];
        // The one letter abbreviation
        $days['letter'] = ['Z', 'M', 'D', 'W', 'D', 'V', 'Z'];

        $this->Days = $days;
        return $this->Days;
    }

    protected function _LoadMonths()
    {
        $months = parent::_LoadMonths();

        /***
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];

        $this->Months = $months;
        return $this->Months;
    }

    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'en_us';
    }
}
