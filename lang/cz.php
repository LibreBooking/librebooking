<?php

require_once('en_gb.php');

class cz extends en_us
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

        $dates['general_date'] = 'j.n.Y';
        $dates['general_datetime'] = 'j.n.Y H:i:s';
        $dates['short_datetime'] = 'j.n.Y H:i';
        $dates['schedule_daily'] = 'l, j.n.Y';
        $dates['reservation_email'] = 'j.n.Y \v H:i';
        $dates['res_popup'] = 'j.n. H:i';
        $dates['res_popup_time'] = 'H:i';
        $dates['short_reservation_date'] = 'j.n.Y H:i';
        $dates['dashboard'] = 'j.n. H:i';
        $dates['period_time'] = 'H:i';
        $dates['mobile_reservation_date'] = 'j.n. H:i';
        $dates['general_date_js'] = 'd.m.yy';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'D.M.YY hh:mm';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'j.n.';
        $dates['embedded_date'] = 'j.n.';
        $dates['embedded_time'] = 'H:i';
        $dates['embedded_datetime'] = 'j.n. H:i';
        $dates['report_date'] = '%d.%m.';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Jméno';
        $strings['LastName'] = 'Příjmení';
        $strings['Timezone'] = 'Časová zóna';
        $strings['Edit'] = 'Upravit';
        $strings['Change'] = 'Změnit';
        $strings['Rename'] = 'Přejmenovat';
        $strings['Remove'] = 'Odstranit';
        $strings['Delete'] = 'Smazat';
        $strings['Update'] = 'Uložit';
        $strings['Cancel'] = 'Zrušit';
        $strings['Add'] = 'Přidat';
        $strings['Name'] = 'Název';
        $strings['Yes'] = 'Ano';
        $strings['No'] = 'Ne';
        $strings['FirstNameRequired'] = 'Jméno je povinné.';
        $strings['LastNameRequired'] = 'Příjmení je povinné.';
        $strings['PwMustMatch'] = 'Hesla se neshodují.';
        $strings['ValidEmailRequired'] = 'E-mailová adresa je povinná.';
        $strings['UniqueEmailRequired'] = 'Tato e-mailová adresa je již v systému zaregistrována.';
        $strings['UniqueUsernameRequired'] = 'Toto uživatelské jméno je již v systému zaregistrované.';
        $strings['UserNameRequired'] = 'Uživatelské jméno je povinné.';
        $strings['CaptchaMustMatch'] = 'Opište bezpečnostní kód z obrázku.';
        $strings['Today'] = 'Dnes';
        $strings['Week'] = 'Týden';
        $strings['Month'] = 'Měsíc';
        $strings['BackToCalendar'] = 'Zpět do kalendáře';
        $strings['BeginDate'] = 'Začátek';
        $strings['EndDate'] = 'Konec';
        $strings['Username'] = 'Uživatelské jméno';
        $strings['Password'] = 'Heslo';
        $strings['PasswordConfirmation'] = 'Potvrdit heslo';
        $strings['DefaultPage'] = 'Výchozí hlavní stránka';
        $strings['MyCalendar'] = 'Můj kalendář';
        $strings['ScheduleCalendar'] = 'Plánovací kalendář';
        $strings['Registration'] = 'Registrace';
        $strings['NoAnnouncements'] = 'Není naplánováno žádné dočasné uzavření provozu.';
        $strings['Announcements'] = 'Omezení provozu';
        $strings['NoUpcomingReservations'] = 'Nyní nemáte žádné naplánované rezervace.';
        $strings['UpcomingReservations'] = 'Vaše naplánované rezervace';
        $strings['AllNoUpcomingReservations'] = 'Žádné naplánované rezervace.';
        $strings['AllUpcomingReservations'] = 'Všechny naplánované rezervace.';
        $strings['ShowHide'] = 'Zobrazit/skrýt';
        $strings['Error'] = 'Chyba';
        $strings['ReturnToPreviousPage'] = 'Vrátit se zpět';
        $strings['UnknownError'] = 'Neznámá chyba';
        $strings['InsufficientPermissionsError'] = 'Nemáte oprávnení';
        $strings['MissingReservationResourceError'] = 'Nebyl vybrán prostředek';
        $strings['MissingReservationScheduleError'] = 'Nebyl zaškrtnut žádný den';
        $strings['DoesNotRepeat'] = 'Neopakovat';
        $strings['Daily'] = 'Denní';
        $strings['Weekly'] = 'Týdenní';
        $strings['Monthly'] = 'Měsíční';
        $strings['Yearly'] = 'Roční';
        $strings['RepeatPrompt'] = 'Opakování';
        $strings['hours'] = 'hodina';
        $strings['days'] = '. den';
        $strings['weeks'] = '. týden';
        $strings['months'] = '. měsíc';
        $strings['years'] = '. rok';
        $strings['day'] = 'den';
        $strings['week'] = 'týden';
        $strings['month'] = 'měsíc';
        $strings['year'] = 'rok';
        $strings['repeatDayOfMonth'] = 'dny měsíce';
        $strings['repeatDayOfWeek'] = 'dny týdne';
        $strings['RepeatUntilPrompt'] = 'konec opakované rezervace';
        $strings['RepeatEveryPrompt'] = 'každý';
        $strings['RepeatDaysPrompt'] = 'Opakovat v dny';
        $strings['CreateReservationHeading'] = 'Vytváření rezervace';
        $strings['EditReservationHeading'] = 'Úprava rezervace: %s';
        $strings['ViewReservationHeading'] = 'Zobrazit rezervaci: %s';
        $strings['ReservationErrors'] = 'Změnit rezervaci';
        $strings['Create'] = 'Vytvořit';
        $strings['ThisInstance'] = 'Pouze v tomto případě';
        $strings['AllInstances'] = 'Po celou dobu';
        $strings['FutureInstances'] = 'Všechny v budoucnu';
        $strings['Print'] = 'Tisknout';
        $strings['ShowHideNavigation'] = 'zobrazit/skrýt navigaci';
        $strings['ReferenceNumber'] = 'Referenční číslo';
        $strings['Tomorrow'] = 'Zítra';
        $strings['LaterThisWeek'] = 'Později v tento týden';
        $strings['NextWeek'] = 'Následující týden';
        $strings['SignOut'] = 'Odhlásit se';
        $strings['LayoutDescription'] = 'Začátek %s, zobrazeno %s dnů';
        $strings['AllResources'] = 'Všechny přístoje';
        $strings['TakeOffline'] = 'Vypnout';
        $strings['BringOnline'] = 'Zapnout';
        $strings['AddImage'] = 'Přidat obrázek';
        $strings['NoImage'] = 'Žádný obrázek';
        $strings['Move'] = 'Přesunout';
        $strings['AppearsOn'] = 'Objeví se na %s';
        $strings['Location'] = 'Umístění';
        $strings['NoLocationLabel'] = '(umístění nenastaveno)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(kontakt nenastaven)';
        $strings['Description'] = 'Popis';
        $strings['NoDescriptionLabel'] = '(bez popisku)';
        $strings['Notes'] = 'Poznámky';
        $strings['NoNotesLabel'] = '(bez poznámek)';
        $strings['NoTitleLabel'] = '(bez názvu)';
        $strings['UsageConfiguration'] = 'Použít konfiguraci';
        $strings['ChangeConfiguration'] = 'Změnit konfiguraci';
        $strings['ResourceMinLength'] = 'Rezervace musí být delší než %s';
        $strings['ResourceMinLengthNone'] = 'Neexistuje žádná minimální doba rezervace';
        $strings['ResourceMaxLength'] = 'Rezervace musí být kratší než %s';
        $strings['ResourceMaxLengthNone'] = 'Neexistuje žádná maximální doba rezervace';
        $strings['ResourceRequiresApproval'] = 'Rezervace musí být schváleny';
        $strings['ResourceRequiresApprovalNone'] = 'Rezervace není třeba potvrzovat';
        $strings['ResourcePermissionAutoGranted'] = 'Povolení je automaticky získáno';
        $strings['ResourcePermissionNotAutoGranted'] = 'Povolení není automaticky získáno';
        $strings['ResourceMinNotice'] = 'Rezervace musí být uskutečněna nejméně %s před začátkem';
        $strings['ResourceMinNoticeNone'] = 'Rezervace lze provést až do současné doby';
        $strings['ResourceMinNoticeUpdate'] = 'Rezervace může být upravena nejpozději %s před začátkem';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Rezervace může být upravena kdykoliv před začátekem';
        $strings['ResourceMinNoticeDelete'] = 'Rezervace může být smazána nejpozději %s před začátkem';
        $strings['ResourceMinNoticeNoneDelete'] = 'Rezervace může být smazána kdykoliv před začátekem';
        $strings['ResourceMaxNotice'] = 'Rezervace nesmí končit více než %s před současností';
        $strings['ResourceMaxNoticeNone'] = 'Rezervace může skončit kdykoliv v budoucnu';
        $strings['ResourceBufferTime'] = 'Mezi rezervacemi musí být pauza %s.';
        $strings['ResourceBufferTimeNone'] = 'Mezi rezervacemi není pauza.';
        $strings['ResourceAllowMultiDay'] = 'Rezervace může být vytvořena na několik dnů';
        $strings['ResourceNotAllowMultiDay'] = 'Rezervace nelze provádět v rámci několika dnů';
        $strings['ResourceCapacity'] = 'Tento přístoj má omezenou kapacitu na %s osob';
        $strings['ResourceCapacityNone'] = 'Tento prostředek má neomezenou kapacitu';
        $strings['AddNewResource'] = 'Přidat nový prostředek';
        $strings['AddNewUser'] = 'Přidat nového uživatele';
        $strings['AddUser'] = 'Přidat uživatele';
        $strings['Schedule'] = 'Plánování';
        $strings['AddResource'] = 'Přidat prostředek';
        $strings['Capacity'] = 'Kapacita';
        $strings['Access'] = 'Přístup';
        $strings['Duration'] = 'Trvání';
        $strings['Active'] = 'Aktivní';
        $strings['Inactive'] = 'Vypnuto';
        $strings['ResetPassword'] = 'Obnovit heslo';
        $strings['LastLogin'] = 'Poslední přihlášení';
        $strings['Search'] = 'Hledání';
        $strings['ResourcePermissions'] = 'Oprávnění prostředků';
        $strings['Reservations'] = 'Rezervace';
        $strings['Groups'] = 'Skupiny';
        $strings['ResetPassword'] = 'Resetovat heslo';
        $strings['AllUsers'] = 'Všichni uživatelé';
        $strings['AllGroups'] = 'Všechny skupiny';
        $strings['AllSchedules'] = 'Všechna plánování';
        $strings['UsernameOrEmail'] = 'Uživatelské jméno nebo e-mail';
        $strings['Members'] = 'Členové';
        $strings['QuickSlotCreation'] = 'Vytvořit místo každých %s minut mezi %s a %s';
        $strings['ApplyUpdatesTo'] = 'provést update';
        $strings['CancelParticipation'] = 'Zrušení účastníků';
        $strings['Attending'] = 'Účast';
        $strings['QuotaConfiguration'] = 'pro %s pro %s uživatelům v %s omezení počtu %s %s na %s';
        $strings['reservations'] = 'rezervací';
        $strings['reservation'] = 'rezervace';
        $strings['ChangeCalendar'] = 'Změnit kalendář';
        $strings['AddQuota'] = 'Přdat kvótu';
        $strings['FindUser'] = 'Najít uživatele';
        $strings['Created'] = 'Vytvořeno';
        $strings['LastModified'] = 'Poslední úprava';
        $strings['GroupName'] = 'Název skupiny';
        $strings['GroupMembers'] = 'Členové skupiny';
        $strings['GroupRoles'] = 'Role skupiny';
        $strings['GroupAdmin'] = 'Administrátor skupiny';
        $strings['Actions'] = 'Akce';
        $strings['CurrentPassword'] = 'Současné heslo';
        $strings['NewPassword'] = 'Nové heslo';
        $strings['InvalidPassword'] = 'Bylo chybně zadáno současné heslo.';
        $strings['PasswordChangedSuccessfully'] = 'Vaše nové heslo bylo úspěšně nastaveno';
        $strings['SignedInAs'] = 'Přihlášen jako';
        $strings['NotSignedIn'] = 'Nepřihlášený';
        $strings['ReservationTitle'] = 'Název rezervace';
        $strings['ReservationDescription'] = 'Volitelný popis';
        $strings['ResourceList'] = 'Rezervované prostředky';
        $strings['Accessories'] = 'Příslušenství';
        $strings['ParticipantList'] = 'Seznam účastníků';
        $strings['InvitationList'] = 'Pozvání';
        $strings['AccessoryName'] = 'Název příslušenství';
        $strings['QuantityAvailable'] = 'Dostupné množství';
        $strings['Resources'] = 'Prostředky';
        $strings['Participants'] = 'Účastníci';
        $strings['User'] = 'Uživatel';
        $strings['Resource'] = 'Prostředek';
        $strings['Status'] = 'Stav';
        $strings['Approve'] = 'Schválit';
        $strings['Page'] = 'Strana';
        $strings['Rows'] = 'Řádek';
        $strings['Unlimited'] = 'neomezeno';
        $strings['Email'] = 'E-mail';
        $strings['EmailAddress'] = 'E-mailová adresa';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Společnost';
        $strings['Position'] = 'Pozice';
        $strings['Language'] = 'Jazyk';
        $strings['Permissions'] = 'Oprávnění';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Najít skupinu';
        $strings['Manage'] = 'Spravovat';
        $strings['None'] = 'Žádné';
        $strings['AddToOutlook'] = 'Přidat do Outlooku';
        $strings['AddToGoogleCalendar'] = 'Přidat do Google';
        $strings['DuplicateReservation'] = 'Duplikovat';
        $strings['Done'] = 'Hotovo';
        $strings['RememberMe'] = 'Přihlásit se trvale';
        $strings['FirstTimeUser?'] = 'Nemáte účet?';
        $strings['CreateAnAccount'] = 'Registrovat se';
        $strings['ViewSchedule'] = 'Zobrazit plánování';
        $strings['ForgotMyPassword'] = 'Zapomenuté heslo';
        $strings['YouWillBeEmailedANewPassword'] = 'Na zadaný e-mail Vám bude zasláno nově vygenerované heslo.';
        $strings['Close'] = 'Zavřít';
        $strings['ExportToCSV'] = 'Exportovat do souboru CSV';
        $strings['OK'] = 'Odeslat';
        $strings['Working'] = 'Načítám';
        $strings['Login'] = 'Přihlášení';
        $strings['AdditionalInformation'] = 'Další informace.';
        $strings['AllFieldsAreRequired'] = 'Všechna pole jsou povinná';
        $strings['Optional'] = 'Nepovinné';
        $strings['YourProfileWasUpdated'] = 'Váš profil by aktualizován.';
        $strings['YourSettingsWereUpdated'] = 'Vaše nastavení bylo provedeno';
        $strings['Register'] = 'Registrovat';
        $strings['SecurityCode'] = 'Bezpečnostní kód';
        $strings['ReservationCreatedPreference'] = 'Když vytvořím rezervaci, nebo když je rezervace mému účtu vytvořena.';
        $strings['ReservationUpdatedPreference'] = 'Když změním rezervaci, nebo když je rezervace mému účtu změněna.';
        $strings['ReservationDeletedPreference'] = 'Když smažu rezervaci, nebo když je rezervace mému účtu smazána.';
        $strings['ReservationApprovalPreference'] = 'Když je má rezervace potvrzena';
        $strings['PreferenceSendEmail'] = 'Oznámit e-mailem';
        $strings['PreferenceNoEmail'] = 'Neoznamovat';
        $strings['ReservationCreated'] = 'Rezervace byla vytvořena.';
        $strings['ReservationUpdated'] = 'Rezervace byla upravena.';
        $strings['ReservationRemoved'] = 'Rezervace byla odstraněna.';
        $strings['ReservationRequiresApproval'] = 'Jedna nebo více rezervací vyžadují schválení. Do té doby budou označeny jako nevyřízené.';
        $strings['YourReferenceNumber'] = 'Referenční číslo: %s';
        $strings['UpdatingReservation'] = 'Obnovení rezervace';
        $strings['ChangeUser'] = 'Změnit uživatele';
        $strings['MoreResources'] = 'Přidat další prostředek';
        $strings['ReservationLength'] = 'Délka rezervace';
        $strings['ParticipantList'] = 'Seznam účastníků';
        $strings['AddParticipants'] = 'Přidat účastníka';
        $strings['InviteOthers'] = 'Pozvat ostatní';
        $strings['AddResources'] = 'Přidat prostředky';
        $strings['AddAccessories'] = 'Přidat příslušenství';
        $strings['Accessory'] = 'Příslušenství';
        $strings['QuantityRequested'] = 'Požadované množství';
        $strings['CreatingReservation'] = 'Vytváření rezervace';
        $strings['UpdatingReservation'] = 'Úprava rezervace';
        $strings['DeleteWarning'] = 'Tato akce je trvalá a nelze ji vrátit zpět!';
        $strings['DeleteAccessoryWarning'] = 'Při odstranění tohoto příslušenství jej odstraníte také ze všech rezervací.';
        $strings['AddAccessory'] = 'Přidat příslušenství';
        $strings['AddBlackout'] = 'Přidat dobu užavření';
        $strings['AllResourcesOn'] = 'všechny prostředky na';
        $strings['Reason'] = 'Odůvodnění';
        $strings['BlackoutShowMe'] = 'Zobrazit rezervace, které jsou v konfliktu s jinými';
        $strings['BlackoutDeleteConflicts'] = 'Odstranit rezervace, které jsou v konfliktu s jinými';
        $strings['Filter'] = 'Filtr';
        $strings['Between'] = 'Mezi';
        $strings['CreatedBy'] = 'Vytvořil';
        $strings['BlackoutCreated'] = 'Doba uzavření byla nastavena.';
        $strings['BlackoutNotCreated'] = 'Doba uzavření nebyla nastavena';
        $strings['BlackoutUpdated'] = 'Doba uzavření byla upravena.';
        $strings['BlackoutNotUpdated'] = 'Doba uzavření nemůže být upravena.';
        $strings['BlackoutConflicts'] = 'Jsou zde časy zavřeného provozu v konfliktu s jinými';
        $strings['ReservationConflicts'] = 'Jsou zde rezervované čase v konfliktu s jinými';
        $strings['UsersInGroup'] = 'Uživatelé v této skupině';
        $strings['Browse'] = 'Prohledat';
        $strings['DeleteGroupWarning'] = 'Odstraněním této skupiny budou odstraněny všechny související oprávnění k prostředkům. Uživatelé v této skupině mohou přijít o přístup k prostředkům.';
        $strings['WhatRolesApplyToThisGroup'] = 'Jaká role se vztahuje k této skupině?';
        $strings['WhoCanManageThisGroup'] = 'Kdo může spravovat tuto skupinu?';
        $strings['WhoCanManageThisSchedule'] = 'Kdo může spravovat tento kalendář?';
        $strings['AddGroup'] = 'Přidat skupinu';
        $strings['AllQuotas'] = 'Všechny kvóty';
        $strings['QuotaReminder'] = 'Nezapomeňte: Kvóty se uplatňují na základě nastavení časového pásma.';
        $strings['AllReservations'] = 'Všechny rezervace';
        $strings['PendingReservations'] = 'Nevyřízené rezervace';
        $strings['Approving'] = 'Schvalování';
        $strings['MoveToSchedule'] = 'Přesunout do plánování';
        $strings['DeleteResourceWarning'] = 'Odstraněním tohoto prostředku vymažete všechna související data.';
        $strings['DeleteResourceWarningReservations'] = 'všechny minulé, současné a budoucí rezervace s ním spojené';
        $strings['DeleteResourceWarningPermissions'] = 'všechna přiřazená povolení';
        $strings['DeleteResourceWarningReassign'] = 'Prosím přeřaďte cokoli, co nechcete, aby bylo vymazáno.';
        $strings['ScheduleLayout'] = 'Rozvržení (časy v %s)';
        $strings['ReservableTimeSlots'] = 'Rezervovatelné časové úseky';
        $strings['BlockedTimeSlots'] = 'Blokovaný časový úsek';
        $strings['ThisIsTheDefaultSchedule'] = 'Toto je výchozí plánování';
        $strings['DefaultScheduleCannotBeDeleted'] = 'výchozí plánování nemůže být odstraněno';
        $strings['MakeDefault'] = 'Vytvořit jako výchozí';
        $strings['BringDown'] = 'Snížit';
        $strings['ChangeLayout'] = 'Změnit rozvržení';
        $strings['AddSchedule'] = 'Přidat plánování';
        $strings['StartsOn'] = 'Začíná od';
        $strings['NumberOfDaysVisible'] = 'Viditelné dnů';
        $strings['UseSameLayoutAs'] = 'Použít rozvržení jako';
        $strings['Format'] = 'Formát';
        $strings['OptionalLabel'] = 'Nepovinné pole';
        $strings['LayoutInstructions'] = 'Vložte každý časový úsek na jeden řádek. Časové úseky musí být naplánovány na celý den - 24hodin';
        $strings['AddUser'] = 'Ruční přidání nového uživatele';
        $strings['UserPermissionInfo'] = 'Aktuální přístup k prostředku se může lišit v závislosti na roli uživatele a skupiny oprávnění nebo externím nastavení oprávnění';
        $strings['DeleteUserWarning'] = 'Po odstranění tohoto uživatele odstraníte také jeho všechny současné, budoucí a minulé rezervace.';
        $strings['AddAnnouncement'] = 'Naplánovat uzavření provozu';
        $strings['Announcement'] = 'Text při úplném uzavření provozu';
        $strings['Priority'] = 'Priorita';
        $strings['Reservable'] = 'Volné';
        $strings['Unreservable'] = 'Zavřeno';
        $strings['Reserved'] = 'Rezervováno';
        $strings['MyReservation'] = 'Mé rezervace';
        $strings['Pending'] = 'Před schválením';
        $strings['Past'] = 'Minulost';
        $strings['Restricted'] = 'Mimo provoz';
        $strings['ViewAll'] = 'Zobrazit vše';
        $strings['MoveResourcesAndReservations'] = 'Přesunout prostředek a rezervace do';
        $strings['TurnOffSubscription'] = 'Vypnout zapisování do kalendáře';
        $strings['TurnOnSubscription'] = 'Zapnout zapisování do kalendáře';
        $strings['SubscribeToCalendar'] = 'Zapisovací kalendář';
        $strings['SubscriptionsAreDisabled'] = 'Administrátor zakázal zapisování do kalendáře';
        $strings['NoResourceAdministratorLabel'] = '(Žádné administrátorské prostředky)';
        $strings['WhoCanManageThisResource'] = 'Kdo může spravovat tyto prostředku?';
        $strings['ResourceAdministrator'] = 'Administrátorské prostředky';
        $strings['Private'] = 'Rezervováno';
        $strings['Accept'] = 'Potvrzeno';
        $strings['Decline'] = 'Zamítnuté';
        $strings['ShowFullWeek'] = 'Zobrazit celý týden';
        $strings['CustomAttributes'] = 'Upravit atributy';
        $strings['AddAttribute'] = 'Přidat atribut';
        $strings['EditAttribute'] = 'Upravit atribut';
        $strings['DisplayLabel'] = 'Zobrazit pole';
        $strings['Type'] = 'Typ';
        $strings['Required'] = 'Povinné';
        $strings['ValidationExpression'] = 'Oveření výrazu';
        $strings['PossibleValues'] = 'Možnosti';
        $strings['SingleLineTextbox'] = 'jednotné textové pole';
        $strings['MultiLineTextbox'] = 'Mnohonásobné textové pole';
        $strings['Checkbox'] = 'Zaškrtávací seznam';
        $strings['SelectList'] = 'Výběr z nabídky';
        $strings['CommaSeparated'] = 'oddělujte čárkou';
        $strings['Category'] = 'Kategorie';
        $strings['CategoryReservation'] = 'Rezervace';
        $strings['CategoryGroup'] = 'Skupina';
        $strings['SortOrder'] = 'Pořadí';
        $strings['Title'] = 'Nadpis';
        $strings['AdditionalAttributes'] = 'Další atributy';
        $strings['True'] = 'Ano';
        $strings['False'] = 'Ne';
        $strings['ForgotPasswordEmailSent'] = 'Na zadaný e-mail byly odeslány instrukce pro obnovení hesla.';
        $strings['ActivationEmailSent'] = 'Brzy obdržíte aktivační e-mail.';
        $strings['AccountActivationError'] = 'Omlouváme se, Váš učet ještě není schválen.';
        $strings['Attachments'] = 'Přílohy';
        $strings['AttachFile'] = 'Příloha';
        $strings['Maximum'] = 'limit';
        $strings['NoScheduleAdministratorLabel'] = 'Žádný kalendář administrátora';
        $strings['ScheduleAdministrator'] = 'Kalendář administrátora';
        $strings['Total'] = 'Celkem';
        $strings['QuantityReserved'] = 'Rezervované množství';
        $strings['AllAccessories'] = 'Všechno příslušenství';
        $strings['GetReport'] = 'Zobrazit hlášení';
        $strings['NoResultsFound'] = 'Nenalezena žádná shoda';
        $strings['SaveThisReport'] = 'Uložit hlášení';
        $strings['ReportSaved'] = 'Hlášení uloženo!';
        $strings['EmailReport'] = 'Zaslat hlášení e-mailem';
        $strings['ReportSent'] = 'Hlášení zasláno na e-mail!';
        $strings['RunReport'] = 'Spustit hlášení';
        $strings['NoSavedReports'] = 'Nemáte uložené žádné hlášení.';
        $strings['CurrentWeek'] = 'Tento týden';
        $strings['CurrentMonth'] = 'Tento měsíc';
        $strings['AllTime'] = 'Vždy';
        $strings['FilterBy'] = 'Filtrovat podle';
        $strings['Select'] = 'Výběr';
        $strings['List'] = 'Seznam';
        $strings['TotalTime'] = 'Celkový čas';
        $strings['Count'] = 'Počet';
        $strings['Usage'] = 'Užití';
        $strings['AggregateBy'] = 'Agregoval';
        $strings['Range'] = 'Rozsah';
        $strings['Choose'] = 'Výběr';
        $strings['All'] = 'Všechno';
        $strings['ViewAsChart'] = 'Zobrazit jako tabulku';
        $strings['ReservedResources'] = 'Rezervované prostředky';
        $strings['ReservedAccessories'] = 'Rezervované příslušenství';
        $strings['ResourceUsageTimeBooked'] = 'Použití stroje - výběr času';
        $strings['ResourceUsageReservationCount'] = 'Použití stroje - počet rezervací';
        $strings['Top20UsersTimeBooked'] = 'Nejlepších 20 uživatelů - výběr času';
        $strings['Top20UsersReservationCount'] = 'Nejlepších 20 uživatelů - počet rezervací';
        $strings['ConfigurationUpdated'] = 'Konfigurační soubor byl upraven';
        $strings['ConfigurationUiNotEnabled'] = 'Stránka není přístupná, protože $conf[\'settings\'][\'pages\'][\'enable.configuration\'] je chybový nebo chybí.';
        $strings['ConfigurationFileNotWritable'] = 'Konfigurační soubor musí být zapisovatelný.';
        $strings['ConfigurationUpdateHelp'] = 'Pro vysvětlení nastavení níže navštivte <a target=_blank href=%s>dokumentaci</a>.';
        $strings['GeneralConfigSettings'] = 'Nastavení';
        $strings['UseSameLayoutForAllDays'] = 'Použít stejný kalendář pro všechny dny';
        $strings['LayoutVariesByDay'] = 'Kalendář pro každý den zvlášť';
        $strings['ManageReminders'] = 'E-mailová upomínka';
        $strings['ReminderUser'] = 'Číslo uživatele';
        $strings['ReminderMessage'] = 'Zpráva';
        $strings['ReminderAddress'] = 'Adresa';
        $strings['ReminderSendtime'] = 'Čas odeslání';
        $strings['ReminderRefNumber'] = 'Referenční číslo rezervace';
        $strings['ReminderSendtimeDate'] = 'Datum upomínky';
        $strings['ReminderSendtimeTime'] = 'Čas (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'dopoledne / odpoledne';
        $strings['AddReminder'] = 'Přidat upomínku';
        $strings['DeleteReminderWarning'] = 'Opravdu to chcete?';
        $strings['NoReminders'] = 'Nemáte naplánovanou žádnou upomínku.';
        $strings['Reminders'] = 'Upomínky';
        $strings['SendReminder'] = 'Odeslat upomínku';
        $strings['minutes'] = 'minut';
        $strings['hours'] = 'hodin';
        $strings['days'] = 'den';
        $strings['ReminderBeforeStart'] = 'Před začátkem';
        $strings['ReminderBeforeEnd'] = 'Před koncem';
        $strings['Logo'] = 'Logotyp';
        $strings['CssFile'] = 'CSS Soubor';
        $strings['ThemeUploadSuccess'] = 'Změny byly uloženy. Obnovte stránku.';
        $strings['MakeDefaultSchedule'] = 'Použít jako výchozí kalendář';
        $strings['DefaultScheduleSet'] = 'Toto je Váš výchozí kalendář';
        $strings['FlipSchedule'] = 'Změnit vzhled kalendáře';
        $strings['Next'] = 'Další';
        $strings['Success'] = 'Provedeno';
        $strings['Participant'] = 'Účastník';
        $strings['ResourceFilter'] = 'Filtr zdrojů';
        $strings['ResourceGroups'] = 'Skupiny zdrojů';
        $strings['AddNewGroup'] = 'Přidat novou skupinu';
        $strings['Quit'] = 'Ukončit';
        $strings['AddGroup'] = 'Přidat skupinu';
        $strings['StandardScheduleDisplay'] = 'Použít klasické zobrazení';
        $strings['TallScheduleDisplay'] = 'Použít zobrazení na výšku';
        $strings['WideScheduleDisplay'] = 'Použít zobrazení na šířku';
        $strings['CondensedWeekScheduleDisplay'] = 'Použít zkrácené týdenní zobrazení';
        $strings['ResourceGroupHelp1'] = 'Použijte metodu Drag and drop pro přemístění skupiny zdrojů.';
        $strings['ResourceGroupHelp2'] = 'Pro další akce Klikněte pravým tlačítkem na název zdroje skupiny.';
        $strings['ResourceGroupHelp3'] = 'Pro přidání droje do skupiny Použijte metodu Drag and drop.';
        $strings['ResourceGroupWarning'] = 'Pokud používáte skupiny zdroje, každý zdroj musí být přiřazen nejméně jedné skupině. Nepřiřazené zdroje nebude možné rezervovat.';
        $strings['ResourceType'] = 'Typ zdroje';
        $strings['AppliesTo'] = 'Použít na';
        $strings['UniquePerInstance'] = 'Unikátní ke každému';
        $strings['AddResourceType'] = 'Přidat typ zdroje';
        $strings['NoResourceTypeLabel'] = '(nezvolen typ zdroje)';
        $strings['ClearFilter'] = 'Vyčistit filtr';
        $strings['MinimumCapacity'] = 'Minimální kapacita';
        $strings['Color'] = 'Barva';
        $strings['Available'] = 'Volné';
        $strings['Unavailable'] = 'Obsazené';
        $strings['Hidden'] = 'Skryté';
        $strings['ResourceStatus'] = 'Stav zroje';
        $strings['CurrentStatus'] = 'Současný stav';
        $strings['AllReservationResources'] = 'Všechny zdroje';
        $strings['File'] = 'Soubor';
        $strings['BulkResourceUpdate'] = 'Prázdné zdroje';
        $strings['Unchanged'] = 'Nezměněné';
        $strings['Common'] = 'příbuzné';
        $strings['AdvancedFilter'] = 'Rozšířený filtr';
        $strings['AllParticipants'] = 'Všichni účastníci';
        $strings['NoResources'] = 'Nebyl přidán zdroj.';
        $strings['ReservationApprovalAdminSubject'] = 'Upozornění: Rezervace vyžaduje Vaše schválení';
        $strings['ResourceAvailability'] = 'Dostupnost zdrojů';
        $strings['UnavailableAllDay'] = 'Nedostupné celý den';
        $strings['AvailableUntil'] = 'Dostupných časů k rezervaci';
        $strings['AvailableBeginningAt'] = 'Dostupný začátek';
        $strings['ChangeLanguage'] = 'Změnit jazyk';
        $strings['Reserve'] = 'Rezervovat';
        $strings['Purge'] = 'Vyčistit';
        $strings['UsersWillBeDeleted'] = 'uživatelů bude smazáno';
        $strings['BlackoutsWillBeDeleted'] = 'termínů zavření bude smazáno';
        $strings['ReservationsWillBePurged'] = 'rezervací bude vyčištěno';
        $strings['ReservationsWillBeDeleted'] = 'rezervací bude smazáno';
        $strings['PermanentlyDeleteUsers'] = 'Trvale vymazat uživatele naposledy přihlášené před';
        $strings['DeleteBlackoutsBefore'] = 'Vymazat termíny zavření provozu před';
        $strings['DeletedReservations'] = 'Smazané rezervace';
        $strings['DeleteReservationsBefore'] = 'Vymazat rezervace před';
        $strings['ReservationSeriesEndingPreference'] = 'Když končí série opakovaných rezervací';
        $strings['DateRange'] = 'Období';
        $strings['ThisWeek'] = 'Tento týden';
        $strings['FindATime'] = 'Vyhledat čas';
        $strings['AnyResource'] = 'Libovolný prostředek';
        $strings['Hours'] = 'hodin';
        $strings['Minutes'] = 'minut';
        $strings['SpecificTime'] = 'Vybraný čas';
        $strings['MoreOptions'] = 'Více nastavení';
        $strings['DisplayPage'] = 'Zobrazit stránku';
        $strings['SendAsEmail'] = 'Informovat e-mailem';
        $strings['Users'] = 'Uživatelé';
        $strings['UsersInGroups'] = 'Uživatelé ve skupinách';
        $strings['UsersWithAccessToResources'] = 'Uživatelé s přístupem k prostředkům';
        $strings['AutomaticallyAddToGroup'] = 'Automaticky přidávat nové uživatele do této skupiny';
        $strings['InviteUsers'] = 'Pozvat uživatele';
        $strings['InviteUsersLabel'] = 'Vyplňte email uživatelů, které chcete pozvat';
        $strings['PrintQRCode'] = 'Vytisknout QR kód';
        $strings['AllResourceTypes'] = 'Všechny typt prostředku';
        $strings['AllResourceStatuses'] = 'Všechny stavy prostředku';
        $strings['AllYear'] = 'Celý rok';
        $strings['ConcurrentYes'] = 'Prostředek může být rezervován více uživateli zároveň';
        $strings['ConcurrentNo'] = 'Prostředek nemůže být rezervován více uživateli zároveň';
        $strings['DefaultStyle'] = 'Výchozí vzhled';
        $strings['SwitchToACustomLayout'] = 'Přepnout na vlastní zobrazení';
        $strings['SwitchToAStandardLayout'] = 'Přepnout na standardní zobrazení';
        $strings['ThisScheduleUsesACustomLayout'] = 'Tento plán používá vlastní zobrazení';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Tento plán používá standardní zobrazení';
        $strings['Autofill'] = 'Vyplnit automaticky';
        $strings['IncludingCompletedReservations'] = 'Zahrnout ukončené rezervace';
        $strings['NotCountingCompletedReservations'] = 'Nezahrnovat ukončené rezervace';
        $strings['AllDay'] = 'Celý den';
        $strings['Everyday'] = 'Každý den';
        $strings['QuotaEnforcement'] = 'Vynutit %s %s';
        $strings['AvailableAllYear'] = 'Celý rok';
        $strings['Availability'] = 'Dostupnost';
        $strings['AvailableBetween'] = 'Dostupno v období';
        $strings['BlackoutAroundConflicts'] = 'Uzavřít provoz před a po konfliktních rezervacích';
        $strings['ImportICS'] = 'Importovat z ICS';
        $strings['ImportQuartzy'] = 'Importovat z Quartzy';
        $strings['OnlyIcs'] = 'Only *.ics files can be uploaded.';
        $strings['IcsLocationsAsResources'] = 'Umístění budou importovány jako prostředky.';
        $strings['IcsMissingOrganizer'] = 'Vlastníck událostí u kterých není nastaven organizátor bude nastaven na současného uživatele.';
        $strings['IcsWarning'] = 'Pravidla pro rezervace nebudou vynucena - může dojít ke konfliktům a duplikacím.';
        $strings['IncludeDeleted'] = 'Zahrnout smazané rezervace';
        $strings['Utilization'] = 'Využití';
        $strings['CheckingIn'] = 'Odbavení';
        $strings['CheckingOut'] = 'Odhlášení';
        $strings['CheckIn'] = 'Odbavení';
        $strings['CheckOut'] = 'Odhlášení';
        $strings['ReleasedIn'] = 'Uvolněno v';
        $strings['CheckedInSuccess'] = 'Byli jste odbaveni';
        $strings['CheckedOutSuccess'] = 'Byli jste odhlášeni';
        $strings['CheckInFailed'] = 'Odbavení se nezdařilo';
        $strings['CheckOutFailed'] = 'Odhlášení se nezdařilo';
        $strings['CheckInTime'] = 'Čas odbavení';
        $strings['CheckOutTime'] = 'Čas odhlášení';
        $strings['OriginalEndDate'] = 'Plánovaný konec';
        $strings['MissedCheckin'] = 'Zmeškaná odbavení';
        $strings['MissedCheckout'] = 'Zmeškaná odhlášení';
        $strings['RequiresApproval'] = 'Vyžaduje schválení';
        $strings['FullAccess'] = 'Plný přístup';
        $strings['ViewOnly'] = 'Pouze čtení';
        $strings['Schedules'] = 'Plánování';
        $strings['ResourceColor'] = 'Barva prostředku';
        $strings['PublicId'] = 'Veřejné Id';
        $strings['Public'] = 'Veřejné';
        $strings['AutoReleaseNotification'] = 'Automaticky uvolnit pokud odbavení neproběhne do %s minut';
        $strings['RequiresCheckInNotification'] = 'Vyžaduje odbavení a odhlášení';
        $strings['NoCheckInRequiredNotification'] = 'Nevyžaduje odbavení a odhlášení';
        $strings['More'] = 'Více';
        $strings['ViewAvailability'] = 'Zobrazit dostupnost';
        $strings['ReservationDetails'] = 'Detaily rezervace';
        $strings['NotifyUser'] = 'Upozornit uživatele';
        $strings['Reject'] = 'Zamítnout';
        $strings['Columns'] = 'Sloupce';
        $strings['NewVersion'] = 'Nová verze!';
        $strings['StartTime'] = 'Čas začátku';
        $strings['EndTime'] = 'Čas konce';
        $strings['SelectUser'] = 'Vybrat uživatele';
        $strings['TermsOfService'] = 'Podmínky užití';
        $strings['EnterTermsManually'] = 'Zadat podmínky ručne';
        $strings['LinkToTerms'] = 'Odkaz na podmínky';
        $strings['UploadTerms'] = 'Nahrát podmínky';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Vyžadovat souhlas s podmínkami';
        $strings['UponReservation'] = 'Při rezervaci';
        $strings['UponRegistration'] = 'Při registraci';
        $strings['ViewTerms'] = 'Zobrazit podmínky užití';
        $strings['IAccept'] = 'Souhlasím';
        $strings['TheTermsOfService'] = 'Podmínky užití';
        $strings['ReservationColors'] = 'Barvy rezervací';
        $strings['Attribute'] = 'Atribut';
        $strings['AddRule'] = 'Přidat pravidlo';
        $strings['RequiredValue'] = 'Vyžadovaná hodnota';
        $strings['ReservationCustomRuleAdd'] = 'Použit barvu, pokud je atribut rezervace nastaven na následující hodnotu';
        $strings['AddReservationColorRule'] = 'Přidat pravidlo barvy rezervací';
        $strings['CollectedFor'] = 'Zobrazováno pro';
        $strings['LimitAttributeScope'] = 'Zobrazovat v konkrétních případech';
        $strings['AdminOnly'] = 'Pouze pro Admina';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Instalace LibreBooking (pouze pro MySQL)';
        $strings['IncorrectInstallPassword'] = 'Nesprávné heslo.';
        $strings['SetInstallPassword'] = 'Před pokračováním musíte zadat instalační heslo.';
        $strings['InstallPasswordInstructions'] = 'V souboru %s nastavte řádek %s na náhodné a obtížně uhodnutelné heslo, poté se vraťte na tuto stránku.<br/>Můžete použít %s';
        $strings['NoUpgradeNeeded'] = 'Žádná aktualizace není potřeba. Opětovné spuštění instalačního procesu smaže veškerá stávající data a znovu nainstaluje LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Zadejte prosím instalační heslo.';
        $strings['InstallPasswordLocation'] = 'To je zadáno v souboru %s v řádku %s.';
        $strings['VerifyInstallSettings'] = 'Před pokračovnáním ověřte následující výchozí nastavení. Nebo jej změňte v %s.';
        $strings['DatabaseName'] = 'Název databáze';
        $strings['DatabaseUser'] = 'Databázový uživatel';
        $strings['DatabaseHost'] = 'Databázový server';
        $strings['DatabaseCredentials'] = 'Musíte zadat pověření MySQL uživatele který má práva k vytváření databází. Pokud taková pověření neznáte, kontaktujte svého databázového administrátora. V mnoha případech bude fungovat root.';
        $strings['MySQLUser'] = 'MySQL uživatel';
        $strings['InstallOptionsWarning'] = 'Následující nastavení pravděpodobně nebudou fungovat ve sdíleném prostředí. Pokud instalujete do sdíleného prostředí, použijte k dokončení následujících kroků nástroje pro správu MySQL.';
        $strings['CreateDatabase'] = 'Vytvořit databázi';
        $strings['CreateDatabaseUser'] = 'Vytvořit databázového uživateler';
        $strings['PopulateExampleData'] = 'Importovat vzorová data. Vytvoří administrátorský účet: admin/password a uživatelský účet: user/password';
        $strings['DataWipeWarning'] = 'Varování: Tato akce smaže veškerá data';
        $strings['RunInstallation'] = 'Spustit instalaci';
        $strings['UpgradeNotice'] = 'Aktualizujete z verze <b>%s</b> na verzi <b>%s</b>';
        $strings['RunUpgrade'] = 'Spustit aktualizaci';
        $strings['Executing'] = 'Provádí se';
        $strings['StatementFailed'] = 'Dotaz selhal. Podrobnosti:';
        $strings['SQLStatement'] = 'SQL dotaz:';
        $strings['ErrorCode'] = 'Kód chyby:';
        $strings['ErrorText'] = 'Text chyby:';
        $strings['InstallationSuccess'] = 'Instalace byla úspěšně dokončena!';
        $strings['RegisterAdminUser'] = 'Zaregistrujte si administrátorského uživatele, pokud jste neimportovali vzorová data. Ověřte že $conf[\'settings\'][\'allow.self.registration\'] = \'true\' v souboru %s.';
        $strings['LoginWithSampleAccounts'] = 'Pokud jste importovali vzorová data, přihlašte se s administrátorským pověřením admin/password nebo uživatelským pověřením user/password.';
        $strings['InstalledVersion'] = 'Nainstalovaná verze LibreBooking je %s';
        $strings['InstallUpgradeConfig'] = 'Doporučujeme aktualizovat váš konfigurační soubor';
        $strings['InstallationFailure'] = 'Vyskytly se problémy během instalace. Prosím opravte je a zkuste instalaci spustit znovu.';
        $strings['ConfigureApplication'] = 'Nastavit LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Váš konfigurační soubor je aktuální!';
        $strings['ConfigUpdateFailure'] = 'Nepodařilo se automaticky aktualizovat konfigurační soubor. Prosím přepište obsah souboru config.php následujícím:';
        $strings['ScriptUrlWarning'] = 'Vaše nastavení <em>script.url</em> pravděpodobně není spávné. V současnosti je nastaveno na <strong>%s</strong>, ale nejspíše by mělo být <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Chybně zadané uživatelské jméno nebo heslo.';
        $strings['ReservationFailed'] = 'Vaše rezervace nemůže být vytvořena.';
        $strings['MinNoticeError'] = 'Tato rezervace musí obsahovat popis. Nejstarší datum, které může být rezervováno je %s.';
        $strings['MaxNoticeError'] = 'Tato rezervace nemůže být naplánována tak daleko. Nejzazší datum, které může být rezervováno je %s.';
        $strings['MinDurationError'] = 'Tato rezervace musí být delší než %s.';
        $strings['MaxDurationError'] = 'Tato rezervace nemůže trvat déle než %s.';
        $strings['ConflictingAccessoryDates'] = 'Překročili jste omezený počet kusů';
        $strings['NoResourcePermission'] = 'Nemáte oprávnění pro vstup k jednomu nebo více požadovaným prostředkům';
        $strings['ConflictingReservationDates'] = 'Zde je výpis rezervací, které jsou v konfliktu s Vámi vytvořenou:';
        $strings['StartDateBeforeEndDateRule'] = 'Začátek rezervace musí začínat dříve než její konec.';
        $strings['StartIsInPast'] = 'Začátek rezervace nemůže být vytvořen v minulosti';
        $strings['EmailDisabled'] = 'Administrátor zakázal posílání e-mailových upozornění.';
        $strings['ValidLayoutRequired'] = 'Časový úsek musí být vytvořen na celý den - 24hodin';
        $strings['CustomAttributeErrors'] = 'Chybné s dalšími hodnotami:';
        $strings['CustomAttributeRequired'] = '%s je povinné pole';
        $strings['CustomAttributeInvalid'] = 'Hodnota pro %s je chybná';
        $strings['AttachmentLoadingError'] = 'Omlouváme se, došlo k chybě při načítání požadovaného souboru.';
        $strings['InvalidAttachmentExtension'] = 'Můžete nahrát pouze soubory těchto typů: %s';
        $strings['InvalidStartSlot'] = 'Začátek a čas rezervace není je chybně zadán.';
        $strings['InvalidEndSlot'] = 'Konec a čas rezervace je chybně zadán.';
        $strings['MaxParticipantsError'] = '%s můžete mít pouze %s účastníků.';
        $strings['ReservationCriticalError'] = 'Závažná chyba při rezervaci. Kontaktuje nás prosím.';
        $strings['InvalidStartReminderTime'] = 'Začátek upomínky je chybně zadán.';
        $strings['InvalidEndReminderTime'] = 'Konec upomínky je chybně zadán.';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s není povolené rezervovat na několik dní.';
        $strings['InvalidReservationData'] = 'Nastal problém při požadavku na rezervaci.';
        $strings['PasswordError'] = 'Heslo musí obsahovat nejméně %s písměn a nejméně %s čísel.';
        $strings['PasswordErrorRequirements'] = 'Heslo musí obsahovat kombinaci nejméně %s malých a velkých písmen a %s čísel.';
        $strings['NoReservationAccess'] = 'Nemáte povolení měnit tuto rezervaci.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Vytvořit rezervaci';
        $strings['EditReservation'] = 'Upravování rezervace';
        $strings['LogIn'] = 'Přihlásit';
        $strings['ManageReservations'] = 'Rezervace';
        $strings['AwaitingActivation'] = 'Čeká na aktivaci';
        $strings['PendingApproval'] = 'Probíhá schválení';
        $strings['ManageSchedules'] = 'Plánování';
        $strings['ManageResources'] = 'Prostředky';
        $strings['ManageAccessories'] = 'Příslušenství';
        $strings['ManageUsers'] = 'Uživatelé';
        $strings['ManageGroups'] = 'Skupiny';
        $strings['ManageQuotas'] = 'Kvóty';
        $strings['ManageBlackouts'] = 'Termíny zavření provozu';
        $strings['MyDashboard'] = 'Hlavní strana';
        $strings['ServerSettings'] = 'Informace o serveru';
        $strings['Dashboard'] = 'Hlavní přehled';
        $strings['Help'] = 'Nápověda';
        $strings['Administration'] = 'Administrace';
        $strings['About'] = 'O nás';
        $strings['Bookings'] = 'Vytvořit rezervaci';
        $strings['Schedule'] = 'Plánování a vytvoření rezervace';
        $strings['Reservations'] = 'Rezervace';
        $strings['Account'] = 'Účet';
        $strings['EditProfile'] = 'Upravit vlastní profil';
        $strings['FindAnOpening'] = 'Najít otevření';
        $strings['OpenInvitations'] = 'Zobrazit pozvání';
        $strings['MyCalendar'] = 'Vlastní kalendář';
        $strings['ResourceCalendar'] = 'Kalendáře prostředků';
        $strings['Reservation'] = 'Nová rezervace';
        $strings['Install'] = 'Instalace';
        $strings['ChangePassword'] = 'Změnit heslo';
        $strings['MyAccount'] = 'Můj účet';
        $strings['Profile'] = 'Nastavení profilu';
        $strings['ApplicationManagement'] = 'Správa systému';
        $strings['ForgotPassword'] = 'Zapomenuté heslo';
        $strings['NotificationPreferences'] = 'Nastavení oznámení e-mailem';
        $strings['ManageAnnouncements'] = 'Úplné uzavření provozu';
        $strings['Responsibilities'] = 'Odpovědnost';
        $strings['GroupReservations'] = 'Skupinové rezervace';
        $strings['ResourceReservations'] = 'Rezervace prostředků';
        $strings['Customization'] = 'Rozšířitelnost';
        $strings['Attributes'] = 'Atributy';
        $strings['AccountActivation'] = 'Aktivace účtů';
        $strings['ScheduleReservations'] = 'Rezervace kalendářů';
        $strings['Reports'] = 'Hlášení';
        $strings['GenerateReport'] = 'Vytvořit nové hlášení';
        $strings['MySavedReports'] = 'Má uložená hlášení';
        $strings['CommonReports'] = 'Společná hlášení';
        $strings['ViewDay'] = 'Zobrazit denní přehled';
        $strings['Group'] = 'Skupina';
        $strings['ManageConfiguration'] = 'Globální nastavení';
        $strings['LookAndFeel'] = 'Nastavení vzhledu';
        $strings['ManageResourceGroups'] = 'Skupiny zdroje';
        $strings['ManageResourceTypes'] = 'Typy zdroje';
        $strings['ManageResourceStatus'] = 'Stavy zdroje';
        $strings['ManageEmailTemplates'] = 'E-mailové šablony';
        $strings['DataCleanup'] = 'Čištění dat';
        $strings['SearchReservations'] = 'Vyhledat rezervace';
        $strings['RegisterANewAccount'] = 'Registrace nového účtu';
        // End Page Titles


        // Day representations
        $strings['DaySundaySingle'] = 'Ne';
        $strings['DayMondaySingle'] = 'Po';
        $strings['DayTuesdaySingle'] = 'Út';
        $strings['DayWednesdaySingle'] = 'St';
        $strings['DayThursdaySingle'] = 'Čt';
        $strings['DayFridaySingle'] = 'Pá';
        $strings['DaySaturdaySingle'] = 'So';

        $strings['DaySundayAbbr'] = 'Ne';
        $strings['DayMondayAbbr'] = 'Po';
        $strings['DayTuesdayAbbr'] = 'Út';
        $strings['DayWednesdayAbbr'] = 'St';
        $strings['DayThursdayAbbr'] = 'Čt';
        $strings['DayFridayAbbr'] = 'Pá';
        $strings['DaySaturdayAbbr'] = 'So';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Vaše rezervace byla potvrzena';
        $strings['ReservationCreatedSubject'] = 'Rezervace byla vytvořena';
        $strings['ReservationUpdatedSubject'] = 'Rezervace byla upravena';
        $strings['ReservationDeletedSubject'] = 'Rezervace byla zrušena';
        $strings['ReservationCreatedAdminSubject'] = 'Upozornění: rezervace vytvořena';
        $strings['ReservationUpdatedAdminSubject'] = 'Upozornění: rezervace upravena';
        $strings['ReservationDeleteAdminSubject'] = 'Upozornění: rezervace zrušena';
        $strings['ParticipantAddedSubject'] = 'Upozornění: rezervace účastníků';
        $strings['ParticipantDeletedSubject'] = 'Rezervace zrušena';
        $strings['InviteeAddedSubject'] = 'Pozvánka do rezervace';
        $strings['ResetPasswordRequest'] = 'Požadavek na resetování hesla';
        $strings['ActivateYourAccount'] = 'Prosíme, aktivujte svůj účet';
        $strings['ReportSubject'] = 'Vaše požadovaná hlášení (%s)';
        $strings['ReservationStartingSoonSubject'] = ' Vaše rezervace %s zanedlouho začne';
        $strings['ReservationEndingSoonSubject'] = 'Vaše rezervace %s zanedlouho končí';
        $strings['UserAdded'] = 'Byl přidán nový uživatel';
        $strings['UserDeleted'] = 'Uživatelský účet %s byl smazán uživatelem %s';
        $strings['GuestAccountCreatedSubject'] = 'Podrobnosti vašeho účtu %s';
        $strings['AccountCreatedSubject'] = 'Podrobnosti vašeho účtu %s';
        $strings['InviteUserSubject'] = '%s vás pozval k přidání do %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Rezervace %s byla schválena';
        $strings['ReservationCreatedSubjectWithResource'] = 'Rezervace %s byla vytvořena';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Rezervace %s byla aktualizována';
        $strings['ReservationDeletedSubjectWithResource'] = 'Rezervace %s byla odstraněna';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Upozornění: Rezervace %s byla schválena';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Upozornění: Rezervace %s byla aktualizována';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Upozornění: Rezervace %s byla odstraněna';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Upozornění: Rezervace %s vyžaduje vaše schválení';
        $strings['ParticipantAddedSubjectWithResource'] = '%s vás přidal k rezervaci %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s vás odebral z rezervace %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s vás pozval k rezervaci %s';
        $strings['MissedCheckinEmailSubject'] = 'Zmeškané odbavení %s';
        $strings['ReservationShareSubject'] = '%s sdílel reservaci %s';
        $strings['ReservationSeriesEndingSubject'] = 'Série rezervací %s končí %s';
        $strings['ReservationParticipantAccept'] = '%s přijal vaše pozvání k rezervaci %s na %s';
        $strings['ReservationParticipantDecline'] = '%s odmítl vaše pozvání k rezervaci %s na %s';
        $strings['ReservationParticipantJoin'] = '%s se přidal k vašemu pozvání k rezervaci %s na %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nemáte žádné minulé rezervace';
        $strings['PastReservations'] = 'Minulé rezervace';
        $strings['AllNoPastReservations'] = 'Nejsou žádné minulé rezervace v posledních %s dnech';
        $strings['AllPastReservations'] = 'Všechny minulé rezervace';
        $strings['Yesterday'] = 'Včera';
        $strings['EarlierThisWeek'] = 'Dříve v tomto týdnu';
        $strings['PreviousWeek'] = 'Předchozí týden';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Vaše skupina nemá žádné nadcházející rezervace';
        $strings['GroupUpcomingReservations'] = 'Nadcházející rezervace mé skupiny(y)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Při přihlašování přes Facebook došlo k chybě. Zkuste to prosím znovu.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nemáte žádné rezervace čekající na schválení';
        $strings['PendingApprovalReservations'] = 'Rezervace čekající na schválení';
        $strings['LaterThisMonth'] = 'Později tento měsíc';
        $strings['LaterThisYear'] = 'Později letos';
        $strings['Remaining'] = 'Zbývající';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Nejsou žádné chybějící rezervace pro odhlášení';
        $strings['MissingCheckOutReservations'] = 'Chybějící rezervace pro odhlášení';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Nemůžete vidět podrobnosti rezervace, protože nemáte oprávnění k žádným zdrojům v této rezervaci';
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
        $days['full'] = ['Neděle', 'Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota'];
        // The three letter abbreviation
        $days['abbr'] = ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];
        // The two letter abbreviation
        $days['two'] = ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];
        // The one letter abbreviation
        $days['letter'] = ['Ne', 'Po', 'Út', 'St', 'Čt', 'Pá', 'So'];

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
        $months['full'] = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];
        // The three letter month name
        $months['abbr'] = ['Leden', 'Únor', 'Březen', 'Duben', 'Květen', 'Červen', 'Červenec', 'Srpen', 'Září', 'Říjen', 'Listopad', 'Prosinec'];

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
        return 'cz';
    }
}
