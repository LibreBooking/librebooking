<?php

class ReservationPastTimeConstraint
{
    public static function IsPast(Date $begin, Date $end): bool
    {
        $constraint = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT);

        if (empty($constraint)) {
            $constraint = ReservationStartTimeConstraint::_DEFAULT;
        }

        if ($constraint == ReservationStartTimeConstraint::NONE) {
            return false;
        }

        if ($constraint == ReservationStartTimeConstraint::CURRENT) {
            return $end->LessThan(Date::Now());
        }

        return $begin->LessThan(Date::Now());
    }
}
