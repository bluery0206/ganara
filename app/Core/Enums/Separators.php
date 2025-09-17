<?php

namespace App\Core\Utils\SQLBuilder;

enum Separators: string {
    case COMMA = ", ";
    case SPACE = " ";
    case EMPTY = "";
}