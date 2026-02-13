<?php

/**
Serbian translation by velisa@velisa.net
 */

require_once('Language.php');
require_once('en_gb.php');

class sr_sr extends en_gb
{
    public function __construct()
    {
        parent::__construct();
        $this->Charset = 'UTF-8';
    }

    /**
     * @return array
     */
    protected function _LoadDates()
    {
        $dates = parent::_LoadDates();

        $dates['general_date'] = 'm/d/G';
        $dates['general_datetime'] = 'm/d/G H:i:s';
        $dates['schedule_daily'] = 'l, m/d/G';
        $dates['reservation_email'] = 'm/d/G @ g:i A (e)';
        $dates['res_popup'] = 'm/d/G g:i A';
        $dates['dashboard'] = 'l, m/d/G g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['general_date_js'] = 'mm/dd/gg';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'M/d';

        $this->Dates = $dates;

        return $this->Dates;
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
        $strings['Change'] = 'Promeni';
        $strings['Rename'] = 'Preimenuj';
        $strings['Remove'] = 'Ukloni';
        $strings['Delete'] = 'Obriši';
        $strings['Update'] = 'Ażuriraj';
        $strings['Cancel'] = 'Odustani';
        $strings['Add'] = 'Dodaj';
        $strings['Name'] = 'Naziv';
        $strings['Yes'] = 'Da';
        $strings['No'] = 'Ne';
        $strings['FirstNameRequired'] = 'Obavezno.';
        $strings['LastNameRequired'] = 'Obavezno.';
        $strings['PwMustMatch'] = 'Potvrda lozinke mora odgovarati lozinki.';
        $strings['ValidEmailRequired'] = 'Vażeća e-mail adresa je obavezna.';
        $strings['UniqueEmailRequired'] = 'E-mail adresa se već koristi';
        $strings['UniqueUsernameRequired'] = 'Korisnik je vec registrovan.';
        $strings['UserNameRequired'] = 'Korisničko ime je obavezno.';
        $strings['CaptchaMustMatch'] = 'Unesite karaktere sa slike.';
        $strings['Today'] = 'Danas';
        $strings['Week'] = 'Sedmica';
        $strings['Month'] = 'Mesec';
        $strings['BackToCalendar'] = 'Povratak na kalendar';
        $strings['BeginDate'] = 'Početak';
        $strings['EndDate'] = 'Kraj';
        $strings['Username'] = 'Korisnik';
        $strings['Password'] = 'Lozinka';
        $strings['PasswordConfirmation'] = 'Potvrdi lozinku';
        $strings['DefaultPage'] = 'Početna stranica';
        $strings['MyCalendar'] = 'Moj kalendar';
        $strings['ScheduleCalendar'] = 'Kalendar rasporeda';
        $strings['Registration'] = 'Registracija';
        $strings['NoAnnouncements'] = 'Nema obaveštenja';
        $strings['Announcements'] = 'Obaveštenja';
        $strings['NoUpcomingReservations'] = 'Nema novih rezervacija';
        $strings['UpcomingReservations'] = 'Nove rezervacije';
        $strings['ShowHide'] = 'Prikazati/Sakriti';
        $strings['Error'] = 'Greška';
        $strings['ReturnToPreviousPage'] = 'Povratak na prethodnu stranicu';
        $strings['UnknownError'] = 'Đe ba zapelo? Bem li ga!';
        $strings['InsufficientPermissionsError'] = 'Nemate dozvolu za pristup';
        $strings['MissingReservationResourceError'] = 'Nije izabran resurs';
        $strings['MissingReservationScheduleError'] = 'Nije izabran resurs';
        $strings['DoesNotRepeat'] = 'Ne ponavlja se';
        $strings['Daily'] = 'Dnevno';
        $strings['Weekly'] = 'Nedeljno';
        $strings['Monthly'] = 'Mesečno';
        $strings['Yearly'] = 'Godišnje';
        $strings['RepeatPrompt'] = 'Ponovi';
        $strings['hours'] = 'sati';
        $strings['days'] = 'dana';
        $strings['weeks'] = 'sedmica';
        $strings['months'] = 'meseci';
        $strings['years'] = 'godina';
        $strings['day'] = 'dan';
        $strings['week'] = 'sedmica';
        $strings['month'] = 'mesec';
        $strings['year'] = 'godina';
        $strings['repeatDayOfMonth'] = 'dan u mesecu';
        $strings['repeatDayOfWeek'] = 'dan u sedmici';
        $strings['RepeatUntilPrompt'] = 'Do';
        $strings['RepeatEveryPrompt'] = 'Svaki';
        $strings['RepeatDaysPrompt'] = 'Na';
        $strings['CreateReservationHeading'] = 'Kreiraj novu rezervaciju';
        $strings['EditReservationHeading'] = 'Uređivanje rezervacije %s';
        $strings['ViewReservationHeading'] = 'Pregled rezervacije %s';
        $strings['ReservationErrors'] = 'Izmena rezervacije';
        $strings['Create'] = 'Kreiraj';
        $strings['ThisInstance'] = 'Samo ovaj put';
        $strings['AllInstances'] = 'Svaki put';
        $strings['FutureInstances'] = 'Ubuduće';
        $strings['Print'] = 'Štampa';
        $strings['ShowHideNavigation'] = 'Pokazati/Sakriti navigaciju';
        $strings['ReferenceNumber'] = 'Referentni broj';
        $strings['Tomorrow'] = 'Sutra';
        $strings['LaterThisWeek'] = 'Kasnije ove sedmice';
        $strings['NextWeek'] = 'Sledeće sedmice';
        $strings['SignOut'] = 'Odjavi se';
        $strings['LayoutDescription'] = 'Počni na %s, pokaži %s dana odjednom';
        $strings['AllResources'] = 'Svi resursi';
        $strings['TakeOffline'] = 'Offline';
        $strings['BringOnline'] = 'Online';
        $strings['AddImage'] = 'Dodaj pičkr';
        $strings['NoImage'] = 'Nema gu pičkr';
        $strings['Move'] = 'Ukloni';
        $strings['AppearsOn'] = 'Pojavljuje se na %s';
        $strings['Location'] = 'Lokacija';
        $strings['NoLocationLabel'] = '(nije podržena lokacija)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(nema informacija o kontaktu)';
        $strings['Description'] = 'Opis';
        $strings['NoDescriptionLabel'] = '(nema opisa)';
        $strings['Notes'] = 'Beleške';
        $strings['NoNotesLabel'] = '(nema beleški)';
        $strings['NoTitleLabel'] = '(nema naziva)';
        $strings['UsageConfiguration'] = 'Konfiguracija korišćenja';
        $strings['ChangeConfiguration'] = 'Izmena konfiguracije';
        $strings['ResourceMinLength'] = 'Rezervacija mora trajati najmanje %s';
        $strings['ResourceMinLengthNone'] = 'Nema minimalnog trajanja rezervacije';
        $strings['ResourceMaxLength'] = 'Rezervacija ne može trajati manje od %s';
        $strings['ResourceMaxLengthNone'] = 'Nema maksimalnog trajanja rezervacije';
        $strings['ResourceRequiresApproval'] = 'Rezervacija mora biti odobrena';
        $strings['ResourceRequiresApprovalNone'] = 'Rezervacija ne zahteva odobrenje';
        $strings['ResourcePermissionAutoGranted'] = 'Dozvola je automatski odobrena';
        $strings['ResourcePermissionNotAutoGranted'] = 'Dozvola nije automatski odobrena';
        $strings['ResourceMinNotice'] = 'Rezervacija mora biti napravljena najmanje %s pre početka';
        $strings['ResourceMinNoticeNone'] = 'Rezervacija može biti napravljena do trenutnog vremena';
        $strings['ResourceMaxNotice'] = 'Rezervacija se ne mora završiti %s od trenutnog vremena';
        $strings['ResourceMaxNoticeNone'] = 'Rezervacija može završiti bilo kada';
        $strings['ResourceBufferTime'] = 'Mora biti %s izmedu rezervacija';
        $strings['ResourceBufferTimeNone'] = 'Nema razmaka između rezervacija';
        $strings['ResourceAllowMultiDay'] = 'Rezervacije mogu biti preko dana';
        $strings['ResourceNotAllowMultiDay'] = 'Rezervacije ne mogu biti preko dana';
        $strings['ResourceCapacity'] = 'Ovaj resurs ima kapacitet od %s osoba';
        $strings['ResourceCapacityNone'] = 'Ovaj resurs ima neograničen kapacitet';
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
        $strings['ResetPassword'] = 'Resetuj lozinku';
        $strings['LastLogin'] = 'Poslednja prijava';
        $strings['Search'] = 'Traži';
        $strings['ResourcePermissions'] = 'Dozvole resursa';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Groups'] = 'Grupe';
        $strings['ResetPassword'] = 'Resetuj lozinku';
        $strings['AllUsers'] = 'Svi korisnici';
        $strings['AllGroups'] = 'Sve grupe';
        $strings['AllSchedules'] = 'Svi rasporedi';
        $strings['UsernameOrEmail'] = 'Korisničko ime ili šifra';
        $strings['Members'] = 'Članovi';
        $strings['QuickSlotCreation'] = 'Kreiraj mesto svakih %s minuta izmedu %s i %s';
        $strings['ApplyUpdatesTo'] = 'Dodaj ažuriranja';
        $strings['CancelParticipation'] = 'Otkaži učestvovanje';
        $strings['Attending'] = 'Prisustvovanje';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['reservations'] = 'rezervacije';
        $strings['reservation'] = 'rezervacija';
        $strings['ChangeCalendar'] = 'Izmeni kalendar';
        $strings['AddQuota'] = 'Dodaj kvotu';
        $strings['FindUser'] = 'Traži korisnika';
        $strings['Created'] = 'Kreirano';
        $strings['LastModified'] = 'Zadnja izmena';
        $strings['GroupName'] = 'Ime grupe';
        $strings['GroupMembers'] = 'Članovi grupe';
        $strings['GroupRoles'] = 'Uloge grupe';
        $strings['GroupAdmin'] = 'Administrator grupe';
        $strings['Actions'] = 'Aktivnost';
        $strings['CurrentPassword'] = 'Trenutna lozinka';
        $strings['NewPassword'] = 'Nova lozinka';
        $strings['InvalidPassword'] = 'Trenutna lozinka je netačna';
        $strings['PasswordChangedSuccessfully'] = 'Vaša lozinka je promenjena';
        $strings['SignedInAs'] = 'Prijavljen kao';
        $strings['NotSignedIn'] = 'Niste prijavljeni';
        $strings['ReservationTitle'] = 'Naslov rezervacije';
        $strings['ReservationDescription'] = 'Opis rezervacije';
        $strings['ResourceList'] = 'Resursi za rezervaciju';
        $strings['Accessories'] = 'Dodaci';
        $strings['ParticipantList'] = 'Učestvuju';
        $strings['InvitationList'] = 'Pozvani';
        $strings['AccessoryName'] = 'Ime dodatka';
        $strings['QuantityAvailable'] = 'Dostupna količina';
        $strings['Resources'] = 'Resursi';
        $strings['Participants'] = 'Učesnici';
        $strings['User'] = 'Korisnik';
        $strings['Resource'] = 'Resurs';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Dozvoli';
        $strings['Page'] = 'Stranica';
        $strings['Rows'] = 'Red';
        $strings['Unlimited'] = 'Neograničeno';
        $strings['Email'] = 'E-mail';
        $strings['EmailAddress'] = 'E-mail Adresa';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organizacija';
        $strings['Position'] = 'Pozicija';
        $strings['Language'] = 'Jezik';
        $strings['Permissions'] = 'Dozvole';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Nadi grupu';
        $strings['Manage'] = 'Upravljanje';
        $strings['None'] = 'Ništa';
        $strings['AddToOutlook'] = 'Dodaj u kalendar';
        $strings['Done'] = 'Gotovo';
        $strings['RememberMe'] = 'Zapamti me';
        $strings['FirstTimeUser?'] = 'Prvi put korisnik?';
        $strings['CreateAnAccount'] = 'Kreiraj nalog';
        $strings['ViewSchedule'] = 'Vidi raspored';
        $strings['ForgotMyPassword'] = 'Zaboravio sam lozinku';
        $strings['YouWillBeEmailedANewPassword'] = 'Biće vam poslata nasumično generisana lozinka';
        $strings['Close'] = 'Zatvori';
        $strings['ExportToCSV'] = 'Izvezi u CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Šljakam...';
        $strings['Login'] = 'Prijava';
        $strings['AdditionalInformation'] = 'Dodatne informacije';
        $strings['AllFieldsAreRequired'] = 'Sva polja su obavezna';
        $strings['Optional'] = 'opcija';
        $strings['YourProfileWasUpdated'] = 'Vaš profil je ažuriran';
        $strings['YourSettingsWereUpdated'] = 'Postavke su ažurirane';
        $strings['Register'] = 'Registracija';
        $strings['SecurityCode'] = 'Sigurnosni kod';
        $strings['ReservationCreatedPreference'] = 'Kada kreiram rezervaciju ili je kreirana u moje ime';
        $strings['ReservationUpdatedPreference'] = 'Kada ažuriram rezervaciju ili je kreirana u moje ime';
        $strings['ReservationDeletedPreference'] = 'Kada obrišem rezervaciju ili je kreirana u moje ime';
        $strings['ReservationApprovalPreference'] = 'Kada je rezervacija na čekanju odobrena';
        $strings['PreferenceSendEmail'] = 'Pošalji mi email';
        $strings['PreferenceNoEmail'] = 'Ne obaveštavaj me';
        $strings['ReservationCreated'] = 'Vaša rezervacija je kreirana!';
        $strings['ReservationUpdated'] = 'Vaša rezervacija je ažurirana!';
        $strings['ReservationRemoved'] = 'Vaša rezervacija je obrisana';
        $strings['YourReferenceNumber'] = 'Vaš referentni broj je %s';
        $strings['UpdatingReservation'] = 'Ažuriraj rezervacije';
        $strings['ChangeUser'] = 'Promeni korisnika';
        $strings['MoreResources'] = 'Više resursa';
        $strings['ReservationLength'] = 'Trajanje rezervacije';
        $strings['ParticipantList'] = 'Lista učesnika';
        $strings['AddParticipants'] = 'Dodaj učesnika';
        $strings['InviteOthers'] = 'Pozovi ostale';
        $strings['AddResources'] = 'Dodaj resurs';
        $strings['AddAccessories'] = 'Dodaj opremu';
        $strings['Accessory'] = 'Oprema';
        $strings['QuantityRequested'] = 'Zahtevana količina';
        $strings['CreatingReservation'] = 'Kreiram rezervaciju';
        $strings['UpdatingReservation'] = 'Ažuriram rezervaciju';
        $strings['DeleteWarning'] = 'Ova akcija je nepovratna!';
        $strings['DeleteAccessoryWarning'] = 'Brisanje opreme će je ukloniti iz svih rezervacija.';
        $strings['AddAccessory'] = 'Dodaj opremu';
        $strings['AddBlackout'] = 'Dodaj Blackout';
        $strings['AllResourcesOn'] = 'Svi resursi ';
        $strings['Reason'] = 'Razlog';
        $strings['BlackoutShowMe'] = 'Prikaži rezervacije u konfliktu';
        $strings['BlackoutDeleteConflicts'] = 'Obriši rezervacije u konfliktu';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Između';
        $strings['CreatedBy'] = 'Kreirano od';
        $strings['BlackoutCreated'] = 'Blackout Created';
        $strings['BlackoutNotCreated'] = 'Blackout could not be created';
        $strings['BlackoutUpdated'] = 'Blackout Updated';
        $strings['BlackoutNotUpdated'] = 'Blackout could not be created';
        $strings['BlackoutConflicts'] = 'There are conflicting blackout times';
        $strings['ReservationConflicts'] = 'Nema rezervacija u konfliktu';
        $strings['UsersInGroup'] = 'Korisnici u grupi';
        $strings['Browse'] = 'Pretraži';
        $strings['DeleteGroupWarning'] = 'Brisanje ove grupe ce obrisati sve pridružene dozvole resursa. Korisnici u grupi bi mogli izgubiti pristup resursima.';
        $strings['WhatRolesApplyToThisGroup'] = 'Koju ulogu dodajete ovoj grupi?';
        $strings['WhoCanManageThisGroup'] = 'Ko može da uređuje grupu?';
        $strings['WhoCanManageThisSchedule'] = 'Ko može da uređuje raspored?';
        $strings['AddGroup'] = 'Dodaj grupu';
        $strings['AllQuotas'] = 'Sve kvote';
        $strings['QuotaReminder'] = 'Zapamtite: kvote se sprovode na osnovu vremenske zone rasporeda.';
        $strings['AllReservations'] = 'Sve rezervacije';
        $strings['PendingReservations'] = 'Rezervacije na čekanju';
        $strings['Approving'] = 'Dozvoljavam';
        $strings['MoveToSchedule'] = 'Makni u raspored';
        $strings['DeleteResourceWarning'] = 'Brisanje resursa će obrisati sve pripadajuće podatke uključujući';
        $strings['DeleteResourceWarningReservations'] = 'sve prošle, trenutne i buduće rezervacije koje su mu dodate';
        $strings['DeleteResourceWarningPermissions'] = 'sve dodate dozvole';
        $strings['DeleteResourceWarningReassign'] = 'Molimo preraspodelite sve što ne želite da se briše pre nastavka';
        $strings['ScheduleLayout'] = 'Izgled (sva vremena %s)';
        $strings['ReservableTimeSlots'] = 'Minutaže rezervacije';
        $strings['BlockedTimeSlots'] = 'Blokiranje minutaže';
        $strings['ThisIsTheDefaultSchedule'] = 'Ovo je osnovni raspored';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Osnovni raspored ne može biti obrisan';
        $strings['MakeDefault'] = 'Postavi kao osnovni';
        $strings['BringDown'] = 'Spusti';
        $strings['ChangeLayout'] = 'Promeni izgled';
        $strings['AddSchedule'] = 'Dodaj raspored';
        $strings['StartsOn'] = 'Počinje u';
        $strings['NumberOfDaysVisible'] = 'Broj vidljivih dana';
        $strings['UseSameLayoutAs'] = 'Koristi isti izgled kao';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Opcioni naziv';
        $strings['LayoutInstructions'] = 'Unesite jedan slot po liniji. Slotovi moraju biti uneti za svih 24 sata sa početkom i krajem u 12:00 AM.';
        $strings['AddUser'] = 'Dodaj korisnika';
        $strings['UserPermissionInfo'] = 'Stvarni pristupi resursima mogu biti različiti, u zavisnosti od Korisnika, dozvola grupe i postavke dozvola';
        $strings['DeleteUserWarning'] = 'Brisanje ovog korisnika će ukloniti sve njihove sadašnje, buduće, i prošle rezervacije.';
        $strings['AddAnnouncement'] = 'Dodaj obaveštenje';
        $strings['Announcement'] = 'Obaveštenje';
        $strings['Priority'] = 'Prioritet';
        $strings['Reservable'] = 'Dostupno';
        $strings['Unreservable'] = 'Nedostupno';
        $strings['Reserved'] = 'Rezervisano';
        $strings['MyReservation'] = 'Moje rezervacije';
        $strings['Pending'] = 'Na čekanju';
        $strings['Past'] = 'Prošlo';
        $strings['Restricted'] = 'Zabranjeno';
        $strings['ViewAll'] = 'Vidi sve';
        $strings['MoveResourcesAndReservations'] = 'ukloni resurse i rezervacije u';
        $strings['TurnOffSubscription'] = 'Iskljuci upis u kalendar';
        $strings['TurnOnSubscription'] = 'Dozvoli upis u kalendar';
        $strings['SubscribeToCalendar'] = 'Upiši se u ovaj kalendar';
        $strings['SubscriptionsAreDisabled'] = 'Administrator je isključio upise u kalendar';
        $strings['NoResourceAdministratorLabel'] = '(nema administratora resursa)';
        $strings['WhoCanManageThisResource'] = 'Ko može uređivati ovaj resurs?';
        $strings['ResourceAdministrator'] = 'Administrator resursa';
        $strings['Private'] = 'Privatno';
        $strings['Accept'] = 'Prihvatiti';
        $strings['Decline'] = 'Odbiti';
        $strings['ShowFullWeek'] = 'Pokazi celu sedmicu';
        $strings['CustomAttributes'] = 'Prilagodjene osobine';
        $strings['AddAttribute'] = 'Dodaj osobinu';
        $strings['EditAttribute'] = 'Ažuriraj osobine';
        $strings['DisplayLabel'] = 'Prikaži naziv';
        $strings['Type'] = 'Tip';
        $strings['Required'] = 'Obavezno';
        $strings['ValidationExpression'] = 'Provera ispravnosti';
        $strings['PossibleValues'] = 'Moguće vrednosti';
        $strings['SingleLineTextbox'] = 'Jednolinijski Textbox';
        $strings['MultiLineTextbox'] = 'Višelinijski Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Lista za izbor';
        $strings['CommaSeparated'] = 'odvojeno zapetom';
        $strings['Category'] = 'Kategorija';
        $strings['CategoryReservation'] = 'Rezervacija';
        $strings['CategoryGroup'] = 'Grupa';
        $strings['SortOrder'] = 'Sortiraj';
        $strings['Title'] = 'Naslov';
        $strings['AdditionalAttributes'] = 'Dodatne osobine';
        $strings['True'] = 'Da';
        $strings['False'] = 'Ne';
        $strings['ForgotPasswordEmailSent'] = 'E-mail je poslat na adresu s uputstvima za resetovanje lozinke';
        $strings['ActivationEmailSent'] = 'Uskoro ćete primiti aktivacioni e-mail.';
        $strings['AccountActivationError'] = 'Žao nam je, ne mozemo aktivirati vaš nalog';
        $strings['Attachments'] = 'Prilozi';
        $strings['AttachFile'] = 'Priloži datoteku';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Nema administratora rasporeda';
        $strings['ScheduleAdministrator'] = 'Admnistrator rasporeda';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Količina rasporeda';
        $strings['AllAccessories'] = 'Sva oprema';
        $strings['GetReport'] = 'Izveštaj';
        $strings['NoResultsFound'] = 'Nema rezultata';
        $strings['SaveThisReport'] = 'Snimi izveštaj';
        $strings['ReportSaved'] = 'Izvestaj snimljen!';
        $strings['EmailReport'] = 'Posalji izveštaj e-mailom';
        $strings['ReportSent'] = 'Izveštaj je poslat!';
        $strings['RunReport'] = 'Pokreni izveštaj';
        $strings['NoSavedReports'] = 'Nemate sačuvanih izveštaja.';
        $strings['CurrentWeek'] = 'Tekuća sedmica';
        $strings['CurrentMonth'] = 'Tekući mesec';
        $strings['AllTime'] = 'Sve vreme';
        $strings['FilterBy'] = 'Filtiraj po';
        $strings['Select'] = 'Odaberi';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Ukupno vreme';
        $strings['Count'] = 'Brojač';
        $strings['Usage'] = 'Upotreba';
        $strings['AggregateBy'] = 'Grupisati po';
        $strings['Range'] = 'Range';
        $strings['Choose'] = 'Odaberi';
        $strings['All'] = 'Sve';
        $strings['ViewAsChart'] = 'Vidi kao nacrt';
        $strings['ReservedResources'] = 'Rezervisani resursi';
        $strings['ReservedAccessories'] = 'Rezervisana oprema';
        $strings['ResourceUsageTimeBooked'] = 'Upotreba resursa - vreme bukiranja';
        $strings['ResourceUsageReservationCount'] = 'Upotreba resursa - broj rezervacije';
        $strings['Top20UsersTimeBooked'] = 'Top 20 korisnika - po vremenu';
        $strings['Top20UsersReservationCount'] = 'Top 20 korisnika - po broju rezervacija';
        $strings['ConfigurationUpdated'] = 'Konfiguraciona datoteka je ažurirana';
        $strings['ConfigurationUiNotEnabled'] = 'This page cannot be accessed because $conf[\'settings\'][\'pages\'][\'enable.configuration\'] is set to false or missing.';
        $strings['ConfigurationFileNotWritable'] = 'The config file is not writable. Please check the permissions of this file and try again.';
        $strings['ConfigurationUpdateHelp'] = 'Refer to the Configuration section of the <a target=_blank href=%s>Help File</a> for documentation on these settings.';
        $strings['GeneralConfigSettings'] = 'postavke';
        $strings['UseSameLayoutForAllDays'] = 'Koristi isti izgled za sve dane';
        $strings['LayoutVariesByDay'] = 'Izgled varira po danima';
        $strings['ManageReminders'] = 'Podsetnik';
        $strings['ReminderUser'] = 'ID korisnika';
        $strings['ReminderMessage'] = 'Poruka';
        $strings['ReminderAddress'] = 'Adrese';
        $strings['ReminderSendtime'] = 'Vreme slanja';
        $strings['ReminderRefNumber'] = 'Referentni broj rezervacije';
        $strings['ReminderSendtimeDate'] = 'Datum podsetnika';
        $strings['ReminderSendtimeTime'] = 'Vreme podsetnika (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Dodaj podsetnik';
        $strings['DeleteReminderWarning'] = 'Da li ste sigurni?';
        $strings['NoReminders'] = 'Nemate podsetnik.';
        $strings['Reminders'] = 'Podsetnici';
        $strings['SendReminder'] = 'Pošalji podsetnik';
        $strings['minutes'] = 'minuta';
        $strings['hours'] = 'sati';
        $strings['days'] = 'dana';
        $strings['ReminderBeforeStart'] = 'pre početka';
        $strings['ReminderBeforeEnd'] = 'pre kraja';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS datoteka';
        $strings['ThemeUploadSuccess'] = 'Promene su snimljene. Osvežite stranicu (F5)';
        $strings['MakeDefaultSchedule'] = 'Postavi kao osnovni raspored';
        $strings['DefaultScheduleSet'] = 'Ovo je sada osnovni raspored';
        $strings['FlipSchedule'] = 'Okrenite izgled rasporeda';
        $strings['Next'] = 'Sledeći';
        $strings['Success'] = 'Uspešno';
        $strings['Participant'] = 'Učesnik';
        $strings['ResourceFilter'] = 'Filter resursa';
        $strings['ResourceGroups'] = 'Grupa resursa';
        $strings['AddNewGroup'] = 'Dodaj novu grupu';
        $strings['Quit'] = 'Izlaz';
        $strings['AddGroup'] = 'Dodaj grupu';
        $strings['StandardScheduleDisplay'] = 'Koristi standardni prikaz rasporeda';
        $strings['TallScheduleDisplay'] = 'Koristi POVIŠENI prikaz rasporeda';
        $strings['WideScheduleDisplay'] = 'Koristi PROŠIRENI prikaz rasporeda';
        $strings['CondensedWeekScheduleDisplay'] = 'Koristi SUŽENI prikaz rasporeda';
        $strings['ResourceGroupHelp1'] = 'Povuci i pusti resurse za reorganizaciju.';
        $strings['ResourceGroupHelp2'] = 'Desni klik na ime grupe resursa za dodatne opcije.';
        $strings['ResourceGroupHelp3'] = 'Povuci i pusti resurse za dodavanje u grupu.';
        $strings['ResourceGroupWarning'] = 'Ako koristite grupe resursa, svaki resurs mora biti dodat u najmanje jednu grupu. Nedodeljeni resursi ne mogu se rezervisati.';
        $strings['ResourceType'] = 'TIP resursa';
        $strings['AppliesTo'] = 'Odnosi se na';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Dodaj TIP resursa';
        $strings['NoResourceTypeLabel'] = '(nije postavljen tip resursa)';
        $strings['ClearFilter'] = 'Obriši filter';
        $strings['MinimumCapacity'] = 'minimalni kapacitet';
        $strings['Color'] = 'Boja';
        $strings['Available'] = 'Dostupan';
        $strings['Unavailable'] = 'Nedostupan';
        $strings['Hidden'] = 'Skriven';
        $strings['ResourceStatus'] = 'Status resursa';
        $strings['CurrentStatus'] = 'Trenutni status';
        $strings['AllReservationResources'] = 'Svi resursi za rezervacije';
        $strings['File'] = 'Datoteka';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Instaliraj INFOTOK Scheduler (MySQL samo)';
        $strings['IncorrectInstallPassword'] = 'Žao nam je, lozinka je pogrešna!.';
        $strings['SetInstallPassword'] = 'Morate postaviti lozinku pre instaliranja.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'Nije potrebno ažurirati. Proces instalacije izbrisaće postojeće podatke i instalirati novu verziju!';
        $strings['ProvideInstallPassword'] = 'Molimo unesite lozinku.';
        $strings['InstallPasswordLocation'] = 'Može biti nađeno u %s u %s.';
        $strings['VerifyInstallSettings'] = 'Proverite zadate postavke. Možete ih pronaći u %s.';
        $strings['DatabaseName'] = 'Ime baze';
        $strings['DatabaseUser'] = 'Korisnik baze';
        $strings['DatabaseHost'] = 'Host baze';
        $strings['DatabaseCredentials'] = 'Morate uneti podatke MySQL korisnika koji ima pravo da kreira bazu. Ukoliko ne znate, kontaktirajte administratora.';
        $strings['MySQLUser'] = 'MySQL korisnik';
        $strings['InstallOptionsWarning'] = 'The following options will probably not work in a hosted environment. If you are installing in a hosted environment, use the MySQL wizard tools to complete these steps.';
        $strings['CreateDatabase'] = 'Kreiraj bazu';
        $strings['CreateDatabaseUser'] = 'Kreiraj korisnika baze';
        $strings['PopulateExampleData'] = 'Uvozi test podatke. Kreira administratorski nalog admin/password i korisniČki user/password';
        $strings['DataWipeWarning'] = 'Pažnja: Ovo će obrisati sve postojeće podatke!';
        $strings['RunInstallation'] = 'Pokreni instalaciju';
        $strings['UpgradeNotice'] = 'Ažurirate verziju <b>%s</b> na verziju <b>%s</b>';
        $strings['RunUpgrade'] = 'Pokreni nadogradnju';
        $strings['Executing'] = 'Izvršavam';
        $strings['StatementFailed'] = 'Greška! Detalji:';
        $strings['SQLStatement'] = 'SQL Statement:';
        $strings['ErrorCode'] = 'Error Code:';
        $strings['ErrorText'] = 'Error Text:';
        $strings['InstallationSuccess'] = 'Instalacija uspešno završena!';
        $strings['RegisterAdminUser'] = 'Register your admin user. This is required if you did not import the sample data. Ensure that $conf[\'settings\'][\'allow.self.registration\'] = \'true\' in your %s file.';
        $strings['LoginWithSampleAccounts'] = 'If you imported the sample data, you can log in with admin/password for admin user or user/password for basic user.';
        $strings['InstalledVersion'] = 'Radite u %s verziji 1.0 INFOTOK Schedulera';
        $strings['InstallUpgradeConfig'] = 'Preporučujemo da nadogradite vašu konfiguracionu datoteku.';
        $strings['InstallationFailure'] = 'There were problems with the installation.  Please correct them and retry the installation.';
        $strings['ConfigureApplication'] = 'Konfiguriši INFOTOK Scheduler';
        $strings['ConfigUpdateSuccess'] = 'Vaša konfiguraciona datoteka je ažurna!';
        $strings['ConfigUpdateFailure'] = 'We could not automatically update your config file. Please overwrite the contents of config.php with the following:';
        $strings['SelectUser'] = 'Izaberi korisnika';
        // End Install

        // Errors
        $strings['LoginError'] = 'Ime i lozinka ne odgovaraju';
        $strings['ReservationFailed'] = 'Vaša rezervacija se ne može napraviti';
        $strings['MinNoticeError'] = 'Ova rezervacija zahteva prethodno obaveštavanje. Najraniji datum i vreme koje može biti rezervisano je %s.';
        $strings['MaxNoticeError'] = 'Rezervacija se ne može napraviti tako daleko.  Krajnji datum je %s.';
        $strings['MinDurationError'] = 'Rezervacija mora trajati najmanje %s.';
        $strings['MaxDurationError'] = 'Rezervacija ne može trajati duže od %s.';
        $strings['ConflictingAccessoryDates'] = 'Nema dovoljno dodatne opreme:';
        $strings['NoResourcePermission'] = 'Nemate prava za pristup resursima.';
        $strings['ConflictingReservationDates'] = 'Postoje rezervacije u konfliktu za sledeći datum:';
        $strings['StartDateBeforeEndDateRule'] = 'Datum i vreme početka mora biti pre datuma i vremena kraja.';
        $strings['StartIsInPast'] = 'Datum i vreme početka ne može biti u prošlosti.';
        $strings['EmailDisabled'] = 'Administrator je isključio e-mail obaveštenja.';
        $strings['ValidLayoutRequired'] = 'Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'There are problems with the additional attributes you provided:';
        $strings['CustomAttributeRequired'] = '%s je obavezno polje.';
        $strings['CustomAttributeInvalid'] = 'Podaci unešeni za %s su neispravni.';
        $strings['AttachmentLoadingError'] = 'Sorry, there was a problem loading the requested file.';
        $strings['InvalidAttachmentExtension'] = 'You can only upload files of type: %s';
        $strings['InvalidStartSlot'] = 'Početno vreme i datum su neispravni';
        $strings['InvalidEndSlot'] = 'Završno vreme i datum su neispravni.';
        $strings['MaxParticipantsError'] = '%s može primiti samo %s učesnika.';
        $strings['ReservationCriticalError'] = 'Greška pri snimanju rezervacije, ukoliko se nastavi kontaktirajte administratora.';
        $strings['InvalidStartReminderTime'] = 'Početno vreme podsetnika je neispravno.';
        $strings['InvalidEndReminderTime'] = 'Krajnje vreme podsetnika je neispravno';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s ne dopušta rezervacije preko dana';
        $strings['InvalidReservationData'] = 'Problem sa zahtevom za rezervaciju.';
        $strings['PasswordError'] = 'Lozinka mora sadržati najmanje %s slova i najmanje %s brojeva.';
        $strings['PasswordErrorRequirements'] = 'Lozinka mora sadržati kombinaciju najmanje %s velikih i malih slova i %s brojeva.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Kreiraj rezervaciju';
        $strings['EditReservation'] = 'Uredi rezervaciju';
        $strings['LogIn'] = 'Prijavi se';
        $strings['ManageReservations'] = 'Rezervacije';
        $strings['AwaitingActivation'] = 'Čekanje na aktivaciju';
        $strings['PendingApproval'] = 'Čekanje na odobrenje';
        $strings['ManageSchedules'] = 'Rasporedi';
        $strings['ManageResources'] = 'Resursi';
        $strings['ManageAccessories'] = 'Oprema';
        $strings['ManageUsers'] = 'Korisnici';
        $strings['ManageGroups'] = 'Grupe';
        $strings['ManageQuotas'] = 'Kvote';
        $strings['ManageBlackouts'] = 'Blackout vreme';
        $strings['MyDashboard'] = 'Kontrolna tabla';
        $strings['ServerSettings'] = 'Postavke servera';
        $strings['Dashboard'] = 'Kontrolna tabla';
        $strings['Help'] = 'Pomoć';
        $strings['Administration'] = 'Administracija';
        $strings['About'] = 'O nama';
        $strings['Bookings'] = 'Booking';
        $strings['Schedule'] = 'Raspored';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Account'] = 'Nalog';
        $strings['EditProfile'] = 'Uredi moj profil';
        $strings['FindAnOpening'] = 'Pronadji otvaranje';
        $strings['OpenInvitations'] = 'Otvorene pozivnice';
        $strings['MyCalendar'] = 'Moj kalendar';
        $strings['ResourceCalendar'] = 'Kalendar resursa';
        $strings['Reservation'] = 'Nova rezervacija';
        $strings['Install'] = 'Instalacija';
        $strings['ChangePassword'] = 'Promena lozinke';
        $strings['MyAccount'] = 'Moj nalog';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Upravljanje aplikacijama';
        $strings['ForgotPassword'] = 'Zaboravljena lozinka';
        $strings['NotificationPreferences'] = 'Postavke obaveštenja';
        $strings['ManageAnnouncements'] = 'Obaveštenja';
        $strings['Responsibilities'] = 'Odgovornosti';
        $strings['GroupReservations'] = 'Grupne rezervacije';
        $strings['ResourceReservations'] = 'Rezervacije resursa';
        $strings['Customization'] = 'PODEŠAVANJA';
        $strings['Attributes'] = 'Osobine';
        $strings['AccountActivation'] = 'Aktivacija računa';
        $strings['ScheduleReservations'] = 'Rezervacije rasporeda';
        $strings['Reports'] = 'Izveštaji';
        $strings['GenerateReport'] = 'Kreiraj novi izveštaj';
        $strings['MySavedReports'] = 'Sačuvani izveštaji';
        $strings['CommonReports'] = 'Zajednički izveštaji';
        $strings['ViewDay'] = 'Pregled dana';
        $strings['Group'] = 'Grupa';
        $strings['ManageConfiguration'] = 'Konfiguracija aplikacija';
        $strings['LookAndFeel'] = 'Izgled i utisak';
        $strings['ManageResourceGroups'] = 'Grupe resursa';
        $strings['ManageResourceTypes'] = 'Tipovi reursa';
        $strings['ManageResourceStatus'] = 'Statusi resursa';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'N';
        $strings['DayMondaySingle'] = 'P';
        $strings['DayTuesdaySingle'] = 'U';
        $strings['DayWednesdaySingle'] = 'S';
        $strings['DayThursdaySingle'] = 'Č';
        $strings['DayFridaySingle'] = 'P';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Ned';
        $strings['DayMondayAbbr'] = 'Pon';
        $strings['DayTuesdayAbbr'] = 'Uto';
        $strings['DayWednesdayAbbr'] = 'Sre';
        $strings['DayThursdayAbbr'] = 'Čet';
        $strings['DayFridayAbbr'] = 'Pet';
        $strings['DaySaturdayAbbr'] = 'Sub';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Rezervacija je odobrena';
        $strings['ReservationCreatedSubject'] = 'Rezervacija je kreirana';
        $strings['ReservationUpdatedSubject'] = 'Rezervacija je ažurirana';
        $strings['ReservationDeletedSubject'] = 'Rezervacija je uklonjena';
        $strings['ReservationCreatedAdminSubject'] = 'Obaveštenje:  Rezervacija je kreirana';
        $strings['ReservationUpdatedAdminSubject'] = 'Obaveštenje:  Rezervacija je azurirana';
        $strings['ReservationDeleteAdminSubject'] = 'Obaveštenje:  Rezervacija je uklonjena';
        $strings['ParticipantAddedSubject'] = 'Obaveštenje učesniku o rezervaciji';
        $strings['ParticipantDeletedSubject'] = 'Rezervacija uklonjena';
        $strings['InviteeAddedSubject'] = 'Rezervacijska pozivnica';
        $strings['ResetPassword'] = 'Zahtev za izmenu lozinke';
        $strings['ActivateYourAccount'] = 'Molimo aktivirajte svoj nalog';
        $strings['ReportSubject'] = 'Vas izvestaj (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Rezervacija za %s uskoro počinje';
        $strings['ReservationEndingSoonSubject'] = 'Rezervacija za %s uskoro ističe';
        $strings['UserAdded'] = 'Dodat je novi korisnik';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nemate prethodne rezervacije';
        $strings['PastReservations'] = 'Prethodne rezervacije';
        $strings['AllNoPastReservations'] = 'Nema prethodnih rezervacija u poslednjih %s dana';
        $strings['AllPastReservations'] = 'Sve prethodne rezervacije';
        $strings['Yesterday'] = 'Juče';
        $strings['EarlierThisWeek'] = 'Ranije ove nedelje';
        $strings['PreviousWeek'] = 'Prethodna nedelja';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Vaša grupa nema nadolazeće rezervacije';
        $strings['GroupUpcomingReservations'] = 'Nadolazeće rezervacije moje grupe(ova)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Došlo je do greške prilikom prijavljivanja putem Facebooka. Molimo pokušajte ponovo.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nemate rezervacija koje čekaju odobrenje';
        $strings['PendingApprovalReservations'] = 'Rezervacije koje čekaju odobrenje';
        $strings['LaterThisMonth'] = 'Kasnije ovog meseca';
        $strings['LaterThisYear'] = 'Kasnije ove godine';
        $strings['Remaining'] = 'Preostalo';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Nema propuštenih rezervacija za odjavu';
        $strings['MissingCheckOutReservations'] = 'Propuštene rezervacije za odjavu';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Ne možete videti detalje rezervacije jer nemate dozvole za nijedan od resursa u ovoj rezervaciji';
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
        $days['full'] = ['Nedelja', 'Ponedeljak', 'Utorak', 'Sreda', 'Četvrtak', 'Petak', 'Subota'];
        // The three letter abbreviation
        $days['abbr'] = ['Ned', 'Pon', 'Uto', 'Sre', 'Čet', 'Pet', 'Sub'];
        // The two letter abbreviation
        $days['two'] = ['Ne', 'Po', 'Ut', 'Sr', 'Če', 'Pe', 'Su'];
        // The one letter abbreviation
        $days['letter'] = ['N', 'P', 'U', 'S', 'Č', 'P', 'S'];

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
        $months['full'] = ['Januar', 'Februar', 'Mart', 'April', 'Maj', 'Jun', 'Jul', 'Avgust', 'Septembar', 'Oktobar', 'Novambar', 'Decembar'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Avg', 'Sep', 'Okt', 'Nov', 'Dec'];

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
        return 'sr';
    }
}
