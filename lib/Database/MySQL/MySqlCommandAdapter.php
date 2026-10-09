<?php

class MySqlCommandAdapter
{
    private $_values = null;
    private $_query = null;
    private $_db = null;

    public function __construct(ISqlCommand &$command, $db)
    {
        $this->_values = [];
        $this->_query = null;
        $this->_db = $db;
        $this->Convert($command);
    }

    public function GetValues()
    {
        return $this->_values;
    }

    public function GetQuery()
    {
        return $this->_query;
    }

    private function Convert(SqlCommand &$command)
    {
        $query = $command->GetQuery();
        $replacements = [];

        for ($p = 0; $p < $command->Parameters->Count(); $p++) {
            $curParam = $command->Parameters->Items($p);

            if (is_null($curParam->Value)) {
                $replacements[$curParam->Name] = 'null';
            } elseif (is_array($curParam->Value)) {
                $escapedValues = [];
                foreach ($curParam->Value as $value) {
                    $escapedValues[] = mysqli_real_escape_string($this->_db, $value ?? '');
                }
                $values = implode("','", $escapedValues);
                $replacements[$curParam->Name] = "'$values'";
            } else {
                $escapedValue = mysqli_real_escape_string($this->_db, $curParam->Value ?? '');
                $replacements[$curParam->Name] = $curParam->QuotedValue($escapedValue);
            }
        }

        if (!empty($replacements)) {
            // Single pass so an inserted value is never searched for later placeholder names.
            // Longest names first so '@userid' is not consumed by '@user'.
            $names = array_keys($replacements);
            usort($names, fn ($a, $b) => strlen($b) <=> strlen($a));
            $pattern = '/' . implode('|', array_map(fn ($name) => preg_quote($name, '/'), $names)) . '/';
            $query = preg_replace_callback($pattern, fn ($match) => $replacements[$match[0]], $query);
        }

        $this->_query = $query . ';';
    }
}
