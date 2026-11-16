<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/SlimServer.php');
require_once(ROOT_DIR . 'lib/WebService/WebServiceParams.php');

class ApiPermissions
{
    public function __construct(
        public bool $isWrite,
        public int|string|null $roGroupId,
        public int|string|null $rwGroupId
    ) {
    }

    public function IsUserAllowedApiAccess(int|string $userId): bool
    {
        if ($this->isWrite) {
            // If a write API, then check if a RW group is set and verify access.
            // If no RW group, then check if a RO group is set and verify access
            if (is_numeric($this->rwGroupId)) {
                return UserGroupHelper::isUserInGroup(groupId: $this->rwGroupId, userId: $userId);
            }
            if (is_numeric($this->roGroupId)) {
                return UserGroupHelper::isUserInGroup(groupId: $this->roGroupId, userId: $userId);
            }
            return true;
        }

        if (is_numeric($this->roGroupId)) {
            return UserGroupHelper::isUserInGroup(groupId: $this->roGroupId, userId: $userId);
        }
        return true;
    }

    public function IsSet(): bool
    {
        return (is_numeric($this->roGroupId) || is_numeric($this->rwGroupId));
    }

}

class SlimWebServiceRegistry
{
    /**
     * Route parameters that carry a numeric database ID.
     */
    public const NUMERIC_ID_PARAMS = [
        WebServiceParams::AccessoryId,
        WebServiceParams::AttributeCategoryId,
        WebServiceParams::AttributeId,
        WebServiceParams::GroupId,
        WebServiceParams::ResourceId,
        WebServiceParams::ScheduleId,
        WebServiceParams::UserId,
    ];

    /**
     * Digits only, at most 10 of them (enough for any INT UNSIGNED ID). A
     * value that does not match makes the route not match, so Slim answers
     * 404 instead of handing a non-numeric string to a handler that expects
     * an int. The length cap keeps the value within PHP's int range.
     */
    public const NUMERIC_ID_PATTERN = '[0-9]{1,10}';

    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private const METHOD_DELETE = 'DELETE';

    /**
     * @var array|SlimWebServiceRegistryCategory[]
     */
    private $categories = [];

    /**
     * @var array
     */
    private $secureRoutes = [];

    /**
     * @var array
     */
    private $adminRoutes = [];

    /**
     * @var array
     */
    private $apiPermissionRoutes = [];

    /**
     * @param RouteCollectorProxyInterface<\Psr\Container\ContainerInterface|null> $slim
     *     The Slim application the routes are added to
     */
    public function __construct(
        private readonly RouteCollectorProxyInterface $slim,
        private readonly SlimServer $server,
    ) {
    }

    /**
     * @param SlimWebServiceRegistryCategory $category
     */
    public function AddCategory(SlimWebServiceRegistryCategory $category)
    {
        foreach ($category->Gets() as $registration) {
            $this->AddRoute(
                method: self::METHOD_GET,
                route: $registration->Route(),
                callback: $registration->Callback(),
                routeName: $registration->RouteName(),
            );
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: false, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Posts() as $registration) {
            $this->AddRoute(
                method: self::METHOD_POST,
                route: $registration->Route(),
                callback: $registration->Callback(),
                routeName: $registration->RouteName(),
            );
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Deletes() as $registration) {
            $this->AddRoute(
                method: self::METHOD_DELETE,
                route: $registration->Route(),
                callback: $registration->Callback(),
                routeName: $registration->RouteName(),
            );
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        $this->categories[] = $category;
    }

    /**
     * Adds a GET route that belongs to no category: it is not secured and is
     * not listed by Categories().
     *
     * @param string $route Route pattern with ":name" placeholders
     */
    public function AddUnlistedGet(string $route, callable $callback, string $routeName): void
    {
        $this->AddRoute(method: self::METHOD_GET, route: $route, callback: $callback, routeName: $routeName);
    }

    /**
     * Converts a route pattern with ":name" placeholders into the pattern
     * syntax of Slim's router.
     *
     * A placeholder matches one path segment; the ones in NUMERIC_ID_PARAMS
     * only match a numeric ID. A pattern that ends in a slash matches with
     * and without that slash; a pattern that does not end in one does not
     * match a URL that does.
     */
    public static function ToRouterPattern(string $route): string
    {
        $pattern = preg_replace_callback(
            '#:(\w+)\+?#',
            function (array $matches): string {
                if (in_array($matches[1], self::NUMERIC_ID_PARAMS, true)) {
                    return '{' . $matches[1] . ':' . self::NUMERIC_ID_PATTERN . '}';
                }
                return '{' . $matches[1] . '}';
            },
            $route
        );

        if ($pattern !== '/' && str_ends_with($pattern, '/')) {
            $pattern = substr($pattern, 0, -1) . '[/]';
        }

        return $pattern;
    }

    private function AddRoute(string $method, string $route, callable $callback, string $routeName): void
    {
        $server = $this->server;
        $server->AddNamedRoute(routeName: $routeName, pattern: $route);

        // The route parameters are passed to the service by position, in the
        // order they appear in the route. array_values() is needed because
        // unpacking an array with string keys would pass named arguments.
        $handler = static function (
            ServerRequestInterface $request,
            ResponseInterface $response,
            array $args
        ) use (
            $server,
            $callback
        ): ResponseInterface {
            $server->SetCurrentRequest($request);
            ob_start();
            try {
                $callback(...array_values($args));
            } finally {
                $output = (string) ob_get_clean();
            }
            return $server->ApplyResponse(response: $response, output: $output);
        };

        $this->slim
            ->map([$method], self::ToRouterPattern($route), $handler)
            ->setName($routeName);
    }

    /**
     * @return SlimWebServiceRegistryCategory[]
     */
    public function Categories()
    {
        $categories = $this->categories;

        usort($categories, function ($a, $b) {
            /**
             * @var SlimWebServiceRegistryCategory $a
             * @var SlimWebServiceRegistryCategory $b
             */

            return ($a->Name() < $b->Name()) ? -1 : 1;
        });

        return $categories;
    }

    /**
     * @param string $routeName
     * @return bool
     */
    public function IsSecure($routeName)
    {
        return array_key_exists($routeName, $this->secureRoutes);
    }

    /**
     * @param string $routeName
     * @return bool
     */
    public function IsLimitedToAdmin($routeName)
    {
        return array_key_exists($routeName, $this->adminRoutes);
    }

    public function IsUserAllowedApiAccess(string $routeName, int|string $userId): bool
    {
        if (!array_key_exists($routeName, $this->apiPermissionRoutes)) {
            return true;
        }
        return $this->apiPermissionRoutes[$routeName]->IsUserAllowedApiAccess(userId: $userId);
    }

    private function SecureRegistration(SlimServiceRegistration $registration, ApiPermissions $apiPermissions)
    {
        if ($registration->IsSecure()) {
            $this->secureRoutes[$registration->RouteName()] = true;
        }

        if ($registration->IsLimitedToAdmin()) {
            $this->adminRoutes[$registration->RouteName()] = true;
        }

        if ($apiPermissions->IsSet()) {
            $this->apiPermissionRoutes[$registration->RouteName()] = $apiPermissions;
        }
    }
}
