<?php
function echoJsonResponse(bool $ok, string $message): void {
    $response = ["ok" => $ok, "message" => $message];
    echo json_encode($response);
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
