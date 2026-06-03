<?php

require_once(ROOT_DIR . 'lib/Common/Helpers/StopWatch.php');

interface IAttributeService
{
    /**
     * @abstract
     * @param $category CustomAttributeCategory|int
     * @param $entityIds array|int[]|int
     * @return IEntityAttributeList
     */
    public function GetAttributes($category, $entityIds = []);

    /**
     * @param $category int|CustomAttributeCategory
     * @param $attributeValues AttributeValue[]|array
     * @param $entityIds int[]
     * @param bool $ignoreEmpty
     * @param bool $isAdmin
     * @return AttributeServiceValidationResult
     */
    public function Validate($category, $attributeValues, $entityIds = [], $ignoreEmpty = false, $isAdmin = false);

    /**
     * @param $category int|CustomAttributeCategory
     * @return array|CustomAttribute[]
     */
    public function GetByCategory($category);

    /**
     * @param $attributeId int
     * @return CustomAttribute
     */
    public function GetById($attributeId);

    /**
     * @param UserSession $userSession
     * @param ReservationView $reservationView
     * @param int $requestedUserId
     * @param int[] $requestedResourceIds
     * @return Attribute[]
     */
    public function GetReservationAttributes(UserSession $userSession, ReservationView $reservationView, $requestedUserId = 0, $requestedResourceIds = []);
}

class AttributeService implements IAttributeService
{
    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    /**
     * @var IAuthorizationService
     */
    private $authorizationService;

    /**
     * @var IResourceService
     */
    private $resourceService;

    /**
     * @var ResourceDto[] indexed by resourceId
     */
    private $allowedResources;
    /**
     * @var IPermissionService|null
     */
    private $permissionService;

    /**
     * @param IAttributeRepository $attributeRepository
     * @param IPermissionService|null $permissionService
     */
    public function __construct(IAttributeRepository $attributeRepository, $permissionService = null)
    {
        $this->attributeRepository = $attributeRepository;
        $this->permissionService = $permissionService;
    }

    /**
     * @return IAuthorizationService
     */
    public function GetAuthorizationService()
    {
        if ($this->authorizationService == null) {
            $this->authorizationService = PluginManager::Instance()->LoadAuthorization();
        }

        return $this->authorizationService;
    }

