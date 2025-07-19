<?php

require_once(ROOT_DIR . 'lib/Database/SqlCommand.php');

class AddAccessoryCommand extends SqlCommand
{
    public function __construct($accessoryName, $quantityAvailable)
    {
        parent::__construct(Queries::ADD_ACCESSORY);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_NAME, $accessoryName));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_QUANTITY, $quantityAvailable));
    }
}

class AddAccessoryResourceCommand extends SqlCommand
{
    public function __construct($accessoryId, $resourceId, $minimumQuantity, $maximumQuantity)
    {
        parent::__construct(Queries::ADD_ACCESSORY_RESOURCE);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_MIN_QUANTITY, $minimumQuantity));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_MAX_QUANTITY, $maximumQuantity));
    }
}

class AddAccountActivationCommand extends SqlCommand
{
    public function __construct($userId, $activationCode)
    {
        parent::__construct(Queries::ADD_ACCOUNT_ACTIVATION);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::ACTIVATION_CODE, $activationCode));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()
            ->ToDatabase()));
    }
}

class AddAnnouncementCommand extends SqlCommand
{
    public function __construct($text, Date $start, Date $end, $priority, $displayPage)
    {
        parent::__construct(Queries::ADD_ANNOUNCEMENT);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_TEXT, $text));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $end->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_PRIORITY, $priority));
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_DISPLAY_PAGE, $displayPage));
    }
}

class AddAnnouncementGroupCommand extends SqlCommand
{
    public function __construct($announcementId, $groupId)
    {
        parent::__construct(Queries::ADD_ANNOUNCEMENT_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_ID, $announcementId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class AddAnnouncementResourceCommand extends SqlCommand
{
    public function __construct($announcementId, $resourceId)
    {
        parent::__construct(Queries::ADD_ANNOUNCEMENT_RESOURCE);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_ID, $announcementId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class AddAttributeCommand extends SqlCommand
{
    public function __construct(
        $label,
        $type,
        $category,
        $regex,
        $required,
        $possibleValues,
        $sortOrder,
        $adminOnly,
        $secondaryCategory,
        $secondaryEntityIds,
        $isPrivate
    ) {
        parent::__construct(Queries::ADD_ATTRIBUTE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_LABEL, $label));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_TYPE, (int)$type));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, (int)$category));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_REGEX, $regex));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_REQUIRED, (int)$required));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_POSSIBLE_VALUES, $possibleValues));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SORT_ORDER, $sortOrder));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ADMIN_ONLY, (int)$adminOnly));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SECONDARY_CATEGORY, $secondaryCategory));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SECONDARY_ENTITY_IDS, implode(',', $secondaryEntityIds)));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_IS_PRIVATE, (int)$isPrivate));
    }
}

class AddAttributeEntityCommand extends SqlCommand
{
    public function __construct($attributeId, $entityId)
    {
        parent::__construct(Queries::ADD_ATTRIBUTE_ENTITY);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_ID, $entityId));
    }
}

class AddAttributeValueCommand extends SqlCommand
{
    public function __construct($attributeId, $value, $entityId, $attributeCategory)
    {
        parent::__construct(Queries::ADD_ATTRIBUTE_VALUE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_VALUE, $value));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_ID, $entityId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, $attributeCategory));
    }
}

class AddBlackoutCommand extends SqlCommand
{
    public function __construct($userId, $title, $repeatTypeId, $repeatTypeConfiguration)
    {
        parent::__construct(Queries::ADD_BLACKOUT_SERIES);
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::TITLE, $title));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_TYPE, $repeatTypeId));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_OPTIONS, $repeatTypeConfiguration));
    }
}

class AddBlackoutInstanceCommand extends SqlCommand
{
    public function __construct($blackoutSeriesId, Date $startDate, Date $endDate)
    {
        parent::__construct(Queries::ADD_BLACKOUT_INSTANCE);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $blackoutSeriesId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
    }
}

class AddBlackoutResourceCommand extends SqlCommand
{
    public function __construct($blackoutSeriesId, $resourceId)
    {
        parent::__construct(Queries::ADD_BLACKOUT_RESOURCE);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $blackoutSeriesId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class AddEmailPreferenceCommand extends SqlCommand
{
    public function __construct($userId, $eventCategory, $eventType)
    {
        parent::__construct(Queries::ADD_EMAIL_PREFERENCE);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::EVENT_CATEGORY, $eventCategory));
        $this->AddParameter(new Parameter(ParameterNames::EVENT_TYPE, $eventType));
    }
}

class AddGroupCommand extends SqlCommand
{
    public function __construct($groupName, $isDefault)
    {
        parent::__construct(Queries::ADD_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_NAME, $groupName));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ISDEFAULT, intval($isDefault)));
    }
}

class AddGroupResourcePermission extends SqlCommand
{
    public function __construct($groupId, $resourceId, $permissionType)
    {
        parent::__construct(Queries::ADD_GROUP_RESOURCE_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::PERMISSION_TYPE, $permissionType));
    }
}

class AddGroupRoleCommand extends SqlCommand
{
    public function __construct($groupId, $roleId)
    {
        parent::__construct(Queries::ADD_GROUP_ROLE);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::ROLE_ID, $roleId));
    }
}

class AdjustUserCreditsCommand extends SqlCommand
{
    public function __construct($userId, $creditsToDeduct, $note)
    {
        parent::__construct(Queries::ADJUST_USER_CREDITS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $creditsToDeduct));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_NOTE, $note));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
    }

    public function IsMultiQuery()
    {
        return true;
    }
}

class AddLayoutCommand extends SqlCommand
{
    public function __construct($timezone, $layoutType)
    {
        parent::__construct(Queries::ADD_LAYOUT);
        $this->AddParameter(new Parameter(ParameterNames::TIMEZONE_NAME, $timezone));
        $this->AddParameter(new Parameter(ParameterNames::LAYOUT_TYPE, $layoutType));
    }
}

class AddLayoutTimeCommand extends SqlCommand
{
    public function __construct($layoutId, Time $start, Time $end, $periodType, $label = null, $dayOfWeek = null)
    {
        parent::__construct(Queries::ADD_LAYOUT_TIME);
        $this->AddParameter(new Parameter(ParameterNames::LAYOUT_ID, $layoutId));
        $this->AddParameter(new Parameter(ParameterNames::START_TIME, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_TIME, $end->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::PERIOD_AVAILABILITY_TYPE, $periodType));
        $this->AddParameter(new Parameter(ParameterNames::PERIOD_LABEL, $label));
        $this->AddParameter(new Parameter(ParameterNames::PERIOD_DAY_OF_WEEK, $dayOfWeek));
    }
}

class AddCustomLayoutPeriodCommand extends SqlCommand
{
    public function __construct($scheduleId, Date $start, Date $end)
    {
        parent::__construct(Queries::ADD_CUSTOM_LAYOUT_SLOT);
        $this->AddParameter(new Parameter(ParameterNames::START_TIME, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_TIME, $end->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class AddPaymentConfigurationCommand extends SqlCommand
{
    public function __construct($creditCount, $creditCost, $creditCurrency)
    {
        parent::__construct(Queries::ADD_PAYMENT_CONFIGURATION);
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $creditCount));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COST, $creditCost));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_CURRENCY, $creditCurrency));
    }
}

class AddPaymentGatewaySettingCommand extends SqlCommand
{
    public function __construct($gatewayType, $settingName, $settingValue)
    {
        parent::__construct(Queries::ADD_PAYMENT_GATEWAY_SETTING);
        $this->AddParameter(new Parameter(ParameterNames::GATEWAY_TYPE, $gatewayType));
        $this->AddParameter(new Parameter(ParameterNames::GATEWAY_SETTING_NAME, $settingName));
        $this->AddParameter(new Parameter(ParameterNames::GATEWAY_SETTING_VALUE, $settingValue));
    }
}

class AddPaymentTransactionLogCommand extends SqlCommand
{
    /**
     * @param string $userId
     * @param string $status
     * @param string $invoiceNumber
     * @param int $transactionId
     * @param float $totalAmount
     * @param float $transactionFee
     * @param string $currency
     * @param string $transactionHref
     * @param string $refundHref
     * @param Date $dateCreated
     * @param string $gatewayDateCreated
     * @param string $gatewayResponse
     */
    public function __construct(
        $userId,
        $status,
        $invoiceNumber,
        $transactionId,
        $totalAmount,
        $transactionFee,
        $currency,
        $transactionHref,
        $refundHref,
        $dateCreated,
        $gatewayDateCreated,
        $gatewayName,
        $gatewayResponse
    ) {
        parent::__construct(Queries::ADD_PAYMENT_TRANSACTION_LOG);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_STATUS, $status));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_INVOICE_NUMBER, $invoiceNumber));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TRANSACTION_ID, $transactionId));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TOTAL, $totalAmount));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TRANSACTION_FEE, $transactionFee));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_CURRENCY, $currency));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TRANSACTION_HREF, $transactionHref));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_REFUND_HREF, $refundHref));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_DATE_CREATED, $dateCreated->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_GATEWAY_DATE_CREATED, $gatewayDateCreated));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_GATEWAY_NAME, $gatewayName));
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_GATEWAY_RESPONSE, $gatewayResponse));
    }
}

