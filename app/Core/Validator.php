<?php

namespace App\Core;

// Add custom message var

class Validator {
    public static function min($min, $value) {
        return strlen($value) >= $min ? "" : "TOO SHORT";
    }

    public static function max($max, $value) {
        return strlen($value) <= $max ? "" : "TOO LONG"   ; 
    }

    public static function required($isRequired, $value) {
        return isset($value) && $isRequired ? "" : "EMPTY";
    }

    public static function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? "" : "INVALID";
    }
}
