<?php

interface IReservationComponentBinder
{
    public function Bind(IReservationComponentInitializer $initializer);
}

class ReservationDateBinder implements IReservationComponentBinder
{
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(IScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function Bind(IReservationComponentInitializer $initializer)
    {
        $timezone = $initializer->GetTimezone();
        $reservationDate = $initializer->GetReservationDate();
        $requestedEndDate = $initializer->GetEndDate();
        $requestedStartDate = $initializer->GetStartDate();
        $requestedScheduleId = $initializer->GetScheduleId();

        $requestedDate = ($reservationDate == null) ? Date::Now()->ToTimezone($timezone) : $reservationDate->ToTimezone($timezone);

        $startDate = ($requestedStartDate == null) ? $requestedDate : $requestedStartDate->ToTimezone($timezone);
        $endDate = ($requestedEndDate == null) ? $requestedDate : $requestedEndDate->ToTimezone($timezone);

        if ($initializer->IsNew()) {
            $resource = $initializer->PrimaryResource();

            if ($resource->GetMinimumLength() != null && !$resource->GetMinimumLength()->Interval()->IsNull() &&
                !DateDiff::BetweenDates($startDate, $endDate)->GreaterThan($resource->GetMinimumLength()->Interval())) {
                $endDate = $startDate->ApplyDifference($resource->GetMinimumLength()->Interval());
            }
        }

        $layout = $this->scheduleRepository->GetLayout($requestedScheduleId, new ReservationLayoutFactory($timezone));
        $schedule = $this->scheduleRepository->LoadById($requestedScheduleId);

        $startPeriods = $this->GetStartPeriods($layout, $startDate);
        $endPeriods = $this->GetEndPeriods($layout, $startDate, $endDate);

        $initializer->SetDates($startDate, $endDate, $startPeriods, $endPeriods, $schedule->GetWeekdayStart(), $layout->UsesCustomLayout());

        $hideRecurrence = (!$initializer->CurrentUser()->IsAdmin &&
            Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_PREVENT_RECURRENCE, new BooleanConverter())
            || $layout->UsesCustomLayout());

        $initializer->HideRecurrence($hideRecurrence);

        if ($schedule->HasAvailability()) {
            $initializer->SetAvailability($schedule->GetAvailability());
        }
    }

    /**
     * @param IScheduleLayout $layout
     * @param Date $startDate
     * @return SchedulePeriod[]
     */
    protected function GetStartPeriods($layout, &$startDate)
    {
        $startPeriods = $layout->GetLayout($startDate, true);

        return $this->GetPeriods($startPeriods, $layout, $startDate);
    }

    /**
     * @param IScheduleLayout $layout
     * @param Date $startDate
     * @param Date $endDate
     * @return SchedulePeriod[]
     */
    protected function GetEndPeriods($layout, $startDate, &$endDate)
    {
        $endPeriods = $layout->GetLayout($endDate, true);
        if (count($endPeriods) == 0 && $startDate->AddDays(1)->Equals($endDate)) {
            // no periods on the next day, return midnight to let the reservation end at the top of the hour
            return [new SchedulePeriod($endDate->SetTimeString('00:00'), $endDate->SetTimeString('00:00', true))];
        }
        return $this->GetPeriods($endPeriods, $layout, $endDate);
    }

    /**
     * @param SchedulePeriod[] $startPeriods
     * @param IScheduleLayout $layout
     * @param Date $startDate
     * @param int $iteration
     * @return array|SchedulePeriod[]
     */
    private function GetPeriods($startPeriods, $layout, &$startDate, $iteration = 0)
    {
        if (count($startPeriods) > 1 && $startPeriods[0]->Begin()->Compare($startPeriods[1]->Begin()) > 0) {
            $period = array_shift($startPeriods);
            $startPeriods[] = $period;
        }

        if (count($startPeriods) == 0 && $iteration < 7) {
            $startDate = $startDate->AddDays(1);
            return $this->GetPeriods($layout->GetLayout($startDate, true), $layout, $startDate, ++$iteration);
        }
        return $startPeriods;
    }
}

class ReservationUserBinder implements IReservationComponentBinder
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    /**
     * @param IUserRepository $userRepository
     * @param IReservationAuthorization $reservationAuthorization
     */
    public function __construct($userRepository, $reservationAuthorization)
    {
        $this->userRepository = $userRepository;
        $this->reservationAuthorization = $reservationAuthorization;
    }

    public function Bind(IReservationComponentInitializer $initializer)
    {
        $userId = $initializer->GetOwnerId();
        $currentUser = $initializer->CurrentUser();
        $canChangeUser = $this->reservationAuthorization->CanChangeUsers($currentUser);

        $initializer->SetCanChangeUser($canChangeUser);

        $reservationUser = $this->userRepository->GetById($userId);
        $initializer->SetReservationUser($reservationUser);

        $hideUser = Configuration::Instance()->GetKey(
            ConfigKeys::PRIVACY_HIDE_USER_DETAILS,
            new BooleanConverter()
        );

        $initializer->ShowUserDetails(!$hideUser || $currentUser->IsAdmin || $userId == $currentUser->UserId);
        $initializer->ShowReservationDetails(true);
        $initializer->SetShowParticipation(!$hideUser || $currentUser->IsAdmin || $currentUser->IsGroupAdmin);
    }
}

