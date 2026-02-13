<?php

require_once(ROOT_DIR . 'Pages/Ajax/ReservationSavePage.php');

class FakeReservationSavePage implements IReservationSavePage
{
    public $userId = 110;
    public $resourceId = 120;
    public $scheduleId = 123;
    public $title = 'title';
    public $description = 'description';
    public $startDate = '2010-01-01';
    public $endDate = '2010-01-02';
    public $startTime = '05:30';
    public $endTime = '04:00';
    public $resourceIds = [11, 22, 120];
    public $repeatType = RepeatType::Daily;
    public $repeatInterval = 2;
    public $repeatWeekdays = [0, 1, 2];
    public $repeatMonthlyType = RepeatMonthlyType::DayOfMonth;
    public $repeatTerminationDate = '2010-10-10';
    public $saveSuccessful = false;
    public $errors = [];
    public $warnings = [];
    public $referenceNumber;
    public $requiresApproval;
    public $participants = [10, 20, 40];
    public $invitees = [11, 21, 41];
    public $accessories = [];
    public $attributes = [];
    public $attachment;
    public $startReminderValue = '15';
    public $startReminderInterval = ReservationReminderInterval::Minutes;
    public $endReminderValue = '1';
    public $endReminderInterval = ReservationReminderInterval::Hours;
    public $hasEndReminder = true;
    public $allowParticipation;
    public $canBeRetried = false;
    public $retryParameters = [];
    public $retryMessages = [];
    public $participatingGuests = [];
    public $invitedGuests = [];
    public $canJoinWaitlist = false;
    public $repeatCustomDates = [];

    public function __construct()
    {
        $now = Date::Now();
        $this->startDate = $now->AddDays(5)->Format('Y-m-d');
        $this->endDate = $now->AddDays(6)->Format('Y-m-d');
        $this->repeatTerminationDate = $now->AddDays(60)->Format('Y-m-d');
        $this->accessories = [new FakeAccessoryFormElement(1, 2, 'accessoryname')];
        $this->attributes = [new AttributeFormElement(1, 'something')];
        $this->attachment = new FakeUploadedFile();
    }

    public function GetUserId()
    {
        return $this->userId;
    }

    public function GetResourceId()
    {
        return $this->resourceId;
    }

    public function GetScheduleId()
    {
        return $this->scheduleId;
    }

    public function GetTitle()
    {
        return $this->title;
    }

    public function GetDescription()
    {
        return $this->description;
    }

    public function GetStartDate()
    {
        return $this->startDate;
    }

    public function GetEndDate()
    {
        return $this->endDate;
    }

    public function GetStartTime()
    {
        return $this->startTime;
    }

    public function GetEndTime()
    {
        return $this->endTime;
    }

    public function GetResources()
    {
        return $this->resourceIds;
    }

    public function GetRepeatType()
    {
        return $this->repeatType;
    }

    public function GetRepeatInterval()
    {
        return $this->repeatInterval;
    }

    public function GetRepeatWeekdays()
    {
        return $this->repeatWeekdays;
    }

    public function GetRepeatMonthlyType()
    {
        return $this->repeatMonthlyType;
    }

    public function GetRepeatTerminationDate()
    {
        return $this->repeatTerminationDate;
    }

    public function SetSaveSuccessfulMessage($succeeded)
    {
        $this->saveSuccessful = $succeeded;
    }

    public function SetReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
    }

    public function SetErrors($errors)
    {
        $this->errors = $errors;
    }

    public function SetWarnings($warnings)
    {
        $this->warnings = $warnings;
    }

    public function GetParticipants()
    {
        return $this->participants;
    }

    public function GetInvitees()
    {
        return $this->invitees;
    }

    public function GetAttachments()
    {
        return [$this->attachment];
    }

    /**
     * @return AccessoryFormElement[]
     */
    public function GetAccessories()
    {
        return $this->accessories;
    }

    /**
     * @return AttributeFormElement[]|array
     */
    public function GetAttributes()
    {
        return $this->attributes;
    }

    public function GetStartReminderValue()
    {
        return $this->startReminderValue;
    }

    public function GetStartReminderInterval()
    {
        return $this->startReminderInterval;
    }

    public function GetEndReminderValue()
    {
        return $this->endReminderValue;
    }

    public function GetEndReminderInterval()
    {
        return $this->endReminderInterval;
    }

    public function HasStartReminder()
    {
        return true;
    }

    public function HasEndReminder()
    {
        return $this->hasEndReminder;
    }

    /**
     * @param bool $requiresApproval
     */
    public function SetRequiresApproval($requiresApproval)
    {
        $this->requiresApproval = $requiresApproval;
    }

    /**
     * @return bool
     */
    public function GetAllowParticipation()
    {
        return $this->allowParticipation;
    }

    /**
     * @param bool $canBeRetried
     */
    public function SetCanBeRetried($canBeRetried)
    {
        $this->canBeRetried = $canBeRetried;
    }

    /**
     * @param ReservationRetryParameter[] $retryParameters
     */
    public function SetRetryParameters($retryParameters)
    {
        $this->retryParameters = $retryParameters;
    }

    /**
     * @return ReservationRetryParameter[]
     */
    public function GetRetryParameters()
    {
        return $this->retryParameters;
    }

    /**
     * @param array|string[] $messages
     */
    public function SetRetryMessages($messages)
    {
        $this->retryMessages = $messages;
    }

    /**
     * @return string[]
     */
    public function GetParticipatingGuests()
    {
        return $this->participatingGuests;
    }

    /**
     * @return string[]
     */
    public function GetInvitedGuests()
    {
        return $this->invitedGuests;
    }

    /**
     * @param bool $canJoinWaitlist
     */
    public function SetCanJoinWaitList($canJoinWaitlist)
    {
        $this->canJoinWaitlist = $canJoinWaitlist;
    }

    public function GetTermsOfServiceAcknowledgement()
    {
        return true;
    }

    public function GetRepeatCustomDates()
    {
        return $this->repeatCustomDates;
    }
}

class FakeAccessoryFormElement extends AccessoryFormElement
{
    public function __construct($id, $quantity, $name)
    {
        $this->Id = $id;
        $this->Quantity = $quantity;
        $this->Name = $name;
    }
}
