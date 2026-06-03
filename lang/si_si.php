<?php

/**
Modified by Alenka Kavčič (alenka.kavcic@fri.uni-lj.si), UL FRI, July 2015
Translated and adapted for Slovenian language
*/

require_once('Language.php');
require_once('en_gb.php');

class si_si extends en_gb
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

        $strings['FirstName'] = 'Ime';
        $strings['LastName'] = 'Priimek';
        $strings['Timezone'] = 'Časovni pas';
        $strings['Edit'] = 'Uredi';
        $strings['Change'] = 'Spremeni';
        $strings['Rename'] = 'Preimenuj';
        $strings['Remove'] = 'Odstrani';
        $strings['Delete'] = 'Briši';
        $strings['Update'] = 'Posodobi';
        $strings['Cancel'] = 'Razveljavi';
        $strings['Add'] = 'Dodaj';
        $strings['Name'] = 'Ime';
        $strings['Yes'] = 'Da';
        $strings['No'] = 'Ne';
        $strings['FirstNameRequired'] = 'Ime je obvezen podatek.';
        $strings['LastNameRequired'] = 'Priimek je obvezen podatek.';
        $strings['PwMustMatch'] = 'Potrditev gesla se mora ujemati z vpisanim geslom.';
        $strings['PwComplexity'] = 'Geslo mora vsebovati najmanj šest znakov, ki so kombinacija črk, številk in drugih znakov.';
        $strings['ValidEmailRequired'] = 'E-poštni naslov je obvezen podatek.';
        $strings['UniqueEmailRequired'] = 'Ta e-poštni naslov je že registriran.';
        $strings['UniqueUsernameRequired'] = 'To uporabniško ime je že registrirano.';
        $strings['UserNameRequired'] = 'Uporabniško ime je obvezen podatek.';
        $strings['CaptchaMustMatch'] = 'Prosimo, vnesite črke z varnostne slike natanko tako, kot so prikazane.';
        $strings['Today'] = 'Danes';
        $strings['Week'] = 'Teden';
        $strings['Month'] = 'Mesec';
        $strings['BackToCalendar'] = 'Nazaj na koledar';
        $strings['BeginDate'] = 'Začetek';
        $strings['EndDate'] = 'Konec';
        $strings['Username'] = 'Uporabniško ime';
        $strings['Password'] = 'Geslo';
        $strings['PasswordConfirmation'] = 'Potrditev gesla';
        $strings['DefaultPage'] = 'Privzeta začetna stran';
        $strings['MyCalendar'] = 'Moj koledar';
        $strings['ScheduleCalendar'] = 'Koledar z urniki';
        $strings['Registration'] = 'Registracija';
        $strings['NoAnnouncements'] = 'Ni obvestil';
        $strings['Announcements'] = 'Obvestila';
        $strings['NoUpcomingReservations'] = 'Nimate prihodnjih rezervacij';
        $strings['UpcomingReservations'] = 'Prihodnje rezervacije';
        $strings['AllNoUpcomingReservations'] = 'Nimate prihodnjih rezervacij v naslednjih %s dneh';
        $strings['AllUpcomingReservations'] = 'Vse prihodnje rezervacije';
        $strings['ShowHide'] = 'Prikaži/Skrij';
        $strings['Error'] = 'Napaka';
        $strings['ReturnToPreviousPage'] = 'Nazaj na predhodno stran';
        $strings['UnknownError'] = 'Neznana napaka';
        $strings['InsufficientPermissionsError'] = 'Za dostop do tega vira nimate dovoljenja';
        $strings['MissingReservationResourceError'] = 'Vir ni bil izbran';
        $strings['MissingReservationScheduleError'] = 'Urnik ni bil izbran';
        $strings['DoesNotRepeat'] = 'Se ne ponavlja';
        $strings['Daily'] = 'Dnevno';
        $strings['Weekly'] = 'Tedensko';
        $strings['Monthly'] = 'Mesečno';
        $strings['Yearly'] = 'Letno';
        $strings['RepeatPrompt'] = 'Ponovi';
        $strings['hours'] = 'ure';
        $strings['days'] = 'dan';
        $strings['weeks'] = 'teden';
        $strings['months'] = 'mesec';
        $strings['years'] = 'leto';
        $strings['day'] = 'dan';
        $strings['week'] = 'teden';
        $strings['month'] = 'mesec';
        $strings['year'] = 'leto';
        $strings['repeatDayOfMonth'] = 'dan v mesecu';
        $strings['repeatDayOfWeek'] = 'dan v tednu';
        $strings['RepeatUntilPrompt'] = 'Do';
        $strings['RepeatEveryPrompt'] = 'Vsak';
        $strings['RepeatDaysPrompt'] = 'Na';
        $strings['CreateReservationHeading'] = 'Ustvari novo rezervacijo';
        $strings['EditReservationHeading'] = 'Urejanje rezervacije %s';
        $strings['ViewReservationHeading'] = 'Ogled rezervacije %s';
        $strings['ReservationErrors'] = 'Spremeni rezervacijo';
        $strings['Create'] = 'Ustvari';
        $strings['ThisInstance'] = 'Samo ta primerek';
        $strings['AllInstances'] = 'Vsi primerki';
        $strings['FutureInstances'] = 'Bodoči primerki';
        $strings['Print'] = 'Natisni';
        $strings['ShowHideNavigation'] = 'Prikaži/Skrij navigacijo';
        $strings['ReferenceNumber'] = 'Referenčna številka';
        $strings['Tomorrow'] = 'Jutri';
        $strings['LaterThisWeek'] = 'Kasneje ta teden';
        $strings['NextWeek'] = 'Naslednji teden';
        $strings['SignOut'] = 'Odjava';
        $strings['LayoutDescription'] = 'Začetek na %s, prikaz %s dni naenkrat';
        $strings['AllResources'] = 'Vsi viri';
        $strings['TakeOffline'] = 'Prenesi Offline';
        $strings['BringOnline'] = 'Prenesi Online';
        $strings['AddImage'] = 'Dodaj sliko';
        $strings['NoImage'] = 'Ni slike';
        $strings['Move'] = 'Premakni';
        $strings['AppearsOn'] = 'Se prikaže v %s';
        $strings['Location'] = 'Lokacija';
        $strings['NoLocationLabel'] = '(lokacija ni podana)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(kontaktni podatki niso podani)';
        $strings['Description'] = 'Opis';
        $strings['NoDescriptionLabel'] = '(ni opisa)';
        $strings['Notes'] = 'Zapiski';
        $strings['NoNotesLabel'] = '(ni zapiskov)';
        $strings['NoTitleLabel'] = '(ni naslova)';
        $strings['UsageConfiguration'] = 'Konfiguracija uporabe';
        $strings['ChangeConfiguration'] = 'Spremeni konfiguracijo';
        $strings['ResourceMinLength'] = 'Rezervacije morajo trajati najmanj %s';
        $strings['ResourceMinLengthNone'] = 'Ni omejitve najkrajšega trajanja rezervacije';
        $strings['ResourceMaxLength'] = 'Rezervacije ne morejo trajati več kot %s';
        $strings['ResourceMaxLengthNone'] = 'Ni omejitve najdaljšega trajanja rezervacije';
        $strings['ResourceRequiresApproval'] = 'Rezervacije morajo biti potrjene';
        $strings['ResourceRequiresApprovalNone'] = 'Rezervacije ne potrebujejo potrditve';
        $strings['ResourcePermissionAutoGranted'] = 'Dovoljenje je dodeljeno samodejno';
        $strings['ResourcePermissionNotAutoGranted'] = 'Dovoljenje ni dodeljeno samodejno';
        $strings['ResourceMinNotice'] = 'Rezervacije morajo biti narejene najmanj %s pred začetkom';
        $strings['ResourceMinNoticeNone'] = 'Rezervacije so lahko narejene do trenutnega časa';
        $strings['ResourceMaxNotice'] = 'Rezervacije se ne smejo zaključiti več kot %s od trenutnega časa';
        $strings['ResourceMaxNoticeNone'] = 'Rezervacije se lahko končajo kadarkoli v prihodnosti';
        $strings['ResourceBufferTime'] = 'Med rezervacijami mora biti najmanj %s';
        $strings['ResourceBufferTimeNone'] = 'Med rezervacijami ni potrebno pustiti prostora';
        $strings['ResourceAllowMultiDay'] = 'Rezervacije so lahko narejene preko več dni';
        $strings['ResourceNotAllowMultiDay'] = 'Rezervacije ne morejo biti narejene preko več dni';
        $strings['ResourceCapacity'] = 'Ta vir ima zmogljivost za %s oseb';
        $strings['ResourceCapacityNone'] = 'Ta vir ima neomejeno zmogljivost';
        $strings['AddNewResource'] = 'Dodaj nov vir';
        $strings['AddNewUser'] = 'Dodaj novega uporabnika';
        $strings['AddUser'] = 'Dodaj uporabnika';
        $strings['Schedule'] = 'Urnik';
        $strings['AddResource'] = 'Dodaj vir';
        $strings['Capacity'] = 'Zmogljivost';
        $strings['Access'] = 'Dostop';
        $strings['Duration'] = 'Trajanje';
        $strings['Active'] = 'Aktiven';
        $strings['Inactive'] = 'Neaktiven';
        $strings['ResetPassword'] = 'Ponastavi geslo';
        $strings['LastLogin'] = 'Zadnja prijava';
        $strings['Search'] = 'Išči';
        $strings['ResourcePermissions'] = 'Dovoljenja vira';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Groups'] = 'Skupine';
        $strings['Users'] = 'Uporabniki';
        $strings['ResetPassword'] = 'Ponastavi geslo';
        $strings['AllUsers'] = 'Vsi uporabniki';
        $strings['AllGroups'] = 'Vse skupine';
        $strings['AllSchedules'] = 'Vsi urniki';
        $strings['UsernameOrEmail'] = 'Uporabniško ime ali e-pošta';
        $strings['Members'] = 'Člani';
        $strings['QuickSlotCreation'] = 'Ustvari intervale vsakih %s minut med %s in %s';
        $strings['ApplyUpdatesTo'] = 'Uveljavi posodobitve za';
        $strings['CancelParticipation'] = 'Razveljavi sodelovanje';
        $strings['Attending'] = 'Prisoten';
        $strings['QuotaConfiguration'] = 'V %s za %s uporabnikov iz %s je omejeno na %s %s na %s';
        $strings['reservations'] = 'rezervacije';
        $strings['reservation'] = 'rezervacija';
        $strings['ChangeCalendar'] = 'Spremeni koledar';
        $strings['AddQuota'] = 'Dodaj kvoto';
        $strings['FindUser'] = 'Poišči uporabnika';
        $strings['Created'] = 'Ustvarjeno';
        $strings['LastModified'] = 'Zanja sprememba';
        $strings['GroupName'] = 'Ime skupine';
        $strings['GroupMembers'] = 'Člani skupine';
        $strings['GroupRoles'] = 'Vloge v skupini';
        $strings['GroupAdmin'] = 'Administrator skupine';
        $strings['Actions'] = 'Akcije';
        $strings['CurrentPassword'] = 'Trenutno geslo';
        $strings['NewPassword'] = 'Novo geslo';
        $strings['InvalidPassword'] = 'Trenutno geslo ni pravilno';
        $strings['PasswordChangedSuccessfully'] = 'Vaše geslo je bilo uspešno spremenjeno';
        $strings['SignedInAs'] = 'Prijavljeni kot';
        $strings['NotSignedIn'] = 'Niste prijavljeni';
        $strings['ReservationTitle'] = 'Naslov rezervacije';
        $strings['ReservationDescription'] = 'Opis rezervacije';
        $strings['ResourceList'] = 'Viri za rezervacijo';
        $strings['Accessories'] = 'Dodatki';
        $strings['Add'] = 'Dodaj';
        $strings['ParticipantList'] = 'Sodelujoči';
        $strings['InvitationList'] = 'Povabljeni';
        $strings['AccessoryName'] = 'Ime dodatka';
        $strings['QuantityAvailable'] = 'Razpoložljiva količina';
        $strings['Resources'] = 'Viri';
        $strings['Participants'] = 'Sodelujoči';
        $strings['User'] = 'Uporabnik';
        $strings['Resource'] = 'Vir';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Odobreno';
        $strings['Page'] = 'Stran';
        $strings['Rows'] = 'Vrstica';
        $strings['Unlimited'] = 'Neomejeno';
        $strings['Email'] = 'E-pošta';
        $strings['EmailAddress'] = 'Naslov e-pošte';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organizacija';
        $strings['Position'] = 'Položaj';
        $strings['Language'] = 'Jezik';
        $strings['Permissions'] = 'Dovoljenja';
        $strings['Reset'] = 'Ponastavi';
        $strings['FindGroup'] = 'Poišči skupino';
        $strings['Manage'] = 'Upravljanje';
        $strings['None'] = 'Noben';
        $strings['AddToOutlook'] = 'Dodaj v koledar (Outlook)';
        $strings['Done'] = 'Opravljeno';
        $strings['RememberMe'] = 'Zapomni si me';
        $strings['FirstTimeUser?'] = 'Prvič uporabljate sistem?';
        $strings['CreateAnAccount'] = 'Ustvarite račun';
        $strings['ViewSchedule'] = 'Pokaži urnik';
        $strings['ForgotMyPassword'] = 'Pozabljeno geslo';
        $strings['YouWillBeEmailedANewPassword'] = 'Novo, naključno ustvarjeno geslo vam bomo poslali po e-pošti.';
        $strings['Close'] = 'Zapri';
        $strings['ExportToCSV'] = 'Izvozi v CSV';
        $strings['OK'] = 'V redu';
        $strings['Working'] = 'Delam ...';
        $strings['Login'] = 'Prijava';
        $strings['AdditionalInformation'] = 'Dodatne informacije';
        $strings['AllFieldsAreRequired'] = 'vsa polja so obvezna';
        $strings['Optional'] = 'Opcijsko';
        $strings['YourProfileWasUpdated'] = 'Vaš profil je posodobljen';
        $strings['YourSettingsWereUpdated'] = 'Vaše nastavitve so posodobljene';
        $strings['Register'] = 'Registracija';
        $strings['SecurityCode'] = 'Varnostna koda';
        $strings['ReservationCreatedPreference'] = 'Ko ustvarim rezervacijo ali ko je rezervacija ustvarjena v mojem imenu';
        $strings['ReservationUpdatedPreference'] = 'Ko posodobim rezervacijo ali ko je rezervacija posodobljena v mojem imenu';
        $strings['ReservationDeletedPreference'] = 'Ko zbrišem rezervacijo ali ko je rezervacija zbrisana v mojem imenu';
        $strings['ReservationApprovalPreference'] = 'Ko je moja čakajoča rezervacija potrjana';
        $strings['PreferenceSendEmail'] = 'Pošlji e-pošto';
        $strings['PreferenceNoEmail'] = 'Ne me obveščati';
        $strings['ReservationCreated'] = 'Vaša rezervacija je bila uspešno ustvarjena!';
        $strings['ReservationUpdated'] = 'Vaša rezervacija je bila uspešno posodobljena!';
        $strings['ReservationRemoved'] = 'Vaša rezervacija je bila zbrisana';
        $strings['ReservationRequiresApproval'] = 'Eden ali več rezerviranih virov pred uporabo potrebuje potrditev. Ta rezervacija je na čakanju, dokler ni potrjena.';
        $strings['YourReferenceNumber'] = 'Vaša referenčna številka je %s';
        $strings['UpdatingReservation'] = 'Posodabljanje rezervacije';
        $strings['ChangeUser'] = 'Spremeni uporabnika';
        $strings['MoreResources'] = 'Več virov';
        $strings['ReservationLength'] = 'Trajanje rezervacije';
        $strings['ParticipantList'] = 'Seznam sodelujočih';
        $strings['AddParticipants'] = 'Dodaj sodelujoče';
        $strings['InviteOthers'] = 'Povabi ostale';
        $strings['AddResources'] = 'Dodaj vire';
        $strings['AddAccessories'] = 'Dodaj dodatke';
        $strings['Accessory'] = 'Dodatek';
        $strings['QuantityRequested'] = 'Zahtevana količina';
        $strings['CreatingReservation'] = 'Ustvarjanje rezervacije';
        $strings['UpdatingReservation'] = 'Posodabljanje rezervacije';
        $strings['DeleteWarning'] = 'Ta akcija ima trajne in nepopravljive posledice!';
        $strings['DeleteAccessoryWarning'] = 'Ob brisanju bo ta dodatek odstranjen iz vseh rezervacij.';
        $strings['AddAccessory'] = 'Dodaj dodatek';
        $strings['AddBlackout'] = 'Dodaj nerazpoložljive termine';
        $strings['AllResourcesOn'] = 'Vsi viri v';
        $strings['Reason'] = 'Razlog';
        $strings['BlackoutShowMe'] = 'Prikaži vse prekrivajoče rezervacije';
        $strings['BlackoutDeleteConflicts'] = 'Zbriši prekrivajoče rezervacije';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Med';
        $strings['CreatedBy'] = 'Ustvaril';
        $strings['BlackoutCreated'] = 'Ustvarjeni so nerazpoložljivi termini!';
        $strings['BlackoutNotCreated'] = 'Nerazpoložljivi termini ne morejo biti ustvarjeni!';
        $strings['BlackoutUpdated'] = 'Nerazpoložljivi termini so posodobljeni';
        $strings['BlackoutNotUpdated'] = 'Nerazpoložljivi termini ne morejo biti posodobljeni';
        $strings['BlackoutConflicts'] = 'Nekateri nerazpoložljivi termini se prekrivajo';
        $strings['ReservationConflicts'] = 'Nekateri rezervirani termini se prekrivajo';
        $strings['UsersInGroup'] = 'Uporabniki v tej skupini';
        $strings['Browse'] = 'Prebrskaj';
        $strings['DeleteGroupWarning'] = 'Brisanje te skupine bo odstranilo tudi vsa njen dovoljenja nad viri. Uporabniki te skupine lahko izgubijo dostop do virov.';
        $strings['WhatRolesApplyToThisGroup'] = 'Katere vloge veljajo za to skupino?';
        $strings['WhoCanManageThisGroup'] = 'Kdo lahko upravlja to skupino?';
        $strings['WhoCanManageThisSchedule'] = 'Kdo lahko upravlja ta urnik?';
        $strings['AddGroup'] = 'Dodaj skupino';
        $strings['AllQuotas'] = 'Vse kvote';
        $strings['QuotaReminder'] = 'Opozorilo: kvote se uporabijo glede na časovni pas urnika.';
        $strings['AllReservations'] = 'Vse rezervacije';
        $strings['PendingReservations'] = 'Rezervacije na čakanju';
        $strings['Approving'] = 'Potrjevanje';
        $strings['MoveToSchedule'] = 'Pojdi na urnik';
        $strings['DeleteResourceWarning'] = 'Brisanje tega vira bo zbrisalo tudi vse pripadajoče podatke, vključujoč';
        $strings['DeleteResourceWarningReservations'] = 'vse pretekle, trenutne in bodoče rezervacije, ki so povezane z njim';
        $strings['DeleteResourceWarningPermissions'] = 'vsa dodeljena dovoljenja';
        $strings['DeleteResourceWarningReassign'] = 'Preden nadaljujete, prosimo, da ponovno dodelite vse stvari, za katere nočete, da se zbrišejo';
        $strings['ScheduleLayout'] = 'Postavitev (vsi termini %s)';
        $strings['ReservableTimeSlots'] = 'Časovni intervali, ki jih lahko rezervirate';
        $strings['BlockedTimeSlots'] = 'Časovni intervali, ki so blokirani';
        $strings['ThisIsTheDefaultSchedule'] = 'To je privzet urnik';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Privzetega urnika ne morete zbrisati';
        $strings['MakeDefault'] = 'Vzami kot privzeto';
        $strings['BringDown'] = 'Prestavi navzdol';
        $strings['ChangeLayout'] = 'Spremeni postavitev';
        $strings['AddSchedule'] = 'Dodaj urnik';
        $strings['StartsOn'] = 'Začetek';
        $strings['NumberOfDaysVisible'] = 'Število vidnih dni';
        $strings['UseSameLayoutAs'] = 'Uporabi enako postavitev kot';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Opcijska oznaka';
        $strings['LayoutInstructions'] = 'Vnesite en interval v vsako vrstico. Intervali morajo biti vpisani za vseh 24 ur dneva, začnejo in končajo pa se ob 12:00.';
        $strings['AddUser'] = 'Dodaj uporabnika';
        $strings['UserPermissionInfo'] = 'Dejanski dostop do vira je lahko drugačen, saj je odvisen od vloge uporabnika, dovoljenj skupine ter zunanjih nastavitev dovoljenj';
        $strings['DeleteUserWarning'] = 'Brisanje tega uporabnika bo zbrisalo tudi vse njegove trenutne, bodoče in pretekle rezervacije.';
        $strings['AddAnnouncement'] = 'Dodaj obvestilo';
        $strings['Announcement'] = 'Obvestilo';
        $strings['Priority'] = 'Prioriteta';
        $strings['Reservable'] = 'Za rezervacijo';
        $strings['Unreservable'] = 'Ni za rezervacijo';
        $strings['Reserved'] = 'Rezervirano';
        $strings['MyReservation'] = 'Moje rezervacije';
        $strings['Pending'] = 'Na čakanju';
        $strings['Past'] = 'Preteklo';
        $strings['Restricted'] = 'Omejeno';
        $strings['ViewAll'] = 'Pokaži vse';
        $strings['MoveResourcesAndReservations'] = 'Premakni vire in rezervacije na';
        $strings['TurnOffSubscription'] = 'Izključi naročila na koledar';
        $strings['TurnOnSubscription'] = 'Dovoli naročila na ta koledar';
        $strings['SubscribeToCalendar'] = 'Naroči se na ta koledar';
        $strings['SubscriptionsAreDisabled'] = 'Adminirstator je onemogočil naročila na koledar';
        $strings['NoResourceAdministratorLabel'] = '(Ni administratorja virov)';
        $strings['WhoCanManageThisResource'] = 'Kdo lahko upravlja s tem virom?';
        $strings['ResourceAdministrator'] = 'Administrator virov';
        $strings['Private'] = 'Privatno';
        $strings['Accept'] = 'Sprejmi';
        $strings['Decline'] = 'Odkloni';
        $strings['ShowFullWeek'] = 'Prikaži celoten teden';
        $strings['CustomAttributes'] = 'Prilagojeni atributi';
        $strings['AddAttribute'] = 'Dodaj atribut';
        $strings['EditAttribute'] = 'Uredi atribut';
        $strings['DisplayLabel'] = 'Prikaži oznako';
        $strings['Type'] = 'Tip';
        $strings['Required'] = 'Obvezno';
        $strings['ValidationExpression'] = 'Izraz za validacijo';
        $strings['PossibleValues'] = 'Možne vrednosti';
        $strings['SingleLineTextbox'] = 'Enovrstično tekstovno polje';
        $strings['MultiLineTextbox'] = 'Večvrstično tekstovno polje';
        $strings['Checkbox'] = 'Stikalo';
        $strings['SelectList'] = 'Izbirni seznam';
        $strings['CommaSeparated'] = 'ločeno z vejico';
        $strings['Category'] = 'Kategorija';
        $strings['CategoryReservation'] = 'Rezervacija';
        $strings['CategoryGroup'] = 'Skupina';
        $strings['SortOrder'] = 'Vrstni red';
        $strings['Title'] = 'Naslov';
        $strings['AdditionalAttributes'] = 'Dodatni atributi';
        $strings['True'] = 'Da ';
        $strings['False'] = 'Ne ';
        $strings['ForgotPasswordEmailSent'] = 'Sporočilo z navodili za ponastavitev gesla je bilo poslano na podan e-poštni naslov';
        $strings['ActivationEmailSent'] = 'V kratkem boste prejeli e-pošto za aktivacijo.';
        $strings['AccountActivationError'] = 'Vašega računa ni mogoče aktivirati.';
        $strings['Attachments'] = 'Priponke';
        $strings['AttachFile'] = 'Pripeta datoteka';
        $strings['Maximum'] = 'največ';
        $strings['NoScheduleAdministratorLabel'] = 'Ni administratorja urnika';
        $strings['ScheduleAdministrator'] = 'Administrator urnika';
        $strings['Total'] = 'Skupaj';
        $strings['QuantityReserved'] = 'Rezervirana količina';
        $strings['AllAccessories'] = 'Vsi dodatki';
        $strings['GetReport'] = 'Pridobi poročilo';
        $strings['NoResultsFound'] = 'Ni ujemajočih se rezultatov';
        $strings['SaveThisReport'] = 'Shrani to poročilo';
        $strings['ReportSaved'] = 'Poročilo shranjeno!';
        $strings['EmailReport'] = 'Pošlji poročilo po e-pošti';
        $strings['ReportSent'] = 'Poročilo poslano!';
        $strings['RunReport'] = 'Izvedi poročilo';
        $strings['NoSavedReports'] = 'Nimate shranjenih poročil.';
        $strings['CurrentWeek'] = 'Trenutni teden';
        $strings['CurrentMonth'] = 'Trenutni mesec';
        $strings['AllTime'] = 'Ves čas';
        $strings['FilterBy'] = 'Filtriraj po';
        $strings['Select'] = 'Izberi';
        $strings['List'] = 'Seznam';
        $strings['TotalTime'] = 'Skupen čas';
        $strings['Count'] = 'Število';
        $strings['Usage'] = 'Uporaba';
        $strings['AggregateBy'] = 'Združi po';
        $strings['Range'] = 'Obseg';
        $strings['Choose'] = 'Izberi';
        $strings['All'] = 'Vse';
        $strings['ViewAsChart'] = 'Prikaži kot graf';
        $strings['ReservedResources'] = 'Rezervirani viri';
        $strings['ReservedAccessories'] = 'Rezervirani dodatki';
        $strings['ResourceUsageTimeBooked'] = 'Uporaba vira - čas rezervacij';
        $strings['ResourceUsageReservationCount'] = 'Uporaba vira - število rezervacij';
        $strings['Top20UsersTimeBooked'] = 'Naj 20 uporabnikov - čas rezervacij';
        $strings['Top20UsersReservationCount'] = 'Naj 20 uporabnikov - število rezervacij';
        $strings['ConfigurationUpdated'] = 'Konfiguracijska datoteka je bila posodobljena';
        $strings['ConfigurationUiNotEnabled'] = 'Ta stran ni dostopna, ker je $conf[\'settings\'][\'pages\'][\'enable.configuration\'] nastavljen na neresnično ali manjka.';
        $strings['ConfigurationFileNotWritable'] = 'V konfiguracijsko datoteko se ne da pisati. Prosimo, preverite dovoljenja te datoteke in poskusite znova.';
        $strings['ConfigurationUpdateHelp'] = 'Poglejte v razdelek Konfiguracija v <a target=_blank href=%s>datoteki s pomočjo</a> za dokumentacijo o teh nastavitvah.';
        $strings['GeneralConfigSettings'] = 'nastavitve';
        $strings['UseSameLayoutForAllDays'] = 'Za vse dneve uporabi enako postavitev';
        $strings['LayoutVariesByDay'] = 'Postavitev se spreminja glede na dan';
        $strings['ManageReminders'] = 'Opomniki';
        $strings['ReminderUser'] = 'ID uporabnika';
        $strings['ReminderMessage'] = 'Sporočilo';
        $strings['ReminderAddress'] = 'Naslov';
        $strings['ReminderSendtime'] = 'Čas za pošiljanje';
        $strings['ReminderRefNumber'] = 'Referenčna številka rezervacije';
        $strings['ReminderSendtimeDate'] = 'Datum opomnika';
        $strings['ReminderSendtimeTime'] = 'Čas opomnika (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Dodaj opomnik';
        $strings['DeleteReminderWarning'] = 'Ali ste prepričani?';
        $strings['NoReminders'] = 'Mimate prihajajočih opomnikov.';
        $strings['Reminders'] = 'Opomniki';
        $strings['SendReminder'] = 'Pošlji opomnik';
        $strings['minutes'] = 'minute';
        $strings['hours'] = 'ure';
        $strings['days'] = 'dnevi';
        $strings['ReminderBeforeStart'] = 'pred začetnim časom';
        $strings['ReminderBeforeEnd'] = 'pred končnnim časom';
        $strings['Logo'] = 'Logotip';
        $strings['CssFile'] = 'Datoteka CSS';
        $strings['ThemeUploadSuccess'] = 'Vaše spremembe so shranjene. Da bodo spremembe vidne, morate obnoviti prikaz podatkov na zaslonu.';
        $strings['MakeDefaultSchedule'] = 'Naj bo to moj privzet urnik';
        $strings['DefaultScheduleSet'] = 'To je sedaj vaš privzet urnik';
        $strings['FlipSchedule'] = 'Obrni postavitev urnika';
        $strings['Next'] = 'Naprej';
        $strings['Success'] = 'Uspeh';
        $strings['Participant'] = 'Sodelujoči';
        $strings['ResourceFilter'] = 'Filter virov';
        $strings['ResourceGroups'] = 'Skupina virov';
        $strings['AddNewGroup'] = 'Dodaj novo skupino';
        $strings['Quit'] = 'Izhod';
        $strings['AddGroup'] = 'Dodaj skupino';
        $strings['StandardScheduleDisplay'] = 'Uporabi standardni prikaz urnika';
        $strings['TallScheduleDisplay'] = 'Uporabi visok prikaz urnika';
        $strings['WideScheduleDisplay'] = 'Uporabi širok prikaz urnika';
        $strings['CondensedWeekScheduleDisplay'] = 'Uporabi prikaz urnika s skrčenimi tedni';
        $strings['ResourceGroupHelp1'] = 'Za ponovno organizacijo povleci in spusti skupino virov.';
        $strings['ResourceGroupHelp2'] = 'Desni klik na ime skupine virov prikaže dodatne akcije.';
        $strings['ResourceGroupHelp3'] = 'Za dodajanje virov v skupine, jih povlecite in spustite.';
        $strings['ResourceGroupWarning'] = 'Če uporabljate skupine virov, mora biti vsak vir dodeljen najmanj eni skupini. Nedodeljenih virov ni možno rezervirati.';
        $strings['ResourceType'] = 'Tip vira';
        $strings['AppliesTo'] = 'Velja za';
        $strings['UniquePerInstance'] = 'Edini na primerek';
        $strings['AddResourceType'] = 'Dodaj tip vira';
        $strings['NoResourceTypeLabel'] = '(tip vira ni nastavljen)';
        $strings['ClearFilter'] = 'Počisti filter';
        $strings['MinimumCapacity'] = 'Najmanjša kapaciteta';
        $strings['Color'] = 'Barva';
        $strings['Available'] = 'Na voljo';
        $strings['Unavailable'] = 'Ni na voljo';
        $strings['Hidden'] = 'Skrito';
        $strings['ResourceStatus'] = 'Status vira';
        $strings['CurrentStatus'] = 'Trenutni status';
        $strings['AllReservationResources'] = 'Vsi viri za rezervacijo';
        $strings['File'] = 'Datoteka';
        $strings['BulkResourceUpdate'] = 'Paketno posodabljanje virov';
        $strings['Unchanged'] = 'Nespremenjeno';
        $strings['Common'] = 'Skupno';
        $strings['AdvancedFilter'] = 'Napredni filter';
        $strings['AllParticipants'] = 'Vsi sodelujoči';
        $strings['ResourceAvailability'] = 'Razpoložljivost vira';
        $strings['UnavailableAllDay'] = 'Cel dan ni na voljo';
        $strings['AvailableUntil'] = 'Na voljo do';
        $strings['AvailableBeginningAt'] = 'Na voljo (začetek) od';
        $strings['AllowParticipantsToJoin'] = 'Dovoli sodelujočim, da se pridružijo';
        $strings['JoinThisReservation'] = 'Pridruži se tej rezervaciji';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Namesti program LibreBooking (samo MySQL)';
        $strings['IncorrectInstallPassword'] = 'Oprostite, geslo ni pravilno.';
        $strings['SetInstallPassword'] = 'Preden zaženete namestitev, morate nastaviti namestitveno geslo.';
        $strings['InstallPasswordInstructions'] = 'V %s nastavite prosim %s na geslo, ki je naključno in ga je težko uganiti, nato pa se vrnite na to strran.<br/>Lahko uporabite %s';
        $strings['NoUpgradeNeeded'] = 'Nadgradnja ni potrebna. Ko boste pognali namestitveni proces, se bodo zbrisali vsi obstoječi podatki in namestila se bo nova kopija programa LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Prosimo, vpišite vaše namestitveno geslo.';
        $strings['InstallPasswordLocation'] = 'To lahko najdete na %s v %s.';
        $strings['VerifyInstallSettings'] = 'Preden nadaljujete, preverite naslednje privzete nastavitve. Lahko jih spremenite v %s.';
        $strings['DatabaseName'] = 'Ime podatkovne baze';
        $strings['DatabaseUser'] = 'Uporabniško ime za podatkovno bazo';
        $strings['DatabaseHost'] = 'Gostiteljski sistem podatkovne baze';
        $strings['DatabaseCredentials'] = 'Podati morate prijavne podatke za uporabnika MySQL, ki ima dovoljenja za ustvarjanje novih podatkovnih baz. Če jih ne poznate, kontaktirajte administratorja vaše podatkovne baze. Pogosto se uporablja ime root.';
        $strings['MySQLUser'] = 'Uporabnik MySQL';
        $strings['InstallOptionsWarning'] = 'Naslednje opcije verjetno ne bodo delovale v gostujočem okolju. Če nameščate v gostujoče okolje, uporabite orodje čarovnik za MySQL, da dokončate te postopke.';
        $strings['CreateDatabase'] = 'Ustvari podatkovno bazo';
        $strings['CreateDatabaseUser'] = 'Ustvari uporabnika podatkovne baze';
        $strings['PopulateExampleData'] = 'Uvozi vzorčne podatke. Ustvari se tudi admin račun: admin/password ter uporabniški račun: user/password';
        $strings['DataWipeWarning'] = 'Opozorilo: ta akcija bo zbrisala vse obstoječe podatke';
        $strings['RunInstallation'] = 'Zaženi namestitev';
        $strings['UpgradeNotice'] = 'Nadgrajujete program z različice <b>%s</b> na različico <b>%s</b>';
        $strings['RunUpgrade'] = 'Zaženi nadgradnjo';
        $strings['Executing'] = 'Se izvaja';
        $strings['StatementFailed'] = 'Odpoved. Podrobnosti:';
        $strings['SQLStatement'] = 'Stavek SQL:';
        $strings['ErrorCode'] = 'Koda napake:';
        $strings['ErrorText'] = 'Opis napake:';
        $strings['InstallationSuccess'] = 'Namestitev je bila uspešno zaključena!';
        $strings['RegisterAdminUser'] = 'Registrirajte vašega administratorskega uporabnika (admin). To se zahteva, če niste uvozili vzorčnih podatkov. Poskrbite, da bo $conf[\'settings\'][\'allow.self.registration\'] = \'true\' v vaši datoteki %s.';
        $strings['LoginWithSampleAccounts'] = 'Če ste uvozili vzorčne podatke, se lahko prijavite kot administrator (admin/password) ali kot navaden uporabnik (user/password).';
        $strings['InstalledVersion'] = 'Trenutno teče različica %s programa LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Priporočamo, da nadgradite vašo konfiguracijsko datoteko';
        $strings['InstallationFailure'] = 'Pri namestitvi je prišlo do napak.  Prosimo, odpravite jih in ponovno poskusite z namestitvijo.';
        $strings['ConfigureApplication'] = 'Konfiguracija programa LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Vaša konfiguracijska datoteka je sedaj posodobljena!';
        $strings['ConfigUpdateFailure'] = 'Vaša konfiguracijska datoteka ne more biti samodejno posodobljena. Prosimo, prepišite vsebino datoteke config.php z naslednjo vsebino:';
        $strings['SelectUser'] = 'Izberi uporabnika';
        // End Install

        // Errors
        $strings['LoginError'] = 'Uporabniško ime in geslo se ne ujemata';
        $strings['ReservationFailed'] = 'Vaša rezervacija ni uspešno narejena';
        $strings['MinNoticeError'] = 'Ta rezervacija zahteva vnaprejšnjo najavo.  Najbolj zgoden termin rezervacije je %s.';
        $strings['MaxNoticeError'] = 'Te rezervacije ne morete narediti toliko v prihodnosti.  Najkasnejši termin za rezervacijo je %s.';
        $strings['MinDurationError'] = 'Ta rezervacija mora trajati najmanj %s.';
        $strings['MaxDurationError'] = 'Ta rezervacija ne more trajati dlje kot %s.';
        $strings['ConflictingAccessoryDates'] = 'Na voljo ni dovolj naslednjih dodatkov:';
        $strings['NoResourcePermission'] = 'Nimate dovoljenja za dostop do enega ali več zahtevanih virov.';
        $strings['ConflictingReservationDates'] = 'Obstajajo konfliktne rezervacije za naslednje datume:';
        $strings['StartDateBeforeEndDateRule'] = 'Začetni datum in čas rezervacije mora biti pred končnim datumom in časom.';
        $strings['RecurringWithoutTerminationRule'] = 'Za ponavljajoče blokade je potreben končni datum.';
        $strings['StartIsInPast'] = 'Začetni in končni datum rezervacije ne more biti v preteklosti.';
        $strings['EmailDisabled'] = 'Administrator je onemogočil obveščanje po elektronski pošti.';
        $strings['ValidLayoutRequired'] = 'Termini morajo biti postavljeni za vseh 24 ur v dnevu z začetkom in koncem ob 12:00.';
        $strings['CustomAttributeErrors'] = 'Problemi z dodatnimi atributi, ki ste jih podali:';
        $strings['CustomAttributeRequired'] = '%s je zahtevano polje.';
        $strings['CustomAttributeInvalid'] = 'Vrednost za %s ni pravilna.';
        $strings['AttachmentLoadingError'] = 'Oprostite, pri nalaganju zahtevane datoteke je prišlo do težav.';
        $strings['InvalidAttachmentExtension'] = 'Naložite lahko le datoteke tipa: %s';
        $strings['InvalidStartSlot'] = 'Zahtevani začetni datum in čas nista veljavna.';
        $strings['InvalidEndSlot'] = 'Zahtevani končni datum in čas nista veljavna.';
        $strings['MaxParticipantsError'] = '%s lahko podpira le %s udeležencev.';
        $strings['ReservationCriticalError'] = 'Pri shranjevanju vaše rezervacije je prišlo do kritične napake. Če se bo to nadaljevalo, kontaktirajte vašega administratorja sistema.';
        $strings['InvalidStartReminderTime'] = 'Čas začetnega opomnika ni veljaven.';
        $strings['InvalidEndReminderTime'] = 'Čas končnega opomnika ni veljaven.';
        $strings['QuotaExceeded'] = 'Presežena meja kvote.';
        $strings['MultiDayRule'] = '%s ne dovoljuje rezervacij preko več dni.';
        $strings['InvalidReservationData'] = 'Pri vaši zahtevi za rezervacijo je prišlo do težav.';
        $strings['PasswordError'] = 'Geslo mora vsebovati najmanj %s črk in najmanj %s številk.';
        $strings['PasswordErrorRequirements'] = 'Geslo mora vsebovati kombinacijo najmanj %s velikih in malih črk in %s številk.';
        $strings['NoReservationAccess'] = 'Za spremembo te rezervacije nimate dovoljenja.';
        $strings['PasswordControlledExternallyError'] = 'Vaše geslo kontrolira zunanji sistem in ga zato tu ne morete posodobiti.';
        $strings['NoResources'] = 'Niste dodali nobenih virov.';
        $strings['ParticipationNotAllowed'] = 'Za pridružitev tej rezervaciji nimate dovoljenja.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Ustvari rezervacijo';
        $strings['EditReservation'] = 'Uredi rezervacije';
        $strings['LogIn'] = 'Prijava';
        $strings['ManageReservations'] = 'Rezervacije';
        $strings['AwaitingActivation'] = 'Čakanje na aktivacijo';
        $strings['PendingApproval'] = 'Čakanje na odobritev';
        $strings['ManageSchedules'] = 'Urniki';
        $strings['ManageResources'] = 'Viri';
        $strings['ManageAccessories'] = 'Dodatki';
        $strings['ManageUsers'] = 'Uporabniki';
        $strings['ManageGroups'] = 'Skupine';
        $strings['ManageQuotas'] = 'Kvote';
        $strings['ManageBlackouts'] = 'Nedostopni termini';
        $strings['MyDashboard'] = 'Moja nadzorna plošča';
        $strings['ServerSettings'] = 'Nastavitve strežnika';
        $strings['Dashboard'] = 'Nadzorna plošča';
        $strings['Help'] = 'Pomoč';
        $strings['Administration'] = 'Administracija';
        $strings['About'] = 'O programu';
        $strings['Bookings'] = 'Rezervacije';
        $strings['Schedule'] = 'Urnik';
        $strings['Reservations'] = 'Rezervacije';
        $strings['Account'] = 'Račun';
        $strings['EditProfile'] = 'Uredi moj profil';
        $strings['FindAnOpening'] = 'Poišči odprt termin';
        $strings['OpenInvitations'] = 'Odprta povabila';
        $strings['MyCalendar'] = 'Moj koledar';
        $strings['ResourceCalendar'] = 'Koledar virov';
        $strings['Reservation'] = 'Nova rezervacija';
        $strings['Install'] = 'Namestitev';
        $strings['ChangePassword'] = 'Spremeni geslo';
        $strings['MyAccount'] = 'Moj račun';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Urejanje aplikacije';
        $strings['ForgotPassword'] = 'Pozabljeno geslo';
        $strings['NotificationPreferences'] = 'Preference obvestil';
        $strings['ManageAnnouncements'] = 'Obvestila';
        $strings['Responsibilities'] = 'Obveznosti';
        $strings['GroupReservations'] = 'Rezervacije za skupine';
        $strings['ResourceReservations'] = 'Rezervacije virov';
        $strings['Customization'] = 'Personalizacija';
        $strings['Attributes'] = 'Atributi';
        $strings['AccountActivation'] = 'Aktivacija računa';
        $strings['ScheduleReservations'] = 'Rezervacije urnika';
        $strings['Reports'] = 'Poročila';
        $strings['GenerateReport'] = 'Ustvari novo poročilo';
        $strings['MySavedReports'] = 'Moja shranjena poročila';
        $strings['CommonReports'] = 'Skupna poročila';
        $strings['ViewDay'] = 'Prikaži dan';
        $strings['Group'] = 'Skupina';
        $strings['ManageConfiguration'] = 'Konfiguracija aplikacije';
        $strings['LookAndFeel'] = 'Izgled';
        $strings['ManageResourceGroups'] = 'Skupine virov';
        $strings['ManageResourceTypes'] = 'Tipi virov';
        $strings['ManageResourceStatus'] = 'Statusi virov';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'N';
        $strings['DayMondaySingle'] = 'P';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'S';
        $strings['DayThursdaySingle'] = 'Č';
        $strings['DayFridaySingle'] = 'P';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'ned';
        $strings['DayMondayAbbr'] = 'pon';
        $strings['DayTuesdayAbbr'] = 'tor';
        $strings['DayWednesdayAbbr'] = 'sre';
        $strings['DayThursdayAbbr'] = 'čet';
        $strings['DayFridayAbbr'] = 'pet';
        $strings['DaySaturdayAbbr'] = 'sob';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Vaša rezervacija je bila potrjena';
        $strings['ReservationCreatedSubject'] = 'Vaša rezervacija je bila ustvarjena';
        $strings['ReservationUpdatedSubject'] = 'Vaša rezervacija je bila posodobljena';
        $strings['ReservationDeletedSubject'] = 'Vaša rezervacija je bila zbrisana';
        $strings['ReservationCreatedAdminSubject'] = 'Opozorilo: narejena rezervacija';
        $strings['ReservationUpdatedAdminSubject'] = 'Opozorilo: posodobljena rezervacija';
        $strings['ReservationDeleteAdminSubject'] = 'Opozorilo: zbrisana rezervacija';
        $strings['ReservationApprovalAdminSubject'] = 'Opozorilo: rezervacija zahteva vašo odobritev';
        $strings['ParticipantAddedSubject'] = 'Opozorilo o sodelovanju pri rezervaciji';
        $strings['ParticipantDeletedSubject'] = 'Zbrisana rezervacija';
        $strings['InviteeAddedSubject'] = 'Povabilo k rezervaciji';
        $strings['ResetPassword'] = 'Zahteva za ponastavitev gesla';
        $strings['ActivateYourAccount'] = 'Prosimo, aktivirajte vaš račun';
        $strings['ReportSubject'] = 'Vaše zahtevano poročilo (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Rezervacija za %s bo kmalu nastopila';
        $strings['ReservationEndingSoonSubject'] = 'Rezervacija za %s se bo kmalu zaključila';
        $strings['UserAdded'] = 'Dodan je bil nov uporabnik';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Ni preteklih rezervacij';
        $strings['PastReservations'] = 'Pretekla rezervacija';
        $strings['AllNoPastReservations'] = 'Ni preteklih rezervacij v zadnjih %s dneh';
        $strings['AllPastReservations'] = 'Vse pretekle rezervacije';
        $strings['Yesterday'] = 'Včeraj';
        $strings['EarlierThisWeek'] = 'Prejšnji teden';
        $strings['PreviousWeek'] = 'Prejšnji teden';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Vaša skupina nima prihodnjih rezervacij';
        $strings['GroupUpcomingReservations'] = 'Prihodnje rezervacije moje skupine(i)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Prijavljanje prek Facebooka ni uspelo. Poskusite znova.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nimate rezervacij, ki čakajo na odobritev';
        $strings['PendingApprovalReservations'] = 'Rezervacije, ki čakajo na odobritev';
        $strings['LaterThisMonth'] = 'Kasneje ta mesec';
        $strings['LaterThisYear'] = 'Kasneje letos';
        $strings['Remaining'] = 'Preostalo';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Ni manjkajočih rezervacij za odhod';
        $strings['MissingCheckOutReservations'] = 'Manjkajoče rezervacije za odhod';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Podrobnosti rezervacije ni mogoče videti, ker nimate dovoljenj za nobenega od virov v tej rezervaciji';
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
        $days['full'] = ['nedelja', 'ponedeljek', 'torek', 'sreda', 'četrtek', 'petek', 'sobota'];
        // The three letter abbreviation
        $days['abbr'] = ['ned', 'pon', 'tor', 'sre', 'čet', 'pet', 'sob'];
        // The two letter abbreviation
        $days['two'] = ['ne', 'po', 'to', 'sr', 'če', 'pe', 'so'];
        // The one letter abbreviation
        $days['letter'] = ['N', 'P', 'T', 'S', 'Č', 'P', 'S'];

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
        $months['full'] = ['januar', 'februar', 'marec', 'april', 'maj', 'junij', 'julij', 'avgust', 'september', 'oktober', 'november', 'december'];
        // The three letter month name
        $months['abbr'] = ['jan', 'feb', 'mar', 'apr', 'maj', 'jun', 'jul', 'avg', 'sep', 'okt', 'nov', 'dec'];

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
        return 'si-si';
    }
}
