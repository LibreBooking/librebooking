<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageQuotasPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

interface IManageQuotasPage extends IActionPage
{
    /**
     * @param array|BookableResource[] $resources
     * @return void
     */
    public function BindResources($resources);

    /**
     * @param array|GroupItemView[] $groups
     * @return void
     */
    public function BindGroups($groups);

    /**
     * @param array|Schedule[] $schedules
     * @return void
     */
    public function BindSchedules($schedules);

    /**
     * @param array|QuotaItemView[] $quotas
     * @return void
     */
    public function BindQuotas($quotas);

    /**
     * @return string
     */
    public function GetDuration();

    /**
     * @return float
     */
    public function GetLimit();

    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return int
     */
    public function GetGroupId();

    /**
     * @return string
     */
    public function GetUnit();

    /**
     * @return int
     */
    public function GetQuotaId();

    /**
     * @return int
     */
    public function GetScheduleId();

    /**
     * @return bool
     */
    public function GetEnforcedAllDay();

    /**
     * @return string
     */
    public function GetEnforcedStartTime();

    /**
     * @return string
     */
    public function GetEnforcedEndTime();

    /**
     * @return bool
     */
    public function GetEnforcedEveryDay();

    /**
     * @return array
     */
    public function GetEnforcedDays();

    /**
     * @return string
     */
    public function GetScope();
}

class ManageQuotasPage extends ActionPage implements IManageQuotasPage
{
    /**
     * @var \ManageQuotasPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageQuotas', 1);
        $this->presenter = new ManageQuotasPresenter(
            $this,
            new ResourceRepository(),
            new GroupRepository(),
            new ScheduleRepository(),
            new QuotaRepository()
        );
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();

        $this->Set('DayNames', [
            0 => 'DaySundayAbbr',
            1 => 'DayMondayAbbr',
            2 => 'DayTuesdayAbbr',
            3 => 'DayWednesdayAbbr',
            4 => 'DayThursdayAbbr',
            5 => 'DayFridayAbbr',
            6 => 'DaySaturdayAbbr',
        ]);
        $this->Set('TimeFormat', Resources::GetInstance()->GetDateFormat('period_time'));

        $this->Display('Admin/manage_quotas.tpl');
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function SetJsonResponse($response)
    {
        parent::SetJson($response);
    }

    /**
     * @param array|BookableResource[] $resources
     * @return void
     */
    public function BindResources($resources)
    {
        $this->Set('Resources', $resources);
    }

    /**
     * @param array|GroupItemView[] $groups
     * @return void
     */
    public function BindGroups($groups)
    {
        $this->Set('Groups', $groups);
    }


    /**
     * @param array|Schedule[] $schedules
     * @return void
     */
    public function BindSchedules($schedules)
    {
        $this->Set('Schedules', $schedules);
    }

    /**
     * @param array|QuotaItemView[] $quotas
     * @return void
     */
    public function BindQuotas($quotas)
    {
        $this->Set('Quotas', $quotas);
    }

    /**
     * @return string
     */
    public function GetDuration()
    {
        return $this->GetForm(FormKeys::DURATION);
    }

    /**
     * @return string
     */
    public function GetLimit()
    {
        return $this->GetForm(FormKeys::LIMIT);
    }

    /**
     * @return int
     */
    public function GetResourceId()
    {
        return $this->GetForm(FormKeys::RESOURCE_ID);
    }

    /**
     * @return int
     */
    public function GetGroupId()
    {
        return $this->GetForm(FormKeys::GROUP);
    }

    /**
     * @return string
     */
    public function GetUnit()
    {
        return $this->GetForm(FormKeys::UNIT);
    }

    /**
     * @return int
     */
    public function GetQuotaId()
    {
        return $this->GetQuerystring(QueryStringKeys::QUOTA_ID);
    }

    /**
     * @return int
     */
    public function GetScheduleId()
    {
        return $this->GetForm(FormKeys::SCHEDULE_ID);
    }

    public function ProcessDataRequest($dataRequest)
    {
        // no-op
    }

    /**
     * @return bool
     */
    public function GetEnforcedAllDay()
    {
        $allDay = $this->GetForm(FormKeys::ENFORCE_ALL_DAY);

        return !empty($allDay);
    }

    /**
     * @return string
     */
    public function GetEnforcedStartTime()
    {
        return $this->GetForm(FormKeys::BEGIN_TIME);
    }

    /**
     * @return string
     */
    public function GetEnforcedEndTime()
    {
        return $this->GetForm(FormKeys::END_TIME);
    }

    /**
     * @return bool
     */
    public function GetEnforcedEveryDay()
    {
        $everyDay = $this->GetForm(FormKeys::ENFORCE_EVERY_DAY);

        return !empty($everyDay);
    }

    /**
     * @return array
     */
    public function GetEnforcedDays()
    {
        $days = $this->GetForm(FormKeys::DAY);

        if (empty($days) || !is_array($days)) {
            return [];
        }

        return $days;
    }

    /**
     * @return string
     */
    public function GetScope()
    {
        return $this->GetForm(FormKeys::QUOTA_SCOPE);
    }
}
