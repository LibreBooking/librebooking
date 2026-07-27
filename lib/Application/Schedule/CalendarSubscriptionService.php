<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

interface ICalendarSubscriptionService
{
    /**
     * @param string $publicResourceId
     * @return BookableResource
     */
    public function GetResource($publicResourceId);

    /**
     * @param string $publicScheduleId
     * @return Schedule
     */
    public function GetSchedule($publicScheduleId);

    /**
     * @param string $publicUserId
     * @return User
     */
    public function GetUser($publicUserId);

    /**
     * @param int $userId
     * @param null|int $resourceId
     * @param null|int $scheduleId
     * @return CalendarSubscriptionDetails
     */
    public function ForUser($userId, $resourceId = null, $scheduleId = null);

    /**
     * @param int $resourceId
     * @return CalendarSubscriptionDetails
     */
    public function ForResource($resourceId);

    /**
     * @param int $scheduleId
     * @return CalendarSubscriptionDetails
     */
    public function ForSchedule($scheduleId);

    /**
     * @param string $publicResourceGroupId
     * @return int[]
     */
    public function GetResourcesInGroup($publicResourceGroupId);

    public function GetResourceGroup(string $publicResourceGroupId): ?ResourceGroup;

    public function ResolveCalendarName(
        ?string $publicScheduleId,
        ?string $publicResourceId,
        ?string $publicResourceGroupId,
        ?string $publicUserId = null
    ): ?string;
}

class CalendarSubscriptionDetails
{
    /**
     * @var bool
     */
    private $isAllowed;

    /**
     * @var CalendarSubscriptionUrl
     */
    private $url;

