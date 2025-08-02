<?php
/**
*  Cron Example:
*  This script must be executed at a specific time to be enabled
*  * * * * * /usr/bin/env php -f ${WWW_DIR}/librebooking/Jobs/sendmissedcheckin.php

*  Each * correspods to Minute Hour Day_Of_Month Month Day_Of_Week, respectively

*  /usr/bin/env php -f                                  -> the path to php (run "which php" to know what it is)
*  ${WWW_DIR}/librebooking/Jobs/sendmissedcheckin.php   -> the path to this script
*/
define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');

Log::Debug('Running deleteolddata.php');

JobCop::EnsureCommandLine();

//Checks if years specified are positive (only 1+ years old data can be permanently deleted)
if ((Configuration::Instance()->GetKey(ConfigKeys::CLEANUP_YEARS_OLD_DATA)) > 0) {
    try {
        //Delete announcements, blackouts and reservations older than $deleteBefore (years -> -2 = 2+ years older)
        $deleteBefore = Date::Now()->AddYears(-(Configuration::Instance()->GetKey(ConfigKeys::CLEANUP_YEARS_OLD_DATA)));

        //ANNOUNCEMENTS
        if (Configuration::Instance()->GetKey(ConfigKeys::CLEANUP_DELETE_OLD_ANNOUNCEMENTS, new BooleanConverter())) {
            $getAnnouncements = GetAnnoucementsIdsToDeleteQuery($deleteBefore);
            $reader = ServiceLocator::GetDatabase()->Query($getAnnouncements);
            Log::Debug('Getting %s old announcements', $reader->NumRows());
            while ($row = $reader->GetRow()) {
                $annoucementId = $row[ColumnNames::ANNOUNCEMENT_ID];
                DeleteAnnouncementsQuery($annoucementId);
            }
            $reader->Free();
        }

        //BLACKOUTS
        if (Configuration::Instance()->GetKey(ConfigKeys::CLEANUP_DELETE_OLD_BLACKOUTS, new BooleanConverter())) {
            $getBlackouts = GetBlackoutsIdsToDeleteQuery($deleteBefore);
            $reader = ServiceLocator::GetDatabase()->Query($getBlackouts);
            Log::Debug('Getting %s old blackouts', $reader->NumRows());
            while ($row = $reader->GetRow()) {
                $blackoutId = $row[ColumnNames::BLACKOUT_SERIES_ID];
                DeleteBlackoutsQuery($blackoutId);
            }
            $reader->Free();
        }

        //RESERVATIONS
        if (Configuration::Instance()->GetKey(ConfigKeys::CLEANUP_DELETE_OLD_RESERVATIONS, new BooleanConverter())) {
            $getReservations = GetReservationsIdsToDeleteQuery($deleteBefore);
            $reader = ServiceLocator::GetDatabase()->Query($getReservations);
            Log::Debug('Getting %s old reservations', $reader->NumRows());
            while ($row = $reader->GetRow()) {
                $reservationId = $row[ColumnNames::RESERVATION_SERIES_ID];

                //RESERVATION USERS
                $getReservationUsers = GetReservationUsersToDelete($reservationId);
                $auxReader = ServiceLocator::GetDatabase()->Query($getReservationUsers);
                while ($auxRow = $auxReader->GetRow()){
                    $reservationUser = $auxRow[ColumnNames::RESERVATION_INSTANCE_ID];
                    DeleteReservationUsersQuery($reservationUser);
                }

                $auxReader->Free();
                DeleteReservationsQuery($reservationId);
            }
            $reader->Free();
        }

    } catch (Exception $ex){
        Log::Error('Error running deleteolddata.php: %s', $ex);
    }
}
else {
    Log::Error('Error running deleteolddata.php');
    Log::Error('Invalid years.old.data value in config file, value must be positive!');
}
Log::Debug('Finished running deleteolddata.php');



//GET INSTANCES FUNCTIONS

//          ->  ANNOUNCEMENTS
function GetAnnoucementsIdsToDeleteQuery($deleteBefore){

    $annoucementsIds = new AdHocCommand(
        "   SELECT announcementid
            FROM announcements
            WHERE end_date < '{$deleteBefore}'"
    );

    return $annoucementsIds;
}

