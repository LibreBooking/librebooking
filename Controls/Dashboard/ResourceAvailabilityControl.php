<?php

require_once(ROOT_DIR . 'Presenters/Dashboard/ResourceAvailabilityControlPresenter.php');
require_once(ROOT_DIR . 'Controls/Dashboard/DashboardItem.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

interface IResourceAvailabilityControl
{
    /**
     * @param AvailableDashboardItem[] $items
     */
    public function SetAvailable($items);

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailable($items);

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailableAllDay($items);

    /**
     * @param Schedule[] $schedules
     */
    public function SetSchedules($schedules);
}

class AvailableDashboardItem
{
    /**
     * @var ResourceDto $resource
     */
    private $resource;

    /**
     * @param ResourceDto $resource
     * @param ReservationItemView|null $next
     */
    public function __construct(ResourceDto $resource, private $next = null)
    {
        $this->resource = $resource;
    }

    /**
     * @return string
     */
    public function ResourceName()
    {
        return $this->resource->GetName();
    }

    /**
     * @return int
     */
    public function ResourceId()
    {
        return $this->resource->GetId();
    }

    /**
     * @return Date|null
     */
    public function NextTime()
    {
        if ($this->next != null) {
            return $this->next->StartDate;
        }

        return null;
    }

    /**
     * @return bool
     */
    public function HasColor()
    {
        if ($this->resource != null) {
            $color = $this->resource->GetColor();
            return !empty($color);
        }

        return false;
    }

    /**
     * @return string
     */
    public function GetTextColor()
    {
        if ($this->resource != null) {
            return $this->resource->GetTextColor();
        }

        return '';
    }

    /**
     * @return string
     */
    public function GetColor()
    {
        if ($this->resource != null) {
            return $this->resource->GetColor();
        }

        return '';
    }
}

class UnavailableDashboardItem
{
    /**
     * @var ResourceDto
     */
    private $resource;

    /**
     * @var ReservationItemView
     */
    private $currentReservation;

    public function __construct(ResourceDto $resource, ReservationItemView $currentReservation)
    {
        $this->resource = $resource;
        $this->currentReservation = $currentReservation;
    }

    /**
     * @return string
     */
    public function ResourceName()
    {
        return $this->resource->GetName();
    }

    /**
     * @return int
     */
    public function ResourceId()
    {
        return $this->resource->GetId();
    }

    /**
     * @return Date|null
     */
    public function ReservationEnds()
    {
        return $this->currentReservation->EndDate;
    }

    public function GetColor()
    {
        return $this->currentReservation->GetColor();
    }

    public function GetTextColor()
    {
        return $this->currentReservation->GetTextColor();
    }
}

class ResourceAvailabilityControl extends DashboardItem implements IResourceAvailabilityControl
{
    /**
     * @var ResourceAvailabilityControlPresenter
     */
    public $presenter;

    public function __construct(SmartyPage $smarty)
    {
        parent::__construct($smarty);

        $this->presenter = new ResourceAvailabilityControlPresenter(
            $this,
            new ResourceService(
                new ResourceRepository(),
                new SchedulePermissionService(PluginManager::Instance()->LoadPermission()),
                new AttributeService(new AttributeRepository()),
                new UserRepository(),
                new AccessoryRepository()
            ),
            new ReservationViewRepository(),
            new ScheduleRepository()
        );
    }

    public function PageLoad()
    {
        $userSession = ServiceLocator::GetServer()->GetUserSession();
        $this->Set('Timezone', $userSession->Timezone);

        $this->presenter->PageLoad($userSession);

        $this->Display('resource_availability.tpl');
    }


    public function SetAvailable($items)
    {
        $this->Assign('Available', $items);
    }

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailable($items)
    {
        $this->Assign('Unavailable', $items);
    }

    /**
     * @param UnavailableDashboardItem[] $items
     */
    public function SetUnavailableAllDay($items)
    {
        $this->Assign('UnavailableAllDay', $items);
    }

    public function SetSchedules($schedules)
    {
        $this->Assign('Schedules', $schedules);
    }
}
