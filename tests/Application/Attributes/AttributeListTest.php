<?php

require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

class AttributeListTest extends TestBase
{
    public function testCanGetLabelsOfAllAttributes()
    {
        $attribute1 = new TestCustomAttribute(1, 'a1');
        $attribute2 = new TestCustomAttribute(2, 'a2');
        $attribute3 = new TestCustomAttribute(3, 'a3');

        $list = new AttributeList();
        $list->AddDefinition($attribute1);
        $list->AddDefinition($attribute2);
        $list->AddDefinition($attribute3);

        $labels = $list->GetLabels();

        $this->assertEquals(['a1', 'a2', 'a3'], $labels);
        $this->assertEquals([1 => $attribute1, 2 => $attribute2, 3 => $attribute3], $list->GetDefinitions());
    }

    public function testGetsAttributeValuesForEntity()
    {
        $entityId = 400;
        $attribute1 = new TestCustomAttribute(1, 'a1');
        $attribute2 = new TestCustomAttribute(2, 'a2');
        $attribute3 = new TestCustomAttribute(3, 'a3');

        $value1 = new AttributeEntityValue(1, $entityId, 'att1');
        $value3 = new AttributeEntityValue(3, $entityId, 'att3');
        $value4 = new AttributeEntityValue(4, $entityId, 'att2');

        $list = new AttributeList();
        $list->AddDefinition($attribute1);
        $list->AddDefinition($attribute2);
        $list->AddDefinition($attribute3);
        $list->AddValue($value1);
        $list->AddValue($value3);
        $list->AddValue($value4);

        $values = $list->GetAttributes($entityId);

        $this->assertEquals([new LBAttribute($attribute1, 'att1'), new LBAttribute($attribute2, null), new LBAttribute($attribute3, 'att3')], $values);
    }

    public function testWhenAttributeAppliesToSubsetOfEntities()
    {
        $entityId = 400;
        $attribute1 = new TestCustomAttribute(1, 'a1', $entityId);

        $value1 = new AttributeEntityValue(1, $entityId, 'att1');
        $value2 = new AttributeEntityValue(1, 2, 'att2');

        $list = new AttributeList();
        $list->AddDefinition($attribute1);
        $list->AddValue($value1);
        $list->AddValue($value2);

        $values = $list->GetAttributes($entityId);

        $this->assertEquals([new LBAttribute($attribute1, 'att1')], $values);
    }
}
