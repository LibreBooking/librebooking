<?php

require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');

class TimeIntervalValidator extends ValidatorBase
{
    public function __construct(private $value, private $attributeName)
    {
        $this->isValid = true;
    }

    /**
     * @return void
     */
    public function Validate()
    {
        try {
            TimeInterval::Parse($this->value);
        } catch (Exception) {
            $this->isValid = false;
            $this->AddMessage("Invalid time specified for {$this->attributeName}");
        }
    }
}
