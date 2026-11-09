<?php

class ResourceImportCsvRow
{
    public $name;
    public $status;
    public $schedule;
    public $resourceType;
    public $sortOrder = 0;
    public $location;
    public $contact;
    public $description;
    public $notes;
    public $resourceAdministrator;
    public $color;
    public $resourceGroups = [];
    public $autoAssign = true;
    public $approvalRequired = false;
    public $capacity;
    public $minLength;
    public $maxLength;
    public $buffer;
    public $crossDay = false;
    public $addNotice;
    public $updateNotice;
    public $deleteNotice;
    public $checkIn = false;
    public $autoreleaseMinutes;
    public $credits;
    public $creditsPeak;
    public $maximumConcurrent;
    public $attributes = [];

    private $values = [];
    private $indexes = [];

    /**
     * @param $values array
     * @param $indexes array
     * @param $attributes CustomAttribute[]
     */
    public function __construct($values, $indexes, $attributes)
    {
        $this->values = $values;
        $this->indexes = $indexes;

        $this->name = $this->valueOrDefault('name');
        $this->status = strtolower($this->valueOrDefault('status'));
        $this->schedule = strtolower($this->valueOrDefault('schedule'));
        $this->resourceType = strtolower($this->valueOrDefault('resourceType'));
        $this->sortOrder = $this->valueOrDefault('sortOrder');
        $this->location = $this->valueOrDefault('location');
        $this->contact = $this->valueOrDefault('contact');
        $this->description = $this->valueOrDefault('description');
        $this->notes = $this->valueOrDefault('notes');
        $this->resourceAdministrator = strtolower($this->valueOrDefault('resourceAdministrator'));
        $this->color = $this->valueOrDefault('color');
        $this->resourceGroups = (!array_key_exists('resourceGroups', $this->indexes) || $indexes['resourceGroups'] === false) ? []
                : array_map('trim', explode(',', htmlspecialchars($values[$indexes['resourceGroups']])));
        $this->autoAssign = strtolower($this->valueOrDefault('autoAssign'));
        $this->approvalRequired = strtolower($this->valueOrDefault('approvalRequired'));
        $this->capacity = $this->valueOrDefault('capacity');
        $this->minLength = $this->valueOrDefault('minLength');
        $this->maxLength = $this->valueOrDefault('maxLength');
        $this->buffer = $this->valueOrDefault('buffer');
        $this->crossDay = $this->valueOrDefault('crossDay');
        $this->addNotice = $this->valueOrDefault('addNotice');
        $this->updateNotice = $this->valueOrDefault('updateNotice');
        $this->deleteNotice = $this->valueOrDefault('deleteNotice');
        $this->checkIn = $this->valueOrDefault('checkIn');
        $this->autoreleaseMinutes = $this->valueOrDefault('autoreleaseMinutes');
        $this->credits = $this->valueOrDefault('credits');
        $this->creditsPeak = $this->valueOrDefault('creditsPeak');
        $this->maximumConcurrent = $this->valueOrDefault('concurrentReservations');

        foreach ($attributes as $label => $attribute) {
            $this->attributes[$label] = $this->valueOrDefault($label);
        }
    }

    public function IsValid()
    {
        $isValid = !empty($this->name);
        if (!$isValid) {
            Log::Debug('Resource import row is not valid. Missing name');
        }
        return $isValid;
    }

