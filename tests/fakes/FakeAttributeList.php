<?php

class FakeAttributeList implements IEntityAttributeList
{
    private $_attributes = [];
    private $_entityAttributes = [];

    /**
     * @param array|Attribute[] $attributes
     */
    public function __construct($attributes = [])
    {
        $this->_attributes = $attributes;
    }

    /**
     * @return array|string[]
     */
    public function GetLabels(): array
    {
        return [];
    }

    /**
     * @param null $entityId
     * @return array|CustomAttribute[]
     */
    public function GetDefinitions($entityId = null): array
    {
        return [];
    }

    /**
     * @param $entityId int|null
     * @return array|LBAttribute[]
     */
    public function GetAttributes($entityId = null): array
    {
        if (array_key_exists($entityId, $this->_entityAttributes)) {
            return $this->_entityAttributes[$entityId];
        }
        return $this->_attributes;
    }

    public function Add($entityId, $attribute)
    {
        $this->_entityAttributes[$entityId][] = $attribute;
    }
}
