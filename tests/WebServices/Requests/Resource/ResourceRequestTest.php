<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Requests/Resource/ResourceRequest.php');

class ResourceRequestTest extends TestBase
{
    public function testHydratesAdminGroupIdAndPreservesOmission(): void
    {
        $omitted = new ResourceRequest(jsonObject: json_decode('{"name":"Room"}'));
        $cleared = new ResourceRequest(jsonObject: json_decode('{"adminGroupId":null}'));
        $assigned = new ResourceRequest(jsonObject: json_decode('{"adminGroupId":42}'));

        $this->assertFalse($omitted->HasAdminGroupId());
        $this->assertTrue($cleared->HasAdminGroupId());
        $this->assertNull($cleared->adminGroupId);
        $this->assertTrue($assigned->HasAdminGroupId());
        $this->assertSame(42, $assigned->adminGroupId);
    }
}
