<?php
require_once __DIR__ . '/../middlewares/auth.php';
require_once __DIR__ . '/../controllers/TicketController.php';

authMiddleware();

$ticketController = new TicketController();

$method = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        $id ? $ticketController->show($id) : $ticketController->index();
        break;
    case 'POST':
        $ticketController->store();
        break;
    case 'PUT':
        if ($action === 'assign') {
            $ticketController->assignToSelf($id);
        } elseif ($action === 'status') {
            $ticketController->updateStatus($id);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}
