<?php

/**
 * Include plugins
 */
require_once(ROOT_DIR . 'lib/Config/namespace.php'); // namespace.php is an include files of classes

class PluginManager
{
    /**
     * @var PluginManager
     */
    private static $_instance = null;

    private $cache = [];

    private function __construct()
    {
    }

    /**
     * @static
     * @return PluginManager
     */
    public static function Instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new PluginManager();
        }
        return self::$_instance;
    }

    /**
     * @static
     * @param $pluginManager PluginManager
     * @return void
     */
    public static function SetInstance($pluginManager)
    {
        self::$_instance = $pluginManager;
    }

    /**
     * Loads the configured Authentication plugin, if one exists
     * If no plugin exists, the default Authentication class is returned
     *
     * @return IAuthentication the authorization class to use
     */
    public function LoadAuthentication()
    {
        require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
        require_once(ROOT_DIR . 'Domain/Access/namespace.php');
        $authentication = new Authentication($this->LoadAuthorization(), new UserRepository(), new GroupRepository());
        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_AUTHENTICATION, 'Authentication', $authentication);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $authentication;
    }

    /**
     * Loads the configured Permission plugin, if one exists
     * If no plugin exists, the default PermissionService class is returned
     *
     * @return IPermissionService
     */
    public function LoadPermission()
    {
        require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

        $resourcePermissionStore = new ResourcePermissionStore(new ScheduleUserRepository());
        $permissionService = new PermissionService($resourcePermissionStore);

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_PERMISSION, 'Permission', $permissionService);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $permissionService;
    }

    /**
     * Loads the configured Authorization plugin, if one exists
     * If no plugin exists, the default PermissionService class is returned
     *
     * @return IAuthorizationService
     */
    public function LoadAuthorization()
    {
        require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

        $authorizationService = new AuthorizationService(new UserRepository());

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_AUTHORIZATION, 'Authorization', $authorizationService);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $authorizationService;
    }

    /**
     * Loads the configured PreReservation plugin, if one exists
     * If no plugin exists, the default PreReservationFactory class is returned
     *
     * @return IPreReservationFactory
     */
    public function LoadPreReservation()
    {
        require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

        $factory = new PreReservationFactory();

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_PRERESERVATION, 'PreReservation', $factory);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $factory;
    }

    /**
     * Loads the configured PreReservation plugin, if one exists
     * If no plugin exists, the default PreReservationFactory class is returned
     *
     * @return IPostReservationFactory
     */
    public function LoadPostReservation()
    {
        require_once(ROOT_DIR . 'lib/Application/Reservation/Notification/namespace.php');

        $factory = new PostReservationFactory();

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_POSTRESERVATION, 'PostReservation', $factory);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $factory;
    }

    /**
     * Loads the configured PostRegistration plugin, if one exists
     * If no plugin exists, the default PostRegistration class is returned
     *
     * @return IPostRegistration
     */
    public function LoadPostRegistration()
    {
        require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');

        $userRepository = new UserRepository();
        $postRegistration = new PostRegistration(new WebAuthentication(self::LoadAuthentication()), new AccountActivation($userRepository, $userRepository));

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_POSTREGISTRATION, 'PostRegistration', $postRegistration);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $postRegistration;
    }

    /**
     * Loads the configured Styling plugin, if one exists
     * If no plugin exists, the default StylingFactory class is returned
     *
     * @return IStylingFactory
     */
    public function LoadStyling()
    {
        require_once(ROOT_DIR . 'lib/Application/Styling/namespace.php');

        $factory = new StylingFactory();

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_STYLING, 'Styling', $factory);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $factory;
    }

    /**
     * Loads the configured Export plugin, if one exists
     * If no plugin exists, the default ExportFactory class is returned
     *
     * @return IExportFactory
     */
    public function LoadExport()
    {
        require_once(ROOT_DIR . 'lib/Application/Export/namespace.php');

        $factory = new ExportFactory();

        $plugin = $this->LoadPlugin(ConfigKeys::PLUGIN_EXPORT, 'Export', $factory);

        if (!is_null($plugin)) {
            return $plugin;
        }

        return $factory;
    }

    /**
     * @param array $configDef key to use
     * @param string $pluginSubDirectory subdirectory name under 'plugins'
     * @param mixed $baseImplementation the base implementation of the plugin.  allows decorating
     * @return mixed|null plugin implementation
     */
    private function LoadPlugin($configDef, $pluginSubDirectory, $baseImplementation)
    {
        $configKey = $configDef['key'];
        if (!$this->Cached($configKey)) {
            $plugin = Configuration::Instance()->GetKey($configDef);
            $pluginFile = ROOT_DIR . "plugins/$pluginSubDirectory/$plugin/$plugin.php";

            if (!empty($plugin) && file_exists($pluginFile)) {
                try {
                    Log::Debug('Loading plugin. Type=%s, Plugin=%s', $configKey, $plugin);
                    require_once($pluginFile);
                    $this->Cache($configKey, new $plugin($baseImplementation));
                } catch (Exception $ex) {
                    Log::Error('Error loading plugin. Type=%s, Plugin=%s', $configKey, $plugin);
                }
            } else {
                $this->Cache($configKey, null);
            }
        }
        return $this->GetCached($configKey);
    }

    private function Cached($cacheKey)
    {
        return array_key_exists($cacheKey, $this->cache);
    }

    private function Cache($cacheKey, $object)
    {
        $this->cache[$cacheKey] = $object;
    }

    private function GetCached($cacheKey)
    {
        return $this->cache[$cacheKey];
    }
}
