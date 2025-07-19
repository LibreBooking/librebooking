<?php

require_once('Language.php');
require_once('en_gb.php');

class fi_fi extends en_gb
{
    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Etunimi';
        $strings['LastName'] = 'Sukunimi';
        $strings['Timezone'] = 'Aikavyöhyke';
        $strings['Edit'] = 'Muokkaa';
        $strings['Change'] = 'Vaihda';
        $strings['Rename'] = 'Nimeä uudelleen';
        $strings['Remove'] = 'Poista';
        $strings['Delete'] = 'Poista';
        $strings['Update'] = 'Päivitä';
        $strings['Cancel'] = 'Peruuta';
        $strings['Add'] = 'Lisää';
        $strings['Name'] = 'Nimi';
        $strings['Yes'] = 'Kyllä';
        $strings['No'] = 'Ei';
        $strings['FirstNameRequired'] = 'Etunimi vaaditaan.';
        $strings['LastNameRequired'] = 'Sukunimi vaaditaan.';
        $strings['PwMustMatch'] = 'Salasanan varmistuksen täytyy vastata salasanaan.';
        $strings['PwComplexity'] = 'Salasanan täytyy olla ainakin 6 merkkiä pitkä.';
        $strings['ValidEmailRequired'] = 'Voimassa oleva email osoite vaaditaan.';
        $strings['UniqueEmailRequired'] = 'Kyseisellä sähköpostiosoitteella on jo rekisteröidytty.';
        $strings['UniqueUsernameRequired'] = 'Käyttäjänimi on jo käytössä.';
        $strings['UserNameRequired'] = 'Käyttäjänimi vaaditaan.';
        $strings['CaptchaMustMatch'] = 'Ole hyvä ja kirjoita kuvakentässä olevat kirjaimet.';
        $strings['Today'] = 'Tänään';
        $strings['Week'] = 'Viikko';
        $strings['Month'] = 'Kuukausi';
        $strings['BackToCalendar'] = 'Takaisin kalenteriin';
        $strings['BeginDate'] = 'Alkaa';
        $strings['EndDate'] = 'Loppuu';
        $strings['Username'] = 'Käyttäjänimi';
        $strings['Password'] = 'Salasana';
        $strings['PasswordConfirmation'] = 'Varmista salasana';
        $strings['DefaultPage'] = 'Aloitussivu';
        $strings['MyCalendar'] = 'Oma kalenteri';
        $strings['ScheduleCalendar'] = 'Varauskalenteri';
        $strings['Registration'] = 'Rekisteröityminen';
        $strings['NoAnnouncements'] = 'Ei uusia ilmoituksia';
        $strings['Announcements'] = 'Ilmoitukset';
        $strings['NoUpcomingReservations'] = 'Sinulla ei ole tulossa olevia varauksia';
        $strings['UpcomingReservations'] = 'Tulossa olevat varaukset';
        $strings['AllNoUpcomingReservations'] = 'Seuraaville %s päivälle ei ole varauksia';
        $strings['AllUpcomingReservations'] = 'Kaikki tulossa olevat varaukset';
        $strings['ShowHide'] = 'Näytä/Piilota';
        $strings['Error'] = 'Virhe';
        $strings['ReturnToPreviousPage'] = 'Palaa edelliselle sivulle';
        $strings['UnknownError'] = 'Tuntematon virhe';
        $strings['InsufficientPermissionsError'] = 'Sinulla ei ole pääsyä tälle alueelle';
        $strings['MissingReservationResourceError'] = 'Resurssia ei valittu';
        $strings['MissingReservationScheduleError'] = 'Varausaikaa ei valittu';
        $strings['DoesNotRepeat'] = 'Ei toistuva';
        $strings['Daily'] = 'Päivittäinen';
        $strings['Weekly'] = 'Viikoittainen';
        $strings['Monthly'] = 'Kuukausittainen';
        $strings['Yearly'] = 'Vuosittainen';
        $strings['RepeatPrompt'] = 'Toistuva';
        $strings['hours'] = 'tunnit';
        $strings['days'] = 'päivät';
        $strings['weeks'] = 'viikot';
        $strings['months'] = 'kuukaudet';
        $strings['years'] = 'vuodet';
        $strings['day'] = 'päivä';
        $strings['week'] = 'viikko';
        $strings['month'] = 'kuukausi';
        $strings['year'] = 'vuosi';
        $strings['repeatDayOfMonth'] = 'kuukaudenpäivä';
        $strings['repeatDayOfWeek'] = 'viikonpäivä';
        $strings['RepeatUntilPrompt'] = 'Asti';
        $strings['RepeatEveryPrompt'] = 'Joka';
        $strings['RepeatDaysPrompt'] = '';
        $strings['CreateReservationHeading'] = 'Tee uusi varaus';
        $strings['EditReservationHeading'] = 'Muokkaa varauksia';
        $strings['ViewReservationHeading'] = 'Katso varauksia';
        $strings['ReservationErrors'] = 'Vaihda varausta';
        $strings['Create'] = 'Luo';
        $strings['ThisInstance'] = 'Vain tämä kerta';
        $strings['AllInstances'] = 'Joka kerta';
        $strings['FutureInstances'] = 'Tulossa olevat kerrat';
        $strings['Print'] = 'Tulosta';
        $strings['ShowHideNavigation'] = 'Näytä/piilota navigointi';
        $strings['ReferenceNumber'] = 'Varausnumero';
        $strings['Tomorrow'] = 'Huomenna';
        $strings['LaterThisWeek'] = 'Myöhemmin tällä viikolla';
        $strings['NextWeek'] = 'Seuraava viikko';
        $strings['SignOut'] = 'Kirjaudu ulos';
        $strings['LayoutDescription'] = 'Alkaa %s, näyttäen %s päivää kerralla';
        $strings['AllResources'] = 'Kaikki resurssit';
        $strings['TakeOffline'] = 'Sulje';
        $strings['BringOnline'] = 'Aloita';
        $strings['AddImage'] = 'Lisää kuva';
        $strings['NoImage'] = 'Ei kuvaa määriteltynä';
        $strings['Move'] = 'Siirrä';
        $strings['AppearsOn'] = 'Näkyy kohteessa %s';
        $strings['Location'] = 'Paikka';
        $strings['NoLocationLabel'] = '(paikkaa ei määritely)';
        $strings['Contact'] = 'Yhteystiedot';
        $strings['NoContactLabel'] = '(ei yhtystietoja)';
        $strings['Description'] = 'Kuvaus';
        $strings['NoDescriptionLabel'] = '(ei kuvausta)';
        $strings['Notes'] = 'Huomioitavaa';
        $strings['NoNotesLabel'] = '(ei huomioitavaa)';
        $strings['NoTitleLabel'] = '(ei otsikkoa)';
        $strings['UsageConfiguration'] = 'Käyttöasetukset';
        $strings['ChangeConfiguration'] = 'Vaihda asetuksia';
        $strings['ResourceMinLength'] = 'Varauksien pitää kestää vähintään %s';
        $strings['ResourceMinLengthNone'] = 'Varauksella ei ole vähittäiskestoa';
        $strings['ResourceMaxLength'] = 'Varaukset eivät voi kestää pitempään kuin %s';
        $strings['ResourceMaxLengthNone'] = 'Varauksilla ei ole maksimikestoa';
        $strings['ResourceRequiresApproval'] = 'Varaukset täytyy hyväksyä';
        $strings['ResourceRequiresApprovalNone'] = 'Varaukset eivät vaadi hyväksyntää';
        $strings['ResourcePermissionAutoGranted'] = 'Oikeudet hyväksytään automaattisesti';
        $strings['ResourcePermissionNotAutoGranted'] = 'Oikeudet myönnetään automaattisesti';
        $strings['ResourceMinNotice'] = 'Varaukset täytyy tehdä ennen %s aloitusaikaa';
        $strings['ResourceMinNoticeNone'] = 'Varauksia voidaan tehdä tähän aikaan asti';
        $strings['ResourceMinNoticeUpdate'] = 'Varaukset täytyy päivittää vähintään %s ennen alkamisaikaa';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Varauksia voi päivittää tähän hetkeen saakka';
        $strings['ResourceMinNoticeDelete'] = 'Varaukset täytyy poistaa vähintään %s ennen alkamisaikaa';
        $strings['ResourceMinNoticeNoneDelete'] = 'Varauksia voi poistaa tähän hetkeen saakka';
        $strings['ResourceMaxNotice'] = 'Varaukset eivät voi loppua %s enempää tästä hetkestä katsoen';
        $strings['ResourceMaxNoticeNone'] = 'Varaukset voivat loppua milloin vain tulevaisuudessa';
        $strings['ResourceBufferTime'] = 'Varausten välillä täytyy olla %s';
        $strings['ResourceBufferTimeNone'] = 'Varausten väliin ei tarvitse jättää tyhjää';
        $strings['ResourceAllowMultiDay'] = 'Varauksia voi tehdä useiksi päiviksi';
        $strings['ResourceNotAllowMultiDay'] = 'Varauksia ei voi tehdä yön ajaksi';
        $strings['ResourceCapacity'] = 'Tällä resurssilla on kapasiteetti %s henkilölle';
        $strings['ResourceCapacityNone'] = 'Tällä resurssilla on rajoittamaton kapasiteetti';
        $strings['AddNewResource'] = 'Lisää uusi resurssi';
        $strings['AddNewUser'] = 'Lisää uusi käyttäjä';
        $strings['AddUser'] = 'Lisää käyttäjä';
        $strings['Schedule'] = 'Aikataulu';
        $strings['AddResource'] = 'Lisää resurssi';
        $strings['Capacity'] = 'Kapasiteetti';
        $strings['Access'] = 'Pääsy';
        $strings['Duration'] = 'Kesto';
        $strings['Active'] = 'Aktiivinen';
        $strings['Inactive'] = 'Ei-aktiivinen';
        $strings['ResetPassword'] = 'Vaihda salasana';
        $strings['LastLogin'] = 'Viimeinen kirjautuminen';
        $strings['Search'] = 'Etsi';
        $strings['ResourcePermissions'] = 'Resurssin käyttöoikeidet';
        $strings['Reservations'] = 'Varaukset';
        $strings['Groups'] = 'Ryhmät';
        $strings['Users'] = 'Käyttäjät';
        $strings['AllUsers'] = 'Kaikki käyttäjät';
        $strings['AllGroups'] = 'Kaikki ryhmät';
        $strings['AllSchedules'] = 'Kaikki waulut';
        $strings['UsernameOrEmail'] = 'Käyttäjänimi tai sähköpostiosoite';
        $strings['Members'] = 'Jäsenet';
        $strings['QuickSlotCreation'] = 'Luo kohta jokaiselle %s minuutille välillä %s - %s'; // TODO
        $strings['ApplyUpdatesTo'] = 'Lisää päivitykset';
        $strings['CancelParticipation'] = 'Peruuta osallistuminen';
        $strings['Attending'] = 'Osallistumassa';
        $strings['QuotaConfiguration'] = 'Kohteessa %s %s käyttäjälle %s on rajoitettu %s %s per %s'; // TODO
        $strings['reservations'] = 'varaukset';
        $strings['reservation'] = 'varaus';
        $strings['ChangeCalendar'] = 'Vaihda kalenteria';
        $strings['AddQuota'] = 'Lisää kiintiö';
        $strings['FindUser'] = 'Etsi käyttäjä';
        $strings['Created'] = 'Luotu';
        $strings['LastModified'] = 'Muokattu viimeksi';
        $strings['GroupName'] = 'Ryhmän nimi';
        $strings['GroupMembers'] = 'Ryhmän jäenet';
        $strings['GroupRoles'] = 'Ryhmän roolit';
        $strings['GroupAdmin'] = 'Ryhmän ylläpitäjä';
        $strings['Actions'] = 'Toiminnot';
        $strings['CurrentPassword'] = 'Nykyinen salasana';
        $strings['NewPassword'] = 'Uusi salasana';
        $strings['InvalidPassword'] = 'Väärä salasana';
        $strings['PasswordChangedSuccessfully'] = 'Salasanan vaihto onnistui';
        $strings['SignedInAs'] = 'Kirjautunut käyttäjänä: ';
        $strings['NotSignedIn'] = 'Et ole kirjautunut sisään';
        $strings['ReservationTitle'] = 'Varauksen otsikko';
        $strings['ReservationDescription'] = 'Varauksen kuvaus';
        $strings['ResourceList'] = 'Resurssit varattaviksi';
        $strings['Accessories'] = 'Tarvikkeet';
        $strings['Add'] = 'Lisää';
        $strings['ParticipantList'] = 'Osallistujat';
        $strings['InvitationList'] = 'Kutsutut';
        $strings['AccessoryName'] = 'Tarvike';
        $strings['QuantityAvailable'] = 'Käytettävissä oleva määrä';
        $strings['Resources'] = 'Resurssit';
        $strings['Participants'] = 'Osallistujat';
        $strings['User'] = 'Käyttäjä';
        $strings['Resource'] = 'Resurssi';
        $strings['Status'] = 'Tila';
        $strings['Approve'] = 'Hyväksy';
        $strings['Page'] = 'Sivu';
        $strings['Rows'] = 'Riviä';
        $strings['Unlimited'] = 'Rajoittamaton';
        $strings['Email'] = 'Sähköposti';
        $strings['EmailAddress'] = 'Sähköpostiosoite';
        $strings['Phone'] = 'Puhelin';
        $strings['Organization'] = 'Organisatio';
        $strings['Position'] = 'Rooli';
        $strings['OrganizationId'] = 'Y-tunnus';
        $strings['CreditorId'] = 'Laskutustunnus';
        $strings['StreetAddress'] = 'Katuosoite';
        $strings['ZipCode'] = 'Postinumero';
        $strings['City'] = 'Postitoimipaikka';
        $strings['Language'] = 'Kieli';
        $strings['Permissions'] = 'Oikeudet';
        $strings['Reset'] = 'Palauta';
        $strings['FindGroup'] = 'Etsi ryhmä';
        $strings['Manage'] = 'Hallitse';
        $strings['None'] = 'Ei mitään';
        $strings['AddToOutlook'] = 'Lisää Outlookiin';
        $strings['Done'] = 'Valmis';
        $strings['RememberMe'] = 'Muista minut';
        $strings['FirstTimeUser?'] = 'Uusi käyttäjä?';
        $strings['CreateAnAccount'] = 'Luo tili';
        $strings['ViewSchedule'] = 'Näytä aikataulu';
        $strings['ForgotMyPassword'] = 'Olen unohtanut salasanani';
        $strings['YouWillBeEmailedANewPassword'] = 'Sinulle lähetetään sähköpostilla uusi satunnainen salasana';
        $strings['Close'] = 'Sulje';
        $strings['ExportToCSV'] = 'Vie CSV-muodossa';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Työskennellään...';
        $strings['Login'] = 'Kirjaudu';
        $strings['AdditionalInformation'] = 'Lisätieto';
        $strings['AllFieldsAreRequired'] = 'kaikki kentät vaaditaan';
        $strings['Optional'] = 'valinnainen';
        $strings['YourProfileWasUpdated'] = 'Profiilisi on päivitetty';
        $strings['YourSettingsWereUpdated'] = 'Asetuksesi on päivitetty';
        $strings['Register'] = 'Rekisteröidy';
        $strings['SecurityCode'] = 'Turvakoodi';
        $strings['ReservationCreatedPreference'] = 'Kun teen varauksen tai varaus tehdään puolestani';
        $strings['ReservationUpdatedPreference'] = 'Kun päivitän varauksen tai varaus päivitetään puolestani';
        $strings['ReservationDeletedPreference'] = 'Kun poistan varauksen tai varaus poistetaan minun puolestani';
        $strings['ReservationApprovalPreference'] = 'Kun odottava varaukseni on hyväksytty';
        $strings['PreferenceSendEmail'] = 'Lähetä minulle sähköposti';
        $strings['PreferenceNoEmail'] = 'En tarvite muistutusta';
        $strings['ReservationCreated'] = 'Varauksesi on onnistuneesti luotu!';
        $strings['ReservationUpdated'] = 'Varauksesi on onnistuneesti päivitetty!';
        $strings['ReservationRemoved'] = 'Varauksesi on poistettu';
        $strings['ReservationRequiresApproval'] = 'Yksi tai useampi varauksesi vaatii hyväksynnän. Tämä varaus odottaa kunnes se on hyväksytty.';
        $strings['YourReferenceNumber'] = 'Varauskoodisi on: %s';
        $strings['UpdatingReservation'] = 'Päivitä varaus';
        $strings['ChangeUser'] = 'Vaihda käyttäjä';
        $strings['MoreResources'] = 'Lisää varattavia tiloja';
        $strings['ReservationLength'] = 'Varauksen pituus';
        $strings['ParticipantList'] = 'Osallistujalista';
        $strings['AddParticipants'] = 'Lisää osallistujia';
        $strings['InviteOthers'] = 'Kutsu muita';
        $strings['AddResources'] = 'Lisää tiloja';
        $strings['AddAccessories'] = 'Lisää tarvikkeita';
        $strings['Accessory'] = 'Tarvike';
        $strings['QuantityRequested'] = 'Pyydetty määrä';
        $strings['CreatingReservation'] = 'Varauksen luominen';
        $strings['UpdatingReservation'] = 'Varauksen päivittäminen';
        $strings['DeleteWarning'] = 'Tämä toiminto on pysyvä ja peruuttamaton!';
        $strings['DeleteAccessoryWarning'] = 'Tämän tarvikkeen poistaminen poistaa sen kaikista varauksista.';
        $strings['AddAccessory'] = 'Lisää tarvike';
        $strings['AddBlackout'] = 'Lisää varauksilta suljettu aika';
        $strings['AllResourcesOn'] = 'Kaikki resurssit käytössä';
        $strings['Reason'] = 'Syy';
        $strings['BlackoutShowMe'] = 'Näytä ristiriitaiset varaukset';
        $strings['BlackoutDeleteConflicts'] = 'Poista ristiriidassa olevat varaukset';
        $strings['Filter'] = 'Suodata';
        $strings['Between'] = 'Välillä';
        $strings['CreatedBy'] = 'Tehnyt';
        $strings['BlackoutCreated'] = 'Suljettu aikaväli lisätty!';
        $strings['BlackoutNotCreated'] = 'Suljettua aikaväliä ei voitu luoda!';
        $strings['BlackoutUpdated'] = 'Suljettu aikaväli päivitetty';
        $strings['BlackoutNotUpdated'] = 'Suljettua aikaväliä ei voitu päivittää';
        $strings['BlackoutConflicts'] = 'Ristiriidan aiheuttavat suljetut aikavälit';
        $strings['ReservationConflicts'] = 'Ristiriidassa olevat varaukset';
        $strings['UsersInGroup'] = 'Käyttäjät tässä ryhmässä';
        $strings['Browse'] = 'Selaa';
        $strings['DeleteGroupWarning'] = 'Tämän ryhmän poistaminen poistaa myös kaikki määritellyt resurssioikeudet.  Käyttäjät tässä ryhmässä menettävät oikeudet resursseihin.';
        $strings['WhatRolesApplyToThisGroup'] = 'Mitkä roolit koskevat tätä ryhmää?';
        $strings['WhoCanManageThisGroup'] = 'Kuka voi ylläpitää tätä ryhmää?';
        $strings['AddGroup'] = 'Lisää ryhmä';
        $strings['AllQuotas'] = 'Kaikki kiintiöt';
        $strings['QuotaReminder'] = 'Muista: rajoituksia sovelletaan varauskalenterin aikavyöhykkeen mukaisesti.';
        $strings['AllReservations'] = 'Kaikki varaukset';
        $strings['PendingReservations'] = 'Odottavat varaukset';
        $strings['Approving'] = 'Hyväksytään';
        $strings['MoveToSchedule'] = 'Siirry varauskalenteriin';
        $strings['DeleteResourceWarning'] = 'Tämän resurssin poistaminen poistaa kaikki siihen liittyvät tiedot, mukaanlukien';
        $strings['DeleteResourceWarningReservations'] = 'kaikki siihen liittyvät menneet, nykyiset ja tulevat varaukset';
        $strings['DeleteResourceWarningPermissions'] = 'kaikki käyttöoikeusmääritykset';
        $strings['DeleteResourceWarningReassign'] = 'Määritä mitä et halua poistettavan ennen jatkamista';
        $strings['ScheduleLayout'] = 'Asettelu (kaikki ajat %s)';
        $strings['ReservableTimeSlots'] = 'Varattavat ajankohdat';
        $strings['BlockedTimeSlots'] = 'Estetyt ajankohdat';
        $strings['ThisIsTheDefaultSchedule'] = 'Tämä on oletusvarauskalenteri';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Oletusvarauskalenteria ei voi poistaa';
        $strings['MakeDefault'] = 'Aseta oletukseksi';
        $strings['BringDown'] = 'Tyhjennä';
        $strings['ChangeLayout'] = 'Muuta ulkoasua';
        $strings['AddSchedule'] = 'Lisää kalenteri';
        $strings['StartsOn'] = 'Alkaa';
        $strings['NumberOfDaysVisible'] = 'Näkyvillä olevat päivät';
        $strings['UseSameLayoutAs'] = 'Käytä samaa ulkoasua kuin';
        $strings['Format'] = 'Tyyppi';
        $strings['OptionalLabel'] = 'Valinnainen rivi';
        $strings['LayoutInstructions'] = 'Lisää yksi kohta per rivi. Kohdat täytyy asettaa kaikille 24 tunnille alkaen ja loppuen 12:00.';
        $strings['AddUser'] = 'Lisää käyttäjä';
        $strings['UserPermissionInfo'] = 'Käyttöoikeus resurssiin voi olla erilainen riippuen käyttäjäroolista, ryhmäoikeuksista tai ylimääräisistä käyttöoikeusasetuksista';
        $strings['DeleteUserWarning'] = 'Poistamalla tämän käyttäjän poistat samalla kaikki hänen tulevat, menneet ja nykyiset varauksensa.';
        $strings['AddAnnouncement'] = 'Lisää ilmoitus';
        $strings['Announcement'] = 'Ilmoitus';
        $strings['Priority'] = 'Priotiteetti';
        $strings['Reservable'] = 'Varaamaton';
        $strings['Unreservable'] = 'Ei varattavissa';
        $strings['Reserved'] = 'Varattu';
        $strings['MyReservation'] = 'Minun varaukseni';
        $strings['Pending'] = 'Odottava';
        $strings['Past'] = 'Menneet';
        $strings['Restricted'] = 'Rajoitettu';
        $strings['ViewAll'] = 'Näytä kaikki';
        $strings['MoveResourcesAndReservations'] = 'Siirrä resurssit ja varaukset:';
        $strings['TurnOffSubscription'] = 'Lopeta kalenterin tilaus';
        $strings['TurnOnSubscription'] = 'Salli tämän kalenterin tilaaminen';
        $strings['SubscribeToCalendar'] = 'Tilaa kalenteri';
        $strings['SubscriptionsAreDisabled'] = 'Ylläpitäjä on poistanut tilaukset käytöstä';
        $strings['NoResourceAdministratorLabel'] = '(Ei resurssien ylläpitäjä)';
        $strings['WhoCanManageThisResource'] = 'Kuka ylläpitää tätä resurssia?';
        $strings['ResourceAdministrator'] = 'Resurssin ylläpitäjä';
        $strings['Private'] = 'Yksityinen';
        $strings['Accept'] = 'Hyväksy';
        $strings['Decline'] = 'Hylkää';
        $strings['ShowFullWeek'] = 'Näytä koko viikko';
        $strings['CustomAttributes'] = 'Lisäkentät';
        $strings['AddAttribute'] = 'Lisää kenttä';
        $strings['EditAttribute'] = 'Muokkaa lisäkenttää';
        $strings['DisplayLabel'] = 'Kentän Nimi';
        $strings['Type'] = 'Tyyppi';
        $strings['Required'] = 'Pakollinen';
        $strings['ValidationExpression'] = 'Validointisääntö';
        $strings['PossibleValues'] = 'Sallitut Arvot';
        $strings['SingleLineTextbox'] = 'Yhden Rivin Teksti';
        $strings['MultiLineTextbox'] = 'Monirivinen Teksti';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Valintalista';
        $strings['CommaSeparated'] = 'pilkuilla erotettu';
        $strings['Category'] = 'Kategoria';
        $strings['CategoryReservation'] = 'Varaus';
        $strings['CategoryGroup'] = 'Ryhmä';
        $strings['SortOrder'] = 'Lajittelujärjestys';
        $strings['Title'] = 'Otsikko';
        $strings['AdditionalAttributes'] = 'Lisäkentät';
        $strings['True'] = 'True';
        $strings['False'] = 'False';
        $strings['ForgotPasswordEmailSent'] = 'Annettuun osoitteeseen on lähetetty sähköposti, jossa on ohjeet salasanan resetoimiseksi ';
        $strings['ActivationEmailSent'] = 'Saat pian aktivointisähköpostin.';
        $strings['AccountActivationError'] = 'Sorry, emme voineet aktivoida tiliäsi.';
        $strings['Attachments'] = 'Liitteet';
        $strings['AttachFile'] = 'Liitä Tiedosto';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Ei Kalenterin Ylläpitäjä';
        $strings['ScheduleAdministrator'] = 'Kalenterin Ylläpitäjä';
        $strings['Total'] = 'Yhteensä';
        $strings['QuantityReserved'] = 'Varausten Määrä';
        $strings['AllAccessories'] = 'Kaikki tarvikkeet';
        $strings['GetReport'] = 'Hae Raportti';
        $strings['NoResultsFound'] = 'Haettavia tuloksia ei löytynyt';
        $strings['SaveThisReport'] = 'Tallenna Tämä Raportti';
        $strings['ReportSaved'] = 'Raportti Tallennettu!';
        $strings['EmailReport'] = 'Lähetä Raportti Sähköpostilla';
        $strings['ReportSent'] = 'Raportti lähetty!';
        $strings['RunReport'] = 'Aja Raportti';
        $strings['NoSavedReports'] = 'Sinulla ei ole tallennettuja Raportteja.';
        $strings['CurrentWeek'] = 'Kuluva Viikko';
        $strings['CurrentMonth'] = 'Kuluva Kuukausi';
        $strings['AllTime'] = 'Kaikki Aika';
        $strings['FilterBy'] = 'Suodata';
        $strings['Select'] = 'Valitse';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Aika Yhteensä';
        $strings['Count'] = 'Lukumäärä';
        $strings['Usage'] = 'Käyttö';
        $strings['AggregateBy'] = 'Ryhmitelty';
        $strings['Range'] = 'Raja';
        $strings['Choose'] = 'Valitse';
        $strings['All'] = 'kaikki';
        $strings['ViewAsChart'] = 'Katso Kaaviona';
//
        $strings['ReservedResources'] = 'Varatut Resurssit';
        $strings['ReservedAccessories'] = 'Varatut Tarvikkeet';
        $strings['ResourceUsageTimeBooked'] = 'Resurssien käyttö - Varatut ajat';
        $strings['ResourceUsageReservationCount'] = 'Resurssien käyttö - Varausten lukumäärä';
        $strings['Top20UsersTimeBooked'] = 'Top 20 Käyttäjää - Varatut ajat';
        $strings['Top20UsersReservationCount'] = 'Top 20 Käyttäjää - Varausten lukumäärä';
        $strings['ConfigurationUpdated'] = 'Konfiguraatiotiedosto päivitettiin';
        $strings['ConfigurationUiNotEnabled'] = 'Tälle sivulle ei pääse koska $conf[\'settings\'][\'pages\'][\'enable.configuration\'] on asetettu false:ksi tai puuttuu.';
        $strings['ConfigurationFileNotWritable'] = 'Konfigraatiotiedostoa ei voi kirjoitaaa. Tarkista tiedoston oikeudet ja yritä uudelleen.';
        $strings['ConfigurationUpdateHelp'] = 'Tarkista <a target=_blank href=%s>Help File</a>:n konfiguraatio-osiosta näiden asetusten dokumentaaatio.';
        $strings['GeneralConfigSettings'] = 'asetukset';
        $strings['UseSameLayoutForAllDays'] = 'Käytä samaa layoutia kaikille päiville';
        $strings['LayoutVariesByDay'] = 'Layout on päiväkohtainen';
        $strings['ManageReminders'] = 'Muistutukset';
        $strings['ReminderUser'] = 'Käyttäjä ID';
        $strings['ReminderMessage'] = 'Viesti';
        $strings['ReminderAddress'] = 'Osoitteet';
        $strings['ReminderSendtime'] = 'Lähetysaika';
        $strings['ReminderRefNumber'] = 'Varausnumero';
        $strings['ReminderSendtimeDate'] = 'Muistutuksen Päiväys';
        $strings['ReminderSendtimeTime'] = 'Muistutuksen aika (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Lisää Muistutus';
        $strings['DeleteReminderWarning'] = 'Haluatko varmasti  poistaa tämä?';
        $strings['NoReminders'] = 'Sinulla ei ole tulevia muistutuksia.';
        $strings['Reminders'] = 'Muistutukset';
        $strings['SendReminder'] = 'Lähetä Muistutus';
        $strings['minutes'] = 'minuuttia';
        $strings['hours'] = 'tuntia';
        $strings['days'] = 'päivää';
        $strings['ReminderBeforeStart'] = 'ennen alkuaikaa';
        $strings['ReminderBeforeEnd'] = 'enne loppuaikaa';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Muutoksesi on tallennettu. Lataa sivu uudelleen saadaksesi ne näkyviin';
        $strings['MakeDefaultSchedule'] = 'Tee tästä oletuskalenterini';
        $strings['DefaultScheduleSet'] = 'Tämä on nyt oletuskalenterisi';
        $strings['FlipSchedule'] = 'Käännä kalenterin layout';
        $strings['Next'] = 'Seuraava';
        $strings['Success'] = 'Onnistui';
        $strings['Participant'] = 'Osallistuja';
        $strings['ResourceFilter'] = 'Resurssisuodatin';
        $strings['ResourceGroups'] = 'Resurssiryhmät';
        $strings['AddNewGroup'] = 'Lisää Uusi Ryhmä';
        $strings['Quit'] = 'Lopeta';
        $strings['AddGroup'] = 'Lisää Ryhmä';
        $strings['StandardScheduleDisplay'] = 'Käytä yleistä kalenterinäyttöä';
        $strings['TallScheduleDisplay'] = 'Käytä korkeaa kalenterinäyttöä';
        $strings['WideScheduleDisplay'] = 'Käytä leveää kalenterinäyttöä';
        $strings['CondensedWeekScheduleDisplay'] = 'Käytä tiivistetyä viikkonäyttöä';
        $strings['ResourceGroupHelp1'] = 'Vedä ja pudota resurssiryhmiä järjestääksesi uudelleen.';
        $strings['ResourceGroupHelp2'] = 'Klikkaa oikealla resurssiryhmänimeä muita toimenpiteitä varten.';
        $strings['ResourceGroupHelp3'] = 'Vedä ja pudota tiloja lisätäksesi ne ryhmiin.';
        $strings['ResourceGroupWarning'] = 'Jos käytät resurssiryhmiä, liitä jokainen resurssi ainakin yhteen ryhmään. Muuten niitä ei voi varata.';
        $strings['ResourceType'] = 'Resurssityyppi';
        $strings['AppliesTo'] = 'Koskee';
        $strings['UniquePerInstance'] = 'Uniikki Jokaiselle Esiintymälle';
        $strings['AddResourceType'] = 'Lisää resurssityyppi';
        $strings['NoResourceTypeLabel'] = '(resurssityyppiä ei asetettu)';
        $strings['ClearFilter'] = 'Poista Suodatin';
        $strings['MinimumCapacity'] = 'Minimikapasiteetti';
        $strings['Color'] = 'Väri';
        $strings['Available'] = 'Saatavilla';
        $strings['Unavailable'] = 'Ei Saatavilla';
        $strings['Hidden'] = 'Piilotettu';
        $strings['ResourceStatus'] = 'Resurssin tila';
        $strings['CurrentStatus'] = 'Nykyinen tila';
        $strings['AllReservationResources'] = 'Kaikki Varattavat Resurssit';
        $strings['File'] = 'Tiedosto';
        $strings['BulkResourceUpdate'] = 'Resurssien massapäivitys';
        $strings['Unchanged'] = 'Muuttumaton';
        $strings['Common'] = 'Yleinen';
        $strings['AdminOnly'] = 'On Vain Ylläpitäjille';
        $strings['AdvancedFilter'] = 'Lisäsuodatin';
        $strings['MinimumQuantity'] = 'Minimimäärä';
        $strings['MaximumQuantity'] = 'Maximimäärä';
        $strings['ChangeLanguage'] = 'Vaihda Kieli';
        $strings['AddRule'] = 'Lisää Sääntö';
        $strings['Attribute'] = 'Kenttä';
        $strings['RequiredValue'] = 'Pakollinen Arvo';
        $strings['ReservationCustomRuleAdd'] = 'Käytä tätä väriä kun lisäkentällä on seuraava arvo';
        $strings['AddReservationColorRule'] = 'Lisää Varaukselle Värisääntö';
        $strings['LimitAttributeScope'] = 'Kerää Tietyissä Tapauksissa';
        $strings['CollectFor'] = 'Kerää';
        $strings['SignIn'] = 'Kirjaudu Sisään';
        $strings['AllParticipants'] = 'Kaikki Osallistujat';
        $strings['RegisterANewAccount'] = 'Rekisteröi Uusi Tili';
        $strings['Dates'] = 'Päiväykset';
        $strings['More'] = 'Lisää';
        $strings['ResourceAvailability'] = 'Resurssin Saatavuus';
        $strings['UnavailableAllDay'] = 'Ei Saatavilla koko päivänä';
        $strings['AvailableUntil'] = 'Saatavilla';
        $strings['AvailableBeginningAt'] = 'Saatavailla Alkaen';
        $strings['AvailableAt'] = 'Saatavilla';
        $strings['AllResourceTypes'] = 'Kaikki Resurssityypit';
        $strings['AllResourceStatuses'] = 'Kaikki resurssien tilat';
        $strings['AllowParticipantsToJoin'] = 'Osallistujat Voivat Liittyä';
        $strings['Join'] = 'Liity';
        $strings['YouAreAParticipant'] = 'Osallistut tähän varaukseen';
        $strings['YouAreInvited'] = 'Sinut on kutsuttu tähän varaukseen';
        $strings['YouCanJoinThisReservation'] = 'Voit liittyä tähän varaukseen';
        $strings['Import'] = 'Tuo';
        $strings['GetTemplate'] = 'Hae Pohja';
        $strings['UserImportInstructions'] = '<ul><li>Tiedoston on oltava CSV formaatissa.</li><li>Käyttäjä ja email ovat pakollisia kenttiä.</li><li>Lisäkentän tarkistamista ei pakoteta.</li><li>Jättämällä muut kentät tyhjiksi laitetaan oletusarvot ja \'salasana\' käyttäjän salasanaksi.</li><li>Käytä annettua pohjaa esimerkkinä.</li></ul>';
        $strings['RowsImported'] = 'Rivejä Tuotu';
        $strings['RowsSkipped'] = 'Rivejä Ohitettu';
        $strings['Columns'] = 'Sarakkeet';
        $strings['Reserve'] = 'Varaa';
        $strings['AllDay'] = 'Koko Päivän';
        $strings['Everyday'] = 'Joka Päivä';
        $strings['IncludingCompletedReservations'] = 'Sisältäen Loppuuntehdyt Varaukset';
        $strings['NotCountingCompletedReservations'] = 'Sisältämättä Loppuuntehtyjä Varauksia';
        $strings['RetrySkipConflicts'] = 'Ohita päällekkäiset varaukset';
        $strings['Retry'] = 'Yritö Uudelleen';
        $strings['RemoveExistingPermissions'] = 'Poistetaanko olemassa olvat Oikeudet?';
        $strings['Continue'] = 'Jatka';
        $strings['WeNeedYourEmailAddress'] = 'Tarvitsemme sähköpostiosoitteesi, jotta voi tehdä varauksen';
        $strings['ResourceColor'] = 'Resurssin Väri';
        $strings['DateTime'] = 'Päiväys Aika';
        $strings['AutoReleaseNotification'] = 'Vapautetaan automaattisesti jos sisäänkirjausta ei ole tehty %s minuutissa';
        $strings['RequiresCheckInNotification'] = 'Vaatii sisään-/uloskirjauksen';
        $strings['NoCheckInRequiredNotification'] = 'Ei vaadi sisään-/uloskirjausta';
        $strings['RequiresApproval'] = 'Vaatii Hyväksymisen';
        $strings['CheckingIn'] = 'Sisäänkirjaus';
        $strings['CheckingOut'] = 'Uloskirjaus';
        $strings['CheckIn'] = 'Kirjaa Sisään';
        $strings['CheckOut'] = 'Kirjaa Ulos';
        $strings['ReleasedIn'] = 'Vapautetaan';
        $strings['CheckedInSuccess'] = 'Olet kirjannut sisään';
        $strings['CheckedOutSuccess'] = 'Olet kirjannut ulos';
        $strings['CheckInFailed'] = 'Et voinut kirjata sisään';
        $strings['CheckOutFailed'] = 'Etvinut kirjata ulos';
        $strings['CheckInTime'] = 'Sisäänkirjausaika';
        $strings['CheckOutTime'] = 'Uloskirjausaika';
        $strings['OriginalEndDate'] = 'Alkuperäinen lopetusaika';
        $strings['SpecificDates'] = 'Näytä Tietyt Päivät';
        $strings['Users'] = 'Käyttäjät';
        $strings['Guest'] = 'Vieras';
        $strings['ResourceDisplayPrompt'] = 'Näytettävä Resurssi';
        $strings['Credits'] = 'Krediitit';
        $strings['AvailableCredits'] = 'Saatavilla Olevat Krediitit';
        $strings['CreditUsagePerSlot'] = 'Vaatii %s krediittiä jokaiselle ajalle (matalasesonki)';
        $strings['PeakCreditUsagePerSlot'] = 'Vaatii %s krediittiä jokaiselle ajalle (korkeasesonki)';
        $strings['CreditsRule'] = 'Sinulla ei ole tarpeeksi krediittejä. Krediittejä tarvitaan: %s. Krediittejä tilillä: %s';
        $strings['PeakTimes'] = 'Korkeasesonki';
        $strings['AllYear'] = 'Koko Vuoden';
        $strings['MoreOptions'] = 'Lisää Vaihtoehtoja';
        $strings['SendAsEmail'] = 'Lähetä sähköpostilla';
        $strings['UsersInGroups'] = 'Käyttyjiä Ryhmissä';
        $strings['UsersWithAccessToResources'] = 'Käyttäjät, Joilla Oikeudet Resursseihin';
        $strings['AnnouncementSubject'] = '%s lähetti uuden ilmoituksen';
        $strings['AnnouncementEmailNotice'] = 'käyttäjille lähetätään tämä ilmoitus sähköpostilla';
        $strings['Day'] = 'Päivä';
        $strings['NotifyWhenAvailable'] = 'Imoita Minulle Kun Saatavilla';
        $strings['AddingToWaitlist'] = 'Sinut lisätään jonotuslistalle';
        $strings['WaitlistRequestAdded'] = 'Sinulle ilmoitetaan, kun aika tulee saataville';
        $strings['PrintQRCode'] = 'Tulosta QR Koodi';
        $strings['FindATime'] = 'Etsi Aika';
        $strings['AnyResource'] = 'Mikä Tahansa Resurssi';
        $strings['ThisWeek'] = 'Tämä Viikko';
        $strings['Hours'] = 'Tunnit';
        $strings['Minutes'] = 'Minuutit';
        $strings['ImportICS'] = 'Tuo ICS';
        $strings['ImportQuartzy'] = 'Tuo Quartzy:stä';
        $strings['OnlyIcs'] = 'Vain *.ics tiedostoja voi ladata.';
        $strings['IcsLocationsAsResources'] = 'Paikat ladataan tiloina.';
        $strings['IcsMissingOrganizer'] = 'Jos tapahtumalla ei ole järjestäjää, nykyinen käyttäjä asetetaan järjetäjäksi';
        $strings['IcsWarning'] = 'Varakssääntöjä ei käytetä - päällekkäisyydet, kopiot, jne ovat mahdollisia.';
        $strings['BlackoutAroundConflicts'] = 'Suljettuja aikoja päällekkäisten varausten ympärillä';
        $strings['DuplicateReservation'] = 'Kopio';
        $strings['UnavailableNow'] = 'Ei Vapaana Nyt';
        $strings['ReserveLater'] = 'Varaa Myöhemmin';
        $strings['CollectedFor'] = 'Kerätty';
        $strings['IncludeDeleted'] = 'Sisällytä Poistetut Varaukset';
        $strings['Deleted'] = 'Poistettu';
        $strings['Back'] = 'Takaisin';
        $strings['Forward'] = 'Eteenpäin';
        $strings['DateRange'] = 'Päivämääräväli';
        $strings['Copy'] = 'Kopioi';
        $strings['Detect'] = 'Etsi';
        $strings['Autofill'] = 'Täytä automaattisesti';
        $strings['NameOrEmail'] = 'nimi tai sähköposti';
        $strings['ImportResources'] = 'Tuo Resursseja';
        $strings['ExportResources'] = 'Vie Resursseja';
        $strings['ResourceImportInstructions'] = '<ul><li>Tiedoston on oltava CSV formaatissa UTF-8 koodattuna.</li><li>Nimi on pakollinen kenttä. Jos jätät muut kentät tyhjiksi käytetään olettusarvoja.</li><li>Tilavaihtoehdot ovat \'Saatavilla\', \'Ei Saatavilla\' and \'Piilotettu\'.</li><li>Värin on oltava hex arvo eli) #ffffff.</li><li>Automaattinen valinta ja hyväksyntä -sarakkeet voivat olla true tai false.</li><li>Lisäkenttää ei tarkisteta.</li><li>Erota resurssiryhmät pilkuilla.</li><li>Käytä annettua pohjaa esimerkkinä.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Tiedoston on oltava CSV formaatissa UTF-8 koodattuna.</li><li>Sähköposti, tilojen nimet, alku, ja loppu ovat pakollisia kenttiä.</li><li>Alku ja loppu tarvitsevat päiväyksen ja ajan. Suositeltu muoto on YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Sääntöjä, päällekkäisyyksiä, ja aikoja ei tarkisteta.</li><li>Ilmoituksia ei lähetetä.</li><li>Lisäkenttiä ei tarkisteta.</li><li>Erota tilojen nimet pilkuilla.</li><li>Käytä annettua pohjaa esimerkkinä.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Automaattivapautus Minuuteissa';
        $strings['CreditsPeak'] = 'Krediitit (korkeasesnki)';
        $strings['CreditsOffPeak'] = 'Krediitit (matalasesonki)';
        $strings['ResourceMinLengthCsv'] = 'Varauksen Vähimmäispituus';
        $strings['ResourceMaxLengthCsv'] = 'Varauksen Enimmäispituus';
        $strings['ResourceBufferTimeCsv'] = 'Puskuriaika';
        // TÄSTÄ
        $strings['ResourceMinNoticeAddCsv'] = 'Reservation Add Minimum Notice';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Reservation Update Minimum Notice';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Reservation Delete Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';

