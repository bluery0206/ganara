<?php

// namespace App\Core\Helpers;

use App\Core\Exceptions\InvalidRouteException;


/**
 * Summary of redirect
 * @param string $url
 * @return void
 */
function redirect(string $url) {
    header("LOCATION: " . route($url));
}


/**
 * Summary of route
 * @param string $name
 * @return string
 */
function route(string $name): string {
    $filePath = DIR_PUBLIC . "/$name.php";
    
    if (!file_exists($filePath)) {
        throw new InvalidRouteException($filePath);
    }

    return URL_PUBLIC . "/$name.php";
}


/**
 * Summary of asset
 * @param string $relativePath
 * @return string
 */
function asset(string $relativePath): string {
    $extension = pathinfo($relativePath, PATHINFO_EXTENSION);
    // echo "EXTENSION: $extension<BR>";

    $pathAbsolute =  DIR_ASSET . "/$relativePath";
    $pathURL =  URL_ASSET . "/$relativePath";
    // echo "ABS_PATH: {$pathAbsolute}<BR>";
    // echo "URL_PATH: {$pathURL}<BR><BR>";

    if (!file_exists($pathAbsolute)) {
        throw new InvalidRouteException($pathAbsolute);
    }

    return $extension == "php" ? $pathAbsolute : $pathURL;
}


/**
 * Checks if the given view name ("../fileName") is the same as the given view ("../fileName.php").
 * If they are the same, return the $returnOnTrue value, else return an empty string.
 * 
 * Useful for setting active class for navigation links.
 * @param string $view
 * @param string $returnOnTrue
 * @return string
 */
function isViewActive(string $view, string $returnOnTrue = "active"): string {
    $viewName = pathinfo(route($view), PATHINFO_FILENAME);
    $currentViewName = pathinfo($_SERVER["PHP_SELF"], PATHINFO_FILENAME);
    return $currentViewName == $viewName ? $returnOnTrue : "" ;
}


/**
 * Summary of pluralize
 * @param string $word
 * @return string
 */
function pluralize(string $word): string {
    // Reverses the order of the characters within the string.
    // Then get the first character (last character of the previously unreversed string).
    $lastCharacter = strrev($word)[0];

    // This is just based on the common rules of pluralization in English
    switch ($lastCharacter) {
        case "s":
            return "{$word}es";
        case "y":
            return str_replace("y", "ies", $word);
        default:
            return "{$word}s";
    }
}


/**
 * Summary of checkMissingKeys
 * @param mixed $required
 * @param mixed $data
 * @throws \InvalidArgumentException
 * @return array
 */
function checkMissingKeys($required, $data): array {
    $lackingKeys = [];

    // Checks if the $this->requires columns are supplied in $data
    foreach ($required as $value) {
        // ECHO "value: "; print_r($value); ECHO "<BR>";

        if (!key_exists($value, $data)) {
            array_push($lackingKeys, $value);
        }
    }

    if ($lackingKeys) {
        $lackingKeys = implode(", ", $lackingKeys);

        throw new InvalidArgumentException(
            "Required column(s) \"{$lackingKeys}\" not specified."
        );
    }

    return $lackingKeys;
}


/**
 * Summary of checkWrongKeys
 * @param mixed $allowed
 * @param mixed $data
 * @throws \InvalidArgumentException
 * @return array
 */
function checkWrongKeys($allowed, $data): array {
    $wrongKeys = [];

    // ECHO "allowed: "; print_r($allowed); ECHO "<BR>";
    // ECHO "data: "; print_r($data); ECHO "<BR>";

    // Checks if the $this->requires columns are supplied in $data
    foreach ($data as $value) {
        if (!in_array($value, $allowed)) {
            array_push($wrongKeys, $value);
        }
    }

    if ($wrongKeys) {
        $wrongKeys = implode(", ", $wrongKeys);

        throw new InvalidArgumentException(
            "Column(s) \"{$wrongKeys}\" is/are not allowed."
        );
    }

    return $wrongKeys;
}