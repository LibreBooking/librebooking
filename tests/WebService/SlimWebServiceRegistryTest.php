<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

use Slim\Factory\AppFactory;

class SlimWebServiceRegistryTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    /**
     * @return array{0: \Slim\App, 1: SlimWebServiceRegistry}
     */
    private function createRegistry(): array
    {
        $app = AppFactory::create();
        $server = $this->createMock(SlimServer::class);
        $registry = new SlimWebServiceRegistry($app, $server);
        return [$app, $registry];
    }

    public function testRegistersCategoryWithSlim(): void
    {
        $callback = [$this, 'cb'];

        [$app, $registry] = $this->createRegistry();

        $c1Name = 'Something';
        $c2Name = 'SomethingElse';

        $category1 = new SlimWebServiceRegistryCategory($c1Name);
        $category2 = new SlimWebServiceRegistryCategory($c2Name);

        $c1p1 = '/post/1/';
        $c1p2 = '/get/{id1}';
        $c1p3 = '/delete/{id1}';

        $c2p1 = 'post/2/';
        $c2p2 = 'get/{id2}';
        $c2p3 = 'delete/{id2}';

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddPost($c1p1, $callback, $c1p1name);
        $category1->AddGet($c1p2, $callback, $c1p2name);
        $category1->AddDelete($c1p3, $callback, $c1p3name);

        $c2p1name = '2';
        $c2p2name = '3';
        $c2p3name = '4';

        $category2->AddPost($c2p1, $callback, $c2p1name);
        $category2->AddGet($c2p2, $callback, $c2p2name);
        $category2->AddDelete($c2p3, $callback, $c2p3name);

        $registry->AddCategory($category1);
        $registry->AddCategory($category2);

        $routesByName = $this->getRoutesByName($app);

        $this->assertArrayHasKey($c1p1name, $routesByName);
        $this->assertEquals('/Something/post/1', $routesByName[$c1p1name]->getPattern());
        $this->assertEquals(['POST'], $routesByName[$c1p1name]->getMethods());
        $this->assertFalse($registry->IsSecure($c1p1name));

        $this->assertArrayHasKey($c1p2name, $routesByName);
        $this->assertEquals('/Something/get/{id1}', $routesByName[$c1p2name]->getPattern());
        $this->assertEquals(['GET'], $routesByName[$c1p2name]->getMethods());
        $this->assertFalse($registry->IsSecure($c1p2name));

        $this->assertArrayHasKey($c1p3name, $routesByName);
        $this->assertEquals('/Something/delete/{id1}', $routesByName[$c1p3name]->getPattern());
        $this->assertEquals(['DELETE'], $routesByName[$c1p3name]->getMethods());
        $this->assertFalse($registry->IsSecure($c1p3name));

        $this->assertArrayHasKey($c2p1name, $routesByName);
        $this->assertEquals('/SomethingElse/post/2', $routesByName[$c2p1name]->getPattern());
        $this->assertArrayHasKey($c2p2name, $routesByName);
        $this->assertEquals('/SomethingElse/get/{id2}', $routesByName[$c2p2name]->getPattern());
        $this->assertArrayHasKey($c2p3name, $routesByName);
        $this->assertEquals('/SomethingElse/delete/{id2}', $routesByName[$c2p3name]->getPattern());
    }

    public function testRegistersSecureRoute(): void
    {
        $callback = [$this, 'cb'];

        [$app, $registry] = $this->createRegistry();

        $c1Name = 'Something';
        $category1 = new SlimWebServiceRegistryCategory($c1Name);

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddSecurePost('/post/1/', $callback, $c1p1name);
        $category1->AddSecureGet('/get/{id}', $callback, $c1p2name);
        $category1->AddSecureDelete('/delete/{id}', $callback, $c1p3name);

        $registry->AddCategory($category1);

        $routesByName = $this->getRoutesByName($app);

        $this->assertArrayHasKey($c1p1name, $routesByName);
        $this->assertEquals(['POST'], $routesByName[$c1p1name]->getMethods());

        $this->assertArrayHasKey($c1p2name, $routesByName);
        $this->assertEquals(['GET'], $routesByName[$c1p2name]->getMethods());

        $this->assertArrayHasKey($c1p3name, $routesByName);
        $this->assertEquals(['DELETE'], $routesByName[$c1p3name]->getMethods());

        $this->assertTrue($registry->IsSecure($c1p1name));
        $this->assertTrue($registry->IsSecure($c1p2name));
        $this->assertTrue($registry->IsSecure($c1p3name));
    }

    public function testRegistersAdminRoute(): void
    {
        $callback = [$this, 'cb'];

        [$app, $registry] = $this->createRegistry();

        $c1Name = 'Something';
        $category1 = new SlimWebServiceRegistryCategory($c1Name);

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddAdminPost('/post/1/', $callback, $c1p1name);
        $category1->AddAdminGet('/get/{id}', $callback, $c1p2name);
        $category1->AddAdminDelete('/delete/{id}', $callback, $c1p3name);

        $registry->AddCategory($category1);

        $this->assertTrue($registry->IsSecure($c1p1name));
        $this->assertTrue($registry->IsSecure($c1p2name));
        $this->assertTrue($registry->IsSecure($c1p3name));

        $this->assertTrue($registry->IsLimitedToAdmin($c1p1name));
        $this->assertTrue($registry->IsLimitedToAdmin($c1p2name));
        $this->assertTrue($registry->IsLimitedToAdmin($c1p3name));
    }

    public function cb(): void
    {
        // callback function for tests
    }

    /**
     * @return array<string, \Slim\Interfaces\RouteInterface>
     */
    private function getRoutesByName(\Slim\App $app): array
    {
        $routesByName = [];
        foreach ($app->getRouteCollector()->getRoutes() as $route) {
            $routesByName[$route->getName()] = $route;
        }
        return $routesByName;
    }

}
