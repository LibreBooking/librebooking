<?php

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;

require_once(ROOT_DIR . 'lib/Common/Logging/Log.php');
require_once(ROOT_DIR . 'lib/WebService/RestResponse.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/SlimServer.php');

/**
 * The responses of the API for a request that no service handled: an
 * unknown URL, an HTTP method the URL does not support, or a service that
 * failed with an exception.
 */
class ApiErrorHandlers
{
    public function __construct(private readonly ResponseFactoryInterface $responseFactory)
    {
    }

    public function Register(ErrorMiddleware $errorMiddleware): void
    {
        $errorMiddleware->setErrorHandler(HttpNotFoundException::class, $this->NotFound(...));
        $errorMiddleware->setErrorHandler(HttpMethodNotAllowedException::class, $this->MethodNotAllowed(...));
        $errorMiddleware->setDefaultErrorHandler($this->ServerError(...));
    }

    /**
     * Unknown routes, including IDs that are not numeric (see
     * SlimWebServiceRegistry::NUMERIC_ID_PARAMS), get the same JSON body as a
     * missing record.
     */
    public function NotFound(): ResponseInterface
    {
        $response = $this->responseFactory
            ->createResponse(RestResponse::NOT_FOUND_CODE)
            ->withHeader(SlimServer::CONTENT_TYPE_HEADER, SlimServer::CONTENT_TYPE_JSON);
        $response->getBody()->write(json_encode(RestResponse::NotFound()));

        return $response;
    }

    public function MethodNotAllowed(ServerRequestInterface $request, HttpMethodNotAllowedException $exception): ResponseInterface
    {
        $methods = [];
        foreach ($exception->getAllowedMethods() as $method) {
            $methods[] = $method;
            // Slim answers HEAD requests with the GET route
            if ($method === 'GET') {
                $methods[] = 'HEAD';
            }
        }
        $allowed = implode(', ', $methods);

        $response = $this->responseFactory
            ->createResponse(RestResponse::METHOD_NOT_ALLOWED_CODE)
            ->withHeader('Allow', $allowed)
            ->withHeader(SlimServer::CONTENT_TYPE_HEADER, SlimServer::CONTENT_TYPE_HTML);
        $response->getBody()->write(
            'HTTP method not allowed for the requested resource. Use one of these instead: ' . $allowed
        );

        return $response;
    }

    /**
     * A PHP error handler (see set_error_handler()) that turns a warning or
     * deprecation into an exception, so that a request during which one is
     * raised fails with ServerError() instead of carrying on with the null
     * the failed expression evaluated to. Slim 2 did the same.
     *
     * Errors that error_reporting() leaves out, which include the ones
     * silenced with "@", are left to PHP.
     *
     * @throws ErrorException
     */
    public function ThrowPhpError(int $severity, string $message, string $file, int $line): bool
    {
        if ((error_reporting() & $severity) === 0) {
            return false;
        }

        throw new ErrorException(message: $message, severity: $severity, filename: $file, line: $line);
    }

    public function ServerError(ServerRequestInterface $request, Throwable $exception): ResponseInterface
    {
        Log::Error('Slim Exception. %s', $exception);

        $response = $this->responseFactory
            ->createResponse(RestResponse::SERVER_ERROR)
            ->withHeader(SlimServer::CONTENT_TYPE_HEADER, SlimServer::CONTENT_TYPE_JSON);
        $restResponse = new RestResponse();
        $restResponse->message = 'Exception was logged.';
        $response->getBody()->write(json_encode($restResponse));

        return $response;
    }
}
