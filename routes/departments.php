<?php
// routes/departments.php

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

$user = authMiddleware(); // Validates and gets user

echo json_encode([
    'message' => 'You are authorized',
    'user' => $user
]);
