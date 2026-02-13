<?php

require_once('en_gb.php');

class tr_tr extends en_gb
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

        $strings['FirstName'] = 'İsim';
        $strings['LastName'] = 'Soyisim';
        $strings['Timezone'] = 'Zaman Dilimi';
        $strings['Edit'] = 'Düzenle';
        $strings['Change'] = 'Değiştir';
        $strings['Rename'] = 'Yeniden Adlandır';
        $strings['Remove'] = 'Kaldır';
        $strings['Delete'] = 'Sil';
        $strings['Update'] = 'Düzenle';
        $strings['Cancel'] = 'İptal';
        $strings['Add'] = 'Ekle';
        $strings['Name'] = 'İsim';
        $strings['Yes'] = 'Evet';
        $strings['No'] = 'Hayır';
        $strings['FirstNameRequired'] = 'İsim zorunludur.';
        $strings['LastNameRequired'] = 'Soyisim zorunludur.';
        $strings['PwMustMatch'] = 'Şifreler eşleşmelidir.';
        $strings['ValidEmailRequired'] = 'Geçerli bir eposta adresi gereklidir.';
        $strings['UniqueEmailRequired'] = 'Bu eposta adresi daha önce kayıt edilmiş.';
        $strings['UniqueUsernameRequired'] = 'Bu kullanıcı adı daha önce kayıt edilmiş.';
        $strings['UserNameRequired'] = 'Kullanıcı adı zorunluduru.';
        $strings['CaptchaMustMatch'] = 'Lütfen resimde görünen karakterleri giriniz.';
        $strings['Today'] = 'Bugün';
        $strings['Week'] = 'Hafta';
        $strings['Month'] = 'Ay';
        $strings['BackToCalendar'] = 'Takvime Dön';
        $strings['BeginDate'] = 'Başlangıç';
        $strings['EndDate'] = 'Bitiş';
        $strings['Username'] = 'Kullanıcı Adı';
        $strings['Password'] = 'Şifre';
        $strings['PasswordConfirmation'] = 'Şifre Onayla';
        $strings['DefaultPage'] = 'Anasayfa';
        $strings['MyCalendar'] = 'Takvimim';
        $strings['ScheduleCalendar'] = 'Takvim Planla';
        $strings['Registration'] = 'Üyelik';
        $strings['NoAnnouncements'] = 'Bildirim yok';
        $strings['Announcements'] = 'Yaklaşan Bildirimler';
        $strings['NoUpcomingReservations'] = 'Rezervasyon yok';
        $strings['UpcomingReservations'] = 'Yaklaşan Rezervasyonlar';
        $strings['AllNoUpcomingReservations'] = 'Önümüzdeki %s gün içinde herhangi bir rezervasyon yok.';
        $strings['AllUpcomingReservations'] = 'Bütün Rezervasyonlar';
        $strings['ShowHide'] = 'Gizle/Göster';
        $strings['Error'] = 'Hata';
        $strings['ReturnToPreviousPage'] = 'Son sayfaya tekrar gidiniz';
        $strings['UnknownError'] = 'Bilinmeyen hata!';
        $strings['InsufficientPermissionsError'] = 'Burayı görmek için yetkiniz bulunmamaktadır.';
        $strings['MissingReservationResourceError'] = 'Kaynak belirtilmedi';
        $strings['MissingReservationScheduleError'] = 'Zaman belirtilmedi';
        $strings['DoesNotRepeat'] = 'Tekrar Yok';
        $strings['Daily'] = 'Günlük';
        $strings['Weekly'] = 'Haftalık';
        $strings['Monthly'] = 'Aylık';
        $strings['Yearly'] = 'Yıllık';
        $strings['RepeatPrompt'] = 'Tekrar';
        $strings['hours'] = 'saat';
        $strings['days'] = 'gün';
        $strings['weeks'] = 'hafta';
        $strings['months'] = 'ay';
        $strings['years'] = 'yıl';
        $strings['day'] = 'gün';
        $strings['week'] = 'hafta';
        $strings['month'] = 'ay';
        $strings['year'] = 'yıl';
        $strings['repeatDayOfMonth'] = 'day of month';
        $strings['repeatDayOfWeek'] = 'day of week';
        $strings['RepeatUntilPrompt'] = 'Until';
        $strings['RepeatEveryPrompt'] = 'Her';
        $strings['RepeatDaysPrompt'] = 'On';
        $strings['CreateReservationHeading'] = 'Yeni Rezervasyon';
        $strings['EditReservationHeading'] = 'Editing Reservation %s';
        $strings['ViewReservationHeading'] = 'Viewing Reservation %s';
        $strings['ReservationErrors'] = 'Değiştir';
        $strings['Create'] = 'Oluştur';
        $strings['ThisInstance'] = 'Only This Instance';
        $strings['AllInstances'] = 'All Instances';
        $strings['FutureInstances'] = 'Future Instances';
        $strings['Print'] = 'Yazdır';
        $strings['ShowHideNavigation'] = 'Menü Gizle/Göster';
        $strings['ReferenceNumber'] = 'Referans Numarası';
        $strings['Tomorrow'] = 'Yarın';
        $strings['LaterThisWeek'] = 'Bu Hafta Sonraki Günler';
        $strings['NextWeek'] = 'Sonraki Hafta';
        $strings['SignOut'] = 'Çıkış';
        $strings['LayoutDescription'] = 'Starts on %s, showing %s days at a time';
        $strings['AllResources'] = 'Bütün Kaynaklar';
        $strings['TakeOffline'] = 'Take Offline';
        $strings['BringOnline'] = 'Bring Online';
        $strings['AddImage'] = 'Resim Ekle';
        $strings['NoImage'] = 'Resim Bulunmamaktadır';
        $strings['Move'] = 'Taşı';
        $strings['AppearsOn'] = 'Appears On %s';
        $strings['Location'] = 'Yer';
        $strings['NoLocationLabel'] = '(yok)';
        $strings['Contact'] = 'İletişim';
        $strings['NoContactLabel'] = '(yok)';
        $strings['Description'] = 'Açıklama';
        $strings['NoDescriptionLabel'] = '(açıklama yok)';
        $strings['Notes'] = 'Notlar';
        $strings['NoNotesLabel'] = '(not yok)';
        $strings['NoTitleLabel'] = '(başlıksız)';
        $strings['UsageConfiguration'] = 'Kullanım Ayarları';
        $strings['ChangeConfiguration'] = 'Değişiklik Ayarları';
        $strings['ResourceMinLength'] = 'Reservations must last at least %s';
        $strings['ResourceMinLengthNone'] = 'There is no minimum reservation duration';
        $strings['ResourceMaxLength'] = 'Reservations cannot last more than %s';
        $strings['ResourceMaxLengthNone'] = 'There is no maximum reservation duration';
        $strings['ResourceRequiresApproval'] = 'Reservations must be approved';
        $strings['ResourceRequiresApprovalNone'] = 'Reservations do not require approval';
        $strings['ResourcePermissionAutoGranted'] = 'Permission is automatically granted';
        $strings['ResourcePermissionNotAutoGranted'] = 'Permission is not automatically granted';
        $strings['ResourceMinNotice'] = 'Reservations must be made at least %s prior to start time';
        $strings['ResourceMinNoticeNone'] = 'Reservations can be made up until the current time';
        $strings['ResourceMaxNotice'] = 'Reservations must not end more than %s from the current time';
        $strings['ResourceMaxNoticeNone'] = 'Reservations can end at any point in the future';
        $strings['ResourceBufferTime'] = 'There must be %s between reservations';
        $strings['ResourceBufferTimeNone'] = 'There is no buffer between reservations';
        $strings['ResourceAllowMultiDay'] = 'Reservations can be made across days';
        $strings['ResourceNotAllowMultiDay'] = 'Reservations cannot be made across days';
        $strings['ResourceCapacity'] = 'This resource has a capacity of %s people';
        $strings['ResourceCapacityNone'] = 'This resource has unlimited capacity';
        $strings['AddNewResource'] = 'Yeni Kaynak Ekle';
        $strings['AddNewUser'] = 'Yeni Üye Ekle';
        $strings['AddResource'] = 'Kaynak Ekle';
        $strings['Capacity'] = 'Kapasite';
        $strings['Access'] = 'Erişim';
        $strings['Duration'] = 'Süre';
        $strings['Active'] = 'Aktif';
        $strings['Inactive'] = 'Pasif';
        $strings['ResetPassword'] = 'Şifre Güncelle';
        $strings['LastLogin'] = 'Son Giriş';
        $strings['Search'] = 'Ara';
        $strings['ResourcePermissions'] = 'Kaynak İzinleri';
        $strings['Reservations'] = 'Rezervasyonlar';
        $strings['Groups'] = 'Gruplar';
        $strings['Users'] = 'Üyeler';
        $strings['AllUsers'] = 'Tüm Üyeler';
        $strings['AllGroups'] = 'Tüm Gruplar';
        $strings['AllSchedules'] = 'Tüm Planlar';
        $strings['UsernameOrEmail'] = 'Kullanıcı adı veya Eposta';
        $strings['Members'] = 'Üyeler';
        $strings['QuickSlotCreation'] = 'Create slots every %s minutes between %s and %s';
        $strings['ApplyUpdatesTo'] = 'Apply Updates To';
        $strings['CancelParticipation'] = 'Katılımı İptal Et';
        $strings['Attending'] = 'Katılacak';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['QuotaEnforcement'] = 'Enforced %s %s';
        $strings['reservations'] = 'reservations';
        $strings['reservation'] = 'reservation';
        $strings['ChangeCalendar'] = 'Takvim Değiştir';
        $strings['AddQuota'] = 'Add Quota';
        $strings['FindUser'] = 'Kullanıcı Bul';
        $strings['Created'] = 'Oluşturuldu';
        $strings['LastModified'] = 'Son Güncelleme';
        $strings['GroupName'] = 'Grup Adı';
        $strings['GroupMembers'] = 'Grup Üyeleri';
        $strings['GroupRoles'] = 'Grup Rolleri';
        $strings['GroupAdmin'] = 'Grup Yöneticisi';
        $strings['Actions'] = 'Aksiyon';
        $strings['CurrentPassword'] = 'Mevcut Şifre';
        $strings['NewPassword'] = 'Yeni Şifre';
        $strings['InvalidPassword'] = 'Mevcut şifre hatalı';
        $strings['PasswordChangedSuccessfully'] = 'Şifreniz başarıyla güncellendi';
        $strings['SignedInAs'] = 'Şu kullanıcı ile giriş yapıldı:';
        $strings['NotSignedIn'] = 'Giriş yapılmadı';
        $strings['ReservationTitle'] = 'Başlık';
        $strings['ReservationDescription'] = 'Açıklama';
        $strings['ResourceList'] = 'Resources to be reserved';
        $strings['Accessories'] = 'Ekipmanlar';
        $strings['InvitationList'] = 'Davetliler';
        $strings['AccessoryName'] = 'Ekipman ismi';
        $strings['QuantityAvailable'] = 'Uygun miktar';
        $strings['Resources'] = 'Kaynak';
        $strings['Participants'] = 'Katılımcılar';
        $strings['User'] = 'Kullanıcı';
        $strings['Resource'] = 'Kaynak';
        $strings['Status'] = 'Durum';
        $strings['Approve'] = 'Onayla';
        $strings['Page'] = 'Sayfa';
        $strings['Rows'] = 'Satır';
        $strings['Unlimited'] = 'Limitsiz';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email Adresi';
        $strings['Phone'] = 'Telefon';
        $strings['Organization'] = 'Şirket';
        $strings['Position'] = 'Pozisyon';
        $strings['Language'] = 'Dil';
        $strings['Permissions'] = 'İzinler';
        $strings['Reset'] = 'Sıfırla';
        $strings['FindGroup'] = 'Grup Bul';
        $strings['Manage'] = 'Yönet';
        $strings['None'] = 'Boş';
        $strings['AddToOutlook'] = 'Takvime Ekle';
        $strings['Done'] = 'Tamam';
        $strings['RememberMe'] = 'Beni Hatırla';
        $strings['FirstTimeUser?'] = 'Yeni üye misin?';
        $strings['CreateAnAccount'] = 'Hesap Oluştur';
        $strings['ViewSchedule'] = 'Takvimi Gör';
        $strings['ForgotMyPassword'] = 'Şifremi Unuttum';
        $strings['YouWillBeEmailedANewPassword'] = 'Yeni şifreniz e-posta adresinize gönderilecektir.';
        $strings['Close'] = 'Kapat';
        $strings['ExportToCSV'] = 'CSV aktar';
        $strings['OK'] = 'Tamam';
        $strings['Working'] = 'Lütfen bekleyin...';
        $strings['Login'] = 'Giriş';
        $strings['AdditionalInformation'] = 'Ek Bilgiler';
        $strings['AllFieldsAreRequired'] = 'zorunlu alan';
        $strings['Optional'] = 'optional';
        $strings['YourProfileWasUpdated'] = 'Profiliniz güncellendi';
        $strings['YourSettingsWereUpdated'] = 'Ayarlarınız güncellendi';
        $strings['Register'] = 'Kayıt Ol';
        $strings['SecurityCode'] = 'Güvenlik Kodu';
        $strings['ReservationCreatedPreference'] = 'When I create a reservation or a reservation is created on my behalf';
        $strings['ReservationUpdatedPreference'] = 'When I update a reservation or a reservation is updated on my behalf';
        $strings['ReservationDeletedPreference'] = 'When I delete a reservation or a reservation is deleted on my behalf';
        $strings['ReservationApprovalPreference'] = 'When my pending reservation is approved';
        $strings['PreferenceSendEmail'] = 'Email Gönder';
        $strings['PreferenceNoEmail'] = 'Bildirim Gönderme';
        $strings['ReservationCreated'] = 'Rezervasyon başarıyla oluşturuldu!';
        $strings['ReservationUpdated'] = 'Rezervasyon başarıyla güncellendi!';
        $strings['ReservationRemoved'] = 'Rezervasyon kaldırıldı';
        $strings['ReservationRequiresApproval'] = 'One or more of the resources reserved require approval before usage.  This reservation will be pending until it is approved.';
        $strings['YourReferenceNumber'] = 'Rezervasyon numaranız: %s';
        $strings['ChangeUser'] = 'Kullanıcı Değiştir';
        $strings['MoreResources'] = 'More Resources';
        $strings['ReservationLength'] = 'Süre';
        $strings['ParticipantList'] = 'Katılımcılar';
        $strings['AddParticipants'] = 'Katılımcı Ekle';
        $strings['InviteOthers'] = 'Davet Et';
        $strings['AddResources'] = 'Kaynak Ekle';
        $strings['AddAccessories'] = 'Ekipman Ekle';
        $strings['Accessory'] = 'Ekipman';
        $strings['QuantityRequested'] = 'Quantity Requested';
        $strings['CreatingReservation'] = 'Rezervasyon oluşturuluyor';
        $strings['UpdatingReservation'] = 'Reservation güncelleniyor';
        $strings['DeleteWarning'] = 'This action is permanent and irrecoverable!';
        $strings['DeleteAccessoryWarning'] = 'Deleting this accessory will remove it from all reservations.';
        $strings['AddAccessory'] = 'Ekipman Ekle';
        $strings['AddBlackout'] = 'Add Blackout';
        $strings['AllResourcesOn'] = 'All Resources On';
        $strings['Reason'] = 'Sebep';
        $strings['BlackoutShowMe'] = 'Çakışan rezervasyonları görüntüle';
        $strings['BlackoutDeleteConflicts'] = 'Çakışan rezervasyonları iptal et';
        $strings['Filter'] = 'Filtrele';
        $strings['Between'] = 'arası';
        $strings['CreatedBy'] = 'Created By';
        $strings['BlackoutCreated'] = 'Blackout Created';
        $strings['BlackoutNotCreated'] = 'Blackout could not be created';
        $strings['BlackoutUpdated'] = 'Blackout Updated';
        $strings['BlackoutNotUpdated'] = 'Blackout could not be updated';
        $strings['BlackoutConflicts'] = 'There are conflicting blackout times';
        $strings['ReservationConflicts'] = 'There are conflicting reservations times';
        $strings['UsersInGroup'] = 'Users in this group';
        $strings['Browse'] = 'Göster';
        $strings['DeleteGroupWarning'] = 'Deleting this group will remove all associated resource permissions.  Users in this group may lose access to resources.';
        $strings['WhatRolesApplyToThisGroup'] = 'Which roles apply to this group?';
        $strings['WhoCanManageThisGroup'] = 'Who can manage this group?';
        $strings['WhoCanManageThisSchedule'] = 'Who can manage this schedule?';
        $strings['AllQuotas'] = 'All Quotas';
        $strings['QuotaReminder'] = 'Remember: Quotas are enforced based on the schedule\'s timezone.';
        $strings['AllReservations'] = 'Bütün Rezervasyonlar';
        $strings['PendingReservations'] = 'Pending Reservations';
        $strings['Approving'] = 'Approving';
        $strings['MoveToSchedule'] = 'Move to schedule';
        $strings['DeleteResourceWarning'] = 'Deleting this resource will delete all associated data, including';
        $strings['DeleteResourceWarningReservations'] = 'all past, current and future reservations associated with it';
        $strings['DeleteResourceWarningPermissions'] = 'all permission assignments';
        $strings['DeleteResourceWarningReassign'] = 'Please reassign anything that you do not want to be deleted before proceeding';
        $strings['ScheduleLayout'] = 'Layout (all times %s)';
        $strings['ReservableTimeSlots'] = 'Rezerve Edilebilecek Zaman Aralıkları';
        $strings['BlockedTimeSlots'] = 'Kapalı Zaman Aralıkları';
        $strings['ThisIsTheDefaultSchedule'] = 'Ana Takvim';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Ana Takvim Silinemez';
        $strings['MakeDefault'] = 'Make Default';
        $strings['BringDown'] = 'Bring Down';
        $strings['ChangeLayout'] = 'Change Layout';
        $strings['AddSchedule'] = 'Add Schedule';
        $strings['StartsOn'] = 'Starts On';
        $strings['NumberOfDaysVisible'] = 'Number of Days Visible';
        $strings['UseSameLayoutAs'] = 'Use Same Layout As';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Optional Label';
        $strings['LayoutInstructions'] = 'Enter one slot per line.  Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['AddUser'] = 'Add User';
        $strings['UserPermissionInfo'] = 'Actual access to resource may be different depending on user role, group permissions, or external permission settings';
        $strings['DeleteUserWarning'] = 'Deleting this user will remove all of their current, future, and historical reservations.';
        $strings['AddAnnouncement'] = 'Bildirim Ekle';
        $strings['Announcement'] = 'Bildirimler';

        $strings['Priority'] = 'Öncelikli';
        $strings['Reservable'] = 'Açık';
        $strings['Unreservable'] = 'Bloklu';
        $strings['Reserved'] = 'Rezerve';
        $strings['MyReservation'] = 'Bana Ait';
        $strings['Pending'] = 'Beklemede';
        $strings['Past'] = 'Geçmiş';
        $strings['Restricted'] = 'Kapalı';
        $strings['ViewAll'] = 'Hepsini Göster';
        $strings['MoveResourcesAndReservations'] = 'Move resources and reservations to';
        $strings['TurnOffSubscription'] = 'Turn Off Calendar Subscriptions';
        $strings['TurnOnSubscription'] = 'Allow Subscriptions to this Calendar';
        $strings['SubscribeToCalendar'] = 'Subscribe to this Calendar';
        $strings['SubscriptionsAreDisabled'] = 'The administrator has disabled calendar subscriptions';
        $strings['NoResourceAdministratorLabel'] = '(No Resource Administrator)';
        $strings['WhoCanManageThisResource'] = 'Who Can Manage This Resource?';
        $strings['ResourceAdministrator'] = 'Resource Administrator';
        $strings['Private'] = 'Özel';
        $strings['Accept'] = 'Kabul';
        $strings['Decline'] = 'Red';
        $strings['ShowFullWeek'] = 'Show Full Week';
        $strings['CustomAttributes'] = 'Özel Alanlar';
        $strings['AddAttribute'] = 'Yeni alan ekle';
        $strings['EditAttribute'] = 'Alan güncelle';
        $strings['DisplayLabel'] = 'Etiket göster';
        $strings['Type'] = 'Tip';
        $strings['Required'] = 'Zorunlu';
        $strings['ValidationExpression'] = 'Validasyon Açıklaması';
        $strings['PossibleValues'] = 'Olabilecek değerler';
        $strings['SingleLineTextbox'] = 'Single Line Textbox';
        $strings['MultiLineTextbox'] = 'Multiple Line Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Select List';
        $strings['CommaSeparated'] = 'comma separated';
        $strings['Category'] = 'Kategori';
        $strings['CategoryReservation'] = 'Rezervasyon';
        $strings['CategoryGroup'] = 'Grup';
        $strings['SortOrder'] = 'Sort Order';
        $strings['Title'] = 'Başlık';
        $strings['AdditionalAttributes'] = 'Diğer alanlar';
        $strings['True'] = 'Doğru';
        $strings['False'] = 'Yanlış';
        $strings['ForgotPasswordEmailSent'] = 'An email has been sent to the address provided with instructions for resetting your password';
        $strings['ActivationEmailSent'] = 'You will receive an activation email soon.';
        $strings['AccountActivationError'] = 'Sorry, we could not activate your account.';
        $strings['Attachments'] = 'Attachments';
        $strings['AttachFile'] = 'Attach File';
        $strings['Maximum'] = 'max';
        $strings['NoScheduleAdministratorLabel'] = 'No Schedule Administrator';
        $strings['ScheduleAdministrator'] = 'Schedule Administrator';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Quantity Reserved';
        $strings['AllAccessories'] = 'All Accessories';
        $strings['GetReport'] = 'Get Report';
        $strings['NoResultsFound'] = 'No matching results found';
        $strings['SaveThisReport'] = 'Save This Report';
        $strings['ReportSaved'] = 'Report Saved!';
        $strings['EmailReport'] = 'Email Report';
        $strings['ReportSent'] = 'Report Sent!';
        $strings['RunReport'] = 'Run Report';
        $strings['NoSavedReports'] = 'You have no saved reports.';
        $strings['CurrentWeek'] = 'Current Week';
        $strings['CurrentMonth'] = 'Current Month';
        $strings['AllTime'] = 'All Time';
        $strings['FilterBy'] = 'Filter By';
        $strings['Select'] = 'Select';
        $strings['List'] = 'List';
        $strings['TotalTime'] = 'Total Time';
        $strings['Count'] = 'Count';
        $strings['Usage'] = 'Usage';
        $strings['AggregateBy'] = 'Aggregate By';
        $strings['Range'] = 'Range';
        $strings['Choose'] = 'Choose';
        $strings['All'] = 'All';
        $strings['ViewAsChart'] = 'View As Chart';
        $strings['ReservedResources'] = 'Reserved Resources';
        $strings['ReservedAccessories'] = 'Reserved Accessories';
        $strings['ResourceUsageTimeBooked'] = 'Resource Usage - Time Booked';
        $strings['ResourceUsageReservationCount'] = 'Resource Usage - Reservation Count';
        $strings['Top20UsersTimeBooked'] = 'Top 20 Users - Time Booked';
        $strings['Top20UsersReservationCount'] = 'Top 20 Users - Reservation Count';
        $strings['ConfigurationUpdated'] = 'The configuration file was updated';
        $strings['ConfigurationUiNotEnabled'] = 'This page cannot be accessed because $conf[\'settings\'][\'pages\'][\'enable.configuration\'] is set to false or missing.';
        $strings['ConfigurationFileNotWritable'] = 'The config file is not writable. Please check the permissions of this file and try again.';
        $strings['ConfigurationUpdateHelp'] = 'Refer to the Configuration section of the <a target=_blank href=%s>Help File</a> for documentation on these settings.';
        $strings['GeneralConfigSettings'] = 'settings';
        $strings['UseSameLayoutForAllDays'] = 'Use the same layout for all days';
        $strings['LayoutVariesByDay'] = 'Layout varies by day';
        $strings['ManageReminders'] = 'Reminders';
        $strings['ReminderUser'] = 'User ID';
        $strings['ReminderMessage'] = 'Message';
        $strings['ReminderAddress'] = 'Addresses';
        $strings['ReminderSendtime'] = 'Time To Send';
        $strings['ReminderRefNumber'] = 'Reservation Reference Number';
        $strings['ReminderSendtimeDate'] = 'Date of Reminder';
        $strings['ReminderSendtimeTime'] = 'Time of Reminder (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Add Reminder';
        $strings['DeleteReminderWarning'] = 'Are you sure you want to delete this?';
        $strings['NoReminders'] = 'You have no upcoming reminders.';
        $strings['Reminders'] = 'Hatırlatmalar';
        $strings['SendReminder'] = 'Hatırlatma Gönder';
        $strings['minutes'] = 'dakika';
        $strings['hours'] = 'saat';
        $strings['days'] = 'gün';
        $strings['ReminderBeforeStart'] = 'before the start time';
        $strings['ReminderBeforeEnd'] = 'before the end time';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Your changes have been saved. Refresh the page for changes to take effect.';
        $strings['MakeDefaultSchedule'] = 'Make this my default schedule';
        $strings['DefaultScheduleSet'] = 'This is now your default schedule';
        $strings['FlipSchedule'] = 'Flip the schedule layout';
        $strings['Next'] = 'Next';
        $strings['Success'] = 'Success';
        $strings['Participant'] = 'Katılımlı';
        $strings['ResourceFilter'] = 'Resource Filter';
        $strings['ResourceGroups'] = 'Resource Groups';
        $strings['AddNewGroup'] = 'Add a new group';
        $strings['Quit'] = 'Çık';
        $strings['AddGroup'] = 'Add Group';
        $strings['StandardScheduleDisplay'] = 'Use the standard schedule display';
        $strings['TallScheduleDisplay'] = 'Use the tall schedule display';
        $strings['WideScheduleDisplay'] = 'Use the wide schedule display';
        $strings['CondensedWeekScheduleDisplay'] = 'Use condensed week schedule display';
        $strings['ResourceGroupHelp1'] = 'Drag and drop resource groups to reorganize.';
        $strings['ResourceGroupHelp2'] = 'Right click a resource group name for additional actions.';
        $strings['ResourceGroupHelp3'] = 'Drag and drop resources to add them to groups.';
        $strings['ResourceGroupWarning'] = 'If using resource groups, each resource must be assigned to at least one group. Unassigned resources will not be able to be reserved.';
        $strings['ResourceType'] = 'Tip';
        $strings['AppliesTo'] = 'Applies To';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Tip Ekle';
        $strings['NoResourceTypeLabel'] = '(tip belirtilmedi)';
        $strings['ClearFilter'] = 'Temizle';
        $strings['MinimumCapacity'] = 'Minimum Kapasite';
        $strings['Color'] = 'Renk';
        $strings['Available'] = 'Uygun';
        $strings['Unavailable'] = 'Uygun Değil';
        $strings['Hidden'] = 'Gizli';
        $strings['ResourceStatus'] = 'Resource Status';
        $strings['CurrentStatus'] = 'Current Status';
        $strings['AllReservationResources'] = 'All Reservation Resources';
        $strings['File'] = 'File';
        $strings['BulkResourceUpdate'] = 'Bulk Resource Update';
        $strings['Unchanged'] = 'Unchanged';
        $strings['Common'] = 'Common';
        $strings['AdminOnly'] = 'Is Admin Only';
        $strings['AdvancedFilter'] = 'Advanced Filter';
        $strings['MinimumQuantity'] = 'Minimum Quantity';
        $strings['MaximumQuantity'] = 'Maximum Quantity';
        $strings['ChangeLanguage'] = 'Dil Seçenekleri';
        $strings['AddRule'] = 'Kural Ekle';
        $strings['Attribute'] = 'Attribute';
        $strings['RequiredValue'] = 'Required Value';
        $strings['ReservationCustomRuleAdd'] = 'If %s then the reservation color will be';
        $strings['AddReservationColorRule'] = 'Add Reservation Color Rule';
        $strings['LimitAttributeScope'] = 'Collect In Specific Cases';
        $strings['CollectFor'] = 'Collect For';
        $strings['SignIn'] = 'Giriş Yap';
        $strings['AllParticipants'] = 'Tüm Katılımcılar';
        $strings['RegisterANewAccount'] = 'Hesap Oluştur';
        $strings['Dates'] = 'Gün';
        $strings['More'] = 'Daha fazla';
        $strings['ResourceAvailability'] = 'Uygunluk Durumu';
        $strings['UnavailableAllDay'] = 'Bütün Gün Uygun Olmayan';
        $strings['AvailableUntil'] = 'Şu zamana kadar uygun';
        $strings['AvailableBeginningAt'] = 'Uygunluk Başlama Tarihi:';
        $strings['AllResourceTypes'] = 'Bütün Kaynak Tipleri';
        $strings['AllResourceStatuses'] = 'Bütün Kaynak Durumları';
        $strings['AllowParticipantsToJoin'] = 'Sonradan katılımcı gelebilir';
        $strings['Join'] = 'Katıl';
        $strings['YouAreAParticipant'] = 'Bu rezervasyonun katılımcısı görünüyorsunuz';
        $strings['YouAreInvited'] = 'Bu rezervasyona davet edildiniz.';
        $strings['YouCanJoinThisReservation'] = 'Bu rezervasyona katılabilirsiniz.';
        $strings['Import'] = 'İçeri Aktar';
        $strings['GetTemplate'] = 'Temlate Al';
        $strings['UserImportInstructions'] = '<ul><li>Dosya CSV formatında olmalıdır.</li><li>Username and email are required fields.</li><li>Attribute validity will not be enforced.</li><li>Leaving other fields blank will set default values and \'password\' as the user\'s password.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['RowsImported'] = 'İçeri Aktarılan Satır';
        $strings['RowsSkipped'] = 'Geçilen Satır';
        $strings['Columns'] = 'Kolonlar';
        $strings['Reserve'] = 'Rezerve Et';
        $strings['AllDay'] = 'All Day';
        $strings['Everyday'] = 'Everyday';
        $strings['IncludingCompletedReservations'] = 'Including Completed Reservations';
        $strings['NotCountingCompletedReservations'] = 'Not Counting Completed Reservations';
        $strings['RetrySkipConflicts'] = 'Skip conflicting reservations';
        $strings['Retry'] = 'Retry';
        $strings['RemoveExistingPermissions'] = 'Remove existing permissions?';
        $strings['Continue'] = 'Continue';
        $strings['WeNeedYourEmailAddress'] = 'We need your email address to reserve';
        $strings['ResourceColor'] = 'Resource Color';
        $strings['DateTime'] = 'Date Time';
        $strings['AutoReleaseNotification'] = 'Automatically released if not checked in within %s minutes';
        $strings['RequiresCheckInNotification'] = 'Requires check in/out';
        $strings['NoCheckInRequiredNotification'] = 'Does not require check in/out';
        $strings['RequiresApproval'] = 'Requires Approval';
        $strings['CheckingIn'] = 'Checking In';
        $strings['CheckingOut'] = 'Checking Out';
        $strings['CheckIn'] = 'Check In';
        $strings['CheckOut'] = 'Check Out';
        $strings['ReleasedIn'] = 'Released in';
        $strings['CheckedInSuccess'] = 'You are checked in';
        $strings['CheckedOutSuccess'] = 'You are checked out';
        $strings['CheckInFailed'] = 'You could not be checked in';
        $strings['CheckOutFailed'] = 'You could not be checked out';
        $strings['CheckInTime'] = 'Check In Time';
        $strings['CheckOutTime'] = 'Check Out Time';
        $strings['OriginalEndDate'] = 'Original End';
        $strings['SpecificDates'] = 'Show Specific Dates';
        $strings['Users'] = 'Users';
        $strings['Guest'] = 'Guest';
        $strings['ResourceDisplayPrompt'] = 'Resource to Display';
        $strings['Credits'] = 'Credits';
        $strings['AvailableCredits'] = 'Available Credits';
        $strings['CreditUsagePerSlot'] = 'Requires %s credits per slot (off peak)';
        $strings['PeakCreditUsagePerSlot'] = 'Requires %s credits per slot (peak)';
        $strings['CreditsRule'] = 'You do not have enough credits. Credits required: %s. Credits in account: %s';
        $strings['PeakTimes'] = 'Peak Times';
        $strings['AllYear'] = 'All Year';
        $strings['MoreOptions'] = 'More Options';
        $strings['SendAsEmail'] = 'Send As Email';
        $strings['UsersInGroups'] = 'Users In Groups';
        $strings['UsersWithAccessToResources'] = 'Users With Access To Resources';
        $strings['AnnouncementSubject'] = 'A new announcement was posted by %s';
        $strings['AnnouncementEmailNotice'] = 'users will be sent this announcement as an email';
        $strings['Day'] = 'Gün';
        $strings['NotifyWhenAvailable'] = 'Notify Me When Available';
        $strings['AddingToWaitlist'] = 'Adding you to the wait list';
        $strings['WaitlistRequestAdded'] = 'You will be notified if this time becomes available';
        $strings['PrintQRCode'] = 'Print QR Code';
        $strings['FindATime'] = 'Zaman Bul';
        $strings['AnyResource'] = 'Any Resource';
        $strings['ThisWeek'] = 'This Week';
        $strings['Hours'] = 'Hours';
        $strings['Minutes'] = 'Minutes';
        $strings['ImportICS'] = 'Import From ICS';
        $strings['ImportQuartzy'] = 'Import From Quartzy';
        $strings['OnlyIcs'] = 'Only *.ics files can be uploaded.';
        $strings['IcsLocationsAsResources'] = 'Locations will be imported as resources.';
        $strings['IcsMissingOrganizer'] = 'Any event missing an organizer will have the owner set to the current user.';
        $strings['IcsWarning'] = 'Reservation rules will not be enforced - conflicts, duplicates, etc are possible.';
        $strings['BlackoutAroundConflicts'] = 'Blackout around conflicting reservations';
        $strings['DuplicateReservation'] = 'Duplicate';
        $strings['DuplicateReservation'] = 'Duplicate';
        $strings['UnavailableNow'] = 'Unavailable Now';
        $strings['ReserveLater'] = 'Reserve Later';
        $strings['CollectedFor'] = 'Collected For';
        $strings['IncludeDeleted'] = 'Include Deleted Reservations';
        $strings['Deleted'] = 'Deleted';
        $strings['Back'] = 'Back';
        $strings['Forward'] = 'Forward';
        $strings['DateRange'] = 'Date Range';
        $strings['Copy'] = 'Copy';
        $strings['Detect'] = 'Detect';
        $strings['Autofill'] = 'Autofill';
        $strings['NameOrEmail'] = 'name or email';
        $strings['ImportResources'] = 'Import Resources';
        $strings['ExportResources'] = 'Export Resources';
        $strings['ResourceImportInstructions'] = '<ul><li>File must be in CSV format.</li><li>Name is required field. Leaving other fields blank will set default values.</li><li>Status options are \'Available\', \'Unavailable\' and \'Hidden\'.</li><li>Color should be the hex value. ex) #ffffff.</li><li>Auto assign and approval columns can be true or false.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource groups.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>File must be in CSV format.</li><li>Email, resource names, begin, and end are required fields.</li><li>Begin and end require full date time. Recommended format is YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Rules, conflicts, and valid time slots will not be checked.</li><li>Notifications will not be sent.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource names.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Autorelease Minutes';
        $strings['CreditsPeak'] = 'Credits (peak)';
        $strings['CreditsOffPeak'] = 'Credits (off peak)';
        $strings['ResourceMinLengthCsv'] = 'Reservation Minimum Length';
        $strings['ResourceMaxLengthCsv'] = 'Reservation Maximum Length';
        $strings['ResourceBufferTimeCsv'] = 'Buffer Time';
        $strings['ResourceMinNoticeCsv'] = 'Reservation Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';
        $strings['Export'] = 'Export';
        $strings['DeleteMultipleUserWarning'] = 'Deleting these users will remove all of their current, future, and historical reservations. No emails will be sent.';
        $strings['DeleteMultipleReservationsWarning'] = 'No emails will be sent.';
        $strings['ErrorMovingReservation'] = 'Error Moving Reservation';
        $strings['SelectUser'] = 'Select User';
        $strings['InviteUsers'] = 'Invite Users';
        $strings['InviteUsersLabel'] = 'Enter the email addresses of the people to invite';
        $strings['ApplyToCurrentUsers'] = 'Apply to current users';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Install LibreBooking (MySQL only)';
        $strings['IncorrectInstallPassword'] = 'Sorry, that password was incorrect.';
        $strings['SetInstallPassword'] = 'You must set an install password before the installation can be run.';
        $strings['InstallPasswordInstructions'] = 'In %s please set %s to a password which is random and difficult to guess, then return to this page.<br/>You can use %s';
        $strings['NoUpgradeNeeded'] = 'LibreBooking is up to date. There is no upgrade needed.';
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
        $strings['InstalledVersion'] = 'You are now running version %s of LibreBooking';
        $strings['InstallUpgradeConfig'] = 'It is recommended to upgrade your config file';
        $strings['InstallationFailure'] = 'There were problems with the installation.  Please correct them and retry the installation.';
        $strings['ConfigureApplication'] = 'Configure LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Your config file is now up to date!';
        $strings['ConfigUpdateFailure'] = 'We could not automatically update your config file. Please overwrite the contents of config.php with the following:';
        $strings['ScriptUrlWarning'] = 'Your <em>script.url</em> setting may not be correct. It is currently <strong>%s</strong>, we think it should be <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'We could not match your username or password';
        $strings['ReservationFailed'] = 'Rezervasyon Yapılamadı!';
        $strings['MinNoticeError'] = 'This reservation requires advance notice.  The earliest date and time that can be reserved is %s.';
        $strings['MaxNoticeError'] = 'This reservation cannot be made this far in the future.  The latest date and time that can be reserved is %s.';
        $strings['MinDurationError'] = 'This reservation must last at least %s.';
        $strings['MaxDurationError'] = 'This reservation cannot last longer than %s.';
        $strings['ConflictingAccessoryDates'] = 'There are not enough of the following accessories:';
        $strings['NoResourcePermission'] = 'You do not have permission to access one or more of the requested resources.';
        $strings['ConflictingReservationDates'] = 'Şu zaman için çakışma mevcut:';
        $strings['StartDateBeforeEndDateRule'] = 'The start date and time must be before the end date and time.';
        $strings['StartIsInPast'] = 'The start date and time cannot be in the past.';
        $strings['EmailDisabled'] = 'The administrator has disabled email notifications.';
        $strings['ValidLayoutRequired'] = 'Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'There are problems with the additional attributes you provided:';
        $strings['CustomAttributeRequired'] = '%s is a required field.';
        $strings['CustomAttributeInvalid'] = 'The value provided for %s is invalid.';
        $strings['AttachmentLoadingError'] = 'Sorry, there was a problem loading the requested file.';
        $strings['InvalidAttachmentExtension'] = 'You can only upload files of type: %s';
        $strings['InvalidStartSlot'] = 'The start date and time requested is not valid.';
        $strings['InvalidEndSlot'] = 'The end date and time requested is not valid.';
        $strings['MaxParticipantsError'] = '%s can only support %s participants.';
        $strings['ReservationCriticalError'] = 'There was a critical error saving your reservation. If this continues, contact your system administrator.';
        $strings['InvalidStartReminderTime'] = 'The start reminder time is not valid.';
        $strings['InvalidEndReminderTime'] = 'The end reminder time is not valid.';
        $strings['QuotaExceeded'] = 'Quota limit exceeded.';
        $strings['MultiDayRule'] = '%s does not allow reservations across days.';
        $strings['InvalidReservationData'] = 'Rezervasyon talebi sırasında hata oluştu.';
        $strings['PasswordError'] = 'Şifreniz en az %s karakter ve en az %s adet rakam içermelidir.';
        $strings['PasswordErrorRequirements'] = 'Şifreniz en az %s adet büyük küçük harf ve %s adet rakam içermelidir.';
        $strings['NoReservationAccess'] = 'You are not allowed to change this reservation.';
        $strings['PasswordControlledExternallyError'] = 'Your password is controlled by an external system and cannot be updated here.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Accessory %s can only be booked with resources %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'You must book at least %s of accessory %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'You cannot book more than %s of accessory %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Accessory \'%s\' cannot be booked with the requested resources';
        $strings['NoResources'] = 'You have not added any resources.';
        $strings['ParticipationNotAllowed'] = 'You are not allowed to join this reservation.';
        $strings['ReservationCannotBeCheckedInTo'] = 'This reservation cannot be checked in to.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'This reservation cannot be checked out from.';
        $strings['InvalidEmailDomain'] = 'That email address is not from an allowed domain';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Rezervasyon yap';
        $strings['EditReservation'] = 'Rezervasyon güncelle';
        $strings['LogIn'] = 'Giriş';
        $strings['ManageReservations'] = 'Rezervasyonlar';
        $strings['AwaitingActivation'] = 'Aksivasyon Bekleniyor';
        $strings['PendingApproval'] = 'Onay Bekliyor';
        $strings['ManageSchedules'] = 'Takvimler';
        $strings['ManageResources'] = 'Kaynaklar';
        $strings['ManageAccessories'] = 'Ekipmanlar';
        $strings['ManageUsers'] = 'Kullanıcılar';
        $strings['ManageGroups'] = 'Gruplar';
        $strings['ManageQuotas'] = 'Kota';
        $strings['ManageBlackouts'] = 'Mazeret Bilgileri';
        $strings['MyDashboard'] = 'Panelim';
        $strings['ServerSettings'] = 'Sunucu Ayarları';
        $strings['Dashboard'] = 'Panel';
        $strings['Help'] = 'Yardım';
        $strings['Administration'] = 'Administration';
        $strings['About'] = 'Hakkında';
        $strings['Bookings'] = 'Rezervasyonlar';
        $strings['Schedule'] = 'Takvim';
        $strings['Account'] = 'Hesap';
        $strings['EditProfile'] = 'Bilgilerimi Güncelle';
        $strings['FindAnOpening'] = 'Find An Opening';
        $strings['OpenInvitations'] = 'Açık Davetler';
        $strings['ResourceCalendar'] = 'Bütün Takvimler';
        $strings['Reservation'] = 'Yeni Rezervasyon';
        $strings['Install'] = 'Yükleme';
        $strings['ChangePassword'] = 'Şifre Güncelle';
        $strings['MyAccount'] = 'Hesabım';
        $strings['Profile'] = 'Bilgilerim';
        $strings['ApplicationManagement'] = 'Uygulama Yönetimi';
        $strings['ForgotPassword'] = 'Şifremi Unuttum';
        $strings['NotificationPreferences'] = 'Bildirim Seçenekleri';
        $strings['ManageAnnouncements'] = 'Bildirimler';
        $strings['Responsibilities'] = 'Görevler';
        $strings['GroupReservations'] = 'Grup Rezervasyonu';
        $strings['ResourceReservations'] = 'Resource Reservations';
        $strings['Customization'] = 'Özelleştir';
        $strings['Attributes'] = 'Parametreler';
        $strings['AccountActivation'] = 'Hesap Aktivasyonu';
        $strings['ScheduleReservations'] = 'Schedule Reservations';
        $strings['Reports'] = 'Raporlar';
        $strings['GenerateReport'] = 'Yeni Rapor';
        $strings['MySavedReports'] = 'Kayıtlı Raporlarım';
        $strings['CommonReports'] = 'Genel Raporlar';
        $strings['ViewDay'] = 'Günü Gör';
        $strings['Group'] = 'Grup';
        $strings['ManageConfiguration'] = 'Uygulama Ayarları';
        $strings['LookAndFeel'] = 'Görünüm';
        $strings['ManageResourceGroups'] = 'Kaynak Grupları';
        $strings['ManageResourceTypes'] = 'Kaynak Tipleri';
        $strings['ManageResourceStatus'] = 'Kaynak Durumları';
        $strings['ReservationColors'] = 'Reservasyon Renkleri';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Paz';
        $strings['DayMondayAbbr'] = 'Pts';
        $strings['DayTuesdayAbbr'] = 'Sal';
        $strings['DayWednesdayAbbr'] = 'Çar';
        $strings['DayThursdayAbbr'] = 'Per';
        $strings['DayFridayAbbr'] = 'Cum';
        $strings['DaySaturdayAbbr'] = 'Cts';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Rezervasyon Onaylandı';
        $strings['ReservationCreatedSubject'] = 'Rezervasyonunuz Oluşturuldu';
        $strings['ReservationUpdatedSubject'] = 'Rezervasyonunuz Güncellendi';
        $strings['ReservationDeletedSubject'] = 'Rezervasyonunuz İptal Edildi';
        $strings['ReservationCreatedAdminSubject'] = 'Bildirim: Rezervasyon Oluşturuldu';
        $strings['ReservationUpdatedAdminSubject'] = 'Bildirim: Rezervasyon Güncellendi';
        $strings['ReservationDeleteAdminSubject'] = 'Bildirim: Rezervasyon Kaldırıldı';
        $strings['ReservationApprovalAdminSubject'] = 'Bildirim: Reservation Requires Your Approval';
        $strings['ParticipantAddedSubject'] = 'Rezervasyon Katılımcı Bildirimi';
        $strings['ParticipantDeletedSubject'] = 'Reservasyon İptal Edildi';
        $strings['InviteeAddedSubject'] = 'Reservation Invitation';
        $strings['ResetPasswordRequest'] = 'Şifre unuttum';
        $strings['ActivateYourAccount'] = 'Lütfen Hesabınızı Onaylayınız';
        $strings['ReportSubject'] = 'Rapor Talebiniz (%s)';
        $strings['ReservationStartingSoonSubject'] = '%s için rezervasyon yaklaşıyor';
        $strings['ReservationEndingSoonSubject'] = '%s rezervasyon bitmek üzere';
        $strings['UserAdded'] = 'Yeni kullanıcı yaratıldı';
        $strings['UserDeleted'] = '%s kullanıcısı %s tarafından kaldırıldı';
        $strings['GuestAccountCreatedSubject'] = 'Hesap Bilgileriniz';
        $strings['InviteUserSubject'] = '%s sizi  %s için katılmaya davet etti';

        $strings['ReservationApprovedSubjectWithResource'] = 'Rezervasyon Onaylandı %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Reservasyon Oluşturuldu %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Reservasyon Güncellendi %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Reservasyon İptal Edildi %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Bildirim: Rezervasyon Onaylandı %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Bildirim: Reservasyon Oluşturuldu %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Bildirim: Reservasyon Güncellendi %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Bildirim: %s Rezervasyonu Onayınızı Bekliyor';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Sizi bir rezervasyona ekledi %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s sizi rezervasyondan kaldırdı %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s Sizi bir rezervasyona davet etti %s';
        $strings['MissedCheckinEmailSubject'] = 'Missed checkin for %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Daha önce yapılmış rezervasyonunuz yok';
        $strings['PastReservations'] = 'Önceki Rezervasyonlar';
        $strings['AllNoPastReservations'] = 'Son %s günde önceki rezervasyon yok';
        $strings['AllPastReservations'] = 'Tüm Önceki Rezervasyonlar';
        $strings['Yesterday'] = 'Dün';
        $strings['EarlierThisWeek'] = 'Bu Haftanın Daha Önceki Günleri';
        $strings['PreviousWeek'] = 'Geçen Hafta';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Grubunuzun gelecek rezervasyonu yok';
        $strings['GroupUpcomingReservations'] = 'Grubumun Yaklaşan Rezervasyonları';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Facebook ile giriş yapılırken bir hata oluştu. Lütfen tekrar deneyin.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Onay bekleyen rezervasyonunuz bulunmamaktadır';
        $strings['PendingApprovalReservations'] = 'Onay bekleyen rezervasyonlar';
        $strings['LaterThisMonth'] = 'Bu ayın ilerisinde';
        $strings['LaterThisYear'] = 'Bu yılın ilerisinde';
        $strings['Remaining'] = 'Kalan';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Eksik check-out rezervasyonu yok';
        $strings['MissingCheckOutReservations'] = 'Eksik Check Out Rezervasyonları';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Bu rezervasyondaki hiçbir kaynağa izinleriniz olmadığından rezervasyon ayrıntılarını göremiyorsunuz';
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
        $days['full'] = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
        // The three letter abbreviation
        $days['abbr'] = ['Pzr', 'Pts', 'Sal', 'Çar', 'Per', 'Cum', 'Cts'];
        // The two letter abbreviation
        $days['two'] = ['Pz', 'Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct'];
        // The one letter abbreviation
        $days['letter'] = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

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
        $months['full'] = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

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
        return 'en';
    }
}
