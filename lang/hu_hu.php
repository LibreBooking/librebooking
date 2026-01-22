<?php

require_once('en_us.php');

class hu_hu extends en_us
{
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
        $dates['dashboard'] = 'D, n/d g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['mobile_reservation_date'] = 'n/j g:i A';
        $dates['general_date_js'] = 'mm/dd/yy';
        $dates['general_time_js'] = 'h:mm tt';
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

        $strings['FirstName'] = 'Keresztnév';
        $strings['LastName'] = 'Vezetkéknév';
        $strings['Timezone'] = 'Időzóna';
        $strings['Edit'] = 'Szerkesztés';
        $strings['Change'] = 'Módosítás';
        $strings['Rename'] = 'Átnevezés';
        $strings['Remove'] = 'Eltávoltítás';
        $strings['Delete'] = 'Törlés';
        $strings['Update'] = 'Frisstés';
        $strings['Cancel'] = 'Mégse';
        $strings['Add'] = 'Hozzáad';
        $strings['Name'] = 'Név';
        $strings['Yes'] = 'Igen';
        $strings['No'] = 'Nem';
        $strings['FirstNameRequired'] = 'A keresztnév szükséges.';
        $strings['LastNameRequired'] = 'A vezetéknév szükséges.';
        $strings['PwMustMatch'] = 'A jelszó megerősítésnek egyeznie kell a jelszóval.';
        $strings['ValidEmailRequired'] = 'Egy érévényes e-mail cím szükséges.';
        $strings['UniqueEmailRequired'] = 'Az e-mail cím már regisztrálva lett.';
        $strings['UniqueUsernameRequired'] = 'A felhasználónév már regisztrálva lett.';
        $strings['UserNameRequired'] = 'Hiányzó felhasználónév.';
        $strings['CaptchaMustMatch'] = 'Captcha szükséges.';
        $strings['Today'] = 'Ma';
        $strings['Week'] = 'Hét';
        $strings['Month'] = 'Hónap';
        $strings['BackToCalendar'] = 'Vissza a naptárhoz';
        $strings['BeginDate'] = 'Kezdés';
        $strings['EndDate'] = 'Befejezés';
        $strings['Username'] = 'Felhasználónév';
        $strings['Password'] = 'Jelszó';
        $strings['PasswordConfirmation'] = 'Jelszó megerősítés';
        $strings['DefaultPage'] = 'Alapértelmezett honlap';
        $strings['MyCalendar'] = 'Naptáram';
        $strings['ScheduleCalendar'] = 'Naptár ütemezés';
        $strings['Registration'] = 'Regisztráció';
        $strings['NoAnnouncements'] = 'Jelenleg nincs bejelentés';
        $strings['Announcements'] = 'Bejelentések';
        $strings['NoUpcomingReservations'] = 'Önnek nincs közelgő foglalása';
        $strings['UpcomingReservations'] = 'Közelgő foglalások';
        $strings['AllNoUpcomingReservations'] = 'Nincs foglalás a következő %s napban';
        $strings['AllUpcomingReservations'] = 'Minden következő foglalás';
        $strings['ShowHide'] = 'Mutat/elrejt';
        $strings['Error'] = 'Hiba';
        $strings['ReturnToPreviousPage'] = 'Visszatérés az előző oldalra';
        $strings['UnknownError'] = 'Ismeretlen hiba';
        $strings['InsufficientPermissionsError'] = 'Nincs megfelelő jogosultsága az eszköz eléréséhez';
        $strings['MissingReservationResourceError'] = 'Eszköz nincs kijelölve';
        $strings['MissingReservationScheduleError'] = 'Beosztás nincs kijelölve';
        $strings['DoesNotRepeat'] = 'Nem ismétlődik';
        $strings['Daily'] = 'Naponta';
        $strings['Weekly'] = 'Hetente';
        $strings['Monthly'] = 'Havonta';
        $strings['Yearly'] = 'Évente';
        $strings['RepeatPrompt'] = 'Ismétlés';
        $strings['hours'] = 'órák';
        $strings['days'] = 'napok';
        $strings['weeks'] = 'hetek';
        $strings['months'] = 'hónapon';
        $strings['years'] = 'évek';
        $strings['day'] = 'nap';
        $strings['week'] = 'hét';
        $strings['month'] = 'hónap';
        $strings['year'] = 'év';
        $strings['repeatDayOfMonth'] = 'a hó napja';
        $strings['repeatDayOfWeek'] = 'a hét napja';
        $strings['RepeatUntilPrompt'] = 'eddig';
        $strings['RepeatEveryPrompt'] = 'Minden';
        $strings['RepeatDaysPrompt'] = 'On';
        $strings['CreateReservationHeading'] = 'Új foglalás';
        $strings['EditReservationHeading'] = 'Foglalás szerkesztése %s';
        $strings['ViewReservationHeading'] = 'Foglalás megtekintése %s';
        $strings['ReservationErrors'] = 'Foglalás módosítása';
        $strings['Create'] = 'Létrehoz';
        $strings['ThisInstance'] = 'Csak ebben az esetben';
        $strings['AllInstances'] = 'Minden esetben';
        $strings['FutureInstances'] = 'A jövőbeli esetekben';
        $strings['Print'] = 'Nyomtatás';
        $strings['ShowHideNavigation'] = 'Navigáció mutat/elrejt';
        $strings['ReferenceNumber'] = 'Referenciaszám';
        $strings['Tomorrow'] = 'Holnap';
        $strings['LaterThisWeek'] = 'Később ezen a héten';
        $strings['NextWeek'] = 'Következő hét';
        $strings['SignOut'] = 'Kijelentkezés';
        $strings['LayoutDescription'] = 'Kezdés %s, mutat %s napot egyszerre';
        $strings['AllResources'] = 'Minden eszköz';
        $strings['TakeOffline'] = 'Vegye elérhetetlenre';
        $strings['BringOnline'] = 'Vegye elérhetőre';
        $strings['AddImage'] = 'Kép hozzáadása';
        $strings['NoImage'] = 'Nincs kép kijelölve';
        $strings['Move'] = 'Mozgat';
        $strings['AppearsOn'] = 'Megjelenik %s';
        $strings['Location'] = 'Helyszín';
        $strings['NoLocationLabel'] = '(nincs helyszín bejelölve)';
        $strings['Contact'] = 'Kapcsolat';
        $strings['NoContactLabel'] = '(nincs kapcsolati információ)';
        $strings['Description'] = 'Leírás';
        $strings['NoDescriptionLabel'] = '(nincs leírás)';
        $strings['Notes'] = 'Jegyzetek';
        $strings['NoNotesLabel'] = '(nincsenek jegyzetek)';
        $strings['NoTitleLabel'] = '(nincs cím)';
        $strings['UsageConfiguration'] = 'Használat megerősítése';
        $strings['ChangeConfiguration'] = 'Megerősítés módosítása';
        $strings['ResourceMinLength'] = 'A foglalás legalább eddig kell tartson %s';
        $strings['ResourceMinLengthNone'] = 'Nincs minimális foglalási időtartam';
        $strings['ResourceMaxLength'] = 'A foglalás nem tarthat tovább, mint %s';
        $strings['ResourceMaxLengthNone'] = 'Nincs maximális fogalási időtartam';
        $strings['ResourceRequiresApproval'] = 'A foglást engedélyezni kell.';
        $strings['ResourceRequiresApprovalNone'] = 'A fogalásokat nem szükséges engedélyezni.';
        $strings['ResourcePermissionAutoGranted'] = 'Hozzáférés automatikusan megadásra kerül';
        $strings['ResourcePermissionNotAutoGranted'] = 'A hozzáférés nem kerül automatikusan megadásra';
        $strings['ResourceMinNotice'] = 'A foglalást legalább %s-tal a kezdeti időpont előtt kell elvégezni.';
        $strings['ResourceMinNoticeNone'] = 'A foglalásokat egészen az aktuális időpontig lehet végezni.';
        $strings['ResourceMinNoticeUpdate'] = 'A foglalást legalább %s-tal a kezdeti időpont előtt lehet frissíteni';
        $strings['ResourceMinNoticeNoneUpdate'] = 'A foglalásokat legfeljebb az aktuális időpontig lehet frissíteni';
        $strings['ResourceMinNoticeDelete'] = 'A foglalást legalább %s-tal a kezdeti időpont előtt lehet törölni';
        $strings['ResourceMinNoticeNoneDelete'] = 'A foglalásokat az aktuális időpontig lehet törölni';
        $strings['ResourceMaxNotice'] = 'A foglalások nem végződhetnek %s-tal az aktuális időponthoz képest';
        $strings['ResourceMaxNoticeNone'] = 'A fogalások bármikor végződhetnek a jövőben.';
        $strings['ResourceBufferTime'] = 'Legalább %s időnek kell lenni két foglalás között';
        $strings['ResourceBufferTimeNone'] = 'Nincs ütközés a két foglalás között';
        $strings['ResourceAllowMultiDay'] = 'A fogalások más napra is átterjedhetnek.';
        $strings['ResourceNotAllowMultiDay'] = 'A fogalások más napra nem terjedhetnek át.';
        $strings['ResourceCapacity'] = 'Ez forrás legfeljebb %s embert fogad be';
        $strings['ResourceCapacityNone'] = 'Ennek a forrásnak korlátlak kapacitása van';
        $strings['AddNewResource'] = 'Új elem hozzáadása';
        $strings['AddNewUser'] = 'Új felhasználó hozzáadása';
        $strings['AddResource'] = 'Elem hozzáadása';
        $strings['Capacity'] = 'Kapacitás';
        $strings['Access'] = 'Hozzáférés';
        $strings['Duration'] = 'Időtartam';
        $strings['Active'] = 'Aktív';
        $strings['Inactive'] = 'Inaktív';
        $strings['ResetPassword'] = 'Jelszó visszaállítása';
        $strings['LastLogin'] = 'Legutóbbi bejelentkezés';
        $strings['Search'] = 'Keresés';
        $strings['ResourcePermissions'] = 'Elem jogosultságai';
        $strings['Reservations'] = 'Foglalások';
        $strings['Groups'] = 'Csoportok';
        $strings['Users'] = 'Felhasználók';
        $strings['AllUsers'] = 'Minden felhasználó';
        $strings['AllGroups'] = 'Minden csoport';
        $strings['AllSchedules'] = 'Minden időbeosztás';
        $strings['UsernameOrEmail'] = 'Felhasználónév vagy jelszó';
        $strings['Members'] = 'Tagok';
        $strings['QuickSlotCreation'] = 'Készítsen egy egységet minden %s percenként %s és %s';
        $strings['ApplyUpdatesTo'] = 'Alkalmazza a frissítéseket a következőkre';
        $strings['CancelParticipation'] = 'Résztvétel visszavonása';
        $strings['Attending'] = 'Csatlakozás';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['QuotaEnforcement'] = 'Kényszerítve %s %s';
        $strings['reservations'] = 'fogalások';
        $strings['reservation'] = 'foglalás';
        $strings['ChangeCalendar'] = 'Naptár váltása';
        $strings['AddQuota'] = 'Kvóta hozzáadása';
        $strings['FindUser'] = 'Felhasználó keresése';
        $strings['Created'] = 'Létrehozva';
        $strings['LastModified'] = 'Utoljára módosítva';
        $strings['GroupName'] = 'Csoport neve';
        $strings['GroupMembers'] = 'Csoport tagjai';
        $strings['GroupRoles'] = 'Csoport szabályai';
        $strings['GroupAdmin'] = 'Csoport adminisztrátor';
        $strings['Actions'] = 'Műveletek';
        $strings['CurrentPassword'] = 'Jelenlegi jelszó';
        $strings['NewPassword'] = 'Új jelszó';
        $strings['InvalidPassword'] = 'A jelenlegi jelszó helytelen';
        $strings['PasswordChangedSuccessfully'] = 'A jelszava sikeresen megváltozott';
        $strings['SignedInAs'] = 'Bejelentkezve mint';
        $strings['NotSignedIn'] = 'Nincs bejelentkezve';
        $strings['ReservationTitle'] = 'A foglalás elnevezése';
        $strings['ReservationDescription'] = 'A foglalás leírása';
        $strings['ResourceList'] = 'Foglalásra váró elemek';
        $strings['Accessories'] = 'Kiegészítők';
        $strings['InvitationList'] = 'Meghívottak';
        $strings['AccessoryName'] = 'A kiegészítő neve';
        $strings['QuantityAvailable'] = 'Elérhető mennyiség';
        $strings['Resources'] = 'Források';
        $strings['Participants'] = 'Résztvevők';
        $strings['User'] = 'felhasználó';
        $strings['Resource'] = 'Forrás';
        $strings['Status'] = 'Státusz';
        $strings['Approve'] = 'Jóváhagyás';
        $strings['Page'] = 'Oldal';
        $strings['Rows'] = 'Sorok';
        $strings['Unlimited'] = 'Korlátlan';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email cím';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Szervezet';
        $strings['Position'] = 'Pozíció';
        $strings['Language'] = 'Nyelv';
        $strings['Permissions'] = 'Engedélyek';
        $strings['Reset'] = 'Visszaállítás';
        $strings['FindGroup'] = 'Csoport keresése';
        $strings['Manage'] = 'Kezelés';
        $strings['None'] = 'Egyik sem';
        $strings['AddToOutlook'] = 'Naptárhoz adás';
        $strings['Done'] = 'Kész';
        $strings['RememberMe'] = 'Emlékezzen rám';
        $strings['FirstTimeUser?'] = 'Első felhasználó?';
        $strings['CreateAnAccount'] = 'Fiók létrehozása';
        $strings['ViewSchedule'] = 'Beosztás megtekintése';
        $strings['ForgotMyPassword'] = 'Elfelejtettem a jelszavam';
        $strings['YouWillBeEmailedANewPassword'] = 'E-mailben küldünk egy véletlen generált jelszót.';
        $strings['Close'] = 'Bezár';
        $strings['ExportToCSV'] = 'Exportálás CSV formátumba';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Dolgozunk...';
        $strings['Login'] = 'Belép';
        $strings['AdditionalInformation'] = 'További információ';
        $strings['AllFieldsAreRequired'] = 'minden mező kötelező';
        $strings['Optional'] = 'választható';
        $strings['YourProfileWasUpdated'] = 'Profilja frissítve';
        $strings['YourSettingsWereUpdated'] = 'Beállításai frissítve';
        $strings['Register'] = 'Regisztrálás';
        $strings['SecurityCode'] = 'Biztonsági kód';
        $strings['ReservationCreatedPreference'] = 'Ha foglalok, vagy egy foglalás történt a megbízásom alapján';
        $strings['ReservationUpdatedPreference'] = 'Ha frissítek egy foglalást, vagy módosítottak egy foglalást a megbízásom alapján.';
        $strings['ReservationDeletedPreference'] = 'Ha törlök vagy töröltek egy foglalást a megbízásom alapján';
        $strings['ReservationApprovalPreference'] = 'Amikor egy függőben lévő foglalásom engedélyezésre kerül';
        $strings['PreferenceSendEmail'] = 'Küldjön e-mailt a részemre';
        $strings['PreferenceNoEmail'] = 'Ne értesítsen';
        $strings['ReservationCreated'] = 'Fogalása sikeresen létrehozva!';
        $strings['ReservationUpdated'] = 'Foglalása sikeresen frissítve!';
        $strings['ReservationRemoved'] = 'Foglalását eltávolították';
        $strings['ReservationRequiresApproval'] = 'Egy vagy több elem engedélyezése szükséges használat előtt.  Ez a foglalás függőben marad ezek engedélyezéséig.';
        $strings['YourReferenceNumber'] = 'Az Ön referencia száma %s';
        $strings['ChangeUser'] = 'Felhasználó váltása';
        $strings['MoreResources'] = 'Több elem';
        $strings['ReservationLength'] = 'A foglalás hossza';
        $strings['ParticipantList'] = 'Résztvevők listája';
        $strings['AddParticipants'] = 'Résztvevők hozzáadása';
        $strings['InviteOthers'] = 'Mások meghívása';
        $strings['AddResources'] = 'Elemek hozzáadása';
        $strings['AddAccessories'] = 'Kiegészítők hozzáadása';
        $strings['Accessory'] = 'Kiegészítő';
        $strings['QuantityRequested'] = 'Igényelt mennyiség';
        $strings['CreatingReservation'] = 'Foglalás létrehozása';
        $strings['UpdatingReservation'] = 'Foglalás frissítése';
        $strings['DeleteWarning'] = 'Ez a művelet végleges és nincs mód a visszavonásra!';
        $strings['DeleteAccessoryWarning'] = 'Ezen kiegészítő törlése esetén törlődik minden foglalásból.';
        $strings['AddAccessory'] = 'Kiegészítő hozzáadása';
        $strings['AddBlackout'] = 'Üzemszünet hozzáadása';
        $strings['AllResourcesOn'] = 'Minden elem aktiválása';
        $strings['Reason'] = 'Indok';
        $strings['BlackoutShowMe'] = 'Mutassa meg az ütköző foglalásokat';
        $strings['BlackoutDeleteConflicts'] = 'Törölje az ütköző foglalásokat';
        $strings['Filter'] = 'Szűrő';
        $strings['Between'] = 'alábbiak között';
        $strings['CreatedBy'] = 'Létrehozta';
        $strings['BlackoutCreated'] = 'Üzemszünet hozzáadva';
        $strings['BlackoutNotCreated'] = 'Az üzemszünetet nem lehet létrehozni';
        $strings['BlackoutUpdated'] = 'Üzemszünet frissítve';
        $strings['BlackoutNotUpdated'] = 'Üzemszünet nem frissíthető';
        $strings['BlackoutConflicts'] = 'Ütközés van az üzemszünetek között';
        $strings['ReservationConflicts'] = 'Ütköző foglalások';
        $strings['UsersInGroup'] = 'Felhasználók ebben a csoportban';
        $strings['Browse'] = 'Tallózás';
        $strings['DeleteGroupWarning'] = 'A csoport eltávolításával törlődnek a hozzá tartozó engedélyek.  A csoport felhasználói elveszthetik a foglalásaik hozzáférését.';
        $strings['WhatRolesApplyToThisGroup'] = 'Mely szabályok érvényesek a csoportra?';
        $strings['WhoCanManageThisGroup'] = 'Ki kezelheti a csoportot?';
        $strings['WhoCanManageThisSchedule'] = 'Ki kezelheti a beosztást?';
        $strings['AllQuotas'] = 'Minden kvóta';
        $strings['QuotaReminder'] = 'Emlékeztető: A kvóták a beosztás időzónájához vannak kötve.';
        $strings['AllReservations'] = 'Minden foglalás';
        $strings['PendingReservations'] = 'Függőben lévő foglalások';
        $strings['Approving'] = 'Jóváhagyás';
        $strings['MoveToSchedule'] = 'A beosztásokhoz';
        $strings['DeleteResourceWarning'] = 'Ezen elem törlésével törlődik minden hozzá tartozó adat, mint';
        $strings['DeleteResourceWarningReservations'] = 'minden hozzá tartozó múltbéli, jelenlegi és jövőbeli foglalás';
        $strings['DeleteResourceWarningPermissions'] = 'minden jogosultság kiosztás';
        $strings['DeleteResourceWarningReassign'] = 'Kérem, mielőtt továbblép, rendeljen hozzá újra mindent, amelyet nem szeretne törölni.';
        $strings['ScheduleLayout'] = 'Elrendezés (mindel alkalom %s)';
        $strings['ReservableTimeSlots'] = 'Foglalható idő egységek';
        $strings['BlockedTimeSlots'] = 'Lezárt idő egységek';
        $strings['ThisIsTheDefaultSchedule'] = 'Ez az alapértelmezett beosztás';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Az alapértelmezett beosztás nem törölhető';
        $strings['MakeDefault'] = 'Tegye alapértelmezetté';
        $strings['BringDown'] = 'Mozgatás lejjebb';
        $strings['ChangeLayout'] = 'Elrendezés változtatása';
        $strings['AddSchedule'] = 'Beosztás hozzáadása';
        $strings['StartsOn'] = 'Kezdő időpont';
        $strings['NumberOfDaysVisible'] = 'Látható napok száma';
        $strings['UseSameLayoutAs'] = 'Használja azt az elrendezést mint';
        $strings['Format'] = 'Formátum';
        $strings['OptionalLabel'] = 'Választható cimke';
        $strings['LayoutInstructions'] = 'Soronként egy egységet adjon meg.  Az egységeket a nap 24 órájára meg kell adni, 0:00 kezdettel és befejezéssel.';
        $strings['AddUser'] = 'Felhasználó hozzáadása';
        $strings['UserPermissionInfo'] = 'Az elemmhez való hozzáférés eltérő lehet függően a felhsználói szabályoktól, csoport szabályoktól vagy külső hozzáférési beállításoktól';
        $strings['DeleteUserWarning'] = 'Jelen felhasználó torlésével törlődik minden jelenlegi jövőbeli és múltbéli foglalása.';
        $strings['AddAnnouncement'] = 'Bejelentés hozzáadása';
        $strings['Announcement'] = 'Bejelentés';
        $strings['Priority'] = 'Prioritás';
        $strings['Reservable'] = 'Megmyitás';
        $strings['Unreservable'] = 'Blokkolt';
        $strings['Reserved'] = 'Foglalt';
        $strings['MyReservation'] = 'Foglalásom';
        $strings['Pending'] = 'Függőbem';
        $strings['Past'] = 'Múlt';
        $strings['Restricted'] = 'Korlátozott';
        $strings['ViewAll'] = 'Mind megtekintése';
        $strings['MoveResourcesAndReservations'] = 'Elemek és foglalások mozgatása';
        $strings['TurnOffSubscription'] = 'Elrejtés';
        $strings['TurnOnSubscription'] = 'Nyilvánossá tesz (RSS, iCalendar)';
        $strings['SubscribeToCalendar'] = 'Feliratkozás erre a naptárra';
        $strings['SubscriptionsAreDisabled'] = 'Az adminisztrátor letiltotta a naptárra való feliratkozást';
        $strings['NoResourceAdministratorLabel'] = '(Nincs Erőforrás Adminiszttrátor)';
        $strings['WhoCanManageThisResource'] = 'Ki kezelheti ezt az elemet?';
        $strings['ResourceAdministrator'] = 'Elem adminisztrátor';
        $strings['Private'] = 'Privát';
        $strings['Accept'] = 'Elfogad';
        $strings['Decline'] = 'Elutasít';
        $strings['ShowFullWeek'] = 'Teljes hét mutatása';
        $strings['CustomAttributes'] = 'Egyedi attribútumok';
        $strings['AddAttribute'] = 'Attribútum hozzáadása';
        $strings['EditAttribute'] = 'Attribútum frissítése';
        $strings['DisplayLabel'] = 'Cimkék megjelenítése';
        $strings['Type'] = 'Tipus';
        $strings['Required'] = 'Szükséges';
        $strings['ValidationExpression'] = 'Validation Expression';
        $strings['PossibleValues'] = 'Lehetséges értékek';
        $strings['SingleLineTextbox'] = 'Egy sorból álló szövegdoboz';
        $strings['MultiLineTextbox'] = 'Több sorból álló szövegdoboz';
        $strings['Checkbox'] = 'Jelölőnégyzet';
        $strings['SelectList'] = 'Legördülő lista';
        $strings['CommaSeparated'] = 'vesszővel elválasztott';
        $strings['Category'] = 'Kategória';
        $strings['CategoryReservation'] = 'Foglalás';
        $strings['CategoryGroup'] = 'Csoport';
        $strings['SortOrder'] = 'Redezési elv';
        $strings['Title'] = 'Megnevezés';
        $strings['AdditionalAttributes'] = 'További attribútumok';
        $strings['True'] = 'Igaz';
        $strings['False'] = 'Hamis';
        $strings['ForgotPasswordEmailSent'] = 'Egy e-mail kiküldésre került a jelszó visszaállításához szükséges utasításokkal';
        $strings['ActivationEmailSent'] = 'Hamarosan e-mailt fog kapni.';
        $strings['AccountActivationError'] = 'Sajnáljuk, nem tudjuk aktiválni a fiókját.';
        $strings['Attachments'] = 'Csatolmányok';
        $strings['AttachFile'] = 'Fájl csatolása';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Nincs beosztás adminisztrátor';
        $strings['ScheduleAdministrator'] = 'Beosztás adminisztrátor';
        $strings['Total'] = 'Összesen';
        $strings['QuantityReserved'] = 'Mennyiség foglalva';
        $strings['AllAccessories'] = 'Minden kiegészítő';
        $strings['GetReport'] = 'Kimutatás lehívása';
        $strings['NoResultsFound'] = 'Nem található megfelelő eredmény';
        $strings['SaveThisReport'] = 'Kimutatás mentése';
        $strings['ReportSaved'] = 'Kimutatás elmentve!';
        $strings['EmailReport'] = 'Kimutatás küldése e-mailben';
        $strings['ReportSent'] = 'Kimutatás elküldve!';
        $strings['RunReport'] = 'Kimutatás futtatása';
        $strings['NoSavedReports'] = 'Nincs mentett kimutatása.';
        $strings['CurrentWeek'] = 'Aktuális hét';
        $strings['CurrentMonth'] = 'Aktuális hónap';
        $strings['AllTime'] = 'Minden időszal';
        $strings['FilterBy'] = 'Szűrés alapja';
        $strings['Select'] = 'Kiválaszt';
        $strings['List'] = 'Listázás';
        $strings['TotalTime'] = 'Idő';
        $strings['Count'] = 'Mennyiség';
        $strings['Usage'] = 'Használat';
        $strings['AggregateBy'] = 'Aggregate By';
        $strings['Range'] = 'Sorba állít';
        $strings['Choose'] = 'Kiválaszt';
        $strings['All'] = 'Mind';
        $strings['ViewAsChart'] = 'Megtekintés diagramként';
        $strings['ReservedResources'] = 'Foglalt elemek';
        $strings['ReservedAccessories'] = 'Foglalt kiegészítők';
        $strings['ResourceUsageTimeBooked'] = 'Elem kihasználtság - foglalás idő szerint';
        $strings['ResourceUsageReservationCount'] = 'Elem kihasználtság - foglalások száma';
        $strings['Top20UsersTimeBooked'] = 'Top 20 felhasználó - foglalási idő szerint';
        $strings['Top20UsersReservationCount'] = 'Top 20 felhasználó - fogalások száma alapján';
        $strings['ConfigurationUpdated'] = 'A konfigurációs fájl mentve';
        $strings['ConfigurationUiNotEnabled'] = 'Ez az oldal nem hozzáférhető. Ok: $conf[\'settings\'][\'pages\'][\'enable.configuration\'] is set to false or missing.';
        $strings['ConfigurationFileNotWritable'] = 'A konfigurációs fájl nem írható. Kérem, ellenőrízze a fájl jogosultságait és próbálja újra.';
        $strings['ConfigurationUpdateHelp'] = 'Kérem, keresse fel a konfiguráció alábbi részét: <a target=_blank href=%s>Súgó fájll</a> alábbi beállítások dokumentéciójáért.';
        $strings['GeneralConfigSettings'] = 'beállítások';
        $strings['UseSameLayoutForAllDays'] = 'Használja ugyanazta a kiosztást minden napra';
        $strings['LayoutVariesByDay'] = 'A kiosztás változik a napok alapján';
        $strings['ManageReminders'] = 'Emlékeztetők';
        $strings['ReminderUser'] = 'Felhasználői azonosító';
        $strings['ReminderMessage'] = 'Üzenet';
        $strings['ReminderAddress'] = 'Címek';
        $strings['ReminderSendtime'] = 'Küldés időpontja';
        $strings['ReminderRefNumber'] = 'A foglalás referencia száma';
        $strings['ReminderSendtimeDate'] = 'Az emlékezető dátuma';
        $strings['ReminderSendtimeTime'] = 'Az emlékezető időpontja (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'DE / DU';
        $strings['AddReminder'] = 'Emlékeztető hozzáadása';
        $strings['DeleteReminderWarning'] = 'Valóban törli?';
        $strings['NoReminders'] = 'Nincs közelgő emlékeztetője.';
        $strings['Reminders'] = 'Emlékeztetők';
        $strings['SendReminder'] = 'Emlékeztető küldése';
        $strings['minutes'] = 'percek';
        $strings['hours'] = 'órák';
        $strings['days'] = 'napok';
        $strings['ReminderBeforeStart'] = 'kezdeti időpont előtt';
        $strings['ReminderBeforeEnd'] = 'befejezés időpontja előtt';
        $strings['Logo'] = 'Logó';
        $strings['CssFile'] = 'CSS Fájl';
        $strings['ThemeUploadSuccess'] = 'Beállításai elmentve. Kérjük frissítse az oldalt a beállítások érvényesítéséhez.';
        $strings['MakeDefaultSchedule'] = 'Tegye ezt a beosztást az én alapértelmezettemmé';
        $strings['DefaultScheduleSet'] = 'Ez a beosztás mostantól az Ön alapértelmezettje';
        $strings['FlipSchedule'] = 'Fordítson a beosztás kiosztásán';
        $strings['Next'] = 'Következő';
        $strings['Success'] = 'Sikeres';
        $strings['Participant'] = 'Résztvevő';
        $strings['ResourceFilter'] = 'Elem szűrő';
        $strings['ResourceGroups'] = 'Elem csoportok';
        $strings['AddNewGroup'] = 'Új csoport hozzáadása';
        $strings['Quit'] = 'Kilépés';
        $strings['AddGroup'] = 'Csoport hozzáadása';
        $strings['StandardScheduleDisplay'] = 'Az alapértelmezett beosztás megjelenítés használata';
        $strings['TallScheduleDisplay'] = 'Magas megejelenítés használata';
        $strings['WideScheduleDisplay'] = 'Széles megjelenítés hsználata';
        $strings['CondensedWeekScheduleDisplay'] = 'Tömör heti megjelenítés használata';
        $strings['ResourceGroupHelp1'] = 'Húzással rendezze át az elem csoportokat.';
        $strings['ResourceGroupHelp2'] = 'Kattintson jobb gombbal egy elem csoportra további műveletekért.';
        $strings['ResourceGroupHelp3'] = 'Húzza az elemeket a csoportokra a hozzáadáshoz.';
        $strings['ResourceGroupWarning'] = 'Ha elemcsoportokat használ, minden elemet legalább egy csoporthoz ki kell jelölni. A ki nem jelölt elemeket nem lehet majd foglalni.';
        $strings['ResourceType'] = 'Elemtípus';
        $strings['AppliesTo'] = 'Alkalmazható a következőre';
        $strings['UniquePerInstance'] = 'Egyedi folyamatonként';
        $strings['AddResourceType'] = 'Elemtípus hozzáadása';
        $strings['NoResourceTypeLabel'] = '(még nincs elemtípus)';
        $strings['ClearFilter'] = 'Szűrő törlése';
        $strings['MinimumCapacity'] = 'Minimum kapacitás';
        $strings['Color'] = 'Szín';
        $strings['Available'] = 'Elérhető';
        $strings['Unavailable'] = 'Nem elérhető';
        $strings['Hidden'] = 'Rejtett';
        $strings['ResourceStatus'] = 'Elem státusz';
        $strings['CurrentStatus'] = 'Jelenlegi státusz';
        $strings['AllReservationResources'] = 'Minden foglalás eleme';
        $strings['File'] = 'Fájl';
        $strings['BulkResourceUpdate'] = 'Kötegelt elem frissítés';
        $strings['Unchanged'] = 'Változatlan';
        $strings['Common'] = 'Közös';
        $strings['AdminOnly'] = 'Kizárólag admin';
        $strings['AdvancedFilter'] = 'Bővített szűrő';
        $strings['MinimumQuantity'] = 'Minimum mennyiség';
        $strings['MaximumQuantity'] = 'Max mennyiség';
        $strings['ChangeLanguage'] = 'Nyelv cseréje';
        $strings['AddRule'] = 'Szabály hozzáadása';
        $strings['Attribute'] = 'Attribútum';
        $strings['RequiredValue'] = 'Szükséges érték';
        $strings['ReservationCustomRuleAdd'] = 'Használja az alábbi színt, amikor a foglalás attribútum a következő értékre került beállításra';
        $strings['AddReservationColorRule'] = 'Foglalási szín szabály hozzáadása';
        $strings['LimitAttributeScope'] = 'Különleges esetekben gyűjtse';
        $strings['CollectFor'] = 'Gyűjtse a következőnek';
        $strings['SignIn'] = 'Bejelentkezés';
        $strings['AllParticipants'] = 'Minden résztvevő';
        $strings['RegisterANewAccount'] = 'Egy új fiók regisztrálása';
        $strings['Dates'] = 'Dátumok';
        $strings['More'] = 'Több';
        $strings['ResourceAvailability'] = 'Elem elérhetősége';
        $strings['UnavailableAllDay'] = 'Nem elérhető egész nap';
        $strings['AvailableUntil'] = 'Elérhető eddig';
        $strings['AvailableBeginningAt'] = 'Elérhető a következő időponttól';
        $strings['AvailableAt'] = 'Elérhető';
        $strings['AllResourceTypes'] = 'Minden elem típus';
        $strings['AllResourceStatuses'] = 'Minden elem státusz';
        $strings['AllowParticipantsToJoin'] = 'Engedélyezze a résztvevőknek, hogy csatlakozzanak';
        $strings['Join'] = 'Csatlakozom';
        $strings['YouAreAParticipant'] = 'Őn résztvevője ennek a foglalásnak';
        $strings['YouAreInvited'] = 'Meghívták erre a foglalásra';
        $strings['YouCanJoinThisReservation'] = 'Csatlakozhat ehhez a foglaláshoz';
        $strings['Import'] = 'Importálás';
        $strings['GetTemplate'] = 'Sablon letöltésre';
        $strings['UserImportInstructions'] = '<ul><li>A fájl CSV formátumban szükséges.</li><li>Username and email are required fields.</li><li>Attribute validity will not be enforced.</li><li>Leaving other fields blank will set default values and \'password\' as the user\'s password.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['RowsImported'] = 'Sor importálva';
        $strings['RowsSkipped'] = 'Sor átugorva';
        $strings['Columns'] = 'Oszlop';
        $strings['Reserve'] = 'Foglalva';
        $strings['AllDay'] = 'Egész nap';
        $strings['Everyday'] = 'Minden nap';
        $strings['IncludingCompletedReservations'] = 'beleértve teljesített foglalást';
        $strings['NotCountingCompletedReservations'] = 'kivéve teljesített foglalások';
        $strings['RetrySkipConflicts'] = 'ütköző foglalások átugrása';
        $strings['Retry'] = 'Újra próbál';
        $strings['RemoveExistingPermissions'] = 'Létező engedélyek eltávolítása?';
        $strings['Continue'] = 'Folytatás';
        $strings['WeNeedYourEmailAddress'] = 'Szükségünk van az e-mail címére a foglaláshoz';
        $strings['ResourceColor'] = 'Elem színe';
        $strings['DateTime'] = 'dátum és idő';
        $strings['AutoReleaseNotification'] = 'Automatikusan felszabadítul, ha nem kerül foglalásra %s percen belül';
        $strings['RequiresCheckInNotification'] = 'Ki és be csakkolás szükséges';
        $strings['NoCheckInRequiredNotification'] = 'Nem szükséges a ki/be csekkolás';
        $strings['RequiresApproval'] = 'Engedélyezés szükséges';
        $strings['CheckingIn'] = 'Bejelentkezés';
        $strings['CheckingOut'] = 'Kijelentkezés';
        $strings['CheckIn'] = 'Bejelentkezés';
        $strings['CheckOut'] = 'Kijelentkezés';
        $strings['ReleasedIn'] = 'Felszabadítva';
        $strings['CheckedInSuccess'] = 'Ön bejelentkezett';
        $strings['CheckedOutSuccess'] = 'Ön kijelentkezett';
        $strings['CheckInFailed'] = 'Nem jelentkeztethető be';
        $strings['CheckOutFailed'] = 'Nem jelentkeztethető ki';
        $strings['CheckInTime'] = 'Bejelentkezés ideje';
        $strings['CheckOutTime'] = 'Kijelentkezés ideje';
        $strings['OriginalEndDate'] = 'Eredeti befejezés';
        $strings['SpecificDates'] = 'Mutassa a megadott dátumokat';
        $strings['Users'] = 'Felhasználók';
        $strings['Guest'] = 'Vendég';
        $strings['ResourceDisplayPrompt'] = 'Elemek megjelenítése';
        $strings['Credits'] = 'Egység';
        $strings['AvailableCredits'] = 'Elérhető egységek';
        $strings['CreditUsagePerSlot'] = 'Szükséges %s egység rekeszenként (csúcsidőn kívül)';
        $strings['PeakCreditUsagePerSlot'] = 'Szükséges %s egység rekeszenként (csúcsidőben)';
        $strings['CreditsRule'] = 'Nincs rendelkezésre álló egység. Szükséges: %s. Egység a fiókjában: %s';
        $strings['PeakTimes'] = 'Csúcsidő';
        $strings['AllYear'] = 'Egész évben';
        $strings['MoreOptions'] = 'További opciók';
        $strings['SendAsEmail'] = 'Küldés e-mailként';
        $strings['UsersInGroups'] = 'Felhasználók a csoportokban';
        $strings['UsersWithAccessToResources'] = 'Felhasználók az elemekhez való hozzáféréssel';
        $strings['AnnouncementSubject'] = 'Egy új belejeltés alábbi felhasználótól %s';
        $strings['AnnouncementEmailNotice'] = 'felhasználóknak e-mailként lesz kiküldve a bejelentés';
        $strings['Day'] = 'Nap';
        $strings['NotifyWhenAvailable'] = 'Értesítsen ha elérhető';
        $strings['AddingToWaitlist'] = 'Hozzáadva a várólistához';
        $strings['WaitlistRequestAdded'] = 'Értesítve lesz, ha ez az időpont elérhető lesz.';
        $strings['PrintQRCode'] = 'QR kód nyomtatása';
        $strings['FindATime'] = 'Időpont keresése';
        $strings['AnyResource'] = 'Bármely elem';
        $strings['ThisWeek'] = 'Ezen a héten';
        $strings['Hours'] = 'Órák';
        $strings['Minutes'] = 'Percek';
        $strings['ImportICS'] = 'Importálás ICS-ből';
        $strings['ImportQuartzy'] = 'Importálás Quartzy-ból';
        $strings['OnlyIcs'] = 'Csak *.ics fájlok feltölthetőek.';
        $strings['IcsLocationsAsResources'] = 'A helyszínek elemekként kerülnek betöltésre.';
        $strings['IcsMissingOrganizer'] = 'Any event missing an organizer will have the owner set to the current user.';
        $strings['IcsWarning'] = 'A foglalási szabályok nem lesznek kényszerítve - ütközések, duplikációk előfordulhatnak.';
        $strings['BlackoutAroundConflicts'] = 'Üzemszünet az ütköző foglalások környékén';
        $strings['DuplicateReservation'] = 'Ismétlődés';
        $strings['UnavailableNow'] = 'Jelenleg nem elérhető';
        $strings['ReserveLater'] = 'Foglálás később';
        $strings['CollectedFor'] = 'Begyűjtve a következő részére';
        $strings['IncludeDeleted'] = 'A törölt foglalásokat is beleértve';
        $strings['Deleted'] = 'Törölve';
        $strings['Back'] = 'Vissza';
        $strings['Forward'] = 'Előre';
        $strings['DateRange'] = 'Dátum tartomány';
        $strings['Copy'] = 'Másol';
        $strings['Detect'] = 'Keres';
        $strings['Autofill'] = 'Kitöltés automatikusan';
        $strings['NameOrEmail'] = 'név vagy e-mail';
        $strings['ImportResources'] = 'Elemek importálása';
        $strings['ExportResources'] = 'Elemek exportálása';
        $strings['ResourceImportInstructions'] = '<ul><li>A fájl CSV formátumban kell, hogy legyen UTF-8 kódolással.</li><li>Name is required field. Leaving other fields blank will set default values.</li><li>Status options are \'Available\', \'Unavailable\' and \'Hidden\'.</li><li>Color should be the hex value. ex) #ffffff.</li><li>Auto assign and approval columns can be true or false.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource groups.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>A fájl CSV formátumban kell, hogy legyen UTF-8 kódolással.</li><li>Email, resource names, begin, and end are required fields.</li><li>Begin and end require full date time. Recommended format is YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Rules, conflicts, and valid time slots will not be checked.</li><li>Notifications will not be sent.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource names.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Percek automatikus felszabadítása';
        $strings['CreditsPeak'] = 'Egység (csúcsidőben)';
        $strings['CreditsOffPeak'] = 'Egység (csúcsidőn kívül)';
        $strings['ResourceMinLengthCsv'] = 'Foglalás minimális hossza';
        $strings['ResourceMaxLengthCsv'] = 'Foglalás maximális hossza';
        $strings['ResourceBufferTimeCsv'] = 'Tartalék idő';
        $strings['ResourceMinNoticeAddCsv'] = 'Reservation Add Minimum Notice';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Reservation Update Minimum Notice';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Reservation Delete Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'A foglalás maximális befejezése';
        $strings['Export'] = 'Exportálás';
        $strings['DeleteMultipleUserWarning'] = 'A felhasználók törlésével eltávolításra kerül minden múltbéli, jelenlegi és jövőbeli foglalás. E-mail nem kerül kiküldésre.';
        $strings['DeleteMultipleReservationsWarning'] = 'E-mail nem kerül kiküldésre.';
        $strings['ErrorMovingReservation'] = 'Hiba: Foglalások mozgatása';
        $strings['SelectUser'] = 'Felhasználó kijelölése';
        $strings['InviteUsers'] = 'Felhasználók meghívása';
        $strings['InviteUsersLabel'] = 'Adja meg a meghívandó emberek e-mail címét';
        $strings['ApplyToCurrentUsers'] = 'Alkalmazás az aktuális felhasználó részére';
        $strings['ReasonText'] = 'Indoklás szövege';
        $strings['NoAvailableMatchingTimes'] = 'A keresés alapján nincs elérhető időpont';
        $strings['Schedules'] = 'Beosztások';
        $strings['NotifyUser'] = 'Felhasználó értesítése';
        $strings['UpdateUsersOnImport'] = 'Létező felhasználó frissítése, ha az e-mail cím már létezik';
        $strings['UpdateResourcesOnImport'] = 'Létező elemek frissítése, ha a név már létezik';
        $strings['Reject'] = 'Elutasítás';
        $strings['CheckingAvailability'] = 'Elérhetőség ellenőrzése';
        $strings['CreditPurchaseNotEnabled'] = 'Nem engedélyezte egységek vásárlásának lehetőségét';
        $strings['CreditsCost'] = 'Egységek darabára';
        $strings['Currency'] = 'Pénznem';
        $strings['PayPalClientId'] = 'Ügyfél azonosítója';
        $strings['PayPalSecret'] = 'Titkos';
        $strings['PayPalEnvironment'] = 'Környezet';
        $strings['Sandbox'] = 'Homokozó';
        $strings['Live'] = 'Élő';
        $strings['StripePublishableKey'] = 'Nyilvános kulcs';
        $strings['StripeSecretKey'] = 'Titkos kulcs';
        $strings['CreditsUpdated'] = 'Egység darabár frissítve';
        $strings['GatewaysUpdated'] = 'A fizető átjárók frissítve';
        $strings['PurchaseSummary'] = 'Vásárlások összesítése';
        $strings['EachCreditCosts'] = 'Az egységek darabára';
        $strings['Checkout'] = 'Fizetés';
        $strings['Quantity'] = 'Mennyiség';
        $strings['CreditPurchase'] = 'Egység vásárás';
        $strings['EmptyCart'] = 'A kosár üres.';
        $strings['BuyCredits'] = 'Egység vásárlása';
        $strings['CreditsPurchased'] = 'egység megvásárolva.';
        $strings['ViewYourCredits'] = 'Egységek megtekintése';
        $strings['TryAgain'] = 'Újra próbál';
        $strings['PurchaseFailed'] = 'Probléma a fizetés közben.';
        $strings['NoteCreditsPurchased'] = 'Egység megvásárolva';
        $strings['CreditsUpdatedLog'] = 'Egység frissítve %s által';
        $strings['ReservationCreatedLog'] = 'Foglalás létrehozva. Referenciaszám %s';
        $strings['ReservationUpdatedLog'] = 'Foglalás frissítve. Referenciaszám %s';
        $strings['ReservationDeletedLog'] = 'Foglalás törölve. Referenciaszám %s';
        $strings['BuyMoreCredits'] = 'További egység vásárlása';
        $strings['Transactions'] = 'Tranzakciók';
        $strings['Cost'] = 'Költség';
        $strings['PaymentGateways'] = 'Fizető átjárók';
        $strings['CreditHistory'] = 'Egység történet';
        $strings['TransactionHistory'] = 'Tranzakciós történet';
        $strings['Date'] = 'Dátum';
        $strings['Note'] = 'Megjegyzés';
        $strings['CreditsBefore'] = 'Egységek megelőzően';
        $strings['CreditsAfter'] = 'Egységek utána';
        $strings['TransactionFee'] = 'Tranzakciós díj';
        $strings['InvoiceNumber'] = 'Számla sorszáma';
        $strings['TransactionId'] = 'Tranzakció azonosítója';
        $strings['Gateway'] = 'Átjáró';
        $strings['GatewayTransactionDate'] = 'Átjáró tranzakciós dátuma';
        $strings['Refund'] = 'Visszatérítés';
        $strings['IssueRefund'] = 'Jóváírás indítása';
        $strings['RefundIssued'] = 'Jóváírás sikeresen kiutalva';
        $strings['RefundAmount'] = 'Jóváírás összege';
        $strings['AmountRefunded'] = 'Jóváírva';
        $strings['FullyRefunded'] = 'Teljesen jóváírva';
        $strings['YourCredits'] = 'Az ön rendelkezésre álló egységei';
        $strings['PayWithCard'] = 'Fizetés kártyával';
        $strings['or'] = 'vagy';
        $strings['CreditsRequired'] = 'Egység szükséges';
        $strings['AddToGoogleCalendar'] = 'Hozzáadás a Google-hoz';
        $strings['Image'] = 'Kép';
        $strings['ChooseOrDropFile'] = 'Válasszon egy fájlt, vagy húzza ide';
        $strings['SlackBookResource'] = 'Foglaljon %s most';
        $strings['SlackBookNow'] = 'Foglalás most';
        $strings['SlackNotFound'] = 'Nem található elem ezzel a névvel. Foglalás most egy új foglalást indít.';
        $strings['AutomaticallyAddToGroup'] = 'Automatikusan adja hozzá az új felhasználókat ehhez a csoporthoz';
        $strings['GroupAutomaticallyAdd'] = 'Automatikus hozzáadás';
        $strings['TermsOfService'] = 'Felhasználási feltételek';
        $strings['EnterTermsManually'] = 'Feltételek manuális hozzáadása';
        $strings['LinkToTerms'] = 'Link a feltételekhez';
        $strings['UploadTerms'] = 'Feltételek feltöltése';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Tegye kötelezővé a felhasználási feltételek elfogadását';
        $strings['UponReservation'] = 'Foglaláskor';
        $strings['UponRegistration'] = 'Regisztráláskor';
        $strings['ViewTerms'] = 'View Felhasználási feltételek';
        $strings['IAccept'] = 'Elfogadom';
        $strings['TheTermsOfService'] = 'a felhasználási feltételeket';
        $strings['DisplayPage'] = 'Oldal megtekintése';
        $strings['AvailableAllYear'] = 'Egész évben';
        $strings['Availability'] = 'Elérhető';
        $strings['AvailableBetween'] = 'Elérhető az alábbi időpontok között';
        $strings['ConcurrentYes'] = 'Az elemet egynél több személy is foglalhatja';
        $strings['ConcurrentNo'] = 'Az elem nem foglalható egynél több személynél';
        $strings['ScheduleAvailabilityEarly'] = ' Ez az elem jelenleg nem elérhető. Elérhető';
        $strings['ScheduleAvailabilityLate'] = 'Ez a beosztás többé nem elérhető. Elérhető';
        $strings['ResourceImages'] = 'Elemhez tartozó képek';
        $strings['FullAccess'] = 'Teljes elérhetőség';
        $strings['ViewOnly'] = 'Megtekintés csak';
        $strings['Purge'] = 'Megsemmisétés';
        $strings['UsersWillBeDeleted'] = 'felhasználók törlésre kerülnek';
        $strings['BlackoutsWillBeDeleted'] = 'üzemszünet időpontjai törölve lesznek';
        $strings['ReservationsWillBePurged'] = 'foglalások törlésre kerülnek';
        $strings['ReservationsWillBeDeleted'] = 'foglalások törlésre kerülnek';
        $strings['PermanentlyDeleteUsers'] = 'Véglegesen törölje a felhasználókat akik nem jelentkeztek be az alábbi ideje';
        $strings['DeleteBlackoutsBefore'] = 'Üzemszünetek törlése mielőtt';
        $strings['DeletedReservations'] = 'Törölt foglalások';
        $strings['DeleteReservationsBefore'] = 'Foglalások törlése mielőtt';
        $strings['SwitchToACustomLayout'] = 'Egy egyedi elrendezésre váltás';
        $strings['SwitchToAStandardLayout'] = 'Alap elrendezésre váltás';
        $strings['ThisScheduleUsesACustomLayout'] = 'Ez a beosztás egyedi elrendezést használ';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Ez a beisztás alap elrendezést használ';
        $strings['SwitchLayoutWarning'] = 'Biztos benne, hogy lecseréli az elrendezés fajtáját? Ezzel eltávolításra kerül minden rekesz.';
        $strings['DeleteThisTimeSlot'] = 'Törli ezt az idő rekeszt?';
        $strings['Refresh'] = 'Frissít';
        $strings['ViewReservation'] = 'Foglalás megtekintése';
        $strings['PublicId'] = 'Nyilvános azonosító';
        $strings['Public'] = 'Nyilvános';
        $strings['AtomFeedTitle'] = '%s foglalások';
        $strings['DefaultStyle'] = 'Alapértelmezett stílus';
        $strings['Standard'] = 'Alap';
        $strings['Wide'] = 'Széles';
        $strings['Tall'] = 'Magas';
        $strings['EmailTemplate'] = 'E-mail sablonja';
        $strings['SelectEmailTemplate'] = 'E-mail sablon kiválasztása';
        $strings['ReloadOriginalContents'] = 'Eredeti tartalom újratöltése';
        $strings['UpdateEmailTemplateSuccess'] = 'Frissített e-mai sablon';
        $strings['UpdateEmailTemplateFailure'] = 'E-mail sablon nem frissíthető. Ellenőrízze, hogy a mappa írható-e.';
        $strings['BulkResourceDelete'] = 'Tömeges elem törlés';
        $strings['NewVersion'] = 'Új verzió!';
        $strings['WhatsNew'] = 'Mi változott?';
        $strings['OnlyViewedCalendar'] = 'Ez a beosztás csak naptár nézetben megtekinhető';
        $strings['Grid'] = 'Rács';
        $strings['List'] = 'Lista';
        $strings['NoReservationsFound'] = 'Fogalás nem található';
        $strings['EmailReservation'] = 'E-mail foglalások';
        $strings['AdHocMeeting'] = 'Ad hoc Találkozó';
        $strings['NextReservation'] = 'Következő foglalás';
        $strings['MissedCheckin'] = 'Elszalasztott bejelentkezés';
        $strings['MissedCheckout'] = 'Elszalasztott kijelentkezés';
        $strings['Utilization'] = 'Felhasználás';
        $strings['SpecificTime'] = 'Megadott időpont';
        $strings['ReservationSeriesEndingPreference'] = 'When my recurring reservation series is ending';
        $strings['NotAttending'] = 'Nem vesz részt';
        $strings['ViewAvailability'] = 'ELérhetőség megtekintése';
        $strings['ReservationDetails'] = 'Foglalás részletei';
        $strings['StartTime'] = 'Kezdési időpont';
        $strings['EndTime'] = 'Befejezés időpontja';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'LibreBooking telepítése';
        $strings['IncorrectInstallPassword'] = 'Sajnáljuk, a jelszó nem megfelelő.';
        $strings['SetInstallPassword'] = 'Meg kell adnia egy jelszót, mielőtt a telepítő lefut.';
        $strings['InstallPasswordInstructions'] = 'In %s kérjük adjon meg egy jelszót %s egy jelszót, amely véletlen szerű és nehezen kitalálható, majd térjen vissza erre az oldalra.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'LibreBooking aktuális. Nincs szükség frissítésre.';
        $strings['ProvideInstallPassword'] = 'Kérjük, adja meg a telepítői jelszavát.';
        $strings['InstallPasswordLocation'] = 'Ez megtalálható %s in %s.';
        $strings['VerifyInstallSettings'] = 'Erősítse meg az alapértelmezett beállításokat, mielőtt folytatjuk. Később itt megváltoztathatja %s.';
        $strings['DatabaseName'] = 'Adatbázis neve';
        $strings['DatabaseUser'] = 'Adatbázis felhasználója';
        $strings['DatabaseHost'] = 'Adatbázis kiszolgálója';
        $strings['DatabaseCredentials'] = 'Adjon meg egy MySQL hozzáférést egy olyan felhasználóhoz, aki rendelkezik táblázat létrehozásához szükséges jogokkal . Ha nem ismeri, lépjen kapcsolatba adminisztrátorával. Sok esetben a root működhet.';
        $strings['MySQLUser'] = 'MySQL felhasználó';
        $strings['InstallOptionsWarning'] = 'A következő opciók valószínűleg nem fognak működik egy kiszolgált környezetben. Ha kiszolgálói környezetben telepít, használjon egy MySQL varázslót, hogy befejezze ezeket a lépéseket.';
        $strings['CreateDatabase'] = 'Adatbázis létrehozása';
        $strings['CreateDatabaseUser'] = 'Adatbázis felhasználó létrehozása';
        $strings['PopulateExampleData'] = 'Minta adatok importálása. Admin fiók létrehozása: admin/jelszó és felhasználói fiók: felhasználó/jelszó';
        $strings['DataWipeWarning'] = 'Figyelem: ezzel tölődik minden létező adat';
        $strings['RunInstallation'] = 'Telepítés futtatása';
        $strings['UpgradeNotice'] = 'Az alábbi verzióról frissít <b>%s</b> az alábbira <b>%s</b>';
        $strings['RunUpgrade'] = 'Frissíés futtatása';
        $strings['Executing'] = 'Végrehajtás';
        $strings['StatementFailed'] = 'Sikertelen. Részletek:';
        $strings['SQLStatement'] = 'SQL nyilatkozat:';
        $strings['ErrorCode'] = 'Hibakód:';
        $strings['ErrorText'] = 'Hiba szövege:';
        $strings['InstallationSuccess'] = 'A telepítés sikeresen végrehajtva!';
        $strings['RegisterAdminUser'] = 'Regisztálja adminisztrátor felhasználóját. Erre akkor van szükség, ha nem importál minta adatokat. Ensure that $conf[\'settings\'][\'allow.self.registration\'] = \'true\' in your %s file.';
        $strings['LoginWithSampleAccounts'] = 'Ha importálta a mintaadatokat, bejelentkezhet admin/jelszó használatával vagy felhasználó/jelszó használatával általános felhasználóhoz.';
        $strings['InstalledVersion'] = 'A következő verzióval használja a LibreBooking %s ';
        $strings['InstallUpgradeConfig'] = 'Ajánlott a konfigurációs fájl frissítése';
        $strings['InstallationFailure'] = 'Problémák voltak a telepítéssel.  Javítsa őket majd próbálja újra a telepítést.';
        $strings['ConfigureApplication'] = 'LibreBooking beállítása';
        $strings['ConfigUpdateSuccess'] = 'A konfigurációs fájl már aktuális!';
        $strings['ConfigUpdateFailure'] = 'Nem tudjuk automatikusan frissíteni a konfigurációs fájlt. Kérjük, írja felül a config.php tartalmát a következőekkel:';
        $strings['ScriptUrlWarning'] = 'Az Ön <em>script.url</em> valószínűleg nem helyes. Jelenleg <strong>%s</strong>, úgy gondoljuk <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Nem találjuk a felhasználónevét vagy jelszavát';
        $strings['ReservationFailed'] = 'Foglalása nem hozható létre';
        $strings['MinNoticeError'] = 'Ez a foglalás előzetes jelenkezést igényel. A legkorábbi dátum és idő, amig foglalható %s.';
        $strings['MinNoticeErrorUpdate'] = 'Ez a foglalás előzetes jelenkezést igényel. Fogalások %s előtt nem változtathatók meg.';
        $strings['MinNoticeErrorDelete'] = 'Ezen foglalás törlése előzetes értesítést igényel. Foglalások %s előtt nem törölhetők.';
        $strings['MaxNoticeError'] = 'This reservation cannot be made this far in the future. A legkésőbbi dátum és időpont, ami foglalható %s.';
        $strings['MinDurationError'] = 'A foglalás időtartama legalább %s.';
        $strings['MaxDurationError'] = 'A foglalás nem tarthat tovább, mint %s.';
        $strings['ConflictingAccessoryDates'] = 'Nincs elegendő mennyiség az alábbi kiegészítőkből:';
        $strings['NoResourcePermission'] = 'Nincs megfeleő joga hozzáférni egy vagy több elemhez.';
        $strings['ConflictingReservationDates'] = 'Ütköző fogalások vannak az alábbi dátumokon:';
        $strings['StartDateBeforeEndDateRule'] = 'A kezdés dátuma és időpontja a befejezés dátumának és időpontjának előtt kell, hogy legyen.';
        $strings['StartIsInPast'] = 'A kezdés dátuma és időpontja nem lehet a múltban.';
        $strings['EmailDisabled'] = 'Az admin letiltotta az e-mail értesítőket.';
        $strings['ValidLayoutRequired'] = 'A rekeszeket egész napra be kell osztani.';
        $strings['CustomAttributeErrors'] = 'Problémák adódtak az Ön által hozzáadot jellemzőkkel:';
        $strings['CustomAttributeRequired'] = '%s egy kötelező mező.';
        $strings['CustomAttributeInvalid'] = 'A megadott érték %s helytelen.';
        $strings['AttachmentLoadingError'] = 'Sajnáljuk, probléma adódott a kért fájl betöltésével.';
        $strings['InvalidAttachmentExtension'] = 'Csak az alábbi tipusú fájlok feltölthetőek: %s';
        $strings['InvalidStartSlot'] = 'Az igényelt kezdési détum és idő nem érvényes.';
        $strings['InvalidEndSlot'] = 'Az igényelt befejezési détum és idő nem érvényes.';
        $strings['MaxParticipantsError'] = '%s csak alábbi mennyiségű %s résztvevőt támogat.';
        $strings['ReservationCriticalError'] = 'Kritikus hiba történt a foglalás rögzítésekor. Amennyiben újra előfordul, lépjen kapcsolatba adminisztrátorával.';
        $strings['InvalidStartReminderTime'] = 'A kezdő időpont emlékeztetője nem érvényes.';
        $strings['InvalidEndReminderTime'] = 'A befejező időpont emlékeztetője nem érvényes.';
        $strings['QuotaExceeded'] = 'A kvóta limit meghaladva.';
        $strings['MultiDayRule'] = '%s nem fogad foglalást az alábbi napokon.';
        $strings['InvalidReservationData'] = 'Probléma adódott az igényelt foglalásával.';
        $strings['PasswordError'] = 'A jelszónak tartalmaznia kell %s betűt és %s számot.';
        $strings['PasswordErrorRequirements'] = 'A jelszónak tartalmaznia kell %s nagy és kisbetűt valamint %s számot.';
        $strings['NoReservationAccess'] = 'Nioncs jogosultsága megváltoztatni ezt a foglalást.';
        $strings['PasswordControlledExternallyError'] = 'Jelszavát egy külső rendszer kezeli, így innen nem frissíthető.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'A kiegészítő %s csak az alábbi elemekkel együtt fogalható %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Legalább %s db-ot foglalnia kell az alábbi kiegészítőből %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Nem foglalható több, mint %s az alábbi kiegészítőből %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'A kiegészítő \'%s\' nem foglalható az igényelt elemmel';
        $strings['NoResources'] = 'Nem adott hozzá egy elemet sem.';
        $strings['ParticipationNotAllowed'] = 'Nincs jogosultsága csatlakozni ehhez a foglaláshoz.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Ez a foglalás nem jelentkeztethető be.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Ez a foglalás nem jelentkeztethető ki.';
        $strings['InvalidEmailDomain'] = 'Ez az e-mail nem engedélyezet domainről érkezett';
        $strings['TermsOfServiceError'] = 'El kell fogadnia a Felhasználási Feltételeket';
        $strings['UserNotFound'] = 'Ez a felhasználó nem található';
        $strings['ScheduleAvailabilityError'] = 'Ez a beosztás elérhető %s és %s között';
        $strings['ReservationNotFoundError'] = 'Foglalás nem található';
        $strings['ReservationNotAvailable'] = 'Fogalás nem elérhető';
        $strings['TitleRequiredRule'] = 'Foglalás megnevezése szükséges';
        $strings['DescriptionRequiredRule'] = 'Foglalás leírása szükséges';
        $strings['WhatCanThisGroupManage'] = 'Mit kezelhet ez a csoport?';
        $strings['ReservationParticipationActivityPreference'] = 'Ha valaki csatlakozik, vagy elhagyja a fogalásaimat';
        $strings['RegisteredAccountRequired'] = 'Csak regisztrált felhasználók foglalhatnak';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Fogalás létrehozása';
        $strings['EditReservation'] = 'Foglalás frissíése';
        $strings['LogIn'] = 'Bejelentkezés';
        $strings['ManageReservations'] = 'Foglalások';
        $strings['AwaitingActivation'] = 'Várakozás az aktiválásra';
        $strings['PendingApproval'] = 'Függő engedélyezés';
        $strings['ManageSchedules'] = 'Beosztások';
        $strings['ManageResources'] = 'Elemek';
        $strings['ManageAccessories'] = 'Kiegészítők';
        $strings['ManageUsers'] = 'Felhasználók';
        $strings['ManageGroups'] = 'Csoportok';
        $strings['ManageQuotas'] = 'Kvóták';
        $strings['ManageBlackouts'] = 'Üzemszünetek';
        $strings['MyDashboard'] = 'Az én irányítópultom';
        $strings['ServerSettings'] = 'Kiszolgáló beállításai';
        $strings['Dashboard'] = 'Irányítópult';
        $strings['Help'] = 'Segítség';
        $strings['Administration'] = 'Adminisztrációk';
        $strings['About'] = 'Rólunk';
        $strings['Bookings'] = 'Foglalások';
        $strings['Schedule'] = 'Beosztás';
        $strings['Account'] = 'Fiók';
        $strings['EditProfile'] = 'Profil szerkesztése';
        $strings['FindAnOpening'] = 'Nyitás keresése';
        $strings['OpenInvitations'] = 'Meghívások megnyitása';
        $strings['ResourceCalendar'] = 'Elemek naptára';
        $strings['Reservation'] = 'Új foglalás';
        $strings['Install'] = 'Telepítés';
        $strings['ChangePassword'] = 'Jelszó megváltoztatása';
        $strings['MyAccount'] = 'Fiókom';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Jelentkezés kezelése';
        $strings['ForgotPassword'] = 'Jelszó elfelejtése';
        $strings['NotificationPreferences'] = 'Értesítések kezelése';
        $strings['ManageAnnouncements'] = 'Bejelentések';
        $strings['Responsibilities'] = 'Felelősségek';
        $strings['GroupReservations'] = 'Csoport foglalások';
        $strings['ResourceReservations'] = 'Elem foglalások';
        $strings['Customization'] = 'Testreszabás';
        $strings['Attributes'] = 'Jellemzők';
        $strings['AccountActivation'] = 'Fiók aktiválás';
        $strings['ScheduleReservations'] = 'Beosztás fogalások';
        $strings['Reports'] = 'Jelentések';
        $strings['GenerateReport'] = 'Új jelentés készítése';
        $strings['MySavedReports'] = 'Mentett jelentéseim';
        $strings['CommonReports'] = 'Közös jelentések';
        $strings['ViewDay'] = 'Nap megtekintése';
        $strings['Group'] = 'Csoport';
        $strings['ManageConfiguration'] = 'Jelentkezés beállítása';
        $strings['LookAndFeel'] = 'Kinézet és érzet';
        $strings['ManageResourceGroups'] = 'Elem csoportok';
        $strings['ManageResourceTypes'] = 'Elem fajtái';
        $strings['ManageResourceStatus'] = 'Elem állapotok';
        $strings['ReservationColors'] = 'Foglalások színei';
        $strings['SearchReservations'] = 'Foglalás keresése';
        $strings['ManagePayments'] = 'Kifizetések';
        $strings['ViewCalendar'] = 'Naptár megtekintése';
        $strings['DataCleanup'] = 'Adat törlés';
        $strings['ManageEmailTemplates'] = 'E-mail sablonok kezelése';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'Va';
        $strings['DayMondaySingle'] = 'Hé';
        $strings['DayTuesdaySingle'] = 'Ke';
        $strings['DayWednesdaySingle'] = 'Sze';
        $strings['DayThursdaySingle'] = 'Cs';
        $strings['DayFridaySingle'] = 'Pé';
        $strings['DaySaturdaySingle'] = 'Szo';

