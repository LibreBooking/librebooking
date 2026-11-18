<?php

declare(strict_types=1);

if (file_exists(ROOT_DIR . 'vendor/autoload.php')) {
    require_once ROOT_DIR . 'vendor/autoload.php';
}

use Smarty\Smarty;

class FakeSmarty extends Smarty
{
    public $_LastVar;
    public $_Value;

    public function getTemplateVars($varName = null, $searchParents = true)
    {
        $this->_LastVar = $varName;

        return $this->_Value;
    }
}