class AddPeakTimesCommand extends SqlCommand
{
    /**
     * @param int $scheduleId
     * @param bool $allDay
     * @param string $beginTime
     * @param string $endTime
     * @param bool $everyDay
     * @param string $peakDays
     * @param bool $allYear
     * @param int $beginDay
     * @param int $beginMonth
     * @param int $endDay
     * @param int $endMonth
     */
    public function __construct($scheduleId, $allDay, $beginTime, $endTime, $everyDay, $peakDays, $allYear, $beginDay, $beginMonth, $endDay, $endMonth)
    {
        parent::__construct(Queries::ADD_PEAK_TIMES);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_ALL_DAY, (int)$allDay));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_START_TIME, $beginTime));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_END_TIME, $endTime));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_EVERY_DAY, (int)$everyDay));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_DAYS, $peakDays));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_ALL_YEAR, (int)$allYear));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_BEGIN_DAY, $beginDay));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_BEGIN_MONTH, $beginMonth));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_END_DAY, $endDay));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_TIMES_END_MONTH, $endMonth));
    }
}

class AddQuotaCommand extends SqlCommand
{
    public function __construct($duration, $limit, $unit, $resourceId, $groupId, $scheduleIds, $enforcedStartTime, $enforcedEndTime, $enforcedDays, $scope)
    {
        parent::__construct(Queries::ADD_QUOTA);
        $this->AddParameter(new Parameter(ParameterNames::QUOTA_DURATION, $duration));
        $this->AddParameter(new Parameter(ParameterNames::QUOTA_LIMIT, $limit));
        $this->AddParameter(new Parameter(ParameterNames::QUOTA_UNIT, $unit));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleIds));
        $this->AddParameter(new Parameter(ParameterNames::START_TIME, is_null($enforcedStartTime) ? null : $enforcedStartTime));
        $this->AddParameter(new Parameter(ParameterNames::END_TIME, is_null($enforcedEndTime) ? null : $enforcedEndTime));
        $this->AddParameter(new Parameter(ParameterNames::ENFORCED_DAYS, empty($enforcedDays) ? null : implode(',', $enforcedDays)));
        $this->AddParameter(new Parameter(ParameterNames::QUOTA_SCOPE, $scope));
    }
}

class AddRefundTransactionLogCommand extends SqlCommand
{
    /**
     * @param string $paymentTransactionLogId
     * @param string $status
     * @param int $transactionId
     * @param float $totalRefundAmount
     * @param float $paymentRefundAmount
     * @param float $feeRefundAmount
     * @param string $transactionHref
     * @param Date $dateCreated
     * @param string $gatewayDateCreated
     * @param string $refundResponse
     */
    public function __construct(
        $paymentTransactionLogId,
        $status,
        $transactionId,
        $totalRefundAmount,
        $paymentRefundAmount,
        $feeRefundAmount,
        $transactionHref,
        $dateCreated,
        $gatewayDateCreated,
        $refundResponse
    ) {
        parent::__construct(Queries::ADD_REFUND_TRANSACTION_LOG);
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TRANSACTION_LOG_ID, $paymentTransactionLogId));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_STATUS, $status));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_TRANSACTION_ID, $transactionId));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_TOTAL_AMOUNT, $totalRefundAmount));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_PAYMENT_AMOUNT, $paymentRefundAmount));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_FEE_AMOUNT, $feeRefundAmount));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_TRANSACTION_HREF, $transactionHref));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_DATE_CREATED, $dateCreated->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_GATEWAY_DATE_CREATED, $gatewayDateCreated));
        $this->AddParameter(new Parameter(ParameterNames::REFUND_GATEWAY_RESPONSE, $refundResponse));
    }
}

class AddReservationSeriesCommand extends SqlCommand
{
    public function __construct(
        Date $dateCreated,
        $title,
        $description,
        $repeatType,
        $repeatOptions,
        $reservationTypeIds,
        $statusId,
        $ownerId,
        $allowParticipation,
        $termsAcceptanceDate,
        $lastActionBy
    ) {
        parent::__construct(Queries::ADD_RESERVATION_SERIES);

        if ($termsAcceptanceDate == null) {
            $termsAcceptanceDate = new NullDate();
        }

        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, $dateCreated->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::TITLE, $title));
        $this->AddParameter(new Parameter(ParameterNames::DESCRIPTION, $description));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_TYPE, $repeatType));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_OPTIONS, $repeatOptions));
        $this->AddParameter(new Parameter(ParameterNames::TYPE_ID, $reservationTypeIds));
        $this->AddParameter(new Parameter(ParameterNames::STATUS_ID, $statusId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $ownerId));
        $this->AddParameter(new Parameter(ParameterNames::ALLOW_PARTICIPATION, (int)$allowParticipation));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_ACCEPTANCE_DATE, $termsAcceptanceDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::LAST_ACTION_BY, $lastActionBy));
    }
}

class AddReservationAccessoryCommand extends SqlCommand
{
    public function __construct($accessoryId, $quantity, $seriesId)
    {
        parent::__construct(Queries::ADD_RESERVATION_ACCESSORY);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_QUANTITY, $quantity));
    }
}

class AddReservationAttachmentCommand extends SqlCommand
{
    public function __construct($fileName, $fileType, $fileSize, $fileExtension, $seriesId)
    {
        parent::__construct(Queries::ADD_RESERVATION_ATTACHMENT);

        $this->AddParameter(new Parameter(ParameterNames::FILE_NAME, $fileName));
        $this->AddParameter(new Parameter(ParameterNames::FILE_TYPE, $fileType));
        $this->AddParameter(new Parameter(ParameterNames::FILE_SIZE, $fileSize));
        $this->AddParameter(new Parameter(ParameterNames::FILE_EXTENSION, $fileExtension));
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class AddReservationColorRuleCommand extends SqlCommand
{
    public function __construct($attributeType, $color, $comparisonType, $requiredValue, $attributeId)
    {
        parent::__construct(Queries::ADD_RESERVATION_COLOR_RULE);

        $this->AddParameter(new Parameter(ParameterNames::COLOR_ATTRIBUTE_TYPE, $attributeType));
        $this->AddParameter(new Parameter(ParameterNames::COLOR, $color));
        $this->AddParameter(new Parameter(ParameterNames::COMPARISON_TYPE, $comparisonType));
        $this->AddParameter(new Parameter(ParameterNames::COLOR_REQUIRED_VALUE, $requiredValue));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
    }
}

class AddReservationReminderCommand extends SqlCommand
{
    public function __construct($seriesId, $minutesPrior, $reminderType)
    {
        parent::__construct(Queries::ADD_RESERVATION_REMINDER);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_MINUTES_PRIOR, $minutesPrior));
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_TYPE, $reminderType));
    }
}

class AddReservationResourceCommand extends SqlCommand
{
    public function __construct($seriesId, $resourceId, $resourceLevelId)
    {
        parent::__construct(Queries::ADD_RESERVATION_RESOURCE);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_LEVEL_ID, $resourceLevelId));
    }
}

class AddReservationCommand extends SqlCommand
{
    public function __construct(
        Date $startDate,
        Date $endDateUtc,
        $referenceNumber,
        $seriesId,
        $credits
    ) {
        parent::__construct(Queries::ADD_RESERVATION);
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDateUtc->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $credits));
    }
}

class AddReservationGuestCommand extends SqlCommand
{
    public function __construct($instanceId, $guestEmail, $levelId)
    {
        parent::__construct(Queries::ADD_RESERVATION_GUEST);

        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $guestEmail));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $levelId));
    }
}

class AddReservationUserCommand extends SqlCommand
{
    public function __construct($instanceId, $userId, $levelId)
    {
        parent::__construct(Queries::ADD_RESERVATION_USER);

        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $levelId));
    }
}

class AddReservationWaitlistCommand extends SqlCommand
{
    /**
     * @param int $userId
     * @param Date $startDate
     * @param Date $endDate
     * @param int $resourceId
     */
    public function __construct($userId, $startDate, $endDate, $resourceId)
    {
        parent::__construct(Queries::ADD_RESERVATION_WAITLIST);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class AddResourceCommand extends SqlCommand
{
    public function __construct(
        $name,
        $schedule_id,
        $autoassign = 1,
        $admin_group_id = null,
        $location = null,
        $contact_info = null,
        $description = null,
        $notes = null,
        $status_id = 1,
        $min_duration = null,
        $min_increment = null,
        $max_duration = null,
        $unit_cost = null,
        $requires_approval = 0,
        $allow_multiday = 1,
        $max_participants = null,
        $min_notice_time_add = null,
        $max_notice_time = null
    ) {
        parent::__construct(Queries::ADD_RESOURCE);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $schedule_id));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_LOCATION, $location));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_CONTACT, $contact_info));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_DESCRIPTION, $description));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_NOTES, $notes));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS, $status_id));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINDURATION, $min_duration));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MININCREMENT, $min_increment));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAXDURATION, $max_duration));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_COST, $unit_cost));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_AUTOASSIGN, (int)$autoassign));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_REQUIRES_APPROVAL, $requires_approval));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ALLOW_MULTIDAY, $allow_multiday));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAX_PARTICIPANTS, $max_participants));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINNOTICE_ADD, $min_notice_time_add));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAXNOTICE, $max_notice_time));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ADMIN_ID, $admin_group_id));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
    }
}

