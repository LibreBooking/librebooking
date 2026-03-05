<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailTemplateContext.php');

// TODO: Need a way to unit test this
class ReservationCreatedEmailAdmin extends EmailMessage
{
    protected UserDto $adminDto;

    protected User $reservationOwner;

    protected ReservationSeries $reservationSeries;

    protected BookableResource $resource;

    protected IAttributeRepository $attributeRepository;

    private string $timezone;

    private IUserRepository $userRepository;

    public function __construct(UserDto $adminDto, User $reservationOwner, ReservationSeries $reservationSeries, BookableResource $primaryResource, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        parent::__construct($adminDto->Language());

        $this->adminDto = $adminDto;
        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->resource = $primaryResource;
        $this->attributeRepository = $attributeRepository;
        $adminTimezone = $adminDto->Timezone();
        $this->timezone = !empty($adminTimezone) ? $adminTimezone : Configuration::Instance()->GetDefaultTimezone();
        $this->userRepository = $userRepository;
    }

    /**
     * @see IEmailMessage::To()
     */
    public function To()
    {
        $address = $this->adminDto->EmailAddress();
        $name = $this->adminDto->FullName();

        return [new EmailAddress($address, $name)];
    }

    public function From()
    {
        return new EmailAddress($this->reservationOwner->EmailAddress(), $this->reservationOwner->FullName());
    }

    /**
     * @see IEmailMessage::Subject()
     */
    public function Subject()
    {
        return $this->Translate('ReservationCreatedAdminSubjectWithResource', [$this->resource->GetName()]);
    }

    /**
     * @see IEmailMessage::Body()
     */
    public function Body()
    {
        $this->PopulateTemplate();
        return $this->FetchTemplate($this->GetTemplateName());
    }

    protected function GetTemplateName()
    {
        return 'ReservationCreatedAdmin.tpl';
    }

    protected function PopulateTemplate()
    {
        $context = new ReservationEmailTemplateContext(
            reservationSeries: $this->reservationSeries,
            reservationOwner: $this->reservationOwner,
            primaryResource: $this->resource,
            attributeRepository: $this->attributeRepository
        );

        $this->Set('UserName', $this->reservationOwner->FullName());

        $currentInstance = $this->reservationSeries->CurrentInstance();

        $this->Set('StartDate', $currentInstance->StartDate()->ToTimezone($this->timezone));
        $this->Set('EndDate', $currentInstance->EndDate()->ToTimezone($this->timezone));
        $this->Set('ResourceName', $this->resource->GetName());
        $resourceImage = ReservationEmailTemplateContext::BuildResourceImageUrl($this->reservationSeries->Resource()->GetImage());
        if ($resourceImage != null) {
            $this->Set('ResourceImage', $resourceImage);
        }
        $this->Set('Title', $this->reservationSeries->Title());
        $this->Set('Description', $this->reservationSeries->Description());

        $repeatVariables = $context->GetRecurrenceInstances($this->timezone);
        $this->Set('RepeatDates', $repeatVariables['RepeatDates']);
        $this->Set('RepeatRanges', $repeatVariables['RepeatRanges']);
        $this->Set('RequiresApproval', $this->reservationSeries->RequiresApproval());
        $this->Set('ReservationUrl', Pages::RESERVATION . '?' . QueryStringKeys::REFERENCE_NUMBER . '=' . $currentInstance->ReferenceNumber());

        $this->Set('ResourceNames', $context->ResourceNames());
        $this->Set('Resources', $context->Resources(true));
        $this->Set('Accessories', $this->reservationSeries->Accessories());

        $this->Set('Attributes', $context->ReservationAttributes());

        $createdBy = $context->CreatedBy();
        if ($createdBy != null) {
            $this->Set('CreatedBy', $createdBy);
        }

        $this->Set('ReferenceNumber', $this->reservationSeries->CurrentInstance()->ReferenceNumber());
    }
}
