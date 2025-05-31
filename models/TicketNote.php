<?php
require_once __DIR__ . '/../config/database.php';

class TicketNote
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getByTicketId($ticket_id)
    {
        $stmt = $this->conn->prepare("SELECT tn.*, u.name AS author FROM ticket_notes tn JOIN users u ON tn.user_id = u.id WHERE ticket_id = :ticket_id ORDER BY created_at ASC");
        $stmt->execute(['ticket_id' => $ticket_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO ticket_notes (ticket_id, user_id, note)
            VALUES (:ticket_id, :user_id, :note)
        ");
        return $stmt->execute([
            'ticket_id' => $data['ticket_id'],
            'user_id' => $data['user_id'],
            'note' => $data['note'],
        ]);
    }
}
