<?php

interface IStylingFactory
{
    /**
     * Returns a file path (on the server) to an additional CSS file to use.
     * @param UserSession $userSession
     * @return null|string
     */
    public function AdditionalCSS(UserSession $userSession);

    /**
     * You can add some CSS classes to reservations items.
     * Those classes can for example depends on some attributes.
     * @param IReservedItemView $item
     * @return string[]
     */
    public function GetReservationAdditonalCSSClasses(IReservedItemView $item);
}

class StylingFactory implements IStylingFactory
{
    public function __construct()
    {
    }

    /**
     * Returns a file path (on the server) to an additional CSS file to use.
     * @param UserSession $userSession
     * @return null|string
     */
    public function AdditionalCSS(UserSession $userSession)
    {
        return null;
    }

    /**
     * You can add some CSS classes to reservations items.
     * Those classes can for example depends on some attributes.
     * @param IReservedItemView $item
     * @return string[]
     */
    public function GetReservationAdditonalCSSClasses(IReservedItemView $item)
    {
        return [];
    }
}
