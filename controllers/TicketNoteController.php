<?php
require_once __DIR__ . '/../models/TicketNote.php';
require_once __DIR__ . '/../helpers/response.php';

class TicketNoteController
{
    private $noteModel;

    public function __construct()
    {
        $this->noteModel = new TicketNote();
    }

    public function index($ticketId)
    {
        // echo json_encode($this->noteModel->getByTicketId($ticketId));
        jsonResponse($this->noteModel->getByTicketId($ticketId), 'Notes fetched successfully', 200);
    }

    public function store($data, $user)
    {
        $user_id = $user['id'] ?? null;
        $ticket_id = $data['ticket_id'] ?? null;

        if (!$user_id) {
            // http_response_code(401);
            // echo json_encode(['error' => 'Unauthorized User with no user id']);
            jsonResponse(null, 'Unauthorized User with no user id', 401, 'Missing session user id');
            return;
        }

        if (!$ticket_id) {
            // http_response_code(400);
            // echo json_encode(['error' => 'Missing ticket_id']);
            jsonResponse(null, 'Missing ticket_id', 400, 'Missing ticket_id');
            return;
        }

        $saved = $this->noteModel->create([
            'ticket_id' => $data['ticket_id'],
            'user_id' => $user['id'],
            'note' => $data['note'],
        ]);

        if ($saved) {
            // echo json_encode(['message' => 'Note added']);
            jsonResponse(null, 'Note added successfully', 201);
        } else {
            // http_response_code(500);
            // echo json_encode(['error' => 'Failed to save note']);
            jsonResponse(null, 'Failed to save note', 500, 'Database error');
        }
    }
}
