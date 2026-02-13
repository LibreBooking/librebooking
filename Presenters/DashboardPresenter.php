<?php

require_once(ROOT_DIR . 'lib/Config/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

require_once(ROOT_DIR . 'Controls/Dashboard/AnnouncementsControl.php');
require_once(ROOT_DIR . 'Controls/Dashboard/UpcomingReservations.php');
require_once(ROOT_DIR . 'Controls/Dashboard/ResourceAvailabilityControl.php');
require_once(ROOT_DIR . 'Controls/Dashboard/PastReservations.php');

class DashboardPresenter
{
    private $_page;

    public function __construct(IDashboardPage $page)
    {
        $this->_page = $page;
    }

    public function Initialize()
    {
        $announcement = new AnnouncementsControl(new SmartyPage());
        $pastReservations = new PastReservations(new SmartyPage());
        $upcomingReservations = new UpcomingReservations(new SmartyPage());
        $availability = new ResourceAvailabilityControl(new SmartyPage());

        $this->_page->AddItem($announcement);
        $this->_page->AddItem($pastReservations);
        $this->_page->AddItem($upcomingReservations);
        $this->_page->AddItem($availability);

        if (ServiceLocator::GetServer()->GetUserSession()->IsAdmin /*|| ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin || ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin*/) {
            $allUpcomingReservations = new AllUpcomingReservations(new SmartyPage());
            $this->_page->AddItem($allUpcomingReservations);
        }

        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin || ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin) {
            $myGroupReservations = new GroupUpcomingReservations(new SmartyPage());
            $this->_page->AddItem($myGroupReservations);
        }

        //Only Resource Admins or App Admins can accept or reject pending reservations
        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin || ServiceLocator::GetServer()->GetUserSession()->IsAdmin) {
            $pendingApprovalReservations = new PendingApprovalReservations(new SmartyPage());
            $this->_page->AddItem($pendingApprovalReservations);
        }

        if (ServiceLocator::GetServer()->GetUserSession()->IsResourceAdmin || ServiceLocator::GetServer()->GetUserSession()->IsScheduleAdmin || ServiceLocator::GetServer()->GetUserSession()->IsAdmin) {
            $missingCheckInOutReservations = new MissingCheckInOutReservations(new SmartyPage());
            $this->_page->AddItem($missingCheckInOutReservations);
        }
    }
}
