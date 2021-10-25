<?php

namespace model;

class DbModel
{
    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name) {
        return $this->$name;
    }

    /**
     * @param array $dbArray
     * @return DbModel
     */
    public function fromObject(array $dbArray): DbModel {
        $class = get_class($this);
        $dtObject = new $class();
        foreach (get_object_vars($this) as $string => $value) {
            if (array_key_exists($string, $dbArray)) {
                $dtObject->{$string}($dbArray[$string]);
            }
        }
        return $dtObject;
    }
}