class ReservationResourceBinder implements IReservationComponentBinder
{
    /**
     * @var IResourceService
     */
    private $resourceService;
    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    public function __construct(IResourceService $resourceService, IScheduleRepository $scheduleRepository)
    {
        $this->resourceService = $resourceService;
        $this->scheduleRepository = $scheduleRepository;
    }

    public function Bind(IReservationComponentInitializer $initializer)
    {
        $requestedScheduleId = $initializer->GetScheduleId();
        $requestedResourceId = $initializer->GetResourceId();
        $groups = $this->resourceService->GetResourceGroups($requestedScheduleId, $initializer->CurrentUser());

        $resources = $groups->GetAllResources();
        if (empty($requestedResourceId) && count($resources) > 0) {
            $first = reset($resources);
            $requestedResourceId = $first->Id;
        }

        $bindableResourceData = $this->GetBindableResourceData($resources, $requestedResourceId);

        if ($bindableResourceData->NumberAccessible <= 0) {
            $initializer->RedirectToError(ErrorMessages::INSUFFICIENT_PERMISSIONS);
            return;
        }

        $schedule = $this->scheduleRepository->LoadById($requestedScheduleId);
        $initializer->BindResourceGroups($groups);
        $initializer->BindAvailableResources($resources);
        $accessories = $this->resourceService->GetAccessories();
        $initializer->BindAvailableAccessories($accessories);
        $initializer->ShowAdditionalResources($bindableResourceData->NumberAccessible > 1);
        $initializer->SetReservationResource($bindableResourceData->ReservationResource);
        $initializer->SetMaximumResources($schedule->GetMaxResourcesPerReservation());
    }

    /**
     * @param $resources array|ResourceDto[]
     * @param $requestedResourceId int
     * @return BindableResourceData
     */
    private function GetBindableResourceData($resources, $requestedResourceId)
    {
        $bindableResourceData = new BindableResourceData();

        if (count($resources) > 0) {
            $bindableResourceData->SetReservationResource(reset($resources));
        }

        /** @var ResourceDto $resource */
        foreach ($resources as $resource) {
            $bindableResourceData->AddAvailableResource($resource);
            if ($resource->Id == $requestedResourceId) {
                $bindableResourceData->SetReservationResource($resource);
            }
        }

        return $bindableResourceData;
    }
}

class ReservationDetailsBinder implements IReservationComponentBinder
{
    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    /**
     * @var IExistingReservationPage
     */
    private $page;

    /**
     * @var ReservationView
     */
    private $reservationView;

    /**
     * @var IPrivacyFilter
     */
    private $privacyFilter;

    public function __construct(
        IReservationAuthorization $reservationAuthorization,
        IExistingReservationPage $page,
        ReservationView $reservationView,
        IPrivacyFilter $privacyFilter
    ) {
        $this->reservationAuthorization = $reservationAuthorization;
        $this->page = $page;
        $this->reservationView = $reservationView;
        $this->privacyFilter = $privacyFilter;
    }

