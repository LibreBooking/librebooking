<?php

require_once(ROOT_DIR . 'Pages/IPageable.php');
require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class QuartzyImportPage extends ActionPage
{
    /**
     * @var QuartzyImportPresenter
     */
    public $presenter;

    public function __construct()
    {
        parent::__construct('Import', 1);
        $this->presenter = new QuartzyImportPresenter($this);
    }

    /**
     * @return void
     */
    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    /**
     * @param $dataRequest string
     * @return void
     */
    public function ProcessDataRequest($dataRequest)
    {
        // TODO: Implement ProcessDataRequest() method.
    }

    /**
     * @return void
     */
    public function ProcessPageLoad()
    {
        $this->Display('Admin/Import/quartzy_import.tpl');
    }

    public function GetImportFile()
    {
        return $this->server->GetFile('quartzyFile');
    }

    public function GetIncludeBookings()
    {
        $include = $this->GetForm('includeBookings');

        return !empty($include);
    }
}

class QuartzyImportPresenter extends ActionPresenter
{
    /**
     * @var QuartzyImportPage
     */
    private $page;
    private $defaultScheduleId = null;
    private $schedules = [];
    private $resources = [];
    private $groupRepository;
    private $userRepository;
    private $resourceRepository;
    private $reservationRepository;
    private $scheduleRepository;

    public function __construct(QuartzyImportPage $page)
    {
        $this->page = $page;
        parent::__construct($page);

        $this->AddAction('importQuartzy', 'Import');

        $this->groupRepository = new GroupRepository();
        $this->userRepository = new UserRepository();
        $this->reservationRepository = new ReservationRepository();
        $this->resourceRepository = new ResourceRepository();
        $this->scheduleRepository = new ScheduleRepository();
    }

    public function Import()
    {
        set_time_limit(12000);

        $file = $this->page->GetImportFile();

        $error = $file->Error();
        if (!empty($error)) {
            die($error);
        }
        $zip = new ZipArchive();

        if ($zip->open($file->TemporaryName()) === true) {
            $extractDirectory = str_replace('.tmp', '', $file->TemporaryName()) . 'zip';
            $zip->extractTo($extractDirectory);
            $zip->close();
            $this->LoadData($extractDirectory);

            $this->removeDirectory($extractDirectory);
        } else {
            die('Could not extract your Quartzy file.');
        }
    }

    private function removeDirectory($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

    protected function LoadValidators($action)
    {
        $this->page->RegisterValidator('fileExtensionValidator', new FileExtensionValidator('zip', $this->page->GetImportFile()));
    }

    private function LoadData($extractDirectory)
    {
        $directories = array_diff(scandir($extractDirectory), ['..', '.']);
        if (count($directories) == 0) {
            echo('No data found in your Quartzy file.');
            return;
        }

        foreach ($directories as $directory) {
            try {
                $this->AddSchedule($directory, $extractDirectory);
            } catch (Exception $ex) {
                Log::Error('Could not import schedule %s', $ex);
            }
        }
    }

    private function AddSchedule($scheduleName, $rootDir)
    {
        if ($this->defaultScheduleId == null) {
            $this->schedules = $this->scheduleRepository->GetAll();
            /** @var Schedule $schedule */
            foreach ($this->schedules as $schedule) {
                if ($schedule->GetIsDefault()) {
                    $this->defaultScheduleId = $schedule->GetId();
                    break;
                }
            }
        }

        $scheduleId = 0;
        foreach ($this->schedules as $schedule) {
            if ($schedule->GetName() == $scheduleName) {
                Log::Debug('QuartzyImport Schedule already exists %s', $scheduleName);
                $scheduleId = $schedule->GetId();
                break;
            }
        }

        if ($scheduleId == 0) {
            Log::Debug('QuartzyImport Schedule does not exist %s', $scheduleName);
            $scheduleId = $this->scheduleRepository->Add(new Schedule(0, htmlspecialchars($scheduleName), false, 0, 7), $this->defaultScheduleId);
        }

        $this->AddEquipment($scheduleId, $rootDir . '/' . $scheduleName);
    }

    private function AddEquipment($scheduleId, $scheduleDirectory)
    {
        $lines = $this->GetCsvData($scheduleDirectory . '/Equipment.csv');
        for ($i = 1; $i < count($lines); $i++) {
            $line = $lines[$i];
            try {
                $this->AddResource($line, $scheduleId, $scheduleDirectory);
            } catch (Exception $ex) {
                Log::Error('Could not import resource %s', $ex);
            }
        }
    }

    private function GetCsvData($path)
    {
        $lines = [];
        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $lines[] = $data;
            }
            fclose($handle);
        }

