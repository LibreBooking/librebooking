<?php

require_once(ROOT_DIR . 'WebServices/Requests/User/CreateUserRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/User/UpdateUserRequest.php');
require_once(ROOT_DIR . 'WebServices/Validators/UserRequestValidator.php');
require_once(ROOT_DIR . 'lib/Application/User/namespace.php');

interface IUserSaveController
{
    /**
     * @param CreateUserRequest $request
     * @param WebServiceUserSession $session
     * @return UserControllerResult
     */
    public function Create($request, $session);

    /**
     * @param int $userId
     * @param UpdateUserRequest $request
     * @param WebServiceUserSession $session
     * @return UserControllerResult
     */
    public function Update($userId, $request, $session);

    /**
     * @param int $userId
     * @param WebServiceUserSession $session
     * @return UserControllerResult
     */
    public function Delete($userId, $session);

    /**
     * @param int $userId
     * @param string $password
     * @param WebServiceUserSession $session
     * @return UserControllerResult
     */
    public function UpdatePassword($userId, $password, $session);
}

class UserSaveController implements IUserSaveController
{
    /**
     * @var IManageUsersServiceFactory
     */
    private $serviceFactory;

    public function __construct(IManageUsersServiceFactory $serviceFactory, private readonly IUserRequestValidator $requestValidator)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function Create($request, $session)
    {
        $errors = $this->requestValidator->ValidateCreateRequest($request);

        if (!empty($errors)) {
            return new UserControllerResult(null, $errors);
        }

        $userService = $this->serviceFactory->CreateAdmin();

        $extraAttributes = [UserAttribute::Phone => $request->phone, UserAttribute::Organization => $request->organization, UserAttribute::Position => $request->position];
        $customAttributes = [];
        foreach ($request->GetCustomAttributes() as $attribute) {
            $customAttributes[] = new AttributeValue($attribute->attributeId, $attribute->attributeValue);
        }

        $user = $userService->AddUser(
            $request->userName,
            $request->emailAddress,
            $request->firstName,
            $request->lastName,
            $request->password,
            $request->timezone,
            $request->language,
            Pages::DEFAULT_HOMEPAGE_ID,
            $extraAttributes,
            $customAttributes
        );

        $userService->ChangeGroups($user, $request->groups);

        return new UserControllerResult($user->Id());
    }

    public function Update($userId, $request, $session)
    {
        $errors = $this->requestValidator->ValidateUpdateRequest($userId, $request);

        if (!empty($errors)) {
            return new UserControllerResult(null, $errors);
        }

        $userService = $this->serviceFactory->CreateAdmin();

        $extraAttributes = [UserAttribute::Phone => $request->phone, UserAttribute::Organization => $request->organization, UserAttribute::Position => $request->position];
        $customAttributes = [];
        foreach ($request->GetCustomAttributes() as $attribute) {
            $customAttributes[] = new AttributeValue($attribute->attributeId, $attribute->attributeValue);
        }

        $user = $userService->UpdateUser(
            $userId,
            $request->userName,
            $request->emailAddress,
            $request->firstName,
            $request->lastName,
            $request->timezone,
            $extraAttributes,
            $customAttributes
        );

        //		$userService->ChangeAttributes($userId, $customAttributes);

        $userService->ChangeGroups($user, $request->groups);

        return new UserControllerResult($userId);
    }

    public function Delete($userId, $session)
    {
        $userService = $this->serviceFactory->CreateAdmin();
        $userService->DeleteUser($userId);

        return new UserControllerResult($userId);
    }

    public function UpdatePassword($userId, $password, $session)
    {
        $errors = $this->requestValidator->ValidateUpdatePasswordRequest($userId, $password);

        if (!empty($errors)) {
            return new UserControllerResult(null, $errors);
        }

        $userService = $this->serviceFactory->CreateAdmin();
        $userService->UpdatePassword($userId, $password);

        return new UserControllerResult($userId);
    }
}

class UserControllerResult
{
    /**
     * @param int $userId
     * @param array $errors
     */
    public function __construct(private $userId, private $errors = [])
    {
    }

    /**
     * @return bool
     */
    public function WasSuccessful()
    {
        return !empty($this->userId) && empty($this->errors);
    }

    /**
     * @return int
     */
    public function UserId()
    {
        return $this->userId;
    }

    /**
     * @return array|string[]
     */
    public function Errors()
    {
        return $this->errors;
    }
}
