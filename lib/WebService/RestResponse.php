<?php

class RestResponse
{
    public const OK_CODE = 200;
    public const CREATED_CODE = 201;
    public const BAD_REQUEST_CODE = 400;
    public const UNAUTHORIZED_CODE = 401;
    public const FORBIDDEN_CODE = 403;
    public const NOT_FOUND_CODE = 404;
    public const SERVER_ERROR = 500;
    public const SERVICE_UNAVAILABLE = 503;

    /**
     * @var array|RestServiceLink[]
     */
    public $links = [];

    /**
     * @var string
     */
    public $message = null;

    /**
     * @param IRestServer $server
     * @param string $serviceName
     * @param array $params
     * @return void
     */
    public function AddService(IRestServer $server, $serviceName, $params = [])
    {
        $url = $server->GetFullServiceUrl($serviceName, $params);
        $this->AddServiceLink(new RestServiceLink($url, $serviceName));
    }

    /**
     * @param string $href
     * @param string $title
     * @return void
     */
    public function AddLink($href, $title)
    {
        $this->AddServiceLink(new RestServiceLink($href, $title));
    }

    protected function AddServiceLink(RestServiceLink $link)
    {
        $this->links[] = $link;
    }

    public static function NotFound()
    {
        $response = new RestResponse();
        $response->message = 'The requested resource was not found';
        return $response;
    }

    public static function Unauthorized()
    {
        $response = new RestResponse();
        $response->message = 'You do not have access to the requested resource';
        return $response;
    }
}
