<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenter.php');

interface IReservationPage extends IPage
{
    /**
     * @param $startPeriods array|SchedulePeriod[]
     * @param $endPeriods array|SchedulePeriod[]
     * @parma $lockDates bool
     */
    public function BindPeriods($startPeriods, $endPeriods, $lockPeriods);

    /**
     * @param $resources array|ResourceDto[]
     */
    public function BindAvailableResources($resources);

    /**
     * @param $accessories Accessory[]
     */
    public function BindAvailableAccessories($accessories);

    /**
     * @param $groups ResourceGroupTree
     */
    public function BindResourceGroups($groups);

    /**
     * @param SchedulePeriod $selectedStart
     * @param Date $startDate
     */
    public function SetSelectedStart(SchedulePeriod $selectedStart, Date $startDate);

    /**
     * @param SchedulePeriod $selectedEnd
     * @param Date $endDate
     */
    public function SetSelectedEnd(SchedulePeriod $selectedEnd, Date $endDate);

    /**
     * @param $repeatTerminationDate Date
     */
    public function SetRepeatTerminationDate($repeatTerminationDate);

    /**
     * @param UserDto $user
     */
    public function SetReservationUser(UserDto $user);

    /**
     * @param IResource $resource
     */
    public function SetReservationResource($resource);

    /**
     * @param int $scheduleId
     */
    public function SetScheduleId($scheduleId);

    /**
     * @param ReservationUserView[] $participants
     */
    public function SetParticipants($participants);

    /**
     * @param ReservationUserView[] $invitees
     */
    public function SetInvitees($invitees);

    /**
     * @param $accessories ReservationAccessory[]|array
     */
    public function SetAccessories($accessories);

    /**
     * @param $attachments ReservationAttachmentView[]|array
     */
    public function SetAttachments($attachments);

    /**
     * @param $canChangeUser
     */
    public function SetCanChangeUser($canChangeUser);

    /**
     * @param bool $canShowAdditionalResources
     */
    public function ShowAdditionalResources($canShowAdditionalResources);

    /**
     * @param bool $canShowUserDetails
     */
    public function ShowUserDetails($canShowUserDetails);

    /**
     * @param bool $shouldShow
     */
    public function SetShowParticipation($shouldShow);

    /**
     * @param bool $showReservationDetails
     */
    public function ShowReservationDetails($showReservationDetails);

    /**
     * @param bool $isHidden
     */
    public function HideRecurrence($isHidden);

    /**
     * @param bool $allowParticipation
     */
    public function SetAllowParticipantsToJoin($allowParticipation);

    /**
     * @param int $reminderValue
     * @param ReservationReminderInterval $reminderInterval
     */
    public function SetStartReminder($reminderValue, $reminderInterval);

    /**
     * @param int $reminderValue
     * @param ReservationReminderInterval $reminderInterval
     */
    public function SetEndReminder($reminderValue, $reminderInterval);

    /**
     * @param DateRange $availability
     */
    public function SetAvailability(DateRange $availability);

    /**
     * @param int $weekday
     */
    public function SetFirstWeekday($weekday);

    public function MakeUnavailable();

    /**
     * @return bool
     */
    public function IsUnavailable();

    /**
     * @param TermsOfService $termsOfService
     */
    public function SetTerms($termsOfService);

    /**
     * @param bool $accepted
     */
    public function SetTermsAccepted($accepted);

    /**
     * @param int $maximum
     */
    public function SetMaximumResources($maximum);
}

abstract class ReservationPage extends Page implements IReservationPage
{
    protected $presenter;
    protected $available = true;

    /**
     * @var IPermissionServiceFactory
     */
    protected $permissionServiceFactory;

    public function __construct($title = null)
    {
        parent::__construct($title);

        if (is_null($this->permissionServiceFactory)) {
            $this->permissionServiceFactory = new PermissionServiceFactory();
        }
    }

    /**
     * @return IReservationPresenter
     */
    abstract protected function GetPresenter();

    /**
     * @return string
     */
    abstract protected function GetTemplateName();

    /**
     * @return string
     */
    abstract protected function GetReservationAction();

