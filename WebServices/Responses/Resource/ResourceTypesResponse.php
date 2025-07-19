<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourceTypesResponse extends RestResponse
{
    public $types = [];

    /**
     * @param ResourceType[] $types
     */
    public function __construct($types)
    {
        foreach ($types as $type) {
            $this->AddType($type->Id(), $type->Description());
        }
    }

    protected function AddType($id, $description)
    {
        $this->types[] = ['id' => $id, 'description' => $description];
    }

    public static function Example()
    {
        return new ExampleResourceTypesResponse();
    }
}

class ExampleResourceTypesResponse extends ResourceTypesResponse
{
    public function __construct()
    {
        $this->AddType(1, 'description');
    }
}
