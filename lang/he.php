<?php

/* Translation for Hebrew interface. Prepared November 2012 by
   Yosef Branse, Younes and Soraya Nazarian Library, University of Haifa.
   Hebrew encoding is UTF-8.
   Corrections and comments may be sent to: jody@univ.haifa.ac.il

   N.B. The term 'resource' has been consistently translated as 'חדר' (room)
   rather than the literal 'משאב', since the application was used by the
   University of Haifa Library for room reservations only. A site which
   enables reservation of different resources may adapt the translation
   as necessary.
*/

require_once('Language.php');
require_once('en_gb.php');

class he extends en_gb
{
    public function __construct()
    {
        parent::__construct();

        $this->TextDirection = 'rtl';
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'שם פרטי';
        $strings['LastName'] = 'שמ משפחה';
        $strings['Timezone'] = 'אזור זמן';
        $strings['Edit'] = 'עריכה';
        $strings['Change'] = 'לשנות';
        $strings['Rename'] = 'שם אחר';
        $strings['Remove'] = 'להסיר';
        $strings['Delete'] = 'למחוק';
        $strings['Update'] = 'לעדכן';
        $strings['Cancel'] = 'לבטל שינויים';
        $strings['Add'] = 'להוסיף';
        $strings['Name'] = 'שם';
        $strings['Yes'] = 'כן';
        $strings['No'] = 'לא';
        $strings['FirstNameRequired'] = 'שם פרטי חיוני.';
        $strings['LastNameRequired'] = 'שם משפחה חיוני.';
        $strings['PwMustMatch'] = 'אימות סיסמה חייבת להיות זהה לסיסמה.';
        $strings['PwComplexity'] = 'סיסמה חייבת להיות לפחות 6 תווים, עם צירוף של אותיות, ספרות וסימנים מיוחדים.';
        $strings['ValidEmailRequired'] = 'כתובת דואר אלקטרוני תקינה - חובה.';
        $strings['UniqueEmailRequired'] = 'כתובת דואר אלקטרוני זו כבר רשומה.';
        $strings['UniqueUsernameRequired'] = 'שם משתמש זה כבר שרום.';
        $strings['UserNameRequired'] = 'שם משתמש - חובה.';
        $strings['CaptchaMustMatch'] = 'נא להקיש תווים מתמונת הבטחון בדיוק כפי שהם מוצגים.';
        $strings['Today'] = 'היום';
        $strings['Week'] = 'שבוע';
        $strings['Month'] = 'יחודש';
        $strings['BackToCalendar'] = 'חזרה ללוח';
        $strings['BeginDate'] = 'תאריך התחלה';
        $strings['EndDate'] = 'תאריך סופי';
        $strings['Username'] = 'שם משתמש';
        $strings['Password'] = 'סיסמה';
        $strings['PasswordConfirmation'] = 'אימות סיסמה';
        $strings['DefaultPage'] = 'דף בית - ברירת מחדל';
        $strings['MyCalendar'] = 'לוח שלי';
        $strings['ScheduleCalendar'] = 'לוח שבועי';
        $strings['Registration'] = 'הרשמה';
        $strings['NoAnnouncements'] = 'אין הודעות';
        $strings['Announcements'] = 'הודעות';
        $strings['NoUpcomingReservations'] = 'אין לך הזמנות עתידיות';
        $strings['UpcomingReservations'] = 'הזמנות עתידיות';
        $strings['AllNoUpcomingReservations'] = 'אין הזמנות עתידיות ב-%s הימים הבאים';
        $strings['AllUpcomingReservations'] = 'כל ההזמנות העתידיות';
        $strings['ShowHide'] = 'להציג/להסתיר';
        $strings['Error'] = 'שגיאה';
        $strings['ReturnToPreviousPage'] = 'חזרה לדף הקודם';
        $strings['UnknownError'] = 'שגיאה לא ידועה';
        $strings['InsufficientPermissionsError'] = 'אין לך הרשאות לגשת לחדר זה';
        $strings['MissingReservationResourceError'] = 'לא נבחר שום חדר';
        $strings['MissingReservationScheduleError'] = 'לא נבחר שום לוח';
        $strings['DoesNotRepeat'] = 'אין הישנות';
        $strings['Daily'] = 'יומי';
        $strings['Weekly'] = 'שבועי';
        $strings['Monthly'] = 'חדשי';
        $strings['Yearly'] = 'שנתי';
        $strings['RepeatPrompt'] = 'הישנות';
        $strings['hours'] = 'שעות';
        $strings['days'] = 'ימים';
        $strings['weeks'] = 'שבועות';
        $strings['months'] = 'חודשים';
        $strings['years'] = 'שנים';
        $strings['day'] = 'יום';
        $strings['week'] = 'שבוע';
        $strings['month'] = 'חודש';
        $strings['year'] = 'שנה';
        $strings['repeatDayOfMonth'] = 'יום בחודש';
        $strings['repeatDayOfWeek'] = 'יום בשבוע';
        $strings['RepeatUntilPrompt'] = 'עד';
        $strings['RepeatEveryPrompt'] = 'כל';
        $strings['RepeatDaysPrompt'] = 'על';
        $strings['CreateReservationHeading'] = 'הזמנה חדשה';
        $strings['EditReservationHeading'] = 'עריכת הזמנה %s';
        $strings['ViewReservationHeading'] = 'צפייה בהזמנה %s';
        $strings['ReservationErrors'] = 'לשנות הזמנה';
        $strings['Create'] = 'יצירת הזמנה';
        $strings['ThisInstance'] = 'מקרה זה בלבד';
        $strings['AllInstances'] = 'כל מקרים';
        $strings['FutureInstances'] = 'מקרים עתידיים';
        $strings['Print'] = 'הדפס';
        $strings['ShowHideNavigation'] = 'להציג/להסתיר ניווט';
        $strings['ReferenceNumber'] = 'מספר אסמכתא';
        $strings['Tomorrow'] = 'מחר';
        $strings['LaterThisWeek'] = 'יותר מאוחר בשבוע זה';
        $strings['NextWeek'] = 'שבוע הבא';
        $strings['SignOut'] = 'להתנתק';
        $strings['LayoutDescription'] = 'מתחיל ב- %s, מראה %s ימים ביחד';
        $strings['AllResources'] = 'כל החדרים';
        $strings['TakeOffline'] = 'Take Offline';
        $strings['BringOnline'] = 'Bring Online';
        $strings['AddImage'] = 'להוסיף תמונה';
        $strings['NoImage'] = 'אין תמונה';
        $strings['Move'] = 'להזיז';
        $strings['AppearsOn'] = 'מופיע ב- %s';
        $strings['Location'] = 'מקום';
        $strings['NoLocationLabel'] = '(מקום לא נקבע)';
        $strings['Contact'] = 'איש קשר';
        $strings['NoContactLabel'] = '(אין איש קשר)';
        $strings['Description'] = 'תאור';
        $strings['NoDescriptionLabel'] = '(אין תאור)';
        $strings['Notes'] = 'הערות';
        $strings['NoNotesLabel'] = '(אין הערות)';
        $strings['NoTitleLabel'] = '(אין כותר)';
        $strings['UsageConfiguration'] = 'קונפיגורציית שימוש';
        $strings['ChangeConfiguration'] = 'לשנות קונפיגורציה';
        $strings['ResourceMinLength'] = 'הזמנה חייבת להיות לפחות %s';
        $strings['ResourceMinLengthNone'] = 'אין אורך מינימאלי להזמנות';
        $strings['ResourceMaxLength'] = 'הזמנה לא יכולה להיות יותר מ- %s';
        $strings['ResourceMaxLengthNone'] = 'אין אורך מקסימאלי להזמנות';
        $strings['ResourceRequiresApproval'] = 'הזמנות כפופות לאישור';
        $strings['ResourceRequiresApprovalNone'] = 'הזמנות לא כפופות לאישור';
        $strings['ResourcePermissionAutoGranted'] = 'אישור ניתן באופן אוטומטי';
        $strings['ResourcePermissionNotAutoGranted'] = 'אישור לא ניתן באופן אוטומטי';
        $strings['ResourceMinNotice'] = 'יש להזמין לפחות %s לפני הזמן המבוקש';
        $strings['ResourceMinNoticeNone'] = 'אפשר להזמין עד לזמן הנוכחי';
        $strings['ResourceMaxNotice'] = 'הזמנות חייבות להסתיים תוך %s מהזמן הנוכחי';
        $strings['ResourceMaxNoticeNone'] = 'הזמנות יכולות להסתיים בכל זמן שהו בעתיד';
        $strings['ResourceAllowMultiDay'] = 'הזמנות יכולות לגלוש על-פני מספר ימים';
        $strings['ResourceNotAllowMultiDay'] = 'הזמנות לא יכולות לגלוש על-פני מספר ימים';
        $strings['ResourceCapacity'] = 'לחדר זה יש מקום למקסימום %s אנשים';
        $strings['ResourceCapacityNone'] = 'לחדר זה אין הגבלה במספר אנשים';
        $strings['AddNewResource'] = 'להוסיף חדר חדש';
        $strings['AddNewUser'] = 'להוסיף משתמש חדש';
        $strings['AddUser'] = 'להוסיף משתמש';
        $strings['Schedule'] = 'לוח שבועי';
        $strings['AddResource'] = 'להוסיף חדר';
        $strings['Capacity'] = 'קיבולת';
        $strings['Access'] = 'גישה';
        $strings['Duration'] = 'משך';
        $strings['Active'] = 'פעיל';
        $strings['Inactive'] = 'לא פעיל';
        $strings['ResetPassword'] = 'איפוס סיסמה';
        $strings['LastLogin'] = 'התחברות אחרונה';
        $strings['Search'] = 'חיפוש';
        $strings['ResourcePermissions'] = 'הרשאות לחדר';
        $strings['Reservations'] = 'הזמנות';
        $strings['Groups'] = 'קבוצות';
        $strings['ResetPassword'] = 'איפוס סיסמה';
        $strings['Users'] = 'משתמשים';
        $strings['AllUsers'] = 'כל משתמשים';
        $strings['AllGroups'] = 'כל קבוצות';
        $strings['AllSchedules'] = 'כל הלוחות';
        $strings['UsernameOrEmail'] = 'שם משתמש או דואר אלקטרוני';
        $strings['Members'] = 'חברים';
        $strings['QuickSlotCreation'] = 'ליצור יחידות זמן כל %s דקות בין %s ו- %s';
        $strings['ApplyUpdatesTo'] = 'ליישם עדכונים ל-';
        $strings['CancelParticipation'] = 'בטל השתתפות';
        $strings['Attending'] = 'משתתף';
        $strings['QuotaConfiguration'] = 'קיימת הגבלה ב%s ל%s משתמשים ב%s מוגבלים ל%s %s ל%s';
        $strings['QuotaEnforcement'] = 'נאכף %s %s';
        $strings['reservations'] = 'הזמנות';
        $strings['reservation'] = 'הזמנה';
        $strings['ChangeCalendar'] = 'לשנות לוח שבועי';
        $strings['AddQuota'] = 'להוסיף מכסה';
        $strings['FindUser'] = 'לאתר משתמש';
        $strings['Created'] = 'נוצר';
        $strings['LastModified'] = 'עדכון אחרון ';
        $strings['GroupName'] = 'שם קבוצה';
        $strings['GroupMembers'] = 'חברי קבוצה';
        $strings['GroupRoles'] = 'תפקידי קבוצה';
        $strings['GroupAdmin'] = 'מנהל קבוצה';
        $strings['Actions'] = 'פעולות';
        $strings['CurrentPassword'] = 'סיסמה נוכחית';
        $strings['NewPassword'] = 'סיסמה חדשה';
        $strings['InvalidPassword'] = 'סיסמה נוכחית לא נכונה';
        $strings['PasswordChangedSuccessfully'] = 'סיסמה שלך שונתה בהצלחה';
        $strings['SignedInAs'] = 'מחובר כ: ';
        $strings['NotSignedIn'] = 'טרם מזוהה';
        $strings['ReservationTitle'] = 'כותר להזמנה';
        $strings['ReservationDescription'] = 'תאור הזמנה';
        $strings['ResourceList'] = 'חדרים זמינים';
        $strings['Accessories'] = 'אביזרים';
        $strings['Add'] = 'להוסיף';
        $strings['ParticipantList'] = 'משתתפים';
        $strings['InvitationList'] = 'מוזמנים';
        $strings['AccessoryName'] = 'שם אביזר';
        $strings['QuantityAvailable'] = 'כמות זמינה';
        $strings['Resources'] = 'חדרים';
        $strings['Participants'] = 'משתתפים';
        $strings['User'] = 'משתמש';
        $strings['Resource'] = 'חדר';
        $strings['Status'] = 'סטטוס';
        $strings['Approve'] = 'לאשר';
        $strings['Page'] = 'דף';
        $strings['Rows'] = 'שורות';
        $strings['Unlimited'] = 'ללא הגבלה';
        $strings['Email'] = 'דואר אלקטרוני';
        $strings['EmailAddress'] = 'כתובת דואר אלקטרוני';
        $strings['Phone'] = 'טלפון';
        $strings['Organization'] = 'ארגון';
        $strings['Position'] = 'תפקיד';
        $strings['Language'] = 'שפה';
        $strings['Permissions'] = 'הרשאות';
        $strings['Reset'] = 'איפוס';
        $strings['FindGroup'] = 'לאתר קבוצנ';
        $strings['Manage'] = 'ניהול';
        $strings['None'] = 'כלום';
        $strings['AddToOutlook'] = 'להוסיף ללוח השנה';
        $strings['Done'] = 'בוצע';
        $strings['RememberMe'] = 'לזכור אותי';
        $strings['FirstTimeUser?'] = '?משתמש חדש';
        $strings['CreateAnAccount'] = 'ליצור חשבון';
        $strings['ViewSchedule'] = 'צפייה בלוח';
        $strings['ForgotMyPassword'] = 'שכחתי את הסיסמה שלי';
        $strings['YouWillBeEmailedANewPassword'] = 'תישלח אליך בדואר אלקטרוני סיסמה חדשה, מחוללת באופן אוטומטי';
        $strings['Close'] = 'לסגור';
        $strings['ExportToCSV'] = 'ייצוא ל-CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'עובד...';
        $strings['Login'] = 'התחברות';
        $strings['AdditionalInformation'] = 'מידע נוסף';
        $strings['AllFieldsAreRequired'] = 'כל השדות חובות';
        $strings['Optional'] = 'לא חובה';
        $strings['YourProfileWasUpdated'] = 'הפרופיל שלך עודכן';
        $strings['YourSettingsWereUpdated'] = 'עודכנו כוונונים שלך';
        $strings['Register'] = 'הרשמה';
        $strings['SecurityCode'] = 'קוד בטחון';
        $strings['ReservationCreatedPreference'] = 'כאשר אני עושה הזמנה או שהזמנה נעשתה בשבילי';
        $strings['ReservationUpdatedPreference'] = 'כאשר אני מעדכן הזמנה או שהזמנה עודכנה בשבילי';
        $strings['ReservationDeletedPreference'] = 'כאשר אני מבטל הזמנה או שהזמנה בוטלה בשבילי';
        $strings['ReservationApprovalPreference'] = 'כאשר תאושר הזמנה שביקשתי';
        $strings['PreferenceSendEmail'] = 'שלח לי מייל';
        $strings['PreferenceNoEmail'] = 'אל תודיע לי';
        $strings['ReservationCreated'] = '!ההזמנה שלך נוצרה בהצלחה';
        $strings['ReservationUpdated'] = '!ההזמנה שלך עודכנה בהצלחה';
        $strings['ReservationRemoved'] = '!ההזמנה שלך בוטלה';
        $strings['ReservationRequiresApproval'] = 'אחד או יותר מהחדרים שהזמנת דורשים אישור לפני השימוש.  הזמנה זו תשאר בהמתנה עד לאישורה.';
        $strings['YourReferenceNumber'] = ' %s מספר האסמכתא שלך הוא:';
        $strings['UpdatingReservation'] = 'מעדכן הזמנה';
        $strings['ChangeUser'] = 'שלשנות משתמש';
        $strings['MoreResources'] = 'חדרים נוספים';
        $strings['ReservationLength'] = 'אורך הזמנה';
        $strings['ParticipantList'] = 'רשימת משתתפים';
        $strings['AddParticipants'] = 'להוסיף משתתפים';
        $strings['InviteOthers'] = 'להזמין אחרים';
        $strings['AddResources'] = 'להוסיף חדרים';
        $strings['AddAccessories'] = 'להוסיף אביזרים';
        $strings['Accessory'] = 'אביזר';
        $strings['QuantityRequested'] = 'כמות מבוקשת';
        $strings['CreatingReservation'] = 'יוצר הזמנה';
        $strings['UpdatingReservation'] = 'מעדכן הזמנה';
        $strings['DeleteWarning'] = 'פעולה זו היא קבועה ובלתי-הפיך!';
        $strings['DeleteAccessoryWarning'] = 'מחיקת אביזר זה תסיר אותו מכל הזמנות.';
        $strings['AddAccessory'] = 'להוסיף אביזר';
        $strings['AddBlackout'] = 'להוסיף חסימה';
        $strings['AllResourcesOn'] = 'כל חדרים ב-';
        $strings['Reason'] = 'סיבה';
        $strings['BlackoutShowMe'] = 'הצג הזמנות קיימות';
        $strings['BlackoutDeleteConflicts'] = 'מחק הזמנות קיימות';
        $strings['Filter'] = 'סינון';
        $strings['Between'] = 'בין';
        $strings['CreatedBy'] = 'נוצר על-ידי:';
        $strings['BlackoutCreated'] = 'נוצרה חסימה!';
        $strings['BlackoutNotCreated'] = 'לא ניתן ליצור חסימה!';
        $strings['BlackoutUpdated'] = 'חסימה עודכנה';
        $strings['BlackoutNotUpdated'] = 'החסימה לא עודכנה';
        $strings['BlackoutConflicts'] = 'יש זמני חסימה סותרים';
        $strings['ReservationConflicts'] = 'יש הזמנות סותרות';
        $strings['UsersInGroup'] = 'משתמשים בקבוצה זו';
        $strings['Browse'] = 'דפדף';
        $strings['DeleteGroupWarning'] = 'מחיקת קבוצה זו תסיר כל הרשאות לחדרים המשוייכות לקבוצה. משתמשים בקבוצה עלולים לאבד גישה לחדרים.';
        $strings['WhatRolesApplyToThisGroup'] = 'איזה תפקידים יש לקובצה זו?';
        $strings['WhoCanManageThisGroup'] = 'מי יכול לנהל קבוצה זו?';
        $strings['WhoCanManageThisSchedule'] = 'מי יכול לנהל לוח זה?';
        $strings['AddGroup'] = 'להוסיף קבוצה';
        $strings['AllQuotas'] = 'כל מכסים';
        $strings['QuotaReminder'] = 'לזכור: אכיפת מכסים היא לפי אזור הזמן של לוח-הזמן.';
        $strings['AllReservations'] = 'כל הזמנות';
        $strings['PendingReservations'] = 'הזמנות בהמתנה';
        $strings['Approving'] = 'מאשר';
        $strings['MoveToSchedule'] = 'לעבור ללוח';
        $strings['DeleteResourceWarning'] = 'מחיקת חדר זה תמחוק כל מידע השייך לו, כולל';
        $strings['DeleteResourceWarningReservations'] = 'כל הזמנות בעבר ובעתיד הקשורות לחדר';
        $strings['DeleteResourceWarningPermissions'] = 'כל הרשאות';
        $strings['DeleteResourceWarningReassign'] = 'נא להעביר כל דבר שברצונך לשמור  לפני המשך פעולה זו';
        $strings['ScheduleLayout'] = 'Layout (all times %s)';
        $strings['ReservableTimeSlots'] = 'שעות זמינות';
        $strings['BlockedTimeSlots'] = 'זמנים חסומים';
        $strings['ThisIsTheDefaultSchedule'] = 'לוח זה הוא ברירת המחדל';
        $strings['DefaultScheduleCannotBeDeleted'] = 'לוח ברירת מחדל - לא ניתן למחוק';
        $strings['MakeDefault'] = 'לקבוע כברירת מחדל';
        $strings['BringDown'] = 'להוריד';
        $strings['ChangeLayout'] = 'לשנות מערכך';
        $strings['AddSchedule'] = 'להוסיף לוח';
        $strings['StartsOn'] = 'מתחיל ב-';
        $strings['NumberOfDaysVisible'] = 'מספר ימים נראים';
        $strings['UseSameLayoutAs'] = 'להשתמש באולו מערך כמו';
        $strings['Format'] = 'פורמט';
        $strings['OptionalLabel'] = 'תגית אופציונאלית';
        $strings['LayoutInstructions'] = 'יש לרשום פרק זמן אחד בכל שורה ולרשום זמנים לכל 24 שעות היממה, החל מחצות הלילה.';
        $strings['AddUser'] = 'להוסיף משתמש';
        $strings['UserPermissionInfo'] = 'גישה בפועל לחדר יכולה להיות תלויה בתפקיד משתמש, הרשאות קבוצה וא הרשאות חיצוניות';
        $strings['DeleteUserWarning'] = 'מחיקת משתחש זה תסיר כל הזמנות שלו - שוטפות, עתידיות, וארכיוניות.';
        $strings['AddAnnouncement'] = 'להוסיף הודעה';
        $strings['Announcement'] = 'הודעה';
        $strings['Priority'] = 'עדיפות';
        $strings['Reservable'] = 'זמין';
        $strings['Unreservable'] = 'סגור';
        $strings['Reserved'] = 'תפוס';
        $strings['MyReservation'] = 'הזמנות שלי';
        $strings['Pending'] = 'בהמתנה';
        $strings['Past'] = 'זמן בעבר';
        $strings['Restricted'] = 'מוגבל';
        $strings['Participant'] = 'משתתף';
        $strings['ViewAll'] = 'צפייה בכל';
        $strings['MoveResourcesAndReservations'] = 'להעביר חדרים והזמנות ל';
        $strings['TurnOffSubscription'] = 'בטל מינויים ללוחות זמנים';
        $strings['TurnOnSubscription'] = 'לאפשר מינויים ללוח זמן זה';
        $strings['SubscribeToCalendar'] = 'לרשום מינוי ללוח זה';
        $strings['SubscriptionsAreDisabled'] = 'מנהל המערכת נטרל מינויים ללוחות-זמנים';
        $strings['NoResourceAdministratorLabel'] = '(אין מנהל לחדר זה)';
        $strings['WhoCanManageThisResource'] = 'מי יכול לנהל חדר זה?';
        $strings['ResourceAdministrator'] = 'מנהל חדרים';
        $strings['Private'] = 'פרטי';
        $strings['Accept'] = 'לקבל';
        $strings['Decline'] = 'לוותר';
        $strings['ShowFullWeek'] = 'להציג שבוע שלם';
        $strings['CustomAttributes'] = 'מאפיינים מקומיים';
        $strings['AddAttribute'] = 'להוסיף מאפיין';
        $strings['EditAttribute'] = 'לעדכן מאפיין';
        $strings['DisplayLabel'] = 'להציג כיתוב';
        $strings['Type'] = 'סוג';
        $strings['Required'] = 'חובה';
        $strings['ValidationExpression'] = 'ביטוי אימות';
        $strings['PossibleValues'] = 'ערכים אפשריים';
        $strings['SingleLineTextbox'] = 'תיבת טקסט בעלת שורה אחת';
        $strings['MultiLineTextbox'] = 'תיבת טקסט בעלת מספר שורות';
        $strings['Checkbox'] = 'Checkbox';
        $strings['SelectList'] = 'רשימת אופציות';
        $strings['CommaSeparated'] = 'פסיק כסימן הפרדה';
        $strings['Category'] = 'סיווג';
        $strings['CategoryReservation'] = 'הזמנה';
        $strings['CategoryGroup'] = 'קבוצה';
        $strings['SortOrder'] = 'סדר מיון';
        $strings['Title'] = 'כותר';
        $strings['AdditionalAttributes'] = 'מאפיינים נוספים';
        $strings['True'] = 'נכון';
        $strings['False'] = 'לא נכון';
        $strings['ForgotPasswordEmailSent'] = 'נשלחה הודעה לכתובת מייל הרשומה, עם הוראות לאיפוס סיסמה שלך';
        $strings['ActivationEmailSent'] = ' בקרוב תקבל הודעה מייל להפעלה.';
        $strings['AccountActivationError'] = 'מצטער, לא יכלנו להפעיל את החשבון שלך.';
        $strings['Attachments'] = 'צרופות';
        $strings['AttachFile'] = 'לצרף קובץ';
        $strings['Maximum'] = 'מקסימום';
        $strings['NoScheduleAdministratorLabel'] = 'אין מנהל ללוח';
        $strings['ScheduleAdministrator'] = 'מנהל הלוח';
        $strings['Total'] = 'סה"כ';
        $strings['QuantityReserved'] = 'כמות מוזמנת';
        $strings['AllAccessories'] = 'כל אביזרים';
        $strings['GetReport'] = 'הפק דוח';
        $strings['NoResultsFound'] = 'לא נמצאו תוצות מתאימות';
        $strings['SaveThisReport'] = 'לשמור דוח זה';
        $strings['ReportSaved'] = '!דוח שמור';
        $strings['EmailReport'] = 'לשלוח דוח במייל';
        $strings['ReportSent'] = '!דוח נשלח';
        $strings['RunReport'] = 'הפק דוח';
        $strings['NoSavedReports'] = 'אין לך דוחות שמורים.';
        $strings['CurrentWeek'] = 'שבוע נוכחי';
        $strings['CurrentMonth'] = 'חודש נוכחי';
        $strings['AllTime'] = 'כל זמנים';
        $strings['FilterBy'] = 'לסנן לפי';
        $strings['Select'] = 'בחר';
        $strings['List'] = 'רשימה';
        $strings['TotalTime'] = 'זמן מצטבר';
        $strings['Count'] = 'ספירה';
        $strings['Usage'] = 'Usage';
        $strings['AggregateBy'] = 'להסתכם לפי';
        $strings['Range'] = 'טווח';
        $strings['Choose'] = 'בחר';
        $strings['All'] = 'הכל';
        $strings['ViewAsChart'] = 'להציג כתרשים';
        $strings['ReservedResources'] = 'חדרים מוזמנים';
        $strings['ReservedAccessories'] = 'אביזרים מוזמנים';
        $strings['ResourceUsageTimeBooked'] = 'שימוש בחדר - לפי זמן מוזמן';
        $strings['ResourceUsageReservationCount'] = 'שימוש בחדר - לפי מספר הזמנות';
        $strings['Top20UsersTimeBooked'] = '20 משתמשים הכי פעילים - לפי זמן מוזמן';
        $strings['Top20UsersReservationCount'] = '20 משתמשים הכי פעילים - לפי מספר הזמנות';
        $strings['ConfigurationUpdated'] = 'קובץ ההגדרות עודכן';
        $strings['ConfigurationUiNotEnabled'] = 'אין אפשרות לגשת לעמוד זה מכיוון שהפרמטר $conf[\'settings\'][\'pages\'][\'enable.configuration\'] מוגדר כ false או חסר.';
        $strings['ConfigurationFileNotWritable'] = 'קובץ ההגדרות אינו ניתן לכתיבה. אנא בדקו את הרשאות הקובץ ובדקו שוב.';
        $strings['ConfigurationUpdateHelp'] = 'קראו בפרק ה-Configuration של <a target=_blank href=%s>קובץ העזרה</a> לתיעוד נוסף על הגדרות אלו.';
        $strings['GeneralConfigSettings'] = 'הגדרות';
        $strings['UseSameLayoutForAllDays'] = 'השתמשו בעיצוב זהה עבור כל הימים';
        $strings['LayoutVariesByDay'] = 'עיצוב משתנה לפי יום';
        $strings['ManageReminders'] = 'תזכורות';
        $strings['ReminderUser'] = 'שם משתמש ';
        $strings['ReminderMessage'] = 'הודעה';
        $strings['ReminderAddress'] = 'כתובות';
        $strings['ReminderSendtime'] = 'זמן שליחה';
        $strings['ReminderRefNumber'] = 'Reservation Reference Number';
        $strings['ReminderSendtimeDate'] = 'Date of Reminder';
        $strings['ReminderSendtimeTime'] = 'Time of Reminder (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'הוספת תזכורת';
        $strings['DeleteReminderWarning'] = 'Are you sure you want to delete this?';
        $strings['NoReminders'] = 'אין תזכורות קרורובת.';
        $strings['Reminders'] = 'תזכורות';
        $strings['SendReminder'] = 'שליחת תזכורת';
        $strings['minutes'] = 'דקות';
        $strings['hours'] = 'שעות';
        $strings['days'] = 'ימים';
        $strings['ReminderBeforeStart'] = 'לפני שעת התחלה';
        $strings['ReminderBeforeEnd'] = 'לפני שעת סיום';
        $strings['Logo'] = 'לוגו';
        $strings['CssFile'] = 'קובץ CSS';
        $strings['ThemeUploadSuccess'] = 'Your changes have been saved. Refresh the page for changes to take effect.';
        $strings['MakeDefaultSchedule'] = 'Make this my default schedule';
        $strings['DefaultScheduleSet'] = 'This is now your default schedule';
        $strings['FlipSchedule'] = 'Flip the schedule layout';
        $strings['Next'] = 'הבא';
        $strings['Success'] = 'הצלחה';
        $strings['Participant'] = 'משתתף';
        $strings['ResourceFilter'] = 'סינון חדרים';
        $strings['ResourceGroups'] = 'קבוצות חדרים';
        $strings['AddNewGroup'] = 'הוספת קבוצה';
        $strings['Quit'] = 'יציאה';
        $strings['AddGroup'] = 'הוספת קבוצה';
        $strings['StandardScheduleDisplay'] = 'Use the standard schedule display';
        $strings['TallScheduleDisplay'] = 'Use the tall schedule display';
        $strings['WideScheduleDisplay'] = 'Use the wide schedule display';
        $strings['CondensedWeekScheduleDisplay'] = 'Use condensed week schedule display';
        $strings['ResourceGroupHelp1'] = 'Drag and drop resource groups to reorganize.';
        $strings['ResourceGroupHelp2'] = 'Right click a resource group name for additional actions.';
        $strings['ResourceGroupHelp3'] = 'Drag and drop resources to add them to groups.';
        $strings['ResourceGroupWarning'] = 'If using resource groups, each resource must be assigned to at least one group. Unassigned resources will not be able to be reserved.';
        $strings['ResourceType'] = 'סוג חדר';
        $strings['AppliesTo'] = 'משפיע על';
        $strings['UniquePerInstance'] = 'ייחודי למופע';
        $strings['AddResourceType'] = 'הוספת סוג חדר';
        $strings['NoResourceTypeLabel'] = '(no resource type set)';
        $strings['ClearFilter'] = 'ניקוי הטופס';
        $strings['MinimumCapacity'] = 'מספר אנשים מינימלי';
        $strings['Color'] = 'Color';
        $strings['Available'] = 'זמין';
        $strings['Unavailable'] = 'לא זמין';
        $strings['Hidden'] = 'מוסתר';
        $strings['ResourceStatus'] = 'סטטוס חדר';
        $strings['CurrentStatus'] = 'סטטוס נוכחי';
        $strings['AllReservationResources'] = 'כל החדרים בהזמנה';
        $strings['File'] = 'קובץ';
        $strings['BulkResourceUpdate'] = 'עדכון חדרים בבת אחת';
        $strings['Unchanged'] = 'ללא שינוי';
        $strings['Common'] = 'Common';
        $strings['AdminOnly'] = 'Is Admin Only';
        $strings['AdvancedFilter'] = 'סינון מתקדם';
        $strings['MinimumQuantity'] = 'כמות מינימלחת';
        $strings['MaximumQuantity'] = 'כמות מקסימלית';
        $strings['ChangeLanguage'] = 'שינוי שפה';
        $strings['AddRule'] = 'הוספת כלל';
        $strings['Attribute'] = 'מאפיין';
        $strings['RequiredValue'] = 'ערך נדרש';
        $strings['ReservationCustomRuleAdd'] = 'If %s then the reservation color will be';
        $strings['AddReservationColorRule'] = 'Add Reservation Color Rule';
        $strings['LimitAttributeScope'] = 'Collect In Specific Cases';
        $strings['CollectFor'] = 'Collect For';
        $strings['SignIn'] = 'Sign In';
        $strings['AllParticipants'] = 'All Participants';
        $strings['RegisterANewAccount'] = 'Register A New Account';
        $strings['Dates'] = 'תאריכים';
        $strings['More'] = 'עוד';
        $strings['ResourceAvailability'] = 'זמינות החדרים';
        $strings['UnavailableAllDay'] = 'לא זמין כל היום';
        $strings['AvailableUntil'] = 'זמין עד';
        $strings['AvailableBeginningAt'] = 'זמין החל מ';
        $strings['AvailableAt'] = 'זמין ב';
        $strings['AllResourceTypes'] = 'כל סוגי החדרים';
        $strings['AllResourceStatuses'] = 'כל סטטוסי החדרים';
        $strings['AllowParticipantsToJoin'] = 'אפשרו למשתתפים להצטרף';
        $strings['Join'] = 'הצטרפו';
        $strings['YouAreAParticipant'] = 'You are a participant of this reservation';
        $strings['YouAreInvited'] = 'You are invited to this reservation';
        $strings['YouCanJoinThisReservation'] = 'You can join this reservation';
        $strings['Import'] = 'ייבוא';
        $strings['GetTemplate'] = 'Get Template';
        $strings['UserImportInstructions'] = '<ul><li>File must be in CSV format.</li><li>Username and email are required fields.</li><li>Attribute validity will not be enforced.</li><li>Leaving other fields blank will set default values and \'password\' as the user\'s password.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['RowsImported'] = 'Rows Imported';
        $strings['RowsSkipped'] = 'Rows Skipped';
        $strings['Columns'] = 'טורים';
        $strings['Reserve'] = 'הזמן';
        $strings['AllDay'] = 'כל יום';
        $strings['Everyday'] = 'בכל הימים';
        $strings['IncludingCompletedReservations'] = 'וכולל הזמנות שהושלמו';
        $strings['NotCountingCompletedReservations'] = 'ללא התחשבות בהזמנות שמולאו';
        $strings['RetrySkipConflicts'] = 'דילוג על הזמנות מתנגשות';
        $strings['Retry'] = 'לנסות מחדש';
        $strings['RemoveExistingPermissions'] = 'Remove existing permissions?';
        $strings['Continue'] = 'Continue';
        $strings['WeNeedYourEmailAddress'] = 'יש צורך בכתובת האימייל שלך לביצוע ההזמנה';
        $strings['ResourceColor'] = 'צבע החדר';
        $strings['DateTime'] = 'Date Time';
        $strings['AutoReleaseNotification'] = 'Automatically released if not checked in within %s minutes';
        $strings['RequiresCheckInNotification'] = 'Requires check in/out';
        $strings['NoCheckInRequiredNotification'] = 'Does not require check in/out';
        $strings['RequiresApproval'] = 'Requires Approval';
        $strings['CheckingIn'] = 'Checking In';
        $strings['CheckingOut'] = 'Checking Out';
        $strings['CheckIn'] = 'Check In';
        $strings['CheckOut'] = 'Check Out';
        $strings['ReleasedIn'] = 'משתחרר בעוד';
        $strings['CheckedInSuccess'] = 'You are checked in';
        $strings['CheckedOutSuccess'] = 'You are checked out';
        $strings['CheckInFailed'] = 'You could not be checked in';
        $strings['CheckOutFailed'] = 'You could not be checked out';
        $strings['CheckInTime'] = 'Check In Time';
        $strings['CheckOutTime'] = 'Check Out Time';
        $strings['OriginalEndDate'] = 'Original End';
        $strings['SpecificDates'] = 'הראה תאריכים ספציפיים';
        $strings['Users'] = 'משתמשים';
        $strings['Guest'] = 'אורח';
        $strings['ResourceDisplayPrompt'] = 'Resource to Display';
        $strings['Credits'] = 'Credits';
        $strings['AvailableCredits'] = 'Available Credits';
        $strings['CreditUsagePerSlot'] = 'Requires %s credits per slot (off peak)';
        $strings['PeakCreditUsagePerSlot'] = 'Requires %s credits per slot (peak)';
        $strings['CreditsRule'] = 'You do not have enough credits. Credits required: %s. Credits in account: %s';
        $strings['PeakTimes'] = 'זמני שיא';
        $strings['AllYear'] = 'כל השנה';
        $strings['MoreOptions'] = 'אפשרויות נוספות';
        $strings['SendAsEmail'] = 'שליחה באימייל';
        $strings['UsersInGroups'] = 'משתמשים בקבוצות';
        $strings['UsersWithAccessToResources'] = 'משתמשים עם גישה לחדרים';
        $strings['AnnouncementSubject'] = 'הודעה חדשה פורסמה ב-%s';
        $strings['AnnouncementEmailNotice'] = 'משתמשים יקבלו הודעה זו באימייל';
        $strings['Day'] = 'יום';
        $strings['NotifyWhenAvailable'] = 'הודיעו לי כשיתפנה החדר';
        $strings['AddingToWaitlist'] = 'התווספת לרשימת ההמתנה';
        $strings['WaitlistRequestAdded'] = 'הודעה תשלח אליך אם זמן זה יתפנה';
        $strings['PrintQRCode'] = 'הדפסת קוד QR';
        $strings['FindATime'] = 'מציאת חדר פנוי';
        $strings['AnyResource'] = 'כל החדרים';
        $strings['ThisWeek'] = 'השבוע';
        $strings['Hours'] = 'שעות';
        $strings['Minutes'] = 'דקות';
        $strings['ImportICS'] = 'יבוא מ-ICS';
        $strings['ImportQuartzy'] = 'יבוא מ-Quartzy';
        $strings['OnlyIcs'] = 'ניתן להעלות רק קבצי *.ics.';
        $strings['IcsLocationsAsResources'] = 'Locations will be imported as resources.';
        $strings['IcsMissingOrganizer'] = 'Any event missing an organizer will have the owner set to the current user.';
        $strings['IcsWarning'] = 'Reservation rules will not be enforced - conflicts, duplicates, etc are possible.';
        $strings['BlackoutAroundConflicts'] = 'Blackout around conflicting reservations';
        $strings['DuplicateReservation'] = 'שכפול הזמנה';
        $strings['UnavailableNow'] = 'לא זמין כעת';
        $strings['ReserveLater'] = 'להזמין אחר כך';
        $strings['CollectedFor'] = 'נאסף עבור';
        $strings['IncludeDeleted'] = 'כולל הזמנות שנמחקו';
        $strings['Deleted'] = 'נמחק';
        $strings['Back'] = 'אחורה';
        $strings['Forward'] = 'קדימה';
        $strings['DateRange'] = 'טווח תאריכים';
        $strings['Copy'] = 'העתק';
        $strings['Detect'] = 'זיהוי';
        $strings['Autofill'] = 'מילוי אוטומטי';
        $strings['NameOrEmail'] = 'שם או אימייל';
        $strings['ImportResources'] = 'ייבוא חדרים';
        $strings['ExportResources'] = 'ייצוא חדרים';
        $strings['ResourceImportInstructions'] = '<ul><li>File must be in CSV format with UTF-8 encoding.</li><li>Name is required field. Leaving other fields blank will set default values.</li><li>Status options are \'Available\', \'Unavailable\' and \'Hidden\'.</li><li>Color should be the hex value. ex) #ffffff.</li><li>Auto assign and approval columns can be true or false.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource groups.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>File must be in CSV format with UTF-8 encoding.</li><li>Email, resource names, begin, and end are required fields.</li><li>Begin and end require full date time. Recommended format is YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Rules, conflicts, and valid time slots will not be checked.</li><li>Notifications will not be sent.</li><li>Attribute validity will not be enforced.</li><li>Comma separate multiple resource names.</li><li>Use the supplied template as an example.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Autorelease Minutes';
        $strings['CreditsPeak'] = 'Credits (peak)';
        $strings['CreditsOffPeak'] = 'Credits (off peak)';
        $strings['ResourceMinLengthCsv'] = 'Reservation Minimum Length';
        $strings['ResourceMaxLengthCsv'] = 'Reservation Maximum Length';
        $strings['ResourceBufferTimeCsv'] = 'זמן חציצה';
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
        $strings['ReasonText'] = 'Reason text';
        $strings['NoAvailableMatchingTimes'] = 'There are no available times that match your search';
        $strings['Schedules'] = 'Schedules';
        $strings['NotifyUser'] = 'Notify User';
        $strings['UpdateUsersOnImport'] = 'Update existing user if email already exists';
        $strings['UpdateResourcesOnImport'] = 'Update existing resources if name already exists';
        $strings['Reject'] = 'Reject';
        $strings['CheckingAvailability'] = 'Checking availability';
        // End Strings


        // Errors
        $strings['LoginError'] = 'לא הצלחנו לוודא שם או סיסמה שלך';
        $strings['ReservationFailed'] = 'לא ניתן לבצע את ההזמנה שלך';
        $strings['MinNoticeError'] = 'הזמנה זו דורשת התראה מראש. המועד הכי קרוב שניתן להזמין הוא %s.';
        $strings['MaxNoticeError'] = 'לא ניתן להזמין בעתיד יותר מאוחר מ- %s.';
        $strings['MinDurationError'] = 'משך ההזמנה חייב להיות לפחות %s.';
        $strings['MaxDurationError'] = 'משך ההזמנה חייב להיות פחות מ- %s.';
        $strings['ConflictingAccessoryDates'] = 'אין מספיק מהאביזרים הבאים:';
        $strings['NoResourcePermission'] = 'אין לך הרשאות לגשת לאחד או יותר מהמשאבים המבוקשים';
        $strings['ConflictingReservationDates'] = 'כבר קיימת הזמנה לחדר זה באותו מועד:';
        $strings['StartDateBeforeEndDateRule'] = 'תאריך/שעת התחלה חייב להיות לפני תאריך/שעת סיום';
        $strings['RecurringWithoutTerminationRule'] = 'נדרש תאריך סיום עבור חסימות חוזרות.';
        $strings['StartIsInPast'] = 'תאריך/שעת התחלה לא יכול להיות בעבר';
        $strings['EmailDisabled'] = 'מנהל המערכת ביטל הודעות באמצעות דואר אלקטרוני';
        $strings['ValidLayoutRequired'] = 'יש לשבץ כל 24 שעות היממא, החל ומסיים בחצות הלילה.';
        $strings['CustomAttributeErrors'] = 'יש בעיות עם המאפיינים הנוספים שהגדרת:';
        $strings['CustomAttributeRequired'] = '%s הוא שדה חובה';
        $strings['CustomAttributeInvalid'] = 'הערך שניתן עבור %s אינו תקין';
        $strings['AttachmentLoadingError'] = 'מצטער - הייתה תקלה בהאעלת הקובץ המבוקש.';
        $strings['InvalidAttachmentExtension'] = 'ניתן להעלות קבצים מסוג: %s בלבד';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'ליצור הזמנה';
        $strings['EditReservation'] = 'לערוך הזמנה';
        $strings['LogIn'] = 'התחברות';
        $strings['ManageReservations'] = 'הזמנות';
        $strings['AwaitingActivation'] = 'ממתין להפעלה';
        $strings['PendingApproval'] = 'ממתינה לאישור';
        $strings['ManageSchedules'] = 'לוחות';
        $strings['ManageResources'] = 'חדרים';
        $strings['ManageAccessories'] = 'אביזרים';
        $strings['ManageUsers'] = 'משתמשים';
        $strings['ManageGroups'] = 'קבוצות';
        $strings['ManageQuotas'] = 'מכסות';
        $strings['ManageBlackouts'] = 'זמנים חסומים';
        $strings['MyDashboard'] = 'עמוד ההזמנות שלי';
        $strings['ServerSettings'] = 'כוונון שרת';
        $strings['Dashboard'] = 'ההזמנות שלי';
        $strings['Help'] = 'עזרה';
        $strings['Administration'] = 'ניהול';
        $strings['About'] = 'על';
        $strings['Bookings'] = 'הזמנות';
        $strings['Schedule'] = 'הזמנות וחדרים';
        $strings['Reservations'] = 'הזמנות';
        $strings['Account'] = 'חשבון';
        $strings['EditProfile'] = 'לערוך פריפיל שלי';
        $strings['FindAnOpening'] = 'לחפש זמן פנוי';
        $strings['OpenInvitations'] = 'הזמנות פתוחות';
        $strings['MyCalendar'] = 'הלוח שלי';
        $strings['ResourceCalendar'] = 'לוח חדרים';
        $strings['Reservation'] = 'הזמנה חדשה';
        $strings['Install'] = 'התקנה';
        $strings['ChangePassword'] = 'להחליף סיסמה';
        $strings['MyAccount'] = 'החשבון שלי';
        $strings['Profile'] = 'פרופיל';
        $strings['ApplicationManagement'] = 'ניהול יישום';
        $strings['ForgotPassword'] = 'שכחתי סיסמה';
        $strings['NotificationPreferences'] = 'העדפות - הודעות';
        $strings['ManageAnnouncements'] = 'הודעות';
        $strings['Responsibilities'] = 'אחריויות';
        $strings['GroupReservations'] = 'הזמנות קבוצה';
        $strings['ResourceReservations'] = 'הזמנות חדר';
        $strings['Customization'] = 'התאמה מקומית';
        $strings['Attributes'] = 'מאפיינים';
        $strings['AccountActivation'] = 'הפעלת חשבון';
        $strings['ScheduleReservations'] = 'Schedule Reservations';
        $strings['Reports'] = 'דוחות';
        $strings['GenerateReport'] = 'ליצור דוח חדש';
        $strings['MySavedReports'] = 'דוחות שמורים שלי';
        $strings['CommonReports'] = 'דוחות שכיחים';
        $strings['ViewDay'] = 'הצג יום';
        $strings['Group'] = 'קבוצה';
        $strings['ManageConfiguration'] = 'הגדרות המערכת';
        $strings['LookAndFeel'] = 'עיצוב ומראה';
        $strings['ManageResourceGroups'] = 'קבוצות חדרים';
        $strings['ManageResourceTypes'] = 'סוגי חדרים';
        $strings['ManageResourceStatus'] = 'סטטוסי חדרים';
        $strings['ReservationColors'] = 'צבעי הזמנות';
        $strings['SearchReservations'] = 'חיפוש בהזמנות';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'יום א';
        $strings['DayMondaySingle'] = 'יום ב';
        $strings['DayTuesdaySingle'] = 'יום ג';
        $strings['DayWednesdaySingle'] = 'יום ד';
        $strings['DayThursdaySingle'] = 'יום ה';
        $strings['DayFridaySingle'] = 'יום ו';
        $strings['DaySaturdaySingle'] = 'שבת';

        $strings['DaySundayAbbr'] = 'יום א';
        $strings['DayMondayAbbr'] = 'יום ב';
        $strings['DayTuesdayAbbr'] = 'יום ג';
        $strings['DayWednesdayAbbr'] = 'יום ד';
        $strings['DayThursdayAbbr'] = 'יום ה';
        $strings['DayFridayAbbr'] = 'יום ו';
        $strings['DaySaturdayAbbr'] = 'שבת';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'ההזמנה שלך אושרה';
        $strings['ReservationCreatedSubject'] = 'ההזמנה שלך נוצרה';
        $strings['ReservationUpdatedSubject'] = 'ההזמנה שלך עודכנרה';
        $strings['ReservationDeletedSubject'] = 'ההזמנה שלך בוטלה';
        $strings['ReservationCreatedAdminSubject'] = 'הודעה: נוצרה הזמנה';
        $strings['ReservationUpdatedAdminSubject'] = 'הודעה: עודכנה הזמנה';
        $strings['ReservationDeleteAdminSubject'] = 'הודעה: בוטלה הזמנה';
        $strings['ParticipantAddedSubject'] = 'הודעה על הוספת משתמש';
        $strings['ParticipantDeletedSubject'] = 'הזמנה הוסרה';
        $strings['InviteeAddedSubject'] = 'הזמנה';
        $strings['ResetPassword'] = 'בקשה לאיפוס סיסמה';
        $strings['ActivateYourAccount'] = 'נא להפעיל את חשבונך';
        $strings['ReportSubject'] = ' הדוח שביקשת (%s)';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'אין לך הזמנות קודמות';
        $strings['PastReservations'] = 'הזמנות קודמות';
        $strings['AllNoPastReservations'] = 'אין הזמנות קודמות ב- %s הימים האחרונים';
        $strings['AllPastReservations'] = 'כל הזמנות הקודמות';
        $strings['Yesterday'] = 'אתמול';
        $strings['EarlierThisWeek'] = 'בתחילת השבוע הזה';
        $strings['PreviousWeek'] = 'השבוע הקודם';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'אין לקבוצתך הזמנות עתידיות';
        $strings['GroupUpcomingReservations'] = 'הזמנות קבוצתי(ות) הבאות';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'אירעה שגיאה בעת ניסיון להתחבר עם Facebook. אנא נסה שוב.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'אין לך הזמנות בהמתנה לאישור';
        $strings['PendingApprovalReservations'] = 'ההזמנות בהמתנה לאישור';
        $strings['LaterThisMonth'] = 'מאוחר יותר החודש';
        $strings['LaterThisYear'] = 'מאוחר יותר השנה';
        $strings['Remaining'] = 'יתרה';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'אין הזמנות ליציאה חסרות';
        $strings['MissingCheckOutReservations'] = 'הזמנות ליציאה חסרות';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'לא ניתן לראות פרטי ההזמנה מכיוון שאין לך הרשאות לאף אחת מהמשאבים בהזמנה זו';
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
        $days['full'] = ['יום א', 'יום ב', 'יום ג', 'יום ד', 'יום ה', 'יום ו', 'שבת'];
        // The three letter abbreviation
        $days['abbr'] = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ש'];
        // The two letter abbreviation
        $days['two'] = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ש'];
        // The one letter abbreviation
        $days['letter'] = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ש'];

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
        $months['full'] = ['ינואר', 'פברואר', 'מרץ', 'אפריל', 'מאי', 'יוני', 'יולי', 'אוגוסט', 'ספטמבר', 'אוקטובר', 'נובמבר', 'דצמבר'];
        // The three letter month name
        $months['abbr'] = ['ינ.', 'פב.', 'מרץ', 'אפר.', 'מאי', 'יוני', 'יולי', 'אוג.', 'ספט.', 'אוקט.', 'נוב.', 'דצ.'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['א', 'ב', 'ג', 'ד', 'ה', 'ו', 'ז', 'ח', 'ט', 'י', 'כ', 'ל', 'מ', 'נ', 'ס', 'ע', 'פ', 'ק', 'ר', 'ש', 'ת'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'he';
    }
}
