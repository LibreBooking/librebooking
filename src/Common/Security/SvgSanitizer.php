<?php

declare(strict_types=1);

namespace LibreBooking\Common\Security;

use enshrined\svgSanitize\Sanitizer;

/**
 * Cleans uploaded SVG images (logo, favicon). An SVG opened directly in the
 * browser runs its scripts on our origin, so uploads are stored sanitized.
 */
final class SvgSanitizer
{
    /**
     * @return string|null the cleaned SVG, or null when the input is not a usable SVG
     */
    public static function sanitize(string $svg): ?string
    {
        $sanitizer = new Sanitizer();
        // Also drop references to other hosts, which would leak visitors to them
        $sanitizer->removeRemoteReferences(removeRemoteRefs: true);

        $clean = $sanitizer->sanitize(dirty: $svg);

        // sanitize() returns false for broken XML and for a root that is not <svg>
        return $clean === false || $clean === '' ? null : $clean;
    }
}
