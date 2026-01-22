<?php

require_once('Language.php');
require_once('en_gb.php');

class el_gr extends en_gb
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

        $dates['general_date'] = 'd/m/Y';
        $dates['general_datetime'] = 'd/m/Y g:i:s A';
        $dates['short_datetime'] = 'j/n/y g:i A';
        $dates['schedule_daily'] = 'l, d/m/Y';
        $dates['reservation_email'] = 'd/m/Y @ g:i A (e)';
        $dates['res_popup'] = 'D, d/n g:i A';
        $dates['res_popup_time'] = 'g:i A';
        $dates['short_reservation_date'] = 'j/n/y g:i A';
        $dates['dashboard'] = 'D, d/n g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['mobile_reservation_date'] = 'j/n g:i A';
        $dates['general_date_js'] = 'dd/mm/yy';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'D/M/YY h:mm A';
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

        $strings['FirstName'] = 'Όνομα';
        $strings['LastName'] = 'Επώνυμο';
        $strings['Timezone'] = 'Ζώνη ώρας';
        $strings['Edit'] = 'Τροποποίηση';
        $strings['Change'] = 'Αλλαγή';
        $strings['Rename'] = 'Μετονομασία';
        $strings['Remove'] = 'Αφαίρεση';
        $strings['Delete'] = 'Διαγραφή';
        $strings['Update'] = 'Ενημέρωση';
        $strings['Cancel'] = 'Άκυρο';
        $strings['Add'] = 'Προσθήκη';
        $strings['Name'] = 'Όνομα';
        $strings['Yes'] = 'Ναι';
        $strings['No'] = 'Όχι';
        $strings['FirstNameRequired'] = 'Απαιτείται το όνομα.';
        $strings['LastNameRequired'] = 'Απαιτείται το επώνυμο.';
        $strings['PwMustMatch'] = 'Η επανάληψη του συνθηματικού θα πρέπει να είναι ίδια με το συνθηματικό.';
        $strings['ValidEmailRequired'] = 'Απαιτείται μια έγκυρη διεύθυνση email.';
        $strings['UniqueEmailRequired'] = 'Αυτό το email υπάρχει ήδη καταχωρισμένο.';
        $strings['UniqueUsernameRequired'] = 'Αυτό το όνομα χρήστη υπάρχει ήδη καταχωρισμένο.';
        $strings['UserNameRequired'] = 'Απαιτείται το όνομα χρήστη.';
        $strings['CaptchaMustMatch'] = 'Απαιτείται το CAPTCHA.';
        $strings['Today'] = 'Σήμερα';
        $strings['Week'] = 'Εβδομάδα';
        $strings['Month'] = 'Μήνας';
        $strings['BackToCalendar'] = 'Πίσω στο ημερολόγιο';
        $strings['BeginDate'] = 'Έναρξη';
        $strings['EndDate'] = 'Λήξη';
        $strings['Username'] = 'Όνομα χρήστη';
        $strings['Password'] = 'Συνθηματικό';
        $strings['PasswordConfirmation'] = 'Επιβεβαίωση συνθηματικού';
        $strings['DefaultPage'] = 'Αρχική σελίδα';
        $strings['MyCalendar'] = 'Το ημερολόγιό μου';
        $strings['ScheduleCalendar'] = 'Ημερολόγιο Προγραμματισμού';
        $strings['Registration'] = 'Εγγραφή';
        $strings['NoAnnouncements'] = 'Δεν υπάρχουν ανακοινώσεις';
        $strings['Announcements'] = 'Ανακοινώσεις';
        $strings['NoUpcomingReservations'] = 'Δεν έχετε προσεχείς κρατήσεις';
        $strings['UpcomingReservations'] = 'Προσεχείς Κρατήσεις';
        $strings['AllNoUpcomingReservations'] = 'Δεν υπάρχουν προσεχείς κρατήσεις στις επόμενες %s ημέρες';
        $strings['AllUpcomingReservations'] = 'Όλες οι προσεχείς κρατήσεις';
        $strings['ShowHide'] = 'Εμφάνιση/Απόκρυψη';
        $strings['Error'] = 'Σφάλμα';
        $strings['ReturnToPreviousPage'] = 'Επιστροφή στην τελευταία σελίδα που βρισκόσασταν';
        $strings['UnknownError'] = 'Άγνωστο σφάλμα';
        $strings['InsufficientPermissionsError'] = 'Δεν έχετε την εξουσιοδότηση για την προσπέλαση αυτού του πόρου';
        $strings['MissingReservationResourceError'] = 'Δεν επιλέχθηκε πόρος';
        $strings['MissingReservationScheduleError'] = 'Δεν επιλέχθηκε πρόγραμμα';
        $strings['DoesNotRepeat'] = 'Δεν επαναλαμβάνεται';
        $strings['Daily'] = 'Καθημερινά';
        $strings['Weekly'] = 'Εβδομαδιαία';
        $strings['Monthly'] = 'Μηνιαία';
        $strings['Yearly'] = 'Ετήσια';
        $strings['RepeatPrompt'] = 'Επανάληψη';
        $strings['hours'] = 'ώρες';
        $strings['days'] = 'ημέρες';
        $strings['weeks'] = 'εβδομάδες';
        $strings['months'] = 'μήνες';
        $strings['years'] = 'έτη';
        $strings['day'] = 'ημέρα';
        $strings['week'] = 'εβδομάδα';
        $strings['month'] = 'μήνας';
        $strings['year'] = 'έτος';
        $strings['repeatDayOfMonth'] = 'ημέρα του μήνα';
        $strings['repeatDayOfWeek'] = 'ημέρα της εβδομάδας';
        $strings['RepeatUntilPrompt'] = 'Μέχρι';
        $strings['RepeatEveryPrompt'] = 'Κάθε';
        $strings['RepeatDaysPrompt'] = 'Στις';
        $strings['CreateReservationHeading'] = 'Νέα κράτηση';
        $strings['EditReservationHeading'] = 'Τροποποίηση Κράτησης %s';
        $strings['ViewReservationHeading'] = 'Προβολή Κράτησης %s';
        $strings['ReservationErrors'] = 'Αλλαγή Κράτησης';
        $strings['Create'] = 'Δημιουργία';
        $strings['ThisInstance'] = 'Μόνο αυτό το στιγμιότυπο';
        $strings['AllInstances'] = 'Όλα τα στιγμιότυπα';
        $strings['FutureInstances'] = 'Μελλοντικά στιγμιότυπα';
        $strings['Print'] = 'Εκτύπωση';
        $strings['ShowHideNavigation'] = 'Εμφάνιση/Απόκρυψη Πλοήγησης';
        $strings['ReferenceNumber'] = 'Αριθμός Αναφοράς';
        $strings['Tomorrow'] = 'Αύριο';
        $strings['LaterThisWeek'] = 'Αργότερα στην εβδομάδα';
        $strings['NextWeek'] = 'Επόμενη εβδομάδα';
        $strings['SignOut'] = 'Αποσύνδεση';
        $strings['LayoutDescription'] = 'Αρχίζει στις %s, εμφάνιση %s ημερών τη φορά';
        $strings['AllResources'] = 'Όλοι οι πόροι';
        $strings['TakeOffline'] = 'Κατάσταση εκτός λειτουργίας';
        $strings['BringOnline'] = 'Κατάσταση σε λειτουργία';
        $strings['AddImage'] = 'Προσθήκη εικόνας';
        $strings['NoImage'] = 'Δεν υπάρχει εικόνα';
        $strings['Move'] = 'Μετακίνηση';
        $strings['AppearsOn'] = 'Εμφανίζει στις %s';
        $strings['Location'] = 'Τοποθεσία';
        $strings['NoLocationLabel'] = '(χωρίς τοποθεσία)';
        $strings['Contact'] = 'Επικοινωνία';
        $strings['NoContactLabel'] = '(χωρίς πληροφορίες επικοινωνίας)';
        $strings['Description'] = 'Περιγραφή';
        $strings['NoDescriptionLabel'] = '(χωρίς περιγραφή)';
        $strings['Notes'] = 'Σημειώσεις';
        $strings['NoNotesLabel'] = '(χωρίς σημειώσεις)';
        $strings['NoTitleLabel'] = '(χωρίς τίτλο)';
        $strings['UsageConfiguration'] = 'Παραμετροποίηση Χρήσης';
        $strings['ChangeConfiguration'] = 'Αλλαγή παραμετροποίησης';
        $strings['ResourceMinLength'] = 'Οι κρατήσεις θα πρέπει να διαρκούν τουλάχιστον %s';
        $strings['ResourceMinLengthNone'] = 'Δεν υπάρχει ελάχιστη διάρκεια κράτησης';
        $strings['ResourceMaxLength'] = 'Οι κρατήσεις δεν μπορούν να διαρκούν περισσότερο από %s';
        $strings['ResourceMaxLengthNone'] = 'Δεν υπάρχει μέγιστη διάρκεια κράτησης';
        $strings['ResourceRequiresApproval'] = 'Οι κρατήσεις θα πρέπει να εγκριθούν';
        $strings['ResourceRequiresApprovalNone'] = 'Οι κρατήσεις δεν απαιτούν έγκριση';
        $strings['ResourcePermissionAutoGranted'] = 'Γίνεται αυτόματη εκχώριση της άδειας';
        $strings['ResourcePermissionNotAutoGranted'] = 'Δεν γίνεται αυτόματη εκχώριση της άδειας';
        $strings['ResourceMinNotice'] = 'Οι Κρατήσεις θα πρέπει να γίνονται τουλάχιστον %s πριν την έναρξη';
        $strings['ResourceMinNoticeNone'] = 'Οι Κρατήσεις μπορούν να γίνονται μέχρι την τρέχουσα ώρα';
        $strings['ResourceMinNoticeUpdate'] = 'Οι Κρατήσεις πρέπει να ενημερώνονται τουλάχιστον %s πριν το χρόνο έναρξης';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Οι Κρατήσεις μπορούν να ενημερώνονται το πολύ μέχρι την τρέχουσα ώρα';
        $strings['ResourceMinNoticeDelete'] = 'Οι Κρατήσεις πρέπει να διαγράφονται τουλάχιστον %s πριν το χρόνο έναρξης';
        $strings['ResourceMinNoticeNoneDelete'] = 'Οι Κρατήσεις μπορούν να διαγράφονται το πολύ μέχρι την τρέχουσα ώρα';
        $strings['ResourceMaxNotice'] = 'Οι Κρατήσεις δεν μπορούν να τελειώνουν πιο μετά από %s από την τρέχουσα ώρα';
        $strings['ResourceMaxNoticeNone'] = 'Οι Κρατήσεις μπορούν να τελειώνουν οποιαδήποτε στιγμή στο μέλλον';
        $strings['ResourceBufferTime'] = 'Πρέπει να υπάρχει %s μεταξύ των κρατήσεων';
        $strings['ResourceBufferTimeNone'] = 'Δεν υπάρχει κενό μεταξύ των κρατήσεων';
        $strings['ResourceAllowMultiDay'] = 'Οι κρατήσεις μπορούν να γίνονται μεταξύ ημερών';
        $strings['ResourceNotAllowMultiDay'] = 'Οι κρατήσεις δεν μπορούν να γίνονται μεταξύ ημερών';
        $strings['ResourceCapacity'] = 'Αυτός ο πόρος έχει χωρητικότητα %s ανθρώπων';
        $strings['ResourceCapacityNone'] = 'Αυτός ο πόρος έχει απεριόριστη χωρητικότητα';
        $strings['AddNewResource'] = 'Προσθήκη Νέου Πόρου';
        $strings['AddNewUser'] = 'Προσθήκη Νέου Χρήστη';
        $strings['AddResource'] = 'Προσθήκη Πόρου';
        $strings['Capacity'] = 'Χωρητικότητα';
        $strings['Access'] = 'Πρόσβαση';
        $strings['Duration'] = 'Διάρκεια';
        $strings['Active'] = 'Ενεργοί';
        $strings['Inactive'] = 'Ανενεργοί';
        $strings['ResetPassword'] = 'Επαναφορά Συνθηματικού';
        $strings['LastLogin'] = 'Τελευταία είσοδος';
        $strings['Search'] = 'Αναζήτηση';
        $strings['ResourcePermissions'] = 'Δικαιώματα Πόρων';
        $strings['Reservations'] = 'Κρατήσεις';
        $strings['Groups'] = 'Ομάδες';
        $strings['Users'] = 'Χρήστες';
        $strings['AllUsers'] = 'Όλοι οι Χρήστες';
        $strings['AllGroups'] = 'Όλες οι Ομάδες';
        $strings['AllSchedules'] = 'Όλοι οι Προγραμματισμοί';
        $strings['UsernameOrEmail'] = 'Όνομα χρήστη ή Email';
        $strings['Members'] = 'Μέλη';
        $strings['QuickSlotCreation'] = 'Δημιουργία κενών θέσεων κάθε %s λεπτά μεταξύ %s και %s';
        $strings['ApplyUpdatesTo'] = 'Εφαρμογή Ενημερώσεων Σε';
        $strings['CancelParticipation'] = 'Ακύρωση Συμμετοχής';
        $strings['Attending'] = 'Παρακολούθηση';
        $strings['QuotaConfiguration'] = 'Στο %s για %s οι χρήστες στο %s περιορίζονται σε %s %s ανά %s';
        $strings['QuotaEnforcement'] = 'Εφαρμόζεται %s %s';
        $strings['reservations'] = 'κρατήσεις';
        $strings['reservation'] = 'κράτηση';
        $strings['ChangeCalendar'] = 'Αλλαγή Ημερολογίου';
        $strings['AddQuota'] = 'Προσθήκη Ποσόστωσης';
        $strings['FindUser'] = 'Εύρεση Χρήστη';
        $strings['Created'] = 'Δημιουργήθηκε';
        $strings['LastModified'] = 'Τελευταία τροποποίηση';
        $strings['GroupName'] = 'Όνομα ομάδας';
        $strings['GroupMembers'] = 'Μέλη Ομάδας';
        $strings['GroupRoles'] = 'Ρόλοι Ομάδας';
        $strings['GroupAdmin'] = 'Διαχειριστής Ομάδας';
        $strings['Actions'] = 'Ενέργειες';
        $strings['CurrentPassword'] = 'Τρέχον συνθηματικό';
        $strings['NewPassword'] = 'Νέο συνθηματικό';
        $strings['InvalidPassword'] = 'Το τρέχον συνθηματικό σας είναι λάθος';
        $strings['PasswordChangedSuccessfully'] = 'Το συνθηματικό σας άλλαξε με επιτυχία';
        $strings['SignedInAs'] = 'Έχει γίνει σύνδεση ως';
        $strings['NotSignedIn'] = 'Δεν έχετε συνδεθεί';
        $strings['ReservationTitle'] = 'Τίτλος κράτησης';
        $strings['ReservationDescription'] = 'Περιγραφή κράτησης';
        $strings['ResourceList'] = 'Πόροι για κράτηση';
        $strings['Accessories'] = 'Εξοπλισμός';
        $strings['InvitationList'] = 'Καλεσμένοι';
        $strings['AccessoryName'] = 'Όνομα εξοπλισμού';
        $strings['QuantityAvailable'] = 'Διαθέσιμη ποσότητα';
        $strings['Resources'] = 'Πόροι';
        $strings['Participants'] = 'Συμμετέχοντες';
        $strings['User'] = 'Χρήστης';
        $strings['Resource'] = 'Πόρος';
        $strings['Status'] = 'Κατάσταση';
        $strings['Approve'] = 'Έγκριση';
        $strings['Page'] = 'Σελίδα';
        $strings['Rows'] = 'Γραμμές';
        $strings['Unlimited'] = 'Χωρίς όριο';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Διεύθυνση Email';
        $strings['Phone'] = 'Τηλέφωνο';
        $strings['Organization'] = 'Οργανισμός';
        $strings['Position'] = 'Θέση';
        $strings['Language'] = 'Γλώσσα';
        $strings['Permissions'] = 'Δικαιώματα';
        $strings['Reset'] = 'Επαναφορά';
        $strings['FindGroup'] = 'Εύρεση Ομάδας';
        $strings['Manage'] = 'Διαχείριση';
        $strings['None'] = 'Κανένα';
        $strings['AddToOutlook'] = 'Προσθήκη στο Ημερολόγιο';
        $strings['Done'] = 'Έτοιμο';
        $strings['RememberMe'] = 'Να με θυμάσαι';
        $strings['FirstTimeUser?'] = 'Πρώτη φορά χρήστης;';
        $strings['CreateAnAccount'] = 'Δημιουργία Λογαριασμού';
        $strings['ViewSchedule'] = 'Προβολή Προγραμματισμού';
        $strings['ForgotMyPassword'] = 'Ξέχασα το συνθηματικό μου';
        $strings['YouWillBeEmailedANewPassword'] = 'Θα σας αποσταλεί με email ένα νέο συνθηματικό από τυχαίους χαρακτήρες';
        $strings['Close'] = 'Κλείσιμο';
        $strings['ExportToCSV'] = 'Εξαγωγή σε CSV';
        $strings['OK'] = 'ΟΚ';
        $strings['Working'] = 'Γίνεται επεξεργασία...';
        $strings['Login'] = 'Είσοδος';
        $strings['AdditionalInformation'] = 'Πρόσθετες πληροφορίες';
        $strings['AllFieldsAreRequired'] = 'είναι απαραίτητα όλα τα πεδία';
        $strings['Optional'] = 'προαιρετικό';
        $strings['YourProfileWasUpdated'] = 'Το προφίλ σας ενημερώθηκε';
        $strings['YourSettingsWereUpdated'] = 'Οι ρυθμίσεις σας ενημερώθηκαν';
        $strings['Register'] = 'Εγγραφή';
        $strings['SecurityCode'] = 'Κωδικός Ασφαλείας';
        $strings['ReservationCreatedPreference'] = 'Όταν δημιουργώ μια κράτηση ή όταν η κράτηση δημιουργείται εκ μέρους μου';
        $strings['ReservationUpdatedPreference'] = 'Όταν ενημερώνω μια κράτηση ή όταν η κράτηση ενημερώνεται εκ μέρους μου';
        $strings['ReservationDeletedPreference'] = 'Όταν διαγράφω μια κράτηση ή όταν η κράτηση διαγράφεται εκ μέρους μου';
        $strings['ReservationApprovalPreference'] = 'Όταν η σε εκκρεμότητα κράτηση εγκρίνεται';
        $strings['PreferenceSendEmail'] = 'Να μου αποστέλλεται email';
        $strings['PreferenceNoEmail'] = 'Να μην ειδοποιούμαι';
        $strings['ReservationCreated'] = 'Η κράτησή σας δημιουργήθηκε με επιτυχία!';
        $strings['ReservationUpdated'] = 'Η κράτησή σας ενημερώθηκε με επιτυχία!';
        $strings['ReservationRemoved'] = 'Η κράτησή σας αφαιρέθηκε';
        $strings['ReservationRequiresApproval'] = 'Ένας ή περισσότεροι πόροι που κρατήθηκαν απαιτούν έγκριση πριν τη χρήση. Η κράτηση θα είναι σε εκκρεμότητα μέχρι να εγκριθεί.';
        $strings['YourReferenceNumber'] = 'Ο αριθμός αναφοράς σας είναι %s';
        $strings['ChangeUser'] = 'Αλλαγή Χρήστη';
        $strings['MoreResources'] = 'Περισσότεροι Πόροι';
        $strings['ReservationLength'] = 'Μήκος Κράτησης';
        $strings['ParticipantList'] = 'Λίστα Συμμετεχόντων';
        $strings['AddParticipants'] = 'Προσθήκη Συμμετεχόντων';
        $strings['InviteOthers'] = 'Πρόσκληση Άλλων';
        $strings['AddResources'] = 'Προσθήκη Πόρων';
        $strings['AddAccessories'] = 'Προσθήκη Εξοπλισμού';
        $strings['Accessory'] = 'Εξοπλισμός';
        $strings['QuantityRequested'] = 'Αιτούμενη Ποσότητα';
        $strings['CreatingReservation'] = 'Δημιουργία της Κράτησης';
        $strings['UpdatingReservation'] = 'Ενημέρωση της Κράτησης';
        $strings['DeleteWarning'] = 'Η ενέργεια είναι μόνιμη και μη αναστρέψιμη!';
        $strings['DeleteAccessoryWarning'] = 'Η διαγραφή του εξοπλισμού θα προκαλέσει την αφαίρεσή του από όλες τις κρατήσεις.';
        $strings['AddAccessory'] = 'Προσθήκη Εξοπλισμού';
        $strings['AddBlackout'] = 'Προσθήκη Μπλακάουτ';
        $strings['AllResourcesOn'] = 'Όλοι οι Πόροι στις';
        $strings['Reason'] = 'Αιτία';
        $strings['BlackoutShowMe'] = 'Εμφάνιση κρατήσεων σε σύγκρουση';
        $strings['BlackoutDeleteConflicts'] = 'Διαγραφή κρατήσεων σε σύγκρουση';
        $strings['Filter'] = 'Φίλτρο';
        $strings['Between'] = 'Μεταξύ';
        $strings['CreatedBy'] = 'Δημιουργήθηκε από';
        $strings['BlackoutCreated'] = 'Το Μπλακάουτ δημιουργήθηκε';
        $strings['BlackoutNotCreated'] = 'Το Μπλακάουτ δεν ήταν δυνατό να δημιουργηθεί';
        $strings['BlackoutUpdated'] = 'Το Μπλακάουτ ενημερώθηκε';
        $strings['BlackoutNotUpdated'] = 'Το Μπλακάουτ δεν ήταν δυνατό να ενημερωθεί';
        $strings['BlackoutConflicts'] = 'Υπάρχουν Μπλακάουτ σε σύγκρουση';
        $strings['ReservationConflicts'] = 'Υπάρχουν χρόνοι κράτησης σε σύγκρουση';
        $strings['UsersInGroup'] = 'Χρήστες στην ομάδα';
        $strings['Browse'] = 'Περιήγηση';
        $strings['DeleteGroupWarning'] = 'Η διαγραφή της ομάδας θα αφαιρέσει όλα τα δικαιώματα που σχετίζονται με πόρους. Οι χρήστες της ομάδας ενδέχεται να χάσουν την πρόσβαση στους πόρους.';
        $strings['WhatRolesApplyToThisGroup'] = 'Ποιοί ρόλοι να εφαρμοστούν σε αυτή την ομάδα;';
        $strings['WhoCanManageThisGroup'] = 'Ποιός θα διαχειρίζεται την ομάδα;';
        $strings['WhoCanManageThisSchedule'] = 'Ποιός θα διαχειρίζεται τον προγραμματισμό;';
        $strings['AllQuotas'] = 'Όλες οι Ποσοστώσεις';
        $strings['QuotaReminder'] = 'Θυμηθείτε: Οι ποσοστώσεις εφαρμόζονται βάσει της ζώνης ώρας του προγραμματισμού.';
        $strings['AllReservations'] = 'Όλες οι Κρατήσεις';
        $strings['PendingReservations'] = 'Κρατήσεις σε Εκκρεμότητα';
        $strings['Approving'] = 'Σε έγκριση';
        $strings['MoveToSchedule'] = 'Μετακίνηση στον προγραμματισμό';
        $strings['DeleteResourceWarning'] = 'Η διαγραφή του πόρου θα διαγράψει όλα τα σχετιζόμενα δεδομένα, συμπεριλαμβανομένου';
        $strings['DeleteResourceWarningReservations'] = 'όλες τις παρελθοντικές, τρέχουσες και μελλοντικές κρατήσεις που σχετίζονται με αυτόν';
        $strings['DeleteResourceWarningPermissions'] = 'όλες τις αναθέσεις δικαιωμάτων';
        $strings['DeleteResourceWarningReassign'] = 'Παρακαλούμε αναθέστε οτιδήποτε που δεν θέλετε να διαγραφεί προτού συνεχίσετε';
        $strings['ScheduleLayout'] = 'Διάταξη (όλοι οι χρόνοι %s)';
        $strings['ReservableTimeSlots'] = 'Διαθέσιμα Κενά Χρόνου';
        $strings['BlockedTimeSlots'] = 'Μπλοκαρισμένα Κενά Χρόνου';
        $strings['ThisIsTheDefaultSchedule'] = 'Αυτός είναι ο προκαθορισμένος προγραμματισμός';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Ο προκαθορισμένος προγραμματισμός δεν μπορεί να διαγραφεί';
        $strings['MakeDefault'] = 'Να γίνει το προκαθορισμένο';
        $strings['BringDown'] = 'Μετακίνηση Κάτω';
        $strings['ChangeLayout'] = 'Αλλαγή Διάταξης';
        $strings['AddSchedule'] = 'Προσθήκη Προγραμματισμού';
        $strings['StartsOn'] = 'Αρχίζει στις';
        $strings['NumberOfDaysVisible'] = 'Αριθμός Ορατών Ημερών';
        $strings['UseSameLayoutAs'] = 'Χρήση ίδιας διάταξης όπως';
        $strings['Format'] = 'Μορφοποίηση';
        $strings['OptionalLabel'] = 'Προαιρετική Ετικέτα';
        $strings['LayoutInstructions'] = 'Εισάγετε ένα κενό ανά γραμμή. Τα κενά πρέπει να εισάγονται για όλες τις 24 ώρες της ημέρας με αρχή και λήξη στις 12:00 ΠΜ.';
        $strings['AddUser'] = 'Προσθήκη Χρήστη';
        $strings['UserPermissionInfo'] = 'Η πραγματική πρόσβαση σε ένα πόρο ενδέχεται να διαφέρει ανάλογα με το ρόλο του χρήστη, τα δικαιώματα της ομάδας ή τις ρυθμίσεις εξωτερικών δικαιωμάτων';
        $strings['DeleteUserWarning'] = 'Η διαγραφή του χρήστη θα αφαιρέσει όλες τις τωρινές, μελλοντικές και παρελθοντικές κρατήσεις του.';
        $strings['AddAnnouncement'] = 'Προσθήκη Ανακοίνωσης';
        $strings['Announcement'] = 'Ανακοίνωση';
        $strings['Priority'] = 'Προτεραιότητα';
        $strings['Reservable'] = 'Ανοικτό';
        $strings['Unreservable'] = 'Κλειστό';
        $strings['Reserved'] = 'Κρατημένο';
        $strings['MyReservation'] = 'Η Κράτησή μου';
        $strings['Pending'] = 'Σε εκκρεμότητα';
        $strings['Past'] = 'Πριν';
        $strings['Restricted'] = 'Απαγορευμένο';
        $strings['ViewAll'] = 'Προβολή Όλων';
        $strings['MoveResourcesAndReservations'] = 'Μετακίνηση των πόρων και των κρατήσεων σε';
        $strings['TurnOffSubscription'] = 'Να κλείσουν οι Εγγραφές Ημερολογίου';
        $strings['TurnOnSubscription'] = 'Να επιτρέπονται οι Εγγραφές στο Ημερολόγιο';
        $strings['SubscribeToCalendar'] = 'Εγγραφή στο Ημερολόγιο';
        $strings['SubscriptionsAreDisabled'] = 'Ο διαχειριστής έχει απενεργοποιήσει τις εγγραφές ημερολογίου';
        $strings['NoResourceAdministratorLabel'] = '(Χωρίς Διαχειριστή Πόρου)';
        $strings['WhoCanManageThisResource'] = 'Ποιός θα διαχειρίζεται τον Πόρο;';
        $strings['ResourceAdministrator'] = 'Διαχειριστής Πόρου';
        $strings['Private'] = 'Ιδιωτικό';
        $strings['Accept'] = 'Αποδοχή';
        $strings['Decline'] = 'Απόρριψη';
        $strings['ShowFullWeek'] = 'Εμφάνιση Πλήρους Εβδομάδας';
        $strings['CustomAttributes'] = 'Προσαρμοσμένες Ιδιότητες';
        $strings['AddAttribute'] = 'Προσθήκη Ιδιότητας';
        $strings['EditAttribute'] = 'Ενημέρωσης μιας Ιδιότητας';
        $strings['DisplayLabel'] = 'Προβολή Ετικέτας';
        $strings['Type'] = 'Τύπος';
        $strings['Required'] = 'Απαιτούμενο';
        $strings['ValidationExpression'] = 'Έκφραση Επαλήθευσης';
        $strings['PossibleValues'] = 'Δυνατές Τιμές';
        $strings['SingleLineTextbox'] = 'Κείμενο μίας Γραμμής';
        $strings['MultiLineTextbox'] = 'Κείμενο Πολλών Γραμμών';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Επιλογή από Λίστα';
        $strings['CommaSeparated'] = 'χωρισμένα με κόμμα';
        $strings['Category'] = 'Κατηγορία';
        $strings['CategoryReservation'] = 'Κράτηση';
        $strings['CategoryGroup'] = 'Ομάδα';
        $strings['SortOrder'] = 'Σειρά Ταξινόμησης';
        $strings['Title'] = 'Τίτλος';
        $strings['AdditionalAttributes'] = 'Πρόσθετες Ιδιότητες';
        $strings['True'] = 'Αληθές';
        $strings['False'] = 'Ψευδές';
        $strings['ForgotPasswordEmailSent'] = 'Στάλθηκε ένα email στην διεύθυνση που εισαγάγατε με οδηγίες για επαναφορά του συνθηματικού σας';
        $strings['ActivationEmailSent'] = 'Θα λάβετε σύντομα ένα email για ενεργοποίηση.';
        $strings['AccountActivationError'] = 'Συγγνώμη, δεν ήταν δυνατή η ενεργοποίηση του λογαριασμού σας.';
        $strings['Attachments'] = 'Συνημμένα';
        $strings['AttachFile'] = 'Επισύναψη Αρχείου';
        $strings['Maximum'] = 'μέγιστο';
        $strings['NoScheduleAdministratorLabel'] = 'Χωρίς Διαχειριστή Προγραμματισμού';
        $strings['ScheduleAdministrator'] = 'Διαχειριστής Προγραμματισμού';
        $strings['Total'] = 'Συνολικά';
        $strings['QuantityReserved'] = 'Δεσμευμένη Ποσότητα';
        $strings['AllAccessories'] = 'Όλος ο Εξοπλισμός';
        $strings['GetReport'] = 'Λήψη Αναφοράς';
        $strings['NoResultsFound'] = 'Δε βρέθηκαν αποτελέσματα';
        $strings['SaveThisReport'] = 'Αποθήκευση της Αναφοράς';
        $strings['ReportSaved'] = 'Η Αναφορά αποθηκεύτηκε!';
        $strings['EmailReport'] = 'Email της Αναφοράς';
        $strings['ReportSent'] = 'Η αναφορά απεστάλλη!';
        $strings['RunReport'] = 'Εκτέλεση της Αναφοράς';
        $strings['NoSavedReports'] = 'Δεν έχετε αποθηκευμένες Αναφορές.';
        $strings['CurrentWeek'] = 'Τρέχουσα Εβδομάδα';
        $strings['CurrentMonth'] = 'Τρέχων Μήνας';
        $strings['AllTime'] = 'Όλος ο Χρόνος';
        $strings['FilterBy'] = 'Φίλτρο με';
        $strings['Select'] = 'Επιλογή';
        $strings['List'] = 'Λίστα';
        $strings['TotalTime'] = 'Συνολικός Χρόνος';
        $strings['Count'] = 'Αριθμός';
        $strings['Usage'] = 'Χρήση';
        $strings['AggregateBy'] = 'Συγκέντρωση Ανά';
        $strings['Range'] = 'Εύρος';
        $strings['Choose'] = 'Επιλογή';
        $strings['All'] = 'Όλα';
        $strings['ViewAsChart'] = 'Προβολή ως γράφημα';
        $strings['ReservedResources'] = 'Δεσμευμένοι Πόροι';
        $strings['ReservedAccessories'] = 'Δεσμευμένος Εξοπλισμός';
        $strings['ResourceUsageTimeBooked'] = 'Χρήση Πόρων - Κλεισμένος Χρόνος';
        $strings['ResourceUsageReservationCount'] = 'Χρήση Πόρων - Αριθμός Κρατήσεων';
        $strings['Top20UsersTimeBooked'] = 'Κορυφαίοι 20 Χρήστες - Κλεισμένος Χρόνος';
        $strings['Top20UsersReservationCount'] = 'Κορυφαίοι 20 Χρήστες - Αριθμός Κρατήσεων';
        $strings['ConfigurationUpdated'] = 'Ενημερώθηκε το αρχείο ρυθμίσεων';
        $strings['ConfigurationUiNotEnabled'] = 'Η σελίδα δεν είναι προσπελάσιμη επειδή η ρύθμιση $conf[\'settings\'][\'pages\'][\'enable.configuration\'] έχει τεθεί σε false ή λείπει.';
        $strings['ConfigurationFileNotWritable'] = 'Το αρχείο ρυθμίσεων δεν είναι εγγράψιμο. Παρακαλούμε ελέγξτε τα δικαιώματα του αρχείου και δοκιμάστε πάλι.';
        $strings['ConfigurationUpdateHelp'] = 'Ανατρέξτε στο τμήμα Ρυθμίσεις <a target=_blank href=%s>Αρχείου Βοήθειας</a> για την τεκμηρίωση για αυτές τις ρυθμίσεις.';
        $strings['GeneralConfigSettings'] = 'ρυθμίσεις';
        $strings['UseSameLayoutForAllDays'] = 'Χρήση της ίδιας διάταξης για όλες τις ημέρες';
        $strings['LayoutVariesByDay'] = 'Η διάταξη αλλάζει ανά ημέρα';
        $strings['ManageReminders'] = 'Υπενθυμίσεις';
        $strings['ReminderUser'] = 'Αναγνωριστικό Χρήστη';
        $strings['ReminderMessage'] = 'Μήνυμα';
        $strings['ReminderAddress'] = 'Διευθύνσεις';
        $strings['ReminderSendtime'] = 'Ώρα για Αποστολή';
        $strings['ReminderRefNumber'] = 'Αριθμός Αναφοράς Κράτησης';
        $strings['ReminderSendtimeDate'] = 'Ημ/νία Υπενθύμισης';
        $strings['ReminderSendtimeTime'] = 'Ώρα Υπενθύμισης (ΩΩ:ΛΛ)';
        $strings['ReminderSendtimeAMPM'] = 'ΠΜ / ΜΜ';
        $strings['AddReminder'] = 'Προσθήκη Υπενθύμισης';
        $strings['DeleteReminderWarning'] = 'Είστε σίγουροι για τη διαγραφή;';
        $strings['NoReminders'] = 'Δεν έχετε προσεχείς υπενθυμίσεις.';
        $strings['Reminders'] = 'Υπενθυμίσεις';
        $strings['SendReminder'] = 'Αποστολή Υπενθυμίσεων';
        $strings['minutes'] = 'λεπτά';
        $strings['hours'] = 'ώρες';
        $strings['days'] = 'ημέρες';
        $strings['ReminderBeforeStart'] = 'πριν το χρόνο έναρξης';
        $strings['ReminderBeforeEnd'] = 'πριν το χρόνο λήξης';
        $strings['Logo'] = 'Λογότυπο';
        $strings['CssFile'] = 'Αρχείο CSS';
        $strings['ThemeUploadSuccess'] = 'Οι αλλαγές σας αποθηκεύτηκαν. Ανανεώστε τη σελίδα ώστε να ισχύσουν οι αλλαγές.';
        $strings['MakeDefaultSchedule'] = 'Κάντε αυτόν τον προγραμματισμό τον προκαθορισμένο';
        $strings['DefaultScheduleSet'] = 'Αυτός είναι πλέον ο προκαθορισμένος προγραμματισμός σας';
        $strings['FlipSchedule'] = 'Αναποδογυρίστε την διάταξη του προγραμματισμού';
        $strings['Next'] = 'Επόμενο';
        $strings['Success'] = 'Επιτυχία';
        $strings['Participant'] = 'Συμμετέχων';
        $strings['ResourceFilter'] = 'Φίλτρο Πόρου';
        $strings['ResourceGroups'] = 'Ομάδες Πόρων';
        $strings['AddNewGroup'] = 'Προσθήκη νέας ομάδας';
        $strings['Quit'] = 'Ακύρωση';
        $strings['AddGroup'] = 'Προσθήκη Ομάδας';
        $strings['StandardScheduleDisplay'] = 'Χρήση της προκαθορισμένης προβολής προγραμματισμού';
        $strings['TallScheduleDisplay'] = 'Χρήση της ψηλής προβολής προγραμματισμού';
        $strings['WideScheduleDisplay'] = 'Χρήση της φαρδιάς προβολής προγραμματισμού';
        $strings['CondensedWeekScheduleDisplay'] = 'Χρήση της συμπτυγμένης προβολής προγραμματισμού εβδομάδας';
        $strings['ResourceGroupHelp1'] = 'Σύρετε και αφήστε τις ομάδες πόρων για επαναδιοργάνωση.';
        $strings['ResourceGroupHelp2'] = 'Δεξί κλικ στο όνομα ομάδας πόρων για πρόσθετες ενέργειες.';
        $strings['ResourceGroupHelp3'] = 'Σύρετε και αφήστε τους πόρους για να τους προσθέσετε σε ομάδες.';
        $strings['ResourceGroupWarning'] = 'Αν χρησιμοποιείτε ομάδες πόρων, κάθε πόρος πρέπει να ανατεθεί σε μία τουλάχιστον ομάδα. Δε θα είναι δυνατή η κράτηση μη ανατηθέντων πόρων.';
        $strings['ResourceType'] = 'Τύπος Πόρου';
        $strings['AppliesTo'] = 'Εφαρμόζεται Σε';
        $strings['UniquePerInstance'] = 'Μοναδικό Ανά Στιγμιότυπο';
        $strings['AddResourceType'] = 'Προσθήκη Τύπου Πόρου';
        $strings['NoResourceTypeLabel'] = '(δεν έχουν οριστεί τύποι πόρων)';
        $strings['ClearFilter'] = 'Καθαρισμός Φίλτρου';
        $strings['MinimumCapacity'] = 'Ελάχιστη Χωρητικότητα';
        $strings['Color'] = 'Χρώμα';
        $strings['Available'] = 'Διαθέσιμο';
        $strings['Unavailable'] = 'Μη διαθέσιμο';
        $strings['Hidden'] = 'Κρυφό';
        $strings['ResourceStatus'] = 'Κατάσταση Πόρου';
        $strings['CurrentStatus'] = 'Τρέχουσα Κατάσταση';
        $strings['AllReservationResources'] = 'Όλοι οι Πόροι Κρατήσεων';
        $strings['File'] = 'Αρχείο';
        $strings['BulkResourceUpdate'] = 'Μαζική Ενημέρωση Πόρων';
        $strings['Unchanged'] = 'Χωρίς αλλαγή';
        $strings['Common'] = 'Κοινό';
        $strings['AdminOnly'] = 'Είναι Μόνο Διαχειριστής';
        $strings['AdvancedFilter'] = 'Φίλτρο για Προχωρημένους';
        $strings['MinimumQuantity'] = 'Ελάχιστη Ποσότητα';
        $strings['MaximumQuantity'] = 'Μέγιστη Ποσότητα';
        $strings['ChangeLanguage'] = 'Αλλαγή Γλώσσας';
        $strings['AddRule'] = 'Προσθήκη Κανόνα';
        $strings['Attribute'] = 'Ιδιότητα';
        $strings['RequiredValue'] = 'Απαιτούμενη Τιμή';
        $strings['ReservationCustomRuleAdd'] = 'Αν %s τότε το χρώμα της κράτησης θα είναι';
        $strings['AddReservationColorRule'] = 'Προσθήκη Κανόνα Χρώματος Κράτησης';
        $strings['LimitAttributeScope'] = 'Συλλογή Σε Ορισμένες Περιπτώσεις';
        $strings['CollectFor'] = 'Συλλογή Για';
        $strings['SignIn'] = 'Είσοδος';
        $strings['AllParticipants'] = 'Όλοι οι Συμμετέχοντες';
        $strings['RegisterANewAccount'] = 'Εγγραφή για Νέο Λογαριασμό';
        $strings['Dates'] = 'Ημερομηνίες';
        $strings['More'] = 'Περισσότερα';
        $strings['ResourceAvailability'] = 'Διαθεσιμότητα Πόρου';
        $strings['UnavailableAllDay'] = 'Μη Διαθέσιμο Όλη την Ημέρα';
        $strings['AvailableUntil'] = 'Διαθέσιμο Μέχρι';
        $strings['AvailableBeginningAt'] = 'Διαθέσιμο Αρχίζοντας Στις';
        $strings['AvailableAt'] = 'Διαθέσιμο Στις';
        $strings['AllResourceTypes'] = 'Όλοι οι Τύποι Πόρων';
        $strings['AllResourceStatuses'] = 'Όλες οι Καταστάσεις Πόρων';
        $strings['AllowParticipantsToJoin'] = 'Να Επιτρέπεται σε Συμμετέχοντες να Εγγράφονται';
        $strings['Join'] = 'Συμμετοχή';
        $strings['YouAreAParticipant'] = 'Είστε συμμετέχοντας σε αυτή την κράτηση';
        $strings['YouAreInvited'] = 'Έχετε προσκληθεί σε αυτή την κράτηση';
        $strings['YouCanJoinThisReservation'] = 'Μπορείτε να συμμετέχετε σε αυτή την κράτηση';
        $strings['Import'] = 'Εισαγωγή';
        $strings['GetTemplate'] = 'Λήψη Προτύπου';
        $strings['UserImportInstructions'] = '<ul><li>Το αρχείο πρέπει να είναι σε μορφή CSV.</li><li>Το όνομα χρήστη και email είναι απαραίτητα πεδία.</li><li>Δε θα επιβληθεί η επαλήθευση ιδιότητας.</li><li>Το να μείνουν τα άλλα πεδία κενά σημαίνει ότι θα χρησιμοποιηθούν προκαθορισμένες τιμές για το \'συνθηματικό\' του χρήστη.</li><li>Χρησιμοποιήστε το παρεχόμενο πρότυπο ως παράδειγμα.</li></ul>';
        $strings['RowsImported'] = 'Εισηγμένες Γραμμές';
        $strings['RowsSkipped'] = 'Αγνοηθείσες Γραμμές';
        $strings['Columns'] = 'Στήλες';
        $strings['Reserve'] = 'Κράτηση';
        $strings['AllDay'] = 'Όλη η ημέρα';
        $strings['Everyday'] = 'Κάθε μέρα';
        $strings['IncludingCompletedReservations'] = 'Συμπεριλαμβάνοντας Ολοκληρωμένες Κρατήσεις';
        $strings['NotCountingCompletedReservations'] = 'Μη Συμπεριλαμβάνοντας Ολοκληρωμένες Κρατήσεις';
        $strings['RetrySkipConflicts'] = 'Αγνόηση αλληλοεπικαλυπτόμενων κρατήσεων';
        $strings['Retry'] = 'Επαναπροσπάθεια';
        $strings['RemoveExistingPermissions'] = 'Να αφαιρεθούν τα υπάρχοντα δικαιώματα;';
        $strings['Continue'] = 'Συνέχεια';
        $strings['WeNeedYourEmailAddress'] = 'Χρειαζόμαστε τη διεύθυνση email σας για την κράτηση';
        $strings['ResourceColor'] = 'Χρώμα Πόρου';
        $strings['DateTime'] = 'Ημερομηνία Ώρα';
        $strings['AutoReleaseNotification'] = 'Αυτόματη απελευθέρωση αν δεν γίνει check-in σε διάστημα %s λεπτών';
        $strings['RequiresCheckInNotification'] = 'Απαιτεί check in/out';
        $strings['NoCheckInRequiredNotification'] = 'Δεν απαιτεί check in/out';
        $strings['RequiresApproval'] = 'Απαιτεί Έγκριση';
        $strings['CheckingIn'] = 'Γίνεται Check in';
        $strings['CheckingOut'] = 'Γίνεται Check out';
        $strings['CheckIn'] = 'Check in';
        $strings['CheckOut'] = 'Check out';
        $strings['ReleasedIn'] = 'Απελευθερώθηκε στις';
        $strings['CheckedInSuccess'] = 'Έχετε κάνει check in';
        $strings['CheckedOutSuccess'] = 'Έχετε κάνει check out';
        $strings['CheckInFailed'] = 'Δεν ήταν δυνατό να κάνετε check in';
        $strings['CheckOutFailed'] = 'Δεν ήταν δυνατό να κάνετε check out';
        $strings['CheckInTime'] = 'Ώρα Check in';
        $strings['CheckOutTime'] = 'Ώρα Check out';
        $strings['OriginalEndDate'] = 'Αρχική Λήξη';
        $strings['SpecificDates'] = 'Εμφάνιση Συγκεκριμένων Ημερομηνιών';
        $strings['Users'] = 'Χρήστες';
        $strings['Guest'] = 'Επισκέπτης';
        $strings['ResourceDisplayPrompt'] = 'Πόρος για Προβολή';
        $strings['Credits'] = 'Μονάδες';
        $strings['AvailableCredits'] = 'Διαθέσιμες Μονάδες';
        $strings['CreditUsagePerSlot'] = 'Απαιτούνται %s μονάδες ανά θέση (χωρίς κορυφή)';
        $strings['PeakCreditUsagePerSlot'] = 'Απαιτούνται %s μονάδες ανά θέση (κορυφή)';
        $strings['CreditsRule'] = 'Δεν έχετε αρκετές μονάδες. Απαιτούμενες Μονάδες: %s. Μονάδες στο λογαριασμό: %s';
        $strings['PeakTimes'] = 'Χρόνοι Κορυφής';
        $strings['AllYear'] = 'Όλο το Έτος';
        $strings['MoreOptions'] = 'Περισσότερες Επιλογές';
        $strings['SendAsEmail'] = 'Αποστολή ως Email';
        $strings['UsersInGroups'] = 'Χρήστες στις Ομάδες';
        $strings['UsersWithAccessToResources'] = 'Χρήστες με Πρόσβαση στους Πόρους';
        $strings['AnnouncementSubject'] = 'Έχει αναρτηθεί μια νέα ανακοίνωση από %s';
        $strings['AnnouncementEmailNotice'] = 'στους χρήστες θα αποσταλλεί η ανακοίνωση ως email';
        $strings['Day'] = 'Ημέρα';
        $strings['NotifyWhenAvailable'] = 'Να Ειδοποιηθώ Όταν είναι Διαθέσιμο';
        $strings['AddingToWaitlist'] = 'Γίνεται προσθήκη στη λίστα αναμονής';
        $strings['WaitlistRequestAdded'] = 'Θα ειδοποιηθείτε αν ο χρόνος αυτός γίνει διαθέσιμος';
        $strings['PrintQRCode'] = 'Εκτύπωση κωδικού QR';
        $strings['FindATime'] = 'Εύρεση Χρόνου';
        $strings['AnyResource'] = 'Οποιοσδήποτε Πόρος';
        $strings['ThisWeek'] = 'Αυτή την Εβδομάδα';
        $strings['Hours'] = 'Ώρες';
        $strings['Minutes'] = 'Λεπτά';
        $strings['ImportICS'] = 'Εισαγωγή από ICS';
        $strings['ImportQuartzy'] = 'Εισαγωγή από Quartzy';
        $strings['OnlyIcs'] = 'Μόνο αρχεία *.ics είναι δυνατό να ανεβούν.';
        $strings['IcsLocationsAsResources'] = 'Οι τοποθεσίες θα εισαχθούν ως πόροι.';
        $strings['IcsMissingOrganizer'] = 'Οποιοδήποτε γεγονός που δεν έχει τον οργανωτή του θα έχει ως ιδιοκτήτη τον τρέχοντα χρήστη.';
        $strings['IcsWarning'] = 'Οι κανόνες κράτησης δε θα εφαρμόζονται - αλληλοεπικαλύψεις, διπλότυπα, κτλ. θα είναι πιθανά.';
        $strings['BlackoutAroundConflicts'] = 'Μπλακάουτ μεταξύ αλληλοεπικαλυπτόμενων κρατήσεων';
        $strings['DuplicateReservation'] = 'Διπλότυπο';
        $strings['UnavailableNow'] = 'Μη διαθέσιμο τώρα';
        $strings['ReserveLater'] = 'Κράτηση Αργότερα';
        $strings['CollectedFor'] = 'Συλλέχθηκαν Για';
        $strings['IncludeDeleted'] = 'Να συμπεριληφθούν Διεγραμμένες Κρατήσεις';
        $strings['Deleted'] = 'Διαγράφηκε';
        $strings['Back'] = 'Πίσω';
        $strings['Forward'] = 'Εμπρός';
        $strings['DateRange'] = 'Εύρος Ημερομηνιών';
        $strings['Copy'] = 'Αντιγραφή';
        $strings['Detect'] = 'Εντοπισμός';
        $strings['Autofill'] = 'Αυτόματη συμπλήρωση';
        $strings['NameOrEmail'] = 'όνομα ή email';
        $strings['ImportResources'] = 'Εισαγωγή Πόρων';
        $strings['ExportResources'] = 'Εξαγωγή Πόρων';
        $strings['ResourceImportInstructions'] = '<ul><li>Το αρχείο πρέπει να είναι σε μορφή CSV.</li><li>Το όνομα είναι απαιτούμενο πεδίο. Αν τα άλλα πεδία μείνουν κενά, θα χρησιμοποιηθούν οι προκαθορισμένες τιμές.</li><li>Οι επιλογές κατάστασης είναι \'Διαθέσιμο\', \'Μη διαθέσιμο\' και \'Κρυφό\'.</li><li>Το χρώμα πρέπει να είναι σε δεκαεξαδική μορφή, πχ. #ffffff.</li><li>Οι στήλες της αυτόματης ανάθεσης και έγκρισης είναι σε μορφή Αληθές ή Ψευδές.</li><li>Δε θα εφαρμόζεται η επαλήθευση ιδιότητας.</li><li>Χωρισμός με κόμμα των πολλαπλών ομάδων πόρων.</li><li>Χρήση του παρεχόμενου προτύπου ως παράδειγμα.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Το αρχείο πρέπει να είναι σε μορφή CSV.</li><li>Τα email, ονόματα πόρων, έναρξη και λήξη είναι απαραίτητα πεδία.</li><li>Η έναρξη και λήξη απαιτούν πλήρη ημερομηνία και ώρα. Η προτεινόμενη μορφή είναι YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Οι κανόνες, οι αλληλεπικαλύψεις και τα έγκυρα κενά θέσεων θα ελέγχονται.</li><li>Δε θα αποστέλλονται οι υπενθυμίσεις.</li><li>Δε θα εφαρμόζεται η επαλήθευση ιδιότητας.</li><li>Χωρισμός με κόμμα των πολλαπλών ομάδων πόρων.</li><li>Χρήση του παρεχόμενου προτύπου ως παράδειγμα.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Λεπτά για αυτόματη απελευθέρωση';
        $strings['CreditsPeak'] = 'Μονάδες (κορυφή)';
        $strings['CreditsOffPeak'] = 'Μονάδες (εκτός κορυφής)';
        $strings['ResourceMinLengthCsv'] = 'Ελάχιστο Μέγεθος Κράτησης';
        $strings['ResourceMaxLengthCsv'] = 'Μέγιστο Μέγεθος Κράτησης';
        $strings['ResourceBufferTimeCsv'] = 'Buffer Time';
        $strings['ResourceMinNoticeAddCsv'] = 'Ελάχιστος Χρόνος Ειδοποίησης Προσθήκης Κράτησης';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Ελάχιστος Χρόνος Ειδοποίησης Ενημέρωσης Κράτησης';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Ελάχιστος Χρόνος Ειδοποίησης Διαγραφής Κράτησης';
        $strings['ResourceMaxNoticeCsv'] = 'Μέγιστος Χρόνος Κράτησης';
        $strings['Export'] = 'Εξαγωγή';
        $strings['DeleteMultipleUserWarning'] = 'Η διαγραφή των χρηστών θα αφαιρέσει όλες τις τρέχουσες, μελλοντικές και ιστορικές κρατήσεις τους. Δε θα αποσταλλούν e-mail.';
        $strings['DeleteMultipleReservationsWarning'] = 'Δε θα αποσταλλούν e-mail.';
        $strings['ErrorMovingReservation'] = 'Σφάλμα Μετακίνησης Κράτησης';
        $strings['SelectUser'] = 'Επιλογή χρήστη';
        $strings['InviteUsers'] = 'Πρόσκληση χρηστών';
        $strings['InviteUsersLabel'] = 'Εισάγετε τις διευθύνσεις e-mail των ανθρώπων που επιθυμείτε να προσκαλέσετε';
        $strings['ApplyToCurrentUsers'] = 'Εφαρμογή στους τρέχοντες χρήστες';
        $strings['ReasonText'] = 'Κείμενο αιτιολόγησης';
        $strings['NoAvailableMatchingTimes'] = 'Δεν υπάρχουν διαθέσιμοι χρόνοι που να ταιριάζουν με τα κριτήρια αναζήτησής σας';
        $strings['Schedules'] = 'Προγραμματισμοί';
        $strings['NotifyUser'] = 'Ειδοποίηση Χρήστη';
        $strings['UpdateUsersOnImport'] = 'Τροποίηση του υπάρχοντος χρήστη αν το email υπάρχει ήδη';
        $strings['UpdateResourcesOnImport'] = 'Τροποποίηση των υπάρχοντων πόρων αν το όνομα υπάρχει ήδη';
        $strings['Reject'] = 'Απόρριψη';
        $strings['CheckingAvailability'] = 'Έλεγχος διαθεσιμότητας';
        $strings['CreditPurchaseNotEnabled'] = 'Δεν έχετε ενεργοποιήσει τη δυνατότητα να αγοράζετε credits';
        $strings['CreditsCost'] = 'Κάθε credit κοστίζει';
        $strings['Currency'] = 'Νόμισμα';
        $strings['PayPalClientId'] = 'Αριθμός Πελάτη';
        $strings['PayPalSecret'] = 'Συνθηματικό';
        $strings['PayPalEnvironment'] = 'Περιβάλλον';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'Σε λειτουργία';
        $strings['StripePublishableKey'] = 'Δημοσιεύσιμο κλειδί';
        $strings['StripeSecretKey'] = 'Μυστικό κλειδί';
        $strings['CreditsUpdated'] = 'Το κόστος των credit ενημερώθηκε';
        $strings['GatewaysUpdated'] = 'Οι πύλες πληρωμής ενημερώθηκαν';
        $strings['PurchaseSummary'] = 'Περίληψη Αγοράς';
        $strings['EachCreditCosts'] = 'Κάθε credit κοστίζει';
        $strings['Checkout'] = 'Checkout';
        $strings['Quantity'] = 'Ποσότητα';
        $strings['CreditPurchase'] = 'Αγορά Credit';
        $strings['EmptyCart'] = 'Το καλάθι σας είναι άδειο.';
        $strings['BuyCredits'] = 'Αγορά Credits';
        $strings['CreditsPurchased'] = 'credits αγοράστηκαν.';
        $strings['ViewYourCredits'] = 'Δείτε τα credits σας';
        $strings['TryAgain'] = 'Δοκιμάστε πάλι';
        $strings['PurchaseFailed'] = 'Πρόβλημα κατά την επεξεργασία της πληρωμής σας.';
        $strings['NoteCreditsPurchased'] = 'Τα credits αγοράστηκαν';
        $strings['CreditsUpdatedLog'] = 'Τα credits τροποποιήθηκαν από %s';
        $strings['ReservationCreatedLog'] = 'Η κράτηση δημιουργήθηκε. Αριθμός αναφοράς %s';
        $strings['ReservationUpdatedLog'] = 'Η κράτηση τροποποιήθηκε. Αριθμός αναφοράς %s';
        $strings['ReservationDeletedLog'] = 'Η κράτηση διαγράφηκε. Αριθμός αναφοράς %s';
        $strings['BuyMoreCredits'] = 'Αγορά Περισσότερων Credits';
        $strings['Transactions'] = 'Συναλλαγές';
        $strings['Cost'] = 'Κόστος';
        $strings['PaymentGateways'] = 'Πύλες Πληρωμής';
        $strings['CreditHistory'] = 'Ιστορικό Credit';
        $strings['TransactionHistory'] = 'Ιστορικό Συναλλαγών';
        $strings['Date'] = 'Ημερομηνία';
        $strings['Note'] = 'Σημείωση';
        $strings['CreditsBefore'] = 'Credits Πριν';
        $strings['CreditsAfter'] = 'Credits Μετά';
        $strings['TransactionFee'] = 'Προμήθεια Συναλλαγής';
        $strings['InvoiceNumber'] = 'Αριθμός Παραστατικού';
        $strings['TransactionId'] = 'Αριθμός Συναλλαγής';
        $strings['Gateway'] = 'Πύλη';
        $strings['GatewayTransactionDate'] = 'Ημερομηνία Συναλλαγής Πύλης';
        $strings['Refund'] = 'Επιστροφή Χρημάτων';
        $strings['IssueRefund'] = 'Αίτηση για Επιστροφή Χρημάτων';
        $strings['RefundIssued'] = 'Η Αίτηση για Επιστροφή Χρημάτων υποβλήθηκε με επιτυχία';
        $strings['RefundAmount'] = 'Ποσό Επιστροφής';
        $strings['AmountRefunded'] = 'Επιστράφηκαν';
        $strings['FullyRefunded'] = 'Πλήρης Επιστροφή Χρημάτων';
        $strings['YourCredits'] = 'Τα Credits σας';
        $strings['PayWithCard'] = 'Πληρωμή με Κάρτα';
        $strings['or'] = 'ή';
        $strings['CreditsRequired'] = 'Credits Απαιτούνται';
        $strings['AddToGoogleCalendar'] = 'Προσθήκη στο Google';
        $strings['Image'] = 'Εικόνα';
        $strings['ChooseOrDropFile'] = 'Επιλέξτε αρχείο ή σύρτε το εδώ';
        $strings['SlackBookResource'] = 'Κράτηση του %s τώρα';
        $strings['SlackBookNow'] = 'Κράτηση Τώρα';
        $strings['SlackNotFound'] = 'Δεν ήταν δυνατή η εύρεση πόρου με αυτό το όνομα. Κάντε Κράτηση τώρα.';
        $strings['AutomaticallyAddToGroup'] = 'Αυτόματη προσθήκη νέων χρηστών σε αυτή την ομάδα';
        $strings['GroupAutomaticallyAdd'] = 'Αυτόματη Προσθήκη';
        $strings['TermsOfService'] = 'Όροι Υπηρεσίας';
        $strings['EnterTermsManually'] = 'Εισάγετε χειροκίνητα τους Όρους';
        $strings['LinkToTerms'] = 'Σύνδεσμος προς τους Όρους';
        $strings['UploadTerms'] = 'Ανέβασμα Όρων';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Να απαιτείται Αποδοχή των Όρων Υπηρεσίας';
        $strings['UponReservation'] = 'Κατά την Κράτηση';
        $strings['UponRegistration'] = 'Κατά την Εγγραφή';
        $strings['ViewTerms'] = 'Εμφάνιση Όρων Υπηρεσίας';
        $strings['IAccept'] = 'Αποδέχομαι';
        $strings['TheTermsOfService'] = 'τους Όρους Υπηρεσίας';
        $strings['DisplayPage'] = 'Εμφάνιση Σελίδας';
        $strings['AvailableAllYear'] = 'Όλο το Έτος';
        $strings['Availability'] = 'Διαθεσιμότητα';
        $strings['AvailableBetween'] = 'Διαθέσιμο Μεταξύ';
        $strings['ConcurrentYes'] = 'Οι πόροι μπορούν να δεσμευτούν από περισσότερα από ένα άτομα τη φορά';
        $strings['ConcurrentNo'] = 'Οι πόροι δεν μπορούν να δεσμευτούν από περισσότερα από ένα άτομα τη φορά';
        $strings['ScheduleAvailabilityEarly'] = 'Ο προγραμματισμός δεν είναι ακόμη διαθέσιμος. Είναι διαθέσιμος';
        $strings['ScheduleAvailabilityLate'] = 'Ο προγραμματισμός δεν είναι πλέον διαθέσιμος. Ήταν διαθέσιμος';
        $strings['ResourceImages'] = 'Εικόνες Πόρων';
        $strings['FullAccess'] = 'Πλήρης Πρόσβαση';
        $strings['ViewOnly'] = 'Μόνο Προβολή';
        $strings['Purge'] = 'Εκκαθάριση';
        $strings['UsersWillBeDeleted'] = 'χρήστες θα διαγραφούν';
        $strings['BlackoutsWillBeDeleted'] = 'χρόνοι μπλακάουτ θα διαγραφούν';
        $strings['ReservationsWillBePurged'] = 'κρατήσεις θα εκκαθαριστούν';
        $strings['ReservationsWillBeDeleted'] = 'κρατήσεις θα διαγραφούν';
        $strings['PermanentlyDeleteUsers'] = 'Μόνιμη διαγραφή χρηστών που δεν έχουν κάνει είσοδο από';
        $strings['DeleteBlackoutsBefore'] = 'Διαγραφή χρόνων μπλακάουτ πριν από';
        $strings['DeletedReservations'] = 'Κρατήσεις διαγράφηκαν';
        $strings['DeleteReservationsBefore'] = 'Διαγραφή Κρατήσεων πριν από';
        $strings['SwitchToACustomLayout'] = 'Μετάβαση σε προσαρμοσμένη διάταξη';
        $strings['SwitchToAStandardLayout'] = 'Μετάβαση σε στάνταρ διάταξη';
        $strings['ThisScheduleUsesACustomLayout'] = 'Ο προγραμματισμός αυτός χρησιμοποιεί προσαρμοσμένη διάταξη';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Ο προγραμματισμός αυτός χρησιμοποιεί στάνταρ διάταξη';
        $strings['SwitchLayoutWarning'] = 'Είστε σίγουροι για την διαγραφή του τύπου διάταξης; Η ενέργεια αυτή θα αφαιρέσει όλες τις υπάρχουσες σχισμές.';
        $strings['DeleteThisTimeSlot'] = 'Να διαγραφεί η χρονική σχισμή;';
        $strings['Refresh'] = 'Ανανέωση';
        $strings['ViewReservation'] = 'Εμφάνιση Κράτησης';
        $strings['PublicId'] = 'Αριθμός Δημόσιου';
        $strings['Public'] = 'Δημόσιο';
        $strings['AtomFeedTitle'] = '%s Κρατήσεις';
        $strings['DefaultStyle'] = 'Προκαθορισμένο Στυλ';
        $strings['Standard'] = 'Στάνταρ';
        $strings['Wide'] = 'Πλατύ';
        $strings['Tall'] = 'Ψηλό';
        $strings['EmailTemplate'] = 'Πρότυπο Email';
        $strings['SelectEmailTemplate'] = 'Επιλογή Προτύπου Email';
        $strings['ReloadOriginalContents'] = 'Επαναφόρτωση Αρχικού Περιεχομένου';
        $strings['UpdateEmailTemplateSuccess'] = 'Το πρότυπο e-mail ενημερώθηκε';
        $strings['UpdateEmailTemplateFailure'] = 'Δεν ήταν δυνατή η διαγραφή του προτύπου e-mail. Βεβαιωθείτε ότι ο κατάλογος είναι εγγράψιμος.';
        $strings['BulkResourceDelete'] = 'Μαζική Διαγραφή Πόρων';
        $strings['NewVersion'] = 'Νέα έκδοση!';
        $strings['WhatsNew'] = 'Τι νέο υπάρχει;';
        $strings['OnlyViewedCalendar'] = 'Ο προγραμματισμός μπορεί να εμφανιστεί μόνο από την όψη ημερολογίου';
        $strings['Grid'] = 'Πλέγμα';
        $strings['List'] = 'Λίστα';
        $strings['NoReservationsFound'] = 'Δεν βρέθηκαν Κρατήσεις';
        $strings['EmailReservation'] = 'Κράτηση Email';
        $strings['AdHocMeeting'] = 'Συνάντηση επί τόπου';
        $strings['NextReservation'] = 'Επόμενη Κράτηση';
        $strings['MissedCheckin'] = 'Χάθηκε το Checkin';
        $strings['MissedCheckout'] = 'Χάθηκε το Checkout';
        $strings['Utilization'] = 'Χρήση';
        $strings['SpecificTime'] = 'Συγκεκριμένη Ώρα';
        $strings['ReservationSeriesEndingPreference'] = 'Όταν η επαναλαμβανόμενη κράτησή μου φτάνει στο τέλος';
        $strings['NotAttending'] = 'Χωρίς παρακολούθηση';
        $strings['ViewAvailability'] = 'Εμφάνιση διαθεσιμότητας';
        $strings['ReservationDetails'] = 'Λεπτομέρειες Κράτησης';
        $strings['StartTime'] = 'Χρόνος Έναρξης';
        $strings['EndTime'] = 'Χρόνος Λήξης';
        $strings['New'] = 'Νέο';
        $strings['Updated'] = 'Τροποποιημένο';
        $strings['Custom'] = 'Προσαρμογή';
        $strings['AddDate'] = 'Προσθήκη Ημερομηνίας';
        $strings['RepeatOn'] = 'Επανάληψη στις';
        $strings['ScheduleConcurrentMaximum'] = 'Ένα μέγιστο <b>%s</b> πόρων μπορούν να κρατηθούν ταυτόχρονα';
        $strings['ScheduleConcurrentMaximumNone'] = 'Δεν υπάρχει όριο στον αριθμό των ταυτόχρονων κρατήσεων πόρων';
        $strings['ScheduleMaximumConcurrent'] = 'Μέγιστος αριθμός πόρων που μπορούν να κρατηθούν ταυτόχρονα';
        $strings['ScheduleMaximumConcurrentNote'] = 'Όταν οριστεί, ο συνολικός αριθμός πόρων που μπορούν να κρατηθούν ταυτόχρονα για αυτόν τον προγραμματισμό θα είναι περιορισμένος.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Κάθε κράτηση θα είναι περιορισμένη στο μέγιστο αριθμό <b>%s</b> πόρων';
        $strings['ScheduleResourcesPerReservationNone'] = 'Δεν υπάρχει όριο στον αριθμό των πόρων ανά κράτηση';
        $strings['ScheduleResourcesPerReservation'] = 'Μέγιστος αριθμός πόρων ανά κράτηση';
        $strings['ResourceConcurrentReservations'] = 'Να επιτρέπονται %s ταυτόχρονες κρατήσεις';
        $strings['ResourceConcurrentReservationsNone'] = 'Να μην επιτρέπονται ταυτόχρονες κρατήσεις';
        $strings['AllowConcurrentReservations'] = 'Να επιτρέπονται ταυτόχρονες κρατήσεις';
        $strings['ResourceDisplayInstructions'] = 'Δεν επιλέχθηκε πόρος. Μπορείτε να βρείτε τη διεύθυνση για την εμφάνιση ενός πόρου στην Ρυθμίσεις Εφαρμογής, Πόροι. Ο πόρος πρέπει να είναι δημόσια προσπελάσιμος.';
        $strings['Owner'] = 'Ιδιοκτήτης';
        $strings['MaximumConcurrentReservations'] = 'Μέγιστες Ταυτόχρονες Κρατήσεις';
        $strings['NotifyUsers'] = 'Ειδοποίηση Χρηστών';
        $strings['Message'] = 'Μήνυμα';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Οποιοσδήποτε με κράτηση μέσα στο επόμενο';
        $strings['ChangeResourceStatus'] = 'Αλλαγή Κατάστασης Πόρου';
        $strings['UpdateGroupsOnImport'] = 'Ενημέρωση υπάρχουσας ομάδας αν το όνομα ταιριάζει';
        $strings['GroupsImportInstructions'] = '<ul><li>Το αρχείο πρέπει να είναι σε μορφή CSV.</li><li>Το όνομα απαιτείται.</li><li>Οι λίστες μελών πρέπει να είναι λίστες e-mail χωρισμένες με κόμμα.</li><li>Οι άδειες λίστες μελών κατά την ενημερώση των ομάδων δε θα επηρεάζουν τα μέλη.</li><li>Οι λίστες δικαιωμάτων πρέπει να είναι λίστες πόρων χωρισμένες με κόμμα.</li><li>Οι άδειες λίστες δικαιωμάτων κατά την ενημέρωση των ομάδων δε θα επηρεάζουν τα δικαιώματα.</li><li>Χρησιμοποιήστε το παρακάτω πρότυπο ως παράδειγμα.</li></ul>';
        $strings['PhoneRequired'] = 'Το τηλέφωνο απαιτείται';
        $strings['OrganizationRequired'] = 'Ο οργανισμός απαιτείται';
        $strings['PositionRequired'] = 'Η θέση απαιτείται';
        $strings['GroupMembership'] = 'Ιδιοκτησία ομάδας';
        $strings['AvailableGroups'] = 'Διαθέσιμες ομάδες';
        $strings['CheckingAvailabilityError'] = 'Δεν είναι δυνατή η ανάκτηση της διαθεσιμότητας πόρου - πάρα πολλοί πόροι';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Εγκατάσταση του LibreBooking (μόνο MySQL)';
        $strings['IncorrectInstallPassword'] = 'Συγγνώμη, το συνθηματικό ήταν λάθος.';
        $strings['SetInstallPassword'] = 'Πρέπει να ορίσετε ένα συνθηματικό εγκατάστασης προτού εκτελεστεί η εγκατάσταση.';
        $strings['InstallPasswordInstructions'] = 'Στο %s ορίστε το %s σε ένα συνθηματικό που είναι τυχαίο και δύσκολο να μαντεφθεί, μετά επιστρέψτε σε αυτή τη σελίδα.<br/>Μπορείτε να χρησιμοποιήσετε %s';
        $strings['NoUpgradeNeeded'] = 'Το LibreBooking είναι ενημερωμένο. Δεν χρειάζεται αναβάθμιση.';
        $strings['ProvideInstallPassword'] = 'Παρακαλούμε εισάγετε το συνθηματικό εγκατάστασης.';
        $strings['InstallPasswordLocation'] = 'Αυτό μπορεί να βρεθεί στο %s σε %s.';
        $strings['VerifyInstallSettings'] = 'Επαληθεύστε τις ακόλουθες ρυθμίσεις προτού συνεχίσετε. Μπορείτε να τις αλλάξετε στο %s.';
        $strings['DatabaseName'] = 'Όνομα βάσης δεδομένων';
        $strings['DatabaseUser'] = 'Όνομα χρήστη βάσης δεδομένων';
        $strings['DatabaseHost'] = 'Διακομιστής βάσης δεδομένων';
        $strings['DatabaseCredentials'] = 'Θα πρέπει να εισάγετε τα διαπιστευτήρια χρήστη στη MySQL που να έχει δικαίωμα να δημιουργεί βάση δεδομένων. Αν δεν τα γνωρίζετε, επικοινωνήστε με τον διαχειριστή της βάσης δεδομένων. Σε αρκετές περιπτώσεις, ο root θα δουλέψει.';
        $strings['MySQLUser'] = 'Χρήστης MySQL';
        $strings['InstallOptionsWarning'] = 'Οι παρακάτω επιλογές λογικά θα δουλέψουν σε μοιραζόμενο περιβάλλον. Αν κάνετε εγκατάσταση σε μοιραζόμενο περιβάλλον, χρησιμοποιήστε τα εργαλεία του οδηγού της MySQL για να ολοκληρώσετε τα βήματα.';
        $strings['CreateDatabase'] = 'Δημιουργία της βάσης';
        $strings['CreateDatabaseUser'] = 'Δημιουργία του χρήστη της βάσης';
        $strings['PopulateExampleData'] = 'Εισαγωγή δοκιμαστικών δεδομένων. Δημιουργεί διαχειριστικό λογαριασμό: admin/password και λογαριασμό χρήστη: user/password';
        $strings['DataWipeWarning'] = 'Προσοχή: Αυτό θα διαγράψει τυχόν υπάρχοντα δεδομένα';
        $strings['RunInstallation'] = 'Εκτέλεση της εγκατάστασης';
        $strings['UpgradeNotice'] = 'Κάνετε αναβάθμιση από την έκδοση <b>%s</b> στην έκδοση <b>%s</b>';
        $strings['RunUpgrade'] = 'Εκτέλεση Αναβάθμισης';
        $strings['Executing'] = 'Εκτέλεση';
        $strings['StatementFailed'] = 'Απέτυχε. Λεπτομέρειες:';
        $strings['SQLStatement'] = 'Ερώτημα SQL:';
        $strings['ErrorCode'] = 'Κωδικός Σφάλματος:';
        $strings['ErrorText'] = 'Κείμενο Σφάλματος:';
        $strings['InstallationSuccess'] = 'Η εγκατάσταση ολοκληρώθηκε με επιτυχία!';
        $strings['RegisterAdminUser'] = 'Κάντε εγγραφή για τον διαχειριστή. Αυτό απαιτείται αν δεν κάνατε εισαγωγή των δοκιμαστικών δεδομένων. Βεβαιωθείτε ότι το είναι $conf[\'settings\'][\'allow.self.registration\'] = \'true\' στο αρχείο %s.';
        $strings['LoginWithSampleAccounts'] = 'Αν κάνατε εισαγωγή των δοκιμαστικών δεδομένων, κάνετε είσοδο με τα στοιχεία χρήστη admin/password για το διαχειριστή ή user/password για τον απλό χρήστη.';
        $strings['InstalledVersion'] = 'Εκτελείτε τώρα την έκδοση %s του LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Προτείνεται να αναβαθμίσετε το αρχείο ρυθμίσεών σας';
        $strings['InstallationFailure'] = 'Υπάρχουν προβλήματα με την εγκατάσταση. Παρακαλούμε διορθώστε τα και ξαναδοκιμάστε την εγκατάσταση.';
        $strings['ConfigureApplication'] = 'Παραμετροποίηση του LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Το αρχείο ρυθμίσεών σας είναι τώρα ενημερωμένο!';
        $strings['ConfigUpdateFailure'] = 'Δεν ήταν δυνατή η αυτόματη ενημέρωση του αρχείου ρυθμίσεών σας. Παρακαλούμε εισάγετε τα παρακάτω σε όλο το περιεχόμενο του αρχείου config.php:';
        $strings['ScriptUrlWarning'] = 'Η ρύθμιση <em>script.url</em> ενδέχεται να μην είναι σωστή. Αυτή τη στιγμή είναι <strong>%s</strong>, ενώ θεωρούμε ότι πρέπει να είναι <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Δεν ήταν δυνατή η εύρεση του ονόματος χρήστη ή του συνθηματικού σας';
        $strings['ReservationFailed'] = 'Δεν ήταν δυνατή η κράτησή σας';
        $strings['MinNoticeError'] = 'Η κράτηση απαιτεί πρότερη ειδοποίηση. Η νωρίτερη ημερομηνία και ώρα που μπορεί να γίνει είναι %s.';
        $strings['MinNoticeErrorUpdate'] = 'Η τροποποίηση της κράτησης απαιτεί νωρίτερη προειδοποίηση. Οι κρατήσεις πριν τις %s δεν μπορούν να τροποποιηθούν.';
        $strings['MinNoticeErrorDelete'] = 'Η διαγραφή της κράτησης απαιτεί νωρίτερη προειδοποίηση. Οι κρατήσεις πριν τις %s δεν μπορούν να διαγραφούν.';
        $strings['MaxNoticeError'] = 'Η κράτηση δεν μπορεί να γίνει τόσο μπροστά στο μέλλον. Η τελευταία ημερομηνία και ώρα που μπορεί να γίνει είναι %s.';
        $strings['MinDurationError'] = 'Η κράτηση πρέπει να διαρκέσει τουλάχιστον %s.';
        $strings['MaxDurationError'] = 'Η κράτηση δεν μπορεί να διαρκέσει περισσότερο από %s.';
        $strings['ConflictingAccessoryDates'] = 'Δεν υπάρχει αρκετός από τον παρακάτω εξοπλισμό:';
        $strings['NoResourcePermission'] = 'Δεν έχετε την εξουσιοδότηση να προσπελάσετε έναν ή περισσότερους από τους ζητούμενους πόρους.';
        $strings['ConflictingReservationDates'] = 'Υπάρχουν κρατήσεις σε σύγκρουση για τις ακόλουθες ημερομηνίες:';
        $strings['InstancesOverlapRule'] = 'Ορισμένα στιγμιότυπα της σειράς κρατήσεων αλληλοεπικαλύπτονται:';
        $strings['StartDateBeforeEndDateRule'] = 'Η ημερομηνία και ώρα έναρξης πρέπει να είναι πριν την ημερομηνία και ώρα λήξης.';
        $strings['StartIsInPast'] = 'Η ημερομηνία και ώρα έναρξης δεν μπορούν να είναι στο παρελθόν.';
        $strings['EmailDisabled'] = 'Ο διαχειριστής έχει απενεργοποιήσει τις ειδοποιήσεις με email.';
        $strings['ValidLayoutRequired'] = 'Τα κενά πρέπει να παρέχονται για όλες τις 24 ώρες της ημέρας με αρχή και λήξη στις 12:00 ΠΜ.';
        $strings['CustomAttributeErrors'] = 'Υπάρχουν προβλήματα με τις πρόσθετες ιδιότητες που εισαγάγατε:';
        $strings['CustomAttributeRequired'] = 'Το %s είναι απαιτούμενο πεδίο.';
        $strings['CustomAttributeInvalid'] = 'Η τιμή για το %s είναι μη έγκυρη.';
        $strings['AttachmentLoadingError'] = 'Συγγνώμη, υπήρξε σφάλμα κατά την φόρτωση του ζητούμενου αρχείου.';
        $strings['InvalidAttachmentExtension'] = 'Μπορείτε να ανεβάσετε μόνο αρχεία του τύπου: %s';
        $strings['InvalidStartSlot'] = 'Η ημερομηνία και ώρα έναρξης δεν είναι έγκυρη.';
        $strings['InvalidEndSlot'] = 'Η ημερομηνία και ώρα λήξης δεν είναι έγκυρη.';
        $strings['MaxParticipantsError'] = 'Το %s μπορεί να υποστηρίξει μόνο %s συμμετέχοντες.';
        $strings['ReservationCriticalError'] = 'Υπήρξε ένα κρίσιμο σφάλμα κατά την αποθήκευση της κράτησης. Αν αυτό συνεχιστεί, επικοινωνήστε με το διαχειριστή.';
        $strings['InvalidStartReminderTime'] = 'Η ώρα υπενθύμισης της έναρξης δεν είναι έγκυρη.';
        $strings['InvalidEndReminderTime'] = 'Η ώρα υπενθύμισης της λήξης δεν είναι έγκυρη.';
        $strings['QuotaExceeded'] = 'Έγινε υπέρβαση της ποσόστωσης.';
        $strings['MultiDayRule'] = 'Το %s δεν επιτρέπει τις κρατήσεις μεταξύ ημερών.';
        $strings['InvalidReservationData'] = 'Υπήρξαν προβλήματα κατά την αίτηση κράτησής σας.';
        $strings['PasswordError'] = 'Το συνθηματικό πρέπει να περιέχει τουλάχιστον %s χαρακτήρες και τουλάχιστον %s αριθμούς.';
        $strings['PasswordErrorRequirements'] = 'Το συνθηματικό πρέπει να περιέχει ένα συνδυασμό από τουλάχιστον %s κεφαλαίους και πεζούς χαρακτήρες και %s αριθμούς.';
        $strings['NoReservationAccess'] = 'Δεν επιτρέπεται να αλλάξετε την κράτηση.';
        $strings['PasswordControlledExternallyError'] = 'Το συνθηματικό σας ελέγχεται από εξωτερικό σύστημα και δεν μπορεί να αλλάξει από εδώ.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Ο εξοπλισμός %s μπορεί να κρατηθεί μόνο με τους πόρους %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Πρέπει να κάνετε κράτηση τουλάχιστον %s του εξοπλισμού %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Δεν μπορείτε να κάνετε κράτηση περισσότερων από %s του εξοπλισμού %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Ο εξοπλισμός \'%s\' δεν μπορεί να κρατηθεί μαζί με τους ζητούμενους πόρους';
        $strings['NoResources'] = 'Δεν έχετε προσθέσει πόρους.';
        $strings['ParticipationNotAllowed'] = 'Δεν σας επιτρέπεται να συμμετέχετε στην κράτηση.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Δεν μπορεί να γίνει check-in για την κράτηση.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Δεν μπορεί να γίνει check-out από την κράτηση.';
        $strings['InvalidEmailDomain'] = 'Η διεύθυνση email δεν είναι από επιτρεπόμενο όνομα χώρου';
        $strings['TermsOfServiceError'] = 'Πρέπει να αποδεχτείτε τους Όρους Υπηρεσίας';
        $strings['UserNotFound'] = 'Δεν ήταν δυνατό να βρεθεί ο χρήστης';
        $strings['ScheduleAvailabilityError'] = 'Ο προγραμματισμός είναι διαθέσιμος μεταξύ %s και %s';
        $strings['ReservationNotFoundError'] = 'Δε βρέθηκε η κράτηση';
        $strings['ReservationNotAvailable'] = 'Η κράτηση δεν είναι διαθέσιμη';
        $strings['TitleRequiredRule'] = 'Ο τίτλος κράτησης είναι απαιτούμενος';
        $strings['DescriptionRequiredRule'] = 'Η περιγραφή για την κράτηση είναι απαιτούμενη';
        $strings['WhatCanThisGroupManage'] = 'Τι μπορεί να διαχειρίζεται η ομάδα αυτή;';
        $strings['ReservationParticipationActivityPreference'] = 'Όταν κάποιος εγγράφεται ή αποχωρεί από την κράτησή μου';
        $strings['RegisteredAccountRequired'] = 'Μόνο εγγεγραμμένοι χρήστες μπορούν να κάνουν κρατήσεις';
        $strings['InvalidNumberOfResourcesError'] = 'Ο μέγιστος αριθμός πόρων που μπορούν να κρατηθούν σε μια μοναδική κράτηση είναι %s';
        $strings['ScheduleTotalReservationsError'] = 'Ο προγραμματισμός επιτρέπει μόνο %s πόρους να κρατηθούν ταυτόχρονα. Η κράτηση θα παραβιάσει αυτό το όριο στις ακόλουθες ημερομηνίες:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Καταχώριση κράτησης';
        $strings['EditReservation'] = 'Τροποποίηση Κράτησης';
        $strings['LogIn'] = 'Είσοδος';
        $strings['ManageReservations'] = 'Κρατήσεις';
        $strings['AwaitingActivation'] = 'Σε αναμονή ενεργοποίησης';
        $strings['PendingApproval'] = 'Σε Εκκρεμότητα Έγκρισης';
        $strings['ManageSchedules'] = 'Προγραμματισμοί';
        $strings['ManageResources'] = 'Πόροι';
        $strings['ManageAccessories'] = 'Εξοπλισμός';
        $strings['ManageUsers'] = 'Χρήστες';
        $strings['ManageGroups'] = 'Ομάδες';
        $strings['ManageQuotas'] = 'Ποσοστώσεις';
        $strings['ManageBlackouts'] = 'Χρόνοι Μπλακάουτ';
        $strings['MyDashboard'] = 'Ο Πίνακάς μου';
        $strings['ServerSettings'] = 'Ρυθμίσεις Διακομιστή';
        $strings['Dashboard'] = 'Αίθουσες';
        $strings['Help'] = 'Βοήθεια';
        $strings['Administration'] = 'Διαχείριση';
        $strings['About'] = 'Σχετικά';
        $strings['Bookings'] = 'Κρατήσεις';
        $strings['Schedule'] = 'Προγραμματισμός';
        $strings['Account'] = 'Λογαριασμός';
        $strings['EditProfile'] = 'Επεξεργασία του προφίλ μου';
        $strings['FindAnOpening'] = 'Εύρεση κενού';
        $strings['OpenInvitations'] = 'Ανοικτές Προσκλήσεις';
        $strings['ResourceCalendar'] = 'Ημερολόγιο Πόρων';
        $strings['Reservation'] = 'Νέα κράτηση';
        $strings['Install'] = 'Εγκατάσταση';
        $strings['ChangePassword'] = 'Αλλαγή Συνθηματικού';
        $strings['MyAccount'] = 'Ο Λογαριασμός μου';
        $strings['Profile'] = 'Προφίλ';
        $strings['ApplicationManagement'] = 'Διαχείριση';
        $strings['ForgotPassword'] = 'Ξέχασα το συνθηματικό μου';
        $strings['NotificationPreferences'] = 'Προτιμήσεις για τις Ειδοποιήσεις';
        $strings['ManageAnnouncements'] = 'Ανακοινώσεις';
        $strings['Responsibilities'] = 'Υποχρεώσεις';
        $strings['GroupReservations'] = 'Κρατήσεις Ομάδας';
        $strings['ResourceReservations'] = 'Κρατήσεις Πόρων';
        $strings['Customization'] = 'Παραμετροποίηση';
        $strings['Attributes'] = 'Ιδιότητες';
        $strings['AccountActivation'] = 'Ενεργοποίηση Λογαριασμού';
        $strings['ScheduleReservations'] = 'Κρατήσεις Ημερολογίου';
        $strings['Reports'] = 'Αναφορές';
        $strings['GenerateReport'] = 'Δημιουργία Νέας Αναφοράς';
        $strings['MySavedReports'] = 'Οι Αποθηκευμένες Αναφορές μου';
        $strings['CommonReports'] = 'Κοινές Αναφορές';
        $strings['ViewDay'] = 'Προβολή Ημέρας';
        $strings['Group'] = 'Ομάδα';
        $strings['ManageConfiguration'] = 'Παραμετροποίηση εφαρμογής';
        $strings['LookAndFeel'] = 'Εμφάνιση και όψη';
        $strings['ManageResourceGroups'] = 'Ομάδες Πόρων';
        $strings['ManageResourceTypes'] = 'Τύποι Πόρων';
        $strings['ManageResourceStatus'] = 'Καταστάσεις Πόρων';
        $strings['ReservationColors'] = 'Χρώματα Κρατήσεων';
        $strings['SearchReservations'] = 'Αναζήτηση στις Κρατήσεις';
        $strings['ManagePayments'] = 'Πληρωμές';
        $strings['ViewCalendar'] = 'Εμφάνιση Ημερολογίου';
        $strings['DataCleanup'] = 'Εκκαθάριση Δεδομένων';
        $strings['ManageEmailTemplates'] = 'Διαχείριση Προτύπων Email';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'Κ';
        $strings['DayMondaySingle'] = 'Δ';
        $strings['DayTuesdaySingle'] = 'Τ';
        $strings['DayWednesdaySingle'] = 'Τ';
        $strings['DayThursdaySingle'] = 'Π';
        $strings['DayFridaySingle'] = 'Π';
        $strings['DaySaturdaySingle'] = 'Σ';

        $strings['DaySundayAbbr'] = 'Κυρ';
        $strings['DayMondayAbbr'] = 'Δευ';
        $strings['DayTuesdayAbbr'] = 'Τρί';
        $strings['DayWednesdayAbbr'] = 'Τετ';
        $strings['DayThursdayAbbr'] = 'Πέμ';
        $strings['DayFridayAbbr'] = 'Παρ';
        $strings['DaySaturdayAbbr'] = 'Σάβ';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Η Κράτησή σας εγκρίθηκε';
        $strings['ReservationCreatedSubject'] = 'Η Κράτησή σας δημιουργήθηκε';
        $strings['ReservationUpdatedSubject'] = 'Η Κράτησή σας τροποποιήθηκε';
        $strings['ReservationDeletedSubject'] = 'Η Κράτησή σας αφαιρέθηκε';
        $strings['ReservationCreatedAdminSubject'] = 'Ειδοποίηση: Δημιουργήθηκε μια Κράτηση';
        $strings['ReservationUpdatedAdminSubject'] = 'Ειδοποίηση: Τροποποιήθηκε μια Κράτηση';
        $strings['ReservationDeleteAdminSubject'] = 'Ειδοποίηση: Αφαιρέθηκε μια Κράτηση';
        $strings['ReservationApprovalAdminSubject'] = 'Ειδοποίηση: Η Κράτηση απαιτεί την έγκρισή σας';
        $strings['ParticipantAddedSubject'] = 'Ειδοποίηση Συμμετοχής σε Κράτηση';
        $strings['ParticipantDeletedSubject'] = 'Η Κράτηση αφαιρέθηκε';
        $strings['InviteeAddedSubject'] = 'Πρόσκληση Συμμετοχής σε Κράτηση';
        $strings['ResetPasswordRequest'] = 'Αίτηση Επαναφοράς Συνθηματικού';
        $strings['ActivateYourAccount'] = 'Παρακαλούμε ενεργοποιήστε το λογαριασμό σας';
        $strings['ReportSubject'] = 'Οι Αναφορές που ζητήσατε (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Η Κράτηση για %s αρχίζει σύντομα';
        $strings['ReservationEndingSoonSubject'] = 'Η Κράτηση για %s τελειώνει σύντομα';
        $strings['UserAdded'] = 'Ένας νέος χρήστης προστέθηκε';
        $strings['UserDeleted'] = 'Ο λογαριασμός χρήση για %s διαγράφηκε από τον %s';
        $strings['GuestAccountCreatedSubject'] = 'Οι πληροφορίες του λογαριασμού σας %s';
        $strings['AccountCreatedSubject'] = 'Οι πληροφορίες του λογαριασμού σας %s';
        $strings['InviteUserSubject'] = 'Ο/Η %s σας προσκάλεσε για συμμετοχή στο %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Η Κράτηση για %s έχει γίνει αποδεκτή';
        $strings['ReservationCreatedSubjectWithResource'] = 'Η Κράτηση για %s δημιουργήθηκε';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Η Κράτηση για %s τροποποιήθηκε';
        $strings['ReservationDeletedSubjectWithResource'] = 'Η Κράτηση για %s αφαιρέθηκε';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Ειδοποίηση: Δημιουργήθηκε η Κράτηση %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Ειδοποίηση: Τροποποιήθηκε η Κράτηση %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Ειδοποίηση: Αφαιρέθηκε η Κράτηση %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Ειδοποίηση: Η Κράτηση %s απαιτεί την έγκρισή σας';
        $strings['ParticipantAddedSubjectWithResource'] = 'Ο/Η %s σας πρόσθεσε στην Κράτηση %s';
        $strings['ParticipantDeletedSubjectWithResource'] = 'Ο/Η %s σας αφαίρεση από την Κράτηση %s';
        $strings['InviteeAddedSubjectWithResource'] = 'Ο/Η %s σας προσκάλεσε σε μια Κράτηση για %s';
        $strings['MissedCheckinEmailSubject'] = 'Χάθηκε το checkin για %s';
        $strings['ReservationShareSubject'] = 'Ο/Η %s κοινοποίησε την Κράτηση %s';
        $strings['ReservationSeriesEndingSubject'] = 'Η σειρά Κρατήσεων %s ολοκληρώνεται στις %s';
        $strings['ReservationParticipantAccept'] = 'Ο/Η %s αποδέχθηκε την πρόσκλησή σας για την Κράτησή σας %s για τις %s';
        $strings['ReservationParticipantDecline'] = 'Ο/Η %s απέρριψε την πρόσκληση για συμμετοχή για την Κράτησή σας %s για τις %s';
        $strings['ReservationParticipantJoin'] = 'Ο/Η %s έχει δηλώσει συμμετοχή για την Κράτησή σας %s για τις %s';
        $strings['ReservationParticipantJoin'] = 'Ο/Η %s έκανε εγγραφή στην κράτησή σας για %s στις %s';
        $strings['ReservationAvailableSubject'] = 'Ο/Η %s είναι διαθέσιμος στις %s';
        $strings['ResourceStatusChangedSubject'] = 'Η διαθεσιμότητα του %s άλλαξε';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Δεν έχετε προηγούμενες κρατήσεις';
        $strings['PastReservations'] = 'Προηγούμενες κρατήσεις';
        $strings['AllNoPastReservations'] = 'Δεν υπάρχουν προηγούμενες κρατήσεις τις τελευταίες %s ημέρες';
        $strings['AllPastReservations'] = 'Όλες οι προηγούμενες κρατήσεις';
        $strings['Yesterday'] = 'Χθες';
        $strings['EarlierThisWeek'] = 'Νωρίτερα αυτήν την εβδομάδα';
        $strings['PreviousWeek'] = 'Προηγούμενη εβδομάδα';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Η ομάδα σας δεν έχει καμία προσεχή κράτηση';
        $strings['GroupUpcomingReservations'] = 'Μελλοντικές κρατήσεις της ομάδας(ών) μου';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Προέκυψε σφάλμα κατά τη σύνδεση με το Facebook. Παρακαλούμε δοκιμάστε ξανά.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Δεν έχετε κρατήσεις που αναμένουν έγκριση';
        $strings['PendingApprovalReservations'] = 'Κρατήσεις προς Έγκριση';
        $strings['LaterThisMonth'] = 'Αργότερα αυτόν το μήνα';
        $strings['LaterThisYear'] = 'Αργότερα φέτος';
        $strings['Remaining'] = 'Υπολειπόμενο';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Δεν υπάρχουν λείπουσες κρατήσεις εξόδου';
        $strings['MissingCheckOutReservations'] = 'Λείπουσες κρατήσεις εξόδου';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Δεν μπορείτε να δείτε λεπτομέρειες κράτησης επειδή δεν έχετε άδειες για κανένα από τους πόρους σε αυτήν την κράτηση';
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
        $days['full'] = ['Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη', 'Πέμπτη', 'Παρασκευή', 'Σάββατο'];
        // The three letter abbreviation
        $days['abbr'] = ['Κυρ', 'Δευ', 'Τρί', 'Τετ', 'Πέμ', 'Παρ', 'Σάβ'];
        // The two letter abbreviation
        $days['two'] = ['Κυ', 'Δε', 'Τρ', 'Τε', 'Πέ', 'Πα', 'Σά'];
        // The one letter abbreviation
        $days['letter'] = ['Κ', 'Δ', 'T', 'Τ', 'Π', 'Π', 'Σ'];

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
        $months['full'] = ['Ιανουάριος', 'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος', 'Μάιος', 'Ιούνιος', 'Ιούλιος', 'Αύγουστος', 'Σεπτέμβριος', 'Οκτώβριος', 'Νοέμβριος', 'Δεκέμβριος'];
        // The three letter month name
        $months['abbr'] = ['Ιαν', 'Φεβ', 'Μάρ', 'Απρ', 'Μάι', 'Ιούν', 'Ιούλ', 'Αύγ', 'Σεπ', 'Οκτ', 'Νοέ', 'Δεκ'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['Α', 'Β', 'Γ', 'Δ', 'Ε', 'Ζ', 'Η', 'Θ', 'Ι', 'Κ', 'Λ', 'Μ', 'Ν', 'Ξ', 'Ο', 'Π', 'Ρ', 'Σ', 'Τ', 'Υ', 'Φ', 'Χ', 'Ψ', 'Ω'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'el';
    }
}
