<?php

namespace App\Core\Exceptions;

use Exception;
use Throwable;

class InvalidRouteException extends Exception {
    public function __construct(string $route, string $message = "", int $code = 0, Throwable|null $previous = null) {
        $message = $message ?: "Route specified: \"{$route}\", doesn't exist.";
        parent::__construct($message, $code, $previous);
    }
}