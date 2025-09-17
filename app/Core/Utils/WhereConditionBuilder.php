<?php

namespace App\Core\Utils;

use App\Core\Enums\Operators\Comparison;
use App\Core\Enums\Operators\Logical;
use InvalidArgumentException;

class WhereConditionBuilder {
    public static function build(
        array $conditions,
        Logical $logical = Logical::AND,
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

        // Implode adds " $comparison ?" per elemet of the array
        // Example: ["username", "password"] -> ["username = ?", "password = ?"]
        // Then contatenate each element of the array using the $logical
        // Example: ["username = ?", "password = ?"] -> "username = ? AND password = ?"
        $formattedColumnFilter = implode(
            $logical->value,
            array_map(fn($col) => "{$col} {$comparison->value} ?", $columnFilter)
        );
        // ECHO "formattedColumnFilter: "; print_r($formattedColumnFilter); ECHO "<BR>";

        // Concatenates the complete WHERE expression
        return "WHERE $formattedColumnFilter";
    }
}