class AddResourceGroupCommand extends SqlCommand
{
    public function __construct($groupName, $parentId = null)
    {
        parent::__construct(Queries::ADD_RESOURCE_GROUP);

        $this->AddParameter(new Parameter(ParameterNames::GROUP_NAME, $groupName));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, empty($parentId) ? null : $parentId));
    }
}

class AddResourceStatusReasonCommand extends SqlCommand
{
    public function __construct($statusId, $reasonDescription)
    {
        parent::__construct(Queries::ADD_RESOURCE_STATUS_REASON);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS, $statusId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS_REASON_DESCRIPTION, $reasonDescription));
    }
}

class AddResourceToGroupCommand extends SqlCommand
{
    public function __construct($resourceId, $groupId)
    {
        parent::__construct(Queries::ADD_RESOURCE_TO_GROUP);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, $groupId));
    }
}

class AddResourceTypeCommand extends SqlCommand
{
    public function __construct($name, $description)
    {
        parent::__construct(Queries::ADD_RESOURCE_TYPE);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_DESCRIPTION, $description));
    }
}

class AddResourceImageCommand extends SqlCommand
{
    public function __construct($resourceId, $image)
    {
        parent::__construct(Queries::ADD_RESOURCE_IMAGE);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_IMAGE_NAME, $image));
    }
}

class AddSavedReportCommand extends SqlCommand
{
    public function __construct($reportName, $userId, Date $dateCreated, $serializedCriteria)
    {
        parent::__construct(Queries::ADD_SAVED_REPORT);
        $this->AddParameter(new Parameter(ParameterNames::REPORT_NAME, $reportName));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, $dateCreated->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::REPORT_DETAILS, $serializedCriteria));
    }
}

class AddScheduleCommand extends SqlCommand
{
    public function __construct($scheduleName, $isDefault, $weekdayStart, $daysVisible, $layoutId, $adminGroupIds = null)
    {
        parent::__construct(Queries::ADD_SCHEDULE);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_NAME, $scheduleName));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ISDEFAULT, (int)$isDefault));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_WEEKDAYSTART, $weekdayStart));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_DAYSVISIBLE, $daysVisible));
        $this->AddParameter(new Parameter(ParameterNames::LAYOUT_ID, $layoutId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ADMIN_ID, $adminGroupIds));
    }
}

class AddTermsOfServiceCommand extends SqlCommand
{
    public function __construct($termsText, $termsUrl, $filename, $applicability)
    {
        parent::__construct(Queries::ADD_TERMS_OF_SERVICE);
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_TEXT, $termsText));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_URL, $termsUrl));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_FILENAME, $filename));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_APPLICABILITY, $applicability));
    }
}

class AddUserGroupCommand extends SqlCommand
{
    public function __construct($userId, $groupId)
    {
        parent::__construct(Queries::ADD_USER_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class AddUserResourcePermission extends SqlCommand
{
    public function __construct($userId, $resourceId, $permissionType)
    {
        parent::__construct(Queries::ADD_USER_RESOURCE_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::PERMISSION_TYPE, $permissionType));
    }
}

class AddUserToDefaultGroupsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::ADD_USER_TO_DEFAULT_GROUPS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class AddUserSessionCommand extends SqlCommand
{
    public function __construct($userId, $token, Date $insertTime, $serializedSession)
    {
        parent::__construct(Queries::ADD_USER_SESSION);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::SESSION_TOKEN, $token));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, $insertTime->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_SESSION, $serializedSession));
    }
}

class AuthorizationCommand extends SqlCommand
{
    public function __construct($username)
    {
        parent::__construct(Queries::VALIDATE_USER);
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, strtolower($username)));
    }
}

class AutoAssignPermissionsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::AUTO_ASSIGN_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class AutoAssignGuestPermissionsCommand extends SqlCommand
{
    public function __construct($userId, $scheduleId)
    {
        parent::__construct(Queries::AUTO_ASSIGN_GUEST_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class AutoAssignResourcePermissionsCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::AUTO_ASSIGN_RESOURCE_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class AutoAssignClearResourcePermissionsCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::AUTO_ASSIGN_CLEAR_RESOURCE_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class CheckEmailCommand extends SqlCommand
{
    public function __construct($emailAddress)
    {
        parent::__construct(Queries::CHECK_EMAIL);
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, strtolower($emailAddress)));
    }
}

class CheckUserExistenceCommand extends SqlCommand
{
    public function __construct($username, $emailAddress)
    {
        parent::__construct(Queries::CHECK_USER_EXISTENCE);
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, $username));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $emailAddress));
    }
}

class CheckUsernameCommand extends SqlCommand
{
    public function __construct($username)
    {
        parent::__construct(Queries::CHECK_USERNAME);
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, $username));
    }
}

class CookieLoginCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::COOKIE_LOGIN);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class DeleteAccessoryCommand extends SqlCommand
{
    public function __construct($accessoryId)
    {
        parent::__construct(Queries::DELETE_ACCESSORY);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
    }
}

class DeleteAcccessoryResourcesCommand extends SqlCommand
{
    public function __construct($accessoryId)
    {
        parent::__construct(Queries::DELETE_ACCESSORY_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
    }
}

class DeleteAttributeCommand extends SqlCommand
{
    public function __construct($attributeId)
    {
        parent::__construct(Queries::DELETE_ATTRIBUTE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
    }
}

class DeleteAttributeValuesCommand extends SqlCommand
{
    public function __construct($attributeId)
    {
        parent::__construct(Queries::DELETE_ATTRIBUTE_VALUES);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
    }
}

class DeleteAttributeColorRulesCommand extends SqlCommand
{
    public function __construct($attributeId)
    {
        parent::__construct(Queries::DELETE_ATTRIBUTE_COLOR_RULES);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
    }
}

class DeleteAccountActivationCommand extends SqlCommand
{
    public function __construct($activationCode)
    {
        parent::__construct(Queries::DELETE_ACCOUNT_ACTIVATION);
        $this->AddParameter(new Parameter(ParameterNames::ACTIVATION_CODE, $activationCode));
    }
}

class DeleteAnnouncementCommand extends SqlCommand
{
    public function __construct($announcementId)
    {
        parent::__construct(Queries::DELETE_ANNOUNCEMENT);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_ID, $announcementId));
    }
}

class DeleteBlackoutInstanceCommand extends SqlCommand
{
    public function __construct($instanceId)
    {
        parent::__construct(Queries::DELETE_BLACKOUT_INSTANCE);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_INSTANCE_ID, $instanceId));
    }
}

class DeleteBlackoutSeriesCommand extends SqlCommand
{
    public function __construct($instanceId)
    {
        parent::__construct(Queries::DELETE_BLACKOUT_SERIES);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_INSTANCE_ID, $instanceId));
    }
}

class DeleteCustomLayoutPeriodCommand extends SqlCommand
{
    public function __construct($scheduleId, Date $start)
    {
        parent::__construct(Queries::DELETE_CUSTOM_LAYOUT_PERIOD);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::START_TIME, $start->ToDatabase()));
    }
}

class DeleteEmailPreferenceCommand extends SqlCommand
{
    public function __construct($userId, $eventCategory, $eventType)
    {
        parent::__construct(Queries::DELETE_EMAIL_PREFERENCE);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::EVENT_CATEGORY, $eventCategory));
        $this->AddParameter(new Parameter(ParameterNames::EVENT_TYPE, $eventType));
    }
}

class DeleteGroupCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::DELETE_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class DeleteGroupResourcePermission extends SqlCommand
{
    public function __construct($groupId, $resourceId)
    {
        parent::__construct(Queries::DELETE_GROUP_RESOURCE_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class DeleteGroupRoleCommand extends SqlCommand
{
    public function __construct($groupId, $roleId)
    {
        parent::__construct(Queries::DELETE_GROUP_ROLE);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::ROLE_ID, $roleId));
    }
}

class DeleteOrphanLayoutsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::DELETE_ORPHAN_LAYOUTS);
    }
}

class DeletePaymentConfigurationCommand extends SqlCommand
{
    public function __construct($creditCount)
    {
        parent::__construct(Queries::DELETE_PAYMENT_CONFIGURATION);
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $creditCount));
    }
}

class DeletePaymentGatewaySettingsCommand extends SqlCommand
{
    /**
     * @param string $gatewayType
     */
    public function __construct($gatewayType)
    {
        parent::__construct(Queries::DELETE_PAYMENT_GATEWAY_SETTINGS);
        $this->AddParameter(new Parameter(ParameterNames::GATEWAY_TYPE, $gatewayType));
    }
}

class DeletePeakTimesCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::DELETE_PEAK_TIMES);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class DeleteQuotaCommand extends SqlCommand
{
    public function __construct($quotaId)
    {
        parent::__construct(Queries::DELETE_QUOTA);
        $this->AddParameter(new Parameter(ParameterNames::QUOTA_ID, $quotaId));
    }
}

