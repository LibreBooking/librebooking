<?php

class Reminder
{
    private $user_id;
    private $reminder_id;
    private $reminderaddress;
    private $remindermessage;
    private $sendTime;
    private $refNumber;

    /**
     * @return string
     */
    public function UserID()
    {
        return $this->user_id;
    }
    /**
     * @return int
     */
    public function ReminderID()
    {
        return $this->reminder_id;
    }

    /**
     * @return string
     */
    public function Address()
    {
        return $this->reminderaddress;
    }

    /**
     * @return string
     */
    public function Message()
    {
        return $this->remindermessage;
    }

    /**
     * @return Date
     */
    public function SendTime()
    {
        return $this->sendTime;
    }

    /**
     * @return string
     */
    public function RefNumber()
    {
        return $this->refNumber;
    }


    public function __construct($id, $userid, $address, $message, $sendtime, $refnumber)
    {
        $this->reminder_id = $id;
        $this->user_id = $userid;
        $this->reminderaddress = $address;
        $this->remindermessage = $message;
        $this->sendTime = $sendtime;
        $this->refNumber = $refnumber;
    }

    public static function Create($id, $userid, $address, $message, $sendtime, $refnumber)
    {
        return new Reminder($id, $userid, $address, $message, $sendtime, $refnumber);
    }

    public static function FromRow($row)
    {
        return new Reminder(
            $row[ColumnNames::REMINDER_ID],
            $row[ColumnNames::REMINDER_USER_ID],
            $row[ColumnNames::REMINDER_ADDRESS],
            $row[ColumnNames::REMINDER_MESSAGE],
            $row[ColumnNames::REMINDER_SENDTIME],
            $row[ColumnNames::REMINDER_REFNUMBER]
        );
    }
    public static function SendItOut(Reminder $reminder)
    {
        $message = $reminder->Message();
        $subject = 'Automatic Reminder from LibreBooking';
        /* replace 'username' and 'password' with your GoogleVoice sign-in */
        $gv = new GoogleVoice('username', 'password');
        $addresses = explode(',', str_replace(' ', '', $reminder->Address()));
        foreach ($addresses as $address) {
            if (ctype_digit($address)) {
                $gv->sms($address, $message);
            } else {
                mail($address, $subject, $message);
            }
        }
        $repository = new ReminderRepository();
        $repository->DeleteReminder($reminder->ReminderID());
        return;
    }
}
