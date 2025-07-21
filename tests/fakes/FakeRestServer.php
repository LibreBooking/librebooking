<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class FakeRestServer implements IRestServer
{
    /**
     * @var mixed
     */
    public $_Request;

    /**
     * @var array|string[]
     */
    public $_ServiceUrls = [];

    /**
     * @var RestResponse
     */
    public $_LastResponse;

    public $_LastResponseCode;

    /**
     * @var string
     */
    public $_Url;

    /**
     * @var WebServiceUserSession
     */
    public $session;

    private $queryStringKeys = [];

    public function __construct()
    {
        $this->SetSession(new FakeWebServiceUserSession(123));
    }
    public function GetRequest()
    {
        return $this->_Request;
    }

    public function WriteResponse(RestResponse $restResponse, $statusCode = 200)
    {
        $this->_LastResponse = $restResponse;
        $this->_LastResponseCode = $statusCode;
    }

    public function GetServiceUrl($serviceName, $params = [])
    {
        if (isset($this->_ServiceUrls[$serviceName])) {
            return $this->_ServiceUrls[$serviceName];
        }
        return null;
    }

    public function SetRequest($request)
    {
        $this->_Request = $request;
    }

    public function GetUrl()
    {
        return $this->_Url;
    }

    public function GetFullServiceUrl($serviceName, $params = [])
    {
        // TODO: Implement GetFullServiceUrl() method.
        return null;
    }

    public function GetHeader($headerName): ?string
    {
        // TODO: Implement GetHeader() method.
        return null;
    }

    public function SetSession(WebServiceUserSession $session)
    {
        $this->session = $session;
    }

    /**
     * @return null|WebServiceUserSession
     */
    public function GetSession()
    {
        return $this->session;
    }

    public function SetQueryString($key, $value)
    {
        $this->queryStringKeys[$key] = $value;
    }

    public function GetQueryString($queryStringKey)
    {
        if (array_key_exists($queryStringKey, $this->queryStringKeys)) {
            return $this->queryStringKeys[$queryStringKey];
        }
        return null;
    }
}
