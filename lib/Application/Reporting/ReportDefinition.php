<?php

require_once(ROOT_DIR . 'lib/Application/Reporting/ChartColumnDefinition.php');
require_once(ROOT_DIR . 'lib/Application/Reporting/ReportColumn.php');
require_once(ROOT_DIR . 'lib/Common/Helpers/String.php');

interface IReportDefinition
{
    /**
     * @return array|ReportColumn
     */
    public function GetColumnHeaders();

    /**
     * @param array $row
     * @return ReportCell[]|array
     */
    public function GetRow($row);

    /**
     * @return string
     */
    public function GetTotal();

    /**
     * @return string|ChartType
     */
    public function GetChartType();
}

class ReportDefinition implements IReportDefinition
{
    /**
     * @var array|ReportColumn[]
     */
    private $columns = [];

    /**
     * @var int
     */
    private $sum = 0;

    /**
     * @var ReportColumn
     */
    private $sumColumn;

    public function __construct(IReport $report, $timezone)
    {
        $dateTimeFormat = Resources::GetInstance()->ShortDateTimeFormat();
        $dateFormat = Resources::GetInstance()->GeneralDateFormat();
        $orderedColumns = [
            ColumnNames::ACCESSORY_NAME => new ReportStringColumn('Accessory', ChartColumnDefinition::Label(ColumnNames::ACCESSORY_ID, ChartGroup::Accessory)),
            ColumnNames::QUANTITY => new ReportStringColumn('QuantityReserved', ChartColumnDefinition::Total()),
            ColumnNames::RESOURCE_NAME_ALIAS => new ReportStringColumn('Resource', ChartColumnDefinition::Label(ColumnNames::RESOURCE_ID, ChartGroup::Resource)),
            ColumnNames::RESERVATION_START => new ReportDateColumn('BeginDate', $timezone, $dateTimeFormat, ChartColumnDefinition::Date()),
            ColumnNames::RESERVATION_END => new ReportDateColumn('EndDate', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::DURATION_ALIAS => new ReportTimeColumn('Duration', ChartColumnDefinition::Null(), false),
            ColumnNames::DURATION_HOURS => new ReportStringColumn('Hours', ChartColumnDefinition::Null()),
            ColumnNames::RESERVATION_TITLE => new ReportStringColumn('Title', ChartColumnDefinition::Null()),
            ColumnNames::RESERVATION_DESCRIPTION => new ReportStringColumn('Description', ChartColumnDefinition::Null()),
            ColumnNames::REFERENCE_NUMBER => new ReportStringColumn('ReferenceNumber', ChartColumnDefinition::Null()),
            ColumnNames::OWNER_FULL_NAME_ALIAS => new ReportStringColumn('User', ChartColumnDefinition::Label(ColumnNames::OWNER_USER_ID)),
            ColumnNames::OWNER_FIRST_NAME => new ReportStringColumn('FirstName', ChartColumnDefinition::Label(ColumnNames::OWNER_USER_ID)),
            ColumnNames::OWNER_LAST_NAME => new ReportStringColumn('LastName', ChartColumnDefinition::Label(ColumnNames::OWNER_USER_ID)),
            ColumnNames::EMAIL => new ReportStringColumn('Email', ChartColumnDefinition::Label(ColumnNames::OWNER_USER_ID)),
            ColumnNames::ORGANIZATION => new ReportStringColumn('Organization', ChartColumnDefinition::Null()),
            ColumnNames::POSITION => new ReportStringColumn('Position', ChartColumnDefinition::Null()),
            ColumnNames::PHONE_NUMBER => new ReportStringColumn('Phone', ChartColumnDefinition::Null()),
            ColumnNames::TIMEZONE_NAME => new ReportStringColumn('Timezone', ChartColumnDefinition::Null()),
            ColumnNames::LANGUAGE_CODE => new ReportStringColumn('Language', ChartColumnDefinition::Null()),
            ColumnNames::USER_GROUP_LIST => new ReportStringColumn('Groups', ChartColumnDefinition::Null()),
            ColumnNames::GROUP_NAME_ALIAS => new ReportStringColumn('Group', ChartColumnDefinition::Label(ColumnNames::GROUP_ID)),
            ColumnNames::PARTICIPANT_LIST => new ReportStringColumn('Participants', ChartColumnDefinition::Null()),
            ColumnNames::SCHEDULE_NAME_ALIAS => new ReportStringColumn('Schedule', ChartColumnDefinition::Label(ColumnNames::SCHEDULE_ID)),
            ColumnNames::RESERVATION_CREATED => new ReportDateColumn('Created', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::RESERVATION_MODIFIED => new ReportDateColumn('LastModified', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::RESERVATION_STATUS => new ReportStatusColumn('Status', ChartColumnDefinition::Null()),
            ColumnNames::CHECKIN_DATE => new ReportDateColumn('CheckInTime', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::CHECKOUT_DATE => new ReportDateColumn('CheckOutTime', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::PREVIOUS_END_DATE => new ReportDateColumn('OriginalEndDate', $timezone, $dateTimeFormat, ChartColumnDefinition::Null()),
            ColumnNames::TOTAL => new ReportStringColumn('Total', ChartColumnDefinition::Total()),
            ColumnNames::TOTAL_TIME => new ReportTimeColumn('Total', ChartColumnDefinition::Total()),
            ColumnNames::DATE => new ReportDateColumn('Date', $timezone, $dateFormat, ChartColumnDefinition::Date()),
            ColumnNames::UTILIZATION => new ReportUtilizationColumn('Utilization', ChartColumnDefinition::Total()),
        ];

        if (Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter())) {
            $orderedColumns[ColumnNames::CREDIT_COUNT] = new ReportStringColumn('Credits', ChartColumnDefinition::Null());
        }

        $reportColumns = $report->GetColumns();

        foreach ($orderedColumns as $key => $column) {
            if ($reportColumns->Exists($key)) {
                $this->columns[$key] = $column;
            }
        }

        foreach ($reportColumns->GetCustomAttributes() as $column) {
            $this->columns[$column->Id] = new ReportAttributeColumn($column->Label, ChartColumnDefinition::Null());
        }
    }

    public function GetColumnHeaders()
    {
        return $this->columns;
    }

    public function GetRow($row)
    {
        $reservationAttributes = null;
        $userAttributes = null;
        $resourceAttributes = null;
        $resourceTypeAttributes = null;

        if (array_key_exists(ColumnNames::ATTRIBUTE_LIST, $row)) {
            $reservationAttributes = CustomAttributes::Parse($row[ColumnNames::ATTRIBUTE_LIST]);
        }
        if (array_key_exists(ColumnNames::USER_ATTRIBUTE_LIST, $row)) {
            $userAttributes = CustomAttributes::Parse($row[ColumnNames::USER_ATTRIBUTE_LIST]);
        }
        if (array_key_exists(ColumnNames::RESOURCE_ATTRIBUTE_LIST, $row)) {
            $resourceAttributes = CustomAttributes::Parse($row[ColumnNames::RESOURCE_ATTRIBUTE_LIST]);
        }
        if (array_key_exists(ColumnNames::RESOURCE_TYPE_ATTRIBUTE_LIST, $row)) {
            $resourceTypeAttributes = CustomAttributes::Parse($row[ColumnNames::RESOURCE_TYPE_ATTRIBUTE_LIST]);
        }
        if (array_key_exists(ColumnNames::PARTICIPANT_LIST, $row)) {
            $row[ColumnNames::PARTICIPANT_LIST] = str_replace('!sep!', ', ', $row[ColumnNames::PARTICIPANT_LIST] ?? '');
        }

        $formattedRow = [];
        foreach ($this->columns as $key => $column) {
            if ($key == ColumnNames::TOTAL || $key == ColumnNames::TOTAL_TIME) {
                $this->sum += $row[$key];
                $this->sumColumn = $column;
            }

            if (BookedStringHelper::Contains($key, 'attribute')) {
                $this->AddCustomAttributes(CustomAttributeCategory::RESERVATION, $reservationAttributes, $formattedRow, $key, $column);
                $this->AddCustomAttributes(CustomAttributeCategory::USER, $userAttributes, $formattedRow, $key, $column);
                $this->AddCustomAttributes(CustomAttributeCategory::RESOURCE, $resourceAttributes, $formattedRow, $key, $column);
                $this->AddCustomAttributes(CustomAttributeCategory::RESOURCE_TYPE, $resourceTypeAttributes, $formattedRow, $key, $column);
            } else {
                $formattedRow[] = new ReportCell(
                    $column->GetData($row[$key]),
                    $column->GetChartData($row, $key),
                    $column->GetChartColumnType(),
                    $column->GetChartGroup()
                );
            }
        }

        return $formattedRow;
    }

    /**
     * @param CustomAttributeCategory|string $category
     * @param CustomAttributes|null $attributes
     * @param array $formattedRow
     * @param string $key
     * @param ReportColumn $column
     */
    private function AddCustomAttributes($category, $attributes, &$formattedRow, $key, ReportColumn $column)
    {
        $prefix = $category . 'attribute';
        if ($attributes != null && BookedStringHelper::StartsWith($key, $prefix)) {
            $id = intval(str_replace($prefix, '', $key));
            $attribute = $attributes->Get($id);
            $formattedRow[] = new ReportAttributeCell($column->GetData($attribute));
        }
    }

    public function GetTotal()
    {
        if ($this->sum > 0) {
            return $this->sumColumn->GetData($this->sum);
        }
        return '';
    }

    /**
     * @return string|ChartType
     */
    public function GetChartType()
    {
        if (array_key_exists(ColumnNames::TOTAL, $this->columns)) {
            return ChartType::Total;
        } elseif (array_key_exists(ColumnNames::TOTAL_TIME, $this->columns)) {
            return ChartType::TotalTime;
        }

        return ChartType::Date;
    }
}
