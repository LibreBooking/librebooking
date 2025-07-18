<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class AuthenticationResponse extends RestResponse
{
    public $sessionToken;
    public $sessionExpires;
    public $userId;
    public $isAuthenticated = false;
    public $version;

    /**
     * @static
     * @param $server IRestServer
     * @param $userSession WebServiceUserSession
     * @return AuthenticationResponse
     */
    public static function Success(IRestServer $server, $userSession, $version)
    {
        $response = new AuthenticationResponse();
        $response->sessionToken = $userSession->SessionToken;
        $response->sessionExpires = $userSession->SessionExpiration;
        $response->isAuthenticated = true;
        $response->userId = $userSession->UserId;
        $response->version = $version;

        $response->AddService($server, WebServices::Logout);
        //$response->AddService($server, WebServices::MyBookings, array($userSession->PublicId));
        //$response->AddService($server, WebServices::AllBookings);
        //		$response->AddAction(RestAction::MyBookings());
        //		$response->AddAction(RestAction::CreateBooking());

        return $response;
    }

    /**
     * @static
     * @return AuthenticationResponse
     */
    public static function Failed()
    {
        $response = new AuthenticationResponse();
        $response->message = 'Login failed. Invalid username or password.';
        return $response;
    }

    public static function NotAuthorized()
    {
        $response = new AuthenticationResponse();
        $response->message = 'Login failed. API access not authorized.';
        return $response;
    }

    public static function Example()
    {
        return new ExampleAuthenticationResponse();
    }
}

class ExampleAuthenticationResponse extends AuthenticationResponse
{
    public function __construct()
    {
        $this->sessionToken = 'sessiontoken';
        $this->sessionExpires = Date::Now()->ToIso();
        $this->isAuthenticated = true;
        $this->userId = 123;
        $this->version = '1.0';
    }
}
