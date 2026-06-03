<?php

declare(strict_types=1);

class FakeBookableResource extends BookableResource
{
    public function __construct($resourceId, $name = null)
    {
        $this->_resourceId = $resourceId;
        $this->_name = $name;
    }

    public function RequiresApproval($requiresApproval)
    {
        $this->_requiresApproval = $requiresApproval;
    }

    public function SetScheduleAdminGroupId($scheduleAdminGroupId)
    {
        $this->_scheduleAdminGroupId = $scheduleAdminGroupId;
    }
}
