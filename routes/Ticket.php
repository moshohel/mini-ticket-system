<?php
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../controllers/TicketController.php';

$method = $_SERVER['REQUEST_METHOD'];
$user = authMiddleware();
$ticketController = new TicketController();

// Route: GET /tickets
if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $ticketController->show($_GET['id']);
    } else {
        $ticketController->index();
    }
}

// Route: POST /tickets
elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $ticketController->store($data, $user);
}

// Route: PUT /tickets?action=assign&id=1 or /tickets?action=status&id=1
elseif ($method === 'PUT' && isset($_GET['action'], $_GET['id'])) {
    if ($_GET['action'] === 'assign') {
        $ticketController->assignToSelf($_GET['id'], $user);
    } elseif ($_GET['action'] === 'status') {
        $data = json_decode(file_get_contents('php://input'), true);
        $ticketController->updateStatus($_GET['id'], $data, $user);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
}

// Route: DELETE /tickets?id=1 (optional)
elseif ($method === 'DELETE' && isset($_GET['id'])) {
    $ticketController->delete($_GET['id'], $user);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Invalid method or parameters']);
}
