<?php

require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');

class AttributeValidator extends ValidatorBase
{
    /**
     * @var IAttributeService
     */
    private $service;

    /**
     * @var CustomAttributeCategory|int
     */
    private $category;

    /**
     * @var array|AttributeValue[]
     */
    private $attributes;

    /**
     * @var array|string[]
     */
    private $messages;

    /**
     * @var int|null
     */
    private $entityId;

    /**
     * @var bool
     */
    private $ignoreEmpty;

    /**
     * @var bool
     */
    private $isAdmin;

    /**
     * @param IAttributeService $service
     * @param $category int|CustomAttributeCategory
     * @param $attributes AttributeValue|array|AttributeValue[]
     * @param $entityId int
     * @param bool $ignoreEmpty
     * @param bool $isAdmin
     * @param int|null $resourceTypeId
     */
    public function __construct(IAttributeService $service, $category, $attributes, $entityId = null, $ignoreEmpty = false, $isAdmin = false, private $resourceTypeId = null)
    {
        $this->service = $service;
        $this->category = $category;
        $this->attributes = is_array($attributes) ? $attributes : [$attributes];
        $this->entityId = $entityId;
        $this->ignoreEmpty = $ignoreEmpty;
        $this->isAdmin = $isAdmin;
    }

    /**
     * @return void
     */
    public function Validate()
    {
        if (empty($this->attributes)) {
            $this->isValid = true;
            return;
        }

        $result = $this->service->Validate($this->category, $this->attributes, $this->entityId, $this->ignoreEmpty, $this->isAdmin, $this->resourceTypeId);
        $this->isValid = $result->IsValid();
        $this->messages = $result->Errors();
    }

    public function Messages()
    {
        return $this->messages;
    }
}

class AttributeValidatorInline extends AttributeValidator
{
    public function ReturnsErrorResponse()
    {
        return true;
    }
}
