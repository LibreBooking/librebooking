<?php

require_once(ROOT_DIR . 'lib/WebService/IRestServer.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteParserInterface;

class SlimServer implements IRestServer
{
    private ?ServerRequestInterface $request = null;
    private ?ResponseInterface $response = null;
    private ?WebServiceUserSession $session = null;
    private RouteParserInterface $routeParser;

    public function __construct(RouteParserInterface $routeParser)
    {
        $this->routeParser = $routeParser;
    }

    public function SetRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }

    public function SetCurrentResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    public function GetCurrentResponse(): ResponseInterface
    {
        if ($this->response === null) {
            throw new \LogicException('No response has been set. Call SetCurrentResponse() before GetCurrentResponse().');
        }
        return $this->response;
    }

    public function GetRequest(): mixed
    {
        return json_decode((string) $this->request->getBody());
    }

    public function WriteResponse(RestResponse $restResponse, $statusCode = 200): void
    {
        $this->response = $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
        $this->response->getBody()->write(json_encode($restResponse));
    }

    public function GetServiceUrl($serviceName, $params = []): string
    {
        return $this->routeParser->urlFor($serviceName, $params);
    }

    public function GetUrl(): string
    {
        $uri = $this->request->getUri();
        return $uri->getScheme() . '://' . $uri->getAuthority();
    }

    public function GetFullServiceUrl($serviceName, $params = []): string
    {
        return $this->GetUrl() . $this->GetServiceUrl($serviceName, $params);
    }

    public function GetHeader($headerName): ?string
    {
        $value = $this->request->getHeaderLine($headerName);
        return $value !== '' ? $value : null;
    }

    public function SetSession(WebServiceUserSession $session): void
    {
        $this->session = $session;
    }

    public function GetSession(): ?WebServiceUserSession
    {
        return $this->session;
    }

    public function GetQueryString($queryStringKey): ?string
    {
        $params = $this->request->getQueryParams();
        return $params[$queryStringKey] ?? null;
    }
}
