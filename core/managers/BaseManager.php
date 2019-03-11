<?php
class BaseManager
{
    public function __construct() {

    }

    public function __destruct() {
        foreach (get_object_vars($this) as $key => $val) {
            $val = NULL;
            unset($this->{$key});
        }
    }
}








