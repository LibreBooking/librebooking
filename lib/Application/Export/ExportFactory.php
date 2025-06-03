<?php

interface IExportFactory
{
    /**
     * Returns the ICal Classification for an item (https://icalendar.org/iCalendar-RFC-5545/3-8-1-3-classification.html)
     * @param IReservedItemView $item
     * @return string
     */
    public function GetIcalendarClassification(IReservedItemView $item);

    /**
     * Optionally returns some lines to add to the iCalendar event.
     * @param IReservedItemView $item
     * @return null|string
     */
    public function GetIcalendarExtraLines(IReservedItemView $item);
}

class ExportFactory implements IExportFactory
{
    public function __construct() {}

    /**
     * Returns the ICal Classification for an item (https://icalendar.org/iCalendar-RFC-5545/3-8-1-3-classification.html)
     * @param IReservedItemView $item
     * @return string
     */
    public function GetIcalendarClassification(IReservedItemView $item) {
        return 'PUBLIC';
    }

    /**
     * Optionally returns some lines to add to the iCalendar event.
     * @param IReservedItemView $item
     * @return null|string
     */
    public function GetIcalendarExtraLines(IReservedItemView $item) {
        return null;
    }
}
