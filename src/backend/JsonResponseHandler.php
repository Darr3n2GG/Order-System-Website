<?php
ini_set("log_errors", 1);
ini_set("error_log", __DIR__ . "/log/error_log.log");
ini_set("display_errors", 0);

set_exception_handler(function ($e) {
    error_log($e->getMessage() . PHP_EOL);
    echoJsonException($e->getCode() ?: 500, $e->getMessage());
});

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE)) {
        http_response_code(500);
        error_log("Fatal Error: {$error['message']} in {$error['file']} on line {$error['line']}" . PHP_EOL);
        echoJsonResponse(false, "A server error occured.");
    }
});

function echoJsonResponse(bool $ok, string $message, array|null $details = null): void {
    $response = ["ok" => $ok, "message" => $message];
    if ($details) {
        $response = array_merge($response, ["details" => $details]);
    }
    echo json_encode($response);
}

function echoJsonException(int $code, string $message): void {
    http_response_code($code ?: 500);
    echo json_encode(["ok" => false, "message" => $message]);
}
