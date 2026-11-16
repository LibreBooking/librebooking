<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class SlimServerTest extends TestBase
{
    private const BASE_PATH = '/Web/Services/index.php';

    private SlimServer $server;

    public function setUp(): void
    {
        parent::setup();

        $this->server = new SlimServer(basePath: self::BASE_PATH);
    }

    public function testGetRequestDecodesTheJsonBody(): void
    {
        $request = $this->request(method: 'POST', uri: '/Users/');
        $request->getBody()->write('{"firstName":"Ada","ids":[1,2]}');
        $this->server->SetCurrentRequest($request);

        $body = $this->server->GetRequest();

        $this->assertSame('Ada', $body->firstName);
        $this->assertSame([1, 2], $body->ids);
    }

    public function testGetRequestIsNullWhenTheBodyIsNotJson(): void
    {
        $request = $this->request(method: 'POST', uri: '/Users/');
        $request->getBody()->write('not json');
        $this->server->SetCurrentRequest($request);

        $this->assertNull($this->server->GetRequest());
    }

    public function testGetHeader(): void
    {
        $request = $this->request(method: 'GET', uri: '/Users/')
            ->withHeader(WebServiceHeaders::SESSION_TOKEN, ' token ');
        $this->server->SetCurrentRequest($request);

        $this->assertSame('token', $this->server->GetHeader(WebServiceHeaders::SESSION_TOKEN));
        $this->assertSame('token', $this->server->GetHeader(strtolower(WebServiceHeaders::SESSION_TOKEN)));
        $this->assertNull($this->server->GetHeader(WebServiceHeaders::USER_ID));
    }

    public function testGetQueryString(): void
    {
        $request = $this->request(method: 'GET', uri: '/Reservations/?userId=5&resourceId=')
            ->withQueryParams(['userId' => '5', 'resourceId' => '']);
        $this->server->SetCurrentRequest($request);

        $this->assertSame('5', $this->server->GetQueryString('userId'));
        $this->assertSame('', $this->server->GetQueryString('resourceId'));
        $this->assertNull($this->server->GetQueryString('scheduleId'));
    }

    public function testGetUrlIsTheSchemeAndHostOfTheRequest(): void
    {
        $request = $this->request(method: 'GET', uri: 'https://booking.example.com:8443/Users/')
            ->withHeader('Host', 'booking.example.com:8443');
        $this->server->SetCurrentRequest($request);

        $this->assertSame('https://booking.example.com:8443', $this->server->GetUrl());
    }

    public function testGetUrlUsesTheHostTheClientSent(): void
    {
        // As behind a reverse proxy: the client asked for port 8443, the
        // backend web server listens on port 8080.
        $request = (new ServerRequestFactory())->createServerRequest(
            'GET',
            'https://booking.example.com:8080/Users/',
            ['HTTP_HOST' => 'booking.example.com:8443', 'SERVER_PORT' => '8080']
        );
        $this->server->SetCurrentRequest($request);

        $this->assertSame('https://booking.example.com:8443', $this->server->GetUrl());
    }

    public function testServiceUrlStartsWithTheBasePath(): void
    {
        $this->server->AddNamedRoute(routeName: WebServices::AllUsers, pattern: '/Users/');

        $this->assertSame(self::BASE_PATH . '/Users/', $this->server->GetServiceUrl(WebServices::AllUsers));
    }

    public function testServiceUrlFillsPlaceholders(): void
    {
        $this->server->AddNamedRoute(routeName: WebServices::UpdatePassword, pattern: '/Users/:userId/Password');
        $this->server->AddNamedRoute(routeName: 'two', pattern: '/Things/:id/Sub/:idSub');

        $this->assertSame(
            self::BASE_PATH . '/Users/5/Password',
            $this->server->GetServiceUrl(WebServices::UpdatePassword, [WebServiceParams::UserId => 5])
        );
        // ":id" must not be replaced inside ":idSub"
        $this->assertSame(
            self::BASE_PATH . '/Things/1/Sub/:idSub',
            $this->server->GetServiceUrl('two', ['id' => 1])
        );
        $this->assertSame(
            self::BASE_PATH . '/Things/1/Sub/$2',
            $this->server->GetServiceUrl('two', ['id' => 1, 'idSub' => '$2'])
        );
    }

    public function testServiceUrlKeepsPlaceholdersWithoutAValue(): void
    {
        $this->server->AddNamedRoute(routeName: WebServices::GetUser, pattern: '/Users/:userId');

        $this->assertSame(self::BASE_PATH . '/Users/:userId', $this->server->GetServiceUrl(WebServices::GetUser));
        $this->assertSame(
            self::BASE_PATH . '/Users/:userId',
            $this->server->GetServiceUrl(WebServices::GetUser, [WebServiceParams::UserId, 5])
        );
    }

    public function testServiceUrlOfAnUnknownRouteFails(): void
    {
        $this->expectException(RuntimeException::class);

        $this->server->GetServiceUrl('NoSuchRoute');
    }

    public function testFullServiceUrl(): void
    {
        $this->server->AddNamedRoute(routeName: WebServices::GetUser, pattern: '/Users/:userId');
        $request = $this->request(method: 'GET', uri: 'http://localhost/Users/')->withHeader('Host', 'localhost');
        $this->server->SetCurrentRequest($request);

        $this->assertSame(
            'http://localhost' . self::BASE_PATH . '/Users/7',
            $this->server->GetFullServiceUrl(WebServices::GetUser, [WebServiceParams::UserId => 7])
        );
    }

    public function testWrittenResponseBecomesAJsonResponse(): void
    {
        $restResponse = new RestResponse();
        $restResponse->message = 'created';
        $this->server->WriteResponse($restResponse, RestResponse::CREATED_CODE);

        $response = $this->server->ApplyResponse(response: (new ResponseFactory())->createResponse());

        $this->assertSame(RestResponse::CREATED_CODE, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertSame('{"links":[],"message":"created"}', (string) $response->getBody());
    }

    public function testWrittenResponseDefaultsToOk(): void
    {
        $this->server->WriteResponse(new RestResponse());

        $response = $this->server->ApplyResponse(response: (new ResponseFactory())->createResponse());

        $this->assertSame(RestResponse::OK_CODE, $response->getStatusCode());
    }

    public function testOutputOnlyBecomesAnHtmlResponse(): void
    {
        $response = $this->server->ApplyResponse(
            response: (new ResponseFactory())->createResponse(),
            output: '<html></html>'
        );

        $this->assertSame(RestResponse::OK_CODE, $response->getStatusCode());
        $this->assertSame('text/html', $response->getHeaderLine('Content-Type'));
        $this->assertSame('<html></html>', (string) $response->getBody());
    }

    public function testAppliedResponseIsNotCarriedIntoTheNextOne(): void
    {
        $this->server->WriteResponse(new RestResponse(), RestResponse::CREATED_CODE);
        $this->server->ApplyResponse(response: (new ResponseFactory())->createResponse());

        $response = $this->server->ApplyResponse(response: (new ResponseFactory())->createResponse());

        $this->assertSame(RestResponse::OK_CODE, $response->getStatusCode());
        $this->assertSame('', (string) $response->getBody());
    }

    public function testReadingWithoutARequestFails(): void
    {
        $this->expectException(LogicException::class);

        $this->server->GetHeader(WebServiceHeaders::USER_ID);
    }

    public function testSession(): void
    {
        $session = new FakeWebServiceUserSession(123);

        $this->server->SetSession($session);

        $this->assertSame($session, $this->server->GetSession());
    }

    private function request(string $method, string $uri): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest($method, $uri);
    }
}