        $strings['DaySundayAbbr'] = 'Vas';
        $strings['DayMondayAbbr'] = 'Hét';
        $strings['DayTuesdayAbbr'] = 'Kedd';
        $strings['DayWednesdayAbbr'] = 'Sze';
        $strings['DayThursdayAbbr'] = 'Csü';
        $strings['DayFridayAbbr'] = 'Pén';
        $strings['DaySaturdayAbbr'] = 'Szo';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Foglalása elfogadásra került';
        $strings['ReservationCreatedSubject'] = 'Fogalása létrehozva';
        $strings['ReservationUpdatedSubject'] = 'Foglalása frissítve';
        $strings['ReservationDeletedSubject'] = 'Fogalása eltávolítva';
        $strings['ReservationCreatedAdminSubject'] = 'Értesítés: Egy foglalás létrehozva';
        $strings['ReservationUpdatedAdminSubject'] = 'Értesíés: Egy foglalás frissítésre került';
        $strings['ReservationDeleteAdminSubject'] = 'Értesítés: Egy foglalás eltávolítva';
        $strings['ReservationApprovalAdminSubject'] = 'Értesítés: Foglalás az engedélyét igényli';
        $strings['ParticipantAddedSubject'] = 'Foglalás résztvétel értesítés';
        $strings['ParticipantDeletedSubject'] = 'Foglalás eltávolítva';
        $strings['InviteeAddedSubject'] = 'Foglalás meghívás';
        $strings['ResetPasswordRequest'] = 'Jelszó visszaállítás igény';
        $strings['ActivateYourAccount'] = 'Kérjük, aktiválja fiókját';
        $strings['ReportSubject'] = 'Igényel jelentése (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Foglalása %s hamarosan kezdődik';
        $strings['ReservationEndingSoonSubject'] = 'Foglalása %s hamarosan befejeződik';
        $strings['UserAdded'] = 'Egy felhasználó hozzáadásra került';
        $strings['UserDeleted'] = 'Felhasználói fiók %s törlésre került %s által';
        $strings['GuestAccountCreatedSubject'] = '%s fiókjának részletei';
        $strings['AccountCreatedSubject'] = 'Fiókja %s részletei';
        $strings['InviteUserSubject'] = '%s meghívta, hogy csatlakozzon ehhez: %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Foglalása elfogadásra került ehhez: %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Foglalása ehhez: %s létrehozva';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Foglalása ehhez: %s frissítésre került';
        $strings['ReservationDeletedSubjectWithResource'] = 'Foglalás eltávolítva %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Értesítés: Reservation Created for %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Értesítés: Reservation Updated for %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Értesítés: Reservation Removed for %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Értesítés: Reservation for %s Requires Your Approval';
        $strings['ParticipantAddedSubjectWithResource'] = '%s hozzáadva a foglalásához %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s eltávolította foglalását %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s meghívta foglalására %s';
        $strings['MissedCheckinEmailSubject'] = 'Elszalasztotta bejelentkezését ehhez: %s';
        $strings['ReservationShareSubject'] = '%s megosztott egy foglalást %s';
        $strings['ReservationSeriesEndingSubject'] = 'Foglalás sorozata %s részére végződik %s -kor';
        $strings['ReservationParticipantAccept'] = '%s Elfogadta meghívását az alábbi foglalásra %s %s -kor';
        $strings['ReservationParticipantDecline'] = '%s elutasította meghívását %s %s -kor';
        $strings['ReservationParticipantJoin'] = '%s Csatlakozott foglalására for %s on %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nincsenek korábbi foglalásai';
        $strings['PastReservations'] = 'Korábbi foglalások';
        $strings['AllNoPastReservations'] = 'Az elmúlt %s napban nincsenek korábbi foglalásai';
        $strings['AllPastReservations'] = 'Az összes korábbi foglalás';
        $strings['Yesterday'] = 'Tegnap';
        $strings['EarlierThisWeek'] = 'Korábban ezen a héten';
        $strings['PreviousWeek'] = 'Előző hét';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'A csoportodnak nincs következő foglalása';
        $strings['GroupUpcomingReservations'] = 'Csoportom(aim) következő foglalásai';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Hiba történt a bejelentkezés során a Facebookkal. Kérjük, próbálja újra.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nincsenek függő jóváhagyásra váró foglalásai';
        $strings['PendingApprovalReservations'] = 'Jóváhagyásra váró foglalások';
        $strings['LaterThisMonth'] = 'Később ebben a hónapban';
        $strings['LaterThisYear'] = 'Később idén';
        $strings['Remaining'] = 'Megmaradt';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Nincsenek hiányzó kijelentkezési foglalások';
        $strings['MissingCheckOutReservations'] = 'Hiányzó kijelentkezési foglalások';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'A foglalás részleteit nem lehet látni, mert nincs jogosultságod egyik erőforráshoz sem ebben a foglalásban';
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
        $days = [];

        /***
         * DAY NAMES
         * All of these arrays MUST start with Sunday as the first element
         * and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'];
        // The three letter abbreviation
        $days['abbr'] = ['Vas', 'Hét', 'Kedd', 'Sze', 'Csü', 'Pén', 'Szo'];
        // The two letter abbreviation
        $days['two'] = ['Va', 'hé', 'Ke', 'Sze', 'Cs', 'Pé', 'Va'];
        // The one letter abbreviation
        $days['letter'] = ['V', 'H', 'K', 'Sze', 'Cs', 'P', 'Szo'];

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
        $months['full'] = ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Jólius', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Már', 'Ápr', 'Máj', 'Jún', 'Júl', 'Aug', 'Szep', 'Okt', 'Nov', 'Dec'];

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
        return 'hu';
    }
}
