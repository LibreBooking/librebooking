<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Responses/Resource/ResourceUpdatedResponse.php');

class ResourceUpdatedResponseTest extends TestBase
{
    public function testLinksIncludeTheResourceId(): void
    {
        $server = new FakeRestServer();
        $server->_ServiceUrls = [
            WebServices::GetResource => '/Resources/:resourceId',
            WebServices::UpdateResource => '/Resources/:resourceId',
        ];

        $response = new ResourceUpdatedResponse($server, 5);

        $this->assertEquals(
            [
                new RestServiceLink('/Resources/5', WebServices::GetResource),
                new RestServiceLink('/Resources/5', WebServices::UpdateResource),
            ],
            $response->links
        );
    }
}
