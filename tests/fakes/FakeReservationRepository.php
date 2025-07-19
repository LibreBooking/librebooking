<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class FakeReservationRepository implements IReservationRepository
{
    public $_Reservations = [];
    public $_LastAddedReservation;
    public $_FirstAddedReservation;
    /**
     * @var ExistingReservationSeries
     */
    public $_Series;
    /**
     * @var ExistingReservationSeries
     */
    public $_LastDeleted;

    public function __construct()
    {
    }

    public static function GetReservationRows()
    {
        $row1 = [ColumnNames::RESERVATION_INSTANCE_ID => 1,
            ColumnNames::RESERVATION_START => '2008-05-20 09:00:00',
            ColumnNames::RESERVATION_END => '2008-05-20 15:30:00',
            ColumnNames::RESERVATION_TYPE => 1,
            ColumnNames::RESERVATION_DESCRIPTION => 'summary1',
            ColumnNames::RESERVATION_TITLE => 'summary1',
            ColumnNames::RESOURCE_ID => 1,
            ColumnNames::USER_ID => 1,
            ColumnNames::FIRST_NAME => 'first',
            ColumnNames::LAST_NAME => 'last',
            ColumnNames::REPEAT_START => null,
            ColumnNames::REPEAT_END => null,
            ColumnNames::REFERENCE_NUMBER => 'r1',
            ColumnNames::RESERVATION_STATUS => ReservationStatus::Created,
            ColumnNames::RESOURCE_NAME => 'rn',
            ColumnNames::RESERVATION_USER_LEVEL => ReservationUserLevel::OWNER,
            ColumnNames::SCHEDULE_ID => 1,
            ColumnNames::OWNER_FIRST_NAME => 'first',
            ColumnNames::OWNER_LAST_NAME => 'last',
            ColumnNames::OWNER_USER_ID => 1,
            ColumnNames::OWNER_PHONE => null,
            ColumnNames::OWNER_ORGANIZATION => null,
            ColumnNames::OWNER_POSITION => null,
            ColumnNames::PARTICIPANT_LIST => null,
            ColumnNames::INVITEE_LIST => null,
            ColumnNames::ATTRIBUTE_LIST => null,
            ColumnNames::USER_PREFERENCES => null,
            ColumnNames::CHECKIN_DATE => null,
            ColumnNames::CHECKOUT_DATE => null,
            ColumnNames::PREVIOUS_END_DATE => null,
            ColumnNames::ENABLE_CHECK_IN => null,
            ColumnNames::AUTO_RELEASE_MINUTES => null,
            ColumnNames::CREDIT_COUNT => null,
            ColumnNames::RESOURCE_ADMIN_GROUP_ID_RESERVATIONS => null,
            ColumnNames::SCHEDULE_ADMIN_GROUP_ID_RESERVATIONS => null,
        ];

        $row2 = [ColumnNames::RESERVATION_INSTANCE_ID => 1,
            ColumnNames::RESERVATION_START => '2008-05-20 09:00:00',
            ColumnNames::RESERVATION_END => '2008-05-20 15:30:00',
            ColumnNames::RESERVATION_TYPE => 1,
            ColumnNames::RESERVATION_DESCRIPTION => 'summary1',
            ColumnNames::RESERVATION_TITLE => 'summary1',
            ColumnNames::RESERVATION_PARENT_ID => null,
            ColumnNames::RESOURCE_ID => 2,
            ColumnNames::USER_ID => 1,
            ColumnNames::FIRST_NAME => 'first',
            ColumnNames::LAST_NAME => 'last',
            ColumnNames::REPEAT_START => null,
            ColumnNames::REPEAT_END => null,
            ColumnNames::REFERENCE_NUMBER => 'r2',
            ColumnNames::RESERVATION_STATUS => ReservationStatus::Created,
            ColumnNames::RESOURCE_NAME => 'rn',
            ColumnNames::RESERVATION_USER_LEVEL => ReservationUserLevel::OWNER,
            ColumnNames::SCHEDULE_ID => 1,
            ColumnNames::OWNER_FIRST_NAME => 'first',
            ColumnNames::OWNER_LAST_NAME => 'last',
            ColumnNames::OWNER_USER_ID => 1,
            ColumnNames::OWNER_PHONE => null,
            ColumnNames::OWNER_ORGANIZATION => null,
            ColumnNames::OWNER_POSITION => null,
            ColumnNames::PARTICIPANT_LIST => null,
            ColumnNames::INVITEE_LIST => null,
            ColumnNames::ATTRIBUTE_LIST => null,
            ColumnNames::USER_PREFERENCES => null,
            ColumnNames::CHECKIN_DATE => null,
            ColumnNames::CHECKOUT_DATE => null,
            ColumnNames::PREVIOUS_END_DATE => null,
            ColumnNames::ENABLE_CHECK_IN => 1,
            ColumnNames::AUTO_RELEASE_MINUTES => 1,
            ColumnNames::CREDIT_COUNT => 1,
            ColumnNames::RESOURCE_ADMIN_GROUP_ID_RESERVATIONS => null,
            ColumnNames::SCHEDULE_ADMIN_GROUP_ID_RESERVATIONS => null,
        ];

        $row3 = [ColumnNames::RESERVATION_INSTANCE_ID => 2,
            ColumnNames::RESERVATION_START => '2008-05-22 06:00:00',
            ColumnNames::RESERVATION_END => '2008-05-24 09:30:00',
            ColumnNames::RESERVATION_TYPE => 1,
            ColumnNames::RESERVATION_DESCRIPTION => 'summary2',
            ColumnNames::RESERVATION_TITLE => 'summary2',
            ColumnNames::RESERVATION_PARENT_ID => null,
            ColumnNames::RESOURCE_ID => 1,
            ColumnNames::USER_ID => 1,
            ColumnNames::FIRST_NAME => 'first',
            ColumnNames::LAST_NAME => 'last',
            ColumnNames::REPEAT_START => null,
            ColumnNames::REPEAT_END => null,
            ColumnNames::REFERENCE_NUMBER => 'r3',
            ColumnNames::RESERVATION_STATUS => ReservationStatus::Pending,
            ColumnNames::RESOURCE_NAME => 'rn',
            ColumnNames::RESERVATION_USER_LEVEL => ReservationUserLevel::OWNER,
            ColumnNames::SCHEDULE_ID => 1,
            ColumnNames::OWNER_FIRST_NAME => 'first',
            ColumnNames::OWNER_LAST_NAME => 'last',
            ColumnNames::OWNER_USER_ID => 1,
            ColumnNames::OWNER_PHONE => null,
            ColumnNames::OWNER_ORGANIZATION => null,
            ColumnNames::OWNER_POSITION => null,
            ColumnNames::PARTICIPANT_LIST => null,
            ColumnNames::INVITEE_LIST => null,
            ColumnNames::ATTRIBUTE_LIST => null,
            ColumnNames::USER_PREFERENCES => null,
            ColumnNames::CHECKIN_DATE => null,
            ColumnNames::CHECKOUT_DATE => null,
            ColumnNames::PREVIOUS_END_DATE => null,
            ColumnNames::ENABLE_CHECK_IN => 0,
            ColumnNames::AUTO_RELEASE_MINUTES => 0,
            ColumnNames::CREDIT_COUNT => 0,
            ColumnNames::RESOURCE_ADMIN_GROUP_ID_RESERVATIONS => null,
            ColumnNames::SCHEDULE_ADMIN_GROUP_ID_RESERVATIONS => null,
        ];

        return [
            $row1,
            $row2,
            $row3
        ];
    }

    /**
     * Insert a new reservation
     *
     * @param ReservationSeries $reservation
     * @return void
     */
    public function Add(ReservationSeries $reservation)
    {
        if ($this->_FirstAddedReservation == null) {
            $this->_FirstAddedReservation = $reservation;
        }
        $this->_LastAddedReservation = $reservation;
    }

    /**
     * Return an existing reservation series
     *
     * @param int $reservationInstanceId
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadById($reservationInstanceId)
    {
        return $this->_Series;
    }

    /**
     * Return an existing reservation series
     *
     * @param string $referenceNumber
     * @return ExistingReservationSeries or null if no reservation found
     */
    public function LoadByReferenceNumber($referenceNumber)
    {
        return $this->_Series;
    }

    /**
     * Update an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Update(ExistingReservationSeries $existingReservationSeries)
    {
        // TODO: Implement Update() method.
    }

    /**
     * Delete all or part of an existing reservation
     *
     * @param ExistingReservationSeries $existingReservationSeries
     * @return void
     */
    public function Delete(ExistingReservationSeries $existingReservationSeries)
    {
        $this->_LastDeleted = $existingReservationSeries;
    }

    /**
     * @param $attachmentFileId int
     * @return ReservationAttachment
     */
    public function LoadReservationAttachment($attachmentFileId)
    {
        // TODO: Implement LoadReservationAttachment() method.
        return null;
    }

    /**
     * @param $attachmentFile ReservationAttachment
     * @return int
     */
    public function AddReservationAttachment(ReservationAttachment $attachmentFile)
    {
        // TODO: Implement AddReservationAttachment() method.
        return null;
    }

    /**
     * @return ReservationColorRule[]
     */
    public function GetReservationColorRules()
    {
        // TODO: Implement GetReservationColorRules() method.
        return null;
    }

    /**
     * @param int $ruleId
     * @return ReservationColorRule
     */
    public function GetReservationColorRule($ruleId)
    {
        // TODO: Implement GetReservationColorRule() method.
        return null;
    }

    /**
     * @param ReservationColorRule $colorRule
     * @return int
     */
    public function AddReservationColorRule(ReservationColorRule $colorRule)
    {
        // TODO: Implement AddReservationColorRule() method.
        return null;
    }

    /**
     * @param ReservationColorRule $colorRule
     */
    public function DeleteReservationColorRule(ReservationColorRule $colorRule)
    {
        // TODO: Implement DeleteReservationColorRule() method.
    }
}
