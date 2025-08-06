<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Schedule/SchedulePresenter.php');
require_once(ROOT_DIR . 'Presenters/Schedule/LoadReservationRequest.php');

interface ISchedulePage extends IActionPage
{
    /**
     * @param array|Schedule[] $schedules
     */
    public function SetSchedules($schedules);

    /**
     * @param array|ResourceDto[] $resources
     */
    public function SetResources($resources);

    /**
     * @param IDailyLayout $dailyLayout
     */
    public function SetDailyLayout($dailyLayout);

    /**
     * @return int
     */
    public function GetScheduleId();

    /**
     * @param int $scheduleId
     */
    public function SetScheduleId($scheduleId);

    /**
     * @param string $scheduleName
     */
    public function SetScheduleName($scheduleName);

    /**
     * @param int $firstWeekday
     */
    public function SetFirstWeekday($firstWeekday);

    /**
     * @param DateRange $dates
     */
    public function SetDisplayDates($dates);

    /**
     * @param Date $previousDate
     * @param Date $nextDate
     */
    public function SetPreviousNextDates($previousDate, $nextDate);

    /**
     * @return string
     */
    public function GetSelectedDate();

    /**
     * @return Date[]
     */
    public function GetSelectedDates();

    public function ShowInaccessibleResources();

    /**
     * @param bool $showShowFullWeekToggle
     */
    public function ShowFullWeekToggle($showShowFullWeekToggle);

    /**
     * @return bool
     */
    public function GetShowFullWeek();

    /**
     * @param ScheduleLayoutSerializable $layoutResponse
     */
    public function SetLayoutResponse($layoutResponse);

    /**
     * @return string
     */
    public function GetLayoutDate();

    /**
     * @param int $scheduleId
     * @return string|ScheduleStyle
     */
    public function GetScheduleStyle($scheduleId);

    /**
     * @param string|ScheduleStyle $direction
     */
    public function SetScheduleStyle($direction);

    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return int[]
     */
    public function GetResourceIds();

    /**
     * @return int|null
     */
    public function GetOwnerId();

    /**
     * @return string
     */
    public function GetOwnerText();

    /**
     * @return int|null
     */
    public function GetParticipantId();

    /**
     * @return string
     */
    public function GetParticipantText();

    /**
     * @param ResourceGroupTree $resourceGroupTree
     */
    public function SetResourceGroupTree(ResourceGroupTree $resourceGroupTree);

    /**
     * @param ResourceType[] $resourceTypes
     */
    public function SetResourceTypes($resourceTypes);

    /**
     * @param Attribute[] $attributes
     */
    public function SetResourceCustomAttributes($attributes);

    /**
     * @param Attribute[] $attributes
     */
    public function SetResourceTypeCustomAttributes($attributes);

    /**
     * @return bool
     */
    public function FilterSubmitted();

    /**
     * @return int
     */
    public function GetResourceTypeId();

    /**
     * @return int
     */
    public function GetMaxParticipants();

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetResourceAttributes();

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetResourceTypeAttributes();

    /**
     * @param ScheduleResourceFilter $resourceFilter
     */
    public function SetFilter($resourceFilter);

    /**
     * @param CalendarSubscriptionUrl $subscriptionUrl
     */
    public function SetSubscriptionUrl(CalendarSubscriptionUrl $subscriptionUrl);

    /**
     * @param bool $shouldShow
     */
    public function ShowPermissionError($shouldShow);

    /**
     * @param UserSession $user
     * @param Schedule $schedule
     * @return string
     */
    public function GetDisplayTimezone(UserSession $user, Schedule $schedule);

    /**
     * @param Date[] $specificDates
     */
    public function SetSpecificDates($specificDates);

    /**
     * @return bool
     */
    public function FilterCleared();

    /**
     * @param DateRange $availability
     * @param bool $tooEarly
     */
    public function BindScheduleAvailability($availability, $tooEarly);

    /**
     * @param $viewableResourceReservations
     */
    public function BindViewableResourceReservations($resourceIds);

    /**
     * @return LoadReservationRequest
     */
    public function GetReservationRequest();

    /**
     * @param ReservationListItem[] $items
     */
    public function BindReservations($items);
}

class SchedulePage extends ActionPage implements ISchedulePage
{
    protected $ScheduleStyle = ScheduleStyle::Standard;
    private $resourceCount = 0;

    /**
     * @var SchedulePresenter
     */
    protected $_presenter;

