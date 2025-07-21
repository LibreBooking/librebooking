<?php

abstract class Control
{
    /**
     * @var SmartyPage|\Smarty\Smarty
     */
    protected $smarty = null;

    /**
     * @var string
     */
    protected $id = null;

    /**
     * @var \Smarty\Data
     */
    protected $data = null;

    /**
     * @param SmartyPage $smarty
     */
    public function __construct(SmartyPage $smarty)
    {
        $this->smarty = $smarty;
        $this->id = uniqid();

        $this->data = $smarty->createData();
    }

    public function Set($var, $value)
    {
        $this->data->assign($var, $value);
    }

    protected function Get($var)
    {
        return $this->data->getTemplateVars($var);
    }

    protected function Display($templateName)
    {
        $tpl = $this->smarty->createTemplate($templateName, $this->data);
        $tpl->display();
    }

    abstract public function PageLoad();
}
