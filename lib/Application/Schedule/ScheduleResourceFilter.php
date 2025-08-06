<?php

interface IScheduleResourceFilter
{
    /**
     * @param BookableResource[] $resources
     * @param IResourceRepository $resourceRepository
     * @param IAttributeService $attributeService
     * @return int[] filtered resource ids
     */
    public function FilterResources(
        $resources,
        IResourceRepository $resourceRepository,
        IAttributeService $attributeService
    );
}

class ScheduleResourceFilter implements IScheduleResourceFilter
{
    public $ScheduleId;
    public $ResourceIds;
    public $ResourceTypeId;
    public $MinCapacity;
    public $ResourceAttributes;
    public $ResourceTypeAttributes;

    /**
     * @param int|null $scheduleId
     * @param int|null $resourceTypeId
     * @param int|null $minCapacity
     * @param AttributeValue[]|null $resourceAttributes
     * @param AttributeValue[]|null $resourceTypeAttributes
     * @param int[]|null $resourceIds
     */
    public function __construct(
        $scheduleId = null,
        $resourceTypeId = null,
        $minCapacity = null,
        $resourceAttributes = null,
        $resourceTypeAttributes = null,
        $resourceIds = null
    ) {
        $this->ScheduleId = $scheduleId;
        $this->ResourceTypeId = $resourceTypeId;
        $this->MinCapacity = empty($minCapacity) ? null : $minCapacity;
        $this->ResourceAttributes = empty($resourceAttributes) ? [] : $resourceAttributes;
        $this->ResourceTypeAttributes = empty($resourceTypeAttributes) ? [] : $resourceTypeAttributes;
        $this->ResourceIds = empty($resourceIds) ? [] : $resourceIds;
    }

    public static function FromCookie($val)
    {
        if (empty($val)) {
            return new ScheduleResourceFilter();
        }

        return new ScheduleResourceFilter(
            $val->ScheduleId,
            $val->ResourceTypeId,
            $val->MinCapacity,
            $val->ResourceAttributes,
            $val->ResourceTypeAttributes,
            isset($val->ResourceIds) ? $val->ResourceIds : null
        );
    }

    public function HasFilter()
    {
        return !empty($this->ResourceIds) || !empty($this->ResourceTypeId) || !empty($this->MinCapacity) || !empty($this->ResourceAttributes) || !empty($this->ResourceTypeAttributes);
    }

    public function FilterResources($resources, IResourceRepository $resourceRepository, IAttributeService $attributeService)
    {
        $resourceIds = [];

        if (!$this->HasFilter()) {
            foreach ($resources as $resource) {
                $resourceIds[] = $resource->GetId();
            }

            return $resourceIds;
        }

        $resourceAttributeValues = null;
        if (!empty($this->ResourceAttributes)) {
            $resourceAttributeValues = $attributeService->GetAttributes(CustomAttributeCategory::RESOURCE, null);
        }

        $resourceTypeAttributeValues = null;
        if (!empty($this->ResourceTypeAttributes)) {
            $resourceTypeAttributeValues = $attributeService->GetAttributes(CustomAttributeCategory::RESOURCE_TYPE, null);
        }

        $resourceIds = [];

        foreach ($resources as $resource) {
            $resourceIds[] = $resource->GetId();

            if (!empty($this->ResourceIds) && !in_array($resource->GetId(), $this->ResourceIds)) {
                array_pop($resourceIds);
                continue;
            }

            if (!empty($this->MinCapacity) && $resource->GetMaxParticipants() < $this->MinCapacity) {
                array_pop($resourceIds);
                continue;
            }

            if (!empty($this->ResourceTypeId) && $resource->GetResourceTypeId() != $this->ResourceTypeId) {
                array_pop($resourceIds);
                continue;
            }

            $resourceAttributesPass = true;
            if (!empty($this->ResourceAttributes)) {
                $values = $resourceAttributeValues->GetAttributes($resource->GetId());

                /** var @attribute AttributeValue */
                foreach ($this->ResourceAttributes as $attribute) {
                    $value = $this->GetAttribute($values, $attribute->AttributeId);
                    if (!$this->AttributeValueMatches($attribute, $value)) {
                        $resourceAttributesPass = false;
                        break;
                    }
                }
            }

            if (!$resourceAttributesPass) {
                array_pop($resourceIds);
                continue;
            }

            $resourceTypeAttributesPass = true;

            if (!empty($this->ResourceTypeAttributes)) {
                if (!$resource->HasResourceType()) {
                    array_pop($resourceIds);
                    // there's a filter but this resource doesn't have a resource type
                    continue;
                }
                $values = $resourceTypeAttributeValues->GetAttributes($resource->GetResourceTypeId());

                /** var @attribute AttributeValue */
                foreach ($this->ResourceTypeAttributes as $attribute) {
                    $value = $this->GetAttribute($values, $attribute->AttributeId);
                    if (!$this->AttributeValueMatches($attribute, $value)) {
                        $resourceTypeAttributesPass = false;
                        break;
                    }
                }
            }

            if (!$resourceTypeAttributesPass) {
                array_pop($resourceIds);
                continue;
            }
        }

        return $resourceIds;
    }

    /**
     * @param LBAttribute[] $attributes
     * @param int $attributeId
     * @return null|LBAttribute
     */
    private function GetAttribute($attributes, $attributeId)
    {
        foreach ($attributes as $attribute) {
            if ($attribute->Id() == $attributeId) {
                return $attribute;
            }
        }
        return null;
    }

    /**
     * @param AttributeValue $attribute
     * @param LBAttribute $value
     * @return bool
     */
    private function AttributeValueMatches($attribute, $value)
    {
        if ($value == null) {
            return false;
        }

        if ((int)$value->Type() == CustomAttributeTypes::SINGLE_LINE_TEXTBOX || (int)$value->Type() == CustomAttributeTypes::MULTI_LINE_TEXTBOX) {
            return strripos($value->Value() ?? "", $attribute->Value) !== false;
        } elseif (is_numeric($value->Value())) {
            return floatval($value->Value()) == $attribute->Value;
        } else {
            return $value->Value() == $attribute->Value;
        }
    }
}
