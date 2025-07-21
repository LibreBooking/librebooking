<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class ScheduleResourceFilterTest extends TestBase
{
    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;

    /**
     * @var IAttributeService|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeService;

    public function setUp(): void
    {
        $this->resourceRepository = $this->createMock('IResourceRepository');
        $this->attributeService = $this->createMock('IAttributeService');

        parent::setup();
    }

    public function testReturnsAllWhenNoFilter()
    {
        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource4 = new FakeBookableResource(4, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $filter = new ScheduleResourceFilter();
        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(count($resources), count($resourceIds));
    }

    public function testFiltersByResourceId()
    {
        $resourceId = 10;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource4 = new FakeBookableResource($resourceId, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $filter = new ScheduleResourceFilter();
        $filter->ResourceIds = [$resourceId];

        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(1, count($resourceIds));
        $this->assertEquals($resourceId, $resourceIds[0]);
    }

    public function testFiltersByMinCapacity()
    {
        $minCapacity = 10;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource1->SetMaxParticipants($minCapacity);

        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource2->SetMaxParticipants($minCapacity - 1);

        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource3->SetMaxParticipants($minCapacity + 1);

        $resources = [$resource1, $resource2, $resource3];

        $filter = new ScheduleResourceFilter();
        $filter->MinCapacity = $minCapacity;

        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(2, count($resourceIds));
        $this->assertEquals(1, $resourceIds[0]);
        $this->assertEquals(3, $resourceIds[1]);
    }

    public function testFiltersByResourceType()
    {
        $resourceTypeId = 4;

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource1->SetResourceTypeId($resourceTypeId);

        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource2->SetResourceTypeId(null);

        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource3->SetResourceTypeId(10);

        $resources = [$resource1, $resource2, $resource3];

        $filter = new ScheduleResourceFilter();
        $filter->ResourceTypeId = $resourceTypeId;

        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(1, count($resourceIds));
        $this->assertEquals(1, $resourceIds[0]);
    }

    public function testFiltersResourceCustomAttributes()
    {
        $attributeId1 = 1;
        $attributeValue1 = 1;

        $attributeId2 = 2;
        $attributeValue2 = 'something';

        $resourceId = 4;

        $attributeList = new FakeAttributeList();
        $attributeList->Add($resourceId, new LBAttribute(new CustomAttribute($attributeId1, '', CustomAttributeTypes::CHECKBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, $resourceId), $attributeValue1));
        $attributeList->Add($resourceId, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, $resourceId), $attributeValue2));
        $attributeList->Add(1, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, 1), $attributeValue2));
        $attributeList->Add(3, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, 3), $attributeValue2));

        $this->attributeService->expects($this->once())
        ->method('GetAttributes')
        ->with($this->equalTo(CustomAttributeCategory::RESOURCE), $this->isNull())
        ->willReturn($attributeList);

        $filter = new ScheduleResourceFilter();
        $filter->ResourceAttributes = [
            new AttributeValue($attributeId1, $attributeValue1),
            new AttributeValue($attributeId2, $attributeValue2),
        ];

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource3 = new FakeBookableResource(3, 'resource3');
        $resource4 = new FakeBookableResource($resourceId, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(1, count($resourceIds));
        $this->assertEquals($resourceId, $resourceIds[0]);
    }

    public function testFiltersResourceTypeCustomAttributes()
    {
        $attributeId1 = 1;
        $attributeValue1 = 1;

        $attributeId2 = 2;
        $attributeValue2 = 'something';

        $resourceId = 3;
        $resourceTypeId = 4;

        $attributeList = new FakeAttributeList();
        $attributeList->Add($resourceTypeId, new LBAttribute(new CustomAttribute($attributeId1, '', CustomAttributeTypes::CHECKBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, $resourceTypeId), $attributeValue1));
        $attributeList->Add($resourceTypeId, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, $resourceTypeId), $attributeValue2));
        $attributeList->Add(1, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, 1), $attributeValue2));
        $attributeList->Add(3, new LBAttribute(new CustomAttribute($attributeId2, '', CustomAttributeTypes::MULTI_LINE_TEXTBOX, CustomAttributeCategory::RESOURCE, '', false, '', 0, 3), $attributeValue2));

        $this->attributeService->expects($this->once())
        ->method('GetAttributes')
        ->with($this->equalTo(CustomAttributeCategory::RESOURCE_TYPE), $this->isNull())
        ->willReturn($attributeList);

        $filter = new ScheduleResourceFilter();
        $filter->ResourceTypeAttributes = [
            new AttributeValue($attributeId1, $attributeValue1),
            new AttributeValue($attributeId2, $attributeValue2),
        ];

        $resource1 = new FakeBookableResource(1, 'resource1');
        $resource1->SetResourceTypeId(100);
        $resource2 = new FakeBookableResource(2, 'resource2');
        $resource2->SetResourceTypeId(200);
        $resource3 = new FakeBookableResource($resourceId, 'resource3');
        $resource3->SetResourceTypeId($resourceTypeId);
        $resource4 = new FakeBookableResource(4, 'resource4');
        $resources = [$resource1, $resource2, $resource3, $resource4];

        $resourceIds = $filter->FilterResources($resources, $this->resourceRepository, $this->attributeService);

        $this->assertEquals(1, count($resourceIds));
        $this->assertEquals($resourceId, $resourceIds[0]);
    }
}