        $strings['Export'] = 'Vie';
        $strings['DeleteMultipleUserWarning'] = 'Näiden käyttäjien poistaminen poistaa myös heidä´n nykyiset, tulevat ja menneet varauksensa. Sähköposteja ei lähetetä.';
        $strings['DeleteMultipleReservationsWarning'] = 'Sähköposteja ei lähetetä.';
        $strings['ErrorMovingReservation'] = 'Virhe Varauksen Siirrossa';
        $strings['SelectUser'] = 'Valitse Käyttäjä';
        $strings['InviteUsers'] = '´Kutus Käyttäjiä';
        $strings['InviteUsersLabel'] = 'Anna kutsuttavien ihmisten sähköpostiosoitteet';
        $strings['ApplyToCurrentUsers'] = 'Koskee nykyisiä käyttäjiä';
        $strings['ReasonText'] = 'Syy teksi';
        $strings['NoAvailableMatchingTimes'] = 'Hakuusi ei löydy vapaita aikoja';
        $strings['Schedules'] = 'Kalenterit';
        $strings['NotifyUser'] = 'Imoita Käyttäjälle';
        $strings['UpdateUsersOnImport'] = 'Päivitä käyttäjä jos sähköpostiosoite on jo olemassa';
        $strings['UpdateResourcesOnImport'] = 'Päivitä resurssi jos nimi on jo olemassa';
        $strings['Reject'] = 'Hylkää';
        $strings['CheckingAvailability'] = 'Tarkistetaan saatavuus';
        $strings['CreditPurchaseNotEnabled'] = 'Et ole ottanut käyttöön krediittien ostaamista';
        $strings['CreditsCost'] = 'Jokainen krediitti maksaa';
        $strings['Currency'] = 'Valuutta';
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
        $strings['Quantity'] = 'Määrä';
        $strings['CreditPurchase'] = 'Krediittien Osto';
        $strings['EmptyCart'] = 'Ostoskorisi on tyhjä';
        $strings['BuyCredits'] = 'Osta Krediittejä';
        $strings['CreditsPurchased'] = 'Krediittiä ostettu.';
        $strings['ViewYourCredits'] = 'Tarkista krediittisi';
        $strings['TryAgain'] = 'Yritä Uudelleen';
        $strings['PurchaseFailed'] = 'Maksusi kanssa oli ongelmia.';
        $strings['NoteCreditsPurchased'] = 'Krediittiä ostettu';
        $strings['CreditsUpdatedLog'] = '%s on päivittänyt Krediitit';
        $strings['ReservationCreatedLog'] = 'Varaus luotu. Varausnumero %s';
        $strings['ReservationUpdatedLog'] = 'Varaus päivitetty. Varausnumero %s';
        $strings['ReservationDeletedLog'] = 'Varaus poistettu. Varausnumero %s';
        $strings['BuyMoreCredits'] = 'Osta Lisää Krediittejä';
        $strings['Transactions'] = 'Tapahtumat';
        $strings['Cost'] = 'Hinta';
        $strings['PaymentGateways'] = 'Payment Gateways';
        $strings['CreditHistory'] = 'Krediittihistoria';
        $strings['TransactionHistory'] = 'Tapahtumahistoria';
        $strings['Date'] = 'Päiväys';
        $strings['Note'] = 'Muistiinpano';
        $strings['CreditsBefore'] = 'Krediittejä Ennen';
        $strings['CreditsAfter'] = 'Krediittejä Jälkeen';
        $strings['TransactionFee'] = 'Tapahtumamaksu';
        $strings['InvoiceNumber'] = 'Laskunumero';
        $strings['TransactionId'] = 'Tapahtuman ID';
        $strings['Gateway'] = 'Gateway';
        $strings['GatewayTransactionDate'] = 'Gateway Transaction Date';
        $strings['Refund'] = 'Hyvitys';
        $strings['IssueRefund'] = 'Tee Hyvitys';
        $strings['RefundIssued'] = 'Hyvietty Onnistuneesti';
        $strings['RefundAmount'] = 'Hyvityksen Määrä';
        $strings['AmountRefunded'] = 'Hyvitetty';
        $strings['FullyRefunded'] = 'Kokonaan Hyvitetty';
        $strings['YourCredits'] = 'Krediittisi';
        $strings['PayWithCard'] = 'Maksa Kortilla';
        $strings['or'] = 'tai';
        $strings['CreditsRequired'] = 'Krediittejä Tarvitaan';
        $strings['AddToGoogleCalendar'] = 'Lisää Googleen';
        $strings['Image'] = 'Kuva';
        $strings['ChooseOrDropFile'] = 'Valitse tiedosto tai vedä se tähän';
        $strings['SlackBookResource'] = 'Varaa %s nyt';
        $strings['SlackBookNow'] = 'Varaa Nyt';
        $strings['SlackNotFound'] = 'Resurssia ei löydy tuolla nimellä. Varaa Nyt aloittaaksesi uuden varauksen.';
        $strings['AutomaticallyAddToGroup'] = 'Lisää käyttäjä automaattisesti tähän ryhmään';
        $strings['GroupAutomaticallyAdd'] = 'Lisää Automaattisesti';
        $strings['TermsOfService'] = 'Palveluehdot';
        $strings['EnterTermsManually'] = 'Anna Ehdot Käsi';
        $strings['LinkToTerms'] = 'Linkki Ehtoihin';
        $strings['UploadTerms'] = 'Lataa Ehdot';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Vaadi Palveluehtojen Hyväksyminen';
        $strings['UponReservation'] = 'Varauksessa';
        $strings['UponRegistration'] = 'Rekisteröinnissä';
        $strings['ViewTerms'] = 'Katso Palveluehdot';
        $strings['IAccept'] = 'Hyväksyn';
        $strings['TheTermsOfService'] = 'Palveluehdot';
        $strings['DisplayPage'] = 'Näytettävä Sivu';
        $strings['AvailableAllYear'] = 'Koko Vuosi';
        $strings['Availability'] = 'Saatavuus';
        $strings['AvailableBetween'] = 'Saatavilla';

