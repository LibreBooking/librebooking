<?php

class AttributeReportColumn
{
    /**
     * @var string
     */
    public $Id;

    /**
     * @var string
     */
    public $Label;

    public function __construct($id, $label)
    {
        $this->Id = $id;
        $this->Label = $label;
    }
}

interface IReportColumns
{
    /**
     * @param $columnName string
     * @return bool
     */
    public function Exists($columnName);

    /**
     * @return string[]
     */
    public function GetAll();

    /**
     * @return AttributeReportColumn[]
     */
    public function GetCustomAttributes(): array;
}
