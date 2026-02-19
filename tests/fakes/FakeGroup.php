<?php

declare(strict_types=1);

class FakeGroup extends Group
{
    public function __construct($id = 123, $name = 'group name')
    {
        $this->WithId($id);
        $this->Rename($name);
        $this->WithGroupAdmin(999);
        $this->WithUser(1);
        $this->WithFullPermission(2);
        $this->WithRole(RoleLevel::APPLICATION_ADMIN);
    }
}
