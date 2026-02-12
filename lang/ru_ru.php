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
        $strings = [];

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
        $strings['CreateReservationHeading'] = 'Создать новое Бронирование';
        $strings['EditReservationHeading'] = 'Редактировать Бронирование %s';
        $strings['ViewReservationHeading'] = 'Посмотреть Бронирование %s';
        $strings['ReservationErrors'] = 'Изменить Бронирование';
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
        $strings['QuotaEnforcement'] = 'Enforced %s %s';
        $strings['reservations'] = 'бронирования';
        $strings['reservation'] = 'бронирование';
        $strings['ChangeCalendar'] = 'Сменить Календарь';
        $strings['AddQuota'] = 'Добавть Квоту';
        $strings['FindUser'] = 'Найти Пользователя';
        $strings['Created'] = 'Создан';
        $strings['LastModified'] = 'Последнее изменение';
        $strings['GroupName'] = 'Имя Группы';
        $strings['GroupMembers'] = 'Члены Группы';
        $strings['GroupRoles'] = 'Роли Группы';
        $strings['GroupAdmin'] = 'Администратор группы';
        $strings['Actions'] = 'Действия';
        $strings['CurrentPassword'] = 'Текущий пароль';
        $strings['NewPassword'] = 'Новый пароль';
        $strings['InvalidPassword'] = 'Неверный пароль';
        $strings['PasswordChangedSuccessfully'] = 'Ваш пароль был успешно изменен';
        $strings['SignedInAs'] = 'Вошли как';
        $strings['NotSignedIn'] = 'Вы не вошли в аккаунт';
        $strings['ReservationTitle'] = 'Название Бронирования';
        $strings['ReservationDescription'] = 'Описание Бронирования';
        $strings['ResourceList'] = 'Помещения зарезервированны';
        $strings['Accessories'] = 'Оборудование';
        $strings['ParticipantList'] = 'Участники';
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
        $strings['FindGroup'] = 'Найти Группу';
        $strings['Manage'] = 'Управлять';
        $strings['None'] = 'Никого';
        $strings['AddToOutlook'] = 'Добавить в календарь';
        $strings['Done'] = 'Готово';
        $strings['RememberMe'] = 'Напомнить мне';
        $strings['FirstTimeUser?'] = 'Вы у нас впервые?';
        $strings['CreateAnAccount'] = 'Создать аккаунт';
        $strings['ViewSchedule'] = 'Посмотреть Планировщик';
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
        $strings['UpdatingReservation'] = 'Обновление предварительного заказа';
        $strings['ChangeUser'] = 'Сменить пользователя';
        $strings['MoreResources'] = 'Больше Помещений';
        $strings['ReservationLength'] = 'Длительность Бронирования';
        $strings['ParticipantList'] = 'Список Участников';
        $strings['AddParticipants'] = 'Добавить участников';
        $strings['InviteOthers'] = 'Пригласить Других';
        $strings['AddResources'] = 'Добавить Помещения';
        $strings['AddAccessories'] = 'Добавить Оборудования';
        $strings['Accessory'] = 'Оборудование';
        $strings['QuantityRequested'] = 'Требуемое кол-во';
        $strings['CreatingReservation'] = 'Создание Бронироания';
        $strings['UpdatingReservation'] = 'Обновление Бронирования';
        $strings['DeleteWarning'] = 'Это действие является постоянным и бесповоротным!';
        $strings['DeleteAccessoryWarning'] = 'Удаляя это оборудование, будет удалено из всех бронирования и мероприятий';
        $strings['AddAccessory'] = 'Добавить Оборудование';
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
        $strings['BlackoutUpdated'] = 'прошедшее Обновлен';
        $strings['BlackoutNotUpdated'] = 'прошедшее не может быть обновлен';
        $strings['BlackoutConflicts'] = 'Есть конфлик в прошедшее по времени';
        $strings['ReservationConflicts'] = 'Есть конфликтующие время бронирование';
        $strings['UsersInGroup'] = 'Пользователи в этой группе';
        $strings['Browse'] = 'Просматреть';
        $strings['DeleteGroupWarning'] = 'Удаляя эту группу будут удалены все связанные с ней права доступа к ресурсам. Пользователи в этой группе могут потерять доступ к этим ресурсам.';
        $strings['WhatRolesApplyToThisGroup'] = 'Какие роли относятся к этой группе?';
        $strings['WhoCanManageThisGroup'] = 'Кто может управлять этой группой?';
        $strings['WhoCanManageThisSchedule'] = 'Кто может управлять Планировщиком?';
        $strings['AddGroup'] = 'Добавить группу';
        $strings['AllQuotas'] = 'Все Квоты';
        $strings['QuotaReminder'] = 'Помните: Квоты применяются на основе часового пояса планировщика.';
        $strings['AllReservations'] = 'Все Забронированные';
        $strings['PendingReservations'] = 'Ожидающие Бронирования';
        $strings['Approving'] = 'Утверждения';
        $strings['MoveToSchedule'] = 'Переместить в Планировщик';
        $strings['DeleteResourceWarning'] = 'Удаление этого ресурса будут удалены все связанные с ним данные, и связи с ним';
        $strings['DeleteResourceWarningReservations'] = 'Все прошлые, нынешние и будущие Бронирования, связанные с ним';
        $strings['DeleteResourceWarningPermissions'] = 'Все назначенные разрешение';
        $strings['DeleteResourceWarningReassign'] = 'Укажите какую-нибудь причину, что вы не хотите быть удалены, прежде чем продолжить';
        $strings['ScheduleLayout'] = 'Разметка (все время %s)';
        $strings['ReservableTimeSlots'] = 'Резервированные Временные Интервалы';
        $strings['BlockedTimeSlots'] = 'Блокированные временные интервалы';
        $strings['ThisIsTheDefaultSchedule'] = 'Это Планировщик по умолчанию';
        $strings['DefaultScheduleCannotBeDeleted'] = 'Планировщик по умолчанию не может быть удален';
        $strings['MakeDefault'] = 'Сделать по умолчанию';
        $strings['BringDown'] = 'Опустить вниз';
        $strings['ChangeLayout'] = 'Изменить Разметку';
        $strings['AddSchedule'] = 'Добавить Планировщика';
        $strings['StartsOn'] = 'Начинается';
        $strings['NumberOfDaysVisible'] = 'Число видимых дней';
        $strings['UseSameLayoutAs'] = 'Использовать некоторые планировки как';
        $strings['Format'] = 'Формат';
        $strings['OptionalLabel'] = 'Дополнительный ярлык';
        $strings['LayoutInstructions'] = 'Введите один слот в каждой строке. Слоты должны быть обеспечены для всех 24 часов дня начиная и заканчивая в 12:00 AM.';
        $strings['AddUser'] = 'Добавить Пользователя';
        $strings['UserPermissionInfo'] = 'Фактический доступ к ресурсу может отличаться в зависимости от роли пользователя, права доступа группы или внешние настройки разрешений';
        $strings['DeleteUserWarning'] = 'Удаление этого пользователя будут удалены все их текущих, будущих, и исторические Бронирование.';
        $strings['AddAnnouncement'] = 'Добавить Объявление';
        $strings['Announcement'] = 'Объявление';
        $strings['Priority'] = 'Приоритет';
        $strings['Reservable'] = 'Резервирование';
        $strings['Unreservable'] = 'Незарезервированные';
        $strings['Reserved'] = 'Зарезервированные';
        $strings['MyReservation'] = 'Мои Бронирования';
        $strings['Pending'] = 'В ожидании';
        $strings['Past'] = 'Прошло';
        $strings['Restricted'] = 'Ограниченные';
        $strings['ViewAll'] = 'Посмотреть все';
        $strings['MoveResourcesAndReservations'] = 'Переместить помещения и мероприятия в';
        $strings['TurnOffSubscription'] = 'Отключить календарные подписки';
        $strings['TurnOnSubscription'] = 'Разрешить подписки на этот календарь';
        $strings['SubscribeToCalendar'] = 'Подписаться на этот календарь';
        $strings['SubscriptionsAreDisabled'] = 'Администратор отключил подписки календаря';
        $strings['NoResourceAdministratorLabel'] = '(Нет Администратора помещения)';
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
        $strings['NoScheduleAdministratorLabel'] = 'Нет Администратора для Планировщика';
        $strings['ScheduleAdministrator'] = 'Администратор Планировщика';
        $strings['Total'] = 'Всего';
        $strings['QuantityReserved'] = 'Кол-во резервирований';
        $strings['AllAccessories'] = 'Всё Оборудование';
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
        $strings['AllTime'] = 'Все Время';
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
        $strings['ReservedAccessories'] = 'Зарезервированное Оборудование';
        $strings['ResourceUsageTimeBooked'] = 'Использование ресурсов - время бронирования';
        $strings['ResourceUsageReservationCount'] = 'Использование ресурсов - Бронирование ряд';
        $strings['Top20UsersTimeBooked'] = 'Топ-20 пользователей - По Времени бронирования';
        $strings['Top20UsersReservationCount'] = 'Топ-20 пользователей - По Количеству Бронирований';
        $strings['ConfigurationUpdated'] = 'Файл конфигурации был обновлен';
        $strings['ConfigurationUiNotEnabled'] = 'Эта страница не может быть доступна, потому что $conf[\'settings\'][\'pages\'][\'enable.configuration\'] установлен в положение ложно или отсутствует.';
        $strings['ConfigurationFileNotWritable'] = 'Конфигурационный файл не доступен для записи. Пожалуйста, проверьте разрешения этого файла и повторите попытку.';
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
        $strings['ReminderSendtimeDate'] = 'Дата Напоминание';
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
        $strings['Logo'] = 'Logo';
        $strings['CssFile'] = 'CSS File';
        $strings['ThemeUploadSuccess'] = 'Ваши изменения были сохранены. Обновите страницу, чтобы изменения вступили в силу.';
        $strings['MakeDefaultSchedule'] = 'Сделать моим Планировщиком по умолчанию';
        $strings['DefaultScheduleSet'] = 'Это теперь ваш Планировщик по умолчанию';
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
        $strings['AddResourceType'] = 'Добавить Тип помещения';
        $strings['NoResourceTypeLabel'] = '(нет никакого установленного типа помещения)';
        $strings['ClearFilter'] = 'Сбросить фильтр';
        $strings['MinimumCapacity'] = 'Минимальное значение';
        $strings['Color'] = 'Цвет';
        $strings['Available'] = 'Доступен';
        $strings['Unavailable'] = 'Недоступен';
        $strings['Hidden'] = 'Скрыт';
        $strings['ResourceStatus'] = 'Состояние пормещения';
        $strings['CurrentStatus'] = 'Текущее состояние';
        $strings['AllReservationResources'] = 'Все помещения Бронирования';
        $strings['File'] = 'Файл';
        $strings['BulkResourceUpdate'] = 'Массовое обновление Помещений';
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
        $strings['AdminOnlyHelp'] = 'Только администраторы могут просматривать и редактировать это значение атрибута';
        $strings['PrivateHelp'] = 'Это значение атрибута видно только пользователю, который владеет бронированием, и администраторам';
        $strings['LimitAttributeScopeHelp'] = 'Собирать этот атрибут только для конкретных объектов, а не для всех объектов этой категории';
        $strings['SignIn'] = 'Войти в систему';
        $strings['AllParticipants'] = 'Все участники';
        $strings['RegisterANewAccount'] = 'Регистрация новой учетной записи';
        $strings['Dates'] = 'Даты';
        $strings['More'] = 'Больше';
        $strings['ResourceAvailability'] = 'Доступные помещения';
        $strings['UnavailableAllDay'] = 'В течение всего дня';
        $strings['AvailableUntil'] = 'Доступно до';
        $strings['AvailableBeginningAt'] = 'Доступно начиная с';
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
        $strings['DateTime'] = 'Дата Время';
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
        $strings['OriginalEndDate'] = 'Original End';
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
        $strings['FindATime'] = 'Найти Время';
        $strings['AnyResource'] = 'Любой Помещение';
        $strings['ThisWeek'] = 'Этой Неделя';
        $strings['Hours'] = 'Часы';
        $strings['Minutes'] = 'Минуты';
        $strings['ImportICS'] = 'Импортировать из ICS';
        $strings['ImportQuartzy'] = 'Импортировать из Quartzy';
        $strings['IncludeDeleted'] = 'Включить удаленные';
        $strings['Deleted'] = 'Удалённые';
        $strings['OnlyIcs'] = 'Только * .ics файлы могут быть загружены.';
        $strings['IcsLocationsAsResources'] = 'Места будут импортированы в качестве ресурсов.';
        $strings['IcsMissingOrganizer'] = 'Любому событию, с отсутствующим организатором, организатором будет назначен текущий пользователь.';
        $strings['IcsWarning'] = 'Правила бронирования не будут применяться - возможны конфликты, дубликаты и т. д.';
        $strings['BlackoutAroundConflicts'] = 'Блокировка из-за противоречивых бронирований';
        $strings['DuplicateReservation'] = 'Дублировать';
        $strings['UnavailableNow'] = 'Недоступно Сейчас';
        $strings['ReserveLater'] = 'Зарезервировать Позже';
        $strings['CollectedFor'] = 'Собрано Для';
        $strings['IncludeDeleted'] = 'Включая удаленные бронирования';
        $strings['Deleted'] = 'Удалено';
        $strings['Back'] = 'Назад';
        $strings['Forward'] = 'Вперед';
        $strings['DateRange'] = 'Диапазон Дат';
        $strings['Copy'] = 'Копировать';
        $strings['Detect'] = 'Обнаружение';
        $strings['Autofill'] = 'Автозаполнение';
        $strings['NameOrEmail'] = 'имя или email';
        $strings['ImportResources'] = 'Импорт Помещений';
        $strings['ExportResources'] = 'Экспорт Помещений';
        $strings['ResourceImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Укажите имя. Если оставить остальные поля пустыми, будут установлены значения по умолчанию.</li><li>Возможные значения: \'Доступен\', \'Недоступен\' and \'Скрыт\'.</li><li>Цвет должен быть шестнадцатеричным значением. ex) #ffffff.</li><li>Столбцы автоматического назначения и утверждения могут быть истинными или ложными.</li><li>Атрибут периода действия не будет применяться.</li><li>Запятые разделяют несколько групп ресурсов.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['ReservationImportInstructions'] = '<ul><li>Файл должен быть в формате CSV.</li><li>Эл.Почта, имена помещений, начало и конец - обязательные поля.</li><li>Для начала и конца требуется полное время. Рекомендуемый формат: YYYY-mm-dd HH:mm (2017-12-31 20:30).</li><li>Правила, конфликты и действительные временные интервалы не будут проверяться.</li><li>Уведомления не будут отправляться.</li><li>трибут периода действия не будет применяться.</li><li>Запятые разделяют имена нескольких ресурсов.</li><li>В качестве примера используйте прилагаемый шаблон.</li></ul>';
        $strings['AutoReleaseMinutes'] = 'Минуты автозагрузки';
        $strings['CreditsPeak'] = 'Кредиты (максимальный)';
        $strings['CreditsOffPeak'] = 'Кредиты (вне пика)';
        $strings['ResourceMinLengthCsv'] = 'Минимальная длина бронирования';
        $strings['ResourceMaxLengthCsv'] = 'Максимальная длина бронирования';
        $strings['ResourceBufferTimeCsv'] = 'Buffer Time';
        $strings['ResourceMinNoticeCsv'] = 'Reservation Minimum Notice';
        $strings['ResourceMaxNoticeCsv'] = 'Reservation Maximum End';
        $strings['Export'] = 'Экспорт';
        $strings['DeleteMultipleUserWarning'] = 'При удалении этих пользователей будут удалены все их текущие, будущие и исторические бронирования. Никакие электронные письма не будут отправлены.';
        $strings['DeleteMultipleReservationsWarning'] = 'Никакие электронные письма не будут отправлены.';
        $strings['ErrorMovingReservation'] = 'Ошибка при перемещении Бронирования';
        $strings['SelectUser'] = 'Выберите пользователя';
        $strings['InviteUsers'] = 'Пригласить пользователей';
        $strings['InviteUsersLabel'] = 'Введите адреса электронной почты приглашенных лиц.';
        $strings['ApplyToCurrentUsers'] = 'Применить к текущим пользователям';
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
        $strings['DatabaseName'] = 'Имя Базы данных';
        $strings['DatabaseUser'] = 'Пользователь Базы данных';
        $strings['DatabaseHost'] = 'Host Базы данных';
        $strings['DatabaseCredentials'] = 'Вы должны предоставить учетные данные пользователя MySQL, который имеет привилегии для создания баз данных. Если вы не знаете, обратитесь к администратору базы данных. Во многих случаях, root будет работать.';
        $strings['MySQLUser'] = 'Пользователь MySQL';
        $strings['InstallOptionsWarning'] = 'Следующие варианты, вероятно не будут,  работать в среде хостинга. Если вы устанавливаете на хост, то используйте мастер установки MySQL для выполнения этих шагов.';
        $strings['CreateDatabase'] = 'Создайте базу данных';
        $strings['CreateDatabaseUser'] = 'Создание пользователя базы данных';
        $strings['PopulateExampleData'] = 'Импорт выборки данных. Дата создания учетной записи администратора: admin/password и пользователя: user/password';
        $strings['DataWipeWarning'] = 'Внимание: Это удалит все существующие данные';
        $strings['RunInstallation'] = 'Запуск установки';
        $strings['UpgradeNotice'] = 'Вы обновляете версию <b>%s</b> до версии <b>%s</b>';
        $strings['RunUpgrade'] = 'Запуск обновления';
        $strings['Executing'] = 'Выполнение';
        $strings['StatementFailed'] = 'Не удалось. Детали:';
        $strings['SQLStatement'] = 'SQL Statement:';
        $strings['ErrorCode'] = 'Код ошибки:';
        $strings['ErrorText'] = 'Текст ошибки:';
        $strings['InstallationSuccess'] = 'Установка успешно завершена!';
        $strings['RegisterAdminUser'] = 'Зарегистрируйте Вашего пользователя с правами администратора. Это необходимо, если вы не импортировали данные из образца . Проверьте, что $conf[\'settings\'][\'allow.self.registration\'] = \'true\' в вашем  %s файле.';
        $strings['LoginWithSampleAccounts'] = 'Если вы импортировали данные примера, вы можете войти с admin/password для администратора или user/password для простого пользователя.';
        $strings['InstalledVersion'] = 'Сейчас у вас запущена версия %s LibreBooking';
        $strings['InstallUpgradeConfig'] = 'Рекомендуется обновить конфигурационный файл';
        $strings['InstallationFailure'] = 'Были проблемы с установкой. Пожалуйста, исправьте их и повторите установку.';
        $strings['ConfigureApplication'] = 'Настроить LibreBooking';
        $strings['ConfigUpdateSuccess'] = 'Ваш конфигурационный файл теперь обновлен!';
        $strings['ConfigUpdateFailure'] = 'Мы не могли автоматически обновлять свой конфигурационный файл. Пожалуйста, перезаписать содержимое config.php со следующими требованиями:';
        $strings['SelectUser'] = 'Выбор пользователя';
        // End Install

        // Errors
        $strings['LoginError'] = 'Нет соответствующего имени пользователя или пароль';
        $strings['ReservationFailed'] = 'Процедура оформления бронирования не может быть создана';
        $strings['MinNoticeError'] = 'Данное бронирование требует предварительного уведомления. Самая ранняя дата и время, которое можно наблюдать в %s.';
        $strings['MaxNoticeError'] = 'Данное бронирование не может быть далеко в будущем. Последняя дата и время, которое можно наблюдать в %s.';
        $strings['MinDurationError'] = 'Данное бронирование должна быть не менее %s.';
        $strings['MaxDurationError'] = 'Данное бронирование не может длиться дольше, чем %s.';
        $strings['ConflictingAccessoryDates'] = 'Слишком мало следующого Оборудования:';
        $strings['NoResourcePermission'] = 'У вас нет разрешения на доступ к одному или нескольким из требуемых помещений.';
        $strings['ConflictingReservationDates'] = 'Существуют противоречивые Бронирования в следующих сроках:';
        $strings['StartDateBeforeEndDateRule'] = 'Дата и время начала должно быть до даты и времени окончания.';
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
        $strings['MultiDayRule'] = '%s не допускает Бронирование через дней.';
        $strings['InvalidReservationData'] = 'Были проблемы с Вашим запросом.';
        $strings['PasswordError'] = 'Пароль должен содержать по меньшей мере %s букв и по крайней мере %s чисел.';
        $strings['PasswordErrorRequirements'] = 'Пароль должен содержать комбинацию, по меньшей мере, %s верхние и строчные буквы и %s чисел.';
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
        $strings['InsecureRequestError'] = 'Небезопасной запрос. Если вы будете продолжать видеть эту ошибку, пожалуйста, снова войти в систему и повторите запрос.';
        $strings['RemoveExistingPermissions'] = 'Удалить существующие разрешения?';
        // End Errors

        // Page Titles
        $strings['CreateReservation'] = 'Создать Бронирование';
        $strings['EditReservation'] = 'Изменить Бронирование';
        $strings['LogIn'] = 'Вход';
        $strings['ManageReservations'] = 'Бронирования';
        $strings['AwaitingActivation'] = 'Ожидает Обобрения';
        $strings['PendingApproval'] = 'В ожидании Одобрения';
        $strings['ManageSchedules'] = 'Расписания';
        $strings['ManageResources'] = 'Помещения';
        $strings['ManageAccessories'] = 'Оборудование';
        $strings['ManageUsers'] = 'Пользователи';
        $strings['ManageGroups'] = 'Группы';
        $strings['ManageQuotas'] = 'Квоты';
        $strings['ManageBlackouts'] = 'Прошедшие мероприятия';
        $strings['MyDashboard'] = 'Моя Панель';
        $strings['ServerSettings'] = 'Настройки Сервера';
        $strings['Dashboard'] = 'Панель';
        $strings['Help'] = 'Помощь';
        $strings['Administration'] = 'Администрирование';
        $strings['About'] = 'About';
        $strings['Bookings'] = 'Бронированные';
        $strings['Schedule'] = 'Расписание';
        $strings['Reservations'] = 'Резервирование';
        $strings['Account'] = 'Аккаунт';
        $strings['EditProfile'] = 'Изменить мой Профиль';
        $strings['FindAnOpening'] = 'Найти Открытые';
        $strings['OpenInvitations'] = 'Открыть Приглашения';
        $strings['MyCalendar'] = 'Мой Календарь';
        $strings['ResourceCalendar'] = 'Календарь по помещениям';
        $strings['Reservation'] = 'Новое резервирование';
        $strings['Install'] = 'Установка';
        $strings['ChangePassword'] = 'Изменить Пароль';
        $strings['MyAccount'] = 'Мой Аккаунт';
        $strings['Profile'] = 'Профиль';
        $strings['ApplicationManagement'] = 'Управление приложением';
        $strings['ForgotPassword'] = 'Забыл Пароль';
        $strings['NotificationPreferences'] = 'Настройки уведомлений';
        $strings['ManageAnnouncements'] = 'Объявления';
        $strings['Responsibilities'] = 'Обязанности';
        $strings['GroupReservations'] = 'Группа Бронирований';
        $strings['ResourceReservations'] = 'Помещения бронирования';
        $strings['Customization'] = 'Настройка';
        $strings['Attributes'] = 'Атрибуты';
        $strings['AccountActivation'] = 'Активация Аккаунта';
        $strings['ScheduleReservations'] = 'Расписание Бронирования';
        $strings['Reports'] = 'Отчёты';
        $strings['GenerateReport'] = 'Создать новый Отчёт';
        $strings['MySavedReports'] = 'Мои Сохраненнные Отчёты';
        $strings['CommonReports'] = 'Общие Отчёты';
        $strings['ViewDay'] = 'Посмотреть День';
        $strings['Group'] = 'Группа';
        $strings['ManageConfiguration'] = 'Настройка приложения';
        $strings['LookAndFeel'] = 'Смотри и чувствуй';
        $strings['ManageResourceGroups'] = 'Группы помещений';
        $strings['ManageResourceTypes'] = 'Типы Помещений';
        $strings['ManageResourceStatus'] = 'Статус Помещений';
        $strings['ReservationColors'] = 'Цвет бронирования';
        $strings['ImportICS'] = 'Импорт ICS File';
        $strings['ImportQuartzy'] = 'Импорт Quartzy File';
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
        $strings['ReservationApprovedSubject'] = 'Ваше Бронирование было одобрено';
        $strings['ReservationCreatedSubject'] = 'Ваше Бронирование было создано';
        $strings['ReservationUpdatedSubject'] = 'Ваше Бронирование было Обновлено';
        $strings['ReservationDeletedSubject'] = 'Ваше Бронирование было удалено';
        $strings['ReservationCreatedAdminSubject'] = 'Уведомление: Бронирование было создано';
        $strings['ReservationUpdatedAdminSubject'] = 'Уведомление: Бронирование было Обновлено';
        $strings['ReservationDeleteAdminSubject'] = 'Уведомление: Бронирование было Удалено';
        $strings['ReservationApprovalAdminSubject'] = 'Уведомление: Бронирование требует одобрения';
        $strings['ParticipantAddedSubject'] = 'Уведомление о участие в Мероприятии';
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
        $strings['InviteUserSubject'] = '%s приглашает вас присоединиться к %s';

        $strings['ReservationApprovedSubjectWithResource'] = 'Бронирование было одобрено для %s';
        $strings['ReservationCreatedSubjectWithResource'] = 'Бронирование Создано для %s';
        $strings['ReservationUpdatedSubjectWithResource'] = 'Бронирование Обновлено для: %s';
        $strings['ReservationDeletedSubjectWithResource'] = 'Бронирование удалено для %s';
        $strings['ReservationCreatedAdminSubjectWithResource'] = 'Уведомление: Бронирование создано для %s';
        $strings['ReservationUpdatedAdminSubjectWithResource'] = 'Уведомление: Бронирование обновлено для %s';
        $strings['ReservationDeleteAdminSubjectWithResource'] = 'Уведомление: бронирование удалено для %s';
        $strings['ReservationApprovalAdminSubjectWithResource'] = 'Уведомление: резервирование для %s требует вашего утверждения';
        $strings['ParticipantAddedSubjectWithResource'] = '%s Добавлен в бронирование для %s';
        $strings['ParticipantDeletedSubjectWithResource'] = '%s Удалено бронирование для %s';
        $strings['InviteeAddedSubjectWithResource'] = '%s Пригласил вас на мнроприятие для %s';
        $strings['MissedCheckinEmailSubject'] = 'Не прошли регистрацию %s';

        $strings['UserDeleted'] = 'Аккаунт Пользователя %s был удален %s';
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
        $strings['Remaining'] = 'Осталось';        
        //End Pending Approval Reservations in Dashboard

        //Missing Check In/Out Reservations in Dashboard
        $strings['NoMissingCheckOutReservations'] = 'Отсутствуют пропущенные резервации на выезд';
        $strings['MissingCheckOutReservations'] = 'Пропущенные резервации на выезд';        
        //End Missing Check In/Out Reservations in Dashboard

        //Schedule Resource Permissions
        $strings['NoResourcePermissions'] = 'Невозможно просмотреть детали бронирования, потому что у вас нет разрешений на ни один из ресурсов в этом бронировании';
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
        $months = [];

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