    public function SetAuthorizationService(IAuthorizationService $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * @return IResourceService
     */
    public function GetResourceService()
    {
        if ($this->resourceService == null) {
            $permissionService = empty($this->permissionService) ? PluginManager::Instance()->LoadPermission() : $this->permissionService;
            $this->resourceService = new ResourceService(
                new ResourceRepository(),
                $permissionService,
                $this,
                new UserRepository(),
                new AccessoryRepository()
            );
        }

        return $this->resourceService;
    }

    public function SetResourceService(IResourceService $resourceService)
    {
        $this->resourceService = $resourceService;
    }

    public function GetAttributes($category, $entityIds = [])
    {
        if (!is_array($entityIds) && !empty($entityIds)) {
            $entityIds = [$entityIds];
        }

        $attributeList = new AttributeList();
        $attributes = $this->attributeRepository->GetByCategory($category);

        $stopwatch = new StopWatch();
        $stopwatch->Start();
        $values = $this->attributeRepository->GetEntityValues($category, $entityIds);

        foreach ($attributes as $attribute) {
            $attributeList->AddDefinition($attribute);
        }

        foreach ($values as $value) {
            $attributeList->AddValue($value);
        }
        $stopwatch->Stop();

        Log::Debug('Took %d seconds to load custom attributes for category %s', $stopwatch->GetTotalSeconds(), $category);

        return $attributeList;
    }

    public function Validate($category, $attributeValues, $entityIds = [], $ignoreEmpty = false, $isAdmin = false)
    {
        $isValid = true;
        $errors = [];
        $invalidAttributes = [];

        $entityIds = is_array($entityIds) ? $entityIds : [$entityIds];

        $resources = Resources::GetInstance();

        $values = [];
        foreach ($attributeValues as $av) {
            $values[$av->AttributeId] = $av->Value;
        }

        $attributes = $this->attributeRepository->GetByCategory($category);
        foreach ($attributes as $attribute) {
            if (!empty($entityIds) &&
                (($attribute->UniquePerEntity() && count(array_intersect($entityIds, $attribute->EntityIds())) == 0) ||
                ($attribute->HasSecondaryEntities() && count(array_intersect($entityIds, $attribute->SecondaryEntityIds())) == 0))) {
                continue;
            }

            if ($attribute->AdminOnly() && !$isAdmin) {
                continue;
            }

            if (!$attribute->Required() && !array_key_exists($attribute->Id(), $values)) {
                continue;
            }

            $value = trim($values[$attribute->Id()] ?? '');
            $label = $attribute->Label();

            if (empty($value) && ($ignoreEmpty || $isAdmin)) {
                continue;
            }

            if (!$attribute->SatisfiesRequired($value)) {
                $isValid = false;
                $error = $resources->GetString('CustomAttributeRequired', $label);
                $errors[] = $error;
                $invalidAttributes[] = new InvalidAttribute($attribute, $error);
            }

            if (!$attribute->SatisfiesConstraint($value)) {
                $isValid = false;
                $error = $resources->GetString('CustomAttributeInvalid', $label);
                $errors[] = $error;
                $invalidAttributes[] = new InvalidAttribute($attribute, $error);
            }
        }

        return new AttributeServiceValidationResult($isValid, $errors, $invalidAttributes);
    }

    public function GetByCategory($category)
    {
        return $this->attributeRepository->GetByCategory($category);
    }

    public function GetById($attributeId)
    {
        return $this->attributeRepository->LoadById($attributeId);
    }

    public function GetReservationAttributes(UserSession $userSession, ReservationView $reservationView, $requestedUserId = 0, $requestedResourceIds = [])
    {
        if ($requestedUserId == 0) {
            $requestedUserId = $reservationView->OwnerId;
        }
        if (empty($requestedResourceIds)) {
            foreach ($reservationView->Resources as $resource) {
                $requestedResourceIds[] = $resource->Id();
            }
        }

        $attributes = [];
        $customAttributes = $this->GetByCategory(CustomAttributeCategory::RESERVATION);
        foreach ($customAttributes as $attribute) {
            $secondaryCategory = $attribute->SecondaryCategory();
            if (empty($secondaryCategory) ||
                    (
                        $secondaryCategory == CustomAttributeCategory::USER &&
                            $this->AvailableForUser($userSession, $requestedUserId, $secondaryCategory, $attribute) ||
                    (($secondaryCategory == CustomAttributeCategory::RESOURCE || $secondaryCategory == CustomAttributeCategory::RESOURCE_TYPE)
                            && $this->AvailableForResource($userSession, $secondaryCategory, $attribute, $requestedResourceIds))
                    )
            ) {
                $viewableForPrivate = (!$attribute->IsPrivate() || ($attribute->IsPrivate() && $this->CanReserveFor($userSession, $requestedUserId)));
                $viewableForAdmin = (!$attribute->AdminOnly() || ($attribute->AdminOnly() && $this->GetAuthorizationService()->IsAdminFor($userSession, $requestedUserId)));
                if ($viewableForPrivate && $viewableForAdmin) {
                    $attributes[] = new LBAttribute($attribute, $reservationView->GetAttributeValue($attribute->Id()));
                }
            }
        }

        return $attributes;
    }

    private function CanReserveFor(UserSession $userSession, $requestedUserId)
    {
        return $userSession->UserId == $requestedUserId || $this->GetAuthorizationService()->CanReserveFor($userSession, $requestedUserId);
    }

    /**
     * @param UserSession $userSession
     * @param int $requestedUserId
     * @param string $secondaryCategory
     * @param CustomAttribute $attribute
     * @return bool
     */
    private function AvailableForUser(UserSession $userSession, $requestedUserId, $secondaryCategory, $attribute)
    {
        return $secondaryCategory == CustomAttributeCategory::USER &&
            in_array($requestedUserId, $attribute->SecondaryEntityIds()) &&
            $this->CanReserveFor($userSession, $requestedUserId);
    }

    /**
     * @param UserSession $userSession
     * @param string $secondaryCategory
     * @param CustomAttribute $attribute
     * @param int[] $requestedResourceIds
     * @return bool
     */
    private function AvailableForResource($userSession, $secondaryCategory, $attribute, $requestedResourceIds)
    {
        if ($secondaryCategory == CustomAttributeCategory::RESOURCE || $secondaryCategory == CustomAttributeCategory::RESOURCE_TYPE) {
            if ($secondaryCategory == CustomAttributeCategory::RESOURCE) {
                $applies = array_intersect($attribute->SecondaryEntityIds(), $requestedResourceIds);
                $allowed = array_intersect($attribute->SecondaryEntityIds(), array_keys($this->GetAllowedResources($userSession)));

                Log::Debug('applies %s allowed %s, ids %s requested %s', count($applies), count($allowed), join(',', $attribute->SecondaryEntityIds()), join(',', $requestedResourceIds));

                return count($applies) > 0 && count($allowed) > 0;
            }

            if ($secondaryCategory == CustomAttributeCategory::RESOURCE_TYPE) {
                $allowedResources = $this->GetAllowedResources($userSession);

                foreach ($requestedResourceIds as $resourceId) {
                    if (array_key_exists($resourceId, $allowedResources)) {
                        $resource = $allowedResources[$resourceId];

                        if (in_array($resource->GetResourceType(), $attribute->SecondaryEntityIds())) {
                            return true;
                        }
                    }
                }

                return false;
            }
        }

        return true;
    }

    private function GetAllowedResources($userSession)
    {
        if ($this->allowedResources == null) {
            $this->allowedResources = [];
            $resources = $this->GetResourceService()->GetAllResources(false, $userSession);
            foreach ($resources as $resource) {
                $this->allowedResources[$resource->GetId()] = $resource;
            }
        }

        return $this->allowedResources;
    }
}


class AttributeServiceValidationResult
{
    /**
     * @var int
     */
    private $isValid;

    /**
     * @var string[]
     */
    private $errors;

    /**
     * @var InvalidAttribute[]
     */
    private $invalidAttributes;

    /**
     * @param int $isValid
     * @param string[] $errors
     * @param InvalidAttribute[] $invalidAttributes
     */
    public function __construct($isValid, $errors, $invalidAttributes = [])
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
        $this->invalidAttributes = $invalidAttributes;
    }

    /**
     * @return int
     */
    public function IsValid()
    {
        return $this->isValid;
    }

    /**
     * @return string[]
     */
    public function Errors()
    {
        return $this->errors;
    }

    /**
     * @return InvalidAttribute[]
     */
    public function InvalidAttributes()
    {
        return $this->invalidAttributes;
    }
}

class InvalidAttribute
{
    /**
     * @var CustomAttribute
     */
    public $Attribute;

    /**
     * @var string
     */
    public $Error;

    public function __construct(CustomAttribute $attribute, $error)
    {
        $this->Attribute = $attribute;
        $this->Error = $error;
    }
}
