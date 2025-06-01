<?php
// middlewares/AuthMiddleware.php

function authMiddleware(): array|null
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Authorization token missing']);
        exit;
    }

    $token = trim(str_replace('Bearer', '', $headers['Authorization']));

    $tokensFile = __DIR__ . '/../storage/tokens.json';
    if (!file_exists($tokensFile)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized token not found']);
        exit;
    }

    $tokens = json_decode(file_get_contents($tokensFile), true);

    foreach ($tokens as $storedToken => $user) {
        if ($storedToken === $token) {
            return $user; // return user data (e.g., ['id' => 1, 'role' => 'admin'])
        }
    }

    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired token']);
    exit;
}
