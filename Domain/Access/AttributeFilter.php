<?php

class AttributeFilter
{
    /**
     * @param string $entityTableAndColumn
     * @param LBAttribute[] $attributes
     * @return ISqlFilter|null
     */
    public static function Create($entityTableAndColumn, $attributes)
    {
        $filteringAttributes = false;

        $f = new SqlFilterFreeForm($entityTableAndColumn . ' IN (SELECT `a0`.`' . ColumnNames::ATTRIBUTE_ENTITY_ID . '` FROM `' . TableNames::CUSTOM_ATTRIBUTE_VALUES . '` `a0` ');

        $attributeFragment = new SqlFilterNull();

        /** @var LBAttribute $attribute */
        foreach ($attributes as $i => $attribute) {
            if ($attribute->Value() == null || $attribute->Value() == '') {
                continue;
            }
            $id = $attribute->Id();
            $filteringAttributes = true;
            $attributeId = new SqlRepeatingFilterColumn("a$id", ColumnNames::CUSTOM_ATTRIBUTE_ID, $id);
            $attributeValue = new SqlRepeatingFilterColumn("a$id", ColumnNames::CUSTOM_ATTRIBUTE_VALUE, $id);

            $idEquals = new SqlFilterEquals($attributeId, $attribute->Id());
            $f->AppendSql('LEFT JOIN `' . TableNames::CUSTOM_ATTRIBUTE_VALUES . '` `a' . $id . '` ON `a0`.`entity_id` = `a' . $id . '`.`entity_id` ');
            if ($attribute->Type() == CustomAttributeTypes::MULTI_LINE_TEXTBOX || $attribute->Type() == CustomAttributeTypes::SINGLE_LINE_TEXTBOX) {
                $attributeFragment->_And($idEquals->_And(new SqlFilterLike($attributeValue, $attribute->Value())));
            } elseif ($attribute->Type() == CustomAttributeTypes::CHECKBOX && $attribute->Value() == '0') {
                $attributeFragment->_And(new SqlFilterFreeForm('NOT EXISTS (SELECT 1 FROM `' . TableNames::CUSTOM_ATTRIBUTE_VALUES . '` `b` WHERE `b`.`entity_id` = `a0`.`entity_id` AND `b`.`custom_attribute_id` = ' . $id . ')'));
            } else {
                $attributeFragment->_And($idEquals->_And(new SqlFilterEquals($attributeValue, $attribute->Value())));
            }
        }

        $f->AppendSql("WHERE [attribute_list_token] )");
        $f->Substitute('attribute_list_token', $attributeFragment);

        if ($filteringAttributes) {
            return $f;
        }

        return null;
    }
}