    private $_styles = [
        ScheduleStyle::Wide => 'Schedule/schedule-days-horizontal.tpl',
        ScheduleStyle::Tall => 'Schedule/schedule-flipped.tpl',
        ScheduleStyle::CondensedWeek => 'Schedule/schedule-week-condensed.tpl',
    ];

    /**
     * @var bool
     */
    private $_isFiltered = true;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    public function __construct()
    {
        parent::__construct('Schedule');

        $this->Set('CanViewUsers', !Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, new BooleanConverter()));
        $this->Set('AllowParticipation', !Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter()));
        $this->Set('AllowCreatePastReservationsButton', ServiceLocator::GetServer()->GetUserSession()->IsAdmin);

        $permissionServiceFactory = new PermissionServiceFactory();
        $scheduleRepository = new ScheduleRepository();
        $userRepository = new UserRepository();
        $this->userRepository = $userRepository;
        $schedulePermissionService = new SchedulePermissionService($permissionServiceFactory->GetPermissionService());
        $resourceService = new ResourceService(
            new ResourceRepository(),
            $schedulePermissionService,
            new AttributeService(new AttributeRepository()),
            $userRepository,
            new AccessoryRepository()
        );
        $pageBuilder = new SchedulePageBuilder();
        $reservationService = new ReservationService(new ReservationViewRepository(), new ReservationListingFactory());
        $dailyLayoutFactory = new DailyLayoutFactory();
        $scheduleService = new ScheduleService($scheduleRepository, $resourceService, $dailyLayoutFactory);
        $this->_presenter = new SchedulePresenter($this, $scheduleService, $resourceService, $pageBuilder, $reservationService);
    }

    public function ProcessPageLoad()
    {
        $start = microtime(true);

        URIScriptValidator::validateOrRedirect($_SERVER['REQUEST_URI'], '/schedule.php');
        ParamsValidator::validateOrRedirect(RouteParamsKeys::VIEW_SCHEDULE, $_SERVER['REQUEST_URI'], '/schedule.php', true);

        $user = ServiceLocator::GetServer()->GetUserSession();

        // ensure Smarty $ResourceIds is an empty array to prevent an error if no
        // resources. Will be overridden if there are resources.
        $this->Set('ResourceIds', []);
        $this->_presenter->PageLoad($user);

        $endLoad = microtime(true);

        if ($user->IsAdmin && $this->resourceCount == 0 && !$this->_isFiltered) {
            $this->Set('ShowResourceWarning', true);
        }

        $authorizationService = new AuthorizationService($this->userRepository);

        $this->Set('SlotLabelFactory', new SlotLabelFactory($user, $authorizationService));
        $this->Set('DisplaySlotFactory', new DisplaySlotFactory());
        $this->Set('PopupMonths', $this->IsMobile ? 1 : 3);
        $this->Set('CreateReservationPage', Pages::RESERVATION);
        $this->Set('LoadViewOnly', false);
        $this->Set('ShowSubscription', true);
        $this->Set('UserIdFilter', $this->GetOwnerId());
        $this->Set('ParticipantIdFilter', $this->GetParticipantId());
        $this->Set('ShowWeekNumbers', Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_SHOW_WEEK_NUMBERS, new BooleanConverter()));
        $this->Set('FastReservationLoad', Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_FAST_RESERVATION_LOAD, new BooleanConverter()) ?? false);

        if ($this->IsMobile && !$this->IsTablet) {
            if ($this->ScheduleStyle == ScheduleStyle::Tall) {
                $this->Display('Schedule/schedule-flipped.tpl');
            } else {
                $this->Display('Schedule/schedule-mobile.tpl');
            }
        } else {
            if (array_key_exists($this->ScheduleStyle, $this->_styles)) {
                $this->Display($this->_styles[$this->ScheduleStyle]);
            } else {
                $this->Display('Schedule/schedule.tpl');
            }
        }

        $endDisplay = microtime(true);

        $load = $endLoad - $start;
        $display = $endDisplay - $endLoad;
        $total = $endDisplay - $start;
        Log::Debug('Schedule took %s sec to load, %s sec to render. Total %s sec', $load, $display, $total);
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest === "reservations") {
            $this->_presenter->LoadReservations();
        } else {
            $this->_presenter->GetLayout(ServiceLocator::GetServer()->GetUserSession());
        }
    }

    public function GetScheduleId()
    {
        return $this->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    public function SetScheduleId($scheduleId)
    {
        $this->Set('ScheduleId', intval($scheduleId));
    }

    public function SetScheduleName($scheduleName)
    {
        $this->Set('ScheduleName', $scheduleName);
    }

    public function SetSchedules($schedules)
    {
        $this->Set('Schedules', $schedules);
    }

    public function SetFirstWeekday($firstWeekday)
    {
        $this->Set('FirstWeekday', $firstWeekday);
    }

    public function SetResources($resources)
    {
        $this->resourceCount = count($resources);
        $this->Set('Resources', $resources);
    }

    public function SetDailyLayout($dailyLayout)
    {
        $this->Set('DailyLayout', $dailyLayout);
    }

    public function SetDisplayDates($dateRange)
    {
        $this->Set('DisplayDates', $dateRange);
        $this->Set('BoundDates', $dateRange->Dates());
    }

    public function SetSpecificDates($specificDates)
    {
        if (!empty($specificDates)) {
            $this->Set('BoundDates', $specificDates);
        }
        $this->Set('SpecificDates', $specificDates);
    }

    public function SetPreviousNextDates($previousDate, $nextDate)
    {
        $this->Set('PreviousDate', $previousDate);
        $this->Set('NextDate', $nextDate);
    }

    public function GetSelectedDate()
    {
        return $this->server->GetQuerystring(QueryStringKeys::START_DATE);
    }

    public function GetSelectedDates()
    {
        $dates = $this->server->GetQuerystring(QueryStringKeys::START_DATES);
        if (empty($dates)) {
            return [];
        }
        $parseDates = [];
        foreach (explode(',', $dates) as $date) {
            $parseDates[] = Date::Parse($date, ServiceLocator::GetServer()->GetUserSession()->Timezone);
        }

        usort($parseDates, function ($d1, $d2) {
            return $d1->Compare($d2);
        });

        return $parseDates;
    }

    public function ShowInaccessibleResources()
    {
        return Configuration::Instance()->GetKey(
                ConfigKeys::SCHEDULE_SHOW_INACCESSIBLE_RESOURCES,
                new BooleanConverter()
            );
    }

    public function ShowFullWeekToggle($showShowFullWeekToggle)
    {
        $this->Set('ShowFullWeekLink', $showShowFullWeekToggle);
    }

    public function GetShowFullWeek()
    {
        $showFullWeek = $this->GetQuerystring(QueryStringKeys::SHOW_FULL_WEEK);

        return !empty($showFullWeek);
    }

    public function ProcessAction()
    {
        // no-op
    }

    public function SetLayoutResponse($layoutResponse)
    {
        $this->SetJson($layoutResponse);
    }

    public function GetLayoutDate()
    {
        return $this->GetQuerystring(QueryStringKeys::LAYOUT_DATE);
    }

    public function GetScheduleStyle($scheduleId)
    {
        $cookie = $this->server->GetCookie("schedule-style-$scheduleId");
        if ($cookie != null) {
            return $cookie;
        }

        return null;
    }

    public function SetScheduleStyle($style)
    {
        $this->ScheduleStyle = $style;
        $this->Set('CookieName', 'schedule-style-' . $this->GetVar('ScheduleId'));
        $this->Set("ScheduleStyle", $style);
    }

    /**
     * @return int
     */
    public function GetResourceId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    /**
     * @return int[]
     */
    public function GetResourceIds()
    {
        $resourceIds = $this->GetQuerystring(FormKeys::RESOURCE_ID);
        if (empty($resourceIds)) {
            return [];
        }

        if (!is_array($resourceIds)) {
            return [intval($resourceIds)];
        }

        return array_filter($resourceIds, 'intval');
    }

    public function SetResourceGroupTree(ResourceGroupTree $resourceGroupTree)
    {
        $this->Set('ResourceGroupsAsJson', json_encode($resourceGroupTree->GetGroups()));
    }

    public function SetResourceTypes($resourceTypes)
    {
        $this->Set('ResourceTypes', $resourceTypes);
    }

    public function SetResourceCustomAttributes($attributes)
    {
        $this->Set('ResourceAttributes', $attributes);
    }

    public function SetResourceTypeCustomAttributes($attributes)
    {
        $this->Set('ResourceTypeAttributes', $attributes);
    }

    public function FilterSubmitted()
    {
        $k = $this->GetQuerystring(FormKeys::SUBMIT);

        return !empty($k);
    }

    public function GetResourceTypeId()
    {
        return $this->GetQuerystring(FormKeys::RESOURCE_TYPE_ID);
    }

    public function GetMaxParticipants()
    {
        $max = $this->GetQuerystring(FormKeys::MAX_PARTICIPANTS);
        return intval($max);
    }

    public function GetResourceAttributes()
    {
        return AttributeFormParser::GetAttributes($this->GetQuerystring('r' . FormKeys::ATTRIBUTE_PREFIX));
    }

    public function GetResourceTypeAttributes()
    {
        return AttributeFormParser::GetAttributes($this->GetQuerystring('rt' . FormKeys::ATTRIBUTE_PREFIX));
    }

    public function SetFilter($resourceFilter)
    {
        $ownerId = $this->GetOwnerId();
        $participantId = $this->GetParticipantId();
        $ownerText = $this->GetOwnerText();
        $participantText = $this->GetParticipantText();

        $this->Set('ResourceIdFilter', $this->GetResourceId());
        $this->Set('ResourceTypeIdFilter', $resourceFilter->ResourceTypeId);
        $this->Set('MaxParticipantsFilter', $resourceFilter->MinCapacity);
        $this->Set('ResourceIds', $resourceFilter->ResourceIds);

        $this->Set('OwnerId', $ownerId);
        if (!empty($ownerId)) {
            $this->Set('OwnerText', $ownerText);
        }
        $this->Set('ParticipantId', $participantId);
        if (!empty($participantId)) {
            $this->Set('ParticipantText', $participantText);
        }
        $this->_isFiltered = $resourceFilter->HasFilter();
    }

    public function SetSubscriptionUrl(CalendarSubscriptionUrl $subscriptionUrl)
    {
        $this->Set('SubscriptionUrl', $subscriptionUrl);
    }

    public function ShowPermissionError($shouldShow)
    {
        $this->Set('IsAccessible', !$shouldShow);
    }

    public function GetDisplayTimezone(UserSession $user, Schedule $schedule)
    {
        return $user->Timezone;
    }

    public function FilterCleared()
    {
        return $this->GetQuerystring('clearFilter') == '1';
    }

    public function BindScheduleAvailability($availability, $tooEarly)
    {
        $this->Set('ScheduleAvailabilityEarly', $tooEarly);
        $this->Set('ScheduleAvailabilityLate', !$tooEarly);
        $this->Set('ScheduleAvailabilityStart', $availability->GetBegin());
        $this->Set('ScheduleAvailabilityEnd', $availability->GetEnd());
        $this->Set('HideSchedule', true);
    }

    public function BindViewableResourceReservations($resourceIds)
    {
        $this->Set('CanViewResourceReservations', $resourceIds);
    }

    public function GetReservationRequest()
    {
        $timezone = $this->server->GetUserSession()->Timezone;

        $specificDatesForm = $this->GetForm(FormKeys::SPECIFIC_DATES);
        $specificDates = [];
        if (!empty($specificDatesForm) && is_array($specificDatesForm)) {
            foreach ($specificDatesForm as $date) {
                $specificDates[] = Date::Parse($date, $timezone);
            }
        }
        $resourceIds = [];
        $resourceIdsForm = $this->GetForm(FormKeys::RESOURCE_ID);
        if (!empty($resourceIdsForm) && is_array($resourceIdsForm)) {
            foreach ($resourceIdsForm as $id) {
                $resourceIds[] = intval($id);
            }
        }
        $builder = new LoadReservationRequestBuilder();
        return $builder
            ->WithRange(Date::Parse($this->GetForm(FormKeys::BEGIN_DATE), $timezone), Date::Parse($this->GetForm(FormKeys::END_DATE), $timezone))
            ->WithResources($resourceIds)
            ->WithScheduleId($this->GetForm(FormKeys::SCHEDULE_ID))
            ->WithSpecificDates($specificDates)
            ->WithOwner($this->GetForm(FormKeys::USER_ID))
            ->WithParticipant($this->GetForm(FormKeys::PARTICIPANT_ID))
            ->Build();
    }

    /**
     * @param ReservationListItem[] $items
     */
    public function BindReservations($items)
    {
        $itemsAsJson = [];
        foreach ($items as $item) {
            $dtos = $item->AsDto($this->server->GetUserSession());
            foreach ($dtos as $dto) {
                $itemsAsJson[] = $dto;
            }
        }
        $this->SetJson($itemsAsJson);
    }

    public function GetOwnerId()
    {
        $id = $this->GetQuerystring(FormKeys::USER_ID);
        if (empty($id)) {
            return null;
        }

        return intval($id);
    }

    public function GetParticipantId()
    {
        $id = $this->GetQuerystring(FormKeys::PARTICIPANT_ID);
        if (empty($id)) {
            return null;
        }

        return intval($id);
    }

    public function GetOwnerText()
    {
        return $this->GetQuerystring(FormKeys::OWNER_TEXT);
    }

    public function GetParticipantText()
    {
        return $this->GetQuerystring(FormKeys::PARTICIPANT_TEXT);
    }
}
