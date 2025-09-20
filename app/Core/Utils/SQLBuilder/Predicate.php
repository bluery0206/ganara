<?php

namespace App\Core\Utils\SQLBuilder;

use App\Core\Enums\Operators\Comparison;

/**
 * Class Predicate
 *
 * Generates individual SQL predicate strings (e.g. `column = ?`)
 * for use in WHERE clauses or other conditional statements.
 *
 * Example:
 * ```php
 * Predicate::generate(['username', 'password'], Comparison::EQUALS);
 * // Returns: ['username = ?', 'password = ?']
 * ```
 *
 * @package App\Core\Utils\SQLBuilder
 */
class Predicate {


    /**
     * Create an array of SQL predicates from a list of column names.
     *
     * Each column is converted into a string like
     * `"columnName <operator> ?"`, where `<operator>` is taken
     * from the provided Comparison enum.
     *
     * @param array $columnList
     *     List of column names to build predicates for.
     * @param Comparison $comparison
     *     Comparison operator to use (defaults to `Comparison::EQUALS`).
     *
     * @return array
     *     Array of predicate strings ready for use in a prepared statement.
     */
    public static function generate(
        array $columnList,
        Comparison $comparison = Comparison::EQUALS
    ): array {
        // Implode adds " $comparison ?" per elemet of the array
        // Example: ["username", "password"] -> ["username = ?", "password = ?"]
        // Then contatenate each element of the array using the $logical
        // Example: ["username = ?", "password = ?"] -> "username = ? AND password = ?"
        // In Summary: 
        //      Input->['username', 'password'] 
        //      Output->['username = ?', 'password = ?']
        return array_map(fn($column) => "{$column} {$comparison->value} ?", $columnList);
    }
}
