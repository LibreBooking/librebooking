<?php

require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageReservationsPresenter.php');

interface IManageBlackoutsPage extends IPageable, IActionPage, IRepeatOptionsComposite
{
    /**
     * @param array|BlackoutItemView[] $blackouts
     * @return void
     */
    public function BindBlackouts($blackouts);

    /**
     * @return string|null
     */
    public function GetStartDate();

    /**
     * @return string|null
     */
    public function GetEndDate();

    public function GetScheduleId(): ?int;

    public function GetResourceId(): ?int;

    /**
     * @param Date $date |null
     * @return void
     */
    public function SetStartDate($date);

    /**
     * @param Date $date |null
     * @return void
     */
    public function SetEndDate($date);

    /**
     * @param int $scheduleId
     * @return void
     */
    public function SetScheduleId($scheduleId);

    /**
     * @param int $resourceId
     * @return void
     */
    public function SetResourceId($resourceId);

    /**
     * @param array|Schedule[] $schedules
     * @return void
     */
    public function BindSchedules($schedules);

    /**
     * @param array|BookableResource[] $resources
     * @return void
     */
    public function BindResources($resources);

    public function GetDeleteBlackoutId(): ?int;

    /**
     * @return string|null
     */
    public function GetDeleteScope();

    /**
     * @return void
     */
    public function ShowPage();

    public function ShowBlackout();

    /**
     * @return bool
     */
    public function GetApplyBlackoutToAllResources();

    public function GetBlackoutScheduleId(): ?int;

    public function GetBlackoutResourceId(): ?int;

    /**
     * @return int[]
     */
    public function GetBlackoutResourceIds(): array;

    /**
     * @return string|null
     */
    public function GetBlackoutStartDate();

    /**
     * @return string|null
     */
    public function GetBlackoutStartTime();

    /**
     * @return string|null
     */
    public function GetBlackoutEndDate();

    /**
     * @return string|null
     */
    public function GetBlackoutEndTime();

    /**
     * @return string|null
     */
    public function GetBlackoutTitle();

    /**
     * @return string|ReservationConflictResolution|null
     */
    public function GetBlackoutConflictAction();

    public function GetBlackoutId(): ?int;

    /**
     * @return string|null SeriesUpdateScope::ThisInstance or
     *     SeriesUpdateScope::FullSeries; the full series when the field is
     *     omitted or blank; null for anything else
     */
    public function GetSeriesUpdateScope();

    public function GetUpdateBlackoutId(): ?int;

    /**
     * @param bool $wasAddedSuccessfully
     * @param string $displayMessage
     * @param array|ReservationItemView[] $conflictingReservations
     * @param array|BlackoutItemView[] $conflictingBlackouts
     * @param string $timezone
     * @return void
     */
    public function ShowAddResult(
        $wasAddedSuccessfully,
        $displayMessage,
        $conflictingReservations,
        $conflictingBlackouts,
        $timezone
    );

    /**
     * @param bool $wasAddedSuccessfully
     * @param string $displayMessage
     * @param array|ReservationItemView[] $conflictingReservations
     * @param array|BlackoutItemView[] $conflictingBlackouts
     * @param string $timezone
     * @return void
     */
    public function ShowUpdateResult(
        $wasAddedSuccessfully,
        $displayMessage,
        $conflictingReservations,
        $conflictingBlackouts,
        $timezone
    );

    /**
     * Reports that a delete was rejected. A successful delete has an empty
     * response, so without this the caller could not tell the two apart.
     */
    public function ShowDeleteRejected(): void;

    /**
     * @param int[] $blackoutResourceIds
     */
    public function SetBlackoutResources($blackoutResourceIds);

    /**
     * @param string $title
     */
    public function SetTitle($title);

    /**
     * @param string $repeatType
     */
    public function SetRepeatType($repeatType);

    /**
     * @param int $repeatInterval
     */
    public function SetRepeatInterval($repeatInterval);

    /**
     * @param string $repeatMonthlyType
     */
    public function SetRepeatMonthlyType($repeatMonthlyType);

