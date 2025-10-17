<?php

// namespace App\Core\Helpers;

use App\Core\Enums\ByteToUnit;
use App\Models\User;
use App\Core\Exceptions\InvalidRouteException;


/**
 * Redirect the client to a public PHP page.
 *
 * Similar to Django's `redirect()`, but instead of a route name
 * it expects the base filename (without `.php`) of a public page.
 *
 * @param string $url
 *     The base filename of the target page (e.g. `"dashboard"`).
 * @return void
 */
function redirect(string $url) {
    header("LOCATION: " . route($url));
}



/**
 * Resolve a public URL from a page name.
 *
 * Works like Django's `reverse()` but without named routes.
 * It checks that the corresponding file exists in the public
 * directory before returning the full URL.
 *
 * @param string $name
 *     Base filename (without `.php`) located in DIR_PUBLIC.
 * @return string
 *     Full public URL to the PHP file.
 */
function route(string $name): string {
    $filePath = DIR_PUBLIC . "/$name.php";
    
    if (!file_exists($filePath)) {
        throw new InvalidRouteException($filePath);
    }

    return URL_PUBLIC . "/$name.php";
}


/**
 * Get the correct path or URL to a static asset.
 *
 * Returns a public URL for typical assets (CSS, JS, images, etc.).
 * If the requested file is a PHP script, it returns the absolute
 * server path instead—handy when you need to `include` or `require`
 * that PHP file instead of linking to it.
 *
 * @param string $relativePath
 *     Path to the asset relative to the assets directory
 *     (e.g. `"css/app.css"` or `"partials/header.php"`).
 *
 * @return string
 *     - For non-PHP files: Full public URL to the asset.
 *     - For `.php` files: Absolute filesystem path.
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
 * Returns the pluraized `$word` based on the common rules of pluralization in English
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
 * Returns the missing keys in `$data` that are `$required`
 * @param mixed $required
 * @param mixed $data
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
 * Returns the wrong keys in `$data` that doesn't exists in `$allowed`
 * @param array $allowed
 * @param array $data
 * @return array
 */
function checkWrongKeys(array $allowed, array $data): array {
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


/**
 * Returns `True` if the `$_SESSION["user"]` or the given `$user` is logged-in.
 * @param User|null $user
 * @return bool|null
 */
function isAuthorized(User|null $user = null) {
    session_start();

    if ($user) {
        return $user == $_SESSION["user"] ?? null;
    }

    return isset($_SESSION["user"]);
}



/**
 * Returns the converted `$value` to `$unit`. Defaults in megabyte
 * @param int $value
 * @param ByteToUnit $unit
 * @return float|int
 */
function byteToUnit(
    int $value, 
    ByteToUnit $unit = ByteToUnit::MEGABYTE
): float {
    return $value / $unit->value;
}
