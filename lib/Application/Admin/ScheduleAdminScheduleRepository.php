<?php

require_once(ROOT_DIR . 'Domain/Access/ScheduleRepository.php');

class ScheduleAdminScheduleRepository extends ScheduleRepository
{
    /**
     * @var IUserRepository
     */
    private $repo;

    /**
     * @var UserSession
     */
    private $user;

    public function __construct(IUserRepository $repo, UserSession $userSession)
    {
        $this->repo = $repo;
        $this->user = $userSession;
        parent::__construct();
    }

    public function GetAll()
    {
        $schedules = parent::GetAll();

        return $this->Filter($schedules);
    }

    public function Update(Schedule $schedule)
    {
        $user = $this->repo->LoadById($this->user->UserId);
        if (!$user->IsScheduleAdminFor($schedule)) {
            // if we got to this point, the user does not have the ability to update the schedule
            throw new Exception(sprintf('Schedule Update Failed. User %s does not have admin access to schedule %s.', $this->user->UserId, $schedule->GetId()));
        }

        parent::Update($schedule);
    }

    public function Add(Schedule $schedule, $copyLayoutFromScheduleId)
    {
        $user = $this->repo->LoadById($this->user->UserId);
        if (!$user->IsInRole(RoleLevel::SCHEDULE_ADMIN)) {
            throw new Exception(sprintf('Schedule Add Failed. User %s does not have admin access.', $this->user->UserId));
        }

        foreach ($user->Groups() as $group) {
            if ($group->IsScheduleAdmin) {
                $schedule->SetAdminGroupId($group->GroupId);
                break;
            }
        }

        return parent::Add($schedule, $copyLayoutFromScheduleId);
    }

    /**
     * @param Schedule[] $schedules
     * @return Schedule[]
     */
    private function Filter($schedules)
    {
        if ($this->user->IsAdmin) {
            return $schedules;
        }

        $user = $this->repo->LoadById($this->user->UserId);

        $filteredList = [];
        /** @var Schedule $schedule */
        foreach ($schedules as $schedule) {
            if ($user->IsScheduleAdminFor($schedule)) {
                $filteredList[] = $schedule;
            }
        }

        return $filteredList;
    }

    public function GetList($pageNumber, $pageSize, $sortField = null, $sortDirection = null, $filter = null)
    {
        $user = $this->repo->LoadById($this->user->UserId);

        if (!$user->IsInRole(RoleLevel::SCHEDULE_ADMIN)) {
            return new PageableData();
        }
        $ids = [];

        $filter = new SqlFilterNull();

        foreach ($user->Groups() as $group) {
            if ($group->IsScheduleAdmin) {
                $ids[] = $group->GroupId;
            }
        }

        $filter->_And(new SqlFilterIn(new SqlFilterColumn(TableNames::SCHEDULES_ALIAS, ColumnNames::RESOURCE_ADMIN_GROUP_ID), $ids));

        return parent::GetList($pageNumber, $pageSize, $sortField, $sortDirection, $filter);
    }
}
