<?php
// header("Content-Type: application/json");

// echo json_encode([
//     "success" => true,
//     "message" => "Mini Support Ticketing System is running!"
// ]);

// require_once '../config/database.php';

// $db = new Database();
// $conn = $db->connect();

// echo json_encode([
//     "success" => true,
//     "message" => "Mini Support Ticketing System is running!",
//     "db_status" => $conn ? "Connected" : "Not Connected"
// ]);
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/mini-ticket-system/public';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));

switch ($uri) {
    case '/auth':
        require __DIR__ . '/../routes/auth.php';
        break;
    case '/departments':
        require __DIR__ . '/../routes/departments.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
}
