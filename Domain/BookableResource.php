<?php

interface IResource extends IPermissibleResource
{
    /**
     * @return int
     */
    public function GetId();

    /**
     * @return string
     */
    public function GetName();

    /**
     * @return int
     */
    public function GetAdminGroupId();

    /**
     * @return int
     */
    public function GetScheduleId();

    /**
     * @return int
     */
    public function GetScheduleAdminGroupId();

    /**
     * @return int
     */
    public function GetStatusId();
}

interface IPermissibleResource
{
    /**
     * @return int
     */
    public function GetResourceId();
}

interface IBookableResource extends IResource
{
    /**
     * @return TimeInterval
     */
    public function GetMinimumLength();

    /**
     * @return bool
     */
    public function GetRequiresApproval();

    /**
     * @return bool
     */
    public function IsCheckInEnabled();

    /**
     * @return bool
     */
    public function IsAutoReleased();

    /**
     * @return null|int
     */
    public function GetAutoReleaseMinutes();

    /**
     * @return int
     */
    public function GetResourceTypeId();

    /**
     * @return null|string
     */
    public function GetColor();

    /**
     * @return null|string
     */
    public function GetTextColor();
}

class BookableResourceBuilder
{
    private $id;
    private $name;
    private $location;
    private $contact;
    private $notes;
    private $minLength;
    private $maxLength;
    private $autoAssign;
    private $requiresApproval = false;
    private $allowMultiday = false;
    private $maxParticipants;
    private $minNoticeAdd;
    private $minNoticeUpdate;
    private $minNoticeDelete;
    private $maxNotice;
    private $description;
    private $scheduleId;
    private $adminGroupId;
    private $imageName;
    private $status;
    private $sortOrder;
    private $resourceTypeId;
    private $publicId;
    private $allowSubscription = false;
    private $bufferTime;
    private $color;
    private $attributeValues = [];
    private $groupIds = [];
    private $allowCheckin = false;
    private $autoReleaseMinutes;
    private $allowDisplay = false;
    private $peakCredits;
    private $offPeakCredits;

    /**
     * @return BookableResourceBuilder
     */
    public static function Create()
    {
        return new BookableResourceBuilder();
    }

    /**
     * @return BookableResource
     */
    public function Build()
    {
        $resource = new BookableResource(
            $this->id,
            $this->name,
            $this->location,
            $this->contact,
            $this->notes,
            $this->minLength,
            $this->maxLength,
            $this->autoAssign,
            $this->requiresApproval,
            $this->allowMultiday,
            $this->maxParticipants,
            $this->minNoticeAdd,
            $this->maxNotice,
            $this->description,
            $this->scheduleId,
            $this->adminGroupId,
            $this->minNoticeUpdate,
            $this->minNoticeDelete
        );

        $resource->SetImage($this->imageName);
        $resource->ChangeStatus($this->status);
        $resource->SetSortOrder($this->sortOrder);
        $resource->SetResourceTypeId($this->resourceTypeId);
        $resource->WithPublicId($this->publicId);
        $resource->WithSubscription($this->allowSubscription);
        $resource->SetBufferTime($this->bufferTime);
        $resource->SetColor($this->color);

        foreach ($this->attributeValues as $av) {
            $resource->WithAttribute($av);
        }

        $resource->SetResourceGroupIds($this->groupIds);
        $resource->SetCheckin($this->allowCheckin, $this->autoReleaseMinutes);

        if ($this->allowDisplay) {
            $resource->EnableDisplay();
        }

        return $resource;
    }

    /**
     * @param int $id
     * @return BookableResourceBuilder
     */
    public function Id($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return BookableResourceBuilder
     */
    public function Name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $location
     * @return BookableResourceBuilder
     */
    public function Location($location)
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @param string $contact
     * @return BookableResourceBuilder
     */
    public function Contact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @param string $notes
     * @return BookableResourceBuilder
     */
    public function Notes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @param string|int|null $minLength
     * @param string|int|null $maxLength
     * @return BookableResourceBuilder
     */
    public function Length($minLength, $maxLength)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @param bool $autoAssign
     * @return BookableResourceBuilder
     */
    public function AutoAssign($autoAssign)
    {
        $this->autoAssign = $autoAssign;
        return $this;
    }

    /**
     * @param bool $requiresApproval
     * @return BookableResourceBuilder
     */
    public function RequiresApproval($requiresApproval)
    {
        $this->requiresApproval = $requiresApproval;
        return $this;
    }

    /**
     * @param bool $allowMultiday
     * @return BookableResourceBuilder
     */
    public function AllowMultiday($allowMultiday)
    {
        $this->allowMultiday = $allowMultiday;
        return $this;
    }

    /**
     * @param int|null $maxParticipants
     * @return BookableResourceBuilder
     */
    public function MaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;
        return $this;
    }

