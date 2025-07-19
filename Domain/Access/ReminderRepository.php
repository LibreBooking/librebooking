<?php

require_once(ROOT_DIR . 'Domain/Reminder.php');
require_once(ROOT_DIR . 'Domain/ReminderNotice.php');

class ReminderRepository implements IReminderRepository
{
    // select date_sub(start_date,INTERVAL rr.minutes_prior MINUTE) as reminder_date from reservation_instances ri INNER JOIN reservation_reminders rr on ri.series_id = rr.series_id

    public function GetAll()
    {
        $reminders = [];

        $reader = ServiceLocator::GetDatabase()->Query(new GetAllRemindersCommand());

        while ($row = $reader->GetRow()) {
            $reminders[] = Reminder::FromRow($row);
        }
        $reader->Free();
        return $reminders;
    }


    /**
     * @param string $user_id
     * @return Reminder[]
     */
    public function GetByUser($user_id)
    {
        $reminders = [];
        $reader = ServiceLocator::GetDatabase()->Query(new GetReminderByUserCommand($user_id));

        while ($row = $reader->GetRow()) {
            $reminders[] = Reminder::FromRow($row);
        }

        $reader->Free();
        return $reminders;
    }

    /**
     * @param string $refnumber
     * @return Reminder[]
     */
    public function GetByRefNumber($refnumber)
    {
        $reminders = [];
        $reader = ServiceLocator::GetDatabase()->Query(new GetReminderByRefNumberCommand($refnumber));

        if ($row = $reader->GetRow()) {
            $reminders = Reminder::FromRow($row);
        }

        $reader->Free();
        return $reminders;
    }

    /**
     * @param int $reminder_id
     */
    public function DeleteReminder($reminder_id)
    {
        ServiceLocator::GetDatabase()->Query(new DeleteReminderCommand($reminder_id));
    }

    /**
     * @param $user_id
     */
    public function DeleteReminderByUser($user_id)
    {
        ServiceLocator::GetDatabase()->Query(new DeleteReminderByUserCommand($user_id));
    }

    /**
     * @param $user_id
     */
    public function DeleteReminderByRefNumber($refnumber)
    {
        ServiceLocator::GetDatabase()->Query(new DeleteReminderByRefNumberCommand($refnumber));
    }

    /**
     * @param Date $now
     * @param ReservationReminderType|int $reminderType
     * @return ReminderNotice[]|array
     */
    public function GetReminderNotices(Date $now, $reminderType)
    {
        $reader = ServiceLocator::GetDatabase()->Query(new GetReminderNoticesCommand($now->ToTheMinute(), $reminderType));

        $notices = [];
        while ($row = $reader->GetRow()) {
            $notices[] = ReminderNotice::FromRow($row);
        }

        $reader->Free();
        return $notices;
    }
}

interface IReminderRepository
{
    /**
     * @abstract
     * @return Reminder[]|array
     */
    public function GetAll();


    /**
     * @abstract
     * @param string $user_id
     * @return Reminder[]|array
     */
    public function GetByUser($user_id);

    /**
     * @abstract
     * @param string $refnumber
     * @return Reminder[]|array
     */
    public function GetByRefNumber($refnumber);

    /**
     * @abstract
     * @param int $reminder_id
     */
    public function DeleteReminder($reminder_id);

    /**
     * @abstract
     * @param $user_id
     */
    public function DeleteReminderByUser($user_id);

    /**
     * @abstract
     * @param $refnumber
     */
    public function DeleteReminderByRefNumber($refnumber);
}