class DeleteReminderCommand extends SqlCommand
{
    public function __construct($reminder_id)
    {
        parent::__construct(Queries::DELETE_REMINDER);
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_ID, $reminder_id));
    }
}

class DeleteReminderByUserCommand extends SqlCommand
{
    public function __construct($user_id)
    {
        parent::__construct(Queries::DELETE_REMINDER_BY_USER);
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_USER_ID, $user_id));
    }
}

class DeleteReminderByRefNumberCommand extends SqlCommand
{
    public function __construct($refnumber)
    {
        parent::__construct(Queries::DELETE_REMINDER_BY_REFNUMBER);
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_REFNUMBER, $refnumber));
    }
}

class DeleteReservationColorRuleCommand extends SqlCommand
{
    public function __construct($ruleId)
    {
        parent::__construct(Queries::DELETE_RESERVATION_COLOR_RULE_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::COLOR_RULE_ID, $ruleId));
    }
}

class DeleteReservationWaitlistCommand extends SqlCommand
{
    public function __construct($requestId)
    {
        parent::__construct(Queries::DELETE_RESERVATION_WAITLIST_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_WAITLIST_REQUEST_ID, $requestId));
    }
}

class DeleteResourceCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class DeleteResourceGroupCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_GROUP_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, $groupId));
    }
}

class DeleteResourceReservationsCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_RESERVATIONS_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class DeleteResourceImagesCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_IMAGES);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class DeleteResourceStatusReasonCommand extends SqlCommand
{
    public function __construct($reasonId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_STATUS_REASON_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS_REASON_ID, $reasonId));
    }
}

class DeleteResourceTypeCommand extends SqlCommand
{
    public function __construct($resourceTypeId)
    {
        parent::__construct(Queries::DELETE_RESOURCE_TYPE_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_ID, $resourceTypeId));
    }
}

class DeleteSavedReportCommand extends SqlCommand
{
    public function __construct($reportId, $userId)
    {
        parent::__construct(Queries::DELETE_SAVED_REPORT);
        $this->AddParameter(new Parameter(ParameterNames::REPORT_ID, $reportId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class DeleteScheduleCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::DELETE_SCHEDULE);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class DeleteSeriesCommand extends SqlCommand
{
    public function __construct($seriesId, Date $dateModified, $lastActionBy)
    {
        parent::__construct(Queries::DELETE_SERIES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, $dateModified->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::STATUS_ID, ReservationStatus::Deleted));
        $this->AddParameter(new Parameter(ParameterNames::LAST_ACTION_BY, $lastActionBy));
    }
}

class DeleteSeriesPermanantCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::DELETE_SERIES_PERMANENT);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class DeleteTermsOfServiceCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::DELETE_TERMS_OF_SERVICE);
    }
}

class DeleteUserCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::DELETE_USER);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class DeleteUserGroupCommand extends SqlCommand
{
    public function __construct($userId, $groupId)
    {
        parent::__construct(Queries::DELETE_USER_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class DeleteUserResourcePermission extends SqlCommand
{
    public function __construct($userId, $resourceId)
    {
        parent::__construct(Queries::DELETE_USER_RESOURCE_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class DeleteUserSessionCommand extends SqlCommand
{
    public function __construct($sessionToken)
    {
        parent::__construct(Queries::DELETE_USER_SESSION);
        $this->AddParameter(new Parameter(ParameterNames::SESSION_TOKEN, $sessionToken));
    }
}

class CleanUpUserSessionsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::CLEANUP_USER_SESSIONS);
    }
}

class GetAccessoryByIdCommand extends SqlCommand
{
    public function __construct($accessoryId)
    {
        parent::__construct(Queries::GET_ACCESSORY_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
    }
}

class GetAccessoryResources extends SqlCommand
{
    public function __construct($accessoryId)
    {
        parent::__construct(Queries::GET_ACCESSORY_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
    }
}

class GetAnnouncementByIdCommand extends SqlCommand
{
    public function __construct($announcementId)
    {
        parent::__construct(Queries::GET_ANNOUNCEMENT_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_ID, $announcementId));
    }
}

class GetAttributesByCategoryCommand extends SqlCommand
{
    public function __construct($attributeCategoryId)
    {
        parent::__construct(Queries::GET_ATTRIBUTES_BASE_QUERY . Queries::GET_ATTRIBUTES_BY_CATEGORY_WHERE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, $attributeCategoryId));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetAttributeByIdCommand extends SqlCommand
{
    public function __construct($attributeId)
    {
        parent::__construct(Queries::GET_ATTRIBUTES_BASE_QUERY . Queries::GET_ATTRIBUTE_BY_ID_WHERE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetAttributeAllValuesCommand extends SqlCommand
{
    public function __construct($attributeCategoryId)
    {
        parent::__construct(Queries::GET_ATTRIBUTE_ALL_VALUES);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, $attributeCategoryId));
    }
}

class GetAttributeMultipleValuesCommand extends SqlCommand
{
    public function __construct($attributeCategoryId, $entityIds)
    {
        parent::__construct(Queries::GET_ATTRIBUTE_MULTIPLE_VALUES);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_IDS, $entityIds));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, $attributeCategoryId));
    }
}

class GetAttributeValuesCommand extends SqlCommand
{
    public function __construct($entityId, $attributeCategoryId)
    {
        parent::__construct(Queries::GET_ATTRIBUTE_VALUES);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_ID, $entityId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, $attributeCategoryId));
    }
}

class GetAccessoryListCommand extends SqlCommand
{
    public function __construct(Date $startDate, Date $endDate)
    {
        parent::__construct(Queries::GET_ACCESSORY_LIST);
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
    }
}

class GetAllAccessoriesCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_ACCESSORIES);
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetAllAnnouncementsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_ANNOUNCEMENTS);
    }
}

class GetAllApplicationAdminsCommand extends SqlCommand
{
    public function __construct($adminEmails)
    {
        parent::__construct(Queries::GET_ALL_APPLICATION_ADMINS);
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, AccountStatus::ACTIVE));
        $this->AddParameter(new Parameter(ParameterNames::ROLE_LEVEL, RoleLevel::APPLICATION_ADMIN));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $adminEmails));
    }
}

class GetAllCreditLogsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_ALL_CREDIT_LOGS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetAllGroupsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_GROUPS);
    }
}

class GetAllGroupsByRoleCommand extends SqlCommand
{
    /**
     * @param $roleLevel int|RoleLevel
     */
    public function __construct($roleLevel)
    {
        parent::__construct(Queries::GET_ALL_GROUPS_BY_ROLE);
        $this->AddParameter(new Parameter(ParameterNames::ROLE_LEVEL, $roleLevel));
    }
}

class GetAllGroupResourcePermissions extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_GROUP_RESOURCE_PERMISSIONS);
    }
}

class GetAllGroupAdminsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_ALL_GROUP_ADMINS);
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, AccountStatus::ACTIVE));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetAllGroupUsersCommand extends SqlCommand
{
    public function __construct($groupId, $statusId = AccountStatus::ACTIVE)
    {
        parent::__construct(Queries::GET_ALL_GROUP_USERS);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $statusId));
    }
}

class GetAllGroupPermissionsCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_GROUP_RESOURCE_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}


class GetAllGroupRolesCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_GROUP_ROLES);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class GetAllQuotasCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_QUOTAS);
    }
}

class GetAllRemindersCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_REMINDERS);
    }
}

class GetAllReservationWaitlistRequests extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_RESERVATION_WAITLIST_REQUESTS);
    }
}

class GetAllResourcesCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_RESOURCES);
    }
}

class GetUserResourcesCommand extends SqlCommand
{
    public function __construct($resourceIds)
    {
        parent::__construct(Queries::GET_USER_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_IDS, $resourceIds));
    }
}

class GetAllResourceGroupsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_RESOURCE_GROUPS);
    }
}

class GetAllResourceGroupAssignmentsCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::GET_ALL_RESOURCE_GROUP_ASSIGNMENTS);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class GetAllResourceAdminsCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::GET_ALL_RESOURCE_ADMINS);
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, AccountStatus::ACTIVE));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::ROLE_LEVEL, RoleLevel::RESOURCE_ADMIN));
    }
}

class GetAllResourceStatusReasonsCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_RESOURCE_STATUS_REASONS);
    }
}

class GetAllResourceTypesCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_RESOURCE_TYPES);
    }
}

class GetAllTransactionLogsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_ALL_TRANSACTION_LOGS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetAllSavedReportsForUserCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_ALL_SAVED_REPORTS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetAllSchedulesCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_ALL_SCHEDULES);
    }
}

class GetAllUsersByStatusCommand extends SqlCommand
{
    /**
     * @param int $userStatusId defaults to getting all users regardless of status
     */
    public function __construct($userStatusId = AccountStatus::ALL)
    {
        parent::__construct(Queries::GET_ALL_USERS_BY_STATUS);
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $userStatusId));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetBlackoutListCommand extends SqlCommand
{
    public function __construct(Date $startDate, Date $endDate, $scheduleId, $resourceIds)
    {
        parent::__construct(Queries::GET_BLACKOUT_LIST);
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceIds));
        $this->AddParameter(new Parameter(ParameterNames::ALL_RESOURCES, (int)empty($resourceIds)));
    }
}

class GetBlackoutListFullCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_BLACKOUT_LIST_FULL);
    }
}

class GetBlackoutInstancesCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_BLACKOUT_INSTANCES);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_SERIES_ID, $seriesId));
    }
}

class GetBlackoutSeriesByBlackoutIdCommand extends SqlCommand
{
    public function __construct($blackoutId)
    {
        parent::__construct(Queries::GET_BLACKOUT_SERIES_BY_BLACKOUT_ID);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_INSTANCE_ID, $blackoutId));
    }
}

class GetBlackoutResourcesCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_BLACKOUT_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_SERIES_ID, $seriesId));
    }
}

class GetCustomLayoutCommand extends SqlCommand
{
    public function __construct(Date $date, $scheduleId)
    {
        parent::__construct(Queries::GET_CUSTOM_LAYOUT);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $date->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $date->AddDays(1)->ToDatabase()));
    }
}

class GetCustomLayoutRangeCommand extends SqlCommand
{
    public function __construct(Date $start, Date $end, $scheduleId)
    {
        parent::__construct(Queries::GET_CUSTOM_LAYOUT);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $end->AddDays(1)->GetDate()->ToDatabase()));
    }
}

class GetDashboardAnnouncementsCommand extends SqlCommand
{
    public function __construct(Date $currentDate, $displayPage)
    {
        parent::__construct(Queries::GET_DASHBOARD_ANNOUNCEMENTS);
        $this->AddParameter(new Parameter(ParameterNames::CURRENT_DATE, $currentDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_DISPLAY_PAGE, $displayPage));
    }
}

class GetGroupByIdCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_GROUP_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class GetGroupResourcesId extends SqlCommand{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_GROUP_RESOURCES_ID);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class GetGroupSchedulesId extends SqlCommand{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_GROUP_SCHEDULES_ID);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
    }
}

class GetResourceAdminResourcesCommand extends SqlCommand {
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_RESOURCE_ADMIN_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetScheduleAdminSchedulesCommand extends SqlCommand {
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_SHCEDULE_ADMIN_SCHEDULES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetScheduleAdminResourcesCommand extends SqlCommand {
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_SCHEDULE_ADMIN_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetGroupsIManageCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_GROUPS_I_CAN_MANAGE);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetLayoutCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::GET_SCHEDULE_TIME_BLOCK_GROUPS);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class GetPaymentConfigurationCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_PAYMENT_CONFIGURATION);
    }
}

class GetPaymentGatewaySettingsCommand extends SqlCommand
{
    public function __construct($gatewayType)
    {
        parent::__construct(Queries::GET_PAYMENT_GATEWAY_SETTINGS);
        $this->AddParameter(new Parameter(ParameterNames::GATEWAY_TYPE, $gatewayType));
    }
}

class GetPeakTimesCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::GET_PEAK_TIMES);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class GetReminderByUserCommand extends SqlCommand
{
    public function __construct($user_id)
    {
        parent::__construct(Queries::GET_REMINDERS_BY_USER);
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_USER_ID, $user_id));
    }
}

class GetReminderByRefNumberCommand extends SqlCommand
{
    public function __construct($refnumber)
    {
        parent::__construct(Queries::GET_REMINDERS_BY_REFNUMBER);
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_REFNUMBER, $refnumber));
    }
}

class GetReservationForEditingCommand extends SqlCommand
{
    public function __construct($referenceNumber)
    {
        parent::__construct(Queries::GET_RESERVATION_FOR_EDITING);
        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, ReservationUserLevel::OWNER));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_LEVEL_ID, ResourceLevel::Primary));
    }
}

class GetFullReservationListCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(QueryBuilder::GET_RESERVATION_LIST_FULL());
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, ReservationUserLevel::OWNER));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetReservationsByAccessoryNameCommand extends SqlCommand
{
    public function __construct(Date $startDate, Date $endDate, $accessoryName)
    {
        parent::__construct(QueryBuilder::GET_RESERVATIONS_BY_ACCESSORY_NAME());
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_NAME, $accessoryName));
    }
}

class GetFullGroupReservationListCommand extends GetFullReservationListCommand
{
    public function __construct($groupIds = [])
    {
        parent::__construct();
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupIds));
    }

    public function GetQuery()
    {
        $query = parent::GetQuery();

        $pos = strripos($query, 'WHERE');
        $newQuery = substr_replace(
            $query,
            'INNER JOIN (SELECT user_id FROM user_groups WHERE group_id IN (@groupid)) ss on ss.user_id = owner_id WHERE',
            $pos,
            strlen('WHERE')
        );

        //		$newQuery = preg_replace('/WHERE/',
        //								 'WHERE owner_id IN (SELECT user_id FROM user_groups WHERE group_id IN (@groupid)) AND ',
        //								 $query, 1);

        return $newQuery;
    }
}

class GetReservationListCommand extends SqlCommand
{
    public function __construct(Date $startDate, Date $endDate, $userIds, $userLevelId, $scheduleIds, $resourceIds, $participantIds)
    {
        parent::__construct(QueryBuilder::GET_RESERVATION_LIST());
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userIds));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $userLevelId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleIds));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceIds));
        $this->AddParameter(new Parameter(ParameterNames::PARTICIPANT_ID, $participantIds));
        $this->AddParameter(new Parameter(ParameterNames::ALL_RESOURCES, (int)empty($resourceIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_SCHEDULES, (int)empty($scheduleIds)));
        $this->AddParameter(new Parameter(ParameterNames::All_OWNERS, (int)empty($userIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_PARTICIPANTS, (int)empty($participantIds)));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetReservationsPendingApprovalCommand extends SqlCommand
{
    public function __construct(Date $startDate, $userIds, $userLevelId, $scheduleIds, $resourceIds, $participantIds)
    {
        parent::__construct(QueryBuilder::GET_RESERVATION_PENDING_APPROVAL_LIST());
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userIds));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $userLevelId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleIds));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceIds));
        $this->AddParameter(new Parameter(ParameterNames::PARTICIPANT_ID, $participantIds));
        $this->AddParameter(new Parameter(ParameterNames::ALL_RESOURCES, (int)empty($resourceIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_SCHEDULES, (int)empty($scheduleIds)));
        $this->AddParameter(new Parameter(ParameterNames::All_OWNERS, (int)empty($userIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_PARTICIPANTS, (int)empty($participantIds)));
    }
}

class GetReservationsMissingCheckInCheckOutCommand extends SqlCommand
{
    public function __construct(?Date $startDate, Date $endDate, $userIds, $userLevelId, $scheduleIds, $resourceIds, $participantIds)
    {
        parent::__construct(QueryBuilder::GET_RESERVATION_MISSING_CHECK_IN_OUT_LIST());
        if ($startDate !== null){
            $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        }
        else {
            $this->AddParameter(new Parameter(ParameterNames::START_DATE, NULL));
        }
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userIds));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $userLevelId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleIds));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceIds));
        $this->AddParameter(new Parameter(ParameterNames::PARTICIPANT_ID, $participantIds));
        $this->AddParameter(new Parameter(ParameterNames::ALL_RESOURCES, (int)empty($resourceIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_SCHEDULES, (int)empty($scheduleIds)));
        $this->AddParameter(new Parameter(ParameterNames::All_OWNERS, (int)empty($userIds)));
        $this->AddParameter(new Parameter(ParameterNames::ALL_PARTICIPANTS, (int)empty($participantIds)));

        //When the interval is set (start and end date), if it includes future dates, it should only return reservations before today.
        //It is impossible to miss a check in/out if the reservation hasn't even started (although checkin_date and checkout_date are null in the db)
        $now = Date::Now();
        $this->AddParameter(new Parameter(ParameterNames::CURRENT_DATE, $now->ToDatabase()));
    }
}

class GetReminderNoticesCommand extends SqlCommand
{
    public function __construct(Date $currentDate, $type)
    {
        parent::__construct(Queries::GET_REMINDER_NOTICES);
        $this->AddParameter(new Parameter(ParameterNames::CURRENT_DATE, $currentDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_TYPE, $type));
    }

    public function ContainsGroupConcat()
    {
        return true;
    }
}

class GetReservationAccessoriesCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_ACCESSORIES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationAttachmentCommand extends SqlCommand
{
    public function __construct($fileId)
    {
        parent::__construct(Queries::GET_RESERVATION_ATTACHMENT);
        $this->AddParameter(new Parameter(ParameterNames::FILE_ID, $fileId));
    }
}

class GetReservationAttachmentsCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_ATTACHMENTS_FOR_SERIES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationGuestsCommand extends SqlCommand
{
    public function __construct($instanceId)
    {
        parent::__construct(Queries::GET_RESERVATION_GUESTS);
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
    }
}

class GetReservationColorRulesCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_RESERVATION_COLOR_RULES);
    }
}

class GetReservationColorRuleCommand extends SqlCommand
{
    public function __construct($ruleId)
    {
        parent::__construct(Queries::GET_RESERVATION_COLOR_RULE);
        $this->AddParameter(new Parameter(ParameterNames::COLOR_RULE_ID, $ruleId));
    }
}

