<?php

namespace App\Core\Enums;

/**
 * Enum FetchOption
 *
 * Defines the available PDO fetch methods for database queries.
 * 
 * Lets you specify whether to grab a single record or a full set without hard-coding the method name.
 *
 * @package App\Core\Enums
 *
 * Cases:
 *  - FETCH:     Fetch a single row (`PDO::fetch`).
 *  - FETCH_ALL: Fetch all rows (`PDO::fetchAll`).
 */
enum FetchOption: string {
    case FETCH = "fetch";
    case FETCH_ALL = "fetchAll";
}