<?php
/**
*  Cron Example:
*  This script must be executed every minute for to enable missed checkin email functionality
*  * * * * * /usr/bin/env php -f ${WWW_DIR}/librebooking/Jobs/sendmissedcheckin.php
*/

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');
require_once(ROOT_DIR . 'lib/Email/Messages/MissedCheckinEmail.php');

Log::Debug('Running sendmissedcheckin.php');

JobCop::EnsureCommandLine();

try {
    $emailEnabled = Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter());
    if (!$emailEnabled) {
        return;
    }

    $alreadySeen = [];

    $reservationViewRepository = new ReservationViewRepository();

    $now = Date::Now();
    $onlyMissedCheckinReservations = new SqlFilterFreeForm(sprintf(
        "%s=1 AND %s IS NULL AND `%s`.`%s` BETWEEN '%s' AND '%s'",
        ColumnNames::ENABLE_CHECK_IN,
        ColumnNames::CHECKIN_DATE,
        TableNames::RESERVATION_INSTANCES_ALIAS,
        ColumnNames::RESERVATION_START,
        $now->AddMinutes(-1)->ToDatabase(),
        $now->ToDatabase()
    ));
    $reservations = $reservationViewRepository->GetList(null, null, null, null, $onlyMissedCheckinReservations)->Results();

    /** @var ReservationItemView $reservation */
    foreach ($reservations as $reservation) {
        if (array_key_exists($reservation->ReferenceNumber, $alreadySeen)) {
            continue;
        }

        $alreadySeen[$reservation->ReferenceNumber] = 1;

        Log::Debug(
            'Sending missed checkin email. ReferenceNumber=%s, User=%s, Resource=%s',
            $reservation->ReferenceNumber,
            $reservation->UserId,
            $reservation->ResourceName
        );

        ServiceLocator::GetEmailService()->Send(new MissedCheckinEmail($reservation));
    }
} catch (Exception $ex) {
    Log::Error('Error running sendmissedcheckin.php: %s', $ex);
}

Log::Debug('Finished running sendmissedcheckin.php');
