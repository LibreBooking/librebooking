<?php

declare(strict_types=1);

namespace LibreBooking\Application\Authentication;

/**
 * Classification of a Microsoft OAuth callback request.
 */
enum MicrosoftOAuthCallbackOutcome
{
    case SUCCESS;
    case OAUTH_ERROR;
    case MALFORMED_REQUEST;
}
