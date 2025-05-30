<?php
// header("Content-Type: application/json");

// echo json_encode([
//     "success" => true,
//     "message" => "Mini Support Ticketing System is running!"
// ]);

require_once '../config/database.php';

$db = new Database();
$conn = $db->connect();

echo json_encode([
    "success" => true,
    "message" => "Mini Support Ticketing System is running!",
    "db_status" => $conn ? "Connected" : "Not Connected"
]);
