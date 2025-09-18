<?php

namespace App\Core\Utils\SQLBuilder;

use InvalidArgumentException;
use App\Core\Enums\Separators;
use App\Core\Enums\Operators\Logical;
use App\Core\Enums\Operators\Comparison;
use App\Core\Utils\SQLBuilder\Predicate;

class Condition {
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
        // ECHO "values: "; print_r($values); ECHO "<BR>";

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
