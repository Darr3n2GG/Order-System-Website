<?php
function echoJsonResponse(bool $ok, string $message): void {
    echo json_encode(["ok" => $ok, "message" => $message]);
}

function echoJsonException(string $message): void {
    http_response_code(400);
    echoJsonResponse(false, $message);
}

function setJsonExceptionHandler(): void {
    set_exception_handler(function ($e) {
        echoJsonException($e->getMessage());
    });
}
