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
    protected function _LoadStrings()
    {
        $strings = parent::_LoadStrings();

        $strings['FirstName'] = 'Имя';
        $strings['LastName'] = 'Фамилия';
        $strings['Timezone'] = 'Часовой пояс';
        $strings['Edit'] = 'Редактировать';
        $strings['Change'] = 'Изменить';
        $strings['Rename'] = 'Переименовать';
        $strings['Remove'] = 'Очистить';
        $strings['Delete'] = 'Удалить';
        $strings['Update'] = 'Обновление';
        $strings['Cancel'] = 'Отменить';
        $strings['Add'] = 'Добавить';
        $strings['Name'] = 'Имя';
        $strings['Yes'] = 'Да';
        $strings['No'] = 'Нет';
        $strings['FirstNameRequired'] = 'Требуется имя.';
        $strings['LastNameRequired'] = 'Требуется фамилия.';
        $strings['PwMustMatch'] = 'Пороли не совпадают.';
        $strings['ValidEmailRequired'] = 'Введите корректный электронный почтовый ящик.';
        $strings['UniqueEmailRequired'] = 'Такой email уже зарегистрирован.';
        $strings['UniqueUsernameRequired'] = 'Такое имя уже зарегистрированно.';
        $strings['UserNameRequired'] = 'Введите имя пользователя.';
        $strings['CaptchaMustMatch'] = 'Пожалуйста, введите буквы с изображения безопасности точно, как показано.';
        $strings['Today'] = 'Сегодня';
        $strings['Week'] = 'Неделя';
        $strings['Month'] = 'Месяц';
        $strings['BackToCalendar'] = 'Назад в календарь';
        $strings['BeginDate'] = 'Начало';
        $strings['EndDate'] = 'Конец';
        $strings['Username'] = 'Имя пользователя';
        $strings['Password'] = 'Пароль';
        $strings['PasswordConfirmation'] = 'Подтверждение пороля';
        $strings['DefaultPage'] = 'Домашняя страница по умолчанию';
        $strings['MyCalendar'] = 'Мой календарь';
        $strings['ScheduleCalendar'] = 'Распичание (календарь)';
        $strings['Registration'] = 'Регистрация';
        $strings['NoAnnouncements'] = 'Нет обновлений.';
        $strings['Announcements'] = 'Объявления';
        $strings['NoUpcomingReservations'] = 'У вас нет информации о предстоящем бронировании';
        $strings['UpcomingReservations'] = 'Предстоящее бронирование';
        $strings['AllNoUpcomingReservations'] = 'Нет информации о предстоящих бронированиях на следующие %s дней';
        $strings['AllUpcomingReservations'] = 'Все бронирования';
        $strings['ShowHide'] = 'Показать/Скрыть';
        $strings['Error'] = 'Ошибка';
        $strings['ReturnToPreviousPage'] = 'Возврат к странице, на которой вы были';
        $strings['UnknownError'] = 'Неизвестная ошибка';
        $strings['DatabaseConnectionError'] = 'Не удалось подключиться к серверу базы данных.<br/>Попросите администратора сайта проверить хост, имя пользователя и пароль базы данных в <code>config/config.php</code>.';
        $strings['DatabaseNotFoundError'] = 'Не удалось выбрать указанную базу данных.<br/>Попросите администратора сайта проверить имя базы данных в <code>config/config.php</code> и убедиться, что база создана и инициализирована.';
        $strings['InsufficientPermissionsError'] = 'У вас нет разрешения на доступ к этому ресурсу';
        $strings['MissingReservationResourceError'] = 'Ресурс не был выбран';
        $strings['MissingReservationScheduleError'] = 'График не был выбран';
        $strings['DoesNotRepeat'] = 'Не повторять';
        $strings['Daily'] = 'Ежедневно';
        $strings['Weekly'] = 'Еженедельно';
        $strings['Monthly'] = 'Ежемесячно';
        $strings['Yearly'] = 'Ежегодно';
        $strings['RepeatPrompt'] = 'Повтор';
        $strings['hours'] = 'часы';
        $strings['days'] = 'дни';
        $strings['weeks'] = 'недели';
        $strings['months'] = 'месяцы';
        $strings['years'] = 'годы';
        $strings['day'] = 'день';
        $strings['week'] = 'неделя';
        $strings['month'] = 'месяц';
        $strings['year'] = 'год';
        $strings['repeatDayOfMonth'] = 'день месяца';
        $strings['repeatDayOfWeek'] = 'день недели';
        $strings['RepeatUntilPrompt'] = 'До';
        $strings['RepeatEveryPrompt'] = 'Каждый';
        $strings['RepeatDaysPrompt'] = 'В';
        $strings['CreateReservationHeading'] = 'Новое бронирование';
        $strings['EditReservationHeading'] = 'Редактирование бронирования %s';
        $strings['ViewReservationHeading'] = 'Просмотр бронирования %s';
        $strings['ReservationErrors'] = 'Изменить бронирование';
        $strings['Create'] = 'Создать';
        $strings['ThisInstance'] = 'Только это';
        $strings['AllInstances'] = 'Все экземпляры';
        $strings['FutureInstances'] = 'Будущие экземпляры';
        $strings['Print'] = 'Печать';
        $strings['ShowHideNavigation'] = 'Показать/Скрыть навигацию';
        $strings['ReferenceNumber'] = 'Реферальный номер';
        $strings['Tomorrow'] = 'Завтра';
        $strings['LaterThisWeek'] = 'Позже на этой неделе';
        $strings['NextWeek'] = 'Следующая неделя';
        $strings['SignOut'] = 'Выйти';
        $strings['JavascriptRequired'] = 'Для корректной работы этого приложения требуется JavaScript. Пожалуйста, включите JavaScript в настройках вашего браузера.';
        $strings['ScriptUrlNotConfigured'] = 'LibreBooking настроен неверно: значение <code>script.url</code> пусто, поэтому часть возможностей работать не будет. Обратитесь к администратору.';
        $strings['ScriptUrlMissingWebSuffix'] = 'LibreBooking настроен неверно: значение <code>script.url</code> должно заканчиваться на <code>/Web</code>, иначе ссылки навигации и перенаправление после входа будут работать неправильно. Обратитесь к администратору.';
        $strings['LayoutDescription'] = 'Запускает на %s, показывая %s дней';
        $strings['AllResources'] = 'Все ресурсы';
        $strings['TakeOffline'] = 'В автономный режим';
        $strings['BringOnline'] = 'Откл. автономный режим';
        $strings['AddImage'] = 'Добавить изображение';
        $strings['NoImage'] = 'Нет добавленых изображений';
        $strings['Move'] = 'Очистить';
        $strings['AppearsOn'] = 'оявляется на %s';
        $strings['Location'] = 'Место';
        $strings['NoLocationLabel'] = '(не выбрано место)';
        $strings['Contact'] = 'Как связаться';
        $strings['NoContactLabel'] = '(нет информации)';
        $strings['Description'] = 'Описание';
        $strings['NoDescriptionLabel'] = '(нет описания)';
        $strings['Notes'] = 'Примечания';
        $strings['NoNotesLabel'] = '(нет примечания)';
        $strings['NoTitleLabel'] = '(нет названия)';
        $strings['UsageConfiguration'] = 'Используемая настройка';
        $strings['ChangeConfiguration'] = 'изменить настройку';
        $strings['ResourceMinLength'] = 'Бронирование должно длиться не менее %s';
        $strings['ResourceMinLengthNone'] = 'Нет минимальной продолжительности бронирования';
        $strings['ResourceMaxLength'] = 'Бронирование не может быть больше %s';
        $strings['ResourceMaxLengthNone'] = 'Нет максимальной продолжительности бронирования';
        $strings['ResourceRequiresApproval'] = 'Бронирования должны быть утверждены';
        $strings['ResourceRequiresApprovalNone'] = 'Бронирования НЕ должны быть утверждены';
        $strings['ResourcePermissionAutoGranted'] = 'Разрешение предоставляется автоматически';
        $strings['ResourcePermissionNotAutoGranted'] = 'Разрешение НЕ предоставляется автоматически';
        $strings['ResourceMinNotice'] = 'Бронирование должно быть сделано не мене %s до времени начала';
        $strings['ResourceMinNoticeNone'] = 'Могут быть сделаны для текущего времени бронирования';
        $strings['ResourceMinNoticeUpdate'] = 'Бронирования нужно изменять не позднее чем за %s до начала';
        $strings['ResourceMinNoticeNoneUpdate'] = 'Бронирования можно изменять вплоть до текущего момента';
        $strings['ResourceMinNoticeDelete'] = 'Бронирования нужно удалять не позднее чем за %s до начала';
        $strings['ResourceMinNoticeNoneDelete'] = 'Бронирования можно удалять вплоть до текущего момента';
        $strings['ResourceMaxNotice'] = 'Бронирования не должны заканчиваться более чем %s от текущего времени';
        $strings['ResourceMaxNoticeNone'] = 'Бронирование может закончиться в любой момент в будущем';
        $strings['ResourceBufferTime'] = 'Должно быть %s между бронированиями';
        $strings['ResourceBufferTimeNone'] = 'Нет буфера между бронированиями';
        $strings['ResourceAllowMultiDay'] = 'Бронирование разрешено на несколько дней подряд';
        $strings['ResourceNotAllowMultiDay'] = 'Бронирование НЕ разрешено на несколько дней подряд';
        $strings['ResourceCapacity'] = 'Этот помещенеи вмещает %s человек';
        $strings['ResourceCapacityNone'] = 'Этот ресурс имеет неограниченные возможности';
        $strings['AddNewResource'] = 'Добавить новое помещение';
        $strings['AddNewUser'] = 'Добавить нового пользователя';
        $strings['AddUser'] = 'Добавить пользователя';
        $strings['Schedule'] = 'Планировщик';
        $strings['AddResource'] = 'Добавть помещение';
        $strings['Capacity'] = 'Вместимость';
        $strings['Access'] = 'Доступ';
        $strings['Duration'] = 'Продолжительность';
        $strings['Active'] = 'Активен';
        $strings['Inactive'] = 'Неактивен';
        $strings['ResetPassword'] = 'Сбросить пароль';
        $strings['LastLogin'] = 'Последний вход';
        $strings['Search'] = 'Поиск';
        $strings['ResourcePermissions'] = 'права доступа к ресурсам';
        $strings['Reservations'] = 'Бронирования';
        $strings['Groups'] = 'Группы';
        $strings['Users'] = 'Пользователи';
        $strings['ResetPassword'] = 'Сбросить пароль';
        $strings['AllUsers'] = 'Все полльзователи';
        $strings['AllGroups'] = 'Все группы';
        $strings['AllSchedules'] = 'Все планировщики';
        $strings['UsernameOrEmail'] = 'Имя пользователя или Email';
        $strings['Members'] = 'Члены';
        $strings['QuickSlotCreation'] = 'Создать интервалы каждые %s минут между %s и %s';
        $strings['ApplyUpdatesTo'] = 'Применить обновления к';
        $strings['CancelParticipation'] = 'Отказаться';
        $strings['Attending'] = 'Принять';
        $strings['QuotaConfiguration'] = 'В %s для %s пользователи %s ограничены %s %s на %s';
        $strings['QuotaEnforcement'] = 'Применяется %s %s';
        $strings['reservations'] = 'бронирования';
        $strings['reservation'] = 'бронирование';
        $strings['ChangeCalendar'] = 'Сменить календарь';
        $strings['AddQuota'] = 'Добавить квоту';
        $strings['FindUser'] = 'Найти пользователя';
        $strings['Created'] = 'Создан';
        $strings['LastModified'] = 'Последнее изменение';
        $strings['GroupName'] = 'Имя группы';
        $strings['GroupMembers'] = 'Члены группы';
        $strings['GroupRoles'] = 'Роли группы';
        $strings['GroupAdmin'] = 'Администратор группы';
        $strings['Actions'] = 'Действия';
        $strings['CurrentPassword'] = 'Текущий пароль';
        $strings['NewPassword'] = 'Новый пароль';
        $strings['InvalidPassword'] = 'Неверный пароль';
        $strings['PasswordChangedSuccessfully'] = 'Ваш пароль был успешно изменен';
        $strings['SignedInAs'] = 'Вошли как';
        $strings['NotSignedIn'] = 'Вы не вошли в аккаунт';
        $strings['ReservationTitle'] = 'Название бронирования';
        $strings['ReservationDescription'] = 'Описание бронирования';
        $strings['ResourceList'] = 'Помещения зарезервированны';
        $strings['Accessories'] = 'Оборудование';
        $strings['ParticipantList'] = 'Список участников';
        $strings['InvitationList'] = 'Приглашенные';
        $strings['AccessoryName'] = 'Наименование оборудования';
        $strings['QuantityAvailable'] = 'Кол-во в наличии';
        $strings['Resources'] = 'Помещения';
        $strings['Participants'] = 'Участники';
        $strings['User'] = 'Пользователь';
        $strings['Resource'] = 'Помещение';
        $strings['Status'] = 'Статус';
        $strings['Approve'] = 'Одобрено';
        $strings['Page'] = 'Страница';
        $strings['Rows'] = 'Колонка';
        $strings['Unlimited'] = 'Неограниченный';
        $strings['Email'] = 'Email';
        $strings['EmailAddress'] = 'Email адрес';
        $strings['Phone'] = 'Телефон';
        $strings['Organization'] = 'Организация';
        $strings['Position'] = 'Расположение (адрес)';
        $strings['Language'] = 'Язык';
        $strings['Permissions'] = 'Разрешения';
        $strings['Reset'] = 'Сброс';
        $strings['FindGroup'] = 'Найти группу';
        $strings['Manage'] = 'Управлять';
        $strings['None'] = 'Никого';
        $strings['AddToOutlook'] = 'Добавить в календарь';
        $strings['Done'] = 'Готово';
        $strings['RememberMe'] = 'Напомнить мне';
        $strings['FirstTimeUser?'] = 'Вы у нас впервые?';
        $strings['CreateAnAccount'] = 'Создать аккаунт';
        $strings['ViewSchedule'] = 'Посмотреть планировщик';
        $strings['ForgotMyPassword'] = 'Я забыл мой пароль';
        $strings['YouWillBeEmailedANewPassword'] = 'Отправлен по электронной почте новый случайно сгенерированный пароль';
        $strings['Close'] = 'Закрыть';
        $strings['ExportToCSV'] = 'Экспорт в CSV';
        $strings['OK'] = 'OK';
        $strings['Working'] = 'Ожидайте...';
        $strings['Login'] = 'Логин';
        $strings['AdditionalInformation'] = 'Дополнительная информация';
        $strings['AllFieldsAreRequired'] = 'все поля обязательны для заполнения';
        $strings['Optional'] = 'необязательно';
        $strings['YourProfileWasUpdated'] = 'Ваш профиль обновлен';
        $strings['YourSettingsWereUpdated'] = 'Ваши настройки обновлены';
        $strings['Register'] = 'Регистрация';
        $strings['SecurityCode'] = 'Секретный код';
        $strings['ReservationCreatedPreference'] = 'Когда я создаю бронирование или заказ создается от моего имени';
        $strings['ReservationUpdatedPreference'] = 'Когда я обновляю бронирование или заказ обновляется от моего имени';
        $strings['ReservationDeletedPreference'] = 'Когда я удаляю бронирование или заказ удаляется от моего имени';
        $strings['ReservationApprovalPreference'] = 'Когда мое бронирование в ожидании одобрения';
        $strings['PreferenceSendEmail'] = 'Вышли мне электронное письмо';
        $strings['PreferenceNoEmail'] = 'Не напоминать мне';
        $strings['ReservationCreated'] = 'Ваше резервирование успешно создано';
        $strings['ReservationUpdated'] = 'Ваше резервирование успешно обновлено!';
        $strings['ReservationRemoved'] = 'Ваше резервирование удалено';
        $strings['ReservationRequiresApproval'] = 'Одно или несколько зарезервированных помещений, требуется дополнительное разрешение перед использованием. Данное бронирование будет в ожидании, пока оно не будет одобрено.';
        $strings['YourReferenceNumber'] = 'Ваш реферальный номер %s';
        $strings['UpdatingReservation'] = 'Обновление бронирования';
        $strings['ChangeUser'] = 'Сменить пользователя';
        $strings['MoreResources'] = 'Больше помещений';
        $strings['ReservationLength'] = 'Длительность бронирования';
        $strings['ParticipantList'] = 'Список участников';
        $strings['AddParticipants'] = 'Добавить участников';
        $strings['InviteOthers'] = 'Пригласить других';
        $strings['AddResources'] = 'Добавить помещения';
        $strings['AddAccessories'] = 'Добавить оборудование';
        $strings['Accessory'] = 'Оборудование';
        $strings['QuantityRequested'] = 'Требуемое кол-во';
        $strings['CreatingReservation'] = 'Создание бронирования';
        $strings['UpdatingReservation'] = 'Обновление бронирования';
        $strings['DeleteWarning'] = 'Это действие является постоянным и бесповоротным!';
        $strings['DeleteAccessoryWarning'] = 'Удаляя это оборудование, будет удалено из всех бронирования и мероприятий';
        $strings['AddAccessory'] = 'Добавить оборудование';
        $strings['AddBlackout'] = 'Добавить прошедшее';
        $strings['AllResourcesOn'] = 'Все помещения';
        $strings['Reason'] = 'Причина';
        $strings['BlackoutShowMe'] = 'Покажи конфликты с другими мероприятиями';
        $strings['BlackoutDeleteConflicts'] = 'Удалить конфликтующие мероприятия и бронирования';
        $strings['Filter'] = 'Фильтр';
        $strings['Between'] = 'Между';
        $strings['CreatedBy'] = 'Создано ';
        $strings['BlackoutCreated'] = 'Создано прошедшее';
        $strings['BlackoutNotCreated'] = 'прошедшее не может быть создан';
        $strings['BlackoutUpdated'] = 'Период недоступности обновлён';
        $strings['BlackoutNotUpdated'] = 'прошедшее не может быть обновлен';
        $strings['BlackoutConflicts'] = 'Есть конфлик в прошедшее по времени';
        $strings['ReservationConflicts'] = 'Есть конфликтующие время бронирование';
        $strings['UsersInGroup'] = 'Пользователи в этой группе';
        $strings['Browse'] = 'Просматреть';
        $strings['DeleteGroupWarning'] = 'Удаляя эту группу будут удалены все связанные с ней права доступа к ресурсам. Пользователи в этой группе могут потерять доступ к этим ресурсам.';
        $strings['WhatRolesApplyToThisGroup'] = 'Какие роли относятся к этой группе?';
        $strings['WhoCanManageThisGroup'] = 'Кто может управлять этой группой?';
        $strings['WhoCanManageThisSchedule'] = 'Кто может управлять планировщиком?';
        $strings['AddGroup'] = 'Добавить группу';
        $strings['AllQuotas'] = 'Все квоты';
        $strings['QuotaReminder'] = 'Помните: Квоты применяются на основе часового пояса планировщика.';
        $strings['AllReservations'] = 'Все бронирования';
        $strings['PendingReservations'] = 'Ожидающие бронирования';
        $strings['Approving'] = 'Утверждения';
        $strings['MoveToSchedule'] = 'Переместить в планировщик';
        $strings['DeleteResourceWarning'] = 'Удаление этого ресурса будут удалены все связанные с ним данные, и связи с ним';
        $strings['DeleteResourceWarningReservations'] = 'все прошлые, нынешние и будущие бронирования, связанные с ним';
        $strings['DeleteResourceWarningPermissions'] = 'Все назначенные разрешение';
        $strings['DeleteResourceWarningReassign'] = 'Укажите какую-нибудь причину, что вы не хотите быть удалены, прежде чем продолжить';
        $strings['ScheduleLayout'] = 'Разметка (все время %s)';
        $strings['ReservableTimeSlots'] = 'Доступные для бронирования интервалы';
        $strings['BlockedTimeSlots'] = 'Блокированные временные интервалы';
        $strings['ThisIsTheDefaultSchedule'] = 'Это планировщик по умолчанию';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Планировщик по умолчанию не может быть удален';
        $strings['MakeDefault'] = 'Сделать по умолчанию';
        $strings['BringDown'] = 'Опустить вниз';
        $strings['ChangeLayout'] = 'Изменить разметку';
        $strings['AddSchedule'] = 'Добавить планировщика';
        $strings['StartsOn'] = 'Начинается';
        $strings['NumberOfDaysVisible'] = 'Число видимых дней';
        $strings['UseSameLayoutAs'] = 'Использовать некоторые планировки как';
        $strings['Format'] = 'Формат';
        $strings['OptionalLabel'] = 'Дополнительный ярлык';
        $strings['LayoutInstructions'] = 'Введите один слот в каждой строке. Слоты должны быть обеспечены для всех 24 часов дня начиная и заканчивая в 12:00 AM.';
        $strings['AddUser'] = 'Добавить пользователя';
        $strings['UserPermissionInfo'] = 'Фактический доступ к ресурсу может отличаться в зависимости от роли пользователя, права доступа группы или внешние настройки разрешений';
        $strings['DeleteUserWarning'] = 'Удаление этого пользователя удалит все его текущие, будущие и прошедшие бронирования.';
        $strings['AddAnnouncement'] = 'Добавить объявление';
        $strings['Announcement'] = 'Объявление';
        $strings['Priority'] = 'Приоритет';
        $strings['Reservable'] = 'Резервирование';
        $strings['Unreservable'] = 'Незарезервированные';
        $strings['Reserved'] = 'Зарезервированные';
        $strings['MyReservation'] = 'Моё бронирование';
        $strings['Pending'] = 'В ожидании';
        $strings['Past'] = 'Прошло';
        $strings['Restricted'] = 'Ограниченные';
        $strings['ViewAll'] = 'Посмотреть все';
        $strings['MoveResourcesAndReservations'] = 'Переместить помещения и мероприятия в';
        $strings['TurnOffSubscription'] = 'Отключить календарные подписки';
        $strings['TurnOnSubscription'] = 'Разрешить подписки на этот календарь';
        $strings['SubscribeToCalendar'] = 'Подписаться на этот календарь';
        $strings['UrlCopiedToClipboard'] = 'Ссылка скопирована в буфер обмена';
        $strings['SubscriptionsAreDisabled'] = 'Администратор отключил подписки календаря';
        $strings['NoResourceAdministratorLabel'] = '(Нет администратора помещения)';
        $strings['WhoCanManageThisResource'] = 'то может управлять этим помещением?';
        $strings['ResourceAdministrator'] = 'Администратор помещения';
        $strings['Private'] = 'Личное';
        $strings['Accept'] = 'Приянять';
        $strings['Decline'] = 'Отказать';
        $strings['ShowFullWeek'] = 'Показать полную неделю';
        $strings['CustomAttributes'] = 'Пользовательские атрибуты';
        $strings['AddAttribute'] = 'Добавление атрибута';
        $strings['EditAttribute'] = 'Обновление атрибута';
        $strings['DisplayLabel'] = 'Показать ярлыки';
        $strings['Type'] = 'Тип';
        $strings['Required'] = 'Обязательный';
        $strings['ValidationExpression'] = 'Проверка выражения';
        $strings['PossibleValues'] = 'Возможные значения';
        $strings['SingleLineTextbox'] = 'Текстовое поле одной строкой';
        $strings['MultiLineTextbox'] = 'Множество текстовых полей';
        $strings['Checkbox'] = 'Галочка';
        $strings['SelectList'] = 'Выбор списка';
        $strings['CommaSeparated'] = 'разделенные запятой';
        $strings['Category'] = 'Категория';
        $strings['CategoryReservation'] = 'Бронирование';
        $strings['CategoryGroup'] = 'Группа';
        $strings['SortOrder'] = 'Порядок сортировки';
        $strings['Title'] = 'Название';
        $strings['AdditionalAttributes'] = 'Дополнительные атрибуты';
        $strings['True'] = 'Истина';
        $strings['False'] = 'Ложь';
        $strings['ForgotPasswordEmailSent'] = 'Электронное сообщение было отправлено по указанному адресу с инструкциями для восстановления пароля';
        $strings['ActivationEmailSent'] = 'Вы получите письмо с кодом активации в ближайшее время.';
        $strings['AccountActivationError'] = 'К сожалению, мы не смогли активировать свой аккаунт.';
        $strings['Attachments'] = 'Вложения';
        $strings['AttachFile'] = 'Прикрепить файл';
        $strings['Maximum'] = 'максимум';
        $strings['NoScheduleAdministratorLabel'] = 'Нет администратора для планировщика';
        $strings['ScheduleAdministrator'] = 'Администратор планировщика';
        $strings['Total'] = 'Всего';
        $strings['QuantityReserved'] = 'Кол-во резервирований';
        $strings['AllAccessories'] = 'Всё оборудование';
        $strings['GetReport'] = 'Получить отчет';
        $strings['NoResultsFound'] = 'Не найдено результатов';
        $strings['SaveThisReport'] = 'Сохранить этот отчёт';
        $strings['ReportSaved'] = 'Отчёт сохранен!';
        $strings['EmailReport'] = 'Отчет на Email';
        $strings['ReportSent'] = 'Отчёт отправлен!';
        $strings['RunReport'] = 'Запустить отчёт';
        $strings['NoSavedReports'] = 'У вас нет сохраненных отчетов.';
        $strings['CurrentWeek'] = 'Текущая неделя';
        $strings['CurrentMonth'] = 'Текущий месяц';
        $strings['AllTime'] = 'Всё время';
        $strings['FilterBy'] = 'Фильтр от';
        $strings['Select'] = 'Выбор';
        $strings['List'] = 'Список';
        $strings['TotalTime'] = 'Общее время';
        $strings['Count'] = 'Счёт';
        $strings['Usage'] = 'Применение';
        $strings['AggregateBy'] = 'Совокупное по';
        $strings['Range'] = 'Ряд';
        $strings['Choose'] = 'Выберите';
        $strings['All'] = 'Все';
        $strings['ViewAsChart'] = 'Показать таблицей';
        $strings['ReservedResources'] = 'Зарезервированные помещения';
        $strings['ReservedAccessories'] = 'Зарезервированное оборудование';
        $strings['ResourceUsageTimeBooked'] = 'Использование ресурсов - время бронирования';
        $strings['ResourceUsageReservationCount'] = 'Использование ресурсов - Бронирование ряд';
        $strings['Top20UsersTimeBooked'] = 'Топ-20 пользователей — по времени бронирования';
        $strings['Top20UsersReservationCount'] = 'Топ-20 пользователей — по количеству бронирований';
        $strings['ConfigurationUpdated'] = 'Файл конфигурации был обновлен';
        $strings['ConfigurationUiNotEnabled'] = 'Эта страница не может быть доступна, потому что $conf[\'settings\'][\'pages\'][\'enable.configuration\'] установлен в положение ложно или отсутствует.';
        $strings['ConfigurationFileNotWritable'] = 'Конфигурационный файл не доступен для записи. Пожалуйста, проверьте разрешения этого файла и повторите попытку.';
        $strings['ConfigurationEnvWarning'] = 'Часть значений конфигурации переопределена переменными окружения или файлом <code>.env</code>. Изменения могут не применяться, пока вы не удалите соответствующие переменные окружения.';
        $strings['ConfigurationUpdateHelp'] = 'Обратитесь к разделу конфигурация <a target=_blank href=%s>Help File</a> для документации об этих настройках';
        $strings['GeneralConfigSettings'] = 'настройки';
        $strings['UseSameLayoutForAllDays'] = 'Используйте тот же формат для всех дней';
        $strings['LayoutVariesByDay'] = 'Макет варьируется в зависимости от дня';
        $strings['ManageReminders'] = 'Напоминания';
        $strings['ReminderUser'] = 'ID пользователя';
        $strings['ReminderMessage'] = 'Сообщение';
        $strings['ReminderAddress'] = 'Адреса';
        $strings['ReminderSendtime'] = 'Время для отправки';
        $strings['ReminderRefNumber'] = 'Реферальный номер бронирования';
        $strings['ReminderSendtimeDate'] = 'Дата напоминания';
        $strings['ReminderSendtimeTime'] = 'Время напоминания (HH:MM)';
        $strings['ReminderSendtimeAMPM'] = 'AM / PM';
        $strings['AddReminder'] = 'Добавить напоминание';
        $strings['DeleteReminderWarning'] = 'Вы уверены в этом?';
        $strings['NoReminders'] = 'У вас нет предстоящих напоминаний.';
        $strings['Reminders'] = 'Напоминания';
        $strings['SendReminder'] = 'Отправить напоминание';
        $strings['minutes'] = 'минуты';
        $strings['hours'] = 'часы';
        $strings['days'] = 'дни';
        $strings['ReminderBeforeStart'] = 'до начала';
        $strings['ReminderBeforeEnd'] = 'до конца';
        $strings['Logo'] = 'Логотип';
        $strings['CssFile'] = 'Файл CSS';
        $strings['ThemeUploadSuccess'] = 'Ваши изменения были сохранены. Обновите страницу, чтобы изменения вступили в силу.';
        $strings['MakeDefaultSchedule'] = 'Сделать моим планировщиком по умолчанию';
        $strings['DefaultScheduleSet'] = 'Это теперь ваш планировщик по умолчанию';
        $strings['FlipSchedule'] = 'Отразить разметку планировщика';
        $strings['Next'] = 'Следующий';
        $strings['Success'] = 'Успешно';
        $strings['Participant'] = 'Участник';
        $strings['ResourceFilter'] = 'Фильтр ресурсов';
        $strings['ResourceGroups'] = 'Группы помещений';
        $strings['AddNewGroup'] = 'Добавить новую группу';
        $strings['Quit'] = 'Выйти';
        $strings['AddGroup'] = 'Добавить группу';
        $strings['StandardScheduleDisplay'] = 'Используйте стандартный дисплей расписания';
        $strings['TallScheduleDisplay'] = 'Используйте высокий дисплей расписания';
        $strings['WideScheduleDisplay'] = 'Используйте широкий экран расписания';
        $strings['CondensedWeekScheduleDisplay'] = 'Используйте уплотненый дисплей расписания на неделю';
        $strings['ResourceGroupHelp1'] = 'Перетащите группы ресурсов для организации.';
        $strings['ResourceGroupHelp2'] = 'Щелкните правой кнопкой мыши имя группы ресурсов для дополнительных действий.';
        $strings['ResourceGroupHelp3'] = 'Перетащите ресурсы, чтобы добавить их к группам.';
        $strings['ResourceGroupWarning'] = 'При использовании групп ресурсов, каждый ресурс должен быть назначен, по меньшей мере, одну группу. Назначенные ресурсы не смогут быть защищены.';
        $strings['ResourceType'] = 'Тип помещения';
        $strings['AppliesTo'] = 'Относится к';
        $strings['UniquePerInstance'] = 'Уникальный для каждого экземпляра';
        $strings['AddResourceType'] = 'Добавить тип помещения';
        $strings['NoResourceTypeLabel'] = '(нет никакого установленного типа помещения)';
        $strings['ClearFilter'] = 'Сбросить фильтр';
        $strings['MinimumCapacity'] = 'Минимальное значение';
        $strings['Color'] = 'Цвет';
        $strings['Available'] = 'Доступен';
        $strings['Unavailable'] = 'Недоступен';
        $strings['Hidden'] = 'Скрыт';
        $strings['ResourceStatus'] = 'Состояние пормещения';
        $strings['CurrentStatus'] = 'Текущее состояние';
        $strings['AllReservationResources'] = 'Все помещения бронирования';
        $strings['File'] = 'Файл';
        $strings['BulkResourceUpdate'] = 'Массовое обновление помещений';
        $strings['Unchanged'] = 'без изменений';
        $strings['Common'] = 'Общий';
        $strings['AdminOnly'] = 'Только администратор';
        $strings['AdvancedFilter'] = 'Расширенный фильтр';
        $strings['MinimumQuantity'] = 'Минимальное количество';
        $strings['MaximumQuantity'] = 'Максимальное количество';
        $strings['ChangeLanguage'] = 'Изменить язык';
        $strings['AddRule'] = 'Добавить правило';
        $strings['Attribute'] = 'Атрибут';
        $strings['RequiredValue'] = 'Требуемое значение';
        $strings['ReservationCustomRuleAdd'] = 'Если %s , тогда цвет бронирования будет';
        $strings['AddReservationColorRule'] = 'Добавить правило окраса бронирования';
        $strings['LimitAttributeScope'] = 'Сбор по конкретным случаям';
        $strings['CollectFor'] = 'Собрать для';
        $strings['SignIn'] = 'Войти в систему';
        $strings['SignInWith'] = 'Войти через';
        $strings['AllParticipants'] = 'Все участники';
        $strings['RegisterANewAccount'] = 'Регистрация новой учетной записи';
        $strings['Dates'] = 'Даты';
        $strings['More'] = 'Больше';
        $strings['ResourceAvailability'] = 'Доступные помещения';
        $strings['UnavailableAllDay'] = 'В течение всего дня';
        $strings['AvailableUntil'] = 'Доступно до';
        $strings['AvailableBeginningAt'] = 'Доступно начиная с';
        $strings['AvailableAt'] = 'Доступно с';
        $strings['AllResourceTypes'] = 'Все типы помещений';
        $strings['AllResourceStatuses'] = 'Все статусы помещений';
        $strings['AllowParticipantsToJoin'] = 'Разрешить участникам присоединяться';
        $strings['Join'] = 'Присоединиться';
        $strings['YouAreAParticipant'] = 'Вы участник этого бронирования';
        $strings['YouAreInvited'] = 'Вы приглашены на это мероприятие';
        $strings['YouCanJoinThisReservation'] = 'Вы можете присоединиться к этому мероприятию';

        $strings['JoinThisReservation'] = 'Присоединиться к этому мероприятию';
        $strings['Import'] = 'Импорт';
        $strings['GetTemplate'] = 'Получить шаблон';
        $strings['UserImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Имя пользователя и адрес электронной почты являются обязательными.</li><li>Атрибут периода действия не будет применяться.</li><li>Leaving other fields blank will set default values and \'password\' as the user\'s password.</li><li>Use the supplied template as an example.</li></ul> Файл должен быть в формате CSV. !!!Имя пользователя и адрес электронной почты, обязательны для заполнения. Оставив другие поля пустыми будут установлены значения по умолчанию и \'password\' в качестве пароля пользователя. Используйте прилагаемый шаблон в качестве примера.';

        $strings['RowsImported'] = 'Строки импортированы';
        $strings['RowsSkipped'] = 'Строки пропущенны';
        $strings['Columns'] = 'Столбцы';
        $strings['Reserve'] = 'Бронировать';
        $strings['AllDay'] = 'Весь день';
        $strings['Everyday'] = 'Каждый день';
        $strings['IncludingCompletedReservations'] = 'Включая завершенные бронирования';
        $strings['NotCountingCompletedReservations'] = 'Не считая завершенные бронирования';
        $strings['RetrySkipConflicts'] = 'Пропустить противоречивые бронирования';
        $strings['Retry'] = 'Повторить';
        $strings['RemoveExistingPermissions'] = 'Удалить существующие разрешения?';
        $strings['Continue'] = 'Продолжить';
        $strings['WeNeedYourEmailAddress'] = 'Нам нужен ваш адрес электронной почты для бронирования.';
        $strings['ResourceColor'] = 'Цвет помещения';
        $strings['DateTime'] = 'Дата и время';
        $strings['AutoReleaseNotification'] = 'Автоматически освобождается, если не подтвержден в течение %s минут';
        $strings['RequiresCheckInNotification'] = 'Требуется регистрация in/out';
        $strings['NoCheckInRequiredNotification'] = 'Не требует проверки in/out';
        $strings['RequiresApproval'] = 'Требуется одобрение';
        $strings['CheckingIn'] = 'Записывание';
        $strings['CheckingOut'] = 'Выписывание';
        $strings['CheckIn'] = 'Записаться';
        $strings['CheckOut'] = 'Выписаться';
        $strings['ReleasedIn'] = 'Выпущено в';
        $strings['CheckedInSuccess'] = 'Вы записались';
        $strings['CheckedOutSuccess'] = 'Вы выписались';
        $strings['CheckInFailed'] = 'Вы не можете записаться';
        $strings['CheckOutFailed'] = 'Вы не можете быть выписаны';
        $strings['CheckInTime'] = 'Время записи';
        $strings['CheckOutTime'] = 'Время выписки';
        $strings['OriginalEndDate'] = 'Исходное окончание';
        $strings['SpecificDates'] = 'Показать конкретные даты';
        $strings['Users'] = 'Пользователи';
        $strings['Guest'] = 'Гость';
        $strings['ResourceDisplayPrompt'] = 'Помещения для отображения';
        $strings['Credits'] = 'Кредиты';
        $strings['AvailableCredits'] = 'Доступные кредиты';
        $strings['CreditUsagePerSlot'] = 'Требуется %s кредитов за слот (от максимума)';
        $strings['PeakCreditUsagePerSlot'] = 'Требуется %s кредитов на слот (максимум)';
        $strings['CreditsRule'] = 'У вас недостаточно кредитов. Необходимые кредиты: %s. Кредиты на счете: %s';
        $strings['PeakTimes'] = 'Часы пик';
        $strings['AllYear'] = 'Весь год';
        $strings['MoreOptions'] = 'Больше вариантов';
        $strings['SendAsEmail'] = 'Отправить на Email';
        $strings['UsersInGroups'] = 'Пользователи в группах';
        $strings['UsersWithAccessToResources'] = 'Пользователи с доступом к помещениям';
        $strings['AnnouncementSubject'] = 'Новое объявление было опубликовано %s';
        $strings['AnnouncementEmailNotice'] = 'пользователям будет отослано обяъвление по электронной почте';
        $strings['Day'] = 'День';
        $strings['NotifyWhenAvailable'] = 'Уведомить меня, если есть';
        $strings['AddingToWaitlist'] = 'Добавление вас в список ожидания';
        $strings['WaitlistRequestAdded'] = 'Вы получите уведомление, если это время станет доступным';
        $strings['PrintQRCode'] = 'Печать QR-кода';
        $strings['FindATime'] = 'Найти время';
        $strings['AnyResource'] = 'Любое помещение';
        $strings['ThisWeek'] = 'Эта неделя';
        $strings['Hours'] = 'Часы';
        $strings['Minutes'] = 'Минуты';
        $strings['ImportICS'] = 'Импорт файла ICS';
        $strings['ImportQuartzy'] = 'Импорт файла Quartzy';
        $strings['IncludeDeleted'] = 'Включить удаленные';
        $strings['Deleted'] = 'Удалённые';
        $strings['OnlyIcs'] = 'Только * .ics файлы могут быть загружены.';
        $strings['IcsLocationsAsResources'] = 'Места будут импортированы в качестве ресурсов.';
        $strings['IcsMissingOrganizer'] = 'Любому событию, с отсутствующим организатором, организатором будет назначен текущий пользователь.';
        $strings['IcsWarning'] = 'Правила бронирования не будут применяться - возможны конфликты, дубликаты и т. д.';
        $strings['BlackoutAroundConflicts'] = 'Блокировка из-за противоречивых бронирований';
        $strings['DuplicateReservation'] = 'Дублировать';
        $strings['UnavailableNow'] = 'Недоступно сейчас';
        $strings['ReserveLater'] = 'Зарезервировать позже';
        $strings['CollectedFor'] = 'Собрано для';
        $strings['IncludeDeleted'] = 'Включая удаленные бронирования';
        $strings['Deleted'] = 'Удалено';
        $strings['Back'] = 'Назад';
        $strings['Forward'] = 'Вперед';
        $strings['DateRange'] = 'Диапазон дат';
        $strings['Copy'] = 'Копировать';
        $strings['Detect'] = 'Обнаружение';
        $strings['Autofill'] = 'Автозаполнение';
        $strings['NameOrEmail'] = 'имя или email';
        $strings['ImportResources'] = 'Импорт помещений';
        $strings['ExportResources'] = 'Экспорт помещений';
        $strings['ResourceImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Укажите имя. Если оставить остальные поля пустыми, будут установлены значения по умолчанию.</li><li>Возможные значения: \'Доступен\', \'Недоступен\' and \'Скрыт\'.</li><li>Цвет должен быть шестнадцатеричным значением. ex) #ffffff.</li><li>Столбцы автоматического назначения и утверждения могут быть истинными или ложными.</li><li>Атрибут периода действия не будет применяться.</li><li>Запятые разделяют несколько групп ресурсов.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Эл.Почта, имена помещений, начало и конец - обязательные поля.</li><li>Для начала и конца требуется полное время. Рекомендуемый формат: YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Правила, конфликты и действительные временные интервалы не будут проверяться.</li><li>Уведомления не будут отправляться.</li><li>трибут периода действия не будет применяться.</li><li>Запятые разделяют имена нескольких ресурсов.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Минуты автозагрузки';
        $strings['CreditsPeak'] = 'Кредиты (максимальный)';
        $strings['CreditsOffPeak'] = 'Кредиты (вне пика)';
        $strings['ResourceMinLengthCsv'] = 'Минимальная длина бронирования';
        $strings['ResourceMaxLengthCsv'] = 'Максимальная длина бронирования';
        $strings['ResourceBufferTimeCsv'] = 'Буферное время';
        $strings['ResourceMinNoticeAddCsv'] = 'Минимальное уведомление для создания бронирования';
        $strings['ResourceMinNoticeUpdateCsv'] = 'Минимальное уведомление для изменения бронирования';
        $strings['ResourceMinNoticeDeleteCsv'] = 'Минимальное уведомление для удаления бронирования';
        $strings['ResourceMinNoticeCsv'] = 'Reservation Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Максимальный срок окончания бронирования';
        $strings['Export'] = 'Экспорт';
        $strings['DeleteMultipleUserWarning'] = 'При удалении этих пользователей будут удалены все их текущие, будущие и исторические бронирования. Никакие электронные письма не будут отправлены.';
        $strings['DeleteMultipleReservationsWarning'] = 'Никакие электронные письма не будут отправлены.';
        $strings['ErrorMovingReservation'] = 'Ошибка при перемещении бронирования';
        $strings['SelectUser'] = 'Выберите пользователя';
        $strings['InviteUsers'] = 'Пригласить пользователей';
        $strings['InviteUsersLabel'] = 'Введите адреса электронной почты приглашенных лиц.';
        $strings['ApplyToCurrentUsers'] = 'Применить к текущим пользователям';
        $strings['ReasonText'] = 'Текст причины';
        $strings['NoAvailableMatchingTimes'] = 'Нет свободного времени, подходящего под ваш запрос';
        $strings['Schedules'] = 'Расписания';
        $strings['NotifyUser'] = 'Уведомить пользователя';
        $strings['UpdateUsersOnImport'] = 'Обновлять существующего пользователя, если адрес почты уже есть';
        $strings['UpdateResourcesOnImport'] = 'Обновлять существующие помещения, если название уже есть';
        $strings['Reject'] = 'Отклонить';
        $strings['CheckingAvailability'] = 'Проверка доступности';
        $strings['CreditPurchaseNotEnabled'] = 'Покупка кредитов не включена';
        $strings['CreditsEachCost1'] = 'Каждый';
        $strings['CreditsEachCost2'] = 'кредит(ов) стоит';
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
        $strings['CreditsUpdated'] = 'Стоимость в кредитах обновлена';
        $strings['GatewaysUpdated'] = 'Платёжные шлюзы обновлены';
        $strings['PurchaseSummary'] = 'Сводка по покупке';
        $strings['EachCreditCosts'] = 'Каждый кредит стоит';
        $strings['Checkout'] = 'Оформление заказа';
        $strings['Quantity'] = 'Количество';
        $strings['CreditPurchase'] = 'Покупка кредитов';
        $strings['EmptyCart'] = 'Ваша корзина пуста.';
        $strings['BuyCredits'] = 'Купить кредиты';
        $strings['CreditsPurchased'] = 'кредитов куплено.';
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
        $strings['SlackNotFound'] = 'Помещение с таким названием не найдено. Нажмите «Забронировать», чтобы создать новое бронирование.';
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
        $strings['AvailableBetween'] = 'Доступно в интервале';
        $strings['ConcurrentYes'] = 'Помещения можно бронировать более чем одному человеку одновременно';
        $strings['ConcurrentNo'] = 'Помещения нельзя бронировать более чем одному человеку одновременно';
        $strings['ScheduleAvailabilityEarly'] = ' Это расписание пока недоступно. Оно доступно';
        $strings['ScheduleAvailabilityLate'] = 'Это расписание больше недоступно. Оно было доступно';
        $strings['ResourceImages'] = 'Изображения помещения';
        $strings['FullAccess'] = 'Полный доступ';
        $strings['ViewOnly'] = 'Только просмотр';
        $strings['Purge'] = 'Очистить';
        $strings['UsersWillBeDeleted'] = 'пользователей будет удалено';
        $strings['BlackoutsWillBeDeleted'] = 'периодов недоступности будет удалено';
        $strings['ReservationsWillBePurged'] = 'бронирований будет безвозвратно очищено';
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
        $strings['BulkResourceDelete'] = 'Массовое удаление помещений';
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
        $strings['Custom'] = 'Пользовательский';
        $strings['AddDate'] = 'Добавить дату';
        $strings['RepeatOn'] = 'Повторять в';
        $strings['ScheduleConcurrentMaximum'] = 'Одновременно можно забронировать не более <b>%s</b> помещений';
        $strings['ScheduleConcurrentMaximumNone'] = 'Число одновременно забронированных помещений не ограничено';
        $strings['ScheduleMaximumConcurrent'] = 'Максимальное число одновременно забронированных помещений';
        $strings['ScheduleMaximumConcurrentNote'] = 'Если задано, общее число помещений, которые можно забронировать одновременно в этом расписании, будет ограничено.';
        $strings['ScheduleResourcesPerReservationMaximum'] = 'Каждое бронирование ограничено максимум <b>%s</b> помещениями';
        $strings['ScheduleResourcesPerReservationNone'] = 'Число помещений в одном бронировании не ограничено';
        $strings['ScheduleResourcesPerReservation'] = 'Максимальное число помещений в одном бронировании';
        $strings['ResourceConcurrentReservations'] = 'Разрешить %s одновременных бронирований';
        $strings['ResourceConcurrentReservationsNone'] = 'Не разрешать одновременные бронирования';
        $strings['AllowConcurrentReservations'] = 'Разрешить одновременные бронирования';
        $strings['ResourceDisplayInstructions'] = 'Помещение не выбрано. Ссылку для отображения помещения можно найти в разделе «Управление приложением», «Помещения». Помещение должно быть общедоступным.';
        $strings['Owner'] = 'Владелец';
        $strings['MaximumConcurrentReservations'] = 'Максимум одновременных бронирований';
        $strings['NotifyUsers'] = 'Уведомить пользователей';
        $strings['Message'] = 'Сообщение';
        $strings['AllUsersWhoHaveAReservationInTheNext'] = 'Все, у кого есть бронирование в течение следующих';
        $strings['ChangeResourceStatus'] = 'Изменить статус помещения';
        $strings['UpdateGroupsOnImport'] = 'Обновлять существующую группу при совпадении названия';
        $strings['GroupsImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Название обязательно.</li><li>Списки участников — адреса почты через запятую.</li><li>Пустой список участников при обновлении группы оставит её состав без изменений.</li><li>Списки прав — названия помещений через запятую.</li><li>Пустой список прав при обновлении группы оставит права без изменений.</li><li>За образец возьмите приложенный шаблон.</li></ul>';
        $strings['PhoneRequired'] = 'Укажите телефон';
        $strings['OrganizationRequired'] = 'Укажите организацию';
        $strings['PositionRequired'] = 'Укажите должность';
        $strings['GroupMembership'] = 'Членство в группах';
        $strings['AvailableGroups'] = 'Доступные группы';
        $strings['CheckingAvailabilityError'] = 'Не удалось получить доступность помещений — их слишком много';
        $strings['ScanToSchedule'] = 'Отсканируйте, чтобы открыть расписание';
        $strings['MaintenanceNotice'] = 'Сейчас проводится обслуживание. Скоро вернёмся.';
        $strings['MoreResourceActions'] = 'Другие действия с помещением';
        $strings['IcsMissingOrganizer'] = 'Любое событие отсутствует организатор будет иметь владельца, установленный для текущего пользователя.';
        $strings['IcsWarning'] = 'Правила бронирования не будут применяться - конфликты, дубликатами и т.д. возможны';
        // End Strings

        // Install
        $strings['InstallApplication'] = 'Установка LibreBooking (только MySQL)';
        $strings['IncorrectInstallPassword'] = 'К сожалению, введен неверный пароль.';
        $strings['SetInstallPassword'] = 'Вы должны задать пароль установки прежде чем, установка будет продолжена';
        $strings['InstallPasswordInstructions'] = 'В %s задайте %s пароль, который является случайным и трудно угадываемый, а затем вернитесь на эту страницу.<br/>Вы можете использовать %s';
        $strings['NoUpgradeNeeded'] = 'Нет необходимости обновления. Запуск процесса установки удалит все существующие данные и установить новую копию LibreBooking!';
        $strings['ProvideInstallPassword'] = 'Введите пароль для установки.';
        $strings['InstallPasswordLocation'] = 'Это можно найти на %s в %s.';
        $strings['VerifyInstallSettings'] = 'Проверьте следующие параметры по умолчанию, прежде чем продолжить. Или вы можете изменить их в %s.';
        $strings['DatabaseName'] = 'Имя базы данных';
        $strings['DatabaseUser'] = 'Пользователь базы данных';
        $strings['DatabaseHost'] = 'Host Базы данных';
        $strings['DatabaseCredentials'] = 'Вы должны предоставить учетные данные пользователя MySQL, который имеет привилегии для создания баз данных. Если вы не знаете, обратитесь к администратору базы данных. Во многих случаях, root будет работать.';
        $strings['MySQLUser'] = 'Пользователь MySQL';
        $strings['InstallOptionsWarning'] = 'Следующие варианты, вероятно не будут,  работать в среде хостинга. Если вы устанавливаете на хост, то используйте мастер установки MySQL для выполнения этих шагов.';
        $strings['CreateDatabase'] = 'Создайте базу данных';
        $strings['CreateDatabaseUser'] = 'Создание пользователя базы данных';
        $strings['PopulateExampleData'] = 'Импорт выборки данных. Дата создания учетной записи администратора: admin/password и пользователя: user/password';
        $strings['PopulateLargeExampleData'] = 'Также импортировать большой набор образцов данных: добавляет пользователей, помещения, группы и бронирования для реалистичного тестирования';
        $strings['DataWipeWarning'] = 'Внимание: Это удалит все существующие данные';
        $strings['RunInstallation'] = 'Запуск установки';
        $strings['UpgradeNotice'] = 'Вы обновляете версию <b>%s</b> до версии <b>%s</b>';
        $strings['RunUpgrade'] = 'Запуск обновления';
        $strings['Executing'] = 'Выполнение';
        $strings['StatementFailed'] = 'Не удалось. Детали:';
        $strings['SQLStatement'] = 'SQL-запрос:';
        $strings['ErrorCode'] = 'Код ошибки:';
        $strings['ErrorText'] = 'Текст ошибки:';
        $strings['InstallationSuccess'] = 'Установка успешно завершена!';
        $strings['RegisterAdminUser'] = 'Зарегистрируйте вашего пользователя с правами администратора. Это необходимо, если вы не импортировали данные из образца. Проверьте, что $conf[\'settings\'][\'allow.self.registration\'] = \'true\' в вашем файле %s.';
        $strings['LoginWithSampleAccounts'] = 'Если вы импортировали данные примера, вы можете войти с admin/password для администратора или user/password для простого пользователя.';
        $strings['InstalledVersion'] = 'Сейчас у вас запущена версия %s LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Рекомендуется обновить конфигурационный файл';
        $strings['InstallationFailure'] = 'Были проблемы с установкой. Пожалуйста, исправьте их и повторите установку.';
        $strings['ConfigureApplication'] = 'Настроить LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Ваш конфигурационный файл теперь обновлен!';
        $strings['ConfigUpdateFailure'] = 'Мы не могли автоматически обновлять свой конфигурационный файл. Пожалуйста, перезаписать содержимое config.php со следующими требованиями:';
        $strings['ScriptUrlWarning'] = 'Похоже, значение <em>script.url</em> указано неверно. Сейчас это <strong>%s</strong>, а должно быть <strong>%s</strong>';
        $strings['SelectUser'] = 'Выбор пользователя';
        // End Install

        // Errors
        $strings['LoginError'] = 'Нет соответствующего имени пользователя или пароль';
        $strings['LdapConnectionErrorMessage'] = 'Не удалось подключиться к серверу LDAP. Обратитесь к администратору.';
        $strings['LdapDependencyMissingMessage'] = 'Аутентификация LDAP недоступна: отсутствует pear/net_ldap2. Установите его командой composer require pear/net_ldap2';
        $strings['ReservationFailed'] = 'Процедура оформления бронирования не может быть создана';
        $strings['MinNoticeError'] = 'Данное бронирование требует предварительного уведомления. Самая ранняя дата и время, которое можно наблюдать в %s.';
        $strings['MinNoticeErrorUpdate'] = 'Изменение этого бронирования требует заблаговременного уведомления. Бронирования, начинающиеся ранее %s, изменять нельзя.';
        $strings['MinNoticeErrorDelete'] = 'Удаление этого бронирования требует заблаговременного уведомления. Бронирования, начинающиеся ранее %s, удалять нельзя.';
        $strings['MaxNoticeError'] = 'Данное бронирование не может быть далеко в будущем. Последняя дата и время, которое можно наблюдать в %s.';
        $strings['MinDurationError'] = 'Данное бронирование должна быть не менее %s.';
        $strings['MaxDurationError'] = 'Данное бронирование не может длиться дольше, чем %s.';
        $strings['ConflictingAccessoryDates'] = 'Недостаточно следующего оборудования:';
        $strings['NoResourcePermission'] = 'У вас нет разрешения на доступ к одному или нескольким из требуемых помещений.';
        $strings['ConflictingReservationDates'] = 'Существуют противоречивые бронирования в следующие даты:';
        $strings['InstancesOverlapRule'] = 'Некоторые бронирования из серии пересекаются:';
        $strings['StartDateBeforeEndDateRule'] = 'Дата и время начала должно быть до даты и времени окончания.';
        $strings['RecurringWithoutTerminationRule'] = 'Для повторяющихся блокировок требуется дата окончания.';
        $strings['StartIsInPast'] = 'Дата и время начала не может быть в прошлом.';
        $strings['EmailDisabled'] = 'Администратор отключил уведомления по электронной почте.';
        $strings['ValidLayoutRequired'] = 'Слоты должны быть обеспечены для всех 24 часов дня начиная и заканчивая в 12:00 AM.';
        $strings['CustomAttributeErrors'] = 'Есть проблемы, связанные с дополнительными атрибутами вы обеспечили:';
        $strings['CustomAttributeRequired'] = '%s поле является обязательным.';
        $strings['CustomAttributeInvalid'] = 'Предусмотренное значение %s является недействительным.';
        $strings['AttachmentLoadingError'] = 'К сожалению, произошла ошибка при загрузке требуемого файла.';
        $strings['InvalidAttachmentExtension'] = 'Вы можете загрузить только файлы типа: %s';
        $strings['InvalidStartSlot'] = 'Запршенное дата и время начала не является действительным.';
        $strings['InvalidEndSlot'] = 'Запршенное дата и время окончания не является действительным.';
        $strings['MaxParticipantsError'] = '%s может поддерживать только %s участников.';
        $strings['ReservationCriticalError'] = 'Был критическая ошибка при сохранении вашей брони. Если это будет продолжаться, обратитесь к системному администратору.';
        $strings['InvalidStartReminderTime'] = 'Время начала напоминания не является действительным.';
        $strings['InvalidEndReminderTime'] = 'Время окончания напоминания не является действительным.';
        $strings['QuotaExceeded'] = 'Предел квоты превышены.';
        $strings['MultiDayRule'] = '%s не допускает бронирования, переходящие на другой день.';
        $strings['InvalidReservationData'] = 'Возникли проблемы с вашим запросом на бронирование.';
        $strings['PasswordError'] = 'Пароль должен содержать по меньшей мере %s букв, %s чисел и %s специальных символов.';
        $strings['PasswordErrorRequirements'] = 'Пароль должен содержать комбинацию, по меньшей мере, %s верхние и строчные буквы, %s чисел и %s специальных символов.';
        $strings['NoReservationAccess'] = 'Вы не можете изменить это бронирование.';
        $strings['PasswordControlledExternallyError'] = 'Ваш пароль контролируется внешней системой и не может быть обновлен здесь.';
        $strings['AccessoryResourceRequiredErrorMessage'] = 'Доп. оборудование %s можно заказать только с помещениями %s';
        $strings['AccessoryMinQuantityErrorMessage'] = 'Вы должны заказать %s доп.оборудование %s';
        $strings['AccessoryMaxQuantityErrorMessage'] = 'Вы не можете заказать больше %s доп.оборудование %s';
        $strings['AccessoryResourceAssociationErrorMessage'] = 'доп.оборудование \'%s\' не могут быть забронированы с запрошенными помещениями';

        $strings['PasswordControlledExternallyError'] = 'Ваш пароль управляется внешней системой и не могут быть обновлены здесь.';
        $strings['NoResources'] = 'Вы не добавили источники.';
        $strings['ParticipationNotAllowed'] = 'Вы не можете присоединиться к этому бронированию.';
        $strings['ReservationCannotBeCheckedInTo'] = 'Это резервирование невозможно проверить в.';
        $strings['ReservationCannotBeCheckedOutFrom'] = 'Эту бронь нельзя оформить.';
        $strings['InvalidEmailDomain'] = 'Этот адрес электронной почты не из разрешенного домена';
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
        $strings['InvalidNumberOfResourcesError'] = 'Максимальное число помещений в одном бронировании — %s';
        $strings['ScheduleTotalReservationsError'] = 'В этом расписании одновременно можно забронировать только %s помещений. Это бронирование нарушит ограничение в следующие даты:';
        $strings['SelfRegistrationDisabled'] = 'Пользователь не зарегистрирован, а саморегистрация отключена. Обратитесь к администратору, чтобы создать учётную запись.';
        $strings['InsecureRequestError'] = 'Небезопасной запрос. Если вы будете продолжать видеть эту ошибку, пожалуйста, снова войти в систему и повторите запрос.';
        $strings['RemoveExistingPermissions'] = 'Удалить существующие разрешения?';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Создать бронирование';
        $strings['EditReservation'] = 'Изменить бронирование';
        $strings['LogIn'] = 'Вход';
        $strings['ManageReservations'] = 'Бронирования';
        $strings['AwaitingActivation'] = 'Ожидает активации';
        $strings['PendingApproval'] = 'В ожидании одобрения';
        $strings['ManageSchedules'] = 'Расписания';
        $strings['ManageResources'] = 'Помещения';
        $strings['ManageAccessories'] = 'Оборудование';
        $strings['ManageUsers'] = 'Пользователи';
        $strings['ManageGroups'] = 'Группы';
        $strings['ManageQuotas'] = 'Квоты';
        $strings['ManageBlackouts'] = 'Прошедшие мероприятия';
        $strings['MyDashboard'] = 'Моя панель';
        $strings['ServerSettings'] = 'Настройки сервера';
        $strings['Dashboard'] = 'Панель';
        $strings['Help'] = 'Помощь';
        $strings['Administration'] = 'Администрирование';
        $strings['About'] = 'О программе';
        $strings['Bookings'] = 'Бронированные';
        $strings['Schedule'] = 'Расписание';
        $strings['Reservations'] = 'Резервирование';
        $strings['Account'] = 'Аккаунт';
        $strings['EditProfile'] = 'Изменить мой профиль';
        $strings['FindAnOpening'] = 'Найти свободное время';
        $strings['OpenInvitations'] = 'Открыть приглашения';
        $strings['MyCalendar'] = 'Мой календарь';
        $strings['ResourceCalendar'] = 'Календарь по помещениям';
        $strings['Reservation'] = 'Новое резервирование';
        $strings['Install'] = 'Установка';
        $strings['ChangePassword'] = 'Изменить пароль';
        $strings['MyAccount'] = 'Мой аккаунт';
        $strings['Profile'] = 'Профиль';
        $strings['ApplicationManagement'] = 'Управление приложением';
        $strings['ForgotPassword'] = 'Забыли пароль';
        $strings['NotificationPreferences'] = 'Настройки уведомлений';
        $strings['ManageAnnouncements'] = 'Объявления';
        $strings['Responsibilities'] = 'Обязанности';
        $strings['GroupReservations'] = 'Бронирования группы';
        $strings['ResourceReservations'] = 'Помещения бронирования';
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
        $strings['LookAndFeel'] = 'Смотри и чувствуй';
        $strings['ManageResourceGroups'] = 'Группы помещений';
        $strings['ManageResourceTypes'] = 'Типы помещений';
        $strings['ManageResourceStatus'] = 'Статусы помещений';
        $strings['ReservationColors'] = 'Цвет бронирования';
        $strings['SearchReservations'] = 'Поиск бронирований';
        $strings['ManagePayments'] = 'Платежи';
        $strings['ViewCalendar'] = 'Посмотреть календарь';
        $strings['DataCleanup'] = 'Очистка данных';
        $strings['ManageEmailTemplates'] = 'Шаблоны писем';
        $strings['CheckResources'] = 'Проверить помещения';
        $strings['CheckSchedules'] = 'Проверить расписания';
        // End Page Titles

        // Day representations
        $strings['DaySundaySingle'] = 'В';
        $strings['DayMondaySingle'] = 'П';
        $strings['DayTuesdaySingle'] = 'В';
        $strings['DayWednesdaySingle'] = 'С';
        $strings['DayThursdaySingle'] = 'Ч';
        $strings['DayFridaySingle'] = 'П';
        $strings['DaySaturdaySingle'] = 'С';

        $strings['DaySundayAbbr'] = 'Вск';
        $strings['DayMondayAbbr'] = 'Пнд';
        $strings['DayTuesdayAbbr'] = 'Втр';
        $strings['DayWednesdayAbbr'] = 'Срд';
        $strings['DayThursdayAbbr'] = 'Чтв';
        $strings['DayFridayAbbr'] = 'Пят';
        $strings['DaySaturdayAbbr'] = 'Суб';
        // End Day representations

        // Email Subjects
        $strings['ReservationApprovedSubject'] = 'Ваше бронирование было одобрено';
        $strings['ReservationCreatedSubject'] = 'Ваше бронирование было создано';
        $strings['ReservationUpdatedSubject'] = 'Ваше бронирование было обновлено';
        $strings['ReservationDeletedSubject'] = 'Ваше бронирование было удалено';
        $strings['ReservationCreatedAdminSubject'] = 'Уведомление: Бронирование было создано';
        $strings['ReservationUpdatedAdminSubject'] = 'Уведомление: бронирование было обновлено';
        $strings['ReservationDeleteAdminSubject'] = 'Уведомление: бронирование было удалено';
        $strings['ReservationApprovalAdminSubject'] = 'Уведомление: Бронирование требует одобрения';
        $strings['ParticipantAddedSubject'] = 'Уведомление об участии в бронировании';
        $strings['ParticipantDeletedSubject'] = 'Бронирование удалено';
        $strings['InviteeAddedSubject'] = 'Приглашение на мероприятие';
        $strings['ResetPasswordRequest'] = 'Password Reset Request';

        $strings['ResetPassword'] = 'Запрос на сброс пароля';
        $strings['ActivateYourAccount'] = 'Актирируйте свой аккаунт';
        $strings['ReportSubject'] = 'Ваш запрошенный отёт (%s)';
        $strings['ReservationStartingSoonSubject'] = 'Мероприятие %s скоро начнется';
        $strings['ReservationEndingSoonSubject'] = 'Мероприятие %s скоро закончится';
        $strings['UserAdded'] = 'Добавлен новый пользователь';
        $strings['GuestAccountCreatedSubject'] = 'Информация о вашем аккаунте';
        $strings['AccountCreatedSubject'] = 'Данные вашей учётной записи %s';
        $strings['InviteUserSubject'] = '%s приглашает вас присоединиться к %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Бронирование было одобрено для %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Бронирование создано для %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Бронирование обновлено для %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Бронирование удалено для %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Уведомление: Бронирование создано для %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Уведомление: Бронирование обновлено для %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Уведомление: бронирование удалено для %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Уведомление: резервирование для %s требует вашего утверждения';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Добавлен в бронирование для %s';
        $strings['ParticipantUpdatedSubjectWithResource'] = '%s изменил бронирование для %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s Удалено бронирование для %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s Пригласил вас на мнроприятие для %s';
        $strings['MissedCheckinEmailSubject'] = 'Не прошли регистрацию %s';
        $strings['ReservationShareSubject'] = '%s поделился бронированием %s';
        $strings['ReservationSeriesEndingSubject'] = 'Серия бронирований %s заканчивается %s';
        $strings['ReservationParticipantAccept'] = '%s принял ваше приглашение к бронированию %s на %s';
        $strings['ReservationParticipantDecline'] = '%s отклонил ваше приглашение к бронированию %s на %s';
        $strings['ReservationParticipantJoin'] = '%s присоединился к вашему бронированию %s на %s';
        $strings['ReservationAvailableSubject'] = '%s свободно %s';
        $strings['ResourceStatusChangedSubject'] = 'Доступность %s изменилась';

        $strings['UserDeleted'] = 'Учётная запись %s удалена пользователем %s';
        $strings['AnnouncementSubject'] = 'Новое объявление было опубликовано %s';
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
        $strings['NoGroupUpcomingReservations'] = 'Ваша группа не имеет предстоящих бронирований';
        $strings['GroupUpcomingReservations'] = 'Предстоящие бронирования моей группы(ов)';
        //End Group Upcoming Reservations

        //Facebook Login SDK Error
        $strings['FacebookLoginErrorMessage'] = 'Произошла ошибка при входе через Facebook. Пожалуйста, попробуйте еще раз.';
        //End Facebook Login SDK Error

        //Pending Approval Reservations in Dashboard
        $strings['NoPendingApprovalReservations'] = 'У вас нет резерваций, ожидающих утверждения';
        $strings['PendingApprovalReservations'] = 'Резервации ожидают утверждения';
        $strings['LaterThisMonth'] = 'Позже в этом месяце';
        $strings['LaterThisYear'] = 'Позже в этом году';
        $strings['Other'] = 'Другое';
        $strings['Remaining'] = 'Осталось';
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Отсутствуют пропущенные резервации на выезд';
        $strings['MissingCheckOutReservations'] = 'Пропущенные резервации на выезд';
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Невозможно просмотреть детали бронирования, потому что у вас нет разрешений на ни один из ресурсов в этом бронировании';
        $strings['Check'] = 'Проверить';
        $strings['PermissionType'] = 'Тип прав';
        $strings['NoResourcesToView'] = 'Нет доступных помещений';
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
        $days['abbr'] = ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Птн', 'Суб'];
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
        $this->Letters = ['А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я'];

        return $this->Letters;
    }

    protected function _GetHtmlLangCode()
    {
        return 'ru';
    }
}
