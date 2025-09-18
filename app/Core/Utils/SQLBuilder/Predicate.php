<?php

namespace App\Core\Utils\SQLBuilder;

use App\Core\Enums\Operators\Comparison;

class Predicate {
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
