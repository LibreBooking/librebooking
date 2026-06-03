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
* Tested on Booked Scheduler 2.3
*
*
*
*/

require_once('Language.php');
require_once('en_us.php');

class zh_cn extends en_us
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
        $strings['Timezone'] = '时区';
        $strings['Edit'] = '编辑';
        $strings['Change'] = '更改';
        $strings['Rename'] = '重新命名';
        $strings['Remove'] = '移除';
        $strings['Delete'] = '删除';
        $strings['Update'] = '更新';
        $strings['Cancel'] = '退出';
        $strings['Add'] = '增加';
        $strings['Name'] = '名称';
        $strings['Yes'] = '是';
        $strings['No'] = '否';
        $strings['FirstNameRequired'] = '名字是必需的.';
        $strings['LastNameRequired'] = '姓氏是必需的.';
        $strings['PwMustMatch'] = '密码必须跟前面输入的密码一致.';
        $strings['PwComplexity'] = '密码必须最少6个字符，由字符、数字、符号组成.';
        $strings['ValidEmailRequired'] = '一个有效的电子邮件地址是必需的.';
        $strings['UniqueEmailRequired'] = '这个电子邮件地址已经被注册过了.';
        $strings['UniqueUsernameRequired'] = '这个用户名已经被注册过了.';
        $strings['UserNameRequired'] = '用户名是必需的.';
        $strings['CaptchaMustMatch'] = '请您完全按照密码图片上显示的内容输入字符.';
        $strings['Today'] = '今天';
        $strings['Week'] = '周';
        $strings['Month'] = '月';
        $strings['BackToCalendar'] = '回到日历';
        $strings['BeginDate'] = '开始';
        $strings['EndDate'] = '结束';
        $strings['Username'] = '用户名';
        $strings['Password'] = '密码';
        $strings['PasswordConfirmation'] = '确认密码';
        $strings['DefaultPage'] = '默认主页';
        $strings['MyCalendar'] = '我的日历';
        $strings['ScheduleCalendar'] = '计划日历';
        $strings['Registration'] = '注册';
        $strings['NoAnnouncements'] = '没有通告';
        $strings['Announcements'] = '通告';
        $strings['NoUpcomingReservations'] = '您没有即将到来的预约';
        $strings['UpcomingReservations'] = '即将到来的预约';
        $strings['ShowHide'] = '显示/隐藏';
        $strings['Error'] = '错误';
        $strings['ReturnToPreviousPage'] = '回到您刚才所在的最后一个页面';
        $strings['UnknownError'] = '未知错误';
        $strings['InsufficientPermissionsError'] = '您没有权限进入这个资源';
        $strings['MissingReservationResourceError'] = '没有选择资源';
        $strings['MissingReservationScheduleError'] = '没有选择时间表';
        $strings['DoesNotRepeat'] = '不重复';
        $strings['Daily'] = '每天';
        $strings['Weekly'] = '每周';
        $strings['Monthly'] = '每月';
        $strings['Yearly'] = '每年';
        $strings['RepeatPrompt'] = '重复';
        $strings['hours'] = '小时';
        $strings['days'] = '天';
        $strings['weeks'] = '周';
        $strings['months'] = '月';
        $strings['years'] = '年';
        $strings['day'] = '天';
        $strings['week'] = '周';
        $strings['month'] = '月';
        $strings['year'] = '年';
        $strings['repeatDayOfMonth'] = '天/月';
        $strings['repeatDayOfWeek'] = '天/周';
        $strings['RepeatUntilPrompt'] = '直到';
        $strings['RepeatEveryPrompt'] = '每';
        $strings['RepeatDaysPrompt'] = '在';
        $strings['CreateReservationHeading'] = '建立一个预约';
        $strings['EditReservationHeading'] = '编辑预约 %s';
        $strings['ViewReservationHeading'] = '浏览预约 %s';
        $strings['ReservationErrors'] = '更改预约';
        $strings['Create'] = '建立';
        $strings['ThisInstance'] = '只此一次';
        $strings['AllInstances'] = '所有情形';
        $strings['FutureInstances'] = '将来情形';
        $strings['Print'] = '打印';
        $strings['ShowHideNavigation'] = '显示/隐藏 导航';
        $strings['ReferenceNumber'] = '参考数字';
        $strings['Tomorrow'] = '明天';
        $strings['LaterThisWeek'] = '本周后段时间';
        $strings['NextWeek'] = '下一周';
        $strings['SignOut'] = '注销';
        $strings['LayoutDescription'] = '自 %s 起，一次显示 %s 天';
        $strings['AllResources'] = '所有资源';
        $strings['TakeOffline'] = 'Take 脱机';
        $strings['BringOnline'] = 'Bring 在线';
        $strings['AddImage'] = '添加图片';
        $strings['NoImage'] = '没有分派图片';
        $strings['Move'] = '更改';
        $strings['AppearsOn'] = '根据 %s 显示';
        $strings['Location'] = '地区';
        $strings['NoLocationLabel'] = '(没有指定地区)';
        $strings['Contact'] = '联系';
        $strings['NoContactLabel'] = '(没有联系方式)';
        $strings['Description'] = '说明';
        $strings['NoDescriptionLabel'] = '(没有说明)';
        $strings['Notes'] = '笔记';
        $strings['NoNotesLabel'] = '(没有笔记)';
        $strings['NoTitleLabel'] = '(没有标题)';
        $strings['UsageConfiguration'] = '使用配置';
        $strings['ChangeConfiguration'] = '改变配置';
        $strings['ResourceMinLength'] = '预约必须持续至少 %s';
        $strings['ResourceMinLengthNone'] = '这里没有最低预约时间';
        $strings['ResourceMaxLength'] = '预约不能延长超过 %s';
        $strings['ResourceMaxLengthNone'] = '这里没有最多预约时间';
        $strings['ResourceRequiresApproval'] = '预约必须得到批准';
        $strings['ResourceRequiresApprovalNone'] = '预约不需要审批';
        $strings['ResourcePermissionAutoGranted'] = '自动授予权限';
        $strings['ResourcePermissionNotAutoGranted'] = '没有自动获得许可';
        $strings['ResourceMinNotice'] = '必须在开始时间前至少 %s 完成预约';
        $strings['ResourceMinNoticeNone'] = '到当前时间均可以预约';
        $strings['ResourceMaxNotice'] = '预约不能在当前时间 %s 后前结束';
        $strings['ResourceMaxNoticeNone'] = '预约可以在将来任意时间点结束';
        $strings['ResourceAllowMultiDay'] = '可以跨日期预约';
        $strings['ResourceNotAllowMultiDay'] = '不能跨日期预约';
        $strings['ResourceCapacity'] = '这个资源可以容纳 %s 人';
        $strings['ResourceCapacityNone'] = '这个资源有无限容纳能力';
        $strings['AddNewResource'] = '添加新的资源';
        $strings['AddNewUser'] = '添加新的用户';
        $strings['AddUser'] = '添加用户';
        $strings['Schedule'] = '时间表';
        $strings['AddResource'] = '增加资源';
        $strings['Capacity'] = '容量';
        $strings['Access'] = '进入';
        $strings['Duration'] = '持续时间';
        $strings['Active'] = '启动';
        $strings['Inactive'] = '未启动';
        $strings['ResetPassword'] = '重设密码';
        $strings['LastLogin'] = '上次登入';
        $strings['Search'] = '搜寻';
        $strings['ResourcePermissions'] = '资源许可';
        $strings['Reservations'] = '预约';
        $strings['Groups'] = '群组';
        $strings['ResetPassword'] = '重设密码';
        $strings['AllUsers'] = '所有用户';
        $strings['AllGroups'] = '所有群组';
        $strings['AllSchedules'] = '所有时间表';
        $strings['UsernameOrEmail'] = '用户名或电子邮件';
        $strings['Members'] = '成员';
        $strings['QuickSlotCreation'] = '每 %s 分钟建立时间间隔 (在 %s 和 %s 之间)';
        $strings['ApplyUpdatesTo'] = '申请更新';
        $strings['CancelParticipation'] = '取消参与';
        $strings['Attending'] = '参与';
        $strings['QuotaConfiguration'] = '在 %s 只有 %s 用户来自 %s 可在去 %s %s 每 %s';
        $strings['reservations'] = '预约';
        $strings['ChangeCalendar'] = '更改日历';
        $strings['AddQuota'] = '增加配额';
        $strings['FindUser'] = '搜寻用户';
        $strings['Created'] = '已建立';
        $strings['LastModified'] = '最后更新的';
        $strings['GroupName'] = '组名';
        $strings['GroupMembers'] = '群组成员';
        $strings['GroupRoles'] = '群组权限';
        $strings['GroupAdmin'] = '群组管理员';
        $strings['Actions'] = '活动';
        $strings['CurrentPassword'] = '当前密码';
        $strings['NewPassword'] = '新密码';
        $strings['InvalidPassword'] = '当前密码不正确';
        $strings['PasswordChangedSuccessfully'] = '您的密码已成功更新';
        $strings['SignedInAs'] = '登陆为';
        $strings['NotSignedIn'] = '您还没有登陆';
        $strings['ReservationTitle'] = '预约名称';
        $strings['ReservationDescription'] = '预约说明';
        $strings['ResourceList'] = '待预约资源';
        $strings['Accessories'] = '设备/附件';
        $strings['Add'] = '增加';
        $strings['ParticipantList'] = '参与者';
        $strings['InvitationList'] = '受邀请者';
        $strings['AccessoryName'] = '附件名称';
        $strings['QuantityAvailable'] = '可用数量';
        $strings['Resources'] = '资源';
        $strings['Participants'] = '参与者';
        $strings['User'] = '用户';
        $strings['Resource'] = '资源';
        $strings['Status'] = '状态';
        $strings['Approve'] = '审批';
        $strings['Page'] = '页';
        $strings['Rows'] = '行';
        $strings['Unlimited'] = '无限的';
        $strings['Email'] = '电子邮件';
        $strings['EmailAddress'] = '电子邮件地址';
        $strings['Phone'] = '电话';
        $strings['Organization'] = '组织';
        $strings['Position'] = '位置';
        $strings['Language'] = '语言';
        $strings['Permissions'] = '许可';
        $strings['Reset'] = '重置';
        $strings['FindGroup'] = '搜寻群组';
        $strings['Manage'] = '管理';
        $strings['None'] = '无';
        $strings['AddToOutlook'] = '添加到日历';
        $strings['Done'] = '完成';
        $strings['RememberMe'] = '记住我';
        $strings['FirstTimeUser?'] = '您是首次来访用户?';
        $strings['CreateAnAccount'] = '建立一个账号';
        $strings['ViewSchedule'] = '查看时间表';
        $strings['ForgotMyPassword'] = '忘记密码';
        $strings['YouWillBeEmailedANewPassword'] = '您将会收到一份系统自动生成的密码';
        $strings['Close'] = '关闭';
        $strings['ExportToCSV'] = '汇出为 CSV 文件';
        $strings['OK'] = '同意';
        $strings['Working'] = '工作中';
        $strings['Login'] = '登陆';
        $strings['AdditionalInformation'] = '附加信息';
        $strings['AllFieldsAreRequired'] = '所有字段都需要填写';
        $strings['Optional'] = '选填内容';
        $strings['YourProfileWasUpdated'] = '您的个人资料已经更新';
        $strings['YourSettingsWereUpdated'] = '您的设置已经上传';
        $strings['Register'] = '注册';
        $strings['SecurityCode'] = '安全码';
        $strings['ReservationCreatedPreference'] = '当我建立一个预约 或 一个预约已经为我建立';
        $strings['ReservationUpdatedPreference'] = '当我建立一个预约 或 一个预约已经为我更新';
        $strings['ReservationDeletedPreference'] = '当我删除一个预约 或 一个预约已经为我删除';
        $strings['ReservationApprovalPreference'] = '当我的待审批预约获得批准';
        $strings['PreferenceSendEmail'] = '电邮给我';
        $strings['PreferenceNoEmail'] = '不用提醒我';
        $strings['ReservationCreated'] = '您的预约已成功建立！';
        $strings['ReservationUpdated'] = '您的预约已成功更新！';
        $strings['ReservationRemoved'] = '您的预约已删除';
        $strings['YourReferenceNumber'] = '您的参考数字是 %s';
        $strings['UpdatingReservation'] = '更新预约';
        $strings['ChangeUser'] = '更改用户';
        $strings['MoreResources'] = '更多的资源';
        $strings['ReservationLength'] = '预约长度';
        $strings['ParticipantList'] = '参与者名单';
        $strings['AddParticipants'] = '添加';
        $strings['InviteOthers'] = '邀请他人';
        $strings['AddResources'] = '添加资源';
        $strings['AddAccessories'] = '添加设备/附件';
        $strings['Accessory'] = '附件';
        $strings['QuantityRequested'] = '请求数';
        $strings['CreatingReservation'] = '建立预约';
        $strings['UpdatingReservation'] = '更新预约';
        $strings['DeleteWarning'] = '这一行动是永久性的，不可更改！ ';
        $strings['DeleteAccessoryWarning'] = '删除这个附件将会导致它在所有预约中被删除.';
        $strings['AddAccessory'] = '添加附件';
        $strings['AddBlackout'] = '添加管制';
        $strings['AllResourcesOn'] = '全部资源在';
        $strings['Reason'] = '理由';
        $strings['BlackoutShowMe'] = '显示有冲突的预约';
        $strings['BlackoutDeleteConflicts'] = '删除有冲突的预约';
        $strings['Filter'] = '筛选';
        $strings['Between'] = '在此期间';
        $strings['CreatedBy'] = '建立者';
        $strings['BlackoutCreated'] = '管制时间已经建立！';
        $strings['BlackoutNotCreated'] = '不能建立管制时间';
        $strings['BlackoutConflicts'] = '存在有冲突的管制时间';
        $strings['ReservationConflicts'] = '存在有冲突的预约时间';
        $strings['UsersInGroup'] = '这个群组里的成员';
        $strings['Browse'] = '浏览';
        $strings['DeleteGroupWarning'] = '删除这个群组会移除所有关联的资源许可，组内成员都将拥有相关资源的权限.';
        $strings['WhatRolesApplyToThisGroup'] = '哪些角色适用于本群组?';
        $strings['WhoCanManageThisGroup'] = '谁能管理这个群组?';
        $strings['WhoCanManageThisSchedule'] = '谁能管理这时间表?';
        $strings['AddGroup'] = '添加群组';
        $strings['AllQuotas'] = '全部配额';
        $strings['QuotaReminder'] = '请记住：配额是强制性地基于已计划的时间分区的.';
        $strings['AllReservations'] = '全部预约';
        $strings['PendingReservations'] = '待审核预约';
        $strings['Approving'] = '审核中';
        $strings['MoveToSchedule'] = '移动到时间表';
        $strings['DeleteResourceWarning'] = '删除这个资源会删除所有关联的数据，包括';
        $strings['DeleteResourceWarningReservations'] = '所有过去的、现在的和将来的预约所关联的.';
        $strings['DeleteResourceWarningPermissions'] = '全部权限分配';
        $strings['DeleteResourceWarningReassign'] = '请您在继续之前把您不想删除的任何内容再一次分配.';
        $strings['ScheduleLayout'] = '规划(全时段 %s)';
        $strings['ReservableTimeSlots'] = '预约的时间间隔';
        $strings['BlockedTimeSlots'] = '管制的时间间隔';
        $strings['ThisIsTheDefaultSchedule'] = '这是默认的时间表';
        $strings['DefaultScheduleCannotBeDeleted'] = '默认时间表不能被删除';
        $strings['MakeDefault'] = '设为默认的';
        $strings['BringDown'] = '下调';
        $strings['ChangeLayout'] = '改变规划';
        $strings['AddSchedule'] = '增加时间表';
        $strings['StartsOn'] = '开始于';
        $strings['NumberOfDaysVisible'] = '显示天数';
        $strings['UseSameLayoutAs'] = '使用相同规划为';
        $strings['Format'] = '格式';
        $strings['OptionalLabel'] = '可选符号';
        $strings['LayoutInstructions'] = '每行输入一个时间间隔.时间间隔必须能提供全部的24小时而且开始和结束于上午12：00.';
        $strings['AddUser'] = '添加用户';
        $strings['UserPermissionInfo'] = '实际进入资源可能会因角色、群组许可或其他许可设定而有所不同.';
        $strings['DeleteUserWarning'] = '删除用户将会删除与他们有关的所有过去的、现在的、将来的预约.';
        $strings['AddAnnouncement'] = '添加通告';
        $strings['Announcement'] = '通告';
        $strings['Priority'] = '优先';
        $strings['Reservable'] = '可预约的';
        $strings['Unreservable'] = '不可预约的';
        $strings['Reserved'] = '已预约';
        $strings['MyReservation'] = '我的预约';
        $strings['Pending'] = '待审批';
        $strings['Past'] = '以前的';
        $strings['Restricted'] = '受限的';
        $strings['ViewAll'] = '查看全部';
        $strings['MoveResourcesAndReservations'] = '移动资源和预约到';
        $strings['TurnOffSubscription'] = '关闭日历订阅';
        $strings['TurnOnSubscription'] = '允许订阅此日历';
        $strings['SubscribeToCalendar'] = '订阅此日历';
        $strings['SubscriptionsAreDisabled'] = '管理员已经禁止订阅此日历';
        $strings['NoResourceAdministratorLabel'] = '(没有资源管理员)';
        $strings['WhoCanManageThisResource'] = '谁能管理这项资源?';
        $strings['ResourceAdministrator'] = '资源管理员';
        $strings['Private'] = '私有的';
        $strings['Accept'] = '接受';
        $strings['Decline'] = '拒绝';
        $strings['ShowFullWeek'] = '显示全部一周';
        $strings['CustomAttributes'] = '自定义属性';
        $strings['AddAttribute'] = '添加一个属性';
        $strings['EditAttribute'] = '更新一个属性';
        $strings['DisplayLabel'] = '显示符号';
        $strings['Type'] = '类型';
        $strings['Required'] = '需要';
        $strings['ValidationExpression'] = '验证表达式';
        $strings['PossibleValues'] = '可能的值';
        $strings['SingleLineTextbox'] = '单行文本框';
        $strings['MultiLineTextbox'] = '多行文本框';
        $strings['Checkbox'] = '检查框';
        $strings['SelectList'] = '选择列表';
        $strings['CommaSeparated'] = '逗号分隔';
        $strings['Category'] = '类别';
        $strings['CategoryReservation'] = '预约';
        $strings['CategoryGroup'] = '群组';
        $strings['SortOrder'] = '排序方式';
        $strings['Title'] = '标题';
        $strings['AdditionalAttributes'] = '附加属性';
        $strings['True'] = '是';
        $strings['False'] = '否';
        $strings['ForgotPasswordEmailSent'] = '一封包含重设密码提示的电子邮件已经发往您提供的的电子邮件地址';
        $strings['ActivationEmailSent'] = '您会立即收到一封有关启动的电子邮件.';
        $strings['AccountActivationError'] = '对不起，我们不能启动您的账号.';
        $strings['Attachments'] = '附件';
        $strings['AttachFile'] = '附件文件';
        $strings['Maximum'] = '最大值';
        $strings['NoScheduleAdministratorLabel'] = '没有时间表管理员';
        $strings['ScheduleAdministrator'] = '时间表管理员';
        $strings['Total'] = '总数';
        $strings['QuantityReserved'] = '已预约数量';
        $strings['AllAccessories'] = '所有设备/附件';
        $strings['GetReport'] = '取得报告';
        $strings['NoResultsFound'] = '没有符合的结果';
        $strings['SaveThisReport'] = '储存这报告';
        $strings['ReportSaved'] = '报告已储存!';
        $strings['EmailReport'] = '以电邮寄出报告';
        $strings['ReportSent'] = '报告已寄出!';
        $strings['RunReport'] = '导出报告';
        $strings['NoSavedReports'] = '你没有已储存的报告.';
        $strings['CurrentWeek'] = '本周';
        $strings['CurrentMonth'] = '本月';
        $strings['AllTime'] = '全部时间';
        $strings['FilterBy'] = '以..过滤';
        $strings['Select'] = '选择';
        $strings['List'] = '以 列';
        $strings['TotalTime'] = '以 总时间';
        $strings['Count'] = '以 数目';
        $strings['Usage'] = '使用情况';
        $strings['AggregateBy'] = '合计以';
        $strings['Range'] = '范围';
        $strings['Choose'] = '选择';
        $strings['All'] = '所有';
        $strings['ViewAsChart'] = '以图表观看';
        $strings['ReservedResources'] = '已预约的资源';
        $strings['ReservedAccessories'] = '已预约的设备/附件';
        $strings['ResourceUsageTimeBooked'] = '资源使用情况 - 预订时间';
        $strings['ResourceUsageReservationCount'] = '资源使用情况 - 预约次数';
        $strings['Top20UsersTimeBooked'] = '二十高使用量使用者 - 预订时间';
        $strings['Top20UsersReservationCount'] = '二十高使用量使用者 - 预约次数';
        // End Strings

        // Errors
        $strings['LoginError'] = '用户名和密码不符合';
        $strings['ReservationFailed'] = '没办法建立您的预约';
        $strings['MinNoticeError'] = '这份预约需要高级提示.最早能提供的日期和时间是 %s.';
        $strings['MaxNoticeError'] = '这份预约不能在以后进行.最迟能被预约的日期和时间是 %s.';
        $strings['MinDurationError'] = '这份预约必须延长到 %s.';
        $strings['MaxDurationError'] = '这份预约不能延长到 %s.';
        $strings['ConflictingAccessoryDates'] = '没有足够的附件：';
        $strings['NoResourcePermission'] = '您没有权限访问一个或一个以上的请求.';
        $strings['ConflictingReservationDates'] = '在接下来的日子里存在有冲突的预约:';
        $strings['StartDateBeforeEndDateRule'] = '开始时间和日期必须早于结束时间和日期';
        $strings['RecurringWithoutTerminationRule'] = '重复黑名单需要设定终止日期。';
        $strings['StartIsInPast'] = '开始时间和日期必须比当前时间晚';
        $strings['EmailDisabled'] = '管理员已经禁止了邮件提醒';
        $strings['ValidLayoutRequired'] = '时间间隔必须提供全天24小时而且必须从上午12时开始并结束于上午12时.';
        $strings['CustomAttributeErrors'] = '您提供的附加属性出了问题:';
        $strings['CustomAttributeRequired'] = '%s 是必填项目';
        $strings['CustomAttributeInvalid'] = '提供给 %s 的内容是有效的.';
        $strings['AttachmentLoadingError'] = '对不起，在加载档时发现了一个问题.';
        $strings['InvalidAttachmentExtension'] = '您只能上传这些类型的档: %s';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = '建立预约';
        $strings['EditReservation'] = '编辑预约';
        $strings['LogIn'] = '登入';
        $strings['ManageReservations'] = '预约';
        $strings['AwaitingActivation'] = '等待启动';
        $strings['PendingApproval'] = '待审批中';
        $strings['ManageSchedules'] = '时间表';
        $strings['ManageResources'] = '资源';
        $strings['ManageAccessories'] = '设备/附件';
        $strings['ManageUsers'] = '用户';
        $strings['ManageGroups'] = '群组';
        $strings['ManageQuotas'] = '配额';
        $strings['ManageBlackouts'] = '管制时间';
        $strings['MyDashboard'] = '我的控制板';
        $strings['ServerSettings'] = '服务器设置';
        $strings['Dashboard'] = '控制台';
        $strings['Help'] = '帮助';
        $strings['Administration'] = '管理';
        $strings['About'] = '关于';
        $strings['Bookings'] = '预订';
        $strings['Schedule'] = '时间表';
        $strings['Reservations'] = '预约';
        $strings['Account'] = '账号';
        $strings['EditProfile'] = '编辑个人编纂';
        $strings['FindAnOpening'] = '找到一个开放的';
        $strings['OpenInvitations'] = '开放的邀请';
        $strings['MyCalendar'] = '我的日历';
        $strings['ResourceCalendar'] = '资源日历';
        $strings['Reservation'] = '新预约';
        $strings['Install'] = '安装';
        $strings['ChangePassword'] = '改变密码';
        $strings['MyAccount'] = '我的账号';
        $strings['Profile'] = '个人信息';
        $strings['ApplicationManagement'] = '程序管理';
        $strings['ForgotPassword'] = '忘了密码';
        $strings['NotificationPreferences'] = '通知设定';
        $strings['ManageAnnouncements'] = '通告';
        $strings['Responsibilities'] = '主要职责';
        $strings['GroupReservations'] = '群组预约';
        $strings['ResourceReservations'] = '预约资源';
        $strings['Customization'] = '自定义';
        $strings['Attributes'] = '属性';
        $strings['AccountActivation'] = '账号激活';
        $strings['ScheduleReservations'] = '计划预约';
        $strings['Reports'] = '报告';
        $strings['GenerateReport'] = '建立一个新报告';
        $strings['MySavedReports'] = '我已储存的报告';
        $strings['CommonReports'] = '共享的报告';
        $strings['ViewDay'] = '观看日期';
        $strings['Group'] = '群组';
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
        $strings['DayMondayAbbr'] = '周一';
        $strings['DayTuesdayAbbr'] = '周二';
        $strings['DayWednesdayAbbr'] = '周三';
        $strings['DayThursdayAbbr'] = '周四';
        $strings['DayFridayAbbr'] = '周五';
        $strings['DaySaturdayAbbr'] = '周六';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = '您的账号已经启动';
        $strings['ReservationCreatedSubject'] = '您的预约已经建立';
        $strings['ReservationUpdatedSubject'] = '您的预约已经更新';
        $strings['ReservationDeletedSubject'] = '您的预约已经移除';
        $strings['ReservationCreatedAdminSubject'] = '提示：一个预约已经建立';
        $strings['ReservationUpdatedAdminSubject'] = '提示：一个预约已经更新';
        $strings['ReservationDeleteAdminSubject'] = '提示：一个预约已经移除';
        $strings['ParticipantAddedSubject'] = '预约参与通知';
        $strings['ParticipantDeletedSubject'] = '已移除的预约';
        $strings['InviteeAddedSubject'] = '预约邀请';
        $strings['ResetPassword'] = '重置密码申请';
        $strings['ActivateYourAccount'] = '请启动您的账号';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = '您没有以前的预订';
        $strings['PastReservations'] = '以前的预订';
        $strings['AllNoPastReservations'] = '在过去的%s天内没有以前的预订';
        $strings['AllPastReservations'] = '所有以前的预订';
        $strings['Yesterday'] = '昨天';
        $strings['EarlierThisWeek'] = '本周早些时候';
        $strings['PreviousWeek'] = '上周';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = '您的团体没有即将到来的预订';
        $strings['GroupUpcomingReservations'] = '我的团体即将到来的预订';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = '使用Facebook登录时发生错误。请重试。';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = '您没有等待批准的预订';
        $strings['PendingApprovalReservations'] = '等待批准的预订';
        $strings['LaterThisMonth'] = '本月晚些时候';
        $strings['LaterThisYear'] = '本年晚些时候';
        $strings['Remaining'] = '剩余';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = '没有缺失的退房预订';
        $strings['MissingCheckOutReservations'] = '缺失的退房预订';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = '由于您在此预订中的任何资源都没有权限，因此无法查看预订详细信息';
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
        return 'zh_cn';
    }
}
