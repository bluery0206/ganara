<?php

namespace App\Core\Enums\Operators;

/**
 * Enum Logical
 *
 * Represents logical operators for SQL query conditions.
 * 
 * Used in SQLBuilders
 *
 * @package App\Core\Enums\Operators
 *
 * Cases:
 *  - AND: Logical conjunction (`AND`) for combining multiple conditions.
 *  - OR:  Logical disjunction (`OR`) for matching any of the conditions.
 */
enum Logical: string {
    case AND = " AND ";
    case OR = " OR ";
}