        $strings['ConcurrentYes'] = 'Resursseja voi varata enemmän kuin yksi ihminen kerrallaan';
        $strings['ConcurrentNo'] = 'Resursseja ei voi varata enemmän kuin yksi ihminen kerrallaan';
        $strings['ScheduleAvailabilityEarly'] = ' Tämä kalenteri ei ole vielä käytettävissö. Se on käytettävissä';
        $strings['ScheduleAvailabilityLate'] = 'Tämä kalenteri ei ole enää käytettävissä. Se oli käytettävissä';
        $strings['ResourceImages'] = 'Resurssien Kuvat';
        $strings['FullAccess'] = 'Kaikki Oikeudet';
        $strings['ViewOnly'] = 'Vain Katsominen';
        $strings['Purge'] = 'Poista';
        $strings['UsersWillBeDeleted'] = 'käyttäjät poistetaan';
        $strings['BlackoutsWillBeDeleted'] = 'suljetut ajat poistetaan';
        $strings['ReservationsWillBePurged'] = 'varaukset poistetaan';
        $strings['ReservationsWillBeDeleted'] = 'varaukset poistetaan';
        $strings['PermanentlyDeleteUsers'] = 'Poista pysyvästi käuttäjät, jotka eivät ole kirjautuneet myöhemmin kuin';
        $strings['DeleteBlackoutsBefore'] = 'Poista suljetut ajat ennen';
        $strings['DeletedReservations'] = 'Poistetut varaukset';
        $strings['DeleteReservationsBefore'] = 'Poista varaukset ennen';
        $strings['SwitchToACustomLayout'] = 'Vaihda mukautettuun layoutiin';
        $strings['SwitchToAStandardLayout'] = 'Vaihda yleiseen layout';
        $strings['ThisScheduleUsesACustomLayout'] = 'Tämä kalenteri käyttää muokattua layoutia';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Tämä kalenteri käyttää yleistä layoutia';
        $strings['SwitchLayoutWarning'] = 'Oletko varma, että haluat vaihtaa layoutin tyypiä? Tämä poistaa olemassa olevat ajat.';
        $strings['DeleteThisTimeSlot'] = 'Poistetaanko tämä aika?';
        $strings['Refresh'] = 'Päivitä Näyttö';
        $strings['ViewReservation'] = 'Katso Varausta';
        $strings['PublicId'] = 'Julkinen Id';
        $strings['Public'] = 'Julkinen';
        $strings['AtomFeedTitle'] = '%s Varaukset';
        $strings['DefaultStyle'] = 'Oletustyyli';
        $strings['Standard'] = 'Yleinen';
        $strings['Wide'] = 'Leveä';
        $strings['Tall'] = 'Korkea';
        $strings['EmailTemplate'] = 'Sähköpostin Pohja';
        $strings['SelectEmailTemplate'] = 'Valitse Sähköpostin Pohja';
        $strings['ReloadOriginalContents'] = 'Lataa Uudelleen Alkuperäinen Sisältö';
        $strings['UpdateEmailTemplateSuccess'] = 'Päivitetty Sähköpostin Pohja';
        $strings['UpdateEmailTemplateFailure'] = 'Sähköposrin pohja ei voitu päivittää. Tarkista, että hakemisto ei ole kirjoitussuojattu.';
        $strings['BulkResourceDelete'] = 'Resurssien Massapoisto';
        $strings['NewVersion'] = 'Uusi versio!';
        $strings['WhatsNew'] = 'Mitä Uutta?';
        $strings['OnlyViewedCalendar'] = 'Tätä kalenteria voi katsoa vain kalenterinäkymässä';
        $strings['Grid'] = 'Ruudukko';
        $strings['List'] = 'Lista';
        $strings['NoReservationsFound'] = 'Varauksia Ei Löytynyt';
        $strings['EmailReservation'] = 'Sähköpostivaraus';
        $strings['AdHocMeeting'] = 'Ad hoc Kokous';
        $strings['NextReservation'] = 'Seuraava Varaus';
        $strings['MissedCheckin'] = 'Puuttuva Sisäänkirjaus';
        $strings['MissedCheckout'] = 'Puuttuva Uloskirjaus';

