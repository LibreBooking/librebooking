<?php

class ChartType
{
    public const Total = 'total';
    public const TotalTime = 'totalTime';
    public const Date = 'date';
}

class ChartColumnType
{
    public const Label = 'label';
    public const Total = 'total';
    public const Date = 'date';
}

class ChartGroup
{
    public const Accessory = 'a';
    public const Resource = 'r';
    public const User = 'u';
}

class ChartColumnDefinition
{
    /**
     * @var ChartColumnType|string|null null for a column that is not charted
     */
    private $chartColumnType;
    /**
     * @var null|string
     */
    private $dataColumnName;
    /**
     * @var ChartGroup|null|string
     */
    private $chartGroup;

    /**
     * @param string|ChartColumnType|null $chartColumnType
     * @param string|null $dataColumnName
     * @param string|null|ChartGroup $chartGroup
     */
    private function __construct($chartColumnType, $dataColumnName = null, $chartGroup = null)
    {
        $this->chartColumnType = $chartColumnType;
        $this->dataColumnName = $dataColumnName;
        $this->chartGroup = $chartGroup;
    }

    /**
     * @static
     * @param string $dataColumnName
     * @param string|ChartGroup $chartGroup
     * @return ChartColumnDefinition
     */
    public static function Label($dataColumnName, $chartGroup = null)
    {
        return new ChartColumnDefinition(ChartColumnType::Label, $dataColumnName, $chartGroup);
    }

    /**
     * @static
     * @return ChartColumnDefinition
     */
    public static function null()
    {
        return new ChartColumnDefinition(null);
    }

    /**
     * @static
     * @return ChartColumnDefinition
     */
    public static function Date()
    {
        return new ChartColumnDefinition(ChartColumnType::Date);
    }

    /**
     * @static
     * @return ChartColumnDefinition
     */
    public static function Total()
    {
        return new ChartColumnDefinition(ChartColumnType::Total);
    }

    /**
     * @return ChartColumnType|string|null
     */
    public function ChartColumnType()
    {
        return $this->chartColumnType;
    }

    /**
     * @param $row array
     * @param $columnKey string
     */
    public function GetChartData($row, $columnKey)
    {
        if (empty($this->dataColumnName)) {
            return $row[$columnKey];
        }
        return $row[$this->dataColumnName];
    }

    /**
     * @return ChartGroup|null|string
     */
    public function GetChartGroup()
    {
        return $this->chartGroup;
    }
}
