<?php
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/TicketNoteController.php';

$method = $_SERVER['REQUEST_METHOD'];
$user = authMiddleware();
$noteController = new TicketNoteController();


if ($method === 'GET' && isset($_GET['ticket_id'])) {
    $noteController->index($_GET['ticket_id']);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $noteController->store($data, $user);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
