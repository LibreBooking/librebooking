<?php

require_once(ROOT_DIR . 'Domain/Access/AccessoryRepository.php');

class AccessoryResourceRule implements IReservationValidationRule
{
    /**
     * @var IAccessoryRepository
     */
    private $accessoryRepository;

    /**
     * @var Resources
     */
    private $strings;

    /**
     * Constructor for initializing dependencies
     *
     * @param IAccessoryRepository $accessoryRepository
     */
    public function __construct(IAccessoryRepository $accessoryRepository)
    {
        $this->accessoryRepository = $accessoryRepository;
        $this->strings = Resources::GetInstance();
    }

    /**
     * Validates reservations for accessory-resource rules
     *
     * @param $reservationSeries
     * @param $retryParameters
     * @return ReservationRuleResult
     */
    public function Validate($reservationSeries, $retryParameters)
    {
        $errors = [];

        // Step 1: Collect booked accessories
        $bookedAccessories = [];
        foreach ($reservationSeries->Accessories() as $accessory) {
            $bookedAccessories[$accessory->AccessoryId] = $accessory;
        }

        // Step 2: Load all accessories and create associations
        $accessories = $this->accessoryRepository->LoadAll();

        // Reject accessories that no longer exist (deleted, stale form or edited request).
        // The checks below assume every booked accessory is known.
        $knownAccessoryIds = [];
        foreach ($accessories as $accessory) {
            $knownAccessoryIds[$accessory->GetId()] = true;
        }
        foreach (array_keys($bookedAccessories) as $bookedAccessoryId) {
            if (!isset($knownAccessoryIds[$bookedAccessoryId])) {
                return new ReservationRuleResult(false, $this->strings->GetString('AccessoryNotFoundErrorMessage'));
            }
        }

        $association = $this->GetResourcesAndRequiredAccessories($accessories);
        $bookedResourceIds = $reservationSeries->AllResourceIds();

        // Step 3: Find invalid accessory-resource associations
        $badAccessories = $association->GetAccessoriesThatCannotBeBookedWithGivenResources($bookedAccessories, $bookedResourceIds);

        foreach ($badAccessories as $accessoryName) {
            $errors[] = $this->strings->GetString('AccessoryResourceAssociationErrorMessage', $accessoryName);
        }

        // Step 4: Ensure all accessories have a QuantityReserved value (even if not booked)
        foreach ($accessories as $accessory) {
            if (!isset($bookedAccessories[$accessory->GetId()])) {
                $bookedAccessories[$accessory->GetId()] = (object) ['QuantityReserved' => 0];
            }
        }

        // Step 5: Validate min and max quantities for resources and accessories
        foreach ($reservationSeries->AllResources() as $resource) {
            $resourceId = $resource->GetResourceId();
            if ($association->ContainsResource($resourceId)) {
                $resourceAccessories = $association->GetResourceAccessories($resourceId);
                foreach ($resourceAccessories as $accessory) {
                    $accessoryId = $accessory->GetId();

                    if (isset($bookedAccessories[$accessoryId]) && $bookedAccessories[$accessoryId] !== null) {
                        $resource = $accessory->GetResource($resourceId);

                        // Validate minimum quantity
                        if (!is_null($resource->MinQuantity) && $bookedAccessories[$accessoryId]->QuantityReserved < $resource->MinQuantity) {
                            $errors[] = $this->strings->GetString('AccessoryMinQuantityErrorMessage', [$resource->MinQuantity, $accessory->GetName()]);
                        }

                        // Validate maximum quantity
                        if (!is_null($resource->MaxQuantity) && $bookedAccessories[$accessoryId]->QuantityReserved > $resource->MaxQuantity) {
                            $errors[] = $this->strings->GetString('AccessoryMaxQuantityErrorMessage', [$resource->MaxQuantity, $accessory->GetName()]);
                        }
                    } else {
                        // Error for unbooked accessory
                        $errors[] = $this->strings->GetString('AccessoryNotBookedErrorMessage', $accessory->GetName());
                    }
                }
            }
        }

        // Return validation result
        return new ReservationRuleResult(count($errors) == 0, implode("\n", $errors));
    }

    /**
     * Builds relationships between resources and accessories
     *
     * @param Accessory[] $accessories
     * @return ResourceAccessoryAssociation
     */
    private function GetResourcesAndRequiredAccessories($accessories)
    {
        $association = new ResourceAccessoryAssociation();
        foreach ($accessories as $accessory) {
            $association->AddAccessory($accessory);
            foreach ($accessory->Resources() as $resource) {
                $association->AddRelationship($resource, $accessory);
            }
        }
        return $association;
    }
}

class ResourceAccessoryAssociation
{
    private $resources = [];

    /** @var Accessory[] */
    private $accessories = [];

    /**
     * Adds a relationship between a resource and an accessory
     *
     * @param ResourceAccessory $resource
     * @param Accessory $accessory
     */
    public function AddRelationship($resource, $accessory)
    {
        $this->resources[$resource->ResourceId][$accessory->GetId()] = $accessory;
    }

    /**
     * Checks if a resource exists in the association
     *
     * @param int $resourceId
     * @return bool
     */
    public function ContainsResource($resourceId)
    {
        return array_key_exists($resourceId, $this->resources);
    }

    /**
     * Gets accessories associated with a resource
     *
     * @param int $resourceId
     * @return Accessory[]
     */
    public function GetResourceAccessories($resourceId)
    {
        return $this->resources[$resourceId] ?? [];
    }

    /**
     * Adds an accessory to the association
     *
     * @param Accessory $accessory
     */
    public function AddAccessory(Accessory $accessory)
    {
        $this->accessories[$accessory->GetId()] = $accessory;
    }

    /**
     * Identifies accessories that cannot be booked with the given resources
     *
     * @param ReservationAccessory[] $bookedAccessories
     * @param int[] $bookedResourceIds
     * @return string[]
     */
    public function GetAccessoriesThatCannotBeBookedWithGivenResources($bookedAccessories, $bookedResourceIds)
    {
        $badAccessories = [];

        foreach ($bookedAccessories as $accessory) {
            $accessoryId = $accessory->AccessoryId;

            if ($this->AccessoryNeedsARequiredResourceToBeBooked($accessoryId, $bookedResourceIds)) {
                $badAccessories[] = $this->accessories[$accessoryId]->GetName();
            }
        }

        return $badAccessories;
    }

    /**
     * Checks if an accessory requires a specific resource to be booked
     *
     * @param int $accessoryId
     * @param int[] $bookedResourceIds
     * @return bool
     */
    private function AccessoryNeedsARequiredResourceToBeBooked($accessoryId, $bookedResourceIds)
    {
        $accessory = $this->accessories[$accessoryId];
        if ($accessory->IsTiedToResource()) {
            Log::Debug('Checking required resources for accessory %s', $accessoryId);
            $overlap = array_intersect($accessory->ResourceIds(), $bookedResourceIds);
            return count($overlap) == 0;
        }
        return false;
    }
}
