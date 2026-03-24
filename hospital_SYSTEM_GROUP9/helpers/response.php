<?php
// helpers/response.php

function sendResponse(int $statusCode, bool $success, string $message, mixed $data = null): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data'    => $data,
    ]);
    exit;
}

// reads raw JSON body from the request
function getRequestBody(): array {
    $body = file_get_contents('php://input');
    return json_decode($body, true) ?? [];
}
