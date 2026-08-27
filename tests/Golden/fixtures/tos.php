<?php

$base = require __DIR__ . '/root-pages-base.php';

return array_merge($base, [
    // ── tos.twig requirements ────────────────────────────────────────────
    // Plain-text terms content; |nl2br will convert newlines to <br>.
    // No HTML-special characters so Smarty and Twig produce identical output.
    'TermsContent' => "These are the terms of service.\nPlease read them carefully.",
]);
