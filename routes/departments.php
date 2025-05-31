<?php
// routes/departments.php

require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/DepartmentController.php';

$method = $_SERVER['REQUEST_METHOD'];
$user = authMiddleware(); // Validates and gets user
$controller = new DepartmentController();

// Route: GET /departments
if ($method === 'GET') {
    $controller->index();
}

// Route: POST /departments
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->store($data, $user);
}

// Route: PUT /departments?id=1
elseif ($method === 'PUT' && isset($_GET['id'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->update($_GET['id'], $data, $user);
}

// Route: DELETE /departments?id=1
elseif ($method === 'DELETE' && isset($_GET['id'])) {
    $controller->delete($_GET['id'], $user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Invalid method or parameters']);
}
