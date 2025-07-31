<?php

interface IEntityAttributeList
{
    /**
     * @return array|string[]
     */
    public function GetLabels();

    /**
     * @param null $entityId
     * @return array|CustomAttribute[]
     */
    public function GetDefinitions($entityId = null);

    /**
     * @param $entityId int|null
     * @return array|LBAttribute[]
     */
    public function GetAttributes($entityId = null);
}

class AttributeList implements IEntityAttributeList
{
    /**
     * @var array|string[]
     */
    private $labels = [];

    /**
     * @var array|string[]
     */
    private $values = [];

    /**
     * @var array|int
     */
    private $attribute_order = [];

    /**
     * @var CustomAttribute[]|array
     */
    private $definitions = [];

    /**
     * @var CustomAttribute[][]|array
     */
    private $entityDefinitions = [];

    /**
     * @var array|int[]
     */
    private $entityAttributes = [];

    public function AddDefinition(CustomAttribute $attribute)
    {
        $this->labels[] = $attribute->Label();
        $this->attribute_order[$attribute->Id()] = 1;
        if ($attribute->UniquePerEntity()) {
            $entityIds = $attribute->EntityIds();
            foreach ($entityIds as $entityId) {
                $this->entityDefinitions[$entityId][$attribute->Id()] = $attribute;
            }
            $this->entityAttributes[$attribute->Id()] = 1;
        //			Log::Debug('Adding custom attribute definition for entityId=%s, label=%s', $attribute->EntityId(), $attribute->Label());
        } else {
            $this->definitions[$attribute->Id()] = $attribute;
            //			Log::Debug('Adding custom attribute definition label=%s', $attribute->Label());
        }
    }

    /**
     * @return array|string[]
     */
    public function GetLabels()
    {
        return $this->labels;
    }

    /**
     * @param null $entityId
     * @return array|CustomAttribute[]
     */
    public function GetDefinitions($entityId = null)
    {
        if (empty($entityId) || !array_key_exists($entityId, $this->entityDefinitions)) {
            return $this->definitions;
        }

        return array_merge($this->definitions, $this->entityDefinitions[$entityId]);
    }

    /**
     * @param $attributeEntityValue AttributeEntityValue
     */
    public function AddValue($attributeEntityValue)
    {
        $entityId = $attributeEntityValue->EntityId;
        $attributeId = $attributeEntityValue->AttributeId;

        if ($this->AttributeExistsAndIsNotEntity($attributeId, $entityId)) {
            Log::Debug('Adding custom attribute value for entityId=%s, attributeId=%s', $entityId, $attributeId);
            $this->values[$entityId][$attributeId] = new LBAttribute($this->definitions[$attributeId], $attributeEntityValue->Value);
        } elseif ($this->EntityAttributeExists($attributeId, $entityId)) {
            Log::Debug(
                'Adding entity specific custom attribute value for entityId=%s, attributeId=%s',
                $entityId,
                $attributeId
            );
            $this->values[$entityId][$attributeId] = new LBAttribute($this->entityDefinitions[$entityId][$attributeId], $attributeEntityValue->Value);
        }
    }

    public function GetAttributes($entityId = null)
    {
        $attributes = [];
        foreach ($this->attribute_order as $attributeId => $placeholder) {
            $definition = null;
            if ($this->AttributeExistsAndIsNotEntity($attributeId, $entityId)) {
                $definition = $this->definitions[$attributeId];
            } elseif (!empty($entityId) && $this->EntityAttributeExists($attributeId, $entityId)) {
                $definition = $this->entityDefinitions[$entityId][$attributeId];
            }

            if ($definition != null) {
                if (empty($entityId) || !array_key_exists($entityId, $this->values) || !array_key_exists(
                    $attributeId,
                    $this->values[$entityId]
                )
                ) {
                    $attributes[] = new LBAttribute($definition);
                } else {
                    $attributes[] = $this->values[$entityId][$definition->Id()];
                }
            }
        }

        Log::Debug('Found %s attributes for entityId %s', count($attributes), $entityId);

        return $attributes;
    }

    /**
     * @param $attributeId int
     * @param $entityId int
     * @return bool
     */
    private function AttributeExistsAndIsNotEntity($attributeId, $entityId)
    {
        return array_key_exists($attributeId, $this->definitions) && !$this->IsEntityAttribute($attributeId, $entityId);
    }

    /**
     * @param $attributeId int
     * @param $entityId int
     * @return bool
     */
    private function EntityAttributeExists($attributeId, $entityId)
    {
        return $this->IsEntityAttribute($attributeId, $entityId) &&
        array_key_exists($entityId, $this->entityDefinitions) && array_key_exists($attributeId, $this->entityDefinitions[$entityId]);
    }

    /**
     * @param $attributeId int
     * @param $entityId int
     * @return bool
     */
    private function IsEntityAttribute($attributeId, $entityId)
    {
        return array_key_exists($attributeId, $this->entityAttributes) && ($entityId != null && array_key_exists($entityId, $this->entityDefinitions));
    }
}
