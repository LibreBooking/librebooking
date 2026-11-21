<?php

$base = require __DIR__ . '/root-pages-base.php';

return array_merge($base, [
    // ── guest-participation.twig requirements ───────────────────────────
    'IsMissingInformation' => false,
    'InvitationAccepted' => false,
    'InvitationDeclined' => false,
    'IsGuest' => false,
    'AllowRegistration' => false,
    'CapacityReached' => false,
    'CapacityErrorMessage' => '',
]);