        // TÄHÄN
//
        // End Strings

        // Errors
        $strings['LoginError'] = 'Käyttäjänimi tai salasana on virheellinen';
        $strings['ReservationFailed'] = 'Varaustasi ei voitu toteuttaa';
        $strings['MinNoticeError'] = 'Tämä varaus on ilmoitettava etukäteen.  Aikaisin mahdollinen päivä varauksen tekemiseen on %s.';
        $strings['MinNoticeErrorUpdate'] = 'Tämän varauksen muuttaminen vaatii etukäteisilmoituksen. Varauksia ennen %s ei voi muuttaa.';
        $strings['MinNoticeErrorDelete'] = 'Tämän varauksen poistaminen vaatii etukäteisilmoituksen. Varauksia ennen %s ei voi poistaa.';
        $strings['MaxNoticeError'] = 'Tätä varausta ei voi tehdä näin kauas tulevaisuuteen.  Viimeisin ajankohta joka voidaan varata on %s.';
        $strings['MinDurationError'] = 'Tämän varauksen tulee olla vähintään %s.';
        $strings['MaxDurationError'] = 'Tämä varaus ei voi kestää pitempään kuin %s.';
        $strings['ConflictingAccessoryDates'] = 'Seuraavia tarjoiltavia ei ole tarpeeksi: ';
        $strings['NoResourcePermission'] = 'Sinulla ei ole käyttöoikeutta yhteen tai useampaan tilaan';
        $strings['ConflictingReservationDates'] = 'Seuraavina päivinä on ristiriidan aiheuttavia toisia varauksia: ';
        $strings['StartDateBeforeEndDateRule'] = 'Aloituspäivän tulee olla ennen varauksen loppumispäivää';
        $strings['StartIsInPast'] = 'Aloituspäivä ei voi olla menneisyydessä';
        $strings['EmailDisabled'] = 'Moderaattori on estänyt automaattiset sähköposti-ilmoitukset';
        $strings['ValidLayoutRequired'] = 'Varaa kaikki 24 tuntia alkaen ja päättyen klo 00:00.';
        $strings['CustomAttributeErrors'] = 'Antamissasi liskentissä on ongelmia:';
        $strings['CustomAttributeRequired'] = '%s on pakollinen kenttä.';
        $strings['CustomAttributeInvalid'] = 'Annettu arvo %s ei kelpaa.';
        $strings['AttachmentLoadingError'] = 'Sorry, pyydetyn tiedoston lataamisessa oli ongelma.';
        $strings['InvalidAttachmentExtension'] = 'Voit ladata vain tyyppiä %s olevia tiedostoja.';
        $strings['InvalidStartSlot'] = 'Aloituspäivä ja -aika eivät ole oikein.';
        $strings['InvalidEndSlot'] = 'Loppupäivä ja -aika eivät ole oikein.';
        $strings['MaxParticipantsError'] = '%s : korkeintaan %s osallistujaa.';
        $strings['ReservationCriticalError'] = 'Kriittinen Virhe tallennettaessa varaustasi. Jos tämä toistuu, ota yhteyttä järjsetlmän ylläpitäjään.';
        $strings['InvalidStartReminderTime'] = 'Muistutuksen alkuaika ei ole oikein.';
        $strings['InvalidEndReminderTime'] = 'Muistutuksen loppuaika ei ole oikein.';
        $strings['QuotaExceeded'] = 'Kiintiön limiitti ylitetty.';
        $strings['MultiDayRule'] = '%s ei salli useampipäiväisiä varauksia.';
        $strings['InvalidReservationData'] = 'Varauspyynnössäsi oli ongelma.';
        $strings['PasswordError'] = 'Salasanassa on oltava vähintääm %s kirjainta ja vähintään %s numeroa.';
        $strings['PasswordErrorRequirements'] = 'Salasanassa on oltava vähintään %s ISOA ja pientä kirjainta ja %s nunmeroa.';
        $strings['NoReservationAccess'] = 'Et voi muuttaa tätä varausta.';
        $strings['PasswordControlledExternallyError'] = 'Salasanaasi hallitsee ulkoinen järjestelmä eikä sitä voi muuttaa täällä.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Tarvike  %s voidaan varata vain resurssin %s kanssa';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Varattava vähintään %s kappaletta tarviketta %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Et voi varata enempää kuin %s kappaletta tarviketta  %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Tarviketta \'%s\' ei voi varata pyydetyn resursin kanssa';
        $strings['NoResources'] = 'Et ole valinnut yhtään resurssia.';
        $strings['ParticipationNotAllowed'] = 'Et voi liittyä tähän varaukseen.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Tähän varaukseen ei voi kirjautua sisään.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Tästä varauksesta ei voi kirjautua ulos.';
        $strings['InvalidEmailDomain'] = 'Sähköpostiosoite ei ole sallitusta domainista';
        $strings['TermsOfServiceError'] = 'Sinun o nhyväksyttävä Palveluehdot';
        $strings['UserNotFound'] = 'Käyttäjää ei löydy';
        $strings['ScheduleAvailabilityError'] = 'Tämä kalenteri ei ole saatavilla %s ja %s välillä';
        $strings['ReservationNotFoundError'] = 'Varausta ei löydy';
        $strings['ReservationNotAvailable'] = 'Varaus ei ole saatavilla';
        $strings['TitleRequiredRule'] = 'Varauksella on oltava otsikko';
        $strings['DescriptionRequiredRule'] = 'Varauksella on oltava kuvaus';
        $strings['WhatCanThisGroupManage'] = 'Mitä tämä ryhmä voi hallita';

        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Tee varaus';
        $strings['EditReservation'] = 'Muokkaa varausta';
        $strings['LogIn'] = 'Kirjaudu';
        $strings['ManageReservations'] = 'Varaukset';
        $strings['AwaitingActivation'] = 'Odottaa aktivointia';
        $strings['PendingApproval'] = 'Odottaa hyväksymistä';
        $strings['ManageSchedules'] = 'Varauskalenterit';
        $strings['ManageResources'] = 'Varattavat resurssit';
        $strings['ManageAccessories'] = 'Tarvikkeet';
        $strings['ManageUsers'] = 'Käyttäjät';
        $strings['ManageGroups'] = 'Ryhmät';
        $strings['ManageQuotas'] = 'Kiintiöt';
        $strings['ManageBlackouts'] = 'Estetyt ajankohdat';
        $strings['MyDashboard'] = 'Oma työpöytä';
        $strings['ServerSettings'] = 'Palvelinasetukset';
        $strings['Dashboard'] = 'Työpöytä';
        $strings['Help'] = 'Ohje';
        $strings['Administration'] = 'Ylläpito';
        $strings['About'] = 'Tietoja';
        $strings['Bookings'] = 'Varaukset';
        $strings['Schedule'] = 'Varauskalenteri';
        $strings['Reservations'] = 'Varaukset';
        $strings['Account'] = 'Tili';
        $strings['EditProfile'] = 'Muokkaa profiilia';
        $strings['FindAnOpening'] = 'Etsi alkukohta';
        $strings['OpenInvitations'] = 'Avaa kutsut';
        $strings['MyCalendar'] = 'Oma kalenteri';
        $strings['ResourceCalendar'] = 'Resurssikalenteri';
        $strings['Reservation'] = 'Uusi varaus';
        $strings['Install'] = 'Asennus';
        $strings['ChangePassword'] = 'Vaihda salasana';
        $strings['MyAccount'] = 'Käyttäjätili';
        $strings['Profile'] = 'Profiili';
        $strings['ApplicationManagement'] = 'Ylläpito';
        $strings['ForgotPassword'] = 'Olen unohtanut salasanani';
        $strings['NotificationPreferences'] = 'Ilmoitusten asetukset';
        $strings['ManageAnnouncements'] = 'Ilmoitukset';

