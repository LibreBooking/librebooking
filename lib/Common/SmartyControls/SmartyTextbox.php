<?php

class SmartyTextbox
{
    private $name;
    private $type;
    private $id;
    private $attributes;
    private $smartyVariable;
    private $smarty;
    private $required;

    public function __construct($formKey, $type, $id, $smartyVariable, $attributes, $required, &$smarty)
    {
        $this->name = $this->GetName($formKey);
        $this->type = empty($type) ? 'text' : $type;
        $this->id = empty($id) ? $this->GetName($formKey) : $id;
        $this->attributes = $attributes;
        $this->smartyVariable = $smartyVariable;
        $this->required = $required;
        $this->smarty = $smarty;
    }

    public function Html()
    {
        // Values are stored either HTML-encoded or raw, so do not encode existing entities
        $value = htmlspecialchars($this->GetValue(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
        $style = empty($this->style) ? '' : " style=\"{$this->style}\"";
        $required = $this->required ? ' required="required" ' : '';

        return "<input type=\"{$this->GetInputType()}\" name=\"{$this->name}\" id=\"{$this->id}\" value=\"$value\"{$required} $this->attributes />";
    }

    protected function GetInputType()
    {
        return $this->type;
    }

    private function GetName($formKey)
    {
        return FormKeys::Evaluate($formKey);
    }

    private function GetValue()
    {
        $value = $this->GetPostedValue();

        if (empty($value)) {
            $value = $this->GetTemplateValue();
        }

        if (!empty($value)) {
            return trim($value);
        }

        return '';
    }

    private function GetPostedValue()
    {
        return ServiceLocator::GetServer()->GetForm($this->name);
    }

    private function GetTemplateValue()
    {
        $value = '';

        if (!empty($this->smartyVariable)) {
            $var = $this->smarty->getTemplateVars($this->smartyVariable);
            if (!empty($var)) {
                $value = $var;
            }
        }

        return $value;
    }
}

class SmartyPasswordbox extends SmartyTextbox
{
    protected function GetInputType()
    {
        return 'password';
    }
}
