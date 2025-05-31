<?php
require_once __DIR__ . '/../models/Ticket.php';

class TicketController
{
    private $ticketModel;

    public function __construct()
    {
        $this->ticketModel = new Ticket();
    }

    public function index()
    {
        echo json_encode($this->ticketModel->getAll());
    }

    public function show($id)
    {
        $ticket = $this->ticketModel->getById($id);
        if ($ticket) {
            echo json_encode($ticket);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ticket not found']);
        }
    }

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['title'], $data['description'], $data['department_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }

        $data['user_id'] = $_SESSION['user']['id']; // from middleware
        if ($this->ticketModel->create($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Ticket submitted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Ticket creation failed']);
        }
    }

    public function assignToSelf($id)
    {
        $user = $_SESSION['user'];
        if ($user['role'] !== 'agent') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if ($this->ticketModel->assignToAgent($id, $user['id'])) {
            echo json_encode(['message' => 'Ticket assigned']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Assignment failed']);
        }
    }

    public function updateStatus($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!in_array($data['status'], ['open', 'in_progress', 'closed'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status']);
            return;
        }

        if ($this->ticketModel->updateStatus($id, $data['status'])) {
            echo json_encode(['message' => 'Status updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed']);
        }
    }
}