        $strings['Responsibilities'] = 'Vastuut';
        $strings['GroupReservations'] = 'Ryhmävaraukset';
        $strings['ResourceReservations'] = 'Resurssien Varaukset';
        $strings['Customization'] = 'Räätälöinti';
        $strings['Attributes'] = 'Lisäkentät';
        $strings['AccountActivation'] = 'Tilin Aktivointi';
        $strings['ScheduleReservations'] = 'Kalenterin Varaukset';
        $strings['Reports'] = 'Raportit';
        $strings['GenerateReport'] = 'Luo Uusi Raportti';
        $strings['MySavedReports'] = 'Tallennetut Raporttini';
        $strings['CommonReports'] = 'Yleiset Raportit';
        $strings['ViewDay'] = 'Katso Päivää';
        $strings['Group'] = 'Ryhmä';
        $strings['ManageConfiguration'] = 'Sovelluksen Konfigurointi';
        $strings['LookAndFeel'] = 'Ulkoasu';
        $strings['ManageResourceGroups'] = 'Resurssiryhmät';
        $strings['ManageResourceTypes'] = 'Resurssityypit';
        $strings['ManageResourceStatus'] = 'Resurssien Tilat';
        $strings['ReservationColors'] = 'Varausten Värit';
        $strings['SearchReservations'] = 'Etsi Varauksia';
        $strings['ManagePayments'] = 'Maksut';
        $strings['ViewCalendar'] = 'Katso Kalenteria';
        $strings['DataCleanup'] = 'Datan Poista';
        $strings['ManageEmailTemplates'] = 'Hallitse Sähköpostipohjia';

        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'K';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'P';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Su';
        $strings['DayMondayAbbr'] = 'Ma';
        $strings['DayTuesdayAbbr'] = 'Ti';
        $strings['DayWednesdayAbbr'] = 'Ke';
        $strings['DayThursdayAbbr'] = 'To';
        $strings['DayFridayAbbr'] = 'Pe';
        $strings['DaySaturdayAbbr'] = 'La';

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Varauksesi on hyväksytty';
        $strings['ReservationCreatedSubject'] = 'Varauksesi on tehty';
        $strings['ReservationUpdatedSubject'] = 'Varauksesi on päivitetty';
        $strings['ReservationDeletedSubject'] = 'Varauksesi on poistettu';
        $strings['ReservationCreatedAdminSubject'] = 'Ilmoitus: Varaus on tehty';
        $strings['ReservationUpdatedAdminSubject'] = 'Ilmoitus: Varaus on päivitetty';
        $strings['ReservationDeleteAdminSubject'] = 'Ilmoitus: Varaus on poistettu';
        $strings['ReservationApprovalAdminSubject'] = 'Imoitus: Varaus Vaatii Hyväksyntäsi';
        $strings['ParticipantAddedSubject'] = 'Ilmoitus varaukseen osallistumisesta';
        $strings['ParticipantDeletedSubject'] = 'Varaus poistettu';
        $strings['InviteeAddedSubject'] = 'Varauskutsu';
        $strings['ResetPasswordRequest'] = 'Salasanan palautuspyyntö';
        $strings['ResetPassword'] = 'Salasanan palautuspyyntö';
        $strings['ForgotPasswordEmailSent'] = 'Ohjeet salasanan palauttamiseksi lähetettiin antamaasi sähköpostiosoitteeseen';

