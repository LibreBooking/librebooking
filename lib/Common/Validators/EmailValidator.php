<?php

use Egulias\EmailValidator\EmailValidator as EguliasValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

class EmailValidator extends ValidatorBase implements IValidator
{
    private $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function Validate()
    {
        // Check for null or empty email first
        if (empty($this->email)) {
            $this->isValid = false;
            $this->AddMessageKey('ValidEmailRequired');
            return;
        }

        $validator = new EguliasValidator();
        $this->isValid = $validator->isValid($this->email, new RFCValidation());

        if (!$this->isValid) {
            $this->AddMessageKey('ValidEmailRequired');
        }
    }
}
