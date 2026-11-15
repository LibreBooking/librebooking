<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class SlimWebServiceRegistryTest extends TestBase
{
    private App $slim;

    private SlimServer $server;

    private SlimWebServiceRegistry $registry;

    /**
     * Arguments of each call of routeCallback()
     *
     * @var array<int, array<int, mixed>>
     */
    private array $calls = [];

    public function setUp(): void
    {
        parent::setup();

        $this->slim = AppFactory::create();
        $this->slim->addRoutingMiddleware();
        $this->server = new SlimServer(basePath: '/base');
        $this->registry = new SlimWebServiceRegistry(slim: $this->slim, server: $this->server);
        $this->calls = [];
    }

    public function testRegistersCategoryWithSlim()
    {
        $callback = [$this, 'routeCallback'];

        $category1 = new SlimWebServiceRegistryCategory('Something');
        $category2 = new SlimWebServiceRegistryCategory('SomethingElse');

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddPost('/post/1/', $callback, $c1p1name);
        $category1->AddGet('/get/:one', $callback, $c1p2name);
        $category1->AddDelete('/delete/:one', $callback, $c1p3name);

        $category2->AddPost('post/2/', $callback, '2');
        $category2->AddGet('get/:two', $callback, '3');
        $category2->AddDelete('delete/:two', $callback, '4');

        $this->registry->AddCategory($category1);
        $this->registry->AddCategory($category2);

        $this->assertSame('/Something/post/1', $this->routePattern($c1p1name));
        $this->assertSame('/Something/get/{one}', $this->routePattern($c1p2name));
        $this->assertSame('/Something/delete/{one}', $this->routePattern($c1p3name));
        $this->assertFalse($this->registry->IsSecure($c1p1name));
        $this->assertFalse($this->registry->IsSecure($c1p2name));
        $this->assertFalse($this->registry->IsSecure($c1p3name));

        $this->assertSame('/base/Something/get/:one', $this->server->GetServiceUrl($c1p2name));

        $this->assertCalled(method: 'POST', path: '/Something/post/1', expectedArgs: []);
        $this->assertCalled(method: 'GET', path: '/Something/get/a', expectedArgs: ['a']);
        $this->assertCalled(method: 'DELETE', path: '/Something/delete/b', expectedArgs: ['b']);
        $this->assertCalled(method: 'POST', path: '/SomethingElse/post/2', expectedArgs: []);
        $this->assertCalled(method: 'GET', path: '/SomethingElse/get/c', expectedArgs: ['c']);
        $this->assertCalled(method: 'DELETE', path: '/SomethingElse/delete/d', expectedArgs: ['d']);

        $this->assertSame([$category1, $category2], $this->registry->Categories());
    }

    public function testRegistersSecureRoute()
    {
        $callback = [$this, 'routeCallback'];
        $category1 = new SlimWebServiceRegistryCategory('Something');

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddSecurePost('/post/1/', $callback, $c1p1name);
        $category1->AddSecureGet('/get/:one', $callback, $c1p2name);
        $category1->AddSecureDelete('/delete/:one', $callback, $c1p3name);

        $this->registry->AddCategory($category1);

        $this->assertSame('/Something/post/1', $this->routePattern($c1p1name));
        $this->assertSame('/Something/get/{one}', $this->routePattern($c1p2name));
        $this->assertSame('/Something/delete/{one}', $this->routePattern($c1p3name));

        $this->assertTrue($this->registry->IsSecure($c1p1name));
        $this->assertTrue($this->registry->IsSecure($c1p2name));
        $this->assertTrue($this->registry->IsSecure($c1p3name));

        $this->assertFalse($this->registry->IsLimitedToAdmin($c1p1name));
        $this->assertFalse($this->registry->IsLimitedToAdmin($c1p2name));
        $this->assertFalse($this->registry->IsLimitedToAdmin($c1p3name));
    }

    public function testRegistersAdminRoute()
    {
        $callback = [$this, 'routeCallback'];
        $category1 = new SlimWebServiceRegistryCategory('Something');

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddAdminPost('/post/1/', $callback, $c1p1name);
        $category1->AddAdminGet('/get/:one', $callback, $c1p2name);
        $category1->AddAdminDelete('/delete/:one', $callback, $c1p3name);

        $this->registry->AddCategory($category1);

        $this->assertSame('/Something/post/1', $this->routePattern($c1p1name));
        $this->assertSame('/Something/get/{one}', $this->routePattern($c1p2name));
        $this->assertSame('/Something/delete/{one}', $this->routePattern($c1p3name));

        $this->assertTrue($this->registry->IsSecure($c1p1name));
        $this->assertTrue($this->registry->IsSecure($c1p2name));
        $this->assertTrue($this->registry->IsSecure($c1p3name));

        $this->assertTrue($this->registry->IsLimitedToAdmin($c1p1name));
        $this->assertTrue($this->registry->IsLimitedToAdmin($c1p2name));
        $this->assertTrue($this->registry->IsLimitedToAdmin($c1p3name));
    }

    public static function numericIdRouteProvider(): array
    {
        $routes = [];
        foreach (SlimWebServiceRegistry::NUMERIC_ID_PARAMS as $param) {
            $routes[$param] = [$param];
        }
        return $routes;
    }

    #[DataProvider('numericIdRouteProvider')]
    public function testNumericIdRouteParamsOnlyMatchDigits(string $param): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:' . $param, [$this, 'routeCallback'], 'get');
        $category->AddPost('/:' . $param . '/Sub', [$this, 'routeCallback'], 'post');
        $category->AddDelete('/:' . $param, [$this, 'routeCallback'], 'delete');
        $this->registry->AddCategory($category);

        $routes = [
            ['GET', '/Things/%s'],
            ['POST', '/Things/%s/Sub'],
            ['DELETE', '/Things/%s'],
        ];

        foreach ($routes as [$method, $uriFormat]) {
            $this->assertCalled(method: $method, path: sprintf($uriFormat, '5'), expectedArgs: ['5']);
            $this->assertCalled(method: $method, path: sprintf($uriFormat, '1234567890'), expectedArgs: ['1234567890']);

            $this->assertNotMatched(method: $method, path: sprintf($uriFormat, 'abc'), message: 'non-numeric id');
            $this->assertNotMatched(method: $method, path: sprintf($uriFormat, '12abc'), message: 'id with trailing text');
            $this->assertNotMatched(method: $method, path: sprintf($uriFormat, '-1'), message: 'negative id');
            $this->assertNotMatched(method: $method, path: sprintf($uriFormat, '1.5'), message: 'decimal id');
            $this->assertNotMatched(method: $method, path: sprintf($uriFormat, '12345678901'), message: 'id longer than 10 digits');
        }
    }

    public function testReferenceNumberRouteParamIsNotRestricted(): void
    {
        $category = new SlimWebServiceRegistryCategory('Reservations');
        $category->AddGet('/:' . WebServiceParams::ReferenceNumber, [$this, 'routeCallback'], 'get');
        $this->registry->AddCategory($category);

        $this->assertCalled(method: 'GET', path: '/Reservations/5f1a2b3c4d5e6', expectedArgs: ['5f1a2b3c4d5e6']);
    }

    public function testRouteParamsArePassedByPositionInRouteOrder(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:second/Sub/:first', [$this, 'routeCallback'], 'get');
        $this->registry->AddCategory($category);

        $this->assertCalled(method: 'GET', path: '/Things/a/Sub/b', expectedArgs: ['a', 'b']);
    }

    public function testRouteParamsAreUrlDecoded(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:name', [$this, 'routeCallback'], 'get');
        $this->registry->AddCategory($category);

        $this->assertCalled(method: 'GET', path: '/Things/a%20b', expectedArgs: ['a b']);
    }

    public function testRouteEndingInSlashMatchesWithAndWithoutIt(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/', [$this, 'routeCallback'], 'all');
        $this->registry->AddCategory($category);

        $this->assertSame('/Things[/]', $this->routePattern('all'));
        $this->assertCalled(method: 'GET', path: '/Things/', expectedArgs: []);
        $this->assertCalled(method: 'GET', path: '/Things', expectedArgs: []);
    }

    public function testRouteNotEndingInSlashDoesNotMatchWithOne(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:' . WebServiceParams::UserId, [$this, 'routeCallback'], 'get');
        $category->AddGet('/Status', [$this, 'routeCallback'], 'status');
        $this->registry->AddCategory($category);

        $this->assertNotMatched(method: 'GET', path: '/Things/5/', message: 'ID route');
        $this->assertNotMatched(method: 'GET', path: '/Things/Status/', message: 'static route');
    }

    public function testStaticRouteIsNotTakenForAnId(): void
    {
        $category = new SlimWebServiceRegistryCategory('Reservations');
        $category->AddGet('/:' . WebServiceParams::ReferenceNumber, [$this, 'routeCallback'], 'get');
        $category->AddGet('/', [$this, 'routeCallback'], 'all');
        $this->registry->AddCategory($category);

        $this->assertCalled(method: 'GET', path: '/Reservations/', expectedArgs: []);
        $this->assertCalled(method: 'GET', path: '/Reservations/abc', expectedArgs: ['abc']);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function routerPatternProvider(): array
    {
        return [
            'root' => ['/', '/'],
            'static' => ['/Resources/Status/Reasons', '/Resources/Status/Reasons'],
            'collection' => ['/Users/', '/Users[/]'],
            'numeric ID' => ['/Users/:userId', '/Users/{userId:[0-9]{1,10}}'],
            'numeric ID with sub path' => ['/Users/:userId/Password', '/Users/{userId:[0-9]{1,10}}/Password'],
            'unrestricted parameter' => ['/Reservations/:referenceNumber', '/Reservations/{referenceNumber}'],
            'category ID' => ['/Attributes/Category/:categoryId', '/Attributes/Category/{categoryId:[0-9]{1,10}}'],
        ];
    }

    #[DataProvider('routerPatternProvider')]
    public function testConvertsRouteToRouterPattern(string $route, string $expected): void
    {
        $this->assertSame($expected, SlimWebServiceRegistry::ToRouterPattern($route));
    }

    public function testServiceResponseBecomesTheResponse(): void
    {
        $server = $this->server;
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddPost('/', [$this, 'routeCallback'], 'create');
        $this->registry->AddCategory($category);
        $this->onCall = function () use ($server): void {
            $restResponse = new RestResponse();
            $restResponse->message = $server->GetRequest()->name;
            $server->WriteResponse($restResponse, RestResponse::CREATED_CODE);
        };

        $response = $this->dispatch(method: 'POST', path: '/Things/', body: '{"name":"thing"}');

        $this->assertSame(RestResponse::CREATED_CODE, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertSame('{"links":[],"message":"thing"}', (string) $response->getBody());
    }

    public function testUnlistedRouteIsRegisteredButBelongsToNoCategory(): void
    {
        $this->registry->AddUnlistedGet(
            route: '/Help',
            callback: function (): void {
                echo '<html>help</html>';
            },
            routeName: 'Help'
        );

        $response = $this->dispatch(method: 'GET', path: '/Help');

        $this->assertSame(RestResponse::OK_CODE, $response->getStatusCode());
        $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
        $this->assertSame('<html>help</html>', (string) $response->getBody());
        $this->assertSame('/base/Help', $this->server->GetServiceUrl('Help'));
        $this->assertFalse($this->registry->IsSecure('Help'));
        $this->assertSame([], $this->registry->Categories());
    }

    public function testOutputBufferIsClosedWhenTheServiceFails(): void
    {
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/', [$this, 'routeCallback'], 'all');
        $this->registry->AddCategory($category);
        $this->onCall = function (): void {
            echo 'partial output';
            throw new RuntimeException('service failed');
        };
        $bufferLevel = ob_get_level();

        try {
            $this->dispatch(method: 'GET', path: '/Things/');
            $this->fail('The exception of the service was swallowed');
        } catch (RuntimeException $exception) {
            $this->assertSame('service failed', $exception->getMessage());
        }

        $this->assertSame($bufferLevel, ob_get_level());
    }

    /**
     * Called by routeCallback(), if set
     *
     * @var (Closure(): void)|null
     */
    private ?Closure $onCall = null;

    // Tagged like a real handler so SlimServiceRegistration can parse its
    // PHPDoc. Only @ tags may appear: a prose line makes that parser loop.
    /**
     * @name RouteCallback
     * @response RestResponse
     */
    public function routeCallback(): void
    {
        $this->calls[] = func_get_args();
        if ($this->onCall !== null) {
            ($this->onCall)();
        }
    }

    private function routePattern(string $routeName): string
    {
        return $this->slim->getRouteCollector()->getNamedRoute($routeName)->getPattern();
    }

    private function dispatch(string $method, string $path, string $body = ''): ResponseInterface
    {
        $request = (new ServerRequestFactory())->createServerRequest($method, $path);
        $request->getBody()->write($body);

        return $this->slim->handle($request);
    }

    /**
     * @param array<int, string> $expectedArgs
     */
    private function assertCalled(string $method, string $path, array $expectedArgs): void
    {
        $this->calls = [];

        $this->dispatch(method: $method, path: $path);

        $this->assertSame([$expectedArgs], $this->calls, $method . ' ' . $path);
    }

    private function assertNotMatched(string $method, string $path, string $message): void
    {
        $this->calls = [];

        try {
            $this->dispatch(method: $method, path: $path);
            $this->fail($message . ': ' . $method . ' ' . $path . ' matched a route');
        } catch (HttpNotFoundException) {
            $this->assertSame([], $this->calls, $message);
        }
    }
}
