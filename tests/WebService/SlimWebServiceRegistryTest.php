<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'lib/external/Slim/Slim.php');
require_once(ROOT_DIR . 'lib/external/Slim/Route.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

class TestSlimCall
{
    public $route;
    public $callback;
    public $response;

    public function __construct($route, $callback, $response)
    {
        $this->route = $route;
        $this->callback = $callback;
        $this->response = $response;
    }

    public function name()
    {
        return $this->response->getName();
    }
}

class TestSlim extends Slim\Slim
{
    public function __construct()
    {
    }

    /**
     * @var array|TestSlimCall[]
     */
    public $gets = [];
    /**
     * @var array|TestSlimCall[]
     */
    public $posts = [];
    /**
     * @var array|TestSlimCall[]
     */
    public $deletes = [];
    public function get()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new Slim\Route($route, $callback);
        $this->gets[] = new TestSlimCall($route, $callback, $response);
        return $response;
    }

    public function post()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new Slim\Route($route, $callback);
        $this->posts[] = new TestSlimCall($route, $callback, $response);
        return $response;
    }

    public function delete()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new Slim\Route($route, $callback);
        $this->deletes[] = new TestSlimCall($route, $callback, $response);
        return $response;
    }
}


class SlimWebServiceRegistryTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testRegistersCategoryWithSlim()
    {
        $callback = [$this, 'cb'];

        $slim = new TestSlim();

        $registry = new SlimWebServiceRegistry($slim);

        $c1Name = 'Something';
        $c2Name = 'SomethingElse';

        $category1 = new SlimWebServiceRegistryCategory($c1Name);
        $category2 = new SlimWebServiceRegistryCategory($c2Name);

        $c1p1 = '/post/1/';
        $c1p2 = '/get/:1';
        $c1p3 = '/delete/:1';

        $c2p1 = 'post/2/';
        $c2p2 = 'get/:2';
        $c2p3 = 'delete/:2';

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddPost($c1p1, $callback, $c1p1name);
        $category1->AddGet($c1p2, $callback, $c1p2name);
        $category1->AddDelete($c1p3, $callback, $c1p3name);

        $category2->AddPost($c2p1, $callback, '2');
        $category2->AddGet($c2p2, $callback, '3');
        $category2->AddDelete($c2p3, $callback, '4');

        $registry->AddCategory($category1);
        $registry->AddCategory($category2);

        $this->assertEquals('/Something/post/1', $slim->posts[0]->route);
        $this->assertEquals($callback, $slim->posts[0]->callback);
        $this->assertEquals($c1p1name, $slim->posts[0]->name());
        $this->assertFalse($registry->IsSecure($c1p1name));

        $this->assertEquals('/Something/get/:1', $slim->gets[0]->route);
        $this->assertEquals($c1p2name, $slim->gets[0]->name());
        $this->assertEquals($callback, $slim->gets[0]->callback);
        $this->assertFalse($registry->IsSecure($c1p2name));

        $this->assertEquals('/Something/delete/:1', $slim->deletes[0]->route);
        $this->assertEquals($c1p3name, $slim->deletes[0]->name());
        $this->assertEquals($callback, $slim->deletes[0]->callback);
        $this->assertFalse($registry->IsSecure($c1p3name));

        $this->assertEquals('/SomethingElse/post/2', $slim->posts[1]->route);
        $this->assertEquals($callback, $slim->posts[1]->callback);
        $this->assertEquals('/SomethingElse/get/:2', $slim->gets[1]->route);
        $this->assertEquals($callback, $slim->gets[1]->callback);
        $this->assertEquals('/SomethingElse/delete/:2', $slim->deletes[1]->route);
        $this->assertEquals($callback, $slim->deletes[1]->callback);
    }

    public function testRegistersSecureRoute()
    {
        $callback = [$this, 'cb'];

        $slim = new TestSlim();

        $registry = new SlimWebServiceRegistry($slim);

        $c1Name = 'Something';

        $category1 = new SlimWebServiceRegistryCategory($c1Name);

        $c1p1 = '/post/1/';
        $c1p2 = '/get/:1';
        $c1p3 = '/delete/:1';

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddSecurePost($c1p1, $callback, $c1p1name);
        $category1->AddSecureGet($c1p2, $callback, $c1p2name);
        $category1->AddSecureDelete($c1p3, $callback, $c1p3name);

        $registry->AddCategory($category1);

        $this->assertEquals('/Something/post/1', $slim->posts[0]->route);
        $this->assertEquals($callback, $slim->posts[0]->callback);
        $this->assertEquals($c1p1name, $slim->posts[0]->name());

        $this->assertEquals('/Something/get/:1', $slim->gets[0]->route);
        $this->assertEquals($c1p2name, $slim->gets[0]->name());
        $this->assertEquals($callback, $slim->gets[0]->callback);

        $this->assertEquals('/Something/delete/:1', $slim->deletes[0]->route);
        $this->assertEquals($c1p3name, $slim->deletes[0]->name());
        $this->assertEquals($callback, $slim->deletes[0]->callback);

        $this->assertTrue($registry->IsSecure($c1p1name));
        $this->assertTrue($registry->IsSecure($c1p2name));
        $this->assertTrue($registry->IsSecure($c1p3name));
    }

    public function testRegistersAdminRoute()
    {
        $callback = [$this, 'cb'];

        $slim = new TestSlim();

        $registry = new SlimWebServiceRegistry($slim);

        $c1Name = 'Something';

        $category1 = new SlimWebServiceRegistryCategory($c1Name);

        $c1p1 = '/post/1/';
        $c1p2 = '/get/:1';
        $c1p3 = '/delete/:1';

        $c1p1name = 'c1p1name';
        $c1p2name = 'c1p2name';
        $c1p3name = 'c1p3name';

        $category1->AddAdminPost($c1p1, $callback, $c1p1name);
        $category1->AddAdminGet($c1p2, $callback, $c1p2name);
        $category1->AddAdminDelete($c1p3, $callback, $c1p3name);

        $registry->AddCategory($category1);

        $this->assertEquals('/Something/post/1', $slim->posts[0]->route);
        $this->assertEquals($callback, $slim->posts[0]->callback);
        $this->assertEquals($c1p1name, $slim->posts[0]->name());

        $this->assertEquals('/Something/get/:1', $slim->gets[0]->route);
        $this->assertEquals($c1p2name, $slim->gets[0]->name());
        $this->assertEquals($callback, $slim->gets[0]->callback);

        $this->assertEquals('/Something/delete/:1', $slim->deletes[0]->route);
        $this->assertEquals($c1p3name, $slim->deletes[0]->name());
        $this->assertEquals($callback, $slim->deletes[0]->callback);

        $this->assertTrue($registry->IsSecure($c1p1name));
        $this->assertTrue($registry->IsSecure($c1p2name));
        $this->assertTrue($registry->IsSecure($c1p3name));

        $this->assertTrue($registry->IsLimitedToAdmin($c1p1name));
        $this->assertTrue($registry->IsLimitedToAdmin($c1p2name));
        $this->assertTrue($registry->IsLimitedToAdmin($c1p3name));
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
        $slim = new TestSlim();
        $registry = new SlimWebServiceRegistry($slim);
        $category = new SlimWebServiceRegistryCategory('Things');
        $category->AddGet('/:' . $param, [$this, 'routeCallback'], 'get');
        $category->AddPost('/:' . $param . '/Sub', [$this, 'routeCallback'], 'post');
        $category->AddDelete('/:' . $param, [$this, 'routeCallback'], 'delete');
        $registry->AddCategory($category);

        $routes = [
            ['/Things/%s', $slim->gets[0]->response],
            ['/Things/%s/Sub', $slim->posts[0]->response],
            ['/Things/%s', $slim->deletes[0]->response],
        ];

        foreach ($routes as [$uriFormat, $route]) {
            $this->assertTrue($route->matches(sprintf($uriFormat, '5')));
            $this->assertSame(['5'], array_values($route->getParams()));
            $this->assertTrue($route->matches(sprintf($uriFormat, '1234567890')));

            $this->assertFalse($route->matches(sprintf($uriFormat, 'abc')), 'non-numeric id');
            $this->assertFalse($route->matches(sprintf($uriFormat, '12abc')), 'id with trailing text');
            $this->assertFalse($route->matches(sprintf($uriFormat, '-1')), 'negative id');
            $this->assertFalse($route->matches(sprintf($uriFormat, '1.5')), 'decimal id');
            $this->assertFalse($route->matches(sprintf($uriFormat, '12345678901')), 'id longer than 10 digits');
        }
    }

    public function testReferenceNumberRouteParamIsNotRestricted(): void
    {
        $slim = new TestSlim();
        $registry = new SlimWebServiceRegistry($slim);
        $category = new SlimWebServiceRegistryCategory('Reservations');
        $category->AddGet('/:' . WebServiceParams::ReferenceNumber, [$this, 'routeCallback'], 'get');
        $registry->AddCategory($category);

        $route = $slim->gets[0]->response;

        $this->assertTrue($route->matches('/Reservations/5f1a2b3c4d5e6'));
        $this->assertSame(['5f1a2b3c4d5e6'], array_values($route->getParams()));
    }

    // Tagged like a real handler so SlimServiceRegistration can parse its
    // PHPDoc. Only @ tags may appear: a prose line makes that parser loop.
    /**
     * @name RouteCallback
     * @response RestResponse
     */
    public function routeCallback(): void
    {
    }

    private function cb()
    {
        // callback function for tests
    }
}
