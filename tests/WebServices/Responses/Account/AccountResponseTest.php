<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'WebServices/Responses/Account/AccountResponse.php');

class AccountResponseTest extends TestBase
{
    public function testLinksPointToTheAccountServicesForTheUser(): void
    {
        $server = new FakeRestServer();
        $server->_ServiceUrls = [
            WebServices::GetAccount => '/Accounts/:userId',
            WebServices::UpdateAccount => '/Accounts/:userId',
            WebServices::UpdateAccountPassword => '/Accounts/:userId/Password',
            // The admin-only route; the account response must not link to it.
            WebServices::UpdatePassword => '/Users/:userId/Password',
        ];

        $response = new AccountResponse($server, new FakeUser(5), new FakeAttributeList());

        $this->assertEquals(
            [
                new RestServiceLink('/Accounts/5', WebServices::GetAccount),
                new RestServiceLink('/Accounts/5', WebServices::UpdateAccount),
                new RestServiceLink('/Accounts/5/Password', WebServices::UpdateAccountPassword),
            ],
            $response->links
        );
    }
}
