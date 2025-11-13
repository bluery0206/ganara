<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

class RowNotFoundException extends Exception {
    public function __construct(
        string $message = "No rows found relating to the given query.", 
        int $code = 0, 
        Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}