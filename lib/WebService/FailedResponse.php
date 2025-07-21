<?php

class FailedResponse extends RestResponse
{
    /**
     * @var array|string[]
     */
    public $errors;

    /**
     * @param array|string[] $errors
     */
    public function __construct($errors)
    {
        $this->message = 'There were errors processing your request';
        $this->errors = $errors;
    }
}
