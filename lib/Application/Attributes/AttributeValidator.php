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
    protected $messages;

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
     */
    public function __construct(IAttributeService $service, $category, $attributes, $entityId = null, $ignoreEmpty = false, $isAdmin = false)
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

        $result = $this->service->Validate($this->category, $this->attributes, $this->entityId, $this->ignoreEmpty, $this->isAdmin);
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
    private bool $attributeMissing;

    /**
     * @param IAttributeService $service
     * @param int|CustomAttributeCategory $category
     * @param AttributeValue|null $attribute null when the submitted attribute
     *                                       name or value is not usable,
     *                                       which fails validation
     * @param int|null $entityId
     * @param bool $ignoreEmpty
     * @param bool $isAdmin
     */
    public function __construct(
        IAttributeService $service,
        $category,
        ?AttributeValue $attribute,
        $entityId = null,
        $ignoreEmpty = false,
        $isAdmin = false
    ) {
        parent::__construct(
            service: $service,
            category: $category,
            attributes: $attribute === null ? [] : [$attribute],
            entityId: $entityId,
            ignoreEmpty: $ignoreEmpty,
            isAdmin: $isAdmin,
        );
        $this->attributeMissing = $attribute === null;
    }

    /**
     * @return void
     */
    public function Validate()
    {
        if ($this->attributeMissing) {
            $this->isValid = false;
            $this->messages = [self::MissingAttributeMessage()];

            return;
        }

        parent::Validate();
    }

    /**
     * The error shown when the attribute name or value of an inline
     * attribute update is not usable.
     */
    public static function MissingAttributeMessage(): string
    {
        $resources = Resources::GetInstance();

        return $resources->GetString(
            key: 'CustomAttributeInvalid',
            args: [$resources->GetString(key: 'Attribute')],
        );
    }

    public function ReturnsErrorResponse()
    {
        return true;
    }
}
