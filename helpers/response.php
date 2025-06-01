<?php

function jsonResponse($data = null, $message = '', $status = 200, $error = null)
{
    http_response_code($status);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data,
        'error' => $error
    ]);
    exit;
}
