<?php

const DEBUG = true;


/* =============
DIRECTORY CONFIG
============= */
const DIR_ROOT = __DIR__;
const DIR_PUBLIC = DIR_ROOT . "/public";
const DIR_ASSET = DIR_ROOT . "/asset";

# If you're in development and have multiple projects inside your `htdocs/` or `html/` folders, then
# You can add those sub-directories here. For example: "/commissions/task-manager", else
# If in deployment, where there should only be the project folder or files, leave "/" as is.
const URL_BASE = "/boilerplate";

# Converted upper-cased (i.e.: HTTP/1.0) "PROTOCOL/NUMBER" into "protocol/number"; then
# Separated the string by "/" resulted to ["protocol", "number"]; then
# Indexed the very first (index 0) element to return "protocol"; then
# Which we have is either "http" or "https" only.
define("SERVER_PROTOCOL", explode("/", strtolower($_SERVER['SERVER_PROTOCOL']))[0]);
define("SERVER_NAME", $_SERVER['SERVER_NAME']);
define("URL_ROOT", SERVER_PROTOCOL . "://" . SERVER_NAME . URL_BASE);

const URL_PUBLIC = URL_ROOT . "/public";
const URL_ASSET = URL_ROOT . "/asset";


/* =============
DEFAULTS CONFIG
============= */