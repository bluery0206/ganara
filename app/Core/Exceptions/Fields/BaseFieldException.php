<?php 

declare(strict_types=1);

namespace App\Core\Exceptions\Fields;

use Exception;
use Throwable;


/**
 * The base class for form-field-based exceptions
 */
class BaseFieldException extends Exception {
    /**
     * Summary of field
     * @var string
     */
    public string $field;


    public function __construct(
        string $field,
        string $message,
        int $code = 0,
        Throwable $previous = null,
    ) {
        $this->field = $field;
        parent::__construct($message, $code, $previous);
    }


    /**
     * Summary of getField
     * @return string
     */
    public function getField(): string {
        return $this->field;
    }
}
