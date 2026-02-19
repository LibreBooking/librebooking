<?php

declare(strict_types=1);

class FakeBookableResource extends BookableResource
{
    public function __construct($id, $name = null)
    {
        $this->_resourceId = $id;
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
