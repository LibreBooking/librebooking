<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenter.php');

interface IReservationPage extends IPage
{
    /**
     * @param $startPeriods array|SchedulePeriod[]
     * @param $endPeriods array|SchedulePeriod[]
     * @parma $lockDates bool
     */
    public function BindPeriods($startPeriods, $endPeriods, $lockPeriods);

    /**
     * @param $resources array|ResourceDto[]
     */
    public function BindAvailableResources($resources);

    /**
     * @param $accessories Accessory[]
     */
    public function BindAvailableAccessories($accessories);

    /**
     * @param $groups ResourceGroupTree
     */
    public function BindResourceGroups($groups);

    /**
     * @param SchedulePeriod $selectedStart
     * @param Date $startDate
     */
    public function SetSelectedStart(SchedulePeriod $selectedStart, Date $startDate);

    /**
     * @param SchedulePeriod $selectedEnd
     * @param Date $endDate
     */
    public function SetSelectedEnd(SchedulePeriod $selectedEnd, Date $endDate);

    /**
     * @param $repeatTerminationDate Date
     */
    public function SetRepeatTerminationDate($repeatTerminationDate);

    /**
     * @param UserDto $user
     */
    public function SetReservationUser(UserDto $user);

    /**
     * @param IResource $resource
     */
    public function SetReservationResource($resource);

    /**
     * @param int $scheduleId
     */
    public function SetScheduleId($scheduleId);

    /**
     * @param ReservationUserView[] $participants
     */
    public function SetParticipants($participants);

    /**
     * @param ReservationUserView[] $invitees
     */
    public function SetInvitees($invitees);

    /**
     * @param $accessories ReservationAccessory[]|array
     */
    public function SetAccessories($accessories);

    /**
     * @param $attachments ReservationAttachmentView[]|array
     */
    public function SetAttachments($attachments);

    /**
     * @param $canChangeUser
     */
    public function SetCanChangeUser($canChangeUser);

    /**
     * @param bool $canShowAdditionalResources
     */
    public function ShowAdditionalResources($canShowAdditionalResources);

    /**
     * @param bool $canShowUserDetails
     */
    public function ShowUserDetails($canShowUserDetails);

    /**
     * @param bool $shouldShow
     */
    public function SetShowParticipation($shouldShow);

    /**
     * @param bool $showReservationDetails
     */
    public function ShowReservationDetails($showReservationDetails);

    /**
     * @param bool $isHidden
     */
    public function HideRecurrence($isHidden);

    /**
     * @param bool $allowParticipation
     */
    public function SetAllowParticipantsToJoin($allowParticipation);

    /**
     * @param int $reminderValue
     * @param ReservationReminderInterval $reminderInterval
     */
    public function SetStartReminder($reminderValue, $reminderInterval);

    /**
     * @param int $reminderValue
     * @param ReservationReminderInterval $reminderInterval
     */
    public function SetEndReminder($reminderValue, $reminderInterval);

    /**
     * @param DateRange $availability
     */
    public function SetAvailability(DateRange $availability);

    /**
     * @param int $weekday
     */
    public function SetFirstWeekday($weekday);

    public function MakeUnavailable();

    /**
     * @return bool
     */
    public function IsUnavailable();

    /**
     * @param TermsOfService $termsOfService
     */
    public function SetTerms($termsOfService);

    /**
     * @param bool $accepted
     */
    public function SetTermsAccepted($accepted);

    /**
     * @param int $maximum
     */
    public function SetMaximumResources($maximum);
}

abstract class ReservationPage extends Page implements IReservationPage
{
    protected $presenter;
    protected $available = true;

    /**
     * @var PermissionServiceFactory
     */
    protected $permissionServiceFactory;

    public function __construct($title = null)
    {
        parent::__construct($title);

        if (is_null($this->permissionServiceFactory)) {
            $this->permissionServiceFactory = new PermissionServiceFactory();
        }
    }

    /**
     * @return IReservationPresenter
     */
    abstract protected function GetPresenter();