    /**
     * @param bool $isAllowed
     * @param null|CalendarSubscriptionUrl $url
     */
    public function __construct($isAllowed, $url = null)
    {
        $this->isAllowed = $isAllowed;
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function IsAllowed()
    {
        return $this->isAllowed;
    }

    /**
     * @return bool
     */
    public function IsEnabled()
    {
        if (!Configuration::Instance()->GetKey(ConfigKeys::ICS_ENABLED, new BooleanConverter())) {
            return false;
        }
        $key = Configuration::Instance()->GetKey(ConfigKeys::ICS_SUBSCRIPTION_KEY);
        return !empty($key);
    }

    /**
     * @return string
     */
    public function Url()
    {
        if (is_null($this->url)) {
            return null;
        }
        return $this->url->__toString();
    }
}

class CalendarSubscriptionService implements ICalendarSubscriptionService
{
    private $cache = [];
    private $groupCache = [];

    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(
        IUserRepository $userRepository,
        IResourceRepository $resourceRepository,
        IScheduleRepository $scheduleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->resourceRepository = $resourceRepository;
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * @param string $publicResourceId
     * @return BookableResource
     */
    public function GetResource($publicResourceId)
    {
        if (!array_key_exists($publicResourceId, $this->cache)) {
            $resource = $this->resourceRepository->LoadByPublicId($publicResourceId);
            $this->cache[$publicResourceId] = $resource;
        }

        return $this->cache[$publicResourceId];
    }

    /**
     * @param string $publicScheduleId
     * @return Schedule
     */
    public function GetSchedule($publicScheduleId)
    {
        if (!array_key_exists($publicScheduleId, $this->cache)) {
            $schedule = $this->scheduleRepository->LoadByPublicId($publicScheduleId);
            $this->cache[$publicScheduleId] = $schedule;
        }

        return $this->cache[$publicScheduleId];
    }

    /**
     * @param string $publicUserId
     * @return User
     */
    public function GetUser($publicUserId)
    {
        if (!array_key_exists($publicUserId, $this->cache)) {
            $user = $this->userRepository->LoadByPublicId($publicUserId);
            $this->cache[$publicUserId] = $user;
        }

        return $this->cache[$publicUserId];
    }

    public function GetResourcesInGroup($publicResourceGroupId)
    {
        if (!array_key_exists($publicResourceGroupId, $this->cache)) {
            $group = $this->LoadResourceGroup($publicResourceGroupId);

            if ($group == null) {
                return [];
            }

            $groups = $this->resourceRepository->GetResourceGroups();
            $this->cache[$publicResourceGroupId] = $groups->GetResourceIds($group->id);
        }

        return $this->cache[$publicResourceGroupId];
    }

    public function GetResourceGroup(string $publicResourceGroupId): ?ResourceGroup
    {
        return $this->LoadResourceGroup($publicResourceGroupId);
    }

    public function ResolveCalendarName(
        ?string $publicScheduleId,
        ?string $publicResourceId,
        ?string $publicResourceGroupId,
        ?string $publicUserId = null
    ): ?string {
        $calendarName = null;

        if (!empty($publicScheduleId)) {
            $calendarName = $this->GetSchedule($publicScheduleId)->GetName();
        }

        if (!empty($publicResourceId)) {
            $calendarName = $this->GetResource($publicResourceId)->GetName();
        }

        if (!empty($publicResourceGroupId)) {
            $resourceGroup = $this->GetResourceGroup($publicResourceGroupId);
            if ($resourceGroup !== null && $resourceGroup->name !== null && $resourceGroup->name !== '') {
                $calendarName = $resourceGroup->name;
            }
        }

        if (!empty($publicUserId)) {
            $myCalendarName = Resources::GetInstance()->GetString('MyCalendar');
            $calendarName = empty($calendarName) ? $myCalendarName : sprintf('%s - %s', $myCalendarName, $calendarName);
        }

        return $calendarName;
    }

    private function LoadResourceGroup(string $publicResourceGroupId): ?ResourceGroup
    {
        if (!array_key_exists($publicResourceGroupId, $this->groupCache)) {
            $this->groupCache[$publicResourceGroupId] = $this->resourceRepository->LoadResourceGroupByPublicId($publicResourceGroupId);
        }

        return $this->groupCache[$publicResourceGroupId];
    }

    public function ForUser($userId, $resourceId = null, $scheduleId = null)
    {
        $user = $this->userRepository->LoadById($userId);

        $resourcePublicId = null;
        $schedulePublicId = null;

        if (!empty($scheduleId)) {
            $schedule = $this->scheduleRepository->LoadById($scheduleId);
            if ($schedule != null) {
                $schedulePublicId = $schedule->GetPublicId();
            }
        }
        if (!empty($resourceId)) {
            $resource = $this->resourceRepository->LoadById($resourceId);
            if ($resource != null) {
                $resourcePublicId = $resource->GetPublicId();
            }
        }

        return new CalendarSubscriptionDetails(
            $user->GetIsCalendarSubscriptionAllowed(),
            new CalendarSubscriptionUrl($user->GetPublicId(), $schedulePublicId, $resourcePublicId)
        );
    }

    /**
     * @param int $resourceId
     * @return CalendarSubscriptionDetails
     */
    public function ForResource($resourceId)
    {
        $resource = $this->resourceRepository->LoadById($resourceId);

        if ($resource == null) {
            return new CalendarSubscriptionDetails(false);
        }

        return new CalendarSubscriptionDetails(
            $resource->GetIsCalendarSubscriptionAllowed(),
            new CalendarSubscriptionUrl(null, null, $resource->GetPublicId())
        );
    }

    /**
     * @param int $scheduleId
     * @return CalendarSubscriptionDetails
     */
    public function ForSchedule($scheduleId)
    {
        $schedule = $this->scheduleRepository->LoadById($scheduleId);

        if ($schedule == null) {
            return new CalendarSubscriptionDetails(false);
        }

        return new CalendarSubscriptionDetails(
            $schedule->GetIsCalendarSubscriptionAllowed(),
            new CalendarSubscriptionUrl(null, $schedule->GetPublicId(), null)
        );
    }
}
