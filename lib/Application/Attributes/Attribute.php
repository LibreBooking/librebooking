<?php

class LBAttribute
{
    /**
     * @var CustomAttribute
     */
    private $attributeDefinition;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(CustomAttribute $attributeDefinition, $value = null)
    {
        $this->attributeDefinition = $attributeDefinition;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function Label()
    {
        return $this->attributeDefinition->Label();
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->attributeDefinition->Id();
    }

    /**
     * @return mixed
     */
    public function Value()
    {
        return $this->value;
    }

    /**
     * @return CustomAttributeTypes|int
     */
    public function Type()
    {
        return $this->attributeDefinition->Type();
    }

    /**
     * @return array|string[]
     */
    public function PossibleValueList()
    {
        return $this->attributeDefinition->PossibleValueList();
    }

    /**
     * @return bool
     */
    public function Required()
    {
        return $this->attributeDefinition->Required();
    }

    /**
     * @return bool
     */
    public function AdminOnly()
    {
        return $this->attributeDefinition->AdminOnly();
    }

    /**
     * @return int
     */
    public function SortOrder()
    {
        return $this->attributeDefinition->SortOrder();
    }

    /**
     * @param $value mixed
     */
    public function SetValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function UniquePerEntity()
    {
        return $this->attributeDefinition->UniquePerEntity();
    }

    /**
     * @return CustomAttributeCategory|int|null
     */
    public function SecondaryCategory()
    {
        return $this->attributeDefinition->SecondaryCategory();
    }

    /**
     * @return int[]|null
     */
    public function SecondaryEntityId()
    {
        return $this->attributeDefinition->SecondaryEntityIds();
    }

    /**
     * @param int|null $resourceTypeId
     * @return bool
     */
    public function AppliesToResourceType($resourceTypeId)
    {
        return $this->attributeDefinition->AppliesToResourceType($resourceTypeId);
    }
}
