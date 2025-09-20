<?php

namespace App\Core;

use InvalidArgumentException;


/**
 * Summary of Validator
 */
class Validator {


    /**
     * Summary of min
     * @param int $min
     * @param string|int $value
     * @param string $errorMessage
     * @throws \InvalidArgumentException
     * @return array<bool|string>
     */
    public static function min(
        int $min, 
        string|int $value,
        string $errorMessage = "Too short"
    ): array {
        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException(
                "Data type must be either an int or a string."
            );
        }

        if (is_string($value)) {
            $value = strlen($value);
        } 

        return $value >= $min ? [True, "Valid"] : [False, $errorMessage];
    }


    /**
     * Summary of max
     * @param int $max
     * @param string|int $value
     * @param string $errorMessage
     * @throws \InvalidArgumentException
     * @return array<bool|string>
     */
    public static function max(
        int $max, 
        string|int $value,
        string $errorMessage = "Too long"
    ): array {
        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException(
                "min() value must be either an int or a string."
            );
        }

        if (is_string($value)) {
            $value = strlen($value);
        } 

        return $value <= $max ? [True, "Valid"] : [False, $errorMessage];
    }


    /**
     * Summary of required
     * @param mixed $value
     * @param string $errorMessage
     * @return array<bool|string>
     */
    public static function required(
        mixed $value,
        string $errorMessage = "This field is required. Cannot be empty."
    ): array {
        return strlen((string)$value) > 0 ? [True, "Valid"] : [False, $errorMessage];
    }


    /**
     * Summary of email
     * @param mixed $value
     * @param string $errorMessage
     * @return array<bool|string>
     */
    public static function email(
        mixed $value,
        string $errorMessage = "Invalid format"
    ): array {
        return filter_var($value, FILTER_VALIDATE_EMAIL) ? [True, "Valid"] : [False, $errorMessage];
    }


    /**
     * Summary of validate
     * @param \App\Core\BaseModel $model
     * @param array $data
     * @return array<array>
     */
    public static function validate(BaseModel $model, array $data): array {
        $modelRules = $model->rules;

        checkWrongKeys($model->columns, array_keys($data)); 

        $violations = [];

        foreach ($modelRules as $field => $fieldRules) {
            // echo "Field: $field <BR>";

            $fieldViolations = [];

            // echo "--- Rules: <BR>";

            // echo "------ "; print_r($fieldRules); echo "<BR>";

            // Separating rules separated by "|"
            $fieldRules = explode("|", $fieldRules);
            // echo "------ "; print_r($fieldRules); echo "<BR>";

            foreach ($fieldRules as $ruleData) {
                // echo "--------- "; var_dump($ruleData); echo "<BR>";

                // Remember we did
                //      $this->rules = [
                //          "field" => "max:1|required",
                //      ]
                // On our model $rules?
                // We have a valued rule (max:1) and one without (required)
                // And for the valued one, the value is separated by ":"
                // So we identify if the stringed $ruleData have a ":"
                // And if it has it, then we assume its a valued rule
                // Since method for valued requires two arguments
                // And the non-valued, one.
                if (str_contains($ruleData, ":")) {
                    // Unpacks or create individual variables for $rule and $value
                    // explode() separates the string into an array from the given separator (":")
                    // And since our valued $ruleData is expected to have the rule (min) and the value (1),
                    // Then we just assign them into each variables
                    [$rule, $value] = explode(":", $ruleData);

                    // Validator methods returns an array [$isValid, $message]
                    // $data[field] is us getting the value in the field
                    // Example: getting the value of $data[password] -> "somepassword"
                    [$isValid, $message] = Validator::$rule($value, $data[$field]);
                    // echo "------ $rule: $value, supplied: \"" . $data[$field] . "\"";
                    // echo $isValid ? " isValid: $isValid <BR>" : "<BR>";
                }
                else {
                    [$isValid, $message] = Validator::$ruleData($data[$field]);
                    // echo "------ $rule, supplied: \"" . $data[$field] . "\" isValid: $isValid <BR>";

                    // I kept $isValid and $message separated for debugging and readability
                }

                if (!$isValid) {
                    array_push($fieldViolations, [$isValid, $message]);
                }
            }

            // var_dump($fieldViolations);

            if ($fieldViolations) {
                $violations[$field] = $fieldViolations;
            }

            // echo "<BR><BR>";
        }

        return $violations;
    }
}
