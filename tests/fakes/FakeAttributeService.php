<?php

class FakeAttributeService implements IAttributeService
{
    /**
     * @var Attribute[]
     */
    public $_ReservationAttributes = [];

    /**
     * @var AttributeServiceValidationResult
     */
    public $_ValidationResult;
    /**
     * @var CustomAttribute[]
     */
    public $_ByCategory = [];
    public $_EntityAttributeList;

    /**
     * @var Attribute[]
     */
    public $_ResourceAttributes = [];

    /**
     * @param $category CustomAttributeCategory|int
     * @param $entityIds array|int[]|int
     * @return IEntityAttributeList
     */
    public function GetAttributes($category, $entityIds = [])
    {
        return $this->_EntityAttributeList;
    }

    /**
     * @param $category int|CustomAttributeCategory
     * @param $attributeValues AttributeValue[]|array
     * @param $entityIds int[]
     * @param bool $ignoreEmpty
     * @param bool $isAdmin
     * @return AttributeServiceValidationResult
     */
    public function Validate($category, $attributeValues, $entityIds = [], $ignoreEmpty = false, $isAdmin = false)
    {
        return $this->_ValidationResult;
    }

    /**
     * @param $category int|CustomAttributeCategory
     * @return array|CustomAttribute[]
     */
    public function GetByCategory($category)
    {
        return $this->_ByCategory[$category];
    }

    /**
     * @param $attributeId int
     * @return CustomAttribute
     */
    public function GetById($attributeId)
    {
        // TODO: Implement GetById() method.
        return null;
    }

    /**
     * @param UserSession $userSession
     * @param ReservationView $reservationView
     * @param int $requestedUserId
     * @return Attribute[]
     */
    public function GetReservationAttributes(UserSession $userSession, ReservationView $reservationView, $requestedUserId = 0, $requestedResourceIds = [])
    {
        return $this->_ReservationAttributes;
    }

    /**
     * @param UserSession $userSession
     * @param int $resourceId
     * @param int $resourceTypeId
     * @return Attribute[]
     */
    public function GetResourceAttributes(UserSession $userSession, $resourceId = 0, $resourceTypeId = 0)
    {
        return $this->_ResourceAttributes;
    }

    /**
     * @param $reservationSeries
     * @param $isAdmin
     * @return mixed
     */
    public function ValidateReservation($reservationSeries, $isAdmin)
    {
        return $this->_ValidationResult;
    }

    /**
     * @param $userId int|null
     * @return array
     */
    public function GetUserManagedAttributes($userId = null)
    {
        return [];
    }

    /**
     * @param $userId
     * @param $attributeId
     * @return array
     */
    public function GetUserManagedPossibleValues($userId, $attributeId)
    {
        return [];
    }

    /**
     * @param $userId
     * @param $attributeId
     * @param $valuesAsString
     * @return void
     */
    public function UpdateUserManagedPossibleValues($userId, $attributeId, $valuesAsString)
    {
        // Stub implementation
    }

    /**
     * @param array $attributeValues
     * @return array
     */
    public function RemoveValuesMissingDependencies(array $attributeValues): array
    {
        return $attributeValues;
    }

    /**
     * @return int
     */
    public function GetTimeConstrainedAttributeCount(): int
    {
        return 0;
    }
}
