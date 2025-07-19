<?php

require_once('Language.php');
require_once('en_gb.php');

class ca extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Nom';
        $strings['LastName'] = 'Cognom';
        $strings['Timezone'] = 'Zona Horaria';
        $strings['Edit'] = 'Editar';
        $strings['Change'] = 'Canviar';
        $strings['Rename'] = 'Reanomenar';
        $strings['Remove'] = 'Eliminar';
        $strings['Delete'] = 'Esborrar';
        $strings['Update'] = 'Actualitzar';
        $strings['Cancel'] = 'Cancel&middot;lar';
        $strings['Add'] = 'Afegir';
        $strings['Name'] = 'Nom';
        $strings['Yes'] = 'S&iacute;';
        $strings['No'] = 'No';
        $strings['FirstNameRequired'] = 'Cal un Nom.';
        $strings['LastNameRequired'] = 'Cal un Cognom.';
        $strings['PwMustMatch'] = 'La contrasenya de confirmaci&oacute; ha de coincidir.';
        $strings['PwComplexity'] = 'La contrasenya ha de tenir com a m&iacute;nim 6 car&agrave;cters amb una convinaci&oacute; de lletres, n&uacute;meros i s&iacute;mbols.';
        $strings['ValidEmailRequired'] = 'Cal una direcci&oacute; v&agrave;lida de correu.';
        $strings['UniqueEmailRequired'] = 'Aquesta direcci&oacute;de correu ja est&agrave; registrada.';
        $strings['UniqueUsernameRequired'] = 'Aquest nom d\'usuari ja est&agrave; registrat.';
        $strings['UserNameRequired'] = 'Cal un nom d\'usuari.';
        $strings['CaptchaMustMatch'] = 'Si us plau, introdueix el codi de seguretat tal i com es veu.';
        $strings['Today'] = 'Avui';
        $strings['Week'] = 'Setmana';
        $strings['Month'] = 'Mes';
        $strings['BackToCalendar'] = 'Tornar al calendari';
        $strings['BeginDate'] = 'Inici';
        $strings['EndDate'] = 'Fi';
        $strings['Username'] = 'Nom d\'Usuari';
        $strings['Password'] = 'Contrasenya';
        $strings['PasswordConfirmation'] = 'Confirmar Contrasenya';
        $strings['DefaultPage'] = 'P&agrave;gina d\'Inici per defecte';
        $strings['MyCalendar'] = 'El meu calendari';
        $strings['ScheduleCalendar'] = 'Calendari de Reserves';
        $strings['Registration'] = 'Registre';
        $strings['NoAnnouncements'] = 'No hi ha Anuncis';
        $strings['Announcements'] = 'Anuncis';
        $strings['NoUpcomingReservations'] = 'No tens Reserves Properes';
        $strings['UpcomingReservations'] = 'Pr&ograve;ximes Reserves';
        $strings['ShowHide'] = 'Veure/Ocultar';
        $strings['Error'] = 'Error';
        $strings['ReturnToPreviousPage'] = 'Tornar a la &uacute;ltima p&agrave;gina on erets';
        $strings['UnknownError'] = 'Error Desconegut';
        $strings['InsufficientPermissionsError'] = 'No tens perm&iacute; per accedir a aquest Recurs';
        $strings['MissingReservationResourceError'] = 'No s\'ha seleccionat un Recurs';
        $strings['MissingReservationScheduleError'] = 'No s\'ha seleccionat una Planificaci&oacute;';
        $strings['DoesNotRepeat'] = 'No Es Repeteix';
        $strings['Daily'] = 'Diari';
        $strings['Weekly'] = 'Setmanal';
        $strings['Monthly'] = 'Mensual';
        $strings['Yearly'] = 'Anual';
        $strings['RepeatPrompt'] = 'Repetir';
        $strings['hours'] = 'hores';
        $strings['days'] = 'dies';
        $strings['weeks'] = 'setmanes';
        $strings['months'] = 'mesos';
        $strings['years'] = 'anys';
        $strings['day'] = 'dia';
        $strings['week'] = 'setmana';
        $strings['month'] = 'mes';
        $strings['year'] = 'any';
        $strings['repeatDayOfMonth'] = 'dia del mes';
        $strings['repeatDayOfWeek'] = 'dia de la setmana';
        $strings['RepeatUntilPrompt'] = 'Fins';
        $strings['RepeatEveryPrompt'] = 'Cada';
        $strings['RepeatDaysPrompt'] = 'En';
        $strings['CreateReservationHeading'] = 'Crear una nova reserva';
        $strings['EditReservationHeading'] = 'Editant reserva %s';
        $strings['ViewReservationHeading'] = 'Veient reserva %s';
        $strings['ReservationErrors'] = 'Canviar Reserva';
        $strings['Create'] = 'Crear';
        $strings['ThisInstance'] = 'Nom&eacute;s Aquesta Instancia';
        $strings['AllInstances'] = 'Totes Les Instancies';
        $strings['FutureInstances'] = 'Instancies Futures';
        $strings['Print'] = 'Imprimir';
        $strings['ShowHideNavigation'] = 'Veure/Ocultar Navegaci&oacute;';
        $strings['ReferenceNumber'] = 'N&uacute;mero de Refer&egrave;ncia';
        $strings['Tomorrow'] = 'Mat&iacute;';
        $strings['LaterThisWeek'] = 'M&eacute;s Tard Aquesta Semana';
        $strings['NextWeek'] = 'Setmana Vinent';
        $strings['SignOut'] = 'Sortir';
        $strings['LayoutDescription'] = 'Comen&ccedil;a en %s, mostrant %s dies cada cop';
        $strings['AllResources'] = 'Tots Els Recursos';
        $strings['TakeOffline'] = 'Deshabilitar';
        $strings['BringOnline'] = 'Habilitar';
        $strings['AddImage'] = 'Afegir Imatge';
        $strings['NoImage'] = 'Sense Imatge Assignada';
        $strings['Move'] = 'Moure';
        $strings['AppearsOn'] = 'Apareix A %s';
        $strings['Location'] = 'Localitzaci&oacute;';
        $strings['NoLocationLabel'] = '(no s\'ha fixat una localizaci&oacute;)';
        $strings['Contact'] = 'Contacte';
        $strings['NoContactLabel'] = '(sense informaci&oacute; de contacte';
        $strings['Description'] = 'Descripci&oacute;';
        $strings['NoDescriptionLabel'] = '(sense descripci&oacute;)';
        $strings['Notes'] = 'Notes';
        $strings['NoNotesLabel'] = '(sense notes)';
        $strings['NoTitleLabel'] = '(sense t&iacute;tol)';
        $strings['UsageConfiguration'] = 'Configuraci&oacute; D\'&Uacute;s';
        $strings['ChangeConfiguration'] = 'Canviar Configuraci&oacute;';
        $strings['ResourceMinLength'] = 'Les reserves han de durar almenys %s';
        $strings['ResourceMinLengthNone'] = 'No hi ha duraci&oacute; m&iacute;nima de reserva';
        $strings['ResourceMaxLength'] = 'Les reserves no poden durar m&eacute;s de %s';
        $strings['ResourceMaxLengthNone'] = 'No hi ha duraci&oacute; m&agrave;xima de reserva';
        $strings['ResourceRequiresApproval'] = 'Les reserves han de ser aprovades';
        $strings['ResourceRequiresApprovalNone'] = 'Les reserves no necessiten ser aprovades';
        $strings['ResourcePermissionAutoGranted'] = 'El perm&iacute;s &eacute;s autom&aacute;ticament concedit';
        $strings['ResourcePermissionNotAutoGranted'] = 'El perm&iacute;s no &eacute;s autom&aacute;ticament concedit';
        $strings['ResourceMinNotice'] = 'Les reserves han de ser realitzades almenys %s abans del temps d\'Inici';
        $strings['ResourceMinNoticeNone'] = 'Les reserves es poden realizar fins el temps actual';
        $strings['ResourceMaxNotice'] = 'Les reserves no han de durar m&eacute;s de %s des del temps actual';
        $strings['ResourceMaxNoticeNone'] = 'Les reserves poden acabar en qualsevol moment futur';
        $strings['ResourceAllowMultiDay'] = 'Les reserves poden extendres durant diversos dies';
        $strings['ResourceNotAllowMultiDay'] = 'Les reserves no poden extendres durant diversos dies';
        $strings['ResourceCapacity'] = 'Aquest recurs t&eacute; una capacitat de %s persones';
        $strings['ResourceCapacityNone'] = 'Aquest recurs t&eacute; capacitat il&middot;limitada';
        $strings['AddNewResource'] = 'Afegir Nou Recurs';
        $strings['AddNewUser'] = 'Afegir Nou Usuari';
        $strings['AddUser'] = 'Afegir Usuari';
        $strings['Schedule'] = 'Planificaci&oacute;';
        $strings['AddResource'] = 'Afegir Recurs';
        $strings['Capacity'] = 'Capacitat';
        $strings['Access'] = 'Acc&eacute;s';
        $strings['Duration'] = 'Duraci&oacute;';
        $strings['Active'] = 'Actiu';
        $strings['Inactive'] = 'Inactiu';
        $strings['ResetPassword'] = 'Reiniciar Contrasenya';
        $strings['LastLogin'] = '&Uacute;ltim Inici de Sessi&oacute;';
        $strings['Search'] = 'Buscar';
        $strings['ResourcePermissions'] = 'Permisos del Recurs';
        $strings['Reservations'] = 'Reserves';
        $strings['Groups'] = 'Grups';
        $strings['ResetPassword'] = 'Reiniciar Contrasenya';
        $strings['AllUsers'] = 'Tots Els Usuaris';
        $strings['AllGroups'] = 'Tots Els Grups';
        $strings['AllSchedules'] = 'Totes Les Planificacions';
        $strings['UsernameOrEmail'] = 'Nom d\'Usuari o Email';
        $strings['Members'] = 'Membres';
        $strings['QuickSlotCreation'] = 'Crear un slot cada %s minuts entre %s i %s';
        $strings['ApplyUpdatesTo'] = 'Aplicar Actualitzacions A';
        $strings['CancelParticipation'] = 'Cancel&middot;lar Participaci&oacute;';
        $strings['Attending'] = 'Assist&egrave;ncia';
        $strings['QuotaConfiguration'] = 'A %s per %s usuaris de %s estan limitats a %s %s per cada %s';
        $strings['reservations'] = 'reserves';
        $strings['ChangeCalendar'] = 'Canviar calendari';
        $strings['AddQuota'] = 'Afegir Quota';
        $strings['FindUser'] = 'Trobar Usuari';
        $strings['Created'] = 'Creat';
        $strings['LastModified'] = '&Uacute;ltima Modificaci&oacute;';
        $strings['GroupName'] = 'Nom de Grup';
        $strings['GroupMembers'] = 'Membres del Grup';
        $strings['GroupRoles'] = 'Rols del Grup';
        $strings['GroupAdmin'] = 'Administrador del Grup';
        $strings['Actions'] = 'Accions';
        $strings['CurrentPassword'] = 'Contrasenya Actual';
        $strings['NewPassword'] = 'Nova Contrasenya';
        $strings['InvalidPassword'] = 'La Contrasenya actual &eacute;s incorrecta';
        $strings['PasswordChangedSuccessfully'] = 'La teva Contrasenya ha sigut modificada amb &egrave;xit';
        $strings['SignedInAs'] = 'Iniciada Sessi&oacute;';
        $strings['NotSignedIn'] = 'No has iniciat sessi&oacute;';
        $strings['ReservationTitle'] = 'T&iacute;tol de la reserva';
        $strings['ReservationDescription'] = 'Descripci&oacute; de la reserva';
        $strings['ResourceList'] = 'Recursos a reservar';
        $strings['Accessories'] = 'Accesoris';
        $strings['Add'] = 'Afegir';
        $strings['ParticipantList'] = 'Participants';
        $strings['InvitationList'] = 'Convidats';
        $strings['AccessoryName'] = 'Nom d\'Accesori';
        $strings['QuantityAvailable'] = 'Quantitat Disponible';
        $strings['Resources'] = 'Recursos';
        $strings['Participants'] = 'Participants';
        $strings['User'] = 'Usuari';
        $strings['Resource'] = 'Recurs';
        $strings['Status'] = 'Estatus';
        $strings['Approve'] = 'Aprovat';
        $strings['Page'] = 'P&agrave;gina';
        $strings['Rows'] = 'Files';
        $strings['Unlimited'] = 'Il&middot;limitat';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Direci&oacute; de Correu';
        $strings['Phone'] = 'Tel&egrave;fon';
        $strings['Organization'] = 'Organitzaci&oacute;';
        $strings['Position'] = 'Posici&oacute;';
        $strings['Language'] = 'Idioma';
        $strings['Permissions'] = 'Permisos';
        $strings['Reset'] = 'Reiniciar';
        $strings['FindGroup'] = 'Trobar Grup';
        $strings['Manage'] = 'Gestionar';
        $strings['None'] = 'Cap';
        $strings['AddToOutlook'] = 'Afegir a Outlook';
        $strings['Done'] = 'Fet';
        $strings['RememberMe'] = 'Recordam';
        $strings['FirstTimeUser?'] = 'Usuari Per Primera Vegada?';
        $strings['CreateAnAccount'] = 'Crear Compte';
        $strings['ViewSchedule'] = 'Veure Planificaci&oacute;';
        $strings['ForgotMyPassword'] = 'No Recordo La Meva Contrasenya';
        $strings['YouWillBeEmailedANewPassword'] = 'Se\'t enviar&agrave; una Contrasenya generada aleatoriament';
        $strings['Close'] = 'Tancar';
        $strings['ExportToCSV'] = 'Exportar a CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Funcionant...';
        $strings['Login'] = 'Iniciar Sessi&oacute;';
        $strings['AdditionalInformation'] = 'Informaci&oacute; Addicional';
        $strings['AllFieldsAreRequired'] = 'es necessiten tots els camps';
        $strings['Optional'] = 'opcional';
        $strings['YourProfileWasUpdated'] = 'El teu perfil ha sigut actualitzat';
        $strings['YourSettingsWereUpdated'] = 'Els teus ajustos han sigut actualitzats';
        $strings['Register'] = 'Registrar';
        $strings['SecurityCode'] = 'Codi de Seguretat';
        $strings['ReservationCreatedPreference'] = 'Quan jo creo una reserva o una reserva es creada en el meu nom';
        $strings['ReservationUpdatedPreference'] = 'Quan jo actualizado una reserva o una reserva es actualizada en el meu nom';
        $strings['ReservationApprovalPreference'] = 'Quan la meva reserva pendent ha sigut aprovada';
        $strings['PreferenceSendEmail'] = 'Envia\'m un correu';
        $strings['PreferenceNoEmail'] = 'No em notifiquis';
        $strings['ReservationCreated'] = 'La teva reserva ha sigut creada amb &egrave;xit!';
        $strings['ReservationUpdated'] = 'La teva reserva ha sigut actualizada amb &egrave;xit!';
        $strings['ReservationRemoved'] = 'La teva reserva ha sigut eliminada';
        $strings['YourReferenceNumber'] = 'El teu n&uacute;mero de referencia es %s';
        $strings['UpdatingReservation'] = 'Actualizant reserva';
        $strings['ChangeUser'] = 'Canviar Usuari';
        $strings['MoreResources'] = 'M&eacute;s Recursos';
        $strings['ReservationLength'] = 'Duraci&oacute; de la Reserva';
        $strings['ParticipantList'] = 'Llista de Participants';
        $strings['AddParticipants'] = 'Afegir Participants';
        $strings['InviteOthers'] = 'Convidar A Altres';
        $strings['AddResources'] = 'Afegir Recursos';
        $strings['AddAccessories'] = 'Afegir Accesoris';
        $strings['Accessory'] = 'Accesori';
        $strings['QuantityRequested'] = 'Quantitat Requerida';
        $strings['CreatingReservation'] = 'Creant Reserva';
        $strings['UpdatingReservation'] = 'Actualitzant Reserva';
        $strings['DeleteWarning'] = 'Aquesta acci&oacute; es permanent i irrecuperable!';
        $strings['DeleteAccessoryWarning'] = 'A l\'esborrar aquest accesori s\'eliminar&agrave; de totes les reserves.';
        $strings['AddAccessory'] = 'Afegir Accesori';
        $strings['AddBlackout'] = 'Afegir Bloqueig';
        $strings['AllResourcesOn'] = 'Tots els Recursos Habilitats';
        $strings['Reason'] = 'Motiu';
        $strings['BlackoutShowMe'] = 'Veure reserves en conflicte';
        $strings['BlackoutDeleteConflicts'] = 'Esborrar les reserves en conflicte';
        $strings['Filter'] = 'Filtrar';
        $strings['Between'] = 'Entre';
        $strings['CreatedBy'] = 'Creada Per';
        $strings['BlackoutCreated'] = 'Bloqueig Created!';
        $strings['BlackoutNotCreated'] = 'El Bloqueig no pot ser creat!';
        $strings['BlackoutConflicts'] = 'Hi ha un conflicte de bloquejos';
        $strings['ReservationConflicts'] = 'Hi ha temps de reserva en conflicte';
        $strings['UsersInGroup'] = 'Usuaris en aquest grup';
        $strings['Browse'] = 'Navegar';
        $strings['DeleteGroupWarning'] = 'A l\'esborrar aquest grup s\'eliminaran tots els permisos dels recursos associats.  Els usuaris en aquest grup poden perder acc&eacute; als recursos.';
        $strings['WhatRolesApplyToThisGroup'] = 'Quins rols s\'apliquen a aquest grup?';
        $strings['WhoCanManageThisGroup'] = 'Qui pot gestionar aquest grup?';
        $strings['AddGroup'] = 'Afegir Grup';
        $strings['AllQuotas'] = 'Totes Les Quotes';
        $strings['QuotaReminder'] = 'Recordar: Les quotes es fixen basant-se en la zona hora de les planificacions.';
        $strings['AllReservations'] = 'Totes Les Reserves';
        $strings['PendingReservations'] = 'Reserves Pendents';
        $strings['Approving'] = 'Aprovant';
        $strings['MoveToSchedule'] = 'Moure a planificaci&oacute;';
        $strings['DeleteResourceWarning'] = 'A l\'esborrar aquest recurs s\'eliminaran totes les dades associades, incloent';
        $strings['DeleteResourceWarningReservations'] = 'tots els passats, actuals i futures reseves associades';
        $strings['DeleteResourceWarningPermissions'] = 'totes les assignaciones de permisos';
        $strings['DeleteResourceWarningReassign'] = 'Si us plau reassigna tot el que no vulguis que sigui esborrat abans de continuar';
        $strings['ScheduleLayout'] = 'Distribuci&oacute; (totes les vegades %s)';
        $strings['ReservableTimeSlots'] = 'Slots de Temps Reservables';
        $strings['BlockedTimeSlots'] = 'Slots de Temps Bloquejats';
        $strings['ThisIsTheDefaultSchedule'] = 'Aquesta &eacute;s la planificaci&oacute; per defecte';
        $strings['DefaultScheduleCannotBeBroughtDown'] = 'La planificaci&oacute; per defecte no es pot eliminar';
        $strings['MakeDefault'] = 'Posar per defecte';
        $strings['BringDown'] = 'Deshabilitar';
        $strings['ChangeLayout'] = 'Canviar Distribuci&oacute;';
        $strings['AddSchedule'] = 'Afegir planificaci&oacute;';
        $strings['StartsOn'] = 'Comen&ccedil;a En';
        $strings['NumberOfDaysVisible'] = 'N&uacute;meros de Dies Visibles';
        $strings['UseSameLayoutAs'] = 'Utilitzar La Mateixa Distribuci&oacute; Que';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Etiqueta Opcional';
        $strings['LayoutInstructions'] = 'Introdueix un slot per l&iacute;nia.  S\'han de proporcionar slots per les 24 hores del dia comen&ccedil;ant i acabant a les 12:00 AM.';
        $strings['AddUser'] = 'Afegir Usuari';
        $strings['UserPermissionInfo'] = 'L\'acc&eacute;s real als recursos pot ser diferent segons els rols de l\'usuari, permisos de grup, o ajustos externs de permisos';
        $strings['DeleteUserWarning'] = 'A l\'esborrar aquest usuari s\'eliminaran totes les reserves actuals, futures i passades.';
        $strings['AddAnnouncement'] = 'Afegir Anunci';
        $strings['Announcement'] = 'Anunci';
        $strings['Priority'] = 'Prioritat';
        $strings['Reservable'] = 'Reservable';
        $strings['Unreservable'] = 'No Reservable';
        $strings['Reserved'] = 'Reservat';
        $strings['MyReservation'] = 'La meva Reserva';
        $strings['Pending'] = 'Pendent';
        $strings['Past'] = 'Passat';
        $strings['Restricted'] = 'Restringit';
        $strings['ViewAll'] = 'Veure-ho tot';
        $strings['MoveResourcesAndReservations'] = 'Moure tamb� Recursos i Reserves';

        // Errors
        $strings['LoginError'] = 'No s\'ha trobat una correspond&egrave;ncia pel teu nom d\'usuari i contrasenya';
        $strings['ReservationFailed'] = 'La Teva Reserva no s\'ha pogut realitzar';
        $strings['MinNoticeError'] = 'Aquesta reserva s\'ha de realitzar anticipadament.  La data m&eacute;s propera que pot ser reservada %s.';
        $strings['MaxNoticeError'] = 'Aquesta reserva no es pot allargar tan enll&agrave; en el futur. L\'&uacute;ltima data en qu&egrave; es pot reservar &eacute;s %s.';
        $strings['MinDurationError'] = 'Aquesta reserva ha de durar almenys %s.';
        $strings['MaxDurationError'] = 'Aquesta reserva no pot durar m&eacute;s de %s.';
        $strings['ConflictingAccessoryDates'] = 'No hi ha suficients dels seguents accesoris:';
        $strings['NoResourcePermission'] = 'No tens permisos per accedir a un o m&eacute;s dels recursos requerits';
        $strings['ConflictingReservationDates'] = 'Hi ha conflictes a les reserves de les seguents dates:';
        $strings['StartDateBeforeEndDateRule'] = 'La data d\'Inici ha de ser anterior que la data final';
        $strings['StartIsInPast'] = 'La data inicial no pot ser passada';
        $strings['EmailDisabled'] = 'L\'administrador ha desactivat les notificacions per email';
        $strings['ValidLayoutRequired'] = 'S\'han de proporcionar slots per les 24 hores del dia comen&ccedil;ant i acabant a les 12:00 AM.';

        // Page Titles
        $strings['CreateReservation'] = 'Crear Reserva';
        $strings['EditReservation'] = 'Editar Reserva';
        $strings['LogIn'] = 'Iniciar Sessi&oacute;';
        $strings['ManageReservations'] = 'Gestionar Reserves';
        $strings['AwaitingActivation'] = 'Esperant Activaci&oacute;';
        $strings['PendingApproval'] = 'Pendent D\'Aprobaci&oacute;';
        $strings['ManageSchedules'] = 'Planificacions';
        $strings['ManageResources'] = 'Recursos';
        $strings['ManageAccessories'] = 'Accesoris';
        $strings['ManageUsers'] = 'Usuaris';
        $strings['ManageGroups'] = 'Grups';
        $strings['ManageQuotas'] = 'Quotes';
        $strings['ManageBlackouts'] = 'Temps de Bloqueig';
        $strings['MyDashboard'] = 'El meu Panell';
        $strings['ServerSettings'] = 'Ajustos De Servidor';
        $strings['Dashboard'] = 'Panell';
        $strings['Help'] = 'Ajuda';
        $strings['Bookings'] = 'Reserves';
        $strings['Schedule'] = 'Planificaci&oacute;';
        $strings['Reservations'] = 'Reserves';
        $strings['Account'] = 'Compte';
        $strings['EditProfile'] = 'Editar El Meu Perfil';
        $strings['FindAnOpening'] = 'Trobar Un Forat';
        $strings['OpenInvitations'] = 'Invitacions Pendents';
        $strings['MyCalendar'] = 'El meu Calendari';
        $strings['ResourceCalendar'] = 'Calendari de Recursos';
        $strings['Reservation'] = 'Nova Reserva';
        $strings['Install'] = 'Instal&middot;laci&oacute;';
        $strings['ChangePassword'] = 'Canviar Contrasenya';
        $strings['MyAccount'] = 'El Meu Compte';
        $strings['Profile'] = 'Perfil';
        $strings['ApplicationManagement'] = 'Gesti&oacute; de l\'Aplicaci&oacute;';
        $strings['ForgotPassword'] = 'Contrasenya Oblidada';
        $strings['NotificationPreferences'] = 'Preferencies de Notificaci&oacute;';
        $strings['ManageAnnouncements'] = 'Anuncis';
        //

        // Day representations
        $strings['DaySundaySingle'] = 'G';
        $strings['DayMondaySingle'] = 'L';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'C';
        $strings['DayThursdaySingle'] = 'J';
        $strings['DayFridaySingle'] = 'V';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Dig';
        $strings['DayMondayAbbr'] = 'Dls';
        $strings['DayTuesdayAbbr'] = 'Dts';
        $strings['DayWednesdayAbbr'] = 'Dcs';
        $strings['DayThursdayAbbr'] = 'Djs';
        $strings['DayFridayAbbr'] = 'Dvs';
        $strings['DaySaturdayAbbr'] = 'Dst';

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'La Teva Reserva Ha Estat Aprovada';
        $strings['ReservationCreatedSubject'] = 'La Teva Reserva Ha Estat Creada';
        $strings['ReservationUpdatedSubject'] = 'La Teva Reserva Ha Estat Actualitzada';
        $strings['ReservationCreatedAdminSubject'] = 'Notificaci&oacute;: S\'Ha Creat Una Reserva';
        $strings['ReservationUpdatedAdminSubject'] = 'Notificaci&oacute;: S\'Ha Creat Actualitzat Reserva';
        $strings['ParticipantAddedSubject'] = 'Notificaci&oacute; de Participaci&oacute; A Reserva';
        $strings['InviteeAddedSubject'] = 'Invitaci&oacute; a Reserva';
        $strings['ResetPassword'] = 'Petici&oacute; de Reinici de Contrasenya';
        $strings['ForgotPasswordEmailSent'] = 'S\'ha enviat un email a la direcci&oacute; proporcionada amb instruccions per reiniciar la teva contrasenya';
        //End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'No tens cap reserva anterior';
        $strings['PastReservations'] = 'Reserves anteriors';
        $strings['AllNoPastReservations'] = 'No hi ha cap reserva anterior en els darrers %s dies';
        $strings['AllPastReservations'] = 'Totes les reserves anteriors';
        $strings['Yesterday'] = 'Ahir';
        $strings['EarlierThisWeek'] = 'Més aviat aquesta setmana';
        $strings['PreviousWeek'] = 'Setmana anterior';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'El vostre grup no té cap reserva futura';
        $strings['GroupUpcomingReservations'] = 'Properes reserves del meu(s) grup(s)';
        //End Group Upcoming Reservations
        
        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'S\'ha produït un error en iniciar la sessió amb Facebook. Torneu-ho a provar.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'No teniu cap reserva a l\'espera d\'aprovació';
        $strings['PendingApprovalReservations'] = 'Reserves pendents d\'aprovació';
        $strings['LaterThisMonth'] = 'Més endavant aquest mes';
        $strings['LaterThisYear'] = 'Més endavant aquest any';
        $strings['Remaining'] = 'Restant';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'No hi ha cap reserva de sortida que falti';
        $strings['MissingCheckOutReservations'] = 'Reserves de sortida que falten';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'No es poden veure els detalls de la reserva perquè no teniu permisos per a cap dels recursos d\'aquesta reserva';
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
        $days['full'] = ['Diumenge', 'Dilluns', 'Dimarts', 'Dimecres', 'Dijous', 'Divendres', 'Dissabte'];
        // The three letter abbreviation
        $days['abbr'] = ['Dig', 'Dls', 'Dts', 'Dcs', 'Djs', 'Dvs', 'Dst'];
        // The two letter abbreviation
        $days['two'] = ['Dg', 'Dl', 'Dm', 'Dc', 'Dj', 'Dv', 'Ds'];
        // The one letter abbreviation
        $days['letter'] = ['G', 'L', 'T', 'C', 'J', 'V', 'S'];

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
        $months['full'] = ['Gener', 'Febrer', 'Mar&ccedil;', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'];
        // The three letter month name
        $months['abbr'] = ['Gen', 'Feb', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Agt', 'Set', 'Oct', 'Nov', 'Des'];

        $this->Months = $months;
        return $this->Months;
    }

    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', '&Ntilde;', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ca';
    }
}
