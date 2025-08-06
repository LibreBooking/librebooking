<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Schedule/SchedulePresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');


interface IReservationPopupPage
{
    /**
     * @return string
     */
    public function GetReservationId();

    /**
     * @param $first string
     * @param $last string
     */
    public function SetName($first, $last);

    /**
     * @param $OwnerId string
     */
    public function SetId($OwnerId);

    /**
     * @param $resources ScheduleResource[]
     */
    public function SetResources($resources);

    /**
     * @param $users ReservationUser[]
     */
    public function SetParticipants($users);

    /**
     * @param $summary string
     */
    public function SetSummary($summary);

    /**
     * @param $title string
     */
    public function SetTitle($title);

    /**
     * @param $startDate Date
     * @param $endDate Date
     */
    public function SetDates($startDate, $endDate);

    /**
     * @param $accessories ReservationAccessory[]
     * @return mixed
     */
    public function SetAccessories($accessories);

    /**
     * @param bool $hideReservationDetails
     * @return void
     */
    public function SetHideDetails($hideReservationDetails);

    /**
     * @param bool $hideUserInfo
     * @return void
     */
    public function SetHideUser($hideUserInfo);

    /**
     * @param Attribute[] $attributes
     */
    public function BindAttributes($attributes);

    /**
     * @param string $emailAddress
     */
    public function SetEmail($emailAddress);

    /**
     * @param string $phone
     */
    public function SetPhone($phone);

    /**
     * @param bool $requiresApproval
     */
    public function SetRequiresApproval($requiresApproval);

    /**
     * @param DateDiff $duration
     */
    public function SetDuration($duration);

    /**
     * @param $viewableResourceReservations
     */
    public function BindViewableResourceReservations($resourceIds);

     /**
     * @param $amIParticipating
     */
    public function SetCurrentUserParticipating($amIParticipating);

    /**
     * @param $amIInvited
     */
    public function SetCurrentUserInvited($amIInvited);
}

class PopupFormatter
{
    private $values = [];

    public function Add($name, $value)
    {
        $this->values[$name] = $value;
    }

    private function GetValue($name)
    {
        if (isset($this->values[$name])) {
            return $this->values[$name];
        }

        return '';
    }

    public function Display()
    {
        $label = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_RESERVATION_POPUP);

        if (empty($label)) {
            $label = "{pending} {name} {email} {dates} {duration} {title} {resources} {participants} {accessories} {description} {attributes}";
        }
        $label = str_replace('{name}', $this->GetValue('name'), $label);
        $label = str_replace('{email}', $this->GetValue('email'), $label);
        $label = str_replace('{dates}', $this->GetValue('dates'), $label);
        $label = str_replace('{title}', $this->GetValue('title'), $label);
        $label = str_replace('{resources}', $this->GetValue('resources'), $label);
        $label = str_replace('{participants}', $this->GetValue('participants'), $label);
        $label = str_replace('{accessories}', $this->GetValue('accessories'), $label);
        $label = str_replace('{description}', $this->GetValue('description'), $label);
        $label = str_replace('{phone}', $this->GetValue('phone'), $label);
        $label = str_replace('{pending}', $this->GetValue('pending'), $label);
        $label = str_replace('{duration}', $this->GetValue('duration'), $label);

        if (strpos($label, '{attributes}') !== false) {
            $label = str_replace('{attributes}', $this->GetValue('attributes'), $label);
        } else {
            $matches = [];
            preg_match_all('/\{(att\d+?)\}/', $label, $matches);

            $matches = $matches[0];
            if (count($matches) > 0) {
                for ($m = 0; $m < count($matches); $m++) {
                    $id = filter_var($matches[$m], FILTER_SANITIZE_NUMBER_INT);
                    $value = $this->GetValue('att' . $id);
                    $label = str_replace($matches[$m], $value, $label);
                }
            }
        }

        return $label;
    }
}

class ReservationPopupPage extends Page implements IReservationPopupPage
{
    /**
     * @var ReservationPopupPresenter
     */
    private $_presenter;

    public function __construct()
    {
        parent::__construct();

        $this->_presenter = new ReservationPopupPresenter(
            $this,
            new ReservationViewRepository(),
            new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization()),
            new AttributeService(new AttributeRepository()),
            new UserRepository()
        );
    }

    public function IsAuthenticated()
    {
        return Configuration::Instance()->GetKey(
            ConfigKeys::PRIVACY_VIEW_RESERVATIONS,
            new BooleanConverter()
        ) ||
                parent::IsAuthenticated();
    }

    public function PageLoad()
    {
        $formatter = new PopupFormatter();
        $this->Set('formatter', $formatter);

        if (!$this->IsAuthenticated()) {
            $this->Set('authorized', false);
        } else {
            $this->Set('authorized', true);
            $this->_presenter->PageLoad();
        }

        $this->Set('ReservationId', $this->GetReservationId());

        $this->Display('Ajax/respopup.tpl');
    }

    /**
     * @return string
     */
    public function GetReservationId()
    {
        return $this->GetQuerystring('id');
    }

    public function SetName($first, $last)
    {
        $this->Set('fullName', new FullName($first, $last));
    }

    public function SetId($OwnerId){
        $this->Set('OwnerId', $OwnerId);
    }

    public function SetResources($resources)
    {
        $this->Set('resources', $resources);
    }

    public function SetParticipants($users)
    {
        $this->Set('participants', $users);
    }

    public function SetSummary($summary)
    {
        $this->Set('summary', $summary);
    }

    public function SetTitle($title)
    {
        $this->Set('title', $title);
    }

    public function SetDates($startDate, $endDate)
    {
        $this->Set('startDate', $startDate);
        $this->Set('endDate', $endDate);
    }

    public function SetAccessories($accessories)
    {
        $this->Set('accessories', $accessories);
    }

    public function SetHideDetails($hideReservationDetails)
    {
        $this->Set('hideDetails', $hideReservationDetails);
    }

    public function SetHideUser($hideUserInfo)
    {
        $this->Set('hideUserInfo', $hideUserInfo);
    }

    public function BindAttributes($attributes)
    {
        $this->Set('attributes', $attributes);
    }

    public function SetEmail($emailAddress)
    {
        $this->Set('email', $emailAddress);
    }

    public function SetPhone($phone)
    {
        $this->Set('phone', $phone);
    }

    public function SetRequiresApproval($requiresApproval)
    {
        $this->Set('requiresApproval', $requiresApproval);
    }

    public function SetDuration($duration)
    {
        $this->Set('duration', $duration);
    }

    public function BindViewableResourceReservations($resourceIds)
    {
        $this->Set('CanViewResourceReservations',$resourceIds);
    }

    public function SetCurrentUserParticipating($amIParticipating)
    {
        $this->Set('IAmParticipating', $amIParticipating);
    }

    public function SetCurrentUserInvited($amIInvited)
    {
        $this->Set('IAmInvited', $amIInvited);
    }

}


