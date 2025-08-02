<?php

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'Presenters/GuestParticipationPresenter.php');

interface IGuestParticipationPage
{
    /**
     * @return string|InvitationAction
     */
    public function GetInvitationAction();

    /**
     * @return string|InvitationAction
     */
    public function GetInvitationReferenceNumber();

    /**
     * @return void
     */
    public function DisplayParticipation();

    /**
     * @param $results serializable json
     * @return void
     */
    public function DisplayResult($results);

    /**
     * @return int
     */
    public function GetEmail();

    /**
     * @return string
     */
    public function GetResponseType();

    /**
     * @param array|ReservationItemView[] $reservations
     * @return void
     */
    public function BindReservations($reservations);

    /**
     * @param $timezone
     * @return void
     */
    public function SetTimezone($timezone);

    /**
     * @param bool $isMissing
     */
    public function SetMissingInformation($isMissing = true);

    /**
     * @param bool $accepted
     */
    public function SetAccepted($accepted = true);

    /**
     * @param bool $declined
     */
    public function SetDeclined($declined = true);

    /**
     * @param bool $capacityReached
     * @param string $maxCapacityMessage
     */
    public function SetMaxCapacityReached($capacityReached = true, $maxCapacityMessage = null);

    /**
     * @param bool $isGuest
     */
    public function SetIsGuest($isGuest = true);
}

class GuestParticipationPage extends Page implements IGuestParticipationPage
{
    /**
     * @var \GuestParticipationPresenter
     */
    private $presenter;

    public function __construct()
    {
        $userRepository = new UserRepository();
        parent::__construct('OpenInvitations');
        $this->presenter = new GuestParticipationPresenter(
            $this,
            new ReservationRepository(),
            $userRepository,
            new ParticipationNotification($userRepository)
        );
    }

    public function PageLoad()
    {
        $this->Set('AllowRegistration', Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter()));
        $this->presenter->PageLoad();
    }

    public function DisplayParticipation()
    {
        $this->Display('guest-participation.tpl');
    }

    public function DisplayResult($results)
    {
        $this->SetJson($results);
    }

    public function GetInvitationAction()
    {
        return $this->GetQuerystring(QueryStringKeys::INVITATION_ACTION);
    }

    public function GetInvitationReferenceNumber()
    {
        return $this->GetQuerystring(QueryStringKeys::REFERENCE_NUMBER);
    }

    public function GetEmail()
    {
        return $this->GetQuerystring(QueryStringKeys::EMAIL);
    }

    public function GetResponseType()
    {
        return $this->GetQuerystring(QueryStringKeys::RESPONSE_TYPE);
    }

    public function BindReservations($reservations)
    {
        $this->Set('Reservations', $reservations);
    }

    public function SetTimezone($timezone)
    {
        $this->Set('Timezone', $timezone);
    }

    public function SetMissingInformation($isMissing = true)
    {
        $this->Set('IsMissingInformation', $isMissing);
    }

    /**
     * @param bool $accepted
     */
    public function SetAccepted($accepted = true)
    {
        $this->Set('InvitationAccepted', $accepted);
    }

    /**
     * @param bool $capacityReached
     * @param string $maxCapacityMessage
     */
    public function SetMaxCapacityReached($capacityReached = true, $maxCapacityMessage = null)
    {
        $this->Set('CapacityReached', $capacityReached);
        $this->Set('CapacityErrorMessage', $maxCapacityMessage);
    }

    /**
     * @param bool $declined
     */
    public function SetDeclined($declined = true)
    {
        $this->Set('InvitationDeclined', $declined);
    }

    /**
     * @param bool $isGuest
     */
    public function SetIsGuest($isGuest = true)
    {
        $this->Set('IsGuest', $isGuest);
    }
}
