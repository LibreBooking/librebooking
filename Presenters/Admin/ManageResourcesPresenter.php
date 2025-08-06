<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Graphics/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Admin/ImageUploadDirectory.php');
require_once(ROOT_DIR . 'lib/Application/Admin/ResourceImportCsv.php');
require_once(ROOT_DIR . 'lib/Application/Admin/CsvImportResult.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ResourceStatusChangeEmail.php');

use BaconQrCode\Renderer\GDLibRenderer;
use BaconQrCode\Writer;

class ManageResourcesActions
{
    public const ActionAdd = 'add';
    public const ActionChangeAdmin = 'changeAdmin';
    public const ActionChangeDescription = 'description';
    public const ActionChangeImage = 'image';
    public const ActionChangeLocation = 'location';
    public const ActionChangeContact = 'contact';
    public const ActionChangeNotes = 'notes';
    public const ActionChangeSchedule = 'schedule';
    public const ActionRemoveImage = 'removeImage';
    public const ActionDefaultImage = 'defaultImage';
    public const ActionRename = 'rename';
    public const ActionDelete = 'delete';
    public const ActionChangeStatus = 'changeStatus';
    public const ActionEnableSubscription = 'enableSubscription';
    public const ActionDisableSubscription = 'disableSubscription';
    public const ActionChangeSort = 'changeSort';
    public const ActionChangeResourceType = 'changeResourceType';
    public const ActionBulkUpdate = 'bulkUpdate';
    public const ActionChangeDuration = 'changeDuration';
    public const ActionChangeCapacity = 'changeCapacity';
    public const ActionChangeAccess = 'changeAccess';
    public const ActionChangeAttribute = 'changeAttribute';
    public const ActionChangeUserPermission = 'changeUserPermission';
    public const ActionChangeResourceGroups = 'changeResourceGroups';
    public const ActionChangeColor = 'changeColor';
    public const ActionChangeCredits = 'changeCredits';
    public const ActionPrintQR = 'printQR';
    public const ActionCopyResource = 'actionCopyResource';
    public const ImportResources = 'importResources';
    public const ActionChangeGroupPermission = 'changeGroupPermission';
    public const ActionBulkDelete = 'bulkDelete';
}

class ManageResourcesPresenter extends ActionPresenter
{
    /**
     * @var IManageResourcesPage
     */
    private $page;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IScheduleRepository
     */
    private $scheduleRepository;

    /**
     * @var IImageFactory
     */
    private $imageFactory;

    /**
     * @var IGroupViewRepository
     */
    private $groupRepository;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @var IUserPreferenceRepository
     */
    private $userPreferenceRepository;

    /**
     * @var IReservationViewRepository
     */
    private $reservationViewRepository;

    public function __construct(
        IManageResourcesPage $page,
        IResourceRepository $resourceRepository,
        IScheduleRepository $scheduleRepository,
        IImageFactory $imageFactory,
        IGroupViewRepository $groupRepository,
        IAttributeService $attributeService,
        IUserPreferenceRepository $userPreferenceRepository,
        IReservationViewRepository $reservationViewRepository
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->resourceRepository = $resourceRepository;
        $this->scheduleRepository = $scheduleRepository;
        $this->imageFactory = $imageFactory;
        $this->groupRepository = $groupRepository;
        $this->attributeService = $attributeService;
        $this->userPreferenceRepository = $userPreferenceRepository;
        $this->reservationViewRepository = $reservationViewRepository;

        $this->AddAction(ManageResourcesActions::ActionAdd, 'Add');
        $this->AddAction(ManageResourcesActions::ActionChangeAdmin, 'ChangeAdmin');
        $this->AddAction(ManageResourcesActions::ActionChangeDescription, 'ChangeDescription');
        $this->AddAction(ManageResourcesActions::ActionChangeImage, 'ChangeImage');
        $this->AddAction(ManageResourcesActions::ActionDefaultImage, 'DefaultImage');
        $this->AddAction(ManageResourcesActions::ActionRemoveImage, 'RemoveImage');
        $this->AddAction(ManageResourcesActions::ActionChangeLocation, 'ChangeLocation');
        $this->AddAction(ManageResourcesActions::ActionChangeContact, 'ChangeContact');
        $this->AddAction(ManageResourcesActions::ActionChangeNotes, 'ChangeNotes');
        $this->AddAction(ManageResourcesActions::ActionChangeSchedule, 'ChangeSchedule');
        $this->AddAction(ManageResourcesActions::ActionRename, 'Rename');
        $this->AddAction(ManageResourcesActions::ActionDelete, 'Delete');
        $this->AddAction(ManageResourcesActions::ActionChangeStatus, 'ChangeStatus');
        $this->AddAction(ManageResourcesActions::ActionEnableSubscription, 'EnableSubscription');
        $this->AddAction(ManageResourcesActions::ActionDisableSubscription, 'DisableSubscription');
        $this->AddAction(ManageResourcesActions::ActionChangeSort, 'ChangeSortOrder');
        $this->AddAction(ManageResourcesActions::ActionChangeResourceType, 'ChangeResourceType');
        $this->AddAction(ManageResourcesActions::ActionBulkUpdate, 'BulkUpdate');
        $this->AddAction(ManageResourcesActions::ActionChangeDuration, 'ChangeDuration');
        $this->AddAction(ManageResourcesActions::ActionChangeCapacity, 'ChangeCapacity');
        $this->AddAction(ManageResourcesActions::ActionChangeAccess, 'ChangeAccess');
        $this->AddAction(ManageResourcesActions::ActionChangeAttribute, 'ChangeAttribute');
        $this->AddAction(ManageResourcesActions::ActionChangeResourceGroups, 'ChangeResourceGroups');
        $this->AddAction(ManageResourcesActions::ActionChangeColor, 'ChangeColor');
        $this->AddAction(ManageResourcesActions::ActionChangeCredits, 'ChangeCredits');
        $this->AddAction(ManageResourcesActions::ActionPrintQR, 'PrintQRCode');
        $this->AddAction(ManageResourcesActions::ActionCopyResource, 'ActionCopyResource');
        $this->AddAction(ManageResourcesActions::ImportResources, 'ImportResource');
        $this->AddAction(ManageResourcesActions::ActionChangeGroupPermission, 'ChangeGroupPermission');
        $this->AddAction(ManageResourcesActions::ActionChangeUserPermission, 'ChangeUserPermission');
        $this->AddAction(ManageResourcesActions::ActionBulkDelete, 'BulkDelete');
    }

