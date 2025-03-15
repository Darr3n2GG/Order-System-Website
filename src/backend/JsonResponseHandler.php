<?php
set_exception_handler(function ($e) {
    error_log($e->getMessage(), 1, __DIR__ . "/error.log");
    echoJsonException($e->getCode() ?: 500, $e->getMessage());
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
