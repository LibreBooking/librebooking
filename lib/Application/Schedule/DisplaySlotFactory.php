<?php

require_once(ROOT_DIR . 'lib/Application/Authorization/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class DisplaySlotFactory
{
    public function GetFunction(SchedulePeriod $slot, $accessAllowed = false, $functionSuffix = '')
    {
        if (!$accessAllowed) {
            return "displayRestricted$functionSuffix";
        } else {
            if ($slot->IsPastDate() && !$this->UserHasAdminRights()) {
                return "displayPastTime$functionSuffix";
            } else {
                if ($slot->IsReservable()) {
                    return "displayReservable$functionSuffix";
                } else {
                    return "displayUnreservable$functionSuffix";
                }
            }
        }
    }

    private function UserHasAdminRights()
    {
        return ServiceLocator::GetServer()->GetUserSession()->IsAdmin;
    }

    /**
     * @param SchedulePeriod[] $periods
     * @param Date $start
     * @param Date $end
     * @return string
     */
    public function GetCondensedPeriodLabel($periods, $start, $end)
    {
        foreach ($periods as $period) {
            if ($period->IsLabelled()) {
                if ($period->BeginDate()->Equals($start)) {
                    return $period->Label() . ' - ' . $period->LabelEnd();
                }
            }
        }
        $format = Resources::GetInstance()->GetDateFormat('period_time');
        return $start->Format($format) . ' - ' . $end->Format($format);
    }
}

class StaticDisplaySlotFactory
{
    public function GetFunction(IReservationSlot $slot, $accessAllowed = false, $functionSuffix = '')
    {
        if ($slot->IsReserved()) {
            if ($this->IsMyReservation($slot)) {
                return "displayMyReserved$functionSuffix";
            } elseif ($this->AmIParticipating($slot)) {
                return "displayMyParticipating$functionSuffix";
            } elseif ($this->IsAdminFor($slot)) {
                return "displayAdminReserved$functionSuffix";
            } else {
                return "displayReserved$functionSuffix";
            }
        } else {
            if (!$accessAllowed) {
                return "displayRestricted$functionSuffix";
            } else {
                if ($slot->IsPastDate(Date::Now()) && !$this->UserHasAdminRights()) {
                    return "displayPastTime$functionSuffix";
                } else {
                    if ($slot->IsReservable()) {
                        return "displayReservable$functionSuffix";
                    } else {
                        return "displayUnreservable$functionSuffix";
                    }
                }
            }
        }
    }

    private function UserHasAdminRights()
    {
        return ServiceLocator::GetServer()->GetUserSession()->IsAdmin;
    }

    private function IsMyReservation(IReservationSlot $slot)
    {
        $mySession = ServiceLocator::GetServer()->GetUserSession();
        return $slot->IsOwnedBy($mySession);
    }

    private function IsAdminFor(IReservationSlot $slot)
    {
        $mySession = ServiceLocator::GetServer()->GetUserSession();
        return $mySession->IsAdmin || $mySession->IsAdminForGroup($slot->OwnerGroupIds());
    }

    private function AmIParticipating(IReservationSlot $slot)
    {
        $mySession = ServiceLocator::GetServer()->GetUserSession();
        return $slot->IsParticipating($mySession);
    }

    /**
     * @param SchedulePeriod[] $periods
     * @param Date $start
     * @param Date $end
     * @return string
     */
    public function GetCondensedPeriodLabel($periods, $start, $end)
    {
        foreach ($periods as $period) {
            if ($period->IsLabelled()) {
                if ($period->BeginDate()->Equals($start)) {
                    return $period->Label() . ' - ' . $period->LabelEnd();
                }
            }
        }
        $format = Resources::GetInstance()->GetDateFormat('period_time');
        return $start->Format($format) . ' - ' . $end->Format($format);
    }
}
