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
}
