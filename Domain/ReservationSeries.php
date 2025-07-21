<?php

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/BookableResource.php');
require_once(ROOT_DIR . 'Domain/Reservation.php');
require_once(ROOT_DIR . 'Domain/Values/ReservationAccessory.php');
require_once(ROOT_DIR . 'Domain/Values/ReservationReminder.php');
require_once(ROOT_DIR . 'Domain/Values/ReservationPastTimeConstraint.php');
require_once(ROOT_DIR . 'Domain/ReservationAttachment.php');

class ReservationSeries
{
    /**
     * @var int
     */
    protected $seriesId;

    /**
     * @return int
     */
    public function SeriesId()
    {
        return $this->seriesId;
    }

    /**
     * @param int $seriesId
     */
    public function SetSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
    }

    /**
     * @var int
     */
    protected $_userId;

    /**
     * @return int
     */
    public function UserId()
    {
        return $this->_userId;
    }

    /**
     * @var UserSession
     */
    protected $_bookedBy;

    /**
     * @return UserSession
     */
    public function BookedBy()
    {
        return $this->_bookedBy;
    }

    /**
     * @var BookableResource
     */
    protected $_resource;

    /**
     * @return int
     */
    public function ResourceId()
    {
        return $this->_resource->GetResourceId();
    }

    /**
     * @return BookableResource
     */
    public function Resource()
    {
        return $this->_resource;
    }

    /**
     * @return int
     */
    public function ScheduleId()
    {
        return $this->_resource->GetScheduleId();
    }

    /**
     * @var string
     */
    protected $_title;

    /**
     * @return string
     */
    public function Title()
    {
        return $this->_title;
    }

    /**
     * @var string
     */
    protected $_description;

    /**
     * @return string
     */
    public function Description()
    {
        return $this->_description;
    }

    /**
     * @var IRepeatOptions
     */
    protected $repeatOptions;

    /**
     * @return IRepeatOptions
     */
    public function RepeatOptions()
    {
        return $this->repeatOptions;
    }

    /**
     * @var array|BookableResource[]
     */
    protected $_additionalResources = [];

    /**
     * @return array|BookableResource[]
     */
    public function AdditionalResources()
    {
        return $this->_additionalResources;
    }

    /**
     * @var ReservationAttachment[]|array
     */
    protected $addedAttachments = [];

    /**
     * @return int[]
     */
    public function AllResourceIds()
    {
        $ids = [$this->ResourceId()];
        foreach ($this->_additionalResources as $resource) {
            $ids[] = $resource->GetResourceId();
        }
        return $ids;
    }

    /**
     * @return array|BookableResource[]
     */
    public function AllResources()
    {
        return array_merge([$this->Resource()], $this->AdditionalResources());
    }

    /**
     * @var array|Reservation[]
     */
    protected $instances = [];

    /**
     * @return Reservation[]
     */
    public function Instances()
    {
        return $this->instances;
    }

    /**
     * @return Reservation[]
     */
    public function SortedInstances()
    {
        $instances = $this->Instances();

        uasort($instances, [$this, 'SortReservations']);

        return $instances;
    }

    /**
     * @param Reservation $r1
     * @param Reservation $r2
     * @return int
     */
    protected function SortReservations(Reservation $r1, Reservation $r2)
    {
        return $r1->StartDate()->Compare($r2->StartDate());
    }

    /**
     * @var array|ReservationAccessory[]
     */
    protected $_accessories = [];

    /**
     * @return array|ReservationAccessory[]
     */
    public function Accessories()
    {
        return $this->_accessories;
    }

    /**
     * @var array|AttributeValue[]
     */
    protected $_attributeValues = [];

    /**
     * @return array|AttributeValue[]
     */
    public function AttributeValues()
    {
        return $this->_attributeValues;
    }

    /**
     * @var Date
     */
    private $currentInstanceKey;

    /**
     * @var int|ReservationStatus
     */
    protected $statusId = ReservationStatus::Created;

    /**
     * @var ReservationReminder
     */
    protected $startReminder;

    /**
     * @var ReservationReminder
     */
    protected $endReminder;

    /**
     * @var bool
     */
    protected $allowParticipation = false;

    /**
     * @var int
     */
    protected $creditsRequired = 0;

    protected function __construct()
    {
        $this->repeatOptions = new RepeatNone();
        $this->startReminder = ReservationReminder::None();
        $this->endReminder = ReservationReminder::None();
    }

    /**
     * @param int $userId
     * @param BookableResource $resource
     * @param string $title
     * @param string $description
     * @param DateRange $reservationDate
     * @param IRepeatOptions $repeatOptions
     * @param UserSession $bookedBy
     * @return ReservationSeries
     */
    public static function Create(
        $userId,
        BookableResource $resource,
        $title,
        $description,
        $reservationDate,
        $repeatOptions,
        UserSession $bookedBy
    ) {
        $series = new ReservationSeries();
        $series->_userId = $userId;
        $series->_resource = $resource;
        $series->_title = $title;
        $series->_description = $description;
        $series->_bookedBy = $bookedBy;
        $series->UpdateDuration($reservationDate);
        $series->Repeats($repeatOptions);

        return $series;
    }

    /**
     * @param DateRange $reservationDate
     */
    protected function UpdateDuration(DateRange $reservationDate)
    {
        $this->AddNewCurrentInstance($reservationDate);
    }

    /**
     * @param IRepeatOptions $repeatOptions
     * @throws Exception
     */
    protected function Repeats(IRepeatOptions $repeatOptions)
    {
        $this->repeatOptions = $repeatOptions;

        $dates = $repeatOptions->GetDates($this->CurrentInstance()->Duration()->ToTimezone($this->_bookedBy->Timezone));

        if (empty($dates)) {
            return;
        }

        foreach ($dates as $date) {
            $this->AddNewInstance($date);
        }
    }

    /**
     * @return TimeInterval|null
     */
    public function MaxBufferTime()
    {
        $max = new TimeInterval(0);

        foreach ($this->AllResources() as $resource) {
            if ($resource->HasBufferTime()) {
                $buffer = $resource->GetBufferTime();
                if ($buffer->TotalSeconds() > $max->TotalSeconds()) {
                    $max = $buffer;
                }
            }
        }

        return $max->TotalSeconds() > 0 ? $max : null;
    }

    /**
     * @param Reservation $reservation
     * @return bool
     * @throws Exception
     */
    public function RemoveInstance(Reservation $reservation)
    {
        if ($reservation == $this->CurrentInstance()) {
            return false; // never remove the current instance, we need it for validations and notifications
        }

        $instanceKey = $this->GetNewKey($reservation);
        unset($this->instances[$instanceKey]);

        return true;
    }

    /**
     * @return bool
     */
    public function HasAcceptedTerms()
    {
        return $this->termsAcceptanceDate != null;
    }

    /**
     * @var Date|null
     */
    protected $termsAcceptanceDate;

    /**
     * @return Date|null
     */
    public function TermsAcceptanceDate()
    {
        return $this->termsAcceptanceDate;
    }

    /**
     * @param bool $accepted
     */
    public function AcceptTerms($accepted)
    {
        if ($accepted) {
            $this->termsAcceptanceDate = Date::Now();
        }
    }

    /**
     * @param DateRange $reservationDate
     * @return bool
     */
    protected function InstanceStartsOnDate(DateRange $reservationDate)
    {
        /** @var Reservation $instance */
        foreach ($this->instances as $instance) {
            if ($instance->StartDate()->DateEquals($reservationDate->GetBegin())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param DateRange $reservationDate
     * @return Reservation|null newly created instance
     */
    protected function AddNewInstance(DateRange $reservationDate): Reservation|null
    {
        $newInstance = new Reservation($this, $reservationDate);
        $this->AddInstance($newInstance);

        return $newInstance;
    }

    protected function AddNewCurrentInstance(DateRange $reservationDate)
    {
        $currentInstance = new Reservation($this, $reservationDate);
        $this->AddInstance($currentInstance);
        $this->SetCurrentInstance($currentInstance);
    }

    protected function AddInstance(Reservation $reservation)
    {
        $key = $this->CreateInstanceKey($reservation);
        $this->instances[$key] = $reservation;
    }

    protected function CreateInstanceKey(Reservation $reservation)
    {
        return $this->GetNewKey($reservation);
    }

    protected function GetNewKey(Reservation $reservation)
    {
        return $reservation->ReferenceNumber();
    }

    /**
     * @param BookableResource $resource
     */
    public function AddResource(BookableResource $resource)
    {
        $this->_additionalResources[] = $resource;
    }

    /**
     * @return bool
     */
    public function IsRecurring()
    {
        return $this->RepeatOptions()->RepeatType() != RepeatType::None;
    }

    /**
     * @return int|ReservationStatus
     */
    public function StatusId()
    {
        return $this->statusId;
    }

    /**
     * @param int|ReservationStatus $statusId
     */
    public function SetStatusId($statusId)
    {
        $this->statusId = $statusId;
    }

    public function RequiresApproval()
    {
        return $this->StatusId() == ReservationStatus::Pending;
    }

    /**
     * @param string $referenceNumber
     * @return Reservation
     */
    public function GetInstance($referenceNumber)
    {
        return $this->instances[$referenceNumber];
    }

    /**
     * @return Reservation
     * @throws Exception
     */
    public function CurrentInstance()
    {
        $instance = $this->GetInstance($this->GetCurrentKey());
        if (!isset($instance)) {
            throw new Exception("Current instance not found. Missing Reservation key {$this->GetCurrentKey()}");
        }
        return $instance;
    }

    /**
     * @param int[] $participantIds
     * @return void
     */
    public function ChangeParticipants($participantIds)
    {
        /** @var Reservation $instance */
        foreach ($this->Instances() as $instance) {
            $instance->ChangeParticipants($participantIds);
        }
    }

    /**
     * @param bool $shouldAllowParticipation
     */
    public function AllowParticipation($shouldAllowParticipation)
    {
        $this->allowParticipation = $shouldAllowParticipation;
    }

    /**
     * @return bool
     */
    public function GetAllowParticipation()
    {
        return $this->allowParticipation;
    }

    /**
     * @param int[] $inviteeIds
     * @return void
     */
    public function ChangeInvitees($inviteeIds)
    {
        /** @var Reservation $instance */
        foreach ($this->Instances() as $instance) {
            $instance->ChangeInvitees($inviteeIds);
        }
    }

    /**
     * @param string[] $invitedGuests
     * @param string[] $participatingGuests
     * @return void
     */
    public function ChangeGuests($invitedGuests, $participatingGuests)
    {
        /** @var Reservation $instance */
        foreach ($this->Instances() as $instance) {
            $instance->ChangeInvitedGuests($invitedGuests);
            $instance->ChangeParticipatingGuests($participatingGuests);
        }
    }

    /**
     * @param Reservation $current
     * @return void
     */
    protected function SetCurrentInstance(Reservation $current)
    {
        $this->currentInstanceKey = $this->GetNewKey($current);
    }

    /**
     * @return Date
     */
    protected function GetCurrentKey()
    {
        return $this->currentInstanceKey;
    }

    /**
     * @param Reservation $instance
     * @return bool
     * @throws Exception
     */
    protected function IsCurrent(Reservation $instance)
    {
        return $instance->ReferenceNumber() == $this->CurrentInstance()->ReferenceNumber();
    }

    /**
     * @param int $resourceId
     * @return bool
     */
    public function ContainsResource($resourceId)
    {
        return in_array($resourceId, $this->AllResourceIds());
    }

    /**
     * @param ReservationAccessory $accessory
     * @return void
     */
    public function AddAccessory(ReservationAccessory $accessory)
    {
        $this->_accessories[] = $accessory;
    }

    /**
     * @param AttributeValue $attributeValue
     */
    public function AddAttributeValue(AttributeValue $attributeValue)
    {
        $this->_attributeValues[$attributeValue->AttributeId] = $attributeValue;
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

    public function IsMarkedForDelete($reservationId)
    {
        return false;
    }

    public function IsMarkedForUpdate($reservationId)
    {
        return false;
    }

    /**
     * @return ReservationAttachment[]|array
     */
    public function AddedAttachments()
    {
        return $this->addedAttachments;
    }

    /**
     * @param ReservationAttachment $attachment
     */
    public function AddAttachment(ReservationAttachment $attachment)
    {
        $this->addedAttachments[] = $attachment;
    }

    public function WithSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
        foreach ($this->addedAttachments as $addedAttachment) {
            if ($addedAttachment != null) {
                $addedAttachment->WithSeriesId($seriesId);
            }
        }
    }

    /**
     * @return ReservationReminder
     */
    public function GetStartReminder()
    {
        return $this->startReminder;
    }

    /**
     * @return ReservationReminder
     */
    public function GetEndReminder()
    {
        return $this->endReminder;
    }

    public function AddStartReminder(ReservationReminder $reminder)
    {
        $this->startReminder = $reminder;
    }

    public function AddEndReminder(ReservationReminder $reminder)
    {
        $this->endReminder = $reminder;
    }

    public function GetCreditsRequired()
    {
        $creditsRequired = 0;
        foreach ($this->Instances() as $instance) {
            if (!$this->IsMarkedForDelete($instance->ReservationId())) {
                $creditsRequired += $instance->GetCreditsRequired();
            }
        }

        $this->creditsRequired = $creditsRequired;
        return $this->creditsRequired;
    }

    public function CalculateCredits(IScheduleLayout $layout)
    {
        $credits = 0;
        foreach ($this->AllResources() as $resource) {
            $credits += ($resource->GetCreditsPerSlot() + $resource->GetPeakCreditsPerSlot());
        }

        if ($credits == 0) {
            $this->creditsRequired = 0;
            return;
        }

        $this->TotalSlots($layout);
    }

    private function TotalSlots(IScheduleLayout $layout)
    {
        $slots = 0;
        foreach ($this->Instances() as $instance) {
            if ($this->IsMarkedForDelete($instance->ReservationId())) {
                continue;
            }

            $instanceSlots = 0;
            $peakSlots = 0;
            $startDate = $instance->StartDate()->ToTimezone($layout->Timezone());
            $endDate = $instance->EndDate()->ToTimezone($layout->Timezone());

            if ($startDate->DateEquals($endDate)) {
                $count = $layout->GetSlotCount($startDate, $endDate);
                Log::Debug('Slot count off peak %s, peak %s', $count->OffPeak, $count->Peak);
                $instanceSlots += $count->OffPeak;
                $peakSlots += $count->Peak;
            } else {
                for ($date = $startDate; $date->Compare($endDate) <= 0; $date = $date->GetDate()->AddDays(1)) {
                    if ($date->DateEquals($startDate)) {
                        $count = $layout->GetSlotCount($startDate, $endDate);
                        $instanceSlots += $count->OffPeak;
                        $peakSlots += $count->Peak;
                    } else {
                        if ($date->DateEquals($endDate)) {
                            $count = $layout->GetSlotCount($endDate->GetDate(), $endDate);
                            $instanceSlots += $count->OffPeak;
                            $peakSlots += $count->Peak;
                        } else {
                            $count = $layout->GetSlotCount($date, $endDate);
                            $instanceSlots += $count->OffPeak;
                            $peakSlots += $count->Peak;
                        }
                    }
                }
            }

            $creditsRequired = 0;
            foreach ($this->AllResources() as $resource) {
                $resourceCredits = $resource->GetCreditsPerSlot();
                $peakCredits = $resource->GetPeakCreditsPerSlot();

                $creditsRequired += $resourceCredits * $instanceSlots;
                $creditsRequired += $peakCredits * $peakSlots;
            }
            $instance->SetCreditsRequired($creditsRequired);

            $slots += $instanceSlots;
        }

        return $slots;
    }

    public function GetCreditsConsumed()
    {
        return 0;
    }
}
