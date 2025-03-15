<?php
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/error_log.log");
ini_set("display_errors", 0);

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        http_response_code(500);
        error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}" . PHP_EOL);
        echo json_encode(["ok" => false, "message" => "A server error occured."]);
    }
});