    public function PageLoad()
    {
        $resourceAttributes = $this->attributeService->GetByCategory(CustomAttributeCategory::RESOURCE);

        $filterValues = $this->page->GetFilterValues();

        $results = $this->resourceRepository->GetList(
            $this->page->GetPageNumber(),
            null,
            null,
            null,
            $filterValues->AsFilter($resourceAttributes)
        );
        $resources = $results->Results();
        $this->page->BindResources($resources);
        $this->page->BindPageInfo($results->PageInfo());

        $schedules = $this->scheduleRepository->GetAll();
        $scheduleList = [];

        /* @var Schedule $schedule */
        foreach ($schedules as $schedule) {
            $scheduleList[$schedule->GetId()] = $schedule->GetName();
        }
        $this->page->BindSchedules($scheduleList);
        $this->page->AllSchedules($schedules);

        $resourceTypes = $this->resourceRepository->GetResourceTypes();
        $resourceTypeList = [];

        /* @var ResourceType $resourceType */
        foreach ($resourceTypes as $resourceType) {
            $resourceTypeList[$resourceType->Id()] = $resourceType;
        }
        $this->page->BindResourceTypes($resourceTypeList);

        $statusReasons = $this->resourceRepository->GetStatusReasons();
        $statusReasonList = [];

        foreach ($statusReasons as $reason) {
            $statusReasonList[$reason->Id()] = $reason;
        }
        $this->page->BindResourceStatusReasons($statusReasonList);

        $groups = $this->groupRepository->GetGroupsByRole(RoleLevel::RESOURCE_ADMIN);
        $this->page->BindAdminGroups($groups);

        $attributeList = $this->attributeService->GetByCategory(CustomAttributeCategory::RESOURCE);
        $this->page->BindAttributeList($attributeList);

        $this->InitializeFilter($filterValues, $resourceAttributes);

        $this->page->BindResourceGroups($this->resourceRepository->GetResourceGroups(null, new ResourceFilterNone()));
    }

    public function Add()
    {
        $name = $this->page->GetResourceName();
        $scheduleId = $this->page->GetScheduleId();
        $autoAssign = $this->page->GetAutoAssign();
        $resourceAdminGroupId = $this->page->GetAdminGroupId();

        Log::Debug(
            "Adding new resource with name: %s, scheduleId: %s, autoAssign: %s, resourceAdminGroupId %s",
            $name,
            $scheduleId,
            $autoAssign,
            $resourceAdminGroupId
        );

        $resource = BookableResource::CreateNew($name, $scheduleId, $autoAssign);
        $resource->SetAdminGroupId($resourceAdminGroupId);
        $resourceId = $this->resourceRepository->Add($resource);

        $this->ChangeResourceImage($resourceId);
    }

    public function ChangeDuration()
    {
        $resourceId = $this->page->GetResourceId();
        $minDuration = $this->page->GetMinimumDuration();
        $maxDuration = $this->page->GetMaximumDuration();
        $bufferTime = $this->page->GetBufferTime();
        $allowMultiDay = $this->page->GetAllowMultiday();

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->SetMinLength($minDuration);
        $resource->SetMaxLength($maxDuration);
        $resource->SetBufferTime($bufferTime);
        $resource->SetAllowMultiday($allowMultiDay);

        Log::Debug(
            'Updating resource id=%s, minDuration=%s, maxDuration=%s, buffer=%s, allowMultiDay=%s',
            $resourceId,
            $minDuration,
            $maxDuration,
            $bufferTime,
            $allowMultiDay
        );

        $this->resourceRepository->Update($resource);

        $this->page->BindUpdatedDuration($resource);
    }

    public function ChangeCapacity()
    {
        $resourceId = $this->page->GetResourceId();
        $maxParticipants = $this->page->GetMaxParticipants();

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->SetMaxParticipants($maxParticipants);

        Log::Debug(
            'Updating resource id=%s, maxParticipants=%s',
            $resourceId,
            $maxParticipants
        );

        $this->resourceRepository->Update($resource);

        $this->page->BindUpdatedCapacity($resource);
    }

