<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/CreateAccountRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/UpdateAccountRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/UpdateAccountPasswordRequest.php');
require_once(ROOT_DIR . 'WebServices/Responses/Account/AccountResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Account/AccountActionResponse.php');
require_once(ROOT_DIR . 'WebServices/Controllers/AccountController.php');

class AccountWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    public function __construct(IRestServer $server, private readonly IAccountController $controller)
    {
        $this->server = $server;
    }

    /**
     * @name GetAccount
     * @description Gets the currently authenticated users's account information
     * @response AccountResponse
     * @return void
     */
    public function GetAccount()
    {
        $session = $this->server->GetSession();
        $user = $this->controller->LoadUser($session);
        $userId = $user->Id();
        if (empty($userId)) {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
            return;
        }

        $attributes = $this->controller->GetUserAttributes($session);
        $this->server->WriteResponse(new AccountResponse($this->server, $user, $attributes));
    }

    /**
     * @name CreateAccount
     * @description Creates a user account. This does not authenticate
     * @request CreateAccountRequest
     * @response AccountCreatedResponse
     * @return void
     */
    public function Create()
    {
        if (!Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter())) {
            $this->server->WriteResponse(
                new FailedResponse(['allow.self.registration is not enabled for the API']),
                RestResponse::UNAUTHORIZED_CODE
            );
            return;
        }

        /** @var CreateAccountRequest $request */
        $request = new CreateAccountRequest($this->server->GetRequest());

        Log::Debug('AccountWebService.Create()');

        $result = $this->controller->Create($request);

        if ($result->WasSuccessful()) {
            Log::Debug('AccountWebService.Create() - User Created. Created UserId=%s', $result->UserId());

            $this->server->WriteResponse(
                new AccountActionResponse($this->server, $result->UserId()),
                RestResponse::CREATED_CODE
            );
        } else {
            Log::Debug('AccountWebService.Create() - User Create Failed.');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }

    /**
     * @name UpdateAccount
     * @description Updates an existing user account
     * @request UpdateAccountRequest
     * @response AccountUpdatedResponse
     * @return void
     */
    public function Update($userId)
    {
        /** @var UpdateAccountRequest $request */
        $request = new UpdateAccountRequest($this->server->GetRequest());

        Log::Debug('AccountWebService.Update()');

        $result = $this->controller->Update($request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('AccountWebService.Update() - User Updated. Updated UserId=%s', $result->UserId());

            $this->server->WriteResponse(
                new AccountActionResponse($this->server, $result->UserId()),
                RestResponse::OK_CODE
            );
        } else {
            Log::Debug('AccountWebService.Update() - User Update Failed.');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }

    /**
     * @name UpdatePassword
     * @description Updates the password for an existing user
     * @request UpdateAccountPasswordRequest
     * @response AccountUpdatedResponse
     * @return void
     */
    public function UpdatePassword($userId)
    {
        /** @var UpdateAccountPasswordRequest $request */
        $request = new UpdateAccountPasswordRequest($this->server->GetRequest());

        Log::Debug('AccountWebService.UpdatePassword()');

        $result = $this->controller->UpdatePassword($request, $this->server->GetSession());

        if ($result->WasSuccessful()) {
            Log::Debug('AccountWebService.UpdatePassword() - Password Updated. Updated UserId=%s', $result->UserId());

            $this->server->WriteResponse(
                new AccountActionResponse($this->server, $result->UserId()),
                RestResponse::OK_CODE
            );
        } else {
            Log::Debug('AccountWebService.Update() - User Update Failed.');

            $this->server->WriteResponse(
                new FailedResponse($result->Errors()),
                RestResponse::BAD_REQUEST_CODE
            );
        }
    }
}
