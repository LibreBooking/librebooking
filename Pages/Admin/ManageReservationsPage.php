<?php

require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageReservationsPresenter.php');

interface IManageReservationsPage extends IPageable, IActionPage
{
    /**
     * @param array|ReservationItemView[] $reservations
     */
    public function BindReservations($reservations);

    /**
     * @return bool
     */
    public function FilterButtonPressed();

    /**
     * @return string
     */
    public function GetStartDate();

    /**
     * @return string
     */
    public function GetEndDate();

    /**
     * @return int
     */
    public function GetUserId();

    /**
     * @return string
     */
    public function GetUserName();

    /**
     * @return int
     */
    public function GetScheduleId();

    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return string
     */
    public function GetReferenceNumber();

    /**
     * @param Date $date |null
     */
    public function SetStartDate($date);

    /**
     * @param Date $date |null
     * @return void
     */
    public function SetEndDate($date);

    /**
     * @param int $userId
     */
    public function SetUserId($userId);

    /**
     * @param string $userName
     */
    public function SetUserName($userName);

    /**
     * @param int $scheduleId
     */
    public function SetScheduleId($scheduleId);

    /**
     * @param int $resourceId
     */
    public function SetResourceId($resourceId);

    /**
     * @param string $referenceNumber
     */
    public function SetReferenceNumber($referenceNumber);

    /**
     * @param int $statusId
     */
    public function SetResourceStatusFilterId($statusId);

    /**
     * @param int $reasonId
     */
    public function SetResourceStatusReasonFilterId($reasonId);

    /**
     * @param array|Schedule[] $schedules
     */
    public function BindSchedules($schedules);

    /**
     * @param array|BookableResource[] $resources
     */
    public function BindResources($resources);

    /**
     * @return string
     */
    public function GetDeleteReferenceNumber();

    /**
     * @return string
     */
    public function GetDeleteScope();

    /**
     * @return int
     */
    public function GetReservationStatusId();

    /**
     * @return int
     */
    public function GetResourceStatusFilterId();

    /**
     * @return int
     */
    public function GetResourceStatusReasonFilterId();

    /**
     * @return string
     */
    public function GetResourceFilterTitle();

    /**
     * @return string
     */
    public function GetResourceFilterDescription();

    /**
     * @param $reservationStatusId int
     */
    public function SetReservationStatusId($reservationStatusId);

    /**
     * @return string
     */
    public function GetApproveReferenceNumber();

    public function ShowPage();

    public function ShowCsv();

    /**
     * @return string
     */
    public function GetFormat();

    /**
     * @param $attributeList IEntityAttributeList
     */
    public function SetAttributes($attributeList);

    /**
     * @param $statusReasons ResourceStatusReason[]
     */
    public function BindResourceStatuses($statusReasons);

    /**
     * @return int
     */
    public function GetResourceStatus();

    /**
     * @return int
     */
    public function GetResourceStatusReason();

    /**
     * @return string
     */
    public function GetResourceStatusReferenceNumber();

    /**
     * @return string
     */
    public function GetUpdateScope();

    /**
     * @return int
     */
    public function GetUpdateResourceId();

    /**
     * @return bool
     */
    public function CanUpdateResourceStatuses();

    /**
     * @return AttributeFormElement[]
     */
    public function GetAttributeFilters();

    /**
     * @param Attribute[] $filters
     */
    public function SetAttributeFilters($filters);

    /**
     * @param CustomAttribute[] $reservationAttributes
     */
    public function SetReservationAttributes($reservationAttributes);

    /**
     * @param ReservationView $reservation
     */
    public function SetReservationJson($reservation);

    /**
     * @return int
     */
    public function GetAttributeId();

    /**
     * @return string
     */
    public function GetAttributeValue();

    /**
     * @param string[] $errors
     */
    public function BindAttributeUpdateErrors($errors);

    /**
     * @return string
     */
    public function GetValue();

    /**
     * return string
     */
    public function GetName();

    /**
     * @param CustomAttribute[] $attributes
     */
    public function ShowTemplateCSV($attributes);

    /**
     * @return UploadedFile
     */
    public function GetImportFile();

    public function SetImportResult($importResult);

    /**
     * @return int[]
     */
    public function GetDeletedReservationIds();

    /**
     * @return string
     */
    public function GetTermsText();

    /**
     * @return string
     */
    public function GetTermsUrl();

    /**
     * @return UploadedFile
     */
    public function GetTermsUpload();

    /**
     * @return string
     */
    public function GetTermsApplicability();

    /**
     * @return string
     */
    public function GetTermsSource();

    /**
     * @param object $terms
     */
    public function BindTerms($terms);

    /**
     * @param string $title
     */
    public function SetReservationTitle($title);

    /**
     * @param string $description
     */
    public function SetReservationDescription($description);

