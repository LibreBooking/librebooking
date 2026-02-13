<?php

class ArrayDiff
{
    private $_added = [];
    private $_removed = [];
    private $_unchanged = [];

    public function __construct($array1, $array2)
    {
        $added = array_diff($array2, $array1);
        $removed = array_diff($array1, $array2);
        $unchanged = array_intersect($array1, $array2);

        if (!empty($added)) {
            $this->_added = array_merge($added);
        }
        if (!empty($removed)) {
            $this->_removed = array_merge($removed);
        }
        if (!empty($unchanged)) {
            $this->_unchanged = array_merge($unchanged);
        }
    }

    public function AreDifferent()
    {
        return !empty($this->_added) || !empty($this->_removed);
    }

    public function GetAddedToArray1()
    {
        return $this->_added;
    }

    public function GetRemovedFromArray1()
    {
        return $this->_removed;
    }

    public function GetUnchangedInArray1()
    {
        return $this->_unchanged;
    }

    /**
     * Eliminates duplicate objects from array
     */
    public static function EliminateDuplicates($array, $keep_key_assoc = false)
    {
        $duplicate_keys = [];
        $tmp = [];

        foreach ($array as $key => $val) {
            // convert objects to arrays, in_array() does not support objects
            if (is_object($val)) {
                $val = (array)$val;
            }

            if (!in_array($val, $tmp)) {
                $tmp[] = $val;
            } else {
                $duplicate_keys[] = $key;
            }
        }

        foreach ($duplicate_keys as $key) {
            unset($array[$key]);
        }

        return $keep_key_assoc ? $array : array_values($array);
    }
}
