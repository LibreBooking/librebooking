<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'lib/Application/User/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'WebServices/Responses/UsersResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/UserResponse.php');

class UsersWebService
{
    /**
     * @var IRestServer
     */
    private $server;

    /**
     * @var IUserRepositoryFactory
     */
    private $repositoryFactory;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(
        IRestServer $server,
        IUserRepositoryFactory $repositoryFactory,
        IAttributeService $attributeService
    ) {
        $this->server = $server;
        $this->repositoryFactory = $repositoryFactory;
        $this->attributeService = $attributeService;
    }

    /**
     * @name GetAllUsers
     * @description Loads all users that the current user can see.
     * Optional query string parameters: username, email, firstName, lastName, phone, organization, position and any custom attributes.
     * If searching on custom attributes, the query string parameter has to be in the format att#=value.
     * For example, Users/?att1=ExpectedAttribute1Value
     * @response UsersResponse
     * @return void
     */
    public function GetUsers()
    {
        $attributes = $this->attributeService->GetByCategory(CustomAttributeCategory::USER);
        $filter = $this->GetUserFilter($attributes);

        $repository = $this->repositoryFactory->Create($this->server->GetSession());
        $data = $repository->GetList(null, null, null, null, $filter->GetFilter(), AccountStatus::ACTIVE);

        $attributeLabels = [];
        foreach ($attributes as $attribute) {
            $attributeLabels[$attribute->Id()] = $attribute->Label();
        }

        $usersResponse = new UsersResponse($this->server, $data->Results(), $attributeLabels);

        unset($data);
        unset($attributeLabels);

        $this->server->WriteResponse($usersResponse);
    }

    /**
     * @name GetUser
     * @description Loads the requested user by Id
     * @response UserResponse
     * @param int $userId
     * @return void
     */
    public function GetUser($userId)
    {
        $responseCode = RestResponse::OK_CODE;

        $hideUsers = Configuration::Instance()->GetKey(
            ConfigKeys::PRIVACY_HIDE_USER_DETAILS,
            new BooleanConverter()
        );
        $userSession = $this->server->GetSession();

        $repository = $this->repositoryFactory->Create($userSession);
        $user = $repository->LoadById($userId);

        $loadedUserId = $user->Id();
        if (empty($loadedUserId)) {
            $this->server->WriteResponse(RestResponse::NotFound(), RestResponse::NOT_FOUND_CODE);
            return;
        }

        $attributes = $this->attributeService->GetAttributes(CustomAttributeCategory::USER, [$userId]);

        if ($userId == $userSession->UserId || !$hideUsers || $userSession->IsAdmin) {
            $response = new UserResponse($this->server, $user, $attributes);
        } else {
            $me = $repository->LoadById($userSession->UserId);

            if ($me->IsAdminFor($user)) {
                $response = new UserResponse($this->server, $user, $attributes);
            } else {
                $response = RestResponse::Unauthorized();
                $responseCode = RestResponse::UNAUTHORIZED_CODE;
            }
        }

        $this->server->WriteResponse($response, $responseCode);
    }

    /**
     * @param CustomAttribute[] $attributes
     * @return UserFilter
     */
    private function GetUserFilter($attributes)
    {
        $attributeFilters = [];
        foreach ($attributes as $attribute) {
            $attributeValue = $this->server->GetQueryString(WebServiceQueryStringKeys::ATTRIBUTE_PREFIX . $attribute->Id());
            if (!empty($attributeValue)) {
                $attributeFilters[] = new LBAttribute($attribute, $attributeValue);
            }
        }

        $filter = new UserFilter(
            $this->server->GetQueryString(WebServiceQueryStringKeys::USERNAME),
            $this->server->GetQueryString(WebServiceQueryStringKeys::EMAIL),
            $this->server->GetQueryString(WebServiceQueryStringKeys::FIRST_NAME),
            $this->server->GetQueryString(WebServiceQueryStringKeys::LAST_NAME),
            $this->server->GetQueryString(WebServiceQueryStringKeys::PHONE),
            $this->server->GetQueryString(WebServiceQueryStringKeys::ORGANIZATION),
            $this->server->GetQueryString(WebServiceQueryStringKeys::POSITION),
            $attributeFilters
        );

        return $filter;
    }
}
