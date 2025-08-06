<?php
session_start();
define('ROOT_DIR', '../');

require_once(ROOT_DIR . 'lib/Common/namespace.php');

//Checks if the user was authenticated by facebook and redirects to external authentication page
//Need to ask facebook token directly in the redirect_uri (?) -> Can't redirect to external auth page and then ask (???)
if (isset($_GET['code'])) {
    try{
        $facebook_Client = new Facebook\Facebook([
            'app_id'                => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_ID),
            'app_secret'            => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_SECRET),
            'default_graph_version' => 'v2.5'
        ]);

        $helper = $facebook_Client->getRedirectLoginHelper();

        // Build the full redirect URL to ensure consistency with login URL generation
        $redirectUri = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_REDIRECT_URI);
        $scriptUrl = Configuration::Instance()->GetScriptUrl();

        // Handle the case where script URL already ends with /Web and redirect URI starts with /Web
        $scriptUrl = rtrim($scriptUrl, '/');
        if (!str_starts_with($redirectUri, '/')) {
            $redirectUri = '/' . $redirectUri;
        }

        // If script URL ends with /Web and redirect URI starts with /Web, remove the duplicate
        if (str_ends_with($scriptUrl, '/Web') && str_starts_with($redirectUri, '/Web/')) {
            $redirectUri = substr($redirectUri, 4); // Remove the first "/Web"
        }

        $fullRedirectUrl = $scriptUrl . $redirectUri;

        $accesstoken = $helper->getAccessToken($fullRedirectUrl);
        $_SESSION['facebook_access_token'] = serialize($accesstoken);

        $code = filter_input(INPUT_GET,'code');
        header("Location: ".ROOT_DIR."Web/external-auth.php?type=fb&code=".$code);
        exit;
    } catch (\Facebook\Exception\ResponseException | \Facebook\Exception\SDKException $e) {
        Log::Debug("Exception during facebook login: %s", $e->getMessage());
        $_SESSION['facebook_error'] = true;
        header("Location:".ROOT_DIR."Web");
        exit();
    }

} else{
    header("Location:".ROOT_DIR."Web");
    exit();
}
?>
