<?php

class AttributeValueRequest
{
    public function __construct(public $attributeId, public $attributeValue)
    {
    }

    public static function Example()
    {
        return new AttributeValueRequest(1, 'attribute value');
    }
}