class GetReservationParticipantsCommand extends SqlCommand
{
    public function __construct($instanceId)
    {
        parent::__construct(Queries::GET_RESERVATION_PARTICIPANTS);
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
    }
}

class GetReservationReminders extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_REMINDERS);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationRepeatDatesCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_REPEAT_DATES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationResourcesCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationByIdCommand extends SqlCommand
{
    public function __construct($reservationId)
    {
        parent::__construct(Queries::GET_RESERVATION_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $reservationId));
    }
}

class GetReservationByReferenceNumberCommand extends SqlCommand
{
    public function __construct($referenceNumber)
    {
        parent::__construct(Queries::GET_RESERVATION_BY_REFERENCE_NUMBER);
        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
    }
}

class GetReservationSeriesGuestsCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_SERIES_GUESTS);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationSeriesInstances extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_SERIES_INSTANCES);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationSeriesParticipantsCommand extends SqlCommand
{
    public function __construct($seriesId)
    {
        parent::__construct(Queries::GET_RESERVATION_SERIES_PARTICIPANTS);
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
    }
}

class GetReservationWaitlistRequestCommand extends SqlCommand
{
    public function __construct($waitlistId)
    {
        parent::__construct(Queries::GET_RESERVATION_WAITLIST_REQUEST);
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_WAITLIST_REQUEST_ID, $waitlistId));
    }
}


## (C) 2012 Alois Schloegl
class GetResourceByContactInfoCommand extends SqlCommand
{
    public function __construct($contact_info)
    {
        parent::__construct(Queries::GET_RESOURCE_BY_CONTACT_INFO);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_CONTACT, $contact_info));
    }
}

class GetResourceByIdCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::GET_RESOURCE_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class GetResourceByPublicIdCommand extends SqlCommand
{
    public function __construct($publicId)
    {
        parent::__construct(Queries::GET_RESOURCE_BY_PUBLIC_ID);
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
    }
}

class GetResourcesPublicCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_RESOURCES_PUBLIC);
    }
}

class GetResourceByNameCommand extends SqlCommand
{
    public function __construct($resourceName)
    {
        parent::__construct(Queries::GET_RESOURCE_BY_NAME);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_NAME, $resourceName));
    }
}

class GetResourceGroupCommand extends SqlCommand
{
    public function __construct($groupId)
    {
        parent::__construct(Queries::GET_RESOURCE_GROUP_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, $groupId));
    }
}

class GetResourceGroupAssignmentsCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::GET_RESOURCE_GROUP_ASSIGNMENTS);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class GetResourceTypeCommand extends SqlCommand
{
    public function __construct($resourceTypeId)
    {
        parent::__construct(Queries::GET_RESOURCE_TYPE_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_ID, $resourceTypeId));
    }
}

class GetResourceTypeByNameCommand extends SqlCommand
{
    public function __construct($resourceTypeName)
    {
        parent::__construct(Queries::GET_RESOURCE_TYPE_BY_NAME);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_NAME, $resourceTypeName));
    }
}

class GetSavedReportForUserCommand extends SqlCommand
{
    public function __construct($reportId, $userId)
    {
        parent::__construct(Queries::GET_SAVED_REPORT);
        $this->AddParameter(new Parameter(ParameterNames::REPORT_ID, $reportId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetScheduleByIdCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::GET_SCHEDULE_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class GetScheduleByPublicIdCommand extends SqlCommand
{
    public function __construct($publicId)
    {
        parent::__construct(Queries::GET_SCHEDULE_BY_PUBLIC_ID);
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
    }
}

class GetScheduleResourcesCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::GET_SCHEDULE_RESOURCES);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class GetSchedulesPublicCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_SCHEDULES_PUBLIC);
    }
}

class GetTransactionLogCommand extends SqlCommand
{
    public function __construct($id)
    {
        parent::__construct(Queries::GET_TRANSACTION_LOG);
        $this->AddParameter(new Parameter(ParameterNames::PAYMENT_TRANSACTION_LOG_ID, $id));
    }
}

class GetTermsOfServiceCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_TERMS_OF_SERVICE);
    }
}

class GetUserIdByActivationCodeCommand extends SqlCommand
{
    public function __construct($activationCode)
    {
        parent::__construct(Queries::GET_USERID_BY_ACTIVATION_CODE);
        $this->AddParameter(new Parameter(ParameterNames::ACTIVATION_CODE, $activationCode));
        $this->AddParameter(new Parameter(ParameterNames::STATUS_ID, AccountStatus::AWAITING_ACTIVATION));
    }
}

class GetUserByIdCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_BY_ID);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetUserByPublicIdCommand extends SqlCommand
{
    public function __construct($publicId)
    {
        parent::__construct(Queries::GET_USER_BY_PUBLIC_ID);
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
    }
}

class GetUserCountCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_USER_COUNT);
    }
}

class GetUserEmailPreferencesCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_EMAIL_PREFERENCES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetUserGroupsCommand extends SqlCommand
{
    public function __construct($userId, $roleLevels)
    {
        parent::__construct(Queries::GET_USER_GROUPS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::ROLE_LEVEL, $roleLevels));
        $this->AddParameter(new Parameter('@role_null', empty($roleLevels) ? null : '1'));
    }
}

class GetUserRoleCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_ROLES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}


class GetUserSessionBySessionTokenCommand extends SqlCommand
{
    public function __construct($sessionToken)
    {
        parent::__construct(Queries::GET_USER_SESSION_BY_SESSION_TOKEN);
        $this->AddParameter(new Parameter(ParameterNames::SESSION_TOKEN, $sessionToken));
    }
}

class GetUserSessionByUserIdCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_SESSION_BY_USERID);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetVersionCommand extends SqlCommand
{
    public function __construct()
    {
        parent::__construct(Queries::GET_VERSION);
    }
}

class LogCreditActivityCommand extends SqlCommand
{
    public function __construct($userId, $originalCredits, $currentCredits, $note)
    {
        parent::__construct(Queries::LOG_CREDIT_ACTIVITY_COMMAND);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::ORIGINAL_CREDIT_COUNT, $originalCredits));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $currentCredits));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_NOTE, $note));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
    }
}

class LoginCommand extends SqlCommand
{
    public function __construct($username)
    {
        parent::__construct(Queries::LOGIN_USER);
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, strtolower($username)));
    }
}

class MigratePasswordCommand extends SqlCommand
{
    public function __construct($userId, $password, $salt)
    {
        parent::__construct(Queries::MIGRATE_PASSWORD);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::PASSWORD, $password));
        $this->AddParameter(new Parameter(ParameterNames::SALT, $salt));
    }
}

class RegisterUserCommand extends SqlCommand
{
    public function __construct(
        $username,
        $email,
        $fname,
        $lname,
        $password,
        $salt,
        $timezone,
        $language,
        $homepageId,
        $phone,
        $organization,
        $position,
        $userStatusId,
        $publicId,
        $scheduleId,
        $termsAcceptedDate
    ) {
        parent::__construct(Queries::REGISTER_USER);

        $termsAcceptedDate = $termsAcceptedDate == null ? new NullDate() : $termsAcceptedDate;

        $this->AddParameter(new Parameter(ParameterNames::USERNAME, $username));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $email));
        $this->AddParameter(new Parameter(ParameterNames::FIRST_NAME, $fname));
        $this->AddParameter(new Parameter(ParameterNames::LAST_NAME, $lname));
        $this->AddParameter(new Parameter(ParameterNames::PASSWORD, $password));
        $this->AddParameter(new Parameter(ParameterNames::SALT, $salt));
        $this->AddParameter(new Parameter(ParameterNames::TIMEZONE_NAME, $timezone));
        $this->AddParameter(new Parameter(ParameterNames::LANGUAGE, $language));
        $this->AddParameter(new Parameter(ParameterNames::HOMEPAGE_ID, $homepageId));
        $this->AddParameter(new Parameter(ParameterNames::PHONE, $phone));
        $this->AddParameter(new Parameter(ParameterNames::ORGANIZATION, $organization));
        $this->AddParameter(new Parameter(ParameterNames::POSITION, $position));
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $userStatusId));
        $this->AddParameter(new Parameter(ParameterNames::DATE_CREATED, Date::Now()->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::TERMS_ACCEPTANCE_DATE, $termsAcceptedDate->ToDatabase()));
    }
}

class RemoveAttributeValueCommand extends SqlCommand
{
    public function __construct($attributeId, $entityId)
    {
        parent::__construct(Queries::REMOVE_ATTRIBUTE_VALUE);

        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_ID, $entityId));
    }
}

class RemoveAttributeEntityCommand extends SqlCommand
{
    public function __construct($attributeId, $entityId)
    {
        parent::__construct(Queries::REMOVE_ATTRIBUTE_ENTITY);

        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ENTITY_ID, $entityId));
    }
}

class RemoveLegacyPasswordCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::REMOVE_LEGACY_PASSWORD);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetResourceGroupByPublicIdCommand extends SqlCommand
{
    public function __construct($publicGroupId)
    {
        parent::__construct(Queries::GET_RESOURCE_GROUP_BY_PUBLIC_ID);
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicGroupId));
    }
}

class GetResourceGroupPermissionCommand extends SqlCommand
{
    public function __construct($resourceId)
    {
        parent::__construct(Queries::GET_RESOURCE_GROUP_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class GetResourceUserPermissionCommand extends SqlCommand
{
    public function __construct($resourceId, $accountStatusId = AccountStatus::ACTIVE)
    {
        parent::__construct(Queries::GET_RESOURCE_USER_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $accountStatusId));
    }
}

class GetResourceUserGroupPermissionCommand extends SqlCommand
{
    public function __construct($resourceId, $accountStatusId = AccountStatus::ACTIVE)
    {
        parent::__construct(Queries::GET_RESOURCE_USER_GROUP_PERMISSION);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $accountStatusId));
    }
}

class RemoveReservationAccessoryCommand extends SqlCommand
{
    public function __construct($seriesId, $accessoryId)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_ACCESSORY);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
    }
}

class RemoveReservationAttachmentCommand extends SqlCommand
{
    public function __construct($fileId)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_ATTACHMENT);

        $this->AddParameter(new Parameter(ParameterNames::FILE_ID, $fileId));
    }
}

class RemoveReservationCommand extends SqlCommand
{
    public function __construct($referenceNumber)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_INSTANCE);

        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
    }
}

class RemoveReservationGuestCommand extends SqlCommand
{
    public function __construct($instanceId, $emailAddress)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_GUEST);

        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $emailAddress));
    }
}

class RemoveReservationReminderCommand extends SqlCommand
{
    public function __construct($seriesId, $reminderType)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_REMINDER);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::REMINDER_TYPE, $reminderType));
    }
}

class RemoveReservationResourceCommand extends SqlCommand
{
    public function __construct($seriesId, $resourceId)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_RESOURCE);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
    }
}

class RemoveReservationUserCommand extends SqlCommand
{
    public function __construct($instanceId, $userId)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_USER);

        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class RemoveReservationUsersCommand extends SqlCommand
{
    public function __construct($instanceId, $levelId)
    {
        parent::__construct(Queries::REMOVE_RESERVATION_USERS);

        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::RESERVATION_USER_LEVEL_ID, $levelId));
    }
}

class RemoveResourceFromGroupCommand extends SqlCommand
{
    public function __construct($resourceId, $groupId)
    {
        parent::__construct(Queries::REMOVE_RESOURCE_FROM_GROUP);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $resourceId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, $groupId));
    }
}

class GetUserPermissionsCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_RESOURCE_PERMISSIONS);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class GetUserPreferenceCommand extends SqlCommand
{
    public function __construct($userId, $name)
    {
        parent::__construct(Queries::GET_USER_PREFERENCE);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::NAME, $name));
    }
}

class GetUserPreferencesCommand extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_PREFERENCES);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class AddUserPreferenceCommand extends SqlCommand
{
    public function __construct($userId, $name, $value)
    {
        parent::__construct(Queries::ADD_USER_PREFERENCE);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::VALUE, $value));
    }
}

class DeleteAllUserPreferences extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::DELETE_ALL_USER_PREFERENCES);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class SelectUserGroupPermissions extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_GROUP_RESOURCE_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class SelectUserGroupResourceAdminPermissions extends SqlCommand
{
    public function __construct($userId)
    {
        parent::__construct(Queries::GET_USER_ADMIN_GROUP_RESOURCE_PERMISSIONS);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class SetDefaultScheduleCommand extends SqlCommand
{
    public function __construct($scheduleId)
    {
        parent::__construct(Queries::SET_DEFAULT_SCHEDULE);
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
    }
}

class UpdateAccessoryCommand extends SqlCommand
{
    public function __construct($accessoryId, $accessoryName, $quantityAvailable)
    {
        parent::__construct(Queries::UPDATE_ACCESSORY);
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_ID, $accessoryId));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_NAME, $accessoryName));
        $this->AddParameter(new Parameter(ParameterNames::ACCESSORY_QUANTITY, $quantityAvailable));
    }
}

class UpdateAnnouncementCommand extends SqlCommand
{
    public function __construct($announcementId, $text, Date $start, Date $end, $priority)
    {
        parent::__construct(Queries::UPDATE_ANNOUNCEMENT);
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_ID, $announcementId));
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_TEXT, $text));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $end->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::ANNOUNCEMENT_PRIORITY, $priority));
    }
}

class UpdateAttributeCommand extends SqlCommand
{
    public function __construct(
        $attributeId,
        $label,
        $type,
        $category,
        $regex,
        $required,
        $possibleValues,
        $sortOrder,
        $adminOnly,
        $secondaryCategory,
        $secondaryEntityIds,
        $isPrivate
    ) {
        parent::__construct(Queries::UPDATE_ATTRIBUTE);
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ID, $attributeId));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_LABEL, $label));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_TYPE, (int)$type));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_CATEGORY, (int)$category));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_REGEX, $regex));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_REQUIRED, (int)$required));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_POSSIBLE_VALUES, $possibleValues));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SORT_ORDER, $sortOrder));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_ADMIN_ONLY, (int)$adminOnly));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SECONDARY_CATEGORY, $secondaryCategory));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_SECONDARY_ENTITY_IDS, implode(',', $secondaryEntityIds)));
        $this->AddParameter(new Parameter(ParameterNames::ATTRIBUTE_IS_PRIVATE, (int)$isPrivate));
    }
}

class UpdateBlackoutInstanceCommand extends SqlCommand
{
    public function __construct($instanceId, $seriesId, Date $start, Date $end)
    {
        parent::__construct(Queries::UPDATE_BLACKOUT_INSTANCE);
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_INSTANCE_ID, $instanceId));
        $this->AddParameter(new Parameter(ParameterNames::BLACKOUT_SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $start->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $end->ToDatabase()));
    }
}

class UpdateGroupCommand extends SqlCommand
{
    public function __construct($groupId, $groupName, $adminGroupId, $isDefault)
    {
        parent::__construct(Queries::UPDATE_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_NAME, $groupName));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ADMIN_ID, $adminGroupId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ISDEFAULT, intval($isDefault)));
    }
}

class UpdateLoginDataCommand extends SqlCommand
{
    public function __construct($userId, $lastLoginTime, $language)
    {
        parent::__construct(Queries::UPDATE_LOGINDATA);
        $this->AddParameter(new Parameter(ParameterNames::LAST_LOGIN, $lastLoginTime));
        $this->AddParameter(new Parameter(ParameterNames::LANGUAGE, $language));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
    }
}

class UpdateFutureReservationsCommand extends SqlCommand
{
    public function __construct($referenceNumber, $newSeriesId, $currentSeriesId)
    {
        parent::__construct(Queries::UPDATE_FUTURE_RESERVATION_INSTANCES);

        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $currentSeriesId));
        $this->AddParameter(new Parameter(ParameterNames::CURRENT_SERIES_ID, $currentSeriesId));
    }
}

class UpdateReservationCommand extends SqlCommand
{
    public function __construct(
        $referenceNumber,
        $seriesId,
        Date $startDate,
        Date $endDate,
        Date $checkinDate,
        Date $checkoutDate,
        Date $previousEndDate,
        $credits
    ) {
        parent::__construct(Queries::UPDATE_RESERVATION_INSTANCE);

        $this->AddParameter(new Parameter(ParameterNames::REFERENCE_NUMBER, $referenceNumber));
        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::START_DATE, $startDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::END_DATE, $endDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::CHECKIN_DATE, $checkinDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::CHECKOUT_DATE, $checkoutDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::PREVIOUS_END_DATE, $previousEndDate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $credits));
    }
}

class UpdateReservationSeriesCommand extends SqlCommand
{
    public function __construct(
        $seriesId,
        $title,
        $description,
        $repeatType,
        $repeatOptions,
        Date $dateModified,
        $statusId,
        $ownerId,
        $allowParticipation,
        $lastActionBy
    ) {
        parent::__construct(Queries::UPDATE_RESERVATION_SERIES);

        $this->AddParameter(new Parameter(ParameterNames::SERIES_ID, $seriesId));
        $this->AddParameter(new Parameter(ParameterNames::TITLE, $title));
        $this->AddParameter(new Parameter(ParameterNames::DESCRIPTION, $description));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_TYPE, $repeatType));
        $this->AddParameter(new Parameter(ParameterNames::REPEAT_OPTIONS, $repeatOptions));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, $dateModified->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::STATUS_ID, $statusId));
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $ownerId));
        $this->AddParameter(new Parameter(ParameterNames::ALLOW_PARTICIPATION, (int)$allowParticipation));
        $this->AddParameter(new Parameter(ParameterNames::LAST_ACTION_BY, $lastActionBy));
    }
}