    public function PageLoad()
    {
        $this->presenter = $this->GetPresenter();
        $this->presenter->PageLoad();

        $this->Set('ReturnUrl', $this->GetReturnUrl());
        $this->Set('ReservationAction', $this->GetReservationAction());
        $this->Set('MaxUploadSize', UploadedFile::GetMaxSize());
        $this->Set('MaxUploadCount', UploadedFile::GetMaxUploadCount());
        $config = Configuration::Instance();
        $this->Set('UploadsEnabled', $config->GetKey(ConfigKeys::UPLOAD_RESERVATION_ATTACHMENTS_ENABLED, new BooleanConverter()));
        $this->Set('AllowParticipation', !$config->GetKey(ConfigKeys::RESERVATION_PREVENT_PARTICIPATION, new BooleanConverter()));
        $this->Set('AllowGuestParticipation', $config->GetKey(ConfigKeys::RESERVATION_ALLOW_GUEST_PARTICIPATION, new BooleanConverter()));
        $remindersEnabled = $config->GetKey(ConfigKeys::RESERVATION_REMINDERS_ENABLED, new BooleanConverter());
        $emailEnabled = $config->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter());
        $this->Set('RemindersEnabled', $remindersEnabled && $emailEnabled);

        $this->Set('RepeatEveryOptions', range(1, 20));
        $this->Set(
            'RepeatOptions',
            [
                'none' => ['key' => 'DoesNotRepeat', 'everyKey' => ''],
                'daily' => ['key' => 'Daily', 'everyKey' => 'days'],
                'weekly' => ['key' => 'Weekly', 'everyKey' => 'weeks'],
                'monthly' => ['key' => 'Monthly', 'everyKey' => 'months'],
                'yearly' => ['key' => 'Yearly', 'everyKey' => 'years'],
                'custom' => ['key' => 'Custom', 'everyKey' => ''],
            ]
        );
        $this->Set(
            'DayNames',
            [
                0 => 'DaySundayAbbr',
                1 => 'DayMondayAbbr',
                2 => 'DayTuesdayAbbr',
                3 => 'DayWednesdayAbbr',
                4 => 'DayThursdayAbbr',
                5 => 'DayFridayAbbr',
                6 => 'DaySaturdayAbbr',
            ]
        );

        $this->Set('TitleRequired', $config->GetKey(ConfigKeys::RESERVATION_TITLE_REQUIRED, new BooleanConverter()));
        $this->Set('DescriptionRequired', $config->GetKey(ConfigKeys::RESERVATION_DESCRIPTION_REQUIRED, new BooleanConverter()));

