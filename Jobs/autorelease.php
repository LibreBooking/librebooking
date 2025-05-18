<?php
/**
*  Cron Example:
*  This script must be executed every minute for to enable automatic release functionality
*  * * * * * /usr/bin/env php -f ${WWW_DIR}/librebooking/Jobs/autorelease.php
*/

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');

Log::Debug('Running autorelease.php');

JobCop::EnsureCommandLine();

try {
    $reservationViewRepository = new ReservationViewRepository();
    $resourceRepository = new ResourceRepository();
    $reservationRepository = new ReservationRepository();

    $onlyAutoReleasedResources = new SqlFilterFreeForm(sprintf(
        "`%s` IS NOT NULL AND `%s` <> ''",
        ColumnNames::AUTO_RELEASE_MINUTES,
        ColumnNames::AUTO_RELEASE_MINUTES
    ));
    $autoReleasedResources = $resourceRepository->GetList(null, null, null, null, $onlyAutoReleasedResources)->Results();

    $userSession = new UserSession(0);
    $userSession->FirstName = 'Auto release job';
    $userSession->IsAdmin = true;

    /** @var BookableResource $resource */
    foreach ($autoReleasedResources as $resource) {
        $autoReleaseMinutes = $resource->GetAutoReleaseMinutes();

        $latestStartDate = Date::Now()->SubtractMinutes($autoReleaseMinutes)->ToDatabase();

        $reservationsThatShouldHaveBeenCheckedIn = new SqlFilterFreeForm(sprintf(
            "`%s`.`%s` = %s AND `%s` IS NULL AND `%s`.`%s` < '%s'",
            TableNames::RESOURCES,
            ColumnNames::RESOURCE_ID,
            $resource->GetId(),
            ColumnNames::CHECKIN_DATE,
            TableNames::RESERVATION_INSTANCES_ALIAS,
            ColumnNames::RESERVATION_START,
            $latestStartDate
        ));
        $reservationItemViews = $reservationViewRepository->GetList(null, null, null, null, $reservationsThatShouldHaveBeenCheckedIn)->Results();

        /** @var ReservationItemView $reservationItemView */
        foreach ($reservationItemViews as $reservationItemView) {
            Log::Debug(
                'Automatically releasing reservation. ReferenceNumber=%s, User=%s %s, Resource=%s',
                $reservationItemView->ReferenceNumber,
                $reservationItemView->OwnerFirstName,
                $reservationItemView->OwnerLastName,
                $reservationItemView->ResourceName
            );

            $reservation = $reservationRepository->LoadByReferenceNumber($reservationItemView->ReferenceNumber);
            $reservation->ApplyChangesTo(SeriesUpdateScope::ThisInstance);
            $reservation->Delete($userSession);
            $reservationRepository->Delete($reservation);
        }
    }
} catch (Exception $ex) {
    Log::Error('Error running autorelease.php: %s', $ex);
}

Log::Debug('Finished running autorelease.php');
