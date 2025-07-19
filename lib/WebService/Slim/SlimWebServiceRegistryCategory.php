<?php

class SlimWebServiceRegistryCategory
{
    private $name;
    private $gets = [];
    private $posts = [];
    private $deletes = [];
    private int|string|null $roGroupId;  // User group allowed Read-Only access to API Category
    private int|string|null $rwGroupId;  // User group allowed Read-Write access to API Category

    public function __construct($name, int|string|null $roGroupId = null, int|string|null $rwGroupId = null)
    {
        $this->name = $name;
        $this->roGroupId = $roGroupId;
        $this->rwGroupId = $rwGroupId;
    }

    /**
     * @return array|SlimServiceRegistration[]
     */
    public function Gets()
    {
        return $this->gets;
    }

    /**
     * @return array|SlimServiceRegistration[]
     */
    public function Posts()
    {
        return $this->posts;
    }

    /**
     * @return array|SlimServiceRegistration[]
     */
    public function Deletes()
    {
        return $this->deletes;
    }

    public function GetRoGroupId(): int|string|null {
        return $this->roGroupId;
    }

    public function GetRwGroupId(): int|string|null {
        return $this->rwGroupId;
    }

    public function UserAllowedRoAccess(int|string $userId): bool {
        if (is_null($this->roGroupId)) {
            return true;
        }
        return UserGroupHelper::isUserInGroup(groupId: $this->roGroupId, userId: $userId);
    }

    public function UserAllowedRwAccess(int|string $userId): bool {
        if (is_null($this->rwGroupId)) {
            return true;
        }
        return UserGroupHelper::isUserInGroup(groupId: $this->rwGroupId, userId: $userId);
    }

    public function AddGet($route, $callback, $routeName)
    {
        $this->gets[] = new SlimServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddPost($route, $callback, $routeName)
    {
        $this->posts[] = new SlimServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddDelete($route, $callback, $routeName)
    {
        $this->deletes[] = new SlimServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddSecureGet($route, $callback, $routeName)
    {
        $this->gets[] = new SlimSecureServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddSecurePost($route, $callback, $routeName)
    {
        $this->posts[] = new SlimSecureServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddSecureDelete($route, $callback, $routeName)
    {
        $this->deletes[] = new SlimSecureServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddAdminGet($route, $callback, $routeName)
    {
        $this->gets[] = new SlimAdminServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddAdminPost($route, $callback, $routeName)
    {
        $this->posts[] = new SlimAdminServiceRegistration($this->name, $route, $callback, $routeName);
    }

    public function AddAdminDelete($route, $callback, $routeName)
    {
        $this->deletes[] = new SlimAdminServiceRegistration($this->name, $route, $callback, $routeName);
    }

    /**
     * @return mixed
     */
    public function Name()
    {
        return $this->name;
    }
}
