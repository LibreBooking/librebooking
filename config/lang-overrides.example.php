<?php

/*

To use this, the file needs to be renamed to `config/lang-overrides.php`

Both arrays ($langOverrides, $langOverridesByLanguage) can be used in the same
file. Per-language overrides ($langOverridesByLanguage) are applied after
global overrides ($langOverrides), so they take precedence when both define the
same key.

Notes:

 - The file path must be config/lang-overrides.php
 - Override keys must match the existing translation string keys used by the
   application. They must be identical in case.
   For example: 'Login' will not match 'LogIn' (one has an upper case 'I')
 - This only overrides string entries; it does not override date, day, or month
   definitions

See
https://librebooking.readthedocs.io/en/latest/CONFIGURATION.html#language-string-overrides
for more details.

*/

// The $langOverrides array applies to all languages:
$langOverrides = [
    'LogIn' => 'Sign in to Example.com reservation system',
    'Register' => 'Request an Example.com account',
];

// The $langOverridesByLanguage array allows overrides scoped to specific
// languages. Use the language code (e.g. en_us, fr_fr) as the key:
$langOverridesByLanguage = [
    'fr_fr' => [
        'LogIn' => 'Se connecter à la réservation',
        'Register' => 'Demander un compte',
    ],
    'fi_fi' => [
        'LogIn' => 'Kirjaudu huonevaraukseen',
        'Register' => 'Pyydä tiliä',
    ],
    'de_de' => [
        'LogIn' => 'Anmeldung zur Raumbuchung',
    ],
];
