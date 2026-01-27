<?php

/**
Translated to Polish by Dariusz Kliszewski
Corrected and expanded by MBK
 */

require_once('Language.php');
require_once('en_gb.php');

class pl extends en_gb
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

        $dates['general_date'] = 'j.m.Y';
        $dates['general_datetime'] = 'j.m.Y H:i:s';
        $dates['short_datetime'] = 'j.m.Y H:i';
        $dates['schedule_daily'] = 'l, j.m.Y';
        $dates['reservation_email'] = 'j.m.Y \v H:i';
        $dates['res_popup'] = 'j.m. H:i';
        $dates['res_popup_time'] = 'H:i';
        $dates['short_reservation_date'] = 'j.m.Y H:i';
        $dates['dashboard'] = 'j.m. H:i';
        $dates['period_time'] = 'H:i';
        $dates['mobile_reservation_date'] = 'j.m. H:i';
        $dates['general_date_js'] = 'd.m.yy';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'D.M.YY hh:mm';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'j.m.';
        $dates['embedded_date'] = 'j.m.';
        $dates['embedded_time'] = 'H:i';
        $dates['embedded_datetime'] = 'j.m. H:i';
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

        $strings['FirstName'] = 'Imię';
        $strings['LastName'] = 'Nazwisko';
        $strings['Timezone'] = 'Strefa czasowa';
        $strings['Edit'] = 'Edycja';
        $strings['Change'] = 'Zmień';
        $strings['Rename'] = 'Zmień nazwę';
        $strings['Remove'] = 'Usuń';
        $strings['Delete'] = 'Skasuj';
        $strings['Update'] = 'Aktualizuj';
        $strings['Cancel'] = 'Anuluj';
        $strings['Add'] = 'Dodaj';
        $strings['Name'] = 'Nazwa';
        $strings['Yes'] = 'Tak';
        $strings['No'] = 'Nie';
        $strings['FirstNameRequired'] = 'Wymagane jest imię.';
        $strings['LastNameRequired'] = 'Wymagane jest nazwisko.';
        $strings['PwMustMatch'] = 'Hasło potwierdzające musi być identyczne.';
        $strings['ValidEmailRequired'] = 'Wymagany poprawny adres mailowy.';
        $strings['UniqueEmailRequired'] = 'Wybrany adres mailowy już istnieje w bazie.';
        $strings['UniqueUsernameRequired'] = 'Wybrany użytkownik już istnieje w bazie.';
        $strings['UserNameRequired'] = 'Wymagana jest nazwa użytkownika.';
        $strings['CaptchaMustMatch'] = 'Wprowadź znaki z pokazanego obrazka.';
        $strings['Today'] = 'Dziś';
        $strings['Week'] = 'Tydzień';
        $strings['Month'] = 'Miesiąc';
        $strings['BackToCalendar'] = 'Powrót do kalendarza';
        $strings['BeginDate'] = 'Od';
        $strings['EndDate'] = 'Do';
        $strings['Username'] = 'Nazwa użytkownika';
        $strings['Password'] = 'Hasło';
        $strings['PasswordConfirmation'] = 'Potwierdź hasło';
        $strings['DefaultPage'] = 'Domyślna strona główna';
        $strings['MyCalendar'] = 'Mój kalendarz';
        $strings['ScheduleCalendar'] = 'Terminarz';
        $strings['Registration'] = 'Rejestracja';
        $strings['NoAnnouncements'] = 'Brak powiadomień';
        $strings['Announcements'] = 'Powiadomienia';
        $strings['NoUpcomingReservations'] = 'Nie masz żadnych zbliżających się rezerwacji';
        $strings['UpcomingReservations'] = 'Zbliżające się rezerwacje';
        $strings['AllNoUpcomingReservations'] = 'Brak rezerwacji w ciągu najbliższych %s dni';
        $strings['AllUpcomingReservations'] = 'Wszystkie nadchodzące rezerwacje';
        $strings['ShowHide'] = 'Pokaż/Ukryj';
        $strings['Error'] = 'Błąd';
        $strings['ReturnToPreviousPage'] = 'Wróc do poprzedniej strony...';
        $strings['UnknownError'] = 'Nieznany błąd';
        $strings['InsufficientPermissionsError'] = 'Nie masz uprawnień do tego zasobu';
        $strings['MissingReservationResourceError'] = 'Zasób nie został wybrany';
        $strings['MissingReservationScheduleError'] = 'Termin nie został wybrany';
        $strings['DoesNotRepeat'] = 'Bez powtarzania';
        $strings['Daily'] = 'Codziennie';
        $strings['Weekly'] = 'Co tydzień';
        $strings['Monthly'] = 'Co miesiąc';
        $strings['Yearly'] = 'Co rok';
        $strings['RepeatPrompt'] = 'Powtarzanie';
        $strings['hours'] = 'godziny';
        $strings['days'] = 'dni';
        $strings['weeks'] = 'tygodnie';
        $strings['months'] = 'miesiące';
        $strings['years'] = 'lata';
        $strings['day'] = 'dzień';
        $strings['week'] = 'tydzień';
        $strings['month'] = 'miesiąc';
        $strings['year'] = 'rok';
        $strings['repeatDayOfMonth'] = 'dnia miesiąca';
        $strings['repeatDayOfWeek'] = 'dnia tygodnia';
        $strings['RepeatUntilPrompt'] = 'Do dnia';
        $strings['RepeatEveryPrompt'] = 'Każdego';
        $strings['RepeatDaysPrompt'] = 'Włączone';
        $strings['CreateReservationHeading'] = 'Stwórz nową rezerwację';
        $strings['EditReservationHeading'] = 'Edytuj rezerwację %s';
        $strings['ViewReservationHeading'] = 'Podgląd rezerwacji %s';
        $strings['ReservationErrors'] = 'Zmień rezerwację';
        $strings['Create'] = 'Stwórz';
        $strings['ThisInstance'] = 'Tylko to wystąpienie';
        $strings['AllInstances'] = 'Wszystkie wystąpienia';
        $strings['FutureInstances'] = 'Przyszłe wystąpienia';
        $strings['Print'] = 'Drukuj';
        $strings['ShowHideNavigation'] = 'Pokaż/Ukryj nawigację';
        $strings['ReferenceNumber'] = 'Numer referencyjny';
        $strings['Tomorrow'] = 'Jutro';
        $strings['LaterThisWeek'] = 'Jeszcze w tym tygodniu';
        $strings['NextWeek'] = 'Następny tydzień';
        $strings['SignOut'] = 'Wyloguj się';
        $strings['LayoutDescription'] = 'Rozpoczyna się %s, trwa %s dni';
        $strings['AllResources'] = 'Wszystkie zasoby';
        $strings['TakeOffline'] = 'Deaktywuj';
        $strings['BringOnline'] = 'Aktywuj';
        $strings['AddImage'] = 'Dodaj obraz';
        $strings['NoImage'] = 'Brak przypisanego obrazu';
        $strings['Move'] = 'Przesuń';
        $strings['AppearsOn'] = 'Występuje w %s';
        $strings['Location'] = 'Lokalizacja';
        $strings['NoLocationLabel'] = '(brak ustawionej lokalizacji)';
        $strings['Contact'] = 'Kontakt';
        $strings['NoContactLabel'] = '(brak danych kontaktowych)';
        $strings['Description'] = 'Opis';
        $strings['NoDescriptionLabel'] = '(brak opisu)';
        $strings['Notes'] = 'Notatki';
        $strings['NoNotesLabel'] = '(brak notatek)';
        $strings['NoTitleLabel'] = '(brak tytułu)';
        $strings['UsageConfiguration'] = 'Konfiguracja użycia';
        $strings['ChangeConfiguration'] = 'Zmień konfigurację';
        $strings['ResourceMinLength'] = 'Rezerwacja musi trwać co najmniej %s';
        $strings['ResourceMinLengthNone'] = 'Brak minimalnego czasu rezerwacji';
        $strings['ResourceMaxLength'] = 'Rezerwacja nie może trwać więcej niż %s';
        $strings['ResourceMaxLengthNone'] = 'Brak maksymalnego czasu rezerwacji';
        $strings['ResourceRequiresApproval'] = 'Rezerwacja musi być zatwierdzana';
        $strings['ResourceRequiresApprovalNone'] = 'Rezerwacja nie wymaga zatwierdzania';
        $strings['ResourcePermissionAutoGranted'] = 'Uprawnienia są automatycznie nadawane';
        $strings['ResourcePermissionNotAutoGranted'] = 'Uprawnienia nie są automatycznie nadawane';
        $strings['ResourceMinNotice'] = 'Rezerwacje muszą być dokonane co najmniej %s przed rozpoczęciem';
        $strings['ResourceMinNoticeNone'] = 'Rezerwacji można dokonać aż do terminu rozpoczęcia';
        $strings['ResourceMinNoticeUpdate'] = 'Rezerwacje mogą być zmienione najpóźniej %s przed czasem rozpoczęcia';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Rezerwacje mogą być zmienione bez wyprzedzenia';
        $strings['ResourceMinNoticeDelete'] = 'Rezerwacje mogą być usunięte najpóźniej %s przed czasem rozpoczęcia';
        $strings['ResourceMinNoticeNoneDelete'] = 'Rezerwacje mogą być usunięte bez wyprzedzenia';
        $strings['ResourceMaxNotice'] = 'Rezerwacja nie może trwać dłużej niż %s od momentu rozpoczecia';
        $strings['ResourceMaxNoticeNone'] = 'Rezerwacje nie są ograniczane czasowo';
        $strings['ResourceBufferTime'] = 'Wymagana jest przerwa między rezerwacjami trwająca %s.';
        $strings['ResourceBufferTimeNone'] = 'Nie ma wymaganej przerwy między rezerwacjami';
        $strings['ResourceAllowMultiDay'] = 'Rezerwacji można dokonać na wiele dni';
        $strings['ResourceNotAllowMultiDay'] = 'Rezerwacji można dokonać tylko w ciągu jednego dnia';
        $strings['ResourceCapacity'] = 'Ten zasób może pomieścić maksymalnie osób: %s ';
        $strings['ResourceCapacityNone'] = 'Ten zasób ma nieograniczoną pojemność';
        $strings['AddNewResource'] = 'Dodaj nowy zasób';
        $strings['AddNewUser'] = 'Dodaj nowego użytkownika';
        $strings['AddResource'] = 'Dodaj zasób';
        $strings['Capacity'] = 'Pojemność';
        $strings['Access'] = 'Dostęp';
        $strings['Duration'] = 'Czas trwania';
        $strings['Active'] = 'Aktywny';
        $strings['Inactive'] = 'Nieaktywny';
        $strings['ResetPassword'] = 'Reset hasła';
        $strings['LastLogin'] = 'Ostatnie logowanie';
        $strings['Search'] = 'Szukaj';
        $strings['ResourcePermissions'] = 'Uprawnienia do zasobów';
        $strings['Reservations'] = 'rezerwacje';
        $strings['Groups'] = 'Grupy';
        $strings['Users'] = 'Użytkownicy';
        $strings['AllUsers'] = 'Wszyscy użytkownicy';
        $strings['AllGroups'] = 'Wszystkie grupy';
        $strings['AllSchedules'] = 'Wszystkie harmonogramy';
        $strings['UsernameOrEmail'] = 'Nazwa użytkownika lub adres mailowy';
        $strings['Members'] = 'Członkowie';
        $strings['QuickSlotCreation'] = 'Twórz wpisy co %s minut, pomiędzy %s i %s';
        $strings['ApplyUpdatesTo'] = 'Uaktualnij';
        $strings['CancelParticipation'] = 'Anuluj uczestnictwo';
        $strings['Attending'] = 'Uczestnictwo';
        $strings['QuotaConfiguration'] = 'Na %s dla %s użytkowników w %s jest limitowana do %s %s przez %s';
        $strings['QuotaEnforcement'] = 'Wymuszono %s %s';
        $strings['reservations'] = 'rezerwacje';
        $strings['reservation'] = 'rezerwacja';
        $strings['ChangeCalendar'] = 'Zmień kalendarz';
        $strings['AddQuota'] = 'Dodaj limit';
        $strings['FindUser'] = 'Znajdź użytkownika';
        $strings['Created'] = 'Stworzony';
        $strings['LastModified'] = 'Ostatnia modyfikacja';
        $strings['GroupName'] = 'Nazwa grupy';
        $strings['GroupMembers'] = 'Członkowie grupy';
        $strings['GroupRoles'] = 'Role grup';
        $strings['GroupAdmin'] = 'Grupa administratorów';
        $strings['Actions'] = 'Akcje';
        $strings['CurrentPassword'] = 'Aktualne hasło';
        $strings['NewPassword'] = 'Nowe hasło';
        $strings['InvalidPassword'] = 'Wprwadzone hasło jest niepoprawne';
        $strings['PasswordChangedSuccessfully'] = 'Twoje hasło zostało zmienione';
        $strings['SignedInAs'] = 'Zalogowany jako';
        $strings['NotSignedIn'] = 'Nie jesteś zalogowany';
        $strings['ReservationTitle'] = 'Tytuł rezerwacji';
        $strings['ReservationDescription'] = 'Opis rezerwacji';
        $strings['ResourceList'] = 'Zasoby do zarezerwowania';
        $strings['Accessories'] = 'Akcesoria';
        $strings['InvitationList'] = 'Zaproszenia';
        $strings['AccessoryName'] = 'Nazwa akcesoria';
        $strings['QuantityAvailable'] = 'Dostepna ilość';
        $strings['Resources'] = 'Zasoby';
        $strings['Participants'] = 'Uczestnicy';
        $strings['User'] = 'Użytkownik';
        $strings['Resource'] = 'Zasób';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Zatwierdź';
        $strings['Page'] = 'Strona';
        $strings['Rows'] = 'Wierszy';
        $strings['Unlimited'] = 'Bez limitu';
        $strings['Email'] = 'Adres e-mail';
        $strings['EmailAddress'] = 'Adres mailowy';
        $strings['Phone'] = 'Numer telefonu';
        $strings['Organization'] = 'Nazwa firmy';
        $strings['Position'] = 'Stanowisko';
        $strings['Language'] = 'Język';
        $strings['Permissions'] = 'Uprawnienia';
        $strings['Reset'] = 'Reset';
        $strings['FindGroup'] = 'Znajdź grupę';
        $strings['Manage'] = 'Zarządzaj';
        $strings['None'] = 'Brak';
        $strings['AddToOutlook'] = 'Pobierz ICS (Outlook)';
        $strings['Done'] = 'Wykonaj';
        $strings['RememberMe'] = 'Zapamiętaj mnie';
        $strings['FirstTimeUser?'] = 'Pierwszy raz tutaj?';
        $strings['CreateAnAccount'] = 'Zarejestruj się';
        $strings['ViewSchedule'] = 'Podgląd harmonogramu';
        $strings['ForgotMyPassword'] = 'Zapomniałeś hasła?';
        $strings['YouWillBeEmailedANewPassword'] = 'Zostanie wysłane nowo wygenerowane hasło na twój adres mailowy';
        $strings['Close'] = 'Zamknij';
        $strings['ExportToCSV'] = 'Eksportuj do CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Pracuje...';
        $strings['Login'] = 'Logowanie';
        $strings['AdditionalInformation'] = 'Dodatkowe informacje';
        $strings['AllFieldsAreRequired'] = 'wszystkie pola są wymagane';
        $strings['Optional'] = 'opcjonalnie';
        $strings['YourProfileWasUpdated'] = 'Twój profil został zaktualizowany';
        $strings['YourSettingsWereUpdated'] = 'Twoje ustawenia zostały zmienione';
        $strings['Register'] = 'Rejestruj';
        $strings['SecurityCode'] = 'Kod zabezpieczający';
        $strings['ReservationCreatedPreference'] = 'Kiedy tworzę rezerwację lub rezerwacja jest tworzona za mnie';
        $strings['ReservationUpdatedPreference'] = 'Kiedy aktualizuję rezerwację lub rezerwacja jest aktualizowana za mnie';
        $strings['ReservationDeletedPreference'] = 'Kiedy usuwam rezerwację lub rezerwacja jest usuwana za mnie';
        $strings['ReservationApprovalPreference'] = 'Kiedy moje rezerwacja zostatnie zatwierdzona';
        $strings['PreferenceSendEmail'] = 'Powiadom mnie mailem';
        $strings['PreferenceNoEmail'] = 'Nie powiadamiaj mnie';
        $strings['ReservationCreated'] = 'Twoja rezerwacja została pomyślnie utworzona!';
        $strings['ReservationUpdated'] = 'Twoja rezerwacja została pomyślnie zaktualizowana!';
        $strings['ReservationRemoved'] = 'Twoja rezerwacja została pomyślnie usunięta';
        $strings['ReservationRequiresApproval'] = 'Rezerwacja jednego lub więcej zasobów wymaga zatwierdzenia. Zostanie ona oznaczona jako oczekująca.';
        $strings['YourReferenceNumber'] = 'Numer twojej rezerwacji to %s';
        $strings['ChangeUser'] = 'Zmiana użytkownika';
        $strings['MoreResources'] = 'Więcej zasobów';
        $strings['ReservationLength'] = 'Długość rezerwacji';
        $strings['ParticipantList'] = 'Lista uczestników';
        $strings['AddParticipants'] = 'Dodaj uczestników';
        $strings['InviteOthers'] = 'Zaproś innych';
        $strings['AddResources'] = 'Dodaj zasób';
        $strings['AddAccessories'] = 'Dodaj akcesoria';
        $strings['Accessory'] = 'Akcesorium';
        $strings['QuantityRequested'] = 'Wymagana ilość';
        $strings['CreatingReservation'] = 'Tworzenie rezerwacji';
        $strings['UpdatingReservation'] = 'Aktualizowanie rezerwacji';
        $strings['DeleteWarning'] = 'Ta akcja jest trwała i bez możliwości powrotu!';
        $strings['DeleteAccessoryWarning'] = 'Usunięcie tego akcesorium spowoduje usunięcie go ze wszystkich rezerwacji.';
        $strings['AddAccessory'] = 'Dodaj akcesorium';
        $strings['AddBlackout'] = 'Dodaj niedostępność';
        $strings['AllResourcesOn'] = 'Wszystkie zasoby włączone';
        $strings['Reason'] = 'Powód';
        $strings['BlackoutShowMe'] = 'Pokaż mi rezerwacje z konfliktem';
        $strings['BlackoutDeleteConflicts'] = 'Usuń rezerwacje z konfliktem';
        $strings['Filter'] = 'Filtr';
        $strings['Between'] = 'Pomiędzy';
        $strings['CreatedBy'] = 'Utworzone przez';
        $strings['BlackoutCreated'] = 'Niedostępność utworzona!';
        $strings['BlackoutNotCreated'] = 'Niedostępnośc nie mogła zostać utworzona!';
        $strings['BlackoutUpdated'] = 'Niedostępność zmodyfikowana';
        $strings['BlackoutNotUpdated'] = 'Niedostępność nie mogła być zmodyfikowana';
        $strings['BlackoutConflicts'] = 'Istnieją konflikty niedostępności';
        $strings['ReservationConflicts'] = 'Istnieją konflikty rezerwacji';
        $strings['UsersInGroup'] = 'Uzytkownicy w tej grupie';
        $strings['Browse'] = 'Przeglądaj';
        $strings['DeleteGroupWarning'] = 'Usunięcie tej grupy spowoduje wykasowanie wszystkich uprawnień do zasobów. Użytkownicy z tej grupy, mogą utracić uprawnienia do zasobów.';
        $strings['WhatRolesApplyToThisGroup'] = 'Jakie dodać role dla tej grupy?';
        $strings['WhoCanManageThisGroup'] = 'Kto może zarządzać tą grupą?';
        $strings['WhoCanManageThisSchedule'] = 'Kto może zarządzać tym harmonogramem?';
        $strings['AllQuotas'] = 'Wszystkie limity';
        $strings['QuotaReminder'] = 'Pamiętaj: Limity są egzekwowane na podstawie strefy czasowej harmonogramu';
        $strings['AllReservations'] = 'Wszystkie rezerwacje';
        $strings['PendingReservations'] = 'Oczekujące rezerwacje';
        $strings['Approving'] = 'Zatwierdź';
        $strings['MoveToSchedule'] = 'Przenieś do harmonogramu';
        $strings['DeleteResourceWarning'] = 'Usunięcie tego zasobu spowoduje wykasowanie wszystkich danych, włącznie z';
        $strings['DeleteResourceWarningReservations'] = 'jego wszystkimi przeszłymi, obecnymi i przyszłymi rezerwacjami';
        $strings['DeleteResourceWarningPermissions'] = 'wszystkimi przypisanymi uprawnieniami';
        $strings['DeleteResourceWarningReassign'] = 'Proszę przemysleć dokładnie zanim coś zostanie usunięte';
        $strings['ScheduleLayout'] = 'Rozkład (zawsze %s)';
        $strings['ReservableTimeSlots'] = 'Lista wolnych terminów rezerwacji';
        $strings['BlockedTimeSlots'] = 'Lista zablokowanych terminów rezerwacji';
        $strings['ThisIsTheDefaultSchedule'] = 'To jest domyślny harmonogram';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Domyślny harmonogram nie może zostać usunięty';
        $strings['MakeDefault'] = 'Ustaw jako domyślny';
        $strings['BringDown'] = 'Opóźnij';
        $strings['ChangeLayout'] = 'Zmiana rozkładu';
        $strings['AddSchedule'] = 'Dodaj harmonogram';
        $strings['StartsOn'] = 'Rozpoczyna się';
        $strings['NumberOfDaysVisible'] = 'Liczba widocznych dni';
        $strings['UseSameLayoutAs'] = 'Użyj takiego samego rozkładu jak';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Opcjonalna nazwa';
        $strings['LayoutInstructions'] = 'Dodawaj jeden wpis w linii. Wpisy muszą być uzupełnione dla wszystkich 24 godzin od rozpoczęcia dnia aż do północy.';
        $strings['AddUser'] = 'Dodaj użytkownika';
        $strings['UserPermissionInfo'] = 'Aktualny dostep do zasobu może być inny w zależności od roli użytkownika, uprawnień grupy lub zewnętrznych ustawień uprawnień';
        $strings['DeleteUserWarning'] = 'Usunięcie tego użytkownika spowoduje usunięcie wszystkich kiedykolwiek utworzonych rezerwacji.';
        $strings['AddAnnouncement'] = 'Dodaj powiadomienie';
        $strings['Announcement'] = 'Powiadomienie';
        $strings['Priority'] = 'Priorytet';
        $strings['Reservable'] = 'Dostępne';
        $strings['Unreservable'] = 'Niedostępne';
        $strings['Reserved'] = 'Zarezerwowane';
        $strings['MyReservation'] = 'Moje rezerwacje';
        $strings['Pending'] = 'Oczekujący';
        $strings['Past'] = 'Przeszłe';
        $strings['Restricted'] = 'Ograniczone';
        $strings['ViewAll'] = 'Podgląd wszystkich';
        $strings['MoveResourcesAndReservations'] = 'Przenieś zasoby i rezerwacje do';
        $strings['TurnOffSubscription'] = 'Wyłącz subskrypcję kalendarza';
        $strings['TurnOnSubscription'] = 'Zezwól na subskrypcję tego kalendarza';
        $strings['SubscribeToCalendar'] = 'Subskrybuj ten kalendarz';
        $strings['SubscriptionsAreDisabled'] = 'Administrator wyłączył subskrypcje kalendarza';
        $strings['NoResourceAdministratorLabel'] = '(Brak Administratora Zasobów)';
        $strings['WhoCanManageThisResource'] = 'Kto może zarządzać tym zasobem?';
        $strings['ResourceAdministrator'] = 'Administrator Zasobów';
        $strings['Private'] = 'Prywatny';
        $strings['Accept'] = 'Akceptuj';
        $strings['Decline'] = 'Odrzuć';
        $strings['ShowFullWeek'] = 'Pokaż cały tydzień';
        $strings['CustomAttributes'] = 'Atrybuty niestandardowe';
        $strings['AddAttribute'] = 'Dodaj atrybut';
        $strings['EditAttribute'] = 'Edytuj atrybut';
        $strings['DisplayLabel'] = 'Etykieta';
        $strings['Type'] = 'Typ';
        $strings['Required'] = 'Wymagany';
        $strings['ValidationExpression'] = 'Wyrażenie walidacyjne';
        $strings['PossibleValues'] = 'Możliwe wartości';
        $strings['SingleLineTextbox'] = 'Jednoliniowe pole tekstowe';
        $strings['MultiLineTextbox'] = 'Wieloliniowe pole tekstowe';
        $strings['Checkbox'] = 'Pole wyboru';
        $strings['SelectList'] = 'Lista wyboru';
        $strings['CommaSeparated'] = 'rozdzielane przecinkiem';
        $strings['Category'] = 'Kategoria';
        $strings['CategoryReservation'] = 'Rezerwacja';
        $strings['CategoryGroup'] = 'Grupa';
        $strings['SortOrder'] = 'Porządek sortowania';
        $strings['Title'] = 'Tytuł';
        $strings['AdditionalAttributes'] = 'Atrybuty dodatkowe';
        $strings['True'] = 'Prawda';
        $strings['False'] = 'Fałsz';
        $strings['ForgotPasswordEmailSent'] = 'Na podany adres został wysłany email z instrukcjami zresetowania hasła';
        $strings['ActivationEmailSent'] = 'Niedługo otrzymasz emaila aktywacyjnego.';
        $strings['AccountActivationError'] = 'Przepraszamy, nie możemy aktywować Twojego konta.';
        $strings['Attachments'] = 'Załączniki';
        $strings['AttachFile'] = 'Załącz plik';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'Brak administratora harmonogramu';
        $strings['ScheduleAdministrator'] = 'Administrator harmonogramu';
        $strings['Total'] = 'Razem';
        $strings['QuantityReserved'] = 'Ilość zarezerwowana';
        $strings['AllAccessories'] = 'Wszystkie akcesoria';
        $strings['GetReport'] = 'Generuj raport';
        $strings['NoResultsFound'] = 'Nie znaleziono pasujących wyników';
        $strings['SaveThisReport'] = 'Zapisz ten raport';
        $strings['ReportSaved'] = 'Raport zapisany!';
        $strings['EmailReport'] = 'Wyślij raport mailem';
        $strings['ReportSent'] = 'Raport wysłany!';
        $strings['RunReport'] = 'Uruchom raport';
        $strings['NoSavedReports'] = 'Nie masz zapisanych raportów.';
        $strings['CurrentWeek'] = 'Bieżący tydzień';
        $strings['CurrentMonth'] = 'Bieżący miesiąc';
        $strings['AllTime'] = 'Od początku';
        $strings['FilterBy'] = 'Filtruj po';
        $strings['Select'] = 'Zaznacz';
        $strings['List'] = 'Lista';
        $strings['TotalTime'] = 'Łączny czas';
        $strings['Count'] = 'Liczba';
        $strings['Usage'] = 'Użycie';
        $strings['AggregateBy'] = 'Grupuj po';
        $strings['Range'] = 'Zakres';
        $strings['Choose'] = 'Wybierz';
        $strings['All'] = 'Wszystko';
        $strings['ViewAsChart'] = 'Zobacz jako wykres';
        $strings['ReservedResources'] = 'Zarezerwowane zasoby';
        $strings['ReservedAccessories'] = 'Zarezerwowane akcesoria';
        $strings['ResourceUsageTimeBooked'] = 'Użycie zasobów - zarezerwowany czas';
        $strings['ResourceUsageReservationCount'] = 'Użycie zasobów - liczba rezerwacji';
        $strings['Top20UsersTimeBooked'] = 'Top 20 użytkowników - zarezerwowany czas';
        $strings['Top20UsersReservationCount'] = 'Top 20 użytkowników - liczba rezerwacji';
        $strings['ConfigurationUpdated'] = 'Plik konfiguracji został zaktualizowany.';
        $strings['ConfigurationUiNotEnabled'] = 'Nie masz dostępu do tej strony ponieważ $conf[\'settings\'][\'pages\'][\'enable.configuration\'] nie jest ustawiony lub ma wartość "fałsz".';
        $strings['ConfigurationFileNotWritable'] = 'Plik konfiguracji nie jest dostępny do zapisu. Sprawdź uprawnienia i spróbuj ponownie.';
        $strings['ConfigurationUpdateHelp'] = 'Sprawdź rozdział Konfiguracja w  <a target=_blank href=%s>pliku pomocy</a> i zapoznaj się z dokumentacją.';
        $strings['GeneralConfigSettings'] = 'ustawienia';
        $strings['UseSameLayoutForAllDays'] = 'Użyj tego samego rozkładu dla wszystkich dni';
        $strings['LayoutVariesByDay'] = 'Rozkład zależny od dnia';
        $strings['ManageReminders'] = 'Przypomnienia';
        $strings['ReminderUser'] = 'Identyfikator użytkownika';
        $strings['ReminderMessage'] = 'Wiadomość';
        $strings['ReminderAddress'] = 'Adres';
        $strings['ReminderSendtime'] = 'Czas wysłania';
        $strings['ReminderRefNumber'] = 'Numer rezerwacji';
        $strings['ReminderSendtimeDate'] = 'Data przypomnienia';
        $strings['ReminderSendtimeTime'] = 'Czas przypomnienia (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Dodaj przypomnienie';
        $strings['DeleteReminderWarning'] = 'Czy na pewno chcesz usunąć przypomnienie?';
        $strings['NoReminders'] = 'Nie masz nadchodzących przypomnień.';
        $strings['Reminders'] = 'Przypomnienia';
        $strings['SendReminder'] = 'Wyślij przypomnienie';
        $strings['minutes'] = 'minut';
        $strings['hours'] = 'godzin';
        $strings['days'] = 'dni';
        $strings['ReminderBeforeStart'] = 'przed rozpoczęciem';
        $strings['ReminderBeforeEnd'] = 'przed zakończeniem';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'plik CSS';
        $strings['ThemeUploadSuccess'] = 'Twoje zmiany zostały zapisane. Odśwież stronę, aby zaczęły działać.';
        $strings['MakeDefaultSchedule'] = 'Ustaw jako mój domyślny harmonogram';
        $strings['DefaultScheduleSet'] = 'Od teraz to jest Twój domyślny harmonogram';
        $strings['FlipSchedule'] = 'Zmień układ';
        $strings['Next'] = 'Następne';
        $strings['Success'] = 'Sukces';
        $strings['Participant'] = 'Wspólne';
        $strings['ResourceFilter'] = 'Filtr zasobów';
        $strings['ResourceGroups'] = 'Grupy zasobów';
        $strings['AddNewGroup'] = 'Nowa grupa';
        $strings['Quit'] = 'Wyjdź';
        $strings['AddGroup'] = 'Dodaj grupę';
        $strings['StandardScheduleDisplay'] = 'Układ standardowy';
        $strings['TallScheduleDisplay'] = 'Układ wysoki';
        $strings['WideScheduleDisplay'] = 'Układ szeroki';
        $strings['CondensedWeekScheduleDisplay'] = 'Układ dopasowany tygodniowy';
        $strings['ResourceGroupHelp1'] = 'Przeciągaj grupy zasobów, aby zmienić ich porządek.';
        $strings['ResourceGroupHelp2'] = 'Kliknij prawym przyciskiem myszy na grupę zasobu, aby zobaczyć dodatkowe opcje.';
        $strings['ResourceGroupHelp3'] = 'Przeciągaj zasoby, aby dodać je do grup.';
        $strings['ResourceGroupWarning'] = 'Każdy zasób musi być przypisany do co najmniej jednej grupy. Nieprzypisane zasoby nie będą mogły być rezerwowane.';
        $strings['ResourceType'] = 'Typ zasobu';
        $strings['AppliesTo'] = 'Dotyczy';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Dodaj typ zasobu';
        $strings['NoResourceTypeLabel'] = '()';
        $strings['ClearFilter'] = 'Wyczyść filtr';
        $strings['MinimumCapacity'] = 'Minimalna liczba osób';
        $strings['Color'] = 'Kolor';
        $strings['Available'] = 'Dostępny';
        $strings['Unavailable'] = 'Niedostępny';
        $strings['Hidden'] = 'Ukryty';
        $strings['ResourceStatus'] = 'Status zasobu';
        $strings['CurrentStatus'] = 'Aktualny status';
        $strings['AllReservationResources'] = 'All Reservation Resources';
        $strings['File'] = 'Plik';
        $strings['BulkResourceUpdate'] = 'Edycja wielu zasobów';
        $strings['Unchanged'] = 'niezmienione';
        $strings['Common'] = 'Wspólne';
        $strings['AdminOnly'] = 'Is Admin Only';
        $strings['AdvancedFilter'] = 'Filtr zaawansowany';
        $strings['MinimumQuantity'] = 'Minimalna liczba';
        $strings['MaximumQuantity'] = 'Maksymalna liczba';
        $strings['ChangeLanguage'] = 'Zmień język';
        $strings['AddRule'] = 'Dodaj regułę';
        $strings['Attribute'] = 'Atrybut';
        $strings['RequiredValue'] = 'Wymagana wartość';
        $strings['ReservationCustomRuleAdd'] = 'Użyj tego koloru, gdy atrybut rezerwacji jest ustawiony na podaną wartość.';
        $strings['AddReservationColorRule'] = 'Dodaj zasadę kolorowania rezerwacji';
        $strings['LimitAttributeScope'] = 'Zbierz w szczególnych przypadkach';
        $strings['CollectFor'] = 'Zbierz dla';
        $strings['SignIn'] = 'Zaloguj się';
        $strings['AllParticipants'] = 'Wszyscy uczestnicy';
        $strings['RegisterANewAccount'] = 'Zarejestruj nowe konto';
        $strings['Dates'] = 'Terminy';
        $strings['More'] = 'Więcej';
        $strings['ResourceAvailability'] = 'Dostępność zasobów';
        $strings['UnavailableAllDay'] = 'Niedostępne przez cały dzień';
        $strings['AvailableUntil'] = 'Dostępne do';
        $strings['AvailableBeginningAt'] = 'Dostępne od';
        $strings['AvailableAt'] = 'Dostępne w';
        $strings['AllResourceTypes'] = 'Wszystkie typy zasobów';
        $strings['AllResourceStatuses'] = 'Wszystkie statusy zasobów';
        $strings['AllowParticipantsToJoin'] = 'Uczestnicy mogą dołączać';
        $strings['Join'] = 'Dołącz';
        $strings['YouAreAParticipant'] = 'Uczestniczysz w tej rezerwacji';
        $strings['YouAreInvited'] = 'Zaproszono Cię na ten termin:';
        $strings['YouCanJoinThisReservation'] = 'Możesz dołączyć do tej rezerwacji';
        $strings['Import'] = 'Importuj';
        $strings['GetTemplate'] = 'Zdobądź szablon';
        $strings['UserImportInstructions'] = '<ul><li>Plik musi być w formacie CSV.</li><li>Wymagane są: nazwa użytkownika i adres e-mail.</li><li>Poprawność atrybutu nie będzie wymagana.</li><li>Pozostawienie pozostałych pól pustych ustawi wartości na domyślne oraz \'password\' na jego domyślne. </li><Jako przykład użyj podanego wzoru.</li></ul>';
        $strings['RowsImported'] = 'wierszy zaimportowano';
        $strings['RowsSkipped'] = 'wierszy pominięto';
        $strings['Columns'] = 'Kolumny';
        $strings['Reserve'] = 'Rezerwuj';
        $strings['AllDay'] = 'Cały dzień';
        $strings['Everyday'] = 'codziennie';
        $strings['IncludingCompletedReservations'] = 'wraz z zakończonymi rezerwacjami';
        $strings['NotCountingCompletedReservations'] = 'bez zakończonych rezerwacji';
        $strings['RetrySkipConflicts'] = 'Pomiń rezerwacje kolidujące';
        $strings['Retry'] = 'Spróbuj ponownie';
        $strings['RemoveExistingPermissions'] = 'Usunąć istniejące uprawnienia?';
        $strings['Continue'] = 'Kontynuuj';
        $strings['WeNeedYourEmailAddress'] = 'Aby złożyć rezerwację potrzebny jest adres e-mail';
        $strings['ResourceColor'] = 'Kolor zasobu';
        $strings['DateTime'] = 'Data / czas';
        $strings['AutoReleaseNotification'] = 'Automatycznie zwolnij w przypadku braku zameldowania w ciągu %s minut';
        $strings['RequiresCheckInNotification'] = 'Wymaga za- i wymeldowania';
        $strings['NoCheckInRequiredNotification'] = 'Nie wymaga za- i wymeldowania';
        $strings['RequiresApproval'] = 'Konieczne zatwierdzenie';
        $strings['CheckingIn'] = 'Meldowanie';
        $strings['CheckingOut'] = 'Wymeldowywanie';
        $strings['CheckIn'] = 'Zamelduj się';
        $strings['CheckOut'] = 'Wymelduj się';
        $strings['ReleasedIn'] = 'Released in';
        $strings['CheckedInSuccess'] = 'Jesteś zameldowany/a';
        $strings['CheckedOutSuccess'] = 'Jesteś wymeldowany/a';
        $strings['CheckInFailed'] = 'Zameldowanie nieudane';
        $strings['CheckOutFailed'] = 'Wymeldowanie nieudane';
        $strings['CheckInTime'] = 'Zameldowanie';
        $strings['CheckOutTime'] = 'Wymeldowanie';
        $strings['OriginalEndDate'] = 'Koniec';
        $strings['SpecificDates'] = 'Pokaż terminy';
        $strings['Users'] = 'Użytkownicy';
        $strings['Guest'] = 'Gość';
        $strings['ResourceDisplayPrompt'] = 'Zasoby do wyświetlenia';
        $strings['Credits'] = 'Żetony';
        $strings['AvailableCredits'] = 'Dostępne żetony';
        $strings['CreditUsagePerSlot'] = 'Wymagane %s żetonów na miejsce(off peak)';
        $strings['PeakCreditUsagePerSlot'] = 'Wymagane %s żetonów na miejsce (peak)';
        $strings['CreditsRule'] = 'Niewystarczająca liczba żetonów. Potrzebne: %s. Posiadane: %s';
        $strings['PeakTimes'] = 'Peak Times';
        $strings['AllYear'] = 'Cały rok';
        $strings['MoreOptions'] = 'Więcej opcji';
        $strings['SendAsEmail'] = 'Wyślij jako e-mail';
        $strings['UsersInGroups'] = 'Użytkownicy grup';
        $strings['UsersWithAccessToResources'] = 'Użytkownicy z dostępem do zasobów';
        $strings['AnnouncementSubject'] = 'Nowe ogłoszenie zostało zamieszczone przez %s';
        $strings['AnnouncementEmailNotice'] = 'Użytkownicy otrzymają treść tego ogłoszenia e-mailem';
        $strings['Day'] = 'Dzień';
        $strings['NotifyWhenAvailable'] = 'Powiadom gdy dostępne';
        $strings['AddingToWaitlist'] = 'Dodajemy Cię do listy oczekujących';
        $strings['WaitlistRequestAdded'] = 'Poinformujemy Cię, gdy ten termin będzie dostępny.';
        $strings['PrintQRCode'] = 'Drukuj kod QR';
        $strings['FindATime'] = 'Znajdź termin';
        $strings['AnyResource'] = 'Dowolny zasób';
        $strings['ThisWeek'] = 'Ten tydzień';
        $strings['Hours'] = 'godzin';
        $strings['Minutes'] = 'minut';
        $strings['ImportICS'] = 'Importuj z pliku ICS';
        $strings['ImportQuartzy'] = 'Importuj z Quartzy';
        $strings['OnlyIcs'] = 'Dozwolone jest ładowanie wyłącznie plików *.ics.';
        $strings['IcsLocationsAsResources'] = 'Lokalizacje zostaną zaimportowane jako zasoby.';
        $strings['IcsMissingOrganizer'] = 'Zdarzenia bez organizatora będą przypisane aktualnemu użytkownikowi.';
        $strings['IcsWarning'] = 'Zasady rezerwacji nie będą wymuszane - możliwe kolizje terminów i duplikaty.';
        $strings['BlackoutAroundConflicts'] = 'Niedostępność obok kolidujących rezerwacji';
        $strings['DuplicateReservation'] = 'Duplikuj';
        $strings['UnavailableNow'] = 'Niedostępne w tej chwili';
        $strings['ReserveLater'] = 'Zarezerwuj później';
        $strings['CollectedFor'] = 'Collected For';
        $strings['IncludeDeleted'] = 'Dołącz usunięte rezerwacje';
        $strings['Deleted'] = 'Usunięte';
        $strings['Back'] = 'Wstecz';
        $strings['Forward'] = 'Dalej';
        $strings['DateRange'] = 'Zakres dat';
        $strings['Copy'] = 'Kopiuj';
        $strings['Detect'] = 'Wykryj';
        $strings['Autofill'] = 'Autowypełnianie';
        $strings['NameOrEmail'] = 'nazwa lub e-mail';
        $strings['ImportResources'] = 'Importuj zasoby';
        $strings['ExportResources'] = 'Eksportuj zasoby';
        $strings['ResourceImportInstructions'] = '<ul><li>Plik musi być zapisany w formacie CSV z kodowaniem UTF-8.</li><li>Nazwa jest polem wymaganym. Pozostawienie pozostałych pól pustych przypisze im wartości domyślne.</li><li>Dostępne statusy to: \'Dostępny\', \'Niedostępny\' i \'Ukryty\'.</li><li>Kolory w wartościach szesnastkowych, np. #ffffff.</li><li>Kolumny automatycznego przypisywania i zatwierdzania mogą mieć wartość prawda / fałsz.</li><li>Poprawność atrybutów nie będzie wymuszana.</li><li>Oddziel przecinkami nazwy wielu grup zasobów.</li><li>Czas trwania może być określony jako #d#h#m lub HH:mm (1d3h30m lub 27:30 dla 1 dnia, 3 godzin i 30 minut)</li><li>Użyj dostarczonego szablonu jako przykładu.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Plik musi być zapisany w formacie CSV z kodowaniem UTF-8.</li><li>E-mail, nazwy zasobów, początek i koniec są polami wymaganymi.</li><li>Początek i koniec wymagają podania pełnej daty i czasu. Zalecany format to YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Zasady, kolizje i właściwość przedziałów czasowych nie będą sprawdzane.</li><li>Powiadomienia nie będą wysyłane.</li><li>Poprawność atrybutów nie będzie wymuszana.</li><li>Oddziel przecinkami nazwy wielu zasobów.</li><li>Użyj dostarczonego szablonu jako przykładu.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Autorelease Minutes';
        $strings['CreditsPeak'] = 'Credits (peak)';
        $strings['CreditsOffPeak'] = 'Credits (off peak)';
        $strings['ResourceMinLengthCsv'] = 'Minimalny czas rezerwacji';
        $strings['ResourceMaxLengthCsv'] = 'Maksymalny czas rezerwacji';
        $strings['ResourceBufferTimeCsv'] = 'Czas przerwy';
        $strings['ResourceMinNoticeAddCsv'] = 'Reservation Add Minimum Notice';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Reservation Update Minimum Notice';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Reservation Delete Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';
        $strings['Export'] = 'Eksport';
        $strings['DeleteMultipleUserWarning'] = 'Usunięcie użytkowników spowoduje usunięcie ich przeszłych, bieżących i przyszłych rezerwacji. E-maile nie będą wysyłane.';
        $strings['DeleteMultipleReservationsWarning'] = 'E-maile nie będą wysyłane.';
        $strings['ErrorMovingReservation'] = 'Błąd przenoszenia rezerwacji';
        $strings['SelectUser'] = 'Wybierz użytkowników';
        $strings['InviteUsers'] = 'Zaproś użytkowników';
        $strings['InviteUsersLabel'] = 'Wprowadź adresy e-mail do wysyłki zaproszeń';
        $strings['ApplyToCurrentUsers'] = 'Zastosuj do bieżących użytkowników';
        $strings['ReasonText'] = 'Treść powodu';
        $strings['NoAvailableMatchingTimes'] = 'Brak dostępnych terminów spełniających podane';
        $strings['Schedules'] = 'Schedules';
        $strings['NotifyUser'] = 'Powiadom użytkownika';
        $strings['UpdateUsersOnImport'] = 'Zaktualizuj dane istniejącego użytkownika, jeśli e-mail już istnieje';
        $strings['UpdateResourcesOnImport'] = 'Zaktualizuj dane zasobu jeśli nazwa już istnieje';
        $strings['Reject'] = 'Odrzuć';
        $strings['CheckingAvailability'] = 'Sprawdzanie dostępności';
        $strings['CreditPurchaseNotEnabled'] = 'Wyłączona możliwość zakupu żetonów';
        $strings['CreditsCost'] = '1 żeton kosztuje';
        $strings['Currency'] = 'Waluta';
        $strings['PayPalClientId'] = 'Identyfikator klienta PayPal';
        $strings['PayPalSecret'] = 'Tajny klucz';
        $strings['PayPalEnvironment'] = 'Środowisko';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Live';
        $strings['StripePublishableKey'] = 'Klucz publikowalny';
        $strings['StripeSecretKey'] = 'Klucz tajny';
        $strings['CreditsUpdated'] = 'Zaktualizowano cenę żetonów';
        $strings['GatewaysUpdated'] = 'Zaktualizowano bramki płatności';
        $strings['PurchaseSummary'] = 'Podsumowanie zakupów';
        $strings['EachCreditCosts'] = '1 żeton kosztuje';
        $strings['Checkout'] = 'Do kasy';
        $strings['Quantity'] = 'Ilość';
        $strings['CreditPurchase'] = 'Zakup żetonów';
        $strings['EmptyCart'] = 'Twój koszyk jest pusty.';
        $strings['BuyCredits'] = 'Kup żetony';
        $strings['CreditsPurchased'] = 'Żetony zakupione.';
        $strings['ViewYourCredits'] = 'Pokaż żetony';
        $strings['TryAgain'] = 'Spróbuj ponownie';
        $strings['PurchaseFailed'] = 'Wystąpił problem z przetwarzaniem Twojej płatności.';
        $strings['NoteCreditsPurchased'] = 'Zakupione żetony';
        $strings['CreditsUpdatedLog'] = 'Żetony zaktualizowane przez %s';
        $strings['ReservationCreatedLog'] = 'Rezerwacja utworzona: Nr referencyjny: %s';
        $strings['ReservationUpdatedLog'] = 'Rezerwacja zmieniona: Nr referencyjny:%s';
        $strings['ReservationDeletedLog'] = 'Rezerwacja usunięta: Nr referencyjny:%s';
        $strings['BuyMoreCredits'] = 'Zakup więcej żetonów';
        $strings['Transactions'] = 'Transakcje';
        $strings['Cost'] = 'Koszt';
        $strings['PaymentGateways'] = 'Bramki płatności';
        $strings['CreditHistory'] = 'Historia';
        $strings['TransactionHistory'] = 'Historia transakcji';
        $strings['Date'] = 'Data';
        $strings['Note'] = 'Notatka';
        $strings['CreditsBefore'] = 'Żetonów przed';
        $strings['CreditsAfter'] = 'Żetonów po';
        $strings['TransactionFee'] = 'Opłata za transakcję';
        $strings['InvoiceNumber'] = 'Numer faktury';
        $strings['TransactionId'] = 'Identyfikator transakcji';
        $strings['Gateway'] = 'Bramka';
        $strings['GatewayTransactionDate'] = 'Data transakcji wg bramki';
        $strings['Refund'] = 'Zwrot';
        $strings['IssueRefund'] = 'Przyznaj zwrot';
        $strings['RefundIssued'] = 'Zwrot przyznano pomyślnie';
        $strings['RefundAmount'] = 'Kwota zwrotu';
        $strings['AmountRefunded'] = 'Zwrócono';
        $strings['FullyRefunded'] = 'Zwrócono w całości';
        $strings['YourCredits'] = 'Twoje żetony';
        $strings['PayWithCard'] = 'Zapłać kartą';
        $strings['or'] = 'lub';
        $strings['CreditsRequired'] = 'Wymagane żetony';
        $strings['AddToGoogleCalendar'] = 'Dodaj do kalendarza Google';
        $strings['Image'] = 'Obraz';
        $strings['ChooseOrDropFile'] = 'Wybierz plik lub przeciągnij tutaj';
        $strings['SlackBookResource'] = 'Zarezerwuj %s teraz';
        $strings['SlackBookNow'] = 'Rezerwuj teraz';
        $strings['SlackNotFound'] = 'Nie znaleziono zasobu o podanej nazwia. Rezerwuj teraz aby rozpocząć nową rezerwację.';
        $strings['AutomaticallyAddToGroup'] = 'Automatycznie dodawaj nowych użytkowników do tej grupy';
        $strings['GroupAutomaticallyAdd'] = 'Dodaj automatycznie';
        $strings['TermsOfService'] = 'Regulamin';
        $strings['EnterTermsManually'] = 'Wprowadź regulamin ręcznie';
        $strings['LinkToTerms'] = 'Link do regulaminu';
        $strings['UploadTerms'] = 'Załaduj regulamin';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Wymagaj znajomości regulaminu';
        $strings['UponReservation'] = 'W trakcie rezerwacji';
        $strings['UponRegistration'] = 'W trakcie rejestracji';
        $strings['ViewTerms'] = 'Pokaż regulamin';
        $strings['IAccept'] = 'Akceptuję';
        $strings['TheTermsOfService'] = 'regulamin';
        $strings['DisplayPage'] = 'Wyświetlaj na stronie';
        $strings['AvailableAllYear'] = 'All Year';
        $strings['Availability'] = 'Dostępność';
        $strings['AvailableBetween'] = 'Dostępne między';
        $strings['ConcurrentYes'] = 'Zasoby mogą być rezerwowane przez wiele osób jednocześnie';
        $strings['ConcurrentNo'] = 'Zasoby nie mogą być rezerwowane przez wiele osób jednocześnie';
        $strings['ScheduleAvailabilityEarly'] = ' This schedule is not yet available. It is available';
        $strings['ScheduleAvailabilityLate'] = 'This schedule is no longer available. It was available';
        $strings['ResourceImages'] = 'Resource Images';
        $strings['FullAccess'] = 'Pełny dostęp';
        $strings['ViewOnly'] = 'Tylko podgląd';
        $strings['Purge'] = 'Wyczyść';
        $strings['UsersWillBeDeleted'] = 'users will be deleted';
        $strings['BlackoutsWillBeDeleted'] = 'niedostępności zostaną usunięte';
        $strings['ReservationsWillBePurged'] = 'rezerwacje zostaną wyczyszczone';
        $strings['ReservationsWillBeDeleted'] = 'rezerwacje zostaną usunięte';
        $strings['PermanentlyDeleteUsers'] = 'Trwale usuń użytkowników, którzy nie logowali się od';
        $strings['DeleteBlackoutsBefore'] = 'Usuń niedostępności sprzed dnia';
        $strings['DeletedReservations'] = 'usunięte rezerwacje';
        $strings['DeleteReservationsBefore'] = 'Usuń rezerwacje sprzed dnia';
        $strings['SwitchToACustomLayout'] = 'Przełącz na niestandardowy rozkład';
        $strings['SwitchToAStandardLayout'] = 'Przełącz na standardowy rozkład';
        $strings['ThisScheduleUsesACustomLayout'] = 'Harmonogram używa niestandardowego rozkładu';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Harmonogram używa standardowego rozkładu';
        $strings['SwitchLayoutWarning'] = 'Czy na pewno chcesz zmienić typ rozkładu? To usunie wszystkie obecne zakresy czasowe.';
        $strings['DeleteThisTimeSlot'] = 'Usunąć ten zakres czasowy?';
        $strings['Refresh'] = 'Odśwież';
        $strings['ViewReservation'] = 'Pokaż rezerwację';
        $strings['PublicId'] = 'Public Id';
        $strings['Public'] = 'Public';
        $strings['AtomFeedTitle'] = '%s Rezerwacje';
        $strings['DefaultStyle'] = 'Domyślny styl';
        $strings['Standard'] = 'Standardowy';
        $strings['Wide'] = 'Szeroki';
        $strings['Tall'] = 'Wysoki';
        $strings['EmailTemplate'] = 'Szablon e-mail';
        $strings['SelectEmailTemplate'] = 'Wybierz szablon wiadomości';
        $strings['ReloadOriginalContents'] = 'Przeładuj oryginalną zawartość';
        $strings['UpdateEmailTemplateSuccess'] = 'Zaktualizowano szablon e-mail';
        $strings['UpdateEmailTemplateFailure'] = 'Nie udało się wprowadzić zmian w szablonie wiadomości. Upewnij się, że katalog jest zapisywalny.';
        $strings['BulkResourceDelete'] = 'Usuń wiele zasobów';
        $strings['NewVersion'] = 'Nowa wersja!';
        $strings['WhatsNew'] = 'Co nowego?';
        $strings['OnlyViewedCalendar'] = 'Ten harmonogram jest dostępny jedynie w widoku kalendarza';
        $strings['Grid'] = 'Siatka';
        $strings['List'] = 'Lista';
        $strings['NoReservationsFound'] = 'Nie znaleziono rezerwacji';
        $strings['EmailReservation'] = 'Rezerwacja przez e-mail';
        $strings['AdHocMeeting'] = 'Szybkie spotkanie';
        $strings['NextReservation'] = 'Następna rezerwacja';
        $strings['MissedCheckin'] = 'Bez zameldowania';
        $strings['MissedCheckout'] = 'Bez wymeldowania';
        $strings['Utilization'] = 'Użycie';
        $strings['SpecificTime'] = 'Określony czas';
        $strings['ReservationSeriesEndingPreference'] = 'Kiedy seria rezerwacji się kończy';
        $strings['NotAttending'] = 'Nie uczestniczy';
        $strings['ViewAvailability'] = 'Pokaż dostępność';
        $strings['ReservationDetails'] = 'Szczegóły rezerwacji';
        $strings['StartTime'] = 'Początek';
        $strings['EndTime'] = 'Koniec';
        $strings['New'] = 'Nowe';
        $strings['Updated'] = 'Zmienione';
        $strings['Custom'] = 'Niestandardowe';
        $strings['AddDate'] = 'Dodaj datę';
        $strings['RepeatOn'] = 'Powtórz';
        $strings['ScheduleConcurrentMaximum'] = 'Maksymalnie <b>%s</b> zasobów może być jednocześnie zarezerwowanych';
        $strings['ScheduleConcurrentMaximumNone'] = 'Brak ograniczeń liczby jednocześnie rezerwowanych zasobów';
        $strings['ScheduleMaximumConcurrent'] = 'Maksymalna liczba jednocześnie rezerwowanych zasobów';
        $strings['ScheduleMaximumConcurrentNote'] = 'Gdy ustawione, liczba jednocześnie rezerwowanych zasobów będzie ograniczona.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Każda rezerwacja jest ograniczona do <b>%s</b> zasobów';
        $strings['ScheduleResourcesPerReservationNone'] = 'Brak limitu zasobów na';
        $strings['ScheduleResourcesPerReservation'] = 'Maksymalna liczba zasobów na rezerwację';
        $strings['ResourceConcurrentReservations'] = 'Zezwój %s jednoczesnych rezerwacji';
        $strings['ResourceConcurrentReservationsNone'] = 'Nie zezwalaj na jednoczesne rezerwacje';
        $strings['AllowConcurrentReservations'] = 'Zezwól na jednoczesne rezerwacje';
        $strings['ResourceDisplayInstructions'] = 'Nie wybrano zasobu. Aby znaleźć adres zasobu, przejdź do Zarządzanie Aplikacją -> Harmonogramy. Zasób musi być publicznie dostępny';
        $strings['Owner'] = 'Właściciel';
        $strings['MaximumConcurrentReservations'] = 'Maksimum jednoczesnych rezerwacji';
        $strings['NotifyUsers'] = 'Powiadom użytkowników';
        $strings['Message'] = 'Wiadomość';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Ktokolwiek z rezerwacją w ciągu';
        $strings['ChangeResourceStatus'] = 'Zmień status zasobu';
        $strings['UpdateGroupsOnImport'] = 'Zmień istniejącą grupę jeśli nazwa zostanie odnaleziona';
        $strings['GroupsImportInstructions'] = '<ul><li>Wymagany plik w formacie CSV.</li><li>Name is required.</li><li>Listy uczestników powinny być listami e-maili rozdzielonych przecinkami.</li><li>Pusta lista uczestników nie zmieni grupy.</li><li>Permissions lists should be comma separated lists of resource names.</li><li>Empty permissions lists when updating groups will leave permissions unchanged.</li><li>Użyj dostarczonego szablonu jako przykładu.</li></ul>';
        $strings['PhoneRequired'] = 'Wymagany jest numer telefonu';
        $strings['OrganizationRequired'] = 'Wymagana nazwa organizacji';
        $strings['PositionRequired'] = 'Wymagana nazwa funkcji';
        $strings['GroupMembership'] = 'Członkostwo w grupie';
        $strings['AvailableGroups'] = 'Dostępne grupy';
        $strings['CheckingAvailabilityError'] = 'Nie otrzymano informacji o dostępności - zbyt wiele zasobów';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Zainstaluj LibreBooking';
        $strings['IncorrectInstallPassword'] = 'Wprowadzone hasło jest nieprawidłowe.';
        $strings['SetInstallPassword'] = 'Przed uruchomieniem instalacji należy ustawić hasło.';
        $strings['InstallPasswordInstructions'] = 'W %s zmień wartość %s na losowe i trudne do odgadnięcia hasło, następnie wróć do tej strony.<br/>Możesz użyć %s';
        $strings['NoUpgradeNeeded'] = 'LibreBooking jest aktualny. Nie ma potrzeby aktualizacji.';
        $strings['ProvideInstallPassword'] = 'Wprowadź hasło instalacji.';
        $strings['InstallPasswordLocation'] = 'Znajduje się w %s wewnątrz %s.';
        $strings['VerifyInstallSettings'] = 'Sprawdź poniższe ustawienia domyślne. Możesz je zmienić w %s.';
        $strings['DatabaseName'] = 'Nazwa bazy danych';
        $strings['DatabaseUser'] = 'Użytkownik bazy danych';
        $strings['DatabaseHost'] = 'Host bazy danych';
        $strings['DatabaseCredentials'] = 'Podaj dane użytkownika MySQL z uprawnieniami do tworzenia baz danych. Jeśli ich nie znasz, skontaktuj się z administratorem bazy danych. Najczęściej jest to root.';
        $strings['MySQLUser'] = 'Użytkownik MySQL';
        $strings['InstallOptionsWarning'] = 'Poniższe opcje prawdopodobnie nie będą działać w środowisku hostowanym. Użyj narzędzi kreatora MySQL aby wykonać poniższe czynności.';
        $strings['CreateDatabase'] = 'Utwórz bazę danych';
        $strings['CreateDatabaseUser'] = 'Utwórz użytkownika bazy danych';
        $strings['PopulateExampleData'] = 'Import przykładowych danych. Tworzy konto administratora: admin/password oraz konto użytkownika: user/password';
        $strings['DataWipeWarning'] = 'Uwaga: Wszystkie istniejące dane zostaną usunięte!';
        $strings['RunInstallation'] = 'Uruchom instalację';
        $strings['UpgradeNotice'] = 'Aktualizacja z wersji <b>%s</b> do wersji <b>%s</b>';
        $strings['RunUpgrade'] = 'Uruchom aktualizację';
        $strings['Executing'] = 'Wykonywanie';
        $strings['StatementFailed'] = 'Niepowodzenie. Szczegóły:';
        $strings['SQLStatement'] = 'Zapytanie SQL:';
        $strings['ErrorCode'] = 'Kod błędu:';
        $strings['ErrorText'] = 'Opis błędu:';
        $strings['InstallationSuccess'] = 'Instalacja zakończona pomyślnie!';
        $strings['RegisterAdminUser'] = 'Zarejestruj użytkownika będącego administratorem. Czynnosć jest wymagana, jeśli nie importowano przykładowych danych. Upewnij się, że parametr $conf[\'settings\'][\'allow.self.registration\'] w pliku %s. ma wartość \'true\'';
        $strings['LoginWithSampleAccounts'] = 'Jeśli zaimportowano przykładowe wpisy do bazy, możesz zalogować się używając danych admin/password dla administratora lub user/password dla uzytkownika podstawowego.';
        $strings['InstalledVersion'] = 'Korzystasz z LibreBooking w wersji %s.';
        $strings['InstallUpgradeConfig'] = 'Zaleca się uaktualnienie pliku konfiguracyjnego';
        $strings['InstallationFailure'] = 'Podczas instalacji napotkano problemy. Popraw je i uruchom instalację ponownie.';
        $strings['ConfigureApplication'] = 'Skonfiguruj LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Twój plik konfiguracji jest aktualny!';
        $strings['ConfigUpdateFailure'] = 'Nie udało się automatycznie zaktualizować Twojego pliku konfiguracyjnego. Nadpisz plik config.php poniższymi danymi:';
        $strings['ScriptUrlWarning'] = 'Parametr <em>script.url</em> może być niepoprawny. Obecna wartość to <strong>%s</strong>, jednak możliwe że powinna to być <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Nie możemy odnaleźć twojej nazwy użytkownika lub hasła';
        $strings['ReservationFailed'] = 'Twoja rezerwacja nie mogła zostać utworzona';
        $strings['MinNoticeError'] = 'Ta rezerwacja wymaga dodatkowej uwagi.  Najwcześniejsza data, która może być zarezerwowana to %s.';
        $strings['MinNoticeErrorUpdate'] = 'Zmiana tej rezerwacji wymaga powiadomienia z wyprzedzeniem.
                                            Rezerwacje zaczynające się przed %s nie mogą być zmienione.';
        $strings['MinNoticeErrorDelete'] = 'Odwołanie tej rezerwacji wymaga powiadomienia z wyprzedzeniem. 
                                            Rezerwacje zaczynające się przed %s nie mogą być odwołane.';
        $strings['MaxNoticeError'] = 'Ta rezerwacja nie może być utworzona w tak odległej przyszłości.  Najdalszą datą jest %s.';
        $strings['MinDurationError'] = 'Ta rezerwacja musi trwać co najmniej %s.';
        $strings['MaxDurationError'] = 'Ta rezerwacja nie może trwać dłużej niż %s.';
        $strings['ConflictingAccessoryDates'] = 'Brak wymaganej ilości akcesoriów:';
        $strings['NoResourcePermission'] = 'Nie posiadasz uprawnień do co najmniej jednego zasobu z wybranych';
        $strings['ConflictingReservationDates'] = 'Istnieją konflikty rezerwacji w podanych dniach:';
        $strings['InstancesOverlapRule'] = 'Rezerwacje nakładają się na siebie:';
        $strings['StartDateBeforeEndDateRule'] = 'Data rozpoczęcia musi być wcześniejsza niż data zakończenia';
        $strings['StartIsInPast'] = 'Data rozpoczęcia nie może być datą z przeszłości';
        $strings['EmailDisabled'] = 'Administrator wyłączył powiadomienia mailowe';
        $strings['ValidLayoutRequired'] = 'Wpisy muszą być uzupełnione dla wszystkich 24 godzin od rozpoczęcia dnia aż do północy.';
        $strings['CustomAttributeErrors'] = 'Są problemy z dodatkowymi atrybutami, które podano:';
        $strings['CustomAttributeRequired'] = '%s jest polem wymaganym';
        $strings['CustomAttributeInvalid'] = 'Wartość podana dla %s jest nieprawidłowa';
        $strings['AttachmentLoadingError'] = 'Przepraszamy, ale wystąpił problem podczas ładownia żądanego plik.';
        $strings['InvalidAttachmentExtension'] = 'Możesz dodawać pliki tylko typu: %s';
        $strings['InvalidStartSlot'] = 'Data i godzina rozpoczęcia jest nieprawidłowa.';
        $strings['InvalidEndSlot'] = 'Data i godzina zakończenia jest nieprawidłowa.';
        $strings['MaxParticipantsError'] = '%s zezwala na udział nie więcej niż %s uczestników.';
        $strings['ReservationCriticalError'] = 'Wystąpił krytyczny błąd podczas zapisywania rezerwacji. Jeśli problem się powtórzy, skontaktuj się z administratorem.';
        $strings['InvalidStartReminderTime'] = 'Czas przypomnienia o rozpoczęciu jest nieprawidłowy.';
        $strings['InvalidEndReminderTime'] = 'czas przypomnienia o zakończeniu jest nieprawidłowy.';
        $strings['QuotaExceeded'] = 'Przekroczono limit.';
        $strings['MultiDayRule'] = '%s nie zezwala na rezerwacje pomiędzy dniami.';
        $strings['InvalidReservationData'] = 'Wystąpił problem z Twoim żądaniem rezerwacji.';
        $strings['PasswordError'] = 'Hasło musi zawierać co najmniej %s liter oraz co najmniej %s cyfr.';
        $strings['PasswordErrorRequirements'] = 'Hasło musi zawierać kombinację co najmniej %s wielkich i małych liter oraz %s cyfr.';
        $strings['NoReservationAccess'] = 'Nie masz uprawnień do zmiany tej rezerwacji.';
        $strings['PasswordControlledExternallyError'] = 'Twoje hasło jest zarządzane przez zewnętrzny system i nie może być zmienione tutaj.';
        $strings['AccessoryResourceRequiredErrorMessage'] = '%s może być zarezerwowane wyłącznie z zasobami %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Musisz zarezerwować co najmniej %s sztuk %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Nie możesz zarezerwować więcej niż %s sztuk %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = '\'%s\' nie może być zarezerwowane wraz z żądanymi zasobami';
        $strings['NoResources'] = 'Nie dodano żadnych zasobów.';
        $strings['ParticipationNotAllowed'] = 'Nie masz uprawnień do dołączenia do tej rezerwacji.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Nie możesz zameldować się do tej rezerwacji.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Nie możesz wymeldować się z tej rezerwacji.';
        $strings['InvalidEmailDomain'] = 'Adres e-mail nie należy do uprawnionych domen';
        $strings['TermsOfServiceError'] = 'Musisz zaakceptować warunki świadczenia usług';
        $strings['UserNotFound'] = 'Nie znaleziono użytkownika';
        $strings['ScheduleAvailabilityError'] = 'Harmonogram jest dostępny od %s do %s';
        $strings['ReservationNotFoundError'] = 'Nie znaleziono rezerwacji';
        $strings['ReservationNotAvailable'] = 'Rezerwacja niedostępna';
        $strings['TitleRequiredRule'] = 'Wymagany tytuł rezerwacji';
        $strings['DescriptionRequiredRule'] = 'Wymagany opis rezerwacji';
        $strings['WhatCanThisGroupManage'] = 'Czym zarządza ta grupa?';
        $strings['ReservationParticipationActivityPreference'] = 'Kiedy ktoś dołącza do mojej rezerwacji lub ją opuszcza';
        $strings['RegisteredAccountRequired'] = 'Tylko zarejestrowani użytkownicy mogą składać rezerwacje';
        $strings['InvalidNumberOfResourcesError'] = 'Maksymalna liczba zasobów w pojedynczej rezerwacji to %s';
        $strings['ScheduleTotalReservationsError'] = 'Ten harmonogram zezwala na jednoczesną rezerwację %s zasobów. Rezerwacja przekroczy limit harmonogramu w podanych terminach:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Tworzenie rezerwacji';
        $strings['EditReservation'] = 'Edycja rezerwacji';
        $strings['LogIn'] = 'Logowanie';
        $strings['ManageReservations'] = 'Rezerwacje';
        $strings['AwaitingActivation'] = 'W oczekiwaniu na aktywację';
        $strings['PendingApproval'] = 'W oczekiwaniu na zatwierdzenie';
        $strings['ManageSchedules'] = 'Harmonogramy';
        $strings['ManageResources'] = 'Zasoby';
        $strings['ManageAccessories'] = 'Akcesoria';
        $strings['ManageUsers'] = 'Użytkownicy';
        $strings['ManageGroups'] = 'Grupy';
        $strings['ManageQuotas'] = 'Limity';
        $strings['ManageBlackouts'] = 'Niedostępności';
        $strings['MyDashboard'] = 'Moja strona';
        $strings['ServerSettings'] = 'Ustawienia serwera';
        $strings['Dashboard'] = 'Strona główna';
        $strings['Help'] = 'Pomoc';
        $strings['Administration'] = 'Administracja';
        $strings['About'] = 'O LibreBooking';
        $strings['Bookings'] = 'Terminarze';
        $strings['Schedule'] = 'Harmonogram';
        $strings['Account'] = 'Konto';
        $strings['EditProfile'] = 'Edycja mojego profilu';
        $strings['FindAnOpening'] = 'Znajdowanie otwarcia';
        $strings['OpenInvitations'] = 'Otwórz zaproszenia';
        $strings['ResourceCalendar'] = 'Kalendarz zasobu';
        $strings['Reservation'] = 'Nowa rezerwacja';
        $strings['Install'] = 'Instalacja';
        $strings['ChangePassword'] = 'Zmiana hasła';
        $strings['MyAccount'] = 'Moje konto';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Zarządzanie aplikacją';
        $strings['ForgotPassword'] = 'Przypomnienie hasła';
        $strings['NotificationPreferences'] = 'Ustawienia powiadomień';
        $strings['ManageAnnouncements'] = 'Ogłoszenia';
        $strings['Responsibilities'] = 'Obowiązki';
        $strings['GroupReservations'] = 'Rezerwacje grupowe';
        $strings['ResourceReservations'] = 'Rezerwacje zasobów';
        $strings['Customization'] = 'Dostosowywanie';
        $strings['Attributes'] = 'Atrybuty';
        $strings['AccountActivation'] = 'Aktywacja konta';
        $strings['ScheduleReservations'] = 'Zaplanuj rezerwacje';
        $strings['Reports'] = 'Raporty';
        $strings['GenerateReport'] = 'Utwórz nowy raport';
        $strings['MySavedReports'] = 'Moje zapisane raporty';
        $strings['CommonReports'] = 'Wspólne raporty';
        $strings['ViewDay'] = 'Podgląd dnia';
        $strings['Group'] = 'Grupa';
        $strings['ManageConfiguration'] = 'Konfiguracja aplikacji';
        $strings['LookAndFeel'] = 'Wygląd';
        $strings['ManageResourceGroups'] = 'Grupy zasobów';
        $strings['ManageResourceTypes'] = 'Typy zasobów';
        $strings['ManageResourceStatus'] = 'Statusy rezerwacji';
        $strings['ReservationColors'] = 'Kolory rezerwacji';
        $strings['SearchReservations'] = 'Przeszukaj rezerwacje';
        $strings['ManagePayments'] = 'Płatności';
        $strings['ViewCalendar'] = 'Podgląd kalendarza';
        $strings['DataCleanup'] = 'Czyszczenie danych';
        $strings['ManageEmailTemplates'] = 'Zarządzaj szablonami e-maili';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'N';
        $strings['DayMondaySingle'] = 'P';
        $strings['DayTuesdaySingle'] = 'W';
        $strings['DayWednesdaySingle'] = 'Ś';
        $strings['DayThursdaySingle'] = 'C';
        $strings['DayFridaySingle'] = 'P';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'N';
        $strings['DayMondayAbbr'] = 'Pn';
        $strings['DayTuesdayAbbr'] = 'Wt';
        $strings['DayWednesdayAbbr'] = 'Śr';
        $strings['DayThursdayAbbr'] = 'Cz';
        $strings['DayFridayAbbr'] = 'Pt';
        $strings['DaySaturdayAbbr'] = 'So';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Twoja rezerwacja została zatwierdzona';
        $strings['ReservationCreatedSubject'] = 'Twoja rezerwacja została utworzona';
        $strings['ReservationUpdatedSubject'] = 'Twoja rezerwacja została zaktualizowana';
        $strings['ReservationDeletedSubject'] = 'Twoja rezerwacja została usunięta';
        $strings['ReservationCreatedAdminSubject'] = 'Powiadomienie: rezerwacja została utworzona';
        $strings['ReservationUpdatedAdminSubject'] = 'Powiadomienie: rezerwacja została zaktualizowana';
        $strings['ReservationDeleteAdminSubject'] = 'Powiadomienie: rezerwacja została usunięta';
        $strings['ReservationApprovalAdminSubject'] = 'Powiadomienie: Rezerwacja wymaga Twojego potwierdzenia';
        $strings['ParticipantAddedSubject'] = 'Zgłoszenie udziału uczestnictwa';
        $strings['ParticipantDeletedSubject'] = 'Rezerwacja usunięta';
        $strings['InviteeAddedSubject'] = 'Zaproszenie do uczestnictwa';
        $strings['ResetPasswordRequest'] = 'Żądanie zmiany hasła';
        $strings['ActivateYourAccount'] = 'Proszę aktywować Swoje konto';
        $strings['ReportSubject'] = 'Twój żądany raport (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Rezerwacja %s za chwilę się zacznie';
        $strings['ReservationEndingSoonSubject'] = 'Rezerwacja %s niedługo się kończy';
        $strings['UserAdded'] = 'Dodano nowego użytkownika';
        $strings['UserDeleted'] = 'Konto użytkownika %s zostało usunięte przez %s';
        $strings['GuestAccountCreatedSubject'] = 'Szczegóły konta %s';
        $strings['AccountCreatedSubject'] = 'Szczegóły konta %s';
        $strings['InviteUserSubject'] = '%s zaprosił cię do dołączenia do %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Rezerwacja %s została zatwierdzona';
        $strings['ReservationCreatedSubjectWithResource'] = 'Rezerwacja %s została utworzona';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Rezerwacja %s została zmieniona';
        $strings['ReservationDeletedSubjectWithResource'] = 'Rezerwacja %s została usunięta';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Powiadomienie: Utworzono rezerwację dla %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Powiadomienie: Rezerwacja dla %s została zmieniona';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Powiadomienie: Rezerwacja dla %s została usunięta';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Powiadomienie: Rezerwacja dla %s wymaga Twojego potwierdzenia';
        $strings['ParticipantAddedSubjectWithResource'] = '%s dodał Cię do rezerwacji dla %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s zmienił rezerwację dla %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s usunął rezerwację dla %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s zaprosił cię do rezerwacji dla %s';
        $strings['MissedCheckinEmailSubject'] = 'Nie zameldowano w %s';
        $strings['ReservationShareSubject'] = '%s udostępnił rezerwację %s';
        $strings['ReservationSeriesEndingSubject'] = 'Seria rezerwacji %s kończy się %s';
        $strings['ReservationParticipantAccept'] = 'Użytkownik %s zaakceptował zaproszenie do rezerwacji %s w terminie %s';
        $strings['ReservationParticipantDecline'] = 'Użytkownik %s odrzucił zaproszenie do rezerwacji %s w terminie %s';
        $strings['ReservationParticipantJoin'] = 'Użytkownik %s dołączył do Twojej rezerwacji %s w terminie %s';
        $strings['ReservationAvailableSubject'] = '%s jest dostępny w terminie %s';
        $strings['ResourceStatusChangedSubject'] = 'Dostępność %s zmieniła się';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Nie masz żadnych wcześniejszych rezerwacji';
        $strings['PastReservations'] = 'Wcześniejsze rezerwacje';
        $strings['AllNoPastReservations'] = 'Nie ma wcześniejszych rezerwacji w ciągu ostatnich %s dni';
        $strings['AllPastReservations'] = 'Wszystkie wcześniejsze rezerwacje';
        $strings['Yesterday'] = 'Wczoraj';
        $strings['EarlierThisWeek'] = 'Wcześniej w tym tygodniu';
        $strings['PreviousWeek'] = 'Poprzedni tydzień';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Twoja grupa nie ma żadnych nadchodzących rezerwacji';
        $strings['GroupUpcomingReservations'] = 'Nadchodzące rezerwacje mojej grupy(y)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Wystąpił błąd podczas logowania przez Facebook. Spróbuj ponownie.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Nie masz żadnych rezerwacji oczekujących na zatwierdzenie';
        $strings['PendingApprovalReservations'] = 'Rezerwacje oczekujące na zatwierdzenie';
        $strings['LaterThisMonth'] = 'Później w tym miesiącu';
        $strings['LaterThisYear'] = 'Później w tym roku';
        $strings['Remaining'] = 'Pozostałe';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Brak brakujących rezerwacji wymeldowania';
        $strings['MissingCheckOutReservations'] = 'Brakujące rezerwacje wymeldowania';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Nie można zobaczyć szczegółów rezerwacji, ponieważ nie masz uprawnień do żadnych zasobów w tej rezerwacji';
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
        $days['full'] = ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'];
        // The three letter abbreviation
        $days['abbr'] = ['Nie', 'Pon', 'Wto', 'Śro', 'Czw', 'Pią', 'Sob'];
        // The two letter abbreviation
        $days['two'] = ['Nd', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So'];
        // The one letter abbreviation
        $days['letter'] = ['N', 'P', 'W', 'Ś', 'C', 'P', 'S'];

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
        $months['full'] = ['Styczeń', 'Luty', 'Marzec', 'Kwieceń', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'];
        // The three letter month name
        $months['abbr'] = ['Sty', 'Lut', 'Mar', 'Kwi', 'Maj', 'Cze', 'Lip', 'Sie', 'Wrz', 'Paź', 'Lis', 'Gru'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['A', 'Ą', 'B', 'C', 'Ć', 'D', 'E', 'Ę', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'Ł', 'M', 'N', 'Ń', 'O', 'Ó', 'P', 'Q', 'R', 'S', 'Ś', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'Ż', 'Ź'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'pl';
    }
}
