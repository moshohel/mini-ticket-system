<?php
require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $password, $role = 'agent')
    {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $password, $role]);
    }
}
