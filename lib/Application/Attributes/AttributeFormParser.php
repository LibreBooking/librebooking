<?php

class AttributeFormElement
{
    /**
     * @var int
     */
    public $Id;

    /**
     * @var mixed
     */
    public $Value;

    public function __construct($id, $value)
    {
        $this->Id = $id;
        $this->Value = $value;
    }
}

class AttributeFormParser
{
    /**
     * @static
     * @param $attributes string|string[]|null The result of $this->GetForm(FormKeys::ATTRIBUTE_PREFIX)
     * @return array|AttributeFormElement[]
     */
    public static function GetAttributes($attributes)
    {
        if (is_array($attributes)) {
            $af = [];

            foreach ($attributes as $id => $value) {
                $af[] = new AttributeFormElement($id, $value);
            }

            return $af;
        }

        return [];
    }

    /**
     * Builds the attribute value submitted by an inline attribute editor,
     * which posts the attribute as a name (the attribute prefix followed by
     * the attribute id) and a value.
     *
     * @param mixed $name the submitted name field
     * @param mixed $value the submitted value field; a list, as a checkbox
     *                     attribute submits, is read as its first item, and
     *                     an empty list as no value
     * @return AttributeValue|null null when the name is not the attribute
     *                             prefix followed by a numeric attribute id,
     *                             or when the value is neither a string nor
     *                             missing, such as a nested list
     */
    public static function GetInlineAttributeValue(mixed $name, mixed $value): ?AttributeValue
    {
        if (!is_string($name) || !str_starts_with(haystack: $name, needle: FormKeys::ATTRIBUTE_PREFIX)) {
            return null;
        }

        $id = substr(string: $name, offset: strlen(string: FormKeys::ATTRIBUTE_PREFIX));
        if (!ctype_digit(text: $id)) {
            return null;
        }

        if (is_array($value)) {
            $value = $value === [] ? null : reset($value);
        }
        if ($value !== null && !is_string($value)) {
            return null;
        }

        return new AttributeValue(attributeId: $id, value: $value);
    }
}
