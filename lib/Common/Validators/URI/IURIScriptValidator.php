<?php

interface IURIScriptValidator
{
    /**
     * Validates a given URI for malicious scripts or harmful data.
     *
     * This function checks the URI for some commonly scripts patterns (<script></script>; ''; "")
     *
     * @param string $requestURI - The request URI to be validated for malicious content.
     * @param string $redirectURL - The URL to which the user will be redirected if the URI is invalid.
     *
     * @return void - No return value. Redirection occurs if the URI is invalid.
     */
    public static function validateOrRedirect(string $requestURI, string $redirectURL): void;
}
