<?php

require_once(ROOT_DIR . 'Pages/Ajax/IReservationSaveResultsView.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Persistence/namespace.php');

interface IManageReservationsService
{
    /**
     * @param $pageNumber int
     * @param $pageSize int
     * @param $sortField string|null
     * @param $sortDirection string|null
     * @param $filter ReservationFilter
     * @param $user UserSession
     * @return PageableData|ReservationItemView[]
     */
    public function LoadFiltered($pageNumber, $pageSize, $sortField, $sortDirection, $filter, $user);

    /**
     * @param  $referenceNumber string
     * @param $user UserSession
     * @return ReservationView|null
     */
    public function LoadByReferenceNumber($referenceNumber, $user);

    /**
     * @param string $referenceNumber
     * @param int $attributeId
     * @param string $attributeValue
     * @param UserSession $userSession
     * @return string[] Any errors that were returned during reservation update
     */
    public function UpdateAttribute($referenceNumber, $attributeId, $attributeValue, $userSession);

    /**
     * Adds a reservation without any validation or notification
     * @param ReservationSeries $series
     */
    public function UnsafeAdd(ReservationSeries $series);

    /**
     * Deletes a reservation instance without any validation or notification
     * @param int $reservationId
     * @param UserSession $userSession
     */
    public function UnsafeDelete($reservationId, $userSession);
}

class ManageReservationsService implements IManageReservationsService
{
    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;

    /**
     * @var IReservationAuthorization
     */
    private $reservationAuthorization;

    /**
     * @var IReservationHandler
     */
    private $reservationHandler;

    /**
     * @var IUpdateReservationPersistenceService
     */
    private $persistenceService;

    /**
     * @var IReservationRepository
     */
    private $reservationRepository;

    /**
     * @param IReservationViewRepository $reservationViewRepository
     * @param IReservationAuthorization|null $authorization
     * @param IReservationHandler|null $reservationHandler
     * @param IUpdateReservationPersistenceService|null $persistenceService
     * @param IReservationRepository|null $reservationRepository
     */
    public function __construct(
        IReservationViewRepository $reservationViewRepository,
        $authorization = null,
        $reservationHandler = null,
        $persistenceService = null,
        $reservationRepository = null
    ) {
        $this->reservationViewRepository = $reservationViewRepository;
        $this->reservationAuthorization = $authorization == null ? new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization()) : $authorization;
        $this->persistenceService = $persistenceService == null ? new UpdateReservationPersistenceService(new ReservationRepository()) : $persistenceService;
        $this->reservationHandler = $reservationHandler == null ? ReservationHandler::Create(ReservationAction::Update, $this->persistenceService, ServiceLocator::GetServer()->GetUserSession()) : $reservationHandler;
        $this->reservationRepository = $reservationRepository == null ? new ReservationRepository() : $reservationRepository;
    }

    public function LoadFiltered($pageNumber, $pageSize, $sortField, $sortDirection, $filter, $user)
    {
        return $this->reservationViewRepository->GetList($pageNumber, $pageSize, $sortField, $sortDirection, $filter->GetFilter());
    }

    public function LoadByReferenceNumber($referenceNumber, $user)
    {
        $reservation = $this->reservationViewRepository->GetReservationForEditing($referenceNumber);

        if ($this->reservationAuthorization->CanEdit($reservation, $user)) {
            return $reservation;
        }

        return null;
    }

    public function UpdateAttribute($referenceNumber, $attributeId, $attributeValue, $userSession)
    {
        $reservation = $this->persistenceService->LoadByReferenceNumber($referenceNumber);
        $reservation->UpdateBookedBy($userSession);

        $attributeValues = $reservation->AttributeValues();
        $attributeValues[$attributeId] = $attributeValue;
        $reservation->ChangeAttribute(new AttributeValue($attributeId, $attributeValue));
        $collector = new ManageReservationsUpdateAttributeResultCollector();
        $this->reservationHandler->Handle($reservation, $collector);

        return $collector->errors;
    }

    public function UnsafeAdd(ReservationSeries $series)
    {
        $this->reservationRepository->Add($series);
    }

    public function UnsafeDelete($reservationId, $userSession)
    {
        $existingSeries = $this->reservationRepository->LoadById($reservationId);
        $existingSeries->ApplyChangesTo(SeriesUpdateScope::ThisInstance);

        $existingSeries->Delete($userSession);
        $this->reservationRepository->Delete($existingSeries);
    }
}

class ManageReservationsUpdateAttributeResultCollector implements IReservationSaveResultsView
{
    /**
     * @var bool
     */
    public $succeeded = false;

    /**
     * @var string[]
     */
    public $warnings;

    /**
     * @var string[]
     */
    public $errors;

    /**
     * @param bool $succeeded
     */
    public function SetSaveSuccessfulMessage($succeeded)
    {
        $this->succeeded = $succeeded;
    }

    /**
     * @param array|string[] $errors
     */
    public function SetErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @param array|string[] $warnings
     */
    public function SetWarnings($warnings)
    {
        $this->warnings = $warnings;
    }

    /**
     * @param array|string[] $messages
     */
    public function SetRetryMessages($messages)
    {
        // no-op
    }

    /**
     * @param bool $canBeRetried
     */
    public function SetCanBeRetried($canBeRetried)
    {
        // no-op
    }

    /**
     * @param ReservationRetryParameter[] $retryParameters
     */
    public function SetRetryParameters($retryParameters)
    {
        // no-op
    }

    /**
     * @return ReservationRetryParameter[]
     */
    public function GetRetryParameters()
    {
        // no-op
        return [];
    }

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        // no-op
    }
}
