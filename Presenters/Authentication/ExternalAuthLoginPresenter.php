<?php

require_once(ROOT_DIR . 'Presenters/Authentication/LoginRedirector.php');

class ExternalAuthLoginPresenter
{
    /**
     * @var ExternalAuthLoginPage
     */
    private $page;
    /**
     * @var IWebAuthentication
     */
    private $authentication;
    /**
     * @var IRegistration
     */
    private $registration;

    public function __construct(ExternalAuthLoginPage $page, IWebAuthentication $authentication, IRegistration $registration)
    {
        $this->page = $page;
        $this->authentication = $authentication;
        $this->registration = $registration;
    }

    public function PageLoad()
    {
        if ($this->page->GetType() == 'google') {
            $this->ProcessGoogleSingleSignOn();
        }
        if ($this->page->GetType() == 'fb') {
            $this->ProcessFacebookSingleSignOn();
        }
        if ($this->page->GetType() == 'microsoft') {
            $this->ProcessMicrosoftSingleSignOn();
        }
        if ($this->page->GetType() == 'keycloak') {
            $this->ProcessKeycloakSingleSignOn();
        }
        if ($this->page->GetType() == 'oauth2') {
            $this->ProcessOauth2SingleSignOn();
        }
    }

    private function buildRedirectUri(string $configuredPath): string
    {
        $scriptUrl = rtrim(Configuration::Instance()->GetScriptUrl(), '/');
        $path = '/' . ltrim($configuredPath, '/');
        if (str_ends_with($scriptUrl, '/Web') && str_starts_with($path, '/Web/')) {
            $path = substr($path, 4);
        }
        return $scriptUrl . $path;
    }

