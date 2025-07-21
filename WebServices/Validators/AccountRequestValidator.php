<?php

require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'WebServices/Validators/RequestRequiredValueValidator.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/CreateAccountRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/UpdateAccountRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/Account/UpdateAccountPasswordRequest.php');

interface IAccountRequestValidator
{
    /**
     * @param CreateAccountRequest $request
     * @return string[]
     */
    public function ValidateCreate($request);

    /**
     * @param UpdateAccountRequest $request
     * @param WebServiceUserSession $session
     * @return string[]
     */
    public function ValidateUpdate($request, WebServiceUserSession $session);

    /**
     * @param UpdateAccountPasswordRequest $request
     * @param WebServiceUserSession $session
     * @return string[]
     */
    public function ValidatePasswordUpdate($request, WebServiceUserSession $session);
}

class AccountRequestValidator implements IAccountRequestValidator
{
    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IUserRepository
     */
    private $userRepository;

    public function __construct(IAttributeService $attributeService, IUserRepository $userRepository)
    {
        $this->attributeService = $attributeService;
        $this->userRepository = $userRepository;
    }

    public function ValidateCreate($request)
    {
        if (empty($request)) {
            return ['Request was not properly formatted'];
        }

        $validators[] = new RequestRequiredValueValidator($request->password, 'password');
        $validators[] = new UniqueEmailValidator($this->userRepository, $request->emailAddress);
        $validators[] = new UniqueUserNameValidator($this->userRepository, $request->userName);

        return $this->Validate($request, $validators);
    }

    public function ValidateUpdate($request, WebServiceUserSession $session)
    {
        if (empty($request)) {
            return ['Request was not properly formatted'];
        }

        $validators[] = new UniqueEmailValidator($this->userRepository, $request->emailAddress, $session->UserId);
        $validators[] = new UniqueUserNameValidator($this->userRepository, $request->userName, $session->UserId);

        return $this->Validate($request, $validators);
    }


    public function ValidatePasswordUpdate($request, WebServiceUserSession $session)
    {
        $validators[] = new PasswordComplexityValidator($request->newPassword);
        $validators[] = new PasswordValidator($request->currentPassword, $this->userRepository->LoadById($session->UserId));

        $errors = [];
        /** @var IValidator $validator */
        foreach ($validators as $validator) {
            $validator->Validate();
            if (!$validator->IsValid()) {
                foreach ($validator->Messages() as $message) {
                    $errors[] = $message;
                }
            }
        }
        return $errors;
    }

    /**
     * @param AccountRequestBase $request
     * @param IValidator[] $additionalValidators
     * @return array|string[]
     */
    private function Validate($request, $additionalValidators = [])
    {
        $validators = $additionalValidators;
        $validators[] = new RequestRequiredValueValidator($request->firstName, 'firstName');
        $validators[] = new RequestRequiredValueValidator($request->lastName, 'lastName');
        $validators[] = new RequestRequiredValueValidator($request->userName, 'userName');
        $validators[] = new RequestRequiredValueValidator($request->emailAddress, 'emailAddress');
        $validators[] = new EmailValidator($request->emailAddress);

        $attributes = [];
        foreach ($request->GetCustomAttributes() as $attribute) {
            $attributes[] = new AttributeValue($attribute->attributeId, $attribute->attributeValue);
        }
        $validators[] = new AttributeValidator($this->attributeService, CustomAttributeCategory::USER, $attributes);

        $errors = [];
        /** @var IValidator $validator */
        foreach ($validators as $validator) {
            $validator->Validate();
            if (!$validator->IsValid()) {
                foreach ($validator->Messages() as $message) {
                    $errors[] = $message;
                }
            }
        }
        return $errors;
    }
}
