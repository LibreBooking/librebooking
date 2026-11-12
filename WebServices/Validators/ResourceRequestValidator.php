<?php

require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');
require_once(ROOT_DIR . 'WebServices/Validators/RequestRequiredValueValidator.php');
require_once(ROOT_DIR . 'WebServices/Validators/TimeIntervalValidator.php');
require_once(ROOT_DIR . 'WebServices/Requests/Resource/ResourceRequest.php');
require_once(ROOT_DIR . 'Domain/Access/GroupRepository.php');

interface IResourceRequestValidator
{
    /**
     * @param ResourceRequest $createRequest
     * @return array|string[]
     */
    public function ValidateCreateRequest($createRequest);

    /**
     * @param int $resourceId
     * @param ResourceRequest $updateRequest
     * @return array|string[]
     */
    public function ValidateUpdateRequest($resourceId, $updateRequest);

    /**
     * @param int $resourceId
     * @return array|string[]
     */
    public function ValidateDeleteRequest($resourceId);
}

class ResourceRequestValidator implements IResourceRequestValidator
{
    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(IAttributeService $attributeService, private readonly IGroupRepository $groupRepository)
    {
        $this->attributeService = $attributeService;
    }

    public function ValidateCreateRequest($createRequest)
    {
        return $this->ValidateCommon($createRequest);
    }

    /**
     * @param ResourceRequest $request
     * @return array
     */
    private function ValidateCommon($request)
    {
        if (empty($request)) {
            return ['Request was not properly formatted'];
        }
        $errors = [];

        if (isset($request->adminGroupId)) {
            if (!is_int($request->adminGroupId) || $request->adminGroupId <= 0) {
                $errors[] = 'adminGroupId must be a positive integer or null';
            } else {
                $group = $this->groupRepository->LoadById(groupId: $request->adminGroupId);
                if ($group === null || $group instanceof NullGroup) {
                    $errors[] = 'adminGroupId must identify an existing group';
                } elseif (!in_array(RoleLevel::RESOURCE_ADMIN, $group->RoleIds())) {
                    $errors[] = 'adminGroupId must identify a group with the Resource Administrator role';
                }
            }
        }

        /** @var IValidator[] $validators */
        $validators = [];
        $validators[] = new RequestRequiredValueValidator($request->name, 'name');
        $validators[] = new RequestRequiredValueValidator($request->scheduleId, 'scheduleId');
        $validators[] = new TimeIntervalValidator($request->minLength, 'minLength');
        $validators[] = new TimeIntervalValidator($request->maxLength, 'maxLength');
        $validators[] = new TimeIntervalValidator($request->minNotice, 'minNotice');
        $validators[] = new TimeIntervalValidator($request->maxNotice, 'maxNotice');

        $attributes = [];
        foreach ($request->GetCustomAttributes() as $attribute) {
            $attributes[] = new AttributeValue($attribute->attributeId, $attribute->attributeValue);
        }
        $validators[] = new AttributeValidator($this->attributeService, CustomAttributeCategory::RESOURCE, $attributes);


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

    public function ValidateUpdateRequest($resourceId, $updateRequest)
    {
        if (empty($resourceId)) {
            return ['resourceId is required'];
        }

        return $this->ValidateCommon($updateRequest);
    }

    public function ValidateDeleteRequest($resourceId)
    {
        if (empty($resourceId)) {
            return ['resourceId is required'];
        }

        return [];
    }
}
