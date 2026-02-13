<?php

define('ROOT_DIR', dirname(__FILE__) . '/../');

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Common/Helpers/namespace.php');

echo '<h1>LibreBooking Data Load</h1>';

$stopWatch = new StopWatch();
$stopWatch->Start();

$numberOfResources = 1000;
$numberOfUsers = 10000;
$numberOfReservations = 500000;
$numberOfAccessories = 20;

$users = [];
$resources = [];

$db = ServiceLocator::GetDatabase();

// USERS
$db->Execute(new AdHocCommand("delete from users where fname ='load' and lname = 'test'"));
$userRepo = new UserRepository();
for ($i = 0; $i < $numberOfUsers; $i++) {
    $user = User::Create("load$i", "test$i", "email $i", "username $i", 'en_us', 'America/Chicago', '7b6aec38ff9b7650d64d0374194307bdde711425', '3b3dbb9b');
    $userId = $userRepo->Add($user);
    $users[] = $user;
}

echo "Loaded $numberOfUsers users<br/>";

// RESOURCES
$db->Execute(new AdHocCommand("delete from resources where name like 'load%'"));
$resourceRepo = new ResourceRepository();
for ($i = 0; $i < $numberOfResources; $i++) {
    $resource = BookableResource::CreateNew("load$i", 1);
    $resourceId = $resourceRepo->Add($resource);
    $resources[] = $resource;
}

echo "Loaded $numberOfResources resources<br/>";

// ACCESSORIES
$db->Execute(new AdHocCommand("delete from accessories where accessory_name like 'load%'"));
$accessoryRepo = new AccessoryRepository();
for ($i = 0; $i < $numberOfAccessories; $i++) {
    $accessory = new Accessory(0, "Load $i", 10);
    $id = $accessoryRepo->Add($accessory);
}

echo "Loaded $numberOfAccessories accessories<br/>";

// RESERVATIONS
$db->Execute(new AdHocCommand("delete from reservation_series where title like 'load%'"));
$scheduleRepo = new ScheduleRepository();
$layout = $scheduleRepo->GetLayout(1, new ScheduleLayoutFactory('America/Chicago'));
$reservationRepo = new ReservationRepository();
$bookedBy = new UserSession(1);
$bookedBy->Timezone = 'America/Chicago';
$currentDate = Date::Now();

$i = 0;
while ($i < $numberOfReservations) {
    $periods = $layout->GetLayout($currentDate);

    foreach ($periods as $period) {
        $howManyResources = rand(1, count($resources));
        $startResource = rand(0, $howManyResources);
        if ($period->IsReservable()) {
            for ($resourceNum = $startResource; $resourceNum < $howManyResources; $resourceNum++) {
                $userId = getRandomUserId($users)->Id();
                $resource = $resources[$resourceNum];
                $date = new DateRange($period->BeginDate(), $period->EndDate(), 'America/Chicago');
                $reservation = ReservationSeries::Create(
                    $userId,
                    $resource,
                    "load$i",
                    null,
                    $date,
                    new RepeatNone(),
                    $bookedBy
                );
                $reservationRepo->Add($reservation);
                $i++;
            }
        }
    }

    $currentDate = $currentDate->AddDays(1);
}

echo "Loaded $numberOfReservations reservations<br/>";
$stopWatch->Stop();

echo '<h5>Took ' . $stopWatch->GetTotalSeconds() . ' seconds</h5>';

/**
 * @param array|User[] $users
 * @return User
 */
function getRandomUserId($users)
{
    $rand = rand(0, count($users) - 1);
    return $users[$rand];
}

/**
 * @param array|BookableResource[] $resources
 * @return BookableResource
 */
function getRandomResource($resources)
{
    $rand = rand(0, count($resources) - 1);
    return $resources[$rand];
}