    /**
     * Exchanges the code given by google in _GET for a token and with said token retrieves client data
     */
    private function ProcessGoogleSingleSignOn()
    {
        $client = new Google\Client();
        $client->setClientId(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_ID));
        $client->setClientSecret(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_CLIENT_SECRET));
        $client->setRedirectUri(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_GOOGLE_REDIRECT_URI));
        $client->addScope("email");
        $client->addScope("profile");

        if (isset($_GET['code'])) {
            //Token validations for the client
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            //set the access token that it received
            $client->setAccessToken($token['access_token']);

            //Using the Google API to get the user information
            $google_oauth = new Google\Service\Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();

            //Save the informations needed to authenticate the login
            $email     =  $google_account_info->email;
            $firstName = $google_account_info->given_name;
            $lastName  = $google_account_info->family_name;

            //Process $userData as needed (e.g., create a user, log in, etc.)
            $this->processUserData($email, $email, $firstName, $lastName);
        }
    }

    /**
     * Exchanges the code given by microsoft in _GET for a token and with said token retrieves client data
     */
    private function ProcessMicrosoftSingleSignOn()
    {
        if (isset($_GET['code'])) {
            $code = filter_input(INPUT_GET, 'code');

            $tokenEndpoint = 'https://login.microsoftonline.com/'
                . urlencode(Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_TENANT_ID))
                . '/oauth2/v2.0/token';

            $postData = [
                'client_id' => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_ID),
                'client_secret' => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_CLIENT_SECRET),
                'redirect_uri' => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_MICROSOFT_REDIRECT_URI),
                'code' => $code, // The authorization code obtained earlier
                'grant_type' => 'authorization_code',
                'scope' => 'user.read',
            ];

            $client = new \GuzzleHttp\Client();

            $response = $client->post($tokenEndpoint, [
                'form_params' => $postData,
            ]);

            // Decode the JSON response
            $tokenData = json_decode($response->getBody(), true);

            // Extract the access token from the response
            $accessToken = $tokenData['access_token'];

            //Get user information
            $graphApiEndpoint = 'https://graph.microsoft.com/v1.0/me';

            // Make a GET request to the Microsoft Graph API endpoint
            $response = $client->request('GET', $graphApiEndpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            // Decode the JSON response
            $userData = json_decode($response->getBody(), true);

            // Handle the user data as needed
            $email     = $userData['mail'];
            $firstName = $userData['givenName'];;
            $lastName  = $userData['surname'];

            //Process $userData as needed (e.g., create a user, log in, etc.)
            $this->processUserData($email, $email, $firstName, $lastName);
        }
    }

    /**
     * Gets token created in facebook-auth.php and exchanges it for the client data
     * Unlike the other two (microsoft and google) the token must be obtained directly in the redirect uri, therefore can't be sent here for exchange(?)
     */
    private function ProcessFacebookSingleSignOn()
    {

        $facebook_Client = new Facebook\Facebook([
            'app_id'                => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_ID),
            'app_secret'            => Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_FACEBOOK_CLIENT_SECRET),
            'default_graph_version' => 'v2.5'
        ]);

        if (isset($_SESSION['facebook_access_token'])) {
            $facebook_Client->setDefaultAccessToken(unserialize($_SESSION['facebook_access_token']));
        }
        unset($_SESSION['facebook_access_token']);

        $profile_request = $facebook_Client->get('/me?fields=name,first_name,last_name,email');
        $profile = $profile_request->getGraphUser();

        $email     = $profile->getField('email');
        $firstName = $profile->getField('first_name');
        $lastName  = $profile->getField('last_name');

        //Process $userData as needed (e.g., create a user, log in, etc.)
        $this->processUserData($email, $email, $firstName, $lastName);
    }

    private function ProcessKeycloakSingleSignOn()
    {
        $code = filter_input(INPUT_GET, 'code', FILTER_UNSAFE_RAW);
        if (!$code) {
            $this->page->ShowError(['Missing authorization code.']);
            return;
        }

        $keycloakUrl = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_URL);
        $realm = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REALM);
        $clientId = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_CLIENT_ID);
        $clientSecret = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_CLIENT_SECRET);
        $redirectUri = $this->buildRedirectUri(
            Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_KEYCLOAK_REDIRECT_URI)
        );

        $openIdConnectEndpoint = rtrim($keycloakUrl, '/') . '/realms/' . rawurlencode($realm) . '/protocol/openid-connect/';
        $tokenEndpoint = $openIdConnectEndpoint . 'token';
        $userInfoEndpoint = $openIdConnectEndpoint . 'userinfo';

        $postData = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($tokenEndpoint, ['form_params' => $postData]);
            $tokenData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $accessToken = $tokenData['access_token'] ?? null;
            if (!$accessToken) {
                $this->page->ShowError(['Keycloak: access_token missing.']);
                return;
            }
            $uResp = $client->get($userInfoEndpoint, ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]);
            $user = json_decode((string) $uResp->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            $this->page->ShowError(['Error retrieving Keycloak token: ' . $e->getMessage()]);
            return;
        }

        $email = $user['email'] ?? '';
        if ($email === '') {
            $this->page->ShowError(['Email is not set in your Keycloak profile.']);
            return;
        }

        $this->processUserData(
            $user['preferred_username'] ?? $email,
            $email,
            $user['given_name'] ?? '',
            $user['family_name'] ?? '',
            $user['phone_number'] ?? '',
            $user['organization'] ?? '',
            $user['title'] ?? ''
        );
    }

    private function ProcessOauth2SingleSignOn()
    {
        $code = filter_input(INPUT_GET, 'code', FILTER_UNSAFE_RAW);
        if (!$code) {
            $this->page->ShowError(['Missing authorization code.']);
            return;
        }

        $oauth2UrlToken  = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_URL_TOKEN);
        $oauth2UrlUserinfo = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_URL_USERINFO);
        $clientId     = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_CLIENT_ID);
        $clientSecret = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_CLIENT_SECRET);
        $redirectUri = $this->buildRedirectUri(
            Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_OAUTH2_REDIRECT_URI)
        );
        // Prepare the POST data for the token request.
        $postData = [
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'redirect_uri'  => $redirectUri,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
        ];

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($oauth2UrlToken, ['form_params' => $postData]);
            $tokenData = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $accessToken = $tokenData['access_token'] ?? null;
            if (!$accessToken) {
                $this->page->ShowError(['Oauth2: access_token missing.']);
                return;
            }
            $uResp = $client->get($oauth2UrlUserinfo, ['headers' => ['Authorization' => 'Bearer ' . $accessToken]]);
            $user = json_decode((string) $uResp->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            $this->page->ShowError(['Error retrieving Oauth2 token: ' . $e->getMessage()]);
            return;
        }

        $email = $user['email'] ?? '';
        if ($email === '') {
            $this->page->ShowError(['Email is not set in your Oauth2 profile.']);
            return;
        }

        $this->processUserData(
            $user['preferred_username'] ?? $email,
            $email,
            $user['given_name'] ?? '',
            $user['family_name'] ?? '',
            $user['phone_number'] ?? '',
            $user['organization'] ?? '',
            $user['title'] ?? ''
        );
    }


    /**
     * Processes user given data, creates a user in database if it doesn't exist and logs it in
     */
    private function processUserData($username, $email, $firstName, $lastName, $phone = null, $organization = null, $title = null)
    {
        $requiredDomainValidator = new RequiredEmailDomainValidator($email);
        $requiredDomainValidator->Validate();
        $allowRegistration = Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_ALLOW_SELF, new BooleanConverter());
        if (!$requiredDomainValidator->IsValid()) {
            $this->page->ShowError(array(Resources::GetInstance()->GetString('InvalidEmailDomain')));
            return;
        }
        if ($this->registration->UserExists($username, $email)) {
            $this->authentication->Login($email, new WebLoginContext(new LoginData()));
            LoginRedirector::Redirect($this->page);
        } else {
            if ($allowRegistration) {
                $this->registration->Synchronize(
                    user: new AuthenticatedUser(
                        $username,
                        $email,
                        $firstName,
                        $lastName,
                        Password::GenerateRandom(),
                        Resources::GetInstance()->CurrentLanguage,
                        Configuration::Instance()->GetDefaultTimezone(),
                        $phone,
                        $organization,
                        $title
                    ),
                    insertOnly: false,
                    overwritePassword: false
                );
                $this->authentication->Login($email, new WebLoginContext(new LoginData()));
                LoginRedirector::Redirect($this->page);
            } else {
                $this->page->ShowError(array(Resources::GetInstance()->GetString('SelfRegistrationDisabled')));
                return;
            }
        }
    }
}
