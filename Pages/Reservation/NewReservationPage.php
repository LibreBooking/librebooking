<?php

require_once(ROOT_DIR . 'Pages/Ajax/AutoCompletePage.php');
require_once(ROOT_DIR . 'Pages/Reservation/ReservationPage.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenter.php');

interface IRequestedResourcePage
{
    public function GetRequestedResourceId();

    public function GetRequestedScheduleId();
}

interface INewReservationPage extends IReservationPage, IRequestedResourcePage
{
    /**
     * @return Date|null
     */
    public function GetReservationDate();

    /**
     * @return Date|null
     */
    public function GetStartDate();

    /**
     * @return Date|null
     */
    public function GetEndDate();
}

class NewReservationPage extends ReservationPage implements INewReservationPage
{
    public function __construct()
    {
        parent::__construct('CreateReservation');

        $this->SetParticipants([]);
        $this->SetInvitees([]);
    }

    protected function GetPresenter()
    {
        $this->LoadInitializerFactory();
        return new ReservationPresenter(
            $this,
            $this->LoadInitializerFactory(),
            new NewReservationPreconditionService()
        );
    }

    protected function GetTemplateName()
    {
        return 'Reservation/create.tpl';
    }

    protected function GetReservationAction()
    {
        return ReservationAction::Create;
    }

    public function GetRequestedResourceId()
    {
        return $this->server->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    public function GetRequestedScheduleId()
    {
        return $this->server->GetQuerystring(QueryStringKeys::SCHEDULE_ID);
    }

    public function GetReservationDate()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $dateTimeString = $this->server->GetQuerystring(QueryStringKeys::RESERVATION_DATE);

        if (empty($dateTimeString)) {
            return null;
        }
        return new Date($dateTimeString, $timezone);
    }

    public function GetStartDate()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $dateTimeString = $this->server->GetQuerystring(QueryStringKeys::START_DATE);

        if (empty($dateTimeString)) {
            return null;
        }
        return new Date($dateTimeString, $timezone);
    }

    public function GetEndDate()
    {
        $timezone = ServiceLocator::GetServer()->GetUserSession()->Timezone;
        $dateTimeString = $this->server->GetQuerystring(QueryStringKeys::END_DATE);

        if (empty($dateTimeString)) {
            return null;
        }
        return new Date($dateTimeString, $timezone);
    }

    public function SetTermsAccepted($accepted)
    {
        $this->Set('TermsAccepted', false);
    }
}
