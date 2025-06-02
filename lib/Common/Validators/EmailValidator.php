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
        $validator = new EguliasValidator();
        $this->isValid = $validator->isValid($this->email, new RFCValidation());

        if (!$this->isValid) {
            $this->AddMessageKey('ValidEmailRequired');
        }
    }
}