        $this->Set('CreditsEnabled', $config->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));

        if ($this->IsUnavailable()) {
            $this->RedirectToError(ErrorMessages::RESERVATION_NOT_AVAILABLE);
            return;
        }

        $this->SetReservationPdfConfig();
        $this->Display($this->GetTemplateName());
    }

    private function SetReservationPdfConfig(): void
    {
        $repeatOptions = $this->SmartyVar('RepeatOptions', []);
        $repeatType = $this->SmartyVar('RepeatType', RepeatType::None);
        $repeatMonthlyType = $this->SmartyVar('RepeatMonthlyType', '');
        $showReservationDetails = (bool)$this->SmartyVar('ShowReservationDetails', false);
        $showParticipation = (bool)$this->SmartyVar('ShowParticipation', false);
        $accessories = $this->SmartyVar('Accessories', []);
        $participants = $this->SmartyVar('Participants', []);
        $invitees = $this->SmartyVar('Invitees', []);
        $attachments = $this->SmartyVar('Attachments', []);
        $repeatWeekdays = $this->SmartyVar('RepeatWeekdays', []);
        $customRepeatDates = $this->SmartyVar('CustomRepeatDates', []);
        $dayNames = Resources::GetInstance()->GetDays('full');
        $reminders = [];

        if ($this->SmartyVar('ReminderTimeStart', '') !== '') {
            $reminders[] = [
                'time' => (string)$this->SmartyVar('ReminderTimeStart'),
                'interval' => $this->Translate($this->SmartyVar('ReminderIntervalStart', '')),
                'text' => $this->Translate('ReminderBeforeStart'),
            ];
        }

        if ($this->SmartyVar('ReminderTimeEnd', '') !== '') {
            $reminders[] = [
                'time' => (string)$this->SmartyVar('ReminderTimeEnd'),
                'interval' => $this->Translate($this->SmartyVar('ReminderIntervalEnd', '')),
                'text' => $this->Translate('ReminderBeforeEnd'),
            ];
        }

        $config = [
            'appTitle' => $this->SmartyVar('AppTitle', ''),
            'reservationDetailsTitle' => $this->SmartyCapitalize($this->Translate('ReservationDetails')),
            'referenceNumberLabel' => $this->Translate('ReferenceNumber'),
            'referenceNumber' => $this->SmartyVar('ReferenceNumber', ''),
            'userLabel' => $this->Translate('User'),
            'reservationUserName' => $this->DecodeHtml($this->SmartyVar('ReservationUserName', '')),
            'showUserDetailsAndReservationDetails' => (bool)$this->SmartyVar('ShowUserDetails', false) && $showReservationDetails,

            'beginDateLabel' => $this->Translate('BeginDate'),
            'beginDateValue' => $this->FormatPdfDate($this->SmartyVar('StartDate'), 'dashboard'),
            'endDateLabel' => $this->Translate('EndDate'),
            'endDateValue' => $this->FormatPdfDate($this->SmartyVar('EndDate'), 'dashboard'),
            'reservationLengthLabel' => $this->Translate('ReservationLength'),
            'repeatPromptLabel' => $this->Translate('RepeatPrompt'),
            'isRecurring' => (bool)$this->SmartyVar('IsRecurring', false),
            'isCustomRepeat' => $repeatType === RepeatType::Custom,
            'repeatTypeLabel' => $this->Translate($repeatOptions[$repeatType]['key'] ?? ''),
            'repeatInterval' => (string)$this->SmartyVar('RepeatInterval', ''),
            'repeatEveryLabel' => $repeatType === RepeatType::Custom ? '' : $this->Translate($repeatOptions[$repeatType]['everyKey'] ?? ''),
            'repeatOnLabel' => $this->Translate('RepeatOn'),
            'typeLabel' => $this->Translate('Type'),
            'repeatMonthlyTypeLabel' => $repeatMonthlyType === '' ? '' : $this->Translate($repeatMonthlyType === RepeatMonthlyType::DayOfMonth ? 'repeatDayOfMonth' : 'repeatDayOfWeek'),
            'daysLabel' => $this->SmartyCapitalize($this->Translate('RepeatDaysPrompt')),
            'repeatWeekdays' => array_map(
                fn ($day) => $dayNames[$day] ?? '',
                is_array($repeatWeekdays) ? $repeatWeekdays : []
            ),
            'repeatCustomDates' => array_map(
                fn ($date) => $this->FormatPdfDate($date, 'schedule_daily', $this->SmartyVar('Timezone')),
                is_array($customRepeatDates) ? $customRepeatDates : []
            ),
            'repeatUntilPromptLabel' => $this->Translate('RepeatUntilPrompt'),
            'repeatUntilDate' => $this->FormatPdfDate($this->SmartyVar('RepeatTerminationDate'), 'dashboard'),

            'additionalAttributesLabel' => $this->Translate('AdditionalAttributes'),
            'customAttributeTypeCheckbox' => CustomAttributeTypes::CHECKBOX,

            'resourcesHeaderLabel' => $this->Translate('Resources'),
            'requiresApprovalLabel' => $this->Translate('RequiresApproval'),
            'requiresCheckInNotificationLabel' => $this->Translate('RequiresCheckInNotification'),
            'releasedInLabel' => sprintf('%s (%s)', $this->Translate('ReleasedIn'), $this->Translate('minutes')),
            'resources' => $this->PdfResources(),

            'showAccessories' => $showReservationDetails && count($accessories) > 0,
            'accessoriesHeaderLabel' => $this->Translate('Accessories'),
            'quantityLabel' => $this->Translate('Quantity'),
            'accessories' => array_map(
                fn ($accessory) => [
                    'name' => (string)$accessory->Name,
                    'quantity' => (string)$accessory->QuantityReserved,
                ],
                $accessories
            ),

            'showParticipants' => $showReservationDetails && $showParticipation && count($participants) > 0,
            'participantsHeaderLabel' => $this->Translate('Participants'),
            'emailLabel' => $this->Translate('Email'),
            'participants' => $this->PdfUsers($participants),

            'showInvitees' => $showReservationDetails && $showParticipation && count($invitees) > 0,
            'invitationListHeaderLabel' => $this->Translate('InvitationList'),
            'invitees' => $this->PdfUsers($invitees),

            'reservationTitleLabel' => $this->Translate('ReservationTitle'),
            'reservationTitle' => $this->DecodeHtml($this->SmartyVar('ReservationTitle', '')),
            'reservationDescriptionLabel' => $this->Translate('ReservationDescription'),
            'reservationDescription' => $this->DecodeHtml($this->SmartyVar('Description', '')),

            'remindersEnabled' => (bool)$this->SmartyVar('RemindersEnabled', false),
            'sendReminderLabel' => $this->Translate('SendReminder'),
            'reminders' => $reminders,

            'attachmentsLabel' => $this->Translate('Attachments'),
            'attachments' => array_map(
                fn ($attachment) => $attachment->FileName(),
                $attachments
            ),

            'showTermsAcceptance' => $this->SmartyVar('Terms') !== null && (bool)$this->SmartyVar('TermsAccepted', false),
            'acceptTermsLabel' => sprintf('%s %s', $this->Translate('IAccept'), $this->Translate('TheTermsOfService')),

            'logoUrl' => sprintf('%s/img/%s', $this->SmartyVar('ScriptUrl', ''), $this->SmartyVar('LogoUrl', '')),
        ];

        $this->Set('ReservationPdfConfigJson', json_encode(
            $config,
            // Keep the inline script assignment safe and fail fast if encoding ever breaks.
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ));
    }

    private function SmartyVar(string $name, mixed $default = null): mixed
    {
        // Bridge existing presenter-to-template assignments while building the PDF config in PHP.
        $value = $this->smarty->getTemplateVars($name);
        return $value === null ? $default : $value;
    }

    private function Translate(string $key): string
    {
        if ($key === '') {
            return '';
        }

        return Resources::GetInstance()->GetString($key, '');
    }

    private function SmartyCapitalize(string $value): string
    {
        $capitalize = $this->smarty->getModifierCallback('capitalize');

        return $capitalize === null ? $value : (string)$capitalize($value);
    }

    private function DecodeHtml(mixed $value): string
    {
        return html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5);
    }

    private function FormatPdfDate(mixed $date, string $key, ?string $timezone = null): string
    {
        $params = [
            'date' => $date,
            'key' => $key,
        ];

        if ($timezone !== null) {
            $params['timezone'] = $timezone;
        }

        return $this->smarty->FormatDate($params, $this->smarty);
    }

    private function PdfResources(): array
    {
        $resources = [];
        $primaryResource = $this->SmartyVar('Resource');

        if ($primaryResource !== null) {
            $resources[] = $this->PdfResource($primaryResource, '');
        }

        $additionalResourceIds = $this->SmartyVar('AdditionalResourceIds', []);
        $availableResources = $this->SmartyVar('AvailableResources', []);

        foreach ($availableResources as $resource) {
            if (in_array($resource->Id, is_array($additionalResourceIds) ? $additionalResourceIds : [])) {
                $resources[] = $this->PdfResource($resource, ' - ');
            }
        }

        return $resources;
    }

    private function PdfResource(IBookableResource $resource, string $emptyReleasedIn): array
    {
        return [
            'name' => $resource->GetName(),
            'requiresApproval' => $resource->GetRequiresApproval(),
            'requiresCheckIn' => $resource->IsCheckInEnabled(),
            'releasedIn' => $resource->IsAutoReleased() ? (string)$resource->GetAutoReleaseMinutes() : $emptyReleasedIn,
        ];
    }

    private function PdfUsers(array $users): array
    {
        return array_map(
            fn ($user) => [
                'fullName' => $this->DecodeHtml($user->FullName),
                'email' => (string)$user->Email,
            ],
            $users
        );
    }

    public function BindPeriods($startPeriods, $endPeriods, $lockPeriods)
    {
        $this->Set('StartPeriods', $startPeriods);
        $this->Set('EndPeriods', $endPeriods);
        $this->Set('LockPeriods', $lockPeriods);
    }

    public function BindAvailableResources($resources)
    {
        $this->Set('AvailableResources', $resources);
    }

    public function ShowAdditionalResources($shouldShow)
    {
        $this->Set('ShowAdditionalResources', $shouldShow);
    }

    public function BindAvailableAccessories($accessories)
    {
        $this->Set('AvailableAccessories', $accessories);
    }

    public function BindResourceGroups($groups)
    {
        $this->Set('ResourceGroupsAsJson', json_encode($groups->GetGroups()));
    }

    public function SetSelectedStart(SchedulePeriod $selectedStart, Date $startDate)
    {
        $this->Set('SelectedStart', $selectedStart);
        $this->Set('StartDate', $startDate);
    }

    public function SetSelectedEnd(SchedulePeriod $selectedEnd, Date $endDate)
    {
        $this->Set('SelectedEnd', $selectedEnd);
        $this->Set('EndDate', $endDate);
    }

    public function SetReservationUser(UserDto $user)
    {
        $this->Set('ReservationUserName', $user->FullName());
        $this->Set('UserId', $user->Id());
        $this->Set('CurrentUserCredits', $user->CurrentCreditCount());
    }

    public function SetReservationResource($resource)
    {
        $this->Set('ResourceName', $resource->GetName());
        $this->Set('ResourceId', $resource->GetId());
        $this->Set('Resource', $resource);
    }

    public function SetScheduleId($scheduleId)
    {
        $this->Set('ScheduleId', $scheduleId);
    }

    public function SetRepeatTerminationDate($repeatTerminationDate)
    {
        $this->Set('RepeatTerminationDate', $repeatTerminationDate);
    }

    public function SetParticipants($participants)
    {
        $this->Set('Participants', $participants);
    }

    public function SetInvitees($invitees)
    {
        $this->Set('Invitees', $invitees);
    }

    public function SetAllowParticipantsToJoin($allowParticipantsToJoin)
    {
        $this->Set('AllowParticipantsToJoin', $allowParticipantsToJoin);
    }

    public function SetAccessories($accessories)
    {
        $this->Set('Accessories', $accessories);
    }

    public function SetAttachments($attachments)
    {
        $this->Set('Attachments', $attachments);
    }

    public function SetCanChangeUser($canChangeUser)
    {
        $this->Set('CanChangeUser', $canChangeUser);
    }

    public function ShowUserDetails($canShowUserDetails)
    {
        $this->Set('ShowUserDetails', $canShowUserDetails);
    }

    public function SetShowParticipation($shouldShow)
    {
        $this->Set('ShowParticipation', $shouldShow);
    }

    public function ShowReservationDetails($showReservationDetails)
    {
        $this->Set('ShowReservationDetails', $showReservationDetails);
    }

    public function HideRecurrence($isHidden)
    {
        $this->Set('HideRecurrence', $isHidden);
    }

    protected function GetReturnUrl()
    {
        $redirect = $this->GetQuerystring(QueryStringKeys::REDIRECT);
        if (!empty($redirect)) {
            return $redirect;
        }
        return $this->GetLastPage(Pages::SCHEDULE);
    }

    protected function LoadInitializerFactory()
    {
        $userRepository = new UserRepository();
        return new ReservationInitializerFactory(
            new ScheduleRepository(),
            $userRepository,
            new ResourceService(
                new ResourceRepository(),
                $this->permissionServiceFactory->GetPermissionService(),
                new AttributeService(new AttributeRepository()),
                $userRepository,
                new AccessoryRepository()
            ),
            new ReservationAuthorization(AuthorizationServiceFactory::GetAuthorizationService())
        );
    }

    public function SetStartReminder($reminderValue, $reminderInterval)
    {
        $this->Set('ReminderTimeStart', $reminderValue);
        $this->Set('ReminderIntervalStart', $reminderInterval);
    }

    public function SetEndReminder($reminderValue, $reminderInterval)
    {
        $this->Set('ReminderTimeEnd', $reminderValue);
        $this->Set('ReminderIntervalEnd', $reminderInterval);
    }

    public function SetAvailability(DateRange $availability)
    {
        $this->Set('AvailabilityStart', $availability->GetBegin());
        $this->Set('AvailabilityEnd', $availability->GetEnd());
    }

    public function SetFirstWeekday($weekday)
    {
        $this->Set('FirstWeekday', $weekday);
    }

    public function MakeUnavailable()
    {
        $this->available = false;
    }

    public function IsUnavailable()
    {
        return !$this->available;
    }

    public function SetTerms($termsOfService)
    {
        $this->Set('Terms', $termsOfService);
    }

    public function SetMaximumResources($maximum)
    {
        $this->Set('MaximumResources', $this->server->GetUserSession()->IsAdmin ? 0 : $maximum);
    }
}
