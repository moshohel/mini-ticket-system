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

    public function store($data, $user)
    {
        $user_id = $user['id'] ?? null;
        $department_id = $data['department_id'] ?? null;

        if (!$user_id) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized User with no user id']);
            return;
        }

        if (!$department_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing department_id']);
            return;
        }

        try {
            $this->ticketModel->create([
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'user_id' => $user_id,
                'department_id' => $department_id
            ]);

            echo json_encode(['message' => 'Ticket created']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create ticket: ' . $e->getMessage()]);
        }
    }
}