    public function Bind(IReservationComponentInitializer $initializer)
    {
        $this->page->SetAdditionalResources($this->reservationView->AdditionalResourceIds);
        $this->page->SetTitle($this->reservationView->Title);
        $this->page->SetDescription($this->reservationView->Description);
        $this->page->SetReferenceNumber($this->reservationView->ReferenceNumber);
        $this->page->SetReservationId($this->reservationView->ReservationId);
        $this->page->SetSeriesId($this->reservationView->SeriesId);

        $this->page->SetIsRecurring($this->reservationView->IsRecurring());
        $this->page->SetRepeatType($this->reservationView->RepeatType);
        $this->page->SetRepeatInterval($this->reservationView->RepeatInterval);
        $this->page->SetRepeatMonthlyType($this->reservationView->RepeatMonthlyType);
        $this->page->SetCustomRepeatDates($this->reservationView->CustomRepeatDates);

        if ($this->reservationView->RepeatTerminationDate != null && $this->reservationView->RepeatTerminationDate->Timestamp() != 0) {
            $this->page->SetRepeatTerminationDate($this->reservationView->RepeatTerminationDate->ToTimezone($initializer->GetTimezone()));
        } else {
            $this->page->SetRepeatTerminationDate($this->reservationView->EndDate);
        }
        $this->page->SetRepeatWeekdays($this->reservationView->RepeatWeekdays);

        $participants = $this->reservationView->Participants;
        $invitees = $this->reservationView->Invitees;

        $this->page->SetParticipants($participants);
        $this->page->SetInvitees($invitees);

        $this->page->SetParticipatingGuests($this->reservationView->ParticipatingGuests);
        $this->page->SetInvitedGuests($this->reservationView->InvitedGuests);

        $this->page->SetAllowParticipantsToJoin($this->reservationView->AllowParticipation);

        $this->page->SetAccessories($this->reservationView->Accessories);

        $currentUser = $initializer->CurrentUser();

        $this->page->SetCurrentUserParticipating($this->IsCurrentUserParticipating($currentUser->UserId));
        $this->page->SetCurrentUserInvited($this->IsCurrentUserInvited($currentUser->UserId));
        $this->UserResourcePermissions($currentUser->UserId);

        $this->page->SetCanAlterParticipation($this->reservationView->EndDate->GreaterThan(Date::Now()));

        $canBeEdited = $this->reservationAuthorization->CanEdit($this->reservationView, $currentUser);
        $this->page->SetIsEditable($canBeEdited);
        $this->page->SetIsApprovable($this->reservationAuthorization->CanApprove($this->reservationView, $currentUser));
        $this->page->SetRequiresApproval($this->reservationView->RequiresApproval());

        $this->page->SetAttachments($this->reservationView->Attachments);

        $showUser = $this->privacyFilter->CanViewUser($initializer->CurrentUser(), $this->reservationView);
        $showDetails = $this->privacyFilter->CanViewDetails($initializer->CurrentUser(), $this->reservationView);

        $initializer->ShowUserDetails($showUser);
        $initializer->ShowReservationDetails($showDetails);

        if (!empty($this->reservationView->StartReminder)) {
            $this->page->SetStartReminder(
                $this->reservationView->StartReminder->GetValue(),
                $this->reservationView->StartReminder->GetInterval()
            );
        }
        if (!empty($this->reservationView->EndReminder)) {
            $this->page->SetEndReminder(
                $this->reservationView->EndReminder->GetValue(),
                $this->reservationView->EndReminder->GetInterval()
            );
        }

        $this->page->SetCheckInRequired(false);
        $this->page->SetCheckOutRequired(false);
        $this->page->SetAutoReleaseMinutes(null);
        if ($canBeEdited) {
            $this->SetCheckinRequired();
            $this->SetCheckoutRequired();
            $this->SetAutoReleaseMinutes();
        }

        $this->page->SetTermsAccepted($this->reservationView->HasAcceptedTerms);
    }

    private function IsCurrentUserParticipating($currentUserId)
    {
        /** @var ReservationUserView $user */
        foreach ($this->reservationView->Participants as $user) {
            if ($user->UserId == $currentUserId) {
                return true;
            }
        }
        return false;
    }

    private function IsCurrentUserInvited($currentUserId)
    {
        /** @var ReservationUserView $user */
        foreach ($this->reservationView->Invitees as $user) {
            if ($user->UserId == $currentUserId) {
                return true;
            }
        }
        return false;
    }

    private function SetCheckinRequired()
    {
        $this->page->SetCheckInRequired($this->reservationView->IsCheckinAvailable());
    }

    private function SetCheckoutRequired()
    {
        $this->page->SetCheckOutRequired($this->reservationView->IsCheckoutAvailable());
    }

    private function SetAutoReleaseMinutes()
    {
        $minAutoReleaseMinutes = null;
        if ($this->reservationView->CheckinDate->ToString() == '') {
            $minAutoReleaseMinutes = $this->reservationView->AutoReleaseMinutes();
        }
        $this->page->SetAutoReleaseMinutes($minAutoReleaseMinutes);
    }

    /**
     * Gets the resources the user has permissions (full access and view only permissions)
     * This is used to block a user from seeing reservation details if he has no permissions to it's resources
     */
    private function UserResourcePermissions($userId)
    {
        $resourceRepo = new ResourceRepository();
        $resourceIds = [];

        $resourceIds = $resourceRepo->GetUserResourcePermissions($userId);

        $resourceIds = $resourceRepo->GetUserGroupResourcePermissions($userId,$resourceIds);

        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin){
            $resourceIds = $resourceRepo->GetResourceAdminResourceIds($userId, $resourceIds);
        }

        if (ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin){
            $resourceIds = $resourceRepo->GetScheduleAdminResourceIds($userId, $resourceIds);
        }

        $this->page->BindViewableResourceReservations($resourceIds);
    }
}
