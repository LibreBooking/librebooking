<?php
/**
*  Cron Example:
*  This script must be executed every minute for to enable waitlist notification emails
*  * * * * * /usr/bin/env php -f ${WWW_DIR}/librebooking/Jobs/sendwaitlist.php
*/

define('ROOT_DIR', __DIR__ . '/../');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Jobs/JobCop.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationAvailableEmail.php');

Log::Debug('Running sendwaitlist.php');

JobCop::EnsureCommandLine();

try {
    $emailEnabled = Configuration::Instance()->GetKey(ConfigKeys::EMAIL_ENABLED, new BooleanConverter());
    if (!$emailEnabled) {
        return;
    }

    $reservationViewRepository = new ReservationViewRepository();
    $resourceRepository = new ResourceRepository();
    $waitlistRepository = new ReservationWaitlistRepository();
    $userRepository = new UserRepository();

    $waitlistRequests = $waitlistRepository->GetAll();

    /** @var ReservationWaitlistRequest $r */
    foreach ($waitlistRequests as $r) {
        $reservations = $reservationViewRepository->GetReservations($r->StartDate(), $r->EndDate(), null, null, null, $r->ResourceId());

        $conflicts = false;

        /** @var ReservationItemView $reservation */
        foreach ($reservations as $reservation) {
            if ($reservation->BufferedTimes()->Overlaps($r->Duration())) {
                $conflicts = true;
                break;
            }
        }

        if (!$conflicts || $r->StartDate()->LessThanOrEqual(Date::Now())) {
            $user = $userRepository->LoadById($r->UserId());
            $resource = $resourceRepository->LoadById($r->ResourceId());
            $email = new ReservationAvailableEmail($user, $resource, $r);
            ServiceLocator::GetEmailService()->Send($email);
            $waitlistRepository->Delete($r);
        }
    }
} catch (Exception $ex) {
    Log::Error('Error running sendwaitlist.php: %s', $ex);
}

Log::Debug('Finished running sendwaitlist.php');
