<?php

declare(strict_types=1);

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class ApiAccessMiddlewareTest extends TestBase
{
    private const SESSION_TOKEN = 'sessionToken';
    private const USER_ID = 15;
    private const RO_GROUP_ID = 7;

    private const OPEN_PATH = '/Things/Status';
    private const SECURE_PATH = '/Things/5';
    private const ADMIN_PATH = '/Things/';

    private const SERVICE_BODY = '{"links":[],"message":"service was called"}';

    private App $slim;

    private IUserSessionRepository&MockObject $userSessionRepository;

    private FakeWebServiceUserSession $session;

    private int $serviceCalls = 0;

    public function setUp(): void
    {
        parent::setup();

        $this->fakeConfig->SetKey(ConfigKeys::API_ENABLED, true);
        // The session of the API request is used, not the one of the web UI
        $this->fakeServer->UserSession = new NullUserSession();

        $this->session = new FakeWebServiceUserSession(self::USER_ID);
        $this->session->SessionToken = self::SESSION_TOKEN;
        $this->session->SessionExpiration = WebServiceExpiration::Create();
        $this->session->IsAdmin = false;

        $this->userSessionRepository = $this->createMock(IUserSessionRepository::class);
        $this->serviceCalls = 0;

        $this->buildApp(roGroupId: null);
    }

    public function testDisabledApiIsUnavailable(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::API_ENABLED, false);

        foreach ([self::OPEN_PATH, self::SECURE_PATH] as $path) {
            $response = $this->dispatch(method: 'GET', path: $path);

            $this->assertSame(RestResponse::SERVICE_UNAVAILABLE, $response->getStatusCode());
            $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
            $this->assertSame(
                'LibreBooking API is disabled. Set ["api"]["enabled"] = true',
                (string) $response->getBody()
            );
        }
        $this->assertSame(0, $this->serviceCalls);
    }

    public function testOpenRouteNeedsNoSession(): void
    {
        $this->userSessionRepository->expects($this->never())->method('LoadBySessionToken');

        $response = $this->dispatch(method: 'GET', path: self::OPEN_PATH);

        $this->assertServiceWasCalled($response);
    }

    public function testSecureRouteWithoutCredentialsIsUnauthorized(): void
    {
        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH);

        $this->assertSame(RestResponse::UNAUTHORIZED_CODE, $response->getStatusCode());
        $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
        $this->assertSame(
            'You must be authenticated in order to access this service.<br/>'
            . 'http://localhost/base/Authentication/Authenticate',
            (string) $response->getBody()
        );
        $this->assertSame(0, $this->serviceCalls);
    }

    public function testSecureRouteWithUnknownSessionIsUnauthorized(): void
    {
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn(null);

        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH, withCredentials: true);

        $this->assertSame(RestResponse::UNAUTHORIZED_CODE, $response->getStatusCode());
        $this->assertSame(0, $this->serviceCalls);
    }

    public function testSecureRouteWithValidSessionReachesTheService(): void
    {
        $this->userSessionRepository->expects($this->once())
            ->method('LoadBySessionToken')
            ->with($this->equalTo(self::SESSION_TOKEN))
            ->willReturn($this->session);

        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH, withCredentials: true);

        $this->assertServiceWasCalled($response);
        $this->assertTrue($this->session->_SessionExtended);
    }

    public function testAdminRouteIsUnauthorizedForOtherUsers(): void
    {
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn($this->session);

        $response = $this->dispatch(method: 'POST', path: self::ADMIN_PATH, withCredentials: true);

        $this->assertSame(RestResponse::UNAUTHORIZED_CODE, $response->getStatusCode());
        $this->assertSame(0, $this->serviceCalls);
    }

    public function testAdminRouteReachesTheServiceForAdmins(): void
    {
        $this->session->IsAdmin = true;
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn($this->session);

        $response = $this->dispatch(method: 'POST', path: self::ADMIN_PATH, withCredentials: true);

        $this->assertServiceWasCalled($response);
    }

    public function testUserOutsideTheApiGroupIsForbidden(): void
    {
        $this->buildApp(roGroupId: self::RO_GROUP_ID);
        $this->setGroupMembers(userIds: [self::USER_ID + 1]);
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn($this->session);

        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH, withCredentials: true);

        $this->assertSame(RestResponse::FORBIDDEN_CODE, $response->getStatusCode());
        $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
        $this->assertSame('You are not authorized to access this service.<br/>', (string) $response->getBody());
        $this->assertSame(0, $this->serviceCalls);
    }

    public function testUserInsideTheApiGroupReachesTheService(): void
    {
        $this->buildApp(roGroupId: self::RO_GROUP_ID);
        $this->setGroupMembers(userIds: [self::USER_ID]);
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn($this->session);

        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH, withCredentials: true);

        $this->assertServiceWasCalled($response);
    }

    public function testAdminReachesTheServiceWithoutBeingInTheApiGroup(): void
    {
        $this->buildApp(roGroupId: self::RO_GROUP_ID);
        $this->setGroupMembers(userIds: []);
        $this->session->IsAdmin = true;
        $this->userSessionRepository->method('LoadBySessionToken')->willReturn($this->session);

        $response = $this->dispatch(method: 'GET', path: self::SECURE_PATH, withCredentials: true);

        $this->assertServiceWasCalled($response);
    }

    // Only @ tags may appear in this PHPDoc: SlimServiceRegistration parses it.
    /**
     * @name RouteCallback
     * @response RestResponse
     */
    public function routeCallback(): void
    {
        $this->serviceCalls++;
        $restResponse = new RestResponse();
        $restResponse->message = 'service was called';
        ServiceLocator::GetApiServer()->WriteResponse($restResponse);
    }

    private function buildApp(?int $roGroupId): void
    {
        $this->slim = AppFactory::create();
        $server = new SlimServer(basePath: '/base');
        ServiceLocator::SetApiServer(apiServer: $server);
        $registry = new SlimWebServiceRegistry(slim: $this->slim, server: $server);

        $authentication = new SlimWebServiceRegistryCategory('Authentication');
        $authentication->AddPost('Authenticate', [$this, 'routeCallback'], WebServices::Login);
        $registry->AddCategory($authentication);

        $things = new SlimWebServiceRegistryCategory('Things', roGroupId: $roGroupId);
        $things->AddGet('/Status', [$this, 'routeCallback'], 'open');
        $things->AddSecureGet('/:' . WebServiceParams::UserId, [$this, 'routeCallback'], 'secure');
        $things->AddAdminPost('/', [$this, 'routeCallback'], 'admin');
        $registry->AddCategory($things);

        $this->slim->add(new ApiAccessMiddleware(
            registry: $registry,
            server: $server,
            security: new WebServiceSecurity($this->userSessionRepository),
            responseFactory: $this->slim->getResponseFactory(),
        ));
        $this->slim->addRoutingMiddleware();
    }

    /**
     * Sets what the queries of GroupRepository::LoadById() return: the
     * group, its users, its permissions and its roles.
     *
     * @param array<int, int> $userIds
     */
    private function setGroupMembers(array $userIds): void
    {
        $groupRow = [
            ColumnNames::GROUP_ID => self::RO_GROUP_ID,
            ColumnNames::GROUP_NAME => 'API users',
            ColumnNames::GROUP_ADMIN_GROUP_ID => null,
            ColumnNames::GROUP_ISDEFAULT => 0,
        ];
        $userRows = array_map(
            fn (int $userId): array => [ColumnNames::USER_ID => $userId],
            $userIds
        );

        $this->db->SetRow(0, [$groupRow]);
        $this->db->SetRow(1, $userRows);
        $this->db->SetRow(2, []);
        $this->db->SetRow(3, []);
    }

    private function dispatch(string $method, string $path, bool $withCredentials = false): ResponseInterface
    {
        $request = (new ServerRequestFactory())
            ->createServerRequest($method, 'http://localhost' . $path)
            ->withHeader('Host', 'localhost');
        if ($withCredentials) {
            $request = $request
                ->withHeader(WebServiceHeaders::SESSION_TOKEN, self::SESSION_TOKEN)
                ->withHeader(WebServiceHeaders::USER_ID, (string) self::USER_ID);
        }
        // WebServiceSecurity logs the request URI
        $_SERVER['REQUEST_URI'] = $path;

        return $this->slim->handle($request);
    }

    private function assertServiceWasCalled(ResponseInterface $response): void
    {
        $this->assertSame(RestResponse::OK_CODE, $response->getStatusCode());
        $this->assertSame(self::SERVICE_BODY, (string) $response->getBody());
        $this->assertSame(1, $this->serviceCalls);
    }
}
