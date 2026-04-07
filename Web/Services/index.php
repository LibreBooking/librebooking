<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'lib/ComposerDependenciesGuard.php');
EnsureComposerDependenciesInstalledForRequest();

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/WebService/Slim/namespace.php');

require_once(ROOT_DIR . 'WebServices/AuthenticationWebService.php');
require_once(ROOT_DIR . 'WebServices/ReservationsWebService.php');
require_once(ROOT_DIR . 'WebServices/ReservationWriteWebService.php');
require_once(ROOT_DIR . 'WebServices/ResourcesWebService.php');
require_once(ROOT_DIR . 'WebServices/ResourcesWriteWebService.php');
require_once(ROOT_DIR . 'WebServices/UsersWebService.php');
require_once(ROOT_DIR . 'WebServices/UsersWriteWebService.php');
require_once(ROOT_DIR . 'WebServices/SchedulesWebService.php');
require_once(ROOT_DIR . 'WebServices/AttributesWebService.php');
require_once(ROOT_DIR . 'WebServices/AttributesWriteWebService.php');
require_once(ROOT_DIR . 'WebServices/GroupsWebService.php');
require_once(ROOT_DIR . 'WebServices/GroupsWriteWebService.php');
require_once(ROOT_DIR . 'WebServices/AccessoriesWebService.php');
require_once(ROOT_DIR . 'WebServices/AccountWebService.php');

