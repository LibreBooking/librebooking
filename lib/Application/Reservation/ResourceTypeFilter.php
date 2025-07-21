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
     * @param IResource $assignment
     * @return bool
     */
    public function ShouldInclude($assignment)
    {
        return in_array($assignment->GetResourceTypeId(), $this->resourcetypeids);
    }
}
