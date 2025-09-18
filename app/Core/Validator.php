<?php

namespace App\Core;

class Validator {
    public static function min($min, $value) {
        return strlen($value) >= $min;
    }

    public static function max($max, $value) {
        return strlen($value) <= $max; 
    }

    public static function required($value) {
        return isset($value);
    }
}
 