<?php

require_once('Language.php');
require_once('en_gb.php');

class ro_ro extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Nume';
        $strings['LastName'] = 'Prenume';
        $strings['Timezone'] = 'Fus orar';
        $strings['Edit'] = 'Modifica';
        $strings['Change'] = 'Schimba';
        $strings['Rename'] = 'Redenumeste';
        $strings['Remove'] = 'Elimina';
        $strings['Delete'] = 'Sterge';
        $strings['Update'] = 'Actualizeaza';
        $strings['Cancel'] = 'Renunta';
        $strings['Add'] = 'Adauga';
        $strings['Name'] = 'Nume';
        $strings['Yes'] = 'da';
        $strings['No'] = 'nu';
        $strings['FirstNameRequired'] = 'Nume � obbligatoriu.';
        $strings['LastNameRequired'] = 'Prenume &obligatoriu.';
        $strings['PwMustMatch'] = 'Confirmati Parola trebuie sa se potriveasca parola.';
        $strings['PwComplexity'] = 'Parola trebuie să fie de cel puțin 6 caractere cu o combinatie de litere, cifre si simboluri.';
        $strings['ValidEmailRequired'] = 'E\' necesară o adresă de e-mail validă.';
        $strings['UniqueEmailRequired'] = 'Aceasta adresa e-mail este deja înregistrata.';
        $strings['UniqueUsernameRequired'] = 'Acest usernameme este deja înregistrat.';
        $strings['UserNameRequired'] = 'Username obbligatoriu.';
        $strings['CaptchaMustMatch'] = 'Tasteaza de caracterele din\' imaginea de securitate întocmai cum arată.';
        $strings['Today'] = 'Azi';
        $strings['Week'] = 'Saptamana';
        $strings['Month'] = 'Luna';
        $strings['BackToCalendar'] = 'Inapoi la calendar';
        $strings['BeginDate'] = 'Start';
        $strings['EndDate'] = 'Stop';
        $strings['Username'] = 'Username';
        $strings['Password'] = 'Password';
        $strings['PasswordConfirmation'] = 'Confirma Password';
        $strings['DefaultPage'] = 'Pagina de start implicita';
        $strings['MyCalendar'] = 'Calendarul meu';
        $strings['ScheduleCalendar'] = 'Calendar programari';
        $strings['Registration'] = 'Inregistrare';
        $strings['NoAnnouncements'] = 'Nu sunt anunturi';
        $strings['Announcements'] = 'Anunturi';
        $strings['NoUpcomingReservations'] = 'Nu ai rezervari in viitorul apropiat';
        $strings['UpcomingReservations'] = 'Rezervari viitoare';
        $strings['ShowHide'] = 'Arata/Ascunde';
        $strings['Error'] = 'Eroare';
        $strings['ReturnToPreviousPage'] = 'Intoarce-te la\'ultima pagina in care te-ai aflat';
        $strings['UnknownError'] = 'Eroare necunoscuta';
        $strings['InsufficientPermissionsError'] = 'NU ai permisiunea sa accesezi aceasta resursa';
        $strings['MissingReservationResourceError'] = 'NU ai selectat o resursa';
        $strings['MissingReservationScheduleError'] = 'NU ai selectat o rezervare';
        $strings['DoesNotRepeat'] = 'NU repeta';
        $strings['Daily'] = 'Zilnic';
        $strings['Weekly'] = 'Saptamanal';
        $strings['Monthly'] = 'Lunar';
        $strings['Yearly'] = 'Anual';
        $strings['RepeatPrompt'] = 'Repeta';
        $strings['hours'] = 'ore';
        $strings['days'] = 'zile';
        $strings['weeks'] = 'saptamani';
        $strings['months'] = 'luni';
        $strings['years'] = 'ani';
        $strings['day'] = 'zi';
        $strings['week'] = 'saptamana';
        $strings['month'] = 'luna';
        $strings['year'] = 'an';
        $strings['repeatDayOfMonth'] = 'zi a lunii';
        $strings['repeatDayOfWeek'] = 'zi a saptamanii';
        $strings['RepeatUntilPrompt'] = 'Pana la';
        $strings['RepeatEveryPrompt'] = 'Fiecare';
        $strings['RepeatDaysPrompt'] = 'On';
        $strings['CreateReservationHeading'] = 'Creeaza o noua rezervare';
        $strings['EditReservationHeading'] = 'Modifica rezervarea %s';
        $strings['ViewReservationHeading'] = 'Vezi rezervarea %s';
        $strings['ReservationErrors'] = 'Schimba rezervarea';
        $strings['Create'] = 'Creeaza';
        $strings['ThisInstance'] = 'Doar acest exemplu';
        $strings['AllInstances'] = 'Toate repetarile';
        $strings['FutureInstances'] = 'Repetarile viitoare';
        $strings['Print'] = 'Printeaza';
        $strings['ShowHideNavigation'] = 'Arata/Ascunde Navigare';
        $strings['ReferenceNumber'] = 'Numar de referinta';
        $strings['Tomorrow'] = 'Maine';
        $strings['LaterThisWeek'] = 'Sfarsitul acestei saptamani';
        $strings['NextWeek'] = 'Saptamana urmatoare';
        $strings['SignOut'] = 'Iesi';
        $strings['LayoutDescription'] = 'Incepe cu ziua de %s, arata %s zile';
        $strings['AllResources'] = 'Toate resursele';
        $strings['TakeOffline'] = 'Deconectare';
        $strings['BringOnline'] = 'Conectare';
        $strings['AddImage'] = 'Adauga imagine';
        $strings['NoImage'] = 'Nicio imagine atribuita';
        $strings['Move'] = 'Treci mai departe';
        $strings['AppearsOn'] = 'Arata pe %s';
        $strings['Location'] = 'Locatia';
        $strings['NoLocationLabel'] = '(nicio locatie setata)';
        $strings['Contact'] = 'Contact';
        $strings['NoContactLabel'] = '(nicio informatie de contact)';
        $strings['Description'] = 'Descriere';
        $strings['NoDescriptionLabel'] = '(nicio descriere)';
        $strings['Notes'] = 'Note';
        $strings['NoNotesLabel'] = '(nicio nota)';
        $strings['NoTitleLabel'] = '(niciun titlu)';
        $strings['UsageConfiguration'] = 'Configurarea de utilizare';
        $strings['ChangeConfiguration'] = 'Schimba configurarea';
        $strings['ResourceMinLength'] = 'Rezervarile trebuie sa fie de cel putin %s';
        $strings['ResourceMinLengthNone'] = 'Nu este prevazuta o durata minima de rezervare';
        $strings['ResourceMaxLength'] = 'Rezervarea nu poate dura mai mult de %s';
        $strings['ResourceMaxLengthNone'] = 'Nu este prevazuta o durata maxima de rezervare';
        $strings['ResourceRequiresApproval'] = 'Rezervarea trebuie aprobata';
        $strings['ResourceRequiresApprovalNone'] = 'Rezervarea nu necesita aprobare';
        $strings['ResourcePermissionAutoGranted'] = 'Autorizatii acordate in mod automat';
        $strings['ResourcePermissionNotAutoGranted'] = 'Permisiunea nu se acorda automat';
        $strings['ResourceMinNotice'] = 'Rezervarile trebuie facute cu cel putin %s fata de ora de\'incepere';
        $strings['ResourceMinNoticeNone'] = 'Rezervarile se pot face pana in momentul de fata';
        $strings['ResourceMaxNotice'] = 'Rezervarea nu trebuie sa se sfarseasca mai devreme de %s momentul actual';
        $strings['ResourceMaxNoticeNone'] = 'Rezervarile se pot termina in orice moment in viitor';
        $strings['ResourceAllowMultiDay'] = 'Rezervarile se pot face pe mai multe\' zile';
        $strings['ResourceNotAllowMultiDay'] = 'Rezervarile nu pot fi facute pe mai multe\' zile';
        $strings['ResourceCapacity'] = 'Aceasta sala are o capacitate\' de %s persoane';
        $strings['ResourceCapacityNone'] = 'Aceasta sala are o capacitate\' nelimitata';
        $strings['AddNewResource'] = 'Adauga resursa noua';
        $strings['AddNewUser'] = 'Adauga un nou utilizator';
        $strings['AddUser'] = 'Adauga user';
        $strings['Schedule'] = 'Orar';
        $strings['AddResource'] = 'Adauga sala';
        $strings['Capacity'] = 'Capacitate\'';
        $strings['Access'] = 'Acces';
        $strings['Duration'] = 'Durata';
        $strings['Active'] = 'Activa';
        $strings['Inactive'] = 'Inactiva';
        $strings['ResetPassword'] = 'Reseteaza Parola';
        $strings['LastLogin'] = 'Ultima Logare';
        $strings['Search'] = 'Cauta';
        $strings['ResourcePermissions'] = 'Permesiuni sala';
        $strings['Reservations'] = 'Rezervari';
        $strings['Groups'] = 'Grupuri';
        $strings['ResetPassword'] = 'Reseteaza Parola';
        $strings['AllUsers'] = 'Toti utilizatorii';
        $strings['AllGroups'] = 'Toate grupurile';
        $strings['AllSchedules'] = 'Toate programarile';
        $strings['UsernameOrEmail'] = 'Username sau Email';
        $strings['Members'] = 'Membri';
        $strings['QuickSlotCreation'] = 'Creeati sloturi de %s minute intre %s si %s';
        $strings['ApplyUpdatesTo'] = 'Aplica improspatare la ';
        $strings['CancelParticipation'] = 'Sterge participarea';
        $strings['Attending'] = 'Participa';
        $strings['QuotaConfiguration'] = 'Pe %s pentru %s utilizatori %s sunt limitate la %s %s pe %s';
        $strings['reservations'] = 'rezervari';
        $strings['ChangeCalendar'] = 'Schimba Calendar';
        $strings['AddQuota'] = 'Adauga cota';
        $strings['FindUser'] = 'Cauta user';
        $strings['Created'] = 'Creat';
        $strings['LastModified'] = 'Ultima Modificare';
        $strings['GroupName'] = 'Nume Grup';
        $strings['GroupMembers'] = 'Membri Grup';
        $strings['GroupRoles'] = 'Roluri Grup';
        $strings['GroupAdmin'] = 'Administrator Grup';
        $strings['Actions'] = 'Actiuni';
        $strings['CurrentPassword'] = 'Parola Actuala';
        $strings['NewPassword'] = 'Parola noua';
        $strings['InvalidPassword'] = 'Parola actuala nu este\' valida';
        $strings['PasswordChangedSuccessfully'] = 'Parola a fost schimbata cu succes';
        $strings['SignedInAs'] = 'Logat ca si';
        $strings['NotSignedIn'] = 'Nu sunteti logat';
        $strings['ReservationTitle'] = 'Titlul rezervarii';
        $strings['ReservationDescription'] = 'Descrierea rezervarii';
        $strings['ResourceList'] = 'Lista salilor';
        $strings['Accessories'] = 'Accesorii';
        $strings['Add'] = 'Adauga';
        $strings['ParticipantList'] = 'Participanti';
        $strings['InvitationList'] = 'Invitati';
        $strings['AccessoryName'] = 'Nume Accesoriu';
        $strings['QuantityAvailable'] = 'Cantitate disponibila';
        $strings['Resources'] = 'Sali';
        $strings['Participants'] = 'Participanti';
        $strings['User'] = 'User';
        $strings['Resource'] = 'Sala';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Aproba';
        $strings['Page'] = 'Pagina';
        $strings['Rows'] = 'Rind';
        $strings['Unlimited'] = 'Nelimitat';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Adresa Email';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Organizatia';
        $strings['Position'] = 'Pozitia';
        $strings['Language'] = 'Limba';
        $strings['Permissions'] = 'Permisiuni';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Cauta Grup';
        $strings['Manage'] = 'Gestioneaza';
        $strings['None'] = 'Nimeni';
        $strings['AddToOutlook'] = 'Adauga la Outlook';
        $strings['Done'] = 'Gata';
        $strings['RememberMe'] = 'Tine-ma minte';
        $strings['FirstTimeUser?'] = 'Utilizator nou?';
        $strings['CreateAnAccount'] = 'Creeaza un cont';
        $strings['ViewSchedule'] = 'Vezi programarea';
        $strings['ForgotMyPassword'] = 'Am uitat parola';
        $strings['YouWillBeEmailedANewPassword'] = 'Iti va fi\' trimisa pe e-mail o parola aleatorie';
        $strings['Close'] = 'Inchide';
        $strings['ExportToCSV'] = 'Exporta in CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Lucreaza';
        $strings['Login'] = 'Login';
        $strings['AdditionalInformation'] = 'Informatii Aditionale';
        $strings['AllFieldsAreRequired'] = 'toate campurile sunt obligatorii';
        $strings['Optional'] = 'optional';
        $strings['YourProfileWasUpdated'] = 'Profilul tau a fost\' actualizat';
        $strings['YourSettingsWereUpdated'] = 'Setarile tale au fost actualizate';
        $strings['Register'] = 'Inregistreaza-te';
        $strings['SecurityCode'] = 'Cod de siguranta';
        $strings['ReservationCreatedPreference'] = 'Cand am creat o rezervare sau a fost creata una in numele meu';
        $strings['ReservationUpdatedPreference'] = 'Cand am modificat o rezervare sau a fost modificata una in numele meu';
        $strings['ReservationDeletedPreference'] = 'Cand am sters o rezervare sau a fost stearsa una in numele meu';
        $strings['ReservationApprovalPreference'] = 'Cand rezervarea mea asteapta sa fie aprobata';
        $strings['PreferenceSendEmail'] = 'Trimite-mi un e-mail';
        $strings['PreferenceNoEmail'] = 'Nu-mi trimite notificare';
        $strings['ReservationCreated'] = 'Rezervarea ta a fost creata!';
        $strings['ReservationUpdated'] = 'Rezervarea ta a fost actualizata!';
        $strings['ReservationRemoved'] = 'Rezervarea ta a fost stearsa';
        $strings['YourReferenceNumber'] = 'Numarul tau de referinta este %s';
        $strings['UpdatingReservation'] = 'Actualizeaza rezervarea';
        $strings['ChangeUser'] = 'Schimba utilizator';
        $strings['MoreResources'] = 'Mai multe resurse';
        $strings['ReservationLength'] = 'Lungimea rezervarii';
        $strings['ParticipantList'] = 'Lista participanti';
        $strings['AddParticipants'] = 'Adauga Participanti';
        $strings['InviteOthers'] = 'Invita Altii';
        $strings['AddResources'] = 'Adauga resurse';
        $strings['AddAccessories'] = 'Adauga Accesorii';
        $strings['Accessory'] = 'Accesorii';
        $strings['QuantityRequested'] = 'Cantitate\' Solicitata';
        $strings['CreatingReservation'] = 'Creeaza rezervarea';
        $strings['UpdatingReservation'] = 'Actualizeaza rezervarea';
        $strings['DeleteWarning'] = 'Aceasta actiune este\' permanenta si nu poate fi recuperata!';
        $strings['DeleteAccessoryWarning'] = 'Stergand acest accesoriu, va sterge\' toate rezervarile.';
        $strings['AddAccessory'] = 'Adauga accesoriu';
        $strings['AddBlackout'] = 'Adauga scoatere din circuit';
        $strings['AllResourcesOn'] = 'Toate resursele ON';
        $strings['Reason'] = 'Motiv';
        $strings['BlackoutShowMe'] = 'Arata rezervarile in conflict';
        $strings['BlackoutDeleteConflicts'] = 'Sterge rezervarile in conflict';
        $strings['Filter'] = 'Filtreaza';
        $strings['Between'] = 'Intre';
        $strings['CreatedBy'] = 'Creat de';
        $strings['BlackoutCreated'] = 'Scoatere din circuit creata!';
        $strings['BlackoutNotCreated'] = 'Scoaterea din circuit nu a putut\' fi creata!';
        $strings['BlackoutConflicts'] = 'Exista scoateri din circuit in conflict';
        $strings['ReservationConflicts'] = 'Exista perioade ale rezervarii in conflict';
        $strings['UsersInGroup'] = 'Utilizatori in acest grup';
        $strings['Browse'] = 'Rasfoiti';
        $strings['DeleteGroupWarning'] = 'Stergand acest grupe veti sterge\' toate permisiunile asociate.  Anumiti utilizatori din acest grup ar putea pierde\'accesul la resurse.';
        $strings['WhatRolesApplyToThisGroup'] = 'Ce rol se aplica acestui grup?';
        $strings['WhoCanManageThisGroup'] = 'Cine poate\' gestiona acest grup?';
        $strings['AddGroup'] = 'Adauga grup';
        $strings['AllQuotas'] = 'Toate cotele alocate';
        $strings['QuotaReminder'] = 'Atentie: Cotele sunt aplicate în functie de fusul orar al calendarului.';
        $strings['AllReservations'] = 'Toate rezervarile';
        $strings['PendingReservations'] = 'Rezervari in asteptare';
        $strings['Approving'] = 'Aprobare';
        $strings['MoveToSchedule'] = 'Du-te la calendar';
        $strings['DeleteResourceWarning'] = 'Stergand aceasta resursa veti sterge\' inclusiv toate datele asociate';
        $strings['DeleteResourceWarningReservations'] = 'toate rezervarile trecute, actuale si viitoare asociate cu ea';
        $strings['DeleteResourceWarningPermissions'] = 'alocarea tuturor permisiunilor';
        $strings['DeleteResourceWarningReassign'] = 'Realocati toate resursele pe care nu doriti sa le stergeti';
        $strings['ScheduleLayout'] = 'Aspect (Fus orar %s)';
        $strings['ReservableTimeSlots'] = 'Intervale de timp disponibile pentru rezervare';
        $strings['BlockedTimeSlots'] = 'Intervale de timp blocate';
        $strings['ThisIsTheDefaultSchedule'] = 'Acesta este\' calendarul prestabilit';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Calendarul prestabilit nu poate\'  fi sters';
        $strings['MakeDefault'] = 'Marcheaza ca implicit';
        $strings['BringDown'] = 'Mai jos\'';
        $strings['ChangeLayout'] = 'Schimba Aspectul';
        $strings['AddSchedule'] = 'Adauga calendar';
        $strings['StartsOn'] = 'Incepe in';
        $strings['NumberOfDaysVisible'] = 'Numar de zile vizibile';
        $strings['UseSameLayoutAs'] = 'Foloseste acelasi format ca si ';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Eticheta optionala';
        $strings['LayoutInstructions'] = 'Introduceti un interval pentru fiecare rand. Intervalele trebuie să fie furnizate pentru toate cele 24 de ore ale zilei de pornire si se termină la 00:00.';
        $strings['AddUser'] = 'Adauga user';
        $strings['UserPermissionInfo'] = 'Accesul efectiv la resurse poate fi diferit, în functie de rolul, permisiunile de grup sau setarile de permisiuni externe';
        $strings['DeleteUserWarning'] = 'Stergand acest utilizator veti elimina toate rezervele sale actuale, viitoare si trecute.';
        $strings['AddAnnouncement'] = 'Adauga anunt';
        $strings['Announcement'] = 'Anunt';
        $strings['Priority'] = 'Prioritate';
        $strings['Reservable'] = 'Rezervabil';
        $strings['Unreservable'] = 'Nerezervabil';
        $strings['Reserved'] = 'Rezervat';
        $strings['MyReservation'] = 'Rezervarile mele';
        $strings['Pending'] = 'In curs';
        $strings['Past'] = 'Trecut';
        $strings['Restricted'] = 'Interzis';
        $strings['ViewAll'] = 'Vezi toate';
        $strings['MoveResourcesAndReservations'] = 'Muta resursele și rezervarea';
        $strings['TurnOffSubscription'] = 'Dezactivati inregistrarea la Calendar';
        $strings['TurnOnSubscription'] = 'Se permit inscrieri la acest calendar';
        $strings['SubscribeToCalendar'] = 'Inscrieti-va la acest calendar';
        $strings['SubscriptionsAreDisabled'] = 'Administratorul a dezactivat inscrierile la calendar';
        $strings['NoResourceAdministratorLabel'] = '(Fara administrator de resurse)';
        $strings['WhoCanManageThisResource'] = 'Cine poate\' gestiona aceasta resursa?';
        $strings['ResourceAdministrator'] = 'Administrator de resurse';
        $strings['Private'] = 'Privat';
        $strings['Accept'] = 'Accepta';
        $strings['Decline'] = 'Refuza';
        $strings['ShowFullWeek'] = 'Arata toata saptamna';
        $strings['CustomAttributes'] = 'Atribute personalizate';
        $strings['AddAttribute'] = 'Adauga un atribut';
        $strings['EditAttribute'] = 'Modifica un atribut';
        $strings['DisplayLabel'] = 'Eticheta';
        $strings['Type'] = 'Zip';
        $strings['Required'] = 'Obligatoriu';
        $strings['ValidationExpression'] = 'Expresie de validare';
        $strings['PossibleValues'] = 'Valori posibile';
        $strings['SingleLineTextbox'] = 'Caseta de text cu o singura linie';
        $strings['MultiLineTextbox'] = 'Caseta text linii multiple';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Lista de selectare';
        $strings['CommaSeparated'] = 'separat de virgula';
        $strings['Category'] = 'Categoria';
        $strings['CategoryReservation'] = 'Categorii rezervari';
        $strings['CategoryGroup'] = 'Grup';
        $strings['SortOrder'] = 'Ordine';
        $strings['Title'] = 'Titlu';
        $strings['AdditionalAttributes'] = 'Atribute suplimentare';
        $strings['True'] = 'Adevarat';
        $strings['False'] = 'Fals';
        $strings['ForgotPasswordEmailSent'] = 'Una email e\' stata inviata all\'indirizzo fornito con le istruzioni per resettare la password';
        $strings['ActivationEmailSent'] = 'Veti primi in cel mai scurt timp un e-mail de activare.';
        $strings['AccountActivationError'] = 'Scuze, nu pot activa contul tau';
        $strings['Attachments'] = 'Anexe';
        $strings['AttachFile'] = 'Anexeaza fisier';
        $strings['Maximum'] = 'max';
        $strings['FindATime'] = 'Gaseste un loc liber';
        $strings['ThisWeek'] = 'Saptamana curenta';
        $strings['DateRange'] = 'Defineste interval';
        $strings['GetReport'] = 'Obtine raportul';
        $strings['NoResultsFound'] = 'Nu au fost gasite rezultate care sa se potriveasca';
        $strings['SaveThisReport'] = 'Salveaza acest raport';
        $strings['ReportSaved'] = 'Raport salvat!';
        $strings['EmailReport'] = 'Trimite raport prin e-mail';
        $strings['ReportSent'] = 'Raport trimis!';
        $strings['RunReport'] = 'Ruleaza raportul';
        $strings['NoSavedReports'] = 'Nu aveti rapoarte salvate.';
        $strings['CurrentWeek'] = 'Saptamana curenta';
        $strings['CurrentMonth'] = 'Luna curenta';
        $strings['AllTime'] = 'Oricand';
        $strings['FilterBy'] = 'Filtreaza dupa';
        $strings['Select'] = 'Selecteaza';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Timp total';
        $strings['Count'] = 'Contor';
        $strings['Usage'] = 'Folosire';
        $strings['AggregateBy'] = 'Grupeaza dupa';
        $strings['Range'] = 'Interval';
        $strings['Choose'] = 'Alege';
        $strings['All'] = 'Toate';
        $strings['ViewAsChart'] = 'Vezi ca si grafic';
        $strings['ReservedResources'] = 'Resurse rezervate';
        $strings['ReservedAccessories'] = 'Accesorii rezervate';
        $strings['ResourceUsageTimeBooked'] = 'Folosire resurse - timp rezervat';
        $strings['ResourceUsageReservationCount'] = 'Folosire resurse - Contor rezervari';
        $strings['Top20UsersTimeBooked'] = 'Top 20 utilizatori - Timp rezervat';
        $strings['Top20UsersReservationCount'] = 'Top 20 utilizatori - Contor rezervari';
        $strings['IncludeDeleted'] = 'Include rezervarile sterse';
        $strings['Columns'] = 'Coloane';
        $strings['DeleteReminderWarning'] = 'Esti sigur ca vrei sa stergi asta?';
        $strings['More'] = 'Mai mult';
        $strings['ResourceFilter'] = 'Filtru resurse';
        $strings['ClearFilter'] = 'Sterge filtrele';
        $strings['MinimumCapacity'] = 'Capacitate minima';
        $strings['ResourceType'] = 'Tipul de resursa';
        $strings['ResourceAvailability'] = 'Disponibilitate resurse';
        $strings['UnavailableAllDay'] = 'Nu sunt disponibile intreaga zi';
        $strings['AvailableUntil'] = 'Disponibil pana';
        $strings['AvailableBeginningAt'] = 'Disponibil incepand de';
        $strings['Available'] = 'Disponibile';
        $strings['Unavailable'] = 'Nu sunt disponibile';
        $strings['NotifyWhenAvailable'] = 'Notifica-ma cand este disponibila';
        $strings['AddingToWaitlist'] = 'Adauga la lista de asteptare';
        $strings['WaitlistRequestAdded'] = 'Veti fi notificat cand intervalul de timp devine disponibil';
        $strings['Reserve'] = 'Rezerva';
        $strings['MoreOptions'] = 'Mai multe optiuni';
        $strings['SendAsEmail'] = 'Trimite ca si e-mail';

        // Errors
        $strings['LoginError'] = 'Username o password errate';
        $strings['ReservationFailed'] = 'Rezervarea dvs. nu poate fi acceptata' ;
        $strings['MinNoticeError'] = 'Questa prenotazione necessita di preavviso. La prima data che pu&ograve; essere prenotata &egrave %s.';
        $strings['MaxNoticeError'] = 'Questa prenotazione non pu&ograve; essere fatta fino a questo punto nel futuro. L\'ultima data che pu&ograve; essere prenotata &egrave %s.';
        $strings['MinDurationError'] = 'Rezervarea trebuie să fie de cel puțin %s.';
        $strings['MaxDurationError'] = 'Questa prenotazione non pu&ograve; durare pi&ugrave; di %s.';
        $strings['ConflictingAccessoryDates'] = 'Nu sunt suficiente următoarele accesorii:';
        $strings['NoResourcePermission'] = 'Nu aveți permisiunea de a avea acces la una sau mai multe; din resursele necesare';
        $strings['ConflictingReservationDates'] = 'Există rezervari contradictorii cu privire la următoarele date:';
        $strings['StartDateBeforeEndDateRule'] = 'Data de începere trebuie să fie anterioară datei de încheiere';
        $strings['StartIsInPast'] = 'Data de inceput nu poate fi stabilita in trecut';
        $strings['EmailDisabled'] = 'Administratorul a restrictionat notificarea prin e-mail';
        $strings['ValidLayoutRequired'] = 'Gli Slots devono essere indicati per tutte le 24 ore del giorno partendo e finendo alle 12:00 AM.';

        // Page Titles
        $strings['CreateReservation'] = 'Creeaza o rezervare';
        $strings['EditReservation'] = 'Modifica Rezervarea';
        $strings['LogIn'] = 'Log In';
        $strings['ManageReservations'] = 'Administreaza rezervarile';
        $strings['AwaitingActivation'] = 'Activari in asteptare';
        $strings['PendingApproval'] = 'Aprobari in asteptare';
        $strings['ManageSchedules'] = 'Administreaza intervalele orare';
        $strings['ManageResources'] = 'Resurse';
        $strings['ManageAccessories'] = 'Accesorii';
        $strings['ManageUsers'] = 'Useri';
        $strings['ManageGroups'] = 'Grupuri';
        $strings['ManageQuotas'] = 'Administreaza cotele';
        $strings['ManageBlackouts'] = 'Scos din circuit';
        $strings['MyDashboard'] = 'Panoul meu de control';
        $strings['ServerSettings'] = 'Setari server';
        $strings['Dashboard'] = 'Panou de control';
        $strings['Help'] = 'Ajutor';
        $strings['Bookings'] = 'Rezervari';
        $strings['Schedule'] = 'Orar';
        $strings['Reservations'] = 'Rezervarile';
        $strings['Account'] = 'Cont';
        $strings['EditProfile'] = 'Modifica profilul meu';
        $strings['FindAnOpening'] = 'Gaseste un loc liber';
        $strings['OpenInvitations'] = 'Deschide pentru invitatii';
        $strings['MyCalendar'] = 'Calendarul meu';
        $strings['ResourceCalendar'] = 'Calendarul resurselor';
        $strings['Reservation'] = 'Rezervare noua';
        $strings['Install'] = 'Instaleaza';
        $strings['ChangePassword'] = 'Schimba parola';
        $strings['MyAccount'] = 'Contul meu';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Gestioneaza aplicatia';
        $strings['ForgotPassword'] = 'Am uitat parola';
        $strings['NotificationPreferences'] = 'Preferinte de notificare';
        $strings['ManageAnnouncements'] = 'Administreaza anunturile';
        $strings['LookAndFeel'] = 'Aspect aplicatie';
        $strings['ManageConfiguration'] = 'Configurare aplicatie';
        $strings['ManageResourceTypes'] = 'Tipuri de resurse';
        $strings['Reports'] = 'Rapoarte';
        $strings['GenerateReport'] = 'Creeaza un raport nou';
        $strings['MySavedReports'] = 'Rapoartele mele salvate';
        $strings['CommonReports'] = 'Rapoarte comune';
        $strings['Responsibilities'] = 'Responsabilitati';
        $strings['GroupReservations'] = 'Rezervari de grup';
        $strings['ResourceReservations'] = 'Rezervari de resurse';
        //

        // Day representations
        $strings['DaySundaySingle'] = 'D';
        $strings['DayMondaySingle'] = 'L';
        $strings['DayTuesdaySingle'] = 'M';
        $strings['DayWednesdaySingle'] = 'M';
        $strings['DayThursdaySingle'] = 'J';
        $strings['DayFridaySingle'] = 'V';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Dum';
        $strings['DayMondayAbbr'] = 'Lun';
        $strings['DayTuesdayAbbr'] = 'Mar';
        $strings['DayWednesdayAbbr'] = 'Mie';
        $strings['DayThursdayAbbr'] = 'Joi';
        $strings['DayFridayAbbr'] = 'Vin';
        $strings['DaySaturdayAbbr'] = 'Sam';

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Rezervarea ta\ a fost aprobata';
        $strings['ReservationCreatedSubject'] = 'Rezervarea ta\ a fost creata';
        $strings['ReservationUpdatedSubject'] = 'Rezervarea ta\ a fost actualizata';
        $strings['ReservationCreatedAdminSubject'] = 'Notifica: una prenotazione e\' stata creata';
        $strings['ReservationUpdatedAdminSubject'] = 'Notifica: una prenotazione e\' stata Reimprospateazata';
        $strings['ParticipantAddedSubject'] = 'Notifica participarea la o activitate rezervata';
        $strings['InviteeAddedSubject'] = 'Invito a prenotazione';
        $strings['ResetPassword'] = 'Cerere de resetare parola';
        $strings['ForgotPasswordEmailSent'] = 'Un email\' a fost transmis\'in care sunt furnizate instructiuni pentru resetarea parolei';
        //End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nu aveți rezervări anterioare';
        $strings['PastReservations'] = 'Rezervări Anterioare';
        $strings['AllNoPastReservations'] = 'Nu există rezervări anterioare în ultimele %s zile';
        $strings['AllPastReservations'] = 'Toate Rezervările Anterioare';
        $strings['Yesterday'] = 'Ieri';
        $strings['EarlierThisWeek'] = 'Mai devreme în această săptămână';
        $strings['PreviousWeek'] = 'Săptămâna trecută';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Grupul tău nu are rezervări viitoare';
        $strings['GroupUpcomingReservations'] = 'Viitoarele rezervări ale grupului/meu(ă)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'A apărut o eroare la autentificarea cu Facebook. Vă rugăm să încercați din nou.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nu aveți rezervări în așteptare de aprobare';
        $strings['PendingApprovalReservations'] = 'Rezervări în așteptare de aprobare';
        $strings['LaterThisMonth'] = 'Mai târziu în acest lună';
        $strings['LaterThisYear'] = 'Mai târziu în acest an';
        $strings['Remaining'] = 'Rămase';        
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Nu există rezervări de ieșire lipsă';
        $strings['MissingCheckOutReservations'] = 'Rezervări de ieșire lipsă';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Nu puteți vedea detaliile rezervării deoarece nu aveți permisiuni pentru niciunul dintre resursele din această rezervare';
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
        $days['full'] = ['Duminica', 'Luni', 'Marti', 'Miercuri', 'Joi', 'Vineri', 'Sambata'];
        // The three letter abbreviation
        $days['abbr'] = ['Du', 'Lu', 'Ma', 'Mi', 'Jo', 'Vi', 'Sa'];
        // The two letter abbreviation
        $days['two'] = ['Du', 'Lu', 'Ma', 'Mi', 'Jo', 'Vi', 'Sa'];
        // The one letter abbreviation
        $days['letter'] = ['D', 'L', 'M', 'M', 'J', 'V', 'S'];

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
        $months['full'] = ['Ianuarie', 'Februarie', 'martie', 'Aprilie', 'Mai', 'Iunie', 'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'];
        // The three letter month name
        $months['abbr'] = ['Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun', 'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
        return 'ro';
    }
}
