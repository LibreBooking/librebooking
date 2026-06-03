<?php

declare(strict_types=1);

class FakeCustomAttribute extends CustomAttribute
{
    /**
     * @var bool
     */
    public $_IsRequiredSatisfied = true;

    /**
     * @var bool
     */
    public $_IsConstraintSatisfied = true;

    /**
     * @var mixed
     */
    public $_RequiredValueChecked;

    /**
     * @var mixed
     */
    public $_ConstraintValueChecked;

    /**
     * @param int $id
     * @param bool $isRequiredOk
     * @param bool $isRegexOk
     * @param int[] $entityIds
     * @param bool $adminOnly
     */
    public function __construct($id = 1, $isRequiredOk = true, $isRegexOk = true, $entityIds = [], $adminOnly = false)
    {
        $this->id = $id;
        $this->label = "fakeCustomAttribute$id";
        $this->entityIds = is_array($entityIds) ? $entityIds : [$entityIds];

        $this->_IsRequiredSatisfied = $isRequiredOk;
        $this->_IsConstraintSatisfied = $isRegexOk;
        $this->adminOnly = $adminOnly;
        $this->category = CustomAttributeCategory::RESERVATION;
    }

    public function SatisfiesRequired(mixed $value): bool
    {
        $this->_RequiredValueChecked = $value;
        return $this->_IsRequiredSatisfied;
    }

    public function SatisfiesConstraint(mixed $value): bool
    {
        $this->_ConstraintValueChecked = $value;
        return $this->_IsConstraintSatisfied;
    }

    public function IsAdminOnly($value)
    {
        $this->adminOnly = $value;
    }
}

class TestCustomAttribute extends CustomAttribute
{
    public function __construct($id, $label, $entityIds = [])
    {
        $this->id = $id;
        $this->label = $label;
        $this->entityIds = is_array($entityIds) ? $entityIds : [$entityIds];
    }
}
