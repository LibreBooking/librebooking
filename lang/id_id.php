<?php

require_once('Language.php');
require_once('en_gb.php');

class id_id extends en_gb
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Nama Awal';
        $strings['LastName'] = 'Nama Akhir';
        $strings['Timezone'] = 'Zona Waktu';
        $strings['Edit'] = 'Sunting';
        $strings['Change'] = 'Ubah';
        $strings['Rename'] = 'Ubah Nama';
        $strings['Remove'] = 'Hapus';
        $strings['Delete'] = 'Hapus';
        $strings['Update'] = 'Perbarui';
        $strings['Cancel'] = 'Batal';
        $strings['Add'] = 'Tambah';
        $strings['Name'] = 'Nama';
        $strings['Yes'] = 'Ya';
        $strings['No'] = 'Tidak';
        $strings['FirstNameRequired'] = 'Nama awal diperlukan.';
        $strings['LastNameRequired'] = 'Nama akhir diperlukan.';
        $strings['PwMustMatch'] = 'Konfirmasi kata sandi harus sama dengan kata sandi.';
        $strings['PwComplexity'] = 'Kata sandi harus sekurangnya 6 karakter dengan kombinasi dari huruf, nomor dan simbol.';
        $strings['ValidEmailRequired'] = 'Alamat email yang sah diperlukan.';
        $strings['UniqueEmailRequired'] = 'Alamat email tersebut telah terdaftar.';
        $strings['UniqueUsernameRequired'] = 'Nama pengguna tersebut telah terdaftar.';
        $strings['UserNameRequired'] = 'Nama pengguna diperlukan.';
        $strings['CaptchaMustMatch'] = 'Silahkan masukkan huruf dari gambar keamanan sama seperti yang ditampilkan.';
        $strings['Today'] = 'Hari ini';
        $strings['Week'] = 'Minggu';
        $strings['Month'] = 'Bulan';
        $strings['BackToCalendar'] = 'Kembali ke kalender';
        $strings['BeginDate'] = 'Mulai';
        $strings['EndDate'] = 'Akhir';
        $strings['Username'] = 'Nama Pengguna';
        $strings['Password'] = 'Kata Sandi';
        $strings['PasswordConfirmation'] = 'Konfirmasi Kata Sandi';
        $strings['DefaultPage'] = 'Beranda Standar';
        $strings['MyCalendar'] = 'Kalender Saya';
        $strings['ScheduleCalendar'] = 'Kalender Jadwal';
        $strings['Registration'] = 'Pendaftaran';
        $strings['NoAnnouncements'] = 'Tidak ada pengumuman';
        $strings['Announcements'] = 'Pengumuman';
        $strings['NoUpcomingReservations'] = 'Anda tidak memiliki reservasi yang akan datang';
        $strings['UpcomingReservations'] = 'Reservasi yang akan datang';
        $strings['ShowHide'] = 'Tampil/Sembunyi';
        $strings['Error'] = 'Error';
        $strings['ReturnToPreviousPage'] = 'Kembali ke halaman terakhir Anda berada';
        $strings['UnknownError'] = 'Error tidak diketahui';
        $strings['InsufficientPermissionsError'] = 'Anda tidak memiliki akses ke resource ini';
        $strings['MissingReservationResourceError'] = 'Resource belum dipilih';
        $strings['MissingReservationScheduleError'] = 'Jadwal belum dipilih';
        $strings['DoesNotRepeat'] = 'Tidak Diulang';
        $strings['Daily'] = 'Harian';
        $strings['Weekly'] = 'Mingguan';
        $strings['Monthly'] = 'Bulanan';
        $strings['Yearly'] = 'Tahunan';
        $strings['RepeatPrompt'] = 'Ulangi';
        $strings['hours'] = 'jam';
        $strings['days'] = 'hari';
        $strings['weeks'] = 'minggu';
        $strings['months'] = 'bulan';
        $strings['years'] = 'tahun';
        $strings['day'] = 'hari';
        $strings['week'] = 'minggu';
        $strings['month'] = 'bulan';
        $strings['year'] = 'tahun';
        $strings['repeatDayOfMonth'] = 'hari dari bulan';
        $strings['repeatDayOfWeek'] = 'hari dari minggu';
        $strings['RepeatUntilPrompt'] = 'Sampai';
        $strings['RepeatEveryPrompt'] = 'Setiap';
        $strings['RepeatDaysPrompt'] = 'Hidup';
        $strings['CreateReservationHeading'] = 'Membuat reservasi baru';
        $strings['EditReservationHeading'] = 'Mennyunting reservasi %s';
        $strings['ViewReservationHeading'] = 'Melihat reservasi %s';
        $strings['ReservationErrors'] = 'Mengganti Reservasi';
        $strings['Create'] = 'Buat';
        $strings['ThisInstance'] = 'Hanya ini';
        $strings['AllInstances'] = 'Semua';
        $strings['FutureInstances'] = 'Masa yang Akan Datang';
        $strings['Print'] = 'Cetak';
        $strings['ShowHideNavigation'] = 'Tampilkan/Sembunyikan Navigasi';
        $strings['ReferenceNumber'] = 'Nomor Referensi';
        $strings['Tomorrow'] = 'Besok';
        $strings['LaterThisWeek'] = 'Setelah Minggu Ini';
        $strings['NextWeek'] = 'Minggu Depan';
        $strings['SignOut'] = 'Keluar';
        $strings['LayoutDescription'] = 'Mulai pada %s, ditampilkan %s hari pada satu waktu';
        $strings['AllResources'] = 'Semua Resource';
        $strings['TakeOffline'] = 'Jadikan Offline';
        $strings['BringOnline'] = 'Jadikan Online';
        $strings['AddImage'] = 'Tambah Gambar';
        $strings['NoImage'] = 'Tidak Ada Gambar Tersedia';
        $strings['Move'] = 'Pindah';
        $strings['AppearsOn'] = 'Muncul Pada %s';
        $strings['Location'] = 'Lokasi';
        $strings['NoLocationLabel'] = '(tidak ada lokasi yang diatur)';
        $strings['Contact'] = 'Kontak';
        $strings['NoContactLabel'] = '(tidak ada informasi kontak)';
        $strings['Description'] = 'Keterangan';
        $strings['NoDescriptionLabel'] = '(tidak ada keterangan)';
        $strings['Notes'] = 'Catatan';
        $strings['NoNotesLabel'] = '(tidak ada catatan)';
        $strings['NoTitleLabel'] = '(tidak ada judul)';
        $strings['UsageConfiguration'] = 'Konfigurasi Penggunaan';
        $strings['ChangeConfiguration'] = 'Ubah Konfigurasi';
        $strings['ResourceMinLength'] = 'Reservasi harus berakhir sekurangnya %s';
        $strings['ResourceMinLengthNone'] = 'Tidak ada minimal lama reservasi';
        $strings['ResourceMaxLength'] = 'Reservasi tidak bisa lebih dari  %s';
        $strings['ResourceMaxLengthNone'] = 'Tidak ada maksimal lama reservasi';
        $strings['ResourceRequiresApproval'] = 'Reservasi harus disetujui';
        $strings['ResourceRequiresApprovalNone'] = 'Reservasi tidak butuh persetujuan';
        $strings['ResourcePermissionAutoGranted'] = 'Izin otomatis diberikan';
        $strings['ResourcePermissionNotAutoGranted'] = 'Izin tidak otomatis diberikan';
        $strings['ResourceMinNotice'] = 'Reservasi harus dibuat sekurangnya %s bergantung waktu mulai';
        $strings['ResourceMinNoticeNone'] = 'Reservasi bisa dibuat sampai waktu saat ini';
        $strings['ResourceMaxNotice'] = 'Reservasi harus berakhir tidak boleh lebih dari %s dari waktu sekarang';
        $strings['ResourceMaxNoticeNone'] = 'Reservasi bisa berakhir kapan saja di masa depan';
        $strings['ResourceAllowMultiDay'] = 'Reservasi bisa dibuat sepanjang hari';
        $strings['ResourceNotAllowMultiDay'] = 'Reservasi tidak bisa dibuat sepanjang hari';
        $strings['ResourceCapacity'] = 'Resource ini memiliki kapasitas %s orang';
        $strings['ResourceCapacityNone'] = 'Resource memiliki kapasitas yang tak terbatas';
        $strings['AddNewResource'] = 'Tambah Resource Baru';
        $strings['AddNewUser'] = 'Tambah Pengguna Baru';
        $strings['AddUser'] = 'Tambah Pengguna';
        $strings['Schedule'] = 'Jadwal';
        $strings['AddResource'] = 'Tambah Resource';
        $strings['Capacity'] = 'Kapasitas';
        $strings['Access'] = 'Akses';
        $strings['Duration'] = 'Durasi';
        $strings['Active'] = 'Aktif';
        $strings['Inactive'] = 'Non-Aktif';
        $strings['ResetPassword'] = 'Atur Ulang Kata Sandi';
        $strings['LastLogin'] = 'Terakhir Masuk';
        $strings['Search'] = 'Cari';
        $strings['ResourcePermissions'] = 'Izin Resource';
        $strings['Reservations'] = 'Reservasi';
        $strings['Groups'] = 'Grup';
        $strings['ResetPassword'] = 'Atur Ulang Kata Sandi';
        $strings['AllUsers'] = 'Semua Pengguna';
        $strings['AllGroups'] = 'Semua Grup';
        $strings['AllSchedules'] = 'Semua Jadwal';
        $strings['UsernameOrEmail'] = 'Nama Pengguna atau Email';
        $strings['Members'] = 'Anggota';
        $strings['QuickSlotCreation'] = 'Buat slot setiap %s menit antara %s dan %s';
        $strings['ApplyUpdatesTo'] = 'Terapkan Pembaruan Untuk';
        $strings['CancelParticipation'] = 'Batalkan Partisipasi';
        $strings['Attending'] = 'Hadir';
        $strings['QuotaConfiguration'] = 'Pada %s untuk %s pengguna dalam %s terbatas untuk %s %s per %s';
        $strings['reservations'] = 'reservasi';
        $strings['ChangeCalendar'] = 'Ganti Kalender';
        $strings['AddQuota'] = 'Tambah Kuota';
        $strings['FindUser'] = 'Cari Pengguna';
        $strings['Created'] = 'Buat';
        $strings['LastModified'] = 'Terakhir dimodifikasi';
        $strings['GroupName'] = 'Nama Grup';
        $strings['GroupMembers'] = 'Anggota Grup';
        $strings['GroupRoles'] = 'Peran Grup';
        $strings['GroupAdmin'] = 'Administrator Grup';
        $strings['Actions'] = 'Aksi';
        $strings['CurrentPassword'] = 'Kata Sandi Saat Ini';
        $strings['NewPassword'] = 'Kata Sandi Baru';
        $strings['InvalidPassword'] = 'Kata sandi saat ini salah';
        $strings['PasswordChangedSuccessfully'] = 'Kata sandi berhasil diganti';
        $strings['SignedInAs'] = 'Masuk sebagai';
        $strings['NotSignedIn'] = 'Anda belum masuk';
        $strings['ReservationTitle'] = 'Judul reservasi';
        $strings['ReservationDescription'] = 'Keterangan reservasi';
        $strings['ResourceList'] = 'Resource yang bisa direservasi';
        $strings['Accessories'] = 'Aksesoris';
        $strings['ParticipantList'] = 'Partisipan';
        $strings['InvitationList'] = 'Undangan';
        $strings['AccessoryName'] = 'Nama Aksesoris';
        $strings['QuantityAvailable'] = 'Jumlah Tersedia';
        $strings['Resources'] = 'Resource';
        $strings['Participants'] = 'Partisipan';
        $strings['User'] = 'Pengguna';
        $strings['Resource'] = 'Resource';
        $strings['Status'] = 'Status';
        $strings['Approve'] = 'Disetujui';
        $strings['Page'] = 'Halaman';
        $strings['Rows'] = 'Baris';
        $strings['Unlimited'] = 'Tidak Terbatas';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Alamat Email';
        $strings['Phone'] = 'Telepon';
        $strings['Organization'] = 'Organisasi';
        $strings['Position'] = 'Posisi';
        $strings['Language'] = 'Bahasa';
        $strings['Permissions'] = 'Izin';
        $strings['Reset'] = 'Atur Ulang';
        $strings['FindGroup'] = 'Cari Grup';
        $strings['Manage'] = 'Mengatur';
        $strings['None'] = 'Tidak Ada';
        $strings['AddToOutlook'] = 'Tambah ke Kalender';
        $strings['Done'] = 'Selesai';
        $strings['RememberMe'] = 'Ingat Saya';
        $strings['FirstTimeUser?'] = 'Pengguna Baru?';
        $strings['CreateAnAccount'] = 'Buat Akun';
        $strings['ViewSchedule'] = 'Lihat Jadwal';
        $strings['ForgotMyPassword'] = 'Saya Lupa Kata Sandi';
        $strings['YouWillBeEmailedANewPassword'] = 'Anda akan dikirim email kata sandi acak yang baru';
        $strings['Close'] = 'Tutup';
        $strings['ExportToCSV'] = 'Ekspor ke CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Bekerja...';
        $strings['Login'] = 'Masuk';
        $strings['AdditionalInformation'] = 'Informasi Tambahan';
        $strings['AllFieldsAreRequired'] = 'semua kolom harus diisi';
        $strings['Optional'] = 'tambahan';
        $strings['YourProfileWasUpdated'] = 'Profil kamu berhasil diperbarui';
        $strings['YourSettingsWereUpdated'] = 'Pengaturan telah diperbarui';
        $strings['Register'] = 'Pendaftaran';
        $strings['SecurityCode'] = 'Kode Pengaman';
        $strings['ReservationCreatedPreference'] = 'Ketika Saya membuat reservasi atau atas nama Saya';
        $strings['ReservationUpdatedPreference'] = 'Ketika Saya memperbarui reservasi atau atas nama Saya';
        $strings['ReservationDeletedPreference'] = 'Ketika Saya menghapus reservasi atau atas nama Saya';
        $strings['ReservationApprovalPreference'] = 'Ketika reservasi yang ditunda telah disetujui';
        $strings['PreferenceSendEmail'] = 'Kirim Saya email';
        $strings['PreferenceNoEmail'] = 'Jangan beritahu Saya';
        $strings['ReservationCreated'] = 'Reservasi telah berhasil dibuat!';
        $strings['ReservationUpdated'] = 'Reservasi Anda berhasil diperbarui!';
        $strings['ReservationRemoved'] = 'Reservasi Anda telah dihapus';
        $strings['YourReferenceNumber'] = 'Nomor referensi kamu adalah %s';
        $strings['UpdatingReservation'] = 'Memperbarui reservasi';
        $strings['ChangeUser'] = 'Ganti Pengguna';
        $strings['MoreResources'] = 'Resource Lain';
        $strings['ReservationLength'] = 'Lama Reservasi';
        $strings['ParticipantList'] = 'Daftar Partisipan';
        $strings['AddParticipants'] = 'Tambah Partisipan';
        $strings['InviteOthers'] = 'Undang Yang Lain';
        $strings['AddResources'] = 'Tambah Resource';
        $strings['AddAccessories'] = 'Tambah Aksesoris';
        $strings['Accessory'] = 'Aksesoris';
        $strings['QuantityRequested'] = 'Jumlah Diminta';
        $strings['CreatingReservation'] = 'Buat Reservasi';
        $strings['UpdatingReservation'] = 'Perbarui Reservasi';
        $strings['DeleteWarning'] = 'This action is permanent and irrecoverable!';
        $strings['DeleteAccessoryWarning'] = 'Hapus aksesoris ini akan menghapusnya dari semua reservasi.';
        $strings['AddAccessory'] = 'Tambah Aksesoris';
        $strings['AddBlackout'] = 'Tambah Penghentian';
        $strings['AllResourcesOn'] = 'Semua Resouce Pada';
        $strings['Reason'] = 'Alasan';
        $strings['BlackoutShowMe'] = 'Tampilkan reservasi yang bentrok';
        $strings['BlackoutDeleteConflicts'] = 'Hapus eservasi yang bentrok';
        $strings['Filter'] = 'Saring';
        $strings['Between'] = 'Antara';
        $strings['CreatedBy'] = 'Dibuat Oleh';
        $strings['BlackoutCreated'] = 'Penghentian Dibuat!';
        $strings['BlackoutNotCreated'] = 'Penghentian tidak bisa dibuat!';
        $strings['BlackoutConflicts'] = 'Terdapat waktu penghentian yang bentrok';
        $strings['ReservationConflicts'] = 'Terdapat waktu resrvasi yang bentrok';
        $strings['UsersInGroup'] = 'Pengguna dalam grup ini';
        $strings['Browse'] = 'Telusuri';
        $strings['DeleteGroupWarning'] = 'Menghapus grup ini akan menghapus semua yang berhubungan izin resource. Pengguna dalam grup ini akankehilangan akses ke resource.';
        $strings['WhatRolesApplyToThisGroup'] = 'Peran apa yang diterapkan untuk grup ini?';
        $strings['WhoCanManageThisGroup'] = 'Siapa yang bisa mengatur grup ini?';
        $strings['WhoCanManageThisSchedule'] = 'Siapa yang bisa mengatur jadwal ini?';
        $strings['AddGroup'] = 'Tambah Grup';
        $strings['AllQuotas'] = 'Semua Kuota';
        $strings['QuotaReminder'] = 'Ingat: Kuota diberlakukan berdasarkan zona waktu jadwal.';
        $strings['AllReservations'] = 'Semua Reservasi';
        $strings['PendingReservations'] = 'Reservasi yang Ditunda';
        $strings['Approving'] = 'Menyetujui';
        $strings['MoveToSchedule'] = 'Pindah ke jadwal';
        $strings['DeleteResourceWarning'] = 'Menghapus resource ini akan menghapus semua data yang berhubungan, termasuk';
        $strings['DeleteResourceWarningReservations'] = 'semua reservasi yang telah lewat, saat ini dan yang akan datang yang berhubungan dengannya';
        $strings['DeleteResourceWarningPermissions'] = 'menerapkan semua perizinan';
        $strings['DeleteResourceWarningReassign'] = 'Silahkan menetapkan kembali semua yang Anda tidak ingin dihapus sebelum diproses';
        $strings['ScheduleLayout'] = 'Tampilan (semua waktu %s)';
        $strings['ReservableTimeSlots'] = 'Slot yang bisa direservasi';
        $strings['BlockedTimeSlots'] = 'Slot Yang Diblokir';
        $strings['ThisIsTheDefaultSchedule'] = 'Ini adalah jadwal standar';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Jadwal standar tidak bisa dihapus';
        $strings['MakeDefault'] = 'Jadikan Standar';
        $strings['BringDown'] = 'Pindahkan Ke Bawah';
        $strings['ChangeLayout'] = 'Ganti Tampilan';
        $strings['AddSchedule'] = 'Tambah Jadwal';
        $strings['StartsOn'] = 'Mulai Pada';
        $strings['NumberOfDaysVisible'] = 'Jumlah Hari yang Terlihat';
        $strings['UseSameLayoutAs'] = 'Gunakan Tampilan yang Sama dengan';
        $strings['Format'] = 'Format';
        $strings['OptionalLabel'] = 'Label Tambahan';
        $strings['LayoutInstructions'] = 'Masukkan satu slot per baris. Slot harus tersedia pada semua 24 jam sehari dimulai dan diakhiri pada 12:00 AM.';
        $strings['AddUser'] = 'Tambah Pengguna';
        $strings['UserPermissionInfo'] = 'Akses terhadap resource mungkin berbeda tergantung pada peran pengguna, akses grup, atau diluar pengaturan akses';
        $strings['DeleteUserWarning'] = 'Menghapus pengguna ini akan menghapus semua catatan reservasi (baik yang sudah lewat, saat ini, dan juga yang akan datang).';
        $strings['AddAnnouncement'] = 'Menambah Pengumuman';
        $strings['Announcement'] = 'Pengumuman';
        $strings['Priority'] = 'Prioritas';
        $strings['Reservable'] = 'Dapat direservasi';
        $strings['Unreservable'] = 'Tidak dapat direservasi';
        $strings['Reserved'] = 'Telah direservasi';
        $strings['MyReservation'] = 'Reservasi Saya';
        $strings['Pending'] = 'Ditunda';
        $strings['Past'] = 'Lampau';
        $strings['Restricted'] = 'Terbatas';
        $strings['ViewAll'] = 'Lihat Semua';
        $strings['MoveResourcesAndReservations'] = 'Pindah resource dan reservasi ke';
        $strings['TurnOffSubscription'] = 'Matikan Berlangganan Kalender';
        $strings['TurnOnSubscription'] = 'Mengizinkan Berlangganan ke Kalender ini';
        $strings['SubscribeToCalendar'] = 'Berlangganan ke Kalender ini';
        $strings['SubscriptionsAreDisabled'] = 'Administrator telah menonaktifkan berlangganan kalender';
        $strings['NoResourceAdministratorLabel'] = '(Tidk Ada Administrator Resource)';
        $strings['WhoCanManageThisResource'] = 'Siapa yang bisa mengatur resource ini?';
        $strings['ResourceAdministrator'] = 'Administrator Resource';
        $strings['Private'] = 'Tertutup';
        $strings['Accept'] = 'Menerima';
        $strings['Decline'] = 'Menolak';
        $strings['ShowFullWeek'] = 'Tampilan Minggu Penuh';
        $strings['CustomAttributes'] = 'Atribut Khusus';
        $strings['AddAttribute'] = 'Tambah Atribut';
        $strings['EditAttribute'] = 'Perbarui Atribut';
        $strings['DisplayLabel'] = 'Tampilan Label';
        $strings['Type'] = 'Tipe';
        $stringsa['Required'] = 'Diwajibkan';
        $strings['ValidationExpression'] = 'Ekspresi yang Sah';
        $strings['PossibleValues'] = 'Kemungkinan Isian';
        $strings['SingleLineTextbox'] = 'Textbox Satu Baris';
        $strings['MultiLineTextbox'] = 'Textboz Banyak Baris';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Daftar Pilihan';
        $strings['CommaSeparated'] = 'pisahkan dengan koma';
        $strings['Category'] = 'Kategori';
        $strings['CategoryReservation'] = 'Reservasi';
        $strings['CategoryGroup'] = 'Grup';
        $strings['SortOrder'] = 'Urutan';
        $strings['Title'] = 'Judul';
        $strings['AdditionalAttributes'] = 'Atribut Tambahan';
        $strings['True'] = 'Benar';
        $strings['False'] = 'Salah';
        $strings['ForgotPasswordEmailSent'] = 'Sebuah email telah dikirim ke alamat yang ditulis dengan perintah untuk mangatur ulang kata sandi Anda';
        $strings['ActivationEmailSent'] = 'Anda akan menerima email aktivasi segera.';
        $strings['AccountActivationError'] = 'Maaf, kamu tidak bisa mengaktifkan akun Anda.';
        $strings['Attachments'] = 'Lampiran';
        $strings['AttachFile'] = 'Lampirkan File';
        $strings['Maximum'] = 'maksimal';
        $strings['NoScheduleAdministratorLabel'] = 'Tidak Ada Administrator Jadwal';
        $strings['ScheduleAdministrator'] = 'Administrator Jadwal';
        $strings['Total'] = 'Total';
        $strings['QuantityReserved'] = 'Jumlah yang direservasi';
        $strings['AllAccessories'] = 'Semua Aksesoris';
        $strings['GetReport'] = 'Dapatkan Laporan';
        $strings['NoResultsFound'] = 'Tidak ada hasil yang cocok';
        $strings['SaveThisReport'] = 'Simpan Laporan Ini';
        $strings['ReportSaved'] = 'Laporan Disimpan!';
        $strings['EmailReport'] = 'Laporan Email';
        $strings['ReportSent'] = 'Laporan dikirim!';
        $strings['RunReport'] = 'Jalankan Laporan';
        $strings['NoSavedReports'] = 'Anda tidak mempunyai laporan tersimpan.';
        $strings['CurrentWeek'] = 'Minggu Ini';
        $strings['CurrentMonth'] = 'Bulan Ini';
        $strings['AllTime'] = 'Semua Waktu';
        $strings['FilterBy'] = 'Disaring Oleh';
        $strings['Select'] = 'Pilih';
        $strings['List'] = 'Daftar';
        $strings['TotalTime'] = 'Total Waktu';
        $strings['Count'] = 'Hitung';
        $strings['Usage'] = 'Pemakaian';
        $strings['AggregateBy'] = 'Digabung Dengan';
        $strings['Range'] = 'Rentang';
        $strings['Choose'] = 'Pilih';
        $strings['All'] = 'Semua';
        $strings['ViewAsChart'] = 'Tampilkan sabagai Grafik';
        $strings['ReservedResources'] = 'Resource Direservasi';
        $strings['ReservedAccessories'] = 'Aksesoris Direservasi';
        $strings['ResourceUsageTimeBooked'] = 'Pemakaian Resource - Waktu Booking';
        $strings['ResourceUsageReservationCount'] = 'Pemakaian Resource - Perhitungan Reservasi';
        $strings['Top20UsersTimeBooked'] = '20 Pengguna Teratas - Waktu Booking';
        $strings['Top20UsersReservationCount'] = '20 Pengguna Teratas - Perhitungan Reservasi';
        $strings['ConfigurationUpdated'] = 'File Konfigurasi berhasil diperbarui';
        $strings['ConfigurationUiNotEnabled'] = 'Halaman ini tidak bisa diakses karena $conf[\'settings\'][\'pages\'][\'enable.configuration\'] diatur sebagai \'false\' atau hilang.';
        $strings['ConfigurationFileNotWritable'] = 'File Konfigurasi tidak bisa ditulis (writeable). Mohon periksa akses ke file dan ulangi lagi..';
        $strings['ConfigurationUpdateHelp'] = 'Lihat bagian konfigurasi dari <a target=_blank href=%s>File Bantuan</a> untuk penjelasan dari pengaturan ini.';
        $strings['GeneralConfigSettings'] = 'pengaturan';
        $strings['UseSameLayoutForAllDays'] = 'Gunakan tampilan yang sama untuk semua hari';
        $strings['LayoutVariesByDay'] = 'Tampilan bervariasi per hari';
        $strings['ManageReminders'] = 'Pengingat';
        $strings['ReminderUser'] = 'ID Pengguna';
        $strings['ReminderMessage'] = 'Pesan';
        $strings['ReminderAddress'] = 'Alamat';
        $strings['ReminderSendtime'] = 'Waktu Pengiriman';
        $strings['ReminderRefNumber'] = 'Nomor Referensi Reservasi';
        $strings['ReminderSendtimeDate'] = 'Hari Pengingat';
        $strings['ReminderSendtimeTime'] = 'Waktu Pengingat (JJ:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Tambah Pengingat';
        $strings['DeleteReminderWarning'] = 'Anda yakin?';
        $strings['NoReminders'] = 'Anda memiliki pengingat yang akan datang.';
        $strings['Reminders'] = 'Pengingat';
        $strings['SendReminder'] = 'Kirim Pengingat';
        $strings['minutes'] = 'menit';
        $strings['hours'] = 'detik';
        $strings['days'] = 'hari';
        $strings['ReminderBeforeStart'] = 'sebelum waktu mulai';
        $strings['ReminderBeforeEnd'] = 'sebelum waktu berakhir';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'File CSS';
        $strings['ThemeUploadSuccess'] = 'Perubahan berhasil disimpan. Refresh halaman untuk melihat perubahan.';
        $strings['MakeDefaultSchedule'] = 'Jadikan ini jadwal standar saya';
        $strings['DefaultScheduleSet'] = 'Sekarang menjadi jadwal standar Anda';
        $strings['FlipSchedule'] = 'Balik tampilan jadwal';
        $strings['Next'] = 'Berikutnya';
        $strings['Success'] = 'Berhasil';
        $strings['Participant'] = 'Partisipan';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Pasang LibreBooking (hanya MySQL)';
        $strings['IncorrectInstallPassword'] = 'Maaf, kata sandi salah.';
        $strings['SetInstallPassword'] = 'Anda harus mengatur kata sandi pemasangan sebelum pemasangan bisa dijalankan.';
        $strings['InstallPasswordInstructions'] = 'Dalam %s mohon atur %s manjadi kata sandi yang acak dan susah untuk ditebak, kemudian kembali ke halaman ini.<br/>Anda bisa menggunakan %s';
        $strings['NoUpgradeNeeded'] = 'Tidak perlu pembaruan. Jalankan proses pemasangan akan menghapus semua dara yang ada dan memasang salinan baru LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Mohon masukkan kata sandi pemasangan.';
        $strings['InstallPasswordLocation'] = 'Bisa ditemukan di %s dalam %s.';
        $strings['VerifyInstallSettings'] = 'Periksa pengaturan standar berikut sebelum melanjutkan. Atau Anda bisa mengubahnya dalam %s.';
        $strings['DatabaseName'] = 'Nama Database';
        $strings['DatabaseUser'] = 'Pengguna Database';
        $strings['DatabaseHost'] = 'Host Database';
        $strings['DatabaseCredentials'] = 'Anda harus memberi kredensial pengguna MySQL yang memiliki hak untuk membuat database. Jika Anda tidak tahu, hubungi admin database Anda. Dalam banyak kasus, \'root\' bisa bekerja.';
        $strings['MySQLUser'] = 'Pengguna MySQL';
        $strings['InstallOptionsWarning'] = 'Pilihan berikut mungkin tidak jalan pada lingkungan hosted. Jika pemasangan di dalam lingkungan hosted, gunakan \'MySQL wizard tools\' untuk melengkapi proses.';
        $strings['CreateDatabase'] = 'Buat database';
        $strings['CreateDatabaseUser'] = 'Buat pengguna database';
        $strings['PopulateExampleData'] = 'Impor data contoh. Buat akun admin: admin/password dan pengguna akun: user/password';
        $strings['DataWipeWarning'] = 'Peringatan: Akan menghapus semua data yang ada';
        $strings['RunInstallation'] = 'Jalankan Pemasangan';
        $strings['UpgradeNotice'] = 'Anda mengupgrade dari versi <b>%s</b> ke versi <b>%s</b>';
        $strings['RunUpgrade'] = 'Jalankan Upgrade';
        $strings['Executing'] = 'Laksanakan';
        $strings['StatementFailed'] = 'Gagal. Keterangan:';
        $strings['SQLStatement'] = 'Pernyataan SQL:';
        $strings['ErrorCode'] = 'Kode Error:';
        $strings['ErrorText'] = 'Teks Error:';
        $strings['InstallationSuccess'] = 'Pemasangan berhasil!';
        $strings['RegisterAdminUser'] = 'Daftar pengguna admin Anda. Dibutuhkan jika Anda tidak ingin impor contoh data. Pastikan $conf[\'settings\'][\'allow.self.registration\'] = \'true\' dalam file %s .';
        $strings['LoginWithSampleAccounts'] = 'Jika Anda impor contoh data, Anda bisa masuk dengan admin/password untuk pengguna admin atau user/password untuk pengguna dasar.';
        $strings['InstalledVersion'] = 'Anda sekarang menjalankan versi %s dari LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Direkomendasikan untuk mengupgrade file config Anda';
        $strings['InstallationFailure'] = 'Ada masalah dengan instalasi. Mohon perbaik dan ulangi pemasangan.';
        $strings['ConfigureApplication'] = 'Pengaturan LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'File config Anda sekarang yang terbaru!';
        $strings['ConfigUpdateFailure'] = 'Kami tidak bisa memperbarui otomatis file config Anda. Mohon tulis ulang isi config.php berikut:';
        // End Install

        // Errors
        $strings['LoginError'] = 'Kami tidak bisa mencocokkan nama pengguna atau kata sandi';
        $strings['ReservationFailed'] = 'Reservasi Anda tidak dapat dibuat';
        $strings['MinNoticeError'] = 'Reservasi ini terdapat catatan lanjutan. Tanggal dan waktu paling awal yang bisa disimpan adalah %s.';
        $strings['MaxNoticeError'] = 'Reservasi ini tidak bisa dibuat terlalu jauh pada masa yang akan datang. Tanggal dan waktu paling akhir yang bisa direservasi %s.';
        $strings['MinDurationError'] = 'Reservasi ini harus berakhir minimal %s.';
        $strings['MaxDurationError'] = 'Reservasi ini tidak boleh lebih lama dari %s.';
        $strings['ConflictingAccessoryDates'] = 'Aksesoris berikut tidak cukup:';
        $strings['NoResourcePermission'] = 'Anda tidak memiliki akses untuk resource yang diminta';
        $strings['ConflictingReservationDates'] = 'Terjadi bentrokan reservasi pada tanggal berikut:';
        $strings['StartDateBeforeEndDateRule'] = 'Tanggal dan waktu mulai harus sebelum tanggal dan waktu berakhir';
        $strings['StartIsInPast'] = 'Tanggal dan waktu mulai tidak bisa dilakukan pada masa lampau';
        $strings['EmailDisabled'] = 'Administrator menonatifkan email pemberitahuan';
        $strings['ValidLayoutRequired'] = 'Slot harus tersedia pada semua 24 jam sehari dimulai dan diakhiri pada 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'Terjadi masalah dengan atribut tambahan yang diisi berikut:';
        $strings['CustomAttributeRequired'] = '%s kolom wajib diisi';
        $strings['CustomAttributeInvalid'] = 'Isian untuk %s tidak sah';
        $strings['AttachmentLoadingError'] = 'Maaf, terjadi masalah saat \'loading\' file yang diminta.';
        $strings['InvalidAttachmentExtension'] = 'Kamu bisa mengunggah file dengan tipe: %s';
        $strings['InvalidStartSlot'] = 'Tanggal dan waktu mulai yang diminta tidak sah.';
        $strings['InvalidEndSlot'] = 'Tanggal dan waktu akhir yang diminta tidak sah.';
        $strings['MaxParticipantsError'] = '%s hanya bisa mendukung %s partisipan.';
        $strings['ReservationCriticalError'] = 'Terjadi error yang penting saat menyimpan reservasi Anda. Jika ini terjadi lagi, hubungi administrator sistem Anda.';
        $strings['InvalidStartReminderTime'] = 'Waktu mulai pengingat anda tidak sah.';
        $strings['InvalidEndReminderTime'] = 'Waktu akhir pengingat anda tidak sah.';
        // End Errors



        // Page Titles
        $strings['CreateReservation'] = 'Buat Reservasi';
        $strings['EditReservation'] = 'Ubah Reservasi';
        $strings['LogIn'] = 'Masuk';
        $strings['ManageReservations'] = 'Reservasi';
        $strings['AwaitingActivation'] = 'Menunggu Aktivasi';
        $strings['PendingApproval'] = 'Penangguhan Persetujuan';
        $strings['ManageSchedules'] = 'Jadwal';
        $strings['ManageResources'] = 'Resource';
        $strings['ManageAccessories'] = 'Aksesoris';
        $strings['ManageUsers'] = 'Pengguna';
        $strings['ManageGroups'] = 'Grup';
        $strings['ManageQuotas'] = 'Kuota';
        $strings['ManageBlackouts'] = 'Waktu Penghentian';
        $strings['MyDashboard'] = 'Dashboard Saya';
        $strings['ServerSettings'] = 'Pengaturan Server';
        $strings['Dashboard'] = 'Dashboard';
        $strings['Help'] = 'Bantuan';
        $strings['Administration'] = 'Administrasi';
        $strings['About'] = 'Tentang';
        $strings['Bookings'] = 'Booking';
        $strings['Schedule'] = 'Jadwal';
        $strings['Reservations'] = 'Reservasi';
        $strings['Account'] = 'Akun';
        $strings['EditProfile'] = 'Ubah Profil Saya';
        $strings['FindAnOpening'] = 'Mencari Pembukaan';
        $strings['OpenInvitations'] = 'Buka Undangan';
        $strings['MyCalendar'] = 'Kalender Saya';
        $strings['ResourceCalendar'] = 'Kalender Resource';
        $strings['Reservation'] = 'Reservasi Baru';
        $strings['Install'] = 'Pemasangan';
        $strings['ChangePassword'] = 'Ganti Kata Sandi';
        $strings['MyAccount'] = 'Akun Saya';
        $strings['Profile'] = 'Profil';
        $strings['ApplicationManagement'] = 'Pengaturan Aplikasi';
        $strings['ForgotPassword'] = 'Lupa Kata Sandi';
        $strings['NotificationPreferences'] = 'Pengaturan Pemberitahuan';
        $strings['ManageAnnouncements'] = 'Pengumuman';
        $strings['Responsibilities'] = 'Tanggung Jawab';
        $strings['GroupReservations'] = 'Grup Reservasi';
        $strings['ResourceReservations'] = 'Resource Reservasi';
        $strings['Customization'] = 'Penyesuaian';
        $strings['Attributes'] = 'Atribut';
        $strings['AccountActivation'] = 'Akitvasi Akun';
        $strings['ScheduleReservations'] = 'Jadwal Reservasi';
        $strings['Reports'] = 'Laporan';
        $strings['GenerateReport'] = 'Buat Laporan Baru';
        $strings['MySavedReports'] = 'Laporan Saya Yang Tersimpan';
        $strings['CommonReports'] = 'Laporan Umum';
        $strings['ViewDay'] = 'Lihat Hari';
        $strings['Group'] = 'Grup';
        $strings['ManageConfiguration'] = 'Konfigurasi Aplikasi';
        $strings['LookAndFeel'] = 'Tampilan dan Suasana';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'M';
        $strings['DayMondaySingle'] = 'S';
        $strings['DayTuesdaySingle'] = 'S';
        $strings['DayWednesdaySingle'] = 'R';
        $strings['DayThursdaySingle'] = 'K';
        $strings['DayFridaySingle'] = 'J';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Min';
        $strings['DayMondayAbbr'] = 'Sen';
        $strings['DayTuesdayAbbr'] = 'Sel';
        $strings['DayWednesdayAbbr'] = 'Rab';
        $strings['DayThursdayAbbr'] = 'Kam';
        $strings['DayFridayAbbr'] = 'Jum';
        $strings['DaySaturdayAbbr'] = 'Sab';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Reservasi Anda Sudah Disetujui';
        $strings['ReservationCreatedSubject'] = 'Reservasi Anda Telah Dibuat';
        $strings['ReservationUpdatedSubject'] = 'Reservasi Anda Telah Diperbarui';
        $strings['ReservationDeletedSubject'] = 'Reservasi Anda Telah Dihapus';
        $strings['ReservationCreatedAdminSubject'] = 'Pemberitahuan: Reservasi Sudah Dibuat';
        $strings['ReservationUpdatedAdminSubject'] = 'Pemberitahuan: Reservasi Sudah Diperbarui';
        $strings['ReservationDeleteAdminSubject'] = 'Pemberitahuan: Reservasi Sudah Dihapus';
        $strings['ParticipantAddedSubject'] = 'Pemberitahuan Reservasi Partisipan';
        $strings['ParticipantDeletedSubject'] = 'Reservasi Telah Dihapus';
        $strings['InviteeAddedSubject'] = 'Undangan Reservasi';
        $strings['ResetPassword'] = 'Permintaan Pengaturan Ulang Kata Sandi';
        $strings['ActivateYourAccount'] = 'Mohon Aktivasi Akun Anda';
        $strings['ReportSubject'] = 'Permintaan Laporan Anda (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Reservasi untuk %s akan segera dimulai';
        $strings['ReservationEndingSoonSubject'] = 'Reservasi untuk %s akan segera berakhir';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Anda tidak memiliki reservasi sebelumnya';
        $strings['PastReservations'] = 'Reservasi Sebelumnya';
        $strings['AllNoPastReservations'] = 'Tidak ada reservasi sebelumnya dalam %s hari terakhir';
        $strings['AllPastReservations'] = 'Semua Reservasi Sebelumnya';
        $strings['Yesterday'] = 'Kemarin';
        $strings['EarlierThisWeek'] = 'Lebih awal pekan ini';
        $strings['PreviousWeek'] = 'Pekan sebelumnya';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Grup Anda tidak memiliki reservasi mendatang';
        $strings['GroupUpcomingReservations'] = 'Reservasi Mendatang Grup Saya';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Terjadi kesalahan saat login dengan Facebook. Silakan coba lagi.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Anda tidak memiliki reservasi yang menunggu persetujuan';
        $strings['PendingApprovalReservations'] = 'Pemesanan Menunggu Persetujuan';
        $strings['LaterThisMonth'] = 'Nanti Bulan Ini';
        $strings['LaterThisYear'] = 'Nanti Tahun Ini';
        $strings['Remaining'] = 'Sisa';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Tidak ada reservasi check out yang hilang';
        $strings['MissingCheckOutReservations'] = 'Reservasi Check Out yang Hilang';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Tidak dapat melihat detail pemesanan karena Anda tidak memiliki izin untuk salah satu dari sumber daya dalam pemesanan ini';
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
        $days['full'] = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];
        // The three letter abbreviation
        $days['abbr'] = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        // The two letter abbreviation
        $days['two'] = ['Mi', 'Se', 'Se', 'Ra', 'Ka', 'Ju', 'Sa'];
        // The one letter abbreviation
        $days['letter'] = ['M', 'S', 'S', 'R', 'K', 'J', 'S'];

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
        $months['full'] = ['Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'Nopember', 'Desember'];
        // The three letter month name
        $months['abbr'] = ['Jan', 'Peb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nop', 'Des'];

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
        return 'id';
    }
}
