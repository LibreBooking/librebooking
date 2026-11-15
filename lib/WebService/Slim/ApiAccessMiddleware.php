<?php

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/SlimServer.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/SlimWebServiceRegistry.php');

/**
 * Decides whether a request that matched a route may reach its service.
 *
 * It has to run after Slim's routing middleware: a request that matches no
 * route is answered with "not found" before it gets here.
 */
class ApiAccessMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly SlimWebServiceRegistry $registry,
        private readonly SlimServer $server,
        private readonly WebServiceSecurity $security,
        private readonly ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->server->SetCurrentRequest($request);

        if (!Configuration::Instance()->GetKey(ConfigKeys::API_ENABLED, new BooleanConverter())) {
            return $this->Halt(
                statusCode: RestResponse::SERVICE_UNAVAILABLE,
                message: 'LibreBooking API is disabled. Set ["api"]["enabled"] = true'
            );
        }

        $routeName = (string) RouteContext::fromRequest($request)->getRoute()?->getName();
        if (!$this->registry->IsSecure($routeName)) {
            return $handler->handle($request);
        }

        $wasHandled = $this->security->HandleSecureRequest($this->server, $this->registry->IsLimitedToAdmin($routeName));
        if (!$wasHandled) {
            return $this->Halt(
                statusCode: RestResponse::UNAUTHORIZED_CODE,
                message: 'You must be authenticated in order to access this service.<br/>'
                    . $this->server->GetFullServiceUrl(WebServices::Login)
            );
        }

        $userSession = ServiceLocator::GetUserSession();
        // Admin users can always use the API
        if ($userSession->IsAdmin) {
            return $handler->handle($request);
        }

        // Check if the user is allowed API access to the route
        if (!$this->registry->IsUserAllowedApiAccess(routeName: $routeName, userId: $userSession->UserId)) {
            return $this->Halt(
                statusCode: RestResponse::FORBIDDEN_CODE,
                message: 'You are not authorized to access this service.<br/>'
            );
        }

        return $handler->handle($request);
    }

    private function Halt(int $statusCode, string $message): ResponseInterface
    {
        $response = $this->responseFactory
            ->createResponse($statusCode)
            ->withHeader(SlimServer::CONTENT_TYPE_HEADER, SlimServer::CONTENT_TYPE_HTML);
        $response->getBody()->write($message);

        return $response;
    }
}
