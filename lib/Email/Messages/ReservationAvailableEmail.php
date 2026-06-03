<?php

require_once(ROOT_DIR . 'lib/Email/EmailMessage.php');
require_once(ROOT_DIR . 'Pages/Pages.php');

class ReservationAvailableEmail extends EmailMessage
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var BookableResource
     */
    private $resource;
    /**
     * @var ReservationWaitlistRequest
     */
    private $request;

    public function __construct(User $user, BookableResource $resource, ReservationWaitlistRequest $request)
    {
        $this->user = $user;
        $this->resource = $resource;
        $this->request = $request;

        parent::__construct($user->Language());
    }

    public function Subject()
    {
        return $this->Translate(
            'ReservationAvailableSubject',
            [
                $this->resource->GetName(),
                $this->request->StartDate()->ToTimezone($this->user->Timezone())->Format(Resources::GetInstance()
                                                                                                  ->ShortDateTimeFormat())]
        );
    }

    public function To()
    {
        return new EmailAddress($this->user->EmailAddress(), $this->user->FullName());
    }

    public function Body()
    {
        $startDate = $this->request->StartDate()->ToTimezone($this->user->Timezone());
        $endDate = $this->request->EndDate()->ToTimezone($this->user->Timezone());
        $format = Resources::GetInstance()->GetDateFormat('system_datetime');

        $this->Set('ResourceName', $this->resource->GetName());
        $this->Set('FirstName', $this->user->FirstName());
        $this->Set('StartDate', $startDate);
        $this->Set('EndDate', $endDate);
        $this->Set('ReservationUrl', sprintf(
            '%s?%s=%s&%s=%s&%s=%s',
            Pages::RESERVATION,
            QueryStringKeys::RESOURCE_ID,
            $this->request->ResourceId(),
            QueryStringKeys::START_DATE,
            urlencode($startDate->Format($format)),
            QueryStringKeys::END_DATE,
            urlencode($endDate->Format($format))
        ));

        return $this->FetchTemplate('ReservationAvailable.tpl');
    }
}
