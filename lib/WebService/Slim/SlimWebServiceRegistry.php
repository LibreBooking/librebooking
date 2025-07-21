<?php

require_once(ROOT_DIR . 'lib/external/Slim/Slim.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class ApiPermissions
{
    public function __construct(
        public bool $isWrite,
        public int|string|null $roGroupId,
        public int|string|null $rwGroupId
    ) { }

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

    public function IsSet(): bool {
        return (is_numeric($this->roGroupId) || is_numeric($this->rwGroupId));
    }

}

class SlimWebServiceRegistry
{
    /**
     * @var Slim\Slim
     */
    private $slim;

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

    public function __construct(Slim\Slim $slim)
    {
        $this->slim = $slim;
    }

    /**
     * @param SlimWebServiceRegistryCategory $category
     */
    public function AddCategory(SlimWebServiceRegistryCategory $category)
    {
        foreach ($category->Gets() as $registration) {
            $this->slim->get($registration->Route(), $registration->Callback())->name($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: false, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Posts() as $registration) {
            $this->slim->post($registration->Route(), $registration->Callback())->name($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        foreach ($category->Deletes() as $registration) {
            $this->slim->delete($registration->Route(), $registration->Callback())->name($registration->RouteName());
            $this->SecureRegistration(
                $registration,
                apiPermissions: new ApiPermissions(isWrite: true, roGroupId: $category->GetRoGroupId(), rwGroupId: $category->GetRwGroupId())
            );
        }

        $this->categories[] = $category;
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

    private function SecureRegistration(SlimServiceRegistration $registration, ApiPermissions $apiPermissions) {
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
