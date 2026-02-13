<?php

require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Presenters/ViewSchedulesPresenter.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageSchedulesPresenter.php'); //ManageScheduleService

class ScheduleViewerViewSchedulesPage extends Page implements IPageable
{
    /**
     * @var ViewSchedulesPresenter
     */
    protected $presenter;

    protected $pageablePage;

    public function __construct()
    {
        parent::__construct('CheckSchedules');
        $resourceRepository = new ResourceRepository();

        $this->presenter = new ViewSchedulesPresenter(
            $this,
            $resourceRepository,
            new ManageScheduleService(new ScheduleRepository(), $resourceRepository),
            new GroupRepository()
        );

        $this->pageablePage = new PageablePage($this);
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad();

        $resources = Resources::GetInstance();
        $this->Set('DayNames', $resources->GetDays('full'));
        $this->Set('Today', Resources::GetInstance()->GetString('Today'));
        $this->Set('Months', Resources::GetInstance()->GetMonths('full'));
        $this->Set('StyleNames', [
            ScheduleStyle::Standard => $resources->GetString('Standard'),
            ScheduleStyle::Wide => $resources->GetString('Wide'),
            ScheduleStyle::Tall => $resources->GetString('Tall'),
            ScheduleStyle::CondensedWeek => $resources->GetString('Week'),
        ]);

        $this->Display(ROOT_DIR.'tpl/Admin/Schedules/view_schedules.tpl');
    }

    public function BindSchedules($schedules, $layouts, $sourceSchedules)
    {
        $this->Set('Schedules', $schedules);
        $this->Set('Layouts', $layouts);
        $this->Set('SourceSchedules', $sourceSchedules);
    }

    public function BindResources($resources)
    {
        $this->Set('Resources', $resources);
    }


    /**
     * @param GroupItemView[] $groups
     */
    public function BindGroups($groups)
    {
        $this->Set('AdminGroups', $groups);
        $groupLookup = [];
        foreach ($groups as $group) {
            $groupLookup[$group->Id] = $group;
        }
        $this->Set('GroupLookup', $groupLookup);
    }

    /**
     * @return int
     */
    public function GetPageNumber()
    {
        return $this->pageablePage->GetPageNumber();
    }

    /**
     * @return int
     */
    public function GetPageSize()
    {
        $pageSize = $this->pageablePage->GetPageSize();

        if ($pageSize > 10) {
            return 10;
        }
        return $pageSize;
    }

    /**
     * @param PageInfo $pageInfo
     * @return void
     */
    public function BindPageInfo(PageInfo $pageInfo)
    {
        $this->pageablePage->BindPageInfo($pageInfo);
    }
}
