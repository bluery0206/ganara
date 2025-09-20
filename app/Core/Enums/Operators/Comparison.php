<?php

namespace App\Core\Enums\Operators;

/**
 * Enum Comparison
 *
 * Represents SQL comparison operators you can use when building query conditions. 
 * 
 * Used in SQLBuilders
 * 
 * @package App\Core\Enums\Operators
 * 
 * Cases:
 *  - EQUALS:   Standard equality check (`=`).
 *  - LIKE:     Pattern matching (`LIKE`).
 */
enum Comparison: string {
    case EQUALS = " = ";
    case LIKE = " LIKE ";
}
