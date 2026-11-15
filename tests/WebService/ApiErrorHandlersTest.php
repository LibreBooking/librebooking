<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class ApiErrorHandlersTest extends TestBase
{
    private App $slim;

    private SlimWebServiceRegistry $registry;

    private ApiErrorHandlers $handlers;

    public function setUp(): void
    {
        parent::setup();

        $this->slim = AppFactory::create();
        $this->registry = new SlimWebServiceRegistry(slim: $this->slim, server: new SlimServer());

        $this->slim->addRoutingMiddleware();
        $errorMiddleware = $this->slim->addErrorMiddleware(
            displayErrorDetails: false,
            logErrors: false,
            logErrorDetails: false
        );
        $this->handlers = new ApiErrorHandlers(responseFactory: $this->slim->getResponseFactory());
        $this->handlers->Register(errorMiddleware: $errorMiddleware);
    }

    public function testUnknownPathIsAJsonNotFound(): void
    {
        $response = $this->dispatch(method: 'GET', path: '/NoSuchService/');

        $this->assertSame(RestResponse::NOT_FOUND_CODE, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            '{"links":[],"message":"The requested resource was not found"}',
            (string) $response->getBody()
        );
    }

    public function testWrongMethodListsTheAllowedMethods(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:' . WebServiceParams::UserId, [$this, 'routeCallback'], 'get');
        $category->AddPost('/:' . WebServiceParams::UserId, [$this, 'routeCallback'], 'post');
        $category->AddDelete('/:' . WebServiceParams::UserId, [$this, 'routeCallback'], 'delete');
        $this->registry->AddCategory($category);

        $response = $this->dispatch(method: 'PUT', path: '/Things/5');

        $this->assertSame(RestResponse::METHOD_NOT_ALLOWED_CODE, $response->getStatusCode());
        $this->assertSame('GET, HEAD, POST, DELETE', $response->getHeaderLine('Allow'));
        $this->assertSame(
            'HTTP method not allowed for the requested resource. Use one of these instead: GET, HEAD, POST, DELETE',
            (string) $response->getBody()
        );
    }

    public function testWrongMethodWithoutAGetRouteDoesNotOfferHead(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddPost('/Sub', [$this, 'routeCallback'], 'post');
        $this->registry->AddCategory($category);

        $response = $this->dispatch(method: 'GET', path: '/Things/Sub');

        $this->assertSame(RestResponse::METHOD_NOT_ALLOWED_CODE, $response->getStatusCode());
        $this->assertSame('POST', $response->getHeaderLine('Allow'));
    }

    /**
     * @return array<string, array{Throwable}>
     */
    public static function failureProvider(): array
    {
        return [
            'exception' => [new RuntimeException('secret detail')],
            'error' => [new TypeError('secret detail')],
        ];
    }

    #[DataProvider('failureProvider')]
    public function testFailingServiceIsAServerErrorWithoutDetails(Throwable $failure): void
    {
        $this->registry->AddUnlistedGet(
            route: '/Fails',
            callback: function () use ($failure): void {
                throw $failure;
            },
            routeName: 'Fails'
        );

        $response = null;
        $this->captureErrorLog(function () use (&$response): void {
            $response = $this->dispatch(method: 'GET', path: '/Fails');
        });

        $this->assertSame(RestResponse::SERVER_ERROR, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
        $body = (string) $response->getBody();
        $this->assertSame('{"links":[],"message":"Exception was logged."}', $body);
        $this->assertStringNotContainsString('secret detail', $body);
    }

    public function testPhpErrorBecomesAnException(): void
    {
        $message = 'Undefined array key "missing"';
        $file = '/some/Service.php';
        $line = 48;

        $exception = null;
        $this->withErrorReporting(E_ALL, function () use (&$exception, $message, $file, $line): void {
            try {
                $this->handlers->ThrowPhpError(severity: E_WARNING, message: $message, file: $file, line: $line);
            } catch (ErrorException $thrown) {
                $exception = $thrown;
            }
        });

        $this->assertInstanceOf(ErrorException::class, $exception, 'The PHP error was not turned into an exception');
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(E_WARNING, $exception->getSeverity());
        $this->assertSame($file, $exception->getFile());
        $this->assertSame($line, $exception->getLine());
    }

    public function testPhpErrorOutsideErrorReportingIsLeftToPhp(): void
    {
        $handled = null;
        $this->withErrorReporting(E_ALL & ~E_NOTICE, function () use (&$handled): void {
            $handled = $this->handlers->ThrowPhpError(
                severity: E_NOTICE,
                message: 'a notice',
                file: __FILE__,
                line: __LINE__
            );
        });

        $this->assertFalse($handled);
    }

    public function testPhpWarningInAServiceIsAServerError(): void
    {
        $continued = false;
        $this->registry->AddUnlistedGet(
            route: '/Warns',
            callback: function () use (&$continued): void {
                trigger_error('a warning from the service', E_USER_WARNING);
                $continued = true;
            },
            routeName: 'Warns'
        );

        $response = null;
        $this->withErrorReporting(E_ALL, function () use (&$response): void {
            set_error_handler($this->handlers->ThrowPhpError(...));
            try {
                $this->captureErrorLog(function () use (&$response): void {
                    $response = $this->dispatch(method: 'GET', path: '/Warns');
                });
            } finally {
                restore_error_handler();
            }
        });

        $this->assertSame(RestResponse::SERVER_ERROR, $response->getStatusCode());
        $this->assertSame('{"links":[],"message":"Exception was logged."}', (string) $response->getBody());
        $this->assertFalse($continued, 'The service carried on after the warning');
    }

    // Only @ tags may appear in this PHPDoc: SlimServiceRegistration parses it.
    /**
     * @name RouteCallback
     * @response RestResponse
     */
    public function routeCallback(): void
    {
    }

    /**
     * PHPUnit lowers error_reporting() while a test runs, so a test of the
     * PHP error handler sets the level it is about.
     *
     * @param callable(): void $callback
     */
    private function withErrorReporting(int $level, callable $callback): void
    {
        $previous = error_reporting($level);
        try {
            $callback();
        } finally {
            error_reporting($previous);
        }
    }

    private function dispatch(string $method, string $path): ResponseInterface
    {
        return $this->slim->handle((new ServerRequestFactory())->createServerRequest($method, $path));
    }
}
