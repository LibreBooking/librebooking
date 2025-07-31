<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationComponentBinder.php');

require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');

interface IReservationComponentInitializer
{
    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return int
     */
    public function GetScheduleId();

    /**
     * @return Date
     */
    public function GetStartDate();

    /**
     * @return Date
     */
    public function GetEndDate();

    /**
     * @return Date
     */
    public function GetReservationDate();

    /**
     * @return int
     */
    public function GetOwnerId();

    /**
     * @return string
     */
    public function GetTimezone();

    /**
     * @param Date $startDate
     * @param Date $endDate
     * @param array|SchedulePeriod[] $startPeriods
     * @param array|SchedulePeriod[] $endPeriods
     * @param int $firstWeekday
     * @parma bool $lockDates
     */
    public function SetDates(Date $startDate, Date $endDate, $startPeriods, $endPeriods, $firstWeekday, $lockDates = false);

    /**
     * @return UserSession
     */
    public function CurrentUser();

    /**
     * @return ResourceDto
     */
    public function PrimaryResource();

    /**
     * @param $canChangeUser bool
     */
    public function SetCanChangeUser($canChangeUser);

    /**
     * @param $reservationUser UserDto
     */
    public function SetReservationUser($reservationUser);

    /**
     * @param $showUserDetails bool
     */
    public function ShowUserDetails($showUserDetails);

    /**
     * @param $shouldShow bool
     */
    public function SetShowParticipation($shouldShow);

    /**
     * @param $showReservationDetails bool
     */
    public function ShowReservationDetails($showReservationDetails);

    /**
     * @param $resources array|IBookableResource[]
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
     * @param $shouldShow bool
     */
    public function ShowAdditionalResources($shouldShow);

    /**
     * @param $resource ResourceDto
     */
    public function SetReservationResource($resource);

    /**
     * @param $maximum int
     */
    public function SetMaximumResources($maximum);

    /**
     * @param $attribute CustomAttribute
     * @param $value mixed
     */
    public function AddAttribute($attribute, $value);

    /**
     * @param ErrorMessages|int $errorMessageId
     */
    public function RedirectToError($errorMessageId);

    /**
     * @param bool $isHidden
     */
    public function HideRecurrence($isHidden);

    /**
     * @return bool
     */
    public function IsNew();

    /**
     * @param DateRange $availability
     */
    public function SetAvailability(DateRange $availability);
}

abstract class ReservationInitializerBase implements IReservationInitializer, IReservationComponentInitializer
{
    /**
     * @var ResourceDto
     */
    protected $primaryResource;

    /**
     * @var IReservationPage
     */
    protected $basePage;

    /**
     * @var IReservationComponentBinder
     */
    protected $userBinder;

    /**
     * @var IReservationComponentBinder
     */
    protected $dateBinder;

    /**
     * @var IReservationComponentBinder
     */
    protected $resourceBinder;

    /**
     * @var int
     */
    protected $currentUserId;

    /**
     * @var UserSession
     */
    protected $currentUser;

    /**
     * @var array|LBAttribute[]
     */
    private $customAttributes = [];
    /**
     * @var ITermsOfServiceRepository
     */
    protected $termsRepository;

    /**
     * @param $page IReservationPage
     * @param $userBinder IReservationComponentBinder
     * @param $dateBinder IReservationComponentBinder
     * @param $resourceBinder IReservationComponentBinder
     * @param $userSession UserSession
     * @param ITermsOfServiceRepository $termsOfServiceRepository
     */
    public function __construct(
        $page,
        IReservationComponentBinder $userBinder,
        IReservationComponentBinder $dateBinder,
        IReservationComponentBinder $resourceBinder,
        UserSession $userSession,
        ITermsOfServiceRepository $termsOfServiceRepository
    ) {
        $this->basePage = $page;
        $this->userBinder = $userBinder;
        $this->dateBinder = $dateBinder;
        $this->resourceBinder = $resourceBinder;
        $this->currentUser = $userSession;
        $this->currentUserId = $this->currentUser->UserId;
        $this->termsRepository = $termsOfServiceRepository;
    }

    public function Initialize()
    {
        $requestedScheduleId = $this->GetScheduleId();
        $this->basePage->SetScheduleId($requestedScheduleId);

        $this->BindResourceAndAccessories();
        $this->BindDates();
        $this->BindUser();
        $this->SetTermsOfService();
    }

    protected function BindUser()
    {
        $this->userBinder->Bind($this);
    }

    protected function BindResourceAndAccessories()
    {
        $this->resourceBinder->Bind($this);
    }

    protected function BindDates()
    {
        $this->dateBinder->Bind($this);
    }

    protected function SetSelectedDates(Date $startDate, Date $endDate, $startPeriods, $endPeriods)
    {
        $startPeriod = $this->GetStartSlotClosestTo($startPeriods, $startDate);

        if (!$startPeriod->IsReservable()) {
            // if there are no more slots today, move to tomorrow
            $startDate = $startDate->AddDays(1)->GetDate();
            $endDate = $endDate->AddDays(1)->GetDate();
            $startPeriod = $this->GetStartSlotClosestTo($startPeriods, $startDate);
        }
        if ($endDate->LessThanOrEqual($startDate)) {
            $endDate = $endDate->SetTime($startPeriod->End());
        }
        $endPeriod = $this->GetEndSlotClosestTo($endPeriods, $endDate);

        $this->basePage->SetSelectedStart($startPeriod, $startDate);
        $this->basePage->SetSelectedEnd($endPeriod, $endDate);
    }

