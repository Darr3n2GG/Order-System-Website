<?php
function echoJsonResponse(bool $ok, string $message, array $details = []): void {
    $response = ["ok" => $ok, "message" => $message, "details" => $details];
    echo json_encode($response);
}

function echoJsonException(string $message, array $details = []): void {
    http_response_code(400);
    echoJsonResponse(false, $message, $details);
}

function setJsonExceptionHandler(): void {
    set_exception_handler(function ($e) {
        echoJsonException($e->getMessage());
    });
}