        return $lines;
    }

    private function AddResource($resourceData, $scheduleId, $scheduleDirectory)
    {
        if (empty($this->resources)) {
            $this->resources = $this->resourceRepository->GetList(null, null)->Results();
        }

        $name = $this->CleanName($resourceData[0]);
        $description = $resourceData[1];
        $location = $resourceData[2];
        $url = $resourceData[3];
        $managedBy = $resourceData[4];
        $favorited = $resourceData[5];
        $enabled = $resourceData[6];

        $resourceId = 0;
        /** @var BookableResource $resource */
        foreach ($this->resources as $resource) {
            if ($resource->GetName() == $name) {
                $resourceId = $resource->GetId();
                break;
            }
        }

        if ($resourceId == 0) {
            $adminGroupId = $this->AddResourceAdmin($managedBy, $name);
            $resource = new BookableResource(
                0,
                htmlspecialchars($name),
                htmlspecialchars($location),
                null,
                $url,
                null,
                null,
                true,
                false,
                true,
                null,
                null,
                null,
                $description,
                $scheduleId,
                $adminGroupId
            );
            if ($enabled != 'YES') {
                $resource->ChangeStatus(ResourceStatus::UNAVAILABLE);
            }
            $resourceId = $this->resourceRepository->Add($resource);
        }

        $this->AddReservations($resourceId, $scheduleDirectory . '/Equipment/' . $name);
    }

    private function AddReservations($resourceId, $resourceDirectory)
    {
        if (!$this->page->GetIncludeBookings()) {
            return;
        }

        ServiceLocator::GetDatabase()
                      ->Execute(new AdHocCommand('delete rs from reservation_series rs inner join reservation_resources rr on rs.series_id = rr.series_id where rr.resource_id = ' . $resourceId));
        $lines = $this->GetCsvData($resourceDirectory . '/Booking Calendar.csv');
        for ($i = 1; $i < count($lines); $i++) {
            $line = $lines[$i];
            try {
                $this->AddReservation($line, $resourceId);
            } catch (Exception $ex) {
                Log::Error('Could not import reservation %s', $ex);
            }
        }
    }

    private function AddResourceAdmin($emailAddress, $resourceName)
    {
        $adminGroupName = "$resourceName Admins";
        $userId = $this->AddUser($emailAddress);

        $groups = $this->groupRepository->GetGroupsByRole(RoleLevel::RESOURCE_ADMIN);

        $adminGroup = null;
        foreach ($groups as $group) {
            if ($group->Name() == $adminGroupName) {
                $adminGroup = $group;
                break;
            }
        }

        if ($adminGroup == null) {
            $adminGroup = new Group(0, htmlspecialchars($adminGroupName));
            $adminGroup->ChangeRoles([RoleLevel::RESOURCE_ADMIN]);
            $id = $this->groupRepository->Add($adminGroup);
            $adminGroup->WithId($id);
        }

        if (!$adminGroup->HasMember($userId) || in_array(RoleLevel::RESOURCE_ADMIN, $adminGroup->RoleIds())) {
            $adminGroup->ChangeRoles([RoleLevel::RESOURCE_ADMIN]);
            $adminGroup->AddUser($userId);
            $this->groupRepository->Update($adminGroup);
        }

        return $adminGroup->Id();
    }

    private function AddUser($email, $firstName = '', $lastName = '')
    {
        $user = $this->userRepository->LoadByUsername($email);

        $userId = $user->Id();
        if (empty($userId)) {
            $enc = new PasswordEncryption();
            $password = $enc->EncryptPassword('p@ssw0rd!');
            $userId = $this->userRepository->Add(User::Create(
                htmlspecialchars($firstName),
                htmlspecialchars($lastName),
                htmlspecialchars($email),
                htmlspecialchars($email),
                'en_us',
                Configuration::Instance()->GetDefaultTimezone(),
                $password->EncryptedPassword(),
                $password->Salt()
            ));
        }

        return $userId;
    }

    private function AddReservation($line, $resourceId)
    {
        //Log::Debug('Adding reservation %s', var_export($line, true));
        $resource = $this->resourceRepository->LoadById($resourceId);

        $name = $line[0];
        $email = $line[1];
        $starts = $line[2];
        $ends = $line[3];
        $created = $line[4];
        $note = $line[5];
        $adminNote = $line[6];

        $startDate = Date::ParseExact($starts);
        $endDate = Date::ParseExact($ends);

        $nameParts = explode(' ', $name, 2);
        if (count($nameParts) != 2) {
            $nameParts = ['', ''];
        }
        $userId = $this->AddUser($email, $nameParts[0], $nameParts[1]);

        $series = ReservationSeries::Create(
            $userId,
            $resource,
            htmlspecialchars($name),
            htmlspecialchars($note),
            new DateRange($startDate, $endDate),
            new RepeatNone(),
            ServiceLocator::GetServer()->GetUserSession()
        );
        $this->reservationRepository->Add($series);
    }

    private function CleanName($name)
    {
        return str_replace('*', '_', str_replace('/', '_', str_replace('.', '_', $name)));
    }
}
