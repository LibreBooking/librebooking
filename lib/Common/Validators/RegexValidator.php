<?php

class RegexValidator extends ValidatorBase implements IValidator
{
    /**
     * @var string
     */
    private $_value;

    /**
     * @var string
     */
    private $_regex;

    public function __construct($value, $regex)
    {
        $this->_value = $value;
        $this->_regex = $regex;
    }

    public function Validate()
    {
        $this->isValid = false;
        if (preg_match($this->_regex, $this->_value)) {
            $this->isValid = true;
        }
    }
}
