<?php

declare(strict_types=1);

namespace LibreBooking\Calendar;

/**
 * RFC 5546 iTip method for an ICS VCALENDAR.
 */
enum IcsMethod: string
{
    case PUBLISH = 'PUBLISH';
    case REQUEST = 'REQUEST';
    case CANCEL = 'CANCEL';
}
