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
        $letters = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_MINIMUM_LETTERS, new IntConverter());
        $numbers = Configuration::Instance()->GetKey(ConfigKeys::PASSWORD_MINIMUM_NUMBERS, new IntConverter());

        $passwordNumbers = preg_match_all("/[^a-zA-Z]/", $this->password, $m1);
        $passwordUpper = preg_match_all("/[A-Z]/", $this->password, $m2);
        $passwordLower = preg_match_all("/[a-z]/", $this->password, $m3);
        $passwordLetters = strlen($this->password);

        if (empty($letters)) {
            $letters = 6;
        }

        $this->isValid = $passwordNumbers >= $numbers && $passwordLetters >= $letters;

        if ($caseRequirements) {
            $this->isValid = $this->isValid && $passwordUpper > 0 && $passwordLower > 0;
        }

        if (!$this->IsValid()) {
            if (!$caseRequirements) {
                $this->AddMessage(Resources::GetInstance()->GetString('PasswordError', [$letters, $numbers]));
            } else {
                $this->AddMessage(Resources::GetInstance()->GetString('PasswordErrorRequirements', [$letters, $numbers]));
            }
        }
    }
}
