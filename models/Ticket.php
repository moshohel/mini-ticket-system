<?php
require_once __DIR__ . '/../config/database.php';

class Ticket
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM tickets");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM tickets WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO tickets (title, description, user_id, department_id) VALUES (:title, :description, :user_id, :department_id)");
        return $stmt->execute($data);
    }

    public function assignToAgent($ticketId, $agentId)
    {
        $stmt = $this->conn->prepare("UPDATE tickets SET assigned_to = :agentId, status = 'in_progress' WHERE id = :ticketId");
        return $stmt->execute(['agentId' => $agentId, 'ticketId' => $ticketId]);
    }

    public function updateStatus($ticketId, $status)
    {
        $stmt = $this->conn->prepare("UPDATE tickets SET status = :status WHERE id = :ticketId");
        return $stmt->execute(['status' => $status, 'ticketId' => $ticketId]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM tickets WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