class UpdateResourceCommand extends SqlCommand
{
    public function __construct(
        $id,
        $name,
        $location,
        $contact,
        $notes,
        TimeInterval $minDuration,
        TimeInterval $maxDuration,
        $autoAssign,
        $requiresApproval,
        $allowMultiday,
        $maxParticipants,
        TimeInterval $minNoticeTimeAdd,
        TimeInterval $maxNoticeTime,
        $description,
        $imageName,
        $scheduleId,
        $adminGroupId,
        $allowCalendarSubscription,
        $publicId,
        $sortOrder,
        $resourceTypeId,
        $statusId,
        $reasonId,
        TimeInterval $bufferTime,
        $color,
        $checkinEnabled,
        $autoReleaseMinutes,
        $isDisplayEnabled,
        $credits,
        $peakCredits,
        TimeInterval $minNoticeTimeUpdate,
        TimeInterval $minNoticeTimeDelete,
        $serializedProperties
    ) {
        parent::__construct(Queries::UPDATE_RESOURCE);

        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ID, $id));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_LOCATION, $location));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_CONTACT, $contact));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_DESCRIPTION, $description));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_NOTES, $notes));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINDURATION, $minDuration->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAXDURATION, $maxDuration->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_AUTOASSIGN, (int)$autoAssign));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_REQUIRES_APPROVAL, $requiresApproval));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ALLOW_MULTIDAY, (int)$allowMultiday));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAX_PARTICIPANTS, $maxParticipants));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINNOTICE_ADD, $minNoticeTimeAdd->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MAXNOTICE, $maxNoticeTime->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_IMAGE_NAME, $imageName));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ADMIN_ID, $adminGroupId));
        $this->AddParameter(new Parameter(ParameterNames::ALLOW_CALENDAR_SUBSCRIPTION, (int)$allowCalendarSubscription));
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_SORT_ORDER, $sortOrder));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_ID, empty($resourceTypeId) ? null : $resourceTypeId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS, $statusId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS_REASON_ID, $reasonId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_BUFFER_TIME, $bufferTime->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::COLOR, $color));
        $this->AddParameter(new Parameter(ParameterNames::ENABLE_CHECK_IN, (int)$checkinEnabled));
        $this->AddParameter(new Parameter(ParameterNames::AUTO_RELEASE_MINUTES, $autoReleaseMinutes));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_ALLOW_DISPLAY, (int)$isDisplayEnabled));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $credits));
        $this->AddParameter(new Parameter(ParameterNames::PEAK_CREDIT_COUNT, $peakCredits));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINNOTICE_UPDATE, $minNoticeTimeUpdate->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_MINNOTICE_DELETE, $minNoticeTimeDelete->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, Date::Now()->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::ADDITIONAL_PROPERTIES, $serializedProperties));
    }
}

class UpdateResourceGroupCommand extends SqlCommand
{
    public function __construct($groupId, $name, $parentId)
    {
        parent::__construct(Queries::UPDATE_RESOURCE_GROUP);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_ID, $groupId));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_GROUP_PARENT_ID, empty($parentId) ? null : $parentId));
    }
}

class UpdateResourceStatusReasonCommand extends SqlCommand
{
    public function __construct($id, $description)
    {
        parent::__construct(Queries::UPDATE_RESOURCE_STATUS_REASON);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS_REASON_ID, $id));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_STATUS_REASON_DESCRIPTION, $description));
    }
}

class UpdateResourceTypeCommand extends SqlCommand
{
    public function __construct($id, $name, $description)
    {
        parent::__construct(Queries::UPDATE_RESOURCE_TYPE);
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_ID, $id));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::RESOURCE_TYPE_DESCRIPTION, $description));
    }
}

class UpdateScheduleCommand extends SqlCommand
{
    public function __construct(
        $scheduleId,
        $name,
        $isDefault,
        $weekdayStart,
        $daysVisible,
        $subscriptionEnabled,
        $publicId,
        $adminGroupId,
        Date $availabilityBegin,
        Date $availabilityEnd,
        $defaultStyle,
        $totalConcurrentReservations,
        $maxResourcesPerReservation
    ) {
        parent::__construct(Queries::UPDATE_SCHEDULE);

        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ISDEFAULT, (int)$isDefault));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_WEEKDAYSTART, (int)$weekdayStart));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_DAYSVISIBLE, (int)$daysVisible));
        $this->AddParameter(new Parameter(ParameterNames::ALLOW_CALENDAR_SUBSCRIPTION, (int)$subscriptionEnabled));
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
        $this->AddParameter(new Parameter(ParameterNames::GROUP_ADMIN_ID, $adminGroupId));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_AVAILABILITY_BEGIN, $availabilityBegin->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_AVAILABILITY_END, $availabilityEnd->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_DEFAULT_STYLE, (int)$defaultStyle));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_TOTAL_CONCURRENT_RESERVATIONS, (int)$totalConcurrentReservations));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_MAX_RESOURCES_PER_RESERVATION, (int)$maxResourcesPerReservation));
    }
}

class UpdateScheduleLayoutCommand extends SqlCommand
{
    public function __construct($scheduleId, $layoutId)
    {
        parent::__construct(Queries::UPDATE_SCHEDULE_LAYOUT);

        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::LAYOUT_ID, $layoutId));
    }
}

class UpdateUserCommand extends SqlCommand
{
    public function __construct(
        $userId,
        $statusId,
        $encryptedPassword,
        $passwordSalt,
        $firstName,
        $lastName,
        $emailAddress,
        $username,
        $homepageId,
        $timezoneName,
        $lastLogin,
        $allowCalendarSubscription,
        $publicId,
        $language,
        $scheduleId,
        $currentCreditCount
    ) {
        parent::__construct(Queries::UPDATE_USER);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::USER_STATUS_ID, $statusId));
        $this->AddParameter(new Parameter(ParameterNames::PASSWORD, $encryptedPassword));
        $this->AddParameter(new Parameter(ParameterNames::SALT, $passwordSalt));
        $this->AddParameter(new Parameter(ParameterNames::FIRST_NAME, $firstName));
        $this->AddParameter(new Parameter(ParameterNames::LAST_NAME, $lastName));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $emailAddress));
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, $username));
        $this->AddParameter(new Parameter(ParameterNames::HOMEPAGE_ID, $homepageId));
        $this->AddParameter(new Parameter(ParameterNames::TIMEZONE_NAME, $timezoneName));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, Date::Now()->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::LAST_LOGIN, $lastLogin));
        $this->AddParameter(new Parameter(ParameterNames::ALLOW_CALENDAR_SUBSCRIPTION, (int)$allowCalendarSubscription));
        $this->AddParameter(new Parameter(ParameterNames::PUBLIC_ID, $publicId));
        $this->AddParameter(new Parameter(ParameterNames::LANGUAGE, $language));
        $this->AddParameter(new Parameter(ParameterNames::SCHEDULE_ID, $scheduleId));
        $this->AddParameter(new Parameter(ParameterNames::CREDIT_COUNT, $currentCreditCount));
    }
}

class UpdateUserAttributesCommand extends SqlCommand
{
    public function __construct(
        $userId,
        $phoneNumber,
        $organization,
        $position
    ) {
        parent::__construct(Queries::UPDATE_USER_ATTRIBUTES);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::PHONE, $phoneNumber));
        $this->AddParameter(new Parameter(ParameterNames::POSITION, $position));
        $this->AddParameter(new Parameter(ParameterNames::ORGANIZATION, $organization));
    }
}


class UpdateUserFromLdapCommand extends SqlCommand
{
    public function __construct($username, $email, $fname, $lname, $password, $salt, $phone, $organization, $position)
    {
        parent::__construct(Queries::UPDATE_USER_BY_USERNAME);
        $this->AddParameter(new Parameter(ParameterNames::USERNAME, $username));
        $this->AddParameter(new Parameter(ParameterNames::EMAIL_ADDRESS, $email));
        $this->AddParameter(new Parameter(ParameterNames::FIRST_NAME, $fname));
        $this->AddParameter(new Parameter(ParameterNames::LAST_NAME, $lname));
        $this->AddParameter(new Parameter(ParameterNames::PASSWORD, $password));
        $this->AddParameter(new Parameter(ParameterNames::SALT, $salt));
        $this->AddParameter(new Parameter(ParameterNames::PHONE, $phone));
        $this->AddParameter(new Parameter(ParameterNames::ORGANIZATION, $organization));
        $this->AddParameter(new Parameter(ParameterNames::POSITION, $position));
    }
}

class UpdateUserPreferenceCommand extends SqlCommand
{
    public function __construct($userId, $name, $value)
    {
        parent::__construct(Queries::UPDATE_USER_PREFERENCE);

        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::NAME, $name));
        $this->AddParameter(new Parameter(ParameterNames::VALUE, $value));
    }
}

class UpdateUserSessionCommand extends SqlCommand
{
    public function __construct($userId, $token, Date $insertTime, $serializedSession)
    {
        parent::__construct(Queries::UPDATE_USER_SESSION);
        $this->AddParameter(new Parameter(ParameterNames::USER_ID, $userId));
        $this->AddParameter(new Parameter(ParameterNames::SESSION_TOKEN, $token));
        $this->AddParameter(new Parameter(ParameterNames::DATE_MODIFIED, $insertTime->ToDatabase()));
        $this->AddParameter(new Parameter(ParameterNames::USER_SESSION, $serializedSession));
    }
}
