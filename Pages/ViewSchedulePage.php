<?php

require_once(ROOT_DIR . 'Pages/SchedulePage.php');
require_once(ROOT_DIR . 'Presenters/Schedule/SchedulePresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/GuestPermissionServiceFactory.php');

class ViewSchedulePage extends SchedulePage
{
    private $userRepository;

    private $_styles = [
        ScheduleStyle::Wide => 'Schedule/schedule-days-horizontal.tpl',
        ScheduleStyle::Tall => 'Schedule/schedule-flipped.tpl',
        ScheduleStyle::CondensedWeek => 'Schedule/schedule-week-condensed.tpl',
    ];

    public function __construct()
    {
        parent::__construct();
        $scheduleRepository = new ScheduleRepository();
        $this->userRepository = new UserRepository();
        $resourceService = new ResourceService(
            new ResourceRepository(),
            new GuestPermissionService(),
            new AttributeService(new AttributeRepository()),
            $this->userRepository,
            new AccessoryRepository()
        );
        $pageBuilder = new SchedulePageBuilder();
        $reservationService = new ReservationService(new ReservationViewRepository(), new ReservationListingFactory());
        $dailyLayoutFactory = new DailyLayoutFactory();

        $this->_presenter = new SchedulePresenter(
            $this,
            new ScheduleService($scheduleRepository, $resourceService, $dailyLayoutFactory),
            $resourceService,
            $pageBuilder,
            $reservationService
        );
    }

    public function ProcessPageLoad()
    {

        // URIScriptValidator::validateOrRedirect($_SERVER['REQUEST_URI'], '/view-schedule.php');
        // ParamsValidator::validateOrRedirect(RouteParamsKeys::VIEW_SCHEDULE, $_SERVER['REQUEST_URI'], '/view-schedule.php', true);

        $user = new NullUserSession();
        $this->_presenter->PageLoad($user);

        $viewReservations = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter());
        $allowGuestBookings = Configuration::Instance()->GetKey(ConfigKeys::PRIVACY_ALLOW_GUEST_RESERVATIONS, new BooleanConverter());

        $this->Set('DisplaySlotFactory', new DisplaySlotFactory());
        $this->Set('SlotLabelFactory', $viewReservations || $allowGuestBookings ? new SlotLabelFactory($user) : new NullSlotLabelFactory());
        $this->Set('PopupMonths', $this->IsMobile ? 1 : 3);
        $this->Set('AllowGuestBooking', $allowGuestBookings);
        $this->Set('CreateReservationPage', Pages::GUEST_RESERVATION);
        $this->Set('LoadViewOnly', true);
        $this->Set('ShowSubscription', true);

        if ($this->IsMobile && !$this->IsTablet) {
            if ($this->ScheduleStyle == ScheduleStyle::Tall) {
                $this->Display('Schedule/schedule-flipped.tpl');
            } else {
                $this->Display('Schedule/schedule-mobile.tpl');
            }
        } else {
            if (array_key_exists($this->ScheduleStyle, $this->_styles)) {
                $this->Display($this->_styles[$this->ScheduleStyle]);
            } else {
                $this->Display('Schedule/schedule.tpl');
            }
        }
    }

    public function ShowInaccessibleResources()
    {
        return Configuration::Instance()->GetKey(ConfigKeys::SCHEDULE_SHOW_INACCESSIBLE_RESOURCES, new BooleanConverter());
    }

    protected function GetShouldAutoLogout()
    {
        return false;
    }

    public function GetDisplayTimezone(UserSession $user, Schedule $schedule)
    {
        $user->Timezone = $schedule->GetTimezone();
        return $schedule->GetTimezone();
    }
}
