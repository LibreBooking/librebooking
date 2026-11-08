<?php

class ReportColumns implements IReportColumns
{
    private $knownColumns = [];
    private $attributeColumns = [];

    /**
     * @param $columnName string
     */
    public function Add($columnName)
    {
        $this->knownColumns[] = $columnName;
    }

    /**
     * @param $attributeTypeId int|CustomAttributeCategory
     * @param $attributeId int
     * @param $label string
     */
    public function AddAttribute($attributeTypeId, $attributeId, $label)
    {
        $this->attributeColumns[] = new AttributeReportColumn("{$attributeTypeId}attribute{$attributeId}", $label);
    }

    public function Exists($columnName)
    {
        return in_array($columnName, $this->knownColumns);
    }

    /**
     * @return string[]
     */
    public function GetAll()
    {
        return $this->knownColumns;
    }

    /**
     * @return AttributeReportColumn[]
     */
    public function GetCustomAttributes(): array
    {
        return $this->attributeColumns;
    }
}
