<?php
set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode(["ok" => false, "message" => $e->getMessage()]);
});
