<?php

use Application\Classes\Core;

const DEBUG_MODE = true;

ini_set('error_reporting', DEBUG_MODE ? E_ALL : false);
ini_set('display_errors', DEBUG_MODE);
ini_set('display_startup_errors', DEBUG_MODE);

const ROOT_DIR = __DIR__;

require_once ROOT_DIR . '/vendor/autoload.php';

const MySQL_HOST = '';
const MySQL_USER = '';
const MySQL_PASS = '';
const MySQL_BASE = '';

try {
    Core::run();
} catch (Exception $e) {
    exit($e->getMessage());
}
