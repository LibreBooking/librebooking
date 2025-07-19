<?php

require_once('Language.php');
require_once('en_gb.php');

class eu_es extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Izena';
        $strings['LastName'] = 'Abizena';
        $strings['Timezone'] = 'Ordu zona';
        $strings['Edit'] = 'Editatu';
        $strings['Change'] = 'Aldatu';
        $strings['Rename'] = 'Berrizendatu';
        $strings['Remove'] = 'Ezabatu';
        $strings['Delete'] = 'Ezabatu';
        $strings['Update'] = 'Eguneratu';
        $strings['Cancel'] = 'Utzi';
        $strings['Add'] = 'Gehitu';
        $strings['Name'] = 'Izena';
        $strings['Yes'] = 'Bai';
        $strings['No'] = 'Ez';
        $strings['FirstNameRequired'] = 'Izena beharrezkoa da.';
        $strings['LastNameRequired'] = 'Abizena beharrezkoa da.';
        $strings['ValidEmailRequired'] = 'Posta helbide baliogarria beharrezkoa da.';
        $strings['UniqueEmailRequired'] = 'Posta helbide hau erregistratuta dago dagoeneko.';
        $strings['PwMustMatch'] = 'Pasahitzak bat egin behar du.';
        $strings['UniqueUsernameRequired'] = 'Erabiltzaile izen hori erregistratuta dago.';
        $strings['UserNameRequired'] = 'Erabiltzaile izena beharrezkoa da.';
        $strings['CaptchaMustMatch'] = 'Mesedez, sartu irudian agertzen diren karaktereak.';
        $strings['Today'] = 'Gaur';
        $strings['Week'] = 'Astea';
        $strings['Month'] = 'Hilabetea';
        $strings['BackToCalendar'] = 'Atzera egutegira';
        $strings['BeginDate'] = 'Hasiera';
        $strings['EndDate'] = 'Amaiera';
        $strings['Username'] = 'Erabiltzaile izena';
        $strings['Password'] = 'Pasahitza';
        $strings['PasswordConfirmation'] = 'Errepikatu pasahitza';
        $strings['DefaultPage'] = 'Lehenetsitako hasiera orria';
        $strings['MyCalendar'] = 'Nire Egutegia';
        $strings['ScheduleCalendar'] = 'Erreserben egutegia';
        $strings['Registration'] = 'Erregistroa';
        $strings['NoAnnouncements'] = 'Jakinarazpenik ez';
        $strings['Announcements'] = 'Jakinarazpenak';
        $strings['NoUpcomingReservations'] = 'Ez duzu erreserbarik hemendik gutxira';
        $strings['UpcomingReservations'] = 'Datozen Erreserbak';
        $strings['AllNoUpcomingReservations'] = 'Ez duzu erreserbarik datozen %s egunetan';
        $strings['AllUpcomingReservations'] = 'Datozen erreserba guztiak';
        $strings['ShowHide'] = 'Erakutsi/Ezkutatu';
        $strings['Error'] = 'Errorea';
        $strings['ReturnToPreviousPage'] = 'Aurreko orrira itzuli';
        $strings['UnknownError'] = 'Errore ezezaguna';
        $strings['InsufficientPermissionsError'] = 'Ez duzu baliabide honetarako baimenik';
        $strings['MissingReservationResourceError'] = 'Ez da baliabiderik hautatu';
        $strings['MissingReservationScheduleError'] = 'Ez da koadranterik hautatu';
        $strings['DoesNotRepeat'] = 'Ez da errepikatzen';
        $strings['Daily'] = 'Egunero';
        $strings['Weekly'] = 'Astero';
        $strings['Monthly'] = 'Hilero';
        $strings['Yearly'] = 'Urtero';
        $strings['RepeatPrompt'] = 'Errepikatu';
        $strings['hours'] = 'ordu';
        $strings['days'] = 'egun';
        $strings['weeks'] = 'aste';
        $strings['months'] = 'hilabete';
        $strings['years'] = 'urte';
        $strings['day'] = 'egun';
        $strings['week'] = 'aste';
        $strings['month'] = 'hilabete';
        $strings['year'] = 'urte';
        $strings['repeatDayOfMonth'] = 'hileko eguna';
        $strings['repeatDayOfWeek'] = 'asteko eguna';
        $strings['RepeatUntilPrompt'] = 'Noiz arte';
        $strings['RepeatEveryPrompt'] = 'Noizero';
        $strings['RepeatDaysPrompt'] = 'Zenbat egunetan';
        $strings['CreateReservationHeading'] = 'Erreserba berria egin';
        $strings['EditReservationHeading'] = '%s erreserba editatzen';
        $strings['ViewReservationHeading'] = '%s erreserba ikusten';
        $strings['ReservationErrors'] = 'Erreserba aldatu';
        $strings['Create'] = 'Sortu';
        $strings['ThisInstance'] = 'Eskabide hau';
        $strings['AllInstances'] = 'Eskabide guztiak';
        $strings['FutureInstances'] = 'Datozen eskabideak';
        $strings['Print'] = 'Inprimatu';
        $strings['ShowHideNavigation'] = 'Nabigazia Erakutsi/Ezkutatu';
        $strings['ReferenceNumber'] = 'Erreferantzia zenbakia';
        $strings['Tomorrow'] = 'Bihar';
        $strings['LaterThisWeek'] = 'Beranduago aste honetan';
        $strings['NextWeek'] = 'Datorren astean';
        $strings['SignOut'] = 'Itxi';
        $strings['LayoutDescription'] = '%s n hasten da, %s erakusten direlarik';
        $strings['AllResources'] = 'Baliabide guztiak';
        $strings['TakeOffline'] = 'Desgaitu';
        $strings['BringOnline'] = 'Gaitu';
        $strings['AddImage'] = 'Irudia gehitu';
        $strings['NoImage'] = 'Irudirik ez';
        $strings['Move'] = 'Mugitu';
        $strings['AppearsOn'] = '%s-n agertzen da';
        $strings['Location'] = 'Kokapena';
        $strings['NoLocationLabel'] = '(ez da zehaztu kokapenik)';
        $strings['Contact'] = 'Kontaktua';
        $strings['NoContactLabel'] = '(kontakturako informaziorik ez)';
        $strings['Description'] = 'Deskripzioa';
        $strings['NoDescriptionLabel'] = '(deskripziorik ez)';
        $strings['Notes'] = 'Oharrak';
        $strings['NoNotesLabel'] = '(oharrik ez)';
        $strings['NoTitleLabel'] = '(izenbururik ez)';
        $strings['UsageConfiguration'] = 'Erabileraren ezarpena';
        $strings['ChangeConfiguration'] = 'Ezarpenak aldatu';
        $strings['ResourceMinLength'] = 'Erreserben iraupena gutxienez %s izan behar du';
        $strings['ResourceMinLengthNone'] = 'Ez dago gutxieneko iraupenik';
        $strings['ResourceMaxLength'] = 'Erreserbak ezin dute iraun %s baino gehiago';
        $strings['ResourceMaxLengthNone'] = 'No hay duración máxima de reserva';
        $strings['ResourceRequiresApproval'] = 'Erreserbek onespena behar dute';
        $strings['ResourceRequiresApprovalNone'] = 'Erreserbek ez dute onespenik behar';
        $strings['ResourcePermissionAutoGranted'] = 'Baimena automatikoki ematen da';
        $strings['ResourcePermissionNotAutoGranted'] = 'Baimena ez da automatikoki ematen';
        $strings['ResourceMinNotice'] = 'Erreserbak gutxienez hasiera ordura baino %s lehenago egin behar dira';
        $strings['ResourceMinNoticeNone'] = 'Erreserbak azken ordura arte egin ahal dira';
        $strings['ResourceMaxNotice'] = 'Erreserbak ezin du oraingo ordua baino %s gehiago iraun';
        $strings['ResourceMaxNoticeNone'] = 'Erreserbak etorkizuneko edozein momentutan amaitu ahal du';
        $strings['ResourceBufferTime'] = 'Erreserben artean %s egon behar du';
        $strings['ResourceBufferTimeNone'] = 'Ez dago denborarik erreserben artean';
        $strings['ResourceAllowMultiDay'] = 'Erreserbak egunetan luzatu ahal dira';
        $strings['ResourceNotAllowMultiDay'] = 'Erreserbak ezin dira egunetan luzatu';
        $strings['ResourceCapacity'] = 'Baliabide honek %s pertsonentzako edukiera dauka';
        $strings['ResourceCapacityNone'] = 'Baliabide honek edukiera mugagabea dauka';
        $strings['AddNewResource'] = 'Baliabide berria gehitu';
        $strings['AddNewUser'] = 'Erabiltzaile berria gehitu';
        $strings['AddUser'] = 'Erabiltzailea gehitu';
        $strings['Schedule'] = 'Koadrante';
        $strings['AddResource'] = 'Baliabidea gehitu';
        $strings['Capacity'] = 'Edukiera';
        $strings['Access'] = 'Sarbidea';
        $strings['Duration'] = 'Iraupena';
        $strings['Active'] = 'Aktiboa';
        $strings['Inactive'] = 'Inaktiboa';
        $strings['ResetPassword'] = 'Pasahitza berrezarri';
        $strings['LastLogin'] = 'Azken saio hasiera';
        $strings['Search'] = 'Bilatu';
        $strings['ResourcePermissions'] = 'Baliabidearen baimenak';
        $strings['Reservations'] = 'Erreserbak';
        $strings['Groups'] = 'Taldeak';
        $strings['Users'] = 'Erabiltzaileak';
        $strings['ResetPassword'] = 'Pasahitza berrezarri';
        $strings['AllUsers'] = 'Erabiltzaile guztiak';
        $strings['AllGroups'] = 'Talde guztiak';
        $strings['AllSchedules'] = 'Koadrante guztiak';
        $strings['UsernameOrEmail'] = 'Erabiltzaile izena edo posta helbidea';
        $strings['Members'] = 'Kideak';
        $strings['QuickSlotCreation'] = 'Denbora tarte bat sortu %s minutuera %s eta %s artean';
        $strings['ApplyUpdatesTo'] = 'Eguneraketak aplikatu honi:';
        $strings['CancelParticipation'] = 'Parte hartzea bertan behera utzi';
        $strings['Attending'] = 'Parte hartzea';
        $strings['QuotaConfiguration'] = 'En %s para %s usuarios en %s están limitados a %s %s por cada %s';
        $strings['QuotaEnforcement'] = '%s %s ezarrita';
        $strings['reservations'] = 'erreserbak';
        $strings['reservation'] = 'erreserba';
        $strings['ChangeCalendar'] = 'Egutegia aldatu';
        $strings['AddQuota'] = 'Kuota ezarri';
        $strings['FindUser'] = 'Erabiltzailea aurkitu';
        $strings['Created'] = 'Sortua';
        $strings['LastModified'] = 'Azken moldaketa';
        $strings['GroupName'] = 'Taldearen izena';
        $strings['GroupMembers'] = 'Taldeko kideak';
        $strings['GroupRoles'] = 'Taldeko rolak';
        $strings['GroupAdmin'] = 'Taldeko kudeatzailea';
        $strings['Actions'] = 'Ekintzak';
        $strings['CurrentPassword'] = 'Oraingo pasahitza';
        $strings['NewPassword'] = 'Pasahitz berria';
        $strings['InvalidPassword'] = 'Oraingo pasahitza ez da zuzena';
        $strings['PasswordChangedSuccessfully'] = 'Zure pasahitza zuzen aldatu da';
        $strings['SignedInAs'] = 'Saioa hasi du: ';
        $strings['NotSignedIn'] = 'Ez duzu saioa hasi';
        $strings['ReservationTitle'] = 'Erreserbaren izenburua';
        $strings['ReservationDescription'] = 'Erreserbaren deskripzioa';
        $strings['ResourceList'] = 'Erreserbatzeko baliabideak';
        $strings['Accessories'] = 'Osagarriak';
        $strings['ParticipantList'] = 'Partehartzaileak';
        $strings['InvitationList'] = 'Gonbidatuak';
        $strings['AccessoryName'] = 'Osagarriaren izena';
        $strings['QuantityAvailable'] = 'Kopuru erabilgarria';
        $strings['Resources'] = 'Baliabideak';
        $strings['Participants'] = 'Partehartzaileak';
        $strings['User'] = 'Erabiltzailea';
        $strings['Resource'] = 'Baliabidea';
        $strings['Status'] = 'Egoera';
        $strings['Approve'] = 'Onartua';
        $strings['Page'] = 'Orrialdea';
        $strings['Rows'] = 'Lerro';
        $strings['Unlimited'] = 'Mugagabea';
        $strings['Email'] = 'Posta';
        $strings['EmailAddress'] = 'Posta helbidea';
        $strings['Phone'] = 'Telefonoa';
        $strings['Organization'] = 'Erakundea';
        $strings['Position'] = 'Lanpostua';
        $strings['Language'] = 'Hizkuntza';
        $strings['Permissions'] = 'Baimenak';
        $strings['Reset'] = 'Berrezarri';
        $strings['FindGroup'] = 'Aurkitu taldea';
        $strings['Manage'] = 'Kudeatu';
        $strings['None'] = 'Bat ere ez';
        $strings['AddToOutlook'] = 'Outlookera gehitu';
        $strings['Done'] = 'Eginda';
        $strings['RememberMe'] = 'Gogoratu';
        $strings['FirstTimeUser?'] = 'Erabiltzaile berria al zara?';
        $strings['CreateAnAccount'] = 'Kontua sortu';
        $strings['ViewSchedule'] = 'Koadrantea ikusi';
        $strings['ForgotMyPassword'] = 'Nire pasahitza ahaztu dut';
        $strings['YouWillBeEmailedANewPassword'] = 'Ausaz sortutako pasahitz bat bidaliko zaizu.';
        $strings['Close'] = 'Itxi';
        $strings['ExportToCSV'] = 'CSVra esportatu';
        $strings['OK'] = 'ADOS';
        $strings['Working'] = 'Lanean...';
        $strings['Login'] = 'Saioa hasi';
        $strings['AdditionalInformation'] = 'Informazio gehigarria';
        $strings['AllFieldsAreRequired'] = 'Eremu guztiak beharrezkoak dira';
        $strings['Optional'] = 'aukerazkoa';
        $strings['YourProfileWasUpdated'] = 'Profila eguneratu da';
        $strings['YourSettingsWereUpdated'] = 'Ezarpenak moldatu dira';
        $strings['Register'] = 'Erregistratu';
        $strings['SecurityCode'] = 'Segurtasun gakoa';
        $strings['ReservationCreatedPreference'] = 'Erreserba bat egitean edo erreserba bat nire izenean egitean';
        $strings['ReservationUpdatedPreference'] = 'Erreserba bat eguneratzean edo erreserba bat nire izenean eguneratzean';
        $strings['ReservationDeletedPreference'] = 'Erreserba bat ezabatzean edo erreserba bat nire izenean ezabatzean';
        $strings['ReservationApprovalPreference'] = 'Nire erreserba onartzen denean';
        $strings['PreferenceSendEmail'] = 'Bidalidazu mezu bat';
        $strings['PreferenceNoEmail'] = 'Ez dut jakinarazpenik nahi';
        $strings['ReservationCreated'] = 'Zure erreserba zuzen sortu da!';
        $strings['ReservationUpdated'] = 'Zure erreserba zuzen eguneratu da!';
        $strings['ReservationRemoved'] = 'Zure erreserba ezabatu da';
        $strings['ReservationRequiresApproval'] = 'Erreserbatutako baliabideetako batek edo gehiagok onespena behar du erabili baino lehen. Erreserba hau zain geratuko da onartua izan arte.';
        $strings['YourReferenceNumber'] = 'Zure erreferentzia zenbakia %s da';
        $strings['UpdatingReservation'] = 'Erreserba eguneratzen';
        $strings['ChangeUser'] = 'Erabiltzailea aldatu';
        $strings['MoreResources'] = 'Baliabide gehiago';
        $strings['ReservationLength'] = 'Erreserbaren iraupena';
        $strings['ParticipantList'] = 'Parte hartzaileen zerrenda';
        $strings['AddParticipants'] = 'Parte hartzaileak gehitu';
        $strings['InviteOthers'] = 'Beste inor gonbidatu';
        $strings['AddResources'] = 'Baliabideak gehitu';
        $strings['AddAccessories'] = 'Osagarria gehitu';
        $strings['Accessory'] = 'Osagarria';
        $strings['QuantityRequested'] = 'Eskatutako kopurua';
        $strings['CreatingReservation'] = 'Erreserba sortzen';
        $strings['UpdatingReservation'] = 'Erreserba eguneratzen';
        $strings['DeleteWarning'] = 'Ekintza hau ezin da desegin!';
        $strings['DeleteAccessoryWarning'] = 'Osagarri hau ezabatuz gero erreserba guztietatik ezabatuko da.';
        $strings['AddAccessory'] = 'Osagarria gehitu';
        $strings['AddBlackout'] = 'Ez erabilgarritasun tartea gehitu';
        $strings['AllResourcesOn'] = 'Baliabide guztiak gaituta';
        $strings['Reason'] = 'Arrazoia';
        $strings['BlackoutShowMe'] = 'Talka egiten duten erreserbak erakutsi';
        $strings['BlackoutDeleteConflicts'] = 'Talka egiten duten erreserbak ezabatu';
        $strings['Filter'] = 'Iragazi';
        $strings['Between'] = 'Honen artean';
        $strings['CreatedBy'] = 'Sortzailea';
        $strings['BlackoutCreated'] = 'Ez erabilgarritasun tartea sortu da';
        $strings['BlackoutNotCreated'] = 'Ezin izan da ez erabilgarritasun tartea sortu';
        $strings['BlackoutUpdated'] = 'Ez erabilgarritasun tartea eguneratu da';
        $strings['BlackoutNotUpdated'] = 'Ezin izan da ez erabilgarritasun tartea eguneratu';
        $strings['BlackoutConflicts'] = 'Talka egiten duten ez erabilgarritasun tarteak daude';
        $strings['ReservationConflicts'] = 'Talka egiten duten erreserba denborak daude';
        $strings['UsersInGroup'] = 'Talde honetako erabiltzaileak';
        $strings['Browse'] = 'Nabigatu';
        $strings['DeleteGroupWarning'] = 'Talde hau ezabatuz gero honekin lotutako baliabideen baimenak ezabatuko dira. Talde honetako erabiltzaileak baliabideetarako sarbidea galdu ahal dute.';
        $strings['WhatRolesApplyToThisGroup'] = 'Ze rol aplikatuko zaizkio talde honi?';
        $strings['WhoCanManageThisGroup'] = 'Nork kudeatu ahal du talde hau?';
        $strings['WhoCanManageThisSchedule'] = 'Nork kudeatu ahal du koadrante hau?';
        $strings['AddGroup'] = 'Taldeari gehitu';
        $strings['AllQuotas'] = 'Kuota guztiak';
        $strings['QuotaReminder'] = 'Jakinarazpena: kuotak koadranteen ordu zonan oinarrituta ezartzen dira.';
        $strings['AllReservations'] = 'Erreserba guztiak';
        $strings['PendingReservations'] = 'Onartzeko zain dauden erreserbak';
        $strings['Approving'] = 'Onartzen';
        $strings['MoveToSchedule'] = 'Koadrantera mugitu';
        $strings['DeleteResourceWarning'] = 'Baliabide hau ezabatzean erlazionatutako datu guztiak ezabatuko dira, hau barne:';
        $strings['DeleteResourceWarningReservations'] = 'erlazionatutako erreserba guztiak, pasatutakoak, oraingoak eta etorkizunekoaktodos las reservas pasadas, actuales y futuras asociadas';
        $strings['DeleteResourceWarningPermissions'] = 'baimen esleipen guztiak';
        $strings['DeleteResourceWarningReassign'] = 'Mesedez, jarraitu baino lehen, esleitu berriro ezabatzea nahi ez duzuna';
        $strings['ScheduleLayout'] = 'Distribución horaria (todas las veces %s)';
        $strings['BlockedTimeSlots'] = 'Intervalos de tiempo bloqueados';
        $strings['ReservableTimeSlots'] = 'Erreserbagarriak diren denbora tarteak';
        $strings['ThisIsTheDefaultSchedule'] = 'Hau da koadrante lehenetsia';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Lehentsitako koadrantea ezin da ezabatu';
        $strings['MakeDefault'] = 'Lehenetsi';
        $strings['BringDown'] = 'Desgaitu';
        $strings['ChangeLayout'] = 'Ordu banaketa aldatu';
        $strings['AddSchedule'] = 'Koadrantea gehitu';
        $strings['StartsOn'] = 'Hasiera';
        $strings['NumberOfDaysVisible'] = 'Ikusgarri egongo den egun kopurua';
        $strings['UseSameLayoutAs'] = 'Beste honen ordu banaketa erabili';
        $strings['Format'] = 'Formatoa';
        $strings['OptionalLabel'] = 'Etiketa aukerazkoa';
        $strings['LayoutInstructions'] = 'Sartu denbora tarte bat lerro bakoitzean. Eguneko 24 orduetarako denbora tarteak eman behar dira 12:00 AM-n hasi eta bukatuta.';
        $strings['AddUser'] = 'Erabiltzailea gehitu';
        $strings['UserPermissionInfo'] = 'Baliabideetarako sarbidea ezberdina izan daiteke erabiltzailearen rolaren arabera, talde baimenak, edo bestelako ezarpenen araberakoa';
        $strings['DeleteUserWarning'] = 'Erabiltzaile hau ezabatzean berak egindako erreserba guztiak ezabatuko dira, pasatutakoak, oraingoak eta etorkizunekoak.';
        $strings['AddAnnouncement'] = 'Jakinarazpena gehitu';
        $strings['Announcement'] = 'Jakinarazpena';
        $strings['Priority'] = 'Lehentasuna';
        $strings['Reservable'] = 'Erreserbagarria';
        $strings['Unreservable'] = 'Ez erreserbagarria';
        $strings['Reserved'] = 'Erreserbatuta';
        $strings['MyReservation'] = 'Nire erreserba';
        $strings['Pending'] = 'Zain';
        $strings['Past'] = 'Pasatutakoa';
        $strings['Restricted'] = 'Mugatuta';
        $strings['ViewAll'] = 'Dena ikusi';
        $strings['MoveResourcesAndReservations'] = 'Mugitu baliabideak eta erreserbak hona:';
        $strings['TurnOffSubscription'] = 'Egutegiko harpidetzak desgaitu';
        $strings['TurnOnSubscription'] = 'Egutegiko harpidetzak gaitu';
        $strings['SubscribeToCalendar'] = 'Harpidetza egin egutegi honetan';
        $strings['SubscriptionsAreDisabled'] = 'Kudeatzaileak egutegi honetako harpidetzak desgaitu ditu';
        $strings['NoResourceAdministratorLabel'] = '(Ez dago baliabide kudeatzailerik)';
        $strings['WhoCanManageThisResource'] = 'Nork kudeatu ahal du baliabide hau?';
        $strings['ResourceAdministrator'] = 'Baliabide kudeatzailea';
        $strings['Private'] = 'Pribatua';
        $strings['Accept'] = 'Onartu';
        $strings['Decline'] = 'Ezetsi';
        $strings['ShowFullWeek'] = 'Aste osoa erakutsi';
        $strings['CustomAttributes'] = 'Ezaugarriak pertsonalizatu';
        $strings['AddAttribute'] = 'Ezaugarri bat gehitu';
        $strings['EditAttribute'] = 'Ezaugarri bat editatu';
        $strings['DisplayLabel'] = 'Etiketa ikusgai';
        $strings['Type'] = 'Mota';
        $strings['Required'] = 'Derrigorrezkoa';
        $strings['ValidationExpression'] = 'Balidazio espresioa';
        $strings['PossibleValues'] = 'Balizko balioak';
        $strings['SingleLineTextbox'] = 'Lerro bakarreko testu kutxa';
        $strings['MultiLineTextbox'] = 'Lerro anitzeko testu kutxa';
        $strings['Checkbox'] = 'Egiaztatze kutxa';
        $strings['SelectList'] = 'Hautapen zerrenda';
        $strings['CommaSeparated'] = 'komaz banatuta';
        $strings['Category'] = 'Kategoria';
        $strings['CategoryReservation'] = 'Erreserba';
        $strings['CategoryGroup'] = 'Taldea';
        $strings['SortOrder'] = 'Hurrenkera';
        $strings['Title'] = 'Izenburua';
        $strings['AdditionalAttributes'] = 'Ezaugarri gehigarriak';
        $strings['True'] = 'Egia';
        $strings['False'] = 'Faltsua';
        $strings['ForgotPasswordEmailSent'] = 'Mezu bat bidali da sartu duzun posta helbidera, bertan pasahitza berritzeko argibideak dituzu';
        $strings['ActivationEmailSent'] = 'Laster aktibazio mezu bat jasoko duzu.';
        $strings['AccountActivationError'] = 'Barkatu, ezin izan dugu kontua aktibatu.';
        $strings['Attachments'] = 'Eranskinak';
        $strings['AttachFile'] = 'Fitxategia erantsi';
        $strings['Maximum'] = 'gehienez';
        $strings['NoScheduleAdministratorLabel'] = 'Ez dago egutegi kudeatzailerik';
        $strings['ScheduleAdministrator'] = 'Egutegi kudeatzailea';
        $strings['Total'] = 'Guztira';
        $strings['QuantityReserved'] = 'Erreserbatutako kopurua';
        $strings['AllAccessories'] = 'Osagarri guztiak';
        $strings['GetReport'] = 'Txostena eskuratu';
        $strings['NoResultsFound'] = 'Ez dugu emaitzarik aurkitu';
        $strings['SaveThisReport'] = 'Txosten hau gorde';
        $strings['ReportSaved'] = 'Txostena gorde da!';
        $strings['EmailReport'] = 'Txostena epostaz bidali';
        $strings['ReportSent'] = 'Txostena bidali da!';
        $strings['RunReport'] = 'Txostena sortu';
        $strings['NoSavedReports'] = 'Ez duzu gorde txostena.';
        $strings['CurrentWeek'] = 'Oraingo astea';
        $strings['CurrentMonth'] = 'Oraingo hilabetea';
        $strings['AllTime'] = 'Dena';
        $strings['FilterBy'] = 'Iragazkia aplikatu';
        $strings['Select'] = 'Hautatu';
        $strings['List'] = 'Zerrenda';
        $strings['TotalTime'] = 'Denbora guztira';
        $strings['Count'] = 'Zenbatu';
        $strings['Usage'] = 'Erabilera';
        $strings['AggregateBy'] = 'Agregar por';
        $strings['Range'] = 'Tartea';
        $strings['Choose'] = 'Aukeratu';
        $strings['All'] = 'Dena';
        $strings['ViewAsChart'] = 'Grafika ikusi';
        $strings['ReservedResources'] = 'Erreserbatutako baliabideak';
        $strings['ReservedAccessories'] = 'Erreserbatutako osagarriak';
        $strings['ResourceUsageTimeBooked'] = 'Baliabideen erabilera - erreserba denbora';
        $strings['ResourceUsageReservationCount'] = 'Baliabideen erabilera - erreserba kopurua';
        $strings['Top20UsersTimeBooked'] = 'Lehen 20ak - Erreserbatutako denbora';
        $strings['Top20UsersReservationCount'] = 'Lehen 20ak - Erreserba kopurua';
        $strings['ConfigurationUpdated'] = 'Konfigurazio fitxategia eguneratu da';
        $strings['ConfigurationUiNotEnabled'] = 'Ezin da orrialde honetara sartu $conf[\'settings\'][\'pages\'][\'enable.configuration\'] Faltsua ezarrita dagoelako.';
        $strings['ConfigurationFileNotWritable'] = 'Konfigurazio fitxategia ez da editagarria. Mesedez fitxategi honetarako baimenak egiaztatu eta saiatu berriro.';
        $strings['ConfigurationUpdateHelp'] = 'Joan <a target=_blank href=%s>Laguntza fitxategia</a>-ren konfiguraziora aukera honen gaineko dokumentazioa ikusteko para documentación sobre estas opciones.';
        $strings['GeneralConfigSettings'] = 'Hobespenak';
        $strings['UseSameLayoutForAllDays'] = 'Erabili ordu banaketa berdina egun guztietan';
        $strings['LayoutVariesByDay'] = 'Ordu banaketa egunen araberakoa da';
        $strings['ManageReminders'] = 'Alarmak';
        $strings['ReminderUser'] = 'Erabiltzaile ID-a';
        $strings['ReminderMessage'] = 'Mezua';
        $strings['ReminderAddress'] = 'Helbideak';
        $strings['ReminderSendtime'] = 'Bidalketa ordua';
        $strings['ReminderRefNumber'] = 'Erreserbaren erreferentzia zenbakia';
        $strings['ReminderSendtimeDate'] = 'Alarmaren data';
        $strings['ReminderSendtimeTime'] = 'Alarmaren ordua (OO:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Alarma gehitu';
        $strings['DeleteReminderWarning'] = 'Ziur zaude?';
        $strings['NoReminders'] = 'Ez duzu alarmarik hemendik gutxira.';
        $strings['Reminders'] = 'Alarmak';
        $strings['SendReminder'] = 'Alarma mezua bidali';
        $strings['minutes'] = 'minutu';
        $strings['hours'] = 'ordu';
        $strings['days'] = 'egun';
        $strings['ReminderBeforeStart'] = 'hasiera ordua baino lehenago';
        $strings['ReminderBeforeEnd'] = 'amaiera ordua baino lehenago';
        $strings['Logo'] = 'Logoa';
        $strings['CssFile'] = 'CSS fitxategia';
        $strings['ThemeUploadSuccess'] = 'Aldaketak gorde dira. Freskatu orria aldaketak eragina izan dezan.';
        $strings['MakeDefaultSchedule'] = 'Koadrante hau nire lehenetsitako koadrante egin';
        $strings['DefaultScheduleSet'] = 'Hau da orain zure koadrente lehenetsia';
        $strings['FlipSchedule'] = 'Koadrantearen antolaketa biratu';
        $strings['Next'] = 'Hurrengoa';
        $strings['Success'] = 'Zuzen';
        $strings['Participant'] = 'Partehartzailea';
        $strings['ResourceFilter'] = 'Baliabideetarako iragazkia';
        $strings['ResourceGroups'] = 'Baliabide taldeak';
        $strings['AddNewGroup'] = 'Talde berria gehitu';
        $strings['Quit'] = 'Irten';
        $strings['AddGroup'] = 'Taldea gehitu';
        $strings['StandardScheduleDisplay'] = 'Planifikazio ikuspegi estandarra erabili';
        $strings['TallScheduleDisplay'] = 'Planifikazio ikuspegi luzea erabili';
        $strings['WideScheduleDisplay'] = 'Planifikazio ikuspegi zabala erabili';
        $strings['CondensedWeekScheduleDisplay'] = 'Astea trinkotua duen planifikazio ikuspegia erabili';
        $strings['ResourceGroupHelp1'] = 'Antolatu nahi dituzun baliabideak arrastratu.';
        $strings['ResourceGroupHelp2'] = 'Klik egin eskuineko botoiaz baliabide taldearen izenean ekintza gehigarriak ikusteko.';
        $strings['ResourceGroupHelp3'] = 'Arrastratu baliabideak taldeetan gehitzeko.';
        $strings['ResourceGroupWarning'] = 'Baliabide taldeak erabiliz gero, baliabide bakoitza talde batean egon behar du. Taldekatuta ez dauden baliabideak ezin izango dira erreserbatu.';
        $strings['ResourceType'] = 'Baliabide mota';
        $strings['AppliesTo'] = 'Honi aplikatzen zaio';
        $strings['UniquePerInstance'] = 'Bakarra eskabideko';
        $strings['AddResourceType'] = 'Baliabide mota gehitu';
        $strings['NoResourceTypeLabel'] = '(ez da ezarri baliabide mota)';
        $strings['ClearFilter'] = 'Iragazkia garbitu';
        $strings['MinimumCapacity'] = 'Gutxieneko edukiera';
        $strings['Color'] = 'Kolorea';
        $strings['Available'] = 'Erabilgarri';
        $strings['Unavailable'] = 'Ez erabilgarri';
        $strings['Hidden'] = 'Ezkutua';
        $strings['ResourceStatus'] = 'Baliabidearen egoera';
        $strings['CurrentStatus'] = 'Oraingo egoera';
        $strings['AllReservationResources'] = 'Erreserbetako baliabide guztiak';
        $strings['File'] = 'Fitxategia';
        $strings['BulkResourceUpdate'] = 'Baliabideen eguneraketa masiboa';
        $strings['Unchanged'] = 'Aldaketarik gabea';
        $strings['Common'] = 'Arrunta';
        $strings['AdminOnly'] = 'Kudeatzaileak soilik';
        $strings['AdvancedFilter'] = 'Iragazki aurreratua';
        $strings['MinimumQuantity'] = 'Gutxienezeko kopurua';
        $strings['MaximumQuantity'] = 'Gehienezko kopurua';
        $strings['ChangeLanguage'] = 'Hizkuntza aldatu';
        $strings['AddRule'] = 'Araua gehitu';
        $strings['Attribute'] = 'Ezaugarria';
        $strings['RequiredValue'] = 'Derrigorrezko balioa';
        $strings['ReservationCustomRuleAdd'] = '%s ba, orduan erreserbaren kolorea hau izango da:';
        $strings['AddReservationColorRule'] = 'Gehitu erreserbaren kolorerako araua';
        $strings['LimitAttributeScope'] = 'Kasu zehatzetan bildu';
        $strings['CollectFor'] = 'Bildu honetarako:';
        $strings['SignIn'] = 'Saioa hasi';
        $strings['AllParticipants'] = 'Partaide guztiak';
        $strings['RegisterANewAccount'] = 'Kontu berri bat erregistratu';
        $strings['Dates'] = 'Datak';
        $strings['More'] = 'Gehiago';
        $strings['ResourceAvailability'] = 'Baliabidearen erabilgarritasuna';
        $strings['UnavailableAllDay'] = 'Ez da erabilgarri egongo egun osoan';
        $strings['AvailableUntil'] = 'Ordu honetara arte erabilgarri';
        $strings['AvailableBeginningAt'] = 'Ordu honetatik erabilgarri';
        $strings['AllResourceTypes'] = 'Baliabide mota guztiak';
        $strings['AllResourceStatuses'] = 'Baliabide egoera guztiak';
        $strings['AllowParticipantsToJoin'] = 'Onartu partehartzaileak elkartzea';
        $strings['Join'] = 'Elkartu';
        $strings['YouAreAParticipant'] = 'Erreserba honetako partaidea zara';
        $strings['YouAreInvited'] = 'Erreserba honetara gonbidatuta zaude';
        $strings['YouCanJoinThisReservation'] = 'Erreserba honetara elkartu ahal zara';
        $strings['Import'] = 'Inportatu';
        $strings['GetTemplate'] = 'Txantiloia eskuratu';
        $strings['UserImportInstructions'] = 'Fitxategiak CSV formatoan egon behar du. Erabiltzaile izena eta posta helbidea derrigorrezko eremuak dira. Beste eremuak zuriz badaude balio lehenetsiak jasoko dituzte eta \'password\' erabiltzailearen pasahitza izango da. Ematen den txantiloia erabili adibide gisa.';
        $strings['RowsImported'] = 'Inportatutako errenkadak';
        $strings['RowsSkipped'] = 'Omititutako zutabeak';
        $strings['Columns'] = 'Zutabeak';
        $strings['Reserve'] = 'Erreserbatu';
        $strings['AllDay'] = 'Egun osoa';
        $strings['Everyday'] = 'Egunero';
        $strings['IncludingCompletedReservations'] = 'Burututako erreserbak barne';
        $strings['NotCountingCompletedReservations'] = 'Burututako erreserbak gabe';
        $strings['RetrySkipConflicts'] = 'Talka egiten duten erreserbak omititu';
        $strings['Retry'] = 'Saiatu berriro';
        $strings['RemoveExistingPermissions'] = 'Dauden baimenak ezabatu?';
        $strings['Continue'] = 'Jarraitu';
        $strings['WeNeedYourEmailAddress'] = 'Erreserba egiteko posta helbidea behar dugu';
        $strings['ResourceColor'] = 'Baliabidearen kolorea';
        $strings['DateTime'] = 'Data Ordua';
        $strings['AutoReleaseNotification'] = 'Automaitkoki askatuko da %s minututan «check in» egin ezean';
        $strings['RequiresCheckInNotification'] = '«check in»/«check out» beharrezkoa';
        $strings['NoCheckInRequiredNotification'] = 'Ez da behar «check in»/«check out»';
        $strings['RequiresApproval'] = 'Onarpena beharrezkoa';
        $strings['CheckingIn'] = '«check in» egiten';
        $strings['CheckingOut'] = '«check out» egiten';
        $strings['CheckIn'] = '«Check in»';
        $strings['CheckOut'] = '«Check out»';
        $strings['ReleasedIn'] = 'Askatzeko falta da: ';
        $strings['CheckedInSuccess'] = '«Check in» eginda';
        $strings['CheckedOutSuccess'] = '«Check out» eginda';
        $strings['CheckInFailed'] = 'Ezin izan da «check in» egin';
        $strings['CheckOutFailed'] = 'Ezin izan da «check out» egin';
        $strings['CheckInTime'] = '«check in» ordua';
        $strings['CheckOutTime'] = '«check out» ordua';
        $strings['OriginalEndDate'] = 'Amaiera originala';
        $strings['SpecificDates'] = 'Data espezifikoak erakutsi';
        $strings['Users'] = 'Erabiltzaileak';
        $strings['Guest'] = 'Gonbidatua';
        $strings['ResourceDisplayPrompt'] = 'Erakutsiko den baliabidea';
        $strings['Credits'] = 'Kredituak';
        $strings['AvailableCredits'] = 'Kreditu erabilgarriak';
        $strings['CreditUsagePerSlot'] = '%s kreditu behar dira denbora tarterako (bailara)';
        $strings['PeakCreditUsagePerSlot'] = '%s kreditu behar dira tarterako (punta)';
        $strings['CreditsRule'] = 'Ez duzu kreditu nahikorik. Beharrezkoak: %s. Kredituak kontuan: %s';
        $strings['PeakTimes'] = 'Ordu puntak';
        $strings['AllYear'] = 'Urte osoa';
        $strings['MoreOptions'] = 'Aukera gehiago';
        $strings['SendAsEmail'] = 'Postaz bidali';
        $strings['UsersInGroups'] = 'Erabiltzaileak taldean';
        $strings['UsersWithAccessToResources'] = 'Baliabideetarako sarbidea duten erabiltzaileak';
        $strings['AnnouncementSubject'] = '%s(e)k jakinarazpen berri bat argitaratu du';
        $strings['AnnouncementEmailNotice'] = 'erabiltzaileek e-postaz jasoko dute jakinarazpen hau';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'LibreBooking instalatu (MySQL baino ez)';
        $strings['IncorrectInstallPassword'] = 'Barkatu, pasahitza ez da zuzena.';
        $strings['SetInstallPassword'] = 'Instalazioa hasi baino lehen instalazio pasahitz bat ezarri behar duzu.';
        $strings['InstallPasswordInstructions'] = 'Mesedez ezarri %s -n %s ausaz sortuta eta asmatzeko zaila den pasahitz bat, orduan orrialde honetara itzuli.<br/>Puedes usar %s';
        $strings['NoUpgradeNeeded'] = 'Ez da beharrezkoa eguneratzea. Instalazio prozesua abiatuz gero dauden datu guztiak ezabatuko dira eta LibreBooking-en kopia berri bat instalatuko da!';
        $strings['ProvideInstallPassword'] = 'Mesedez, idatzi instalazio pasahitza.';
        $strings['InstallPasswordLocation'] = '%s -n egon daiteke,  %s -n.';
        $strings['VerifyInstallSettings'] = 'Egiaztatu lehenetsitako ezarpen hauek jarraitu baino lehen. Edo alda itzazu hemen: %s.';
        $strings['DatabaseName'] = 'Datu basearen izena';
        $strings['DatabaseUser'] = 'Datu basearen erabiltzailea';
        $strings['DatabaseHost'] = 'Datu base zerbitzaria';
        $strings['DatabaseCredentials'] = 'Datu baseak sortzeko baimenak dituen MySQL erabiltzalie baten kredentzialak eman behar dituzu. Ez badakizu zein, kontaktatu datu basearen kudeatzailearekin. Kasu askotan, «root» balio behar du.';
        $strings['MySQLUser'] = 'MySQL erabiltzailea';
        $strings['InstallOptionsWarning'] = 'Hurrengo aukerak agian ez dute funtzionatuko hosting batean. Hosting batean ari bazara instalatzen, erabili MySQL laguntza tresnak pausu hauek osatzeko.';
        $strings['CreateDatabase'] = 'Datu basea sortu';
        $strings['CreateDatabaseUser'] = 'Datu base erabiltzailea sortu';
        $strings['PopulateExampleData'] = 'Eredu datuak inportatu. Kudeatzailearen kontua sortuko du: admin/password eta erabiltzaile kontu hau: user/password';
        $strings['DataWipeWarning'] = 'Oharra: honek dauden datuak ezabatuko ditu';
        $strings['RunInstallation'] = 'Instalazioa abiarazi';
        $strings['UpgradeNotice'] = '<b>%s</b> bertsiotik <b>%s</b> bertsiora eguneratzen ari zara';
        $strings['RunUpgrade'] = 'Eguneraketa abiarazi';
        $strings['Executing'] = 'Lanean';
        $strings['StatementFailed'] = 'Errorea. Xehetasunak:';
        $strings['SQLStatement'] = 'SQL sententzia:';
        $strings['ErrorCode'] = 'Errorearen gakoa:';
        $strings['ErrorText'] = 'Errorearen testua:';
        $strings['InstallationSuccess'] = 'Instalazioa arrakastaz burutu da!';
        $strings['RegisterAdminUser'] = 'Erregistratu zure kudeatzaile erabiltzailea. Hau beharrezkoa da ez bazenituen eredu daturik inportatu. Ziurtatu $conf[\'settings\'][\'allow.self.registration\'] = \'true\' ezarrita dagoela %s fitxategian.';
        $strings['LoginWithSampleAccounts'] = 'Eredu datuak inportatu bazenituen, saioa hasi ahal duzu admin/password erabilita kudeatzaile erabiltzaile gisa edo user/password erabiltzaile arrunt gisa.';
        $strings['InstalledVersion'] = 'Une honetan LibreBooking-en %s bertsioa erabiltzen ari zara';
        $strings['InstallUpgradeConfig'] = 'Gomendagarria da konfigurazio fitxategia eguneratzea';
        $strings['InstallationFailure'] = 'Arazoak egon dira instalazioarekin. Mesedez, zuzendu itzazu eta saiatu berriro.';
        $strings['ConfigureApplication'] = 'LibreBooking konfiguratu';
        $strings['ConfigUpdateSuccess'] = 'Konfigurazio fitxategia eguneratu da!';
        $strings['ConfigUpdateFailure'] = 'Ezin izan da automatikoki eguneratu konfigurazio fitxategia. Mesedez gainidatzei config.php fitxategiaren edukia honekin:';
        $strings['SelectUser'] = 'Aukeratu erabiltzailea';
        // End Install


        // Errors
        $strings['LoginError'] = 'Ez da Erabiltzaile Izena (Identifikadorea) eta pasahitz horrekin ezer aurkitu';
        $strings['ReservationFailed'] = 'Zure erreserba ezin izan da burutu';
        $strings['MinNoticeError'] = 'Erreserba hau aldez aurretik egin behar da. Erreserbatu ahal den datarik hurbilena hau da: %s.';
        $strings['MaxNoticeError'] = 'Erreserba hau ezin da horrenbeste luzatu. Erreserbatu ahal den azken data hau da: %s.';
        $strings['MinDurationError'] = 'Erreserba honen gutxieneko iraupena: %s.';
        $strings['MaxDurationError'] = 'Erreserba honen gehienezko iraupena: %s.';
        $strings['ConflictingAccessoryDates'] = 'Ez dago horrelako osagarri nahikorik:';
        $strings['NoResourcePermission'] = 'Ez duzu baimenik eskatutako baliabideren baterako';
        $strings['ConflictingReservationDates'] = 'Data hauetako erreserben arteko gatazkak daude:';
        $strings['StartDateBeforeEndDateRule'] = 'Hasiera datak amaiera data baino lehenago izan behar duLa fecha de inicio debe ser anterior a la fecha final';
        $strings['StartIsInPast'] = 'Hasiera data ezin da pasatutakoa izan';
        $strings['EmailDisabled'] = 'Kudeatzaileak posta bidezko jakinarazpenak desgaitu ditu';
        $strings['ValidLayoutRequired'] = 'Eguneko 24 orduetarako denbora tarteak eman behar dira 12:00 AM-n hasita eta bukatuta.';
        $strings['CustomAttributeErrors'] = 'Eman dituzun ezaugarri gehigarriekin arazoak daude:';
        $strings['CustomAttributeRequired'] = '%s derrigorrezko eremua da.';
        $strings['CustomAttributeInvalid'] = '%s rako sartutako balioa ez da baliogarria.';
        $strings['AttachmentLoadingError'] = 'Barkatu, eskatutako fitxategiarekin arazo bat egon da.';
        $strings['InvalidAttachmentExtension'] = 'Mota honetako fitxategiak baino ez dira onartzen: %s';
        $strings['InvalidStartSlot'] = 'Eskatutako hasiera data eta ordua ez da baliogarria.';
        $strings['InvalidEndSlot'] = 'Eskatutako bukaera data eta ordua ez da baliogarria.';
        $strings['MaxParticipantsError'] = '%s (k) %s partehartzaile baino ez du onartzen.';
        $strings['ReservationCriticalError'] = 'Errore kritiko bat egon da zure erreserba gordetzean. Jarraitzen badu kudeatzailearekin kontaktatu.';
        $strings['InvalidStartReminderTime'] = 'Alarmaren hasiera ordua ez da baliogarria.';
        $strings['InvalidEndReminderTime'] = 'Alarmaren amaiera ordua ez da baliogarria.';
        $strings['QuotaExceeded'] = 'Kuota gaindituta.';
        $strings['MultiDayRule'] = '%s ezin da erreserbatu egun bat baino gehiagorako.';
        $strings['InvalidReservationData'] = 'Arazoren bat egon da zure erreserba eskaerarekin.';
        $strings['PasswordError'] = 'Pasahitzak gutxienez %s letra eta %s zenbaki eduki behar ditu.';
        $strings['PasswordErrorRequirements'] = 'Pasahitzak gutxienez %s letra larri eta xehez eta %s zenbakiko konbinaketa behar du.';
        $strings['NoReservationAccess'] = 'Ez duzu erreserba hau aldatzeko baimenik.';
        $strings['PasswordControlledExternallyError'] = 'Pasahitza kanpoko sistema batekin kontrolatzen da eta ezin da hemendik aldatu.';
        $strings['AccessoryResourceRequiredErrorMessage'] = '%s osagarria %s baliabidearekin batera baino ezin da erreserbatu';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Gutxienez %s erreserbatu behar duzu %s osagarritik';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Gehienez %s erreserbatu ahal duzu %s osagarritik';
        $strings['AccessoryResourceAssociationErrorMessage'] = '\'%s\' osagarria ezin da erreserbatu eskatutako baliabideekin batera';
        $strings['NoResources'] = 'Ez duzu baliabiderik gehitu.';
        $strings['ParticipationNotAllowed'] = 'Ez duzu baimenik erreserba honetara elkartzeko.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Ezin da «check in» egin erreserba honetan.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Ezin da «check out» egin erreserba honetan.';

        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Erreserba sortu';
        $strings['EditReservation'] = 'Erreserba editatu';
        $strings['LogIn'] = 'Saioa hasi';
        $strings['ManageReservations'] = 'Erreserbak kudeatu';
        $strings['AwaitingActivation'] = 'Aktibazioaren zain';
        $strings['PendingApproval'] = 'Onartzeko zain';
        $strings['ManageSchedules'] = 'Koadranteak';
        $strings['ManageResources'] = 'Baliabideak';
        $strings['ManageAccessories'] = 'Osagarriak';
        $strings['ManageUsers'] = 'Erabiltzaileak';
        $strings['ManageGroups'] = 'Taldeak';
        $strings['ManageQuotas'] = 'Kuotak';
        $strings['ManageBlackouts'] = 'Ez erabilgarritasun Agenda';
        $strings['MyDashboard'] = 'Nire Arbela';
        $strings['ServerSettings'] = 'Zerbitzariaren ezarpenak';
        $strings['Dashboard'] = 'Arbela';
        $strings['Help'] = 'Laguntza';
        $strings['Administration'] = 'Kudeaketa';
        $strings['About'] = 'Honi buruz';
        $strings['Bookings'] = 'Erreserbak';
        $strings['Schedule'] = 'Koadrantea';
        $strings['Reservations'] = 'Erreserbak';
        $strings['Account'] = 'Kontua';
        $strings['EditProfile'] = 'Editatu nire profila';
        $strings['FindAnOpening'] = 'Tarte bat bilatu';
        $strings['OpenInvitations'] = 'Zain dauden gonbidapenak';
        $strings['MyCalendar'] = 'Nire egutegia';
        $strings['ResourceCalendar'] = 'Baliabideen egutegia';
        $strings['Reservation'] = 'Erreserba berria';
        $strings['Install'] = 'Instalazioa';
        $strings['ChangePassword'] = 'Pasahitza aldatu';
        $strings['MyAccount'] = 'Nire kontua';
        $strings['Profile'] = 'Profila';
        $strings['ApplicationManagement'] = 'Aplikazioaren kudeaketa';
        $strings['ForgotPassword'] = 'Ahaztutako pasahitza';
        $strings['NotificationPreferences'] = 'Jakinarazpen ezarpenak';
        $strings['ManageAnnouncements'] = 'Jakinarazpenak';
        $strings['Responsibilities'] = 'Ardurak';
        $strings['GroupReservations'] = 'Taldeko erreserbak';
        $strings['ResourceReservations'] = 'Baliabideen erreserbak';
        $strings['Customization'] = 'Pertsonalizatu';
        $strings['Attributes'] = 'Ezaugarriak';
        $strings['AccountActivation'] = 'Kontuaren aktibazioa';
        $strings['ScheduleReservations'] = 'Erreserbak programatu';
        $strings['Reports'] = 'Txostenak';
        $strings['GenerateReport'] = 'Txosten berria sortu';
        $strings['MySavedReports'] = 'Nire gordetako txostenak';
        $strings['CommonReports'] = 'Txosten komunak';
        $strings['ViewDay'] = 'Eguna ikusi';
        $strings['Group'] = 'Taldea';
        $strings['ManageConfiguration'] = 'Aplikazioaren ezarpenak';
        $strings['LookAndFeel'] = 'Itxura';
        $strings['ManageResourceGroups'] = 'Baliabide taldeak';
        $strings['ManageResourceTypes'] = 'Baliabide motak';
        $strings['ManageResourceStatus'] = 'Baliabideen egoerak';
        $strings['ReservationColors'] = 'Erreserben koloreak';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'I';
        $strings['DayMondaySingle'] = 'A';
        $strings['DayTuesdaySingle'] = 'A';
        $strings['DayWednesdaySingle'] = 'A';
        $strings['DayThursdaySingle'] = 'O';
        $strings['DayFridaySingle'] = 'O';
        $strings['DaySaturdaySingle'] = 'L';

        $strings['DaySundayAbbr'] = 'Ig';
        $strings['DayMondayAbbr'] = 'Al';
        $strings['DayTuesdayAbbr'] = 'As';
        $strings['DayWednesdayAbbr'] = 'Az';
        $strings['DayThursdayAbbr'] = 'Og';
        $strings['DayFridayAbbr'] = 'Ol';
        $strings['DaySaturdayAbbr'] = 'La';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Zure erreserba onartu da';
        $strings['ReservationCreatedSubject'] = 'Zure erreserba sortu da';
        $strings['ReservationUpdatedSubject'] = 'Zure erreserba eguneratu da';
        $strings['ReservationDeletedSubject'] = 'Zure erreserba ezabatu da';
        $strings['ReservationCreatedAdminSubject'] = 'Jakinarazpena: erreserba bat sortu da';
        $strings['ReservationUpdatedAdminSubject'] = 'Jakinarazpena: erreserba bat eguneratu da';
        $strings['ReservationDeleteAdminSubject'] = 'Jakinarazpena: erreserba bat ezabatu da';
        $strings['ReservationApprovalAdminSubject'] = 'Jakinarazpena: erreserba batek onarpena behar du';
        $strings['ParticipantAddedSubject'] = 'Erreserba batean partehartzeari buruzko jakinarazpena';
        $strings['ParticipantDeletedSubject'] = 'Erreserba batean partehartzea ezabatu da';
        $strings['InviteeAddedSubject'] = 'Erreserbarako gonbidapena';
        $strings['ResetPassword'] = 'Pasahitza berrizteko eskaera';
        $strings['ActivateYourAccount'] = 'Mesedez, aktibatu zure kontua';
        $strings['ReportSubject'] = 'Eskatutako txostena: (%s)';
        $strings['ReservationStartingSoonSubject'] = '%s (r)en erreserba laster hasiko da';
        $strings['ReservationEndingSoonSubject'] = '%s (r)en erreserba laster amaituko da';
        $strings['UserAdded'] = 'Erabiltzaile berria gehitu da';
        $strings['UserDeleted'] = '%s (r)en erabiltzaile kontua  %s (e)k ezabatu du';
        $strings['GuestAccountCreatedSubject'] = 'Kontuaren xehetasunak';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Ez duzu aurreko erreserik';
        $strings['PastReservations'] = 'Aurreko erreserak';
        $strings['AllNoPastReservations'] = 'Ez dago aurreko erreserarik azken %s egunetan';
        $strings['AllPastReservations'] = 'Denak aurreko erreserak';
        $strings['Yesterday'] = 'Atzo';
        $strings['EarlierThisWeek'] = 'Aurreko astean';
        $strings['PreviousWeek'] = 'Aurreko astean';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Zure taldeak ez du etorriko erreserbarik';
        $strings['GroupUpcomingReservations'] = 'Nire Taldeen Hurrengo Erreserba(k)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Facebook-en saioa hastean errorea gertatu da. Mesedez, saiatu berriro.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Ez duzu onartu beharreko erreserbarik';
        $strings['PendingApprovalReservations'] = 'Onartzea zaindako erreserbak';
        $strings['LaterThisMonth'] = 'Hilabete honetan geroago';
        $strings['LaterThisYear'] = 'Aurten geroago';
        $strings['Remaining'] = 'Geratzen dena';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Ez dago irteerako erreserbarik falta';
        $strings['MissingCheckOutReservations'] = 'Irteerako erreserbak falta dira';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Ezinezkoa da erreserbaaren xehetasunak ikustea, erreserba honetan dagoen edozein baliabideen baimenik ez delako';
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
        $days['full'] = ['Igandea', 'Astelehena', 'Asteartea', 'Asteazkena', 'Osteguna', 'Ostirala', 'Larunbata'];
        // The three letter abbreviation
        $days['abbr'] = ['Iga', 'Ast', 'Ast', 'Ast', 'Ost', 'Ost', 'Lar'];
        // The two letter abbreviation
        $days['two'] = ['Ig', 'Al', 'As', 'Az', 'Og', 'Ol', 'La'];
        // The one letter abbreviation
        $days['letter'] = ['I', 'A', 'A', 'A', 'O', 'O', 'L'];

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
        $months['full'] = ['Urtarrila', 'Otsaila', 'Martxoa', 'Apirila', 'Maiatza', 'Ekaina', 'Uztaila', 'Abuztua', 'Iraila', 'Urria', 'Azaroa', 'Abendua'];
        // The three letter month name
        $months['abbr'] = ['Urt', 'Ots', 'Mar', 'Api', 'Mai', 'Eka', 'Uzt', 'Abu', 'Ira', 'Urr', 'Aza', 'Abe'];

        $this->Months = $months;
        return $this->Months;
    }

    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'eu_es';
    }
}
