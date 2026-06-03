<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Domain/Values/RoleLevel.php');

class ReservationEmailTemplateContext
{
    /** @var array<int, Schedule>|null */
    private ?array $schedulesById = null;

    /** @var array<int, GroupItemView>|null */
    private ?array $resourceAdminGroupsById = null;

    public static function BuildResourceImageUrl(?string $image): ?string
    {
        if (empty($image)) {
            return null;
        }

        return Configuration::Instance()->GetKey(ConfigKeys::UPLOAD_IMAGE_URL) . '/' . $image;
    }

    public function __construct(
        private ReservationSeries $reservationSeries,
        private User $reservationOwner,
        private BookableResource $primaryResource,
        private IAttributeRepository $attributeRepository,
        private ?IScheduleRepository $scheduleRepository = null,
        private ?IGroupViewRepository $groupRepository = null
    ) {
        $this->scheduleRepository ??= new ScheduleRepository();
        $this->groupRepository ??= new GroupRepository();
    }

    /**
     * Returns the start dates and durations for each instance of a recurring series,
     * converted to the given timezone. Both lists are empty for non-recurring reservations.
     *
     * @return array{RepeatDates: list<Date>, RepeatRanges: list<DateRange>}
     */
    public function GetRecurrenceInstances(string $timezone): array
    {
        $repeatDates = [];
        $repeatRanges = [];

        if ($this->reservationSeries->IsRecurring()) {
            foreach ($this->reservationSeries->Instances() as $repeated) {
                $repeatDates[] = $repeated->StartDate()->ToTimezone(timezone: $timezone);
                $repeatRanges[] = $repeated->Duration()->ToTimezone(timezone: $timezone);
            }
        }

        return ['RepeatDates' => $repeatDates, 'RepeatRanges' => $repeatRanges];
    }

    /**
     * @return list<string>
     */
    public function ResourceNames(): array
    {
        $resourceNames = [];
        foreach ($this->reservationSeries->AllResources() as $resource) {
            $resourceNames[] = $resource->GetName();
        }

        return $resourceNames;
    }

    /**
     * @return list<LBAttribute>
     */
    public function ReservationAttributes(): array
    {
        $attributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESERVATION);
        $attributeValues = [];
        $resourceIds = $this->reservationSeries->AllResourceIds();
        $resourceTypeIds = [];
        // Precompute resource type IDs used by this reservation so RESOURCE_TYPE-scoped
        // attributes can be matched without recomputing inside the loop.
        foreach ($this->reservationSeries->AllResources() as $resource) {
            $resourceTypeId = $resource->GetResourceTypeId();
            if (!empty($resourceTypeId)) {
                $resourceTypeIds[] = $resourceTypeId;
            }
        }
        $ownerId = $this->reservationOwner->Id();

        foreach ($attributes as $attribute) {
            if ($attribute->HasSecondaryEntities()) {
                $secondaryEntityIds = $attribute->SecondaryEntityIds();
                // Secondary entity IDs are category-specific, so we must branch by
                // SecondaryCategory() to avoid matching across incompatible ID domains.
                $applies = match ($attribute->SecondaryCategory()) {
                    CustomAttributeCategory::RESOURCE => count(array_intersect($resourceIds, $secondaryEntityIds)) > 0,
                    CustomAttributeCategory::RESOURCE_TYPE => count(array_intersect($resourceTypeIds, $secondaryEntityIds)) > 0,
                    CustomAttributeCategory::USER => in_array($ownerId, $secondaryEntityIds),
                    default => false,
                };

                if (!$applies) {
                    continue;
                }
            }

            $attributeValues[] = new LBAttribute(
                attributeDefinition: $attribute,
                value: $this->reservationSeries->GetAttributeValue($attribute->Id())
            );
        }

