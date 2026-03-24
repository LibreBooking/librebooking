<?php

require_once('en_gb.php');

class vn_vn extends en_gb
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
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Họ';
        $strings['LastName'] = 'Tên';
        $strings['Timezone'] = 'Múi Giờ';
        $strings['Edit'] = 'Chỉnh Sửa';
        $strings['Change'] = 'Thay Đổi';
        $strings['Rename'] = 'Đổi tên';
        $strings['Remove'] = 'Xóa bỏ';
        $strings['Delete'] = 'Xóa';
        $strings['Update'] = 'Cập nhật';
        $strings['Cancel'] = 'Hủy';
        $strings['Add'] = 'Thêm';
        $strings['Name'] = 'Tên';
        $strings['Yes'] = 'Yes';
        $strings['No'] = 'No';
        $strings['FirstNameRequired'] = 'Yêu cầu phải có Họ.';
        $strings['LastNameRequired'] = 'Yêu cầu phải có Tên.';
        $strings['PwMustMatch'] = 'Mật khẩu xác nhận phải trùng với mật khẩu vừa đánh.';
        $strings['ValidEmailRequired'] = 'Yêu cầu phải có địa chỉ e-mail tồn tại để xác thực.';
        $strings['UniqueEmailRequired'] = 'Địa chỉ E-mail đã được đăng ký.';
        $strings['UniqueUsernameRequired'] = 'Tên đăng nhập đã được đăng ký.';
        $strings['UserNameRequired'] = 'Yêu cầu phải có username.';
        $strings['CaptchaMustMatch'] = 'Vui lòng đền đúng những ký tự mà bạn thấy dưới ảnh.';
        $strings['Today'] = 'Ngày hôm nay';
        $strings['Week'] = 'Tuần';
        $strings['Month'] = 'Tháng';
        $strings['BackToCalendar'] = 'Quay trở lại Lịch';
        $strings['BeginDate'] = 'Bắt đầu';
        $strings['EndDate'] = 'Kết thúc';
        $strings['Username'] = 'Tên đăng nhập';
        $strings['Password'] = 'Mật khẩu';
        $strings['PasswordConfirmation'] = 'Xác nhận mật khẩu';
        $strings['DefaultPage'] = 'Trang chủ mặc định';
        $strings['MyCalendar'] = 'Lịch của tôi';
        $strings['ScheduleCalendar'] = 'Lịch';
        $strings['Registration'] = 'Đăng Ký';
        $strings['NoAnnouncements'] = 'Không có thông báo mới';
        $strings['Announcements'] = 'Thông Báo';
        $strings['NoUpcomingReservations'] = 'Sắp tới, Bạn không có lịch đặt phòng nào.';
        $strings['UpcomingReservations'] = 'Đặt phòng sắp tới';
        $strings['AllNoUpcomingReservations'] = 'Không có lịch họp nào đến %s ngày tiếp theo';
        $strings['AllUpcomingReservations'] = 'Tất cả lịch đặt phòng sắp tới';
        $strings['ShowHide'] = 'Hiện/Ẩn';
        $strings['Error'] = 'Lỗi !';
        $strings['ReturnToPreviousPage'] = 'Quay lại trang cuối cùng mà bạn đã truy cập';
        $strings['UnknownError'] = 'Lỗi chưa xác định';
        $strings['InsufficientPermissionsError'] = 'Lỗi ! Bạn phải đăng nhập vào hệ thống để có thể đặt phòng';
        $strings['MissingReservationResourceError'] = 'A resource was not selected';
        $strings['MissingReservationScheduleError'] = 'A schedule was not selected';
        $strings['DoesNotRepeat'] = 'Không lặp lại';
        $strings['Daily'] = 'Hàng ngày';
        $strings['Weekly'] = 'Hàng Tuần';
        $strings['Monthly'] = 'Hàng Tháng';
        $strings['Yearly'] = 'Hàng Năm';
        $strings['RepeatPrompt'] = 'Lặp lại';
        $strings['hours'] = 'Giờ';
        $strings['days'] = 'Ngày';
        $strings['weeks'] = 'Tuần';
        $strings['months'] = 'Tháng';
        $strings['years'] = 'Năm';
        $strings['day'] = 'Ngày';
        $strings['week'] = 'Tuần';
        $strings['month'] = 'Tháng';
        $strings['year'] = 'Năm';
        $strings['repeatDayOfMonth'] = 'Ngày trong tháng';
        $strings['repeatDayOfWeek'] = 'Ngày trong tuần';
        $strings['RepeatUntilPrompt'] = 'Cho đến khi';
        $strings['RepeatEveryPrompt'] = 'Every';
        $strings['RepeatDaysPrompt'] = 'On';
        $strings['CreateReservationHeading'] = 'Đặt phòng mới';
        $strings['EditReservationHeading'] = 'Sửa lịch họp %s';
        $strings['ViewReservationHeading'] = 'Xem Lịch họp %s';
        $strings['ReservationErrors'] = 'Đổi đặt phòng';
        $strings['Create'] = 'Tạo mới';
        $strings['ThisInstance'] = 'Only This Instance';
        $strings['AllInstances'] = 'All Instances';
        $strings['FutureInstances'] = 'Future Instances';
        $strings['Print'] = 'In';
        $strings['ShowHideNavigation'] = 'Show/Hide Navigation';
        $strings['ReferenceNumber'] = 'Reference Number';
        $strings['Tomorrow'] = 'Ngày Mai';
        $strings['LaterThisWeek'] = 'Later This Week';
        $strings['NextWeek'] = 'Tuần tới';
        $strings['SignOut'] = 'Đăng xuất';
        $strings['LayoutDescription'] = 'Starts on %s, showing %s days at a time';
        $strings['AllResources'] = 'Tất cả lịch họp';
        $strings['TakeOffline'] = 'Take Offline';
        $strings['BringOnline'] = 'Bring Online';
        $strings['AddImage'] = 'Thêm Ảnh';
        $strings['NoImage'] = 'No Image Assigned';
        $strings['Move'] = 'Di Chuyển đi...';
        $strings['AppearsOn'] = 'Appears On %s';
        $strings['Location'] = 'Vị trí';
        $strings['NoLocationLabel'] = '(Vị trí chưa được cài đặt)';
        $strings['Contact'] = 'Liên Hệ';
        $strings['NoContactLabel'] = '(Không có thông tin liên hệ)';
        $strings['Description'] = 'Miêu tả ngắn';
        $strings['NoDescriptionLabel'] = '(Không có miêu tả ngắn)';
        $strings['Notes'] = 'Ghi Chú';
        $strings['NoNotesLabel'] = '(Không có ghi chú nào)';
        $strings['NoTitleLabel'] = '(Không có tiêu đề)';
        $strings['UsageConfiguration'] = 'Sử dụng cấu hình này';
        $strings['ChangeConfiguration'] = 'Thay đổi cấu hình';
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
        $strings['AddNewResource'] = 'Tạo lịch họp mới';
        $strings['AddNewUser'] = 'Thêm Người dùng mới';
        $strings['AddResource'] = 'Add Resource';
        $strings['Capacity'] = 'Sức chứa';
        $strings['Access'] = 'Access';
        $strings['Duration'] = 'Duration';
        $strings['Active'] = 'Kích hoạt';
        $strings['Inactive'] = 'Bỏ kích hoạt';
        $strings['ResetPassword'] = 'Khôi phục mật khẩu';
        $strings['LastLogin'] = 'Lần đăng nhập trước';
        $strings['Search'] = 'Tìm kiếm';
        $strings['ResourcePermissions'] = 'Resource Permissions';
        $strings['Reservations'] = 'Reservations';
        $strings['Groups'] = 'Nhóm';
        $strings['Users'] = 'Người sử dụng';
        $strings['AllUsers'] = 'Tất cả người sử dụng';
        $strings['AllGroups'] = 'Tất cả các nhóm';
        $strings['AllSchedules'] = 'Tất cả các lịch họp';
        $strings['UsernameOrEmail'] = 'Tên đăng nhập hoặc Email';
        $strings['Members'] = 'Thành viên';
        $strings['QuickSlotCreation'] = 'Create slots every %s minutes between %s and %s';
        $strings['ApplyUpdatesTo'] = 'Áp dụng cập nhật đến..';
        $strings['CancelParticipation'] = 'Hủy những người sẽ tham gia họp';
        $strings['Attending'] = 'Attending';
        $strings['QuotaConfiguration'] = 'On %s for %s users in %s are limited to %s %s per %s';
        $strings['QuotaEnforcement'] = 'Enforced %s %s';
        $strings['reservations'] = 'reservations';
        $strings['reservation'] = 'reservation';
        $strings['ChangeCalendar'] = 'Đổi Lịch';
        $strings['AddQuota'] = 'Thêm định mức';
        $strings['FindUser'] = 'Tìm người dùng';
        $strings['Created'] = 'Đã tạo';
        $strings['LastModified'] = 'Lần thay đổi gần đây';
        $strings['GroupName'] = 'Tên Nhóm';
        $strings['GroupMembers'] = 'Thành Viên Nhóm';
        $strings['GroupRoles'] = 'Group Roles';
        $strings['GroupAdmin'] = 'Quản trị viên Nhóm';
        $strings['Actions'] = 'Actions';
        $strings['CurrentPassword'] = 'Mật khẩu hiện tại';
        $strings['NewPassword'] = 'Mật khẩu mới';
        $strings['InvalidPassword'] = 'Mật khẩu hiện tại không đúng';
        $strings['PasswordChangedSuccessfully'] = 'Mật khẩu đã được đổi thành công';
        $strings['SignedInAs'] = 'Signed in as';
        $strings['NotSignedIn'] = 'Bạn chưa đăng nhập...';
        $strings['ReservationTitle'] = 'Tiêu đề cuộc họp';
        $strings['ReservationDescription'] = 'Mô tả ngắn về cuộc họp';
        $strings['ResourceList'] = 'Resources to be reserved';
        $strings['Accessories'] = 'Trang thiết bị cần cho cuộc họp:';
        $strings['InvitationList'] = 'Mời họp';
        $strings['AccessoryName'] = 'Thiết bị/Phụ kiện..';
        $strings['QuantityAvailable'] = 'Số lượng hiện có: ';
        $strings['Resources'] = 'Phòng họp: ';
        $strings['Participants'] = 'Participants';
        $strings['User'] = 'User';
        $strings['Resource'] = 'Resource';
        $strings['Status'] = 'Trạng thái';
        $strings['Approve'] = 'Phê Duyệt';
        $strings['Page'] = 'Trang..';
        $strings['Rows'] = 'Dòng..';
        $strings['Unlimited'] = 'Không giới hạn';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Địa chỉ E-mail';
        $strings['Phone'] = 'Số điện thoại';
        $strings['Organization'] = 'Phòng Ban';
        $strings['Position'] = 'Vị Trí';
        $strings['Language'] = 'Ngôn Ngữ';
        $strings['Permissions'] = 'Permissions';
        $strings['Reset'] = 'Kh';
        $strings['FindGroup'] = 'Tìm nhóm';
        $strings['Manage'] = 'Manage';
        $strings['None'] = 'None';
        $strings['AddToOutlook'] = 'Add to Calendar';
        $strings['Done'] = 'Done';
        $strings['RememberMe'] = 'Ghi nhớ đăng nhập';
        $strings['FirstTimeUser?'] = 'Đây là lần đầu tiên bạn sử dụng hệ thống ?';
        $strings['CreateAnAccount'] = 'Tạo một tài khoản mới';
        $strings['ViewSchedule'] = 'Xem Lịch Họp';
        $strings['ForgotMyPassword'] = 'Tôi quên mật khẩu..';
        $strings['YouWillBeEmailedANewPassword'] = 'Hệ thống sẽ tự động gửi mật khẩu vào e-mail cho bạn.';
        $strings['Close'] = 'Đóng';
        $strings['ExportToCSV'] = 'Xuất file sang CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Working...';
        $strings['Login'] = 'Đăng Nhập';
        $strings['AdditionalInformation'] = 'Thông tin thêm';
        $strings['AllFieldsAreRequired'] = 'all fields are required';
        $strings['Optional'] = 'Tùy Chọn';
        $strings['YourProfileWasUpdated'] = 'Hồ sơ của bạn đã được cập nhật';
        $strings['YourSettingsWereUpdated'] = 'Cấu hình của bạn đã được cập nhật';
        $strings['Register'] = 'Đăng ký';
        $strings['SecurityCode'] = 'Mã bảo mật';
        $strings['ReservationCreatedPreference'] = 'When I create a reservation or a reservation is created on my behalf';
        $strings['ReservationUpdatedPreference'] = 'When I update a reservation or a reservation is updated on my behalf';
        $strings['ReservationDeletedPreference'] = 'When I delete a reservation or a reservation is deleted on my behalf';
        $strings['ReservationApprovalPreference'] = 'When my pending reservation is approved';
        $strings['PreferenceSendEmail'] = 'Gửi E-mail cho tôi';
        $strings['PreferenceNoEmail'] = 'Không gửi thông báo cho tôi';
        $strings['ReservationCreated'] = 'Your reservation was successfully created!';
        $strings['ReservationUpdated'] = 'Your reservation was successfully updated!';
        $strings['ReservationRemoved'] = 'Your reservation was removed';
        $strings['ReservationRequiresApproval'] = 'One or more of the resources reserved require approval before usage.  This reservation will be pending until it is approved.';
        $strings['YourReferenceNumber'] = 'Your reference number is %s';
        $strings['ChangeUser'] = 'Change User';
        $strings['MoreResources'] = 'More Resources';
        $strings['ReservationLength'] = 'Thời gian họp là: ';
        $strings['ParticipantList'] = 'Danh sách người tham dự họp:';
        $strings['AddParticipants'] = 'Thêm người tham dự';
        $strings['InviteOthers'] = 'Thêm người họp khác:';
        $strings['AddResources'] = 'Tất cả các phòng họp:';
        $strings['AddAccessories'] = 'Thêm trang thiết bị:';
        $strings['Accessory'] = 'Trang/thiết bị';
        $strings['QuantityRequested'] = 'Số lượng yêu cầu';
        $strings['CreatingReservation'] = 'Tạo cuộc họp mới';
        $strings['UpdatingReservation'] = 'Cập nhật thông tin cuộc họp';
        $strings['DeleteWarning'] = 'This action is permanent and irrecoverable!';
        $strings['DeleteAccessoryWarning'] = 'Deleting this accessory will remove it from all reservations.';
        $strings['AddAccessory'] = 'Add Accessory';
        $strings['AddBlackout'] = 'Add Blackout';
        $strings['AllResourcesOn'] = 'All Resources On';
        $strings['Reason'] = 'Reason';
        $strings['BlackoutShowMe'] = 'Hiện lịch họp bị trùng giờ';
        $strings['BlackoutDeleteConflicts'] = 'Xóa lịch họp bị trùng giờ';
        $strings['Filter'] = 'Tìm kiếm';
        $strings['Between'] = 'Between';
        $strings['CreatedBy'] = 'Created By';
        $strings['BlackoutCreated'] = 'Blackout Created';
        $strings['BlackoutNotCreated'] = 'Blackout could not be created';
        $strings['BlackoutUpdated'] = 'Blackout Updated';
        $strings['BlackoutNotUpdated'] = 'Blackout could not be updated';
        $strings['BlackoutConflicts'] = 'There are conflicting blackout times';
        $strings['ReservationConflicts'] = 'There are conflicting reservations times';
        $strings['UsersInGroup'] = 'Users in this group';
        $strings['Browse'] = 'Browse';
        $strings['DeleteGroupWarning'] = 'Deleting this group will remove all associated resource permissions.  Users in this group may lose access to resources.';
        $strings['WhatRolesApplyToThisGroup'] = 'Which roles apply to this group?';
        $strings['WhoCanManageThisGroup'] = 'Who can manage this group?';
        $strings['WhoCanManageThisSchedule'] = 'Who can manage this schedule?';
        $strings['AllQuotas'] = 'All Quotas';
        $strings['QuotaReminder'] = 'Remember: Quotas are enforced based on the schedule\'s timezone.';
        $strings['AllReservations'] = 'All Reservations';
        $strings['PendingReservations'] = 'Pending Reservations';
        $strings['Approving'] = 'Approving';
        $strings['MoveToSchedule'] = 'Move to schedule';
        $strings['DeleteResourceWarning'] = 'Deleting this resource will delete all associated data, including';
        $strings['DeleteResourceWarningReservations'] = 'all past, current and future reservations associated with it';
        $strings['DeleteResourceWarningPermissions'] = 'all permission assignments';
        $strings['DeleteResourceWarningReassign'] = 'Please reassign anything that you do not want to be deleted before proceeding';
        $strings['ScheduleLayout'] = 'Layout (all times %s)';
        $strings['ReservableTimeSlots'] = 'Reservable Time Slots';
        $strings['BlockedTimeSlots'] = 'Blocked Time Slots';
        $strings['ThisIsTheDefaultSchedule'] = 'This is the default schedule';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Default schedule cannot be deleted';
        $strings['MakeDefault'] = 'Đặt làm mặc định';
        $strings['BringDown'] = 'Bring Down';
        $strings['ChangeLayout'] = 'Change Layout';
        $strings['AddSchedule'] = 'Add Schedule';
        $strings['StartsOn'] = 'Starts On';
        $strings['NumberOfDaysVisible'] = 'Number of Days Visible';
        $strings['UseSameLayoutAs'] = 'Use Same Layout As';
        $strings['Format'] = 'Định dạng';
        $strings['OptionalLabel'] = 'Optional Label';
        $strings['LayoutInstructions'] = 'Enter one slot per line.  Slots must be provided for all 24 hours of the day beginning and ending at 12:00 AM.';
        $strings['AddUser'] = 'Thêm người dùng';
        $strings['UserPermissionInfo'] = 'Actual access to resource may be different depending on user role, group permissions, or external permission settings';
        $strings['DeleteUserWarning'] = 'Deleting this user will remove all of their current, future, and historical reservations.';
        $strings['AddAnnouncement'] = 'Thêm thông báo';
        $strings['Announcement'] = 'Thông Báo';
        $strings['Priority'] = 'Ưu tiên';
        $strings['Reservable'] = 'Phòng Trống';
        $strings['Unreservable'] = 'Khóa Phòng';
        $strings['Reserved'] = 'Đã đặt phòng';
        $strings['MyReservation'] = 'Lịch họp của tôi';
        $strings['Pending'] = 'Đang chờ';
        $strings['Past'] = 'TG đã qua';
        $strings['Restricted'] = 'Cấm';
        $strings['ViewAll'] = 'Xem tất cả';
        $strings['MoveResourcesAndReservations'] = 'Move resources and reservations to';
        $strings['TurnOffSubscription'] = 'Turn Off Calendar Subscriptions';
        $strings['TurnOnSubscription'] = 'Allow Subscriptions to this Calendar';
        $strings['SubscribeToCalendar'] = 'Subscribe to this Calendar';
        $strings['SubscriptionsAreDisabled'] = 'The administrator has disabled calendar subscriptions';
        $strings['NoResourceAdministratorLabel'] = '(No Resource Administrator)';
        $strings['WhoCanManageThisResource'] = 'Who Can Manage This Resource?';
        $strings['ResourceAdministrator'] = 'Resource Administrator';
        $strings['Private'] = 'Riêng Tư';
        $strings['Accept'] = 'Cho phép';
        $strings['Decline'] = 'Decline';
        $strings['ShowFullWeek'] = 'Xem lịch họp cả tuần';
        $strings['CustomAttributes'] = 'Custom Attributes';
        $strings['AddAttribute'] = 'Add an Attribute';
        $strings['EditAttribute'] = 'Update an Attribute';
        $strings['DisplayLabel'] = 'Display Label';
        $strings['Type'] = 'Type';
        $strings['Required'] = 'Required';
        $strings['ValidationExpression'] = 'Validation Expression';
        $strings['PossibleValues'] = 'Possible Values';
        $strings['SingleLineTextbox'] = 'Single Line Textbox';
        $strings['MultiLineTextbox'] = 'Multiple Line Textbox';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Select List';
        $strings['CommaSeparated'] = 'comma separated';
        $strings['Category'] = 'Category';
        $strings['CategoryReservation'] = 'Reservation';
        $strings['CategoryGroup'] = 'Group';
        $strings['SortOrder'] = 'Sort Order';
        $strings['Title'] = 'Title';
        $strings['AdditionalAttributes'] = 'Additional Attributes';
        $strings['True'] = 'True';
        $strings['False'] = 'False';
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
        $strings['ReportSaved'] = 'Báo cáo đã được lưu!';
        $strings['EmailReport'] = 'Email Report';
        $strings['ReportSent'] = 'Đã gửi báo cáo!';
        $strings['RunReport'] = 'Run Report';
        $strings['NoSavedReports'] = 'Bạn không có báo cáo nào đã lưu.';
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
        $strings['All'] = 'Tất cả các phòng:';
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
        $strings['ManageReminders'] = 'Nhắc nhở';
        $strings['ReminderUser'] = 'User ID';
        $strings['ReminderMessage'] = 'Message';
        $strings['ReminderAddress'] = 'Addresses';
        $strings['ReminderSendtime'] = 'Thời gian gửi';
        $strings['ReminderRefNumber'] = 'Reservation Reference Number';
        $strings['ReminderSendtimeDate'] = 'Date of Reminder';
        $strings['ReminderSendtimeTime'] = 'Time of Reminder (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Thêm nhắc nhở';
        $strings['DeleteReminderWarning'] = 'Are you sure you want to delete this?';
        $strings['NoReminders'] = 'You have no upcoming reminders.';
        $strings['Reminders'] = 'Reminders';
        $strings['SendReminder'] = 'Gửi nhắc nhở';
        $strings['minutes'] = 'Phút';
        $strings['hours'] = 'Giờ';
        $strings['days'] = 'Ngày';
        $strings['ReminderBeforeStart'] = 'Trước khi cuộc họp bắt đầu';
        $strings['ReminderBeforeEnd'] = 'Trước khi cuộc họp kết thúc';
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Your changes have been saved. Refresh the page for changes to take effect.';
        $strings['MakeDefaultSchedule'] = 'Make this my default schedule';
        $strings['DefaultScheduleSet'] = 'This is now your default schedule';
        $strings['FlipSchedule'] = 'Flip the schedule layout';
        $strings['Next'] = 'Next';
        $strings['Success'] = 'Thành công';
        $strings['Participant'] = 'Người tham dự';
        $strings['ResourceFilter'] = 'Tìm phòng họp theo yêu cầu';
        $strings['ResourceGroups'] = 'Resource Groups';
        $strings['AddNewGroup'] = 'Tạo một nhóm mới';
        $strings['Quit'] = 'Quit';
        $strings['AddGroup'] = 'Thêm Nhóm';
        $strings['StandardScheduleDisplay'] = 'Use the standard schedule display';
        $strings['TallScheduleDisplay'] = 'Use the tall schedule display';
        $strings['WideScheduleDisplay'] = 'Use the wide schedule display';
        $strings['CondensedWeekScheduleDisplay'] = 'Use condensed week schedule display';
        $strings['ResourceGroupHelp1'] = 'Drag and drop resource groups to reorganize.';
        $strings['ResourceGroupHelp2'] = 'Right click a resource group name for additional actions.';
        $strings['ResourceGroupHelp3'] = 'Drag and drop resources to add them to groups.';
        $strings['ResourceGroupWarning'] = 'If using resource groups, each resource must be assigned to at least one group. Unassigned resources will not be able to be reserved.';
        $strings['ResourceType'] = 'Resource Type';
        $strings['AppliesTo'] = 'Applies To';
        $strings['UniquePerInstance'] = 'Unique Per Instance';
        $strings['AddResourceType'] = 'Add Resource Type';
        $strings['NoResourceTypeLabel'] = '(no resource type set)';
        $strings['ClearFilter'] = 'Xóa tìm kiếm';
        $strings['MinimumCapacity'] = 'Chỗ ngồi tối thiểu';
        $strings['Color'] = 'Color';
        $strings['Available'] = 'Sẵn sàng';
        $strings['Unavailable'] = 'Phòng họp đang bận:';
        $strings['Hidden'] = 'Ẩn';
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
        $strings['ChangeLanguage'] = 'Thay đổi ngôn ngữ';
        $strings['AddRule'] = 'Add Rule';
        $strings['Attribute'] = 'Attribute';
        $strings['RequiredValue'] = 'Required Value';
        $strings['ReservationCustomRuleAdd'] = 'If %s then the reservation color will be';
        $strings['AddReservationColorRule'] = 'Add Reservation Color Rule';
        $strings['LimitAttributeScope'] = 'Collect In Specific Cases';
        $strings['CollectFor'] = 'Collect For';
        $strings['SignIn'] = 'Sign In';
        $strings['AllParticipants'] = 'All Participants';
        $strings['RegisterANewAccount'] = 'Register A New Account';
        $strings['Dates'] = 'Dates';
        $strings['More'] = 'More';
        $strings['ResourceAvailability'] = 'Resource Availability';
        $strings['UnavailableAllDay'] = 'Phòng họp bận cả ngày:';
        $strings['AvailableUntil'] = 'Phòng họp bận đến..';
        $strings['AvailableBeginningAt'] = 'Available Beginning At';
        $strings['AllResourceTypes'] = 'All Resource Types';
        $strings['AllResourceStatuses'] = 'All Resource Statuses';
        $strings['AllowParticipantsToJoin'] = 'Allow Participants To Join';
        $strings['Join'] = 'Join';
        $strings['YouAreAParticipant'] = 'You are a participant of this reservation';
        $strings['YouAreInvited'] = 'You are invited to this reservation';
        $strings['YouCanJoinThisReservation'] = 'Bạn có thể tham gia cuộc họp này';
        $strings['Import'] = 'Import';
        $strings['GetTemplate'] = 'Get Template';
        $strings['UserImportInstructions'] = 'File must be in CSV format. Username and email are required fields. Leaving other fields blank will set default values and \'password\' as the user\'s password. Use the supplied template as an example.';
        $strings['RowsImported'] = 'Rows Imported';
        $strings['RowsSkipped'] = 'Rows Skipped';
        $strings['Columns'] = 'Columns';
        $strings['Reserve'] = 'Đặt phòng họp';
        $strings['AllDay'] = 'Cả Ngày';
        $strings['Everyday'] = 'Hàng ngày';
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
        $strings['Guest'] = 'Khách mời';
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
        $strings['Day'] = 'Day';
        $strings['NotifyWhenAvailable'] = 'Notify Me When Available';
        $strings['AddingToWaitlist'] = 'Adding you to the wait list';
        $strings['WaitlistRequestAdded'] = 'You will be notified if this time becomes available';
        $strings['PrintQRCode'] = 'Print QR Code';
        $strings['FindATime'] = 'Find A Time';
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
        $strings['UnavailableNow'] = 'Unavailable Now';
        $strings['ReserveLater'] = 'Reserve Later';
        $strings['CollectedFor'] = 'Collected For';
        $strings['IncludeDeleted'] = 'Include Deleted Reservations';
        $strings['Deleted'] = 'Deleted';
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
        $strings['SelectUser'] = 'Select User';
        // End Install

        // Errors
        $strings['LoginError'] = 'Hệ thống không tìm thấy tên đăng nhập hoặc mật khẩu mà bạn vừa gõ';
        $strings['ReservationFailed'] = 'Your reservation could not be made';
        $strings['MinNoticeError'] = 'This reservation requires advance notice.  The earliest date and time that can be reserved is %s.';
        $strings['MaxNoticeError'] = 'This reservation cannot be made this far in the future.  The latest date and time that can be reserved is %s.';
        $strings['MinDurationError'] = 'This reservation must last at least %s.';
        $strings['MaxDurationError'] = 'This reservation cannot last longer than %s.';
        $strings['ConflictingAccessoryDates'] = 'There are not enough of the following accessories:';
        $strings['NoResourcePermission'] = 'You do not have permission to access one or more of the requested resources.';
        $strings['ConflictingReservationDates'] = 'There are conflicting reservations on the following dates:';
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
        $strings['InvalidReservationData'] = 'There were problems with your reservation request.';
        $strings['PasswordError'] = 'Password must contain at least %s letters and at least %s numbers.';
        $strings['PasswordErrorRequirements'] = 'Password must contain a combination of at least %s upper and lower case letters and %s numbers.';
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
        $strings['CreateReservation'] = 'Tạo lịch họp';
        $strings['EditReservation'] = 'Cập nhật lịch họp';
        $strings['LogIn'] = 'Đăng nhập vào hệ thống';
        $strings['ManageReservations'] = 'Reservations';
        $strings['AwaitingActivation'] = 'Awaiting Activation';
        $strings['PendingApproval'] = 'Đang chờ phê duyệt';
        $strings['ManageSchedules'] = 'Xem lịch họp đã tạo';
        $strings['ManageResources'] = 'Resources';
        $strings['ManageAccessories'] = 'Accessories';
        $strings['ManageUsers'] = 'Người dùng';
        $strings['ManageGroups'] = 'Nhóm';
        $strings['ManageQuotas'] = 'Quotas';
        $strings['ManageBlackouts'] = 'Blackout Times';
        $strings['MyDashboard'] = 'Bảng điều khiển của tôi';
        $strings['ServerSettings'] = 'Server Settings';
        $strings['Dashboard'] = 'Bảng điều khiển';
        $strings['Help'] = 'Trợ Giúp';
        $strings['Administration'] = 'Administration';
        $strings['About'] = 'Thông tin hệ thống';
        $strings['Bookings'] = 'Bookings';
        $strings['Schedule'] = 'Schedule';
        $strings['Account'] = 'Tài khoản';
        $strings['EditProfile'] = 'Sửa thông tin cá nhân của tôi';
        $strings['FindAnOpening'] = 'Find An Opening';
        $strings['OpenInvitations'] = 'Open Invitations';
        $strings['ResourceCalendar'] = 'Resource Calendar';
        $strings['Reservation'] = 'Lên lịch họp';
        $strings['Install'] = 'Installation';
        $strings['ChangePassword'] = 'Đổi mật khẩu';
        $strings['MyAccount'] = 'Tài khoản của tôi';
        $strings['Profile'] = 'Profile';
        $strings['ApplicationManagement'] = 'Application Management';
        $strings['ForgotPassword'] = 'Quên mật khẩu';
        $strings['NotificationPreferences'] = 'Notification Preferences';
        $strings['ManageAnnouncements'] = 'Thông báo !!!';
        $strings['Responsibilities'] = 'Responsibilities';
        $strings['GroupReservations'] = 'Group Reservations';
        $strings['ResourceReservations'] = 'Resource Reservations';
        $strings['Customization'] = 'Customization';
        $strings['Attributes'] = 'Attributes';
        $strings['AccountActivation'] = 'Account Activation';
        $strings['ScheduleReservations'] = 'Schedule Reservations';
        $strings['Reports'] = 'Báo cáo';
        $strings['GenerateReport'] = 'Tạo báo cáo mới';
        $strings['MySavedReports'] = 'Lưu báo cáo';
        $strings['CommonReports'] = 'Common Reports';
        $strings['ViewDay'] = 'Xem Ngày';
        $strings['Group'] = 'Nhóm';
        $strings['ManageConfiguration'] = 'Application Configuration';
        $strings['LookAndFeel'] = 'Look and Feel';
        $strings['ManageResourceGroups'] = 'Resource Groups';
        $strings['ManageResourceTypes'] = 'Resource Types';
        $strings['ManageResourceStatus'] = 'Resource Statuses';
        $strings['ReservationColors'] = 'Reservation Colors';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Chủ Nhật';
        $strings['DayMondayAbbr'] = 'Thứ 2';
        $strings['DayTuesdayAbbr'] = 'Thứ 3';
        $strings['DayWednesdayAbbr'] = 'Thứ 4';
        $strings['DayThursdayAbbr'] = 'Thứ 5';
        $strings['DayFridayAbbr'] = 'Thứ 6';
        $strings['DaySaturdayAbbr'] = 'Thứ 7';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Lịch họp của bạn đã được phê duyệt';
        $strings['ReservationCreatedSubject'] = 'Lịch họp của bạn đã được tạo';
        $strings['ReservationUpdatedSubject'] = 'Lịch họp của bạn đã được cập nhật';
        $strings['ReservationDeletedSubject'] = 'Lịch họp của bạn đã bị xóa';
        $strings['ReservationCreatedAdminSubject'] = 'Notification: A Reservation Was Created';
        $strings['ReservationUpdatedAdminSubject'] = 'Notification: A Reservation Was Updated';
        $strings['ReservationDeleteAdminSubject'] = 'Notification: A Reservation Was Removed';
        $strings['ReservationApprovalAdminSubject'] = 'Thông báo: Yêu cầu đặt phòng họp của bạn phải được phê duyệ';
        $strings['ParticipantAddedSubject'] = 'Reservation Participation Notification';
        $strings['ParticipantDeletedSubject'] = 'Lịch họp đã hủy';
        $strings['InviteeAddedSubject'] = 'Mời người tham dự họp';
        $strings['ResetPasswordRequest'] = 'Yêu cầu đổi mật khẩu';
        $strings['ActivateYourAccount'] = 'Vui lòng kích hoạt tài khoản của bạn';
        $strings['ReportSubject'] = 'Your Requested Report (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Lịch họp %s sẽ sớm được bắt đầu ';
        $strings['ReservationEndingSoonSubject'] = 'Reservation for %s is ending soon';
        $strings['UserAdded'] = 'Người sử dụng mới đã được tạo';
        $strings['UserDeleted'] = 'User account for %s was deleted by %s';
        $strings['GuestAccountCreatedSubject'] = 'Thông tin tài khoản của bạn';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Bạn không có đặt phòng nào trong quá khứ';
        $strings['PastReservations'] = 'Đặt phòng trong quá khứ';
        $strings['AllNoPastReservations'] = 'Không có đặt phòng nào trong %s ngày qua';
        $strings['AllPastReservations'] = 'Tất cả đặt phòng trong quá khứ';
        $strings['Yesterday'] = 'Hôm qua';
        $strings['EarlierThisWeek'] = 'Trước đó trong tuần này';
        $strings['PreviousWeek'] = 'Tuần trước';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Nhóm của bạn không có đặt phòng sắp tới';
        $strings['GroupUpcomingReservations'] = 'Đặt phòng sắp tới của Nhóm của tôi';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Đã xảy ra lỗi khi đăng nhập bằng Facebook. Vui lòng thử lại.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Bạn không có đặt phòng nào đang chờ duyệt';
        $strings['PendingApprovalReservations'] = 'Đặt phòng đang chờ duyệt';
        $strings['LaterThisMonth'] = 'Sau này trong tháng này';
        $strings['LaterThisYear'] = 'Sau này trong năm nay';
        $strings['Remaining'] = 'Còn lại';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Không có đặt phòng check out nào bị thiếu';
        $strings['MissingCheckOutReservations'] = 'Đặt phòng Check Out bị thiếu';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Không thể xem chi tiết đặt phòng vì bạn không có quyền truy cập vào bất kỳ nguồn tài nguyên nào trong đặt phòng này';
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
         * DAY NAMES
         * All of these arrays MUST start with Sunday as the first element
         * and go through the seven day week, ending on Saturday
         ***/
        // The full day name
        $days['full'] = ['Chủ Nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        // The three letter abbreviation
        $days['abbr'] = ['CN', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
        // The two letter abbreviation
        $days['two'] = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
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
        $months = parent::_LoadMonths();

        /***
         * MONTH NAMES
         * All of these arrays MUST start with January as the first element
         * and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
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
