<?php

require_once('Language.php');
require_once('en_us.php');

class it_it extends en_us
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
        $dates = parent::_LoadDates();

        setlocale(LC_ALL, 'it_IT.UTF-8');

        // change defaults here
        $dates['general_date'] = 'd/m/Y';
        $dates['general_datetime'] = 'd/m/Y H:i:s';
        $dates['schedule_daily'] = 'l, d/m/Y';
        $dates['reservation_email'] = 'd/m/Y @ H:i (e)';
        $dates['res_popup'] = 'd/m/Y H:i';
        $dates['dashboard'] = 'l, d/m/Y H:i';
        $dates['period_time'] = 'H:i';
        $dates['general_date_js'] = 'dd/mm/yy';
        $dates['short_datetime'] = 'j/n/y H:i';
        $dates['schedule_daily'] = 'l, d/m/Y';
        $dates['res_popup_time'] = 'D, d/n H:i';
        $dates['short_reservation_date'] = 'j/n/y H:i';
        $dates['mobile_reservation_date'] = 'j/n H:i';
        $dates['general_time_js'] = 'H:mm';
        $dates['momentjs_datetime'] = 'D/M/YY H:mm';
        $dates['calendar_time'] = 'H:mm';
        $dates['calendar_dates'] = 'd ' . (preg_replace('/(.)(.)(.)/i', '\$1\$2\$3', date("M")));
        $dates['report_date'] = '%d/%m';

        $this->Dates = $dates;
        return $this->Dates;
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Nome';
        $strings['LastName'] = 'Cognome';
        $strings['Timezone'] = 'Fuso orario';
        $strings['Edit'] = 'Modifica';
        $strings['Change'] = 'Cambia';
        $strings['Rename'] = 'Rinomina';
        $strings['Remove'] = 'Rimuovi';
        $strings['Delete'] = 'Elimina';
        $strings['Update'] = 'Aggiorna';
        $strings['Cancel'] = 'Annulla';
        $strings['Add'] = 'Aggiungi';
        $strings['Name'] = 'Nome';
        $strings['Yes'] = 'S&igrave;';
        $strings['No'] = 'No';
        $strings['FirstNameRequired'] = 'Nome &egrave; obbligatorio.';
        $strings['LastNameRequired'] = 'Cognome &egrave; obbligatorio.';
        $strings['PwMustMatch'] = 'La password di conferma non corrisponde con la password inserita.';
        $strings['ValidEmailRequired'] = '&Egrave; richiesto un indirizzo email valido.';
        $strings['UniqueEmailRequired'] = 'Questo indirizzo email è già registrato.';
        $strings['UniqueUsernameRequired'] = 'Questo nome utente è già registrato.';
        $strings['UserNameRequired'] = 'Nome utente &egrave; obbligatorio.';
        $strings['CaptchaMustMatch'] = 'Inserire le lettere dall&apos;immagine di sicurezza esattamente come mostrato.';
        $strings['Today'] = 'Oggi';
        $strings['Week'] = 'Settimana';
        $strings['Month'] = 'Mese';
        $strings['BackToCalendar'] = 'Torna al calendario';
        $strings['BeginDate'] = 'Inizio';
        $strings['EndDate'] = 'Fine';
        $strings['Username'] = 'Nome utente';
        $strings['Password'] = 'Password';
        $strings['PasswordConfirmation'] = 'Conferma password';
        $strings['DefaultPage'] = 'Homepage predefinita';
        $strings['MyCalendar'] = 'Calendario personale';
        $strings['ScheduleCalendar'] = 'Calendario';
        $strings['Registration'] = 'Registrazione';
        $strings['NoAnnouncements'] = 'Non ci sono avvisi';
        $strings['Announcements'] = 'Avvisi';
        $strings['NoUpcomingReservations'] = 'Non hai prenotazioni imminenti';
        $strings['UpcomingReservations'] = 'Le tue prenotazioni imminenti';
        $strings['AllNoUpcomingReservations'] = 'Non ci sono prenotazioni imminenti';
        $strings['AllUpcomingReservations'] = 'Tutte le prenotazioni imminenti';
        $strings['ShowHide'] = 'Mostra/Nascondi';
        $strings['Error'] = 'Errore';
        $strings['ReturnToPreviousPage'] = 'Torna all&apos;ultima pagina sulla quale ti trovavi';
        $strings['UnknownError'] = 'Errore Sconosciuto';
        $strings['InsufficientPermissionsError'] = 'Non hai i permessi per accedere a questa risorsa';
        $strings['MissingReservationResourceError'] = 'Non &egrave; stata selezionata una risorsa';
        $strings['MissingReservationScheduleError'] = 'Non &egrave; stato selezionato un calendario';
        $strings['DoesNotRepeat'] = 'Non ripetere';
        $strings['Daily'] = 'Giornaliero';
        $strings['Weekly'] = 'Settimanale';
        $strings['Monthly'] = 'Mensile';
        $strings['Yearly'] = 'Annuale';
        $strings['RepeatPrompt'] = 'Ripeti';
        $strings['hours'] = 'ore';
        $strings['days'] = 'giorni';
        $strings['weeks'] = 'settimane';
        $strings['months'] = 'mesi';
        $strings['years'] = 'anni';
        $strings['day'] = 'giorno';
        $strings['week'] = 'settimana';
        $strings['month'] = 'mese';
        $strings['year'] = 'anno';
        $strings['repeatDayOfMonth'] = 'giorno del mese';
        $strings['repeatDayOfWeek'] = 'giorno della settimana';
        $strings['RepeatUntilPrompt'] = 'Fino';
        $strings['RepeatEveryPrompt'] = 'Ogni';
        $strings['RepeatDaysPrompt'] = 'il';
        $strings['CreateReservationHeading'] = 'Crea una nuova prenotazione';
        $strings['EditReservationHeading'] = 'Modifica prenotazione %s';
        $strings['ViewReservationHeading'] = 'Visualizza prenotazione %s';
        $strings['ReservationErrors'] = 'Modifica la prenotazione';
        $strings['Create'] = 'Crea';
        $strings['ThisInstance'] = 'Solo questa ripetizione';
        $strings['AllInstances'] = 'Tutte le ripetizioni';
        $strings['FutureInstances'] = 'Ripetizioni future';
        $strings['Print'] = 'Stampa';
        $strings['ShowHideNavigation'] = 'Mostra/Nascondi navigazione';
        $strings['ReferenceNumber'] = 'Numero riferimento';
        $strings['Tomorrow'] = 'Domani';
        $strings['LaterThisWeek'] = 'Questo fine settimana';
        $strings['NextWeek'] = 'Prossima settimana';
        $strings['SignOut'] = 'Disconnetti';
        $strings['LayoutDescription'] = 'Inizia di %s, mostrando %s giorni alla volta';
        $strings['AllResources'] = 'Tutte le risorse';
        $strings['TakeOffline'] = 'Non in linea';
        $strings['BringOnline'] = 'In linea';
        $strings['AddImage'] = 'Aggiungi immagine';
        $strings['NoImage'] = 'Nessuna immagine assegnata';
        $strings['Move'] = 'Muovi';
        $strings['AppearsOn'] = 'Appare su %s';
        $strings['Location'] = 'Posizione';
        $strings['NoLocationLabel'] = '(nessuna posizione impostata)';
        $strings['Contact'] = 'Contatto';
        $strings['NoContactLabel'] = '(nessuna informazione di contatto)';
        $strings['Description'] = 'Descrizione';
        $strings['NoDescriptionLabel'] = '(nessuna descrizione)';
        $strings['Notes'] = 'Note';
        $strings['NoNotesLabel'] = '(nessuna nota)';
        $strings['NoTitleLabel'] = '(nessun titolo)';
        $strings['UsageConfiguration'] = 'Configurazione utilizzo';
        $strings['ChangeConfiguration'] = 'Cambia configurazione';
        $strings['ResourceMinLength'] = 'Le prenotazioni devono durare almeno %s';
        $strings['ResourceMinLengthNone'] = 'Non &egrave; prevista una durata minima della prenotazione';
        $strings['ResourceMaxLength'] = 'Le prenotazioni non possono durare pi&ugrave; di %s';
        $strings['ResourceMaxLengthNone'] = 'Non &egrave; prevista una durata massima della prenotazione';
        $strings['ResourceRequiresApproval'] = 'Le prenotazioni devono essere approvate';
        $strings['ResourceRequiresApprovalNone'] = 'Le prenotazioni non richiedono approvazione';
        $strings['ResourcePermissionAutoGranted'] = 'Disponibilit&agrave; automatica immediata';
        $strings['ResourcePermissionNotAutoGranted'] = 'Disponibilit&agrave; non concessa automaticamente';
        $strings['ResourceMinNotice'] = 'Le prenotazioni devono essere effettuate almeno %s prima dell&apos;inizio';
        $strings['ResourceMinNoticeNone'] = 'Le prenotazioni possono essere effettuate fino al momento attuale';
        $strings['ResourceMinNoticeUpdate'] = 'Le prenotazioni devono essere aggiornate almeno %s prima dell&apos;inizio';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Le prenotazioni possono essere aggiornate fino al momento attuale';
        $strings['ResourceMinNoticeDelete'] = 'Le prenotazioni devono essere cancellate almeno %s prima dell&apos;inizio';
        $strings['ResourceMinNoticeNoneDelete'] = 'Le prenotazioni possono essere cancellate fino al momento attuale';
        $strings['ResourceMaxNotice'] = 'Le prenotazioni non devono terminare pi&ugrave; %s da ora';
        $strings['ResourceMaxNoticeNone'] = 'Le prenotazioni possono terminare in qualsiasi momento nel futuro';
        $strings['ResourceBufferTime'] = 'Ci devono essere %s tra le prenotazioni';
        $strings['ResourceBufferTimeNone'] = 'Non ci sono vincoli temporali tra le prenotazioni';
        $strings['ResourceAllowMultiDay'] = 'Le prenotazioni possono essere effettuate su pi&ugrave; giorni';
        $strings['ResourceNotAllowMultiDay'] = 'Le prenotazioni non possono essere effettuate su pi&ugrave; giorni';
        $strings['ResourceCapacity'] = 'Questa risorsa ha una capacit&agrave; di %s persone';
        $strings['ResourceCapacityNone'] = 'Questa risorsa ha capacit&agrave; illimitata';
        $strings['AddNewResource'] = 'Aggiungi nuova risorsa';
        $strings['AddNewUser'] = 'Aggiungi nuovo utente';
        $strings['AddResource'] = 'Aggiungi risorsa';
        $strings['Capacity'] = 'Capacit&agrave;';
        $strings['Access'] = 'Accesso';
        $strings['Duration'] = 'Durata';
        $strings['Active'] = 'Attivo';
        $strings['Inactive'] = 'Inattivo';
        $strings['ResetPassword'] = 'Reimposta password';
        $strings['LastLogin'] = 'Ultimo accesso';
        $strings['Search'] = 'Cerca';
        $strings['ResourcePermissions'] = 'Permessi risorsa';
        $strings['Reservations'] = 'Prenotazioni';
        $strings['Groups'] = 'Gruppi';
        $strings['Users'] = 'Utenti';
        $strings['AllUsers'] = 'Tutti gli utenti';
        $strings['AllGroups'] = 'Tutti i gruppi';
        $strings['AllSchedules'] = 'Tutti i calendari';
        $strings['UsernameOrEmail'] = 'Nome utente oppure email';
        $strings['Members'] = 'Componenti';
        $strings['QuickSlotCreation'] = 'Crea fasce orarie ogni %s minuti tra %s e %s';
        $strings['ApplyUpdatesTo'] = 'Applica aggiornamenti a';
        $strings['CancelParticipation'] = 'Annulla partecipazione';
        $strings['Attending'] = 'Partecipa';
        $strings['QuotaConfiguration'] = 'In %s per %s utenti in %s sono limitati a %s %s per %s';
        $strings['QuotaEnforcement'] = 'Rinforzo %s %s';
        $strings['reservations'] = 'prenotazioni';
        $strings['reservation'] = 'prenotazione';
        $strings['ChangeCalendar'] = 'Cambia calendario';
        $strings['AddQuota'] = 'Aggiungi quota';
        $strings['FindUser'] = 'Cerca utente';
        $strings['Created'] = 'Creato';
        $strings['LastModified'] = 'Ultima modifica';
        $strings['GroupName'] = 'Nome gruppo';
        $strings['GroupMembers'] = 'Componenti gruppo';
        $strings['GroupRoles'] = 'Ruoli gruppo';
        $strings['GroupAdmin'] = 'Amministratore gruppo';
        $strings['Actions'] = 'Azioni';
        $strings['CurrentPassword'] = 'Password attuale';
        $strings['NewPassword'] = 'Nuova password';
        $strings['InvalidPassword'] = 'La password attuale &egrave; errata';
        $strings['PasswordChangedSuccessfully'] = 'Password modificata correttamente';
        $strings['SignedInAs'] = 'Collegato come';
        $strings['NotSignedIn'] = 'Non sei collegato';
        $strings['ReservationTitle'] = 'Titolo';
        $strings['ReservationDescription'] = 'Descrizione';
        $strings['ResourceList'] = 'Lista risorse:';
        $strings['Accessories'] = 'Accessori';
        $strings['InvitationList'] = 'Inviti';
        $strings['AccessoryName'] = 'Nome accessorio';
        $strings['QuantityAvailable'] = 'Quantit&agrave; disponibile';
        $strings['Resources'] = 'Risorse';
        $strings['Participants'] = 'Partecipanti';
        $strings['User'] = 'Utente';
        $strings['Resource'] = 'Risorsa';
        $strings['Status'] = 'Stato';
        $strings['Approve'] = 'Approva';
        $strings['Page'] = 'Pagina';
        $strings['Rows'] = 'Righe';
        $strings['Unlimited'] = 'Illimitata';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Indirizzo email';
        $strings['Phone'] = 'Telefono';
        $strings['Organization'] = 'Dipartimento';
        $strings['Position'] = 'Posizione';
        $strings['Language'] = 'Lingua';
        $strings['Permissions'] = 'Permessi';
        $strings['Reset'] = 'Azzera';
        $strings['FindGroup'] = 'Cerca gruppo';
        $strings['Manage'] = 'Gestisci';
        $strings['None'] = 'Nessuno';
        $strings['AddToOutlook'] = 'Aggiungi ad Outlook';
        $strings['Done'] = 'Fatto';
        $strings['RememberMe'] = 'Ricordami';
        $strings['FirstTimeUser?'] = 'Nuovo utente?';
        $strings['CreateAnAccount'] = 'Crea un account';
        $strings['ViewSchedule'] = 'Visualizza calendario';
        $strings['ForgotMyPassword'] = 'Password dimenticata';
        $strings['YouWillBeEmailedANewPassword'] = 'Ti verr&agrave; inviata una password generata in modo casuale';
        $strings['Close'] = 'Chiudi';
        $strings['ExportToCSV'] = 'Esporta in CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Elaborazione in corso...';
        $strings['Login'] = 'Accesso';
        $strings['AdditionalInformation'] = 'Informazioni aggiuntive';
        $strings['AllFieldsAreRequired'] = 'tutti i campi sono obbligatori';
        $strings['Optional'] = 'opzionale';
        $strings['YourProfileWasUpdated'] = 'Il tuo profilo &egrave; stato aggiornato';
        $strings['YourSettingsWereUpdated'] = 'Le tue impostazioni sono state aggiornate';
        $strings['Register'] = 'Registra';
        $strings['SecurityCode'] = 'Codice sicurezza';
        $strings['ReservationCreatedPreference'] = 'Quando creo una prenotazione o una prenotazione viene creata a mio nome';
        $strings['ReservationUpdatedPreference'] = 'Quando modifico una prenotazione o una prenotazione a mio nome viene modificata';
        $strings['ReservationDeletedPreference'] = 'Quando elimino una prenotazione o una prenotazione a mio nome viene eliminata';
        $strings['ReservationApprovalPreference'] = 'Quando la mia prenotazione in sospeso viene approvata';
        $strings['PreferenceSendEmail'] = 'Mandami una email';
        $strings['PreferenceNoEmail'] = 'Non avvisarmi';
        $strings['ReservationCreated'] = 'La tua richiesta &egrave; stata inoltrata';
        $strings['ReservationUpdated'] = 'La tua richiesta &egrave; stata aggiornata';
        $strings['ReservationRemoved'] = 'La tua richiesta &egrave; stata rimossa';
        $strings['ReservationRequiresApproval'] = 'Una o pi&ugrave; risorse prenotate richiedono approvazione. La prenotazione rimarr&agrave; fino ad allora in sospeso.';
        $strings['YourReferenceNumber'] = 'Il tuo numero di riferimento &egrave; %s';
        $strings['ChangeUser'] = 'Cambia utente';
        $strings['MoreResources'] = 'Altre risorse';
        $strings['ReservationLength'] = 'Lunghezza prenotazione';
        $strings['ParticipantList'] = 'Lista partecipanti';
        $strings['AddParticipants'] = 'Aggiungi partecipanti';
        $strings['InviteOthers'] = 'Invita altri';
        $strings['AddResources'] = 'Aggiungi risorse';
        $strings['AddAccessories'] = 'Aggiungi accessori';
        $strings['Accessory'] = 'Accessori';
        $strings['QuantityRequested'] = 'Quantit&agrave; richiesta';
        $strings['CreatingReservation'] = 'Creazione della richiesta di prenotazione';
        $strings['UpdatingReservation'] = 'Aggiornamento prenotazione';
        $strings['DeleteWarning'] = 'Questa azione &egrave; permanente e non recuperabile!';
        $strings['DeleteAccessoryWarning'] = 'Eliminando questo accessorio, verr&agrave; rimosso da tutte le prenotazioni.';
        $strings['AddAccessory'] = 'Aggiungi accessorio';
        $strings['AddBlackout'] = 'Aggiungi Fuori-servizio';
        $strings['AllResourcesOn'] = 'Tutte le risorse attive';
        $strings['Reason'] = 'Motivo';
        $strings['BlackoutShowMe'] = 'Mostra prenotazioni in conflitto';
        $strings['BlackoutDeleteConflicts'] = 'Elimina le prenotazioni in conflitto';
        $strings['Filter'] = 'Filtra';
        $strings['Between'] = 'Tra';
        $strings['CreatedBy'] = 'Creato da';
        $strings['BlackoutCreated'] = 'Fuori-servizio creato';
        $strings['BlackoutNotCreated'] = 'Impossibile creare il fuori-servizio';
        $strings['BlackoutUpdated'] = 'Fuori-servizio aggiornato';
        $strings['BlackoutNotUpdated'] = 'Impossibile aggiornare il fuori-servizio';
        $strings['BlackoutConflicts'] = 'Ci sono fuori-servizio in conflitto';
        $strings['ReservationConflicts'] = 'Ci sono prenotazioni in conflitto';
        $strings['UsersInGroup'] = 'Utenti in questo gruppo';
        $strings['Browse'] = 'Sfoglia';
        $strings['DeleteGroupWarning'] = 'Eliminando questo gruppo rimuoverai tutti i permessi associati. Gli utenti in questo gruppo possono perdere l&apos;accesso alle risorse.';
        $strings['WhatRolesApplyToThisGroup'] = 'Quale ruolo applico a questo gruppo?';
        $strings['WhoCanManageThisGroup'] = 'Chi pu&ograve; gestire questo gruppo?';
        $strings['WhoCanManageThisSchedule'] = 'Chi pu&ograve; gestire questo calendario?';
        $strings['AllQuotas'] = 'Tutte le quote';
        $strings['QuotaReminder'] = 'Ricorda: le quote vengono applicate in base al fuso orario.';
        $strings['AllReservations'] = 'Tutti le prenotazioni';
        $strings['PendingReservations'] = 'Prenotazioni in sospeso';
        $strings['Approving'] = 'Approvazione';
        $strings['MoveToSchedule'] = 'Sposta nel calendario';
        $strings['DeleteResourceWarning'] = 'Eliminando questa risorsa cancellerai tutti i dati, incluso';
        $strings['DeleteResourceWarningReservations'] = 'Tutte le prenotazioni passate, correnti e future associate ad essa';
        $strings['DeleteResourceWarningPermissions'] = 'Assegnazione di tutti i permessi';
        $strings['DeleteResourceWarningReassign'] = 'Riassegna tutto ci&ograve; che non vuoi venga eliminato prima di procedere';
        $strings['ScheduleLayout'] = 'Layout (fuso orario %s)';
        $strings['ReservableTimeSlots'] = 'Fasce orarie assegnabili';
        $strings['BlockedTimeSlots'] = 'Fasce orarie bloccate';
        $strings['ThisIsTheDefaultSchedule'] = 'Questo &egrave; il calendario predefinito';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Il calendario predefinito non pu&ograve; essere eliminato';
        $strings['MakeDefault'] = 'Imposta come predefinito';
        $strings['BringDown'] = 'Porta Gi&ugrave;';
        $strings['ChangeLayout'] = 'Cambia layout';
        $strings['AddSchedule'] = 'Aggiungi calendario';
        $strings['StartsOn'] = 'Inizia di';
        $strings['NumberOfDaysVisible'] = 'Numero di giorni visibili';
        $strings['UseSameLayoutAs'] = 'Usa lo stesso layout di';
        $strings['Format'] = 'Formato';
        $strings['OptionalLabel'] = 'Etichetta opzionale';
        $strings['LayoutInstructions'] = 'Inserisci una fascia oraria per riga. L&apos;insieme delle fasce orarie deve coprire tutte le 24 ore del giorno iniziando e finendo alle 00:00.';
        $strings['AddUser'] = 'Aggiungi utente';
        $strings['UserPermissionInfo'] = 'L&apos;accesso attuale alla risorsa pu&ograve; essere diverso a seconda del ruolo, permessi di gruppo, o impostazioni di permessi esterni';
        $strings['DeleteUserWarning'] = 'Eliminando questo utente verranno rimosse tutte le sue prenotazioni passate, presenti e future.';
        $strings['AddAnnouncement'] = 'Aggiungi avviso';
        $strings['Announcement'] = 'Avviso';
        $strings['Priority'] = 'Priorit&agrave;';
        $strings['Reservable'] = 'Prenotabile';
        $strings['Unreservable'] = 'Non prenotabile';
        $strings['Reserved'] = 'Prenotato';
        $strings['MyReservation'] = 'Mie prenotazioni';
        $strings['Pending'] = 'In sospeso';
        $strings['Past'] = 'Passato';
        $strings['Restricted'] = 'Ristretto';
        $strings['ViewAll'] = 'Vedi tutte';
        $strings['MoveResourcesAndReservations'] = 'Sposta risorse e prenotazioni a';
        $strings['TurnOffSubscription'] = 'Disabilita la sottoscrizione';
        $strings['TurnOnSubscription'] = 'Abilita la sottoscrizione';
        $strings['SubscribeToCalendar'] = 'Apri una sottoscrizione';
        $strings['SubscriptionsAreDisabled'] = 'L&apos;amministratore ha disabilitato le sottoscrizioni';
        $strings['NoResourceAdministratorLabel'] = '(Nessun amministratore della risorsa)';
        $strings['WhoCanManageThisResource'] = 'Chi pu&ograve; gestire questa risorsa?';
        $strings['ResourceAdministrator'] = 'Amministratore risorsa';
        $strings['Private'] = 'Prenotato';
        $strings['Accept'] = 'Accetta';
        $strings['Decline'] = 'Rifiuta';
        $strings['ShowFullWeek'] = 'Mostra settimana intera';
        $strings['CustomAttributes'] = 'Attributi personalizzati';
        $strings['AddAttribute'] = 'Aggiungi un attributo';
        $strings['EditAttribute'] = 'Modifica un attributo';
        $strings['DisplayLabel'] = 'Etichetta visualizzata';
        $strings['Type'] = 'Tipo';
        $strings['Required'] = 'Obbligatorio';
        $strings['ValidationExpression'] = 'Espressione di convalida';
        $strings['PossibleValues'] = 'Valori possibili';
        $strings['SingleLineTextbox'] = 'Casella di testo singola';
        $strings['MultiLineTextbox'] = 'Casella di testo multipla';
        $strings['Checkbox'] = 'Casella di spunta';
        $strings['SelectList'] = 'Lista a tendina';
        $strings['CommaSeparated'] = 'separato da virgola';
        $strings['Category'] = 'Categoria';
        $strings['CategoryReservation'] = 'Categoria prenotazione';
        $strings['CategoryGroup'] = 'Categoria gruppo';
        $strings['SortOrder'] = 'Ordinamento';
        $strings['Title'] = 'Titolo';
        $strings['AdditionalAttributes'] = 'Attributi aggiuntivi';
        $strings['True'] = 'Vero';
        $strings['False'] = 'Falso';
        $strings['ForgotPasswordEmailSent'] = 'Una email &egrave; stata inviata all&apos;indirizzo fornito con le istruzioni per reimpostare la password';
        $strings['ActivationEmailSent'] = 'Riceverai presto una email di attivazione.';
        $strings['AccountActivationError'] = 'Spiacente, non puoi attivare il tuo account.';
        $strings['Attachments'] = 'Allegati';
        $strings['AttachFile'] = 'File allegato';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Nessun amministratore';
        $strings['ScheduleAdministrator'] = 'Amministratore calendario';
        $strings['Total'] = 'Totale';
        $strings['QuantityReserved'] = 'Quantit&agrave; assegnata';
        $strings['AllAccessories'] = 'Tutti gli accessori';
        $strings['GetReport'] = 'Crea report';
        $strings['NoResultsFound'] = 'Nessun risultato';
        $strings['SaveThisReport'] = 'Salva questo report';
        $strings['ReportSaved'] = 'Report salvato';
        $strings['EmailReport'] = 'Invia report';
        $strings['ReportSent'] = 'Report inviato';
        $strings['RunReport'] = 'Esegui report';
        $strings['NoSavedReports'] = 'Non hai report salvati';
        $strings['CurrentWeek'] = 'Settimana corrente';
        $strings['CurrentMonth'] = 'Mese corrente';
        $strings['AllTime'] = 'Tutti gli orari';
        $strings['FilterBy'] = 'Filtra per';
        $strings['Select'] = 'Seleziona';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Tempo totale';
        $strings['Count'] = 'Conteggio';
        $strings['Usage'] = 'Uso';
        $strings['AggregateBy'] = 'Aggregato per';
        $strings['Range'] = 'Intervallo';
        $strings['Choose'] = 'Scegli';
        $strings['All'] = 'Tutto';
        $strings['ViewAsChart'] = 'Visualizza come grafico';
        $strings['ReservedResources'] = 'Prenotazioni per risorsa';
        $strings['ReservedAccessories'] = 'Accessori assegnati';
        $strings['ResourceUsageTimeBooked'] = 'Copertura risorse - orario prenotazioni';
        $strings['ResourceUsageReservationCount'] = 'Copertura risorse - conteggio prenotazioni';
        $strings['Top20UsersTimeBooked'] = 'Top 20 utenti - orario prenotazioni';
        $strings['Top20UsersReservationCount'] = 'Top 20 utenti - conteggio prenotazioni';
        $strings['ConfigurationUpdated'] = 'Il file di configurazione &egrave; stato aggiornato';
        $strings['ConfigurationUiNotEnabled'] = 'Questa pagina non pu&ograve; essere acceduta perch&eacute; $conf[&apos;settings&apos;][&apos;pages&apos;][&apos;enable.configuration&apos;] &egrave; impostata a falso oppure &egrave; mancante.';
        $strings['ConfigurationFileNotWritable'] = 'Il file di configurazione non &egrave; scrivibile. Verifica i permessi del file e riprova.';
        $strings['ConfigurationUpdateHelp'] = 'Vedi la sezione Configurazione del <a target=_blank href=%s>Manuale</a> per ulteriori dettagli su queste impostazioni.';
        $strings['GeneralConfigSettings'] = 'impostazioni';
        $strings['UseSameLayoutForAllDays'] = 'Usa lo stesso layout per tutti i giorni';
        $strings['LayoutVariesByDay'] = 'Il layout &egrave; diverso per ogni giorno';
        $strings['ManageReminders'] = 'Promemoria';
        $strings['ReminderUser'] = 'User ID';
        $strings['ReminderMessage'] = 'Messaggio';
        $strings['ReminderAddress'] = 'Indirizzo';
        $strings['ReminderSendtime'] = 'Orario di invio';
        $strings['ReminderRefNumber'] = 'Numero di riferimento prenotazione';
        $strings['ReminderSendtimeDate'] = 'Giorno del promemoria';
        $strings['ReminderSendtimeTime'] = 'Ora del promemoria (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Aggiungi promemoria';
        $strings['DeleteReminderWarning'] = 'Sei sicuro?';
        $strings['NoReminders'] = 'Non ci sono promemoria imminenti.';
        $strings['Reminders'] = 'Promemoria';
        $strings['SendReminder'] = 'Invia promemoria';
        $strings['minutes'] = 'minuti';
        $strings['hours'] = 'ore';
        $strings['days'] = 'giorni';
        $strings['ReminderBeforeStart'] = 'prima dell&apos;orario di inizio';
        $strings['ReminderBeforeEnd'] = 'prima dell&apos;orario di fine';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'File CSS';
        $strings['ThemeUploadSuccess'] = 'Le modifiche sono state salvate. Aggiorna la pagina per rendere le modifiche effettive.';
        $strings['MakeDefaultSchedule'] = 'Rendi questo calendario quello predefinito';
        $strings['DefaultScheduleSet'] = 'Adesso questo calendario &egrave; quello predefinito';
        $strings['FlipSchedule'] = 'Rovescia visualizzazione prenotazioni';
        $strings['Next'] = 'Successivo';
        $strings['Success'] = 'Eseguito correttamente';
        $strings['Participant'] = 'Invitato';
        $strings['ResourceFilter'] = 'Filtro risorsa';
        $strings['ResourceGroups'] = 'Filtro gruppi';
        $strings['AddNewGroup'] = 'Aggiungi nuovo gruppo';
        $strings['Quit'] = 'Esci';
        $strings['AddGroup'] = 'Aggiungi gruppo';
        $strings['StandardScheduleDisplay'] = 'Visualizzazione standard';
        $strings['TallScheduleDisplay'] = 'Visualizzazione allungata';
        $strings['WideScheduleDisplay'] = 'Visualizzazione allargata';
        $strings['CondensedWeekScheduleDisplay'] = 'Visualizzazione settimana condensata';
        $strings['ResourceGroupHelp1'] = 'Trascina i gruppi di risorse per riorganizzare.';
        $strings['ResourceGroupHelp2'] = 'Fai clic con il pulsante destro sul nome del gruppo di risorse per ulteriori azioni.';
        $strings['ResourceGroupHelp3'] = 'Trascina le risorse per aggiungerle ai gruppi.';
        $strings['ResourceGroupWarning'] = 'Se usi i gruppi di risorse, ogni risorsa deve essere assegnata ad almeno un gruppo. Le risorse non assegnate non possono essere utilizzate nelle prenotazioni.';
        $strings['ResourceType'] = 'Tipo risorsa';
        $strings['AppliesTo'] = 'Si applica a';
        $strings['UniquePerInstance'] = 'Unico per istanza';
        $strings['AddResourceType'] = 'Aggiungi tipo risorsa';
        $strings['NoResourceTypeLabel'] = '(nessun tipo risorsa impostato)';
        $strings['ClearFilter'] = 'Pulisci filtro';
        $strings['MinimumCapacity'] = 'Capacit&agrave; minima';
        $strings['Color'] = 'Colore';
        $strings['Available'] = 'Disponibile';
        $strings['Unavailable'] = 'Non disponibile';
        $strings['Hidden'] = 'Nascosta';
        $strings['ResourceStatus'] = 'Stato risorsa';
        $strings['CurrentStatus'] = 'Stato attuale';
        $strings['AllReservationResources'] = 'Tutte le risorse delle prenotazioni';
        $strings['File'] = 'File';
        $strings['BulkResourceUpdate'] = 'Aggiornamento combinato risorsa';
        $strings['Unchanged'] = 'Non modificato';
        $strings['Common'] = 'Generale';
        $strings['AdminOnly'] = 'Solo per gli amministratori';
        $strings['AdvancedFilter'] = 'Filtro avanzato';
        $strings['MinimumQuantity'] = 'Minima quantit&agrave;';
        $strings['MaximumQuantity'] = 'Massima quantit&agrave;';
        $strings['ChangeLanguage'] = 'Cambia lingua';
        $strings['AddRule'] = 'Aggiungi ruolo';
        $strings['Attribute'] = 'Attributi';
        $strings['RequiredValue'] = 'Obbligatori';
        $strings['ReservationCustomRuleAdd'] = 'Se %s allora la prenotazione apparir&agrave; in';
        $strings['AddReservationColorRule'] = 'Aggiungi un colore di ruolo per la prenotazione';
        $strings['LimitAttributeScope'] = 'Limitata l&apos;attributo a...';
        $strings['CollectFor'] = 'Limitato a';
        $strings['SignIn'] = 'Iscriviti';
        $strings['AllParticipants'] = 'Tutti i partecipanti';
        $strings['RegisterANewAccount'] = 'Crea un nuovo profilo';
        $strings['Dates'] = 'Date';
        $strings['More'] = 'Altro';
        $strings['ResourceAvailability'] = 'Disponibilit&agrave; delle risorse';
        $strings['UnavailableAllDay'] = 'Indisponibile per tutto il giorno';
        $strings['AvailableUntil'] = 'Disponibile fino a';
        $strings['AvailableBeginningAt'] = 'Disponibile a partire da';
        $strings['AvailableAt'] = 'Disponibile da';
        $strings['AllResourceTypes'] = 'Tutti i tipi di risorsa';
        $strings['AllResourceStatuses'] = 'Qualunque stato';
        $strings['AllowParticipantsToJoin'] = 'Consenti ai partecipanti di unirsi';
        $strings['Join'] = 'Partecipa';
        $strings['YouAreAParticipant'] = 'Sei un partecipante a questa prenotazione';
        $strings['YouAreInvited'] = 'Sei invitato a questa prenotazione';
        $strings['YouCanJoinThisReservation'] = 'Puoi unirti a questa prenotazione';
        $strings['Import'] = 'Importa';
        $strings['GetTemplate'] = 'Genera il template';
        $strings['UserImportInstructions'] = '<ul><li>I file devono essere in formato CSV e con codifica UTF-8.</li><li>Nome utente ed email sono campi obbligatori.</li><li>Lasciando vuoti gli altri campi si erediteranno i valori di default e, come &apos;password&apos; la password dell&apos;utente.</li><li>Si usi il template fornito come esempio.</li></ul>';
        $strings['RowsImported'] = 'Righe importate';
        $strings['RowsSkipped'] = 'Righe scartate';
        $strings['Columns'] = 'Colonne';
        $strings['Reserve'] = 'Prenota';
        $strings['AllDay'] = 'Tutto il giorno';
        $strings['Everyday'] = 'Ogni giorno';
        $strings['IncludingCompletedReservations'] = 'incluse le prenotazioni gi&agrave; effettuate';
        $strings['NotCountingCompletedReservations'] = 'senza contare le prenotazioni gi&agrave; effettuate';
        $strings['RetrySkipConflicts'] = 'Trascura le prenotazioni che confliggono';
        $strings['Retry'] = 'Riprova';
        $strings['RemoveExistingPermissions'] = 'Elimino i permessi attuali?';
        $strings['Continue'] = 'Continua';
        $strings['WeNeedYourEmailAddress'] = 'L&apos;indirizzo di posta elettronica &egrave; necessario per prenotare';
        $strings['ResourceColor'] = 'Colore della risorsa';
        $strings['DateTime'] = 'Data e ora';
        $strings['AutoReleaseNotification'] = 'Cancellata automaticamente se non vi &egrave; un check-in entro %s minuti';
        $strings['RequiresCheckInNotification'] = 'Richiede il check-in/check-out';
        $strings['NoCheckInRequiredNotification'] = 'Non richiede il check-in/check-out';
        $strings['RequiresApproval'] = 'Richiede approvazione';
        $strings['CheckingIn'] = 'Facendo check-in';
        $strings['CheckingOut'] = 'Facendo check-out';
        $strings['CheckIn'] = 'Check In';
        $strings['CheckOut'] = 'Check Out';
        $strings['ReleasedIn'] = 'Cancellato il';
        $strings['CheckedInSuccess'] = 'Check-in effettuato';
        $strings['CheckedOutSuccess'] = 'Check-out effettuato';
        $strings['CheckInFailed'] = 'Processo di check-in fallito';
        $strings['CheckOutFailed'] = 'Processo di check-out fallito';
        $strings['CheckInTime'] = 'Ora del check-in';
        $strings['CheckOutTime'] = 'Ora del check-out';
        $strings['OriginalEndDate'] = 'Data originale di conclusione';
        $strings['SpecificDates'] = 'Mostra date specifiche';
        $strings['Users'] = 'Utenti';
        $strings['Guest'] = 'Ospite';
        $strings['ResourceDisplayPrompt'] = 'Risorse da mostrare';
        $strings['Credits'] = 'Crediti';
        $strings['AvailableCredits'] = 'Crediti disponibili';
        $strings['CreditUsagePerSlot'] = 'Richiede %s crediti per casella (fascia non di punta)';
        $strings['PeakCreditUsagePerSlot'] = 'Richiede %s crediti per casella (fascia di punta)';
        $strings['CreditsRule'] = 'Non hai sufficienti crediti. Crediti richiesti: %s. Crediti attuali: %s';
        $strings['PeakTimes'] = 'Ore di punta';
        $strings['AllYear'] = 'Tutto l&apos;anno';
        $strings['MoreOptions'] = 'Altre opzioni';
        $strings['SendAsEmail'] = 'Invia come email';
        $strings['UsersInGroups'] = 'Utenti nel gruppo';
        $strings['UsersWithAccessToResources'] = 'Utenti con accesso alle risorse';
        $strings['AnnouncementSubject'] = 'Una nuova comunicazione &egrave; stata pubblicata da %s';
        $strings['AnnouncementEmailNotice'] = 'la comunicazione sar&agrave; inviata agli utenti per posta elettronica';
        $strings['Day'] = 'Giorno';
        $strings['NotifyWhenAvailable'] = 'Avvisami quando disponibile';
        $strings['AddingToWaitlist'] = 'Sei stato aggiunto alla lista di attesa';
        $strings['WaitlistRequestAdded'] = 'Sarai avvisato se questo orario diventer&agrave; disponibile';
        $strings['PrintQRCode'] = 'Stampa il QR Code';
        $strings['FindATime'] = 'Trova una disponibilit&agrave;';
        $strings['AnyResource'] = 'Qualsiasi risorsa';
        $strings['ThisWeek'] = 'Questa settimana';
        $strings['Hours'] = 'Ore';
        $strings['Minutes'] = 'Minuti';
        $strings['ImportICS'] = 'Importa da ICS';
        $strings['ImportQuartzy'] = 'Importa da Quartzy';
        $strings['OnlyIcs'] = 'Solo file *.ics possono essere caricati.';
        $strings['IcsLocationsAsResources'] = 'I luoghi saranno importati come risorse.';
        $strings['IcsMissingOrganizer'] = 'Gli eventi privi dell&apos;organizzatore saranno assegnati all&apos;utente corrente.';
        $strings['IcsWarning'] = 'Le regole di prenotazione non saranno verificate. Potranno verificarsi conflitti e duplicazioni.';
        $strings['BlackoutAroundConflicts'] = 'Salta le prenotazioni in conflitto';
        $strings['DuplicateReservation'] = 'Duplicazione';
        $strings['UnavailableNow'] = 'Non disponibile adesso';
        $strings['ReserveLater'] = 'Prenota pi&ugrave; tardi';
        $strings['CollectedFor'] = 'Valido per';
        $strings['IncludeDeleted'] = 'Includi le prenotazioni cancellate';
        $strings['Deleted'] = 'Cancellato';
        $strings['Back'] = 'Indietro';
        $strings['Forward'] = 'Avanti';
        $strings['DateRange'] = 'Intervallo temporale';
        $strings['Copy'] = 'Copia';
        $strings['Detect'] = 'Rileva';
        $strings['Autofill'] = 'Autoriempimento';
        $strings['NameOrEmail'] = 'nome o email';
        $strings['ImportResources'] = 'Importa risorse';
        $strings['ExportResources'] = 'Esporta risorse';
        $strings['ResourceImportInstructions'] = '<ul><li>I file devono essere in formato CSV e con codifica UTF-8.</li><li>Il campo nome &egrave; obbligatorio. Lasciando vuoti gli altri campi si erediteranno i valori di default.</li><li>I possibili valori per lo Stato sono &apos;Disponibile&apos;, &apos;Non disponibile&apos; e &apos;Nascosta&apos;.</li><li>Colore deve essere il valore esadecimale. es) #ffffff.</li><li>Le colonne Assegna automaticamente e approva possono essere vero o falso.</li><li>La validit&agrave; degli attributi non verr&agrave; forzata.</li><li>I gruppi di risorsa multipli devono essere separati da virgola.</li><li>Si usi il template fornito come esempio.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>I file devono essere in formato CSV e con codifica UTF-8.</li><li>I campi email, nome risorsa, inizio e fine sono obbligatori.</li><li>I campi inizio e fine richiedono data e ora. Il formato raccomandato &egrave; YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Ruoli, conflitti, e fasce orarie non verranno controllati.</li><li>Le Notifiche non verranno inviate.</li><li>La validit&agrave; degli attributi non verr&agrave; forzata.</li><li>I nomi di risorsa multipli devono essere separati da virgola.</li><li>Si usi il template fornito come esempio.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Minuti per cancellazione automatica';
        $strings['CreditsPeak'] = 'Crediti (fascia di punta)';
        $strings['CreditsOffPeak'] = 'Crediti (fascia non di punta)';
        $strings['ResourceMinLengthCsv'] = 'Durata minima della prenotazione';
        $strings['ResourceMaxLengthCsv'] = 'Durata massima della prenotazione';
        $strings['ResourceBufferTimeCsv'] = 'Tempo di buffer';
        $strings['ResourceMinNoticeAddCsv'] = 'Tempo minimo aggiunta prenotazione';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Tempo minimo aggiornamento prenotazione';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Tempo minimo eliminazione prenotazione';
        $strings['ResourceMaxNoticeCsv'] = 'Tempo massimo termine prenotazione';
        $strings['Export'] = 'Esporta';
        $strings['DeleteMultipleUserWarning'] = 'Eliminando questi utenti eliminerai tutte le loro prenotazioni passate, presenti e future. Nessuna email verr&agrave; inviata.';
        $strings['DeleteMultipleReservationsWarning'] = 'Nessuna email verr&agrave; inviata.';
        $strings['ErrorMovingReservation'] = 'Errore spostando la prenotazione';
        $strings['SelectUser'] = 'Seleziona utente';
        $strings['InviteUsers'] = 'Invita utenti';
        $strings['InviteUsersLabel'] = 'Inserisci gli indirizzi email delle persone da invitare';
        $strings['ApplyToCurrentUsers'] = 'Applica agli utenti correnti';
        $strings['ReasonText'] = 'Motivo';
        $strings['NoAvailableMatchingTimes'] = 'Non ci sono fasce orarie disponibili per la ricerca che hai effettuato';
        $strings['Schedules'] = 'Calendari';
        $strings['NotifyUser'] = 'Notifica utente';
        $strings['UpdateUsersOnImport'] = 'Aggiorna utente esistente se l&apos;email esiste';
        $strings['UpdateResourcesOnImport'] = 'Aggiorna risorse esistenti se il nome esiste';
        $strings['Reject'] = 'Rifiuta';
        $strings['CheckingAvailability'] = 'Controlla disponibilit&agrave;';
        $strings['CreditPurchaseNotEnabled'] = 'Non sei abilitato a comprare crediti';
        $strings['CreditsCost'] = 'Ogni credito costa';
        $strings['Currency'] = 'Valuta';
        $strings['PayPalClientId'] = 'Client ID';
        $strings['PayPalSecret'] = 'Secret';
        $strings['PayPalEnvironment'] = 'Environment';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Live';
        $strings['StripePublishableKey'] = 'Publishable key';
        $strings['StripeSecretKey'] = 'Secret key';
        $strings['CreditsUpdated'] = 'Il costo di un credito &egrave; stato aggiornato';
        $strings['GatewaysUpdated'] = 'Sistema di pagamento aggiornato';
        $strings['PurchaseSummary'] = 'Riepilogo degli acquisti';
        $strings['EachCreditCosts'] = 'Ogni credito costa';
        $strings['Checkout'] = 'Alla cassa';
        $strings['Quantity'] = 'Quantit&agrave;';
        $strings['CreditPurchase'] = 'Acquisto crediti';
        $strings['EmptyCart'] = 'Il tuo carrello &egrave; vuoto.';
        $strings['BuyCredits'] = 'Compra crediti';
        $strings['CreditsPurchased'] = 'crediti acquistati.';
        $strings['ViewYourCredits'] = 'Vedi i tuoi crediti';
        $strings['TryAgain'] = 'Riprova';
        $strings['PurchaseFailed'] = 'Abbiamo riscontrato problemi durante l&apos;elaborazione del pagamento.';
        $strings['NoteCreditsPurchased'] = 'Crediti acquistati';
        $strings['CreditsUpdatedLog'] = 'Crediti aggiornati da %s';
        $strings['ReservationCreatedLog'] = 'Prenotazione creata. Numero Riferimento %s';
        $strings['ReservationUpdatedLog'] = 'Prenotazione aggiornata. Numero Riferimento %s';
        $strings['ReservationDeletedLog'] = 'Prenotazione eliminata. Numero Riferimento %s';
        $strings['BuyMoreCredits'] = 'Compra pi&ugrave; crediti';
        $strings['Transactions'] = 'Transazioni';
        $strings['Cost'] = 'Costo';
        $strings['PaymentGateways'] = 'Sistemi di pagamento';
        $strings['CreditHistory'] = 'Storico crediti';
        $strings['TransactionHistory'] = 'Storico transazioni';
        $strings['Date'] = 'Data';
        $strings['Note'] = 'Note';
        $strings['CreditsBefore'] = 'Crediti prima';
        $strings['CreditsAfter'] = 'Crediti dopo';
        $strings['TransactionFee'] = 'Tassa transazione';
        $strings['InvoiceNumber'] = 'Numero di fattura';
        $strings['TransactionId'] = 'ID della transazione';
        $strings['Gateway'] = 'Gateway';
        $strings['GatewayTransactionDate'] = 'Data transazione';
        $strings['Refund'] = 'Rimborso';
        $strings['IssueRefund'] = 'Emetti rimborso';
        $strings['RefundIssued'] = 'Rimborso effettuato con successo';
        $strings['RefundAmount'] = 'Importo rimborso';
        $strings['AmountRefunded'] = 'Rimborsato';
        $strings['FullyRefunded'] = 'Totalmente rimborsato';
        $strings['YourCredits'] = 'I tuoi crediti';
        $strings['PayWithCard'] = 'Paga con una carta';
        $strings['or'] = 'o';
        $strings['CreditsRequired'] = 'Crediti obbligatori';
        $strings['AddToGoogleCalendar'] = 'Aggiungi al calendario Google';
        $strings['Image'] = 'Immagine';
        $strings['ChooseOrDropFile'] = 'Scegli un file o trascinalo qui';
        $strings['SlackBookResource'] = 'Prenota %s ora';
        $strings['SlackBookNow'] = 'Prenota ora';
        $strings['SlackNotFound'] = 'Non abbiamo trovato una risorsa con questo nome. Prenota ora per iniziare una nuova prenotazione.';
        $strings['AutomaticallyAddToGroup'] = 'Aggiungi automaticamente i nuovi utenti a questo gruppo';
        $strings['GroupAutomaticallyAdd'] = 'Aggiungi automaticamente';
        $strings['TermsOfService'] = 'Termini di Servizio';
        $strings['EnterTermsManually'] = 'Aggiungi Termini di Servizio manualmente';
        $strings['LinkToTerms'] = 'Link ai Termini di Servizio';
        $strings['UploadTerms'] = 'Carica Termini di Servizio';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Richiedi l&apos;approvazione dei Termini di Servizio';
        $strings['UponReservation'] = 'Al momento della prenotazione';
        $strings['UponRegistration'] = 'Al momento della registrazione';
        $strings['ViewTerms'] = 'Vedi Termini di Servizio';
        $strings['IAccept'] = 'Accetto';
        $strings['TheTermsOfService'] = 'i Termini di Servizio';
        $strings['DisplayPage'] = 'Mostra pagina';
        $strings['AvailableAllYear'] = 'Tutto l&apos;anno';
        $strings['Availability'] = 'Disponibilit&agrave;';
        $strings['AvailableBetween'] = 'Disponibilit&agrave; Tra';
        $strings['ConcurrentYes'] = 'Le risorse possono essere prenotate da pi&ugrave; di una persona alla volta';
        $strings['ConcurrentNo'] = 'Le risorse non possono essere prenotate da pi&ugrave; di una persona alla volta';
        $strings['ScheduleAvailabilityEarly'] = 'Questo calendario non &egrave; ancora disponibile. Disponibile';
        $strings['ScheduleAvailabilityLate'] = 'Questo calendario non &egrave; più disponibile. Era disponibile';
        $strings['ResourceImages'] = 'Immagini della risorsa';
        $strings['FullAccess'] = 'Accesso completo';
        $strings['ViewOnly'] = 'Solo visualizzazione';
        $strings['Purge'] = 'Elimina';
        $strings['UsersWillBeDeleted'] = 'utenti saranno eliminati';
        $strings['BlackoutsWillBeDeleted'] = 'blackout times saranno eliminati';
        $strings['ReservationsWillBePurged'] = 'le prenotazioni saranno eliminate';
        $strings['ReservationsWillBeDeleted'] = 'le prenotazioni saranno eliminate';
        $strings['PermanentlyDeleteUsers'] = 'Elimina definitivamente gli utenti che non hanno effettuato l&apos;accesso da';
        $strings['DeleteBlackoutsBefore'] = 'Elimina blackout times prima di';
        $strings['DeletedReservations'] = 'Prenotazioni eliminate';
        $strings['DeleteReservationsBefore'] = 'Elimina le prenotazioni effettuate prima di';
        $strings['SwitchToACustomLayout'] = 'Passa ad un layout personalizzato';
        $strings['SwitchToAStandardLayout'] = 'Passa ad un layout standard';
        $strings['ThisScheduleUsesACustomLayout'] = 'Questo calendario usa un layout personalizzato';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Questo calendario usa un layout standard';
        $strings['SwitchLayoutWarning'] = 'Sei sicuro di volere cambiare il tipo di layout? Questo eliminer&agrave; tutti gli slot di tempo esistenti.';
        $strings['DeleteThisTimeSlot'] = 'Elimina questo slot di tempo?';
        $strings['Refresh'] = 'Aggiorna';
        $strings['ViewReservation'] = 'Vedi prenotazione';
        $strings['PublicId'] = 'ID pubblico';
        $strings['Public'] = 'Pubblico';
        $strings['AtomFeedTitle'] = '%s Prenotazioni';
        $strings['DefaultStyle'] = 'Stile di default';
        $strings['Standard'] = 'Standard';
        $strings['Wide'] = 'Largo';
        $strings['Tall'] = 'Alto';
        $strings['EmailTemplate'] = 'Template email';
        $strings['SelectEmailTemplate'] = 'Seleziona template email';
        $strings['ReloadOriginalContents'] = 'Ricarica email originale';
        $strings['UpdateEmailTemplateSuccess'] = 'Template email aggiornato';
        $strings['UpdateEmailTemplateFailure'] = 'Impossibile aggiornare il template email. Controlla che la cartella abbia gli accessi di scrittura.';
        $strings['BulkResourceDelete'] = 'Eliminazione di massa delle risorse';
        $strings['NewVersion'] = 'Nuova versione!';
        $strings['WhatsNew'] = 'Quali novit&agrave;?';
        $strings['OnlyViewedCalendar'] = 'Questo calendario &egrave; visibile solo dalla vista Calendari';
        $strings['Grid'] = 'Griglia';
        $strings['List'] = 'Lista';
        $strings['NoReservationsFound'] = 'Nessuna prenotazione trovata';
        $strings['EmailReservation'] = 'Manda la prenotazione tramite email';
        $strings['AdHocMeeting'] = 'Ad hoc Meeting';
        $strings['NextReservation'] = 'Prossima prenotazione';
        $strings['MissedCheckin'] = 'Mancato check-in';
        $strings['MissedCheckout'] = 'Mancato check-out';
        $strings['Utilization'] = 'Utilizzo';
        $strings['SpecificTime'] = 'Orario Esatto';
        $strings['ReservationSeriesEndingPreference'] = 'Quando le mie prenotazioni ricorrenti stanno per terminare';
        $strings['NotAttending'] = 'Non partecipare';
        $strings['ViewAvailability'] = 'Vedi disponibilit&agrave;';
        $strings['ReservationDetails'] = 'Dettagli della prenotazione';
        $strings['StartTime'] = 'Ora di inizio';
        $strings['EndTime'] = 'Ora di fine';
        $strings['New'] = 'Nuovo';
        $strings['Updated'] = 'Aggiornato';
        $strings['Custom'] = 'Personalizzato';
        $strings['AddDate'] = 'Aggiungi data';
        $strings['RepeatOn'] = 'Ripeti il';
        $strings['ScheduleConcurrentMaximum'] = 'Un totale di <b>%s</b> risorse possono essere prenotate in contemporanea';
        $strings['ScheduleConcurrentMaximumNone'] = 'Non c&apos;&egrave; limite al numero di risorse che si possono prenotare in contemporanea';
        $strings['ScheduleMaximumConcurrent'] = 'Massimo numero di risorse prenotate in contemporanea';
        $strings['ScheduleMaximumConcurrentNote'] = 'Quando popolato, il numero totale di risorse che si possono prenotare in contemporanea per questo calendario sar&agrave; limitato.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Ogni prenotazione &egrave; limitata ad un massimo di <b>%s</b> risorse';
        $strings['ScheduleResourcesPerReservationNone'] = 'Non c&apos;&egrave; limite al numero di risorse per prenotazione';
        $strings['ScheduleResourcesPerReservation'] = 'Massimo numero di risorse per prenotazione';
        $strings['ResourceConcurrentReservations'] = 'Permetti %s prenotazioni concorrenti';
        $strings['ResourceConcurrentReservationsNone'] = 'Non permettere prenotazioni concorrenti';
        $strings['AllowConcurrentReservations'] = 'Permetti prenotazioni concorrenti';
        $strings['ResourceDisplayInstructions'] = 'Nessuna risorsa selezionata. Puoi trovare l&apos;URL da visualizzare nel menu &apos;Gestione applicazione&apos; &gt; Risorse. La risorsa deve essere accessibile pubblicamente.';
        $strings['Owner'] = 'Proprietario';
        $strings['MaximumConcurrentReservations'] = 'Massimo numero prenotazioni concorrenti';
        $strings['NotifyUsers'] = 'Notifica utenti';
        $strings['Message'] = 'Messaggio';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Chiunque abbia una prenotazione nei prossimi';
        $strings['ChangeResourceStatus'] = 'Cambia stato risorsa';
        $strings['UpdateGroupsOnImport'] = 'Aggiorna gruppo esistente se il nome corrisponde';
        $strings['GroupsImportInstructions'] = '<ul><li>I file devono essere in formato CSV e con codifica UTF-8.</li><li>Il campo nome &egrave; obbligatorio.</li><li>La lista dei membri deve essere una lista di indirizzi email separata da virgole.</li><li>Una lista dei membri vuota non modificher&agrave; il gruppo.</li><li>La lista dei permessi deve essere una lista di risorse separata da virgole.</li><li>Una lista dei permessi vuota non modificher&agrave; i permessi del gruppo.</li><li>Si usi il template fornito come esempio.</li></ul>';
        $strings['PhoneRequired'] = 'Telefono richiesto';
        $strings['OrganizationRequired'] = 'Organizzazione richiesta';
        $strings['PositionRequired'] = 'Posizione richiesta';
        $strings['GroupMembership'] = 'Membri gruppo';
        $strings['AvailableGroups'] = 'Gruppi disponibili';
        $strings['CheckingAvailabilityError'] = 'Disponibilit&agrave; della risorsa non disponibile - troppe risorse';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Installa LibreBooking (solo MySQL)';
        $strings['IncorrectInstallPassword'] = 'Attenzione, la password non &egrave; corretta.';
        $strings['SetInstallPassword'] = '&Egrave; necessario impostare una password di installazione prima che l&apos;installazione possa essere eseguita.';
        $strings['InstallPasswordInstructions'] = 'In %s si consiglia di scegliere %s una password sicura e, quindi, di riprovare.<br />&Egrave; possibile usare %s';
        $strings['NoUpgradeNeeded'] = 'Non &egrave; necessario alcun aggiornamento. L&apos;installazione cancella tutti i dati esistenti e installa una nuova copia di LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Immettere la password di installazione.';
        $strings['InstallPasswordLocation'] = 'Quest&apos;ultima &egrave; definita nel file %s in %s.';
        $strings['VerifyInstallSettings'] = 'Verifica i seguenti parametri prima di continuare. Sono eventualmente modificabili da %s.';
        $strings['DatabaseName'] = 'Nome del database';
        $strings['DatabaseUser'] = 'Utente del database';
        $strings['DatabaseHost'] = 'Server del database';
        $strings['DatabaseCredentials'] = '&Egrave; necessario fornire le credenziali di un utente MySQL con privilegi sufficienti alla creazione del database. Se non si conoscono, contattare l&apos;amministratore del database. In molti casi, &apos;root&apos; funziona.';
        $strings['MySQLUser'] = 'Utente MySQL';
        $strings['InstallOptionsWarning'] = 'Le seguenti opzioni, probabilmente, non funzioneranno su un server remoto. In questo caso, utilizzare gli strumenti della procedura guidata per completare la procedura.';
        $strings['CreateDatabase'] = 'Crea il database';
        $strings['CreateDatabaseUser'] = 'Crea l&apos;utente MySQL con diritti sul database';
        $strings['PopulateExampleData'] = 'Importa dati di esempio. Crea un account per l&apos;amministratore: admin/password e un account per l&apos;utente: user/password';
        $strings['DataWipeWarning'] = 'Attenzione: Questo distrugger&agrave; ogni dato preesistente';
        $strings['RunInstallation'] = 'Avvia l&apos;installazione';
        $strings['UpgradeNotice'] = 'Si sta aggiornando dalla versione <b>%s</b> alla versione <b>%s</b>';
        $strings['RunUpgrade'] = 'Avvia l&apos;aggiornamento';
        $strings['Executing'] = 'Esecuzione in corso';
        $strings['StatementFailed'] = 'Errore. Dettagli:';
        $strings['SQLStatement'] = 'Query SQL:';
        $strings['ErrorCode'] = 'Codice di errore:';
        $strings['ErrorText'] = 'Messaggio di errore:';
        $strings['InstallationSuccess'] = 'Installazione eseguita con successo!';
        $strings['RegisterAdminUser'] = 'Accedi come amministratore. Questo &egrave; necessario se non sono stati importati dati di esempio. Assicurarsi che $conf[&apos;settings&apos;][&apos;allow.self.registration&apos;] = &apos;true&apos; nel file %s file.';
        $strings['LoginWithSampleAccounts'] = 'Se sono stati importati dati di esempio, &egrave; possibile accedere come amministratore con credenziali: admin/password o come utente con credenziali: user/password.';
        $strings['InstalledVersion'] = 'Si sta usando la versione %s di LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Si raccomanda di aggiornare il file config';
        $strings['InstallationFailure'] = 'Si sono verificati dei problemi durante l&apos;intallazione. Si prega di correggerli e di riprovare l&apos;installazione.';
        $strings['ConfigureApplication'] = 'Configura LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Il file config &egrave; aggiornato!';
        $strings['ConfigUpdateFailure'] = 'Non &egrave; possibile aggiornare automaticamente il file config. Si prega di sovrascrivere il contenuto corrente del file con il seguente:';
        $strings['SelectUser'] = 'Seleziona utente';
        $strings['InviteUsers'] = 'Invita altri utenti';
        $strings['InviteUsersLabel'] = 'Riporta l&apos;indirizzo di posta elettronica delle persone da invitare';
        $strings['ScriptUrlWarning'] = 'Il setting <em>script.url</em> potrebbe non essere corretto. Attualmente &egrave; <strong>%s</strong>, dovrebbe essere <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Nome utente o password errate';
        $strings['ReservationFailed'] = 'Impossibile creare la prenotazione';
        $strings['MinNoticeError'] = 'Questa prenotazione non &egrave; assegnabile in anticipo. La prima data che pu&ograve; essere assegnata &egrave; %s.';
        $strings['MinNoticeErrorUpdate'] = 'Cambiare questa prenotazione richiede un preavviso. Non &egrave; possibile modificare le prenotazioni prima di %s .';
        $strings['MinNoticeErrorDelete'] = 'Eliminare questa prenotazione richiede un preavviso. Non &egrave; possibile eliminare le prenotazioni prima di %s.';
        $strings['MaxNoticeError'] = 'Questa prenotazione non pu&ograve; essere creata fino a questa data nel futuro. L&apos;ultima data che pu&ograve; essere assegnata &egrave; %s.';
        $strings['MinDurationError'] = 'La prenotazione deve durare almeno %s.';
        $strings['MaxDurationError'] = 'Questa prenotazione non pu&ograve; durare pi&ugrave; di %s.';
        $strings['ConflictingAccessoryDates'] = 'Non ci sono abbastanza accessori:';
        $strings['NoResourcePermission'] = 'Non hai i permessi per accedere a una o pi&ugrave; risorse richieste';
        $strings['ConflictingReservationDates'] = 'Ci sono prenotazioni in conflitto nelle seguenti date:';
        $strings['InstancesOverlapRule'] = 'Qualche istanza della serie di prenotazioni si sovrappone:';
        $strings['StartDateBeforeEndDateRule'] = 'La data di inizio deve essere antecedente alla data di fine';
        $strings['StartIsInPast'] = 'La data di inizio non pu&ograve; essere nel passato';
        $strings['EmailDisabled'] = 'L&apos;amministratore ha disabilitato le notifiche via email';
        $strings['ValidLayoutRequired'] = 'L&apos;insieme delle fasce orarie deve coprire tutte le 24 ore del giorno iniziando e finendo alle 00:00.';
        $strings['CustomAttributeErrors'] = 'C&apos;&egrave; un problema con gli attributi aggiuntivi:';
        $strings['CustomAttributeRequired'] = '%s &egrave; un campo obbligatorio';
        $strings['CustomAttributeInvalid'] = 'Il valore fornito per %s non &egrave; valido';
        $strings['AttachmentLoadingError'] = 'Spiacente, c&apos;&egrave; stato un problema durante il caricamento del file richiesto.';
        $strings['InvalidAttachmentExtension'] = 'Puoi solo caricare file in %s';
        $strings['InvalidStartSlot'] = 'La data/ora di inizio non &egrave; valida.';
        $strings['InvalidEndSlot'] = 'La data/ora di fine non &egrave; valida.';
        $strings['MaxParticipantsError'] = '%s pu&ograve; contenere al massimo %s partecipanti.';
        $strings['ReservationCriticalError'] = 'Errore critico durante il salvataggio della prenotazione. Se il problema persiste contatta l&apos;amministratore dell&apos;applicazione.';
        $strings['InvalidStartReminderTime'] = 'L&apos;orario del promemoria di inizio prenotazione non &egrave; valido.';
        $strings['InvalidEndReminderTime'] = 'L&apos;orario del promemoria di fine prenotazione non &egrave; valido.';
        $strings['QuotaExceeded'] = 'Limite della quota superato.';
        $strings['MultiDayRule'] = '%s non permette prenotazioni su pi&ugrave; giorni.';
        $strings['InvalidReservationData'] = 'Ci sono stati problemi con la tua richiesta di prenotazione.';
        $strings['PasswordError'] = 'La password deve contenere almeno %s lettere ed %s numeri.';
        $strings['PasswordErrorRequirements'] = 'La password deve contenere una combinazione di almeno %s maiuscole e minuscole e %s numeri.';
        $strings['NoReservationAccess'] = 'Non sei abilitato a modificare questa prenotazione.';
        $strings['PasswordControlledExternallyError'] = 'La password &egrave; controllata da un sistema esterno e non pu&ograve; essere modificata localmente.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'L&apos;accessorio %s pu&ograve; essere richiesto solo con la risorsa %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Si devono richiedere almeno %s %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Non si possono richiedere pi&ugrave; di %s %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'L&apos;accessorio &apos;%s&apos; non pu&ograve; essere richiesto con la risorsa selezionata';
        $strings['NoResources'] = 'Nessuna risorsa &egrave; stata aggiunta.';
        $strings['ParticipationNotAllowed'] = 'Non puoi accedere a questa prenotazione.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Questa prenotazione non pu&ograve; essere inviata.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Questa prenotazione non pu&ograve; essere ritirata.';
        $strings['InvalidEmailDomain'] = 'Questo indirizzo di posta elettronica non appartiene ad un dominio consentito';
        $strings['TermsOfServiceError'] = 'Devi accettare i Termini di Servizio';
        $strings['UserNotFound'] = 'Utente non trovato';
        $strings['ScheduleAvailabilityError'] = 'Questo calendario &egrave; disponibile tra %s and %s';
        $strings['ReservationNotFoundError'] = 'Prenotazione non trovata';
        $strings['ReservationNotAvailable'] = 'Prenotazione non disponibile';
        $strings['TitleRequiredRule'] = 'Il titolo della prenotazione &egrave; obbligatorio';
        $strings['DescriptionRequiredRule'] = 'La descrizione della prenotazione &egrave; obbligatoria';
        $strings['WhatCanThisGroupManage'] = 'Cosa pu&ograve; gestire questo gruppo?';
        $strings['ReservationParticipationActivityPreference'] = 'Quando qualcuno si unisce o lascia la mia prenotazione';
        $strings['RegisteredAccountRequired'] = 'Solo gli utenti registrati possono effettuare prenotazioni';
        $strings['InvalidNumberOfResourcesError'] = 'Il numero massimo di risorse che possono essere prenotate in una singola prenotazione &egrave; %s';
        $strings['ScheduleTotalReservationsError'] = 'Questo calendario permette di prenotare solo %s risorse contemporaneamente. Questa prenotazione potrebbe violare questo limite nelle seguenti date:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Crea prenotazione';
        $strings['EditReservation'] = 'Modifica prenotazione';
        $strings['LogIn'] = 'Accedi';
        $strings['ManageReservations'] = 'Prenotazioni';
        $strings['AwaitingActivation'] = 'Attesa attivazione';
        $strings['PendingApproval'] = 'Approvazione in sospeso';
        $strings['ManageSchedules'] = 'Calendari';
        $strings['ManageResources'] = 'Risorse';
        $strings['ManageAccessories'] = 'Accessori';
        $strings['ManageUsers'] = 'Utenti';
        $strings['ManageGroups'] = 'Gruppi';
        $strings['ManageQuotas'] = 'Quote';
        $strings['ManageBlackouts'] = 'Fuori-servizio';
        $strings['MyDashboard'] = 'Cruscotto personale';
        $strings['ServerSettings'] = 'Impostazioni server';
        $strings['Dashboard'] = 'Cruscotto';
        $strings['Help'] = 'Aiuto';
        $strings['Administration'] = 'Amministrazione';
        $strings['About'] = 'Informazioni su';
        $strings['Bookings'] = 'Prenotazioni';
        $strings['Schedule'] = 'Calendario';
        $strings['Account'] = 'Account';
        $strings['EditProfile'] = 'Modifica il profilo';
        $strings['FindAnOpening'] = 'Trova una data disponibile';
        $strings['OpenInvitations'] = 'Inviti aperti';
        $strings['ResourceCalendar'] = 'Calendario risorse';
        $strings['Reservation'] = 'Nuova prenotazione';
        $strings['Install'] = 'Installazione';
        $strings['ChangePassword'] = 'Cambia password';
        $strings['MyAccount'] = 'Account personale';
        $strings['Profile'] = 'Profilo';
        $strings['ApplicationManagement'] = 'Gestione applicazione';
        $strings['ForgotPassword'] = 'Password dimenticata';
        $strings['NotificationPreferences'] = 'Preferenze di notifica';
        $strings['ManageAnnouncements'] = 'Avvisi';
        $strings['Responsibilities'] = 'Amministrazione';
        $strings['GroupReservations'] = 'Prenotazioni gruppo';
        $strings['ResourceReservations'] = 'Prenotazioni risorsa';
        $strings['Customization'] = 'Personalizzazione';
        $strings['Attributes'] = 'Attributi';
        $strings['AccountActivation'] = 'Attivazione account';
        $strings['ScheduleReservations'] = 'Calendario prenotazioni';
        $strings['Reports'] = 'Report';
        $strings['GenerateReport'] = 'Crea nuovo report';
        $strings['MySavedReports'] = 'I miei report salvati';
        $strings['CommonReports'] = 'Report comuni';
        $strings['ViewDay'] = 'Mostra giorno';
        $strings['Group'] = 'Gruppo';
        $strings['ManageConfiguration'] = 'Configurazione applicazione';
        $strings['LookAndFeel'] = 'Aspetto applicazione';
        $strings['ManageResourceGroups'] = 'Gruppi risorsa';
        $strings['ManageResourceTypes'] = 'Tipi risorsa';
        $strings['ManageResourceStatus'] = 'Stati risorsa';
        $strings['ReservationColors'] = 'Colori delle prenotazioni';
        $strings['SearchReservations'] = 'Cerca Prenotazioni';
        $strings['ManagePayments'] = 'Pagamenti';
        $strings['ViewCalendar'] = 'Visualizza calendario';
        $strings['DataCleanup'] = 'Pulizia dei dati';
        $strings['ManageEmailTemplates'] = 'Gestisci template email';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'D';
        $strings['DayMondaySingle'] = 'L';
        $strings['DayTuesdaySingle'] = 'M';
        $strings['DayWednesdaySingle'] = 'M';
        $strings['DayThursdaySingle'] = 'G';
        $strings['DayFridaySingle'] = 'V';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Dom';
        $strings['DayMondayAbbr'] = 'Lun';
        $strings['DayTuesdayAbbr'] = 'Mar';
        $strings['DayWednesdayAbbr'] = 'Mer';
        $strings['DayThursdayAbbr'] = 'Gio';
        $strings['DayFridayAbbr'] = 'Ven';
        $strings['DaySaturdayAbbr'] = 'Sab';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'La sua richiesta di prenotazione è stata approvata';
        $strings['ReservationCreatedSubject'] = 'La sua richiesta di prenotazione è stata inoltrata';
        $strings['ReservationUpdatedSubject'] = 'La sua richiesta di prenotazione è stata aggiornata';
        $strings['ReservationDeletedSubject'] = 'La sua richiesta di prenotazione è stata rimossa';

        $strings['ReservationCreatedAdminSubject'] = 'Notifica: Ha inoltrato una richiesta di prenotazione';
        $strings['ReservationUpdatedAdminSubject'] = 'Notifica: Una sua richiesta di prenotazione è stata aggiornata';
        $strings['ReservationDeleteAdminSubject'] = 'Notifica: Una sua richiesta di prenotazione è stata rimossa';
        $strings['ReservationApprovalAdminSubject'] = 'Notifica: Una sua richiesta di prenotazione è in attesa di approvazione';

        $strings['ParticipantAddedSubject'] = 'Notifica di partecipazione ad una prenotazione';
        $strings['ParticipantDeletedSubject'] = 'Annullata partecipazione ad una prenotazione';
        $strings['InviteeAddedSubject'] = 'Invito ad una prenotazione';
        $strings['ResetPasswordRequest'] = 'Richiesta di reimpostazione della password';
        $strings['ActivateYourAccount'] = 'Attivi il suo account';
        $strings['ReportSubject'] = 'Il report che ha richiesto (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Promemoria: prenotazione di %s';
        $strings['ReservationEndingSoonSubject'] = 'Promemoria: prenotazione di %s';
        $strings['UserAdded'] = 'Aggiunto nuovo utente';
        $strings['UserDeleted'] = 'Il profilo di %s è stato cancellato da %s';
        $strings['GuestAccountCreatedSubject'] = 'Dettagli del suo profilo';
        $strings['AccountCreatedSubject'] = 'Dettagli del suo account %s';
        $strings['InviteUserSubject'] = '%s ha invitato lei ad unirsi a %s';

        $strings['ReservationCreatedSubjectWithResource'] = 'Ha inoltrato una richiesta di prenotazione per %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Ha modificato una richiesta di prenotazione per %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Una sua richiesta di prenotazione per %s è stata eliminata';
        $strings['ReservationApprovedSubjectWithResource'] = 'Una sua richiesta di prenotazione per %s è stata approvata';

        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Notifica: Richiesta di prenotazione inoltrata per %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Notifica: Richiesta di prenotazione modificata per %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Notifica: Richiesta di prenotazione eliminata per %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Notifica: Richiesta di prenotazione per %s richiede la tua approvazione';

        $strings['ParticipantAddedSubjectWithResource'] = '%s ha aggiunto lei alla prenotazione per %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s ha eliminato la prenotazione per %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s ha invitato lei alla prenotazione per %s';
        $strings['ReservationShareSubject'] = '%s ha condiviso una prenotazione per %s';
        $strings['ReservationSeriesEndingSubject'] = 'La serie di prenotazioni per %s terminerà il %s';
        $strings['ReservationParticipantAccept'] = '%s ha accettato il suo invito alla prenotazione per %s il %s';
        $strings['ReservationParticipantDecline'] = '%s ha rifiutato il suo invito alla prenotazione per %s il %s';
        $strings['ReservationParticipantJoin'] = '%s si è unito alla sua prenotazione per %s il %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Non hai prenotazioni passate';
        $strings['PastReservations'] = 'Prenotazioni passate';
        $strings['AllNoPastReservations'] = 'Non ci sono prenotazioni passate nei precedenti %s giorni';
        $strings['AllPastReservations'] = 'Tutte le prenotazioni passate';
        $strings['Yesterday'] = 'Ieri';
        $strings['EarlierThisWeek'] = 'All\'inizio di questa settimana';
        $strings['PreviousWeek'] = 'Settimana precedente';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Il tuo gruppo non ha prenotazioni future';
        $strings['GroupUpcomingReservations'] = 'Prossime prenotazioni del mio(i) gruppo(i)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Si è verificato un errore durante l\'accesso con Facebook. Riprova.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Non hai prenotazioni in attesa di approvazione';
        $strings['PendingApprovalReservations'] = 'Prenotazioni in attesa di approvazione';
        $strings['LaterThisMonth'] = 'Più tardi questo mese';
        $strings['LaterThisYear'] = 'Più tardi quest\'anno';
        $strings['Remaining'] = 'Rimanenti';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Non ci sono prenotazioni mancanti per il check out';
        $strings['MissingCheckOutReservations'] = 'Prenotazioni mancanti per il check out';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Impossibile visualizzare i dettagli della prenotazione perché non hai le autorizzazioni per nessuna delle risorse in questa prenotazione';
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
        $days['full'] = ['Domenica', 'Luned&igrave;', 'Marted&igrave;', 'Mercoled&igrave;', 'Gioved&igrave;', 'Venerd&igrave;', 'Sabato'];
        // The three letter abbreviation
        $days['abbr'] = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
        // The two letter abbreviation
        $days['two'] = ['Do', 'Lu', 'Ma', 'Me', 'Gi', 'Ve', 'Sa'];
        // The one letter abbreviation
        $days['letter'] = ['D', 'L', 'M', 'M', 'G', 'V', 'S'];

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
        $months['full'] = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
        // The three letter month name
        $months['abbr'] = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];

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
        return 'it';
    }
}
