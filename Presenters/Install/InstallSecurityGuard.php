<?php

class InstallSecurityGuard
{
    public const VALIDATED_INSTALL = 'validated_install';

    /**
     * true if password exists
     * false if password is missing
     * @return bool
     */
    public function CheckForInstallPasswordInConfig()
    {
        $installPassword = Configuration::Instance()->GetKey(ConfigKeys::INSTALL_PASSWORD);

        if (empty($installPassword)) {
            return false;
        }

        return true;
    }

    /**
     * true if password is correct
     * false if password is incorrect
     * @param string $installPassword
     * @return bool
     */
    public function ValidatePassword($installPassword)
    {
        $validated = $installPassword == Configuration::Instance()->GetKey(ConfigKeys::INSTALL_PASSWORD);

        if ($validated) {
            ServiceLocator::GetServer()->SetSession(SessionKeys::INSTALLATION, self::VALIDATED_INSTALL);
        } else {
            ServiceLocator::GetServer()->SetSession(SessionKeys::INSTALLATION, null);
        }

        return $validated;
    }


    public function IsAuthenticated()
    {
        return ServiceLocator::GetServer()->GetSession(SessionKeys::INSTALLATION) == self::VALIDATED_INSTALL;
    }
}
