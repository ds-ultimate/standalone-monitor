<?php

function exit_with_code($code) {
    http_response_code($code);
    die("err $code");
}

function myErrorHandler($errno, $errstr, $errfile, $errline) {
    exit_with_code(500);
}

set_error_handler("myErrorHandler");