    /**
     * @param SchedulePeriod[] $periods
     * @param Date $date
     * @return SchedulePeriod
     */
    private function GetStartSlotClosestTo($periods, $date)
    {
        for ($i = 0; $i < count($periods); $i++) {
            $currentPeriod = $periods[$i];
            $periodBegin = $currentPeriod->BeginDate();

            if ($currentPeriod->IsReservable() && $periodBegin->CompareTime($date) >= 0) {
                return $currentPeriod;
            }
        }

        $lastIndex = count($periods) - 1;
        return $periods[$lastIndex];
    }

    /**
     * @param SchedulePeriod[] $periods
     * @param Date $date
     * @return SchedulePeriod
     */
    private function GetEndSlotClosestTo($periods, $date)
    {
        $lastIndex = count($periods) - 1;

        if ($periods[$lastIndex]->EndDate()->CompareTime($date) == 0) {
            return $periods[$lastIndex];
        }

        for ($i = 0; $i < count($periods); $i++) {
            $currentPeriod = $periods[$i];
            $periodEnd = $currentPeriod->EndDate();

            if ($currentPeriod->IsReservable() && $periodEnd->CompareTime($date) >= 0) {
                return $currentPeriod;
            }
        }

        return $periods[$lastIndex];
    }

    public function SetDates(Date $startDate, Date $endDate, $startPeriods, $endPeriods, $firstWeekday, $lockDates = false)
    {
        if (count($startPeriods) == 0 || count($endPeriods) == 0) {
            $this->basePage->MakeUnavailable();
            return;
        }
        $this->basePage->BindPeriods($startPeriods, $endPeriods, $lockDates);
        $this->SetSelectedDates($startDate, $endDate, $startPeriods, $endPeriods);
        if ($firstWeekday == Schedule::Today) {
            $this->basePage->SetFirstWeekday(0);
        } else {
            $this->basePage->SetFirstWeekday($firstWeekday);
        }
    }

    /**
     * @return UserSession
     */
    public function CurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @return ResourceDto
     */
    public function PrimaryResource()
    {
        return $this->primaryResource;
    }

    /**
     * @param $canChangeUser bool
     */
    public function SetCanChangeUser($canChangeUser)
    {
        $this->basePage->SetCanChangeUser($canChangeUser);
    }

    /**
     * @param $reservationUser UserDto
     */
    public function SetReservationUser($reservationUser)
    {
        $this->basePage->SetReservationUser($reservationUser);
    }

    /**
     * @param $showUserDetails bool
     */
    public function ShowUserDetails($showUserDetails)
    {
        $this->basePage->ShowUserDetails($showUserDetails);
    }

    public function SetShowParticipation($shouldShow)
    {
        $this->basePage->SetShowParticipation($shouldShow);
    }

    /**
     * @param $showReservationDetails bool
     */
    public function ShowReservationDetails($showReservationDetails)
    {
        $this->basePage->ShowReservationDetails($showReservationDetails);
    }

    /**
     * @param $resources array|IResource[]
     */
    public function BindAvailableResources($resources)
    {
        $this->basePage->BindAvailableResources($resources);
    }

    /**
     * @param $accessories Accessory[]
     */
    public function BindAvailableAccessories($accessories)
    {
        $this->basePage->BindAvailableAccessories($accessories);
    }

    public function BindResourceGroups($groups)
    {
        $this->basePage->BindResourceGroups($groups);
    }

    /**
     * @param $shouldShow bool
     */
    public function ShowAdditionalResources($shouldShow)
    {
        $this->basePage->ShowAdditionalResources($shouldShow);
    }

    /**
     * @param IBookableResource $resource
     */
    public function SetReservationResource($resource)
    {
        $this->primaryResource = $resource;
        $this->basePage->SetReservationResource($resource);
    }

    /**
     * @param $attribute CustomAttribute
     * @param $value mixed
     */
    public function AddAttribute($attribute, $value)
    {
        $this->customAttributes[] = new LBAttribute($attribute, $value);
    }

    public function RedirectToError($errorMessageId)
    {
        $this->basePage->RedirectToError($errorMessageId);
    }

    public function HideRecurrence($isHidden)
    {
        $this->basePage->HideRecurrence($isHidden);
    }

    public function SetAvailability(DateRange $availability)
    {
        $this->basePage->SetAvailability($availability);
    }

    public function IsNew()
    {
        return true;
    }

    public function SetMaximumResources($maximum)
    {
        $this->basePage->SetMaximumResources($maximum);
    }

    private function SetTermsOfService()
    {
        $termsOfService = $this->termsRepository->Load();
        if ($termsOfService != null && $termsOfService->AppliesToReservation()) {
            $this->basePage->SetTerms($termsOfService);
        }
    }
}
