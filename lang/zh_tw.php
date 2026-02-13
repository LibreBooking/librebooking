<?php

/**
*
*
* Translated by lujisheng in CSDN (Simplified Chinese)
*
* http://blog.csdn.net/lujisheng/article/details/7821538
*
* Converted and edited by mingminghome (Traditional Chinese & Simplified Chinese))
* E-mail: mingminghomework@gmail.com
*
*
* Tested on Booked Scheduler 2.4.2
*
*
*
*/
require_once('Language.php');
require_once('en_us.php');

class zh_tw extends en_us
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

        $dates['general_date'] = 'Y/m/d';
        $dates['general_datetime'] = 'Y/m/d H:i:s';
        $dates['schedule_daily'] = 'l, Y/m/d';
        $dates['reservation_email'] = 'Y/m/d @ g:i A (e)';
        $dates['res_popup'] = 'Y/m/d g:i A';
        $dates['dashboard'] = 'l, Y/m/d g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['general_date_js'] = 'yy/mm/dd';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'M/d';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = '名';
        $strings['LastName'] = '姓';
        $strings['Timezone'] = '時區';
        $strings['Edit'] = '編輯';
        $strings['Change'] = '更改';
        $strings['Rename'] = '重新命名';
        $strings['Remove'] = '移除';
        $strings['Delete'] = '刪除';
        $strings['Update'] = '更新';
        $strings['Cancel'] = '退出';
        $strings['Add'] = '增加';
        $strings['Name'] = '名稱';
        $strings['Yes'] = '是';
        $strings['No'] = '否';
        $strings['FirstNameRequired'] = '名字是必需的.';
        $strings['LastNameRequired'] = '姓氏是必需的.';
        $strings['PwMustMatch'] = '密碼必須跟前面輸入的密碼一致.';
        $strings['PwComplexity'] = '密碼必須最少6個字元，由字元、數位、符號組成.';
        $strings['ValidEmailRequired'] = '一個有效的電子郵寄地址是必需的.';
        $strings['UniqueEmailRequired'] = '這個電子郵寄地址已經被註冊過了.';
        $strings['UniqueUsernameRequired'] = '這個用戶名已經被註冊過了.';
        $strings['UserNameRequired'] = '用戶名是必需的.';
        $strings['CaptchaMustMatch'] = '請您完全按照密碼圖片上顯示的內容輸入字元.';
        $strings['Today'] = '今天';
        $strings['Week'] = '週';
        $strings['Month'] = '月';
        $strings['BackToCalendar'] = '回到日曆';
        $strings['BeginDate'] = '開始';
        $strings['EndDate'] = '結束';
        $strings['Username'] = '用戶名';
        $strings['Password'] = '密碼';
        $strings['PasswordConfirmation'] = '確認密碼';
        $strings['DefaultPage'] = '默認主頁';
        $strings['MyCalendar'] = '我的日曆';
        $strings['ScheduleCalendar'] = '計劃日曆';
        $strings['Registration'] = '註冊';
        $strings['NoAnnouncements'] = '沒有通告';
        $strings['Announcements'] = '通告';
        $strings['NoUpcomingReservations'] = '您沒有即將到來的預約';
        $strings['UpcomingReservations'] = '即將到來的預約';
        $strings['ShowHide'] = '顯示/隱藏';
        $strings['Error'] = '錯誤';
        $strings['ReturnToPreviousPage'] = '回到您剛才所在的最後一個頁面';
        $strings['UnknownError'] = '未知錯誤';
        $strings['InsufficientPermissionsError'] = '您沒有權限進入這個資源';
        $strings['MissingReservationResourceError'] = '沒有選擇資源';
        $strings['MissingReservationScheduleError'] = '沒有選擇時間表';
        $strings['DoesNotRepeat'] = '不重複';
        $strings['Daily'] = '每天';
        $strings['Weekly'] = '每週';
        $strings['Monthly'] = '每月';
        $strings['Yearly'] = '每年';
        $strings['RepeatPrompt'] = '重複';
        $strings['hours'] = '小時';
        $strings['days'] = '天';
        $strings['weeks'] = '週';
        $strings['months'] = '月';
        $strings['years'] = '年';
        $strings['day'] = '天';
        $strings['week'] = '週';
        $strings['month'] = '月';
        $strings['year'] = '年';
        $strings['repeatDayOfMonth'] = '天/月';
        $strings['repeatDayOfWeek'] = '天/週';
        $strings['RepeatUntilPrompt'] = '直到';
        $strings['RepeatEveryPrompt'] = '每';
        $strings['RepeatDaysPrompt'] = '在';
        $strings['CreateReservationHeading'] = '建立一個預約';
        $strings['EditReservationHeading'] = '編輯預約 %s';
        $strings['ViewReservationHeading'] = '瀏覽預約 %s';
        $strings['ReservationErrors'] = '更改預約';
        $strings['Create'] = '建立';
        $strings['ThisInstance'] = '只此一次';
        $strings['AllInstances'] = '所有情形';
        $strings['FutureInstances'] = '將來情形';
        $strings['Print'] = '列印';
        $strings['ShowHideNavigation'] = '顯示/隱藏 導航';
        $strings['ReferenceNumber'] = '參考數字';
        $strings['Tomorrow'] = '明天';
        $strings['LaterThisWeek'] = '本周後段時間';
        $strings['NextWeek'] = '下一周';
        $strings['SignOut'] = '登出';
        $strings['LayoutDescription'] = '自 %s 起，一次顯示 %s 天';
        $strings['AllResources'] = '所有資源';
        $strings['TakeOffline'] = 'Take 離線';
        $strings['BringOnline'] = 'Bring 線上';
        $strings['AddImage'] = '添加圖片';
        $strings['NoImage'] = '沒有分派圖片';
        $strings['Move'] = '更改';
        $strings['AppearsOn'] = '根據 %s 顯示';
        $strings['Location'] = '地區';
        $strings['NoLocationLabel'] = '(沒有指定地區)';
        $strings['Contact'] = '聯繫';
        $strings['NoContactLabel'] = '(沒有聯繫方式)';
        $strings['Description'] = '說明';
        $strings['NoDescriptionLabel'] = '(沒有說明)';
        $strings['Notes'] = '筆記';
        $strings['NoNotesLabel'] = '(沒有筆記)';
        $strings['NoTitleLabel'] = '(沒有標題)';
        $strings['UsageConfiguration'] = '使用配置';
        $strings['ChangeConfiguration'] = '改變配置';
        $strings['ResourceMinLength'] = '預約必須持續至少 %s';
        $strings['ResourceMinLengthNone'] = '這裡沒有最低預約時間';
        $strings['ResourceMaxLength'] = '預約不能延長超過 %s';
        $strings['ResourceMaxLengthNone'] = '這裡沒有最多預約時間';
        $strings['ResourceRequiresApproval'] = '預約必須得到批准';
        $strings['ResourceRequiresApprovalNone'] = '預約不需要審批';
        $strings['ResourcePermissionAutoGranted'] = '自動授予權限';
        $strings['ResourcePermissionNotAutoGranted'] = '沒有自動獲得許可';
        $strings['ResourceMinNotice'] = '必須在開始時間前至少 %s 完成預約';
        $strings['ResourceMinNoticeNone'] = '到當前時間均可以預約';
        $strings['ResourceMaxNotice'] = '預約不能在當前時間 %s 後前結束';
        $strings['ResourceMaxNoticeNone'] = '預約可以在將來任意時間點結束';
        $strings['ResourceAllowMultiDay'] = '可以跨日期預約';
        $strings['ResourceNotAllowMultiDay'] = '不能跨日期預約';
        $strings['ResourceCapacity'] = '這個資源可以容納 %s 人';
        $strings['ResourceCapacityNone'] = '這個資源有無限容納能力';
        $strings['AddNewResource'] = '添加新的資源';
        $strings['AddNewUser'] = '添加新的用戶';
        $strings['AddUser'] = '添加用戶';
        $strings['Schedule'] = '時間表';
        $strings['AddResource'] = '增加資源';
        $strings['Capacity'] = '容量';
        $strings['Access'] = '進入';
        $strings['Duration'] = '持續時間';
        $strings['Active'] = '啟動';
        $strings['Inactive'] = '未啟動';
        $strings['ResetPassword'] = '重設密碼';
        $strings['LastLogin'] = '上次登入';
        $strings['Search'] = '搜尋';
        $strings['ResourcePermissions'] = '資源許可';
        $strings['Reservations'] = '預約';
        $strings['Groups'] = '群組';
        $strings['ResetPassword'] = '重設密碼';
        $strings['AllUsers'] = '所有用戶';
        $strings['AllGroups'] = '所有群組';
        $strings['AllSchedules'] = '所有時間表';
        $strings['UsernameOrEmail'] = '用戶名或電子郵件';
        $strings['Members'] = '成員';
        $strings['QuickSlotCreation'] = '每 %s 分鐘建立時間間隔 (在 %s 和 %s 之間)';
        $strings['ApplyUpdatesTo'] = '申請更新';
        $strings['CancelParticipation'] = '取消參與';
        $strings['Attending'] = '參與';
        $strings['QuotaConfiguration'] = '在 %s 只有 %s 用戶來自 %s 可在去 %s %s 每 %s';
        $strings['reservations'] = '預約';
        $strings['ChangeCalendar'] = '更改日曆';
        $strings['AddQuota'] = '增加配額';
        $strings['FindUser'] = '搜尋用戶';
        $strings['Created'] = '已建立';
        $strings['LastModified'] = '最後更新的';
        $strings['GroupName'] = '群組名稱';
        $strings['GroupMembers'] = '群組成員';
        $strings['GroupRoles'] = '群組權限';
        $strings['GroupAdmin'] = '群組管理員';
        $strings['Actions'] = '活動';
        $strings['CurrentPassword'] = '當前密碼';
        $strings['NewPassword'] = '新密碼';
        $strings['InvalidPassword'] = '當前密碼不正確';
        $strings['PasswordChangedSuccessfully'] = '您的密碼已成功更新';
        $strings['SignedInAs'] = '登入為';
        $strings['NotSignedIn'] = '您還未登入';
        $strings['ReservationTitle'] = '預約名稱';
        $strings['ReservationDescription'] = '預約說明';
        $strings['ResourceList'] = '待預約資源';
        $strings['Accessories'] = '設備/附件';
        $strings['ParticipantList'] = '參與者';
        $strings['InvitationList'] = '受邀請者';
        $strings['AccessoryName'] = '附件名稱';
        $strings['QuantityAvailable'] = '可用數量';
        $strings['Resources'] = '資源';
        $strings['Participants'] = '參與者';
        $strings['User'] = '用戶';
        $strings['Resource'] = '資源';
        $strings['Status'] = '狀態';
        $strings['Approve'] = '審批';
        $strings['Page'] = '頁';
        $strings['Rows'] = '行';
        $strings['Unlimited'] = '無限的';
        $strings['Email'] = '電子郵件';
        $strings['EmailAddress'] = '電子郵寄地址';
        $strings['Phone'] = '電話';
        $strings['Organization'] = '組織';
        $strings['Position'] = '位置';
        $strings['Language'] = '語言';
        $strings['Permissions'] = '許可';
        $strings['Reset'] = '重置';
        $strings['FindGroup'] = '搜尋群組';
        $strings['Manage'] = '管理';
        $strings['None'] = '無';
        $strings['AddToOutlook'] = '添加到日曆';
        $strings['Done'] = '完成';
        $strings['RememberMe'] = '記住我';
        $strings['FirstTimeUser?'] = '您是首次來訪用戶?';
        $strings['CreateAnAccount'] = '建立一個帳號';
        $strings['ViewSchedule'] = '查看時間表';
        $strings['ForgotMyPassword'] = '忘記密碼';
        $strings['YouWillBeEmailedANewPassword'] = '您將會收到一份系統自動生成的密碼';
        $strings['Close'] = '關閉';
        $strings['ExportToCSV'] = '匯出為 CSV 文件';
        $strings['OK'] = '同意';
        $strings['Working'] = '工作中';
        $strings['Login'] = '登入';
        $strings['AdditionalInformation'] = '附加資訊';
        $strings['AllFieldsAreRequired'] = '所有欄位都需要填寫';
        $strings['Optional'] = '選填內容';
        $strings['YourProfileWasUpdated'] = '您的個人資料已經更新';
        $strings['YourSettingsWereUpdated'] = '您的設置已經上傳';
        $strings['Register'] = '註冊';
        $strings['SecurityCode'] = '安全碼';
        $strings['ReservationCreatedPreference'] = '當我建立一個預約 或 一個預約已經為我建立';
        $strings['ReservationUpdatedPreference'] = '當我建立一個預約 或 一個預約已經為我更新';
        $strings['ReservationDeletedPreference'] = '當我刪除一個預約 或 一個預約已經為我刪除';
        $strings['ReservationApprovalPreference'] = '當我的待審批預約獲得批准';
        $strings['PreferenceSendEmail'] = '電郵給我';
        $strings['PreferenceNoEmail'] = '不用提醒我';
        $strings['ReservationCreated'] = '您的預約已成功建立！';
        $strings['ReservationUpdated'] = '您的預約已成功更新！';
        $strings['ReservationRemoved'] = '您的預約已刪除';
        $strings['YourReferenceNumber'] = '您的參考數字是 %s';
        $strings['UpdatingReservation'] = '更新預約';
        $strings['ChangeUser'] = '更改用戶';
        $strings['MoreResources'] = '更多的資源';
        $strings['ReservationLength'] = '預約長度';
        $strings['ParticipantList'] = '參與者名單';
        $strings['AddParticipants'] = '添加';
        $strings['InviteOthers'] = '邀請他人';
        $strings['AddResources'] = '添加資源';
        $strings['AddAccessories'] = '添加設備/附件';
        $strings['Accessory'] = '附件';
        $strings['QuantityRequested'] = '請求數';
        $strings['CreatingReservation'] = '建立預約';
        $strings['UpdatingReservation'] = '更新預約';
        $strings['DeleteWarning'] = '這一行動是永久性的，不可更改！ ';
        $strings['DeleteAccessoryWarning'] = '刪除這個附件將會導致它在所有預約中被刪除.';
        $strings['AddAccessory'] = '添加附件';
        $strings['AddBlackout'] = '添加管制';
        $strings['AllResourcesOn'] = '全部資源在';
        $strings['Reason'] = '理由';
        $strings['BlackoutShowMe'] = '顯示有衝突的預約';
        $strings['BlackoutDeleteConflicts'] = '刪除有衝突的預約';
        $strings['Filter'] = '篩選';
        $strings['Between'] = '在此期間';
        $strings['CreatedBy'] = '建立者';
        $strings['BlackoutCreated'] = '管制時間已經建立！';
        $strings['BlackoutNotCreated'] = '不能建立管制時間';
        $strings['BlackoutConflicts'] = '存在有衝突的管制時間';
        $strings['ReservationConflicts'] = '存在有衝突的預約時間';
        $strings['UsersInGroup'] = '這個群組裡的成員';
        $strings['Browse'] = '瀏覽';
        $strings['DeleteGroupWarning'] = '刪除這個群組會移除所有關聯的資源許可，組內成員都將擁有相關資源的權限.';
        $strings['WhatRolesApplyToThisGroup'] = '哪些角色適用於本群組?';
        $strings['WhoCanManageThisGroup'] = '誰能管理這個群組?';
        $strings['WhoCanManageThisSchedule'] = '誰能管理這時間表?';
        $strings['AddGroup'] = '添加群組';
        $strings['AllQuotas'] = '全部配額';
        $strings['QuotaReminder'] = '請記住：配額是強制性地基於已計劃的時間分區的.';
        $strings['AllReservations'] = '全部預約';
        $strings['PendingReservations'] = '待審核預約';
        $strings['Approving'] = '審核中';
        $strings['MoveToSchedule'] = '移動到時間表';
        $strings['DeleteResourceWarning'] = '刪除這個資源會刪除所有關聯的資料，包括';
        $strings['DeleteResourceWarningReservations'] = '所有過去的、現在的和將來的預約所關聯的.';
        $strings['DeleteResourceWarningPermissions'] = '全部權限分配';
        $strings['DeleteResourceWarningReassign'] = '請您在繼續之前把您不想刪除的任何內容再一次分配.';
        $strings['ScheduleLayout'] = '規劃(全時段 %s)';
        $strings['ReservableTimeSlots'] = '預約的時間間隔';
        $strings['BlockedTimeSlots'] = '管制的時間間隔';
        $strings['ThisIsTheDefaultSchedule'] = '這是默認的時間表';
        $strings['DefaultScheduleCannotBeDeleted'] = '默認時間表不能被刪除';
        $strings['MakeDefault'] = '設為默認的';
        $strings['BringDown'] = '下調';
        $strings['ChangeLayout'] = '改變規劃';
        $strings['AddSchedule'] = '增加時間表';
        $strings['StartsOn'] = '開始於';
        $strings['NumberOfDaysVisible'] = '顯示天數';
        $strings['UseSameLayoutAs'] = '使用相同規劃為';
        $strings['Format'] = '格式';
        $strings['OptionalLabel'] = '可選符號';
        $strings['LayoutInstructions'] = '每行輸入一個時間間隔.時間間隔必須能提供全部的24小時而且開始和結束於上午12：00.';
        $strings['AddUser'] = '添加用戶';
        $strings['UserPermissionInfo'] = '實際進入資源可能會因角色、群組許可或其他許可設定而有所不同.';
        $strings['DeleteUserWarning'] = '刪除用戶將會刪除與他們有關的所有過去的、現在的、將來的預約.';
        $strings['AddAnnouncement'] = '添加通告';
        $strings['Announcement'] = '通告';
        $strings['Priority'] = '優先';
        $strings['Reservable'] = '可預約的';
        $strings['Unreservable'] = '不可預約的';
        $strings['Reserved'] = '已預約';
        $strings['MyReservation'] = '我的預約';
        $strings['Pending'] = '待審批';
        $strings['Past'] = '以前的';
        $strings['Restricted'] = '受限的';
        $strings['ViewAll'] = '查看全部';
        $strings['MoveResourcesAndReservations'] = '移動資源和預約到';
        $strings['TurnOffSubscription'] = '關閉日曆訂閱';
        $strings['TurnOnSubscription'] = '允許訂閱此日曆';
        $strings['SubscribeToCalendar'] = '訂閱此日曆';
        $strings['SubscriptionsAreDisabled'] = '管理員已經禁止訂閱此日曆';
        $strings['NoResourceAdministratorLabel'] = '(沒有資源管理員)';
        $strings['WhoCanManageThisResource'] = '誰能管理這項資源?';
        $strings['ResourceAdministrator'] = '資源管理員';
        $strings['Private'] = '私有的';
        $strings['Accept'] = '接受';
        $strings['Decline'] = '拒絕';
        $strings['ShowFullWeek'] = '顯示全部一週';
        $strings['CustomAttributes'] = '自訂屬性';
        $strings['AddAttribute'] = '添加一個屬性';
        $strings['EditAttribute'] = '更新一個屬性';
        $strings['DisplayLabel'] = '顯示符號';
        $strings['Type'] = '類型';
        $strings['Required'] = '需要';
        $strings['ValidationExpression'] = '驗證運算式';
        $strings['PossibleValues'] = '可能的值';
        $strings['SingleLineTextbox'] = '單行文字方塊';
        $strings['MultiLineTextbox'] = '多行文字方塊';
        $strings['Checkbox'] = '檢查框';
        $strings['SelectList'] = '選擇列表';
        $strings['CommaSeparated'] = '逗號分隔';
        $strings['Category'] = '類別';
        $strings['CategoryReservation'] = '預約';
        $strings['CategoryGroup'] = '群組';
        $strings['SortOrder'] = '排序方式';
        $strings['Title'] = '標題';
        $strings['AdditionalAttributes'] = '附加屬性';
        $strings['True'] = '是';
        $strings['False'] = '否';
        $strings['ForgotPasswordEmailSent'] = '一封包含重設密碼提示的電子郵件已經發往您提供的的電子郵寄地址';
        $strings['ActivationEmailSent'] = '您會立即收到一封有關啟動的電子郵件.';
        $strings['AccountActivationError'] = '對不起，我們不能啟動您的帳號.';
        $strings['Attachments'] = '附件';
        $strings['AttachFile'] = '附件檔';
        $strings['Maximum'] = '最大值';
        $strings['NoScheduleAdministratorLabel'] = '沒有時間表管理員';
        $strings['ScheduleAdministrator'] = '時間表管理員';
        $strings['Total'] = '總數';
        $strings['QuantityReserved'] = '已預約數量';
        $strings['AllAccessories'] = '所有設備/附件';
        $strings['GetReport'] = '取得報告';
        $strings['NoResultsFound'] = '沒有符合的結果';
        $strings['SaveThisReport'] = '儲存這報告';
        $strings['ReportSaved'] = '報告已儲存!';
        $strings['EmailReport'] = '以電郵寄出報告';
        $strings['ReportSent'] = '報告已寄出!';
        $strings['RunReport'] = '導出報告';
        $strings['NoSavedReports'] = '你沒有已儲存的報告.';
        $strings['CurrentWeek'] = '本週';
        $strings['CurrentMonth'] = '本月';
        $strings['AllTime'] = '全部時間';
        $strings['FilterBy'] = '以..過濾';
        $strings['Select'] = '選擇';
        $strings['List'] = '以 列';
        $strings['TotalTime'] = '以 總時間';
        $strings['Count'] = '以 數目';
        $strings['Usage'] = '使用情況';
        $strings['AggregateBy'] = '合計以';
        $strings['Range'] = '範圍';
        $strings['Choose'] = '選擇';
        $strings['All'] = '所有';
        $strings['ViewAsChart'] = '以圖表觀看';
        $strings['ReservedResources'] = '已預約的資源';
        $strings['ReservedAccessories'] = '已預約的設備/附件';
        $strings['ResourceUsageTimeBooked'] = '資源使用情況 - 預訂時間';
        $strings['ResourceUsageReservationCount'] = '資源使用情況 - 預約次數';
        $strings['Top20UsersTimeBooked'] = '二十高使用量使用者 - 預訂時間';
        $strings['Top20UsersReservationCount'] = '二十高使用量使用者 - 預約次數';
        $strings['ConfigurationUpdated'] = '設定檔已更新';
        $strings['ConfigurationUiNotEnabled'] = '這個頁面不能訪問，因為$conf[\'settings\'][\'pages\'][\'enable.configuration\'] 設置為「否」或「欠缺」。';
        $strings['ConfigurationFileNotWritable'] = '設定檔不能寫入。請檢查設定檔的權限後再嘗試。';
        $strings['ConfigurationUpdateHelp'] = '有關配置請參閱配置部份 <a target=_blank href=%s>說明</a>';
        $strings['GeneralConfigSettings'] = '配置';
        $strings['UseSameLayoutForAllDays'] = '每日使用同樣的設定';
        $strings['LayoutVariesByDay'] = '自訂每日的設定';
        $strings['ManageReminders'] = '提醒';
        $strings['ReminderUser'] = '用戶名';
        $strings['ReminderMessage'] = '訊息';
        $strings['ReminderAddress'] = '地址';
        $strings['ReminderSendtime'] = '寄出時間';
        $strings['ReminderRefNumber'] = '預約參考編號';
        $strings['ReminderSendtimeDate'] = '提醒日期';
        $strings['ReminderSendtimeTime'] = '提醒時間 (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = '早上 / 下午';
        $strings['AddReminder'] = '新增提醒';
        $strings['DeleteReminderWarning'] = '確定？';
        $strings['NoReminders'] = '你有沒有即將到來的提醒';
        $strings['Reminders'] = '提醒';
        $strings['SendReminder'] = '發出提醒';
        $strings['minutes'] = '分';
        $strings['hours'] = '小時';
        $strings['days'] = '日';
        $strings['ReminderBeforeStart'] = '開始時間之前';
        $strings['ReminderBeforeEnd'] = '結束時間之前';
        $strings['Logo'] = '圖示';
        $strings['CssFile'] = 'CSS 檔案';
        $strings['ThemeUploadSuccess'] = '您的變更已保存。刷新頁面變更才會生效。';
        $strings['MakeDefaultSchedule'] = '設定為預設的時間表';
        $strings['DefaultScheduleSet'] = '這是你預設的時間表';
        $strings['FlipSchedule'] = '翻轉的時間表排版';
        $strings['Next'] = '下一個';
        $strings['Success'] = '成功';
        $strings['Participant'] = '參與者';
        // End Strings

        // Install
        $strings['InstallApplication'] = '安裝 phpScheduleIt (只限 MySQL)';
        $strings['IncorrectInstallPassword'] = '對不起！密碼不正確。';
        $strings['SetInstallPassword'] = '在執行安裝前，您必須設置安裝密碼。';
        $strings['InstallPasswordInstructions'] = '在 %s 請將 %s 的密碼設定為隨機的及難以猜測，然後返回到這個頁面。<BR/>您可以用 %s 的';
        $strings['NoUpgradeNeeded'] = '有沒有升級的需要。執行安裝過程中會刪除所有現有數據，並安裝一個全新的phpScheduleIt！';
        $strings['ProvideInstallPassword'] = '請提供安裝密碼';
        $strings['InstallPasswordLocation'] = '安裝密碼可以在 %s 內 %s 找到';
        $strings['VerifyInstallSettings'] = '進入下一步前，請驗證以下預設設置。或者，你可以在 %s 改變他們。';
        $strings['DatabaseName'] = '數據庫名稱';
        $strings['DatabaseUser'] = '數據庫用戶';
        $strings['DatabaseHost'] = '數據庫主機';
        $strings['DatabaseCredentials'] = '你必須提供一個有創建數據庫權限的MySQL用戶。如果你不知道，請聯繫您的數據庫管理員。在一般情況下，可使用 Root 用戶';
        $strings['MySQLUser'] = 'MySQL 用戶';
        $strings['InstallOptionsWarning'] = '下列選項可能不適合用於託管環境 (hosted environment) 。如果您要安裝在託管環境 (hosted environment) 中，請使用MySQL的嚮導工具來完成這些步驟。';
        $strings['CreateDatabase'] = '創建數據庫';
        $strings['CreateDatabaseUser'] = '創建數據庫用戶';
        $strings['PopulateExampleData'] = '導入示例數據。創建管理員帳號：admin/password 和 用戶帳號：user/password';
        $strings['DataWipeWarning'] = '警告：這將刪除任何現有的數據';
        $strings['RunInstallation'] = '執行安裝';
        $strings['UpgradeNotice'] = '你從 <b>%s</b> 版本升級到 <b>%s</b> 版本';
        $strings['RunUpgrade'] = '進行升級';
        $strings['Executing'] = '執行中';
        $strings['StatementFailed'] = '失敗。詳細信息：';
        $strings['SQLStatement'] = 'SQL 語句：';
        $strings['ErrorCode'] = '錯誤代碼:';
        $strings['ErrorText'] = '錯誤訊息:';
        $strings['InstallationSuccess'] = '安裝完成';
        $strings['RegisterAdminUser'] = '註冊您的管理員用戶。這是必需的，如果你沒有導入示例數據。請確保 $conf[\'settings\'][\'allow.self.registration\'] = \'true\' 在 %s 檔案';
        $strings['LoginWithSampleAccounts'] = '如果有導入示例數據，您可以登錄 admin/password 為管理員用戶或者 user/password 為普通用戶';
        $strings['InstalledVersion'] = '您現在正在運行 %s 版本的phpScheduleIt';
        $strings['InstallUpgradeConfig'] = '建議升級您的配置文件';
        $strings['InstallationFailure'] = '安裝過程出現問題。請予以更正，並重新安裝。';
        $strings['ConfigureApplication'] = '配置 phpScheduleIt';
        $strings['ConfigUpdateSuccess'] = '你的設定檔目前是最新的';
        $strings['ConfigUpdateFailure'] = '我們不能自動更新你的設定檔。請使用下列的內容覆蓋config.php：';
        // End Install

        // Errors
        $strings['LoginError'] = '用戶名和密碼不符合';
        $strings['ReservationFailed'] = '沒辦法建立您的預約';
        $strings['MinNoticeError'] = '這份預約需要高級提示.最早能提供的日期和時間是 %s.';
        $strings['MaxNoticeError'] = '這份預約不能在以後進行.最遲能被預約的日期和時間是 %s.';
        $strings['MinDurationError'] = '這份預約必須延長到 %s.';
        $strings['MaxDurationError'] = '這份預約不能延長到 %s.';
        $strings['ConflictingAccessoryDates'] = '沒有足夠的附件：';
        $strings['NoResourcePermission'] = '您沒有權限訪問一個或一個以上的請求.';
        $strings['ConflictingReservationDates'] = '在接下來的日子裡存在有衝突的預約:';
        $strings['StartDateBeforeEndDateRule'] = '開始時間和日期必須早於結束時間和日期';
        $strings['StartIsInPast'] = '開始時間和日期必須比當前時間晚';
        $strings['EmailDisabled'] = '管理員已經禁止了郵件提醒';
        $strings['ValidLayoutRequired'] = '時間間隔必須提供全天24小時而且必須從上午12時開始並結束於上午12時.';
        $strings['CustomAttributeErrors'] = '您提供的附加屬性出了問題:';
        $strings['CustomAttributeRequired'] = '%s 是必填項目';
        $strings['CustomAttributeInvalid'] = '提供給 %s 的內容是有效的.';
        $strings['AttachmentLoadingError'] = '對不起，在載入檔時發現了一個問題.';
        $strings['InvalidAttachmentExtension'] = '您只能上傳這些類型的檔: %s';
        $strings['InvalidStartSlot'] = '開始時間和日期不正確';
        $strings['InvalidEndSlot'] = '結束時間和日期不正確';
        $strings['MaxParticipantsError'] = '%s 只可以支援 %s 參與者';
        $strings['ReservationCriticalError'] = '有一個嚴重的錯誤影響您的預訂。如果這種情況持續下去，您的系統管理員聯繫。';
        $strings['InvalidStartReminderTime'] = '開始提醒時間不正確';
        $strings['InvalidEndReminderTime'] = '結束提醒時間不正確';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = '建立預約';
        $strings['EditReservation'] = '編輯預約';
        $strings['LogIn'] = '登入';
        $strings['ManageReservations'] = '預約';
        $strings['AwaitingActivation'] = '等待啟動';
        $strings['PendingApproval'] = '待審批中';
        $strings['ManageSchedules'] = '時間表';
        $strings['ManageResources'] = '資源';
        $strings['ManageAccessories'] = '設備/附件';
        $strings['ManageUsers'] = '用戶';
        $strings['ManageGroups'] = '群組';
        $strings['ManageQuotas'] = '配額';
        $strings['ManageBlackouts'] = '管制時間';
        $strings['MyDashboard'] = '我的控制板';
        $strings['ServerSettings'] = '伺服器設置';
        $strings['Dashboard'] = '控制台';
        $strings['Help'] = '幫助';
        $strings['Administration'] = '管理';
        $strings['About'] = '關於';
        $strings['Bookings'] = '預訂';
        $strings['Schedule'] = '時間表';
        $strings['Reservations'] = '預約';
        $strings['Account'] = '帳號';
        $strings['EditProfile'] = '編輯個人編纂';
        $strings['FindAnOpening'] = '找到一個開放的';
        $strings['OpenInvitations'] = '開放的邀請';
        $strings['MyCalendar'] = '我的日曆';
        $strings['ResourceCalendar'] = '資源日曆';
        $strings['Reservation'] = '新預約';
        $strings['Install'] = '安裝';
        $strings['ChangePassword'] = '改變密碼';
        $strings['MyAccount'] = '我的帳號';
        $strings['Profile'] = '個人資訊';
        $strings['ApplicationManagement'] = '程式管理';
        $strings['ForgotPassword'] = '忘了密碼';
        $strings['NotificationPreferences'] = '通知設定';
        $strings['ManageAnnouncements'] = '通告';
        $strings['Responsibilities'] = '主要職責';
        $strings['GroupReservations'] = '群組預約';
        $strings['ResourceReservations'] = '預約資源';
        $strings['Customization'] = '自訂';
        $strings['Attributes'] = '屬性';
        $strings['AccountActivation'] = '帳號啟動';
        $strings['ScheduleReservations'] = '計劃預約';
        $strings['Reports'] = '報告';
        $strings['GenerateReport'] = '建立一個新報告';
        $strings['MySavedReports'] = '我已儲存的報告';
        $strings['CommonReports'] = '共享的報告';
        $strings['ViewDay'] = '觀看日期';
        $strings['Group'] = '群組';
        $strings['ManageConfiguration'] = '系統設定';
        $strings['LookAndFeel'] = '佈景設定';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = '周日';
        $strings['DayMondayAbbr'] = '週一';
        $strings['DayTuesdayAbbr'] = '週二';
        $strings['DayWednesdayAbbr'] = '週三';
        $strings['DayThursdayAbbr'] = '週四';
        $strings['DayFridayAbbr'] = '週五';
        $strings['DaySaturdayAbbr'] = '週六';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = '您的帳號已經啟動';
        $strings['ReservationCreatedSubject'] = '您的預約已經建立';
        $strings['ReservationUpdatedSubject'] = '您的預約已經更新';
        $strings['ReservationDeletedSubject'] = '您的預約已經移除';
        $strings['ReservationCreatedAdminSubject'] = '提示：一個預約已經建立';
        $strings['ReservationUpdatedAdminSubject'] = '提示：一個預約已經更新';
        $strings['ReservationDeleteAdminSubject'] = '提示：一個預約已經移除';
        $strings['ParticipantAddedSubject'] = '預約參與通知';
        $strings['ParticipantDeletedSubject'] = '已移除的預約';
        $strings['InviteeAddedSubject'] = '預約邀請';
        $strings['ResetPassword'] = '重置密碼申請';
        $strings['ActivateYourAccount'] = '請啟動您的帳號';
        $strings['ReportSubject'] = '您要求的報告 (%s)';
        $strings['ReservationStartingSoonSubject'] = '%s 的預約即將開始';
        $strings['ReservationEndingSoonSubject'] = '%s 的預約即將結束';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = '您沒有以前的預訂';
        $strings['PastReservations'] = '以前的預訂';
        $strings['AllNoPastReservations'] = '在過去的%s天內沒有以前的預訂';
        $strings['AllPastReservations'] = '所有以前的預訂';
        $strings['Yesterday'] = '昨天';
        $strings['EarlierThisWeek'] = '本週早些時候';
        $strings['PreviousWeek'] = '上週';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = '您的團體沒有即將到來的預訂';
        $strings['GroupUpcomingReservations'] = '我的團體即將到來的預訂';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = '使用Facebook登入時發生錯誤。請重試。';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = '您沒有等待審批的預訂';
        $strings['PendingApprovalReservations'] = '等待審批的預訂';
        $strings['LaterThisMonth'] = '本月晚些時候';
        $strings['LaterThisYear'] = '本年晚些時候';
        $strings['Remaining'] = '剩餘';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = '沒有遺失的退房預訂';
        $strings['MissingCheckOutReservations'] = '遺失的退房預訂';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = '由於您在此預訂中的任何資源都沒有權限，因此無法查看預訂詳細信息';
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
        $days['full'] = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
        // The three letter abbreviation
        $days['abbr'] = ['日', '一', '二', '三', '四', '五', '六'];
        // The two letter abbreviation
        $days['two'] = ['日', '一', '二', '三', '四', '五', '六'];
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
        MONTH NAMES
        All of these arrays MUST start with January as the first element
        and go through the twelve months of the year, ending on December
         ***/
        // The full month name
        $months['full'] = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
        // The three letter month name
        $months['abbr'] = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'];

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
        return 'zh_tw';
    }
}
