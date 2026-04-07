<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

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
    private App $app;
    private SlimServer $server;

    /**
     * @var array|SlimWebServiceRegistryCategory[]
     */
    private array $categories = [];

    /**
     * @var array
     */
    private array $secureRoutes = [];

    /**
     * @var array
     */
    private array $adminRoutes = [];

    /**
     * @var array
     */
    private array $apiPermissionRoutes = [];

    public function __construct(App $app, SlimServer $server)
    {
        $this->app = $app;
        $this->server = $server;
    }

    /**
     * @param SlimWebServiceRegistryCategory $category
     */
    public function AddCategory(SlimWebServiceRegistryCategory $category): void
    {
        foreach ($category->Gets() as $registration) {
            $this->app->get($registration->Route(), $this->wrapCallback($registration->Callback()))
                ->setName($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: false, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Posts() as $registration) {
            $this->app->post($registration->Route(), $this->wrapCallback($registration->Callback()))
                ->setName($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Deletes() as $registration) {
            $this->app->delete($registration->Route(), $this->wrapCallback($registration->Callback()))
                ->setName($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        $this->categories[] = $category;
    }

    private function wrapCallback(mixed $callback): \Closure
    {
        $server = $this->server;
        return function (ServerRequestInterface $request, ResponseInterface $response, array $args) use ($callback, $server): ResponseInterface {
            $server->SetRequest($request);
            $server->SetCurrentResponse($response);
            call_user_func_array($callback, array_values($args));
            return $server->GetCurrentResponse();
        };
    }

    /**
     * @return SlimWebServiceRegistryCategory[]
     */
    public function Categories(): array
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
    public function IsSecure($routeName): bool
    {
        return array_key_exists($routeName, $this->secureRoutes);
    }

    /**
     * @param string $routeName
     * @return bool
     */
    public function IsLimitedToAdmin($routeName): bool
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

    private function SecureRegistration(SlimServiceRegistration $registration, ApiPermissions $apiPermissions): void
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