    public function ChangeAccess()
    {
        $resourceId = $this->page->GetResourceId();
        $requiresApproval = $this->page->GetRequiresApproval();
        $autoAssign = $this->page->GetAutoAssign();
        $clearAllPermissions = $this->page->GetAutoAssignClear();
        $minNoticeAdd = $this->page->GetStartNoticeMinutesAdd();
        $minNoticeUpdate = $this->page->GetStartNoticeMinutesUpdate();
        $minNoticeDelete = $this->page->GetStartNoticeMinutesDelete();
        $maxNotice = $this->page->GetEndNoticeMinutes();
        $enableCheckin = $this->page->GetEnableCheckin();
        $autoReleaseMinutes = $this->page->GetAutoReleaseMinutes();
        $allowConcurrent = $this->page->GetAllowConcurrentReservations();
        $maxConcurrent = $allowConcurrent ? $this->page->GetMaxConcurrentReservations() : 1;

        $resource = $this->resourceRepository->LoadById($resourceId);

        $resource->SetRequiresApproval($requiresApproval);
        $resource->SetAutoAssign($autoAssign);
        $resource->SetClearAllPermissions($clearAllPermissions);
        $resource->SetMinNoticeAdd($minNoticeAdd);
        $resource->SetMinNoticeUpdate($minNoticeUpdate);
        $resource->SetMinNoticeDelete($minNoticeDelete);
        $resource->SetMaxNotice($maxNotice);
        $resource->SetCheckin($enableCheckin, $autoReleaseMinutes);
        $resource->SetMaxConcurrentReservations($maxConcurrent);

        Log::Debug(
            'Updating resource id=%s, requiresApproval=%s, autoAssign=%s, minNoticeAdd=%s, minNoticeUpdate=%s, minNoticeDelete=%s, maxNotice=%s, maxConcurrent=%s',
            $resourceId,
            $requiresApproval,
            $autoAssign,
            $minNoticeAdd,
            $minNoticeUpdate,
            $minNoticeDelete,
            $maxNotice,
            $maxConcurrent
        );

        $this->resourceRepository->Update($resource);

        $this->page->BindUpdatedAccess($resource);
    }

    public function Delete()
    {
        $resourceId = $this->page->GetResourceId();
        $this->SaveResourceImage(null, $resourceId);
        $resource = $this->resourceRepository->LoadById($resourceId);
        $this->resourceRepository->Delete($resource);
    }

    public function ChangeDescription()
    {
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $resource->SetDescription($this->page->GetValue());

        $this->resourceRepository->Update($resource);
    }

    public function ChangeNotes()
    {
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $resource->SetNotes($this->page->GetValue());

        $this->resourceRepository->Update($resource);
    }

    public function Rename()
    {
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $name = $this->page->GetValue();
        if (!empty($name)) {
            $resource->SetName($name);

            $this->resourceRepository->Update($resource);
        }
    }

    public function ChangeLocation()
    {
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $resource->SetLocation($this->page->GetValue());

        $this->resourceRepository->Update($resource);
    }

    public function ChangeContact()
    {
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $resource->SetContact($this->page->GetValue());

        $this->resourceRepository->Update($resource);
    }

    public function ChangeImage()
    {
        $this->ChangeResourceImage($this->page->GetResourceId());
    }

    private function ChangeResourceImage($resourceId)
    {
        Log::Debug("Changing resource image for resource id %s", $resourceId);
        $uploadedImage = $this->page->GetUploadedImage();

        if ($uploadedImage == null) {
            return;
        }

        if ($uploadedImage->IsError()) {
            Log::Error('Error with uploaded image for resource id %s. %s', $resourceId, $uploadedImage->Error());
            die("Image error: " . $uploadedImage->Error());
        }

        $fileType = strtolower($uploadedImage->Extension());

        $supportedTypes = ['jpeg', 'gif', 'png', 'jpg'];

        if (!in_array($fileType, $supportedTypes)) {
            Log::Error('Invalid image type for resource id %s, filetype %s', $resourceId, $fileType);
            die("Invalid image type: $fileType");
        }

        $imageSize = getimagesize($uploadedImage->TemporaryName());
        $bytesNeeded = $imageSize[0] * $imageSize[1] * 3;
        $memoryLimit = ini_get('memory_limit');
        $currentUsage = memory_get_usage();
        $needed = ($bytesNeeded + $currentUsage) / 1048576;
        $limit = str_replace('M', '', $memoryLimit);

        if ($needed > $limit) {
            echo 'Image too big. Resize to a smaller size or reduce the resolution and try again.';
            Log::Error("Uploaded image for %s is too big. Needed %s limit %s", $resourceId, $needed, $limit);
            die();
        }

        $image = $this->imageFactory->Load($uploadedImage->TemporaryName());
        $image->ResizeToWidth(300);

        $time = time();
        $fileName = "resource{$resourceId}{$time}.$fileType";
        $path = $this->GetResourceImageDirectory($fileName);

        Log::Debug("Saving resource image $path");

        $image->Save($path);

        $resource = $this->SaveResourceImage($fileName, $resourceId);

        $this->page->BindResourceImages($resource);
    }

