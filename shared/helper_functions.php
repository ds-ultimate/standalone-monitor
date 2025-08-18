<?php

function exit_with_code($code) {
    http_response_code($code);
    die();
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    if (PHP_SAPI === 'cli') {
        //Write debug error
        fwrite(STDERR, "[ERROR] [$errno] $errstr in $errfile on line $errline\n");
    }
    else {
        exit_with_code(500);
    }
}

set_error_handler("myErrorHandler");

    
/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function startsWith($haystack, $needle) {
    return $needle === "" || strpos($haystack, $needle) === 0;
}

/**
 * @param $haystack
 * @param $needle
 * @return bool
 */
function endsWith($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}
