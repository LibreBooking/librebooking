<?php

class RouteParamsKeys
{
    private function __construct()
    {
    }

    public const GUEST_RESERVATION_FROM_SCHEDULE = [
        QueryStringKeys::REPORT_ID => ParamsValidatorKeys::NUMERICAL,
        QueryStringKeys::SCHEDULE_ID => ParamsValidatorKeys::NUMERICAL,
        QueryStringKeys::RESERVATION_DATE => ParamsValidatorKeys::DATE,
        QueryStringKeys::START_DATE => ParamsValidatorKeys::COMPLEX_DATETIME,
        QueryStringKeys::END_DATE => ParamsValidatorKeys::COMPLEX_DATETIME
    ];

    public const GUEST_RESERVATION_FROM_CALENDAR = [
        QueryStringKeys::SCHEDULE_ID => ParamsValidatorKeys::EXISTS,
        QueryStringKeys::REPORT_ID => ParamsValidatorKeys::EXISTS,
        QueryStringKeys::START_DATE => ParamsValidatorKeys::SIMPLE_DATETIME,
        QueryStringKeys::END_DATE => ParamsValidatorKeys::SIMPLE_DATETIME,
        QueryStringKeys::REDIRECT => ParamsValidatorKeys::REDIRECT_GUEST_RESERVATION
    ];

    public const VIEW_SCHEDULE = [
        FormKeys::PARTICIPANT_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        FormKeys::USER_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        QueryStringKeys::SCHEDULE_ID => ParamsValidatorKeys::NUMERICAL,
        QueryStringKeys::START_DATE => ParamsValidatorKeys::DATE,
        'clearFilter' => ParamsValidatorKeys::NUMERICAL,
        QueryStringKeys::START_DATES => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::SIMPLE_DATE],
        FormKeys::RESOURCE_TYPE_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        FormKeys::MAX_PARTICIPANTS => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        FormKeys::SUBMIT => ParamsValidatorKeys::BOOLEAN,
        QueryStringKeys::DATA_REQUEST => [ParamsValidatorKeys::MATCH => ['reservations']]
    ];

    public const VIEW_CALENDAR = [
        QueryStringKeys::REPORT_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        QueryStringKeys::SCHEDULE_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL],
        QueryStringKeys::START => ParamsValidatorKeys::SIMPLE_DATE,
        //QueryStringKeys::END => ParamsValidatorKeys::SIMPLE_DATE,
        QueryStringKeys::GROUP_ID => [ParamsValidatorKeys::EXISTS, ParamsValidatorKeys::NUMERICAL]
    ];
}