    /**
     * @param int[] $repeatWeekdays
     */
    public function SetRepeatWeekdays($repeatWeekdays);

    /**
     * @param Date[] $customRepeatDates
     */
    public function SetCustomRepeatDates($customRepeatDates);

    /**
     * @param Date $repeatTerminationDate
     */
    public function SetRepeatTerminationDate($repeatTerminationDate);

    /**
     * @param int $blackoutId
     */
    public function SetBlackoutId($blackoutId);

    /**
     * @param bool $isRecurring
     */
    public function SetIsRecurring($isRecurring);

    public function SetBlackoutStartDate(Date $startDate);

    public function SetBlackoutEndDate(Date $endDate);

    /**
     * @param bool $wasFound
     */
    public function SetWasBlackoutFound($wasFound);

    /**
     * @return int[]
     */
    public function GetDeletedBlackoutIds(): array;
}

class ManageBlackoutsPage extends ActionPage implements IManageBlackoutsPage
{
    private const HTTP_BAD_REQUEST = 400;

    /**
     * @var ManageBlackoutsPresenter
     */
    private $presenter;

    /**
     * @var PageablePage
     * @todo(jlvillal): 2025-07-15: Convert this to `Page` for LibreBooking v4.0.0
     */
    private $pageablePage;

    public function __construct()
    {
        parent::__construct('ManageBlackouts', 1);

        $userRepo = new UserRepository();
        $userSession = ServiceLocator::GetServer()->GetUserSession();

        $this->presenter = new ManageBlackoutsPresenter(
            $this,
            new ManageBlackoutsService(new ReservationViewRepository(), new BlackoutRepository(), $userRepo),
            new ScheduleAdminScheduleRepository($userRepo, $userSession),
            new ResourceAdminResourceRepository($userRepo, $userSession)
        );

        /**
         * @todo(jlvillal): 2025-07-15: Convert this to `Page` for LibreBooking v4.0.0
         */
        $this->pageablePage = new PageablePage($this);
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessPageLoad()
    {
        $userTimezone = $this->server->GetUserSession()->Timezone;

        $this->Set('Timezone', $userTimezone);
        $this->Set('AddStartDate', Date::Now()->ToTimezone($userTimezone));
        $this->Set('AddEndDate', Date::Now()->ToTimezone($userTimezone));
        $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('period_time'));
        $this->presenter->PageLoad($userTimezone);
    }

    public function ShowPage()
    {
        $this->Display('Admin/Blackouts/manage_blackouts.tpl');
    }

