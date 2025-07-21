<?php

require_once(ROOT_DIR . 'Controls/Control.php');

class AttributeControl extends Control
{
    public function __construct(SmartyPage $smarty)
    {
        parent::__construct($smarty);
    }

    public function PageLoad()
    {
        $templates[CustomAttributeTypes::CHECKBOX] = 'Checkbox.tpl';
        $templates[CustomAttributeTypes::MULTI_LINE_TEXTBOX] = 'MultiLineTextbox.tpl';
        $templates[CustomAttributeTypes::SELECT_LIST] = 'SelectList.tpl';
        $templates[CustomAttributeTypes::SINGLE_LINE_TEXTBOX] = 'SingleLineTextbox.tpl';
        $templates[CustomAttributeTypes::DATETIME] = 'Date.tpl';

        /** @var Attribute|CustomAttribute $attribute */
        $attribute = $this->Get('attribute');

        if (is_a($attribute, 'CustomAttribute')) {
            $attributeVal = $this->Get('value');
            $attribute = new LBAttribute($attribute, $attributeVal);
            $this->Set('attribute', $attribute);
        }

        $prefix = $this->Get('namePrefix');
        $idPrefix = $this->Get('idPrefix');

        $this->Set('attributeName', sprintf('%s%s[%s]', $prefix, FormKeys::ATTRIBUTE_PREFIX, $attribute->Id()));
        $this->Set('attributeId', sprintf('%s%s%s', $idPrefix, FormKeys::ATTRIBUTE_PREFIX, $attribute->Id()));
        $this->Display('Controls/Attributes/' . $templates[$attribute->Type()]);
    }
}
