<?php

require_once('en_us.php');

class ee_ee extends en_us
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

        $dates['general_date']            = 'd.m.Y';
        $dates['general_datetime']        = 'd.m.Y H:i:s';
        $dates['short_datetime']          = 'j.n.y H:i';
        $dates['schedule_daily']          = 'l, d.m.Y';
        $dates['reservation_email']      = 'd.m.Y @ H:i (e)';
        $dates['res_popup'] = 'D, n/d g:i A';
        $dates['res_popup_time'] = 'g:i A';
        $dates['short_reservation_date']  = 'j.n.y H:i';
        $dates['dashboard']               = 'D, d.n Y H:i';
        $dates['period_time']             = 'H:i';
        $dates['mobile_reservation_date'] = 'j.n H:i';
        $dates['general_date_js']         = 'dd.mm.yy';
        $dates['general_time_js']         = 'HH:mm';
        $dates['momentjs_datetime']       = 'DD.MM.YYYY HH:mm';
        $dates['calendar_time']           = 'HH:mm';
        $dates['calendar_dates']          = 'd. MMM';
        $dates['embedded_date']           = 'D, d.n';
        $dates['embedded_time']           = 'H:i';
        $dates['embedded_datetime']       = 'j.n H:i';
        $dates['report_date']             = '%d.%m';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = [];

        $strings['FirstName'] = 'Eesnimi';
        $strings['LastName'] = 'Perekonnanimi';
        $strings['Timezone'] = 'Ajavöönd';
        $strings['Edit'] = 'Muuda';
        $strings['Change'] = 'Muuda';
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
        $strings['PwMustMatch'] = 'Parooli kinnitus peab ühtima parooliga.';
        $strings['ValidEmailRequired'] = 'Kehtiv e-posti aadress on kohustuslik.';
        $strings['UniqueEmailRequired'] = 'See e-posti aadress on juba registreeritud.';
        $strings['UniqueUsernameRequired'] = 'See kasutajanimi on juba registreeritud.';
        $strings['UserNameRequired'] = 'Kasutajanimi on kohustuslik.';
        $strings['CaptchaMustMatch'] = 'Pildikood (CAPTCHA) on kohustuslik.';
        $strings['Today'] = 'Täna';
        $strings['Week'] = 'Nädal';
        $strings['Month'] = 'Kuu';
        $strings['BackToCalendar'] = 'Tagasi kalendrisse';
        $strings['BeginDate'] = 'Algus';
        $strings['EndDate'] = 'Lõpp';
        $strings['Username'] = 'Kasutajanimi';
        $strings['Password'] = 'Parool';
        $strings['PasswordConfirmation'] = 'Kinnita parool';
        $strings['DefaultPage'] = 'Vaikimisi avaleht';
        $strings['MyCalendar'] = 'Minu kalender';
        $strings['ScheduleCalendar'] = 'Ajakava kalender';
        $strings['Registration'] = 'Registreerimine';
        $strings['NoAnnouncements'] = 'Teadaandeid pole';
        $strings['Announcements'] = 'Teadaanded';
        $strings['NoUpcomingReservations'] = 'Sulle pole tulevasi broneeringuid';
        $strings['UpcomingReservations'] = 'Tulevased broneeringud';
        $strings['AllNoUpcomingReservations'] = 'Järgmise %s päeva jooksul pole ühtegi tulevast broneeringut';
        $strings['AllUpcomingReservations'] = 'Kõik tulevased broneeringud';
        $strings['ShowHide'] = 'Näita/Peida';
        $strings['Error'] = 'Viga';
        $strings['ReturnToPreviousPage'] = 'Pöördu tagasi eelmisele lehele';
        $strings['UnknownError'] = 'Tundmatu viga';
        $strings['DatabaseConnectionError'] = 'Andmebaasiserveriga ei õnnestunud ühendust luua.<br/>Paluda veebilehe administraatoril kontrollida andmebaasi hosti, kasutajanime ja parooli failis <code>config/config.php</code>.';
        $strings['DatabaseNotFoundError'] = 'Seadistatud andmebaasi ei õnnestunud valida.<br/>Paluda veebilehe administraatoril kontrollida andmebaasi nime failis <code>config/config.php</code> ja veenduda, et andmebaas on loodud ning algseadistatud.';
        $strings['InsufficientPermissionsError'] = 'Sul puuduvad õigused sellele ressursile juurdepääsuks';
        $strings['MissingReservationResourceError'] = 'Ressurss jäi valimata';
        $strings['MissingReservationScheduleError'] = 'Ajakava jäi valimata';
        $strings['DoesNotRepeat'] = 'Ei kordu';
        $strings['Daily'] = 'Iga päev';
        $strings['Weekly'] = 'Iga nädal';
        $strings['Monthly'] = 'Iga kuu';
        $strings['Yearly'] = 'Iga aasta';
        $strings['RepeatPrompt'] = 'Korda';
        $strings['hours'] = 'tundi';
        $strings['days'] = 'päeva';
        $strings['weeks'] = 'nädalat';
        $strings['months'] = 'kuud';
        $strings['years'] = 'aastat';
        $strings['day'] = 'päev';
        $strings['week'] = 'nädal';
        $strings['month'] = 'kuu';
        $strings['year'] = 'aasta';
        $strings['repeatDayOfMonth'] = 'kuupäeval';
        $strings['repeatDayOfWeek'] = 'nädalapäeval';
        $strings['RepeatUntilPrompt'] = 'Kuni';
        $strings['RepeatEveryPrompt'] = 'Iga';
        $strings['RepeatDaysPrompt'] = 'Päevadel';
        $strings['CreateReservationHeading'] = 'Uus broneering';
        $strings['EditReservationHeading'] = 'Broneeringu %s muutmine';
        $strings['ViewReservationHeading'] = 'Broneeringu %s vaatamine';
        $strings['ReservationErrors'] = 'Muuda broneeringut';
        $strings['Create'] = 'Loo';
        $strings['ThisInstance'] = 'Ainult see kordus';
        $strings['AllInstances'] = 'Kõik kordused';
        $strings['FutureInstances'] = 'Tulevased kordused';
        $strings['Print'] = 'Prindi';
        $strings['ShowHideNavigation'] = 'Näita/Peida navigatsioon';
        $strings['ReferenceNumber'] = 'Viitenumber';
        $strings['Tomorrow'] = 'Homme';
        $strings['LaterThisWeek'] = 'Hilisem sel nädalal';
        $strings['NextWeek'] = 'Järgmine nädal';
        $strings['SignOut'] = 'Logi välja';
        $strings['JavascriptRequired'] = 'See rakendus vajab nõuetekohaseks toimimiseks JavaScripti. Palun lülita JavaScript oma veebilehitseja seadetes sisse.';
        $strings['ScriptUrlNotConfigured'] = 'LibreBooking ei ole õigesti seadistatud. Seade <code>script.url</code> on tühi, mistõttu mõned rakenduse funktsioonid ei tööta. Palun võta ühendust administraatoriga.';
        $strings['ScriptUrlMissingWebSuffix'] = 'LibreBooking ei ole õigesti seadistatud. Seade <code>script.url</code> peab lõppema liitega <code>/Web</code>, vastasel juhul ei tööta navigatsioonilingid ja sisselogimise ümbersuunamised õigesti. Palun võta ühendust administraatoriga.';
        $strings['LayoutDescription'] = 'Algab %s, kuvades %s päeva korraga';
        $strings['AllResources'] = 'Kõik ressursid';
        $strings['TakeOffline'] = 'Märgi võrguühenduseta (võta maha)';
        $strings['BringOnline'] = 'Märgi võrguühenduses (too veebi)';
        $strings['AddImage'] = 'Lisa pilt';
        $strings['NoImage'] = 'Pilti pole määratud';
        $strings['Move'] = 'Liiguta';
        $strings['AppearsOn'] = 'Ilmub %s';
        $strings['Location'] = 'Asukoht';
        $strings['NoLocationLabel'] = '(asukoht määramata)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(kontaktandmed puuduvad)';
        $strings['Description'] = 'Kirjeldus';
        $strings['NoDescriptionLabel'] = '(kirjeldus puudub)';
        $strings['Notes'] = 'Märkused';
        $strings['NoNotesLabel'] = '(märkused puuduvad)';
        $strings['NoTitleLabel'] = '(pealkiri puudub)';
        $strings['UsageConfiguration'] = 'Kasutusseaded';
        $strings['ChangeConfiguration'] = 'Muuda seadistust';
        $strings['ResourceMinLength'] = 'Broneering peab kestma vähemalt %s';
        $strings['ResourceMinLengthNone'] = 'Broneeringu miinimumkestus puudub';
        $strings['ResourceMaxLength'] = 'Broneering ei tohi kesta kauem kui %s';
        $strings['ResourceMaxLengthNone'] = 'Broneeringu maksimumkestus puudub';
        $strings['ResourceRequiresApproval'] = 'Broneeringud vajavad kinnitamist';
        $strings['ResourceRequiresApprovalNone'] = 'Broneeringud ei vaja kinnitamist';
        $strings['ResourcePermissionAutoGranted'] = 'Juurdepääsuõigus antakse automaatselt';
        $strings['ResourcePermissionNotAutoGranted'] = 'Juurdepääsuõigust ei anta automaatselt';
        $strings['ResourceMinNotice'] = 'Broneering tuleb teha vähemalt %s enne algusaega';
        $strings['ResourceMinNoticeNone'] = 'Broneeringuid saab teha kuni praeguse hetkeni';
        $strings['ResourceMinNoticeUpdate'] = 'Broneeringut tuleb muuta vähemalt %s enne algusaega';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Broneeringuid saab muuta kuni praeguse hetkeni';
        $strings['ResourceMinNoticeDelete'] = 'Broneering tuleb kustutada vähemalt %s enne algusaega';
        $strings['ResourceMinNoticeNoneDelete'] = 'Broneeringuid saab kustutada kuni praeguse hetkeni';
        $strings['ResourceMaxNotice'] = 'Broneering ei tohi lõppeda hiljem kui %s praegusest hetkest';
        $strings['ResourceMaxNoticeNone'] = 'Broneeringud võivad lõppeda ükskõik millal tulevikus';
        $strings['ResourceBufferTime'] = 'Broneeringute vahele peab jääma %s';
        $strings['ResourceBufferTimeNone'] = 'Broneeringute vahel puhveraega pole';
        $strings['ResourceAllowMultiDay'] = 'Broneeringuid saab teha üle mitme päeva';
        $strings['ResourceNotAllowMultiDay'] = 'Broneeringuid ei saa teha üle mitme päeva';
        $strings['ResourceCapacity'] = 'Selle ressursi mahutavus on %s inimest';
        $strings['ResourceCapacityNone'] = 'Sellel ressursil on piiramatu mahutavus';
        $strings['AddNewResource'] = 'Lisa uus ressurss';
        $strings['AddNewUser'] = 'Lisa uus kasutaja';
        $strings['AddResource'] = 'Lisa ressurss';
        $strings['Capacity'] = 'Mahutavus';
        $strings['Access'] = 'Juurdepääs';
        $strings['Duration'] = 'Kestus';
        $strings['Active'] = 'Aktiivne';
        $strings['Inactive'] = 'Mitteaktiivne';
        $strings['ResetPassword'] = 'Lähtesta parool';
        $strings['LastLogin'] = 'Viimane sisselogimine';
        $strings['Search'] = 'Osi';
        $strings['ResourcePermissions'] = 'Ressursi õigused';
        $strings['Reservations'] = 'Broneeringud';
        $strings['Groups'] = 'Grupid';
        $strings['Users'] = 'Kasutajad';
        $strings['AllUsers'] = 'Kõik kasutajad';
        $strings['AllGroups'] = 'Kõik grupid';
        $strings['AllSchedules'] = 'Kõik ajakavad';
        $strings['UsernameOrEmail'] = 'Kasutajanimi või e-post';
        $strings['Members'] = 'Liikmed';
        $strings['QuickSlotCreation'] = 'Loo ajapulbad iga %s minuti järel vahemikus %s kuni %s';
        $strings['ApplyUpdatesTo'] = 'Rakenda muudatused kohale';
        $strings['CancelParticipation'] = 'Tühista osalemine';
        $strings['Attending'] = 'Osaleb';
        $strings['QuotaConfiguration'] = 'Kohas %s on kasutajatel rollis %s piirang %s %s %s kohta';
        $strings['QuotaEnforcement'] = 'Rakendatud %s %s';
        $strings['reservations'] = 'broneeringut';
        $strings['reservation'] = 'broneering';
        $strings['ChangeCalendar'] = 'Muuda kalendrit';
        $strings['AddQuota'] = 'Lisa kvoot';
        $strings['FindUser'] = 'Osi kasutajat';
        $strings['Created'] = 'Loodud';
        $strings['LastModified'] = 'Viimati muudetud';
        $strings['GroupName'] = 'Grupi nimi';
        $strings['GroupMembers'] = 'Grupi liikmed';
        $strings['GroupRoles'] = 'Grupi rollid';
        $strings['GroupAdmin'] = 'Grupi administraator';
        $strings['Actions'] = 'Tegevused';
        $strings['CurrentPassword'] = 'Praegune parool';
        $strings['NewPassword'] = 'Uus parool';
        $strings['InvalidPassword'] = 'Praegune parool on vale';
        $strings['PasswordChangedSuccessfully'] = 'Sinu parool on edukalt muudetud';
        $strings['SignedInAs'] = 'Sisse logitud kui';
        $strings['NotSignedIn'] = 'Sa ei ole sisse logitud';
        $strings['ReservationTitle'] = 'Broneeringu pealkiri';
        $strings['ReservationDescription'] = 'Broneeringu kirjeldus';
        $strings['ResourceList'] = 'Broneeritavad ressursid';
        $strings['Accessories'] = 'Lisatarvikud';
        $strings['InvitationList'] = 'Kutsutud';
        $strings['AccessoryName'] = 'Lisatarviku nimi';
        $strings['QuantityAvailable'] = 'Saadav kogus';
        $strings['Resources'] = 'Ressursid';
        $strings['Participants'] = 'Osalejad';
        $strings['User'] = 'Kasutaja';
        $strings['Resource'] = 'Ressurss';
        $strings['Status'] = 'Olek';
        $strings['Approve'] = 'Kinnita';
        $strings['Page'] = 'Lehekülg';
        $strings['Rows'] = 'Ridu';
        $strings['Unlimited'] = 'Piiramatu';
        $strings['Email'] = 'E-post';
        $strings['EmailAddress'] = 'E-posti aadress';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organisatsioon';
        $strings['Position'] = 'Ametikoht';
        $strings['Language'] = 'Keel';
        $strings['Permissions'] = 'Õigused';
        $strings['Reset'] = 'Lähtesta';
        $strings['FindGroup'] = 'Osi gruppi';
        $strings['Manage'] = 'Halda';
        $strings['None'] = 'Puudub';
        $strings['AddToOutlook'] = 'Lisa kalendrisse';
        $strings['Done'] = 'Valmis';
        $strings['RememberMe'] = 'Jäta mind meelde';
        $strings['FirstTimeUser?'] = 'Esmakordne kasutaja?';
        $strings['CreateAnAccount'] = 'Loo konto';
        $strings['ViewSchedule'] = 'Vaata ajakava';
        $strings['ForgotMyPassword'] = 'Unustasin parooli';
        $strings['YouWillBeEmailedANewPassword'] = 'Sulle saadetakse e-postiga uus juhuslikult genereeritud parool';
        $strings['Close'] = 'Sulge';
        $strings['ExportToCSV'] = 'Ekspordi CSV-faili';
        $strings['OK'] = 'Sobilik';
        $strings['Working'] = 'Töötlemisel...';
        $strings['Login'] = 'Logi sisse';
        $strings['AdditionalInformation'] = 'Lisateave';
        $strings['AllFieldsAreRequired'] = 'kõik väljad on kohustuslikud';
        $strings['Optional'] = 'valikuline';
        $strings['YourProfileWasUpdated'] = 'Sinu profiil on uuendatud';
        $strings['YourSettingsWereUpdated'] = 'Sinu seaded on uuendatud';
        $strings['Register'] = 'Registreeru';
        $strings['SecurityCode'] = 'Turvakood';
        $strings['ReservationCreatedPreference'] = 'Kui ma loon broneeringu või broneering luuakse minu nimel';
        $strings['ReservationUpdatedPreference'] = 'Kui ma muudan broneeringut või broneeringut muudetakse minu nimel';
        $strings['ReservationDeletedPreference'] = 'Kui ma kustutan broneeringu või broneering kustutatakse minu nimel';
        $strings['ReservationApprovalPreference'] = 'Kui minu ootel broneering kinnitatakse';
        $strings['PreferenceSendEmail'] = 'Saada mulle e-kiri';
        $strings['PreferenceNoEmail'] = 'Ära teavita mind';
        $strings['ReservationCreated'] = 'Sinu broneering on edukalt loodud!';
        $strings['ReservationUpdated'] = 'Sinu broneering on edukalt uuendatud!';
        $strings['ReservationRemoved'] = 'Sinu broneering on eemaldatud';
        $strings['ReservationRequiresApproval'] = 'Üks või mitu broneeritud ressurssi vajavad enne kasutamist kinnitamist. See broneering jääb ootele kuni selle kinnitamiseni.';
        $strings['YourReferenceNumber'] = 'Sinu viitenumber on %s';
        $strings['ChangeUser'] = 'Muuda kasutajat';
        $strings['MoreResources'] = 'Rohkem ressursse';
        $strings['ReservationLength'] = 'Broneeringu kestus';
        $strings['ParticipantList'] = 'Osalejate nimekiri';
        $strings['AddParticipants'] = 'Lisa osalejaid';
        $strings['InviteOthers'] = 'Kutsu teisi';
        $strings['AddResources'] = 'Lisa ressursse';
        $strings['AddAccessories'] = 'Lisa lisatarvikuid';
        $strings['Accessory'] = 'Lisatarvik';
        $strings['QuantityRequested'] = 'Soovitud kogus';
        $strings['CreatingReservation'] = 'Broneeringu loomine';
        $strings['UpdatingReservation'] = 'Broneeringu uuendamine';
        $strings['DeleteWarning'] = 'See tegevus on püsiv ning seda ei saa tagasi võtta!';
        $strings['DeleteAccessoryWarning'] = 'Selle lisatarviku kustutamine eemaldab selle kõikidest broneeringutest.';
        $strings['AddAccessory'] = 'Lisa lisatarvik';
        $strings['AddBlackout'] = 'Lisa broneerimiskeeld (blackout)';
        $strings['AllResourcesOn'] = 'Kõik ressursid ajakavas';
        $strings['Reason'] = 'Põhjus';
        $strings['BlackoutShowMe'] = 'Näita mulle konfliktseid broneeringuid';
        $strings['BlackoutDeleteConflicts'] = 'Kustuta konfliktsed broneeringud';
        $strings['Filter'] = 'Filtreeri';
        $strings['Between'] = 'Vahemikus';
        $strings['CreatedBy'] = 'Looja';
        $strings['BlackoutCreated'] = 'Broneerimiskeeld loodud';
        $strings['BlackoutNotCreated'] = 'Broneerimiskeeldu ei saanud luua';
        $strings['BlackoutUpdated'] = 'Broneerimiskeeld uuendatud';
        $strings['BlackoutNotUpdated'] = 'Broneerimiskeeldu ei saanud uuendada';
        $strings['BlackoutConflicts'] = 'Esinavad konfliktsed broneerimiskeeldu ajad';
        $strings['ReservationConflicts'] = 'Esinavad konfliktsed broneeringute ajad';
        $strings['UsersInGroup'] = 'Kasutajad selles grupis';
        $strings['Browse'] = 'Sirvi';
        $strings['DeleteGroupWarning'] = 'Selle grupi kustutamine eemaldab kõik seotud ressursiõigused. Selle grupi kasutajad võivad kaotada juurdepääsu ressurssidele.';
        $strings['WhatRolesApplyToThisGroup'] = 'Millised rollid kehtivad sellele meeskonnale/grupile?';
        $strings['WhoCanManageThisGroup'] = 'Kes saab seda meeskonda/gruppi hallata?';
        $strings['WhoCanManageThisSchedule'] = 'Kes saab seda ajakava hallata?';
        $strings['AllQuotas'] = 'Kõik kvoodid';
        $strings['QuotaReminder'] = 'Pea meeles: Kvoote rakendatakse ajakava ajavööndi alusel.';
        $strings['AllReservations'] = 'Kõik broneeringud';
        $strings['PendingReservations'] = 'Ootel broneeringud';
        $strings['Approving'] = 'Kinnitamine';
        $strings['MoveToSchedule'] = 'Teisalda ajakavasse';
        $strings['DeleteResourceWarning'] = 'Selle ressursi kustutamine eemaldab kõik seotud andmed, sealhulgas';
        $strings['DeleteResourceWarningReservations'] = 'kõik sellega seotud möödunud, praegused ja tulevased broneeringud';
        $strings['DeleteResourceWarningPermissions'] = 'kõik määratud juurdepääsuõigused';
        $strings['DeleteResourceWarningReassign'] = 'Palun määra enne jätkamist ümber kõik andmed, mida sa ei soovi kustutada';
        $strings['ScheduleLayout'] = 'Plaaning (kõik ajad %s)';
        $strings['ReservableTimeSlots'] = 'Broneeritavad ajapulbad';
        $strings['BlockedTimeSlots'] = 'Blokeeritud ajapulbad';
        $strings['ThisIsTheDefaultSchedule'] = 'See on vaikimisi ajakava';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Vaikimisi ajakava ei saa kustutada';
        $strings['MakeDefault'] = 'Määra vaikimisi ajakavaks';
        $strings['BringDown'] = 'Liiguta allapoole';
        $strings['ChangeLayout'] = 'Muuda plaaningut';
        $strings['AddSchedule'] = 'Lisa ajakava';
        $strings['StartsOn'] = 'Algab päeval';
        $strings['NumberOfDaysVisible'] = 'Nähtavate päevade arv';
        $strings['UseSameLayoutAs'] = 'Kasuta sama plaaningut kui';
        $strings['Format'] = 'Vorming';
        $strings['OptionalLabel'] = 'Valikuline silt';
        $strings['LayoutInstructions'] = 'Sisesta üks ajapulge reale. Ajapulbad peavad katma kõik 24 tundi ööpäevas, algades ja lõppedes kell 00:00.';
        $strings['AddUser'] = 'Lisa kasutaja';
        $strings['UserPermissionInfo'] = 'Tegelik juurdepääs ressursile võib erineda sõltuvalt kasutaja rollist, grupi õigustest või välistest õiguste seadetest';
        $strings['DeleteUserWarning'] = 'Selle kasutaja kustutamine eemaldab kõik tema praegused, tulevased ja ajaloolised broneeringud.';
        $strings['AddAnnouncement'] = 'Lisa teadaanne';
        $strings['Announcement'] = 'Teadaanne';
        $strings['Priority'] = 'Prioriteet';
        $strings['Reservable'] = 'Avatud';
        $strings['Unreservable'] = 'Blokeeritud';
        $strings['Reserved'] = 'Broneeritud';
        $strings['MyReservation'] = 'Minu broneering';
        $strings['Pending'] = 'Ootel';
        $strings['Past'] = 'Möödunud';
        $strings['Restricted'] = 'Piiratud';
        $strings['ViewAll'] = 'Vaata kõiki';
        $strings['MoveResourcesAndReservations'] = 'Teisalda ressursid ja broneeringud sihtkohta';
        $strings['TurnOffSubscription'] = 'Peida avalikkuse eest';
        $strings['TurnOnSubscription'] = 'Näita avalikkusele (RSS, iCalendar, tahvelarvuti, ekraan)';
        $strings['SubscribeToCalendar'] = 'Telli see kalender';
        $strings['UrlCopiedToClipboard'] = 'URL kopeeritud lõikepuhvrisse';
        $strings['SubscriptionsAreDisabled'] = 'Administraator on kalendrite tellimise välja lülitanud';
        $strings['NoResourceAdministratorLabel'] = '(Ressursi administraator puudub)';
        $strings['WhoCanManageThisResource'] = 'Kes saab seda ressurssi hallata?';
        $strings['ResourceAdministrator'] = 'Ressursi administraator';
        $strings['Private'] = 'Isiklik';
        $strings['Accept'] = 'Nõustu';
        $strings['Decline'] = 'Keeldu';
        $strings['ShowFullWeek'] = 'Näita tervet nädalat';
        $strings['CustomAttributes'] = 'Kohandatud atribuudid';
        $strings['AddAttribute'] = 'Lisa atribuut';
        $strings['EditAttribute'] = 'Uuenda atribuuti';
        $strings['DisplayLabel'] = 'Kuvatav silt';
        $strings['Type'] = 'Tüüp';
        $strings['Required'] = 'Kohustuslik';
        $strings['ValidationExpression'] = 'Validatsiooniavatis';
        $strings['PossibleValues'] = 'Võimalikud väärtused';
        $strings['SingleLineTextbox'] = 'Üherealine tekstiväli';
        $strings['MultiLineTextbox'] = 'Mitmerealine tekstiväli';
        $strings['Checkbox'] = 'Märkeruut';
        $strings['SelectList'] = 'Valikuloend';
        $strings['CommaSeparated'] = 'eraldatud komadega';
        $strings['Category'] = 'Kategooria';
        $strings['CategoryReservation'] = 'Broneering';
        $strings['CategoryGroup'] = 'Grupp';
        $strings['SortOrder'] = 'Sorteerimise järjekord';
        $strings['Title'] = 'Pealkiri';
        $strings['AdditionalAttributes'] = 'Lisasümbolid / lisaatribuudid';
        $strings['True'] = 'Tõene';
        $strings['False'] = 'Väär';
        $strings['ForgotPasswordEmailSent'] = 'Sinu sisestatud e-posti aadressile saadeti kiri parooli lähtestamise juhistega';
        $strings['ActivationEmailSent'] = 'Saadad peagi konto aktiveerimise e-kirja.';
        $strings['AccountActivationError'] = 'Kahjuks ei saanud me sinu kontot aktiveerida.';
        $strings['Attachments'] = 'Manused';
        $strings['AttachFile'] = 'Lisa manus';
        $strings['Maximum'] = 'maks';
        $strings['NoScheduleAdministratorLabel'] = 'Ajakava administraator puudub';
        $strings['ScheduleAdministrator'] = 'Ajakava administraator';
        $strings['Total'] = 'Kokku';
        $strings['QuantityReserved'] = 'Broneeritud kogus';
        $strings['AllAccessories'] = 'Kõik lisatarvikud';
        $strings['GetReport'] = 'Koosta aruanne';
        $strings['NoResultsFound'] = 'Vastavaid tulemusi ei leitud';
        $strings['SaveThisReport'] = 'Salvesta see aruanne';
        $strings['ReportSaved'] = 'Aruanne salvestatud!';
        $strings['EmailReport'] = 'Saada aruanne e-postiga';
        $strings['ReportSent'] = 'Aruanne saadetud!';
        $strings['RunReport'] = 'Käivita aruanne';
        $strings['NoSavedReports'] = 'Sul ei ole salvestatud aruandeid.';
        $strings['CurrentWeek'] = 'Käesolev nädal';
        $strings['CurrentMonth'] = 'Käesolev kuu';
        $strings['AllTime'] = 'Kogu aeg';
        $strings['FilterBy'] = 'Filtreeri kohaselt';
        $strings['Select'] = 'Vali';
        $strings['List'] = 'Nimekiri';
        $strings['TotalTime'] = 'Aeg';
        $strings['Count'] = 'Arv';
        $strings['Usage'] = 'Kasutus';
        $strings['AggregateBy'] = 'Grupeeri kohaselt';
        $strings['Range'] = 'Vahemik';
        $strings['Choose'] = 'Vali';
        $strings['All'] = 'Kõik';
        $strings['ViewAsChart'] = 'Kuva graafikuna';
        $strings['ReservedResources'] = 'Broneeritud ressursid';
        $strings['ReservedAccessories'] = 'Broneeritud lisatarvikud';
        $strings['ResourceUsageTimeBooked'] = 'Ressursi kasutus - Broneeritud aeg';
        $strings['ResourceUsageReservationCount'] = 'Ressursi kasutus - Broneeringute arv';
        $strings['Top20UsersTimeBooked'] = 'Top 20 kasutajat - Broneeritud aeg';
        $strings['Top20UsersReservationCount'] = 'Top 20 kasutajat - Broneeringute arv';
        $strings['ConfigurationUpdated'] = 'Seadistusfail on uuendatud';
        $strings['ConfigurationUiNotEnabled'] = 'Sellele lehele ei pääse juurde, sest väärtus $conf[\'settings\'][\'pages\'][\'enable.configuration\'] on seatud \'false\' või puudub.';
        $strings['ConfigurationFileNotWritable'] = 'Seadistusfail ei ole kirjutatav. Palun kontrolli selle faili õigusi ja proovi uuesti.';
        $strings['ConfigurationEnvWarning'] = 'Mõned seadistusväärtused kirjutatakse üle sinu keskkonnamuutujate või <code>.env</code> faili poolt. Muudatused võivad olla võimalikud vaid vastavate keskkonnamuutujate eemaldamisel.';
        $strings['ConfigurationUpdateHelp'] = 'Nende seadete dokumentatsiooni leiate <a target=_blank href=%s class=link-primary>Abifaili</a> jaotisest Seadistamine.';
        $strings['GeneralConfigSettings'] = 'seaded';
        $strings['UseSameLayoutForAllDays'] = 'Kasuta kõigil päevadel sama plaaningut';
        $strings['LayoutVariesByDay'] = 'Plaaning varieerub päeviti';
        $strings['ManageReminders'] = 'Meeldetuletused';
        $strings['ReminderUser'] = 'Kasutaja ID';
        $strings['ReminderMessage'] = 'Sõnum';
        $strings['ReminderAddress'] = 'Aadressid';
        $strings['ReminderSendtime'] = 'Saatmise aeg';
        $strings['ReminderRefNumber'] = 'Broneeringu viitenumber';
        $strings['ReminderSendtimeDate'] = 'Meeldetuletuse kuupäev';
        $strings['ReminderSendtimeTime'] = 'Meeldetuletuse kellaaeg (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Lisa meeldetuletus';
        $strings['DeleteReminderWarning'] = 'Kas oled kindel, et soovid selle kustutada?';
        $strings['NoReminders'] = 'Sul ei ole tulevasi meeldetuletusi.';
        $strings['Reminders'] = 'Meeldetuletused';
        $strings['SendReminder'] = 'Saada meeldetuletus';
        $strings['minutes'] = 'minutit';
        $strings['hours'] = 'tundi';
        $strings['days'] = 'päeva';
        $strings['ReminderBeforeStart'] = 'enne algusaega';
        $strings['ReminderBeforeEnd'] = 'enne lõpuaega';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS-fail';
        $strings['ThemeUploadSuccess'] = 'Sinu muudatused on salvestatud. Muudatuste rakendumiseks värskenda lehte.';
        $strings['MakeDefaultSchedule'] = 'Määra see minu vaikimisi ajakavaks';
        $strings['DefaultScheduleSet'] = 'See on nüüd sinu vaikimisi ajakava';
        $strings['FlipSchedule'] = 'Pööra ajakava plaaningut (vaheta teljed)';
        $strings['Next'] = 'Järgmine';
        $strings['Success'] = 'Edukas';
        $strings['Participant'] = 'Osaleja';
        $strings['ResourceFilter'] = 'Ressursi filter';
        $strings['ResourceGroups'] = 'Ressursigrupid';
        $strings['AddNewGroup'] = 'Lisa uus grupp';
        $strings['Quit'] = 'Välju';
        $strings['AddGroup'] = 'Lisa grupp';
        $strings['StandardScheduleDisplay'] = 'Kasuta standardset ajakava kuva';
        $strings['TallScheduleDisplay'] = 'Kasuta kõrget ajakava kuva';
        $strings['WideScheduleDisplay'] = 'Kasuta laia ajakava kuva';
        $strings['CondensedWeekScheduleDisplay'] = 'Kasuta tihendatud nädala ajakava kuva';
        $strings['ResourceGroupHelp1'] = 'Umberkorraldamiseks lohista ressursigruppe.';
        $strings['ResourceGroupHelp2'] = 'Lisategevustest nägemiseks tee paremklõps ressursigrupi nimel.';
        $strings['ResourceGroupHelp3'] = 'Ressursside lisamiseks gruppidesse lohista need kohale.';
        $strings['ResourceGroupWarning'] = 'Ressursigruppide kasutamisel peab iga ressurss olema määratud vähemalt ühte gruppi. Määramata ressursse ei saa broneerida.';
        $strings['ResourceType'] = 'Ressursi tüüp';
        $strings['AppliesTo'] = 'Kehtib instantsile';
        $strings['UniquePerInstance'] = 'Unikaalne eksemplari kohta';
        $strings['AddResourceType'] = 'Lisa ressursi tüüp';
        $strings['NoResourceTypeLabel'] = '(ressursi tüüpi pole määratud)';
        $strings['ClearFilter'] = 'Tühjenda filter';
        $strings['MinimumCapacity'] = 'Miinimummahutavus';
        $strings['Color'] = 'Värv';
        $strings['Available'] = 'Saadaval';
        $strings['Unavailable'] = 'Pole saadaval';
        $strings['Hidden'] = 'Peidetud';
        $strings['ResourceStatus'] = 'Ressursi olek';
        $strings['CurrentStatus'] = 'Praegune olek';
        $strings['AllReservationResources'] = 'Kõik broneeringu ressursid';
        $strings['File'] = 'Fail';
        $strings['BulkResourceUpdate'] = 'Ressursside hulgimuutmine';
        $strings['Unchanged'] = 'Muutmata';
        $strings['Common'] = 'Üldine';
        $strings['AdminOnly'] = 'Vaid administraatorile';
        $strings['AdvancedFilter'] = 'Täpsem filter';
        $strings['MinimumQuantity'] = 'Miinimumkogus';
        $strings['MaximumQuantity'] = 'Maksimumkogus';
        $strings['ChangeLanguage'] = 'Muuda keelt';
        $strings['AddRule'] = 'Lisa reegel';
        $strings['Attribute'] = 'Atribuut';
        $strings['RequiredValue'] = 'Nõutud väärtus';
        $strings['ReservationCustomRuleAdd'] = 'Kasuta seda värvi, kui broneeringu atribuudi väärtuseks on määratud järgnev';
        $strings['AddReservationColorRule'] = 'Lisa broneeringu värvireegel';
        $strings['LimitAttributeScope'] = 'Kogu spetsiifilistel juhtudel';
        $strings['CollectFor'] = 'Kogu järgneva jaoks';
        $strings['SignIn'] = 'Logi sisse';
        $strings['SignInWith'] = 'Logi sisse teenusega';
        $strings['AllParticipants'] = 'Kõik osalejad';
        $strings['RegisterANewAccount'] = 'Registreeri uus konto';
        $strings['Dates'] = 'Kuupäevad';
        $strings['More'] = 'Veel';
        $strings['ResourceAvailability'] = 'Ressursi saadavus';
        $strings['UnavailableAllDay'] = 'Terve päev kättesaamatu';
        $strings['AvailableUntil'] = 'Saadaval kuni';
        $strings['AvailableBeginningAt'] = 'Saadaval alates';
        $strings['AvailableAt'] = 'Saadaval kell';
        $strings['AllResourceTypes'] = 'Kõik ressursitüübid';
        $strings['AllResourceStatuses'] = 'Kõik ressursiolekud';
        $strings['AllowParticipantsToJoin'] = 'Luba osalejatel liituda';
        $strings['Join'] = 'Liitu';
        $strings['YouAreAParticipant'] = 'Oled selle broneeringu osaleja';
        $strings['YouAreInvited'] = 'Oled kutsutud sellele broneeringule';
        $strings['YouCanJoinThisReservation'] = 'Saad selle broneeringuga liituda';
        $strings['Import'] = 'Impordi';
        $strings['GetTemplate'] = 'Loo/laadi mall';
        $strings['UserImportInstructions'] = '<ul><li>Fail peab olema CSV-vormingus.</li><li>Kasutajanimi ja e-post on kohustuslikud väljad.</li><li>Atribuutide kehtivust ei kontrollita.</li><li>Teiste väljade tühjaks jätmisel seatakse vaikimisi väärtused ning kasutaja parooliks saab \'password\'.</li><li>Kasuta näitena kaasasolevat malli.</li></ul>';
        $strings['RowsImported'] = 'Imporditud read';
        $strings['RowsSkipped'] = 'Vahele jäetud read';
        $strings['Columns'] = 'Veerud';
        $strings['Reserve'] = 'Broneeri';
        $strings['AllDay'] = 'Terve päev';
        $strings['Everyday'] = 'Iga päev';
        $strings['IncludingCompletedReservations'] = 'Koos lõpetatud broneeringutega';
        $strings['NotCountingCompletedReservations'] = 'Ilma lõpetatud broneeringuteta';
        $strings['RetrySkipConflicts'] = 'Jäta konfliktsed broneeringud vahele';
        $strings['Retry'] = 'Proovi uuesti';
        $strings['RemoveExistingPermissions'] = 'Kas eemaldada olemasolevad õigused?';
        $strings['Continue'] = 'Jätka';
        $strings['WeNeedYourEmailAddress'] = 'Broneerimiseks on vaja sinu e-posti aadressi';
        $strings['ResourceColor'] = 'Ressursi värv';
        $strings['DateTime'] = 'Kuupäev ja aeg';
        $strings['AutoReleaseNotification'] = 'Vabastatakse automaatselt, kui sisseregistreerimist ei tehta %s minuti jooksul';
        $strings['RequiresCheckInNotification'] = 'Nõuab sisse-/väljaregistreerimist';
        $strings['NoCheckInRequiredNotification'] = 'Ei nõua sisse-/väljaregistreerimist';
        $strings['RequiresApproval'] = 'Vajab kinnitamist';
        $strings['CheckingIn'] = 'Sisseregistreerimine';
        $strings['CheckingOut'] = 'Väljaregistreerimine';
        $strings['CheckIn'] = 'Registreeri sisse';
        $strings['CheckOut'] = 'Registreeri välja';
        $strings['ReleasedIn'] = 'Vabastatakse';
        $strings['CheckedInSuccess'] = 'Oled sisse registreeritud';
        $strings['CheckedOutSuccess'] = 'Oled välja registreeritud';
        $strings['CheckInFailed'] = 'Sisseregistreerimine ebaõnnestus';
        $strings['CheckOutFailed'] = 'Väljaregistreerimine ebaõnnestus';
        $strings['CheckInTime'] = 'Sisseregistreerimise aeg';
        $strings['CheckOutTime'] = 'Väljaregistreerimise aeg';
        $strings['OriginalEndDate'] = 'Algne lõpuaeg';
        $strings['SpecificDates'] = 'Näita kindlaid kuupäevi';
        $strings['Users'] = 'Kasutajad';
        $strings['Guest'] = 'Külaline';
        $strings['ResourceDisplayPrompt'] = 'Kuvatav ressurss';
        $strings['Credits'] = 'Krediidipunktid';
        $strings['AvailableCredits'] = 'Saadaolevad krediidipunktid';
        $strings['CreditUsagePerSlot'] = 'Vajab %s krediidipunkti ajapulga kohta (tavaperiood)';
        $strings['PeakCreditUsagePerSlot'] = 'Vajab %s krediidipunkti ajapulga kohta (tipptund)';
        $strings['CreditsRule'] = 'Sul ei ole piisavalt krediidipunkte. Vajalik: %s, kontol olemas: %s';
        $strings['PeakTimes'] = 'Tipptunnid';
        $strings['AllYear'] = 'Terve aasta';
        $strings['MoreOptions'] = 'Rohkem valikuid';
        $strings['SendAsEmail'] = 'Saada e-kirjana';
        $strings['UsersInGroups'] = 'Kasutajad gruppides';
        $strings['UsersWithAccessToResources'] = 'Ressurssidele juurdepääsuga kasutajad';
        $strings['AnnouncementSubject'] = 'Kasutaja %s postitas uue teadaande';
        $strings['AnnouncementEmailNotice'] = 'kasutajatele saadetakse see teadaanne e-postiga';
        $strings['Day'] = 'Päev';
        $strings['NotifyWhenAvailable'] = 'Teavita mind saadavusest';
        $strings['AddingToWaitlist'] = 'Sinu lisamine ootelisti';
        $strings['WaitlistRequestAdded'] = 'Sind teavitatakse, kui see aeg muutub kättesaadavaks';
        $strings['PrintQRCode'] = 'Prindi QR-kood';
        $strings['FindATime'] = 'Leia sobiv aeg';
        $strings['AnyResource'] = 'Mistahes ressurss';
        $strings['ThisWeek'] = 'Sellel nädalal';
        $strings['Hours'] = 'Tundi';
        $strings['Minutes'] = 'Minutit';
        $strings['ImportICS'] = 'Impordi ICS-failist';
        $strings['ImportQuartzy'] = 'Impordi Quartzyst';
        $strings['OnlyIcs'] = 'Üles saab laadida ainult *.ics faile.';
        $strings['IcsLocationsAsResources'] = 'Asukohad imporditakse ressurssidena.';
        $strings['IcsMissingOrganizer'] = 'Sündmustel, millel puudub korraldaja, määratakse omanikuks praegune kasutaja.';
        $strings['IcsWarning'] = 'Broneerimisreegleid ei rakendata – võimalikud on konfliktid, dublikaadid jne.';
        $strings['BlackoutAroundConflicts'] = 'Loo broneerimiskeeld (blackout) ümber konfliktsete broneeringute';
        $strings['DuplicateReservation'] = 'Klooni/Dubleeri';
        $strings['UnavailableNow'] = 'Hetkel pole saadaval';
        $strings['ReserveLater'] = 'Broneeri hiljem';
        $strings['CollectedFor'] = 'Kogutud järgneva jaoks';
        $strings['IncludeDeleted'] = 'Kaasa kustutatud broneeringud';
        $strings['Deleted'] = 'Kustutatud';
        $strings['Back'] = 'Tagasi';
        $strings['Forward'] = 'Edasi';
        $strings['DateRange'] = 'Kuupäevavahemik';
        $strings['Copy'] = 'Kopeeri';
        $strings['Detect'] = 'Tuvasta';
        $strings['Autofill'] = 'Automaatne täitmine';
        $strings['NameOrEmail'] = 'nimi või e-post';
        $strings['ImportResources'] = 'Impordi ressursid';
        $strings['ExportResources'] = 'Ekspordi ressursid';
        $strings['ResourceImportInstructions'] = '<ul><li>Fail peab olema CSV-vormingus ja UTF-8 kodeeringus.</li><li>Nimi on kohustuslik väli. Teiste väljade tühjaks jätmisel rakendatakse vaikimisi väärtused.</li><li>Oleku valikud on \'Available\' (Saadaval), \'Unavailable\' (Pole saadaval) ja \'Hidden\' (Peidetud).</li><li>Värv peab olema kuusteistkümnendväärtuses (HEX), nt #ffffff.</li><li>Automaatse määramise ja kinnitamise veergude väärtus saab olla true või false.</li><li>Atribuutide kehtivust ei kontrollita.</li><li>Mitmik-ressursigrupid eralda komadega.</li><li>Kestusi saab määrata vormingus #d#h#m või HH:mm (1d3h30m või 27:30 tähistab 1 päev, 3 tundi ja 30 minutit).</li><li>Kasuta näitena kaasasolevat malli.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Fail peab olema CSV-vormingus ja UTF-8 kodeeringus.</li><li>E-post, ressursside nimed, algus ja lõpp on kohustuslikud väljad.</li><li>Algus ja lõpp vajavad täielikku kuupäeva ja kellaaega. Soovitatav vorming on YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Reegleid, konflikte ja kehtivaid ajapulki ei kontrollita.</li><li>Teavitusi ei saadeta.</li><li>Atribuutide kehtivust ei kontrollita.</li><li>Mitmik-ressursside nimed eralda komadega.</li><li>Kasuta näitena kaasasolevat malli.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Automaatse vabastamise minutid';
        $strings['CreditsPeak'] = 'Krediidipunktid (tipptund)';
        $strings['CreditsOffPeak'] = 'Krediidipunktid (tavaperiood)';
        $strings['ResourceMinLengthCsv'] = 'Broneeringu miinimumkestus';
        $strings['ResourceMaxLengthCsv'] = 'Broneeringu maksimumkestus';
        $strings['ResourceBufferTimeCsv'] = 'Puhveraeg (puhver)';
        $strings['ResourceMinNoticeAddCsv'] = 'Broneeringu lisamise miinimum-eelteavitusaeg';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Broneeringu muutmise miinimum-eelteavitusaeg';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Broneeringu kustutamise miinimum-eelteavitusaeg';
        $strings['ResourceMaxNoticeCsv'] = 'Broneeringu maksimaalne lõpp-aeg tulevikus';
        $strings['Export'] = 'Ekspordi';
        $strings['DeleteMultipleUserWarning'] = 'Nende kasutajate kustutamisel eemaldatakse kõik nende praegused, tulevased ja ajaloolised broneeringud. E-kirju ei saadeta.';
        $strings['DeleteMultipleReservationsWarning'] = 'E-kirju ei saadeta.';
        $strings['ErrorMovingReservation'] = 'Viga broneeringu teisaldamisel';
        $strings['SelectUser'] = 'Vali kasutaja';
        $strings['InviteUsers'] = 'Kutsu kasutajaid';
        $strings['InviteUsersLabel'] = 'Sisesta kutsutavate inimeste e-posti aadressid';
        $strings['ApplyToCurrentUsers'] = 'Rakenda praegustele kasutajatele';
        $strings['ReasonText'] = 'Põhjenduse tekst';
        $strings['NoAvailableMatchingTimes'] = 'Sinu otsingule vastavad vabad ajad puuduvad';
        $strings['Schedules'] = 'Ajakavad';
        $strings['NotifyUser'] = 'Teavita kasutajat';
        $strings['UpdateUsersOnImport'] = 'Uuenda olemasolevat kasutajat, kui e-post on juba olemas';
        $strings['UpdateResourcesOnImport'] = 'Uuenda olemasolevaid ressursse, kui nimi on juba olemas';
        $strings['Reject'] = 'Lükka tagasi';
        $strings['CheckingAvailability'] = 'Saadavuse kontrollimine';
        $strings['CreditPurchaseNotEnabled'] = 'Sa ei ole krediidipunktide ostmist sisse lülitanud';
        $strings['CreditsEachCost1'] = 'Iga';
        $strings['CreditsEachCost2'] = 'krediidipunkti maksumus on';
        $strings['CreditsCount'] = 'Krediidipunktide arv';
        $strings['CreditsCost'] = 'Maksumus';
        $strings['Currency'] = 'Valuuta';
        $strings['PayPalClientId'] = 'Kliendi ID (Client ID)';
        $strings['PayPalSecret'] = 'Salajane võti (Secret)';
        $strings['PayPalEnvironment'] = 'Keskkond';
        $strings['Sandbox'] = 'Testkeskkond (Sandbox)';
        $strings['Live'] = 'Töökeskkond (Live)';
        $strings['StripePublishableKey'] = 'Avalik võti (Publishable key)';
        $strings['StripeSecretKey'] = 'Salajane võti (Secret key)';
        $strings['CreditsUpdated'] = 'Krediidipunkti maksumus on uuendatud';
        $strings['GatewaysUpdated'] = 'Maksekanalid on uuendatud';
        $strings['PurchaseSummary'] = 'Ostu kokkuvõte';
        $strings['EachCreditCosts'] = 'Iga krediidipunkt maksab';
        $strings['Checkout'] = 'Maksma';
        $strings['Quantity'] = 'Kogus';
        $strings['CreditPurchase'] = 'Krediidipunktide ost';
        $strings['EmptyCart'] = 'Sinu ostukorv on tühi.';
        $strings['BuyCredits'] = 'Osta krediidipunkte';
        $strings['CreditsPurchased'] = 'krediidipunkti ostetud.';
        $strings['ViewYourCredits'] = 'Vaata oma krediidipunkte';
        $strings['TryAgain'] = 'Proovi uuesti';
        $strings['PurchaseFailed'] = 'Makse töötlemisel tekkis viga.';
        $strings['NoteCreditsPurchased'] = 'Ostetud krediidipunktid';
        $strings['CreditsUpdatedLog'] = 'Krediidipunkte uuendas %s';
        $strings['ReservationCreatedLog'] = 'Broneering loodud. Viitenumber %s';
        $strings['ReservationUpdatedLog'] = 'Broneering uuendatud. Viitenumber %s';
        $strings['ReservationDeletedLog'] = 'Broneering kustutatud. Viitenumber %s';
        $strings['BuyMoreCredits'] = 'Osta lisakrediiti';
        $strings['Transactions'] = 'Tehingud';
        $strings['Cost'] = 'Maksumus';
        $strings['PaymentGateways'] = 'Maksekanalid';
        $strings['CreditHistory'] = 'Krediidiajalugu';
        $strings['TransactionHistory'] = 'Tehingute ajalugu';
        $strings['Date'] = 'Kuupäev';
        $strings['Note'] = 'Märkus';
        $strings['CreditsBefore'] = 'Krediit enne';
        $strings['CreditsAfter'] = 'Krediit pärast';
        $strings['TransactionFee'] = 'Tehingutasu';
        $strings['InvoiceNumber'] = 'Arve number';
        $strings['TransactionId'] = 'Tehingu ID';
        $strings['Gateway'] = 'Maksekanal';
        $strings['GatewayTransactionDate'] = 'Maksekanali tehingu kuupäev';
        $strings['Refund'] = 'Tagasimakse';
        $strings['IssueRefund'] = 'Tee tagasimakse';
        $strings['RefundIssued'] = 'Tagasimakse edukalt teostatud';
        $strings['RefundAmount'] = 'Tagasimakse summa';
        $strings['AmountRefunded'] = 'Tagastatud';
        $strings['FullyRefunded'] = 'Täielikult tagastatud';
        $strings['YourCredits'] = 'Sinu krediidipunktid';
        $strings['PayWithCard'] = 'Maksa kaardiga';
        $strings['or'] = 'või';
        $strings['CreditsRequired'] = 'Vajalikud krediidipunktid';
        $strings['AddToGoogleCalendar'] = 'Lisa Google\'i kalendrisse';
        $strings['Image'] = 'Pilt';
        $strings['ChooseOrDropFile'] = 'Vali fail või lohista see siia';
        $strings['SlackBookResource'] = 'Broneeri %s kohe';
        $strings['SlackBookNow'] = 'Broneeri kohe';
        $strings['SlackNotFound'] = 'Selle nimega ressurssi ei leitud. Uue broneeringu alustamiseks vajuta "Broneeri kohe".';
        $strings['AutomaticallyAddToGroup'] = 'Lisa uued kasutajad automaatselt sellesse meeskonda/gruppi';
        $strings['GroupAutomaticallyAdd'] = 'Automaatne lisamine';
        $strings['TermsOfService'] = 'Kasutustingimused';
        $strings['EnterTermsManually'] = 'Sisesta tingimused käsitsi';
        $strings['LinkToTerms'] = 'Link tingimustele';
        $strings['UploadTerms'] = 'Laadi tingimused üles';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Nõua kasutustingimustega nõustumist';
        $strings['UponReservation'] = 'Broneerimisel';
        $strings['UponRegistration'] = 'Registreerumisel';
        $strings['ViewTerms'] = 'Vaata kasutustingimusi';
        $strings['IAccept'] = 'Nõustun';
        $strings['TheTermsOfService'] = 'kasutustingimustega';
        $strings['DisplayPage'] = 'Kuvatav leht';
        $strings['AvailableAllYear'] = 'Aastaringselt saadaval';
        $strings['Availability'] = 'Saadavus';
        $strings['AvailableBetween'] = 'Saadaval vahemikus';
        $strings['ConcurrentYes'] = 'Ressursse saab broneerida mitu inimest korraga';
        $strings['ConcurrentNo'] = 'Ressursse ei saa broneerida mitu inimest korraga';
        $strings['ScheduleAvailabilityEarly'] = ' See ajakava ei ole veel saadaval. See on saadaval';
        $strings['ScheduleAvailabilityLate'] = 'See ajakava ei ole enam saadaval. See oli saadaval';
        $strings['ResourceImages'] = 'Ressursi pildid';
        $strings['FullAccess'] = 'Täielik juurdepääs';
        $strings['ViewOnly'] = 'Ainult vaatamine';
        $strings['Purge'] = 'Puhasta / Kustuta lõplikult';
        $strings['UsersWillBeDeleted'] = 'kasutajat kustutatakse';
        $strings['BlackoutsWillBeDeleted'] = 'katkestusaega kustutatakse';
        $strings['ReservationsWillBePurged'] = 'broneeringut puhastatakse lõplikult';
        $strings['ReservationsWillBeDeleted'] = 'broneeringut kustutatakse';
        $strings['PermanentlyDeleteUsers'] = 'Kustuta lõplikult kasutajad, kes pole sisse loginud alates';
        $strings['DeleteBlackoutsBefore'] = 'Kustuta katkestusajad enne';
        $strings['DeletedReservations'] = 'Kustutatud broneeringud';
        $strings['DeleteReservationsBefore'] = 'Kustuta broneeringud enne';
        $strings['PermanentlyPurgeAllDeletedReservations'] = 'Puhasta (kustuta lõplikult) kõik kustutatud broneeringud';
        $strings['SwitchToACustomLayout'] = 'Lülitu kohandatud paigutusele';
        $strings['SwitchToAStandardLayout'] = 'Lülitu tavapärasele paigutusele';
        $strings['ThisScheduleUsesACustomLayout'] = 'See ajakava kasutab kohandatud paigutust';
        $strings['ThisScheduleUsesAStandardLayout'] = 'See ajakava kasutab tavapärast paigutust';
        $strings['SwitchLayoutWarning'] = 'Kas oled kindel, et soovid paigutuse tüüpi muuta? See eemaldab kõik olemasolevad ajavahemikud.';
        $strings['DeleteThisTimeSlot'] = 'Kas kustutada see ajavahemik?';
        $strings['Refresh'] = 'Värskenda';
        $strings['ViewReservation'] = 'Vaata broneeringut';
        $strings['PublicId'] = 'Avalik ID';
        $strings['Public'] = 'Avalik';
        $strings['AtomFeedTitle'] = '%s broneeringud';
        $strings['DefaultStyle'] = 'Vaikimisi stiil';
        $strings['Standard'] = 'Standardne';
        $strings['Wide'] = 'Lai';
        $strings['Tall'] = 'Kõrge';
        $strings['EmailTemplate'] = 'E-kirja mall';
        $strings['SelectEmailTemplate'] = 'Vali e-kirja mall';
        $strings['ReloadOriginalContents'] = 'Laadi uuesti algne sisu';
        $strings['UpdateEmailTemplateSuccess'] = 'E-kirja mall uuendatud';
        $strings['UpdateEmailTemplateFailure'] = 'E-kirja malli uuendamine ebaõnnestus. Kontrolli, et kaust oleks kirjutatav.';
        $strings['BulkResourceDelete'] = 'Ressursside hulgikustutamine';
        $strings['NewVersion'] = 'Uus versioon!';
        $strings['WhatsNew'] = 'Mis on uut?';
        $strings['OnlyViewedCalendar'] = 'Seda ajakava saab vaadata ainult kalendrivaates';
        $strings['Grid'] = 'Ruudustik';
        $strings['List'] = 'Nimekiri';
        $strings['NoReservationsFound'] = 'Broneeringuid ei leitud';
        $strings['EmailReservation'] = 'Saada broneering e-postiga';
        $strings['AdHocMeeting'] = 'Isetekkeline (Ad hoc) kohtumine';
        $strings['NextReservation'] = 'Järgmine broneering';
        $strings['CurrentReservation'] = 'Praegune broneering';
        $strings['MissedCheckin'] = 'Maha magatud sisseregistreerimine';
        $strings['MissedCheckout'] = 'Maha magatud väljaregistreerimine';
        $strings['Utilization'] = 'Kasutatvus / Hõlmatus';
        $strings['SpecificTime'] = 'Täpne kellaaeg';
        $strings['ReservationSeriesEndingPreference'] = 'Kui minu korduva broneeringu sari on lõppemas';
        $strings['NotAttending'] = 'Ei osale';
        $strings['ViewAvailability'] = 'Vaata saadavust';
        $strings['ReservationDetails'] = 'Broneeringu detailid';
        $strings['StartTime'] = 'Algusaeg';
        $strings['EndTime'] = 'Lõpuaeg';
        $strings['New'] = 'Uus';
        $strings['Updated'] = 'Uuendatud';
        $strings['Custom'] = 'Kohandatud';
        $strings['AddDate'] = 'Lisa kuupäev';
        $strings['RepeatOn'] = 'Korda päevadel';
        $strings['ScheduleConcurrentMaximum'] = 'Samaaegselt saab broneerida kokku kuni <b>%s</b> ressurssi';
        $strings['ScheduleConcurrentMaximumNone'] = 'Samaaegselt broneeritavate ressursside arvule piirangut pole';
        $strings['ScheduleMaximumConcurrent'] = 'Maksimaalne samaaegselt broneeritavate ressursside arv';
        $strings['ScheduleMaximumConcurrentNote'] = 'Määramisel piiratakse selle ajakava raames samaaegselt broneeritavate ressursside koguarvu.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Iga broneering on piiratud maksimaalselt <b>%s</b> ressursiga';
        $strings['ScheduleResourcesPerReservationNone'] = 'Ressursside arvule ühe broneeringu kohta piirangut pole';
        $strings['ScheduleResourcesPerReservation'] = 'Maksimaalne ressursside arv ühe broneeringu kohta';
        $strings['ResourceConcurrentReservations'] = 'Luba %s samaaegset broneeringut';
        $strings['ResourceConcurrentReservationsNone'] = 'Ära luba samaaegseid broneeringuid';
        $strings['AllowConcurrentReservations'] = 'Luba samaaegsed broneeringud';
        $strings['ResourceDisplayInstructions'] = 'Ühtegi ressurssi pole valitud. Ressursi kuvamise URL-i leiad jaotisest Haldus -> Ressursid. Ressurss peab olema avalikult kättesaadav.';
        $strings['Owner'] = 'Omanik';
        $strings['MaximumConcurrentReservations'] = 'Maksimaalne samaaegsete broneeringute arv';
        $strings['NotifyUsers'] = 'Teavita kasutajaid';
        $strings['Message'] = 'Sõnum';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Igaüks, kellel on broneering järgmise';
        $strings['ChangeResourceStatus'] = 'Muuda ressursi olekut';
        $strings['UpdateGroupsOnImport'] = 'Uuenda olemasolevat gruppi, kui nimi kattub';
        $strings['GroupsImportInstructions'] = '<ul><li>Fail peab olema CSV-vormingus.</li><li>Nimi on kohustuslik väli.</li><li>Liikmete nimekirjad peavad olema e-posti aadresside komaeraldusloendid.</li><li>Tühjad liikmete nimekirjad gruppide uuendamisel jätavad liikmed muutmata.</li><li>Õiguste nimekirjad peavad olema ressursside nimede komaeraldusloendid.</li><li>Tühjad õiguste nimekirjad gruppide uuendamisel jätavad õigused muutmata.</li><li>Kasuta näitena kaasasolevat malli.</li></ul>';
        $strings['PhoneRequired'] = 'Telefoninumber on nõutud';
        $strings['OrganizationRequired'] = 'Organisatsioon on nõutud';
        $strings['PositionRequired'] = 'Ametikoht on nõutud';
        $strings['GroupMembership'] = 'Grupikuuluvus';
        $strings['AvailableGroups'] = 'Saadaolevad grupid';
        $strings['CheckingAvailabilityError'] = 'Ressursside saadavust ei saa pärida – liiga palju ressursse';
        $strings['ScanToSchedule'] = 'Skanni broneerimiseks';
        $strings['MaintenanceNotice'] = 'Hetkel teostatakse hooldustöid. Oleme peagi tagasi.';
        $strings['MoreResourceActions'] = 'Veel ressursitoiminguid';
        // End Strings

        // Errors
        $strings['LoginError'] = 'Kasutajanimi või parool oli vale';
        $strings['LdapConnectionErrorMessage'] = 'LDAP-serveriga ei õnnestunud ühendust luua. Palun võta ühendust administraatoriga.';
        $strings['LdapDependencyMissingMessage'] = 'LDAP-autentimine pole saadaval, kuna pear/net_ldap2 on puudu. Paigalda see käsuga: composer require pear/net_ldap2';
        $strings['ReservationFailed'] = 'Sinu broneeringut ei saanud teostada';
        $strings['MinNoticeError'] = 'See broneering vajab ette teatamist. Varaseim võimalik broneeringu aeg on %s.';
        $strings['MinNoticeErrorUpdate'] = 'Selle broneeringu muutmine vajab ette teatamist. Broneeringuid enne %s ei ole lubatud muuta.';
        $strings['MinNoticeErrorDelete'] = 'Selle broneeringu kustutamine vajab ette teatamist. Broneeringuid enne %s ei ole lubatud kustutada.';
        $strings['MaxNoticeError'] = 'Seda broneeringut ei saa nii kaugele tulevikku teha. Hiliseim võimalik broneeringu aeg on %s.';
        $strings['MinDurationError'] = 'Broneeringu minimaalne kestus peab olema vähemalt %s.';
        $strings['MaxDurationError'] = 'Broneeringu maksimaalne kestus ei tohi ületada %s.';
        $strings['ConflictingAccessoryDates'] = 'Järgnevaid lisatarvikuid ei ole piisavas koguses saadaval:';
        $strings['NoResourcePermission'] = 'Sul puudub õigus ühele või mitmele valitud ressurssidest juurde pääseda.';
        $strings['ConflictingReservationDates'] = 'Järgmistel kuupäevadel on konfliktseid broneeringuid:';
        $strings['InstancesOverlapRule'] = 'Mõned korduva broneeringu sarja osad kattuvad omavahel:';
        $strings['StartDateBeforeEndDateRule'] = 'Algusaeg peab olema enne lõpuaega.';
        $strings['RecurringWithoutTerminationRule'] = 'Korduvate broneerimiskeeldude (blackouts) puhul on lõppkuupäev kohustuslik.';
        $strings['StartIsInPast'] = 'Algusaeg ei saa olla minevikus.';
        $strings['EmailDisabled'] = 'Administraator on e-posti teavitused välja lülitanud.';
        $strings['ValidLayoutRequired'] = 'Ajavahemikud peavad katma 24 tundi ööpäevas, alguse ja lõpuga kell 00:00.';
        $strings['CustomAttributeErrors'] = 'Sinu sisestatud lisaväljadega tekkisid järgmised probleemid:';
        $strings['CustomAttributeRequired'] = '%s on kohustuslik väli.';
        $strings['CustomAttributeInvalid'] = 'Välja %s sisestatud väärtus on vigane.';
        $strings['AttachmentLoadingError'] = 'Vabandust, soovitud faili laadimisel tekkis viga.';
        $strings['InvalidAttachmentExtension'] = 'Üles saab laadida ainult järgmise tüübiga faile: %s';
        $strings['InvalidStartSlot'] = 'Soovitud alguskuupäev ja -kellaaeg ei ole kehtivad.';
        $strings['InvalidEndSlot'] = 'Soovitud lõppkuupäev ja -kellaaeg ei ole kehtivad.';
        $strings['MaxParticipantsError'] = 'Ressurss %s mahutab maksimaalselt %s osalejat.';
        $strings['ReservationCriticalError'] = 'Broneeringu salvestamisel tekkis kriitiline viga. Probleemi püsitudes võta ühendust süsteemiadministraatoriga.';
        $strings['InvalidStartReminderTime'] = 'Alguse meeldetuletuse aeg ei ole kehtiv.';
        $strings['InvalidEndReminderTime'] = 'Lõpu meeldetuletuse aeg ei ole kehtiv.';
        $strings['QuotaExceeded'] = 'Broneeringute limiit (kvoot) on ületatud.';
        $strings['MultiDayRule'] = 'Ressurss %s ei luba üle mitme päeva ulatuvaid broneeringuid.';
        $strings['InvalidReservationData'] = 'Sinu broneeringutaotluses esines probleeme.';
        $strings['PasswordError'] = 'Parool peab sisaldama vähemalt %s tähte ja vähemalt %s numbrit.';
        $strings['PasswordErrorRequirements'] = 'Parool peab sisaldama kombinatsiooni vähemalt %s suur- ja väiketähest ning %s numbrist.';
        $strings['NoReservationAccess'] = 'Sul ei ole õigust seda broneeringut muuta.';
        $strings['PasswordControlledExternallyError'] = 'Sinu parooli hallatakse välises süsteemis ja seda ei saa siin uuendada.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Lisatarvikut %s saab broneerida ainult koos ressurssidega %s.';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Pead broneerima vähemalt %s lisatarvikut %s.';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Sa ei saa broneerida rohkem kui %s lisatarvikut %s.';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Lisatarvikut \'%s\' ei saa valida koos soovitud ressurssidega.';
        $strings['NoResources'] = 'Sa ei ole lisanud ühtegi ressurssi.';
        $strings['ParticipationNotAllowed'] = 'Sul ei ole lubatud selle broneeringuga liituda.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Sellele broneeringule ei saa sisse registreeruda (check-in).';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Sellelt broneeringult ei saa välja registreeruda (check-out).';
        $strings['InvalidEmailDomain'] = 'Selle e-posti aadressi doomegaan ei ole lubatud nimekirjas.';
        $strings['TermsOfServiceError'] = 'Pead nõustuma kasutustingimustega.';
        $strings['UserNotFound'] = 'Seda kasutajat ei leitud';
        $strings['ScheduleAvailabilityError'] = 'See ajakava on saadaval vahemikus %s kuni %s';
        $strings['ReservationNotFoundError'] = 'Broneeringut ei leitud';
        $strings['ReservationNotAvailable'] = 'Broneering ei ole saadaval';
        $strings['TitleRequiredRule'] = 'Broneeringu pealkiri on kohustuslik';
        $strings['DescriptionRequiredRule'] = 'Broneeringu kirjeldus on kohustuslik';
        $strings['WhatCanThisGroupManage'] = 'Mida see grupp saab hallata?';
        $strings['ReservationParticipationActivityPreference'] = 'Kui keegi liitub minu broneeringuga või lahkub sellest';
        $strings['RegisteredAccountRequired'] = 'Arealiseerida / broneerida saavad ainult registreeritud kasutajad';
        $strings['InvalidNumberOfResourcesError'] = 'Maksimaalne ressursside arv ühes broneeringus on %s';
        $strings['ScheduleTotalReservationsError'] = 'See ajakava lubab samaaegselt broneerida ainult %s ressurssi. Antud broneering rikuks seda piirangut järgmistel kuupäevadel:';
        $strings['SelfRegistrationDisabled'] = 'Registreerimata kasutajad ja iseregistreerumine on välja lülitatud. Konto loomiseks võta ühendust administraatoriga.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Loo broneering';
        $strings['EditReservation'] = 'Uuenda broneeringut';
        $strings['LogIn'] = 'Logi sisse';
        $strings['ManageReservations'] = 'Broneeringud';
        $strings['AwaitingActivation'] = 'Ootab aktiveerimist';
        $strings['PendingApproval'] = 'Ootab kinnitamist';
        $strings['ManageSchedules'] = 'Ajakavad';
        $strings['ManageResources'] = 'Ressursid';
        $strings['ManageAccessories'] = 'Lisatarvikud';
        $strings['ManageUsers'] = 'Kasutajad';
        $strings['ManageGroups'] = 'Grupid';
        $strings['ManageQuotas'] = 'Kvoodid';
        $strings['ManageBlackouts'] = 'Broneerimiskeelud';
        $strings['MyDashboard'] = 'Minu töölaud';
        $strings['ServerSettings'] = 'Serveri seaded';
        $strings['Dashboard'] = 'Töölaud';
        $strings['Help'] = 'Abi';
        $strings['Administration'] = 'Haldus';
        $strings['About'] = 'Teave';
        $strings['Bookings'] = 'Broneeringud';
        $strings['Schedule'] = 'Ajakava';
        $strings['Account'] = 'Konto';
        $strings['EditProfile'] = 'Muuda minu profiili';
        $strings['FindAnOpening'] = 'Otsi vaba aega';
        $strings['OpenInvitations'] = 'Aktiivsed kutsed';
        $strings['ResourceCalendar'] = 'Ressursi kalender';
        $strings['Reservation'] = 'Uus broneering';
        $strings['Install'] = 'Paigaldamine';
        $strings['ChangePassword'] = 'Muuda parooli';
        $strings['MyAccount'] = 'Minu konto';
        $strings['Profile'] = 'Profiil';
        $strings['ApplicationManagement'] = 'Rakenduse haldus';
        $strings['ForgotPassword'] = 'Unustasid parooli';
        $strings['NotificationPreferences'] = 'Teavituste eelistused';
        $strings['ManageAnnouncements'] = 'Tadaanded';
        $strings['Responsibilities'] = 'Vastutusalad';
        $strings['GroupReservations'] = 'Grupi broneeringud';
        $strings['ResourceReservations'] = 'Ressursi broneeringud';
        $strings['Customization'] = 'Kohandamine';
        $strings['Attributes'] = 'Atribuudid / Lisaväljad';
        $strings['AccountActivation'] = 'Konto aktiveerimine';
        $strings['ScheduleReservations'] = 'Ajakava broneeringud';
        $strings['Reports'] = 'Aruanded';
        $strings['GenerateReport'] = 'Loo uus aruanne';
        $strings['MySavedReports'] = 'Minu salvestatud aruanded';
        $strings['CommonReports'] = 'Üldised aruanded';
        $strings['ViewDay'] = 'Vaata päeva';
        $strings['Group'] = 'Grupp';
        $strings['ManageConfiguration'] = 'Rakenduse seadistus';
        $strings['LookAndFeel'] = 'Välimus ja teema';
        $strings['ManageResourceGroups'] = 'Ressursside grupid';
        $strings['ManageResourceTypes'] = 'Ressursside tüübid';
        $strings['ManageResourceStatus'] = 'Ressursside olekud';
        $strings['ReservationColors'] = 'Broneeringute värvid';
        $strings['SearchReservations'] = 'Otsi broneeringuid';
        $strings['ManagePayments'] = 'Maksed';
        $strings['ViewCalendar'] = 'Vaata kalendrit';
        $strings['DataCleanup'] = 'Andmepuhastus';
        $strings['ManageEmailTemplates'] = 'Halda e-kirjade malle';
        $strings['CheckResources'] = 'Kontrolli ressursse';
        $strings['CheckSchedules'] = 'Kontrolli ajakavu';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'P';
        $strings['DayMondaySingle'] = 'E';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'K';
        $strings['DayThursdaySingle'] = 'N';
        $strings['DayFridaySingle'] = 'R';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Pü';
        $strings['DayMondayAbbr'] = 'Es';
        $strings['DayTuesdayAbbr'] = 'Te';
        $strings['DayWednesdayAbbr'] = 'Ko';
        $strings['DayThursdayAbbr'] = 'Ne';
        $strings['DayFridayAbbr'] = 'Re';
        $strings['DaySaturdayAbbr'] = 'La';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Sinu broneering on kinnitatud';
        $strings['ReservationCreatedSubject'] = 'Sinu broneering on loodud';
        $strings['ReservationUpdatedSubject'] = 'Sinu broneeringut on muudetud';
        $strings['ReservationDeletedSubject'] = 'Sinu broneering on tühistatud';
        $strings['ReservationCreatedAdminSubject'] = 'Teavitus: Loodi uus broneering';
        $strings['ReservationUpdatedAdminSubject'] = 'Teavitus: Broneeringut on muudetud';
        $strings['ReservationDeleteAdminSubject'] = 'Teavitus: Broneering on tühistatud';
        $strings['ReservationApprovalAdminSubject'] = 'Teavitus: Broneering vajab sinu kinnitust';
        $strings['ParticipantAddedSubject'] = 'Broneeringus osalemise teavitus';
        $strings['ParticipantDeletedSubject'] = 'Broneering eemaldatud';
        $strings['InviteeAddedSubject'] = 'Broneeringu kutse';
        $strings['ResetPasswordRequest'] = 'Parooli lähtestamise taotlus';
        $strings['ActivateYourAccount'] = 'Palun aktiveeri oma konto';
        $strings['ReportSubject'] = 'Sinu tellitud aruanne (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Ressurssi %s puudutav broneering algab peagi';
        $strings['ReservationEndingSoonSubject'] = 'Ressurssi %s puudutav broneering lõpeb peagi';
        $strings['UserAdded'] = 'Uus kasutaja on lisatud';
        $strings['UserDeleted'] = 'Kasutaja %s konto kustutati kasutaja %s poolt';
        $strings['GuestAccountCreatedSubject'] = 'Sinu %s konto andmed';
        $strings['AccountCreatedSubject'] = 'Sinu %s konto andmed';
        $strings['InviteUserSubject'] = '%s kutsus sind liituma keskkonnaga %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Broneering kinnitati ressursile %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Loodi broneering ressursile %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Broneeringut muudeti ressursil %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Broneering eemaldati ressursilt %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Teavitus: Loodi broneering ressursile %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Teavitus: Muudeti broneeringut ressursil %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Teavitus: Eemaldati broneering ressursilt %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Teavitus: Broneering ressursile %s vajab sinu kinnitust';
        $strings['ParticipantAddedSubjectWithResource'] = '%s lisas sind broneeringule ressursil %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s muutis broneeringut ressursil %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s eemaldas broneeringu ressursilt %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s kutsus sind broneeringule ressursil %s';
        $strings['MissedCheckinEmailSubject'] = 'Sisseregistreerimine jäi vahele ressursil %s';
        $strings['ReservationShareSubject'] = '%s jagas sinuga broneeringut ressursil %s';
        $strings['ReservationSeriesEndingSubject'] = 'Korduv broneeringute sari ressursil %s lõpeb %s';
        $strings['ReservationParticipantAccept'] = '%s võttis vastu sinu kutse broneeringule ressursil %s kuupäeval %s';
        $strings['ReservationParticipantDecline'] = '%s lükkas tagasi sinu kutse broneeringule ressursil %s kuupäeval %s';
        $strings['ReservationParticipantJoin'] = '%s liitus sinu broneeringuga ressursil %s kuupäeval %s';
        $strings['ReservationAvailableSubject'] = 'Ressurss %s on saadaval %s';
        $strings['ResourceStatusChangedSubject'] = 'Ressurssi %s saadavus muutus';
        // End Email Subjects

        //Past Reservations
        $strings['NoPastReservations'] = 'Sul ei ole möödunud broneeringuid';
        $strings['PastReservations'] = 'Möödunud broneeringud';
        $strings['AllNoPastReservations'] = 'Viimase %s päeva jooksul ei ole ühtegi möödunud broneeringut';
        $strings['AllPastReservations'] = 'Kõik möödunud broneeringud';
        $strings['Yesterday'] = 'Eile';
        $strings['EarlierThisWeek'] = 'Varem sel nädalal';
        $strings['PreviousWeek'] = 'Eelmisel nädalal';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['GroupUpcomingReservations'] = 'Minu grupi (gruppide) tulevased broneeringud';
        $strings['NoGroupUpcomingReservations'] = 'Sinu grupil ei ole tulevasi broneeringuid';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Facebookiga sisselogimisel tekkis viga. Palun proovi uuesti.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Sul ei ole kinnitamist ootavaid broneeringuid';
        $strings['PendingApprovalReservations'] = 'Kinnitamist ootavad broneeringud';
        $strings['LaterThisMonth'] = 'Hiljem sel kuul';
        $strings['LaterThisYear'] = 'Hiljem sel aastal';
        $strings['Other'] = 'Muu';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Puuduvad välja registreerimata broneeringud';
        $strings['MissingCheckOutReservations'] = 'Välja registreerimata broneeringud';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Ei saa broneeringu üksikasju vaadata, kuna sul puuduvad õigused kõigile selles broneeringus olevatele ressurssidele';
        //End Schedule Resource Permissions

        //View Resource
        $strings['Check'] = 'Kontrolli';
        $strings['PermissionType'] = 'Õiguse tüüp';
        $strings['NoResourcesToView'] = 'Saadaolevaid ressursse pole';
        //End View Resource

        //Datatables
        $strings['Info'] = 'Kuvatakse lehekülg _PAGE_ / _PAGES_ (kokku _MAX_ kirjet)';
        $strings['LengthMenu'] = 'Kuva _MENU_ kirjet lehel';
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
        $days['full'] = ['Pühapäev', 'Esmaspäev', 'Teisipäev', 'Kolmapäev', 'Neljapäev', 'Reede', 'Laupäev'];
        // The three letter abbreviation
        $days['abbr'] = ['Pühap', 'Esmas', 'Teisip', 'Kolmap', 'Neljap', 'Reede', 'Laup'];
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
        $months = [];

        /***
         * MONTH NAMES
         * All of these arrays MUST start with January as the first element
         * and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Jaanuar', 'Veebruar', 'Märts', 'Aprill', 'Mai', 'Juuni', 'Juuli', 'August', 'September', 'Oktoober', 'Novembril', 'Detsember'];
        // The three letter month name
        $months['abbr'] = ['Jaan', 'Veeb', 'Märts', 'Apr', 'Mai', 'Juuni', 'Juuli', 'Aug', 'Sept', 'Okt', 'Nov', 'Dets'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'Š', 'Z', 'Ž', 'T', 'U', 'V', 'W', 'Õ', 'Ä', 'Ö', 'Ü', 'X', 'Y'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'et'; 
    }
}