    public function RemoveImage()
    {
        $fileName = $this->page->GetImageName();
        $resourceId = $this->page->GetResourceId();
        $imageName = $this->GetImageName($fileName);
        $path = $this->GetResourceImageDirectory($imageName);

        @unlink($path);

        Log::Debug("Deleting resource image %s for resource %s", $path, $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->RemoveImage($imageName);

        $this->resourceRepository->Update($resource);

        $this->page->BindResourceImages($resource);
    }

    public function DefaultImage()
    {
        $fileName = $this->page->GetImageName();
        $resourceId = $this->page->GetResourceId();
        $imageName = $this->GetImageName($fileName);

        Log::Debug("Changing default resource image %s for resource %s", $imageName, $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->ChangeDefaultImage($imageName);

        $this->resourceRepository->Update($resource);

        $this->page->BindResourceImages($resource);
    }

    public function ChangeStatus()
    {
        $resourceId = $this->page->GetResourceId();
        $statusId = $this->page->GetStatusId();
        $statusReasonId = $this->page->GetStatusReasonId();
        $statusReason = $this->page->GetNewStatusReason();

        Log::Debug('Changing resource status. ResourceId: %s', $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);

        if (empty($statusReasonId) && !empty($statusReason)) {
            $statusReasonId = $this->resourceRepository->AddStatusReason($statusId, $statusReason);
        }

        $resource->ChangeStatus($statusId, $statusReasonId);
        $this->resourceRepository->Update($resource);

        if ($this->page->SendStatusChangeMessage()) {
            $emails = [];
            $days = intval($this->page->StatusChangeDays());
            $days = max(1, min($days, 365));
            $message = $this->page->GetStatusChangeMessage();

            Log::Debug("Sending resource status changed email to users. Days: %s", $days);

            $reservations = $this->reservationViewRepository->GetReservations(Date::Now(), Date::Now()->AddDays($days), null, null, null, $resourceId);

            foreach ($reservations as $reservation) {
                $email = $reservation->OwnerEmailAddress;
                if (!array_key_exists($email, $emails)) {
                    $emails[$email] = 1;
                    ServiceLocator::GetEmailService()->Send(new ResourceStatusChangeEmail($email, $resource, $message, $reservation->OwnerLanguage));
                }
            }
        }
    }

    public function ChangeSchedule()
    {
        $scheduleId = $this->page->GetValue();
        if (!empty($scheduleId)) {
            $resourceId = $this->page->GetResourceId();
            Log::Debug('Changing schedule for resource %s', $resourceId);
            $resource = $this->resourceRepository->LoadById($resourceId);

            $resource->SetScheduleId($scheduleId);
            $this->resourceRepository->Update($resource);
        }
    }

    public function ChangeAdmin()
    {
        $resourceId = $this->page->GetResourceId();
        Log::Debug('Changing resource admin for resource %s', $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $adminGroupId = $this->page->GetValue();
        $resource->SetAdminGroupId($adminGroupId);
        $this->resourceRepository->Update($resource);
    }

    public function EnableSubscription()
    {
        $resourceId = $this->page->GetResourceId();
        Log::Debug('Enable calendar subscription for resource %s', $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->EnableSubscription();
        Configuration::Instance()->EnableSubscription();
        $this->resourceRepository->Update($resource);

        $this->page->DisplayPublicSettings($resource);
    }

    public function DisableSubscription()
    {
        $resourceId = $this->page->GetResourceId();
        Log::Debug('Disable calendar subscription for resource %s', $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->DisableSubscription();
        $this->resourceRepository->Update($resource);

        $this->page->DisplayPublicSettings($resource);
    }

    public function ChangeAttribute()
    {
        $resourceId = $this->page->GetResourceId();

        $resource = $this->resourceRepository->LoadById($resourceId);

        $attributeValue = $this->GetInlineAttributeValue();
        Log::Debug('Changing attributes. ResourceId=%s, AttributeId=%s, Value=%s', $resourceId, $attributeValue->AttributeId, $attributeValue->Value);

        $resource->ChangeAttribute($attributeValue);
        $this->resourceRepository->Update($resource);
    }

    private function GetInlineAttributeValue()
    {
        $value = $this->page->GetValue();
        if (is_array($value)) {
            $value = $value[0];
        }
        $id = str_replace(FormKeys::ATTRIBUTE_PREFIX, '', $this->page->GetName());

        return new AttributeValue($id, $value);
    }

    public function ChangeSortOrder()
    {
        $resourceId = $this->page->GetResourceId();
        $sortOrder = $this->page->GetValue();
        Log::Debug('Changing sort order for resource %s', $resourceId);

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->SetSortOrder($sortOrder);
        $this->resourceRepository->Update($resource);
    }

    public function ChangeResourceType()
    {
        $resourceTypeId = $this->page->GetValue();
        $resourceId = $this->page->GetResourceId();
        Log::Debug('Changing resource type for resource %s', $resourceId);
        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->SetResourceTypeId($resourceTypeId);
        $this->resourceRepository->Update($resource);
    }

    private function GetAttributeValues()
    {
        $attributes = [];
        foreach ($this->page->GetAttributes() as $attribute) {
            $attributes[] = new AttributeValue($attribute->Id, $attribute->Value);
        }
        return $attributes;
    }

    private function SaveResourceImage($fileName, $resourceId)
    {
        $resource = $this->resourceRepository->LoadById($resourceId);

        if ($resource->HasImage()) {
            $resource->AddImage($fileName);
        } else {
            $resource->SetImage($fileName);
        }

        $this->resourceRepository->Update($resource);

        return $resource;
    }

    private function GetImageName($fileName)
    {
        $parts = explode('/', $fileName);
        return $parts[count($parts) - 1];
    }

    /**
     * @param ResourceFilterValues $filterValues
     * @param CustomAttribute[] $resourceAttributes
     */
    public function InitializeFilter($filterValues, $resourceAttributes)
    {
        $filters = $filterValues->Attributes;
        $attributeFilters = [];
        foreach ($resourceAttributes as $attribute) {
            $attributeValue = null;
            if (array_key_exists($attribute->Id(), $filters)) {
                $attributeValue = $filters[$attribute->Id()];
            }
            $attributeFilters[] = new LBAttribute($attribute, $attributeValue);
        }

        $this->page->BindAttributeFilters($attributeFilters);
        $this->page->SetFilterValues($filterValues);
    }

    public function BulkUpdate()
    {
        $scheduleId = $this->page->GetScheduleId();
        $resourceTypeId = $this->page->GetResourceTypeId();
        $location = $this->page->GetLocation();
        $contact = $this->page->GetContact();
        $description = $this->page->GetDescription();
        $notes = $this->page->GetNotes();
        $adminGroupId = $this->page->GetAdminGroupId();

        $statusId = $this->page->GetStatusId();
        $reasonId = $this->page->GetStatusReasonId();

        // need to figure out difference between empty and unchanged
        $minDuration = $this->page->GetMinimumDuration();
        $minDurationNone = $this->page->GetMinimumDurationNone();
        $maxDuration = $this->page->GetMaximumDuration();
        $maxDurationNone = $this->page->GetMaximumDurationNone();
        $bufferTime = $this->page->GetBufferTime();
        $bufferTimeNone = $this->page->GetBufferTimeNone();
        $minNoticeAdd = $this->page->GetStartNoticeMinutesAdd();
        $minNoticeNoneAdd = $this->page->GetStartNoticeNoneAdd();
        $minNoticeUpdate = $this->page->GetStartNoticeMinutesUpdate();
        $minNoticeNoneUpdate = $this->page->GetStartNoticeNoneUpdate();
        $minNoticeDelete = $this->page->GetStartNoticeMinutesDelete();
        $minNoticeNoneDelete = $this->page->GetStartNoticeNoneDelete();
        $maxNotice = $this->page->GetEndNoticeMinutes();
        $maxNoticeNone = $this->page->GetEndNoticeNone();
        $allowMultiDay = $this->page->GetBulkAllowMultiday();
        $requiresApproval = $this->page->GetBulkRequiresApproval();
        $autoAssign = $this->page->GetBulkAutoAssign();
        $enableCheckin = $this->page->GetBulkEnableCheckin();
        $allowSubscription = $this->page->GetAllowSubscriptions();
        $credits = $this->page->GetCredits();
        $peakCredits = $this->page->GetPeakCredits();
        $maxCapacity = $this->page->GetMaxParticipants();
        $unlimitedCapacity = $this->page->GetMaxParticipantsUnlimited();
        $allowConcurrent = $this->page->GetBulkAllowConcurrentReservations();
        $maxConcurrent = $this->page->GetMaxConcurrentReservations();

        $resourceIds = $this->page->GetBulkUpdateResourceIds();

        $emptyDuration = 'dhm';

        foreach ($resourceIds as $resourceId) {
            try {
                $resource = $this->resourceRepository->LoadById($resourceId);

                if ($this->ChangingDropDown($scheduleId)) {
                    $resource->SetScheduleId($scheduleId);
                }
                if ($this->ChangingDropDown($resourceTypeId)) {
                    $resource->SetResourceTypeId($resourceTypeId);
                }
                if ($this->ChangingValue($location)) {
                    $resource->SetLocation($location);
                }
                if ($this->ChangingValue($contact)) {
                    $resource->SetContact($contact);
                }
                if ($this->ChangingValue($description)) {
                    $resource->SetDescription($description);
                }
                if ($this->ChangingValue($notes)) {
                    $resource->SetNotes($notes);
                }
                if ($this->ChangingDropDown($adminGroupId)) {
                    $resource->SetAdminGroupId($adminGroupId);
                }
                if ($this->ChangingDropDown($statusId)) {
                    $resource->ChangeStatus($statusId, $reasonId);
                }
                if ($minDurationNone) {
                    $resource->SetMinLength(null);
                }
                if (!$minDurationNone && $minDuration != $emptyDuration) {
                    $resource->SetMinLength($minDuration);
                }
                if (!$maxDurationNone && $maxDuration != $emptyDuration) {
                    $resource->SetMaxLength($maxDuration);
                }
                if ($maxDurationNone) {
                    $resource->SetMaxLength(null);
                }
                if (!$bufferTimeNone && $bufferTime != $emptyDuration) {
                    $resource->SetBufferTime($bufferTime);
                }
                if ($bufferTimeNone) {
                    $resource->SetBufferTime(null);
                }
                if (!$minNoticeNoneAdd && $minNoticeAdd != $emptyDuration) {
                    $resource->SetMinNoticeAdd($minNoticeAdd);
                }
                if ($minNoticeNoneAdd) {
                    $resource->SetMinNoticeAdd(null);
                }
                if (!$minNoticeNoneUpdate && $minNoticeUpdate != $emptyDuration) {
                    $resource->SetMinNoticeUpdate($minNoticeUpdate);
                }
                if ($minNoticeNoneUpdate) {
                    $resource->SetMinNoticeUpdate(null);
                }
                if (!$minNoticeNoneDelete && $minNoticeDelete != $emptyDuration) {
                    $resource->SetMinNoticeDelete($minNoticeDelete);
                }
                if ($minNoticeNoneDelete) {
                    $resource->SetMinNoticeDelete(null);
                }
                if (!$maxNoticeNone && $maxNotice != $emptyDuration) {
                    $resource->SetMaxNotice($maxNotice);
                }
                if ($maxNoticeNone) {
                    $resource->SetMaxNotice(null);
                }
                if ($this->ChangingDropDown($allowMultiDay)) {
                    $resource->SetAllowMultiday($allowMultiDay);
                }
                if ($this->ChangingDropDown($requiresApproval)) {
                    $resource->SetRequiresApproval($requiresApproval);
                }
                if ($this->ChangingDropDown($autoAssign)) {
                    $resource->SetAutoAssign($autoAssign);
                }
                if ($this->ChangingDropDown($enableCheckin)) {
                    $resource->SetCheckin($enableCheckin, $this->page->GetAutoReleaseMinutes());
                }
                if ($this->ChangingDropDown($allowSubscription)) {
                    if ($allowSubscription) {
                        $resource->EnableSubscription();
                    } else {
                        $resource->DisableSubscription();
                    }
                }
                if (!empty($credits)) {
                    $resource->SetCreditsPerSlot($credits);
                }
                if (!empty($peakCredits)) {
                    $resource->SetPeakCreditsPerSlot($peakCredits);
                }

                if ($unlimitedCapacity) {
                    $resource->SetMaxParticipants(null);
                }
                if (!$unlimitedCapacity && $maxCapacity != '') {
                    $resource->SetMaxParticipants($maxCapacity);
                }

                if ($this->ChangingDropDown($allowConcurrent)) {
                    if ($allowConcurrent) {
                        $resource->SetMaxConcurrentReservations($maxConcurrent);
                    } else {
                        $resource->SetMaxConcurrentReservations(1);
                    }
                }

                /** @var AttributeValue $attribute */
                foreach ($this->GetAttributeValues() as $attribute) {
                    if (!empty($attribute->Value)) {
                        $resource->ChangeAttribute($attribute);
                    }
                }

                $this->resourceRepository->Update($resource);
            } catch (Exception $ex) {
                Log::Error('Error bulk updating resource. Id=%s. Error=%s', $resourceId, $ex);
            }
        }
    }

    public function BulkDelete()
    {
        $resourceIds = $this->page->GetBulkUpdateResourceIds();
        foreach ($resourceIds as $resourceId) {
            $resource = $this->resourceRepository->LoadById($resourceId);
            Log::Debug('Bulk deleting resource. Name=%s, Id=%s', $resource->GetName(), $resourceId);

            $this->resourceRepository->Delete($resource);
        }
    }

    public function ChangeUserPermission()
    {
        $userId = $this->page->GetPermissionUserId();
        $type = $this->page->GetPermissionType();
        $resourceId = $this->page->GetResourceId();

        $this->resourceRepository->ChangeResourceUserPermission($resourceId, $userId, $type);
    }

    public function ChangeGroupPermission()
    {
        $groupId = $this->page->GetPermissionGroupId();
        $type = $this->page->GetPermissionType();
        $resourceId = $this->page->GetResourceId();

        $this->resourceRepository->ChangeResourceGroupPermission($resourceId, $groupId, $type);
    }

    public function ChangeResourceGroups()
    {
        $resourceId = $this->page->GetResourceId();
        $resourceGroups = $this->page->GetResourceGroupIds();

        $resource = $this->resourceRepository->LoadById($resourceId);
        $currentGroupIds = $resource->GetResourceGroupIds();

        $diff = new ArrayDiff($currentGroupIds, $resourceGroups);

        foreach ($diff->GetRemovedFromArray1() as $i => $groupId) {
            $this->resourceRepository->RemoveResourceFromGroup($resourceId, $groupId);
        }

        foreach ($diff->GetAddedToArray1() as $i => $groupId) {
            $this->resourceRepository->AddResourceToGroup($resourceId, $groupId);
        }

        $resource->SetResourceGroupIds($resourceGroups);

        $groups = $this->resourceRepository->GetResourceGroups();
        $this->page->BindUpdatedResourceGroups($resource, $groups->GetGroupList(false));
    }

    public function ChangeColor()
    {
        $color = $this->page->GetColor();
        $resource = $this->resourceRepository->LoadById($this->page->GetResourceId());

        $resource->SetColor($color);

        $this->resourceRepository->Update($resource);
    }

    public function ChangeCredits()
    {
        $resourceId = $this->page->GetResourceId();
        $credits = $this->page->GetCredits();
        $peakCredits = $this->page->GetPeakCredits();

        $resource = $this->resourceRepository->LoadById($resourceId);
        $resource->SetCreditsPerSlot($credits);
        $resource->SetPeakCreditsPerSlot($peakCredits);

        $this->resourceRepository->Update($resource);

        $this->page->BindUpdatedResourceCredits($resource);
    }

    public function PrintQRCode()
    {
        $qrGenerator = new GDLibRenderer(400);

        $resourceId = $this->page->GetResourceId();

        $imageUploadDir = new ImageUploadDirectory();
        $imageName = "/resourceqr{$resourceId}.png";
        $url = $imageUploadDir->GetPath() . $imageName;
        $savePath = $imageUploadDir->GetDirectory() . $imageName;

        $qrPath = sprintf('%s/%s?%s=%s', Configuration::Instance()->GetScriptUrl(), Pages::RESOURCE_QR_ROUTER, QueryStringKeys::RESOURCE_ID, $resourceId);

        $writer = new Writer($qrGenerator);
        $writer->writeFile($qrPath, $savePath);
        $resource = $this->resourceRepository->LoadById($resourceId);
        $this->page->ShowQRCode($url, $resource->GetName());
    }

    public function ActionCopyResource()
    {
        $sourceId = $this->page->GetResourceId();
        $name = $this->page->GetResourceName();

        $resource = $this->resourceRepository->LoadById($sourceId);
        $resource->AsCopy($name);
        $this->resourceRepository->Add($resource);
        $this->resourceRepository->Update($resource);

        $resourceId = $resource->GetResourceId();

        foreach ($resource->GetResourceGroupIds() as $groupId) {
            $this->resourceRepository->AddResourceToGroup($resourceId, $groupId);
        }

        $groups = $this->resourceRepository->GetGroupsWithPermission($sourceId);

        /** @var GroupPermissionItemView $group */
        foreach ($groups->Results() as $group) {
            $this->resourceRepository->ChangeResourceGroupPermission($resourceId, $group->Id(), $group->PermissionType());
        }

        $users = $this->resourceRepository->GetUsersWithPermission($sourceId);
        /** @var UserPermissionItemView $user */
        foreach ($users->Results() as $user) {
            $this->resourceRepository->ChangeResourceUserPermission($resourceId, $user->Id, $user->PermissionType());
        }
    }

    public function ImportResource()
    {
        ini_set('max_execution_time', 600);

        $shouldUpdate = $this->page->GetUpdateOnImport();

        $attributes = $this->attributeService->GetByCategory(CustomAttributeCategory::RESOURCE);
        /** @var CustomAttribute[] $attributesIndexed */
        $attributesIndexed = [];
        /** @var CustomAttribute $attribute */
        foreach ($attributes as $attribute) {
            if (!$attribute->UniquePerEntity()) {
                $attributesIndexed[strtolower($attribute->Label())] = $attribute;
            }
        }

        $importFile = $this->page->GetImportFile();
        $csv = new ResourceImportCsv($importFile, $attributesIndexed);

        $importCount = 0;
        $messages = [];

        $rows = $csv->GetRows();

        if (count($rows) == 0) {
            $this->page->SetImportResult(new CsvImportResult(0, [], 'Empty file or missing header row'));
            return;
        }

        $groups = $this->groupRepository->GetGroupsByRole(RoleLevel::RESOURCE_ADMIN);
        $groupsIndexed = [];
        foreach ($groups as $group) {
            $groupsIndexed[strtolower($group->Name())] = $group->Id();
        }

        $resourceGroups = $this->resourceRepository->GetResourceGroups();
        $resourceGroupsIndexed = [];
        foreach ($resourceGroups->GetGroupList() as $group) {
            $resourceGroupsIndexed[strtolower($group->name)] = $group->id;
        }

        $defaultScheduleId = 0;
        $schedules = $this->scheduleRepository->GetAll();
        $schedulesIndexed = [];
        foreach ($schedules as $schedule) {
            $schedulesIndexed[strtolower($schedule->GetName())] = $schedule->GetId();
            if ($schedule->GetIsDefault()) {
                $defaultScheduleId = $schedule->GetId();
            }
        }

        $resourceTypes = $this->resourceRepository->GetResourceTypes();
        $resourceTypesIndexed = [];
        foreach ($resourceTypes as $resourceType) {
            $resourceTypesIndexed[strtolower($resourceType->Name())] = $resourceType->Id();
        }

        $resourceStatusesIndexed = ['available' => ResourceStatus::AVAILABLE, 'unavailable' => ResourceStatus::UNAVAILABLE, 'hidden' => ResourceStatus::HIDDEN];

        for ($i = 0; $i < count($rows); $i++) {
            $row = $rows[$i];

            try {
                $scheduleId = (empty($row->schedule) || !array_key_exists(
                    $row->schedule,
                    $schedulesIndexed
                )) ? $defaultScheduleId : $schedulesIndexed[$row->schedule];
                $resourceTypeId = (empty($row->resourceType) || !array_key_exists(
                    $row->resourceType,
                    $resourceTypesIndexed
                )) ? null : $resourceTypesIndexed[$row->resourceType];
                $adminGroupId = (empty($row->resourceAdministrator) || !array_key_exists(
                    $row->resourceAdministrator,
                    $groupsIndexed
                )) ? null : $groupsIndexed[$row->resourceAdministrator];
                $statusId = (empty($row->status) || !array_key_exists(
                    $row->status,
                    $resourceStatusesIndexed
                )) ? ResourceStatus::AVAILABLE : $resourceStatusesIndexed[$row->status];
                $autoAssign = $row->autoAssign == 'true' || $row->autoAssign == '1';

                $resource = null;
                if ($shouldUpdate) {
                    $resource = $this->resourceRepository->LoadByName($row->name);
                    if ($resource->GetId() == null) {
                        $shouldUpdate = false;
                    } else {
                        $resource->SetScheduleId($scheduleId);
                        $resource->SetAutoAssign($autoAssign);
                        $resource->SetSortOrder(intval($row->sortOrder));
                    }
                }

                if (!$shouldUpdate) {
                    $resource = BookableResource::CreateNew($row->name, $scheduleId, $autoAssign, intval($row->sortOrder));
                    $this->resourceRepository->Add($resource);
                }

                if ($resource !== null) {
                    $resource->ChangeStatus($statusId);
                    $resource->SetResourceTypeId($resourceTypeId);
                    $resource->SetLocation($row->location);
                    $resource->SetContact($row->contact);
                    $resource->SetDescription($row->description);
                    $resource->SetNotes($row->notes);
                    $resource->SetAdminGroupId($adminGroupId);
                    $resource->SetColor($row->color);
                    $resource->SetRequiresApproval($row->approvalRequired == 'true' || $row->approvalRequired == '1');
                    $resource->SetMaxParticipants($row->capacity);
                    $resource->SetMinLength($row->minLength);
                    $resource->SetMaxLength($row->maxLength);
                    $resource->SetBufferTime($row->buffer);
                    $resource->SetAllowMultiday($row->crossDay);
                    $resource->SetMinNoticeAdd($row->addNotice);
                    $resource->SetMinNoticeUpdate($row->updateNotice);
                    $resource->SetMinNoticeDelete($row->deleteNotice);
                    $resource->SetCheckin($row->checkIn, $row->autoreleaseMinutes);
                    $resource->SetCreditsPerSlot($row->credits);
                    $resource->SetPeakCreditsPerSlot($row->creditsPeak);
                    $resource->SetMaxConcurrentReservations($row->maximumConcurrent);

                    foreach ($row->attributes as $label => $value) {
                        if (empty($value)) {
                            continue;
                        }
                        if (array_key_exists($label, $attributesIndexed)) {
                            $attribute = $attributesIndexed[$label];
                            $resource->ChangeAttribute(new AttributeValue($attribute->Id(), $value));
                        }
                    }

                    $this->resourceRepository->Update($resource);

                    foreach ($row->resourceGroups as $groupName) {
                        $groupName = strtolower($groupName);
                        if (array_key_exists($groupName, $resourceGroupsIndexed)) {
                            Log::Debug('Assigning resource %s to group %s', $row->name, $groupName);
                            $this->resourceRepository->AddResourceToGroup($resource->GetId(), $resourceGroupsIndexed[$groupName]);
                        }
                    }
                }

                $importCount++;
            } catch (Exception $ex) {
                $messages[] = 'Invalid data in row ' . $i;
                Log::Error('Error importing resources. %s', $ex);
            }
        }

        $this->page->SetImportResult(new CsvImportResult($importCount, $csv->GetSkippedRowNumbers(), $messages));
    }

    public function ExportResources()
    {
        $this->PageLoad();
        $this->page->ShowExportCsv();
    }

    protected function LoadValidators($action)
    {
        if ($action == ManageResourcesActions::ActionChangeAttribute) {
            $attributes = $this->GetInlineAttributeValue();
            $this->page->RegisterValidator('attributeValidator', new AttributeValidatorInline(
                $this->attributeService,
                CustomAttributeCategory::RESOURCE,
                $attributes,
                $this->page->GetResourceId(),
                true,
                true
            ));
        }
        if ($action == ManageResourcesActions::ActionBulkUpdate) {
            $attributes = $this->GetAttributeValues();
            $this->page->RegisterValidator(
                'bulkAttributeValidator',
                new AttributeValidator($this->attributeService, CustomAttributeCategory::RESOURCE, $attributes, null, true, true)
            );
        }

        if ($action == ManageResourcesActions::ImportResources) {
            $this->page->RegisterValidator('fileExtensionValidator', new FileExtensionValidator('csv', $this->page->GetImportFile()));
        }
    }

    public function ProcessDataRequest($dataRequest)
    {
        switch ($dataRequest) {
            case 'all': {
                    $this->page->SetResourcesJson(array_map(['AdminResourceJson', 'FromBookable'], $this->resourceRepository->GetResourceList()));
                    break;
                }
            case 'users': {
                    $users = $this->resourceRepository->GetUsersWithPermission($this->page->GetResourceId());
                    $this->page->BindUserPermissions($users->Results());
                    break;
                }
            case 'usersAll': {
                    $userRepository = new UserRepository();
                    $users = $this->resourceRepository->GetUsersWithPermission($this->page->GetResourceId());
                    $users = $users->Results();
                    $allUsers = $userRepository->GetList(null, 1000);
                    $allUsers = $allUsers->Results();

                    $idsWithPermissions = [];
                    foreach ($users as $permission) {
                        $idsWithPermissions[$permission->Id] = true;
                    }
                    /** @var UserItemView $user */
                    foreach ($allUsers as $user) {
                        $found = array_key_exists($user->Id, $idsWithPermissions);

                        if (!$found) {
                            $u = new UserPermissionItemView();
                            $u->Id = $user->Id;
                            $u->First = $user->First;
                            $u->Last = $user->Last;
                            $users[] = $u;
                        }
                    }
                    $this->page->BindUserPermissions($users);
                    break;
                }
            case 'groups': {
                    $groups = $this->resourceRepository->GetGroupsWithPermission($this->page->GetResourceId());
                    $this->page->BindGroupPermissions($groups->Results());
                    break;
                }
            case 'groupsAll': {
                    $groups = $this->resourceRepository->GetGroupsWithPermission($this->page->GetResourceId());
                    /** @var GroupPermissionItemView[] $groups */
                    $groups = $groups->Results();
                    $allGroups = $this->groupRepository->GetList(null, 1000);
                    $allGroups = $allGroups->Results();

                    $idsWithPermissions = [];
                    foreach ($groups as $permission) {
                        $idsWithPermissions[$permission->Id] = true;
                    }

                    /** @var GroupItemView $group */
                    foreach ($allGroups as $group) {
                        $found = array_key_exists($group->Id(), $idsWithPermissions);

                        if (!$found) {
                            $groups[] = new GroupPermissionItemView($group->Id(), $group->Name());
                        }
                    }
                    $this->page->BindGroupPermissions($groups);
                    break;
                }
            case 'template': {
                    $attributes = $this->attributeService->GetByCategory(CustomAttributeCategory::RESOURCE);
                    $importAttributes = [];
                    foreach ($attributes as $attribute) {
                        if (!$attribute->UniquePerEntity()) {
                            $importAttributes[] = $attribute;
                        }
                    }
                    $this->page->ShowTemplateCSV($importAttributes);
                    break;
                }
            case 'export': {
                    $this->ExportResources();
                }
        }
    }

    private function ChangingDropDown($value)
    {
        return $value != "-1";
    }

    private function ChangingValue($value)
    {
        return !empty($value);
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function GetResourceImageDirectory($fileName)
    {
        $imageUploadDirectory = Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_IMAGE_DIRECTORY);

        $path = '';

        if (is_dir($imageUploadDirectory)) {
            $path = $imageUploadDirectory;
        } else {
            if (is_dir(ROOT_DIR . $imageUploadDirectory)) {
                $path = ROOT_DIR . $imageUploadDirectory;
            }
        }
        return $path = "$path/$fileName";;
    }
}

class AdminResourceJson
{
    public $Id;
    public $Name;

    public function __construct($id, $name)
    {
        $this->Id = $id;
        $this->Name = $name;
    }

    public static function FromBookable(BookableResource $resource)
    {
        return new AdminResourceJson($resource->GetId(), $resource->GetName());
    }
}

class UserResults
{
    /**
     * @param UserItemView[] $users
     * @param int $totalUsers
     */
    public function __construct($users, $totalUsers)
    {
        foreach ($users as $user) {
            $this->Users[] = new AutocompleteUser($user->Id, $user->First, $user->Last, $user->Email, $user->Username);
        }
        $this->Total = $totalUsers;
    }

    /**
     * @var int
     */
    public $Total;

    /**
     * @var AutocompleteUser[]
     */
    public $Users;
}

class GroupResults
{
    /**
     * @param GroupItemView[] $groups
     * @param int $totalGroups
     */
    public function __construct($groups, $totalGroups)
    {
        $this->Groups = $groups;
        $this->Total = $totalGroups;
    }

    /**
     * @var int
     */
    public $Total;

    /**
     * @var GroupItemView[]
     */
    public $Groups;
}

class ResourceFilterNone implements IResourceFilter
{
    /**
     * @param IResource $resource
     * @return bool
     */
    public function ShouldInclude($resource)
    {
        return false;
    }
}
