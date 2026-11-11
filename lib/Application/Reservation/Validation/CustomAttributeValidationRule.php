<?php

class CustomAttributeValidationRule implements IReservationValidationRule
{
    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    public function __construct(IAttributeService $attributeService, IUserRepository $userRepository)
    {
        $this->attributeService = $attributeService;
        $this->userRepository = $userRepository;
    }

    public function Validate($reservationSeries, $retryParameters)
    {
        $resources = Resources::GetInstance();
        $errorMessage = new StringBuilder();

        $user = $this->userRepository->LoadById($reservationSeries->UserId());
        $bookedBy = $this->userRepository->LoadById($reservationSeries->BookedBy()->UserId);
        $isResourceAdmin = $bookedBy->IsResourceAdminForOneOf($reservationSeries->AllResources());
        $isAdminUser = $bookedBy->IsAdminFor($user) || $isResourceAdmin;

        $result = $this->attributeService->Validate(CustomAttributeCategory::RESERVATION, $reservationSeries->AttributeValues(), [], false, $isAdminUser);

        $isValid = $result->IsValid();

        if (!$isValid) {
            // make sure the failures are for attributes that apply to this reservation
            foreach ($result->InvalidAttributes() as $invalidAttribute) {
                $secondaryCategory = $invalidAttribute->Attribute->SecondaryCategory();
                $secondaryEntityIds = $invalidAttribute->Attribute->SecondaryEntityIds();

                if ($secondaryCategory == CustomAttributeCategory::USER && !in_array($reservationSeries->UserId(), $secondaryEntityIds)) {
                    // the attribute applies to a different user
                    continue;
                }
                if ($secondaryCategory == CustomAttributeCategory::RESOURCE && count(array_intersect($secondaryEntityIds, $reservationSeries->AllResourceIds())) == 0) {
                    // the attribute is not for a resource that is being booked
                    continue;
                }
                if ($secondaryCategory == CustomAttributeCategory::RESOURCE_TYPE) {
                    $appliesToResourceType = false;
                    foreach ($reservationSeries->AllResources() as $resource) {
                        if ($appliesToResourceType) {
                            // don't keep checking if we already know it applies to this resource type
                            break;
                        }
                        if (in_array($resource->GetResourceTypeId(), $secondaryEntityIds)) {
                            $appliesToResourceType = true;
                        }
                    }

                    if (!$appliesToResourceType) {
                        // the attribute is for a resource type that is not being booked
                        continue;
                    }
                }

                $errorMessage->Append($invalidAttribute->Error);
            }
        }

        if ($errorMessage->Count() > 0) {
            $errorMessage->PrependLine($resources->GetString('CustomAttributeErrors'));
        }

        return new ReservationRuleResult($errorMessage->Count() == 0, $errorMessage->ToString());
    }
}