        $strings['ActivateYourAccount'] = 'Aktivoi Tilisi';
        $strings['ReportSubject'] = 'Pyytämäsi Raportti (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Varaus %s alkaa pian';
        $strings['ReservationEndingSoonSubject'] = 'Varaus %s alkaa pian';
        $strings['UserAdded'] = 'Uusi käyttäjä on lisätty';
        $strings['UserDeleted'] = 'Käyttäjätili %s poistettu. Poistaja oli %s';
        $strings['GuestAccountCreatedSubject'] = 'Tilisi %s tiedot';
        $strings['AccountCreatedSubject'] = 'Tilisi %s tiedot';
        $strings['InviteUserSubject'] = '%s on kutsunut sinut liittymään %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Varaus on hyväksytty %s :lle';
        $strings['ReservationCreatedSubjectWithResource'] = 'Varaus on luotu %s :lle';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Varaus on päivitetty %s :lle';
        $strings['ReservationDeletedSubjectWithResource'] = 'Varaus on poistettu %s :lta';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Notification: Varaus on luotu %s :lle';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Notification: Varaus on päivitetty %s :lle';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Notification: Varaus on poistettu %s :lta';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Notification: Varaus %s :lle tarvitsee hyväksyntäsi';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Lisäsi Sinut Varaukseen %s :lle';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s Poisti Varauksen %s :lta';
        $strings['InviteeAddedSubjectWithResource'] = '%s Kutsui Sinut Varaukseen for %s :lle';
        $strings['MissedCheckinEmailSubject'] = 'Sisäänkirjaus puuttuu %s :lta';
        $strings['ReservationShareSubject'] = '%s Jakoi Varauksen %s :lle';
        //End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Sinulla ei ole aikaisempia varauksia';
        $strings['PastReservations'] = 'Aikaisemmat varaukset';
        $strings['AllNoPastReservations'] = 'Viimeisen %s päivän aikana ei ole aikaisempia varauksia';
        $strings['AllPastReservations'] = 'Kaikki aikaisemmat varaukset';
        $strings['Yesterday'] = 'Eilen';
        $strings['EarlierThisWeek'] = 'Aiemmin tällä viikolla';
        $strings['PreviousWeek'] = 'Edellinen viikko';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Ryhmällänne ei ole tulevia varauksia';
        $strings['GroupUpcomingReservations'] = 'Ryhmäni tulevat varaukset';
        //End Group Upcoming Reservations
    
        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Virhe kirjautuessa Facebookin kanssa. Yritä uudelleen.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Sinulla ei ole odottavia hyväksyttäviä varauksia';
        $strings['PendingApprovalReservations'] = 'Odottaa hyväksyntää olevat varaukset';
        $strings['LaterThisMonth'] = 'Myöhemmin tänä kuukautena';
        $strings['LaterThisYear'] = 'Myöhemmin tänä vuonna';
        $strings['Remaining'] = 'Jäljellä oleva';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Puuttuvia uloskirjautumisvarauksia ei ole';
        $strings['MissingCheckOutReservations'] = 'Puuttuvat uloskirjautumisvaraukset';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Et voi nähdä varauksen tietoja, koska sinulla ei ole oikeuksia mihinkään tämän varauksen resursseista';
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
        $days['full'] = ['Sunnuntai', 'Maanantai', 'Tiistai', 'Keskiviikko', 'Torstai', 'Perjantai', 'Lauantai'];
        // The three letter abbreviation
        $days['abbr'] = ['Sun', 'Maa', 'Tii', 'Kes', 'Tor', 'Per', 'Lau'];
        // The two letter abbreviation
        $days['two'] = ['Su', 'Ma', 'Ti', 'Ke', 'To', 'Pe', 'La'];
        // The one letter abbreviation
        $days['letter'] = ['S', 'M', 'T', 'K', 'T', 'P', 'L'];

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
        $months['full'] = ['Tammikuu', 'Helmikuu', 'Maaliskuu', 'Huhtikuu', 'Toukokuu', 'Kesäkuu', 'Heinäkuu', 'Elokuu', 'Syyskuu', 'Lokakuu', 'Marraskuu', 'Joulukuu'];
        // The three letter month name
        $months['abbr'] = ['Tam', 'Hel', 'Maa', 'Huh', 'Tou', 'Kes', 'Hei', 'Elo', 'Syy', 'Lok', 'Mar', 'Jou'];

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
        return 'fi';
    }
}