    /**
     * @return string
     */
    abstract protected function GetTemplateName();

    /**
     * @return string
     */
    abstract protected function GetReservationAction();

    public function PageLoad()
    {
        $this->presenter = $this->GetPresenter();
        $this->presenter->PageLoad();

        $this->Set('ReturnUrl', $this->GetReturnUrl());
        $this->Set('ReservationAction', $this->GetReservationAction());
        $this->Set('MaxUploadSize', UploadedFile::GetMaxSize());
        $this->Set('MaxUploadCount', UploadedFile::GetMaxUploadCount());
        $config = Configuration::Instance();
        $this->Set('UploadsEnabled', $config->GetKey(ConfigKeys::UPLOAD_RESERVATION_ATTACHMENTS_ENABLED, new BooleanConverter()));
        $this->Set('AllowParticipation', !$config->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter()));
        $this->Set('AllowGuestParticipation', $config->GetKey(ConfigKeys::RESERVATION_ALLOW_GUEST_PARTICIPATION, new BooleanConverter()));
        $remindersEnabled = $config->GetKey(ConfigKeys::RESERVATION_REMINDERS_ENABLED, new BooleanConverter());
        $emailEnabled = $config->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter());
        $this->Set('RemindersEnabled', $remindersEnabled && $emailEnabled);

        $this->Set('RepeatEveryOptions', range(1, 20));
        $this->Set(
            'RepeatOptions',
            [
                                          'none' => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
                                          'daily' => ['key' => 'Daily', 'everyKey' => 'days'],
                                          'weekly' => ['key' => 'Weekly', 'everyKey' => 'weeks'],
                                          'monthly' => ['key' => 'Monthly', 'everyKey' => 'months'],
                                          'yearly' => ['key' => 'Yearly', 'everyKey' => 'years'],
                                  ]
        );
        $this->Set(
            'DayNames',
            [
                                     0 => 'DaySundayAbbr',
                                     1 => 'DayMondayAbbr',
                                     2 => 'DayTuesdayAbbr',
                                     3 => 'DayWednesdayAbbr',
                                     4 => 'DayThursdayAbbr',
                                     5 => 'DayFridayAbbr',
                                     6 => 'DaySaturdayAbbr',
                             ]
        );

        $this->Set('TitleRequired', $config->GetKey(ConfigKeys::RESERVATION_TITLE_REQUIRED, new BooleanConverter()));
        $this->Set('DescriptionRequired', $config->GetKey(ConfigKeys::RESERVATION_DESCRIPTION_REQUIRED, new BooleanConverter()));

        $this->Set('CreditsEnabled', $config->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));

        if ($this->IsUnavailable()) {
            $this->RedirectToError(ErrorMessages::RESERVATION_NOT_AVAILABLE);
            return;
        }

        $this->Display($this->GetTemplateName());
    }

    public function BindPeriods($startPeriods, $endPeriods, $lockPeriods)
    {
        $this->Set('StartPeriods', $startPeriods);
        $this->Set('EndPeriods', $endPeriods);
        $this->Set('LockPeriods', $lockPeriods);
    }

    public function BindAvailableResources($resources)
    {
        $this->Set('AvailableResources', $resources);
    }

    public function ShowAdditionalResources($shouldShow)
    {
        $this->Set('ShowAdditionalResources', $shouldShow);
    }

    public function BindAvailableAccessories($accessories)
    {
        $this->Set('AvailableAccessories', $accessories);
    }

    public function BindResourceGroups($groups)
    {
        $this->Set('ResourceGroupsAsJson', json_encode($groups->GetGroups()));
    }

    public function SetSelectedStart(SchedulePeriod $selectedStart, Date $startDate)
    {
        $this->Set('SelectedStart', $selectedStart);
        $this->Set('StartDate', $startDate);
    }

    public function SetSelectedEnd(SchedulePeriod $selectedEnd, Date $endDate)
    {
        $this->Set('SelectedEnd', $selectedEnd);
        $this->Set('EndDate', $endDate);
    }

    public function SetReservationUser(UserDto $user)
    {
        $this->Set('ReservationUserName', $user->FullName());
        $this->Set('UserId', $user->Id());
        $this->Set('CurrentUserCredits', $user->CurrentCreditCount());
    }

    public function SetReservationResource($resource)
    {
        $this->Set('ResourceName', $resource->GetName());
        $this->Set('ResourceId', $resource->GetId());
        $this->Set('Resource', $resource);
    }

    public function SetScheduleId($scheduleId)
    {
        $this->Set('ScheduleId', $scheduleId);
    }

    public function SetRepeatTerminationDate($repeatTerminationDate)
    {
        $this->Set('RepeatTerminationDate', $repeatTerminationDate);
    }

    public function SetParticipants($participants)
    {
        $this->Set('Participants', $participants);
    }

    public function SetInvitees($invitees)
    {
        $this->Set('Invitees', $invitees);
    }

    public function SetAllowParticipantsToJoin($allowParticipantsToJoin)
    {
        $this->Set('AllowParticipantsToJoin', $allowParticipantsToJoin);
    }

    public function SetAccessories($accessories)
    {
        $this->Set('Accessories', $accessories);
    }

    public function SetAttachments($attachments)
    {
        $this->Set('Attachments', $attachments);
    }

    public function SetCanChangeUser($canChangeUser)
    {
        $this->Set('CanChangeUser', $canChangeUser);
    }

    public function ShowUserDetails($canShowUserDetails)
    {
        $this->Set('ShowUserDetails', $canShowUserDetails);
    }

    public function SetShowParticipation($shouldShow)
    {
        $this->Set('ShowParticipation', $shouldShow);
    }

    public function ShowReservationDetails($showReservationDetails)
    {
        $this->Set('ShowReservationDetails', $showReservationDetails);
    }

    public function HideRecurrence($isHidden)
    {
        $this->Set('HideRecurrence', $isHidden);
    }

    protected function GetReturnUrl()
    {
        $redirect = $this->GetQuerystring(QueryStringKeys::REDIRECT);
        if (!empty($redirect)) {
            return $redirect;
        }
        return $this->GetLastPage(Pages::SCHEDULE);
    }

    protected function LoadInitializerFactory()
    {
        $userRepository = new UserRepository();
        return new ReservationInitializerFactory(
            new ScheduleRepository(),
            $userRepository,
            new ResourceService(
                new ResourceRepository(),
                $this->permissionServiceFactory->GetPermissionService(),
                new AttributeService(new AttributeRepository()),
                $userRepository,
                new AccessoryRepository()
            ),
            new ReservationAuthorization(AuthorizationServiceFactory::GetAuthorizationService())
        );
    }

    public function SetStartReminder($reminderValue, $reminderInterval)
    {
        $this->Set('ReminderTimeStart', $reminderValue);
        $this->Set('ReminderIntervalStart', $reminderInterval);
    }

    public function SetEndReminder($reminderValue, $reminderInterval)
    {
        $this->Set('ReminderTimeEnd', $reminderValue);
        $this->Set('ReminderIntervalEnd', $reminderInterval);
    }

    public function SetAvailability(DateRange $availability)
    {
        $this->Set('AvailabilityStart', $availability->GetBegin());
        $this->Set('AvailabilityEnd', $availability->GetEnd());
    }

    public function SetFirstWeekday($weekday)
    {
        $this->Set('FirstWeekday', $weekday);
    }

    public function MakeUnavailable()
    {
        $this->available = false;
    }

    public function IsUnavailable()
    {
        return !$this->available;
    }

    public function SetTerms($termsOfService)
    {
        $this->Set('Terms', $termsOfService);
    }

    public function SetMaximumResources($maximum)
    {
        $this->Set('MaximumResources', $this->server->GetUserSession()->IsAdmin ? 0 : $maximum);
    }
}
