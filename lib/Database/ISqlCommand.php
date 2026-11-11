<?php

interface ISqlCommand
{
    /**
     * @param Parameters $parameters
     */
    public function SetParameters(Parameters $parameters);

    /**
     * @param Parameter $parameter
     */
    public function AddParameter(Parameter $parameter);

    /**
     * @return Parameters the parameters to bind into the query
     */
    public function GetParameters(): Parameters;

    /**
     * @return string the underlying query to be executed
     */
    public function GetQuery();

    /**
     * @return bool
     */
    public function ContainsGroupConcat();

    /**
     * @return bool
     */
    public function IsMultiQuery();
}