    /**
     * @return bool
     */
    public function GetMissedCheckin();

    /**
     * @return bool
     */
    public function GetMissedCheckout();

    /**
     * @param bool $missedCheckin
     */
    public function SetMissedCheckin($missedCheckin);

    /**
     * @param bool $missedCheckout
     */
    public function SetMissedCheckout($missedCheckout);
}

class ManageReservationsPage extends ActionPage implements IManageReservationsPage
{
    /**
     * @var ManageReservationsPresenter
     */
    protected $presenter;

    /**
     * @var PageablePage
     * @todo(jlvillal): 2025-07-15: Convert this to `Page` for LibreBooking v4.0.0
     */
    protected $pageablePage;

    public function __construct()
    {
        parent::__construct('ManageReservations', 1);

        $this->presenter = new ManageReservationsPresenter(
            $this,
            new ManageReservationsService(new ReservationViewRepository()),
            new ScheduleRepository(),
            new ResourceRepository(),
            new AttributeService(new AttributeRepository()),
            new UserRepository(),
            new TermsOfServiceRepository()
        );

        /**
         *  @todo(jlvillal): 2025-07-15: Convert this to `Page` for LibreBooking v4.0.0
         */
        $this->pageablePage = new PageablePage($this);

        $this->Set('CreditsEnabled', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));
        $this->SetCanUpdateResourceStatus(true);
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function ProcessPageLoad()
    {
        $userTimezone = $this->server->GetUserSession()->Timezone;

        $this->Set('Timezone', $userTimezone);
        $this->Set('CsvExportUrl', ServiceLocator::GetServer()->GetUrl() . '&' . QueryStringKeys::FORMAT . '=csv');
        $this->presenter->PageLoad($userTimezone);
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest);
    }

    public function ShowPage()
    {
        $this->Display('Admin/Reservations/manage_reservations.tpl');
    }

    public function ShowCsv()
    {
        $this->DisplayCsv('Admin/Reservations/reservations_csv.tpl', 'reservations.csv');
    }

    public function BindReservations($reservations)
    {
        $this->Set('reservations', $reservations);
    }

    public function FilterButtonPressed()
    {
        return count($_GET) > 0;
    }

    public function GetStartDate()
    {
        return $this->server->GetQuerystring(QueryStringKeys::START_DATE);
    }

    public function GetEndDate()
    {
        return $this->server->GetQuerystring(QueryStringKeys::END_DATE);
    }

    public function SetStartDate($date)
    {
        $this->Set('StartDate', $date);
    }

    public function SetEndDate($date)
    {
        $this->Set('EndDate', $date);
    }

    public function GetUserId()
    {
        return $this->GetQuerystring(QueryStringKeys::USER_ID);
    }

    public function GetUserName()
    {
        return $this->GetQuerystring(QueryStringKeys::USER_NAME);
    }

    public function GetScheduleId()
    {
        return $this->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    public function GetResourceId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    public function SetUserId($userId)
    {
        $this->Set('UserIdFilter', $userId);
    }

    public function SetUserName($userName)
    {
        $this->Set('UserNameFilter', $userName);
    }

    public function SetScheduleId($scheduleId)
    {
        $this->Set('ScheduleId', $scheduleId);
    }

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

    public function GetReferenceNumber()
    {
        $rn = $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);

        if (empty($rn)) {
            $rn = $this->GetForm(FormKeys::PK);
        }

        return $rn;
    }

    public function SetReferenceNumber($referenceNumber)
    {
        $this->Set('ReferenceNumber', $referenceNumber);
    }

    public function GetPageNumber()
    {
        return $this->pageablePage->GetPageNumber();
    }

    /**
     * @todo(jlvillal): 2025-07-15: Remove this method for LibreBooking v4.0.0
     */
    public function GetPageSize()
    {
        /* replaced by dataTable */
        //return $this->pageablePage->GetPageSize();
        throw new \LogicException('GetPageSize is not implemented - replaced by dataTable pagination');
    }

    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->pageablePage->BindPageInfo($pageInfo);
    }

    public function GetDeleteReferenceNumber()
    {
        return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    public function GetDeleteScope()
    {
        return $this->GetForm(FormKeys::SERIES_UPDATE_SCOPE);
    }

    public function GetReservationStatusId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_STATUS_ID);
    }

    public function SetReservationStatusId($reservationStatusId)
    {
        $this->Set('ReservationStatusId', $reservationStatusId);
    }

    public function GetApproveReferenceNumber()
    {
        return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    public function GetFormat()
    {
        return $this->GetQuerystring(QueryStringKeys::FORMAT);
    }

    public function SetAttributes($attributeList)
    {
        $this->Set('AttributeList', $attributeList);
    }

    public function BindResourceStatuses($statusReasons)
    {
        $this->Set('StatusReasons', $statusReasons);
    }

    public function GetResourceStatus()
    {
        return $this->GetForm(FormKeys::RESOURCE_STATUS_ID);
    }

    public function GetResourceStatusReason()
    {
        return $this->GetForm(FormKeys::RESOURCE_STATUS_REASON_ID);
    }

    public function GetResourceStatusReferenceNumber()
    {
        return $this->GetForm(FormKeys::REFERENCE_NUMBER);
    }

    public function GetUpdateScope()
    {
        return $this->GetForm(FormKeys::RESOURCE_STATUS_UPDATE_SCOPE);
    }

    public function GetUpdateResourceId()
    {
        return $this->GetForm(FormKeys::RESOURCE_ID);
    }

    public function SetResourceStatusFilterId($statusId)
    {
        $this->Set('ResourceStatusFilterId', $statusId != '' ? intval($statusId) : '');
    }

    public function SetResourceStatusReasonFilterId($reasonId)
    {
        $this->Set('ResourceStatusReasonFilterId', $reasonId != '' ? intval($reasonId) : '');
    }

    public function GetResourceStatusFilterId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_RESOURCE_STATUS_ID);
    }

    public function GetResourceStatusReasonFilterId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_RESOURCE_REASON_ID);
    }

    public function GetResourceFilterTitle()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_TITLE);
    }

    public function GetResourceFilterDescription()
    {
        return $this->GetQuerystring(QueryStringKeys::RESERVATION_DESCRIPTION);
    }

    public function SetCanUpdateResourceStatus($canUpdate)
    {
        $this->Set('CanUpdateResourceStatus', $canUpdate);
    }

    public function CanUpdateResourceStatuses()
    {
        return $this->GetVar('CanUpdateResourceStatus');
    }

    public function GetAttributeFilters()
    {
        return AttributeFormParser::GetAttributes($this->GetQuerystring(FormKeys::ATTRIBUTE_PREFIX));
    }

    public function SetAttributeFilters($filters)
    {
        $this->Set('AttributeFilters', $filters);
    }

    public function SetReservationAttributes($reservationAttributes)
    {
        $this->Set('ReservationAttributes', $reservationAttributes);
    }

    public function SetReservationJson($reservation)
    {
        $this->SetJson($reservation);
    }

    public function GetAttributeId()
    {
        return $this->GetForm(FormKeys::ATTRIBUTE_ID);
    }

    public function GetAttributeValue()
    {
        return $this->GetForm(FormKeys::ATTRIBUTE_VALUE);
    }

    public function BindAttributeUpdateErrors($errors)
    {
        $this->SetJson(null, $errors);
    }

    public function GetName()
    {
        return $this->GetForm(FormKeys::NAME);
    }

    public function GetValue()
    {
        return $this->GetForm(FormKeys::VALUE);
    }

    public function ShowTemplateCSV($attributes)
    {
        $this->Set('Attributes', $attributes);
        $this->DisplayCsv('Admin/Reservations/import_reservations_template_csv.tpl', 'reservations.csv');
    }

    public function GetImportFile()
    {
        return $this->server->GetFile(FormKeys::RESERVATION_IMPORT_FILE);
    }

    public function SetImportResult($importResult)
    {
        parent::SetJson($importResult);
    }

    public function GetDeletedReservationIds()
    {
        $ids = $this->GetForm(FormKeys::RESERVATION_ID);
        if (!is_array($ids)) {
            return [$ids];
        }

        return $ids;
    }

    public function GetTermsText()
    {
        return $this->GetForm(FormKeys::TOS_TEXT);
    }

    public function GetTermsUrl()
    {
        return $this->GetForm(FormKeys::TOS_URL);
    }

    public function GetTermsUpload()
    {
        return $this->GetFile(FormKeys::TOS_UPLOAD);
    }

    public function GetTermsApplicability()
    {
        return $this->GetForm(FormKeys::TOS_APPLICABILITY);
    }

    public function GetTermsSource()
    {
        return $this->GetForm(FormKeys::TOS_METHOD);
    }

    public function BindTerms($terms)
    {
        $this->SetJson($terms);
    }

    public function SetReservationTitle($title)
    {
        $this->Set('ReservationTitle', $title);
    }

    public function SetReservationDescription($description)
    {
        $this->Set('ReservationDescription', $description);
    }

    public function GetMissedCheckin()
    {
        return (bool)$this->GetQuerystring(QueryStringKeys::MISSED_CHECKIN);
    }

    public function GetMissedCheckout()
    {
        return (bool)$this->GetQuerystring(QueryStringKeys::MISSED_CHECKOUT);
    }

    public function SetMissedCheckin($missedCheckin)
    {
        $this->Set('MissedCheckin', (bool)$missedCheckin);
    }

    public function SetMissedCheckout($missedCheckout)
    {
        $this->Set('MissedCheckout', (bool)$missedCheckout);
    }
}