//          ->  BLACKOUTS
function GetBlackoutsIdsToDeleteQuery($deleteBefore){

    $blackoutsIds = new AdHocCommand(
        "   SELECT blackout_series_id
            FROM blackout_series
            WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(repeat_options, '|', -2),'=',-2),'|',1) < '{$deleteBefore}' AND repeat_options != ''

            UNION

            SELECT blackout_series.blackout_series_id
            FROM blackout_series
            LEFT JOIN blackout_instances
            ON blackout_series.blackout_series_id = blackout_instances.blackout_series_id
            WHERE blackout_series.repeat_type = 'none' AND blackout_instances.end_date < '{$deleteBefore}'"
    );

    return $blackoutsIds;
}

//          ->  RESERVATION USERS
function GetReservationUsersToDelete($reservationSeriesId){
    $reservationInstance = new AdHocCommand(
        "   SELECT reservation_instance_id
            FROM reservation_instances
            WHERE series_id = '{$reservationSeriesId}'"
    );
    return $reservationInstance;
}

//          -> RESERVATIONS
function GetReservationsIdsToDeleteQuery($deleteBefore) {

    $reservationIds = new AdHocCommand(
        "   SELECT reservation_series.series_id
            FROM reservation_series
            LEFT JOIN reservation_instances
            ON reservation_instances.series_id = reservation_series.series_id
            WHERE reservation_series.repeat_type = 'none' AND end_date < '{$deleteBefore}'

            UNION

            SELECT series_id
            FROM reservation_series
            WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(repeat_options, '|', -1), '=', -1) < '{$deleteBefore}' AND repeat_options != ''"
    );

    return $reservationIds;
}


//DELETE INSTANCES FUNCTIONS

//          ->  ANNOUNCEMENTS
function DeleteAnnouncementsQuery($announcementid){
    Log::Debug('Deleting announcement with id %s', $announcementid);

    $deleteGroupAnnouncements = new AdHocCommand(
        "   DELETE FROM announcement_groups
            WHERE announcementid = '{$announcementid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteGroupAnnouncements);

    $deleteResourceAnnouncements = new AdHocCommand(
        "   DELETE FROM announcement_resources
            WHERE announcementid = '{$announcementid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteResourceAnnouncements);

    $deleteAnnouncements = new AdHocCommand(
        "   DELETE FROM announcements
            WHERE announcementid = '{$announcementid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteAnnouncements);

    Log::Debug('Announcement with id %s deleted', $announcementid);
}

//          ->    BLACKOUTS
function DeleteBlackoutsQuery($blackoutid)
{
    Log::Debug('Deleting blackout with id %s', $blackoutid);

    $deleteBlackoutSeriesResources = new AdHocCommand(
        "   DELETE FROM blackout_series_resources
            WHERE blackout_series_id = '{$blackoutid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteBlackoutSeriesResources);

    $deleteBlackoutInstances = new AdHocCommand(
        "   DELETE FROM blackout_instances
            WHERE blackout_series_id = '{$blackoutid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteBlackoutInstances);

    $deleteBlackoutSeries = new AdHocCommand(
        "   DELETE FROM blackout_series
            WHERE blackout_series_id = '{$blackoutid}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteBlackoutSeries);

    Log::Debug('Blackout with id %s deleted', $blackoutid);
}

//          ->    RESERVATION USERS
function DeleteReservationUsersQuery($reservationInstanceId){
    $deleteReservationGuests = new AdHocCommand(
        "DELETE FROM reservation_guests
        WHERE reservation_instance_id = '{$reservationInstanceId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationGuests);

    $deleteReservationUsers = new AdHocCommand(
        "DELETE FROM reservation_users
        WHERE reservation_instance_id = '{$reservationInstanceId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationUsers);
}

//          ->    RESERVATIONS
function DeleteReservationsQuery($reservationId){
    Log::Debug('Deleting reservation with id %s', $reservationId);

    $deleteReservationAcessories = new AdHocCommand(
        "DELETE FROM reservation_accessories
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationAcessories);

    $deleteReservationFiles = new AdHocCommand(
        "DELETE FROM reservation_files
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationFiles);

    $deleteReservationReminders = new AdHocCommand(
        "DELETE FROM reservation_reminders
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationReminders);

    $deleteReservationResources = new AdHocCommand(
        "DELETE FROM reservation_resources
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationResources);

    $deleteReservationInstances = new AdHocCommand(
        "DELETE FROM reservation_instances
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationInstances);

    $deleteReservationSeries = new AdHocCommand(
        "DELETE FROM reservation_series
        WHERE series_id = '{$reservationId}'"
    );
    ServiceLocator::GetDatabase()->Execute($deleteReservationSeries);

    Log::Debug('Reservation with id %s deleted', $reservationId);
}
