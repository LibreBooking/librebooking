<?php

require_once(ROOT_DIR . 'lib/external/Slim/Slim.php');
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
        return $this->response->name;
    }
}

class TestSlimResponse
{
    public $name;

    public function name($name)
    {
        $this->name = $name;
    }
}

class TestSlim extends Slim\Slim
{
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
    /**
     * @var TestSlimResponse
     */
    public $getResponse;

    public function __construct()
    {
        $this->getResponse = new TestSlimResponse();
    }

    /**
     * @param $route
     * @param $callback
     * @return TestSlimResponse
     */
    public function get()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new TestSlimResponse();
        $this->gets[] = new TestSlimCall($route, $callback, $response);
        return $response;
    }

    /**
     * @param $route
     * @param $callback
     * @return TestSlimResponse
     */
    public function post()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new TestSlimResponse();
        $this->posts[] = new TestSlimCall($route, $callback, $response);
        return $response;
    }

    /**
     * @param $route
     * @param $callback
     * @return TestSlimResponse
     */
    public function delete()
    {
        $args = func_get_args();
        $route = $args[0];
        $callback = $args[1];

        $response = new TestSlimResponse();
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

    private function cb()
    {
        // callback function for tests
    }
}