    /**
     * @param string|int|null $add
     * @param string|int|null $update
     * @param string|int|null $delete
     * @return BookableResourceBuilder
     */
    public function MinNotice($add, $update, $delete)
    {
        $this->minNoticeAdd = $add;
        $this->minNoticeUpdate = $update;
        $this->minNoticeDelete = $delete;
        return $this;
    }

    /**
     * @param string|int|null $maxNotice
     * @return BookableResourceBuilder
     */
    public function MaxNotice($maxNotice)
    {
        $this->maxNotice = $maxNotice;
        return $this;
    }

    /**
     * @param string $description
     * @return BookableResourceBuilder
     */
    public function Description($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param int $scheduleId
     * @return BookableResourceBuilder
     */
    public function ScheduleId($scheduleId)
    {
        $this->scheduleId = $scheduleId;
        return $this;
    }

    /**
     * @param int $adminGroupId
     * @return BookableResourceBuilder
     */
    public function AdminGroupId($adminGroupId)
    {
        $this->adminGroupId = $adminGroupId;
        return $this;
    }

    /**
     * @param string $imageName
     * @return BookableResourceBuilder
     */
    public function ImageName($imageName)
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @param int $sortOrder
     * @return BookableResourceBuilder
     */
    public function SortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @param int $status
     * @return BookableResourceBuilder
     */
    public function Status($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param int $resourceTypeId
     * @return BookableResourceBuilder
     */
    public function ResourceType($resourceTypeId)
    {
        $this->resourceTypeId = $resourceTypeId;
        return $this;
    }

    /**
     * @param string $publicId
     * @return BookableResourceBuilder
     */
    public function PublicId($publicId)
    {
        $this->publicId = $publicId;
        return $this;
    }

    /**
     * @param bool $allowSubscription
     * @return BookableResourceBuilder
     */
    public function AllowSubscription($allowSubscription)
    {
        $this->allowSubscription = $allowSubscription;
        return $this;
    }

    /**
     * @param int|string|null $bufferTime
     * @return BookableResourceBuilder
     */
    public function BufferTime($bufferTime)
    {
        $this->bufferTime = $bufferTime;
        return $this;
    }

    /**
     * @param string $color
     * @return BookableResourceBuilder
     */
    public function Color($color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @param AttributeValue $attributeValue
     * @return BookableResourceBuilder
     */
    public function Attribute($attributeValue)
    {
        $this->attributeValues[] = $attributeValue;
        return $this;
    }

    /**
     * @param AttributeValue[] $attributeValues
     * @return BookableResourceBuilder
     */
    public function Attributes($attributeValues)
    {
        $this->attributeValues = $attributeValues;
        return $this;
    }

    /**
     * @param int $groupId
     * @return BookableResourceBuilder
     */
    public function GroupId($groupId)
    {
        $this->groupIds[] = $groupId;
        return $this;
    }

    /**
     * @param int[] $groupIds
     * @return BookableResourceBuilder
     */
    public function GroupIds($groupIds)
    {
        $this->groupIds = $groupIds;
        return $this;
    }

    /**
     * @param bool $allowCheckin
     * @return BookableResourceBuilder
     */
    public function AllowCheckin($allowCheckin)
    {
        $this->allowCheckin = $allowCheckin;
        return $this;
    }

    /**
     * @param int $autoReleaseMinutes
     * @return BookableResourceBuilder
     */
    public function AutoReleaseMinutes($autoReleaseMinutes)
    {
        $this->autoReleaseMinutes = $autoReleaseMinutes;
        return $this;
    }

    /**
     * @param int $allowDisplay
     * @return BookableResourceBuilder
     */
    public function AllowDisplay($allowDisplay)
    {
        $this->allowDisplay = $allowDisplay;
        return $this;
    }

    /**
     * @param int $peak
     * @param int $offPeak
     * @return BookableResourceBuilder
     */
    public function Credits($peak, $offPeak)
    {
        $this->peakCredits = $peak;
        $this->offPeakCredits = $offPeak;
        return $this;
    }
}

class BookableResource implements IBookableResource
{
    protected $_resourceId;
    protected $_name;
    protected $_location;
    protected $_contact;
    protected $_notes;
    protected $_description;
    /**
     * @var string|int
     */
    protected $_minLength;
    /**
     * @var string|int
     */
    protected $_maxLength;
    protected $_autoAssign;
    protected $_clearAllPermissions;
    protected $_autoAssignToggledOn = false;
    protected $_requiresApproval;
    protected $_allowMultiday;
    protected $_maxParticipants;
    /**
     * @var string|int
     */
    protected $_minNoticeAdd;
    /**
     * @var string|int
     */
    protected $_minNoticeUpdate;
    /**
     * @var string|int
     */
    protected $_minNoticeDelete;
    /**
     * @var string|int
     */
    protected $_maxNotice;
    /**
     * @var string|int
     */
    protected $_bufferTime;
    protected $_scheduleId;
    protected $_imageName;
    protected $_imageNames = [];
    protected $_statusId = ResourceStatus::AVAILABLE;
    protected $_statusReasonId;
    protected $_adminGroupId;
    protected $_isCalendarSubscriptionAllowed = false;
    protected $_isDisplayAllowed = false;
    protected $_publicId;
    protected $_scheduleAdminGroupId;
    protected $_sortOrder;
    protected $_resourceTypeId;
    protected $_resourceGroupIds = [];
    protected $_enableCheckIn = false;
    protected $_autoReleaseMinutes = null;
    protected $_color;
    protected $_textColor;
    protected $_creditsPerSlot;
    protected $_peakCreditsPerSlot;
    protected $_maxConcurrentReservations = 1;

    /**
     * @var array|AttributeValue[]
     */
    protected $_attributeValues = [];

    public function __construct(
        $resourceId,
        $name,
        $location,
        $contact,
        $notes,
        $minLength,
        $maxLength,
        $autoAssign,
        $requiresApproval,
        $allowMultiday,
        $maxParticipants,
        $minNoticeAdd,
        $maxNotice,
        $description = null,
        $scheduleId = null,
        $adminGroupId = null,
        $minNoticeUpdate = null,
        $minNoticeDelete = null,
        $bufferTime = null,
        $groupIds = null,
        $resourceTypeId = null,
    ) {
        $this->SetResourceId($resourceId);
        $this->SetName($name);
        $this->SetLocation($location);
        $this->SetContact($contact);
        $this->SetNotes($notes);
        $this->SetDescription($description);
        $this->SetMinLength($minLength);
        $this->SetMaxLength($maxLength);
        $this->_autoAssign = $autoAssign;
        $this->SetRequiresApproval($requiresApproval);
        $this->SetAllowMultiday($allowMultiday);
        $this->SetMaxParticipants($maxParticipants);
        $this->SetMinNoticeAdd($minNoticeAdd);
        $this->SetMinNoticeUpdate($minNoticeUpdate);
        $this->SetMinNoticeDelete($minNoticeDelete);
        $this->SetMaxNotice($maxNotice);
        $this->SetScheduleId($scheduleId);
        $this->SetAdminGroupId($adminGroupId);
        $this->SetBufferTime($bufferTime);
        $this->SetResourceGroupIds($groupIds ?? []);
        $this->SetResourceTypeId($resourceTypeId);
    }

    /**
     * @param string $resourceName
     * @param int $scheduleId
     * @param bool $autoAssign
     * @param int $order
     * @return BookableResource
     */
    public static function CreateNew($resourceName, $scheduleId, $autoAssign = false, $order = 0)
    {
        return new BookableResource(
            null,
            $resourceName,
            null,
            null,
            null,
            null,
            null,
            $autoAssign,
            null,
            null,
            null,
            null,
            null,
            null,
            $scheduleId
        );
    }

    /**
     * @param array $row
     * @return BookableResource
     */
    public static function Create($row)
    {
        $resource = new BookableResource(
            $row[ColumnNames::RESOURCE_ID],
            $row[ColumnNames::RESOURCE_NAME],
            $row[ColumnNames::RESOURCE_LOCATION],
            $row[ColumnNames::RESOURCE_CONTACT],
            $row[ColumnNames::RESOURCE_NOTES],
            $row[ColumnNames::RESOURCE_MINDURATION],
            $row[ColumnNames::RESOURCE_MAXDURATION],
            $row[ColumnNames::RESOURCE_AUTOASSIGN],
            $row[ColumnNames::RESOURCE_REQUIRES_APPROVAL],
            $row[ColumnNames::RESOURCE_ALLOW_MULTIDAY],
            $row[ColumnNames::RESOURCE_MAX_PARTICIPANTS],
            $row[ColumnNames::RESOURCE_MINNOTICE_ADD],
            $row[ColumnNames::RESOURCE_MAXNOTICE],
            $row[ColumnNames::RESOURCE_DESCRIPTION],
            $row[ColumnNames::SCHEDULE_ID]
        );

        $resource->SetImage($row[ColumnNames::RESOURCE_IMAGE_NAME]);
        $resource->SetAdminGroupId($row[ColumnNames::RESOURCE_ADMIN_GROUP_ID]);
        $resource->SetSortOrder($row[ColumnNames::RESOURCE_SORT_ORDER]);
        $resource->ChangeStatus($row[ColumnNames::RESOURCE_STATUS_ID], $row[ColumnNames::RESOURCE_STATUS_REASON_ID]);

        $resource->WithPublicId($row[ColumnNames::PUBLIC_ID]);
        $resource->WithSubscription($row[ColumnNames::ALLOW_CALENDAR_SUBSCRIPTION]);
        $resource->WithScheduleAdminGroupId($row[ColumnNames::SCHEDULE_ADMIN_GROUP_ID_ALIAS]);
        $resource->SetResourceTypeId($row[ColumnNames::RESOURCE_TYPE_ID]);
        $resource->SetBufferTime($row[ColumnNames::RESOURCE_BUFFER_TIME]);
        $resource->SetColor($row[ColumnNames::RESERVATION_COLOR]);

        if (isset($row[ColumnNames::ATTRIBUTE_LIST])) {
            $attributes = CustomAttributes::Parse($row[ColumnNames::ATTRIBUTE_LIST]);
            foreach ($attributes->All() as $id => $value) {
                $resource->WithAttribute(new AttributeValue($id, $value));
            }
        }
        if (isset($row[ColumnNames::RESOURCE_GROUP_LIST])) {
            $groupIds = explode('!sep!', $row[ColumnNames::RESOURCE_GROUP_LIST]);
            for ($i = 0; $i < count($groupIds); $i++) {
                $resource->WithResourceGroupId($groupIds[$i]);
            }
        }
        if (isset($row[ColumnNames::ENABLE_CHECK_IN])) {
            $resource->_enableCheckIn = intval($row[ColumnNames::ENABLE_CHECK_IN]);
        }
        if (isset($row[ColumnNames::AUTO_RELEASE_MINUTES])) {
            $resource->_autoReleaseMinutes = intval($row[ColumnNames::AUTO_RELEASE_MINUTES]);
        }
        if (isset($row[ColumnNames::RESOURCE_ALLOW_DISPLAY])) {
            $resource->_isDisplayAllowed = intval($row[ColumnNames::RESOURCE_ALLOW_DISPLAY]);
        }
        if (isset($row[ColumnNames::RESOURCE_IMAGE_LIST])) {
            $resource->_imageNames = explode('!sep!', $row[ColumnNames::RESOURCE_IMAGE_LIST]);
        }

        $resource->WithCreditsPerSlot($row[ColumnNames::CREDIT_COUNT]);
        $resource->WithPeakCreditsPerSlot($row[ColumnNames::PEAK_CREDIT_COUNT]);
        $resource->SetMinNoticeUpdate($row[ColumnNames::RESOURCE_MINNOTICE_UPDATE]);
        $resource->SetMinNoticeDelete($row[ColumnNames::RESOURCE_MINNOTICE_DELETE]);
        $resource->DeserializeProperties($row[ColumnNames::RESOURCE_ADDITIONAL_PROPERTIES]);

        return $resource;
    }

    public function GetResourceId()
    {
        return $this->_resourceId;
    }

    public function GetId()
    {
        return $this->_resourceId;
    }

    public function SetResourceId($value)
    {
        $this->_resourceId = $value;
    }

    public function GetName()
    {
        return $this->_name;
    }

    public function SetName($value)
    {
        $this->_name = $value;
    }

    public function GetLocation()
    {
        return $this->_location;
    }

    public function SetLocation($value)
    {
        $this->_location = $value;
    }

    public function HasLocation()
    {
        return !empty($this->_location);
    }

    public function GetContact()
    {
        return $this->_contact;
    }

    public function SetContact($value)
    {
        $this->_contact = $value;
    }

    public function HasContact()
    {
        return !empty($this->_contact);
    }

    public function GetNotes()
    {
        return $this->_notes;
    }

    public function SetNotes($value)
    {
        $this->_notes = $value;
    }

    public function HasNotes()
    {
        return !empty($this->_notes);
    }

    public function GetDescription()
    {
        return $this->_description;
    }

    public function SetDescription($value)
    {
        $this->_description = $value;
    }

    public function HasDescription()
    {
        return !empty($this->_description);
    }

    /**
     * @return TimeInterval
     */
    public function GetMinLength()
    {
        return TimeInterval::Parse($this->_minLength);
    }

    public function GetMinimumLength()
    {
        return $this->GetMinLength();
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMinLength($value)
    {
        $this->_minLength = $this->GetIntervalValue($value);
    }

    /**
     * @param $resourceGroupIds int[]
     */
    public function SetResourceGroupIds($resourceGroupIds)
    {
        $this->_resourceGroupIds = $resourceGroupIds;
    }

    /**
     * @param $resourceGroupId int
     */
    public function WithResourceGroupId($resourceGroupId)
    {
        $this->_resourceGroupIds[] = $resourceGroupId;
    }

    /**
     * @return int[]
     */
    public function GetResourceGroupIds()
    {
        return $this->_resourceGroupIds;
    }

    /**
     * @param string $imageName
     */
    public function RemoveImage($imageName)
    {
        if ($this->_imageName == $imageName) {
            $this->_imageName = null;
            if (!empty($this->_imageNames)) {
                $this->_imageName = $this->_imageNames[0];
                array_shift($this->_imageNames);
            }
        } else {
            $index = array_search($imageName, $this->_imageNames);
            if ($index !== false) {
                array_splice($this->_imageNames, $index, 1);
            }
        }
    }

    /**
     * @param string $imageName
     */
    public function ChangeDefaultImage($imageName)
    {
        if ($this->_imageName != $imageName) {
            $index = array_search($imageName, $this->_imageNames);

            if ($index !== false) {
                array_splice($this->_imageNames, $index, 1);
            }
            $this->_imageNames[] = $this->_imageName;

            $this->_imageName = '' . $imageName;
        }
    }

    /**
     * @param TimeInterval|string|int $value
     * @return int
     */
    private function GetIntervalValue($value)
    {
        if (is_a($value, 'TimeInterval')) {
            return $value->TotalSeconds();
        } else {
            return TimeInterval::Parse($value)->TotalSeconds();
        }
    }

    /**
     * @return bool
     */
    public function HasMinLength()
    {
        return !empty($this->_minLength);
    }

    /**
     * @return TimeInterval
     */
    public function GetMaxLength()
    {
        return TimeInterval::Parse($this->_maxLength);
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMaxLength($value)
    {
        $this->_maxLength = $this->GetIntervalValue($value);
    }

    /**
     * @return bool
     */
    public function HasMaxLength()
    {
        return !empty($this->_maxLength);
    }

    /**
     * @return bool
     */
    public function GetAutoAssign()
    {
        return $this->_autoAssign;
    }

    /**
     * @return bool
     */
    public function GetClearAllPermissions()
    {
        return $this->_clearAllPermissions;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function SetAutoAssign($value)
    {
        $value = intval($value);
        if ($this->_autoAssign == false && $value == true) {
            $this->_autoAssignToggledOn = true;
        } else {
            $this->_autoAssignToggledOn = false;
        }

        $this->_autoAssign = $value;
    }

    public function SetClearAllPermissions($value)
    {
        $this->_clearAllPermissions = intval($value);
    }

    /**
     * @return bool
     */
    public function GetRequiresApproval()
    {
        return $this->_requiresApproval;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function SetRequiresApproval($value)
    {
        if (!empty($value)) {
            $this->_requiresApproval = intval($value);
        } else {
            $this->_requiresApproval = 0;
        }
    }

    /**
     * @return bool
     */
    public function GetAllowMultiday()
    {
        return $this->_allowMultiday;
    }

    /**
     * @param bool $value
     * @return void
     */
    public function SetAllowMultiday($value)
    {
        $this->_allowMultiday = $value;
    }

    /**
     * @return int
     */
    public function GetMaxParticipants()
    {
        return $this->_maxParticipants;
    }

    /**
     * @param int $value
     */
    public function SetMaxParticipants($value)
    {
        $this->_maxParticipants = $value;
        if (empty($value)) {
            $this->_maxParticipants = null;
        }
    }

    /**
     * @return bool
     */
    public function HasMaxParticipants()
    {
        return !empty($this->_maxParticipants);
    }

    /**
     * @return TimeInterval
     */
    public function GetMinNoticeAdd()
    {
        return TimeInterval::Parse($this->_minNoticeAdd);
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMinNoticeAdd($value)
    {
        $this->_minNoticeAdd = $this->GetIntervalValue($value);
    }

    /**
     * @return bool
     */
    public function HasMinNoticeAdd()
    {
        return !empty($this->_minNoticeAdd);
    }

    /**
     * @return TimeInterval
     */
    public function GetMinNoticeUpdate()
    {
        return TimeInterval::Parse($this->_minNoticeUpdate);
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMinNoticeUpdate($value)
    {
        $this->_minNoticeUpdate = $this->GetIntervalValue($value);
    }

    /**
     * @return bool
     */
    public function HasMinNoticeUpdate()
    {
        return !empty($this->_minNoticeUpdate);
    }

    /**
     * @return TimeInterval
     */
    public function GetMinNoticeDelete()
    {
        return TimeInterval::Parse($this->_minNoticeDelete);
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMinNoticeDelete($value)
    {
        $this->_minNoticeDelete = $this->GetIntervalValue($value);
    }

    /**
     * @return bool
     */
    public function HasMinNoticeDelete()
    {
        return !empty($this->_minNoticeDelete);
    }

    /**
     * @return TimeInterval
     */
    public function GetMaxNotice()
    {
        return TimeInterval::Parse($this->_maxNotice);
    }

    /**
     * @param string|int|TimeInterval $value
     */
    public function SetMaxNotice($value)
    {
        $this->_maxNotice = $this->GetIntervalValue($value);
    }

    /**
     * @return bool
     */
    public function HasMaxNotice()
    {
        return !empty($this->_maxNotice);
    }

    /**
     * @return int
     */
    public function GetScheduleId()
    {
        return $this->_scheduleId;
    }

    /**
     * @param int $value
     * @return void
     */
    public function SetScheduleId($value)
    {
        $this->_scheduleId = $value;
    }

    /**
     * @return int
     */
    public function GetAdminGroupId()
    {
        return $this->_adminGroupId;
    }

    /**
     * @param int $adminGroupId
     */
    public function SetAdminGroupId($adminGroupId)
    {
        $this->_adminGroupId = $adminGroupId;
        if (empty($adminGroupId)) {
            $this->_adminGroupId = null;
        }
    }

    /**
     * @return bool
     */
    public function HasAdminGroup()
    {
        return !empty($this->_adminGroupId);
    }

    /**
     * @return string
     */
    public function GetImage()
    {
        return $this->_imageName;
    }

    /**
     * @param string $value
     * @return void
     */
    public function SetImage($value)
    {
        $this->_imageName = $value;
    }

    /**
     * @return bool
     */
    public function HasImage()
    {
        return !empty($this->_imageName) || !empty($this->_imageNames);
    }

    /**
     * @return string[]
     */
    public function GetImages()
    {
        return $this->_imageNames;
    }

    /**
     * @param $value string[]
     * @return void
     */
    public function SetImages($value)
    {
        $this->_imageNames = $value;
    }

    /**
     * @param string $fileName
     */
    public function AddImage($fileName)
    {
        $this->_imageNames[] = $fileName;
    }

    /**
     * @param int|ResourceStatus $statusId
     * @param int|null $statusReasonId
     * @return void
     */
    public function ChangeStatus($statusId, $statusReasonId = null)
    {
        $this->_statusId = $statusId;
        if (empty($statusReasonId)) {
            $statusReasonId = null;
        }
        $this->_statusReasonId = $statusReasonId;
    }

    /**
     * @return bool
     */
    public function IsAvailable()
    {
        return $this->_statusId == ResourceStatus::AVAILABLE;
    }

    /**
     * @return bool
     */
    public function IsUnavailable()
    {
        return $this->_statusId == ResourceStatus::UNAVAILABLE;
    }

    /**
     * @return bool
     */
    public function IsHidden()
    {
        return $this->_statusId == ResourceStatus::HIDDEN;
    }

    /**
     * @return int|ResourceStatus
     */
    public function GetStatusId()
    {
        return $this->_statusId;
    }

    /**
     * @return int|null
     */
    public function GetStatusReasonId()
    {
        return $this->_statusReasonId;
    }

    /**
     * @param bool $isAllowed
     */
    protected function SetIsCalendarSubscriptionAllowed($isAllowed)
    {
        $this->_isCalendarSubscriptionAllowed = $isAllowed;
    }

    /**
     * @return bool
     */
    public function GetIsCalendarSubscriptionAllowed()
    {
        return $this->_isCalendarSubscriptionAllowed;
    }

    /**
     * @param bool $isAllowed
     */
    protected function SetIsDisplayEnabled($isAllowed)
    {
        $this->_isDisplayAllowed = $isAllowed;
    }

    /**
     * @return bool
     */
    public function GetIsDisplayEnabled()
    {
        return !empty($this->_publicId);
    }

    /**
     * @param string $publicId
     */
    protected function SetPublicId($publicId)
    {
        $this->_publicId = $publicId;
    }

    /**
     * @return string
     */
    public function GetPublicId()
    {
        return $this->_publicId;
    }

    public function EnableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(true);
        if (empty($this->_publicId)) {
            $this->SetPublicId(BookedStringHelper::Random(20));
        }
    }

    public function DisableSubscription()
    {
        $this->SetIsCalendarSubscriptionAllowed(false);
    }

    public function GetSubscriptionUrl()
    {
        return new CalendarSubscriptionUrl(null, null, $this->GetPublicId());
    }

    public function EnableDisplay()
    {
        $this->SetIsDisplayEnabled(true);
        if (empty($this->_publicId)) {
            $this->SetPublicId(BookedStringHelper::Random(20));
        }
    }

    public function WithAttribute(AttributeValue $attribute)
    {
        $this->_attributeValues[$attribute->AttributeId] = $attribute;
    }

    /**
     * @return bool
     */
    public function IsCheckInEnabled()
    {
        return $this->_enableCheckIn;
    }

    /**
     * @return bool
     */
    public function IsAutoReleased()
    {
        return !is_null($this->_autoReleaseMinutes);
    }

    /**
     * @return int|null
     */
    public function GetAutoReleaseMinutes()
    {
        return $this->_autoReleaseMinutes;
    }

    /**
     * @param bool $enabled
     * @param int|null $autoReleaseMinutes
     */
    public function SetCheckin($enabled, $autoReleaseMinutes = null)
    {
        if ($autoReleaseMinutes <= 0) {
            $autoReleaseMinutes = null;
        }

        $this->_enableCheckIn = $enabled;
        $this->_autoReleaseMinutes = $enabled ? $autoReleaseMinutes : null;
    }

    /**
     * @var array|AttributeValue[]
     */
    private $_addedAttributeValues = [];

    /**
     * @var array|AttributeValue[]
     */
    private $_removedAttributeValues = [];

    /**
     * @param AttributeValue[]|array $attributes
     */
    public function ChangeAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            $this->ChangeAttribute($attribute);
        }
    }

    /**
     * @param $attribute AttributeValue
     */
    public function ChangeAttribute($attribute)
    {
        $this->_removedAttributeValues[] = $attribute;
        $this->_addedAttributeValues[] = $attribute;
        $this->AddAttributeValue($attribute);
    }

    /**
     * @param $attributeValue AttributeValue
     */
    public function AddAttributeValue($attributeValue)
    {
        $this->_attributeValues[$attributeValue->AttributeId] = $attributeValue;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetAddedAttributes()
    {
        return $this->_addedAttributeValues;
    }

    /**
     * @return array|AttributeValue[]
     */
    public function GetRemovedAttributes()
    {
        return $this->_removedAttributeValues;
    }

    /**
     * @param $customAttributeId
     * @return mixed
     */
    public function GetAttributeValue($customAttributeId)
    {
        if (array_key_exists($customAttributeId, $this->_attributeValues)) {
            return $this->_attributeValues[$customAttributeId]->Value;
        }

        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'BookableResource' . $this->_resourceId;
    }

    /**
     * @static
     * @return BookableResource
     */
    public static function null()
    {
        return new BookableResource(null, null, null, null, null, null, null, false, false, false, null, null, null);
    }

    public function WithPublicId($publicId)
    {
        $this->SetPublicId($publicId);
    }

    public function WithSubscription($isAllowed)
    {
        $this->SetIsCalendarSubscriptionAllowed($isAllowed);
    }

    /**
     * @param $scheduleAdminGroupId int
     */
    protected function WithScheduleAdminGroupId($scheduleAdminGroupId)
    {
        $this->_scheduleAdminGroupId = $scheduleAdminGroupId;
    }

    /**
     * @return int
     */
    public function GetScheduleAdminGroupId()
    {
        return $this->_scheduleAdminGroupId;
    }

    /**
     * @param int $sortOrder
     */
    public function SetSortOrder($sortOrder)
    {
        $this->_sortOrder = intval($sortOrder);
    }

    /**
     * @return int
     */
    public function GetSortOrder()
    {
        return $this->_sortOrder;
    }

    /**
     * @param int $resourceTypeId
     */
    public function SetResourceTypeId($resourceTypeId)
    {
        $this->_resourceTypeId = $resourceTypeId;
    }

    /**
     * @return int
     */
    public function GetResourceTypeId()
    {
        return $this->_resourceTypeId;
    }

    /**
     * @return bool
     */
    public function HasResourceType()
    {
        return !empty($this->_resourceTypeId);
    }

    /**
     * @return bool
     */
    public function HasBufferTime()
    {
        return !empty($this->_bufferTime);
    }

    /**
     * @param int|string|null $bufferTime
     */
    public function SetBufferTime($bufferTime)
    {
        $this->_bufferTime = $this->GetIntervalValue($bufferTime);
    }

    /**
     * @return TimeInterval
     */
    public function GetBufferTime()
    {
        return TimeInterval::Parse($this->_bufferTime);
    }

    /**
     * @return bool
     */
    public function WasAutoAssignToggledOn()
    {
        return $this->_autoAssignToggledOn;
    }

    /**
     * @return bool
     */
    public function HasColor()
    {
        return !empty($this->_color);
    }

    /**
     * @return string|null
     */
    public function GetColor()
    {
        return $this->_color;
    }

    /**
     * @param string $color
     */
    public function SetColor($color)
    {
        if (empty($color)) {
            $this->_color = '';
            $this->_textColor = '';
            return;
        }
        if (!BookedStringHelper::StartsWith($color, '#')) {
            $color = '#' . $color;
        }

        $this->_color = $color;
        $contrast = new ContrastingColor($color);
        $this->_textColor = $contrast;
    }

    /**
     * @return null|string
     */
    public function GetTextColor()
    {
        return $this->_textColor;
    }

    /**
     * @param $creditsPerSlot int
     */
    protected function WithCreditsPerSlot($creditsPerSlot)
    {
        $this->_creditsPerSlot = $creditsPerSlot;
    }

    /**
     * @param $creditsPerSlot int
     */
    protected function WithPeakCreditsPerSlot($creditsPerSlot)
    {
        $this->_peakCreditsPerSlot = $creditsPerSlot;
    }

    /**
     * @return int
     */
    public function GetCreditsPerSlot()
    {
        return empty($this->_creditsPerSlot) ? 0 : $this->_creditsPerSlot;
    }

    public function GetPeakCreditsPerSlot()
    {
        return empty($this->_peakCreditsPerSlot) ? 0 : $this->_peakCreditsPerSlot;
    }

    /**
     * @param $creditsPerSlot int
     */
    public function SetCreditsPerSlot($creditsPerSlot)
    {
        $this->_creditsPerSlot = $creditsPerSlot;
    }

    /**
     * @param $creditsPerSlot int
     */
    public function SetPeakCreditsPerSlot($creditsPerSlot)
    {
        $this->_peakCreditsPerSlot = $creditsPerSlot;
    }

    /**
     * @param $max int
     */
    public function SetMaxConcurrentReservations($max)
    {
        $val = !empty($max) ? $max : 1;
        $this->_maxConcurrentReservations = min(max(1, $val), 99);
    }

    /**
     * @return bool
     */
    public function GetAllowConcurrentReservations()
    {
        return $this->GetMaxConcurrentReservations() > 1;
    }

    /**
     * @return int
     */
    public function GetMaxConcurrentReservations()
    {
        return max($this->_maxConcurrentReservations, 1);
    }

    /**
     * @return string
     */
    public function GetSerializedProperties()
    {
        $properties = ResourceProperties::FromResource($this);
        return $properties->Serialize();
    }

    /**
     * @param $propertiesAsJson string
     */
    public function DeserializeProperties($propertiesAsJson)
    {
        $properties = ResourceProperties::Deserialize($propertiesAsJson);
        $this->SetMaxConcurrentReservations($properties->MaxConcurrentReservations);
    }

    public function AsCopy($name)
    {
        $this->SetResourceId(null);
        $this->SetName($name);
        $this->DisableSubscription();
        $this->SetImage(null);
        $this->WithPublicId(null);
    }
}

class ResourceProperties
{
    /**
     * @var int
     */
    public $MaxConcurrentReservations;

    public static function FromResource(BookableResource $resource)
    {
        $obj = new ResourceProperties();
        $obj->MaxConcurrentReservations = $resource->GetMaxConcurrentReservations();

        return $obj;
    }

    public static function Deserialize($propertiesAsJson)
    {
        $obj = new ResourceProperties();
        $decoded = json_decode($propertiesAsJson ?? '{}');
        if (isset($decoded->MaxConcurrentReservations)) {
            $obj->MaxConcurrentReservations = $decoded->MaxConcurrentReservations;
        }

        return $obj;
    }

    public function Serialize()
    {
        return json_encode($this);
    }
}
