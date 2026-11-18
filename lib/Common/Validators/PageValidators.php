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
     * @var array<int|string, IValidator>
     */
    private array $failed = [];

    public function Register($id, $validator)
    {
        $this->validators[$id] = $validator;
    }

    public function Validate()
    {
        foreach ($this->validators as $id => $validator) {
            $validator->Validate();

            if (!$validator->IsValid()) {
                $this->failed[$id] = $validator;
            }
        }

        $this->isValidated = true;
    }

    /**
     * The validators that did not pass when Validate() ran, keyed by the id
     * they were registered with.
     *
     * @return array<int|string, IValidator>
     */
    public function GetFailed(): array
    {
        return $this->failed;
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