        return $attributeValues;
    }

    public function CreatedBy(): ?FullName
    {
        $bookedBy = $this->reservationSeries->BookedBy();
        if ($bookedBy != null && ($bookedBy->UserId != $this->reservationOwner->Id())) {
            return new FullName($bookedBy->FirstName, $bookedBy->LastName);
        }

        return null;
    }

    /**
     * @return list<array{
     *   id: int,
     *   name: string,
     *   scheduleName: string,
     *   location: string|null,
     *   contact: string|null,
     *   notes: string|null,
     *   description: string|null,
     *   resourceAdministrator: string,
     *   image: string|null,
     *   attributes: list<LBAttribute>,
     *   attributeRows: list<array{label: string, displayValue: string}>
     * }>
     */
    public function Resources(bool $includeAdminOnly = false): array
    {
        $resourceAttributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESOURCE);
        $resources = [];

        foreach ($this->reservationSeries->AllResources() as $resource) {
            $resources[] = $this->BuildResourceData(
                resource: $resource,
                resourceAttributeDefinitions: $resourceAttributes,
                includeAdminOnly: $includeAdminOnly
            );
        }

        return $resources;
    }

    /**
     * @param list<CustomAttribute> $resourceAttributeDefinitions
     * @return array{
     *   id: int,
     *   name: string,
     *   scheduleName: string,
     *   location: string|null,
     *   contact: string|null,
     *   notes: string|null,
     *   description: string|null,
     *   resourceAdministrator: string,
     *   image: string|null,
     *   attributes: list<LBAttribute>,
     *   attributeRows: list<array{label: string, displayValue: string}>
     * }
     */
    private function BuildResourceData(BookableResource $resource, array $resourceAttributeDefinitions, bool $includeAdminOnly): array
    {
        $resourceImage = self::BuildResourceImageUrl(image: $resource->GetImage());

        $resourceAttributeValues = [];
        foreach ($resourceAttributeDefinitions as $attribute) {
            if (!$includeAdminOnly && $attribute->AdminOnly()) {
                continue;
            }

            if ($attribute->AppliesToEntity($resource->GetId())) {
                $resourceAttributeValues[] = new LBAttribute(
                    attributeDefinition: $attribute,
                    value: $resource->GetAttributeValue($attribute->Id())
                );
            }
        }

        usort(
            $resourceAttributeValues,
            static fn (LBAttribute $left, LBAttribute $right): int => strcasecmp($left->Label(), $right->Label())
        );

        return [
            'id' => $resource->GetId(),
            'name' => $resource->GetName(),
            'scheduleName' => $this->ScheduleName(resource: $resource),
            'location' => $resource->GetLocation(),
            'contact' => $resource->GetContact(),
            'notes' => $resource->GetNotes(),
            'description' => $resource->GetDescription(),
            'resourceAdministrator' => $this->ResourceAdministrator(resource: $resource),
            'image' => $resourceImage,
            'attributes' => $resourceAttributeValues,
            'attributeRows' => $this->AttributeRows(attributes: $resourceAttributeValues),
        ];
    }

    /**
     * @param list<LBAttribute> $attributes
     * @return list<array{label: string, displayValue: string}>
     */
    private function AttributeRows(array $attributes): array
    {
        $rows = [];
        $resources = Resources::GetInstance();
        $dateFormat = $resources->GetDateFormat('general_datetime');

        foreach ($attributes as $attribute) {
            $rows[] = [
                'label' => $attribute->Label(),
                'displayValue' => $this->AttributeDisplayValue(
                    attribute: $attribute,
                    resources: $resources,
                    dateFormat: $dateFormat
                ),
            ];
        }

        return $rows;
    }

    private function AttributeDisplayValue(LBAttribute $attribute, Resources $resources, string $dateFormat): string
    {
        $value = $attribute->Value();

        if ($value === null || $value === '') {
            return '';
        }

        if ($attribute->Type() == CustomAttributeTypes::CHECKBOX) {
            return $value == '1'
                ? $resources->GetString('True')
                : $resources->GetString('False');
        }

        if ($attribute->Type() == CustomAttributeTypes::DATETIME) {
            if ($value instanceof Date) {
                return $value->Format($dateFormat);
            }

            if (is_string($value)) {
                try {
                    return Date::Parse($value)->Format($dateFormat);
                } catch (Exception $e) {
                    return $value;
                }
            }
        }

        return strval($value);
    }

    private function ScheduleName(BookableResource $resource): string
    {
        $schedules = $this->SchedulesById();
        $scheduleId = $resource->GetScheduleId();

        return isset($schedules[$scheduleId]) ? $schedules[$scheduleId]->GetName() : '';
    }

    private function ResourceAdministrator(BookableResource $resource): string
    {
        $groups = $this->ResourceAdminGroupsById();
        $groupId = $resource->GetAdminGroupId();

        $key = $groupId ?? '';
        return isset($groups[$key]) ? $groups[$key]->Name : '';
    }

    /**
     * @return array<int, Schedule>
     */
    private function SchedulesById(): array
    {
        if ($this->schedulesById === null) {
            $this->schedulesById = [];

            foreach ($this->scheduleRepository->GetAll() as $schedule) {
                $this->schedulesById[$schedule->GetId()] = $schedule;
            }
        }

        return $this->schedulesById;
    }

    /**
     * @return array<int, GroupItemView>
     */
    private function ResourceAdminGroupsById(): array
    {
        if ($this->resourceAdminGroupsById === null) {
            $this->resourceAdminGroupsById = [];

            foreach ($this->groupRepository->GetGroupsByRole(RoleLevel::RESOURCE_ADMIN) as $group) {
                $this->resourceAdminGroupsById[$group->Id] = $group;
            }
        }

        return $this->resourceAdminGroupsById;
    }
}
