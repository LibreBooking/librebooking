<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');

// TODO: Need a way to unit test this
class ReservationCreatedEmailAdmin extends EmailMessage
{
    /**
     * @var UserDto
     */
    protected $adminDto;

    /**
     * @var User
     */
    protected $reservationOwner;

    /**
     * @var ReservationSeries
     */
    protected $reservationSeries;

    /**
     * @var IResource
     */
    protected $resource;

    /**
     * @var IAttributeRepository
     */
    protected $attributeRepository;

    /**
     * @var string
     */
    protected $timezone;
    /**
     * @var IUserRepository
     */
    private $userRepository;

    /**
     * @param UserDto $adminDto
     * @param User $reservationOwner
     * @param ReservationSeries $reservationSeries
     * @param IResource $primaryResource
     * @param IAttributeRepository $attributeRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(UserDto $adminDto, User $reservationOwner, ReservationSeries $reservationSeries, IResource $primaryResource, IAttributeRepository $attributeRepository, IUserRepository $userRepository)
    {
        parent::__construct($adminDto->Language());

        $this->adminDto = $adminDto;
        $this->reservationOwner = $reservationOwner;
        $this->reservationSeries = $reservationSeries;
        $this->resource = $primaryResource;
        $this->attributeRepository = $attributeRepository;
        $this->timezone = $adminDto->Timezone();
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
        $this->Set('UserName', $this->reservationOwner->FullName());

        $currentInstance = $this->reservationSeries->CurrentInstance();

        $this->Set('StartDate', $currentInstance->StartDate()->ToTimezone($this->timezone));
        $this->Set('EndDate', $currentInstance->EndDate()->ToTimezone($this->timezone));
        $this->Set('ResourceName', $this->resource->GetName());
        $this->Set('Title', $this->reservationSeries->Title());
        $this->Set('Description', $this->reservationSeries->Description());

        $img = $this->reservationSeries->Resource()->GetImage();
        if (!empty($img)) {
            $this->Set('ResourceImage', $this->GetFullImagePath($img));
        }

        $repeatDates = [];
        $repeatRanges = [];
        if ($this->reservationSeries->IsRecurring()) {
            foreach ($this->reservationSeries->Instances() as $repeated) {
                $repeatDates[] = $repeated->StartDate()->ToTimezone($this->timezone);
                $repeatRanges[] = $repeated->Duration()->ToTimezone($this->timezone);
            }
        }
        $this->Set('RepeatDates', $repeatDates);
        $this->Set('RepeatRanges', $repeatRanges);
        $this->Set('RequiresApproval', $this->reservationSeries->RequiresApproval());
        $this->Set('ReservationUrl', Pages::RESERVATION . "?" . QueryStringKeys::REFERENCE_NUMBER . '=' . $currentInstance->ReferenceNumber());

        $resourceNames = [];
        foreach ($this->reservationSeries->AllResources() as $resource) {
            $resourceNames[] = $resource->GetName();
        }
        $this->Set('ResourceNames', $resourceNames);
        $this->Set('Accessories', $this->reservationSeries->Accessories());

        $attributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESERVATION);
        $attributeValues = [];
        foreach ($attributes as $attribute) {
            if (($attribute->HasSecondaryEntities()) && in_array($this->reservationSeries->ResourceId(), $attribute->SecondaryEntityIds())) {
                $attributeValues[] = new LBAttribute($attribute, $this->reservationSeries->GetAttributeValue($attribute->Id()));
            }
        }

        $this->Set('Attributes', $attributeValues);

        $bookedBy = $this->reservationSeries->BookedBy();
        if ($bookedBy != null && ($bookedBy->UserId != $this->reservationOwner->Id())) {
            $this->Set('CreatedBy', new FullName($bookedBy->FirstName, $bookedBy->LastName));
        }

        $this->Set('ReferenceNumber', $this->reservationSeries->CurrentInstance()->ReferenceNumber());
    }

    private function GetFullImagePath($img)
    {
        return Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_IMAGE_URL) . '/' . $img;
    }
}
