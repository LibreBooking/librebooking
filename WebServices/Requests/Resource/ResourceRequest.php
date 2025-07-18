<?php

require_once(ROOT_DIR . 'lib/WebService/JsonRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/CustomAttributes/AttributeValueRequest.php');

class ResourceRequest extends JsonRequest
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $location;
    /**
     * @var string
     */
    public $contact;
    /**
     * @var string
     */
    public $notes;
    /**
     * @var string
     */
    public $minLength;
    /**
     * @var string
     */
    public $maxLength;
    /**
     * @var bool
     */
    public $requiresApproval;
    /**
     * @var bool
     */
    public $allowMultiday;
    /**
     * @var int
     */
    public $maxParticipants;
    /**
     * @var string
     */
    public $minNotice;
    /**
     * @var string
     */
    public $maxNotice;
    /**
     * @var string
     */
    public $description;
    /**
     * @var int
     */
    public $scheduleId;
    /**
     * @var bool
     */
    public $autoAssignPermissions;
    /**
     * @var array|AttributeValueRequest[]
     */
    public $customAttributes = [];
    /**
     * @var int
     */
    public $sortOrder;
    /**
     * @var int
     */
    public $statusId;
    /**
     * @var int|null
     */
    public $statusReasonId;

    /**
     * @var int|null
     */
    public $autoReleaseMinutes;
    /**
     * @var bool|null
     */
    public $requiresCheckIn;
    /**
     * @var string|null
     */
    public $color;
    /**
     * @var int|null
     */
    public $creditsPerSlot;
    /**
     * @var int|null
     */
    public $peakCreditsPerSlot;
    /**
     * @var int|null
     */
    public $maxConcurrentReservations;

    public string $bufferTime;
    public array|null $groupIds = null;
    public string|null $icsUrl = null;
    public string $minNoticeAdd;
    public string $minNoticeDelete;
    public string $minNoticeUpdate;
    public int|null $typeId = null;


    /**
     * @return ExampleResourceRequest
     */
    public static function Example()
    {
        return new ExampleResourceRequest();
    }

    /**
     * @return array|AttributeValueRequest[]
     */
    public function GetCustomAttributes()
    {
        if (!empty($this->customAttributes)) {
            return $this->customAttributes;
        }
        return [];
    }
}

class ExampleResourceRequest extends ResourceRequest
{
    public function __construct()
    {
        $this->name = 'resource name';
        $this->location = 'location';
        $this->contact = 'contact information';
        $this->notes = 'notes';
        $this->minLength = '1d0h0m';
        $this->maxLength = '3600';
        $this->requiresApproval = true;
        $this->allowMultiday = true;
        $this->maxParticipants = 100;
        $this->minNotice = '86400';
        $this->maxNotice = '0d12h30m';
        $this->description = 'description';
        $this->scheduleId = 10;
        $this->autoAssignPermissions = true;
        $this->customAttributes = [AttributeValueRequest::Example()];
        $this->sortOrder = 1;
        $this->statusId = ResourceStatus::AVAILABLE;
        $this->statusReasonId = 2;
        $this->autoReleaseMinutes = 15;
        $this->requiresCheckIn = true;
        $this->color = '#ffffff';
        $this->creditsPerSlot = 3;
        $this->peakCreditsPerSlot = 6;
        $this->maxConcurrentReservations = 1;
        $this->bufferTime = '1d2h3m';
        $this->groupIds = [];
        $this->icsUrl = 'http://ics.example.com/a_calendar.ics';
        $this->minNoticeAdd = '0d0h1m';
        $this->minNoticeDelete = '0d0h2m';
        $this->minNoticeUpdate = '0d0h3m';
        $this->typeId = 1;
    }
}
