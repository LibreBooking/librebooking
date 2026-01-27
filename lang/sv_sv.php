<?php

require_once('Language.php');
require_once('en_gb.php');

class sv_sv extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */

    // Väldigt oklart om detta just _LoadDates används längre, får stå kvar

    protected function _LoadDates()
    {
        $dates = parent::_LoadDates();

        $dates['general_date'] = 'Y-m-d';
        $dates['general_datetime'] = 'Y-m-d H:i';
        $dates['short_datetime'] = 'Y-m-d H:i';
        $dates['schedule_daily'] = 'l j\/n Y';
        $dates['reservation_email'] = 'l j M Y \k\l\. H:i';
        $dates['res_popup'] = 'D j\/n -y H:i';
        $dates['res_popup_time'] = 'D j\/n -y H:i';
        $dates['short_reservation_date'] = 'D j\/n -y H:i';
        $dates['dashboard'] = 'D j\/n -y H:i';
        $dates['period_time'] = 'H:i';
        $dates['mobile_reservation_date'] = 'm/d H:i';
        $dates['general_date_js'] = 'yy-mm-dd';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'yy-mm-dd h:mm';
        $dates['calendar_time'] = 'HH:mm';
        $dates['calendar_dates'] = 'd/M';
        $dates['full_week'] = '\V\e\c\k\a W Y';        // tillagt ar
        $dates['full_month'] = 'F Y';        // tillagt ar


        $this->Dates = $dates;
        return $this->Dates;
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Förnamn';
        $strings['LastName'] = 'Efternamn';
        $strings['Timezone'] = 'Tidzon';
        $strings['Edit'] = 'Redigera';     // ändrad AR
        $strings['Change'] = 'Byt';
        $strings['Rename'] = 'Ändra namn';
        $strings['Remove'] = 'Avlägsna';
        $strings['Delete'] = 'Ta bort';
        $strings['Update'] = 'Uppdatera';
        $strings['Cancel'] = 'Avbryt';
        $strings['Add'] = 'Lägg till';
        $strings['Name'] = 'Namn';
        $strings['Yes'] = 'Ja';
        $strings['No'] = 'Nej';
        $strings['FirstNameRequired'] = 'Förnamn krävs.';
        $strings['LastNameRequired'] = 'Efternamn krävs.';
        $strings['PwMustMatch'] = 'Bekräftat lösenord måste matcha lösenordet.';
        $strings['PwComplexity'] = 'Lösenordet måste bestå av minst 6 tecken med en kombination av bokstäver, siffror och symboler.';
        $strings['ValidEmailRequired'] = 'En giltig E-postadress krävs.';
        $strings['UniqueEmailRequired'] = 'Den här e-postadressen är redan registrerad.';
        $strings['UniqueUsernameRequired'] = 'Användarnamnet finns redan.';
        $strings['UserNameRequired'] = 'Användarnamn krävs.';
        $strings['CaptchaMustMatch'] = 'Ange bokstäverna från säkerhetesbilden exakt som de visas.';
        $strings['Today'] = 'Idag';
        $strings['Week'] = 'Vecka';
        $strings['day'] = 'Dag';
        $strings['Day'] = 'Dag';
        $strings['Month'] = 'Månad';
        $strings['BackToCalendar'] = 'Tillbaka till kalendern';
        $strings['BeginDate'] = 'Börjar';
        $strings['EndDate'] = 'Slutar';
        $strings['Username'] = 'Användarnamn';
        $strings['Password'] = 'Lösenord';
        $strings['PasswordConfirmation'] = 'Bekräfta lösenordet';
        $strings['DefaultPage'] = 'Startsida';
        $strings['MyCalendar'] = 'Min kalender';
        $strings['ScheduleCalendar'] = 'Schedule Calendar';
        $strings['Registration'] = 'Registrering';
        $strings['NoAnnouncements'] = 'Det finns inga meddelanden';
        $strings['Announcements'] = 'Meddelanden';
        $strings['NoUpcomingReservations'] = 'Det finns inga bokningar';
        $strings['UpcomingReservations'] = 'Kommande bokningar';

        $strings['ResourceAvailability'] = 'Tillgängliga lokaler';        // tillagt AR
        $strings['UnavailableAllDay'] = 'Bokat hela dagen';        // tillagt AR
        $strings['AvailableUntil'] = 'Ledigt tills';        // tillagt AR
        $strings['AvailableBeginningAt'] = 'Ledigt från';        // tillagt AR
        $strings['Available'] = 'Ledigt';    // tillagt AR
        $strings['Unavailable'] = 'Bokat';    // tillagt AR
        $strings['Reserve'] = 'Boka';    // tillagt AR
        $strings['ViewAvailability'] = 'Visa tillgängliga lokaler';    // tillagt AR
        $strings['Dates'] = 'Datum';
        $strings['ReservationRequiresApproval'] = 'Bokningen är ännu <b>inte bekräftad</b>. Vi återkommer när den är godkänd och klar.';


        $strings['ShowHide'] = 'Visa/Dölj';
        $strings['Error'] = 'Fel';
        $strings['ReturnToPreviousPage'] = 'Tillbaka till föregående sida';
        $strings['UnknownError'] = 'Okänt fel';
        $strings['InsufficientPermissionsError'] = 'Du har inte behörighet att komma åt denna lokal'; // tjänst --> lokal
        $strings['MissingReservationResourceError'] = 'En lokal har inte valts'; // tjänst --> lokal
        $strings['MissingReservationScheduleError'] = 'Ett schema har inte valts';
        $strings['DoesNotRepeat'] = 'Upprepas inte';
        $strings['Daily'] = 'Dagligen';
        $strings['Weekly'] = 'Veckovis';
        $strings['Monthly'] = 'Månadsvis';
        $strings['Yearly'] = 'Årsvis';
        $strings['RepeatPrompt'] = 'Upprepa';
        $strings['hours'] = 'timmar';
        $strings['days'] = 'dag/ar';
        $strings['weeks'] = 'vecka/or';
        $strings['months'] = 'månad/er';
        $strings['years'] = 'år';
        $strings['day'] = 'dag';
        $strings['week'] = 'vecka';
        $strings['month'] = 'månad';
        $strings['year'] = 'år';
        $strings['repeatDayOfMonth'] = 'dag i månad';
        $strings['repeatDayOfWeek'] = 'dag i vecka';
        $strings['RepeatUntilPrompt'] = 'Till';
        $strings['RepeatEveryPrompt'] = 'Repetera var(t)';    // helt tokigt i tidigare övers.
        $strings['RepeatDaysPrompt'] = 'På';
        $strings['CreateReservationHeading'] = 'Ny bokning';
        $strings['EditReservationHeading'] = 'Redigera bokning %s';
        $strings['ViewReservationHeading'] = 'Visa bokning %s';
        $strings['ReservationErrors'] = 'Redigera bokning'; // ändrad AR
        $strings['Create'] = 'Skapa';
        $strings['ThisInstance'] = 'Bara denna bokning';    // bokningar hellre än instans
        $strings['AllInstances'] = 'Alla bokningar';    // bokningar hellre än instans
        $strings['FutureInstances'] = 'Framtida bokningar'; // bokningar hellre än instans
        $strings['Print'] = 'Skriv ut';
        $strings['ShowHideNavigation'] = 'Visa/dölj navigering'; // ort, bokningar hellre än instans
        $strings['ReferenceNumber'] = 'Bokningsnummer';
        $strings['Tomorrow'] = 'Imorgon';
        $strings['LaterThisWeek'] = 'Senare denna vecka';
        $strings['NextWeek'] = 'Nästa vecka';
        $strings['SignOut'] = 'Logga ut';
        $strings['LayoutDescription'] = 'Börja på %s, visar %s dagar i sänder';
        $strings['AllResources'] = 'Alla lokaler';    // tjänst --> lokal
        $strings['AllAccessories'] = 'Alla tillbehör';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Total tid';
        $strings['Count'] = 'Räkna';
        $strings['Group'] = 'Grupp';
        $strings['CurrentMonth'] = 'Denna månad';
        $strings['CurrentWeek'] = 'Denna vecka';
        $strings['Total'] = 'Totalt';
        $strings['AllTime'] = 'All tid';
        $strings['GetReport'] = 'Hämta rapport';
        $strings['Select'] = 'Välj rapport:';
        $strings['AggregateBy'] = 'Välj efter:';
        $strings['houers'] = 'Timmar:';
        $strings['Usage'] = 'Välj typ av:';
        $strings['Range'] = 'Välj intervall:';
        $strings['FilterBy'] = 'Filtrera efter:';
        $strings['TakeOffline'] = 'Gå offline';
        $strings['BringOnline'] = 'Gå online';
        $strings['AddImage'] = 'Lägg till bild';
        $strings['NoImage'] = 'Bild inte tilldelad';
        $strings['Move'] = 'Flytta';
        $strings['AppearsOn'] = 'Visas på %s';
        $strings['Location'] = 'Plats';
        $strings['NoLocationLabel'] = '(Ingen plats utsatt)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(Ingen kontaktinformation)';
        $strings['Description'] = 'Beskrivning';
        $strings['NoDescriptionLabel'] = '(ingen beskrivning)';
        $strings['Notes'] = 'Anteckningar';
        $strings['NoNotesLabel'] = '(inga anteckningar)';
        $strings['NoTitleLabel'] = '(ingen titel)';
        $strings['UsageConfiguration'] = 'Användarkonfigurering';
        $strings['ChangeConfiguration'] = 'Redigera konfigurering'; // ändrad AR
        $strings['ResourceMinLength'] = 'Bokning måste vara minst %s';
        $strings['ResourceMinLengthNone'] = 'Det finns ingen minsta reservationstid';
        $strings['ResourceMaxLength'] = 'Bokning kan inte vara längre än %s';
        $strings['ResourceMaxLengthNone'] = 'Det finns ingen längsta reservationstid';
        $strings['ResourceRequiresApproval'] = 'Bokning måste bli godkänd';
        $strings['ResourceRequiresApprovalNone'] = 'Bokning behöver inte godkännas';
        $strings['ResourcePermissionAutoGranted'] = 'Tillstånd beviljas automatiskt';
        $strings['ResourcePermissionNotAutoGranted'] = 'Tillstånd beviljas inte automatiskt';
        $strings['ResourceMinNotice'] = 'Bokning måste göras minst %s före bokad tid';
        $strings['ResourceMinNoticeNone'] = 'Bokning kan göras fram till bokad tid';
        $strings['ResourceMaxNotice'] = 'Bokningar får inte sluta mer än %s från nuvarande tid';
        $strings['ResourceMaxNoticeNone'] = 'Bokningar kan sluta vid vilken tidpunkt som helst i framtiden';
        $strings['ResourceAllowMultiDay'] = 'Bokning kan göras över flera dygn';    // språkvård
        $strings['ResourceNotAllowMultiDay'] = 'Bokningar kan inte göras över flera dygn';    // språkvård
        $strings['ResourceCapacity'] = 'Denna lokal har en kapacitet av %s personer';    // tjänst --> lokal
        $strings['ResourceCapacityNone'] = 'Denna lokal är obegränsad'; // tjänst --> lokal
        $strings['AddNewResource'] = 'Lägg till ny lokal'; // tjänst --> lokal
        $strings['AddNewUser'] = 'Lägg till ny användare';
        $strings['AddUser'] = 'Lägg till användare';
        $strings['Schedule'] = 'Schema';
        $strings['AddResource'] = 'Lägg till lokal'; // tjänst --> lokal
        $strings['Capacity'] = 'Kapasitet';
        $strings['Access'] = 'Tillträde';
        $strings['Duration'] = 'Längd';
        $strings['Active'] = 'Aktiv';
        $strings['Inactive'] = 'Inaktiv';
        $strings['ResetPassword'] = 'Återställ lösenord';
        $strings['LastLogin'] = 'Senaste inloggning';
        $strings['Search'] = 'Sök';
        $strings['ResourcePermissions'] = 'Behörighet till lokal';    // tjänst --> lokal
        $strings['Reservations'] = 'Bokning';
        $strings['Groups'] = 'Grupp';
        $strings['ResetPassword'] = 'Återställ lösenord';
        $strings['AllUsers'] = 'Alla användare';
        $strings['AllGroups'] = 'Alla Grupper';
        $strings['AllSchedules'] = 'Alla scheman';
        $strings['UsernameOrEmail'] = 'Användarnamn eller E-post';
        $strings['Members'] = 'Medlem';
        $strings['QuickSlotCreation'] = 'Skapa bokningsintervall var %s minut mellan %s och %s';
        $strings['ApplyUpdatesTo'] = 'Tillämpa uppdateringar på';
        $strings['CancelParticipation'] = 'Ångra deltagandet';
        $strings['Attending'] = 'Närvarande';
        $strings['QuotaConfiguration'] = 'På %s får %s användare i %s är begränsade till %s %s per %s';
        $strings['reservations'] = 'Bokningar';
        $strings['ChangeCalendar'] = 'Byt Kalender';
        $strings['AddQuota'] = 'Lägg till ett filter';
        $strings['FindUser'] = 'sök användare';
        $strings['Created'] = 'Skapad';
        $strings['LastModified'] = 'Senast ändrad';
        $strings['GroupName'] = 'Gruppnamn';
        $strings['GroupMembers'] = 'Gruppmedlemmar';
        $strings['GroupRoles'] = 'Grupproll';
        $strings['GroupAdmin'] = 'Gruppadministratör';
        $strings['Actions'] = 'Åtgärd';
        $strings['CurrentPassword'] = 'Nuvarande Lösenord';
        $strings['NewPassword'] = 'Nytt Lösenord';
        $strings['InvalidPassword'] = 'Lösenord är felaktigt';
        $strings['PasswordChangedSuccessfully'] = 'Ditt lösenord har ändrats';
        $strings['SignedInAs'] = 'Inloggad som';
        $strings['NotSignedIn'] = 'Du är inte inloggad';
        $strings['ReservationTitle'] = 'Bokningens titel';    // ändrat
        $strings['ReservationDescription'] = 'Ansvarig person';    // ändrat
        $strings['ResourceList'] = 'Bokning av rum';
        $strings['Accessories'] = 'Tillbehör';
        $strings['Add'] = 'Lägg till';
        $strings['ParticipantList'] = 'Deltagare';
        $strings['InvitationList'] = 'Inbjudna';
        $strings['AccessoryName'] = 'Namn på tillbehör';
        $strings['QuantityAvailable'] = 'Tillgängligt antal';
        $strings['Resources'] = 'Lokaler'; // tjänst --> lokal
        $strings['Participants'] = 'Deltagare';
        $strings['User'] = 'Användare';
        $strings['Resource'] = 'Lokal'; // tjänst --> lokal
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Godkänna';
        $strings['Page'] = 'Sida';
        $strings['Rows'] = 'Rad';
        $strings['Unlimited'] = 'Obegränsat';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email Adress';
        $strings['Phone'] = 'Telefon';    // ändrat AR
        $strings['Organization']                        = 'Organisation';
        $strings['Position']                            = 'Position';   // ändrat AR
        $strings['Language'] = 'Språk';
        $strings['Permissions'] = 'Behörighet';
        $strings['Reset'] = 'Återställ';
        $strings['FindGroup'] = 'Sök Grupp';
        $strings['Manage'] = 'Hantera';
        $strings['None'] = 'Ingen';
        $strings['AddToOutlook'] = 'Lägg till i kalendern';
        $strings['Done'] = 'Klart';
        $strings['RememberMe'] = 'Komihåg mig';
        $strings['FirstTimeUser?'] = 'Första gången du är här?';    // ändrat AR
        $strings['CreateAnAccount'] = 'Skapa ett konto';
        $strings['ViewSchedule'] = 'Visa kalender';
        $strings['ForgotMyPassword'] = 'Glömt lösenordet';
        $strings['YouWillBeEmailedANewPassword'] = 'Vi skickar ett nytt slumpmässigt genererat lösenord till din e-post';    // ändrat AR
        $strings['Close'] = 'Stäng';
        $strings['ExportToCSV'] = 'Exportera till CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'fungerar...';
        $strings['Login'] = 'Login';
        $strings['AdditionalInformation']               = 'Ytterligare information'; // Ändrat AR
        $strings['AllFieldsAreRequired'] = 'Alla fält är obligatoriska';
        $strings['Optional']                            = 'Valfritt';
        $strings['YourProfileWasUpdated'] = 'Din profil är uppdaterad';
        $strings['YourSettingsWereUpdated'] = 'Dina inställningar har uppdaterats';
        $strings['Register'] = 'Registrera';
        $strings['TermsOfService'] = 'bokningsvillkor'; // Tillagt AR
        $strings['ViewTerms'] = 'Visa bokningsvillkor'; // Tillagt AR
        $strings['IAccept'] = 'Jag accepterar';    // Tillagt AR
        $strings['TheTermsOfService'] = 'bokningsvillkoren'; // tillagt AR
        $strings['SecurityCode'] = 'Säkerhetskod';
        $strings['ReservationCreatedPreference'] = 'När jag skapar en bokning eller en bokning skapas för min räkning';
        $strings['ReservationUpdatedPreference'] = 'När jag uppdaterar en bokning eller en bokning uppdateras för min räkning';
        $strings['ReservationDeletedPreference'] = 'När jag tar bort en bokning eller en bokning tas bort för min räkning';
        $strings['ReservationApprovalPreference'] = 'I avvaktan på att min bokning blir godkänd';
        $strings['PreferenceSendEmail'] = 'Skicka ett email till mig';
        $strings['PreferenceNoEmail'] = 'Meddela mig inte';
        $strings['ReservationCreated'] = 'Din bokning har skapats!';
        $strings['ReservationUpdated'] = 'Din bokning är uppdaterad!';
        $strings['ReservationRemoved'] = 'Din bokning har tagits bort';
        $strings['YourReferenceNumber'] = 'Ert bokningsnummer är %s';    // referens --> bokning
        $strings['UpdatingReservation'] = 'Uppdaterar bokning';
        $strings['ChangeUser'] = 'Byt användare';
        $strings['MoreResources'] = 'Boka flera rum';
        $strings['ReservationLength'] = 'Bokningens längd';
        $strings['ParticipantList'] = 'Deltagarlista';
        $strings['AddParticipants'] = 'Lägg till deltagare';
        $strings['InviteOthers'] = 'Bjud in andra';
        $strings['AddResources'] = 'Lägg till tillbehör';
        $strings['AddAccessories'] = 'Lägg till tillbehör';
        $strings['Accessory'] = 'Tillbehör';
        $strings['QuantityRequested'] = 'Antal ';
        $strings['CreatingReservation'] = 'Skapar bokning';
        $strings['UpdatingReservation'] = 'Uppdaterar bokning';
        $strings['DeleteWarning'] = 'Denna åtgärd är permanent och går inte att ångra!';
        $strings['DeleteAccessoryWarning'] = 'Tar du bort detta tillbehör kommer det att raderas det från alla bokningar.';
        $strings['AddAccessory'] = 'Lägg till tillbehör';
        $strings['AddBlackout'] = 'Lägg till Ej Tillgänglig Tid';
        $strings['AllResourcesOn'] = 'Alla tillgångar är på';
        $strings['Reason'] = 'Orsak';
        $strings['BlackoutShowMe'] = 'Visa Ej Tillgänglig Tid i konflikt';
        $strings['BlackoutDeleteConflicts'] = 'Ta bort ej tillgänglig tid som är i konflikt';
        $strings['Filter'] = 'Filtrera';    // Ändrad AR
        $strings['Between'] = 'Mellan';
        $strings['CreatedBy'] = 'Skapad av';
        $strings['BlackoutCreated'] = 'Ej Tillgänglig Tid Skapad!';
        $strings['BlackoutNotCreated'] = 'Ej Tillgänglig Tid kunde inte skapas!';
        $strings['BlackoutConflicts'] = 'Det finns Ej Tillgänglig Tid i konflikt';
        $strings['ReservationConflicts'] = 'Det finns motstridiga reservationstider';
        $strings['UsersInGroup'] = 'Användare i denna grupp';
        $strings['Browse'] = 'Bläddra';
        $strings['DeleteGroupWarning'] = 'Tar du bort denna grupp kommer alla lokaltillhörigheter att raderas . Användare i denna grupp kan förlora tillgången till lokalerna.'; // tjänst --> lokal
        $strings['WhatRolesApplyToThisGroup'] = 'Vilka roller gäller i denna grupp?';
        $strings['WhoCanManageThisGroup'] = 'Vem hanterar denna grupp?';
        $strings['AddGroup'] = 'Lägg till grupp';
        $strings['AllQuotas'] = 'Alla filter';
        $strings['QuotaReminder'] = 'Komihåg: tillämpade filter bygger på schemat \ s tidszon.';
        $strings['AllReservations'] = 'Alla bokningar';
        $strings['PendingReservations'] = 'Ej godkända bokningar';
        $strings['Approving'] = 'Godkänner';
        $strings['MoveToSchedule'] = 'Flytta till kalendern';
        $strings['DeleteResourceWarning'] = 'Tar du bort denna lokal kommer all tillhörande data tas bort, inklusive'; // tjänst --> lokal
        $strings['DeleteResourceWarningReservations'] = 'alla tidigare, nuvarande och framtida bokningar associerade med den';
        $strings['DeleteResourceWarningPermissions'] = 'alla behörighetstilldelningar';
        $strings['DeleteResourceWarningReassign'] = 'Vänligen tilldela allt som du inte vill ska tas bort innan du går vidare';
        $strings['ScheduleLayout'] = 'Layout (alla tider %s)';
        $strings['ScheduleReservations'] = 'Bokningar (per schema)'; // ändrat AR
        $strings['ReservableTimeSlots'] = 'Bokningsbara tidsintervaller';
        $strings['BlockedTimeSlots'] = 'Blockerade tidsintervaller';
        $strings['ThisIsTheDefaultSchedule'] = 'Denna kalendern är förvald';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Den förvalda kalendern kan inte raderas';
        $strings['MakeDefault'] = 'Ange som förvald';
        $strings['BringDown'] = 'Flytta ner';
        $strings['ChangeLayout'] = 'Ändra Layout';
        $strings['AddSchedule'] = 'Lägg till bokningskalender';
        $strings['StartsOn'] = 'Börjar på';
        $strings['NumberOfDaysVisible'] = 'Antal synliga dagar';
        $strings['UseSameLayoutAs'] = 'Använd samma inställningar som';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Valfritt fält';
        $strings['LayoutInstructions'] = 'Lägg till ett tidsintervall per rad.  Tidsintervall måste tilldelas alla 24 timmar på dygnet med början och slut 12.00.';
        $strings['AddUser'] = 'Lägg till användare';
        $strings['UserPermissionInfo'] = 'Faktisk åtkomst till lokalen kan variera beroende på användarens roll, grupptillhörighet eller externa behörighetsinställningar'; // tjänst --> lokal
        $strings['DeleteUserWarning'] = 'Om du raderar den här användaren avlägsnas denna användares samtliga bokningar.';
        $strings['AddAnnouncement'] = 'Lägg till meddelande';
        $strings['Announcement'] = 'Meddelande';
        $strings['Priority'] = 'Prioritet';
        $strings['Reservable'] = 'Bokningsbar';
        $strings['Unreservable'] = 'Ej bokningsbar';
        $strings['Reserved'] = 'Reserverad';
        $strings['MyReservation'] = 'Mina bokningar';
        $strings['Pending'] = 'Ej bekräftad';    // ändrat AR
        $strings['Past'] = 'Förflutna';        // ändrat AR
        $strings['Participant'] = 'Deltagare';    // ändrat AR
        $strings['Restricted'] = 'Begränsat';    // ändrat AR
        $strings['ViewAll'] = 'Visa allt';
        $strings['MoveResourcesAndReservations'] = 'Flytta lokaler och bokningar till'; // tjänst --> lokal
        $strings['TurnOffSubscription'] = 'Stäng av prenumeration på kalendern';
        $strings['TurnOnSubscription'] = 'Tillåt Prenumeration av denna kalender';
        $strings['SubscribeToCalendar'] = 'Prenumerera på denna kalender';
        $strings['SubscriptionsAreDisabled'] = 'Administratören har inaktiverat abonnemang av kalendern';
        $strings['NoResourceAdministratorLabel'] = '(Ingen administratör av lokal)'; // tjänst --> lokal
        $strings['WhoCanManageThisResource'] = 'Vem kan hantera denna lokal?'; // tjänst --> lokal
        $strings['ResourceAdministrator'] = 'Administratör av lokal'; // tjänst --> lokal
        $strings['Private'] = 'Privat';
        $strings['Accept'] = 'Acceptera';
        $strings['Decline'] = 'Avböj';
        $strings['ShowFullWeek'] = 'Visa hel vecka';
        $strings['CustomAttributes'] = 'Eget attribut';
        $strings['AddAttribute'] = 'Lägg till ett attribut';
        $strings['EditAttribute'] = 'Uppdatera attribut';
        $strings['DisplayLabel'] = 'Visa Etikett';
        $strings['Type'] = 'Typ';
        $strings['Required'] = 'Obligatorisk';
        $strings['ValidationExpression'] = 'Validation Expression';
        $strings['PossibleValues'] = 'Möjliga värden';
        $strings['SingleLineTextbox'] = 'Enradig Textbox';
        $strings['MultiLineTextbox'] = 'Flerradig Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Välj lista';
        $strings['CommaSeparated'] = 'komma separerad';
        $strings['Category'] = 'Kategori';
        $strings['CategoryReservation'] = 'Reservation';
        $strings['CategoryGroup'] = 'Grupp';
        $strings['SortOrder'] = 'Sorteringsordning';
        $strings['Title'] = 'Rubrik';
        $strings['AdditionalAttributes']                = 'Flera attribut';
        $strings['True'] = 'Sant';
        $strings['False'] = 'Falskt';
        $strings['NoResultsFound'] = 'Hittade inga rapporter';
        $strings['NoSavedReports'] = 'Det finns inga sparade rapporter';
        $strings['GenerateReports'] = 'Skapa rapport';
        $strings['ForgotPasswordEmailSent'] = 'Ett e-postmeddelande har skickats till din e-postadress med instruktioner om hur du återställer ditt lösenord'; // ändrat AR
        $strings['ActivationEmailSent'] = 'Ett aktiveringsmail har skickats till dig.';
        $strings['AccountActivationError'] = 'Ett problem uppstod vid aktiveringen av ert konto, kontot kunde inte aktiveras. Vänligen försök igen.';
        $strings['Attachments'] = 'Bilaga';
        $strings['AttachFile'] = 'Bifoga fil';
        $strings['Maximum'] = 'Max';
        // End Strings

        // Reports
        $strings['Working'] = 'Arbetar...';
        $strings['Created'] = 'Skapad';
        $strings['ViewAsChart'] = 'Visa som stapeldiagram';
        $strings['SaveThisReport'] = 'Spara denna rapport';
        $strings['NoSavedReports'] = 'Inga sparade rapporter';
        $strings['RunReport'] = 'Kör Rapport';
        $strings['EmailReport'] = 'Skicka rapport med E-post';
        $strings['Edit'] = 'Ändra';
        $strings['ReservedResources'] = 'Bokade lokaler'; // tjänst --> lokal
        $strings['ReservedAccessories'] = 'Bokade tillbehör';
        $strings['ResourceUsageTimeBooked'] = 'Bokade lokaler - Bokad tid'; // tjänst --> lokal
        $strings['ResourceUsageReservationCount'] = 'Utnyttjade lokaler - Antal bokningar'; // tjänst --> lokal
        $strings['Top20UsersTimeBooked'] = 'Topp 20 användare - Totalt bokad tid'; // ort
        $strings['Top20UsersReservationCount'] = 'Topp 20 användare - Totalt antal bokningar'; // ort, hela detta block
        $strings['AddToGoogleCalendar'] = 'Lägg till i Google Calendar';
        $strings['hours'] = 'timmar';
        $strings['minutes'] = 'minuter';
        $strings['More'] = 'Mer';
        // End Strings

        // Errors
        $strings['LoginError'] = 'Användarnamn eller lösenord är felaktigt';
        $strings['ReservationFailed'] = 'Din bokning kunde inte göras';
        $strings['MinNoticeError'] = 'Du kan inte boka med så kort varsel. Den första dagen som kan bokas direkt är %s.';    //
        $strings['MaxNoticeError'] = 'Denna bokning kan inte göras så långt i framtiden. Det tidigaste datum som kan bokas är %s.';    //
        $strings['MinNoticeErrorUpdate'] = 'Du kan inte ändra en bokning med så kort varsel.';    //
        $strings['MinNoticeErrorDelete'] = 'Du kan inte ta bort en bokning med så kort varsel.';
        $strings['MinDurationError'] = 'Denna bokning måste vara minst %s.';
        $strings['MaxDurationError'] = 'Denna bokning kan inte vara längre än %s.';
        $strings['ConflictingAccessoryDates'] = 'Det finns inte tillräckligt av följande tillbehör:';
        $strings['NoResourcePermission'] = 'Du har ingen behörighet att komma åt begärd(a) lokal(er)'; // // tjänst --> lokal
        $strings['ConflictingReservationDates'] = 'Det finns motstridiga reservationer på följande datum:';
        $strings['StartDateBeforeEndDateRule'] = 'Startdatum och tid måste vara före slutdatum och tid';
        $strings['StartIsInPast'] = 'Startdatum och tid kan inte vara i det förflutna';
        $strings['EmailDisabled'] = 'Administratören har inaktiverat e-postmeddelanden';
        $strings['ValidLayoutRequired'] = 'Tidsintervall skall lämnas för alla 24 timmar på dagen med början och slut vid 12:00 .';
        $strings['CustomAttributeErrors'] = 'Ett problem har uppstått med attributet som har angetts:';
        $strings['CustomAttributeRequired'] = '%s är ett obligatoriskt fält';
        $strings['CustomAttributeInvalid'] = 'Värdet du angett får %s är ogiltigt';
        $strings['AttachmentLoadingError'] = 'Ett problem uppstod vid uppladdning av den begärda filen.';
        $strings['InvalidAttachmentExtension'] = 'Du kan endast ladda upp filer av typen: %s';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Bokning';
        $strings['EditReservation'] = 'Redigera bokning';    // ändrad AR
        $strings['LogIn'] = 'Inloggning';
        $strings['ManageReservations'] = 'Bokningar';
        $strings['AwaitingActivation'] = 'Avvaktar aktivering';
        $strings['PendingApproval'] = 'Avvaktar bekräftelse';
        $strings['ManageSchedules'] = 'Kalender';
        $strings['ManageResources'] = 'Lokaler';
        $strings['ManageAccessories'] = 'Tillbehör';
        $strings['ManageUsers'] = 'Användare';
        $strings['ManageGroups'] = 'Grupper';
        $strings['ManageQuotas'] = 'Filter';
        $strings['ManageBlackouts'] = 'Blockerade tider';
        $strings['MyDashboard'] = 'Min anslagstavla';
        $strings['ServerSettings'] = 'Serverinställningar';
        $strings['Dashboard'] = 'Anslagstavla';
        $strings['Help'] = 'Hjälp';
        $strings['Bookings'] = 'Bokningar';
        $strings['Schedule'] = 'Kalender';
        $strings['Reservations'] = 'Bokningar';    // ändrat AR
        $strings['Account'] = 'Konto';
        $strings['EditProfile']                         = 'Namn och organisation'; // Ändrat AR
        $strings['FindAnOpening'] = 'Hitta en öppning';
        $strings['OpenInvitations'] = 'Inbjudan';
        $strings['MyCalendar'] = 'Min kalender';
        $strings['ResourceCalendar'] = 'Lokalkalender'; // tjänst --> lokal
        $strings['FindATime'] = 'Hitta en tid';    // tillagt AR
        $strings['SearchReservations'] = 'Sök bokningar';
        $strings['Reservation'] = 'Bokning';
        $strings['Install'] = 'Installation';
        $strings['ChangePassword'] = 'Ändra lösenord';
        $strings['MyAccount'] = 'Mitt konto';
        $strings['Profile'] = 'Namn och organisation';        // Ändrat AR
        $strings['ApplicationManagement'] = 'Administration';
        $strings['ForgotPassword'] = 'Glömt lösenordet';
        $strings['NotificationPreferences'] = 'E-postinställningar';    // Ändrat AR
        $strings['ManageAnnouncements'] = 'Meddelande';
        $strings['Responsibilities'] = 'Ansvar';
        $strings['GroupReservations'] = 'Bokningar (per grupp)';    // Ändrat AR
        $strings['ResourceReservations'] = 'Bokningar (per lokal)'; // tjänst --> lokal, ändrat
        $strings['Customization'] = 'Anpassningar';
        $strings['Attributes'] = 'Attribut';
        $strings['AccountActivation'] = 'Aktivering av konto';
        $strings['Reports'] = 'Rapporter';
        $strings['GenerateReport'] = 'Skapa ny rapport';
        $strings['CommonReports'] = 'Vanligaste Rapporterna';
        $strings['MySavedReports'] = 'Mina sparade rapporter';
        $strings['About'] = 'Om';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'O';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Sön';
        $strings['DayMondayAbbr'] = 'Mån';
        $strings['DayTuesdayAbbr'] = 'Tis';
        $strings['DayWednesdayAbbr'] = 'Ons';
        $strings['DayThursdayAbbr'] = 'Tor';
        $strings['DayFridayAbbr'] = 'Fre';
        $strings['DaySaturdayAbbr'] = 'Lör';
        // End Day representations

        // Email Subjects *** KOLLA ALLA ***
        $strings['ReservationApprovedSubject'] = 'Din bokning har bekräftats';    // ändrat AR
        $strings['ReservationCreatedSubject'] = 'Ny bokning';    // ändrat AR
        $strings['ReservationUpdatedSubject'] = 'En bokning har uppdaterats';    // ändrat AR
        $strings['ReservationDeletedSubject'] = 'En bokning har tagits bort';    // ändrat AR
        $strings['ReservationCreatedAdminSubject'] = 'Underrättelse: Ny bokning';    // ändrat AR
        $strings['ReservationUpdatedAdminSubject'] = 'Underrättelse: En bokning har uppdaterats';
        $strings['ReservationDeleteAdminSubject'] = 'Underrättelse: En bokning har uppdaterats';
        $strings['ReservationApprovalAdminSubject'] = 'Underrättelse: En bokning måste godkännas';
        $strings['ParticipantAddedSubject'] = 'Underrättelse om bokningsdeltagande';
        $strings['ParticipantDeletedSubject'] = 'Bokning borttagen';
        $strings['InviteeAddedSubject'] = 'Bokningsinbjudan';
        $strings['ResetPassword'] = 'Återställningsbegäran av lösenord';
        $strings['ActivateYourAccount'] = 'Var vänlig och aktivera ert konto';
        $strings['ReservationApprovedSubjectWithResource'] = 'En bokning har godkänts för %s'; // Ändrat AR hela vägen ner!
        $strings['ReservationCreatedSubjectWithResource'] = 'Ny bokning av %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'En bokning av %s har uppdaterats';
        $strings['ReservationDeletedSubjectWithResource'] = 'En bokning av %s har tagits bort';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Underrättelse: Ny bokning av %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Underrättelse: En bokning av %s har uppdaterats';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Underrättelse: En bokning av %s har tagits bort';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Underrättelse: En bokning av %s måste godkännas';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Du har inga tidigare bokningar';
        $strings['PastReservations'] = 'Tidigare bokningar';
        $strings['AllNoPastReservations'] = 'Det finns inga tidigare bokningar de senaste %s dagarna';
        $strings['AllPastReservations'] = 'Alla tidigare bokningar';
        $strings['Yesterday'] = 'Igår';
        $strings['EarlierThisWeek'] = 'Tidigare den här veckan';
        $strings['PreviousWeek'] = 'Förra veckan';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Din grupp har inga kommande bokningar';
        $strings['GroupUpcomingReservations'] = 'Mina grupps kommande bokningar';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Ett fel inträffade vid inloggning med Facebook. Försök igen.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Du har inga bokningar som väntar på godkännande';
        $strings['PendingApprovalReservations'] = 'Bokningar väntar på godkännande';
        $strings['LaterThisMonth'] = 'Senare denna månad';
        $strings['LaterThisYear'] = 'Senare i år';
        $strings['Remaining'] = 'Återstående';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Det finns inga saknade utcheckningsreservat';
        $strings['MissingCheckOutReservations'] = 'Saknade utcheckningsreservat';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Kan inte se detaljer om bokningen eftersom du inte har behörighet för någon av resurserna i denna bokning';
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
        $days['full'] = ['Söndag', 'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lördag'];
        // The three letter abbreviation
        $days['abbr'] = ['Sön', 'Mån', 'Tis', 'Ons', 'Tor', 'Fre', 'Lör'];
        // The two letter abbreviation
        $days['two'] = ['Sö', 'Må', 'Ti', 'On', 'To', 'Fr', 'Lö'];
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
        $months['full'] = ['Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'Maj', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'];

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
        return 'sv';
    }
}
