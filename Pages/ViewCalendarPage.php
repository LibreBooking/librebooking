<?php

require_once(ROOT_DIR . 'Pages/CalendarPage.php');
require_once(ROOT_DIR . 'Presenters/Calendar/CalendarPresenter.php');
require_once(ROOT_DIR . 'lib/Application/Authorization/GuestPermissionServiceFactory.php');

class ViewCalendarPage extends CalendarPage
{
    public function __construct()
    {
        parent::__construct();

        $resourceRepository = new ResourceRepository();
        $scheduleRepository = new ScheduleRepository();
        $userRepository = new UserRepository();
        $resourceService = new ResourceService(
            $resourceRepository,
            new GuestPermissionService(),
            new AttributeService(new AttributeRepository()),
            $userRepository,
            new AccessoryRepository()
        );
        $subscriptionService = new CalendarSubscriptionService($userRepository, $resourceRepository, $scheduleRepository);
        $privacyFilter = new PrivacyFilter(new ReservationAuthorization(PluginManager::Instance()->LoadAuthorization()));

        $viewReservations = Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY, ConfigKeys::PRIVACY_VIEW_RESERVATIONS, new BooleanConverter());
        $allowGuestBookings = Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY, ConfigKeys::PRIVACY_ALLOW_GUEST_BOOKING, new BooleanConverter());
        $factory = ($viewReservations || $allowGuestBookings) ? new SlotLabelFactory() : new NullSlotLabelFactory();

        $this->presenter = new CalendarPresenter(
            $this,
            new ReservationViewRepository(),
            $scheduleRepository,
            new UserRepository(),
            $resourceService,
            $subscriptionService,
            $privacyFilter,
            $factory
        );
    }

    public function DisplayPage()
    {
        URIScriptValidator::validate($_SERVER['REQUEST_URI'], '/view-calendar.php');
        ParamsValidator::validate(RouteParamsKeys::VIEW_CALENDAR, $_SERVER['REQUEST_URI'], '/view-calendar.php', true);
        
        $this->Set('pageUrl', Pages::VIEW_CALENDAR);
        $this->Set('CreateReservationPage', Pages::GUEST_RESERVATION);
        $this->Set('HideCreate', !Configuration::Instance()->GetSectionKey(ConfigSection::PRIVACY, ConfigKeys::PRIVACY_ALLOW_GUEST_BOOKING, new BooleanConverter()));
        parent::DisplayPage();
    }

    public function RenderSubscriptionDetails()
    {
    }
}
