<?php

class ResourceType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array|AttributeValue[]
     */
    private $attributeValues = [];

    public function __construct($id, $name, $description, $attributeList = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        if (!empty($attributeList)) {
            $attributes = CustomAttributes::Parse($attributeList);
            foreach ($attributes->All() as $id => $value) {
                $this->WithAttribute(new AttributeValue($id, $value));
            }
        }
    }

    /**
     * @param string $name
     * @param string $description
     * @return ResourceType
     */
    public static function CreateNew($name, $description)
    {
        return new ResourceType(0, $name, $description);
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function Description()
    {
        return $this->description;
    }

    /**
     * @param $name string
     */
    public function SetName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $description string
     */
    public function SetDescription($description)
    {
        $this->description = $description;
    }

    public function WithAttribute(AttributeValue $attribute)
    {
        $this->attributeValues[$attribute->AttributeId] = $attribute;
    }

    /**
     * @var array|AttributeValue[]
     */
    private $addedAttributeValues = [];

    /**
     * @var array|AttributeValue[]
     */
    private $removedAttributeValues = [];

    /**
     * @param $attributes AttributeValue[]|array
     */
    public function ChangeAttributes($attributes)
    {
        $diff = new ArrayDiff($this->attributeValues, $attributes);

        $added = $diff->GetAddedToArray1();
        $removed = $diff->GetRemovedFromArray1();

        /** @var AttributeValue $attribute */
        foreach ($added as $attribute) {
            $this->addedAttributeValues[] = $attribute;
        }

        /** @var AttributeValue $attribute */
        foreach ($removed as $attribute) {
            $this->removedAttributeValues[] = $attribute;
        }

        foreach ($attributes as $attribute) {
            $this->AddAttributeValue($attribute);
        }
    }

    /**
     * @param $attribute AttributeValue
     */
    public function ChangeAttribute($attribute)
    {
        $this->removedAttributeValues[] = $attribute;
        $this->addedAttributeValues[] = $attribute;
        $this->AddAttributeValue($attribute);
    }

    /**
     * @param $attributeValue AttributeValue
     */
    public function AddAttributeValue($attributeValue)
    {
        $this->attributeValues[$attributeValue->AttributeId] = $attributeValue;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetAddedAttributes()
    {
        return $this->addedAttributeValues;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetRemovedAttributes()
    {
        return $this->removedAttributeValues;
    }

    /**
     * @param $customAttributeId
     * @return mixed
     */
    public function GetAttributeValue($customAttributeId)
    {
        if (array_key_exists($customAttributeId, $this->attributeValues)) {
            return $this->attributeValues[$customAttributeId]->Value;
        }

        return null;
    }
}
