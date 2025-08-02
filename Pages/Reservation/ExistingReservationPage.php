<?php

require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');

interface IExistingReservationPage extends IReservationPage
{
    public function GetReferenceNumber();

    /**
     * @param $additionalResourceIds int[]
     */
    public function SetAdditionalResources($additionalResourceIds);

    /**
     * @param $title string
     */
    public function SetTitle($title);

    /**
     * @param $description string
     */
    public function SetDescription($description);

    /**
     * @param $repeatType string
     */
    public function SetRepeatType($repeatType);

    /**
     * @param $repeatInterval string
     */
    public function SetRepeatInterval($repeatInterval);

    /**
     * @param $repeatMonthlyType string
     */
    public function SetRepeatMonthlyType($repeatMonthlyType);

    /**
     * @param $repeatWeekdays int[]
     */
    public function SetRepeatWeekdays($repeatWeekdays);

    /**
     * @param $customRepeatDates Date[]
     */
    public function SetCustomRepeatDates($customRepeatDates);

    /**
     * @param $referenceNumber string
     */
    public function SetReferenceNumber($referenceNumber);

    /**
     * @param $reservationId int
     */
    public function SetReservationId($reservationId);

    /**
     * @param $seriesId int
     */
    public function SetSeriesId($seriesId);

    /**
     * @param $isRecurring bool
     */
    public function SetIsRecurring($isRecurring);

    /**
     * @param $canBeEdited bool
     */
    public function SetIsEditable($canBeEdited);

    /**
     * @abstract
     * @param $canBeApproved bool
     * @return void
     */
    public function SetIsApprovable($canBeApproved);

    /**
     * @param $amIParticipating
     */
    public function SetCurrentUserParticipating($amIParticipating);

    /**
     * @param $amIInvited
     */
    public function SetCurrentUserInvited($amIInvited);

    /**
     * @param bool $canAlterParticipation
     */
    public function SetCanAlterParticipation($canAlterParticipation);

    /**
     * @param bool $isCheckInRequired
     */
    public function SetCheckInRequired($isCheckInRequired);

    /**
     * @param bool $isCheckOutRequired
     */
    public function SetCheckOutRequired($isCheckOutRequired);

    /**
     * @param int $autoReleaseMinutes
     */
    public function SetAutoReleaseMinutes($autoReleaseMinutes);

    /**
     * @param string[] $participatingGuests
     */
    public function SetParticipatingGuests($participatingGuests);

    /**
     * @param string[] $invitedGuests
     */
    public function SetInvitedGuests($invitedGuests);

    /**
     * @param bool $requiresApproval
     */
    public function SetRequiresApproval($requiresApproval);

    /**
     * @param $viewableResourceReservations
     */
    public function BindViewableResourceReservations($resourceIds);

}