    public function ShowBlackout()
    {
        $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('period_time'));
        $this->Display('Admin/Blackouts/manage_blackouts_edit.tpl');
    }

    public function ShowAddResult(
        $wasAddedSuccessfully,
        $displayMessage,
        $conflictingReservations,
        $conflictingBlackouts,
        $timezone
    ) {
        $this->Set('Successful', $wasAddedSuccessfully);
        $this->Set('SuccessKey', 'BlackoutCreated');
        $this->Set('FailKey', 'BlackoutNotCreated');
        $this->Set('Message', $displayMessage);
        $this->Set('Reservations', $conflictingReservations);
        $this->Set('Blackouts', $conflictingBlackouts);
        $this->Set('Timezone', $timezone);
        $this->Display('Admin/Blackouts/manage_blackouts_response.tpl');
    }

    public function ShowUpdateResult(
        $wasAddedSuccessfully,
        $displayMessage,
        $conflictingReservations,
        $conflictingBlackouts,
        $timezone
    ) {
        $this->Set('Successful', $wasAddedSuccessfully);
        $this->Set('SuccessKey', 'BlackoutUpdated');
        $this->Set('FailKey', 'BlackoutNotUpdated');
        $this->Set('Message', $displayMessage);
        $this->Set('Reservations', $conflictingReservations);
        $this->Set('Blackouts', $conflictingBlackouts);
        $this->Set('Timezone', $timezone);
        $this->Display('Admin/Blackouts/manage_blackouts_response.tpl');
    }

    public function ShowDeleteRejected(): void
    {
        http_response_code(self::HTTP_BAD_REQUEST);
    }

    public function BindBlackouts($blackouts)
    {
        $this->Set('blackouts', $blackouts);
    }

    public function GetStartDate(): ?string
    {
        return $this->GetQuerystringString(key: QueryStringKeys::START_DATE);
    }

    public function GetEndDate(): ?string
    {
        return $this->GetQuerystringString(key: QueryStringKeys::END_DATE);
    }

    /**
     * @param Date $date
     * @return void
     */
    public function SetStartDate($date)
    {
        $this->Set('StartDate', $date);
    }

    /**
     * @param Date $date
     * @return void
     */
    public function SetEndDate($date)
    {
        $this->Set('EndDate', $date);
    }

    public function GetScheduleId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::SCHEDULE_ID);
    }

    public function GetResourceId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::RESOURCE_ID);
    }

    /**
     * @param int $scheduleId
     * @return void
     */
    public function SetScheduleId($scheduleId)
    {
        $this->Set('ScheduleId', $scheduleId);
    }

    /**
     * @param int $resourceId
     * @return void
     */
    public function SetResourceId($resourceId)
    {
        $this->Set('ResourceId', $resourceId);
    }

    public function BindSchedules($schedules)
    {
        $this->Set('Schedules', $schedules);
    }

    public function BindResources($resources)
    {
        $this->Set('Resources', $resources);
    }

    /**
     * @return int
     */
    public function GetPageNumber()
    {
        return $this->pageablePage->GetPageNumber();
    }

    public function GetPageSize(): int
    {
        return $this->pageablePage->GetPageSize();
    }

    /**
     * @param PageInfo $pageInfo
     * @return void
     */
    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->pageablePage->BindPageInfo($pageInfo);
    }

    public function GetDeleteBlackoutId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::BLACKOUT_ID);
    }

    public function GetDeleteScope(): ?string
    {
        return $this->GetFormString(key: FormKeys::SERIES_UPDATE_SCOPE);
    }

    /**
     * @return bool
     */
    public function GetApplyBlackoutToAllResources()
    {
        $applyToSchedule = $this->GetForm(FormKeys::BLACKOUT_APPLY_TO_SCHEDULE);
        return isset($applyToSchedule);
    }

    public function GetBlackoutScheduleId(): ?int
    {
        return $this->GetFormInt(key: FormKeys::SCHEDULE_ID);
    }

    public function GetBlackoutResourceId(): ?int
    {
        return $this->GetFormInt(key: FormKeys::RESOURCE_ID);
    }

    public function GetBlackoutStartDate(): ?string
    {
        return $this->GetFormString(key: FormKeys::BEGIN_DATE);
    }

    public function GetBlackoutStartTime(): ?string
    {
        return $this->GetFormString(key: FormKeys::BEGIN_TIME);
    }

    public function GetBlackoutEndDate(): ?string
    {
        return $this->GetFormString(key: FormKeys::END_DATE);
    }

    public function GetBlackoutEndTime(): ?string
    {
        return $this->GetFormString(key: FormKeys::END_TIME);
    }

    public function GetBlackoutTitle(): ?string
    {
        return $this->GetFormString(key: FormKeys::SUMMARY);
    }

    public function GetBlackoutConflictAction(): ?string
    {
        return $this->GetFormString(key: FormKeys::CONFLICT_ACTION);
    }


    public function GetBlackoutId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::BLACKOUT_ID);
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    public function GetRepeatType(): ?string
    {
        return $this->GetFormString(key: FormKeys::REPEAT_OPTIONS);
    }

    public function GetRepeatInterval(): ?string
    {
        return $this->GetFormString(key: FormKeys::REPEAT_EVERY);
    }

    public function GetRepeatWeekdays()
    {
        $days = [];

        $sun = $this->GetForm(FormKeys::REPEAT_SUNDAY);
        if (!empty($sun)) {
            $days[] = 0;
        }

        $mon = $this->GetForm(FormKeys::REPEAT_MONDAY);
        if (!empty($mon)) {
            $days[] = 1;
        }

        $tue = $this->GetForm(FormKeys::REPEAT_TUESDAY);
        if (!empty($tue)) {
            $days[] = 2;
        }

        $wed = $this->GetForm(FormKeys::REPEAT_WEDNESDAY);
        if (!empty($wed)) {
            $days[] = 3;
        }

        $thu = $this->GetForm(FormKeys::REPEAT_THURSDAY);
        if (!empty($thu)) {
            $days[] = 4;
        }

        $fri = $this->GetForm(FormKeys::REPEAT_FRIDAY);
        if (!empty($fri)) {
            $days[] = 5;
        }

        $sat = $this->GetForm(FormKeys::REPEAT_SATURDAY);
        if (!empty($sat)) {
            $days[] = 6;
        }

        return $days;
    }

    public function GetRepeatMonthlyType(): ?string
    {
        return $this->GetFormString(key: FormKeys::REPEAT_MONTHLY_TYPE);
    }

    public function GetRepeatTerminationDate(): ?string
    {
        return $this->GetFormString(key: FormKeys::END_REPEAT_DATE);
    }

    public function GetRepeatCustomDates()
    {
        $dates = $this->GetForm(FormKeys::REPEAT_CUSTOM_DATES);
        if (!is_array($dates) || empty($dates)) {
            return [];
        }

        return $dates;
    }

    public function GetSeriesUpdateScope(): ?string
    {
        $scope = $this->GetForm(var: FormKeys::SERIES_UPDATE_SCOPE);

        if ($scope === null || $scope === '') {
            return SeriesUpdateScope::FullSeries;
        }

        // Blackouts are changed one instance at a time or as a whole series.
        // Any other value, such as a list or the future-instances scope, must
        // not fall through to either of those.
        $supportedScopes = [SeriesUpdateScope::ThisInstance, SeriesUpdateScope::FullSeries];

        return in_array(needle: $scope, haystack: $supportedScopes, strict: true) ? $scope : null;
    }

    public function SetBlackoutResources($blackoutResourceIds)
    {
        $this->Set('BlackoutResourceIds', $blackoutResourceIds);
    }

    public function SetTitle($title)
    {
        $this->Set('BlackoutTitle', $title);
    }

    public function SetRepeatType($repeatType)
    {
        $this->Set('RepeatType', $repeatType);
    }

    public function SetRepeatInterval($repeatInterval)
    {
        $this->Set('RepeatInterval', $repeatInterval);
    }

    public function SetRepeatMonthlyType($repeatMonthlyType)
    {
        $this->Set('RepeatMonthlyType', $repeatMonthlyType);
    }

    public function SetRepeatWeekdays($repeatWeekdays)
    {
        $this->Set('RepeatWeekdays', $repeatWeekdays);
    }

    public function SetRepeatTerminationDate($repeatTerminationDate)
    {
        $this->Set('RepeatTerminationDate', $repeatTerminationDate);
    }

    public function SetCustomRepeatDates($customRepeatDates)
    {
        $this->Set('CustomRepeatDates', $customRepeatDates);
    }

    public function SetBlackoutId($blackoutId)
    {
        $this->Set('BlackoutId', $blackoutId);
    }

    public function SetIsRecurring($isRecurring)
    {
        $this->Set('IsRecurring', $isRecurring);
    }

    public function SetBlackoutStartDate(Date $startDate)
    {
        $this->Set('BlackoutStartDate', $startDate);
    }

    public function SetBlackoutEndDate(Date $endDate)
    {
        $this->Set('BlackoutEndDate', $endDate);
    }

    public function SetWasBlackoutFound($wasFound)
    {
        $this->Set('WasBlackoutFound', $wasFound);
    }

    public function GetBlackoutResourceIds(): array
    {
        return $this->GetFormIntList(key: FormKeys::RESOURCE_ID);
    }

    public function GetUpdateBlackoutId(): ?int
    {
        return $this->GetFormInt(key: FormKeys::BLACKOUT_INSTANCE_ID);
    }

    public function GetDeletedBlackoutIds(): array
    {
        return $this->GetFormIntList(key: FormKeys::BLACKOUT_INSTANCE_ID);
    }
}
