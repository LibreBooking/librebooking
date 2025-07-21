<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourceStatusReasonsResponse extends RestResponse
{
    public $reasons = [];

    /**
     * @param ResourceStatusReason[] $reasons
     */
    public function __construct($reasons)
    {
        foreach ($reasons as $reason) {
            $this->AddReason($reason->Id(), $reason->Description(), $reason->StatusId());
        }
    }

    protected function AddReason($id, $description, $statusId)
    {
        $this->reasons[] = ['id' => $id, 'description' => $description, 'statusId' => $statusId];
    }

    public static function Example()
    {
        return new ExampleResourceStatusReasonsResponse();
    }
}

class ExampleResourceStatusReasonsResponse extends ResourceStatusReasonsResponse
{
    public function __construct()
    {
        $this->AddReason(1, 'reason description', ResourceStatus::UNAVAILABLE);
    }
}