class ReservationPopupPresenter
{
    /**
     * @var IReservationPopupPage
     */
    private $_page;

    /*
     * @var IReservationViewRepository
     */
    private $_reservationRepository;

    /**
     * @var IReservationAuthorization
     */
    private $_reservationAuthorization;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IUserRepository
     */
    private $_userRepository;

    public function __construct(
        IReservationPopupPage $page,
        IReservationViewRepository $reservationRepository,
        IReservationAuthorization $reservationAuthorization,
        IAttributeService $attributeService,
        IUserRepository $userRepository
    ) {
        $this->_page = $page;
        $this->_reservationRepository = $reservationRepository;
        $this->_reservationAuthorization = $reservationAuthorization;
        $this->attributeService = $attributeService;
        $this->_userRepository = $userRepository;
    }

    public function PageLoad()
    {
        $hideUserInfo = Configuration::Instance()->GetKey(
            ConfigKeys::PRIVACY_HIDE_USER_DETAILS,
            new BooleanConverter()
        );

        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $tz = $userSession->Timezone;

        $reservation = $this->_reservationRepository->GetReservationForEditing($this->_page->GetReservationId());

        if (!$reservation->IsDisplayable()) {
            return;
        }

        $hideReservationDetails = ReservationDetailsFilter::HideReservationDetails($reservation->StartDate, $reservation->EndDate);

        if ($hideReservationDetails || $hideUserInfo) {
            $canViewDetails = $this->_reservationAuthorization->CanViewDetails($reservation, ServiceLocator::GetServer()->GetUserSession());

            $hideReservationDetails = !$canViewDetails && $hideReservationDetails;
            $hideUserInfo = !$canViewDetails && $hideUserInfo;
        }
        $this->_page->SetHideDetails($hideReservationDetails);
        $this->_page->SetHideUser($hideUserInfo);

        $startDate = $reservation->StartDate->ToTimezone($tz);
        $endDate = $reservation->EndDate->ToTimezone($tz);

        $this->_page->SetId($reservation->OwnerId);
        $this->_page->SetName($reservation->OwnerFirstName, $reservation->OwnerLastName);
        $this->_page->SetEmail($reservation->OwnerEmailAddress);
        $this->_page->SetPhone($reservation->OwnerPhone);
        $this->_page->SetResources($reservation->Resources);
        $this->_page->SetParticipants($reservation->Participants);
        $this->_page->SetSummary($reservation->Description);
        $this->_page->SetTitle($reservation->Title);
        $this->_page->SetAccessories($reservation->Accessories);
        $this->_page->SetRequiresApproval($reservation->RequiresApproval());
        $duration = $reservation->StartDate->GetDifference($reservation->EndDate);
        $this->_page->SetDuration($duration);

        $this->_page->SetDates($startDate, $endDate);

        $user = $this->_userRepository->LoadById(ServiceLocator::GetServer()->GetUserSession()->UserId);
        $owner = $this->_userRepository->LoadById($reservation->OwnerId);

        $this->UserResourcePermissions(ServiceLocator::GetServer()->GetUserSession()->UserId);
        $this->_page->SetCurrentUserParticipating($this->IsCurrentUserParticipating(ServiceLocator::GetServer()->GetUserSession()->UserId));
        $this->_page->SetCurrentUserInvited($this->IsCurrentUserInvited(ServiceLocator::GetServer()->GetUserSession()->UserId));

        $canViewAdminAttributes = $user->IsAdminFor($owner);

        if (!$canViewAdminAttributes) {
            foreach ($reservation->Resources as $resource) {
                if ($user->IsResourceAdminFor($resource)) {
                    $canViewAdminAttributes = true;
                    break;
                }
            }
        }

        $attributeValues = $this->attributeService->GetReservationAttributes($userSession, $reservation);

        $this->_page->BindAttributes($attributeValues);
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

        $this->_page->BindViewableResourceReservations($resourceIds);
    }

    private function IsCurrentUserParticipating($currentUserId)
    {
        foreach ($this->_reservationRepository->GetReservationForEditing($this->_page->GetReservationId())->Participants as $user) {
            if ($user->UserId == $currentUserId) {
                return true;
            }
        }
        return false;
    }

    private function IsCurrentUserInvited($currentUserId)
    {
        foreach ($this->_reservationRepository->GetReservationForEditing($this->_page->GetReservationId())->Invitees as $user) {
            if ($user->UserId == $currentUserId) {
                return true;
            }
        }
        return false;
    }
}
