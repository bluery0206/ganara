<?php

// namespace App\Core\Helpers;

use App\Core\Exceptions\InvalidRouteException;

function redirect(string $url) {
    header("LOCATION: " . route($url));
}

function route(string $name): string {
    $filePath = DIR_PUBLIC . "/$name.php";
    
    if (!file_exists($filePath)) {
        throw new InvalidRouteException($filePath);
    }

    return URL_PUBLIC . "/$name.php";
}

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

function isViewActive(string $view, string $returnOnTrue = "active"): string {
    $viewPath = route($view);
    $viewName = getFileName($viewPath);

    $currentViewName = getFileName($_SERVER["PHP_SELF"]);

    return $currentViewName == $viewName ? $returnOnTrue : "" ;
}

function getFileName(string $filePath): string {
    return pathinfo($filePath, PATHINFO_FILENAME);
}