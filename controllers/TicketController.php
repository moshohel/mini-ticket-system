<?php

require_once __DIR__ . '/../models/Ticket.php';
require_once __DIR__ . '/../helpers/response.php';

class TicketController
{
    private $ticketModel;

    public function __construct()
    {
        $this->ticketModel = new Ticket();
    }

    public function index()
    {
        $tickets = $this->ticketModel->getAll();
        jsonResponse($tickets, 'Tickets fetched successfully', 200);
    }

    public function show($id)
    {
        $ticket = $this->ticketModel->getById($id);
        if ($ticket) {
            jsonResponse($ticket, 'Ticket fetched successfully', 200);
        } else {
            jsonResponse(null, 'Ticket not found', 404, 'Not Found');
        }
    }

    public function store($data, $user)
    {
        $user_id = $user['id'] ?? null;
        $department_id = $data['department_id'] ?? null;

        if (!$user_id) {
            jsonResponse(null, 'Unauthorized User with no user id', 401, 'Missing session user id');
            return;
        }

        if (!$department_id) {
            jsonResponse(null, 'Missing department id', 400, 'Missing department_id');
            return;
        }

        try {
            $this->ticketModel->create([
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'user_id' => $user_id,
                'department_id' => $department_id
            ]);

            jsonResponse(null, 'Ticket created successfully', 200);
        } catch (Exception $e) {
            jsonResponse(null, 'Server error Failed to create ticket', 500, $e->getMessage());
        }
    }

    public function assignToSelf($id, $user)
    {
        if (($user['role'] ?? '') !== 'agent') {
            jsonResponse(null, 'Only agents can assign tickets', 403, 'Forbidden');
            return;
        }

        if ($this->ticketModel->assignToAgent($id, $user['id'])) {
            jsonResponse(null, 'Ticket assigned Successfully', 200);
        } else {
            jsonResponse(null, 'Assignment failed', 500, 'Failed to assign ticket');
        }
    }

    public function updateStatus($id, $data, $user)
    {
        if (!isset($data['status']) || !in_array($data['status'], ['open', 'in_progress', 'closed'])) {
            jsonResponse(null, 'Invalid or missing status', 400, 'Invalid status');
            return;
        }

        if ($this->ticketModel->updateStatus($id, $data['status'])) {;
            jsonResponse(null, 'Status updated successfully', 200);
        } else {
            jsonResponse(null, 'Update failed', 500, 'Failed to update ticket status');
        }
    }

    public function delete($id, $user)
    {
        if (($user['role'] ?? '') !== 'admin') {
            jsonResponse(null, 'Only admin can delete tickets', 403, 'Forbidden');
            return;
        }

        if ($this->ticketModel->delete($id)) {
            jsonResponse(null, 'Ticket deleted successfully', 200);
        } else {
            jsonResponse(null, 'Deletion failed', 500, 'Failed to delete ticket');
        }
    }
}
