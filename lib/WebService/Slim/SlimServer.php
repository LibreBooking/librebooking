<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

require_once(ROOT_DIR . 'lib/WebService/IRestServer.php');

class SlimServer implements IRestServer
{
    public const CONTENT_TYPE_HEADER = 'Content-Type';
    public const CONTENT_TYPE_JSON = 'application/json';
    public const CONTENT_TYPE_HTML = 'text/html';
    private const SERVER_PARAM_HOST = 'HTTP_HOST';

    private ?ServerRequestInterface $request = null;

    /**
     * @var WebServiceUserSession
     */
    private $session;

    /**
     * Route patterns by route name, written with ":name" placeholders.
     *
     * @var array<string, string>
     */
    private array $routes = [];

    private ?int $statusCode = null;

    private string $body = '';

    /**
     * @param string $basePath Path of the API inside the web root, without a
     *     trailing slash. Service URLs start with it.
     */
    public function __construct(private readonly string $basePath = '')
    {
    }

    /**
     * Sets the request that the other methods read from.
     */
    public function SetCurrentRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Makes a route known so that URLs can be generated for it.
     *
     * @param string $pattern Route pattern with ":name" placeholders
     */
    public function AddNamedRoute(string $routeName, string $pattern): void
    {
        $this->routes[$routeName] = $pattern;
    }

    /**
     * Moves what a service produced into the response: the status and JSON
     * body passed to WriteResponse(), followed by anything the service wrote
     * to the output. A service that only wrote to the output gets an HTML
     * response.
     *
     * @param string $output Output the service wrote while it ran
     */
    public function ApplyResponse(ResponseInterface $response, string $output = ''): ResponseInterface
    {
        if ($this->statusCode === null) {
            $response = $response->withHeader(self::CONTENT_TYPE_HEADER, self::CONTENT_TYPE_HTML);
        } else {
            $response = $response
                ->withHeader(self::CONTENT_TYPE_HEADER, self::CONTENT_TYPE_JSON)
                ->withStatus($this->statusCode);
        }
        $response->getBody()->write($this->body . $output);

        $this->statusCode = null;
        $this->body = '';

        return $response;
    }

    public function GetRequest()
    {
        return json_decode((string) $this->CurrentRequest()->getBody());
    }

    public function WriteResponse(RestResponse $restResponse, $statusCode = 200)
    {
        $this->statusCode = (int) $statusCode;
        $this->body .= json_encode($restResponse);
    }

    /**
     * Each key of $params replaces the ":key" placeholder of the route. A
     * placeholder without a matching key is left in the URL.
     */
    public function GetServiceUrl($serviceName, $params = [])
    {
        if (!array_key_exists($serviceName, $this->routes)) {
            throw new RuntimeException('Named route not found for name: ' . $serviceName);
        }

        $url = $this->routes[$serviceName];
        foreach ($params as $key => $value) {
            $url = preg_replace_callback(
                '#:' . preg_quote((string) $key, '#') . '\+?(?!\w)#',
                fn (array $matches): string => (string) $value,
                $url
            );
        }

        return $this->basePath . $url;
    }

    /**
     * The host is the one the client sent, including a port if it sent one.
     * The Host header of the request object is not used when the web server
     * provided the original value: the request object drops the port from
     * the header, and its URI may carry the port of a backend server behind a
     * reverse proxy.
     */
    public function GetUrl()
    {
        $request = $this->CurrentRequest();
        $host = $request->getServerParams()[self::SERVER_PARAM_HOST] ?? $request->getHeaderLine('Host');

        return $request->getUri()->getScheme() . '://' . trim((string) $host);
    }

    public function GetFullServiceUrl($serviceName, $params = [])
    {
        return $this->GetUrl() . $this->GetServiceUrl($serviceName, $params);
    }

    public function GetHeader($headerName)
    {
        $request = $this->CurrentRequest();
        if (!$request->hasHeader($headerName)) {
            return null;
        }
        return trim($request->getHeaderLine($headerName));
    }

    public function SetSession(WebServiceUserSession $session)
    {
        $this->session = $session;
    }

    public function GetSession()
    {
        return $this->session;
    }

    /**
     * @param string $queryStringKey
     * @return string|null
     */
    public function GetQueryString($queryStringKey)
    {
        return $this->CurrentRequest()->getQueryParams()[$queryStringKey] ?? null;
    }

    private function CurrentRequest(): ServerRequestInterface
    {
        if ($this->request === null) {
            throw new LogicException('The API server has not been given a request.');
        }
        return $this->request;
    }
}
