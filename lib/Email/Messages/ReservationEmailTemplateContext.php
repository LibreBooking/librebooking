<?php

class ReservationEmailTemplateContext
{
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
        private IAttributeRepository $attributeRepository
    ) {
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
                $repeatDates[] = $repeated->StartDate()->ToTimezone($timezone);
                $repeatRanges[] = $repeated->Duration()->ToTimezone($timezone);
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

        foreach ($attributes as $attribute) {
            if (!$attribute->HasSecondaryEntities()) {
                continue;
            }

            if (!in_array($this->reservationSeries->ResourceId(), $attribute->SecondaryEntityIds())) {
                continue;
            }

            $attributeValues[] = new LBAttribute($attribute, $this->reservationSeries->GetAttributeValue($attribute->Id()));
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
     *   location: string|null,
     *   contact: string|null,
     *   notes: string|null,
     *   description: string|null,
     *   image: string|null,
     *   attributes: list<LBAttribute>
     * }>
     */
    public function Resources(bool $includeAdminOnly = false): array
    {
        $resourceAttributes = $this->attributeRepository->GetByCategory(CustomAttributeCategory::RESOURCE);
        $resources = [];

        foreach ($this->reservationSeries->AllResources() as $resource) {
            $resources[] = $this->BuildResourceData($resource, $resourceAttributes, $includeAdminOnly);
        }

        return $resources;
    }

    /**
     * @param list<CustomAttribute> $resourceAttributeDefinitions
     * @return array{
     *   id: int,
     *   name: string,
     *   location: string|null,
     *   contact: string|null,
     *   notes: string|null,
     *   description: string|null,
     *   image: string|null,
     *   attributes: list<LBAttribute>
     * }
     */
    private function BuildResourceData(BookableResource $resource, array $resourceAttributeDefinitions, bool $includeAdminOnly): array
    {
        $resourceImage = self::BuildResourceImageUrl($resource->GetImage());

        $resourceAttributeValues = [];
        foreach ($resourceAttributeDefinitions as $attribute) {
            if (!$includeAdminOnly && $attribute->AdminOnly()) {
                continue;
            }

            if ($attribute->AppliesToEntity($resource->GetId())) {
                $resourceAttributeValues[] = new LBAttribute($attribute, $resource->GetAttributeValue($attribute->Id()));
            }
        }

        return [
            'id' => $resource->GetId(),
            'name' => $resource->GetName(),
            'location' => $resource->GetLocation(),
            'contact' => $resource->GetContact(),
            'notes' => $resource->GetNotes(),
            'description' => $resource->GetDescription(),
            'image' => $resourceImage,
            'attributes' => $resourceAttributeValues,
        ];
    }
}
