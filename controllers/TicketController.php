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

    public function assignToSelf($id, $user)
    {
        if (($user['role'] ?? '') !== 'agent') {
            http_response_code(403);
            echo json_encode(['error' => 'Only agents can assign tickets']);
            return;
        }

        if ($this->ticketModel->assignToAgent($id, $user['id'])) {
            echo json_encode(['message' => 'Ticket assigned']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Assignment failed']);
        }
    }

    public function updateStatus($id, $data, $user)
    {
        if (!isset($data['status']) || !in_array($data['status'], ['open', 'in_progress', 'closed'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing status']);
            return;
        }

        if ($this->ticketModel->updateStatus($id, $data['status'])) {
            echo json_encode(['message' => 'Status updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed']);
        }
    }

    public function delete($id, $user)
    {
        if (($user['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Only admin can delete tickets']);
            return;
        }

        if ($this->ticketModel->delete($id)) {
            echo json_encode(['message' => 'Ticket deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Deletion failed']);
        }
    }
}
