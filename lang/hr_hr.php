<?php

require_once('Language.php');
require_once('en_gb.php');

class hr_hr extends en_gb
{
    public function __construct()
    {
        parent::__construct();
        $this->Charset = 'UTF-8';
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Ime';
        $strings['LastName'] = 'Prezime';
        $strings['Timezone'] = 'Vremenska zona';
        $strings['Edit'] = 'Uredi';
        $strings['Change'] = 'Promjeni';
        $strings['Rename'] = 'Preimenuj';
        $strings['Remove'] = 'Makni';
        $strings['Delete'] = 'Obriďż˝i';
        $strings['Update'] = 'Aďż˝uriraj';
        $strings['Cancel'] = 'Odustani';
        $strings['Add'] = 'Dodaj';
        $strings['Name'] = 'Ime';
        $strings['Yes'] = 'Da';
        $strings['No'] = 'Ne';
        $strings['FirstNameRequired'] = 'Ime je obavezno.';
        $strings['LastNameRequired'] = 'Prezime je obavezno.';
        $strings['PwMustMatch'] = 'Potvrda lozinke mora odgovarati lozinki.';
        $strings['ValidEmailRequired'] = 'Vaďż˝eca mail adresa je obavezna.';
        $strings['UniqueEmailRequired'] = 'Mail adresa se vec koristi';
        $strings['UniqueUsernameRequired'] = 'Korisnik je vec registriran.';
        $strings['UserNameRequired'] = 'Korisnicko ime je obavezno.';
        $strings['CaptchaMustMatch'] = 'Unesite slova sa sigurnosne slike.';
        $strings['Today'] = 'Danas';
        $strings['Week'] = 'Tjedan';
        $strings['Month'] = 'Mjesec';
        $strings['BackToCalendar'] = 'Povratak na kalendar';
        $strings['BeginDate'] = 'Pocetak';
        $strings['EndDate'] = 'Kraj';
        $strings['Username'] = 'Korisnicko ime';
        $strings['Password'] = 'Lozinka';
        $strings['PasswordConfirmation'] = 'Potvrdi lozinku';
        $strings['DefaultPage'] = 'Pocetna stranica';
        $strings['MyCalendar'] = 'Moj kalendar';
        $strings['ScheduleCalendar'] = 'Kalendar rasporeda';
        $strings['Registration'] = 'Registracija';
        $strings['NoAnnouncements'] = 'Nema obavijesti';
        $strings['Announcements'] = 'Obavijesti';
        $strings['NoUpcomingReservations'] = 'Nema nadolazecih rezervacija';
        $strings['UpcomingReservations'] = 'Nadolazece rezervacije';
        $strings['ShowHide'] = 'Prikaďż˝i/Sakrij';
        $strings['Error'] = 'Greďż˝ka';
        $strings['ReturnToPreviousPage'] = 'Povratak na prethodnu stranicu';
        $strings['UnknownError'] = 'Dje je ba zapelo?';
        $strings['InsufficientPermissionsError'] = 'Nemate dozvolu za pristup';
        $strings['MissingReservationResourceError'] = 'Nije izabran resurs';
        $strings['MissingReservationScheduleError'] = 'Nije izabran resurs';
        $strings['DoesNotRepeat'] = 'Ne ponavlja';
        $strings['Daily'] = 'Dnevno';
        $strings['Weekly'] = 'Tjedno';
        $strings['Monthly'] = 'Mjesecno';
        $strings['Yearly'] = 'Godiďż˝nje';
        $strings['RepeatPrompt'] = 'Ponovi';
        $strings['hours'] = 'sati';
        $strings['days'] = 'dana';
        $strings['weeks'] = 'tjedana';
        $strings['months'] = 'mjeseci';
        $strings['years'] = 'godina';
        $strings['day'] = 'dan';
        $strings['week'] = 'tjedan';
        $strings['month'] = 'mjesec';
        $strings['year'] = 'godina';
        $strings['repeatDayOfMonth'] = 'dan u mjesecu';
        $strings['repeatDayOfWeek'] = 'dan u tjednu';
        $strings['RepeatUntilPrompt'] = 'Do';
        $strings['RepeatEveryPrompt'] = 'Svaki';
        $strings['RepeatDaysPrompt'] = 'Na';
        $strings['CreateReservationHeading'] = 'Kreiraj novu rezervaciju';
        $strings['EditReservationHeading'] = 'Uredivanje rezervacije %s';
        $strings['ViewReservationHeading'] = 'Pregledavanje rezervacije %s';
        $strings['ReservationErrors'] = 'Izmjena rezervacije';
        $strings['Create'] = 'Kreiraj';
        $strings['ThisInstance'] = 'Samo ovaj put';
        $strings['AllInstances'] = 'Svaki put';
        $strings['FutureInstances'] = 'Ubuduce';
        $strings['Print'] = 'Print';
        $strings['ShowHideNavigation'] = 'Pokaďż˝i/Sakrij Navigaciju';
        $strings['ReferenceNumber'] = 'Referentni broj';
        $strings['Tomorrow'] = 'Sutra';
        $strings['LaterThisWeek'] = 'Kasnije ovaj tjedan';
        $strings['NextWeek'] = 'Sljedeci tjedan';
        $strings['SignOut'] = 'Odjavi se';
        $strings['LayoutDescription'] = 'Pocni na %s, pokaďż˝i %s dana odjednom';
        $strings['AllResources'] = 'Sve resurse';
        $strings['TakeOffline'] = 'Offline';
        $strings['BringOnline'] = 'Online';
        $strings['AddImage'] = 'Dodaj sliku';
        $strings['NoImage'] = 'Slika nije dodijeljena';
        $strings['Move'] = 'Makni';
        $strings['AppearsOn'] = 'Pojavljuje se na %s';
        $strings['Location'] = 'Lokacija';
        $strings['NoLocationLabel'] = '(nije podeďż˝ena lokacija)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(nema informacija o kontaktu)';
        $strings['Description'] = 'Opis';
        $strings['NoDescriptionLabel'] = '(nema opisa)';
        $strings['Notes'] = 'Biljeďż˝ke';
        $strings['NoNotesLabel'] = '(nema biljeďż˝ki)';
        $strings['NoTitleLabel'] = '(nema naziva)';
        $strings['UsageConfiguration'] = 'Konfiguracija koriďż˝tenja';
        $strings['ChangeConfiguration'] = 'Izmjeni konfiguraciju';
        $strings['ResourceMinLength'] = 'Rezervacija mora trajati najmanje %s';
        $strings['ResourceMinLengthNone'] = 'Nema minimalnog trajanja rezervacije';
        $strings['ResourceMaxLength'] = 'Rezervacija ne moďż˝e trajati manje od %s';
        $strings['ResourceMaxLengthNone'] = 'Nema maksimalnog trajanja rezervacije';
        $strings['ResourceRequiresApproval'] = 'Rezervacija mora biti odobrena';
        $strings['ResourceRequiresApprovalNone'] = 'Rezervacija ne zahtjeva odobrenje';
        $strings['ResourcePermissionAutoGranted'] = 'Dozvola je automatski odobrena';
        $strings['ResourcePermissionNotAutoGranted'] = 'Dozvola nije automatski odobrena';
        $strings['ResourceMinNotice'] = 'Rezervacija mora biti napravljna najmanje %s prije pocetka';
        $strings['ResourceMinNoticeNone'] = 'Rezervacija moďż˝e biti napravljena do trenutnog vremena';
        $strings['ResourceMaxNotice'] = 'Rezervacija ne mora zavrďż˝iti %s od trenutnog vremena';
        $strings['ResourceMaxNoticeNone'] = 'Rezervacija moďż˝e zavrďż˝iti bilo kada';
        $strings['ResourceBufferTime'] = 'Mora biti %s izmedu rezervacija';
        $strings['ResourceBufferTimeNone'] = 'Nema razmaka izmedu rezervacija';
        $strings['ResourceAllowMultiDay'] = 'Rezervacije mogu biti preko dana';
        $strings['ResourceNotAllowMultiDay'] = 'Rezervacije ne mogu biti preko dana';
        $strings['ResourceCapacity'] = 'Ovaj resurs ima kapacitet od %s osoba';
        $strings['ResourceCapacityNone'] = 'Ovaj resurs ima neogranicen kapacitet';
        $strings['AddNewResource'] = 'Dodaj novi resurs';
        $strings['AddNewUser'] = 'Dodaj novog korisnika';
        $strings['AddUser'] = 'Dodaj korisnika';
        $strings['Schedule'] = 'Raspored';
        $strings['AddResource'] = 'Dodaj resurs';
        $strings['Capacity'] = 'Kapacitet';
        $strings['Access'] = 'Pristup';
        $strings['Duration'] = 'Trajanje';
        $strings['Active'] = 'Aktivan';
        $strings['Inactive'] = 'Neaktivan';
        $strings['ResetPassword'] = 'Resetiraj lozinku';
        $strings['LastLogin'] = 'Posljednje logiranje';
        $strings['Search'] = 'Traďż˝i';
        $strings['ResourcePermissions'] = 'Dozvole resursa';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Groups'] = 'Grupe';
        $strings['ResetPassword'] = 'Resetiraj lozinku';
        $strings['AllUsers'] = 'Svi korisnici';
        $strings['AllGroups'] = 'Sve grupe';
        $strings['AllSchedules'] = 'Svi rasporedi';
        $strings['UsernameOrEmail'] = 'Korisnicko ime ili ďż˝ifra';
        $strings['Members'] = 'Clanovi';
        $strings['QuickSlotCreation'] = 'Kreiraj mjesto svakih %s minuta izmedu %s i %s';
        $strings['ApplyUpdatesTo'] = 'Dodaj aďż˝uriranja';
        $strings['CancelParticipation'] = 'Otkaďż˝i sudjelovanje';
        $strings['Attending'] = 'Prisustvovanje';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['reservations'] = 'rezervacije';
        $strings['reservation'] = 'rezervacija';
        $strings['ChangeCalendar'] = 'Izmjeni kalendar';
        $strings['AddQuota'] = 'Dodaj kvotu';
        $strings['FindUser'] = 'Traďż˝i korisnika';
        $strings['Created'] = 'Kreirano';
        $strings['LastModified'] = 'Zadnja izmjena';
        $strings['GroupName'] = 'Ime grupe';
        $strings['GroupMembers'] = 'Clanovi grupe';
        $strings['GroupRoles'] = 'Uloge grupe';
        $strings['GroupAdmin'] = 'Administrator grupe';
        $strings['Actions'] = 'Aktivnost';
        $strings['CurrentPassword'] = 'Trenutna lozinka';
        $strings['NewPassword'] = 'Nova lozinka';
        $strings['InvalidPassword'] = 'Trenutna lozinka je netocna';
        $strings['PasswordChangedSuccessfully'] = 'Vaďż˝a lozinka je promjenjena';
        $strings['SignedInAs'] = 'Logiran kao';
        $strings['NotSignedIn'] = 'Niste ulogirani';
        $strings['ReservationTitle'] = 'Naslov rezervacije';
        $strings['ReservationDescription'] = 'Opis rezervacije';
        $strings['ResourceList'] = 'Resursi za rezervaciju';
        $strings['Accessories'] = 'Dodaci';
        $strings['ParticipantList'] = 'Sudjeluju';
        $strings['InvitationList'] = 'Pozvani';
        $strings['AccessoryName'] = 'Ime dodatka';
        $strings['QuantityAvailable'] = 'Dostupna kolicina';
        $strings['Resources'] = 'Resursi';
        $strings['Participants'] = 'Sudjeluju';
        $strings['User'] = 'Korisnik';
        $strings['Resource'] = 'Resurs';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Dozvoli';
        $strings['Page'] = 'Stranica';
        $strings['Rows'] = 'Red';
        $strings['Unlimited'] = 'Neograniceno';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email Adresa';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organizacija';
        $strings['Position'] = 'Pozicija';
        $strings['Language'] = 'Jezik';
        $strings['Permissions'] = 'Dozvole';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Nadi grupu';
        $strings['Manage'] = 'Upravljanje';
        $strings['None'] = 'Niďż˝ta';
        $strings['AddToOutlook'] = 'Dodaj u kalendar';
        $strings['Done'] = 'Gotovo';
        $strings['RememberMe'] = 'Zapamti me';
        $strings['FirstTimeUser?'] = 'Prvi put korisnik?';
        $strings['CreateAnAccount'] = 'Kreiraj racun';
        $strings['ViewSchedule'] = 'Vidi raspored';
        $strings['ForgotMyPassword'] = 'Zaboravio sam lozinku';
        $strings['YouWillBeEmailedANewPassword'] = 'Biti ce vam poslana nasumce generirana lozinka';
        $strings['Close'] = 'Zatvori';
        $strings['ExportToCSV'] = 'Izvezi u CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Radim...';
        $strings['Login'] = 'Logiranje';
        $strings['AdditionalInformation'] = 'Dodatne informacije';
        $strings['AllFieldsAreRequired'] = 'sva polja su obavezna';
        $strings['Optional'] = 'opcija';
        $strings['YourProfileWasUpdated'] = 'Vaďż˝ profil je aďż˝uriran';
        $strings['YourSettingsWereUpdated'] = 'Postavke su aďż˝urirane';
        $strings['Register'] = 'Registriraj';
        $strings['SecurityCode'] = 'Sigurnosni kod';
        $strings['ReservationCreatedPreference'] = 'Kada kreiram rezervaciju ili je kreirana u moje ime';
        $strings['ReservationUpdatedPreference'] = 'Kada aďż˝uriram rezervaciju ili je kreirana u moje ime';
        $strings['ReservationDeletedPreference'] = 'Kada obriďż˝em rezervaciju ili je kreirana u moje ime';
        $strings['ReservationApprovalPreference'] = 'Kada je rezervacija na cekanju odobrena';
        $strings['PreferenceSendEmail'] = 'Poďż˝alji mi email';
        $strings['PreferenceNoEmail'] = 'Ne obavjeďż˝tavaj me';
        $strings['ReservationCreated'] = 'Vaďż˝a rezervacija je kreirana!';
        $strings['ReservationUpdated'] = 'Vaďż˝a rezervacija je aďż˝urirana!';
        $strings['ReservationRemoved'] = 'Vaďż˝a rezervacija je obrisana';
        $strings['YourReferenceNumber'] = 'Vaďż˝ referentni broj je %s';
        $strings['UpdatingReservation'] = 'Aďż˝uriraj rezervacije';
        $strings['ChangeUser'] = 'Promjeni korisnika';
        $strings['MoreResources'] = 'Viďż˝e resursa';
        $strings['ReservationLength'] = 'Trajanje rezervacije';
        $strings['ParticipantList'] = 'Lista sudionika';
        $strings['AddParticipants'] = 'Dodaj sudionika';
        $strings['InviteOthers'] = 'Pozovi ostale';
        $strings['AddResources'] = 'Dodaj resurs';
        $strings['AddAccessories'] = 'Dodaj opremu';
        $strings['Accessory'] = 'Oprema';
        $strings['QuantityRequested'] = 'Zahtjevana kolicina';
        $strings['CreatingReservation'] = 'Kreiram rezervaciju';
        $strings['UpdatingReservation'] = 'Aďż˝uriram rezervaciju';
        $strings['DeleteWarning'] = 'Ova akcija je nepovratna!';
        $strings['DeleteAccessoryWarning'] = 'Brisanje opreme ce je ukloniti iz svih rezervacija.';
        $strings['AddAccessory'] = 'Dodaj opremu';
        $strings['AddBlackout'] = 'Add Blackout';
        $strings['AllResourcesOn'] = 'Svi resursi ';
        $strings['Reason'] = 'Razlog';
        $strings['BlackoutShowMe'] = 'Prikaďż˝i rezervacije u konfliktu';
        $strings['BlackoutDeleteConflicts'] = 'Obriďż˝i rezervacije u konfliktu';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Izmedu';
        $strings['CreatedBy'] = 'Kreirano od';
        $strings['BlackoutCreated'] = 'Blackout Created';
        $strings['BlackoutNotCreated'] = 'Blackout could not be created';
        $strings['BlackoutUpdated'] = 'Blackout Updated';
        $strings['BlackoutNotUpdated'] = 'Blackout could not be created';
        $strings['BlackoutConflicts'] = 'There are conflicting blackout times';
        $strings['ReservationConflicts'] = 'Nema rezervacija u konfliktu';
        $strings['UsersInGroup'] = 'Korisnici u grupi';
        $strings['Browse'] = 'Pretraďż˝i';
        $strings['DeleteGroupWarning'] = 'Brisanje ove grupe ce obrisati sve pridruzene dozvole resursa. Korisnici u grupi bi mogli izgubiti pristup resursima.';
        $strings['WhatRolesApplyToThisGroup'] = 'Koju ulogu dodajete ovoj grupi?';
        $strings['WhoCanManageThisGroup'] = 'Tko moze uredjivati grupu?';
        $strings['WhoCanManageThisSchedule'] = 'Tko moze uredjivati raspored?';
        $strings['AddGroup'] = 'Dodaj grupu';
        $strings['AllQuotas'] = 'Sve kvote';
        $strings['QuotaReminder'] = 'Zapamtite: kvote se provode na temelju vremenske zone rasporeda.';
        $strings['AllReservations'] = 'Sve rezervacije';
        $strings['PendingReservations'] = 'Rezervacije na cekanju';
        $strings['Approving'] = 'Dozvoljavam';
        $strings['MoveToSchedule'] = 'Makni u raspored';
        $strings['DeleteResourceWarning'] = 'Brisanje resursa ce obrisati sve pripadajuce podatke ukljucujuci';
        $strings['DeleteResourceWarningReservations'] = 'sve prosle, trenutne i buduce rezervacije koje su mu dodane';
        $strings['DeleteResourceWarningPermissions'] = 'sve dodane dozvole';
        $strings['DeleteResourceWarningReassign'] = 'Molimo preraspodjeliti sve ďż˝to ne ďż˝elite da se briďż˝e prije nastavka';
        $strings['ScheduleLayout'] = 'Izgled (sva vremena %s)';
        $strings['ReservableTimeSlots'] = 'Minutaze rezervacije';
        $strings['BlockedTimeSlots'] = 'Blokiranje minutaze';
        $strings['ThisIsTheDefaultSchedule'] = 'Ovo je zadani raspored';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Zadani raspored ne moze biti obrisan';
        $strings['MakeDefault'] = 'Postavi kao zadani';
        $strings['BringDown'] = 'Spusti';
        $strings['ChangeLayout'] = 'Promjeni izgled';
        $strings['AddSchedule'] = 'Dodaj raspored';
        $strings['StartsOn'] = 'Pocinje u';
        $strings['NumberOfDaysVisible'] = 'Broj vidljivih dana';
        $strings['UseSameLayoutAs'] = 'Koristi isti izgled kao';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Opcionalni naziv';
        $strings['LayoutInstructions'] = 'Enter one slot per line.  Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['AddUser'] = 'Dodaj korisnika';
        $strings['UserPermissionInfo'] = 'Stvarni pristupi resursima mogu biti razliciti, ovisno o Korisnickoj ulozi, dozvolama grupe ili vanjskim postavke dozvola';
        $strings['DeleteUserWarning'] = 'Brisanje ovog korisnika ce ukloniti sve njihove sadaďż˝nje, buduce, i prosle rezervacije.';
        $strings['AddAnnouncement'] = 'Dodaj obavijest';
        $strings['Announcement'] = 'Obavijest';
        $strings['Priority'] = 'Prioritet';
        $strings['Reservable'] = 'Dostupno';
        $strings['Unreservable'] = 'Nedostupno';
        $strings['Reserved'] = 'Rezervirano';
        $strings['MyReservation'] = 'Moje rezervacije';
        $strings['Pending'] = 'Na cekanju';
        $strings['Past'] = 'Proslo';
        $strings['Restricted'] = 'Zabranjeno';
        $strings['ViewAll'] = 'Vidi sve';
        $strings['MoveResourcesAndReservations'] = 'makni resurse i rezervacije u';
        $strings['TurnOffSubscription'] = 'Iskljuci upise u kalendar';
        $strings['TurnOnSubscription'] = 'Dozvoli upise u kalendar';
        $strings['SubscribeToCalendar'] = 'Upisi se u ovaj kalendar';
        $strings['SubscriptionsAreDisabled'] = 'Administrator je iskljucio upise u kalendar';
        $strings['NoResourceAdministratorLabel'] = '(nema administratora resursa)';
        $strings['WhoCanManageThisResource'] = 'Tko moze uredjivati ovaj resurs?';
        $strings['ResourceAdministrator'] = 'Administrator resursa';
        $strings['Private'] = 'Privatno';
        $strings['Accept'] = 'Prihvati';
        $strings['Decline'] = 'Odbij';
        $strings['ShowFullWeek'] = 'Pokazi cijeli tjedan';
        $strings['CustomAttributes'] = 'Prilagodjene znacajke';
        $strings['AddAttribute'] = 'Dodaj znacajku';
        $strings['EditAttribute'] = 'Azuriraj znacajke';
        $strings['DisplayLabel'] = 'Prikazi naziv';
        $strings['Type'] = 'Tip';
        $strings['Required'] = 'Obavezno';
        $strings['ValidationExpression'] = 'Provjera valjanosti';
        $strings['PossibleValues'] = 'Moguce vrijednosti';
        $strings['SingleLineTextbox'] = 'Jednolinijski Textbox';
        $strings['MultiLineTextbox'] = 'Viselinijski Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Lista odabira';
        $strings['CommaSeparated'] = 'odvojeno zarezom';
        $strings['Category'] = 'Kategorija';
        $strings['CategoryReservation'] = 'Rezervacija';
        $strings['CategoryGroup'] = 'Grupa';
        $strings['SortOrder'] = 'Sortiraj';
        $strings['Title'] = 'Naslov';
        $strings['AdditionalAttributes'] = 'Dodatne znacajke';
        $strings['True'] = 'Da';
        $strings['False'] = 'Ne';
        $strings['ForgotPasswordEmailSent'] = 'E-mail je poslan na adresu s uputama za resetiranje zaporke';
        $strings['ActivationEmailSent'] = 'Uskoro cete primiti aktivacijiski email.';
        $strings['AccountActivationError'] = 'Zao nam je, ne mozemo aktivirati vas racun';
        $strings['Attachments'] = 'Prilozi';
        $strings['AttachFile'] = 'Prilozi datoteku';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Nema administratora rasporeda';
        $strings['ScheduleAdministrator'] = 'Admnistrator rasporeda';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Kolicina rasporeda';
        $strings['AllAccessories'] = 'Sva oprema';
        $strings['GetReport'] = 'Izvjestaj';
        $strings['NoResultsFound'] = 'Nema rezultata';
        $strings['SaveThisReport'] = 'Snimi izvjestaj';
        $strings['ReportSaved'] = 'Izvjestaj snimljen!';
        $strings['EmailReport'] = 'Posalji izvjestaj emailom';
        $strings['ReportSent'] = 'Izvjestaj poslan!';
        $strings['RunReport'] = 'Pokreni izvjestaj';
        $strings['NoSavedReports'] = 'Nemate spremnljenih izvjestaja.';
        $strings['CurrentWeek'] = 'Tekuci tjedan';
        $strings['CurrentMonth'] = 'Tekuci mjesec';
        $strings['AllTime'] = 'Sve vrijeme';
        $strings['FilterBy'] = 'Filtiraj po';
        $strings['Select'] = 'Odaberi';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Ukupno vrijeme';
        $strings['Count'] = 'Count';
        $strings['Usage'] = 'Upotreba';
        $strings['AggregateBy'] = 'Aggregate By';
        $strings['Range'] = 'Range';
        $strings['Choose'] = 'Odaberi';
        $strings['All'] = 'Sve';
        $strings['ViewAsChart'] = 'Vidi kao nacrt';
        $strings['ReservedResources'] = 'Rezervirani resursi';
        $strings['ReservedAccessories'] = 'Rezervirana oprema';
        $strings['ResourceUsageTimeBooked'] = 'Upotreba resursa - vrijeme bukiranja';
        $strings['ResourceUsageReservationCount'] = 'Upotreba resursa - broj rezervacije';
        $strings['Top20UsersTimeBooked'] = 'Top 20 korisnika - po vremenu';
        $strings['Top20UsersReservationCount'] = 'Top 20 korisnika - po broju rezervacija';
        $strings['ConfigurationUpdated'] = 'Konfiguracijska datoteka azurirana';
        $strings['ConfigurationUiNotEnabled'] = 'This page cannot be accessed because $conf[\'settings\'][\'pages\'][\'enable.configuration\'] is set to false or missing.';
        $strings['ConfigurationFileNotWritable'] = 'The config file is not writable. Please check the permissions of this file and try again.';
        $strings['ConfigurationUpdateHelp'] = 'Refer to the Configuration section of the <a target=_blank href=%s>Help File</a> for documentation on these settings.';
        $strings['GeneralConfigSettings'] = 'postavke';
        $strings['UseSameLayoutForAllDays'] = 'Koristi isti izgled za sve dane';
        $strings['LayoutVariesByDay'] = 'Izgled varira po danima';
        $strings['ManageReminders'] = 'Podsjetnik';
        $strings['ReminderUser'] = 'Korisnicki ID';
        $strings['ReminderMessage'] = 'Poruka';
        $strings['ReminderAddress'] = 'Adrese';
        $strings['ReminderSendtime'] = 'Vrijeme slanja';
        $strings['ReminderRefNumber'] = 'Referentni broj rezervacije';
        $strings['ReminderSendtimeDate'] = 'Datum podsjetnika';
        $strings['ReminderSendtimeTime'] = 'Vrijeme podsjetnika (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Dodaj podsjetnik';
        $strings['DeleteReminderWarning'] = 'Sigurni ste?';
        $strings['NoReminders'] = 'Nemate podsjetnika.';
        $strings['Reminders'] = 'Podsjetnici';
        $strings['SendReminder'] = 'Posalji podsjetnik';
        $strings['minutes'] = 'minuta';
        $strings['hours'] = 'sati';
        $strings['days'] = 'dana';
        $strings['ReminderBeforeStart'] = 'prije pocetka';
        $strings['ReminderBeforeEnd'] = 'prije kraja';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS datoteka';
        $strings['ThemeUploadSuccess'] = 'Promjene su snimljene. Osvjezite stranicu (F5)';
        $strings['MakeDefaultSchedule'] = 'Postavi kao zadani raspored';
        $strings['DefaultScheduleSet'] = 'Ovo je sada zadani raspored';
        $strings['FlipSchedule'] = 'Okrenite izgled rasporeda';
        $strings['Next'] = 'Sljedeci';
        $strings['Success'] = 'Uspjesno';
        $strings['Participant'] = 'Sudionik';
        $strings['ResourceFilter'] = 'Filter resursa';
        $strings['ResourceGroups'] = 'Grupa resursa';
        $strings['AddNewGroup'] = 'Dodaj novu grupu';
        $strings['Quit'] = 'Izlaz';
        $strings['AddGroup'] = 'Dodaj grupu';
        $strings['StandardScheduleDisplay'] = 'Koristi standardni prikaz rasporeda';
        $strings['TallScheduleDisplay'] = 'Koristi poviseni prikaz rasporeda';
        $strings['WideScheduleDisplay'] = 'Koristi prosireni prikaz rasporeda';
        $strings['CondensedWeekScheduleDisplay'] = 'Koristi stisnuti prikaz rasporeda';
        $strings['ResourceGroupHelp1'] = 'Povuci i pusti resurse za reorganizaciju.';
        $strings['ResourceGroupHelp2'] = 'Desni klik na ime grupe resursa za dodatne opcije.';
        $strings['ResourceGroupHelp3'] = 'Povuci i pusti resurse za dodavanje u grupu.';
        $strings['ResourceGroupWarning'] = 'Ako koristite grupe resursa, svaki resurs mora biti dodan u najmanje jednu grupu. Nedodjeljeni resursi ne mogu se rezervirati.';
        $strings['ResourceType'] = 'Tip resursa';
        $strings['AppliesTo'] = 'Odnosi se na';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Dodaj tip resursa';
        $strings['NoResourceTypeLabel'] = '(nije postavljen tip resursa)';
        $strings['ClearFilter'] = 'obrisi filter';
        $strings['MinimumCapacity'] = 'minimalni kapacitet';
        $strings['Color'] = 'Boja';
        $strings['Available'] = 'Dostupan';
        $strings['Unavailable'] = 'Nedostupan';
        $strings['Hidden'] = 'Skriven';
        $strings['ResourceStatus'] = 'Status resursa';
        $strings['CurrentStatus'] = 'Trenutni status';
        $strings['AllReservationResources'] = 'Svi rezervacijski resursi';
        $strings['File'] = 'Datoteka';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Instaliraj LibreBooking (MySQL samo)';
        $strings['IncorrectInstallPassword'] = 'ďż˝ao nam je, lozinka je netocna!.';
        $strings['SetInstallPassword'] = 'Morate postaviti lozinku prije instaliranja.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'Nije potrbno aďż˝urirati.Proces instalacije izbrisati ce postojece podatke i instalirati novu verziju!';
        $strings['ProvideInstallPassword'] = 'Molimo unesite instalacijsku lozinku.';
        $strings['InstallPasswordLocation'] = 'Moze biti nadeno u %s u %s.';
        $strings['VerifyInstallSettings'] = 'Provjerite zadane postavke. Moďż˝ete ih pronaci u %s.';
        $strings['DatabaseName'] = 'Ime databaze';
        $strings['DatabaseUser'] = 'Korisnik databaze';
        $strings['DatabaseHost'] = 'Host databaze';
        $strings['DatabaseCredentials'] = 'Morate unijeti podatke MySQL korisnika koji ima pravo kreirati databazu. Ukoliko ne znate, kontaktirajte administratora.';
        $strings['MySQLUser'] = 'MySQL korisnik';
        $strings['InstallOptionsWarning'] = 'The following options will probably not work in a hosted environment. If you are installing in a hosted environment, use the MySQL wizard tools to complete these steps.';
        $strings['CreateDatabase'] = 'Kreiraj databazu';
        $strings['CreateDatabaseUser'] = 'Kreiraj korisnika databaze';
        $strings['PopulateExampleData'] = 'Uvozi test podatke. Kreira administratorski racun admin/password i korisnicki user/password';
        $strings['DataWipeWarning'] = 'Paďż˝nja: Ovo ce obrisati sve postojece podatke!';
        $strings['RunInstallation'] = 'Pokreni instalaciju';
        $strings['UpgradeNotice'] = 'Aďż˝urirate verziju <b>%s</b> na verziju <b>%s</b>';
        $strings['RunUpgrade'] = 'Pokreni nadogradnju';
        $strings['Executing'] = 'Izvrďż˝avam';
        $strings['StatementFailed'] = 'Greďż˝ka! Detalji:';
        $strings['SQLStatement'] = 'SQL Statement:';
        $strings['ErrorCode'] = 'Error Code:';
        $strings['ErrorText'] = 'Error Text:';
        $strings['InstallationSuccess'] = 'Instalacija uspjeďż˝no zavrďż˝ena!';
        $strings['RegisterAdminUser'] = 'Register your admin user. This is required if you did not import the sample data. Ensure that $conf[\'settings\'][\'allow.self.registration\'] = \'true\' in your %s file.';
        $strings['LoginWithSampleAccounts'] = 'If you imported the sample data, you can log in with admin/password for admin user or user/password for basic user.';
        $strings['InstalledVersion'] = 'Radite u %s verziji LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Preporucujemo da nadogradite vaďż˝u konfiguracijsku datoteku.';
        $strings['InstallationFailure'] = 'There were problems with the installation.  Please correct them and retry the installation.';
        $strings['ConfigureApplication'] = 'Konfiguriraj LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Vaďż˝a konfiguracijska datoteka je aďż˝urna!';
        $strings['ConfigUpdateFailure'] = 'We could not automatically update your config file. Please overwrite the contents of config.php with the following:';
        $strings['SelectUser'] = 'Odaberi korisnika';
        // End Install

        // Errors
        $strings['LoginError'] = 'Ime i lozinka ne odgovaraju';
        $strings['ReservationFailed'] = 'Vasa rezervacija ne moze se napraviti';
        $strings['MinNoticeError'] = 'Ova rezervacija zahtijeva prethodnu obavijest.Najraniji datum i vrijeme koje moďż˝e biti rezervirano je %s.';
        $strings['MaxNoticeError'] = 'Rezervacija se ne moze napraviti tako daleko.  Krajnji datum je %s.';
        $strings['MinDurationError'] = 'Rezervacija mora trajati najmanje %s.';
        $strings['MaxDurationError'] = 'Rezervacija ne moze trajati duze od %s.';
        $strings['ConflictingAccessoryDates'] = 'Nema dovoljno dodatne opreme:';
        $strings['NoResourcePermission'] = 'Nemate prava za pristup resursima.';
        $strings['ConflictingReservationDates'] = 'Postoje rezervacije u konfliktu za slijedeci datum:';
        $strings['StartDateBeforeEndDateRule'] = 'Datum i vrijeme pocetka mora biti prije datuma i vremena kraja.';
        $strings['StartIsInPast'] = 'Datum i vrijeme pocetka ne moďż˝e biti u proďż˝losti.';
        $strings['EmailDisabled'] = 'Administrator je iskljucio email obavijesti.';
        $strings['ValidLayoutRequired'] = 'Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'There are problems with the additional attributes you provided:';
        $strings['CustomAttributeRequired'] = '%s je obavezno polje.';
        $strings['CustomAttributeInvalid'] = 'Podaci uneseni za %s su neispravni.';
        $strings['AttachmentLoadingError'] = 'Sorry, there was a problem loading the requested file.';
        $strings['InvalidAttachmentExtension'] = 'You can only upload files of type: %s';
        $strings['InvalidStartSlot'] = 'Pocetno vrijeme i datum su neispravni';
        $strings['InvalidEndSlot'] = 'Zavrsno vrijeme i datum su neispravni.';
        $strings['MaxParticipantsError'] = '%s moze primiti samo %s ucesnika.';
        $strings['ReservationCriticalError'] = 'Greska pri snimanju rezervacije, ukoliko se nastavi kontaktirajte administratora.';
        $strings['InvalidStartReminderTime'] = 'Pocetno vrijeme podsjetnika je neispravno.';
        $strings['InvalidEndReminderTime'] = 'Krajnje vrijeme podsjetnika je neispravno';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s ne dopusta rezervacije preko dana';
        $strings['InvalidReservationData'] = 'Problem sa zahtjevom za rezervaciju.';
        $strings['PasswordError'] = 'Lozinka mora sadrzavati najmanje %s slova i najmanje %s brojeva.';
        $strings['PasswordErrorRequirements'] = 'Lozinka mora sadrzavati kombinaciju najmanje %s velikih i malih slova i %s brojeva.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Kreiraj rezervaciju';
        $strings['EditReservation'] = 'Uredi rezervaciju';
        $strings['LogIn'] = 'Logiraj se';
        $strings['ManageReservations'] = 'Rezervacije';
        $strings['AwaitingActivation'] = 'Cekanje na aktivaciju';
        $strings['PendingApproval'] = 'Cekanje na odobrenje';
        $strings['ManageSchedules'] = 'Rasporedi';
        $strings['ManageResources'] = 'Resursi';
        $strings['ManageAccessories'] = 'Oprema';
        $strings['ManageUsers'] = 'Korisnici';
        $strings['ManageGroups'] = 'Grupe';
        $strings['ManageQuotas'] = 'Kvote';
        $strings['ManageBlackouts'] = 'Blackout vrijeme';
        $strings['MyDashboard'] = 'Moja kontrolna ploca';
        $strings['ServerSettings'] = 'Postavke servera';
        $strings['Dashboard'] = 'Kontrolna ploca';
        $strings['Help'] = 'Pomoc';
        $strings['Administration'] = 'Administracija';
        $strings['About'] = 'O nama';
        $strings['Bookings'] = 'Booking';
        $strings['Schedule'] = 'Raspored';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Account'] = 'Racun';
        $strings['EditProfile'] = 'uredi moj profil';
        $strings['FindAnOpening'] = 'Pronadji otvaranje';
        $strings['OpenInvitations'] = 'Otvorene pozivnice';
        $strings['MyCalendar'] = 'Moj kalendar';
        $strings['ResourceCalendar'] = 'Kalendar resursa';
        $strings['Reservation'] = 'Nova rezervacija';
        $strings['Install'] = 'Instalacija';
        $strings['ChangePassword'] = 'Promjeni lozinku';
        $strings['MyAccount'] = 'Moj racun';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Upravljanje aplikacijama';
        $strings['ForgotPassword'] = 'Zaboravljena lozinka';
        $strings['NotificationPreferences'] = 'Postavke obavijesti';
        $strings['ManageAnnouncements'] = 'Obavijesti';
        $strings['Responsibilities'] = 'Odgovornosti';
        $strings['GroupReservations'] = 'Grupne rezervacije';
        $strings['ResourceReservations'] = 'Rezervacije resursa';
        $strings['Customization'] = 'Podesavanja';
        $strings['Attributes'] = 'Znacajke';
        $strings['AccountActivation'] = 'Aktivacija racuna';
        $strings['ScheduleReservations'] = 'Rezervacije rasporeda';
        $strings['Reports'] = 'Izvjestaji';
        $strings['GenerateReport'] = 'Kreiraj novi izvjestaj';
        $strings['MySavedReports'] = 'Spremljeni izvjestaji';
        $strings['CommonReports'] = 'Zajednicki izvjestaji';
        $strings['ViewDay'] = 'Vidi dan';
        $strings['Group'] = 'Grupa';
        $strings['ManageConfiguration'] = 'Konfiguracija aplikacija';
        $strings['LookAndFeel'] = 'Pogledaj i osjeti';
        $strings['ManageResourceGroups'] = 'Grupe resursa';
        $strings['ManageResourceTypes'] = 'Tipovi reursa';
        $strings['ManageResourceStatus'] = 'Statusi resursa';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'N';
        $strings['DayMondaySingle'] = 'P';
        $strings['DayTuesdaySingle'] = 'U';
        $strings['DayWednesdaySingle'] = 'S';
        $strings['DayThursdaySingle'] = 'C';
        $strings['DayFridaySingle'] = 'P';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Ned';
        $strings['DayMondayAbbr'] = 'Pon';
        $strings['DayTuesdayAbbr'] = 'Uto';
        $strings['DayWednesdayAbbr'] = 'Sri';
        $strings['DayThursdayAbbr'] = 'Cet';
        $strings['DayFridayAbbr'] = 'Pet';
        $strings['DaySaturdayAbbr'] = 'Sub';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Rezervacija je odobrena';
        $strings['ReservationCreatedSubject'] = 'Rezervacija je kreirana';
        $strings['ReservationUpdatedSubject'] = 'Rezervacija je azurirana';
        $strings['ReservationDeletedSubject'] = 'Rezervacija je uklonjena';
        $strings['ReservationCreatedAdminSubject'] = 'Obavijest:  Rezervacija je kreirana';
        $strings['ReservationUpdatedAdminSubject'] = 'Obavijest:  Rezervacija je azurirana';
        $strings['ReservationDeleteAdminSubject'] = 'Obavijest:  Rezervacija je uklonjena';
        $strings['ParticipantAddedSubject'] = 'Obavijest ucesniku o rezervaciji';
        $strings['ParticipantDeletedSubject'] = 'Rezervacija uklonjena';
        $strings['InviteeAddedSubject'] = 'Rezervacijska pozivnica';
        $strings['ResetPassword'] = 'Zahtjev za izmjenu lozinke';
        $strings['ActivateYourAccount'] = 'Molimo aktivirajte svoj racun';
        $strings['ReportSubject'] = 'Vas izvjestaj (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Rezervacija za %s uskoro pocinje';
        $strings['ReservationEndingSoonSubject'] = 'Rezervacija za %s uskoro istice';
        $strings['UserAdded'] = 'Dodan je novi korisnik';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nemate prethodne rezervacije';
        $strings['PastReservations'] = 'Prethodne rezervacije';
        $strings['AllNoPastReservations'] = 'Nema prethodnih rezervacija u posljednjih %s dana';
        $strings['AllPastReservations'] = 'Sve prethodne rezervacije';
        $strings['Yesterday'] = 'Jučer';
        $strings['EarlierThisWeek'] = 'Ranije ove sedmice';
        $strings['PreviousWeek'] = 'Prethodni tjedan';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Vaša grupa nema nadolazećih rezervacija';
        $strings['GroupUpcomingReservations'] = 'Nadolazeće rezervacije moje grupe/ova';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Došlo je do pogreške prilikom prijave putem Facebooka. Molimo pokušajte ponovno.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nemate rezervacija koje čekaju odobrenje';
        $strings['PendingApprovalReservations'] = 'Rezervacije na odobrenje';
        $strings['LaterThisMonth'] = 'Kasnije ovog mjeseca';
        $strings['LaterThisYear'] = 'Kasnije ove godine';
        $strings['Remaining'] = 'Preostalo';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Nema rezervacija za odjavu';
        $strings['MissingCheckOutReservations'] = 'Nedostajuće rezervacije za odjavu';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Ne možete vidjeti pojedinosti o rezervaciji jer nemate dozvole za nijedan od resursa u ovoj rezervaciji';
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
        $days['full'] = ['Nedjelja', 'Ponedjeljak', 'Utorak', 'Srijeda', 'Cetvrtak', 'Petak', 'Subota'];
        // The three letter abbreviation
        $days['abbr'] = ['ned', 'Pon', 'Uto', 'Sri', 'Cet', 'Pet', 'Sub'];
        // The two letter abbreviation
        $days['two'] = ['Ne', 'Po', 'Ut', 'Sr', 'Ce', 'Pe', 'Su'];
        // The one letter abbreviation
        $days['letter'] = ['N', 'P', 'U', 'S', 'C', 'P', 'S'];

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
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Sijecanj', 'Veljaca', 'Ozujak', 'Travanj', 'Svibanj', 'Lipanj', 'Srpanj', 'Kolovoz', 'Rujan', 'Listopad', 'Studeni', 'Prosinac'];
        // The three letter month name
        $months['abbr'] = ['Sij', 'Velj', 'Ozu', 'Tra', 'Svi', 'Lip', 'Srp', 'Kol', 'Ruj', 'Lis', 'Stu', 'Pro'];

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
        return 'hr';
    }
}
