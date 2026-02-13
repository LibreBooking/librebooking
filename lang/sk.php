<?php

/**
Translation: 2014 Slovak Language: Branislav Ďorď <branislav.dord@eurogalaxy.sk>
 */

require_once('en_gb.php');

class sk extends en_gb
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
        $strings = [];

        $strings['FirstName'] = 'Meno';
        $strings['LastName'] = 'Priezvisko';
        $strings['Timezone'] = 'Časová zóna';
        $strings['Edit'] = 'Upraviť';
        $strings['Change'] = 'Zmeniť';
        $strings['Rename'] = 'Premenovať';
        $strings['Remove'] = 'Odstrániť';
        $strings['Delete'] = 'Zmazať';
        $strings['Update'] = 'Uložiť';
        $strings['Cancel'] = 'Zrušiť';
        $strings['Add'] = 'Pridať';
        $strings['Name'] = 'Názov';
        $strings['Yes'] = 'Áno';
        $strings['No'] = 'Nie';
        $strings['FirstNameRequired'] = 'Meno je povinné.';
        $strings['LastNameRequired'] = 'Priezvisko je povinné.';
        $strings['PwMustMatch'] = 'Heslá sa nezhodujú.';
        $strings['PwComplexity'] = 'Heslo musí mať nejmenej 6 znakov v kombinacií s veľkým písmenom, číslom a symbolom.';
        $strings['ValidEmailRequired'] = 'Emailová adresa je povinná.';
        $strings['UniqueEmailRequired'] = 'Táto e-mailová adresa už je v systéme zaregistrovaná.';
        $strings['UniqueUsernameRequired'] = 'Toto uživateľské meno už je v systéme zaregistrované.';
        $strings['UserNameRequired'] = 'Uživatelské meno je povinné.';
        $strings['CaptchaMustMatch'] = 'Opíšte bezpečnostný kód z obrázku.';
        $strings['Today'] = 'Dnes';
        $strings['Week'] = 'Týžden';
        $strings['Month'] = 'Mesiac';
        $strings['BackToCalendar'] = 'Speť do kalendára';
        $strings['BeginDate'] = 'Začiatok';
        $strings['EndDate'] = 'Koniec';
        $strings['Username'] = 'Uživateľské meno';
        $strings['Password'] = 'Heslo';
        $strings['PasswordConfirmation'] = 'Potvrdiť heslo';
        $strings['DefaultPage'] = 'Východzia hlavná stránka';
        $strings['MyCalendar'] = 'Môj kalendár';
        $strings['ScheduleCalendar'] = 'Plánovací kalendár';
        $strings['Registration'] = 'Registrácia';
        $strings['NoAnnouncements'] = 'Nieje žiadné obmedzenie prevádzky';
        $strings['Announcements'] = 'Obmedzenie prevádzky';
        $strings['NoUpcomingReservations'] = 'Nemáte žiadne naplánované rezervácie.';
        $strings['UpcomingReservations'] = 'Naplánované rezervácie';
        $strings['AllNoUpcomingReservations'] = 'Nie sú žiadne pripravované rezervácie';
        $strings['AllUpcomingReservations'] = 'Všetky pripravované rezervácie';
        $strings['ShowHide'] = 'Zobraziť/skryť';
        $strings['Error'] = 'Chyba';
        $strings['ReturnToPreviousPage'] = 'Vrátiť sa speť';
        $strings['UnknownError'] = 'Neznáma chyba';
        $strings['InsufficientPermissionsError'] = 'Nemáte oprávnenie. Je potrebné sa prihlásiť.';
        $strings['MissingReservationResourceError'] = 'Nebol vybraný prostriedok';
        $strings['MissingReservationScheduleError'] = 'Nebol zaškrtnutý žiadný deň';
        $strings['DoesNotRepeat'] = 'Neopakovať';
        $strings['Daily'] = 'Denný';
        $strings['Weekly'] = 'Týždenný';
        $strings['Monthly'] = 'Mesačný';
        $strings['Yearly'] = 'Ročný';
        $strings['RepeatPrompt'] = 'Opakovanie';
        $strings['hours'] = 'hodina';
        $strings['days'] = 'dni';
        $strings['weeks'] = 'týždne';
        $strings['months'] = 'mesiace';
        $strings['years'] = 'roky';
        $strings['day'] = 'deň';
        $strings['week'] = 'týždeň';
        $strings['month'] = 'mesiac';
        $strings['year'] = 'rok';
        $strings['repeatDayOfMonth'] = 'dni mesiaca';
        $strings['repeatDayOfWeek'] = 'dni týždňa';
        $strings['RepeatUntilPrompt'] =	'až do';
        $strings['RepeatEveryPrompt'] = 'každý';
        $strings['RepeatDaysPrompt'] = 'Opakovať v dni';
        $strings['CreateReservationHeading'] = 'Vytváranie rezervácie';
        $strings['EditReservationHeading'] = 'Upraviť rezerváciu: %s';
        $strings['ViewReservationHeading'] = 'Zobraziť rezerváciu: %s';
        $strings['ReservationErrors'] = 'Zmeniť rezerváciu';
        $strings['Create'] = 'Vytvoriť';
        $strings['ThisInstance'] = 'Len tento prípad';
        $strings['AllInstances'] = 'Všetky prípady';
        $strings['FutureInstances'] = 'Buduce prípady';
        $strings['Print'] = 'Tlačiť';
        $strings['ShowHideNavigation'] = 'zobraziť kalendár na tri mesiace /skryť';
        $strings['ReferenceNumber'] = 'Referenčné číslo';
        $strings['Tomorrow'] = 'Zajtra';
        $strings['LaterThisWeek'] = 'Neskôr v tomto týždni';
        $strings['NextWeek'] = 'Nasledujúcí týždeň';
        $strings['SignOut'] = 'Odhlásiť sa';
        $strings['LayoutDescription'] = 'Začiatok %s, zobrazené %s dní';
        $strings['AllResources'] = 'Všetky ihriská';
        $strings['TakeOffline'] = 'Použiť offline';
        $strings['BringOnline'] = 'Zapnúť online';
        $strings['AddImage'] = 'Pridať obrázok';
        $strings['NoImage'] = 'Žiadny obrázok';
        $strings['Move'] = 'Presunúť';
        $strings['AppearsOn'] = 'Objaví se na %s';
        $strings['Location'] = 'Umiestnenie';
        $strings['NoLocationLabel'] = '(umiestenie nenastavené)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(kontakt nenastavený)';
        $strings['Description'] = 'Popis';
        $strings['NoDescriptionLabel'] = '(bez popisu)';
        $strings['Notes'] = 'Poznámky';
        $strings['NoNotesLabel'] = '(bez poznámok)';
        $strings['NoTitleLabel'] = '(bez názvu)';
        $strings['UsageConfiguration'] = 'Použiť konfiguráciu';
        $strings['ChangeConfiguration'] = 'Zmeniť konfiguráciu';
        $strings['ResourceMinLength'] = 'Rezervácia musí byť dlhšia než %s';
        $strings['ResourceMinLengthNone'] = 'Neexistuje žiadna minimálna doba rezervácie';
        $strings['ResourceMaxLength'] = 'Rezervácia musí byť kratšia než %s';
        $strings['ResourceMaxLengthNone'] = 'Neexistuje žiadna minimálna doba rezervácie';
        $strings['ResourceRequiresApproval'] = 'Rezervácia musí byť schválena adminom';
        $strings['ResourceRequiresApprovalNone'] = 'Rezerváciu nemusí schvalovať admin';
        $strings['ResourcePermissionAutoGranted'] = 'Povolenie je automaticky získané';
        $strings['ResourcePermissionNotAutoGranted'] = 'Povolenie nieje automaticky získané';
        $strings['ResourceMinNotice'] = 'Rezervácia musí byť uskutečnená najmenej %s pred začiatkom';
        $strings['ResourceMinNoticeNone'] = 'Rezerváciu je možné spraviť až do súčasnej doby';
        $strings['ResourceMaxNotice'] = 'Rezervácia nesmie končit viac než %s pred súčastnosťou';
        $strings['ResourceMaxNoticeNone'] = 'Rezervácia môže skončiť kedykoľvek v budúcnosti';
        $strings['ResourceBufferTime'] = 'Musí byť %s medzi rezervácie';
        $strings['ResourceBufferTimeNone'] = 'Neexistujú žiadne prostriedky rezervácie';
        $strings['ResourceAllowMultiDay'] = 'Rezervácia môže byť vytvorená na niekoľko dní';
        $strings['ResourceNotAllowMultiDay'] = 'Rezerváciu nieje možné spraviť v rámci niekoľko dní';
        $strings['ResourceCapacity'] = 'Tento prostriedok má obmedzenú kapacitu na %s osôb';
        $strings['ResourceCapacityNone'] = 'Táto objednávka má neobmedzenú kapacitu';
        $strings['AddNewResource'] = 'Pridať nový prostriedok';
        $strings['AddNewUser'] = 'Pridať nového užívateľa';
        $strings['AddUser'] = 'Pridať užívateľa';
        $strings['Schedule'] = 'Kalendáre';
        $strings['AddResource'] = 'Pridať prosriedok';
        $strings['Capacity'] = 'Kapacita';
        $strings['Access'] = 'Prístup';
        $strings['Duration'] = 'Trvanie';
        $strings['Active'] = 'Aktívne';
        $strings['Inactive'] = 'Vypnuté';
        $strings['ResetPassword'] = 'Zmeniť heslo';
        $strings['LastLogin'] = 'Posledné prihlásenie';
        $strings['Search'] = 'Hľadanie';
        $strings['ResourcePermissions'] = 'Oprávnenie kalendára';
        $strings['Reservations'] = 'Rezervácia';
        $strings['Groups'] = 'Skupiny';
        $strings['ResetPassword'] = 'Resetovať heslo';
        $strings['AllUsers'] = 'Všetci užívatelia';
        $strings['AllGroups'] = 'Všetky skupiny';
        $strings['AllSchedules'] = 'Všetky kalendáre';
        $strings['UsernameOrEmail'] = 'Užívateľské meno alebo e-mail';
        $strings['Members'] = 'Členovia';
        $strings['QuickSlotCreation'] = 'Vytvoriť miesto každých %s minút medzi %s a %s';
        $strings['ApplyUpdatesTo'] = 'vykonať update';
        $strings['CancelParticipation'] = 'Zrušenie účastníkov';
        $strings['Attending'] = 'Účasť';
        $strings['QuotaConfiguration'] = 'pre %s pre %s uživateľom v %s obmezenie počtu %s %s na %s';
        $strings['reservations'] = 'rezervácií';
        $strings['reservation'] = 'rezervácia';
        $strings['ChangeCalendar'] = 'Zmeniť kalendár';
        $strings['AddQuota'] = 'Pridať kvótu';
        $strings['FindUser'] = 'Nájsť užívateľa';
        $strings['Created'] = 'Vytvorené';
        $strings['LastModified'] = 'Posledná úprava';
        $strings['GroupName'] = 'Názov skupiny';
        $strings['GroupMembers'] = 'Členovia skupiny';
        $strings['GroupRoles'] = 'Rola skupiny';
        $strings['GroupAdmin'] = 'Administrátor skupiny';
        $strings['Actions'] = 'Akcia';
        $strings['CurrentPassword'] = 'Súčasné heslo';
        $strings['NewPassword'] = 'Nové heslo';
        $strings['InvalidPassword'] = 'Bolo chybne zadané súčasné heslo.';
        $strings['PasswordChangedSuccessfully'] = 'Vaše nové heslo bolo úspešne zmenené';
        $strings['SignedInAs'] = 'Prihlásený ako';
        $strings['NotSignedIn'] = 'Neprihlásený';
        $strings['ReservationTitle'] = 'Názov rezervácie';
        $strings['ReservationDescription'] = 'Voliteľný popis';
        $strings['ResourceList'] = 'Rezervované ihriská';
        $strings['Accessories'] = 'Príslušenstvo';
        $strings['Add'] = 'Pridať';
        $strings['ParticipantList'] = 'Zoznam účastníkov';
        $strings['InvitationList'] = 'Pozvanie';
        $strings['AccessoryName'] = 'Názov príslušenstva';
        $strings['QuantityAvailable'] = 'Dostupné množstvo';
        $strings['Resources'] = 'Ihriská';
        $strings['Participants'] = 'Účastníci';
        $strings['User'] = 'Užívateľ';
        $strings['Resource'] = 'Ihriská';
        $strings['Status'] = 'Stav';
        $strings['Approve'] = 'Schvaľovanie';
        $strings['Page'] = 'Strana';
        $strings['Rows'] = 'Riadok/-y';
        $strings['Unlimited'] = 'neobmedzene';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Emailová adresa';
        $strings['Phone'] = 'Telefón';
        $strings['Organization'] = 'Spoločnosť';
        $strings['Position'] = 'Pozícia';
        $strings['Language'] = 'Jazyk';
        $strings['Permissions'] = 'Oprávnenie';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Nájsť skupinu';
        $strings['Manage'] = 'Spravovať';
        $strings['None'] = 'Nezadané';
        $strings['AddToOutlook'] = 'Pridať do aplikácie Outlook';
        $strings['Done'] = 'Hotovo';
        $strings['RememberMe'] = 'Zapamätať si ma';
        $strings['FirstTimeUser?'] = 'Nemáte tu založený účet?';
        $strings['CreateAnAccount'] = 'Registrovať sa';
        $strings['ViewSchedule'] = 'Zobraziť rozvrh';
        $strings['ForgotMyPassword'] = 'Zabudnuté heslo';
        $strings['YouWillBeEmailedANewPassword'] = 'Na zadaný e-mail Vám bude zaslané novo vygenerované heslo.';
        $strings['Close'] = 'Zatvoriť';
        $strings['ExportToCSV'] = 'Exportovať do súboru CSV';
        $strings['OK'] = 'Odoslať';
        $strings['Working'] = 'Pracujúci...';
        $strings['Login'] = 'Prihlásenie';
        $strings['AdditionalInformation'] = 'Dalšie informácie';
        $strings['AllFieldsAreRequired'] = 'Všetky polia sú povinné';
        $strings['Optional'] = 'Nepovinné';
        $strings['YourProfileWasUpdated'] = 'Váš profil bol aktualizovaný.';
        $strings['YourSettingsWereUpdated'] = 'Vaše nastavenie bolo aktualizované';
        $strings['Register'] = 'Registrovať';
        $strings['SecurityCode'] = 'Bezpečnostný kód';
        $strings['ReservationCreatedPreference'] = 'Keď si vytvorím rezerváciu, alebo keď mi bola vytvorená adminom.';
        $strings['ReservationUpdatedPreference'] = 'Keď si aktualizujem rezerváciu sám, alebo keď mi bola aktualizovaná adminom.';
        $strings['ReservationDeletedPreference'] = 'Keď zruším rezerváciu, alebo keď mi bola zrušená adminom.';
        $strings['ReservationApprovalPreference'] = 'Keď je moja rezervácia schválená adminom';
        $strings['PreferenceSendEmail'] = 'Oznámiť e-mailom';
        $strings['PreferenceNoEmail'] = 'Neoznamovať';
        $strings['ReservationCreated'] = 'Rezervácia bola vytvorená.';
        $strings['ReservationUpdated'] = 'Rezervácia bola upravená.';
        $strings['ReservationRemoved'] = 'Rezervácia bola odstránená.';
        $strings['ReservationRequiresApproval'] = 'Jedna alebo viac rezervácií vyžaduje schválenie. Táto rezervácia čaká, kým nebude schválená adminom.';
        $strings['YourReferenceNumber'] = 'Referenčné číslo: %s';
        $strings['UpdatingReservation'] = 'Obnovenie rezervácie';
        $strings['ChangeUser'] = 'Zmeniť užívateľa';
        $strings['MoreResources'] = 'Pridať ďalší prostriedok';
        $strings['ReservationLength'] = 'Dĺžka rezervácie';
        $strings['ParticipantList'] = 'Zoznam účastníkov';
        $strings['AddParticipants'] = 'Pridať účastníka';
        $strings['InviteOthers'] = 'Pozvat ostatných';
        $strings['AddResources'] = 'Pridať ihriská';
        $strings['AddAccessories'] = 'Pridať príslušenstvo';
        $strings['Accessory'] = 'Príslušenstvo';
        $strings['QuantityRequested'] = 'Požadované množstvo';
        $strings['CreatingReservation'] = 'Vytváranie rezervácie';
        $strings['UpdatingReservation'] = 'Úprava rezervácie';
        $strings['DeleteWarning'] = 'Táto akcia je trvalá a nejde ju vrátiť speť!';
        $strings['DeleteAccessoryWarning'] = 'Pri odstranení tohoto príslušenstva bude odstránené aj zo všetkých rezervácií.';
        $strings['AddAccessory'] = 'Pridať príslušenstvo';
        $strings['AddBlackout'] = 'Pridať obdobie, v ktorom nebude prostriedok dostupný';
        $strings['AllResourcesOn'] = 'všetky ihriská na';
        $strings['Reason'] = 'Odôvodnenie';
        $strings['BlackoutShowMe'] = 'Zobraziť rezervácie, ktoré sú v konflikte s inými';
        $strings['BlackoutDeleteConflicts'] = 'Odstrániť rezervácie, ktoré sú v konflikte s inými';
        $strings['Filter'] = 'Filter';
        $strings['Between'] = 'Medzi';
        $strings['CreatedBy'] = 'Vytvoril';
        $strings['BlackoutCreated'] = 'Doba uzavretia bola nastavená.';
        $strings['BlackoutNotCreated'] = 'Doba uzavretia nebola nastavená';
        $strings['BlackoutConflicts'] = 'Sú tu časy zatvorenej prevádzky v konflikte s inými';
        $strings['ReservationConflicts'] = 'Sú tu rezervované časy v konflikte s inými';
        $strings['UsersInGroup'] = 'Užívatelie v tejto skupine';
        $strings['Browse'] = 'Prehľadať';
        $strings['DeleteGroupWarning'] = 'Odstránením tejto skupiny budú odstránené všetky súvisiace oprávnenia k prostriedkom. Užívatelia v tejto skupine môžu prísť o prístup k prostriedkom.';
        $strings['WhatRolesApplyToThisGroup'] = 'Aká rola sa vzťahuje k tejto skupine?';
        $strings['WhoCanManageThisGroup'] = 'Kto môže spravovať túto skupinu?';
        $strings['WhoCanManageThisSchedule'] = 'Kto môže spravovať tento kalendár?';
        $strings['AddGroup'] = 'Pridať skupinu';
        $strings['AllQuotas'] = 'Všetky kvóty';
        $strings['QuotaReminder'] = 'Nezabudnite: Kvóty sa uplatňujú na základe nastavenia časového pásma.';
        $strings['AllReservations'] = 'Všetky rezervácie';
        $strings['PendingReservations'] = 'Nevybavené rezervácie';
        $strings['Approving'] = 'Schvaľovanie';
        $strings['MoveToSchedule'] = 'Presunúť do rozvrhu';
        $strings['DeleteResourceWarning'] = 'Odstránením tohoto prostriedku vymažete všetky súvisiace dáta.';
        $strings['DeleteResourceWarningReservations'] = 'všetky minulé, súčasné a budúce rezervácie s ním spojené';
        $strings['DeleteResourceWarningPermissions'] = 'všetky priradené povolenia';
        $strings['DeleteResourceWarningReassign'] = 'Prosím presuňte všetko, čo nechcete aby bolo vymazané.';
        $strings['ScheduleLayout'] = 'Rozvrhnutie (časy v %s)';
        $strings['ReservableTimeSlots'] = 'Rezervovatelné časové úseky';
        $strings['BlockedTimeSlots'] = 'Blokovaný časový úsek';
        $strings['ThisIsTheDefaultSchedule'] = 'Toto je základný rozvrh';
        $strings['DefaultScheduleCannotBeDeleted'] = 'základný rozvrh nemôže byť odstránený';
        $strings['MakeDefault'] = 'Nastaviť ako základný';
        $strings['BringDown'] = 'Znížit';
        $strings['ChangeLayout'] = 'Zmeniť rozvrhnutie';
        $strings['AddSchedule'] = 'Pridať rozvrh';
        $strings['StartsOn'] = 'Začína od';
        $strings['NumberOfDaysVisible'] = 'Viditelné dní';
        $strings['UseSameLayoutAs'] = 'Použiť rozvrhnutie ako';
        $strings['Format'] = 'Formát';
        $strings['OptionalLabel'] = 'Nepovinné pole';
        $strings['LayoutInstructions'] = 'Vložte každý časový úsek na jeden riadok. Časové úseky musia byť naplánováné na celý deň - 24 hodín';
        $strings['AddUser'] = 'Ručne pridať nového užívateľa';
        $strings['UserPermissionInfo'] = 'Aktuálny prístup ku kalendárom sa môže líšiť v závislosti na roli užľvateľa a skupiny oprávnenia alebo externým nastavením oprávnení';
        $strings['DeleteUserWarning'] = 'Po odstránení tohoto užívateľa odstránite aj jeho všetky sučasné, budúce a minulé rezervácie.';
        $strings['AddAnnouncement'] = 'Naplánovať prerušenie prevádzky';
        $strings['Announcement'] = 'Text pri úplnom prerušení prevádzky';
        $strings['Priority'] = 'Priorita';
        $strings['Reservable'] = 'Voľné';
        $strings['Unreservable'] = 'Zatvorené';
        $strings['Reserved'] = 'Rezervované';
        $strings['MyReservation'] = 'Moje rezervácie';
        $strings['Pending'] = 'Schvaľovanie';
        $strings['Past'] = 'Čas uplynul';
        $strings['Restricted'] = 'Mimo prevádzku';
        $strings['ViewAll'] = 'Zobraziť všetko';
        $strings['MoveResourcesAndReservations'] = 'Presunúť ihriská a rezervácie do';
        $strings['TurnOffSubscription'] = 'Vypnúť zapisovanie do kalendára';
        $strings['TurnOnSubscription'] = 'Zapnúť zapisovanie do kalendára';
        $strings['SubscribeToCalendar'] = 'Zapisovací kalendár';
        $strings['SubscriptionsAreDisabled'] = 'Administrátor zakázal zapisovanie do kalendára';
        $strings['NoResourceAdministratorLabel'] = '(Žiadny správca)';
        $strings['WhoCanManageThisResource'] = 'Kto môže spravovať tento prostriedok?';
        $strings['ResourceAdministrator'] = 'Správca prostriedku';
        $strings['Private'] = 'Súkromné';
        $strings['Accept'] = 'Potvrdiť';
        $strings['Decline'] = 'Zamietnuť';
        $strings['ShowFullWeek'] = 'Zobraziť celý týždeň';
        $strings['CustomAttributes'] = 'Upraviť atribúty';
        $strings['AddAttribute'] = 'Pridať atribút';
        $strings['EditAttribute'] = 'Upraviť atribút';
        $strings['DisplayLabel'] = 'Zobrazit pole';
        $strings['Type'] = 'Typ';
        $strings['Required'] = 'Povinné';
        $strings['ValidationExpression'] = 'Overenie termínu';
        $strings['PossibleValues'] = 'Možnosti';
        $strings['SingleLineTextbox'] = 'jednotné textové pole';
        $strings['MultiLineTextbox'] = 'Mnohonásobné textové pole';
        $strings['Checkbox'] = 'Zaškrtávací zoznam';
        $strings['SelectList'] = 'Výber z ponuky';
        $strings['CommaSeparated'] = 'oddeľujte čiarkou';
        $strings['Category'] = 'Kategórie';
        $strings['CategoryReservation'] = 'Rezervácie';
        $strings['CategoryGroup'] = 'Skupina';
        $strings['SortOrder'] = 'Poradie';
        $strings['Title'] = 'Nadpis';
        $strings['AdditionalAttributes'] = 'Dalšie atribúty';
        $strings['True'] = 'Áno';
        $strings['False'] = 'Nie';
        $strings['ForgotPasswordEmailSent'] = 'Na zadaný e-mail boly odeslané inštrukcie pre obnovenie hesla.';
        $strings['ActivationEmailSent'] = 'Čoskoro obdržíte aktivačný e-mail.';
        $strings['AccountActivationError'] = 'Ospravedlňujeme sa, Váš učet ešte nieje schválený.';
        $strings['Attachments'] = 'Prílohy';
        $strings['AttachFile'] = 'Príloha';
        $strings['Maximum'] = 'limit';
        $strings['NoScheduleAdministratorLabel'] = '(Žiadny správca)';
        $strings['ScheduleAdministrator'] = 'Správca Rozvrhu';
        $strings['Total'] = 'Celkom';
        $strings['QuantityReserved'] = 'Rezervované množstvo';
        $strings['AllAccessories'] = 'Všetko príslušenstvo';
        $strings['GetReport'] = 'Zobrazit prehľad';
        $strings['NoResultsFound'] = 'Nenájdená žiadna zhoda';
        $strings['SaveThisReport'] = 'Uložiť prehľad';
        $strings['ReportSaved'] = 'Prehľad uložený!';
        $strings['EmailReport'] = 'Poslať prehľad e-mailom';
        $strings['ReportSent'] = 'Prehľad odoslaný na e-mail!';
        $strings['RunReport'] = 'Spustiť prehľad';
        $strings['NoSavedReports'] = 'Nemáte uložený žiadny prehľad.';
        $strings['CurrentWeek'] = 'Tento týžden';
        $strings['CurrentMonth'] = 'Tento mesiac';
        $strings['AllTime'] = 'Všetko';
        $strings['FilterBy'] = 'Filtrovať podľa';
        $strings['Select'] = 'Typ prehľadu';
        $strings['List'] = 'Zoznam';
        $strings['TotalTime'] = 'Celkový čas';
        $strings['Count'] = 'Počet';
        $strings['Usage'] = 'Využitie';
        $strings['AggregateBy'] = 'Rozdelenie';
        $strings['Range'] = 'Obdobie';
        $strings['Choose'] = 'Vyberte';
        $strings['All'] = 'Všetko';
        $strings['ViewAsChart'] = 'Zobraziť ako graf';
        $strings['ReservedResources'] = 'Rezervované ihriská';
        $strings['ReservedAccessories'] = 'Rezervované príslušenstvo';
        $strings['ResourceUsageTimeBooked'] = 'Využitie prostriedkov - celkový čas';
        $strings['ResourceUsageReservationCount'] = 'Využitie prostriedkov - počet rezervácií';
        $strings['Top20UsersTimeBooked'] = '20 najväčších užívateľov - celkový čas';
        $strings['Top20UsersReservationCount'] = '20 najväčších užívateľov - počet rezervácií';
        $strings['ConfigurationUpdated'] = 'Konfiguračný súbor bol aktualizovaný';
        $strings['ConfigurationUiNotEnabled'] = 'Táto stránka nemôže byť zobrazená, preto že $conf[\'settings\'][\'pages\'][\'enable.configuration\'] je nastavená na hodnotu false alebo chýba.';
        $strings['ConfigurationFileNotWritable'] = 'Konfiguračný súbor nie je zapisovateľný. Skontrolujte prosím oprávnenie tohto súboru a skúste to znova.';
        $strings['ConfigurationUpdateHelp'] = 'Pozrite sa do časti Konfigurácia <a target=_blank href=%s>súbor Pomocníka</a> k dokumentácii o týchto nastaveniach.';
        $strings['GeneralConfigSettings'] = 'nastavenie';
        $strings['UseSameLayoutForAllDays'] = 'Použite rovnaké rozloženie pre všetky dni';
        $strings['LayoutVariesByDay'] = 'Plán sa líši podľa dňa';
        $strings['ManageReminders'] = 'Prípomienky';
        $strings['ReminderUser'] = 'Uživateľské ID';
        $strings['ReminderMessage'] = 'Správa';
        $strings['ReminderAddress'] = 'Adresa';
        $strings['ReminderSendtime'] = 'Čas odoslania';
        $strings['ReminderRefNumber'] = 'Referenčné číslo rezervácie';
        $strings['ReminderSendtimeDate'] = 'Dátum pripomienky';
        $strings['ReminderSendtimeTime'] = 'Čas prípomienky (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'Ráno / Poobede';
        $strings['AddReminder'] = 'Pridať prípomienku';
        $strings['DeleteReminderWarning'] = 'Ste si tým istý?';
        $strings['NoReminders'] = 'Nemáte žiadne nadchádzajúce prípomienky.';
        $strings['Reminders'] = 'Prípomienky';
        $strings['SendReminder'] = 'Poslať prípomienku';
        $strings['minutes'] = 'minút';
        $strings['hours'] = 'hodín';
        $strings['days'] = 'dní';
        $strings['ReminderBeforeStart'] = 'pred začiatkom času';
        $strings['ReminderBeforeEnd'] = 'pred koncom času';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS Súbor';
        $strings['ThemeUploadSuccess'] = 'Vaše zmeny boli uložené. Pre zobrazenie zmien refrešnite stránku.';
        $strings['MakeDefaultSchedule'] = 'Nastaviť toto zobrazenie ako moje základné';
        $strings['DefaultScheduleSet'] = 'Vaše základné zobrazenie bolo nastavené';
        $strings['FlipSchedule'] = 'Prepnúť zobrazenie';
        $strings['Next'] = 'Další';
        $strings['Success'] = 'Úspech';
        $strings['Participant'] = 'Pozvánka';
        $strings['ResourceFilter'] = 'Filter kalendára';
        $strings['ResourceGroups'] = 'Skupina kalendárov';
        $strings['AddNewGroup'] = 'Pridať novú skupinu';
        $strings['Quit'] = 'Ukončiť';
        $strings['AddGroup'] = 'Pridať skupinu';
        $strings['StandardScheduleDisplay'] = 'Použite týždenné zobrazenie kalendára';
        $strings['TallScheduleDisplay'] = 'Použite denné zobrazenie kalendára';
        $strings['WideScheduleDisplay'] = 'Zobraziť iba jeden deň';
        $strings['CondensedWeekScheduleDisplay'] = 'Zobraziť celý týždeň a počtom rezervácií';
        $strings['ResourceGroupHelp1'] = '(Drag and drop) ťahaj a pusti zdroj skupiny pre zmenu.';
        $strings['ResourceGroupHelp2'] = 'Kliknite pravým tlačidlom myši na názov skupiny zdrojov pre ďalšie akcie.';
        $strings['ResourceGroupHelp3'] = '(Drag and drop) ťahaj a pusti zdroje pre ich pridanie do skupín.';
        $strings['ResourceGroupWarning'] = 'Pri použití skupín kalendárov, musí byť každý zdroj priradený aspoň do jednej skupiny. Nepriradené zdroje nebudú môcť byť vyhradené.';
        $strings['ResourceType'] = 'Typ zdroja';
        $strings['AppliesTo'] = 'Platí pre';
        $strings['UniquePerInstance'] = 'Unikátny pre každú inštanciu';
        $strings['AddResourceType'] = 'Pridať typ zdrojov';
        $strings['NoResourceTypeLabel'] = '(žiadny typ zdroja sady)';
        $strings['ClearFilter'] = 'Vymazať filter';
        $strings['MinimumCapacity'] = 'Minimálna kapacita';
        $strings['Color'] = 'Farba';
        $strings['Available'] = 'Dostupné';
        $strings['Unavailable'] = 'Nie je k dispozícii';
        $strings['Hidden'] = 'Skryté';
        $strings['ResourceStatus'] = 'Stav kalendárov';
        $strings['CurrentStatus'] = 'Súčasný stav';
        $strings['AllReservationResources'] = 'Všetky zdroje rezervácií';
        $strings['File'] = 'Súbor';
        $strings['BulkResourceUpdate'] = 'Aktualizácia množstva zdrojov ';
        $strings['Unchanged'] = 'Nezmenený';
        $strings['Common'] = 'Spoločný';
        $strings['AdvancedFilter'] = 'Rozšírený filter';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'nainštalujte phpScheduleIt (MySQL only)';
        $strings['IncorrectInstallPassword'] = 'Sorry, that password was incorrect.';
        $strings['SetInstallPassword'] = 'You must set an install password before the installation can be run.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'There is no upgrade needed. Running the installation http://www.rezervacie.eurogalaxy.sk/s will delete all existing data and install a new copy of phpScheduleIt!';
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
        $strings['InstalledVersion'] = 'You are now running version %s of phpScheduleIt';
        $strings['InstallUpgradeConfig'] = 'It is recommended to upgrade your config file';
        $strings['InstallationFailure'] = 'There were problems with the installation.  Please correct them and retry the installation.';
        $strings['ConfigureApplication'] = 'Configure LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Your config file is now up to date!';
        $strings['ConfigUpdateFailure'] = 'We could not automatically update your config file. Please overwrite the contents of config.php with the following:';
        $strings['SelectUser'] = 'Vybrať užívateľa';
        // End Install

        // Errors
        $strings['LoginError'] = 'Chybne zadané uživateľské meno alebo heslo.';
        $strings['ReservationFailed'] = 'Vaša rezervácia nemôže byť vytvorená.';
        $strings['MinNoticeError'] = 'Táto rezervácia musí obsahovať popis. Najstarší dátum, v ktorom môže byť urobená rezervácia je %s.';
        $strings['MaxNoticeError'] = 'Táto rezervácia nemôže byť naplánovaná tak ďaleko. Najstarší dátum, v ktorom môže byť urobená rezervácia je %s.';
        $strings['MinDurationError'] = 'Táto rezervácia musí byť dlhšia než %s.';
        $strings['MaxDurationError'] = 'Táto rezervácia nemôže trvať dlhšie než %s.';
        $strings['ConflictingAccessoryDates'] = 'Zatiaľ je tu obmedzený počet príslušenstva:';
        $strings['NoResourcePermission'] = 'Nemáte oprávnenie k jednemu alebo viac požadovaným prostriedkom';
        $strings['ConflictingReservationDates'] = 'Tu je výpis rezervácií, ktoré sú v konflikte s Vami vytvorenou:';
        $strings['StartDateBeforeEndDateRule'] = 'Začiatok rezervácie musí začínať skôr ako jej koniec.';
        $strings['StartIsInPast'] = 'Začiatok rezervácie nemôže být vytvorený v minulosti';
        $strings['EmailDisabled'] = 'Administrátor zakázal odosielanie e-mailových upozornení.';
        $strings['ValidLayoutRequired'] = 'Časový úsek musí byť vytvorený na celý deň - 24 hodín';
        $strings['CustomAttributeErrors'] = 'Chybné s dalšími hodnotami:';
        $strings['CustomAttributeRequired'] = '%s je povinné pole';
        $strings['CustomAttributeInvalid'] = 'Hodnota pre %s je chybná';
        $strings['AttachmentLoadingError'] = 'Ospravedlňujeme sa, došlo k chybe pri načítání požadovaného súboru.';
        $strings['InvalidAttachmentExtension'] = 'Môžete nahrať iba súbory týchto typov: %s';
        $strings['InvalidStartSlot'] = 'Dátum a čas začatia o ktorý žiadate nie je platný.';
        $strings['InvalidEndSlot'] = 'Dátum a čas ukončenia o ktorý žiadate nie je platný.';
        $strings['MaxParticipantsError'] = '%s môže iba podpora %s účastníci.';
        $strings['ReservationCriticalError'] = 'Pri ukladaní Vašich rezervácií nastala chyba. Ak by tento trend pokračoval, obráťte sa na správcu systému.';
        $strings['InvalidStartReminderTime'] = 'Počiatočný čas pripomenutia nie je platný.';
        $strings['InvalidEndReminderTime'] = 'Koniec času pripomenutia nie je platný.';
        $strings['QuotaExceeded'] = 'Maximálna kvóta prekročená.';
        $strings['MultiDayRule'] = '%s neumožňuje rezervácie viac dní.';
        $strings['InvalidReservationData'] = 'Nastali problémy s Vašou žiadosťou o rezerváciu.';
        $strings['PasswordError'] = 'Heslo musí obsahovať aspoň %s písmeno/á a aspoň %s číslo/a.';
        $strings['PasswordErrorRequirements'] = 'Heslo musí obsahovať kombináciu aspoň %s veľké a malé písmeno/á a %s číslo/a.';
        $strings['NoReservationAccess'] = 'Nie ste oprávnený meniť túto rezerváciu.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Vytvoriť rezerváciu';
        $strings['EditReservation'] = 'Upravovanie rezervácie';
        $strings['LogIn'] = 'Prihlásiť';
        $strings['ManageReservations'] = 'Rezervácie';
        $strings['AwaitingActivation'] = 'Čaká na aktiváciu';
        $strings['PendingApproval'] = 'Prebieha schvalovanie';
        $strings['ManageSchedules'] = 'Rozvrhy';
        $strings['ManageResources'] = 'Ihriská';
        $strings['ManageAccessories'] = 'Príslušenstvo';
        $strings['ManageUsers'] = 'Užívatelia';
        $strings['ManageGroups'] = 'Skupiny';
        $strings['ManageQuotas'] = 'Kvóty';
        $strings['ManageBlackouts'] = 'Termíny prerušenia prevádzky';
        $strings['MyDashboard'] = 'Hlavná stránka';
        $strings['ServerSettings'] = 'Informácia o servere';
        $strings['Dashboard'] = 'Hlavný prehľad';
        $strings['Help'] = 'Nápoveda';
        $strings['Administration'] = 'Administrácia';
        $strings['About'] = 'O nás';
        $strings['Bookings'] = 'Rezervácie';
        $strings['Schedule'] = 'Prehľad rezervácií';
        $strings['Reservations'] = 'Rezervácie';
        $strings['Account'] = 'Účet';
        $strings['EditProfile'] = 'Upraviť vlastný profil';
        $strings['FindAnOpening'] = 'Nájsť otvorenie';
        $strings['OpenInvitations'] = 'Zobraziť pozvanie';
        $strings['MyCalendar'] = 'Kalendár mojích rezervácií';
        $strings['ResourceCalendar'] = 'Kalendár všetkých rezervácií';
        $strings['Reservation'] = 'Nová rezervácia';
        $strings['Install'] = 'Inštalácia';
        $strings['ChangePassword'] = 'Zmeniť heslo';
        $strings['MyAccount'] = 'Môj účet';
        $strings['Profile'] = 'Nastavenie profilu';
        $strings['ApplicationManagement'] = 'Správa systému';
        $strings['ForgotPassword'] = 'zabudnuté heslo';
        $strings['NotificationPreferences'] = 'Nastavenie oznámenia e-mailem';
        $strings['ManageAnnouncements'] = 'Úplné prerušenie prevádzky';
        $strings['Responsibilities'] = 'Správa';
        $strings['GroupReservations'] = 'Skupinová rezervácia';
        $strings['ResourceReservations'] = 'Rezervácia prostriedkov';
        $strings['Customization'] = 'Prispôsobenie';
        $strings['Attributes'] = 'Atribúty';
        $strings['AccountActivation'] = 'Aktivácia účtov';
        $strings['ScheduleReservations'] = 'Rezervácia kalendárov';
        $strings['Reports'] = 'Prehľady';
        $strings['GenerateReport'] = 'Vytvoriť nový prehľad';
        $strings['MySavedReports'] = 'Moje uložené prehľady';
        $strings['CommonReports'] = 'Spoločné prehľady';
        $strings['ViewDay'] = 'Zobraziť denný prehľad';
        $strings['Group'] = 'Skupina';
        $strings['ManageConfiguration'] = 'Konfigurácia';
        $strings['LookAndFeel'] = 'Vzhľad';
        $strings['ManageResourceGroups'] = 'Skupiny kalendárov';
        $strings['ManageResourceTypes'] = 'Typy kalendárov';
        $strings['ManageResourceStatus'] = 'Stavy kalendárov';
        // End Page Titles


        // Day representations
        $strings['DaySundaySingle'] = 'Ne';
        $strings['DayMondaySingle'] = 'Po';
        $strings['DayTuesdaySingle'] = 'Út';
        $strings['DayWednesdaySingle'] = 'St';
        $strings['DayThursdaySingle'] = 'Št';
        $strings['DayFridaySingle'] = 'Pia';
        $strings['DaySaturdaySingle'] = 'So';
        $strings['DaySundaySingle'] = 'Ne';

        $strings['DaySundayAbbr'] = 'Ne';
        $strings['DayMondayAbbr'] = 'Po';
        $strings['DayTuesdayAbbr'] = 'Út';
        $strings['DayWednesdayAbbr'] = 'St';
        $strings['DayThursdayAbbr'] = 'Št';
        $strings['DayFridayAbbr'] = 'Pia';
        $strings['DaySaturdayAbbr'] = 'So';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Vaša rezervácia bola potvrdená';
        $strings['ReservationCreatedSubject'] = 'Rezervácia bola vytvorená';
        $strings['ReservationUpdatedSubject'] = 'Rezervácia bola upravená';
        $strings['ReservationDeletedSubject'] = 'Rezervácia bola zrušená';
        $strings['ReservationCreatedAdminSubject'] = 'Upozornenie: rezervácia vytvorená';
        $strings['ReservationUpdatedAdminSubject'] = 'Upozornenie: rezervácia upravená';
        $strings['ReservationDeleteAdminSubject'] = 'Upozornenie: rezervácia zrušená';
        $strings['ParticipantAddedSubject'] = 'Upozornenie: rezervácia účastníkov';
        $strings['ParticipantDeletedSubject'] = 'Rezervácia  zrušená';
        $strings['InviteeAddedSubject'] = 'Pozvánka do rezervácie';
        $strings['ResetPassword'] = 'Požiadavka na resetovanie hesla';
        $strings['ActivateYourAccount'] = 'Prosíme Vás, aktivujte si svoj účet';
        $strings['ReportSubject'] = 'Vaše požadované správy (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Rezervácia pre %s začína čoskoro';
        $strings['ReservationEndingSoonSubject'] = 'Rezervácia pre %s končí čoskoro';
        $strings['UserAdded'] = 'Nový užívateľ bol pridaný';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nemáte žiadne predchádzajúce rezervácie';
        $strings['PastReservations'] = 'Predchádzajúce rezervácie';
        $strings['AllNoPastReservations'] = 'Žiadne predchádzajúce rezervácie v posledných %s dňoch';
        $strings['AllPastReservations'] = 'Všetky predchádzajúce rezervácie';
        $strings['Yesterday'] = 'Včera';
        $strings['EarlierThisWeek'] = 'Skôr tento týždeň';
        $strings['PreviousWeek'] = 'Predchádzajúci týždeň';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Vaša skupina nemá žiadne nadchádzajúce rezervácie';
        $strings['GroupUpcomingReservations'] = 'Nadchádzajúce rezervácie mojej skupiny(y)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Pri prihlásení cez Facebook sa vyskytla chyba. Skúste to prosím znovu.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nemáte žiadne rezervácie čakajúce na schválenie';
        $strings['PendingApprovalReservations'] = 'Rezervácie čakajúce na schválenie';
        $strings['LaterThisMonth'] = 'Neskôr tento mesiac';
        $strings['LaterThisYear'] = 'Neskôr tento rok';
        $strings['Remaining'] = 'Zostáva';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Žiadne chýbajúce rezervácie na odchod';
        $strings['MissingCheckOutReservations'] = 'Chýbajúce rezervácie na odchod';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Nie je možné zobraziť podrobnosti o rezervácii, pretože nemáte oprávnenie k žiadnym zdrojom v tejto rezervácii';
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
        DAY NAMES
        All of these arrays MUST start with Sunday as the first element
        and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Nedela', 'Pondelok', 'Útorok', 'Streda', 'Štvrtok', 'Piatok', 'Sobota'];
        // The three letter abbreviation
        $days['abbr'] = ['Ne', 'Po', 'Út', 'St', 'Št', 'Pia', 'So'];
        // The two letter abbreviation
        $days['two'] = ['Ne', 'Po', 'Út', 'St', 'Št', 'Pia', 'So'];
        // The one letter abbreviation
        $days['letter'] = ['Ne', 'Po', 'Út', 'St', 'Št', 'Pia', 'So'];

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
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['Január', 'Február', 'Marec', 'Apríl', 'Máj', 'Jún', 'Júl', 'August', 'September', 'Október', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Január', 'Február', 'Marec', 'Apríl', 'Máj', 'Jún', 'Júl', 'August', 'September', 'Október', 'November', 'December'];

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
        return 'sk';
    }
}
