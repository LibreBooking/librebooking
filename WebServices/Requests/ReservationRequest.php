<?php

require_once(ROOT_DIR . 'WebServices/Requests/ReservationAccessoryRequest.php');
require_once(ROOT_DIR . 'WebServices/Requests/CustomAttributes/AttributeValueRequest.php');
require_once(ROOT_DIR . 'WebServices/Responses/RecurrenceRequestResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/ReminderRequestResponse.php');
require_once(ROOT_DIR . 'WebServices/Responses/Reservation/ReservationRetryParameterRequestResponse.php');

class ReservationRequest
{
    /**
     * @var array|ReservationAccessoryRequest[]
     */
    public $accessories = [];
    /**
     * @var array|AttributeValueRequest[]
     */
    public $customAttributes = [];
    public $description;
    public $endDateTime;
    /**
     * @var array|int[]
     */
    public $invitees = [];
    /**
     * @var array|int[]
     */
    public $participants = [];
    /**
     * @var array|string[]
     */
    public $participatingGuests = [];
    /**
     * @var array|string[]
     */
    public $invitedGuests = [];
    /**
     * @var RecurrenceRequestResponse|null
     */
    public $recurrenceRule;
    public $resourceId;
    /**
     * @var array|int[]
     */
    public $resources = [];
    public $startDateTime;
    public $title;
    public $userId;
    /**
     * @var ReminderRequestResponse|null
     */
    public $startReminder;
    /**
     * @var ReminderRequestResponse|null
     */
    public $endReminder;

    /**
     * @var bool
     */
    public $allowParticipation;

    /**
     * @var ReservationRetryParameterRequestResponse[]|null
     */
    public $retryParameters;
    /**
     * @var bool
     */
    public $termsAccepted;

    public static function Example()
    {
        $date = Date::Now()->ToIso();
        $request = new ReservationRequest();
        $request->accessories = [new ReservationAccessoryRequest(1, 2)];
        $request->customAttributes = [new AttributeValueRequest(2, 'some value')];
        $request->description = 'reservation description';
        $request->endDateTime = $date;
        $request->invitees = [1, 2, 3];
        $request->participants = [1, 2];
        $request->participatingGuests = ['participating.guest@email.com'];
        $request->invitedGuests = ['invited.guest@email.com'];
        $request->recurrenceRule = RecurrenceRequestResponse::Example();
        $request->resourceId = 1;
        $request->resources = [2, 3];
        $request->startDateTime = $date;
        $request->title = 'reservation title';
        $request->userId = 1;
        $request->startReminder = ReminderRequestResponse::Example();
        $request->allowParticipation = true;
        $request->retryParameters = [ReservationRetryParameterRequestResponse::Example()];
        $request->termsAccepted = true;

        return $request;
    }
}
