<?php

class PageValidators
{
    /**
     * @var array|IValidator[]
     */
    private $validators = [];

    /**
     * @var bool
     */
    private $isValidated = false;

    /**
     * @var SmartyPage
     */
    private $page;

    public function __construct(SmartyPage|TwigRenderer $page)
    {
        $this->page = $page;
    }

    public function Register($id, $validator)
    {
        $this->validators[$id] = $validator;
    }

    public function Validate()
    {
        foreach ($this->validators as $id => $validator) {
            $validator->Validate();

            if (!$validator->IsValid()) {
                $this->page->AddFailedValidation($id, $validator);
            }
        }

        $this->isValidated = true;
    }

    public function AreAllValid()
    {
        if (!$this->isValidated) {
            $this->Validate();
        }

        foreach ($this->validators as $validator) {
            if (!$validator->IsValid()) {
                return false;
            }
        }

        return true;
    }

    public function Get($id)
    {
        if (!array_key_exists($id, $this->validators)) {
            return new NullValidator();
        }
        return $this->validators[$id];
    }
}

class NullValidator extends ValidatorBase implements IValidator
{
    public function Validate()
    {
        $this->isValid = true;
    }
}
