<?php

require_once('Language.php');
require_once('en_gb.php');

class bg_bg extends en_gb
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

        $strings['FirstName'] = 'Име';
        $strings['LastName'] = 'Фамилия';
        $strings['Timezone'] = 'Часова зона';
        $strings['Edit'] = 'Редактиране';
        $strings['Change'] = 'Промяна';
        $strings['Rename'] = 'Преименуване';
        $strings['Remove'] = 'Премахване';
        $strings['Delete'] = 'Изтриване';
        $strings['Update'] = 'Обновяване';
        $strings['Cancel'] = 'Отказване';
        $strings['Add'] = 'Добавяне';
        $strings['Name'] = 'Име';
        $strings['Yes'] = 'Да';
        $strings['No'] = 'Не';
        $strings['FirstNameRequired'] = 'Името е задължително.';
        $strings['LastNameRequired'] = 'Фамилията е задължителна.';
        $strings['PwMustMatch'] = 'Повторението на паролата трябва да съвпада с нея.';
        $strings['PwComplexity'] = 'Паролата трябва да бъде поне 6 знака с комбинация от букви, цифри и символи.';
        $strings['ValidEmailRequired'] = 'Необходим е валиден email адрес.';
        $strings['UniqueEmailRequired'] = 'Този email адрес вече е регистриран.';
        $strings['UniqueUsernameRequired'] = 'Това потребителско име вече е регистрирано.';
        $strings['UserNameRequired'] = 'Необходимо е потребителско име.';
        $strings['CaptchaMustMatch'] = 'Моля, въведете буквите от изображението за сигурност, точно както е показано.';
        $strings['Today'] = 'Днес';
        $strings['Week'] = 'Седмица';
        $strings['Month'] = 'Месец';
        $strings['BackToCalendar'] = 'Обратно към клендар';
        $strings['BeginDate'] = 'Начало';
        $strings['EndDate'] = 'Край';
        $strings['Username'] = 'Потреб.име';
        $strings['Password'] = 'Парола';
        $strings['PasswordConfirmation'] = 'Потвърди парола';
        $strings['DefaultPage'] = 'Начална страница по подразбиране';
        $strings['MyCalendar'] = 'Моят календар';
        $strings['ScheduleCalendar'] = 'Календар на разписание';
        $strings['Registration'] = 'Регистрация';
        $strings['NoAnnouncements'] = 'Няма обяви';
        $strings['Announcements'] = 'Обяви';
        $strings['NoUpcomingReservations'] = 'Вие нямате предстоящи резервации';
        $strings['UpcomingReservations'] = 'Предстоящи резервации';
        $strings['ShowHide'] = 'Показване/Скриване';
        $strings['Error'] = 'Грешка';
        $strings['ReturnToPreviousPage'] = 'Назад към последната страница, на която сте били';
        $strings['UnknownError'] = 'Неизвестна грешка';
        $strings['InsufficientPermissionsError'] = 'Вие нямате права за достъп до този ресурс';
        $strings['MissingReservationResourceError'] = 'Не е избран ресурс';
        $strings['MissingReservationScheduleError'] = 'Не е избрано разписание';
        $strings['DoesNotRepeat'] = 'Не се повтаря';
        $strings['Daily'] = 'Дневно';
        $strings['Weekly'] = 'Седмично';
        $strings['Monthly'] = 'Месечно';
        $strings['Yearly'] = 'Годишно';
        $strings['RepeatPrompt'] = 'Повторение';
        $strings['hours'] = 'часове';
        $strings['days'] = 'дни';
        $strings['weeks'] = 'седмици';
        $strings['months'] = 'месеци';
        $strings['years'] = 'години';
        $strings['day'] = 'ден';
        $strings['week'] = 'седмица';
        $strings['month'] = 'месец';
        $strings['year'] = 'година';
        $strings['repeatDayOfMonth'] = 'ден от месец';
        $strings['repeatDayOfWeek'] = 'ден от седмица';
        $strings['RepeatUntilPrompt'] = 'Докато';
        $strings['RepeatEveryPrompt'] = 'Всеки';
        $strings['RepeatDaysPrompt'] = 'На';
        $strings['CreateReservationHeading'] = 'Създаване на нова резервация';
        $strings['EditReservationHeading'] = 'Редактиране на резервация %s';
        $strings['ViewReservationHeading'] = 'Преглед на резервация %s';
        $strings['ReservationErrors'] = 'Промяна на резервация';
        $strings['Create'] = 'Създаване';
        $strings['ThisInstance'] = 'Само този екземпляр';
        $strings['AllInstances'] = 'Всички екземпляри';
        $strings['FutureInstances'] = 'Бъдещи екземпляри';
        $strings['Print'] = 'Печат';
        $strings['ShowHideNavigation'] = 'Показване/скриване на навигация';
        $strings['ReferenceNumber'] = 'Референтен номер';
        $strings['Tomorrow'] = 'Утре';
        $strings['LaterThisWeek'] = 'По-късно тази седмица';
        $strings['NextWeek'] = 'Следваща седмица';
        $strings['SignOut'] = 'Изход';
        $strings['LayoutDescription'] = 'Започва на %s, показвайки %s дни едновременно';
        $strings['AllResources'] = 'Всички ресурси';
        $strings['TakeOffline'] = 'Деактивиране';
        $strings['BringOnline'] = 'Активиране';
        $strings['AddImage'] = 'Добавяне на изображение';
        $strings['NoImage'] = 'Не е присвоено изображение';
        $strings['Move'] = 'Преместване';
        $strings['AppearsOn'] = 'Показва се на %s';
        $strings['Location'] = 'Локация';
        $strings['NoLocationLabel'] = '(не е зададена локация)';
        $strings['Contact'] = 'Контакт';
        $strings['NoContactLabel'] = '(няма информация за контакт)';
        $strings['Description'] = 'Описание';
        $strings['NoDescriptionLabel'] = '(няма описание)';
        $strings['Notes'] = 'Бележки';
        $strings['NoNotesLabel'] = '(няма бележки)';
        $strings['NoTitleLabel'] = '(няма заглавие)';
        $strings['UsageConfiguration'] = 'Конфигуриране на използване';
        $strings['ChangeConfiguration'] = 'Промяна на конфигурация';
        $strings['ResourceMinLength'] = 'Резервациите трябва да продължат най-малко %s';
        $strings['ResourceMinLengthNone'] = 'Няма минимална продължителност на резервация';
        $strings['ResourceMaxLength'] = 'Резервациите не може да продължат повече от %s';
        $strings['ResourceMaxLengthNone'] = 'Няма максимална продължителност на резервация';
        $strings['ResourceRequiresApproval'] = 'Резервациите трябва да бъдат одобрени';
        $strings['ResourceRequiresApprovalNone'] = 'Резервациите не се нуждаят от одобрение';
        $strings['ResourcePermissionAutoGranted'] = 'Достъпите се дават автоматично';
        $strings['ResourcePermissionNotAutoGranted'] = 'Достъпите не се дават автоматично';
        $strings['ResourceMinNotice'] = 'Резервациите трябва да бъдат направени най-малко %s преди началото';
        $strings['ResourceMinNoticeNone'] = 'Резервации могат да бъдат правени до настоящия момент';
        $strings['ResourceMaxNotice'] = 'Резервациите не трябва да завършват повече от %s от текущото време';
        $strings['ResourceMaxNoticeNone'] = 'Резервациите може да завържат във всеки един момент в бъдещето';
        $strings['ResourceAllowMultiDay'] = 'Резервации могат да бъдат направени през дни';
        $strings['ResourceNotAllowMultiDay'] = 'Резервации не могат да бъдат направени през дни';
        $strings['ResourceCapacity'] = 'Този ресурс е с капацитет от %s човека';
        $strings['ResourceCapacityNone'] = 'Този ресурс е с неограничен капацитет';
        $strings['AddNewResource'] = 'Добавяне на нов ресурс';
        $strings['AddNewUser'] = 'Добавяне на нов потребител';
        $strings['AddUser'] = 'Добавяне на потребител';
        $strings['Schedule'] = 'Разписание';
        $strings['AddResource'] = 'Добавяне на ресурс';
        $strings['Capacity'] = 'Капацитет';
        $strings['Access'] = 'Достъп';
        $strings['Duration'] = 'Продължителност';
        $strings['Active'] = 'Активен';
        $strings['Inactive'] = 'Неактивен';
        $strings['ResetPassword'] = 'Смяна на парола';
        $strings['LastLogin'] = 'Последно влизане';
        $strings['Search'] = 'Търсене';
        $strings['ResourcePermissions'] = 'Достъпи до ресурс';
        $strings['Reservations'] = 'Резервации';
        $strings['Groups'] = 'Групи';
        $strings['ResetPassword'] = 'Смяна на парола';
        $strings['AllUsers'] = 'Всички потребители';
        $strings['AllGroups'] = 'Всички групи';
        $strings['AllSchedules'] = 'Всички разписания';
        $strings['UsernameOrEmail'] = 'Потреб. име или Email';
        $strings['Members'] = 'Членове';
        $strings['QuickSlotCreation'] = 'Създаване на слотове всеки %s минути между %s и %s';
        $strings['ApplyUpdatesTo'] = 'Прилагане na обновления към';
        $strings['CancelParticipation'] = 'Отказ Участие';
        $strings['Attending'] = 'Участие';
        $strings['QuotaConfiguration'] = 'На %s за %s потребители в %s са ограничени до %s %s за %s';
        $strings['reservations'] = 'резервации';
        $strings['ChangeCalendar'] = 'Промяна календар';
        $strings['AddQuota'] = 'Добавяне на квота';
        $strings['FindUser'] = 'Намиране на потребител';
        $strings['Created'] = 'Създаден';
        $strings['LastModified'] = 'Последна промяна на';
        $strings['GroupName'] = 'Име на група';
        $strings['GroupMembers'] = 'Членове на група';
        $strings['GroupRoles'] = 'Роли на група';
        $strings['GroupAdmin'] = 'Администратор на група';
        $strings['Actions'] = 'Действия';
        $strings['CurrentPassword'] = 'Текуща парола';
        $strings['NewPassword'] = 'Нова парола';
        $strings['InvalidPassword'] = 'Текущата парола е неправилна';
        $strings['PasswordChangedSuccessfully'] = 'Вашата парола е променена успешно';
        $strings['SignedInAs'] = 'Влезли сте като';
        $strings['NotSignedIn'] = 'Вие не сте влезли в';
        $strings['ReservationTitle'] = 'Наименование на резервация';
        $strings['ReservationDescription'] = 'Описание на резервация';
        $strings['ResourceList'] = 'Ресурсит които ще бъдат резервирани';
        $strings['Accessories'] = 'Аксесоари';
        $strings['Add'] = 'Добавяне';
        $strings['ParticipantList'] = 'Участници';
        $strings['InvitationList'] = 'Поканени';
        $strings['AccessoryName'] = 'Име на аксесоар';
        $strings['QuantityAvailable'] = 'Налично количество';
        $strings['Resources'] = 'Ресурси';
        $strings['Participants'] = 'Участници';
        $strings['User'] = 'Потребител';
        $strings['Resource'] = 'Ресурс';
        $strings['Status'] = 'Статус';
        $strings['Approve'] = 'Одобряване';
        $strings['Page'] = 'Страница';
        $strings['Rows'] = 'Редове';
        $strings['Unlimited'] = 'Неограничен';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email адрес';
        $strings['Phone'] = 'Телефон';
        $strings['Organization'] = 'Организация';
        $strings['Position'] = 'Позиция';
        $strings['Language'] = 'Език';
        $strings['Permissions'] = 'Достъпи';
        $strings['Reset'] = 'Въвеждане отново';
        $strings['FindGroup'] = 'Намиране на група';
        $strings['Manage'] = 'Управление';
        $strings['None'] = 'Никой';
        $strings['AddToOutlook'] = 'Добавяне към календар';
        $strings['Done'] = 'Готово';
        $strings['RememberMe'] = 'Запомни ме';
        $strings['FirstTimeUser?'] = 'Нов потребител?';
        $strings['CreateAnAccount'] = 'Регистрация';
        $strings['ViewSchedule'] = 'Разглеждане на разписание';
        $strings['ForgotMyPassword'] = 'Аз съм си забравил паролата';
        $strings['YouWillBeEmailedANewPassword'] = 'Ще бъде изпратена по email нова случайно генерирана парола';
        $strings['Close'] = 'Затваряне';
        $strings['ExportToCSV'] = 'Експорт в CSV';
        $strings['OK'] = 'О.К.';
        $strings['Working'] = 'Работещ...';
        $strings['Login'] = 'Влизане';
        $strings['AdditionalInformation'] = 'Допълнителна информация';
        $strings['AllFieldsAreRequired'] = 'всички полета са задължителни';
        $strings['Optional'] = 'незадължителен';
        $strings['YourProfileWasUpdated'] = 'Вашият профил бе актуализиран';
        $strings['YourSettingsWereUpdated'] = 'Вашите настройки бяха актуализирани';
        $strings['Register'] = 'Регистриране';
        $strings['SecurityCode'] = 'Код за сигурност';
        $strings['ReservationCreatedPreference'] = 'Когато създавам резервация или резервация е създадена от мое име';
        $strings['ReservationUpdatedPreference'] = 'Когато актуализирам резервация или резервация е актуализирана от мое име';
        $strings['ReservationDeletedPreference'] = 'Когато изтривам резервация или резервация е изтрита от мое име';
        $strings['ReservationApprovalPreference'] = 'Когато моя чакаща резервация е одобрена';
        $strings['PreferenceSendEmail'] = 'Изпратете ми email';
        $strings['PreferenceNoEmail'] = 'Не ме уведомявайте';
        $strings['ReservationCreated'] = 'Вашата резервация е създадена успешно!';
        $strings['ReservationUpdated'] = 'Вашата резервация е успешно актуализирана!';
        $strings['ReservationRemoved'] = 'Вашата резервация е премахната';
        $strings['YourReferenceNumber'] = 'Вашият референтен номер е %s';
        $strings['UpdatingReservation'] = 'Актуализиране на резервация';
        $strings['ChangeUser'] = 'Смяна на потребител';
        $strings['MoreResources'] = 'Още ресурси';
        $strings['ReservationLength'] = 'Времетраене на резервация';
        $strings['ParticipantList'] = 'Списък на участници';
        $strings['AddParticipants'] = 'Добавяне на Участници';
        $strings['InviteOthers'] = 'Покани други';
        $strings['AddResources'] = 'Добавяне на ресурси';
        $strings['AddAccessories'] = 'Добавяне на аксесоари';
        $strings['Accessory'] = 'Аксесоари';
        $strings['QuantityRequested'] = 'Искано количество';
        $strings['CreatingReservation'] = 'Създаване на резервация';
        $strings['UpdatingReservation'] = 'Актуализиране на Резервация';
        $strings['DeleteWarning'] = 'Това действие е постоянно и необратимо!';
        $strings['DeleteAccessoryWarning'] = 'Изтриването на този аксесоар ще го отстрани от всички резервации.';
        $strings['AddAccessory'] = 'Добавяне на аксесоар';
        $strings['AddBlackout'] = 'Добавяне на забрана';
        $strings['AllResourcesOn'] = 'Всички ресурси включени';
        $strings['Reason'] = 'Причина';
        $strings['BlackoutShowMe'] = 'Покажи ми противоречиви резервации';
        $strings['BlackoutDeleteConflicts'] = 'Изтриване на конфликтни резервации';
        $strings['Filter'] = 'Филтър';
        $strings['Between'] = 'Между';
        $strings['CreatedBy'] = 'Създаден от';
        $strings['BlackoutCreated'] = 'Създадена забрана!';
        $strings['BlackoutNotCreated'] = 'Не може да бъде създадена забрана!';
        $strings['BlackoutConflicts'] = 'Има противоречиви времена на забрана';
        $strings['ReservationConflicts'] = 'Има противоречиви времена на резервации';
        $strings['UsersInGroup'] = 'Потребители в тази група';
        $strings['Browse'] = 'Разглеждане';
        $strings['DeleteGroupWarning'] = 'Изтриването на тази група ще премахне всички свързани достъпи до ресурси. Потребителите в тази група може да загубят достъпи до ресурси.';
        $strings['WhatRolesApplyToThisGroup'] = 'Какви роли се отнасят към тази група?';
        $strings['WhoCanManageThisGroup'] = 'Кой може да управлява тази група?';
        $strings['WhoCanManageThisSchedule'] = 'Кой може да управлява това разписание?';
        $strings['AddGroup'] = 'Добавяне на група';
        $strings['AllQuotas'] = 'Всички квоти';
        $strings['QuotaReminder'] = 'Запомнете: Квотите се прилагат въз основа на часовата зона на разписанието.';
        $strings['AllReservations'] = 'Всички резервации';
        $strings['PendingReservations'] = 'Чакащи резервации';
        $strings['Approving'] = 'Одобряване';
        $strings['MoveToSchedule'] = 'Преместване в разписание';
        $strings['DeleteResourceWarning'] = 'Изтриване на този ресурс ще изтрие всички свързаните с него данни, включително';
        $strings['DeleteResourceWarningReservations'] = 'всички минали, настоящи и бъдещи резервации, свързани с него';
        $strings['DeleteResourceWarningPermissions'] = 'всички зададени права';
        $strings['DeleteResourceWarningReassign'] = 'Моля да превъзложите всичко, което не искате да бъде изтрито, преди да продължите';
        $strings['ScheduleLayout'] = 'Оформление (всички времена %s)';
        $strings['ReservableTimeSlots'] = 'Времеви слотове за резервиране';
        $strings['BlockedTimeSlots'] = 'Блокирани времеви слотове';
        $strings['ThisIsTheDefaultSchedule'] = 'Това е разписанието по подразбиране';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Разписанието по подразбиране не може да бъде изтрито';
        $strings['MakeDefault'] = 'По подразбиране';
        $strings['BringDown'] = 'Намаляване';
        $strings['ChangeLayout'] = 'Промяна на оформлението';
        $strings['AddSchedule'] = 'Добавяне на Разписание';
        $strings['StartsOn'] = 'Започва на';
        $strings['NumberOfDaysVisible'] = 'Брой дни видим';
        $strings['UseSameLayoutAs'] = 'Използвайте същото оформление като';
        $strings['Format'] = 'Формат';
        $strings['OptionalLabel'] = 'Незадължителен етикет';
        $strings['LayoutInstructions'] = 'Въведете по един времеви интервал на ред. Трябва да бъдат осигурени интервали за всички 24 часа на денонощие, започваики и завършвайки в 12:00 ч.';
        $strings['AddUser'] = 'Добавяне на потребител';
        $strings['UserPermissionInfo'] = 'Реалният достъп до ресурс може да бъде различен, в зависимост от ролята на потребителя, достъпите на групата или външно зададени достъпи';
        $strings['DeleteUserWarning'] = 'Изтриването на този потребител ще премахне всички негови текущи, бъдещи и минали резервации.';
        $strings['AddAnnouncement'] = 'Добавяне на обява';
        $strings['Announcement'] = 'Обява';
        $strings['Priority'] = 'Приоритет';
        $strings['Reservable'] = 'Резервируем';
        $strings['Unreservable'] = 'Нерезервируем';
        $strings['Reserved'] = 'Резервиран';
        $strings['MyReservation'] = 'Моите резервации';
        $strings['Pending'] = 'Висящ';
        $strings['Past'] = 'Минал';
        $strings['Restricted'] = 'Ограничен';
        $strings['ViewAll'] = 'Виж всички';
        $strings['MoveResourcesAndReservations'] = 'Преместване на ресурси и резервации към';
        $strings['TurnOffSubscription'] = 'Изключване Абонаменти за Календар';
        $strings['TurnOnSubscription'] = 'Разрешаване на абонамент за този календар';
        $strings['SubscribeToCalendar'] = 'Абониране за този календар';
        $strings['SubscriptionsAreDisabled'] = 'Администраторът е забранил абонаменти за календар ';
        $strings['NoResourceAdministratorLabel'] = '(Няма администратор на ресурс)';
        $strings['WhoCanManageThisResource'] = 'Който може да управлява този ресурс?';
        $strings['ResourceAdministrator'] = 'Администратор на ресурс';
        $strings['Private'] = 'Частен';
        $strings['Accept'] = 'Приемане';
        $strings['Decline'] = 'Отказване';
        $strings['ShowFullWeek'] = 'Показване на пълната седмица';
        $strings['CustomAttributes'] = 'Персонализирани атрибути';
        $strings['AddAttribute'] = 'Добавяне на атрибут';
        $strings['EditAttribute'] = 'Актуализиране на атрибут';
        $strings['DisplayLabel'] = 'Показване на етикет';
        $strings['Type'] = 'Тип';
        $strings['Required'] = 'Задължителен';
        $strings['ValidationExpression'] = 'Валидиращ израз';
        $strings['PossibleValues'] = 'Възможни стойности';
        $strings['SingleLineTextbox'] = 'Един ред каре';
        $strings['MultiLineTextbox'] = 'Многоредово каре';
        $strings['Checkbox'] = 'Квадратче за отметка';
        $strings['SelectList'] = 'Избиране на списък';
        $strings['CommaSeparated'] = 'разделени със запетая';
        $strings['Category'] = 'Категория';
        $strings['CategoryReservation'] = 'Резервация';
        $strings['CategoryGroup'] = 'Група';
        $strings['SortOrder'] = 'Sort Order';
        $strings['Title'] = 'Заглавие';
        $strings['AdditionalAttributes'] = 'Допълнителни атрибути';
        $strings['True'] = 'Истина';
        $strings['False'] = 'Лъжа';
        $strings['ForgotPasswordEmailSent'] = 'На адреса е изпратен един имейл, съдържащ инструкции за смяна на Вашата парола';
        $strings['ActivationEmailSent'] = 'Скоро Вие ще получите имейл за активиране.';
        $strings['AccountActivationError'] = 'Съжаляваме, не можем да активираме Вашия профил.';
        $strings['Attachments'] = 'Прикачени файлове';
        $strings['AttachFile'] = 'Прикачване на файл';
        $strings['Maximum'] = 'макс';
        $strings['NoScheduleAdministratorLabel'] = 'Няма администратор на разписание';
        $strings['ScheduleAdministrator'] = 'Администратор на разписание';
        $strings['Total'] = 'Общо';
        $strings['QuantityReserved'] = 'Резервирано количество';
        $strings['AllAccessories'] = 'Всички аксесоари';
        $strings['GetReport'] = 'Получаване на отчет';
        $strings['NoResultsFound'] = 'Няма намерени съвпадащи резултати';
        $strings['SaveThisReport'] = 'Запази този отчет';
        $strings['ReportSaved'] = 'Отчетът запазен!';
        $strings['EmailReport'] = 'Email отчет';
        $strings['ReportSent'] = 'отчетът изпратен!';
        $strings['RunReport'] = 'Пускане на отчет';
        $strings['NoSavedReports'] = 'Вие нямате запазени отчети.';
        $strings['CurrentWeek'] = 'Текущата седмица';
        $strings['CurrentMonth'] = 'Текущия месец';
        $strings['AllTime'] = 'Цялото време';
        $strings['FilterBy'] = 'Филтриране по';
        $strings['Select'] = 'Избиране';
        $strings['List'] = 'Списък';
        $strings['TotalTime'] = 'Общо време';
        $strings['Count'] = 'Брой';
        $strings['Usage'] = 'Използване';
        $strings['AggregateBy'] = 'Сумиране по';
        $strings['Range'] = 'Обхват';
        $strings['Choose'] = 'Избиране';
        $strings['All'] = 'Всички';
        $strings['ViewAsChart'] = 'Виж като Графика';
        $strings['ReservedResources'] = 'Запазени ресурси';
        $strings['ReservedAccessories'] = 'RЗапазени аксесоари';
        $strings['ResourceUsageTimeBooked'] = 'Използване на ресурс - Ангажирано време';
        $strings['ResourceUsageReservationCount'] = 'Използване на ресурс - Брой резервации';
        $strings['Top20UsersTimeBooked'] = 'Топ 20 Потребители - Ангажирано време';
        $strings['Top20UsersReservationCount'] = 'Топ 20 Потребители - Брой резервации';
        // End Strings

        // Errors
        $strings['LoginError'] = 'Ние не можем да съпоставим Вашето потребителско име или парола';
        $strings['ReservationFailed'] = 'Вашата резервация не може да се направи';
        $strings['MinNoticeError'] = 'Тази резервация изисква предварително известие. Най-ранната дата и час, които могат да бъдат запазени, са %s.';
        $strings['MaxNoticeError'] = 'Тази резервация не може да се направи това далеч в бъдещето. Най-късната дата и час, които могат да бъдат запазени, са %s.';
        $strings['MinDurationError'] = 'Тази резервация трябва да продължи най-малко %s.';
        $strings['MaxDurationError'] = 'Тази резервация не може да продължи по-дълго от %s.';
        $strings['ConflictingAccessoryDates'] = 'Няма достатъчно от следните аксесоари:';
        $strings['NoResourcePermission'] = 'Вие нямате права за достъп до един или повече от поисканите ресурси';
        $strings['ConflictingReservationDates'] = 'Има противоречиви резервации на следните дати:';
        $strings['StartDateBeforeEndDateRule'] = 'Началната дата и час трябва да бъде преди крайната дата и час';
        $strings['RecurringWithoutTerminationRule'] = 'Крайна дата е задължителна за повтарящи се блокирания.';
        $strings['StartIsInPast'] = 'Началната дата и час не могат да бъдат в миналото';
        $strings['EmailDisabled'] = 'Администраторът е забранил известия по имейл';
        $strings['ValidLayoutRequired'] = 'Времеви интервали трябва да бъдат осигурени за всички 24 часа на денонощието, започвайки и завършвайки в 12:00 ч.';
        $strings['CustomAttributeErrors'] = 'Има проблеми с допълнителните атрибути, които въведохте:';
        $strings['CustomAttributeRequired'] = '%s е задължително поле';
        $strings['CustomAttributeInvalid'] = 'Въведената стойност за %s е невалидна';
        $strings['AttachmentLoadingError'] = 'За съжаление, имаше проблем при зареждането на искания файл.';
        $strings['InvalidAttachmentExtension'] = 'Можете да качвате файлове от тип: %s';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Създаване на резервация';
        $strings['EditReservation'] = 'Редактиране на резервация';
        $strings['LogIn'] = 'Вход';
        $strings['ManageReservations'] = 'Резервации';
        $strings['AwaitingActivation'] = 'Очаква активиране';
        $strings['PendingApproval'] = 'Изчаква се одобрение';
        $strings['ManageSchedules'] = 'Разписания';
        $strings['ManageResources'] = 'Ресурси';
        $strings['ManageAccessories'] = 'Аксесоари';
        $strings['ManageUsers'] = 'Потребители';
        $strings['ManageGroups'] = 'Групи';
        $strings['ManageQuotas'] = 'Квоти';
        $strings['ManageBlackouts'] = 'Забранени интервали';
        $strings['MyDashboard'] = 'Моето табло';
        $strings['ServerSettings'] = 'настройки на сървъра';
        $strings['Dashboard'] = 'Табло';
        $strings['Help'] = 'Помощ';
        $strings['Administration'] = 'Администриране';
        $strings['About'] = 'За';
        $strings['Bookings'] = 'Ангацирания';
        $strings['Schedule'] = 'Разписание';
        $strings['Reservations'] = 'Резервации';
        $strings['Account'] = 'Профил';
        $strings['EditProfile'] = 'Редактиране на моя профил';
        $strings['FindAnOpening'] = 'Отговор';
        $strings['OpenInvitations'] = 'Отваряне на покани';
        $strings['MyCalendar'] = 'Моят календар';
        $strings['ResourceCalendar'] = 'Ресурсен Календар';
        $strings['Reservation'] = 'Нова резервация';
        $strings['Install'] = 'Инсталация';
        $strings['ChangePassword'] = 'Смяна на парола';
        $strings['MyAccount'] = 'Моят профил';
        $strings['Profile'] = 'Профил';
        $strings['ApplicationManagement'] = 'Управление на приложението';
        $strings['ForgotPassword'] = 'Забравена парола';
        $strings['NotificationPreferences'] = 'Предпочитания за уведомяване';
        $strings['ManageAnnouncements'] = 'Обяви';
        $strings['Responsibilities'] = 'Отговорности';
        $strings['GroupReservations'] = 'Групови резервации';
        $strings['ResourceReservations'] = 'Ресурсни резервации';
        $strings['Customization'] = 'Персонализиране';
        $strings['Attributes'] = 'Атрибути';
        $strings['AccountActivation'] = 'Активиране на профил';
        $strings['ScheduleReservations'] = 'Разписание резервации';
        $strings['Reports'] = 'Отчети';
        $strings['GenerateReport'] = 'Създаване на нов отчет';
        $strings['MySavedReports'] = 'Моите запазени отчети';
        $strings['CommonReports'] = 'Общи отчети';
        $strings['ViewDay'] = 'Преглеждане на ден';
        $strings['Group'] = 'Група';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'Н';
        $strings['DayMondaySingle'] = 'П';
        $strings['DayTuesdaySingle'] = 'В';
        $strings['DayWednesdaySingle'] = 'С';
        $strings['DayThursdaySingle'] = 'Ч';
        $strings['DayFridaySingle'] = 'П';
        $strings['DaySaturdaySingle'] = 'С';

        $strings['DaySundayAbbr'] = 'Нед';
        $strings['DayMondayAbbr'] = 'Пон';
        $strings['DayTuesdayAbbr'] = 'Вто';
        $strings['DayWednesdayAbbr'] = 'Сря';
        $strings['DayThursdayAbbr'] = 'Чет';
        $strings['DayFridayAbbr'] = 'Пет';
        $strings['DaySaturdayAbbr'] = 'Съб';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Вашата резервация е одобрена';
        $strings['ReservationCreatedSubject'] = 'Вашата резервация е създадена';
        $strings['ReservationUpdatedSubject'] = 'Вашата резервация е актуализирана';
        $strings['ReservationDeletedSubject'] = 'Вашата резервация е премахната';
        $strings['ReservationCreatedAdminSubject'] = 'Уведомление: създадена е резервация';
        $strings['ReservationUpdatedAdminSubject'] = 'Уведомление: актуализирана е резервация';
        $strings['ReservationDeleteAdminSubject'] = 'Уведомление: премахната е резервация';
        $strings['ParticipantAddedSubject'] = 'Уведомление за участие в резервация';
        $strings['ParticipantDeletedSubject'] = 'Резервацията е премахната';
        $strings['InviteeAddedSubject'] = 'Покана за резервация';
        $strings['ResetPassword'] = 'Заявка за смяна на парола';
        $strings['ActivateYourAccount'] = 'Моля активирайте профила си';
        $strings['ReportSubject'] = 'Вашият заявен отчет (%s)';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'Нямате предходни резервации';
        $strings['PastReservations'] = 'Предходни резервации';
        $strings['AllNoPastReservations'] = 'Няма предходни резервации в последните %s дни';
        $strings['AllPastReservations'] = 'Всички предходни резервации';
        $strings['Yesterday'] = 'Вчера';
        $strings['EarlierThisWeek'] = 'По-рано този седмица';
        $strings['PreviousWeek'] = 'Предходна седмица';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'Вашата група няма предстоящи резервации';
        $strings['GroupUpcomingReservations'] = 'Бъдещите резервации на моята група(и)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Възникна грешка при влизане с Facebook. Моля, опитайте отново.';
        //End Facebook Login SDK Error


        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'Нямате резервации, изчакващи одобрение';
        $strings['PendingApprovalReservations'] = 'Резервации в очакване на одобрение';
        $strings['LaterThisMonth'] = 'По-късно този месец';
        $strings['LaterThisYear'] = 'По-късно тази година';
        $strings['Remaining'] = 'Оставащи';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Няма липсващи резервации за напускане';
        $strings['MissingCheckOutReservations'] = 'Липсващи резервации за напускане';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Не можете да видите подробности за резервацията, защото нямате права за нито един от ресурсите в тази резервация';
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
        $days['full'] = ['Неделя', 'Понеделник', 'Вторник', 'Сряда', 'Четвъртък', 'Петък', 'Събота'];
        // The three letter abbreviation
        $days['abbr'] = ['Нед', 'Пон', 'Вто', 'Сря', 'Чет', 'Пет', 'Съб'];
        // The two letter abbreviation
        $days['two'] = ['Не', 'По', 'Вт', 'Ср', 'Че', 'Пе', 'Съ'];
        // The one letter abbreviation
        $days['letter'] = ['Н', 'П', 'В', 'С', 'Ч', 'П', 'С'];

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
        $months['full'] = ['Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни', 'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'];
        // The three letter month name
        $months['abbr'] = ['Яну', 'Фев', 'Мар', 'Апр', 'Май', 'Юни', 'Юли', 'Авг', 'Сеп', 'Окт', 'Ное', 'Дек'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ь', 'Ю', 'Я'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'bg';
    }
}
