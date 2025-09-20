<?php

namespace App\Core\Enums;

/**
 * Enum Separators
 *
 * Common string separators you can reuse for query building.  
 *
 * Used in SQLBuilders
 * 
 * @package App\Core\Enums
 * 
 * Cases:
 *  - COMMA: Comma followed by a space (`, `).
 *  - SPACE: Single space (` `).
 *  - EMPTY: No separator (empty string).
 */
enum Separators: string {
    case COMMA = ", ";
    case SPACE = " ";
    case EMPTY = "";
}