<?php

/**
Translation: 2016 Neklyudov Dmitriy <neodim5@mail.ru>
 */

require_once('Language.php');
require_once('en_gb.php');

class ru_ru extends en_gb
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

        // Russian conventions: day.month.year, 24-hour time.
        // Month and short weekday names (M, F, D) are not translated by the
        // formatter, so only numeric dates and the full weekday name (l) are used.
        $dates['general_date'] = 'd.m.Y';
        $dates['general_datetime'] = 'd.m.Y H:i:s';
        $dates['short_datetime'] = 'd.m.y H:i';
        $dates['schedule_daily'] = 'l, d.m.Y';
        $dates['reservation_email'] = 'd.m.Y, H:i (e)';
        $dates['res_popup'] = 'd.m.Y H:i';
        $dates['res_popup_time'] = 'H:i';
        $dates['short_reservation_date'] = 'd.m.y H:i';
        $dates['dashboard'] = 'l, d.m.Y H:i';
        $dates['period_time'] = 'H:i';
        $dates['mobile_reservation_date'] = 'd.m H:i';
        $dates['general_date_js'] = 'dd.mm.yy';
        $dates['general_time_js'] = 'H:mm';
        $dates['momentjs_datetime'] = 'DD.MM.YY H:mm';
        $dates['calendar_time'] = 'H:mm';
        $dates['calendar_dates'] = 'd.m';
        $dates['embedded_date'] = 'd.m';
        $dates['embedded_time'] = 'H:i';
        $dates['embedded_datetime'] = 'd.m.Y H:i';
        $dates['report_date'] = '%d.%m';

        $this->Dates = $dates;

        return $this->Dates;
    }

    /**
     * @return array
     */
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Имя';
        $strings['LastName'] = 'Фамилия';
        $strings['Timezone'] = 'Часовой пояс';
        $strings['Edit'] = 'Редактировать';
        $strings['Change'] = 'Изменить';
        $strings['Rename'] = 'Переименовать';
        $strings['Remove'] = 'Удалить';
        $strings['Delete'] = 'Удалить';
        $strings['Update'] = 'Обновить';
        $strings['Cancel'] = 'Отмена';
        $strings['Add'] = 'Добавить';
        $strings['Name'] = 'Имя';
        $strings['Yes'] = 'Да';
        $strings['No'] = 'Нет';
        $strings['FirstNameRequired'] = 'Требуется имя.';
        $strings['LastNameRequired'] = 'Требуется фамилия.';
        $strings['PwMustMatch'] = 'Пароли не совпадают.';
        $strings['ValidEmailRequired'] = 'Введите корректный адрес электронной почты.';
        $strings['UniqueEmailRequired'] = 'Такой email уже зарегистрирован.';
        $strings['UniqueUsernameRequired'] = 'Такое имя пользователя уже зарегистрировано.';
        $strings['UserNameRequired'] = 'Введите имя пользователя.';
        $strings['CaptchaMustMatch'] = 'Введите код с картинки.';
        $strings['Today'] = 'Сегодня';
        $strings['Week'] = 'Неделя';
        $strings['Month'] = 'Месяц';
        $strings['BackToCalendar'] = 'Назад в календарь';
        $strings['BeginDate'] = 'Начало';
        $strings['EndDate'] = 'Конец';
        $strings['Username'] = 'Имя пользователя';
        $strings['Password'] = 'Пароль';
        $strings['PasswordConfirmation'] = 'Подтверждение пароля';
        $strings['DefaultPage'] = 'Домашняя страница по умолчанию';
        $strings['MyCalendar'] = 'Мой календарь';
        $strings['ScheduleCalendar'] = 'Расписание (календарь)';
        $strings['Registration'] = 'Регистрация';
        $strings['NoAnnouncements'] = 'Объявлений нет';
        $strings['Announcements'] = 'Объявления';
        $strings['NoUpcomingReservations'] = 'У вас нет предстоящих бронирований';
        $strings['UpcomingReservations'] = 'Предстоящие бронирования';
        $strings['AllNoUpcomingReservations'] = 'На следующие %s дней нет предстоящих бронирований';
        $strings['AllUpcomingReservations'] = 'Все предстоящие бронирования';
        $strings['ShowHide'] = 'Показать/скрыть';
        $strings['Error'] = 'Ошибка';
        $strings['ReturnToPreviousPage'] = 'Вернуться на предыдущую страницу';
        $strings['UnknownError'] = 'Неизвестная ошибка';
        $strings['DatabaseConnectionError'] = 'Не удалось подключиться к серверу базы данных.<br/>Попросите администратора сайта проверить хост, имя пользователя и пароль базы данных в <code>config/config.php</code>.';
        $strings['DatabaseNotFoundError'] = 'Не удалось выбрать указанную базу данных.<br/>Попросите администратора сайта проверить имя базы данных в <code>config/config.php</code> и убедиться, что база создана и инициализирована.';
        $strings['InsufficientPermissionsError'] = 'У вас нет разрешения на доступ к этому ресурсу';
        $strings['MissingReservationResourceError'] = 'Ресурс не выбран';
        $strings['MissingReservationScheduleError'] = 'Расписание не выбрано';
        $strings['DoesNotRepeat'] = 'Без повтора';
        $strings['Daily'] = 'Ежедневно';
        $strings['Weekly'] = 'Еженедельно';
        $strings['Monthly'] = 'Ежемесячно';
        $strings['Yearly'] = 'Ежегодно';
        $strings['RepeatPrompt'] = 'Повтор';
        $strings['hours'] = 'ч';
        $strings['days'] = 'дн.';
        $strings['weeks'] = 'нед.';
        $strings['months'] = 'мес.';
        $strings['years'] = 'г.';
        $strings['day'] = 'в день';
        $strings['week'] = 'в неделю';
        $strings['month'] = 'в месяц';
        $strings['year'] = 'в год';
        $strings['repeatDayOfMonth'] = 'День месяца';
        $strings['repeatDayOfWeek'] = 'День недели';
        $strings['RepeatUntilPrompt'] = 'До';
        $strings['RepeatEveryPrompt'] = 'Интервал';
        $strings['RepeatDaysPrompt'] = 'В';
        $strings['CreateReservationHeading'] = 'Новое бронирование';
        $strings['EditReservationHeading'] = 'Редактирование бронирования %s';
        $strings['ViewReservationHeading'] = 'Просмотр бронирования %s';
        $strings['ReservationErrors'] = 'Изменить бронирование';
        $strings['Create'] = 'Создать';
        $strings['ThisInstance'] = 'Только это повторение';
        $strings['AllInstances'] = 'Вся серия';
        $strings['FutureInstances'] = 'Это и последующие';
        $strings['Print'] = 'Печать';
        $strings['ShowHideNavigation'] = 'Показать/скрыть навигацию';
        $strings['ReferenceNumber'] = 'Номер бронирования';
        $strings['Tomorrow'] = 'Завтра';
        $strings['LaterThisWeek'] = 'Позже на этой неделе';
        $strings['NextWeek'] = 'Следующая неделя';
        $strings['SignOut'] = 'Выйти';
        $strings['JavascriptRequired'] = 'Для корректной работы этого приложения требуется JavaScript. Пожалуйста, включите JavaScript в настройках вашего браузера.';
        $strings['ScriptUrlNotConfigured'] = 'LibreBooking настроен неверно: значение <code>script.url</code> пусто, поэтому часть возможностей работать не будет. Обратитесь к администратору.';
        $strings['ScriptUrlMissingWebSuffix'] = 'LibreBooking настроен неверно: значение <code>script.url</code> должно заканчиваться на <code>/Web</code>, иначе ссылки навигации и перенаправление после входа будут работать неправильно. Обратитесь к администратору.';
        $strings['LayoutDescription'] = 'Начало: %s, показывать дней за раз: %s';
        $strings['AllResources'] = 'Все ресурсы';
        $strings['TakeOffline'] = 'Отключить';
        $strings['BringOnline'] = 'Включить';
        $strings['AddImage'] = 'Добавить изображение';
        $strings['NoImage'] = 'Изображение не добавлено';
        $strings['Move'] = 'Переместить';
        $strings['AppearsOn'] = 'Появляется в %s';
        $strings['Location'] = 'Место';
        $strings['NoLocationLabel'] = '(место не указано)';
        $strings['Contact'] = 'Как связаться';
        $strings['NoContactLabel'] = '(нет контактной информации)';
        $strings['Description'] = 'Описание';
        $strings['NoDescriptionLabel'] = '(нет описания)';
        $strings['Notes'] = 'Примечания';
        $strings['NoNotesLabel'] = '(нет примечаний)';
        $strings['NoTitleLabel'] = '(нет названия)';
        $strings['UsageConfiguration'] = 'Настройки использования';
        $strings['ChangeConfiguration'] = 'Изменить настройки';
        $strings['ResourceMinLength'] = 'Бронирование должно длиться не менее %s';
        $strings['ResourceMinLengthNone'] = 'Нет минимальной продолжительности бронирования';
        $strings['ResourceMaxLength'] = 'Бронирование не может длиться дольше %s';
        $strings['ResourceMaxLengthNone'] = 'Нет максимальной продолжительности бронирования';
        $strings['ResourceRequiresApproval'] = 'Бронирования должны быть одобрены';
        $strings['ResourceRequiresApprovalNone'] = 'Бронирования не требуют одобрения';
        $strings['ResourcePermissionAutoGranted'] = 'Разрешение предоставляется автоматически';
        $strings['ResourcePermissionNotAutoGranted'] = 'Разрешение не предоставляется автоматически';
        $strings['ResourceMinNotice'] = 'Бронирование нужно создавать не позднее чем за %s до начала';
        $strings['ResourceMinNoticeNone'] = 'Бронировать можно вплоть до текущего момента';
        $strings['ResourceMinNoticeUpdate'] = 'Бронирования нужно изменять не позднее чем за %s до начала';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Бронирования можно изменять вплоть до текущего момента';
        $strings['ResourceMinNoticeDelete'] = 'Бронирования нужно удалять не позднее чем за %s до начала';
        $strings['ResourceMinNoticeNoneDelete'] = 'Бронирования можно удалять вплоть до текущего момента';
        $strings['ResourceMaxNotice'] = 'Бронирования должны заканчиваться не позднее чем через %s от текущего момента';
        $strings['ResourceMaxNoticeNone'] = 'Бронирование может заканчиваться в любой момент в будущем';
        $strings['ResourceBufferTime'] = 'Между бронированиями должно быть %s';
        $strings['ResourceBufferTimeNone'] = 'Перерыв между бронированиями не требуется';
        $strings['ResourceAllowMultiDay'] = 'Бронирование может охватывать несколько дней';
        $strings['ResourceNotAllowMultiDay'] = 'Бронирование не может охватывать несколько дней';
        $strings['ResourceCapacity'] = 'Вместимость этого ресурса: %s чел.';
        $strings['ResourceCapacityNone'] = 'Вместимость этого ресурса не ограничена';
        $strings['AddNewResource'] = 'Добавить новый ресурс';
        $strings['AddNewUser'] = 'Добавить нового пользователя';
        $strings['AddUser'] = 'Добавить пользователя';
        $strings['Schedule'] = 'Расписание';
        $strings['AddResource'] = 'Добавить ресурс';
        $strings['Capacity'] = 'Вместимость';
        $strings['Access'] = 'Доступ';
        $strings['Duration'] = 'Продолжительность';
        $strings['Active'] = 'Активен';
        $strings['Inactive'] = 'Неактивен';
        $strings['ResetPassword'] = 'Сбросить пароль';
        $strings['LastLogin'] = 'Последний вход';
        $strings['Search'] = 'Поиск';
        $strings['ResourcePermissions'] = 'Права доступа к ресурсам';
        $strings['Reservations'] = 'Бронирования';
        $strings['Groups'] = 'Группы';
        $strings['Users'] = 'Пользователи';
        $strings['AllUsers'] = 'Все пользователи';
        $strings['AllGroups'] = 'Все группы';
        $strings['AllSchedules'] = 'Все расписания';
        $strings['UsernameOrEmail'] = 'Имя пользователя или Email';
        $strings['Members'] = 'Члены';
        $strings['QuickSlotCreation'] = 'Создать интервалы каждые %s минут между %s и %s';
        $strings['ApplyUpdatesTo'] = 'Применить обновления к';
        $strings['CancelParticipation'] = 'Отказаться от участия';
        $strings['Attending'] = 'Участвую';
        $strings['QuotaConfiguration'] = 'Расписание %s, ресурс %s, группа %s: ограничение %s %s %s';
        $strings['QuotaEnforcement'] = 'Применяется %s %s';
        $strings['reservations'] = 'бронирований';
        $strings['reservation'] = 'бронирование';
        $strings['ChangeCalendar'] = 'Сменить календарь';
        $strings['AddQuota'] = 'Добавить квоту';
        $strings['FindUser'] = 'Найти пользователя';
        $strings['Created'] = 'Создано';
        $strings['LastModified'] = 'Последнее изменение';
        $strings['GroupName'] = 'Имя группы';
        $strings['GroupMembers'] = 'Члены группы';
        $strings['GroupRoles'] = 'Роли группы';
        $strings['GroupAdmin'] = 'Администратор группы';
        $strings['Actions'] = 'Действия';
        $strings['CurrentPassword'] = 'Текущий пароль';
        $strings['NewPassword'] = 'Новый пароль';
        $strings['InvalidPassword'] = 'Текущий пароль указан неверно';
        $strings['PasswordChangedSuccessfully'] = 'Ваш пароль успешно изменён';
        $strings['SignedInAs'] = 'Вошли как';
        $strings['NotSignedIn'] = 'Вы не вошли в аккаунт';
        $strings['ReservationTitle'] = 'Название бронирования';
        $strings['ReservationDescription'] = 'Описание бронирования';
        $strings['ResourceList'] = 'Бронируемые ресурсы';
        $strings['Accessories'] = 'Оборудование';
        $strings['ParticipantList'] = 'Список участников';
        $strings['InvitationList'] = 'Приглашённые';
        $strings['AccessoryName'] = 'Наименование оборудования';
        $strings['QuantityAvailable'] = 'Кол-во в наличии';
        $strings['Resources'] = 'Ресурсы';
        $strings['Participants'] = 'Участники';
        $strings['User'] = 'Пользователь';
        $strings['Resource'] = 'Ресурс';
        $strings['Status'] = 'Статус';
        $strings['Approve'] = 'Одобрить';
        $strings['Page'] = 'Страница';
        $strings['Rows'] = 'строк';
        $strings['Unlimited'] = 'Без ограничений';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Адрес электронной почты';
        $strings['Phone'] = 'Телефон';
        $strings['Organization'] = 'Организация';
        $strings['Position'] = 'Должность';
        $strings['Language'] = 'Язык';
        $strings['Permissions'] = 'Разрешения';
        $strings['Reset'] = 'Сбросить';
        $strings['FindGroup'] = 'Найти группу';
        $strings['Manage'] = 'Управлять';
        $strings['None'] = 'Нет';
        $strings['AddToOutlook'] = 'Добавить в календарь';
        $strings['Done'] = 'Готово';
        $strings['RememberMe'] = 'Запомнить меня';
        $strings['FirstTimeUser?'] = 'Вы у нас впервые?';
        $strings['CreateAnAccount'] = 'Создать аккаунт';
        $strings['ViewSchedule'] = 'Посмотреть расписание';
        $strings['ForgotMyPassword'] = 'Забыли пароль?';
        $strings['YouWillBeEmailedANewPassword'] = 'Вам будет отправлен новый случайно сгенерированный пароль';
        $strings['Close'] = 'Закрыть';
        $strings['ExportToCSV'] = 'Экспорт в CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Ожидайте...';
        $strings['Login'] = 'Вход';
        $strings['AdditionalInformation'] = 'Дополнительная информация';
        $strings['AllFieldsAreRequired'] = 'все поля обязательны для заполнения';
        $strings['Optional'] = 'необязательно';
        $strings['YourProfileWasUpdated'] = 'Ваш профиль обновлён';
        $strings['YourSettingsWereUpdated'] = 'Ваши настройки обновлены';
        $strings['Register'] = 'Зарегистрироваться';
        $strings['SecurityCode'] = 'Код безопасности';
        $strings['ReservationCreatedPreference'] = 'Когда я создаю бронирование или оно создаётся от моего имени';
        $strings['ReservationUpdatedPreference'] = 'Когда я изменяю бронирование или оно изменяется от моего имени';
        $strings['ReservationDeletedPreference'] = 'Когда я удаляю бронирование или оно удаляется от моего имени';
        $strings['ReservationApprovalPreference'] = 'Когда моё ожидающее бронирование одобрено';
        $strings['PreferenceSendEmail'] = 'Отправлять мне письмо';
        $strings['PreferenceNoEmail'] = 'Не уведомлять меня';
        $strings['ReservationCreated'] = 'Ваше бронирование успешно создано!';
        $strings['ReservationUpdated'] = 'Ваше бронирование успешно обновлено!';
        $strings['ReservationRemoved'] = 'Ваше бронирование удалено';
        $strings['ReservationRequiresApproval'] = 'Один или несколько забронированных ресурсов требуют одобрения перед использованием. Бронирование будет в ожидании, пока его не одобрят.';
        $strings['YourReferenceNumber'] = 'Номер вашего бронирования: %s';
        $strings['UpdatingReservation'] = 'Обновление бронирования';
        $strings['ChangeUser'] = 'Сменить пользователя';
        $strings['MoreResources'] = 'Больше ресурсов';
        $strings['ReservationLength'] = 'Длительность бронирования';
        $strings['AddParticipants'] = 'Добавить участников';
        $strings['InviteOthers'] = 'Пригласить других';
        $strings['AddResources'] = 'Добавить ресурсы';
        $strings['AddAccessories'] = 'Добавить оборудование';
        $strings['Accessory'] = 'Оборудование';
        $strings['QuantityRequested'] = 'Требуемое кол-во';
        $strings['CreatingReservation'] = 'Создание бронирования';
        $strings['DeleteWarning'] = 'Это действие необратимо!';
        $strings['DeleteAccessoryWarning'] = 'Удаление этого оборудования уберёт его из всех бронирований.';
        $strings['AddAccessory'] = 'Добавить оборудование';
        $strings['AddBlackout'] = 'Добавить период недоступности';
        $strings['AllResourcesOn'] = 'Все ресурсы расписания';
        $strings['Reason'] = 'Причина';
        $strings['BlackoutShowMe'] = 'Показать конфликтующие бронирования';
        $strings['BlackoutDeleteConflicts'] = 'Удалить конфликтующие бронирования';
        $strings['Filter'] = 'Фильтр';
        $strings['Between'] = 'В промежутке';
        $strings['CreatedBy'] = 'Кем создано';
        $strings['BlackoutCreated'] = 'Период недоступности создан';
        $strings['BlackoutNotCreated'] = 'Не удалось создать период недоступности';
        $strings['BlackoutUpdated'] = 'Период недоступности обновлён';
        $strings['BlackoutNotUpdated'] = 'Не удалось обновить период недоступности';
        $strings['BlackoutConflicts'] = 'Есть конфликтующие периоды недоступности';
        $strings['ReservationConflicts'] = 'Есть конфликтующие по времени бронирования';
        $strings['UsersInGroup'] = 'чел. в группе';
        $strings['Browse'] = 'Выбрать из списка';
        $strings['DeleteGroupWarning'] = 'При удалении этой группы будут удалены все связанные с ней права доступа к ресурсам. Пользователи этой группы могут потерять доступ к ресурсам.';
        $strings['WhatRolesApplyToThisGroup'] = 'Какие роли относятся к этой группе?';
        $strings['WhoCanManageThisGroup'] = 'Кто может управлять этой группой?';
        $strings['WhoCanManageThisSchedule'] = 'Кто может управлять этим расписанием?';
        $strings['AddGroup'] = 'Добавить группу';
        $strings['AllQuotas'] = 'Все квоты';
        $strings['QuotaReminder'] = 'Помните: квоты применяются по часовому поясу расписания.';
        $strings['AllReservations'] = 'Все бронирования';
        $strings['PendingReservations'] = 'Ожидающие бронирования';
        $strings['Approving'] = 'Одобрение';
        $strings['MoveToSchedule'] = 'Переместить в расписание';
        $strings['DeleteResourceWarning'] = 'При удалении этого ресурса будут удалены все связанные с ним данные, включая';
        $strings['DeleteResourceWarningReservations'] = 'все прошлые, нынешние и будущие бронирования, связанные с ним';
        $strings['DeleteResourceWarningPermissions'] = 'все назначенные разрешения';
        $strings['DeleteResourceWarningReassign'] = 'Перед продолжением переназначьте всё, что не должно быть удалено';
        $strings['ScheduleLayout'] = 'Разметка (часовой пояс: %s)';
        $strings['ReservableTimeSlots'] = 'Доступные для бронирования интервалы';
        $strings['BlockedTimeSlots'] = 'Заблокированные интервалы';
        $strings['ThisIsTheDefaultSchedule'] = 'Это расписание по умолчанию';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Расписание по умолчанию нельзя удалить';
        $strings['MakeDefault'] = 'Сделать по умолчанию';
        $strings['BringDown'] = 'Опустить';
        $strings['ChangeLayout'] = 'Изменить разметку';
        $strings['AddSchedule'] = 'Добавить расписание';
        $strings['StartsOn'] = 'Начинается';
        $strings['NumberOfDaysVisible'] = 'Число видимых дней';
        $strings['UseSameLayoutAs'] = 'Использовать ту же разметку, что и';
        $strings['Format'] = 'Формат';
        $strings['OptionalLabel'] = 'Необязательная подпись';
        $strings['LayoutInstructions'] = 'Вводите по одному интервалу в строке. Интервалы должны покрывать все 24 часа суток, начиная и заканчивая в 00:00.';
        $strings['UserPermissionInfo'] = 'Фактический доступ к ресурсу может отличаться в зависимости от роли пользователя, прав доступа группы и внешних настроек разрешений';
        $strings['DeleteUserWarning'] = 'Удаление этого пользователя удалит все его текущие, будущие и прошедшие бронирования.';
        $strings['AddAnnouncement'] = 'Добавить объявление';
        $strings['Announcement'] = 'Объявление';
        $strings['Priority'] = 'Приоритет';
        $strings['Reservable'] = 'Свободно';
        $strings['Unreservable'] = 'Заблокировано';
        $strings['Reserved'] = 'Забронировано';
        $strings['MyReservation'] = 'Моё бронирование';
        $strings['Pending'] = 'В ожидании';
        $strings['Past'] = 'Прошло';
        $strings['Restricted'] = 'Ограничено';
        $strings['ViewAll'] = 'Посмотреть все';
        $strings['MoveResourcesAndReservations'] = 'Переместить ресурсы и бронирования в';
        $strings['TurnOffSubscription'] = 'Скрыть от всех';
        $strings['TurnOnSubscription'] = 'Показывать всем (RSS, iCalendar, планшет, монитор)';
        $strings['SubscribeToCalendar'] = 'Подписаться на этот календарь';
        $strings['UrlCopiedToClipboard'] = 'Ссылка скопирована в буфер обмена';
        $strings['SubscriptionsAreDisabled'] = 'Администратор отключил подписку на календари';
        $strings['NoResourceAdministratorLabel'] = '(Нет администратора ресурса)';
        $strings['WhoCanManageThisResource'] = 'Кто может управлять этим ресурсом?';
        $strings['ResourceAdministrator'] = 'Администратор ресурса';
        $strings['Private'] = 'Приватный';
        $strings['Accept'] = 'Принять';
        $strings['Decline'] = 'Отклонить';
        $strings['ShowFullWeek'] = 'Показать полную неделю';
        $strings['CustomAttributes'] = 'Пользовательские атрибуты';
        $strings['AddAttribute'] = 'Добавить атрибут';
        $strings['EditAttribute'] = 'Изменить атрибут';
        $strings['DisplayLabel'] = 'Отображаемая подпись';
        $strings['Type'] = 'Тип';
        $strings['Required'] = 'Обязательный';
        $strings['ValidationExpression'] = 'Выражение для проверки';
        $strings['PossibleValues'] = 'Возможные значения';
        $strings['SingleLineTextbox'] = 'Однострочное текстовое поле';
        $strings['MultiLineTextbox'] = 'Многострочное текстовое поле';
        $strings['Checkbox'] = 'Флажок';
        $strings['SelectList'] = 'Выпадающий список';
        $strings['CommaSeparated'] = 'через запятую';
        $strings['Category'] = 'Категория';
        $strings['CategoryReservation'] = 'Бронирование';
        $strings['CategoryGroup'] = 'Группа';
        $strings['SortOrder'] = 'Порядок сортировки';
        $strings['Title'] = 'Название';
        $strings['AdditionalAttributes'] = 'Дополнительные атрибуты';
        $strings['True'] = 'Да';
        $strings['False'] = 'Нет';
        $strings['ForgotPasswordEmailSent'] = 'На указанный адрес отправлено письмо с инструкциями по восстановлению пароля';
        $strings['ActivationEmailSent'] = 'Скоро вы получите письмо для активации аккаунта.';
        $strings['AccountActivationError'] = 'К сожалению, не удалось активировать ваш аккаунт.';
        $strings['Attachments'] = 'Вложения';
        $strings['AttachFile'] = 'Прикрепить файл';
        $strings['Maximum'] = 'максимум';
        $strings['NoScheduleAdministratorLabel'] = 'Нет администратора расписания';
        $strings['ScheduleAdministrator'] = 'Администратор расписания';
        $strings['Total'] = 'Всего';
        $strings['QuantityReserved'] = 'Забронированное количество';
        $strings['AllAccessories'] = 'Всё оборудование';
        $strings['GetReport'] = 'Получить отчёт';
        $strings['NoResultsFound'] = 'Не найдено результатов';
        $strings['SaveThisReport'] = 'Сохранить этот отчёт';
        $strings['ReportSaved'] = 'Отчёт сохранён!';
        $strings['EmailReport'] = 'Отправить отчёт на email';
        $strings['ReportSent'] = 'Отчёт отправлен!';
        $strings['RunReport'] = 'Запустить отчёт';
        $strings['NoSavedReports'] = 'У вас нет сохранённых отчётов.';
        $strings['CurrentWeek'] = 'Текущая неделя';
        $strings['CurrentMonth'] = 'Текущий месяц';
        $strings['AllTime'] = 'Всё время';
        $strings['FilterBy'] = 'Фильтровать по';
        $strings['Select'] = 'Выбор';
        $strings['List'] = 'Список';
        $strings['TotalTime'] = 'Общее время';
        $strings['Count'] = 'Количество';
        $strings['Usage'] = 'Использование';
        $strings['AggregateBy'] = 'Группировать по';
        $strings['Range'] = 'Диапазон';
        $strings['Choose'] = 'Выберите';
        $strings['All'] = 'Все';
        $strings['ViewAsChart'] = 'Показать диаграммой';
        $strings['ReservedResources'] = 'Забронированные ресурсы';
        $strings['ReservedAccessories'] = 'Забронированное оборудование';
        $strings['ResourceUsageTimeBooked'] = 'Использование ресурсов — время бронирования';
        $strings['ResourceUsageReservationCount'] = 'Использование ресурсов — количество бронирований';
        $strings['Top20UsersTimeBooked'] = 'Топ-20 пользователей — по времени бронирования';
        $strings['Top20UsersReservationCount'] = 'Топ-20 пользователей — по количеству бронирований';
        $strings['ConfigurationUpdated'] = 'Файл конфигурации обновлён';
        $strings['ConfigurationUiNotEnabled'] = 'Эта страница недоступна, потому что значение $conf[\'settings\'][\'pages\'][\'enable.configuration\'] равно false или отсутствует.';
        $strings['ConfigurationFileNotWritable'] = 'Файл конфигурации недоступен для записи. Проверьте права доступа к нему и повторите попытку.';
        $strings['ConfigurationEnvWarning'] = 'Часть значений конфигурации переопределена переменными окружения или файлом <code>.env</code>. Изменения могут не применяться, пока вы не удалите соответствующие переменные окружения.';
        $strings['ConfigurationUpdateHelp'] = 'Описание этих настроек см. в разделе «Конфигурация» <a target=_blank href=%s class=link-primary>справки</a>.';
        $strings['GeneralConfigSettings'] = 'настройки';
        $strings['UseSameLayoutForAllDays'] = 'Использовать одну разметку для всех дней';
        $strings['LayoutVariesByDay'] = 'Разметка зависит от дня';
        $strings['ManageReminders'] = 'Напоминания';
        $strings['ReminderUser'] = 'ID пользователя';
        $strings['ReminderMessage'] = 'Сообщение';
        $strings['ReminderAddress'] = 'Адреса';
        $strings['ReminderSendtime'] = 'Время отправки';
        $strings['ReminderRefNumber'] = 'Номер бронирования';
        $strings['ReminderSendtimeDate'] = 'Дата напоминания';
        $strings['ReminderSendtimeTime'] = 'Время напоминания (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Добавить напоминание';
        $strings['DeleteReminderWarning'] = 'Вы уверены, что хотите это удалить?';
        $strings['NoReminders'] = 'У вас нет предстоящих напоминаний.';
        $strings['Reminders'] = 'Напоминания';
        $strings['SendReminder'] = 'Отправить напоминание';
        $strings['minutes'] = 'мин';
        $strings['ReminderBeforeStart'] = 'до начала';
        $strings['ReminderBeforeEnd'] = 'до конца';
        $strings['Logo'] = 'Логотип';
        $strings['CssFile'] = 'Файл CSS';
        $strings['ThemeUploadSuccess'] = 'Ваши изменения были сохранены. Обновите страницу, чтобы изменения вступили в силу.';
        $strings['MakeDefaultSchedule'] = 'Сделать моим расписанием по умолчанию';
        $strings['DefaultScheduleSet'] = 'Теперь это ваше расписание по умолчанию';
        $strings['FlipSchedule'] = 'Отразить разметку расписания';
        $strings['Next'] = 'Далее';
        $strings['Success'] = 'Успешно';
        $strings['Participant'] = 'Участник';
        $strings['ResourceFilter'] = 'Фильтр ресурсов';
        $strings['ResourceGroups'] = 'Группы ресурсов';
        $strings['AddNewGroup'] = 'Добавить новую группу';
        $strings['Quit'] = 'Выйти';
        $strings['StandardScheduleDisplay'] = 'Использовать стандартный вид расписания';
        $strings['TallScheduleDisplay'] = 'Использовать высокий вид расписания';
        $strings['WideScheduleDisplay'] = 'Использовать широкий вид расписания';
        $strings['CondensedWeekScheduleDisplay'] = 'Использовать сжатый недельный вид расписания';
        $strings['ResourceGroupHelp1'] = 'Перетаскивайте группы ресурсов, чтобы изменить их порядок.';
        $strings['ResourceGroupHelp2'] = 'Щёлкните правой кнопкой мыши по названию группы ресурсов, чтобы увидеть другие действия.';
        $strings['ResourceGroupHelp3'] = 'Перетащите ресурсы, чтобы добавить их в группы.';
        $strings['ResourceGroupWarning'] = 'При использовании групп ресурсов каждый ресурс должен входить хотя бы в одну группу. Ресурсы без группы нельзя будет забронировать.';
        $strings['ResourceType'] = 'Тип ресурса';
        $strings['AppliesTo'] = 'Относится к';
        $strings['UniquePerInstance'] = 'Уникальный для каждого экземпляра';
        $strings['AddResourceType'] = 'Добавить тип ресурса';
        $strings['NoResourceTypeLabel'] = '(тип ресурса не задан)';
        $strings['ClearFilter'] = 'Сбросить фильтр';
        $strings['MinimumCapacity'] = 'Минимальная вместимость';
        $strings['Color'] = 'Цвет';
        $strings['Available'] = 'Доступен';
        $strings['Unavailable'] = 'Недоступен';
        $strings['Hidden'] = 'Скрыт';
        $strings['ResourceStatus'] = 'Статус ресурса';
        $strings['CurrentStatus'] = 'Текущее состояние';
        $strings['AllReservationResources'] = 'Все ресурсы бронирования';
        $strings['File'] = 'Файл';
        $strings['BulkResourceUpdate'] = 'Массовое обновление ресурсов';
        $strings['Unchanged'] = 'Без изменений';
        $strings['Common'] = 'Общие';
        $strings['AdminOnly'] = 'Только для администраторов';
        $strings['AdvancedFilter'] = 'Расширенный фильтр';
        $strings['MinimumQuantity'] = 'Минимальное количество';
        $strings['MaximumQuantity'] = 'Максимальное количество';
        $strings['ChangeLanguage'] = 'Изменить язык';
        $strings['AddRule'] = 'Добавить правило';
        $strings['Attribute'] = 'Атрибут';
        $strings['RequiredValue'] = 'Требуемое значение';
        $strings['ReservationCustomRuleAdd'] = 'Использовать этот цвет, если атрибут бронирования имеет следующее значение';
        $strings['AddReservationColorRule'] = 'Добавить правило цвета бронирования';
        $strings['LimitAttributeScope'] = 'Запрашивать только в определённых случаях';
        $strings['CollectFor'] = 'Запрашивать для';
        $strings['AdminOnlyHelp'] = 'Только администраторы могут просматривать и редактировать это значение атрибута';
        $strings['PrivateHelp'] = 'Это значение атрибута видят только владелец бронирования и администраторы';
        $strings['LimitAttributeScopeHelp'] = 'Запрашивать этот атрибут только для выбранных пользователей, ресурсов или типов ресурсов';
        $strings['SignIn'] = 'Войти в систему';
        $strings['SignInWith'] = 'Войти через';
        $strings['AllParticipants'] = 'Все участники';
        $strings['RegisterANewAccount'] = 'Регистрация новой учётной записи';
        $strings['Dates'] = 'Даты';
        $strings['More'] = 'Ещё';
        $strings['ResourceAvailability'] = 'Доступность ресурсов';
        $strings['UnavailableAllDay'] = 'Недоступен весь день';
        $strings['AvailableUntil'] = 'Доступно до';
        $strings['AvailableBeginningAt'] = 'Доступно начиная с';
        $strings['AvailableAt'] = 'Доступно с';
        $strings['AllResourceTypes'] = 'Все типы ресурсов';
        $strings['AllResourceStatuses'] = 'Все статусы ресурсов';
        $strings['AllowParticipantsToJoin'] = 'Разрешить участникам присоединяться';
        $strings['Join'] = 'Присоединиться';
        $strings['YouAreAParticipant'] = 'Вы участник этого бронирования';
        $strings['YouAreInvited'] = 'Вы приглашены в это бронирование';
        $strings['YouCanJoinThisReservation'] = 'Вы можете присоединиться к этому бронированию';

        $strings['Import'] = 'Импорт';
        $strings['GetTemplate'] = 'Получить шаблон';
        $strings['UserImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Имя пользователя и адрес электронной почты обязательны.</li><li>Корректность атрибутов не проверяется.</li><li>Если оставить остальные поля пустыми, будут заданы значения по умолчанию, а паролем пользователя станет \'password\'.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';

        $strings['RowsImported'] = 'Импортировано строк';
        $strings['RowsSkipped'] = 'Пропущено строк';
        $strings['Columns'] = 'Столбцы';
        $strings['Reserve'] = 'Забронировать';
        $strings['AllDay'] = 'Весь день';
        $strings['Everyday'] = 'Каждый день';
        $strings['IncludingCompletedReservations'] = 'Включая завершённые бронирования';
        $strings['NotCountingCompletedReservations'] = 'Не считая завершённых бронирований';
        $strings['RetrySkipConflicts'] = 'Пропустить конфликтующие бронирования';
        $strings['Retry'] = 'Повторить';
        $strings['RemoveExistingPermissions'] = 'Удалить существующие разрешения?';
        $strings['Continue'] = 'Продолжить';
        $strings['WeNeedYourEmailAddress'] = 'Нам нужен ваш адрес электронной почты для бронирования';
        $strings['ResourceColor'] = 'Цвет ресурса';
        $strings['DateTime'] = 'Дата и время';
        $strings['AutoReleaseNotification'] = 'Автоматически освобождается, если отметка о приходе не сделана в течение %s мин.';
        $strings['RequiresCheckInNotification'] = 'Требуется отметка о приходе и уходе';
        $strings['NoCheckInRequiredNotification'] = 'Отметка о приходе и уходе не требуется';
        $strings['RequiresApproval'] = 'Требуется одобрение';
        $strings['CheckingIn'] = 'Отметка о приходе';
        $strings['CheckingOut'] = 'Отметка об уходе';
        $strings['CheckIn'] = 'Отметить приход';
        $strings['CheckOut'] = 'Отметить уход';
        $strings['ReleasedIn'] = 'Освободится через';
        $strings['CheckedInSuccess'] = 'Приход отмечен';
        $strings['CheckedOutSuccess'] = 'Уход отмечен';
        $strings['CheckInFailed'] = 'Не удалось отметить приход';
        $strings['CheckOutFailed'] = 'Не удалось отметить уход';
        $strings['CheckInTime'] = 'Время прихода';
        $strings['CheckOutTime'] = 'Время ухода';
        $strings['OriginalEndDate'] = 'Исходное окончание';
        $strings['SpecificDates'] = 'Показать конкретные даты';
        $strings['Guest'] = 'Гость';
        $strings['ResourceDisplayPrompt'] = 'Ресурс для отображения';
        $strings['Credits'] = 'Кредиты';
        $strings['AvailableCredits'] = 'Доступные кредиты';
        $strings['CreditUsagePerSlot'] = 'Кредитов за интервал (вне пика): %s';
        $strings['PeakCreditUsagePerSlot'] = 'Кредитов за интервал (в пик): %s';
        $strings['CreditsRule'] = 'У вас недостаточно кредитов. Требуется: %s. На счёте: %s';
        $strings['PeakTimes'] = 'Часы пик';
        $strings['AllYear'] = 'Весь год';
        $strings['MoreOptions'] = 'Дополнительные параметры';
        $strings['SendAsEmail'] = 'Отправить на Email';
        $strings['UsersInGroups'] = 'Пользователи в группах';
        $strings['UsersWithAccessToResources'] = 'Пользователи с доступом к ресурсам';
        $strings['AnnouncementSubject'] = 'Новое объявление от %s';
        $strings['AnnouncementEmailNotice'] = 'пользователям будет отправлено это объявление по электронной почте';
        $strings['Day'] = 'День';
        $strings['NotifyWhenAvailable'] = 'Уведомить, когда освободится';
        $strings['AddingToWaitlist'] = 'Добавление вас в список ожидания';
        $strings['WaitlistRequestAdded'] = 'Вы получите уведомление, если это время станет доступным';
        $strings['PrintQRCode'] = 'Печать QR-кода';
        $strings['FindATime'] = 'Найти время';
        $strings['AnyResource'] = 'Любой ресурс';
        $strings['ThisWeek'] = 'Эта неделя';
        $strings['Hours'] = 'Часы';
        $strings['Minutes'] = 'Минуты';
        $strings['ImportICS'] = 'Импорт файла ICS';
        $strings['ImportQuartzy'] = 'Импорт файла Quartzy';
        $strings['IncludeDeleted'] = 'Включая удалённые бронирования';
        $strings['Deleted'] = 'Удалено';
        $strings['OnlyIcs'] = 'Можно загружать только файлы *.ics.';
        $strings['IcsLocationsAsResources'] = 'Места будут импортированы как ресурсы.';
        $strings['IcsMissingOrganizer'] = 'Для событий без организатора владельцем будет назначен текущий пользователь.';
        $strings['IcsWarning'] = 'Правила бронирования применяться не будут — возможны конфликты, дубликаты и т. д.';
        $strings['BlackoutAroundConflicts'] = 'Создать период недоступности в обход конфликтующих бронирований';
        $strings['DuplicateReservation'] = 'Дублировать';
        $strings['UnavailableNow'] = 'Недоступно сейчас';
        $strings['ReserveLater'] = 'Забронировать позже';
        $strings['CollectedFor'] = 'Запрашивается для';
        $strings['Back'] = 'Назад';
        $strings['Forward'] = 'Вперёд';
        $strings['DateRange'] = 'Диапазон дат';
        $strings['Copy'] = 'Копировать';
        $strings['Detect'] = 'Определить';
        $strings['Autofill'] = 'Автозаполнение';
        $strings['NameOrEmail'] = 'имя или email';
        $strings['ImportResources'] = 'Импорт ресурсов';
        $strings['ExportResources'] = 'Экспорт ресурсов';
        $strings['ResourceImportInstructions'] = '<ul><li>Файл должен быть в формате CSV с кодировкой UTF-8.</li><li>Название обязательно. Если оставить остальные поля пустыми, будут заданы значения по умолчанию.</li><li>Возможные значения статуса: \'Available\' (доступен), \'Unavailable\' (недоступен) и \'Hidden\' (скрыт).</li><li>Цвет указывается шестнадцатеричным значением, например #ffffff.</li><li>Столбцы автоматического назначения и одобрения могут иметь значения true или false.</li><li>Корректность атрибутов не проверяется.</li><li>Несколько групп ресурсов перечисляются через запятую.</li><li>Длительность можно указать в формате #d#h#m или HH:mm (1d3h30m или 27:30 — это 1 день, 3 часа и 30 минут).</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Файл должен быть в формате CSV с кодировкой UTF-8.</li><li>Адрес электронной почты, названия ресурсов, начало и конец — обязательные поля.</li><li>Для начала и конца нужны полные дата и время. Рекомендуемый формат: YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Правила, конфликты и допустимость интервалов не проверяются.</li><li>Уведомления отправляться не будут.</li><li>Корректность атрибутов не проверяется.</li><li>Несколько названий ресурсов перечисляются через запятую.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Минуты до автоосвобождения';
        $strings['CreditsPeak'] = 'Кредиты (в пик)';
        $strings['CreditsOffPeak'] = 'Кредиты (вне пика)';
        $strings['ResourceMinLengthCsv'] = 'Минимальная длительность бронирования';
        $strings['ResourceMaxLengthCsv'] = 'Максимальная длительность бронирования';
        $strings['ResourceBufferTimeCsv'] = 'Буферное время';
        $strings['ResourceMinNoticeAddCsv'] = 'Минимальный срок для создания бронирования';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Минимальный срок для изменения бронирования';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Минимальный срок для удаления бронирования';
        $strings['ResourceMaxNoticeCsv'] = 'Максимальный срок окончания бронирования';
        $strings['Export'] = 'Экспорт';
        $strings['DeleteMultipleUserWarning'] = 'При удалении этих пользователей будут удалены все их текущие, будущие и прошедшие бронирования. Письма отправляться не будут.';
        $strings['DeleteMultipleReservationsWarning'] = 'Письма отправляться не будут.';
        $strings['ErrorMovingReservation'] = 'Ошибка при перемещении бронирования';
        $strings['SelectUser'] = 'Выбор пользователя';
        $strings['InviteUsers'] = 'Пригласить пользователей';
        $strings['InviteUsersLabel'] = 'Введите адреса электронной почты приглашённых';
        $strings['ApplyToCurrentUsers'] = 'Применить к текущим пользователям';
        $strings['ReasonText'] = 'Текст причины';
        $strings['NoAvailableMatchingTimes'] = 'Нет свободного времени, подходящего под ваш запрос';
        $strings['Schedules'] = 'Расписания';
        $strings['NotifyUser'] = 'Уведомить пользователя';
        $strings['UpdateUsersOnImport'] = 'Обновлять существующего пользователя, если адрес почты уже есть';
        $strings['UpdateResourcesOnImport'] = 'Обновлять существующие ресурсы, если название уже есть';
        $strings['Reject'] = 'Отклонить';
        $strings['CheckingAvailability'] = 'Проверка доступности';
        $strings['CreditPurchaseNotEnabled'] = 'Покупка кредитов не включена';
        $strings['CreditsEachCost1'] = 'Цена за';
        $strings['CreditsEachCost2'] = 'кредит(ов):';
        $strings['CreditsCount'] = 'Количество кредитов';
        $strings['CreditsCost'] = 'Стоимость';
        $strings['Currency'] = 'Валюта';
        $strings['PayPalClientId'] = 'Client ID';
        $strings['PayPalSecret'] = 'Secret';
        $strings['PayPalEnvironment'] = 'Окружение';
        $strings['Sandbox'] = 'Песочница';
        $strings['Live'] = 'Рабочий режим';
        $strings['StripePublishableKey'] = 'Публикуемый ключ';
        $strings['StripeSecretKey'] = 'Секретный ключ';
        $strings['CreditsUpdated'] = 'Стоимость кредитов обновлена';
        $strings['CreditsNotUpdated'] = 'Стоимость кредитов не обновлена. Проверьте значения и повторите попытку.';
        $strings['GatewaysUpdated'] = 'Платёжные шлюзы обновлены';
        $strings['GatewaysNotUpdated'] = 'Платёжные шлюзы не обновлены. Проверьте значения и повторите попытку.';
        $strings['PurchaseSummary'] = 'Сводка по покупке';
        $strings['EachCreditCosts'] = 'Каждый кредит стоит';
        $strings['Checkout'] = 'Оформление заказа';
        $strings['Quantity'] = 'Количество';
        $strings['CreditPurchase'] = 'Покупка кредитов';
        $strings['EmptyCart'] = 'Ваша корзина пуста.';
        $strings['BuyCredits'] = 'Купить кредиты';
        $strings['CreditsPurchased'] = 'кредит(ов) куплено.';
        $strings['ViewYourCredits'] = 'Посмотреть ваши кредиты';
        $strings['TryAgain'] = 'Попробовать снова';
        $strings['PurchaseFailed'] = 'Не удалось обработать платёж.';
        $strings['NoteCreditsPurchased'] = 'Кредиты куплены';
        $strings['CreditsUpdatedLog'] = 'Кредиты обновлены пользователем %s';
        $strings['ReservationCreatedLog'] = 'Бронирование создано. Номер: %s';
        $strings['ReservationUpdatedLog'] = 'Бронирование обновлено. Номер: %s';
        $strings['ReservationDeletedLog'] = 'Бронирование удалено. Номер: %s';
        $strings['BuyMoreCredits'] = 'Купить ещё кредитов';
        $strings['Transactions'] = 'Транзакции';
        $strings['Cost'] = 'Стоимость';
        $strings['PaymentGateways'] = 'Платёжные шлюзы';
        $strings['CreditHistory'] = 'История кредитов';
        $strings['TransactionHistory'] = 'История транзакций';
        $strings['Date'] = 'Дата';
        $strings['Note'] = 'Примечание';
        $strings['CreditsBefore'] = 'Кредитов до';
        $strings['CreditsAfter'] = 'Кредитов после';
        $strings['TransactionFee'] = 'Комиссия';
        $strings['InvoiceNumber'] = 'Номер счёта';
        $strings['TransactionId'] = 'Идентификатор транзакции';
        $strings['Gateway'] = 'Платёжный шлюз';
        $strings['GatewayTransactionDate'] = 'Дата транзакции шлюза';
        $strings['Refund'] = 'Возврат';
        $strings['IssueRefund'] = 'Оформить возврат';
        $strings['RefundIssued'] = 'Возврат оформлен';
        $strings['RefundAmount'] = 'Сумма возврата';
        $strings['AmountRefunded'] = 'Возвращено';
        $strings['FullyRefunded'] = 'Возвращено полностью';
        $strings['YourCredits'] = 'Ваши кредиты';
        $strings['PayWithCard'] = 'Оплатить картой';
        $strings['or'] = 'или';
        $strings['CreditsRequired'] = 'Требуется кредитов';
        $strings['AddToGoogleCalendar'] = 'Добавить в Google';
        $strings['Image'] = 'Изображение';
        $strings['ChooseOrDropFile'] = 'Выберите файл или перетащите его сюда';
        $strings['SlackBookResource'] = 'Забронировать %s';
        $strings['SlackBookNow'] = 'Забронировать';
        $strings['SlackNotFound'] = 'Ресурс с таким названием не найден. Нажмите «Забронировать», чтобы создать новое бронирование.';
        $strings['AutomaticallyAddToGroup'] = 'Автоматически добавлять новых пользователей в эту группу';
        $strings['GroupAutomaticallyAdd'] = 'Добавлять автоматически';
        $strings['TermsOfService'] = 'Условия использования';
        $strings['EnterTermsManually'] = 'Ввести условия вручную';
        $strings['LinkToTerms'] = 'Ссылка на условия';
        $strings['UploadTerms'] = 'Загрузить условия';
        $strings['RequireTermsOfServiceAcknowledgement'] = 'Требовать согласия с условиями использования';
        $strings['UponReservation'] = 'При бронировании';
        $strings['UponRegistration'] = 'При регистрации';
        $strings['ViewTerms'] = 'Посмотреть условия использования';
        $strings['IAccept'] = 'Я принимаю';
        $strings['TheTermsOfService'] = 'условия использования';
        $strings['DisplayPage'] = 'Страница отображения';
        $strings['AvailableAllYear'] = 'Весь год';
        $strings['Availability'] = 'Доступность';
        $strings['AvailableBetween'] = 'Доступно в период';
        $strings['ConcurrentYes'] = 'Ресурсы могут бронировать несколько человек одновременно';
        $strings['ConcurrentNo'] = 'Ресурсы не могут бронировать несколько человек одновременно';
        $strings['ScheduleAvailabilityEarly'] = ' Это расписание пока недоступно. Оно будет доступно в период';
        $strings['ScheduleAvailabilityLate'] = 'Это расписание больше недоступно. Оно было доступно в период';
        $strings['ResourceImages'] = 'Изображения ресурса';
        $strings['FullAccess'] = 'Полный доступ';
        $strings['ViewOnly'] = 'Только просмотр';
        $strings['Purge'] = 'Очистить';
        $strings['UsersWillBeDeleted'] = 'пользователей будет удалено';
        $strings['BlackoutsWillBeDeleted'] = 'периодов недоступности будет удалено';
        $strings['ReservationsWillBePurged'] = 'бронирований будет удалено безвозвратно';
        $strings['ReservationsWillBeDeleted'] = 'бронирований будет удалено';
        $strings['PermanentlyDeleteUsers'] = 'Безвозвратно удалить пользователей, не входивших с';
        $strings['DeleteBlackoutsBefore'] = 'Удалить периоды недоступности до';
        $strings['DeletedReservations'] = 'Удалённые бронирования';
        $strings['DeleteReservationsBefore'] = 'Удалить бронирования до';
        $strings['PermanentlyPurgeAllDeletedReservations'] = 'Безвозвратно очистить все удалённые бронирования';
        $strings['SwitchToACustomLayout'] = 'Перейти на произвольную разметку';
        $strings['SwitchToAStandardLayout'] = 'Перейти на стандартную разметку';
        $strings['ThisScheduleUsesACustomLayout'] = 'Это расписание использует произвольную разметку';
        $strings['ThisScheduleUsesAStandardLayout'] = 'Это расписание использует стандартную разметку';
        $strings['SwitchLayoutWarning'] = 'Вы уверены, что хотите сменить тип разметки? Это удалит все существующие интервалы.';
        $strings['DeleteThisTimeSlot'] = 'Удалить этот интервал?';
        $strings['Refresh'] = 'Обновить';
        $strings['ViewReservation'] = 'Посмотреть бронирование';
        $strings['PublicId'] = 'Публичный идентификатор';
        $strings['Public'] = 'Публичный';
        $strings['AtomFeedTitle'] = 'Бронирования — %s';
        $strings['DefaultStyle'] = 'Стиль по умолчанию';
        $strings['Standard'] = 'Стандартный';
        $strings['Wide'] = 'Широкий';
        $strings['Tall'] = 'Высокий';
        $strings['EmailTemplate'] = 'Шаблон письма';
        $strings['SelectEmailTemplate'] = 'Выберите шаблон письма';
        $strings['ReloadOriginalContents'] = 'Загрузить исходное содержимое заново';
        $strings['UpdateEmailTemplateSuccess'] = 'Шаблон письма обновлён';
        $strings['UpdateEmailTemplateFailure'] = 'Не удалось обновить шаблон письма. Проверьте, что каталог доступен для записи.';
        $strings['BulkResourceDelete'] = 'Массовое удаление ресурсов';
        $strings['NewVersion'] = 'Новая версия!';
        $strings['WhatsNew'] = 'Что нового?';
        $strings['OnlyViewedCalendar'] = 'Это расписание можно смотреть только в виде календаря';
        $strings['Grid'] = 'Сетка';
        $strings['NoReservationsFound'] = 'Бронирования не найдены';
        $strings['EmailReservation'] = 'Отправить бронирование по почте';
        $strings['AdHocMeeting'] = 'Внеплановая встреча';
        $strings['NextReservation'] = 'Следующее бронирование';
        $strings['CurrentReservation'] = 'Текущее бронирование';
        $strings['MissedCheckin'] = 'Отметка о приходе пропущена';
        $strings['MissedCheckout'] = 'Отметка об уходе пропущена';
        $strings['Utilization'] = 'Загруженность';
        $strings['SpecificTime'] = 'Определённое время';
        $strings['ReservationSeriesEndingPreference'] = 'Когда моя серия повторяющихся бронирований заканчивается';
        $strings['NotAttending'] = 'Не участвую';
        $strings['ViewAvailability'] = 'Посмотреть доступность';
        $strings['ReservationDetails'] = 'Сведения о бронировании';
        $strings['StartTime'] = 'Время начала';
        $strings['EndTime'] = 'Время окончания';
        $strings['New'] = 'Новое';
        $strings['Updated'] = 'Обновлено';
        $strings['Custom'] = 'Выбранные даты';
        $strings['AddDate'] = 'Добавить дату';
        $strings['RepeatOn'] = 'Повторять в';
        $strings['ScheduleConcurrentMaximum'] = 'Максимум одновременно забронированных ресурсов: <b>%s</b>';
        $strings['ScheduleConcurrentMaximumNone'] = 'Число одновременно забронированных ресурсов не ограничено';
        $strings['ScheduleMaximumConcurrent'] = 'Максимальное число одновременно забронированных ресурсов';
        $strings['ScheduleMaximumConcurrentNote'] = 'Если задано, общее число ресурсов, которые можно забронировать одновременно в этом расписании, будет ограничено.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Максимум ресурсов в одном бронировании: <b>%s</b>';
        $strings['ScheduleResourcesPerReservationNone'] = 'Число ресурсов в одном бронировании не ограничено';
        $strings['ScheduleResourcesPerReservation'] = 'Максимальное число ресурсов в одном бронировании';
        $strings['ResourceConcurrentReservations'] = 'Одновременных бронирований: до %s';
        $strings['ResourceConcurrentReservationsNone'] = 'Не разрешать одновременные бронирования';
        $strings['AllowConcurrentReservations'] = 'Разрешить одновременные бронирования';
        $strings['ResourceDisplayInstructions'] = 'Ресурс не выбран. Ссылку для отображения ресурса можно найти в разделе «Управление приложением», «Ресурсы». Ресурс должен быть общедоступным.';
        $strings['Owner'] = 'Владелец';
        $strings['MaximumConcurrentReservations'] = 'Максимум одновременных бронирований';
        $strings['NotifyUsers'] = 'Уведомить пользователей';
        $strings['Message'] = 'Сообщение';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Все, у кого есть бронирование в течение следующих';
        $strings['ChangeResourceStatus'] = 'Изменить статус ресурса';
        $strings['UpdateGroupsOnImport'] = 'Обновлять существующую группу при совпадении названия';
        $strings['GroupsImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Название обязательно.</li><li>Списки участников — адреса почты через запятую.</li><li>Пустой список участников при обновлении группы оставит её состав без изменений.</li><li>Списки прав — названия ресурсов через запятую.</li><li>Пустой список прав при обновлении группы оставит права без изменений.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['PhoneRequired'] = 'Укажите телефон';
        $strings['OrganizationRequired'] = 'Укажите организацию';
        $strings['PositionRequired'] = 'Укажите должность';
        $strings['GroupMembership'] = 'Членство в группах';
        $strings['AvailableGroups'] = 'Доступные группы';
        $strings['CheckingAvailabilityError'] = 'Не удалось получить доступность ресурсов — их слишком много';
        $strings['ScanToSchedule'] = 'Отсканируйте, чтобы открыть расписание';
        $strings['MaintenanceNotice'] = 'Сейчас проводится обслуживание. Скоро вернёмся.';
        $strings['MoreResourceActions'] = 'Другие действия с ресурсами';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Установка LibreBooking';
        $strings['IncorrectInstallPassword'] = 'К сожалению, введён неверный пароль.';
        $strings['SetInstallPassword'] = 'Перед запуском установки необходимо задать пароль установки.';
        $strings['InstallPasswordInstructions'] = 'В %s задайте для %s случайный, трудно угадываемый пароль, а затем вернитесь на эту страницу.<br/>Можно использовать %s';
        $strings['NoUpgradeNeeded'] = 'Установлена актуальная версия LibreBooking. Обновление не требуется.';
        $strings['ProvideInstallPassword'] = 'Введите пароль для установки.';
        $strings['InstallPasswordLocation'] = 'Он задаётся в файле %s параметром %s.';
        $strings['VerifyInstallSettings'] = 'Проверьте следующие параметры по умолчанию, прежде чем продолжить. Или вы можете изменить их в %s.';
        $strings['DatabaseName'] = 'Имя базы данных';
        $strings['DatabaseUser'] = 'Пользователь базы данных';
        $strings['DatabaseHost'] = 'Хост базы данных';
        $strings['DatabaseCredentials'] = 'Укажите учётные данные пользователя MySQL с правом создавать базы данных. Если вы их не знаете, обратитесь к администратору базы данных. Во многих случаях подойдёт root.';
        $strings['MySQLUser'] = 'Пользователь MySQL';
        $strings['InstallOptionsWarning'] = 'Следующие параметры, вероятно, не будут работать на хостинге. Если вы устанавливаете на хостинг, выполните эти шаги с помощью мастера MySQL.';
        $strings['CreateDatabase'] = 'Создать базу данных';
        $strings['CreateDatabaseUser'] = 'Создать пользователя базы данных';
        $strings['PopulateExampleData'] = 'Импортировать демонстрационные данные. Будут созданы учётные записи администратора admin/password и пользователя user/password';
        $strings['PopulateLargeExampleData'] = 'Также импортировать большой набор демонстрационных данных: добавляет пользователей, ресурсы, группы и бронирования для реалистичного тестирования';
        $strings['DataWipeWarning'] = 'Внимание: все существующие данные будут удалены';
        $strings['RunInstallation'] = 'Запустить установку';
        $strings['UpgradeNotice'] = 'Вы обновляете версию <b>%s</b> до версии <b>%s</b>';
        $strings['RunUpgrade'] = 'Запустить обновление';
        $strings['Executing'] = 'Выполнение';
        $strings['StatementFailed'] = 'Не удалось. Детали:';
        $strings['SQLStatement'] = 'SQL-запрос:';
        $strings['ErrorCode'] = 'Код ошибки:';
        $strings['ErrorText'] = 'Текст ошибки:';
        $strings['InstallationSuccess'] = 'Установка успешно завершена!';
        $strings['RegisterAdminUser'] = 'Зарегистрируйте пользователя с правами администратора. Это необходимо, если вы не импортировали демонстрационные данные. Проверьте, что в файле %s задано $conf[\'settings\'][\'allow.self.registration\'] = \'true\'.';
        $strings['LoginWithSampleAccounts'] = 'Если вы импортировали демонстрационные данные, можно войти как admin/password (администратор) или user/password (обычный пользователь).';
        $strings['InstalledVersion'] = 'Сейчас установлена версия LibreBooking %s';
        $strings['InstallUpgradeConfig'] = 'Рекомендуется обновить конфигурационный файл';
        $strings['InstallationFailure'] = 'Были проблемы с установкой. Пожалуйста, исправьте их и повторите установку.';
        $strings['ConfigureApplication'] = 'Настройка LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Ваш файл конфигурации обновлён!';
        $strings['ConfigUpdateFailure'] = 'Не удалось автоматически обновить файл конфигурации. Замените содержимое config.php следующим текстом:';
        $strings['ScriptUrlWarning'] = 'Похоже, значение <em>script.url</em> указано неверно. Сейчас это <strong>%s</strong>, а, вероятно, должно быть <strong>%s</strong>';
        // End Install

        // Errors
        $strings['LoginError'] = 'Неверное имя пользователя или пароль';
        $strings['LdapConnectionErrorMessage'] = 'Не удалось подключиться к серверу LDAP. Обратитесь к администратору.';
        $strings['LdapDependencyMissingMessage'] = 'Аутентификация LDAP недоступна: отсутствует pear/net_ldap2. Установите его командой composer require pear/net_ldap2';
        $strings['ReservationFailed'] = 'Не удалось создать бронирование';
        $strings['MinNoticeError'] = 'Это бронирование требует заблаговременного уведомления. Самые ранние доступные дата и время: %s.';
        $strings['MinNoticeErrorUpdate'] = 'Изменение этого бронирования требует заблаговременного уведомления. Бронирования, начинающиеся ранее %s, изменять нельзя.';
        $strings['MinNoticeErrorDelete'] = 'Удаление этого бронирования требует заблаговременного уведомления. Бронирования, начинающиеся ранее %s, удалять нельзя.';
        $strings['MaxNoticeError'] = 'Это бронирование нельзя создать так далеко в будущем. Самые поздние доступные дата и время: %s.';
        $strings['MinDurationError'] = 'Это бронирование должно длиться не менее %s.';
        $strings['MaxDurationError'] = 'Это бронирование не может длиться дольше %s.';
        $strings['ConflictingAccessoryDates'] = 'Недостаточно следующего оборудования:';
        $strings['NoResourcePermission'] = 'У вас нет доступа к одному или нескольким запрошенным ресурсам.';
        $strings['ConflictingReservationDates'] = 'В следующие даты есть конфликтующие бронирования:';
        $strings['InstancesOverlapRule'] = 'Некоторые бронирования из серии пересекаются:';
        $strings['StartDateBeforeEndDateRule'] = 'Дата и время начала должны быть раньше даты и времени окончания.';
        $strings['RecurringWithoutTerminationRule'] = 'Для повторяющихся периодов недоступности требуется дата окончания.';
        $strings['StartIsInPast'] = 'Дата и время начала не могут быть в прошлом.';
        $strings['EmailDisabled'] = 'Администратор отключил уведомления по электронной почте.';
        $strings['ValidLayoutRequired'] = 'Интервалы должны покрывать все 24 часа суток, начиная и заканчивая в 00:00.';
        $strings['CustomAttributeErrors'] = 'Есть проблемы с указанными дополнительными атрибутами:';
        $strings['CustomAttributeRequired'] = 'Поле %s обязательно для заполнения.';
        $strings['CustomAttributeInvalid'] = 'Указано недопустимое значение для %s.';
        $strings['AttachmentLoadingError'] = 'К сожалению, не удалось открыть запрошенный файл.';
        $strings['InvalidAttachmentExtension'] = 'Вы можете загрузить только файлы типа: %s';
        $strings['AttachmentUploadFailed'] = 'Не удалось загрузить файл %s. Максимальный размер файла — %s МБ.';
        $strings['InvalidStartSlot'] = 'Запрошенные дата и время начала недопустимы.';
        $strings['InvalidEndSlot'] = 'Запрошенные дата и время окончания недопустимы.';
        $strings['MaxParticipantsError'] = '%s допускает не более %s участников.';
        $strings['ReservationCriticalError'] = 'При сохранении бронирования произошла критическая ошибка. Если она повторится, обратитесь к системному администратору.';
        $strings['InvalidStartReminderTime'] = 'Неверное время напоминания о начале.';
        $strings['InvalidEndReminderTime'] = 'Неверное время напоминания об окончании.';
        $strings['QuotaExceeded'] = 'Превышен лимит квоты.';
        $strings['MultiDayRule'] = '%s не допускает бронирования, переходящие на другой день.';
        $strings['InvalidReservationData'] = 'Возникли проблемы с вашим запросом на бронирование.';
        $strings['PasswordError'] = 'Пароль должен быть не короче %s символов и содержать не менее %s цифр и %s специальных символов.';
        $strings['PasswordErrorRequirements'] = 'Пароль должен быть не короче %s символов, содержать заглавные и строчные латинские буквы, не менее %s цифр и %s специальных символов.';
        $strings['NoReservationAccess'] = 'Вы не можете изменить это бронирование.';
        $strings['PasswordControlledExternallyError'] = 'Ваш пароль управляется внешней системой, и здесь его изменить нельзя.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Оборудование %s можно бронировать только с ресурсами %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Нужно забронировать не менее %s ед. оборудования %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Нельзя забронировать более %s ед. оборудования %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'Оборудование «%s» нельзя забронировать с запрошенными ресурсами';
        $strings['AccessoryNotFoundErrorMessage'] = 'Часть выбранного оборудования больше не существует. Измените выбор оборудования и повторите попытку.';

        $strings['NoResources'] = 'Вы не добавили ни одного ресурса.';
        $strings['ParticipationNotAllowed'] = 'Вы не можете присоединиться к этому бронированию.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Для этого бронирования нельзя отметить приход.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Для этого бронирования нельзя отметить уход.';
        $strings['InvalidEmailDomain'] = 'Этот адрес электронной почты не из разрешённого домена';
        $strings['TermsOfServiceError'] = 'Необходимо принять условия использования';
        $strings['UserNotFound'] = 'Такой пользователь не найден';
        $strings['ScheduleAvailabilityError'] = 'Это расписание доступно с %s по %s';
        $strings['ReservationNotFoundError'] = 'Бронирование не найдено';
        $strings['ReservationNotAvailable'] = 'Бронирование недоступно';
        $strings['TitleRequiredRule'] = 'Название бронирования обязательно';
        $strings['DescriptionRequiredRule'] = 'Описание бронирования обязательно';
        $strings['WhatCanThisGroupManage'] = 'Чем может управлять эта группа?';
        $strings['ReservationParticipationActivityPreference'] = 'Когда кто-то присоединяется к моему бронированию или покидает его';
        $strings['RegisteredAccountRequired'] = 'Бронировать могут только зарегистрированные пользователи';
        $strings['InvalidNumberOfResourcesError'] = 'Максимальное число ресурсов в одном бронировании — %s';
        $strings['ScheduleTotalReservationsError'] = 'Максимальное число одновременно забронированных ресурсов в этом расписании — %s. Это бронирование нарушит ограничение в следующие даты:';
        $strings['SelfRegistrationDisabled'] = 'Пользователь не зарегистрирован, а саморегистрация отключена. Обратитесь к администратору, чтобы создать учётную запись.';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Создать бронирование';
        $strings['EditReservation'] = 'Изменить бронирование';
        $strings['LogIn'] = 'Войти';
        $strings['ManageReservations'] = 'Бронирования';
        $strings['AwaitingActivation'] = 'Ожидает активации';
        $strings['PendingApproval'] = 'В ожидании одобрения';
        $strings['ManageSchedules'] = 'Расписания';
        $strings['ManageResources'] = 'Ресурсы';
        $strings['ManageAccessories'] = 'Оборудование';
        $strings['ManageUsers'] = 'Пользователи';
        $strings['ManageGroups'] = 'Группы';
        $strings['ManageQuotas'] = 'Квоты';
        $strings['ManageBlackouts'] = 'Периоды недоступности';
        $strings['MyDashboard'] = 'Моя панель';
        $strings['ServerSettings'] = 'Настройки сервера';
        $strings['Dashboard'] = 'Панель';
        $strings['Help'] = 'Помощь';
        $strings['Administration'] = 'Администрирование';
        $strings['About'] = 'О программе';
        $strings['Bookings'] = 'Бронирования';
        $strings['Account'] = 'Аккаунт';
        $strings['EditProfile'] = 'Редактирование профиля';
        $strings['FindAnOpening'] = 'Найти свободное время';
        $strings['OpenInvitations'] = 'Открытые приглашения';
        $strings['ResourceCalendar'] = 'Календарь ресурсов';
        $strings['Reservation'] = 'Новое бронирование';
        $strings['Install'] = 'Установка';
        $strings['ChangePassword'] = 'Изменить пароль';
        $strings['MyAccount'] = 'Мой аккаунт';
        $strings['Profile'] = 'Профиль';
        $strings['ApplicationManagement'] = 'Управление приложением';
        $strings['ForgotPassword'] = 'Восстановление пароля';
        $strings['NotificationPreferences'] = 'Настройки уведомлений';
        $strings['ManageAnnouncements'] = 'Объявления';
        $strings['Responsibilities'] = 'Обязанности';
        $strings['GroupReservations'] = 'Бронирования группы';
        $strings['ResourceReservations'] = 'Бронирования ресурса';
        $strings['Customization'] = 'Настройка';
        $strings['Attributes'] = 'Атрибуты';
        $strings['AccountActivation'] = 'Активация аккаунта';
        $strings['ScheduleReservations'] = 'Бронирования расписания';
        $strings['Reports'] = 'Отчёты';
        $strings['GenerateReport'] = 'Создать новый отчёт';
        $strings['MySavedReports'] = 'Мои сохранённые отчёты';
        $strings['CommonReports'] = 'Общие отчёты';
        $strings['ViewDay'] = 'Посмотреть день';
        $strings['Group'] = 'Группа';
        $strings['ManageConfiguration'] = 'Настройка приложения';
        $strings['LookAndFeel'] = 'Внешний вид';
        $strings['ManageResourceGroups'] = 'Группы ресурсов';
        $strings['ManageResourceTypes'] = 'Типы ресурсов';
        $strings['ManageResourceStatus'] = 'Статусы ресурсов';
        $strings['ReservationColors'] = 'Цвета бронирований';
        $strings['SearchReservations'] = 'Поиск бронирований';
        $strings['ManagePayments'] = 'Платежи';
        $strings['ViewCalendar'] = 'Посмотреть календарь';
        $strings['DataCleanup'] = 'Очистка данных';
        $strings['ManageEmailTemplates'] = 'Шаблоны писем';
        $strings['CheckResources'] = 'Обзор ресурсов';
        $strings['CheckSchedules'] = 'Обзор расписаний';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'В';
        $strings['DayMondaySingle'] = 'П';
        $strings['DayTuesdaySingle'] = 'В';
        $strings['DayWednesdaySingle'] = 'С';
        $strings['DayThursdaySingle'] = 'Ч';
        $strings['DayFridaySingle'] = 'П';
        $strings['DaySaturdaySingle'] = 'С';

        $strings['DaySundayAbbr'] = 'Вс';
        $strings['DayMondayAbbr'] = 'Пн';
        $strings['DayTuesdayAbbr'] = 'Вт';
        $strings['DayWednesdayAbbr'] = 'Ср';
        $strings['DayThursdayAbbr'] = 'Чт';
        $strings['DayFridayAbbr'] = 'Пт';
        $strings['DaySaturdayAbbr'] = 'Сб';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Ваше бронирование одобрено';
        $strings['ReservationCreatedSubject'] = 'Ваше бронирование создано';
        $strings['ReservationUpdatedSubject'] = 'Ваше бронирование обновлено';
        $strings['ReservationDeletedSubject'] = 'Ваше бронирование удалено';
        $strings['ReservationCreatedAdminSubject'] = 'Уведомление: бронирование создано';
        $strings['ReservationUpdatedAdminSubject'] = 'Уведомление: бронирование обновлено';
        $strings['ReservationDeleteAdminSubject'] = 'Уведомление: бронирование удалено';
        $strings['ReservationApprovalAdminSubject'] = 'Уведомление: бронирование требует вашего одобрения';
        $strings['ParticipantAddedSubject'] = 'Уведомление об участии в бронировании';
        $strings['ParticipantDeletedSubject'] = 'Бронирование удалено';
        $strings['InviteeAddedSubject'] = 'Приглашение в бронирование';
        $strings['ResetPasswordRequest'] = 'Запрос на сброс пароля';

        $strings['ActivateYourAccount'] = 'Активируйте свой аккаунт';
        $strings['ReportSubject'] = 'Запрошенный вами отчёт (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Скоро начнётся бронирование ресурса %s';
        $strings['ReservationEndingSoonSubject'] = 'Скоро закончится бронирование ресурса %s';
        $strings['UserAdded'] = 'Добавлен новый пользователь';
        $strings['GuestAccountCreatedSubject'] = 'Данные вашей учётной записи %s';
        $strings['AccountCreatedSubject'] = 'Данные вашей учётной записи %s';
        $strings['InviteUserSubject'] = '%s приглашает вас присоединиться к %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Бронирование ресурса %s одобрено';
        $strings['ReservationCreatedSubjectWithResource'] = 'Бронирование ресурса %s создано';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Бронирование ресурса %s обновлено';
        $strings['ReservationDeletedSubjectWithResource'] = 'Бронирование ресурса %s удалено';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Уведомление: бронирование ресурса %s создано';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Уведомление: бронирование ресурса %s обновлено';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Уведомление: бронирование ресурса %s удалено';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Уведомление: бронирование ресурса %s требует вашего одобрения';
        $strings['ParticipantAddedSubjectWithResource'] = '%s добавил(а) вас в бронирование ресурса %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s изменил(а) бронирование ресурса %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s удалил(а) бронирование ресурса %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s пригласил(а) вас в бронирование ресурса %s';
        $strings['MissedCheckinEmailSubject'] = 'Пропущена отметка о приходе: %s';
        $strings['ReservationShareSubject'] = '%s поделился(ась) бронированием ресурса %s';
        $strings['ReservationSeriesEndingSubject'] = 'Серия бронирований ресурса %s заканчивается %s';
        $strings['ReservationParticipantAccept'] = '%s принял(а) ваше приглашение к бронированию ресурса %s на %s';
        $strings['ReservationParticipantDecline'] = '%s отклонил(а) ваше приглашение к бронированию ресурса %s на %s';
        $strings['ReservationParticipantJoin'] = '%s присоединился(ась) к вашему бронированию ресурса %s на %s';
        $strings['ReservationAvailableSubject'] = 'Ресурс %s свободен %s';
        $strings['ResourceStatusChangedSubject'] = 'Доступность ресурса %s изменилась';

        $strings['UserDeleted'] = 'Учётная запись %s удалена пользователем %s';
        // End Email Subjects

        //NEEDS CHECKING
        //Past Reservations
        $strings['NoPastReservations'] = 'У вас нет предыдущих бронирований';
        $strings['PastReservations'] = 'Предыдущие бронирования';
        $strings['AllNoPastReservations'] = 'За последние %s дней нет предыдущих бронирований';
        $strings['AllPastReservations'] = 'Все предыдущие бронирования';
        $strings['Yesterday'] = 'Вчера';
        $strings['EarlierThisWeek'] = 'Ранее на этой неделе';
        $strings['PreviousWeek'] = 'Прошлая неделя';
        //End Past Reservations

        //Group Upcoming Reservations
        $strings['NoGroupUpcomingReservations'] = 'У вашей группы нет предстоящих бронирований';
        $strings['GroupUpcomingReservations'] = 'Предстоящие бронирования моих групп';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Произошла ошибка при входе через Facebook. Попробуйте ещё раз.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'У вас нет бронирований, ожидающих одобрения';
        $strings['PendingApprovalReservations'] = 'Бронирования, ожидающие одобрения';
        $strings['LaterThisMonth'] = 'Позже в этом месяце';
        $strings['LaterThisYear'] = 'Позже в этом году';
        $strings['Other'] = 'Другое';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Нет бронирований без отметки об уходе';
        $strings['MissingCheckOutReservations'] = 'Бронирования без отметки об уходе';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Невозможно просмотреть сведения о бронировании: у вас нет доступа ни к одному из его ресурсов';
        $strings['Check'] = 'Обзор';
        $strings['PermissionType'] = 'Тип прав';
        $strings['NoResourcesToView'] = 'Нет доступных ресурсов';
        $strings['Info'] = 'Страница _PAGE_ из _PAGES_ (всего записей: _MAX_)';
        $strings['LengthMenu'] = 'Показывать по _MENU_ записей на странице';
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
        $days['full'] = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
        // The three letter abbreviation
        $days['abbr'] = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        // The two letter abbreviation
        $days['two'] = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
        // The one letter abbreviation
        $days['letter'] = ['В', 'П', 'В', 'С', 'Ч', 'П', 'С'];

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
        $months['full'] = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        // The three letter month name
        $months['abbr'] = ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'];

        $this->Months = $months;

        return $this->Months;
    }

    /**
     * @return array
     */
    protected function _LoadLetters()
    {
        $this->Letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ru';
    }
}
