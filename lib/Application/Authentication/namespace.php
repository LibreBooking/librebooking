<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/ILoginContext.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IAuthentication.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/WebLoginContext.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/WebAuthentication.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/WebServiceAuthentication.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/Authentication.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/PasswordEncryption.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/Password.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/LoginCookie.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IRegistration.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/Registration.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/AuthenticatedUser.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/CaptchaService.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IAccountActivation.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/AccountActivation.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IPostRegistration.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/PostRegistration.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/CSRFToken.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IRegistrationNotificationStrategy.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/RegistrationNotificationStrategy.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/IRegistrationPermissionStrategy.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/RegistrationPermissionStrategy.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/GuestUserService.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/GuestRegistrationNotificationStrategy.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/SetAdminFirstRegistrationStrategy.php');