require_once(ROOT_DIR . 'Web/Services/Help/ApiHelpPage.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteContext;

set_exception_handler(function ($e) {
    Log::Error('Uncaught bootstrap exception: %s', $e);
    $accept    = $_SERVER['HTTP_ACCEPT'] ?? '';
    $wantsJson = stripos($accept, 'application/json') !== false;

    header('Content-Type: ' . ($wantsJson ? 'application/json; charset=utf-8'
                                          : 'text/plain; charset=utf-8'), true, 500);
    echo $wantsJson
        ? json_encode(['error' => 'server_error', 'message' => $e->getMessage()], JSON_UNESCAPED_SLASHES)
        : 'Server error: ' . $e->getMessage();
    exit;
});

$app = AppFactory::create();
$routeParser = $app->getRouteCollector()->getRouteParser();

$server = new SlimServer($routeParser);
ServiceLocator::SetApiServer(apiServer: $server);
$registry = new SlimWebServiceRegistry($app, $server);

RegisterHelp($app, $registry);
RegisterAuthentication($server, $registry);
RegisterReservations($server, $registry);
RegisterResources($server, $registry);
RegisterUsers($server, $registry);
RegisterSchedules($server, $registry);
RegisterAttributes($server, $registry);
RegisterGroups($server, $registry);
RegisterAccessories($server, $registry);
RegisterAccounts($server, $registry);

$responseFactory = $app->getResponseFactory();

// Auth middleware: runs after routing (so route name is available), before route handler
$app->add(function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($server, $registry, $responseFactory): ResponseInterface {
    $server->SetRequest($request);

    if (!Configuration::Instance()->GetKey(ConfigKeys::API_ENABLED, new BooleanConverter())) {
        $response = $responseFactory->createResponse(RestResponse::SERVICE_UNAVAILABLE);
        $response->getBody()->write((string) json_encode(['message' => 'LibreBooking API is disabled. Set ["api"]["enabled"] = true']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $routeContext = RouteContext::fromRequest($request);
    $route = $routeContext->getRoute();

    if ($route !== null) {
        $routeName = $route->getName();
        if ($registry->IsSecure($routeName)) {
            $security = new WebServiceSecurity(new UserSessionRepository());
            $wasHandled = $security->HandleSecureRequest($server, $registry->IsLimitedToAdmin($routeName));
            if (!$wasHandled) {
                $response = $responseFactory->createResponse(RestResponse::UNAUTHORIZED_CODE);
                $response->getBody()->write((string) json_encode([
                    'message' => 'You must be authenticated in order to access this service.',
                    'links' => [['href' => $server->GetFullServiceUrl(WebServices::Login), 'title' => WebServices::Login]],
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }

            $userSession = ServiceLocator::GetUserSession();
            // Admin users can always use the API
            if (!$userSession->IsAdmin && !$registry->IsUserAllowedApiAccess(routeName: $routeName, userId: $userSession->UserId)) {
                $response = $responseFactory->createResponse(RestResponse::FORBIDDEN_CODE);
                $response->getBody()->write((string) json_encode(['message' => 'You are not authorized to access this service.']));
                return $response->withHeader('Content-Type', 'application/json');
            }
        }
    }

    return $handler->handle($request);
});

// Routing middleware must be added after auth middleware so it runs before auth in the request pipeline
$app->addRoutingMiddleware();

// Error middleware must be added last so it is the outermost layer and catches all exceptions
$errorMiddleware = $app->addErrorMiddleware(displayErrorDetails: false, logErrors: true, logErrorDetails: true);
$errorMiddleware->setDefaultErrorHandler(function (
    ServerRequestInterface $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($responseFactory): ResponseInterface {
    // Preserve HTTP status codes from Slim's own HTTP exceptions (404, 405, etc.)
    // so that missing/wrong-method routes return the correct client error, not 500.
    if ($exception instanceof \Slim\Exception\HttpException) {
        $response = $responseFactory->createResponse($exception->getCode());
        // RFC 7231 requires the Allow header on 405 responses.
        if ($exception instanceof \Slim\Exception\HttpMethodNotAllowedException) {
            $response = $response->withHeader('Allow', implode(', ', $exception->getAllowedMethods()));
        }
        $response->getBody()->write(json_encode(['message' => $exception->getMessage()], JSON_UNESCAPED_SLASHES));
        return $response->withHeader('Content-Type', 'application/json');
    }

    require_once(ROOT_DIR . 'lib/Common/Logging/Log.php');
    Log::Error('Slim Exception. %s', $exception);
    $response = $responseFactory->createResponse(RestResponse::SERVER_ERROR);
    $response->getBody()->write('Exception was logged.');
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();

function RegisterHelp(\Slim\App $app, SlimWebServiceRegistry $registry): void
{
    $renderHelp = function (ServerRequestInterface $request, ResponseInterface $response) use ($registry): ResponseInterface {
        ob_start();
        ApiHelpPage::Render($registry);
        $content = ob_get_clean();
        $response->getBody()->write($content);
        return $response;
    };

    $app->get('/', $renderHelp)->setName('Default');
    $app->get('/Help', $renderHelp)->setName('Help');
}

function RegisterAuthentication(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $api_access_group_id = GetConfigGroup(ConfigKeys::API_AUTHENTICATION_GROUP);
    $webService = new AuthenticationWebService(
        $server,
        new WebServiceAuthentication(PluginManager::Instance()->LoadAuthentication(), new UserSessionRepository()),
        api_access_group_id: $api_access_group_id
    );

    $category = new SlimWebServiceRegistryCategory('Authentication');
    $category->AddPost('SignOut', [$webService, 'SignOut'], WebServices::Logout);
    $category->AddPost('Authenticate', [$webService, 'Authenticate'], WebServices::Login);
    $registry->AddCategory($category);
}

function RegisterReservations(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $readService = new ReservationsWebService($server, new ReservationViewRepository(), new PrivacyFilter(new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization())), new AttributeService(new AttributeRepository()));
    $writeService = new ReservationWriteWebService($server, new ReservationSaveController(new ReservationPresenterFactory()));

    $roGroupId = GetConfigGroup(ConfigKeys::API_RESERVATIONS_RO_GROUP);
    $rwGroupId = GetConfigGroup(ConfigKeys::API_RESERVATIONS_RW_GROUP);
    $category = new SlimWebServiceRegistryCategory('Reservations', roGroupId: $roGroupId, rwGroupId: $rwGroupId);

    $category->AddSecurePost('/', [$writeService, 'Create'], WebServices::CreateReservation);
    $category->AddSecureGet('/', [$readService, 'GetReservations'], WebServices::AllReservations);
    $category->AddSecureGet('/{referenceNumber}', [$readService, 'GetReservation'], WebServices::GetReservation);
    $category->AddSecurePost('/{referenceNumber}', [$writeService, 'Update'], WebServices::UpdateReservation);
    $category->AddSecurePost('/{referenceNumber}/Approval', [$writeService, 'Approve'], WebServices::ApproveReservation);
    $category->AddSecurePost('/{referenceNumber}/CheckIn', [$writeService, 'Checkin'], WebServices::CheckinReservation);
    $category->AddSecurePost('/{referenceNumber}/CheckOut', [$writeService, 'Checkout'], WebServices::CheckoutReservation);
    $category->AddSecureDelete('/{referenceNumber}', [$writeService, 'Delete'], WebServices::DeleteReservation);

    $registry->AddCategory($category);
}

function RegisterResources(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $resourceRepository = new ResourceRepository();
    $attributeService = new AttributeService(new AttributeRepository());
    $webService = new ResourcesWebService($server, $resourceRepository, $attributeService, new ReservationViewRepository());
    $writeWebService = new ResourcesWriteWebService($server, new ResourceSaveController($resourceRepository, new ResourceRequestValidator($attributeService)));

    $roGroupId = GetConfigGroup(ConfigKeys::API_RESOURCES_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Resources', roGroupId: $roGroupId);

    $category->AddGet('/Status', [$webService, 'GetStatuses'], WebServices::GetStatuses);
    $category->AddSecureGet('/', [$webService, 'GetAll'], WebServices::AllResources);
    $category->AddSecureGet('/Status/Reasons', [$webService, 'GetStatusReasons'], WebServices::GetStatusReasons);
    $category->AddSecureGet('/Availability', [$webService, 'GetAvailability'], WebServices::AllAvailability);
    $category->AddSecureGet('/Groups', [$webService, 'GetGroups'], WebServices::GetResourceGroups);
    $category->AddSecureGet('/Types', [$webService, 'GetTypes'], WebServices::GetResourceTypes);
    $category->AddSecureGet('/{resourceId}', [$webService, 'GetResource'], WebServices::GetResource);
    $category->AddSecureGet('/{resourceId}/Availability', [$webService, 'GetAvailability'], WebServices::GetResourceAvailability);
    $category->AddAdminPost('/', [$writeWebService, 'Create'], WebServices::CreateResource);
    $category->AddAdminPost('/{resourceId}', [$writeWebService, 'Update'], WebServices::UpdateResource);
    $category->AddAdminDelete('/{resourceId}', [$writeWebService, 'Delete'], WebServices::DeleteResource);
    $registry->AddCategory($category);
}

function RegisterAccessories(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $webService = new AccessoriesWebService($server, new ResourceRepository(), new AccessoryRepository());

    $roGroupId = GetConfigGroup(ConfigKeys::API_ACCESSORIES_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Accessories', roGroupId: $roGroupId);

    $category->AddSecureGet('/', [$webService, 'GetAll'], WebServices::AllAccessories);
    $category->AddSecureGet('/{accessoryId}', [$webService, 'GetAccessory'], WebServices::GetAccessory);
    $registry->AddCategory($category);
}

function RegisterUsers(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $attributeService = new AttributeService(new AttributeRepository());
    $webService = new UsersWebService($server, new UserRepositoryFactory(), $attributeService);
    $writeWebService = new UsersWriteWebService(
        $server,
        new UserSaveController(new ManageUsersServiceFactory(), new UserRequestValidator($attributeService, new UserRepository()))
    );

    $roGroupId = GetConfigGroup(ConfigKeys::API_USERS_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Users', roGroupId: $roGroupId);

    $category->AddSecureGet('/', [$webService, 'GetUsers'], WebServices::AllUsers);
    $category->AddSecureGet('/{userId}', [$webService, 'GetUser'], WebServices::GetUser);
    $category->AddAdminPost('/', [$writeWebService, 'Create'], WebServices::CreateUser);
    $category->AddAdminPost('/{userId}', [$writeWebService, 'Update'], WebServices::UpdateUser);
    $category->AddAdminPost('/{userId}/Password', [$writeWebService, 'UpdatePassword'], WebServices::UpdatePassword);
    $category->AddAdminDelete('/{userId}', [$writeWebService, 'Delete'], WebServices::DeleteUser);
    $registry->AddCategory($category);
}

function RegisterSchedules(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $webService = new SchedulesWebService($server, new ScheduleRepository(), new PrivacyFilter(new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization())));

    $roGroupId = GetConfigGroup(ConfigKeys::API_SCHEDULES_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Schedules', roGroupId: $roGroupId);

    $category->AddSecureGet('/', [$webService, 'GetSchedules'], WebServices::AllSchedules);
    $category->AddSecureGet('/{scheduleId}', [$webService, 'GetSchedule'], WebServices::GetSchedule);
    $category->AddSecureGet('/{scheduleId}/Slots', [$webService, 'GetSlots'], WebServices::GetScheduleSlots);
    $registry->AddCategory($category);
}

function RegisterAttributes(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $webService = new AttributesWebService($server, new AttributeService(new AttributeRepository()));
    $writeWebService = new AttributesWriteWebService($server, new AttributeSaveController(new AttributeRepository()));

    $roGroupId = GetConfigGroup(ConfigKeys::API_ATTRIBUTES_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Attributes', roGroupId: $roGroupId);

    $category->AddSecureGet('Category/{categoryId}', [$webService, 'GetAttributes'], WebServices::AllCustomAttributes);
    $category->AddSecureGet('/{attributeId}', [$webService, 'GetAttribute'], WebServices::GetCustomAttribute);
    $category->AddAdminPost('/', [$writeWebService, 'Create'], WebServices::CreateCustomAttribute);
    $category->AddAdminPost('/{attributeId}', [$writeWebService, 'Update'], WebServices::UpdateCustomAttribute);
    $category->AddAdminDelete('/{attributeId}', [$writeWebService, 'Delete'], WebServices::DeleteCustomAttribute);
    $registry->AddCategory(category: $category);
}

function RegisterGroups(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $groupRepository = new GroupRepository();
    $webService = new GroupsWebService($server, $groupRepository, $groupRepository);
    $writeWebService = new GroupsWriteWebService($server, new GroupSaveController($groupRepository, new ResourceRepository(), new ScheduleRepository()));

    $roGroupId = GetConfigGroup(ConfigKeys::API_GROUPS_RO_GROUP);
    $category = new SlimWebServiceRegistryCategory('Groups', roGroupId: $roGroupId);

    $category->AddSecureGet('/', [$webService, 'GetGroups'], WebServices::AllGroups);
    $category->AddSecureGet('/{groupId}', [$webService, 'GetGroup'], WebServices::GetGroup);
    $category->AddAdminPost('/', [$writeWebService, 'Create'], WebServices::CreateGroup);
    $category->AddAdminPost('/{groupId}', [$writeWebService, 'Update'], WebServices::UpdateGroup);
    $category->AddAdminPost('/{groupId}/Roles', [$writeWebService, 'Roles'], WebServices::UpdateGroupRoles);
    $category->AddAdminPost('/{groupId}/Permissions', [$writeWebService, 'Permissions'], WebServices::UpdateGroupPermissions);
    $category->AddAdminPost('/{groupId}/Users', [$writeWebService, 'Users'], WebServices::UpdateGroupUsers);
    $category->AddAdminDelete('/{groupId}', [$writeWebService, 'Delete'], WebServices::DeleteGroup);

    $registry->AddCategory($category);
}

function RegisterAccounts(SlimServer $server, SlimWebServiceRegistry $registry): void
{
    $userRepository = new UserRepository();
    $attributeService = new AttributeService(new AttributeRepository());
    $passwordEncryption = new PasswordEncryption();
    $registration = new Registration($passwordEncryption, $userRepository, new RegistrationNotificationStrategy(), new RegistrationPermissionStrategy(), new GroupRepository());
    $controller = new AccountController($registration, $userRepository, new AccountRequestValidator($attributeService, $userRepository), $passwordEncryption, $attributeService);

    $webService = new AccountWebService($server, $controller);

    $roGroupId = GetConfigGroup(ConfigKeys::API_ACCOUNTS_RO_GROUP);
    $rwGroupId = GetConfigGroup(ConfigKeys::API_ACCOUNTS_RW_GROUP);
    $category = new SlimWebServiceRegistryCategory('Accounts', roGroupId: $roGroupId, rwGroupId: $rwGroupId);

    $category->AddPost('/', [$webService, 'Create'], WebServices::CreateAccount);
    $category->AddSecurePost('/{userId}', [$webService, 'Update'], WebServices::UpdateAccount);
    $category->AddSecurePost('/{userId}/Password', [$webService, 'UpdatePassword'], WebServices::UpdateAccountPassword);
    $category->AddSecureGet('/{userId}', [$webService, 'GetAccount'], WebServices::GetAccount);

    $registry->AddCategory($category);
}


/**
 * Resolve a group ID from a configuration key that stores a group name.
 *
 * @param string|array $configDef Configuration key whose value is the desired group name.
 * @return string|null The matching group ID, or `null` if the config value is empty.
 */
function GetConfigGroup($configDef): string|null
{
    $groupName = Configuration::Instance()->GetKey($configDef) ?? '';
    if ($groupName == '') {
        return null;
    }
    $groupRepository = new GroupRepository();
    $groups = $groupRepository->GetList()->Results();
    foreach ($groups as $group) {
        if ($group->Name == $groupName) {
            return $group->Id();
        }
    }

    $confKeyLabel = is_string($configDef) ? $configDef : 'config key';
    throw new \RuntimeException(
        "Group '{$groupName}' for {$confKeyLabel} not found. Please fix config.php."
    );
}
