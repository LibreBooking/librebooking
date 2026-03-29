<?php

require_once('Language.php');
require_once('en_gb.php');

class th_th extends en_gb
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

        /**
            * Additional code to support the Thai and Buddhist calendar year
            * Also encountered problems in the display of the Thai day in $dates['res_popup'] & $dates['dashboard'] Welcome suggestions
            * Code เพิ่มเติมเพื่อสนับสนุนปฏิทินไทยและพุทธศักราช
            * ยังพบปัญหาในการแสดงวันภาษาไทยใน $dates['res_popup'] และ $dates['dashboard'] ยินดีรับคำแนะนำ
        */


        $dates['general_date'] = 'd/m/'.(date('Y') + 543);
        $dates['general_datetime'] = 'd/m/'.(date('Y') + 543).' H:i:s';
        $dates['short_datetime'] = 'd/m/'.(date('Y') + 543).' H:i';
        $dates['schedule_daily'] = 'l, d/m/'.(date('Y') + 543);
        $dates['reservation_email'] = 'd/m/'.(date('Y') + 543).' @ H:i (e)';
        $dates['res_popup'] = 'd/m/'.(date('Y') + 543).' H:i'; //
        $dates['res_popup_time'] = 'H:i';
        $dates['short_reservation_date'] = 'd/m/'.(date('Y') + 543).' H:i';
        $dates['dashboard'] = 'd/m/'.(date('Y') + 543).' H:i';
        //$dates['dashboard'] = $this->_LoadDayThai(date('D')).', d/m/'.(date('Y')+543).' H:i'; // Test Display Thai Day in Dashboard
        $dates['period_time'] = 'H:i';
        $dates['mobile_reservation_date'] = 'd/m/'.(date('Y') + 543).' H:i';
        $dates['general_date_js'] = 'dd/mm/'.(date('Y') + 543);
        $dates['momentjs_datetime'] = 'D/M/'.(date('Y') + 543).' h:mm A';
        $dates['calendar_time'] = 'h:mmt';
        $dates['calendar_dates'] = 'd/M/'.(date('Y') + 543);

        $this->Dates = $dates;

        return $this->Dates;
    }

    /* Function Test Display Thai Day in Dashboard
    protected function _LoadDayThai($daythai=null){
                $days_en = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $days_thai = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
                $day_thai = '';
                foreach($days_en as $key => $values){
                        if($days_en[$key] == $daythai){
                                    $day_thai = $days_thai [$key];
                        }
                }

        return $day_thai;
    }
    */

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'ชื่อ';
        $strings['LastName'] = 'นามสกุล';
        $strings['Timezone'] = 'เขตเวลา';
        $strings['Edit'] = 'แก้ไข';
        $strings['Change'] = 'เปลี่ยน';
        $strings['Rename'] = 'แก้ไขชื่อ';
        $strings['Remove'] = 'นำออก';
        $strings['Delete'] = 'ลบ';
        $strings['Update'] = 'อัพเดท';
        $strings['Cancel'] = 'ยกเลิก';
        $strings['Add'] = 'เพิ่ม';
        $strings['Name'] = 'ชื่อ';
        $strings['Yes'] = 'ใช่';
        $strings['No'] = 'ไม่ใช่';
        $strings['FirstNameRequired'] = 'จำเป็นต้องระบุชื่อ';
        $strings['LastNameRequired'] = 'จำเป็นต้องระบุนามสกุล';
        $strings['PwMustMatch'] = 'การยืนยันรหัสผ่านต้องตรงกับรหัสผ่าน';
        $strings['ValidEmailRequired'] = 'จำเป็นต้องใช้อีเมล์ที่ถูกต้อง';
        $strings['UniqueEmailRequired'] = 'อีเมล์นี้ได้ทำการลงทะเบียนไว้แล้ว';
        $strings['UniqueUsernameRequired'] = 'ชื่อผู้ใช้นี้ได้ทำการลงทะเบียนไว้แล้ว';
        $strings['UserNameRequired'] = 'จำเป็นต้องระบุชื่อผู้ใช้';
        $strings['CaptchaMustMatch'] = 'กรุณาป้อนตัวอักษรจากภาพรักษาความปลอดภัยที่แสดง';
        $strings['Today'] = 'วันนี้';
        $strings['Week'] = 'สัปดาห์';
        $strings['Month'] = 'เดือน';
        $strings['BackToCalendar'] = 'ย้อนกลับไปที่ปฏิทิน';
        $strings['BeginDate'] = 'เริ่ม';
        $strings['EndDate'] = 'สิ้นสุด';
        $strings['Username'] = 'ชื่อผู้ใช้';
        $strings['Password'] = 'รหัสผ่าน';
        $strings['PasswordConfirmation'] = 'ยืนยันรหัสผ่าน';
        $strings['DefaultPage'] = 'หน้าโฮมเพจตั้งต้น';
        $strings['MyCalendar'] = 'ปฏิทินของฉัน';
        $strings['ScheduleCalendar'] = 'ปฏิทินกำหนดการ';
        $strings['Registration'] = 'ลงทะเบียน';
        $strings['NoAnnouncements'] = 'ขณะนี้ยังไม่มีประกาศ';
        $strings['Announcements'] = 'ประกาศ';
        $strings['NoUpcomingReservations'] = 'คุณยังไม่มีการจองที่จะมาถึง';
        $strings['UpcomingReservations'] = 'การจองที่กำลังจะมาถึง';
        $strings['AllNoUpcomingReservations'] = 'ไม่มีการจองที่กำลังจะมาถึงใน %s วัน';
        $strings['AllUpcomingReservations'] = 'การจองที่กำลังจะมาถึงทั้งหมด';
        $strings['ShowHide'] = 'แสดง/ซ่อน';
        $strings['Error'] = 'ผิดพลาด';
        $strings['ReturnToPreviousPage'] = 'กลับไปยังหน้าล่าสุดที่คุณเพิ่งมา';
        $strings['UnknownError'] = 'เกิดข้อผิดพลาดที่ไม่รู้จัก';
        $strings['InsufficientPermissionsError'] = 'คุณไม่ได้รับอนุญาตให้เข้าถึงทรัพยากรนี้';
        $strings['MissingReservationResourceError'] = 'ยังไม่ได้เลือกทรัพยากร';
        $strings['MissingReservationScheduleError'] = 'ยังไม่ได้เลือกตารางการจอง';
        $strings['DoesNotRepeat'] = 'ไม่มีการเกิดขึ้นซ้ำ';
        $strings['Daily'] = 'ทุกวัน';
        $strings['Weekly'] = 'ทุกสัปดาห์';
        $strings['Monthly'] = 'ทุกเดือน';
        $strings['Yearly'] = 'ทุกปี';
        $strings['RepeatPrompt'] = 'การซ้ำ';
        $strings['hours'] = 'ชั่วโมง';
        $strings['days'] = 'วัน';
        $strings['weeks'] = 'สัปดาห์';
        $strings['months'] = 'เดือน';
        $strings['years'] = 'ปี';
        $strings['day'] = 'วัน';
        $strings['week'] = 'สัปดาห์';
        $strings['month'] = 'เดือน';
        $strings['year'] = 'ปี';
        $strings['repeatDayOfMonth'] = 'วันของเดือน';
        $strings['repeatDayOfWeek'] = 'วันของสัปดาห์';
        $strings['RepeatUntilPrompt'] = 'จนถึง';
        $strings['RepeatEveryPrompt'] = 'ทุก ๆ ';
        $strings['RepeatDaysPrompt'] = 'บน';
        $strings['CreateReservationHeading'] = 'การจองใหม่';
        $strings['EditReservationHeading'] = 'แก้ไขการจอง %s';
        $strings['ViewReservationHeading'] = 'ดูการจอง %s';
        $strings['ReservationErrors'] = 'ปรับแก้การจอง';
        $strings['Create'] = 'สร้าง';
        $strings['ThisInstance'] = 'เฉพาะครั้งนี้';
        $strings['AllInstances'] = 'ทุก ๆ ครั้ง';
        $strings['FutureInstances'] = 'ครั้งในอนาคต';
        $strings['Print'] = 'พิมพ์';
        $strings['ShowHideNavigation'] = 'แสดง/ซ่อน แถบนำทาง';
        $strings['ReferenceNumber'] = 'หมายเลขอ้างอิง';
        $strings['Tomorrow'] = 'พรุ่งนี้';
        $strings['LaterThisWeek'] = 'สัปดาห์ก่อนหน้านี้';
        $strings['NextWeek'] = 'สัปดาห์หน้า';
        $strings['SignOut'] = 'ออกจากระบบ';
        $strings['LayoutDescription'] = 'เริ่มบน %s, แสดง %s วัน ณ ขณะนี้';
        $strings['AllResources'] = 'ทรัพยากรทั้งหมด';
        $strings['TakeOffline'] = 'ทำเป็นออฟไลน์';
        $strings['BringOnline'] = 'ทำเป็นออนไลน์';
        $strings['AddImage'] = 'เพิ่มรูปภาพ';
        $strings['NoImage'] = 'ยังไม่มีภาพประกอบ ';
        $strings['Move'] = 'ย้าย';
        $strings['AppearsOn'] = 'ปรากฎอยู่บน %s';
        $strings['Location'] = 'สถานที่';
        $strings['NoLocationLabel'] = '(ไม่ได้ตั้งค่าสถานที่)';
        $strings['Contact'] = 'ติดต่อ';
        $strings['NoContactLabel'] = '(ไม่มีข้อมูลการติดต่อ)';
        $strings['Description'] = 'รายละเอียด';
        $strings['NoDescriptionLabel'] = '(ไม่มีรายละเอียด)';
        $strings['Notes'] = 'บันทึกช่วยจำ';
        $strings['NoNotesLabel'] = '(ไม่มีบันทึกช่วยจำ)';
        $strings['NoTitleLabel'] = '(ไม่มีชื่อ)';
        $strings['UsageConfiguration'] = 'การกำหนดค่าการใช้งาน';
        $strings['ChangeConfiguration'] = 'เปลี่ยนการกำหนดค่า';
        $strings['ResourceMinLength'] = ' การจองต้องมีอายุอย่างน้อย %s';
        $strings['ResourceMinLengthNone'] = 'ไม่มีระยะเวลาการจองขั้นต่ำ';
        $strings['ResourceMaxLength'] = 'การจองไม่สามารถมีมากกว่า %s';
        $strings['ResourceMaxLengthNone'] = 'ไม่มีระยะเวลาการจองสูงสุด';
        $strings['ResourceRequiresApproval'] = 'การจองต้องได้รับการอนุมัติ';
        $strings['ResourceRequiresApprovalNone'] = 'การจองไม่จำเป็นต้องได้รับการอนุมัติ';
        $strings['ResourcePermissionAutoGranted'] = 'อนุญาตโดยอัตโนมัติ';
        $strings['ResourcePermissionNotAutoGranted'] = 'ไม่ได้รับอนุญาตโดยอัตโนมัติ';
        $strings['ResourceMinNotice'] = 'การจองต้องจองล่วงหน้าอย่างน้อย %s ก่อนเวลาเริ่มต้น';
        $strings['ResourceMinNoticeNone'] = 'สามารถจองได้จนถึงเวลาปัจจุบัน';
        $strings['ResourceMaxNotice'] = 'การจองต้องไม่สิ้นสุดเกินกว่า %s จากเวลาปัจจุบัน';
        $strings['ResourceMaxNoticeNone'] = 'การจองสามารถสิ้นสุดที่ตอนไหนก็ได้ในอนาคต';
        $strings['ResourceBufferTime'] = 'ต้องมี %s ระหว่างการจอง';
        $strings['ResourceBufferTimeNone'] = 'ไม่มีช่วงกั้นระหว่างการจอง';
        $strings['ResourceAllowMultiDay'] = 'สามารถจองตลอดวันได้';
        $strings['ResourceNotAllowMultiDay'] = 'ไม่สามารถจองตลอดวันได้';
        $strings['ResourceCapacity'] = 'ทรัพยากรนี้มีความจุ %s คน';
        $strings['ResourceCapacityNone'] = 'ทรัพยากรนี้มีความจุไม่จำกัด';
        $strings['AddNewResource'] = 'เพิ่มทรัพยากรใหม่';
        $strings['AddNewUser'] = 'เพิ่มผู้ใช้ใหม่';
        $strings['AddResource'] = 'เพิ่มทรัพยากร';
        $strings['Capacity'] = 'ความจุ';
        $strings['Access'] = 'การเข้าถึง';
        $strings['Duration'] = 'ระยะเวลา';
        $strings['Active'] = 'ใช้งานได้';
        $strings['Inactive'] = 'ใช้งานไม่ได้';
        $strings['ResetPassword'] = 'รีเซ็ตรหัสผ่าน';
        $strings['LastLogin'] = 'เข้าใช้งานครั้งล่าสุด';
        $strings['Search'] = 'ค้นหา';
        $strings['ResourcePermissions'] = 'การเข้าถึงทรัพยากร';
        $strings['Reservations'] = 'การจอง';
        $strings['Groups'] = 'กลุ่มผู้ใช้';
        $strings['Users'] = 'ผู้ใช้';
        $strings['AllUsers'] = 'ผู้ใช้ทั้งหมด';
        $strings['AllGroups'] = 'กลุ่มผู้ใช้ทั้งหมด';
        $strings['AllSchedules'] = 'ตารางการจองทั้งหมด';
        $strings['UsernameOrEmail'] = 'ชื่อผู้ใช้หรืออีเมล์';
        $strings['Members'] = 'สมาชิก';
        $strings['QuickSlotCreation'] = 'สร้างช่วงเวลาทุก ๆ %s นาที ระหว่าง %s และ %s';
        $strings['ApplyUpdatesTo'] = 'นำการอัพเดทไปใช้กับ';
        $strings['CancelParticipation'] = 'ยกเลิกการเข้าร่วม';
        $strings['Attending'] = 'ผู้เข้าร่วม';
        $strings['QuotaConfiguration'] = 'บน %s สำหรับ %s ผู้ใช้ใน %s ถูกจำกัดไว้ที่ %s %s ต่อ %s';
        $strings['QuotaEnforcement'] = 'บังคับใช้กับ %s %s';
        $strings['reservations'] = 'การจอง';
        $strings['reservation'] = 'การจอง';
        $strings['ChangeCalendar'] = 'เปลี่ยนปฏิทิน';
        $strings['AddQuota'] = 'เพิ่มโคต้า';
        $strings['FindUser'] = 'ค้นหาผู้ใช้';
        $strings['Created'] = 'สร้างเมื่อ';
        $strings['LastModified'] = 'การแก้ไขล่าสุด';
        $strings['GroupName'] = 'ชื่อกลุ่ม';
        $strings['GroupMembers'] = 'สมาชิกในกลุ่ม';
        $strings['GroupRoles'] = 'สิทธิ์ของกลุ่ม';
        $strings['GroupAdmin'] = 'ผู้ดูแลกลุ่ม';
        $strings['Actions'] = 'การจัดการ';
        $strings['CurrentPassword'] = 'รหัสผ่านปัจุบัน';
        $strings['NewPassword'] = 'รหัสผ่านใหม่';
        $strings['InvalidPassword'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
        $strings['PasswordChangedSuccessfully'] = 'รหัสผ่านของคุณได้รับการเปลี่ยนเรียบร้อยแล้ว';
        $strings['SignedInAs'] = 'เข้าสู่ระบบด้วยชื่อ';
        $strings['NotSignedIn'] = 'คุณยังไม่ได้เข้าสู่ระบบ';
        $strings['ReservationTitle'] = 'ชื่อการจอง';
        $strings['ReservationDescription'] = 'รายละเอียดการจอง';
        $strings['ResourceList'] = 'ทรัพยากรที่ต้องการจอง';
        $strings['Accessories'] = 'อุปกรณ์เสริม';
        $strings['InvitationList'] = 'ได้รับเชิญ';
        $strings['AccessoryName'] = 'ชืออุปกรณ์เสริม';
        $strings['QuantityAvailable'] = 'ปริมาณที่พร้อมบริการ';
        $strings['Resources'] = 'ทรัพยากร';
        $strings['Participants'] = 'ผู้เข้าร่วม';
        $strings['User'] = 'ผู้ใช้';
        $strings['Resource'] = 'ทรัพยากร';
        $strings['Status'] = 'สถานะ';
        $strings['Approve'] = 'ยืนยัน';
        $strings['Page'] = 'หน้า';
        $strings['Rows'] = 'แถว';
        $strings['Unlimited'] = 'ไม่จำกัด';
        $strings['Email'] = 'อีเมล์';
        $strings['EmailAddress'] = 'ที่อยู่อีเมล์';
        $strings['Phone'] = 'หมายเลขโทรศัทพ์';
        $strings['Organization'] = 'หน่วยงาน';
        $strings['Position'] = 'ตำแหน่ง';
        $strings['Language'] = 'ภาษา';
        $strings['Permissions'] = 'สิทธิ์';
        $strings['Reset'] = 'รีเซ็ต';
        $strings['FindGroup'] = 'ค้นหากลุ่ม';
        $strings['Manage'] = 'จัดการ';
        $strings['None'] = 'ไม่มี';
        $strings['AddToOutlook'] = 'เพิ่มไปยังปฏิทิน';
        $strings['Done'] = 'ดำเนินการเรียบร้อย';
        $strings['RememberMe'] = 'จดจำผู้ใช้';
        $strings['FirstTimeUser?'] = 'เป็นผู้ใช้งานครั้งแรกหรือเปล่า?';
        $strings['CreateAnAccount'] = 'สร้างบัญชีสมาชิก';
        $strings['ViewSchedule'] = 'ดูตารางการจอง';
        $strings['ForgotMyPassword'] = 'ฉันลืมรหัสผ่านของฉัน';
        $strings['YouWillBeEmailedANewPassword'] = 'รหัสผ่านใหม่ที่เกิดจากการสุ่มจะส่งถึงคุณทางอีเมล์';
        $strings['Close'] = 'ปิด';
        $strings['ExportToCSV'] = 'ส่งออกแบบ CSV';
        $strings['OK'] = 'ตกลง';
        $strings['Working'] = 'กำลังดำเนินการ...';
        $strings['Login'] = 'เข้าสู่ระบบ';
        $strings['AdditionalInformation'] = 'ข้อมูลเพิ่มเติม';
        $strings['AllFieldsAreRequired'] = 'ต้องการข้อมูลทุกช่อง';
        $strings['Optional'] = 'ตัวเลือก';
        $strings['YourProfileWasUpdated'] = 'ข้อมูลผู้ใช้ของคุณได้รับการอัพเดทแล้ว';
        $strings['YourSettingsWereUpdated'] = 'การตั้งค่าของคุณได้รับการอัพเดทแล้ว';
        $strings['Register'] = 'ลงะเบียน';
        $strings['SecurityCode'] = 'รหัสเพื่อความปลอดภัย';
        $strings['ReservationCreatedPreference'] = 'เมื่อฉันสร้างการจองหรือมีการจองที่ถูกสร้างขึ้นในนามของฉัน';
        $strings['ReservationUpdatedPreference'] = 'เมื่อฉันอัพเดทการจองหรือมีอัพเดทการจองของฉัน';
        $strings['ReservationDeletedPreference'] = 'เมื่อฉันลบการจองหรือมีการลบจองของฉัน';
        $strings['ReservationApprovalPreference'] = 'เมื่อการจองของฉันได้รับการอนมัติ';
        $strings['PreferenceSendEmail'] = 'ส่งอีเมล์ถึงฉันด้วย';
        $strings['PreferenceNoEmail'] = 'ไม่ต้องเตือนฉัน';
        $strings['ReservationCreated'] = 'การจองของคุณได้ถูกสร้างเรียบร้อยแล้ว!';
        $strings['ReservationUpdated'] = 'การจองของคุณได้ถูกอัพเดทเรียบร้อยแล้ว!';
        $strings['ReservationRemoved'] = 'การจองของคุณได้ถูกนำออกเรียบร้อยแล้ว';
        $strings['ReservationRequiresApproval'] = 'หนึ่งในทรัพยากรหรือมากกว่า ต้องได้รับอนุมัติก่อนการใช้งาน การจองนี้จะอยู่ ระหว่างดำเนินการ จนกว่าจะได้รับการอนุมัติ';
        $strings['YourReferenceNumber'] = 'หมายเลขอ้างอิงของคุณคือ %s';
        $strings['ChangeUser'] = 'เปลี่ยนผู้ใช้งาน';
        $strings['MoreResources'] = 'ทรัพยากรมากขึ้น';
        $strings['ReservationLength'] = 'ระยะเวลากาาจอง';
        $strings['ParticipantList'] = 'รายชื่อผู้เข้าร่วม';
        $strings['AddParticipants'] = 'เพิ่มผู้เข้าร่วม';
        $strings['InviteOthers'] = 'เชิญชวนผู้อื่น';
        $strings['AddResources'] = 'เพิ่มทรัพยากร';
        $strings['AddAccessories'] = 'เพิ่มอุปกรณ์เสริม';
        $strings['Accessory'] = 'อุปกรณ์เสริม';
        $strings['QuantityRequested'] = 'ปริมาณการขอใช้บริการ';
        $strings['CreatingReservation'] = 'สร้างการจอง';
        $strings['UpdatingReservation'] = 'อัพเดทการจอง';
        $strings['DeleteWarning'] = 'การกระทำนี้เป็นจะเป็นแบบถาวรและเรียกคืนไม่ได้!';
        $strings['DeleteAccessoryWarning'] = 'การลบอุปกรณ์เสริมนี้จะทำการลบการจองอุปกรณ์เสริมนี้ทั้งหมดออกด้วย';
        $strings['AddAccessory'] = 'เพิ่มอุปกรณ์เสริม';
        $strings['AddBlackout'] = 'เพิ่มเวลางดจอง';
        $strings['AllResourcesOn'] = 'ทุกทรัพยากรบน';
        $strings['Reason'] = 'เหตุผล';
        $strings['BlackoutShowMe'] = 'แสดงการจองที่ขัดแย้งกัน';
        $strings['BlackoutDeleteConflicts'] = 'ลบการจองที่ซ้ำกัน';
        $strings['Filter'] = 'กรอง';
        $strings['Between'] = 'ระหว่าง';
        $strings['CreatedBy'] = 'สร้างโดย';
        $strings['BlackoutCreated'] = 'สร้างเวลางดจองแล้ว';
        $strings['BlackoutNotCreated'] = 'ไม่สามารถสร้างเวลางดจองได้';
        $strings['BlackoutUpdated'] = 'อัพเดทเวลางดจองแล้ว';
        $strings['BlackoutNotUpdated'] = 'ไม่สามารถอัพเดทเวลางดจองได้';
        $strings['BlackoutConflicts'] = 'มีเวลางดจองที่ขัดแย้งกัน';
        $strings['ReservationConflicts'] = 'มีเวลาจองที่ขัดแย้งกัน';
        $strings['UsersInGroup'] = 'ผู้ใช้ในกลุ่มนี้';
        $strings['Browse'] = 'เรียกดู';
        $strings['DeleteGroupWarning'] = 'การลบกลุ่มนี้จะทำการถอนสิทธิ์การเข้าถึงทรัพยากรด้วย ผุ้ใช้ในกลุ่มนี้จะไม่สามารถเข้าถึงทรัพยการได้';
        $strings['WhatRolesApplyToThisGroup'] = 'บทบาทไหนที่จะนำไปใช้กับกลุ่มนี้?';
        $strings['WhoCanManageThisGroup'] = 'ใครสามารถจัดการลุ่มนี้ได้?';
        $strings['WhoCanManageThisSchedule'] = 'สามารถสามารถจัดการตารางการจองนี้ได้?';
        $strings['AllQuotas'] = 'โควต้าทั้งหมด';
        $strings['QuotaReminder'] = 'โปรดจำไว้ว่า: โควต้ามีการบังคับใช้จะขึ้นอยู่กับเขตเวลาของตารางการจองกำหนดการจอง';
        $strings['AllReservations'] = 'การจองทั้งหมด';
        $strings['PendingReservations'] = 'กำลังรอการอนุมัติ';
        $strings['Approving'] = 'กำลังอนุมัติ';
        $strings['MoveToSchedule'] = 'ย้ายไปยังตารางการจอง';
        $strings['DeleteResourceWarning'] = 'การลบทรัพยากรจะทำการลบข้อมูลที่เกี่ยวข้องด้วย รวมถึง';
        $strings['DeleteResourceWarningReservations'] = 'การจองที่ผ่านมาทั้งหมด ปัจจุบัน และในอนาคตจะเกี่ยวข้องกับมันด้วย';
        $strings['DeleteResourceWarningPermissions'] = 'สิทธิ์ทั้งหมดได้รับอนุญาติแล้ว';
        $strings['DeleteResourceWarningReassign'] = 'กรุณาโอนสิ่งที่คุณไม่ต้องการที่จะลบออก ก่อนดำเนินการต่อ';
        $strings['ScheduleLayout'] = 'เลย์เอาท์ (ทุกช่วงเวลา %s)';
        $strings['ReservableTimeSlots'] = 'ช่องของช่วงเวลาที่สามารถจองได้';
        $strings['BlockedTimeSlots'] = 'ช่องของช่วงเวลาที่งดจอง';
        $strings['ThisIsTheDefaultSchedule'] = 'นี่คือตารางการจองที่เป็นค่าเริ่มต้น';
        $strings['DefaultScheduleCannotBeDeleted'] = 'ตารางการจองที่เป็นค่าเริ่มต้นไม่สามารถลบได้';
        $strings['MakeDefault'] = 'ทำให้เป็นค่าเริ่มต้น';
        $strings['BringDown'] = 'นำลงมา';
        $strings['ChangeLayout'] = 'เปลี่ยนเลย์เอาท์';
        $strings['AddSchedule'] = 'เพิ่มตารางการจอง';
        $strings['StartsOn'] = 'เริ่มที่';
        $strings['NumberOfDaysVisible'] = 'จำนวนของวันที่มองเห็นได้';
        $strings['UseSameLayoutAs'] = 'ใช้เหมือนกับเลย์เอาท์';
        $strings['Format'] = 'รูปแบบ';
        $strings['OptionalLabel'] = 'ลาเบลตัวเลือก';
        $strings['LayoutInstructions'] = 'กรุณาใส่ช่วงเวลา 1 ช่วงเวลาต่อ 1 บรรทัด ช่วงเวลาต้องครอบคลุมทั้ง 24 ชั่วโมงของวัน โดยเริ่มและสิ้นสุดที่ 12:00 AM';
        $strings['AddUser'] = 'เพิ่มผู้ใช้ใหม่';
        $strings['UserPermissionInfo'] = 'การเข้าถึงทรัพยากร้จริง อาจแตกต่างกันไปขึ้นอยู่กับบทบาทของผู้ใช้สิทธิ์ของกลุ่มหรือการตั้งค่าสิทธิ์ภายนอก';
        $strings['DeleteUserWarning'] = 'การลบผู้ใช้ จะทำการลบข้อมูลการจองทั้งในประวัติการจอง ปัจจุบัน และอนาคตด้วย';
        $strings['AddAnnouncement'] = 'เพิ่มประกาศ';
        $strings['Announcement'] = 'ประกาศ';
        $strings['Priority'] = 'ลำดับความสำคัญ';
        $strings['Reservable'] = 'จองได้';
        $strings['Unreservable'] = 'งดจอง';
        $strings['Reserved'] = 'จองแล้ว';
        $strings['MyReservation'] = 'การจองของฉัน';
        $strings['Pending'] = 'รอการอนุมัติ';
        $strings['Past'] = 'ผ่านมาแล้ว';
        $strings['Restricted'] = 'จำกัดการจอง';
        $strings['ViewAll'] = 'ดูทั้งหมด';
        $strings['MoveResourcesAndReservations'] = 'ย้ายทรัพยากรและการจองไปยัง';
        $strings['TurnOffSubscription'] = 'ปิดการให้สมัครเป็นสมาชิกปฏิทินนี้';
        $strings['TurnOnSubscription'] = 'อนุญาตให้สมัครเป็นสมาชิกปฏิทินนี้ได้';
        $strings['SubscribeToCalendar'] = 'สมัครเป็นสมาชิกปฏิทินนี้';
        $strings['SubscriptionsAreDisabled'] = 'ผู้ดูแลระบบได้ปิดการใช้งานการสมัครสมาชิกปฏิทิน';
        $strings['NoResourceAdministratorLabel'] = '(ไม่มีผู้ดูแลระบบทรัพยากรนี้)';
        $strings['WhoCanManageThisResource'] = 'ใครที่สามารถจัดการทรัพยากรนี้ได้?';
        $strings['ResourceAdministrator'] = 'ผู้ดูแลทรัพยากร';
        $strings['Private'] = 'ส่วนบุคคล';
        $strings['Accept'] = 'ยอมรับ';
        $strings['Decline'] = 'ปฏิเสธ';
        $strings['ShowFullWeek'] = 'แสดงทั้งอาทิตย์';
        $strings['CustomAttributes'] = 'ปรับแต่งคุณลักษณะ';
        $strings['AddAttribute'] = 'เพิ่มคุณลักษณะ';
        $strings['EditAttribute'] = 'แก้ไขคุณลักษณะ';
        $strings['DisplayLabel'] = 'แสดงลาเบล';
        $strings['Type'] = 'ชนิด';
        $strings['Required'] = 'จำเป็น';
        $strings['ValidationExpression'] = 'นิพจน์การตรวจสอบความถูกต้อง';
        $strings['PossibleValues'] = 'ค่าที่เป็นไปได้';
        $strings['SingleLineTextbox'] = 'Textbox แบบบรรทัดเดียว';
        $strings['MultiLineTextbox'] = 'Textbox แบบหลายบรรทัด';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'Select List';
        $strings['CommaSeparated'] = 'comma separated';
        $strings['Category'] = 'หมวดหมู่';
        $strings['CategoryReservation'] = 'การจอง';
        $strings['CategoryGroup'] = 'กลุ่ม';
        $strings['SortOrder'] = 'การเรียงลำดับ';
        $strings['Title'] = 'ชื่อ';
        $strings['AdditionalAttributes'] = 'คุณลักษณะเพิ่มเติม';
        $strings['True'] = 'ใช่';
        $strings['False'] = 'ไม่ใช่';
        $strings['ForgotPasswordEmailSent'] = 'อีเมลถูกส่งไปยังที่อยู่ที่ให้ไว้ โดยมีคำแนะนำสำหรับการรีเซ็ตรหัสผ่านของคุณในนั้น';
        $strings['ActivationEmailSent'] = 'คุณจะได้รับอีเมลแจ้งการเปิดใช้งานเร็ว ๆ นี้';
        $strings['AccountActivationError'] = 'ขออภัย เราไม่สามารถเปิดใช้งานบัญชีของคุณได้';
        $strings['Attachments'] = 'เอกสารแนบ';
        $strings['AttachFile'] = 'ไฟล์แนบ';
        $strings['Maximum'] = 'สูงสุด';
        $strings['NoScheduleAdministratorLabel'] = 'ไม่มีผู้ดูแลตารางการจอง';
        $strings['ScheduleAdministrator'] = 'ผู้ดูแลตารางการจอง';
        $strings['Total'] = 'รวมทั้งสิ้น';
        $strings['QuantityReserved'] = 'จำนวนที่จองไว้';
        $strings['AllAccessories'] = 'อุปรกรณืทั้งหมด';
        $strings['GetReport'] = 'รับรายงาน';
        $strings['NoResultsFound'] = 'ไม่พบผลลัพธ์ที่ตรงกัน';
        $strings['SaveThisReport'] = 'บันทึกรายงานนี้';
        $strings['ReportSaved'] = 'รายงานถูกบันทึกแล้ว!';
        $strings['EmailReport'] = 'ส่งรายงานทางอีเมล';
        $strings['ReportSent'] = 'รายงานถูกส่งแล้ว!';
        $strings['RunReport'] = 'เรียกใช้รายงาน';
        $strings['NoSavedReports'] = 'คุณไม่มีรายงานที่บันทึกไว้';
        $strings['CurrentWeek'] = 'สัปดาห์ปัจจุบัน';
        $strings['CurrentMonth'] = 'เดือนปัจจุบัน';
        $strings['AllTime'] = 'ทุกช่วงเวลา';
        $strings['FilterBy'] = 'กรองโดย';
        $strings['Select'] = 'เลือก';
        $strings['List'] = 'รายการ';
        $strings['TotalTime'] = 'เวลาทั้งหมด';
        $strings['Count'] = 'นับ';
        $strings['Usage'] = 'การใช้งาน';
        $strings['AggregateBy'] = 'รวมโดย';
        $strings['Range'] = 'ช่วง';
        $strings['Choose'] = 'เลือก';
        $strings['All'] = 'ทั้งหมด';
        $strings['ViewAsChart'] = 'ดูแบบแผนภูมิ';
        $strings['ReservedResources'] = 'ทรัพยากรที่มีการจองไว้';
        $strings['ReservedAccessories'] = 'อุปกรร์ที่มีการจองไว้';
        $strings['ResourceUsageTimeBooked'] = 'การใช้ทรัพยากร - เวลาที่จองไว้';
        $strings['ResourceUsageReservationCount'] = 'การใช้ทรัพยากร - จำนวนการจอง';
        $strings['Top20UsersTimeBooked'] = 'ผู้ใช้ 20 อันดับแรก - เวลาที่จองไว้';
        $strings['Top20UsersReservationCount'] = 'ผู้ใช้ 20 อันดับแรก - จำนวนการจอง';
        $strings['ConfigurationUpdated'] = 'อัปเดตไฟล์คอนฟิกแล้ว';
        $strings['ConfigurationUiNotEnabled'] = 'ไม่สามารถเข้าถึงหน้านี้ได้เนื่องจาก $conf[\'settings\'][\'pages\'][\'enable.configuration\'] ถูกตั้งค่าให้ปฏิเสธหรือไม่มีการตั้งค่า';
        $strings['ConfigurationFileNotWritable'] = 'ไฟล์คอนฟิกไม่สามารถเขียนได้ โปรดตรวจสอบสิทธิ์ของไฟล์นี้และลองอีกครั้ง';
        $strings['ConfigurationUpdateHelp'] = 'โปรดดูส่วนการกำหนดค่าของ <a target=_blank href=%s>ไฟล์การช่วยเหลือ</a> สำหรับเอกสารเกี่ยวกับการตั้งค่าเหล่านี้';
        $strings['GeneralConfigSettings'] = 'การตั้งค่า';
        $strings['UseSameLayoutForAllDays'] = 'ใช้เลย์เอาท์เดียวกันทุกวัน';
        $strings['LayoutVariesByDay'] = 'เลย์เอาท์จะแตกต่างกันไปในแต่ละวัน';
        $strings['ManageReminders'] = 'การแจ้งเตือน';
        $strings['ReminderUser'] = 'รหัสผู้ใช้';
        $strings['ReminderMessage'] = 'ข้้อความ';
        $strings['ReminderAddress'] = 'ที่อยู่';
        $strings['ReminderSendtime'] = 'เวลาที่จะส่ง';
        $strings['ReminderRefNumber'] = 'หมายเลขอ้างอิงการจอง';
        $strings['ReminderSendtimeDate'] = 'วันที่ในการแจ้งเตือน';
        $strings['ReminderSendtimeTime'] = 'เวลาในการแจ้งเตือน(HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'เพิ่มการแจ้งเตือน';
        $strings['DeleteReminderWarning'] = 'คุณแน่ใจหรือไม่ว่าต้องการลบ?';
        $strings['NoReminders'] = 'คุณไม่มีการแจ้งเตือนที่กำลังจะมาถึง';
        $strings['Reminders'] = 'การแจ้งเตือน';
        $strings['SendReminder'] = 'ส่งการแจ้งเตือน';
        $strings['minutes'] = 'นาที';
        $strings['hours'] = 'ชั่วโมง';
        $strings['days'] = 'วัน';
        $strings['ReminderBeforeStart'] = 'ก่อนเวลาเริ่ม';
        $strings['ReminderBeforeEnd'] = 'ก่อนเวลาสิ้นสุด';
        $strings['Logo'] = 'โลโก้';
        $strings['CssFile'] = 'ไฟล์ CSS';
        $strings['ThemeUploadSuccess'] = 'การเปลี่ยนแปลงของคุณได้รับการบันทึกแล้ว รีเฟรชหน้าเว็บเพื่อให้การเปลี่ยนแปลงมีผล';
        $strings['MakeDefaultSchedule'] = 'ทำให้กำหนดการเป็นค่าเริ่มต้นของฉัน';
        $strings['DefaultScheduleSet'] = 'กำหนดการนี้เป็นค่าเริ่มต้นของคุณแล้ว';
        $strings['FlipSchedule'] = 'พลิกเลย์เอาท์ตารางเวลา';
        $strings['Next'] = 'ถัดไป';
        $strings['Success'] = 'สำเร็จ';
        $strings['Participant'] = 'ผู้เข้าร่วม';
        $strings['ResourceFilter'] = 'กรองทรัพยากร';
        $strings['ResourceGroups'] = 'กลุ่มของทรัพยากร';
        $strings['AddNewGroup'] = 'เพิ่มกลุ่มใหม่';
        $strings['Quit'] = 'ออก';
        $strings['AddGroup'] = 'เพิ่มกลุ่ม';
        $strings['StandardScheduleDisplay'] = 'ใช้การแสดงตารางการจองแบบมาตรฐาน';
        $strings['TallScheduleDisplay'] = 'ใช้การแสดงตารางการจองแบบสูง';
        $strings['WideScheduleDisplay'] = 'ใช้การแสดงตารางการจองแบบกว้าง';
        $strings['CondensedWeekScheduleDisplay'] = 'ใช้การแสดงตารางการจองในสัปดาห์แบบย่อ';
        $strings['ResourceGroupHelp1'] = 'ลากและวางกลุ่มทรัพยากรเพื่อจัดระเบียบใหม่';
        $strings['ResourceGroupHelp2'] = 'คลิกขวาที่ชื่อกลุ่มทรัพยากรเพื่อดำเนินการเพิ่มเติม';
        $strings['ResourceGroupHelp3'] = 'ลากและวางทรัพยากรเพื่อเพิ่มลงในกลุ่ม';
        $strings['ResourceGroupWarning'] = 'ถ้าใช้กลุ่มทรัพยากรแต่ละทรัพยากรต้องถูกกำหนดให้กับกลุ่มอย่างน้อยหนึ่งกลุ่ม ทรัพยากรที่ไม่ได้กำหนดกลุ่มจะไม่สามารถถูกจองได้';
        $strings['ResourceType'] = 'ประเภทของทรัพยากร';
        $strings['AppliesTo'] = 'นำไปใช้กับ';
        $strings['UniquePerInstance'] = 'เป็นเอกลักษร์แต่ละกรณี';
        $strings['AddResourceType'] = 'เพิ่มชนิดทรัพยากร';
        $strings['NoResourceTypeLabel'] = '(ไม่มีการตั้งต่าประเภททรัพยากร)';
        $strings['ClearFilter'] = 'ล้างการกรอง';
        $strings['MinimumCapacity'] = 'ความจุขั้นต่ำ';
        $strings['Color'] = 'สี';
        $strings['Available'] = 'ว่าง';
        $strings['Unavailable'] = 'ไม่ว่าง';
        $strings['Hidden'] = 'ซ่อน';
        $strings['ResourceStatus'] = 'สถานะทรัพยากร';
        $strings['CurrentStatus'] = 'สถานะปัจจุบัน';
        $strings['AllReservationResources'] = 'การจองทรัพยากรทั้งหมด';
        $strings['File'] = 'ไฟล์';
        $strings['BulkResourceUpdate'] = 'การอัปเดตทรัพยากรเป็นกลุ่ม';
        $strings['Unchanged'] = 'ไม่เปลี่ยน';
        $strings['Common'] = 'ร่วมกัน';
        $strings['AdminOnly'] = 'เฉพาะผู้ดูแลระบบเท่านั้น';
        $strings['AdvancedFilter'] = 'การกรองขั้นสูง';
        $strings['MinimumQuantity'] = 'จำนวนขั้นต่ำ';
        $strings['MaximumQuantity'] = 'จำนวนสูงสุด';
        $strings['ChangeLanguage'] = 'เปลี่ยนภาษา';
        $strings['AddRule'] = 'เพิ่มกฏ';
        $strings['Attribute'] = 'คุณลักษณะ';
        $strings['RequiredValue'] = 'จำเป็นต้องระบุ';
        $strings['ReservationCustomRuleAdd'] = 'ถ้า %s แล้วสีสำรองจะเป็น';
        $strings['AddReservationColorRule'] = 'เพิ่มกฏสีของการจอง';
        $strings['LimitAttributeScope'] = 'เก็บเฉพาะในกรณีเจาะจง';
        $strings['CollectFor'] = 'เก็บสำหรับสำหรับ';
        $strings['SignIn'] = 'เข้าสู่ระบบ';
        $strings['AllParticipants'] = 'ผู้เข้าร่วมทั้งหมด';
        $strings['RegisterANewAccount'] = 'ลงทะเบียนบัญชีสมาชิกใหม่';
        $strings['Dates'] = 'วันที่';
        $strings['More'] = 'เพิ่มเติม';
        $strings['ResourceAvailability'] = 'ความพร้อมของทรัพยากร';
        $strings['UnavailableAllDay'] = 'ไม่ว่างทั้งวัน';
        $strings['AvailableUntil'] = 'ว่างจนถึงวันที่';
        $strings['AvailableBeginningAt'] = 'จะเริ่มว่างในวันที่';
        $strings['AllResourceTypes'] = 'ทุกชนิดของทรัพยากร';
        $strings['AllResourceStatuses'] = 'ทุกสถานะของทรัพยกร';
        $strings['AllowParticipantsToJoin'] = 'อนุญาตให้ผู้เข้าร่วมเข้าร่วมได้';
        $strings['Join'] = 'เข้าร่วม';
        $strings['YouAreAParticipant'] = 'คุณเป็นผู้เข้าร่วมการจองนี้';
        $strings['YouAreInvited'] = 'คุณถูกเชิญให้เข้าร่วมการจองนี้';
        $strings['YouCanJoinThisReservation'] = 'คุณสามารถเข้าร่วมการจองนี้ได้';
        $strings['Import'] = 'การนำเข้า';
        $strings['GetTemplate'] = 'รับ Template';
        $strings['UserImportInstructions'] = 'ไฟล์ต้องอยู่ในรูปแบบ CSV ต้องระบุชื่อผู้ใช้และอีเมล ปล่อยให้ช่องอื่นว่างไว ้จะกำหนดค่าเริ่มต้นให้และ \'password \' เป็นรหัสผ่านของผู้ใช้ สามารถใช้เทมเพลตที่ให้มาเป็นตัวอย่างได้';
        $strings['RowsImported'] = 'แถวที่นำเข้า';
        $strings['RowsSkipped'] = 'แถวที่ข้าม';
        $strings['Columns'] = 'คอลัมน์';
        $strings['Reserve'] = 'ทำการจอง';
        $strings['AllDay'] = 'ทั้งวัน';
        $strings['Everyday'] = 'ทุกวัน';
        $strings['IncludingCompletedReservations'] = 'รวมถึงการจองที่สำเร็จแล้ว';
        $strings['NotCountingCompletedReservations'] = 'ไม่นับการจองที่สำเร็จแล้ว';
        $strings['RetrySkipConflicts'] = 'ข้ามการจองที่ขัดแย้งกัน';
        $strings['Retry'] = 'ลองอีกครั้ง';
        $strings['RemoveExistingPermissions'] = 'ลบสิทธิ์ที่มีอยู่ออก?';
        $strings['Continue'] = 'ต่อไป';
        $strings['WeNeedYourEmailAddress'] = 'เราต้องการที่อยู่อีเมลของคุณเพื่อทำการจอง';
        $strings['ResourceColor'] = 'สีของทรัพยากร';
        $strings['DateTime'] = 'วั้นที่ เวลา';
        $strings['AutoReleaseNotification'] = 'ปล่อยการจองโดยอัตโนมัติหากไม่ได้เช็คอินภายใน %s นาที';
        $strings['RequiresCheckInNotification'] = 'ต้องการการเช็คอิน / การเช็คเอาท์';
        $strings['NoCheckInRequiredNotification'] = 'ไม่ต้องการการเช็คอิน / การเช็คเอาท์';
        $strings['RequiresApproval'] = 'ต้องได้รับการอนุมัติ';
        $strings['CheckingIn'] = 'กำลังเช็คอิน';
        $strings['CheckingOut'] = 'กำลังเช็คเอาท์';
        $strings['CheckIn'] = 'เช็คอิน';
        $strings['CheckOut'] = 'เช็คเอาท์';
        $strings['ReleasedIn'] = 'ปล่อยการจองใน';
        $strings['CheckedInSuccess'] = 'คุณได้เช็คอินแล้ว';
        $strings['CheckedOutSuccess'] = 'คุณได้เช็คเอาท์แล้ว';
        $strings['CheckInFailed'] = 'คุณไม่สามารถเช็คอินได้';
        $strings['CheckOutFailed'] = 'คุณไม่สามารถเช็คเอาท์ได้';
        $strings['CheckInTime'] = 'เวลาเช็คอิน';
        $strings['CheckOutTime'] = 'เวลาเช็คเอาท์';
        $strings['OriginalEndDate'] = 'เวลาสิ้นสุดเดิม';
        $strings['SpecificDates'] = 'แสดงตามวันที่เลือก';
        $strings['Users'] = 'ผู้ใช้';
        $strings['Guest'] = 'ผู้เยี่ยมชม';
        $strings['ResourceDisplayPrompt'] = 'ทรัพยากรที่จะแสดง';
        $strings['Credits'] = 'เครดิต';
        $strings['AvailableCredits'] = 'เครดิตที่ใช้ได้';
        $strings['CreditUsagePerSlot'] = 'ต้องใช้เครดิต %s ต่อช่วง (เวลาที่มีการใช้งานปกติ)';
        $strings['PeakCreditUsagePerSlot'] = 'ต้องใช้เครดิต %s ต่อช่วง (เวลาที่มีการใช้งานเยอะ)';
        $strings['CreditsRule'] = 'คุณ่มีเครดิตไม่เพียงพอ เครดิตที่ต้องการ: %s เครดิตในบัญชีของคุณ: %s ';
        $strings['PeakTimes'] = 'ช่วงเวลาที่มีการใช้เยอะที่สุด';
        $strings['AllYear'] = 'ทุกปี';
        $strings['MoreOptions'] = 'ตัวเลือกที่มากขึ้น';
        $strings['SendAsEmail'] = 'ส่งเป็นอีเมล';
        $strings['UsersInGroups'] = 'ผู้ใช้ในกลุ่ม';
        $strings['UsersWithAccessToResources'] = 'ผู้ใช้ที่เข้าถึงทรัพยการ';
        $strings['AnnouncementSubject'] = 'ประกาศใหม่ถูกโพสต์โดย %s';
        $strings['AnnouncementEmailNotice'] = 'ผู้ใช้จะถูกส่งประกาศนี้เป็นอีเมล';
        $strings['Day'] = 'วัน';
        $strings['NotifyWhenAvailable'] = 'แจ้งเตือนฉันเมื่อ ว่าง';
        $strings['AddingToWaitlist'] = 'เพิ่มคุณเข้าสู่รายชื่อผู้รอแล้ว';
        $strings['WaitlistRequestAdded'] = 'คุณจะได้รับการแจ้งเตือนเมื่อว่าง';
        $strings['PrintQRCode'] = 'พิมพ์ QR Code';
        $strings['FindATime'] = 'ค้นหาตามเวลา';
        $strings['AnyResource'] = 'ทรัพยากรใด ๆ ก็ได้';
        $strings['ThisWeek'] = 'อาทิตย์นี้';
        $strings['Hours'] = 'ชั่วโมง';
        $strings['Minutes'] = 'นาที';
        $strings['ImportICS'] = 'นำเข้าจาก ICS';
        $strings['ImportQuartzy'] = 'นำเข้าจาก Quartzy';
        $strings['OnlyIcs'] = 'เฉพาะไฟล์ *.ics เท่านั้นที่สามารถอัพโหลดได้';
        $strings['IcsLocationsAsResources'] = 'สถานที่จะถูกนำเข้าเป็นทรัพยากร';
        $strings['IcsMissingOrganizer'] = 'กิจกรรมใด ๆ ที่ไม่มีผู้จัดงานจะมีการตั้งค่าเจ้าของให้กับผู้ใช้ปัจจุบัน';
        $strings['IcsWarning'] = 'กฎการจองจะไม่บังคับใช้ - การจองซ้ำ การจองซ้อน ฯลฯ หรืออื่น ๆ ที่จะเกิดขึ้นได้';
        $strings['BlackoutAroundConflicts'] = 'ปิดบังการจองที่ขัดแย้งกัน';
        $strings['DuplicateReservation'] = 'ทำซ้ำ';
        $strings['UnavailableNow'] = 'ไม่ว่างในขณะนี้';
        $strings['ReserveLater'] = 'จองภายหลัง';
        $strings['CollectedFor'] = 'ถูกเก็บไว้สำหรับ';
        $strings['IncludeDeleted'] = 'รวมถึงการจองที่ถูกลบไปแล้วด้วย';
        $strings['Deleted'] = 'ลบแล้ว';
        $strings['Back'] = 'ย้อนกลับ';
        $strings['Forward'] = 'ไปข้างหน้า';
        $strings['DateRange'] = 'ช่วงวัน';
        $strings['Copy'] = 'คัดลอก';
        $strings['Detect'] = 'ตรวจพบ';
        $strings['Autofill'] = 'ป้อนอัตโนมัติ';
        $strings['NameOrEmail'] = 'ชื่อ หรือ อีเมล';
        $strings['ImportResources'] = 'นำเข้าทรัพยากร';
        $strings['ExportResources'] = 'ส่งออกทรัพยากร';
        $strings['ResourceImportInstructions'] = '</ul></li>ไฟล์ต้องอยู่ในรูปแบบ CSV </li> <li> ต้องระบุชื่อ ปล่อยให้ฟิลด์อื่นว่างไว้จะตั้งค่าเริ่มต้น </li> <li> ตัวเลือกสถานะคือ \'ว่าง\', \'ไม่ว่าง\' และ \'ซ่อน\' </li> <li> สีควรเป็นค่า hex เช่น #ffffff</li> <li> การมอบหมายและการอนุมัติจะต้องเป็น true หรือ false </li> <li>ความถูกต้องของแอตทริบิวต์จะไม่ถูกบังคับใช้</li><li>ใช้คอมม่าแยกกลุ่มทรัพยากรแต่ละกลุ่ม</li> <li> ใช้เทมเพลตที่ให้มาเป็นตัวอย่าง </li> </ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>ไฟล์ต้องอยู่ในรูปแบบ CSV </li><li>จำเป็นต้องมีชื่ออีเมล, ชื่อทรัพยากร, เวลาเริ่มต้นและสิ้นสุด </li><li>เวลาเริ่มต้นและสิ้นสุดต้องใช้วันที่แบบเต็ม รูปแบบที่แนะนำคือ YYYY-mm-dd HH: mm (2017-12-31 20:30) </li><li> กฎ ข้อขัดแย้งและช่วงเวลาที่ถูกต้องจะไม่ได้รับการตรวจสอบ</li><li>การแจ้งเตือน จะไม่ส่งออกไป</li><li> ความถูกต้องของแอตทริบิวต์จะไม่ถูกบังคับใช้ </li> <li> คั่นด้วยเครื่องหมายคอมม่าหากมีหลายชื่อทรัพยากร</li><li> ใช้เทมเพลตที่ให้มาเป็นตัวอย่าง </li></ul>';
        $strings['AutoReleaseMinutes'] = 'ระยะเวลาในการคืนสถานะห้องให้ว่าง';
        $strings['CreditsPeak'] = 'เครดิต (ช่วงหนาแน่น)';
        $strings['CreditsOffPeak'] = 'เครดิต (ช่วงปกติ)';
        $strings['ResourceMinLengthCsv'] = 'เวลาสั้นที่สุดที่สามารถจองได้';
        $strings['ResourceMaxLengthCsv'] = 'เวลาในการจองสูงสุด';
        $strings['ResourceBufferTimeCsv'] = 'ช่วงกั้นเวลาระหว่างการจอง';
        $strings['ResourceMinNoticeCsv'] = 'ประกาศขั้นต่ำสำหรับการจอง';
        $strings['ResourceMaxNoticeCsv'] = 'การจองสูงสุด';
        $strings['Export'] = 'ส่งออก';
        $strings['DeleteMultipleUserWarning'] = 'การลบผู้ใช้เหล่านี้จะเป็นการลบการจองปัจจุบันในอนาคตและประวัติทั้งหมดของพวกเขา โดยไม่มีการส่งอีเมล';
        $strings['DeleteMultipleReservationsWarning'] = 'ไม่มีอีเมลที่จะถูกส่ง';
        $strings['ErrorMovingReservation'] = 'ผิดพลาดในการย้ายการจอง';
        $strings['SelectUser'] = 'เลือกผุ้ใช้';
        $strings['InviteUsers'] = 'เชิญผู้ใช้';
        $strings['InviteUsersLabel'] = 'ป้อนที่อยู่อีเมลคนที่คุณต้องการเชิญ';
        $strings['ApplyToCurrentUsers'] = 'นำไปใช้กับผู้ใช้ปัจจุบัน';
        $strings['ReasonText'] = 'ข้อความเหตุผล';
        $strings['NoAvailableMatchingTimes'] = 'ไม่มีช่วงเวลาว่างที่ตรงกับคุณค้นหา';
        $strings['Schedules'] = 'ตารางการจอง';
        $strings['NotifyUser'] = 'เตือนผู้ใช้';


        // End Strings

        // Install
        $strings['InstallApplication'] = 'ติดตั้ง LibreBooking (สำหรับ MySQL เท่านั้น)';
        $strings['IncorrectInstallPassword'] = 'ขออภัย, รหัสผ่านไม่ถูกต้อง';
        $strings['SetInstallPassword'] = 'คุณต้องตั้งรหัสผ่านการติดตั้งก่อนที่จะเริ่มเข้าสู่การติดตั้ง';
        $strings['InstallPasswordInstructions'] = 'ใน %s โปรดตั้งค่า %s เป็นรหัสผ่านที่สุ่มและยากที่จะคาดเดา จากนั้นกลับไปที่หน้านี้ <br/> คุณสามารถใช้ %s ได้';
        $strings['NoUpgradeNeeded'] = 'LibreBooking เป็นรุ่นล่าสุดแล้ว ไม่จำเป็นต้องอัพเกรด';
        $strings['ProvideInstallPassword'] = 'กรูณาระบุรหัสผ่านในการติดตั้ง';
        $strings['InstallPasswordLocation'] = 'ซึ่งจะสามารถพบได้ที่ %s ใน %s.';
        $strings['VerifyInstallSettings'] = 'ตรวจสอบการตั้งค่าเริ่มต้นต่อไปนี้ก่อนดำเนินการต่อ หรือคุณสามารถเปลี่ยนแปลงได้ใน %s';
        $strings['DatabaseName'] = 'ชื่อฐานข้อมูล';
        $strings['DatabaseUser'] = 'ชื่อผู้ใช้ฐานข้อมูล';
        $strings['DatabaseHost'] = 'โฮสต์ของฐานข้อมูล';
        $strings['DatabaseCredentials'] = 'คุณต้องให้ข้อมูลผู้ใช้ MySQL ที่มีสิทธิในการสร้างฐานข้อมูล ถ้าคุณไม่ทราบให้ติดต่อผู้ดูแลระบบฐานข้อมูลของคุณ ในหลายกรณี รูท จะทำงานได้';
        $strings['MySQLUser'] = 'ชื่อผู้ใช้ MySQL';
        $strings['InstallOptionsWarning'] = 'ตัวเลือกต่อไปนี้อาจไม่ทำงานในสภาพแวดล้อมแบบโฮสต์ ถ้าคุณกำลังติดตั้งในสภาพแวดล้อมแบบโฮสต์ให้ใช้เครื่องมือวิซาร์ด MySQL เพื่อทำตามขั้นตอนต่อไปนี้';
        $strings['CreateDatabase'] = 'สร้างฐานข้อมูล';
        $strings['CreateDatabaseUser'] = 'สร้างผู้ใช้งานฐานข้อมูล';
        $strings['PopulateExampleData'] = 'นำเข้าตัวอย่างข้อมูล สร้างบัญชีผู้ดูแลระบบ: admin/password และบัญชีผู้ใช้: user/password';
        $strings['DataWipeWarning'] = 'ระวัง: การดำเนินการนี้จะลบข้อมูลที่มีอยู่เดิม';
        $strings['RunInstallation'] = 'เริ่มการติดตั้ง';
        $strings['UpgradeNotice'] = 'คุณกำลังจพอัพเกรดจากเวอร์ชั่น<b>%s</b>  ไปยังเวอร์ชั่น <b>%s</b>';
        $strings['RunUpgrade'] = 'เริ่มการอัพเกรด';
        $strings['Executing'] = 'กำลังดำเนินการ';
        $strings['StatementFailed'] = 'ผิดพลาด รายละเอียด:';
        $strings['SQLStatement'] = 'คำชี้แจง SQL:';
        $strings['ErrorCode'] = 'รหัสข้อผิดพลาด:';
        $strings['ErrorText'] = 'ข้อความแสดงขอ้ผิดพลาด:';
        $strings['InstallationSuccess'] = 'การติดตั้งเสร็จสมบูรณ์!';
        $strings['RegisterAdminUser'] = 'การลงทะเบียนผู้ดูแลระบบนี้ จำเป็นอย่างยิ่งหากคุณไม่ได้นำเข้าข้อมูลตัวอย่าง แต่ทั้งนี้ต้องแน่ใจว่า  $conf[\'settings\'][\'allow.self.registration\'] = \'true\' ในไฟล์ %s .';
        $strings['LoginWithSampleAccounts'] = 'หากคุณนำเข้าข้อมูลตัวอย่างคุณสามารถเข้าสู่ระบบด้วย admin / password สำหรับผู้ใช้ admin หรือ user / password สำหรับผู้ใช้ขั้นพื้นฐาน';
        $strings['InstalledVersion'] = 'คุณกำลังใช้งาน LibreBooking รุ่น %s';
        $strings['InstallUpgradeConfig'] = 'ขอแนะนำให้อัปเกรดไฟล์คอนฟิกของคุณ';
        $strings['InstallationFailure'] = 'เกิดปัญหากับการติดตั้ง โปรดแก้ไขปัญหาเหล่านี้และลองติดตั้งอีกครั้ง';
        $strings['ConfigureApplication'] = 'การตั้งค่า LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'ไฟล์ config ของคุณได้รับการอัพเดทแล้ว !';
        $strings['ConfigUpdateFailure'] = 'เราไม่สามารถอัพเดทไฟล์ config ของคุณได้แบบอัตโนมัติ. กรุณาเขียนทับเนื่อหาของ config.php ตามนี้:';
        $strings['SelectUser'] = 'เลือกผู้ใช้ ';
        $strings['InviteUsers'] = 'เชิญชวนผู้ใช้';
        $strings['InviteUsersLabel'] = 'ป้อนที่อยู่อีเมล์ของผู้ที่ต้องการเชิญชวน';
        // End Install

        // Errors
        $strings['LoginError'] = 'เราไม่สามารถจับคู่ชื่อผู้ใช้หรือรหัสผ่านของคุณได้';
        $strings['ReservationFailed'] = 'การจองของคุณไม่สามารถดำเนินการได้';
        $strings['MinNoticeError'] = 'การจองนี้ต้องแจ้งให้ทราบล่วงหน้า วันที่และเวลาที่เร็วที่สุดที่สามารถจองได้คือ %s';
        $strings['MaxNoticeError'] = 'การจองนี้ไม่สามารถทำได้ในอนาคต วันที่และเวลาล่าสุดที่สามารถจองได้คือ %s';
        $strings['MinDurationError'] = 'การจองนี้ต้องใช้เวลาอย่างน้อย %s';
        $strings['MaxDurationError'] = 'การจองนี้ไม่สามารถใช้งานได้นานกว่า %s';
        $strings['ConflictingAccessoryDates'] = 'มีอุปกรณ์เสริมต่อไปนี้ไม่เพียงพอ:';
        $strings['NoResourcePermission'] = 'คุณไม่มีสิทธิ์เข้าถึงแหล่งข้อมูลที่ร้องขออย่างน้อยหนึ่งรายการ';
        $strings['ConflictingReservationDates'] = 'มีการจองที่ขัดแย้งกันในวันที่ต่อไปนี้:';
        $strings['StartDateBeforeEndDateRule'] = 'วันที่และเวลาเริ่มต้นต้องอยู่ก่อนวันที่และเวลาสิ้นสุด';
        $strings['RecurringWithoutTerminationRule'] = 'จำเป็นต้องระบุวันที่สิ้นสุดสำหรับการปิดกั้นที่เกิดซ้ำ';
        $strings['StartIsInPast'] = 'วันที่และเวลาเริ่มต้นไม่สามารถใช้วันที่ผ่านมาแล้วได้';
        $strings['EmailDisabled'] = 'ผู้ดูแลระบบปิดการแจ้งเตือนทางอีเมล';
        $strings['ValidLayoutRequired'] = 'สล็อตต้องมีการให้บริการตลอด 24 ชั่วโมงของวันเริ่มต้นและสิ้นสุดในเวลา 12:00 AM ';
        $strings['CustomAttributeErrors'] = 'มีปัญหาเกี่ยวกับแอตทริบิวต์เพิ่มเติมที่คุณระบุ:';
        $strings['CustomAttributeRequired'] = '%s เป็นฟิลด์ที่ต้องระบุ';
        $strings['CustomAttributeInvalid'] = 'ค่าที่ระบุสำหรับ %s ไม่ถูกต้อง';
        $strings['AttachmentLoadingError'] = 'ขออภัยเกิดปัญหาในการโหลดไฟล์ที่ขอ';
        $strings['InvalidAttachmentExtension'] = 'คุณสามารถอัปโหลดไฟล์ประเภท: %s ได้';
        $strings['InvalidStartSlot'] = 'วันที่เริ่มต้นและเวลาที่ร้องขอไม่ถูกต้อง';
        $strings['InvalidEndSlot'] = 'วันที่สิ้นสุดและเวลาที่ร้องขอไม่ถูกต้อง';
        $strings['MaxParticipantsError'] = '%s สามารถสนับสนุนผู้เข้าร่วมได้ %s เท่านั้น';
        $strings['ReservationCriticalError'] = 'เกิดข้อผิดพลาดที่สำคัญในการบันทึกการจองของคุณ หากยังคงดำเนินการต่ออยู่ให้ติดต่อผู้ดูแลระบบ';
        $strings['InvalidStartReminderTime'] = 'เวลาเตือนความจำเริ่มต้นไม่ถูกต้อง';
        $strings['InvalidEndReminderTime'] = 'เวลาการแจ้งเตือนไม่ถูกต้อง';
        $strings['QuotaExceeded'] = 'เกินโควต้าแล้ว';
        $strings['MultiDayRule'] = '%s ไม่อนุญาตให้มีการจองข้ามวัน';
        $strings['InvalidReservationData'] = 'มีปัญหาเกี่ยวกับคำขอจองของคุณ';
        $strings['PasswordError'] = 'รหัสผ่านต้องประกอบด้วย %s ตัวอักษรและอย่างน้อย %s ตัวเลข';
        $strings['PasswordErrorRequirements'] = 'รหัสผ่านต้องประกอบด้วยตัวพิมพ์ใหญ่และตัวพิมพ์เล็กและ %s ตัวเลขเป็นอย่างน้อย';
        $strings['NoReservationAccess'] = 'คุณไม่ได้รับอนุญาตให้เปลี่ยนการจองนี้';
        $strings['PasswordControlledExternallyError'] = 'รหัสผ่านของคุณถูกควบคุมโดยระบบภายนอกและไม่สามารถอัพเดตที่นี่ได้';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'อุปกรณ์เสริม %s สามารถจองได้เฉพาะกับทรัพยากร %s เท่านั้น';
        $strings['AccessoryMinQuantityErrorMessage'] = 'คุณต้องจองอย่างน้อย %s ของอุปกรณ์เสริม %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'คุณไม่สามารถจองได้มากกว่า %s ของอุปกรณ์เสริม %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'อุปกรณ์เสริม \'%s\' ไม่สามารถจองได้ตามทรัพยากรที่ร้องขอ';
        $strings['NoResources'] = 'คุณยังไม่ได้เพิ่มทรัพยการใด ๆ ในระบบ';
        $strings['ParticipationNotAllowed'] = 'คุณไม่ได้รับอนุญาตให้เข้าร่วมการจองนี้';
        $strings['ReservationCannotBeCheckedInTo'] = 'การจองนี้ไม่สามารถเช็คอินได้';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'การจองนี้ไม่สามารถเช็คเอาท์ได้';
        $strings['InvalidEmailDomain'] = 'ที่อยู่อีเมลนั้นไม่ได้มาจากโดเมนที่อนุญาต';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'เริ่มสร้างการจอง';
        $strings['EditReservation'] = 'แก้ไขการจอง';
        $strings['LogIn'] = 'เข้าสู่ระบบ';
        $strings['ManageReservations'] = 'การจอง';
        $strings['AwaitingActivation'] = 'รอการยืนยันบัญชีสมาชิก';
        $strings['PendingApproval'] = 'รอการอนุมัติ';
        $strings['ManageSchedules'] = 'ตารางการจอง';
        $strings['ManageResources'] = 'ทรัพยากร';
        $strings['ManageAccessories'] = 'อุปกรณ์เสริม';
        $strings['ManageUsers'] = 'ผู้ใช้';
        $strings['ManageGroups'] = 'กลุ่ม';
        $strings['ManageQuotas'] = 'โควต้า';
        $strings['ManageBlackouts'] = 'เวลางดจอง';
        $strings['MyDashboard'] = 'แดชบอร์ดของฉัน';
        $strings['ServerSettings'] = 'การตั้งค่าเซิร์ฟเวอร์';
        $strings['Dashboard'] = 'แดชบอร์ด';
        $strings['Help'] = 'ช่วยเหลือ';
        $strings['Administration'] = 'ผู้ดูแลระบบ';
        $strings['About'] = 'เกี่ยวกับ';
        $strings['Bookings'] = 'การจอง';
        $strings['Schedule'] = 'ตารางการจอง';
        $strings['Account'] = 'บัญชีสมาชิก';
        $strings['EditProfile'] = 'แก้ไขข้อมูลผู้ใช้ของฉัน';
        $strings['FindAnOpening'] = 'ค้นหาการเปิด';
        $strings['OpenInvitations'] = 'เปิดการเชิญชวน';
        $strings['ResourceCalendar'] = 'ปฏิทินการจองทรัพยากร';
        $strings['Reservation'] = 'การจองใหม่';
        $strings['Install'] = 'การติดตั้ง';
        $strings['ChangePassword'] = 'เปลี่ยนรหัสผ่าน';
        $strings['MyAccount'] = 'บัญชีของฉัน';
        $strings['Profile'] = 'ข้อมูลผู้ใช้';
        $strings['ApplicationManagement'] = 'การจัดการระบบจอง';
        $strings['ForgotPassword'] = 'ลืมรหัสผ่าน';
        $strings['NotificationPreferences'] = 'ตั้งค่าการแจ้งเตือน';
        $strings['ManageAnnouncements'] = 'ประกาศ';
        $strings['Responsibilities'] = 'ผู้รับผิดชอบ';
        $strings['GroupReservations'] = 'การของแบบกลุ่ม';
        $strings['ResourceReservations'] = 'การจองทรัพยากร';
        $strings['Customization'] = 'การปรับแต่ง';
        $strings['Attributes'] = 'คุณลักษณะ';
        $strings['AccountActivation'] = 'บัญชีสมาชิกได้รับการยืนยันแล้ว';
        $strings['ScheduleReservations'] = 'ตารางการจอง';
        $strings['Reports'] = 'รายงาน';
        $strings['GenerateReport'] = 'สร้างรายงานใหม่';
        $strings['MySavedReports'] = 'รายงานที่ฉันบันทึกไว้';
        $strings['CommonReports'] = 'รายงานทั่วไป';
        $strings['ViewDay'] = 'ดูวัน';
        $strings['Group'] = 'กลุ่ม';
        $strings['ManageConfiguration'] = 'การปรับแต่งระบบการจอง';
        $strings['LookAndFeel'] = 'หน้าตาและมุมมอง';
        $strings['ManageResourceGroups'] = 'กลุ่มของทรัพยากร';
        $strings['ManageResourceTypes'] = 'ชนิดของทรัพยากร';
        $strings['ManageResourceStatus'] = 'สถานะของทรัพยากร';
        $strings['ReservationColors'] = 'สีในการจอง';
        $strings['SearchReservations'] = 'ค้นหาการจอง';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'อา.';
        $strings['DayMondaySingle'] = 'จ.';
        $strings['DayTuesdaySingle'] = 'อ.';
        $strings['DayWednesdaySingle'] = 'พ.';
        $strings['DayThursdaySingle'] = 'พฤ.';
        $strings['DayFridaySingle'] = 'ศ';
        $strings['DaySaturdaySingle'] = 'ส';

        $strings['DaySundayAbbr'] = 'อา.';
        $strings['DayMondayAbbr'] = 'จ.';
        $strings['DayTuesdayAbbr'] = 'อ.';
        $strings['DayWednesdayAbbr'] = 'พ.';
        $strings['DayThursdayAbbr'] = 'พฤ.';
        $strings['DayFridayAbbr'] = 'ศ.';
        $strings['DaySaturdayAbbr'] = 'ส.';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'การจองของคุณได้รับการอนุมัติแล้ว';
        $strings['ReservationCreatedSubject'] = 'การจองของคุณได้ถูกสร้างแล้ว';
        $strings['ReservationUpdatedSubject'] = 'การจองของคุณได้ถูกอัพเดทแล้ว';
        $strings['ReservationDeletedSubject'] = 'การจองของคุณถูกลบออกแล้ว';
        $strings['ReservationCreatedAdminSubject'] = 'การแจ้งเตือน: มีการจองถูกสร้างขึ้น';
        $strings['ReservationUpdatedAdminSubject'] = 'การแจ้งเตือน: มีการอัพเดทการจอง';
        $strings['ReservationDeleteAdminSubject'] = 'การแจ้งเตือน: มีการลบการจอง';
        $strings['ReservationApprovalAdminSubject'] = 'การแจ้งเตือน: การจองจำเป็นต้องได้รับการอนุมัติจากคุณ';
        $strings['ParticipantAddedSubject'] = 'แจ้งการเข้าร่วมการจอง';
        $strings['ParticipantDeletedSubject'] = 'การจองถูกลบแล้ว';
        $strings['InviteeAddedSubject'] = 'คำเชิญการจอง';
        $strings['ResetPasswordRequest'] = 'การร้องขอรีเซ็ทรหัสผ่าน';
        $strings['ActivateYourAccount'] = 'กรุณาเปิดใช้งานบัญชีของคุณ';
        $strings['ReportSubject'] = 'รายงานที่คุณร้องขอ (%s)';
        $strings['ReservationStartingSoonSubject'] = 'การจองสำหรับ %s กำลังจะเริ่มต้นแล้ว';
        $strings['ReservationEndingSoonSubject'] = 'การจองสำหรับ %s กำลังจะสิ้นสุดแล้ว';
        $strings['UserAdded'] = 'ผู้ใช้ใหม่ได้ถูกเพิ่มแล้ว';
        $strings['UserDeleted'] = 'บัญชีสมาชิกของ %s ถูกลบออกโดย %s';
        $strings['GuestAccountCreatedSubject'] = 'รายละเอียดบัญชีสมาชิกของคุณ';
        $strings['InviteUserSubject'] = '%s ได้เชิญคุณเข้าร่วม %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'การจองสำหรับ %s ได้รับการอนุมัติแล้ว';
        $strings['ReservationCreatedSubjectWithResource'] = 'การจองสำหรับ %s ได้ถูกสร้างแล้ว';
        $strings['ReservationUpdatedSubjectWithResource'] = 'การอัพเดทการจองสำหรับ %s ได้ดำเนินการแล้ว';
        $strings['ReservationDeletedSubjectWithResource'] = 'การลบการจองสำหรับ %s ได้ดำเนินการแล้ว';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'การแจ้งเตือน: การจองสำหรับ %s ได้ถูกสร้าง';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'การแจ้งเตือน: มีการอัพเดทการจองสำหรับ %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'การแจ้งเตือน: มีการลบการจองสำหรับ %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'การแจ้งเตือน: มีการจองสำหรับ %s รอการอนุมัติ';
        $strings['ParticipantAddedSubjectWithResource'] = '%s เพิ่มคุณในการจองสำหรับ %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s ลบคุณในการจองสำหรับ %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s เชิญคุณในการจองสำหรับ %s';
        $strings['MissedCheckinEmailSubject'] = 'ไม่ได้รับการเช็คอินสำหรับ %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'คุณไม่มีการจองที่ผ่านมา';
        $strings['PastReservations'] = 'การจองที่ผ่านมา';
        $strings['AllNoPastReservations'] = 'ไม่มีการจองที่ผ่านมาใน %s วันที่ผ่านมา';
        $strings['AllPastReservations'] = 'การจองที่ผ่านมาทั้งหมด';
        $strings['Yesterday'] = 'เมื่อวาน';
        $strings['EarlierThisWeek'] = 'ก่อนหน้านี้ในสัปดาห์นี้';
        $strings['PreviousWeek'] = 'สัปดาห์ที่แล้ว';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'กลุ่มของคุณไม่มีการจองที่จะมาถึง';
        $strings['GroupUpcomingReservations'] = 'การจองที่มีต่อไปของกลุ่มของฉัน';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'เกิดข้อผิดพลาดขณะเข้าสู่ระบบด้วย Facebook กรุณาลองอีกครั้ง';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'คุณไม่มีการจองที่รอการอนุมัติ';
        $strings['PendingApprovalReservations'] = 'การจองรอการอนุมัติ';
        $strings['LaterThisMonth'] = 'ในภายหลังเดือนนี้';
        $strings['LaterThisYear'] = 'ในภายหลังปีนี้';
        $strings['Remaining'] = 'ที่เหลือ';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'ไม่มีการจองเช็คเอาท์ที่ขาดหายไป';
        $strings['MissingCheckOutReservations'] = 'การจองเช็คเอาท์ที่ขาดหายไป';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'ไม่สามารถดูรายละเอียดการจองเนื่องจากคุณไม่มีสิทธิ์ที่เพียงพอที่จะเข้าถึงทรัพยากรใด ๆ ในการจองนี้';
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
        $days['full'] = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        // The three letter abbreviation
        $days['abbr'] = ['อา.', 'จ.', 'อ.', 'พ.', 'พฤ', 'ศ.', 'ส.'];
        // The two letter abbreviation
        $days['two'] = ['อา', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];
        // The one letter abbreviation
        $days['letter'] = ['อา', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.'];

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
        $months['full'] = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
        // The three letter month name
        $months['abbr'] = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];

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
        return 'th';
    }
}
