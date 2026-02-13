<?php

class AttributeValue
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
     * @var string
     */
    public $AttributeLabel;

    /**
     * @param $attributeId int
     * @param $value mixed
     * @param $attributeLabel string|null
     */
    public function __construct($attributeId, $value, $attributeLabel = null)
    {
        $this->AttributeId = $attributeId;
        $this->Value = trim((string) ($value ?? ''));
        $this->AttributeLabel = $attributeLabel;
    }

    public function __toString()
    {
        return sprintf('AttributeValue id:%s value:%s', $this->AttributeId, $this->Value);
    }
}

class NullAttributeValue extends AttributeValue
{
    public function __construct()
    {
        parent::__construct(null, null);
    }
}
