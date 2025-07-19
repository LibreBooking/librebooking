<?php

require_once(ROOT_DIR . 'Pages/Reservation/ExistingReservationPage.php');

class FakeExistingReservationPage extends FakePageBase implements IExistingReservationPage
{
    public $_CheckInRequired = false;
    public $_CheckOutRequired = false;
    public $_AutoReleaseMinutes = null;

    public function BindViewableResourceReservations($resourceIds) { }

    public function GetReferenceNumber()
    {
    }

    public function SetAdditionalResources($additionalResourceIds)
    {
    }

    public function SetTitle($title)
    {
    }

    public function SetDescription($description)
    {
    }

    public function SetRepeatType($repeatType)
    {
    }

    public function SetRepeatInterval($repeatInterval)
    {
    }

    public function SetRepeatMonthlyType($repeatMonthlyType)
    {
    }

    public function SetRepeatWeekdays($repeatWeekdays)
    {
    }

    public function SetReferenceNumber($referenceNumber)
    {
    }

    public function SetReservationId($reservationId)
    {
    }

    public function SetSeriesId($seriesId)
    {
    }

    public function SetIsRecurring($isRecurring)
    {
    }

    public function SetIsEditable($canBeEdited)
    {
    }

    public function SetIsApprovable($canBeApproved)
    {
    }

    public function SetCurrentUserParticipating($amIParticipating)
    {
    }

    public function SetCurrentUserInvited($amIInvited)
    {
    }

    public function SetStartReminder($reminderValue, $reminderInterval)
    {
    }

    public function SetEndReminder($reminderValue, $reminderInterval)
    {
    }

    public function SetCanAlterParticipation($canAlterParticipation)
    {
    }

    public function SetCheckInRequired($isCheckInRequired)
    {
        $this->_CheckInRequired = $isCheckInRequired;
    }

    public function SetCheckOutRequired($isCheckOutRequired)
    {
        $this->_CheckOutRequired = $isCheckOutRequired;
    }

    public function SetAutoReleaseMinutes($autoReleaseMinutes)
    {
        $this->_AutoReleaseMinutes = $autoReleaseMinutes;
    }

    public function PageLoad()
    {
    }

    public function Redirect($url)
    {
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
    }

    public function IsPostBack()
    {
    }

    public function IsValid()
    {
    }

    public function GetLastPage($defaultPage = '')
    {
    }

    public function RegisterValidator($validatorId, $validator)
    {
    }

    public function EnforceCSRFCheck()
    {
    }

    public function BindPeriods($startPeriods, $endPeriods, $lockPeriods)
    {
    }

    public function BindAvailableResources($resources)
    {
    }

    public function BindAvailableAccessories($accessories)
    {
    }

    public function BindResourceGroups($groups)
    {
    }

    public function SetSelectedStart(SchedulePeriod $selectedStart, Date $startDate)
    {
    }

    public function SetSelectedEnd(SchedulePeriod $selectedEnd, Date $endDate)
    {
    }

    public function SetRepeatTerminationDate($repeatTerminationDate)
    {
    }

    public function SetReservationUser(UserDto $user)
    {
    }

    public function SetReservationResource($resource)
    {
    }

    public function SetScheduleId($scheduleId)
    {
    }

    public function SetParticipants($participants)
    {
    }

    public function SetInvitees($invitees)
    {
    }

    public function SetAccessories($accessories)
    {
    }

    public function SetAttachments($attachments)
    {
    }

    public function SetCanChangeUser($canChangeUser)
    {
    }

    public function ShowAdditionalResources($canShowAdditionalResources)
    {
    }

    public function ShowUserDetails($canShowUserDetails)
    {
    }

    public function SetShowParticipation($shouldShow)
    {
    }

    public function ShowReservationDetails($showReservationDetails)
    {
    }

    public function HideRecurrence($isHidden)
    {
    }

    public function SetAllowParticipantsToJoin($allowParticipation)
    {
    }

    public function SetParticipatingGuests($participatingGuests)
    {
    }

    public function SetInvitedGuests($invitedGuests)
    {
    }

    public function SetRequiresApproval($requiresApproval)
    {
    }

    public function SetAvailability(DateRange $availability)
    {
    }

    public function SetFirstWeekday($weekday)
    {
    }

    public function MakeUnavailable()
    {
        // TODO: Implement MakeUnavailable() method.
    }

    public function IsUnavailable()
    {
        // TODO: Implement IsUnavailable() method.
        return null;
    }

    public function SetTerms($termsOfService)
    {
        // TODO: Implement SetTerms() method.
    }

    public function SetTermsAccepted($accepted)
    {
        // TODO: Implement SetTermsAccepted() method.
    }

    public function SetCustomRepeatDates($customRepeatDates)
    {
        // TODO: Implement SetCustomRepeatDates() method.
    }

    public function SetMaximumResources($maximum)
    {
        // TODO: Implement SetMaximumResources() method.
    }
}
