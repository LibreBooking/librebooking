<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/AuthenticationResponse.php');
require_once(ROOT_DIR . 'WebServices/Requests/AuthenticationRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/SignOutRequest.php');

class SignedOutResponse extends RestResponse
{
    /**
     * @var bool
     */
    public $signedOut;
}


class AuthenticationWebService
{
    /**
     * @var IRestServer
     */
    private $server;
    /**
     * @var IWebServiceAuthentication
     */
    private $authentication;
    private int|string|null $api_access_group_id;  // If specified and user not in group then authentication will be denied

    public function __construct(IRestServer $server, IWebServiceAuthentication $authentication, int|string|null $api_access_group_id = null)
    {
        $this->server = $server;
        $this->authentication = $authentication;
        $this->api_access_group_id = $api_access_group_id;
    }

    /**
     * @name Authenticate
     * @description Authenticates an existing LibreBooking user
     * @request AuthenticationRequest
     * @response AuthenticationResponse
     * @return void
     */
    public function Authenticate()
    {
        /** @var AuthenticationRequest $request */
        $request = $this->server->GetRequest();
        $username = $request->username;
        $password = $request->password;

        Log::Debug('WebService Authenticate for user %s', $username);

        $isValid = $this->authentication->Validate($username, $password);
        if ($isValid) {
            Log::Debug('WebService Authenticate, user %s was authenticated', $username);
            $session = $this->authentication->Login($username);

            if (!$session->IsAdmin && is_numeric($this->api_access_group_id)) {
                if (!UserGroupHelper::isUserInGroup(groupId: $this->api_access_group_id, userId: $session->UserId)) {
                    Log::Debug('WebService Authenticate, user %s was denied API access', $username);
                    $this->server->WriteResponse(AuthenticationResponse::NotAuthorized(), statusCode: RestResponse::FORBIDDEN_CODE);
                    return;
                }
            }

            $version = 0;
            $reader = ServiceLocator::GetDatabase()->Query(new GetVersionCommand());
            if ($row = $reader->GetRow()) {
                $version = $row[ColumnNames::VERSION_NUMBER];
            }
            $reader->Free();

            Log::Debug('SessionToken=%s', $session->SessionToken);
            $this->server->WriteResponse(AuthenticationResponse::Success($this->server, $session, $version));
        } else {
            Log::Debug('WebService Authenticate, user %s was not authenticated', $username);

            $this->server->WriteResponse(AuthenticationResponse::Failed(), RestResponse::UNAUTHORIZED_CODE);
        }
    }

    /**
     * @name SignOut
     * @request SignOutRequest
     * @return void
     */
    public function SignOut()
    {
        /** @var SignOutRequest $request */
        $request = $this->server->GetRequest();
        $userId = $request->userId;
        $sessionToken = $request->sessionToken;

        Log::Debug('WebService SignOut for userId %s and sessionToken %s', $userId, $sessionToken);

        $this->authentication->Logout($userId, $sessionToken);
        $r = new SignedOutResponse();
        $r->signedOut = true;
        $this->server->WriteResponse($r);
    }
}
