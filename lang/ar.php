<?php

require_once('Language.php');
require_once('en_us.php');

class ar extends en_us
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

        $dates['general_date'] = 'm/d/Y';
        $dates['general_datetime'] = 'm/d/Y g:i:s A';
        $dates['short_datetime'] = 'n/j/y g:i A';
        $dates['schedule_daily'] = 'l, n/j/y';
        $dates['reservation_email'] = 'm/d/Y @ g:i A (e)';
        $dates['res_popup'] = 'D, n/d g:i A';
        $dates['res_popup_time'] = 'g:i A';
        $dates['short_reservation_date'] = 'n/j/y g:i A';
        $dates['dashboard'] = 'D, n/d g:i A';
        $dates['period_time'] = 'g:i A';
        $dates['mobile_reservation_date'] = 'n/j g:i A';
        $dates['general_date_js'] = 'mm/dd/yy';
        $dates['general_time_js'] = 'h:mm tt';
        $dates['momentjs_datetime'] = 'M/D/YY h:mm A';
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

        $strings['FirstName'] = 'الاسم الأول ';
        $strings['LastName'] = 'الاسم الأخير';
        $strings['Timezone'] = 'توقيت المنطقة';
        $strings['Edit'] = 'تحرير';
        $strings['Change'] = 'تغيير';
        $strings['Rename'] = 'إعادة تسمية ';
        $strings['Remove'] = 'إزالة';
        $strings['Delete'] = 'حذف';
        $strings['Update'] = 'تحديث';
        $strings['Cancel'] = 'إالغاء';
        $strings['Add'] = 'إضافة';
        $strings['Name'] = 'الاسم';
        $strings['Yes'] = 'نعم';
        $strings['No'] = 'لا';
        $strings['FirstNameRequired'] = 'الاسم الأول مطلوب.';
        $strings['LastNameRequired'] = 'الاسم الأخير مطلوب.';
        $strings['PwMustMatch'] = 'يجب مطابفة كلمة المرور مع كلمة المرورالمدخلة .';
        $strings['ValidEmailRequired'] = 'مطلوب عنوان بريد الكتروني صالح.';
        $strings['UniqueEmailRequired'] = 'البريد الألكتروني مسجل مسبقا.';
        $strings['UniqueUsernameRequired'] = 'اسم المستخدم مسجل مسبقا.';
        $strings['UserNameRequired'] = 'اسم المستخدم مطلوب.';
        $strings['CaptchaMustMatch'] = 'كلمة التحقق مطلوبه.';
        $strings['Today'] = 'اليوم ';
        $strings['Week'] = 'اسبوع';
        $strings['Month'] = 'شهر';
        $strings['BackToCalendar'] = 'العودة إلى التقويم';
        $strings['BeginDate'] = 'بدأ';
        $strings['EndDate'] = 'نهاية';
        $strings['Username'] = 'اسم المستخدم';
        $strings['Password'] = 'كلمة المرور';
        $strings['PasswordConfirmation'] = 'تأكيد كلمةالمرور';
        $strings['DefaultPage'] = 'الصفحة الرئسية الافتراضية';
        $strings['MyCalendar'] = 'تقويمي';
        $strings['ScheduleCalendar'] = 'جدولة التقويم';
        $strings['Registration'] = 'تسجيل';
        $strings['NoAnnouncements'] = 'لا توجد إعلانات';
        $strings['Announcements'] = 'الإعلانات ';
        $strings['NoUpcomingReservations'] = 'لا توجد لديك حجوزات قادمة ';
        $strings['UpcomingReservations'] = 'الحجوزات القادمة';
        $strings['AllNoUpcomingReservations'] = 'لا توجد لديك حجوزات قادمة  %s ايام';
        $strings['AllUpcomingReservations'] = 'جميع الحجوزات القادمة';
        $strings['ShowHide'] = 'إظهار/إخفاء';
        $strings['Error'] = 'خطأ';
        $strings['ReturnToPreviousPage'] = 'ارجع إلى الصفحة الأخيرة التي كنت فيها';
        $strings['UnknownError'] = 'خطأ غير معروف';
        $strings['InsufficientPermissionsError'] = 'ليس لديك إذن للوصول إلى هذا المورد';
        $strings['MissingReservationResourceError'] = 'لم يتم اختيار مورد';
        $strings['MissingReservationScheduleError'] = 'لم يتم تحديد الجدول الزمني';
        $strings['DoesNotRepeat'] = 'لا يتكرر';
        $strings['Daily'] = 'يومي';
        $strings['Weekly'] = 'اسبوعي';
        $strings['Monthly'] = 'شهري';
        $strings['Yearly'] = 'سنوي';
        $strings['RepeatPrompt'] = 'تكرار';
        $strings['hours'] = 'ساعات';
        $strings['days'] = 'ايام';
        $strings['weeks'] = 'اسبوعي';
        $strings['months'] = 'شهري';
        $strings['years'] = 'سنوي';
        $strings['day'] = 'يوم';
        $strings['week'] = 'اسبوع';
        $strings['month'] = 'شهر';
        $strings['year'] = 'سنة';
        $strings['repeatDayOfMonth'] = 'يوم من الشهر';
        $strings['repeatDayOfWeek'] = 'يوم من الاسبوع';
        $strings['RepeatUntilPrompt'] = 'حتى';
        $strings['RepeatEveryPrompt'] = 'كل';
        $strings['RepeatDaysPrompt'] = 'على';
        $strings['CreateReservationHeading'] = 'حجز جديد';
        $strings['EditReservationHeading'] = 'تحرير الحجز %s';
        $strings['ViewReservationHeading'] = 'عرض الحجز %s';
        $strings['ReservationErrors'] = 'تغيير الحجز';
        $strings['Create'] = 'انشاء';
        $strings['ThisInstance'] = 'هذا النموذج فقط';
        $strings['AllInstances'] = 'كل النماذج ';
        $strings['FutureInstances'] = 'نماذج مستقبلية ';
        $strings['Print'] = 'طباعة';
        $strings['ShowHideNavigation'] = 'إظهار/إخفاء التنقل';
        $strings['ReferenceNumber'] = 'رقم الحجز - ';
        $strings['Tomorrow'] = 'غدا';
        $strings['LaterThisWeek'] = 'اخر هذا الاسبوع';
        $strings['NextWeek'] = 'الاسبوع القادم';
        $strings['SignOut'] = 'تسجيل خروج';
        $strings['LayoutDescription'] = 'بدء من  %s, إظهار %s أيام في كل مرة';
        $strings['AllResources'] = 'جميع المورد';
        $strings['TakeOffline'] = 'اخذ دون اتصال';
        $strings['BringOnline'] = 'احضار بالاتصال';
        $strings['AddImage'] = 'إضافة صورة';
        $strings['NoImage'] = 'لم يتم تعين صورة';
        $strings['Move'] = 'تحريك';
        $strings['AppearsOn'] = 'يظهر في %s';
        $strings['Location'] = 'الموقع';
        $strings['NoLocationLabel'] = '(لم يتم تعين الموقع )';
        $strings['Contact'] = 'اتصال';
        $strings['NoContactLabel'] = '(لا يوجد بيانات لجهة الاتصال)';
        $strings['Description'] = 'وصف';
        $strings['NoDescriptionLabel'] = '(لا يوجد وصف)';
        $strings['Notes'] = 'مذكرة ملاحظة';
        $strings['NoNotesLabel'] = '(لا يوجد ملاحظة)';
        $strings['NoTitleLabel'] = '(لا يوجد عنوان )';
        $strings['UsageConfiguration'] = 'تكوين الاستخدام';
        $strings['ChangeConfiguration'] = 'تغيير التكوين';
        $strings['ResourceMinLength'] = 'يجب أن تستمر الحجوزات على الأقل %s';
        $strings['ResourceMinLengthNone'] = 'لا يوجد حد أدنى لمدة الحجز';
        $strings['ResourceMaxLength'] = 'لا يمكن أن تستمر الحجوزات أكثر من %s';
        $strings['ResourceMaxLengthNone'] = 'لا يوجد حد أقصى لمدة الحجز';
        $strings['ResourceRequiresApproval'] = 'يجب الموافقة على الحجوزات';
        $strings['ResourceRequiresApprovalNone'] = 'الحجوزات لا تتطلب الموافقة';
        $strings['ResourcePermissionAutoGranted'] = 'يتم منح الإذن تلقائيًا';
        $strings['ResourcePermissionNotAutoGranted'] = 'لا يتم منح الإذن تلقائيًا';
        $strings['ResourceMinNotice'] = 'يجب أن يتم الحجز قبل %s على الأقل من وقت البدء';
        $strings['ResourceMinNoticeNone'] = 'يمكن إجراء الحجوزات حتى الوقت الحالي';
        $strings['ResourceMinNoticeUpdate'] = 'يجب تحديث الحجوزات على الأقل %s قبل وقت البدء';
        $strings['ResourceMinNoticeNoneUpdate'] = 'يمكن تحديث الحجوزات حتى الوقت الحالي';
        $strings['ResourceMinNoticeDelete'] = 'يجب حذف الحجوزات على الأقل %s قبل وقت البدء';
        $strings['ResourceMinNoticeNoneDelete'] = 'يمكن حذف الحجوزات حتى الوقت الحالي';
        $strings['ResourceMaxNotice'] = 'يجب ألا تنتهي الحجوزات أكثر من %s من الوقت الحالي';
        $strings['ResourceMaxNoticeNone'] = 'يمكن أن تنتهي الحجوزات في أي وقت في المستقبل';
        $strings['ResourceBufferTime'] = 'يجب أن يكون هناك %s بين الحجوزات ';
        $strings['ResourceBufferTimeNone'] = 'لا يوجد فاصل بين الحجوزات';
        $strings['ResourceAllowMultiDay'] = 'يمكن إجراء الحجوزات عبر الأيام';
        $strings['ResourceNotAllowMultiDay'] = 'لا يمكن إجراء الحجوزات عبر الأيام';
        $strings['ResourceCapacity'] = 'هذا المورد لديه قدرة %s اشخاص';
        $strings['ResourceCapacityNone'] = 'هذا المورد لديه قدرة غير محدودة';
        $strings['AddNewResource'] = 'أضف مصدر جديد';
        $strings['AddNewUser'] = 'إضافة مستخدم جديد ';
        $strings['AddResource'] = 'إضافة مورد';
        $strings['Capacity'] = 'السعة';
        $strings['Access'] = 'التمكن ';
        $strings['Duration'] = 'المدة الزمنية';
        $strings['Active'] = 'فعال';
        $strings['Inactive'] = 'غير فعال';
        $strings['ResetPassword'] = 'إعادة ضبط كلمة المرور';
        $strings['LastLogin'] = 'أخر دخول';
        $strings['Search'] = 'بحث';
        $strings['ResourcePermissions'] = 'أذونات الموارد';
        $strings['Reservations'] = 'الحجوزات';
        $strings['Groups'] = 'المجموعات';
        $strings['Users'] = 'المستخدمين';
        $strings['AllUsers'] = 'جميع المستخدمين';
        $strings['AllGroups'] = 'جميع المجموعات';
        $strings['AllSchedules'] = 'جميع الجداول';
        $strings['UsernameOrEmail'] = 'اسم المستخدم او كلمة المرور ';
        $strings['Members'] = 'الاعضاء';
        $strings['QuickSlotCreation'] = 'إنشاء فتحات كل %s دقائق بين %s و %s';
        $strings['ApplyUpdatesTo'] = 'تطبيق التحديثات على';
        $strings['CancelParticipation'] = ' إلغاء المشاركة';
        $strings['Attending'] = 'حضور';
        $strings['QuotaConfiguration'] = 'من %s إلى %s المستخدمين في %s تقتصر على %s %s لكل %s';
        $strings['QuotaEnforcement'] = 'فرض %s %s';
        $strings['reservations'] = 'الحجوزات';
        $strings['reservation'] = 'حجز';
        $strings['ChangeCalendar'] = 'تغيير التقويم';
        $strings['AddQuota'] = 'إضافة نسبة';
        $strings['FindUser'] = 'بحث مستخدم';
        $strings['Created'] = 'انشاء';
        $strings['LastModified'] = 'اخر تعديل';
        $strings['GroupName'] = 'اسم المجموعة';
        $strings['GroupMembers'] = 'اعضاء المجموعة';
        $strings['GroupRoles'] = 'ادوار المجموعة';
        $strings['GroupAdmin'] = 'مسؤول المجموعة';
        $strings['Actions'] = 'إجراء';
        $strings['CurrentPassword'] = 'كلمة المرور الحالية';
        $strings['NewPassword'] = 'كلمة مرور جديدة';
        $strings['InvalidPassword'] = 'كلمة المرور الحالية غير صحيحة';
        $strings['PasswordChangedSuccessfully'] = 'تم تغيير كلمة المرور بنجاح';
        $strings['SignedInAs'] = 'دخول بأسم';
        $strings['NotSignedIn'] = 'لست مسجل دخول';
        $strings['ReservationTitle'] = 'عنوان الحجز';
        $strings['ReservationDescription'] = 'وصف الحجز';
        $strings['ResourceList'] = 'الموارد التي سيتم حجزها';
        $strings['Accessories'] = 'مستلزمات - إضافات ';
        $strings['InvitationList'] = 'المدعوين';
        $strings['AccessoryName'] = 'اسم الاضافة - المستلزم';
        $strings['QuantityAvailable'] = 'الكمية المتاحة';
        $strings['Resources'] = 'المورد';
        $strings['Participants'] = 'المشاركين';
        $strings['User'] = 'مستخدم';
        $strings['Resource'] = 'الموارد';
        $strings['Status'] = 'الحالة';
        $strings['Approve'] = 'موافقة';
        $strings['Page'] = 'صفحة';
        $strings['Rows'] = 'صف';
        $strings['Unlimited'] = 'غير محدود';
        $strings['Email'] = 'البريد الألكتروني';
        $strings['EmailAddress'] = 'عنوان البريد الألكتروني';
        $strings['Phone'] = 'هاتف';
        $strings['Organization'] = 'المنظمة';
        $strings['Position'] = 'الوضع';
        $strings['Language'] = 'اللغة';
        $strings['Permissions'] = 'الأذونات ';
        $strings['Reset'] = 'إعادة ضبط ';
        $strings['FindGroup'] = 'البحث عن مجموعة';
        $strings['Manage'] = 'إدارة';
        $strings['None'] = 'لا شيء ';
        $strings['AddToOutlook'] = 'إضافة الى التقويم ';
        $strings['Done'] = 'موافق';
        $strings['RememberMe'] = 'تذكرني';
        $strings['FirstTimeUser?'] = 'مستخدم اول مرة ?';
        $strings['CreateAnAccount'] = 'انشاء حساب';
        $strings['ViewSchedule'] = 'عرض الجدولة';
        $strings['ForgotMyPassword'] = 'نسيت كلمة المرور';
        $strings['YouWillBeEmailedANewPassword'] = 'سيتم إرسال كلمة مرور جديدة عشوائيًا عبر البريد الإلكتروني';
        $strings['Close'] = 'إغلاق';
        $strings['ExportToCSV'] = 'تصدير إلى  CSV';
        $strings['OK'] = 'موافق';
        $strings['Working'] = 'جاري العمل...';
        $strings['Login'] = 'تسجيل الدخول';
        $strings['AdditionalInformation'] = 'معلومات إضافية';
        $strings['AllFieldsAreRequired'] = 'جميع الحقول مطلوبة';
        $strings['Optional'] = 'اختياري';
        $strings['YourProfileWasUpdated'] = 'تم تحديث ملف التعريف الخاص بك';
        $strings['YourSettingsWereUpdated'] = 'تم تحديث إعداداتك';
        $strings['Register'] = 'تسجيل';
        $strings['SecurityCode'] = 'كود التحقق';
        $strings['ReservationCreatedPreference'] = 'عندما أقوم بإنشاء حجز أو يتم إنشاء حجز نيابة عني';
        $strings['ReservationUpdatedPreference'] = 'عندما أقوم بتحديث حجز أو تحديث حجز نيابة عني';
        $strings['ReservationDeletedPreference'] = 'عندما أقوم بحذف حجز أو حذف حجز نيابة عني';
        $strings['ReservationApprovalPreference'] = 'عندما تتم الموافقة على حجزي المعلق';
        $strings['PreferenceSendEmail'] = 'ارسل لي بريد الكتروني';
        $strings['PreferenceNoEmail'] = 'لا ترسل إشعارات لي';
        $strings['ReservationCreated'] = 'تم إنشاء الحجز الخاص بك بنجاح!';
        $strings['ReservationUpdated'] = 'تم تحديث حجزك بنجاح!';
        $strings['ReservationRemoved'] = 'تمت إزالة حجزك';
        $strings['ReservationRequiresApproval'] = 'يتطلب واحد أو أكثر من الموارد المحجوزة الموافقة قبل الاستخدام. سيكون هذا الحجز معلقًا حتى تتم الموافقة عليه.';
        $strings['YourReferenceNumber'] = 'الرقم المرجعي - الحجز الخاص بك هو %s';
        $strings['ChangeUser'] = 'تغيير مستخدم';
        $strings['MoreResources'] = 'موارد اكثر';
        $strings['ReservationLength'] = 'مدة الحجز';
        $strings['ParticipantList'] = 'قائمة المشاركين';
        $strings['AddParticipants'] = 'أضف مشاركين';
        $strings['InviteOthers'] = 'ادع الاخرين';
        $strings['AddResources'] = 'أضف الموارد';
        $strings['AddAccessories'] = 'أضف ملحقات- مستلزمات';
        $strings['Accessory'] = 'ملحقات - مستلزمات';
        $strings['QuantityRequested'] = 'الكمية المطلوبة';
        $strings['CreatingReservation'] = 'إنشاء حجز';
        $strings['UpdatingReservation'] = 'تحديث حجز';
        $strings['DeleteWarning'] = 'هذا الإجراء نهائي ولا يمكن التراجع عنه!';
        $strings['DeleteAccessoryWarning'] = 'سيؤدي حذف هذا الملحق إلى إزالته من جميع الحجوزات.';
        $strings['AddAccessory'] = 'إضافة ملحقات - مستلزمات';
        $strings['AddBlackout'] = 'أضف الحجب';
        $strings['AllResourcesOn'] = 'تشغيل كافة الموارد';
        $strings['Reason'] = 'السبب';
        $strings['BlackoutShowMe'] = 'عرض الحجوزات المتضاربة';
        $strings['BlackoutDeleteConflicts'] = 'حذف الحجوزات المتضاربة';
        $strings['Filter'] = 'تصفيه';
        $strings['Between'] = 'بين';
        $strings['CreatedBy'] = 'انشئ من قبل ';
        $strings['BlackoutCreated'] = 'تم إنشاء التعتيم';
        $strings['BlackoutNotCreated'] = 'تعذر إنشاء تعتيم';
        $strings['BlackoutUpdated'] = 'تم تحديث التعتيم';
        $strings['BlackoutNotUpdated'] = 'تعذر تحديث التعتيم';
        $strings['BlackoutConflicts'] = 'هناك أوقات متضاربة';
        $strings['ReservationConflicts'] = 'هناك أوقات متضاربة في الحجوزات';
        $strings['UsersInGroup'] = 'المستخدمين في هذه المجموعة';
        $strings['Browse'] = 'استعراض';
        $strings['DeleteGroupWarning'] = 'سيؤدي حذف هذه المجموعة إلى إزالة جميع أذونات الموارد المرتبطة. قد يفقد المستخدمون في هذه المجموعة الوصول إلى الموارد.';
        $strings['WhatRolesApplyToThisGroup'] = 'الأدوار التي تنطبق على هذه المجموعة?';
        $strings['WhoCanManageThisGroup'] = 'من يمكنه إدارة هذه المجموعة?';
        $strings['WhoCanManageThisSchedule'] = 'من يمكنه إدارة هذا الجدول الزمني?';
        $strings['AllQuotas'] = 'كل النسسب';
        $strings['QuotaReminder'] = 'تذكر: يتم فرض الحصص بناءً على الجدول الزمني\'s المنطقة الزمنية.';
        $strings['AllReservations'] = 'جميع الحجوزات';
        $strings['PendingReservations'] = 'الحجوزات المعلقة';
        $strings['Approving'] = 'الموافقة';
        $strings['MoveToSchedule'] = 'الانتقال إلى الجدول الزمني';
        $strings['DeleteResourceWarning'] = 'سيؤدي حذف هذا المورد إلى حذف جميع البيانات المرتبطة ، بما في ذلك';
        $strings['DeleteResourceWarningReservations'] = 'جميع الحجوزات السابقة والحالية والمستقبلية المرتبطة بها';
        $strings['DeleteResourceWarningPermissions'] = 'جميع تخصيصات الأذونات';
        $strings['DeleteResourceWarningReassign'] = 'الرجاء إعادة تعيين أي شيء لا تريد حذفه قبل المتابعة';
        $strings['ScheduleLayout'] = 'نسق (جميع الاوقات %s)';
        $strings['ReservableTimeSlots'] = 'خانات زمنية قابلة للحجز';
        $strings['BlockedTimeSlots'] = 'فترات زمنية محظورة';
        $strings['ThisIsTheDefaultSchedule'] = 'هذا هو الجدول الزمني الافتراضي';
        $strings['DefaultScheduleCannotBeDeleted'] = 'لا يمكن حذف الجدول الافتراضي';
        $strings['MakeDefault'] = 'جعله افتراضي';
        $strings['BringDown'] = 'يجلب';
        $strings['ChangeLayout'] = 'تغيير النسق';
        $strings['AddSchedule'] = 'إضافة جدولة';
        $strings['StartsOn'] = 'يبدأمن ';
        $strings['NumberOfDaysVisible'] = 'عدد الأيام المرئية';
        $strings['UseSameLayoutAs'] = 'استخدم نفس التخطيط باسم';
        $strings['Format'] = 'شكل';
        $strings['OptionalLabel'] = 'تسمية اختيارية';
        $strings['LayoutInstructions'] = 'أدخل فتحة واحدة في كل سطر. يجب توفير الخانات الزمنية لجميع الـ 24 ساعة من اليوم التي تبدأ وتنتهي في 12:00 AM.';
        $strings['AddUser'] = 'إضافة مستخدم';
        $strings['UserPermissionInfo'] = 'قد يختلف الوصول الفعلي إلى المورد حسب دور المستخدم أو أذونات المجموعة أو إعدادات الأذونات الخارجية';
        $strings['DeleteUserWarning'] = 'سيؤدي حذف هذا المستخدم إلى إزالة جميع حجوزاته الحالية والمستقبلية والتاريخية.';
        $strings['AddAnnouncement'] = 'اضافة اعلان';
        $strings['Announcement'] = 'إعلان';
        $strings['Priority'] = 'أفضلية';
        $strings['Reservable'] = 'مفتوح';
        $strings['Unreservable'] = 'محجوب ';
        $strings['Reserved'] = 'محجوز';
        $strings['MyReservation'] = 'حجوزاتي';
        $strings['Pending'] = 'قيد الانتظار';
        $strings['Past'] = 'الماضي';
        $strings['Restricted'] = 'محدد';
        $strings['ViewAll'] = 'عرض الكل';
        $strings['MoveResourcesAndReservations'] = 'نقل الموارد و الحجوزات إلى';
        $strings['TurnOffSubscription'] = 'إخفاء من الجمهور';
        $strings['TurnOnSubscription'] = 'عرض للجمهور (RSS, iCalendar, Tablet, Monitor)';
        $strings['SubscribeToCalendar'] = 'اشترك في هذا التقويم';
        $strings['SubscriptionsAreDisabled'] = 'قام المسؤول بتعطيل اشتراكات التقويم';
        $strings['NoResourceAdministratorLabel'] = '(لا يوجد مسؤول الموارد)';
        $strings['WhoCanManageThisResource'] = 'من يمكنه إدارة هذا المورد?';
        $strings['ResourceAdministrator'] = 'مسؤول الموارد';
        $strings['Private'] = 'مسؤول';
        $strings['Accept'] = 'مقبول';
        $strings['Decline'] = 'رفض';
        $strings['ShowFullWeek'] = 'عرض كامل الاسبوع';
        $strings['CustomAttributes'] = 'حقول مخصصة';
        $strings['AddAttribute'] = 'أضف سمة';
        $strings['EditAttribute'] = 'تحديث السمة';
        $strings['DisplayLabel'] = 'عرض التسمية';
        $strings['Type'] = 'النوع';
        $strings['Required'] = 'مطلوب';
        $strings['ValidationExpression'] = 'تعبير التحقق';
        $strings['PossibleValues'] = 'القيم الممكنة';
        $strings['SingleLineTextbox'] = 'مربع نص سطر واحد';
        $strings['MultiLineTextbox'] = 'مربع نص متعدد الأسطر';
        $strings['Checkbox'] = 'خانة اختيار';
        $strings['SelectList'] = 'حدد قائمة';
        $strings['CommaSeparated'] = 'مفصولة بفواصل';
        $strings['Category'] = 'الفئة';
        $strings['CategoryReservation'] = 'حجز';
        $strings['CategoryGroup'] = 'مجموعة';
        $strings['SortOrder'] = 'امر ترتيب';
        $strings['Title'] = 'العنوان';
        $strings['AdditionalAttributes'] = 'سمات إضافية';
        $strings['True'] = 'نعم';
        $strings['False'] = 'لا';
        $strings['ForgotPasswordEmailSent'] = 'تم إرسال بريد إلكتروني إلى العنوان المزود بتعليمات لإعادة تعيين كلمة المرور الخاصة بك';
        $strings['ActivationEmailSent'] = 'ستتلقى رسالة بريد إلكتروني للتفعيل قريبًا.';
        $strings['AccountActivationError'] = 'عذرا ، لم نتمكن من تفعيل حسابك.';
        $strings['Attachments'] = 'المرفقات';
        $strings['AttachFile'] = 'أرفق ملف';
        $strings['Maximum'] = 'اعلى';
        $strings['NoScheduleAdministratorLabel'] = 'لا يوجد مسؤول جدولة';
        $strings['ScheduleAdministrator'] = 'مدير الجدول';
        $strings['Total'] = 'مجموع';
        $strings['QuantityReserved'] = 'الكمية المحجوزة';
        $strings['AllAccessories'] = 'جميع الملحقات';
        $strings['GetReport'] = 'احصل على تقرير';
        $strings['NoResultsFound'] = 'لم يتم العثور على نتائج مطابقة';
        $strings['SaveThisReport'] = 'احفظ هذا التقرير';
        $strings['ReportSaved'] = 'تم حفظ التقرير!';
        $strings['EmailReport'] = 'تقرير بالبريد الإلكتروني';
        $strings['ReportSent'] = 'تم إرسال التقرير!';
        $strings['RunReport'] = 'تشغيل التقرير';
        $strings['NoSavedReports'] = 'ليس لديك تقارير محفوظة.';
        $strings['CurrentWeek'] = 'الأسبوع الحالي';
        $strings['CurrentMonth'] = 'الشهر الحالي';
        $strings['AllTime'] = 'كل الوقت';
        $strings['FilterBy'] = 'مصنف بواسطة';
        $strings['Select'] = 'اختيار';
        $strings['List'] = 'قائمة';
        $strings['TotalTime'] = 'الوقت';
        $strings['Count'] = 'العدد';
        $strings['Usage'] = 'إستعمال';
        $strings['AggregateBy'] = 'تجميع حسب';
        $strings['Range'] = 'نطاق';
        $strings['Choose'] = 'أختر';
        $strings['All'] = 'الجميع';
        $strings['ViewAsChart'] = 'عرض كمخطط';
        $strings['ReservedResources'] = 'الموارد المحجوزة';
        $strings['ReservedAccessories'] = 'الملحقات المحجوزة';
        $strings['ResourceUsageTimeBooked'] = 'استخدام الموارد - الوقت المحجوز';
        $strings['ResourceUsageReservationCount'] = 'استخدام الموارد - عدد الحجوزات';
        $strings['Top20UsersTimeBooked'] = 'أفضل 20 مستخدمًا - حجز الوقت ';
        $strings['Top20UsersReservationCount'] = 'أفضل 20 مستخدمًا - عدد الحجوزات';
        $strings['ConfigurationUpdated'] = 'تم تحديث ملف التكوين';
        $strings['ConfigurationUiNotEnabled'] = 'لا يمكن الوصول إلى هذه الصفحة بسبب $conf[\'settings\'][\'pages\'][\'enable.configuration\'] تم ضبطه على خطأ أو مفقود.';
        $strings['ConfigurationFileNotWritable'] = 'ملف التكوين غير قابل للكتابة. الرجاء التحقق من أذونات هذا الملف وحاول مرة أخرى.';
        $strings['ConfigurationUpdateHelp'] = 'راجع قسم التكوين في <a target=_blank href=%s>Help File</a> لتوثيق هذه الإعدادات.';
        $strings['GeneralConfigSettings'] = 'إعدادات';
        $strings['UseSameLayoutForAllDays'] = 'استخدم نفس التخطيط لجميع الأيام';
        $strings['LayoutVariesByDay'] = 'يختلف التخطيط حسب اليوم';
        $strings['ManageReminders'] = 'تذكير';
        $strings['ReminderUser'] = 'معرف المستخدم';
        $strings['ReminderMessage'] = 'رسالة';
        $strings['ReminderAddress'] = 'العنوان ';
        $strings['ReminderSendtime'] = 'وقت الارسال';
        $strings['ReminderRefNumber'] = 'الرقم المرجعي للحجز';
        $strings['ReminderSendtimeDate'] = 'تاريخ التذكير';
        $strings['ReminderSendtimeTime'] = 'وقت التذكير (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'إضافة تذكير';
        $strings['DeleteReminderWarning'] = 'هل أنت متأكد أنك تريد حذف هذا?';
        $strings['NoReminders'] = 'ليس لديك تذكيرات قادمة.';
        $strings['Reminders'] = 'تذكير';
        $strings['SendReminder'] = 'يرسل تذكير';
        $strings['minutes'] = 'دقائق';
        $strings['hours'] = 'ساعات';
        $strings['days'] = 'ايام';
        $strings['ReminderBeforeStart'] = 'قبل وقت البدء';
        $strings['ReminderBeforeEnd'] = 'قبل وقت الانتهاء';
        $strings['Logo'] = 'شعار';
        $strings['CssFile'] = 'CSS ملف';
        $strings['ThemeUploadSuccess'] = 'تم حفظ التغييرات. قم بتحديث الصفحة لتصبح التغييرات سارية المفعول.';
        $strings['MakeDefaultSchedule'] = 'اجعل هذا الجدول الزمني الافتراضي الخاص بي';
        $strings['DefaultScheduleSet'] = 'هذا هو الجدول الزمني الافتراضي الخاص بك الآن';
        $strings['FlipSchedule'] = 'اقلب تخطيط الجدول';
        $strings['Next'] = 'التالي';
        $strings['Success'] = 'نجاح';
        $strings['Participant'] = 'مشارك';
        $strings['ResourceFilter'] = 'عامل تصفية الموارد';
        $strings['ResourceGroups'] = 'مجموعات الموارد';
        $strings['AddNewGroup'] = 'إضافة مجموعة';
        $strings['Quit'] = 'خروج';
        $strings['AddGroup'] = 'إضافة مجموعة';
        $strings['StandardScheduleDisplay'] = 'استخدم عرض الجدول القياسي';
        $strings['TallScheduleDisplay'] = 'استخدم عرض الجدول الزمني الطويل';
        $strings['WideScheduleDisplay'] = 'استخدم عرض الجدول الزمني الواسع';
        $strings['CondensedWeekScheduleDisplay'] = 'استخدم عرض جدول الأسبوع المكثف';
        $strings['ResourceGroupHelp1'] = 'اسحب مجموعات الموارد وأفلتها لإعادة تنظيمها.';
        $strings['ResourceGroupHelp2'] = 'انقر بزر الماوس الأيمن فوق اسم مجموعة الموارد للحصول على إجراءات إضافية.';
        $strings['ResourceGroupHelp3'] = 'اسحب الموارد وأفلتها لإضافتها إلى المجموعات.';
        $strings['ResourceGroupWarning'] = 'في حالة استخدام مجموعات الموارد ، يجب تعيين كل مورد لمجموعة واحدة على الأقل. لن يتم حجز الموارد غير المعينة.';
        $strings['ResourceType'] = 'نوع المورد';
        $strings['AppliesTo'] = 'ينطبق على';
        $strings['UniquePerInstance'] = 'فريد لكل مثيل';
        $strings['AddResourceType'] = 'أضف نوع المورد';
        $strings['NoResourceTypeLabel'] = '(لم يتم تعيين نوع الموارد)';
        $strings['ClearFilter'] = 'الغاء التصفية';
        $strings['MinimumCapacity'] = 'ادنى سعة';
        $strings['Color'] = 'اللون';
        $strings['Available'] = 'متاح';
        $strings['Unavailable'] = 'غير متاح';
        $strings['Hidden'] = 'إخفاء';
        $strings['ResourceStatus'] = 'حالة المورد';
        $strings['CurrentStatus'] = 'الوضع الحالي';
        $strings['AllReservationResources'] = 'جميع موارد الحجز';
        $strings['File'] = 'ملف';
        $strings['BulkResourceUpdate'] = 'تحديث الموارد بالجملة';
        $strings['Unchanged'] = 'دون تغيير';
        $strings['Common'] = 'مشترك';
        $strings['AdminOnly'] = ' المسؤول فقط';
        $strings['AdvancedFilter'] = 'تصفية متقدمة';
        $strings['MinimumQuantity'] = 'الحد الأدنى من الكمية';
        $strings['MaximumQuantity'] = 'الكمية القصوى';
        $strings['ChangeLanguage'] = 'تغيير اللغة';
        $strings['AddRule'] = 'أضف قاعدة';
        $strings['Attribute'] = 'ينسب';
        $strings['RequiredValue'] = 'قيمة مطلوبة';
        $strings['ReservationCustomRuleAdd'] = 'استخدم هذا اللون عند تعيين سمة الحجز على القيمة التالية';
        $strings['AddReservationColorRule'] = 'أضف قاعدة لون الحجز';
        $strings['LimitAttributeScope'] = 'اجمع في حالات محددة';
        $strings['CollectFor'] = 'اجمع من أجل';
        $strings['SignIn'] = 'تسجيل الدخول';
        $strings['AllParticipants'] = 'كل المشاركين';
        $strings['RegisterANewAccount'] = 'تسجيل حساب جديد';
        $strings['Dates'] = 'التواريخ';
        $strings['More'] = 'اكثر';
        $strings['ResourceAvailability'] = 'توافر الموارد';
        $strings['UnavailableAllDay'] = 'غير متوفر طوال اليوم';
        $strings['AvailableUntil'] = 'متاح حتى';
        $strings['AvailableBeginningAt'] = 'متاح بدءًا من';
        $strings['AvailableAt'] = 'متواجد في';
        $strings['AllResourceTypes'] = 'جميع انواع الموارد';
        $strings['AllResourceStatuses'] = 'جميع حالات الموارد';
        $strings['AllowParticipantsToJoin'] = 'اسمح للمشاركين بالانضمام';
        $strings['Join'] = 'Join';
        $strings['YouAreAParticipant'] = 'أنت مشارك في هذا الحجز';
        $strings['YouAreInvited'] = 'أنت مدعو لهذا الحجز';
        $strings['YouCanJoinThisReservation'] = 'يمكنك الانضمام إلى هذا الحجز';
        $strings['Import'] = 'استيراد';
        $strings['GetTemplate'] = 'احصل على نموذج';
        $strings['UserImportInstructions'] = '<ul><li>يجب أن يكون الملف بتنسيق CSV.</li><li>حقل اسم المستخدم و البريد الألكتروني مطلوب.</li><li>لن يتم فرض صلاحية السمة.</li><li>سيؤدي ترك الحقول الأخرى فارغة إلى تعيين القيم الافتراضية و \'password\' كمستخدم \'s password.</li><li>استخدم القالب المقدم كمثال.</li></ul>';
        $strings['RowsImported'] = 'صفوف مستوردة';
        $strings['RowsSkipped'] = 'تم تخطي الصفوف';
        $strings['Columns'] = 'الأعمدة';
        $strings['Reserve'] = 'الاحتياطي';
        $strings['AllDay'] = 'جميع الايام';
        $strings['Everyday'] = 'كل يوم';
        $strings['IncludingCompletedReservations'] = 'بما في ذلك الحجوزات المكتملة';
        $strings['NotCountingCompletedReservations'] = 'لا يشمل الحجوزات المكتملة';
        $strings['RetrySkipConflicts'] = 'تخطي الحجوزات المتضاربة';
        $strings['Retry'] = 'أعد المحاولة';
        $strings['RemoveExistingPermissions'] = 'إزالة الأذونات الموجودة?';
        $strings['Continue'] = 'استمرار';
        $strings['WeNeedYourEmailAddress'] = 'نحتاج إلى عنوان بريدك الإلكتروني للحجز';
        $strings['ResourceColor'] = 'لون المورد';
        $strings['DateTime'] = 'تاريخ وقت ';
        $strings['AutoReleaseNotification'] = 'يتم إصداره تلقائيًا إذا لم يتم تسجيل الوصول فيه %s دقائق';
        $strings['RequiresCheckInNotification'] = 'يتطلب تسجيل الوصول / المغادرة';
        $strings['NoCheckInRequiredNotification'] = 'لا يتطلب إجراءات الدخول / المغادرة';
        $strings['RequiresApproval'] = 'يتطلب الموافقة';
        $strings['CheckingIn'] = 'التحقق من الدخول';
        $strings['CheckingOut'] = 'التحقق من الخروج';
        $strings['CheckIn'] = 'تسجيل الدخول';
        $strings['CheckOut'] = 'تسجيل الخروج ';
        $strings['ReleasedIn'] = 'ارجو ارفاق سيرتك الذاتية مع الرسالة';
        $strings['CheckedInSuccess'] = 'تم تسجيل وصولك ';
        $strings['CheckedOutSuccess'] = 'تم تسجيل خروجك ';
        $strings['CheckInFailed'] = 'لا يمكن ان يتم تسجيل الوصول ';
        $strings['CheckOutFailed'] = 'لا يمكن تسجيل الخروج ';
        $strings['CheckInTime'] = 'وقت الدخول';
        $strings['CheckOutTime'] = 'وقت الخروج ';
        $strings['OriginalEndDate'] = 'Original End';
        $strings['SpecificDates'] = 'عرض تواريخ محددة';
        $strings['Users'] = 'مستخدمون';
        $strings['Guest'] = 'زائرون';
        $strings['ResourceDisplayPrompt'] = 'مورد لعرضه';
        $strings['Credits'] = 'الاعتمادات';
        $strings['AvailableCredits'] = 'الاعتمادات المتاحة';
        $strings['CreditUsagePerSlot'] = 'يستوجب %s الاعتمادات لكل فتحة (خارج وقت الذروة)';
        $strings['PeakCreditUsagePerSlot'] = 'يستوجب %s الاعتمادات لكل فتحة (الذروة)';
        $strings['CreditsRule'] = 'ليس لديك ما يكفي من الاعتمادات. الاعتمادات المطلوبة: %s. الاعتمادات في الحساب: %s';
        $strings['PeakTimes'] = 'أوقات الذروة';
        $strings['AllYear'] = 'طوال العام';
        $strings['MoreOptions'] = 'المزيد من الخيارات ';
        $strings['SendAsEmail'] = 'إرسال بالبريد الإلكتروني';
        $strings['UsersInGroups'] = 'المستخدمون في المجموعات';
        $strings['UsersWithAccessToResources'] = 'المستخدمون الذين لديهم حق الوصول إلى الموارد';
        $strings['AnnouncementSubject'] = 'تم نشر إعلان جديد من قبل %s';
        $strings['AnnouncementEmailNotice'] = 'سيتم إرسال هذا الإعلان للمستخدمين عبر البريد الإلكتروني';
        $strings['Day'] = 'يوم';
        $strings['NotifyWhenAvailable'] = 'اعلمني عندما يكون متاح';
        $strings['AddingToWaitlist'] = 'إضافتك إلى قائمة الانتظار';
        $strings['WaitlistRequestAdded'] = 'سيتم إعلامك إذا أصبح هذا الوقت متاحًا';
        $strings['PrintQRCode'] = 'طباعة QR Code';
        $strings['FindATime'] = 'ابحث عن وقت';
        $strings['AnyResource'] = 'اي مورد';
        $strings['ThisWeek'] = 'هذا الاسبوع';
        $strings['Hours'] = 'ساعات';
        $strings['Minutes'] = 'دقائق';
        $strings['ImportICS'] = 'استيراد من ICS';
        $strings['ImportQuartzy'] = 'استيراد من  Quartzy';
        $strings['OnlyIcs'] = 'فقط هذا النوع *.ics من الملف تستطيع استيراده  .';
        $strings['IcsLocationsAsResources'] = 'سيتم استيراد المواقع كموارد.';
        $strings['IcsMissingOrganizer'] = 'سيتم تعيين المالك لأي حدث يفتقد إلى المنظم على المستخدم الحالي.';
        $strings['IcsWarning'] = 'لن يتم فرض قواعد الحجز - من الممكن حدوث تضارب وتكرار وما إلى ذلك.';
        $strings['BlackoutAroundConflicts'] = 'التعتيم حول الحجوزات المتضاربة';
        $strings['DuplicateReservation'] = 'تكرار';
        $strings['UnavailableNow'] = 'غير متاح الان';
        $strings['ReserveLater'] = 'احجز لاحقًا';
        $strings['CollectedFor'] = 'جمعت ل';
        $strings['IncludeDeleted'] = 'تضمين الحجوزات المحذوفة';
        $strings['Deleted'] = 'تم الحذف';
        $strings['Back'] = 'عودة';
        $strings['Forward'] = 'إلى الأمام';
        $strings['DateRange'] = 'نطاق الموعد';
        $strings['Copy'] = 'نسخ';
        $strings['Detect'] = 'الكشف';
        $strings['Autofill'] = 'تعبئة تلقائية';
        $strings['NameOrEmail'] = 'الاسم او البريد الألكتروني';
        $strings['ImportResources'] = 'استيراد مورد ';
        $strings['ExportResources'] = 'تصدير مورد';
        $strings['ResourceImportInstructions'] = '<ul><li>يجب أن يكون الملف بتنسيق CSV بترميز UTF-8.</li><li>الاسم حقل مطلوب. سيؤدي ترك الحقول الأخرى فارغة إلى تعيين القيم الافتراضية.</li><li> الخيارات المتاحة \'متاح\', \'غير متاح\' و \'مخفي\'.</li><li>يجب أن يكون اللون هو القيمة السداسية. على سبيل المثال) #ffffff.</li><li>يمكن أن تكون أعمدة التخصيص والموافقة التلقائية صحيحة أو خاطئة.</li><li>لن يتم فرض صلاحية السمة.</li><li>تفصل فاصلة مجموعات موارد متعددة.</li><li>استخدم القالب المقدم كمثال.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>يجب أن يكون الملف بتنسيق CSV بترميز UTF-8.</li><li>البريد الإلكتروني ، وأسماء الموارد ، والبدء ، والنهاية هي حقول مطلوبة.</li><li>تتطلب البداية والنهاية وقت التاريخ الكامل. التنسيق الموصى به هو YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>لن يتم التحقق من القواعد والتعارضات والفترات الزمنية الصالحة.</li><li>لن يتم إرسال الإخطارات.</li><li>لن يتم فرض صلاحية السمة.</li><li>تفصل الفاصلة بين أسماء الموارد المتعددة.</li><li>استخدم القالب المقدم كمثال.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'دقائق الإصدار التلقائي';
        $strings['CreditsPeak'] = 'الاعتمادات (الذروة)';
        $strings['CreditsOffPeak'] = 'الاعتمادات (خارج أوقات الذروة)';
        $strings['ResourceMinLengthCsv'] = 'الحد الأدنى لطول الحجز';
        $strings['ResourceMaxLengthCsv'] = 'الطول الأقصى للحجز';
        $strings['ResourceBufferTimeCsv'] = 'وقت العزل';
        $strings['ResourceMinNoticeAddCsv'] = 'الحد الأدنى من إشعار إضافة الحجز';
        $strings['ResourceMinNoticeUpdateCsv'] = 'إشعار الحد الأدنى لتحديث الحجز';
        $strings['ResourceMinNoticeDeleteCsv'] = 'إشعار حذف الحد الأدنى للحجز';
        $strings['ResourceMaxNoticeCsv'] = 'نهاية الحد الأقصى للحجز';
        $strings['Export'] = 'تصدير';
        $strings['DeleteMultipleUserWarning'] = 'سيؤدي حذف هؤلاء المستخدمين إلى إزالة جميع حجوزاتهم الحالية والمستقبلية والتاريخية. لن يتم إرسال رسائل بريد إلكتروني.';
        $strings['DeleteMultipleReservationsWarning'] = 'لن يتم إرسال رسائل بريد إلكتروني.';
        $strings['ErrorMovingReservation'] = 'خطأ في نقل الحجز';
        $strings['SelectUser'] = 'اختيار مستخدم ';
        $strings['InviteUsers'] = 'Invite Users';
        $strings['InviteUsersLabel'] = 'أدخل عناوين البريد الإلكتروني للأشخاص لدعوتهم';
        $strings['ApplyToCurrentUsers'] = 'تنطبق على المستخدمين الحاليين';
        $strings['ReasonText'] = 'السبب';
        $strings['NoAvailableMatchingTimes'] = 'لا توجد أوقات متاحة تطابق بحثك';
        $strings['Schedules'] = 'جداول';
        $strings['NotifyUser'] = 'إخطار المستخدم';
        $strings['UpdateUsersOnImport'] = 'تحديث المستخدم الحالي إذا كان البريد الإلكتروني موجود بالفعل';
        $strings['UpdateResourcesOnImport'] = 'تحديث الموارد الموجودة إذا كان الاسم موجودًا بالفعل';
        $strings['Reject'] = 'مرفوض';
        $strings['CheckingAvailability'] = 'التحقق من التوفر';
        $strings['CreditPurchaseNotEnabled'] = 'لم تقم بتمكين القدرة على شراء الاعتمادات';
        $strings['CreditsCost'] = 'كل تكاليف الائتمان';
        $strings['Currency'] = 'العملة';
        $strings['PayPalClientId'] = 'معرف العميل';
        $strings['PayPalSecret'] = 'السر';
        $strings['PayPalEnvironment'] = 'بيئة';
        $strings['Sandbox'] = 'Sandbox';
        $strings['Live'] = 'مباشر';
        $strings['StripePublishableKey'] = 'مفتاح قابل للنشر';
        $strings['StripeSecretKey'] = 'مفتاح الامان ';
        $strings['CreditsUpdated'] = 'تم تحديث تكلفة الائتمان';
        $strings['GatewaysUpdated'] = 'تم تحديث بوابات الدفع';
        $strings['PurchaseSummary'] = 'ملخص شراء';
        $strings['EachCreditCosts'] = 'كل تكاليف الائتمان';
        $strings['Checkout'] = 'الدفع';
        $strings['Quantity'] = 'الكمية';
        $strings['CreditPurchase'] = 'شراء الائتمان';
        $strings['EmptyCart'] = 'بطاقتك فارغه.';
        $strings['BuyCredits'] = 'شراء إئتمانات';
        $strings['CreditsPurchased'] = 'شراء الاعتمادات';
        $strings['ViewYourCredits'] = 'عرض اعتماداتك';
        $strings['TryAgain'] = 'محاولة مرة اخرى ';
        $strings['PurchaseFailed'] = 'لقد واجهتنا مشكلة في معالجة دفعتك.';
        $strings['NoteCreditsPurchased'] = 'شراء الاعتمادات';
        $strings['CreditsUpdatedLog'] = 'تم تحديث الاعتمادات بواسطة %s';
        $strings['ReservationCreatedLog'] = 'تم إنشاء الحجز. رقم المرجع %s';
        $strings['ReservationUpdatedLog'] = 'تم تحديث الحجز. رقم المرجع %s';
        $strings['ReservationDeletedLog'] = 'تم حذف الحجز. رقم المرجع %s';
        $strings['BuyMoreCredits'] = 'شراء المزيد من الاعتمادات';
        $strings['Transactions'] = 'العمليات';
        $strings['Cost'] = 'التكلفة';
        $strings['PaymentGateways'] = 'بوباة الدفع';
        $strings['CreditHistory'] = 'تاريخ الرصيد';
        $strings['TransactionHistory'] = 'تاريخ العمليات';
        $strings['Date'] = 'تاريخ';
        $strings['Note'] = 'ملاحظة';
        $strings['CreditsBefore'] = 'الاعتمادات قبل';
        $strings['CreditsAfter'] = 'الاعتمادات بعد';
        $strings['TransactionFee'] = 'العمليات المجانية';
        $strings['InvoiceNumber'] = 'رقم الفاتورة';
        $strings['TransactionId'] = 'رقم العملية';
        $strings['Gateway'] = 'البوابة';
        $strings['GatewayTransactionDate'] = 'تاريخ عملية البوابة';
        $strings['Refund'] = 'إسترجاع ';
        $strings['IssueRefund'] = 'استرداد القضية';
        $strings['RefundIssued'] = 'استرداد القضية بنجاح ';
        $strings['RefundAmount'] = 'مبلغ الاستعادة';
        $strings['AmountRefunded'] = 'إسترجاع';
        $strings['FullyRefunded'] = 'إسترجاع بالكامل';
        $strings['YourCredits'] = 'الاعتمادات الخاصة بك';
        $strings['PayWithCard'] = 'الدفع بالبطاقة';
        $strings['or'] = 'أو';
        $strings['CreditsRequired'] = 'قروض مطلوبة';
        $strings['AddToGoogleCalendar'] = 'أضف إلى Google';
        $strings['Image'] = 'صورة';
        $strings['ChooseOrDropFile'] = 'اختر ملفًا أو اسحبه هنا';
        $strings['SlackBookResource'] = 'احجز %s الأن';
        $strings['SlackBookNow'] = 'احجز الان';
        $strings['SlackNotFound'] = 'لم نتمكن من العثور على مورد بهذا الاسم. احجز الآن لبدء حجز جديد.';
        $strings['AutomaticallyAddToGroup'] = 'إضافة مستخدمين جدد إلى هذه المجموعة تلقائيًا';
        $strings['GroupAutomaticallyAdd'] = 'إضافة تلقائية';
        $strings['TermsOfService'] = 'شروط الخدمة';
        $strings['EnterTermsManually'] = 'أدخل الشروط يدويًا';
        $strings['LinkToTerms'] = 'رابط للشروط';
        $strings['UploadTerms'] = 'شروط التحميل';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'يتطلب إقرار شروط الخدمة';
        $strings['UponReservation'] = 'عند الحجز';
        $strings['UponRegistration'] = 'عند التسجيل';
        $strings['ViewTerms'] = 'عرض شروط الخدمة';
        $strings['IAccept'] = 'انا موافق';
        $strings['TheTermsOfService'] = 'شروط الخدمة';
        $strings['DisplayPage'] = 'عرض الصفحة';
        $strings['AvailableAllYear'] = 'طوال السنة';
        $strings['Availability'] = 'المتوافر';
        $strings['AvailableBetween'] = 'المتوافر بين ';
        $strings['ConcurrentYes'] = 'يمكن لأكثر من شخص حجز الموارد في وقت واحد';
        $strings['ConcurrentNo'] = 'لا يمكن لأكثر من شخص حجز الموارد في نفس الوقت';
        $strings['ScheduleAvailabilityEarly'] = ' هذا الجدول غير متاح بعد. كان متوفرا';
        $strings['ScheduleAvailabilityLate'] = 'هذا الجدول لم يعد متاحا. كانت متوفرة';
        $strings['ResourceImages'] = 'صور الموارد';
        $strings['FullAccess'] = 'كامل الصلاحيات ';
        $strings['ViewOnly'] = 'العرض فقط';
        $strings['Purge'] = 'تنظيف';
        $strings['UsersWillBeDeleted'] = 'سيتم حذف المستخدمين';
        $strings['BlackoutsWillBeDeleted'] = 'سيتم حذف أوقات التعتيم';
        $strings['ReservationsWillBePurged'] = 'سيتم تنظيف الحجوزات';
        $strings['ReservationsWillBeDeleted'] = 'سيتم حذف الحجوزات';
        $strings['PermanentlyDeleteUsers'] = 'حذف المستخدمين الذين لم يقوموا بتسجيل الدخول منذ ذلك الحين نهائيًا';
        $strings['DeleteBlackoutsBefore'] = 'حذف مرات الحجب من قبل';
        $strings['DeletedReservations'] = 'الحجوزات المحذوفة';
        $strings['DeleteReservationsBefore'] = 'حذف الحجوزات من قبل';
        $strings['SwitchToACustomLayout'] = 'قم بالتبديل إلى تنسيق مخصص';
        $strings['SwitchToAStandardLayout'] = 'قم بالتبديل إلى التخطيط القياسي';
        $strings['ThisScheduleUsesACustomLayout'] = 'يستخدم هذا الجدول الزمني تخطيطًا مخصصًا';
        $strings['ThisScheduleUsesAStandardLayout'] = 'This schedule uses a standard layout';
        $strings['SwitchLayoutWarning'] = 'هل أنت متأكد أنك تريد تغيير نوع التخطيط؟ سيؤدي هذا إلى إزالة جميع الفتحات الموجودة.';
        $strings['DeleteThisTimeSlot'] = 'احذف هذه الفترة الزمنية?';
        $strings['Refresh'] = 'تحديث';
        $strings['ViewReservation'] = 'عرض الحجز';
        $strings['PublicId'] = 'معرف عام';
        $strings['Public'] = 'عام';
        $strings['AtomFeedTitle'] = '%s حجز';
        $strings['DefaultStyle'] = 'أسلوب إفتراضي';
        $strings['Standard'] = 'اساسي';
        $strings['Wide'] = 'واسع';
        $strings['Tall'] = 'طويل';
        $strings['EmailTemplate'] = 'نماذج قوالب';
        $strings['SelectEmailTemplate'] = 'حدد قالب البريد الإلكتروني';
        $strings['ReloadOriginalContents'] = 'إعادة تحميل المحتويات الأصلية';
        $strings['UpdateEmailTemplateSuccess'] = 'قالب البريد الإلكتروني المحدث';
        $strings['UpdateEmailTemplateFailure'] = 'تعذر تحديث قالب البريد الإلكتروني. تحقق للتأكد من أن الدليل قابل للكتابة.';
        $strings['BulkResourceDelete'] = 'حذف الموارد بالجملة';
        $strings['NewVersion'] = 'نسخة جديدة!';
        $strings['WhatsNew'] = 'ما هو الجديد؟';
        $strings['OnlyViewedCalendar'] = 'لا يمكن عرض هذا الجدول الزمني إلا من عرض التقويم';
        $strings['Grid'] = 'شبكة';
        $strings['List'] = 'قائمة';
        $strings['NoReservationsFound'] = 'لا توجد حجوزات';
        $strings['EmailReservation'] = 'الحجز عبر البريد الإلكتروني';
        $strings['AdHocMeeting'] = 'اجتماع خاص';
        $strings['NextReservation'] = 'الحجز التالي';
        $strings['MissedCheckin'] = 'لم يتم تسجيل الوصول';
        $strings['MissedCheckout'] = 'غاب عن الخروج';
        $strings['Utilization'] = 'استغلال';
        $strings['SpecificTime'] = 'وقت محدد';
        $strings['ReservationSeriesEndingPreference'] = 'عندما تنتهي سلسلة الحجز المتكرر';
        $strings['NotAttending'] = 'عدم حضوره';
        $strings['ViewAvailability'] = 'مشاهدة التوفر';
        $strings['ReservationDetails'] = 'تفاصيل الحجز';
        $strings['StartTime'] = 'وقت البدء';
        $strings['EndTime'] = 'وقت النهاية';
        $strings['New'] = 'جديد';
        $strings['Updated'] = 'تحديث';
        $strings['Custom'] = 'مخصص';
        $strings['AddDate'] = 'إضافة تاريخ';
        $strings['RepeatOn'] = 'كرر في';
        $strings['ScheduleConcurrentMaximum'] = 'ما مجموعه <b>%s</b> قد يتم حجز الموارد بشكل متزامن';
        $strings['ScheduleConcurrentMaximumNone'] = 'لا يوجد حد لعدد الموارد المحجوزة المتزامنة';
        $strings['ScheduleMaximumConcurrent'] = 'العدد الأقصى من الموارد المحجوزة في نفس الوقت';
        $strings['ScheduleMaximumConcurrentNote'] = 'عند التعيين ، سيكون العدد الإجمالي للموارد التي يمكن حجزها بشكل متزامن لهذا الجدول محدودًا.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'كل حجز محدد بحد أقصى <b>%s</b> مصادر';
        $strings['ScheduleResourcesPerReservationNone'] = 'لا يوجد حد لعدد الموارد لكل حجز';
        $strings['ScheduleResourcesPerReservation'] = 'الحد الأقصى لعدد الموارد لكل حجز';
        $strings['ResourceConcurrentReservations'] = 'السماح %s الحجوزات المتزامنة';
        $strings['ResourceConcurrentReservationsNone'] = 'لا تسمح بالحجوزات المتزامنة';
        $strings['AllowConcurrentReservations'] = 'السماح بالحجوزات المتزامنة';
        $strings['ResourceDisplayInstructions'] = 'لم يتم اختيار أي مورد. يمكنك العثور على عنوان URL لعرض مورد في إدارة التطبيقات ، الموارد. يجب أن يكون المورد متاحًا للجمهور.';
        $strings['Owner'] = 'صاحب';
        $strings['MaximumConcurrentReservations'] = 'الحد الأقصى للحجوزات المتزامنة';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Install LibreBooking';
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
        $strings['LoginError'] = 'لم نتمكن من مطابقة اسم المستخدم أو كلمة المرور الخاصة بك';
        $strings['ReservationFailed'] = 'لا يمكن إجراء الحجز الخاص بك';
        $strings['MinNoticeError'] = 'يتطلب هذا الحجز إشعارًا مسبقًا. أقرب تاريخ ووقت يمكن حجزهما هو %s.';
        $strings['MinNoticeErrorUpdate'] = 'يتطلب تغيير هذا الحجز إشعارًا مسبقًا. الحجوزات من قبل %s لا يسمح بتغييرها.';
        $strings['MinNoticeErrorDelete'] = 'يتطلب حذف هذا الحجز إشعارًا مسبقًا. الحجوزات من قبل %s لا يسمح لحذفها.';
        $strings['MaxNoticeError'] = 'لا يمكن إجراء هذا الحجز إلى هذا الحد في المستقبل. آخر تاريخ ووقت يمكن حجزهما هو %s.';
        $strings['MinDurationError'] = 'يجب أن يستمر هذا الحجز على الأقل %s.';
        $strings['MaxDurationError'] = 'لا يمكن أن يستمر هذا الحجز لفترة أطول من %s.';
        $strings['ConflictingAccessoryDates'] = 'لا يوجد ما يكفي من الملحقات التالية:';
        $strings['NoResourcePermission'] = 'ليس لديك إذن للوصول إلى واحد أو أكثر من الموارد المطلوبة.';
        $strings['ConflictingReservationDates'] = 'توجد حجوزات متضاربة في التواريخ التالية:';
        $strings['InstancesOverlapRule'] = 'تتداخل بعض حالات سلسلة الحجز:';
        $strings['StartDateBeforeEndDateRule'] = 'يجب أن يكون تاريخ ووقت البدء قبل تاريخ ووقت الانتهاء.';
        $strings['StartIsInPast'] = 'لا يمكن أن يكون تاريخ ووقت البدء في الماضي.';
        $strings['EmailDisabled'] = 'قام المسؤول بتعطيل إشعارات البريد الإلكتروني.';
        $strings['ValidLayoutRequired'] = 'يجب توفير الخانات الزمنية لجميع الـ 24 ساعة من اليوم التي تبدأ وتنتهي في 00:00.';
        $strings['CustomAttributeErrors'] = 'توجد مشكلات في السمات الإضافية التي قدمتها:';
        $strings['CustomAttributeRequired'] = '%s هو حقل مطلوب.';
        $strings['CustomAttributeInvalid'] = 'القيمة المقدمة ل %s غير صالح.';
        $strings['AttachmentLoadingError'] = 'عذرا ، كانت هناك مشكلة في تحميل الملف المطلوب.';
        $strings['InvalidAttachmentExtension'] = 'يمكنك فقط تحميل الملفات من النوع: %s';
        $strings['InvalidStartSlot'] = 'تاريخ البدء والوقت المطلوب غير صالحين.';
        $strings['InvalidEndSlot'] = 'تاريخ ووقت الانتهاء المطلوب غير صالحين.';
        $strings['MaxParticipantsError'] = '%s يمكن أن تدعم فقط %s المشاركين.';
        $strings['ReservationCriticalError'] = 'كان هناك خطأ فادح في حفظ الحجز الخاص بك. إذا استمر هذا ، اتصل بمسؤول النظام.';
        $strings['InvalidStartReminderTime'] = 'وقت تذكير البدء غير صالح.';
        $strings['InvalidEndReminderTime'] = 'وقت تذكير الانتهاء غير صالح.';
        $strings['QuotaExceeded'] = 'تم تجاوز حد الحصة النسبية.';
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
        $strings['TermsOfServiceError'] = 'You must accept the Terms of Service';
        $strings['UserNotFound'] = 'That user could not be found';
        $strings['ScheduleAvailabilityError'] = 'This schedule is available between %s and %s';
        $strings['ReservationNotFoundError'] = 'Reservation not found';
        $strings['ReservationNotAvailable'] = 'Reservation not available';
        $strings['TitleRequiredRule'] = 'Reservation title is required';
        $strings['DescriptionRequiredRule'] = 'Reservation description is required';
        $strings['WhatCanThisGroupManage'] = 'What can this group manage?';
        $strings['ReservationParticipationActivityPreference'] = 'When someone joins or leaves my reservation';
        $strings['RegisteredAccountRequired'] = 'Only registered users can book reservations';
        $strings['InvalidNumberOfResourcesError'] = 'The maximum number of resources that can be reserved in a single reservation is %s';
        $strings['ScheduleTotalReservationsError'] = 'This schedule only allows %s resources to be reserved concurrently. This reservation would violate that limit on the following dates:';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'انشاء حجز';
        $strings['EditReservation'] = 'تحديث حجز';
        $strings['LogIn'] = 'تسجيل الدخول';
        $strings['ManageReservations'] = 'إدارة الحجوزات';
        $strings['AwaitingActivation'] = 'في انتظار التفعيل';
        $strings['PendingApproval'] = 'ما زال يحتاج بتصدير';
        $strings['ManageSchedules'] = 'جداول';
        $strings['ManageResources'] = 'الموارد';
        $strings['ManageAccessories'] = 'الإضافات';
        $strings['ManageUsers'] = 'مستخدمين';
        $strings['ManageGroups'] = 'المجموعات';
        $strings['ManageQuotas'] = 'الحصص';
        $strings['ManageBlackouts'] = 'بلاك اوت تايمز';
        $strings['MyDashboard'] = 'لوحة القيادة الخاصة بي';
        $strings['ServerSettings'] = 'اعدادات الخادم';
        $strings['Dashboard'] = 'لوحة القيادة';
        $strings['Help'] = 'مساعدة';
        $strings['Administration'] = 'الادارة';
        $strings['About'] = 'عن';
        $strings['Bookings'] = 'حجز';
        $strings['Schedule'] = 'جدولة';
        $strings['Account'] = 'الحساب';
        $strings['EditProfile'] = 'تعديل الملف الشخصي';
        $strings['FindAnOpening'] = 'ابحث عن مفتوح';
        $strings['OpenInvitations'] = 'افتح الدعوات';
        $strings['ResourceCalendar'] = 'تقويم الموارد';
        $strings['Reservation'] = 'حجز جديد';
        $strings['Install'] = 'التركيب';
        $strings['ChangePassword'] = 'تغيير الباسورد';
        $strings['MyAccount'] = 'حسابي';
        $strings['Profile'] = 'الملف الشخصي';
        $strings['ApplicationManagement'] = 'ادارة التطبيقات ';
        $strings['ForgotPassword'] = 'نسيت كلمة المرور';
        $strings['NotificationPreferences'] = 'تفضيلات الإخطار';
        $strings['ManageAnnouncements'] = 'الإعلانات';
        $strings['Responsibilities'] = 'المسؤوليات';
        $strings['GroupReservations'] = 'حجوزات المجموعة';
        $strings['ResourceReservations'] = 'حجوزات الموارد';
        $strings['Customization'] = 'التخصيص';
        $strings['Attributes'] = 'السمات';
        $strings['AccountActivation'] = 'تفعيل الحساب';
        $strings['ScheduleReservations'] = 'جدولة الحجوزات';
        $strings['Reports'] = 'التقارير';
        $strings['GenerateReport'] = 'انشاء تقرير جديد';
        $strings['MySavedReports'] = 'تقاريري المحفوظه';
        $strings['CommonReports'] = 'التقارير المشتركة';
        $strings['ViewDay'] = 'عرض يوم ';
        $strings['Group'] = 'المجموعة';
        $strings['ManageConfiguration'] = 'إعداد التطبقيات ';
        $strings['LookAndFeel'] = 'الشعار و الثيم';
        $strings['ManageResourceGroups'] = 'مجموعات الموارد';
        $strings['ManageResourceTypes'] = 'انواع الموارد';
        $strings['ManageResourceStatus'] = 'وضع الموارد';
        $strings['ReservationColors'] = 'الوان الحجوزات ';
        $strings['SearchReservations'] = 'بحث عن الحجوزات';
        $strings['ManagePayments'] = 'المدفوعات';
        $strings['ViewCalendar'] = 'عرض التقويم';
        $strings['DataCleanup'] = 'تنظيف البيانات';
        $strings['ManageEmailTemplates'] = 'إدارة قوالب البريد الإلكتروني';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'S';
        $strings['DayMondaySingle'] = 'M';
        $strings['DayTuesdaySingle'] = 'T';
        $strings['DayWednesdaySingle'] = 'W';
        $strings['DayThursdaySingle'] = 'T';
        $strings['DayFridaySingle'] = 'F';
        $strings['DaySaturdaySingle'] = 'S';

        $strings['DaySundayAbbr'] = 'Sun';
        $strings['DayMondayAbbr'] = 'Mon';
        $strings['DayTuesdayAbbr'] = 'Tue';
        $strings['DayWednesdayAbbr'] = 'Wed';
        $strings['DayThursdayAbbr'] = 'Thu';
        $strings['DayFridayAbbr'] = 'Fri';
        $strings['DaySaturdayAbbr'] = 'Sat';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Your Reservation Has Been Approved';
        $strings['ReservationCreatedSubject'] = 'Your Reservation Was Created';
        $strings['ReservationUpdatedSubject'] = 'Your Reservation Was Updated';
        $strings['ReservationDeletedSubject'] = 'Your Reservation Was Removed';
        $strings['ReservationCreatedAdminSubject'] = 'Notification: A Reservation Was Created';
        $strings['ReservationUpdatedAdminSubject'] = 'Notification: A Reservation Was Updated';
        $strings['ReservationDeleteAdminSubject'] = 'Notification: A Reservation Was Removed';
        $strings['ReservationApprovalAdminSubject'] = 'Notification: Reservation Requires Your Approval';
        $strings['ParticipantAddedSubject'] = 'Reservation Participation Notification';
        $strings['ParticipantDeletedSubject'] = 'Reservation Removed';
        $strings['InviteeAddedSubject'] = 'Reservation Invitation';
        $strings['ResetPasswordRequest'] = 'Password Reset Request';
        $strings['ActivateYourAccount'] = 'Please Activate Your Account';
        $strings['ReportSubject'] = 'Your Requested Report (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Reservation for %s is starting soon';
        $strings['ReservationEndingSoonSubject'] = 'Reservation for %s is ending soon';
        $strings['UserAdded'] = 'A new user has been added';
        $strings['UserDeleted'] = 'User account for %s was deleted by %s';
        $strings['GuestAccountCreatedSubject'] = 'Your %s account details';
        $strings['AccountCreatedSubject'] = 'Your %s account details';
        $strings['InviteUserSubject'] = '%s has invited you to join %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Reservation Has Been Approved for %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Reservation Created for %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Reservation Updated for %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Reservation Removed for %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Notification: Reservation Created for %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Notification: Reservation Updated for %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Notification: Reservation Removed for %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Notification: Reservation for %s Requires Your Approval';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Added You to a Reservation for %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s Removed a Reservation for %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s Invited You to a Reservation for %s';
        $strings['MissedCheckinEmailSubject'] = 'Missed checkin for %s';
        $strings['ReservationShareSubject'] = '%s Shared a Reservation for %s';
        $strings['ReservationSeriesEndingSubject'] = 'Reservation Series for %s is Ending on %s';
        $strings['ReservationParticipantAccept'] = '%s Has Accepted Your Reservation Invitation for %s on %s';
        $strings['ReservationParticipantDecline'] = '%s Has Declined Your Reservation Invitation for %s on %s';
        $strings['ReservationParticipantJoin'] = '%s Has Joined Your Reservation for %s on %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'ليس لديك حجوزات سابقة';
        $strings['PastReservations'] = 'الحجوزات السابقة';
        $strings['AllNoPastReservations'] = 'لا توجد حجوزات سابقة في الـ %s الأيام السابقة';
        $strings['AllPastReservations'] = 'كل الحجوزات السابقة';
        $strings['Yesterday'] = 'أمس';
        $strings['EarlierThisWeek'] = 'في وقت سابق من هذا الأسبوع';
        $strings['PreviousWeek'] = 'الأسبوع السابق';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'ليس لديكم مجموعة قادمة للحجز';
        $strings['GroupUpcomingReservations'] = 'الحجوزات القادمة لمجموعتي';
        //End Group Upcoming Reservations 

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'حدث خطأ أثناء تسجيل الدخول باستخدام فيسبوك. يرجى المحاولة مرة أخرى.';
        //End Facebook Login SDK Error


        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'ليس لديك حجوزات في انتظار الموافقة';
        $strings['PendingApprovalReservations'] = 'الحجوزات قيد الموافقة';
        $strings['LaterThisMonth'] = 'في وقت لاحق هذا الشهر';
        $strings['LaterThisYear'] = 'في وقت لاحق هذا العام';
        $strings['Remaining'] = 'المتبقي';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'لا توجد حجوزات للتسجيل الخروج المفقودة';
        $strings['MissingCheckOutReservations'] = 'الحجوزات المفقودة للتسجيل الخروج';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'لا يمكن رؤية تفاصيل الحجز لأن ليس لديك أذونات لأي من الموارد في هذا الحجز';
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
        $days['full'] = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        // The three letter abbreviation
        $days['abbr'] = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
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
        $months = [];

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