class ExistingReservationPage extends ReservationPage implements IExistingReservationPage
{
    protected $IsEditable = false;
    protected $IsApprovable = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function PageLoad()
    {
        $this->Set('CanJoinWaitList', Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_ALLOW_WAITLIST, new BooleanConverter()));
        parent::PageLoad();
    }

    protected function GetPresenter()
    {
        $preconditionService = new EditReservationPreconditionService();
        $reservationViewRepository = new ReservationViewRepository();

        return new EditReservationPresenter(
            $this,
            $this->LoadInitializerFactory(),
            $preconditionService,
            $reservationViewRepository
        );
    }

    protected function GetTemplateName()
    {
        $readOnly = $this->GetQuerystring(QueryStringKeys::READ_ONLY) == 1;

        if (!$readOnly && $this->IsApprovable && !$this->UpdatingBeforeApproving()) {
            return 'Reservation/approve.tpl';
        }
        if (!$readOnly && $this->IsEditable) {
            return 'Reservation/edit.tpl';
        }
        return 'Reservation/view.tpl';
    }

    protected function UpdatingBeforeApproving()
    {
        $forceUpdate = $this->GetQuerystring('update');

        return $forceUpdate == '1' && $this->IsApprovable;
    }

    protected function GetReturnUrl()
    {
        if ($this->UpdatingBeforeApproving()) {
            return str_replace('&update=1', '', ServiceLocator::GetServer()->GetUrl());
        }
        return parent::GetReturnUrl();
    }

    protected function GetReservationAction()
    {
        return ReservationAction::Update;
    }

    public function GetReferenceNumber()
    {
        return $this->server->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    public function SetAdditionalResources($additionalResourceIds)
    {
        $this->Set('AdditionalResourceIds', $additionalResourceIds);
    }

    public function SetTitle($title)
    {
        $this->Set('ReservationTitle', htmlspecialchars($title));
    }

    public function SetDescription($description)
    {
        $this->Set('Description', $description);
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

    public function SetCustomRepeatDates($customRepeatDates)
    {
        $this->Set('CustomRepeatDates', $customRepeatDates);
    }

    public function SetReferenceNumber($referenceNumber)
    {
        $this->Set('ReferenceNumber', $referenceNumber);
    }

    public function SetReservationId($reservationId)
    {
        $this->Set('ReservationId', $reservationId);
    }

    public function SetSeriesId($seriesId)
    {
        $this->Set('SeriesId', $seriesId);
    }

    public function SetIsRecurring($isRecurring)
    {
        $this->Set('IsRecurring', $isRecurring);
    }

    public function SetIsEditable($canBeEdited)
    {
        $this->IsEditable = $canBeEdited;
    }

    public function SetCurrentUserParticipating($amIParticipating)
    {
        $this->Set('IAmParticipating', $amIParticipating);
    }

    public function SetCurrentUserInvited($amIInvited)
    {
        $this->Set('IAmInvited', $amIInvited);
    }

    public function SetIsApprovable($canBeApproved)
    {
        $this->IsApprovable = $canBeApproved;
    }

    public function SetCanAlterParticipation($canAlterParticipation)
    {
        $this->Set('CanAlterParticipation', $canAlterParticipation);
    }

    public function SetCheckInRequired($isCheckInRequired)
    {
        $this->Set('CheckInRequired', $isCheckInRequired);
    }

    public function SetCheckOutRequired($isCheckOutRequired)
    {
        $this->Set('CheckOutRequired', $isCheckOutRequired);
    }

    public function SetAutoReleaseMinutes($autoReleaseMinutes)
    {
        $this->Set('AutoReleaseMinutes', $autoReleaseMinutes);
    }

    public function SetParticipatingGuests($participatingGuests)
    {
        $this->Set('ParticipatingGuests', $participatingGuests);
    }

    public function SetInvitedGuests($invitedGuests)
    {
        $this->Set('InvitedGuests', $invitedGuests);
    }

    public function SetRequiresApproval($requiresApproval)
    {
        $this->Set('RequiresApproval', $requiresApproval);
    }

    public function SetTermsAccepted($accepted)
    {
        $this->Set('TermsAccepted', $accepted);
    }

    public function BindViewableResourceReservations($resourceIds)
    {
        $this->Set('CanViewResourceReservations',$resourceIds);
    }
}

class DuplicateReservationPage extends ExistingReservationPage
{
    public function __construct()
    {
        parent::__construct();
        $this->Set('Title', Resources::GetInstance()->GetString('DuplicateReservation'));
    }

    protected function GetTemplateName()
    {
        return 'Reservation/create.tpl';
    }

    public function PageLoad()
    {
        parent::PageLoad();

        $this->Set('ReturnUrl', urldecode($this->GetQuerystring('return')));
    }

    protected function GetReservationAction()
    {
        return ReservationAction::Create;
    }

    public function GetReferenceNumber()
    {
        return $this->server->GetQuerystring(QueryStringKeys::SOURCE_REFERENCE_NUMBER);
    }

    protected function GetReturnUrl()
    {
        return urldecode($this->GetQuerystring(QueryStringKeys::REDIRECT));
    }

    public function SetTermsAccepted($accepted)
    {
        $this->Set('TermsAccepted', false);
    }
}
