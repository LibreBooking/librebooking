<?php

require_once(ROOT_DIR . 'Pages/Authentication/ILoginBasePage.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'lib/Config/namespace.php');

class LoginRedirector
{
    public static function Redirect(ILoginBasePage $page)
    {
        $redirect = $page->GetResumeUrl();
        $defaultId = ServiceLocator::GetServer()->GetUserSession()->HomepageId;
        $fallback = Pages::UrlFromId($defaultId);
        if (empty($fallback)) {
            $fallback = Pages::UrlFromId(Pages::DEFAULT_HOMEPAGE_ID);
        }

        if (!empty($redirect)) {
            $page->Redirect(RedirectUrlSanitizer::Sanitize(
                url: $redirect,
                path: '',
                scriptUrl: Configuration::Instance()->GetScriptUrl(),
                fallback: $fallback
            ));
        } else {
            $page->Redirect($fallback);
        }
    }
}
