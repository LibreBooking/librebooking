<?php

$base = require __DIR__ . '/root-pages-base.php';

return array_merge($base, [
    // ── forgot_pwd.twig requirements ──────────────────────────────────────
    'Enabled' => true,
    'ShowResetEmailSent' => false,
]);
