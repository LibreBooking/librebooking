<?php

class ResourceTypeFilter implements IResourceFilter
{
    /**
     * @var int[] $resourcetypeids
     */
    private $resourcetypeids = [];

    public function __construct($resourcetypename)
    {
        $reader = ServiceLocator::GetDatabase()
                  ->Query(new GetResourceTypeByNameCommand($resourcetypename));

        while ($row = $reader->GetRow()) {
            $this->resourcetypeids[] = $row[ColumnNames::RESOURCE_TYPE_ID];
        }

        $reader->Free();
    }

    /**
     * @param IResource $resource
     * @return bool
     */
    public function ShouldInclude($resource)
    {
        if (!$resource instanceof IBookableResource) {
            return false;
        }

        return in_array(needle: $resource->GetResourceTypeId(), haystack: $this->resourcetypeids);
    }
}
