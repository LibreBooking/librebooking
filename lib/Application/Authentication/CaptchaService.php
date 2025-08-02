<?php

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}

interface ICaptchaService {
    /**
     * @abstract
     * @return string
     */
    public function GetImageUrl();

    /**
     * @abstract
     * @param string $captchaValue
     * @return bool
     */
    public function IsCorrect($captchaValue);
}

class NullCaptchaService implements ICaptchaService
{
    /**
     * @return string
     */
    public function GetImageUrl()
    {
        return '';
    }

    /**
     * @param string $captchaValue
     * @return bool
     */
    public function IsCorrect($captchaValue)
    {
        return true;
    }
}

class CaptchaService implements ICaptchaService
{
    private function __construct()
    {
    }

    public function GetImageUrl()
    {
        $url = new Url(Configuration::Instance()->GetScriptUrl() . '/Services/Authentication/show-captcha.php');
        $url->AddQueryString('show', 'true');
        return $url->__toString();
    }

    public function IsCorrect($captchaValue)
    {
        $isValid = $captchaValue == $_SESSION['phrase'];

        Log::Debug('Checking captcha value. Value entered: %s. Correct value: %s.  IsValid: %s', $captchaValue,$_SESSION['phrase'] , (int)$isValid);

        return $isValid;
    }

    /**
     * @static
     * @return ICaptchaService
     */
    public static function Create()
    {
        if (
            Configuration::Instance()->GetKey(ConfigKeys::REGISTRATION_CAPTCHA_ENABLED, new BooleanConverter()) ||
            (Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_CAPTCHA_ON_LOGIN, new BooleanConverter()))
        ) {
            if (
                Configuration::Instance()->GetKey(
                    ConfigKeys::RECAPTCHA_ENABLED,
                    new BooleanConverter()
                )
            ) {
                //				Log::Debug('Using ReCaptchaService');
                return new ReCaptchaService();
            }
            //			Log::Debug('Using CaptchaService');
            return new CaptchaService();
        }

        return new NullCaptchaService();
    }
}

class ReCaptchaService implements ICaptchaService
{
    /**
     * @return string
     */
    public function GetImageUrl()
    {
        return '';
    }

    /**
     * @param string $captchaValue
     * @return bool
     */
    public function IsCorrect($captchaValue)
    {
        $server = ServiceLocator::GetServer();

        $privatekey = Configuration::Instance()->GetKey(ConfigKeys::RECAPTCHA_PRIVATE_KEY);

        $configuredMethod = Configuration::Instance()->GetKey(ConfigKeys::RECAPTCHA_REQUEST_METHOD);
        $method = new \ReCaptcha\RequestMethod\Post();
        switch ($configuredMethod)
        {
            case null:
            case '':
            case 'post':
                break;
            case 'socket':
                $method = new \ReCaptcha\RequestMethod\SocketPost();
                break;
            case 'curl':
                $method = new \ReCaptcha\RequestMethod\CurlPost();
                break;
            default:
                Log::Error('Invalid ReCaptcha request method: %s. Fallback to', $configuredMethod);
        }

        $recap = new \ReCaptcha\ReCaptcha($privatekey, $method);
        $resp = $recap->verify($server->GetForm('g-recaptcha-response'),$server->GetRemoteAddress());

        $success = $resp->isSuccess();
        Log::Debug('ReCaptcha IsValid: %s', $success ? 'TRUE' : 'FALSE');
        if (!$success)
            Log::Debug('ReCaptcha error codes: %s', join(', ', $resp->getErrorCodes()));

        return $success;
    }
}
