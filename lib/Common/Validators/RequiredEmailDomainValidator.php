<?php

class RequiredEmailDomainValidator extends ValidatorBase implements IValidator
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function Validate()
    {
        $this->isValid = true;

        $domains = Configuration::Instance()->GetKey(ConfigKeys::AUTHENTICATION_REQUIRED_EMAIL_DOMAINS);

        if (empty($domains)) {
            return;
        }

        $allDomains = preg_split('/[\,\s;]/', $domains);

        $trimmed = trim($this->value);

        foreach ($allDomains as $d) {
            $d = str_replace('@', '', trim($d));
            if (BookedStringHelper::EndsWith($trimmed, '@' . $d)) {
                return;
            }
        }

        $this->isValid = false;
    }
}