    /**
     * @param string[] $values
     * @param CustomAttribute[] $attributes
     * @return bool|string[]
     */
    public static function GetHeaders($values, $attributes)
    {
        $values = array_map('strtolower', $values);
        if (!in_array('name', $values) && !in_array('name', $values)) {
            return false;
        }

        $indexes['name'] = self::indexOrFalse('name', $values);
        $indexes['status'] = self::indexOrFalse('status', $values);
        $indexes['schedule'] = self::indexOrFalse('schedule', $values);
        $indexes['resourceType'] = self::indexOrFalse('resource type', $values);
        $indexes['sortOrder'] = self::indexOrFalse('sort order', $values);
        $indexes['location'] = self::indexOrFalse('location', $values);
        $indexes['contact'] = self::indexOrFalse('contact', $values);
        $indexes['description'] = self::indexOrFalse('description', $values);
        $indexes['notes'] = self::indexOrFalse('notes', $values);
        $indexes['resourceAdministrator'] = self::indexOrFalse('resource administrator', $values);
        $indexes['color'] = self::indexOrFalse('resource color', $values);
        $indexes['resourceGroups'] = self::indexOrFalse('resource groups', $values);
        $indexes['autoAssign'] = self::indexOrFalse('permission is automatically granted', $values);
        $indexes['approvalRequired'] = self::indexOrFalse('reservations must be approved', $values);
        $indexes['capacity'] = self::indexOrFalse('capacity', $values);
        $indexes['minLength'] = self::indexOrFalse('reservation minimum length', $values);
        $indexes['maxLength'] = self::indexOrFalse('reservation maximum length', $values);
        $indexes['buffer'] = self::indexOrFalse('buffer time', $values);
        $indexes['crossDay'] = self::indexOrFalse('reservations can be made across days', $values);
        $indexes['addNotice'] = self::indexOrFalse('reservation add minimum notice', $values);
        $indexes['updateNotice'] = self::indexOrFalse('reservation update minimum notice', $values);
        $indexes['deleteNotice'] = self::indexOrFalse('reservation delete minimum notice', $values);
        $indexes['checkIn'] = self::indexOrFalse('requires check in/out', $values);
        $indexes['autoreleaseMinutes'] = self::indexOrFalse('autorelease minutes', $values);
        $indexes['credits'] = self::indexOrFalse('credits (off peak)', $values);
        $indexes['creditsPeak'] = self::indexOrFalse('credits (peak)', $values);
        $indexes['concurrentReservations'] = self::indexOrFalse('maximum concurrent reservations', $values);

        foreach ($attributes as $label => $attribute) {
            $escapedLabel = str_replace('\'', '\\\\', $label);
            $indexes[$label] = self::indexOrFalse($escapedLabel, $values);
        }

        return $indexes;
    }

    private static function indexOrFalse($columnName, $values)
    {
        $values = array_map('strtolower', $values);
        $index = array_search($columnName, $values);
        if ($index === false) {
            return false;
        }

        return intval($index);
    }

    /**
     * @param $column string
     * @return string
     */
    private function valueOrDefault($column)
    {
        return ($this->indexes[$column] === false ||
                !array_key_exists($this->indexes[$column], $this->values)) ? ''
                : htmlspecialchars(trim($this->values[$this->indexes[$column]]));
    }
}

class ResourceImportCsv
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var int[]
     */
    private $skippedRowNumbers = [];

    /**
     * @var CustomAttribute[]
     */
    private $attributes;

    /**
     * @param UploadedFile $file
     * @param CustomAttribute[] $attributes
     */
    public function __construct(UploadedFile $file, $attributes)
    {
        $this->file = $file;
        $this->attributes = $attributes;
    }

    /**
     * @return ResourceImportCsvRow[]
     */
    public function GetRows()
    {
        $rows = [];

        $contents = $this->file->Contents();

        $contents = $this->RemoveUTF8BOM($contents);
        $csvRows = preg_split('/\n|\r\n?/', $contents);

        if (count($csvRows) == 0) {
            Log::Debug('No rows in resource import file');
            return $rows;
        }

        Log::Debug('%s rows in resource import file', count($csvRows));

        $headers = ResourceImportCsvRow::GetHeaders(str_getcsv($csvRows[0]), $this->attributes);

        if (!$headers) {
            Log::Debug('No headers in resource import file.');
            Log::Debug('Header row: %s', var_export(str_getcsv($csvRows[0]), true));
            return $rows;
        }

        for ($i = 1; $i < count($csvRows); $i++) {
            $values = str_getcsv($csvRows[$i]);

            $row = new ResourceImportCsvRow($values, $headers, $this->attributes);

            if ($row->IsValid()) {
                $rows[] = $row;
            } else {
                Log::Error('Skipped import of resource row %s. Values %s', $i, print_r($values, true));
                $this->skippedRowNumbers[] = $i;
            }
        }

        return $rows;
    }

    /**
     * @return int[]
     */
    public function GetSkippedRowNumbers()
    {
        return $this->skippedRowNumbers;
    }

    private function RemoveUTF8BOM($text)
    {
        return str_replace("\xEF\xBB\xBF", '', $text);
    }
}
