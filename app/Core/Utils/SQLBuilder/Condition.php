<?php

namespace App\Core\Utils\SQLBuilder;

use InvalidArgumentException;
use App\Core\Enums\Separators;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;
use App\Core\Utils\SQLBuilder\Predicate;


/**
 * Class Condition
 *
 * Utility for building SQL WHERE conditions.  
 * Validates that each condition includes a column key and
 * then generates predicates joined by a logical operator
 * or separator.
 *
 * Example:
 * ```php
 * Condition::generate(
 *     ['username' => 'aori', 'status' => 'active'],
 *     Logical::AND,
 *     Comparison::EQUALS
 * );
 * // Outputs: "username = ? AND status = ?"
 * ```
 *
 * @package App\Core\Utils\SQLBuilder
 */
class Condition {


    /**
     * Build a SQL WHERE clause from an associative array of column/value pairs.
     *
     * @param array<string,mixed> $conditions
     *     Key/value pairs where the key is the column name
     *     and the value is the expected value to bind.
     * @param Logical|Separators $logical
     *     How to join multiple predicates (e.g. `Logical::AND`, `Separators::COMMA`).
     *     Defaults to `Logical::AND`.
     * @param Comparison $comparison
     *     Comparison operator for each predicate (e.g. `Comparison::EQUALS`, `Comparison::LIKE`).
     *     Defaults to `Comparison::EQUALS`.
     *
     * @return string
     *     A full WHERE clause string with placeholders, ready for prepared statements.
     */
    public static function generate(
        array $conditions,
        Logical|Separators $logical = Logical::AND,
        Comparison $comparison = Comparison::EQUALS
    ): string {
        // ECHO "conditions: "; print_r($conditions); ECHO "<BR>";

        // Checks if all columns or keys have a value
        // By checking if a key's data type is a string or not
        // Because if it's specified, then it's expected be a string
        // Examples:
        //      OK:
        //          ["username" => "aori"] -> key: "username", value: "aori"
        //      NOT OK:
        //          ["username"] -> key: 0, value: username
        foreach ($conditions as $key => $value) {
            if (!is_string($key)) {
                throw new InvalidArgumentException(
                    "Column \"{$value}\" doesn't have a value."
                );
            }
        }

        $columnFilter = array_keys($conditions); // The columns for WHERE

        // Creates predicates like "columnName = ?" per elemet of the array
        // Then contatenate each element of the array using the $logical
        $predicateWhere = Predicate::generate(
            $columnFilter,
            $comparison
        );
        // ECHO "predicateWhere: "; print_r($predicateWhere); ECHO "<BR>";

        // Concatenates the complete WHERE expression and return it
        return implode(
            $logical->value,
            $predicateWhere
        );
    }
}
