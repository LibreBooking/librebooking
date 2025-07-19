<?php

require_once('Language.php');
require_once('en_gb.php');

class no_no extends en_gb
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

        $strings['FirstName'] = 'Fornavn';
        $strings['LastName'] = 'Etternavn';
        $strings['Timezone'] = 'Tidssone';
        $strings['Edit'] = 'Rediger';
        $strings['Change'] = 'Endre';
        $strings['Rename'] = 'Gi nytt navn';
        $strings['Remove'] = 'Fjern';
        $strings['Delete'] = 'Slett';
        $strings['Update'] = 'Oppdater';
        $strings['Cancel'] = 'Avbryt';
        $strings['Add'] = 'Legg til';
        $strings['Name'] = 'Navn';
        $strings['Yes'] = 'Ja';
        $strings['No'] = 'Nei';
        $strings['FirstNameRequired'] = 'Fornavn er obligatorisk.';
        $strings['LastNameRequired'] = 'Etternavn er obligatorisk.';
        $strings['PwMustMatch'] = 'Passordbekreftelsen må matche oppgitt passord.';
        $strings['ValidEmailRequired'] = 'En gyldig epostaddresse er påkrevd.';
        $strings['UniqueEmailRequired'] = 'Denne epostaddressen finnes allerede i registeret.';
        $strings['UniqueUsernameRequired'] = 'Brukernavnet er opptatt.';
        $strings['UserNameRequired'] = 'User name is required.';
        $strings['CaptchaMustMatch'] = 'Vennligst angi tegnene fra sikkerhetsbildet eksakt som angitt.';
        $strings['Today'] = 'I dag';
        $strings['Week'] = 'Uke';
        $strings['Month'] = 'Måned';
        $strings['BackToCalendar'] = 'Tilbake til kalender';
        $strings['BeginDate'] = 'Start';
        $strings['EndDate'] = 'Slutt';
        $strings['Username'] = 'Brukernavn';
        $strings['Password'] = 'Passord';
        $strings['PasswordConfirmation'] = 'Bekreft passord';
        $strings['DefaultPage'] = 'Standardsiden';
        $strings['MyCalendar'] = 'Min kalender';
        $strings['ScheduleCalendar'] = 'Bookingkalender';
        $strings['Registration'] = 'Registrering';
        $strings['NoAnnouncements'] = 'Det er ingen kunngjøringer';
        $strings['Announcements'] = 'Kunngjøringer';
        $strings['NoUpcomingReservations'] = 'Du har ingen kommende reservasjoner';
        $strings['UpcomingReservations'] = 'Kommende reservasjoner';
        $strings['AllNoUpcomingReservations'] = 'Det finnes ingen kommnde reservasjoner';
        $strings['AllUpcomingReservations'] = 'Alle kommende reservasjoner';
        $strings['ShowHide'] = 'Vis/Skjul';
        $strings['Error'] = 'Feil';
        $strings['ReturnToPreviousPage'] = 'Tilbake til forrige side';
        $strings['UnknownError'] = 'Ukjent feil';
        $strings['InsufficientPermissionsError'] = 'Du har ikke tilgangsrettigheter til dette objektet';  //usikker på oversettelse resource
        $strings['MissingReservationResourceError'] = 'Leieobjekt ikke valgt';
        $strings['MissingReservationScheduleError'] = 'Bookingkalender ikke valgt';
        $strings['DoesNotRepeat'] = 'Repeteres ikke';
        $strings['Daily'] = 'Daglig';
        $strings['Weekly'] = 'Ukentlig';
        $strings['Monthly'] = 'Månedlig';
        $strings['Yearly'] = 'Årlig';
        $strings['RepeatPrompt'] = 'Repeter';
        $strings['hours'] = 'timer';
        $strings['days'] = 'dager';
        $strings['weeks'] = 'uker';
        $strings['months'] = 'måneder';
        $strings['years'] = 'år';
        $strings['day'] = 'dag';
        $strings['week'] = 'uke';
        $strings['month'] = 'måned';
        $strings['year'] = 'år';
        $strings['repeatDayOfMonth'] = 'dag i måned';
        $strings['repeatDayOfWeek'] = 'dag i uke';
        $strings['RepeatUntilPrompt'] = 'Frem til';
        $strings['RepeatEveryPrompt'] = 'Hver';
        $strings['RepeatDaysPrompt'] = 'På';
        $strings['CreateReservationHeading'] = 'Opprett en ny reservasjon';
        $strings['EditReservationHeading'] = 'Redigerer reservasjonen %s';
        $strings['ViewReservationHeading'] = 'Viser reservation %s';
        $strings['ReservationErrors'] = 'Endre Reservasjon';
        $strings['Create'] = 'Opprett';
        $strings['ThisInstance'] = 'Kun denne enheten';
        $strings['AllInstances'] = 'Alle enheter';
        $strings['FutureInstances'] = 'Fremtidige enheter';
        $strings['Print'] = 'Skriv ut';
        $strings['ShowHideNavigation'] = 'Vis/Skjul navigasjon';
        $strings['ReferenceNumber'] = 'Referansenummer';
        $strings['Tomorrow'] = 'I morgen';
        $strings['LaterThisWeek'] = 'Senere denne uken';
        $strings['NextWeek'] = 'Neste uke';
        $strings['SignOut'] = 'Logg ut';                                                //usikker oversettelse
        $strings['LayoutDescription'] = 'Starter på %s, viser %s dager om gangen';
        $strings['AllResources'] = 'Alle ressurser';
        $strings['TakeOffline'] = 'Deaktiver nettvisning';
        $strings['BringOnline'] = 'Aktiver nettvisning';
        $strings['AddImage'] = 'Legg til bilde';
        $strings['NoImage'] = 'Ingen bilde valgt';
        $strings['Move'] = 'Flytt';
        $strings['AppearsOn'] = 'Opptrer på %s';
        $strings['Location'] = 'Stedsangivelse';
        $strings['NoLocationLabel'] = '(ingen stedsangivelse oppgitt)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(ingen kontaktinformasjon)';
        $strings['Description'] = 'Beskrivelse';
        $strings['NoDescriptionLabel'] = '(ingen beskrivelse)';
        $strings['Notes'] = 'Anmerkninger';
        $strings['NoNotesLabel'] = '(ingen anmerkninger)';
        $strings['NoTitleLabel'] = '(ingen tittel)';
        $strings['UsageConfiguration'] = 'Konfigurasjon bruk';                    //usikker oversettelse
        $strings['ChangeConfiguration'] = 'Endre konfigurasjon';
        $strings['ResourceMinLength'] = 'Reservasjonen må ha en varighet på minst %s';
        $strings['ResourceMinLengthNone'] = 'Det er ingen minimum reservasjonstid';
        $strings['ResourceMaxLength'] = 'Reservasjonen kan ikke vare lengre enn %s';
        $strings['ResourceMaxLengthNone'] = 'Det er ingen maksimum reservasjonstid';
        $strings['ResourceRequiresApproval'] = 'Reservasjoner må godkjennes';
        $strings['ResourceRequiresApprovalNone'] = 'Reservasjoner trenger ikke godkjennes';
        $strings['ResourcePermissionAutoGranted'] = 'Godkjennes automatisk';
        $strings['ResourcePermissionNotAutoGranted'] = 'Godkjennes ikke automatisk';
        $strings['ResourceMinNotice'] = 'Reservasjoner må gjøres minst %s før starttid';
        $strings['ResourceMinNoticeNone'] = 'Reservasjoner kan gjøres fram til nåværende tidspunkt';
        $strings['ResourceMaxNotice'] = 'Reservasjoner kan ikke ha endetidspunkt nærmere enn %s fra nåværende tidspunkt';
        $strings['ResourceMaxNoticeNone'] = 'Ingen grense for sluttidspunkt';
        $strings['ResourceBufferTime'] = 'Det må være %s mellom reservasjoner';
        $strings['ResourceBufferTimeNone'] = 'Det er ingen buffer mellom reservasjoner';
        $strings['ResourceAllowMultiDay'] = 'Reservasjoner kan gjøres over flere dager';
        $strings['ResourceNotAllowMultiDay'] = 'Reservasjoner kan ikke gjøres over flere dager';
        $strings['ResourceCapacity'] = 'Denne enhet har en kapasitet på %s person(er)';
        $strings['ResourceCapacityNone'] = 'Denne enhet har ubegrenset kapasitet';
        $strings['AddNewResource'] = 'Legg til ny enhet';
        $strings['AddNewUser'] = 'Legg til ny bruker';
        $strings['AddUser'] = 'Legg til bruker';
        $strings['Schedule'] = 'Bookingkalender';
        $strings['AddResource'] = 'Legg til enhet';
        $strings['Capacity'] = 'Kapasitet';
        $strings['Access'] = 'Tilgang';
        $strings['Duration'] = 'Varighet';
        $strings['Active'] = 'Aktivert';
        $strings['Inactive'] = 'Deaktivert';
        $strings['ResetPassword'] = 'Tilbakestill passord';
        $strings['LastLogin'] = 'Siste innlogging';
        $strings['Search'] = 'Søk';
        $strings['ResourcePermissions'] = 'Tilgangsrettigheter enhet';
        $strings['Reservations'] = 'Reservasjoner';
        $strings['Groups'] = 'Grupper';
        $strings['ResetPassword'] = 'Tilbakestill passord';
        $strings['AllUsers'] = 'Alle brukere';
        $strings['AllGroups'] = 'Alle grupper';
        $strings['AllSchedules'] = 'Alle bookingkalendere';
        $strings['UsernameOrEmail'] = 'Brukernavn eller epost';
        $strings['Members'] = 'Medlemmer';
        $strings['QuickSlotCreation'] = 'Create slots every %s minutes between %s and %s';
        $strings['ApplyUpdatesTo'] = 'Gjennomfør oppdatering på';
        $strings['CancelParticipation'] = 'Avbryt deltakelse';
        $strings['Attending'] = 'Deltar';
        $strings['QuotaConfiguration'] = 'På %s for %s brukere i %s er begrenset til %s %s pr %s';
        $strings['reservations'] = 'reservasjoner';
        $strings['reservation'] = 'reservasjon';
        $strings['ChangeCalendar'] = 'Bytt kalender';
        $strings['AddQuota'] = 'Legg til kvote';
        $strings['FindUser'] = 'Finn bruker';
        $strings['Created'] = 'Opprettet';
        $strings['LastModified'] = 'Sist endret';
        $strings['GroupName'] = 'Gruppenavn';
        $strings['GroupMembers'] = 'Gruppemedlemmer';
        $strings['GroupRoles'] = 'Grupperoller';
        $strings['GroupAdmin'] = 'Gruppeadministrator';
        $strings['Actions'] = 'Handlinger';
        $strings['CurrentPassword'] = 'Nåværende passord';
        $strings['NewPassword'] = 'Nytt passord';
        $strings['InvalidPassword'] = 'Nåværende passord er feil';
        $strings['PasswordChangedSuccessfully'] = 'Passordet er endret';
        $strings['SignedInAs'] = 'Logget inn som';
        $strings['NotSignedIn'] = 'Du er ikke innlogget';
        $strings['ReservationTitle'] = 'Reservasjonstittel';
        $strings['ReservationDescription'] = 'Reservasjonsbeskrivelse';
        $strings['ResourceList'] = 'Enheter til reservasjon';
        $strings['Accessories'] = 'Tilbehør';
        $strings['ParticipantList'] = 'Deltakere';
        $strings['InvitationList'] = 'Invitasjonsliste';
        $strings['AccessoryName'] = 'Navn tilbehør';
        $strings['QuantityAvailable'] = 'Tilgjengelig antall';
        $strings['Resources'] = 'Enheter';
        $strings['Participants'] = 'Deltakere';
        $strings['User'] = 'Bruker';
        $strings['Resource'] = 'Enhet';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Godkjenn';
        $strings['Page'] = 'Side';
        $strings['Rows'] = 'Rader';
        $strings['Unlimited'] = 'Ubegrenset';
        $strings['Email'] = 'Epost';
        $strings['EmailAddress'] = 'Epostaddresse';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Gateaddresse';
        $strings['Position'] = 'Sted';
        $strings['Language'] = 'Språk';
        $strings['Permissions'] = 'Tilgangsrettigheter';
        $strings['Reset'] = 'Tilbakestill';
        $strings['FindGroup'] = 'Finn gruppe';
        $strings['Manage'] = 'Administrer';
        $strings['None'] = 'Ingen';
        $strings['AddToOutlook'] = 'Legg til outlookkalender';
        $strings['Done'] = 'Ferdig';
        $strings['RememberMe'] = 'Husk meg';
        $strings['FirstTimeUser?'] = 'Førstegangsbruker?';
        $strings['CreateAnAccount'] = 'Opprett en brukerprofil';
        $strings['ViewSchedule'] = 'Vis bookingkalender';
        $strings['ForgotMyPassword'] = 'Jeg har glemt mitt passord';
        $strings['YouWillBeEmailedANewPassword'] = 'Du vil få et autogenerert passord på epost';
        $strings['Close'] = 'Lukk';
        $strings['ExportToCSV'] = 'Eksporter til CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'arbeider...';
        $strings['Login'] = 'Logg inn';
        $strings['AdditionalInformation'] = 'Tilleggsinformasjon';
        $strings['AllFieldsAreRequired'] = 'alle felter er obligatorisk';
        $strings['Optional'] = 'valgfritt';
        $strings['YourProfileWasUpdated'] = 'Profilen din ble oppdatert';
        $strings['YourSettingsWereUpdated'] = 'Dine innstillinger ble oppdatert';
        $strings['Register'] = 'Registerer deg';
        $strings['SecurityCode'] = 'Sikkerhetskode';
        $strings['ReservationCreatedPreference'] = 'Når jeg oppretter en reservasjon, eller en reservasjon blir opprettet på mine vegne';
        $strings['ReservationUpdatedPreference'] = 'Når jeg oppdaterer en reservasjon, eller en reservasjon blir oppdatert på mine vegne';
        $strings['ReservationDeletedPreference'] = 'Når jeg sletter en reservasjon, eller en reservasjon blir slettet på mine vegne';
        $strings['ReservationApprovalPreference'] = 'Når min ventende reservasjon blir godkjent';
        $strings['PreferenceSendEmail'] = 'Send meg en epost';
        $strings['PreferenceNoEmail'] = 'Ikke gi meg tilbakemelding';
        $strings['ReservationCreated'] = 'Din reservasjon er opprettet!';
        $strings['ReservationUpdated'] = 'Din reservasjon er oppdatert!';
        $strings['ReservationRemoved'] = 'Din reservasjon ble fjernet';
        $strings['ReservationRequiresApproval'] = 'En eller flere av enhetsreservasjonene trenger godkjenning før den er gjeldende.  Denne        reservasjonen   er kun midlertidig fram til den er godkjent.';
        $strings['YourReferenceNumber'] = 'Ditt referansenummer er %s';
        $strings['UpdatingReservation'] = 'Oppdaterer reservasjonen';
        $strings['ChangeUser'] = 'Bytt bruker';
        $strings['MoreResources'] = 'Flere enheter';
        $strings['ReservationLength'] = 'Reservasjonslengde';
        $strings['ParticipantList'] = 'Deltakerliste';
        $strings['AddParticipants'] = 'Legg til deltakere';
        $strings['InviteOthers'] = 'Inviter andre';
        $strings['AddResources'] = 'Legg til enheter';
        $strings['AddAccessories'] = 'Legg til tilbehør';
        $strings['Accessory'] = 'Tilbehør';
        $strings['QuantityRequested'] = 'Antall anmodet';
        $strings['CreatingReservation'] = 'Oppretter reservasjon';
        $strings['UpdatingReservation'] = 'Oppdaterer reservasjon';
        $strings['DeleteWarning'] = 'Denne handlingen er permanent og kan ikke angres!';
        $strings['DeleteAccessoryWarning'] = 'Sletting av dette tilbehøret vil fjerne det fra alle reservasjoner.';
        $strings['AddAccessory'] = 'Legg til tilbehør';
        $strings['AddBlackout'] = 'Legg til utilgjengelighet';
        $strings['AllResourcesOn'] = 'Alle enheter vises';
        $strings['Reason'] = 'Årsak';
        $strings['BlackoutShowMe'] = 'Vis reservasjoner i konflikt';
        $strings['BlackoutDeleteConflicts'] = 'Slett reservasjoner i konflikt';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Mellom';
        $strings['CreatedBy'] = 'Opprettet av';
        $strings['BlackoutCreated'] = 'Utilgjengelighet opprettet';
        $strings['BlackoutNotCreated'] = 'Utilgjengelighet kunne ikke opprettes';
        $strings['BlackoutUpdated'] = 'Utilgjengelighet oppdatert';
        $strings['BlackoutNotUpdated'] = 'Utilgjengelighet kunne ikke oppdateres';
        $strings['BlackoutConflicts'] = 'Det er konflikt mellom utilgjengelighetstider';
        $strings['ReservationConflicts'] = 'Det er konflikt mellom reservasjonstider';
        $strings['UsersInGroup'] = 'Brukere i denne gruppen';
        $strings['Browse'] = 'Se(browse)';
        $strings['DeleteGroupWarning'] = 'Ved å slette denne gruppen fjernes alle tilknyttede enhetsrettigheter.  Brukerne kan miste tilgang til enhetene.';
        $strings['WhatRolesApplyToThisGroup'] = 'Hvilke roller gjelder for denne gruppen?';
        $strings['WhoCanManageThisGroup'] = 'Hvem kan administrere denne gruppen?';
        $strings['WhoCanManageThisSchedule'] = 'Hvem kan administrere denne bookingkalenderen?';
        $strings['AddGroup'] = 'Legg til gruppe';
        $strings['AllQuotas'] = 'Alle kvoter';
        $strings['QuotaReminder'] = 'Husk: Kvotene er styrt av\'s tidssone.';
        $strings['AllReservations'] = 'Alle reservasjoner';
        $strings['PendingReservations'] = 'Reservasjoner som avventer godkjenning';
        $strings['Approving'] = 'Godkjenner';
        $strings['MoveToSchedule'] = 'Flytt til bookingkalender';
        $strings['DeleteResourceWarning'] = 'Sletting av denne enheten vil også slette tilhørende data, inkludert';
        $strings['DeleteResourceWarningReservations'] = 'alle foregående, nåværende og fremtidige reservasjoner assosiert med den';
        $strings['DeleteResourceWarningPermissions'] = 'alle tilknyttede rettigheter';
        $strings['DeleteResourceWarningReassign'] = 'Vennligst tilkntee på nytt alt du ikke vil skal slettes før du fortsetter';
        $strings['ScheduleLayout'] = 'Utforming (alle tider %s)';
        $strings['ReservableTimeSlots'] = 'Reserverbare tidsperioder';
        $strings['BlockedTimeSlots'] = 'Blokkerte tidsperioder';
        $strings['ThisIsTheDefaultSchedule'] = 'Dette er standard bookingkalender';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Standard bookingkalender kan ikke slettes';
        $strings['MakeDefault'] = 'Gjør til standard';
        $strings['BringDown'] = 'Flytt ned';
        $strings['ChangeLayout'] = 'Forandre utformingen';
        $strings['AddSchedule'] = 'Legg til bookingkalender';
        $strings['StartsOn'] = 'Starter på';
        $strings['NumberOfDaysVisible'] = 'Antall dager synlig';
        $strings['UseSameLayoutAs'] = 'Bruk samme utforming som';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Valgfri merkelapp';
        $strings['LayoutInstructions'] = 'Legg til en periode pr linje.  Periode må angis for alle 24 timer med begynnelse og slutt 12:00.';
        $strings['AddUser'] = 'Legg til bruker';
        $strings['UserPermissionInfo'] = 'Faktisk tilgang til enhet kan variere basert på brukerrolle, gruppetilgangsrettigheter, eller       innstillingene for eksterne tilgangsrettigheter';
        $strings['DeleteUserWarning'] = 'Ved å slette denne brukeren, fjerner du også alle hans foregående, nåværende og fremtidige reservasjoner.';
        $strings['AddAnnouncement'] = 'Legg til kunngjøring';
        $strings['Announcement'] = 'Kunngjøring';
        $strings['Priority'] = 'Prioritet';
        $strings['Reservable'] = 'Reserverbar';
        $strings['Unreservable'] = 'Ikke reserverbar';
        $strings['Reserved'] = 'Reservert';
        $strings['MyReservation'] = 'Min reservasjon';
        $strings['Pending'] = 'venter';                                                       //pending? se bruk før endelig oversettelse
        $strings['Past'] = 'Foregående';
        $strings['Restricted'] = 'Begrenset';                                                    //restricted?? se bruk før endelig oversettelse
        $strings['ViewAll'] = 'View All';
        $strings['MoveResourcesAndReservations'] = 'Flytt enheter og reservasjoner til';
        $strings['TurnOffSubscription'] = 'Slå av kalenderabbonement';
        $strings['TurnOnSubscription'] = 'Slå på kalenderabbonement';
        $strings['SubscribeToCalendar'] = 'Abboner på denne kalenderen';
        $strings['SubscriptionsAreDisabled'] = 'Administrator har fjernet muligheten til kalenderabbonering';
        $strings['NoResourceAdministratorLabel'] = '(Ingen enhetsadministrator)';
        $strings['WhoCanManageThisResource'] = 'Hvem kan administere denne enheten?';
        $strings['ResourceAdministrator'] = 'Enhetsadministrator';
        $strings['Private'] = 'Privat';
        $strings['Accept'] = 'Godta';
        $strings['Decline'] = 'Avslå';
        $strings['ShowFullWeek'] = 'Vis hele uken';
        $strings['CustomAttributes'] = 'Valgfrie attributter';
        $strings['AddAttribute'] = 'Legg til en attributt';
        $strings['EditAttribute'] = 'Oppdater en attributt';
        $strings['DisplayLabel'] = 'Vis merkelapp';
        $strings['Type'] = 'Type';
        $strings['Required'] = 'Obligatorisk';
        $strings['ValidationExpression'] = 'Godkjenningsuttrykk';
        $strings['PossibleValues'] = 'Mulige verdier';
        $strings['SingleLineTextbox'] = 'Enlinjes tekstboks';
        $strings['MultiLineTextbox'] = 'Flerlinjes tekstboks';
        $strings['Checkbox'] = 'Avkryssingsboks';
        $strings['SelectList'] = 'Listevalg';
        $strings['CommaSeparated'] = 'kommaseparert';
        $strings['Category'] = 'Kategori';
        $strings['CategoryReservation'] = 'Reservasjon';
        $strings['CategoryGroup'] = 'Gruppe';
        $strings['SortOrder'] = 'Sorteringsrekkefølge';
        $strings['Title'] = 'Tittel';
        $strings['AdditionalAttributes'] = 'Tilvalgsattributter';
        $strings['True'] = 'På';
        $strings['False'] = 'Av';
        $strings['ForgotPasswordEmailSent'] = 'En epost er sendt til oppgitt addresse med informasjon om tilbakestilling av passord';
        $strings['ActivationEmailSent'] = 'Du vil om kort tid motta en aktiverings-epost.';
        $strings['AccountActivationError'] = 'Beklager, vi kunne ikke aktivere din konto.';
        $strings['Attachments'] = 'Vedlegg';
        $strings['AttachFile'] = 'Legg ved fil';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Ingen administrator for bookingkalender';
        $strings['ScheduleAdministrator'] = 'Administrator bookingkalender';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Antall reservert';
        $strings['AllAccessories'] = 'Alt tilbehør';
        $strings['GetReport'] = 'Hent rapport';
        $strings['NoResultsFound'] = 'Ingen matchende resultater';
        $strings['SaveThisReport'] = 'Lagre denne rapporten';
        $strings['ReportSaved'] = 'Rapport lagret!';
        $strings['EmailReport'] = 'Send rapport på epost';
        $strings['ReportSent'] = 'Rapport sendt!';
        $strings['RunReport'] = 'Kjør rapport';
        $strings['NoSavedReports'] = 'Du har ingen lagrede rapporter.';
        $strings['CurrentWeek'] = 'Denne uke';
        $strings['CurrentMonth'] = 'Denne måned';
        $strings['AllTime'] = 'All tid';
        $strings['FilterBy'] = 'Filtrer ved';
        $strings['Select'] = 'Velg';
        $strings['List'] = 'Liste';
        $strings['TotalTime'] = 'Total Tid';
        $strings['Count'] = 'regn ut';
        $strings['Usage'] = 'Bruk';
        $strings['AggregateBy'] = 'Legg til ved';                                                //????????
        $strings['Range'] = 'utvalgsstørrelse';
        $strings['Choose'] = 'Velg';
        $strings['All'] = 'Alt';
        $strings['ViewAsChart'] = 'Vis som diagram';
        $strings['ReservedResources'] = 'Reserverte enheter';
        $strings['ReservedAccessories'] = 'Reservert tilbehør';
        $strings['ResourceUsageTimeBooked'] = 'Bruk av enheter - Tid booket';
        $strings['ResourceUsageReservationCount'] = 'Bruk av enheter - Antall reservasjoner';
        $strings['Top20UsersTimeBooked'] = 'Top 20 Brukere - Tid booket';
        $strings['Top20UsersReservationCount'] = 'Top 20 Brukere - Antall reservasjoner';
        $strings['ConfigurationUpdated'] = 'Konfigurasjonsfilen ble oppdatert';
        $strings['ConfigurationFileNotWritable'] = 'Konfigurasjonsfilen kan ikke skrives til. Vennligst kontroller tilgangsrettighetene på denne fila og prøv igjen.';
        $strings['ConfigurationUpdateHelp'] = 'Vis til konfigurasjonsinnstillingene i <a target=_blank href=%s>Hjelpefil</a> for dokumentasjon på disse innstillingene.';
        $strings['GeneralConfigSettings'] = 'innstillinger';
        $strings['UseSameLayoutForAllDays'] = 'Bruk samme utforming for alle dager';
        $strings['LayoutVariesByDay'] = 'Utforming varierer dag for dag';
        $strings['ManageReminders'] = 'Administrer Påminnelser';
        $strings['ReminderUser'] = 'Bruker ID påminnelser';
        $strings['ReminderMessage'] = 'Påminnelsesmelding';
        $strings['ReminderAddress'] = 'Addresser for påminnelser';
        $strings['ReminderSendtime'] = 'Tid for utsending av påminnelse';
        $strings['ReminderRefNumber'] = 'Påminnelse referansenummer';
        $strings['ReminderSendtimeDate'] = 'Dato for påminnelse';
        $strings['ReminderSendtimeTime'] = 'Tid for påminnelse (TT:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Legg til påminnelse';
        $strings['DeleteReminderWarning'] = 'Vil du virkelig slette påminnelsen?';
        $strings['NoReminders'] = 'Du har ingen kommende påminnelser.';
        $strings['Reminders'] = 'Påminnelser';
        $strings['SendReminder'] = 'Send påminnelse';
        $strings['minutes'] = 'minutter';
        $strings['hours'] = 'timer';
        $strings['days'] = 'dager';
        $strings['ReminderBeforeStart'] = 'før starttid';
        $strings['ReminderBeforeEnd'] = 'før avslutning';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS Fil';
        $strings['ThemeUploadSuccess'] = 'Dine endringer er lagret. Last siden (F5) for å oppdatere siden.';
        $strings['MakeDefaultSchedule'] = 'Gjør til min standardkalender';
        $strings['DefaultScheduleSet'] = 'Dette er nå din standardkalender';
        $strings['FlipSchedule'] = 'Bytt utforming på bookingkalender';
        $strings['Next'] = 'Neste';
        $strings['Success'] = 'Suksess';
        $strings['Participant'] = 'Deltaker';
        $strings['ResourceFilter'] = 'Enhetsfilter';
        $strings['ResourceGroups'] = 'Enhetsgrupper';
        $strings['AddNewGroup'] = 'Legg til ny gruppe';
        $strings['Quit'] = 'Avslutt';
        $strings['AddGroup'] = 'Legg til gruppe';
        $strings['StandardScheduleDisplay'] = 'Bruk standard kalendervisning';
        $strings['TallScheduleDisplay'] = 'Bruk høy kalendervisning';
        $strings['WideScheduleDisplay'] = 'Bruk utvidet kalendervisning';
        $strings['CondensedWeekScheduleDisplay'] = 'Bruk komprimert ukevisning';
        $strings['ResourceGroupHelp1'] = 'Dra og slipp enhetsgrupper for å omorganisere.';
        $strings['ResourceGroupHelp2'] = 'Høyreklikk et enhetsgruppenavn for flere valg.';
        $strings['ResourceGroupHelp3'] = 'Dra og slipp enheter for å legge dem til grupper.';
        $strings['ResourceGroupWarning'] = 'Om enhetsgrupper brukes, må hver enhet tilordnes minst en gruppe. Enheter som ikke er tilordnet en gruppe kan ikke reserveres.';
        $strings['ResourceType'] = 'Enhetstype';
        $strings['AppliesTo'] = 'Tilhører';
        $strings['UniquePerInstance'] = 'Unik pr instans';
        $strings['AddResourceType'] = 'Legg til enhetstype';
        $strings['NoResourceTypeLabel'] = '(ingen enhetstype satt)';
        $strings['ClearFilter'] = 'Rens filter';
        $strings['MinimumCapacity'] = 'Minimum kapasitet';
        $strings['Color'] = 'Farge';
        $strings['Available'] = 'Tilgjengelig';
        $strings['Unavailable'] = 'Ikke tilgjengelig';
        $strings['Hidden'] = 'Skjult';
        $strings['ResourceStatus'] = 'Enhetsstatus';
        $strings['CurrentStatus'] = 'Nåværende status';
        $strings['AllReservationResources'] = 'Alle reserverbare enheter';
        $strings['File'] = 'Fil';
        $strings['BulkResourceUpdate'] = 'Multioppdatering av enheter';
        $strings['Unchanged'] = 'Uendret';
        $strings['Common'] = 'Vanlig';
        $strings['AdvancedFilter'] = 'Avansert Filter';
        $strings['AllParticipants'] = 'Alle deltakere';
        $strings['ResourceAvailability'] = 'Tilgjengelige enheter';
        $strings['UnavailableAllDay'] = 'Utilgjengelig hele dagen';
        $strings['AvailableUntil'] = 'Tilgjengelig fram til';
        $strings['AvailableBeginningAt'] = 'Tilgjengelig fra og med';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Installer LibreBooking (kun MySQL)';
        $strings['IncorrectInstallPassword'] = 'Beklager, feil passord.';
        $strings['SetInstallPassword'] = 'Du må opprette et innstallasjonspassord før du kan fortsette.';
        $strings['InstallPasswordInstructions'] = 'I %s vennligst sett %s til et passord som er tilfeldig og vanskelig å gjette, returner deretter til denne siden.<br/>Du kan bruke %s';
        $strings['NoUpgradeNeeded'] = 'Ingen oppdatering nødvendig. Ved å kjøre en ny innstallasjon vil alle eksisterende data bli slettet og en ny kopi av LibreBooking vil bli innstallert!';
        $strings['ProvideInstallPassword'] = 'Vennligst angi ditt innstallasjonspassord.';
        $strings['InstallPasswordLocation'] = 'Dette finner du på %s i %s.';
        $strings['VerifyInstallSettings'] = 'Godkjenn følgende standardinnstillinger før du fortsetter. Eller du kan forandre dem i %s.';
        $strings['DatabaseName'] = 'Navn på database';
        $strings['DatabaseUser'] = 'Database Brukernavn';
        $strings['DatabaseHost'] = 'Database Vert';
        $strings['DatabaseCredentials'] = 'Du må angi opplysninger om en MySQL-bruker som har rettigheter til å opprette databaser. Om du ikke vet, kontakt administratoren for databaser. I mange tilfeller vil innstallasjon på rotnivå fungere.';
        $strings['MySQLUser'] = 'MySQL-bruker';
        $strings['InstallOptionsWarning'] = 'Følgende muligheter vil kanskje ikke virke i et vertsmiljø. Hvis du innstallerer på en vert, bruk MySQL-wizard verktøyet for å fullføre disse stegene.';
        $strings['CreateDatabase'] = 'Opprett databasen';
        $strings['CreateDatabaseUser'] = 'Opprett databasebrukeren';
        $strings['PopulateExampleData'] = 'Importer eksempeldata. Oppretter adminkonto: admin/passord og brukerkonto: bruker/passord';
        $strings['DataWipeWarning'] = 'Advarsel: Dette vil slette eksisterende data';
        $strings['RunInstallation'] = 'Kjør innstallasjon';
        $strings['UpgradeNotice'] = 'Du oppgraderer fra versjon <b>%s</b> til versjon <b>%s</b>';
        $strings['RunUpgrade'] = 'Kjør oppgradering';
        $strings['Executing'] = 'Gjennomfører';
        $strings['StatementFailed'] = 'Feilet. Detaljer:';
        $strings['SQLStatement'] = 'SQL Uttrykk:';
        $strings['ErrorCode'] = 'Feilkode:';
        $strings['ErrorText'] = 'Feilmelding:';
        $strings['InstallationSuccess'] = 'Installasjonen er gjennomført!';
        $strings['RegisterAdminUser'] = 'Registrer til administratorbruker. Dette er påkrevet om du ikke importerte eksempeldata. Forsikre deg om at $conf[\'settings\'][\'allow.self.registration\'] = \'true\' er i %s filen.';
        $strings['LoginWithSampleAccounts'] = 'Hvis du importerte eksempeldata, kan du nå logge deg inn med admin/password for adminbruker eller user/password for basisbruker.';
        $strings['InstalledVersion'] = 'Du kjører nå versjon %s av LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Det er anbefalt å oppgradere konfigurasjonsfilen';
        $strings['InstallationFailure'] = 'Det oppsto et problem med innstallasjonen.  Vennligst korriger og prøv igjen.';
        $strings['ConfigureApplication'] = 'Konfigurer LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Konfigurasjonsfilen er nå oppdatert!';
        $strings['ConfigUpdateFailure'] = 'Kunne ikke automatisk oppdatere konfigurasjonsfilen. Vennligst erstatt innholdet i filen config.php med følgende:';
        $strings['SelectUser'] = 'Velg bruker';
        // End Install

        // Errors
        $strings['LoginError'] = 'Brukernavn og/eller passord er feil';
        $strings['ReservationFailed'] = 'Din reservasjon kunne ikke opprettes';
        $strings['MinNoticeError'] = 'Denne reservasjonen krever forhåndsbooking.  Kan tidligst reserveres %s.';
        $strings['MaxNoticeError'] = 'Reservasjonen kan ikke utføres så langt frem i tid.  The latest date and time that can be reserved is %s.';
        $strings['MinDurationError'] = 'Reservasjonen må vare minst %s.';
        $strings['MaxDurationError'] = 'Reservasjonen kan ikke vare lengre enn %s.';
        $strings['ConflictingAccessoryDates'] = 'Det finnes ikke nok av følgende tilbehør:';
        $strings['NoResourcePermission'] = 'Du har ikke tilgangsrettigheter til en eller flere av de anmodede enheter.';
        $strings['ConflictingReservationDates'] = 'Det er konflikt i reservasjonene på følgende datoer:';
        $strings['StartDateBeforeEndDateRule'] = 'Starttid må være før sluttid.';
        $strings['StartIsInPast'] = 'Du må velge en starttid som ikke er passert.';
        $strings['EmailDisabled'] = 'Adiministrator har slått av epostkunngjøringer.';
        $strings['ValidLayoutRequired'] = 'Tidsperioder må angis for alle 24 timer, men begynnelse og slutt kl 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'Det er problemer med de valgfrie attributtene du anga:';
        $strings['CustomAttributeRequired'] = '%s er et obligatorisk felt.';
        $strings['CustomAttributeInvalid'] = 'Angitt verdi for %s er ugyldig.';
        $strings['AttachmentLoadingError'] = 'Beklager, et problem oppsto under opplasting av filen.';
        $strings['InvalidAttachmentExtension'] = 'Du kan bare laste opp filer av type: %s';
        $strings['InvalidStartSlot'] = 'Ønsket starttidspunkt er ikke gyldig.';
        $strings['InvalidEndSlot'] = 'Ønsket sluttidspunkt er ikke gyldig.';
        $strings['MaxParticipantsError'] = '%s kan kun ha %s deltakere.';
        $strings['ReservationCriticalError'] = 'Det oppsto en kritisk feil under lagringen av din reservasjon. Kontakt administrator om problemet fortsetter.';
        $strings['InvalidStartReminderTime'] = 'Starttid for påminnelse ikke gyldig.';
        $strings['InvalidEndReminderTime'] = 'Sluttid for påminnelse ikke gyldig.';
        $strings['QuotaExceeded'] = 'Kvotegrensen overskredet.';
        $strings['MultiDayRule'] = '%s tillater ikke reservasjoner over flere dager.';
        $strings['InvalidReservationData'] = 'Det oppsto et problem med din anmodning om reservasjon.';
        $strings['PasswordError'] = 'Passordet må inneholde minst %s bokstaver og minst %s tall.';
        $strings['PasswordErrorRequirements'] = 'Passordet må inneholde en kombinasjon av minst %s store og små bokstaver og %s tall.';
        $strings['NoReservationAccess'] = 'Du har ikke tilgangsrettigheter til å endre denne reservasjonen.';
        $strings['PasswordControlledExternallyError'] = 'Passordet ditt er kontrollert av et eksternt system og kan ikke forandres her.';
        $strings['NoResources'] = 'Du har ikke lagt til noen enheter.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Opprett reservasjon';
        $strings['EditReservation'] = 'Redigere reservasjon';
        $strings['LogIn'] = 'Logg inn';
        $strings['ManageReservations'] = 'Reservasjoner';
        $strings['AwaitingActivation'] = 'Avventer aktivering';
        $strings['PendingApproval'] = 'Avventer godkjenning';
        $strings['ManageSchedules'] = 'Bookingkalendere';
        $strings['ManageResources'] = 'Enheter';
        $strings['ManageAccessories'] = 'Tilbehør';
        $strings['ManageUsers'] = 'Brukere';
        $strings['ManageGroups'] = 'Grupper';
        $strings['ManageQuotas'] = 'Kvoter';
        $strings['ManageBlackouts'] = 'Utilgjengelighet';
        $strings['MyDashboard'] = 'Mitt dashbord';
        $strings['ServerSettings'] = 'Serverinnstillinger';
        $strings['Dashboard'] = 'Dashbord';
        $strings['Help'] = 'Hjelp';
        $strings['Administration'] = 'Administrasjon';
        $strings['About'] = 'Om';
        $strings['Bookings'] = 'Bookinger';
        $strings['Schedule'] = 'Bookingkalender';
        $strings['Reservations'] = 'Reservasjoner';
        $strings['Account'] = 'Konto';
        $strings['EditProfile'] = 'Endre min profil';
        $strings['FindAnOpening'] = 'Finn et leietidspunkt';
        $strings['OpenInvitations'] = 'Åpne invitasjoner';
        $strings['MyCalendar'] = 'Min kalender';
        $strings['ResourceCalendar'] = 'Enhetskalender';
        $strings['Reservation'] = 'Ny reservasjon';
        $strings['Install'] = 'Installasjon';
        $strings['ChangePassword'] = 'Endre passord';
        $strings['MyAccount'] = 'Min konto';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Programstyring';
        $strings['ForgotPassword'] = 'Glemt passord';
        $strings['NotificationPreferences'] = 'Kunngjøringsinnstillinger';
        $strings['ManageAnnouncements'] = 'Kunngjøringer';
        $strings['Responsibilities'] = 'Ansvarsforhold';
        $strings['GroupReservations'] = 'Gruppereservasjoner';
        $strings['ResourceReservations'] = 'Enhetsreservasjoner';
        $strings['Customization'] = 'Personlige innstillinger';
        $strings['Attributes'] = 'Attributter';
        $strings['AccountActivation'] = 'Kontoaktivering';
        $strings['ScheduleReservations'] = 'Opprett reservasjoner';
        $strings['Reports'] = 'Rapporter';
        $strings['GenerateReport'] = 'Opprett ny rapport';
        $strings['MySavedReports'] = 'Mine lagrede rapporter';
        $strings['CommonReports'] = 'Vanlige rapporter';
        $strings['ViewDay'] = 'Vis dag';
        $strings['Group'] = 'Gruppe';
        $strings['ManageConfiguration'] = 'Program Konfigurasjon';
        $strings['LookAndFeel'] = 'Web-utseende';
        $strings['ManageResourceGroups'] = 'Enhetsgrupper';
        $strings['ManageResourceTypes'] = 'Enhetstyper';
        $strings['ManageResourceStatus'] = 'Enhetsstatuser';
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
        $strings['ReservationApprovedSubject'] = 'Din reservasjon er godkjent';
        $strings['ReservationCreatedSubject'] = 'Din reservasjon ble opprettet';
        $strings['ReservationUpdatedSubject'] = 'Din reservasjon ble oppdatert';
        $strings['ReservationDeletedSubject'] = 'Din reservasjon ble slettet';
        $strings['ReservationCreatedAdminSubject'] = 'Kunngjøring: En reservasjon ble opprettet';
        $strings['ReservationUpdatedAdminSubject'] = 'Kunngjøring: En reservasjon ble oppdatert';
        $strings['ReservationDeleteAdminSubject'] = 'Kunngjøring: En reservasjon ble slettet';
        $strings['ReservationApprovalAdminSubject'] = 'Kunngjøring: En reservasjon trenger godkjenning';
        $strings['ParticipantAddedSubject'] = 'Kunngjøring: Deltaker lagt til en reservasjon';
        $strings['ParticipantDeletedSubject'] = 'Reservasjon fjernet';
        $strings['InviteeAddedSubject'] = 'Reservasjonsinvitasjon';
        $strings['ResetPassword'] = 'Anmoding om tilbakestilling av passord';
        $strings['ActivateYourAccount'] = 'Vennligst aktiver din konto';
        $strings['ReportSubject'] = 'Din anmodede rapport (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Reservasjonen av %s starter om kort tid';
        $strings['ReservationEndingSoonSubject'] = 'Reservasjonen av %s slutter snart';
        $strings['UserAdded'] = 'En ny bruker er lagt til';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Du har ingen tidligere reservasjoner';
        $strings['PastReservations'] = 'Tidligere reservasjoner';
        $strings['AllNoPastReservations'] = 'Det er ingen tidligere reservasjoner de siste %s dagene';
        $strings['AllPastReservations'] = 'Alle tidligere reservasjoner';
        $strings['Yesterday'] = 'I går';
        $strings['EarlierThisWeek'] = 'Tidligere denne uken';
        $strings['PreviousWeek'] = 'Forrige uke';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Din gruppe har ingen kommende reservasjoner';
        $strings['GroupUpcomingReservations'] = 'Mine grupp(er) kommende reservasjoner';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Det oppstod en feil under pålogging med Facebook. Prøv igjen.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Du har ingen reservasjoner som venter på godkjenning';
        $strings['PendingApprovalReservations'] = 'Reservasjoner venter på godkjenning';
        $strings['LaterThisMonth'] = 'Senere denne måneden';
        $strings['LaterThisYear'] = 'Senere i år';
        $strings['Remaining'] = 'Gjenstående';        
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Det er ingen manglende utsjekkingsreservasjoner';
        $strings['MissingCheckOutReservations'] = 'Manglende utsjekkingsreservasjoner';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Kan ikke se reservasjonsdetaljer fordi du ikke har tillatelser til noen av ressursene i denne reservasjonen';
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
        DAY NAMES
        All of these arrays MUST start with Sunday as the first element
        and go through the seven day week, ending on Saturday
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

    protected function _LoadMonths()
    {
        $months = parent::_LoadMonths();

        /***
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Januar', 'Februar', 'Mars', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Desember'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];

        $this->Months = $months;
        return $this->Months;
    }

    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Æ', 'Ø', 'Å'];
        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'no_no';
    }
}
