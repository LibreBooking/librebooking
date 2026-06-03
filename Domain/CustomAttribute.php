<?php

class CustomAttributeTypes
{
    public const SINGLE_LINE_TEXTBOX = 1;
    public const MULTI_LINE_TEXTBOX = 2;
    public const SELECT_LIST = 3;
    public const CHECKBOX = 4;
    public const DATETIME = 5;
}

class CustomAttributeCategory
{
    public const RESERVATION = 1;
    public const USER = 2;
    //const GROUP = 3;
    public const RESOURCE = 4;
    public const RESOURCE_TYPE = 5;
}

class CustomAttribute
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var CustomAttributeTypes|int
     */
    protected $type;

    /**
     * @var CustomAttributeCategory|int
     */
    protected $category;

    /**
     * @var string
     */
    protected $regex;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var int[]
     */
    protected $entityIds = [];

    /**
     * @var int[]
     */
    protected $addedEntityIds = [];

    /**
     * @var int[]
     */
    protected $removedEntityIds = [];

    /**
     * @var string[]
     */
    protected $entityDescriptions = [];

    /**
     * @var bool
     */
    protected $adminOnly = false;

    /**
     * @var string|null
     */
    protected $possibleValues;

    /**
     * @var int
     */
    protected $sortOrder;

    /**
     * @var CustomAttributeTypes|int
     */
    protected $secondaryCategory;

    /**
     * @var int[]
     */
    protected $secondaryEntityIds = [];

    /**
     * @var string[]
     */
    protected $secondaryEntityDescriptions = [];

    /**
     * @var bool
     */
    protected $isPrivate = false;

    /**
     * @return int
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function Label()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function PossibleValues()
    {
        return $this->possibleValues;
    }

    /**
     * @return array|string[]
     */
    public function PossibleValueList()
    {
        if (is_null($this->possibleValues)) {
            return [];
        }
        return explode(',', $this->possibleValues);
    }

    /**
     * @return string
     */
    public function Regex()
    {
        return $this->regex;
    }

    /**
     * @return bool
     */
    public function Required()
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function UniquePerEntity()
    {
        return !empty($this->entityIds);
    }

    /**
     * @return int[]
     */
    public function EntityIds()
    {
        return empty($this->entityIds) ? [] : $this->entityIds;
    }

    /**
     * @return int[]
     */
    public function AddedEntityIds()
    {
        return empty($this->addedEntityIds) ? [] : $this->addedEntityIds;
    }

    /**
     * @return int[]
     */
    public function RemovedEntityIds()
    {
        return empty($this->removedEntityIds) ? [] : $this->removedEntityIds;
    }

    /**
     * @return array|string[]
     */
    public function EntityDescriptions()
    {
        return empty($this->entityDescriptions) ? [] : $this->entityDescriptions;
    }

    /**
     * @return CustomAttributeCategory|int
     */
    public function Category()
    {
        return $this->category;
    }

    /**
     * @return CustomAttributeTypes|int
     */
    public function Type()
    {
        return $this->type;
    }

    public function HasSecondaryEntities()
    {
        return !empty($this->secondaryCategory) && !empty($this->secondaryEntityIds);
    }

    /**
     * @return CustomAttributeCategory|int|null
     */
    public function SecondaryCategory()
    {
        return $this->secondaryCategory;
    }

    /**
     * @return int[]
     */
    public function SecondaryEntityIds()
    {
        return empty($this->secondaryEntityIds) ? [] : $this->secondaryEntityIds;
    }

    /**
     * @return string[]
     */
    public function SecondaryEntityDescriptions()
    {
        return empty($this->secondaryEntityDescriptions) ? [] : $this->secondaryEntityDescriptions;
    }

    /**
     * @return int
     */
    public function SortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * @return bool
     */
    public function AdminOnly()
    {
        return (int)$this->adminOnly;
    }

    /**
     * @return bool
     */
    public function IsPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * @param $entityId
     * @return bool
     */
    public function AppliesToEntity($entityId)
    {
        if ($this->UniquePerEntity()) {
            return in_array($entityId, $this->EntityIds());
        }
        return true;
    }

    /**
     * @param int $id
     * @param string $label
     * @param CustomAttributeTypes|int $type
     * @param CustomAttributeCategory|int $category
     * @param string $regex
     * @param bool $required
     * @param string $possibleValues
     * @param int $sortOrder
     * @param int[] $entityIds
     * @param bool $adminOnly
     * @return CustomAttribute
     */
    public function __construct(
        $id,
        $label,
        $type,
        $category,
        $regex,
        $required,
        $possibleValues,
        $sortOrder,
        $entityIds = [],
        $adminOnly = false
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->type = $type;
        $this->category = $category;
        $this->SetRegex($regex);
        $this->required = $required;
        if ($category != CustomAttributeCategory::RESERVATION) {
            $this->entityIds = is_array($entityIds) ? $entityIds : ($entityIds);
        }
        $this->adminOnly = $adminOnly;
        $this->SetSortOrder($sortOrder);
        $this->SetPossibleValues($possibleValues);
    }

    /**
     * @static
     * @param string $label
     * @param CustomAttributeTypes|int $type
     * @param CustomAttributeCategory|int $category
     * @param string $regex
     * @param bool $required
     * @param string $possibleValues
     * @param int $sortOrder
     * @param int[] $entityIds
     * @param bool $adminOnly
     * @return CustomAttribute
     */
    public static function Create(
        $label,
        $type,
        $category,
        $regex,
        $required,
        $possibleValues,
        $sortOrder,
        $entityIds = [],
        $adminOnly = false
    ) {
        return new CustomAttribute(
            null,
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $entityIds,
            $adminOnly
        );
    }

    /**
     * @static
     * @param $row array
     * @return CustomAttribute
     */
    public static function FromRow($row)
    {
        $entityIds = [];
        if (!empty($row[ColumnNames::ATTRIBUTE_ENTITY_IDS])) {
            $entityIds = explode('!sep!', $row[ColumnNames::ATTRIBUTE_ENTITY_IDS]);
        }

        $descriptions = [];
        if (!empty($row[ColumnNames::ATTRIBUTE_ENTITY_DESCRIPTIONS])) {
            $descriptions = explode('!sep!', $row[ColumnNames::ATTRIBUTE_ENTITY_DESCRIPTIONS]);
        }

        $attribute = new CustomAttribute(
            $row[ColumnNames::ATTRIBUTE_ID],
            $row[ColumnNames::ATTRIBUTE_LABEL],
            $row[ColumnNames::ATTRIBUTE_TYPE],
            $row[ColumnNames::ATTRIBUTE_CATEGORY],
            $row[ColumnNames::ATTRIBUTE_CONSTRAINT],
            $row[ColumnNames::ATTRIBUTE_REQUIRED],
            $row[ColumnNames::ATTRIBUTE_POSSIBLE_VALUES],
            $row[ColumnNames::ATTRIBUTE_SORT_ORDER],
            $entityIds,
            $row[ColumnNames::ATTRIBUTE_ADMIN_ONLY]
        );

        $attribute->WithEntityDescriptions($descriptions);

        if (isset($row[ColumnNames::ATTRIBUTE_SECONDARY_CATEGORY])) {
            $attribute->WithSecondaryEntities(
                $row[ColumnNames::ATTRIBUTE_SECONDARY_CATEGORY],
                $row[ColumnNames::ATTRIBUTE_SECONDARY_ENTITY_IDS],
                $row[ColumnNames::ATTRIBUTE_SECONDARY_ENTITY_DESCRIPTIONS]
            );
        }

        if (isset($row[ColumnNames::ATTRIBUTE_IS_PRIVATE])) {
            $attribute->WithIsPrivate($row[ColumnNames::ATTRIBUTE_IS_PRIVATE]);
        }

        return $attribute;
    }

    /**
     * @param $value mixed
     * @return bool
     */
    public function SatisfiesRequired(mixed $value): bool
    {
        if (!$this->required) {
            return true;
        }

        $trimmed = trim((string) ($value ?? ''));
        return ($trimmed !== '');
    }

    /**
     * @param $value mixed
     * @return bool
     */
    public function SatisfiesConstraint(mixed $value): bool
    {
        if (!empty($this->regex)) {
            return preg_match($this->regex, (string) ($value ?? '')) > 0;
        }

        if (!empty($this->possibleValues)) {
            if (!$this->required) {
                return true;
            }

            $list = $this->PossibleValueList();
            return in_array($value, $list);
        }

        return true;
    }

    /**
     * @param string $label
     * @param string $regex
     * @param bool $required
     * @param string $possibleValues
     * @param int $sortOrder
     * @param int[] $entityIds
     * @param bool $adminOnly
     */
    public function Update($label, $regex, $required, $possibleValues, $sortOrder, $entityIds, $adminOnly)
    {
        $this->label = $label;
        $this->SetRegex($regex);
        $this->required = $required;

        if ($this->category != CustomAttributeCategory::RESERVATION) {
            $entityIds = is_array($entityIds) ? $entityIds : [$entityIds];
            $removed = array_diff($this->entityIds, $entityIds);
            $added = array_diff($entityIds, $this->entityIds);

            if (!empty($removed) || !empty($added)) {
                $this->removedEntityIds = $removed;
                $this->addedEntityIds = $added;
            }

            $this->entityIds = $entityIds;
        }

        $this->adminOnly = $adminOnly;
        $this->SetPossibleValues($possibleValues);
        $this->SetSortOrder($sortOrder);
    }

    /**
     * @param string $possibleValues
     */
    private function SetPossibleValues($possibleValues)
    {
        if (!empty($possibleValues)) {
            $this->possibleValues = preg_replace('/\s*,\s*/', ',', trim($possibleValues));
        }
    }

    /**
     * @param int $sortOrder
     */
    private function SetSortOrder($sortOrder)
    {
        $this->sortOrder = intval($sortOrder);
    }

    /**
     * @param string[] $entityDescriptions
     */
    public function WithEntityDescriptions($entityDescriptions)
    {
        $this->entityDescriptions = $entityDescriptions;
    }

    /**
     * @param int|CustomAttributeCategory $category
     * @param string|int[] $entityIds
     * @param string|null $entityDescriptions
     */
    public function WithSecondaryEntities($category, $entityIds, $entityDescriptions = null)
    {
        if ($this->category != CustomAttributeCategory::RESERVATION) {
            return;
        }

        if (!empty($category) && !empty($entityIds)) {
            $this->secondaryCategory = $category;

            if (is_array($entityIds)) {
                $this->secondaryEntityIds = $entityIds;
            } else {
                $this->secondaryEntityIds = explode(',', $entityIds);
            }

            $descriptions = [];
            if (!empty($entityDescriptions)) {
                $descriptions = explode('!sep!', $entityDescriptions);
            }
            $this->secondaryEntityDescriptions = $descriptions;
        } else {
            $this->secondaryCategory = null;
            $this->secondaryEntityIds = null;
            $this->secondaryEntityDescriptions = null;
        }
    }

    /**
     * @param int|bool $isPrivate
     */
    public function WithIsPrivate($isPrivate)
    {
        $this->isPrivate = BooleanConverter::ConvertValue($isPrivate);
    }

    /**
     * @param string $regex
     */
    private function SetRegex($regex)
    {
        $this->regex = $regex;
        if (empty($this->regex)) {
            return;
        }

        if (!BookedStringHelper::StartsWith($this->regex, '/')) {
            $this->regex = '/' . $this->regex;
        }
        if (!BookedStringHelper::EndsWith($this->regex, '/')) {
            $this->regex = $this->regex . '/';
        }
    }
}
