<?php

class PasswordComplexityValidator extends ValidatorBase implements IValidator
{
    private $password;

    public function __construct($passwordPlainText)
    {
        $this->password = $passwordPlainText;
    }

    public function Validate()
    {
        $caseRequirements = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_UPPER_AND_LOWER, new BooleanConverter());
        $length = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_MINIMUM_LENGTH, new IntConverter());
        $numbers = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_MINIMUM_NUMBERS, new IntConverter());
        $specialCharacters = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_MINIMUM_SPECIALCHAR, new IntConverter());

        $passwordNumbers = preg_match_all('/[0-9]/', $this->password, $m1);
        $passwordUpper = preg_match_all('/[A-Z]/', $this->password, $m2);
        $passwordLower = preg_match_all('/[a-z]/', $this->password, $m3);
        $passwordLength = strlen($this->password);
        $passwordSpecialCharacters = preg_match_all('/[^a-zA-Z0-9]/', $this->password, $m4);

        if (empty($length)) {
            $length = 6;
        }

        $this->isValid = $passwordNumbers >= $numbers
            && $passwordLength >= $length
            && $passwordSpecialCharacters >= $specialCharacters;

        if ($caseRequirements) {
            $this->isValid = $this->isValid && $passwordUpper > 0 && $passwordLower > 0;
        }

        if (!$this->IsValid()) {
            if (!$caseRequirements) {
                $this->AddMessage(Resources::GetInstance()->GetString('PasswordError', [$length, $numbers, $specialCharacters]));
            } else {
                $this->AddMessage(Resources::GetInstance()->GetString('PasswordErrorRequirements', [$length, $numbers, $specialCharacters]));
            }
        }
    }
}
