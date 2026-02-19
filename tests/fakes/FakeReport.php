<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reporting/namespace.php');

class FakeReport implements IReport
{
    public $_ReportColumns;
    public $_ReportData;
    public $_ResultCount = 0;

    public function __construct()
    {
        $this->_ReportColumns = new FakeReportColumns();
        $this->_ReportData = new FakeReportData();
    }
    /**
     * @return IReportColumns
     */
    public function GetColumns()
    {
        return $this->_ReportColumns;
    }

    /**
     * @return IReportData
     */
    public function GetData()
    {
        return $this->_ReportData;
    }

    /**
     * @return int
     */
    public function ResultCount()
    {
        return $this->_ResultCount;
    }
}

class FakeReportColumns implements IReportColumns
{
    /**
     * @param $columnName string
     * @return bool
     */
    public function Exists($columnName)
    {
        return true;
    }

    /**
     * @return array|string
     */
    public function GetAll()
    {
        return [];
    }

    /**
     * @return AttributeReportColumn[]
     */
    public function GetCustomAttributes(): array
    {
        return [];
    }
}

class FakeReportData implements IReportData
{
    /**
     * @return array
     */
    public function Rows()
    {
        return [];
    }
}
