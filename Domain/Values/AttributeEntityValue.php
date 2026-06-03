<?php

class AttributeEntityValue
{
    /**
     * @var int
     */
    public $AttributeId;

    /**
     * @var mixed
     */
    public $Value;

    /**
     * @var int
     */
    public $EntityId;

    /**
     * @param $attributeId int
     * @param $entityId int
     * @param $value mixed
     */
    public function __construct($attributeId, $entityId, $value)
    {
        $this->AttributeId = $attributeId;
        $this->Value = trim($value);
        $this->EntityId = $entityId;
    }

    public function __toString()
    {
        return sprintf('AttributeEntityValue attributeid:%s entityid:%s value:%s', $this->AttributeId, $this->EntityId, $this->Value);
    }
}
