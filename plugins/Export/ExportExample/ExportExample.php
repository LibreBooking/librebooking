<?php

class ExportExample implements IExportFactory
{
    /**
     * @var ExportFactory
     */
    private $factoryToDecorate;

    public function __construct(ExportFactory $factoryToDecorate)
    {
        $this->factoryToDecorate = $factoryToDecorate;
    }

    public function GetIcalendarClassification(IReservedItemView $item) {
        return 'PRIVATE';
    }

    public function GetIcalendarExtraLines(IReservedItemView $item) {
        return "TRANSP:TRANSPARENT\n";
    }
}
