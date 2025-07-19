<?php

require_once(ROOT_DIR . 'lib/Application/Reporting/ChartColumnDefinition.php');

class ReportCell
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var null|string
     */
    private $chartValue;

    /**
     * @var ChartType|string|null
     */
    private $chartColumnType;
    private $chartGroup;

    /**
     * @param string $value
     * @param string|null $chartValue
     * @param ChartColumnType|string|null $chartColumnType
     * @param ChartGroup $chartGroup
     */
    public function __construct($value, $chartValue = null, $chartColumnType = null, $chartGroup = null)
    {
        $this->value = $value;
        $this->chartValue = $chartValue;
        $this->chartColumnType = $chartColumnType;
        $this->chartGroup = $chartGroup;
    }

    public function Value()
    {
        return $this->value;
    }

    public function ChartValue()
    {
        return $this->chartValue;
    }

    public function GetChartColumnType()
    {
        return $this->chartColumnType;
    }

    public function GetChartGroup()
    {
        return $this->chartGroup;
    }

    public function __toString()
    {
        return $this->Value();
    }
}

class ReportAttributeCell extends ReportCell
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function Value()
    {
        return $this->value;
    }
}

abstract class ReportColumn
{
    /**
     * @var string
     */
    protected $titleKey;

    /**
     * @var ChartColumnDefinition
     */
    protected $chartColumnDefinition;

    /**
     * @var string
     */
    protected $title;

    /**
     * @param $titleKey string
     * @param $chartColumnDefinition ChartColumnDefinition|null
     */
    public function __construct($titleKey, ?ChartColumnDefinition $chartColumnDefinition = null)
    {
        $this->titleKey = $titleKey;
        $this->chartColumnDefinition = $chartColumnDefinition;
    }

    /**
     * @return string
     */
    public function TitleKey()
    {
        return $this->titleKey;
    }

    /**
     * @return string
     */
    public function Title()
    {
        return $this->title;
    }

    /**
     * @return bool
     */
    public function HasTitle()
    {
        return $this->title != null;
    }

    /**
     * @param $data mixed
     * @return string
     */
    public function GetData($data)
    {
        return $data;
    }

    /**
     * @return ChartColumnType|string|null
     */
    public function GetChartColumnType()
    {
        return $this->chartColumnDefinition->ChartColumnType();
    }

    /**
     * @param $row array
     * @param $columnKey string
     * @return string
     */
    public function GetChartData($row, $columnKey)
    {
        return $this->chartColumnDefinition->GetChartData($row, $columnKey);
    }

    /**
     * @return ChartGroup|null|string
     */
    public function GetChartGroup()
    {
        return $this->chartColumnDefinition->GetChartGroup();
    }
}

class ReportStringColumn extends ReportColumn
{
    public function __construct($titleKey, ?ChartColumnDefinition $chartColumnDefinition = null)
    {
        parent::__construct(null, $chartColumnDefinition);
        $this->titleKey = $titleKey;
    }
}

class ReportAttributeColumn extends ReportColumn
{
    public function __construct($title, ?ChartColumnDefinition $chartColumnDefinition = null)
    {
        parent::__construct(null, $chartColumnDefinition);
        $this->title = $title;
    }
}

class ReportStatusColumn extends ReportStringColumn
{
    public function GetData($data)
    {
        $r = Resources::GetInstance();

        if ($data == ReservationStatus::Created) {
            return $r->GetString('Created');
        }

        if ($data == ReservationStatus::Pending) {
            return $r->GetString('Pending');
        }

        return $r->GetString('Deleted');
    }
}

class ReportDateColumn extends ReportColumn
{
    private $timezone;
    private $format;

    public function __construct($titleKey, $timezone, $format, ?ChartColumnDefinition $chartColumnDefinition = null)
    {
        parent::__construct($titleKey, $chartColumnDefinition);
        $this->timezone = $timezone;
        $this->format = $format;
    }

    public function GetData($data)
    {
        if (is_a($data, 'Date')) {
            $date = $data;
        } else {
            $date = Date::FromDatabase($data);
        }
        return $date->ToTimezone($this->timezone)->Format($this->format);
    }

    public function GetChartData($row, $key)
    {
        if (is_a($row[$key], 'Date')) {
            $date = $row[$key];
        } else {
            $date = Date::FromDatabase($row[$key]);
        }
        //		$format = Resources::GetInstance()->GetDateFormat(ResourceKeys::DATE_GENERAL);
        return $date->ToTimezone($this->timezone)->GetDate()->ToIso();
    }
}

class ReportTimeColumn extends ReportColumn
{
    private $includeTotalHours;

    public function __construct($titleKey, ?ChartColumnDefinition $chartColumnDefinition = null, $includeTotalHours = true)
    {
        parent::__construct($titleKey, $chartColumnDefinition);
        $this->includeTotalHours = $includeTotalHours;
    }

    public function GetData($data)
    {
        $interval = new TimeInterval($data);
        return $interval->ToString($this->includeTotalHours);
    }
}

class ReportUtilizationColumn extends ReportColumn
{
    public function __construct($titleKey, ?ChartColumnDefinition $chartColumnDefinition = null)
    {
        parent::__construct($titleKey, $chartColumnDefinition);
    }

    public function GetData($data)
    {
        return "$data%";
    }

    public function GetChartData($row, $key)
    {
        return $row[$key];
    }
}
